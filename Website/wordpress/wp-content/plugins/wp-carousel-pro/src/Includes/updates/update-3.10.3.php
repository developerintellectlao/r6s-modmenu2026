<?php
/**
 * Update options for the version 3.10.3
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.10.3' );
update_option( 'wp_carousel_pro_db_version', '3.10.3' );

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
		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}
		$upload_data       = get_post_meta( $carousel_id, 'sp_wpcp_upload_options', true );
		$old_carousel_type = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';

		if ( isset( $shortcode_data['wpcp_product_image_border'] ) && ( 'post-carousel' === $old_carousel_type || 'image-carousel' === $old_carousel_type ) ) {
			$carousel_border                                    = isset( $shortcode_data['wpcp_product_image_border']['all'] ) ? $shortcode_data['wpcp_product_image_border']['all'] : '0';
			$shortcode_data['wpcp_product_image_border']['all'] = ( 1 === (int) $carousel_border ) ? '0' : $carousel_border;
		}
		$wpcp_layout = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
		if ( 'carousel' === $wpcp_layout ) {
			$shortcode_data['wpcp_image_vertical_alignment'] = 'center';
		} else {
			$shortcode_data['wpcp_image_vertical_alignment'] = 'flex-start';
		}
		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
	}
}

