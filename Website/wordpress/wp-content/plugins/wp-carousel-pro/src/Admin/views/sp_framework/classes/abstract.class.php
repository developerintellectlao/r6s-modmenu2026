<?php
/**
 * Framework abstract.class file.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */


use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! class_exists( 'SP_WPCP_Framework_Abstract' ) ) {
	/**
	 *
	 * Abstract Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	abstract class SP_WPCP_Framework_Abstract {

		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = '';
		/**
		 * CSS Output
		 *
		 * @var string
		 */
		public $output_css = '';

		/**
		 * Construct functions of the class.
		 *
		 * @return void
		 */
		public function __construct() {

			// Collect output css and typography.
			if ( ! empty( $this->args['output_css'] ) || ! empty( $this->args['enqueue_webfont'] ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'collect_output_css_and_typography' ), 10 );
				SP_WPCP_Framework::$css = apply_filters( "sp_wpcp_{$this->unique}_output_css", SP_WPCP_Framework::$css, $this );
			}

		}

		/**
		 * Add output CSS and typography.
		 *
		 * @return void
		 */
		public function collect_output_css_and_typography() {
			$this->recursive_output_css( $this->pre_fields );
		}
		/**
		 * Add output CSS.
		 *
		 * @param array $fields get fields.
		 * @param array $combine_field get fields.
		 *
		 * @return void
		 */
		public function recursive_output_css( $fields = array(), $combine_field = array() ) {

			if ( ! empty( $fields ) ) {

				foreach ( $fields as $field ) {

					$field_id     = ( ! empty( $field['id'] ) ) ? $field['id'] : '';
					$field_type   = ( ! empty( $field['type'] ) ) ? $field['type'] : '';
					$field_output = ( ! empty( $field['output'] ) ) ? $field['output'] : '';
					$field_check  = ( 'typography' === $field_type || $field_output ) ? true : false;
					$field_class  = 'SP_WPCP_Framework_Field_' . $field_type;

					if ( $field_type && $field_id ) {
						if ( 'accordion' === $field_type ) {
							if ( ! empty( $field['accordions'] ) ) {
								foreach ( $field['accordions'] as $accordion ) {
									$this->recursive_output_css( $accordion['fields'], $field );
								}
							}
						}
						if ( class_exists( $field_class ) ) {

							if ( method_exists( $field_class, 'output' ) || method_exists( $field_class, 'enqueue_google_fonts' ) ) {

								$field_value = '';

								if ( $field_check && ( 'options' === $this->abstract || 'customize' === $this->abstract ) ) {

									if ( ! empty( $combine_field ) ) {

										$field_value = ( isset( $this->options[ $combine_field['id'] ][ $field_id ] ) ) ? $this->options[ $combine_field['id'] ][ $field_id ] : '';

									} else {

										$field_value = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : '';

									}
								} elseif ( $field_check && ( 'metabox' === $this->abstract && is_singular() || 'taxonomy' === $this->abstract && is_archive() ) ) {

									if ( ! empty( $combine_field ) ) {

										$meta_value  = $this->get_meta_value( $combine_field );
										$field_value = ( isset( $meta_value[ $field_id ] ) ) ? $meta_value[ $field_id ] : '';

									} else {

										$meta_value  = $this->get_meta_value( $field );
										$field_value = ( isset( $meta_value ) ) ? $meta_value : '';

									}
								}

								$instance = new $field_class( $field, $field_value, $this->unique, 'wp/enqueue', $this );

								// typography enqueue and embed google web fonts.
								if ( 'typography' === $field_type && $this->args['enqueue_webfont'] && ! empty( $field_value['font-family'] ) ) {

									$method = ( ! empty( $this->args['async_webfont'] ) ) ? 'async' : 'enqueue';

									$instance->enqueue_google_fonts( $method );

								}

								// output css.
								if ( $field_output && $this->args['output_css'] ) {
									SP_WPCP_Framework::$css .= $instance->output();
								}

								unset( $instance );

							}
						}
					}
				}
			}

		}

	}
}
