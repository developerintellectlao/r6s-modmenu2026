<?php
/**
 * The image carousel template.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/mix-content-type.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<?php
	if ( 'image' === $mix_source_type ) {
		include self::wpcp_locate_template( 'loop/mix-content-type/image.php' );
	}
	if ( 'content' === $mix_source_type ) {
		include self::wpcp_locate_template( 'loop/mix-content-type/content.php' );
	}
	if ( 'video' === $mix_source_type ) {
		include self::wpcp_locate_template( 'loop/mix-content-type/video.php' );
	}
	if ( 'audio' === $mix_source_type ) {
		include self::wpcp_locate_template( 'loop/mix-content-type/audio.php' );
	}
	?>
</div>
