<?php
/**
 * Thumbnails Slider.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/thumbnails-slider.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// $wpcp_vertical = ( 'true' === $vertical ) ? ' wpcp_swiper_vertical' : '';
$dir_type = ( isset( $rtl ) && 'true' === $rtl ) ? 'rtl' : 'ltr';
?>
<div id="wpcpro-wrapper-<?php echo esc_attr( $post_id ); ?>" class="wpcp-carousel-wrapper wpcpro-thumbnail-slider wpcpro-wrapper wpcp-wrapper-<?php echo esc_attr( $post_id ); ?>">
<?php
self::section_title( $post_id, $section_title, $main_section_title );
self::preloader( $preloader_image, $post_id, $preloader );
?>
	<div id="sp-wp-carousel-pro-id-<?php echo esc_attr( $post_id ); ?>" dir="<?php echo esc_attr( $dir_type ); ?>" class="<?php echo esc_attr( $carousel_classes ); ?>" <?php echo $carousel_attr; ?> <?php echo esc_attr( $carousel_types ); ?> <?php echo $lightbox_setting; ?>>
		<div class="wpcpro-gallery-slider">
			<div class="swiper-wrapper">
				<?php self::get_item_loops( $upload_data, $shortcode_data, $post_id, $animation_class, $post_query, $slider_animation ); ?>
			</div>
			<!-- If we need navigation buttons -->
			<?php if ( 'true' === $arrows ) { ?>
				<div class="wpcp-prev-button swiper-button-prev"><i class="fa"></i></div>
				<div class="wpcp-next-button swiper-button-next"><i class="fa"></i></div>
			<?php } ?>
		</div>
		<div class="wpcpro-gallery-thumbs" <?php echo esc_attr( $carousel_types ); ?> >
			<div class="swiper-wrapper">
				<?php
				self::get_item_loops( $upload_data, $shortcode_data, $post_id, $animation_class, $post_query, $slider_animation, true );
				?>
			</div>
		</div>
	</div>

</div>
