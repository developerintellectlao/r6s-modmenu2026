<?php
/**
 * WooCommerce
 */
namespace Rishi\Customizer\Sections;
use \Rishi\Customizer\Helpers\Defaults as Defaults;
use Rishi\Customizer\Abstracts\Customize_Section;
class Woo_Shop extends Customize_Section {

	protected $id = 'woocommerce_shop';

	protected $panel = 'main_woo_settings';

	protected $container = true;

	public function get_title() {
		return __( 'Shop Page', 'rishi' );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_type() {
		return self::OPTIONS;
	}

	public static function is_enabled() {
		return rishi_is_woocommerce_activated();
	}

	public static function get_order() {
		return 28;
	}
	public function get_dynamic_styles( $dynamic_styles ){
		$woo_defaults = self::get_woo_default_value();

		$shop_title_font = get_theme_mod( 'woo_shop_title_typo',$woo_defaults['woo_shop_title_typo'] );
		$shop_button_font = get_theme_mod( 'woo_shop_button_typo',$woo_defaults['woo_shop_button_typo'] );
		$cardCaptionBoxShadow  = get_theme_mod( 'cardCaptionBoxShadow', $woo_defaults['cardCaptionBoxShadow'] );

		$options = array(
			'woo_alignment' => array(
				'selector'     => '.woocommerce.archive .site-content .archive-title-wrapper .tagged-in-wrapper',
				'variableName' => 'alignment',
				'value'        => get_theme_mod( 'woo_alignment', $woo_defaults['woo_alignment'] ),
				'responsive'   => false,
				'type'         => 'alignment'
			),
			'woo_margin'            => array(
				'selector'     => '.woocommerce.archive .site-content .archive-title-wrapper .tagged-in-wrapper',
				'variableName' => 'wooMargin',
				'value'        => get_theme_mod( 'woo_margin', $woo_defaults['woo_margin'] ),
				'responsive'   => true,
				'type'         => 'slider',
			),
			'shop_page_content_background_color'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['shop_page_content_background_color'] ),
					'selector' => '.woocommerce.archive .site-content .archive-title-wrapper',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce.archive .site-content .archive-title-wrapper',
						'variable' => 'background-color',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'shop_page_content_background_color',$woo_defaults['shop_page_content_background_color'] ),
			),
			'shop_font_color'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['shop_font_color'] ),
					'selector' => '.woocommerce.archive .site-content .archive-title-wrapper',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce.archive .site-content .archive-title-wrapper',
						'variable' => 'shopFontColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'shop_font_color',$woo_defaults['shop_font_color'] ),
			),
			'shop_cards_alignment' => array(
				'selector'     => '.woocommerce',
				'variableName' => 'cardAlignment',
				'value'        => get_theme_mod( 'shop_cards_alignment', $woo_defaults['shop_cards_alignment'] ),
				'responsive'   => false,
				'type'         => 'alignment'
			),
			'shop_button_roundness'  => array(
				'selector'     => '.woocommerce ul.products li.product .caption-content-wrapper :is(.button, .added_to_cart)',
				'variableName' => 'cardbuttonRoundness',
				'value'        => get_theme_mod( 'shop_button_roundness', $woo_defaults['shop_button_roundness'] ),
				'responsive'   => false,
				'type'         => 'slider',
			),
			'shop_button_padding' => [
				'selector'   => '.woocommerce ul.products li.product .caption-content-wrapper :is(.button, .added_to_cart)',
				'important'  => true,
				'value'      => get_theme_mod( 'shop_button_padding', $woo_defaults['shop_button_padding'] ),
				'unit'       => 'px',
				'type'       => 'spacing',
				'responsive' => true,
				'property'   => 'padding',
				'variableName' => 'btnPadding',
			],
			'cardProductTitleColor'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['cardProductTitleColor'] ),
					'selector' => '.woocommerce .woocommerce-loop-product__title',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce .woocommerce-loop-product__title',
						'variable' => 'color',
					),
					'hover'   => array(
						'selector' => '.woocommerce .woocommerce-loop-product__title',
						'variable' => 'colorHover',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'cardProductTitleColor',$woo_defaults['cardProductTitleColor'] ),
			),
			'woo_shopCategoryColor'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['woo_shopCategoryColor'] ),
					'selector' => '.woocommerce .caption-content-wrapper .cat-wrap',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce .caption-content-wrapper .cat-wrap',
						'variable' => 'catColor',
					),
					'hover'   => array(
						'selector' => '.woocommerce .caption-content-wrapper .cat-wrap',
						'variable' => 'catHoverColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'woo_shopCategoryColor',$woo_defaults['woo_shopCategoryColor'] ),
			),
			'cardProductPriceColor'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['cardProductPriceColor'] ),
					'selector' => '.woocommerce .price',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce .price',
						'variable' => 'color',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'cardProductPriceColor',$woo_defaults['cardProductPriceColor'] ),
			),
			'cardCaptionBgColor'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['cardCaptionBgColor'] ),
					'selector' => '.woocommerce',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce',
						'variable' => 'cardCaptionBgColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'cardCaptionBgColor',$woo_defaults['cardCaptionBgColor'] ),
			),
			'cardProductRadius' => [
				'selector'     => '.woocommerce ul.products .product',
				'value'        => get_theme_mod( 'cardProductRadius', $woo_defaults['cardProductRadius'] ),
				'type'         => 'spacing',
				'responsive'   => true,
				'property'     => 'padding',
				'variableName' => 'borderRadius',
			],
			'cardProductButtonText'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['cardProductButtonText'] ),
					'selector' => '.woocommerce ul.products li.product',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce ul.products li.product :is(.button, .added_to_cart)',
						'variable' => 'buttonTextInitialColor',
					),
					'hover'   => array(
						'selector' => '.woocommerce ul.products li.product :is(.button:hover, .added_to_cart:hover)',
						'variable' => 'buttonTextHoverColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'cardProductButtonText',$woo_defaults['cardProductButtonText'] ),
			),
			'cardProductButtonBackground'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['cardProductButtonBackground'] ),
					'selector' => '.woocommerce ul.products li.product',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce ul.products li.product :is(.button, .added_to_cart)',
						'variable' => 'buttonInitialColor',
					),
					'hover'   => array(
						'selector' => '.woocommerce ul.products li.product :is(.button:hover, .added_to_cart:hover)',
						'variable' => 'buttonHoverColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'cardProductButtonBackground',$woo_defaults['cardProductButtonBackground'] ),
			),
			'cardProductButtonBorder'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['cardProductButtonBorder'] ),
					'selector' => '.woocommerce ul.products li.product',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce ul.products li.product :is(.button, .added_to_cart)',
						'variable' => 'btnBorderColor',
					),
					'hover'   => array(
						'selector' => '.woocommerce ul.products li.product :is(.button:hover, .added_to_cart:hover)',
						'variable' => 'btnBorderHoverColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'cardProductButtonBorder',$woo_defaults['cardProductButtonBorder'] ),
			),
			'woo_shop_button_typo' => array(
				'value'      => $shop_button_font,
				'selector'   => '.woocommerce ul.products .product',
				'type'       => 'typography'
			),
			'woo_shop_title_typo' => array(
				'value'      => $shop_title_font,
				'selector'   => '.woocommerce-shop .rishi-tagged-inner .category-title',
				'type'       => 'typography'
			),
			'cardCaptionBoxShadow' => array(
				'value'     =>$cardCaptionBoxShadow,
				'default' => $woo_defaults['cardCaptionBoxShadow'],
				'variables' => array(
					'default' => array(
						'variable' => 'box-shadow',
						'selector' => '.woocommerce ul.products .product',
					),
				),
				'type'      => 'boxshadow',
			),
		);

		foreach( $options as $key => $option ) {
			$dynamic_styles->add( $key, $option );
		}
	}

	protected function get_customize_settings() {
		return $this->settings->get_settings();
	}


	protected function add_controls() {

		$this->wp_customize->add_section(
			'woo_shop_container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'woo_shop_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'woo_shop_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'woo_shop_section_options',
				'section'            => $this->get_id(),
				'innerControls'      => $this->get_customize_settings(),
			)
		);

		$control->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $this->get_setting(),
			'customize_section' => 'container',
			'innerControls'     => $this->get_customize_settings(),
			'sanitize_callback' => function ($input, $setting) {
				return $input;
			},
		);

		$this->wp_customize->add_control( $control );
	}

	/**
	 * Set default value for Woo page.
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
						'desktop' => '1.75',
						'tablet'  => '1.75',
						'mobile'  => '1.75',
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
						'desktop' => '18px',
						'tablet'  => '18px',
						'mobile'  => '18px',
					),
					'line-height'            => array(
						'desktop' => '1.2',
						'tablet'  => '1.2',
						'mobile'  => '1.2',
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
