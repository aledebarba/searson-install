<?php

namespace ShopWP\Factories\API\Admin;

defined('ABSPATH') ?: die;

use ShopWP\Factories;
use ShopWP\API;

class Admin_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Admin(
            Factories\DB\Settings_Connection_Factory::build()
			);

		}

		return self::$instantiated;

	}

}