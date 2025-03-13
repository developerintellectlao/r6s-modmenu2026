<?php 
/**
 * Single Author Box - Dynamic CSS
 *
 * @package Rishi
 *
 * @since 1.0.0
 */
use Rishi\Customizer\Helpers\Basic as Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'rishi_dynamic_theme_css', 'rishi_single_author_box_static_css', 11 );
function rishi_single_author_box_static_css( $output_css ){

    $ed_show_post_author      = get_theme_mod( 'ed_show_post_author', 'yes' );
    $author_box_layout        = get_theme_mod( 'author_box_layout', 'layout-one' );

    if ( ( $ed_show_post_author === 'yes' ) && Helpers::get_meta( get_the_ID(), 'disable_author_box', 'no' ) === 'no' ) {
        $output_css .= '
        .author-top-wrap .img-holder img {
            border-radius: 50%;
            object-fit: cover;
            min-height: unset;
            min-width: unset;
            vertical-align: top;
        }
        
        .author-top-wrap .author-name {
            font-size: calc(var(--fontSize) / 1.5);
            font-weight: var(--fontWeight);
            line-height: var(--lineHeight);
            letter-spacing: var(--letterSpacing, 0.05px);
            margin: 0;
        }
        
        .author-top-wrap .author-description {
            display: block;
            margin-top: 8px;
        }
        
        .author-top-wrap .social-networks {
            margin: 0;
            padding: 0;
            list-style: none;
            display: block;
            margin-top: 10px;
        }
        
        .author-top-wrap .social-networks li {
            display: inline-block;
            font-size: 16px;
            margin-right: 26px;
            vertical-align: middle;
        }
        
        .author-top-wrap .social-networks li a {
            transition: all ease 0.3s;
        }
        
        .author-top-wrap .social-networks li svg {
            height: 16px;
        }
        
        .post-author-wrap {
            background: var(--paletteColor7);
        }
        
        @media (max-width: 768px) {
            .post-author-wrap .img-holder {
                text-align: center;
            }
        }
        
        .post-author-wrap .img-holder img {
            width: 130px;
            height: 130px;
        }
        
        @media (max-width: 768px) {
            .post-author-wrap .img-holder img {
                width: 100px;
                height: 100px;
            }
        }
        
        .post-author-wrap .author-content-wrapper {
            display: block;
        }
        
        .post-author-wrap .author-content-wrapper .author-meta {
            margin-bottom: 16px;
        }
        
        @media (max-width: 768px) {
            .post-author-wrap .author-content-wrapper .author-meta {
                text-align: center;
            }
        }
        
        .post-author-wrap .author-content-wrapper .rishi_social_box {
            margin: 0;
            justify-content: center;
        }
        
        @media (max-width: 768px) {
            .post-author-wrap .author-content-wrapper .rishi_social_box {
                margin: 0 0 5px;
            }
        }
        
        .post-author-wrap .author-content-wrapper .rishi_social_box a {
            margin: 0 15px 10px 0;
        }
        
        @media (max-width: 768px) {
            .post-author-wrap .author-content-wrapper .rishi_social_box a {
                margin: 0 10px 10px 0;
            }
        }
        
        .post-author-wrap .author-footer {
            display: inline-flex;
            vertical-align: middle;
            justify-content: space-between;
            width: 100%;
        }
        
        @media (max-width: 768px) {
            .post-author-wrap .author-footer {
                display: block;
            }
        }
        
        .post-author-wrap .view-all-auth {
            font-size: 0.88889em;
            font-weight: 400;
            line-height: 1em;
            color: var(--genLinkColor);
            text-decoration: none;
        }
        
        @media (max-width: 768px) {
            .post-author-wrap .view-all-auth {
                display: block;
                text-align: center;
            }
        }
        
        .post-author-wrap .view-all-auth:after {
            content: "";
            background: var(--genLinkColor);
            height: 12px;
            width: 18px;
            line-height: 1;
            display: inline-block;
            margin-left: 10px;
            font-size: 8px;
            vertical-align: middle;
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            transition: transform ease 0.25s;
        }
        
        .post-author-wrap .view-all-auth:hover {
            color: var(--genLinkHoverColor) !important;
        }
        
        .post-author-wrap .view-all-auth:hover:after {
            background: var(--genLinkHoverColor);
            transform: translateX(6px);
        }
        .autor-section.layout-one .author-top-wrap {
            display: grid;
            grid-template-columns: 130px 1fr;
            padding: 24px;
        }
        
        @media (max-width: 768px) {
            .autor-section.layout-one .author-top-wrap {
                grid-template-columns: 1fr;
                padding: 15px;
            }
        }
        
        .autor-section.layout-one .author-content-wrapper {
            padding-left: 24px;
            padding-right: 0;
        }
        
        @media (max-width: 768px) {
            .autor-section.layout-one .author-content-wrapper {
                padding-left: 0;
                padding-right: 0;
                margin-top: 10px;
            }
        }';

        if ( $author_box_layout == 'layout-two' ){
            $output_css .= '
            .autor-section.layout-two {
                margin-top: 65px;
            }
            
            @media (max-width: 768px) {
                .autor-section.layout-two {
                    margin-top: 55px;
                }
            }
            
            .autor-section.layout-two .author-top-wrap {
                padding: 0 24px 24px;
            }
            
            @media (max-width: 768px) {
                .autor-section.layout-two .author-top-wrap {
                    padding: 0 15px 15px;
                }
            }
            
            .autor-section.layout-two .author-top-wrap .author-description {
                margin-top: 16px;
            }
            
            .autor-section.layout-two .author-content-wrapper {
                margin-top: 10px;
                text-align: center;
            }
            
            .autor-section.layout-two .author-content-wrapper .author-footer {
                max-width: 450px;
            }
            
            @media (max-width: 768px) {
                .autor-section.layout-two .author-content-wrapper .author-footer {
                    max-width: 100%;
                }
            }
            
            .autor-section.layout-two .author-content-wrapper .author-footer .view-all-auth {
                width: 100%;
            }
            
            .autor-section.layout-two .author-content-wrapper .author-footer .rishi_social_box + .view-all-auth {
                width: auto;
            }
            
            .autor-section.layout-two .img-holder {
                text-align: center;
            }
            
            .autor-section.layout-two .img-holder img {
                margin-top: -54px;
            }
            
            @media (max-width: 768px) {
                .autor-section.layout-two .img-holder img {
                    margin-top: -44px;
                }
            }';
        }
    }

    return rishi_trim_css( $output_css );
}
