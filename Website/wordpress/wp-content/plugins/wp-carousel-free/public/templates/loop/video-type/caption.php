<?php
/**
 * Video caption.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel/templates/loop/video-type/caption.php
 *
 * @package WP_Carousel
 */

// Video caption.
if ( ! empty( $sp_url['video_desc'] ) ) {
	?>
<div class="wpcp-all-captions">
	<?php echo wp_kses_post( $sp_url['video_desc'] ); ?>
</div>
<?php } ?>
