<?php
/**
 * Customizer Color Section
 */
namespace Rishi\Customizer\Sections;
use \Rishi\Customizer\Helpers\Defaults as Defaults;
use Rishi\Customizer\Abstracts\Customize_Section;

class Button extends Customize_Section {

	protected $priority = 1;

	protected $id = 'global-button-panel';

	protected $panel = 'main_global_settings';


	protected $container = true;

	public function get_title() {
		return __( 'Button', 'rishi' );
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
		return 12;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$defaults       = Defaults::get_layout_defaults();
		$buttondefaults = Defaults::button_defaults();

		$options = array(
			'bottonRoundness'           => array(
				'selector'     => ':root',
				'variableName' => 'bottonRoundness',
				'value'        => get_theme_mod(
					'botton_roundness',
					$buttondefaults['botton_roundness']
				),
				'type'         => 'slider',
				'responsive'   => true,
			),
			'buttonPadding'             => array(
				'selector'     => ':root',
				'variableName' => 'buttonPadding',
				'responsive'   => true,
				'value'        => get_theme_mod(
					'button_padding',
					$buttondefaults['button_padding']
				),
				'type'         => 'spacing',
			),
			'sticky_row_box_shadow'     => array(
				'value'     => get_theme_mod(
					'sticky_row_box_shadow',
					$defaults['sticky_row_box_shadow']
				),
				'default'   => $defaults['sticky_row_box_shadow'],
				'variables' => array(
					'default' => array(
						'variable' => 'stickyBoxShadow',
						'selector' => '.header-row.sticky-row',
					),
				),
				'type'      => 'boxshadow',
			),
			'btn_border'                => array(
				'value'     => get_theme_mod(
					'btn_border',
					$defaults['btn_border']
				),
				'type'      => 'divider',
				'unit'      => 'px',
				'default'   => $defaults['btn_border'],
				'variables' => array(
					'default'  => array(
						'variable' => 'buttonBorder',
						'selector' => ':root',
					),
				),
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
			'button_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'button_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'button_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'button_section_options',
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
