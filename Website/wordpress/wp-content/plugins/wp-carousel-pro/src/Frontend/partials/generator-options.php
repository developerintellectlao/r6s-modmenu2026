<?php
// Carousel type.
$carousel_type    = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
$carousel_mode    = isset( $shortcode_data['wpcp_carousel_mode'] ) ? $shortcode_data['wpcp_carousel_mode'] : 'standard';
$lazy_load_img    = apply_filters( 'wpcp_lazy_load_img', WPCAROUSEL_URL . 'Frontend/css/spinner.svg' );
$wpcp_post_detail = isset( $shortcode_data['wpcp_post_detail_position'] ) ? $shortcode_data['wpcp_post_detail_position'] : '';
// Image settings.
$image_orderby         = isset( $shortcode_data['wpcp_image_order_by'] ) ? $shortcode_data['wpcp_image_order_by'] : '';
$post_per_page         = isset( $shortcode_data['post_per_page'] ) ? (int) $shortcode_data['post_per_page'] : 10;
$show_slide_image      = isset( $shortcode_data['show_image'] ) ? $shortcode_data['show_image'] : '';
$show_img_title        = isset( $shortcode_data['wpcp_post_title'] ) ? $shortcode_data['wpcp_post_title'] : '';
$show_img_caption      = isset( $shortcode_data['wpcp_image_caption'] ) ? $shortcode_data['wpcp_image_caption'] : '';
$image_title_source    = isset( $shortcode_data['wpcp_image_title_source'] ) ? $shortcode_data['wpcp_image_title_source'] : 'caption';
$show_img_description  = isset( $shortcode_data['wpcp_image_desc'] ) ? $shortcode_data['wpcp_image_desc'] : '';
$img_desc_display_type = isset( $shortcode_data['img_desc_display_type'] ) ? $shortcode_data['img_desc_display_type'] : 'full';
$img_desc_word_limit   = isset( $shortcode_data['img_desc_word_limit'] ) ? $shortcode_data['img_desc_word_limit'] : '30';
$img_desc_read_more    = isset( $shortcode_data['img_desc_read_more'] ) ? $shortcode_data['img_desc_read_more'] : true;
$img_readmore_label    = isset( $shortcode_data['img_readmore_label'] ) ? $shortcode_data['img_readmore_label'] : 'Read More';
$is_variable_width     = isset( $shortcode_data['_variable_width'] ) ? $shortcode_data['_variable_width'] : '';
$variable_width        = $is_variable_width ? 'true' : 'false';
$lazy_load_image       = isset( $shortcode_data['wpcp_image_lazy_load'] ) ? $shortcode_data['wpcp_image_lazy_load'] : 'false';
$lazy_load_image       = ( '1' === $lazy_load_image || 'ondemand' === $lazy_load_image ) && 'true' !== $variable_width ? 'ondemand' : 'false';
$carousel_row          = ! empty( $shortcode_data['wpcp_carousel_row'] ) ? $shortcode_data['wpcp_carousel_row'] : array(
	'lg_desktop' => '2',
	'desktop'    => '2',
	'laptop'     => '2',
	'tablet'     => '2',
	'mobile'     => '2',
);
$multi_row             = 'multi-row' === $carousel_mode ? true : false;

if ( 'mix-content' === $carousel_type || 'external-carousel' === $carousel_type || 'content-carousel' === $carousel_type || $multi_row ) {
	$lazy_load_image = 'false';
}
$light_box = isset( $shortcode_data['_image_light_box'] ) ? $shortcode_data['_image_light_box'] : false;
$group     = isset( $shortcode_data['_image_group'] ) ? $shortcode_data['_image_group'] : '';

$_image_title_att      = isset( $shortcode_data['_image_title_attr'] ) ? $shortcode_data['_image_title_attr'] : '';
$show_image_title_attr = ( $_image_title_att ) ? 'true' : 'false';
$image_link_show       = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_show'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_show'] : 'l_box';
$link_target           = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_link_open_target'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_link_open_target'] : '_blank';
$image_sizes           = isset( $shortcode_data['wpcp_image_sizes'] ) ? $shortcode_data['wpcp_image_sizes'] : '';
$image_width           = isset( $shortcode_data['wpcp_image_crop_size']['top'] ) ? $shortcode_data['wpcp_image_crop_size']['top'] : '';
$image_height          = isset( $shortcode_data['wpcp_image_crop_size']['right'] ) ? $shortcode_data['wpcp_image_crop_size']['right'] : '';
$do_image_crop         = isset( $shortcode_data['wpcp_image_crop_size']['style'] ) ? $shortcode_data['wpcp_image_crop_size']['style'] : '';
$image_crop            = 'Hard-crop' === $do_image_crop ? true : false;

