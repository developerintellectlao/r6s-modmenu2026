<?php
 /**
  * Framework slider field.
  *
  * @link       https://shapedplugin.com
  * @since      4.0.0
  *
  * @package    WP_Carousel_Pro
  * @subpackage WP_Carousel_Pro/admin/views
  */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WPCP_Framework_Field_slider' ) ) {
	/**
	 *
	 * Field: slider
	 *
	 * @since 4.0.0
	 * @version 4.0.0
	 */
	class SP_WPCP_Framework_Field_slider extends SP_WPCP_Framework_Fields {

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

			$args = wp_parse_args(
				$this->field,
				array(
					'max'  => 100,
					'min'  => 0,
					'step' => 1,
					'unit' => '',
				)
			);

			$is_unit = ( ! empty( $args['unit'] ) ) ? ' sp_wpcp--is-unit' : '';
			if ( isset( $this->value['all'] ) ) {
				$this->value = $this->value['all'];
			}
			echo wp_kses_post( $this->field_before() );

			echo '<div class="sp_wpcp--wrap">';
			echo '<div class="sp_wpcp-slider-ui"></div>';
			echo '<div class="sp_wpcp--input">';
			echo '<input type="number" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes( array( 'class' => 'sp_wpcp-input-number' . esc_attr( $is_unit ) ) ) . ' data-min="' . esc_attr( $args['min'] ) . '" data-max="' . esc_attr( $args['max'] ) . '" data-step="' . esc_attr( $args['step'] ) . '" step="any" />';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo ( ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
			echo '</div>';
			echo '</div>';

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-slider' ) ) {
				wp_enqueue_script( 'jquery-ui-slider' );
			}
		}
	}
}
