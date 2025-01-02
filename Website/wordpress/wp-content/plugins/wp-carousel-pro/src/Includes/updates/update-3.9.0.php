<?php
/**
 * Update options for the version 3.9.0
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.9.0' );
update_option( 'wp_carousel_pro_db_version', '3.9.0' );

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
		$wpcp_layout    = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : '';
		if ( isset( $shortcode_data['wpcp_slide_margin']['all'] ) ) {
			$shortcode_data['wpcp_slide_margin']['top']   = $shortcode_data['wpcp_slide_margin']['all'];
			$shortcode_data['wpcp_slide_margin']['right'] = $shortcode_data['wpcp_slide_margin']['all'];
		}
		if ( 'gallery' === $wpcp_layout ) {
			$wpcp_grid_mode = isset( $shortcode_data['wpcp_grid_mode'] ) ? $shortcode_data['wpcp_grid_mode'] : '';
			if ( 'masonry' === $wpcp_grid_mode ) {
				$shortcode_data['wpcp_layout'] = 'masonry';
			} else {
				$shortcode_data['wpcp_layout'] = 'grid';
			}
		}
		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
	}// End of foreach.
}
