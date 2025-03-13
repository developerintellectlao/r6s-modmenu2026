<?php

namespace Rishi\Customizer\Settings;
use \Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts\Customize_Settings;

class Container_Setting extends Customize_Settings {

	protected function add_settings() {
		$defaults       = self::get_layout_default_value();

		$this->add_setting(
			'container_width',
			array(
				'label'      => __( 'Container Width', 'rishi' ),
				'help'       => __( 'This setting sets the container width of the site.', 'rishi' ),
				'control'    => ControlTypes::INPUT_SLIDER,
				'value'      => array(
					'desktop' => $defaults['container_width']['desktop'],
					'tablet'  => $defaults['container_width']['tablet'],
					'mobile'  => $defaults['container_width']['mobile'],
				),
				'units'      => \Rishi\Customizer\Helpers\Basic::get_units(
					array(
						array(
							'unit' => 'px',
							'min'  => 0,
							'max'  => 1900,
						),
					)
				),
				'responsive' => true,
				'divider'    => 'bottom',
			)
		);

		$this->add_setting(
			'layout',
			array(
				'label'   => __( 'Layout', 'rishi' ),
				'control' => ControlTypes::INPUT_SELECT,
				'value'   => $defaults['layout'],
				'divider' => 'bottom',
				'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
					array(
						'boxed'                => __( 'Boxed', 'rishi' ),
						'content_boxed'        => __( 'Content Boxed', 'rishi' ),
						'full_width_contained' => __( 'Unboxed', 'rishi' ),
					)
				),
				'help'    => __( 'Choose the default site layout.', 'rishi' ),
			)
		);
		$this->add_setting(
			'container_content_max_width',
			array(
				'label'      => __( 'Centered Max-width', 'rishi' ),
				'help'       => __( 'This setting sets the container width for a Fullwidth Centered layout.', 'rishi' ),
				'control'    => ControlTypes::INPUT_SLIDER,
				'value'      => array(
					'desktop' => $defaults['container_content_max_width']['desktop'],
					'tablet'  => $defaults['container_content_max_width']['tablet'],
					'mobile'  => $defaults['container_content_max_width']['mobile'],
				),
				'units'      => \Rishi\Customizer\Helpers\Basic::get_units(
					array(
						array(
							'unit' => 'px',
							'min'  => 0,
							'max'  => 1170,
						),
					)
				),
				'responsive' => true,
				'divider'    => 'bottom',
			)
		);
		$this->add_setting(
			'containerVerticalMargin',
			array(
				'label'      => __( 'Centered Vertical Spacing', 'rishi' ),
				'help'       => __( 'This setting sets the spacing at the top and bottom of the container.', 'rishi' ),
				'control'    => ControlTypes::INPUT_SLIDER,
				'value'      => array(
					'desktop' => $defaults['containerVerticalMargin']['desktop'],
					'tablet'  => $defaults['containerVerticalMargin']['tablet'],
					'mobile'  => $defaults['containerVerticalMargin']['mobile'],
				),
				'units'      => \Rishi\Customizer\Helpers\Basic::get_units(
					array(
						array(
							'unit' => 'px',
							'min'  => 0,
							'max'  => 250,
						),
					)
				),
				'responsive' => true,
				'divider'    => 'bottom',

		));
		$this->add_setting('containerStrechedPadding', array(
			'label' => __('Stretched Padding', 'rishi'),
			'control' => ControlTypes::INPUT_SLIDER,
			'help' => __('This setting sets the spacing at the left and right of the container when the Stretch layout is enabled.', 'rishi'),
			'value' => array('desktop' => '40px', 'tablet' => '30px', 'mobile' => '15px', ),
			'units' => \Rishi\Customizer\Helpers\Basic::get_units(array(array('unit' => 'px', 'min' => 0, 'max' => 250, ), )),
			'responsive' => true,
			'divider' => 'bottom',
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
