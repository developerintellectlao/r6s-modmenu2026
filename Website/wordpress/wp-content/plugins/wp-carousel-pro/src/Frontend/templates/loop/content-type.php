<?php
/**
 * The content carousel template.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/content-type.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item" style="<?php echo esc_attr( $content_style ); ?>">
		<div class="wpcp-single-content">
			<?php echo do_shortcode( wpautop( $wp_embed->autoembed( $content_source['carousel_content_description'] ) ) ); ?>
		</div>
	</div>
</div>
