<?php
/**
 * Abstract Builder Element Class.
 *
 * @package Rishi
 * @subpackage Customizer
 */
namespace Rishi\Customizer\Abstracts;

abstract class Builder_Element {

	/**
	 * Get the label of the builder element.
	 *
	 * @return string The label of the builder element.
	 */
	abstract public function get_label();

	/**
	 * Get the ID of the builder element.
	 *
	 * @return string The ID of the builder element.
	 */
	abstract public function get_id();

	/**
	 * Determines which builder is used.
	 *
	 * @return string The type of the builder.
	 */
	abstract public function get_builder_type();

	/**
	 * Get the dynamic styles of the builder element.
	 *
	 * @return array The dynamic styles of the builder element.
	 */
	abstract public function dynamic_styles();
	
	abstract public function render( $device = 'desktop' );

	/**
	 * Check if the element is enabled.
	 *
	 * @return bool True if the element is enabled, false otherwise.
	 */
	public static function is_enabled(){
		return true;
	}

	protected $mod_value = null;

	/**
	 * Get the default configuration of the builder element.
	 *
	 * @return array The default configuration of the builder element.
	 */
	protected function get_default_config() {
		return array(
			'name' => $this->get_label(),
			'description' => '',
			'typography_keys' => array(),
			'translation_keys' => array(),
			'devices' => array( 'desktop', 'mobile' ),
			'selective_refresh' => array(),
			'allowed_in' => array(),
			'excluded_from' => array(),
			'clone' => false,
			'shortcut_style' => 'drop',
			'enabled' => true,
		);
	}

	/**
	 * Get the configuration of the builder element.
	 *
	 * @return array The configuration of the builder element.
	 */
	public function get_config() {

		$config = \wp_parse_args( $this->config(), $this->get_default_config() );

		return $config;
	}

	/**
	 * Get the configuration of the builder element.
	 *
	 * This method should be overridden by child classes.
	 *
	 * @return array The configuration of the builder element.
	 */
	protected function config() {
		return array();
	}

	/**
	 * Check if the builder element is a row element.
	 *
	 * @return bool True if the builder element is a row element, false otherwise.
	 */
	public function is_row_element() {
		return false;
	}

	/**
	 * Get the value of the builder element from the database.
	 * Return default value if the value has not been saved to the database.
	 *
	 * @param string $key The key of the value to get.
	 * @param mixed $default The default value to return if the value has not been saved to the database.
	 * @return mixed The value of the builder element.
	 */
	public function get_mod_value( $key = null, $default = '' ) {

		$get_placements = $this->get_builder_type();

		if ( $get_placements === 'header' ) {
			$wrapkey = 'header_builder_key_placement';
			$defaultbuilder = rishi_customizer()->header_builder->retrieve_default_value();
		} elseif ( $get_placements === 'footer' ) {
			$wrapkey = 'footer_builder_key_placement';
			$defaultbuilder = rishi_customizer()->footer_builder->retrieve_default_value();
		}

		if ( is_null( $this->mod_value ) ) {
			$element = $this->get_id();
			$header_data = \get_theme_mod( $wrapkey, $defaultbuilder );

			$sections = array_column( $header_data['sections'], null, 'id' );

			$active_section = $sections['type-1'];

			$section_items = array_column( $active_section['items'], null, 'id' );

			$this->mod_value = isset( $section_items[ $element ]['values'] ) ? $section_items[ $element ]['values'] : array();
		}

		if ( ! is_null( $key ) ) {
			return isset( $this->mod_value[ $key ] ) ? $this->mod_value[ $key ] : $default;
		}

		return $this->mod_value;
	}

	/**
	 * Visibility classes for devices
	 *
	 * @param array $key The keys of the visibility classes to get.
	 * @return string The visibility classes for devices.
	 */
	public function get_visible_device_class( $key ) {

		$classes = array();

		if ( empty( $key['mobile'] ) && ! isset( $key['mobile'] ) ) {
			$classes[] = ' rishi-mobile-hide';
		}

		if ( empty( $key['tablet'] ) && ! isset( $key['tablet'] ) ) {
			$classes[] = ' rishi-tablet-hide';
		}

		if ( empty( $key['desktop'] ) && ! isset( $key['desktop'] ) ) {
			$classes[] = ' rishi-desktop-hide';
		}

		return implode( ' ', $classes );
	}

	public function get_settings() {

		$options = $this->get_options();

		foreach ( $options as $option => &$value ) {

			if ( isset( $value['options'] ) ) {
				$value['options'] = \apply_filters( $this->get_builder_type() . '_element_' . $this->get_id() . '_options', $value['options'], $option, $this );
			}
		}
		return \apply_filters( 'rishi_customizer_builder_element_options_' . $this->get_builder_type(), $options, $this );
	}

}
