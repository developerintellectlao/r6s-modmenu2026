<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/Frontend
 */

namespace ShapedPlugin\WPCarouselPro\Frontend;

/**
 * Frontend
 */
class Frontend {

	/**
	 * Script and style suffix
	 *
	 * @since 3.0.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * The ID of the plugin.
	 *
	 * @since 3.0.0
	 * @access protected
	 * @var string      $plugin_name The ID of this plugin
	 */
	protected $plugin_name;

	/**
	 * The version of the plugin
	 *
	 * @since 3.0.0
	 * @access protected
	 * @var string      $version The current version fo the plugin.
	 */
	protected $version;

	/**
	 * Initialize the class sets its properties.
	 *
	 * @since 3.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->suffix      = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		if ( ! defined( 'WPCP_TRANSIENT_EXPIRATION' ) ) {
			if ( is_admin() ) {
				define( 'WPCP_TRANSIENT_EXPIRATION', 0 );
			} else {
				define( 'WPCP_TRANSIENT_EXPIRATION', apply_filters( 'sp_wpcp_pro_transient_expiration', DAY_IN_SECONDS ) );
			}
		}

		// Ajax modal.
		add_action( 'wp_ajax_wpcp_password_cookie', array( $this, 'wpcp_setpassword_cookie' ) );
		add_action( 'wp_ajax_nopriv_wpcp_password_cookie', array( $this, 'wpcp_setpassword_cookie' ) );

		add_action( 'wp_ajax_wpcp_ajax_image_load', array( $this, 'wpcp_ajax_image_load' ) );
		add_action( 'wp_ajax_nopriv_wpcp_ajax_image_load', array( $this, 'wpcp_ajax_image_load' ) );
	}

	/**
	 * Set password cookie
	 *
	 * @return mixed
	 */
	public function wpcp_setpassword_cookie() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : null;

