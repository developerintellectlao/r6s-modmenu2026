<?php
/**
 * The post carousel template.
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/post-type.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item">
		<?php require self::wpcp_locate_template( 'loop/post-type/thumbnails.php' ); ?>
		<div class="wpcp-all-captions <?php echo esc_attr( $animation_class ); ?>">
		<?php
			require self::wpcp_locate_template( 'loop/post-type/meta.php' );
			require self::wpcp_locate_template( 'loop/post-type/title.php' );
			require self::wpcp_locate_template( 'loop/post-type/content.php' );
			require self::wpcp_locate_template( 'loop/post-type/social-share.php' );
		?>
		</div>
	</div>
</div>
