<?php

namespace ShopWP;

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

if (!current_user_can('activate_plugins')) {
    exit();
}

// Don't clean up if deleting old WP Shopify plugin
if (defined('SHOPWP_BASENAME')) {
    return;
}

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

use ShopWP\Factories\Processing\Database_Factory;

$Processing_Database = Database_Factory::build();

if (is_multisite()) {
    $Processing_Database->uninstall_plugin_multisite();
} else {
    $Processing_Database->uninstall_plugin();
}
