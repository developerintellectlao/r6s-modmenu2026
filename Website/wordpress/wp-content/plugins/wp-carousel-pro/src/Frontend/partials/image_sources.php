<?php
if ( is_array( $attachments ) || ! empty( $attachments ) ) :
	foreach ( $attachments as $attachment_id ) {
		$image_data = get_post( $attachment_id );
		if ( is_object( $image_data ) ) {
			$image_title      = $image_data->post_title;
			$image_alt_titles = $image_data->_wp_attachment_image_alt;
			$image_caption    = $image_data->post_excerpt;
			if ( 'title' === $image_title_source ) {
				$image_caption = $image_title;
			} elseif ( 'alt' === $image_title_source ) {
				$image_caption = $image_alt_titles;
			}

			$image_description = $image_data->post_content;

			$image_alt_title    = ! empty( $image_alt_titles ) ? $image_alt_titles : $image_title;
			$image_linking_meta = wp_get_attachment_metadata( $attachment_id );

			$image_linking_urls = isset( $image_linking_meta['image_meta'] ) ? $image_linking_meta['image_meta'] : '';
			$image_linking_url  = ! empty( $image_linking_urls['wpcplinking'] ) ? esc_url( $image_linking_urls['wpcplinking'] ) : '';
			$crop_position      = get_post_meta( $attachment_id, 'crop_position', true );
			$link_target        = get_post_meta( $attachment_id, 'wpcplinktarget', true ) ? '_blank' : $link_target;
			$image_crop_new     = $image_crop;
			// Check if a custom crop position has been provided and needs to be applied.
			if ( ! empty( $crop_position ) && 'center-center' !== $crop_position && 'custom' === $image_sizes && $image_crop ) {
				/**
				 * If the crop position is not empty, not the default 'center-center',
				 * and the image size is 'custom', we update the $image_crop value
				 * to use the provided $crop_position.
				 *
				 * This ensures that custom cropping (e.g., 'left-top', 'right-bottom', etc.)
				 * is applied instead of the default 'center-center' cropping.
				 */
				$image_crop_new = $crop_position;
			}

			$wcp_light_box_class = '';
			if ( 'l_box' === $image_link_show ) {
				$image_linking_url   = '#';
				$wcp_light_box_class = 'wcp-light-box-caption';
			}
			$the_image_title_attr = ' title="' . $image_title . '"';
			$image_title_attr     = 'true' === $show_image_title_attr ? $the_image_title_attr : '';

			$image_attr = self::image_attr( $attachment_id, $image_sizes, $is_variable_width, $carousel_mode, $image_width, $image_height, $image_crop_new, $show_2x_image, $wpcp_watermark );

			$image               = self::image_tag( $lazy_load_image, $carousel_mode, $image_attr, $image_alt_title, $image_title_attr, $lazy_load_img, $wpcp_layout );
			$image_light_box_url = wp_get_attachment_image_src( $attachment_id, 'full' );
			include self::wpcp_locate_template( 'loop/image-type.php' );
		}
	} // End foreach.
endif;
