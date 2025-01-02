<?php
/**
 * The file that defines the shortcode plugin class.
 *
 * A class definition that define main carousel shortcode of the plugin.
 *
 * @link       https://shapedplugin.com/
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/Frontend
 * @author     ShapedPlugin <shapedplugin@gmail.com>
 */

namespace ShapedPlugin\WPCarouselPro\Frontend;

use ShapedPlugin\WPCarouselPro\Admin\Image_Resize;
use ShapedPlugin\WPCarouselPro\Includes\Image_Watermark;

if ( ! class_exists( 'Helper' ) ) {

	/**
	 * The Helper class.
	 */
	class Helper {

		/**
		 * Holds the class object.
		 *
		 * @since 3.0.0
		 * @var object
		 */
		public static $instance;

		/**
		 * Contain the version class object.
		 *
		 * @since 3.0.0
		 * @var object
		 */
		public $version;

		/**
		 * Holds the carousel data.
		 *
		 * @since 3.0.0
		 * @var array
		 */
		public $data;

		/**
		 * YouTube video support.
		 *
		 * @since 3.0.0
		 * @var boolean
		 */
		public $youtube = false;

		/**
		 * Vimeo video support.
		 *
		 * @since 3.0.0
		 * @var boolean
		 */
		public $vimeo = false;

		/**
		 * The post ID.
		 *
		 * @var string $post_id The post id of the carousel shortcode.
		 */
		public $post_id;

		/**
		 * Allows for accessing single instance of class. Class should only be constructed once per call.
		 *
		 * @since 3.0.0
		 * @static
		 * @return Register_Shortcode Shortcode instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Custom Template locator.
		 *
		 * @param  mixed $template_name template name .
		 * @param  mixed $template_path template path .
		 * @param  mixed $default_path default path .
		 * @return string
		 */
		public static function wpcp_locate_template( $template_name, $template_path = '', $default_path = '' ) {
			if ( ! $template_path ) {
				$template_path = 'wp-carousel-pro/templates';
			}
			if ( ! $default_path ) {
				$default_path = WPCAROUSEL_PATH . 'Frontend/templates/';
			}
			$template = locate_template( trailingslashit( $template_path ) . $template_name );
			// Get default template.
			if ( ! $template ) {
				$template = $default_path . $template_name;
			}
			// Return what we found.
			return $template;
		}

		/**
		 * Minify output
		 *
		 * @param  string $html output.
		 * @return statement
		 */
		public static function minify_output( $html ) {
			$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
			$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
			while ( stristr( $html, '  ' ) ) {
				$html = str_replace( '  ', ' ', $html );
			}
			return $html;
		}

		/**
		 * This is just a tiny wrapper function for the class above so that there is no
		 * need to change any code in your own WP themes. Usage is still the same :)
		 *
		 * @param string  $url The image URL.
		 * @param integer $width Width of the image.
		 * @param integer $height Height of the image.
		 * @param mixed   $crop Crop the image.
		 * @param boolean $single single.
		 * @param boolean $upscale Upscale.
		 * @return statement
		 */
		public static function image_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
			/* WPML Fix */
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
				global $sitepress;
				$url = $sitepress->convert_url( $url, $sitepress->get_default_language() );
			}
			/* WPML Fix */
			$wpcp_aq_resize = Image_Resize::get_instance();
			return $wpcp_aq_resize->process( $url, $width, $height, $crop, $single, $upscale );
		}

		/**
		 * Image Attribute.
		 *
		 * @param  mixed $img_id image id.
		 * @param  mixed $image_sizes Dimensions.
		 * @param  mixed $is_variable_width boolean.
		 * @param  mixed $carousel_mode Carousel mode.
		 * @param  mixed $image_width width.
		 * @param  mixed $image_height height.
		 * @param  bool  $image_crop crop.
		 * @param  bool  $show_2x_image load 2x image.
		 * @param  bool  $watermark watermark.
		 * @return array
		 */
		public static function image_attr( $img_id, $image_sizes, $is_variable_width, $carousel_mode, $image_width, $image_height, $image_crop = false, $show_2x_image = false, $watermark = false ) {
			$image_light_box_url = wp_get_attachment_image_src( $img_id, 'full' );
			$image_light_box_url = is_array( $image_light_box_url ) ? $image_light_box_url : array( '', '', '' );
			$image_url           = wp_get_attachment_image_src( $img_id, $image_sizes );
			$image_url           = is_array( $image_url ) ? $image_url : array( '', '', '' );
			$image_width_attr    = $is_variable_width ? 'auto' : $image_url[1];
			$image_height_attr   = $image_url[2];
			$image_resize_url    = '';
			$image_resize_2x_url = '';
			if ( ( 'custom' === $image_sizes ) && ( ! empty( $image_width ) && $image_light_box_url[1] >= $image_width ) && ( ! empty( $image_height ) && $image_light_box_url[2] >= $image_height ) ) {
				$image_resize_url = self::image_resize( $image_light_box_url[0], $image_width, $image_height, $image_crop );
				if ( $show_2x_image && ( $image_light_box_url[1] >= ( $image_width * 2 ) ) && $image_light_box_url[2] >= ( $image_height * 2 ) ) {
					$image_resize_2x_url = self::image_resize( $image_light_box_url[0], $image_width * 2, $image_height * 2, $image_crop );
				}
				$image_width_attr  = ( $is_variable_width ) ? 'auto' : $image_width;
				$image_height_attr = $image_height;
			}
			$image_src = ! empty( $image_resize_url ) ? $image_resize_url : $image_url[0];

			if ( $watermark && function_exists( 'imagecreatefromstring' ) ) {
				$image_src = self::wpcp_watermark( $image_src )['url'];
				if ( $image_resize_2x_url ) {
					$image_resize_2x_url = self::wpcp_watermark( $image_resize_2x_url )['url'];
				}
			}
			return array(
				'src'    => $image_src,
				'srcset' => $image_resize_2x_url,
				'width'  => $image_width_attr,
				'height' => $image_height_attr,
			);
		}
		/**
		 * The get_item_slug function return item slug from item url.
		 *
		 * @param  mixed $item_url item url.
		 * @return string
		 */
		public static function get_item_slug( $item_url ) {
			$video_parse     = explode( '/', $item_url );
			$video_extension = is_array( $video_parse ) && ! empty( $video_parse ) ? $video_parse [ count( $video_parse ) - 1 ] : '';
			return $video_extension;
		}
		/**
		 * Image watermark method
		 *
		 * @param  mixed $img_src image src.
		 * @return string
		 */
		public static function wpcp_watermark( $img_src ) {
			@ini_set( 'memory_limit', '256M' );
			$wm_img          = wpcp_get_option( 'wm_image', '' );
			$_watermark_type = wpcp_get_option( 'wm_watermark_type', 'logo' );
			if ( ! empty( $wm_img ) ) {
				$wm_img_title = $wm_img['title'];
				$wm_img       = $wm_img['url'];
			}

			if ( empty( $wm_img ) && 'text' === $_watermark_type ) {
				$wm_img = $img_src;
			}

			if ( empty( $wm_img ) ) {
				return array( 'url' => $img_src );
			}
			$wm_position            = wpcp_get_option( 'wm_position', 'rb' );
			$wm_opacity             = wpcp_get_option( 'wm_opacity', 0.5 );
			$wm_opacity             = $wm_opacity * 100;
			$wm_size                = wpcp_get_option( 'wm_size', array( 15, 15 ) );
			$wm_margin_arr          = wpcp_get_option( 'wm_margin' );
			$wm_quality             = wpcp_get_option( 'wm_quality', 80 );
			$wm_margin              = isset( $wm_margin_arr['all'] ) ? (int) $wm_margin_arr['all'] : 10;
			$wm_margin_unit         = isset( $wm_margin_arr['unit'] ) ? $wm_margin_arr['unit'] : '%';
			$wm_horizontal          = isset( $wm_size['top'] ) ? (int) $wm_size['top'] : 15;
			$wm_vertical            = isset( $wm_size['bottom'] ) ? (int) $wm_size['bottom'] : 15;
			$wm_watermark_text_size = wpcp_get_option( 'wm_watermark_text_size', '24' );
			$wm_text_color          = wpcp_get_option( 'wm_text_color', '#ffffff' );
			// Cached instance? use it!
			if ( ! filter_var( $wm_img, FILTER_VALIDATE_URL ) ) {
				die( esc_html__( "Watermark image's url is wrong", 'wp-carousel-pro' ) );
			}

			// Setup class.
			$wp_dirs     = wp_upload_dir();
			$folder_name = 'wpcp_watermarked';
			$args        = array(
				'cache_folder_dir' => trailingslashit( $wp_dirs['basedir'] ) . $folder_name,
				'cache_folder_url' => trailingslashit( $wp_dirs['baseurl'] ) . $folder_name,
				'quality'          => $wm_quality,
				'proportional'     => wpcp_get_option( 'wm_custom', false ),
				'prop_sizes'       => array( $wm_horizontal, $wm_vertical ),
				'wm_pos'           => $wm_position,
				'wm_margin'        => $wm_margin,
				'wm_margin_type'   => $wm_margin_unit,
				'wm_opacity'       => $wm_opacity,
				'text_size'        => $wm_watermark_text_size,
				'wm_text_color'    => $wm_text_color,

			);

			$wpcpwm = new Image_Watermark( $wm_img, $args, $wm_img_title, $_watermark_type );

			return $wpcpwm->mark_it( $img_src );
		}

		/**
		 * Image Tag
		 *
		 * @param  mixed  $lazy_load_image lazy load.
		 * @param  mixed  $carousel_mode Carousel mode.
		 * @param  mixed  $image_attr image attr.
		 * @param  mixed  $image_alt_title image alt text.
		 * @param  mixed  $image_title_attr image title attribute.
		 * @param  string $lazy_load_img lazy image.
		 * @param  string $wpcp_layout layout types.
		 * @return string
		 */
		public static function image_tag( $lazy_load_image, $carousel_mode, $image_attr, $image_alt_title, $image_title_attr, $lazy_load_img, $wpcp_layout ) {
			$src_2x = ( ! empty( $image_attr['srcset'] ) ) ? esc_attr( $image_attr['src'] ) . ', ' . esc_attr( $image_attr['srcset'] ) . ' 2x' : esc_attr( $image_attr['src'] );
			if ( 'false' !== $lazy_load_image && 'ticker' !== $carousel_mode && 'carousel' === $wpcp_layout ) {
				$image = sprintf( '<img data-srcset="%6$s" class="wcp-lazy" data-src="%1$s" src="%1$s" %2$s alt="%3$s" width="%4$s" height="%5$s" loading="lazy">', $image_attr['src'], $image_title_attr, $image_alt_title, $image_attr['width'], $image_attr['height'], $src_2x );
			} elseif ( ! self::is_carousel( $wpcp_layout ) && 'false' !== $lazy_load_image ) {
				$image = sprintf( '<img srcset="%7$s" class="wpcp-lazyload" data-wpcp_src="%1$s" src="%2$s" %3$s alt="%4$s" width="%5$s" height="%6$s">', $image_attr['src'], $lazy_load_img, $image_title_attr, $image_alt_title, $image_attr['width'], $image_attr['height'], $src_2x );
			} else {
				$image = sprintf( '<img srcset="%6$s" src="%1$s" %2$s alt="%3$s" width="%4$s" height="%5$s" class="skip-lazy">', $image_attr['src'], $image_title_attr, $image_alt_title, $image_attr['width'], $image_attr['height'], $src_2x );
			}
			return $image;
		}

		/**
		 * Sets cached data based on a cache key, with optional user and cache settings.
		 *
		 * @param string $cache_key              The key for storing the cached data.
		 * @param mixed  $cache_data             The data to be cached.
		 * @param bool   $is_source_video_type   Flag indicating whether the cache is for source video type.
		 */
		public static function wpcp_set_transient( $cache_key, $cache_data, $is_source_video_type = false ) {
			// Check if caching is enabled and the current user has the required capability.
			if ( ! self::wpcp_should_use_cache() ) {
				return;
			}

			// If cache is for source video type, bypass user permission.
			if ( ( $is_source_video_type && self::wpcp_is_admin() ) || ! self::wpcp_is_admin() ) {
				// Set the cached data based on cache key.
				if ( is_multisite() ) {
					set_site_transient( $cache_key, $cache_data, WPCP_TRANSIENT_EXPIRATION );
				} else {
					set_transient( $cache_key, $cache_data, WPCP_TRANSIENT_EXPIRATION );
				}
			}
		}

		/**
		 * Checks if caching is enabled.
		 *
		 * @return bool Whether caching is enabled or not.
		 */
		public static function wpcp_should_use_cache() {
			return wpcp_get_option( 'wpcp_use_cache', true );
		}
		/**
		 * Check if the layout is a carousel type.
		 *
		 * @param string $wpcp_layout The layout type to check.
		 * @return bool True if the layout is a carousel type, false otherwise.
		 */
		public static function is_carousel( $wpcp_layout ) {
			return in_array( $wpcp_layout, array( 'carousel', 'thumbnails-slider', 'slider' ), true );
		}
		/**
		 * Checks if the current user is an admin.
		 *
		 * @return bool Whether the current user is an admin or not.
		 */
		public static function wpcp_is_admin() {
			$capability = apply_filters( 'sp_wp_carousel_ui_permission', 'manage_options' );
			return current_user_can( $capability ) ? true : false;
		}

		/**
		 * Retrieves cached data based on a cache key, with optional user and cache settings.
		 *
		 * @param string $cache_key              The key for retrieving the cached data.
		 * @param bool   $is_source_video_type   Flag indicating whether the cache is for source video type.
		 * @return mixed|false                   The cached data if available, or false if caching is disabled or user lacks permission.
		 */
		public static function wpcp_get_transient( $cache_key, $is_source_video_type = false ) {
			// If cache is for source video type, override admin status.
			$wpcp_is_admin = self::wpcp_is_admin();
			if ( $is_source_video_type && $wpcp_is_admin ) {
				$wpcp_is_admin = false;
			}
			if ( ! self::wpcp_should_use_cache() || $wpcp_is_admin ) {
				return false;
			}
			// Retrieve the cached data based on cache key.
			return is_multisite() ? get_site_transient( $cache_key ) : get_transient( $cache_key );
		}


		/**
		 * Section title
		 *
		 * @param  mixed  $post_id post id.
		 * @param  mixed  $section_title show/hide section title.
		 * @param  string $title section title.
		 * @return void
		 */
		public static function section_title( $post_id, $section_title, $title ) {
			$cache_key  = 'sp_wpcp_section_title' . $post_id . WPCAROUSEL_VERSION;
			$cache_data = self::wpcp_get_transient( $cache_key );
			if ( false !== $cache_data ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo $cache_data;
			} elseif ( $section_title ) {
					ob_start();
					include self::wpcp_locate_template( 'section-title.php' );
					$wpcp_section_title = apply_filters( 'sp_wpcp_section_title', ob_get_clean() );
					self::wpcp_set_transient( $cache_key, $wpcp_section_title );
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo $wpcp_section_title;
			}
		}

		/**
		 * Preloader
		 *
		 * @param  mixed $preloader_image loader image.
		 * @param  mixed $post_id post id.
		 * @param  mixed $preloader boolean.
		 * @return void
		 */
		public static function preloader( $preloader_image, $post_id, $preloader ) {
			if ( ! empty( $preloader_image ) && $preloader ) {
				ob_start();
				include self::wpcp_locate_template( 'preloader.php' );
				// Filter hook for the preloader.
				echo apply_filters( 'sp_wpcp_preloader', ob_get_clean() ); // phpcs:ignore
			}
		}

		/**
		 * Content carousel
		 *
		 * @param  mixed $content_source Content source.
		 * @return void
		 */
		public static function content_carousel_content( $content_source ) {
			global $wp_embed;
			$wpcp_inline_css = $content_source['wpcp_carousel_content_bg'];
			$image_bg        = '';
			$color_bg        = 'background-color: ' . $wpcp_inline_css['background-color'] . ';';

			if ( ! empty( $wpcp_inline_css['background-image']['url'] ) ) {
				$image_bg = ' background-image: url(' . $wpcp_inline_css['background-image']['url'] . '); background-position: ' . $wpcp_inline_css['background-position'] . '; background-repeat: ' . $wpcp_inline_css['background-repeat'] . '; background-size: ' . $wpcp_inline_css['background-size'] . '; background-attachment: ' . $wpcp_inline_css['background-attachment'] . ';';
			} else {
				$image_bg = ' background-image: linear-gradient(' . $wpcp_inline_css['background-gradient-direction'] . ', ' . $wpcp_inline_css['background-color'] . ', ' . $wpcp_inline_css['background-gradient-color'] . ');';
			}
			$content_style = $color_bg . $image_bg;
			ob_start();
			include self::wpcp_locate_template( 'loop/content-type.php' );
			// Filter hook for the carousel content.
			echo apply_filters( 'sp_wpcp_content_carousel_content', ob_get_clean() ); // phpcs:ignore
		}

		/**
		 * Post password required.
		 *
		 * @param  mixed $post_id post id.
		 * @return statement
		 */
		public static function post_password_required( $post_id = null ) {
			$post = get_post( $post_id );
			if ( empty( $post->post_password ) ) {
				return false;
			}

			if ( ! isset( $_COOKIE[ 'wpcp-postpass_cookie' . $post_id ] ) ) {
				return true;
			}

			$hash = sanitize_text_field( wp_unslash( $_COOKIE[ 'wpcp-postpass_cookie' . $post_id ] ) );
			if ( $post->post_password == $hash ) {
				return false;
			} else {
				return true;
			}
		}

		/**
		 * Get content from clint url
		 *
		 * @param  mixed $url clint url.
		 * @return string
		 */
		public static function file_get_contents_curl( $url ) {
			$insta_source = wp_remote_get( $url );
			$insta_source = $insta_source['body'];
			if ( is_wp_error( $insta_source ) || 200 != wp_remote_retrieve_response_code( $insta_source ) || empty( $insta_source['body'] ) ) {
				$ch         = curl_init();
				$ssl_verify = is_ssl() ? true : false;

				curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
				curl_setopt( $ch, CURLOPT_HEADER, 0 );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, $ssl_verify );

				$data = curl_exec( $ch );
				curl_close( $ch );

				return $data;
			}
			return $insta_source['body'];
		}

		/**
		 * Get pagination
		 *
		 * @param  array $upload_data shortcode upper metabox.
		 * @param  array $shortcode_data  shortcode bottom metabox.
		 * @param  int   $post_id shortcode id.
		 * @param  int   $pages total pages.
		 * @param  int   $posts_found total posts.
		 * @return void
		 */
		public static function get_pagination( $upload_data, $shortcode_data, $post_id, $pages, $posts_found ) {
			$wpcp_pagination  = isset( $shortcode_data['wpcp_source_pagination'] ) ? $shortcode_data['wpcp_source_pagination'] : false;
			$load_more_label  = isset( $shortcode_data['load_more_label'] ) ? $shortcode_data['load_more_label'] : 'Load More';
			$wpcp_layout      = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
			$per_views        = isset( $shortcode_data['post_per_page'] ) ? $shortcode_data['post_per_page'] : '';
			$end_content_text = apply_filters( 'wpcpro_end_content_text', __( 'End Of the Content', 'wp-carousel-pro' ) );
			$pagination_type  = isset( $shortcode_data['wpcp_pagination_type'] ) ? $shortcode_data['wpcp_pagination_type'] : '';
			if ( $wpcp_pagination && 'carousel' !== $wpcp_layout ) {
				$post_per_page  = isset( $shortcode_data['post_per_page'] ) ? (int) $shortcode_data['post_per_page'] : 10;
				$item_per_click = $post_per_page;
				if ( 'ajax_number' !== $pagination_type ) {
					$item_per_click = isset( $shortcode_data['item_per_click'] ) ? (int) $shortcode_data['item_per_click'] : $post_per_page;
				}
				$post_per_click = isset( $shortcode_data['post_per_click'] ) ? (int) $shortcode_data['post_per_click'] : $post_per_page;
				$carousel_type  = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
				$total_page     = 0;
				if ( 'content-carousel' === $carousel_type ) {
					$content_sources = isset( $upload_data['carousel_content_source'] ) && is_array( $upload_data['carousel_content_source'] ) ? $upload_data['carousel_content_source'] : array();
					$content_sources = count( $content_sources );
					$total_page      = $content_sources / $post_per_page;
					$total_page      = ceil( $total_page );
					// Post per page changed into post per click on load more.
					if ( 'ajax_number' !== $pagination_type && $total_page > 1 ) {
						$per_click          = $item_per_click;
						$after_offset_items = $content_sources > $post_per_page ? $content_sources - $post_per_page : 0;
						$total_page         = ceil( ( $after_offset_items / $per_click ) + 1 );
					}
				}
				if ( 'image-carousel' === $carousel_type ) {
					$gallery_ids = $upload_data['wpcp_gallery'];
					$attachments = count( explode( ',', $gallery_ids ) );
					$total_page  = $attachments / $post_per_page;
					$total_page  = ceil( $total_page );
					// Post per page changed into post per click on load more.
					if ( 'ajax_number' !== $pagination_type && $total_page > 1 ) {
						$per_click          = $item_per_click;
						$after_offset_items = $attachments > $post_per_page ? $attachments - $post_per_page : 0;
						$total_page         = ceil( ( $after_offset_items / $per_click ) + 1 );
					}
				}
				if ( 'video-carousel' === $carousel_type ) {
					$video_sources = isset( $upload_data['carousel_video_source'] ) && is_array( $upload_data['carousel_video_source'] ) ? $upload_data['carousel_video_source'] : array();
					$video_sources = count( $video_sources );
					$total_page    = $video_sources / $post_per_page;
					$total_page    = ceil( $total_page );
					// Post per page changed into post per click on load more.
					if ( 'ajax_number' !== $pagination_type && $total_page > 1 ) {
						$per_click          = $item_per_click;
						$after_offset_items = $video_sources > $post_per_page ? $video_sources - $post_per_page : 0;
						$total_page         = ceil( ( $after_offset_items / $per_click ) + 1 );
					}
				}
				if ( 'audio-carousel' === $carousel_type ) {
					$audio_sources = isset( $upload_data['carousel_audio_source'] ) && is_array( $upload_data['carousel_audio_source'] ) ? $upload_data['carousel_audio_source'] : array();
					$audio_sources = count( $audio_sources );
					$total_page    = $audio_sources / $post_per_page;
					$total_page    = ceil( $total_page );
					// Post per page changed into post per click on load more.
					if ( 'ajax_number' !== $pagination_type && $total_page > 1 ) {
						$per_click          = $item_per_click;
						$after_offset_items = $audio_sources > $post_per_page ? $audio_sources - $post_per_page : 0;
						$total_page         = ceil( ( $after_offset_items / $per_click ) + 1 );
					}
				}
				if ( 'mix-content' === $carousel_type ) {
					$mix_sources = isset( $upload_data['carousel_mix_source'] ) && is_array( $upload_data['carousel_mix_source'] ) ? $upload_data['carousel_mix_source'] : array();
					$mix_sources = count( $mix_sources );
					$total_page  = $mix_sources / $post_per_page;
					$total_page  = ceil( $total_page );
					// Post per page changed into post per click on load more.
					if ( 'ajax_number' !== $pagination_type && $total_page > 1 ) {
						$per_click          = $item_per_click;
						$after_offset_items = $mix_sources > $post_per_page ? $mix_sources - $post_per_page : 0;
						$total_page         = ceil( ( $after_offset_items / $per_click ) + 1 );
					}
				}
				if ( 'external-carousel' === $carousel_type ) {
					$show_total_content = isset( $upload_data['wpcp_external_limit'] ) && ! empty( $upload_data['wpcp_external_limit'] ) ? $upload_data['wpcp_external_limit'] : '10';
					$rss_items          = self::rss_feeds( $upload_data, $show_total_content );
					$all_rss_data_count = $rss_items ? count( $rss_items ) : 0;
					$total_page         = $all_rss_data_count > 0 ? $all_rss_data_count / $post_per_page : 1;
					$total_page         = ceil( $total_page );
					// Post per page changed into post per click on load more.
					if ( 'ajax_number' !== $pagination_type && $total_page > 1 ) {
						$per_click          = $item_per_click;
						$after_offset_items = $all_rss_data_count > $post_per_page ? $all_rss_data_count - $post_per_page : 0;
						$total_page         = ceil( ( $after_offset_items / $per_click ) + 1 );
					}
				}
				$wpcp_nonce = wp_create_nonce( 'wpcp_nonce' );
				if ( 'post-carousel' === $carousel_type || 'product-carousel' === $carousel_type ) {
					$product_limit        = isset( $upload_data['wpcp_total_products'] ) ? $upload_data['wpcp_total_products'] : '';
					$post_limit           = isset( $upload_data['number_of_total_posts'] ) ? $upload_data['number_of_total_posts'] : '';
					$limit                = 'post-carousel' === $carousel_type ? $post_limit : $product_limit;
					$post_order_by        = isset( $shortcode_data['wpcp_post_order_by'] ) ? $shortcode_data['wpcp_post_order_by'] : '';
					$post_order           = isset( $shortcode_data['wpcp_post_order'] ) ? $shortcode_data['wpcp_post_order'] : '';
					$post_pagination_type = isset( $shortcode_data['wpcp_post_pagination_type'] ) ? $shortcode_data['wpcp_post_pagination_type'] : '';

					$wpcp_layout = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
					$prev_next   = true;
					if ( 'load_more_btn' === $post_pagination_type || 'infinite_scroll' === $post_pagination_type ) {
						$prev_next = false;
					}
					$total_pages = $pages;
					// Full wp pagination example.
					$wppaged    = 'paged' . $post_id;
					$args       = array(
						'format'       => '?' . $wppaged . '=%#%',
						'current'      => isset( $_GET[ "$wppaged" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$wppaged" ] ) ) : 1,
						'total'        => $total_pages,
						'prev_next'    => $prev_next,
						'next_text'    => '<i class="fa fa-angle-right"></i>',
						'prev_text'    => '<i class="fa fa-angle-left"></i>',
						'show_all'     => true,
						'aria_current' => true,
					);
					$page_links = paginate_links( $args );
				}
				include self::wpcp_locate_template( 'pagination.php' );
			}
		}
		/**
		 * Handle random order and set transient for random value.
		 *
		 * @param mixed $post_id Shortcode ID.
		 * @return string Orderby parameter for WP_Query.
		 */
		private static function handle_random_order( $post_id ) {
			$wppaged = 'paged' . $post_id;
			$paged   = isset( $_GET[ $wppaged ] ) ? wp_unslash( $_GET[ $wppaged ] ) : 1;

			if ( $paged && $paged === 1 ) {
				set_transient( 'wpcp_rand', wp_rand() );
			}

			return 'rand(' . get_transient( 'wpcp_rand' ) . ')';
		}
		/**
		 * Post and product query
		 *
		 * @param  array $upload_data upper upload data.
		 * @param  array $shortcode_data bottom metabox.
		 * @param  mixed $post_id shortcode id.
		 * @return object
		 */
		public static function wpcp_query( $upload_data, $shortcode_data, $post_id ) {
			$carousel_type = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
			// Order orderby.
			$post_order_by   = isset( $shortcode_data['wpcp_post_order_by'] ) ? $shortcode_data['wpcp_post_order_by'] : 'date';
			$post_order      = isset( $shortcode_data['wpcp_post_order'] ) ? $shortcode_data['wpcp_post_order'] : 'DESC';
			$post_per_click  = isset( $shortcode_data['post_per_click'] ) ? (int) $shortcode_data['post_per_click'] : 10;
			$post_per_page   = isset( $shortcode_data['post_per_page'] ) ? (int) $shortcode_data['post_per_page'] : 15;
			$wpcp_pagination = isset( $shortcode_data['wpcp_source_pagination'] ) ? $shortcode_data['wpcp_source_pagination'] : false;
			$wpcp_layout     = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
			$final_args      = array();

			$wpcp_hide_product_without_img = isset( $shortcode_data['wpcp_hide_product_without_img'] ) ? $shortcode_data['wpcp_hide_product_without_img'] : false;

			if ( 'rand' === $post_order_by ) {
				$post_order_by = self::handle_random_order( $post_id );
			}

			if ( 'post-carousel' === $carousel_type ) {
				$wpcp_post_type = isset( $upload_data['wpcp_post_type'] ) ? $upload_data['wpcp_post_type'] : 'post';
				$tax_filter     = true;
				if ( 'multiple_post_type' === $wpcp_post_type ) {
					$tax_filter     = false;
					$wpcp_post_type = isset( $upload_data['wpcp_multi_post_type'] ) ? $upload_data['wpcp_multi_post_type'] : 'post';
				}
				$post_from              = isset( $upload_data['wpcp_display_posts_from'] ) ? $upload_data['wpcp_display_posts_from'] : '';
				$specific_post_ids      = isset( $upload_data['wpcp_specific_posts'] ) ? $upload_data['wpcp_specific_posts'] : array();
				$post_taxonomy          = isset( $upload_data['wpcp_post_taxonomy'] ) ? $upload_data['wpcp_post_taxonomy'] : '';
				$post_taxonomy_terms    = isset( $upload_data['wpcp_taxonomy_terms'] ) ? $upload_data['wpcp_taxonomy_terms'] : '';
				$post_taxonomy_operator = isset( $upload_data['taxonomy_operator'] ) ? $upload_data['taxonomy_operator'] : '';
				$number_of_total_posts  = isset( $upload_data['number_of_total_posts'] ) && ! empty( $upload_data['number_of_total_posts'] ) ? $upload_data['number_of_total_posts'] : -1;
				$include_current_post   = apply_filters( 'sp_wpcp_include_current_post', false );
				$post_suppress_filters  = apply_filters( 'sp_wpcp_suppress_filter', false );
				$args                   = array(
					'post_type'           => $wpcp_post_type,
					'post_status'         => 'publish',
					'fields'              => 'ids',
					'order'               => $post_order, // If used random order, Randomly limited ids come from all ids.
					'orderby'             => $post_order_by,
					'ignore_sticky_posts' => 1,
					'suppress_filters'    => $post_suppress_filters,
					'posts_per_page'      => $number_of_total_posts,
					'post__not_in'        => array( get_the_ID() ),
				);

				if ( $include_current_post ) {
					unset( $args['post__not_in'] );
				}
				// In multiple post type filter posts option Excluded.
				if ( $tax_filter ) {
					// Specific post.
					if ( 'specific_post' === $post_from ) {
						if ( is_array( $specific_post_ids ) && ( false !== $key = array_search( get_the_ID(), $specific_post_ids ) ) && ! $include_current_post ) {
							unset( $specific_post_ids[ $key ] );
						}
						$wpcp_get_specific = array(
							'post__in'       => $specific_post_ids,
							'posts_per_page' => -1,
							'orderby'        => 'post__in',

						);
						$args = array_merge( $args, $wpcp_get_specific );
					}
					// Get posts with taxonomy.
					if ( 'taxonomy' === $post_from && ! empty( $post_taxonomy ) && ! empty( $post_taxonomy_terms ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => $post_taxonomy,
							'field'    => 'term_id',
							'terms'    => $post_taxonomy_terms,
							'operator' => $post_taxonomy_operator,
						);
					}
				}
				// Get array of all queried members id.
				$queried_post_ids      = get_posts( $args );
				$number_of_total_posts = count( $queried_post_ids );

				if ( ! empty( $queried_post_ids ) ) {
					if ( ! self::is_carousel( $wpcp_layout ) && $wpcp_pagination ) {
						$wppaged              = 'paged' . $post_id;
						$post_pagination_type = isset( $shortcode_data['wpcp_post_pagination_type'] ) ? $shortcode_data['wpcp_post_pagination_type'] : '';
						$paged                = isset( $_GET[ "$wppaged" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$wppaged" ] ) ) : 1;

						// Post per page changed into post per click on load more.
						if ( 'normal' !== $post_pagination_type && 'ajax_number' !== $post_pagination_type && $paged > 1 ) {
							$after_offset_post = $number_of_total_posts > $post_per_page ? $number_of_total_posts - $post_per_page : 0;
							$offset            = $post_per_page + ( $post_per_click * ( $paged - 2 ) );
							$post_per_page     = $post_per_click;
							$total_page        = ceil( ( $after_offset_post / $post_per_page ) + 1 );
							if ( $total_page == $paged ) {
								$last_page     = $paged - 2;
								$post_per_page = $after_offset_post - ( $last_page * $post_per_page );
							}

							$final_args = array(
								'post_type'           => $wpcp_post_type,
								'post_status'         => 'publish',
								'order'               => $post_order,
								'orderby'             => $post_order_by,
								'offset'              => $offset,
								'posts_per_page'      => $post_per_page,
								'ignore_sticky_posts' => 1,
								'suppress_filters'    => $post_suppress_filters,
								'post__in'            => $queried_post_ids,
							);
						} else {
								$final_args = array(
									'post_type'           => $wpcp_post_type,
									'post_status'         => 'publish',
									'order'               => $post_order,
									'orderby'             => $post_order_by,
									'posts_per_page'      => $post_per_page,
									'paged'               => $paged,
									'ignore_sticky_posts' => 1,
									'suppress_filters'    => $post_suppress_filters,
									'post__in'            => $queried_post_ids,
								);
						}
					} else {
						$final_args = array(
							'post_type'           => $wpcp_post_type,
							'post_status'         => 'publish',
							'order'               => $post_order,
							'orderby'             => $post_order_by,
							'ignore_sticky_posts' => 1,
							'suppress_filters'    => $post_suppress_filters,
							'post__in'            => $queried_post_ids,
							'posts_per_page'      => $number_of_total_posts,
						);
					}
				}
			}
			if ( 'product-carousel' === $carousel_type ) {
				$wpcp_product_from        = $upload_data['wpcp_display_product_from'];
				$specific_product_ids     = isset( $upload_data['wpcp_specific_product'] ) ? $upload_data['wpcp_specific_product'] : '';
				$product_category_terms   = isset( $upload_data['wpcp_taxonomy_product_terms'] ) ? $upload_data['wpcp_taxonomy_product_terms'] : '';
				$product_terms_operator   = isset( $upload_data['wpcp_product_category_operator'] ) ? $upload_data['wpcp_product_category_operator'] : '';
				$number_of_total_products = isset( $upload_data['wpcp_total_products'] ) && ! empty( $upload_data['wpcp_total_products'] ) ? $upload_data['wpcp_total_products'] : -1;
				$product_suppress_filters = apply_filters( 'sp_wpcp_suppress_filter', false );
				$default_args             = array(
					'post_type'           => 'product',
					'post_status'         => 'publish',
					'ignore_sticky_posts' => 1,
					'posts_per_page'      => $number_of_total_products,
					'fields'              => 'ids',
					'orderby'             => $post_order_by,
					'order'               => $post_order,
					'meta_query'          => array(
						array(
							'key'     => '_stock_status',
							'value'   => 'outofstock',
							'compare' => 'NOT IN',
						),
					),
				);
				if ( 'latest' === $wpcp_product_from ) {
					$args = $default_args;
				} elseif ( 'on_sale' === $wpcp_product_from ) {
					$args['meta_query'] = array(
						array(
							'key'     => '_sale_price',
							'value'   => 0,
							'compare' => '>',
							'type'    => 'numeric',
						),
					);
					$args               = array_merge( $default_args, $args );
				} elseif ( 'specific_products' === $wpcp_product_from ) {
					$args = array(
						'post__in'       => $specific_product_ids,
						'posts_per_page' => -1,
						'orderby'        => 'post__in',
					);
					$args = array_merge( $default_args, $args );
				} elseif ( 'taxonomy' === $wpcp_product_from ) {
					$args['tax_query'][] = array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $product_category_terms,
						'operator' => $product_terms_operator,
					);
					$args                = array_merge( $default_args, $args );
				}
				$args                  = apply_filters( 'wpcp_product_carousel_args', $args, $post_id );
				$queried_post_ids      = get_posts( $args );
				$number_of_total_posts = count( $queried_post_ids );

				if ( ! empty( $queried_post_ids ) ) {

					if ( ! self::is_carousel( $wpcp_layout ) && $wpcp_pagination ) {
						$wppaged = 'paged' . $post_id;
						$paged   = isset( $_GET[ "$wppaged" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$wppaged" ] ) ) : 1;

						$post_pagination_type = isset( $shortcode_data['wpcp_post_pagination_type'] ) ? $shortcode_data['wpcp_post_pagination_type'] : '';
						// Post per page changed into post per click on load more.
						$offset = 0;
						if ( 'normal' !== $post_pagination_type && 'ajax_number' !== $post_pagination_type && $paged > 1 ) {
							$after_offset_post = $number_of_total_posts > $post_per_page ? $number_of_total_posts - $post_per_page : 0;
							$offset            = $post_per_page + ( $post_per_click * ( $paged - 2 ) );
							$post_per_page     = $post_per_click;
							$total_page        = ceil( ( $after_offset_post / $post_per_page ) + 1 );
							if ( $total_page == $paged ) {
								$last_page     = $paged - 2;
								$post_per_page = $after_offset_post - ( $last_page * $post_per_page );
							}

							$final_args = array(
								'post_type'           => 'product',
								'post_status'         => 'publish',
								'order'               => $post_order,
								'orderby'             => $post_order_by,
								'offset'              => $offset,
								'posts_per_page'      => $post_per_page,
								'ignore_sticky_posts' => 1,
								'suppress_filters'    => $product_suppress_filters,
								'post__in'            => $queried_post_ids,
							);
						} else {
							$final_args = array(
								'post_type'           => 'product',
								'post_status'         => 'publish',
								'order'               => $post_order,
								'orderby'             => $post_order_by,
								'posts_per_page'      => $post_per_page,
								'paged'               => $paged,
								'ignore_sticky_posts' => 1,
								'suppress_filters'    => $product_suppress_filters,
								'post__in'            => $queried_post_ids,
							);
						}
					} else {
						$final_args = array(
							'post_type'           => 'product',
							'post_status'         => 'publish',
							'ignore_sticky_posts' => 1,
							'order'               => $post_order,
							'orderby'             => $post_order_by,
							'suppress_filters'    => $product_suppress_filters,
							'post__in'            => $queried_post_ids,
							'posts_per_page'      => $number_of_total_posts,
						);
					}
				}
			}

			if ( $wpcp_hide_product_without_img && 'product-carousel' === $carousel_type ) {
				$final_args['meta_query'][] = array(
					'key'     => '_thumbnail_id',
					'compare' => 'AND',
				);
			}

			$post_query = new \WP_Query( $final_args );

			return $post_query;
		}

		/**
		 * All loops items.
		 *
		 * @param  array  $upload_data upper upload data.
		 * @param  array  $shortcode_data bottom metabox.
		 * @param  int    $post_id shortcode id.
		 * @param  string $animation_class animation class.
		 * @param  object $wpcp_query query.
		 * @param  string $slider_animation carousel slide class.
		 * @param  string $thumbnail_slider thumb slide.
		 * @return statement
		 */
		public static function get_item_loops( $upload_data, $shortcode_data, $post_id, $animation_class, $wpcp_query = null, $slider_animation = 'slider', $thumbnail_slider = false ) {
			include WPCAROUSEL_PATH . 'Frontend/partials/generator-options.php';
			// Show Pagination.
			$wpcp_pagination = isset( $shortcode_data['wpcp_source_pagination'] ) ? $shortcode_data['wpcp_source_pagination'] : false;
			// Content.
			if ( 'content-carousel' === $carousel_type ) {
				$content_sources = isset( $upload_data['carousel_content_source'] ) ? $upload_data['carousel_content_source'] : '';
				if ( empty( $content_sources ) ) {
					return;
				}
				if ( 'rand' === $image_orderby ) {
					shuffle( $content_sources );
					if ( $wpcp_pagination && count( $content_sources ) > $post_per_page && $wpcp_pagination && ! self::is_carousel( $wpcp_layout ) ) {
						set_transient( $post_id . 'sp_wpcp_content_data', $content_sources, 10 );
					}
				}
				if ( $wpcp_pagination && count( $content_sources ) > $post_per_page && $wpcp_pagination && ! self::is_carousel( $wpcp_layout ) ) {
					$content_sources = array_slice( $content_sources, 0, $post_per_page );
				}
				include WPCAROUSEL_PATH . 'Frontend/partials/content_sources.php';
			}
			// Image.
			if ( 'image-carousel' === $carousel_type ) {
				$gallery_ids            = $upload_data['wpcp_gallery'];
				$is_image_link_nofollow = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] : '';
				$wpcp_watermark         = isset( $shortcode_data['wpcp_watermark'] ) ? $shortcode_data['wpcp_watermark'] : false;
				$image_link_nofollow    = $is_image_link_nofollow ? ' rel=' . esc_attr( 'nofollow' ) . '' : '';
				if ( empty( $gallery_ids ) ) {
					return;
				}
				$attachments = explode( ',', $gallery_ids );
				if ( 'rand' === $image_orderby ) {
					shuffle( $attachments );
					if ( ! empty( $post_per_page ) && count( $attachments ) > $post_per_page && $wpcp_pagination && ! self::is_carousel( $wpcp_layout ) ) {
						set_transient( $post_id . 'sp_wpcp_attachments_data', json_encode( $attachments ), 10 );
					}
				}
				if ( ! empty( $post_per_page ) && count( $attachments ) > $post_per_page && $wpcp_pagination && ! self::is_carousel( $wpcp_layout ) ) {
					$attachments = array_slice( $attachments, 0, $post_per_page );
				}

				$attachments = apply_filters( 'sp_wpcp_image_attachments', $attachments, $post_id );
				if ( is_array( $attachments ) || is_object( $attachments ) ) :
					foreach ( $attachments as $attachment ) {
						$image_data = get_post( $attachment );
						if ( ! $image_data ) {
							continue;
						}
						$image_id         = $image_data->ID;
						$image_title      = $image_data->post_title;
						$image_alt_titles = $image_data->_wp_attachment_image_alt;

						$image_caption = $image_data->post_excerpt;
						if ( 'title' === $image_title_source ) {
							$image_caption = $image_title;
						} elseif ( 'alt' === $image_title_source ) {
							$image_caption = $image_alt_titles;
						}
						$image_description  = $image_data->post_content;
						$image_alt_title    = ! empty( $image_alt_titles ) ? $image_alt_titles : $image_title;
						$image_linking_meta = wp_get_attachment_metadata( $attachment );
						$image_linking_urls = isset( $image_linking_meta['image_meta'] ) ? $image_linking_meta['image_meta'] : '';
						$image_linking_url  = ! empty( $image_linking_urls['wpcplinking'] ) ? esc_url( $image_linking_urls['wpcplinking'] ) : '';
						// $image_linking_url  = get_post_meta( $image_id, 'wpcplinking', true );
						$crop_position      = get_post_meta( $image_id, 'crop_position', true );
						$link_target        = get_post_meta( $image_id, 'wpcplinktarget', true ) ? '_blank' : $link_target;
						$image_crop_new     = $image_crop;
						// Check if a custom crop position has been provided and needs to be applied.
						if ( ! empty( $crop_position ) && 'center-center' !== $crop_position && 'custom' === $image_sizes && $image_crop ) {
							/**
							 * If the crop position is not empty, not the default 'center-center',
							 * and the image size is 'custom', we update the $image_crop value
							 * to use the provided $crop_position.
							 *
							 * This ensures that custom cropping (e.g., 'left-top', 'right-bottom', etc.)
							 * is applied instead of the default 'center-center' cropping.
							 */
							$image_crop_new = $crop_position;
						}
						$wcp_light_box_class = '';
						if ( 'l_box' === $image_link_show ) {
							$image_linking_url   = '#';
							$wcp_light_box_class = 'wcp-light-box-caption';
						}
						$the_image_title_attr = ' title="' . $image_title . '"';
						$image_title_attr     = 'true' === $show_image_title_attr ? $the_image_title_attr : '';
						$image_attr           = self::image_attr( $attachment, $image_sizes, $is_variable_width, $carousel_mode, $image_width, $image_height, $image_crop_new, $show_2x_image, $wpcp_watermark );

						$image               = self::image_tag( $lazy_load_image, $carousel_mode, $image_attr, $image_alt_title, $image_title_attr, $lazy_load_img, $wpcp_layout );
						$image_light_box_url = wp_get_attachment_image_src( $attachment, 'full' );
						include self::wpcp_locate_template( 'loop/image-type.php' );
					} // End foreach.
				endif;
			}
			// Audio.
			if ( 'audio-carousel' === $carousel_type ) {
				$audio_sources = ! empty( $upload_data['carousel_audio_source'] ) ? $upload_data['carousel_audio_source'] : array();
				if ( empty( $audio_sources ) ) {
					return;
				}
				if ( 'rand' === $image_orderby ) {
						shuffle( $audio_sources );
					if ( ! empty( $post_per_page ) && count( $audio_sources ) > $post_per_page && $wpcp_pagination && ! self::is_carousel( $wpcp_layout ) ) {
						set_transient( $post_id . 'sp_wpcp_audio_data', $audio_sources, 10 );
					}
				}
				if ( ! empty( $post_per_page ) && count( $audio_sources ) > $post_per_page && $wpcp_pagination && ! self::is_carousel( $wpcp_layout ) ) {
					$audio_sources = array_slice( $audio_sources, 0, $post_per_page );
				}
				include WPCAROUSEL_PATH . 'Frontend/partials/audio_sources.php';

			}
			// Video.
			if ( 'video-carousel' === $carousel_type ) {
				$video_sources = isset( $upload_data['carousel_video_source'] ) ? $upload_data['carousel_video_source'] : '';
				if ( empty( $video_sources ) ) {
					return;
				}
				$sp_urls       = self::get_video_thumb_url( $video_sources );
				$lightbox_data = 'data-thumbs="true" data-outside="1" data-loop=1 data-keyboard=1';
				if ( 'rand' === $image_orderby ) {
					$cash_video_data = get_transient( $post_id . 'sp_wpcp_video_data' );
					if ( $cash_video_data ) {
						$sp_urls = $cash_video_data;
					} else {
						shuffle( $sp_urls );
						set_transient( $post_id . 'sp_wpcp_video_data', $sp_urls, 10 );
					}
				}
				if ( ! empty( $post_per_page ) && count( $sp_urls ) > $post_per_page && $wpcp_pagination && ! self::is_carousel( $wpcp_layout ) ) {
					$sp_urls = array_slice( $sp_urls, 0, $post_per_page );
				}

				include WPCAROUSEL_PATH . 'Frontend/partials/video_sources.php';
			}
			// Post.
			if ( 'post-carousel' === $carousel_type ) {
				$show_post_content      = $shortcode_data['wpcp_post_content_show'];
				$wpcp_content_type      = $shortcode_data['wpcp_post_content_type'];
				$wpcp_title_chars_limit = isset( $shortcode_data['wpcp_post_title_chars_limit'] ) ? $shortcode_data['wpcp_post_title_chars_limit'] : '';
				$wpcp_word_limit        = isset( $shortcode_data['wpcp_post_content_words_limit'] ) ? $shortcode_data['wpcp_post_content_words_limit'] : 30;
				$post_taxonomy          = isset( $upload_data['wpcp_post_taxonomy'] ) ? $upload_data['wpcp_post_taxonomy'] : '';
				$show_read_more         = isset( $shortcode_data['wpcp_post_readmore_button_show'] ) ? $shortcode_data['wpcp_post_readmore_button_show'] : '';
				$post_read_more_text    = isset( $shortcode_data['wpcp_post_readmore_text'] ) ? $shortcode_data['wpcp_post_readmore_text'] : '';
				$wpcp_post_type         = isset( $upload_data['wpcp_post_type'] ) ? $upload_data['wpcp_post_type'] : 'post';
				if ( 'multiple_post_type' === $wpcp_post_type ) {
					$wpcp_post_type = isset( $upload_data['wpcp_multi_post_type'] ) ? $upload_data['wpcp_multi_post_type'] : 'post';
				}
				$show_post_category    = isset( $shortcode_data['wpcp_post_category_show'] ) ? $shortcode_data['wpcp_post_category_show'] : true;
				$show_post_date        = isset( $shortcode_data['wpcp_post_date_show'] ) ? $shortcode_data['wpcp_post_date_show'] : true;
				$show_post_author      = isset( $shortcode_data['wpcp_post_author_show'] ) ? $shortcode_data['wpcp_post_author_show'] : true;
				$show_post_tags        = isset( $shortcode_data['wpcp_post_tags_show'] ) ? $shortcode_data['wpcp_post_tags_show'] : true;
				$show_post_comment     = isset( $shortcode_data['wpcp_post_comment_show'] ) ? $shortcode_data['wpcp_post_comment_show'] : true;
				$wpcp_post_social_show = isset( $shortcode_data['wpcp_post_social_show'] ) ? $shortcode_data['wpcp_post_social_show'] : false;

				$post_query = $wpcp_query;
				if ( $post_query->have_posts() ) {
					while ( $post_query->have_posts() ) :
						$post_query->the_post();
						$wpcp_get_post_title_attr = the_title_attribute( 'echo=0' );
						if ( $thumbnail_slider ) {
							$show_slide_image = 1;
							include self::wpcp_locate_template( 'loop/post-type/thumbnails.php' );
						} else {
							include self::wpcp_locate_template( 'loop/post-type.php' );
						}
						endwhile;
					wp_reset_postdata();
				} else {
					echo '<h2 class="wpcp-no-post-found" >' . esc_html__( 'No posts found', 'wp-carousel-pro' ) . '</h2>';
				}
			}
			// Product.
			if ( 'product-carousel' === $carousel_type ) {
				$wpcp_product_from            = $upload_data['wpcp_display_product_from'];
				$specific_product_ids         = isset( $upload_data['wpcp_specific_product'] ) ? $upload_data['wpcp_specific_product'] : '';
				$product_category_terms       = isset( $upload_data['wpcp_taxonomy_terms'] ) ? $upload_data['wpcp_taxonomy_terms'] : '';
				$product_terms_operator       = isset( $upload_data['wpcp_product_category_operator'] ) ? $upload_data['wpcp_product_category_operator'] : '';
				$show_product_image           = $shortcode_data['show_image'];
				$show_product_name            = $shortcode_data['wpcp_product_name'];
				$wpcp_name_chars_limit        = isset( $shortcode_data['wpcp_product_name_chars_limit'] ) ? $shortcode_data['wpcp_product_name_chars_limit'] : '';
				$show_product_price           = $shortcode_data['wpcp_product_price'];
				$show_product_rating          = $shortcode_data['wpcp_product_rating'];
				$show_product_cart            = $shortcode_data['wpcp_product_cart'];
				$show_product_desc            = isset( $shortcode_data['wpcp_product_desc'] ) ? $shortcode_data['wpcp_product_desc'] : 'full';
				$product_content_limit_number = isset( $shortcode_data['wpcp_product_desc_limit_number'] ) ? $shortcode_data['wpcp_product_desc_limit_number'] : '';
				$show_product_readmore        = isset( $shortcode_data['wpcp_product_readmore_show'] ) ? $shortcode_data['wpcp_product_readmore_show'] : '';
				$product_readmore_text        = isset( $shortcode_data['wpcp_product_readmore_text'] ) ? $shortcode_data['wpcp_product_readmore_text'] : '';
				$product_query                = $wpcp_query;
				$lightbox_data                = 'data-loop=1 data-keyboard=1';
				// Carousel Wrapper Start.
				if ( $product_query->have_posts() ) {
					while ( $product_query->have_posts() ) :
						$product_query->the_post();
						global $product, $woocommerce;
						if ( $thumbnail_slider ) {
							$show_slide_image = 1;
							include self::wpcp_locate_template( 'loop/product-type/image.php' );
						} else {
							include self::wpcp_locate_template( 'loop/product-type.php' );
						}
					endwhile;
					wp_reset_postdata();
				} else {
					// Apply filter hook for the product item not found text.
					echo apply_filters( 'wpcp_product_no_content_found_text', '<h2 class="sp-not-found-any-post">' . esc_html__( 'No products found', 'wp-carousel-pro' ) . '</h2>' ); // phpcs:ignore
				}
			}
			// Mix content.
			if ( 'mix-content' === $carousel_type ) {
				$mix_sources   = isset( $upload_data['carousel_mix_source'] ) ? $upload_data['carousel_mix_source'] : array();
				$lightbox_data = 'data-thumbs="true" data-outside="1" data-loop=1 data-keyboard=1';
				if ( ! empty( $post_per_page ) && count( $mix_sources ) > $post_per_page && ! self::is_carousel( $wpcp_layout ) ) {
					$mix_sources = array_slice( $mix_sources, 0, $post_per_page );
				}
				include WPCAROUSEL_PATH . 'Frontend/partials/mix_content_sources.php';
			}
			// External.
			if ( 'external-carousel' === $carousel_type ) {
				$show_total_content            = isset( $upload_data['wpcp_external_limit'] ) && ! empty( $upload_data['wpcp_external_limit'] ) ? $upload_data['wpcp_external_limit'] : '10';
				$wpcp_feed_content_show        = isset( $shortcode_data['wpcp_feed_content_show'] ) ? $shortcode_data['wpcp_feed_content_show'] : true;
				$show_feed_title               = isset( $shortcode_data['wpcp_feed_title'] ) ? $shortcode_data['wpcp_feed_title'] : true;
				$wpcp_feed_content_chars_limit = isset( $shortcode_data['wpcp_feed_content_chars_limit'] ) ? $shortcode_data['wpcp_feed_content_chars_limit'] : '';
				$show_feed_read_more           = isset( $shortcode_data['wpcp_feed_readmore_button_show'] ) ? $shortcode_data['wpcp_feed_readmore_button_show'] : true;
				$wpcp_feed_readmore_text       = isset( $shortcode_data['wpcp_feed_readmore_text'] ) ? $shortcode_data['wpcp_feed_readmore_text'] : 'Read More';
				$is_image_link_nofollow        = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] : '';
				$image_link_nofollow           = $is_image_link_nofollow ? ' rel=' . esc_attr( 'nofollow' ) . '' : '';
				$rss_items                     = self::rss_feeds( $upload_data, $show_total_content );
				if ( ! is_wp_error( $rss_items ) && $rss_items ) { // Checks that the object is created.
					if ( ! empty( $post_per_page ) && ! self::is_carousel( $wpcp_layout ) ) {
						$rss_items = array_slice( $rss_items, 0, $post_per_page );
					}
					foreach ( $rss_items as $item ) :
						$title       = $item->get_title();
						$link        = $item->get_permalink();
						$description = $item->get_content();

						if ( ! empty( $wpcp_feed_content_chars_limit ) ) {
							$description = wp_html_excerpt( $description, $wpcp_feed_content_chars_limit ) . ' ...';
						}
						$pubDate  = $item->get_date( 'j F Y | g:i a' );
						$category = $item->get_category();
						// If item have media:content.
						$enclosure = $item->get_enclosure();
						$thumb_src = '';
						if ( $enclosure ) {
							$thumb_src = $enclosure->get_link();
						}
						include self::wpcp_locate_template( 'loop/external-type/feeds.php' );
					endforeach;
				} else {
					echo '<h2>Invalid RSS feed URL.</h2>';
				}
				// }
			}
		}

		/**
		 * RSS feeds
		 *
		 * @param array $upload_data upload option array.
		 * @param  int   $show_total_content limit.
		 * @return array
		 */
		public static function rss_feeds( $upload_data, $show_total_content ) {
			$feeds_url = isset( $upload_data['wpcp_feeds_url'] ) ? $upload_data['wpcp_feeds_url'] : '';
			if ( ! function_exists( 'fetch_feed' ) ) {
				include_once ABSPATH . WPINC . '/feed.php';
			}
			$rss = fetch_feed( $feeds_url );
			if ( ! is_wp_error( $rss ) ) {
				$rss_items = $rss->get_items( 0, $show_total_content );
				return $rss_items;
			}
			return;
		}

		/**
		 * Full html show.
		 *
		 * @param array $upload_data get all layout options.
		 * @param array $shortcode_data get all meta options.
		 * @param  mixed $post_id post id.
		 * @param  mixed $main_section_title section title of the post.
		 * @param  mixed $is_preview preview mode or not.
		 * @return void
		 */
		public static function sp_wpcp_html_show( $upload_data, $shortcode_data, $post_id, $main_section_title, $is_preview = false ) {
			// Video Carousel.
			if ( empty( $upload_data ) ) {
				return;
			}

			$carousel_type = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : 'image-carousel';
			// Carousel Section Settings.
			$scheduler = isset( $shortcode_data['wpcp_scheduler'] ) ? $shortcode_data['wpcp_scheduler'] : false;
			if ( $scheduler ) {
				$date_picker  = isset( $shortcode_data['wpcp_date_picker'] ) ? $shortcode_data['wpcp_date_picker'] : '';
				$start_date   = isset( $date_picker['from'] ) ? $date_picker ['from'] : '';
				$end_date     = isset( $date_picker['to'] ) ? $date_picker['to'] : '';
				$current_date = date_i18n( 'Y/m/d H:i' );
				$end_date     = gmdate( 'YmdHi', strtotime( $end_date ) );
				$current_date = gmdate( 'YmdHi', strtotime( $current_date ) );
				$start_date   = gmdate( 'YmdHi', strtotime( $start_date ) );

				$showing_date = $end_date - $start_date;
				if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
					if ( $start_date <= $current_date && $end_date >= $current_date ) {
						echo '<span style="display:none;">' . esc_html__( 'This is scheduling shortcode', 'wp-carousel-pro' ) . '</span>';
					} else {
						return false;
					}
				}
			}
			// Password protection.
			if ( self::post_password_required( $post_id ) ) {
				$password_nonce = wp_create_nonce( 'wpcp_password_nonce' );
				wp_enqueue_script( 'wpcp-password' );
				return '<div id="wpcp-password-' . esc_attr( $post_id ) . '" class="wpcp-password-area"><p>' . __( 'This carousel is password protected. To view it please enter your password below:', 'wp-carousel-pro' ) . '</p> <p><label for="wpcp_password">Password: <input name="wpcp_password" class="wpcp_password" type="password" size="20" data-url="' . admin_url( 'admin-ajax.php' ) . '" data-id="' . esc_attr( $post_id ) . '" data-nonce="' . esc_attr( $password_nonce ) . '"  /></label> <input class="submit_wpcp_password" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'wp-carousel-pro' ) . '" /></p></div>';
			}
			$preloader          = isset( $shortcode_data['wpcp_preloader'] ) ? $shortcode_data['wpcp_preloader'] : true;
			$wpcp_layout        = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : 'carousel';
			$wpcp_slider_effect = isset( $shortcode_data['wpcp_slider_style'] ) ? $shortcode_data['wpcp_slider_style'] : 'normal';
			$preloader_image    = apply_filters( 'wpcp_preloader_image', WPCAROUSEL_URL . 'Frontend/css/spinner.svg' );
			$section_title      = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : true;
			$wpcp_screen_sizes  = wpcp_get_option( 'wpcp_responsive_screen_setting' );
			$desktop_size       = isset( $wpcp_screen_sizes['desktop'] ) && ! empty( $wpcp_screen_sizes['desktop'] ) ? $wpcp_screen_sizes['desktop'] : '1200';
			$laptop_size        = isset( $wpcp_screen_sizes['laptop'] ) && ! empty( $wpcp_screen_sizes['laptop'] ) ? $wpcp_screen_sizes['laptop'] : '980';
			$tablet_size        = isset( $wpcp_screen_sizes['tablet'] ) && ! empty( $wpcp_screen_sizes['tablet'] ) ? $wpcp_screen_sizes['tablet'] : '736';
			$mobile_size        = isset( $wpcp_screen_sizes['mobile'] ) && ! empty( $wpcp_screen_sizes['mobile'] ) ? $wpcp_screen_sizes['mobile'] : '480';
			// Carousel Column Number Settings.
			$column_number     = isset( $shortcode_data['wpcp_number_of_columns'] ) ? $shortcode_data['wpcp_number_of_columns'] : '';
			$column_lg_desktop = isset( $column_number['lg_desktop'] ) && ! empty( $column_number['lg_desktop'] ) ? $column_number['lg_desktop'] : '5';
			$column_desktop    = isset( $column_number['desktop'] ) && ! empty( $column_number['desktop'] ) ? $column_number['desktop'] : '4';
			$column_laptop     = isset( $column_number['laptop'] ) && ! empty( $column_number['laptop'] ) ? $column_number['laptop'] : '3';
			$column_tablet     = isset( $column_number['tablet'] ) && ! empty( $column_number['tablet'] ) ? $column_number['tablet'] : '2';
			$column_mobile     = isset( $column_number['mobile'] ) && ! empty( $column_number['mobile'] ) ? $column_number['mobile'] : '1';
			$carousel_classes  = ' ';
			// Slide margin.
			$slide_margin            = isset( $shortcode_data['wpcp_slide_margin']['all'] ) && is_numeric( $shortcode_data['wpcp_slide_margin']['all'] ) ? $shortcode_data['wpcp_slide_margin']['all'] : '20';
			$slide_margin            = isset( $shortcode_data['wpcp_slide_margin']['top'] ) && is_numeric( $shortcode_data['wpcp_slide_margin']['top'] ) ? $shortcode_data['wpcp_slide_margin']['top'] : $slide_margin;
			$slide_margin_horizontal = isset( $shortcode_data['wpcp_slide_margin']['right'] ) && is_numeric( $shortcode_data['wpcp_slide_margin']['right'] ) ? $shortcode_data['wpcp_slide_margin']['right'] : $slide_margin;
			$data_carousel_mode      = '';
			$data_slider_effect      = '';
			if ( self::is_carousel( $wpcp_layout ) ) {
				// Responsive screen sizes.
				$carousel_mode        = isset( $shortcode_data['wpcp_carousel_mode'] ) ? $shortcode_data['wpcp_carousel_mode'] : 'standard';
				$carousel_orientation = isset( $shortcode_data['wpcp_carousel_orientation'] ) ? $shortcode_data['wpcp_carousel_orientation'] : 'horizontal';
				$vertical             = ( 'vertical' === $carousel_orientation ) ? 'true' : 'false';
				// Center Mode.
				$center_mode            = 'center' === $carousel_mode ? 'true' : 'false';
				$center_padding         = '0';
				$center_padding_desktop = '0';
				$center_padding_laptop  = '0';
				$center_padding_tablet  = '0';
				$center_padding_mobile  = '0';
				if ( 'true' === $center_mode ) {
					$center_mode_padding    = isset( $shortcode_data['wpcp_image_center_mode_padding'] ) ? $shortcode_data['wpcp_image_center_mode_padding'] : '';
					$center_padding         = isset( $center_mode_padding['lg_desktop'] ) ? $center_mode_padding['lg_desktop'] : '100';
					$center_padding_desktop = isset( $center_mode_padding['desktop'] ) ? $center_mode_padding['desktop'] : '100';
					$center_padding_laptop  = isset( $center_mode_padding['laptop'] ) ? $center_mode_padding['laptop'] : '70';
					$center_padding_tablet  = isset( $center_mode_padding['tablet'] ) ? $center_mode_padding['tablet'] : '50';
					$center_padding_mobile  = isset( $center_mode_padding['mobile'] ) ? $center_mode_padding['mobile'] : '40';
				}
				// Ticker Mode.
				$slide_width = isset( $shortcode_data['wpcp_slide_width']['all'] ) && ! empty( $shortcode_data['wpcp_slide_width']['all'] ) ? $shortcode_data['wpcp_slide_width']['all'] : '250';
				// Carousel Row.
				$carousel_row = ! empty( $shortcode_data['wpcp_carousel_row'] ) ? $shortcode_data['wpcp_carousel_row'] : array(
					'lg_desktop' => '2',
					'desktop'    => '2',
					'laptop'     => '2',
					'tablet'     => '2',
					'mobile'     => '2',
				);
				if ( 'multi-row' === $carousel_mode ) {
					$row_lg_desktop = ! empty( $carousel_row['lg_desktop'] ) ? $carousel_row['lg_desktop'] : 2;
					$row_desktop    = ! empty( $carousel_row['desktop'] ) ? $carousel_row['desktop'] : 2;
					$row_laptop     = ! empty( $carousel_row['laptop'] ) ? $carousel_row['laptop'] : 2;
					$row_tablet     = ! empty( $carousel_row['tablet'] ) ? $carousel_row['tablet'] : 2;
					$row_mobile     = ! empty( $carousel_row['mobile'] ) ? $carousel_row['mobile'] : 2;
				} else {
					$row_lg_desktop = 1;
					$row_desktop    = 1;
					$row_laptop     = 1;
					$row_tablet     = 1;
					$row_mobile     = 1;
				}
				$data_carousel_mode = $carousel_mode;
				// Ticker Carousel.
				$column_number_ticker = isset( $shortcode_data['wpcp_number_of_columns_ticker'] ) ? $shortcode_data['wpcp_number_of_columns_ticker'] : '';
				$max_column           = isset( $column_number_ticker['lg_desktop'] ) ? $column_number_ticker['lg_desktop'] : '5';
				$min_column           = isset( $column_number_ticker['mobile'] ) ? $column_number_ticker['mobile'] : '2';

				// Carousel Settings.
				// Carousel Normal Settings.
				$is_auto_play = isset( $shortcode_data['wpcp_carousel_auto_play'] ) ? $shortcode_data['wpcp_carousel_auto_play'] : true;
				$auto_play    = $is_auto_play ? 'true' : 'false';
				// Autoplay Speed.
				$autoplay_speed_old = isset( $shortcode_data['carousel_auto_play_speed']['all'] ) && ! empty( $shortcode_data['carousel_auto_play_speed']['all'] ) ? $shortcode_data['carousel_auto_play_speed']['all'] : '3000';
				$autoplay_speed     = isset( $shortcode_data['carousel_auto_play_speed'] ) && is_string( $shortcode_data['carousel_auto_play_speed'] ) ? $shortcode_data['carousel_auto_play_speed'] : $autoplay_speed_old;
				// Carousel Speed.
				$old_speed = isset( $shortcode_data['standard_carousel_scroll_speed']['all'] ) && ! empty( $shortcode_data['standard_carousel_scroll_speed']['all'] ) ? $shortcode_data['standard_carousel_scroll_speed']['all'] : '600';
				$speed     = isset( $shortcode_data['standard_carousel_scroll_speed'] ) && is_string( $shortcode_data['standard_carousel_scroll_speed'] ) ? $shortcode_data['standard_carousel_scroll_speed'] : $old_speed;

				$ticker_speed = isset( $shortcode_data['ticker_carousel_scroll_speed']['all'] ) && ! empty( $shortcode_data['ticker_carousel_scroll_speed']['all'] ) ? $shortcode_data['ticker_carousel_scroll_speed']['all'] : '8000';

				// Slide to scroll.
				$slide_to_scroll_lg_desktop = isset( $shortcode_data['slides_to_scroll']['lg_desktop'] ) ? $shortcode_data['slides_to_scroll']['lg_desktop'] : '1';
				$slide_to_scroll_desktop    = isset( $shortcode_data['slides_to_scroll']['desktop'] ) ? $shortcode_data['slides_to_scroll']['desktop'] : '1';
				$slide_to_scroll_laptop     = isset( $shortcode_data['slides_to_scroll']['laptop'] ) ? $shortcode_data['slides_to_scroll']['laptop'] : '1';
				$slide_to_scroll_tablet     = isset( $shortcode_data['slides_to_scroll']['tablet'] ) ? $shortcode_data['slides_to_scroll']['tablet'] : '1';
				$slide_to_scroll_mobile     = isset( $shortcode_data['slides_to_scroll']['mobile'] ) ? $shortcode_data['slides_to_scroll']['mobile'] : '1';

				$is_infinite        = isset( $shortcode_data['carousel_infinite'] ) ? $shortcode_data['carousel_infinite'] : '';
				$infinite           = $is_infinite ? 'true' : 'false';
				$is_pause_on_hover  = isset( $shortcode_data['carousel_pause_on_hover'] ) ? $shortcode_data['carousel_pause_on_hover'] : '';
				$pause_on_hover     = $is_pause_on_hover ? 'true' : 'false';
				$carousel_direction = isset( $shortcode_data['wpcp_carousel_direction'] ) ? $shortcode_data['wpcp_carousel_direction'] : '';
				$slider_animation   = isset( $shortcode_data['wpcp_slider_animation'] ) && 'standard' === $carousel_mode ? $shortcode_data['wpcp_slider_animation'] : 'slider';
				// Navigation settings.
				$arrow_position        = isset( $shortcode_data['wpcp_carousel_nav_position'] ) ? $shortcode_data['wpcp_carousel_nav_position'] : 'vertical_outer';
				$wpcp_visible_on_hover = isset( $shortcode_data['wpcp_visible_on_hover'] ) ? $shortcode_data['wpcp_visible_on_hover'] : false;
				$wpcp_arrows           = isset( $shortcode_data['wpcp_carousel_navigation']['wpcp_navigation'] ) ? $shortcode_data['wpcp_carousel_navigation']['wpcp_navigation'] : true;
				$wpcp_hide_on_mobile   = isset( $shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] ) ? $shortcode_data['wpcp_carousel_navigation']['wpcp_hide_on_mobile'] : '';
				$shaders_effect        = isset( $shortcode_data['shaders_effect'] ) ? $shortcode_data['shaders_effect'] : 'random';
				$arrows                = 'false';
				$arrows_mobile         = 'false';
				if ( $wpcp_arrows ) {
					$arrows        = 'true';
					$arrows_mobile = 'false';
				}
				if ( $wpcp_hide_on_mobile ) {
					$arrows        = 'true';
					$arrows_mobile = 'true';
				}

				$nav_icons = isset( $shortcode_data['navigation_icons'] ) ? $shortcode_data['navigation_icons'] : 'angle';

				// Pagination settings.
				$wpcp_dots                      = isset( $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination'] ) ? $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination'] : true;
				$wpcp_pagination_hide_on_mobile = isset( $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] ) ? $shortcode_data['wpcp_carousel_pagination']['wpcp_pagination_hide_on_mobile'] : '';
				$dots                           = 'false';
				$dots_mobile                    = 'false';
				if ( $wpcp_dots ) {
					$dots        = 'true';
					$dots_mobile = 'false';
				}
				if ( $wpcp_pagination_hide_on_mobile ) {
					$dots        = 'true';
					$dots_mobile = 'true';
				}
				$carousel_pagination_type = isset( $shortcode_data['wpcp_carousel_pagination_type'] ) ? $shortcode_data['wpcp_carousel_pagination_type'] : 'dots';

				// Miscellaneous Settings.
				$is_adaptive_height           = isset( $shortcode_data['wpcp_adaptive_height'] ) ? $shortcode_data['wpcp_adaptive_height'] : true;
				$adaptive_height              = $is_adaptive_height ? 'true' : 'false';
				$is_tab_and_key_accessibility = isset( $shortcode_data['wpcp_accessibility'] ) ? $shortcode_data['wpcp_accessibility'] : true;
				$accessibility                = $is_tab_and_key_accessibility ? 'true' : 'false';
				$is_swipe                     = isset( $shortcode_data['slider_swipe'] ) ? $shortcode_data['slider_swipe'] : true;
				$swipe                        = $is_swipe ? 'true' : 'false';
				$is_draggable                 = isset( $shortcode_data['slider_draggable'] ) ? $shortcode_data['slider_draggable'] : true;
				$draggable                    = $is_draggable ? 'true' : 'false';
				$is_swipetoslide              = isset( $shortcode_data['carousel_swipetoslide'] ) ? $shortcode_data['carousel_swipetoslide'] : true;
				$swipetoslide                 = $is_swipetoslide ? 'true' : 'false';
				$free_mode                    = isset( $shortcode_data['free_mode'] ) && $shortcode_data['free_mode'] ? 'true' : 'false';
				$is_variable_width            = isset( $shortcode_data['_variable_width'] ) ? $shortcode_data['_variable_width'] : '';
				$variable_width               = 'false';
				if ( 'horizontal' === $carousel_orientation ) {
					$variable_width = $is_variable_width ? 'true' : 'false';
				}
				// Vertical carousel always rtl mode.
				if ( 'vertical' === $carousel_orientation && 'thumbnails-slider' !== $wpcp_layout ) {
					$carousel_direction = 'rtl';
					$slider_animation   = 'false';
					$variable_width     = 'false';
				}
				if ( 'vertical' === $carousel_orientation ) {
					$slider_animation = 'false';
				}
				$the_rtl             = '';
				$data_shaders_effect = '';
				if ( 'ticker' === $carousel_mode && 'thumbnails-slider' !== $wpcp_layout ) {
					$carousel_direction = isset( $shortcode_data['wpcp_carousel_direction'] ) ? $shortcode_data['wpcp_carousel_direction'] : '';
					$auto_direction     = 'rtl' === $carousel_direction ? 'next' : 'prev';
					$ticker_drag        = true === apply_filters( 'sp_wpcp_enable_mouse_drag_in_ticker', false ) ? 'true' : 'false';
					if ( wpcp_get_option( 'wpcp_bx_js', true ) ) {
						wp_enqueue_script( 'wpcp-bx-slider' );
					}
					wp_enqueue_script( 'wpcp-bx-slider-config' );
					$carousel_classes .= ' wpcp-ticker';
					$wpcp_bx_config    = ' data-mode="' . $carousel_orientation . '" data-max-slides="' . $max_column . '" data-min-slides="' . $min_column . '" data-hover-pause="' . $pause_on_hover . '" data-speed="' . $ticker_speed . '" data-slide-width="' . $slide_width . '" data-variable-width="' . $variable_width . '" data-slide-margin="' . $slide_margin . '" data-direction="' . $auto_direction . '" data-ticker-drag="' . $ticker_drag . '"';
				} else {
					$the_rtl = ( 'ltr' === $carousel_direction ) ? ' dir="rtl"' : ' dir="ltr"';
					$rtl     = ( 'ltr' === $carousel_direction ) ? 'true' : 'false';
					if ( wpcp_get_option( 'wpcp_swiper_js', true ) ) {
						wp_enqueue_script( 'wpcp-swiper' );
					}
					if ( 'thumbnails-slider' === $wpcp_layout ) {
						$carousel_classes .= ' wpcp-thumbnail-slider';
					} else {
						$carousel_classes .= ' wpcp-standard';
					}

					// Need lazyload var.
					$lazy_load_image = isset( $shortcode_data['wpcp_image_lazy_load'] ) ? $shortcode_data['wpcp_image_lazy_load'] : 'false';
					$lazy_load_image = ( '1' === $lazy_load_image || 'ondemand' === $lazy_load_image ) && 'true' !== $variable_width ? 'ondemand' : 'false';
					// Navigation arrow classes.
					if ( $wpcp_arrows && 'ticker' !== $carousel_mode ) {
						$visible_on_hover = $wpcp_visible_on_hover ? ' nav-vertical-on-hover' : '';
						switch ( $arrow_position ) {
							case 'top_right':
								$carousel_classes .= ' nav-top-right';
								break;
							case 'top_center':
								$carousel_classes .= ' nav-top-center';
								break;
							case 'top_left':
								$carousel_classes .= ' nav-top-left';
								break;
							case 'bottom_left':
								$carousel_classes .= ' nav-bottom-left';
								break;
							case 'bottom_center':
								$carousel_classes .= ' nav-bottom-center';
								break;
							case 'bottom_right':
								$carousel_classes .= ' nav-bottom-right';
								break;
							case 'vertical_outer':
								$carousel_classes .= ' nav-vertical-center ' . $visible_on_hover . '';
								break;
							case 'vertical_center_inner':
								$carousel_classes .= ' nav-vertical-center-inner ' . $visible_on_hover . '';
								break;
							case 'vertical_center':
								$carousel_classes .= ' nav-vertically-inner-and-outer ' . $visible_on_hover . '';
								break;
						}
					}
					$thumbnail_slider_orientation = isset( $shortcode_data['thumbnails_orientation'] ) ? $shortcode_data['thumbnails_orientation'] : 'horizontal';
					$thumbnail_position           = isset( $shortcode_data['wpcp_thumbnail_position'] ) ? $shortcode_data['wpcp_thumbnail_position'] : 'bottom';
					$_position                    = 'horizontal';
					if ( 'left' === $thumbnail_position || 'right' === $thumbnail_position ) {
						$_position = 'vertical';
					}
					if ( 'slider' === $wpcp_layout ) {
						$slider_animation    = $wpcp_slider_effect;
						$data_slider_effect  = $wpcp_slider_effect;
						$data_shaders_effect = $shaders_effect;
						$vertical            = 'false';
						$center_mode         = 'false';
						if ( 'kenburn' === $slider_animation ) {
								$carousel_classes .= ' wpcp-kenburn';
								$slider_animation  = 'fade';
						}
						if ( 'shaders' === $wpcp_slider_effect ) {
							wp_enqueue_script( 'wpcp-swiper-gl' );
						}
						// Setting column and row values for 'slider' layout.
						$column_tablet              = 1;
						$column_desktop             = 1;
						$column_mobile              = 1;
						$column_laptop              = 1;
						$column_lg_desktop          = 1;
						$row_desktop                = 1;
						$row_tablet                 = 1;
						$row_mobile                 = 1;
						$row_laptop                 = 1;
						$row_lg_desktop             = 1;
						$slide_to_scroll_lg_desktop = 1;
						$slide_to_scroll_mobile     = 1;
						$slide_to_scroll_tablet     = 1;
						$slide_to_scroll_desktop    = 1;
						$slide_to_scroll_laptop     = 1;
					} else {
						// Effect for carousel.
						$data_slider_effect = $carousel_mode;
						if ( 'standard' === $carousel_mode ) {
							$data_slider_effect = $slider_animation;
						}
						if ( 'fade' === $slider_animation || 'cube' === $slider_animation || 'flip' === $slider_animation || 'kenburn' === $slider_animation ) {
							$carousel_classes .= ' wpcp-advance-effect';
							if ( 'kenburn' === $slider_animation ) {
								$carousel_classes .= ' wpcp-kenburn';
								$slider_animation  = 'fade';
							}
						}
					}
					if ( 'true' === $center_mode ) {
						$carousel_classes .= ' wpcp-center';
					}
					$wpcp_swiper_options = 'data-swiper=\'{"pagination_type": "' . $carousel_pagination_type . '","vertical":' . $vertical . ',"orientation":"' . $_position . '","slider_orientation": "' . $thumbnail_slider_orientation . '","accessibility":true, "centerMode":' . $center_mode . ', "centerPadding":{"lg_desktop":"' . $center_padding . '", "desktop":"' . $center_padding_desktop . '", "laptop":"' . $center_padding_laptop . '", "tablet":"' . $center_padding_tablet . '", "mobile":"' . $center_padding_mobile . '"}, "swipeToSlide":' . $swipetoslide . ', "adaptiveHeight":' . $adaptive_height . ', "carousel_accessibility":' . $accessibility . ', "arrows":' . $arrows . ', "autoplay":' . $auto_play . ', "autoplaySpeed":' . $autoplay_speed . ', "spaceBetween":' . $slide_margin . ', "dots":' . $dots . ', "infinite":' . $infinite . ', "speed":' . $speed . ', "pauseOnHover":' . $pause_on_hover . ', "slidesToScroll":{"lg_desktop":' . $slide_to_scroll_lg_desktop . ', "desktop":' . $slide_to_scroll_desktop . ', "laptop":' . $slide_to_scroll_laptop . ', "tablet":' . $slide_to_scroll_tablet . ', "mobile":' . $slide_to_scroll_mobile . '}, "slidesToShow":{"lg_desktop":' . $column_lg_desktop . ', "desktop":' . $column_desktop . ', "laptop":' . $column_laptop . ', "tablet":' . $column_tablet . ', "mobile":' . $column_mobile . '}, "rows":{"lg_desktop":' . $row_lg_desktop . ', "desktop":' . $row_desktop . ', "laptop":' . $row_laptop . ', "tablet":' . $row_tablet . ', "mobile":' . $row_mobile . '}, "responsive":{"desktop":' . $desktop_size . ', "laptop": ' . $laptop_size . ', "tablet": ' . $tablet_size . ', "mobile": ' . $mobile_size . '}, "rtl":' . $rtl . ', "variableWidth":' . $variable_width . ', "effect":"' . $slider_animation . '", "lazyLoad": "' . $lazy_load_image . '", "swipe": ' . $swipe . ', "draggable": ' . $draggable . ', "freeMode":' . $free_mode . ' }\' data-arrowtype="' . $nav_icons . '"' . $the_rtl . '';
				}
				$carousel_attr  = ( 'ticker' === $carousel_mode && 'thumbnails-slider' !== $wpcp_layout ? $wpcp_bx_config : $wpcp_swiper_options ) . $the_rtl;
				$carousel_types = 'data-carousel_type="' . esc_attr( $carousel_type ) . '"';
			}

			$wpcp_content_style    = isset( $shortcode_data['wpcp_content_style'] ) ? $shortcode_data['wpcp_content_style'] : 'default';
			$wpcp_overlay_position = isset( $shortcode_data['wpcp_overlay_position'] ) ? $shortcode_data['wpcp_overlay_position'] : 'full_covered';
			$wpcp_caption_full     = isset( $shortcode_data['wpcp_caption_full'] ) ? $shortcode_data['wpcp_caption_full'] : 'bottom';
			$wpcp_caption_partial  = isset( $shortcode_data['wpcp_caption_partial'] ) ? $shortcode_data['wpcp_caption_partial'] : 'bottom_left';
			$wpcp_caption_diagonal = isset( $shortcode_data['wpcp_caption_diagonal'] ) ? $shortcode_data['wpcp_caption_diagonal'] : 'bottom_left';
			$wpcp_content_box      = isset( $shortcode_data['wpcp_content_box'] ) ? $shortcode_data['wpcp_content_box'] : 'bottom';
			$wpcp_post_detail      = isset( $shortcode_data['wpcp_post_detail_position'] ) ? $shortcode_data['wpcp_post_detail_position'] : '';

			$wpcp_overlay_visibility = isset( $shortcode_data['wpcp_overlay_visibility'] ) ? $shortcode_data['wpcp_overlay_visibility'] : '';
			$light_box               = isset( $shortcode_data['_image_light_box'] ) ? $shortcode_data['_image_light_box'] : false;
			$image_link_show         = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_show'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_show'] : 'l_box';
			if ( ( 'product-carousel' === $carousel_type && $light_box ) || 'video-carousel' === $carousel_type || ( 'image-carousel' === $carousel_type && 'l_box' === $image_link_show ) ) {
				if ( wpcp_get_option( 'wpcp_fancybox_js', true ) ) {
					wp_enqueue_script( 'wpcp-fancybox-popup' );
					wp_enqueue_script( 'wpcp-fancybox-config' );
				}
			}
			wp_enqueue_script( 'wpcp-jGallery' );

			// Carousel Classes.
			$carousel_classes .= ' wpcp-carousel-section sp-wpcp-' . $post_id;
			if ( 'image-carousel' === $carousel_type ) {
				$carousel_classes .= ' wpcp-image-carousel';
			} elseif ( 'video-carousel' === $carousel_type ) {
				$carousel_classes .= ' wpcp-video-carousel';
			} elseif ( 'post-carousel' === $carousel_type ) {
				$carousel_classes .= ' wpcp-post-carousel';
			} elseif ( 'content-carousel' === $carousel_type ) {
				$carousel_classes .= ' wpcp-content-carousel';
			} elseif ( 'product-carousel' === $carousel_type ) {
				$carousel_classes .= ' wpcp-product-carousel';
			}

			// Content Style.
			if ( 'with_overlay' === $wpcp_content_style ) { // Check if the Content Style is set to 'Overlay'.
				$carousel_classes .= ' detail-with-overlay';

				switch ( $wpcp_overlay_position ) {
					case 'left':
						$carousel_classes .= ' overlay-on-left';
						break;
					case 'right':
						$carousel_classes .= ' overlay-on-right';
						break;
				}
			} elseif ( 'caption_full' === $wpcp_content_style ) { // Check if the Content Style is set to 'Caption Full'.
				$carousel_classes .= ' detail-with-overlay overlay-lower';

				switch ( $wpcp_caption_full ) {
					case 'center':
						$carousel_classes .= ' overlay-on-middle';
						break;
					case 'top':
						$carousel_classes .= ' overlay-on-top';
						break;
				}
			} elseif ( 'caption_partial' === $wpcp_content_style ) { // Check if the Content Style is set to 'Caption Partial'.
				$carousel_classes .= ' detail-with-overlay caption-on-bottom-left';

				switch ( $wpcp_caption_partial ) {
					case 'top_left':
						$carousel_classes .= ' caption-on-top-left';
						break;
					case 'bottom_right':
						$carousel_classes .= ' caption-on-bottom-right';
						break;
					case 'top_right':
						$carousel_classes .= ' caption-on-top-right';
						break;
				}
			} elseif ( 'content_diagonal' === $wpcp_content_style ) { // Check if the Content Style is set to 'Diagonal'.
				$carousel_classes .= ' detail-with-overlay overlay-curved';

				switch ( $wpcp_caption_diagonal ) {
					case 'top_left':
						$carousel_classes .= ' diagonal-on-top-left';
						break;
					case 'bottom_right':
						$carousel_classes .= ' diagonal-on-bottom-right';
						break;
					case 'top_right':
						$carousel_classes .= ' diagonal-on-top-right';
						break;
				}
			} elseif ( 'content_box' === $wpcp_content_style ) { // Check if the Content Style is set to 'Diagonal'.
				$carousel_classes .= ' detail-with-overlay content-box';

				switch ( $wpcp_content_box ) {
					case 'bottom':
						$carousel_classes .= ' box-on-bottom';
						break;
					case 'top':
						$carousel_classes .= ' box-on-top';
						break;
					case 'left':
						$carousel_classes .= ' box-on-left';
						break;
					case 'right':
						$carousel_classes .= ' box-on-right';
						break;
					case 'center':
						$carousel_classes .= ' box-on-center';
						break;
				}
			} elseif ( 'moving' === $wpcp_content_style ) {
				$carousel_classes .= ' detail-with-overlay caption-on-bottom-left caption-on-moving';
			}

			// Check if the Overlay Visibility is set to 'On Hover'.
			if ( 'on_hover' === $wpcp_overlay_visibility ) {
				$carousel_classes .= ' overlay-on-hover';
			}

			// Check if the Content Style is set to 'default'.
			if ( 'default' === $wpcp_content_style ) {
				switch ( $wpcp_post_detail ) {
					case 'on_right':
						$carousel_classes .= ' detail-on-right';
						break;
					case 'on_left':
						$carousel_classes .= ' detail-on-left';
						break;
					case 'top':
						$carousel_classes .= ' detail-on-top';
						break;
					default:
						$carousel_classes .= ' detail-on-bottom';
						break;
				}
				$item_same_height = isset( $shortcode_data['item_same_height'] ) ? $shortcode_data['item_same_height'] : false;
				// If same height is enabled in default content style, then add class 'wpcp_same_height' for same height of each item.
				if ( $item_same_height ) {
					$carousel_classes .= ' wpcp_same_height';
				}
			}

			// Overlay content animation.
			$wpcp_overlay_animation = isset( $shortcode_data['wpcp_overlay_animation'] ) ? $shortcode_data['wpcp_overlay_animation'] : 'none';
			$animation_class        = '';
			if ( 'none' !== $wpcp_overlay_animation && wpcp_get_option( 'wpcp_enqueue_animation_css', true ) ) {
				wp_enqueue_style( 'wpcp-animate' );
			}

			$img_protection = isset( $shortcode_data['wpcp_img_protection'] ) && ( 'image-carousel' === $carousel_type ) ? $shortcode_data['wpcp_img_protection'] : false;
			if ( $img_protection ) {
				$carousel_classes .= ' wpcp_img_protection';
			}
			$masonry_class = '';
			// Masonry class.
			if ( 'masonry' === $wpcp_layout ) {
				$masonry_class = 'wpcp-masonry';
				wp_enqueue_script( 'imagesloaded' );
				wp_enqueue_script( 'masonry' );
			}
			// Preloader classes.
			if ( $preloader ) {
				wp_enqueue_script( 'wpcp-preloader' );
				$carousel_classes .= ' wpcp-preloader';
			}

			// Lightbox meta options.
			$show_lightbox_image_counter = isset( $shortcode_data['wpcp_image_counter'] ) ? $shortcode_data['wpcp_image_counter'] : true;
			$l_box_thumb_visibility      = isset( $shortcode_data['l_box_thumb_visibility'] ) ? $shortcode_data['l_box_thumb_visibility'] : true;
			$l_box_protect_image         = isset( $shortcode_data['l_box_protect_image'] ) ? $shortcode_data['l_box_protect_image'] : false;
			$l_box_keyboard_nav          = isset( $shortcode_data['l_box_keyboard_nav'] ) ? $shortcode_data['l_box_keyboard_nav'] : true;
			$l_box_loop                  = isset( $shortcode_data['l_box_loop'] ) ? $shortcode_data['l_box_loop'] : true;
			$l_box_hover_img_on_mobile   = isset( $shortcode_data['l_box_hover_img_on_mobile'] ) ? $shortcode_data['l_box_hover_img_on_mobile'] : false;
			$l_box_autoplay              = isset( $shortcode_data['l_box_autoplay'] ) ? $shortcode_data['l_box_autoplay'] : false;
			$l_box_outside_close         = isset( $shortcode_data['l_box_outside_close'] ) && $shortcode_data['l_box_outside_close'] ? 'true' : 'false';
			$l_box_autoplay_speed        = ! empty( $shortcode_data['l_box_autoplay_speed'] ) && is_numeric( $shortcode_data['l_box_autoplay_speed'] ) ? $shortcode_data['l_box_autoplay_speed'] : 4000;
			$l_box_sliding_effect        = isset( $shortcode_data['l_box_sliding_effect'] ) ? $shortcode_data['l_box_sliding_effect'] : 'fade';
			$l_box_open_close_effect     = isset( $shortcode_data['l_box_open_close_effect'] ) ? $shortcode_data['l_box_open_close_effect'] : 'fade';
			$l_box_zoom_button           = isset( $shortcode_data['l_box_zoom_button'] ) ? $shortcode_data['l_box_zoom_button'] : 'zoom';
			$l_box_icon_position         = isset( $shortcode_data['l_box_icon_position'] ) ? $shortcode_data['l_box_icon_position'] : 'middle';
			$justified_row_height        = isset( $shortcode_data['rowHeight']['all'] ) ? $shortcode_data['rowHeight']['all'] : '250';
			$l_box_zoom_button           = $l_box_zoom_button ? 'zoom' : '';

			$l_box_thumb_visibility           = $l_box_thumb_visibility ? 'true' : 'false';
			$show_l_box_img_sharper_on_retina = isset( $shortcode_data['l_box_img_sharper_on_retina'] ) ? $shortcode_data['l_box_img_sharper_on_retina'] : false;

			$lightbox_data = 'data-infobar="' . $show_lightbox_image_counter . '" data-thumbs="' . $l_box_thumb_visibility . '" data-protect_image="' . $l_box_protect_image . '" data-autoplay="' . $l_box_autoplay . '" data-loop="' . $l_box_loop . '" data-speed="' . $l_box_autoplay_speed . '" data-sliding_effect="' . $l_box_sliding_effect . '" data-open_close="' . $l_box_open_close_effect . '" data-outside="' . $l_box_outside_close . '" data-keyboard="' . $l_box_keyboard_nav . '"  data-l_box_img_sharpe="' . $show_l_box_img_sharper_on_retina . '"';

			$lightbox_setting = ( 'l_box' === $image_link_show ) ? $lightbox_data : '';
			wp_enqueue_script( 'wpcp-carousel-config' );

			$lazy_load_image = isset( $shortcode_data['wpcp_image_lazy_load'] ) ? $shortcode_data['wpcp_image_lazy_load'] : 'false';
			$lazy_load_image = ( '1' === $lazy_load_image || 'ondemand' === $lazy_load_image ) ? 'ondemand' : 'false';

			if ( ! self::is_carousel( $wpcp_layout ) && 'false' !== $lazy_load_image ) {
				wp_enqueue_script( 'wpcp-carousel-lazy-load' );
			}

			$post_per_page        = isset( $shortcode_data['post_per_page'] ) ? (int) $shortcode_data['post_per_page'] : 15;
			$post_pagination_type = isset( $shortcode_data['wpcp_post_pagination_type'] ) ? $shortcode_data['wpcp_post_pagination_type'] : '';
			$post_query           = self::wpcp_query( $upload_data, $shortcode_data, $post_id );
			$total_page           = $post_query->max_num_pages;
			$posts_found          = $post_query->found_posts;
			// Total page calculated, Total page effected after added post per click option on load more.
			if ( 'normal' !== $post_pagination_type && 'ajax_number' !== $post_pagination_type && $total_page > 1 ) {
				$per_click         = isset( $shortcode_data['post_per_click'] ) ? (int) $shortcode_data['post_per_click'] : 10;
				$per_click         = $per_click > 0 ? $per_click : $post_per_page;
				$after_offset_post = $posts_found > $post_per_page ? $posts_found - $post_per_page : 0;
				$total_page        = ceil( ( $after_offset_post / $per_click ) + 1 );
			}

			$is_source_video_type = ( 'video-carousel' === $carousel_type ) ? true : false;

			$wpcp_paged    = 'paged' . $post_id;
			$wpcp_paged_id = isset( $_GET[ "$wpcp_paged" ] ) ? sanitize_text_field( wp_unslash( $_GET[ "$wpcp_paged" ] ) ) : 1;
			$cache_key     = 'sp_wpcp_layout' . $wpcp_paged_id . $post_id . WPCAROUSEL_VERSION;
			$cache_data    = self::wpcp_get_transient( $cache_key, $is_source_video_type );
			if ( false !== $cache_data ) {
				$html = $cache_data;
			} elseif ( 'carousel' === $wpcp_layout || 'slider' === $wpcp_layout ) {
					ob_start();
					include self::wpcp_locate_template( 'carousel.php' );
					$html = ob_get_clean();
					$html = self::minify_output( $html );
					self::wpcp_set_transient( $cache_key, $html, $is_source_video_type );
			} elseif ( 'tiles' === $wpcp_layout ) {
				ob_start();
				include self::wpcp_locate_template( 'tiles.php' );
				$html = ob_get_clean();
				$html = self::minify_output( $html );
				self::wpcp_set_transient( $cache_key, $html, $is_source_video_type );
			} elseif ( 'justified' === $wpcp_layout ) {
				ob_start();
				include self::wpcp_locate_template( 'justified.php' );
				$html = ob_get_clean();
				$html = self::minify_output( $html );
				self::wpcp_set_transient( $cache_key, $html, $is_source_video_type );
			} elseif ( 'thumbnails-slider' === $wpcp_layout ) {
				ob_start();
				include self::wpcp_locate_template( 'thumbnails-slider.php' );
				$html = ob_get_clean();
				$html = self::minify_output( $html );
				self::wpcp_set_transient( $cache_key, $html, $is_source_video_type );
			} elseif ( 'masonry' === $wpcp_layout ) {
				ob_start();
				include self::wpcp_locate_template( 'masonry.php' );
				$html = ob_get_clean();
				$html = self::minify_output( $html );
				self::wpcp_set_transient( $cache_key, $html, $is_source_video_type );
			} else {
				ob_start();
				include self::wpcp_locate_template( 'grid.php' );
				$html = ob_get_clean();
				$html = self::minify_output( $html );
				self::wpcp_set_transient( $cache_key, $html, $is_source_video_type );
			}
			return $html;
		}

		/**
		 * Ajax load more data
		 *
		 * @param  int     $post_id post id.
		 * @param  int     $wpcppage page number.
		 * @param  array   $upload_data upload option.
		 * @param  array   $shortcode_data setting options.
		 * @param  boolean $thumbnail_slider show/hide options.
		 * @return void
		 */
		public static function wpcp_ajax_more_data( $post_id, $wpcppage, $upload_data, $shortcode_data, $thumbnail_slider = false ) {
			include WPCAROUSEL_PATH . 'Frontend/partials/generator-options.php';
			$post_per_page        = isset( $shortcode_data['post_per_page'] ) ? (int) $shortcode_data['post_per_page'] : 10;
			$wpcp_pagination_type = isset( $shortcode_data['wpcp_pagination_type'] ) ? $shortcode_data['wpcp_pagination_type'] : '';
			$item_per_click       = $post_per_page;
			if ( 'ajax_number' !== $wpcp_pagination_type ) {
				$item_per_click = isset( $shortcode_data['item_per_click'] ) ? (int) $shortcode_data['item_per_click'] : $post_per_page;
			}
			// Content.
			if ( 'content-carousel' === $carousel_type ) {
				$content_sources = isset( $upload_data['carousel_content_source'] ) ? $upload_data['carousel_content_source'] : array();
				if ( empty( $content_sources ) ) {
					return;
				}
				if ( 'rand' === $image_orderby ) {
					$cash_content_data = get_transient( $post_id . 'sp_wpcp_content_data' );
					if ( $cash_content_data ) {
						$content_sources = $cash_content_data;
					} else {
						shuffle( $content_sources );
						set_transient( $post_id . 'sp_wpcp_content_data', $content_sources, 10 );
					}
				}
				if ( ! empty( $post_per_page ) && count( $content_sources ) > $post_per_page ) {
					$start_post      = $post_per_page + ( ( $wpcppage - 1 ) * $item_per_click );
					$content_sources = array_slice( $content_sources, $start_post, $item_per_click );
				}

				include WPCAROUSEL_PATH . 'Frontend/partials/content_sources.php';
			}
			// Image.
			if ( 'image-carousel' === $carousel_type ) {
				$gallery_ids            = $upload_data['wpcp_gallery'];
				$is_image_link_nofollow = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] : '';
				$wpcp_watermark         = isset( $shortcode_data['wpcp_watermark'] ) ? $shortcode_data['wpcp_watermark'] : false;

				$image_link_nofollow = $is_image_link_nofollow ? ' rel=' . esc_attr( 'nofollow' ) . '' : '';
				if ( empty( $gallery_ids ) ) {
					return;
				}
				$attachments = explode( ',', $gallery_ids );
				if ( 'rand' === $image_orderby ) {
					if ( ! empty( $post_per_page ) && count( $attachments ) > $post_per_page ) {
						$cached_data = get_transient( $post_id . 'sp_wpcp_attachments_data' );
						if ( $cached_data ) {
							$attachments = json_decode( $cached_data );
						} else {
							shuffle( $attachments );
							set_transient( $post_id . 'sp_wpcp_attachments_data', json_encode( $attachments ), 10 );
						}
					} else {
						shuffle( $attachments );
					}
				}
				if ( ! empty( $post_per_page ) && count( $attachments ) > $post_per_page ) {
					$start_post  = $post_per_page + ( ( $wpcppage - 1 ) * $item_per_click );
					$attachments = array_unique( $attachments );
					$attachments = array_slice( $attachments, $start_post, $item_per_click );
					$attachments = array_unique( $attachments );
				}
				include WPCAROUSEL_PATH . 'Frontend/partials/image_sources.php';
			}
			// Audio.
			if ( 'audio-carousel' === $carousel_type ) {
				$audio_sources = ! empty( $upload_data['carousel_audio_source'] ) ? $upload_data['carousel_audio_source'] : array();
				if ( empty( $audio_sources ) ) {
					return;
				}
				if ( 'rand' === $image_orderby ) {
					$cash_audio_data = get_transient( $post_id . 'sp_wpcp_audio_data' );
					if ( $cash_audio_data ) {
						$audio_sources = $cash_audio_data;
					} else {
						shuffle( $audio_sources );
						set_transient( $post_id . 'sp_wpcp_audio_data', $audio_sources, 10 );
					}
				}
				if ( ! empty( $post_per_page ) && count( $audio_sources ) > $post_per_page ) {
					$start_post    = $post_per_page + ( ( $wpcppage - 1 ) * $item_per_click );
					$audio_sources = array_slice( $audio_sources, $start_post, $item_per_click );
				}
				include WPCAROUSEL_PATH . 'Frontend/partials/audio_sources.php';
			}
			// Video.
			if ( 'video-carousel' === $carousel_type ) {
				$video_sources = $upload_data['carousel_video_source'];
				if ( empty( $video_sources ) ) {
					return;
				}
				$lightbox_data = 'data-thumbs="true" data-outside="1" data-loop=1 data-keyboard=1';
				$sp_urls       = self::get_video_thumb_url( $video_sources );
				if ( 'rand' === $image_orderby ) {
					$cash_video_data = get_transient( $post_id . 'sp_wpcp_video_data' );
					if ( $cash_video_data ) {
						$sp_urls = $cash_video_data;
					} else {
						shuffle( $sp_urls );
						set_transient( $post_id . 'sp_wpcp_video_data', $sp_urls, 10 );
					}
				}
				if ( ! empty( $post_per_page ) && count( $sp_urls ) > $post_per_page ) {
					$start_post = $post_per_page + ( ( $wpcppage - 1 ) * $item_per_click );
					$sp_urls    = array_slice( $sp_urls, $start_post, $item_per_click );
				}
				include WPCAROUSEL_PATH . 'Frontend/partials/video_sources.php';
			}
			// Mix content.
			if ( 'mix-content' === $carousel_type ) {
				$mix_sources   = isset( $upload_data['carousel_mix_source'] ) ? $upload_data['carousel_mix_source'] : '';
				$lightbox_data = 'data-thumbs="true" data-outside="1" data-loop=1 data-keyboard=1';
				if ( ! empty( $post_per_page ) && count( $mix_sources ) > $post_per_page ) {
					$start_post  = $post_per_page + ( ( $wpcppage - 1 ) * $item_per_click );
					$mix_sources = array_slice( $mix_sources, $start_post, $item_per_click );
				}
				include WPCAROUSEL_PATH . 'Frontend/partials/mix_content_sources.php';
			}
			// External.
			if ( 'external-carousel' === $carousel_type ) {
				$show_total_content            = isset( $upload_data['wpcp_external_limit'] ) && ! empty( $upload_data['wpcp_external_limit'] ) ? $upload_data['wpcp_external_limit'] : '10';
				$wpcp_feed_content_show        = isset( $shortcode_data['wpcp_feed_content_show'] ) ? $shortcode_data['wpcp_feed_content_show'] : true;
				$show_feed_title               = isset( $shortcode_data['wpcp_feed_title'] ) ? $shortcode_data['wpcp_feed_title'] : true;
				$wpcp_feed_content_chars_limit = isset( $shortcode_data['wpcp_feed_content_chars_limit'] ) ? $shortcode_data['wpcp_feed_content_chars_limit'] : '';
				$show_feed_read_more           = isset( $shortcode_data['wpcp_feed_readmore_button_show'] ) ? $shortcode_data['wpcp_feed_readmore_button_show'] : true;
				$wpcp_feed_readmore_text       = isset( $shortcode_data['wpcp_feed_readmore_text'] ) ? $shortcode_data['wpcp_feed_readmore_text'] : 'Read More';
				$is_image_link_nofollow        = isset( $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] ) ? $shortcode_data['wpcp_click_action_type_group']['wpcp_logo_link_nofollow'] : '';
				$image_link_nofollow           = $is_image_link_nofollow ? ' rel=' . esc_attr( 'nofollow' ) . '' : '';
				$rss_items                     = self::rss_feeds( $upload_data, $show_total_content );
				if ( ! is_wp_error( $rss_items ) ) { // Checks that the object is created.
					if ( ! empty( $post_per_page ) && count( $rss_items ) > $post_per_page ) {
						$start_post = $post_per_page + ( ( $wpcppage - 1 ) * $item_per_click );
						$rss_items  = array_slice( $rss_items, $start_post, $item_per_click );
					}
					foreach ( $rss_items as $item ) :
						$title       = $item->get_title();
						$link        = $item->get_permalink();
						$description = $item->get_content();
						$description = strip_tags( $description );
						if ( ! empty( $wpcp_feed_content_chars_limit ) ) {
							$description = wp_html_excerpt( $description, $wpcp_feed_content_chars_limit ) . ' ...';
						}
						$thumb_src = '';
						if ( $enclosure = $item->get_enclosure() ) {
							$thumb_src = $enclosure->get_thumbnail();
						}
						$pubDate  = $item->get_date( 'j F Y | g:i a' );
						$category = $item->get_category();
						include self::wpcp_locate_template( 'loop/external-type/feeds.php' );
					endforeach;
				}
			}
		}

		/**
		 * Fetch highest resolution thumb from YouTube .YouTube available thumb size - maxresdefault, sddefault, mqdefault, hqdefault, and default.
		 *
		 * @param string $wpcp_video_id YouTube video id.
		 * @return mixed
		 */
		public static function fetch_highest_res( $wpcp_video_id ) {
			$resolutions      = array( 'hqdefault', 'maxresdefault', 'mqdefault', 'sddefault', 'default' );
			$http             = is_ssl() ? 'https:' : 'http:';
			$thumb_resolution = apply_filters( 'wpcp_youtube_thumb_resolution', true );
			if ( $thumb_resolution ) {
				foreach ( $resolutions as $res ) {
					$resolution = apply_filters( 'wp_carousel_youtube_thumb_size', $res );
					$thumb_url  = "$http//img.youtube.com/vi_webp/$wpcp_video_id/$resolution.webp";
					if ( ! empty( $wpcp_video_id ) && ! empty( $thumb_url ) && @getimagesize( $thumb_url ) ) {
						return $thumb_url;
					}
				}
			}
			$default_resolution = apply_filters( 'wp_carousel_youtube_default_thumb_size', 'hqdefault' );
			return "$http//img.youtube.com/vi/$wpcp_video_id/$default_resolution.jpg";
		}
		/**
		 * Get video URL and Thumbnail.
		 *
		 * @param array $_video_sources video sources.
		 * @return array
		 */
		public static function get_video_thumb_url( array $_video_sources ) {
			$vid_url = array();
			foreach ( $_video_sources as $_video_source ) {
				$video_type            = isset( $_video_source['carousel_video_source_type'] ) ? $_video_source['carousel_video_source_type'] : 'youtube';
				$wpcp_video_id         = $_video_source['carousel_video_source_id'];
				$wpcp_video_desc       = $_video_source['carousel_video_description'];
				$carousel_wistia_id    = isset( $_video_source['carousel_wistia_url'] ) && ! empty( $_video_source['carousel_wistia_url'] ) ? $_video_source['carousel_wistia_url'] : '';
				$wpcp_video_thumb_url  = '';
				$wpcp_img_click_action = '';
				$wpcp_video_url        = '';
				$video_thumb_alt_text  = '';

				switch ( $video_type ) {
					case 'youtube':
						$video_thumb_alt_text = 'youtube-video-thumbnail';
						$wpcp_video_url       = 'https://www.youtube.com/watch?v=' . $wpcp_video_id;
						$wpcp_video_thumb_url = self::fetch_highest_res( $wpcp_video_id );
						break;
					case 'twitch':
						// Get the server name and sanitize.
						$wpcp_host = ! empty( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : '';

						// Set default twitch id type.
						$twitch_id_type = isset( $_video_source['twitch_id_type'] ) ? $_video_source['twitch_id_type'] : 'video';

						// Get the relevant Twitch ID.
						$video_twitch_id  = isset( $_video_source['carousel_video_twitch_id'] ) ? $_video_source['carousel_video_twitch_id'] : '';
						$video_channel_id = isset( $_video_source['carousel_video_channel_id'] ) ? $_video_source['carousel_video_channel_id'] : '';

						// Construct the Twitch video URL based on the ID type.
						if ( 'channel' === $twitch_id_type && $video_channel_id ) {
							$wpcp_video_url = 'https://player.twitch.tv/?channel=' . $video_channel_id . '&parent=' . $wpcp_host;
						} else {
							$wpcp_video_url = 'https://player.twitch.tv/?video=' . $video_twitch_id . '&parent=' . $wpcp_host;
						}
						$video_thumb_alt_text = 'twitch-video-thumbnail';
						$wpcp_video_thumb_url = ! empty( $_video_source['carousel_video_source_thumb']['url'] ) ? $_video_source['carousel_video_source_thumb']['url'] : '';
						break;
					case 'tiktok':
						$wpcp_video_url       = 'https://www.tiktok.com/embed/v3/' . $wpcp_video_id;
						$video_thumb_alt_text = 'tiktok-video-thumbnail';
						$wpcp_video_thumb_url = ! empty( $_video_source['carousel_video_source_thumb']['url'] ) ? $_video_source['carousel_video_source_thumb']['url'] : '';
						break;
					case 'wistia':
						$video_thumb_alt_text = 'wistia-video-thumbnail';
						$wpcp_video_url       = "https://fast.wistia.net/embed/iframe/$carousel_wistia_id";
						$wpcp_video_thumb_url = isset( $_video_source['carousel_video_source_thumb']['url'] ) && ! empty( $_video_source['carousel_video_source_thumb']['url'] ) ? $_video_source['carousel_video_source_thumb']['url'] : '';
						if ( empty( $wpcp_video_thumb_url ) && ! empty( $carousel_wistia_id ) ) {
							$wistia_video_width   = apply_filters( 'wp_carousel_pro_wistia_thumb_width', '960' );
							$wistia_embed_data    = wp_remote_get( "https://fast.wistia.net/oembed?url=https://home.wistia.com/medias/$carousel_wistia_id?embedType=async&videoWidth=$wistia_video_width" );
							$wistia_data          = json_decode( wp_remote_retrieve_body( $wistia_embed_data ), true );
							$wpcp_video_thumb_url = isset( $wistia_data['thumbnail_url'] ) ? $wistia_data['thumbnail_url'] : '';
						}
						break;
					case 'vimeo':
						$video_thumb_alt_text = 'vimeo-video-thumbnail';
						$data_url             = wp_remote_get( "https://vimeo.com/api/v2/video/$wpcp_video_id.json" );
						if ( isset( $data_url ) ) {
							$thumb_data           = json_decode( wp_remote_retrieve_body( $data_url ), true );
							$wpcp_video_thumb_url = isset( $thumb_data[0]['thumbnail_large'] ) ? $thumb_data[0]['thumbnail_large'] : null;
						}
						$wpcp_video_url = 'https://vimeo.com/' . $wpcp_video_id;
						break;
					case 'dailymotion':
						$video_thumb_alt_text = 'dailymotion-video-thumbnail';
						$wpcp_video_url       = 'https://www.dailymotion.com/video/' . $wpcp_video_id;
						$wpcp_video_thumb_url = 'https://dailymotion.com/thumbnail/video/' . $wpcp_video_id;
						break;
					case 'self_hosted':
						$wpcp_video_url       = $_video_source['carousel_video_source_upload'];
						$wpcp_video_thumb_url = $_video_source['carousel_video_source_thumb'];
						$video_thumb_alt_text = $wpcp_video_thumb_url['alt'];
						break;
					default:
						$wpcp_video_thumb_url = 'https://via.placeholder.com/650x450';
				}
				$vid_url[] = array(
					'video_type'       => $video_type,
					'img_click_action' => $wpcp_img_click_action,
					'video_id'         => $wpcp_video_id,
					'video_url'        => $wpcp_video_url,
					'video_thumb_url'  => $wpcp_video_thumb_url,
					'video_thumb_alt'  => $video_thumb_alt_text,
					'video_desc'       => $wpcp_video_desc,
				);
			} // End foreach.
			return $vid_url;
		}

		/**
		 * Get video URL and Thumbnail.
		 *
		 * @param  mixed $video_type video types.
		 * @param  mixed $wpcp_video_id video ids.
		 * @param  mixed $wpcp_video_desc descriptions.
		 * @param  array $video_source_thumb thumbnail sources.
		 * @param array $video_source_upload uploaded sources.
		 * @return statement
		 */
		public static function get_mix_video_thumb_url( $video_type, $wpcp_video_id, $wpcp_video_desc = '', $video_source_thumb = '', $video_source_upload = '', $twitch_video_url = '' ) {
			$vid_url               = array();
			$wpcp_video_thumb_url  = '';
			$wpcp_img_click_action = '';
			$wpcp_video_url        = '';
			$video_thumb_alt_text  = '';
			switch ( $video_type ) {
				case 'youtube':
					$wpcp_video_url       = 'https://www.youtube.com/watch?v=' . $wpcp_video_id;
					$wpcp_video_thumb_url = self::fetch_highest_res( $wpcp_video_id );
					$video_thumb_alt_text = 'youtube-video-thumbnail';
					break;
				case 'vimeo':
					$data_url = wp_remote_get( "https://vimeo.com/api/v2/video/$wpcp_video_id.json" );
					if ( isset( $data_url ) ) {
						$thumb_data           = json_decode( wp_remote_retrieve_body( $data_url ), true );
						$wpcp_video_thumb_url = isset( $thumb_data[0]['thumbnail_large'] ) ? $thumb_data[0]['thumbnail_large'] : null;
					}
					$wpcp_video_url       = 'https://vimeo.com/' . $wpcp_video_id;
					$video_thumb_alt_text = 'vimeo-video-thumbnail';
					break;
				case 'twitch':
					$wpcp_video_thumb_url = $video_source_thumb['url'];
					$video_thumb_alt_text = 'twitch-video-thumbnail';
					$wpcp_video_url       = $twitch_video_url;
					break;
				case 'tiktok':
					$wpcp_video_url       = 'https://www.tiktok.com/embed/v3/' . $wpcp_video_id;
					$video_thumb_alt_text = 'tiktok-video-thumbnail';
					$wpcp_video_thumb_url = $video_source_thumb['url'];
					break;
				case 'wistia':
					$video_thumb_alt_text = 'wistia-video-thumbnail';
					$wpcp_video_url       = "https://fast.wistia.net/embed/iframe/$wpcp_video_id";
					$wpcp_video_thumb_url = $video_source_thumb['url'];
					if ( empty( $wpcp_video_thumb_url ) && ! empty( $wpcp_video_id ) ) {
						$wistia_video_width   = apply_filters( 'wp_carousel_pro_wistia_thumb_width', '960' );
						$wistia_embed_data    = wp_remote_get( "https://fast.wistia.net/oembed?url=https://home.wistia.com/medias/$wpcp_video_id?embedType=async&videoWidth=$wistia_video_width" );
						$wistia_data          = json_decode( wp_remote_retrieve_body( $wistia_embed_data ), true );
						$wpcp_video_thumb_url = isset( $wistia_data['thumbnail_url'] ) ? $wistia_data['thumbnail_url'] : '';
					}
					break;
				case 'dailymotion':
					$wpcp_video_url       = 'https://www.dailymotion.com/video/' . $wpcp_video_id;
					$wpcp_video_thumb_url = 'https://dailymotion.com/thumbnail/video/' . $wpcp_video_id;
					$video_thumb_alt_text = 'dailymotion-video-thumbnail';
					break;
				case 'self_hosted':
					$wpcp_video_url       = $video_source_upload;
					$wpcp_video_thumb_url = $video_source_thumb['url'];
					$video_thumb_alt_text = 'self-video-thumbnail';
					break;
				default:
					$wpcp_video_thumb_url = 'https://via.placeholder.com/650x450';
			}
			$vid_url = array(
				'video_type'       => $video_type,
				'img_click_action' => $wpcp_img_click_action,
				'video_id'         => $wpcp_video_id,
				'video_url'        => $wpcp_video_url,
				'video_thumb_url'  => $wpcp_video_thumb_url,
				'video_desc'       => $wpcp_video_desc,
				'video_alt_text'   => $video_thumb_alt_text,
			);
			return $vid_url;
		}
	}

}
