<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Softlite_Advanced extends \Elementor\Widget_Base {

	public function __construct() {
		parent::__construct();
		$this->init_controls();
	}

	public function get_name() {
		return 'softlite-advanced';
	}

    public function get_script_depends() {
        return ['softlite-editor-css-script'];
    }

    public function init_controls() {
        add_action('elementor/element/common/_section_responsive/after_section_end', [$this, 'softlite_register_controls'], 10, 2);
        add_action('elementor/element/section/_section_responsive/after_section_end', [$this, 'softlite_register_controls'], 10, 2);
        add_action('elementor/element/column/_section_responsive/after_section_end', [$this, 'softlite_register_controls'], 10, 2);
        add_action('elementor/element/container/_section_responsive/after_section_end', [$this, 'softlite_register_controls'], 10, 2);

        add_action('elementor/element/parse_css', [$this, 'add_post_css'], 10, 2);
        add_action('elementor/css-file/post/parse', [$this, 'add_page_settings_css']);
    }

    public function softlite_register_controls($element, $section_id) {

        if (!current_user_can('edit_pages') && !current_user_can('unfiltered_html')) {
            return;
        }

        $element->start_controls_section(
            'advanced_by_softlite_section',
            [
                'label' => esc_html__('Advanced by Softlite.io', 'softlite'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $element->add_responsive_control(
            'advanced_by_softlite_display',
            [
                'label' => esc_html__('Display', 'softlite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'softlite'),
                    'block' => esc_html__('Block', 'softlite'),
                    'inline' => esc_html__('Inline', 'softlite'),
                    'inline-block' => esc_html__('Inline Block', 'softlite'),
                    'flex' => esc_html__('Flex', 'softlite'),
                    'inline-flex' => esc_html__('Inline Flex', 'softlite'),
                    'grid' => esc_html__('Grid', 'softlite'),
                    'inline-grid' => esc_html__('Inline Grid', 'softlite'),
                    'table' => esc_html__('Table', 'softlite'),
                    'table-cell' => esc_html__('Table Cell', 'softlite'),
                    'table-row' => esc_html__('Table Row', 'softlite'),
                    'none' => esc_html__('None', 'softlite'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'display: {{VALUE}};',
                ],
            ]
        );

        $element->add_responsive_control(
            'advanced_by_softlite_position',
            [
                'label' => esc_html__('Position', 'softlite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('Default', 'softlite'),
                    'static' => esc_html__('Static', 'softlite'),
                    'relative' => esc_html__('Relative', 'softlite'),
                    'absolute' => esc_html__('Absolute', 'softlite'),
                    'fixed' => esc_html__('Fixed', 'softlite'),
                    'sticky' => esc_html__('Sticky', 'softlite'),
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'position: {{VALUE}};',
                ],
            ]
        );

        $element->add_responsive_control(
            'advanced_by_softlite_position_top',
            [
                'label' => esc_html__('Top', 'softlite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'advanced_by_softlite_position!' => '', 
                ]
            ]
        );

        $element->add_responsive_control(
            'advanced_by_softlite_position_right',
            [
                'label' => esc_html__('Right', 'softlite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'advanced_by_softlite_position!' => '', 
                ]
            ]
        );

        $element->add_responsive_control(
            'advanced_by_softlite_position_bottom',
            [
                'label' => esc_html__('Bottom', 'softlite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'advanced_by_softlite_position!' => '', 
                ]
            ]
        );

        $element->add_responsive_control(
            'advanced_by_softlite_position_left',
            [
                'label' => esc_html__('Left', 'softlite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'advanced_by_softlite_position!' => '', 
                ]
            ]
        );

        $height_control_settings = [
			'label' => esc_html__( 'Height', 'softlite' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
			'range' => [
				'px' => [
					'min' => 0,
					'max' => 2000,
				],
				'%' => [
					'min' => 0,
					'max' => 100,
				],
				'vh' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'default' => [
				'unit' => 'px',
			],
			'separator' => 'none',
		];

		$element->add_responsive_control(
			'advanced_by_softlite_height',
			array_merge( $height_control_settings, [
				'selectors' => [
					'{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
				],
			] )
		);

        $element->add_control(
            '_custom_css',
            [
                'label' => esc_html__('Custom CSS', 'softlite'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'render_type' => 'ui',
                'separator' => 'none',
                'description' => esc_html__('Use "selector" to target the element wrapper. For example: selector {font-size: 18px} or selector .child {font-size: 20px}', 'softlite'),
            ]
        );
        
        $element->end_controls_section();
    }

    public function add_post_css($post_css, $element) {
        if ($post_css instanceof \Elementor\Core\DynamicTags\Dynamic_CSS) {
            return;
        }

        $element_settings = $element->get_settings();

        $sanitize_css = $this->parse_css($element_settings, $post_css->get_element_unique_selector($element));

        $post_css->get_stylesheet()->add_raw_css($sanitize_css);
    }

    public function add_page_settings_css($post_css) {

        $document = \Elementor\Plugin::instance()->documents->get($post_css->get_post_id());

        $element_settings = $document->get_settings();

        $sanitize_css = $this->parse_css($element_settings, $document->get_css_wrapper_selector());

        $post_css->get_stylesheet()->add_raw_css($sanitize_css);
    }

    public function parse_css($element_settings, $unique_selector) {

        if (empty($element_settings['_custom_css'])) {
            return;
        }

        $custom_css = trim($element_settings['_custom_css']);

        if (empty($custom_css)) {
            return;
        }

        $custom_css = str_replace('selector', $unique_selector, $custom_css);

        return wp_strip_all_tags($custom_css);
    }
  
}
