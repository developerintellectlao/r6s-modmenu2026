<?php
/**
 * Product name
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/product-type/name.php
 *
 * @package WP_Carousel_Pro
 */

// Product name.
if ( $wpcp_name_chars_limit ) {
	$wpcp_product_name = wp_html_excerpt( get_the_title(), $wpcp_name_chars_limit, '...' );
} else {
	$wpcp_product_name = get_the_title();
}
if ( $show_product_name ) :
	?>
<h2 class="wpcp-product-title">
	<a href="<?php echo esc_attr( get_the_permalink() ); ?>">
		<?php echo wp_kses_post( $wpcp_product_name ); ?>
	</a>
</h2>
<?php endif; ?>
