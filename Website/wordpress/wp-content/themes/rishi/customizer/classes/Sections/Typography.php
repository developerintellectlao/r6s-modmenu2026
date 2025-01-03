<?php
/**
 * Customizer Color Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

class Typography extends Customize_Section {

	protected $priority = 1;

	protected $id = 'typography-section';

	protected $panel = 'main_global_settings';


	protected $container = true;

	public function get_title() {
		return __( 'Typography', 'rishi' );
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
		return 8;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$typo_options = array(
			'rootTypography'              => array(
				'value'    => get_theme_mod(
					'rootTypography',
					Defaults::typography_value(
						array(
							'family'          => 'System Default',
							'weight'          => '400',
							'size'            => array(
								'desktop' => '18px',
								'tablet'  => '18px',
								'mobile'  => '18px',
							),
							'line-height'            => array(
								'desktop' => '1.75',
								'tablet'  => '1.75',
								'mobile'  => '1.75',
							),
							'letter-spacing'            => array(
								'desktop' => '0em',
								'tablet'  => '0em',
								'mobile'  => '0em',
							),
							'style'           => 'Default',
							'text-transform'  => 'none',
							'text-decoration' => 'none',
						),
						'rootTypography'
					)
				),
				'selector' => ':root',
				'type'     => 'typography',
			),
			'h1Typography'                => array(
				'value'    => get_theme_mod(
					'h1Typography',
					Defaults::typography_value(
						array(
							'size'            => array(
								'desktop' => '40px',
								'tablet'  => '40px',
								'mobile'  => '40px',
							),
							'line-height'            => array(
								'desktop' => '1.5',
								'tablet'  => '1.5',
								'mobile'  => '1.5',
							),
							'weight'      => '700',
						)
					)
				),
				'selector' => 'h1, .block-editor-page .editor-styles-wrapper h1, .block-editor-page .editor-post-title__block .editor-post-title__input',
				'type'     => 'typography',
			),
			'h2Typography'                => array(
				'value'    => get_theme_mod(
					'h2Typography',
					Defaults::typography_value(
						array(
							'size'            => array(
								'desktop' => '36px',
								'tablet'  => '36px',
								'mobile'  => '36px',
							),
							'line-height'            => array(
								'desktop' => '1.5',
								'tablet'  => '1.5',
								'mobile'  => '1.5',
							),
							'weight'      => '700',
						)
					)
				),
				'selector' => 'h2',
				'type'     => 'typography',
			),
			'h3Typography'                => array(
				'value'    => get_theme_mod(
					'h3Typography',
					Defaults::typography_value(
						array(
							'size'            => array(
								'desktop' => '30px',
								'tablet'  => '30px',
								'mobile'  => '30px',
							),
							'line-height'            => array(
								'desktop' => '1.5',
								'tablet'  => '1.5',
								'mobile'  => '1.5',
							),
							'weight'      => '700',
						)
					)
				),
				'selector' => 'h3',
				'type'     => 'typography',
			),
			'h4Typography'                => array(
				'value'    => get_theme_mod(
					'h4Typography',
					Defaults::typography_value(
						array(
							'size'            => array(
								'desktop' => '26px',
								'tablet'  => '26px',
								'mobile'  => '26px',
							),
							'line-height'            => array(
								'desktop' => '1.5',
								'tablet'  => '1.5',
								'mobile'  => '1.5',
							),
							'weight'      => '700',
						)
					)
				),
				'selector' => 'h4',
				'type'     => 'typography',
			),
			'h5Typography'                => array(
				'value'    => get_theme_mod(
					'h5Typography',
					Defaults::typography_value(
						array(
							'size'            => array(
								'desktop' => '22px',
								'tablet'  => '22px',
								'mobile'  => '22px',
							),
							'line-height'            => array(
								'desktop' => '1.5',
								'tablet'  => '1.5',
								'mobile'  => '1.5',
							),
							'weight'      => '700',
						)
					)
				),
				'selector' => 'h5',
				'type'     => 'typography',
			),
			'h6Typography'                => array(
				'value'    => get_theme_mod(
					'h6Typography',
					Defaults::typography_value(
						array(
							'size'            => array(
								'desktop' => '18px',
								'tablet'  => '18px',
								'mobile'  => '18px',
							),
							'line-height'            => array(
								'desktop' => '1.5',
								'tablet'  => '1.5',
								'mobile'  => '1.5',
							),
							'weight'      => '700',
						)
					)
				),
				'selector' => 'h6',
				'type'     => 'typography',
			),
			'breadcrumbsTypo'             => array(
				'value'    => get_theme_mod(
					'breadcrumbsTypo',
					Defaults::typography_value(
						array(
							'family' => 'System Default',
							'weight' => '400',
							'size'            => array(
								'desktop' => '14px',
								'tablet'  => '14px',
								'mobile'  => '14px',
							),
						)
					)
				),
				'selector' => '.rishi-breadcrumb-main-wrap .rishi-breadcrumbs',
				'variable' => 'breadcrumbsTypo',
				'type'     => 'typography',
			),
			'button_Typo'                 => array(
				'value'    => get_theme_mod(
					'button_Typo',
					Defaults::typography_value(
						array(
							'family'      => 'Default',
							'weight'   => '400',
							'size'        => '18px',
							'line-height' => '1.2',
						)
					)
				),
				'selector' => ':root',
				'prefix'   => 'btn',
				'type'     => 'typography',
			)
		);

		foreach ( $typo_options as $key => $option ) {
			$dynamic_styles->add( $key, $option );
		}

	}

	public function get_customize_settings() {

		return $this->settings->get_settings();

	}

	public function get_control_setting_id() {
		return 'layouts_typography_options';
	}

	protected function add_controls() {
		$this->wp_customize->add_section(
			'typography_container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'typography_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'typography_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'typography_section_options',
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
