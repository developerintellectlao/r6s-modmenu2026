<?php if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.
/**
 *
 * Field: tabbed
 *
 * @since  4.0.0
 * @version 4.0.0
 */

use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;

if ( ! class_exists( 'SP_WPCP_Framework_Field_tabbed' ) ) {

	class SP_WPCP_Framework_Field_tabbed extends SP_WPCP_Framework_Fields {

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
		 * Render field
		 *
		 * @return void
		 */
		public function render() {

			$unallows = array( 'tabbed' );
			echo wp_kses_post( $this->field_before() );

			echo '<div class="sp_wpcp-tabbed-nav">';
			foreach ( $this->field['tabs'] as $key => $tab ) {
				$tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? '<i class="sp_wpcp--icon ' . $tab['icon'] . '"></i>' : '';
				$tabbed_active = ( empty( $key ) ) ? ' class="sp_wpcp-tabbed-active"' : '';

				echo '<a href="#"' . $tabbed_active . '>' . wp_kses_post( $tabbed_icon . $tab['title'] ) . '</a>';
			}
			echo '</div>';

			echo '<div class="sp_wpcp-tabbed-sections">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';
				echo '<div class="sp_wpcp-tabbed-section' . esc_attr( $tabbed_hidden ) . '">';

				foreach ( $tab['fields'] as $field ) {
					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true; }
					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique : '';
					SP_WPCP_Framework::field( $field, $field_value, $unique_id, 'field/tabbed' );
				}

				echo '</div>';
			}
			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
