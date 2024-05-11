<?php

namespace WPEssential\Plugins\OrderExportWoo\Assets;

class AssetsInit
{
	protected static function run ()
	{
		RegisterAssets::constructor();
		Enqueue::constructor();
	}

	public static function constructor ()
	{
		//self::run();
	}
}
