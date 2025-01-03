<?php
/**
 *
 */
namespace Rishi\Customizer\Settings;

use \Rishi\Customizer\ControlTypes;
use \Rishi\Customizer\Helpers;
use \Rishi\Customizer\Abstracts;

class Typography_Setting extends Abstracts\Customize_Settings {

	protected function add_settings() {
		$defaults = Helpers\Defaults::typography_defaults();
		$this->add_setting( 'rootTypography', self::create_typography_control(
			esc_html__( 'Base Font', 'rishi' ),
			$defaults['body']['family'],
			$defaults['body']['size'],
			$defaults['body']['weight'],
			$defaults['body']['style'],
			$defaults['body']['line_height'],
			$defaults['body']['letter-spacing'],
			$defaults['body']['text-transform'],
			$defaults['body']['text-decoration']
		 ) );
		$this->add_setting( 'h1Typography', self::create_typography_control(
			esc_html__( 'Heading 1 (H1)', 'rishi' ),
			$defaults['heading_one']['family'],
			$defaults['heading_one']['size'],
			$defaults['heading_one']['weight'],
			$defaults['heading_one']['style'],
			$defaults['heading_one']['line_height'],
			$defaults['heading_one']['letter-spacing'],
			$defaults['heading_one']['text-transform'],
			$defaults['heading_one']['text-decoration']
		 ) );
		$this->add_setting( 'h2Typography', self::create_typography_control(
			esc_html__( 'Heading 2 (H2)', 'rishi' ),
			$defaults['heading_two']['family'],
			$defaults['heading_two']['size'],
			$defaults['heading_two']['weight'],
			$defaults['heading_two']['style'],
			$defaults['heading_two']['line_height'],
			$defaults['heading_two']['letter-spacing'],
			$defaults['heading_two']['text-transform'],
			$defaults['heading_two']['text-decoration']
		 ) );
		$this->add_setting( 'h3Typography', self::create_typography_control(
			esc_html__( 'Heading 3 (H3)', 'rishi' ),
			$defaults['heading_three']['family'],
			$defaults['heading_three']['size'],
			$defaults['heading_three']['weight'],
			$defaults['heading_three']['style'],
			$defaults['heading_three']['line_height'],
			$defaults['heading_three']['letter-spacing'],
			$defaults['heading_three']['text-transform'],
			$defaults['heading_three']['text-decoration']
		 ) );
		$this->add_setting( 'h4Typography', self::create_typography_control(
			esc_html__( 'Heading 4 (H4)', 'rishi' ),
			$defaults['heading_four']['family'],
			$defaults['heading_four']['size'],
			$defaults['heading_four']['weight'],
			$defaults['heading_four']['style'],
			$defaults['heading_four']['line_height'],
			$defaults['heading_four']['letter-spacing'],
			$defaults['heading_four']['text-transform'],
			$defaults['heading_four']['text-decoration']
		 ) );
		$this->add_setting( 'h5Typography', self::create_typography_control(
			esc_html__( 'Heading 5 (H5)', 'rishi' ),
			$defaults['heading_five']['family'],
			$defaults['heading_five']['size'],
			$defaults['heading_five']['weight'],
			$defaults['heading_five']['style'],
			$defaults['heading_five']['line_height'],
			$defaults['heading_five']['letter-spacing'],
			$defaults['heading_five']['text-transform'],
			$defaults['heading_five']['text-decoration']
		 ) );
		$this->add_setting( 'h6Typography', self::create_typography_control(
			esc_html__( 'Heading 6 (H6)', 'rishi' ),
			$defaults['heading_six']['family'],
			$defaults['heading_six']['size'],
			$defaults['heading_six']['weight'],
			$defaults['heading_six']['style'],
			$defaults['heading_six']['line_height'],
			$defaults['heading_six']['letter-spacing'],
			$defaults['heading_six']['text-transform'],
			$defaults['heading_six']['text-decoration']
		 ) );
		$this->add_setting( 'font_family_fallback', array(
			'control' => ControlTypes::INPUT_TEXT,
			'value'   => 'Sans-Serif',
			'divider' => 'top:bottom',
			'label'   => __( 'Fallback Font Family', 'rishi' ),
			'help'    => __( 'This font is used if the chosen font isn\'t available.', 'rishi' ),
		) );

		$this->add_setting( 'local_google_fonts', array(
			'label'   => __( 'Load Google Fonts Locally', 'rishi' ),
			'help'    => __( 'This will load Google fonts from your server to speed up your site and make it GDPR compliant.', 'rishi' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value'   => 'no',
			'divider' => 'bottom',

		) );

		$this->add_setting( 'preload_local_fonts', array(
			'conditions' => [
				'local_google_fonts' => 'yes'
			],
			'label'   => __( 'Preload Local Fonts', 'rishi' ),
			'help' => __( 'Preloading Google fonts will speed up your website speed.', 'rishi' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value'   => 'no',
			'divider' => 'bottom',

		) );

		$this->add_setting(
			'flush_google_fonts',
			array(
				'label'       => __( 'Flush Local Fonts Cache', 'rishi' ),
				'help'        => __( 'Click the button to reset the local fonts cache.', 'rishi' ),
				'control'     => ControlTypes::INPUT_BUTTON,
				'size'		  => 'full',
				'input_attrs' => array(
					'value' => __( 'Flush Local Fonts Cache', 'rishi' ),
					'class' => 'button button-primary flush-it',
				),
				'conditions' => [
					'local_google_fonts' => 'yes'
				],
			)
		);
	}

	protected static function create_typography_control( $label, $font, $size, $weight, $style, $line_height, $letter_spacing,  $text_transform, $text_decoration  ) {
		return array(
			'control'     => ControlTypes::TYPOGRAPHY,
			'controlType' => 'Typography',
			'label'       => $label,
			'divider'     => 'top',
			'value'       => Helpers\Defaults::typography_value(
				array(
					'size'        => $size,
					'weight'      => $weight,
					'style'      => $style,
					'line-height' => $line_height,
					'letter-spacing' => $letter_spacing,
					'family'=> $font
				)
			),
			'settings'	=> array(
				'options'	 => self::set_typography_options(
					$font,
					$size,
					$weight,
					$style,
					$line_height,
					$letter_spacing,
					$text_transform,
					$text_decoration
				),
			)
		);
	}

	public static function set_typography_options( $font, $size, $weight, $style, $line_height, $letter_spacing,  $text_transform, $text_decoration ){
		$system_fonts = apply_filters( 'rishi-custom-fonts',array(
			'Default' => __( 'Default', 'rishi' ),
			'System Default' => __( 'System Default', 'rishi' ),
			'Arial' => __( 'Arial', 'rishi' ),
			'Verdana' => __( 'Verdana', 'rishi' ),
			'Trebuchet' => __( 'Trebuchet', 'rishi' ),
			'Georgia' => __( 'Georgia', 'rishi' ),
			'Times New Roman' => __( 'Times New Roman', 'rishi' ),
			'Palatino' => __( 'Palatino', 'rishi' ),
			'Helvetica' => __( 'Helvetica', 'rishi' ),
			'Myriad Pro' => __( 'Myriad Pro', 'rishi' ),
			'Lucida' => __( 'Lucida', 'rishi' ),
			'Gill Sans' => __( 'Gill Sans', 'rishi' ),
			'Impact' => __( 'Impact', 'rishi' ),
			'Serif' => __( 'Serif', 'rishi' ),
			'Monospace' => __( 'Monospace', 'rishi' )
		) );
		$google_fonts = get_transient( 'rishi_google_fonts' );
		$new_google_fonts = [];
		if( is_array( $google_fonts ) ){
			foreach ($google_fonts as $fonts) {
				$font_name = $fonts['name'];
				$new_google_fonts[$font_name] = $font_name;
			}
		}

		// Merge the existing font families with the processed Google Fonts data
		$rishi_fonts = array_merge($system_fonts, $new_google_fonts);

		$options = array(
			'family'	=> array(
				'label'   => __( 'Family', 'rishi' ),
				'control' => ControlTypes::INPUT_SELECT,
				'variant' => 'solid',
				'isSearchable' => true,
				'value'   => $font,
				'design'  => 'inline',
				'divider'    => 'bottom',
				'hasRevertButton'  => false,
				'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
					$rishi_fonts
				),
				'style' => ['minWidth' => '178px']
			),
			'size'    => array(
				'label'      => __( 'Size', 'rishi' ),
				'control'       => ControlTypes::INPUT_SLIDER,
				'value'      => $size,
				'responsive' => true,
				'divider'    => 'bottom',
				'units'      => [
					[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
					[ 'unit' => 'em', 'min' => 0, 'max' => 10 ],
					[ 'unit' => 'rem', 'min' => 0, 'max' => 10 ],
					[ 'unit' => 'vw', 'min' => 0, 'max' => 100 ],
				]
			),
			'line-height'    => array(
				'label'      => __( 'Line Height', 'rishi' ),
				'control'       => ControlTypes::INPUT_SLIDER,
				'value'      => $line_height,
				'responsive' => true,
				'divider'    => 'bottom',
				'units'      => [
					[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
					[ 'unit' => 'em', 'min' => 0, 'max' => 10 ],
					[ 'unit' => 'rem', 'min' => 0, 'max' => 10 ],
				]
			),
			'letter-spacing'    => array(
				'label'      => __( 'Letter Spacing', 'rishi' ),
				'control'       => ControlTypes::INPUT_SLIDER,
				'value'      => $letter_spacing,
				'responsive' => true,
				'divider'    => 'bottom',
				'units'      => [
					[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
					[ 'unit' => 'em', 'min' => 0, 'max' => 10 ],
					[ 'unit' => 'rem', 'min' => 0, 'max' => 10 ],
				]
			),
			'weight'	=> array(
				'label'   => __( 'Font Weight', 'rishi' ),
				'control' => ControlTypes::INPUT_SELECT,
				'variant' => 'solid',
				'value'   => $weight,
				'design'  => 'inline',
				'divider'    => 'bottom',
				'hasRevertButton'  => false,
				'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
					array()
				),
				'style' => ['minWidth' => '150px']
			),
			'style'	=> array(
				'label'   => __( 'Style', 'rishi' ),
				'control' => ControlTypes::INPUT_SELECT,
				'variant' => 'solid',
				'value'   => $style,
				'design'  => 'inline',
				'divider'    => 'bottom',
				'hasRevertButton'  => false,
				'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
					array()
				),
				'style' => ['minWidth' => '150px']
			),
			'text-transform'	=> array(
				'label'   => __( 'Transform', 'rishi' ),
				'control' => ControlTypes::INPUT_SELECT,
				'variant' => 'solid',
				'value'   => $text_transform,
				'design'  => 'inline',
				'divider'    => 'bottom',
				'hasRevertButton'  => false,
				'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
					array(
						'default' => __( 'Default', 'rishi' ),
						'lowercase' => __( 'Lowercase', 'rishi' ),
						'uppercase' => __( 'Uppercase', 'rishi' ),
						'capitalize' => __( 'Capitalize', 'rishi' ),
						'normal' => __( 'Normal', 'rishi' ),
					)
				),
				'style' => ['minWidth' => '150px']
			),
			'text-decoration'	=> array(
				'label'   => __( 'Decoration', 'rishi' ),
				'control' => ControlTypes::ICON_RADIO,
				'value'   => $text_decoration,
				'design'  => 'inline',
				'hasRevertButton'  => false,
				'choices' => array(
					'none' => ['title' => __('None', 'rishi'), 'icon' => 'decoration_normal'],
					'underline' => ['title' => __('Underline', 'rishi'), 'icon' => 'decoration_underline'],
					'line-through' => ['title' => __('Strikeout', 'rishi'), 'icon' => 'decoration_strikeout'],
				),
				'style' => ['width' => '150px']
			),
		);
		return $options;
	}
}
