<?php

/**
 * The file that defines the shortcode plugin class.
 *
 * A class definition that define main carousel shortcode of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes
 */

namespace ShapedPlugin\WPCarouselPro\Includes;

use ShapedPlugin\WPCarouselPro\Frontend\Helper;

/**
 * The Shortcode class.
 *
 * This is used to define shortcode, shortcode attributes, and carousel types.
 */
class Register_Shortcode {

	/**
	 * Holds the class object.
	 *
	 * @since 3.0.0
	 * @var object
	 */
	public static $instance;

	/**
	 * Contain the version class object.
	 *
	 * @since 3.0.0
	 * @var object
	 */
	public $version;

	/**
	 * Holds the carousel data.
	 *
	 * @since 3.0.0
	 * @var array
	 */
	public $data;

	/**
	 * YouTube video support.
	 *
	 * @since 3.0.0
	 * @var boolean
	 */
	public $youtube = false;

	/**
	 * Vimeo video support.
	 *
	 * @since 3.0.0
	 * @var boolean
	 */
	public $vimeo = false;

	/**
	 * The post ID.
	 *
	 * @var string $post_id The post id of the carousel shortcode.
	 */
	public $post_id;


	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 3.0.0
	 * @static
	 * @return Register_Shortcode Shortcode instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * A shortcode for rendering the carousel.
	 *
	 * @param integer $attributes The ID the shortcode.
	 * @return statement
	 */
	public function sp_wp_carousel_shortcode( $attributes ) {
		$manage_license     = new License( WPCAROUSEL_PRO_FILE, WPCAROUSEL_VERSION, 'ShapedPlugin', WPCAROUSEL_STORE_URL, WPCAROUSEL_ITEM_ID, WPCAROUSEL_ITEM_SLUG );
		$license_key_status = $manage_license->get_license_status();
		$license_status     = ( is_object( $license_key_status ) && $license_key_status ? $license_key_status->license : '' );
		$if_license_status  = array( 'valid', 'expired' );
		$first_version      = get_option( 'wp_carousel_pro_first_version' );
		if ( ! in_array( $license_status, $if_license_status ) && 1 === version_compare( $first_version, '3.2.3' ) ) {
			$activation_notice = '';
			if ( current_user_can( 'manage_options' ) ) {
				$activation_notice = sprintf(
					'<div class="wp-carousel-pro-license-notice" style="background: #ffebee;color: #444;padding: 18px 16px;border: 1px solid #d0919f;border-radius: 4px;font-size: 18px;line-height: 28px;">Please <strong><a href="%1$s">activate</a></strong> the license key to get the output of the <strong>WP Carousel Pro</strong> plugin.</div>',
					esc_url( admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=1' ) )
				);
			}
			return $activation_notice;
		}

		$current_status = get_post_status( $attributes['id'] );
		if ( 'trash' === $current_status || empty( $attributes['id'] || 'sp_wp_carousel' !== get_post_type( $attributes['id'] ) ) ) {
			return;
		}

		$post_id = esc_attr( (int) $attributes['id'] );

		$upload_data        = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
		$shortcode_data     = get_post_meta( $post_id, 'sp_wpcp_shortcode_options', true );
		$main_section_title = get_the_title( $post_id );

		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		$current_page_id    = get_queried_object_id();
		$option_key         = 'sp_wpcp_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'sp_wpcp_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
		ob_start();
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $post_id, $found_generator_id ) || wpcp_get_option( 'wpcp_carousel_in_ajax_theme', false ) ) {
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
			wp_enqueue_style( 'wpcp-navigation-and-tabbed-icons' );
			wp_enqueue_style( 'wp-carousel-pro' );
			$carousel_id       = $post_id;
			$css_data          = $shortcode_data;
			$enqueue_fonts     = array();
			$wpcpro_typography = array();
			$dynamic_css       = '';
			include WPCAROUSEL_PATH . '/Frontend/css/dynamic/dynamic-style.php';
			include WPCAROUSEL_PATH . '/Frontend/css/dynamic/responsive.php';
			// Custom css.
			$custom_css = trim( html_entity_decode( wpcp_get_option( 'wpcp_custom_css' ) ) );
			if ( ! empty( $custom_css ) ) {
				$dynamic_css .= $custom_css;
			}
			$dynamic_css = Helper::minify_output( $dynamic_css );
			echo '<style id="sp_wpcp_dynamic_css' . esc_attr( $post_id ) . '">' . wp_strip_all_tags( $dynamic_css ) . '</style>';
			// Load Google font.
			if ( wpcp_get_option( 'wpcp_dequeue_google_font', true ) ) {
				if ( ! empty( $wpcpro_typography ) ) {
					foreach ( $wpcpro_typography as $font ) {
						if ( isset( $font['type'] ) && 'google' === $font['type'] ) {
							$variant         = ( isset( $font['font-weight'] ) ) ? ':' . $font['font-weight'] : '';
							$subset          = isset( $font['subset'] ) ? ':' . $font['subset'] : '';
							$font_family     = isset( $font['font-family'] ) ? $font['font-family'] : '';
							$enqueue_fonts[] = $font_family . $variant . $subset;
						}
					}
				}
				if ( ! empty( $enqueue_fonts ) ) {
					$enqueue_fonts = array_unique( $enqueue_fonts );
					wp_enqueue_style( 'sp-wpcp-google-fonts' . $post_id, esc_url( add_query_arg( 'family', rawurlencode( implode( '|', $enqueue_fonts ) ), '//fonts.googleapis.com/css' ) ), array(), WPCAROUSEL_VERSION );
				}
			} // Google font enqueue dequeue.
		}
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) { // If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} elseif ( $current_page_id ) { // If option not set in current page add option.
			if ( is_multisite() ) {
				add_site_option( $option_key, array( $post_id ) );
			} else {
				add_option( $option_key, array( $post_id ) );
			}
		}
		// Show the full html of wp carousel.
		echo Helper::sp_wpcp_html_show( $upload_data, $shortcode_data, $post_id, $main_section_title ); // phpcs:ignore
		return ob_get_clean();
	}
}
