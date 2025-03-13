<?php
/**
 * Rishi_Companion_Plugin_Manager class
 *
 * This class is responsible for managing plugins.
 *
 * @package Rishi_Companion
 */

namespace Rishi_Companion;

defined( 'ABSPATH' ) || exit;

/**
 * Main Rishi_Companion_Plugin_Manager Class.
 *
 * This class is the main class for managing plugins.
 */
class Plugin_Manager {

	/**
	 * List of plugins.
	 *
	 * @var array
	 */
	protected $plugin_list = array();

	/**
	 * Constructor.
	 *
	 * Initialize the list of plugins.
	 */
	public function __construct() {
		$this->plugins_list();
	}

	/**
	 * Get the plugin configuration.
	 *
	 * @return array The plugin configuration.
	 */
	public function get_config() {
		return $this->plugin_list;
	}

	/**
	 * Get the list of plugins to be shown.
	 *
	 * This method sets the list of plugins to be shown.
	 */
	protected function plugins_list() {
		$this->plugin_list = array(
			'affiliatex'                         => array(
				'type'        => 'web',
				'title'       => esc_html__( 'AffiliateX', 'rishi-companion' ),
				'description' => esc_html__(
					'AffiliateX is a Gutenberg blocks plugin for affiliate marketers. You can create highly effective affiliate marketing blogs with AffiliateX blocks, increasing the conversion rate and boost your affiliate income.',
					'rishi-companion'
				),
				'icon'        => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'src/admin/dashboard/images/affiliate.png',
				'file'		  => 'affiliatex/affiliatex.php'
			),
			'mega-elements-addons-for-elementor' => array(
				'type'        => 'web',
				'title'       => esc_html__( 'Mega Elements', 'rishi-companion' ),
				'description' => esc_html__(
					'Mega Elements is a powerful and advanced all-in-one Elementor addons that help you create a beautiful website with ease.',
					'rishi-companion'
				),
				'icon'        => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'src/admin/dashboard/images/mega-elements.png',
				'file'		  => 'mega-elements-addons-for-elementor/mega-elements-addons-for-elementor.php'
			),
			'elementor'                          => array(
				'type'        => 'web',
				'title'       => esc_html__( 'Elementor Page Builder', 'rishi-companion' ),
				'description' => esc_html__(
					'Elementor is one of the most advanced frontend drag & drop page builders to help you create pixel-perfect websites in less time.',
					'rishi-companion'
				),
				'icon'        => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'src/admin/dashboard/images/elementor.png',
				'file'		  => 'elementor/elementor.php'
			),
			'woocommerce'                        => array(
				'type'        => 'web',
				'title'       => esc_html__( 'WooCommerce', 'rishi-companion' ),
				'description' => esc_html__(
					'WooCommerce is the world’s most popular open source eCommerce plugin that helps you create an online store easily.',
					'rishi-companion'
				),
				'icon'        => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'src/admin/dashboard/images/woo.png',
				'file'		  => 'woocommerce/woocommerce.php'
			),
			'contact-form-7'                     => array(
				'type'        => 'web',
				'title'       => esc_html__( 'Contact Form 7', 'rishi-companion' ),
				'description' => esc_html__(
					'Contact Form 7 is one of the most downloaded and popular contact form plugins to quickly add forms to your website.',
					'rishi-companion'
				),
				'icon'        => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'src/admin/dashboard/images/contact-form-7.png',
				'file'		  => 'contact-form-7/wp-contact-form-7.php'
			),
		);
	}

	/**
	 * Get the list of plugins.
	 *
	 * @return array The list of plugins.
	 */
	public function get_plugins() {
		return $this->plugin_list ?? array();
	}

	/**
	 * Check if a plugin is installed.
	 *
	 * @param string $slug The slug of the plugin.
	 * @return bool|string The plugin if it is installed, false otherwise.
	 */
	public function check_plugin_installed( $slug ) {
		$all_installed_plugins = $this->get_plugins();
		$plugin_file = isset( $all_installed_plugins[$slug] ) ? WP_PLUGIN_DIR .'/' . $all_installed_plugins[$slug]['file'] : $slug;
		if ( file_exists( $plugin_file ))  {
			return $slug;
		}
		return false;
	}

