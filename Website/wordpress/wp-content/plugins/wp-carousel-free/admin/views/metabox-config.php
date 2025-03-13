<?php
/**
 * The Metabox  configuration
 *
 * @package WP Carousel
 * @subpackage wp-carousel-free/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access pages directly.

//
// Metabox of the uppers section / Upload section.
// Set a unique slug-like ID.
//
$wpcp_carousel_content_source_settings = 'sp_wpcp_upload_options';

$smart_brand_plugin_link = 'smart-brands-for-woocommerce/smart-brands-for-woocommerce.php';
$smart_brand_plugin_data = SP_WPCF::plugin_installation_activation(
	$smart_brand_plugin_link,
	'Install Now',
	'activate_plugin',
	array(
		'ShapedPlugin\SmartBrands\SmartBrands',
		'ShapedPlugin\SmartBrandsPro\SmartBrandsPro',
	),
	'smart-brands-for-woocommerce'
);

// Woo quick view Plugin.
$quick_view_plugin_link = 'woo-quickview/woo-quick-view.php';
$quick_view_plugin_data = SP_WPCF::plugin_installation_activation(
	$quick_view_plugin_link,
	'Install Now',
	'activate_plugin',
	array(
		'SP_Woo_Quick_View',
		'SP_Woo_Quick_View_Pro',
	),
	'woo-quickview'
);

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SP_WPCF::createMetabox(
	'sp_wpcf_live_preview',
	array(
		'title'        => __( 'Live Preview', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'context'      => 'normal',
	)
);

SP_WPCF::createSection(
	'sp_wpcf_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Create a metabox.
//
SP_WPCF::createMetabox(
	$wpcp_carousel_content_source_settings,
	array(
		'title'        => __( 'WordPress Carousel', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'context'      => 'normal',
	)
);

//
// Create a section.
//
SP_WPCF::createSection(
	$wpcp_carousel_content_source_settings,
	array(
		'fields' => array(
			array(
				'type'    => 'heading',
				'image'   => plugin_dir_url( __DIR__ ) . 'img/wpcp-logo.svg',
				'after'   => '<i class="fa fa-life-ring"></i> Support',
				'link'    => 'https://shapedplugin.com/support/?user=lite',
				'class'   => 'wpcp-admin-header',
				'version' => WPCAROUSELF_VERSION,
			),
			array(
				'id'      => 'wpcp_carousel_type',
				'class'   => 'wpcp_carousel_type',
				'type'    => 'carousel_type',
				'title'   => __( 'Source Type', 'wp-carousel-free' ),
				'options' => array(
					'image-carousel'    => array(
						'icon' => 'wpcf-icon-image-1',
						'text' => __( 'Image', 'wp-carousel-free' ),
					),
					'post-carousel'     => array(
						'icon' => 'wpcf-icon-post',
						'text' => __( 'Post', 'wp-carousel-free' ),
					),
					'product-carousel'  => array(
						'icon' => 'wpcf-icon-products',
						'text' => __( 'Product', 'wp-carousel-free' ),
					),
					'video-carousel'    => array(
						'icon' => 'wpcf-icon-video',
						'text' => __( 'Video', 'wp-carousel-free' ),
					),
					'audio-carousel'    => array(
						'icon' => 'wpcf-icon-audio',
						'text' => __( 'Audio', 'wp-carousel-free' ),
						// 'pro_only' => true,
					),
					'content-carousel'  => array(
						'icon' => 'wpcf-icon-content',
						'text' => __( 'Content', 'wp-carousel-free' ),
						// 'pro_only' => true,
					),
					'mix-content'       => array(
						'icon' => 'wpcf-icon-mix-content',
						'text' => __( 'Mix-Content', 'wp-carousel-free' ),
						// 'pro_only' => true,
					),
					'external-carousel' => array(
						'icon' => 'wpcf-icon-external',
						'text' => __( 'External', 'wp-carousel-free' ),
						// 'pro_only' => true,
					),
				),
				'default' => 'image-carousel',
			),
			array(
				'type'       => 'addContent',

				'title'      => __( 'Content', 'wp-carousel-free' ),
				'text'       => __( 'Add Content', 'wp-carousel-free' ),
				'content'    => 'With <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" />WP Carousel Pro</a>, you can
<a href="https://wpcarousel.io/content-carousel/" target="_blank" />Slide Any Content</a>, e.g., images, text, HTML, shortcodes, custom content, etc.
<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" /><b>Get Pro Now!</b></a> ',
				'dependency' => array( 'wpcp_carousel_type', '==', 'content-carousel' ),
			),
			array(
				'type'       => 'addContent',

				'title'      => __( 'Audio', 'wp-carousel-free' ),
				'text'       => __( 'Add Audio', 'wp-carousel-free' ),
				'content'    => 'With <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" />WP Carousel Pro</a>, you can create unlimited
<a href="https://wpcarousel.io/embed-audio/" target="_blank" />Audio Carousels</a> and Galleries from any audio platform, including self-hosted.
<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" /><b>Get Pro Now!</b></a> ',
				'dependency' => array( 'wpcp_carousel_type', '==', 'audio-carousel' ),
			),
			array(
				'type'       => 'addContent',
				'title'      => __( 'Mix Content', 'wp-carousel-free' ),
				'text'       => __( 'Add Content', 'wp-carousel-free' ),
				'content'    => 'With <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" />WP Carousel Pro</a>, you can
<a href="https://wpcarousel.io/mix-content-carousel/" target="_blank" />Slide Mix Content</a>, e.g., images, video, audio, HTML, custom content, etc.
<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" /><b>Get Pro Now!</b></a>',
				'dependency' => array( 'wpcp_carousel_type', '==', 'mix-content' ),
			),

			array(
				'id'         => 'wpcp_feeds_url',
				'type'       => 'text',
				'class'      => 'pro_only_field',
				'title'      => __( 'Feeds URL', 'wp-carousel-free' ),
				'desc'       => __( 'Write your feeds URL. <a href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/how-to-find-the-rss-feed-url-of-a-site/" target="_blank">Get help</a>', 'wp-carousel-free' ),
				'attributes' => array(
					'placeholder' => __( 'Feeds URL', 'wp-carousel-free' ),
				),
				'dependency' => array( 'wpcp_carousel_type', '==', 'external-carousel' ),
			),

			array(
				'id'         => 'wpcp_external_limit',
				'type'       => 'spinner',
				'class'      => 'pro_only_field',
				'title'      => __( 'Limit', 'wp-carousel-free' ),
				'default'    => '20',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_carousel_type', '==', 'external-carousel' ),
			),
			array(
				'type'       => 'addContent',
				'class'      => 'external_content',
				'content'    => 'With <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" />WP Carousel Pro</a>, you can show <a href="https://wpcarousel.io/external-rss-feed-carousel/" target="_blank" />
Feeds Content</a> from external sources.
<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank" /><b>Get Pro Now!</b></a> ',
				'dependency' => array( 'wpcp_carousel_type', '==', 'external-carousel' ),
			),
			// End external Carousel.
			array(
				'id'          => 'wpcp_gallery',
				'type'        => 'gallery',
				'title'       => __( 'Images', 'wp-carousel-free' ),
				'wrap_class'  => 'wpcp-gallery-filed-wrapper',
				'add_title'   => __( 'ADD IMAGE', 'wp-carousel-free' ),
				'edit_title'  => __( 'EDIT IMAGE', 'wp-carousel-free' ),
				'clear_title' => __( 'REMOVE ALL', 'wp-carousel-free' ),
				'dependency'  => array( 'wpcp_carousel_type', '==', 'image-carousel' ),
			),
			// Post Carousel.
			array(
				'id'         => 'wpcp_post_type',
				'type'       => 'select',
				'title'      => __( 'Post Type', 'wp-carousel-free' ),
				'options'    => array(
					'post'       => array(
						'text' => __( 'Posts', 'wp-carousel-free' ),
					),
					'page'       => array(
						'text'     => __( 'Pages (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'custom'     => array(
						'text'     => __( 'Custom Post Types (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'multi_post' => array(
						'text'     => __( 'Multiple Post Types (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'post',
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel' ),
			),
			array(
				'id'         => 'wpcp_display_posts_from',
				'type'       => 'select',
				'title'      => __( 'Filter Posts', 'wp-carousel-free' ),
				'options'    => array(
					'latest'        => array(
						'text' => __( 'Latest', 'wp-carousel-free' ),
					),
					'taxonomy'      => array(
						'text'     => __( 'Taxonomy (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'specific_post' => array(
						'text'     => __( 'Specific (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'latest',
				'class'      => 'chosen',
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			array(
				'id'         => 'number_of_total_posts',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-carousel-free' ),
				'default'    => '10',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
			),
			// Product Carousel.
			array(
				'id'         => 'wpcp_display_product_from',
				'type'       => 'select',
				'title'      => __( 'Filter Products', 'wp-carousel-free' ),
				'options'    => array(
					'latest'            => array(
						'text' => __( 'Latest', 'wp-carousel-free' ),
					),
					'taxonomy'          => array(
						'text'     => __( 'Category (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'specific_products' => array(
						'text'     => __( 'Specific (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'on_sale'           => array(
						'text'     => __( 'On Sale (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'latest',
				'class'      => 'chosen',
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_total_products',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-carousel-free' ),
				'default'    => '10',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
			),
			// End Product Carousel.
			// Video.
			array(
				'id'                     => 'carousel_video_source',
				'type'                   => 'group',
				'class'                  => 'wpcp-video-field-wrapper',
				'title'                  => __( 'Video', 'wp-carousel-free' ),
				'button_title'           => __( '<i class="fa fa-plus-circle"></i> Add Video', 'wp-carousel-free' ),
				'accordion_title_prefix' => __( 'Video:', 'wp-carousel-free' ),
				'desc'                   => sprintf(
						/* translators: 1: start link tag, 1: close tag. */
					__( 'Maximum display limit 6 videos. To show unlimited videos from different video platforms, %1$sUpgrade to Pro!%2$s', 'wp-carousel-free' ),
					'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
					'</b></a>'
				),
				'accordion_title_number' => true,
				'fields'                 => array(
					array(
						'id'      => 'carousel_video_source_type',
						'type'    => 'carousel_type',
						'class'   => 'carousel_type_small',
						'title'   => 'Source',
						'options' => array(
							'youtube'     => array(
								'image' => plugin_dir_url( __DIR__ ) . 'img/source/youtube.svg',
								'text'  => __( 'YouTube', 'wp-carousel-free' ),
							),
							'vimeo'       => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/source/vimeo.svg',
								'text'     => __( 'Vimeo', 'wp-carousel-free' ),
								'pro_only' => true,
							),
							'tiktok'      => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/source/tik-tok.svg',
								'text'     => __( 'TikTok', 'wp-carousel-free' ),
								'pro_only' => true,
							),
							'twitch'      => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/source/twitch.svg',
								'text'     => __( 'Twitch', 'wp-carousel-free' ),
								'pro_only' => true,
							),
							'dailymotion' => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/source/dailymotion.svg',
								'text'     => __( 'Dailymotion', 'wp-carousel-free' ),
								'pro_only' => true,
							),
							'wistia'      => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/source/wistia.svg',
								'text'     => __( 'Wistia', 'wp-carousel-free' ),
								'pro_only' => true,
							),
							'self_hosted' => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/source/clapperboard.svg',
								'text'     => __( 'Self Hosted', 'wp-carousel-free' ),
								'pro_only' => true,
							),
						),
						'default' => 'youtube',
					),
					array(
						'id'         => 'carousel_video_source_id',
						'class'      => 'carousel_video_source_id',
						'type'       => 'text',
						'title'      => __( 'Video ID', 'wp-carousel-free' ),
						'title_help' => __( 'The last part of the URL is the ID e.g: //youtube.com/watch?v=<b><i>eKFTSSKCzWA</i></b> <br>//vimeo.com/<b><i>95746815</i></b>', 'wp-carousel-free' ),
					),
					array(
						'id'     => 'carousel_video_description',
						'class'  => 'wpcp-video-description',
						'type'   => 'wp_editor',
						'title'  => __( 'Title & Description (optional)', 'wp-carousel-free' ),
						'height' => '150px',
					),
				),
				'dependency'             => array( 'wpcp_carousel_type', '==', 'video-carousel' ),
			), // End of Video Carousel.
		), // End of fields array.
	)
);

//
// Metabox for the Carousel Post Type.
// Set a unique slug-like ID.
//
$wpcp_carousel_shortcode_settings = 'sp_wpcp_shortcode_options';

