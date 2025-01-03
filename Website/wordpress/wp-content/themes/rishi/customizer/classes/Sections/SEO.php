<?php
/**
 * Customizer Color Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class SEO extends Customize_Section {

	protected $priority = 1;

	protected $id = 'seo-panel';

	protected $panel = 'main_global_settings';


	protected $container = true;

	public function get_title() {
		return __( 'SEO', 'rishi' );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_type() {
		return self::OPTIONS;
	}

	protected function get_defaults() {
		return array();
	}

	public static function get_order() {
		return 14;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$colordefaults = \Rishi\Customizer\Helpers\Defaults::color_value();
		$seo_defaults  = self::get_seo_default_value();

		$seo_options = array(
			'breadcrumbsColor'          => array(
				'selector'     => ':root',
				'variableName' => 'breadcrumbsColor',
				'unit'         => '',
				'value'        => get_theme_mod(
					'breadcrumbs_color',
					$colordefaults['breadcrumbsColor']
				),
				'default'      => array(
					'color' => $colordefaults['breadcrumbsColor'],
				),
				'responsive'   => false,
				'type'         => 'color',
			),
			'breadcrumbsCurrentColor'   => array(
				'selector'     => ':root',
				'variableName' => 'breadcrumbsCurrentColor',
				'unit'         => '',
				'value'        => get_theme_mod(
					'breadcrumbs_current_color',
					$colordefaults['breadcrumbsColor']
				),
				'default'      => array(
					'color' => $colordefaults['breadcrumbsCurrentColor'],
				),
				'responsive'   => false,
				'type'         => 'color',
			),
			'breadcrumbsSeparatorColor' => array(
				'selector'     => ':root',
				'variableName' => 'breadcrumbsSeparatorColor',
				'unit'         => '',
				'value'        => get_theme_mod(
					'breadcrumbsSeparatorColor',
					$colordefaults['breadcrumbsSeparatorColor']
				),
				'default'      => array(
					'color' => $colordefaults['breadcrumbsSeparatorColor'],
				),
				'responsive'   => false,
				'type'         => 'color',
			),
			'breadcrumbsPadding'        => array(
				'selector'     => '.rishi-breadcrumb-main-wrap',
				'variableName' => 'padding',
				'unit'         => '',
				'responsive'   => true,
				'value'        => get_theme_mod(
					'breadcrumbsPadding',
					$seo_defaults['breadcrumbsPadding']
				),
				'type'         => 'spacing',
			),
			'breadcrumbs_alignment'        => array(
				'selector'     => '.rishi-breadcrumb-main-wrap .rishi-breadcrumbs',
				'variableName' => 'alignment',
				'value'        => get_theme_mod(
					'breadcrumbs_alignment',
					$seo_defaults['breadcrumbs_alignment']
				),
				'type'         => 'alignment',
			),
		);
		foreach ( $seo_options as $key => $option ) {
			$dynamic_styles->add( $key, $option );
		}

	}

	public function get_customize_settings() {

		return $this->settings->get_settings();

	}

	public function get_control_setting_id() {
		return 'layouts_container_options';
	}

	protected function add_controls() {
		$this->wp_customize->add_section(
			'seo_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'seo_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'seo_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'seo_section_options',
				'section'            => $this->get_id(),
				'innerControls'      => $this->get_customize_settings(),
			)
		);

		$control->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $this->get_setting(),
			'customize_section' => 'container',
			'innerControls'     => $this->get_customize_settings(),
			'sanitize_callback' => function ( $input, $setting ) {
				return $input;
			},
		);

		$this->wp_customize->add_control( $control );
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
