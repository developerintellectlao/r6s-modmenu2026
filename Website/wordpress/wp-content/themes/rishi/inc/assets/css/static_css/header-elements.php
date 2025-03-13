<?php

/**
 * Header Elements
 *
 * @package Rishi
 *
 * @since 1.0.0
 */

use Rishi\Customizer\Helpers\Basic;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

global $pagenow;

if(is_admin() && !is_customize_preview()){
    return; //Solve image uploader issue in various posttypes
}

$item_array = [
    'logo',
    'date',
    'search',
    'button',
    'cart',
    'contacts',
    'image',
    'menu-secondary',
    'menu',
    'mobile-menu',
    'randomize',
    'socials',
    'text',
    'trigger'
];

$get_active_items = Basic::get_header_active_elements();

/**
 * Enqueue CSS for Customizer Screen Only
 */
foreach ($item_array as $items) {
    if (is_customize_preview() && !in_array($items, $get_active_items)) { //Only move ahead if item does not exist in database
        if (!function_exists("rishi_header_{$items}_static_css")) continue;
        add_filter('rishi_dynamic_customizer_css', "rishi_header_{$items}_static_css", 9);
    }
}

foreach ($get_active_items as $items) {
    if (in_array($items, $item_array)) {

        if (strpos($items, '-') !== false) {
            $items = str_replace('-', '_', $items);
        }

        if ($items === 'menu' || $items === 'menu_secondary') {
            $items = 'menu';
        }

        if (!function_exists("rishi_header_{$items}_static_css")) continue;
        add_filter('rishi_dynamic_theme_css', "rishi_header_{$items}_static_css", 11);
    }
}


/**
 * Header Elements - CSS
 *
 * @param  string $output_css.
 * @return String CSS for Header Elements.
 *
 * @since 1.0.0
 */
function rishi_header_logo_static_css($output_css) {
    $element_logo   = rishi_customizer()->header_builder->get_elements()->get_items()['logo'];
    $_logoinstance  = new $element_logo();

    $logo_type                 = $_logoinstance->get_mod_value('logo_type', 'logo-title');
    $logo_title_layout         = $_logoinstance->get_mod_value('logo_title_layout', 'logotitle');
    $logo_title_tagline_layout = $_logoinstance->get_mod_value('logo_title_tagline_layout', 'logotitletagline');

    $output_css .= '
        .site-branding {
            margin: var(--margin);
        }
        .site-logo {
            display: block;
            line-height: 1;
            margin: 0 auto;
            max-width: var(--LogoMaxWidth, 150px);
        
        }
        .site-logo img {
            width: auto;
            height: inherit;
            object-fit: contain;
        }
        .site-branding-container {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap:15px;
        }
        .site-description {
            color: var(--color);
            margin:0;
        }
        .site-title{
            margin:0;
        }
    ';

    if ($logo_type == 'logo-title' && $logo_title_layout === 'titlelogo') {
        $output_css .= '
            .titlelogo .site-branding-container {
                flex-direction: row-reverse;
            }
        ';
    }

    if ($logo_type == 'logo-title' && $logo_title_layout === 'logouptitle') {
        $output_css .= '
            .logouptitle .site-branding-container {
                flex-direction: column;
            }
        ';
    }

    if ($logo_type == 'logo-title' && $logo_title_layout === 'logodowntitle') {
        $output_css .= '
            .logodowntitle .site-branding-container {
                flex-direction: column-reverse;
            }
        ';
    }

    if ($logo_type == 'logo-title-tagline' && $logo_title_tagline_layout === 'titletaglinelogo') {
        $output_css .= '
            .titletaglinelogo .site-branding-container {
            flex-direction:row-reverse;
            }
        ';
    }

    if ($logo_type == 'logo-title-tagline' && $logo_title_tagline_layout === 'logodowntitletagline') {
        $output_css .= '
            .logodowntitletagline .site-logo {
                order: 2;
            }
        ';
    }

    if ($logo_type == 'logo-title-tagline' && $logo_title_tagline_layout === 'titlelogotagline') {
        $output_css .= '
            .titlelogotagline .site-logo {
                order: 2;
            }
            .titlelogotagline .site-description {
                order: 3;
                width: 100%;
            }
            .titlelogotagline .site-title {
                order: 1;
                width: 100%;
            }
        ';
    }


    if ($logo_type == 'logo-title-tagline' && ($logo_title_tagline_layout === 'logouptitletagline' || $logo_title_tagline_layout === 'logodowntitletagline' || $logo_title_tagline_layout === 'titlelogotagline')) {
        $output_css .= '
            .logouptitletagline .site-branding-container,
            .logodowntitletagline .site-branding-container,
            .titlelogotagline .site-branding-container {
                text-align: center;
            }
            .logouptitletagline .site-branding-container,
            .logodowntitletagline .site-branding-container{
                flex-direction:column;
            }

            @media (max-width: 1000px) {
                .logouptitletagline .site-branding-container,
                .logodowntitletagline .site-branding-container,
                .titlelogotagline .site-branding-container {
                    text-align: left;
                }
            }
        ';
    }

    if ($logo_type == 'logo-title-tagline' && ($logo_title_tagline_layout === 'logotitletagline' || $logo_title_tagline_layout === 'titletaglinelogo')) {
        $output_css .= '
            .logotitletagline .site-branding-container,
            .titletaglinelogo .site-branding-container {
                flex-wrap:nowrap;
            }

            @media (max-width: 1000px) {
                .logotitletagline .site-branding-container,
                .titletaglinelogo .site-branding-container {
                    gap: 5px;
                }
            }
        ';
    }

    return rishi_trim_css($output_css);
}

