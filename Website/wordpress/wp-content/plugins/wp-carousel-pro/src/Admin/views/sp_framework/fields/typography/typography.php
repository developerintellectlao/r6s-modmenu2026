<?php
/**
 * Framework typography field.
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

if ( ! class_exists( 'SP_WPCP_Framework_Field_typography' ) ) {
	/**
	 *
	 * Field: typography
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework_Field_typography extends SP_WPCP_Framework_Fields {

		/**
		 * Chosen
		 *
		 * @var bool
		 */
		public $chosen = false;

		/**
		 * Value
		 *
		 * @var array
		 */
		public $value = array();

		/**
		 * Create fields.
		 *
		 * @param  mixed $field field.
		 * @param  mixed $value value.
		 * @param  mixed $unique unique id.
		 * @param  mixed $where where to add.
		 * @param  mixed $parent parent.
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
			echo wp_kses_post( $this->field_before() );

			$args = wp_parse_args(
				$this->field,
				array(
					'font_family'        => true,
					'font_weight'        => true,
					'font_style'         => true,
					'font_size'          => true,
					'line_height'        => true,
					'letter_spacing'     => true,
					'text_align'         => true,
					'text_transform'     => true,
					'color'              => true,
					'hover_color'        => false,
					'chosen'             => true,
					'preview'            => true,
					'subset'             => false,
					'multi_subset'       => false,
					'extra_styles'       => false,
					'backup_font_family' => false,
					'font_variant'       => false,
					'word_spacing'       => false,
					'text_decoration'    => false,
					'custom_style'       => false,
					'compact'            => false,
					'exclude'            => '',
					'margin_top'         => '',
					'margin_bottom'      => '',
					'unit'               => 'px',
					'line_height_unit'   => '',
					'preview_text'       => 'The quick brown fox jumps over the lazy dog',
				)
			);

			if ( $args['compact'] ) {
				$args['text_transform'] = false;
				$args['text_align']     = false;
				$args['font_size']      = false;
				$args['line_height']    = false;
				$args['letter_spacing'] = false;
				$args['preview']        = false;
				$args['color']          = false;
			}

			$default_value = array(
				'font-family'        => '',
				'font-weight'        => '',
				'font-style'         => '',
				'font-variant'       => '',
				'font-size'          => '',
				'line-height'        => '',
				'letter-spacing'     => '',
				'word-spacing'       => '',
				'text-align'         => '',
				'text-transform'     => '',
				'text-decoration'    => '',
				'backup-font-family' => '',
				'color'              => '',
				'hover_color'        => '',
				'custom-style'       => '',
				'type'               => '',
				'subset'             => '',
				'extra-styles'       => array(),
				'margin-top'         => '',
				'margin-bottom'      => '',
			);

			$default_value    = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;
			$this->value      = wp_parse_args( $this->value, $default_value );
			$this->chosen     = $args['chosen'];
			$chosen_class     = ( $this->chosen ) ? ' sp_wpcp--chosen' : '';
			$line_height_unit = ( ! empty( $args['line_height_unit'] ) ) ? $args['line_height_unit'] : $args['unit'];
			$section_margin   = isset( $this->field['margin'] ) ? $this->field['margin'] : false;

			echo '<div class="sp_wpcp--typography' . esc_attr( $chosen_class ) . '" data-depend-id="' . esc_attr( $this->field['id'] ) . '" data-unit="' . esc_attr( $args['unit'] ) . '" data-line-height-unit="' . esc_attr( $line_height_unit ) . '" data-exclude="' . esc_attr( $args['exclude'] ) . '">';

			echo '<div class="sp_wpcp--blocks sp_wpcp--blocks-selects">';

			//
			// Font Family.
			if ( ! empty( $args['font_family'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Font Family', 'wp-carousel-pro' ) . '</div>';
				// phpcs:ignore
				echo $this->create_select( array( $this->value['font-family'] => $this->value['font-family'] ), 'font-family', esc_html__( 'Select a font', 'wp-carousel-pro' ) );
				echo '</div>';
			}

			// Backup Font Family.
			if ( ! empty( $args['backup_font_family'] ) ) {
				echo '<div class="sp_wpcp--block sp_wpcp--block-backup-font-family hidden">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Backup Font Family', 'wp-carousel-pro' ) . '</div>';
				// phpcs:ignore
				echo $this->create_select(
					apply_filters(
						'sp_wpcp_field_typography_backup_font_family',
						array(
							'Arial, Helvetica, sans-serif',
							"'Arial Black', Gadget, sans-serif",
							"'Comic Sans MS', cursive, sans-serif",
							'Impact, Charcoal, sans-serif',
							"'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
							'Tahoma, Geneva, sans-serif',
							"'Trebuchet MS', Helvetica, sans-serif'",
							'Verdana, Geneva, sans-serif',
							"'Courier New', Courier, monospace",
							"'Lucida Console', Monaco, monospace",
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
					'backup-font-family',
					esc_html__( 'Default', 'wp-carousel-pro' )
				);
				echo '</div>';
			}

			// Font Style and Extra Style Select.
			if ( ! empty( $args['font_weight'] ) || ! empty( $args['font_style'] ) ) {

				// Font Style Select.
				echo '<div class="sp_wpcp--block sp_wpcp--block-font-style hidden">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Font Style', 'wp-carousel-pro' ) . '</div>';
				echo '<select class="sp_wpcp--font-style-select" data-placeholder="Default">';
				echo '<option value="">' . ( ! $this->chosen ? esc_html__( 'Default', 'wp-carousel-pro' ) : '' ) . '</option>';// phpcs:ignore
				if ( ! empty( $this->value['font-weight'] ) || ! empty( $this->value['font-style'] ) ) {
					echo '<option value="' . esc_attr( strtolower( $this->value['font-weight'] . $this->value['font-style'] ) ) . '" selected></option>';
				}
				echo '</select>';
				echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[font-weight]' ) ) . '" class="sp_wpcp--font-weight" value="' . esc_attr( $this->value['font-weight'] ) . '" />';
				echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[font-style]' ) ) . '" class="sp_wpcp--font-style" value="' . esc_attr( $this->value['font-style'] ) . '" />';

				//
				// Extra Font Style Select.
				if ( ! empty( $args['extra_styles'] ) ) {
					echo '<div class="sp_wpcp--block-extra-styles hidden">';
					echo ( ! $this->chosen ) ? '<div class="sp_wpcp--title">' . esc_html__( 'Load Extra Styles', 'wp-carousel-pro' ) . '</div>' : '';
					$placeholder = ( $this->chosen ) ? esc_html__( 'Load Extra Styles', 'wp-carousel-pro' ) : esc_html__( 'Default', 'wp-carousel-pro' );
					echo $this->create_select( $this->value['extra-styles'], 'extra-styles', $placeholder, true );// phpcs:ignore
					echo '</div>';
				}

				echo '</div>';

			}

			//
			// Subset.
			if ( ! empty( $args['subset'] ) ) {
				echo '<div class="sp_wpcp--block sp_wpcp--block-subset hidden">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Subset', 'wp-carousel-pro' ) . '</div>';
				$subset = ( is_array( $this->value['subset'] ) ) ? $this->value['subset'] : array_filter( (array) $this->value['subset'] );
				// phpcs:ignore
				echo $this->create_select( $subset, 'subset', esc_html__( 'Default', 'wp-carousel-pro' ), $args['multi_subset'] );
				echo '</div>';
			}

			//
			// Text Align.
			if ( ! empty( $args['text_align'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Text Align', 'wp-carousel-pro' ) . '</div>';
				// phpcs:ignore
				echo $this->create_select(
					array(
						'inherit' => esc_html__( 'Inherit', 'wp-carousel-pro' ),
						'left'    => esc_html__( 'Left', 'wp-carousel-pro' ),
						'center'  => esc_html__( 'Center', 'wp-carousel-pro' ),
						'right'   => esc_html__( 'Right', 'wp-carousel-pro' ),
						'justify' => esc_html__( 'Justify', 'wp-carousel-pro' ),
						'initial' => esc_html__( 'Initial', 'wp-carousel-pro' ),
					),
					'text-align',
					esc_html__( 'Default', 'wp-carousel-pro' )
				);
				echo '</div>';
			}

			//
			// Font Variant.
			if ( ! empty( $args['font_variant'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Font Variant', 'wp-carousel-pro' ) . '</div>';
				// phpcs:ignore
				echo $this->create_select(
					array(
						'normal'         => esc_html__( 'Normal', 'wp-carousel-pro' ),
						'small-caps'     => esc_html__( 'Small Caps', 'wp-carousel-pro' ),
						'all-small-caps' => esc_html__( 'All Small Caps', 'wp-carousel-pro' ),
					),
					'font-variant',
					esc_html__( 'Default', 'wp-carousel-pro' )
				);
				echo '</div>';
			}

			//
			// Text Transform.
			if ( ! empty( $args['text_transform'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Text Transform', 'wp-carousel-pro' ) . '</div>';
				// phpcs:ignore
				echo $this->create_select(
					array(
						'none'       => esc_html__( 'None', 'wp-carousel-pro' ),
						'capitalize' => esc_html__( 'Capitalize', 'wp-carousel-pro' ),
						'uppercase'  => esc_html__( 'Uppercase', 'wp-carousel-pro' ),
						'lowercase'  => esc_html__( 'Lowercase', 'wp-carousel-pro' ),
					),
					'text-transform',
					esc_html__( 'Default', 'wp-carousel-pro' )
				);
				echo '</div>';
			}

			//
			// Text Decoration.
			if ( ! empty( $args['text_decoration'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Text Decoration', 'wp-carousel-pro' ) . '</div>';
				// phpcs:ignore
				echo $this->create_select(
					array(
						'none'               => esc_html__( 'None', 'wp-carousel-pro' ),
						'underline'          => esc_html__( 'Solid', 'wp-carousel-pro' ),
						'underline double'   => esc_html__( 'Double', 'wp-carousel-pro' ),
						'underline dotted'   => esc_html__( 'Dotted', 'wp-carousel-pro' ),
						'underline dashed'   => esc_html__( 'Dashed', 'wp-carousel-pro' ),
						'underline wavy'     => esc_html__( 'Wavy', 'wp-carousel-pro' ),
						'underline overline' => esc_html__( 'Overline', 'wp-carousel-pro' ),
						'line-through'       => esc_html__( 'Line-through', 'wp-carousel-pro' ),
					),
					'text-decoration',
					esc_html__( 'Default', 'wp-carousel-pro' )
				);
				echo '</div>';
			}

			echo '</div>';

			echo '<div class="sp_wpcp--blocks sp_wpcp--blocks-inputs">';

			//
			// Font Size.
			if ( ! empty( $args['font_size'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Font Size', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[font-size]' ) ) . '" class="sp_wpcp--font-size sp_wpcp--input sp_wpcp-input-number" value="' . esc_attr( $this->value['font-size'] ) . '" step="any" />';
				echo '<span class="sp_wpcp--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Line Height.
			if ( ! empty( $args['line_height'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Line Height', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[line-height]' ) ) . '" class="sp_wpcp--line-height sp_wpcp--input sp_wpcp-input-number" value="' . esc_attr( $this->value['line-height'] ) . '" step="any" />';
				echo '<span class="sp_wpcp--unit">' . esc_attr( $line_height_unit ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Letter Spacing.
			if ( ! empty( $args['letter_spacing'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Letter Spacing', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[letter-spacing]' ) ) . '" class="sp_wpcp--letter-spacing sp_wpcp--input sp_wpcp-input-number" value="' . esc_attr( $this->value['letter-spacing'] ) . '" step="any" />';
				echo '<span class="sp_wpcp--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Word Spacing.
			if ( ! empty( $args['word_spacing'] ) ) {
				echo '<div class="sp_wpcp--block">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Word Spacing', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp--input-wrap">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[word-spacing]' ) ) . '" class="sp_wpcp--word-spacing sp_wpcp--input sp_wpcp-input-number" value="' . esc_attr( $this->value['word-spacing'] ) . '" step="any" />';
				echo '<span class="sp_wpcp--unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			echo '</div>';

			//
			// Font Color.
			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="sp_wpcp--block sp_wpcp--block-font-color">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Font Color', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" class="sp_wpcp-color sp_wpcp--color" value="' . esc_attr( $this->value['color'] ) . '"' . $default_color_attr . ' />';// phpcs:ignore
				echo '</div>';
				echo '</div>';
			}

			// margin top.
			if ( $section_margin ) {
				echo '<div class="sp_wpcp--block sp_wpcp--margin-top">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Margin Top', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp--input-wrap">';
				echo '<div class="sp_wpcp--margin-icon"><i class="fa fa-long-arrow-up"></i></div>';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[margin-top]' ) ) . '" class="sp_wpcp--margin-top sp_wpcp--input sp_wpcp-input-number" value="' . esc_attr( $this->value['margin-top'] ) . '" step="any" />';
				echo '<span class="sp_wpcp--unit sp_wpcp--margin-unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			// Margin Bottom.
			if ( $section_margin ) {
				echo '<div class="sp_wpcp--block sp_wpcp--margin-bottom">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Margin Bottom', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp--input-wrap">';
				echo '<div class="sp_wpcp--margin-icon"><i class="fa fa-long-arrow-down"></i></div>';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[margin-bottom]' ) ) . '" class="sp_wpcp--margin-bottom sp_wpcp--input sp_wpcp-input-number" value="' . esc_attr( $this->value['margin-bottom'] ) . '" step="any" />';
				echo '<span class="sp_wpcp--unit sp_wpcp--margin-unit">' . esc_attr( $args['unit'] ) . '</span>';
				echo '</div>';
				echo '</div>';
			}

			//
			// Hover Color.
			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="sp_wpcp--block sp_wpcp--block-font-color">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Hover Color', 'wp-carousel-pro' ) . '</div>';
				echo '<div class="sp_wpcp-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" class="sp_wpcp-color sp_wpcp--color" value="' . esc_attr( $this->value['hover_color'] ) . '"' . $default_color_attr . ' />';// phpcs:ignore
				echo '</div>';
				echo '</div>';
			}
			//
			// Custom style.
			if ( ! empty( $args['custom_style'] ) ) {
				echo '<div class="sp_wpcp--block sp_wpcp--block-custom-style">';
				echo '<div class="sp_wpcp--title">' . esc_html__( 'Custom Style', 'wp-carousel-pro' ) . '</div>';
				echo '<textarea name="' . esc_attr( $this->field_name( '[custom-style]' ) ) . '" class="sp_wpcp--custom-style">' . esc_attr( $this->value['custom-style'] ) . '</textarea>';
				echo '</div>';
			}

			//
			// Preview.
			$always_preview = ( 'always' !== $args['preview'] ) ? ' hidden' : '';

			if ( ! empty( $args['preview'] ) ) {
				echo '<div class="sp_wpcp--block sp_wpcp--block-preview' . esc_attr( $always_preview ) . '">';
				echo '<div class="sp_wpcp--toggle fa fa-toggle-off"></div>';
				echo '<div class="sp_wpcp--preview">' . esc_attr( $args['preview_text'] ) . '</div>';
				echo '</div>';
			}

			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[type]' ) ) . '" class="sp_wpcp--type" value="' . esc_attr( $this->value['type'] ) . '" />';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name( '[unit]' ) ) . '" class="sp_wpcp--unit-save" value="' . esc_attr( $args['unit'] ) . '" />';

			echo '</div>';

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Create_select
		 *
		 * @param  mixed $options options.
		 * @param  mixed $name name.
		 * @param  mixed $placeholder placeholder.
		 * @param  mixed $is_multiple multiple check.
		 * @return statement
		 */
		public function create_select( $options, $name, $placeholder = '', $is_multiple = false ) {

			$multiple_name = ( $is_multiple ) ? '[]' : '';
			$multiple_attr = ( $is_multiple ) ? ' multiple data-multiple="true"' : '';
			$chosen_rtl    = ( $this->chosen && is_rtl() ) ? ' chosen-rtl' : '';

			$output  = '<select name="' . esc_attr( $this->field_name( '[' . $name . ']' . $multiple_name ) ) . '" class="sp_wpcp--' . esc_attr( $name ) . esc_attr( $chosen_rtl ) . '" data-placeholder="' . esc_attr( $placeholder ) . '"' . $multiple_attr . '>';
			$output .= ( ! empty( $placeholder ) ) ? '<option value="">' . esc_attr( ( ! $this->chosen ) ? $placeholder : '' ) . '</option>' : '';

			if ( ! empty( $options ) ) {
				foreach ( $options as $option_key => $option_value ) {
					if ( $is_multiple ) {
						$selected = ( in_array( $option_value, $this->value[ $name ] ) ) ? ' selected' : '';
						$output  .= '<option value="' . esc_attr( $option_value ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $option_value ) . '</option>';
					} else {
						$option_key = ( is_numeric( $option_key ) ) ? $option_value : $option_key;
						$selected   = ( $option_key === $this->value[ $name ] ) ? ' selected' : '';
						$output    .= '<option value="' . esc_attr( $option_key ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $option_value ) . '</option>';
					}
				}
			}

			$output .= '</select>';

			return $output;
		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {

			if ( ! wp_script_is( 'sp_wpcp-webfontloader' ) ) {

				SP_WPCP_Framework::include_plugin_file( 'fields/typography/google-fonts.php' );

				wp_enqueue_script( 'sp_wpcp-webfontloader', 'https://cdn.jsdelivr.net/npm/webfontloader@1.6.28/webfontloader.min.js', array( 'sp_wpcp' ), '1.6.28', true );

				$webfonts = array();

				$customwebfonts = apply_filters( 'sp_wpcp_field_typography_customwebfonts', array() );

				if ( ! empty( $customwebfonts ) ) {
					$webfonts['custom'] = array(
						'label' => esc_html__( 'Custom Web Fonts', 'wp-carousel-pro' ),
						'fonts' => $customwebfonts,
					);
				}

				$webfonts['safe'] = array(
					'label' => esc_html__( 'Safe Web Fonts', 'wp-carousel-pro' ),
					'fonts' => apply_filters(
						'sp_wpcp_field_typography_safewebfonts',
						array(
							'Arial',
							'Arial Black',
							'Helvetica',
							'Times New Roman',
							'Courier New',
							'Tahoma',
							'Verdana',
							'Impact',
							'Trebuchet MS',
							'Comic Sans MS',
							'Lucida Console',
							'Lucida Sans Unicode',
							'Georgia, serif',
							'Palatino Linotype',
						)
					),
				);

				$webfonts['google'] = array(
					'label' => esc_html__( 'Google Web Fonts', 'wp-carousel-pro' ),
					'fonts' => apply_filters(
						'sp_wpcp_field_typography_googlewebfonts',
						sp_wpcp_get_google_fonts()
					),
				);

				$defaultstyles = apply_filters( 'sp_wpcp_field_typography_defaultstyles', array( 'normal', 'italic', '700', '700italic' ) );

				$googlestyles = apply_filters(
					'sp_wpcp_field_typography_googlestyles',
					array(
						'100'       => 'Thin 100',
						'100italic' => 'Thin 100 Italic',
						'200'       => 'Extra-Light 200',
						'200italic' => 'Extra-Light 200 Italic',
						'300'       => 'Light 300',
						'300italic' => 'Light 300 Italic',
						'normal'    => 'Normal 400',
						'italic'    => 'Normal 400 Italic',
						'500'       => 'Medium 500',
						'500italic' => 'Medium 500 Italic',
						'600'       => 'Semi-Bold 600',
						'600italic' => 'Semi-Bold 600 Italic',
						'700'       => 'Bold 700',
						'700italic' => 'Bold 700 Italic',
						'800'       => 'Extra-Bold 800',
						'800italic' => 'Extra-Bold 800 Italic',
						'900'       => 'Black 900',
						'900italic' => 'Black 900 Italic',
					)
				);

				$webfonts = apply_filters( 'sp_wpcp_field_typography_webfonts', $webfonts );

				wp_localize_script(
					'sp_wpcp',
					'sp_wpcp_typography_json',
					array(
						'webfonts'      => $webfonts,
						'defaultstyles' => $defaultstyles,
						'googlestyles'  => $googlestyles,
					)
				);

			}
		}

		/**
		 * Enqueue_google_fonts
		 *
		 * @param  mixed $method methods.
		 * @return statement
		 */
		public function enqueue_google_fonts( $method = 'enqueue' ) {

			$is_google = false;

			if ( ! empty( $this->value['type'] ) ) {
				$is_google = ( 'google' === $this->value['type'] ) ? true : false;
			} else {
				SP_WPCP_Framework::include_plugin_file( 'fields/typography/google-fonts.php' );
				$is_google = ( array_key_exists( $this->value['font-family'], sp_wpcp_get_google_fonts() ) ) ? true : false;
			}

			if ( $is_google ) {

				// set style.
				$font_family = ( ! empty( $this->value['font-family'] ) ) ? $this->value['font-family'] : '';
				$font_weight = ( ! empty( $this->value['font-weight'] ) ) ? $this->value['font-weight'] : '';
				$font_style  = ( ! empty( $this->value['font-style'] ) ) ? $this->value['font-style'] : '';

				if ( $font_weight || $font_style ) {
					$style = $font_weight . $font_style;
					if ( ! empty( $style ) ) {
						$style = ( 'normal' === $style ) ? '400' : $style;
						SP_WPCP_Framework::$webfonts[ $method ][ $font_family ][ $style ] = $style;
					}
				} else {
					SP_WPCP_Framework::$webfonts[ $method ][ $font_family ] = array();
				}

				// set extra styles.
				if ( ! empty( $this->value['extra-styles'] ) ) {
					foreach ( $this->value['extra-styles'] as $extra_style ) {
						if ( ! empty( $extra_style ) ) {
							$extra_style = ( 'normal' === $extra_style ) ? '400' : $extra_style;
							SP_WPCP_Framework::$webfonts[ $method ][ $font_family ][ $extra_style ] = $extra_style;
						}
					}
				}
				// set subsets.
				if ( ! empty( $this->value['subset'] ) ) {
					$this->value['subset'] = ( is_array( $this->value['subset'] ) ) ? $this->value['subset'] : array_filter( (array) $this->value['subset'] );
					foreach ( $this->value['subset'] as $subset ) {
						if ( ! empty( $subset ) ) {
							SP_WPCP_Framework::$subsets[ $subset ] = $subset;
						}
					}
				}

				return true;

			}

			return false;
		}
	}
}
