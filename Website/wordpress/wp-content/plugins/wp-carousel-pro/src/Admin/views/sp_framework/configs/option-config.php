<?php
/**
 * Create options config
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


// Set a unique slug-like ID.
$prefix = 'sp_wpcp_settings';

//
// Create options.
//
SP_WPCP_Framework::createOptions(
	$prefix,
	array(
		'menu_title'         => __( 'Settings', 'wp-carousel-pro' ),
		'menu_slug'          => 'wpcp_settings',
		'menu_parent'        => 'edit.php?post_type=sp_wp_carousel',
		'menu_type'          => 'submenu',
		'ajax_save'          => true,
		'save_defaults'      => true,
		'show_reset_all'     => false,
		'framework_title'    => __( 'Settings', 'wp-carousel-pro' ),
		'framework_class'    => 'sp-wpcp-options',
		'theme'              => 'light',
		'show_bar_menu'      => false,
		'show_footer'        => false,
		'show_sub_menu'      => false,
		'show_network_menu'  => false,
		'show_in_customizer' => false,
		'show_search'        => false,
		'show_reset_section' => true,
		'show_all_options'   => false,
	)
);

// License key section.
//
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'title'  => __( 'License Key', 'wp-carousel-pro' ),
		'icon'   => 'fa fa-key',
		'fields' => array(
			array(
				'id'   => 'license_key',
				'type' => 'license',
			),
		),
	)
);

//
// Create a section.
//
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'title'  => 'Advanced Controls',
		'icon'   => 'fa fa-wrench',
		'fields' => array(
			array(
				'id'         => 'wpcp_delete_all_data',
				'type'       => 'checkbox',
				'title'      => __( 'Clean-up Data on Deletion', 'wp-carousel-pro' ),
				'title_help' => __( 'Check to remove plugin\'s data when plugin is uninstalled or deleted.', 'wp-carousel-pro' ),
				'default'    => false,
			),
			array(
				'id'         => 'wpcp_dequeue_google_font',
				'type'       => 'switcher',
				'title'      => __( 'Google Fonts', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => false,
			),
			array(
				'id'         => 'wpcp_use_cache',
				'type'       => 'switcher',
				'title'      => __( 'Cache', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enabled', 'wp-carousel-pro' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-pro' ),
				'title_help' => __( 'Normally cache gets cleared after 24 hours.', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'      => 'wpcp_cache_remove',
				'class'   => 'wpcp_cache_remove',
				'type'    => 'button_clean',
				'options' => array(
					'' => 'Purge Cache',
				),
				'title'   => __( 'Clean Cache', 'wp-carousel-pro' ),
				'default' => false,
			),
			array(
				'id'         => 'wpcp_carousel_in_ajax_theme',
				'type'       => 'switcher',
				'title'      => __( 'Load Carousel in Ajax Theme', 'wp-carousel-pro' ),
				'title_help' => __( 'Enable this option to make compatible the carousel with Ajax-based theme.', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => false,
			),
		),
	)
);

//  Assets controls.
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'title'  => 'Assets Controls',
		'icon'   => 'wpcp-icon-tab_asset-control',
		'fields' => array(
			array(
				'id'         => 'wpcp_enqueue_swiper_css',
				'type'       => 'switcher',
				'title'      => __( 'Swiper CSS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_enqueue_bx_css',
				'type'       => 'switcher',
				'title'      => __( 'bxSlider CSS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_enqueue_fa_css',
				'type'       => 'switcher',
				'title'      => __( 'Font Awesome CSS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_enqueue_fancybox_css',
				'type'       => 'switcher',
				'title'      => __( 'FancyBox Popup CSS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_enqueue_animation_css',
				'type'       => 'switcher',
				'title'      => __( 'Animate CSS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_swiper_js',
				'type'       => 'switcher',
				'title'      => __( 'Swiper JS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_bx_js',
				'type'       => 'switcher',
				'title'      => __( 'bxSlider JS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
			array(
				'id'         => 'wpcp_fancybox_js',
				'type'       => 'switcher',
				'title'      => __( 'FancyBox Popup JS', 'wp-carousel-pro' ),
				'text_on'    => __( 'Enqueued', 'wp-carousel-pro' ),
				'text_off'   => __( 'Dequeued', 'wp-carousel-pro' ),
				'text_width' => 100,
				'default'    => true,
			),
		),
	)
);
// License key section.
//
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'title'  => __( 'Watermark Settings', 'wp-carousel-pro' ),
		'icon'   => 'fa fa-copyright',
		'fields' => array(
			array(
				'id'      => 'wm_watermark_type',
				'title'   => __( 'Watermark Type', 'wp-carousel-pro' ),
				'type'    => 'button_set',
				'options' => array(
					'logo' => __( 'Logo', 'wp-carousel-pro' ),
					'text' => __( 'Text', 'wp-carousel-pro' ),
				),
				'default' => 'logo',
			),
			array(
				'id'         => 'wm_watermark_label',
				'type'       => 'text',
				'title'      => __( 'Watermark Label', 'wp-carousel-pro' ),
				'title_help' => __( 'Text Watermark depends on PHP imagick module. It will work perfectly if server has PHP imagick module.', 'wp-carousel-pro' ),
				'default'    => 'Watermark Label',
				'dependency' => array( 'wm_watermark_type', '==', 'text' ),
			),
			array(
				'id'         => 'wm_watermark_text_size',
				'type'       => 'spinner',
				'title'      => __( 'Text Font Size', 'wp-carousel-pro' ),
				'default'    => '24',
				'unit'       => 'px',
				'dependency' => array( 'wm_watermark_type', '==', 'text' ),
			),

			array(
				'id'         => 'wm_text_color',
				'type'       => 'color',
				'title'      => __( 'Text Color', 'wp-carousel-pro' ),
				'default'    => '#ffffff',
				'dependency' => array( 'wm_watermark_type', '==', 'text' ),
			),

			array(
				'id'         => 'wm_image',
				'title'      => __( 'Watermark Image', 'wp-carousel-pro' ),
				'type'       => 'media',
				'library'    => array( 'image' ),
				'url'        => false,
				'preview'    => true,
				'dependency' => array( 'wm_watermark_type', '==', 'logo' ),
			),
			array(
				'id'      => 'wm_position',
				'title'   => __( 'Position', 'wp-carousel-pro' ),
				'type'    => 'select',
				'options' => array(
					'lt' => __( 'Left Top', 'wp-carousel-pro' ),
					'lm' => __( 'Left Center', 'wp-carousel-pro' ),
					'lb' => __( 'Left Bottom', 'wp-carousel-pro' ),
					'rt' => __( 'Right top', 'wp-carousel-pro' ),
					'rm' => __( 'Right Center', 'wp-carousel-pro' ),
					'rb' => __( 'Right Bottom', 'wp-carousel-pro' ),
					'mb' => __( 'Center Bottom', 'wp-carousel-pro' ),
					'mm' => __( 'Center Center', 'wp-carousel-pro' ),
					'mt' => __( 'Center Top', 'wp-carousel-pro' ),
				),
				'default' => 'rb',
			),
			array(
				'id'         => 'wm_margin',
				'type'       => 'spacing',
				'class'      => 'standard_width_of_spacing_field',
				'title'      => __( 'Margin', 'wp-carousel-pro' ),
				'all'        => true,
				'default'    => array(
					'all'  => '10',
					'unit' => '%',
				),
				'units'      => array(
					'px',
					'%',
				),
				'dependency' => array( 'wm_watermark_type', '==', 'logo' ),
			),
			array(
				'id'      => 'wm_opacity',
				'type'    => 'spinner',
				'title'   => __( 'Opacity', 'wp-carousel-pro' ),
				'default' => '0.5',
				'min'     => 0,
				'max'     => 1,
				'step'    => 0.1,
			),
			array(
				'id'         => 'wm_custom',
				'class'      => 'wm_custom',
				'type'       => 'switcher',
				'title'      => __( 'Custom Size', 'wp-carousel-pro' ),
				'title_help' => __( 'Set watermark custom size related to image (horizontally/vertically)', 'wp-carousel-pro' ),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'wp-carousel-pro' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-pro' ),
				'text_width' => 100,
				'dependency' => array( 'wm_watermark_type', '==', 'logo' ),
			),
			array(
				'id'          => 'wm_size',
				'type'        => 'spacing',
				'class'       => 'standard_width_of_spacing_field',
				'title'       => ' ',
				'top_icon'    => '',
				'bottom_icon' => '',
				'top_text'    => 'Width',
				'bottom_text' => 'Height',
				'left'        => false,
				'right'       => false,
				'default'     => array(
					'top'    => 10,
					'bottom' => 10,
				),
				'units'       => array(
					'%',
				),
				'dependency'  => array( 'wm_custom|wm_watermark_type', '==|==', 'true|logo' ),
			),
			array(
				'id'         => 'wm_quality',
				'type'       => 'spinner',
				'title'      => __( 'Image Quality', 'wp-carousel-pro' ),
				'default'    => '100',
				'min'        => 5,
				'max'        => 100,
				'unit'       => '%',
				'step'       => 1,
				'dependency' => array( 'wm_watermark_type', '==', 'logo' ),
			),
			array(
				'id'         => 'wm_clean',
				'class'      => 'wm_clean_cache',
				'type'       => 'button_clean',
				'title'      => __( 'Purge Watermark Cache', 'wp-carousel-pro' ),
				'title_help' => __( 'Purging or clearing cached watermarked images will ensure to display the recent changes of watermark configurations.', 'wp-carousel-pro' ),
				'options'    => array(
					'' => __( 'Clean', 'wp-carousel-pro' ),
				),
			),
		),
	)
);
// Responsive section.
//
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'title'  => __( 'Responsive Breakpoints', 'wp-carousel-pro' ),
		'icon'   => 'fa fa-tablet',
		'fields' => array(
			array(
				'id'           => 'wpcp_responsive_screen_setting',
				'type'         => 'column',
				'title'        => __( 'Minimum Screen Width', 'wp-carousel-pro' ),
				'min'          => '300',
				'unit'         => true,
				'units'        => array(
					'px',
				),
				'lg_desktop'   => false,
				'desktop_icon' => __( 'Desktop', 'wp-carousel-pro' ),
				'laptop_icon'  => __( 'Laptop', 'wp-carousel-pro' ),
				'tablet_icon'  => __( 'Tablet', 'wp-carousel-pro' ),
				'mobile_icon'  => __( 'Mobile', 'wp-carousel-pro' ),
				'default'      => array(
					'desktop' => '1200',
					'laptop'  => '980',
					'tablet'  => '736',
					'mobile'  => '480',
				),
			),
		),
	)
);

//
// Custom CSS Fields.
//
SP_WPCP_Framework::createSection(
	$prefix,
	array(
		'id'     => 'custom_css_section',
		'title'  => __( 'Custom CSS & JS', 'wp-carousel-pro' ),
		'icon'   => 'fa fa-file-code-o',
		'fields' => array(
			array(
				'id'       => 'wpcp_custom_css',
				'type'     => 'code_editor',
				'title'    => __( 'Custom CSS', 'wp-carousel-pro' ),
				'settings' => array(
					'mode'  => 'css',
					'theme' => 'monokai',
				),
			),
			array(
				'id'       => 'wpcp_custom_js',
				'type'     => 'code_editor',
				'title'    => __( 'Custom JS', 'wp-carousel-pro' ),
				'settings' => array(
					'theme' => 'monokai',
					'mode'  => 'javascript',
				),
			),
		),
	)
);
