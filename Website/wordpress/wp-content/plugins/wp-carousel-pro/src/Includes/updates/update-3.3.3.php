<?php
/**
 * Update options for the version 3.3.3
 *
 * @link       https://shapedplugin.com
 * @since      3..3.3
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.3.3' );
update_option( 'wp_carousel_pro_db_version', '3.3.3' );

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
		$carousel_data = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );

		if ( ! $carousel_data['wpcp_lb_control'] ) {
			$carousel_data['l_box_zoom_button']        = false;
			$carousel_data['wpcp_thumbnails_gallery']  = false;
			$carousel_data['l_box_slideshow_button']   = false;
			$carousel_data['l_box_social_button']      = false;
			$carousel_data['l_box_full_screen_button'] = false;
			$carousel_data['l_box_download_button']    = false;
			$carousel_data['l_box_close_button']       = false;
		}
		$carousel_data['l_box_icon_style']        = 'none';
		$carousel_data['l_box_open_close_effect'] = 'fade';
		$carousel_data['l_box_loop']              = true;
		$carousel_data['l_box_desc']              = false;
		if ( ! $carousel_data['wpcp_thumbnails_gallery'] ) {
			$carousel_data['l_box_thumb_visibility'] = false;
		}

		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $carousel_data );
	} // End of foreach.
}
