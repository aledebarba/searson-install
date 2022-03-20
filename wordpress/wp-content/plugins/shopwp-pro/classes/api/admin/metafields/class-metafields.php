<?php

namespace ShopWP\API\Admin;

use ShopWP\Utils;

if (!defined('ABSPATH')) {
	exit;
}

/*

Convenience wrappers for consuming the Storefront API

*/
class Metafields {

    public function __construct($Admin, $Admin_Queries) {
        $this->Admin = $Admin;
        $this->Admin_Queries = $Admin_Queries;
    }

    public function get_metafields($namespace) {
        return $this->Admin->graphql_api_request($this->Admin_Queries->graph_query_get_metafields($namespace));
    }

    public function show_metafield($key, $namespace) {

        if (empty($key) || empty($namespace)) {
            
            return Utils::wp_error([
                'message_lookup'    => 'Missing key or namespace for metafield',
                'call_method'       => __METHOD__,
                'call_line'         => __LINE__,
            ]);
        }

        $metafields_show_resp = $this->Admin->graphql_api_request($this->Admin_Queries->graph_query_show_metafield($key, $namespace));

        if (is_wp_error($metafields_show_resp)) {
            
            return Utils::wp_error([
                'message_lookup'    => $metafields_show_resp->get_error_message(),
                'call_method'       => __METHOD__,
                'call_line'         => __LINE__,
            ]);
        }

        if (!empty($metafields_show_resp->metafieldStorefrontVisibilityCreate->userErrors)) {

            return Utils::wp_error([
                'message_lookup'    => $metafields_show_resp->metafieldStorefrontVisibilityCreate->userErrors[0]->message,
                'call_method'       => __METHOD__,
                'call_line'         => __LINE__,
            ]);

        }

        return $metafields_show_resp;
        
    }

    public function hide_metafield($metafield_id) {
        return $this->Admin->graphql_api_request($this->Admin_Queries->graph_query_hide_metafield($metafield_id));
    }

}
