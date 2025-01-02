<?php
/**
 * Content Carousel content
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/content-carousel/content.php.
 *
 * @package WP_Carousel_Pro
 */

?>
<div class="wpcp-single-item" style="<?php echo esc_attr( $content_style ); ?>">
	<div class="wpcp-single-content">
		<?php echo do_shortcode( wpautop( $wp_embed->autoembed( $content_source['carousel_content_description'] ) ) ); ?>
	</div>
</div>
