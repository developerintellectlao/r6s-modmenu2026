<?php
/**
 * The options configuration.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    easy-accordion-free
 * @subpackage easy-accordion-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_eap_settings';

//
// Create options.
//
SP_EAP::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Settings', 'easy-accordion-free' ),
		'menu_slug'        => 'eap_settings',
		'menu_parent'      => 'edit.php?post_type=sp_easy_accordion',
		'menu_type'        => 'submenu',
		'ajax_save'        => true,
		'show_bar_menu'    => false,
		'save_defaults'    => true,
		'show_reset_all'   => false,
		'show_all_options' => false,
		'show_search'      => false,
		'show_footer'      => false,
		'framework_title'  => __( 'Settings', 'easy-accordion-free' ),
		'framework_class'  => 'sp-eap-options',
		'theme'            => 'light',
	)
);

//
// Create a section.
//
SP_EAP::createSection(
	$prefix,
	array(
		'title'  => __( 'Advanced', 'easy-accordion-free' ),
		'icon'   => 'fa fa-wrench',
		'fields' => array(
			array(
				'id'         => 'eap_data_remove',
				'type'       => 'checkbox',
				'title'      => __( 'Clean-up Data on Deletion', 'easy-accordion-free' ),
				'title_info' => __( 'Check this box if you would like Easy Accordion to completely remove all of its data when the plugin is deleted.', 'easy-accordion-free' ),
				'default'    => false,
				'sanitize'   => 'rest_sanitize_boolean',
			),
			array(
				'id'         => 'eap_focus_style',
				'type'       => 'checkbox',
				'title'      => __( 'Focus Style for Accessibility', 'easy-accordion-free' ),
				'title_info' => __( 'Check this to enable focus style to improve accessibility.', 'easy-accordion-free' ),
				'default'    => false,
			),
		),
	)
);

//
// Woo commerce faq.
//
SP_EAP::createSection(
	$prefix,
	array(
		'title'  => __( 'WooCommerce FAQs', 'easy-accordion-free' ),
		'icon'   => 'fa fa-shopping-cart',
		'fields' => array(
			// array(
			// 'type'    => 'submessage',
			// 'content' => __( 'Note: <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> is required to use this feature.', 'easy-accordion-free' ),
			// 'style'   => 'warning',
			// ),
			array(
				'id'         => 'eap_woo_faq',
				'type'       => 'switcher',
				'title'      => __( 'WooCommerce FAQs Tab', 'easy-accordion-free' ),
				'text_on'    => __( 'Enabled', 'easy-accordion-free' ),
				'text_off'   => __( 'Disabled', 'easy-accordion-free' ),
				'text_width' => '100',
				'default'    => false,
				'title_info' => '<div class="ea-info-label">' . __( 'WooCommerce FAQs Tab', 'easy-accordion-free' ) . '</div> <div class="ea-short-content">' . __( 'WooCommerce\'s FAQs tab gives quick answers to common customer queries about products and services.', 'easy-accordion-free' ) . '</div><div class="info-button"><a class="ea-open-live-demo" href="https://easyaccordion.io/product/ninja-t-shirt/" target="_blank">' . __( 'Live Demo', 'easy-accordion-free' ) . '</a></div>',
			),
			array(
				'id'         => 'eap_woo_faq_label',
				'type'       => 'text',
				'class'      => 'eap_woo_faq_label',
				'title'      => __( 'FAQs Tab Label', 'easy-accordion-free' ),
				'title_info' => __( 'Set custom text for faq tab.', 'easy-accordion-free' ),
				'default'    => __( 'FAQs', 'easy-accordion-free' ),
				'dependency' => array( 'eap_woo_faq', '==', true ),
			),
			array(
				'id'         => 'eap_woo_faq_label_priority',
				'type'       => 'spinner',
				'class'      => 'eap_woo_faq_label_priority only-for-pro',
				'title'      => __( 'FAQs Tab Priority', 'easy-accordion-free' ),
				'title_info' => __( 'Set WooCommerce FAQs tab priority position. Default value is 50.', 'easy-accordion-free' ),
				'default'    => '50',
				'dependency' => array( 'eap_woo_faq', '==', true ),
			),
			array(
				'id'         => 'eap_woo_set_tab',
				'type'       => 'group',
				'title'      => 'FAQs Tabs',
				'class'      => 'eap-woo-faq-tab-group',
				'fields'     => array(
					array(
						'id'      => 'eap_display_tab_for',
						'type'    => 'select',
						'title'   => __( 'Display FAQs on', 'easy-accordion-free' ),
						'options' => array(
							'all'               => __( 'All Products', 'easy-accordion-free' ),
							'taxonomy'          => __( 'Category', 'easy-accordion-free' ),
							'Specific_Products' => __( 'Specific Products', 'easy-accordion-free' ),
						),
						'default' => 'latest',
						'class'   => 'chosen',
					),
					array(
						'id'          => 'eap_specific_product',
						'type'        => 'select',
						'class'       => 'eap_specific_terms only-for-pro',
						'title'       => __( 'Specific Product(s)', 'easy-accordion-free' ),
						'options'     => 'posts',
						'query_args'  => array(
							'post_type'      => 'product',
							'orderby'        => 'post_date',
							'order'          => 'DESC',
							'numberposts'    => 3000,
							'posts_per_page' => 3000,
							'cache_results'  => false,
							'no_found_rows'  => true,
						),
						'chosen'      => true,
						'sortable'    => true,
						'multiple'    => true,
						'placeholder' => __( 'Choose Product', 'easy-accordion-free' ),
						'dependency'  => array( 'eap_display_tab_for', '==', 'Specific_Products' ),
					),
					array(
						'id'          => 'eap_taxonomy_terms',
						'type'        => 'select',
						'class'       => 'eap_taxonomy_terms only-for-pro',
						'title'       => __( 'Category Term(s)', 'easy-accordion-free' ),
						'options'     => 'categories',
						'query_args'  => array(
							'post_type' => 'product',
							'taxonomy'  => 'product_cat',
						),
						'chosen'      => true,
						'sortable'    => true,
						'multiple'    => true,
						'placeholder' => __( 'Choose term(s)', 'easy-accordion-free' ),
						'dependency'  => array( 'eap_display_tab_for', '==', 'taxonomy' ),
						'attributes'  => array(
							'style' => 'min-width: 250px;',
						),
					),
					array(
						'id'         => 'eap_woo_tab_shortcode',
						'type'       => 'select',
						'title'      => __( 'Select FAQs Group(s)', 'easy-accordion-free' ),
						'options'    => 'shortcode_list',
						'query_args' => array(
							'post_type'      => 'sp_easy_accordion',
							'orderby'        => 'post_date',
							'order'          => 'DESC',
							'posts_per_page' => 100,
						),
						'chosen'     => true,
						'sortable'   => true,
						'multiple'   => true,
					),
				),
				'dependency' => array( 'eap_woo_faq', '==', 'true' ),
				'attributes' => array(
					'style' => 'max-width: 250px;',
				),
			),
			array(
				'type'       => 'notice', /* translators: 1: start link tag, 2: close tag. */
				'content'    => sprintf( __( 'Want to add %1$scustom FAQs%2$s tab to your specific products page to increase sales?', 'easy-accordion-free' ), '<a href="https://easyaccordion.io/product/ninja-t-shirt/#product-56" target="_blank"><b>', '</b></a>' ) . ' <a href="https://easyaccordion.io/pricing/?ref=1" target="_blank"><b>' . __( 'Upgrade to Pro!', 'easy-accordion-free' ) . '</b></a>',
				'dependency' => array( 'eap_woo_faq', '==', 'true' ),
			),
		),
	)
);

//
// Custom CSS Fields.
//
SP_EAP::createSection(
	$prefix,
	array(
		'id'     => 'custom_css_section',
		'title'  => __( 'Custom CSS & JS', 'easy-accordion-free' ),
		'icon'   => 'fa fa-file-code-o',
		'fields' => array(
			array(
				'id'       => 'ea_custom_css',
				'type'     => 'code_editor',
				'title'    => __( 'Custom CSS', 'easy-accordion-free' ),
				'sanitize' => 'wp_strip_all_tags',
				'settings' => array(
					'mode'  => 'css',
					'theme' => 'monokai',
				),
			),
			array(
				'id'       => 'custom_js',
				'type'     => 'code_editor',
				'title'    => __( 'Custom JS', 'easy-accordion-free' ),
				'sanitize' => 'wp_strip_all_tags',
				'settings' => array(
					'theme' => 'monokai',
					'mode'  => 'javascript',
				),
			),
		),
	)
);

// Custom CSS.
SP_EAP::createSection(
	$prefix,
	array(
		'title'  => __( 'License Key', 'easy-accordion-free' ),
		'icon'   => 'fa fa-key',
		'fields' => array(
			array(
				'id'   => 'license_key',
				'type' => 'license',
			),
		),
	)
);
