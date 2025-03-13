<?php
/**
 * Framework upload field.
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

if ( ! class_exists( 'SP_WPCP_Framework_Field_upload' ) ) {
	/**
	 *
	 * Field: upload
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_upload extends SP_WPCP_Framework_Fields {

		/**
		 * Field class constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 * @return void
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
					'library'        => array(),
					'preview'        => false,
					'preview_width'  => '',
					'preview_height' => '',
					'button_title'   => esc_html__( 'Upload', 'wp-carousel-pro' ),
					'remove_title'   => esc_html__( 'Remove', 'wp-carousel-pro' ),
				)
			);

			echo wp_kses_post( $this->field_before() );

			$library = ( is_array( $args['library'] ) ) ? $args['library'] : array_filter( (array) $args['library'] );
			$library = ( ! empty( $library ) ) ? implode( ',', $library ) : '';
			$hidden  = ( empty( $this->value ) ) ? ' hidden' : '';

			if ( ! empty( $args['preview'] ) ) {

				$preview_type   = ( ! empty( $this->value ) ) ? strtolower( substr( strrchr( $this->value, '.' ), 1 ) ) : '';
				$preview_src    = ( ! empty( $preview_type ) && in_array( $preview_type, array( 'jpg', 'jpeg', 'gif', 'png', 'svg', 'webp' ) ) ) ? $this->value : '';
				$preview_width  = ( ! empty( $args['preview_width'] ) ) ? 'max-width:' . esc_attr( $args['preview_width'] ) . 'px;' : '';
				$preview_height = ( ! empty( $args['preview_height'] ) ) ? 'max-height:' . esc_attr( $args['preview_height'] ) . 'px;' : '';
				$preview_style  = ( ! empty( $preview_width ) || ! empty( $preview_height ) ) ? ' style="' . esc_attr( $preview_width . $preview_height ) . '"' : '';// phpcs:ignore
				$preview_hidden = ( empty( $preview_src ) ) ? ' hidden' : '';

				echo '<div class="sp_wpcp--preview' . esc_attr( $preview_hidden ) . '">';
				echo '<div class="sp_wpcp-image-preview"' . $preview_style . '>';// phpcs:ignore
				echo '<i class="sp_wpcp--remove fa fa-times"></i><span><img src="' . esc_url( $preview_src ) . '" class="sp_wpcp--src" /></span>';
				echo '</div>';
				echo '</div>';

			}

			echo '<div class="sp_wpcp--wrap">';
			echo '<input class="hidden" type="text" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '"' . $this->field_attributes() . '/>';// phpcs:ignore
			echo '<a href="#" class="button button-primary sp_wpcp--button" data-library="' . esc_attr( $library ) . '">' . esc_html( $args['button_title'] ) . '</a>';
			echo '<a href="#" class="button button-secondary sp_wpcp-warning-primary sp_wpcp--remove' . esc_attr( $hidden ) . '">' . esc_html( $args['remove_title'] ) . '</a>';
			echo '</div>';

			echo wp_kses_post( $this->field_after() );

		}
	}
}
