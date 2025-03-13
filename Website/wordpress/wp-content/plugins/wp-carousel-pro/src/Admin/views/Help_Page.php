<?php
/**
 * The help page for the WP Carousel Pro
 *
 * @package WP Carousel Pro
 * @subpackage wp-carousel-pro/admin
 */

namespace ShapedPlugin\WPCarouselPro\Admin\views;

use ShapedPlugin\WPCarouselPro\Includes\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the WP Carousel Pro.
 */
class Help_Page {

	/**
	 * Wp Carousel Pro single instance of the class
	 *
	 * @var null
	 * @since 2.0
	 */
	protected static $_instance = null;

	/**
	 * Main Help_Page Instance
	 *
	 * @since 2.0
	 * @static
	 * @see sp_wpcp_help()
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=sp_wp_carousel',
			__( 'WP Carousel Help', 'wp-carousel-pro' ),
			__( 'Help', 'wp-carousel-pro' ),
			'manage_options',
			'wpcp_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * The WP Carousel Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		echo '
        <div class="wrap about-wrap sp-wpcp-help">
        <h1>' . esc_html__( 'Welcome to WP Carousel Pro! ', 'wp-carousel-pro' ) . '</h1>
        </div>
        <div class="wrap about-wrap sp-wpcp-help">
			<p class="about-text">' . esc_html__(
			'Thank you for installing WP Carousel Pro! You\'re now running the most popular Carousel, Slider, and Gallery plugin.
This video will help you get started with the plugin.',
			'wp-carousel-pro'
		) . '</p>
			<div class="headline-feature-video">
			<div class="headline-feature feature-video">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLoUb-7uG-5jNgTTcnUflIiytxgTWaBEzm" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
			</div>

			<div class="feature-section three-col">
				<div class="col">
					<div class="sp-wpcp-feature text-center">
						<h3><i class="sp-wpcp-font-icon fa fa-file-text"></i>
						' . esc_html__( 'Documentation', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Check out our documentation page and more information about what you can do with WP Carousel Pro.', 'wp-carousel-pro' ) . '</p>
						<a href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/introduction/" target="_blank" class="button button-primary">' . esc_html__( 'Browse Docs', 'wp-carousel-pro' ) . '</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-wpcp-feature text-center">
						<h3><i class="sp-wpcp-font-icon fa fa-envelope"></i>
						' . esc_html__( 'Email Support', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( "Need one-to-one assistance? Get in touch with our top-notch support team! We'd love to help you immediately.", 'wp-carousel-pro' ) . '</p>
						<a href="https://shapedplugin.com/support/" target="_blank" class="button button-primary">' . esc_html__( 'Get Support', 'wp-carousel-pro' ) . '</a>
					</div>
				</div>
				<div class="col">
					<div class="sp-wpcp-feature text-center">
						<h3><i class="sp-wpcp-font-icon fa fa-file-video-o"></i>
						' . esc_html__( 'Video Tutorials', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Check our video tutorials which cover everything you need to know about WP Carousel Pro.', 'wp-carousel-pro' ) . '</p>
						<a href="https://www.youtube.com/watch?v=j8EJnYpZmAA&list=PLoUb-7uG-5jNgTTcnUflIiytxgTWaBEzm&ab_channel=ShapedPlugin" target="_blank" class="button button-primary">' . esc_html__( 'Watch Now', 'wp-carousel-pro' ) . '</a>
					</div>
				</div>
			</div>

			<div class="about-wrap plugin-section">
				<div class="sp-plugin-section-title text-center">
					<h2>' . esc_html__( 'Take your website beyond the typical with more WordPress plugins!', 'wp-carousel-pro' ) . '</h2>
					<h4>' . esc_html__( 'Some of our powerful premium plugins are ready to make your website awesome.', 'wp-carousel-pro' ) . '</h4>
				</div>
				<div class="feature-section first-cols three-col">
				<div class="col">
					<a href="https://wooproductslider.io"  target="_blank" alt="WooCommerce Product Slider Pro" class="wpcp-plugin-link">
					<div class="sp-wpcp-feature text-center">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/woo-product-slider.png" alt="WooCommerce Product Slider Pro" class="wpcp-help-img">
						<h3>' . esc_html__( 'Product Slider for WooCommerce', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.', 'wp-carousel-pro' ) . '</p>
					</div>
					</a>
				</div>
				<div class="col">
					<a href="https://logocarousel.com" alt="Logo Carousel" target="_blank" class="wpcp-plugin-link">
					<div class="sp-wpcp-feature text-center">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/logo-carousel.png" alt="Logo Carousel" class="wpcp-help-img">
						<h3>
						' . esc_html__( 'Logo Carousel', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.', 'wp-carousel-pro' ) . '</p>
					</div>
					</a>
				</div>
				<div class="col">
					<a href="https://realtestimonials.io" alt="Real Testimonials" target="_blank" class="wpcp-plugin-link">
					<div class="sp-wpcp-feature text-center">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/real-testimonials.png" alt="Real Testimonials" class="wpcp-help-img">
						<h3>
						' . esc_html__( 'Real Testimonials', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Simply collect, manage, and display Testimonials on your website and boost conversions.', 'wp-carousel-pro' ) . '</p>
					</div>
					</a>
				</div>
			</div>
			<div class="feature-section three-col">
				<div class="col">
					<a href="https://shapedplugin.com/plugin/woocommerce-gallery-slider-pro/" target="_blank" alt="Gallery Slider for WooCommerce" class="wpcp-plugin-link">
					<div class="sp-wpcp-feature text-center">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/gallery-slider-for-woocommerce.png" alt="Gallery Slider for WooCommerce" class="wpcp-help-img">
						<h3>
						' . esc_html__( 'Gallery Slider for WooCommerce', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.', 'wp-carousel-pro' ) . '</p>
					</div>
					</a>
				</div>
				<div class="col">
				    <a href="https://easyaccordion.io" alt="Easy Accordion" target="_blank" class="wpcp-plugin-link">
					<div class="sp-wpcp-feature text-center">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/easy-accordion.png" alt="Easy Accordion" class="wpcp-help-img">
						<h3>
						' . esc_html__( 'Easy Accordion', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Minimize customer support by offering comprehensive FAQs and increasing conversions.', 'wp-carousel-pro' ) . '</p>
					</div>
					</a>
				</div>
				<div class="col">
				<a href="https://smartpostshow.com/" alt="Smart Post Show" target="_blank" class="wpcp-plugin-link" >
					<div class="sp-wpcp-feature text-center">
						<img src="https://shapedplugin.com/wp-content/uploads/edd/2022/08/smart-post-show.png" alt="Smart Post Show" class="wpcp-help-img">
						<h3>
						' . esc_html__( 'Smart Post Show', 'wp-carousel-pro' ) . '</h3>
						<p>' . esc_html__( 'Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.', 'wp-carousel-pro' ) . '</p>

					</div>
					</a>
				</div>
			</div>
			</div>
		</div>';
	}

	/**
	 * Add plugin action menu
	 *
	 * @param array  $links The action link.
	 * @param string $file The file.
	 *
	 * @return array
	 */
	public function add_plugin_action_links( $links, $file ) {

		$manage_license     = new License( WPCAROUSEL_PRO_FILE, WPCAROUSEL_VERSION, 'ShapedPlugin', WPCAROUSEL_STORE_URL, WPCAROUSEL_ITEM_ID, WPCAROUSEL_ITEM_SLUG );
		$license_key_status = $manage_license->get_license_status();
		$license_status     = ( is_object( $license_key_status ) ? $license_key_status->license : '' );

		if ( WPCAROUSEL_BASENAME === $file ) {
			if ( 'valid' === $license_status ) {
				$new_links = array(
					sprintf( '<a href="%s">%s</a>', admin_url( 'post-new.php?post_type=sp_wp_carousel' ), __( 'Create Carousel', 'wp-carousel-pro' ) ),
				);
			} else {
				$new_links = array(
					sprintf( '<a style="color: red; font-weight: 600;" href="%s">%s</a>', admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=1' ), __( 'Activate license', 'wp-carousel-pro' ) ),
				);
			}
			return array_merge( $new_links, $links );
		}
		return $links;
	}
}