function rishi_header_randomize_static_css($output_css) {
    $element_randomize = rishi_customizer()->header_builder->get_elements()->get_items()['randomize'];
    $_randomizeInstance = new $element_randomize();
    $ed_randomize_label = $_randomizeInstance->get_mod_value('header_randomize_ed_title', 'no');
    $output_css .= '
        .header-randomize-section {
            display: inline-flex;
            align-items: center;
        }
        
        .header-randomize-section a {
            line-height: 0;
        }
        
        .header-randomize-section a svg {
            fill: var(--headerRandomizeInitialIconColor);
            width: var(--icon-size);
            height: var(--icon-size);
            vertical-align:middle;
        }
        
        .header-randomize-section a svg.no-fill {
            fill: none;
            stroke: var(--headerRandomizeInitialIconColor);
        }
        
        .header-randomize-section a:hover svg {
            fill: var(--headerRandomizeInitialIconHoverColor);
        }
        
        .header-randomize-section a:hover svg.no-fill {
            fill: none;
            stroke: var(--headerRandomizeInitialIconHoverColor);
        }
    ';

    if ($ed_randomize_label == 'yes') {
        $output_css .= '
            .header-randomize-section .randomize-label {
                color: var(--headerRandomizeInitialColor);
                margin-right: 5px;
            }
        ';
    }
    return rishi_trim_css($output_css);
}

function rishi_header_date_static_css($output_css) {
    $output_css .= '
        .header-date-section {
            display: inline-flex;
            align-items: baseline;
            color: var(--headerDateInitialColor);
        }
        
        .header-date-section svg {
            fill: var(--headerDateInitialIconColor);
            margin-right: 8px;
            height: var(--icon-size);
            width: var(--icon-size);
        }
        
    ';

    return rishi_trim_css($output_css);
}

