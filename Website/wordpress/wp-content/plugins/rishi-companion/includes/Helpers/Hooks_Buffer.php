<?php
/**
 * Hooks Buffer and Development Changes.
 *
 * @package Rishi_Companion
 */

namespace Rishi_Companion\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Rishi Hooks Buffer.
 *
 * @package Rishi_Companion
 */
class Hooks_Buffer {

	/**
	 * Holds the validity of the buffer.
	 *
	 * @var bool
	 */
	private static $valid_buffer = true;

	/**
	 * Initializes the buffer.
	 *
	 * @return void
	 */
	public static function init() {
		// Initial checks.
		if ( is_admin() || rishi_companion_is_dynamic_request() || rishi_companion_is_page_builder() || is_customize_preview() ) {
			return;
		}

		// Add buffer actions.
		add_action( 'init', array( __CLASS__, 'start' ), 0 );
		add_action( 'template_redirect', array( __CLASS__, 'start' ) );
	}

	/**
	 * Starts the buffer.
	 *
	 * @return void
	 */
	public static function start() {
		$current_filter = current_filter();

		if ( self::$valid_buffer && ! empty( $current_filter ) && has_filter( 'rishi_companion_output_buffer_' . $current_filter ) ) {

			// Exclude certain requests.
			if ( is_embed() || is_feed() || is_preview() ) {
				self::$valid_buffer = false;
				return;
			}

			ob_start(
				function( $html ) use ( $current_filter ) {

					if ( 'init' === $current_filter && ! self::is_valid_buffer( $html ) ) {
						self::$valid_buffer = false;
						return $html;
					}

					// Run buffer filters.
					$html = (string) apply_filters( 'rishi_companion_output_buffer_' . $current_filter, $html );

					// Return processed HTML.
					return $html;
				}
			);
		}
	}

	/**
	 * Checks if the buffer is valid.
	 *
	 * @param string $html The HTML to check.
	 * @return bool Whether the buffer is valid.
	 */
	private static function is_valid_buffer( $html ) {
		// Check for valid/invalid tags.
		if ( false === stripos( $html, '<html' ) || false === stripos( $html, '</body>' ) || false !== stripos( $html, '<xsl:stylesheet' ) ) {
			return false;
		}

		// Check for doctype.
		if ( ! preg_match( '/^<!DOCTYPE.+html/i', ltrim( $html ) ) ) {
			return false;
		}

		// Check for invalid URLs.
		$current_url = home_url( ! empty( $_SERVER['REQUEST_URI'] ) );
		$matches     = array( '.xml', '.txt', '.php' );

		foreach ( $matches as $match ) {
			if ( false !== stripos( $current_url, $match ) ) {
				return false;
			}
		}

		return true;
	}
}
