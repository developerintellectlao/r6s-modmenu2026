<?php
/**
 * Perform all updating activities accordion to the plugin versions.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes
 * @author     ShapedPlugin <shapedplugin@gmail.com>
 */

namespace ShapedPlugin\WPCarouselPro\Includes;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Update metabox and options settings during update.
 */
class Backward_Compatibility {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = array(
		'3.2.0'  => 'updates/update-3.2.0.php',
		'3.2.2'  => 'updates/update-3.2.2.php',
		'3.2.4'  => 'updates/update-3.2.4.php',
		'3.3.2'  => 'updates/update-3.3.2.php',
		'3.3.3'  => 'updates/update-3.3.3.php',
		'3.5.0'  => 'updates/update-3.5.0.php',
		'3.7.0'  => 'updates/update-3.7.0.php',
		'3.8.0'  => 'updates/update-3.8.0.php',
		'3.9.0'  => 'updates/update-3.9.0.php',
		'3.10.1' => 'updates/update-3.10.1.php',
		'3.10.3' => 'updates/update-3.10.3.php',
		'4.0.0'  => 'updates/update-4.0.0.php',
		'4.1.0'  => 'updates/update-4.1.0.php',
	);

	/**
	 * Binding all events
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ) );
	}

	/**
	 * Check if need any update.
	 *
	 * @since 3.2.0
	 *
	 * @return boolean
	 */
	public function does_need_update() {
		$installed_version = get_option( 'wp_carousel_pro_version' );
		$first_version     = get_option( 'wp_carousel_pro_first_version' );
		$activation_date   = get_option( 'wp_carousel_pro_activation_date' );

		if ( ! $installed_version ) {
			update_option( 'wp_carousel_pro_version', WPCAROUSEL_VERSION );
			update_option( 'wp_carousel_pro_db_version', WPCAROUSEL_VERSION );
		}
		if ( false === $first_version ) {
			update_option( 'wp_carousel_pro_first_version', WPCAROUSEL_VERSION );
		}
		if ( false === $activation_date ) {
			update_option( 'wp_carousel_pro_activation_date', current_time( 'timestamp' ) );
		}

		if ( version_compare( $installed_version, WPCAROUSEL_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Do updates.
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates
	 *
	 * @since 3.2.0
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->does_need_update() ) {
			return;
		}

		$installed_version = get_option( 'wp_carousel_pro_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'wp_carousel_pro_version', $version );
			}
		}

		update_option( 'wp_carousel_pro_version', WPCAROUSEL_VERSION );
	}
}
