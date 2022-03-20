<?php

namespace ShopWP\API\Items;

if (!defined('ABSPATH')) {
    exit();
}

class Webhooks extends \ShopWP\API
{
    public function __construct(
        $DB_Settings_Syncing,
        $Webhooks,
        $Processing_Webhooks,
        $Processing_Webhooks_Deletions,
        $Shopify_API
    ) {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Webhooks = $Webhooks;
        $this->Processing_Webhooks = $Processing_Webhooks;
        $this->Processing_Webhooks_Deletions = $Processing_Webhooks_Deletions;
        $this->Shopify_API = $Shopify_API;
    }

    public function get_webhooks_count($topics)
    {
        return count($topics);
    }

    public function get_currently_connected_webhook_total($response) {
        return count($response->webhooks);
    }

    public function get_currently_connected_webhooks() {

        return $this->Shopify_API->pre_response_check($this->Shopify_API->get_webhooks());
    }

    public function get_to_be_connected_webhooks() {

        $topics = $this->Webhooks->default_topics();

        if (is_string($topics)) {
            $topics_array = explode(',', $topics);
            
        } else {
            $topics_array = $topics;
        }

        return $topics_array;
    }

    public function get_to_be_connected_webhook_total() {
        return count($this->get_to_be_connected_webhooks());
    }

    public function set_total_webhooks($total) {
        return $this->DB_Settings_Syncing->set_syncing_totals(['webhooks' => $total], []);
    }

    public function delete_webhooks($request)
    {   
        $is_reconnecting = $request->get_param('reconnect');

        $webhooks = $this->get_currently_connected_webhooks();

        $currently_connected_total = $this->get_currently_connected_webhook_total($webhooks);

        if ($is_reconnecting) {
            $to_be_connected_total = $this->get_to_be_connected_webhook_total();

            $grand_total = $currently_connected_total + $to_be_connected_total;
        } else {
            $grand_total = $currently_connected_total;
        }

        $this->set_total_webhooks($grand_total);

        return $this->handle_response([
            'response' => $webhooks,
            'access_prop' => 'webhooks',
            'process_fns' => [$this->Processing_Webhooks_Deletions],
        ]);
    }

    public function register_all_webhooks($request)
    {

        $topics_array = $this->get_to_be_connected_webhooks();

        return $this->handle_response([
            'response' => $topics_array,
            'warning_message' => 'webhooks_not_found',
            'process_fns' => [$this->Processing_Webhooks],
        ]);
    }

    public function register_route_webhooks_count()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/webhooks/count',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'get_webhooks_count'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

  
    public function register_route_webhooks()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/webhooks',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'register_all_webhooks'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function register_route_webhooks_delete()
    {
        return register_rest_route(
            SHOPWP_SHOPIFY_API_NAMESPACE,
            '/webhooks/delete',
            [
                [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'delete_webhooks'],
                    'permission_callback' => [$this, 'pre_process'],
                ],
            ]
        );
    }

    public function init()
    {
        add_action('rest_api_init', [$this, 'register_route_webhooks']);
        add_action('rest_api_init', [$this, 'register_route_webhooks_count']);
        add_action('rest_api_init', [$this, 'register_route_webhooks_delete']);
    }

}
