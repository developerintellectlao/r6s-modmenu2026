<?php
/**
 * WooCommerce
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class Woo_Checkout extends Customize_Section {

	protected $id = 'woo_checkout';
	
	protected $panel = 'main_woo_settings';

	protected $container = true;

	public function get_title() {
		return __( 'Checkout', 'rishi' );
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
		return 32;
	}

	public function get_dynamic_styles( $dynamic_styles ){
	}

	protected function get_customize_settings() {
        return [];
    }

	protected function add_controls() {

		$this->wp_customize->add_section(
			'woocommerce_checkout',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'woo_checkout_section_options',
        array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'woo_checkout_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'woo_checkout_section_options',
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

}