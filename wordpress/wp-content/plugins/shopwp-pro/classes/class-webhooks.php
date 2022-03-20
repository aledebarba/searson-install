<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\Messages;

if (!defined('ABSPATH')) {
    exit();
}

class Webhooks
{
    public $plugin_settings;
    public $Template_Loader;

    public function __construct($plugin_settings, $Template_Loader)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Template_Loader = $Template_Loader;
    }

    public function get_webhook_body_from_topic($topic)
    {
        $receiver = $this->get_callback_name_from_topic($topic);
        $webhook_body = $this->get_webhook_body_request($topic, $receiver);

        return $webhook_body;
    }

    public function collections_create_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_collections_create', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/collections/collections-create');

            do_action('wps_after_collections_create', $data);
        }
    }

    public function collections_update_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_collections_update', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/collections/collections-update');

            do_action('wps_after_collection_update', $data);
        }
    }

    public function collections_delete_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_collections_delete', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/collections/collections-delete');

            do_action('wps_after_collections_delete', $data);
        }
    }

    public function product_listings_add_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_product_create', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/products/product-create');

            do_action('wps_after_product_create', $data);
        }
    }

    public function product_listings_update_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_product_update', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/products/product-update');

            do_action('wps_after_product_update', $data);
        }
    }

    public function product_listings_remove_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_product_delete', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/products/product-delete');

            do_action('wps_after_product_delete', $data);
        }
    }

    public function get_webhook_data($webhook_fn_name)
    {
        $json_data = $this->before_webhook_process();

        if (!$this->is_valid_webhook($json_data)) {
            return false;
        }

        return json_decode($json_data);
    }

    public function shop_update_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_shop_update', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/shop/shop-update');

            do_action('wps_after_shop_update', $data);
        }
    }

    public function app_uninstalled_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_app_uninstall', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/app/app-uninstalled');

            do_action('wps_after_app_uninstall', $data);
        }
    }

    public function orders_create_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_create', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/orders/order-create');

            do_action('wps_after_order_create', $data);
        }
    }

    public function orders_cancelled_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_cancelled', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/orders/order-cancelled');

            do_action('wps_after_order_cancelled', $data);
        }
    }

    public function orders_delete_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_delete', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/orders/order-delete');

            do_action('wps_after_order_delete', $data);
        }
    }

    public function orders_fulfilled_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_fulfilled', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/orders/order-fulfilled');

            do_action('wps_after_order_fulfilled', $data);
        }
    }

    public function orders_paid_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_paid', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/orders/order-paid');

            do_action('wps_after_order_paid', $data);
            do_action('wps_webhook_checkouts_order_paid', $data);
            // Deprecated. Need to leave for backwards compatibility
        }
    }

    public function orders_partially_fulfilled_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_partially_fulfilled', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part(
                    'webhooks/orders/order-partially-fulfilled'
                );

            do_action('wps_after_order_partially_fulfilled', $data);
        }
    }

    public function orders_updated_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_updated', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/orders/order-updated');

            do_action('wps_after_order_updated', $data);
        }
    }

    public function order_transactions_create_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_order_transactions_create', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part(
                    'webhooks/orders/order-transactions-create'
                );

            do_action('wps_after_order_transactions_create', $data);
        }
    }

    public function checkouts_create_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_checkout_create', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/checkouts/checkout-create');

            do_action('wps_after_checkout_create', $data);
        }
    }

    public function checkouts_delete_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_checkout_delete', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/checkouts/checkout-delete');

            do_action('wps_after_checkout_update', $data);
        }
    }

    public function checkouts_update_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_checkout_update', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/checkouts/checkout-update');

            do_action('wps_after_checkout_update', $data);
        }
    }

    public function customers_create_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_customer_create', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/customers/customer-create');

            do_action('wps_after_customer_create', $data);
        }
    }

    public function customers_update_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_customer_update', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/customers/customer-update');

            do_action('wps_after_customer_update', $data);
        }
    }

    public function customers_delete_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_customer_delete', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/customers/customer-delete');

            do_action('wps_after_customer_delete', $data);
        }
    }

    public function customers_disable_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_customer_disable', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/customers/customer-disable');

            do_action('wps_after_customer_disable', $data);
        }
    }

    public function customers_enable_callback()
    {
        $data = $this->get_webhook_data(__FUNCTION__);

        if ($data) {
            do_action('wps_before_customer_enable', $data);

            $this->Template_Loader
                ->set_template_data($data)
                ->get_template_part('webhooks/customers/customer-enable');

            do_action('wps_after_customer_enable', $data);
        }
    }

    public function default_topics()
    {
        $topics = $this->plugin_settings['general']['sync_by_webhooks'];

        $topics_unserialized = maybe_unserialize($topics);

        if (empty($topics_unserialized)) {
            return [];
        }

        return $topics_unserialized;
    }

    public function invalid_topic_error_needle()
    {
        return 'Invalid topic specified';
    }

    public function is_invalid_topic_error($response)
    {
        if (
            Utils::str_contains_start(
                $response->get_error_message(),
                $this->invalid_topic_error_needle()
            )
        ) {
            return true;
        }

        return false;
    }

    public function get_webhook_body_request($topic, $callback_receiver)
    {
        // This is the URI where Shopify will send its POST request when an event occurs.
        $custom_webbooks_url =
            $this->plugin_settings['general']['url_webhooks'];
        $home_url = Utils::get_site_url(); // also default webhook URL
        $admin_url = admin_url();

        if ($home_url !== $custom_webbooks_url) {
            $admin_path = Utils::construct_admin_path_from_urls(
                $home_url,
                $admin_url
            );

            $callback_url =
                $custom_webbooks_url .
                $admin_path .
                "admin-ajax.php?action=" .
                $callback_receiver;
        } else {
            $callback_url =
                admin_url('admin-ajax.php') . "?action=" . $callback_receiver;
        }

        // Data to send to Shopify in our POST
        return [
            "webhook" => [
                "topic" => $topic,
                "address" => apply_filters(
                    'shopwp_webhooks_callback_url',
                    $callback_url
                ),
                "format" => 'json',
            ],
        ];
    }

    private function calculate_hmac($data, $shared_secret)
    {
        return base64_encode(hash_hmac('sha256', $data, $shared_secret, true));
    }

    public function webhook_verified($data, $hmac_header)
    {
        $shared_secret = $this->plugin_settings['connection']['shared_secret'];

        // Must have an active connection even if allow insecure is checked
        if (empty($shared_secret) || empty($hmac_header)) {
            return false;
        }

        $calculated_hmac = $this->calculate_hmac($data, $shared_secret);

        return hash_equals($hmac_header, $calculated_hmac);
    }

    public function get_header_hmac()
    {
        $_SERVER = wp_unslash($_SERVER);

        if (isset($_SERVER[SHOPWP_SHOPIFY_HEADER_VERIFY_WEBHOOKS])) {
            return $_SERVER[SHOPWP_SHOPIFY_HEADER_VERIFY_WEBHOOKS];
        }
    }

    public function get_callback_name_from_topic($topic)
    {
        return str_replace('/', '_', $topic) . '_callback';
    }

    public function is_valid_webhook($json_data)
    {
        if ($this->webhook_verified($json_data, $this->get_header_hmac())) {
            return true;
        }

        return false;
    }

    public function before_webhook_process()
    {
        http_response_code(200);

        global $wp_filesystem;

        if (empty($wp_filesystem)) {
            require_once (ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        return $wp_filesystem->get_contents('php://input');

    }

    public function init()
    {
        add_action('wp_ajax_customers_update_callback', [
            $this,
            'customers_update_callback',
        ]);
        add_action('wp_ajax_nopriv_customers_update_callback', [
            $this,
            'customers_update_callback',
        ]);

        add_action('wp_ajax_customers_create_callback', [
            $this,
            'customers_create_callback',
        ]);
        add_action('wp_ajax_nopriv_customers_create_callback', [
            $this,
            'customers_create_callback',
        ]);

        add_action('wp_ajax_customers_delete_callback', [
            $this,
            'customers_delete_callback',
        ]);
        add_action('wp_ajax_nopriv_customers_delete_callback', [
            $this,
            'customers_delete_callback',
        ]);

        add_action('wp_ajax_customers_disable_callback', [
            $this,
            'customers_disable_callback',
        ]);
        add_action('wp_ajax_nopriv_customers_disable_callback', [
            $this,
            'customers_disable_callback',
        ]);

        add_action('wp_ajax_customers_enable_callback', [
            $this,
            'customers_enable_callback',
        ]);
        add_action('wp_ajax_nopriv_customers_enable_callback', [
            $this,
            'customers_enable_callback',
        ]);

        add_action('wp_ajax_orders_create_callback', [
            $this,
            'orders_create_callback',
        ]);
        add_action('wp_ajax_nopriv_orders_create_callback', [
            $this,
            'orders_create_callback',
        ]);

        add_action('wp_ajax_orders_paid_callback', [
            $this,
            'orders_paid_callback',
        ]);
        add_action('wp_ajax_nopriv_orders_paid_callback', [
            $this,
            'orders_paid_callback',
        ]);

        add_action('wp_ajax_orders_cancelled_callback', [
            $this,
            'orders_cancelled_callback',
        ]);
        add_action('wp_ajax_nopriv_orders_cancelled_callback', [
            $this,
            'orders_cancelled_callback',
        ]);

        add_action('wp_ajax_orders_delete_callback', [
            $this,
            'orders_delete_callback',
        ]);
        add_action('wp_ajax_nopriv_orders_delete_callback', [
            $this,
            'orders_delete_callback',
        ]);

        add_action('wp_ajax_orders_fulfilled_callback', [
            $this,
            'orders_fulfilled_callback',
        ]);
        add_action('wp_ajax_nopriv_orders_fulfilled_callback', [
            $this,
            'orders_fulfilled_callback',
        ]);

        add_action('wp_ajax_orders_partially_fulfilled_callback', [
            $this,
            'orders_partially_fulfilled_callback',
        ]);
        add_action('wp_ajax_nopriv_orders_partially_fulfilled_callback', [
            $this,
            'orders_partially_fulfilled_callback',
        ]);

        add_action('wp_ajax_orders_updated_callback', [
            $this,
            'orders_updated_callback',
        ]);
        add_action('wp_ajax_nopriv_orders_updated_callback', [
            $this,
            'orders_updated_callback',
        ]);

        add_action('wp_ajax_order_transactions_create_callback', [
            $this,
            'order_transactions_create_callback',
        ]);
        add_action('wp_ajax_nopriv_order_transactions_create_callback', [
            $this,
            'order_transactions_create_callback',
        ]);

        add_action('wp_ajax_product_listings_add_callback', [
            $this,
            'product_listings_add_callback',
        ]);
        add_action('wp_ajax_nopriv_product_listings_add_callback', [
            $this,
            'product_listings_add_callback',
        ]);

        add_action('wp_ajax_product_listings_update_callback', [
            $this,
            'product_listings_update_callback',
        ]);
        add_action('wp_ajax_nopriv_product_listings_update_callback', [
            $this,
            'product_listings_update_callback',
        ]);

        add_action('wp_ajax_product_listings_remove_callback', [
            $this,
            'product_listings_remove_callback',
        ]);
        add_action('wp_ajax_nopriv_product_listings_remove_callback', [
            $this,
            'product_listings_remove_callback',
        ]);

        add_action('wp_ajax_collections_create_callback', [
            $this,
            'collections_create_callback',
        ]);
        add_action('wp_ajax_nopriv_collections_create_callback', [
            $this,
            'collections_create_callback',
        ]);

        add_action('wp_ajax_collections_update_callback', [
            $this,
            'collections_update_callback',
        ]);
        add_action('wp_ajax_nopriv_collections_update_callback', [
            $this,
            'collections_update_callback',
        ]);

        add_action('wp_ajax_collections_delete_callback', [
            $this,
            'collections_delete_callback',
        ]);
        add_action('wp_ajax_nopriv_collections_delete_callback', [
            $this,
            'collections_delete_callback',
        ]);

        add_action('wp_ajax_shop_update_callback', [
            $this,
            'shop_update_callback',
        ]);
        add_action('wp_ajax_nopriv_shop_update_callback', [
            $this,
            'shop_update_callback',
        ]);

        add_action('wp_ajax_app_uninstalled_callback', [
            $this,
            'app_uninstalled_callback',
        ]);
        add_action('wp_ajax_nopriv_app_uninstalled_callback', [
            $this,
            'app_uninstalled_callback',
        ]);

        add_action('wp_ajax_checkouts_create_callback', [
            $this,
            'checkouts_create_callback',
        ]);
        add_action('wp_ajax_nopriv_checkouts_create_callback', [
            $this,
            'checkouts_create_callback',
        ]);

        add_action('wp_ajax_checkouts_delete_callback', [
            $this,
            'checkouts_delete_callback',
        ]);
        add_action('wp_ajax_nopriv_checkouts_delete_callback', [
            $this,
            'checkouts_delete_callback',
        ]);

        add_action('wp_ajax_checkouts_update_callback', [
            $this,
            'checkouts_update_callback',
        ]);
        add_action('wp_ajax_nopriv_checkouts_update_callback', [
            $this,
            'checkouts_update_callback',
        ]);
    }

}
