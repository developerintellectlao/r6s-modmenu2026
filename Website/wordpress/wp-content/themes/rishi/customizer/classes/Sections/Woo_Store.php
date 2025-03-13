<?php
/**
 * WooCommerce
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
class Woo_Store extends Customize_Section {

	protected $id = 'woocommerce_store';

	protected $panel = 'main_woo_settings';

	protected $container = true;

	public function get_title() {
		return __( 'Store Notice', 'rishi' );
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
		return 31;
	}

	public function get_dynamic_styles( $dynamic_styles ){

		$woo_defaults = self::get_woo_store_default_value();
		$options = array(
            'wooNoticeContent'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['wooNoticeContent'] ),
				),
                'variables' => array(
					'default' => array(
						'selector' => '.woo-notice',
						'variable' => 'color',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'wooNoticeContent',$woo_defaults['wooNoticeContent'] ),
			),
            'wooNoticeBackground'     => array(
				'default'   => array(
					'default'  => array( 'color' => $woo_defaults['wooNoticeBackground'] ),
				),
                'variables' => array(
					'default' => array(
						'selector' => '.woo-notice',
						'variable' => 'bgColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'wooNoticeBackground',$woo_defaults['wooNoticeBackground'] ),
			),
			'wooNoticeTypo' => array(
				'value'      => get_theme_mod( 'wooNoticeTypo',$woo_defaults['wooNoticeTypo'] ),
				'selector'   => '.woo-notice',
				'type'       => 'typography'
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
			'woo_store_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'woo_store_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'woo_store_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'woo_store_section_options',
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
						'desktop' => '1.2',
						'tablet'  => '1.2',
						'mobile'  => '1.2',
					),
					'weight'      => '400',
				)
			)
		);

		return $woo_defaults;
	}
}
