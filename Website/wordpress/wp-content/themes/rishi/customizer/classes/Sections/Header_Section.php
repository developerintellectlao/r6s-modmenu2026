<?php
/**
 *
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class Header_Section extends Customize_Section {

	protected $id = 'header';

	protected $priority = 1;

	public function get_title() {
		return __( 'Header Builder', 'rishi' );
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
		return 15;
	}

	public function get_dynamic_styles( $styles ) {
		return array();
	}

	protected function get_customize_settings() {

		return $this->settings->get_settings();

	}

	protected function get_section_control() {
		$_options = $this->get_customize_settings();
		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'header_general_section_options',
			array(
				'section' => $this->id,
				'label' => __( 'Header', 'rishi' ),
				'description' => __( 'Header options', 'rishi' ),
				'customizer_section' => self::SECTION_LAYOUT,
				'settings' => 'header_general_section_options',
				'type' => self::OPTIONS,
				'priority' => 10,
				'innerControls' => $_options,
			)
		);

		$control->json['option'] = array(
			'type' => self::OPTIONS,
			'customize_section' => self::SECTION_LAYOUT,
			'innerControls' => $_options,
			'sanitize_callback' => function ($input, $setting) {
				return $input;
			},
		);

		return $control;
	}

	protected function add_controls() {
		$this->wp_customize->add_setting(
			$this->id . '_placements',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default' => rishi_customizer()->header_builder->retrieve_default_value()
			)
		);

		$this->wp_customize->add_setting(
			$this->id . '_general_section_options',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' )
			)
		);

		$this->wp_customize->add_control(
			$this->get_section_control()
		);
	}
}
