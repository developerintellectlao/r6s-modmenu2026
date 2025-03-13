<?php

namespace Rishi\Customizer\Settings;
use \Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts\Customize_Settings;

class Scroll_To_Top_Setting extends Customize_Settings {

	protected function add_settings() {
		$defaults       = self::get_layout_default_value();
		$colordefaults  = \Rishi\Customizer\Helpers\Defaults::color_value();
		$this->add_setting('to_top_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'ed_scroll_to_top'                 => array(
					'label' => __('Show Scroll To Top', 'rishi'),
					'control' => ControlTypes::INPUT_SWITCH,
					'value' => $defaults['ed_scroll_to_top'],
					'divider' => 'bottom',
				),
				'top_button_type'                         => array(
					'label' => __('Icon Selector', 'rishi'),
					'control' => ControlTypes::IMAGE_PICKER,
					'value' => 'type-1',
					'attr' => array(
						'data-type' => 'background',
						'data-usage' => 'totop',
						'data-columns' => '4',
					),
					'choices' => array(

						'type-1' => array(
							'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name('top-1'),
							'title' => __('Type 1', 'rishi'),
						),

						'type-2' => array(
							'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name('top-2'),
							'title' => __('Type 2', 'rishi'),
						),

						'type-3' => array(
							'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name('top-3'),
							'title' => __('Type 3', 'rishi'),
						),
						'type-4' => array(
							'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name('top-4'),
							'title' => __('Type 4', 'rishi'),
						),
					),
					'conditions' => ['ed_scroll_to_top' => 'yes'],
				),
				'top_button_shape'             => array(
					'label' => __('Button Shape', 'rishi'),
					'control' => ControlTypes::INPUT_RADIO,
					'value' => 'square',
					'design' => 'block',
					'divider' => 'top',
					'choices' => array(
						'square' => __('Square', 'rishi'),
						'circle' => __('Circle', 'rishi'),
					),
					'conditions' => ['ed_scroll_to_top' => 'yes'],

				),
				'topButtonSize'             => array(
					'label' => __('Icon Size', 'rishi'),
					'control' => ControlTypes::INPUT_SLIDER,
					'value' => array(
						'desktop' => '14px',
						'tablet' => '14px',
						'mobile' => '14px',
					),
					'units' => \Rishi\Customizer\Helpers\Basic::get_units(
						array(
							array(
								'unit' => 'px',
								'min' => 12,
								'max' => 50,
							),
						)
					),
					'responsive' => true,
					'divider' => 'top',
					'conditions' => ['ed_scroll_to_top' => 'yes'],
				),
				'topButtonOffset'             => array(
					'label' => __('Bottom Offset', 'rishi'),
					'control' => ControlTypes::INPUT_SLIDER,
					'value' => array(
						'desktop' => '25px',
						'tablet' => '25px',
						'mobile' => '25px',
					),
					'units' => \Rishi\Customizer\Helpers\Basic::get_units(
						array(
							array(
								'unit' => 'px',
								'min' => 5,
								'max' => 300,
							),
						)
					),
					'responsive' => true,
					'divider' => 'top',
					'conditions' => ['ed_scroll_to_top' => 'yes'],
				),
				'sideButtonOffset'             => array(
					'label' => __('Side Offset', 'rishi'),
					'control' => ControlTypes::INPUT_SLIDER,
					'value' => array(
						'desktop' => '25px',
						'tablet' => '25px',
						'mobile' => '25px',
					),
					'divider' => 'top',
					'units' => \Rishi\Customizer\Helpers\Basic::get_units(
						array(
							array(
								'unit' => 'px',
								'min' => 5,
								'max' => 300,
							),
						)
					),
					'responsive' => true,
					'conditions' => ['ed_scroll_to_top' => 'yes'],
				),
				'top_button_alignment'             => array(
					'label' => __('Alignment', 'rishi'),
					'control' => ControlTypes::INPUT_RADIO,
					'value' => 'right',
					'divider' => 'top',
					'attr' => array('data-type' => 'alignment'),
					'choices' => array(
						'left' => '',
						'right' => '',
					),
					'conditions' => ['ed_scroll_to_top' => 'yes'],
				),
				'back_top_visibility'             => array(
					'label' => __('Visibility', 'rishi'),
					'control' => ControlTypes::VISIBILITY,
					'design' => 'block',
					'divider' => 'top',
					'value' => array(
						'desktop' => 'desktop',
						'tablet' => 'tablet',
						'mobile' => 'mobile',
					),

					'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
						array(
							'desktop' => __('Desktop', 'rishi'),
							'tablet' => __('Tablet', 'rishi'),
							'mobile' => __('Mobile', 'rishi'),
						)
					),
					'conditions' => ['ed_scroll_to_top' => 'yes'],
				),
			),
		));

		$this->add_setting('to_top_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'top_button_scroll_style'    => array(
					'label' => __('Button Type', 'rishi'),
					'control' => ControlTypes::INPUT_RADIO,
					'value' => 'filled',
					'design' => 'block',
					'choices' => array(
						'filled' => __('Filled', 'rishi'),
						'outline' => __('Outline', 'rishi'),
					),
				),
				'topButtonIconColor'    => array(
					'label' => __('Icon Color', 'rishi'),
					'control' => ControlTypes::COLOR_PICKER,
					'design' => 'inline',
					'colorPalette'	  => true,
					'divider' => 'top',
					'value' => array(
						'default' => array(
							'color' => $colordefaults['topButtonIconColorDefault'],
						),

						'hover' => array(
							'color' => $colordefaults['topButtonIconColorHover'],
						),
					),

					'pickers' => array(
						array(
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						),

						array(
							'title' => __('Hover', 'rishi'),
							'id' => 'hover',
						),
					),
				),
				'topButtonShapeBackground'    => array(
					'label' => __('Shape Background Color', 'rishi'),
					'control' => ControlTypes::COLOR_PICKER,
					'design' => 'inline',
					'colorPalette'	  => true,
					'divider' => 'top',
					'value' => array(
						'default' => array(
							'color' => $colordefaults['topButtonShapeBackgroundDefault'],
						),

						'hover' => array(
							'color' => $colordefaults['topButtonShapeBackgroundHover'],
						),
					),

					'pickers' => array(
						array(
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						),

						array(
							'title' => __('Hover', 'rishi'),
							'id' => 'hover',
						),
					),
					'conditions' => [
						'top_button_scroll_style' => 'filled'
					],
				),
				'top_btn_border'    => array(
					'label'     => __( 'Border', 'rishi' ),
					'control'   => ControlTypes::BORDER,
					'design'    => 'inline',
					'divider'   => 'top',
					'value'     => array(
						'width' => 1,
						'style' => 'solid',
						'color' => array(
							'color' => $colordefaults['topButtonBorderDefaultColor'],
							'hover' => $colordefaults['topButtonBorderHoverColor'],
						),
					),
				),
				'topButtonShadow'    => array(
					'label' => __('Box Shadow', 'rishi'),
					'control' => ControlTypes::BOX_SHADOW,
					'design' => 'inline',
					'divider' => 'top',
					'responsive' => false,
					'value' => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value(
						array(
							'enable' => true,
							'inset' => false,
							'h_offset' => '0px',
							'v_offset' => '5px',
							'blur' => '20px',
							'spread' => '0px',
							'color' => 'rgba(210, 213, 218, 0.2)',
						)
					),
				),
				'top_button_padding'    => array(
					'label' => __('Button Padding', 'rishi'),
					'control' => ControlTypes::INPUT_SPACING,
					'divider' => 'top:bottom',
					'value' => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top' => '10',
							'left' => '10',
							'right' => '10',
							'bottom' => '10',
							'unit' => 'px',
						)
					),
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
				),
			),
		));
	}

	protected static function get_layout_default_value() {

		$defaults = array(
			'container_width'             => array(
				'desktop' => '1200px',
				'tablet'  => '992px',
				'mobile'  => '420px',
			),
			'container_content_max_width' => array(
				'desktop' => '728px',
				'tablet'  => '500px',
				'mobile'  => '400px',
			),
			'containerVerticalMargin'     => array(
				'desktop' => '80px',
				'tablet'  => '40px',
				'mobile'  => '40px',
			),
			'sidebar_widget_spacing'      => array(
				'desktop' => '64px',
				'tablet'  => '50px',
				'mobile'  => '30px',
			),
			'widgets_font_size'           => array(
				'desktop' => '18px',
				'tablet'  => '16px',
				'mobile'  => '14px',
			),
			'layout'                      => 'boxed',
			'ed_scroll_to_top'            => 'no',
			'content_sidebar_width'       => '28%',
			'layout_style'                => 'no-sidebar',
		);

		return $defaults;
	}

}
