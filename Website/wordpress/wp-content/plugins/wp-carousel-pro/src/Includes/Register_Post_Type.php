<?php
/**
 * The file that defines the carousel post type.
 *
 * A class the that defines the carousel post type and make the plugins' menu.
 *
 * @link http://shapedplugin.com
 * @since 3.0.0
 *
 * @package WordPress_Carousel_Pro
 * @subpackage WordPress_Carousel_Pro/includes
 */

namespace ShapedPlugin\WPCarouselPro\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom post class to register the carousel.
 */
class Register_Post_Type {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 3.0.0
	 */
	private static $instance;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 1.0.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * WordPress carousel post type
	 */
	public function wp_carousel_post_type() {

		if ( post_type_exists( 'sp_wp_carousel' ) ) {
			return;
		}
		$capability = apply_filters( 'sp_wp_carousel_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		// Set the WordPress carousel post type labels.
		$labels = apply_filters(
			'sp_wp_carousel_post_type_labels',
			array(
				'name'               => esc_html_x( 'All Carousels', 'wp-carousel-pro' ),
				'singular_name'      => esc_html_x( 'WP Carousel', 'wp-carousel-pro' ),
				'add_new'            => esc_html__( 'Add New', 'wp-carousel-pro' ),
				'add_new_item'       => esc_html__( 'Add New Carousel', 'wp-carousel-pro' ),
				'edit_item'          => esc_html__( 'Edit Carousel', 'wp-carousel-pro' ),
				'new_item'           => esc_html__( 'New Carousel', 'wp-carousel-pro' ),
				'view_item'          => esc_html__( 'View Carousel', 'wp-carousel-pro' ),
				'search_items'       => esc_html__( 'Search Carousels', 'wp-carousel-pro' ),
				'not_found'          => esc_html__( 'No Carousels found.', 'wp-carousel-pro' ),
				'not_found_in_trash' => esc_html__( 'No Carousels found in trash.', 'wp-carousel-pro' ),
				'parent_item_colon'  => esc_html__( 'Parent Item:', 'wp-carousel-pro' ),
				'menu_name'          => esc_html__( 'WP Carousel', 'wp-carousel-pro' ),
				'all_items'          => esc_html__( 'All Carousels', 'wp-carousel-pro' ),
			)
		);
		// Base 64 encoded SVG image.
		$menu_icon = 'data:image/svg+xml;base64,' . base64_encode(
			'<svg width="20" height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M0 0.416667V14.5833C0 14.8125 0.1875 15 0.416667 15H19.5833C19.8125 15 20 14.8125 20 14.5833V0.416667C20 0.1875 19.8125 0 19.5833 0H0.416667C0.1875 0 0 0.1875 0 0.416667ZM17.075 12.7042H2.925C2.69583 12.7042 2.50833 12.5167 2.50833 12.2875V2.7125C2.50833 2.48333 2.69583 2.29583 2.925 2.29583H17.075C17.3042 2.29583 17.4917 2.48333 17.4917 2.7125V12.2875C17.4917 12.5167 17.3042 12.7042 17.075 12.7042Z" fill="white"/>
<path d="M15.7333 7.62495L13.9625 9.83745C13.8917 9.92912 13.7583 9.94162 13.6708 9.87078L12.775 9.14995C12.6875 9.07912 12.6708 8.94578 12.7417 8.85828L13.725 7.63328C13.7875 7.55828 13.7875 7.44995 13.725 7.37078L12.7333 6.13745C12.6625 6.04578 12.675 5.91662 12.7667 5.84578L13.6625 5.12495C13.7542 5.05412 13.8833 5.06662 13.9542 5.15828L15.6042 7.21662L15.7292 7.36662C15.7958 7.43745 15.7958 7.54578 15.7333 7.62495Z" fill="white"/><path d="M6.27085 7.6333L7.25418 8.8583C7.32501 8.94997 7.31251 9.07913 7.22085 9.14997L6.32918 9.86663C6.23751 9.93746 6.10835 9.92497 6.03751 9.8333L4.26251 7.62497C4.20001 7.54997 4.20001 7.44163 4.26251 7.36247L4.29585 7.3208L6.05001 5.15413C6.12085 5.06247 6.25418 5.04997 6.34168 5.1208L7.23751 5.84163C7.32918 5.91247 7.34168 6.0458 7.27085 6.1333L6.27085 7.37497C6.21251 7.44997 6.20835 7.5583 6.27085 7.6333Z" fill="white"/>
</svg>'
		);
		// Set the WordPress carousel post type arguments.
		$args = apply_filters(
			'sp_wp_carousel_post_type_args',
			array(
				'labels'              => $labels,
				'public'              => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_ui'             => $show_ui,
				'show_in_admin_bar'   => false,
				'menu_position'       => apply_filters( 'sp_wp_carousel_menu_position', 120 ),
				'menu_icon'           => $menu_icon,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array(
					'title',
				),
			)
		);
		register_post_type( 'sp_wp_carousel', $args );
	}
}
