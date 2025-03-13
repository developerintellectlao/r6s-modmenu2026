<?php
/**
 * Image
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/video-type/image.php
 *
 * @since   2.7.0
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( ! $image_width_attr && $image_src ) {
	$image_attr        = @getimagesize( $image_src );
	$image_width_attr  = isset( $image_attr[0] ) ? $image_attr[0] : $image_width_attr;
	$image_height_attr = isset( $image_attr[1] ) ? $image_attr[1] : $image_height_attr;
}
?>
<a class="wcp-light-box" data-buttons='["close"]' href="<?php echo esc_url( $video_url ); ?>"  data-fancybox="wpcp_view">
		<img src="<?php echo esc_url( $image_src ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
		<?php
		if ( isset( $sp_url['video_url'] ) && ! empty( $sp_url['video_url'] ) ) {
			?>
		<i class="fa fa-play-circle-o" aria-hidden="true"></i>
		<?php } ?>
</a>
