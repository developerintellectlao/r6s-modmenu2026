<?php
/**
 * Update options for the version 3.2.2
 *
 * @link       https://shapedplugin.com
 * @since      3.2.2
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '3.2.2' );
update_option( 'wp_carousel_pro_db_version', '3.2.2' );

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

		$carousel_data['wpcp_image_counter']       = true;
		$carousel_data['wpcp_l_box_image_caption'] = true;
		$carousel_data['wpcp_lb_caption_color']    = '#ffffff';
		$carousel_data['wpcp_thumbnails_gallery']  = true;
		$carousel_data['wpcp_lb_control']          = true;

		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $carousel_data );
	} // End of foreach.
}

/**
 * Update new Setting options along with old options.
 */
$existing_options = get_option( 'sp_wpcp_settings', true );
$fancybox_options = array(
	'wpcp_enqueue_fancybox_css' => true,
	'wpcp_fancybox_js'          => true,
);
$all_options      = array_merge( $existing_options, $fancybox_options );
$plugin_options   = update_option( 'sp_wpcp_settings', $all_options );
