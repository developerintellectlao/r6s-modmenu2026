<?php
/**
 * Justified.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/justified.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div id="wpcpro-wrapper-<?php echo esc_attr( $post_id ); ?>" class="wpcp-carousel-wrapper wpcpro-wrapper wpcp-justified  wpcp-gallery wpcp-wrapper-<?php echo esc_attr( $post_id ); ?> <?php echo $img_protection ? ' wpcp_img_protection' : ''; ?>">
	<?php
	self::section_title( $post_id, $section_title, $main_section_title );
	self::preloader( $preloader_image, $post_id, $preloader );
	?>
	<div id="sp-wp-carousel-pro-id-<?php echo esc_attr( $post_id ); ?>" class="<?php echo esc_attr( $carousel_classes ); ?>" <?php echo $lightbox_setting; ?> data-rowheight="<?php echo $justified_row_height; ?>" data-horizontalgap="<?php echo $slide_margin; ?>" data-verticalgap="<?php echo $slide_margin_horizontal; ?>">
		<div class="wpcpro-row">
			<?php self::get_item_loops( $upload_data, $shortcode_data, $post_id, $animation_class, $post_query ); ?>
		</div>
	</div>
	<?php self::get_pagination( $upload_data, $shortcode_data, $post_id, $total_page, $posts_found ); ?>
</div> <!-- // Carousel Wrapper. -->
