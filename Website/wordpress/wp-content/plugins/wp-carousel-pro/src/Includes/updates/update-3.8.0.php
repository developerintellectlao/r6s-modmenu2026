<?php
/**
 * Update options for the version 3.8.0
 *
 * @link       https://shapedplugin.com
 * @since      3.7.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.8.0' );
update_option( 'wp_carousel_pro_db_version', '3.8.0' );

/**
 * WP Carousel query for id.
 */
$args         = new WP_Query(
	array(
		'post_type'      => 'sp_wp_carousel',
		'post_status'    => 'any',
		'posts_per_page' => '3000',
	)
);
$carousel_ids = wp_list_pluck( $args->posts, 'ID' );

/**
 * Update metabox data along with previous data.
 */
if ( count( $carousel_ids ) > 0 ) {
	foreach ( $carousel_ids as $carousel_key => $carousel_id ) {
		$shortcode_data = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
		$show_read_more = isset( $shortcode_data['wpcp_post_readmore_button_show'] ) ? $shortcode_data['wpcp_post_readmore_button_show'] : true;
		$section_title  = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : true;
		if ( $show_read_more || $section_title ) {
			$post_readmore_color = isset( $shortcode_data['wpcp_readmore_color_set'] ) ? $shortcode_data['wpcp_readmore_color_set'] : array();
			// Read more button get border old color.
			$post_readmore_color5 = isset( $post_readmore_color['color5'] ) ? $post_readmore_color['color5'] : '#22afba';
			$post_readmore_color6 = isset( $post_readmore_color['color6'] ) ? $post_readmore_color['color6'] : '#22afba';

			// Read more button border color update.
			$shortcode_data['wpcp_readmore_border'] = array(
				'all'         => '1',
				'style'       => 'solid',
				'color'       => $post_readmore_color5,
				'hover-color' => $post_readmore_color6,
				'radius'      => '0',
			);

			// Section title get old margin data.
			$section_title_margin        = isset( $shortcode_data['section_title_margin_'] ) ? $shortcode_data['section_title_margin_'] : ' ';
			$section_title_margin_top    = isset( $section_title_margin['top'] ) ? $section_title_margin['top'] : '0';
			$section_title_margin_bottom = isset( $section_title_margin['bottom'] ) ? $section_title_margin['bottom'] : '30';

			// Section title margin data update.
			$section_title_typography                  = $shortcode_data['wpcp_section_title_typography'];
			$section_title_typography['margin-top']    = $section_title_margin_top;
			$section_title_typography['margin-bottom'] = $section_title_margin_bottom;

			$shortcode_data['wpcp_section_title_typography'] = $section_title_typography;

			update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
		}
	} // End of foreach.
}

/**
 * Update new Setting options along with old options.
 */
$existing_options = get_option( 'sp_wpcp_settings', true );
$new_options      = array(
	'wpcp_enqueue_swiper_css' => true,
	'wpcp_swiper_js'          => true,
);
$all_options      = array_merge( $existing_options, $new_options );
$plugin_options   = update_option( 'sp_wpcp_settings', $all_options );
