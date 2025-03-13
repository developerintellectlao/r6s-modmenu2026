<?php
/**
 * Metabox config file.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
//
// Metabox of the uppers section / Upload section.
// Set a unique slug-like ID.
//
$wpcp_carousel_content_source_settings = 'sp_wpcp_upload_options';
$single_page_link                      = admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=watermark-settings' );
$instagram_api_link                    = admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=instagram-settings' );

require 'generator/uploads-options.php';

//
// Metabox for the Carousel Post Type.
// Set a unique slug-like ID.
//
$wpcp_carousel_shortcode_settings = 'sp_wpcp_shortcode_options';
require 'generator/general-section.php';
require 'generator/style-section.php';
require 'generator/lightbox-section.php';
require 'generator/carousel-section.php';
require 'generator/shortcode-display.php';
