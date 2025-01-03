<?php
/**
 * Class Date.
 */
namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Date extends Abstracts\Builder_Element{

	public function get_id() {
		return 'date';
	}

    public function get_builder_type() {
		return 'header';
	}

    public function get_label(){
        return __('Date', 'rishi');
    }

    public function config()
    {
        return array(
            'name' => $this->get_label(),
            'visibilityKey' => 'header_hide_' . $this->get_id(),
        );
    }

    /**
     * Add customizer settings for the element
     *
     * @return array get options
     */
    public function get_options(){
        $options = [
            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                'title' => __( 'General', 'rishi' ),
                'control' => ControlTypes::TAB,
                'options' => [
                    'header_hide_' . $this->get_id() => [
                        'label'               => false,
                        'control'             => ControlTypes::HIDDEN,
                        'value'               => false,
                        'disableRevertButton' => true,
                        'help'                => __('Hide', 'rishi'),
                    ],
                    'header_date_format_type' => [
                        'label'   => __('Date Format', 'rishi'),
                        'control'    => ControlTypes::INPUT_RADIO,
                        'value'   => 'format_1',
                        'view'    => 'radio',
                        'design'  => 'block',
                        'divider' => 'bottom',
                        'attr'    => [ 'data-columns' => '1'],
                        'choices' => [
                            'format_1' => esc_html( date_i18n( 'l, F j, Y' ) ),
                            'format_2' => esc_html( date_i18n('F j, Y') ),
                            'format_3' => esc_html( date_i18n('m-d-Y' ) ),
                            'format_4' => esc_html( date_i18n('m/d/Y' ) ),
                            'format_5' => __( 'Custom', 'rishi' )
                        ],
                    ],

                    'header_date_format_custom' => [
                        'label'      => __('Custom', 'rishi'),
                        'control'    => ControlTypes::INPUT_TEXT,
                        'design'     => 'block',
                        'value'      => __('Y-m-d', 'rishi'),
                        'conditions' => ['header_date_format_type' => 'format_5'],
                        'divider'    => 'bottom',
                    ],

                    'header_date_ed_icon' => [
                        'label'   => __('Show Icon', 'rishi'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'bottom',

                    ],
                    'header_date_icon_size'  => [
                        'label'      => __( 'Icon Size', 'rishi' ),
                        'control'    => ControlTypes::INPUT_SLIDER,
                        'value'      => '18px',
                        'divider'    => 'bottom',
                        'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
                            [ 'unit' => 'px', 'min' => 0, 'max' => 50 ],
                        ] ),
                        'conditions' => [
                            'header_date_ed_icon' => 'yes'
                        ]
                    ],
                ]
            ],

            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                'title' => __( 'Design', 'rishi' ),
                'control' => ControlTypes::TAB,
                'options' => [
                    'headerDateFont' => rishi_typography_control_option([
                        'control' => ControlTypes::TYPOGRAPHY,
                        'label'   => __('Font', 'rishi'),
                        'divider' => 'bottom',
                        'value'   => \Rishi\Customizer\Helpers\Defaults::typography_value([
							'size'            => array(
								'desktop' => '18px',
								'tablet'  => '18px',
								'mobile'  => '18px',
							),
                        ]),
                        'design' => 'inline',
                    ]),

                    'text_color_group' => [
                        'label'   => __('Text Color', 'rishi'),
                        'control' => ControlTypes::CONTROLS_GROUP,
                        'divider' => 'bottom',
                        'value'      => [
							'headerDateColor' => [
								'default'      => [
									'color' => 'var(--paletteColor1)',
								]
							],
						],
                        'settings' => [
							'headerDateColor'    => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::COLOR_PICKER,
								'design'     => 'inline',
								'colorPalette'	  => true,
								'responsive' => false,
								'pickers'    => [
									[
										'title'   => __( 'Initial', 'rishi' ),
										'id'      => 'default',
									],
								],
                                'value'      => [
                                    'default'      => [
                                        'color' => 'var(--paletteColor1)',
                                    ]
                                ]
							],
						]
                    ],

                    'icon_color_group' => [
                        'label'   => __('Icon Color', 'rishi'),
                        'control' =>  ControlTypes::CONTROLS_GROUP,
                        'divider' => 'bottom',
                        'conditions' => [
                            'header_date_ed_icon' => 'yes'
                        ],
                        'value'      => [
							'headerDateIconColor' => [
								'default'      => [
									'color' => 'var(--paletteColor1)',
								]
							],
						],
                        'settings' => [
							'headerDateIconColor'    => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::COLOR_PICKER,
								'design'     => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'pickers'    => [
									[
										'title'   => __( 'Initial', 'rishi' ),
										'id'      => 'default',
									],
								],
                                'value' => [
                                    'default'      => [
                                        'color' => 'var(--paletteColor1)',
                                    ]
                                ],
							],
						]
                    ],
                ]
            ]
        ];
        return $options;
    }

    /**
     * Write logic for dynamic css change for the elements
     *
     * @return array dynamic styles
     */
    public function dynamic_styles(){
		$header_default        = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$text_color_group      = $this->get_mod_value( 'text_color_group',[
            'headerDateColor' => $header_default['headerDateColor'],
        ]);
		$headerDateColor  = $text_color_group['headerDateColor'];

        $icon_color_group      = $this->get_mod_value( 'icon_color_group',[
            'headerDateIconColor'  => $header_default['headerDateIconColor'],
        ]);
		$headerDateIconColor  = $icon_color_group['headerDateIconColor'];

		$header_date_icon_size = $this->get_mod_value( 'header_date_icon_size', $header_default['header_date_icon_size'] );
		$dateFont              = $this->get_mod_value( 'headerDateFont', $header_default['headerDateFont'] );

        $options =  array(
            'header_date_icon_size'      => [
                'selector'     => '.header-date-section',
                'variableName' => 'icon-size',
                'value'        => $header_date_icon_size,
                'unit'         => '',
                'responsive'   => false,
                'type'         => 'slider',
            ],
            'headerDateColor' => array(
                'value'     => $headerDateColor,
                'type'      => 'color',
                'default'   => array(
                    'default' => array( 'color' => 'var(--paletteColor1)' ),
                ),
                'variables' => array(
                    'default' => array(
                        'variable' => 'headerDateInitialColor',
                        'selector' => '.header-date-section',
                    ),
                ),
            ),
            'headerDateIconColor' => array(
                'value'     => $headerDateIconColor,
                'type'      => 'color',
                'default'   => array(
                    'default' => array( 'color' => 'var(--paletteColor1)' ),
                ),
                'variables' => array(
                    'default' => array(
                        'variable' => 'headerDateInitialIconColor',
                        'selector' => '.header-date-section',
                    ),
                ),
            ),
            'headerDateFont' => array(
                'value'      => $dateFont,
                'selector'   => '.header-date-section',
                'type'       => 'typography'
            ),
        );

        return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
    }

    /**
     * Renders function
     * @param string $desktop
     * @return void
     */
    public function render( $device = 'desktop'){

        $date_format_type = $this->get_mod_value( 'header_date_format_type', 'format_1' );
        $ed_icon          = $this->get_mod_value( 'header_date_ed_icon', 'no' );
        $custom_format    = $this->get_mod_value( 'header_date_format_custom', 'Y-m-d' );

        ?>
        <div id="rishi-date">
            <div class="header-date-section">
                <?php if( $ed_icon == 'yes' ){ 
                /**
                 * Note to code reviewers: It contains inline SVG, which is absolutely safe and this line doesn't need to be escaped.
                 */
                ?>
                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 1C8.55229 1 9 1.44772 9 2V3H15V2C15 1.44772 15.4477 1 16 1C16.5523 1 17 1.44772 17 2V3H19C20.6569 3 22 4.34315 22 6V20C22 21.6569 20.6569 23 19 23H5C3.34315 23 2 21.6569 2 20V6C2 4.34315 3.34315 3 5 3H7V2C7 1.44772 7.44772 1 8 1ZM20 11V20C20 20.5523 19.5523 21 19 21H5C4.44772 21 4 20.5523 4 20V11H20Z" /></svg>
                <?php } ?>
                <span class="rishi-date">
                    <?php if( $date_format_type == 'format_1' ){
                        echo esc_html( date_i18n( 'l, F j, Y' ) );
                    }elseif( $date_format_type == 'format_2' ){
                        echo esc_html( date_i18n('F j, Y') );
                    }elseif( $date_format_type == 'format_3' ){
                        echo esc_html( date_i18n('m-d-Y' ) );
                    }elseif( $date_format_type == 'format_4' ){
                        echo esc_html( date_i18n('m/d/Y' ) );
                    }elseif( $date_format_type == 'format_5' ){
                        echo esc_html( date_i18n( $custom_format ) );
                    }
                    ?>
                </span>
            </div>
        </div>
        <?php
    }
}
