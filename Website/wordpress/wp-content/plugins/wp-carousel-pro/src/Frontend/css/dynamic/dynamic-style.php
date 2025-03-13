<?php
/**
 * The style file for the WP Carousel pro.
 *
 * @package WP Carousel Pro
 */

/**
 * Carousel section title styles.
 */
$carousel_type        = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
$section_title        = isset( $css_data['section_title'] ) ? $css_data['section_title'] : true;
$layout               = isset( $css_data['wpcp_layout'] ) ? $css_data['wpcp_layout'] : 'carousel';
$section_background   = isset( $css_data['wpcp_section_background'] ) ? $css_data['wpcp_section_background'] : 'rgb(159, 160, 81)';
$slider_effect_height = ! empty( $css_data['slider_height']['top'] ) ? $css_data['slider_height'] : array(
	'top'    => 600,
	'right'  => 500,
	'bottom' => 400,
	'left'   => 300,
);

$section_title_dynamic_css = '';

/**
 * Carousel section title styles.
 */
$section_title_typography    = isset( $css_data['wpcp_section_title_typography'] ) ? $css_data['wpcp_section_title_typography'] : '';
$section_title_margin_bottom = isset( $section_title_typography['margin-bottom'] ) ? $section_title_typography['margin-bottom'] : '30';
if ( $section_title ) {
	$section_title_font_load  = isset( $css_data['section_title_font_load'] ) ? $css_data['section_title_font_load'] : '';
	$section_title_margin_top = isset( $section_title_typography['margin-top'] ) ? $section_title_typography['margin-top'] : '0';

	$section_title_typography_color      = isset( $section_title_typography['color'] ) ? $section_title_typography['color'] : '#444';
	$section_title_typography_size       = isset( $section_title_typography['font-size'] ) ? $section_title_typography['font-size'] : '24';
	$section_title_typography_height     = isset( $section_title_typography['line-height'] ) ? $section_title_typography['line-height'] : '28';
	$section_title_typography_spacing    = isset( $section_title_typography['letter-spacing'] ) ? $section_title_typography['letter-spacing'] : '0';
	$section_title_typography_transform  = isset( $section_title_typography['text-transform'] ) && ! empty( $section_title_typography['text-transform'] ) ? $section_title_typography['text-transform'] : 'none';
	$section_title_typography_alignment  = isset( $section_title_typography['text-align'] ) ? $section_title_typography['text-align'] : 'center';
	$section_title_typography_family     = isset( $section_title_typography['font-family'] ) ? $section_title_typography['font-family'] : '';
	$section_title_typography_font_style = isset( $section_title_typography['font-style'] ) && ! empty( $section_title_typography['font-style'] ) ? $section_title_typography['font-style'] : 'normal';
	$dynamic_css                        .= '
	#poststuff .wpcp-wrapper-' . $carousel_id . ' .sp-wpcpro-section-title,
	.post-type-sp_wp_carousel .wpcp-wrapper-' . $carousel_id . ' .sp-wpcpro-section-title,.wpcp-wrapper-' . $carousel_id . ' .sp-wpcpro-section-title {
			margin :' . $section_title_margin_top . 'px 0px ' . $section_title_margin_bottom . 'px 0px;
			color: ' . $section_title_typography_color . ';
			font-size: ' . $section_title_typography_size . 'px;
			line-height: ' . $section_title_typography_height . 'px;
			letter-spacing: ' . $section_title_typography_spacing . 'px;
			text-transform: ' . $section_title_typography_transform . ';
			text-align: ' . $section_title_typography_alignment . ';';
	if ( $section_title_font_load || ! empty( $section_title_typography_family ) ) {
		$wpcpro_typography[] = $section_title_typography;
		$dynamic_css        .= '
			font-family: ' . $section_title_typography_family . ';
			font-weight: ' . ( isset( $section_title_typography['font-weight'] ) && is_numeric( $section_title_typography['font-weight'] ) ? $section_title_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . $section_title_typography_font_style . ';
		';
	}
	$dynamic_css .= '}';
}

// else {
// $dynamic_css .= '
// .wpcp-wrapper-' . $carousel_id . ' .wpcp-carousel-section.nav-vertical-on-hover .wpcp-swiper-dots ~ .wpcp-next-button,
// .wpcp-wrapper-' . $carousel_id . ' .wpcp-carousel-section.nav-vertical-on-hover .wpcp-swiper-dots ~ .wpcp-prev-button,
// .wpcp-wrapper-' . $carousel_id . ' .wpcp-carousel-section.nav-vertical-center-inner .wpcp-swiper-dots ~ .wpcp-next-button,
// .wpcp-wrapper-' . $carousel_id . ' .wpcp-carousel-section.nav-vertical-center-inner .wpcp-swiper-dots ~ .wpcp-prev-button,
// .wpcp-wrapper-' . $carousel_id . ' .wpcp-carousel-section.nav-vertical-center .wpcp-swiper-dots ~ .wpcp-next-button,
// .wpcp-wrapper-' . $carousel_id . ' .wpcp-carousel-section.nav-vertical-center:not(.wpcp_swiper_vertical) .wpcp-swiper-dots ~ .wpcp-prev-button {
// margin-top: -40px;
// }';
// }

$autoplay_speed_old = isset( $css_data['carousel_auto_play_speed']['all'] ) && ! empty( $css_data['carousel_auto_play_speed']['all'] ) ? $css_data['carousel_auto_play_speed']['all'] : '3000';
$autoplay_speed     = isset( $css_data['carousel_auto_play_speed'] ) && is_string( $css_data['carousel_auto_play_speed'] ) ? $css_data['carousel_auto_play_speed'] : $autoplay_speed_old;

$carousel_orientation = isset( $css_data['wpcp_carousel_orientation'] ) ? $css_data['wpcp_carousel_orientation'] : 'horizontal';
/**
 * Image Zoom
 */
$image_zoom = isset( $css_data['wpcp_image_zoom'] ) ? $css_data['wpcp_image_zoom'] : '';

// Image border.
$image_border             = isset( $css_data['wpcp_product_image_border'] ) ? $css_data['wpcp_product_image_border'] : '';
$image_border_width       = isset( $image_border['all'] ) ? $image_border['all'] : '0';
$image_border_style       = isset( $image_border['style'] ) ? $image_border['style'] : 'solid';
$image_border_color       = isset( $image_border['color'] ) ? $image_border['color'] : '#dddddd';
$image_border_hover_color = isset( $image_border['hover-color'] ) ? $image_border['hover-color'] : '#dddddd';
$lazy_load_image          = isset( $css_data['wpcp_image_lazy_load'] ) ? $css_data['wpcp_image_lazy_load'] : 'false';

/**
 * Border radius.
 */
$radius_dimension = '0';
$radius_unit      = 'px';
if ( isset( $css_data['wpcp_image_border_radius'] ) ) {
	$radius_dimension = isset( $css_data['wpcp_image_border_radius']['all'] ) ? $css_data['wpcp_image_border_radius']['all'] : '0';
	$radius_unit      = isset( $css_data['wpcp_image_border_radius']['style'] ) ? $css_data['wpcp_image_border_radius']['style'] : 'px';
}
$wpcp_slide_background        = isset( $css_data['wpcp_slide_background'] ) ? $css_data['wpcp_slide_background'] : 'transparent';
$hide_nav_bg_border           = isset( $css_data['wpcp_hide_nav_bg_border'] ) ? $css_data['wpcp_hide_nav_bg_border'] : '';
$slide_border                 = isset( $css_data['wpcp_slide_border'] ) ? $css_data['wpcp_slide_border'] : '';
$slide_border_width           = isset( $slide_border['all'] ) ? $slide_border['all'] : '1';
$slide_border_style           = isset( $slide_border['style'] ) ? $slide_border['style'] : 'solid';
$slide_border_color           = isset( $slide_border['color'] ) ? $slide_border['color'] : '#dddddd';
$slide_border_radius          = isset( $slide_border['radius'] ) ? $slide_border['radius'] : '0';
$slide_margin                 = isset( $css_data['wpcp_slide_margin']['all'] ) && is_numeric( $css_data['wpcp_slide_margin']['all'] ) ? $css_data['wpcp_slide_margin']['all'] : 20;
$thumbnails_height_type       = isset( $css_data['thumbnails_height_type'] ) ? $css_data['thumbnails_height_type'] : 'static';
$wpcp_img_width_auto          = isset( $css_data['wpcp_img_width_auto'] ) ? $css_data['wpcp_img_width_auto'] : false;
$thumb_height                 = isset( $css_data['thumbHeight']['all'] ) ? $css_data['thumbHeight']['all'] : '150';
$thumb_unit                   = isset( $css_data['thumbHeight']['unit'] ) ? $css_data['thumbHeight']['unit'] : 'px';
$slide_margin_horizontal      = isset( $css_data['wpcp_slide_margin']['right'] ) && is_numeric( $css_data['wpcp_slide_margin']['right'] ) ? $css_data['wpcp_slide_margin']['right'] : $slide_margin;
$slide_margin_vertical        = isset( $css_data['wpcp_slide_margin']['top'] ) && is_numeric( $css_data['wpcp_slide_margin']['top'] ) ? $css_data['wpcp_slide_margin']['top'] : $slide_margin;
$slide_margin_half            = $slide_margin_vertical / 2;
$slide_horizontal_margin_half = (int) $slide_margin_horizontal / 2;

// Box Shadow.
$show_box_shadow        = isset( $css_data['wpcp_show_box_shadow'] ) ? $css_data['wpcp_show_box_shadow'] : false;
$show_box_shadow        = isset( $css_data['wpcp_box_shadow_style'] ) ? $css_data['wpcp_box_shadow_style'] : 'none';
$box_shadow             = ( isset( $css_data['wpcp_box_shadow'] ) && $show_box_shadow ) ? $css_data['wpcp_box_shadow'] : array();
$box_shadow_horizontal  = isset( $box_shadow['horizontal'] ) ? $box_shadow['horizontal'] : '0';
$box_shadow_vertical    = isset( $box_shadow['vertical'] ) ? $box_shadow['vertical'] : '0';
$box_shadow_blur        = isset( $box_shadow['blur'] ) ? $box_shadow['blur'] : '0';
$box_shadow_spread      = isset( $box_shadow['spread'] ) ? $box_shadow['spread'] : '0';
$box_shadow_style       = $show_box_shadow;
$box_shadow_style       = ( 'inset' === $box_shadow_style ) ? $box_shadow_style : '';
$box_shadow_color       = isset( $box_shadow['color'] ) ? $box_shadow['color'] : '#dddddd';
$box_shadow_hover_color = isset( $box_shadow['hover_color'] ) ? $box_shadow['hover_color'] : '#dddddd';
$shadow_margin          = ( 'inset' === $box_shadow_style ) ? '' : 'margin: ' . $box_shadow_blur . 'px';


if ( 'none' !== $show_box_shadow ) {
	$dynamic_css .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item {
		box-shadow: ' . $box_shadow_style . ' ' . $box_shadow_horizontal . 'px ' . $box_shadow_vertical . 'px ' . $box_shadow_blur . 'px ' . $box_shadow_spread . 'px ' . $box_shadow_color . ';
		transition: all .3s;
		' . $shadow_margin . ';
	}

	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover {
		box-shadow: ' . $box_shadow_style . ' ' . $box_shadow_horizontal . 'px ' . $box_shadow_vertical . 'px ' . $box_shadow_blur . 'px ' . $box_shadow_spread . 'px ' . $box_shadow_hover_color . ';
	}';
}

// Slider Inner padding.
$inner_padding        = isset( $css_data['wpcp_slide_inner_padding'] ) ? $css_data['wpcp_slide_inner_padding'] : '';
$inner_padding_top    = isset( $inner_padding['top'] ) ? $inner_padding['top'] : 0;
$inner_padding_right  = isset( $inner_padding['right'] ) ? $inner_padding['right'] : 0;
$inner_padding_bottom = isset( $inner_padding['bottom'] ) ? $inner_padding['bottom'] : 0;
$inner_padding_left   = isset( $inner_padding['left'] ) ? $inner_padding['left'] : 0;
// Content Inner padding.
$content_inner_padding  = isset( $css_data['wpcp_content_inner_padding'] ) ? $css_data['wpcp_content_inner_padding'] : '';
$content_padding_top    = isset( $content_inner_padding['top'] ) ? $content_inner_padding['top'] : 0;
$content_padding_right  = isset( $content_inner_padding['right'] ) ? $content_inner_padding['right'] : 0;
$content_padding_bottom = isset( $content_inner_padding['bottom'] ) ? $content_inner_padding['bottom'] : 0;
$content_padding_left   = isset( $content_inner_padding['left'] ) ? $content_inner_padding['left'] : 0;
// Content Box padding.
$content_box_padding        = isset( $css_data['wpcp_content_box_padding'] ) ? $css_data['wpcp_content_box_padding'] : '';
$content_box_padding_top    = isset( $content_box_padding['top'] ) ? $content_box_padding['top'] : 0;
$content_box_padding_right  = isset( $content_box_padding['right'] ) ? $content_box_padding['right'] : 0;
$content_box_padding_bottom = isset( $content_box_padding['bottom'] ) ? $content_box_padding['bottom'] : 0;
$content_box_padding_left   = isset( $content_box_padding['left'] ) ? $content_box_padding['left'] : 0;

// Pagination margin.
$wpcp_pagination_show     = isset( $css_data['wpcp_carousel_pagination']['wpcp_pagination'] ) ? $css_data['wpcp_carousel_pagination']['wpcp_pagination'] : 'show';
$pagination_margin        = isset( $css_data['wpcp_pagination_margin'] ) ? $css_data['wpcp_pagination_margin'] : '';
$pagination_margin_top    = isset( $pagination_margin['top'] ) ? (int) $pagination_margin['top'] : 40;
$pagination_margin_right  = isset( $pagination_margin['right'] ) ? $pagination_margin['right'] : '0';
$pagination_margin_bottom = isset( $pagination_margin['bottom'] ) ? $pagination_margin['bottom'] : '0';
$pagination_margin_left   = isset( $pagination_margin['left'] ) ? $pagination_margin['left'] : '0';
// Others style .
$is_variable_width              = isset( $css_data['_variable_width'] ) ? $css_data['_variable_width'] : '';
$variable_width                 = $is_variable_width ? 'true' : 'false';
$preloader                      = isset( $css_data['wpcp_preloader'] ) ? $css_data['wpcp_preloader'] : true;
$is_adaptive_height             = isset( $css_data['wpcp_adaptive_height'] ) ? $css_data['wpcp_adaptive_height'] : true;
$adaptive_height                = $is_adaptive_height ? 'true' : 'false';
$wpcp_post_detail               = isset( $css_data['wpcp_post_detail_position'] ) ? $css_data['wpcp_post_detail_position'] : '';
$carousel_mode                  = isset( $css_data['wpcp_carousel_mode'] ) ? $css_data['wpcp_carousel_mode'] : 'standard';
$variable_width                 = $is_variable_width ? 'true' : 'false';
$wpcp_arrows                    = isset( $css_data['wpcp_carousel_navigation']['wpcp_navigation'] ) ? $css_data['wpcp_carousel_navigation']['wpcp_navigation'] : '';
$wpcp_hide_on_mobile            = isset( $css_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] ) ? $css_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] : '';
$wpcp_dots                      = isset( $css_data['wpcp_carousel_pagination']['wpcp_pagination'] ) ? $css_data['wpcp_carousel_pagination']['wpcp_pagination'] : 'show';
$wpcp_pagination_hide_on_mobile = isset( $css_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] ) ? $css_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] : '';
$vertical_carousel              = isset( $css_data['wpcp_vertical_carousel'] ) ? $css_data['wpcp_vertical_carousel'] : '';
$image_vertical_alignment       = isset( $css_data['wpcp_image_vertical_alignment'] ) ? $css_data['wpcp_image_vertical_alignment'] : 'center';
$content_vertical_alignment     = isset( $css_data['wpcp_content_vertical_alignment'] ) ? $css_data['wpcp_content_vertical_alignment'] : 'center';
$content_box_size               = isset( $css_data['wpcp_content_box_size'] ) ? $css_data['wpcp_content_box_size'] : '50%';
// Responsive style.
$wpcp_screen_sizes = wpcp_get_option( 'wpcp_responsive_screen_setting' );
$desktop_size      = isset( $wpcp_screen_sizes['desktop'] ) && ! empty( $wpcp_screen_sizes['desktop'] ) ? $wpcp_screen_sizes['desktop'] : '1200';
$laptop_size       = isset( $wpcp_screen_sizes['laptop'] ) && ! empty( $wpcp_screen_sizes['laptop'] ) ? $wpcp_screen_sizes['laptop'] : '980';
$tablet_size       = isset( $wpcp_screen_sizes['tablet'] ) && ! empty( $wpcp_screen_sizes['tablet'] ) ? $wpcp_screen_sizes['tablet'] : '736';
$mobile_size       = isset( $wpcp_screen_sizes['mobile'] ) && ! empty( $wpcp_screen_sizes['mobile'] ) ? $wpcp_screen_sizes['mobile'] : '480';

