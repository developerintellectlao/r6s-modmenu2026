<?php
/**
 * Admin area settings and hooks.
 *
 * @package Rishi_Companion
 */

namespace Rishi_Companion;

defined( 'ABSPATH' ) || exit;

/**
 * Global Settings.
 */
class Plugin_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();

	}

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init() {
		// Create a new Builder_Element_Filter object.
		$builder_element_filter = new Helpers\Builder_Element_Filter();

		// Initialize hooks.
		$this->init_hooks();

		// Allow 3rd party to remove hooks.
		do_action( 'rishi_companion_admin_unhook', $this );
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init_hooks() {

		// Add a filter to customize the sections directory.
		add_filter(
			'rishi_customizer_sections_directory',
			function ( $directories ) {
				$directories['Rishi_Companion\\Modules\\Sections\\'] = __DIR__ . '/Modules/Sections';
				return $directories;
			}
		);

		// Enqueue admin scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// If the theme mod 'ed_favicon' is set to 'yes', remove the favicon request.
		if ( get_theme_mod( 'ed_favicon', 'yes' ) === 'yes' ) {
			add_action( 'admin_head', array( $this, 'rishi_remove_favicon_request' ), 10 );
			add_action( 'wp_head', array( $this, 'rishi_remove_favicon_request' ), 10 );
		}

		// Add a block category.
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );

		// Register post meta.
		add_action( 'init', array( $this, 'register_post_meta' ), 9999999 );

		// Include user meta.
		$this->includes_users_meta();
	}

	/**
	 * Include user meta.
	 *
	 * @return void
	 */
	public function includes_users_meta() {
		include_once plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . '/includes/additions/Users_Meta.php';
	}

	/**
	 * Disable Automatic Favicon Request
	 */
	public function rishi_remove_favicon_request() {
		// Output a blank favicon to prevent automatic requests.
		echo '<link rel="icon" href="data:,">';
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Create instances of Plugin_Manager and Module_Manager.
		$plugin_manager    = new Plugin_Manager();
		$module_manager = new Module_Manager();

		// Get the configurations.
		$free_plugins     = $plugin_manager->get_config();
		$rishi_extensions = $module_manager->get_config();

		// Include the dashboard assets.
		$global_deps = include_once plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . '/build/dashboard.asset.php';
		$version     = ( ! empty( $global_deps['version'] ) ) ? $global_deps['version'] : '';

		// Enqueue the dashboard style.
		wp_enqueue_style( 'rishi-companion-dashboard', esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/dashboard.css', array(), $version );

		// Register the dashboard script.
		wp_enqueue_script( 'rishi-companion-dashboard', esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/dashboard.js', $global_deps['dependencies'], $global_deps['version'], true );

		// Localize the dashboard script.
		wp_localize_script(
			'rishi-companion-dashboard',
			'RishiCompanionDashboard',
			array(
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
				'adminURL'     => esc_url( admin_url() ),
				'siteURL'      => esc_url( home_url( '/' ) ),
				'pluginUrl'    => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ),
				'pluginName'   => $free_plugins,
				'extensions'   => $rishi_extensions,
				'customizeURL' => admin_url( '/customize.php?autofocus' ),
				'plugin_data'  => apply_filters( 'rishi_companion_dashboard_localizations', array() ),
				'proActivated' => \function_exists( 'rishi_is_pro_activated' ) ? \rishi_is_pro_activated() : false,
			)
		);
	}

	/**
	 * Add Block Category
	 *
	 * @param array $categories Existing block categories.
	 * @return array Modified block categories.
	 */
	public function add_block_category( $categories ) {
		// Setup category array.
		$rishi_blocks_category = array(
			'slug'  => 'rishi-blocks',
			'title' => __( 'Rishi Blocks', 'rishi-companion' ),
		);

		// Make a new category array and insert ours at position 1.
		$new_categories    = array();
		$new_categories[0] = $rishi_blocks_category;

		// Rebuild categories array.
		foreach ( $categories as $category ) {
			$new_categories[] = $category;
		}

		return $new_categories;
	}

	/**
	 * Register Block meta
	 *
	 * @return void
	 */
	public function register_post_meta() {
		// Register a meta field for the post view count.
		register_post_meta(
			'post',
			'_rishi_post_view_count',
			array(
				'single'            => true,
				'type'              => 'number',
				'show_in_rest'      => true,
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

}
