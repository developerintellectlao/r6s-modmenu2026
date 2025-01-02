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
// Metabox of the footer section / shortcode section.
// Set a unique slug-like ID.
//
$wpcp_display_shortcode = 'sp_wpcp_display_shortcodes';

//
// Create a metabox.
//
SP_WPCP_Framework::createMetabox(
	$wpcp_display_shortcode,
	array(
		'title'        => __( 'How To Use', 'wp-carousel-pro' ),
		'post_type'    => 'sp_wp_carousel',
		'context'      => 'side',
		'show_restore' => false,
	)
);


SP_WPCP_Framework::createSection(
	$wpcp_display_shortcode,
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => true,
				'class'     => 'sp_wpcp-admin-sidebar',
			),
		),
	)
);
SP_WPCP_Framework::createMetabox(
	'sp_wpcp_display_builders',
	array(
		'title'        => __( 'Page Builders', 'wp-carousel-pro' ),
		'post_type'    => 'sp_wp_carousel',
		'context'      => 'side',
		'show_restore' => false,
	)
);
SP_WPCP_Framework::createSection(
	'sp_wpcp_display_builders',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => false,
				'class'     => 'sp_wpcp-admin-sidebar',
			),
		),
	)
);
