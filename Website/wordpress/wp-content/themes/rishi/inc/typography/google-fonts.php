<?php
/**
 * Google Fonts
 *  
 * Return an array of all Google Fonts.
*/
use Rishi\Customizer\Helpers\Defaults;
function rishi_get_all_google_fonts_from_json(){

  $google_fonts = apply_filters( 'rishi_google_lists', get_template_directory() . '/inc/typography/google-fonts-lists.php' );

  $google_fonts_list = include $google_fonts;
  
  // Loop through them and put what we need into our fonts array
  $fonts = array();

  foreach ( $google_fonts_list as $item ) {

    // Grab what we need from our big list
    $atts = array(
      'name'     => $item['family'],
      'category' => $item['category'],
      'variants' => $item['variants'],
    );

    // Create an ID using our font family name
    $id = str_replace( ' ', '_',$item['family'] );

    // Add our attributes to our new array
    $fonts[ $id ] = $atts;    
  }

  // Alphabetize our fonts
  if ( apply_filters( 'rishi_alphabetize_google_fonts', true ) ) {
    asort( $fonts );
  }

  // Filter to allow us to modify the fonts array
  return apply_filters( 'rishi_google_fonts_array', $fonts );

}

if ( ! function_exists( 'rishi_load_google_fonts' ) ) :
	/**
	 * Google Fonts url
	 */
	function rishi_load_google_fonts() {
		$helpers                = new Defaults();
		$fonts_url              = '';
		$local_google_fonts     = get_theme_mod( 'local_google_fonts', 'no' );
		$customizer_settings    = $helpers->get_customizer_typography_value();
		$header_settings        = $helpers->get_header_customizer_typography_value();
		$footer_settings        = $helpers->get_footer_customizer_typography_value();
		$font_settings          = $helpers->get_rishi_fonts_key();
		$header_footer_settings = array_merge( $header_settings, $footer_settings );
		$settings               = array_merge( $customizer_settings, $header_footer_settings );
		$not_google             = str_replace( ' ', '+', $helpers->rishi_typography_default_fonts() );
		$google_fonts           = array();
		$fontWeightFormat = array(
			'thin' => '100',
			'extra_light' => '200',
			'light' => '300',
			'regular' => '400',
			'medium' => '500',
			'semibold' => '600',
			'bold' => '700',
			'extra_bold' => '800',
			'ultra_bold' => '900',
		);
		foreach ( $font_settings as $key ) {
			$value = '';

			if ( isset( $settings[ $key ]['family'] ) ) {
				$value = str_replace( ' ', '+', $settings[ $key ]['family'] );
			}

			if ( isset( $settings[ $key ]['family'] ) && ! in_array( $value, $not_google ) ) {

				$weight = isset( $settings[ $key ]['weight'] ) ? $settings[ $key ]['weight'] : '';
				$style  = isset( $settings[ $key ]['style'] ) ? $settings[ $key ]['style'] : '';
				// Check if the weight is mapped in the fontWeightFormat array
				if (isset($fontWeightFormat[$weight])) {
					$weight = $fontWeightFormat[$weight];
				}

				$value = ! empty( $weight ) ? $value . ':' . $weight : $value;
				$value = ! empty( $style ) && $style == 'italic' ? $value . $style : $value;
			}

			// Make sure we don't add the same font twice.
			if ( ! in_array( $value, $google_fonts ) ) {
				$google_fonts[] = $value;
			}
		}
		// Ignore any non-Google fonts.
		$google_fonts = array_diff( $google_fonts, $not_google );
		$google_fonts = array_filter( $google_fonts ); // Remove empty values

		$google_fonts = implode( '|', $google_fonts );
		$google_fonts = apply_filters( 'rishi_typography_google_fonts', $google_fonts );

		$font_args           = array();
		$font_args['family'] = $google_fonts;

		$display = apply_filters( 'rishi_google_font_display', 'swap' );

		if ( $display ) {
			$font_args['display'] = $display;
		}

		if ( $google_fonts ) {
			$fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );
		}

		if ( $google_fonts && $local_google_fonts == 'yes' ) {
			$fonts_url = rishi_get_webfont_url( add_query_arg( $font_args, 'https://fonts.googleapis.com/css' ) );
		}

		return esc_url( $fonts_url );
	}
endif;