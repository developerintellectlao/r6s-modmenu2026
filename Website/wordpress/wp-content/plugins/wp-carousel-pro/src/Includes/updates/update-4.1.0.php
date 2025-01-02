<?php
/**
 * Update options for the version 4.1.0
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '4.1.0' );
update_option( 'wp_carousel_pro_db_version', '4.1.0' );

/**
 * WP Carousel query for id.
 */
$carousel_ids = get_posts(
	array(
		'post_type'      => 'sp_wp_carousel',
		'post_status'    => 'any',
		'posts_per_page' => '3000',
		'fields'         => 'ids',
	)
);

/**
* Update metabox data along with previous data.
*/
if ( count( $carousel_ids ) > 0 ) {
	foreach ( $carousel_ids as $carousel_key => $carousel_id ) {
		$shortcode_data = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}
		$wpcp_layout        = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : '';
		$wpcp_carousel_mode = isset( $shortcode_data['wpcp_carousel_mode'] ) ? $shortcode_data['wpcp_carousel_mode'] : '';
		if ( 'carousel' === $wpcp_layout ) {
			$wpcp_carousel_mode = isset( $shortcode_data['wpcp_carousel_row'] ) ? $shortcode_data['wpcp_carousel_row'] : array(
				'lg_desktop' => '1',
				'desktop'    => '1',
				'laptop'     => '1',
				'tablet'     => '1',
				'mobile'     => '1',
			);
			// Set a default value of false for $multi_row.
			$multi_row = false;
			foreach ( $wpcp_carousel_mode as $value ) {
				if ( $value > 1 ) {
					$multi_row = true;
					break;
				}
			}
			if ( $multi_row ) {
				$shortcode_data['wpcp_carousel_mode'] = 'multi-row';
			}
		}
		if ( in_array( $wpcp_layout, array( 'tiles', 'grid', 'masonry' ), true ) ) {
			$wpcp_content_style = isset( $shortcode_data['wpcp_content_style'] ) ? $shortcode_data['wpcp_content_style'] : '';
			if ( 'caption_partial' === $wpcp_content_style ) {
				$wpcp_caption_partial = isset( $shortcode_data['wpcp_caption_partial'] ) ? $shortcode_data['wpcp_caption_partial'] : '';
				if ( 'moving' === $wpcp_caption_partial ) {
					$shortcode_data['wpcp_content_style'] = 'moving';
				}
			}
		}
		$wpcp_show_box_shadow                   = isset( $shortcode_data['wpcp_show_box_shadow'] ) ? $shortcode_data['wpcp_show_box_shadow'] : false;
		$shortcode_data['wpcp_show_box_shadow'] = 'none';
		if ( $wpcp_show_box_shadow ) {
			$wpcp_box_shadow_style                  = isset( $shortcode_data['wpcp_box_shadow']['style'] ) ? $shortcode_data['wpcp_box_shadow']['style'] : 'outset';
			$shortcode_data['wpcp_show_box_shadow'] = $wpcp_box_shadow_style;
		}
		$l_box_autoplay_speed                   = isset( $shortcode_data['l_box_autoplay_speed']['all'] ) ? $shortcode_data['l_box_autoplay_speed']['all'] : '4000';
		$shortcode_data['l_box_autoplay_speed'] = $l_box_autoplay_speed;
		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
	}
}
