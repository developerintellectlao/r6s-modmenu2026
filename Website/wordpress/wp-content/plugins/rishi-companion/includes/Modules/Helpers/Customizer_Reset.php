<?php
/**
 * Customizer Reset Extension.
 *
 * @package Rishi_Companion\Modules\Helpers
 */

namespace Rishi_Companion\Modules\Helpers;

/**
 * Class Customizer_Reset
 *
 * Handles the initialization and asset loading for customizer reset.
 */
class Customizer_Reset {

	/**
	 * Customizer_Reset constructor.
	 *
	 * Hooks into WordPress to initialize blocks and enqueue necessary assets.
	 */
	public function __construct() {
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'wp_ajax_rishi_customizer_reset', array( $this, 'rishi_customizer_reset' ) );
	}

	/**
	 * Enqueue scripts and styles for the extension.
	 */
	public function enqueue() {
		$dependencies_file_path = plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/customizerReset.asset.php';

		if ( file_exists( $dependencies_file_path ) ) {
			$customizer_deps = include_once $dependencies_file_path;
			$js_dependencies = ( ! empty( $customizer_deps['dependencies'] ) ) ? $customizer_deps['dependencies'] : array();
			$version         = ( ! empty( $customizer_deps['version'] ) ) ? $customizer_deps['version'] : '';

			wp_enqueue_style( 'rishi-customizer-reset', esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/customizerReset.css', array(), $version );
			wp_enqueue_script( 'rishi-customizer-reset', esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/customizerReset.js', $js_dependencies, $version, true );
			wp_localize_script(
				'rishi-customizer-reset',
				'Rishi_Reset_Data',
				array(
					'nonce'   => wp_create_nonce( 'rishi-customizer-reset' ),
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				)
			);
		}

	}

	/**
	 * Reset the customizer settings.
	 */
	public function reset_customizer() {
		remove_theme_mods();
	}

	/**
	 * Set the theme mod transient.
	 * Function that saves customizer theme option in transient for 7 days.
	 */
	public function set_theme_mod_transient() {
		$theme_mods = get_option( 'theme_mods_rishi' );
		if ( $theme_mods ) {
			set_transient( 'rishi_theme_mod', $theme_mods, WEEK_IN_SECONDS );
		}
	}

	/**
	 * Handle the customizer reset AJAX request.
	 */
	public function rishi_customizer_reset() {

		if ( ! \check_ajax_referer( 'rishi-customizer-reset', 'nonce', false ) ) {
			wp_send_json_error( 'invalid_nonce' );
		}

		$this->set_theme_mod_transient();
		$this->reset_customizer();

		wp_send_json_success();
		wp_die();
	}
}
