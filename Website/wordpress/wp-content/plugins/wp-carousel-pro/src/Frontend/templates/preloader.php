<?php
/**
 * Preloader.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/preloader.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$preloader_image = apply_filters( 'sp_wpcp_preloder_url', $preloader_image );
$preloder_alt_text = apply_filters( 'sp_wpcp_preloder_alt_text', __( 'Preloader image', 'wp-carousel-pro' ) );
?>
<div id="wpcp-preloader-<?php echo esc_attr( $post_id ); ?>" class="wpcp-carousel-preloader">
	<img src="<?php echo esc_url( $preloader_image ); ?>" class="skip-lazy" alt="<?php echo $preloder_alt_text; ?>"/>
</div>
