<?php
/**
 *
 * Field: heading
 *
 * @since 1.0.0
 * @version 1.0.0
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_heading' ) ) {
	/**
	 *
	 * Field: heading
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_heading extends SP_WPCF_Fields {

		/**
		 * Heading field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		// public function render() {

		// echo ( ! empty( $this->field['content'] ) ) ? wp_kses_post( $this->field['content'] ) : '';
		// echo ( ! empty( $this->field['image'] ) ) ? '<img src="' . esc_url( $this->field['image'] ) . '">' : '';

		// echo ( ! empty( $this->field['after'] ) && ! empty( $this->field['link'] ) ) ? '<span class="spacer"></span><span class="support"><a target="_blank" href="' . esc_url( $this->field['link'] ) . '">' . wp_kses_post( $this->field['after'] ) . '</a></span>' : '';
		// }

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			$version = ! empty( $this->field['version'] ) ? $this->field['version'] : '';
			echo ( ! empty( $this->field['content'] ) ) ? wp_kses_post( $this->field['content'] ) : '';
			echo ( ! empty( $this->field['image'] ) ) ? '<div class="heading-wrapper"> <img src="' . esc_url( $this->field['image'] ) . '"><span class="sp_wpcp-version">' . esc_html( $version ) . '</span></div>' : '';

			// echo ( ! empty( $this->field['after'] ) && ! empty( $this->field['link'] ) ) ? '<span class="spacer"></span><span class="support"><a target="_blank" href="' . esc_url( $this->field['link'] ) . '">' . wp_kses_post( $this->field['after'] ) . '</a></span>' : '';

			echo ( ! empty( $this->field['after'] ) && ! empty( $this->field['link'] ) ) ? '<span class="sp_wpcp-support-area"><span class="support">' . wp_kses_post( $this->field['after'] ) . '</span><div class="wpcf-help-text sp_wpcp-support"><div class="sp_wpcp-info-label">Documentation</div>Check out our documentation and more information about what you can do with the WP Carousel.<a class="sp_wpcp-open-docs browser-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/introduction/" target="_blank">Browse Docs</a><div class="sp_wpcp-info-label">Need Help or Missing a Feature?</div>Feel free to get help from our friendly support team or request a new feature if needed. We appreciate your suggestions to make the plugin better.<a class="sp_wpcp-open-docs support" href="https://shapedplugin.com/create-new-ticket/" target="_blank">Get Help</a><a class="sp_wpcp-open-docs feature-request" href="https://shapedplugin.com/contact-us/" target="_blank">Request a Feature</a></div></span>' : '';
		}
	}
}
