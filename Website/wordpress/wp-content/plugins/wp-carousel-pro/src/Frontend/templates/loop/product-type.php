<?php
/**
 * The product carousel template.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/product-type.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
if ( ! class_exists( 'WooCommerce' ) ||  ! is_object( $product ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item">
		<?php
		require self::wpcp_locate_template( 'loop/product-type/image.php' );
		?>
		<div class="wpcp-all-captions <?php echo esc_attr( $animation_class ); ?>">
			<?php
			require self::wpcp_locate_template( 'loop/product-type/name.php' );
			require self::wpcp_locate_template( 'loop/product-type/price.php' );
			require self::wpcp_locate_template( 'loop/product-type/ratting.php' );
			require self::wpcp_locate_template( 'loop/product-type/content.php' );
			require self::wpcp_locate_template( 'loop/product-type/add_to_cart.php' );
			?>
		</div>
	</div>
</div>
