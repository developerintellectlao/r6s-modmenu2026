<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://shapedplugin.com/
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes
 */

namespace ShapedPlugin\WPCarouselPro\Includes;

/**
 * WP_Carousel_Pro_I18n class.
 *
 * @since      3.0.0
 */
class WP_Carousel_Pro_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    3.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wp-carousel-pro',
			false,
			dirname( WPCAROUSEL_BASENAME ) . '/languages/'
		);
	}
}
