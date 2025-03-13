<?php
/**
 * Update options for the version 3.10.1
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.10.1' );
update_option( 'wp_carousel_pro_db_version', '3.10.1' );

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
		$shortcode_data                   = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
		$old_product_desc                 = isset( $shortcode_data['wpcp_product_desc'] ) ? $shortcode_data['wpcp_product_desc'] : true;
		$old_limit_product_content        = isset( $shortcode_data['wpcp_limit_product_desc'] ) ? $shortcode_data['wpcp_limit_product_desc'] : true;
		$old_product_content_limit_number = isset( $shortcode_data['wpcp_product_desc_limit_number'] ) ? $shortcode_data['wpcp_product_desc_limit_number'] : '';

		$shortcode_data['wpcp_product_desc']              = $old_product_desc ? 'full' : 'hide';
		$shortcode_data['wpcp_product_desc_limit_number'] = $old_product_desc && $old_limit_product_content ? $old_product_content_limit_number : '';

		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
	} // End of foreach.
}
