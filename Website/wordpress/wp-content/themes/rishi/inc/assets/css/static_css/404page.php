<?php 
/**
 * 404 Page
 *
 * @package Rishi
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'rishi_dynamic_theme_css', 'rishi_404_page_static_css', 11 );
/**
 * 404 Page - CSS
 *
 * @param  String $output_css.
 * @return String CSS for 404 Page.
 *
 * @since 1.0.0
 */
function rishi_404_page_static_css( $output_css ){

    $show_latest_post = get_theme_mod( '404_show_latest_post', 'yes' );
    $show_button      = get_theme_mod( '404_show_blog_page_button','yes' );
    $show_search      = get_theme_mod( '404_show_search_form','yes' );
    
    if ( $show_latest_post == 'yes' ){
        $output_css .= '
        .error404 .rishi-container-wrap .posts-wrap {
            display: flex;
            flex-wrap: wrap;
            margin-left: -15px;
            margin-right: -15px;
        }
        
        .error404 .rishi-container-wrap .posts-wrap article {
            padding: 0 15px;
        }

        .error404 .rishi-container-wrap .posts-wrap article .entry-meta-pri-wrap {
            padding: 20px 15px 0 15px;
        }
        
        .error404 .rishi-container-wrap .posts-wrap article .entry-meta-pri-wrap .entry-meta-pri {
            padding: 0 0 10px;
        }
        
        .error404 .rishi-container-wrap .posts-wrap article .entry-meta-pri-wrap .entry-meta-pri .cat-links {
            font-size: 15px;
        }
        
        .error404 .rishi-container-wrap .posts-wrap article .entry-meta-pri-wrap .entry-meta-pri .cat-links a {
            color: var(--genLinkColor);
        }
        
        .error404 .rishi-container-wrap .posts-wrap article .entry-meta-pri-wrap .entry-meta-pri .cat-links a:hover {
            color: var(--genLinkHoverColor);
        }
        
        .error404 .rishi-container-wrap .posts-wrap article .entry-header {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .error404 .rishi-container-wrap .posts-wrap article .entry-title {
            font-size: 1.222em;
            margin-bottom: 0;
        }
        
        .error404 .rishi-container-wrap.col-per-1 {
            max-width: 761px;
            margin: 0 auto;
            width: 100%;
        }
        
        .error404 .rishi-container-wrap.col-per-1 .posts-wrap article {
            width: 100%;
        }
        
        .error404 .rishi-container-wrap.col-per-2 .posts-wrap article {
            width: 50%;
        }
        
        @media (max-width: 768px) {
            .error404 .rishi-container-wrap.col-per-2 .posts-wrap article {
                width: 100%;
            }
        }
        
        .error404 .rishi-container-wrap.col-per-3 .posts-wrap article {
            width: 33.33%;
        }
        
        @media (max-width: 768px) {
            .error404 .rishi-container-wrap.col-per-3 .posts-wrap article {
                margin-bottom: 40px;
                width: 100%;
            }
        }
        
        .error404 .rishi-container-wrap.col-per-4 .posts-wrap article {
            width: 25%;
        }
        
        .error404.box-layout .rishi-container-wrap {
            background: none;
            padding: 0;
        }
        
        .error404.box-layout .rishi-container-wrap .posts-wrap .entry-content-main-wrap {
            padding: 0 0 5px;
        }
        
        @media (max-width: 768px) {
            .error404.content-box-layout .main-content-wrapper {
                margin-left: -15px;
                margin-right: -15px;
            }
        }
        .recommended-title {
            font-size: 1.444em;
            margin-bottom: 46px;
        }
        
        @media (max-width: 768px) {
            .recommended-title {
                font-size: 1.333333em;
                margin-bottom: 25px;
            }
        }';
    }

    if( $show_button === "yes" ){
        $output_css .= '
        .go-to-blog-wrap {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 40px;
        }
        
        .go-to-blog-wrap .go-to-blog {
            display: inline-block;
            padding: var(--buttonPadding);
            border: var(--buttonBorder);
            color: var(--btnTextColor);
            background: var(--btnBgColor);
            border-radius: var(--bottonRoundness);
            transition: all ease 0.3s;
        }
        
        .go-to-blog-wrap .go-to-blog:hover {
            background: var(--btnBgHoverColor);
            border: var(--buttonBorder_hover);
            color: var(--btnTextHoverColor);
        }';
    }

    if( $show_search === "yes" ){
        $output_css .= '.error-search-again-wrapper {
            margin-top: 55px;
        }
        
        .error-search-again-wrapper .error-search-inner {
            padding: 50px 100px;
            background: var(--baseColor);
        }
        
        @media (max-width: 768px) {
            .error-search-again-wrapper .error-search-inner {
                padding: 30px 15px;
            }
        }
        
        .error-search-again-wrapper .search-form {
            width: 100%;
            display: block;
            position: relative;
        }
        
        .error-search-again-wrapper .search-form input[type="search"] {
            width: 100%;
            height: 70px;
            line-height: 70px;
            padding: 0 20px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border: none;
            background: #FCFCFC;
        }
        
        @media (max-width: 768px) {
            .error-search-again-wrapper .search-form input[type="search"] {
                height: 60px;
                line-height: 60px;
            }
        }
        
        .error-search-again-wrapper .search-form input[type="submit"] {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0;
            height: 70px;
            width: 70px;
            border: none;
            background-repeat: no-repeat;
            background-size: 24px;
            background-position: center;
            background-color: var(--genLinkColor);
            border-radius: 0;
            padding: 0;
        }
        
        @media (max-width: 768px) {
            .error-search-again-wrapper .search-form input[type="submit"] {
                height: 60px;
                line-height: 60px;
                width: 60px;
                background-size: 20px !important;
            }
        }';
    }

    $output_css .= '
        .four-error-wrap {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 0 8%;
        }
        
        @media (max-width: 768px) {
            .four-error-wrap {
                padding: 0;
            }
        }
        
        .four-error-wrap figure {
            width: 176px;
        }
        
        @media (max-width: 768px) {
            .four-error-wrap figure {
                width: 114px;
            }
        }
        
        .four-error-wrap .four-error-content {
            width: calc(100% - 176px);
            padding-left: 70px;
        }
        
        @media (max-width: 768px) {
            .four-error-wrap .four-error-content {
                width: 100%;
                padding-left: 0;
            }
        }
        
        .four-error-wrap .error-title {
            font-size: 2.66667em;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .four-error-wrap .error-title {
                font-size: 2.222222em;
            }
        }
        
        .four-error-wrap .error-sub-title {
            font-size: 1.22222em;
            margin-top: 20px;
            margin-bottom: 0;
        }
        
        @media (max-width: 768px) {
            .four-error-wrap .error-sub-title {
                font-size: 1.11111em;
            }
        }
        
        .four-error-wrap .error-desc {
            font-size: 1em;
            color: var(--primaryColor);
            margin-top: 12px;
            margin-bottom: 0;
        }';

    return rishi_trim_css( $output_css );
}