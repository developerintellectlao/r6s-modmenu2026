<?php
/**
 * Framework license field.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

use ShapedPlugin\WPCarouselPro\Includes\License;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! class_exists( '\ShapedPlugin\WPCarouselPro\Includes\License\SP_WPCP_Framework_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 3.2.4
	 * @version 3.2.4
	 */
	class SP_WPCP_Framework_Field_license extends SP_WPCP_Framework_Fields {

		/**
		 * Field class constructor.
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
		public function render() {
			echo wp_kses_post( $this->field_before() );
			$type = ( ! empty( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : 'text';

			$manage_license       = new License( WPCAROUSEL_PRO_FILE, WPCAROUSEL_VERSION, 'ShapedPlugin', WPCAROUSEL_STORE_URL, WPCAROUSEL_ITEM_ID, WPCAROUSEL_ITEM_SLUG );
			$license_key          = $manage_license->get_license_key();
			$license_key_status   = $manage_license->get_license_status();
			$license_status       = ( is_object( $license_key_status ) ? $license_key_status->license : '' );
			$license_notices      = $manage_license->license_notices();
			$license_status_class = '';
			$license_active       = '';
			$license_data         = $manage_license->api_request();

			echo '<div class="wp-carousel-pro-license text-center">';
			echo '<h3>' . esc_html__( 'WP Carousel Pro License Key', 'wp-carousel-pro' ) . '</h3>';
			if ( 'valid' === $license_status ) {
				$license_status_class = 'license-key-active';
				$license_active       = '<span>' . esc_html__( 'Active', 'wp-carousel-pro' ) . '</span>';
				echo '<p>' . esc_html__( 'Your license key is active.', 'wp-carousel-pro' ) . '</p>';
			} elseif ( 'expired' === $license_status ) {
				echo '<p style="color: red;">Your license key expired on ' . date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) ) . '. <a href="' . WPCAROUSEL_STORE_URL . '/checkout/?edd_license_key=' . esc_attr( $license_key ) . '&download_id=' . esc_attr( WPCAROUSEL_ITEM_ID ) . '&utm_campaign=wp_carousel_pro&utm_source=licenses&utm_medium=expired" target="_blank">Renew license key at discount.</a></p>';
			} else {
				echo '<p>Please activate your license key to make the plugin work. <a href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/getting-started/activating-license-key/" target="_blank">How to activate license key?</a></p>';
			}
			echo '<div class="wp-carousel-pro-license-area">';
			echo '<div class="wp-carousel-pro-license-key"><input class="wp-carousel-pro-license-key-input ' . esc_attr( $license_status_class ) . '" type="' . esc_attr( $type ). '" name="' . esc_attr( $this->field_name() ). '" value="' . esc_attr( $this->value ). '"' . $this->field_attributes() . ' />' . wp_kses_post( $license_active ) . '</div>';// phpcs:ignore
			wp_nonce_field( 'sp_wp_carousel_pro_nonce', 'sp_wp_carousel_pro_nonce' );
			if ( 'valid' === $license_status ) {
				echo '<input style="color: #dc3545; border-color: #dc3545;" type="submit" class="button-secondary btn-license-deactivate" name="sp_wp_carousel_pro_license_deactivate" value="' . esc_html__( 'Deactivate', 'wp-carousel-pro' ) . '"/>';
			} else {
				echo '<input type="submit" class="button-secondary btn-license-save-activate" name="' . esc_attr( $this->unique ) . '[_nonce][save]" value="' . esc_html__( 'Activate', 'wp-carousel-pro' ) . '"/>';
				echo '<input type="hidden" class="btn-license-activate" name="sp_wp_carousel_pro_license_activate" value="' . esc_html__( 'Activate', 'wp-carousel-pro' ) . '"/>';
			}
			echo '<br><div class="wp-carousel-pro-license-error-notices">' . esc_html( $license_notices ) . '</div>';
			echo '</div>';
			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
