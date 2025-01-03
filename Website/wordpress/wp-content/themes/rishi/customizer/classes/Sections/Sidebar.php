<?php
/**
 * Customizer Color Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class Sidebar extends Customize_Section {

	protected $priority = 1;

	protected $id = 'sidebar-panel';

	protected $panel = 'main_global_settings';


	protected $container = true;

	public function get_title() {
		return __( 'Sidebar', 'rishi' );
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
		return 13;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$defaults       = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();

		$options = array(
			'content_sidebar_width'     => array(
				'selector'     => ':root',
				'variableName' => 'contentSidebarWidth',
				'responsive'   => false,
				'value'        => get_theme_mod(
					'content_sidebar_width',
					$defaults['content_sidebar_width']
				),
				'type'         => 'slider',
			),
			'sidebar_widget_spacing'    => array(
				'selector'     => ':root',
				'variableName' => 'sidebarWidgetSpacing',
				'unit'         => '',
				'responsive'   => true,
				'value'        => get_theme_mod(
					'sidebar_widget_spacing',
					$defaults['sidebar_widget_spacing']
				),
				'type'         => 'slider',
			),
			'widgets_font_size'         => array(
				'selector'     => '#secondary',
				'variableName' => 'widgetsFontSize',
				'unit'         => '',
				'responsive'   => true,
				'value'        => get_theme_mod(
					'widgets_font_size',
					$defaults['widgets_font_size']
				),
				'type'         => 'slider',
			),
			'widgetsContentAreaSpacing' => array(
				'selector'     => ':root',
				'variableName' => 'widgetsContentAreaSpacing',
				'unit'         => '',
				'responsive'   => false,
				'value'        => get_theme_mod(
					'widgets_content_area_spacing',
					$defaults['widgets_content_area_spacing']
				),
				'type'         => 'spacing',
			),
		);
		foreach ( $options as $key => $option ) {
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
			'sidebar_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'sidebar_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'sidebar_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'sidebar_section_options',
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
}
