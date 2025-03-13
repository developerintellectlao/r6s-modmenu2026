<?php
/**
 * Breadcrumbs - Static CSS
 *
 * @package Rishi
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'rishi_dynamic_theme_css', 'rishi_breadcrumbs_static_css', 11 );

function rishi_breadcrumbs_static_css( $output_css ){

    $defaults = \Rishi\Customizer\Helpers\Defaults::breadcrumbs_defaults();
    $breadcrumbs_position = get_theme_mod( 'breadcrumbs_position', $defaults['breadcrumbs_position'] );

    if ( $breadcrumbs_position == 'none' ){
        return $output_css;
    }

    $output_css .= '
        .rishi-breadcrumb-main-wrap {
            padding: var(--padding);
        }

        .rishi-breadcrumbs {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .rishi-breadcrumbs > span a {
            transition: all ease 0.3s;
        }

        .rishi-breadcrumbs > span a span {
            color: var(--breadcrumbsColor);
        }

        .rishi-breadcrumbs > span a:hover span {
            color: var(--breadcrumbsCurrentColor);
        }

        .rishi-breadcrumbs > span .separator {
            display: inline-block;
            margin: 0 5px;
            line-height: 1;
        }

        .rishi-breadcrumbs > span .separator svg {
            width: calc(var(--fontSize) / 1.5);
            height: calc(var(--fontSize) / 1.5);
            color: var(--breadcrumbsSeparatorColor);
        }

        .rishi-breadcrumbs > span.current a {
            pointer-events: none;
        }

        .rishi-breadcrumbs > span.current a span {
            color: var(--breadcrumbsCurrentColor);
        }';

    return rishi_trim_css( $output_css );
}
