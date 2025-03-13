<?php
/**
 * The image carousel template.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/audio-type.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item wcp-audio-item">
		<?php
			require self::wpcp_locate_template( 'loop/audio-type/audio.php' );
			require self::wpcp_locate_template( 'loop/audio-type/caption.php' );
		?>
	</div>
</div>