// Column style.
$column_number     = isset( $css_data['wpcp_number_of_columns'] ) ? $css_data['wpcp_number_of_columns'] : '';
$column_lg_desktop = isset( $column_number['lg_desktop'] ) ? $column_number['lg_desktop'] : '5';
$column_desktop    = isset( $column_number['desktop'] ) ? $column_number['desktop'] : '4';
$column_laptop     = isset( $column_number['laptop'] ) ? $column_number['laptop'] : '3';
$column_tablet     = isset( $column_number['tablet'] ) ? $column_number['tablet'] : '2';
$column_mobile     = isset( $column_number['mobile'] ) ? $column_number['mobile'] : '1';

$wpcp_post_detail        = isset( $css_data['wpcp_post_detail_position'] ) ? $css_data['wpcp_post_detail_position'] : '';
$wpcp_overlay_position   = isset( $css_data['wpcp_overlay_position'] ) ? $css_data['wpcp_overlay_position'] : '';
$wpcp_content_style      = isset( $css_data['wpcp_content_style'] ) ? $css_data['wpcp_content_style'] : '';
$wpcp_caption_partial    = isset( $css_data['wpcp_caption_partial'] ) ? $css_data['wpcp_caption_partial'] : 'bottom_left';
$wpcp_content_box        = isset( $css_data['wpcp_content_box'] ) ? $css_data['wpcp_content_box'] : 'bottom';
$wpcp_overlay_visibility = isset( $css_data['wpcp_overlay_visibility'] ) ? $css_data['wpcp_overlay_visibility'] : '';

$lb_overlay_color = isset( $css_data['wpcp_img_lb_overlay_color'] ) ? $css_data['wpcp_img_lb_overlay_color'] : '#0b0b0b';
$lb_caption_color = isset( $css_data['wpcp_lb_caption_color'] ) ? $css_data['wpcp_lb_caption_color'] : '#ffffff';
$l_box_desc_color = isset( $css_data['l_box_desc_color'] ) ? $css_data['l_box_desc_color'] : '#ffffff';

if ( 'slider' === $layout ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' .swiper-shutters, #wpcpro-wrapper-' . $carousel_id . ' .swiper-gl, #wpcpro-wrapper-' . $carousel_id . ' .swiper-slicer{
    height: ' . $slider_effect_height['top'] . 'px !important;
}';
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' .spring-slider .swiper-slide {
	padding: 0 ' . $slide_margin_half . 'px; }';
}



/**
 * Image Carousel CSS.
 */
