<?php
/**
 * Post thumbnails
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/post-type/thumbnails.php
 *
 * @package WP_Carousel_Pro
 */

if ( $show_slide_image ) {
	if ( has_post_thumbnail( $post_query->post->ID ) ) {
		$image_id             = get_post_thumbnail_id();
		$image_alt_text       = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		$the_image_title_attr = ' title="' . $wpcp_get_post_title_attr . '"';
		$image_title_attr     = 'true' == $show_image_title_attr ? $the_image_title_attr : '';

		$image_attr = self::image_attr( $image_id, $image_sizes, $is_variable_width, $carousel_mode, $image_width, $image_height, $image_crop, $show_2x_image );
		$post_thumb = self::image_tag( $lazy_load_image, $carousel_mode, $image_attr, $image_alt_text, $image_title_attr, $lazy_load_img, $wpcp_layout );
		if ( $thumbnail_slider ) {
			?>
		<div class="<?php echo esc_attr( $grid_column ); ?>">
			<div class="wpcp-slide-image">
				<?php echo $post_thumb; ?>
			</div>
		</div>
			<?php
		} else {
			?>
		<div class="wpcp-slide-image">
			<a href="<?php echo esc_url( get_the_permalink() ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
				<?php echo $post_thumb; ?>
			</a>
		</div>
			<?php
		}
	} else {
		if ( $thumbnail_slider || 'thumbnails-slider' === $wpcp_layout ) {
			?>
	<div class="<?php echo esc_attr( $grid_column ); ?>">
		<div class="wpcp-slide-image">
			<img src="<?php echo WPCAROUSEL_URL; ?>Frontend/img/placeholder.png" width="600" height="450" alt="placeholder">
		</div>
	</div>
			<?php
		}
	}
} // End of Has post thumbnail.
