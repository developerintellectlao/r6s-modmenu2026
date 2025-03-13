<?php
/**
 * Customizer_Control Class.
 */
namespace Rishi\Customizer;

/**
 * This class extends the WP_Customize_Control class and is used to create custom controls in the WordPress customizer.
 */
class Customizer_Control extends \WP_Customize_Control {

	const OPTIONS = 'rishi-customizer-section';

	/**
	 * Constructor function.
	 *
	 * This function is used to initialize the class and set its properties.
	 *
	 * @param object $section The section object.
	 */
	public function __construct( $section ) {

		parent::__construct(
			$section->get_manager(),
			$this->get_control_id(),
			array(
				'label'              => $this->get_label(),
				'description'        => $this->get_description(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => $section->get_control_setting_id(),
				'section'            => $section->get_id(),
				'innerControls'      => $section->get_customize_settings(),
			)
		);

		$this->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $section->get_setting(),
			'customize_section' => 'container',
			'innerControls'     => $section->get_customize_settings(),
			'sanitize_callback' => function ( $input, $setting ) {
				return $input;
			},
		);
	}

	/**
	  * Get Description function.
	  *
	  * This function is used to get the description of the control.
	  *
	  * @return string Returns an empty string.
	  */
	public function get_description() {
		return '';
	}

	/**
	 * Get Settings function.
	 *
	 * This function is used to get the settings of the control.
	 *
	 * @return array Returns an empty array.
	 */
	public function get_settings() {
		return array();
	}

	/**
	 * Get Type function.
	 *
	 * This function is used to get the type of the control.
	 *
	 * @return string Returns the type of the control.
	 */
	public function get_type() {
		return self::OPTIONS;
	}

}
