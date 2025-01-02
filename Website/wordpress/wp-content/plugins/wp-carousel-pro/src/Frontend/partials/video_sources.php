<?php
foreach ( $sp_urls as $sp_url ) {
	$image_width_attr     = '';
	$image_height_attr    = '';
	$image_src            = isset( $sp_url['video_thumb_url'] ) ? $sp_url['video_thumb_url'] : '';
	$video_thumb_alt_text = isset( $sp_url['video_thumb_alt'] ) ? $sp_url['video_thumb_alt'] : '';

	if ( 'self_hosted' === $sp_url['video_type'] ) {
		$video_thumb_url_id     = isset( $sp_url['video_thumb_url']['id'] ) ? $sp_url['video_thumb_url']['id'] : '';
		$wp_img_src_thumb       = wp_get_attachment_image_src( $video_thumb_url_id, $image_sizes );
		$wpcp_img_full          = isset( $sp_url['video_thumb_url']['url'] ) ? $sp_url['video_thumb_url']['url'] : '';
		$image_alt_title        = isset( $sp_url['video_thumb_url']['alt'] ) ? $sp_url['video_thumb_url']['alt'] : '';
		$wp_img_src_thumb_width = isset( $wp_img_src_thumb[1] ) && ! empty( $wp_img_src_thumb[1] ) ? $wp_img_src_thumb[1] : '';
		$image_width_attr       = ( $is_variable_width && 'ticker' !== $carousel_mode ) ? 'auto' : $wp_img_src_thumb_width;
		$image_height_attr      = isset( $wp_img_src_thumb[2] ) && ! empty( $wp_img_src_thumb[2] ) ? $wp_img_src_thumb[2] : '';
		$image_size_url         = isset( $wp_img_src_thumb[0] ) && ! empty( $wp_img_src_thumb[0] ) ? $wp_img_src_thumb[0] : '';
		if ( ( 'custom' === $image_sizes ) && ( ! empty( $image_width ) && $sp_url['video_thumb_url']['width'] >= ! $image_width ) && ( ! empty( $image_height ) ) && $sp_url['video_thumb_url']['height'] >= ! $image_height ) {
			$image_resize_url  = self::image_resize( $wpcp_img_full, $image_width, $image_height, $image_crop );
			$image_width_attr  = ( $is_variable_width && 'ticker' !== $carousel_mode ) ? 'auto' : $image_width;
			$image_height_attr = $image_height;
		}
		$image_src = ! empty( $image_resize_url ) ? $image_resize_url : $image_size_url;
		if ( empty( $image_src ) ) {
			$image_src = 'https://via.placeholder.com/650x450';
		}
		// Image Link.
		$image_linking_meta = wp_get_attachment_metadata( $video_thumb_url_id );
		$image_linking_urls = isset( $image_linking_meta['image_meta'] ) ? $image_linking_meta['image_meta'] : '';
		$image_linking_url  = ! empty( $image_linking_urls['wpcplinking'] ) ? esc_url( $image_linking_urls['wpcplinking'] ) : '';
	}
	if ( ! empty( $image_src ) ) {
		$video_url = isset( $sp_url['video_url'] ) && ! empty( $sp_url['video_url'] ) ? $sp_url['video_url'] : $image_src;
		include self::wpcp_locate_template( 'loop/video-type.php' );
	}
} // End foreach.
