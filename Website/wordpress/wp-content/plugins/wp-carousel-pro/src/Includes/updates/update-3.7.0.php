<?php
/**
 * Update options for the version 3.7.0
 *
 * @link       https://shapedplugin.com
 * @since      3.5.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.7.0' );
update_option( 'wp_carousel_pro_db_version', '3.7.0' );

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
		$upload_data        = get_post_meta( $carousel_id, 'sp_wpcp_upload_options', true );
		$wpcp_carousel_type = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
		if ( 'video-carousel' === $wpcp_carousel_type ) {
			$video_sources     = isset( $upload_data['carousel_video_source'] ) ? $upload_data['carousel_video_source'] : '';
			$new_video_sources = array();
			foreach ( $video_sources as $key => $value ) {
				$video_source_type = isset( $value['carousel_video_source_type'] ) ? $value['carousel_video_source_type'] : '';
				if ( 'image_only' === $video_source_type ) {
					$video_source_type = 'self_hosted';
				}
				$value['carousel_video_source_type'] = $video_source_type;
				$new_video_sources[]                 = $value;
			}
			$upload_data['carousel_video_source'] = $new_video_sources;
			update_post_meta( $carousel_id, 'sp_wpcp_upload_options', $upload_data );
		}
	} // End of foreach.
}
