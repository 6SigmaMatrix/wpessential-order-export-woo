<?php

namespace WPEssential\Plugins\OrderExportWoo\Requesting;

if ( ! \defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class RequestingInit
{
	public static function constructor ()
	{
		$prefix = WPE_AJAX_PREFIX;
		add_action( "wp_ajax_{$prefix}_woeow_file_download", [ __CLASS__, 'download' ] );
		add_action( "wp_ajax_nopriv_{$prefix}_woeow_file_download", [ __CLASS__, 'download' ] );
	}

	public static function download ()
	{
		wpe_ajax_authorized();

		$from_data    = sanitize_text_field( wpe_array_get( $_POST, 'from_date' ) );
		$to_date      = sanitize_text_field( wpe_array_get( $_POST, 'to_date' ) );
		$order_status = sanitize_text_field( wpe_array_get( $_POST, 'order_status' ) );
		$order        = sanitize_text_field( wpe_array_get( $_POST, 'order' ) );
		$orderby      = sanitize_text_field( wpe_array_get( $_POST, 'orderby' ) );
		$email        = sanitize_text_field( wpe_array_get( $_POST, 'custom_address' ) );

		if ( $from_data && $to_date && $order_status ) {
			exit( esc_html__( 'Data is not verified...', 'wpessential-order-export-woo' ) );
		}

		$ids    = self::get_order_ids( [ $from_data, $to_date, $order_status, $order, $orderby, $email ] );
		$orders = self::get_orders_detail( $ids );
		if ( empty( $orders ) ) {
			return;
		}

		$url = self::get_csv( $orders );
		exit( $url );
	}

	public static function get_order_ids ( $args = [] )
	{
		$default = [
			'date_created' => '',
			'status'       => 'wc-processing',
			'order'        => 'DESC',
			'orderby'      => 'title',
			'customer'     => ''
		];

		$args = wp_parse_args( $args, $default );
		$args = array_filter( $args );

		$ids       = [];
		$order_get = wc_get_orders( $args );
		if ( ! empty( $order_get ) ) {
			foreach ( $order_get as $result ) {
				$ids[] = $result->get_id();
			}
		}

		return $ids;
	}

	public static function get_orders_detail ( $ids )
	{
		$data_array = [];
		if ( ! empty( $ids ) ) {
			foreach ( $ids as $id ) {
				$order    = new \WC_Order( $id );
				$qty      = 0;
				$products = [];
				foreach ( $order->get_items() as $item ) {
					$qty        += $item->get_quantity();
					$products[] = $item->get_id();
				}
				$oder_date    = $order->get_date_created();
				$data_array[] = [
					'order_id'            => $order->get_id(),
					'date'                => $oder_date->date( 'm/d/Y' ),
					'order_status'        => $order->get_status(),
					'product_id'          => implode( '|', $products ),
					'qty'                 => $qty,
					'billing_first_name'  => $order->get_billing_first_name(),
					'billing_Last_name'   => $order->get_billing_last_name(),
					'billing_company'     => $order->get_billing_company(),
					'billing_address_1'   => $order->get_billing_address_1(),
					'billing_address_2'   => $order->get_billing_address_2(),
					'billing_city'        => $order->get_billing_city(),
					'billing_state'       => $order->get_billing_state(),
					'billing_zip_code'    => $order->get_billing_postcode(),
					'billing_country'     => $order->get_billing_country(),
					'billing_email'       => $order->get_billing_email(),
					'billing_phone'       => $order->get_billing_phone(),
					'shipping_first_name' => $order->get_shipping_first_name(),
					'shipping_Last_name'  => $order->get_shipping_last_name(),
					'shipping_company'    => $order->get_shipping_company(),
					'shipping_address_1'  => $order->get_shipping_address_1(),
					'shipping_address_2'  => $order->get_shipping_address_2(),
					'shipping_city'       => $order->get_shipping_city(),
					'shipping_state'      => $order->get_shipping_state(),
					'shipping_zip_code'   => $order->get_shipping_postcode(),
					'shipping_country'    => $order->get_shipping_country(),
					'order_note'          => $order->get_customer_note(),
					'custom_order_note'   => $order->get_meta( 'custom_order_note' ),
					'currency'            => html_entity_decode( $order->get_currency() ),
					'total_price'         => $order->get_total(),
				];
			}
		}

		return $data_array;
	}

	public static function get_csv ( $report_data )
	{
		$csv_file_name   = 'wpessential-order-export-woo-' . time() . '.csv';
		$plugin_file_dir = '/wpessential-order-export-woo';
		$dir             = wp_get_upload_dir()[ 'basedir' ] . $plugin_file_dir;
		$file_dir_set    = $dir . '/' . $csv_file_name;
		$fop             = @fopen( $file_dir_set, 'wb' );

		$header_displayed = false;
		if ( ! empty( $report_data ) ) {
			foreach ( $report_data as $data ) {
				if ( ! $header_displayed ) {
					fputcsv( $fop, array_keys( $data ) );
					$header_displayed = true;
				}
				fputcsv( $fop, $data );

			}
		}
		fclose( $fop );

		if ( ! file_exists( $file_dir_set ) ) {
			return false;
		}

		return wp_get_upload_dir()[ 'baseurl' ] . "{$plugin_file_dir}/{$csv_file_name}";
	}
}
