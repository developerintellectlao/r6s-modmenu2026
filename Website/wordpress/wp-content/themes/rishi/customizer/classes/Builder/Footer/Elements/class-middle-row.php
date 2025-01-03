<?php
/**
 * Class Middle_Row.
 */

namespace Rishi\Customizer\Footer\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

class Middle_Row extends Abstracts\Builder_Element {
	public function get_id() {
		return 'middle-row';
	}

    public function get_builder_type() {
		return 'footer';
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
	public function get_options( $top_spacing = null,$bottom_spacing = null,$items_per_row = null, $id = null ) {

		$row_default = Defaults::get_footer_row_defaults()['middle-row'];

		$default_background = $row_default['footerRowBackground'];
		$items_spacing      = $row_default['rowItemSpacing'];
		$col_spacing        = $row_default['rowColumnSpacing'];

		if ( is_null( $top_spacing ) ) {
			$top_spacing = $row_default['rowTopSpacing'];
		}

		if ( is_null( $bottom_spacing ) ) {
			$bottom_spacing = $row_default['rowBottomSpacing'];
		}

		if ( is_null( $items_per_row ) ) $items_per_row  =  $row_default['items_per_row'];

		if ( is_null( $id ) ) $id  =  $this->get_id();

		$options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'General', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [
					'hide_footer_row'  => [
						'label'               => false,
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],

					'footerRowWidth'   => [
						'label'   => __( 'Row Container Width', 'rishi' ),
						'control' => ControlTypes::INPUT_RADIO,
						'value'   => 'default',
						'design'  => 'block',
						'choices' => [
								'default'    => __( 'Default', 'rishi' ),
								'full-width' => __( 'Full Width', 'rishi' ),
								'custom'     => __( 'Custom', 'rishi' ),
							],
					],
					'custom_footer_row_width'  => [
						'label'      => __( 'Custom Width', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '1200px',
						'responsive' => false,
						'divider'    => 'top:bottom',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 750, 'max' => 1920 ],
						] ),
						'conditions' => [
							'footerRowWidth' => 'custom',
						],
					],
					'items_per_row'                           => [
						'label'   => __( 'Columns per row', 'rishi' ),
						'control' => ControlTypes::INPUT_RADIO,
						'value'   => $items_per_row,
						'divider' => 'bottom',
						'design'  => 'block',
						'choices'     => [
								'1' => 1,
								'2' => 2,
								'3' => 3,
								'4' => 4,
								'5' => 5
							],
					],

					'2_columns_layout' => [
						'label'         => __( 'Columns Layout', 'rishi' ),
						'control'       => ControlTypes::IMAGE_PICKER,
						'attr'          => [
								'data-ratio' => '2:1',
								'data-usage' => 'footer-layout',
								'className'	 => 'rishi-columns-layout',
								'data-columns' => '3',
							],
						'conditions' => [
							'items_per_row' => '2'
						],
						'value'         => 'repeat(2, 1fr)',
						'divider'       => 'bottom',
						'choices'       => [
								'repeat(2, 1fr)' => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1' ),
								],

								'2fr 1fr'        => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '2-1' ),
								],

