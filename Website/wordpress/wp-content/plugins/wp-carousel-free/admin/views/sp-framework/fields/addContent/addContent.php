<?php
/**
 * Framework sub message field.
 *
 * @link https://shapedplugin.com
 * @since 2.5.8
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/sp-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_addContent' ) ) {
	/**
	 *
	 * Field: addContent
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCF_Field_addContent extends SP_WPCF_Fields {
		/**
		 * Constructor function.
		 *
		 * @param array  $field field.
		 * @param string $value field value.
		 * @param string $unique field unique.
		 * @param string $where field where.
		 * @param string $parent field parent.
		 * @since 2.0
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
			$style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';
			// echo '<div class="wpcf-addContent wpcf-addContent-' . esc_attr( $style ) . '">' . wp_kses_post( $this->field['content'] ) . '</div>';
			echo ! empty( $this->field['text'] ) ? '<a href="#" class="button button-primary wpcf-cloneable-add"><i class="fa fa-plus-circle"></i> ' . $this->field['text'] . ' </a>' : '';
			echo '<div class="wpcf-desc-text">' . wp_kses_post( $this->field['content'] ) . '</div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
