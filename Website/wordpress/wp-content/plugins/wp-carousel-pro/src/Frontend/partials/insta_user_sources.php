<?php
if ( ! empty( $all_image_data ) ) {
	shuffle( $all_image_data );
	foreach ( $all_image_data as $key => $value ) {
		$image        = $value['media_url'];
		$media_type   = $value['media_type'];
		$image_data   = $image;
		$image_src    = $image_data;
		$image_height = '';
		$image_width  = '';
		if ( 'VIDEO' == $media_type ) {
			$image_data = $value['thumbnail_url'];
		}
		$image_linking_url = $value['permalink'];
		$image_height      = '';
		$image_width       = '';
		$image_width       = '';
		if ( 'false' !== $lazy_load_image && 'ticker' !== $carousel_mode ) {
			$image = sprintf( '<img class="wcp-lazy" data-lazy="%1$s" src="%2$s"  width="%3$s" height="%4$s">', $thumbnail, $lazy_load_img, $image_width, $image_height );
		} else {
			$image = sprintf( '<img src="%1$s"  width="%2$s" height="%3$s">', $image_data, $image_width, $image_height );
		}
		include self::wpcp_locate_template( 'loop/external-type/instagram.php' );
	}
}
