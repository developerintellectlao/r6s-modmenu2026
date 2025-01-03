<?php
/**
 * Customizer Color Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class Container extends Customize_Section {

	protected $priority = 1;

	protected $id = 'container-panel';

	protected $panel = 'main_global_settings';


	protected $container = true;

	public function get_title() {
		return __( 'Container', 'rishi' );
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
		return 9;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$defaults       = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
		$options        = array(
			// Container
			'containerWidth'            => array(
				'selector'     => ':root',
				'variableName' => 'containerWidth',
				'unit'         => '',
				'value'        => get_theme_mod(
					'container_width',
					$defaults['container_width']
				),
				'responsive'   => true,
				'type'         => 'slider',
			),
			'containerContentMaxWidth'  => array(
				'selector'     => ':root',
				'variableName' => 'containerContentMaxWidth',
				'unit'         => '',
				'value'        => get_theme_mod(
					'container_content_max_width',
					array(
						'desktop' => $defaults['container_content_max_width']['desktop'],
						'tablet'  => $defaults['container_content_max_width']['tablet'],
						'mobile'  => $defaults['container_content_max_width']['mobile'],
					)
				),
				'responsive'   => true,
				'type'         => 'slider',
			),
			'containerVerticalMargin'   => array(
				'selector'     => ':root',
				'variableName' => 'containerVerticalMargin',
				'unit'         => '',
				'value'        => get_theme_mod(
					'containerVerticalMargin',
					array(
						'desktop' => $defaults['containerVerticalMargin']['desktop'],
						'tablet'  => $defaults['containerVerticalMargin']['tablet'],
						'mobile'  => $defaults['containerVerticalMargin']['mobile'],
					)
				),
				'responsive'   => true,
			),
			'containerStrechedPadding'  => array(
				'selector'     => '.rishi-container[data-strech="full"]',
				'variableName' => 'streched-padding',
				'unit'         => '',
				'value'        => get_theme_mod(
					'containerStrechedPadding',
					array(
						'desktop' => '40px',
						'tablet'  => '30px',
						'mobile'  => '15px',
					)
				),
				'responsive'   => true,
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
			'container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'container_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'container_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'container_section_options',
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
