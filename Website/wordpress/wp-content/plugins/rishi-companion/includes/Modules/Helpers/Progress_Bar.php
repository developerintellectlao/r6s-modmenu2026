<?php
/**
 * Progress Bar Extension.
 *
 * @package Rishi_Companion\Modules\Helpers
 */

namespace Rishi_Companion\Modules\Helpers;

/**
 * Class Progress_Bar
 *
 * Handles the display and functionality of the progress bar.
 */
class Progress_Bar {

	/**
	 * Progress_Bar constructor.
	 *
	 * Hooks into WordPress to display the progress bar and enqueue necessary assets.
	 */
	public function __construct() {
		add_action( 'wp_body_open', array( $this, 'rishi_progress_bar' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 11 );
	}

	/**
	 * Displays the progress bar based on theme settings.
	 */
	public function rishi_progress_bar() {
		$displayProgress    = get_theme_mod( 'displayProgress', 'post' );
		$display_top_bottom = get_theme_mod( 'display_top_bottom', 'top' );

		if ( 'everywhere' === $displayProgress ) {
			echo '<div data-location="' . esc_attr( $display_top_bottom ) . '" data-progress="everywhere" id="rishi-progress-bar"><progress value="0"></progress></div>';
		}

		if ( 'post' === $displayProgress && is_single() ) {
			echo '<div data-location="' . esc_attr( $display_top_bottom ) . '" data-progress="post" id="rishi-progress-bar"><progress value="0"></progress></div>';
		}

		if ( 'page' === $displayProgress && is_page() ) {
			echo '<div data-location="' . esc_attr( $display_top_bottom ) . '" data-progress="page" id="rishi-progress-bar"><progress value="0"></progress></div>';
		}
	}

	/**
	 * Enqueues the necessary assets for the progress bar.
	 */
	public function enqueue_assets() {
		$dependencies_file_path = plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/progressBar.asset.php';
		$js_dependencies        = ( ! empty( $dependencies_file_path['dependencies'] ) ) ? $dependencies_file_path['dependencies'] : array();
		$version                = ( ! empty( $dependencies_file_path['version'] ) ) ? $dependencies_file_path['version'] : '';

		wp_enqueue_script(
			'rishi-companion-progress',
			esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/progressBar.js',
			$js_dependencies,
			$version,
			true
		);
	}
}
