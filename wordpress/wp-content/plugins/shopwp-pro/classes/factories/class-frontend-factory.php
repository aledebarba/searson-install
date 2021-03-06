<?php

namespace ShopWP\Factories;

use ShopWP\Frontend;
use ShopWP\Factories;

if (!defined('ABSPATH')) {
    exit();
}

class Frontend_Factory
{
    protected static $instantiated = null;

    public static function build($plugin_settings = false)
    {
        if (!$plugin_settings) {
            $plugin_settings = Factories\DB\Settings_Plugin_Factory::build();
        }

        if (is_null(self::$instantiated)) {
            self::$instantiated = new Frontend(
                $plugin_settings,
                Factories\Data_Bridge_Factory::build($plugin_settings)
            );
        }

        return self::$instantiated;
    }
}
