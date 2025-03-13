<?php
/**
 * The file that defines the Accordion post type.
 *
 * A class the that defines the Accordion post type and make the plugins' menu.
 *
 * @link http://shapedplugin.com
 * @since 2.0.2
 *
 * @package Easy_Accordion_Free
 * @subpackage Easy_Accordion_Free/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom post class to register the Accordion.
 */
class Easy_Accordion_Free_Product_Tab {

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 2.0.2
	 */
	private static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 2.0.2
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * Holds the base class object.
	 *
	 * @since 2.0.2
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 2.0.2
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Adds a custom FAQ tab to WooCommerce product pages.
	 *
	 * @param array $tabs The existing tabs array.
	 * @return array The modified tabs array with the FAQ tab added.
	 */
	public function eap_woo_faq_tab( $tabs ) {

		// Retrieve settings from options.
		$settings         = get_option( 'sp_eap_settings' );
		$ea_tab_label     = isset( $settings['eap_woo_faq_label'] ) ? $settings['eap_woo_faq_label'] : 'FAQ';
		$eap_woo_set_tabs = isset( $settings['eap_woo_set_tab'] ) ? $settings['eap_woo_set_tab'] : array();

		$eap_woo_set_tab           = array();
		$eap_woo_tab_shortcode_ids = array();

		if ( $eap_woo_set_tabs ) {
			foreach ( $eap_woo_set_tabs as $eap_woo_set_tab ) {
				// Determine if the tab should be displayed for the current product.
				$eap_display_tab_for = isset( $eap_woo_set_tab['eap_display_tab_for'] ) ? $eap_woo_set_tab['eap_display_tab_for'] : '';

				if ( 'all' === $eap_display_tab_for ) {
					$eap_woo_set_tab           = $eap_woo_set_tab;
					$eap_woo_tab_shortcode     = isset( $eap_woo_set_tab['eap_woo_tab_shortcode'] ) ? $eap_woo_set_tab['eap_woo_tab_shortcode'] : array();
					$eap_woo_tab_shortcode_ids = array_merge( $eap_woo_tab_shortcode_ids, $eap_woo_tab_shortcode );
				}
			}
		}

		// Add the FAQ tab if there are shortcode IDs.
		if ( ! empty( $eap_woo_tab_shortcode_ids ) ) {
			$tabs['eap_faq_tab'] = array(
				'title'      => $ea_tab_label,
				'callback'   => array( $this, 'woo_new_product_tab_content' ),
				'priority'   => '50',
				'shortcodes' => $eap_woo_tab_shortcode_ids,
			);
		}

		return $tabs;
	}

	/**
	 * Displays the content of a custom tab in a WooCommerce product page.
	 *
	 * @param string $key The key or identifier of the tab.
	 * @param array  $tab The tab configuration array.
	 */
	public function woo_new_product_tab_content( $key, $tab ) {
		$current_faqs = (array) $tab['shortcodes'];
		// Display the content of the new tab.
		if ( ! empty( $current_faqs ) ) {
			foreach ( $current_faqs as $faq_list ) {
				echo do_shortcode( "[sp_easyaccordion id='" . esc_attr( $faq_list ) . "']" );
			}
		}
	}
}
