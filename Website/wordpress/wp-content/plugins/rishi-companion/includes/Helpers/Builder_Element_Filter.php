<?php
/**
 * Filter method for the builder elements.
 *
 * @package Rishi_Companion\Helpers
 */

namespace Rishi_Companion\Helpers;

use Rishi\Customizer\ControlTypes;

/**
 * Class Builder_Element_Filter
 *
 * This class is used to filter the builder elements.
 */
class Builder_Element_Filter {

	/**
	 * Constructor.
	 *
	 * Add filters for header elements.
	 */
	public function __construct() {
		// Add filters for header elements.
		$secondary_menu = 'menu-secondary';
		$middle_row     = 'middle-row';
		$top_row        = 'top-row';
		$bottom_row     = 'bottom-row';
		\add_filter( 'header_element_contacts_options', array( __CLASS__, 'header_element_contacts_options' ), 10, 3 );
		\add_filter( 'header_element_logo_options', array( __CLASS__, 'header_element_logo_options' ), 10, 3 );
		\add_filter( 'header_element_date_options', array( __CLASS__, 'header_element_date_options' ), 10, 3 );
		\add_filter( 'header_element_search_options', array( __CLASS__, 'header_element_search_options' ), 10, 3 );
		\add_filter( 'header_element_menu_options', array( __CLASS__, 'header_element_menu_options' ), 10, 3 );
		\add_filter( "header_element_{$secondary_menu}_options", array( __CLASS__, 'header_element_secondary_menu_options' ), 10, 3 );
		\add_filter( 'header_element_randomize_options', array( __CLASS__, 'header_element_randomize_options' ), 10, 3 );
		\add_filter( 'header_element_socials_options', array( __CLASS__, 'header_element_socials_options' ), 10, 3 );
		\add_filter( 'header_element_text_options', array( __CLASS__, 'header_element_text_options' ), 10, 3 );
		\add_filter( 'header_element_button_options', array( __CLASS__, 'header_element_button_options' ), 10, 3 );
		\add_filter( 'header_element_button_options', array( __CLASS__, 'header_element_button_options' ), 10, 3 );
		\add_filter( "header_element_{$middle_row}_options", array( __CLASS__, 'header_element_middle_row_options' ), 10, 3 );
		\add_filter( "header_element_{$top_row}_options", array( __CLASS__, 'header_element_top_row_options' ), 10, 3 );
		\add_filter( "header_element_{$bottom_row}_options", array( __CLASS__, 'header_element_bottom_row_options' ), 10, 3 );
	}