//
// Create a metabox.
//
SP_WPCF::createMetabox(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'        => __( 'Shortcode Section', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'nav'          => 'inline',
		'theme'        => 'light',
		'class'        => 'sp_wpcp_shortcode_generator',
	)
);

//
// Create a section.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'General Settings', 'wp-carousel-free' ),
		'icon'   => 'wpcf-icon-lightbox-general',
		'fields' => array(
			array(
				'id'       => 'wpcp_layout',
				'class'    => 'wpcp_layout',
				'type'     => 'image_select',
				'title'    => __( 'Layout Preset', 'wp-carousel-free' ),
				'subtitle' => __( 'Choose a layout preset.', 'wp-carousel-free' ),
				'desc'     => sprintf(
					/* translators: 1: start link tag, 2: close tag. */
					__( 'Want to create engaging %1$slayouts%2$s and advanced customizations? %3$sUpgrade to Pro!%4$s', 'wp-carousel-free' ),
					'<a href="https://wpcarousel.io/layout-types/" target="_blank"><b>',
					'</b></a></a>',
					'<b><a href="https://wpcarousel.io/pricing/?ref=1" target="_blank">',
					'</b></a></br>'
				),
				'options'  => array(
					'carousel'          => array(
						'image'           => plugin_dir_url( __DIR__ ) . 'img/layout/carousel.svg',
						'text'            => __( 'Carousel', 'wp-carousel-free' ),
						'option_demo_url' => 'https://wpcarousel.io/simple-image-carousel/',
					),
					'slider'            => array(
						'image'           => plugin_dir_url( __DIR__ ) . 'img/layout/slider.svg',
						'text'            => __( 'Slider', 'wp-carousel-free' ),
						'option_demo_url' => 'https://wpcarousel.io/slider-sliding-effects/',
					),
					'grid'              => array(
						'image'           => plugin_dir_url( __DIR__ ) . 'img/layout/grid.svg',
						'text'            => __( 'Grid', 'wp-carousel-free' ),
						'option_demo_url' => 'https://wpcarousel.io/grid/',
					),
					'thumbnails-slider' => array(
						'image'           => plugin_dir_url( __DIR__ ) . 'img/layout/thumbnails-slider.svg',
						'text'            => __( 'Thumbs Slider', 'wp-carousel-free' ),
						'option_demo_url' => 'https://wpcarousel.io/thumbnails-slider/',
						'pro_only'        => true,
					),
					'tiles'             => array(
						'image'           => plugin_dir_url( __DIR__ ) . 'img/layout/tiles.svg',
						'text'            => __( 'Tiles', 'wp-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://wpcarousel.io/image-tiles/',
					),
					'masonry'           => array(
						'image'           => plugin_dir_url( __DIR__ ) . 'img/layout/masonry.svg',
						'text'            => __( 'Masonry', 'wp-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://wpcarousel.io/masonry/',
					),
					'justified'         => array(
						'image'           => plugin_dir_url( __DIR__ ) . 'img/layout/justified.svg',
						'text'            => __( 'Justified', 'wp-carousel-free' ),
						'pro_only'        => true,
						'option_demo_url' => 'https://wpcarousel.io/justified/',
					),
				),
				'default'  => 'carousel',
			),
			array(
				'id'         => 'wpcp_carousel_mode',
				'class'      => 'wpcp_carousel_mode',
				'type'       => 'image_select',
				'title'      => __( 'Carousel Style', 'wp-carousel-free' ),
				'subtitle'   => __( 'Choose a carousel style.', 'wp-carousel-free' ),
				// 'title_help' => sprintf(
				// * translators: 1: start div tag, 2: close div and start antoher div tag 3: start bold tag 4: close bold tag 5: start div and link tag 6: close link and start another link tag 7: close link tag.. */
				// __( '%1$sCarousel Mode%2$sChoose %3$sStandard%4$s for a classic display, %3$sTicker%4$s for continuous scrolling, or %3$sCenter%4$s for a focused and immersive view.%5$sOpen Docs%6$sLive Demo%7$s', 'wp-carousel-free' ),
				// '<div class="sp_wpcp-info-label">',
				// '</div><div class="sp_wpcp-short-content">',
				// '<b>',
				// '</b>',
				// '</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-carousel-mode/" target="_blank">',
				// '</a><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/carousel-modes/" target="_blank">',
				// '</a>'
				// ),
				'options'    => array(
					'standard'    => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/standard.svg',
						'text'  => __( 'Standard', 'wp-carousel-free' ),
					),
					'center'      => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/center.svg',
						'text'  => __( 'Center', 'wp-carousel-free' ),
					),
					'ticker'      => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/ticker.svg',
						'text'     => __( 'Ticker', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'multi-row'   => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/multi-row.svg',
						'text'     => __( 'Multi Row', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'3d-carousel' => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/3d.svg',
						'text'     => __( '3D Carousel', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'panorama'    => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/panorama.svg',
						'text'     => __( 'Panorama', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'triple'      => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/triple.svg',
						'text'     => __( 'Triple', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'spring'      => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/spring.svg',
						'text'     => __( 'Spring', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'standard',
				'dependency' => array( 'wpcp_layout', '==', 'carousel' ),
			),
			array(
				'id'         => 'wpcp_slider_style',
				'class'      => 'wpcp_slider_style',
				'type'       => 'image_select',
				'title'      => __( 'Slider Style', 'wp-carousel-free' ),
				'subtitle'   => __( 'Choose a slider style.', 'wp-carousel-free' ),
				'options'    => array(
					'normal'    => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/layout/slider.svg',
						'text'  => __( 'Slide', 'wp-carousel-free' ),
					),
					'flip'      => array(
						'image' => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/flip.svg',
						'text'  => __( 'Flip', 'wp-carousel-free' ),
					),
					'fade'      => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/fadeslide.png',
						'text'     => __( 'Fade', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'kenburn'   => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/Kenburns.svg',
						'text'     => __( 'Ken Burns', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'shaders'   => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/shader.svg',
						'text'     => __( 'Shaders', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'slicer'    => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/slicer.svg',
						'text'     => __( 'Slicer', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'shutters'  => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/shutters.svg',
						'text'     => __( 'Shutters', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'fashion'   => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/fashion.svg',
						'text'     => __( 'Fashion', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'coverflow' => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/coverflow.svg',
						'text'     => __( 'Coverflow', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'cube'      => array(
						'image'    => plugin_dir_url( __DIR__ ) . 'img/carousel-mode/cube.svg',
						'text'     => __( 'Cube', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'normal',
				'dependency' => array( 'wpcp_layout', '==', 'slider' ),
			),
			// array(
			// 'id'         => 'wpcp_thumbnail_position',
			// 'type'       => 'image_select',
			// 'class'      => 'wpcp_thumbnail_position',
			// 'title'      => __( 'Thumbnails Position', 'wp-carousel-free' ),
			// 'subtitle'   => __( 'Choose a thumbnails position.', 'wp-carousel-free' ),
			// 'options'    => array(
			// 'bottom' => array(
			// 'image' => plugin_dir_url( __DIR__ ) . 'img/thumbnail_position_bottom.svg',
			// 'text'  => __( 'Bottom', 'wp-carousel-free' ),
			// ),
			// 'top'    => array(
			// 'image'    => plugin_dir_url( __DIR__ ) . 'img/thumbnail_position_top.svg',
			// 'text'     => __( 'Top', 'wp-carousel-free' ),
			// 'pro_only' => true,
			// ),
			// 'left'   => array(
			// 'image'    => plugin_dir_url( __DIR__ ) . 'img/thumbnail_position_left.svg',
			// 'text'     => __( 'Left', 'wp-carousel-free' ),
			// 'pro_only' => true,
			// ),
			// 'right'  => array(
			// 'image'    => plugin_dir_url( __DIR__ ) . 'img/thumbnail_position_right.svg',
			// 'text'     => __( 'Right', 'wp-carousel-free' ),
			// 'pro_only' => true,
			// ),
			// ),
			// 'default'    => 'bottom',
			// 'dependency' => array( 'wpcp_layout', '==', 'thumbnails-slider', true ),
			// ),

			array(
				'id'         => 'wpcp_slider_animation',
				'class'      => 'wpcp_slider_animation',
				'type'       => 'select',
				'title'      => __( 'Slide Effect', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select a slide transition effect.', 'wp-carousel-free' ),
				'title_help' => sprintf(
					/* translators: 1: start div tag, 2: close div and start another div tag 3: close div and start link tag 4: close link tag. */
					__(
						'%1$sSlide Effect%2$sEnhance your slide transition with charming Slide Effects to add elegance and dynamic motion to your slides.%3$sLive Demo%4$s',
						'wp-carousel-free'
					),
					'<div class="sp_wpcp-info-label">',
					'</div><div class="sp_wpcp-short-content">',
					'</div><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/slider-sliding-effects/" target="_blank">',
					'</a>'
				),
				'options'    => array(
					''          => __( 'Slide', 'wp-carousel-free' ),
					'flip'      => array(
						'text' => __( 'Flip', 'wp-carousel-free' ),
					),
					'fade'      => array(
						'text'     => __( 'Fade (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'coverflow' => array(
						'text'     => __( 'Coverflow (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'cube'      => array(
						'text'     => __( 'Cube (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
					'kenburn'   => array(
						'text'     => __( 'Ken Burns (Pro)', 'wp-carousel-free' ),
						'pro_only' => true,
					),
				),
				'default'    => 'slide',
				'dependency' => array( 'wpcp_layout|wpcp_carousel_mode', '==|==', 'carousel|standard', true ),
			),

			// array(
			// 'id'         => 'thumbnails_hide_on_mobile',
			// 'type'       => 'checkbox',
			// 'title'      => __( 'Hide Thumbnails on Mobile', 'wp-carousel-free' ),
			// 'default'    => false,
			// 'dependency' => array( 'wpcp_layout', '==', 'thumbnails-slider' ),
			// ),
			array(
				'id'         => 'wpcp_number_of_columns',
				'type'       => 'column',
				'class'      => 'wpcp_number_of_columns',
				'title'      => __( 'Columns', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set number of column on devices.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_number_array_field',
				// 'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/column.svg" alt="' . __( 'Column(s)', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Column(s)', 'wp-carousel-free' ) . '</div>',
				'default'    => array(
					'lg_desktop' => '5',
					'desktop'    => '4',
					'laptop'     => '3',
					'tablet'     => '2',
					'mobile'     => '1',
				),
				'title_help' => sprintf(
					/* translators: 1: start bold tag, 2: close bold tag. */
					__(
						'
						%1$s LARGE DESKTOP %2$s - Screens larger than 1280px.%3$s
						%4$s DESKTOP %2$s - Screens larger than 1280px.%3$s
						%5$s LAPTOP %2$s - Screens smaller than 980px.%3$s
						%6$s TABLET %2$s - Screens smaller than 736px.%3$s
						%7$s MOBILE %2$s - Screens smaller than 480px.%3$s',
						'wp-carousel-free'
					),
					'<i class="fa fa-television"></i><b>',
					'</b>',
					'<br/>',
					'<i class="fa fa-desktop"></i><b>',
					'<i class="fa fa-laptop"></i><b>',
					'<i class="fa fa-tablet"></i><b>',
					'<i class="fa fa-mobile"></i><b>'
				),
				'min'        => '1',
				'dependency' => array( 'wpcp_layout', '!=', 'slider' ),
			),
			array(
				'id'         => 'wpcp_slide_margin',
				'class'      => 'wpcp-slide-margin',
				'type'       => 'spacing',
				'title'      => __( 'Space', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set a space between the items.', 'wp-carousel-free' ),
				'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/space.svg" alt="' . __( 'Space', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Space', 'wp-carousel-free' ) . '</div>',
				'sanitize'   => 'wpcf_sanitize_number_array_field',
				'right'      => true,
				'top'        => true,
				'left'       => false,
				'bottom'     => false,
				'right_text' => 'Vertical Gap',
				'top_text'   => 'Gap',
				'right_icon' => '<i class="fa fa-arrows-v"></i>',
				'top_icon'   => '<i class="fa fa-arrows-h"></i>',
				'unit'       => true,
				'units'      => array( 'px' ),
				'default'    => array(
					'top'   => '20',
					'right' => '20',
				),
				'dependency' => array( 'wpcp_layout', '!=', 'slider' ),
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
						'title'      => __( 'Click Action Type', 'wp-carousel-free' ),
						'options'    => array(
							'l_box' => array(
								'image' => plugin_dir_url( __DIR__ ) . 'img/lightbox.svg',
							),
							'link'  => array(
								'image'    => plugin_dir_url( __DIR__ ) . 'img/url.svg',
								'pro_only' => true,
							),

							'none'  => array(
								'image' => plugin_dir_url( __DIR__ ) . 'img/disabled.svg',
							),
						),
						'subtitle'   => __( 'Set a click action type for the items.', 'wp-carousel-free' ),
						'default'    => 'l_box',
						'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
					),
				),
			),
			array(
				'id'         => 'wpcp_image_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set an order by option.', 'wp-carousel-free' ),
				'options'    => array(
					'menu_order' => __( 'Drag & Drop', 'wp-carousel-free' ),
					'rand'       => __( 'Random', 'wp-carousel-free' ),
				),
				'default'    => 'menu_order',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'image-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_order_by',
				'type'       => 'select',
				'title'      => __( 'Order By', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select an order by option.', 'wp-carousel-free' ),
				'options'    => array(
					'ID'         => __( 'ID', 'wp-carousel-free' ),
					'date'       => __( 'Date', 'wp-carousel-free' ),
					'rand'       => __( 'Random', 'wp-carousel-free' ),
					'title'      => __( 'Title', 'wp-carousel-free' ),
					'modified'   => __( 'Modified', 'wp-carousel-free' ),
					'menu_order' => __( 'Menu Order', 'wp-carousel-free' ),
				),
				'default'    => 'date',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_post_order',
				'type'       => 'select',
				'title'      => __( 'Order', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select an order option.', 'wp-carousel-free' ),
				'options'    => array(
					'ASC'  => __( 'Ascending', 'wp-carousel-free' ),
					'DESC' => __( 'Descending', 'wp-carousel-free' ),
				),
				'default'    => 'DESC',
				'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
			),
			array(
				'id'         => 'wpcp_scheduler',
				'type'       => 'switcher',
				'class'      => 'wpcf_show_hide',
				'title'      => __( 'Scheduling', 'wp-carousel-free' ),
				'subtitle'   => __( 'Schedule sliders or galleries to show at specific time intervals.', 'wp-carousel-free' ),
				'title_help' => sprintf(
					/* translators: 1: start div tag, 2: close div and start antoher div tag 3: close div and start link tag 4: close link and start another link tag 5: close link tag.. */
					__( '%1$sScheduling%2$sEnable the scheduling feature to set the specific date and time for your carousel sliders or galleries to be displayed (perfect for highlighting time-sensitive content).%3$sOpen Docs%4$sLive Demo%5$s', 'wp-carousel-free' ),
					'<div class="sp_wpcp-info-label">',
					'</div><div class="sp_wpcp-short-content">',
					'</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-scheduling-feature/" target="_blank">',
					'</a><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/scheduled-carousel/" target="_blank">',
					'</a>'
				),
				'default'    => false,
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
			),
			array(
				'id'         => 'wpcp_preloader',
				'type'       => 'switcher',
				'title'      => __( 'Preloader', 'wp-carousel-free' ),
				'subtitle'   => __( 'Items will be hidden until page load completed.', 'wp-carousel-free' ),
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'default'    => true,
			),
			// Pagination.
			array(
				'type'       => 'subheading',
				'content'    => __( 'Pagination', 'wp-carousel-free' ),
				'dependency' => array( 'wpcp_layout', '==', 'grid', true ),
			),
			array(
				'id'         => 'wpcp_source_pagination_pro',
				'class'      => 'wpcf_show_hide',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'title'      => __( 'Pagination', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable to show pagination.', 'wp-carousel-free' ),
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
			),
			array(
				'id'         => 'wpcp_source_pagination',
				'type'       => 'switcher',
				'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
				'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
				'text_width' => 100,
				'title'      => __( 'Pagination', 'wp-carousel-free' ),
				'subtitle'   => __( 'Enable to show pagination.', 'wp-carousel-free' ),
				'default'    => true,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', 'any|==', 'post-carousel,product-carousel|grid', true ),
			),
			array(
				'id'         => 'wpcp_post_pagination_type',
				'class'      => 'wpcp_post_pagination_type',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select pagination type.', 'wp-carousel-free' ),
				'options'    => array(
					'load_more_btn'   => __( 'Load More Button (Pro)', 'wp-carousel-free' ),
					'infinite_scroll' => __( 'Load More on Infinite Scroll (Pro)', 'wp-carousel-free' ),
					'ajax_number'     => __( 'Ajax Number Pagination (Pro)', 'wp-carousel-free' ),
					'normal'          => __( 'Normal Pagination', 'wp-carousel-free' ),
				),
				'default'    => 'normal',
				'dependency' => array( 'wpcp_carousel_type|wpcp_source_pagination|wpcp_layout', 'any|==|==', 'post-carousel,product-carousel|true|grid', true ),
			),
			array(
				'id'         => 'wpcp_pagination_type',
				'class'      => 'pro_only_field',
				'type'       => 'radio',
				'title'      => __( 'Pagination Type', 'wp-carousel-free' ),
				'subtitle'   => __( 'Select pagination type.', 'wp-carousel-free' ),
				'options'    => array(
					'load_more_btn'   => __( 'Load More Button (Ajax)', 'wp-carousel-free' ),
					'infinite_scroll' => __( 'Load More on Infinite Scroll (Ajax)', 'wp-carousel-free' ),
					'ajax_number'     => __( 'Number Pagination (Ajax)', 'wp-carousel-free' ),
				),
				'default'    => 'load_more_btn',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
			),
			array(
				'id'         => 'post_per_page',
				'type'       => 'spinner',
				'title'      => __( 'Items To Show Per Page', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set items to show per page.', 'wp-carousel-free' ),
				'default'    => '8',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination', '!=|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'post_per_page_pro',
				'type'       => 'spinner',
				'class'      => 'pro_only_field',
				'title'      => __( 'Items To Show Per Page (Pro)', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set items to show per page.', 'wp-carousel-free' ),
				'default'    => '8',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination_pro', '==|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'post_per_click_pro',
				'class'      => 'pro_only_field',
				'type'       => 'spinner',
				'title'      => __( 'Items To Show Per Click (Pro)', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set items to show per click.', 'wp-carousel-free' ),
				'default'    => '8',
				'min'        => 1,
				'max'        => 10000,
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination_pro', '==|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'pagination_alignment',
				'type'       => 'button_set',
				'title'      => __( 'Alignment', 'wp-carousel-free' ),
				'subtitle'   => __( 'Choose pagination alignment.', 'wp-carousel-free' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="Left"></i>',
					'center' => '<i class="fa fa-align-center" title="Center"></i>',
					'right'  => '<i class="fa fa-align-right" title="Right"></i>',
				),
				'default'    => 'center',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination', '!=|==|==', 'image-carousel|grid|true', true ),
			),
			array(
				'id'         => 'pagination_alignment_pro',
				'type'       => 'button_set',
				'class'      => 'pro_only_field',
				'title'      => __( 'Alignment', 'wp-carousel-free' ),
				'subtitle'   => __( 'Choose pagination alignment.', 'wp-carousel-free' ),
				'options'    => array(
					'left'   => '<i class="fa fa-align-left" title="Left"></i>',
					'center' => '<i class="fa fa-align-center" title="Center"></i>',
					'right'  => '<i class="fa fa-align-right" title="Right"></i>',
				),
				'default'    => 'center',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
			),
			array(
				'id'         => 'pagination_color',
				'type'       => 'color_group',
				'title'      => __( 'Color', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set pagination color.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_color_group_field',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout|wpcp_source_pagination', '!=|==|==', 'image-carousel|grid|true', true ),
				'options'    => array(
					'color'        => __( 'Color', 'wp-carousel-free' ),
					'hover_color'  => __( 'Hover Color', 'wp-carousel-free' ),
					'bg'           => __( 'Background', 'wp-carousel-free' ),
					'hover_bg'     => __( 'Hover Background', 'wp-carousel-free' ),
					'border'       => __( 'Border', 'wp-carousel-free' ),
					'hover_border' => __( 'Hover Border', 'wp-carousel-free' ),
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
			array(
				'id'         => 'pagination_color_pro',
				'type'       => 'color_group',
				'class'      => 'pro_only_field',
				'title'      => __( 'Color', 'wp-carousel-free' ),
				'subtitle'   => __( 'Set pagination color.', 'wp-carousel-free' ),
				'sanitize'   => 'wpcf_sanitize_color_group_field',
				'dependency' => array( 'wpcp_carousel_type|wpcp_layout', '==|==', 'image-carousel|grid', true ),
				'options'    => array(
					'color'        => __( 'Color', 'wp-carousel-free' ),
					'hover_color'  => __( 'Hover Color', 'wp-carousel-free' ),
					'bg'           => __( 'Background', 'wp-carousel-free' ),
					'hover_bg'     => __( 'Hover Background', 'wp-carousel-free' ),
					'border'       => __( 'Border', 'wp-carousel-free' ),
					'hover_border' => __( 'Hover Border', 'wp-carousel-free' ),
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
			array(
				'type'       => 'notice',
				'style'      => 'normal',
				'class'      => 'sp-settings-pro-notice',
				'content'    => sprintf(
					/* translators: 1: start link and bold tag, 2: close bold and link tag. */
					__( 'Want to unleash the power of Ajax Paginations and take your website UX to the next level? %1$sUpgrade to Pro!%2$s', 'wp-carousel-free' ),
					'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
					'</b></a>'
				),
				'dependency' => array( 'wpcp_layout|wpcp_source_pagination', '==|==', 'grid|true', true ),
			),
		), // Fields array end.
	)
); // End of Upload section.


//
// Style settings section begin.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'Display Settings', 'wp-carousel-free' ),
		'icon'   => 'wpcf-icon-display',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'wp-carousel-style-tabs',
				'tabs'  => array(
					// Item Style.
					array(
						'title'  => __( 'Item Styles', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-tab_style-settings',
						'fields' => array(
							array(
								'id'         => 'section_title',
								'type'       => 'switcher',
								'title'      => __( 'Section Title', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide the section title.', 'wp-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
							),

							array(
								'id'         => 'wpcp_content_style',
								'class'      => 'wpcp_content_style',
								'type'       => 'image_select',
								'title'      => __( 'Items Style', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select an item style for the title, description, meta etc.', 'wp-carousel-free' ),
								'options'    => array(
									'default'          => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/default/default.svg',
										'text'  => __( 'Classic', 'wp-carousel-free' ),
									),
									'with_overlay'     => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/overlay/overlay.svg',
										'text'     => __( 'Overlay', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'caption_full'     => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/caption-full/caption_full.svg',
										'text'     => __( 'Caption Full', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'caption_partial'  => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/caption-partial/caption_partial.svg',
										'text'     => __( 'Caption Part', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'content_diagonal' => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/diagonal/diagonal.svg',
										'text'     => __( 'Diagonal', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'content_box'      => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/content-box/content-box.svg',
										'text'     => __( 'Content Box', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'moving'           => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/caption-partial/content-moving.svg',
										'text'     => __( 'Moving', 'wp-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'    => 'default',
								'dependency' => array( 'wpcp_carousel_type', 'any|==', 'image-carousel,post-carousel,product-carousel,external-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_detail_position',
								'class'      => 'wpcp_content_position',
								'type'       => 'image_select',
								'title'      => __( 'Content Position', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set a position for the content.', 'wp-carousel-free' ),
								'desc'       => sprintf(
								/* translators: 1: start link tag, 2: close tag. */
									__( 'Want to unlock amazing %1$sItem Styles%2$s and unleash your creativity? %3$sUpgrade to Pro!%2$s', 'wp-carousel-free' ),
									'<a href="https://wpcarousel.io/item-styles/" target="_blank"><b>',
									'</b></a>',
									'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>'
								),
								'options'    => array(
									'bottom'   => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/default/default-bottom.svg',
										'text'  => __( 'Bottom', 'wp-carousel-free' ),
									),
									'top'      => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/default/default-top.svg',
										'text'  => __( 'Top', 'wp-carousel-free' ),
									),
									'on_right' => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/default/default-right.svg',
										'text'     => __( 'Right', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'on_left'  => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/default/default-left.svg',
										'text'     => __( 'Left', 'wp-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'    => 'bottom',
								'dependency' => array( 'wpcp_carousel_type|wpcp_content_style', 'any|==', 'image-carousel,post-carousel,product-carousel,external-carousel|default', true ),
							),
							array(
								'id'         => 'item_same_height',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Enable Equal Height', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable to make all items or slides equal to the tallest one.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/equal-height.svg" alt="' . __( 'Equal Height', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Equal Height', 'wp-carousel-free' ) . '</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-enable-equal-height/" target="_blank">' . __( 'Open Docs', 'wp-carousel-free' ) . '</a>',
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'wpcp_layout|wpcp_content_style', 'not-any|==', 'thumbnails-slider,justified,masonry,tiles|default', true ),
							),
							array(
								'id'         => 'wpcp_image_vertical_alignment',
								'type'       => 'button_set',
								'class'      => 'wpcp_image_vertical_alignment',
								'title'      => __( 'Items Vertical Alignment', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a vertical alignment for the items.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/slider-vertical-alignment.svg" alt="Slider Vertical Alignment"></div><div class="sp_wpcp-info-label">' . __( 'Slider Vertical Alignment', 'wp-carousel-free' ) . '</div>',
								'options'    => array(
									'flex-start' => __( 'Top', 'wp-carousel-free' ),
									'center'     => __( 'Middle', 'wp-carousel-free' ),
									'flex-end'   => __( 'Bottom', 'wp-carousel-free' ),
								),
								'default'    => 'center',
								// 'dependency' => array( 'wpcp_layout|item_same_height', 'not-any|==', 'thumbnails-slider,justified,masonry|false', true ),
							),

							array(
								'id'         => 'wpcp_slide_border',
								'type'       => 'border',
								'title'      => __( 'Item Border', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set border for the items.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_border_field',
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/slider-border.svg" alt="' . __( 'Items Border', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Items Border', 'wp-carousel-free' ) . '</div>',
								'all'        => true,
								'default'    => array(
									'all'   => '1',
									'style' => 'solid',
									'color' => '#dddddd',
								),
								'dependency' => array( 'wpcp_carousel_type', '!=', 'product-carousel', true ),
							),

							array(
								'id'         => 'wpcp_slide_background',
								'type'       => 'color',
								'title'      => __( 'Slide Background', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set background color for the slide.', 'wp-carousel-free' ),
								'default'    => '#f9f9f9',
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'       => 'wpcp_box_shadow_style',
								'type'     => 'button_set',
								'title'    => __( 'Items Box-Shadow', 'wp-carousel-free' ),
								'subtitle' => __( 'Set Box-Shadow for the items.', 'wp-carousel-free' ),
								'options'  => array(
									'outset' => __( 'Outset', 'wp-carousel-free' ),
									'inset'  => __( 'Inset', 'wp-carousel-free' ),
									'none'   => __( 'None', 'wp-carousel-free' ),
								),
								'default'  => 'none',
							),
							array(
								'id'          => 'wpcp_box_shadow',
								'type'        => 'box_shadow',
								'title'       => __( 'Box-Shadow Values', 'wp-carousel-free' ),
								'subtitle'    => __( 'Set box-shadow property values for the item.', 'wp-carousel-free' ),
								'style'       => false,
								'hover_color' => true,
								'default'     => array(
									'vertical'    => '0',
									'horizontal'  => '0',
									'blur'        => '10',
									'spread'      => '0',
									// 'style'       => 'outset',
									'color'       => '#dddddd',
									'hover_color' => '#dddddd',
								),
								'dependency'  => array( 'wpcp_box_shadow_style', '!=', 'none', true ),
							),

							// array(
							// 'id'         => 'wpcp_image_caption',
							// 'type'       => 'switcher',
							// 'class'      => 'wpcf_show_hide',
							// 'title'      => __( 'Caption', 'wp-carousel-free' ),
							// 'subtitle'   => __( 'Show/Hide image caption for image.', 'wp-carousel-free' ),
							// 'text_on'    => __( 'Show', 'wp-carousel-free' ),
							// 'text_off'   => __( 'Hide', 'wp-carousel-free' ),
							// 'text_width' => 80,
							// 'default'    => false,
							// 'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
							// ),

							// array(
							// 'id'         => 'wpcp_image_desc',
							// 'type'       => 'switcher',
							// 'class'      => 'wpcf_show_hide',
							// 'title'      => __( 'Description', 'wp-carousel-free' ),
							// 'subtitle'   => __( 'Show/Hide description for image.', 'wp-carousel-free' ),
							// 'text_on'    => __( 'Show', 'wp-carousel-free' ),
							// 'text_off'   => __( 'Hide', 'wp-carousel-free' ),
							// 'text_width' => 80,
							// 'default'    => false,
							// 'dependency' => array( 'wpcp_carousel_type', 'any', 'image-carousel,video-carousel', true ),
							// ),
						),
					),
					// Image Style.
					array(
						'title'  => __( 'Image Styles', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-tab_image-settings',
						'fields' => array(
							array(
								'id'         => 'show_image',
								'type'       => 'switcher',
								'title'      => __( 'Image', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide slide image.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', 'any', 'post-carousel,product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_image_sizes',
								'type'       => 'image_sizes',
								'chosen'     => true,
								'title'      => __( 'Dimensions', 'wp-carousel-free' ),
								'default'    => 'medium',
								'subtitle'   => __( 'Set dimensions for the image.', 'wp-carousel-free' ),
								'dependency' => array( 'wpcp_carousel_type|show_image', 'any|==', 'image-carousel,post-carousel,product-carousel|true', true ),
							),
							array(
								'id'                => 'wpcp_image_crop_size',
								'type'              => 'dimensions_advanced',
								'title'             => __( 'Custom Size', 'wp-carousel-free' ),
								'class'             => 'wpcp_carousel_row_pro_only',
								'subtitle'          => __( 'Set width and height of the image.', 'wp-carousel-free' ),
								'chosen'            => true,
								'bottom'            => false,
								'left'              => false,
								'color'             => false,
								'top_icon'          => '<i class="fa fa-arrows-h"></i>',
								'right_icon'        => '<i class="fa fa-arrows-v"></i>',
								'top_placeholder'   => 'width',
								'right_placeholder' => 'height',
								'styles'            => array(
									'Soft-crop',
									'Hard-crop',
								),
								'default'           => array(
									'top'   => '600',
									'right' => '400',
									'style' => 'Soft-crop',
									'unit'  => 'px',
								),
								'attributes'        => array(
									'min' => 0,
								),
								'dependency'        => array( 'wpcp_carousel_type|wpcp_image_sizes|show_image', 'any|==|==', 'image-carousel,post-carousel,product-carousel|custom|true', true ),
							),
							array(
								'id'         => 'load_2x_image',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'title'      => __(
									'Load 2x Resolution Image in Retina Display',
									'wp-carousel-free'
								),
								'subtitle'   => __(
									'You should upload 2x sized images to show in retina display.',
									'wp-carousel-free'
								),
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type|wpcp_image_sizes|show_image', 'any|==|==', 'image-carousel,post-carousel,product-carousel|custom|true', true ),
							),
							array(
								'id'         => '_variable_width',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Variable Width', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable variable width.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/variable-width.svg" alt="' . __( 'Variable Width', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Variable Width', 'wp-carousel-free' ) . '</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-enable-the-variable-width/" target="_blank">' . __( 'Open Docs', 'wp-carousel-free' ) . '</a><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/variable-width/" target="_blank">' . __( 'Live Demo', 'wp-carousel-free' ) . '</a>',
								'default'    => false,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
							),
							array(
								'id'       => 'wpcp_image_gray_scale',
								'type'     => 'select',
								'class'    => 'wpcp_image_gray_scale_pro',
								'title'    => __( 'Image Mode', 'wp-carousel-free' ),
								'subtitle' => __( 'Set a mode for the images.', 'wp-carousel-free' ),
								'options'  => array(
									''  => __( 'Original', 'wp-carousel-free' ),
									'1' => array(
										'text'     => __( 'Grayscale and original on hover (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'2' => array(
										'text'     => __( 'Grayscale on hover (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'3' => array(
										'text'     => __( 'Always grayscale (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'4' => array(
										'text'     => __( 'Custom Color (Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'  => '',
								'class'    => 'chosen',
							),
							array(
								'id'         => 'wpcp_image_lazy_load',
								'type'       => 'button_set',
								'title'      => __( 'Lazy Load', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set lazy load option for the image.', 'wp-carousel-free' ),
								'options'    => array(
									'false'    => __( 'Off', 'wp-carousel-free' ),
									'ondemand' => __( 'On Demand', 'wp-carousel-free' ),
								),
								'radio'      => true,
								'default'    => 'false',
								'dependency' => array( 'wpcp_carousel_type|wpcp_carousel_mode|show_image|wpcp_layout', 'any|!=|==', 'image-carousel,post-carousel,product-carousel|ticker|true|carousel', true ),
							),
							array(
								'id'         => 'wpcp_image_zoom',
								'type'       => 'select',
								'title'      => __( 'Zoom Effect', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set a zoom effect on hover the image.', 'wp-carousel-free' ),
								'title_help' => sprintf(
								/* translators: 1: start div tag, 2: close div and start another div tag 3: close div and start link tag 4: close link tag. */
									__( '%1$sZoom%2$sThis feature lets you choose a specific zoom effect when hovering over an image for an engaging experience.%3$sLive Demo%4$s', 'wp-carousel-free' ),
									'<div class="sp_wpcp-info-label">',
									'</div><div class="sp_wpcp-short-content">',
									'</div><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/post-carousel-zoom-image-modes/" target="_blank">',
									'</a>'
								),
								'options'    => array(
									''         => __( 'None', 'wp-carousel-free' ),
									'zoom_in'  => __( 'Zoom In', 'wp-carousel-free' ),
									'zoom_out' => __( 'Zoom Out', 'wp-carousel-free' ),
								),
								'default'    => 'zoom_in',
								'class'      => 'chosen',
								'dependency' => array( 'wpcp_carousel_type|show_image', 'any|==', 'image-carousel,post-carousel,product-carousel|true', true ),
							),
							array(
								'id'         => 'wpcp_product_image_border',
								'type'       => 'border',
								'title'      => __( 'Image Border', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set border for the product image.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_border_field',
								'all'        => true,
								'default'    => array(
									'all'   => '1',
									'style' => 'solid',
									'color' => '#dddddd',
								),
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_watermark',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'title'      => __( 'Watermark', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable to add watermark to the image.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/watermark.svg" alt="' . __( 'Watermark', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Watermark', 'wp-carousel-free' ) . '</div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-watermark/" target="_blank">' . __( 'Open Docs', 'wp-carousel-free' ) . '</a><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/watermark-protection/" target="_blank">' . __( 'Live Demo', 'wp-carousel-free' ) . '</a>',
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
							),
							array(
								'id'         => 'wpcp_img_protection',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'title'      => __( 'Image Protection', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable to protect image downloading from right-click.', 'wp-carousel-free' ),
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),

							),
							array(
								'id'         => '_image_title_attr',
								'type'       => 'switcher',
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'title'      => __( 'Image Title Attribute', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide image title attribute.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'dependency' => array( 'wpcp_carousel_type|show_image', 'any|==', 'image-carousel,post-carousel,product-carousel|true', true ),
							),
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'class'   => 'image-settings-tab-notice',
								'content' => sprintf(
								/* translators: 1: start bold tag, 2: close bold and start link tag 3: close bold and link tag. */
									__( 'Want to take your image editing experience to the next level with %1$sImage Variable Width, Watermark, Protection from Right-click, Grayscale, Custom Color, and Custom Size? %2$sUpgrade to Pro!%3$s', 'wp-carousel-free' ),
									'<b>',
									'</b><a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
						),
					),
					// Post Content.
					array(
						'title'  => __( 'Post Content', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-tab_post-meta',
						'fields' => array(
							// Post Settings.
							array(
								'id'         => 'wpcp_post_title',
								'type'       => 'switcher',
								'title'      => __( 'Post Title', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post title.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_title_chars_limit',
								'class'      => 'pro_only_field',
								'type'       => 'spinner',
								'title'      => __( 'Length', 'wp-carousel-free' ),
								'subtitle'   => __( 'Leave empty to show full post title.', 'wp-carousel-free' ),
								'default'    => '',
								'min'        => 0,
								'unit'       => 'Letters',
								'dependency' => array( 'wpcp_carousel_type|wpcp_post_title', '==|==', 'post-carousel|true', true ),
							),
							array(
								'id'         => 'wpcp_post_content_show',
								'type'       => 'switcher',
								'title'      => __( 'Post Content', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post content.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_content_type',
								'class'      => 'wpcp_post_content_type',
								'type'       => 'select',
								'title'      => __( 'Content Display Type', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a content display type.', 'wp-carousel-free' ),
								'desc'       => 'This is a <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank">Pro Feature</a>!',
								'options'    => array(
									'excerpt'            => array(
										'text' => __( 'Excerpt', 'wp-carousel-free' ),
									),
									'content'            => array(
										'text' => __( 'Full Content', 'wp-carousel-free' ),
									),
									'content_with_limit' => array(
										'text' => __( 'Content with Limit', 'wp-carousel-free' ),
									),
								),
								'default'    => 'excerpt',
								'dependency' => array( 'wpcp_carousel_type|wpcp_post_content_show', '==|==', 'post-carousel|true', true ),
							),
							array(
								'id'         => 'wpcp_post_content_words_limit',
								'type'       => 'spinner',
								'class'      => 'pro_only_field',
								'title'      => __( 'Words Limit', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set post content words limit. Default value is 30 words.', 'wp-carousel-free' ),
								'default'    => 30,
								'min'        => 0,
								'dependency' => array( 'wpcp_carousel_type|wpcp_post_content_show|wpcp_post_content_type', '==|==|==', 'post-carousel|true|content_with_limit', true ),
							),
							array(
								'id'         => 'wpcp_post_readmore_button_show',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'title'      => __( 'Read More Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide content read more button.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => true,
								'dependency' => array( 'wpcp_post_content_type|wpcp_carousel_type', '!=|==', 'content|post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_readmore_text',
								'type'       => 'text',
								'class'      => 'pro_only_field',
								'title'      => __( 'Read More Button Label', 'wp-carousel-free' ),
								'subtitle'   => __( 'Change the read more button label text.', 'wp-carousel-free' ),
								'default'    => 'Read More',
								'dependency' => array( 'wpcp_carousel_type|wpcp_post_content_type', '==|any', 'post-carousel|content_with_limit,excerpt', true ),
							),
							array(
								'type'       => 'subheading',
								'content'    => __( 'Post Meta', 'wp-carousel-free' ),
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),



							array(
								'id'         => 'wpcp_post_author_show',
								'type'       => 'switcher',
								'title'      => __( 'Author', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post author name.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_date_show',
								'type'       => 'switcher',
								'title'      => __( 'Date', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post date.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_comment_show',
								'type'       => 'switcher',
								'title'      => __( 'Comment', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post comment number.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_category_show',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'title'      => __( 'Category', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post category name.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),

							array(
								'id'         => 'wpcp_post_tags_show',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Tag', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post tags.', 'wp-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'type'       => 'subheading',
								'content'    => __( 'Social Share', 'wp-carousel-free' ),
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_social_show',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Social Share', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post social share.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
						),
					),

					// Product info.
					array(
						'title'  => __( 'Product Info', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-tab_product-info',
						'fields' => array(
							array(
								'id'         => 'wpcp_product_name',
								'type'       => 'switcher',
								'title'      => __( 'Product Name', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide product name.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_product_name_chars_limit',
								'class'      => 'pro_only_field',
								'type'       => 'spinner',
								'title'      => __( 'Length', 'wp-carousel-free' ),
								'subtitle'   => __( 'Leave empty to show full product name.', 'wp-carousel-free' ),
								'default'    => '',
								'min'        => 0,
								'unit'       => 'Letters',
								'dependency' => array( 'wpcp_carousel_type|wpcp_product_name', '==|==', 'product-carousel|true', true ),
							),
							array(
								'id'         => 'wpcp_product_desc',
								'class'      => 'wpcp_product_desc',
								'type'       => 'button_set',
								'title'      => __( 'Product Description', 'wp-carousel-free' ),
								'subtitle'   => __( 'Choose the description display type.', 'wp-carousel-free' ),
								'desc'       => 'This is a <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank">Pro Feature</a>!',
								'options'    => array(
									'full'  => __( 'Full', 'wp-carousel-free' ),
									'short' => __( 'Short', 'wp-carousel-free' ),
									'hide'  => __( 'Hide', 'wp-carousel-free' ),
								),
								'default'    => 'hide',
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_product_desc_limit_number',
								'type'       => 'spinner',
								'class'      => 'pro_only_field',
								'title'      => __( 'Limit', 'wp-carousel-free' ),
								'subtitle'   => __( 'Leave empty to show full product description.', 'wp-carousel-free' ),
								'default'    => '15',
								'min'        => 0,
								'unit'       => 'Words',
								'dependency' => array( 'wpcp_carousel_type|wpcp_product_desc', '==|any', 'product-carousel|full,short', true ),
							),
							array(
								'id'         => 'wpcp_product_readmore_text',
								'class'      => 'pro_only_field',
								'type'       => 'text',
								'title'      => __( 'Read More Label', 'wp-carousel-free' ),
								'subtitle'   => __( 'Change the read more button label text.', 'wp-carousel-free' ),
								'default'    => 'Read More',
								'dependency' => array( 'wpcp_carousel_type|wpcp_product_desc', '==|any', 'product-carousel|full,short', true ),
							),

							array(
								'id'         => 'wpcp_product_price',
								'type'       => 'switcher',
								'title'      => __( 'Product Price', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide product price.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_product_rating',
								'type'       => 'switcher',
								'title'      => __( 'Product Rating', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide product rating.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_product_cart',
								'type'       => 'switcher',
								'title'      => __( 'Add to Cart Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide add to cart button.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_social_show',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Social Share', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide post social share.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'type'       => 'subheading',
								'content'    => __( 'Product Brands', 'wp-carousel-free' ),
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'show_product_brands',
								'type'       => 'switcher',
								'title'      => __( 'Show Brands', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide product brands.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'type'       => 'submessage',
								'style'      => 'info',
								'content'    => sprintf(
								/* translators: 1: start link tag, 2: close link tag. */
									__( 'To Enable Product Brands feature, you must Install and Activate the %1$sSmart Brands for WooCommerce%2$s plugin. %3$s', 'wp-carousel-free' ),
									'<a class="thickbox open-plugin-details-modal" href="' . esc_url( $smart_brand_plugin_data['plugin_link'] ) . '">',
									'</a>',
									'<a href="#" class="brand-plugin-install' . $smart_brand_plugin_data['has_plugin'] . '" data-url="' . $smart_brand_plugin_data['activate_plugin_url'] . '" data-nonce="' . wp_create_nonce( 'updates' ) . '"> ' . $smart_brand_plugin_data['button_text'] . ' <i class="fa fa-angle-double-right"></i></a>'
								),
								'dependency' => array( 'show_product_brands|wpcp_carousel_type', '==|==', 'true|product-carousel', true ),
							),
							array(
								'type'       => 'subheading',
								'content'    => __( 'Quick View Button', 'wp-carousel-free' ),
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'quick_view',
								'type'       => 'switcher',
								'title'      => __( 'Show Quick View Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide quick view button.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'type'       => 'submessage',
								'style'      => 'info',
								'content'    => sprintf(
								/* translators: 1: start link tag, 2: close tag. */
									__( 'To Enable Quick view feature, you must Install and Activate the %1$sQuick View for WooCommerce%2$s plugin. %3$s', 'wp-carousel-free' ),
									'<a class="thickbox open-plugin-details-modal" href="' . esc_url( $quick_view_plugin_data['plugin_link'] ) . '">',
									'</a>',
									'<a href="#" class="quick-view-install' . $quick_view_plugin_data['has_plugin'] . '" data-url="' . $quick_view_plugin_data['activate_plugin_url'] . '" data-nonce="' . wp_create_nonce( 'updates' ) . '"> ' . $quick_view_plugin_data['button_text'] . ' <i class="fa fa-angle-double-right"></i></a>'
								),
								'dependency' => array( 'quick_view|wpcp_carousel_type', '==|==', 'true|product-carousel', true ),
							),
						),
					),
					// Image Content.
					array(
						'title'  => __( 'Title & Description', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-title_description',
						'fields' => array(
							array(
								'id'         => 'wpcp_image_caption',
								'type'       => 'switcher',
								'title'      => __( 'Title', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide title for the image.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
							),
							array(
								'id'         => 'wpcp_image_title_source',
								'type'       => 'select',
								'title'      => __( 'Title Source', 'wp-carousel-free' ),
								'subtitle'   => __( 'Choose a title source.', 'wp-carousel-free' ),
								'options'    => array(
									'title'   => __( 'Title', 'wp-carousel-free' ),
									'caption' => array(
										'text'     => __( 'Caption(Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'alt'     => array(
										'text'     => __( 'Alt Text(Pro)', 'wp-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'    => 'title',
								'dependency' => array( 'wpcp_image_caption', '==', 'true' ),
							),
							array(
								'id'         => 'wpcp_image_desc',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Description', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide description for image.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => false,
							),
							array(
								'id'       => 'img_desc_display_type',
								'type'     => 'button_set',
								'class'    => 'pro_only_field',
								'title'    => __( 'Display Type', 'wp-carousel-free' ),
								'subtitle' => __( 'Choose the description display type.', 'wp-carousel-free' ),
								'options'  => array(
									'full'  => __( 'Full', 'wp-carousel-free' ),
									'limit' => __( 'Limit', 'wp-carousel-free' ),
								),
								'default'  => 'limit',
							),
							array(
								'id'       => 'img_desc_word_limit',
								'type'     => 'spinner',
								'class'    => 'pro_only_field',
								'title'    => __( 'Words Limit', 'wp-carousel-free' ),
								'subtitle' => __( 'Set description words limit.', 'wp-carousel-free' ),
								'default'  => '30',
								'min'      => 0,
							),
							array(
								'id'         => 'img_desc_read_more',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Read More Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide description read more button.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 77,
								'default'    => true,
							),
							array(
								'id'       => 'img_readmore_label',
								'type'     => 'text',
								'class'    => 'pro_only_field',
								'title'    => __( 'Read More Button Label', 'wp-carousel-free' ),
								'subtitle' => __( 'Change the read more button label text.', 'wp-carousel-free' ),
								'default'  => 'Read More',
							),
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'class'   => 'sp-settings-pro-notice ',
								'content' => sprintf(
									/* translators: 1: start bold tag, 2: close bold tag 3: start link and bold tag 4: close bold and link tag. */
									__( 'To show the Image Description, Limit Words, and Read More button, %1$sUpgrade to Pro!%2$s', 'wp-carousel-free' ),
									'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
						),
					),
					// Typographpy.
					array(
						'title'  => __( 'Typographpy', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-tab_typography',
						'fields' => array(
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'class'   => 'watermark-pro-notice typography-pro-notice',
								'content' => sprintf(
									/* translators: 1: start bold tag, 2: close bold tag 3: start link and bold tag 4: close bold and link tag. */
									__( 'Want to customize everything %1$s(Colors and Typography)%2$s easily? %3$sUpgrade to Pro!%4$s', 'wp-carousel-free' ),
									'<b>',
									'</b>',
									'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'section_title_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Load Section Title Font', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for the section title.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
							),
							array(
								'id'            => 'wpcp_section_title_typography',
								'class'         => 'disable-color-picker',
								'type'          => 'typography',
								'title'         => __( 'Section Title Font', 'wp-carousel-free' ),
								'subtitle'      => __( 'Set the section title font properties.', 'wp-carousel-free' ),
								'margin_bottom' => true,
								'default'       => array(
									'color'          => '#444444',
									'font-family'    => 'Open Sans',
									'font-weight'    => '600',
									'font-size'      => '24',
									'line-height'    => '28',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'text-transform' => 'none',
									'type'           => 'google',
									'unit'           => 'px',
									'margin-bottom'  => '30',
									'Set the section title font properties.' => 'px',
								),
								'preview'       => 'always',
								'preview_text'  => 'Section Title',
							),
							array(
								'id'         => 'wpcp_image_caption_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Load Caption Font', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for the image caption.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
							),
							array(
								'id'           => 'wpcp_image_caption_typography',
								'class'        => 'disable-color-picker',
								'type'         => 'typography',
								'title'        => __( 'Caption Font', 'wp-carousel-free' ),
								'subtitle'     => __( 'Set caption font properties.', 'wp-carousel-free' ),
								'class'        => 'disable-color-picker',
								'default'      => array(
									'color'          => '#333',
									'font-family'    => 'Open Sans',
									'font-weight'    => '600',
									'font-size'      => '15',
									'line-height'    => '23',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'text-transform' => 'capitalize',
									'type'           => 'google',
								),
								'preview_text' => 'The image caption',
								'dependency'   => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
							),
							array(
								'id'         => 'wpcp_image_desc_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Load Description Font', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for the image description.', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => false,
								'dependency' => array( 'wpcp_carousel_type|wpcp_post_title', '==|==', 'image-carousel|true', true ),
							),
							array(
								'id'         => 'wpcp_image_desc_typography',
								'class'      => 'disable-color-picker',
								'type'       => 'typography',
								'title'      => __( 'Description Font', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set description font properties.', 'wp-carousel-free' ),
								'class'      => 'disable-color-picker',
								'default'    => array(
									'color'          => '#333',
									'font-family'    => 'Open Sans',
									'font-weight'    => '400',
									'font-style'     => 'normal',
									'font-size'      => '14',
									'line-height'    => '21',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'type'           => 'google',
								),
								'dependency' => array( 'wpcp_carousel_type', '==', 'image-carousel', true ),
							),
							// Post Typography.
							array(
								'id'         => 'wpcp_title_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Load Title Font', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for the slide title.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'           => 'wpcp_title_typography',
								'class'        => 'disable-color-picker',
								'type'         => 'typography',
								'title'        => __( 'Post Title Font', 'wp-carousel-free' ),
								'subtitle'     => __( 'Set title font properties.', 'wp-carousel-free' ),
								'default'      => array(
									'color'          => '#444',
									'hover_color'    => '#555',
									'font-family'    => 'Open Sans',
									'font-style'     => '600',
									'font-size'      => '20',
									'line-height'    => '30',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'text-transform' => 'capitalize',
									'type'           => 'google',
								),
								'hover_color'  => true,
								'preview_text' => 'The Post Title',
								'dependency'   => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_content_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Post Content Font Load', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for post the content.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_content_typography',
								'class'      => 'disable-color-picker',
								'type'       => 'typography',
								'title'      => __( 'Post Content Font', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set post content font properties.', 'wp-carousel-free' ),
								'default'    => array(
									'color'          => '#333',
									'font-family'    => 'Open Sans',
									'font-style'     => '400',
									'font-size'      => '16',
									'line-height'    => '26',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'type'           => 'google',
								),
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'         => 'wpcp_post_meta_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Post Meta Font Load', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for the post meta.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'dependency' => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							array(
								'id'           => 'wpcp_post_meta_typography',
								'class'        => 'disable-color-picker',
								'type'         => 'typography',
								'title'        => __( 'Post Meta Font', 'wp-carousel-free' ),
								'subtitle'     => __( 'Set post meta font properties.', 'wp-carousel-free' ),
								'default'      => array(
									'color'          => '#999',
									'font-family'    => 'Open Sans',
									'font-style'     => '400',
									'font-size'      => '14',
									'line-height'    => '24',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'type'           => 'google',
								),
								'preview_text' => 'Post Meta', // Replace preview text with any text you like.
								'dependency'   => array( 'wpcp_carousel_type', '==', 'post-carousel', true ),
							),
							// Product Typography.
							array(
								'id'         => 'wpcp_product_name_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Product Name Font Load', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for the product name.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'           => 'wpcp_product_name_typography',
								'class'        => 'disable-color-picker',
								'type'         => 'typography',
								'title'        => __( 'Product Name Font', 'wp-carousel-free' ),
								'subtitle'     => __( 'Set product name font properties.', 'wp-carousel-free' ),
								'default'      => array(
									'color'          => '#444',
									'hover_color'    => '#555',
									'font-family'    => 'Open Sans',
									'font-style'     => '400',
									'font-size'      => '15',
									'line-height'    => '23',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'type'           => 'google',
								),
								'hover_color'  => true,
								'preview_text' => 'Product Name', // Replace preview text.
								'dependency'   => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'         => 'wpcp_product_price_font_load',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Product Price Font Load', 'wp-carousel-free' ),
								'subtitle'   => __( 'On/Off google font for the product price.', 'wp-carousel-free' ),
								'default'    => false,
								'text_width' => 80,
								'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
							array(
								'id'           => 'wpcp_product_price_typography',
								'class'        => 'disable-color-picker',
								'type'         => 'typography',

								'title'        => __( 'Product Price Font', 'wp-carousel-free' ),
								'subtitle'     => __( 'Set product price font properties.', 'wp-carousel-free' ),
								'default'      => array(
									'color'          => '#222',
									'font-family'    => 'Open Sans',
									'font-style'     => '700',
									'font-size'      => '14',
									'line-height'    => '26',
									'letter-spacing' => '0',
									'text-align'     => 'center',
									'type'           => 'google',
								),
								'preview_text' => '$49.00', // Replace preview text with any text you like.
								'dependency'   => array( 'wpcp_carousel_type', '==', 'product-carousel', true ),
							),
						), // End of fields array.
					),
				),
			),
		), // End of fields array.
	)
); // Style settings section end.

//
// Lightbox settings section begin.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'Lightbox Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-search',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'wp-carousel-lightbox-settings-tabs',
				'tabs'  => array(
					array(
						'title'  => __( 'General (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-general-2',
						'fields' => array(// Navigation.
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'class'   => 'wpc-lightbox-general',
								'content' => sprintf(
									/* translators: 1: start bold tag, 2: close bold tag 3: start link and bold tag 4: close bold and link tag. */
									__( 'The basic lightbox works in the Lite version. To unleash the full potential of your images with %1$s28+ Pro Lightbox%2$s options, %3$sUpgrade to Pro!%4$s', 'wp-carousel-free' ),
									'<b>',
									'</b>',
									'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'l_box_autoplay',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'AutoPlay ', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable to automatically start slideshow.', 'wp-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
							),
							array(
								'id'              => 'l_box_autoplay_speed',
								'type'            => 'slider',
								'class'           => 'pro_only_field',
								'title'           => __( 'Speed', 'wp-carousel-free' ),
								'subtitle'        => __( 'The timeout between sliding to the next slide in milliseconds.', 'wp-carousel-free' ),
								'all'             => true,
								'all_text'        => false,
								'all_placeholder' => 'speed',
								'default'         => '4000',
								'unit'            => 'ms',
								'min'             => 0,
								'max'             => 20000,
							),
							array(
								'id'         => 'l_box_loop',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Loop', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable infinite gallery navigation.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_keyboard_nav',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Keyboard Navigation', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable keyboard navigation for the lightbox image.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'       => 'l_box_nav_arrow_color',
								'class'    => 'pro_only_field',
								'type'     => 'color_group',
								'title'    => __( 'Lightbox Navigation Arrow', 'wp-carousel-free' ),
								'subtitle' => __( 'Set navigation color for the lightbox.', 'wp-carousel-free' ),
								'sanitize' => 'wpcf_sanitize_color_group_field',
								'options'  => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
									'color3' => __( 'Background', 'wp-carousel-free' ),
									'color4' => __( 'Hover Background', 'wp-carousel-free' ),
								),
								'default'  => array(
									'color1' => '#ccc',
									'color2' => '#fff',
									'color3' => '#1e1e1e',
									'color4' => '#1e1e1e',
								),
							),
							array(
								'id'       => 'wpcp_img_lb_overlay_color',
								'class'    => 'pro_only_field',
								'type'     => 'color',
								'title'    => __( 'Overlay Background Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Set overlay background color for lightbox.', 'wp-carousel-free' ),
								'default'  => '#0b0b0b',
							),
							array(
								'id'         => 'l_box_outside_close',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Overlay/Outside Close', 'wp-carousel-free' ),
								'subtitle'   => __( 'Close when clicked outside of the image and content or dark overlay.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'type'    => 'subheading',
								'content' => __( 'Lightbox Icons (Pro)', 'wp-carousel-free' ),
							),
							array(
								'id'       => 'l_box_icon_style',
								'class'    => 'l_box_icon_style pro_only_field',
								'type'     => 'button_set',
								'title'    => __( 'Lightbox Icon Style', 'wp-carousel-free' ),
								'subtitle' => __( 'Choose a icon on hover image.', 'wp-carousel-free' ),
								'multiple' => false,
								'options'  => array(
									'search'      => '<i class="fa fa-search"></i>',
									'plus'        => '<i class="fa fa-plus"></i>',
									'zoom'        => '<i class="fa fa-search-plus"></i>',
									'eye'         => '<i class="fa fa-eye"></i>',
									'info'        => '<i class="fa fa-info"></i>',
									'expand'      => '<i class="fa fa-expand"></i>',
									'arrow_alt'   => '<i class="fa fa-arrows-alt"></i>',
									'plus_square' => '<i class="fa fa-plus-square-o"></i>',
									'none'        => array(
										'option_name' => __( 'none', 'wp-carousel-free' ),
										'pro_only'    => true,
									),
								),
								'default'  => array( 'search' ),
							),
							array(
								'id'         => 'l_box_icon_position',
								'type'       => 'image_select',
								'title'      => __( 'Icon Display Position', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a icon display position on image.', 'wp-carousel-free' ),
								'class'      => 'wpcp_content_position',
								'title'      => __( 'Thumbnail Position', 'wp-carousel-free' ),
								'options'    => array(
									'middle'       => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/lightbox-icon-middle.svg',
										'text'  => __( 'Middle', 'wp-carousel-free' ),
									),
									'top_right'    => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-icon-top-right.svg',
										'text'     => __( 'Top Right', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'top_left'     => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-icon-top-left.svg',
										'text'     => __( 'Top Left', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'bottom_right' => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-icon-bottom-right.svg',
										'text'     => __( 'Bottom Right', 'wp-carousel-free' ),
										'pro_only' => true,
									),
									'bottom_left'  => array(
										'image'    => plugin_dir_url( __DIR__ ) . 'img/lightbox-icon-bottom-left.svg',
										'text'     => __( 'Bottom Left', 'wp-carousel-free' ),
										'pro_only' => true,
									),
								),
								'default'    => array( 'middle' ),
								'dependency' => array( 'wpcp_carousel_type|wpcp_logo_link_show|l_box_icon_style|wpcp_post_detail_position', 'any|==|!=|!=', 'image-carousel,mix-content,external-carousel|l_box|none|with_overlay', true ),
							),
							array(
								'id'       => 'l_box_icon_size',
								'class'    => 'border_radius_around pro_only_field',
								'type'     => 'spinner',
								'title'    => __( 'Icon Size', 'wp-carousel-free' ),
								'subtitle' => __( 'Set icon size for image.', 'wp-carousel-free' ),
								'default'  => 16,
								'unit'     => 'px',
							),
							array(
								'id'       => 'l_box_icon_color',
								'type'     => 'color_group',
								'class'    => 'pro_only_field',
								'title'    => __( 'Icon Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Set color for the lightbox icon.', 'wp-carousel-free' ),
								'sanitize' => 'wpcf_sanitize_color_group_field',
								'options'  => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
									'color3' => __( 'Background', 'wp-carousel-free' ),
									'color4' => __( 'Hover Background', 'wp-carousel-free' ),
								),
								'default'  => array(
									'color1' => '#fff',
									'color2' => '#fff',
									'color3' => 'rgba(0, 0, 0, 0.5)',
									'color4' => 'rgba(0, 0, 0, 0.8)',
								),
							),
							array(
								'type'    => 'subheading',
								'content' => __( 'Animations (Pro)', 'wp-carousel-free' ),
							),
							array(
								'id'       => 'l_box_sliding_effect',
								'class'    => 'pro_only_field',
								'type'     => 'select',
								'title'    => __( 'Transition Effect Between Slides', 'wp-carousel-free' ),
								'subtitle' => __( 'Select a transition effect between slides for lightbox image.', 'wp-carousel-free' ),
								'multiple' => false,
								'options'  => array(
									'fade'        => __( 'Fade', 'wp-carousel-free' ),
									'slide'       => __( 'Slide', 'wp-carousel-free' ),
									'circular'    => __( 'Circular', 'wp-carousel-free' ),
									'tube'        => __( 'Tube', 'wp-carousel-free' ),
									'zoom-in-out' => __( 'Zoom-in-out', 'wp-carousel-free' ),
									'rotate'      => __( 'Rotate', 'wp-carousel-free' ),
									'none'        => __( 'None', 'wp-carousel-free' ),
								),
								'default'  => array( 'fade' ),
							),
							array(
								'id'       => 'l_box_open_close_effect',
								'class'    => 'pro_only_field',
								'type'     => 'select',
								'title'    => __( 'Open/Close Animation Type', 'wp-carousel-free' ),
								'subtitle' => __( 'Select an animation type for opening/closing lightbox image.', 'wp-carousel-free' ),
								'multiple' => false,
								'options'  => array(
									'zoom'        => __( 'Zoom', 'wp-carousel-free' ),
									'fade'        => __( 'Fade', 'wp-carousel-free' ),
									'slide'       => __( 'Slide', 'wp-carousel-free' ),
									'circular'    => __( 'Circular', 'wp-carousel-free' ),
									'tube'        => __( 'Tube', 'wp-carousel-free' ),
									'zoom-in-out' => __( 'Zoom-in-out', 'wp-carousel-free' ),
									'rotate'      => __( 'Rotate', 'wp-carousel-free' ),
									'none'        => __( 'None', 'wp-carousel-free' ),
								),
								'default'  => array( 'zoom' ),
							),
						),
					),
					array(
						'title'  => __( 'Image & Thumbs (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-image-and-thumbnail',
						'fields' => array(// Navigation.
							array(
								'id'         => 'l_box_icon_overlay_color',
								'class'      => 'pro_only_field',
								'type'       => 'color',
								'title'      => __( 'Image Icon Overlay Color', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set icon overlay color for image.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/image-icon-overlay-color.svg" alt="' . __( 'Image Icon Overlay Color', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Image Icon Overlay Color', 'wp-carousel-free' ) . '</div>',
								'default'    => 'rgba(0,0,0,0.5)',
							),
							array(
								'id'         => 'wpcp_l_box_image_caption',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'title'      => __( 'Image Title', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide image title for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'       => 'wpcp_lb_caption_color',
								'class'    => 'pro_only_field',
								'type'     => 'color',
								'title'    => __( 'Caption Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Change the color for lightbox image caption.', 'wp-carousel-free' ),
								'default'  => '#ffffff',
							),
							array(
								'id'         => 'l_box_desc',
								'class'      => 'wpcf_show_hide',
								'type'       => 'switcher',
								'title'      => __( 'Image Description', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide image description for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => false,
							),
							array(
								'id'       => 'l_box_desc_color',
								'class'    => 'pro_only_field',
								'type'     => 'color',
								'title'    => __( 'Description Color', 'wp-carousel-free' ),
								'subtitle' => __( 'Change the color for lightbox image description.', 'wp-carousel-free' ),
								'default'  => '#ffffff',
							),
							array(
								'id'         => 'wpcp_image_counter',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Image Counter', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide image counter for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'       => 'l_box_hover_img_on_mobile',
								'class'    => 'pro_only_field',
								'type'     => 'checkbox',
								'title'    => __( 'Disable Image Hover Overlay on the Mobile Devices', 'wp-carousel-free' ),
								'subtitle' => __( 'Check to disable image hover overlay on the mobile devices.', 'wp-carousel-free' ),
								'default'  => false,
							),
							array(
								'id'         => 'wpcp_thumbnails_gallery',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Lightbox Bottom Thumbnails Gallery Icon', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide bottom thumbnails gallery icon for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_thumb_visibility',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Bottom Thumbnails Gallery Visibility', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide bottom thumbnails gallery visibility for lightbox.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-img-tag"><img src="' . plugin_dir_url( __DIR__ ) . 'img/help-visuals/lightbox-thumbnail.svg" alt="' . __( 'Bottom Thumbnail Gallery Visibility', 'wp-carousel-free' ) . '"></div><div class="sp_wpcp-info-label">' . __( 'Bottom Thumbnail Gallery Visibility', 'wp-carousel-free' ) . '</div>',
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_protect_image',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Protect Images', 'wp-carousel-free' ),
								'subtitle'   => __( 'Protect an image downloading from right-click.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
							),
						),
					),
					array(
						'title'  => __( 'Toolbar (Pro)', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-lightbox-toolbar',
						'fields' => array(// Toolbar.
							array(
								'id'         => 'l_box_zoom_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Zoom Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide zoom button for lightbox image.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_full_screen_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Full-Screen Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide full-screen button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_slideshow_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Slideshow Play Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide slideshow play button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_social_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Social Share Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide social share button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_download_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Download Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide download button for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
							array(
								'id'         => 'l_box_close_button',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Close Button', 'wp-carousel-free' ),
								'subtitle'   => __( 'Show/Hide close bottom for lightbox.', 'wp-carousel-free' ),
								'text_on'    => __( 'Show', 'wp-carousel-free' ),
								'text_off'   => __( 'Hide', 'wp-carousel-free' ),
								'text_width' => 80,
								'default'    => true,
							),
						),
					),
				),
			),
		), // End of fields array.
	)
); // Style settings section end.

//
// Carousel settings section begin.
//
SP_WPCF::createSection(
	$wpcp_carousel_shortcode_settings,
	array(
		'title'  => __( 'Carousel Settings', 'wp-carousel-free' ),
		'icon'   => 'fa fa-sliders',
		'fields' => array(
			array(
				'type'  => 'tabbed',
				'class' => 'wp-carousel-display-tabs',
				'tabs'  => array(
					array(
						'title'  => __( 'Basics', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-tab_basic-settings',
						'fields' => array(

							array(
								'id'         => 'wpcp_carousel_auto_play',
								'type'       => 'switcher',
								'title'      => __( 'AutoPlay', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable auto play.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'carousel_auto_play_speed',
								'type'       => 'slider',
								'sanitize'   => 'wpcf_sanitize_number_field',
								'title'      => __( 'AutoPlay Delay Time', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set auto play delay time in millisecond.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-info-label">' . __( 'AutoPlay Delay Time', 'wp-carousel-free' ) . '</div><div class="sp_wpcp-short-content">' . __( 'Set autoplay delay or interval time. The amount of time to delay between automatically carousel item. e.g. 1000 milliseconds(ms) = 1 second.', 'wp-carousel-free' ) . '</div>',
								'unit'       => __( 'ms', 'wp-carousel-free' ),
								'step'       => 100,
								'min'        => 100,
								'max'        => 50000,
								'default'    => 3000,
								'dependency' => array(
									'wpcp_carousel_auto_play',
									'==',
									'true',
								),
							),
							array(
								'id'         => 'standard_carousel_scroll_speed',
								'type'       => 'slider',
								'sanitize'   => 'wpcf_sanitize_number_field',
								'title'      => __( 'Carousel Speed', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set autoplay scroll speed in millisecond.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-info-label">' . __( 'Carousel Speed', 'wp-carousel-free' ) . '</div><div class="sp_wpcp-short-content">' . __( 'Set carousel scrolling speed. e.g. 1000 milliseconds(ms) = 1 second.', 'wp-carousel-free' ) . '</div>',
								'unit'       => __( 'ms', 'wp-carousel-free' ),
								'step'       => 50,
								'min'        => 100,
								'max'        => 20000,
								'default'    => 600,
							),
							array(
								'id'         => 'wpcp_carousel_orientation',
								'type'       => 'button_set',
								'class'      => 'wpcp_carousel_orientation',
								'title'      => __( 'Carousel Orientation', 'wp-carousel-free' ),
								'subtitle'   => __( 'Choose a carousel orientation.', 'wp-carousel-free' ),
								'title_help' => sprintf(
								/* translators: 1: start div tag 2: close div and start antoher div tag 3: start strong tag 4: close strong tag 5: start bold tag 6: close bold tag 7: start strong tag 8: close bold, div and start link tag 9. close link and start another link 10: close link tag. */
									__(
										'%1$sCarousel Orientation %2$sChoose the carousel slide movement:%3$sHorizontal%4$s: If you want the slides to transition horizontally, select %5$sHorizontal%6$s. %7$sVertical (Pro)%4$s:  If you want the slides to transition vertically, select %5$sVertical%8$sOpen Docs%9$sLive Demo%10$s',
										'wp-carousel-free'
									),
									'<div class="sp_wpcp-info-label">',
									'</div><div class="sp_wpcp-short-content">',
									'<br><strong style="font-weight: 700;">',
									'</strong>',
									'<b>',
									'</b>',
									'<br><strong style="font-weight: 700;">',
									'</b></div><a class="sp_wpcp-open-docs" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/configurations/how-to-configure-the-carousel-orientation/" target="_blank">',
									'</a><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/carousel-orientations/" target="_blank">',
									'</a>'
								),
								'options'    => array(
									'horizontal' => __( 'Horizontal', 'wp-carousel-free' ),
									'vertical'   => array(
										'option_name' => __( 'Vertical', 'wp-carousel-free' ),
										'pro_only'    => true,
									),
								),
								'default'    => 'horizontal',
								'dependency' => array( 'wpcp_layout', '==', 'carousel', true ),
							),
							array(
								'id'         => 'carousel_pause_on_hover',
								'type'       => 'switcher',
								'title'      => __( 'Pause on Hover', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable carousel pause on hover.', 'wp-carousel-free' ),
								'default'    => true,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'dependency' => array( 'wpcp_carousel_auto_play', '==', 'true', true ),
							),
							array(
								'id'         => 'carousel_infinite',
								'type'       => 'switcher',
								'title'      => __( 'Infinite Loop', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable infinite loop mode.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'wpcp_carousel_direction',
								'type'       => 'button_set',
								'title'      => __( 'Carousel Direction', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set carousel direction as you need.', 'wp-carousel-free' ),
								'options'    => array(
									'rtl' => __( 'Right to Left', 'wp-carousel-free' ),
									'ltr' => __( 'Left to Right', 'wp-carousel-free' ),
								),
								'radio'      => true,
								'default'    => 'rtl',
								'dependency' => array( 'wpcp_carousel_orientation', '==', 'horizontal', true ),
							),
							array(
								'id'         => 'wpcp_adaptive_height',
								'type'       => 'switcher',
								'class'      => 'wpcf_show_hide',
								'title'      => __( 'Adaptive Height', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable adaptive height for the carousel.', 'wp-carousel-free' ),
								'default'    => false,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 95,
							),
							array(
								'type'    => 'notice',
								'style'   => 'normal',
								'class'   => 'watermark-pro-notice sp-settings-pro-notice',
								'content' => sprintf(
									/* translators: 1: start bold tag, 2: close bold tag 3: start link and bold tag 4: close bold and link tag. */
									__( 'Ready to fascinate your audience with beautiful image transitions, like %1$sFade, Coverflow, Cube, Kenburn,%2$s and create %1$sVertical%2$s and %1$sMulti-row Sliders%2$s? %3$sUpgrade to Pro!%4$s', 'wp-carousel-free' ),
									'<b>',
									'</b>',
									'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
							),
						),
					),
					array(
						'title'  => __( 'Navigation', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-navigation',
						'fields' => array(// Navigation.
							array(
								'id'     => 'wpcp_carousel_navigation',
								'class'  => 'wpcf-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'wpcp_navigation',
										'type'       => 'switcher',
										'class'      => 'wpcp_navigation',
										'title'      => __( 'Navigation', 'wp-carousel-free' ),
										'subtitle'   => __( 'Show carousel navigation.', 'wp-carousel-free' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'wp-carousel-free' ),
										'text_off'   => __( 'Hide', 'wp-carousel-free' ),
										'text_width' => 80,
										'dependency' => array( 'wpcp_carousel_mode', '!=', 'ticker', true ),
									),
									array(
										'id'         => 'wpcp_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'wpcp_hide_on_mobile',
										'title'      => __( 'Hide on Mobile', 'wp-carousel-free' ),
										'default'    => false,
										'dependency' => array( 'wpcp_carousel_mode|wpcp_navigation', '!=|==', 'ticker|true', true ),
									),
								),
							),
							array(
								'id'         => 'wpcp_carousel_nav_position',
								'type'       => 'select',
								'class'      => 'chosen wpcp-carousel-nav-position',
								'preview'    => true,
								'title'      => __( 'Select Position', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a position for the navigation arrows.', 'wp-carousel-free' ),
								'desc'       => 'This is a <a href="https://wpcarousel.io/pricing/?ref=1" target="_blank">Pro Feature</a>!',
								'options'    => array(
									'vertical_outer'  => __( 'Vertical Outer', 'wp-carousel-free' ),
									'vertical_center_inner' => array(
										'text' => __( 'Vertical Inner', 'wp-carousel-free' ),
									),
									'vertical_center' => array(
										'text' => __( 'Vertical Center', 'wp-carousel-free' ),
									),
									'top_right'       => array(
										'text' => __( 'Top Right', 'wp-carousel-free' ),
									),
									'top_center'      => array(
										'text' => __( 'Top Center', 'wp-carousel-free' ),
									),
									'top_left'        => array(
										'text' => __( 'Top Left', 'wp-carousel-free' ),
									),
									'bottom_left'     => array(
										'text' => __( 'Bottom Left', 'wp-carousel-free' ),
									),
									'bottom_center'   => array(
										'text' => __( 'Bottom Center', 'wp-carousel-free' ),
									),
									'bottom_right'    => array(
										'text' => __( 'Bottom Right', 'wp-carousel-free' ),
									),
								),
								'default'    => 'vertical_outer',
								'dependency' => array( 'wpcp_navigation|wpcp_carousel_mode', '!=|!=', 'false|ticker', true ),
							),
							array(
								'id'         => 'wpcp_visible_on_hover',
								'type'       => 'checkbox',
								'title'      => __( 'Show on Hover', 'wp-carousel-free' ),
								'class'      => 'pro_only_field carousel-nav-pro-options',
								'subtitle'   => __( 'Check to show navigation on hover in the carousel or slider area.', 'wp-carousel-free' ),
								'default'    => false,
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode|wpcp_carousel_nav_position',
									'!=|!=|any',
									'false|ticker|vertical_center,vertical_center_inner,vertical_outer',
									true,
								),
							),
							array(
								'id'         => 'navigation_icons',
								'type'       => 'button_set',
								'title'      => __( 'Navigation Arrow Style', 'wp-carousel-free' ),
								'subtitle'   => __( 'Choose a carousel navigation arrow icon.', 'wp-carousel-free' ),
								'class'      => 'wpcf_navigation_icons',
								'options'    => array(
									'right_open'         => '<i class="wpcf-icon-right-open"></i>',
									'angle'              => '<i class="wpcf-icon-angle-right"></i>',
									'chevron_open_big'   => '<i class="wpcf-icon-right-open-big"></i>',
									'chevron'            => '<i class="wpcf-icon-right-open-1"></i>',
									'right_open_3'       => '<i class="wpcf-icon-right-open-3"></i>',
									'right_open_outline' => '<i class="wpcf-icon-right-open-outline"></i>',
									'arrow'              => '<i class="wpcf-icon-right"></i>',
									'triangle'           => '<i class="wpcf-icon-arrow-triangle-right"></i>',
								),
								'default'    => 'right_open',
								'radio'      => true,
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),

							array(
								'id'         => 'navigation_icons_size',
								'type'       => 'spacing',
								'class'      => 'standard_width_of_spacing_field carousel-nav-pro-options',
								'title'      => __( 'Icon Size', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set a size for the nav arrow icon.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_number_array_field',
								'style'      => false,
								'color'      => false,
								'all'        => true,
								'units'      => array( 'px' ),
								'default'    => array(
									'all' => '20',
								),
								'attributes' => array(
									'min' => 0,
								),
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode',
									'!=|!=',
									'false|ticker',
									true,
								),
							),

							array(
								'id'         => 'wpcp_nav_bg',
								'type'       => 'color_group',
								'class'      => 'carousel-nav-pro-options',
								'title'      => __( 'Background', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set color for the carousel navigation arrow.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_color_group_field',
								'options'    => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
								),
								'default'    => array(
									'color1' => 'transparent',
									'color2' => '#178087',
								),
								'dependency' => array(
									'wpcp_navigation|wpcp_carousel_mode|wpcp_hide_nav_bg_border',
									'!=|!=|==',
									'false|ticker|false',
									true,
								),
							),
							array(
								'id'         => 'wpcp_nav_colors',
								'type'       => 'color_group',
								'title'      => __( 'Navigation Color', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set color for the carousel navigation.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_color_group_field',
								'options'    => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Hover Color', 'wp-carousel-free' ),
								),
								'default'    => array(
									'color1' => '#aaa',
									'color2' => '#178087',
								),
								'dependency' => array( 'wpcp_navigation', '!=', 'false' ),
							),
							array(
								'type'       => 'notice',
								'style'      => 'normal',
								'class'      => 'watermark-pro-notice sp-settings-pro-notice',
								'content'    => sprintf(
									/* translators: 1: start bold tag, 2: close bold tag 3: start link and bold tag 4: close bold and link tag. */
									__( 'Want even more fine-tuned control over your %1$sCarousel Navigation%2$s display? %3$sUpgrade to Pro!%4$s', 'wp-carousel-free' ),
									'<b>',
									'</b>',
									'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
								'dependency' => array( 'wpcp_navigation', '!=', 'false' ),
							),
						),
					),

					array(
						'title'  => __( 'Pagination', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-tab_pagination',
						'fields' => array(// Pagination.
							array(
								'id'     => 'wpcp_carousel_pagination',
								'class'  => 'wpcf-navigation-and-pagination-style',
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'id'         => 'wpcp_pagination',
										'type'       => 'switcher',
										'class'      => 'wpcp_pagination',
										'title'      => __( 'Pagination', 'wp-carousel-free' ),
										'subtitle'   => __( 'Show carousel pagination.', 'wp-carousel-free' ),
										'default'    => true,
										'text_on'    => __( 'Show', 'wp-carousel-free' ),
										'text_off'   => __( 'Hide', 'wp-carousel-free' ),
										'text_width' => 80,
										'dependency' => array( 'wpcp_carousel_mode|wpcp_layout', '!=|==', 'ticker|carousel', true ),
									),
									array(
										'id'         => 'wpcp_pagination_hide_on_mobile',
										'type'       => 'checkbox',
										'class'      => 'wpcp_hide_on_mobile',
										'title'      => __( 'Hide on Mobile', 'wp-carousel-free' ),
										'default'    => false,
										'dependency' => array( 'wpcp_carousel_mode|wpcp_layout|wpcp_pagination', '!=|==|==', 'ticker|carousel|true', true ),
									),
								),
							),

							array(
								'id'         => 'wpcp_carousel_pagination_type',
								'type'       => 'image_select',
								'class'      => 'wpcp_carousel_pagination_width',
								'title'      => __( 'Pagination Style', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select carousel pagination type.', 'wp-carousel-free' ),
								'options'    => array(
									'dots'      => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/bullets.svg',
										'text'  => __( 'Bullets', 'wp-carousel-free' ),
									),
									'dynamic'   => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/dynamic.svg',
										'text'  => __( 'Dynamic', 'wp-carousel-free' ),
									),
									'strokes'   => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/strokes.svg',
										'text'  => __( 'Strokes', 'wp-carousel-free' ),
									),
									'scrollbar' => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/scrollbar.svg',
										'text'  => __( 'Scrollbar', 'wp-carousel-free' ),
									),
									'fraction'  => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/numbers.svg',
										'text'  => __( 'Fraction', 'wp-carousel-free' ),
									),
									'numbers'   => array(
										'image' => plugin_dir_url( __DIR__ ) . 'img/pagination/custom-numbers.svg',
										'text'  => __( 'Numbers', 'wp-carousel-free' ),
									),
								),
								'radio'      => true,
								'default'    => 'dots',
								'dependency' => array( 'wpcp_pagination|wpcp_carousel_mode|wpcp_layout', '!=|!=|==', 'false|ticker|carousel', true ),
							),
							array(
								'id'         => 'wpcp_carousel_pagination_position',
								'type'       => 'button_set',
								'class'      => 'wpcp_carousel_pagination_pro_options',
								'title'      => __( 'Position', 'wp-carousel-free' ),
								'subtitle'   => __( 'Select a position for the pagination.', 'wp-carousel-free' ),
								'options'    => array(
									'outside' => __( 'Outside', 'wp-carousel-free' ),
									'inside'  => __( 'Inside', 'wp-carousel-free' ),
								),
								'radio'      => true,
								'default'    => 'outside',
								'dependency' => array( 'wpcp_pagination|wpcp_carousel_mode|wpcp_layout', '!=|!=|==', 'false|ticker|carousel', true ),
							),
							array(
								'id'          => 'wpcp_pagination_margin',
								'type'        => 'spacing',
								'title'       => __( 'Margin', 'wp-carousel-free' ),
								'subtitle'    => __( 'Set margin for carousel pagination.', 'wp-carousel-free' ),
								'output_mode' => 'margin',
								'unit_text'   => 'Unit',
								'sanitize'    => 'wpcf_sanitize_number_array_field',
								// 'class'       => 'wpcp_carousel_pagination_pro_options',
								'min'         => '-200',
								'default'     => array(
									'top'    => '40',
									'right'  => '0',
									'bottom' => '0',
									'left'   => '0',
									'unit'   => 'px',
								),
								'dependency'  => array( 'wpcp_pagination|wpcp_carousel_mode|wpcp_layout', '!=|!=|==', 'false|ticker|carousel', true ),
							),
							array(
								'id'         => 'wpcp_pagination_color',
								'type'       => 'color_group',
								'title'      => __( 'Pagination Color', 'wp-carousel-free' ),
								'subtitle'   => __( 'Set color for the carousel pagination dots.', 'wp-carousel-free' ),
								'sanitize'   => 'wpcf_sanitize_color_group_field',
								'options'    => array(
									'color1' => __( 'Color', 'wp-carousel-free' ),
									'color2' => __( 'Active Color', 'wp-carousel-free' ),
								),
								'default'    => array(
									'color1' => '#cccccc',
									'color2' => '#178087',
								),
								'dependency' => array( 'wpcp_pagination', '!=', 'false' ),
							),
							array(
								'id'         => 'slides_to_scroll',
								'class'         => 'pro_only_field',
								'type'       => 'column',
								'title'      => __( 'Slide to Scroll', 'wp-carousel-free' ),
								'subtitle'   => __( 'Number of slide(s) to scroll at a time.', 'wp-carousel-free' ),
								'unit'       => false,
								'default'    => array(
									'lg_desktop' => '1',
									'desktop'    => '1',
									'laptop'     => '1',
									'tablet'     => '1',
									'mobile'     => '1',
								),
								'dependency' => array( 'wpcp_layout', '==', 'carousel', true ),
							),
							array(
								'type'       => 'notice',
								'style'      => 'normal',
								'class'      => 'watermark-pro-notice sp-settings-pro-notice',
								'content'    => sprintf(
									/* translators: 1: start bold tag, 2: close bold tag 3: start link and bold tag 4: close bold and link tag. */
									__( 'Want even more fine-tuned control over your %1$sCarousel Pagination%2$s display? %3$sUpgrade to Pro!%4$s', 'wp-carousel-free' ),
									'<b>',
									'</b>',
									'<a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><b>',
									'</b></a>'
								),
								'dependency' => array( 'wpcp_pagination', '!=', 'false' ),
							),
						),
					),

					array(
						'title'  => __( 'Miscellaneous', 'wp-carousel-free' ),
						'icon'   => 'wpcf-icon-miscellaneous',
						'fields' => array(// Miscellaneous.
							array(
								'id'         => 'slider_swipe',
								'type'       => 'switcher',
								'title'      => __( 'Touch Swipe', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable touch swipe mode.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
							),
							array(
								'id'         => 'slider_draggable',
								'type'       => 'switcher',
								'title'      => __( 'Mouse Draggable', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable mouse draggable mode.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => true,
								'dependency' => array( 'slider_swipe', '==', 'true' ),
							),
							array(
								'id'         => 'free_mode',
								'type'       => 'switcher',
								'title'      => __( 'Free Mode', 'wp-carousel-free' ),
								'subtitle'   => __( 'Enable/Disable free mode slider.', 'wp-carousel-free' ),
								'title_help' => '<div class="sp_wpcp-info-label">' . __( 'Free Mode', 'wp-carousel-free' ) . '</div><div class="sp_wpcp-short-content">' . __( 'Enable this feature to allow users to freely scroll and position the slides at anywhere instead of specific positions.', 'wp-carousel-free' ) . '</div><a class="sp_wpcp-open-live-demo" href="https://wpcarousel.io/free-mode-carousel/" target="_blank">' . __( 'Live Demo', 'wp-carousel-free' ) . '</a>',
								'default'    => false,
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
							),
							array(
								'id'         => 'carousel_swipetoslide',
								'type'       => 'switcher',
								'title'      => __( 'Swipe To Slide', 'wp-carousel-free' ),
								'subtitle'   => __( 'Allow users to drag or swipe directly to a slide irrespective of slides to scroll.', 'wp-carousel-free' ),
								'text_on'    => __( 'Enabled', 'wp-carousel-free' ),
								'text_off'   => __( 'Disabled', 'wp-carousel-free' ),
								'text_width' => 100,
								'default'    => false,
								'dependency' => array( 'slider_swipe', '==', 'true' ),
							),
						),
					),
				),
			),
		),
	)
); // Carousel settings section end.


//
// Metabox of the footer section / shortcode section.
// Set a unique slug-like ID.
//
$wpcp_display_shortcode = 'sp_wpcp_display_shortcodes';

//
// Create a metabox.
//
SP_WPCF::createMetabox(
	$wpcp_display_shortcode,
	array(
		'title'        => __( 'How To Use', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'context'      => 'side',
		'show_restore' => false,
	)
);


SP_WPCF::createSection(
	$wpcp_display_shortcode,
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => true,
				'class'     => 'sp_wpcp-admin-sidebar',
			),
		),
	)
);
SP_WPCF::createMetabox(
	'sp_wpcp_display_builders',
	array(
		'title'        => __( 'Page Builders', 'wp-carousel-free' ),
		'post_type'    => 'sp_wp_carousel',
		'context'      => 'side',
		'show_restore' => false,
	)
);
SP_WPCF::createSection(
	'sp_wpcp_display_builders',
	array(
		'fields' => array(
			array(
				'type'      => 'shortcode',
				'shortcode' => false,
				'class'     => 'sp_wpcp-admin-sidebar',
			),
		),
	)
);
