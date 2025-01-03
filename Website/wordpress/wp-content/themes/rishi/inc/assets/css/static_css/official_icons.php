<?php 
/**
 * Official Icons - Dynamic CSS
 *
 * @package Rishi
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'rishi_dynamic_theme_css', 'rishi_official_icons_static_css', 11 );

/**
 * Official Icons - CSS
 *
 * @param  String $output_css.
 * @return String CSS for Official Icons.
 *
 * @since 1.0.0
 */
function rishi_official_icons_static_css( $output_css ){

    $output_css .= '
    .rishi-color-type-official .rishi-facebook {
        --official-color: #557dbc;
    }
    
    .rishi-color-type-official .rishi-twitter {
        --official-color: #7acdee;
    }
    
    .rishi-color-type-official .rishi-instagram {
        --official-color: #292929;
    }
    
    .rishi-color-type-official .rishi-pinterest {
        --official-color: #ea575a;
    }
    
    .rishi-color-type-official .rishi-dribbble {
        --official-color: #d77ea6;
    }
    
    .rishi-color-type-official .rishi-behance {
        --official-color: #1b64f6;
    }
    
    .rishi-color-type-official .rishi-linkedin {
        --official-color: #1c86c6;
    }
    
    .rishi-color-type-official .rishi-wordpress {
        --official-color: #1074a8;
    }
    
    .rishi-color-type-official .rishi-parler {
        --official-color: #bc2131;
    }
    
    .rishi-color-type-official .rishi-medium {
        --official-color: #292929;
    }
    
    .rishi-color-type-official .rishi-slack {
        --official-color: #4e1850;
    }
    
    .rishi-color-type-official .rishi-facebook_group {
        --official-color: #3d87fb;
    }
    
    .rishi-color-type-official .rishi-discord {
        --official-color: #7187d4;
    }
    
    .rishi-color-type-official .rishi-tripadvisor {
        --official-color: #40dfa3;
    }
    
    .rishi-color-type-official .rishi-foursquare {
        --official-color: #f84a7a;
    }
    
    .rishi-color-type-official .rishi-yelp {
        --official-color: #ca252a;
    }
    
    .rishi-color-type-official .rishi-unsplash {
        --official-color: #000000;
    }
    
    .rishi-color-type-official .rishi-five-hundred-px {
        --official-color: #000000;
    }
    
    .rishi-color-type-official .rishi-codepen {
        --official-color: #000000;
    }
    
    .rishi-color-type-official .rishi-reddit {
        --official-color: #fc471e;
    }
    
    .rishi-color-type-official .rishi-twitch {
        --official-color: #9150fb;
    }
    
    .rishi-color-type-official .rishi-tiktok {
        --official-color: #000000;
    }
    
    .rishi-color-type-official .rishi-snapchat {
        --official-color: #f9d821;
    }
    
    .rishi-color-type-official .rishi-spotify {
        --official-color: #2ab859;
    }
    
    .rishi-color-type-official .rishi-soundcloud {
        --official-color: #fd561f;
    }
    
    .rishi-color-type-official .rishi-apple_podcast {
        --official-color: #933ac3;
    }
    
    .rishi-color-type-official .rishi-patreon {
        --official-color: #e65c4b;
    }
    
    .rishi-color-type-official .rishi-alignable {
        --official-color: #4a396f;
    }
    
    .rishi-color-type-official .rishi-vk {
        --official-color: #5382b6;
    }
    
    .rishi-color-type-official .rishi-youtube {
        --official-color: #e96651;
    }
    
    .rishi-color-type-official .rishi-dtube {
        --official-color: #233253;
    }
    
    .rishi-color-type-official .rishi-vimeo {
        --official-color: #8ecfde;
    }
    
    .rishi-color-type-official .rishi-rss {
        --official-color: #f09124;
    }
    
    .rishi-color-type-official .rishi-whatsapp {
        --official-color: #5bba67;
    }
    
    .rishi-color-type-official .rishi-viber {
        --official-color: #7f509e;
    }
    
    .rishi-color-type-official .rishi-telegram {
        --official-color: #229cce;
    }
    
    .rishi-color-type-official .rishi-xing {
        --official-color: #0a5c5d;
    }
    
    .rishi-color-type-official .rishi-weibo {
        --official-color: #e41c34;
    }
    
    .rishi-color-type-official .rishi-tumblr {
        --official-color: #314255;
    }
    
    .rishi-color-type-official .rishi-qq {
        --official-color: #487fc8;
    }
    
    .rishi-color-type-official .rishi-wechat {
        --official-color: #2dc121;
    }
    
    .rishi-color-type-official .rishi-strava {
        --official-color: #2dc121;
    }
    
    .rishi-color-type-official .rishi-flickr {
        --official-color: #0f64d1;
    }
    
    .rishi-color-type-official .rishi-phone {
        --official-color: #244371;
    }
    
    .rishi-color-type-official .rishi-email {
        --official-color: #392c44;
    }
    
    .rishi-color-type-official .rishi-github {
        --official-color: #24292e;
    }
    
    .rishi-color-type-official .rishi-gitlab {
        --official-color: #f8713f;
    }
    
    .rishi-color-type-official .rishi-skype {
        --official-color: #1caae7;
    }
    
    .rishi-color-type-official .rishi-getpocket {
        --official-color: #ef4056;
    }
    
    .rishi-color-type-official .rishi-evernote {
        --official-color: #5ba525;
    }
    
    .rishi-color-type-official .rishi-hacker_news {
        --official-color: #ff6600;
    }
    
    .rishi-color-type-official .rishi-threema {
        --official-color: #323232;
    }
    
    .rishi-color-type-official .rishi-ok {
        --official-color: #F96800;
    }
    
    .rishi-color-type-official .rishi-xing {
        --official-color: #006064;
    }
    
    .rishi-color-type-official .rishi-flipboard {
        --official-color: #CC0000;
    }
    
    .rishi-color-type-official .rishi-line {
        --official-color: #00C300;
    }
    
    .rishi-color-type-official .rishi-label {
        color: var(--official-color);
    }';

    return rishi_trim_css( $output_css );
}


