<?php

namespace ShopWP;

use ShopWP\Options;
use ShopWP\Utils;
use ShopWP\Utils\Data;

if (!defined('ABSPATH')) {
    exit();
}

class Data_Bridge
{
    public $plugin_settings;
    public $Render_Products_Defaults;

    public function __construct($plugin_settings, $Render_Products_Defaults)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Render_Products_Defaults = $Render_Products_Defaults;
    }

    public function replace_rest_protocol()
    {
        if (\is_ssl()) {
            return str_replace("http://", "https://", \get_rest_url());
        }

        return \get_rest_url();
    }

    public function starts_with($haystack, $needle) {
        $length = strlen( $needle );
        return substr( $haystack, 0, $length ) === $needle;
    }
    
    public function ends_with($haystack, $needle) {
        
        $length = strlen( $needle );
        
        if (!$length) {
        return true;
        }
    
        return substr( $haystack, -$length ) === $needle;
    
    }
    
    public function get_available_pages()
    {
        $pages = \get_pages();
    
        $final_pages = array_map(function ($page) {
            return [
                'id' => $page->ID,
                'post_title' => $page->post_title,
                'permalink' => get_permalink($page->ID)
            ];
        }, $pages);

        return $final_pages;
    }    

    public function get_has_connection($connection)
    {
        if (empty($connection)) {
            return false;
        }

        if (
            empty($connection['api_password']) ||
            empty($connection['domain'])
        ) {
            return false;
        }

        return true;
    }

    public function get_settings($is_admin)
    {
        global $post;

        $active_metafields = maybe_unserialize(Options::get('shopwp_active_metafields'));

        if (empty($active_metafields)) {
            $active_metafields = false;
        }

        $locale = \get_locale();

        $currency_symbol = $this->plugin_settings['general']['currency_symbol'] ? $this->plugin_settings['general']['currency_symbol'] : '$';
        $currency_code = $this->plugin_settings['general']['currency_code'] ? $this->plugin_settings['general']['currency_code'] : 'USD';
       
        return [
            'settings' => [
                'products' => $is_admin
                    ? $this->Render_Products_Defaults->all_attrs()
                    : false, // used for blocks
                'syncing' => [
                    'reconnectingWebhooks' => false,
                    'hasConnection' => $this->get_has_connection(
                        $this->plugin_settings['connection']
                    ),
                    'isSyncing' => false,
                    'manuallyCanceled' => false,
                    'isClearing' => false,
                    'isDisconnecting' => false,
                    'isConnecting' => false,
                ],
                'general' => Data::sanitize_settings(
                    $this->plugin_settings['general']
                ),
                'connection' => [
                   'masked' => Data::mask_api_values($this->plugin_settings['connection']),
                    'storefront' => [
                        'domain' =>
                            $this->plugin_settings['connection']['domain'],
                        'storefrontAccessToken' =>
                            $this->plugin_settings['connection'][
                                'storefront_access_token'
                            ],
                    ],
                    'currencySymbol' => apply_filters('shopwp_currency_symbol', $currency_symbol),
                    'currencyCode' => apply_filters('shopwp_currency_code', $currency_code),
                ]
            ],
            'notices' => $this->plugin_settings['notices'],
            'api' => [
                'namespace' => SHOPWP_SHOPIFY_API_NAMESPACE,
                'restUrl' => $this->replace_rest_protocol(),
                'nonce' => \wp_create_nonce('wp_rest'),
            ],
            'misc' => [
                'availablePages' => $is_admin ? $this->get_available_pages() : false,
                'postID' => $post ? $post->ID : false,
                'postURL' => $is_admin && $post ? get_permalink($post->ID) : false,
                'isMobile' => \wp_is_mobile(),
                'isSSL' => \is_ssl(),
                'pluginsDirURL' => \plugin_dir_url(dirname(__FILE__)),
                'pluginsDistURL' => plugin_dir_url(dirname(__FILE__)) . 'dist/',
                'adminURL' => \get_admin_url(),
                'siteUrl' => \site_url(),
                'siteDomain' => parse_url(site_url())['host'],
                'isAdmin' => $is_admin,
                'latestVersion' => SHOPWP_NEW_PLUGIN_VERSION,
                'isPro' => true,
                'hasRecharge' => defined('SHOPWP_DOWNLOAD_ID_RECHARGE_EXTENSION') ? true : false,
                'timers' => [
                    'syncing' => false,
                ],
                'locale' => $locale,
            ],
            'collections' => is_admin() ? maybe_unserialize(Transients::get('shopwp_all_collections')) : false,
            'metafields' => is_admin() ? $active_metafields : false
        ];
    }

    public function stringify_settings($settings, $is_admin)
    {
        $settings_encoded_string = wp_json_encode(
            Utils::convert_underscore_to_camel_array($settings)
        );

        if ($is_admin) {
            $js_string = "var wpshopify = window.wpshopify || " . $settings_encoded_string . ";";

        } else {

            $js_string =
                "function deepFreeze(object) {let propNames = Object.getOwnPropertyNames(object);for (let name of propNames) {let value = object[name];object[name] = value && typeof value === 'object' ? deepFreeze(value) : value;}return Object.freeze(object);}var wpshopify = window.wpshopify || " .
                $settings_encoded_string .
                ";deepFreeze(wpshopify);";
        }

        return $js_string;
    }

    public function add_settings_script($script_dep, $is_admin)
    {

        $string_settings = $this->stringify_settings($this->get_settings($is_admin), $is_admin);

        wp_add_inline_script(
            $script_dep,
            $string_settings,
            'before'
        );
    }
}
