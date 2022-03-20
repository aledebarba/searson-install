<?php

namespace ShopWP\API\Items;

use ShopWP\Transients;
use ShopWP\Messages;
use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;
use ShopWP\CPT;

if (!defined('ABSPATH')) {
    exit();
}

class Products extends \ShopWP\API
{
    public function __construct(
        $DB_Settings_General,
        $DB_Settings_Syncing,
        $DB_Tags,
        $DB_Products,
        $Shopify_API,
        $Processing_Products,
        $Processing_Variants,
        $Processing_Tags,
        $Processing_Options,
        $Processing_Images,
        $Admin_API_Variants,
        $API_Counts,
        $plugin_settings,
        $Processing_Database,
        $API_Syncing_Status,
        $Admin_API_Shop,
        $Storefront_Products,
        $API_Items_Collections
    ) {
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->DB_Tags = $DB_Tags;
        $this->DB_Products = $DB_Products;

        $this->Shopify_API = $Shopify_API;

        $this->Processing_Products = $Processing_Products;
        $this->Processing_Variants = $Processing_Variants;
        $this->Processing_Tags = $Processing_Tags;
        $this->Processing_Options = $Processing_Options;
        $this->Processing_Images = $Processing_Images;

        $this->Admin_API_Variants = $Admin_API_Variants;
        $this->API_Counts = $API_Counts;
        $this->plugin_settings = $plugin_settings;
        $this->Processing_Database = $Processing_Database;
        $this->API_Syncing_Status = $API_Syncing_Status;

        $this->Admin_API_Shop = $Admin_API_Shop;
        
        $this->Storefront_Products = $Storefront_Products;

        $this->API_Items_Collections = $API_Items_Collections;
    }


    public function construct_streaming_options($count) {

        $count = (int) $count;
        $items_per_request = (int) $this->plugin_settings['general']['items_per_request'];
        $total_pages = ceil($count / $items_per_request);

        return [
            'current_page' => 1,
            'pages' => $total_pages
        ];
      }




    public function stream_collections($counts) {

        $final = [];

        if (!empty($counts['custom_collections'])) {
            $final['custom_collections'] = $this->API_Items_Collections->get_custom_collections(true);
        }

        if (!empty($counts['smart_collections'])) {
            $final['smart_collections'] = $this->API_Items_Collections->get_smart_collections(true);
        }

        return $final;
        
    }

    public function stream_products($counts, $sync_by_collections) {

        $custom_schema = '
            id
            title
            handle
            seo {
                title
                description
            }
        ';
                
        // Syncing by collections
        if (!empty($sync_by_collections)) {

            $collection_ids = $this->format_collection_ids($sync_by_collections);

            $query_params = [
                'first' => $this->DB_Settings_General->get_items_per_request()
            ];

            $products_to_process = $this->get_all_products_from_collection_ids($collection_ids, $query_params, $custom_schema);

        // Syncing all products
        } else {

            $query_params = [
                'query' => '*',
                'first' => $this->DB_Settings_General->get_items_per_request()
            ];

            $products_to_process = $this->get_all_products($query_params, $custom_schema);

        }   
        
        return $this->handle_response([
            'response' => $products_to_process,
            'warning_message' => 'missing_products_for_page',
            'process_fns' => [
                $this->Processing_Products
            ],
        ]);
        
    }

    public function remove_existing_synced_data() {

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Removing previously synced data ...');
        
        // Kick off the async data deletion 
        $this->Processing_Database->delete_only_synced_data();

        return true;

    }

    public function expire_syncing() {

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Finalizing ...');
        $this->DB_Settings_Syncing->toggle_syncing(0);
        
        return $this->DB_Settings_Syncing->expire_sync();
    }

