<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://rishitheme.com/rishi-companion
 * @since             1.0.0
 * @package           Rishi
 *
 * @wordpress-plugin
 * Plugin Name:       Rishi Companion
 * Plugin URI:        https://rishitheme.com/rishi-companion/
 * Description:       Rishi Companion is a plugin that offers powerful features for Rishi theme. It includes features to speed your website and tune fine your website.
 * Version:           2.0.2
 * Author:            Rishi Theme
 * Author URI:        https://rishitheme.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       rishi-companion
 * Domain Path:       /languages
 * Requires at least: 6.3
 * Requires PHP: 7.4
 * Tested up to: 6.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'RISHI_COMPANION_PLUGIN_FILE' ) ) {
	define( 'RISHI_COMPANION_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'RISHI_COMPANION_PLUGIN_DIR' ) ) {
	define( 'RISHI_COMPANION_PLUGIN_DIR', __DIR__ );
}
// Include the autoloader.
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Return the main instance of Rishi_Companion.
 *
 * @since 1.0.0
 * @return Rishi_Companion
 */
function RISHI_CMPN() {
	return \Rishi_Companion\Plugin::instance();
}

$GLOBALS['RSH_COMPANION'] = RISHI_CMPN();
