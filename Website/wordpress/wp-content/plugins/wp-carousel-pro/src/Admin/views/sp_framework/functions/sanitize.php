<?php
/**
 * Wp Carousel Pro sanitize functions..
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

if ( ! function_exists( 'sp_wpcp_sanitize_replace_a_to_b' ) ) {
	/**
	 *
	 * Sanitize
	 * Replace letter a to letter b
	 *
	 * @param mixed $value sanitize.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_sanitize_replace_a_to_b( $value ) {
		return str_replace( 'a', 'b', $value );
	}
}

if ( ! function_exists( 'sp_wpcp_sanitize_title' ) ) {
	/**
	 *
	 * Sanitize title
	 *
	 * @param  mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_sanitize_title( $value ) {
		return sanitize_title( $value );
	}
}
