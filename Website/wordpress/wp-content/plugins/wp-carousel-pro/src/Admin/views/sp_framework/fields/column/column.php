<?php
/**
 * Framework column field.
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

if ( ! class_exists( 'SP_WPCP_Framework_Field_column' ) ) {

	/**
	 * The column field class.
	 */
	class SP_WPCP_Framework_Field_column extends SP_WPCP_Framework_Fields {

		/**
		 * Column field constructor.
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
		 * Render column function.
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'lg_desktop_icon'        => '<i class="fa fa-television"></i>',
					'desktop_icon'           => '<i class="fa fa-desktop"></i>',
					'laptop_icon'            => '<i class="fa fa-laptop"></i>',
					'tablet_icon'            => '<i class="fa fa-tablet"></i>',
					'mobile_icon'            => '<i class="fa fa-mobile"></i>',
					'all_text'               => '<i class="fa fa-arrows"></i>',
					'lg_desktop_placeholder' => esc_html__( 'Large Desktop', 'wp-carousel-pro' ),
					'desktop_placeholder'    => esc_html__( 'Desktop', 'wp-carousel-pro' ),
					'laptop_placeholder'     => esc_html__( 'Small Desktop', 'wp-carousel-pro' ),
					'tablet_placeholder'     => esc_html__( 'Tablet', 'wp-carousel-pro' ),
					'mobile_placeholder'     => esc_html__( 'Mobile', 'wp-carousel-pro' ),
					'all_placeholder'        => esc_html__( 'all', 'wp-carousel-pro' ),
					'lg_desktop'             => true,
					'desktop'                => true,
					'laptop'                 => true,
					'tablet'                 => true,
					'mobile'                 => true,
					'min'                    => '1',
					'unit'                   => false,
					'all'                    => false,
					'units'                  => array( 'px', '%', 'em' ),
				)
			);

			$default_values = array(
				'lg_desktop' => '5',
				'desktop'    => '4',
				'laptop'     => '3',
				'tablet'     => '2',
				'mobile'     => '1',
				'min'        => '',
				'all'        => '',
				'unit'       => 'px',
			);

			$value = wp_parse_args( $this->value, $default_values );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="sp_wpcp--inputs" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';
			$min = ( isset( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';
			if ( ! empty( $args['all'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? ' placeholder="' . $args['all_placeholder'] . '"' : '';
				echo '<div class="sp_wpcp--input">';
				echo ( ! empty( $args['all_text'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--label-icon">' . esc_html( $args['all_text'] ) . '</span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[all]' ) ) . '" value="' . esc_attr( $value['all'] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="sp_wpcp-number" />';
				echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
				echo '</div>';
			} else {

				$properties = array();

				foreach ( array( 'lg_desktop', 'desktop', 'laptop', 'tablet', 'mobile' ) as $prop ) {
					if ( ! empty( $args[ $prop ] ) ) {
						$properties[] = $prop;
					}
				}

				$properties = ( array( 'laptop', 'mobile' ) === $properties ) ? array_reverse( $properties ) : $properties;

				foreach ( $properties as $property ) {

					$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? ' placeholder="' . $args[ $property . '_placeholder' ] . '"' : '';

					echo '<div class="sp_wpcp--input">';
					echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<span class="sp_wpcp--label sp_wpcp--icon">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</span>' : '';
					echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '"' . wp_kses_post( $placeholder . $min ) . ' class="sp_wpcp-number" />';
					echo ( count( $args['units'] ) === 1 && ! empty( $args['unit'] ) ) ? '<span class="sp_wpcp--label sp_wpcp--unit">' . esc_attr( $args['units'][0] ) . '</span>' : '';
					echo '</div>';

				}
			}

			if ( ! empty( $args['unit'] ) && count( $args['units'] ) > 1 ) {
				echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
				foreach ( $args['units'] as $unit ) {
					$selected = ( $value['unit'] === $unit ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_html( $unit ) . '</option>';
				}
				echo '</select>';
			}

			echo '<div class="clear"></div>';
			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
