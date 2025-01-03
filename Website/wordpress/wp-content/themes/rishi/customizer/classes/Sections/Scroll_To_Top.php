<?php
/**
 * Customizer Color Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
use Rishi\Customizer\Helpers\Defaults;

class Scroll_To_Top extends Customize_Section {

	protected $priority = 1;

	protected $id = 'scroll-to-top-panel';

	protected $panel = 'main_global_settings';


	protected $container = true;

	public function get_title() {
		return __( 'Scroll To Top', 'rishi' );
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
		return 10;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$defaults       = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
		$buttondefaults = \Rishi\Customizer\Helpers\Defaults::button_defaults();
		$colordefaults = Defaults::color_value();

		$options = array(
			'topButtonSize'             => array(
				'selector'     => '.to_top',
				'variableName' => 'topButtonSize',
				'unit'         => '',
				'responsive'   => true,
				'value'        => get_theme_mod(
					'topButtonSize',
					array(
						'desktop' => '14px',
						'tablet'  => '14px',
						'mobile'  => '14px',
					)
				),
			),
			'topButtonOffset'           => array(
				'selector'     => '.to_top',
				'variableName' => 'topButtonOffset',
				'unit'         => '',
				'responsive'   => true,
				'value'        => get_theme_mod(
					'topButtonOffset',
					array(
						'desktop' => '25px',
						'tablet'  => '25px',
						'mobile'  => '25px',
					)
				),
			),
			'sideButtonOffset'          => array(
				'selector'     => '.to_top',
				'variableName' => 'sideButtonOffset',
				'unit'         => '',
				'responsive'   => true,
				'value'        => get_theme_mod(
					'sideButtonOffset',
					array(
						'desktop' => '25px',
						'tablet'  => '25px',
						'mobile'  => '25px',
					)
				),
			),
			'topButtonIconColor'        => array(
				'value'     => get_theme_mod( 'topButtonIconColor' ),
				'default'   => array(
					'default' => array( 'color' => $colordefaults['topButtonIconColorDefault'] ),
					'hover'   => array( 'color' => $colordefaults['topButtonIconColorHover'] ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'topButtonIconColorDefault',
						'selector' => '.to_top',
					),
					'hover'   => array(
						'variable' => 'topButtonIconColorHover',
						'selector' => '.to_top',
					),
				),
				'type'      => 'color',
			),
			'topButtonShapeBackground'  => array(
				'value'     => get_theme_mod( 'topButtonShapeBackground' ),
				'default'   => array(
					'default' => array( 'color' => $colordefaults['topButtonShapeBackgroundDefault'] ),
					'hover'   => array( 'color' => $colordefaults['topButtonShapeBackgroundHover'] ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'topButtonShapeBackgroundDefault',
						'selector' => '.to_top',
					),
					'hover'   => array(
						'variable' => 'topButtonShapeBackgroundHover',
						'selector' => '.to_top',
					),
				),
				'type'      => 'color',
			),
			'topButtonShadow'           => array(
				'value'     => get_theme_mod(
					'topButtonShadow',
					$defaults['topButtonShadow']
				),
				'default'   => $defaults['topButtonShadow'],
				'variables' => array(
					'default' => array(
						'variable' => 'topButtonShadow',
						'selector' => '.to_top',
					),
				),
				'type'      => 'boxshadow',
			),
			'top_button_padding'        => array(
				'selector'     => '.to_top',
				'variableName' => 'top_button_padding',
				'unit'         => '',
				'responsive'   => false,
				'value'        => get_theme_mod(
					'top_button_padding',
					$buttondefaults['top_button_padding']
				),
				'type'         => 'spacing',
			),
			'top_btn_border'            => array(
				'value'     => get_theme_mod(
					'top_btn_border',
					$defaults['btn_border']
				),
				'type'      => 'divider',
				'unit'      => 'px',
				'default'   => $defaults['btn_border'],
				'variables' => array(
					'default' => array(
						'variable' => 'top-button-border',
						'selector' => '.to_top',
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
			'scroll_to_top_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'scroll_to_top_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'scroll_to_top_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'scroll_to_top_section_options',
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
