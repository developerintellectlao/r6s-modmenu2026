<?php
/**
 * Video caption.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/video-type/caption.php
 *
 * @package WP_Carousel_Pro
 */

// Video caption.
if ( $show_img_description && ! empty( $sp_url['video_desc'] ) ) {
	?>
<div class="wpcp-all-captions">
	<?php echo wp_kses_post( $sp_url['video_desc'] ); ?>
</div>
<?php } ?>
