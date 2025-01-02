<?php
/**
 * Framework image select field.
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

if ( ! class_exists( 'SP_WPCP_Framework_Field_image_select' ) ) {
	/**
	 *
	 * Field: image_select
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_image_select extends SP_WPCP_Framework_Fields {

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

			$args = wp_parse_args(
				$this->field,
				array(
					'multiple' => false,
					'inline'   => false,
					'options'  => array(),
				)
			);

			$inline = ( $args['inline'] ) ? ' sp_wpcp--inline-list' : '';

			$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

			echo wp_kses_post( $this->field_before() );

			if ( ! empty( $args['options'] ) ) {

				echo '<div class="sp_wpcp-siblings sp_wpcp--image-group' . esc_attr( $inline ) . '" data-multiple="' . esc_attr( $args['multiple'] ) . '">';

				$num = 1;

				foreach ( $args['options'] as $key => $option ) {

					$type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
					$extra   = ( $args['multiple'] ) ? '[]' : '';
					$active  = ( in_array( $key, $value ) ) ? ' sp_wpcp--active' : '';
					$checked = ( in_array( $key, $value ) ) ? ' checked' : '';
					$image   = isset( $option['image'] ) ? $option['image'] : $option;

					echo '<div class="sp_wpcp--sibling sp_wpcp--image' . esc_attr( $active ) . '">';
					echo '<figure>';
					echo '<img src="' . esc_url( $image ) . '" alt="img-' . esc_attr( $num++ ) . '" />';

					if ( isset( $option['option_demo_url'] ) ) {
						echo '<p class="sp-carousel-type">' . esc_html( $option['text'] ) . '<a href="' . esc_url( $option['option_demo_url'] ) . '" tooltip="Demo" class="wpcp-live-demo-icon" target="_blank"><i class="wpcp-icon-external_link"></i></a></p>';
					}else{
						echo isset( $option['text'] ) ? '<p class="sp-carousel-type">' . wp_kses_post( $option['text'] ) . '</p>' : '';
					}
					// echo '<p class="sp-carousel-type">Slider<a href="https://realtestimonials.io/demos/slider/" tooltip="Demo" class="wpcp-live-demo-icon" target="_blank"><i class="wpcp-icon-lightbox-animation"></i></a></p>';

					echo '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $this->field_name( $extra ) ) . '" value="' . esc_attr( $key ) . '"' . $this->field_attributes() . esc_attr( $checked ) . '/>';// phpcs:ignore
					echo '</figure>';
					echo '</div>';

				}

				echo '</div>';

			}

			echo wp_kses_post( $this->field_after() );

		}
	}
}
