<?php
/**
 * Framework image_color field.
 *
 * @link https://shapedplugin.com
 * @since 4.0.0
 *
 * @package WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WPCP_Framework_Field_image_color' ) ) {
	/**
	 *
	 * Field: image_color
	 *
	 * @since 4.0.0
	 * @version 4.0.0
	 */
	class SP_WPCP_Framework_Field_image_color extends SP_WPCP_Framework_Fields {

		/**
		 * Field constructor.
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

			$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $options ) ) {
				foreach ( $options as $key => $option ) {

					$color_value  = ( ! empty( $this->value[ $key ] ) ) ? $this->value[ $key ] : '';
					$logo_key     = 'fl_' . $key;
					$logo_value   = ( ! empty( $this->value[ $logo_key ] ) ) ? $this->value[ $logo_key ] : '';
					$default_attr = ( ! empty( $this->field['default'][ $key ] ) ) ? ' data-default-color="' . esc_attr( $this->field['default'][ $key ] ) . '"' : '';

					echo '<div class="sp_wpcp--left sp_wpcp-field-color">';
					echo '<div class="sp_wpcp--title">' . $option . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo '<input type="text" name="' . esc_attr( $this->field_name( '[' . $key . ']' ) ) . '" value="' . esc_attr( $color_value ) . '" class="sp_wpcp-color"' . $default_attr . $this->field_attributes() . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

					echo '<input type="text" name="' . esc_attr( $this->field_name( '[fl_' . $key . ']' ) ) . '" value="' . esc_attr( $logo_value ) . '" class="sp_wpcp-image-color"/>';
					echo '</div>';
				}
			}
			echo wp_kses_post( $this->field_after() );
		}
	}
}
