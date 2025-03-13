<?php
/**
 * Elementor shortcode block.
 *
 * @since      3.6.2
 * @package   WordPress_Carousel_Pro
 * @subpackage WordPress_Carousel_Pro/admin
 */

namespace ShapedPlugin\WPCarouselPro\Admin;

use ShapedPlugin\WPCarouselPro\Frontend\Helper;
use ShapedPlugin\WPCarouselPro\Admin\views\ElementAddon\Elementor_Addon_Widget;

/**
 * Elementor Addon.
 *
 * @since      3.6.2
 * @package   WordPress_Carousel_Pro
 * @subpackage WordPress_Carousel_Pro/admin
 */
class Elementor_Addon {
	/**
	 * Script and style suffix
	 *
	 * @access protected
	 * @var string
	 */
	protected $suffix;
	/**
	 * Instance
	 *
	 * @since 3.6.2
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Addon The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 3.6.2
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 3.6.2
	 *
	 * @access public
	 */
	public function __construct() {
		$this->suffix = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'sp_wp_carousel_pro_block_enqueue_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'sp_wp_carousel_pro_block_enqueue_style' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sp_wp_carousel_pro_element_block_icon' ) );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    3.6.2
	 * @return void
	 */
	public function sp_wp_carousel_pro_element_block_icon() {
		wp_enqueue_style( 'sp_wp_carousel_element_block_icon', WPCAROUSEL_URL . 'Admin/css/fontello.css', array(), WPCAROUSEL_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    3.6.2
	 */
	public function sp_wp_carousel_pro_block_enqueue_style() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in carousel_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The carousel_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'wpcp-swiper' );
		wp_enqueue_style( 'wpcp-navigation-and-tabbed-icons' );
		wp_enqueue_style( 'wpcp-bx-slider-css' );
		wp_enqueue_style( 'wp-carousel-pro-fontawesome' );
		wp_enqueue_style( 'wpcp-fancybox-popup' );
		wp_enqueue_style( 'wpcp-animate' );
		wp_enqueue_style( 'wp-carousel-pro' );
		$wpcp_posts   = new \WP_Query(
			array(
				'post_type'      => 'sp_wp_carousel',
				'posts_per_page' => 500,
				'fields'         => 'ids',
			)
		);
		$carousel_ids = $wpcp_posts->posts;
		$dynamic_css  = '';
		foreach ( $carousel_ids as $carousel_id ) {
				$upload_data = get_post_meta( $carousel_id, 'sp_wpcp_upload_options', true );
				$css_data    = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
				include WPCAROUSEL_PATH . '/Frontend/css/dynamic/dynamic-style.php';
		}
		include WPCAROUSEL_PATH . '/Frontend/css/dynamic/responsive.php';
		// Custom css.
		$custom_css = trim( html_entity_decode( wpcp_get_option( 'wpcp_custom_css' ) ) );
		if ( ! empty( $custom_css ) ) {
			$dynamic_css .= $custom_css;
		}
		$dynamic_css = Helper::minify_output( $dynamic_css );
		wp_add_inline_style( 'wp-carousel-pro', $dynamic_css );
	}

	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    3.6.2
	 */
	public function sp_wp_carousel_pro_block_enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in carousel_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The carousel_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'wpcp-swiper' );
		wp_enqueue_script( 'wpcp-swiper-gl' );
		wp_enqueue_script( 'wpcp-bx-slider' );
		wp_enqueue_script( 'wpcp-fancybox-popup' );
		wp_enqueue_script( 'wpcp-jGallery' );
		wp_enqueue_script( 'wpcp-password' );
		wp_enqueue_script( 'wpcp-preloader' );
		wp_enqueue_script( 'wpcp-carousel-lazy-load' );
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'wpcp-carousel-config' );
		wp_localize_script(
			'wpcp-carousel-config',
			'sp_wpcp_vars',
			array(
				'wpcp_swiper_js' => true,
			)
		);
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 3.6.2
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {
		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 3.6.2
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 3.6.2
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register( new Elementor_Addon_Widget() );
	}

}
