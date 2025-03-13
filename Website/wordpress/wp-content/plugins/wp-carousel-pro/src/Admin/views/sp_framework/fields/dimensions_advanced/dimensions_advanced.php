<?php
/**
 * Framework background field.
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

if ( ! class_exists( 'SP_WPCP_Field_dimensions_advanced' ) ) {

	/**
	 * The Advanced Dimensions field class.
	 *
	 * @since 3.5
	 */
	class SP_WPCP_Framework_Field_dimensions_advanced extends SP_WPCP_Framework_Fields {

		/**
		 * Advanced Dimensions field constructor.
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
					'lg_desktop_icon'    => '<i class="fa fa-television"></i>',
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'all_icon'           => '<i class="fa fa-arrows"></i>',
					'top_placeholder'    => esc_html__( 'top', 'wp-carousel-pro' ),
					'right_placeholder'  => esc_html__( 'right', 'wp-carousel-pro' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'wp-carousel-pro' ),
					'left_placeholder'   => esc_html__( 'left', 'wp-carousel-pro' ),
					'all_placeholder'    => esc_html__( 'all', 'wp-carousel-pro' ),
					'top_text'           => '',
					'right_text'         => '',
					'style_text'         => '',
					'lg_desktop'         => false,
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'all'                => false,
					'color'              => true,
					'style'              => true,
					'styles'             => array( 'Soft-crop', 'Hard-crop' ),
					'unit'               => 'px',
					'min'                => '0',
				)
			);

			$default_value = array(
				'lg_desktop' => '',
				'top'        => '',
				'right'      => '',
				'bottom'     => '',
				'left'       => '',
				'color'      => '',
				'style'      => 'solid',
				'all'        => '',
				'min'        => '',
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
			$unit          = ! empty( $args['unit'] ) ? $args['unit'] : '';
			$is_unit       = ( ! empty( $unit ) ) ? ' sp_wpcp--is-unit' : '';
			$value         = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="sp_wpcp--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			$min = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';

			if ( ! empty( $args['lg_desktop'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';
				echo '<div class="sp_wpcp--input sp_wpcp--input">';
				echo ( ! empty( $args['lg_desktop_icon'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args['lg_desktop_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[lg_desktop]' ) ) . '" value="' . esc_attr( $value['lg_desktop'] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="sp_wpcp-number' . esc_attr( $is_unit ) . '" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_html( $args['unit'] ) . '</span>' : '';
				echo '</div>';
			}
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';
				echo '<div class="sp_wpcp--input sp_wpcp--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args['all_icon'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="sp_wpcp-number' . esc_attr( $is_unit ) . '" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_html( $args['unit'] ) . '</span>' : '';
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

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

					echo '<div class="sp_wpcp--dimension">';
					echo ( ! empty( $args[ $property . '_text' ] ) ) ? '<div class="sp_wpcp--title">' . esc_html( $args[ $property . '_text' ] ) . '</div>' : '';
					echo '<div class="sp_wpcp--input sp_wpcp--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="sp_wpcp-number' . esc_attr( $is_unit ) . '" />';
					echo ( ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_html( $args['unit'] ) . '</span>' : '';
					echo '</div>';
					echo '</div>';
				}
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="sp_wpcp--dimension_style">';
				echo ( ! empty( $args['style_text'] ) ) ? '<div class="sp_wpcp--title">' . esc_html( $args['style_text'] ) . '</div>' : '';
				echo '<div class="sp_wpcp--input sp_wpcp--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( $args['styles'] as $style_prop ) {
					$selected = ( $value['style'] === $style_prop ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $style_prop ) . '"' . esc_attr( $selected ) . '>' . esc_html( $style_prop ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . $default_value['color'] . '"' : '';
				echo '<div class="sp_wpcp--input sp_wpcp-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="sp_wpcp-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
			}

			echo '</div>';
			echo '<div class="clear"></div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
