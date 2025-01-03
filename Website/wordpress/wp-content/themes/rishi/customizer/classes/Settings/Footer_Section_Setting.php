<?php
/**
 *
 */
namespace Rishi\Customizer\Settings;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Helpers\Defaults;
use Rishi\Customizer\Abstracts\Customize_Settings;

class Footer_Section_Setting extends Customize_Settings {

	public function add_settings() {
		return $this->add_setting(
			'footer_builder_key_placement',
			array(
				'control' => ControlTypes::LAYOUT_BUILDER,
				'builderType' => 'Footer',
				'value' => Defaults::footer_placements_value()->get_value(),
			)
		);
	}
}
