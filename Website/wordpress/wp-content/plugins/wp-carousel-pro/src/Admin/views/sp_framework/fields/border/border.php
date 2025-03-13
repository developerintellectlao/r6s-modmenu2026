<?php
/**
 * Framework border field.
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

if ( ! class_exists( 'SP_WPCP_Framework_Field_border' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_border extends SP_WPCP_Framework_Fields {
		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'top_icon'           => '<i class="fa fa-long-arrow-alt-up"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-alt-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-alt-down"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-alt-right"></i>',
					'all_icon'           => '<i class="fa fa-arrows-alt"></i>',
					'top_placeholder'    => esc_html__( 'top', 'wp-carousel-pro' ),
					'right_placeholder'  => esc_html__( 'right', 'wp-carousel-pro' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'wp-carousel-pro' ),
					'left_placeholder'   => esc_html__( 'left', 'wp-carousel-pro' ),
					'all_placeholder'    => esc_html__( 'all', 'wp-carousel-pro' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'radius'             => false,
					'color'              => true,
					'hover-color'        => true,
					'style'              => true,
					'unit'               => 'px',
					'min'                => '0',
				)
			);

			$default_value = array(
				'top'         => '',
				'right'       => '',
				'bottom'      => '',
				'left'        => '',
				'color'       => '',
				'hover-color' => '',
				'style'       => 'solid',
				'all'         => '',
				'unit'        => 'px',
			);

			$border_props = array(
				'solid'  => esc_html__( 'Solid', 'wp-carousel-pro' ),
				'dashed' => esc_html__( 'Dashed', 'wp-carousel-pro' ),
				'dotted' => esc_html__( 'Dotted', 'wp-carousel-pro' ),
				'double' => esc_html__( 'Double', 'wp-carousel-pro' ),
				'inset'  => esc_html__( 'Inset', 'wp-carousel-pro' ),
				'outset' => esc_html__( 'Outset', 'wp-carousel-pro' ),
				'groove' => esc_html__( 'Groove', 'wp-carousel-pro' ),
				'ridge'  => esc_html__( 'ridge', 'wp-carousel-pro' ),
				'none'   => esc_html__( 'None', 'wp-carousel-pro' ),
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="sp_wpcp-width">';
			echo '<div class="sp_wpcp--title">Width</div>';
			echo '<div class="sp_wpcp--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			$min = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? $args['all_placeholder'] : '';

				echo '<div class="sp_wpcp--input">';

				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="sp_wpcp-input-number sp_wpcp--is-unit" step="any" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';

			} else {

				$properties = array();

				foreach ( array( 'top', 'right', 'bottom', 'left' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'right', 'left' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? $args[ $property . '_placeholder' ] : '';

					echo '<div class="sp_wpcp--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="sp_wpcp-input-number sp_wpcp--is-unit" step="any" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
					echo '</div>';

				}
			}
			echo '</div>';
			echo '</div>';
			if ( ! empty( $args['style'] ) ) {
				echo '<div class="sp_wpcp-style">';
				echo '<div class="sp_wpcp--title">Style</div>';
				echo '<div class="sp_wpcp--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $border_props as $border_prop_key => $border_prop_value ) {
					$selected = ( $value['style'] === $border_prop_key ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $border_prop_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $border_prop_value ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="sp_wpcp--color">';
				echo '<div class="sp_wpcp--title">Color</div>';
				echo '<div class="sp_wpcp-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="sp_wpcp-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['hover-color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover-color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover-color'] ) . '"' : '';
				echo '<div class="sp_wpcp--color">';
				echo '<div class="sp_wpcp--title">Hover Color</div>';
				echo '<div class="sp_wpcp-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover-color]' ) ) . '" value="' . esc_attr( $value['hover-color'] ) . '" class="sp_wpcp-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}
			if ( ! empty( $args['radius'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';

				echo '<div class="sp_wpcp-radius">';
				echo '<div class="sp_wpcp--title">Radius</div>';
				echo '<div class="sp_wpcp--left sp_wpcp--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[radius]' ) ) . '" value="' . esc_attr( $value['radius'] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="sp_wpcp-number" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';

			}
			echo wp_kses_post( $this->field_after() );
		}
	}
}
