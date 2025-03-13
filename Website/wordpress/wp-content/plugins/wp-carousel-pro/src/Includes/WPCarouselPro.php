<?php
/**
 * A carousel plugin for WordPress.
 *
 * @link              https://shapedplugin.com/
 * @since             3.0.0
 * @package           WP_Carousel_Pro
 *
 * @wordpress-plugin
 */

namespace ShapedPlugin\WPCarouselPro\Includes;

use ShapedPlugin\WPCarouselPro\Admin\Admin;
use ShapedPlugin\WPCarouselPro\Frontend\Frontend;
use ShapedPlugin\WPCarouselPro\Admin\views\Help_Page;
use ShapedPlugin\WPCarouselPro\Admin\Elementor_Addon;
use ShapedPlugin\WPCarouselPro\Admin\Gutenberg_Block;
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main class of the plugin
 *
 * @package WP_Carousel_Pro
 * @author Shamim Mia <shamhagh@gmail.com>
 */
class WPCarouselPro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	public $loader;

	/**
	 * The unique name of this plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    3.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Plugin textdomain.
	 *
	 * @since 3.0.0
	 *
	 * @var string
	 */
	public $domain = 'wp-carousel-pro';

	/**
	 * Plugin license.
	 *
	 * @since 3.2.0
	 *
	 * @var string
	 */
	public $license;

	/**
	 * Minimum PHP version required
	 *
	 * @since 3.0.0
	 * @var string
	 */
	private $min_php = '5.6';

	/**
	 * Plugin file.
	 *
	 * @var string
	 */
	private $file = __FILE__;

	/**
	 * Holds class object
	 *
	 * @var object
	 *
	 * @since 3.0.0
	 */
	private static $instance;

	/**
	 * Initialize the WPCarouselPro() class
	 *
	 * @since 3.0.0
	 * @return object
	 */
	public static function init() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof WPCarouselPro ) ) {
			self::$instance = new WPCarouselPro();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    3.0.0
	 */
	public function setup() {

		$this->plugin_name = 'wp-carousel-pro';
		$this->version     = WPCAROUSEL_VERSION;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_common_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Loader. Orchestrates the hooks of the plugin.
	 * - WP_Carousel_Pro_i18n. Defines internationalization functionality.
	 * - Admin. Defines all hooks for the admin area.
	 * - Frontend. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader = new Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Carousel_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Carousel_Pro_I18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register common hooks.
	 *
	 * @since 3.0.0
	 * @access private
	 */
	private function define_common_hooks() {
		$plugin_cpt = new Register_Post_Type( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $plugin_cpt, 'wp_carousel_post_type', 11 );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_admin_styles' );

		$this->loader->add_filter( 'post_updated_messages', $plugin_admin, 'wpcp_carousel_updated_messages', 10, 2 );
		$this->loader->add_filter( 'manage_sp_wp_carousel_posts_columns', $plugin_admin, 'filter_carousel_admin_column' );
		$this->loader->add_action( 'manage_sp_wp_carousel_posts_custom_column', $plugin_admin, 'display_carousel_admin_fields', 10, 2 );
		$this->loader->add_action( 'admin_action_sp_wpcp_duplicate_carousel', $plugin_admin, 'sp_wpcp_duplicate_carousel' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'sp_wpcp_duplicate_carousel_link', 10, 2 );
		$this->loader->add_filter( 'admin_footer_text', $plugin_admin, 'sp_wpcp_review_text', 10, 2 );

		// License Page.
		$manage_license = new License( WPCAROUSEL_PRO_FILE, WPCAROUSEL_VERSION, 'ShapedPlugin', WPCAROUSEL_STORE_URL, WPCAROUSEL_ITEM_ID, WPCAROUSEL_ITEM_SLUG );

		// Admin Menu.
		$this->loader->add_action( 'admin_init', $manage_license, 'wp_carousel_pro_activate_license' );
		$this->loader->add_action( 'admin_init', $manage_license, 'wp_carousel_pro_deactivate_license' );

		$this->loader->add_action( 'wp_carousel_pro_weekly_scheduled_events', $manage_license, 'check_license_status' );
		// This code for testing.
		// Init Updater.
		$this->loader->add_action( 'admin_init', $manage_license, 'init_updater', 0 );

		// Display notices to admins.
		$this->loader->add_action( 'admin_notices', $manage_license, 'license_active_notices' );
		$this->loader->add_action( 'in_plugin_update_message-' . WPCAROUSEL_BASENAME, $manage_license, 'plugin_row_license_missing', 10, 2 );

		// Redirect after active.
		$this->loader->add_action( 'activated_plugin', $this, 'redirect_to' );

		// Help Page.
		$help_page = new Help_Page( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $help_page, 'help_admin_menu', 40 );
		$this->loader->add_filter( 'plugin_action_links', $help_page, 'add_plugin_action_links', 10, 2 );

		$import_export = new Import_Export( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_ajax_wpcp_export_shortcodes', $import_export, 'export_shortcodes' );
		$this->loader->add_action( 'wp_ajax_wpcp_import_shortcodes', $import_export, 'import_shortcodes' );

		// DB Updater.
		new Backward_Compatibility();

		// Gutenberg block.
		if ( version_compare( $GLOBALS['wp_version'], '5.3', '>=' ) ) {
			new Gutenberg_Block();
		}
		// Elementor shortcode block.
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			new Elementor_Addon();
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    3.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Frontend( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_loaded', $plugin_public, 'register_all_scripts' );
		$this->loader->add_action( 'save_post', $plugin_public, 'delete_page_wpcp_option_on_save' );

		$plugin_shortcode = new Register_Shortcode( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_shortcode( 'sp_wpcarousel', $plugin_shortcode, 'sp_wp_carousel_shortcode' );
	}

	/**
	 * Redirect after license activation.
	 *
	 * @param mixed $plugin plugin.
	 * @return void
	 */
	public function redirect_to( $plugin ) {
		$manage_license     = new License( WPCAROUSEL_PRO_FILE, WPCAROUSEL_VERSION, 'ShapedPlugin', WPCAROUSEL_STORE_URL, WPCAROUSEL_ITEM_ID, WPCAROUSEL_ITEM_SLUG );
		$license_key_status = $manage_license->get_license_status();
		$license_status     = ( is_object( $license_key_status ) ? $license_key_status->license : '' );
		if ( WPCAROUSEL_BASENAME === $plugin && 'valid' !== $license_status ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=1' ) );
			exit();
		}
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     3.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     3.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     3.0.0
	 * @return    Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    3.0.0
	 */
	public function run() {
		$this->loader->run();
	}
} // SP_WP_Carousel_Pro
