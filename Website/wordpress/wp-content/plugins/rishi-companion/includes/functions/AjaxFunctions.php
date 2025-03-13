<?php
/**
 * AJAX Functions.
 *
 * @package Rishi_Companion
 */

namespace Rishi_Companion\Functions;

use Rishi_Companion\Module_Manager;
use Rishi_Companion\Plugin_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Ajax Settings.
 */
class AjaxFunctions {

	/**
	 * Plugin manager instance.
	 *
	 * @var Plugin_Manager
	 */
	private $plugin_manager;

	/**
	 * Extension manager instance.
	 *
	 * @var Module_Manager
	 */
	private $module_manager;

	/**
	 * Constructor.
	 */
	public function __construct( Plugin_Manager $plugin_manager, Module_Manager $module_manager ) {
		$this->plugin_manager = $plugin_manager;
        $this->module_manager = $module_manager;
        $this->init();
	}

	/**
	 * Initialization.
	 */
	private function init() {
		// Initialize hooks.
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		// AJAX for Plugin Status.
		add_action( 'wp_ajax_rishi_get_plugins_status', array( $this, 'rishi_get_plugins_status' ) );

		// AJAX for Plugin Download.
		add_action( 'wp_ajax_rishi_get_plugin_download', array( $this, 'rishi_get_plugin_download' ) );

		// AJAX for Plugin Activate.
		add_action( 'wp_ajax_rishi_get_plugin_activate', array( $this, 'rishi_get_plugin_activate' ) );

		// AJAX for Plugin Deactivate.
		add_action( 'wp_ajax_rishi_get_plugin_deactivate', array( $this, 'rishi_get_plugin_deactivate' ) );

		// AJAX for Addons Status.
		add_action( 'wp_ajax_rishi_get_extensions_status', array( $this, 'rishi_get_extensions_status' ) );

		// AJAX for Addons Activate.
		add_action( 'wp_ajax_rishi_enable_extension', array( $this, 'rishi_enable_extension' ) );

		// AJAX for Addons Deactivate.
		add_action( 'wp_ajax_rishi_disable_extension', array( $this, 'rishi_disable_extension' ) );
	}

	private function check_capability_and_retrieve_from_request($cap, $type) {
        $this->check_capability($cap);
        return $this->retrieve_from_request($type);
    }

	/**
	 * Common function to check capabilities.
	 *
	 * @param string $cap Capability to check.
	 * @return bool Whether the user has the capability.
	 */
	private function check_capability( $cap ) {
		if ( ! $this->plugin_manager->user_access( $cap ) ) {
			wp_send_json_error();
			exit;
		}
	}

	/**
	 * Common function to get the extension or plugin from the request.
	 *
	 * @param string $type The type of data to retrieve ('extension' or 'plugin').
	 * @return string The sanitized extension or plugin name.
	 */
	private function retrieve_from_request( $type ) {
		$data = filter_input( INPUT_POST, $type, FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! $data ) {
			wp_send_json_error(
				array(
					'message' => "Error: {$type} not set in request.",
				)
			);
		}
		return $data;
	}

	/**
	 * Get Plugin Status
	 */
	public function rishi_get_plugins_status() {

		$this->check_capability( 'edit_plugins' );
		$result = array();

		$plugins = $this->plugin_manager->get_config();

		foreach ( $plugins as $plugin => $value ) {
			$installed_path = $this->plugin_manager->check_plugin_installed( $plugin );

			if ( ! $installed_path ) {
				$status = 'uninstalled'; // Plugin is not installed.
			} else {
				$status = is_plugin_active( $value['file'] ) ? 'activated' : 'deactivated';
			}

			$result[] = array(
				'name'   => $plugin,
				'status' => $status,
			);
		}

		wp_send_json_success( $result );
	}

	/**
	 * Get Plugin Download
	 */
	public function rishi_get_plugin_download() {
		$plugin = $this->check_capability_and_retrieve_from_request('install_plugins', 'plugin');
        $install = $this->plugin_manager->install_plugin( $plugin );
        if ( $install ) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Error installing plugin.');
            exit;
        }
	}

	/**
	 * Get Plugin Activate
	 */
	public function rishi_get_plugin_activate() {
		$plugin = $this->check_capability_and_retrieve_from_request('edit_plugins', 'plugin');
		$all_installed_plugins = $this->plugin_manager->get_plugins();
        $plugin_file = WP_PLUGIN_DIR .'/'. $all_installed_plugins[$plugin]['file'];
        $result = $this->plugin_manager->activate_plugin( $plugin_file );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( 'Error activating plugin: ' . $result->get_error_message() );
			return;
		}
		wp_send_json_success();
	}

	/**
	 * Get Plugin Deactivate
	 */
	public function rishi_get_plugin_deactivate() {
		$plugin = $this->check_capability_and_retrieve_from_request('edit_plugins', 'plugin');
		$all_installed_plugins = $this->plugin_manager->get_plugins();
        $plugin_file = WP_PLUGIN_DIR .'/'. $all_installed_plugins[$plugin]['file'];
        $result = $this->plugin_manager->deactivate_plugin( $plugin_file );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( 'Error deactivating plugin: ' . $result->get_error_message() );
			return;
		}
		wp_send_json_success();
	}

	/**
	 * Get Extension Status
	 *
	 * @return void
	 */
	public function rishi_get_extensions_status() {
		$this->check_capability( 'edit_theme_options' );
		$data = $this->module_manager->retrieve_extensions();
		if ( ! $data ) {
			wp_send_json_error( 'No extensions found.' );
			return;
		}
		wp_send_json_success( $data );
	}

	/**
	 * Get Extension Activate
	 *
	 * @return void
	 */
	public function rishi_enable_extension() {
		$extension = $this->check_capability_and_retrieve_from_request('edit_theme_options', 'extension');
		$this->module_manager->enableModule( $extension );
		wp_send_json_success();
	}

	/**
	 * Get Extension Deactivate
	 *
	 * @return void
	 */
	public function rishi_disable_extension() {
		$extension = $this->check_capability_and_retrieve_from_request('edit_theme_options', 'extension');
		$this->module_manager->disableModule( $extension );
		wp_send_json_success();
	}
}
$plugin_manager = new Plugin_Manager();
$module_manager = new Module_Manager();
new AjaxFunctions($plugin_manager, $module_manager);
