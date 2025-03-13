<?php
/**
 * Mix content carousel content
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/mix-content-type/content.php
 *
 * @package WP_Carousel_Pro
 */

$wpcp_inline_css = $source['wpcp_carousel_content_bg'];
$image_bg        = '';
$color_bg        = 'background-color: ' . $wpcp_inline_css['background-color'] . ';';
if ( ! empty( $wpcp_inline_css['background-image']['url'] ) ) {
	$image_bg = ' background-image: url(' . $wpcp_inline_css['background-image']['url'] . '); background-position: ' . $wpcp_inline_css['background-position'] . '; background-repeat: ' . $wpcp_inline_css['background-repeat'] . '; background-size: ' . $wpcp_inline_css['background-size'] . '; background-attachment: ' . $wpcp_inline_css['background-attachment'] . ';';
} else {
	$image_bg = ' background-image: linear-gradient(' . $wpcp_inline_css['background-gradient-direction'] . ', ' . $wpcp_inline_css['background-color'] . ', ' . $wpcp_inline_css['background-gradient-color'] . ');';
}
$content_style = $color_bg . $image_bg;
?>
<div class="wpcp-single-item" style="<?php echo esc_attr( $content_style ); ?>">
	<div class="wpcp-single-content">
		<?php echo do_shortcode( wpautop( $wp_embed->autoembed( $mix_content_description ) ) ); ?>
	</div>
</div>
