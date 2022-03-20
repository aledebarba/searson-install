<?php

/*

Plugin Name:         ShopWP Pro
Plugin URI:          https://wpshop.io
Description:         Sell and build custom Shopify experiences on WordPress.
Version:             4.1.1
Author:              ShopWP
Author URI:          https://wpshop.io
License:             GPL-2.0+
License URI:         https://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:         shopwp
Domain Path:         /languages
Requires at least:   5.4
Requires PHP:        5.6

*/

global $ShopWP;

require_once ABSPATH . 'wp-admin/includes/plugin.php';

// If this file is called directly, abort.
defined('WPINC') ?: die();

// If this file is called directly, abort.
defined('ABSPATH') ?: die();

if (\is_plugin_active('shopwp/shopwp.php') || \is_plugin_active('wpshopify/shopwp.php')) {
    add_option('has_upgraded_to_shop_pro', true);
    deactivate_plugins('shopwp/shopwp.php');
}

if (\is_plugin_active('wpshopify/shopwp.php')) {
    add_option('has_upgraded_to_shop_pro', true);
    deactivate_plugins('wpshopify/shopwp.php');
}


/*

Used for both free / pro versions

*/
if (!defined('SHOPWP_BASENAME')) {
    define('SHOPWP_BASENAME', plugin_basename(__FILE__));
}

if (!defined('SHOPWP_ROOT_FILE_PATH')) {
    define('SHOPWP_ROOT_FILE_PATH', __FILE__);
}

if (!defined('SHOPWP_PLUGIN_DIR')) {
    define('SHOPWP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use ShopWP\Bootstrap;
use ShopWP\Utils;

if (!\function_exists('shopwp_bootstrap')) {
    function shopwp_bootstrap()
    {
        // initialize
        if (!isset($ShopWP)) {
            $ShopWP = new Bootstrap();
            $ShopWP->initialize();
        }

        // return
        return $ShopWP;
    }
}

shopwp_bootstrap();

/*

Adds hooks which run on both plugin activation and deactivation.
The actions here are added during Activator->init() and Deactivator-init().

*/
register_activation_hook(__FILE__, function ($network_wide) {
    do_action('shopwp_on_plugin_activate', $network_wide);
});

register_deactivation_hook(__FILE__, function () {
    do_action('shopwp_on_plugin_deactivate');
});
