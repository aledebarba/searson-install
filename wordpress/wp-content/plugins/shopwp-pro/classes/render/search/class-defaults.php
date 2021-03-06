<?php

namespace ShopWP\Render\Search;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils\Data;

class Defaults
{
    public $plugin_settings;
    public $Render_Attributes;
    public $Products_Defaults;

    public function __construct(
        $plugin_settings,
        $Render_Attributes,
        $Products_Defaults
    ) {
        $this->plugin_settings = $plugin_settings;
        $this->Render_Attributes = $Render_Attributes;
        $this->Products_Defaults = $Products_Defaults;
    }

    public function create_product_query($user_atts) {
        return $this->Products_Defaults->create_product_query($user_atts);
    }

    public function search_component_attributes($attrs)
    {
        return [
            'render_from_server' => $this->Render_Attributes->attr(
                $attrs,
                'render_from_server',
                false
            ),
            'dropzone_form' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_form',
                false
            ),
            'dropzone_payload' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_payload',
                false
            ),
            'dropzone_loader' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_loader',
                false
            ),
            'dropzone_options' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_options',
                false
            ),
            'dropzone_sorting' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_sorting',
                false
            ),
            'dropzone_heading' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_heading',
                false
            ),
            'dropzone_page_size' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_page_size',
                false
            ),
            'dropzone_load_more' => $this->Render_Attributes->attr(
                $attrs,
                'dropzone_load_more',
                false
            ),
            'pagination' => $this->Render_Attributes->attr(
                $attrs,
                'pagination',
                false
            ),
            'pagination_hide_initial' => $this->Render_Attributes->attr(
                $attrs,
                'pagination_hide_initial',
                true
            ),
            'show_pagination' => $this->Render_Attributes->attr(
                $attrs,
                'show_pagination',
                false
            ),
            'no_results_text' => $this->Render_Attributes->attr(
                $attrs,
                'no_results_text',
                'No search results found'
            ),
            'excludes' => $this->Render_Attributes->attr($attrs, 'excludes', [
                'description',
            ]),
            'connective' => strtoupper(
                $this->Render_Attributes->attr($attrs, 'connective', 'OR')
            ),
            'items_per_row' => $this->Render_Attributes->attr(
                $attrs,
                'items_per_row',
                3
            ),
            'limit' => $this->Render_Attributes->attr($attrs, 'limit', false),
            'skip_initial_render' => $this->Render_Attributes->attr(
                $attrs,
                'skip_initial_render',
                true
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
            'component_type' => $this->Render_Attributes->attr(
                $attrs,
                'component_type',
                'search'
            ),
            'search_by' => $this->Render_Attributes->attr(
                $attrs,
                'search_by',
                $this->plugin_settings['general']['search_by']
            ),
            'search_exact_match' => $this->Render_Attributes->attr(
                $attrs,
                'search_exact_match',
                $this->plugin_settings['general']['search_exact_match']
            ),
            'query' => $this->Render_Attributes->attr($attrs, 'query', '*'),
            'sort_by' => $this->Render_Attributes->attr(
                $attrs,
                'sort_by',
                'title'
            ),
            'reverse' => $this->Render_Attributes->attr(
                $attrs,
                'reverse',
                false
            ),
            'page_size' => $this->Render_Attributes->attr(
                $attrs,
                'page_size',
                $this->plugin_settings['general']['num_posts']
            ),
            'search_placeholder_text' => $this->Render_Attributes->attr(
                $attrs,
                'search_placeholder_text',
                'Search the store'
            ),
            'container_width' => $this->Render_Attributes->attr($attrs, 'container_width', '1300px'),
        ];
    }

    public function all_search_attributes($attrs)
    {
        return apply_filters('shopwp_search_default_payload_settings', array_merge(
            $this->Products_Defaults->all_attrs($attrs),
            $this->search_component_attributes($attrs)
        ));
    }

    public function search($attrs)
    {
        $all_atts = $this->all_search_attributes($attrs);

        return $this->Render_Attributes->combine_products_attributes($all_atts);
    }
}
