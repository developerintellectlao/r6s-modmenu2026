<?php
/**
 *
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class General_Options extends Customize_Section {

	protected $priority = 0;

	protected $id = 'general';

	public function get_type() {
		return self::GROUP_TITLE;
	}

	public static function get_order() {
		return 5;
	}

	public function get_id() {
		return $this->id;
	}

	protected function get_defaults() {
		return array();
	}

	public function get_title() {
		return '';
	}

	public function get_dynamic_styles($styles)
	{
		return array();
	}
}
