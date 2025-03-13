<?php
foreach ( $content_sources as $content_source ) {
	global $wp_embed;
	$wpcp_inline_css = $content_source['wpcp_carousel_content_bg'];
	$image_bg        = '';
	$color_bg        = 'background-color: ' . $wpcp_inline_css['background-color'] . ';';

	if ( ! empty( $wpcp_inline_css['background-image']['url'] ) ) {
		$image_bg = ' background-image: url(' . $wpcp_inline_css['background-image']['url'] . '); background-position: ' . $wpcp_inline_css['background-position'] . '; background-repeat: ' . $wpcp_inline_css['background-repeat'] . '; background-size: ' . $wpcp_inline_css['background-size'] . '; background-attachment: ' . $wpcp_inline_css['background-attachment'] . ';';
	} else {
		$image_bg = ' background-image: linear-gradient(' . $wpcp_inline_css['background-gradient-direction'] . ', ' . $wpcp_inline_css['background-color'] . ', ' . $wpcp_inline_css['background-gradient-color'] . ');';
	}
	$content_style = $color_bg . $image_bg;
	ob_start();
	include self::wpcp_locate_template( 'loop/content-type.php' );
	echo apply_filters( 'sp_wpcp_content_carousel_content', ob_get_clean() );
}
