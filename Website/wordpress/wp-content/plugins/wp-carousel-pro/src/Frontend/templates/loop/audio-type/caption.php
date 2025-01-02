<?php
/**
 * Audio caption.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/audio-type/caption.php
 *
 * @package WP_Carousel_Pro
 */

// audio caption.
if ( $show_img_caption && ! empty( $audio_description ) ) {
	?>
<div class="wpcp-all-captions">
	<?php echo wp_kses_post( $audio_description ); ?>
</div>
<?php } ?>