$wpcp_layout        = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
$wpcp_content_style = isset( $shortcode_data['wpcp_content_style'] ) ? $shortcode_data['wpcp_content_style'] : '';
$show_2x_image      = isset( $shortcode_data['load_2x_image'] ) ? $shortcode_data['load_2x_image'] : '';
if ( 'image-carousel' === $carousel_type || 'mix-content' === $carousel_type || 'external-carousel' === $carousel_type ) {
	// Lightbox meta options.
	$show_lightbox_image_counter      = isset( $shortcode_data['wpcp_image_counter'] ) ? $shortcode_data['wpcp_image_counter'] : true;
	$show_lightbox_image_caption      = isset( $shortcode_data['wpcp_l_box_image_caption'] ) ? $shortcode_data['wpcp_l_box_image_caption'] : '';
	$show_lightbox_image_desc         = isset( $shortcode_data['l_box_desc'] ) ? $shortcode_data['l_box_desc'] : false;
	$show_lightbox_image_thumb        = isset( $shortcode_data['wpcp_thumbnails_gallery'] ) ? $shortcode_data['wpcp_thumbnails_gallery'] : true;
	$l_box_thumb_visibility           = isset( $shortcode_data['l_box_thumb_visibility'] ) ? $shortcode_data['l_box_thumb_visibility'] : true;
	$show_lightbox_download_button    = isset( $shortcode_data['l_box_download_button'] ) ? $shortcode_data['l_box_download_button'] : true;
	$show_lightbox_slideshow_button   = isset( $shortcode_data['l_box_slideshow_button'] ) ? $shortcode_data['l_box_slideshow_button'] : true;
	$show_lightbox_social_button      = isset( $shortcode_data['l_box_social_button'] ) ? $shortcode_data['l_box_social_button'] : true;
	$show_lightbox_full_screen_button = isset( $shortcode_data['l_box_full_screen_button'] ) ? $shortcode_data['l_box_full_screen_button'] : true;
	$l_box_protect_image              = isset( $shortcode_data['l_box_protect_image'] ) ? $shortcode_data['l_box_protect_image'] : false;
	$l_box_keyboard_nav               = isset( $shortcode_data['l_box_keyboard_nav'] ) ? $shortcode_data['l_box_keyboard_nav'] : true;
	$l_box_loop                       = isset( $shortcode_data['l_box_loop'] ) ? $shortcode_data['l_box_loop'] : true;
	$l_box_hover_img_on_mobile        = isset( $shortcode_data['l_box_hover_img_on_mobile'] ) ? $shortcode_data['l_box_hover_img_on_mobile'] : false;
	$is_mobile                        = $l_box_hover_img_on_mobile && wp_is_mobile() ? true : false;
	$l_box_autoplay                   = isset( $shortcode_data['l_box_autoplay'] ) ? $shortcode_data['l_box_autoplay'] : false;
	$l_box_outside_close              = isset( $shortcode_data['l_box_outside_close'] ) ? $shortcode_data['l_box_outside_close'] : true;
	$l_box_autoplay_speed             = ! empty( $shortcode_data['l_box_autoplay_speed'] ) && is_numeric( $shortcode_data['l_box_autoplay_speed'] ) ? $shortcode_data['l_box_autoplay_speed'] : 4000;
	$l_box_sliding_effect             = isset( $shortcode_data['l_box_sliding_effect'] ) ? $shortcode_data['l_box_sliding_effect'] : 'fade';
	$l_box_open_close_effect          = isset( $shortcode_data['l_box_open_close_effect'] ) ? $shortcode_data['l_box_open_close_effect'] : 'fade';
	$l_box_zoom_button                = isset( $shortcode_data['l_box_zoom_button'] ) ? $shortcode_data['l_box_zoom_button'] : 'zoom';
	$show_l_box_img_sharper_on_retina = isset( $shortcode_data['l_box_img_sharper_on_retina'] ) ? $shortcode_data['l_box_img_sharper_on_retina'] : false;
	$l_box_close_button               = isset( $shortcode_data['l_box_close_button'] ) ? $shortcode_data['l_box_close_button'] : 'close';
	$l_box_icon_position              = isset( $shortcode_data['l_box_icon_position'] ) ? $shortcode_data['l_box_icon_position'] : 'middle';
	$l_box_icon_style                 = isset( $shortcode_data['l_box_icon_style'] ) ? $shortcode_data['l_box_icon_style'] : 'search';

	switch ( $l_box_icon_style ) {
		case 'plus':
			$l_box_icon = '<i class="fa fa-plus"></i>';
			break;
		case 'search':
			$l_box_icon = '<i class="fa fa-search"></i>';
			break;
		case 'zoom':
			$l_box_icon = '<i class="fa fa-search-plus"></i>';
			break;
		case 'eye':
			$l_box_icon = '<i class="fa fa-eye"></i>';
			break;
		case 'info':
			$l_box_icon = '<i class="fa fa-info"></i>';
			break;
		case 'expand':
			$l_box_icon = '<i class="fa fa-expand"></i>';
			break;
		case 'arrow_alt':
			$l_box_icon = '<i class="fa fa-arrows-alt"></i>';
			break;
		case 'plus_square':
			$l_box_icon = '<i class="fa fa-plus-square-o"></i>';
			break;
	}
	$l_box_zoom_button      = $l_box_zoom_button ? 'zoom' : '';
	$show_l_box_img_caption = $show_lightbox_image_caption ? true : false;
	$l_box_thumb_visibility = $l_box_thumb_visibility ? 'true' : 'false';
	$show_img_thumb         = $show_lightbox_image_thumb ? 'thumbs' : '';
	$show_slideshow         = $show_lightbox_slideshow_button ? 'slideShow' : '';
	$show_social_share      = $show_lightbox_social_button ? 'share' : '';
	$show_full_screen       = $show_lightbox_full_screen_button ? 'fullScreen' : '';
	$show_download_button   = $show_lightbox_download_button ? 'download' : '';
	$l_box_close_button     = $l_box_close_button ? 'close' : '';
}
	$thumbHeight = isset( $shortcode_data['thumbHeight']['all'] ) ? $shortcode_data['thumbHeight']['all'] : '150';

	$grid_column       = '';
	$column_number     = isset( $shortcode_data['wpcp_number_of_columns'] ) ? $shortcode_data['wpcp_number_of_columns'] : '';
	$column_lg_desktop = isset( $column_number['lg_desktop'] ) && ! empty( $column_number['lg_desktop'] ) ? $column_number['lg_desktop'] : '5';
	$column_desktop    = isset( $column_number['desktop'] ) && ! empty( $column_number['desktop'] ) ? $column_number['desktop'] : '4';
	$column_laptop     = isset( $column_number['laptop'] ) && ! empty( $column_number['laptop'] ) ? $column_number['laptop'] : '3';
	$column_tablet     = isset( $column_number['tablet'] ) && ! empty( $column_number['tablet'] ) ? $column_number['tablet'] : '2';
	$column_mobile     = isset( $column_number['mobile'] ) && ! empty( $column_number['mobile'] ) ? $column_number['mobile'] : '1';
	$grid_column       = "wpcpro-col-xs-$column_mobile wpcpro-col-sm-$column_tablet wpcpro-col-md-$column_laptop wpcpro-col-lg-$column_desktop wpcpro-col-xl-$column_lg_desktop";