	/**
	 * Get the full name of a plugin.
	 *
	 * @param string $plugin The plugin to get the full name for.
	 * @return string|\WP_Error The full name of the plugin, or a WP_Error object on failure.
	 */
	public function get_plugin_name( $plugin ) {
		$plugin_name = $this->check_plugin_installed( $plugin );
		return is_wp_error( $plugin_name ) ? new \WP_Error() : $plugin_name;
	}

	/**
	 * Activate a plugin.
	 *
	 * @param string $plugin The plugin to activate.
	 * @return string|\WP_Error The result of the activation, or a WP_Error object on failure.
	 */
	public function activate_plugin( $plugin ) {
		$plugin_name = $this->get_plugin_name( $plugin );
		return is_wp_error( $plugin_name ) ? $plugin_name : activate_plugin( $plugin_name, '', false, true );
	}

	/**
	 * Deactivate a plugin.
	 *
	 * @param string $plugin The plugin to deactivate.
	 * @return string|\WP_Error The result of the deactivation, or a WP_Error object on failure.
	 */
	public function deactivate_plugin( $plugin ) {
		$plugin_name = $this->get_plugin_name( $plugin );
		return is_wp_error( $plugin_name ) ? $plugin_name : deactivate_plugins( $plugin_name );
	}

	/**
	 * Process plugin installation.
	 *
	 * @param string $plugin The plugin to install.
	 * @return bool|\WP_Error The result of the installation.
	 */
	public function install_plugin($plugin) {
		// Check if user has access
		if (!$this->user_access()) {
			throw new \Exception('User does not have sufficient access to install plugins.');
		}
		$plugins_list = $this->get_plugins();
		if ( ! array_key_exists( $plugin, $plugins_list ) || 'web' === $plugins_list[ $plugin ]['type'] ) {
			return $this->plugin_installer( $plugin );
		}
	}

	/**
	 * Check if the user has the capability to install a plugin.
	 *
	 * @return bool True if the user has the capability, false otherwise.
	 */
	public function user_access() {
		// Get the current user
		$current_user = wp_get_current_user();
		// Check if the WordPress installation is a multisite
		if (is_multisite()) {
			// If it's a multisite, check if the user is a super admin
			return is_super_admin( $current_user->ID );
		} else {
			// If it's a normal site, check if the user has the 'install_plugins' capability
			return in_array( 'install_plugins', $current_user->allcaps );
		}
	}

	/**
	 * Includes necessary files for plugin installation.
	 */
	protected function file_inclusion() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	}

	/**
	 * Installs a plugin.
	 *
	 * @param string $plugin_slug The slug of the plugin to install.
	 * @return bool True on success, false on failure.
	 * @throws \Exception If the plugin is not found in the WordPress Plugin Repository.
	 */
	public function plugin_installer( $plugin_slug ) {
		$this->file_inclusion();
		if ( $this->check_plugin_installed( $plugin_slug ) ) {
			return true;
		}
		$plugin_info = $this->get_plugin_info( $plugin_slug );
		// Check if the plugin exists in the WordPress Plugin Repository.
		if ( empty( $plugin_info->name ) ) {
			throw new \Exception( 'The requested plugin was not found in the WordPress Plugin Repository.' );
		}
		// Create a new instance of Plugin_Upgrader
		$upgrader = new \Plugin_Upgrader(new \Plugin_Installer_Skin());
		// Install the plugin
		$upgrader->install($plugin_info->download_link);
	}

	/**
	 * Retrieves plugin information.
	 *
	 * @param string $plugin_slug The slug of the plugin.
	 * @return object The plugin information.
	 * @throws \Exception If the plugin information could not be retrieved.
	 */
	private function get_plugin_info( $plugin_slug ) {
		// Fetch plugin information from the WordPress.org API.
		$api = plugins_api('plugin_information', ['slug' => $plugin_slug]);
		if ( is_wp_error( $api ) ) {
			throw new \Exception( 'Plugin information could not be retrieved: ' . $api->get_error_message() );
		}
		return $api;
	}

}