$image_carousel_dynamic_css = '';
if ( 'image-carousel' === $carousel_type ) {
	$image_caption_font_load                = isset( $css_data['wpcp_image_caption_font_load'] ) ? $css_data['wpcp_image_caption_font_load'] : '';
	$image_caption_typography               = isset( $css_data['wpcp_image_caption_typography'] ) ? $css_data['wpcp_image_caption_typography'] : '';
	$image_caption_typography_color         = isset( $image_caption_typography['color'] ) ? $image_caption_typography['color'] : '#333';
	$image_caption_typography_size          = isset( $image_caption_typography['font-size'] ) ? $image_caption_typography['font-size'] : '15';
	$image_caption_typography_height        = isset( $image_caption_typography['line-height'] ) ? $image_caption_typography['line-height'] : '23';
	$image_caption_typography_spacing       = isset( $image_caption_typography['letter-spacing'] ) ? $image_caption_typography['letter-spacing'] : '0';
	$image_caption_typography_transform     = isset( $image_caption_typography['text-transform'] ) && ! empty( $image_caption_typography['text-transform'] ) ? $image_caption_typography['text-transform'] : 'capitalize';
	$image_caption_typography_alignment     = isset( $image_caption_typography['text-align'] ) ? $image_caption_typography['text-align'] : 'center';
	$image_caption_typography_family        = isset( $image_caption_typography['font-family'] ) ? $image_caption_typography['font-family'] : '';
	$image_caption_typography_margin_top    = isset( $image_caption_typography['margin-top'] ) ? $image_caption_typography['margin-top'] : '0';
	$image_caption_typography_margin_bottom = isset( $image_caption_typography['margin-bottom'] ) ? $image_caption_typography['margin-bottom'] : '0';

	$image_desc_font_load  = isset( $css_data['wpcp_image_desc_font_load'] ) ? $css_data['wpcp_image_desc_font_load'] : '';
	$image_desc_typography = isset( $css_data['wpcp_image_desc_typography'] ) ? $css_data['wpcp_image_desc_typography'] : '';

	$image_desc_typography_color         = isset( $image_desc_typography['color'] ) ? $image_desc_typography['color'] : '#333';
	$image_desc_typography_size          = isset( $image_desc_typography['font-size'] ) ? $image_desc_typography['font-size'] : '14';
	$image_desc_typography_height        = isset( $image_desc_typography['line-height'] ) ? $image_desc_typography['line-height'] : '21';
	$image_desc_typography_spacing       = isset( $image_desc_typography['letter-spacing'] ) ? $image_desc_typography['letter-spacing'] : '0';
	$image_desc_typography_transform     = isset( $image_desc_typography['text-transform'] ) && ! empty( $image_desc_typography['text-transform'] ) ? $image_desc_typography['text-transform'] : 'none';
	$image_desc_typography_alignment     = isset( $image_desc_typography['text-align'] ) ? $image_desc_typography['text-align'] : 'center';
	$image_desc_typography_margin_top    = isset( $image_desc_typography['margin-top'] ) ? $image_desc_typography['margin-top'] : '0';
	$image_desc_typography_margin_bottom = isset( $image_desc_typography['margin-bottom'] ) ? $image_desc_typography['margin-bottom'] : '0';
	$image_desc_typography_family        = isset( $image_desc_typography['font-family'] ) ? $image_desc_typography['font-family'] : '';

	$dynamic_css .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-image-caption a,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-image-caption {
			color: ' . $image_caption_typography_color . ';
			font-size: ' . $image_caption_typography_size . 'px;
			line-height: ' . $image_caption_typography_height . 'px;
			letter-spacing: ' . $image_caption_typography_spacing . 'px;
			text-transform: ' . $image_caption_typography_transform . ';
			margin-bottom: ' . $image_caption_typography_margin_bottom . 'px;
			margin-top: ' . $image_caption_typography_margin_top . 'px;
			text-align: ' . $image_caption_typography_alignment . ';';
	if ( $image_caption_font_load || ! empty( $image_caption_typography_family ) ) {
		$wpcpro_typography[] = $image_desc_typography;
		$dynamic_css        .= '
			font-family: ' . $image_caption_typography_family . ';
			font-weight: ' . ( isset( $image_caption_typography['font-weight'] ) && is_numeric( $image_caption_typography['font-weight'] ) ? $image_caption_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $image_caption_typography['font-style'] ) && ! empty( $image_caption_typography['font-style'] ) ? $image_caption_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-image-description {
			color: ' . $image_desc_typography_color . ';
			font-size: ' . $image_desc_typography_size . 'px;
			line-height: ' . $image_desc_typography_height . 'px;
			letter-spacing: ' . $image_desc_typography_spacing . 'px;
			margin-bottom: ' . $image_desc_typography_margin_bottom . 'px;
			margin-top: ' . $image_desc_typography_margin_top . 'px;
			text-transform: ' . $image_desc_typography_transform . ';
			text-align: ' . $image_desc_typography_alignment . ';';
	if ( $image_desc_font_load || ! empty( $image_desc_typography_family ) ) {
		$wpcpro_typography[] = $image_desc_typography;
		$dynamic_css        .= '
			font-family: ' . $image_desc_typography_family . ';
			font-weight: ' . ( isset( $image_desc_typography['font-weight'] ) && is_numeric( $image_desc_typography['font-weight'] ) ? $image_desc_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $image_desc_typography['font-style'] ) && ! empty( $image_desc_typography['font-style'] ) ? $image_desc_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}';
	// Lightbox background.
	$dynamic_css .= '.sp-wp-carousel-pro-id-' . $carousel_id . ' .fancybox-bg{
		background: ' . $lb_overlay_color . ';
		opacity: 0.8;
	}
	.sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-fancybox-wrapper .fancybox-caption .wpcp_image_details,
	.sp-wp-carousel-pro-id-' . $carousel_id . ' .fancybox-caption .wpcp_image_details .wpcp_img_caption{
		color: ' . $lb_caption_color . ';
	}
	.sp-wp-carousel-pro-id-' . $carousel_id . ' .fancybox-caption .wpcp_image_details .wpcp_desc{
		color: ' . $l_box_desc_color . ';
	}';
	// Image description read more style options.
	$read_more_border = isset( $css_data['img_readmore_border'] ) ? $css_data['img_readmore_border'] : array(
		'all'         => '1',
		'style'       => 'solid',
		'color'       => '#257F87',
		'hover-color' => '#1f5c5d',
		'radius'      => '0',
	);
	$read_more_color  = isset( $css_data['img_readmore_color_set'] ) ? $css_data['img_readmore_color_set'] : array(
		'color1' => '#fff',
		'color2' => '#fff',
		'color3' => '#257F87',
		'color4' => '#1f5c5d',
	);
	// Read More Button for image short description.
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-image-read-more {
		border: ' . $read_more_border['all'] . 'px ' . $read_more_border['style'] . ' ' . $read_more_border['color'] . ';
		background:' . $read_more_color['color3'] . ';
		border-radius: ' . $read_more_border['radius'] . 'px;
	    cursor: pointer;
		margin-top: 18px;
		margin-bottom:0px;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-image-read-more a{
		color:' . $read_more_color['color1'] . ';
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-image-read-more:hover a{
		color:' . $read_more_color['color2'] . ';
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-image-read-more:hover{
		border-color:' . $read_more_border['hover-color'] . ';
		background:' . $read_more_color['color4'] . ';
	}';
}

/**
 * Video Carousel CSS
 */
if ( 'video-carousel' === $carousel_type ) {
	$video_icon_size  = isset( $css_data['wpcp_video_icon_size'] ) ? $css_data['wpcp_video_icon_size'] : '40';
	$video_icon_color = isset( $css_data['wpcp_video_icon_color'] ) ? $css_data['wpcp_video_icon_color'] : '#fff';
	$dynamic_css     .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item.wcp-video-item i{
		    font-size: ' . $video_icon_size . 'px;
			color: ' . $video_icon_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item.wcp-video-item:hover i{
		    opacity: 0.9;
		}';
}

// Social button css.
$wpcp_post_social_show = isset( $css_data['wpcp_post_social_show'] ) ? $css_data['wpcp_post_social_show'] : false;

if ( 'post-carousel' === $carousel_type && $wpcp_post_social_show ) {
	// Social icon border.
	$wpcp_post_social_border        = isset( $css_data['wpcp_post_social_border'] ) ? $css_data['wpcp_post_social_border'] : array();
	$wpcp_post_social_border_width  = isset( $wpcp_post_social_border['all'] ) ? $wpcp_post_social_border['all'] : '1';
	$wpcp_post_social_border_style  = isset( $wpcp_post_social_border['style'] ) ? $wpcp_post_social_border['style'] : 'solid';
	$wpcp_post_social_border_color  = isset( $wpcp_post_social_border['color'] ) ? $wpcp_post_social_border['color'] : '#257F87';
	$wpcp_post_social_border_hover  = isset( $wpcp_post_social_border['hover-color'] ) ? $wpcp_post_social_border['hover-color'] : '#1f5c5d';
	$wpcp_post_social_border_radius = isset( $wpcp_post_social_border['radius'] ) ? $wpcp_post_social_border['radius'] : '0';
	// Social icon color.
	$wpcp_post_social_color           = isset( $css_data['wpcp_post_social_color'] ) ? $css_data['wpcp_post_social_color'] : array();
	$wpcp_post_social_icon_color      = isset( $wpcp_post_social_color['color1'] ) ? $wpcp_post_social_color['color1'] : '#257F87';
	$wpcp_post_social_icon_h_color    = isset( $wpcp_post_social_color['color2'] ) ? $wpcp_post_social_color['color2'] : '#fff';
	$wpcp_post_social_icon_bg_color   = isset( $wpcp_post_social_color['color3'] ) ? $wpcp_post_social_color['color3'] : '#fff';
	$wpcp_post_social_icon_bg_h_color = isset( $wpcp_post_social_color['color4'] ) ? $wpcp_post_social_color['color4'] : '#1f5c5d';
	// Social Margin.
	$wpcp_post_social_margin        = isset( $css_data['wpcp_post_social_margin'] ) ? $css_data['wpcp_post_social_margin'] : array();
	$wpcp_post_social_margin_top    = isset( $wpcp_post_social_margin['top'] ) ? $wpcp_post_social_margin['top'] : '0';
	$wpcp_post_social_margin_right  = isset( $wpcp_post_social_margin['right'] ) ? $wpcp_post_social_margin['right'] : '5';
	$wpcp_post_social_margin_bottom = isset( $wpcp_post_social_margin['bottom'] ) ? $wpcp_post_social_margin['bottom'] : '0';
	$wpcp_post_social_margin_left   = isset( $wpcp_post_social_margin['left'] ) ? $wpcp_post_social_margin['left'] : '0';
	$wpcp_post_social_margin_css    = $wpcp_post_social_margin_top . 'px ' . $wpcp_post_social_margin_right . 'px ' . $wpcp_post_social_margin_bottom . 'px ' . $wpcp_post_social_margin_left . 'px';
	// Social alignment.
	$wpcp_post_social_alignment = isset( $css_data['wpcp_post_social_alignment'] ) ? $css_data['wpcp_post_social_alignment'] : 'center';
	$dynamic_css               .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcpro-social-share {
		text-align: ' . $wpcp_post_social_alignment . ';
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcpro-social-share a i {
		border: ' . $wpcp_post_social_border_width . 'px ' . $wpcp_post_social_border_style . ' ' . $wpcp_post_social_border_color . ';
		border-radius:  ' . $wpcp_post_social_border_radius . 'px;
		color:  ' . $wpcp_post_social_icon_color . ';
		background-color:  ' . $wpcp_post_social_icon_bg_color . ';
		margin:  ' . $wpcp_post_social_margin_css . ';
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcpro-social-share a:hover i {
		border: ' . $wpcp_post_social_border_width . 'px ' . $wpcp_post_social_border_style . ' ' . $wpcp_post_social_border_hover . ';
		color:  ' . $wpcp_post_social_icon_h_color . ';
		background-color:  ' . $wpcp_post_social_icon_bg_h_color . ';
	}';
}

/**
 * Post Carousel CSS.
 */
if ( 'post-carousel' === $carousel_type || ( 'external-carousel' === $carousel_type ) ) {
	$post_cat_font_load  = isset( $css_data['wpcp_post_cat_font_load'] ) ? $css_data['wpcp_post_cat_font_load'] : '';
	$post_cat_typography = isset( $css_data['wpcp_post_cat_typography'] ) ? $css_data['wpcp_post_cat_typography'] : '';

	$post_cat_typography_color     = isset( $post_cat_typography['color'] ) ? $post_cat_typography['color'] : '#22afba';
	$post_cat_typography_size      = isset( $post_cat_typography['font-size'] ) ? $post_cat_typography['font-size'] : '14';
	$post_cat_typography_height    = isset( $post_cat_typography['line-height'] ) ? $post_cat_typography['line-height'] : '21';
	$post_cat_typography_spacing   = isset( $post_cat_typography['letter-spacing'] ) ? $post_cat_typography['letter-spacing'] : '0';
	$post_cat_typography_transform = isset( $post_cat_typography['text-transform'] ) && ! empty( $post_cat_typography['text-transform'] ) ? $post_cat_typography['text-transform'] : 'none';
	$post_cat_typography_alignment = isset( $post_cat_typography['text-align'] ) ? $post_cat_typography['text-align'] : 'center';
	$post_cat_typography_family    = isset( $post_cat_typography['font-family'] ) ? $post_cat_typography['font-family'] : '';

	$post_title_font_load  = isset( $css_data['wpcp_title_font_load'] ) ? $css_data['wpcp_title_font_load'] : '';
	$post_title_typography = isset( $css_data['wpcp_title_typography'] ) ? $css_data['wpcp_title_typography'] : '';

	$post_title_typography_color       = isset( $post_title_typography['color'] ) ? $post_title_typography['color'] : '#444';
	$post_title_typography_hover_color = isset( $post_title_typography['hover_color'] ) ? $post_title_typography['hover_color'] : '#555';
	$post_title_typography_size        = isset( $post_title_typography['font-size'] ) ? $post_title_typography['font-size'] : '20';
	$post_title_typography_height      = isset( $post_title_typography['line-height'] ) ? $post_title_typography['line-height'] : '30';
	$post_title_typography_spacing     = isset( $post_title_typography['letter-spacing'] ) ? $post_title_typography['letter-spacing'] : '0';
	$post_title_typography_transform   = isset( $post_title_typography['text-transform'] ) && ! empty( $post_title_typography['text-transform'] ) ? $post_title_typography['text-transform'] : 'none';
	$post_title_typography_alignment   = isset( $post_title_typography['text-align'] ) ? $post_title_typography['text-align'] : 'center';
	$post_title_typography_family      = isset( $post_title_typography['font-family'] ) ? $post_title_typography['font-family'] : '';

	$post_content_font_load            = isset( $css_data['wpcp_post_content_font_load'] ) ? $css_data['wpcp_post_content_font_load'] : '';
	$post_content_typography           = isset( $css_data['wpcp_post_content_typography'] ) ? $css_data['wpcp_post_content_typography'] : '';
	$post_content_typography_color     = isset( $post_content_typography['color'] ) ? $post_content_typography['color'] : '#333333';
	$post_content_typography_size      = isset( $post_content_typography['font-size'] ) ? $post_content_typography['font-size'] : '16';
	$post_content_typography_height    = isset( $post_content_typography['line-height'] ) ? $post_content_typography['line-height'] : '26';
	$post_content_typography_spacing   = isset( $post_content_typography['letter-spacing'] ) ? $post_content_typography['letter-spacing'] : '0';
	$post_content_typography_transform = isset( $post_content_typography['text-transform'] ) && ! empty( $post_content_typography['text-transform'] ) ? $post_content_typography['text-transform'] : 'none';
	$post_content_typography_alignment = isset( $post_content_typography['text-align'] ) ? $post_content_typography['text-align'] : 'center';
	$post_content_typography_family    = isset( $post_content_typography['font-family'] ) ? $post_content_typography['font-family'] : '';

	$post_meta_font_load            = isset( $css_data['wpcp_post_meta_font_load'] ) ? $css_data['wpcp_post_meta_font_load'] : '';
	$post_meta_typography           = isset( $css_data['wpcp_post_meta_typography'] ) ? $css_data['wpcp_post_meta_typography'] : '';
	$post_meta_typography_color     = isset( $post_meta_typography['color'] ) ? $post_meta_typography['color'] : '#999';
	$post_meta_typography_size      = isset( $post_meta_typography['font-size'] ) ? $post_meta_typography['font-size'] : '14';
	$post_meta_typography_height    = isset( $post_meta_typography['line-height'] ) ? $post_meta_typography['line-height'] : '24';
	$post_meta_typography_spacing   = isset( $post_meta_typography['letter-spacing'] ) ? $post_meta_typography['letter-spacing'] : '0';
	$post_meta_typography_transform = isset( $post_meta_typography['text-transform'] ) && ! empty( $post_meta_typography['text-transform'] ) ? $post_meta_typography['text-transform'] : 'none';
	$post_meta_typography_alignment = isset( $post_meta_typography['text-align'] ) ? $post_meta_typography['text-align'] : 'center';
	$post_meta_typography_family    = isset( $post_meta_typography['font-family'] ) ? $post_meta_typography['font-family'] : 'Open Sans';

	$post_readmore_font_load              = isset( $css_data['wpcp_post_readmore_font_load'] ) ? $css_data['wpcp_post_readmore_font_load'] : '';
	$post_readmore_typography             = isset( $css_data['wpcp_post_readmore_typography'] ) ? $css_data['wpcp_post_readmore_typography'] : '';
	$post_readmore_typography_color       = isset( $post_readmore_typography['color'] ) ? $post_readmore_typography['color'] : '#fff';
	$post_readmore_typography_hover_color = isset( $post_readmore_typography['hover_color'] ) ? $post_readmore_typography['hover_color'] : '#fff';
	$post_readmore_typography_size        = isset( $post_readmore_typography['font-size'] ) ? $post_readmore_typography['font-size'] : '14';
	$post_readmore_typography_height      = isset( $post_readmore_typography['line-height'] ) ? $post_readmore_typography['line-height'] : '24';
	$post_readmore_typography_spacing     = isset( $post_readmore_typography['letter-spacing'] ) ? $post_readmore_typography['letter-spacing'] : '0';
	$post_readmore_typography_transform   = isset( $post_readmore_typography['text-transform'] ) && ! empty( $post_readmore_typography['text-transform'] ) ? $post_readmore_typography['text-transform'] : 'none';
	$post_readmore_typography_alignment   = isset( $post_readmore_typography['text-align'] ) ? $post_readmore_typography['text-align'] : 'center';
	$post_readmore_typography_family      = isset( $post_readmore_typography['font-family'] ) ? $post_readmore_typography['font-family'] : '';

	// Read More Button.
	$post_readmore_color  = isset( $css_data['wpcp_readmore_color_set'] ) ? $css_data['wpcp_readmore_color_set'] : '';
	$post_readmore_color1 = isset( $post_readmore_color['color1'] ) ? $post_readmore_color['color1'] : '#fff';
	$post_readmore_color2 = isset( $post_readmore_color['color2'] ) ? $post_readmore_color['color2'] : '#fff';
	$post_readmore_color3 = isset( $post_readmore_color['color3'] ) ? $post_readmore_color['color3'] : '#257F87';
	$post_readmore_color4 = isset( $post_readmore_color['color4'] ) ? $post_readmore_color['color4'] : '#1f5c5d';

	// Read More border.
	$post_readmore_border        = isset( $css_data['wpcp_readmore_border'] ) ? $css_data['wpcp_readmore_border'] : array();
	$post_readmore_border_width  = isset( $post_readmore_border['all'] ) ? $post_readmore_border['all'] : '1';
	$post_readmore_border_style  = isset( $post_readmore_border['style'] ) ? $post_readmore_border['style'] : 'solid';
	$post_readmore_border_color  = isset( $post_readmore_border['color'] ) ? $post_readmore_border['color'] : '#257F87';
	$post_readmore_border_hover  = isset( $post_readmore_border['hover-color'] ) ? $post_readmore_border['hover-color'] : '#1f5c5d';
	$post_readmore_border_radius = isset( $post_readmore_border['radius'] ) ? $post_readmore_border['radius'] : '0';
	if ( 'external-carousel' === $carousel_type ) {
		$post_readmore_color  = isset( $css_data['wpcp_feed_readmore_color_set'] ) ? $css_data['wpcp_feed_readmore_color_set'] : '';
		$post_readmore_color1 = isset( $post_readmore_color['color1'] ) ? $post_readmore_color['color1'] : '#fff';
		$post_readmore_color2 = isset( $post_readmore_color['color2'] ) ? $post_readmore_color['color2'] : '#fff';
		$post_readmore_color3 = isset( $post_readmore_color['color3'] ) ? $post_readmore_color['color3'] : '#22afba';
		$post_readmore_color4 = isset( $post_readmore_color['color4'] ) ? $post_readmore_color['color4'] : '#22afba';

		// Read More border.
		$post_readmore_border        = isset( $css_data['wpcp_feed_readmore_border'] ) ? $css_data['wpcp_feed_readmore_border'] : array();
		$post_readmore_border_width  = isset( $post_readmore_border['all'] ) ? $post_readmore_border['all'] : '1';
		$post_readmore_border_style  = isset( $post_readmore_border['style'] ) ? $post_readmore_border['style'] : 'solid';
		$post_readmore_border_color  = isset( $post_readmore_border['color'] ) ? $post_readmore_border['color'] : '#257F87';
		$post_readmore_border_hover  = isset( $post_readmore_border['hover-color'] ) ? $post_readmore_border['hover-color'] : '#1f5c5d';
		$post_readmore_border_radius = isset( $post_readmore_border['radius'] ) ? $post_readmore_border['radius'] : '0';
	}
	$dynamic_css .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-meta .post-categories a {
				color: ' . $post_cat_typography_color . ';
				font-size: ' . $post_cat_typography_size . 'px;
				line-height: ' . $post_cat_typography_height . 'px;
				letter-spacing: ' . $post_cat_typography_spacing . 'px;
				text-transform: ' . $post_cat_typography_transform . ';';
	if ( $post_cat_font_load || ! empty( $post_cat_typography_family ) ) {
		$wpcpro_typography[] = $post_cat_typography;
		$dynamic_css        .= '
			font-family: ' . $post_cat_typography_family . ';
			font-weight: ' . ( isset( $post_cat_typography['font-weight'] ) && is_numeric( $post_cat_typography['font-weight'] ) ? $post_cat_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $post_cat_typography['font-style'] ) && ! empty( $post_cat_typography['font-style'] ) ? $post_cat_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-title a {
			display: block;
    		color: ' . $post_title_typography_color . ';
			font-size: ' . $post_title_typography_size . 'px;
			line-height: ' . $post_title_typography_height . 'px;
			letter-spacing: ' . $post_title_typography_spacing . 'px;
			text-transform: ' . $post_title_typography_transform . ';';
	if ( $post_title_font_load || ! empty( $post_title_typography_family ) ) {
		$wpcpro_typography[] = $post_title_typography;
		$dynamic_css        .= '
			font-family: ' . $post_title_typography_family . ';
			font-weight: ' . ( isset( $post_title_typography['font-weight'] ) && is_numeric( $post_title_typography['font-weight'] ) ? $post_title_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $post_title_typography['font-style'] ) && ! empty( $post_title_typography['font-style'] ) ? $post_title_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-title a:hover{
			color: ' . $post_title_typography_hover_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-title {
			text-align: ' . $post_title_typography_alignment . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-content {
			color: ' . $post_content_typography_color . ';
			font-size: ' . $post_content_typography_size . 'px;
			line-height: ' . $post_content_typography_height . 'px;
			letter-spacing: ' . $post_content_typography_spacing . 'px;
			text-transform: ' . $post_content_typography_transform . ';';
	if ( $post_content_font_load || ! empty( $image_caption_typography_family ) ) {
		$wpcpro_typography[] = $post_content_typography;
		$dynamic_css        .= '
			font-family: ' . $post_content_typography_family . ';
			font-weight: ' . ( isset( $post_content_typography['font-weight'] ) && is_numeric( $post_content_typography['font-weight'] ) ? $post_content_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $post_content_typography['font-style'] ) && ! empty( $post_content_typography['font-style'] ) ? $post_content_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-content {
			text-align: ' . $post_content_typography_alignment . ';
		}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-meta li,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-meta a {
		color: ' . $post_meta_typography_color . ';
		font-size: ' . $post_meta_typography_size . 'px;
		line-height: ' . $post_meta_typography_height . 'px;
		letter-spacing: ' . $post_meta_typography_spacing . 'px;
		text-transform: ' . $post_meta_typography_transform . ';';
	if ( $post_meta_font_load || ! empty( $post_meta_typography_family ) ) {
		$wpcpro_typography[] = $post_meta_typography;
		$dynamic_css        .= '
			font-family: ' . $post_meta_typography_family . ';
			font-weight: ' . ( isset( $post_meta_typography['font-weight'] ) && is_numeric( $post_meta_typography['font-weight'] ) ? $post_meta_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $post_meta_typography['font-style'] ) && ! empty( $post_meta_typography['font-style'] ) ? $post_meta_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-post-meta {
			text-align: ' . $post_meta_typography_alignment . ';
			line-height: ' . $post_meta_typography_height . 'px;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .sp-wpcp-read-more a {
		font-size: ' . $post_readmore_typography_size . 'px;
		line-height: ' . $post_readmore_typography_height . 'px;
		letter-spacing: ' . $post_readmore_typography_spacing . 'px;
		text-transform: ' . $post_readmore_typography_transform . ';';
	if ( $post_readmore_font_load || ! empty( $post_readmore_typography_family ) ) {
		$wpcpro_typography[] = $post_readmore_typography;
		$dynamic_css        .= '
			font-family: ' . $post_readmore_typography_family . ';
			font-weight: ' . ( isset( $post_readmore_typography['font-weight'] ) && is_numeric( $post_readmore_typography['font-weight'] ) ? $post_readmore_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $post_readmore_typography['font-style'] ) && ! empty( $post_readmore_typography['font-style'] ) ? $post_readmore_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .sp-wpcp-read-more {
			text-align: ' . $post_readmore_typography_alignment . ';
			margin-top:10px;
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .sp-wpcp-read-more a {
			color: ' . $post_readmore_color1 . ';
			background: ' . $post_readmore_color3 . ';
			border: ' . $post_readmore_border_width . 'px ' . $post_readmore_border_style . ' ' . $post_readmore_border_color . ';
			border-radius: ' . $post_readmore_border_radius . 'px;
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .sp-wpcp-read-more a:hover {
			color: ' . $post_readmore_color2 . ';
			background: ' . $post_readmore_color4 . ';
			border-color: ' . $post_readmore_border_hover . ';
		}';
}

/**
 * Product Carousel CSS.
 */
if ( 'product-carousel' === $carousel_type ) {
	$product_name_font_load              = isset( $css_data['wpcp_product_name_font_load'] ) ? $css_data['wpcp_product_name_font_load'] : '';
	$product_name_typography             = isset( $css_data['wpcp_product_name_typography'] ) ? $css_data['wpcp_product_name_typography'] : '';
	$product_name_typography_color       = isset( $product_name_typography['color'] ) ? $product_name_typography['color'] : '#444';
	$product_name_typography_hover_color = isset( $product_name_typography['hover_color'] ) ? $product_name_typography['hover_color'] : '#555';
	$product_name_typography_size        = isset( $product_name_typography['font-size'] ) ? $product_name_typography['font-size'] : '15';
	$product_name_typography_height      = isset( $product_name_typography['line-height'] ) ? $product_name_typography['line-height'] : '23';
	$product_name_typography_spacing     = isset( $product_name_typography['letter-spacing'] ) ? $product_name_typography['letter-spacing'] : '0';
	$product_name_typography_transform   = isset( $product_name_typography['text-transform'] ) && ! empty( $product_name_typography['text-transform'] ) ? $product_name_typography['text-transform'] : 'none';
	$product_name_typography_alignment   = isset( $product_name_typography['text-align'] ) ? $product_name_typography['text-align'] : 'center';
	$product_name_typography_family      = isset( $product_name_typography['font-family'] ) ? $product_name_typography['font-family'] : '';

	$product_desc_font_load            = isset( $css_data['wpcp_product_desc_font_load'] ) ? $css_data['wpcp_product_desc_font_load'] : '';
	$product_desc_typography           = isset( $css_data['wpcp_product_desc_typography'] ) ? $css_data['wpcp_product_desc_typography'] : '';
	$product_desc_typography_color     = isset( $product_desc_typography['color'] ) ? $product_desc_typography['color'] : '#333';
	$product_desc_typography_size      = isset( $product_desc_typography['font-size'] ) ? $product_desc_typography['font-size'] : '14';
	$product_desc_typography_height    = isset( $product_desc_typography['line-height'] ) ? $product_desc_typography['line-height'] : '22';
	$product_desc_typography_spacing   = isset( $product_desc_typography['letter-spacing'] ) ? $product_desc_typography['letter-spacing'] : '0';
	$product_desc_typography_transform = isset( $product_desc_typography['text-transform'] ) && ! empty( $product_desc_typography['text-transform'] ) ? $product_desc_typography['text-transform'] : 'none';
	$product_desc_typography_alignment = isset( $product_desc_typography['text-align'] ) ? $product_desc_typography['text-align'] : 'center';
	$product_desc_typography_family    = isset( $product_desc_typography['font-family'] ) ? $product_desc_typography['font-family'] : '';

	$product_price_font_load            = isset( $css_data['wpcp_product_price_font_load'] ) ? $css_data['wpcp_product_price_font_load'] : '';
	$product_price_typography           = isset( $css_data['wpcp_product_price_typography'] ) ? $css_data['wpcp_product_price_typography'] : '';
	$product_price_typography_color     = isset( $product_price_typography['color'] ) ? $product_price_typography['color'] : '#222';
	$product_price_typography_size      = isset( $product_price_typography['font-size'] ) ? $product_price_typography['font-size'] : '14';
	$product_price_typography_height    = isset( $product_price_typography['line-height'] ) ? $product_price_typography['line-height'] : '26';
	$product_price_typography_spacing   = isset( $product_price_typography['letter-spacing'] ) ? $product_price_typography['letter-spacing'] : '0';
	$product_price_typography_transform = isset( $product_price_typography['text-transform'] ) && ! empty( $product_price_typography['text-transform'] ) ? $product_price_typography['text-transform'] : 'none';
	$product_price_typography_alignment = isset( $product_price_typography['text-align'] ) ? $product_price_typography['text-align'] : 'center';
	$product_price_typography_family    = isset( $product_price_typography['font-family'] ) ? $product_price_typography['font-family'] : '';

	$product_rm_font_load              = isset( $css_data['wpcp_product_readmore_font_load'] ) ? $css_data['wpcp_product_readmore_font_load'] : '';
	$product_rm_typography             = isset( $css_data['wpcp_product_readmore_typography'] ) ? $css_data['wpcp_product_readmore_typography'] : '';
	$product_rm_typography_color       = isset( $product_rm_typography['color'] ) ? $product_rm_typography['color'] : '#e74c3c';
	$product_rm_typography_hover_color = isset( $product_rm_typography['hover_color'] ) ? $product_rm_typography['hover_color'] : '#e74c3c';
	$product_rm_typography_size        = isset( $product_rm_typography['font-size'] ) ? $product_rm_typography['font-size'] : '14';
	$product_rm_typography_height      = isset( $product_rm_typography['line-height'] ) ? $product_rm_typography['line-height'] : '24';
	$product_rm_typography_spacing     = isset( $product_rm_typography['letter-spacing'] ) ? $product_rm_typography['letter-spacing'] : '0';
	$product_rm_typography_transform   = isset( $product_rm_typography['text-transform'] ) && ! empty( $product_rm_typography['text-transform'] ) ? $product_rm_typography['text-transform'] : 'none';
	$product_rm_typography_alignment   = isset( $product_rm_typography['text-align'] ) ? $product_rm_typography['text-align'] : 'center';
	$product_rm_typography_family      = isset( $product_rm_typography['font-family'] ) ? $product_rm_typography['font-family'] : '';


	$product_rating_color     = isset( $css_data['wpcp_product_rating_star_color_set'] ) ? $css_data['wpcp_product_rating_star_color_set'] : '';
	$product_rating_color1    = isset( $product_rating_color['color1'] ) ? $product_rating_color['color1'] : '#e74c3c';
	$product_rating_color2    = isset( $product_rating_color['color2'] ) ? $product_rating_color['color2'] : '#e74c3c';
	$product_rating_alignment = isset( $css_data['wpcp_product_rating_alignment'] ) ? $css_data['wpcp_product_rating_alignment'] : 'center';
	if ( 'center' === $product_rating_alignment ) {
		$product_rating_alignment = 'float: none; margin: 8px auto;';
	} else {
		$product_rating_alignment = 'float: ' . $product_rating_alignment . '; margin: 8px 0;';
	}

	$product_readmore_color = isset( $css_data['wpcp_product_readmore_color_set'] ) ? $css_data['wpcp_product_readmore_color_set'] : '';

	// Product Cart Typography.
	$product_cart_font_load     = isset( $css_data['wpcp_product_cart_font_load'] ) ? $css_data['wpcp_product_cart_font_load'] : '';
	$product_cart_typography    = isset( $css_data['wpcp_product_cart_typography'] ) ? $css_data['wpcp_product_cart_typography'] : array(
		'font-family'    => '',
		'font-style'     => '500',
		'font-size'      => '14',
		'line-height'    => '21',
		'letter-spacing' => '0',
		'text-align'     => 'center',
		'text-transform' => 'uppercase',
		'type'           => 'google',
		'margin-top'     => '0',
		'margin-bottom'  => '10',
		'unit'           => 'px',
	);
	$product_add_to_cart_color  = isset( $css_data['wpcp_add_to_cart_color_set'] ) ? $css_data['wpcp_add_to_cart_color_set'] : '';
	$product_add_to_cart_color1 = isset( $product_add_to_cart_color['color1'] ) ? $product_add_to_cart_color['color1'] : '#545454';
	$product_add_to_cart_color2 = isset( $product_add_to_cart_color['color2'] ) ? $product_add_to_cart_color['color2'] : '#fff';
	$product_add_to_cart_color3 = isset( $product_add_to_cart_color['color3'] ) ? $product_add_to_cart_color['color3'] : '#ebebeb';
	$product_add_to_cart_color4 = isset( $product_add_to_cart_color['color4'] ) ? $product_add_to_cart_color['color4'] : '#3f3f3f';
	$product_add_to_cart_color5 = isset( $product_add_to_cart_color['color5'] ) ? $product_add_to_cart_color['color5'] : '#d1d1d1';
	$product_add_to_cart_color6 = isset( $product_add_to_cart_color['color6'] ) ? $product_add_to_cart_color['color6'] : '#d1d1d1';

	$wpcp_add_to_cart_border = isset( $css_data['wpcp_add_to_cart_border'] ) ? $css_data['wpcp_add_to_cart_border'] : array(
		'all'         => '1',
		'style'       => 'solid',
		'color'       => '#d1d1d1',
		'hover-color' => '#d1d1d1',
		'radius'      => '0',
	);
	$cart_border_color       = isset( $wpcp_add_to_cart_border['color'] ) ? $wpcp_add_to_cart_border['color'] : $product_add_to_cart_color5;
	$cart_border_hover_color = isset( $wpcp_add_to_cart_border['hover-color'] ) ? $wpcp_add_to_cart_border['hover-color'] : $product_add_to_cart_color6;

	$dynamic_css .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-product-title a{
			color: ' . $product_name_typography_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-product-title {
			text-align: ' . $product_name_typography_alignment . ';
			font-size: ' . $product_name_typography_size . 'px;
			line-height: ' . $product_name_typography_height . 'px;
			letter-spacing: ' . $product_name_typography_spacing . 'px;
			text-transform: ' . $product_name_typography_transform . ';';
	if ( $product_name_font_load || ! empty( $product_name_typography_family ) ) {
		$wpcpro_typography[] = $product_name_typography;
		$dynamic_css        .= '
			font-family: ' . $product_name_typography_family . ';
			font-weight: ' . ( isset( $product_name_typography['font-weight'] ) && is_numeric( $product_name_typography['font-weight'] ) ? $product_name_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $product_name_typography['font-style'] ) && ! empty( $product_name_typography['font-style'] ) ? $product_name_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-product-title a:hover {
			color: ' . $product_name_typography_hover_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-all-captions {
			color: ' . $product_desc_typography_color . ';
			font-size: ' . $product_desc_typography_size . 'px;
			line-height: ' . $product_desc_typography_height . 'px;
			letter-spacing: ' . $product_desc_typography_spacing . 'px;
			text-transform: ' . $product_desc_typography_transform . ';
			text-align: ' . $product_desc_typography_alignment . ';';
	if ( $product_desc_font_load || ! empty( $product_desc_typography_family ) ) {
		$wpcpro_typography[] = $product_desc_typography;
		$dynamic_css        .= '
			font-family: ' . $product_desc_typography_family . ';
			font-weight: ' . ( isset( $product_desc_typography['font-weight'] ) && is_numeric( $product_desc_typography['font-weight'] ) ? $product_desc_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $product_desc_typography['font-style'] ) && ! empty( $product_desc_typography['font-style'] ) ? $product_desc_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-product-price{
			color: ' . $product_price_typography_color . ';
			font-size: ' . $product_price_typography_size . 'px;
			line-height: ' . $product_price_typography_height . 'px;
			letter-spacing: ' . $product_price_typography_spacing . 'px;
			text-transform: ' . $product_price_typography_transform . ';
			text-align: ' . $product_price_typography_alignment . ';';
	if ( $product_price_font_load || ! empty( $product_price_typography_family ) ) {
		$wpcpro_typography[] = $product_price_typography;
		$dynamic_css        .= '
			font-family: ' . $product_price_typography_family . ';
			font-weight: ' . ( isset( $product_price_typography['font-weight'] ) && is_numeric( $product_price_typography['font-weight'] ) ? $product_price_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $product_price_typography['font-style'] ) && ! empty( $product_price_typography['font-style'] ) ? $product_price_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .star-rating span::before {
			color: ' . $product_rating_color1 . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .star-rating::before {
			color: ' . $product_rating_color2 . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .woocommerce-product-rating .star-rating {
			' . $product_rating_alignment . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-product-more-content a {
			color: ' . $product_rm_typography_color . ';
			font-size: ' . $product_rm_typography_size . 'px;
			line-height: ' . $product_rm_typography_height . 'px;
			letter-spacing: ' . $product_rm_typography_spacing . 'px;
			text-transform: ' . $product_rm_typography_transform . ';
			text-align: ' . $product_rm_typography_alignment . ';';
	if ( $product_rm_font_load || ! empty( $product_rm_typography_family ) ) {
		$wpcpro_typography[] = $product_rm_typography;
		$dynamic_css        .= '
			font-family: ' . $product_rm_typography_family . ';
			font-weight: ' . ( isset( $product_rm_typography['font-weight'] ) && is_numeric( $product_rm_typography['font-weight'] ) ? $product_rm_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $product_rm_typography['font-style'] ) && ! empty( $product_rm_typography['font-style'] ) ? $product_rm_typography['font-style'] : 'normal' ) . ';';
	}
	$dynamic_css .= '}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-all-captions .wpcp-product-more-content a:hover {
			color: ' . $product_rm_typography_hover_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-all-captions .wpcp-cart-button a.button {
			background: ' . $product_add_to_cart_color3 . ';
			border: ' . $wpcp_add_to_cart_border['all'] . 'px ' . $wpcp_add_to_cart_border['style'] . ' ' . $cart_border_color . ';
			border-radius: ' . $wpcp_add_to_cart_border['radius'] . 'px;
			color: ' . $product_add_to_cart_color1 . ';
			font-size: ' . $product_cart_typography['font-size'] . 'px;
			line-height: ' . $product_cart_typography['line-height'] . 'px;
			letter-spacing: ' . $product_cart_typography['letter-spacing'] . 'px;
			margin-top: ' . $product_cart_typography['margin-top'] . 'px;
			margin-bottom: ' . $product_cart_typography['margin-bottom'] . 'px;
			text-transform: ' . $product_cart_typography['text-transform'] . ';';
	if ( $product_cart_font_load || ! empty( $product_cart_typography['font-family'] ) ) {
		$wpcpro_typography[] = $product_cart_typography;
		$dynamic_css        .= '
			font-family: ' . $product_cart_typography['font-family'] . ';
			font-weight: ' . ( isset( $product_cart_typography['font-weight'] ) && is_numeric( $product_cart_typography['font-weight'] ) ? $product_cart_typography['font-weight'] : 'normal' ) . ';
			font-style: ' . ( isset( $product_cart_typography['font-style'] ) && ! empty( $product_cart_typography['font-style'] ) ? $product_cart_typography['font-style'] : 'normal' ) . ';';
	}
		$dynamic_css .= '}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-all-captions .wpcp-cart-button a.added_to_cart{
            margin-top: ' . $product_cart_typography['margin-top'] . 'px;
			margin-bottom: ' . $product_cart_typography['margin-bottom'] . 'px;
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-cart-button {
			text-align: ' . $product_cart_typography['text-align'] . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-all-captions .wpcp-cart-button a.added_to_cart,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-all-captions .wpcp-cart-button a.added_to_cart:hover,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-all-captions .wpcp-cart-button a.button:hover {
			background: ' . $product_add_to_cart_color4 . ';
			border-color: ' . $cart_border_hover_color . ';
			color: ' . $product_add_to_cart_color2 . ';
		}';
	if ( 'with_overlay' === $wpcp_post_detail ) {
		$dynamic_css .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-single-item .wpcp-all-captions {
			padding-bottom: 20px;
		}';
	}
	// Lightbox background.
	$lb_overlay_color = isset( $css_data['wpcp_lb_overlay_color'] ) ? $css_data['wpcp_lb_overlay_color'] : '#0b0b0b';
	$dynamic_css     .= '.sp-wp-carousel-pro-id-' . $carousel_id . ' .fancybox-bg{
		background: ' . $lb_overlay_color . ';
		opacity: 0.8;
	}';
} // End of Product Carousel.
$wpcp_carousel_pagination_position = isset( $css_data['wpcp_carousel_pagination_position'] ) ? $css_data['wpcp_carousel_pagination_position'] : 'outside';
$pagination_alignment              = isset( $css_data['pagination_alignment'] ) ? $css_data['pagination_alignment'] : 'center';
$pagination_color                  = isset( $css_data['pagination_color'] ) ? $css_data['pagination_color'] : array(
	'color'        => '#5e5e5e',
	'hover_color'  => '#ffffff',
	'bg'           => '#ffffff',
	'hover_bg'     => '#178087',
	'border'       => '#dddddd',
	'hover_border' => '#178087',
);
$dynamic_css                      .= '#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-load-more button,
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-pagination .page-numbers, #wpcpro-wrapper-' . $carousel_id . ' .wpcpro-load-more button{
    color: ' . $pagination_color['color'] . ';
    border-color: ' . $pagination_color['border'] . ';
    background:  ' . $pagination_color['bg'] . ';
}#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-load-more button:hover,
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-load-more button:hover{
    color: ' . $pagination_color['hover_color'] . ';
    border-color: ' . $pagination_color['hover_border'] . ';
    background:  ' . $pagination_color['hover_bg'] . ';
}
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-load-more,
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-infinite-scroll-loader,
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-load-more, #wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-pagination {
    text-align: ' . $pagination_alignment . ';
}
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-pagination .page-numbers:hover,
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-pagination .page-numbers.current,
#wpcpro-wrapper-' . $carousel_id . ' .wpcpro-post-pagination .page-numbers.current{
    color: ' . $pagination_color['hover_color'] . ';
    border-color: ' . $pagination_color['hover_border'] . ';
    background:  ' . $pagination_color['hover_bg'] . ';
}';
$item_same_height                  = isset( $css_data['item_same_height'] ) ? $css_data['item_same_height'] : false;

if ( ! $item_same_height ) {
	if ( 'justified' === $layout || 'thumbnails-slider' === $layout ) {
		$image_vertical_alignment   = 'flex-start';
		$content_vertical_alignment = 'flex-start';
	}
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . ' .swiper-wrapper,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-ticker:not(.wpcp_swiper_vertical),
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . ' .wpcpro-row:not(.wpcp-masonry){
		align-items: ' . $image_vertical_alignment . ';
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-ticker:not(.wpcp_swiper_vertical) .wpcp-single-item .wpcp-all-captions,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . ' .wpcpro-row:not(.wpcp-masonry) .wpcp-single-item .wpcp-all-captions {
		justify-content: ' . $content_vertical_alignment . ';
	}';
}

/**
 * When Caption partial selects to moving.
 */
if ( 'moving' === $wpcp_content_style ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.detail-with-overlay.caption-on-bottom-left .wpcp-all-captions {
		position: fixed;
		height: fit-content;
		background-color: white;
		padding: 5px 12px;
		box-shadow: 0 0 2px rgba(0,0,0,0.2);
		border-radius: 5px;
		font-family: Open Sans;
		display: none;
		width: 300px;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.detail-with-overlay.caption-on-bottom-left .wpcp-single-item:hover .wpcp-all-captions {
		display: block;
	}';
}

/**
 * Content Carousel
 */
$wpcp_content_height_dimen = isset( $css_data['wpcp_content_carousel_height']['top'] ) ? $css_data['wpcp_content_carousel_height']['top'] : '300';
$wpcp_content_height_prop  = isset( $css_data['wpcp_content_carousel_height']['style'] ) ? $css_data['wpcp_content_carousel_height']['style'] : 'min-height';
if ( 'content-carousel' === $carousel_type ) {
	$dynamic_css .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.wpcp-content-carousel .wpcp-single-content { ' . $wpcp_content_height_prop . ':' . $wpcp_content_height_dimen . 'px;
	}';
}

/**
 * Grayscale CSS.
 */
$image_gray_scale = isset( $css_data['wpcp_image_gray_scale'] ) ? $css_data['wpcp_image_gray_scale'] : '';

switch ( $image_gray_scale ) {
	case 'gray_and_normal_on_hover':
		$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item .wpcp-slide-image img, .sp-wpcp-' . $carousel_id . ' .wpcp-single-item img {
			-webkit-filter: grayscale(100%);
			filter: grayscale(100%);
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover .wpcp-slide-image img,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover img {
			-webkit-filter: grayscale(0%);
			filter: grayscale(0%);
		}';
		break;
	case 'gray_on_hover':
		$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover .wpcp-slide-image img,#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover img {
			-webkit-filter: grayscale(100%);
			filter: grayscale(100%);
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item img,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item .wpcp-slide-image img {
			-webkit-filter: grayscale(0%);
			filter: grayscale(0%);
		}';
		break;
	case 'always_gray':
		$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item .wpcp-slide-image img, .sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover .wpcp-slide-image img, #wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item img, #wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover img {
			-webkit-filter: grayscale(100%);
			filter: grayscale(100%);
		}';
		break;
	case 'custom_color':
		// Image Custom Color.
		$custom_img_color = isset( $css_data['wpcp_image_custom_color'] ) ? $css_data['wpcp_image_custom_color'] : array(
			'fl_color'       => 'invert(86%) sepia(8%) saturate(13%) hue-rotate(342deg) brightness(79%) contrast(86%) opacity(1)',
			'fl_color_hover' => 'invert(65%) sepia(15%) saturate(0%) hue-rotate(151deg) brightness(91%) contrast(88%) opacity(1)',
		);
		$dynamic_css     .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item .wpcp-slide-image {
			filter: ' . $custom_img_color['fl_color'] . ';
			-webkit-filter: ' . $custom_img_color['fl_color'] . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item:hover .wpcp-slide-image {
			filter: ' . $custom_img_color['fl_color_hover'] . ';
			-webkit-filter: ' . $custom_img_color['fl_color_hover'] . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item .wpcp-slide-image img {
			filter: grayscale(1) brightness(0) saturate(100%);
			-webkit-filter: grayscale(1) brightness(0) saturate(100%);
		}';
		break;
}

// Nav Style.
$wpcp_nav_color              = isset( $css_data['wpcp_nav_arrow_color']['color1'] ) ? $css_data['wpcp_nav_arrow_color']['color1'] : '#aaa';
$wpcp_nav_hover_color        = isset( $css_data['wpcp_nav_arrow_color']['color2'] ) ? $css_data['wpcp_nav_arrow_color']['color2'] : '#fff';
$wpcp_nav_border             = isset( $css_data['wpcp_nav_border']['all'] ) ? $css_data['wpcp_nav_border']['all'] : '1';
$wpcp_nav_border_style       = isset( $css_data['wpcp_nav_border']['style'] ) ? $css_data['wpcp_nav_border']['style'] : 'solid';
$wpcp_nav_border_color       = isset( $css_data['wpcp_nav_border']['color'] ) ? $css_data['wpcp_nav_border']['color'] : '#aaa';
$wpcp_nav_border_hover_color = isset( $css_data['wpcp_nav_border']['hover-color'] ) ? $css_data['wpcp_nav_border']['hover-color'] : '#178087';
$nav_border_radius           = isset( $css_data['wpcp_nav_border']['radius'] ) ? $css_data['wpcp_nav_border']['radius'] : '0';
$wpcp_nav_bg                 = isset( $css_data['wpcp_nav_bg']['color1'] ) ? $css_data['wpcp_nav_bg']['color1'] : 'transparent';
$wpcp_nav_hover_bg           = isset( $css_data['wpcp_nav_bg']['color2'] ) ? $css_data['wpcp_nav_bg']['color2'] : '#178087';
$wpcp_nav_font_size          = isset( $css_data['navigation_icons_size']['all'] ) ? $css_data['navigation_icons_size']['all'] : '20';
$carousel_direction          = isset( $css_data['wpcp_carousel_direction'] ) ? $css_data['wpcp_carousel_direction'] : '';
$dynamic_css                .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-row [class*="wpcpro-col-"] {
	padding-right: ' . $slide_margin_half . 'px;
	padding-left: ' . $slide_margin_half . 'px;
	padding-bottom: ' . $slide_margin_horizontal . 'px;
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.swiper-grid-column .swiper-slide {
	padding-bottom: ' . $slide_horizontal_margin_half . 'px;
	padding-top: ' . $slide_horizontal_margin_half . 'px;
}
#wpcpro-wrapper-' . $carousel_id . ':not(.wpcp-justified) #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-row {
	margin-right: -' . $slide_margin_half . 'px;
	margin-left: -' . $slide_margin_half . 'px;
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs .swiper-slide{
	margin-top: ' . $slide_margin_horizontal . 'px;
}';
if ( $wpcp_img_width_auto ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-slider .wpcp-slide-image img{
		width: auto;
	}';
}
$thumbnail_position = isset( $css_data['wpcp_thumbnail_position'] ) ? $css_data['wpcp_thumbnail_position'] : 'bottom';

if ( 'static' === $thumbnails_height_type && ( 'left' !== $thumbnail_position || 'right' !== $thumbnail_position ) ) {
	if ( '%' === $thumb_unit ) {
		$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs .wpcp-slide-image:before{
			content: "";
			padding-top:  ' . $thumb_height . '%;
			display: block;
		}#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs .wpcp-slide-image img{
			position: absolute;
			top: 0;
			left: 0;
			object-fit: cover;
			height: 100%
		}';
	} else {
		$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs img{
			height: ' . $thumb_height . 'px;
			object-fit: cover;
		}';
	}
}

if ( 'rtl' === $carousel_direction ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.wpcp-advance-effect .swiper-slide .single-item-fade:not(:last-child) {
		margin-right: ' . $slide_margin_horizontal . 'px;
	}';
} else {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.wpcp-advance-effect .swiper-slide .single-item-fade:not(:last-child) {
		margin-left: ' . $slide_margin_horizontal . 'px;
	}';
}
$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' .wpcp-kenburn .wpcpro-gallery-slider .swiper-slide .wpcp-single-item img,
	#wpcpro-wrapper-' . $carousel_id . ' .wpcp-kenburn .swiper-slide .swiper-slide-kenburn {
		transition: transform ' . $autoplay_speed . 'ms linear;
	}';

if ( ! $hide_nav_bg_border ) {
	$dynamic_css .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button {
			color: ' . $wpcp_nav_color . ';
			background-color: ' . $wpcp_nav_bg . ';
			border: ' . $wpcp_nav_border . 'px ' . $wpcp_nav_border_style . ';
			border-color: ' . $wpcp_nav_border_color . ';
			border-radius: ' . $nav_border_radius . '%;
			font-size: ' . $wpcp_nav_font_size . 'px;
			height: ' . ( (int) $wpcp_nav_font_size + 10 ) . 'px;
			width: ' . ( (int) $wpcp_nav_font_size + 10 ) . 'px;
			pointer-events: auto;
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button:hover,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button:hover {
			color: ' . $wpcp_nav_hover_color . ';
			background-color: ' . $wpcp_nav_hover_bg . ';
			border-color: ' . $wpcp_nav_border_hover_color . ';
			font-size: ' . $wpcp_nav_font_size . 'px;
		}';
} else {
	$dynamic_css .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button:hover,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button:hover {
			background: none;
			border: none;
			font-size: ' . $wpcp_nav_font_size . 'px;
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button {
			color: ' . $wpcp_nav_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button:hover,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button:hover {
			color: ' . $wpcp_nav_hover_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button {
			text-align: left;
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button {
			text-align: right;
		}';
}


/**
 * The Dynamic Style CSS.
 */
$wpcp_pagination_color1 = isset( $css_data['wpcp_pagination_color']['color1'] ) ? $css_data['wpcp_pagination_color']['color1'] : '#cccccc';
$wpcp_pagination_color2 = isset( $css_data['wpcp_pagination_color']['color2'] ) ? $css_data['wpcp_pagination_color']['color2'] : '#52b3d9';

// Carousel background Gradient and Solid Color Type.
$overlay_color_type         = isset( $css_data['wpcp_overlay_color_type'] ) ? $css_data['wpcp_overlay_color_type'] : 'solid';
$content_overlay_color_type = isset( $css_data['wpcp_content_box_color_type'] ) ? $css_data['wpcp_content_box_color_type'] : 'solid';
switch ( $overlay_color_type ) {
	case 'solid':
		$wpcp_overlay_bg = isset( $css_data['wpcp_overlay_bg'] ) ? $css_data['wpcp_overlay_bg'] : '#444'; // overlay solid background color.
		$dynamic_css    .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay .wpcp-all-captions {
				background: ' . $wpcp_overlay_bg . ';
			}';
		break;
	case 'gradient':
		$gradient_colors           = isset( $css_data['wpcp_bg_gradient_color'] ) ? $css_data['wpcp_bg_gradient_color'] : ''; // Overlay Gradient Color.
		$gradient_direction        = isset( $gradient_colors['background-gradient-direction'] ) ? $gradient_colors['background-gradient-direction'] : '135deg';
		$gradient_background_color = isset( $gradient_colors['background-color'] ) ? $gradient_colors['background-color'] : '#004054bd';
		$_gradient_color           = isset( $gradient_colors['background-gradient-color'] ) ? $gradient_colors['background-gradient-color'] : '#23b7edb5';
		$dynamic_css              .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay .wpcp-all-captions {
			background-color: ' . $gradient_background_color . ';
			background-image: linear-gradient( ' . $gradient_direction . ' , ' . $gradient_background_color . ', ' . $_gradient_color . ' );
		}';
		break;
}

/**
 * Overlay styles.
 */
if ( 'default' !== $wpcp_content_style ) {
	if ( 'caption_full' === $wpcp_content_style ) {
		$dynamic_css .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.overlay-lower .wpcp-all-captions {
			border-radius: 0 0 ' . $inner_padding_top . 'px ' . $inner_padding_right . 'px;
		}';
	} else {
		$dynamic_css .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay .wpcp-all-captions {
			border-radius: ' . $radius_dimension . $radius_unit . ';
		}';
	}
}
// Content Overlay background color.
switch ( $content_overlay_color_type ) {
	case 'solid':
		$content_overlay_bg = isset( $css_data['wpcp_content_overlay_bg'] ) ? $css_data['wpcp_content_overlay_bg'] : '#444'; // overlay solid background color.
		$dynamic_css       .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.content-box .wpcp-all-captions,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.caption-on-moving .wpcp-all-captions {
				background: ' . $content_overlay_bg . ';
			}';
		break;
	case 'gradient':
		$content_gradient_colors    = isset( $css_data['wpcp_content_bg_gradient_color'] ) ? $css_data['wpcp_content_bg_gradient_color'] : ''; // Overlay Gradient Color.
		$content_gradient_direction = isset( $content_gradient_colors['background-gradient-direction'] ) ? $content_gradient_colors['background-gradient-direction'] : '135deg';
		$content_gradient_bg_color  = isset( $content_gradient_colors['background-color'] ) ? $content_gradient_colors['background-color'] : '#004054bd';
		$content_gradient_color     = isset( $content_gradient_colors['background-gradient-color'] ) ? $content_gradient_colors['background-gradient-color'] : '#23b7edb5';
		$dynamic_css               .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.content-box .wpcp-all-captions,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.caption-on-moving .wpcp-all-captions {
			background-color: ' . $content_gradient_bg_color . ';
			background-image: linear-gradient( ' . $content_gradient_direction . ' , ' . $content_gradient_bg_color . ', ' . $content_gradient_color . ' );
		}';
		break;
}

/**
 * Content Box Padding for Overlay.
 */
if ( ( 'with_overlay' === $wpcp_content_style && 'full_covered' === $wpcp_overlay_position ) ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay .wpcp-all-captions {
		top: ' . $content_box_padding_top . 'px;
		right: ' . $content_box_padding_right . 'px;
		bottom: ' . $content_box_padding_bottom . 'px;
		left: ' . $content_box_padding_left . 'px;
		overflow: hidden;
	}';
}

// Box Border and Box Shadow for Content Box Style.
$is_content_box_style      = 'content_box' === $wpcp_content_style;
$is_caption_partial_moving = 'moving' === $wpcp_content_style;
if ( $is_content_box_style || $is_caption_partial_moving ) {
	$wpcp_content_box_border = isset( $css_data['wpcp_content_box_border'] ) ? $css_data['wpcp_content_box_border'] : '';
	$border_width            = isset( $wpcp_content_box_border['all'] ) ? $wpcp_content_box_border['all'] : '0';
	$border_style            = isset( $wpcp_content_box_border['style'] ) ? $wpcp_content_box_border['style'] : 'solid';
	$border_color            = isset( $wpcp_content_box_border['color'] ) ? $wpcp_content_box_border['color'] : 'solid';
	$border_radius           = isset( $wpcp_content_box_border['radius'] ) ? $wpcp_content_box_border['radius'] : '2';
	$show_content_box_shadow = isset( $css_data['wpcp_show_content_box_shadow'] ) ? $css_data['wpcp_show_content_box_shadow'] : 0;

	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay .wpcp-all-captions {
		border: ' . $border_width . 'px ' . $border_style . ' ' . $border_color . ';
		border-radius: ' . $border_radius . 'px;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.content-box .wpcp-all-captions {
		padding: ' . $content_box_padding_top . 'px ' . $content_box_padding_right . 'px ' . $content_box_padding_bottom . 'px ' . $content_box_padding_left . 'px;
	}';

	if ( $show_content_box_shadow ) {
		$content_box_shadow             = isset( $css_data['wpcp_content_box_shadow'] ) ? $css_data['wpcp_content_box_shadow'] : array();
		$content_box_shadow_horizontal  = isset( $content_box_shadow['horizontal'] ) ? $content_box_shadow['horizontal'] : '0';
		$content_box_shadow_vertical    = isset( $content_box_shadow['vertical'] ) ? $content_box_shadow['vertical'] : '0';
		$content_box_shadow_blur        = isset( $content_box_shadow['blur'] ) ? $content_box_shadow['blur'] : '0';
		$content_box_shadow_spread      = isset( $content_box_shadow['spread'] ) ? $content_box_shadow['spread'] : '0';
		$content_box_shadow_style       = isset( $content_box_shadow['style'] ) ? $content_box_shadow['style'] : 'outset';
		$content_box_shadow_style       = ( 'inset' === $content_box_shadow ) ? $content_box_shadow : '';
		$content_box_shadow_color       = isset( $content_box_shadow['color'] ) ? $content_box_shadow['color'] : '#dddddd';
		$content_box_shadow_hover_color = isset( $content_box_shadow['hover_color'] ) ? $content_box_shadow['hover_color'] : '#dddddd';
		$box_shadow_margin              = ( 'inset' === $content_box_shadow_style ) ? '' : 'margin: ' . $content_box_shadow_blur . 'px';

		$dynamic_css .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay .wpcp-all-captions {
			box-shadow: ' . $content_box_shadow_style . ' ' . $content_box_shadow_horizontal . 'px ' . $content_box_shadow_vertical . 'px ' . $content_box_shadow_blur . 'px ' . $content_box_shadow_spread . 'px ' . $content_box_shadow_color . ';
			' . $box_shadow_margin . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay .wpcp-all-captions:hover {
			box-shadow: ' . $content_box_shadow_style . ' ' . $content_box_shadow_horizontal . 'px ' . $content_box_shadow_vertical . 'px ' . $content_box_shadow_blur . 'px ' . $content_box_shadow_spread . 'px ' . $content_box_shadow_hover_color . ';
		}
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.content_box .wpcp-all-captions {
			transition: all .3s;
		}';
	}
}

/**
 * Dynamic Style start.
 */
$dynamic_css .= '
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.overlay-on-left .wpcp-all-captions {
	width: ' . $content_box_size . '%;
	overflow: hidden;
	right: unset;
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay.overlay-on-right .wpcp-all-captions {
	width: ' . $content_box_size . '%;
	overflow: hidden;
	left: unset;
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-on-right .wpcp-all-captions,
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-on-left .wpcp-all-captions,
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.detail-with-overlay:not(.content-box) .wpcp-all-captions{
	padding: ' . esc_html( $content_padding_top . 'px ' . $content_padding_right . 'px ' . $content_padding_bottom . 'px ' . $content_padding_left ) . 'px;
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-swiper-dots,#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . '.swiper-horizontal>.wpcp-pagination-scrollbar {
		margin: ' . esc_html( $pagination_margin_top . 'px ' . $pagination_margin_right . 'px ' . $pagination_margin_bottom . 'px ' . $pagination_margin_left ) . 'px;
		bottom: unset;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-swiper-dots .swiper-pagination-bullet,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-pagination-scrollbar {
		background-color: ' . $wpcp_pagination_color1 . ';
		opacity: 1;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-swiper-dots .swiper-pagination-bullet.swiper-pagination-bullet-active,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-pagination-scrollbar .swiper-scrollbar-drag {
		background-color: ' . $wpcp_pagination_color2 . ';
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-single-item {
		border: ' . $slide_border_width . 'px ' . $slide_border_style . ' ' . $slide_border_color . ';
		padding: ' . esc_html( $inner_padding_top . 'px ' . $inner_padding_right . 'px ' . $inner_padding_bottom . 'px ' . $inner_padding_left ) . 'px;
		border-radius: ' . $slide_border_radius . 'px;
	}
';

// if ( $wpcp_pagination_show && ! $section_title ) {
// $dynamic_css .= '
// .wpcp-wrapper-' . $carousel_id . '.wpcpro-wrapper .wpcp-carousel-section.nav-vertically-inner-and-outer .wpcp-prev-button,
// .wpcp-wrapper-' . $carousel_id . '.wpcpro-wrapper .wpcp-carousel-section.nav-vertically-inner-and-outer .wpcp-next-button {
// margin-top: -' . $pagination_margin_top . 'px;
// }';
// }
// if ( ! $wpcp_pagination_show && $section_title ) {
// $dynamic_css .= '
// .wpcp-wrapper-' . $carousel_id . '.wpcpro-wrapper .wpcp-carousel-section.nav-vertically-inner-and-outer .wpcp-prev-button,
// .wpcp-wrapper-' . $carousel_id . '.wpcpro-wrapper .wpcp-carousel-section.nav-vertically-inner-and-outer .wpcp-next-button {
// margin-top: ' . $section_title_margin_bottom . 'px;
// }';
// }

if ( $preloader ) {
	$dynamic_css .= '
		.wpcp-carousel-wrapper.wpcp-wrapper-' . $carousel_id . '{
			position: relative;
		}#wpcp-preloader-' . $carousel_id . '{
			background: #fff;
			position: absolute;
			left: 0;
			top: 0;
			height: 100%;
			width: 100%;
			text-align: center;
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 999;
		}';
}
if ( $wpcp_hide_on_mobile ) {
	$dynamic_css .= '
		@media screen and (max-width: 479px) {
			#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-prev-button.swiper-button-prev,
			#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-next-button.swiper-button-next {
				visibility: hidden;
			}
			#wpcpro-wrapper-' . $carousel_id . ' .wpcp-carousel-section.nav-vertical-center:not(.wpcp_swiper_vertical) {
				margin: 0;
			}
		}';
}

if ( $wpcp_pagination_hide_on_mobile ) {
	$dynamic_css .= '
	@media screen and (max-width: 479px) {
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-swiper-dots,#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-pagination-scrollbar {
			display: none;
		}
	}';
}

if ( $lazy_load_image && 'carousel' === $layout ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wcp-lazy.swiper-lazy:not(.swiper-lazy-loaded) {
			height: 100px;
	}';
}

/**
 * Image height CSS.
 */
$image_height_css            = isset( $css_data['wpcp_image_height_css'] ) ? $css_data['wpcp_image_height_css'] : '';
$image_height_css_desktop    = isset( $image_height_css['top'] ) ? $image_height_css['top'] : '';
$image_height_css_lg_desktop = isset( $image_height_css['lg_desktop'] ) ? $image_height_css['lg_desktop'] : $image_height_css_desktop; // This option added later.
$image_height_css_laptop     = isset( $image_height_css['right'] ) ? $image_height_css['right'] : '';
$image_height_css_tablet     = isset( $image_height_css['bottom'] ) ? $image_height_css['bottom'] : '';
$image_height_css_mobile     = isset( $image_height_css['left'] ) ? $image_height_css['left'] : '';
$image_height_css_force      = isset( $image_height_css['style'] ) ? $image_height_css['style'] : '';
$desktop_size_plus           = (int) $desktop_size + 1;
$laptop_size_plus            = (int) $laptop_size + 1;
$tablet_size_plus            = (int) $tablet_size + 1;
$mobile_size_plus            = (int) $mobile_size + 1;
if ( $image_height_css_lg_desktop ) {
	$dynamic_css .= '
	@media screen and (min-width: ' . $desktop_size_plus . 'px) {
		#wpcpro-wrapper-' . $carousel_id . ':not(.wpcp-justified) #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ':not(.wpcp-content-carousel) > div:not(.wpcpro-gallery-thumbs) .wpcp-single-item img { ' . $image_height_css_force . ':' . $image_height_css_lg_desktop . 'px;
		}
	}';
}
if ( $image_height_css_desktop ) {
	$dynamic_css .= '
	@media screen and (min-width: ' . $laptop_size_plus . 'px) and (max-width: ' . $desktop_size_plus . 'px) {
		#wpcpro-wrapper-' . $carousel_id . ':not(.wpcp-justified) #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ':not(.wpcp-content-carousel) > div:not(.wpcpro-gallery-thumbs) .wpcp-single-item img { ' . $image_height_css_force . ':' . $image_height_css_desktop . 'px;
		}
	}';
}
if ( $image_height_css_laptop ) {
	$dynamic_css .= '
	@media screen and (min-width: ' . $tablet_size_plus . 'px) and (max-width: ' . $laptop_size . 'px) {
		#wpcpro-wrapper-' . $carousel_id . ':not(.wpcp-justified) #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ':not(.wpcp-content-carousel) > div:not(.wpcpro-gallery-thumbs) .wpcp-single-item img { ' . $image_height_css_force . ':' . $image_height_css_laptop . 'px; }
	}';
}
if ( $image_height_css_tablet ) {
	$dynamic_css .= '
	@media screen and (min-width: ' . $mobile_size_plus . 'px) and (max-width: ' . $tablet_size . 'px) {
		#wpcpro-wrapper-' . $carousel_id . ':not(.wpcp-justified) #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ':not(.wpcp-content-carousel)> div:not(.wpcpro-gallery-thumbs) .wpcp-single-item img { ' . $image_height_css_force . ':' . $image_height_css_tablet . 'px; }
	}';
}
if ( $image_height_css_mobile ) {
	$dynamic_css .= '
	@media screen and  (max-width: ' . $mobile_size . 'px) {
		#wpcpro-wrapper-' . $carousel_id . ':not(.wpcp-justified) #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ':not(.wpcp-content-carousel)  > div:not(.wpcpro-gallery-thumbs) .wpcp-single-item img { ' . $image_height_css_force . ':' . $image_height_css_mobile . 'px; }
	}';
}

if ( 'slider' === $layout ) {
	$dynamic_css      .= ' @media screen and (max-width: ' . $mobile_size . 'px) {
	#wpcpro-wrapper-' . $carousel_id . ' .swiper-shutters, #wpcpro-wrapper-' . $carousel_id . ' .swiper-gl, #wpcpro-wrapper-' . $carousel_id . ' .swiper-slicer{
    height: ' . $slider_effect_height['left'] . 'px !important;
	}
	}
	@media screen and (min-width: ' . $tablet_size_plus . 'px) {
	#wpcpro-wrapper-' . $carousel_id . ' .swiper-shutters, #wpcpro-wrapper-' . $carousel_id . ' .swiper-gl, #wpcpro-wrapper-' . $carousel_id . ' .swiper-slicer{
    height: ' . $slider_effect_height['bottom'] . 'px !important;
	}
	}
	@media screen and (min-width: ' . $laptop_size_plus . 'px) {
	#wpcpro-wrapper-' . $carousel_id . ' .swiper-shutters, #wpcpro-wrapper-' . $carousel_id . ' .swiper-gl, #wpcpro-wrapper-' . $carousel_id . ' .swiper-slicer{
    height: ' . $slider_effect_height['right'] . 'px !important;
	}
	}
	@media screen and (min-width: ' . $desktop_size_plus . 'px) {
	#wpcpro-wrapper-' . $carousel_id . ' .swiper-shutters, #wpcpro-wrapper-' . $carousel_id . ' .swiper-gl, #wpcpro-wrapper-' . $carousel_id . ' .swiper-slicer{
    height: ' . $slider_effect_height['top'] . 'px !important;
	}
	}';
}
// Image zoom css.
switch ( $image_zoom ) {
	case 'zoom_in':
		$dynamic_css .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-image-carousel .wpcp-slide-image:hover img,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-post-carousel .wpcp-slide-image:hover img,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-slide-image:hover img{
				-webkit-transform: scale(1.2);
				-moz-transform: scale(1.2);
				transform: scale(1.2);
			}';
		break;
	case 'zoom_out':
		$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-image-carousel .wpcp-slide-image img,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-post-carousel .wpcp-slide-image img,
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-slide-image img{
				-webkit-transform: scale(1.2);
				-moz-transform: scale(1.2);
				transform: scale(1.2);
			}
			#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-image-carousel .wpcp-slide-image:hover img,
			#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-post-carousel .wpcp-slide-image:hover img,
			#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-slide-image:hover img{
				-webkit-transform: scale(1);
				-moz-transform: scale(1);
				transform: scale(1);
			}';
		break;
}
if ( 'content-carousel' !== $carousel_type ) {
	$dynamic_css .= '
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-slide-image img,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-single-item.wpcp-mix-content img,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-slide-image .wpcp_icon_overlay,
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ' .wpcp-product-carousel .wpcp-slide-image a {
		border-radius: ' . $radius_dimension . $radius_unit . ';
		overflow: hidden;
	}';
}

$dynamic_css .= '
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . ':not(.wpcp-content-carousel):not(.swiper-gl) .wpcp-single-item {
	background: ' . $wpcp_slide_background . ';
}';
$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' .wpcp-carousel-section.detail-on-bottom.swiper-gl .wpcp-all-caption{
	background: ' . $wpcp_slide_background . ';
	display: none !important;
}';
$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' .wpcp-carousel-section.detail-on-bottom.swiper-gl .wpcp-all-captions{
	background: ' . $wpcp_slide_background . ';
}';
// Image border.
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-image-carousel .wpcp-slide-image img,#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-slide-image a img,#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-post-carousel .wpcp-slide-image a img{
		border: ' . $image_border_width . 'px ' . $image_border_style . ' ' . $image_border_color . ';
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-image-carousel .wpcp-slide-image a:hover img,#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-post-carousel .wpcp-slide-image a:hover img,#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.sp-wpcp-' . $carousel_id . '.wpcp-product-carousel .wpcp-slide-image a:hover img{
		border-color: ' . $image_border_hover_color . ';
	}';
// FancyBox arrow styling.
$l_box_icon_size            = isset( $css_data['l_box_icon_size'] ) ? $css_data['l_box_icon_size'] : '13';
$l_box_icon_overlay_color   = isset( $css_data['l_box_icon_overlay_color'] ) ? $css_data['l_box_icon_overlay_color'] : 'rgba(0,0,0,0.3)';
$l_box_icon_color           = isset( $css_data['l_box_icon_color'] ) ? $css_data['l_box_icon_color'] : '';
$l_box_icons_color          = isset( $l_box_icon_color['color1'] ) ? $l_box_icon_color['color1'] : '#fff';
$l_box_icon_hover_color     = isset( $l_box_icon_color['color2'] ) ? $l_box_icon_color['color2'] : '#fff';
$l_box_icon_bg              = isset( $l_box_icon_color['color3'] ) ? $l_box_icon_color['color3'] : 'rgba(0, 0, 0, 0.5)';
$l_box_icon_hover_bg        = isset( $l_box_icon_color['color4'] ) ? $l_box_icon_color['color4'] : 'rgba(0, 0, 0, 0.8)';
$l_box_nav_arrow_color      = isset( $css_data['l_box_nav_arrow_color'] ) ? $css_data['l_box_nav_arrow_color'] : '';
$l_box_arrow_color          = isset( $l_box_nav_arrow_color['color1'] ) ? $l_box_nav_arrow_color['color1'] : '#ccc';
$l_box_arrow_hover_color    = isset( $l_box_nav_arrow_color['color2'] ) ? $l_box_nav_arrow_color['color2'] : '#fff';
$l_box_arrow_bg_color       = isset( $l_box_nav_arrow_color['color3'] ) ? $l_box_nav_arrow_color['color3'] : '#1e1e1e';
$l_box_arrow_hover_bg_color = isset( $l_box_nav_arrow_color['color4'] ) ? $l_box_nav_arrow_color['color4'] : '#1e1e1e';

$dynamic_css .= '
 .sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-fancybox-wrapper .fancybox-navigation .fancybox-button .wpcp-fancybox-nav-arrow i {
	color: ' . $l_box_arrow_color . ';
}
.sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-fancybox-wrapper .fancybox-navigation .fancybox-button .wpcp-fancybox-nav-arrow i:hover {
	color: ' . $l_box_arrow_hover_color . ';
}
.sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-fancybox-wrapper .fancybox-navigation .fancybox-button {
	background: ' . $l_box_arrow_bg_color . ';
}
.sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-fancybox-wrapper .fancybox-navigation .fancybox-button:hover {
	background: ' . $l_box_arrow_hover_bg_color . ';
}
.sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-fancybox-wrapper .fancybox-caption .wpcp_image_details{
	color: ' . $lb_caption_color . ';
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section .wpcp-mix-content .wpcp_icon_overlay i,
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.wpcp-image-carousel .wpcp-slide-image .wpcp_icon_overlay i {
	font-size: ' . $l_box_icon_size . 'px;
	color: ' . $l_box_icons_color . ';
	background: ' . $l_box_icon_bg . ';
	padding : 10px;
	border-radius: 50%;
	height: ' . (int) $l_box_icon_size * 2 . 'px;
	width: ' . (int) $l_box_icon_size * 2 . 'px;
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section .wpcp-mix-content .wpcp_icon_overlay,
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.wpcp-image-carousel .wpcp-slide-image .wpcp_icon_overlay {
	background-color: ' . $l_box_icon_overlay_color . ';
}
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section .wpcp-mix-content .wpcp_icon_overlay i:hover,
#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.wpcp-image-carousel .wpcp-slide-image .wpcp_icon_overlay i:hover {
	color: ' . $l_box_icon_hover_color . ';
	background: ' . $l_box_icon_hover_bg . ';
}
';

/* Style for Pagination Inside */
if ( 'inside' === $wpcp_carousel_pagination_position ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcp-swiper-dots {
		position: absolute;
		bottom: 20px;
		margin: 0;
	}';
}
if ( 'hide' !== $wpcp_pagination_show && 'outside' === $wpcp_carousel_pagination_position ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ':not(.wpcpro-thumbnail-slider)  .wpcp-swiper-wrapper{
	margin-bottom: 60px;}';
}
if ( 'hide' !== $wpcp_pagination_show && 'vertical' === $carousel_orientation ) {
	$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . '{
	margin-bottom:60px;
}';
}
$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' .fashion-slider .wpcp-carousel-section{
	background-color: ' . $section_background . ';
}';
if ( 'thumbnails-slider' === $layout ) {
	$thumb_orientation         = isset( $css_data['thumbnails_orientation'] ) ? $css_data['thumbnails_orientation'] : 'horizontal';
	$thumbnails_hide_on_mobile = isset( $css_data['thumbnails_hide_on_mobile'] ) ? $css_data['thumbnails_hide_on_mobile'] : false;

	if ( 'left' === $thumbnail_position || 'right' === $thumbnail_position ) {
		$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . '.wpcpro-wrapper .wpcp-thumbnail-slider .wpcpro-gallery-slider {
		position: relative;
	}
	#wpcpro-wrapper-' . $carousel_id . '.wpcpro-thumbnail-slider .wpcp-thumbnail-slider {
		position: relative;
		width: 100%;
		display: flex;
		justify-content: space-between;
		margin:0;
	}
	#wpcpro-wrapper-' . $carousel_id . '.wpcpro-thumbnail-slider .wpcp-swiper-wrapper {
		width: calc(100% - 18%);
	}
	#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs {
		order: 1;
		width: 18%;
		margin-left: ' . $slide_margin_vertical . 'px;
		overflow:hidden;
	}

	#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs .swiper-slide * {
		height: inherit;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs .wpcp-slide-image img{
		height: inherit;
		object-fit: cover;
	}
	#wpcpro-wrapper-' . $carousel_id . ' .wpcp-carousel-section.wpcp-thumbnail-slider.nav-vertical-center .wpcp-next-button {
		right: 10px;
	}
	#wpcpro-wrapper-' . $carousel_id . ' .wpcp-carousel-section.wpcp-thumbnail-slider.nav-vertical-center .wpcp-prev-button{
		left: 10px;
	}
	#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider[dir="rtl"] .wpcpro-gallery-thumbs {
		margin-left: ' . $slide_margin_vertical . 'px;
	}
	#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs .swiper-slide{
        margin-top:0;
		overflow: hidden;
	}';
		if ( 'left' === $thumbnail_position ) {
			$dynamic_css .= '#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs {
				order: -1;
				width: 18%;
				margin-right: ' . $slide_margin_vertical . 'px;
				margin-left: 0;
				overflow:hidden;
			}';
		}
		if ( $image_height_css_desktop ) {
			$dynamic_css .= '@media screen and (min-width: ' . $laptop_size_plus . 'px) {
				#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs {
				max-height: ' . $image_height_css_desktop . 'px;
				}
			}';
		}
		if ( $image_height_css_laptop ) {
			$dynamic_css .= '
			@media screen and (min-width: ' . $tablet_size_plus . 'px) and (max-width: ' . $laptop_size . 'px) {#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs {
				max-height: ' . $image_height_css_laptop . 'px;
				}
			}';
		}
		if ( $image_height_css_tablet ) {
			$dynamic_css .= '
			@media screen and (min-width: ' . $mobile_size_plus . 'px) and (max-width: ' . $tablet_size . 'px) {
				#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs {
					max-height: ' . $image_height_css_tablet . 'px;
				}
			}';
		}
		if ( $image_height_css_mobile ) {
			$dynamic_css .= '
			@media screen and  (max-width: ' . $mobile_size . 'px) {#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs {
				max-height: ' . $image_height_css_mobile . 'px;
				}
			}';
		}
	} else {
		$dynamic_css .= '
		#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs .swiper-slide{
			margin-top: ' . $slide_margin_horizontal . 'px;
		}';
		if ( 'top' === $thumbnail_position ) {
			$dynamic_css .= '
			#wpcpro-wrapper-' . $carousel_id . '.wpcpro-thumbnail-slider .wpcp-carousel-section.wpcp-thumbnail-slider {
				display: flex;
				flex-direction: column;
			}
			#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-slider{
				order: 1;
			}
			#wpcpro-wrapper-' . $carousel_id . ' .wpcp-thumbnail-slider .wpcpro-gallery-thumbs{
				order: -1;
			}
			#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs .swiper-slide{
				margin-top: 0;
				margin-bottom: ' . $slide_margin_horizontal . 'px;
			}
			';
		}
	}

	/**
	 * Thumbnails Hide On Mobile CSS.
	 */
	if ( $thumbnails_hide_on_mobile ) {
		$dynamic_css .= '
		@media screen and (max-width: 479px) {
			#wpcpro-wrapper-' . $carousel_id . ' #sp-wp-carousel-pro-id-' . $carousel_id . '.wpcp-carousel-section.sp-wpcp-' . $carousel_id . ' .wpcpro-gallery-thumbs {
				display: none;
			}
			#wpcpro-wrapper-' . $carousel_id . '.wpcpro-thumbnail-slider .wpcp-thumbnail-slider {
				justify-content: center;
			}
		}';
	}
}
