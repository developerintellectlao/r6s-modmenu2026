<?php
/**
 * Video thumb
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/video-type/image.php
 *
 * @package WP_Carousel_Pro
 */

?>
<div class="wpcp-slide-image">
<?php
if ( filter_var( $image_src, FILTER_VALIDATE_URL ) ) {
	if ( $thumbnail_slider ) {
		?>
		<img src="<?php echo esc_url( $image_src ); ?>"  width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
		<?php
	} else {
		if ( 'wistia' === $sp_url['video_type'] || 'tiktok' === $sp_url['video_type'] || 'twitch' === $sp_url['video_type'] ) {
			?>
	<a class="wcp-video" data-fancybox="wpcp_view" data-type="iframe" data-buttons='["zoom","slideShow","fullScreen","share","download","thumbs","close"]' href="<?php echo esc_url( $video_url ); ?>">
			<?php
			if ( 'false' !== $lazy_load_image && 'ticker' !== $carousel_mode ) {
				?>
		<img loading="lazy" class="wcp-lazy swiper-lazy" data-src="<?php echo esc_url( $image_src ); ?>" src="<?php echo esc_url( $image_src ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $video_url ) ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
				<?php
			} else {
				?>
		<img src="<?php echo esc_url( $image_src ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $video_url ) ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
				<?php
			}
			?>
		<i class="fa fa-play-circle-o" aria-hidden="true"></i>
	</a>
			<?php
		} else {
			if ( ! $image_width_attr && $image_src ) {
				$image_attr        = @getimagesize( $image_src );
				$image_width_attr  = isset( $image_attr[0] ) ? $image_attr[0] : $image_width_attr;
				$image_height_attr = isset( $image_attr[1] ) ? $image_attr[1] : $image_height_attr;
			}
			?>
<a class="wcp-video" data-fancybox="wpcp_view" data-buttons='["zoom","slideShow","fullScreen","share","download","thumbs","close"]' href="<?php echo esc_url( $video_url ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $video_url ) ); ?>">
			<?php
			if ( 'false' !== $lazy_load_image && 'ticker' !== $carousel_mode ) {
				?>
	<img loading="lazy" class="wcp-lazy swiper-lazy" data-src="<?php echo esc_url( $image_src ); ?>" src="<?php echo esc_url( $image_src ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
				<?php
			} else {
				?>
		<img src="<?php echo esc_url( $image_src ); ?>" width="<?php echo esc_attr( $image_width_attr ); ?>" height="<?php echo esc_attr( $image_height_attr ); ?>" alt="<?php echo esc_attr( $video_thumb_alt_text ); ?>">
				<?php
			}
			if ( isset( $sp_url['video_url'] ) && ! empty( $sp_url['video_url'] ) ) {
				?>
		<i class="fa fa-play-circle-o" aria-hidden="true"></i>
			<?php } ?>
			</a>
			<?php
		}
	}
}
?>
</div>
