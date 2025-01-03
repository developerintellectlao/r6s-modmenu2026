<?php
/**
 * Class Footer_Menu.
 */

namespace Rishi\Customizer\Footer\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Footer_Menu extends Abstracts\Builder_Element {
	public function get_id() {
		return 'footer-menu';
	}

    public function get_builder_type() {
		return 'footer';
	}

	public function get_label() {
		return __( 'Footer Menu', 'rishi' );
	}

	public function config() {
		return array(
			'name'          => $this->get_label(),
			'visibilityKey' => 'footer_hide_' . $this->get_id(),
		);
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return array get options
	 */
	public function get_options() {

		$options = [
			'footer_hide_' . $this->get_id() => [
                'label'               => false,
                'control'             => ControlTypes::HIDDEN,
                'value'               => false,
                'disableRevertButton' => true,
                'help'                => __('Hide', 'rishi'),
            ],
            'footer-menu' => [
                'label'       => __('Choose Menu', 'rishi'),
                'help'        => __( 'Select the menu that you prefer to display in the footer.', 'rishi' ),
                'control'     => ControlTypes::INPUT_SELECT,
                'value'       => 'rishi_customizer_locations',
                'view'        => 'text',
                'design'      => 'block',
                'placeholder' => __('Select menu...', 'rishi'),
                'choices'     => \Rishi\Customizer\Helpers\Basic::ordered_keys( \Rishi\Customizer\Helpers\Basic::get_menu_list()),
                'help'        => sprintf(
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
                'control' => ControlTypes::DIVIDER,
            ],

        \Rishi\Customizer\Helpers\Basic::uniqid() => [
                'title' => __('General', 'rishi'),
                'control' => ControlTypes::TAB,
                'options' => [

                    'footerMenuItemsSpacing' => [
                        'label' => __('Items Spacing', 'rishi'),
                        'control' => ControlTypes::INPUT_SLIDER,
                        'value' => '25px',
                        'divider' => 'bottom',
                        'responsive' => false,
                        'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 10, 'max' => 100 ],
						] ),
                    ],

                    'stretch_menu' => [
                        'label'   => __('Stretch Menu', 'rishi'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'bottom',
                    ],

                \Rishi\Customizer\Helpers\Basic::uniqid() => [
                        'control' => ControlTypes::DIVIDER,
                    ],

                    'footerMenuAlignment' => [
                        'control' => ControlTypes::INPUT_RADIO,
                        'label' => __('Horizontal Alignment', 'rishi'),
                        'design' => 'block',
                        'responsive' => false,
                        'attr' => ['data-type' => 'horizontal-alignment'],
                        'value' => 'flex-start',
                        'choices' => [
                            'flex-start' => 'Start',
                            'center' => 'Center',
                            'flex-end' => 'End',
                        ],
                    ],

                    'footerMenuVerticalAlignment' => [
                        'control' => ControlTypes::INPUT_RADIO,
                        'label' => __('Vertical Alignment', 'rishi'),
                        'design' => 'block',
                        'divider' => 'top',
                        'responsive' => false,
                        'attr' => ['data-type' => 'vertical-alignment'],
                        'value' => 'flex-start',
                        'choices' => [
                            'flex-start' => 'Top',
                            'center' => 'Center',
                            'flex-end' => 'Bottom',
                        ],
                    ],

                    'footer_menu_visibility' => [
                        'label' => __('Element Visibility', 'rishi'),
                        'control' => ControlTypes::VISIBILITY,
                        'design' => 'block',
                        'divider' => 'top',
                        'value' => [
                            'desktop' => 'desktop',
                            'tablet'  => 'tablet',
                            'mobile'  => 'mobile',
                        ],

                        'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys([
                            'desktop' => __('Desktop', 'rishi'),
                            'tablet' => __('Tablet', 'rishi'),
                            'mobile' => __('Mobile', 'rishi'),
                        ]),
                    ],

                ],
            ],

        \Rishi\Customizer\Helpers\Basic::uniqid() => [
                'title' => __('Design', 'rishi'),
                'control' => ControlTypes::TAB,
                'options' => [

                    'footerMenuFont' => rishi_typography_control_option([
                        'control' => ControlTypes::TYPOGRAPHY,
                        'label' => __('Font', 'rishi'),
                        'divider' => 'bottom',
                        'value' => \Rishi\Customizer\Helpers\Defaults::typography_value([
							'size'            => array(
								'desktop' => '14px',
								'tablet'  => '14px',
								'mobile'  => '14px',
							),
							'line-height'            => array(
								'desktop' => '1.3em',
								'tablet'  => '1.3em',
								'mobile'  => '1.3em',
							),
							'letter-spacing'            => array(
								'desktop' => '0.3px',
								'tablet'  => '0.3px',
								'mobile'  => '0.3px',
							),
                            'weight' => '400',
                            'text-transform' => 'normal',
                        ]),
                    ]),

                    'footerMenuFontColor' => [
                        'label' => __('Font Color', 'rishi'),
                        'control'  => ControlTypes::COLOR_PICKER,
						'colorPalette'	  => true,
                        'design' => 'inline',
                        'divider' => 'bottom',
                        'value' => [
                            'default' => [
                                'color' => 'var(--paletteColor5)',
                            ],

                            'hover' => [
                                'color' => 'var(--paletteColor3)',
                            ],
                        ],

                        'pickers' => [
                            [
                                'title' => __('Initial', 'rishi'),
                                'id' => 'default',
                            ],

                            [
                                'title' => __('Hover/Active', 'rishi'),
                                'id' => 'hover',
                                'inherit' => 'var(--hover-color)',
                            ],
                        ],
                    ],

                    'footerMenuMargin' => [
                        'label'   => __('Margin', 'rishi'),
                        'control' => ControlTypes::INPUT_SPACING,
                        'divider' => 'bottom',
                        'value'   => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '0',
									'unit'	 => 'px'
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '0',
									'unit'	 => 'px'
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '0',
									'unit'	 => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => true,
                    ],

                ],
            ],
        ];
        return $options;
	}

    /**
	 * Write logic for dynamic css change for the elements
	 *
	 * @return array dynamic styles
	 */
	public function dynamic_styles() {
        $footer_menu_default     = \Rishi\Customizer\Helpers\Defaults::get_footer_defaults();
        $menu_margin         = $this->get_mod_value( 'footerMenuMargin', $footer_menu_default['footerMenuMargin'] );
        $spacing             = $this->get_mod_value( 'footerMenuItemsSpacing', $footer_menu_default['footerMenuItemsSpacing'] );
        $footerMenuFontColor = $this->get_mod_value( 'footerMenuFontColor', $footer_menu_default['footerMenuFontColor'] );
		$footerMenuFont      = $this->get_mod_value('footerMenuFont', $footer_menu_default['footerMenuFont']);

		return array(
			'footerMenuMargin' => [
				'selector'     => '#footer-site-navigation',
				'variableName' => 'margin',
				'value'        => $menu_margin,
				'responsive'   => true,
				'type'       => 'spacing',
				'property'   => 'margin',
			],
			'footerMenuItemsSpacing'   => array(
				'selector'     => '#footer-site-navigation',
				'variableName' => 'menu-items-spacing',
				'value'        => $spacing,
				'responsive'   => false,
				'type'         => 'slider'
			),
			'footerMenuFontColor'      => array(
				'value'     => $footerMenuFontColor,
				'type'      => 'color',
				'default'   => $footer_menu_default['footerMenuFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '#footer-site-navigation',
					),
					'hover' => array(
						'variable' => 'linkHoverColor',
						'selector' => '#footer-site-navigation',
					)
				)
			),
			'footerMenuFont' => array(
				'value' => $footerMenuFont,
				'selector' => '#footer-site-navigation',
				'type' => 'typography'
			),

		);
    }
	/**
	 * Add markup for the element
	 * @return void
	 */
	public function render( $device = 'desktop') {

        $class              = 'rishi-footer-navigation';
		$stretch_menu       = $this->get_mod_value( 'stretch_menu', 'no' );
		$horizontal_align   = $this->get_mod_value( 'footerMenuAlignment', 'flex-start');
		$vertical_alignment = $this->get_mod_value( 'footerMenuVerticalAlignment', 'flex-start' );
        $visibility = $this->get_mod_value(
            'footer_menu_visibility',
            [
                'desktop' => 'desktop',
                'tablet'  => 'tablet',
                'mobile'  => 'mobile',
            ]
        );

        $visibility_class   = $this->get_visible_device_class( $visibility );
        $class .= $visibility_class . ' vertical-' . $vertical_alignment .' horizontal-' . $horizontal_align ;

		if ( $stretch_menu === 'yes' ) {
			$class .= ' rishi-strech-' . $stretch_menu;
		}

        $menu_args = array(
            'container'      => 'ul',
            'menu_class'     => 'rishi-menu',
            'theme_location' => 'footer-menu',
            'depth'          => 1,
            'fallback_cb'    => 'Rishi\Customizer\Helpers\Basic::rishi_menu_fallback'
        );

        $selected_menu = $this->get_mod_value( 'footer-menu', 'rishi_customizer_locations' );
        if ( $selected_menu !== 'rishi_customizer_locations') {
            $menu_args['menu'] = $selected_menu;
        }
        ?>
        <nav id="footer-site-navigation" class="<?php echo esc_attr($class); ?>">
			<?php wp_nav_menu( $menu_args ); ?>
		</nav>
        <?php
	}
}