	/**
	 * Filters the options for the header element contacts.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_contacts_options( $options, $key ) {
		if ( isset( $options['font_color_group'] ) ) {
			$options['font_color_group']['value'] = array_merge(
				$options['font_color_group']['value'],
				array(
					'transparent_contacts_font_color' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'sticky_contacts_font_color'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['font_color_group']['settings'] = array_merge(
				$options['font_color_group']['settings'],
				array(
					'transparent_contacts_font_color' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'design'     => 'inline',
						'responsive' => false,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'pickers'    => array(
							array(
								'title'   => __( 'Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
							array(
								'title'   => __( 'Hover', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--hover-color)',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
					),
					'sticky_contacts_font_color'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'pickers'    => array(
							array(
								'title'   => __( 'Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
							array(
								'title'   => __( 'Hover', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--hover-color)',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['icon_color_group'] ) ) {
			$options['icon_color_group']['value'] = array_merge(
				$options['icon_color_group']['value'],
				array(
					'transparent_contacts_icon_color' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'sticky_contacts_icon_color'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['icon_color_group']['settings'] = array_merge(
				$options['icon_color_group']['settings'],
				array(
					'transparent_contacts_icon_color' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'pickers'    => array(
							array(
								'title'   => __( 'Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
							array(
								'title'   => __( 'Hover', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--hover-color)',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
					),
					'sticky_contacts_icon_color'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'pickers'    => array(
							array(
								'title'   => __( 'Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
							array(
								'title'   => __( 'Hover', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--hover-color)',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['icon_bg_group'] ) ) {
			$options['icon_bg_group']['value'] = array_merge(
				$options['icon_bg_group']['value'],
				array(
					'transparent_contacts_icon_background' => array(
						'default' => array(
							'color' => 'var(--paletteColor6)',
						),
						'hover'   => array(
							'color' => 'rgba(218, 222, 228, 0.7)',
						),
					),
					'sticky_contacts_icon_background'      => array(
						'default' => array(
							'color' => 'var(--paletteColor6)',
						),
						'hover'   => array(
							'color' => 'rgba(218, 222, 228, 0.7)',
						),
					),
				)
			);

			$options['icon_bg_group']['settings'] = array_merge(
				$options['icon_bg_group']['settings'],
				array(
					'transparent_contacts_icon_background' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor6)',
							),
							'hover'   => array(
								'color' => 'rgba(218, 222, 228, 0.7)',
							),
						),
					),
					'sticky_contacts_icon_background'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor6)',
							),
							'hover'   => array(
								'color' => 'rgba(218, 222, 228, 0.7)',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element logo.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_logo_options( $options, $key ) {

		if ( isset( $options['title_color_group'] ) ) {
			$options['title_color_group']['value'] = array_merge(
				$options['title_color_group']['value'],
				array(
					'transparentSiteTitleColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor2)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'stickySiteTitleColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor2)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['title_color_group']['settings'] = array_merge(
				$options['title_color_group']['settings'],
				array(
					'transparentSiteTitleColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor2)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
					'stickySiteTitleColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor2)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['tagline_color_group'] ) ) {
			$options['tagline_color_group']['value'] = array_merge(
				$options['tagline_color_group']['value'],
				array(
					'transparentSiteTaglineColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
					'stickySiteTaglineColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
				)
			);

			$options['tagline_color_group']['settings'] = array_merge(
				$options['tagline_color_group']['settings'],
				array(
					'transparentSiteTaglineColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
					'stickySiteTaglineColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element date.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_date_options( $options, $key ) {

		if ( isset( $options['text_color_group'] ) ) {
			$options['text_color_group']['value'] = array_merge(
				$options['text_color_group']['value'],
				array(

					'transparentHeaderDateColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
					'stickyHeaderDateColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
				)
			);

			$options['text_color_group']['settings'] = array_merge(
				$options['text_color_group']['settings'],
				array(
					'transparentHeaderDateColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
						'value'   => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
					'stickyHeaderDateColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'pickers'    => array(
							array(
								'title'   => __( 'Text Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
						),
						'value'   => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['icon_color_group'] ) ) {
			$options['icon_color_group']['value'] = array_merge(
				$options['icon_color_group']['value'],
				array(
					'transparentHeaderDateIconColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
					'stickyHeaderDateIconColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
				)
			);

			$options['icon_color_group']['settings'] = array_merge(
				$options['icon_color_group']['settings'],
				array(
					'transparentHeaderDateIconColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
					'stickyHeaderDateIconColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'pickers'    => array(
							array(
								'title'   => __( 'Text Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element search.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_search_options( $options, $key ) {

		if ( isset( $options['search_icon_color'] ) ) {
			$options['search_icon_color']['value'] = array_merge(
				$options['search_icon_color']['value'],
				array(
					'transparentSearchHeaderIconColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'stickySearchHeaderIconColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['search_icon_color']['settings'] = array_merge(
				$options['search_icon_color']['settings'],
				array(
					'transparentSearchHeaderIconColor' => array(
						'label'        => __( 'Transparent State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_transparent_header' => 'yes' ),
						'colorPalette' => true,
						'design'       => 'inline',
						'responsive'   => false,
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
						'value'        => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
					),
					'stickySearchHeaderIconColor'      => array(
						'label'        => __( 'Sticky State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_sticky_header' => 'yes' ),
						'colorPalette' => true,
						'design'       => 'inline',
						'responsive'   => false,
						'pickers'      => array(
							array(
								'title'   => __( 'Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
							array(
								'title'   => __( 'Hover', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--hover-color)',
							),
						),
						'value'        => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element menu.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_menu_options( $options, $key ) {
		if ( isset( $options['menu_font_color_group'] ) ) {
			$options['menu_font_color_group']['value'] = array_merge(
				$options['menu_font_color_group']['value'],
				array(
					'transparentMenuFontColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'stickyMenuFontColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['menu_font_color_group']['settings'] = array_merge(
				$options['menu_font_color_group']['settings'],
				array(
					'transparentMenuFontColor' => array(
						'label'        => __( 'Transparent State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_transparent_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title'   => __( 'Hover/Active', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--linkHoverColor)',
							),
						),
					),
					'stickyMenuFontColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title'   => __( 'Hover/Active', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--linkHoverColor)',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element secondary menu.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_secondary_menu_options( $options, $key ) {
		if ( isset( $options['menu_font_color_group'] ) ) {
			$options['menu_font_color_group']['value'] = array_merge(
				$options['menu_font_color_group']['value'],
				array(
					'transparentMenuFontColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'stickyMenuFontColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['menu_font_color_group']['settings'] = array_merge(
				$options['menu_font_color_group']['settings'],
				array(
					'transparentMenuFontColor' => array(
						'label'        => __( 'Transparent State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_transparent_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title'   => __( 'Hover/Active', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--linkHoverColor)',
							),
						),
					),
					'stickyMenuFontColor'      => array(
						'label'        => __( 'Sticky State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_sticky_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title'   => __( 'Hover/Active', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--linkHoverColor)',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element randomize.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_randomize_options( $options, $key ) {
		if ( isset( $options['text_color_group'] ) ) {
			$options['text_color_group']['value'] = array_merge(
				$options['text_color_group']['value'],
				array(
					'transparentHeaderRandomizeColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
					'stickyHeaderRandomizeColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
				)
			);

			$options['text_color_group']['settings'] = array_merge(
				$options['text_color_group']['settings'],
				array(
					'transparentHeaderRandomizeColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
					'stickyHeaderRandomizeColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['icon_color_group'] ) ) {
			$options['icon_color_group']['value'] = array_merge(
				$options['icon_color_group']['value'],
				array(
					'transparentHeaderRandomizeIconColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'stickyRandomizeIconColor'            => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['icon_color_group']['settings'] = array_merge(
				$options['icon_color_group']['settings'],
				array(
					'transparentHeaderRandomizeIconColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
					'stickyRandomizeIconColor'            => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
                        'colorPalette'	  => true,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element socials.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_socials_options( $options, $key ) {
		if ( isset( $options['icon_color_group'] ) ) {
			$options['icon_color_group']['value'] = array_merge(
				$options['icon_color_group']['value'],
				array(
					'transparentHeaderSocialsIconColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
					'stickyHeaderSocialsIconColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor3)',
						),
					),
				)
			);

			$options['icon_color_group']['settings'] = array_merge(
				$options['icon_color_group']['settings'],
				array(
					'transparentHeaderSocialsIconColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
					'stickyHeaderSocialsIconColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['icon_color_background'] ) ) {
			$options['icon_color_background']['value'] = array_merge(
				$options['icon_color_background']['value'],
				array(
					'transparentHeaderSocialsIconBackground' => array(
						'default' => array(
							'color' => 'var(--paletteColor7)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor6)',
						),
					),
					'stickyHeaderSocialsIconBackground'      => array(
						'default' => array(
							'color' => 'var(--paletteColor7)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor6)',
						),
					),
				)
			);

			$options['icon_color_background']['settings'] = array_merge(
				$options['icon_color_background']['settings'],
				array(
					'transparentHeaderSocialsIconBackground' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor7)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor6)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
					'stickyHeaderSocialsIconBackground'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor7)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor6)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element text.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_text_options( $options, $key ) {
		if ( isset( $options['text_color_group'] ) ) {
			$options['text_color_group']['value'] = array_merge(
				$options['text_color_group']['value'],
				array(
					'transparentHeaderTextColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
					'stickyHeaderTextColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor1)',
						),
					),
				)
			);

			$options['text_color_group']['settings'] = array_merge(
				$options['text_color_group']['settings'],
				array(
					'transparentHeaderTextColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
					'stickyHeaderTextColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['link_color_group'] ) ) {
			$options['link_color_group']['value'] = array_merge(
				$options['link_color_group']['value'],
				array(
					'transparent_headerLinkColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor3)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor2)',
						),
					),
					'sticky_headerLinkColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor3)',
						),
						'hover'   => array(
							'color' => 'var(--paletteColor2)',
						),
					),
				)
			);

			$options['link_color_group']['settings'] = array_merge(
				$options['link_color_group']['settings'],
				array(
					'transparent_headerLinkColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
                        'colorPalette'	  => true,
						'pickers'    => array(
							array(
								'title' => __( 'Text Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
							array(
								'title' => __( 'Link Initial', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor2)',
							),
						),
					),
					'sticky_headerLinkColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'pickers'    => array(
							array(
								'title'   => __( 'Text Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--color)',
							),
							array(
								'title'   => __( 'Link Initial', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--hover-color)',
							),
						),
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),
							'hover'   => array(
								'color' => 'var(--paletteColor2)',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element button.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_button_options( $options, $key ) {

		if ( isset( $options['btn_font_color_group'] ) ) {
			$options['btn_font_color_group']['value'] = array_merge(
				$options['btn_font_color_group']['value'],
				array(
					'transparentButtonFontColor' => array(
						'default' => array(
							'color' => 'var(--paletteColor5)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor5)',
						),
					),
					'stickyButtonFontColor'      => array(
						'default' => array(
							'color' => 'var(--paletteColor5)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor5)',
						),
					),
				)
			);

			$options['btn_font_color_group']['settings'] = array_merge(
				$options['btn_font_color_group']['settings'],
				array(
					'transparentButtonFontColor' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'design'     => 'inline',
						'responsive' => false,
                        'colorPalette'	  => true,
						'conditions' => [ 'has_transparent_header' => 'yes' ],
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor5)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor5)',
							),
						),
						'pickers'    => array(
							array(
								'title'   => __( 'Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--buttonInitialColor)',
							),

							array(
								'title'   => __( 'Hover', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--buttonHoverColor)',
							),
						),
					),
					'stickyButtonFontColor'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => [ 'has_sticky_header' => 'yes' ],
						'design'     => 'inline',
						'responsive' => false,
                        'colorPalette'	  => true,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor5)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor5)',
							),
						),
						'pickers'    => array(
							array(
								'title'   => __( 'Initial', 'rishi-companion' ),
								'id'      => 'default',
								'inherit' => 'var(--buttonInitialColor)',
							),

							array(
								'title'   => __( 'Hover', 'rishi-companion' ),
								'id'      => 'hover',
								'inherit' => 'var(--buttonHoverColor)',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['btn_outline_color_group'] ) ) {
			$options['btn_outline_color_group']['value'] = array_merge(
				$options['btn_outline_color_group']['value'],
				array(
					'transparentButtonFontColorOutline' => array(
						'default' => array(
							'color' => 'var(--paletteColor3)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor5)',
						),
					),
					'stickyButtonFontColorOutline'      => array(
						'default' => array(
							'color' => 'var(--paletteColor3)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor5)',
						),
					),
				)
			);

			$options['btn_outline_color_group']['settings'] = array_merge(
				$options['btn_outline_color_group']['settings'],
				array(
					'transparentButtonFontColorOutline' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor5)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
					'stickyButtonFontColorOutline'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
                        'colorPalette'	  => true,
						'responsive' => false,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor5)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['btn_foreground_group'] ) ) {
			$options['btn_foreground_group']['value'] = array_merge(
				$options['btn_foreground_group']['value'],
				array(
					'transparentHeaderButtonForeground' => array(
						'default' => array(
							'color' => 'var(--paletteColor3)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor2)',
						),
					),
					'stickyHeaderButtonForeground'      => array(
						'default' => array(
							'color' => 'var(--paletteColor3)',
						),

						'hover'   => array(
							'color' => 'var(--paletteColor2)',
						),
					),
				)
			);

			$options['btn_foreground_group']['settings'] = array_merge(
				$options['btn_foreground_group']['settings'],
				array(
					'transparentHeaderButtonForeground' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
                        'colorPalette'	  => true,
						'value' => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor2)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
					'stickyHeaderButtonForeground'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
                        'colorPalette'	  => true,
						'value'      => array(
							'default' => array(
								'color' => 'var(--paletteColor3)',
							),

							'hover'   => array(
								'color' => 'var(--paletteColor2)',
							),
						),
						'pickers'    => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),

							array(
								'title' => __( 'Hover', 'rishi-companion' ),
								'id'    => 'hover',
							),
						),
					),
				)
			);
		}

		return $options;
	}

	/**
	 * Filters the options for the header element middle row.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_middle_row_options( $options, $key ) {

		$row_defaults = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults();

		if ( isset( $options['row_bg_color_group'] ) ) {
			$options['row_bg_color_group']['value'] = array_merge(
				$options['row_bg_color_group']['value'],
				array(
					'transparentHeaderRowBackground' => $row_defaults['transparentHeaderRowBackground'],
					'stickyHeaderRowBackground'      => $row_defaults['stickyHeaderRowBackground'],
				)
			);

			$options['row_bg_color_group']['settings'] = array_merge(
				$options['row_bg_color_group']['settings'],
				array(
					'transparentHeaderRowBackground' => array(
						'label'        => __( 'Transparent State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_transparent_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => $row_defaults['transparentHeaderRowBackground'],
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
					'stickyHeaderRowBackground'      => array(
						'label'        => __( 'Sticky State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_sticky_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => $row_defaults['stickyHeaderRowBackground'],
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['row_top_border_color_group'] ) ) {
			$options['row_top_border_color_group']['value'] = array_merge(
				$options['row_top_border_color_group']['value'],
				array(
					'transparentHeaderRowTopBorder' => $row_defaults['transparentHeaderRowTopBorder'],
					'stickyHeaderRowTopBorder'      => $row_defaults['stickyHeaderRowTopBorder'],
				)
			);

			$options['row_top_border_color_group']['settings'] = array_merge(
				$options['row_top_border_color_group']['settings'],
				array(
					'transparentHeaderRowTopBorder' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['transparentHeaderRowTopBorder'],
					),
					'stickyHeaderRowTopBorder'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['stickyHeaderRowTopBorder'],
					),
				)
			);
		}

		if ( isset( $options['row_btm_border_color_group'] ) ) {
			$options['row_btm_border_color_group']['value'] = array_merge(
				$options['row_btm_border_color_group']['value'],
				array(
					'transparentHeaderRowBottomBorder' => $row_defaults['transparentHeaderRowBottomBorder'],
					'stickyHeaderRowBottomBorder'      => $row_defaults['stickyHeaderRowBottomBorder'],
				)
			);

			$options['row_btm_border_color_group']['settings'] = array_merge(
				$options['row_btm_border_color_group']['settings'],
				array(
					'transparentHeaderRowBottomBorder' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['transparentHeaderRowBottomBorder'],
					),
					'stickyHeaderRowBottomBorder'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['stickyHeaderRowBottomBorder'],
					),
				)
			);
		}
		return $options;
	}

	/**
	 * Filters the options for the header element top row.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_top_row_options( $options, $key ) {

		$row_defaults = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults();

		if ( isset( $options['row_bg_color_group'] ) ) {
			$options['row_bg_color_group']['value'] = array_merge(
				$options['row_bg_color_group']['value'],
				array(
					'transparentHeaderRowBackground' => $row_defaults['transparentHeaderRowBackground'],
					'stickyHeaderRowBackground'      => $row_defaults['stickyHeaderRowBackground'],
				)
			);

			$options['row_bg_color_group']['settings'] = array_merge(
				$options['row_bg_color_group']['settings'],
				array(
					'transparentHeaderRowBackground' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => $row_defaults['transparentHeaderRowBackground'],
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
					'stickyHeaderRowBackground'      => array(
						'label'        => __( 'Sticky State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_sticky_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => $row_defaults['stickyHeaderRowBackground'],
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['row_top_border_color_group'] ) ) {
			$options['row_top_border_color_group']['value'] = array_merge(
				$options['row_top_border_color_group']['value'],
				array(
					'transparentHeaderRowTopBorder' => $row_defaults['transparentHeaderRowTopBorder'],
					'stickyHeaderRowTopBorder'      => $row_defaults['stickyHeaderRowTopBorder'],
				)
			);

			$options['row_top_border_color_group']['settings'] = array_merge(
				$options['row_top_border_color_group']['settings'],
				array(
					'transparentHeaderRowTopBorder' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['transparentHeaderRowTopBorder'],
					),
					'stickyHeaderRowTopBorder'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['stickyHeaderRowTopBorder'],
					),
				)
			);
		}

		if ( isset( $options['row_btm_border_color_group'] ) ) {
			$options['row_btm_border_color_group']['value'] = array_merge(
				$options['row_btm_border_color_group']['value'],
				array(
					'transparentHeaderRowBottomBorder' => $row_defaults['transparentHeaderRowBottomBorder'],
					'stickyHeaderRowBottomBorder'      => $row_defaults['stickyHeaderRowBottomBorder'],
				)
			);

			$options['row_btm_border_color_group']['settings'] = array_merge(
				$options['row_btm_border_color_group']['settings'],
				array(
					'transparentHeaderRowBottomBorder' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['transparentHeaderRowBottomBorder'],
					),
					'stickyHeaderRowBottomBorder'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['stickyHeaderRowBottomBorder'],
					),
				)
			);
		}
		return $options;
	}

	/**
	 * Filters the options for the header element bottom row.
	 *
	 * @param array  $options The current options.
	 * @param string $key The key for the options.
	 * @return array The filtered options.
	 */
	public static function header_element_bottom_row_options( $options, $key ) {

		$row_defaults = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults();

		if ( isset( $options['row_bg_color_group'] ) ) {
			$options['row_bg_color_group']['value'] = array_merge(
				$options['row_bg_color_group']['value'],
				array(
					'transparentHeaderRowBackground' => $row_defaults['transparentHeaderRowBackground'],
					'stickyHeaderRowBackground'      => $row_defaults['stickyHeaderRowBackground'],
				)
			);

			$options['row_bg_color_group']['settings'] = array_merge(
				$options['row_bg_color_group']['settings'],
				array(
					'transparentHeaderRowBackground' => array(
						'label'        => __( 'Transparent State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_transparent_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => $row_defaults['transparentHeaderRowBackground'],
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
					'stickyHeaderRowBackground'      => array(
						'label'        => __( 'Sticky State', 'rishi-companion' ),
						'control'      => ControlTypes::COLOR_PICKER,
						'conditions'   => array( 'has_sticky_header' => 'yes' ),
						'design'       => 'inline',
						'colorPalette' => true,
						'responsive'   => false,
						'value'        => $row_defaults['stickyHeaderRowBackground'],
						'pickers'      => array(
							array(
								'title' => __( 'Initial', 'rishi-companion' ),
								'id'    => 'default',
							),
						),
					),
				)
			);
		}

		if ( isset( $options['row_top_border_color_group'] ) ) {
			$options['row_top_border_color_group']['value'] = array_merge(
				$options['row_top_border_color_group']['value'],
				array(
					'transparentHeaderRowTopBorder' => $row_defaults['transparentHeaderRowTopBorder'],
					'stickyHeaderRowTopBorder'      => $row_defaults['stickyHeaderRowTopBorder'],
				)
			);

			$options['row_top_border_color_group']['settings'] = array_merge(
				$options['row_top_border_color_group']['settings'],
				array(
					'transparentHeaderRowTopBorder' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['transparentHeaderRowTopBorder'],
					),
					'stickyHeaderRowTopBorder'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['stickyHeaderRowTopBorder'],
					),
				)
			);
		}

		if ( isset( $options['row_btm_border_color_group'] ) ) {
			$options['row_btm_border_color_group']['value'] = array_merge(
				$options['row_btm_border_color_group']['value'],
				array(
					'transparentHeaderRowBottomBorder' => $row_defaults['transparentHeaderRowBottomBorder'],
					'stickyHeaderRowBottomBorder'      => $row_defaults['stickyHeaderRowBottomBorder'],
				)
			);

			$options['row_btm_border_color_group']['settings'] = array_merge(
				$options['row_btm_border_color_group']['settings'],
				array(
					'transparentHeaderRowBottomBorder' => array(
						'label'      => __( 'Transparent State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_transparent_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['transparentHeaderRowBottomBorder'],
					),
					'stickyHeaderRowBottomBorder'      => array(
						'label'      => __( 'Sticky State', 'rishi-companion' ),
						'control'    => ControlTypes::BORDER,
						'conditions' => array( 'has_sticky_header' => 'yes' ),
						'design'     => 'inline',
						'responsive' => false,
						'value'      => $row_defaults['stickyHeaderRowBottomBorder'],
					),
				)
			);
		}
		return $options;
	}
}
