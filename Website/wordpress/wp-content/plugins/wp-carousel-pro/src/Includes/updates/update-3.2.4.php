<?php
use ShapedPlugin\WPCarouselPro\Includes\License;

/**
 * Update version.
 */
update_option( 'wp_carousel_pro_version', '3.2.4' );
update_option( 'wp_carousel_pro_db_version', '3.2.4' );

$old_license             = get_option( 'sp_wp_carousel_license_key' );
$settings                = get_option( 'sp_wpcp_settings' );
$settings['license_key'] = $old_license;

update_option( 'sp_wpcp_settings', $settings );
delete_option( 'sp_wp_carousel_license_key' );
delete_option( 'sp_wp_carousel_license_key_status' );

/**
 * Update license status.
 */
$manage_license = new License( WPCAROUSEL_PRO_FILE, WPCAROUSEL_VERSION, 'ShapedPlugin', WPCAROUSEL_STORE_URL, WPCAROUSEL_ITEM_ID, WPCAROUSEL_ITEM_SLUG );
$manage_license->check_license_status();
