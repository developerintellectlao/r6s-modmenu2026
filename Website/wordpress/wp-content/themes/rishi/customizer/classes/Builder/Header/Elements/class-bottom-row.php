<?php
/**
 * Class for the bottom row of the header.
 *
 * Handles the generation and styling of the bottom row in the header.
 *
 * @package Rishi
 */
namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\Abstracts;
class Bottom_Row extends Abstracts\Builder_Element {

	/**
	 * Get ID
	 *
	 * This method returns the ID of the bottom row.
	 *
	 * @return string The ID of the bottom row.
	 */
	public function get_id() {
		return 'bottom-row';
	}

	/**
	 * Get Section Type
	 *
	 * This method returns the section type of the bottom row.
	 *
	 * @return string The section type of the bottom row.
	 */
	public function get_builder_type() {
		return 'header';
	}

	/**
	 * Get Label
	 *
	 * This method returns the label of the bottom row.
	 *
	 * @return string The label of the bottom row.
	 */
	public function get_label() {
		return __( 'Bottom Row', 'rishi' );
	}

	/**
	 * Is Row Element
	 *
	 * This method checks if the bottom row is a row element.
	 *
	 * @return bool True if the bottom row is a row element, false otherwise.
	 */
	public function is_row_element() {
		return true;
	}

	/**
	 * Add customizer settings for the element
	 *
	* @return array get options
	 */
	public function get_options() {

		$row_default = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults()['bottom-row'];

		$default_background = $row_default['headerRowBackground'];

		$get_header  = rishi_customizer()->header_builder;
		$get_mid_row = $get_header->get_elements()->get_items()['middle-row'];

		$_instance = new $get_mid_row();
		$options   = $_instance->get_options( $default_background );
		return $options;
	}

	/**
	 * Method for dynamic styles
	 *
	 * This method is used to write logic for dynamic CSS changes for the elements.
	 *
	 * @return array The array of dynamic styles.
	 */
	public function dynamic_styles() {
		$row_default      = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults()['bottom-row'];
		$custom_width     = $this->get_mod_value( 'custom_header_row_width', $row_default['custom_header_row_width'] );
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

		$options = array(
			'headerRowPadding'        => array(
				'selector'     => '.site-header .header-row.bottom-row',
				'variableName' => 'padding',
				'value'        => $headerRowPadding,
				'unit'         => 'px',
				'type'         => 'spacing',
				'property'     => 'padding',
				'responsive'   => true,
			),
			'custom_header_row_width' => array(
				'selector'     => '.site-header .header-row.bottom-row',
				'variableName' => 'rowContainerWidth',
				'value'        => $custom_width,
				'responsive'   => false,
				'type'         => 'slider',
			),
			'headerRowBackground'  => array(
				'value'     => $rowBgColor['headerRowBackground'],
				'type'      => 'color',
				'default'   => $row_default['headerRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.site-header .header-row.bottom-row',
					),
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
						'selector' => '.site-header .header-row.bottom-row',
					),
				),
			),
			'headerRowBottomBorder'   => array(
				'value'     => $btmBorderColor['headerRowBottomBorder'],
				'type'      => 'divider',
				'default'   => $row_default['headerRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-bottom',
						'selector' => '.site-header .header-row.bottom-row',
					),
				),
			),
			'headerRowShadow'         => array(
				'value'     => $boxshadow,
				'default'   => $row_default['headerRowShadow'],
				'variables' => array(
					'default' => array(
						'variable' => 'box-shadow',
						'selector' => '.site-header .header-row.bottom-row',
					),
				),
				'type'      => 'boxshadow',
			),
			'headerRowItemSpacing' => array(
				'selector'     => '.site-header .header-row.bottom-row',
				'variableName' => 'item-gap',
				'value'        => $item_gap,
				'responsive'   => true,
				'type'         => 'slider',
			),
		);

		return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
	}

	/**
	 * Render function
	 *
	 * This method is used to render the bottom row.
	 * @param string $desktop
	 * @return void
	 */
	public function render( $device = 'desktop') {
	}
}
