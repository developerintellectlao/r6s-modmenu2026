<?php

namespace Rishi\Customizer\Settings;
use \Rishi\Customizer\ControlTypes;
use \Rishi\Customizer\Helpers\Defaults as Defaults;
use Rishi\Customizer\Abstracts\Customize_Settings;

class SEO_Setting extends Customize_Settings {

	protected function add_settings() {
		$defaults       = self::get_layout_default_value();
		$seo_defaults = self::get_seo_default_value();
		$colordefaults  = Defaults::color_value();
		$this->add_setting('rishi_seo_section_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'breadcrumbs_position'                 => array(
					'label'   => __( 'Breadcrumb Position', 'rishi' ),
					'control' => ControlTypes::INPUT_SELECT,
					'value'   => $seo_defaults['breadcrumbs_position'],
					'design'  => 'block',
					'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
						array(
							'none'   => __( 'None', 'rishi' ),
							'before' => __( 'Before Title', 'rishi' ),
						)
					),
				),
				'breadcrumbs_separator'                         => array(
					'label'   => __( 'Separator', 'rishi' ),
					'control' => ControlTypes::IMAGE_PICKER,
					'value'   => $seo_defaults['breadcrumbs_separator'],
					'divider' => 'top',
					'attr'    => array( 'data-columns' => '3' ),
					'choices' => array(
						'type-1' => array(
							'src'   => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( 'breadcrumb-sep-1' ),
							'title' => __( 'Type 1', 'rishi' ),
						),

						'type-2' => array(
							'src'   => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( 'breadcrumb-sep-2' ),
							'title' => __( 'Type 2', 'rishi' ),
						),

						'type-3' => array(
							'src'   => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( 'breadcrumb-sep-3' ),
							'title' => __( 'Type 3', 'rishi' ),
						),
					),
				),
				'breadcrumbs_alignment'             => array(
					'control'    => ControlTypes::INPUT_RADIO,
					'label'      => __( 'Horizontal Alignment', 'rishi' ),
					'value'      => $seo_defaults['breadcrumbs_alignment'],
					'view'       => 'text',
					'attr'       => array( 'data-type' => 'alignment' ),
					'design'     => 'block',
					'divider'    => 'top',
					'choices'    => array(
						'left'   => __( 'Left', 'rishi' ),
						'center' => __( 'Center', 'rishi' ),
						'right'  => __( 'Right', 'rishi' ),
					),

				),
				'enable_schema_org_markup'             => array(
					'label'   => __( 'Schema Markup', 'rishi' ),
					'control' => ControlTypes::INPUT_SWITCH,
					'value'   => 'yes',
					'help'    => __( 'This option will be enable schema markup.', 'rishi' ),
					'divider' => 'top:bottom',
				),
			),
		));

		$this->add_setting('rishi_seo_section_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'breadcrumbs_color'    => array(
					'label'           => __( 'Breadcrumb Color', 'rishi' ),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'bottom',
					'value'           => array(
						'default' => array(
							'color' => $colordefaults['breadcrumbsColor'],
						),
					),
					'pickers'         => array(
						array(
							'title' => __( 'Initial', 'rishi' ),
							'id'    => 'default',
						),
					),
				),
				'breadcrumbs_current_color'    => array(
					'label'           => __( 'Breadcrumb Current Color', 'rishi' ),
					'control'            => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'bottom',
					'value'           => array(
						'default' => array(
							'color' => $colordefaults['breadcrumbsCurrentColor'],
						),
					),
					'pickers'         => array(
						array(
							'title' => __( 'Initial', 'rishi' ),
							'id'    => 'default',
						),
					),
				),
				'breadcrumbsSeparatorColor'    => array(
					'label'   => __( 'Breadcrumb Separator Color', 'rishi' ),
					'control' => ControlTypes::COLOR_PICKER,
					'design'  => 'inline',
					'colorPalette'	  => true,
					'divider' => 'bottom',
					'value'   => array(
						'default' => array(
							'color' => $colordefaults['breadcrumbsSeparatorColor'],
						),
					),
					'pickers'         => array(
						array(
							'title' => __( 'Initial', 'rishi' ),
							'id'    => 'default',
						),
					),
				),
				'breadcrumbsTypo'           => rishi_typography_control_option(array(
					'control' => ControlTypes::TYPOGRAPHY,
					'label'   => __( 'Font', 'rishi' ),
					'divider' => 'bottom',
					'value'   => Defaults::typography_value(
						array(
							'size'            => array(
								'desktop' => '14px',
								'tablet'  => '14px',
								'mobile'  => '14px',
							),
							'family'    => 'System Default',
							'weight' => '500',
						)
					),
				)),
				'breadcrumbsPadding'        => array(
					'label'      => __( 'Padding', 'rishi' ),
					'control'    => ControlTypes::INPUT_SPACING,
					'divider'    => 'bottom',
					'value'      => array(
						'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
							array(
								'linked' => false,
								'top'    => '10',
								'left'   => '0',
								'right'  => '0',
								'bottom' => '10',
								'unit'	 => 'px'
							)
						),
						'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
							array(
								'linked' => false,
								'top'    => '10',
								'left'   => '0',
								'right'  => '0',
								'bottom' => '10',
								'unit'	 => 'px'
							)
						),
						'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
							array(
								'linked' => false,
								'top'    => '10',
								'left'   => '0',
								'right'  => '0',
								'bottom' => '10',
								'unit'	 => 'px'
							)
						),
					),
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
					'responsive' => true,
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

	/**
	 * Set default value for seo page.
	 */
	protected static function get_seo_default_value() {

		$seo_defaults = array(
			'breadcrumbs_position'  => 'before',
			'breadcrumbs_separator' => 'type-1',
			'breadcrumbs_alignment' => 'left',
			'breadcrumbsPadding' => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '10',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '10',
						'unit'	 => 'px'
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '10',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '10',
						'unit'	 => 'px'
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '10',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '10',
						'unit'	 => 'px'
					)
				),
			)
		);

		return $seo_defaults;
	}

}
