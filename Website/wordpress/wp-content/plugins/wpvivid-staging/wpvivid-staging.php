<?php

/**
 * @link              https://wpvivid.com
 * @since             1.9.1
 * @package           wpvivid
 *
 * @wordpress-plugin
 * Plugin Name:       WPvivid Staging
 * Description:       WPvivid Staging plugin allows you to easily create a staging site and publish a staging site to live site.
 * Version:           2.0.19
 * Author:            wpvivid.com
 * Author URI:        https://wpvivid.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/copyleft/gpl.html
 * Text Domain:       wpvivid
 * Domain Path:       /languages
 */

define('WPVIVID_STAGING_PLUGIN_DIR',plugin_dir_path(__FILE__));
define('WPVIVID_STAGING_PLUGIN_URL',plugin_dir_url(__FILE__));
define('WPVIVID_STAGING_VERSION','2.0.19');
define('WPVIVID_STAGING_SLUG','WPvivid_Staging');
define('WPVIVID_STAGING_DIR','wpvivid_staging');

define('WPVIVID_STAGING_DB_INSERT_COUNT_EX', 10000);
define('WPVIVID_STAGING_DB_REPLACE_COUNT_EX', 5000);
define('WPVIVID_STAGING_FILE_COPY_COUNT_EX', 500);
define('WPVIVID_STAGING_MAX_FILE_SIZE_EX', 30);
define('WPVIVID_STAGING_MEMORY_LIMIT_EX', '256M');
define('WPVIVID_STAGING_MAX_EXECUTION_TIME_EX', 900);
define('WPVIVID_STAGING_RESUME_COUNT_EX', 6);
define('WPVIVID_STAGING_PATH', 'wpvivid_staging');
define('WPVIVID_STAGING_DELAY_BETWEEN_REQUESTS', 1500);
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) )
{
    die;
}

require WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging.php';

function run_wpvivid_staging()
{
    $wpvivid_staging=new WPvivid_Staging();
    $GLOBALS['wpvivid_staging'] = $wpvivid_staging;
}
run_wpvivid_staging();