function rishi_header_search_static_css($output_css) {
    $output_css .= '
        .search-form-section {
            display: inline-block;
            line-height: 0;
            position: relative;
        }
        
        .header-search-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            appearance: none;
            padding: 0;
            margin: 0;
            border: none;
            border-radius: 0;
            background: transparent;
            color: var(--primaryColor);
        }
        
        .header-search-btn:focus {
            outline: dotted 1px rgba(41, 41, 41, 0.6);
        }
        
        .header-search-btn:focus, .header-search-btn:hover {
            background: transparent;
            border: none;
        }
        
        .header-search-btn svg {
            fill: var(--icon-color);
            height: var(--icon-size);
            margin: var(--margin);
            width: var(--icon-size);
            transition: 0.3s ease all;
        }
        
        .header-search-btn:hover svg {
            fill: var(--icon-hover-color);
        }
        
        .search-toggle-form.cover-modal {
            background-color: var(--background-color);
            display: none;
            left: 0;
            margin-top: 0;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 9999;
            width: 100%;
            height: 100%;
            transition: opacity 0.3s ease-in-out;
        }
        
        .search-toggle-form .header-search-inner {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 0 15px;
            height: 100%;
        }
        
        .search-toggle-form .header-search-inner .search-form {
            display: flex;
            flex-wrap: wrap;
            max-width: 800px;
            width: 100%;
            background: #ffffff;
            position: relative;
        }
        
        .search-toggle-form .header-search-inner label {
            width: calc(100% - 60px);
        }
        
        .search-toggle-form .header-search-inner input[type="submit"] {
            text-indent: 9999999px;
            position: absolute;
            top: 0;
            right: 0;
            height: 60px;
            width: 60px;
            background-repeat: no-repeat;
            background-size: 24px;
            background-position: center;
            padding: 0 10px;
            border-radius: 0;
            border: none;
            transition: background-color 0.25s ease-in-out;
        }
        
        .search-toggle-form .header-search-inner input[type="submit"]:hover, .search-toggle-form .header-search-inner input[type="submit"]:focus {
            --btnBgHoverColor: var(--paletteColor4);
        }
        
        .search-toggle-form .header-search-inner input[type="submit"]:focus-visible {
            outline: 2px dotted rgba(41, 41, 41, 0.6);
        }
        
        .search-toggle-form input[type="search"] {
            width: 100%;
            height: 60px;
            line-height: 60px;
            padding: 13px 50px 11px 24px;
            border-color: var(--genborderColor);
            font-size: 0.777788em;
            color: var(--searchHeaderFontColor);
        }
        
        .search-toggle-form input[type="search"]::placeholder {
            color: var(--searchHeaderFontColor);
        }
        
        .search-toggle-form input[type="search"]::-moz-placeholder {
            color: var(--searchHeaderFontColor);
        }
        
        .search-toggle-form input[type="search"]:focus {
            outline: 2px dotted rgba(0, 0, 0, 0.6);
        }
        
        .search-toggle-form .btn-form-close {
            background: var(--closeButtonBackground);
            border: 1px solid var(--closeIconColor);
            border-radius: 100px;
            color: var(--closeIconColor);
            padding: 0;
            height: 30px;
            opacity: 1;
            position: absolute;
            right: 30px;
            top: 30px;
            width: 30px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: all ease 0.3s;
        }
        
        @media (max-width: 1024px) {
            .search-toggle-form .btn-form-close {
                right: 15px;
            }
        }
        
        .search-toggle-form .btn-form-close:hover {
            background: var(--closeButtonHoverBackground);
            border: 1px solid var(--closeIconHoverColor);
            color: var(--closeIconHoverColor);
        }
        
        .search-toggle-form .btn-form-close:focus {
            outline: 2px dotted rgba(255, 255, 255, 0.6);
        }
        
        .search-toggle-form .btn-form-close:before, .search-toggle-form .btn-form-close:after {
            position: absolute;
            top: 5px;
            left: auto;
            content: "";
            width: 1px;
            height: 18px;
            border-radius: 0;
            background: currentColor;
            transition: width 240ms ease-in-out, transform 240ms ease-in-out;
        }
        
        .search-toggle-form .btn-form-close:before {
            transform: rotate(45deg);
        }
        
        .search-toggle-form .btn-form-close:after {
            transform: rotate(-45deg);
        }
        
        .search-toggle-form .btn-form-close:focus {
            opacity: 1;
        }
    ';

    return rishi_trim_css($output_css);
}

