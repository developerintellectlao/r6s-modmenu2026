<?php
/**
 * Product ratting
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/product-type/ratting.php
 *
 * @package WP_Carousel_Pro
 */

$av_rating      = $product->get_average_rating();
$average_rating = ( $av_rating / 5 ) * 100;

if ( $average_rating > 0 && $show_product_rating ) :
	?>
<div class="wpcp-product-rating woocommerce">
	<div class="woocommerce-product-rating">
		<div class="star-rating" title="<?php echo __( 'Rated ', 'wp-carousel-pro' ) . $av_rating . __( ' out of 5', 'wp-carousel-pro' ); ?>">
			<span style="width:<?php echo esc_attr( $average_rating . '%' ); ?>"></span>
		</div>
	</div>
</div>
<?php endif; ?>
