<?php
/**
 * Class Top_Row.
 */
namespace Rishi\Customizer\Footer\Elements;
use Rishi\Customizer\Abstracts;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

class Top_Row extends Abstracts\Builder_Element{

	public function get_id() {
		return  'top-row';
	}

    public function get_builder_type() {
		return 'footer';
	}
    
    public function get_label(){
        return __('Top Row', 'rishi');
    }

    public function config(){
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

		$row_default = Defaults::get_footer_row_defaults()['top-row'];
    
		$top_spacing        = $row_default['rowTopSpacing'];
		$bottom_spacing     = $row_default['rowBottomSpacing'];
		$items_per_row      = $row_default['items_per_row'];

        $get_footer  = rishi_customizer()->footer_builder;
        $get_mid_row = $get_footer->get_elements()->get_items()['middle-row'];

        $_instance = new $get_mid_row;
        $options   = $_instance->get_options(
			$top_spacing, 
			$bottom_spacing,
			$items_per_row,
			$this->get_id()
		);
        return $options;
    }

    /**
     * Write logic for dynamic css change for the elements
     *
     * @return array dynamic styles
     */
    public function dynamic_styles(){

		$row_default = Defaults::get_footer_row_defaults()['top-row'];
		
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
		$footerWidgetsTitleFont  = $this->get_mod_value( 'top-row-footerWidgetsTitleFont', $row_default['top-row-footerWidgetsTitleFont'] );
		$footerWidgetsFont       = $this->get_mod_value( 'top-row-footerWidgetsFont', $row_default['top-row-footerWidgetsFont'] );
		$top_divider             = $this->get_mod_value( 'footerRowTopDivider', $row_default['footerRowTopDivider'] );
		$bot_divider             = $this->get_mod_value( 'footerRowBottomDivider', $row_default['footerRowBottomDivider'] );
		$col_divider             = $this->get_mod_value( 'footerColumnsDivider', $row_default['footerColumnsDivider'] );
		$items                   = $this->get_mod_value( 'items_per_row', $row_default['items_per_row'] );
		$col_val                 = $row_col[$items];

        return array(
			'items_per_row' => array(
				'selector'     => '.rishi-footer .footer-top-row',
				'variableName' => 'col-no',
				'value'        => $col_val,
				'type'         => 'alignment'
			),
			'rowColumnSpacing'     => array(
				'selector'     => '.rishi-footer .footer-top-row',
				'variableName' => 'colSpacing',
				'value'        => $col_gap,
				'responsive'   => true,
				'type'         => 'slider'
			),
			'custom_footer_row_width'     => array(
				'selector'     => '.rishi-footer .footer-top-row',
				'variableName' => 'rowContainerWidth',
				'value'        => $custom_container,
				'responsive'   => false,
				'type'         => 'slider'
			),
			'rowTopSpacing'     => array(
				'selector'     => '.rishi-footer .footer-top-row',
				'variableName' => 'topSpacing',
				'value'        => $top_gap,
				'responsive'   => true,
				'type'         => 'slider'
			),
			'rowBottomSpacing'     => array(
				'selector'     => '.rishi-footer .footer-top-row',
				'variableName' => 'botSpacing',
				'value'        => $bottom_gap,
				'responsive'   => true,
				'type'         => 'slider'
			),
			'rowItemSpacing'     => array(
				'selector'     => '.rishi-footer .footer-top-row',
				'variableName' => 'itemSpacing',
				'value'        => $item_gap,
				'responsive'   => true,
				'type'         => 'slider'
			),
			'footerRowBackground'      => array(
				'value'     => $bg_color,
				'type'      => 'color',
				'default'   => $row_default['footerRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.rishi-footer .footer-top-row',
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
						'selector' => '.rishi-footer .footer-top-row',
					)
				)
			),
			'footerWidgetsTitleFont' => array(
				'value'      => $footerWidgetsTitleFont,
				'selector'   => '.rishi-footer .footer-top-row .widget h1,.rishi-footer .footer-top-row .widget h2,.rishi-footer .footer-top-row .widget h3,.rishi-footer .footer-top-row .widget h4,.rishi-footer .footer-top-row .widget h5,.rishi-footer .footer-top-row .widget h6',
				'type'       => 'typography'
			),
			'footerWidgetsFont' => array(
				'value'      => $footerWidgetsFont,
				'selector'   => '.rishi-footer .footer-top-row .widget',
				'type'       => 'typography'
			),
			'rowFontColor'      => array(
				'value'     => $rowFontColor,
				'type'      => 'color',
				'default'   => $row_default['rowFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'color',
						'selector' => '.rishi-footer .footer-top-row .widget',
					),
					'link_initial' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.rishi-footer .footer-top-row .widget',
					),
					'link_hover' => array(
						'variable' => 'linkHoverColor',
						'selector' => '.rishi-footer .footer-top-row .widget',
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
						'selector' => '.rishi-footer .footer-top-row',
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
						'selector' => '.rishi-footer .footer-top-row',
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
						'selector' => '.rishi-footer .footer-top-row',
					)
				),
			),
		);
    }

    /**
     * Renders function
     * @return void
     */
    public function render( $device = 'desktop'){
    }
}