    public function get_total_collections_count() {

        $is_syncing_collections = $this->DB_Settings_General->get_selective_sync_collections_status();

        if (empty($is_syncing_collections)) {
            return false;
        }
        
        $custom_count = $this->Shopify_API->get_custom_collections_count();

        if (is_wp_error($custom_count)) {
            $this->DB_Settings_Syncing->server_error($custom_count->get_error_message(), __METHOD__, __LINE__);
            die();
        }

        $smart_count = $this->Shopify_API->get_smart_collections_count();

        if (is_wp_error($smart_count)) {
            $this->DB_Settings_Syncing->server_error($smart_count->get_error_message(), __METHOD__, __LINE__);
            die();
        }

        return [
            'smart_collections' => $smart_count['body']->count,
            'custom_collections' => $custom_count['body']->count
        ];

    }

    /*

	Sync detail pages

	*/
    public function sync_product_detail_pages($request)
    {
        
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->DB_Settings_Syncing->expire_sync();
            return;
        }
        
        $selective_sync = $this->DB_Settings_General->selective_sync_status();
        $is_syncing_by_collections = $this->DB_Settings_General->sync_by_collections();
        $sync_by_collections = maybe_unserialize($is_syncing_by_collections);

        $total_products_count = [];
        $total_collections_count = [];

        // If syncing collections ...
        if ($selective_sync['smart_collections'] && $selective_sync['custom_collections']) {
            // If syncing by collections
            if (!empty($sync_by_collections)) {

                $smart_collections = [];
                $custom_collections = [];

                foreach ($sync_by_collections as $collection) {
                    if (empty($collection['smart'])) {
                        $custom_collections['custom_collections'][] = $collection;
                    } else {
                        $smart_collections['smart_collections'][] = $collection;
                    }
                }

                if (!empty($custom_collections['custom_collections'])) {
                    $total_collections_count['custom_collections'] = count($custom_collections['custom_collections']);
                }

                if (!empty($smart_collections['smart_collections'])) {
                    $total_collections_count['smart_collections'] = count($smart_collections['smart_collections']);
                }

                $this->API_Counts->set_syncing_counts($total_collections_count, ['webhooks', 'media']);

                if (!empty($custom_collections)) {
                    $this->API_Items_Collections->process_custom_collections($custom_collections);
                }

                if (!empty($smart_collections)) {
                    $this->API_Items_Collections->process_smart_collections($smart_collections);
                }

            } else {

                $this->DB_Settings_Syncing->set_current_syncing_step_text('Getting collections count ...');

                $total_collections_count = $this->get_total_collections_count();
                
                if (!empty($total_collections_count)) {
                    
                    $save_collections_count_result = $this->API_Counts->set_syncing_counts($total_collections_count, ['webhooks', 'media']);

                    if (is_wp_error($save_collections_count_result)) {
                        die();
                    }
                }
            }            
        }


        if ($selective_sync['products']) {
            $this->DB_Settings_Syncing->set_current_syncing_step_text('Getting products count ...');
            $total_products_count = $this->get_products_count();
        }

        if (empty($total_collections_count) && empty($total_products_count)) {
            $this->DB_Settings_Syncing->expire_sync();
            return wp_send_json_success();
        }


        if ($selective_sync['products']) {

            if (!empty($total_products_count)) {

                if (is_wp_error($total_products_count)) {
                    $this->DB_Settings_Syncing->server_error($total_products_count->get_error_message(), __METHOD__, __LINE__);
                    die();
                }

                if (!empty($total_collections_count)) {
                    $final_count = array_merge($total_products_count, $total_collections_count);

                } else {
                    $final_count = $total_products_count;
                }

                $save_result = $this->API_Counts->set_syncing_counts($final_count, ['webhooks', 'media']);

                if (is_wp_error($save_result)) {
                    die();
                }

                $this->DB_Settings_Syncing->set_current_syncing_step_text('Syncing detail pages ...');

                // Fetch products and start the processor
                $stream_products_result = $this->stream_products($total_products_count['products'], $sync_by_collections);

            }
        }

        // Lands here if we need to sync all collections
        if (!empty($total_collections_count) && empty($sync_by_collections)) {

            $this->DB_Settings_Syncing->set_current_syncing_step_text('Syncing detail pages ...');
           
            // Fetch all collections and start the processor
            $stream_collections_result = $this->stream_collections($total_collections_count);
        }

