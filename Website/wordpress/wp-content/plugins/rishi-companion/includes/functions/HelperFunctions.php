<?php
/**
 * Helper Functions.
 *
 * @package Rishi_Companion
 */

// Add filter for rishi header data defaults.
add_filter(
	'rishi_header_data_defaults',
	function ( $defaults ) {
		// Set default colors for sticky header.
		$defaults['stickyHeaderDateColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		$defaults['stickyHeaderDateIconColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		// Contacts.
		$defaults['sticky_contacts_font_color'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['sticky_contacts_icon_color'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['sticky_contacts_icon_background'] = array(
			'default' => array(
				'color' => 'var(--paletteColor6)',
			),
			'hover'   => array(
				'color' => 'rgba(218, 222, 228, 0.7)',
			),
		);

		$defaults['stickyHeaderRandomizeColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		$defaults['stickyRandomizeIconColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['stickySiteTitleColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor2)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['stickySiteTaglineColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		$defaults['stickyButtonFontColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor5)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor5)',
			),
		);

		$defaults['stickyButtonFontColorOutline'] = array(
			'default' => array(
				'color' => 'var(--paletteColor3)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor5)',
			),
		);

		$defaults['stickyHeaderButtonForeground'] = array(
			'default' => array(
				'color' => 'var(--paletteColor3)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor2)',
			),
		);

		// HTML.
		$defaults['stickyHeaderTextColor']  = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);
		$defaults['sticky_headerLinkColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor3)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor2)',
			),
		);

		// Socials.
		$defaults['stickyHeaderSocialsIconColor']      = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);
		$defaults['stickyHeaderSocialsIconBackground'] = array(
			'default' => array(
				'color' => 'var(--paletteColor7)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor6)',
			),
		);

		// Menu.
		$defaults['stickyMenuFontColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		// Randomize.
		$defaults['stickyHeaderRandomizeColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);
		$defaults['stickyRandomizeIconColor']   = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor6)',
			),
		);

		// Search.
		$defaults['stickySearchHeaderIconColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);
		return $defaults;
	}
);

// Add filter for rishi header data defaults.
add_filter(
	'rishi_header_data_defaults',
	function( $defaults ) {
		// Set default colors for transparent header.
		$defaults['transparentHeaderDateColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		$defaults['transparentHeaderDateIconColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		$defaults['transparent_contacts_font_color'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['transparent_contacts_icon_color'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['transparent_contacts_icon_background'] = array(
			'default' => array(
				'color' => 'var(--paletteColor6)',
			),
			'hover'   => array(
				'color' => 'rgba(218, 222, 228, 0.7)',
			),
		);

		$defaults['transparentHeaderRandomizeColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		$defaults['transparentHeaderRandomizeIconColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['transparentSiteTitleColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor2)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		$defaults['transparentSiteTaglineColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);

		$defaults['transparentButtonFontColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor5)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor5)',
			),
		);

		$defaults['transparentButtonFontColorOutline'] = array(
			'default' => array(
				'color' => 'var(--paletteColor3)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor5)',
			),
		);

		$defaults['transparentHeaderButtonForeground'] = array(
			'default' => array(
				'color' => 'var(--paletteColor3)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor2)',
			),
		);

		// HTML.
		$defaults['transparentHeaderTextColor']  = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);
		$defaults['transparent_headerLinkColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor3)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor2)',
			),
		);

		// Socials.
		$defaults['transparentHeaderSocialsIconColor']      = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);
		$defaults['transparentHeaderSocialsIconBackground'] = array(
			'default' => array(
				'color' => 'var(--paletteColor7)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor6)',
			),
		);

		// Menu.
		$defaults['transparentMenuFontColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		// Randomize.
		$defaults['transparentHeaderRandomizeColor']     = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
		);
		$defaults['transparentHeaderRandomizeIconColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);

		// Search.
		$defaults['transparentSearchHeaderIconColor'] = array(
			'default' => array(
				'color' => 'var(--paletteColor1)',
			),
			'hover'   => array(
				'color' => 'var(--paletteColor3)',
			),
		);
		return $defaults;
	}
);

add_filter(
	'rishi_header_row_data_defaults',
	function( $defaults ) {
		// Header Row Background.
		$defaults['transparentHeaderRowBackground'] = array(
			'default' => array(
				'color' => 'rgba(0,0,0,0)',
			),
		);

		$defaults['stickyHeaderRowBackground'] = array(
			'default' => array(
				'color' => '#f9f9f9',
			),
		);

		// Header Row Top Border Color.
		$defaults['stickyHeaderRowTopBorder']      = array(
			'width' => 1,
			'style' => 'none',
			'color' => array(
				'color' => 'rgba(44,62,80,0.2)',
			),
		);
		$defaults['transparentHeaderRowTopBorder'] = array(
			'width' => 1,
			'style' => 'none',
			'color' => array(
				'color' => 'rgba(0,0,0,0)',
			),
		);

		// Header Row Bottom Border Color.
		$defaults['stickyHeaderRowBottomBorder'] = array(
			'width' => 1,
			'style' => 'none',
			'color' => array(
				'color' => 'rgba(44,62,80,0.2)',
			),
		);

		$defaults['transparentHeaderRowBottomBorder'] = array(
			'width' => 1,
			'style' => 'none',
			'color' => array(
				'color' => 'rgba(0,0,0,0)',
			),
		);

		return $defaults;
	}
);

/**
 * Check if the current request is rest or ajax
 *
 * @return bool
 */
function rishi_companion_is_dynamic_request() {
	if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( function_exists( 'wp_is_json_request' ) && wp_is_json_request() ) || wp_doing_ajax() || wp_doing_cron() ) {
		return true;
	}

	return false;
}

/**
 * Check for page builder query args
 *
 * @return bool
 */
function rishi_companion_is_page_builder() {
	$page_builders = array(
		'elementor-preview', // elementor.
		'fl_builder', // beaver builder.
		'et_fb', // divi.
		'ct_builder', // oxygen.
		'tve', // thrive.
	);

	foreach ( $page_builders as $page_builder ) {
		if ( isset( $_GET[ $page_builder ] ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Check for lazy load
 *
 * @param string $atts_string Attributes string.
 * @return array|bool Returns array of attributes if not empty, false otherwise.
 */
function rishi_lazyload_get_atts_array( $atts_string ) {
	if ( ! empty( $atts_string ) ) {
		$atts_array = array_map(
			function ( array $attribute ) {
				return $attribute['value'];
			},
			wp_kses_hair( $atts_string, wp_allowed_protocols() )
		);

		return $atts_array;
	}

	return false;
}

/**
 * Check for lazy load
 *
 * @param array $atts_array Array of attributes.
 * @return string|bool Returns string of attributes if not empty, false otherwise.
 */
function rishi_lazyload_get_atts_string( $atts_array ) {
	if ( ! empty( $atts_array ) ) {
		$assigned_atts_array = array_map(
			function ( $name, $value ) {
				if ( '' === $value ) {
					return $name;
				}
				return sprintf( '%s="%s"', $name, esc_attr( $value ) );
			},
			array_keys( $atts_array ),
			$atts_array
		);
		$atts_string         = implode( ' ', $assigned_atts_array );

		return $atts_string;
	}

	return false;
}

/**
 * Check for comment counts
 *
 * @return int Returns the number of comments for the post.
 */
function rishi_companion_get_comments_count() {
	global $post;

	return get_comments_number( $post );
}

/**
 * List function.
 *
 * @param array $list_array List array.
 * @param array $data Data array.
 * @return array Returns mapped array.
 */
function rishi_companion_list( $list_array, $data ) {
	if ( is_array( $list_array ) ) {
		return array_map(
			function ( $key ) use ( $data ) {
				if ( isset( $data[ $key ] ) ) {
					return $data[ $key ];
				}
				return null;
			},
			$list_array
		);
	}
	return $data;
}
