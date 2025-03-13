<?php
/**
 * Metabox config file.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;
if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.

//
// Create a metabox.
//
SP_WPCP_Framework::createMetabox(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'        => __( 'Shortcode Section', 'wp-carousel-pro' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'theme'        => 'light',
		'class'        => 'sp_wpcp_shortcode_generator',
		'nav'          => 'inline',
	)
);

//
// General section.
//
SP_WPCP_Framework::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'General Settings', 'wp-carousel-pro' ),
		'icon'   => 'wpcp-icon-lightbox-general',
		'fields' => array(
			array(
				'id'       => 'wpcp_layout',
				'class'    => 'wpcp_layout',
				'type'     => 'image_select',
				'title'    => __( 'Layout Preset', 'wp-carousel-pro' ),
				'subtitle' => __( 'Choose a layout preset.', 'wp-carousel-pro' ),
				'options'  => array(
					'carousel'          => array(
						'image'           => WPCAROUSEL_URL . 'Admin/img/layout/carousel.svg',
						'text'            => __( 'Carousel', 'wp-carousel-pro' ),
						'option_demo_url' => 'https://wpcarousel.io/simple-image-carousel/',
					),
					'slider'            => array(
						'image'           => WPCAROUSEL_URL . 'Admin/img/layout/slider.svg',
						'text'            => __( 'Slider', 'wp-carousel-pro' ),
						'option_demo_url' => 'https://wpcarousel.io/slider-sliding-effects/',
					),
					'thumbnails-slider' => array(
						'image'           => WPCAROUSEL_URL . 'Admin/img/layout/thumbnails-slider.svg',
						'text'            => __( 'Thumbs Slider', 'wp-carousel-pro' ),
						'option_demo_url' => 'https://wpcarousel.io/thumbnails-slider/',
					),
					'grid'              => array(
						'image'           => WPCAROUSEL_URL . 'Admin/img/layout/grid.svg',
						'text'            => __( 'Grid', 'wp-carousel-pro' ),
						'option_demo_url' => 'https://wpcarousel.io/grid/',
					),
					'tiles'             => array(
						'image'           => WPCAROUSEL_URL . 'Admin/img/layout/tiles.svg',
						'text'            => __( 'Tiles', 'wp-carousel-pro' ),
						'option_demo_url' => 'https://wpcarousel.io/image-tiles/',
					),
					'masonry'           => array(
						'image'           => WPCAROUSEL_URL . 'Admin/img/layout/masonry.svg',
						'text'            => __( 'Masonry', 'wp-carousel-pro' ),
						'option_demo_url' => 'https://wpcarousel.io/masonry/',
					),
					'justified'         => array(
						'image'           => WPCAROUSEL_URL . 'Admin/img/layout/justified.svg',
						'text'            => __( 'Justified', 'wp-carousel-pro' ),
						'option_demo_url' => 'https://wpcarousel.io/justified/',
					),
				),
				'default'  => 'carousel',
			),
			array(
				'id'         => 'wpcp_thumbnail_position',
				'type'       => 'image_select',
				'class'      => 'wpcp_thumbnail_position',
				'title'      => __( 'Thumbnails Position', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Choose a thumbnails position.', 'wp-carousel-pro' ),
				'options'    => array(
					'bottom' => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/thumbnail_position_bottom.svg',
						'text'  => __( 'Bottom', 'wp-carousel-pro' ),
					),
					'top'    => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/thumbnail_position_top.svg',
						'text'  => __( 'Top', 'wp-carousel-pro' ),
					),
					'left'   => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/thumbnail_position_left.svg',
						'text'  => __( 'Left', 'wp-carousel-pro' ),
					),
					'right'  => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/thumbnail_position_right.svg',
						'text'  => __( 'Right', 'wp-carousel-pro' ),
					),
				),
				'default'    => 'bottom',
				'dependency' => array( 'wpcp_layout', '==', 'thumbnails-slider', true ),
			),
			array(
				'id'         => 'wpcp_slider_style',
				'class'      => 'wpcp_slider_style',
				'type'       => 'image_select',
				'title'      => __( 'Slider Style', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Choose a slider style.', 'wp-carousel-pro' ),
				'options'    => array(
					'normal'    => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/layout/slider.svg',
						'text'  => __( 'Slide', 'wp-carousel-pro' ),
					),
					'fade'      => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/fadeslide.png',
						'text'  => __( 'Fade', 'wp-carousel-pro' ),
					),
					'kenburn'   => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/Kenburns.svg',
						'text'  => __( 'Ken Burns', 'wp-carousel-pro' ),
					),
					'shaders'   => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/shader.svg',
						'text'  => __( 'Shaders', 'wp-carousel-pro' ),
					),
					'slicer'    => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/slicer.svg',
						'text'  => __( 'Slicer', 'wp-carousel-pro' ),
					),
					'shutters'  => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/shutters.svg',
						'text'  => __( 'Shutters', 'wp-carousel-pro' ),
					),
					'fashion'   => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/fashion.svg',
						'text'  => __( 'Fashion', 'wp-carousel-pro' ),
					),
					'coverflow' => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/coverflow.svg',
						'text'  => __( 'Coverflow', 'wp-carousel-pro' ),
					),
					'flip'      => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/flip.svg',
						'text'  => __( 'Flip', 'wp-carousel-pro' ),
					),
					'cube'      => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/cube.svg',
						'text'  => __( 'Cube', 'wp-carousel-pro' ),
					),
				),
				'default'    => 'normal',
				'dependency' => array( 'wpcp_layout', '==', 'slider' ),
			),
			array(
				'id'         => 'shaders_effect',
				'type'       => 'select',
				'title'      => __( 'Slider Effect', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Select a slider effect.', 'wp-carousel-pro' ),
				'options'    => array(
					'random'         => __( 'Random', 'wp-carousel-pro' ),
					'dots'           => __( 'Dots', 'wp-carousel-pro' ),
					'flyeye'         => __( 'Flyeye', 'wp-carousel-pro' ),
					'morph-x'        => __( 'Morph X', 'wp-carousel-pro' ),
					'morph-y'        => __( 'Morph Y', 'wp-carousel-pro' ),
					'page-curl'      => __( 'Page Curl', 'wp-carousel-pro' ),
					'peel-x'         => __( 'Peel X', 'wp-carousel-pro' ),
					'peel-y'         => __( 'Peel Y', 'wp-carousel-pro' ),
					'polygons-fall'  => __( 'Polygons Fall', 'wp-carousel-pro' ),
					'polygons-morph' => __( 'Polygons Morph', 'wp-carousel-pro' ),
					'polygons-wind'  => __( 'Polygons Wind', 'wp-carousel-pro' ),
					'pixelize'       => __( 'Pixelize', 'wp-carousel-pro' ),
					'ripple'         => __( 'Sripple', 'wp-carousel-pro' ),
					'shutters'       => __( 'Shutters', 'wp-carousel-pro' ),
					'slices'         => __( 'Slices', 'wp-carousel-pro' ),
					'squares'        => __( 'Squares', 'wp-carousel-pro' ),
					'stretch'        => __( 'Stretch', 'wp-carousel-pro' ),
					'wave-x'         => __( 'Wave X', 'wp-carousel-pro' ),
					'wind'           => __( 'Wind', 'wp-carousel-pro' ),
				),
				'default'    => 'random',
				'dependency' => array( 'wpcp_layout|wpcp_slider_style', '==|==', 'slider|shaders' ),
			),
			array(
				'id'         => 'wpcp_carousel_mode',
				'class'      => 'wpcp_carousel_mode wpcp_thumbnail_position',
				'type'       => 'image_select',
				'title'      => __( 'Carousel Style', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Choose a carousel style.', 'wp-carousel-pro' ),
				'options'    => array(
					'standard'    => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/standard.svg',
						'text'  => __( 'Standard', 'wp-carousel-pro' ),
					),
					'ticker'      => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/ticker.svg',
						'text'  => __( 'Ticker', 'wp-carousel-pro' ),
					),
					'center'      => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/center.svg',
						'text'  => __( 'Center', 'wp-carousel-pro' ),
					),
					'multi-row'   => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/multi-row.svg',
						'text'  => __( 'Multi Row', 'wp-carousel-pro' ),
					),
					'3d-carousel' => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/3d.svg',
						'text'  => __( '3D Carousel', 'wp-carousel-pro' ),
					),
					'panorama'    => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/panorama.svg',
						'text'  => __( 'Panorama', 'wp-carousel-pro' ),
					),
					'triple'      => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/triple.svg',
						'text'  => __( 'Triple', 'wp-carousel-pro' ),
					),
					'spring'      => array(
						'image' => WPCAROUSEL_URL . 'Admin/img/carousel-mode/spring.svg',
						'text'  => __( 'Spring', 'wp-carousel-pro' ),
					),
				),
				'default'    => 'standard',
				'dependency' => array( 'wpcp_layout', '==', 'carousel' ),
			),
			array(
				'id'         => 'wpcp_carousel_orientation',
				'type'       => 'button_set',
				'title'      => __( 'Carousel Orientation', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Choose a carousel orientation.', 'wp-carousel-pro' ),
				'title_help' => __(
					'<div class="sp_wpcp-info-label">Carousel Orientation</div><div class="sp_wpcp-short-content">Choose the carousel slide movement:<br>
									<strong style="font-weight: 700;">Horizontal</strong>: If you want the slides to transition horizontally, select <b>Horizontal</b>.<br>
									<strong style="font-weight: 700;">Vertical</strong>:  If you want the slides to transition vertically, select <b>Vertical</b></div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-carousel-orientation/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/carousel-orientations/" target="_blank">Live Demo</a>',
					'wp-carousel-pro'
				),
				'options'    => array(
					'horizontal' => __( 'Horizontal', 'wp-carousel-pro' ),
					'vertical'   => __( 'Vertical', 'wp-carousel-pro' ),
				),
				// 'radio'      => true,
				'default'    => 'horizontal',
				'dependency' => array( 'wpcp_layout|wpcp_carousel_mode', '==|any', 'carousel|standard,ticker,center', true ),
			),
			array(
				'id'         => 'wpcp_slider_animation',
				'class'      => 'wpcp_slider_animation',
				'type'       => 'select',
				'title'      => __( 'Slide Effect', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Select a slide transition effect.', 'wp-carousel-pro' ),
				'title_help' => __(
					'<div class="sp_wpcp-info-label">Slide Effect</div><div class="sp_wpcp-short-content">Enhance your slide transition with charming Slide Effects to add elegance and dynamic motion to your slides.</div>',
					'wp-carousel-pro'
				),
				'options'    => array(
					'slide'     => __( 'Slide', 'wp-carousel-pro' ),
					'fade'      => __( 'Fade', 'wp-carousel-pro' ),
					'coverflow' => __( 'Coverflow', 'wp-carousel-pro' ),
					'flip'      => __( 'Flip', 'wp-carousel-pro' ),
					'cube'      => __( 'Cube', 'wp-carousel-pro' ),
					'kenburn'   => __( 'Ken Burns', 'wp-carousel-pro' ),
				),
				'default'    => 'slide',
				'attributes' => array(
					'data-depend-id' => 'slider_animation',
				),
				'dependency' => array( 'wpcp_layout|wpcp_carousel_mode|wpcp_carousel_orientation', '==|==|==', 'carousel|standard|horizontal', true ),
			),

			array(
				'id'         => 'thumbnails_orientation',
				'type'       => 'button_set',
				'title'      => __( 'Slider Orientation', 'wp-carousel-pro' ),
				'title_help' => __(
					'<div class="sp_wpcp-info-label">Slider Orientation</div><div class="sp_wpcp-short-content">
					<strong style="font-weight: 700;">Horizontal</strong>: If you want the slides to transition horizontally, select <b>Horizontal</b>.<br>
					<strong style="font-weight: 700;">Vertical</strong>:  If you want the slides to transition vertically, select <b>Vertical</b></div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-carousel-orientation/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/carousel-orientations/" target="_blank">Live Demo</a>',
					'wp-carousel-pro'
				),
				'options'    => array(
					'horizontal' => __( 'Horizontal', 'wp-carousel-pro' ),
					'vertical'   => __( 'Vertical', 'wp-carousel-pro' ),
				),
				'default'    => 'horizontal',
				'dependency' => array( 'wpcp_layout', '==', 'thumbnails-slider' ),
			),
			array(
				'id'         => 'wpcp_carousel_row',
				'type'       => 'column',
				'title'      => __( 'Carousel Row', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set number of carousel row on devices.', 'wp-carousel-pro' ),
				'lg_desktop' => true,
				'desktop'    => true,
				'laptop'     => true,
				'tablet'     => true,
				'mobile'     => true,
				'default'    => array(
					'lg_desktop' => '2',
					'desktop'    => '2',
					'laptop'     => '2',
					'tablet'     => '2',
					'mobile'     => '2',
				),
				'dependency' => array( 'wpcp_carousel_mode|wpcp_layout', '==|==', 'multi-row|carousel', true ),
			),
			array(
				'id'         => 'wpcp_number_of_columns',
				'class'      => 'wpcp_number_of_columns standard_width_of_spacing_field',
				'type'       => 'column',
				'title'      => __( 'Columns', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set number of column on devices.', 'wp-carousel-pro' ),
				'title_help' => '<i class="fa fa-television"></i> LARGE DESKTOP - Screens larger than 1280px.<br/>
							<i class="fa fa-desktop"></i> DESKTOP - Screens smaller than 1280px.<br/>
							<i class="fa fa-laptop"></i> LAPTOP - Screens smaller than 980px.<br/>
							<i class="fa fa-tablet"></i> TABLET - Screens smaller than 736px.<br/>
							<i class="fa fa-mobile"></i> MOBILE - Screens smaller than 480px.<br/>',
				'default'    => array(
					'lg_desktop' => '5',
					'desktop'    => '4',
					'laptop'     => '3',
					'tablet'     => '2',
					'mobile'     => '1',
				),
				'min'        => '1',
				'dependency' => array( 'wpcp_carousel_mode|wpcp_layout', 'not-any|not-any', 'ticker,triple|slider,justified' ),
			),
			array(
				'id'         => 'wpcp_number_of_columns_ticker',
				'type'       => 'column',
				'title'      => __( 'Columns', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set maximum & minimum column number.', 'wp-carousel-pro' ),
				'desktop'    => false,
				'laptop'     => false,
				'tablet'     => false,
				'default'    => array(
					'lg_desktop' => '5',
					'mobile'     => '2',
				),
				'dependency' => array( 'wpcp_carousel_mode|wpcp_layout', '==|==', 'ticker|carousel' ),
			),
			array(
				'id'         => 'wpcp_slide_margin',
				'class'      => 'wpcp-slide-margin standard_width_of_spacing_field',
				'type'       => 'spacing',
				'title'      => __( 'Space', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set a space between the items.', 'wp-carousel-pro' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . WPCAROUSEL_URL . 'Admin/img/help-visuals/space.svg" alt="Space"></div><div class="sp_wpcp-info-label">' . __( 'Space', 'wp-carousel-pro' ) . '</div>',
				'right'      => true,
				'top'        => true,
				'left'       => false,
				'bottom'     => false,
				'right_text' => __( 'Vertical Gap', 'wp-carousel-pro' ),
				'top_text'   => __( 'Gap', 'wp-carousel-pro' ),
				'right_icon' => '<i class="fa fa-arrows-v"></i>',
				'top_icon'   => '<i class="fa fa-arrows-h"></i>',
				'unit'       => true,
				'units'      => array( 'px' ),
				'default'    => array(
					'top'   => '20',
					'right' => '20',
				),
				'dependency' => array( 'wpcp_carousel_mode|wpcp_layout', '!=|!=', 'triple|slider' ),
			),
			array(
				'id'         => 'thumbnails_hide_on_mobile',
				'type'       => 'checkbox',
				'title'      => __( 'Hide Thumbnails on Mobile', 'wp-carousel-pro' ),
				'default'    => false,
				'dependency' => array( 'wpcp_layout', '==', 'thumbnails-slider' ),
			),
			array(
				'id'         => 'thumbnails_height_type',
				'type'       => 'select',
				'title'      => __( 'Thumbnails Height', 'wp-carousel-pro' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . WPCAROUSEL_URL . 'Admin/img/help-visuals/thumbnail-height.svg" alt="Thumbnails Height"></div><div class="sp_wpcp-info-label">' . __( 'Thumbnails Height', 'wp-carousel-pro' ) . '</div>',
				'options'    => array(
					'auto'   => __( 'Auto', 'wp-carousel-pro' ),
					'static' => __( 'Static', 'wp-carousel-pro' ),
				),
				'default'    => 'static',
				'dependency' => array( 'wpcp_layout|wpcp_thumbnail_position', '==|any', 'thumbnails-slider|top,bottom' ),
			),
			array(
				'id'         => 'thumbHeight',
				'type'       => 'spacing',
				'title'      => __( 'Thumbnails Static Height', 'wp-carousel-pro' ),
				'all'        => true,
				'all_text'   => '<i class="fa fa-arrows-v"></i>',
				'unit'       => true,
				'units'      => array( 'px', '%' ),
				'default'    => array(
					'all'  => '140',
					'unit' => 'px',
				),
				'dependency' => array( 'wpcp_layout|wpcp_thumbnail_position|thumbnails_height_type', '==|any|==', 'thumbnails-slider|top,bottom|static' ),
			),
			array(
				'id'         => 'rowHeight',
				'type'       => 'spacing',
				'class'      => 'standard_width_of_spacing_field',
				'title'      => __( 'Row Height', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set a row height for justified layout.', 'wp-carousel-pro' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . WPCAROUSEL_URL . 'Admin/img/help-visuals/row-height.svg" alt="Row Height"></div><div class="sp_wpcp-info-label">' . __( 'Row Height', 'wp-carousel-pro' ) . '</div>',
				'all'        => true,
				'all_text'   => '<i class="fa fa-arrows-v"></i>',
				'unit'       => true,
				'units'      => array( 'px' ),
				'default'    => array(
					'all' => '250',
				),
				'dependency' => array( 'wpcp_layout', '==', 'justified' ),
			),
			array(
				'id'              => 'wpcp_slide_width',
				'type'            => 'spacing',
				'class'           => 'standard_width_of_spacing_field',
				'title'           => __( 'Items Width', 'wp-carousel-pro' ),
				'subtitle'        => __( 'Set a width for the each items.', 'wp-carousel-pro' ),
				'all'             => true,
				'all_text'        => '<i class="fa fa-arrows-h"></i>',
				'all_placeholder' => 'width',
				'default'         => array(
					'all' => '250',
				),
				'units'           => array(
					'px',
				),
				'attributes'      => array(
					'min' => 0,
				),
				'dependency'      => array( 'wpcp_carousel_mode|wpcp_layout', '==|==', 'ticker|carousel' ),
			),

			array(
				'id'         => 'wpcp_image_center_mode_padding',
				'type'       => 'column',
				'title'      => __( 'Center Padding', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set center mode padding.', 'wp-carousel-pro' ),
				'unit'       => true,
				'units'      => array(
					'px',
				),
				'default'    => array(
					'lg_desktop' => '100',
					'desktop'    => '100',
					'laptop'     => '70',
					'tablet'     => '50',
					'mobile'     => '40',
				),
				'dependency' => array( 'wpcp_carousel_mode|wpcp_layout', '==|==', 'center|carousel' ),
			),

			array(
				'id'                 => 'slider_height',
				'title'              => __( 'Slider Height', 'wp-carousel-pro' ),
				'subtitle'           => __( 'Set a height for Shaders, Slicer and Shutters effect.', 'wp-carousel-pro' ),
				'type'               => 'dimensions_advanced',
				'class'              => 'dimensions_advanced_field',
				'top_icon'           => '<i class="fa fa-desktop"></i>',
				'right_icon'         => '<i class="fa fa-laptop"></i>',
				'bottom_icon'        => '<i class="fa fa-tablet"></i>',
				'left_icon'          => '<i class="fa fa-mobile"></i>',
				'top_placeholder'    => 'height',
				'right_placeholder'  => 'height',
				'bottom_placeholder' => 'height',
				'left_placeholder'   => 'height',
				'lg_desktop'         => false,
				'color'              => false,
				'style'              => false,
				'styles'             => false,

				'default'            => array(
					'top'    => 600,
					'right'  => 500,
					'bottom' => 400,
					'left'   => 300,
					'unit'   => 'px',
				),
				'dependency'         => array( 'wpcp_layout|wpcp_slider_style', '==|any', 'slider|shaders,slicer,shutters', true ),
			),
			array(
				'id'     => 'wpcp_click_action_type_group',
				'class'  => 'wp-carousel-click-action-type',
				'type'   => 'fieldset',
				'fields' => array(
					array(
						'id'         => 'wpcp_logo_link_show',
						'type'       => 'image_select',
						'class'      => 'wpcp_logo_link_show_class',
						'title'      => __( 'Click Action Type', 'wp-carousel-pro' ),
						'options'    => array(
							'l_box' => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/lightbox.svg',
							),
							'link'  => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/url.svg',
							),
							'none'  => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/disabled.svg',
							),
						),
						'subtitle'   => __( 'Set a click action type for the items.', 'wp-carousel-pro' ),
						'default'    => 'l_box',
						'dependency' => array( 'wpcp_carousel_type', 'any', 'image-carousel,post-carousel,external-carousel', true ),
					),
					array(
						'id'         => 'wpcp_logo_link_nofollow',
						'type'       => 'checkbox',
						'class'      => 'wpcp_logo_link_nofollow',
						'title'      => __( 'Nofollow', 'wp-carousel-pro' ),
						'default'    => false,
						'dependency' => array( 'wpcp_carousel_type|wpcp_logo_link_show', 'any|not-any', 'image-carousel,post-carousel,external-carousel|none,l_box', true ),
					),
					array(
						'id'         => 'wpcp_link_open_target',
						'type'       => 'select',
						'class'      => 'wpcp_link_open_target',
						'title'      => __( 'Target', 'wp-carousel-pro' ),
						'options'    => array(
							'_self'  => __( 'Current Tab', 'wp-carousel-pro' ),
							'_blank' => __( 'New Tab', 'wp-carousel-pro' ),
						),
						'default'    => '_blank',
						'dependency' => array( 'wpcp_carousel_type|wpcp_logo_link_show', 'any|not-any', 'image-carousel,post-carousel,external-carousel|none,l_box', true ),
					),
				),
			),
			array(
				'id'         => 'wpcp_image_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set an order by option.', 'wp-carousel-pro' ),
				'options'    => array(
					'menu_order' => __( 'Drag & Drop', 'wp-carousel-pro' ),
					'rand'       => __( 'Random', 'wp-carousel-pro' ),
				),
				'default'    => 'menu_order',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'image-carousel,video-carousel,content-carousel,audio_carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Select an order by option.', 'wp-carousel-pro' ),
				'options'    => array(
					'ID'         => __( 'ID', 'wp-carousel-pro' ),
					'date'       => __( 'Date', 'wp-carousel-pro' ),
					'rand'       => __( 'Random', 'wp-carousel-pro' ),
					'title'      => __( 'Title', 'wp-carousel-pro' ),
					'modified'   => __( 'Modified', 'wp-carousel-pro' ),
					'menu_order' => __( 'Menu Order', 'wp-carousel-pro' ),
					'post__in'   => __( 'Drag & Drop', 'wp-carousel-pro' ),
				),
				'default'    => 'menu_order',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_order',
				'type'       => 'select',
				'title'      => __( 'Order', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Select an order option.', 'wp-carousel-pro' ),
				'options'    => array(
					'ASC'  => __( 'Ascending', 'wp-carousel-pro' ),
					'DESC' => __( 'Descending', 'wp-carousel-pro' ),
				),
				'default'    => 'rand',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_scheduler',
				'type'       => 'switcher',
				'title'      => __( 'Scheduling', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Schedule sliders or galleries to show at specific time intervals.', 'wp-carousel-pro' ),
				'title_help' => __( '<div class="sp_wpcp-info-label">Scheduling</div><div class="sp_wpcp-short-content">Enable the scheduling feature to set the specific date and time for your carousel sliders or galleries to be displayed (perfect for highlighting time-sensitive content).</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-scheduling-feature/" target="_blank">Open Docs</a><a class="sp_wpcp-open-live-demo" href="https://wordpresscarousel.com/scheduled-carousel/" target="_blank">Live Demo</a>', 'wp-carousel-pro' ),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'wp-carousel-pro' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-pro' ),
				'text_width' => 95,
			),
			array(
				'id'         => 'wpcp_date_picker',
				'type'       => 'datetime',
				'title'      => '  ',
				'from_to'    => true,
				'help'       => __( 'Set a time according to your website time zone.', 'wp-carousel-pro' ),
				'dependency' => array( 'wpcp_scheduler', '==', 'true', true ),
			),
			array(
				'id'         => 'wpcp_preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Items will be hidden until page load completed.', 'wp-carousel-pro' ),
				'default'    => true,
				'text_on'    => __( 'Enabled', 'wp-carousel-pro' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-pro' ),
				'text_width' => 95,
			),
			// Pagination.
			array(
				'type'       => 'subheading',
				'content'    => __( 'Load More Pagination', 'wp-carousel-pro' ),
				'dependency' => array( 'wpcp_layout', 'not-any', 'carousel,thumbnails-slider,slider', true ),
			),
			array(
				'id'         => 'wpcp_source_pagination',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-pro' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-pro' ),
				'text_width' => 95,
				'title'      => __( 'Pagination', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Enable to show pagination.', 'wp-carousel-pro' ),
				'default'    => true,
				'dependency' => array( 'wpcp_layout', 'not-any', 'carousel,thumbnails-slider,slider', true ),
			),
			array(
				'id'         => 'wpcp_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Select pagination type.', 'wp-carousel-pro' ),
				'options'    => array(
					'load_more_btn'   => __( 'Load More Button(Ajax)', 'wp-carousel-pro' ),
					'infinite_scroll' => __( 'Load More on Infinite Scroll(Ajax)', 'wp-carousel-pro' ),
					'ajax_number'     => __( 'Number Pagination(Ajax)', 'wp-carousel-pro' ),
				),
				'default'    => 'load_more_btn',
				'dependency' => array( 'wpcp_carousel_type|wpcp_source_pagination|wpcp_layout', 'any|==|not-any', 'image-carousel,content-carousel,mix-content,external-carousel,video-carousel|true|carousel,thumbnails-slider,slider', true ),
			),
			array(
				'id'         => 'wpcp_post_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Select pagination type.', 'wp-carousel-pro' ),
				'options'    => array(
					'load_more_btn'   => __( 'Load More Button', 'wp-carousel-pro' ),
					'infinite_scroll' => __( 'Load More on Infinite Scroll', 'wp-carousel-pro' ),
					'ajax_number'     => __( 'Ajax Number Pagination', 'wp-carousel-pro' ),
					'normal'          => __( 'No Ajax (Normal Pagination)', 'wp-carousel-pro' ),
				),
				'default'    => 'load_more_btn',
				'dependency' => array( 'wpcp_carousel_type|wpcp_source_pagination|wpcp_layout', 'any|==|not-any', 'post-carousel,product-carousel|true|carousel,thumbnails-slider,slider', true ),
			),
			array(
				'id'         => 'load_more_label',
				'type'       => 'text',
				'title'      => __( 'Load More Button Label', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Change load more button label.', 'wp-carousel-pro' ),
				'default'    => 'Load More',
				'dependency' => array( 'wpcp_source_pagination|wpcp_post_pagination_type|wpcp_pagination_type|wpcp_layout', '==|==|==|not-any', 'true|load_more_btn|load_more_btn|carousel,thumbnails-slider,slider', true ),
			),
			array(
				'id'         => 'post_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Items To Show Per Page', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set items to show per page.', 'wp-carousel-pro' ),
				'default'    => '15',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_source_pagination|wpcp_layout', '==|not-any', 'true|carousel,thumbnails-slider,slider', true ),
			),
			array(
				'id'         => 'post_per_click',
				'type'       => 'spinner',
				'title'      => __( 'Items To Show Per Click', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set items to show per click.', 'wp-carousel-pro' ),
				'default'    => '10',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_post_pagination_type|wpcp_source_pagination', 'any|not-any|any|==', 'post-carousel,product-carousel|carousel,thumbnails-slider,slider|load_more_btn,infinite_scroll|true', true ),
			),
			array(
				'id'         => 'item_per_click',
				'type'       => 'spinner',
				'title'      => __( 'Item(s) To Show Per Click', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set item(s) to show per click.', 'wp-carousel-pro' ),
				'default'    => '10',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_source_pagination|wpcp_layout|wpcp_pagination_type|wpcp_carousel_type', '==|not-any|any|any', 'true|carousel,thumbnails-slider,slider|load_more_btn,infinite_scroll|image-carousel,content-carousel,mix-content,external-carousel,video-carousel', true ),
			),
			array(
				'id'         => 'pagination_alignment',
				'type'       => 'button_set',
				'title'      => __( 'Alignment', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Choose pagination alignment.', 'wp-carousel-pro' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="Left"></i>',
					'center' => '<i class="fa fa-align-center" title="Center"></i>',
					'right'  => '<i class="fa fa-align-right" title="Right"></i>',
				),
				'default'    => 'center',
				'dependency' => array( 'wpcp_source_pagination|wpcp_layout', '==|not-any', 'true|carousel,thumbnails-slider,slider', true ),
			),
			array(
				'id'         => 'pagination_color',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'wp-carousel-pro' ),
				'subtitle'   => __( 'Set pagination color.', 'wp-carousel-pro' ),
				'dependency' => array( 'wpcp_source_pagination|wpcp_layout', '==|not-any', 'true|carousel,thumbnails-slider,slider', true ),
				'options'    => array(
					'color'        => __( 'Color', 'wp-carousel-pro' ),
					'hover_color'  => __( 'Hover Color', 'wp-carousel-pro' ),
					'bg'           => __( 'Background', 'wp-carousel-pro' ),
					'hover_bg'     => __( 'Hover Background', 'wp-carousel-pro' ),
					'border'       => __( 'Border', 'wp-carousel-pro' ),
					'hover_border' => __( 'Hover Border', 'wp-carousel-pro' ),
				),
				'default'    => array(
					'color'        => '#5e5e5e',
					'hover_color'  => '#ffffff',
					'bg'           => '#ffffff',
					'hover_bg'     => '#178087',
					'border'       => '#dddddd',
					'hover_border' => '#178087',
				),
			),
		), // Fields array end.
	)
); // End of Upload section.
