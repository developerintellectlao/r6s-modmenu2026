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

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SP_WPCP_Framework::createMetabox(
	'sp_wpcp_live_preview',
	array(
		'title'        => __( 'Live Preview', 'wp-carousel-pro' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'context'      => 'normal',
	)
);
SP_WPCP_Framework::createSection(
	'sp_wpcp_live_preview',
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
SP_WPCP_Framework::createMetabox(
	$wpcp_carousel_content_source_settings,
	array(
		'title'        => __( 'WP Carousel Pro', 'wp-carousel-pro' ),
		'post_type'    => 'sp_wp_carousel',
		'show_restore' => false,
		'context'      => 'normal',
	)
);

//
// Create a section.
//
SP_WPCP_Framework::createSection(
	$wpcp_carousel_content_source_settings,
	array(
		'fields' => array(
			array(
				'type'    => 'heading',
				'image'   => WPCAROUSEL_URL . 'Admin/img/wpcp-logo.svg',
				'after'   => '<i class="fa fa-life-ring"></i> Support',
				'link'    => 'https://shapedplugin.com/support/',
				'class'   => 'wpcp-admin-header',
				'version' => WPCAROUSEL_VERSION,
			),
			array(
				'id'      => 'wpcp_carousel_type',
				'class'   => 'wpcp_carousel_type',
				'type'    => 'carousel_type',
				'title'   => __( 'Source Type', 'wp-carousel-pro' ),
				'options' => array(
					'image-carousel'    => array(
						'icon' => 'wpcp-icon-image-1',
						'text' => __( 'Image', 'wp-carousel-pro' ),
					),
					'post-carousel'     => array(
						'icon' => 'wpcp-icon-post',
						'text' => __( 'Post', 'wp-carousel-pro' ),
					),
					'product-carousel'  => array(
						'icon' => 'wpcp-icon-products',
						'text' => __( 'Product', 'wp-carousel-pro' ),
					),
					'video-carousel'    => array(
						'icon' => 'wpcp-icon-video',
						'text' => __( 'Video', 'wp-carousel-pro' ),
					),
					'audio-carousel'    => array(
						'icon' => 'wpcp-icon-audio',
						'text' => __( 'Audio', 'wp-carousel-pro' ),
					),
					'content-carousel'  => array(
						'icon' => 'wpcp-icon-content',
						'text' => __( 'Content', 'wp-carousel-pro' ),
					),
					'mix-content'       => array(
						'icon' => 'wpcp-icon-mix-content',
						'text' => __( 'Mix-Content', 'wp-carousel-pro' ),
					),
					'external-carousel' => array(
						'icon' => 'wpcp-icon-external',
						'text' => __( 'External', 'wp-carousel-pro' ),
					),
				),
				'default' => 'image-carousel',
			),
			// Audio Source.
			array(
				'id'                     => 'carousel_audio_source',
				'type'                   => 'group',
				'class'                  => 'wpcp-video-field-wrapper',
				'title'                  => __( 'Audio', 'wp-carousel-pro' ),
				'button_title'           => __( '<i class="fa fa-plus-circle"></i> Add Audio', 'wp-carousel-pro' ),
				'accordion_title_prefix' => __( 'Audio:', 'wp-carousel-pro' ),
				'accordion_title_number' => true,
				'fields'                 => array(
					array(
						'id'      => 'carousel_audio_source_type',
						'type'    => 'carousel_type',
						'class'   => 'carousel_type_small',
						'title'   => 'Source',
						'options' => array(
							// 'soundcloud'  => array(
							// 'image' => WPCAROUSEL_URL . 'Admin/img/source/soundcloud.svg',
							// 'text'  => __( 'SoundCloud', 'wp-carousel-pro' ),
							// ),
							// 'spotify'     => array(
							// 'image' => WPCAROUSEL_URL . 'Admin/img/source/spotify.svg',
							// 'text'  => __( 'Spotify', 'wp-carousel-pro' ),
							// ),
							'embed'       => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/embed.svg',
								'text'  => __( 'Embed Audio', 'wp-carousel-pro' ),
							),
							'self_hosted' => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/self-hosted-audio.svg',
								'text'  => __( 'Self Hosted', 'wp-carousel-pro' ),
							),
						),
						'default' => 'embed',
					),
					array(
						'id'         => 'wpcp_audio_embed',
						'class'      => 'wpcp_audio_embed',
						'type'       => 'textarea',
						'title'      => __( 'Audio Embed Code', 'wp-carousel-pro' ),
						'title_help' => __( 'SoundCloud, Spotify or any audio embed code', 'wp-carousel-pro' ),
						// 'attributes' => array(
						// 'placeholder' => __( '', 'wp-carousel-pro' ),
						// ),
						'dependency' => array( 'carousel_audio_source_type', 'any', 'embed' ),
					),
					// array(
					// 'id'           => 'carousel_audio_source_thumb',
					// 'type'         => 'media',
					// 'library'      => array( 'image' ),
					// 'url'          => false,
					// 'preview'      => true,
					// 'title'        => __( 'Custom Thumbnail', 'wp-carousel-pro' ),
					// 'title_help'   => __( 'Upload custom thumbnail.', 'wp-carousel-pro' ),
					// 'button_title' => __( 'Upload Image', 'wp-carousel-pro' ),
					// 'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
					// 'placeholder'  => __( 'No image selected', 'wp-carousel-pro' ),
					// // 'dependency'   => array( 'carousel_video_source_type', 'any', 'self_hosted,image_only,wistia' ),
					// ),
						array(
							'id'           => 'carousel_audio_source_upload',
							'type'         => 'upload',
							'title'        => __( 'Upload audio', 'wp-carousel-pro' ),
							'upload_type'  => 'audio',
							'button_title' => __( 'Upload Audio', 'wp-carousel-pro' ),
							'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
							'placeholder'  => __( 'No Audio selected', 'wp-carousel-pro' ),
							'library'      => array( 'audio' ),
							'dependency'   => array( 'carousel_audio_source_type', '==', 'self_hosted' ),
						),
					array(
						'id'     => 'carousel_audio_description',
						'class'  => 'wpcp-video-description',
						'type'   => 'wp_editor',
						'title'  => __( 'Title & Description (optional)', 'wp-carousel-pro' ),
						'height' => '150px',
					),
				),
				'dependency'             => array( 'wpcp_carousel_type', '==', 'audio-carousel' ),
			),

			// External Carousel.
			array(
				'id'         => 'wpcp_feeds_url',
				'type'       => 'text',
				'title'      => __( 'Feeds URL', 'wp-carousel-pro' ),
				'desc'       => __( 'Write your feeds URL. <a href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/how-to-find-the-rss-feed-url-of-a-site/" target="_blank">Get help</a>', 'wp-carousel-pro' ),
				'attributes' => array(
					'placeholder' => __( 'Feeds URL', 'wp-carousel-pro' ),
				),
				'dependency' => array( 'wpcp_carousel_type', '==', 'external-carousel' ),
			),
			array(
				'id'         => 'wpcp_external_limit',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-carousel-pro' ),
				'default'    => '20',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_carousel_type', '==', 'external-carousel' ),
			),
			// End external Carousel.
			array(
				'id'          => 'wpcp_gallery',
				'type'        => 'gallery',
				'wrap_class'  => 'wpcp-gallery-filed-wrapper',
				'title'       => __( 'Images', 'wp-carousel-pro' ),
				'add_title'   => __( 'ADD IMAGE', 'wp-carousel-pro' ),
				'edit_title'  => __( 'EDIT IMAGE', 'wp-carousel-pro' ),
				'clear_title' => __( 'REMOVE ALL', 'wp-carousel-pro' ),
				'dependency'  => array( 'wpcp_carousel_type', '==', 'image-carousel' ),
			),
			// Post Carousel.
			array(
				'id'            => 'wpcp_post_type',
				'type'          => 'select',
				'title'         => __( 'Post Type', 'wp-carousel-pro' ),
				'options'       => 'post_type',
				'class'         => 'sp_wpcp_post_type',
				'multiple_type' => true,
				'attributes'    => array(
					'placeholder' => __( 'Select Post Type', 'wp-carousel-pro' ),
					'style'       => 'min-width: 150px;',
				),
				'default'       => 'post',
				'dependency'    => array( 'wpcp_carousel_type', '==', 'post-carousel' ),
			),
			array(
				'id'         => 'wpcp_multi_post_type',
				'type'       => 'select',
				'title'      => __( 'Multiple Post Types', 'wp-carousel-pro' ),
				'options'    => 'post_type',
				// 'class'      => 'sp_wpcp_post_type',
				'attributes' => array(
					'placeholder' => __( 'Select Post Type', 'wp-carousel-pro' ),
					'style'       => 'min-width: 150px;',
				),
				'multiple'   => true,
				'chosen'     => true,
				'default'    => 'post',
				'dependency' => array( 'wpcp_carousel_type|wpcp_post_type', '==|==', 'post-carousel|multiple_post_type' ),
			),
			array(
				'id'         => 'wpcp_display_posts_from',
				'type'       => 'select',
				'title'      => __( 'Filter Posts', 'wp-carousel-pro' ),
				'options'    => array(
					'latest'        => __( 'Latest', 'wp-carousel-pro' ),
					'taxonomy'      => __( 'Taxonomy', 'wp-carousel-pro' ),
					'specific_post' => __( 'Specific', 'wp-carousel-pro' ),
				),
				'default'    => 'latest',
				'class'      => 'chosen',
				'dependency' => array( 'wpcp_carousel_type|wpcp_post_type', '==|!=', 'post-carousel|multiple_post_type' ),
			),
			array(
				'id'         => 'wpcp_post_taxonomy',
				'type'       => 'select',
				'title'      => __( 'Select Taxonomy', 'wp-carousel-pro' ),
				'options'    => 'taxonomies',
				'class'      => 'sp_wpcp_post_taxonomy',
				'dependency' => array( 'wpcp_display_posts_from|wpcp_carousel_type|wpcp_post_type', '==|==|!=', 'taxonomy|post-carousel|multiple_post_type' ),
			),
			array(
				'id'          => 'wpcp_taxonomy_terms',
				'type'        => 'select',
				'title'       => __( 'Choose Term(s)', 'wp-carousel-pro' ),
				'help'        => __( 'Choose the taxonomy term(s) to show the posts from.', 'wp-carousel-pro' ),
				'options'     => 'terms',
				'class'       => 'sp_wpcp_taxonomy_terms',
				'attributes'  => array(
					'style' => 'width: 280px;',
				),
				'sortable'    => true,
				'multiple'    => true,
				'placeholder' => __( 'Select Term(s)', 'wp-carousel-pro' ),
				'chosen'      => true,
				'dependency'  => array( 'wpcp_display_posts_from|wpcp_carousel_type|wpcp_post_type', '==|==|!=', 'taxonomy|post-carousel|multiple_post_type', true ),
			),
			array(
				'id'         => 'taxonomy_operator',
				'type'       => 'select',
				'class'      => 'wpcp_taxonomy_operator',
				'title'      => __( 'Operator', 'wp-carousel-pro' ),
				'options'    => array(
					'IN'     => __( 'IN ', 'wp-carousel-pro' ),
					'AND'    => __( 'AND ', 'wp-carousel-pro' ),
					'NOT IN' => __( 'NOT IN ', 'wp-carousel-pro' ),
				),
				'help'       => '<b>IN</b> - Show posts which associate with one or more terms.<br/>
				<b>AND</b> - Show posts which match all terms.<br/>
				<b>NOT IN</b> - Show posts which don\'t match the terms.<br/>',
				'default'    => 'IN',
				'dependency' => array( 'wpcp_display_posts_from|wpcp_carousel_type|wpcp_post_type', '==|==|!=', 'taxonomy|post-carousel|multiple_post_type', true ),
			),
			array(
				'id'         => 'number_of_total_posts',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-carousel-pro' ),
				'help'       => __( 'Total number of posts to show. Leave empty to show all found posts.', 'wp-carousel-pro' ),
				'default'    => '20',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_display_posts_from|wpcp_carousel_type', '!=|==', 'specific_post|post-carousel' ),
			),
			array(
				'id'          => 'wpcp_specific_posts',
				'type'        => 'select',
				'title'       => __( 'Select Post(s)', 'wp-carousel-pro' ),
				'options'     => 'posts',
				'help'        => __( 'Choose the posts to display. Sort the posts position by drag & drop.', 'wp-carousel-pro' ),
				'chosen'      => true,
				'sortable'    => true,
				'ajax'        => true,
				'class'       => 'sp_wpcp_specific_posts',
				'multiple'    => true,
				'placeholder' => __( 'Choose Posts', 'wp-carousel-pro' ),
				'query_args'  => array(
					'posts_per_page' => -1,
					'cache_results'  => false,
					'no_found_rows'  => true,
				),
				'dependency'  => array( 'wpcp_display_posts_from|wpcp_carousel_type|wpcp_post_type', '==|==|!=', 'specific_post|post-carousel|multiple_post_type', true ),
			),
			// Product Carousel.
			array(
				'id'         => 'wpcp_display_product_from',
				'type'       => 'select',
				'title'      => __( 'Filter Products', 'wp-carousel-pro' ),
				'options'    => array(
					'latest'            => __( 'Latest', 'wp-carousel-pro' ),
					'taxonomy'          => __( 'Category', 'wp-carousel-pro' ),
					'specific_products' => __( 'Specific', 'wp-carousel-pro' ),
					'on_sale'           => __( 'On Sale', 'wp-carousel-pro' ),
				),
				'default'    => 'latest',
				'class'      => 'chosen',
				'dependency' => array( 'wpcp_carousel_type', '==', 'product-carousel' ),
			),
			array(
				'id'          => 'wpcp_specific_product',
				'type'        => 'select',
				'title'       => __( 'Select Product', 'wp-carousel-pro' ),
				'help'        => __( 'Choose the products to display. Sort the products position by drag & drop.', 'wp-carousel-pro' ),
				'options'     => 'posts',
				'query_args'  => array(
					'post_type' => 'product',
					'orderby'   => 'post_date',
				),
				'ajax'        => true,
				'chosen'      => true,
				'sortable'    => true,
				'multiple'    => true,
				'placeholder' => __( 'Choose Product', 'wp-carousel-pro' ),
				'dependency'  => array( 'wpcp_display_product_from|wpcp_carousel_type', '==|==', 'specific_products|product-carousel' ),
			),
			array(
				'id'          => 'wpcp_taxonomy_product_terms',
				'type'        => 'select',
				'class'       => 'sp_wpcp_taxonomy_terms',
				'title'       => __( 'Category Term(s)', 'wp-carousel-pro' ),
				'help'        => __( 'Choose the product category term(s).', 'wp-carousel-pro' ),
				'options'     => 'categories',
				'query_args'  => array(
					'post_type' => 'product',
					'taxonomy'  => 'product_cat',
				),
				'chosen'      => true,
				'sortable'    => true,
				'multiple'    => true,
				'placeholder' => __( 'Choose term(s)', 'wp-carousel-pro' ),
				'dependency'  => array( 'wpcp_display_product_from|wpcp_carousel_type', '==|==', 'taxonomy|product-carousel', true ),
				'attributes'  => array(
					'style' => 'min-width: 250px;',
				),
			),
			array(
				'id'         => 'wpcp_product_category_operator',
				'type'       => 'select',
				'class'      => 'wpcp_taxonomy_operator',
				'title'      => __( 'Operator', 'wp-carousel-pro' ),
				'options'    => array(
					'IN'     => __( 'IN ', 'wp-carousel-pro' ),
					'AND'    => __( 'AND ', 'wp-carousel-pro' ),
					'NOT IN' => __( 'NOT IN ', 'wp-carousel-pro' ),
				),
				'help'       => '<b>IN</b> - Show posts which associate with one or more terms.<br/>
				<b>AND</b> - Show posts which match all terms.<br/>
				<b>NOT IN</b> - Show posts which don\'t match the terms.<br/>',
				'default'    => 'IN',
				'dependency' => array( 'wpcp_display_product_from|wpcp_carousel_type', '==|==', 'taxonomy|product-carousel' ),
			),
			array(
				'id'         => 'wpcp_total_products',
				'type'       => 'spinner',
				'title'      => __( 'Limit', 'wp-carousel-pro' ),
				'help'       => __( 'Total number of posts to show. Leave empty to show all found posts.', 'wp-carousel-pro' ),
				'default'    => '20',
				'min'        => 1,
				'max'        => 1000,
				'dependency' => array( 'wpcp_display_product_from|wpcp_carousel_type', '!=|==', 'specific_products|product-carousel' ),
			),
			// Content Carousel.
			array(
				'id'                     => 'carousel_content_source',
				'type'                   => 'group',
				'title'                  => __( 'Content', 'wp-carousel-pro' ),
				'button_title'           => __( '<i class="fa fa-plus-circle"></i> Add Content', 'wp-carousel-pro' ),
				'class'                  => 'wpcp_carousel_content_wrapper',
				'accordion_title_prefix' => __( 'Slide Content', 'wp-carousel-pro' ),
				'accordion_title_number' => true,
				'accordion_title_auto'   => false,
				'fields'                 => array(
					array(
						'id'     => 'carousel_content_description',
						'type'   => 'wp_editor',
						'class'  => 'wpcp_carousel_content_source',
						'title'  => __( 'Slide Content', 'wp-carousel-pro' ),
						'height' => '150px',
					),
					array(
						'id'                       => 'wpcp_carousel_content_bg',
						'type'                     => 'background',
						'class'                    => 'wpcp_carousel_content_bg',
						'title'                    => __( 'Slide Background & Settings', 'wp-carousel-pro' ),
						'background_gradient'      => true,
						'default'                  => array(
							'background-color'          => '#cfe2f3',
							'background-gradient-color' => '#555',
							'background-size'           => 'initial',
							'background-position'       => 'center center',
							'background-repeat'         => 'no-repeat',
							'background-attachment'     => 'scroll',
						),
						'background_image_preview' => false,
						'preview'                  => 'always',
					),
				),
				'dependency'             => array( 'wpcp_carousel_type', '==', 'content-carousel' ),
			), // End of Content Carousel.
			// Video.
			array(
				'id'                     => 'carousel_video_source',
				'type'                   => 'group',
				'class'                  => 'wpcp-video-field-wrapper',
				'title'                  => __( 'Video', 'wp-carousel-pro' ),
				'button_title'           => __( '<i class="fa fa-plus-circle"></i> Add Video', 'wp-carousel-pro' ),
				'accordion_title_prefix' => __( 'Video:', 'wp-carousel-pro' ),
				'accordion_title_number' => true,
				'fields'                 => array(
					array(
						'id'      => 'carousel_video_source_type',
						'type'    => 'carousel_type',
						'class'   => 'carousel_type_small',
						'title'   => 'Source',
						'options' => array(
							'youtube'     => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/youtube.svg',
								'text'  => __( 'YouTube', 'wp-carousel-pro' ),
							),
							'vimeo'       => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/vimeo.svg',
								'text'  => __( 'Vimeo', 'wp-carousel-pro' ),
							),
							'tiktok'      => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/tik-tok.svg',
								'text'  => __( 'TikTok', 'wp-carousel-pro' ),
							),
							'twitch'      => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/twitch.svg',
								'text'  => __( 'Twitch', 'wp-carousel-pro' ),
							),
							'dailymotion' => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/dailymotion.svg',
								'text'  => __( 'Dailymotion', 'wp-carousel-pro' ),
							),
							'wistia'      => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/wistia.svg',
								'text'  => __( 'Wistia', 'wp-carousel-pro' ),
							),
							'self_hosted' => array(
								'image' => WPCAROUSEL_URL . 'Admin/img/source/clapperboard.svg',
								'text'  => __( 'Self Hosted', 'wp-carousel-pro' ),
							),
						),
						'default' => 'youtube',
					),
					array(
						'id'         => 'twitch_id_type',
						'type'       => 'button_set',
						'title'      => __( 'Twitch Embed by', 'wp-carousel-pro' ),
						'options'    => array(
							'video'   => __( 'Video ID', 'wp-carousel-pro' ),
							'channel' => __( 'Channel ID', 'wp-carousel-pro' ),
						),
						'default'    => 'video',
						'dependency' => array( 'carousel_video_source_type', '==', 'twitch' ),
					),
					array(
						'id'         => 'carousel_video_twitch_id',
						'class'      => 'carousel_video_source_id',
						'type'       => 'text',
						'title'      => __( 'Video ID', 'wp-carousel-pro' ),
						'dependency' => array( 'carousel_video_source_type|twitch_id_type', '==|==', 'twitch|video' ),
					),
					array(
						'id'         => 'carousel_video_channel_id',
						'class'      => 'carousel_video_source_id',
						'type'       => 'text',
						'title'      => __( 'Channel ID', 'wp-carousel-pro' ),
						'dependency' => array( 'carousel_video_source_type|twitch_id_type', '==|==', 'twitch|channel' ),
					),
					array(
						'id'         => 'carousel_video_source_id',
						'class'      => 'carousel_video_source_id',
						'type'       => 'text',
						'title'      => __( 'Video ID', 'wp-carousel-pro' ),
						'help'       => __( 'The last part of the URL is the ID e.g: //youtube.com/watch?v=<b><i>eKFTSSKCzWA</i></b> <br>//vimeo.com/<b><i>95746815</i></b>', 'wp-carousel-pro' ),
						'dependency' => array( 'carousel_video_source_type', 'any', 'youtube,vimeo,dailymotion,tiktok' ),
					),
					array(
						'id'         => 'carousel_wistia_url',
						'type'       => 'text',
						'title'      => __( 'Wistia Video ID', 'wp-carousel-pro' ),
						'desc'       => '<a target="_blank" href="https://docs.shapedplugin.com/docs/wordpress-carousel-pro/shortcode-generator-settings/style-settings-video-carousel/#wistia-video">Learn how to get wistia video ID</a>',
						'dependency' => array( 'carousel_video_source_type', '==', 'wistia' ),
					),
					array(
						'id'           => 'carousel_video_source_upload',
						'type'         => 'upload',
						'title'        => __( 'Upload Video', 'wp-carousel-pro' ),
						'upload_type'  => 'video',
						'button_title' => __( 'Upload Video', 'wp-carousel-pro' ),
						'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
						'placeholder'  => __( 'No video selected', 'wp-carousel-pro' ),
						'library'      => array( 'video' ),
						'dependency'   => array( 'carousel_video_source_type', '==', 'self_hosted' ),
					),
					array(
						'id'           => 'carousel_video_source_thumb',
						'type'         => 'media',
						'library'      => array( 'image' ),
						'url'          => false,
						'preview'      => true,
						'title'        => __( 'Custom Thumbnail', 'wp-carousel-pro' ),
						'title_help'   => __( 'Upload custom thumbnail.', 'wp-carousel-pro' ),
						'button_title' => __( 'Upload Image', 'wp-carousel-pro' ),
						'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
						'placeholder'  => __( 'No image selected', 'wp-carousel-pro' ),
						'dependency'   => array( 'carousel_video_source_type', 'any', 'self_hosted,image_only,wistia,twitch,tiktok' ),
					),
					array(
						'id'     => 'carousel_video_description',
						'class'  => 'wpcp-video-description',
						'type'   => 'wp_editor',
						'title'  => __( 'Title & Description (optional)', 'wp-carousel-pro' ),
						'height' => '150px',
					),
				),
				'dependency'             => array( 'wpcp_carousel_type', '==', 'video-carousel' ),
			), // End of Video Carousel.
			// Mix Content.
			array(
				'id'                     => 'carousel_mix_source',
				'type'                   => 'group',
				'class'                  => 'wpcp-video-field-wrapper',
				'title'                  => __( 'Mix-Content', 'wp-carousel-pro' ),
				'button_title'           => __( '<i class="fa fa-plus-circle"></i> Add content', 'wp-carousel-pro' ),
				'accordion_title_prefix' => __( 'Content:', 'wp-carousel-pro' ),
				'accordion_title_number' => true,
				'fields'                 => array(
					array(
						'id'      => 'carousel_mix_source_type',
						'type'    => 'carousel_type',
						'class'   => 'carousel_type_small',
						'title'   => __( 'Content Type', 'wp-carousel-pro' ),
						'options' => array(
							'image'   => array(
								'icon' => 'wpcp-icon-image-1',
								'text' => __( 'Image', 'wp-carousel-pro' ),
							),
							'content' => array(
								'icon' => 'wpcp-icon-content',
								'text' => __( 'Content', 'wp-carousel-pro' ),
							),
							'video'   => array(
								'icon' => 'wpcp-icon-video',
								'text' => __( 'Video', 'wp-carousel-pro' ),
							),
							'audio'   => array(
								'icon' => 'wpcp-icon-audio',
								'text' => __( 'Audio', 'wp-carousel-pro' ),
							),
						),
						'default' => 'image',
					),
					array(
						'id'         => 'carousel_audio_source_type',
						'type'       => 'select',
						'title'      => 'Source',
						'options'    => array(
							'self_hosted' => __( 'Self Hosted', 'wp-carousel-pro' ),
							'embed'       => __( 'Embed Audio', 'wp-carousel-pro' ),
						),
						'default'    => 'self_hosted',
						'dependency' => array( 'carousel_mix_source_type', '==', 'audio' ),
					),
					array(
						'id'           => 'carousel_audio_source_upload',
						'type'         => 'upload',
						'title'        => __( 'Upload Audio', 'wp-carousel-pro' ),
						'upload_type'  => 'audio',
						'button_title' => __( 'Upload audio', 'wp-carousel-pro' ),
						'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
						'placeholder'  => __( 'No Audio selected', 'wp-carousel-pro' ),
						'library'      => array( 'audio' ),
						'dependency'   => array( 'carousel_audio_source_type|carousel_mix_source_type', '==|==', 'self_hosted|audio' ),
					),
					array(
						'id'         => 'carousel_video_source_type',
						'type'       => 'select',
						'title'      => 'Source',
						'options'    => array(
							'youtube'     => __( 'YouTube', 'wp-carousel-pro' ),
							'vimeo'       => __( 'Vimeo', 'wp-carousel-pro' ),
							'dailymotion' => __( 'Dailymotion', 'wp-carousel-pro' ),
							'tiktok'      => __( 'TikTok', 'wp-carousel-pro' ),
							'twitch'      => __( 'Twitch', 'wp-carousel-pro' ),
							'wistia'      => __( 'Wistia', 'wp-carousel-pro' ),
							'self_hosted' => __( 'Self Hosted', 'wp-carousel-pro' ),
						),
						'default'    => 'youtube',
						'dependency' => array( 'carousel_mix_source_type', '==', 'video' ),
					),

					array(
						'id'         => 'twitch_id_type',
						'type'       => 'button_set',
						'title'      => __( 'Twitch Embed by', 'wp-carousel-pro' ),
						'options'    => array(
							'video'   => __( 'Video ID', 'wp-carousel-pro' ),
							'channel' => __( 'Channel ID', 'wp-carousel-pro' ),
						),
						'default'    => 'video',
						'dependency' => array( 'carousel_video_source_type', '==', 'twitch' ),
					),
					array(
						'id'         => 'carousel_video_twitch_id',
						'class'      => 'carousel_video_source_id',
						'type'       => 'text',
						'title'      => __( 'Video ID', 'wp-carousel-pro' ),
						'dependency' => array( 'carousel_video_source_type|twitch_id_type', '==|==', 'twitch|video' ),
					),
					array(
						'id'         => 'carousel_video_channel_id',
						'class'      => 'carousel_video_source_id',
						'type'       => 'text',
						'title'      => __( 'Channel ID', 'wp-carousel-pro' ),
						'dependency' => array( 'carousel_video_source_type|twitch_id_type', '==|==', 'twitch|channel' ),
					),
					array(
						'id'         => 'carousel_mix_video_source_id',
						'class'      => 'carousel_video_source_id',
						'type'       => 'text',
						'title'      => __( 'Video ID', 'wp-carousel-pro' ),
						'help'       => __( 'The last part of the URL is the ID e.g: //youtube.com/watch?v=<b><i>eKFTSSKCzWA</i></b> <br>//vimeo.com/<b><i>95746815</i></b>', 'wp-carousel-pro' ),
						'dependency' => array( 'carousel_video_source_type|carousel_mix_source_type', 'any|==', 'youtube,vimeo,dailymotion,tiktok,wistia|video' ),
					),
					array(
						'id'           => 'carousel_video_source_upload',
						'type'         => 'upload',
						'title'        => __( 'Upload Video', 'wp-carousel-pro' ),
						'upload_type'  => 'video',
						'button_title' => __( 'Upload Video', 'wp-carousel-pro' ),
						'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
						'placeholder'  => __( 'No video selected', 'wp-carousel-pro' ),
						'library'      => array( 'video' ),
						'dependency'   => array( 'carousel_video_source_type|carousel_mix_source_type', '==|==', 'self_hosted|video' ),
					),
					array(
						'id'           => 'carousel_video_source_thumb',
						'type'         => 'media',
						'library'      => array( 'image' ),
						'url'          => false,
						'preview'      => true,
						'title'        => __( 'Custom Thumbnail', 'wp-carousel-pro' ),
						'button_title' => __( 'Upload Image', 'wp-carousel-pro' ),
						'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
						'placeholder'  => __( 'No image selected', 'wp-carousel-pro' ),
						'dependency'   => array( 'carousel_video_source_type|carousel_mix_source_type', 'any|==', 'self_hosted,twitch,tiktok,wistia|video' ),
					),
					array(
						'id'           => 'carousel_image_thumb',
						'type'         => 'media',
						'library'      => array( 'image' ),
						'url'          => false,
						'preview'      => true,
						'title'        => __( 'Image', 'wp-carousel-pro' ),
						'button_title' => __( 'Add Image', 'wp-carousel-pro' ),
						'remove_title' => __( 'Remove', 'wp-carousel-pro' ),
						'placeholder'  => __( 'No image selected', 'wp-carousel-pro' ),
						'dependency'   => array( 'carousel_mix_source_type', '==', 'image' ),
					),
					array(
						'id'         => 'carousel_image_link_action',
						'type'       => 'select',
						'title'      => __( 'Click Action Type', 'wp-carousel-pro' ),
						'options'    => array(
							'img_link' => __( 'Link URL', 'wp-carousel-pro' ),
							'img_lbox' => __( 'Lightbox', 'wp-carousel-pro' ),
							'none'     => __( 'Disabled', 'wp-carousel-pro' ),
						),
						'attributes' => array(
							'data-depend-id' => 'carousel_image_only_link_action',
						),
						'radio'      => true,
						'default'    => 'img_link',
						'dependency' => array( 'carousel_mix_source_type', '==', 'image' ),
					),
					array(
						'id'         => 'wpcp_mix_audio_embed',
						'class'      => 'wpcp_audio_embed',
						'type'       => 'textarea',
						'title'      => __( 'Audio Embed Code', 'wp-carousel-pro' ),
						'title_help' => __( 'SoundCloud, Spotify or any audio embed code', 'wp-carousel-pro' ),
						// 'attributes' => array(
						// 'placeholder' => __( '', 'wp-carousel-pro' ),
						// ),
					'dependency'     => array( 'carousel_audio_source_type|carousel_mix_source_type', '==|==', 'embed|audio' ),
					),
					array(
						'id'     => 'carousel_mix_content_description',
						'class'  => 'wpcp-video-description',
						'type'   => 'wp_editor',
						'title'  => __( 'Title & Description', 'wp-carousel-pro' ),
						'height' => '150px',
					),
					array(
						'id'                       => 'wpcp_carousel_content_bg',
						'type'                     => 'background',
						'class'                    => 'wpcp_carousel_content_bg',
						'title'                    => __( 'Slide Background & Settings', 'wp-carousel-pro' ),
						'background_gradient'      => true,
						'default'                  => array(
							'background-color'          => '#cfe2f3',
							'background-gradient-color' => '#555',
							'background-size'           => 'initial',
							'background-position'       => 'center center',
							'background-repeat'         => 'no-repeat',
							'background-attachment'     => 'scroll',
						),
						'background_image_preview' => false,
						'preview'                  => 'always',
						'dependency'               => array( 'carousel_mix_source_type', '==', 'content' ),
					),
				),
				'dependency'             => array( 'wpcp_carousel_type', '==', 'mix-content' ),
			), // End of Video Carousel.
		), // End of fields array.
	)
);
