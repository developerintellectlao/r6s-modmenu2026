<?php
/**
 * Framework date time field.
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

if ( ! class_exists( 'SP_WPCP_Framework_Field_datetime' ) ) {
	/**
	 *
	 * Field: datetime
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_datetime extends SP_WPCP_Framework_Fields {

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

			if ( ! empty( $this->field['from_to'] ) ) {

				$args = wp_parse_args(
					$this->field,
					array(
						'text_from' => esc_html__( 'Start date', 'wp-carousel-pro' ),
						'text_to'   => esc_html__( 'End date', 'wp-carousel-pro' ),
					)
				);

				$value = wp_parse_args(
					$this->value,
					array(
						'from' => '',
						'to'   => '',
					)
				);

				echo '<label class="sp_wpcp--from">' . esc_attr( $args['text_from'] ) . ' <input type="text" name="' . esc_attr( $this->field_name( '[from]' ) ) . '" value="' . esc_attr( $value['from'] ) . '"' . $this->field_attributes() . '/></label>';// phpcs:ignore
				echo '<label class="sp_wpcp--to">' . esc_attr( $args['text_to'] ) . ' <input type="text" name="' . esc_attr( $this->field_name( '[to]' ) ) . '" value="' . esc_attr( $value['to'] ) . '"' . $this->field_attributes() . '/></label>';// phpcs:ignore
			} else {
				echo '<input type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . '/>';// phpcs:ignore
			}
			echo wp_kses_post( $this->field_after() );

		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_style( 'sp_wpcp_datetimepicker', WPCAROUSEL_URL . 'Admin/views/sp_framework/assets/css/jquery.datetimepicker.min.css', array(), WPCAROUSEL_VERSION, 'all' );
			wp_enqueue_script( 'sp_wpcp_datetimepicker', WPCAROUSEL_URL . 'Admin/views/sp_framework/assets/js/jquery.datetimepicker.full.min.js', array( 'jquery' ), WPCAROUSEL_VERSION, true );

		}

	}
}
