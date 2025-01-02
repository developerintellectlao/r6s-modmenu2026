<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPCarouselPro\Admin\views\GutenbergBlock;

use ShapedPlugin\WPCarouselPro\Frontend\Helper;
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutenberg_Block_Init' ) ) {
	/**
	 * Gutenberg_Block_Init class.
	 */
	class Gutenberg_Block_Init {
		/**
		 * Script and style suffix
		 *
		 * @since 3.0.0
		 * @access protected
		 * @var string
		 */
		protected $suffix;
		/**
		 * Custom Gutenberg Block Initializer.
		 */
		public function __construct() {
			$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
			add_action( 'init', array( $this, 'sp_wp_carousel_pro_gutenberg_shortcode_block' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'sp_wp_carousel_pro_block_editor_assets' ) );
		}

		/**
		 * Register block editor script for backend.
		 */
		public function sp_wp_carousel_pro_block_editor_assets() {
			wp_enqueue_script(
				'sp-wp-carousel-pro-shortcode-block',
				plugins_url( '/GutenbergBlock/build/index.js', __DIR__ ),
				array( 'jquery' ),
				WPCAROUSEL_VERSION,
				true
			);

			/**
			 * Register block editor css file enqueue for backend.
			 */
			if ( wpcp_get_option( 'wpcp_enqueue_swiper_css', true ) ) {
				wp_enqueue_style( 'wpcp-swiper' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_bx_css', true ) ) {
				wp_enqueue_style( 'wpcp-bx-slider-css' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_fa_css', true ) ) {
				wp_enqueue_style( 'wp-carousel-pro-fontawesome' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_animation_css', true ) ) {
				wp_enqueue_style( 'wpcp-animate' );
			}
			if ( wpcp_get_option( 'wpcp_enqueue_fancybox_css', true ) ) {
				wp_enqueue_style( 'wpcp-fancybox-popup' );
			}
			wp_enqueue_style( 'wpcp-navigation-and-tabbed-icons' );
			wp_enqueue_style( 'wp-carousel-pro' );
		}
		/**
		 * Shortcode list.
		 *
		 * @return array
		 */
		public function sp_wp_carousel_pro_post_list() {
			if ( ! is_admin() ) {
				return array();
			}
			$shortcodes = get_posts(
				array(
					'post_type'      => 'sp_wp_carousel',
					'post_status'    => 'publish',
					'posts_per_page' => 9999,
				)
			);

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			return array_map(
				function ( $shortcode ) {
						return (object) array(
							'id'    => absint( $shortcode->ID ),
							'title' => esc_html( $shortcode->post_title ),
						);
				},
				$shortcodes
			);
		}

		/**
		 * Register Gutenberg shortcode block.
		 */
		public function sp_wp_carousel_pro_gutenberg_shortcode_block() {
			/**
			 * Register block editor js file enqueue for backend.
			 */
			wp_register_script( 'wpcp-carousel-gb-config', WPCAROUSEL_URL . 'Frontend/js/wp-carousel-pro-public' . $this->suffix . '.js', array( 'jquery' ), WPCAROUSEL_VERSION, true );

			wp_localize_script(
				'wpcp-carousel-gb-config',
				'sp_wp_carousel_pro',
				array(
					'url'           => WPCAROUSEL_URL,
					'lazyLoad'      => WPCAROUSEL_URL . 'Frontend/js/wpcp-lazyload.js',
					'loadBxSlider'  => WPCAROUSEL_URL . 'Frontend/js/bxslider-config.js',
					'loadScript'    => WPCAROUSEL_URL . 'Frontend/js/wp-carousel-pro-public.js',
					'link'          => admin_url( 'post-new.php?post_type=sp_wp_carousel' ),
					'shortcodeList' => $this->sp_wp_carousel_pro_post_list(),
				)
			);
			wp_localize_script(
				'wpcp-carousel-gb-config',
				'sp_wpcp_vars',
				array(
					'wpcp_swiper_js' => true,
				)
			);
			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'sp-wp-carousel-pro/shortcode',
				array(
					'attributes'      => array(
						'shortcodelist'      => array(
							'type'    => 'object',
							'default' => '',
						),
						'shortcode'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'showInputShortcode' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					// Enqueue blocks.editor.build.js in the editor only.
					'editor_script'   => array(
						'wpcp-preloader',
						'wpcp-swiper',
						'wpcp-swiper-gl',
						'wpcp-bx-slider',
						'wpcp-bx-slider-config',
						'wpcp-fancybox-popup',
						'wpcp-fancybox-config',
						'wpcp-jGallery',
						'wpcp-password',
						'jquery-masonry',
						'wpcp-carousel-lazy-load',
						'wpcp-carousel-gb-config',
					),
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'    => array(),
					'render_callback' => array( $this, 'sp_wp_carousel_pro_render_shortcode' ),
				)
			);
		}

		/**
		 * Render callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function sp_wp_carousel_pro_render_shortcode( $attributes ) {

			$class_name = '';
			if ( ! empty( $attributes['className'] ) ) {
				$class_name = 'class="' . esc_attr( $attributes['className'] ) . '"';
			}

			if ( ! $attributes['is_admin'] ) {
				return '<div ' . $class_name . '>' . do_shortcode( '[sp_wpcarousel id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
			}

			$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );

			return '<div id="' . uniqid() . '" ' . $class_name . ' ><a href="' . esc_url( $edit_page_link ) . '" target="_blank" class="sp_wp_carousel_block_edit_button">Edit View</a>' . do_shortcode( '[sp_wpcarousel id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}
	}
}
