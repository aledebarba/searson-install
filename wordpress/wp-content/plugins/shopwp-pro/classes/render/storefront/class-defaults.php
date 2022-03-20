<?php

namespace ShopWP\Render\Storefront;

defined('ABSPATH') ?: exit();

use ShopWP\Utils\Data;

class Defaults
{
    public $Render_Attributes;
    public $Products_Defaults;

    public function __construct($Render_Attributes, $Products_Defaults)
    {
        $this->Render_Attributes = $Render_Attributes;
        $this->Products_Defaults = $Products_Defaults;
    }

    public function create_product_query($user_atts) {
        return $this->Products_Defaults->create_product_query($user_atts);
    }

    public function storefront_attributes($attrs)
    {
        return [
            'query' => $this->Render_Attributes->attr($attrs, 'query', '*'),
            'sort_by' => $this->Render_Attributes->attr(
                $attrs,
                'sort_by',
                'TITLE'
            ),
            'reverse' => $this->Render_Attributes->attr(
                $attrs,
                'reverse',
                false
            ),
            'page_size' => $this->Render_Attributes->attr(
                $attrs,
                'page_size',
                10
            ),
            'show_tags' => $this->Render_Attributes->attr(
                $attrs,
                'show_tags',
                true
            ),
            'show_vendors' => $this->Render_Attributes->attr(
                $attrs,
                'show_vendors',
                true
            ),
            'show_types' => $this->Render_Attributes->attr(
                $attrs,
                'show_types',
                true
            ),
            'show_collections' => $this->Render_Attributes->attr(
                $attrs,
                'show_collections',
                true
            ),
            'show_price' => $this->Render_Attributes->attr(
                $attrs,
                'show_price',
                true
            ),
            'show_selections' => $this->Render_Attributes->attr(
                $attrs,
                'show_selections',
                true
            ),
            'show_sorting' => $this->Render_Attributes->attr(
                $attrs,
                'show_sorting',
                true
            ),
            'show_pagination' => $this->Render_Attributes->attr(
                $attrs,
                'show_pagination',
                true
            ),
            'show_options_heading' => $this->Render_Attributes->attr(
                $attrs,
                'show_options_heading',
                true
            ),
            'render_from_server' => $this->Render_Attributes->attr(
                $attrs,
                'render_from_server',
                false
            ),
            'dropzone_payload' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_payload',
                '#shopwp-storefront-payload'
            ),
            'dropzone_options' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_options',
                '#shopwp-storefront-options'
            ),
            'dropzone_selections' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_selections',
                '#shopwp-storefront-selections'
            ),
            'dropzone_sorting' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_sorting',
                '#shopwp-storefront-sort'
            ),
            'dropzone_page_size' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_page_size',
                '#shopwp-storefront-page-size'
            ),            
            'dropzone_heading' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_heading',
                false
            ),
            'dropzone_load_more' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_load_more',
                true
            ),
            'dropzone_loader' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_loader',
                false
            ),
            'dropzone_notices' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_notices',
                false
            ),
            'pagination' => $this->Render_Attributes->attr(
                $attrs,
                'pagination',
                true
            ),
            'no_results_text' => $this->Render_Attributes->attr(
                $attrs,
                'no_results_text',
                'No results found'
            ),
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'description',
            ]),
            'items_per_row' => $this->Render_Attributes->attr(
                $attrs,
                'items_per_row',
                3
            ),
            'limit' => $this->Render_Attributes->attr($attrs, 'limit', false),
            'skip_initial_render' => $this->Render_Attributes->attr(
                $attrs,
                'skip_initial_render',
                false
            ),
            'infinite_scroll' => $this->Render_Attributes->attr(
                $attrs,
                'infinite_scroll',
                false
            ),
            'infinite_scroll_offset' => $this->Render_Attributes->attr(
                $attrs,
                'infinite_scroll_offset',
                -200
            ),
            'data_type' => $this->Render_Attributes->attr(
                $attrs,
                'data_type',
                'products'
            ),
            'price' => $this->Render_Attributes->attr(
                $attrs,
                'price',
                false
            ),
            'connective' => $this->Render_Attributes->attr(
                $attrs,
                'connective',
                'OR'
            ),
            'available_for_sale' => $this->Render_Attributes->attr(
                $attrs,
                'available_for_sale',
                'any'
            ),
            'filter_option_open_on_load' => $this->Render_Attributes->attr(
                $attrs,
                'filter_option_open_on_load',
                false
            ),
            'sorting_options_collections' => $this->Render_Attributes->attr(
                $attrs,
                'sorting_options_collections',
                [
                    [
                        'label' => 'Title (A-Z)',
                        'value' => 'TITLE',
                    ],
                    [
                        'label' => 'Title (Z-A)',
                        'value' => 'TITLE-REVERSE',
                    ],
                    [
                        'label' => 'Price (Low to high)',
                        'value' => 'PRICE',
                    ],
                    [
                        'label' => 'Price (high to low)',
                        'value' => 'PRICE-REVERSE',
                    ],
                    [
                        'label' => 'Best Selling',
                        'value' => 'BEST_SELLING',
                    ],
                    [
                        'label' => 'Recently Added',
                        'value' => 'CREATED',
                    ],
                    [
                        'label' => 'Collection default',
                        'value' => 'COLLECTION_DEFAULT',
                    ],
                    [
                        'label' => 'Manual',
                        'value' => 'MANUAL',
                    ]
                ]
            ),
            'sorting_options_products' => $this->Render_Attributes->attr(
                $attrs,
                'sorting_options_products',
                [
                    [
                        'label' => 'Title (A-Z)',
                        'value' => 'TITLE',
                    ],
                    [
                        'label' => 'Title (Z-A)',
                        'value' => 'TITLE-REVERSE',
                    ],
                    [
                        'label' => 'Price (Low to high)',
                        'value' => 'PRICE',
                    ],
                    [
                        'label' => 'Price (High to low)',
                        'value' => 'PRICE-REVERSE',
                    ],
                    [
                        'label' => 'Best Selling',
                        'value' => 'BEST_SELLING',
                    ],
                    [
                        'label' => 'Recently Added',
                        'value' => 'CREATED_AT',
                    ]
                ]
            ),
            'sorting_options_page_size' => $this->Render_Attributes->attr(
                $attrs,
                'sorting_options_page_size',
                [
                    [
                        'label' => '10',
                        'value' => 10,
                    ],
                    [
                        'label' => '25',
                        'value' => 25,
                    ],
                    [
                        'label' => '50',
                        'value' => 50,
                    ],
                    [
                        'label' => '100',
                        'value' => 100,
                    ]
                ]
            ),
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'storefront'
            ),
            'filterable_price_values' => $this->Render_Attributes->attr(
                $attrs,
                'filterable_price_values',
                [
                    '$0.00 - $15.00',
                    '$15.00 - $25.00',
                    '$25.00 - $50.00',
                    '$50.00 - $100.00',
                    '$100.00 +',
                ]
            ),
            'no_filter_group_found_text' => $this->Render_Attributes->attr(
                $attrs,
                'no_filter_group_found_text',
                'No %s found'
            ),
            'filter_by_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'filter_by_label_text',
                'Filter by:'
            ),
            'page_size_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'page_size_label_text',
                'Page size:'
            ),
            'clear_filter_selections_text' => $this->Render_Attributes->attr(
                $attrs,
                'clear_filter_selections_text',
                'Clear all'
            ),
            'selections_available_for_sale_text' => $this->Render_Attributes->attr(
                $attrs,
                'selections_available_for_sale_text',
                'Available for sale'
            ),
            'sort_by_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'sort_by_label_text',
                'Sort by:'
            ),
            'load_more_collections_busy_text' => $this->Render_Attributes->attr(
                $attrs,
                'load_more_collections_busy_text',
                'Loading...'
            ),
            'load_more_collections_text' => $this->Render_Attributes->attr(
                $attrs,
                'load_more_collections_text',
                'See more'
            ),
            'collections_heading' => $this->Render_Attributes->attr(
                $attrs,
                'collections_heading',
                'Collections'
            ),
            'price_heading' => $this->Render_Attributes->attr(
                $attrs,
                'price_heading',
                'Price'
            ),
            'tags_heading' => $this->Render_Attributes->attr(
                $attrs,
                'tags_heading',
                'Tags'
            ),
            'types_heading' => $this->Render_Attributes->attr(
                $attrs,
                'types_heading',
                'Types'
            ),
            'vendors_heading' => $this->Render_Attributes->attr(
                $attrs,
                'vendors_heading',
                'Vendors'
            ),
        ];
    }

    public function all_storefront_attributes($attrs)
    {
        return apply_filters('shopwp_storefront_default_payload_settings', array_merge(
            $this->Products_Defaults->all_attrs($attrs),
            $this->storefront_attributes($attrs)
        ));
    }

    public function storefront($attrs)
    {
        return $this->Render_Attributes->combine_products_attributes(
            $this->all_storefront_attributes($attrs)
        );
    }
}
