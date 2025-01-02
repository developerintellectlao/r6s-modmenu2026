<?php
/**
 * Framework spacing field.
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

if ( ! class_exists( 'SP_WPCP_Framework_Field_spacing' ) ) {
	/**
	 *
	 * Field: spacing
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_spacing extends SP_WPCP_Framework_Fields {

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
					'top_icon'           => '<i class="fa fa-long-arrow-up"></i>',
					'right_icon'         => '<i class="fa fa-long-arrow-right"></i>',
					'bottom_icon'        => '<i class="fa fa-long-arrow-down"></i>',
					'left_icon'          => '<i class="fa fa-long-arrow-left"></i>',
					'all_text'           => '<i class="fa fa-arrows"></i>',
					'all_text_title'     => '',
					'top_text'           => esc_html__( 'Top', 'wp-carousel-pro' ),
					'right_text'         => esc_html__( 'Right', 'wp-carousel-pro' ),
					'bottom_text'        => esc_html__( 'Bottom', 'wp-carousel-pro' ),
					'left_text'          => esc_html__( 'Left', 'wp-carousel-pro' ),
					'unit_text'          => '',
					'top_placeholder'    => esc_html__( 'top', 'wp-carousel-pro' ),
					'right_placeholder'  => esc_html__( 'right', 'wp-carousel-pro' ),
					'bottom_placeholder' => esc_html__( 'bottom', 'wp-carousel-pro' ),
					'left_placeholder'   => esc_html__( 'left', 'wp-carousel-pro' ),
					'all_placeholder'    => esc_html__( 'all', 'wp-carousel-pro' ),
					'top'                => true,
					'left'               => true,
					'bottom'             => true,
					'right'              => true,
					'unit'               => true,
					'show_units'         => true,
					'all'                => false,
					'units'              => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'top'    => '',
				'right'  => '',
				'bottom' => '',
				'left'   => '',
				'all'    => '',
				'unit'   => 'px',
			);

			$value   = wp_parse_args( $this->value, $default_values );
			$unit    = ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? $args['units'][0] : '';
			$is_unit = ( ! empty( $unit ) ) ? ' sp_wpcp--is-unit' : '';

			echo wp_kses_post( $this->field_before() );

			echo '<div class="sp_wpcp--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

			if ( ! empty( $args['all'] ) ) {
				echo '<div class="sp_wpcp--spacing-input">';
				echo ( ! empty( $args['all_text_title'] ) ) ? '<div class="sp_wpcp--title">' . esc_html( $args['all_text_title'] ) . '</div>' : '';

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . esc_attr( $args['all_placeholder'] ) . '"' : '';
				echo '<div class="sp_wpcp--input">';
				echo ( ! empty( $args['all_text'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args['all_text'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder ) . ' class="sp_wpcp-input-number' . esc_attr( $is_unit ) . '" step="any" />';
				echo ( $unit ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
				echo '</div>';
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
					echo '<div class="sp_wpcp--spacing-input">';
					echo ( ! empty( $args[ $property . '_text' ] ) ) ? '<div class="sp_wpcp--title">' . esc_html( $args[ $property . '_text' ] ) . '</div>' : '';

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . esc_attr( $args[ $property . '_placeholder' ] ) . '"' : '';

					echo '<div class="sp_wpcp--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . $placeholder . ' class="sp_wpcp-input-number' . esc_attr( $is_unit ) . '" step="any" />';// phpcs:ignore
					// preloader already escaping above.
					echo ( $unit ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';
					echo '</div>';

				}
			}

			if ( ! empty( $args['unit'] ) && ! empty( $args['show_units'] ) && count( $args['units'] ) > 1 ) {
				echo '<div class="sp_wpcp--spacing-unit">';
				echo ( ! empty( $args['unit_text'] ) ) ? '<div class="sp_wpcp--title">' . esc_html( $args['unit_text'] ) . '</div>' : '';
				echo '<div class="sp_wpcp--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
