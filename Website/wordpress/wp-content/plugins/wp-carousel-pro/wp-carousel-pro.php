<?php
/**
 * A carousel plugin for WordPress.
 *
 * @link              https://shapedplugin.com/
 * @since             3.0.0
 * @package           WP_Carousel_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       WP Carousel Pro
 * Plugin URI:        https://wordpresscarousel.com/
 * Description:       Responsive WordPress Carousel plugin to create beautiful carousels easily. Build responsive Image Carousel, Post Carousel, WooCommerce Product Carousel, Content Carousel, Video Carousel, and more.
 * Version:           4.1.1
 * Author:            ShapedPlugin LLC
 * Author URI:        https://shapedplugin.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-carousel-pro
 * Domain Path:       /languages
 * WC requires at least: 5.0
 * WC tested up to: 9.3.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


require_once __DIR__ . '/vendor/autoload.php';

define( 'WPCAROUSEL_PRO_FILE', __FILE__ );
define( 'WPCAROUSEL_BASENAME', plugin_basename( __FILE__ ) );
define( 'WPCAROUSEL_VERSION', '4.1.1' );
define( 'WPCAROUSEL_PATH', plugin_dir_path( __FILE__ ) . 'src/' );
define( 'WPCAROUSEL_INCLUDES', WPCAROUSEL_PATH . '/includes' );
define( 'WPCAROUSEL_URL', plugin_dir_url( __FILE__ ) . 'src/' );
define( 'WPCAROUSEL_ITEM_NAME', 'WP Carousel Pro' );
define( 'WPCAROUSEL_ITEM_SLUG', 'wp-carousel-pro' );
define( 'WPCAROUSEL_ITEM_ID', 411 );
define( 'WPCAROUSEL_STORE_URL', 'https://shapedplugin.com' );
define( 'WPCAROUSEL_PRODUCT_URL', 'https://wordpresscarousel.com/' );

if ( ! function_exists( 'activate_wp_carousel_pro' ) ) {
	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/Plugin_Activator.php
	 */
	function activate_wp_carousel_pro() {
		$activate_wp_carousel_pro = new ShapedPlugin\WPCarouselPro\Includes\Plugin_Activator();
		$activate_wp_carousel_pro::activate();
	}
	register_activation_hook( __FILE__, 'activate_wp_carousel_pro' );
}

if ( ! function_exists( 'sp_wpcp' ) ) {
	/**
	 * Main instance of WP Carousel Pro
	 *
	 * Returns the main instance of the WP Carousel Pro.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	function sp_wpcp() {
		$plugin = new ShapedPlugin\WPCarouselPro\Includes\WPCarouselPro();
		$plugin = $plugin::init();
		$plugin->run();
	}
	// Launch it out.
	sp_wpcp();
}
