<?php
/**
 * Heading for WooCommerce Panels
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class WooCommerce_Title extends Customize_Section {

	protected $priority = 3;

	protected $id = 'woocommerce-panel';

	public function get_type() {
		return self::GROUP_TITLE;
	}

	public static function get_order() {
		return 27;
	}

	public function get_id() {
		return $this->id;
	}

	public static function is_enabled() {
		return rishi_is_woocommerce_activated();
	}

	protected function get_defaults() {
		return array();
	}

	public function get_title() {
		return '';
	}

	public function get_dynamic_styles($styles) {
		return array();
	}
}
