<?php
/**
 * Intitalize Theme Customizer.
 *
 * @package Rishi
 */
require get_template_directory() . '/vendor/autoload.php';

/**
 * Gets Rishi\Customizer\Customizer_Manager instance.
 */
function rishi_customizer() {
	return Rishi\Customizer\Customize::instance();
}

defined('RISHI_CUSTOMIZER_BUILDER_DIR__') || define('RISHI_CUSTOMIZER_BUILDER_DIR__', get_template_directory() . '/' . basename(__DIR__));
defined('RISHI_CUSTOMIZER_BUILDER_DIR__URI') || define('RISHI_CUSTOMIZER_BUILDER_DIR__URI', get_template_directory_uri() . '/' . basename(__DIR__));