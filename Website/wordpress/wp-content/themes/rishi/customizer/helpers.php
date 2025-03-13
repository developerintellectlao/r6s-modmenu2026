<?php
/**
 * Helper functions.
 */
use \Rishi\Customizer\Controls\Typography_Control_Option;

function rishi_typography_control_option($args) {
	$instance = new Typography_Control_Option($args);
	return $instance->get_options();
}
