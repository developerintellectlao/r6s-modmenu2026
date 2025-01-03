<?php 
/**
 * Footer Elements
 *
 * @package Rishi
 *
 * @since 1.2.3
 */
use Rishi\Customizer\Helpers\Basic;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $pagenow;

if(is_admin() && !is_customize_preview()){
    return; //Solve image uploader issue in various posttypes
}

$item_array = [
    'copyright',
	'socials',
	'contacts'
];

$get_active_items = Basic::get_footer_active_elements();

/**
 * Enqueue CSS for Customizer Screen Only
 */
foreach ($item_array as $items) {
    if (is_customize_preview() && !in_array($items, $get_active_items)) { //Only move ahead if item does not exist in database
        if (!function_exists("rishi_footer_{$items}_static_css")) continue;
        add_filter('rishi_dynamic_customizer_css', "rishi_footer_{$items}_static_css", 9);
    }
}

foreach( $get_active_items as $items) {
	if( in_array($items, $item_array)) {

		if( !function_exists( "rishi_footer_{$items}_static_css" ) ) continue;
		add_filter( 'rishi_dynamic_theme_css', "rishi_footer_{$items}_static_css", 11 );
	}
}

/**
 * Footer Elements - CSS
 *
 * @param  string $output_css.
 * @return String CSS for Footer Elements.
 *
 * @since 1.2.3
 */
function rishi_footer_copyright_static_css( $output_css ){
    $output_css .='
    #rishi-copyrights {
        display: flex;
        height: 100%;
      }
      #rishi-copyrights .rishi-footer-copyrights {
        font-family: var(--fontFamily);
        font-size: var(--fontSize);
        line-height: var(--lineHeight);
        font-weight: var(--fontWeight);
        color: var(--color);
        margin: var(--margin);
      }
      #rishi-copyrights .rishi-footer-copyrights p {
        margin-bottom: 0;
      }
      #rishi-copyrights .rishi-footer-copyrights a {
        color: var(--linkInitialColor);
        transition: 0.3s ease all;
      }
      #rishi-copyrights .rishi-footer-copyrights a:hover {
        color: var(--linkHoverColor);
      }
      #rishi-copyrights .rishi-footer-copyrights > *:not(:first-child) {
        margin-top: 0.5em;
      }';

    return rishi_trim_css( $output_css );
}

function rishi_footer_contacts_static_css( $output_css ) {
    $element_footer_contacts = rishi_customizer()->footer_builder->get_elements()->get_items()['contacts'];
    $_contactsInstance = new $element_footer_contacts();
    $ed_contact_icon_shape = $_contactsInstance->get_mod_value( 'contacts_icon_shape', 'rounded' );
    $ed_contact_icon_fill = $_contactsInstance->get_mod_value( 'contacts_icon_fill_type', 'solid' );
  
    $output_css .='
        .rishi-footer-contact-info{
            margin: var(--margin);
        }

      .rishi-footer-contact-info ul {
          display: flex;
          flex-wrap: wrap;
          margin: 0;
          gap: var(--items-spacing);
          flex-direction:column;
      }
      
      .rishi-footer-contact-info ul li .contact-info {
          color: var(--color);
      }
      
      .rishi-footer-contact-info ul li .contact-info span {
          display: block;
      }
      
      .rishi-footer-contact-info ul li .contact-info a {
          color: inherit;
      }
      
      .rishi-footer-contact-info ul li .contact-title {
          font-weight: 600;
      }
      
      .rishi-footer-contact-info ul li:hover .contact-info a {
          color: var(--hover-color);
      }
      
      .rishi-footer-contact-info ul.solid li .rishi-icon-container {
          background-color: var(--background-color);
      }
      
      .rishi-footer-contact-info ul.solid li:hover .rishi-icon-container {
          background-color: var(--background-hover-color);
      }
      
      .rishi-footer-contact-info ul.outline li .rishi-icon-container {
          border: 1px solid var(--background-color);
      }
      
      .rishi-footer-contact-info ul.outline li:hover .rishi-icon-container {
          border: 1px solid var(--background-hover-color);
      }
      
      .rishi-footer-contact-info li {
          display: grid;
          grid-template-columns: auto 1fr;
          grid-column-gap: 15px;
          align-items: center;
      }
      
      .rishi_footer .rishi-footer-contact-info {
          margin: var(--margin);
      }
      
      .rishi_footer .rishi-footer-contact-info ul {
          flex-direction: column;
          align-items: flex-start;
      }
      
      .rishi_footer .rishi-footer-contact-info ul li {
          display: grid;
          grid-template-columns: auto 1fr;
          grid-column-gap: 15px;
          align-items: center;
      }

      .rishi-footer-contact-info li:hover .rishi-icon-container svg {
        fill: var(--icon-hover-color);
    }
  ';

    if( $ed_contact_icon_shape == 'rounded' ) {
        $output_css .='
            .rishi-contacts-type-rounded {
                --border-radius: 100%;
            }
        ';
    }

    if( $ed_contact_icon_shape == 'square' ) {
        $output_css .='
            .rishi-contacts-type-square {
                --border-radius: 2px;
            }
        ';
    }

    if( $ed_contact_icon_fill == 'solid' ) {
        $output_css .='
            .rishi-contacts-fill-type-solid .rishi-icon-container {
                background-color: var(--background-color);
            }

            .rishi-contacts-fill-type-solid>*:hover .rishi-icon-container {
                background-color: var(--background-hover-color);
            }
        ';
    }

    if( $ed_contact_icon_fill == 'outline' ) {
        $output_css .='
            .rishi-contacts-fill-type-outline .rishi-icon-container {
                border: 1px solid var(--background-color);
            }

            .rishi-contacts-fill-type-outline>*:hover .rishi-icon-container {
                border-color: var(--background-hover-color);
            }
        ';
    }

  return rishi_trim_css( $output_css );
}


