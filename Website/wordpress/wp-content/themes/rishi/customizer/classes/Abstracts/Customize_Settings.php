<?php
/**
 * Class Customize_Settings
 *
 * This class is responsible for adding settings to the WordPress Customizer.
 * It is an abstract class and should be extended by other classes to implement the add_settings method.
 *
 * @package Rishi
 * @subpackage Customizer
 */

namespace Rishi\Customizer\Abstracts;

use Rishi\Customizer\ControlTypes;

abstract class Customize_Settings extends ControlTypes {

	protected $settings = array();

	public function __construct() {
		$this->add_settings();
	}

	abstract protected function add_settings();

	/**
	 * Add settings to the WordPress Customizer.
	 *
	 * This is an abstract method and should be implemented by classes extending Customize_Settings.
	 *
	 * @return void
	 */
	protected function add_setting( $name, $args ) {
		if ( isset( $args['optParent'] ) ) {
			$this->settings[ $args['parent'] ]['innerControls'][ $args['optParent'] ]['options'][ $name ] = $args;
			return;
		}

		if ( isset( $args['parent'] ) ) {
			$this->settings[ $args['parent'] ]['innerControls'][ $name ] = $args;
			return;
		}
		$this->settings[ $name ] = $args;
	}

	/**
	 * Retrieve settings.
	 */
	public function get_settings() {

		$prefix = substr( strrchr( get_class( $this ), "\\" ), 1 );

		return \apply_filters( strtolower( $prefix . '_' . __FUNCTION__ ), $this->settings, $this );
	}

}
