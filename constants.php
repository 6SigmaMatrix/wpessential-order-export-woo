<?php

if ( ! \defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define the plugin directory url in http:// or https:// base
 *
 * @since 1.0
 */
defined( 'WPEOEW_URL' ) || define( 'WPEOEW_URL', plugin_dir_url( __FILE__ ) );

/**
 * Define the plugin directory path
 *
 * @since 1.0
 */
defined( 'WPEOEW_DIR' ) || define( 'WPEOEW_DIR', plugin_dir_path( __FILE__ ) );


/**
 * Define the plugin version
 *
 * @since 1.0
 */
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$data = get_plugin_data( WPEOEW_DIR . 'wpessential-order-export-woo.php' );
defined( 'WPEOEW_VERSION' ) || define( 'WPEOEW_VERSION', $data[ 'Version' ] );
