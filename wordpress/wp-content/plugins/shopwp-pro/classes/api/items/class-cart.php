<?php

namespace ShopWP\API\Items;

if (!defined('ABSPATH')) {
    exit();
}

class Cart extends \ShopWP\API
{
    public function __construct($Storefront_Cart) {
        $this->Storefront_Cart = $Storefront_Cart;
    }

    public function handle_apply_discount_to_checkout($request) {

        $cart_data = $this->create_cart_data($request);

        $response_one = $this->Storefront_Cart->create_checkout($cart_data);

        if (is_wp_error($response_one)) {
            return wp_send_json_error($response_one);
        }

        if (!empty($response_one->checkoutCreate->checkoutUserErrors)) {

            $err = $response_one->checkoutCreate->checkoutUserErrors[0]->message;

            if ($err === 'Variant is invalid') {
                return wp_send_json_error('Uh oh, one or more of the products in your cart are no longer available to purchase. Try clearing your cart and adding the products again.');
            }

            return wp_send_json_error($err);
        }


        $cart_data['checkoutId'] = $response_one->checkoutCreate->checkout->id;

        $response = $this->Storefront_Cart->apply_discount($cart_data);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        if (!empty($response->checkoutDiscountCodeApplyV2->checkoutUserErrors)) {
            return wp_send_json_error($response->checkoutDiscountCodeApplyV2->checkoutUserErrors[0]->message);
        }

        return wp_send_json_success($response->checkoutDiscountCodeApplyV2->checkout);

    }

    public function create_cart_data($request, $line_type = 'variantId') {

        $checkout_note = $request->get_param('note');
        $checkout_attributes = $request->get_param('customAttributes');
        $checkout_discount_codes = $request->get_param('discountCode');
        $checkout_cache = $request->get_param('checkoutCache');

        $checkout_lineitems = $this->create_lineitems_from_cache($checkout_cache, $line_type);

        $cart_data = [
            "lines"         => $checkout_lineitems,
            "note"          => empty($checkout_note) ? '' : $checkout_note,
            "attributes"    => empty($checkout_attributes) ? [] : $checkout_attributes,
            "discountCodes" => empty($checkout_discount_codes) ? '' : $checkout_discount_codes
        ];

        return apply_filters('shopwp_cart_data', $cart_data);

    }

    public function find_lineitem_options_from_lineitem_id($lineitem_options, $lineitem_id) {

        if (empty($lineitem_options)) {
            return false;
        }

        return array_filter($lineitem_options, function($lineitem_option) use($lineitem_id) {
            return $lineitem_option['variantId'] === $lineitem_id;
        });

    }

    public function create_lineitems_from_cache($checkout_cache, $line_type) {

        $line_items_new = array_map(function($lineitem) use($checkout_cache, $line_type) {
            
            $lineitem_info = [
                'quantity' => $lineitem['quantity']
            ];

            $lineitem_info[$line_type] = $lineitem['variantId'];

            $options_found = $this->find_lineitem_options_from_lineitem_id($checkout_cache['lineItemOptions'], $lineitem['variantId']);

            if (!empty($options_found)) {

                $options_found = array_values($options_found); 

                if (!empty($options_found[0]['options']['subscription'])) {
                    $lineitem_info['sellingPlanId'] = base64_encode('gid://shopify/SellingPlan/' . $options_found[0]['options']['subscription']['sellingPlanId']);    
                }

                if (!empty($options_found[0]['options']['attributes'])) {
                    $lineitem_info['attributes'] = $options_found[0]['options']['attributes'];    
                }
                
            }

            return $lineitem_info;

        }, $checkout_cache['lineItems']);

        return $line_items_new;        
    }

    public function handle_checkout($request)
    {
        
        $cart_data = $this->create_cart_data($request, 'merchandiseId');

        $response = $this->Storefront_Cart->create_cart($cart_data);

        if (is_wp_error($response)) {
            return wp_send_json_error($response);
        }

        return wp_send_json_success($response->cartCreate->cart);

    }

    public function register_route_create_cart()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/cart/create',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'handle_checkout'],
                    'permission_callback' => [$this, 'pre_process'],
                ]
            ]
        );
    }

    public function register_route_apply_discount_to_checkout()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/cart/discount',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'handle_apply_discount_to_checkout'],
                    'permission_callback' => [$this, 'pre_process'],
                ]
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_create_cart']);
        add_action('rest_api_init', [$this, 'register_route_apply_discount_to_checkout']);
    }

}
