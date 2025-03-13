<?php
/**
 * Footer Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class Footer_Section extends Customize_Section {

	protected $id = 'footer';

	protected $section = 'footer';

	protected $priority = 1;

	public function get_title() {
		return __( 'Footer Builder', 'rishi' );
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

	public function get_priority() {
		return 9;
	}

	public static function get_order() {
		return 16;
	}

	public function get_dynamic_styles($styles)
	{
		return array();
	}

	protected function get_customize_settings() {
		return $this->settings->get_settings();
	}

	protected function get_section_control() {
		$_options = $this->get_customize_settings();
		$control  = new \WP_Customize_Control(
			$this->wp_customize,
			'footer_general_section_options',
			array(
				'section'            => $this->id,
				'label'              => __( 'Footer', 'rishi' ),
				'description'        => __( 'Footer Options', 'rishi' ),
				'customizer_section' => self::SECTION_LAYOUT,
				'settings'           => 'footer_general_section_options',
				'type'               => self::OPTIONS,
				'priority'           => 10,
				'innerControls'      => $_options,
			)
		);

		$footer_builder          = \rishi_customizer()->footer_builder;
		$control->json['option'] = array(
			'type'              => self::OPTIONS,
			'builderData'       => array(
				'items'          => $footer_builder->get_items(),
				'options'        => $footer_builder->get_options(),
				'secondaryItems' => $footer_builder->get_items(),
			),
			'customize_section' => self::SECTION_LAYOUT,
			'innerControls'     => $_options,
			'sanitize_callback' => function ($input, $setting) {
				return $input;
			},
		);

		return $control;
	}

	protected function add_controls() {
		$this->wp_customize->add_setting(
			'footer_general_section_options',
			array_merge(
				array(
					'default' => '',
				),
				$this->get_setting()
			)
		);
		$this->wp_customize->add_control( $this->get_section_control() );
	}
}
