<?php
/**
 * Framework group field.
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

use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;

if ( ! class_exists( 'SP_WPCP_Framework_Field_group' ) ) {
	/**
	 *
	 * Field: group
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_group extends SP_WPCP_Framework_Fields {

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
					'max'                    => 0,
					'min'                    => 0,
					'fields'                 => array(),
					'button_title'           => esc_html__( 'Add New', 'wp-carousel-pro' ),
					'accordion_title_prefix' => '',
					'accordion_title_number' => false,
					'accordion_title_auto'   => true,
				)
			);

			$title_prefix = ( ! empty( $args['accordion_title_prefix'] ) ) ? $args['accordion_title_prefix'] : '';
			$title_number = ( ! empty( $args['accordion_title_number'] ) ) ? true : false;
			$title_auto   = ( ! empty( $args['accordion_title_auto'] ) ) ? true : false;

			if ( preg_match( '/' . preg_quote( '[' . $this->field['id'] . ']' ) . '/', $this->unique ) ) {

				echo '<div class="sp_wpcp-notice sp_wpcp-notice-danger">' . esc_html__( 'Error: Field ID conflict.', 'wp-carousel-pro' ) . '</div>';

			} else {

				echo wp_kses_post( $this->field_before() );

				echo '<div class="sp_wpcp-cloneable-item sp_wpcp-cloneable-hidden" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

				echo '<div class="sp_wpcp-cloneable-helper">';
				echo '<i class="sp_wpcp-cloneable-sort fa fa-arrows-alt"></i>';
				echo '<i class="sp_wpcp-cloneable-clone fa fa-clone"></i>';
				echo '<i class="sp_wpcp-cloneable-remove sp_wpcp-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'wp-carousel-pro' ) . '"></i>';
				echo '</div>';

				echo '<h4 class="sp_wpcp-cloneable-title">';
				echo '<span class="sp_wpcp-cloneable-text">';
				echo ( $title_number ) ? '<span class="sp_wpcp-cloneable-title-number"></span>' : '';
				echo ( $title_prefix ) ? '<span class="sp_wpcp-cloneable-title-prefix">' . esc_attr( $title_prefix ) . '</span>' : '';
				echo ( $title_auto ) ? '<span class="sp_wpcp-cloneable-value"><span class="sp_wpcp-cloneable-placeholder"></span></span>' : '';
				echo '</span>';
				echo '</h4>';

				echo '<div class="sp_wpcp-cloneable-content">';
				foreach ( $this->field['fields'] as $field ) {

					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_unique  = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][0]' : $this->field['id'] . '[0]';

					SP_WPCP_Framework::field( $field, $field_default, '___' . $field_unique, 'field/group' );

				}
				echo '</div>';

				echo '</div>';

				echo '<div class="sp_wpcp-cloneable-wrapper sp_wpcp-data-wrapper" data-title-number="' . esc_attr( $title_number ) . '" data-field-id="[' . esc_attr( $this->field['id'] ) . ']" data-max="' . esc_attr( $args['max'] ) . '" data-min="' . esc_attr( $args['min'] ) . '">';

				if ( ! empty( $this->value ) ) {

					$num = 0;

					foreach ( $this->value as $value ) {

						$first_id    = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : '';
						$first_value = ( isset( $value[ $first_id ] ) ) ? $value[ $first_id ] : '';
						$first_value = ( is_array( $first_value ) ) ? reset( $first_value ) : $first_value;

						echo '<div class="sp_wpcp-cloneable-item">';
						echo '<div class="sp_wpcp-cloneable-helper">';
						echo '<i class="sp_wpcp-cloneable-sort fa fa-arrows-alt"></i>';
						echo '<i class="sp_wpcp-cloneable-clone fa fa-clone"></i>';
						echo '<i class="sp_wpcp-cloneable-remove sp_wpcp-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'wp-carousel-pro' ) . '"></i>';
						echo '</div>';

						echo '<h4 class="sp_wpcp-cloneable-title">';
						echo '<span class="sp_wpcp-cloneable-text">';
						echo ( $title_number ) ? '<span class="sp_wpcp-cloneable-title-number">' . esc_attr( $num + 1 ) . '.</span>' : '';
						echo ( $title_prefix ) ? '<span class="sp_wpcp-cloneable-title-prefix">' . esc_attr( $title_prefix ) . '</span>' : '';
						echo ( $title_auto ) ? '<span class="sp_wpcp-cloneable-value">' . esc_attr( $first_value ) . '</span>' : '';
						echo '</span>';
						echo '</h4>';

						echo '<div class="sp_wpcp-cloneable-content">';

						foreach ( $this->field['fields'] as $field ) {

							$field_unique = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][' . $num . ']' : $this->field['id'] . '[' . $num . ']';
							$field_value  = ( isset( $field['id'] ) && isset( $value[ $field['id'] ] ) ) ? $value[ $field['id'] ] : '';

							SP_WPCP_Framework::field( $field, $field_value, $field_unique, 'field/group' );

						}

						echo '</div>';

						echo '</div>';
						$num++;

					}
				}

				echo '</div>';

				echo '<div class="sp_wpcp-cloneable-alert sp_wpcp-cloneable-max">' . esc_html__( 'You cannot add more.', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp-cloneable-alert sp_wpcp-cloneable-min">' . esc_html__( 'You cannot remove more.', 'wp-carousel-pro' ) . '</div>';
				echo '<a href="#" class="button button-primary sp_wpcp-cloneable-add">' . $args['button_title'] . '</a>';

				echo wp_kses_post( $this->field_after() );

			}

		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'jquery-ui-accordion' ) ) {
				wp_enqueue_script( 'jquery-ui-accordion' );
			}

			if ( ! wp_script_is( 'jquery-ui-sortable' ) ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

		}

	}
}
