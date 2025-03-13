<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Softlite_Background_Image extends \Elementor\Widget_Base {

	public function __construct() {
		parent::__construct();
		$this->init_control();
	}

	public function get_name() {
		return 'softlite-background-image';
	}

	public function softlite_register_controls( $element, $args ) {

        $element->add_control(
			'softlite_background_image_enable',
			[
				'label' => __( 'Softlite.io Background Image', 'pafe' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Yes',
				'label_off' => 'No',
				'return_value' => 'yes',
			]
		);

        $element->add_control(
            'softlite_background_image_source',
            [
                'label' => esc_html__( 'Image Source', 'elementor' ),
				'type' => Elementor\Controls_Manager::SELECT,
				'options' => [
					'upload' => esc_html__( 'Upload File', 'elementor' ),
					'external' => esc_html__( 'External URL', 'elementor' ),
                    'custom' => esc_html__( 'Custom', 'elementor' ),
				],
				'default' => 'external',
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                ],
            ]
        );

        $element->add_responsive_control(
			'softlite_background_image',
			[
                'label' => esc_html__( 'Image', 'elementor' ),
                'type' => Elementor\Controls_Manager::MEDIA,
                'ai' => [
                    'category' => 'background',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'title' => esc_html__( 'Background Image', 'elementor' ),
                'selectors' => [
                    '{{WRAPPER}}' => 'background-image: url("{{URL}}");',
                ],
                'has_sizes' => true,
                'render_type' => 'template',
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                    'softlite_background_image_source' => 'upload',
                ],
            ]
        );

		$element->add_control(
			'softlite_background_image_external_url',
			[
				'label' => __( 'Image URL', 'pafe' ),
                'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
                'selectors' => [
                    '{{WRAPPER}}' => 'background-image: url({{VALUE}});',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                    'softlite_background_image_source' => 'external',
                ],
			]
		);

        $element->add_control(
            'softlite_background_image_custom',
            [
                'label' => esc_html__( 'Custom', 'elementor' ),
                'label_block' => true,
                'type' => Elementor\Controls_Manager::TEXT,
                'selectors' => [
                    '{{WRAPPER}}' => 'background-image: {{VALUE}};',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                    'softlite_background_image_source' => 'custom',
                ],
            ]
        );

        $element->add_responsive_control(
            'softlite_background_position',
            [
                'label' => esc_html__( 'Position', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'elementor' ),
                    'center center' => esc_html__( 'Center Center', 'elementor' ),
                    'center left' => esc_html__( 'Center Left', 'elementor' ),
                    'center right' => esc_html__( 'Center Right', 'elementor' ),
                    'top center' => esc_html__( 'Top Center', 'elementor' ),
                    'top left' => esc_html__( 'Top Left', 'elementor' ),
                    'top right' => esc_html__( 'Top Right', 'elementor' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'elementor' ),
                    'bottom left' => esc_html__( 'Bottom Left', 'elementor' ),
                    'bottom right' => esc_html__( 'Bottom Right', 'elementor' ),
                    'initial' => esc_html__( 'Custom', 'elementor' ),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'background-position: {{VALUE}};',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                ],
            ]
        );

		$element->add_responsive_control(
            'softlite_background_xpos',
            [
                'label' => esc_html__( 'X Position', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'tablet_default' => [
                    'size' => 0,
                ],
                'mobile_default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => -800,
                        'max' => 800,
                    ],
                    'em' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'background-position: {{SIZE}}{{UNIT}} {{softlite_background_ypos.SIZE}}{{softlite_background_ypos.UNIT}}',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                    'softlite_background_position' => [ 'initial' ],
                ],
                'required' => true,
            ]
        );

		$element->add_responsive_control(
            'softlite_background_ypos',
            [
                'label' => esc_html__( 'Y Position', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'default' => [
                    'size' => 0,
                ],
                'tablet_default' => [
                    'size' => 0,
                ],
                'mobile_default' => [
                    'size' => 0,
                ],
                'range' => [
                    'px' => [
                        'min' => -800,
                        'max' => 800,
                    ],
                    'em' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'background-position: {{softlite_background_xpos.SIZE}}{{softlite_background_xpos.UNIT}} {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                    'softlite_background_position' => [ 'initial' ],
                ],
                'required' => true,
            ]
        );

		$element->add_control(
            'softlite_background_attachment',
            [
                'label' => esc_html_x( 'Attachment', 'Background Control', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'elementor' ),
                    'scroll' => esc_html_x( 'Scroll', 'Background Control', 'elementor' ),
                    'fixed' => esc_html_x( 'Fixed', 'Background Control', 'elementor' ),
                ],
                'selectors' => [
                    '(desktop+){{WRAPPER}}' => 'background-attachment: {{VALUE}};',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                ],
            ]
        );

		$element->add_responsive_control(
            'softlite_background_repeat',
            [
                'label' => esc_html_x( 'Repeat', 'Background Control', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'elementor' ),
                    'no-repeat' => esc_html__( 'No-repeat', 'elementor' ),
                    'repeat' => esc_html__( 'Repeat', 'elementor' ),
                    'repeat-x' => esc_html__( 'Repeat-x', 'elementor' ),
                    'repeat-y' => esc_html__( 'Repeat-y', 'elementor' ),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'background-repeat: {{VALUE}};',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                ],
            ]
        );

		$element->add_responsive_control(
            'softlite_background_size',
            [
                'label' => esc_html__( 'Display Size', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'Default', 'elementor' ),
                    'auto' => esc_html__( 'Auto', 'elementor' ),
                    'cover' => esc_html__( 'Cover', 'elementor' ),
                    'contain' => esc_html__( 'Contain', 'elementor' ),
                    'initial' => esc_html__( 'Custom', 'elementor' ),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'background-size: {{VALUE}};',
                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                ],
            ]
        );

		$element->add_responsive_control(
            'softlite_background_bg_width',
            [
                'label' => esc_html__( 'Width', 'elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'required' => true,
                'selectors' => [
                    '{{WRAPPER}}' => 'background-size: {{SIZE}}{{UNIT}} auto',

                ],
                'condition' => [
                    'softlite_background_image_enable' => 'yes',
                    'softlite_background_size' => [ 'initial' ],
                ],
            ]
        );

	}

	protected function init_control() {
		add_action( 'elementor/element/section/section_background/before_section_end', [ $this, 'softlite_register_controls' ], 10, 2 );
		add_action( 'elementor/element/container/section_background/before_section_end', [ $this, 'softlite_register_controls' ], 10, 2 );
		add_action( 'elementor/element/column/section_style/before_section_end', [ $this, 'softlite_register_controls' ], 10, 2 );
		add_action( 'elementor/element/common/_section_background/before_section_end', [ $this, 'softlite_register_controls' ], 10, 2 );
	}

}
