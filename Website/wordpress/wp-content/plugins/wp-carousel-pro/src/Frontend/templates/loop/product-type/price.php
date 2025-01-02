<?php
/**
 * Product price
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/product-type/price.php
 *
 * @package WP_Carousel_Pro
 */

$price_html = $product->get_price_html();
if ( $price_html && $show_product_price ) {
	?>
	<div class="wpcp-product-price"><?php echo $price_html; ?></div>
	<?php
}
