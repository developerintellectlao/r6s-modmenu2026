<?php
/**
 * Product Content
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/product-type/content.php
 *
 * @package WP_Carousel_Pro
 */

// Product content & Read more button.
if ( 'short' === $show_product_desc ) {
	$product_content = get_the_excerpt();
} else {
	$product_content = get_the_content();
}
$product_content = ! empty( $product_content_limit_number ) ? wp_trim_words( $product_content, $product_content_limit_number, '...' ) : $product_content;


$content_count = str_word_count( $product_content );
if ( $content_count >= $product_content_limit_number && $show_product_readmore ) {
	$product_content_more_button = sprintf( '<div class="wpcp-product-more-content"><a href="%1$s">%2$s</a></div>', get_the_permalink(), $product_readmore_text );
} else {
	$product_content_more_button = '';
}
if ( 'hide' !== $show_product_desc ) :
	?>
<div class="wpcp-product-content">
	<?php echo do_shortcode( $product_content ) . $product_content_more_button; ?>
</div>
<?php endif; ?>
