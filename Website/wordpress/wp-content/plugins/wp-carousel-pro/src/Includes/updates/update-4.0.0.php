<?php
/**
 * Update options for the version 4.0.0
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes/updates
 */

update_option( 'wp_carousel_pro_version', '4.0.0' );
update_option( 'wp_carousel_pro_db_version', '4.0.0' );

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
		$shortcode_data       = get_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', true );
		$old_arrow_position   = isset( $shortcode_data['wpcp_carousel_nav_position'] ) ? $shortcode_data['wpcp_carousel_nav_position'] : 'vertical_center';
		$old_detail_position  = isset( $shortcode_data['wpcp_post_detail_position'] ) ? $shortcode_data['wpcp_post_detail_position'] : '';
		$old_overlay_position = isset( $shortcode_data['wpcp_overlay_position'] ) ? $shortcode_data['wpcp_overlay_position'] : '';

		// Check if '$arrow_position' is 'vertical_center' true, update 'wpcp_carousel_nav_position' to 'vertical_outer' .
		if ( 'vertical_center' === $old_arrow_position ) {
			$shortcode_data['wpcp_carousel_nav_position'] = 'vertical_outer';
		}

		// Check if '$arrow_position' is 'vertical_center_inner_hover' true, update 'wpcp_visible_on_hover' to 'true/1' And, set 'wpcp_carousel_nav_position' to 'vertical_center_inner'.
		if ( 'vertical_center_inner_hover' === $old_arrow_position ) {
			$shortcode_data['wpcp_visible_on_hover']      = '1';
			$shortcode_data['wpcp_carousel_nav_position'] = 'vertical_center_inner';
		}

		$old_image_link_show     = isset( $shortcode_data['wpcp_logo_link_show'] ) ? $shortcode_data['wpcp_logo_link_show'] : 'l_box';
		$old_image_link_nofollow = isset( $shortcode_data['wpcp_logo_link_nofollow'] ) ? $shortcode_data['wpcp_logo_link_nofollow'] : false;
		$old_link_target         = isset( $shortcode_data['wpcp_link_open_target'] ) ? $shortcode_data['wpcp_link_open_target'] : '_blank';

		// Update the 'wpcp_logo_link_show, wpcp_logo_link_nofollow, wpcp_link_open_target' in the 'wpcp_click_action_type_group' array.
		$shortcode_data['wpcp_click_action_type_group'] = array(
			'wpcp_logo_link_show'     => $old_image_link_show,
			'wpcp_logo_link_nofollow' => $old_image_link_nofollow,
			'wpcp_link_open_target'   => $old_link_target,
		);

		// Check if the old detail content position is not 'with_overlay', then set the content style to 'default'.
		if ( 'with_overlay' !== $old_detail_position ) {
			$shortcode_data['wpcp_content_style'] = 'default';

			if ( 'on_right' === $old_detail_position || 'on_left' === $old_detail_position ) {
				$shortcode_data['wpcp_content_inner_padding'] = array(
					'top'    => '10',
					'right'  => '15',
					'bottom' => '15',
					'left'   => '15',
				);
			}
		} else {
			// Mapping of old Overlay Content Positions to new shortcode data values.
			$mapping = array(
				'full_covered'     => array(
					'wpcp_content_style'    => 'with_overlay',
					'wpcp_overlay_position' => 'full_covered',
				),
				'lower'            => array(
					'wpcp_content_style' => 'caption_full',
					'wpcp_caption_full'  => 'bottom',
				),
				'middle'           => array(
					'wpcp_content_style' => 'caption_full',
					'wpcp_caption_full'  => 'center',
				),
				'top'              => array(
					'wpcp_content_style' => 'caption_full',
					'wpcp_caption_full'  => 'top',
				),
				'on_bottom_curved' => array(
					'wpcp_content_style' => 'content_diagonal',
					'wpcp_caption_full'  => 'bottom_left',
				),
				'on_bottom_corner' => array(
					'wpcp_content_style' => 'caption_partial',
					'wpcp_caption_full'  => 'bottom_left',
				),
			);
			// Check if the old overlay content position exists in the mapping.
			if ( array_key_exists( $old_overlay_position, $mapping ) ) {
				// If it does, merge the mapping values into the shortcode data.
				$shortcode_data = array_merge( $shortcode_data, $mapping[ $old_overlay_position ] );
			}

			$wpcp_overlay_position                        = isset( $shortcode_data['wpcp_overlay_position'] ) ? $shortcode_data['wpcp_overlay_position'] : '';
			$shortcode_data['wpcp_content_inner_padding'] = array(
				'top'    => '10',
				'right'  => '10',
				'bottom' => '10',
				'left'   => '10',
			);
			if ( 'on_bottom_curved' === $wpcp_overlay_position ) {
				$shortcode_data['wpcp_content_inner_padding'] = array(
					'top'    => '20',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
				);
			}
		}

		$nav_icons = isset( $shortcode_data['navigation_icons'] ) ? $shortcode_data['navigation_icons'] : 'angle';
		// Update old carousel navigation icons according to the current enhancement.
		switch ( $nav_icons ) {
			case 'angle-double':
				$shortcode_data['navigation_icons'] = 'right_open_outline';
				break;
			case 'arrow':
				$shortcode_data['navigation_icons'] = 'arrow';
				break;
			case 'long-arrow':
				$shortcode_data['navigation_icons'] = 'arrow';
				break;
			case 'caret':
				$shortcode_data['navigation_icons'] = 'triangle';
				break;
		}

		$old_wpcp_arrows = isset( $shortcode_data['wpcp_navigation'] ) ? $shortcode_data['wpcp_navigation'] : '';
		// Use database updater for the "carousel navigation" and "hide on mobile" options.
		switch ( $old_wpcp_arrows ) {
			case 'show':
				$shortcode_data['wpcp_carousel_navigation']['wpcp_navigation']     = '1';
				$shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] = '0';
				break;
			case 'hide':
				$shortcode_data['wpcp_carousel_navigation']['wpcp_navigation']     = '0';
				$shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] = '0';
				break;
			case 'hide_mobile':
				$shortcode_data['wpcp_carousel_navigation']['wpcp_navigation']     = '1';
				$shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] = '1';
				break;
		}

		$old_wpcp_dots = isset( $shortcode_data['wpcp_pagination'] ) ? $shortcode_data['wpcp_pagination'] : '';
		// Use database updater for the "carousel pagination" and "hide on mobile" options.
		switch ( $old_wpcp_dots ) {
			case 'show':
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination']                = '1';
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] = '0';
				break;
			case 'hide':
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination']                = '0';
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] = '0';
				break;
			case 'hide_mobile':
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination']                = '1';
				$shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] = '1';
				break;
		}

		$thumb_orientation_old = isset( $shortcode_data['thumbnails_orientation'] ) ? $shortcode_data['thumbnails_orientation'] : 'horizontal';
		// Set vertical orientation for thumbnail slider.
		if ( 'vertical' === $thumb_orientation_old ) {
			$shortcode_data['wpcp_thumbnail_position'] = 'left';
			$shortcode_data['thumbnails_orientation']  = 'horizontal';
		}

		/* Autoplay Speed */
		$autoplay_speed                             = isset( $shortcode_data['carousel_auto_play_speed']['all'] ) && ! empty( $shortcode_data['carousel_auto_play_speed']['all'] ) ? $shortcode_data['carousel_auto_play_speed']['all'] : '3000';
		$shortcode_data['carousel_auto_play_speed'] = $autoplay_speed;
		/* Carousel/Sliding Speed */
		$speed = isset( $shortcode_data['standard_carousel_scroll_speed']['all'] ) && ! empty( $shortcode_data['standard_carousel_scroll_speed']['all'] ) ? $shortcode_data['standard_carousel_scroll_speed']['all'] : '600';
		$shortcode_data['standard_carousel_scroll_speed'] = $speed;

		update_post_meta( $carousel_id, 'sp_wpcp_shortcode_options', $shortcode_data );
	}
}