function rishi_header_mobile_menu_static_css($output_css) {
    $element_mobile_menu = rishi_customizer()->header_builder->get_elements()->get_items()['mobile-menu'];
    $_mobile_menuInstance = new $element_mobile_menu();
    $ed_mobile_menu_border = $_mobile_menuInstance->get_mod_value('mobile_menu_type', 'type-1');
    $output_css .= '
        .rishi-offcanvas-drawer .rishi-mobile-menu {
            width: 100%;
            margin: var(--margin);
        }
        
        .rishi-mobile-menu ul {
            margin: 0;
        }
        
        .rishi-mobile-menu li {
            display: flex;
            flex-direction: column;
            align-items: var(--horizontal-alignment);
        }
        
        .rishi-mobile-menu li a {
            display: inline-flex;
            align-items: center;
            position: relative;
            width: 100%;
        }
        
        .rishi-mobile-menu .menu-item-has-children>ul.sub-menu {
            width: 100%;
            overflow: hidden;
            transition: height .3s cubic-bezier(0.4, 0, 0.2, 1), opacity .3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .rishi-mobile-menu .menu-item-has-children:not(.submenu-active)>ul.sub-menu {
            display: none;
            opacity: 0;
        }
        
        .rishi-mobile-menu .menu-item-has-children .submenu-active>ul.sub-menu {
            opacity: 1;
            display: block;
        }
        
        .rishi-mobile-menu .menu-item-has-children>ul a,
        .rishi-mobile-menu .page_item_has_children>ul a {
            font-size: var(--fontSize);
        }
        
        .rishi-mobile-menu .submenu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-left: auto;
            padding-left: 20px;
            padding: 0 0 0 20px;
            border: none;
            background: transparent;
        }
        
        .rishi-mobile-menu .submenu-toggle svg {
            fill: var(--linkInitialColor);
            margin: 10px;
        }
        
        .rishi-mobile-menu li.current-menu-item>a .submenu-toggle svg {
            fill: var(--linkHoverColor);
        }
        
        .rishi-mobile-menu ul li a {
            color: var(--linkInitialColor);
            font-family: var(--fontFamily);
            font-weight: var(--fontWeight);
            font-size: var(--fontSize);
            line-height: var(--lineHeight);
            letter-spacing: var(--letterSpacing);
            text-decoration: var(--textDecoration);
            text-transform: var(--textTransform);
            padding: var(--padding, .5em 0);
            transition: 0.3s ease all;
        }
        
        .rishi-mobile-menu ul .menu-item-has-children>ul li a {
            font-size: var(--mobile_menu_child_size);
        }
    ';

    if ($ed_mobile_menu_border == 'type-1') {
        $output_css .= '
            .rishi-mobile-menu.menu-default ul li a:hover .submenu-toggle:before {
                opacity: 1;
            }
            
            .rishi-mobile-menu.menu-default ul .menu-item-has-children>ul,
            .rishi-mobile-menu.menu-default ul .page_item_has_children>ul {
                padding-left: 30px;
            }
            
            .rishi-mobile-menu.menu-default ul .menu-item-has-children>ul li a:before,
            .rishi-mobile-menu.menu-default ul .page_item_has_children>ul li a:before {
                position: absolute;
                content: "";
                left: -20px;
                width: 10px;
                height: 1px;
                background: currentColor;
            }
        ';
    }

    if ($ed_mobile_menu_border == 'type-2') {
        $output_css .= '
            .rishi-mobile-menu.menu-border ul:not(.sub-menu) {
                border-bottom: var(--mobile-menu-divider, 1px solid #fff);
            }
            
            .rishi-mobile-menu.menu-border ul li {
                border-top: var(--mobile-menu-divider, 1px solid #fff);
            }
            
            .rishi-mobile-menu[data-type=type-2] ul ul:last-child {
                border-bottom: none;
            }
            
            .rishi-mobile-menu.menu-border ul .menu-item-has-children ul,
            .rishi-mobile-menu.menu-border ul .page_item_has_children ul {
                padding-left: 30px;
            }
            
            .rishi-mobile-menu.menu-border ul .menu-item-has-children ul li a,
            .rishi-mobile-menu.menu-border ul .page_item_has_children ul li a {
                padding-left: 0;
            }
            
            .rishi-mobile-menu.menu-border ul .menu-item-has-children ul li a:before,
            .rishi-mobile-menu.menu-border ul .page_item_has_children ul li a:before {
                position: absolute;
                content: "";
                top: calc(50% - 4px);
                left: -15px;
                width: 6px;
                height: 8px;
                margin-right: 15px;
                opacity: 0.3;
                border: 1px solid currentColor;
                border-top: none;
                border-right: none;
                transition: opacity 240ms ease-in-out;
            }
            
            .rishi-mobile-menu.menu-border ul .menu-item-has-children ul li a:hover:before,
            .rishi-mobile-menu.menu-border ul .page_item_has_children ul li a:hover:before {
                opacity: 0.8;
            }

        ';
    }
    return rishi_trim_css($output_css);
}

function rishi_header_contacts_static_css($output_css) {
    $element_header_contacts = rishi_customizer()->header_builder->get_elements()->get_items()['contacts'];
    $_contactsInstance = new $element_header_contacts();
    $ed_contact_icon_shape = $_contactsInstance->get_mod_value('contacts_icon_shape', 'rounded');
    $ed_contact_icon_fill = $_contactsInstance->get_mod_value('contacts_icon_fill_type', 'solid');

    $output_css .= '
        .rishi-header-contact-info{
            margin: var(--margin);
        }

        .rishi-header-contact-info ul {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin: 0;
            gap: var(--items-spacing);
        }
        
        .rishi-header-contact-info ul li .contact-info {
            color: var(--color);
        }
        
        .rishi-header-contact-info ul li .contact-info span {
            display: block;
        }
        
        .rishi-header-contact-info ul li .contact-info a {
            color: inherit;
        }
        
        .rishi-header-contact-info ul li .contact-title {
            font-weight: 600;
        }
        
        .rishi-header-contact-info ul li:hover .contact-info a {
            color: var(--hover-color);
        }
        
        .rishi-header-contact-info ul.solid li .rishi-icon-container {
            background-color: var(--background-color);
        }
        
        .rishi-header-contact-info ul.solid li:hover .rishi-icon-container {
            background-color: var(--background-hover-color);
        }
        
        .rishi-header-contact-info ul.outline li .rishi-icon-container {
            border: 1px solid var(--background-color);
        }
        
        .rishi-header-contact-info ul.outline li:hover .rishi-icon-container {
            border: 1px solid var(--background-hover-color);
        }
        
        .rishi-header-contact-info li {
            display: grid;
            grid-template-columns: auto 1fr;
            grid-column-gap: 15px;
            align-items: center;
        }

        .rishi-header-contact-info li:hover .rishi-icon-container svg {
            fill: var(--icon-hover-color);
        }
    ';

    if ($ed_contact_icon_shape == 'rounded') {
        $output_css .= '
            .rishi-contacts-type-rounded {
                --border-radius: 100%;
            }
        ';
    }

    if ($ed_contact_icon_shape == 'square') {
        $output_css .= '
            .rishi-contacts-type-square {
                --border-radius: 2px;
            }
        ';
    }

    if ($ed_contact_icon_fill == 'solid') {
        $output_css .= '
            .rishi-contacts-fill-type-solid .rishi-icon-container {
                background-color: var(--background-color);
            }

            .rishi-contacts-fill-type-solid>*:hover .rishi-icon-container {
                background-color: var(--background-hover-color);
            }
        ';
    }

    if ($ed_contact_icon_fill == 'outline') {
        $output_css .= '
            .rishi-contacts-fill-type-outline .rishi-icon-container {
                border: 1px solid var(--background-color);
            }

            .rishi-contacts-fill-type-outline>*:hover .rishi-icon-container {
                border-color: var(--background-hover-color);
            }
        ';
    }

    return rishi_trim_css($output_css);
}

function rishi_header_socials_static_css($output_css) {

    $element_header_socials = rishi_customizer()->header_builder->get_elements()->get_items()['socials'];
    $_socialsInstance = new $element_header_socials();
    $ed_social_icon_shape = $_socialsInstance->get_mod_value('socialsType', 'simple');
    $ed_social_icon_fill = $_socialsInstance->get_mod_value('socialsFillType', 'solid');
    $ed_social_icon_color = $_socialsInstance->get_mod_value('headerSocialsColor', 'custom');

    $output_css .= '
        .rishi_header_socials {
            margin: var(--margin);
        }
        .rishi_social_box {
            display: flex;
            color: var(--icon-color);
            flex-wrap: wrap;
            gap: var(--spacing);
        }
        .rishi_social_box a {
            display: flex;
            align-items: center;
        }
        .rishi_social_box a:hover svg {
            fill: var(--icon-hover-color, var(--paletteColor2));
        }
    ';

    if ($ed_social_icon_shape == 'rounded') {
        $output_css .= '
            .rishi-socials-type-rounded {
                --border-radius: 100%;
            }
        ';
    }

    if ($ed_social_icon_shape == 'square') {
        $output_css .= '
            .rishi-socials-type-square {
                --border-radius: 2px;
            }
        ';
    }

    if ($ed_social_icon_fill == 'solid') {
        $output_css .= '
            .rishi-socials-fill-type-solid .rishi-icon-container {
                background-color: var(--background-color);
            }
            
            .rishi-socials-fill-type-solid>*:hover .rishi-icon-container {
                background-color: var(--background-hover-color);
            }
        ';
    }

    if ($ed_social_icon_fill == 'outline') {
        $output_css .= '
            .rishi-socials-fill-type-outline .rishi-icon-container {
                border: 1px solid var(--background-color);
            }
            
            .rishi-socials-fill-type-outline>*:hover .rishi-icon-container {
                border-color: var(--background-hover-color);
            }
        ';
    }

    if ($ed_social_icon_color == 'official') {
        $output_css .= '
            .site-header .rishi-color-type-official > * {
                --transition: opacity 240ms ease-in-out;
            }
            .site-header .rishi-color-type-official > *:hover {
                opacity: 0.8;
            }
            .site-header .rishi-color-type-official a {
                color: var(--official-color);
            }
            .site-header .rishi-color-type-official .rishi-icon-container {
                --icon-color: var(--official-color);
                --icon-hover-color: var(--official-color);
            }
        ';
    }

    if ($ed_social_icon_fill == 'solid' && $ed_social_icon_color == 'official') {
        $output_css .= '
            .rishi-color-type-official.rishi-socials-fill-type-solid .rishi-icon-container {
                --icon-color: #fff;
                --icon-hover-color: #fff;
                background-color: var(--official-color);
            }
        ';
    }

    if ($ed_social_icon_fill == 'outline' && $ed_social_icon_color == 'official') {
        $output_css .= '
            .rishi-color-type-official.rishi-socials-fill-type-outline .rishi-icon-container {
                border: 1px solid var(--official-color);
            }
        ';
    }

    return rishi_trim_css($output_css);
}

function rishi_header_image_static_css($output_css) {
    $output_css .= '
        .header-image-section .image-wrapper figure {
            margin: 0;
        }
        
        .header-image-section .image-wrapper figure img {
            display: block;
            max-width: var(--max-width);
        }
    ';
    return rishi_trim_css($output_css);
}

function rishi_header_text_static_css($output_css) {
    $output_css .= '
        .rishi_header_text {
            display: flex;
            align-items: center;
            margin: var(--margin);
            color: var(--color);
            max-width: 250px;
            width: auto;
        }
        
        .rishi_header_text .html-content {
            width: 100%;
        }
        
        .rishi_header_text .html-content p {
            margin-bottom: 10px;
        }
        
        .rishi_header_text .html-content p:last-child {
            margin-bottom: 0;
        }
    ';
    return rishi_trim_css($output_css);
}

function rishi_header_button_static_css($output_css) {
    $output_css .= '
        .rishi-header-cta {
            margin: var(--margin);
        }
        
        .rishi-header-cta .rishi-button {
            border: var(--headerButtonBorder);
            border-radius: var(--buttonBorderRadius);
            cursor: pointer;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: var(--buttonMinHeight);
            min-width: var(--buttonMinWidth);
            padding: var(--headerCtaPadding);
            transition: all 0.2s ease-in-out;
        }
        
        .rishi-header-cta .rishi-button:hover {
            border: var(--headerButtonBorder_hover);
        }
        
        .rishi-header-cta .rishi-button.btn-outline {
            border-color: var(--headerButtonBorderColor);
            background: transparent;
        }
        
        .rishi-header-cta .rishi-button.btn-outline:hover {
            border-color: var(--headerButtonBorderHoverColor);
            background-color: var(--buttonHoverColor);
        }
        
        .rishi-header-cta .btn-small {
            --buttonMinHeight: 40px;
            --buttonFontSize: 0.89em;
        }
        
        .rishi-header-cta .btn-medium {
            --buttonMinHeight: 50px;
            --buttonFontSize: 1em;
        }
        
        .rishi-header-cta .btn-large {
            --buttonMinHeight: 60px;
            --buttonFontSize: 1.15em;
        }
    ';
    return rishi_trim_css($output_css);
}

function rishi_header_menu_static_css($output_css) {
    $output_css .= '
        .site-header .rishi-menu > .menu-item-has-children > .sub-menu {
            top: 100%;
            margin-top: var(--dropdown-top-offset, 0);
        }

        .site-header .rishi-menu > .menu-item-has-children > .sub-menu a {
            padding: calc(var(--dropdown-items-spacing) / 2) 15px;
        }

        .site-header .menu-item-has-children {
            position: relative;
        }

        .site-header .menu-item-has-children .sub-menu {
            position: absolute;
            border-radius: var(--border-radius);
            list-style: none;
            width: var(--dropdown-width, 200px);
            background-color: var(--background-color);
            box-shadow: var(--box-shadow);
            opacity: 0;
            transition: opacity 0.2s linear, transform 0.2s linear, visibility 0.2s linear;
            padding: 0;
            z-index: 1;
            visibility: hidden;
        }

        .keyboard-nav-on .site-header .menu-item-has-children .sub-menu {
            visibility: visible;
        }
        
        .site-header .menu-item-has-children .sub-menu .sub-menu {
            left: 100%;
            margin: 0 5px;
            top: 0;
        }
        
        .site-header .menu-item-has-children .sub-menu.right {
            right: 0;
        }
        
        .site-header .menu-item-has-children .sub-menu.right .sub-menu {
            left: auto;
            right: 100%;
        }
        
        .site-header .menu-item-has-children .sub-menu.right li.menu-item > a {
            flex-direction: row-reverse;
        }
        
        .site-header .menu-item-has-children .sub-menu.right li.menu-item > a .submenu-toggle {
            transform: rotate(90deg);
        }
        
        .site-header .menu-item-has-children .sub-menu:before {
            position: absolute;
            content: "";
            top: 0;
            left: 0;
            width: 100%;
            height: var(--dropdown-top-offset, 0);
            transform: translateY(-100%);
        }
        
        .site-header .menu-item-has-children .sub-menu li {
            border-top: var(--dropdown-divider);
        }
        
        .site-header .menu-item-has-children .sub-menu li:first-child {
            border-top: none;
        }
        
        .site-header .menu-item-has-children .sub-menu li a {
            justify-content: space-between;
            
        }
        
        .site-header .menu-item-has-children .sub-menu li a .submenu-toggle {
            transform: rotate(270deg);
        }

        [class*="rishi-menu-layout"] > ul > li {
            display: flex;
            align-items: center;
        }

        :is(.rishi-menu-layout-type-2, .rishi-menu-layout-type-4, .rishi-menu-layout-type-7) > ul > li > a {
            height: var(--menu-item-height);
            background-color: transparent;
            background-image: linear-gradient(var(--currentMenuLinkAccent, var(--paletteColor2)), var(--currentMenuLinkAccent, var(--paletteColor2)));
            background-size: 0 2px;
            background-repeat: no-repeat;
            background-position: left bottom;
        }
        
        :is(.rishi-menu-layout-type-2, .rishi-menu-layout-type-4, .rishi-menu-layout-type-7) > ul > li:hover a, :is(.rishi-menu-layout-type-2, .rishi-menu-layout-type-4, .rishi-menu-layout-type-7) > ul > li.current-menu-item a {
            background-size: 100% 2px;
        }

        .rishi-menu-layout-type-4 > ul > li:hover > a, 
        .rishi-menu-layout-type-4 > ul > li.current-menu-item > a {
            background-color: var(--currentMenuLinkBg, var(--paletteColor2));
        }

        .rishi-menu-layout-type-3 > ul > li:hover > a, 
        .rishi-menu-layout-type-3 > ul > li.current-menu-item > a {
            background-color: var(--currentMenuLinkAccent, var(--paletteColor2));
        }

        .rishi-menu-layout-type-5 > ul > li > a:after {
            content: "";
            height: 5px;
            width: 5px;
            bottom: 10%;
        }

        :is(.rishi-menu-layout-type-5, .rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li > a::after, 
        :is(.rishi-menu-layout-type-5, .rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li > a::before {
            position: absolute;
            border-radius: 100%;
            aspect-ratio: 1/1;
            opacity: 0;
            background-color: var(--currentMenuLinkAccent, var(--paletteColor2));
            transition: opacity 0.25s ease-in-out;
        }

        :is(.rishi-menu-layout-type-5, .rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li:hover > a::after,
        :is(.rishi-menu-layout-type-5, .rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li:hover > a::before, :is(.rishi-menu-layout-type-5, .rishi-menu-layout-type-6, .rishi-menu-layout-type-8)> ul > li.current-menu-item > a::after,
        :is(.rishi-menu-layout-type-5, .rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li.current-menu-item > a::before {
            opacity: 1;
        }

        :is(.rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li > a::after, :is(.rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li > a::before {
            content: "";
            top: 50%;
            transform: translateY(-50%);
        }
        
        :is(.rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li > a::after {
            left: 0;
        }
        
        :is(.rishi-menu-layout-type-6, .rishi-menu-layout-type-8) > ul > li > a::before {
            right: 0;
        }

        .rishi-menu-layout-type-6 > ul > li > a::after, .rishi-menu-layout-type-6 > ul > li > a::before {
            height: 5px;
            width: 5px;
        }

        .rishi-menu-layout-type-8 > ul > li > a::after, .rishi-menu-layout-type-8 > ul > li > a::before {
            height: 2px;
            width: 5px;
        }

        .rishi-menu-layout-type-7 > ul > li > a {
            background-position: left 52%;
        }

        .rishi-slide-down .menu-item-has-children>.sub-menu {
            transform: translateY(-20px);
        }
        
        .rishi-slide-up .menu-item-has-children>.sub-menu {
            transform: translateY(20px);
        }

        :is(.rishi-slide-down, .rishi-slide-up) .menu-item-has-children:hover>.sub-menu,
        :is(.rishi-slide-down, .rishi-slide-up) .menu-item-has-children:focus-within>.sub-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .rishi-fade-in .menu-item-has-children:hover>.sub-menu,
        .rishi-fade-in .menu-item-has-children:focus-within>.sub-menu {
            opacity: 1;
            visibility: visible;
        }

        :is(.rishi-menu-layout-type-2, .rishi-menu-layout-type-3, .rishi-menu-layout-type-4, .rishi-menu-layout-type-6, .rishi-menu-layout-type-8) li>a {
            padding-left: 15px;
            padding-right: 15px
        }
    ';

    return rishi_trim_css($output_css);
}
