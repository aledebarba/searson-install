<?php

namespace ShopWP\Render\Cart;

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
        $this->Render_Attributes = $Render_Attributes;
    }

    public function all_cart_icon_attributes($attrs)
    {
        return [
            'render_from_server' => $this->Render_Attributes->attr(
                $attrs,
                'render_from_server',
                false
            ),
            'icon' => $this->Render_Attributes->attr($attrs, 'icon', false),
            'type' => $this->Render_Attributes->attr($attrs, 'type', 'inline'),
            'inline_background_color' => $this->Render_Attributes->attr(
                $attrs,
                'inline_background_color',
                $this->plugin_settings['general']['cart_counter_color']
            ),
            'inline_cart_counter_text_color' => $this->Render_Attributes->attr(
                $attrs,
                'inline_cart_counter_text_color',
                $this->plugin_settings['general'][
                    'inline_cart_counter_text_color'
                ]
            ),
            'inline_icon_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_icon_color',
                $this->plugin_settings['general']['cart_icon_color']
            ),
            'show_counter' => $this->Render_Attributes->attr(
                $attrs,
                'show_counter',
                true
            ),
            'data_type' => $this->Render_Attributes->attr(
                $attrs,
                'data_type',
                false
            ),
            'fixed_background_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_fixed_background_color',
                $this->plugin_settings['general']['cart_fixed_background_color']
            ),
            'fixed_counter_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_counter_fixed_color',
                $this->plugin_settings['general']['cart_counter_fixed_color']
            ),
            'fixed_icon_color' => $this->Render_Attributes->attr(
                $attrs,
                'cart_icon_fixed_color',
                $this->plugin_settings['general']['cart_icon_fixed_color']
            ),
            'show_inventory_levels' => $this->Render_Attributes->attr(
                $attrs,
                'show_inventory_levels',
                true
            ),
            'cart_title' => $this->Render_Attributes->attr(
                $attrs,
                'cart_title',
                'Shopping cart'
            ),
            'checkout_text' => $this->Render_Attributes->attr(
                $attrs,
                'checkout_text',
                'Begin checkout'
            ),
            'checkout_failed_message' => $this->Render_Attributes->attr(
                $attrs,
                'checkout_failed_message',
                'Unable to checkout. Please reload the page and try again.'
            ),
            'lineitem_remove_text' => $this->Render_Attributes->attr(
                $attrs,
                'lineitem_remove_text',
                'Remove'
            ),
            'lineitem_sale_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'lineitem_sale_label_text',
                'Sale!'
            ),
            'lineitems_disable_link' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_disable_link',
                false
            ),
            'lineitems_max_quantity' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_max_quantity',
                false
            ),
            'lineitems_min_quantity' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_min_quantity',
                false
            ),
            'lineitems_quantity_step' => $this->Render_Attributes->attr(
                $attrs,
                'lineitems_quantity_step',
                false
            ),
            'notes_label' => $this->Render_Attributes->attr(
                $attrs,
                'notes_label',
                'Checkout notes'
            ),
            'notes_placeholder' => $this->Render_Attributes->attr(
                $attrs,
                'notes_placeholder',
                $this->plugin_settings['general'][
                    'cart_notes_placeholder'
                ]
            ),
            'empty_cart_text' => $this->Render_Attributes->attr(
                $attrs,
                'empty_cart_text',
                'Your cart is empty'
            ),
            'subtotal_label_text' => $this->Render_Attributes->attr(
                $attrs,
                'subtotal_label_text',
                'Subtotal:'
            ),
            'show_cart_close_icon' => $this->Render_Attributes->attr(
                $attrs,
                'show_cart_close_icon',
                true
            ),
            'show_cart_title' => $this->Render_Attributes->attr(
                $attrs,
                'show_cart_title',
                true
            ),
        ];
    }

    public function cart_icon($attrs)
    {
        return apply_filters('shopwp_cart_default_payload_settings', $this->all_cart_icon_attributes($attrs));
    }
}
