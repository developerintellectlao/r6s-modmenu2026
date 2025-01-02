<?php
/**
 * Sidebar Blocks Extension.
 *
 * @package Rishi_Companion\Modules\Helpers
 */

namespace Rishi_Companion\Modules\Helpers;

/**
 * Class Sidebar_Blocks
 *
 * Handles the initialization and asset loading for sidebar blocks.
 */
class Sidebar_Blocks {

	/**
	 * Sidebar_Blocks constructor.
	 *
	 * Hooks into WordPress to initialize blocks and enqueue necessary assets.
	 */
	public function __construct() {
		$this->init();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 11 );
	}

	/**
	 * Initializes the blocks.
	 */
	public function init() {
		require_once plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'includes/blocks/blocks.php';
	}

	/**
	 * Enqueues the necessary assets for the blocks.
	 */
	public function enqueue_assets() {
		$dependencies_file_path     = \plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/public.asset.php';
		$dependencies_file_path_css = \plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/blocks.asset.php';
		$version                    = ( ! empty( $dependencies_file_path_css['version'] ) ) ? $dependencies_file_path_css['version'] : '';

		if ( file_exists( $dependencies_file_path ) ) {
			$public_deps = include_once $dependencies_file_path;

			wp_enqueue_style(
				'rishi-companion-blocks-public', // Handle for common block css
				esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/blocks.css',
				array(),
				$version
			);

			wp_register_script(
				'rishi-companion-tab', // Handle for PostsTab JS
				esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/postsTab.js',
				$public_deps['dependencies'],
				$public_deps['version'],
				true
			);
		}
	}
}
