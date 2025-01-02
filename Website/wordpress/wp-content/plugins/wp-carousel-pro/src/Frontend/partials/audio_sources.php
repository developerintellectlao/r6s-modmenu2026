<?php
foreach ( $audio_sources as $audio ) {
	$audio_type = isset( $audio['carousel_audio_source_type']) ? $audio['carousel_audio_source_type'] : '';
	$audio_url         = isset( $audio['carousel_audio_source_upload'] ) ? $audio['carousel_audio_source_upload'] : '';
	$audio_description = isset( $audio['carousel_audio_description'] ) ? $audio['carousel_audio_description'] : '';
	$wpcp_audio_embed  = isset( $audio['wpcp_audio_embed'] ) ? $audio['wpcp_audio_embed'] : '';
	include self::wpcp_locate_template( 'loop/audio-type.php' );
}
