<?php
/**
 * Class Button.
 */

namespace Rishi\Customizer\Header\Elements;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;
use \Rishi\Customizer\Helpers\Defaults as Defaults;
class Button extends Abstracts\Builder_Element {

	public function get_id() {
		return 'button';
	}

	public function get_builder_type() {
		return 'header';
	}
	public function get_label() {
		return __('Button', 'rishi');
	}

	public function config() {
		return array(
			'name' => $this->get_label(),
			'visibilityKey' => 'header_hide_'.$this->get_id(),
		);
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return array get options
	 */
	public function get_options() {
		$colordefaults = Defaults::color_value();

		$options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title' => __('General', 'rishi'),
				'control' => ControlTypes::TAB,
				'options' => array_merge([
					'header_hide_'.$this->get_id() => [
						'label' => false,
						'control' => ControlTypes::HIDDEN,
						'value' => false,
						'disableRevertButton' => true,
						'help' => __('Hide', 'rishi'),
					],
					'header_button_type' => [
						'label' => __('Button Type', 'rishi'),
						'control' => ControlTypes::INPUT_RADIO,
						'value' => 'type-1',
						'design' => 'block',
						'divider' => 'bottom',
						'choices' => [
							'type-1' => __('Filled', 'rishi'),
							'type-2' => __('Outlined', 'rishi'),
						],
					],
					'header_button_size' => [
						'label' => __('Button Size', 'rishi'),
						'control' => ControlTypes::INPUT_RADIO,
						'value' => 'small',
						'design' => 'block',
						'divider' => 'bottom',
						'choices' => [
							'small' => __('Small', 'rishi'),
							'medium' => __('Medium', 'rishi'),
							'large' => __('Large', 'rishi'),
						],
					],

					'header_button_text' => [
						'label' => __('Button Label', 'rishi'),
						'control' => ControlTypes::INPUT_TEXT,
						'design' => 'block',
						'divider' => 'bottom',
						'value' => __('Download', 'rishi'),
					],

					'header_button_link' => [
						'label' => __('Button Link', 'rishi'),
						'control' => ControlTypes::INPUT_TEXT,
						'design' => 'block',
						'value' => '#',
						'type' => 'link',
					],
					'header_button_minwidth' => [
						'label' => __('Min. Width ', 'rishi'),
						'control' => ControlTypes::INPUT_SLIDER,
						'value' => array(
							'desktop' => '50px',
							'tablet' => '50px',
							'mobile' => '50px',
						),
						'responsive' => true,
						'units' => \Rishi\Customizer\Helpers\Basic::get_units([
							['unit' => 'px', 'min' => 0, 'max' => 300],
						]),
						'divider' => 'top',
					],
					'header_button_target' => [
						'label' => __('Open New Tab', 'rishi'),
						'control' => ControlTypes::INPUT_SWITCH,
						'value' => 'no',
						'divider' => 'top',

					],
					'rel_attributes' => [
						'label'      => __( 'Rel Attributes', 'rishi' ),
						'help'       => __( 'Choose the Rel Attributes', 'rishi' ),
						'control'    => ControlTypes::INPUT_SELECT,
						'divider'    => 'top',
						'isMultiple' => true,
						'value'      => array( 'header_button_ed_nofollow', 'header_button_ed_sponsored' ),
						'view'       => 'text',
						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys( [
							'header_button_ed_nofollow'   => __( 'Nofollow', 'rishi' ),
							'header_button_ed_sponsored'  => __( 'Sponsored', 'rishi' ),
							'header_button_ed_noopener'   => __( 'Noopener', 'rishi' ),
							'header_button_ed_noreferrer' => __( 'Noreferrer', 'rishi' ),
						] ),
					],
					'button_visibility' => [
						'label' => __('Button Visibility', 'rishi'),
						'control' => ControlTypes::VISIBILITY,
						'divider' => 'top',
						'design' => 'block',
						'value' => [
							'desktop' => 'desktop',
							'tablet' => 'tablet',
							'mobile' => 'mobile',
						],
						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys([
							'desktop' => __('Desktop', 'rishi'),
							'tablet' => __('Tablet', 'rishi'),
							'mobile' => __('Mobile', 'rishi'),
						]),
					],
				]),
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title' => __('Design', 'rishi'),
				'control' => ControlTypes::TAB,
				'options' => [
					'headerButtonFont' => rishi_typography_control_option([
						'control' => ControlTypes::TYPOGRAPHY,
						'label' => __('Font', 'rishi'),
						'value' => Defaults::typography_value([
							'size'            => array(
								'desktop' => '16px',
								'tablet'  => '16px',
								'mobile'  => '16px',
							),
							'line-height'            => array(
								'desktop' => '1.7em',
								'tablet'  => '1.7em',
								'mobile'  => '1.7em',
							),
							'text-transform' => 'normal',
						]),
					]),
					'btn_font_color_group' => [
						'label' => __('Text Color', 'rishi'),
						'control' => ControlTypes::CONTROLS_GROUP,
						'divider' => 'top',
						'conditions' => ['header_button_type' => 'type-1'],
						'value' => [
							'headerButtonFontColor' => [
								'default' => [
									'color' => 'var(--paletteColor5)',
								],

								'hover' => [
									'color' => 'var(--paletteColor5)',
								],
							],
						],
						'settings' => [
							'headerButtonFontColor' => [
								'label' => __('Default State', 'rishi'),
								'control' => ControlTypes::COLOR_PICKER,
								'design' => 'inline',
								'colorPalette'	  => true,
								'responsive' => false,
								'value' => [
									'default' => [
										'color' => 'var(--paletteColor5)',
									],

									'hover' => [
										'color' => 'var(--paletteColor5)',
									],
								],
								'pickers' => [
									[
										'title' => __('Initial', 'rishi'),
										'id' => 'default',
										'inherit' => 'var(--buttonInitialColor)',
									],

									[
										'title' => __('Hover', 'rishi'),
										'id' => 'hover',
										'inherit' => 'var(--buttonHoverColor)',
									],
								],
							],
						]
					],
					'btn_outline_color_group' => [
						'label' => __('Text Color', 'rishi'),
						'control' => ControlTypes::CONTROLS_GROUP,
						'divider' => 'top',
						'conditions' => ['header_button_type' => 'type-2'],
						'value' => [
							'headerButtonFontColorOutline' => [
								'default' => [
									'color' => 'var(--paletteColor3)',
								],

								'hover' => [
									'color' => 'var(--paletteColor5)',
								],
							],
						],
						'settings' => [
							'headerButtonFontColorOutline' => [
								'label' => __('Default State', 'rishi'),
								'control' => ControlTypes::COLOR_PICKER,
								'design' => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'value' => [
									'default' => [
										'color' => 'var(--paletteColor3)',
									],

									'hover' => [
										'color' => 'var(--paletteColor5)',
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
									],
								],
							],
						]
					],
					'btn_foreground_group' => [
						'label' => __('Background Color', 'rishi'),
						'control' => ControlTypes::CONTROLS_GROUP,
						'divider' => 'top',
						'responsive' => false,
						'value' => [
							'headerButtonForeground' => [
								'default' => [
									'color' => 'var(--paletteColor3)',
								],

								'hover' => [
									'color' => 'var(--paletteColor2)',
								],
							],
						],
						'settings' => [
							'headerButtonForeground' => [
								'label' => __('Default State', 'rishi'),
								'control' => ControlTypes::COLOR_PICKER,
								'design' => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
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
									],

									[
										'title' => __('Hover', 'rishi'),
										'id' => 'hover',
									],
								],
							],
						],
					],
					'headerButtonBorder' => array(
						'label' => __( 'Border', 'rishi' ),
						'control' => ControlTypes::BORDER,
						'divider' => 'top',
						'design' => 'inline',
						'value' => array(
							'width' => 1,
							'style' => 'solid',
							'color' => array(
								'color' => $colordefaults['btn_border_color'],
								'hover' => $colordefaults['btn_border_hover_color'],
							),
						),
					),
					'headerButtonBorderRadius' => [
						'label' => __('Border Radius', 'rishi'),
						'control' => ControlTypes::INPUT_SPACING,
						'divider' => 'top',
						'value' => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '3',
									'left' => '3',
									'right' => '3',
									'bottom' => '3',
									'unit' => 'px'
								)
							),
							'tablet' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '3',
									'left' => '3',
									'right' => '3',
									'bottom' => '3',
									'unit' => 'px'
								)
							),
							'mobile' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '3',
									'left' => '3',
									'right' => '3',
									'bottom' => '3',
									'unit' => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
						'responsive' => true,
					],
					'headerButtonPadding' => [
						'label' => __('Button Padding', 'rishi'),
						'control' => ControlTypes::INPUT_SPACING,
						'divider' => 'top',
						'value' => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top' => '10',
									'left' => '20',
									'right' => '20',
									'bottom' => '10',
									'unit' => 'px'
								)
							),
							'tablet' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top' => '10',
									'left' => '20',
									'right' => '20',
									'bottom' => '10',
									'unit' => 'px'
								)
							),
							'mobile' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top' => '10',
									'left' => '20',
									'right' => '20',
									'bottom' => '10',
									'unit' => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
						'responsive' => true,
					],
					'headerButtonMargin' => [
						'label' => __('Button Margin', 'rishi'),
						'control' => ControlTypes::INPUT_SPACING,
						'divider' => 'top',
						'value' => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px'
								)
							),
							'tablet' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px'
								)
							),
							'mobile' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => true,
					],
				],
			]
		];

		return $options;
	}

	/**
	 * Write logic for dynamic css change for the elements
	 *
	 * @return array dynamic styles
	 */
	public function dynamic_styles() {
		$defaults = Defaults::get_layout_defaults();
		$header_default = Defaults::get_header_defaults();
		$header_button_minwidth = $this->get_mod_value('header_button_minwidth', $header_default['header_button_minwidth']);
		$headerButtonBorder = $this->get_mod_value('headerButtonBorder', $header_default['headerButtonBorder']);
		$headerButtonMargin = $this->get_mod_value('headerButtonMargin', $header_default['headerButtonMargin']);
		$headerButtonPadding = $this->get_mod_value('headerButtonPadding', $header_default['headerButtonPadding']);
		$header_button_minwidth = $this->get_mod_value('header_button_minwidth', $header_default['header_button_minwidth']);
		$headerButtonMargin = $this->get_mod_value('headerButtonMargin', $header_default['headerButtonMargin']);
		$headerButtonPadding = $this->get_mod_value('headerButtonPadding', $header_default['headerButtonPadding']);
		$headerButtonBorderRadius = $this->get_mod_value('headerButtonBorderRadius', $header_default['headerButtonBorderRadius']);
		$headerButtonFont = $this->get_mod_value('headerButtonFont', $header_default['headerButtonFont']);
		$headerButtonBorderWidth = $this->get_mod_value('headerButtonBorderWidth', $header_default['headerButtonBorderWidth']);

		$btn_font_color = $this->get_mod_value(
			'btn_font_color_group',
			array(
				'headerButtonFontColor' => $header_default['headerButtonFontColor'],
			)
		);

		$btn_outline_color = $this->get_mod_value(
			'btn_outline_color_group',
			array(
				'headerButtonFontColorOutline' => $header_default['headerButtonFontColorOutline'],
			)
		);

		$btn_foreground = $this->get_mod_value(
			'btn_foreground_group',
			array(
				'headerButtonForeground' => $header_default['headerButtonForeground'],
			)
		);

		$options = array(
			'headerButtonFont' => array(
				'value' => $headerButtonFont,
				'selector' => '#rishi-button',
				'prefix' => 'btn',
				'type' => 'typography'
			),
			'header_button_minwidth' => array(
				'selector' => '#rishi-button',
				'variableName' => 'buttonMinWidth',
				'value' => $header_button_minwidth,
				'responsive' => true,
				'type' => 'slider',
			),
			'headerButtonMargin' => [
				'selector' => '#rishi-button',
				'value' => $headerButtonMargin,
				'variableName' => 'margin',
				'type' => 'spacing',
				'responsive' => true,
			],
			'headerButtonPadding' => [
				'selector' => '#rishi-button',
				'value' => $headerButtonPadding,
				'type' => 'spacing',
				'variableName' => 'headerCtaPadding',
				'responsive' => true,
			],
			'headerButtonBorderRadius' => [
				'selector' => '#rishi-button',
				'value' => $headerButtonBorderRadius,
				'type' => 'spacing',
				'variableName' => 'buttonBorderRadius',
				'responsive' => false,
			],
			'headerButtonFontColor' => [
				'value' => $btn_font_color['headerButtonFontColor'],
				'default' => array(
					'default' => array('color' => 'var(--paletteColor5)'),
					'hover' => array('color' => 'var(--paletteColor5)'),
				),
				'variables' => array(
					'default' => array(
						'selector' => '#rishi-button .btn-default',
						'variable' => 'buttonTextInitialColor',
					),
					'hover' => array(
						'selector' => '#rishi-button .btn-default',
						'variable' => 'buttonTextHoverColor',
					),
				),
				'type' => 'color',
				'responsive' => false
			],
			'headerButtonFontColorOutline' => [
				'value' => $btn_outline_color['headerButtonFontColorOutline'],
				'default' => array(
					'default' => array('color' => 'var(--paletteColor3)'),
					'hover' => array('color' => 'var(--paletteColor5)'),
				),
				'variables' => array(
					'default' => array(
						'selector' => '#rishi-button .btn-outline',
						'variable' => 'buttonTextInitialColor',
					),
					'hover' => array(
						'selector' => '#rishi-button .btn-outline',
						'variable' => 'buttonTextHoverColor',
					),
				),
				'type' => 'color',
				'responsive' => false
			],
			'headerButtonForeground' => [
				'value' => $btn_foreground['headerButtonForeground'],
				'default' => array(
					'default' => array('color' => 'var(--paletteColor3)'),
					'hover' => array('color' => 'var(--paletteColor2)'),
				),
				'variables' => array(
					'default' => array(
						'selector' => '#rishi-button',
						'variable' => 'buttonInitialColor',
					),
					'hover' => array(
						'selector' => '#rishi-button',
						'variable' => 'buttonHoverColor',
					),
				),
				'type' => 'color',
				'responsive' => false
			],
			'headerButtonBorder' => array(
				'value' => $headerButtonBorder,
				'type' => 'divider',
				'unit' => 'px',
				'default' => $defaults['btn_border'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerButtonBorder',
						'selector' => '#rishi-button'
					),
				),
			),
			'headerButtonBorderWidth' => array(
				'value' => $headerButtonBorderWidth,
				'selector' => '#rishi-button',
				'type' => 'border',
				'variableName' => 'headerButtonBorderWidth',
				'unit' => 'px'
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
	 * @param string $desktop
	 * @return void
	 */
	public function render( $device = 'desktop') {

		$header_button_type    = $this->get_mod_value( 'header_button_type', 'type-1' );
		$header_button_size    = $this->get_mod_value( 'header_button_size', 'small' );
		$header_button_text    = $this->get_mod_value( 'header_button_text', __( 'Download', 'rishi' ) );
		$header_button_link    = $this->get_mod_value( 'header_button_link', '#' );
		$header_button_target  = $this->get_mod_value( 'header_button_target', 'no' );
		$rel_attributes        = $this->get_mod_value( 'rel_attributes', array( 'header_button_ed_nofollow', 'header_button_ed_sponsored' ) );
		$target                = $header_button_target === 'yes' ? 'target=_blank' : '';
		$class                 = $header_button_type   === 'type-1' ? 'btn-default' : 'btn-outline';
		$class                .= ' btn-' . $header_button_size;

		if  ( in_array('header_button_ed_nofollow', $rel_attributes ) ) {
			$rel_nofollow ='nofollow ';
		} else {
			$rel_nofollow = '';
		}

		if ( in_array('header_button_ed_sponsored', $rel_attributes ) ) {
			$rel_sponsored = 'sponsored ';
		} else {
			$rel_sponsored = '';
		}
		if ( in_array('header_button_ed_noopener', $rel_attributes ) ) {
			$rel_noopener = 'noopener ';
		} else {
			$rel_noopener = '';
		}
		if ( in_array('header_button_ed_noreferrer', $rel_attributes ) ) {
			$rel_noreferrer = 'noreferrer ';
		} else {
			$rel_noreferrer = '';
		}

		$button_visibility = $this->get_mod_value(
			'button_visibility',
			[
				'desktop' => 'desktop',
				'tablet' => 'tablet',
				'mobile' => 'mobile',
			]
		);

		$button_visibility = $this->get_visible_device_class( $button_visibility ); ?>
		<div class="rishi-header-cta <?php echo esc_attr( $button_visibility ); ?>" id="rishi-button">
			<a class="rishi-button <?php echo esc_attr( $class ); ?>"
				href="<?php echo esc_url( $header_button_link ); ?>" <?php echo esc_attr( $target ); ?> rel="<?php echo esc_attr( $rel_nofollow ) . esc_attr( $rel_sponsored ) . esc_attr( $rel_noreferrer ) .esc_attr( $rel_noopener ); ?>">
				<?php echo esc_html( $header_button_text ); ?>
			</a>
		</div>
		<?php
	}
}
