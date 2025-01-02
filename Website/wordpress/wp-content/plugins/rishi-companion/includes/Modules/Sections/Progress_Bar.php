<?php
/**
 * Progress Bar Extension
 *
 * This class provides the functionality for the Progress Bar extension.
 *
 * @package Rishi_Companion\Modules\Sections
 */

namespace Rishi_Companion\Modules\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
use Rishi\Customizer\ControlTypes;
class Progress_Bar extends Customize_Section {

	/**
     * The priority of the extension.
     *
     * @var int
     */
    protected $priority = 2;

    /**
     * The ID of the extension.
     *
     * @var string
     */
    protected $id = 'progress-bar';

    /**
     * The container of the extension.
     *
     * @var bool
     */
    protected $container = true;

    /**
     * Get the title of the extension.
     *
     * @return string
     */
    public function get_title() {
        return __( 'Progress Bar', 'rishi-companion' );
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
        return 51;
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
     * Check if the extension is enabled.
     *
     * @return bool
     */
    public static function is_enabled() {
        $active_extensions = get_option('rc_active_extensions', array());

        if (in_array('progress-bar', $active_extensions)) {
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
            \Rishi\Customizer\Helpers\Basic::uniqid( ) => [
                'title' => __( 'General', 'rishi-companion' ),
                'control' => ControlTypes::TAB,
                'options' => [
                    'progressThickness' => [
                        'label'   => __( 'Thickness', 'rishi-companion' ),
                        'control' => ControlTypes::INPUT_SLIDER,
                        'units'   => \Rishi\Customizer\Helpers\Basic::get_units(
                            array(
                                array(
                                    'unit' => 'px',
                                    'min'  => 1,
                                    'max'  => 30,
                                ),
								array(
                                    'unit' => 'pt',
                                    'min'  => 1,
                                    'max'  => 25,
                                ),
								array(
                                    'unit' => 'em',
                                    'min'  => 0,
                                    'max'  => 5,
                                ),
								array(
                                    'unit' => 'rem',
                                    'min'  => 0,
                                    'max'  => 5,
                                ),
                            )
                        ),
                        'divider' => 'bottom',
                        'value'   => '5px',
                    ],
                    'displayProgress' => [
                        'label'   => __('Display Condition', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_RADIO,
                        'value'   => 'post',
                        'view'    => 'text',
                        'divider' => 'bottom',
                        'choices' => [
                            'everywhere' => __('Everywhere', 'rishi-companion'),
                            'post'  	 => __('Post', 'rishi-companion'),
                            'page'  	 => __('Page', 'rishi-companion'),
                        ],
                    ],
                    'display_top_bottom' => [
                        'label'   => __('Position', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_RADIO,
                        'value'   => 'top',
                        'divider' => 'bottom',
                        'view'    => 'text',
                        'choices' => [
                            'top'   => __('Top', 'rishi-companion'),
                            'bottom'  => __('Bottom', 'rishi-companion'),
                        ],
                    ],
                ],
            ],
            \Rishi\Customizer\Helpers\Basic::uniqid( ) => [
                'title' => __( 'Design', 'rishi-companion' ),
                'control' => ControlTypes::TAB,
                'options' => [
                    'progressBarColor' => [
                        'label' => __( 'Color', 'rishi-companion' ),
                        'control'         => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
                        'design'          => 'inline',
                        'value' => [
                            'default' => [
                                'color' => 'var(--paletteColor5)',
                            ],

                            'progress' => [
                                'color' => 'var(--paletteColor3)',
                            ],
                        ],

                        'pickers' => [
                            [
                                'title' => __( 'Background Color', 'rishi-companion' ),
                                'id' => 'default',
                            ],

                            [
                                'title' => __( 'Progress Color', 'rishi-companion' ),
                                'id' => 'progress',
                            ],
                        ],
                        'divider' => 'bottom',
                    ],
                ],
            ],

        );

	}

     /**
     * Add controls for the extension.
     */
    protected function add_controls() {
		$this->wp_customize->add_section(
			'progress_bar_container_panel',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'progress_bar_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'progress_bar_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'progress_bar_section_options',
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
     * Get the dynamic styles of the extension.
     *
     * @param array $styles
     * @return array
     */
    public function get_dynamic_styles( $dynamic_styles )	{

        $options = array (
            'progressThickness' => array(
				'type' 	   => 'slider',
				'selector'     => '#rishi-progress-bar',
				'variableName' => 'thickness',
				'unit'         => '',
				'value'        => get_theme_mod(
					'progressThickness',
					'5px'
				),
				'responsive'   => false,
			),
            'progressBarColor'      => array(
				'value'     => get_theme_mod( 'progressBarColor' ),
				'type'      => 'color',
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor5)' ),
					'progress' => array( 'color' =>  'var(--paletteColor3)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'colorDefault',
						'selector' => '#rishi-progress-bar',
					),
					'progress'   => array(
						'variable' => 'colorProgress',
						'selector' => '#rishi-progress-bar',
					),
				),
			),
        );

		foreach ( $options as $key => $option ) {
			$dynamic_styles->add( $key, $option );
		}
	}
}
