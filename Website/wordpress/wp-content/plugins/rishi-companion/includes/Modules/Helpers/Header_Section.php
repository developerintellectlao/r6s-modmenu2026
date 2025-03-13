<?php
/**
 * Advanced Header Extension.
 *
 * This class handles the Header_Section optimization of the website.
 *
 * @package Rishi_Companion\Modules\Helpers
 */
namespace Rishi_Companion\Modules\Helpers;

class Header_Section {

	/**
	 * Header_Section constructor.
	 *
	 * Hooks into WordPress to initialize blocks and enqueue necessary assets.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 11 );
	}

	/**
	 * Enqueues the necessary assets for the blocks.
	 */
	public function enqueue_assets() {
		$dependencies_file_path = \plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/stickyHeader.asset.php';

		if ( file_exists( $dependencies_file_path ) && get_theme_mod( 'has_sticky_header', 'no' ) === 'yes' ) {
			$public_deps = include_once $dependencies_file_path;

			wp_enqueue_script(
				'rishi-sticky-header', // Handle.
				esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/stickyHeader.js',
				$public_deps['dependencies'],
				$public_deps['version'],
				true
			);
		}
	}
}
