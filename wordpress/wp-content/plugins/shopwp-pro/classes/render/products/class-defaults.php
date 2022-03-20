<?php

namespace ShopWP\Render\Products;

use ShopWP\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Defaults
{
    public $plugin_settings;
    public $Render_Attributes;

    public function __construct($plugin_settings, $Render_Attributes)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Attrs = $Render_Attributes;
    }

    public function lowercase_filter_params($filter_params)
    {
        return array_map(function ($value) {
            if (is_array($value)) {
                return $this->lowercase_filter_params($value);
            }

            if (is_string($value)) {
                return strtolower($value);
            }

            return $value;
        }, $filter_params);
    }

    public function create_product_query($user_atts)
    {

        $filter_params = $this->Attrs->get_products_filter_params_from_shortcode(
            $user_atts
        );

        if (!isset($user_atts['connective'])) {
            if (empty($user_atts)) {
                $user_atts = [];
            }

            $user_atts['connective'] = 'AND';
        }

        $final_query = $this->Attrs->build_query(
            $this->lowercase_filter_params($filter_params),
            $user_atts
        );

        return $final_query;
    }

    public function product_add_to_cart($attrs = [])
    {
        return array_replace($this->all_attrs($attrs), [
            'excludes' => $this->Attrs->attr($attrs, 'excludes', [
                'title',
                'pricing',
                'description',
                'images',
            ]),
            'component_type' => $this->Attrs->attr(
                $attrs,
                'component_type',
                'products/buy-button'
            ),
            'is_single_component' => $this->Attrs->attr(
                $attrs,
                'is_single_component',
                true
            ),
            'link_to' => $this->Attrs->attr(
                $attrs,
                'link_to',
                'none'
            ),
        ]);
    }

    public function product_buy_button($attrs)
    {
        return $this->product_add_to_cart($attrs);
    }

    public function product_title($attrs)
    {
        return array_replace($this->all_attrs($attrs), [
            'excludes' => $this->Attrs->attr($attrs, 'excludes', [
                'description',
                'buy-button',
                'images',
                'pricing',
            ]),
            'component_type' => $this->Attrs->attr(
                $attrs,
                'component_type',
                'products/title'
            ),
            'is_single_component' => $this->Attrs->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function product_description($attrs)
    {
        return array_replace($this->all_attrs($attrs), [
            'excludes' => $this->Attrs->attr($attrs, 'excludes', [
                'title',
                'buy-button',
                'images',
                'pricing',
            ]),
            'component_type' => $this->Attrs->attr(
                $attrs,
                'component_type',
                'products/description'
            ),
            'is_single_component' => $this->Attrs->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function product_pricing($attrs)
    {
        return array_replace($this->all_attrs($attrs), [
            'excludes' => $this->Attrs->attr($attrs, 'excludes', [
                'title',
                'buy-button',
                'images',
                'description',
            ]),
            'component_type' => $this->Attrs->attr(
                $attrs,
                'component_type',
                'products/pricing'
            ),
            'is_single_component' => $this->Attrs->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function product_gallery($attrs)
    {
        return array_replace($this->all_attrs($attrs), [
            'excludes' => $this->Attrs->attr($attrs, 'excludes', [
                'title',
                'pricing',
                'description',
                'buy-button',
            ]),
            'component_type' => $this->Attrs->attr(
                $attrs,
                'component_type',
                'products/images'
            ),
            'is_single_component' => $this->Attrs->attr(
                $attrs,
                'is_single_component',
                true
            ),
        ]);
    }

    public function products($attrs)
    {
        return $this->all_attrs($attrs);
    }

    public function all_attrs($attrs = [])
    {
        return apply_filters('shopwp_products_default_payload_settings', [
            'query' => $this->Attrs->attr($attrs, 'query', '*'),
            'sort_by' => $this->Attrs->attr($attrs, 'sort_by', 'title'),
            'reverse' => $this->Attrs->attr($attrs, 'reverse', false),
            'page_size' => $this->Attrs->attr($attrs, 'page_size', $this->plugin_settings['general']['num_posts']),
            'product' => $this->Attrs->attr($attrs, 'product', false),
            'product_id' => $this->Attrs->attr($attrs, 'product_id', false),
            'post_id' => $this->Attrs->attr($attrs, 'post_id', false),
            'available_for_sale' => $this->Attrs->attr($attrs, 'available_for_sale', 'any'),
            'product_type' => $this->Attrs->attr($attrs, 'product_type', false),
            'tag' => $this->Attrs->attr($attrs, 'tag', false),
            'collection' => $this->Attrs->attr($attrs, 'collection', false),
            'title' => $this->Attrs->attr($attrs, 'title', false),
            'title_color' => $this->Attrs->attr($attrs, 'title_color', '#111'),
            'title_type_font_family' => $this->Attrs->attr($attrs, 'title_type_font_family', false),
            'title_type_font_size' => $this->Attrs->attr($attrs, 'title_type_font_size', false),
            'title_type_font_weight' => $this->Attrs->attr($attrs, 'title_type_font_weight', false),
            'title_type_text_transform' => $this->Attrs->attr($attrs, 'title_type_text_transform', false),
            'title_type_font_style' => $this->Attrs->attr($attrs, 'title_type_font_style', false),
            'title_type_text_decoration' => $this->Attrs->attr($attrs, 'title_type_text_decoration', false),
            'title_type_line_height' => $this->Attrs->attr($attrs, 'title_type_line_height', false),
            'title_type_letter_spacing' => $this->Attrs->attr($attrs, 'title_type_letter_spacing', false),
            'description_length' => $this->Attrs->attr($attrs, 'description_length', false),
            'description_color' => $this->Attrs->attr($attrs, 'description_color', '#111'),
            'description_type_font_family' => $this->Attrs->attr($attrs, 'description_type_font_family', false),
            'description_type_font_size' => $this->Attrs->attr($attrs, 'description_type_font_size', false),
            'description_type_font_weight' => $this->Attrs->attr($attrs, 'description_type_font_weight', false),
            'description_type_text_transform' => $this->Attrs->attr($attrs, 'description_type_text_transform', false),
            'description_type_font_style' => $this->Attrs->attr($attrs, 'description_type_font_style', false),
            'description_type_text_decoration' => $this->Attrs->attr($attrs, 'description_type_text_decoration', false),
            'description_type_line_height' => $this->Attrs->attr($attrs, 'description_type_line_height', false),
            'description_type_letter_spacing' => $this->Attrs->attr($attrs, 'description_type_letter_spacing', false),
            'variants_price' => $this->Attrs->attr($attrs, 'variants_price', false),
            'vendor' => $this->Attrs->attr($attrs, 'vendor', false),
            'post_meta' => $this->Attrs->attr($attrs, 'post_meta', false),
            'connective' => $this->Attrs->attr($attrs, 'connective', 'OR'),
            'render_from_server' => $this->Attrs->attr($attrs, 'render_from_server', false),
            'limit' => $this->Attrs->attr($attrs, 'limit', false),
            'random' => $this->Attrs->attr($attrs, 'random', false),
            'excludes' => $this->Attrs->attr($attrs, 'excludes', ['description']),
            'items_per_row' => $this->Attrs->attr($attrs, 'items_per_row', 3),
            'grid_column_gap' => $this->Attrs->attr($attrs, 'grid_column_gap', '20px'),
            'no_results_text' => $this->Attrs->attr($attrs, 'no_results_text', 'No products left to show'),
            'align_height' => $this->Attrs->attr($attrs, 'align_height', false),
            'pagination' => $this->Attrs->attr($attrs, 'pagination', true),
            'dropzone_page_size' => $this->Attrs->attr($attrs, 'dropzone_page_size', false),
            'dropzone_load_more' => $this->Attrs->attr($attrs, 'dropzone_load_more', false),
            'dropzone_product_buy_button' => $this->Attrs->attr($attrs, 'dropzone_product_buy_button', false),
            'dropzone_product_title' => $this->Attrs->attr($attrs, 'dropzone_product_title', false),
            'dropzone_product_description' => $this->Attrs->attr($attrs, 'dropzone_product_description', false),
            'dropzone_product_pricing' => $this->Attrs->attr($attrs, 'dropzone_product_pricing', false),
            'dropzone_product_gallery' => $this->Attrs->attr($attrs, 'dropzone_product_gallery', false),
            'skip_initial_render' => $this->Attrs->attr($attrs, 'skip_initial_render', false),
            'data_type' => $this->Attrs->attr($attrs, 'data_type', 'products'),
            'infinite_scroll' => $this->Attrs->attr($attrs, 'infinite_scroll', false),
            'infinite_scroll_offset' => $this->Attrs->attr($attrs, 'infinite_scroll_offset', -200),
            'is_single_component' => $this->Attrs->attr($attrs, 'is_single_component', false),
            'is_singular' => $this->Attrs->attr($attrs, 'is_singular', is_singular(SHOPWP_PRODUCTS_POST_TYPE_SLUG)),
            'link_to' => $this->Attrs->attr($attrs, 'link_to', $this->plugin_settings['general']['products_link_to']),
            'link_target' => $this->Attrs->attr($attrs, 'link_target', $this->plugin_settings['general']['products_link_target']),
            'link_with_buy_button' => $this->Attrs->attr($attrs, 'link_with_buy_button', false),
            'direct_checkout' => $this->Attrs->attr($attrs, 'direct_checkout', false),
            'html_template' => $this->Attrs->attr($attrs, 'html_template', false),
            'component_type' => $this->Attrs->attr($attrs, 'component_type', 'products'),
            'full_width' => $this->Attrs->attr($attrs, 'full_width', false),
            'keep_commas' => $this->Attrs->attr($attrs, 'keep_commas', false),
            'show_price_under_variant_button' => $this->Attrs->attr($attrs, 'show_price_under_variant_button', false),
            'add_to_cart_button_text' => $this->Attrs->attr($attrs, 'add_to_cart_button_text', SHOPWP_DEFAULT_ADD_TO_CART_TEXT),
            'add_to_cart_button_text_color' => $this->Attrs->attr($attrs, 'add_to_cart_button_text_color', false),
            'add_to_cart_button_color' => $this->Attrs->attr($attrs, 'add_to_cart_button_color', $this->plugin_settings['general']['add_to_cart_color']),
            'add_to_cart_button_type_font_family' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_font_family', false),
            'add_to_cart_button_type_font_size' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_font_size', false),
            'add_to_cart_button_type_font_weight' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_font_weight', false),
            'add_to_cart_button_type_text_transform' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_text_transform', false),
            'add_to_cart_button_type_font_style' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_font_style', false),
            'add_to_cart_button_type_text_decoration' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_text_decoration', false),
            'add_to_cart_button_type_line_height' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_line_height', false),
            'add_to_cart_button_type_letter_spacing' => $this->Attrs->attr($attrs, 'add_to_cart_button_type_letter_spacing', false),
            'variant_dropdown_button_color' => $this->Attrs->attr($attrs, 'variant_dropdown_button_color', $this->plugin_settings['general']['variant_color']),
            'variant_dropdown_text_color' => $this->Attrs->attr($attrs, 'variant_dropdown_text_color', '#FFFFFF'),
            'variant_dropdown_type_font_family' => $this->Attrs->attr($attrs, 'variant_dropdown_type_font_family', false),
            'variant_dropdown_type_font_size' => $this->Attrs->attr($attrs, 'variant_dropdown_type_font_size', false),
            'variant_dropdown_type_font_weight' => $this->Attrs->attr($attrs, 'variant_dropdown_type_font_weight', false),
            'variant_dropdown_type_text_transform' => $this->Attrs->attr($attrs, 'variant_dropdown_type_text_transform', false),
            'variant_dropdown_type_font_style' => $this->Attrs->attr($attrs, 'variant_dropdown_type_font_style', false),
            'variant_dropdown_type_text_decoration' => $this->Attrs->attr($attrs, 'variant_dropdown_type_text_decoration', false),
            'variant_dropdown_type_line_height' => $this->Attrs->attr($attrs, 'variant_dropdown_type_line_height', false),
            'variant_dropdown_type_letter_spacing' => $this->Attrs->attr($attrs, 'variant_dropdown_type_letter_spacing', false),
            'variant_style' => $this->Attrs->attr($attrs, 'variant_style', $this->plugin_settings['general']['variant_style']),
            'hide_quantity' => $this->Attrs->attr($attrs, 'hide_quantity', false),
            'min_quantity' => $this->Attrs->attr($attrs, 'min_quantity', false),
            'max_quantity' => $this->Attrs->attr($attrs, 'max_quantity', false),
            'show_quantity_label' => $this->Attrs->attr($attrs, 'show_quantity_label', true),
            'quantity_label_text' => $this->Attrs->attr($attrs, 'quantity_label_text', 'Quantity'),
            'pricing_type_font_family' => $this->Attrs->attr($attrs, 'pricing_type_font_family', false),
            'pricing_type_font_size' => $this->Attrs->attr($attrs, 'pricing_type_font_size', false),
            'pricing_type_font_weight' => $this->Attrs->attr($attrs, 'pricing_type_font_weight', false),
            'pricing_type_text_transform' => $this->Attrs->attr($attrs, 'pricing_type_text_transform', false),
            'pricing_type_font_style' => $this->Attrs->attr($attrs, 'pricing_type_font_style', false),
            'pricing_type_text_decoration' => $this->Attrs->attr($attrs, 'pricing_type_text_decoration', false),
            'pricing_type_line_height' => $this->Attrs->attr($attrs, 'pricing_type_line_height', false),
            'pricing_type_letter_spacing' => $this->Attrs->attr($attrs, 'pricing_type_letter_spacing', false),
            'pricing_color' => $this->Attrs->attr($attrs, 'pricing_color', false),
            'show_price_range' => $this->Attrs->attr($attrs, 'show_price_range', $this->plugin_settings['general']['products_show_price_range']),
            'show_compare_at' => $this->Attrs->attr($attrs, 'show_compare_at', $this->plugin_settings['general']['products_compare_at']),
            'show_featured_only' => $this->Attrs->attr($attrs, 'show_featured_only', false),
            'show_zoom' => $this->Attrs->attr($attrs, 'show_zoom', $this->plugin_settings['general']['products_images_show_zoom']),
            'images_sizing_toggle' => $this->Attrs->attr($attrs, 'images_sizing_toggle', $this->plugin_settings['general']['products_images_sizing_toggle']),
            'images_sizing_width' => $this->Attrs->attr($attrs, 'images_sizing_width', $this->plugin_settings['general']['products_images_sizing_width']),
            'images_sizing_height' => $this->Attrs->attr($attrs, 'images_sizing_height', $this->plugin_settings['general']['products_images_sizing_height']),
            'images_sizing_crop' => $this->Attrs->attr($attrs, 'images_sizing_crop', $this->plugin_settings['general']['products_images_sizing_crop']),
            'images_sizing_scale' => $this->Attrs->attr($attrs, 'images_sizing_scale', $this->plugin_settings['general']['products_images_sizing_scale']),
            'images_align' => $this->Attrs->attr($attrs, 'images_align', 'left'),
            'images_show_next_on_hover' => $this->Attrs->attr($attrs, 'images_show_next_on_hover', false),
            'thumbnail_images_sizing_toggle' => $this->Attrs->attr($attrs, 'thumbnail_images_sizing_toggle', $this->plugin_settings['general']['products_thumbnail_images_sizing_toggle']),
            'thumbnail_images_sizing_width' => $this->Attrs->attr($attrs, 'thumbnail_images_sizing_width', $this->plugin_settings['general']['products_thumbnail_images_sizing_width']),
            'thumbnail_images_sizing_height' => $this->Attrs->attr($attrs, 'thumbnail_images_sizing_height', $this->plugin_settings['general']['products_thumbnail_images_sizing_height']),
            'thumbnail_images_sizing_crop' => $this->Attrs->attr($attrs, 'thumbnail_images_sizing_crop', $this->plugin_settings['general']['products_thumbnail_images_sizing_crop']),
            'thumbnail_images_sizing_scale' => $this->Attrs->attr($attrs, 'thumbnail_images_sizing_scale', $this->plugin_settings['general']['products_thumbnail_images_sizing_scale']),
            'show_images_carousel' => $this->Attrs->attr($attrs, 'show_images_carousel', false),
            'carousel' => $this->Attrs->attr($attrs, 'carousel', false),
            'carousel_dots' => $this->Attrs->attr($attrs, 'carousel_dots', true),
            'carousel_infinite' => $this->Attrs->attr($attrs, 'carousel_infinite', true),
            'carousel_speed' => $this->Attrs->attr($attrs, 'carousel_speed', 500),
            'carousel_slides_to_show' => $this->Attrs->attr($attrs, 'carousel_slides_to_show', 3),
            'carousel_slides_to_scroll' => $this->Attrs->attr($attrs, 'carousel_slides_to_scroll', 3),
            'carousel_prev_arrow' => $this->Attrs->attr($attrs, 'carousel_prev_arrow', "data:image/svg+xml,%3Csvg aria-hidden='true' focusable='false' data-prefix='far' data-icon='angle-left' class='svg-inline--fa fa-angle-left fa-w-6' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 512'%3E%3Cpath fill='currentColor' d='M4.2 247.5L151 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L69.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L168 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 264.5c-4.7-4.7-4.7-12.3 0-17z'%3E%3C/path%3E%3C/svg%3E"),
            'carousel_next_arrow' => $this->Attrs->attr($attrs, 'carousel_next_arrow', "data:image/svg+xml,%3Csvg aria-hidden='true' focusable='false' data-prefix='far' data-icon='angle-right' class='svg-inline--fa fa-angle-right fa-w-6' role='img' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 512'%3E%3Cpath fill='currentColor' d='M187.8 264.5L41 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 392.7c-4.7-4.7-4.7-12.3 0-17L122.7 256 4.2 136.3c-4.7-4.7-4.7-12.3 0-17L24 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17z'%3E%3C/path%3E%3C/svg%3E"),
            'subscriptions' => $this->Attrs->attr($attrs, 'subscriptions', $this->plugin_settings['general']['subscriptions']),
            'subscriptions_select_on_load' => $this->Attrs->attr($attrs, 'subscriptions_select_on_load', false),
            'show_out_of_stock_variants' => $this->Attrs->attr($attrs, 'show_out_of_stock_variants', false),
            'left_in_stock_threshold' => $this->Attrs->attr($attrs, 'left_in_stock_threshold', 10),
            'show_inventory_levels' => $this->Attrs->attr($attrs, 'show_inventory_levels', true),
            'cache_templates' => $this->Attrs->attr($attrs, 'cache_templates', false),
            'container_width' => $this->Attrs->attr($attrs, 'container_width', '1300px'),
            'mobile_columns' => $this->Attrs->attr($attrs, 'mobile_columns', 1),
            'select_first_variant' => $this->Attrs->attr($attrs, 'select_first_variant', false),
            'reset_variants_after_adding' => $this->Attrs->attr($attrs, 'reset_variants_after_adding', true),
            'after_added_text' => $this->Attrs->attr($attrs, 'after_added_text', 'We like that one too!'),
            'clear_selections_text' => $this->Attrs->attr($attrs, 'clear_selections_text', 'Clear selections'),
            'quantity_step' => $this->Attrs->attr($attrs, 'quantity_step', false),
            'color_swatch_names' => $this->Attrs->attr($attrs, 'color_swatch_names', ['color']),
            'image_zoom_options' => $this->Attrs->attr($attrs, 'image_zoom_options', [
                'inlinePane'    => wp_is_mobile(),
                'inlineOffsetX' => wp_is_mobile() ? -100 : 0,
                'inlineOffsetY' => wp_is_mobile() ? -100 : 0,
                'touchDelay'    => 100,
            ]),
            'show_sale_notice' => $this->Attrs->attr($attrs, 'show_sale_notice', true),
            'show_out_of_stock_notice' => $this->Attrs->attr($attrs, 'show_out_of_stock_notice', true),
            'image_placeholder' => $this->Attrs->attr($attrs, 'image_placeholder', SHOPWP_PLUGIN_URL . 'public/imgs/placeholder.png'),
            'title_class_name' => $this->Attrs->attr($attrs, 'title_class_name', 'wps-products-title'),
            'notice_unavailable_text' => $this->Attrs->attr($attrs, 'notice_unavailable_text', 'Out of stock'),
            'pagination_load_more_text' => $this->Attrs->attr($attrs, 'pagination_load_more_text', 'Load more'),
            'out_of_stock_notice_text' => $this->Attrs->attr($attrs, 'out_of_stock_notice_text', 'Out of stock. Please try selecting a different variant combination.'),
            'sale_label_text' => $this->Attrs->attr($attrs, 'sale_label_text', 'Sale!'),
            'sold_out_image_label_text' => $this->Attrs->attr($attrs, 'sold_out_image_label_text', 'Sold out'),
        ]);

    }
}