								'1fr 2fr'        => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-2' ),
								],

								'3fr 1fr'        => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '3-1' ),
								],

								'1fr 3fr'        => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-3' ),
								],
							],
					],

					'3_columns_layout' => [
						'label'         => __( 'Columns Layout', 'rishi' ),
						'control'       => ControlTypes::IMAGE_PICKER,
						'conditions'	=> [
							'items_per_row' => '3'
						],
						'attr'          => [
								'data-ratio' => '2:1',
								'data-usage' => 'footer-layout',
								'className'			=> 'rishi-columns-layout',
							],
						'value'         => 'repeat(3, 1fr)',
						'divider'       => 'bottom',
						'choices'       => [
								'repeat(3, 1fr)' => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1-1' ),
								],

								'1fr 2fr 1fr'    => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-2-1' ),
								],

								'2fr 1fr 1fr'    => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '2-1-1' ),
								],

								'1fr 1fr 2fr'    => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1-2' ),
								],
							],
					],

					'4_columns_layout' => [
						'label'         => __( 'Columns Layout', 'rishi' ),
						'conditions'    => [
							'items_per_row' => '4'
						],
						'control'       => ControlTypes::IMAGE_PICKER,
						'attr'          => [
							'data-ratio' => '2:1',
							'className'	 => 'rishi-columns-layout',
						],
						'value'         => 'repeat(4, 1fr)',
						'divider'       => 'bottom',
						'choices'       => [
								'repeat(4, 1fr)'  => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1-1-1' ),
								],

								'1fr 2fr 2fr 1fr' => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-2-2-1' ),
								],

								'2fr 1fr 1fr 1fr' => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '2-1-1-1' ),
								],

								'1fr 1fr 1fr 2fr' => [
									'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1-1-2' ),
								],
							],
					],

					'5_columns_layout' => [
						'label'         => __( 'Columns Layout', 'rishi' ),
						'control'       => ControlTypes::IMAGE_PICKER,
						'attr'          => [
							'data-ratio' => '2:1',
							'className'	 => 'rishi-columns-layout',
						],
						'value'         => 'repeat(5, 1fr)',
						'conditions'    => [
							'items_per_row' => '5'
						],
						'responsive'    => false,
						'divider'       => 'bottom',
						'choices'       => [
							'repeat(5, 1fr)'      => [
								'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1-1-1-1' ),
							],

							'2fr 1fr 1fr 1fr 1fr' => [
								'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '2-1-1-1-1' ),
							],

							'1fr 1fr 1fr 1fr 2fr' => [
								'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1-1-1-2' ),
							],

							'1fr 1fr 2fr 1fr 1fr' => [
								'src' => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( '1-1-2-1-1' ),
							],
						],
					],
					'footer_row_column_direction' => [
						'control'    => ControlTypes::INPUT_RADIO,
						'label'      => __('Column Direction', 'rishi'),
						'view'       => 'text',
						'design'     => 'block',
						'value' => 'vertical',
						'choices' => [
							'vertical' => __('Vertical', 'rishi'),
							'horizontal' => __('Horizontal', 'rishi')
						],
					],
					'rowColumnSpacing'   => [
						'label'      => __( 'Column Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => $col_spacing,
						'divider'    => 'top',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => 'px', 'min' => 0, 'max' => 500 ],
								] ),
						'responsive' => true,
					],
					'rowTopSpacing'                     => [
						'label'      => __( 'Top Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => $top_spacing,
						'divider'    => 'top',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => 'px', 'min' => 0, 'max' => 500 ],
								] ),
						'responsive' => true,
						'help'       => __( 'Set the container\'s top spacing.', 'rishi' ),
					],
					'rowBottomSpacing'                     => [
						'label'      => __( 'Bottom Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => $bottom_spacing,
						'divider'    => 'top',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => 'px', 'min' => 0, 'max' => 500 ],
								] ),
						'responsive' => true,
						'help'       => __( 'Set the container\'s bottom spacing.', 'rishi' ),
					],
					'rowItemSpacing'                     => [
						'label'      => __( 'Item Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => $items_spacing,
						'divider'    => 'top',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => 'px', 'min' => 0, 'max' => 500 ],
								] ),
						'responsive' => true,
						'help'       => __( 'Set the container\'s item spacing.', 'rishi' ),
					],
					'footer_row_vertical_alignment'           => [
						'control'    => ControlTypes::INPUT_RADIO,
						'label'      => __( 'Vertical Alignment', 'rishi' ),
						'view'       => 'text',
						'design'     => 'block',
						'divider'    => 'top',
						'attr'       => [ 'data-type' => 'vertical-alignment' ],
						'value'      => 'flex-start',
						'choices'    => [
							'flex-start' => __('Top', 'rishi'),
							'center'     => __('Center', 'rishi'),
							'flex-end'   => __('Bottom', 'rishi'),
						],
					]
				],
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [

					'footerRowTopBorderFullWidth' => [
						'label'   => __( 'Border Width', 'rishi' ),
						'control' => ControlTypes::INPUT_RADIO,
						'value'   => 'default',
						'divider' => 'bottom',
						'design'  => 'block',
						'choices' => [
							'default'    => __( 'Default', 'rishi' ),
							'full-width' => __( 'Full Width', 'rishi' ),
						],
					],

					'footerRowBackground' => [
						'label'           => __( 'Background', 'rishi' ),
						'control'         => ControlTypes::COLOR_PICKER,
						'design'          => 'inline',
						'divider'         => 'bottom',
						'colorPalette'	  => true,
						'value'   => $default_background,
						'pickers' => array(
							array(
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							),
						),
					],

					'footerRowTopDivider' => [
						'label'   => __( 'Top Divider', 'rishi' ),
						'control' => ControlTypes::BORDER,
						'design'  => 'inline',
						'divider' => 'bottom',
						'value'      => [
							'width' => 1,
							'style' => 'none',
							'color' => [
								'color' => '#dddddd',
							],
						]
					],

					'footerRowBottomDivider' => [
						'label'   => __( 'Bottom Divider', 'rishi' ),
						'control' => ControlTypes::BORDER,
						'design'  => 'inline',
						'divider' => 'bottom',
						'value'      => [
							'width' => 1,
							'style' => 'none',
							'color' => [
								'color' => '#dddddd',
							],
						]
					],

					'footerColumnsDivider'  => [
						'label'   => __( 'Columns Divider', 'rishi' ),
						'control' => ControlTypes::BORDER,
						'design'  => 'inline',
						'divider' => 'bottom',
						'help'    => __( 'This divider will be placed between columns, elements and widgets.', 'rishi' ),
						'value'   => [
							'width' => 1,
							'style' => 'none',
							'color' => [
								'color' => '#dddddd',
							],
						],
					],

					$id . '-footerWidgetsTitleFont'    => rishi_typography_control_option([
						'control' => ControlTypes::TYPOGRAPHY,
						'label'   => __( 'Widgets Title Font', 'rishi' ),
						'value'   => Defaults::typography_value( [
							'size'            => array(
								'desktop' => '16px',
								'tablet'  => '16px',
								'mobile'  => '16px',
							),
							'line-height'            => array(
								'desktop' => '1.75em',
								'tablet'  => '1.75em',
								'mobile'  => '1.75em',
							),
							'letter-spacing'            => array(
								'desktop' => '0.4px',
								'tablet'  => '0.4px',
								'mobile'  => '0.4px',
							),
							'text-transform' => 'uppercase',
						] ),
						'design'     => 'inline',
						'divider' => 'bottom',
					]),

					'footerWidgetsTitleColor'                 => [
						'label'      => __( 'Widgets Title Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'design'     => 'inline',
						'colorPalette'	  => true,
						'divider' => 'bottom',
						'value'      => [
							'default' => [
								'color' => 'var(--paletteColor5)',
							],
						],

						'pickers'    => [
							[
								'title'   => __( 'Initial', 'rishi' ),
								'id'      => 'default',
								'inherit' => 'var(--headingColor)'
							],
						],
					],

					$id . '-footerWidgetsFont'                       => rishi_typography_control_option([
						'control' => ControlTypes::TYPOGRAPHY,
						'label'   => __( 'Widgets Font', 'rishi' ),
						'value'   => Defaults::typography_value( [
							'size'            => array(
								'desktop' => '16px',
								'tablet'  => '16px',
								'mobile'  => '16px',
							),
							'letter-spacing'            => array(
								'desktop' => '0.4px',
								'tablet'  => '0.4px',
								'mobile'  => '0.4px',
							),
						] ),
						'divider' => 'bottom',
						'design' => 'inline',
					]),

					'rowFontColor'  => [
						'label'      => __( 'Widgets Font Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'design'     => 'inline',
						'colorPalette'	  => true,
						'divider' => 'bottom',
						'value'      => [
							'default'      => [
								'color' => 'var(--paletteColor5)',
							],

							'link_initial' => [
								'color' => 'var(--paletteColor5)',
							],

							'link_hover'   => [
								'color' => 'var(--paletteColor3)',
							],
						],

						'pickers'    => [
							[
								'title'   => __( 'Text Initial', 'rishi' ),
								'id'      => 'default',
								'inherit' => 'var(--color)'
							],

							[
								'title' => __( 'Link Initial', 'rishi' ),
								'id'    => 'link_initial',
							],

							[
								'title'   => __( 'Link Hover', 'rishi' ),
								'id'      => 'link_hover',
								'inherit' => 'var(--linkHoverColor)'
							],
						],
					]
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
		$row_default = Defaults::get_footer_row_defaults()['middle-row'];

		$row_col = [
			'1' => 'repeat(1, 1fr)',
			'2' => $this->get_mod_value( '2_columns_layout', $row_default['2_columns_layout'] ),
			'3' => $this->get_mod_value( '3_columns_layout', $row_default['3_columns_layout'] ),
			'4' => $this->get_mod_value( '4_columns_layout', $row_default['4_columns_layout'] ),
			'5' => $this->get_mod_value( '5_columns_layout', $row_default['5_columns_layout'] )
		];

		$col_gap                 = $this->get_mod_value( 'rowColumnSpacing', $row_default['rowColumnSpacing'] );
		$top_gap                 = $this->get_mod_value( 'rowTopSpacing', $row_default['rowTopSpacing'] );
		$bottom_gap              = $this->get_mod_value( 'rowBottomSpacing', $row_default['rowBottomSpacing'] );
		$item_gap                = $this->get_mod_value( 'rowItemSpacing', $row_default['rowItemSpacing'] );
		$custom_container        = $this->get_mod_value( 'custom_footer_row_width', $row_default['custom_footer_row_width'] );
		$bg_color                = $this->get_mod_value( 'footerRowBackground', $row_default['footerRowBackground'] );
		$rowFontColor            = $this->get_mod_value( 'rowFontColor', $row_default['rowFontColor'] );
		$footerWidgetsTitleColor = $this->get_mod_value( 'footerWidgetsTitleColor', $row_default['footerWidgetsTitleColor'] );
		$footerWidgetsTitleFont  = $this->get_mod_value( 'middle-row-footerWidgetsTitleFont', $row_default['middle-row-footerWidgetsTitleFont'] );
		$footerWidgetsFont       = $this->get_mod_value( 'middle-row-footerWidgetsFont', $row_default['middle-row-footerWidgetsFont'] );
		$top_divider             = $this->get_mod_value( 'footerRowTopDivider', $row_default['footerRowTopDivider'] );
		$bot_divider             = $this->get_mod_value( 'footerRowBottomDivider', $row_default['footerRowBottomDivider'] );
		$col_divider             = $this->get_mod_value( 'footerColumnsDivider', $row_default['footerColumnsDivider'] );
		$items                   = $this->get_mod_value( 'items_per_row', $row_default['items_per_row'] );
		$col_val                 = $row_col[$items];

        return array(
			'items_per_row' => array(
				'selector'     => '.rishi-footer .footer-middle-row',
				'variableName' => 'col-no',
				'value'        => $col_val,
				'type'         => 'alignment'
			),
			'rowColumnSpacing'     => array(
				'selector'     => '.rishi-footer .footer-middle-row',
				'variableName' => 'colSpacing',
				'value'        => $col_gap,
				'unit'         => '',
				'responsive'   => true,
				'type'         => 'slider'
			),
			'custom_footer_row_width'     => array(
				'selector'     => '.rishi-footer .footer-middle-row',
				'variableName' => 'rowContainerWidth',
				'value'        => $custom_container,
				'unit'         => '',
				'responsive'   => false,
				'type'         => 'slider'
			),
			'rowTopSpacing'     => array(
				'selector'     => '.rishi-footer .footer-middle-row',
				'variableName' => 'topSpacing',
				'value'        => $top_gap,
				'unit'         => '',
				'responsive'   => true,
				'type' => 'slider'
			),
			'rowBottomSpacing'     => array(
				'selector'     => '.rishi-footer .footer-middle-row',
				'variableName' => 'botSpacing',
				'value'        => $bottom_gap,
				'unit'         => '',
				'responsive'   => true,
				'type' 		  => 'slider'
			),
			'rowItemSpacing'     => array(
				'selector'     => '.rishi-footer .footer-middle-row',
				'variableName' => 'itemSpacing',
				'value'        => $item_gap,
				'unit'         => '',
				'responsive'   => true,
				'type' 		   => 'slider'
			),
			'footerRowBackground'      => array(
				'value'     => $bg_color,
				'type'      => 'color',
				'default'   => $row_default['footerRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.rishi-footer .footer-middle-row',
					)
				),
			),
			'footerWidgetsTitleColor'      => array(
				'value'     => $footerWidgetsTitleColor,
				'type'      => 'color',
				'default'   => $row_default['footerWidgetsTitleColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headingColor',
						'selector' => '.rishi-footer .footer-middle-row',
					)
				)
			),
			'footerWidgetsTitleFont' => array(
				'value'      => $footerWidgetsTitleFont,
				'selector'   => '.rishi-footer .footer-middle-row .widget h1,.rishi-footer .footer-middle-row .widget h2,.rishi-footer .footer-middle-row .widget h3,.rishi-footer .footer-middle-row .widget h4,.rishi-footer .footer-middle-row .widget h5,.rishi-footer .footer-middle-row .widget h6',
				'type'       => 'typography'
			),
			'footerWidgetsFont' => array(
				'value'      => $footerWidgetsFont,
				'selector'   => '.rishi-footer .footer-middle-row .widget',
				'type'       => 'typography'
			),
			'rowFontColor'      => array(
				'value'     => $rowFontColor,
				'type'      => 'color',
				'default'   => $row_default['rowFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'color',
						'selector' => '.rishi-footer .footer-middle-row .widget',
					),
					'link_initial' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.rishi-footer .footer-middle-row .widget',
					),
					'link_hover' => array(
						'variable' => 'linkHoverColor',
						'selector' => '.rishi-footer .footer-middle-row .widget',
					)
				)
			),
			'footerRowTopDivider'      => array(
				'value'     => $top_divider,
				'type'      => 'divider',
				'default'   => $row_default['footerRowTopDivider'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-top',
						'selector' => '.rishi-footer .footer-middle-row',
					)
				),
			),
			'footerRowBottomDivider'      => array(
				'value'     => $bot_divider,
				'type'      => 'divider',
				'default'   => $row_default['footerRowBottomDivider'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-bottom',
						'selector' => '.rishi-footer .footer-middle-row',
					)
				),
			),
			'footerColumnsDivider'      => array(
				'value'     => $col_divider,
				'type'      => 'divider',
				'default'   => $row_default['footerColumnsDivider'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'colBorder',
						'selector' => '.rishi-footer .footer-middle-row',
					)
				),
			),
		);
	}

	/**
	 * Renders function
	 * @return void
	 */
	public function render( $device = 'desktop') {
	}
}
