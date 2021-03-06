<?php

namespace ShopWP\Factories;

use ShopWP\Data_Bridge;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Data_Bridge_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new Data_Bridge(
                $plugin_settings,
                Factories\Render\Products\Defaults_Factory::build()
            );
        }

        return self::$instantiated;
    }
}
