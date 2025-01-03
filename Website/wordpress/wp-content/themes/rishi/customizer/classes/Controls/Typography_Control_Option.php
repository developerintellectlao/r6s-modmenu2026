<?php
/**
 * Typography Controls Class File.
 */
namespace Rishi\Customizer\Controls;

use Rishi\Customizer\ControlTypes;

class Typography_Control_Option {

	protected $label = '';

	protected $control = ControlTypes::TYPOGRAPHY;

	protected $divider = '';

	protected $value = '';

	protected $settings = array();

	protected $font_family = 'Default';
	protected $font_size = array(
		'desktop' => '18px',
		'tablet'  => '18px',
		'mobile'  => '18px',
	);

	protected $font_weight = '400';


	public function __construct($args) {

		$this->label = $args['label'];

		$this->divider = isset( $args['divider'] ) ? $args['divider'] : '';

		$this->value = $args['value'];

	}

	public function get_options() {
		return array(
			'label' => $this->label,
			'control' => $this->control,
			'divider' => $this->divider,
			'value' => $this->value,
			'settings' => [
				'options' => $this->get_settings(),
			]
		);
	}
	protected function get_settings() {
		$font = isset($this->value['font_family']) ? $this->value['font_family'] : $this->font_family;
		$size = isset($this->value['size']) ? $this->value['size'] : $this->font_size;
		$weight = isset($this->value['weight']) ? $this->value['weight'] : $this->font_weight;
		$style = isset($this->value['font_style']) ? $this->value['font_style'] : '';
		$line_height = isset($this->value['line-height']) ? $this->value['line-height'] : '';
		$letter_spacing = isset($this->value['letter-spacing']) ? $this->value['letter-spacing'] : '';
		$text_transform = isset($this->value['text-transform']) ? $this->value['text-transform'] : '';
		$text_decoration = isset($this->value['text-decoration']) ? $this->value['text-decoration'] : '';

		return \Rishi\Customizer\Settings\Typography_Setting::set_typography_options(
			$font,
			$size,
			$weight,
			$style,
			$line_height,
			$letter_spacing,
			$text_transform,
			$text_decoration
		);
	}
}
