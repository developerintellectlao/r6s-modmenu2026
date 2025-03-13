<?php

foreach ( $mix_sources  as $source ) {
	$mix_source_type         = isset( $source['carousel_mix_source_type'] ) ? $source['carousel_mix_source_type'] : '';
	$mix_audio_embed       = ! empty( $source['wpcp_mix_audio_embed'] ) ? $source['wpcp_mix_audio_embed'] : '';
	$mix_content_description = isset( $source['carousel_mix_content_description'] ) && ! empty( $source['carousel_mix_content_description'] ) ? $source['carousel_mix_content_description'] : '';
	global $wp_embed;
	include self::wpcp_locate_template( 'loop/mix-content-type.php' );
}