function rishi_footer_socials_static_css( $output_css ){
  $element_footer_socials = rishi_customizer()->footer_builder->get_elements()->get_items()['socials'];
  $_socialsInstance = new $element_footer_socials();
  $ed_social_icon_shape = $_socialsInstance->get_mod_value( 'socialsType', 'simple' );
  $ed_social_icon_fill = $_socialsInstance->get_mod_value( 'socialsFillType', 'solid' );
  $ed_social_icon_color = $_socialsInstance->get_mod_value( 'footerSocialsColor', 'custom' );

  $output_css .='
    .rishi_footer_socials{
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

  if( $ed_social_icon_shape == 'rounded' ) {
      $output_css .='
          .rishi-socials-type-rounded {
              --border-radius: 100%;
          }
      ';
  }

  if( $ed_social_icon_shape == 'square' ) {
      $output_css .='
          .rishi-socials-type-square {
              --border-radius: 2px;
          }
      ';
  }

  if( $ed_social_icon_fill == 'solid' ) {
      $output_css .='
          .rishi-socials-fill-type-solid .rishi-icon-container {
              background-color: var(--background-color);
          }
          
          .rishi-socials-fill-type-solid>*:hover .rishi-icon-container {
              background-color: var(--background-hover-color);
          }
      ';
  }

  if( $ed_social_icon_fill == 'outline' ) {
      $output_css .='
          .rishi-socials-fill-type-outline .rishi-icon-container {
              border: 1px solid var(--background-color);
          }
          
          .rishi-socials-fill-type-outline>*:hover .rishi-icon-container {
              border-color: var(--background-hover-color);
          }
      ';
  }

  if( $ed_social_icon_color == 'official' ) {
      $output_css .='
          .rishi-color-type-official > * {
              --transition: opacity 240ms ease-in-out;
          }
          .rishi-color-type-official > *:hover {
              opacity: 0.8;
          }
          .rishi-color-type-official a {
              color: var(--official-color);
          }
          .rishi-color-type-official .rishi-icon-container {
              --icon-color: var(--official-color);
              --icon-hover-color: var(--official-color);
          }
      ';
  }

  if( $ed_social_icon_fill == 'solid' && $ed_social_icon_color == 'official' ) {
      $output_css .='
          .rishi-color-type-official.rishi-socials-fill-type-solid .rishi-icon-container {
              --icon-color: #fff;
              --icon-hover-color: #fff;
              background-color: var(--official-color);
          }
      ';
  }

  if( $ed_social_icon_fill == 'outline' && $ed_social_icon_color == 'official' ) {
      $output_css .='
          .rishi-color-type-official.rishi-socials-fill-type-outline .rishi-icon-container {
              border: 1px solid var(--official-color);
          }
      ';
  }

  return rishi_trim_css( $output_css );
}
