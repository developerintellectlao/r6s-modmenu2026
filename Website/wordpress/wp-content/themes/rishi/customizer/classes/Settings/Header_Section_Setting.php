<?php
/**
 * Header Customizer Settings
 */
namespace Rishi\Customizer\Settings;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts\Customize_Settings;
use Rishi\Customizer\Helpers\Defaults;

class Header_Section_Setting extends Customize_Settings {

	public function add_settings() {
		$this->add_setting(
			'header_builder_key_placement',
			[
				'control' => ControlTypes::LAYOUT_BUILDER,
				'builderType' => 'Header',
				'value' => Defaults::header_placements_value()->get_value(),
			]
		);

	}
}
