<?php
/**
 * WooCommerce
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
class Woo_Single extends Customize_Section {

	protected $id = 'woocommerce_single';

	protected $panel = 'main_woo_settings';

	protected $container = true;

	public function get_title() {
		return __( 'Single Product', 'rishi' );
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
		return 30;
	}
	public function get_dynamic_styles( $dynamic_styles ){
        $woo_defaults = self::get_woo_single_default_value();
		$image_ratio = get_theme_mod( 'gallery_image_options', 'auto' );
		$image_scale = get_theme_mod( 'gallery_image_scale', 'contain' );
		
		$options = array(
			'gallery_img_width'            => array(
				'selector'     => '.product-entry-wrapper',
				'variableName' => 'product-gallery-width',
				'unit'         => '',
				'value'        => get_theme_mod( 'gallery_img_width', $woo_defaults['gallery_img_width'] ),
				'responsive'   => false,
				'type'         => 'slider',
			),
            'gallery_thumbnail_spacing'            => array(
				'selector'     => '.product-entry-wrapper',
				'variableName' => 'thumbs-width',
				'value'        => get_theme_mod( 'gallery_thumbnail_spacing', $woo_defaults['gallery_thumbnail_spacing'] ),
				'responsive'   => false,
				'type'         => 'slider',
			),
            'singleProductTitleColor'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['singleProductTitleColor'] ),
					'selector' => '.single-product.woocommerce .summary .product_title',
				),
                'variables' => array(
					'default' => array(
						'selector' => '.single-product.woocommerce .summary .product_title',
						'variable' => 'headingColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'singleProductTitleColor',$woo_defaults['singleProductTitleColor'] ),
			),
            'singleProductPriceColor'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['singleProductPriceColor'] ),
					'selector' => '.single-product.woocommerce .summary .price',
				),
                'variables' => array(
					'default' => array(
						'selector' => '.single-product.woocommerce .summary .price',
						'variable' => 'productColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'singleProductPriceColor',$woo_defaults['singleProductPriceColor'] ),
			),
			'gallery_image_options'  => array(
				'selector'     => '.single-product.woocommerce .product-entry-wrapper',
				'variableName' => 'img-ratio',
				'value'        => $image_ratio,
				'type'         => 'alignment',
			),
			'gallery_image_scale'  => array(
				'selector'     => '.single-product.woocommerce .product-entry-wrapper',
				'variableName' => 'img-scale',
				'value'        => $image_scale,
				'type'         => 'alignment',
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
			'woo_single_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'woo_single_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'woo_single_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'woo_single_section_options',
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
	protected static function get_woo_single_default_value() {

		$woo_defaults = array(
			'breadcrumbs_ed_single_product' => 'yes',
			'gallery_img_width'             => '50%',
			'gallery_thumbnail_spacing'     => '10px',
			'gallery_thumbnail_position'    => 'horizontal',
			'gallery_ed_lightbox'           => 'no',
			'gallery_ed_zoom_effect'        => 'no',
			'has_product_single_rating'     => 'yes',
			'has_product_single_meta'       => 'yes',
			'woo_ed_upsell_tab'             => 'no',
			'woo_upsell_tab_label'          => __('Upsell Products', 'rishi'),
			'woo_single_no_of_upsell'       => 24,
			'woo_single_no_of_upsell_row'   => 4,
			'single_related_products'       => __( 'Related Products', 'rishi' ),
			'woo_single_no_of_posts'        => 3,
			'woo_single_no_of_posts_row'    => 4,
			'singleProductTitleColor'    => [
                'default' => [
                    'color' => 'var(--paletteColor1)',
                ],
            ],
            'singleProductPriceColor'    => [
                'default' => [
                    'color' => 'var(--paletteColor1)',
                ],
            ]
		);

		return $woo_defaults;
	}
}
