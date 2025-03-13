<?php

/**
 * Class Middle_Row.
 */

namespace Rishi\Customizer\Header\Elements;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

/**
 * Class Middle_Row
 */
class Middle_Row extends Abstracts\Builder_Element {
	public function get_id() {
		return 'middle-row';
	}

    public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'Middle Row', 'rishi' );
	}

	public function config() {
		return array(
			'name' => $this->get_label(),
		);
	}

	public function is_row_element() {
		return true;
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return void
	 */
	public function get_options( $default_background = null ) {

		$row_default = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults()['middle-row'];
		if(is_null($default_background)) $default_background = $row_default['headerRowBackground'];

		$options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'General', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [

					'header_hide_row'   => [
						'label'   => false,
						'control' => ControlTypes::HIDDEN,
						'value'   => false,
						'divider' => 'bottom',
						'setting' => [
							'type' => 'option',
						],
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],

					'headerRowWidth'  => [
						'label'   => __( 'Container Width', 'rishi' ),
						'control' => ControlTypes::INPUT_RADIO,
						'value'   => 'default',
						'divider'    => 'bottom',
						'design'  => 'block',
						'choices' => [
							'default'    => __( 'Default', 'rishi' ),
							'full-width' => __( 'Full Width', 'rishi' ),
							'custom'     => __( 'Custom', 'rishi' ),
						],
					],
					'custom_header_row_width'  => [
						'label'      => __( 'Custom Width', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '1200px',
						'responsive' => false,
						'divider'    => 'bottom',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 750, 'max' => 1920 ],
						] ),
						'conditions' => ['headerRowWidth' => 'custom']
					],
				],
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [
					'row_bg_color_group'  => [
						'label'   => __( 'Background Color', 'rishi' ),
						'control'    => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value'   => [
							'headerRowBackground' => $default_background,
						],
						'settings' => [
							'headerRowBackground'            => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::COLOR_PICKER,
								'design'     => 'inline',
								'colorPalette'	  => true,
								'responsive' => false,
								'value'      => $default_background,
								'pickers' => [
									[
										'title' => __('Initial', 'rishi'),
										'id' => 'default',
									],
								],
							],
						]
					],
					'row_top_border_color_group'  => [
						'label'   => __( 'Top Border Color', 'rishi' ),
						'control'    => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value'   => [
							'headerRowTopBorder' => [
								'width' => 1,
								'style' => 'none',
								'color' => [
									'color' => 'rgba(44,62,80,0.2)',
								],
							]
						],
						'settings' => [
							'headerRowTopBorder'            => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::BORDER,
								'design'     => 'inline',
								'responsive' => false,
								'value'      => [
									'width' => 1,
									'style' => 'none',
									'color' => [
										'color' => 'rgba(44,62,80,0.2)',
									],
								]
							],
						]
					],

					'row_btm_border_color_group'  => [
						'label'   => __( 'Bottom Border Color', 'rishi' ),
						'control'    => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value'   => [
							'headerRowBottomBorder' =>	[
								'width' => 1,
								'style' => 'none',
								'color' => [
									'color' => 'rgba(44,62,80,0.2)',
								],
							]
						],
						'settings' => [
							'headerRowBottomBorder' => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::BORDER,
								'design'     => 'inline',
								'responsive' => false,
								'value'      => [
									'width' => 1,
									'style' => 'none',
									'color' => [
										'color' => 'rgba(44,62,80,0.2)',
									],
								]
							],
						]
					],
					'headerRowShadow'            => [
						'label'                 => __( 'Box Shadow', 'rishi' ),
						'control'               => ControlTypes::BOX_SHADOW,
						'responsive'            => false,
						'hide_shadow_placement' => true,
						'divider'    			=> 'bottom',
						'design'                => 'inline',
						'value' => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value( [
							'enable'   => false,
							'h_offset' => '0px',
							'v_offset' => '10px',
							'blur'     => '20px',
							'spread'   => '0px',
							'inset'    => false,
							'color'    => 'rgba(44,62,80,0.05)'
						])
					],

					'headerRowPadding' => [
						'label'      => __('Padding', 'rishi'),
						'control'    => ControlTypes::INPUT_SPACING,
						'divider'    => 'bottom',
						'responsive' => true,
						'value'      => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '16',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '16',
									'unit'	 => 'px'
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '16',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '16',
									'unit'	 => 'px'
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '16',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '16',
									'unit'	 => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
					],
					'headerRowItemSpacing'  => [
						'label'      => __( 'Item Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => [
							'desktop' => '15px',
							'tablet'  => '15px',
							'mobile'  => '15px'
						],
						'divider'    => 'bottom',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => 'px', 'min' => 0, 'max' => 500 ],
								] ),
						'responsive' => true,
						'help'       => __( 'Set the item spacing.', 'rishi' ),
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
	public function dynamic_styles() {

		$row_default      = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults()['middle-row'];
		$custom_width     = $this->get_mod_value( 'custom_header_row_width', $row_default['custom_header_row_width']  );
		$boxshadow        = $this->get_mod_value( 'headerRowShadow', $row_default['headerRowShadow'] );
		$headerRowPadding = $this->get_mod_value( 'headerRowPadding', $row_default['headerRowPadding'] );
		$item_gap         = $this->get_mod_value( 'headerRowItemSpacing', $row_default['headerRowItemSpacing'] );

		$rowBgColor  = $this->get_mod_value( 'row_bg_color_group', [
			'headerRowBackground' => $row_default['headerRowBackground']
		] );

		$topBorderColor  = $this->get_mod_value( 'row_top_border_color_group', [
			'headerRowTopBorder' => $row_default['headerRowTopBorder']
		] );

		$btmBorderColor  = $this->get_mod_value( 'row_btm_border_color_group', [
			'headerRowBottomBorder' => $row_default['headerRowBottomBorder']
		] );

		$options =  array(
			'custom_header_row_width'     => array(
				'selector'     => '.site-header .header-row.middle-row',
				'variableName' => 'rowContainerWidth',
				'value'        => $custom_width,
				'responsive'   => false,
				'type'         => 'slider'
			),
			'headerRowBackground'      => array(
				'value'     => $rowBgColor['headerRowBackground'],
				'type'      => 'color',
				'default'   => $row_default['headerRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.site-header .header-row.middle-row',
					)
				),
			),
			'headerRowTopBorder'      => array(
				'value'     => $topBorderColor['headerRowTopBorder'],
				'type'      => 'divider',
				'default'   => $row_default['headerRowTopBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-top',
						'selector' => '.site-header .header-row.middle-row',
					)
				),
			),
			'headerRowBottomBorder'      => array(
				'value'     => $btmBorderColor['headerRowBottomBorder'],
				'type'      => 'divider',
				'default'   => $row_default['headerRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-bottom',
						'selector' => '.site-header .header-row.middle-row',
					)
				),
			),
			'headerRowShadow' => array(
				'value'     => $boxshadow,
				'default' 	=> $row_default['headerRowShadow'],
				'variables' => array(
					'default' => array(
						'variable' => 'box-shadow',
						'selector' => '.site-header .header-row.middle-row',
					),
				),
				'type'      => 'boxshadow',
			),
			'headerRowPadding' => array(
				'selector'     => '.site-header .header-row.middle-row',
				'variableName' => 'padding',
				'value'        => $headerRowPadding,
				'unit'		   => 'px',
				'type'         => 'spacing',
				'property'     => 'padding',
				'responsive'   => true
			),
			'headerRowItemSpacing'     => array(
				'selector'     => '.site-header .header-row.middle-row',
				'variableName' => 'item-gap',
				'value'        => $item_gap,
				'responsive'   => true,
				'type'         => 'slider'
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
	 *
	 * @param string $device
	 * @return void
	 */
	public function render( $device = 'desktop') {
	}
}
