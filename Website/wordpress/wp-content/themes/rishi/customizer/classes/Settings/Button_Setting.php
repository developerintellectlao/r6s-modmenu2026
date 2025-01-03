<?php

namespace Rishi\Customizer\Settings;
use \Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts\Customize_Settings;

class Button_Setting extends Customize_Settings {

	protected function add_settings() {
		$colordefaults  = \Rishi\Customizer\Helpers\Defaults::color_value();
		$buttondefaults = \Rishi\Customizer\Helpers\Defaults::button_defaults();

		$this->add_setting('button_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'botton_roundness'                 => array(
					'label' => __('Button Roundness', 'rishi'),
					'control' => ControlTypes::INPUT_SLIDER,
					'value' => [
						'desktop' => $buttondefaults['botton_roundness']['desktop'],
						'tablet'  => $buttondefaults['botton_roundness']['tablet'],
						'mobile'  => $buttondefaults['botton_roundness']['mobile'],
					],
					'units' => \Rishi\Customizer\Helpers\Basic::get_units(
						array(
							array(
								'unit' => 'px',
								'min' => 0,
								'max' => 100,
							),
							array(
								'unit' => 'em',
								'min' => 5,
								'max' => 10,
							),
						)
					),
					'responsive' => true,
					'tab' => 'general',
				),
				'button_padding'                         => array(
					'label' => __('Button Padding', 'rishi'),
					'control' => ControlTypes::INPUT_SPACING,
					'divider' => 'top:bottom',
					'value' => $buttondefaults['button_padding'],
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
					'responsive' => true,
					'tab' => 'general',
				),
			),
		));

		$this->add_setting('button_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'button_Typo' => rishi_typography_control_option([
					'control' => ControlTypes::TYPOGRAPHY,
					'label' => __('Font', 'rishi'),
					'value' => \Rishi\Customizer\Helpers\Defaults::typography_value([
						'size'            => array(
							'desktop' => '18px',
							'tablet'  => '18px',
							'mobile'  => '18px',
						),
						'line-height'            => array(
							'desktop' => '1.2em',
							'tablet'  => '1.2em',
							'mobile'  => '1.2em',
						),
					])
				]),
				'btn_text_color'    => array(
					'label' => __('Text Color', 'rishi'),
					'control' => ControlTypes::COLOR_PICKER,
					'colorPalette'	  => true,
					'design' => 'inline',
					'divider' => 'top',
					'value' => array(
						'default' => array(
							'color' => $colordefaults['btn_text_color'],
						),
					),
					'pickers' => array(
						array(
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						),
					),
				),
				'btn_text_hover_color'    => array(
					'label' => __('Text Hover Color', 'rishi'),
					'control' => ControlTypes::COLOR_PICKER,
					'design' => 'inline',
					'colorPalette'	  => true,
					'divider' => 'top',
					'value' => array(
						'default' => array(
							'color' => $colordefaults['btn_text_hover_color'],
						),
					),
					'pickers' => array(
						array(
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						),
					),
				),
				'btn_bg_color'    => array(
					'label' => __('Background Color', 'rishi'),
					'control' => ControlTypes::COLOR_PICKER,
					'design' => 'inline',
					'colorPalette'	  => true,
					'divider' => 'top',
					'value' => array(
						'default' => array(
							'color' => $colordefaults['btn_bg_color'],
						),
					),
					'pickers' => array(
						array(
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						),
					),
				),
				'btn_bg_hover_color'    => array(
					'label' => __('Background Hover Color', 'rishi'),
					'control' => ControlTypes::COLOR_PICKER,
					'design' => 'inline',
					'colorPalette'	  => true,
					'divider' => 'top',
					'value' => array(
						'default' => array(
							'color' => $colordefaults['btn_bg_hover_color'],
						),
					),
					'pickers' => array(
						array(
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						),
					),
				),
				'btn_border'    => array(
					'label'     => __( 'Border', 'rishi' ),
					'control'   => ControlTypes::BORDER,
					'design'    => 'inline',
					'divider'   => 'top:bottom',
					'value'     => array(
						'width' => 1,
						'style' => 'solid',
						'color' => array(
							'color' => $colordefaults['btn_border_color'],
							'hover' => $colordefaults['btn_border_hover_color'],
						),
					),
				),
			),
		));
	}

}
