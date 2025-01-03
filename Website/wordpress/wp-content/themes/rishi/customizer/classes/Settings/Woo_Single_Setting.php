<?php
/**
 * WooCommerce Single Product Customizer Settings
 */
namespace Rishi\Customizer\Settings;
use Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts\Customize_Settings;

class Woo_Single_Setting extends Customize_Settings {

	public function add_settings() {
		$this->add_woo_single_settings();
	}

	protected function add_woo_single_settings(){
		$woo_defaults = self::get_woo_single_default_value();

		$options = array(
			'breadcrumbs_ed_single_product' => array(
				'label' => __( 'Breadcrumb', 'rishi' ),
				'control'  => ControlTypes::INPUT_SWITCH,
				'value' => $woo_defaults['breadcrumbs_ed_single_product'],
				'divider'    => 'bottom',

			),
			'single_product_gallery_options_panel' => array(
				'label'         => __( 'Gallery Options', 'rishi' ),
				'control'       => ControlTypes::PANEL,
				'divider'    => 'bottom',
				'innerControls' => array(
					'gallery_img_width'                     => [
						'label'      => __( 'Product Width', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => $woo_defaults['gallery_img_width'],
						'divider'    => 'bottom',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => '%', 'min' => 0, 'max' => 90 ],
								] ),
						'responsive' => false,
					],
					'gallery_thumbnail_spacing'                     => [
						'label'      => __( 'Thumbnails Spacing', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => $woo_defaults['gallery_thumbnail_spacing'],
						'divider'    => 'bottom',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
									[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
								] ),
						'responsive' => false,
					],
					'gallery_thumbnail_position' => [
						'label'   => __( 'Gallery Position', 'rishi' ),
						'control' => ControlTypes::INPUT_RADIO,
						'value'   => $woo_defaults['gallery_thumbnail_position'],
						'view'    => 'text',
						'divider' => 'bottom',
						'design'  => 'block',
						'choices' => array(
							'horizontal' => __('Horizontal', 'rishi'),
							'vertical'   => __('Vertical', 'rishi'),
						),
					],
					'gallery_image_options' => [
						'label'   => __( 'Image Ratio', 'rishi' ),
						'control' => ControlTypes::INPUT_SELECT,
						'design'  => 'inline',
						'divider' => 'bottom',
						'value'   => 'auto',
						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
							array(
								'auto' => __('Original', 'rishi'),
								'1/1'  => __( 'Square - 1:1', 'rishi'),
								'4/3'  => __('Standard - 4:3', 'rishi'),
								'3/4'  => __('Portrait - 3:4', 'rishi'),
								'3/2'  => __('Classic - 3:2', 'rishi'),
								'2/3'  => __( 'Classic Portrait - 2:3', 'rishi'),
								'16/9' => __('Wide - 16:9', 'rishi'),
								'9/16' => __('Tall - 9:16', 'rishi'),
							)
						),
					],
					'gallery_image_scale' => array(
						'label'   => __( 'Image Scale', 'rishi' ),
						'control' => ControlTypes::INPUT_SELECT,
						'design'  => 'inline',
						'divider' => 'bottom',
						'value'   => 'contain',
						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
							array(
								'contain' => __( 'Contain', 'rishi' ),
								'cover'   => __( 'Cover', 'rishi' ),
								'fill'    => __( 'Fill', 'rishi' ),
							)
						),
					),
					'gallery_ed_lightbox' => array(
						'label'   => __( 'Enable Lightbox', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $woo_defaults['gallery_ed_lightbox'],
						'divider' => 'bottom',

					),
					'gallery_ed_zoom_effect' => array(
						'label'   => __( 'Enable Zoom', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => $woo_defaults['gallery_ed_zoom_effect'],
					),
				),
			),
			'has_product_single_rating' => array(
				'label'   => __( 'Star Rating', 'rishi' ),
				'control' => ControlTypes::INPUT_SWITCH,
				'value'   => $woo_defaults['has_product_single_rating'],
				'divider' => 'bottom',

			),
			'has_product_single_meta' => array(
				'label'   => __( 'Product Meta', 'rishi' ),
				'control' => ControlTypes::INPUT_SWITCH,
				'value'   => $woo_defaults['has_product_single_meta'],
				'divider' => 'bottom',

			),
			'single_product_upsell_options_panel' => array(
				'label'   => __( 'Upsell Products Options', 'rishi' ),
				'control'       => ControlTypes::PANEL,
				'divider'    => 'bottom',
				'innerControls' => array(
					'woo_ed_upsell_tab' => array(
						'label' => __( 'Move Upsell Products', 'rishi' ),
						'help'    => __('This setting will move upsell products to default tab section', 'rishi'),
						'control'  => ControlTypes::INPUT_SWITCH,
						'value' => $woo_defaults['woo_ed_upsell_tab'],

					),
					'woo_upsell_tab_label'     => array(
						'label'   => __('Upsell Products Label', 'rishi'),
						'help'    => __( 'This label will on tab section for upsell product', 'rishi' ),
						'control'   => ControlTypes::INPUT_TEXT,
						'design' => 'block',
						'value'  => $woo_defaults['woo_upsell_tab_label'],
						'conditions' => [
							'woo_ed_upsell_tab' => 'yes'
						]
					),
					'woo_single_no_of_upsell' => array(
						'label'      => __( 'Upsell Products', 'rishi' ),
						'control'    => ControlTypes::INPUT_NUMBER,
						'design'     => 'inline',
						'value'      => $woo_defaults['woo_single_no_of_upsell'],
						'min'        => 1,
						'max'        => 100,
						'divider'    => 'top:bottom',
						'responsive' => false,
					),
					'woo_single_no_of_upsell_row' => array(
						'label'      => __( 'Upsell Products per Row', 'rishi' ),
						'control'    => ControlTypes::INPUT_NUMBER,
						'design'     => 'inline',
						'value'      => $woo_defaults['woo_single_no_of_upsell_row'],
						'min'        => 1,
						'max'        => 5,
						'divider'    => 'bottom',
						'responsive' => false,
					),
				),
			),
			'single_product_related_options_panel' => array(
				'label'         => __( 'Related Products Options', 'rishi' ),
				'control'       => ControlTypes::PANEL,
				'divider'    => 'bottom',
				'innerControls' => apply_filters( 'rishi_woo_single_related_options',
					array(
						'single_related_products'     => array(
							'label'   => __('Related Products Title', 'rishi'),
							'control'   => ControlTypes::INPUT_TEXT,
							'design' => 'block',
							'value'  => $woo_defaults['single_related_products'],
							'divider'    => 'bottom',
						),
						'woo_single_no_of_posts' => array(
							'label'      => __( 'Related Products', 'rishi' ),
							'control'    => ControlTypes::INPUT_NUMBER,
							'design'     => 'inline',
							'value'      => $woo_defaults['woo_single_no_of_posts'],
							'min'        => 1,
							'max'        => 24,
							'divider'    => 'bottom',
							'responsive' => false,
						),
						'woo_single_no_of_posts_row' => array(
							'label'      => __( 'Related Products per Row', 'rishi' ),
							'control'    => ControlTypes::INPUT_NUMBER,
							'design'     => 'inline',
							'value'      => $woo_defaults['woo_single_no_of_posts_row'],
							'min'        => 1,
							'max'        => 5,
							'divider'    => 'bottom',
							'responsive' => false,
						),
					)
				)
			),
		);

		$this->add_setting( 'woo_single_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => apply_filters('rishi_woo_single_general_tab',
				$options
			),
		));

		$this->add_setting( 'woo_single_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'singleProductTitleColor' => [
					'label'           => __('Product Title Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'colorPalette'	  => true,
					'design'          => 'inline',
					'divider'         => 'bottom',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['singleProductTitleColor'],
					'pickers' => [
						[
							'title' => __( 'Initial', 'rishi' ),
							'id' => 'default',
						],
					],
				],
				'singleProductPriceColor' => [
					'label'           => __('Product Price Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'design'          => 'inline',
					'colorPalette'	  => true,
					'divider'         => 'bottom',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['singleProductPriceColor'],
					'pickers' => [
						[
							'title' => __( 'Initial', 'rishi' ),
							'id' => 'default',
						],
					],
				],
			),
		));
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
