<?php

if ( ! \defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
function wpeoew_unsintall ()
{
	$dir_check = wp_get_upload_dir()[ 'basedir' ] . '/wpessential-order-export-woo';
	if ( is_dir( $dir_check ) ) {
		array_map( 'unlink', glob( "$dir_check/*.*" ) );
		rmdir( $dir_check );
	}
}
