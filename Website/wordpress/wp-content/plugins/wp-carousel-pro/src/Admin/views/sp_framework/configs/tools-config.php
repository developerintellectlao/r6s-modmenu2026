<?php
/**
 * Create tools options.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes
 */

use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_wpcp_tools';

//
// Create options.
//
SP_WPCP_Framework::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Tools', 'wp-carousel-pro' ),
		'menu_slug'        => 'wpcp_tools',
		'menu_parent'      => 'edit.php?post_type=sp_wp_carousel',
		'menu_type'        => 'submenu',
		'ajax_save'        => false,
		'show_bar_menu'    => false,
		'save_defaults'    => false,
		'show_reset_all'   => false,
		'show_all_options' => false,
		'show_search'      => false,
		'show_footer'      => false,
		'show_buttons'     => false, // Custom show button option added for hide save button in tools page.
		'theme'            => 'light',
		'framework_title'  => __( 'Tools', 'wp-carousel-pro' ),
		'framework_class'  => 'sp-wpcp-options wpcp_tools',
	)
);
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'title'  => __( 'Export', 'wp-carousel-pro' ),
		'fields' => array(
			array(
				'id'       => 'wpcp_what_exports',
				'type'     => 'radio',
				'class'    => 'wpcp_what_export',
				'title'    => __( 'Choose What To Export', 'wp-carousel-pro' ),
				'multiple' => false,
				'options'  => array(
					'all_shortcodes'      => __( 'All Carousels (Shortcodes)', 'wp-carousel-pro' ),
					'selected_shortcodes' => __( 'Selected Carousel (Shortcode)', 'wp-carousel-pro' ),
				),
				'default'  => 'all_shortcodes',
			),
			array(
				'id'          => 'wpcp_post',
				'class'       => 'wpcp_post_ids',
				'type'        => 'select',
				'title'       => ' ',
				'options'     => 'sp_wp_carousel',
				'chosen'      => true,
				'sortable'    => false,
				'multiple'    => true,
				'placeholder' => __( 'Choose shortcode(s)', 'wp-carousel-pro' ),
				'query_args'  => array(
					'posts_per_page' => -1,
				),
				'dependency'  => array( 'wpcp_what_exports', '==', 'selected_shortcodes', true ),

			),
			array(
				'id'      => 'export',
				'class'   => 'wpcp_export',
				'type'    => 'button_set',
				'title'   => ' ',
				'options' => array(
					'' => __( 'Export', 'wp-carousel-pro' ),
				),
			),
		),
	)
);
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'title'  => __( 'Import', 'wp-carousel-pro' ),
		'fields' => array(
			array(
				'id'         => 'import_unSanitize',
				'type'       => 'checkbox',
				'title'      => __( 'Allow Iframe/Script Tags', 'wp-carousel-pro' ),
				'title_help' => __( 'Enabling this option, you are allowing to import the carousel which contains iframe, script or embed tags', 'wp-carousel-pro' ),
				'default'    => false,
			),
			array(
				'class' => 'wpcp_import',
				'type'  => 'custom_import',
				'title' => __( 'Import JSON File To Upload', 'wp-carousel-pro' ),
			),
		),
	)
);
