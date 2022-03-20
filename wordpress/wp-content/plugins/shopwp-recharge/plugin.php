<?php

namespace ShopWP_Recharge;

use ShopWP\Utils;
use ShopWP\Transients;
use ShopWP\DB\Settings_General;

class Plugin {

	private static $_instance = null;

	public static function instance() {
		
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function filter_groups_by_product_id($groups, $product_id) {
		return array_values(array_filter($groups, function($group) use($product_id) {
			return (string) $group->external_product_id === (string) $product_id;
		}));		
	}

	public function create_numeric_product_id_from_storefront_id($storefront_product_id) {
		return array_values(array_filter(explode('gid://shopify/Product/', base64_decode($storefront_product_id))))[0];
	}

	public function request($endpoint, $method = 'GET') {
		
		$Settings_General = new Settings_General();
		$api_key = $Settings_General->get_col_val('recharge_api_key', 'string');

		if (empty($api_key)) {
			return new \WP_Error('error', 'No Recharge connection found.');
		}

		return wp_remote_request(SHOPWP_RECHARGE_API_DOMAIN . $endpoint, [
			'method' => $method,
			'headers' => [
				'X-Recharge-Access-Token' => $api_key
			]
		]);
	}

	public function decode_response($response) {
		return json_decode(wp_remote_retrieve_body($response));
	}

	/*
	
	Docs found here: https://developer.rechargepayments.com/v1-shopify
	
	*/
	public function get_selling_plan_groups_from_product_id($request) {
		
		$storefront_product_id = $request->get_param('shopify_product_id');
		$shopify_product_id_numeric = $this->create_numeric_product_id_from_storefront_id($storefront_product_id);
		$cached_selling_plan_groups = Transients::get('shopwp_selling_plan_groups_product_id_' . $shopify_product_id_numeric);

		if (!empty($cached_selling_plan_groups)) {
			return wp_send_json_success($cached_selling_plan_groups);
		}

		$response = $this->request('/selling_plan_groups?include=product&external_product_id=' . $shopify_product_id_numeric);

		if (is_wp_error($response)) {
			return wp_send_json_error($response);
		}

		$body = $this->decode_response($response);


		if (property_exists($body, 'error')) {
			return wp_send_json_error(new \WP_Error('error', 'Recharge request failed with error message: ' . $body->error));
		}

		if (!property_exists($body, 'selling_plan_groups') || empty($body->selling_plan_groups)) {

			// No subscriptions were found for this product
			error_log('ShopWP Error: No selling plan groups found for Shopify product ID: ' . $shopify_product_id_numeric);
			return wp_send_json_success(false);
		}

		Transients::set('shopwp_selling_plan_groups_product_id_' . $shopify_product_id_numeric, $body->selling_plan_groups);

		return wp_send_json_success($body->selling_plan_groups);

	}

	public function activate($api_key) {
		$Settings_General = new Settings_General();
		return $Settings_General->update_col('recharge_api_key', sanitize_text_field($api_key));
	}

	public function deactivate() {
		$Settings_General = new Settings_General();
		return $Settings_General->update_col('recharge_api_key', '');
	}

	public function activate_license_key($request) {
		
		$api_key = $request->get_param('apiKey');

		if (empty($api_key)) {
			return wp_send_json_error(new \WP_Error('error', 'No Recharge API key found!'));
		}

		$result = $this->activate($api_key);

		if (is_wp_error($result)) {
			return wp_send_json_error($result);
		}

		return wp_send_json_success(Utils::mask($api_key));

	}

	public function deactivate_license_key($request) {
		
		$api_key = $request->get_param('apiKey');

		if (empty($api_key)) {
			return wp_send_json_error(new \WP_Error('error', 'No Recharge API key found!'));
		}

		$result = $this->deactivate();

		if (is_wp_error($result)) {
			return wp_send_json_error($result);
		}

		return wp_send_json_success($result);
	}	
	
	public function register_route_get_recharge_products()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/recharge/subscriptions/product',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_selling_plan_groups_from_product_id'],
                    'permission_callback' => '__return_true',
                ],
            ]
        );
    }

	public function register_route_recharge_api_key()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/recharge/api',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'activate_license_key'],
                    'permission_callback' => '__return_true',
                ],
				[
                    'methods' => \WP_REST_Server::DELETABLE,
                    'callback' => [$this, 'deactivate_license_key'],
                    'permission_callback' => '__return_true',
                ],
            ]
        );
    }

	public function __construct() {
		add_action('rest_api_init', [$this, 'register_route_get_recharge_products']);
		add_action('rest_api_init', [$this, 'register_route_recharge_api_key']);
	}
}

Plugin::instance();