<?php
/**
 * Wp Carousel Pro admin helper functions.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'sp_wpcp_array_search' ) ) {
	/**
	 * Array search key & value
	 *
	 * @param  mixed $array search array or not.
	 * @param  mixed $key array key.
	 * @param  mixed $value value.
	 * @return statement
	 */
	function sp_wpcp_array_search( $array, $key, $value ) {

		$results = array();

		if ( is_array( $array ) ) {
			if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
				$results[] = $array;
			}

			foreach ( $array as $sub_array ) {
				$results = array_merge( $results, sp_wpcp_array_search( $sub_array, $key, $value ) );
			}
		}

		return $results;

	}
}

if ( ! function_exists( 'sp_wpcp_timeout' ) ) {
	/**
	 * Between Microtime
	 *
	 * @param  mixed $timenow current time.
	 * @param  mixed $starttime start time.
	 * @param  mixed $timeout time out.
	 * @return statement
	 */
	function sp_wpcp_timeout( $timenow, $starttime, $timeout = 30 ) {
		return ( ( $timenow - $starttime ) < $timeout ) ? true : false;
	}
}

if ( ! function_exists( 'sp_wpcp_wp_editor_api' ) ) {
	/**
	 *
	 * Check for wp editor api
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_wp_editor_api() {
		global $wp_version;
		return version_compare( $wp_version, '4.8', '>=' );
	}
}
