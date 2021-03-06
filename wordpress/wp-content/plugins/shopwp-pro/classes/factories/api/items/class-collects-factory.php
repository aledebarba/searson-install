<?php

namespace ShopWP\Factories\API\Items;

defined('ABSPATH') ?: die;

use ShopWP\API;
use ShopWP\Factories;

class Collects_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Items\Collects(
				Factories\DB\Settings_General_Factory::build(),
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\Shopify_API_Factory::build(),
				Factories\Processing\Collects_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
