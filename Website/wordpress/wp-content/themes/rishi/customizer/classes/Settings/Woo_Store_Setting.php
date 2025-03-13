<?php
/**
 * WooCommerce Store Customizer Settings
 */
namespace Rishi\Customizer\Settings;

use Rishi\Customizer\Abstracts\Customize_Settings;
use Rishi\Customizer\ControlTypes;

class Woo_Store_Setting extends Customize_Settings {

	public function add_settings() {
		$this->add_woo_store_settings();
	}

	protected function add_woo_store_settings(){
		$woo_defaults = self::get_woo_store_default_value();

		$this->add_setting('woo_store_general_tab', array(
			'title'   => __( 'General', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'store_notice_position' => array(
					'label'   => __( 'Notice Position', 'rishi' ),
					'control'    => ControlTypes::INPUT_RADIO,
					'value'   => $woo_defaults['store_notice_position'],
					'divider'    => 'bottom',
					'design'  => 'block',
					'choices' => array(
						'top' => __('Top', 'rishi'),
						'bottom' => __('Bottom', 'rishi'),
					),
				),
			),
		));

		$this->add_setting('woo_store_design_tab', array(
			'title'   => __( 'Design', 'rishi' ),
			'control' => ControlTypes::TAB,
			'options' => array(
				'wooNoticeContent' => [
					'label'           => __('Notice Font Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'colorPalette'	  => true,
					'design'          => 'inline',
					'divider'         => 'bottom',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['wooNoticeContent'],
					'pickers' => [
						[
							'title' => __( 'Initial', 'rishi' ),
							'id' => 'default',
						],
					],
				],
				'wooNoticeBackground' => [
					'label'           => __('Notice Background Color', 'rishi'),
					'control'         => ControlTypes::COLOR_PICKER,
					'colorPalette'	  => true,
					'design'          => 'inline',
					'divider'         => 'bottom',
					'responsive'      => false,
					'skipEditPalette' => true,
					'value'           => $woo_defaults['wooNoticeBackground'],
					'pickers' => [
						[
							'title' => __( 'Initial', 'rishi' ),
							'id' => 'default',
						],
					],
				],

				'wooNoticeTypo'    => rishi_typography_control_option([
					'control'     => ControlTypes::TYPOGRAPHY,
					'label'       => __( 'Notice Typography', 'rishi' ),
					'divider'     => 'bottom',
					'value'       => $woo_defaults['wooNoticeTypo'],
				]),
			),
		));
	}

	/**
	 * Set default value for Woo page.
	 */
	protected static function get_woo_store_default_value() {

		$woo_defaults = array(
			'store_notice_position'         => 'bottom',
			'wooNoticeContent'              => [
                'default' => [
                    'color' => 'var(--paletteColor5)',
                ],
            ],
            'wooNoticeBackground' => [
                'default' => [
                    'color' => 'var(--paletteColor3)',
                ],
            ],
            'wooNoticeTypo' => \Rishi\Customizer\Helpers\Defaults::typography_value(
				array(
					'size'            => array(
						'desktop' => '18px',
						'tablet'  => '18px',
						'mobile'  => '18px',
					),
					'line-height'            => array(
						'desktop' => '1.2em',
						'tablet'  => '1.2em',
						'mobile'  => '1.2em',
					),
					'weight'      => '400',
				)
			)
		);

		return $woo_defaults;
	}
}
