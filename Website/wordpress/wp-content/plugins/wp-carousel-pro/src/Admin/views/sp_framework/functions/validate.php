<?php
/**
 * Wp Carousel Pro validate functions..
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

if ( ! function_exists( 'sp_wpcp_validate_email' ) ) {
	/**
	 *
	 * Email validate
	 *
	 * @param  mixed $value value.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_validate_email( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
			return esc_html__( 'Please enter a valid email address.', 'wp-carousel-pro' );
		}

	}
}

if ( ! function_exists( 'sp_wpcp_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate
	 *
	 * @param  mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_validate_numeric( $value ) {
		if ( ! is_numeric( $value ) ) {
			return esc_html__( 'Please enter a valid number.', 'wp-carousel-pro' );
		}
	}
}

if ( ! function_exists( 'sp_wpcp_validate_required' ) ) {
		/**
		 *
		 * Required validate
		 *
		 * @param  mixed $value value.
		 * @since 1.0.0
		 * @version 1.0.0
		 */
	function sp_wpcp_validate_required( $value ) {

		if ( empty( $value ) ) {
			return esc_html__( 'This field is required.', 'wp-carousel-pro' );
		}

	}
}

if ( ! function_exists( 'sp_wpcp_validate_url' ) ) {
	/**
	 *
	 * URL validate
	 *
	 * @param  mixed $value value.
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_validate_url( $value ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			return esc_html__( 'Please enter a valid URL.', 'wp-carousel-pro' );
		}

	}
}

if ( ! function_exists( 'sp_wpcp_customize_validate_email' ) ) {
	/**
	 *
	 * Email validate for Customizer
	 *
	 * @param mixed $validity email validity.
	 * @param mixed $value email.
	 * @param mixed $wp_customize email customize.
	 *  @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_customize_validate_email( $validity, $value, $wp_customize ) {

		if ( ! sanitize_email( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid email address.', 'wp-carousel-pro' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'sp_wpcp_customize_validate_numeric' ) ) {
	/**
	 *
	 * Numeric validate for Customizer
	 *
	 * @param mixed $validity number validity.
	 * @param mixed $value number.
	 * @param mixed $wp_customize number customize.
	 *  @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_customize_validate_numeric( $validity, $value, $wp_customize ) {

		if ( ! is_numeric( $value ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid number.', 'wp-carousel-pro' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'sp_wpcp_customize_validate_required' ) ) {
	/**
	 *
	 * Required validate for Customizer
	 *
	 * @param mixed $validity  validity.
	 * @param mixed $value validate.
	 * @param mixed $wp_customize customize.
	 *  @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_customize_validate_required( $validity, $value, $wp_customize ) {

		if ( empty( $value ) ) {
			$validity->add( 'required', esc_html__( 'This field is required.', 'wp-carousel-pro' ) );
		}

		return $validity;

	}
}

if ( ! function_exists( 'sp_wpcp_customize_validate_url' ) ) {
	/**
	 *
	 * URL validate for Customizer
	 *
	 * @param mixed $validity URL validity.
	 * @param mixed $value URL.
	 * @param mixed $wp_customize URL customize.
	 *  @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_customize_validate_url( $validity, $value, $wp_customize ) {

		if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
			$validity->add( 'required', esc_html__( 'Please enter a valid URL.', 'wp-carousel-pro' ) );
		}

		return $validity;

	}
}
