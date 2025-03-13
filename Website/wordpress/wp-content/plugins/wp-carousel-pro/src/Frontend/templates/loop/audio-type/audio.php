<?php
/**
 * Audio
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/audio-type/audio.php
 *
 * @package WP_Carousel_Pro
 */

?>
<div class="wpcp-slide-audio">
	<?php if ( 'self_hosted' == $audio_type ) { ?>
<audio controls>
	<source src="<?php echo esc_url( $audio_url ); ?>">
</audio>
	<?php } ?>
	<?php
	if ( 'embed' == $audio_type ) {
		echo $wpcp_audio_embed;
	}
	?>
</div>
