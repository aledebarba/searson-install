<?php

namespace ShopWP;

use ShopWP\Utils;
use ShopWP\Options;
use ShopWP\Utils\Data;
use ShopWP\Utils\Server;

if (!defined('ABSPATH')) {
    exit();
}

class Frontend
{
    public $plugin_settings;
    public $Data_Bridge;

    public function __construct($plugin_settings, $Data_Bridge)
    {
        $this->plugin_settings = $plugin_settings;
        $this->Data_Bridge = $Data_Bridge;
    }

    public function public_styles()
    {
        if (!is_admin()) {
            wp_enqueue_style(
                'shopwp-styles-frontend-all',
                SHOPWP_PLUGIN_URL . 'dist/public.min.css',
                [],
                filemtime(SHOPWP_PLUGIN_DIR_PATH . 'dist/public.min.css')
            );
        }
    }

    public function public_scripts()
    {

        global $post;

        $runtime_path = 'dist/runtime.d307a507.min.js';
        $runtime_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $runtime_path);
        $runtime_url = SHOPWP_PLUGIN_URL . $runtime_path;

        $vendors_admin_path = 'dist/vendors-public.d307a507.min.js';
        $vendors_admin_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $vendors_admin_path);
        $vendors_admin_url = SHOPWP_PLUGIN_URL . $vendors_admin_path;

        $main_path = 'dist/public.d307a507.min.js';
        $main_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $main_path);
        $main_url = SHOPWP_PLUGIN_URL . $main_path;

        wp_enqueue_script('shopwp-runtime', $runtime_url, ['wp-hooks', 'wp-element', 'wp-i18n'], $runtime_filetime, true);
        wp_enqueue_script('shopwp-vendors-public', $vendors_admin_url, ['wp-hooks', 'wp-element', 'wp-i18n'], $vendors_admin_filetime, true);
        wp_enqueue_script(
            'shopwp-public', 
            $main_url, 
            [
                'wp-hooks',
                'wp-element',
                'wp-i18n',
                'shopwp-runtime',
                'shopwp-vendors-public',
            ],
            $main_filetime,
            true
        );

        if (empty($this->plugin_settings['shop'])) {
            $currency = 'USD';
        } else {
            $currency = $this->plugin_settings['shop']['currency'];
        }

        // Global plugin JS settings
        $this->Data_Bridge->add_settings_script('shopwp-public', false);

        wp_set_script_translations(
            'shopwp-public',
            'shopwp',
            SHOPWP_PLUGIN_DIR . SHOPWP_LANGUAGES_FOLDER
        );
        
    }

    public function css_body_class($classes)
    {
        $classes[] = 'shopwp';

        return $classes;
    }

    public function init()
    {
        if (\is_admin()) {
            return;
        }

        add_filter('body_class', [$this, 'css_body_class']);
        add_action('wp_enqueue_scripts', [$this, 'public_styles']);
        add_action('wp_enqueue_scripts', [$this, 'public_scripts']);

    }
}
