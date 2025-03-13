<?php 
/**
 * Scroll to Top - Dynamic CSS
 *
 * @package Rishi
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'rishi_dynamic_theme_css', 'rishi_scroll_to_top_static_css', 11 );

/**
 * Scroll to Top - CSS
 *
 * @param  string $output_css.
 * @return String CSS for Scroll to Top.
 *
 * @since 1.0.0
 */
function rishi_scroll_to_top_static_css( $output_css ){

    $defaults             = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
    $scrolltotop          = get_theme_mod( 'ed_scroll_to_top', $defaults['ed_scroll_to_top'] );

    if ( $scrolltotop == 'no' ){
        return $output_css;
    }

    $output_css .= '
        .to_top {
            cursor: pointer;
            display: none;
            position: fixed;
            bottom: var(--topButtonOffset, 25px);
            z-index: 99;
        }
        
        .to_top.active {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            align-items: center;
        }

        .to_top {
            border: var(--top-button-border);
            box-shadow: var(--topButtonShadow);
            font-size: var(--topButtonSize);
            color: var(--topButtonIconColorDefault);
            padding: var(--top_button_padding);
            transition: all ease 0.3s;
        }
        
        .to_top:hover {
            color: var(--topButtonIconColorHover);
            border: var(--top-button-border_hover);
        }
        
        .to_top:hover svg {
            stroke: var(--topButtonIconColorHover);
        }
        
        .to_top svg {
            fill: none;
            height: 1em;
            stroke: var(--topButtonIconColorDefault);
            width: 1em;
        }
        
        .to_top.top-align-right {
            right: var(--sideButtonOffset, 25px);
        }
        
        .to_top.top-align-left {
            left: var(--sideButtonOffset, 25px);
        }
        
        .to_top.top-shape-circle  {
            border-radius: 100%;
        }
        
        .to_top.top-shape-square {
            border-radius: 3px;
        }

        .to_top.top-scroll-filled {
            background: var(--topButtonShapeBackgroundDefault);
        }

        .to_top.top-scroll-filled:hover {
            background: var(--topButtonShapeBackgroundHover);
        }
        
        .to_top.top-scroll-outline {
            background: transparent;
        }
        
        .to_top.top-type-4 svg {
            fill: currentColor;
            stroke: none;
    }';

    return rishi_trim_css( $output_css );
}
