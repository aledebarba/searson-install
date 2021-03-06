<?php

namespace ShopWP\Factories\API\Items;

defined('ABSPATH') ?: die;

use ShopWP\API;
use ShopWP\Factories;

class Webhooks_Factory {

	protected static $instantiated = null;

	public static function build($plugin_settings = false) {

		if (is_null(self::$instantiated)) {

			self::$instantiated = new API\Items\Webhooks(
				Factories\DB\Settings_Syncing_Factory::build(),
				Factories\Webhooks_Factory::build(),
				Factories\Processing\Webhooks_Factory::build(),
				Factories\Processing\Webhooks_Deletions_Factory::build(),
				Factories\Shopify_API_Factory::build()
			);

		}

		return self::$instantiated;

	}

}
