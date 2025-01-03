<?php
/**
 * Colors_Section Class File.
 *
 * This file contains the Colors_Section class which extends the Customizer_Control class.
 * The Colors_Section class is used to create a custom control for managing color options in the WordPress Customizer.
 *
 * @package Rishi\Customizer\Controls
 */
namespace Rishi\Customizer\Controls;

use Rishi\Customizer\Customizer_Control;

/**
 * Colors_Section Class.
 *
 * This class extends the Customizer_Control class and is used to create a custom control for managing color options in the WordPress Customizer.
 */
class Colors_Section extends Customizer_Control {
	/**
	 * Get Control ID.
	 *
	 * This method returns the ID of the control. This ID is used to identify the control in the WordPress Customizer.
	 *
	 * @return string The ID of the control.
	 */
	public function get_control_id() {
		return 'layouts_color_options';
	}

	/**
	 * Get Label.
	 *
	 * This method returns the label of the control. This label is displayed in the WordPress Customizer.
	 *
	 * @return string The label of the control.
	 */
	public function get_label() {
		return __( 'Colors', 'rishi' );
	}
}
