<?php
/**
 * The image carousel template.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/video-type.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item wcp-video-item">
		<?php
			require self::wpcp_locate_template( 'loop/video-type/image.php' );
			require self::wpcp_locate_template( 'loop/video-type/caption.php' );
		?>
	</div>
</div>
