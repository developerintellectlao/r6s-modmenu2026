<?php
/**
 * Post Title
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/post-type/title.php
 *
 * @package WP_Carousel_Pro
 */

$get_wpcp_title = get_the_title();
if ( $wpcp_title_chars_limit ) {
	$get_wpcp_title = wp_html_excerpt( get_the_title(), $wpcp_title_chars_limit, '...' );
}

if ( ( $show_img_title && ! empty( $get_wpcp_title ) ) ) {
	?>
<h2 class="wpcp-post-title">
	<a href="<?php echo esc_url( apply_filters( 'wpcp_post_title_url', get_the_permalink() ) ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
		<?php echo wp_kses_post( $get_wpcp_title ); ?>
	</a>
</h2>
	<?php
}