        return wp_send_json_success();

    }


    public function get_product_listings_count_by_collection_ids()
    {
        $products_count = [];
        $collections = $this->DB_Settings_General->get_sync_by_collections_ids();
        $errors = false;

        foreach ($collections as $collection) {

            $response = $this->Shopify_API->get_product_listings_count_by_collection_id(
                $collection['id']
            );

            if (is_wp_error($response)) {
                $errors = $response;
                break;
            }

            $response_body = $this->Shopify_API->sanitize_response(
                $response['body']
            );

            if (Utils::has($response_body, 'count')) {
                $products_count[] = $response_body->count;
            }
        }

        if ($errors) {
            return $errors;
        }

        return [
            'count' => array_sum($products_count),
        ];
    }

    
    public function get_products_count()
    {

        if ($this->DB_Settings_General->is_syncing_by_collection()) {
            $resp = $this->get_product_ids_by_collection_ids();

            if (is_wp_error($resp)) {
                return $resp;
            }

            $count = count($resp);

        } else {
            $resp = $this->Shopify_API->get_product_listings_count();

            if (is_wp_error($resp)) {
                return $resp;
            }
                        
            $count = (int) $resp['body']->count;
        }

        return [
            'products' => $count
        ];
    }

    
    public function get_product_ids_by_collection_id(
        $collection_id,
        $page_link = false,
        $limit = false,
        $combined_product_ids = []
    ) {

        $response = $this->Shopify_API->get_products_listing_product_ids_by_collection_id_per_page(
            $collection_id,
            $limit,
            $page_link
        );

        if (is_wp_error($response)) {
            return $response;
        }

        // No additional pages left
        if (!$response) {
            return $combined_product_ids;
        }

        $response_body = $this->Shopify_API->sanitize_response(
            $response['body']
        );

        $new_product_ids = $response_body->product_listings;

        $new_array = array_map(function($product_id) {
            return $product_id->product_id;
        }, $new_product_ids);

        // Save the result in memory
        $combined_product_ids = array_merge(
            $combined_product_ids,
            $new_array
        );

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Found ' . count($combined_product_ids) . ' products to sync ...');

        if (!$this->Shopify_API->has_pagination($response)) {
            return $combined_product_ids;
        }

        $page_link = $this->Shopify_API->get_pagination_link($response);

        return $this->get_product_ids_by_collection_id(
            $collection_id,
            $page_link,
            $limit,
            $combined_product_ids
        );
    }

    public function get_product_ids_by_collection_ids()
    {
        $collections = maybe_unserialize(
            $this->DB_Settings_General->sync_by_collections()
        );
     
        $all_product_ids = [];

        $limit = $this->DB_Settings_General->get_items_per_request();

        if (empty($collections)) {
            return [];
        }

        foreach ($collections as $collection) {

            $this->DB_Settings_Syncing->set_current_syncing_step_text('Getting products from collection: ' . $collection['title'] . ' ...');

            $collection_product_ids = $this->get_product_ids_by_collection_id(
                $collection['id'],
                false,
                $limit
            );

            if (is_wp_error($collection_product_ids)) {
                return $collection_product_ids;
            }

            $all_product_ids = array_merge(
                $all_product_ids,
                $collection_product_ids
            );
        }

        return array_values(array_unique($all_product_ids));

    }

    public function update_total_count_with_duplicates($new_total_count_to_set)
    {
        return $this->DB_Settings_Syncing->update_col('syncing_totals_products', $new_total_count_to_set);
    }

    public function has_duplicates_product_ids(
        $all_product_ids = [],
        $current_totals = 0
    ) {
        if (empty($all_product_ids) || !is_int($current_totals)) {
            return false;
        }

        $num_of_unique_ids = count(array_count_values($all_product_ids));
        $num_of_all_ids = count($all_product_ids);

        if ($num_of_all_ids > $num_of_unique_ids) {
            $difference = $num_of_all_ids - $num_of_unique_ids;

            $new_total_count_to_set = $current_totals - $difference;

            // New totals should never be negative
            if ($new_total_count_to_set < 0) {
                return false;
            }

            return $new_total_count_to_set;
        }

        return false;
    }


    /*

	Gets published product ids as a URL param string

	*/
    public function get_published_product_ids_as_param($current_page)
    {
        $product_ids = $this->DB_Settings_Syncing->get_published_product_ids();

        if (empty($product_ids)) {
            return false;
        }

        $limit = $this->DB_Settings_General->get_items_per_request();

        return $this->Shopify_API->create_param_ids(
            $product_ids,
            $limit,
            $current_page
        );
    }

    /*

	Gets products by page

	*/
    public function get_products_per_page($current_page)
    {

        $product_ids_comma_string = $this->get_published_product_ids_as_param($current_page);
        $limit = $this->DB_Settings_General->get_items_per_request();

        $response = $this->Shopify_API->get_products_per_page(
            $product_ids_comma_string,
            $limit
        );

        return $this->Shopify_API->pre_response_check($response);
    }

    public function get_all_products_vendors()
    {
        return [
            'vendors' => $this->DB_Products->get_unique_vendors(),
        ];
    }

    public function get_all_products_types()
    {
        return [
            'types' => $this->DB_Products->get_unique_types(),
        ];
    }

    public function normalize_filter_data($data, $access_key) {
        return array_map(function($item) {
            return $item->node;
        }, $data->shop->{$access_key}->edges);
    }


    public function get_all_product_tags($request) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {
            $cached_tags = Transients::get('shopwp_all_tags');

            if (!empty($cached_tags)) {
                return wp_send_json_success($cached_tags);
            }
        }

        $tags = $this->Admin_API_Shop->get_tags();

        if (is_wp_error($tags)) {
            return $this->handle_response($tags);
        }

        $tags = $this->normalize_filter_data($tags, 'productTags');

        if ($cached_enabled) {
            Transients::set('shopwp_all_tags', $tags, $data_cache_length);
        }

        return wp_send_json_success($tags);
    }

    public function get_all_product_vendors($request) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {
            $cached_vendors = Transients::get('shopwp_all_vendors');

            if (!empty($cached_vendors)) {
                return wp_send_json_success($cached_vendors);
            }
        }

        $vendors = $this->Admin_API_Shop->get_vendors();

        if (is_wp_error($vendors)) {
            return $this->handle_response($vendors);
        }

        $vendors = $this->normalize_filter_data($vendors, 'productVendors');

        if ($cached_enabled) {
            Transients::set('shopwp_all_vendors', $vendors, $data_cache_length);
        }

        return wp_send_json_success($vendors);

    }

    public function get_all_product_types($request) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_types = Transients::get('shopwp_all_types');

            if (!empty($cached_types)) {
                return wp_send_json_success($cached_types);
            }

        }

        $types = $this->Admin_API_Shop->get_product_types();

        if (is_wp_error($types)) {
            return $this->handle_response($types);
        }

        $types = $this->normalize_filter_data($types, 'productTypes');

        if ($cached_enabled) {
            Transients::set('shopwp_all_types', $types, $data_cache_length);
        }

        return wp_send_json_success($types);
    }

    public function get_product_by_id_query($request) {

        $storefront_id = $request->get_param('id');

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_' . $storefront_id));

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }

        $result = $this->Storefront_Products->get_product_by_id($storefront_id);

        if (is_wp_error($result)) {
            return $this->handle_response($result);
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $storefront_id, \maybe_serialize($result), $data_cache_length);
        }

        return \wp_send_json_success($result);

    }   

    /*
    
    Public API Method
    
    */
    public function get_products($query_params) {

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $hash = Utils::hash($query_params, true);

            $cached_query = Transients::get('shopwp_query_' . $hash);

            $cached_query = \maybe_unserialize($cached_query);

            if (!empty($cached_query)) {
                return $cached_query;
            }
        }

        $final_query_params = $this->public_api_default_values($query_params);

        $result = $this->Storefront_Products->get_products($final_query_params, $final_query_params['schema']);

        if (is_wp_error($result)) {
            return $result;
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $hash, \maybe_serialize($result), $data_cache_length);
        }

        return $result;

    }

    /*
    
    Public API Method
    
    */
    public function get_product($params) {
        
        $storefront_id = $this->create_product_storefront_id($params);
        $schema = isset($params['schema']) ? $params['schema'] : false;
        $cache_id = md5($schema) . md5($storefront_id);

        if (empty($storefront_id)) {
            return Utils::wp_error('No storefront id provided');
        }

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_' . $cache_id));

            if (!empty($cached_query)) {
                return $cached_query;
            }
        }

        $result = $this->Storefront_Products->get_product_by_id($storefront_id, $schema);

        if (is_wp_error($result)) {
            return $result;
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $cache_id, \maybe_serialize($result), $data_cache_length);
        }

        return $result;

    }

    public function format_collection_ids($collection_ids) {
        return array_map(function($collection_id) {

            if (is_array($collection_id)) {
                return 'gid://shopify/Collection/' . $collection_id['id'];
            } else {
                return 'gid://shopify/Collection/' . $collection_id;
            }
            
        }, $collection_ids);
    }

    public function format_collections_data($result) {
        return array_reduce($result, function($carry, $current) {

            $carry[] = [
                'collection' => $current->id,
                'products' => $current->products->edges
            ];

            return $carry;

        }, []);
    }    

    

    public function get_all_products_from_collection_id($query_params, $custom_schema = false, $has_next_page = true, $total_products = []) {

        $result = $this->Storefront_Products->get_products_from_collection_id($query_params, $custom_schema);

        if (is_wp_error($result)) {
            return $result;
        }

        if (empty($result->nodes)) {
            return $total_products;
        }

        $products = $result->nodes[0]->products->edges;

        $only_products = [];

        foreach ($products as $product) {
            $only_products[] = $product->node;
        }

        $total_products = array_merge($total_products, $only_products);

        $last_cursor_id = $this->Shopify_API->get_last_cursor($products);

        $has_next_page = $this->Shopify_API->has_next_page($result->nodes[0]->products);

        if ($has_next_page) {
            $query_params['cursor'] = $last_cursor_id;

            return $this->get_all_products_from_collection_id($query_params, $custom_schema, $has_next_page, $total_products);

        } else {
            return $total_products;
        }
    }

    public function get_all_products($query_params, $custom_schema, $has_next_page = false, $total_products = []) {

        $result = $this->Storefront_Products->get_products($query_params, $custom_schema);

        if (is_wp_error($result)) {
            return $result;
        }

        if (empty($result)) {
            return $total_products;
        }

        $products = $result->products->edges;

        $total_products = array_merge($total_products, $products);

        $last_cursor_id = $this->Shopify_API->get_last_cursor($products);

        $has_next_page = $this->Shopify_API->has_next_page($result->products);

        if ($has_next_page) {
            $query_params['cursor'] = $last_cursor_id;

            return $this->get_all_products($query_params, $custom_schema, $has_next_page, $total_products);

        } else {

            return array_values(array_map(function($product) {
                return $product->node;
            }, $total_products));
        }

    }

    public function get_all_products_from_collection_ids($collection_ids, $query_params, $custom_schema = false) {
    
        $final_all_products = [];

        foreach ($collection_ids as $collections_id) {

            $query_params['ids'] = [$collections_id];

            $result = $this->get_all_products_from_collection_id($query_params, $custom_schema);

            if (is_wp_error($result)) {
                $final_all_products = $result;
                break;
            }

            $final_all_products = array_merge($final_all_products, $result);
            
        }

        if (is_array($final_all_products)) {
            return array_values(array_unique($final_all_products, SORT_REGULAR));
        }

        return $final_all_products;
    }

    public function public_api_default_values($user_data) {
        
        $defaults = [
            'first'     => isset($user_data['page_size']) ? $user_data['page_size'] : 10,
            'reverse'   => isset($user_data['reverse']) ? $user_data['reverse'] : false,
            'sortKey'   => isset($user_data['sort_by']) ? $user_data['sort_by'] : 'TITLE',
            'schema'    => isset($user_data['schema']) ? $user_data['schema'] : false,
            'cursor'    => false
        ];

        if (isset($user_data['query'])) {
            $defaults['query'] = $user_data['query'];
        }

        return $defaults;
    }

    /*
    
    Public API Method
    
    */
    public function get_products_by_collection_ids($params)
    {
        if (empty($params) || empty($params['collection_ids']) || !is_array($params['collection_ids'])) {
            return [];
        }

        $cache_id = md5(implode("-", $params['collection_ids']));

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_query = \maybe_unserialize(Transients::get('shopwp_query_' . $cache_id));

            if (!empty($cached_query)) {
                return $cached_query;
            }
        }
        

        $collection_ids = $this->format_collection_ids($params['collection_ids']);

        $query_params = $this->public_api_default_values($params);

        $all_products = $this->get_all_products_from_collection_ids($collection_ids, $query_params, $query_params['schema']);

        if (is_wp_error($all_products)) {
            return $all_products;
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $cache_id, \maybe_serialize($all_products), $data_cache_length);
        }

        return $all_products;

    }

    public function create_product_storefront_id($params) {

        $prefix = 'gid://shopify/Product/';

        if (!is_array($params) | empty($params)) {
            return false;
        }

        if (isset($params['post_id'])) {
            $product_id = get_post_meta($params['post_id'], 'product_id', true);

            if (empty($product_id)) {
                return false;
            }

            $storefront_id = $prefix . $product_id;

        } else if (isset($params['product_id'])) {
            $storefront_id = $prefix . $params['product_id'];

        } else if (isset($params['storefront_id'])) {
            $storefront_id = $params['storefront_id'];

        } else {
            $storefront_id = false;
        }

        return $storefront_id;
        
    }

    public function get_products_query($request) {

        $query_params = $request->get_param('queryParams');
        
        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {
            $hash = Utils::hash($query_params, true);

            $cached_query = Transients::get('shopwp_query_' . $hash);

            $cached_query = \maybe_unserialize($cached_query);

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }

        $result = $this->Storefront_Products->get_products($query_params);

        if (is_wp_error($result)) {
            return $this->handle_response($result);
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $hash, \maybe_serialize($result), $data_cache_length);
        }

        return \wp_send_json_success($result);

    }

    public function build_collection_id_obj($collection_title, $cached_collections_list, $found_key) {
        return [
            'label' => $collection_title,
            'id' => base64_encode('gid://shopify/Collection/' . $cached_collections_list[$found_key]->id)
        ];
    }

    public function adjust_query_params_for_products_by_collection_titles($query_params) {
        
        $supplied_titles = $query_params['collection_titles'];

        if (is_string($supplied_titles)) {
            $supplied_titles = [strtolower($supplied_titles)];
        } else {
            $supplied_titles = array_map('strtolower', $supplied_titles);
        }

        $cached_collections_list = maybe_unserialize(Transients::get('shopwp_all_collections'));

        if (empty($cached_collections_list)) {
            $cached_collections_list = $this->API_Items_Collections->get_all_collections(false);
        }

        $new_stuff = array_filter(array_map(function($collection_title) use($cached_collections_list) {

            $cached_collection_titles_to_search = array_map('strtolower', array_column($cached_collections_list, 'title'));

            $found_key = array_search($collection_title, $cached_collection_titles_to_search);

            if ($found_key === false) {
                return false;
            }

            return $this->build_collection_id_obj($collection_title, $cached_collections_list, $found_key);

        }, $supplied_titles));

        return $new_stuff;

    }
    
    public function get_products_from_collections($request) {

        $query_params = $request->get_param('queryParamsCollectionProducts');

        /*
        
        If the user is searching for products based on collection title, then
        we need to transform the data into ids. To do this, we need to first look 
        to see if they have a cached collections response. If they do, we can use that 
        to find the collection id. 

        If they don't, we need to fetch all the collections, cache them, and find the 
        id that way.
        
        */
        if (!empty($query_params['collection_titles'])) {

            $query_params['ids'] = $this->adjust_query_params_for_products_by_collection_titles($query_params);

            if (empty($query_params['ids'])) {
                return \wp_send_json_success([]);
            }

            $hash = Utils::hash($query_params, true);

        } else {
            $hash = Utils::hash($query_params, true);
        }

        $cached_enabled = $this->DB_Settings_General->get_col_val('enable_data_cache', 'bool');
        $data_cache_length = $this->DB_Settings_General->get_col_val('data_cache_length', 'int');

        if ($cached_enabled) {

            $cached_query = Transients::get('shopwp_query_' . $hash);

            $cached_query = \maybe_unserialize($cached_query);

            if (!empty($cached_query)) {
                return \wp_send_json_success($cached_query);
            }
        }
        
        
        if (!empty($query_params['query'])) {

            // Called from either shortcode or Render API
            $result = $this->Storefront_Products->get_products_from_collection_id($query_params);

        } else {

            // Called from Storefront collections filter
            $result = $this->get_all_products_from_collection_ids($query_params['ids'], $query_params);

            $result = [
                'nodes' => [[
                    'products' => [
                        'cursor' => '',
                        'edges' => $result,
                        'pageInfo' => [
                                'hasNextPage' => false,
                                'hasPreviousPage' => false
                            ]
                    ]
                ]]
            ];
        }

        if (is_wp_error($result)) {
            return $this->handle_response($result);
        }

        if ($cached_enabled) {
            Transients::set('shopwp_query_' . $hash, \maybe_serialize($result), $data_cache_length);
        }

        return \wp_send_json_success($result);
        
    }


    public function get_variant_inventory_management($data)
    {
        $variant_ids = $data['ids'];

        if (empty($data) || empty($data['ids'])) {
            return $this->handle_response(Utils::wp_error('No ids found'));
        }

        $response = $this->Admin_API_Variants->get_variants_inventory_tracked(
            $data['ids']
        );

        if (is_wp_error($response)) {
            return $this->handle_response([
                'response' => Utils::wp_error($response->get_error_message()),
            ]);
        }

        if (empty($response) || !property_exists($response, 'nodes')) {
            return [];
        }

        $data = array_filter($response->nodes);

        return $data;
    }

    public function register_route_products_ids()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/products/ids',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_published_product_ids'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_products_count()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/products/count',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_products_count'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_get_variant_inventory_management()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/products/variants/inventory_management',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_variant_inventory_management'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_get_all_product_tags()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/products/tags',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_all_product_tags'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_get_all_product_vendors()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/products/vendors',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_all_product_vendors'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }
    
    public function register_route_get_all_product_types()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/products/types',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_all_product_types'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }    

    public function register_route_sync_product_detail_pages()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/product_detail_pages',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'sync_product_detail_pages'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_remove_existing_synced_data()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/remove',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'remove_existing_synced_data'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_expire_sync()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/syncing/expire',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'expire_syncing'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_get_products()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/query/products',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_products_query'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_get_product_by_id()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/query/product/id',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_product_by_id_query'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_get_products_from_collections()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/query/products/collections',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_products_from_collections'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {

        add_action('rest_api_init', [$this, 'register_route_sync_product_detail_pages']);
        add_action('rest_api_init', [$this, 'register_route_remove_existing_synced_data']);
        add_action('rest_api_init', [$this, 'register_route_expire_sync']);

        add_action('rest_api_init', [$this, 'register_route_products_ids']);
        add_action('rest_api_init', [$this, 'register_route_products_count']);
        

        add_action('rest_api_init', [
            $this,
            'register_route_get_variant_inventory_management',
        ]);

        add_action('rest_api_init', [$this, 'register_route_get_all_product_tags']);
        add_action('rest_api_init', [$this, 'register_route_get_all_product_vendors']);
        add_action('rest_api_init', [$this, 'register_route_get_all_product_types']);

        add_action('rest_api_init', [$this, 'register_route_get_products']);
        add_action('rest_api_init', [$this, 'register_route_get_product_by_id']);
        add_action('rest_api_init', [$this, 'register_route_get_products_from_collections']);

    }
}
