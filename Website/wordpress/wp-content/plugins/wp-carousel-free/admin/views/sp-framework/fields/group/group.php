<?php
/**
 * Framework group field.
 *
 * @link       https://shapedplugin.com
 *
 * @package    WP_Carousel
 * @subpackage WP_Carousel/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! class_exists( 'SP_WPCF_Field_group' ) ) {
	/**
	 *
	 * Field: group
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_group extends SP_WPCF_Fields {

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
					'button_title'           => esc_html__( 'Add New', 'wp-carousel-free' ),
					'accordion_title_prefix' => '',
					'accordion_title_number' => false,
					'accordion_title_auto'   => true,
				)
			);

			$title_prefix = ( ! empty( $args['accordion_title_prefix'] ) ) ? $args['accordion_title_prefix'] : '';
			$title_number = ( ! empty( $args['accordion_title_number'] ) ) ? true : false;
			$title_auto   = ( ! empty( $args['accordion_title_auto'] ) ) ? true : false;

			if ( preg_match( '/' . preg_quote( '[' . $this->field['id'] . ']' ) . '/', $this->unique ) ) {

				echo '<div class="wpcf-notice wpcf-notice-danger">' . esc_html__( 'Error: Field ID conflict.', 'wp-carousel-free' ) . '</div>';

			} else {

				echo wp_kses_post( $this->field_before() );

				echo '<div class="wpcf-cloneable-item wpcf-cloneable-hidden" data-depend-id="' . esc_attr( $this->field['id'] ) . '">';

				echo '<div class="wpcf-cloneable-helper">';
				echo '<i class="wpcf-cloneable-sort fa fa-arrows-alt"></i>';
				echo '<i class="wpcf-cloneable-clone fa fa-clone"></i>';
				echo '<i class="wpcf-cloneable-remove wpcf-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'wp-carousel-free' ) . '"></i>';
				echo '</div>';

				echo '<h4 class="wpcf-cloneable-title">';
				echo '<span class="wpcf-cloneable-text">';
				echo ( $title_number ) ? '<span class="wpcf-cloneable-title-number"></span>' : '';
				echo ( $title_prefix ) ? '<span class="wpcf-cloneable-title-prefix">' . esc_attr( $title_prefix ) . '</span>' : '';
				echo ( $title_auto ) ? '<span class="wpcf-cloneable-value"><span class="wpcf-cloneable-placeholder"></span></span>' : '';
				echo '</span>';
				echo '</h4>';

				echo '<div class="wpcf-cloneable-content">';
				foreach ( $this->field['fields'] as $field ) {

					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_unique  = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][0]' : $this->field['id'] . '[0]';

					SP_WPCF::field( $field, $field_default, '___' . $field_unique, 'field/group' );

				}
				echo '</div>';

				echo '</div>';

				echo '<div class="wpcf-cloneable-wrapper wpcf-data-wrapper" data-title-number="' . esc_attr( $title_number ) . '" data-field-id="[' . esc_attr( $this->field['id'] ) . ']" data-max="' . esc_attr( $args['max'] ) . '" data-min="' . esc_attr( $args['min'] ) . '">';

				if ( ! empty( $this->value ) ) {

					$num = 0;

					foreach ( $this->value as $value ) {

						$first_id    = ( isset( $this->field['fields'][0]['id'] ) ) ? $this->field['fields'][0]['id'] : '';
						$first_value = ( isset( $value[ $first_id ] ) ) ? $value[ $first_id ] : '';
						$first_value = ( is_array( $first_value ) ) ? reset( $first_value ) : $first_value;

						echo '<div class="wpcf-cloneable-item">';
						echo '<div class="wpcf-cloneable-helper">';
						echo '<i class="wpcf-cloneable-sort fa fa-arrows-alt"></i>';
						echo '<i class="wpcf-cloneable-clone fa fa-clone"></i>';
						echo '<i class="wpcf-cloneable-remove wpcf-confirm fa fa-times" data-confirm="' . esc_html__( 'Are you sure to delete this item?', 'wp-carousel-free' ) . '"></i>';
						echo '</div>';

						echo '<h4 class="wpcf-cloneable-title">';
						echo '<span class="wpcf-cloneable-text">';
						echo ( $title_number ) ? '<span class="wpcf-cloneable-title-number">' . esc_attr( $num + 1 ) . '.</span>' : '';
						echo ( $title_prefix ) ? '<span class="wpcf-cloneable-title-prefix">' . esc_attr( $title_prefix ) . '</span>' : '';
						echo ( $title_auto ) ? '<span class="wpcf-cloneable-value">' . esc_attr( $first_value ) . '</span>' : '';
						echo '</span>';
						echo '</h4>';

						echo '<div class="wpcf-cloneable-content">';

						foreach ( $this->field['fields'] as $field ) {

							$field_unique = ( ! empty( $this->unique ) ) ? $this->unique . '[' . $this->field['id'] . '][' . $num . ']' : $this->field['id'] . '[' . $num . ']';
							$field_value  = ( isset( $field['id'] ) && isset( $value[ $field['id'] ] ) ) ? $value[ $field['id'] ] : '';

							SP_WPCF::field( $field, $field_value, $field_unique, 'field/group' );

						}

						echo '</div>';

						echo '</div>';
						++$num;

					}
				}

				echo '</div>';

				echo '<div class="wpcf-cloneable-alert wpcf-cloneable-max">' . esc_html__( 'You cannot add more.', 'wp-carousel-free' ) . '</div>';
				echo '<div class="wpcf-cloneable-alert wpcf-cloneable-min">' . esc_html__( 'You cannot remove more.', 'wp-carousel-free' ) . '</div>';
				echo '<a href="#" class="button button-primary wpcf-cloneable-add">' . wp_kses_post( $args['button_title'] ) . '</a>';

				// <h3></h3>

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
