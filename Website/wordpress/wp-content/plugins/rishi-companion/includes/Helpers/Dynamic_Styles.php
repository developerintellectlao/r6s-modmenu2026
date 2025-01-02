<?php
/**
 * Dynamic Styles
 *
 * @package Rishi_Companion\Helpers
 */

namespace Rishi_Companion\Helpers;

/**
 * Class Dynamic_Styles
 */
class Dynamic_Styles {

	/**
	 * Dynamic_Styles constructor.
	 */
	public function __construct() {
		$secondary_menu = 'menu-secondary';
		$middle_row     = 'middle-row';
		$top_row        = 'top-row';
		$bottom_row     = 'bottom-row';
		\add_filter( 'dynamic_header_element_contacts_options', array( __CLASS__, 'dynamic_header_element_contacts_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_button_options', array( __CLASS__, 'dynamic_header_element_button_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_date_options', array( __CLASS__, 'dynamic_header_element_date_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_logo_options', array( __CLASS__, 'dynamic_header_element_logo_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_text_options', array( __CLASS__, 'dynamic_header_element_text_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_search_options', array( __CLASS__, 'dynamic_header_element_search_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_socials_options', array( __CLASS__, 'dynamic_header_element_socials_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_randomize_options', array( __CLASS__, 'dynamic_header_element_randomize_options' ), 10, 2 );
		\add_filter( 'dynamic_header_element_menu_options', array( __CLASS__, 'dynamic_header_element_menu_options' ), 10, 2 );
		\add_filter( "dynamic_header_element_{$secondary_menu}_options", array( __CLASS__, 'dynamic_header_element_secondary_menu_options' ), 10, 2 );
		\add_filter( "dynamic_header_element_{$middle_row}_options", array( __CLASS__, 'dynamic_header_element_middle_row_options' ), 10, 2 );
		\add_filter( "dynamic_header_element_{$top_row}_options", array( __CLASS__, 'dynamic_header_element_top_row_options' ), 10, 2 );
		\add_filter( "dynamic_header_element_{$bottom_row}_options", array( __CLASS__, 'dynamic_header_element_bottom_row_options' ), 10, 2 );
	}

	/**
	 * Dynamic header element contacts options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_contacts_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();

		$font_color_group = $icon_color_group = $icon_bg_group = null;

		if ( isset( $defaults['sticky_contacts_font_color'] ) ) {
			$font_color_group = $elements_data->get_mod_value(
				'font_color_group',
				array(
					'transparent_contacts_font_color' => $defaults['transparent_contacts_font_color'],
					'sticky_contacts_font_color' => $defaults['sticky_contacts_font_color'],
				)
			);
		}

		if ( isset( $defaults['sticky_contacts_icon_color'] ) ) {
			$icon_color_group = $elements_data->get_mod_value(
				'icon_color_group',
				array(
					'transparent_contacts_icon_color' => $defaults['transparent_contacts_icon_color'],
					'sticky_contacts_icon_color' => $defaults['sticky_contacts_icon_color'],
				)
			);
		}

		if ( isset( $defaults['sticky_contacts_icon_background'] ) ) {
			$icon_bg_group = $elements_data->get_mod_value(
				'icon_bg_group',
				array(
					'transparent_contacts_icon_background' => $defaults['transparent_contacts_icon_background'],
					'sticky_contacts_icon_background' => $defaults['sticky_contacts_icon_background'],
				)
			);
		}

		if ( !isset( $font_color_group, $icon_color_group, $icon_bg_group ) ) {
			return array_merge($dynamic_options, array());
		}

		$options = array();

    if ( isset( $font_color_group['sticky_contacts_font_color'] ) ) {
        $options['sticky_font_color'] = array(
            'value'     => $font_color_group['sticky_contacts_font_color'],
            'type'      => 'color',
            'default'   => $font_color_group['sticky_contacts_font_color'],
            'variables' => array(
                'default' => array(
                    'variable' => 'color',
                    'selector' => '.sticky-header.is-sticky .sticky-row #rishi-header-contacts',
                ),
                'hover'   => array(
                    'variable' => 'hover-color',
                    'selector' => '.sticky-header.is-sticky .sticky-row #rishi-header-contacts',
                ),
            ),
        );
    }

    if ( isset( $font_color_group['transparent_contacts_font_color'] ) ) {
        $options['transparent_font_color'] = array(
            'value'     => $font_color_group['transparent_contacts_font_color'],
            'type'      => 'color',
            'default'   => $font_color_group['transparent_contacts_font_color'],
            'variables' => array(
                'default' => array(
                    'variable' => 'color',
                    'selector' => '.transparent-active .transparent-header #rishi-header-contacts',
                ),
                'hover'   => array(
                    'variable' => 'hover-color',
                    'selector' => '.transparent-active .transparent-header #rishi-header-contacts',
                ),
            ),
        );
    }

    if ( isset( $icon_color_group['sticky_contacts_icon_color'] ) ) {
        $options['sticky_icon_color'] = array(
            'value'     => $icon_color_group['sticky_contacts_icon_color'],
            'type'      => 'color',
            'default'   => $icon_color_group['sticky_contacts_icon_color'],
            'variables' => array(
                'default' => array(
                    'variable' => 'icon-color',
                    'selector' => '.sticky-header.is-sticky .sticky-row #rishi-header-contacts',
                ),
                'hover'   => array(
                    'variable' => 'icon-hover-color',
                    'selector' => '.sticky-header.is-sticky .sticky-row #rishi-header-contacts',
                ),
            ),
        );
    }

    if ( isset( $icon_color_group['transparent_contacts_icon_color'] ) ) {
        $options['transparent_icon_color'] = array(
            'value'     => $icon_color_group['transparent_contacts_icon_color'],
            'type'      => 'color',
            'default'   => $icon_color_group['transparent_contacts_icon_color'],
            'variables' => array(
                'default' => array(
                    'variable' => 'icon-color',
                    'selector' => '.transparent-active .transparent-header #rishi-header-contacts',
                ),
                'hover'   => array(
                    'variable' => 'icon-hover-color',
                    'selector' => '.transparent-active .transparent-header #rishi-header-contacts',
                ),
            ),
        );
    }

    if ( isset( $icon_bg_group['sticky_contacts_icon_background'] ) ) {
        $options['sticky_icon_background'] = array(
            'value'     => $icon_bg_group['sticky_contacts_icon_background'],
            'type'      => 'color',
            'default'   => $icon_bg_group['sticky_contacts_icon_background'],
            'variables' => array(
                'default' => array(
                    'variable' => 'icon-background',
                    'selector' => '.sticky-header.is-sticky .sticky-row #rishi-header-contacts',
                ),
                'hover'   => array(
                    'variable' => 'icon-hover-background',
                    'selector' => '.sticky-header.is-sticky .sticky-row #rishi-header-contacts',
                ),
            ),
        );
    }

    if ( isset( $icon_bg_group['transparent_contacts_icon_background'] ) ) {
        $options['transparent_icon_background'] = array(
            'value'     => $icon_bg_group['transparent_contacts_icon_background'],
            'type'      => 'color',
            'default'   => $icon_bg_group['transparent_contacts_icon_background'],
            'variables' => array(
                'default' => array(
                    'variable' => 'icon-background',
                    'selector' => '.transparent-active .transparent-header #rishi-header-contacts',
                ),
                'hover'   => array(
                    'variable' => 'icon-hover-background',
                    'selector' => '.transparent-active .transparent-header #rishi-header-contacts',
                ),
            ),
        );
    }

		return array_merge( $dynamic_options, $options );
	}

	/**
	 * Dynamic header element button options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_button_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();

		$font_color_group = $elements_data->get_mod_value(
			'btn_font_color_group',
			array(
				'transparentButtonFontColor' => $defaults['transparentButtonFontColor'],
				'stickyButtonFontColor'      => $defaults['stickyButtonFontColor'],
			)
		);

		$sticky_font_color = isset( $font_color_group['stickyButtonFontColor'] ) ? $font_color_group['stickyButtonFontColor'] : $defaults['stickyButtonFontColor'];
		$trans_font_color  = isset( $font_color_group['transparentButtonFontColor'] ) ? $font_color_group['transparentButtonFontColor'] : $defaults['transparentButtonFontColor'];

		$outline_color_group = $elements_data->get_mod_value(
			'btn_outline_color_group',
			array(
				'transparentButtonFontColorOutline' => $defaults['transparentButtonFontColorOutline'],
				'stickyButtonFontColorOutline'      => $defaults['stickyButtonFontColorOutline'],
			)
		);

		$sticky_outline_color = isset( $outline_color_group['stickyButtonFontColorOutline'] ) ? $outline_color_group['stickyButtonFontColorOutline'] : $defaults['stickyButtonFontColorOutline'];
		$trans_outline_color  = isset( $outline_color_group['transparentButtonFontColorOutline'] ) ? $outline_color_group['transparentButtonFontColorOutline'] : $defaults['transparentButtonFontColorOutline'];

		$foreground_color_group = $elements_data->get_mod_value(
			'btn_foreground_group',
			array(
				'transparentHeaderButtonForeground' => $defaults['transparentHeaderButtonForeground'],
				'stickyHeaderButtonForeground'      => $defaults['stickyHeaderButtonForeground'],
			)
		);

		$sticky_foreground = isset( $foreground_color_group['stickyHeaderButtonForeground'] ) ? $foreground_color_group['stickyHeaderButtonForeground'] : $defaults['stickyHeaderButtonForeground'];
		$trans_foreground  = isset( $foreground_color_group['transparentHeaderButtonForeground'] ) ? $foreground_color_group['transparentHeaderButtonForeground'] : $defaults['transparentHeaderButtonForeground'];

		$options = array(
			'sticky_font_color'                  => array(
				'value'     => $sticky_font_color,
				'type'      => 'color',
				'default'   => $defaults['stickyButtonFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'buttonTextInitialColor',
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-button .btn-default',
					),
					'hover'   => array(
						'variable' => 'buttonTextHoverColor',
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-button .btn-default',
					),
				),
			),
			'transparent_font_color'             => array(
				'value'     => $trans_font_color,
				'type'      => 'color',
				'default'   => $defaults['transparentButtonFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'buttonTextInitialColor',
						'selector' => '.transparent-active .transparent-header #rishi-button .btn-default',
					),
					'hover'   => array(
						'variable' => 'buttonTextHoverColor',
						'selector' => '.transparent-active .transparent-header #rishi-button .btn-default',
					),
				),
			),
			'stickyHeaderButtonFontColorOutline' => array(
				'value'     => $sticky_outline_color,
				'default'   => $defaults['stickyButtonFontColorOutline'],
				'variables' => array(
					'default' => array(
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-button .btn-outline',
						'variable' => 'buttonTextInitialColor',
					),
					'hover'   => array(
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-button .btn-outline',
						'variable' => 'buttonTextHoverColor',
					),
				),
				'type'      => 'color',
			),
			'transHeaderButtonFontColorOutline'  => array(
				'value'     => $trans_outline_color,
				'default'   => $defaults['transparentButtonFontColorOutline'],
				'variables' => array(
					'default' => array(
						'selector' => '.transparent-active .transparent-header #rishi-button .btn-outline',
						'variable' => 'buttonTextInitialColor',
					),
					'hover'   => array(
						'selector' => '.transparent-active .transparent-header #rishi-button .btn-outline',
						'variable' => 'buttonTextHoverColor',
					),
				),
				'type'      => 'color',
			),
			'stickyHeaderButtonForeground'       => array(
				'value'     => $sticky_foreground,
				'default'   => $defaults['stickyHeaderButtonForeground'],
				'variables' => array(
					'default' => array(
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-button',
						'variable' => 'buttonInitialColor',
					),
					'hover'   => array(
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-button',
						'variable' => 'buttonHoverColor',
					),
				),
				'type'      => 'color',
			),
			'transHeaderButtonForeground'        => array(
				'value'     => $trans_foreground,
				'default'   => $defaults['transparentHeaderButtonForeground'],
				'variables' => array(
					'default' => array(
						'selector' => '.transparent-active .transparent-header #rishi-button',
						'variable' => 'buttonInitialColor',
					),
					'hover'   => array(
						'selector' => '.transparent-active .transparent-header #rishi-button',
						'variable' => 'buttonHoverColor',
					),
				),
				'type'      => 'color',
			),

		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element date options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_date_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();

		$text_color_group = $elements_data->get_mod_value(
			'text_color_group',
			array(
				'transparentHeaderDateColor' => $defaults['transparentHeaderDateColor'],
				'stickyHeaderDateColor'      => $defaults['stickyHeaderDateColor'],
			)
		);

		$icon_color_group = $elements_data->get_mod_value(
			'icon_color_group',
			array(
				'transparentHeaderDateIconColor' => $defaults['transparentHeaderDateIconColor'],
				'stickyHeaderDateIconColor'      => $defaults['stickyHeaderDateIconColor'],
			)
		);

		$transparent_color      = isset( $text_color_group['transparentHeaderDateColor'] ) ? $text_color_group['transparentHeaderDateColor'] : $defaults['transparentHeaderDateColor'];
		$sticky_color           = isset( $text_color_group['stickyHeaderDateColor'] ) ? $text_color_group['stickyHeaderDateColor'] : $defaults['stickyHeaderDateColor'];
		$transparent_icon_color = isset( $icon_color_group['transparentHeaderDateIconColor'] ) ? $icon_color_group['transparentHeaderDateIconColor'] : $defaults['transparentHeaderDateIconColor'];
		$sticky_icon_color      = isset( $icon_color_group['stickyHeaderDateIconColor'] ) ? $icon_color_group['stickyHeaderDateIconColor'] : $defaults['stickyHeaderDateIconColor'];

		$options = array(
			'transparentColor'     => array(
				'value'     => $transparent_color,
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderDateColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerDateInitialColor',
						'selector' => '.transparent-active .transparent-header .header-date-section',
					),
				),
			),
			'stickyColor'          => array(
				'value'     => $sticky_color,
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderDateColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerDateInitialColor',
						'selector' => '.sticky-header.is-sticky .sticky-row .header-date-section',
					),
				),
			),
			'transparentIconColor' => array(
				'value'     => $transparent_icon_color,
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderDateIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerDateInitialIconColor',
						'selector' => '.transparent-active .transparent-header .header-date-section',
					),
				),
			),
			'stickyIconColor'      => array(
				'value'     => $sticky_icon_color,
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderDateIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerDateInitialIconColor',
						'selector' => '.sticky-header.is-sticky .sticky-row .header-date-section',
					),
				),
			),
		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element logo options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_logo_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();

		$title_color = $elements_data->get_mod_value(
			'title_color_group',
			array(
				'transparentSiteTitleColor' => $defaults['transparentSiteTitleColor'],
				'stickySiteTitleColor'      => $defaults['stickySiteTitleColor'],
			)
		);

		$tagline_color = $elements_data->get_mod_value(
			'tagline_color_group',
			array(
				'transparentSiteTaglineColor' => $defaults['transparentSiteTaglineColor'],
				'stickySiteTaglineColor'      => $defaults['stickySiteTaglineColor'],
			)
		);

		$transparent_site_title_color   = isset( $title_color['transparentSiteTitleColor'] ) ? $title_color['transparentSiteTitleColor'] : $defaults['transparentSiteTitleColor'];
		$sticky_site_title_color        = isset( $title_color['stickySiteTitleColor'] ) ? $title_color['stickySiteTitleColor'] : $defaults['stickySiteTitleColor'];
		$transparent_site_tagline_color = isset( $tagline_color['transparentSiteTaglineColor'] ) ? $tagline_color['transparentSiteTaglineColor'] : $defaults['transparentSiteTaglineColor'];
		$sticky_site_tagline_color      = isset( $tagline_color['stickySiteTaglineColor'] ) ? $tagline_color['stickySiteTaglineColor'] : $defaults['stickySiteTaglineColor'];

		$options = array(
			'transparentSiteTitleColor'   => array(
				'value'     => $transparent_site_title_color,
				'default'   => $defaults['transparentSiteTitleColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.transparent-active .transparent-header .site-branding',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.transparent-active .transparent-header .site-branding',
					),
				),
				'type'      => 'color',
			),
			'stickySiteTitleColor'        => array(
				'value'     => $sticky_site_title_color,
				'default'   => $defaults['stickySiteTitleColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.sticky-header.is-sticky .sticky-row .site-branding',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.sticky-header.is-sticky .sticky-row .site-branding',
					),
				),
				'type'      => 'color',
			),
			'transparentSiteTaglineColor' => array(
				'value'     => $transparent_site_tagline_color,
				'default'   => $defaults['transparentSiteTaglineColor'],
				'variables' => array(
					'default' => array(
						'selector' => '.transparent-active .transparent-header .site-branding',
						'variable' => 'color',
					),
				),
				'type'      => 'color',
			),
			'stickySiteTaglineColor'      => array(
				'value'     => $sticky_site_tagline_color,
				'default'   => $defaults['stickySiteTaglineColor'],
				'variables' => array(
					'default' => array(
						'selector' => '.sticky-header.is-sticky .sticky-row .site-branding',
						'variable' => 'color',
					),
				),
				'type'      => 'color',
			),
		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element text options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_text_options( $dynamic_options, $elements_data ) {
		$defaults         = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$text_color_group = $elements_data->get_mod_value(
			'text_color_group',
			array(
				'transparentHeaderTextColor' => $defaults['transparentHeaderTextColor'],
				'stickyHeaderTextColor'      => $defaults['stickyHeaderTextColor'],
			)
		);
		$link_color_group = $elements_data->get_mod_value(
			'link_color_group',
			array(
				'transparent_headerLinkColor' => $defaults['transparent_headerLinkColor'],
				'sticky_headerLinkColor'      => $defaults['sticky_headerLinkColor'],
			)
		);

		$options = array();

		if ( isset( $text_color_group['stickyHeaderTextColor'] ) ) {
			$options['sticky_font_color'] = array(
				'value'     => $text_color_group['stickyHeaderTextColor'],
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderTextColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'color',
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-text',
					),
				),
			);
		}

		if ( isset( $text_color_group['transparentHeaderTextColor'] ) ) {
			$options['transparent_font_color'] = array(
				'value'     => $text_color_group['transparentHeaderTextColor'],
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderTextColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'color',
						'selector' => '.transparent-active .transparent-header #rishi-text',
					),
				),
			);
		}

		if ( isset( $link_color_group['sticky_headerLinkColor'] ) ) {
			$options['sticky_headerLinkColor'] = array(
				'value'     => $link_color_group['sticky_headerLinkColor'],
				'type'      => 'color',
				'default'   => $defaults['sticky_headerLinkColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.sticky-header.is-sticky .sticky-row #rishi-text a',
					),
				),
			);
		}

		if ( isset( $link_color_group['transparent_headerLinkColor'] ) ) {
			$options['transparent_headerLinkColor'] = array(
				'value'     => $link_color_group['transparent_headerLinkColor'],
				'type'      => 'color',
				'default'   => $defaults['transparent_headerLinkColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.transparent-active .transparent-header #rishi-text a',
					),
				),
			);
		}

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element socials options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_socials_options( $dynamic_options, $elements_data ) {
		$defaults              = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$icon_color_group      = $elements_data->get_mod_value(
			'icon_color_group',
			array(
				'transparentHeaderSocialsIconColor' => $defaults['transparentHeaderSocialsIconColor'],
				'stickyHeaderSocialsIconColor'      => $defaults['stickyHeaderSocialsIconColor'],
			)
		);
		$icon_color_background = $elements_data->get_mod_value(
			'icon_color_background',
			array(
				'transparentHeaderSocialsIconBackground' => $defaults['transparentHeaderSocialsIconBackground'],
				'stickyHeaderSocialsIconBackground'      => $defaults['stickyHeaderSocialsIconBackground'],
			)
		);

		$options = array();

		if ( isset( $icon_color_group['stickyHeaderSocialsIconColor'] ) ) {
			$options['stickyHeaderSocialsIconColor'] = array(
				'value'     => $icon_color_group['stickyHeaderSocialsIconColor'],
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderSocialsIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '.sticky-header.is-sticky .sticky-row .rishi-color-type-custom',
					),
					'hover'   => array(
						'variable' => 'icon-hover-color',
						'selector' => '.sticky-header.is-sticky .sticky-row .rishi-color-type-custom',
					),
				),
			);
		}

		if ( isset( $icon_color_group['transparentHeaderSocialsIconColor'] ) ) {
			$options['transparentHeaderSocialsIconColor'] = array(
				'value'     => $icon_color_group['transparentHeaderSocialsIconColor'],
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderSocialsIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '.transparent-active .transparent-header .rishi-color-type-custom',
					),
					'hover'   => array(
						'variable' => 'icon-hover-color',
						'selector' => '.transparent-active .transparent-header .rishi-color-type-custom',
					),
				),
			);
		}

		if ( isset( $icon_color_background['stickyHeaderSocialsIconBackground'] ) ) {
			$options['stickyHeaderSocialsIconBackground'] = array(
				'value'     => $icon_color_background['stickyHeaderSocialsIconBackground'],
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderSocialsIconBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.sticky-header.is-sticky .sticky-row .rishi-color-type-custom',
					),
					'hover'   => array(
						'variable' => 'background-hover-color',
						'selector' => '.sticky-header.is-sticky .sticky-row .rishi-color-type-custom',
					),
				),
			);
		}

		if ( isset( $icon_color_background['transparentHeaderSocialsIconBackground'] ) ) {
			$options['transparentHeaderSocialsIconBackground'] = array(
				'value'     => $icon_color_background['transparentHeaderSocialsIconBackground'],
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderSocialsIconBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-hover-color',
						'selector' => '.transparent-active .transparent-header .rishi-color-type-custom',
					),
					'hover'   => array(
						'variable' => 'background-hover-color',
						'selector' => '.transparent-active .transparent-header .rishi-color-type-custom',
					),
				),
			);
		}

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element randomize options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_randomize_options( $dynamic_options, $elements_data ) {
		$defaults         = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$text_color_group = $elements_data->get_mod_value(
			'text_color_group',
			array(
				'transparentHeaderRandomizeColor' => $defaults['transparentHeaderRandomizeColor'],
				'stickyHeaderRandomizeColor'      => $defaults['stickyHeaderRandomizeColor'],
			)
		);
		$icon_color_group = $elements_data->get_mod_value(
			'icon_color_group',
			array(
				'transparentHeaderRandomizeIconColor' => $defaults['transparentHeaderRandomizeIconColor'],
				'stickyRandomizeIconColor'            => $defaults['stickyRandomizeIconColor'],
			)
		);

		$options = array();

		if ( isset( $text_color_group['stickyHeaderRandomizeColor'] ) ) {
			$options['stickyHeaderRandomizeColor'] = array(
				'value'     => $text_color_group['stickyHeaderRandomizeColor'],
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderRandomizeColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerRandomizeInitialColor',
						'selector' => '.sticky-header.is-sticky .sticky-row .header-randomize-section',
					),
				),
			);
		}

		if ( isset ( $text_color_group['transparentHeaderRandomizeColor'] ) ) {
			$options['transparentHeaderRandomizeColor'] = array(
				'value'     => $text_color_group['transparentHeaderRandomizeColor'],
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderRandomizeColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerRandomizeInitialColor',
						'selector' => '.transparent-active .transparent-header .header-randomize-section',
					),
				),
			);
		}

		if ( isset( $icon_color_group['stickyRandomizeIconColor'] ) ) {
			$options['stickyRandomizeIconColor'] = array(
				'value'     => $icon_color_group['stickyRandomizeIconColor'],
				'type'      => 'color',
				'default'   => $defaults['stickyRandomizeIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerRandomizeInitialIconColor',
						'selector' => '.sticky-header.is-sticky .sticky-row .header-randomize-section',
					),
					'hover'   => array(
						'variable' => 'headerRandomizeInitialIconHoverColor',
						'selector' => '.sticky-header.is-sticky .sticky-row .header-randomize-section',
					),
				),
			);
		}

		if ( isset( $icon_color_group['transparentHeaderRandomizeIconColor'] ) ) {
			$options['transparentHeaderRandomizeIconColor'] = array(
				'value'     => $icon_color_group['transparentHeaderRandomizeIconColor'],
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderRandomizeIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'headerRandomizeInitialIconColor',
						'selector' => '.transparent-active .transparent-header .header-randomize-section',
					),
					'hover'   => array(
						'variable' => 'headerRandomizeInitialIconHoverColor',
						'selector' => '.transparent-active .transparent-header .header-randomize-section',
					),
				),
			);
		}

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element menu options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_menu_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();

		$menu_font_color = $elements_data->get_mod_value(
			'menu_font_color_group',
			array(
				'transparentMenuFontColor' => $defaults['transparentMenuFontColor'],
				'stickyMenuFontColor'      => $defaults['stickyMenuFontColor'],
			)
		);

		$trans_font_color  = isset( $menu_font_color['transparentMenuFontColor'] ) ? $menu_font_color['transparentMenuFontColor'] : $defaults['transparentMenuFontColor'];
		$sticky_font_color = isset( $menu_font_color['stickyMenuFontColor'] ) ? $menu_font_color['stickyMenuFontColor'] : $defaults['stickyMenuFontColor'];

		$options = array(
			'stickyHeaderMenuColor' => array(
				'value'     => $sticky_font_color,
				'type'      => 'color',
				'default'   => $defaults['stickyMenuFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.site-header .sticky-header.is-sticky .sticky-row .site-navigation-1 > ul > li > a',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.site-header .sticky-header.is-sticky .sticky-row .site-navigation-1 > ul > li > a',
					),
				),
			),
			'transHeaderMenuColor'  => array(
				'value'     => $trans_font_color,
				'type'      => 'color',
				'default'   => $defaults['transparentMenuFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.transparent-active .site-header .transparent-header .site-navigation-1 > ul > li > a',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.transparent-active .site-header .transparent-header .site-navigation-1 > ul > li > a',
					),
				),
			),
		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element secondary menu options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_secondary_menu_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();

		$menu_font_color = $elements_data->get_mod_value(
			'menu_font_color_group',
			array(
				'transparentMenuFontColor' => $defaults['transparentMenuFontColor'],
				'stickyMenuFontColor'      => $defaults['stickyMenuFontColor'],
			)
		);

		$trans_font_color  = isset( $menu_font_color['transparentMenuFontColor'] ) ? $menu_font_color['transparentMenuFontColor'] : $defaults['transparentMenuFontColor'];
		$sticky_font_color = isset( $menu_font_color['stickyMenuFontColor'] ) ? $menu_font_color['stickyMenuFontColor'] : $defaults['stickyMenuFontColor'];

		$options = array(
			'stickyHeaderMenuColor' => array(
				'value'     => $sticky_font_color,
				'type'      => 'color',
				'default'   => $defaults['stickyMenuFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.site-header .sticky-header.is-sticky .sticky-row .site-navigation-2 > ul > li > a',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.site-header .sticky-header.is-sticky .sticky-row .site-navigation-2 > ul > li > a',
					),
				),
			),
			'transHeaderMenuColor'  => array(
				'value'     => $trans_font_color,
				'type'      => 'color',
				'default'   => $defaults['transparentMenuFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.transparent-active .site-header .transparent-header .site-navigation-2 > ul > li > a',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.transparent-active .site-header .transparent-header .site-navigation-2 > ul > li > a',
					),
				),
			),
		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element search options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_search_options( $dynamic_options, $elements_data ) {
		$defaults          = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$search_icon_color = $elements_data->get_mod_value(
			'search_icon_color',
			array(
				'transparentSearchHeaderIconColor' => $defaults['transparentSearchHeaderIconColor'],
				'stickySearchHeaderIconColor'      => $defaults['stickySearchHeaderIconColor'],
			)
		);

		$options = array();

		if ( isset( $search_icon_color['stickySearchHeaderIconColor'] ) ) {
			$options['stickyHeaderMenuColor'] = array(
				'value'     => $search_icon_color['stickySearchHeaderIconColor'],
				'type'      => 'color',
				'default'   => $defaults['stickySearchHeaderIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '.sticky-header.is-sticky .sticky-row #search',
					),
					'hover'   => array(
						'variable' => 'icon-hover-color',
						'selector' => '.sticky-header.is-sticky .sticky-row #search',
					),
				),
			);
		}

		if ( isset( $search_icon_color['transparentSearchHeaderIconColor'] ) ) {
			$options['transparentSearchHeaderIconColor'] = array(
				'value'     => $search_icon_color['transparentSearchHeaderIconColor'],
				'type'      => 'color',
				'default'   => $defaults['transparentSearchHeaderIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '.transparent-active .transparent-header #search',
					),
					'hover'   => array(
						'variable' => 'icon-hover-color',
						'selector' => '.transparent-active .transparent-header #search',
					),
				),
			);
		}

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element top row options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_top_row_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults();

		$row_bg_color = $elements_data->get_mod_value(
			'row_bg_color_group',
			array(
				'transparentHeaderRowBackground' => $defaults['transparentHeaderRowBackground'],
				'stickyHeaderRowBackground'      => $defaults['stickyHeaderRowBackground'],
			)
		);

		$trans_font_color  = isset( $row_bg_color['transparentHeaderRowBackground'] ) ? $row_bg_color['transparentHeaderRowBackground'] : $defaults['transparentHeaderRowBackground'];
		$sticky_font_color = isset( $row_bg_color['stickyHeaderRowBackground'] ) ? $row_bg_color['stickyHeaderRowBackground'] : $defaults['stickyHeaderRowBackground'];

		$top_border_color = $elements_data->get_mod_value(
			'row_top_border_color_group',
			array(
				'transparentHeaderRowTopBorder' => $defaults['transparentHeaderRowTopBorder'],
				'stickyHeaderRowTopBorder'      => $defaults['stickyHeaderRowTopBorder'],
			)
		);

		$trans_top_border_color  = isset( $top_border_color['transparentHeaderRowTopBorder'] ) ? $top_border_color['transparentHeaderRowTopBorder'] : $defaults['transparentHeaderRowTopBorder'];
		$sticky_top_border_color = isset( $top_border_color['stickyHeaderRowTopBorder'] ) ? $top_border_color['stickyHeaderRowTopBorder'] : $defaults['stickyHeaderRowTopBorder'];

		$btm_border_color = $elements_data->get_mod_value(
			'row_btm_border_color_group',
			array(
				'transparentHeaderRowBottomBorder' => $defaults['transparentHeaderRowBottomBorder'],
				'stickyHeaderRowBottomBorder'      => $defaults['stickyHeaderRowBottomBorder'],
			)
		);

		$trans_btm_border_color  = isset( $btm_border_color['transparentHeaderRowBottomBorder'] ) ? $btm_border_color['transparentHeaderRowBottomBorder'] : $defaults['transparentHeaderRowBottomBorder'];
		$sticky_btm_border_color = isset( $btm_border_color['stickyHeaderRowBottomBorder'] ) ? $btm_border_color['stickyHeaderRowBottomBorder'] : $defaults['stickyHeaderRowBottomBorder'];

		$options = array(
			'stickyHeaderBgColor'         => array(
				'value'     => $sticky_font_color,
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.top-row',
					),
				),
			),
			'transHeaderBgColor'          => array(
				'value'     => $trans_font_color,
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.transparent-active .site-header .transparent-header .top-row',
					),
				),
			),
			'stickyHeaderRowTopBorder'    => array(
				'value'     => $sticky_top_border_color,
				'type'      => 'divider',
				'default'   => $defaults['stickyHeaderRowTopBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-top',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.top-row',
					),
				),
			),
			'transHeaderRowTopBorder'     => array(
				'value'     => $trans_top_border_color,
				'type'      => 'divider',
				'default'   => $defaults['transparentHeaderRowTopBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-top',
						'selector' => '.transparent-active .site-header .transparent-header .top-row',
					),
				),
			),
			'stickyHeaderRowBottomBorder' => array(
				'value'     => $sticky_btm_border_color,
				'type'      => 'divider',
				'default'   => $defaults['stickyHeaderRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-bottom',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.top-row',
					),
				),
			),
			'transHeaderRowBottomBorder'  => array(
				'value'     => $trans_btm_border_color,
				'type'      => 'divider',
				'default'   => $defaults['transparentHeaderRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-bottom',
						'selector' => '.transparent-active .site-header .transparent-header .top-row',
					),
				),
			),
		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element middle row options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_middle_row_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults();

		$row_bg_color = $elements_data->get_mod_value(
			'row_bg_color_group',
			array(
				'transparentHeaderRowBackground' => $defaults['transparentHeaderRowBackground'],
				'stickyHeaderRowBackground'      => $defaults['stickyHeaderRowBackground'],
			)
		);

		$trans_font_color  = isset( $row_bg_color['transparentHeaderRowBackground'] ) ? $row_bg_color['transparentHeaderRowBackground'] : $defaults['transparentHeaderRowBackground'];
		$sticky_font_color = isset( $row_bg_color['stickyHeaderRowBackground'] ) ? $row_bg_color['stickyHeaderRowBackground'] : $defaults['stickyHeaderRowBackground'];

		$top_border_color = $elements_data->get_mod_value(
			'row_top_border_color_group',
			array(
				'transparentHeaderRowTopBorder' => $defaults['transparentHeaderRowTopBorder'],
				'stickyHeaderRowTopBorder'      => $defaults['stickyHeaderRowTopBorder'],
			)
		);

		$trans_top_border_color  = isset( $top_border_color['transparentHeaderRowTopBorder'] ) ? $top_border_color['transparentHeaderRowTopBorder'] : $defaults['transparentHeaderRowTopBorder'];
		$sticky_top_border_color = isset( $top_border_color['stickyHeaderRowTopBorder'] ) ? $top_border_color['stickyHeaderRowTopBorder'] : $defaults['stickyHeaderRowTopBorder'];

		$btm_border_color = $elements_data->get_mod_value(
			'row_btm_border_color_group',
			array(
				'transparentHeaderRowBottomBorder' => $defaults['transparentHeaderRowBottomBorder'],
				'stickyHeaderRowBottomBorder'      => $defaults['stickyHeaderRowBottomBorder'],
			)
		);

		$trans_btm_border_color  = isset( $btm_border_color['transparentHeaderRowBottomBorder'] ) ? $btm_border_color['transparentHeaderRowBottomBorder'] : $defaults['transparentHeaderRowBottomBorder'];
		$sticky_btm_border_color = isset( $btm_border_color['stickyHeaderRowBottomBorder'] ) ? $btm_border_color['stickyHeaderRowBottomBorder'] : $defaults['stickyHeaderRowBottomBorder'];

		$options = array(
			'stickyHeaderBgColor'         => array(
				'value'     => $sticky_font_color,
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.middle-row',
					),
				),
			),
			'transHeaderBgColor'          => array(
				'value'     => $trans_font_color,
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.transparent-active .site-header .transparent-header .middle-row',
					),
				),
			),
			'stickyHeaderRowTopBorder'    => array(
				'value'     => $sticky_top_border_color,
				'type'      => 'divider',
				'default'   => $defaults['stickyHeaderRowTopBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-top',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.middle-row',
					),
				),
			),
			'transHeaderRowTopBorder'     => array(
				'value'     => $trans_top_border_color,
				'type'      => 'divider',
				'default'   => $defaults['transparentHeaderRowTopBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-top',
						'selector' => '.transparent-active .site-header .transparent-header .middle-row',
					),
				),
			),
			'stickyHeaderRowBottomBorder' => array(
				'value'     => $sticky_btm_border_color,
				'type'      => 'divider',
				'default'   => $defaults['stickyHeaderRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-bottom',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.middle-row',
					),
				),
			),
			'transHeaderRowBottomBorder'  => array(
				'value'     => $trans_btm_border_color,
				'type'      => 'divider',
				'default'   => $defaults['transparentHeaderRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'border-bottom',
						'selector' => '.transparent-active .site-header .transparent-header .middle-row',
					),
				),
			),
		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}

	/**
	 * Dynamic header element bottom row options.
	 *
	 * @param array  $dynamic_options Dynamic options.
	 * @param object $elements_data Elements data.
	 *
	 * @return array
	 */
	public static function dynamic_header_element_bottom_row_options( $dynamic_options, $elements_data ) {
		$defaults = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults();

		$row_bg_color = $elements_data->get_mod_value(
			'row_bg_color_group',
			array(
				'transparentHeaderRowBackground' => $defaults['transparentHeaderRowBackground'],
				'stickyHeaderRowBackground'      => $defaults['stickyHeaderRowBackground'],
			)
		);

		$trans_font_color  = isset( $row_bg_color['transparentHeaderRowBackground'] ) ? $row_bg_color['transparentHeaderRowBackground'] : $defaults['transparentHeaderRowBackground'];
		$sticky_font_color = isset( $row_bg_color['stickyHeaderRowBackground'] ) ? $row_bg_color['stickyHeaderRowBackground'] : $defaults['stickyHeaderRowBackground'];

		$top_border_color = $elements_data->get_mod_value(
			'row_top_border_color_group',
			array(
				'transparentHeaderRowTopBorder' => $defaults['transparentHeaderRowTopBorder'],
				'stickyHeaderRowTopBorder'      => $defaults['stickyHeaderRowTopBorder'],
			)
		);

		$trans_top_border_color  = isset( $top_border_color['transparentHeaderRowTopBorder'] ) ? $top_border_color['transparentHeaderRowTopBorder'] : $defaults['transparentHeaderRowTopBorder'];
		$sticky_top_border_color = isset( $top_border_color['stickyHeaderRowTopBorder'] ) ? $top_border_color['stickyHeaderRowTopBorder'] : $defaults['stickyHeaderRowTopBorder'];

		$btm_border_color = $elements_data->get_mod_value(
			'row_btm_border_color_group',
			array(
				'transparentHeaderRowBottomBorder' => $defaults['transparentHeaderRowBottomBorder'],
				'stickyHeaderRowBottomBorder'      => $defaults['stickyHeaderRowBottomBorder'],
			)
		);

		$trans_btm_border_color  = isset( $btm_border_color['transparentHeaderRowBottomBorder'] ) ? $btm_border_color['transparentHeaderRowBottomBorder'] : $defaults['transparentHeaderRowBottomBorder'];
		$sticky_btm_border_color = isset( $btm_border_color['stickyHeaderRowBottomBorder'] ) ? $btm_border_color['stickyHeaderRowBottomBorder'] : $defaults['stickyHeaderRowBottomBorder'];

		$options = array(
			'stickyHeaderBgColor'         => array(
				'value'     => $sticky_font_color,
				'type'      => 'color',
				'default'   => $defaults['stickyHeaderRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.bottom-row',
					),
				),
			),
			'transHeaderBgColor'          => array(
				'value'     => $trans_font_color,
				'type'      => 'color',
				'default'   => $defaults['transparentHeaderRowBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.transparent-active .site-header .transparent-header .bottom-row',
					),
				),
			),
			'stickyHeaderRowTopBorder'    => array(
				'value'     => $sticky_top_border_color,
				'type'      => 'divider',
				'default'   => $defaults['stickyHeaderRowTopBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'borderTop',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.bottom-row',
					),
				),
			),
			'transHeaderRowTopBorder'     => array(
				'value'     => $trans_top_border_color,
				'type'      => 'divider',
				'default'   => $defaults['transparentHeaderRowTopBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'borderTop',
						'selector' => '.transparent-active .site-header .transparent-header .bottom-row',
					),
				),
			),
			'stickyHeaderRowBottomBorder' => array(
				'value'     => $sticky_btm_border_color,
				'type'      => 'divider',
				'default'   => $defaults['stickyHeaderRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'borderBottom',
						'selector' => '.sticky-header.is-sticky .sticky-row.header-row.bottom-row',
					),
				),
			),
			'transHeaderRowBottomBorder'  => array(
				'value'     => $trans_btm_border_color,
				'type'      => 'divider',
				'default'   => $defaults['transparentHeaderRowBottomBorder'],
				'unit'      => 'px',
				'variables' => array(
					'default' => array(
						'variable' => 'borderBottom',
						'selector' => '.transparent-active .site-header .transparent-header .bottom-row',
					),
				),
			),
		);

		$dynamic_options = array_merge( $dynamic_options, array_values( $options ) );

		return $dynamic_options;
	}
}
