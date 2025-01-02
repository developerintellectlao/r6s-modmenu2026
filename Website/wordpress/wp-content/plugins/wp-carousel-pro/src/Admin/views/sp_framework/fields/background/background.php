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

use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;

if ( ! class_exists( 'SP_WPCP_Framework_Field_background' ) ) {
	/**
	 *
	 * Field: background
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_background extends SP_WPCP_Framework_Fields {

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
					'background_color'              => true,
					'background_image'              => true,
					'background_position'           => true,
					'background_repeat'             => true,
					'background_attachment'         => true,
					'background_size'               => true,
					'background_origin'             => false,
					'background_clip'               => false,
					'background_blend_mode'         => false,
					'background_gradient'           => false,
					'background_gradient_color'     => true,
					'background_gradient_direction' => true,
					'background_image_preview'      => true,
					'background_auto_attributes'    => false,
					'compact'                       => false,
					'background_image_library'      => 'image',
					'background_image_placeholder'  => esc_html__( 'Not selected', 'wp-carousel-pro' ),

					// Advance preview.
					'preview'                       => false,
					'preview_text'                  => '',
				)
			);

			if ( $args['compact'] ) {
				$args['background_color']           = false;
				$args['background_auto_attributes'] = true;
			}

			$default_value = array(
				'background-color'              => '',
				'background-image'              => '',
				'background-position'           => '',
				'background-repeat'             => '',
				'background-attachment'         => '',
				'background-size'               => '',
				'background-origin'             => '',
				'background-clip'               => '',
				'background-blend-mode'         => '',
				'background-gradient-color'     => '',
				'background-gradient-direction' => '',
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$this->value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="sp_wpcp--background-colors">';

			//
			// Background Color.
			if ( ! empty( $args['background_color'] ) ) {

				echo '<div class="sp_wpcp--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="sp_wpcp--title">' . esc_html__( 'From', 'wp-carousel-pro' ) . '</div>' : '';

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-color',
						'type'    => 'color',
						'default' => $default_value['background-color'],
					),
					$this->value['background-color'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';

			}

			//
			// Background Gradient Color.
			if ( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

				echo '<div class="sp_wpcp--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="sp_wpcp--title">' . esc_html__( 'To', 'wp-carousel-pro' ) . '</div>' : '';

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-gradient-color',
						'type'    => 'color',
						'default' => $default_value['background-gradient-color'],
					),
					$this->value['background-gradient-color'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';

			}

			//
			// Background Gradient Direction.
			if ( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

				echo '<div class="sp_wpcp--color">';

				echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="sp_wpcp---title">' . esc_html__( 'Direction', 'wp-carousel-pro' ) . '</div>' : '';

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-gradient-direction',
						'type'    => 'select',
						'options' => array(
							''          => esc_html__( 'Gradient Direction', 'wp-carousel-pro' ),
							'to bottom' => esc_html__( '&#8659; top to bottom', 'wp-carousel-pro' ),
							'to right'  => esc_html__( '&#8658; left to right', 'wp-carousel-pro' ),
							'135deg'    => esc_html__( '&#8664; corner top to right', 'wp-carousel-pro' ),
							'-135deg'   => esc_html__( '&#8665; corner top to left', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-gradient-direction'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';

			}

			echo '</div>';

			//
			// Background Image.
			if ( ! empty( $args['background_image'] ) ) {

				echo '<div class="sp_wpcp--background-image">';

				SP_WPCP_Framework::field(
					array(
						'id'          => 'background-image',
						'type'        => 'media',
						'class'       => 'sp_wpcp-assign-field-background',
						'library'     => $args['background_image_library'],
						'preview'     => $args['background_image_preview'],
						'placeholder' => $args['background_image_placeholder'],
						'attributes'  => array( 'data-depend-id' => $this->field['id'] ),
					),
					$this->value['background-image'],
					$this->field_name(),
					'field/background'
				);

				echo '</div>';

			}

			$auto_class   = ( ! empty( $args['background_auto_attributes'] ) ) ? ' sp_wpcp--auto-attributes' : '';
			$hidden_class = ( ! empty( $args['background_auto_attributes'] ) && empty( $this->value['background-image']['url'] ) ) ? ' sp_wpcp--attributes-hidden' : '';

			echo '<div class="sp_wpcp--background-attributes' . esc_attr( $auto_class . $hidden_class ) . '">';

			//
			// Background Position.
			if ( ! empty( $args['background_position'] ) ) {

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-position',
						'type'    => 'select',
						'options' => array(
							''              => esc_html__( 'Background Position', 'wp-carousel-pro' ),
							'left top'      => esc_html__( 'Left Top', 'wp-carousel-pro' ),
							'left center'   => esc_html__( 'Left Center', 'wp-carousel-pro' ),
							'left bottom'   => esc_html__( 'Left Bottom', 'wp-carousel-pro' ),
							'center top'    => esc_html__( 'Center Top', 'wp-carousel-pro' ),
							'center center' => esc_html__( 'Center Center', 'wp-carousel-pro' ),
							'center bottom' => esc_html__( 'Center Bottom', 'wp-carousel-pro' ),
							'right top'     => esc_html__( 'Right Top', 'wp-carousel-pro' ),
							'right center'  => esc_html__( 'Right Center', 'wp-carousel-pro' ),
							'right bottom'  => esc_html__( 'Right Bottom', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-position'],
					$this->field_name(),
					'field/background'
				);
			}

			// Background Repeat.
			if ( ! empty( $args['background_repeat'] ) ) {

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-repeat',
						'type'    => 'select',
						'options' => array(
							''          => esc_html__( 'Background Repeat', 'wp-carousel-pro' ),
							'repeat'    => esc_html__( 'Repeat', 'wp-carousel-pro' ),
							'no-repeat' => esc_html__( 'No Repeat', 'wp-carousel-pro' ),
							'repeat-x'  => esc_html__( 'Repeat Horizontally', 'wp-carousel-pro' ),
							'repeat-y'  => esc_html__( 'Repeat Vertically', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-repeat'],
					$this->field_name(),
					'field/background'
				);
			}

			//
			// Background Attachment.
			if ( ! empty( $args['background_attachment'] ) ) {

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-attachment',
						'type'    => 'select',
						'options' => array(
							''       => esc_html__( 'Background Attachment', 'wp-carousel-pro' ),
							'scroll' => esc_html__( 'Scroll', 'wp-carousel-pro' ),
							'fixed'  => esc_html__( 'Fixed', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-attachment'],
					$this->field_name(),
					'field/background'
				);
			}

			// Background Size.
			if ( ! empty( $args['background_size'] ) ) {

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-size',
						'type'    => 'select',
						'options' => array(
							''        => esc_html__( 'Background Size', 'wp-carousel-pro' ),
							'cover'   => esc_html__( 'Cover', 'wp-carousel-pro' ),
							'contain' => esc_html__( 'Contain', 'wp-carousel-pro' ),
							'auto'    => esc_html__( 'Auto', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-size'],
					$this->field_name(),
					'field/background'
				);
			}

			//
			// Background Origin.
			if ( ! empty( $args['background_origin'] ) ) {

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-origin',
						'type'    => 'select',
						'options' => array(
							''            => esc_html__( 'Background Origin', 'wp-carousel-pro' ),
							'padding-box' => esc_html__( 'Padding Box', 'wp-carousel-pro' ),
							'border-box'  => esc_html__( 'Border Box', 'wp-carousel-pro' ),
							'content-box' => esc_html__( 'Content Box', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-origin'],
					$this->field_name(),
					'field/background'
				);
			}

			//
			// Background Clip.
			if ( ! empty( $args['background_clip'] ) ) {

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-clip',
						'type'    => 'select',
						'options' => array(
							''            => esc_html__( 'Background Clip', 'wp-carousel-pro' ),
							'border-box'  => esc_html__( 'Border Box', 'wp-carousel-pro' ),
							'padding-box' => esc_html__( 'Padding Box', 'wp-carousel-pro' ),
							'content-box' => esc_html__( 'Content Box', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-clip'],
					$this->field_name(),
					'field/background'
				);
			}

			//
			// Background Blend Mode.
			if ( ! empty( $args['background_blend_mode'] ) ) {

				SP_WPCP_Framework::field(
					array(
						'id'      => 'background-blend-mode',
						'type'    => 'select',
						'options' => array(
							''            => esc_html__( 'Background Blend Mode', 'wp-carousel-pro' ),
							'normal'      => esc_html__( 'Normal', 'wp-carousel-pro' ),
							'multiply'    => esc_html__( 'Multiply', 'wp-carousel-pro' ),
							'screen'      => esc_html__( 'Screen', 'wp-carousel-pro' ),
							'overlay'     => esc_html__( 'Overlay', 'wp-carousel-pro' ),
							'darken'      => esc_html__( 'Darken', 'wp-carousel-pro' ),
							'lighten'     => esc_html__( 'Lighten', 'wp-carousel-pro' ),
							'color-dodge' => esc_html__( 'Color Dodge', 'wp-carousel-pro' ),
							'saturation'  => esc_html__( 'Saturation', 'wp-carousel-pro' ),
							'color'       => esc_html__( 'Color', 'wp-carousel-pro' ),
							'luminosity'  => esc_html__( 'Luminosity', 'wp-carousel-pro' ),
						),
					),
					$this->value['background-blend-mode'],
					$this->field_name(),
					'field/background'
				);
			}

			echo '</div>';
			// Preview.
			$always_preview = ( 'always' !== $args['preview'] ) ? ' hidden' : '';

			if ( ! empty( $args['preview'] ) ) {
				echo '<div class="sp_wpcp--block sp_wpcp--block-preview' . esc_attr( $always_preview ) . '">';
				echo '<div class="sp_wpcp--toggle fa fa-toggle-off"></div>';
				echo '<div class="sp_wpcp--preview">' . esc_attr( $args['preview_text'] ) . '</div>';
				echo '</div>';
			}
			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Output
		 *
		 * @return statement
		 */
		public function output() {

			$output    = '';
			$bg_image  = array();
			$important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
			$element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

			// Background image and gradient.
			$background_color        = ( ! empty( $this->value['background-color'] ) ) ? $this->value['background-color'] : '';
			$background_gd_color     = ( ! empty( $this->value['background-gradient-color'] ) ) ? $this->value['background-gradient-color'] : '';
			$background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
			$background_image        = ( ! empty( $this->value['background-image']['url'] ) ) ? $this->value['background-image']['url'] : '';

			if ( $background_color && $background_gd_color ) {
				$gd_direction = ( $background_gd_direction ) ? $background_gd_direction . ',' : '';
				$bg_image[]   = 'linear-gradient(' . $gd_direction . $background_color . ',' . $background_gd_color . ')';
				unset( $this->value['background-color'] );
			}

			if ( $background_image ) {
				$bg_image[] = 'url(' . $background_image . ')';
			}

			if ( ! empty( $bg_image ) ) {
				$output .= 'background-image:' . implode( ',', $bg_image ) . $important . ';';
			}

			// Common background properties.
			$properties = array( 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' );

			foreach ( $properties as $property ) {
				$property = 'background-' . $property;
				if ( ! empty( $this->value[ $property ] ) ) {
					$output .= $property . ':' . $this->value[ $property ] . $important . ';';
				}
			}

			if ( $output ) {
				$output = $element . '{' . $output . '}';
			}

			$this->parent->output_css .= $output;

			return $output;

		}
	}
}
