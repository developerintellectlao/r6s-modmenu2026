<?php 
/**
 * Link Highlights - Dynamic CSS
 *
 * @package Rishi
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'rishi_dynamic_theme_css', 'rishi_link_highlights_static_css', 11 );

/**
 * Link Highlights - CSS
 *
 * @param  string $output_css.
 * @return String CSS for Link Highlights.
 *
 * @since 1.0.0
 */
function rishi_link_highlights_static_css( $output_css ){

    $defaults        = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
    $ed_related_post = get_theme_mod( 'ed_related', $defaults['ed_related'] );

    if ( $ed_related_post == 'no' && is_single() ){
        return $output_css;
    }

    $underlinestyle = get_theme_mod( 'underlinestyle', $defaults['underlinestyle'] );

    if ( get_theme_mod( 'ed_link_highlight', 'yes' ) === 'no' ) {
        return $output_css;
    }

    if ( ( get_theme_mod( 'ed_link_highlight', 'yes' ) === 'yes' ) && ( $underlinestyle === 'style2' ) ) {

        $output_css .= '
        .link-highlight-style2 .entry-content p > a {
            color: var(--linkHighlightColor);
            line-height: var(--lineHeight);
            text-decoration: none;
            min-height: auto;
            min-width: auto;
            position: relative;
            display: inline-block;
            padding: 0 5px;
            z-index: 1;
        }

        .link-highlight-style2 .entry-content p > a:after {
            content: "";
            background: var(--linkHighlightBackgroundColor);
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            transition: all ease 0.35s;
            z-index: -1;
        }

        .link-highlight-style2 .entry-content p > a:hover {
            color: var(--linkHighlightHoverColor);
        }

        .link-highlight-style2 .entry-content p > a:hover:after {
            height: 100%;
            background: var(--linkHighlightBackgroundHoverColor);
        }

        .link-highlight-style2 .entry-content .wp-block-button__link {
            color: var(--btnTextColor);
            background-color: var(--btnBgColor);
            border: 1px solid var(--btnBorderColor);
            padding: var(--buttonPadding, 5px 20px);
            text-decoration: var(--btnTextDecoration);
        }

        .link-highlight-style2 .entry-content .wp-block-button__link:hover {
            color: var(--btnTextHoverColor);
            background-color: var(--btnBgHoverColor);
            border-color: var(--btnBorderHoverColor);
        }

        .link-highlight-style2 .entry-content .wp-block-button__link:after {
            display: none;
        }';
    }

    $output_css .= '
    .link-highlight-style1 .entry-content p > a {
        color: var(--linkHighlightColor);
        line-height: var(--lineHeight);
        position: relative;
        text-decoration: underline;
    }

    .link-highlight-style1 .entry-content p > a:hover {
        color: var(--linkHighlightHoverColor);
    }';

    return rishi_trim_css( $output_css );
}