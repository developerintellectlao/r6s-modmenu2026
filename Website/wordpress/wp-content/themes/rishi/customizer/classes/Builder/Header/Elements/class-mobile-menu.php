<?php
/**
 * Class Mobile_Menu.
 */

namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts;

/**
 * Class Mobile_Menu
 */
class Mobile_Menu extends Abstracts\Builder_Element
{
	public function get_id() {
		return 'mobile-menu';
	}

    public function get_builder_type() {
		return 'header';
	}

    public function get_label()
    {
        return __('Mobile Menu', 'rishi');
    }

    public function config()
    {
        return array(
            'name' => $this->get_label(),
            'visibilityKey' => 'header_hide_' . $this->get_id(),
            'allowed_in' => ['offcanvas'],
            'devices'    => ['mobile']
        );
    }

    /**
     * Add customizer settings for the element
     *
     * @return void
     */
    public function get_options(){
        $location = __('Mobile Menu', 'rishi');

        $options = [
            'header_hide_' . $this->get_id() => [
                'label'               => false,
                'control'             => ControlTypes::HIDDEN,
                'value'               => false,
                'disableRevertButton' => true,
                'help'                => __('Hide', 'rishi'),
            ],
            'footer_menu' => [
                'label' => __('Select Menu', 'rishi'),
                'control' => ControlTypes::INPUT_SELECT,
                'value' => 'rishi_customizer_locations',
                'view' => 'text',
                'design' => 'block',
                'placeholder' => __('Select menu...', 'rishi'),
                'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys( \Rishi\Customizer\Helpers\Basic::get_menu_list($location)),
                'help' => sprintf(
                    // translators: placeholder here means the actual URL.
                    __('Manage your menus in the %1$sMenus screen%2$s.', 'rishi'),
                    sprintf(
                        '<a href="%s" target="_blank">',
                        admin_url('/nav-menus.php')
                    ),
                    '</a>'
                ),
            ],

            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                    'title' => __('General', 'rishi'),
                    'control' => ControlTypes::TAB,
                    'options' => [

                        'mobile_menu_type' => [
                            'label' => __('Menu Type', 'rishi'),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value' => 'type-1',
                            'view' => 'text',
                            'design' => 'block',
                            'choices' => [
                                'type-1' => __('Default', 'rishi'),
                                'type-2' => __('Bordered', 'rishi'),
                            ],
                        ],

                    ],
                ],

            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                    'title' => __('Design', 'rishi'),
                    'control' => ControlTypes::TAB,
                    'options' => [

                        'mobileMenuFont' => rishi_typography_control_option([
                            'control' => ControlTypes::TYPOGRAPHY,
                            'label' => __('Font', 'rishi'),
                            'value' => \Rishi\Customizer\Helpers\Defaults::typography_value( [
								'size'            => array(
									'desktop' => '30px',
									'tablet'  => '20px',
									'mobile'  => '16px',
								),
                            ] ),
                        ]),

                        'mobileMenuColor' => [
                            'label' => __('Font Color', 'rishi'),
                            'control'  => ControlTypes::COLOR_PICKER,
                            'design' => 'inline',
							'colorPalette'	  => true,
                            'value' => [
                                'default' => [
                                    'color' =>  'var(--paletteColor1)',
                                ],

                                'hover' => [
                                    'color' =>  'var(--paletteColor3)',
                                ],
                            ],

                            'pickers' => [
                                [
                                    'title' => __('Initial', 'rishi'),
                                    'id' => 'default',
                                ],

                                [
                                    'title' => __('Hover', 'rishi'),
                                    'id' => 'hover',
                                    'inherit' => 'var(--linkHoverColor)'
                                ],
                            ],
                        ],

                        'mobileMenuChildSize' => [
                            'label' => __('Dropdown Font Size', 'rishi'),
                            'control' => ControlTypes::INPUT_SLIDER,
                            'value' => '14px',
                            'divider' => 'top',
                            'units' => [
                                ['unit' => 'px', 'min' => 0, 'max' => 100],
                            ],
						    'responsive' => false,
                        ],
                        'mobile_menu_divider' => [
                            'label'      => __( 'Divider', 'rishi' ),
                            'control'    => ControlTypes::BORDER,
                            'design'     => 'inline',
                            'value'      => [
                                'width' => 1,
                                'style' => 'solid',
                                'color' => [
                                    'color' => 'var(--paletteColor6)',
                                ],
                            ],
                            'conditions' => [
                                'mobile_menu_type' => 'type-2'
                            ]
                        ],

                        'mobileMenuMargin' => [
                            'label' => __('Margin', 'rishi'),
                            'control' => ControlTypes::INPUT_SPACING,
                            'value' => \Rishi\Customizer\Helpers\Basic::spacing_value([
                                'left' => '0',
                                'top'    => '0',
                                'right' => '0',
                                'bottom' => '0',
                                'linked' => true,
                                'unit'   => 'px'
                            ]),
							'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
                            'responsive' => false
                        ],
                        'mobileMenuPadding' => [
                            'label' => __('Padding', 'rishi'),
                            'control' => ControlTypes::INPUT_SPACING,
                            'value' => \Rishi\Customizer\Helpers\Basic::spacing_value([
                                'linked' => true,
                                'top'    => '5',
                                'left'   => '0',
                                'bottom' => '5',
                                'right'  => '0',
                                'unit'   => 'px'
                            ]),
							'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
                            'responsive' => false
                        ],
                    ],
                ],
        ];
        return $options;
    }

    /**
     * Write logic for dynamic css change for the elements
     *
     * @return
     */
    public function dynamic_styles(){
		$header_default      = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$mobileMenuFont      = $this->get_mod_value( 'mobileMenuFont', $header_default['mobileMenuFont'] );
		$mobileMenuColor     = $this->get_mod_value( 'mobileMenuColor', $header_default['mobileMenuColor'] );
		$mobileMenuChildSize = $this->get_mod_value( 'mobileMenuChildSize', $header_default['mobileMenuChildSize'] );
		$mobileMenuMargin    = $this->get_mod_value( 'mobileMenuMargin', $header_default['mobileMenuMargin'] );
		$mobileMenuPadding   = $this->get_mod_value( 'mobileMenuPadding', $header_default['mobileMenuPadding'] );
		$mobile_menu_divider   = $this->get_mod_value( 'mobile_menu_divider', $header_default['mobile_menu_divider'] );


        return array(
            'mobileMenuFont' => array(
                'value'      => $mobileMenuFont,
                'selector'   => '#rishi-offcanvas .rishi-mobile-menu',
                'type'       => 'typography'
            ),
            'mobileMenuColor'      => array(
                'value'     => $mobileMenuColor,
                'type'      => 'color',
                'default'   => array(
                    'default' => array( 'color' => 'var(--paletteColor1)' ),
                    'hover'   => array( 'color' => 'var(--paletteColor3)' ),
                ),
                'variables' => array(
                    'default' => array(
                        'variable' => 'linkInitialColor',
                        'selector' => '#rishi-offcanvas .rishi-mobile-menu',
                    ),
                    'hover'   => array(
                        'variable' => 'linkHoverColor',
                        'selector' => '#rishi-offcanvas .rishi-mobile-menu',
                    ),
                ),
            ),
            'mobileMenuChildSize' => array(
                'selector'     => '#rishi-offcanvas .rishi-mobile-menu',
                'variableName' => 'mobile_menu_child_size',
                'value'        => $mobileMenuChildSize,
                'unit'         => '',
                'responsive'   => false,
                'type'         => 'slider',
            ),
            'mobileMenuMargin'       => array(
                'selector'     => '#rishi-offcanvas .rishi-mobile-menu',
                'important'    => true,
                'variableName' => 'margin',
                'property'     => 'margin',
                'value'        => $mobileMenuMargin,
                'unit'         => '',
                'type'         => 'spacing',
                'responsive'   => false,
            ),
            'mobileMenuPadding'       => array(
                'selector'     => '#rishi-offcanvas .rishi-mobile-menu',
                'important'    => true,
                'variableName' => 'padding',
                'property'     => 'padding',
                'value'        => $mobileMenuPadding,
                'unit'         => '',
                'type'         => 'spacing',
                'responsive'   => false,
            ),
            'mobile_menu_divider'      => array(
                'value'     => $mobile_menu_divider,
                'type'      => 'divider',
                'default'   =>  $header_default['mobile_menu_divider'],
                'unit'      => 'px',
                'variables' => array(
                    'default' => array(
                        'variable' => 'mobile-menu-divider',
                        'selector' => '#rishi-offcanvas .rishi-mobile-menu',
                    )
                ),
            ),
        );
    }

    /**
     * Renders function
     * @param string $device
     * @return void
     */
    public function render( $device = 'desktop'){

        $hidden = $this->get_mod_value('header_hide_mobile_menu', false);

        if ($hidden) {
            return '';
        }

        $layout_class = $this->get_mod_value('mobile_menu_type', 'type-1');

        $menu_type = $layout_class === 'type-1' ? 'menu-default' : 'menu-border';

        $class = 'rishi-mobile-menu' . ' ' . $menu_type;

        $menu_args = array(
			'container'      => 'ul',
			'menu_class'     => '',
			'theme_location' => 'primary-menu'
		);

		$rishi_menu = $this->get_mod_value('footer_menu', 'rishi_customizer_locations');

        if ($rishi_menu !== 'rishi_customizer_locations') {
            $menu_args['menu'] = $rishi_menu;
        }

        ?>
        <nav id="rishi-mobile-menu" class="<?php echo esc_attr($class); ?>">
            <?php wp_nav_menu( $menu_args ); ?>
        </nav>

        <?php
    }
}
