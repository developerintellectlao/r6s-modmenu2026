<?php
/**
 * Update options for the version 3.5.0
 *
 * @link       https://shapedplugin.com
 * @since      3.5.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.5.0' );
update_option( 'wp_carousel_pro_db_version', '3.5.0' );

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

		$upload_data     = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
		$carousels_types = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
		if ( 'product-carousel' === $carousel_type ) {
			$upload_data['wpcp_taxonomy_product_terms'] = $upload_data['wpcp_taxonomy_terms'];
			update_post_meta( $carousel_id, 'sp_wpcp_upload_options', $upload_data );
		}
	} // End of foreach.
}
