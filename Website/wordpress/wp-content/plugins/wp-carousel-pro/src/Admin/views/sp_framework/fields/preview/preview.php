<?php
/**
 * Preview field file.
 *
 * @link http://shapedplugin.com
 * @since 2.4.0
 *
 * @package Wp_Carousel_Pro
 * @subpackage Wp_Carousel_Pro/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WPCP_Framework_Field_preview' ) ) {
	/**
	 *
	 * Field: shortcode
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_preview extends SP_WPCP_Framework_Fields {

		/**
		 * Shortcode field constructor.
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
			echo '<div class="sp-wpcp-preview-box"><div id="sp-wpcp-preview-box"></div></div>';
		}

	}
}
