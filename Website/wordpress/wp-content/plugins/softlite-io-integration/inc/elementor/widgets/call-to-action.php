<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Softlite_Call_To_Action extends \Elementor\Widget_Base {
    public function get_name() {
		return 'softlite-call-to-action';
	}

	public function get_title() {
		return esc_html__( 'Softlite.io Call to Action', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_keywords() {
		return [ 'call to action', 'cta', 'button' ];
	}

    public function get_style_depends() {
		return [ 
			'softlite-integration-widget-style'
		];
	}

	public function get_categories() {
		return [ 'softlite' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'elementor-pro' ),
			]
		);

		// $this->add_control(
		// 	'skin',
		// 	[
		// 		'label' => esc_html__( 'Skin', 'elementor-pro' ),
		// 		'type' => \Elementor\Controls_Manager::SELECT,
		// 		'options' => [
		// 			'classic' => esc_html__( 'Classic', 'elementor-pro' ),
		// 			'cover' => esc_html__( 'Cover', 'elementor-pro' ),
		// 		],
		// 		'render_type' => 'template',
		// 		'prefix_class' => 'softlite-cta--skin-',
		// 		'default' => 'classic',
		// 	]
		// );

		$this->add_responsive_control(
			'layout',
			[
				'label' => esc_html__( 'Position', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'top' => [
						'title' => esc_html__( 'Top', 'elementor-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-h-align-right',
					],
                    'bottom' => [
						'title' => esc_html__( 'Bottom', 'elementor-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
                'default' => 'top',
				'prefix_class' => 'softlite-cta-%s-layout-image-',
			]
		);

        $this->add_control(
			'graphic_element',
			[
				'label' => esc_html__( 'Graphic Element', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'elementor-pro' ),
					'image' => esc_html__( 'Image', 'elementor-pro' ),
                    'svg' => esc_html__( 'SVG', 'elementor-pro' ),
					'icon' => esc_html__( 'Icon', 'elementor-pro' ),
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'graphic_element' => 'image',
				],
				'show_label' => false,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Actually its `image_size`
				'default' => 'thumbnail',
				'condition' => [
					'graphic_element' => 'image',
					'image[id]!' => '',
				],
			]
		);

        $this->add_control(
            'svg',
            [
                'label' => esc_html__( 'Paste SVG code here', 'softlite' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
                'condition' => [
					'graphic_element' => 'svg',
				],
            ]
        );

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_view',
			[
				'label' => esc_html__( 'View', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'elementor-pro' ),
					'stacked' => esc_html__( 'Stacked', 'elementor-pro' ),
					'framed' => esc_html__( 'Framed', 'elementor-pro' ),
				],
				'default' => 'default',
				'condition' => [
					'graphic_element' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => esc_html__( 'Shape', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'circle' => esc_html__( 'Circle', 'elementor-pro' ),
					'square' => esc_html__( 'Square', 'elementor-pro' ),
				],
				'default' => 'circle',
				'condition' => [
					'icon_view!' => 'default',
					'graphic_element' => 'icon',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'text_1',
			[
				'label' => esc_html__( 'Text 1', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'text_1_tag',
			[
				'label' => esc_html__( 'Text 1 Tag', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
				],
				'default' => 'h2',
				'condition' => [
					'text_1!' => '',
				],
			]
		);

		$this->add_control(
			'text_2',
			[
				'label' => esc_html__( 'Text 2', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
                'separator' => 'before',
			]
		);

		$this->add_control(
			'text_2_tag',
			[
				'label' => esc_html__( 'Text 2 Tag', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
				],
                'default' => 'h2',
				'condition' => [
					'text_2!' => '',
				],
			]
		);

        $this->add_control(
			'text_3',
			[
				'label' => esc_html__( 'Text 3', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
                'separator' => 'before',
			]
		);

		$this->add_control(
			'text_3_tag',
			[
				'label' => esc_html__( 'Text 3 Tag', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
				],
                'default' => 'h2',
				'condition' => [
					'text_3!' => '',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
			'section_buttons',
			[
				'label' => esc_html__( 'Buttons', 'elementor-pro' ),
			]
		);

        $this->add_control(
			'buttons_link_click',
			[
				'label' => esc_html__( 'Apply Link On', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'box' => esc_html__( 'Whole Box', 'elementor-pro' ),
					'button' => esc_html__( 'Button Only', 'elementor-pro' ),
				],
				'default' => 'button',
				'separator' => 'none',
				'condition' => [
                    'first_button!' => '',
					'first_button_link[url]!' => '',
				],
			]
		);

        $this->add_responsive_control(
			'buttons_layout',
			[
				'label' => esc_html__( 'Position', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-h-align-right',
					],
                    'bottom' => [
						'title' => esc_html__( 'Bottom', 'elementor-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
                'default' => 'bottom',
				'prefix_class' => 'softlite-cta-%s-layout-buttons-',
                'condition' => [
                    'first_button!' => '',
					'first_button_link[url]!' => '',
				],
			]
		);

        $this->add_control(
			'first_button',
			[
				'label' => esc_html__( 'First Button', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'elementor-pro' ),
				'label_off' => esc_html__( 'Off', 'elementor-pro' ),
				'return_value' => 'on',
			]
		);

        $this->add_control(
			'first_button_text',
			[
				'label' => esc_html__( 'Text', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => [
					'first_button!' => '',
				],
			]
		);

        $this->add_control(
			'first_button_link',
			[
				'label' => esc_html__( 'Link', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-pro' ),
                'condition' => [
					'first_button!' => '',
				],
			]
		);

        $this->add_control(
			'second_button',
			[
				'label' => esc_html__( 'Second Button', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'elementor-pro' ),
				'label_off' => esc_html__( 'Off', 'elementor-pro' ),
				'return_value' => 'on',
			]
		);

        $this->add_control(
			'second_button_text',
			[
				'label' => esc_html__( 'Text', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'condition' => [
					'second_button!' => '',
				],
			]
		);

        $this->add_control(
			'second_button_link',
			[
				'label' => esc_html__( 'Link', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-pro' ),
                'condition' => [
					'second_button!' => '',
				],
			]
		);

        $this->add_responsive_control(
			'second_button_alignment',
			[
				'label' => esc_html__( 'Second Button Alignment', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'vertical' => [
						'title' => esc_html__( 'Vertical', 'elementor-pro' ),
						'icon' => 'eicon-navigation-vertical',
					],
                    'horizontal' => [
						'title' => esc_html__( 'Horizontal', 'elementor-pro' ),
						'icon' => 'eicon-navigation-horizontal',
					],
				],
				'prefix_class' => 'softlite-cta-%s-layout-button-',
                'condition' => [
					'second_button!' => '',
				],
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'elementor-pro' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'image[url]!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'image_min_width',
			[
				'label' => esc_html__( 'Width', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__bg-wrapper' => 'min-width: {{SIZE}}{{UNIT}}',
				],
                'condition' => [
					'image[url]!' => '',
				],
			]
		);

		// $this->add_responsive_control(
		// 	'image_min_height',
		// 	[
		// 		'label' => esc_html__( 'Height', 'elementor-pro' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', 'em', 'rem', 'vh', 'custom' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 500,
		// 			],
		// 			'vh' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .softlite-cta__bg-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
		// 		],
        //         'condition' => [
		// 			'image[url]!' => '',
		// 		],
		// 	]
		// );

        $this->add_control(
			'image_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'border-color: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
			'image_border_width',
			[
				'label' => esc_html__( 'Border Width', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 20,
					],
					'em' => [
						'max' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'elementor-pro' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				// 'conditions' => [
				// 	'relation' => 'or',
				// 	'terms' => [
				// 		[
				// 			'name' => 'text_1',
				// 			'operator' => '!==',
				// 			'value' => '',
				// 		],
				// 		[
				// 			'name' => 'text_2',
				// 			'operator' => '!==',
				// 			'value' => '',
				// 		],
                //         [
				// 			'name' => 'text_3',
				// 			'operator' => '!==',
				// 			'value' => '',
				// 		],
				// 	],
				// ],
			]
		);

        $this->add_responsive_control(
			'min-height',
			[
				'label' => esc_html__( 'Height', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'vh', 'custom' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__content' => 'min-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__content' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'vertical_position',
			[
				'label' => esc_html__( 'Vertical Position', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'elementor-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'elementor-pro' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'elementor-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'softlite-cta--valign-',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'text_1_title',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => esc_html__( 'Text 1', 'elementor-pro' ),
				'condition' => [
					'text_1!' => '',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_1_typography',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .softlite-cta__title',
				'condition' => [
					'title_1!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'text_1_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'text_1!' => '',
				],
			]
		);

		$this->add_control(
			'text_2_title',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => esc_html__( 'Text 2', 'elementor-pro' ),
                'separator' => 'before',
				'condition' => [
					'text_2!' => '',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_2_typography',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .softlite-cta__title',
				'condition' => [
					'title_2!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'text_2_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'text_2!' => '',
				],
			]
		);

        $this->add_control(
			'text_3_title',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => esc_html__( 'Text 3', 'elementor-pro' ),
                'separator' => 'before',
				'condition' => [
					'text_3!' => '',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_3_typography',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .softlite-cta__title',
				'condition' => [
					'title_3!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'text_3_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'text_3!' => '',
				],
			]
		);

		$this->add_control(
			'heading_content_colors',
			[
				'type' => \Elementor\Controls_Manager::HEADING,
				'label' => esc_html__( 'Colors', 'elementor-pro' ),
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'color_tabs' );

		$this->start_controls_tab( 'colors_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_1_color',
			[
				'label' => esc_html__( 'Text 1 Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_1!' => '',
				],
			]
		);

		$this->add_control(
			'text_2_color',
			[
				'label' => esc_html__( 'Text 2 Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_2!' => '',
				],
			]
		);

        $this->add_control(
			'text_3_color',
			[
				'label' => esc_html__( 'Text 3 Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_3!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'colors_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'content_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta:hover .softlite-cta__content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_1_color_hover',
			[
				'label' => esc_html__( 'Text 1 Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta:hover .softlite-cta__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_1!' => '',
				],
			]
		);

		$this->add_control(
			'text_2_color_hover',
			[
				'label' => esc_html__( 'Text 2 Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta:hover .softlite-cta__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_2!' => '',
				],
			]
		);

        $this->add_control(
			'text_3_color_hover',
			[
				'label' => esc_html__( 'Text 3 Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta:hover .softlite-cta__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'text_3!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'first_button_style',
			[
				'label' => esc_html__( 'First Button', 'elementor-pro' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'first_button!' => '',
				],
			]
		);

        $this->add_control(
			'first_button_width',
			[
				'label' => esc_html__( 'Width', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'first_button_size',
			[
				'label' => esc_html__( 'Size', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => [
					'xs' => esc_html__( 'Extra Small', 'elementor-pro' ),
					'sm' => esc_html__( 'Small', 'elementor-pro' ),
					'md' => esc_html__( 'Medium', 'elementor-pro' ),
					'lg' => esc_html__( 'Large', 'elementor-pro' ),
					'xl' => esc_html__( 'Extra Large', 'elementor-pro' ),
				],
			]
		);

        $this->add_responsive_control(
			'first_button_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'first_button_typography',
				'selector' => '{{WRAPPER}} .softlite-cta__button',
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'first_button_text_shadow',
				'selector' => '{{WRAPPER}} .softlite-cta__button',
			]
		);

		$this->start_controls_tabs( 'first_button_tabs' );

		$this->start_controls_tab( 'first_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'first_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'first_button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'first_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'first_button-hover',
			[
				'label' => esc_html__( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'first_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'first_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'first_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'first_button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 20,
					],
					'em' => [
						'max' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'first_button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .softlite-cta__button',
			]
		);

		$this->add_responsive_control(
			'first_button_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'second_button_style',
            [
                'label' => esc_html__( 'Second Button', 'elementor-pro' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'second_button!' => '',
                ],
            ]
        );

        $this->add_control(
			'second_button_width',
			[
				'label' => esc_html__( 'Width', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .softlite-cta__button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'second_button_size',
            [
                'label' => esc_html__( 'Size', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => [
                    'xs' => esc_html__( 'Extra Small', 'elementor-pro' ),
                    'sm' => esc_html__( 'Small', 'elementor-pro' ),
                    'md' => esc_html__( 'Medium', 'elementor-pro' ),
                    'lg' => esc_html__( 'Large', 'elementor-pro' ),
                    'xl' => esc_html__( 'Extra Large', 'elementor-pro' ),
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'second_button_typography',
                'selector' => '{{WRAPPER}} .softlite-cta__button',
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Typography::TYPOGRAPHY_ACCENT,
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'second_button_text_shadow',
                'selector' => '{{WRAPPER}} .softlite-cta__button',
            ]
        );
        
        $this->start_controls_tabs( 'second_button_tabs' );
        
        $this->start_controls_tab( 'second_button_normal',
            [
                'label' => esc_html__( 'Normal', 'elementor-pro' ),
            ]
        );
        
        $this->add_control(
            'second_button_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'second_button_background_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'second_button_border_color',
            [
                'label' => esc_html__( 'Border Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'second_button-hover',
            [
                'label' => esc_html__( 'Hover', 'elementor-pro' ),
            ]
        );
        
        $this->add_control(
            'second_button_hover_text_color',
            [
                'label' => esc_html__( 'Text Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'second_button_hover_background_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'second_button_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
            'second_button_border_width',
            [
                'label' => esc_html__( 'Border Width', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'range' => [
                    'px' => [
                        'max' => 20,
                    ],
                    'em' => [
                        'max' => 2,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'second_button_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .softlite-cta__button',
            ]
        );
        
        $this->add_responsive_control(
            'second_button_padding',
            [
                'label' => esc_html__( 'Padding', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
                'selectors' => [
                    '{{WRAPPER}} .softlite-cta__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        
        $this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$wrapper_tag = 'div';
		$button_tag = 'a';
		$text_1_tag = $settings['text_1_tag'] ? $settings['text_1_tag'] : 'h2';
		$text_2_tag = $settings['text_2_tag'] ? $settings['text_2_tag'] : 'div';
        $text_3_tag = $settings['text_3_tag'] ? $settings['text_3_tag'] : 'div';
		$image = '';

		if ( ! empty( $settings['image']['id'] ) ) {
			$image = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], 'image', $settings );
		} elseif ( ! empty( $settings['image']['url'] ) ) {
			$image = $settings['image']['url'];
		}

		$this->add_render_attribute( 'wrapper', 'class', 'softlite-cta__wrapper' );

		$this->add_render_attribute( 'text_one', 'class', [
			'softlite-cta__text-one',
		] );

		$this->add_render_attribute( 'text_two', 'class', [
			'softlite-cta__text-two',
		] );

        $this->add_render_attribute( 'text_three', 'class', [
			'softlite-cta__text-three',
		] );

		$this->add_render_attribute( 'first_button', 'class', [
			'softlite-cta__button',
			'elementor-button',
			'elementor-size-' . $settings['first_button_size'],
		] );

        $this->add_render_attribute( 'second_button', 'class', [
			'softlite-cta__button',
			'elementor-button',
			'elementor-size-' . $settings['second_button_size'],
		] );

		if ( 'icon' === $settings['graphic_element'] ) {
			$this->add_render_attribute( 'graphic_element', 'class',
				[
					'elementor-icon-wrapper',
					'softlite-cta__icon',
				]
			);
			$this->add_render_attribute( 'graphic_element', 'class', 'elementor-view-' . $settings['icon_view'] );
			if ( 'default' != $settings['icon_view'] ) {
				$this->add_render_attribute( 'graphic_element', 'class', 'elementor-shape-' . $settings['icon_shape'] );
			}

			if ( ! isset( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
				// add old default
				$settings['icon'] = 'fa fa-star';
			}

			if ( ! empty( $settings['icon'] ) ) {
				$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
			}
		} elseif ( 'image' === $settings['graphic_element'] && ! empty( $settings['image']['url'] ) ) {
			$this->add_render_attribute( 'graphic_element', 'class', 'softlite-cta__image' );
		}

		if ( ! empty( $settings['first_button'] ) && ! empty( $settings['first_button_link']['url'] ) ) {
			$link_element = 'first_button';

			if ( 'box' === $settings['buttons_link_click'] ) {
				$wrapper_tag = 'a';
				$button_tag = 'span';
				$link_element = 'wrapper';
			}

			$this->add_link_attributes( $link_element, $settings['first_button_link'] );
		}

        if ( ! empty( $settings['second_button'] ) && ! empty( $settings['second_button_link']['url'] ) ) {
			$link_element = 'second_button';

			if ( 'box' === $settings['buttons_link_click'] ) {
				$wrapper_tag = 'a';
				$button_tag = 'span';
				$link_element = 'wrapper';
			} else {
                $this->add_link_attributes( $link_element, $settings['second_button_link'] );
            }
		}

		// $this->add_inline_editing_attributes( 'text_one' );
		// $this->add_inline_editing_attributes( 'text_two' );
        // $this->add_inline_editing_attributes( 'text_three' );
		// $this->add_inline_editing_attributes( 'first_button' );
        // $this->add_inline_editing_attributes( 'second_button' );

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();

		?>
		<<?php \Elementor\Utils::print_validated_html_tag( $wrapper_tag ); ?> <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			
            <?php if ( 'image' === $settings['graphic_element'] && ! empty( $settings['image']['url'] ) ) : ?>
                <div <?php $this->print_render_attribute_string( 'graphic_element' ); ?>>
                    <?php \Elementor\Group_Control_Image_Size::print_attachment_image_html( $settings, 'image' ); ?>
                </div>
            <?php elseif ( 'icon' === $settings['graphic_element'] && ( ! empty( $settings['icon'] ) || ! empty( $settings['selected_icon'] ) ) ) : ?>
                <div <?php $this->print_render_attribute_string( 'graphic_element' ); ?>>
                    <div class="elementor-icon">
                        <?php if ( $is_new || $migrated ) :
                            \Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
                        else : ?>
                            <i <?php $this->print_render_attribute_string( 'icon' ); ?>></i>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ( 'svg' === $settings['graphic_element'] && ! empty( $settings['svg'] ) ) : ?>
                <div <?php $this->print_render_attribute_string( 'graphic_element' ); ?>>
                    <?php echo $settings['svg']; ?>
                </div>
            <?php endif; ?>
            
            <div class="softlite-cta__content">
                <div class="softlite-cta__content-inner">
                    <?php if ( ! empty( $settings['text_1'] ) ) : ?>
                        <<?php \Elementor\Utils::print_validated_html_tag( $text_1_tag ); ?> <?php $this->print_render_attribute_string( 'text_one' ); ?>>
                            <?php $this->print_unescaped_setting( 'text_1' ); ?>
                        </<?php \Elementor\Utils::print_validated_html_tag( $text_1_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( ! empty( $settings['text_2'] ) ) : ?>
                        <<?php \Elementor\Utils::print_validated_html_tag( $text_2_tag ); ?> <?php $this->print_render_attribute_string( 'text_two' ); ?>>
                            <?php $this->print_unescaped_setting( 'text_2' ); ?>
                        </<?php \Elementor\Utils::print_validated_html_tag( $text_2_tag ); ?>>
                    <?php endif; ?>
                
                    <?php if ( ! empty( $settings['text_3'] ) ) : ?>
                        <<?php \Elementor\Utils::print_validated_html_tag( $text_3_tag ); ?> <?php $this->print_render_attribute_string( 'text_three' ); ?>>
                            <?php $this->print_unescaped_setting( 'text_3' ); ?>
                        </<?php \Elementor\Utils::print_validated_html_tag( $text_3_tag ); ?>>
                    <?php endif; ?>
                </div>
                
                <?php if ( ! empty( $settings['first_button'] ) ) : ?>
                    <div class="softlite-cta__button-wrapper">
                        <div class="softlite-cta__button-item softlite-cta__button-item--first">
                            <<?php \Elementor\Utils::print_validated_html_tag( $button_tag ); ?> <?php $this->print_render_attribute_string( 'first_button' ); ?>>
                                <?php $this->print_unescaped_setting( 'first_button_text' ); ?>
                            </<?php \Elementor\Utils::print_unescaped_internal_string( $button_tag ); ?>>
                        </div>
                        <?php if ( ! empty( $settings['second_button'] ) ) : ?>
                            <div class="softlite-cta__button-item softlite-cta__button-item--second">
                                <<?php \Elementor\Utils::print_validated_html_tag( $button_tag ); ?> <?php $this->print_render_attribute_string( 'second_button' ); ?>>
                                    <?php $this->print_unescaped_setting( 'second_button_text' ); ?>
                                </<?php \Elementor\Utils::print_unescaped_internal_string( $button_tag ); ?>>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

		</<?php \Elementor\Utils::print_validated_html_tag( $wrapper_tag ); ?>>
		<?php
	}

	/**
	 * Render Call to Action widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
	}
}