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

update_option( 'wp_carousel_pro_version', '3.3.2' );
update_option( 'wp_carousel_pro_db_version', '3.3.2' );

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

		$carousel_data['wpcp_nav_arrow_color'] = array(
			'color1' => isset( $carousel_data['wpcp_nav_colors']['color1'] ) ? $carousel_data['wpcp_nav_colors']['color1'] : '#aaa',
			'color2' => isset( $carousel_data['wpcp_nav_colors']['color2'] ) ? $carousel_data['wpcp_nav_colors']['color2'] : '#fff',
		);
		$carousel_data['wpcp_nav_bg']          = array(
			'color1' => isset( $carousel_data['wpcp_nav_colors']['color3'] ) ? $carousel_data['wpcp_nav_colors']['color3'] : 'transparent',
			'color2' => isset( $carousel_data['wpcp_nav_colors']['color4'] ) ? $carousel_data['wpcp_nav_colors']['color4'] : '#178087',
		);
		$carousel_data['wpcp_nav_border']      = array(
			'color'       => isset( $carousel_data['wpcp_nav_colors']['color5'] ) ? $carousel_data['wpcp_nav_colors']['color5'] : '#aaa',
			'hover-color' => isset( $carousel_data['wpcp_nav_colors']['color6'] ) ? $carousel_data['wpcp_nav_colors']['color6'] : '#178087',
			'radius'      => isset( $carousel_data['navigation_icons_border_radius']['all'] ) ? $carousel_data['navigation_icons_border_radius']['all'] : '0',
		);

		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $carousel_data );
	} // End of foreach.
}
