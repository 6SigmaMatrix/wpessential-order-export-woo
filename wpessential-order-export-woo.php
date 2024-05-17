<?php
/**
 * Plugin Name: WPEssential Order Export Woo
 * Plugin URI: https://wpessential.org/
 * Description: WPEssential Order Export for Woo used to export the WooCommerce orders with all details. It has the option to export the order from a selectable date range and order status base.
 * Version: 1.0
 * Author: WPEssential
 * Author URI: https://wpessential.org/
 * Text Domain: wpessential-order-export-woo
 * Requires at least: 4.5
 * Tested up to: 6.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages/
 * WC requires at least: 6.0
 * WC tested up to: 6.0
 */


if ( ! \defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'constants.php';
require_once WPEOEW_DIR . 'vendor/autoload.php';

add_action( 'plugins_loaded', function () {
	if ( ! did_action( 'wpessential_loaded' ) ) {
		$plugin_notify = \WPEssential\Plugins\Icons\Libraries\RequiredNotifire::make( __( 'Thanks for', 'wpessential-order-export-woo' ) );
		$plugin_notify->plugin_check( 'wpessential' );
		$plugin_notify->desc( __( 'Choosing the WPEssential product. The installed plugin has required the WPEssential base plugin.', 'wpessential-order-export-woo' ) );
		$plugin_notify->dismiss( true );
		$plugin_notify->icon( WPEOEW_URL . 'assets/images/wpessential-logo.jpg' );

	}
}, 1000 );

add_action( 'wpessential_loaded', function () {
	\WPEssential\Plugins\OrderExportWooInit::constructor();
}, 100 );

require_once WPEOEW_DIR . 'install.php';
require_once WPEOEW_DIR . 'uninstall.php';
register_activation_hook( __FILE__, 'wpeoew_install' );
register_deactivation_hook( __FILE__, 'wpeoew_unsintall' );
