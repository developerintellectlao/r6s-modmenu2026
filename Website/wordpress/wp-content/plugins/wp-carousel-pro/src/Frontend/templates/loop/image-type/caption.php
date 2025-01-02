<?php

/**
 * Image
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/image-type/caption.php
 *
 * @package WP_Carousel_Pro
 */

// Image Caption and description.
$caption_html_tag = apply_filters( 'filter_wpcp_image_caption_tag', 'h2' );
if ( ( $show_img_caption && ! empty( $image_caption ) ) || ( $show_img_description && ! empty( $image_description ) ) ) {
	?>
	<div class="wpcp-all-captions <?php echo esc_attr( $animation_class ); ?>">
		<?php if ( $show_img_caption && ! empty( $image_caption ) ) { ?>
		<<?php echo esc_attr( $caption_html_tag ); ?> class="wpcp-image-caption">
			<?php
			if ( 'none' === $image_link_show ) {
				echo wp_kses_post( $image_caption );
			} else {
				?>
			<a class="<?php echo esc_attr( $wcp_light_box_class ); ?>" href="<?php echo esc_url( $image_linking_url ); ?>" <?php echo $image_link_nofollow; ?> target="<?php echo esc_attr( $link_target ); // phpcs:ignore ?>">
				<?php echo wp_kses_post( $image_caption ); ?>
			</a>
				<?php
			}
			?>
		</<?php echo esc_attr( $caption_html_tag ); ?>>
			<?php
		}
		// Image description.
		if ( $show_img_description && ! empty( $image_description ) ) {
			$desc_limit  = wp_trim_words( $image_description, $img_desc_word_limit, '...' );
			$description = 'limit' === $img_desc_display_type ? $desc_limit : $image_description;
			?>
			<div class="wpcp-image-description">
				<?php echo wp_kses_post( $description ); ?>
				<?php
				if ( 'none' !== $image_link_show && 'limit' === $img_desc_display_type && $img_desc_read_more ) {
					?>
			<div class="wpcp-read-more-wrapper">
			<a class="<?php echo esc_attr( $wcp_light_box_class ); ?> wpcp-image-read-more" href="<?php echo esc_url( $image_linking_url ); ?>" <?php echo $image_link_nofollow; ?> target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_attr( $img_readmore_label ); // phpcs:ignore ?> </a></div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}
