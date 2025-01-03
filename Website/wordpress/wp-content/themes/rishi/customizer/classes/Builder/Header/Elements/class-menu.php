<?php
/**
 * Class Menu.
 */

namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts;

/**
 * Class Menu
 */
class Menu extends Abstracts\Builder_Element {
	public function get_id() {
		return 'menu';
	}

	public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'Menu 1', 'rishi' );
	}

	public function config() {
		return array(
			'name'          => $this->get_label(),
			'visibilityKey' => 'header_hide_' . $this->get_id(),
			'devices'       => ['desktop']
		);
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return void
	 */
	public function get_options() {

		$options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'General', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [
					'header_hide_' . $this->get_id()  => [
						'label'               => false,
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],
					'menu'                                    => [
						'label'       => __( 'Choose Menu', 'rishi' ),
						'help'		 => __( 'Select the menu that you prefer to display in the footer.', 'rishi' ),
						'control'        => ControlTypes::INPUT_SELECT,
						'value'       => 'rishi_customizer_locations',
						'view'        => 'text',
						'design'      => 'block',
						'placeholder' => __( 'Select menu...', 'rishi' ),
						'help'        => sprintf(
								// translators: placeholder here means the actual URL.
								__( 'Manage your menu items in the %1$sMenus screen%2$s.', 'rishi' ),
								sprintf(
									'<a href="%s" target="_blank">',
									admin_url( '/nav-menus.php' )
								),
								'</a>'
							),
						'choices'     => \Rishi\Customizer\Helpers\Basic::ordered_keys( \Rishi\Customizer\Helpers\Basic::get_menu_list() ),
					],
					'header_menu_type'                        => [
						'label'                => __( 'Menu Layout', 'rishi' ),
						'control'                 => ControlTypes::IMAGE_PICKER,
						'value'                => 'type-1',
						'divider'			   => 'top',
						'attr'                 => [
								'data-type'  => 'background',
								'data-usage' => 'menu-type',
							],
						'switchDeviceOnChange' => 'desktop',
						'choices'              => [

							'type-1' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-1.svg' ),
								'title' => __( 'Type 1', 'rishi' ),
							],

							'type-2' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-2.svg' ),
								'title' => __( 'Type 2', 'rishi' ),
							],

							'type-3' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-3.svg' ),
								'title' => __( 'Type 3', 'rishi' ),
							],

							'type-4' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-4.svg' ),
								'title' => __( 'Type 4', 'rishi' ),
							],

							'type-5' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-5.svg' ),
								'title' => __( 'Type 5', 'rishi' ),
							],

							'type-6' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-6.svg' ),
								'title' => __( 'Type 6', 'rishi' ),
							],

							'type-7' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-7.svg' ),
								'title' => __( 'Type 7', 'rishi' ),
							],

							'type-8' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'menu-layout-8.svg' ),
								'title' => __( 'Type 8', 'rishi' ),
							],
						],
					],

					'headerMenuItemsSpacing'  => [
						'label'      => __( 'Item Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'divider'    => 'top',
						'value'      => '25px',
						'responsive' => false,
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 10, 'max' => 100 ],
						] ),
					],

						'headerMenuItemsHeight' => [
							'label'   => __( 'Items Height', 'rishi' ),
							'control' => ControlTypes::INPUT_SLIDER,
							'divider' => 'top',
							'value'   => '60px',
							'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
								[ 'unit' => 'px', 'min' => 0, 'max' => 500 ],
							] ),
							'conditions' => [
								'header_menu_type' => 'type-2|type-3|type-4'
							]
						],

					'stretch_menu'                            => [
						'label'   => __( 'Stretch Menu', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => 'no',
						'divider' => 'top',

					],

					\Rishi\Customizer\Helpers\Basic::uniqid() => [
						'label'   => __( 'SUBMENU SETTINGS', 'rishi' ),
						'control' => ControlTypes::TITLE,
					],

					'dropdownMenuWidth'  => [
						'label'      => __( 'Submenu Width', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '200px',
						'divider' => 'bottom',
						'responsive' => false,
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 100, 'max' => 300 ],
						] ),
					],

					'dropdownTopOffset'  => [
						'label'      => __( 'Submenu Top Offset', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'divider' => 'bottom',
						'value'      => '0px',
						'responsive' => false,
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => -150, 'max' => 150 ],
						] ),
					],

					'dropdown_animation'    => [
						'label'   => __( 'Submenu Transition', 'rishi' ),
						'control'    => ControlTypes::INPUT_RADIO,
						'divider' => 'bottom',
						'value'   => 'slide-down',
						'design'  => 'block',
						'choices' => [
							'fade-in'    => __( 'Fade In', 'rishi' ),
							'slide-up'   => __( 'Slide Up', 'rishi' ),
							'slide-down' => __( 'Slide Down', 'rishi' ),
						],
					],
				],
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [
					\Rishi\Customizer\Helpers\Basic::uniqid() => [
						'label'   => __( 'NAVIGATION MENU', 'rishi' ),
						'control' => ControlTypes::TITLE,
					],

					'headerMenuFont'       => rishi_typography_control_option([
						'control' => ControlTypes::TYPOGRAPHY,
						'label'   => __( 'Font', 'rishi' ),
						'divider' => 'bottom',
						'value'   => \Rishi\Customizer\Helpers\Defaults::typography_value( [
							'size'            => array(
								'desktop' => '16px',
								'tablet'  => '16px',
								'mobile'  => '16px',
							),
							'line-height'            => array(
								'desktop' => '2.25em',
								'tablet'  => '2.25em',
								'mobile'  => '2.25em',
							),
							'text-transform' => 'normal',
						] ),
					]),
					'menu_font_color_group'  => [
						'label'   => __( 'Color', 'rishi' ),
						'control'    => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value'   => [
							'menuFontColor' => [
								'default' => [
									'color' => 'var(--paletteColor1)',
								],

								'hover'   => [
									'color' => 'var(--paletteColor3)',
								],
							],
						],
						'settings' => [
							'menuFontColor'            => [
								'label'        => __( 'Default State', 'rishi' ),
								'control'      => ControlTypes::COLOR_PICKER,
								'design'       => 'inline',
								'colorPalette' => true,
								'responsive'   => false,
								'value' => [
									'default' => [
										'color' => 'var(--paletteColor1)',
									],

									'hover'   => [
										'color' => 'var(--paletteColor3)',
									],
								],
								'pickers' => [
									[
										'title' => __( 'Initial', 'rishi' ),
										'id'    => 'default',
									],

									[
										'title'   => __( 'Hover/Active', 'rishi' ),
										'id'      => 'hover',
										'inherit' => 'var(--linkHoverColor)',
									],
								],
							],
						]
					],

					'menuIndicatorColor'            => array(
						'label'   => __( 'Active Indicator Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'  => 'inline',
						'divider' => 'bottom',
						'value'   => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),
						),

						'pickers' => array(
							array(
								'title' => __( 'Active', 'rishi' ),
								'id'    => 'default',
							),
						),
					),
					'currentMenuLinkBg'            => array(
						'label'   => __( 'Active Indicator Background Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'  => 'inline',
						'divider' => 'bottom',
						'value'   => array(
							'default' => array(
								'color' => 'var(--paletteColor7)',
							),
						),

						'pickers' => array(
							array(
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							),
						),

						'conditions' => [
							'header_menu_type' => 'type-4'
						]
					),

					'headerDropdownBackground' => array(
                        'label'           => __( 'Dropdown Background Color', 'rishi' ),
                        'control'         => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
                        'skipEditPalette' => true,
                        'design'          => 'inline',
                        'divider'         => 'bottom',
						'responsive'      => false,
                        'value'           => array(
                            'default' => array(
                                'color' => 'var(--paletteColor5)',
                            )
                        ),
                        'pickers'         => array(
                            array(
                                'title' => __( 'Initial', 'rishi' ),
                                'id'    => 'default',
                            ),
                        ),
                    ),
					'headerMenuMargin'                        => [
						'label'      => __( 'Margin', 'rishi' ),
						'control'       => ControlTypes::INPUT_SPACING,
						'value'      => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => 'auto',
									'left'   => '20',
									'right'  => '20',
									'bottom' => 'auto',
									'unit'	 => 'px'
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => 'auto',
									'left'   => '20',
									'right'  => '20',
									'bottom' => 'auto',
									'unit'	 => 'px'
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => 'auto',
									'left'   => '20',
									'right'  => '20',
									'bottom' => 'auto',
									'unit'	 => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => true,
					],
					\Rishi\Customizer\Helpers\Basic::uniqid() => [
						'label'   => __( 'SUBMENU SETTINGS', 'rishi' ),
						'control' => ControlTypes::TITLE,
					],

					'headerDropdownFont'       => rishi_typography_control_option([
						'control' => ControlTypes::TYPOGRAPHY,
						'divider' => 'bottom',
						'label'   => __( 'Font', 'rishi' ),
						'value'   => \Rishi\Customizer\Helpers\Defaults::typography_value( [
							'size'            => array(
								'desktop' => '16px',
								'tablet'  => '16px',
								'mobile'  => '16px',
							),
						] ),
					]),
					'headerDropdownFontColor'  => [
						'label'   => __( 'Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'  => 'inline',
						'divider' => 'bottom',

						'value'   => [
								'default' => [
									'color' => 'var(--paletteColor1)',
								],

								'hover'   => [
									'color' => 'var(--paletteColor3)',
								],
							],

						'pickers' => [
							[
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							],

							[
								'title'   => __( 'Hover/Active', 'rishi' ),
								'id'      => 'hover',
								'inherit' => 'var(--linkHoverColor)',
							],
						],
					],
					'dropdownItemsSpacing'  => [
						'label'      => __( 'Item Vertical Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '15px',
						'divider' => 'bottom',
						'responsive' => false,
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
						] ),
					],
					'headerDropdownDivider' => [
						'label'      => __( 'Divider Color', 'rishi' ),
						'control'    => ControlTypes::BORDER,
						'divider' => 'bottom',
						'design'     => 'inline',
						'value'      => [
							'width' => 1,
							'style' => 'none',
							'color' => [
								'color' => '#dddddd',
							],
						]
					],
					'headerDropdownShadow'     => [
						'label'   => __( 'Submenu Shadow', 'rishi' ),
						'control'    => ControlTypes::BOX_SHADOW,
						'design'  => 'inline',
						'divider' => 'bottom',
						'value'   => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value( [
							'enable'   => true,
							'inset'    => false,
							'h_offset' => '0px',
							'v_offset' => '10px',
							'blur'     => '20px',
							'spread'   => '0px',
							'color' => 'rgba(41, 51, 61, 0.1)',
						] )
					],
					'headerDropdownRadius'     => [
						'label'   => __( 'Dropdown Border Radius', 'rishi' ),
						'control' => ControlTypes::INPUT_SPACING,
						'value'      => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top'    => '0',
									'left'   => '2',
									'right'  => '0',
									'bottom' => '2',
									'unit'	 => 'px'
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top'    => '0',
									'left'   => '2',
									'right'  => '0',
									'bottom' => '2',
									'unit'	 => 'px'
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top'    => '0',
									'left'   => '2',
									'right'  => '0',
									'bottom' => '2',
									'unit'	 => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
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
	 * @return
	 */
	public function dynamic_styles() {
		$header_default               = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$headerMenuItemsSpacing       = $this->get_mod_value( 'headerMenuItemsSpacing', $header_default['headerMenuItemsSpacing'] );
		$headerMenuItemsHeight        = $this->get_mod_value( 'headerMenuItemsHeight', $header_default['headerMenuItemsHeight'] );
		$headerMenuMargin             = $this->get_mod_value( 'headerMenuMargin', $header_default['headerMenuMargin'] );
		$menuFont                     = $this->get_mod_value( 'headerMenuFont', $header_default['headerMenuFont'] );
		$headerDropdownFont           = $this->get_mod_value( 'headerDropdownFont', $header_default['headerDropdownFont'] );
		$headerDropdownFontColor      = $this->get_mod_value( 'headerDropdownFontColor', $header_default['headerDropdownFontColor'] );
		$dropdownItemsSpacing         = $this->get_mod_value( 'dropdownItemsSpacing', $header_default['dropdownItemsSpacing'] );
		$dropdownMenuWidth            = $this->get_mod_value( 'dropdownMenuWidth', $header_default['dropdownMenuWidth'] );
		$dropdownTopOffset            = $this->get_mod_value( 'dropdownTopOffset', $header_default['dropdownTopOffset'] );
		$headerDropdownRadius         = $this->get_mod_value( 'headerDropdownRadius', $header_default['headerDropdownRadius'] );
		$headerDropdownShadow         = $this->get_mod_value( 'headerDropdownShadow', $header_default['headerDropdownShadow'] );
		$dropdownDivider              = $this->get_mod_value( 'headerDropdownDivider', $header_default['headerDropdownDivider'] );
		$menuIndicatorColor           = $this->get_mod_value( 'menuIndicatorColor', $header_default['menuIndicatorColor'] );
		$currentMenuLinkBg = $this->get_mod_value( 'currentMenuLinkBg', $header_default['currentMenuLinkBg'] );
		$headerDropdownBackground = $this->get_mod_value( 'headerDropdownBackground', $header_default['headerDropdownBackground'] );

		$menuFontColor  = $this->get_mod_value( 'menu_font_color_group', [
			'menuFontColor' => $header_default['menuFontColor']
		] );

		$options = array(
			'headerMenuItemsSpacing' => array(
				'selector'     => '.site-header .site-navigation-1',
				'variableName' => 'menu-items-spacing',
				'value'        => $headerMenuItemsSpacing,
				'responsive'   => false,
				'type'         => 'slider',
			),
			'headerMenuItemsHeight' => array(
				'selector'     => '.site-header .site-navigation-1',
				'variableName' => 'menu-item-height',
				'value'        => $headerMenuItemsHeight,
				'responsive'   => false,
				'type'         => 'slider',
			),
			'headerMenuMargin'       => array(
				'selector'   => '.site-header .site-navigation-1',
				'important'  => true,
				'variableName' => 'margin',
				'value'      => $headerMenuMargin,
				'unit'       => '',
				'type'       => 'spacing',
				'responsive' => true,
			),
			'headerDropdownBackground' => array(
				'value'     => $headerDropdownBackground,
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor5)' )
				),
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.site-header .site-navigation-1 .sub-menu',
					)
				),
				'type'      => 'color',
			),
			//headermenu.
			'headerMenuColor'      => array(
				'value'     => $menuFontColor['menuFontColor'],
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor1)' ),
					'hover'   => array( 'color' => 'var(--paletteColor3)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.site-header .site-navigation-1 > ul > li > a',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.site-header .site-navigation-1 > ul > li > a',
					)
				),
			),
			'headerMenuFont' => array(
				'value'      => $menuFont,
				'selector'   => '.site-header .site-navigation-1 > ul > li > a',
				'type'       => 'typography'
			),
			'menuIndicatorColor' => array(
				'value'     => $menuIndicatorColor,
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor3)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'currentMenuLinkAccent',
						'selector' => '.site-header .site-navigation-1',
					),
				),
			),
			'currentMenuLinkBg' => array(
				'value'     => $currentMenuLinkBg,
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor7)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'currentMenuLinkBg',
						'selector' => '.site-header .site-navigation-1',
					),
				),
			),
			//submenu
			'headerDropdownFont' => array(
				'value'      => $headerDropdownFont,
				'selector'   => '.site-header .site-navigation-1 .sub-menu',
				'type'       => 'typography'
			),
			'headerDropdownFontColor'      => array(
				'value'     => $headerDropdownFontColor,
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor1)' ),
					'hover'   => array( 'color' => 'var(--paletteColor3)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.site-header .site-navigation-1 .sub-menu',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.site-header .site-navigation-1 .sub-menu',
					),
				),
			),
			'dropdownTopOffset'      => [
				'selector'     => '.site-header .site-navigation-1 .sub-menu',
				'variableName' => 'dropdown-top-offset',
				'value'        => $dropdownTopOffset,
				'unit'         => '',
				'responsive'   => false,
				'type'         => 'slider',
			],
			'dropdownMenuWidth'      => [
				'selector'     => '.site-header .site-navigation-1 .sub-menu',
				'variableName' => 'dropdown-width',
				'value'        => $dropdownMenuWidth,
				'unit'         => '',
				'responsive'   => false,
				'type'         => 'slider',
			],
			'dropdownItemsSpacing' => array(
				'selector'     => '.site-header .site-navigation-1 .sub-menu',
				'variableName' => 'dropdown-items-spacing',
				'value'        => $dropdownItemsSpacing,
				'unit'         => '',
				'responsive'   => false,
				'type'         => 'slider',
			),
			'headerDropdownDivider'      => array(
				'value'     => $dropdownDivider,
				'type'      => 'divider',
				'default'   =>  $header_default['headerDropdownDivider'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'dropdown-divider',
						'selector' => '.site-header .site-navigation-1 .sub-menu',
					)
				),
			),
			'headerDropdownRadius'       => array(
				'selector'   => '.site-header .site-navigation-1 .sub-menu',
				'important'  => true,
				'variableName' => 'border-radius',
				'value'      => $headerDropdownRadius,
				'unit'       => '',
				'type'       => 'spacing',
				'responsive' => true,
			),
			'headerDropdownShadow' => array(
				'value'     =>$headerDropdownShadow,
				'default' => array(
					'default' => array(
						'enable' => true,
						'inset' => false,
						'h_offset' => '0px',
						'v_offset' => '10px',
						'blur'  => '20px',
						'spread' => '0px',
						'color' => 'rgba(41, 51, 61, 0.1)',
					),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'box-shadow',
						'selector' => '.site-header .site-navigation-1 .sub-menu',
					),
				),
				'type'      => 'boxshadow',
			),
		);

		return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
	}

	/**
	 * Add markup for the element
	 * @return void
	 */
	public function render( $device = 'desktop') {

		$class                 = 'site-navigation-1';
		$header_menu_type      = $this->get_mod_value( 'header_menu_type', 'type-1' );

		if ( $header_menu_type ) {
			$class .= ' rishi-menu-layout-' . $header_menu_type;
		}

		$stretch_menu = $this->get_mod_value( 'stretch_menu', 'no' );

		if ( $stretch_menu === 'yes' ) {
			$class .= ' rishi-strech-' . $stretch_menu;
		}

		$dropdown_animation  = $this->get_mod_value( 'dropdown_animation', 'slide-down' );

		if ( $dropdown_animation ) {
			$class .= ' rishi-' . $dropdown_animation;
		}

		$menu_args = array(
			'container'      => 'ul',
			'menu_class'     => 'rishi-menu',
			'theme_location' => 'primary-menu',
			'fallback_cb'    => 'Rishi\Customizer\Helpers\Basic::rishi_menu_fallback'
		);

		$rishi_menu = $this->get_mod_value('menu', 'rishi_customizer_locations');

        if ($rishi_menu !== 'rishi_customizer_locations') {
            $menu_args['menu'] = $rishi_menu;
        }

		?>
		<nav id="site-navigation-1" class="<?php echo esc_attr( $class ); ?>">
			<?php wp_nav_menu( $menu_args ); ?>
		</nav>
		<?php
	}
}
