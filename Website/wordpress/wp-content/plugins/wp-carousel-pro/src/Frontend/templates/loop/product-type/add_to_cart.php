<?php
/**
 * Product add to cart button
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/product-type/add_to_cart.php
 *
 * @package WP_Carousel_Pro
 */

// Add to cart button.
$wpcp_cart = apply_filters( 'wpcp_filter_product_cart', do_shortcode( '[add_to_cart id="' . get_the_ID() . '" show_price="false" style="none"]' ) );
if ( $show_product_cart ) :
	?>
<div class="wpcp-cart-button"><?php echo $wpcp_cart; ?></div>
<?php endif; ?>
