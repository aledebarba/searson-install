<?php
/**
 * Plugin Name: ShopWP Recharge
 * Description: Custom Recharge extension for ShopWP
 * Plugin URI:  https://wpshop.io/extensions/recharge
 * Version:     1.0.2
 * Author:      ShopWP
 * Author URI:  https://wpshop.io/extensions/recharge
 * Text Domain: shopwp-recharge
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!defined('SHOPWP_RECHARGE_MODULE_DIR')) {
   define( 'SHOPWP_RECHARGE_MODULE_DIR', plugin_dir_path( __FILE__ ) );
}

if (!defined('SHOPWP_RECHARGE_BASENAME')) {
    define('SHOPWP_RECHARGE_BASENAME', plugin_basename(__FILE__));
}

if (!defined('SHOPWP_RECHARGE_MODULE_URL')) {
   define( 'SHOPWP_RECHARGE_MODULE_URL', plugins_url( '/', __FILE__ ) );
}

if (!defined('SHOPWP_DOWNLOAD_NAME_RECHARGE')) {
   define('SHOPWP_DOWNLOAD_NAME_RECHARGE', 'Recharge Extension');
}

if (!defined('SHOPWP_DOWNLOAD_ID_RECHARGE_EXTENSION')) {
	define('SHOPWP_DOWNLOAD_ID_RECHARGE_EXTENSION', 209061);
}

if (!defined('SHOPWP_RECHARGE_NEW_PLUGIN_VERSION')) {
	define('SHOPWP_RECHARGE_NEW_PLUGIN_VERSION', '1.0.2');
}

if (!defined('SHOPWP_RECHARGE_API_DOMAIN')) {
	define('SHOPWP_RECHARGE_API_DOMAIN', 'https://api.rechargeapps.com');
}

final class ShopWP_Recharge_Extension {

	const MINIMUM_PHP_VERSION = '5.6';

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'shopwp_is_ready', [$this, 'plugin_updater']);
	}

	public function i18n() {
		load_plugin_textdomain( 'shopwp-recharge' );
	}

	public function init() {

		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		if ( !defined('SHOPWP_BASENAME') || SHOPWP_BASENAME !== 'shopwp-pro/shopwp.php') {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		$this->i18n();

		require_once( 'plugin.php' );
	}

	public function plugin_updater() {

		$DB_Settings_License = ShopWP\Factories\DB\Settings_License_Factory::build();
		$DB_Settings_General = ShopWP\Factories\DB\Settings_General_Factory::build();
		$Updater = ShopWP\Factories\Updater_Factory::build();

		$licenses = $DB_Settings_License->get_all_rows();

		// If no keys are found, return
		if (empty($licenses)) {
			return;
		}

		$Updater->maybe_check_for_updates([
			'licenses'           => $licenses,
			'plugin_basename'    => SHOPWP_RECHARGE_BASENAME,
			'current_version'    => SHOPWP_RECHARGE_NEW_PLUGIN_VERSION,
			'item_id'            => SHOPWP_DOWNLOAD_ID_RECHARGE_EXTENSION,
			'enable_beta'        => $DB_Settings_General->get_col_val('enable_beta', 'bool'),
			'updater_class_name' => 'ShopWP_Recharge_EDD_SL_Plugin_Updater',
			'updater_class_path' => SHOPWP_RECHARGE_MODULE_DIR . 'vendor/EDD/ShopWP_Recharge_EDD_SL_Plugin_Updater.php',
		]);

	}

	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Recharge */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'shopwp-recharge' ),
			'<strong>' . esc_html__( 'ShopWP Recharge Extension', 'shopwp-recharge' ) . '</strong>',
			'<strong>' . esc_html__( 'ShopWP Pro', 'shopwp-recharge' ) . '</strong>'
		);

		deactivate_plugins( plugin_basename( __FILE__ ) );

		printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'shopwp-recharge' ),
			'<strong>' . esc_html__( 'ShopWP Recharge Extension', 'shopwp-recharge' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'shopwp-recharge' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
   }

}

new ShopWP_Recharge_Extension();
