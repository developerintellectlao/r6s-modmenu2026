<?php
/**
 * Image
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/image-type/image.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */

if ( 'l_box' === $image_link_show ) {
	$image_full_url = wp_get_attachment_image_src( $attachment, 'full' );
	$image_full_url = isset( $image_full_url [0] ) ? $image_full_url[0] : $image_url[0];
	?>
	<a class="wcp-light-box" data-buttons='["close"]' data-wpc_url='<?php echo esc_url( $image_url[0] ); ?>' href="<?php echo esc_url( $image_full_url ); ?>" data-fancybox="wpcp_view">
		<figure>
			<?php echo wp_kses_post( $image ); ?>
		</figure>
	</a>
	<?php
} else {
	?>
	<div class="wpcp-slide-image">
		<?php echo wp_kses_post( $image ); ?>
	</div>
	<?php
}
