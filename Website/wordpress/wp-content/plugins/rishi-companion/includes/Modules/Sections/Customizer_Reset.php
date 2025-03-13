<?php
/**
 * Customizer Reset Extension
 *
 * This class provides the functionality for the Customizer Reset extension.
 *
 * @package Rishi_Companion\Modules\Sections
 */

namespace Rishi_Companion\Modules\Sections;

use \Rishi\Customizer\Abstracts\Customize_Section;
use \Rishi\Customizer\ControlTypes;

class Customizer_Reset extends Customize_Section {

	/**
     * The priority of the extension.
     *
     * @var int
     */
    protected $priority = 2;

    /**
     * The container of the extension.
     *
     * @var bool
     */
    protected $container = true;

    /**
     * The ID of the extension.
     *
     * @var string
     */
    protected $id = 'customizer-reset';

	/**
     * Get the type of the extension.
     *
     * @return string
     */
    public function get_type() {
        return self::OPTIONS;
    }

    /**
     * Get the order of the extension.
     *
     * @return int
     */
    public static function get_order() {
        return 54;
    }

    /**
     * Get the ID of the extension.
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get the default values of the extension.
     *
     * @return array
     */
    protected function get_defaults() {
        return array();
    }

    /**
     * Get the title of the extension.
     *
     * @return string
     */
    public function get_title() {
        return __( 'Customizer Reset', 'rishi-companion' );
    }

	/**
     * Get the dynamic styles of the extension.
     *
     * @param array $styles
     * @return array
     */
    public function get_dynamic_styles($styles) {
        return array();
    }

    /**
     * Check if the extension is enabled.
     *
     * @return bool
     */
    public static function is_enabled() {
        $active_extensions = get_option('rc_active_extensions', array());

        if (in_array('customizer-reset', $active_extensions)) {
            return true;
        }

        return false;
    }

    /**
     * Get the customize settings of the extension.
     *
     * @return array
     */
	protected function get_customize_settings() {
        return array(
			'customizer_reset_button' => array(
				'label'       => __( 'Customizer Reset', 'rishi-companion' ),
                'help'        => __( 'Click this button to revert all configurations to their initial settings. Please be aware that this action is irreversible.', 'rishi-companion' ),
				'control'     => ControlTypes::INPUT_BUTTON,
				'size'		  => 'full',
				'input_attrs' => array(
					'value' => __( 'Customizer Reset', 'rishi-companion' ),
					'class' => 'button button-primary customizer-reset',
				)
			)
		);
    }

    /**
     * Add controls for the extension.
     */
	protected function add_controls() {

		$this->wp_customize->add_section(
			'customizer_reset_panel',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'customizer_reset_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'customizer_reset_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'customizer_reset_section_options',
				'section'            => $this->get_id(),
				'innerControls'      => $this->get_customize_settings(),
			)
		);

		$control->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $this->get_setting(),
			'customize_section' => 'container',
			'innerControls'     => $this->get_customize_settings(),
			'sanitize_callback' => function ($input, $setting) {
				return $input;
			},
		);

		$this->wp_customize->add_control( $control );
	}
}
