<?php

/**
 * Class Offcanvas.
 */

namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts;

/**
 * Class Offcanvas
 */
class Offcanvas extends Abstracts\Builder_Element{

    private $keymods;
	public function get_id() {
		return 'offcanvas';
	}

    public function get_builder_type() {
		return 'header';
	}

    public function get_label(){
        return __('Offcanvas', 'rishi');
    }

    public function config()
    {
        return array(
            'name' => $this->get_label(),
        );
    }

	public function is_row_element()  {
		return true;
	}

    /**
     * Add customizer settings for the element
     *
     * @return void
     */
    public function get_options(){
        $options = [
            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                'title' => __('General', 'rishi'),
                'control' => ControlTypes::TAB,
                'options' => [
                    'side_panel_position' => [
                        'label' => __('Direction', 'rishi'),
                        'control' => ControlTypes::INPUT_RADIO,
                        'value' => 'right',
                        'view' => 'text',
                        'design' => 'block',
						'divider' => 'bottom',
                        'choices' => [
                            'left' => __('Left Side', 'rishi'),
                            'right' => __('Right Side', 'rishi'),
                        ],
                    ],
                    'close_btn_size'  => [
						'label'      => __( 'Close Button Size', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'divider' => 'bottom',
						'value'      => '30px',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 10, 'max' => 500 ],
						] ),
					],
                    'offcanvasContentAlignment' => [
                        'control'    => ControlTypes::INPUT_RADIO,
                        'label'      => __('Horizontal Alignment', 'rishi'),
                        'view'       => 'text',
                        'design'     => 'block',
                        'divider' => 'bottom',
                        'responsive' => false,
                        'attr'       => ['data-type' => 'horizontal-alignment'],
                        'value'   => 'flex-start',
                        'choices' => [
                            'flex-start' => 'Left',
                            'center'     => 'Center',
                            'flex-end'   => 'Right',
                        ],
                    ],
                    'offcanvasItemSpacing'  => [
						'label'      => __( 'Item Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '30px',
						'divider'    => 'bottom',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => 'px', 'min' => 0, 'max' => 500 ],
								] ),
						'help'       => __( 'Set the item spacing.', 'rishi' ),
					],
                ],
            ],
            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                'title' => __('Design', 'rishi'),
                'control' => ControlTypes::TAB,
                'options' => [

                    'offcanvasBackground' => [
                        'label' => __('Background', 'rishi'),
                        'control'  => ControlTypes::COLOR_PICKER,
                        'design' => 'inline',
						'colorPalette'	  => true,
						'divider' => 'bottom',
                        'responsive' => false,
                        'value' => array(
                            'default' => [
                                'color' => 'var(--paletteColor5)',
                            ]
                        ),
                        'pickers' => [
                            [
                                'title' => __('Initial', 'rishi'),
                                'id' => 'default',
                            ]
                        ]
                    ],

                    'bottomBorderColor' => [
                        'label' => __('Bottom Border Color', 'rishi'),
                        'control'  => ControlTypes::COLOR_PICKER,
                        'design' => 'inline',
						'divider' => 'bottom',
						'colorPalette'	  => true,
                        'responsive' => false,
                        'value' => array(
                            'default' => [
                                'color' => 'var(--paletteColor5)',
                            ]
                        ),
                        'pickers' => [
                            [
                                'title' => __('Initial', 'rishi'),
                                'id' => 'default',
                            ]
                        ]
                    ],

                    'menu_close_button_color' => [
                        'label' => __('Close Button Color', 'rishi'),
                        'control'  => ControlTypes::COLOR_PICKER,
                        'design' => 'inline',
						'divider' => 'bottom',
						'colorPalette'	  => true,
                        'skipEditPalette' => true,
                        'value' => [
                            'default' => [
                                'color' => 'var(--paletteColor3)',
                            ],

                            'hover' => [
                                'color' => 'var(--paletteColor2)',
                            ],
                        ],

                        'pickers' => [
                            [
                                'title' => __('Initial', 'rishi'),
                                'id' => 'default',
                                'inherit' => 'rgba(255, 255, 255, 0.7)'
                            ],

                            [
                                'title' => __('Hover', 'rishi'),
                                'id' => 'hover',
                                'inherit' => '#ffffff'
                            ],
                        ],
                    ],
                ]
            ]
        ];
        return $options;
    }

    /**
     * Write logic for dynamic css change for the elements
     *
     * @return
     */
    public function dynamic_styles(){
        $row_default   = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults()['offcanvas'];
        $custom_width  = $this->get_mod_value( 'close_btn_size', $row_default['close_btn_size']  );
        $alignment     = $this->get_mod_value( 'offcanvasContentAlignment', $row_default['offcanvasContentAlignment']  );
        $bg_color      = $this->get_mod_value( 'offcanvasBackground', $row_default['offcanvasBackground'] );
        $bottom_border = $this->get_mod_value( 'bottomBorderColor', $row_default['bottomBorderColor'] );
        $close_btn     = $this->get_mod_value( 'menu_close_button_color', $row_default['menu_close_button_color'] );
		$item_gap      = $this->get_mod_value( 'offcanvasItemSpacing', $row_default['offcanvasItemSpacing'] );

		return array(
			'close_btn_size'     => array(
				'selector'     => '#rishi-offcanvas',
				'variableName' => 'closeBtnSize',
				'value'        => $custom_width,
				'responsive'   => false,
				'type'         => 'slider'
			),
			'offcanvasBackground'      => array(
				'value'     => $bg_color,
                'type'      => 'color',
				'default'   => $row_default['offcanvasBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '#rishi-offcanvas',
					)
				),
			),
			'bottomBorderColor'      => array(
				'value'     => $bottom_border,
                'type'      => 'color',
				'default'   => $row_default['bottomBorderColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'border-color',
						'selector' => '#rishi-offcanvas',
					)
				),
			),
			'menu_close_button_color'      => array(
				'value'     => $close_btn,
                'type'      => 'color',
				'default'   => $row_default['menu_close_button_color'],
				'variables' => array(
					'default' => array(
						'variable' => 'close-btn-color-init',
						'selector' => '#rishi-offcanvas',
                    ),
					'hover' => array(
						'variable' => 'close-btn-color-hover',
						'selector' => '#rishi-offcanvas',
					)
				),
			),
            'offcanvasContentAlignment' => array(
                'selector'     => '#rishi-offcanvas .rishi-drawer-wrapper',
                'variableName' => 'horizontal-alignment',
                'unit'         => '',
                'value'        => $alignment,
                'type'         => 'alignment'
            ),
            'offcanvasItemSpacing'     => array(
				'selector'     => '#rishi-offcanvas',
				'variableName' => 'item-gap',
				'value'        => $item_gap,
				'type'         => 'slider'
			),
		);
    }

    /**
     * Renders function
     *
     * @param string $device
     * @return void
     */
    public function render( $device = 'desktop'){
    }
}
