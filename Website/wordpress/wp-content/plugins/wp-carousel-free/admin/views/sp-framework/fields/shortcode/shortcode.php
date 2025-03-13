<?php
/**
 * Framework shortcode field.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( ' SP_WPCF_Field_shortcode' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_shortcode extends SP_WPCF_Fields {

		// /**
		//  * Render
		//  *
		//  * @return void
		//  */
		// public function render() {

		// 	// Get the Post ID.
		// 	$post_id = get_the_ID();

		// 	echo ( ! empty( $post_id ) ) ? '<div class="wpcp-scode-wrap"><span class="wpcp-sc-title">Shortcode:</span><span class="wpcf-shortcode-selectable">[sp_wpcarousel id="' . esc_attr( $post_id ) . '"]</span></div><div class="wpcp-scode-wrap"><span class="wpcp-sc-title">Template Include:</span><span class="wpcf-shortcode-selectable">&lt;?php echo do_shortcode(\'[sp_wpcarousel id="' . esc_attr( $post_id ) . '"]\'); ?&gt;</span></div><div class="spwpc-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>' : '';
		// }
		/**
		 * Render method.
		 *
		 * @return void
		 */
		public function render() {
			// Get the Post ID.
			$post_id = get_the_ID();
			if ( ! empty( $this->field['shortcode'] ) ) {
				echo ( ! empty( $post_id ) ) ? '<div class="wpcp-scode-wrap-side"><p>To display your carousel, slider or gallery, add the following shortcode into your post, custom post types, page, widget or block editor. If adding the slider to your theme files, additionally include the surrounding PHP code, <a href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/faqs/#template-include" target="_blank">see how</a>.</p><span class="wpcf-shortcode-selectable">[sp_wpcarousel id="' . esc_attr( $post_id ) . '"]</span></div><div class="spwpc-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>' : '';
			} else {
				echo ( ! empty( $post_id ) ) ? '<div class="wpcp-scode-wrap-side"><p>WP Carousel has seamless integration with Gutenberg, Classic Editor, <strong>Elementor,</strong> Divi, Bricks, Beaver, Oxygen, WPBakery Builder, etc.</p></div>' : '';
			}
		}
	}
}
