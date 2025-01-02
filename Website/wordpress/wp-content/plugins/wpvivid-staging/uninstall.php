<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
{
    exit;
}

$options=get_option('wpvivid_staging_options',array());
$staging_keep_setting=isset($options['staging_keep_setting']) ? $options['staging_keep_setting'] : true;
if($staging_keep_setting)
{

}
else
{
    global $wpdb;
    $wpvivid_option_table = $wpdb->base_prefix."wpvivid_options";
    $result = $wpdb->delete( $wpvivid_option_table, array( 'option_name' => 'wpvivid_staging_history_ex' ) );
    $result = $wpdb->delete( $wpvivid_option_table, array( 'option_name' => 'wpvivid_init_staging_history_ex' ) );
    $result = $wpdb->delete( $wpvivid_option_table, array( 'option_name' => 'wpvivid_staging_push_running' ) );
    $result = $wpdb->delete( $wpvivid_option_table, array( 'option_name' => 'wpvivid_staging_custom_select_website_size_ex' ) );
    $result = $wpdb->delete( $wpvivid_option_table, array( 'option_name' => 'wpvivid_staging_task_ex' ) );
    $result = $wpdb->delete( $wpvivid_option_table, array( 'option_name' => 'staging_task_cancel' ) );
    $result = $wpdb->delete( $wpvivid_option_table, array( 'option_name' => 'staging_site_data' ) );
}