if ( 'justified' === $wpcp_layout ) {
	$grid_column = 'wpcp-item-wrapper';
}

$slider_animation = isset( $shortcode_data['wpcp_slider_animation'] ) && 'standard' === $carousel_mode ? $shortcode_data['wpcp_slider_animation'] : 'slider';
if ( 'carousel' === $wpcp_layout ) {
	$grid_column = ( 'ticker' !== $carousel_mode ) ? ' swiper-slide' : 'wpcp-ticker';
	if ( 'fade' === $slider_animation || 'cube' === $slider_animation || 'flip' === $slider_animation || 'kenburn' === $slider_animation ) {
		$grid_column = ' single-item-fade';
	}
}
if ( 'slider' === $wpcp_layout ) {
	$slider_animation = isset( $shortcode_data['wpcp_slider_style'] ) ? $shortcode_data['wpcp_slider_style'] : 'slide';
	$grid_column      = ' swiper-slide';
	if ( 'fade' === $slider_animation || 'cube' === $slider_animation || 'flip' === $slider_animation || 'kenburn' === $slider_animation ) {
		$grid_column = ' single-item-fade';
	}
}
if ( 'thumbnails-slider' === $wpcp_layout ) {
	$grid_column = ' swiper-slide';
}
// Overlay content animation.
$wpcp_overlay_animation  = isset( $shortcode_data['wpcp_overlay_animation'] ) ? $shortcode_data['wpcp_overlay_animation'] : 'none';
$wpcp_overlay_visibility = isset( $shortcode_data['wpcp_overlay_visibility'] ) ? $shortcode_data['wpcp_overlay_visibility'] : '';

$animation_class = '';
// Apply animation class if overlay is set to 'on_hover', and make sure the content style is not any 'default' or 'content_box' selected.
if ( 'on_hover' === $wpcp_overlay_visibility && 'none' !== $wpcp_overlay_animation && ( 'default' !== $wpcp_content_style && 'content_box' !== $wpcp_content_style ) ) {
	$animation_class = 'animate__animated ' . $wpcp_overlay_animation;
}
