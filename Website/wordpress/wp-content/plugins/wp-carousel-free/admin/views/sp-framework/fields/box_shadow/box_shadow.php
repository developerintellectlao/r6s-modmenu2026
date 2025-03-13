<?php
/**
 * Framework box-shadow fields.
 *
 * @link https://shapedplugin.com
 * @since 3.7.0
 *
 * @package WP_Carousel_Pro.
 * @subpackage WP_Carousel_Pro/Fields.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_box_shadow' ) ) {
	/**
	 *
	 * Field: box shadow
	 *
	 * @since 3.7.0
	 * @version 3.7.0
	 */
	class SP_WPCF_Field_box_shadow extends SP_WPCF_Fields {
		/**
		 * Constructor function.
		 *
		 * @param array  $field field.
		 * @param string $value field value.
		 * @param string $unique field unique.
		 * @param string $where field where.
		 * @param string $parent field parent.
		 * @since 3.7.0
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
					'horizontal_icon'        => __( 'X offset', 'wp-carousel-free' ),
					'vertical_icon'          => __( 'Y offset', 'wp-carousel-free' ),
					'blur_icon'              => __( 'Blur', 'wp-carousel-free' ),
					'spread_icon'            => __( 'Spread', 'wp-carousel-free' ),
					'horizontal_placeholder' => __( 'h-offset', 'wp-carousel-free' ),
					'vertical_placeholder'   => __( 'v-offset', 'wp-carousel-free' ),
					'blur_placeholder'       => __( 'blur', 'wp-carousel-free' ),
					'spread_placeholder'     => __( 'spread', 'wp-carousel-free' ),
					'horizontal'             => true,
					'vertical'               => true,
					'blur'                   => true,
					'spread'                 => true,
					'color'                  => true,
					'hover_color'            => false,
					'style'                  => false,
					'unit'                   => 'px',
				)
			);

			$default_value = array(
				'horizontal' => '0',
				'vertical'   => '0',
				'blur'       => '0',
				'spread'     => '0',
				'color'      => '#ddd',
				'style'      => 'outset',
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="wpcf--inputs">';

			$properties = array();

			foreach ( array( 'horizontal', 'vertical', 'blur', 'spread' ) as $prop ) {
				if ( ! empty( $args[ $prop ] ) ) {
					$properties[] = $prop;
				}
			}

			foreach ( $properties as $property ) {

				$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? $args[ $property . '_placeholder' ] : '';
				echo '<div class="wpcf--box_shadow">';
				echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<div class="wpcf--title">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</div>' : '';
				echo '<div class="wpcf--input">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="wpcf-input-number wpcf--is-unit" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="wpcf--label wpcf--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="wpcf--type">';
				echo '<div class="wpcf--title">' . esc_html__( 'Type', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( array( 'inset', 'outset' ) as $style ) {
					$selected = ( $value['style'] === $style ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $style ) . '"' . esc_attr( $selected ) . '>' . esc_attr( ucfirst( $style ) ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? $default_value['color'] : '';
				echo '<div class="wpcf--color">';
				echo '<div class="wpcf-field-color">';
				echo '<div class="wpcf--title">Color</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="wpcf-color" data-default-color="' . esc_attr( $default_color_attr ) . '" />';
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['hover_color'] ) ) {
				$default_hover_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? $default_value['hover_color'] : '';
				echo '<div class="wpcf--color">';
				echo '<div class="wpcf-field-color">';
				echo '<div class="wpcf--title">Hover Color</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="wpcf-color" data-default-color="' . esc_attr( $default_hover_color_attr ) . '" />';
				echo '</div>';
				echo '</div>';
			}
			echo '<div class="clear"></div>';

			echo wp_kses_post( $this->field_after() );

		}
		/**
		 * Output
		 *
		 * @return Statement
		 */
		public function output() {

			$output    = '';
			$unit      = ( ! empty( $this->value['unit'] ) ) ? $this->value['unit'] : 'px';
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			// properties.
			$horizontal = ( isset( $this->value['horizontal'] ) && '' !== $this->value['horizontal'] ) ? $this->value['horizontal'] : '';
			$vertical   = ( isset( $this->value['vertical'] ) && '' !== $this->value['vertical'] ) ? $this->value['vertical'] : '';
			$blur       = ( isset( $this->value['blur'] ) && '' !== $this->value['blur'] ) ? $this->value['blur'] : '';
			$spread     = ( isset( $this->value['spread'] ) && '' !== $this->value['spread'] ) ? $this->value['spread'] : '';
			$style      = ( isset( $this->value['style'] ) && '' !== $this->value['style'] && 'outset' !== $this->value['style'] ) ? $this->value['style'] : '';
			$color      = ( isset( $this->value['color'] ) && '' !== $this->value['color'] ) ? $this->value['color'] : '';

				$output  = $element . '{ box-shadow: ';
				$output .= ( '' !== $horizontal ) ? $horizontal . $unit : '0' . $unit;
				$output .= ( '' !== $vertical ) ? $vertical . $unit : '0' . $unit;
				$output .= ( '' !== $blur ) ? $blur . $unit : '0' . $unit;
				$output .= ( '' !== $spread ) ? $spread . $unit : '0' . $unit;
				$output .= ( '' !== $color ) ? $color : '';
				$output .= ( '' !== $style ) ? $style : '';
				$output .= ';' . $important . ' }';

			$this->parent->output_css .= $output;

			return $output;
		}
	}
}