		if ( ! wp_verify_nonce( $nonce, 'wpcp_password_nonce' ) ) {
			return false;
		}
		$shortcode_id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';
		$pword        = isset( $_POST['pword'] ) ? wp_unslash( $_POST['pword'] ) : '';
		setcookie( 'wpcp-postpass_cookie' . $shortcode_id, $pword, time() + ( 86400 * 30 ), '/' );
		wp_die();
	}

	/**
	 * Ajax custom ajax load function.
	 * Retrieve the 'id' parameter from the $_POST array, sanitize it, and assign it to the $post_id variable.
	 * Retrieve the 'wpc_page' parameter from the $_POST array, sanitize it, and assign it to the $wpcppage variable.
	 * Retrieve the upload options associated with the $post_id and assign them to the $upload_data variable.
	 * Retrieve the shortcode options associated with the $post_id and assign them to the $shortcode_data variable.
	 * Call the wpcp_ajax_more_data() method of the Helper class, passing the necessary parameters.
	 *
	 * @return void
	 */
	public function wpcp_ajax_image_load() {
		// No need nonce for read only data.
		$post_id        = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '';
		$wpcppage       = isset( $_POST['wpc_page'] ) ? sanitize_text_field( wp_unslash( $_POST['wpc_page'] ) ) : '';
		$upload_data    = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
		$shortcode_data = get_post_meta( $post_id, 'sp_wpcp_shortcode_options', true );
		Helper::wpcp_ajax_more_data( $post_id, $wpcppage, $upload_data, $shortcode_data );
		wp_die();
	}

	/**
	 * Register the stylesheets for the public-facing side of the plugin.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_wpcp_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_wpcp_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		if ( $found_generator_id ) {
			// Style load in header.
			if ( wpcp_get_option( 'wpcp_enqueue_swiper_css', true ) ) {
				wp_enqueue_style( 'wpcp-swiper' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_bx_css', true ) ) {
				wp_enqueue_style( 'wpcp-bx-slider-css' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_fa_css', true ) ) {
				wp_enqueue_style( 'wp-carousel-pro-fontawesome' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_fancybox_css', true ) ) {
				wp_enqueue_style( 'wpcp-fancybox-popup' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_animation_css', true ) ) {
				wp_enqueue_style( 'wpcp-animate' );
			}
			wp_enqueue_style( 'wp-carousel-pro' );
			wp_enqueue_style( 'wpcp-navigation-and-tabbed-icons' );

			// Load Google font.
			$enqueue_fonts     = array();
			$wpcpro_typography = array();
			$dynamic_css       = '';
			foreach ( $found_generator_id as $carousel_id ) {
				if ( $carousel_id && is_numeric( $carousel_id ) && get_post_status( $carousel_id ) !== 'trash' ) {
					$upload_data = get_post_meta( $carousel_id, 'sp_wpcp_upload_options', true );
					$css_data    = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
					include WPCAROUSEL_PATH . '/Frontend/css/dynamic/dynamic-style.php';
				}
			}
			if ( wpcp_get_option( 'wpcp_dequeue_google_font', true ) ) {
				if ( ! empty( $wpcpro_typography ) ) {
					foreach ( $wpcpro_typography as $font ) {
						if ( isset( $font['type'] ) && 'google' === $font['type'] ) {
							$variant         = isset( $font['font-weight'] ) ? ':' . $font['font-weight'] : '';
							$subset          = isset( $font['subset'] ) ? ':' . $font['subset'] : '';
							$font_family     = isset( $font['font-family'] ) ? $font['font-family'] : '';
							$enqueue_fonts[] = $font_family . $variant . $subset;
						}
					}
				}
				if ( ! empty( $enqueue_fonts ) ) {
					$enqueue_fonts = array_unique( $enqueue_fonts );
					wp_enqueue_style( 'sp-wpcp-google-fonts', esc_url( add_query_arg( 'family', rawurlencode( implode( '|', $enqueue_fonts ) ), '//fonts.googleapis.com/css' ) ), array(), WPCAROUSEL_VERSION );
				}
			} // Google font enqueue dequeue.
			include WPCAROUSEL_PATH . '/Frontend/css/dynamic/responsive.php';
			// Custom css.
			$custom_css = trim( html_entity_decode( wpcp_get_option( 'wpcp_custom_css' ) ) );
			if ( ! empty( $custom_css ) ) {
				$dynamic_css .= $custom_css;
			}
			$dynamic_css = Helper::minify_output( $dynamic_css );
			wp_add_inline_style( 'wp-carousel-pro', $dynamic_css );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the plugin.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		$ajax_theme = wpcp_get_option( 'wpcp_carousel_in_ajax_theme', false );
		$custom_js  = wpcp_get_option( 'wpcp_custom_js' );
		if ( $ajax_theme ) {
			wp_enqueue_script( 'wpcp-ajax-theme' );
		}
		if ( ! empty( $custom_js ) || $ajax_theme ) {
			wp_enqueue_script( 'wpcp-carousel-config' );
			wp_add_inline_script( 'wpcp-carousel-config', $custom_js );
		}
	}

	/**
	 * Register the all scripts of the plugin.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts() {
		/**
		 * Register the stylesheets for the public and admin facing side of the plugin.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		wp_register_style( 'wpcp-swiper', WPCAROUSEL_URL . 'Frontend/css/swiper-bundle.min.css', array(), $this->version, 'all' );
		wp_register_style( 'wpcp-navigation-and-tabbed-icons', WPCAROUSEL_URL . 'Admin/css/fontello.css', array(), WPCAROUSEL_VERSION, 'all' );
		wp_register_style( 'wpcp-animate', WPCAROUSEL_URL . 'Frontend/css/animate.min.css', array(), $this->version, 'all' );
		wp_register_style( 'wpcp-bx-slider-css', WPCAROUSEL_URL . 'Frontend/css/jquery-bxslider.min.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name . '-fontawesome', WPCAROUSEL_URL . 'Frontend/css/font-awesome.min.css', array(), $this->version, 'all' );
		wp_register_style( 'wpcp-fancybox-popup', WPCAROUSEL_URL . 'Frontend/css/jquery.fancybox.min.css', array(), $this->version, 'all' );
		wp_register_style( $this->plugin_name, WPCAROUSEL_URL . 'Frontend/css/wp-carousel-pro-public' . $this->suffix . '.css', array(), $this->version, 'all' );

		/**
		 * Register the JavaScript for the public and admin facing side of the plugin.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		wp_register_script( 'wpcp-preloader', WPCAROUSEL_URL . 'Frontend/js/preloader' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-swiper', WPCAROUSEL_URL . 'Frontend/js/swiper-bundle.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-swiper-gl', WPCAROUSEL_URL . 'Frontend/js/swiper-gl.min.js', array( 'jquery', 'wpcp-swiper' ), $this->version, true );
		wp_register_script( 'wpcp-bx-slider', WPCAROUSEL_URL . 'Frontend/js/jquery-bxslider.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-bx-slider-config', WPCAROUSEL_URL . 'Frontend/js/bxslider-config' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-fancybox-popup', WPCAROUSEL_URL . 'Frontend/js/fancybox.min.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-fancybox-config', WPCAROUSEL_URL . 'Frontend/js/fancybox-config' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-image-custom-color', WPCAROUSEL_URL . 'Frontend/js/custom-img-color' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-jGallery', WPCAROUSEL_URL . 'Frontend/js/fjGallery' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-password', WPCAROUSEL_URL . 'Frontend/js/password-ajax' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-carousel-config', WPCAROUSEL_URL . 'Frontend/js/wp-carousel-pro-public' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wpcp-carousel-lazy-load', WPCAROUSEL_URL . 'Frontend/js/wpcp-lazyload' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
		$ajax_theme     = wpcp_get_option( 'wpcp_carousel_in_ajax_theme', false );
		$wpcp_swiper_js = wpcp_get_option( 'wpcp_swiper_js', true );
		if ( $ajax_theme ) {
			wp_register_script( 'wpcp-ajax-theme', WPCAROUSEL_URL . 'Frontend/js/ajaxtheme' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
			wp_localize_script(
				'wpcp-ajax-theme',
				'sp_wpcp_ajax',
				array(
					'script_path' => WPCAROUSEL_URL . 'Frontend/js',
				)
			);
		}
		wp_localize_script(
			'wpcp-carousel-config',
			'sp_wpcp_vars',
			array(
				'wpcp_swiper_js'           => $wpcp_swiper_js,
				'wpcp_slug_in_address_bar' => apply_filters( 'wpcp_lightbox_item_slug_in_address_bar', false ),
				'fancybox_tooltip_i18n'    => array(
					'zoom'            => __( 'Zoom', 'wp-carousel-pro' ),
					'full_screen'     => __( 'Full Screen', 'wp-carousel-pro' ),
					'start_slideshow' => __( 'Start Slideshow', 'wp-carousel-pro' ),
					'share'           => __( 'Share', 'wp-carousel-pro' ),
					'download'        => __( 'Download', 'wp-carousel-pro' ),
					'thumbnails'      => __( 'Thumbnails', 'wp-carousel-pro' ),
					'close'           => __( 'Close', 'wp-carousel-pro' ),
				),
			)
		);
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_wpcp_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp_wpcp_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} elseif ( get_option( 'sp_wpcp_page_id' . $post_ID ) ) {
				delete_option( 'sp_wpcp_page_id' . $post_ID );
		}
	}
}
