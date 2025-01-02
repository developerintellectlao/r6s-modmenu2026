<?php
/**
 * Update options for the version 3.2.0
 *
 * @link       https://shapedplugin.com
 * @since      3.2.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.2.0' );
update_option( 'wp_carousel_pro_db_version', '3.2.0' );

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
if ( count( $carousel_ids ) > 0 ) {
	foreach ( $carousel_ids as $carousel_key => $carousel_id ) {
		$carousel_data                = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
		$section_title_margin         = isset( $carousel_data['section_title_margin_bottom'] ) ? $carousel_data['section_title_margin_bottom'] : '';
		$section_title_margin_bottom  = isset( $section_title_margin['all'] ) ? $section_title_margin['all'] : '';
		$show_logo_link               = isset( $carousel_data['wpcp_logo_link_show'] ) ? $carousel_data['wpcp_logo_link_show'] : '';
		$wpcp_slide_inner_padding     = isset( $carousel_data['wpcp_slide_inner_padding'] ) ? $carousel_data['wpcp_slide_inner_padding'] : '';
		$wpcp_slide_inner_padding_all = isset( $wpcp_slide_inner_padding['all'] ) ? $wpcp_slide_inner_padding['all'] : '';

		if ( ! empty( $section_title_margin_bottom ) ) {
			$carousel_data['section_title_margin_']['bottom'] = $section_title_margin_bottom;
			unset( $carousel_data['section_title_margin_bottom'] );
		}

		if ( $show_logo_link ) {
			$carousel_data['wpcp_logo_link_show'] = 'link';
		} else {
			$carousel_data['wpcp_logo_link_show'] = 'none';
		}
		if ( ! empty( $wpcp_slide_inner_padding_all ) ) {
			$wpcp_slide_inner_padding['top']    = $wpcp_slide_inner_padding_all;
			$wpcp_slide_inner_padding['right']  = $wpcp_slide_inner_padding_all;
			$wpcp_slide_inner_padding['bottom'] = $wpcp_slide_inner_padding_all;
			$wpcp_slide_inner_padding['left']   = $wpcp_slide_inner_padding_all;
			unset( $wpcp_slide_inner_padding['all'] );
		}

		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $carousel_data );
	} // End of foreach.
}
