<?php
/**
 * Image
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/image-type/image.php
 *
 * @package WP_Carousel_Pro
 */

?>
	<div class="wpcp-slide-image">
	<?php
	if ( 'l_box' === $image_link_show && ! $thumbnail_slider ) {

		$l_box_cap      = $show_l_box_img_caption && $image_caption ? $image_caption : '';
		$l_box_desc     = $show_lightbox_image_desc && $image_description ? $image_description : '';
		$image_full_url = $image_light_box_url[0];

		if ( $wpcp_watermark && function_exists( 'imagecreatefromstring' ) ) {
			$image_full_url = self::wpcp_watermark( $image_light_box_url[0] )['url'];
		}
		?>
			<a class="wcp-light-box" data-wpc_url='<?php echo esc_url( $image_full_url ); ?>'  href="<?php echo esc_url( $image_full_url ); ?>" data-fancybox="wpcp_view" data-item-slug="<?php echo esc_attr( self::get_item_slug( $image_full_url ) ); ?>" data-buttons='["<?php echo esc_attr( $l_box_zoom_button ); ?>","<?php echo esc_attr( $show_full_screen ); ?>","<?php echo esc_attr( $show_slideshow ); ?>","<?php echo esc_attr( $show_social_share ); ?>","<?php echo esc_attr( $show_download_button ); ?>","<?php echo esc_attr( $show_img_thumb ); ?>","<?php echo esc_attr( $l_box_close_button ); ?>"]'  data-lightbox-gallery="group-<?php echo esc_attr( $post_id ); ?>" data-caption="<?php echo esc_html( $l_box_cap ); ?>"
			data-desc="<?php echo esc_html( $l_box_desc ); ?>"
			>
			<figure>
			<?php echo $image; ?>
				<?php if ( 'none' !== $l_box_icon_style && 'with_overlay' !== $wpcp_post_detail && ! $is_mobile ) : ?>
				<div class="wpcp_icon_overlay l_box-icon-position-<?php echo esc_attr( $l_box_icon_position ); ?>">
					<?php echo $l_box_icon; ?>
				</div>
				<?php endif; ?>
			</figure>
			</a>
			<?php

	} elseif ( 'link' === $image_link_show && isset( $image_linking_url ) && filter_var( $image_linking_url, FILTER_VALIDATE_URL ) && ! $thumbnail_slider ) {
		?>
		<a href="<?php echo esc_url( $image_linking_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" <?php echo $image_link_nofollow; ?>><?php echo $image; // phpcs:ignore ?></a>
		<?php
	} else {
		echo $image;
	}
	?>
	</div>
