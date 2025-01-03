<?php
/**
 * WooCommerce
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
class Woo_General extends Customize_Section {

	protected $id = 'woocommerce_general';

	protected $panel = 'main_woo_settings';

	protected $container = true;

	public function get_title() {
		return __( 'General Settings', 'rishi' );
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
		return 29;
	}
	public function get_dynamic_styles( $dynamic_styles ){
		$woo_defaults = self::get_woo_general_default_value();
		$woo_shadow   = get_theme_mod( 'woo_content_boxed_shadow', $woo_defaults['woo_content_boxed_shadow'] );

		$options = array(

			'woo_content_background'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['woo_content_background'] ),
					'selector' => '.box-layout.woocommerce .main-content-wrapper, .content-box-layout.woocommerce .main-content-wrapper',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.box-layout.woocommerce .main-content-wrapper, .content-box-layout.woocommerce .main-content-wrapper',
						'variable' => 'background-color',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'woo_content_background',$woo_defaults['woo_content_background'] ),
			),
			'woo_content_boxed_shadow' => array(
				'value'     =>$woo_shadow,
				'default' => $woo_defaults['woo_content_boxed_shadow'],
				'variables' => array(
					'default' => array(
						'variable' => 'box-shadow',
						'selector' => '.woocommerce .main-content-wrapper',
					),
				),
				'type'      => 'boxshadow',
			),
			'salesBagdgeColor'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['salesBagdgeColor'] ),
					'selector' => '.woocommerce span.onsale',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce span.onsale',
						'variable' => 'color',
					),
					'background'   => array(
						'selector' => '.woocommerce span.onsale',
						'variable' => 'colorBg',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'salesBagdgeColor',$woo_defaults['salesBagdgeColor'] ),
			),
			'woo_boxed_content_spacing' => [
				'selector'   => '.box-layout.woocommerce .main-content-wrapper, .content-box-layout.woocommerce .main-content-wrapper',
				'important'  => true,
				'value'      => get_theme_mod( 'woo_boxed_content_spacing', $woo_defaults['woo_boxed_content_spacing'] ),
				'unit'       => 'px',
				'type'       => 'spacing',
				'responsive' => true,
				'property'   => 'padding',
				'variableName' => 'padding',
			],
			'woo_content_boxed_radius' => [
				'selector'   => '.box-layout.woocommerce .main-content-wrapper, .content-box-layout.woocommerce .main-content-wrapper',
				'important'  => true,
				'value'      => get_theme_mod( 'woo_content_boxed_radius', $woo_defaults['woo_content_boxed_radius'] ),
				'unit'       => 'px',
				'type'       => 'spacing',
				'responsive' => true,
				'property'   => 'padding',
				'variableName' => 'box-radius',
			],
			'woo_btn_text_color'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['woo_btn_text_color'] ),
					'selector' => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
						'variable' => 'color',
					),
					'hover'   => array(
						'selector' => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
						'variable' => 'colorHover',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'woo_btn_text_color',$woo_defaults['woo_btn_text_color'] ),
			),
			'woo_btn_bg_color'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['woo_btn_bg_color'] ),
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
						'variable' => 'bgColor',
					),
					'hover'   => array(
						'selector' => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
						'variable' => 'bgColorHover',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'woo_btn_bg_color',$woo_defaults['woo_btn_bg_color'] ),
			),
			'woo_btn_border_color'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['woo_btn_border_color'] ),
				),
				'variables' => array(
					'default' => array(
						'selector' => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
						'variable' => 'borderColor',
					),
					'hover'   => array(
						'selector' => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
						'variable' => 'borderColorHover',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'woo_btn_border_color',$woo_defaults['woo_btn_border_color'] ),
			),
			'woo_general_padding' => [
				'selector'   => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
				'important'  => false,
				'value'      => get_theme_mod( 'woo_general_padding', $woo_defaults['woo_general_padding'] ),
				'unit'       => 'px',
				'type'       => 'spacing',
				'responsive' => true,
				'property'   => 'padding',
				'variableName' => 'padding',
			],
			'woo_general_radius' => [
				'selector'   => '.woocommerce-page .components-button, .woocommerce-page .single_add_to_cart_button, .woocommerce-page .wc-block-components-totals-coupon-link, .woocommerce-page .woocommerce-Button, .woocommerce-page .woocommerce-button, .woocommerce-account .woocommerce-MyAccount-navigation, .wc-block-components-notice-banner__content .wc-forward, .woocommerce-address-fields .button',
				'important'  => false,
				'value'      => get_theme_mod( 'woo_general_radius', $woo_defaults['woo_general_radius'] ),
				'unit'       => 'px',
				'type'       => 'spacing',
				'responsive' => true,
				'property'   => 'padding',
				'variableName' => 'box-radius',
			],
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
			'woo_general_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'woo_general_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'woo_general_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'woo_general_section_options',
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
	protected static function get_woo_general_default_value() {

		$woo_defaults = array(
			'woocommerce_sidebar_layout'         => 'no-sidebar',
			'woocommerce_layout'                 => 'boxed',
			'woo_layout_streched_ed'             => 'no',
			'has_sale_badge'                     => 'yes',
			'shop_page_content_background_color' => 'yes',
			'sales_badge_title'                  => __('SALE!', 'rishi'),
			'shop_cards_sales_badge_design'      => 'circle',
			'woo_boxed_content_spacing'      => array(
				'linked' => true,
				'top'    => '40',
				'left'   => '40',
				'right'  => '40',
				'bottom' => '40',
				'unit' => 'px',
			),
			'woo_general_padding'      => array(
				'linked' => true,
				'top'    => '14',
				'left'   => '32',
				'right'  => '32',
				'bottom' => '14',
				'unit' => 'px',
			),
			'woo_content_boxed_radius'      => array(
				'linked' => true,
				'top'    => '3',
				'left'   => '3',
				'right'  => '3',
				'bottom' => '3',
				'unit' => 'px',
			),
			'woo_general_radius'      => array(
				'linked' => true,
				'top'    => '3',
				'left'   => '3',
				'right'  => '3',
				'bottom' => '3',
				'unit' => 'px',
			),
			'salesBagdgeColor' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],

				'background' => [
					'color' => '#E71919',
				],
			],
			'woo_content_background' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],
			],
			'woo_content_boxed_shadow'   => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value( [
				'enable'   => false,
				'h_offset' => '0px',
				'v_offset' => '12px',
				'blur'     => '18px',
				'spread'   => '-6px',
				'inset'    => false,
				'color'    => 'rgba(34, 56, 101, 0.04)',
			] ),
			'woo_btn_text_color' => [
				'default' => [
					'color' => 'var(--paletteColor5)',
				],

				'hover' => [
					'color' => 'var(--paletteColor5)',
				],
			],
			'woo_btn_bg_color' => [
				'default' => [
					'color' => 'var(--paletteColor3)',
				],

				'hover' => [
					'color' => 'var(--paletteColor4)',
				],
			],
			'woo_btn_border_color' => [
				'default' => [
					'color' => 'var(--paletteColor3)',
				],

				'hover' => [
					'color' => 'var(--paletteColor4)',
				],
			]
		);

		return $woo_defaults;
	}
}
