<?php
/**
 * Main Rishi_Companion class
 *
 * @package Rishi_Companion
 */

namespace Rishi_Companion;

use Rishi_Companion\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Main Rishi_Companion Class.
 *
 * @class Plugin
 */
final class Plugin {
	/**
	 * Rishi_Companion version.
	 *
	 * @var string
	 */
	public $version = '2.0.2';

	/**
	 * Admin settings instance.
	 *
	 * @var Plugin_Admin
	 */
	protected $admin_settings;

	/**
	 * Public settings instance.
	 *
	 * @var Plugin_Public
	 */
	protected $public_settings;

	/**
	 * Updater instance.
	 *
	 * @var Rishi_Companion_Updater
	 */
	protected $updater;

	/**
	 * The single instance of the class.
	 *
	 * @var Plugin|null
	 */
	protected static $_instance = null;

	/**
	 * Main Rishi_Companion Instance.
	 *
	 * Ensures only one instance of Rishi_Companion is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @return Plugin - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Rishi_Companion Constructor.
	 */
	public function __construct() {
		$this->init_hooks();
		$this->_define_constants();
		$this->includes();

		Helpers\Hooks_Buffer::init();
		$dynamic_styles_filter = new Helpers\Dynamic_Styles();
		$dynamic_styles_filter->__construct();
		$this->admin_settings  = new Plugin_Admin();
		$this->public_settings = new Plugin_Public();
		if ( is_admin() ) {
			$this->updater = new Rishi_Companion_Updater();
		}

	}

	/**
	 * Hook into actions and filters.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );
	}

	/**
	 * Define Rishi_Companion Constants.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function _define_constants() {
		$this->define( 'RISHI_COMPANION_PLUGIN_NAME', 'rishi-companion' );
		$this->define( 'RISHI_COMPANION_ABSPATH', dirname( RISHI_COMPANION_PLUGIN_FILE ) . '/' );
		$this->define( 'RISHI_COMPANION_VERSION', $this->version );
		$this->define( 'RISHI_COMPANION_PLUGIN_URL', $this->plugin_url() );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name       Constant name.
	 * @param string|bool $value      Constant value.
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Output error message and disable plugin if requirements are not met.
	 *
	 * This fires on admin_notices.
	 *
	 * @since 1.0.0
	 */
	public function maybe_disable_plugin() {
		if ( ! $this->meets_requirements() ) {
			echo '<div class="error"><p>';
			echo wp_kses_post(
				sprintf(
					/*
					* Translators: %1$s and %2$s are placeholders for opening and closing bold tags respectively.
					* Rishi Companion plugin requires Rishi Theme to be installed and activated to work properly. Please install and activate the theme to use the plugin.
					*/
					__( '%1$sRishi Companion plugin%2$s requires Rishi Theme to be installed and activated to work properly. Please install and activate the theme to use the plugin.', 'rishi-companion' ),
					'<b>',
					'</b>'
				)
			);
			echo '</p></div>';
		}
	}

	/**
	 * Includes necessary files.
	 */
	public function includes() {
		if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			require plugin_dir_path( __FILE__ ) . 'updater/EDD_SL_Plugin_Updater.php';
		}
		require plugin_dir_path( __FILE__ ) . 'updater/class-rishi-companion-updater.php';
		add_action( 'plugins_loaded', array( $this, 'plugins_compatibility' ) );
	}

	/**
	 * Check and load plugins compatibility.
	 */
	public function plugins_compatibility() {
		require_once plugin_dir_path( __FILE__ ) . 'compatibility/web-stories/WebStories.php';
	}

	/**
	 * Get the plugin URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', RISHI_COMPANION_PLUGIN_FILE ) );
	}

	/**
	 * Check if all plugin requirements are met.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if requirements are met, otherwise false.
	 */
	private function meets_requirements() {
		return function_exists('rishi_check_if_theme_is_activated') && \rishi_check_if_theme_is_activated() ? true : false;
	}
}
