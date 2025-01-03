<?php
/**
 * WooCommerce Shop Page Customizer Settings
 */
namespace Rishi\Customizer\Settings;

use Rishi\Customizer\Abstracts\Customize_Settings;
use Rishi\Customizer\ControlTypes;
use \Rishi\Customizer\Helpers\Defaults as Defaults;
class Woo_Shop_Setting extends Customize_Settings {

	public function add_settings() {
		$this->add_woo_shop_settings();
	}

	protected function add_woo_shop_settings(){
		$woo_defaults = self::get_woo_default_value();

		$this->add_setting( 'woo_shop_title_panel', array(
			'label'   => __( 'Shop Title', 'rishi' ),
			'control' => ControlTypes::PANEL,
		));

		$this->add_setting( 'woo_shop_title_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'breadcrumbs_ed_archive_product' => array(
					'label'   => __( 'Breadcrumb', 'rishi' ),
					'control' => ControlTypes::INPUT_SWITCH,
					'value'   => $woo_defaults['breadcrumbs_ed_archive_product'],
					'divider' => 'bottom',

				),
				'shop_page_title' => [
					'label'   => __( 'Enable Page Title', 'rishi'),
					'control' => ControlTypes::INPUT_SWITCH,
					'divider' => 'bottom',
					'value'   => $woo_defaults['shop_page_title'],

				],
				'woo_alignment' => array(
					'control'    => ControlTypes::INPUT_RADIO,
					'label'      => __( 'Horizontal Alignment', 'rishi' ),
					'value'      => $woo_defaults['woo_alignment'],
					'view'       => 'text',
					'attr'       => array( 'data-type' => 'alignment' ),
					'design'     => 'block',
					'divider'    => 'bottom',
					'choices'    => array(
						'left'   => __('Left', 'rishi'),
						'center' => __('Center', 'rishi'),
						'right'  => __('Right', 'rishi'),
					),
					'conditions' => [
						'shop_page_title' => 'yes'
					]
				),
				'woo_margin'    => array(
					'label'      => __( 'Vertical Spacing', 'rishi' ),
					'control'    => ControlTypes::INPUT_SLIDER,
					'value'      => $woo_defaults['woo_margin'],
					'divider'     => 'bottom',
					'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
						[ 'unit' => 'px', 'min' => 0, 'max' => 300 ],
					] ),
					'responsive' => true,
					'conditions' => [
						'shop_page_title' => 'yes'
					]
				)
			),
			'parent' => 'woo_shop_title_panel'
		));

		$this->add_setting( 'woo_shop_title_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'shop_page_content_background_color' => [
					'label'           => __('Content Area Background Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'colorPalette'	  => true,
					'design'          => 'inline',
					'divider'         => 'bottom',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['shop_page_content_background_color'],
					'pickers' => [
						[
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						],
					],
				],
				'shop_font_color' => [
					'label'           => __( 'Font Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'bottom',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['shop_font_color'],
					'pickers' => [
						[
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						],
					],
					'conditions' => [
						'shop_page_title' => 'yes'
					]
				],
				'woo_shop_title_typo'    =>  rishi_typography_control_option([
					'control'     => ControlTypes::TYPOGRAPHY,
					'label'       => __( 'Title Typography', 'rishi' ),
					'divider'     => 'bottom',
					'value'       => $woo_defaults['woo_shop_title_typo'],
					'conditions' => [
						'shop_page_title' => 'yes'
					]
				]),
			),
			'parent' => 'woo_shop_title_panel'
		));

		$this->add_setting( \Rishi\Customizer\Helpers\Basic::uniqid(), array(
			'label'   => __( 'Shop Structure', 'rishi' ),
			'control' => ControlTypes::TITLE,
			'desc'    => __( ' It allows you to customize through WooCommerce Shop Page Structure and its Card Options', 'rishi' ),
		));

		$this->add_setting( 'woocommerce_cols', array(
			'label'      => __( 'No of Columns', 'rishi' ),
			'control'    => ControlTypes::INPUT_NUMBER,
			'design'     => 'inline',
			'value'      => $woo_defaults['woocommerce_cols'],
			'min'        => 1,
			'max'        => 5,
			'divider'    => 'top',
			'responsive' => false,
		));

		$this->add_setting('woocommerce_rows', array(
			'label'      => __( 'No of Rows', 'rishi' ),
			'control'    => ControlTypes::INPUT_NUMBER,
			'design'     => 'inline',
			'value'      => $woo_defaults['woocommerce_rows'],
			'min'        => 1,
			'max'        => 5,
			'divider'    => 'top:bottom',
			'responsive' => false,
		));

		$this->add_setting( 'shop_options_panel', array(
			'label'   => __( 'Cards Options', 'rishi' ),
			'control' => ControlTypes::PANEL,
		));

		$this->add_setting('card_opts_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'has_woo_category' => array(
					'label'   => __( 'Show Category', 'rishi' ),
					'control' => ControlTypes::INPUT_SWITCH,
					'value'   => $woo_defaults['has_woo_category'],
					'divider' => 'bottom',

				),
				'has_star_rating' => array(
					'label'   => __( 'Star Rating', 'rishi' ),
					'control' => ControlTypes::INPUT_SWITCH,
					'value'   => $woo_defaults['has_star_rating'],
					'divider' => 'bottom',

				),
				'shop_cards_type' => [
					'label'   => __( 'Card Design', 'rishi' ),
					'control'    => ControlTypes::INPUT_SELECT,
					'value'   => $woo_defaults['shop_cards_type'],
					'view'    => 'text',
					'divider' => 'bottom',
					'design'  => 'inline',
					'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
						[
							'normal'     => __('Normal', 'rishi'),
							'background' => __('Background', 'rishi'),
						]
					),
				],
				'shop_cards_alignment' => array(
					'control'    => ControlTypes::INPUT_RADIO,
					'label'      => __( 'Content Alignment', 'rishi' ),
					'value'      => $woo_defaults['shop_cards_alignment'],
					'view'       => 'text',
					'attr'       => array( 'data-type' => 'alignment' ),
					'responsive' => false,
					'design'     => 'block',
					'divider'    => 'bottom',
					'choices'    => array(
						'left'   => '',
						'center' => '',
						'right'  => '',
					),
				),
				'shop_button_roundness'    => array(
					'label'      => __( 'Button Roundness', 'rishi' ),
					'control'    => ControlTypes::INPUT_SLIDER,
					'divider'    => 'bottom',
					'value'      => $woo_defaults['shop_button_roundness'],
					'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
								[ 'unit' => 'px', 'min' => 0, 'max' => 70 ],
							] ),
					'responsive' => false,
				),
				'shop_button_padding'                        => [
					'label'      => __( 'Button Padding', 'rishi' ),
					'control'       => ControlTypes::INPUT_SPACING,
					'divider'    => 'bottom',
					'value'      => $woo_defaults['shop_button_padding'],
					'responsive' => true,
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
				]
			),
			'parent' => 'shop_options_panel'
		));

		$this->add_setting('card_opts_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'cardProductTitleColor' => [
					'label'           => __('Title Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['cardProductTitleColor'],
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
				'woo_shopCategoryColor' => [
					'label'           => __('Category Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'divider'         => 'top',
					'colorPalette'	  => true,
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['woo_shopCategoryColor'],
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
				'cardProductPriceColor' => [
					'label'           => __('Price Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'top',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['cardProductPriceColor'],
					'pickers' => [
						[
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						]
					],
				],
				'cardCaptionBgColor' => [
					'label'           => __('Card Caption Background Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'divider'         => 'top',
					'colorPalette'	  => true,
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['cardCaptionBgColor'],
					'pickers' => [
						[
							'title' => __('Initial', 'rishi'),
							'id' => 'default',
						]
					],
					'conditions' => [
						'shop_cards_type' => 'background'
					]
				],
				'cardCaptionBoxShadow' => [
					'label'      => __( 'Card Caption Box Shadow', 'rishi' ),
					'control'    => ControlTypes::BOX_SHADOW,
					'design'     => 'inline',
					'divider'    => 'top',
					'responsive' => false,
					'value'      => $woo_defaults['cardCaptionBoxShadow'],
					'conditions' => [
						'shop_cards_type' => 'background'
					]
				],
				'cardProductRadius'                        => [
					'label'      => __( 'Card Border Radius', 'rishi' ),
					'control'       => ControlTypes::INPUT_SPACING,
					'divider'    => 'top',
					'value'      => $woo_defaults['cardProductRadius'],
					'responsive' => true,
					'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
				],
				'cardProductButtonText' => [
					'label'           => __('Text Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'top',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['cardProductButtonText'],
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
				'cardProductButtonBackground' => [
					'label'           => __('Background Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'top',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['cardProductButtonBackground'],
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
				'cardProductButtonBorder' => [
					'label'           => __('Border Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'top',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['cardProductButtonBorder'],
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
				'woo_shop_button_typo'    => rishi_typography_control_option([
					'control'     => ControlTypes::TYPOGRAPHY,
					'label'       => __( 'Button Typography', 'rishi' ),
					'divider'     => 'top',
					'value'       => $woo_defaults['woo_shop_button_typo'],
				]),
			),
			'parent' => 'shop_options_panel'
		));

		$this->add_setting(\Rishi\Customizer\Helpers\Basic::uniqid(), array(
			'label'   => __( 'Page Elements', 'rishi' ),
			'control' => ControlTypes::TITLE,
		));

		$this->add_setting('has_shop_sort', array(
			'label' => __( 'Shop Sort', 'rishi' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value'   => $woo_defaults['has_shop_sort'],
			'divider' => 'bottom',
		));

		$this->add_setting('has_shop_results_count', array(
			'label'   => __( 'Shop Results Count', 'rishi' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value'   => $woo_defaults['has_shop_results_count'],
			'divider' => 'bottom',
		));
	}

	/**
	 * Set default value for Woo Shop page.
	 */
	protected static function get_woo_default_value() {

		$woo_defaults = array(
			'breadcrumbs_ed_archive_product' 	=> 'yes',
			'woo_alignment'               	 	=> 'left',
			'woo_margin'                	 	=> array(
				'desktop' => '85px',
				'tablet'  => '60px',
				'mobile'  => '30px',
			),
			'shop_page_content_background_color' => [
				'default' => [
					'color' => 'var(--paletteColor7)',
				],
			],
			'shop_font_color'       	 => [
				'default' => [
					'color' => 'var(--paletteColor1)',
				],
			],
			'woo_shop_title_typo'        => Defaults::typography_value(
				array(
					'size'            => array(
						'desktop' => '40px',
						'tablet'  => '40px',
						'mobile'  => '40px',
					),
					'line-height'            => array(
						'desktop' => '1.75em',
						'tablet'  => '1.75em',
						'mobile'  => '1.75em',
					),
					'weight'      => '600',
				)
			),
			'shop_button_padding' => [
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value( [
					'linked' => false,
					'top'    => '15',
					'left'   => '34',
					'right'  => '34',
					'bottom' => '15',
					'unit'   => 'px'
				] ),
				'tablet' => \Rishi\Customizer\Helpers\Basic::spacing_value( [
					'linked' => false,
					'top'    => '15',
					'left'   => '34',
					'right'  => '34',
					'bottom' => '15',
					'unit'   => 'px'
				] ),
				'mobile' => \Rishi\Customizer\Helpers\Basic::spacing_value( [
					'linked' => false,
					'top'    => '15',
					'left'   => '34',
					'right'  => '34',
					'bottom' => '15',
					'unit'   => 'px'
				] ),
			],

			'shop_button_roundness' => '3px',
			'woocommerce_cols'      => 4,
			'woocommerce_rows'      => 4,
			'shop_page_title'		=> 'yes',
			'has_woo_category'      => 'yes',
			'has_star_rating'       => 'yes',
			'shop_cards_type'       => 'normal',
			'shop_cards_alignment'  => 'center',
			'cardProductTitleColor' => [
				'default' => [
					'color' => 'var(--paletteColor2)',
				],

				'hover' => [
					'color' => 'var(--paletteColor3)',
				],
			],
			'woo_shopCategoryColor' => [
				'default' => [
					'color' => 'var(--paletteColor1)',
				],
				'hover' => [
					'color' => 'var(--paletteColor3)',
				]
			],
			'cardProductPriceColor' => [
				'default' => [
					'color' => 'var(--paletteColor1)',
				],
			],
			'cardCaptionBgColor' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],
			],
			'cardProductButtonText' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],

				'hover' => [
					'color' => 'var(--paletteColor5)',
				],
			],
			'cardProductRadius' =>[
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value( [
					'linked' => false,
					'top'    => '3',
					'left'   => '3',
					'right'  => '3',
					'bottom' => '3',
					'unit'   => 'px'
				] ),
				'tablet' => \Rishi\Customizer\Helpers\Basic::spacing_value( [
					'linked' => false,
					'top'    => '3',
					'left'   => '3',
					'right'  => '3',
					'bottom' => '3',
					'unit'   => 'px'
				] ),
				'mobile' => \Rishi\Customizer\Helpers\Basic::spacing_value( [
					'linked' => false,
					'top'    => '3',
					'left'   => '3',
					'right'  => '3',
					'bottom' => '3',
					'unit'   => 'px'
				] ),
			],
			'cardProductButtonBackground' => [
				'default' => [
					'color' => 'var(--paletteColor3)',
				],

				'hover' => [
					'color' => 'var(--paletteColor4)',
				],
			],
			'cardProductButtonBorder' => [
				'default' => [
					'color' => 'var(--paletteColor3)',
				],

				'hover' => [
					'color' => 'var(--paletteColor4)',
				],
			],
			'woo_shop_button_typo'        => Defaults::typography_value(
				array(
					'size'            => array(
						'desktop' => '14px',
						'tablet'  => '14px',
						'mobile'  => '14px',
					),
					'line-height'            => array(
						'desktop' => '1.2em',
						'tablet'  => '1.2em',
						'mobile'  => '1.2em',
					),
					'weight'      => '400',
				)
			),
			'has_shop_sort'          => 'yes',
			'has_shop_results_count' => 'yes',
			'cardCaptionBoxShadow'   => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value(
				array(
					'enable'   => false,
					'h_offset' => '0px',
					'v_offset' => '12px',
					'blur'     => '18px',
					'spread'   => '-6px',
					'inset'    => false,
					'color'    => 'rgba(34, 56, 101, 0.04)',
				)
			),

		);

		return $woo_defaults;
	}
}
