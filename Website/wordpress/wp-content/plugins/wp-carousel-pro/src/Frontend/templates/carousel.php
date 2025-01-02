<?php
/**
 * Carousel.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/carousel.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$wpcp_vertical = ( 'true' === $vertical ) ? ' wpcp_swiper_vertical' : '';
$dir_type      = ( isset( $rtl ) && 'true' === $rtl ) ? 'rtl' : 'ltr';
?>
<div id="wpcpro-wrapper-<?php echo esc_attr( $post_id ); ?>" class="wpcp-carousel-wrapper wpcpro-wrapper wpcp-wrapper-<?php echo esc_attr( $post_id ); ?>" data-slider-type="<?php echo esc_attr( $data_slider_effect ); ?>" data-shaders-type="<?php echo esc_attr( $data_shaders_effect ); ?>" >
<?php
self::section_title( $post_id, $section_title, $main_section_title );
self::preloader( $preloader_image, $post_id, $preloader );
?>
	<div id="sp-wp-carousel-pro-id-<?php echo esc_attr( $post_id ); ?>" dir="<?php echo esc_attr( $dir_type ); ?>" class="<?php echo esc_attr( $carousel_classes . $wpcp_vertical ); ?> swiper" <?php echo $carousel_attr; ?> <?php echo $carousel_types; ?> <?php echo $lightbox_setting; ?> data-variableWidth="<?php echo esc_attr( $variable_width ); ?>">
		<?php if ( 'ticker' !== $carousel_mode ) { ?>
		<div class="swiper-wrapper">
		<?php } ?>
			<?php self::get_item_loops( $upload_data, $shortcode_data, $post_id, $animation_class, $post_query, $slider_animation ); ?>
		<?php if ( 'ticker' !== $carousel_mode ) { ?>
		</div>
		<?php } ?>
		<?php
		if ( 'fashion' === $data_slider_effect ) {
			?>
		<!-- right/next navigation button -->
		<div class="fashion-slider-button-prev fashion-slider-button">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 350 160 90">
			<g class="fashion-slider-svg-wrap">
				<g class="fashion-slider-svg-circle-wrap">
				<circle cx="42" cy="42" r="40"></circle>
				</g>
				<path class="fashion-slider-svg-arrow" d="M.983,6.929,4.447,3.464.983,0,0,.983,2.482,3.464,0,5.946Z">
				</path>
				<path class="fashion-slider-svg-line" d="M80,0H0"></path>
			</g>
			</svg>
		</div>
		<!-- left/previous navigation button -->
		<div class="fashion-slider-button-next fashion-slider-button">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 350 160 90">
			<g class="fashion-slider-svg-wrap">
				<g class="fashion-slider-svg-circle-wrap">
				<circle cx="42" cy="42" r="40"></circle>
				</g>
				<path class="fashion-slider-svg-arrow" d="M.983,6.929,4.447,3.464.983,0,0,.983,2.482,3.464,0,5.946Z">
				</path>
				<path class="fashion-slider-svg-line" d="M80,0H0"></path>
			</g>
			</svg>
		</div>
			<?php
		} else {
			// If we need pagination.
			if ( 'triple' !== $data_slider_effect ) {
				$pagination_type = '';
				if ( 'strokes' === $carousel_pagination_type ) {
					$pagination_type = ' wpcp-pagination-strokes';
				} elseif ( 'fraction' === $carousel_pagination_type ) {
					$pagination_type = ' wpcp-pagination-fraction';
				}
				if ( $wpcp_dots && 'scrollbar' !== $carousel_pagination_type && 'ticker' !== $carousel_mode ) {
					?>
			<div class="wpcp-swiper-dots swiper-pagination<?php echo esc_attr( $pagination_type ); ?>"></div>
				<?php } ?>
		<!-- If we need navigation buttons -->
				<?php if ( 'true' === $arrows && 'ticker' !== $carousel_mode ) { ?>
				<div class="wpcp-prev-button swiper-button-prev wpcp-nav"><i></i></div>
				<div class="wpcp-next-button swiper-button-next wpcp-nav"><i></i></div>
			<?php } ?>
		<!-- scrollbar -->
				<?php
				if ( $wpcp_dots && 'scrollbar' === $carousel_pagination_type && 'ticker' !== $carousel_mode ) {
					?>
					<div class="swiper-scrollbar wpcp-pagination-scrollbar"></div>
					<?php
				}
			}
		}
		?>
	</div>
</div>
