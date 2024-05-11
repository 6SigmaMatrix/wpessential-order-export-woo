<?php

namespace WPEssential\Plugins;

use WPEssential\Plugins\OrderExportWoo\Assets\AssetsInit;
use WPEssential\Plugins\OrderExportWoo\Requesting\RequestingInit;
use WPEssential\Plugins\OrderExportWoo\Utility\WooOrderExport;

final class OrderExportWooInit
{
	public static function init ()
	{
		load_plugin_textdomain( 'wpessential-order-export-woo', false, WPEOEW_DIR . '/language' );
	}

	public static function constructor ()
	{
		self::load_files();
		self::start();
		add_action( 'wpessential_init', [ __CLASS__, 'init' ], 50 );
	}

	public static function load_files ()
	{
		require_once WPEOEW_DIR . '/inc/Plugins/OrderExportWoo/Functions/general.php';
	}

	public static function start ()
	{
		AssetsInit::constructor();
		RequestingInit::constructor();
		WooOrderExport::constructor();
	}
}
