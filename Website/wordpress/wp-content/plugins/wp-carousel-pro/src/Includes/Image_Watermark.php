<?php
/**
 * Fired during plugin activation
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes
 */

namespace ShapedPlugin\WPCarouselPro\Includes;

if ( ! class_exists( 'Image_Watermark' ) ) {
	/**
	 * Image watermark.
	 */
	class Image_Watermark {

		/**
		 * Debug mode
		 *
		 * @var bool
		 */
		private $debug_mode = true;

		/**
		 * Cache folder's path where images will be saved - by default cache folder is placed in WP uploads dir and named "wpcp_watermarked".
		 *
		 * @var string
		 */
		public $cache_folder_dir;

		/**
		 * Cache folder's url where images will be saved.
		 *
		 * @var string
		 */
		public $cache_folder_url;

		/**
		 * Resulting image quality
		 *
		 * @var int
		 */
		public $quality = 95;

		/**
		 * Where to use fixed watermark Dimensionss or set it proportionally basing on Dimensionss
		 *
		 * @var bool
		 */
		public $proportional = true;
		/**
		 * Specifies proportional watermark sizes (width-height) in PERCENTAGE values related to Dimensionss
		 *
		 * @var array
		 */
		public $prop_sizes = array( 15, 15 );

		/**
		 * Watermark image's path
		 *
		 * @var string
		 */
		public $wm_img_path;

		/**
		 * Watermark position - use lt/mt/rt lm/mm/rm lb/mb/rb
		 *
		 * @var string
		 */
		public $wm_pos = 'mm';

		/**
		 * Watermark opacity (0 to 100)
		 *
		 * @var int
		 */
		public $wm_opacity = 100;

		/**
		 * Watermark margin (in pixels) from image edges
		 *
		 * @var int
		 */
		public $wm_margin = 10;

		/**
		 * Watermark margin type: px or %
		 *
		 * @var string
		 */
		public $wm_margin_type = '%';

		/**
		 * Watermark image type
		 *
		 * @var string
		 */
		private $wm_mime;

		/**
		 * Image mime type
		 *
		 * @var string
		 */
		private $img_mime;

		/**
		 * Watermark Text
		 *
		 * @var string
		 */
		private $wm_text;

		/**
		 * Watermark Text
		 *
		 * @var string
		 */
		private $wm_text_color = '#ffffff';

		/**
		 * Watermark Type
		 *
		 * @var string
		 */
		private $wm_type;

		/**
		 * Watermark Type
		 *
		 * @var string
		 */
		private $text_size = 24;
		/**
		 * Watermark Type
		 *
		 * @var string
		 */
		private $text_font = WPCAROUSEL_PATH . '/Frontend/fonts/WatermarkFont.ttf';

		/**
		 * Contents received from WP_remote_get/cURL when it comes to manage an external image
		 *
		 * @var mixed
		 */
		private $rm_img_data;

		/**
		 * Initialize class specifying watermark image. By default cache folder is placed in WP uploads dir and named "wpcp_watermarked".
		 *
		 * @param  mixed $wm_img WP attachment ID or direct path (if URL is passed, class will try to retrieve the path).
		 * @param  mixed $props an associative array containing class properties to override.
		 * @param  mixed $wm_img_title post title.
		 * @param  mixed $watermark_type water mark type.
		 * @return statement
		 */
		public function __construct( $wm_img, $props = array(), $wm_img_title = '', $watermark_type = 'logo' ) {
			$this->wm_type = $watermark_type;

			if ( empty( $wm_img ) && 'logo' === $this->wm_type ) { // Check watermark image existence.
				$this->throw_notice( 'No watermark specified', __LINE__ );
				return false;
			}

			// Retrieve watermark image's path.
			if ( 'logo' === $this->wm_type ) {
				if ( is_numeric( $wm_img ) ) { // Attach id.
					$this->wm_img_path = get_attached_file( $wm_img );
				} elseif ( $this->is_url( $wm_img ) ) { // Image URL.
					global $wpdb;
					$attachment        = $wpdb->get_col( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . " WHERE guid = '%s';", $wm_img ) );
					$this->wm_img_path = ( ! is_array( $attachment ) || ! count( $attachment ) ) ? '' : get_attached_file( $attachment[0] );
					if ( ! $this->wm_img_path && $wm_img_title ) {
						$attachment = $wpdb->get_col( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->posts . " WHERE post_title = '%s';", $wm_img_title ) );

						$this->wm_img_path = ( ! is_array( $attachment ) || ! count( $attachment ) ) ? '' : get_attached_file( $attachment[0] );
					}
				} else {
					$this->wm_img_path = ( file_exists( $wm_img ) ) ? $wm_img : '';
				}

				// Has path been setup ?
				if ( empty( $this->wm_img_path ) ) {
					$this->throw_notice( 'Watermark path not found', __LINE__ );
					return false;
				}
				// Retrieve watermark mime type .
				$this->wm_mime = $this->path_to_mime( $this->wm_img_path );
				if ( ! in_array( $this->wm_mime, array( 'image/jpeg', 'image/png', 'image/gif' ) ) ) {
					$this->throw_notice( 'Watermark image must be JPG, PNG or GIF', __LINE__ );
					return false;
				}
			} else {
				$this->wm_text = wpcp_get_option( 'wm_watermark_label', '' );
			}

			// Setup properties.
			foreach ( $props as $key => $val ) {
				$this->{$key} = $val;
			}

			// If no custom cache folder is specified - setup the default one.
			if ( ! $this->cache_folder_dir || ! $this->cache_folder_url ) {

				$wp_dirs     = wp_upload_dir();
				$folder_name = 'wpcp_watermarked';

				$this->cache_folder_dir = trailingslashit( $wp_dirs['basedir'] ) . $folder_name;
				$this->cache_folder_url = trailingslashit( $wp_dirs['baseurl'] ) . $folder_name;
			}

			// Check cache folder existence.
			if ( ! $this->setup_cache_dir() ) {
				return false;
			}
		}

		/**
		 * Create cache folder and check its existence + its readability
		 *
		 * @return bool
		 */
		private function setup_cache_dir() {
			if ( ! wp_mkdir_p( $this->cache_folder_dir ) ) {
				$this->throw_notice( 'Cache path (' . $this->cache_folder_dir . ') does not exist or folder has wrong permissions', __LINE__ );
				return false;
			}
			return true;
		}

		/**
		 *
		 * CREATE WATERMARKED IMAGE
		 *
		 * @param string $img_src could be a WP attachment ID or image path or image URL (even external URL).
		 * @return (array/bool)
		 */
		public function mark_it( $img_src ) {
			// if is numeric - retrieve image's path.
			if ( is_numeric( $img_src ) ) {
				$img_src = get_attached_file( $img_src );
			}
			if ( empty( $img_src ) ) {
				$this->throw_notice( 'Image not found', __LINE__ );
				return false;
			}

			// If watermarked version already exists in cache folder? just returns the final array.
			$extless_path = $this->cache_folder_dir . '/' . $this->cache_filename( $img_src, true );

			if ( @file_exists( $extless_path . '.jpg' ) ) {
				$this->img_mime = 'image/jpeg';
				$exists         = true;
			} elseif ( @file_exists( $extless_path . '.png' ) ) {
				$this->img_mime = 'image/png';
				$exists         = true;
			} elseif ( @file_exists( $extless_path . '.gif' ) ) {
				$this->img_mime = 'image/gif';
				$exists         = true;
			}

			if ( isset( $exists ) ) {
				return $this->wm_paths_arr( $img_src );
			}

			// Setup mime type and also retrieve contents for remote images.
			if ( $this->is_url( $img_src ) ) {
				$this->get_remote_contents( $img_src );
			} else {
				$this->img_mime = $this->path_to_mime( $img_src );
			}

			// Check mime.
			if ( ! in_array( $this->img_mime, array( 'image/jpeg', 'image/png', 'image/gif' ) ) ) {
				$this->throw_notice( 'Remote image (' . $img_src . ') not found or is unsupported type', __LINE__ );
				return false;
			}

			// Create wm image.
			if ( 'text' === $this->wm_type ) {
				if ( ! class_exists( 'Imagick' ) ) {
					return array( 'url' => $img_src );
				}
				$this->wpcp_add_watermark_text( $img_src );
			} else {
				$wpcp_wp = function_exists( 'imagecreatefromstring' ) ? $this->wpcp_wm( $img_src ) : '';
				( _wp_image_editor_choose() == 'WP_Image_Editor_\Imagick' ) ? $this->imagick_wm( $img_src ) : $wpcp_wp;
			}

			// If file exist returns paths - otherwise false.
			if ( @file_exists( $this->cache_folder_dir . '/' . $this->cache_filename( $img_src ) ) ) {
				return $this->wm_paths_arr( $img_src );
			} else {
				return false;
			}

			$this->rm_img_data = null; // Free up memory.
		}

		/**
		 * Add text watermark.
		 *
		 * @param  mixed $img_src could be a path or an URL.
		 * @return void
		 */
		private function wpcp_add_watermark_text( $img_src ) {
			$image = new \Imagick();
			$image->readImageBlob( $this->rm_img_data );
			$this->wm_pos    = $this->wm_text_pos();
			$this->text_font = apply_filters( 'wpcp_watermark_text_font_path', $this->text_font );
			$watermark       = new \ImagickDraw();
			// Set the font properties for the watermark text.
			$watermark->setFontSize( $this->text_size ); // watermark font size.
			// $watermark->setFontFamily( 'Arial' );// watermark font size.
			$watermark->setFont( $this->text_font );// watermark font family.
			$watermark->setFillColor( $this->wm_text_color ); // watermark text color.
			$watermark->setGravity( $this->wm_pos ); // watermark text position.
			$watermark->setFillOpacity( $this->wm_opacity ); // watermark text opacity.

			// Get dimensions of text.
			$image->annotateImage( $watermark, 14, 14, 0, $this->wm_text );

			$image->writeimage( $this->cache_folder_dir . '/' . $this->cache_filename( $img_src ) );
			// Free up memory.
			$image->destroy();
		}

		/**
		 *  Watermarking process through php imagick.
		 *
		 * @param  mixed $img_src could be a path or an URL.
		 * @return void
		 */
		private function imagick_wm( $img_src ) {
			$options = array();

			// Create image resource.
			if ( $this->is_url( $img_src ) ) {
				$image = new \Imagick();
				$image->readImageBlob( $this->rm_img_data );
			} else {
				$image = new \Imagick( $img_src );
			}

			// Create watermark resource.
			$watermark = new \Imagick( $this->wm_img_path );

			// Set wm opacity.
			$watermark->evaluateImage( \Imagick::EVALUATE_MULTIPLY, ( $this->wm_opacity / 100 ), \Imagick::CHANNEL_ALPHA );

			// Set compression quality.
			if ( $this->img_mime === 'image/jpeg' ) {
				$image->setImageCompressionQuality( $this->quality );
				$image->setImageCompression( \imagick::COMPRESSION_JPEG );
			} else {
				$image->setImageCompressionQuality( $this->quality );
			}

			// Set image output to progressive.
			$image->setImageInterlaceScheme( \Imagick::INTERLACE_PLANE );

			// Get image dimensions.
			$image_dim = $image->getImageGeometry();

			// Get watermark dimensions.
			$watermark_dim = $watermark->getImageGeometry();

			// Calculate watermark new dimensions.
			list( $width, $height ) = $this->wm_dimensions( $image_dim['width'], $image_dim['height'], $watermark_dim['width'], $watermark_dim['height'] );

			// Resize watermark.
			$watermark->resizeImage( $width, $height, \imagick::FILTER_POINT, 1 );

			// Calculate image coordinates.
			list( $dest_x, $dest_y ) = $this->wm_coordinates( $image_dim['width'], $image_dim['height'], $width, $height );

			// Combine two images together.
			$watermark->setImageVirtualPixelMethod( \Imagick::VIRTUALPIXELMETHOD_TRANSPARENT );
			$image->compositeImage( $watermark, \Imagick::COMPOSITE_OVER, $dest_x, $dest_y );

			// Save watermarked image.
			$destination = $this->cache_folder_dir . '/' . $this->cache_filename( $img_src );
			$image->writeImage( $destination );

			// Clear image memory.
			$image->clear();
			$image->destroy();
			$image = null;

			// Clear watermark memory.
			$watermark->clear();
			$watermark->destroy();
			$watermark = null;
		}

		/**
		 * Watermarking process
		 *
		 * @param  string $img_src could be a path or an URL.
		 * @return string/bool
		 */
		private function wpcp_wm( $img_src ) {
			// Create image resource.
			if ( $this->is_url( $img_src ) ) {
				$image = imagecreatefromstring( $this->rm_img_data );
			} else {
				switch ( $this->img_mime ) {
					case 'image/jpeg':
						$image = imagecreatefromjpeg( $img_src );
						break;
					case 'image/png':
						$image = imagecreatefrompng( $img_src );
						imagefilledrectangle( $image, 0, 0, imagesx( $image ), imagesy( $image ), imagecolorallocatealpha( $image, 255, 255, 255, 127 ) );
						break;
					case 'image/gif':
						$image = imagecreatefromgif( $img_src );
						break;
					default:
						$image = false;
				}
			}

			// Image resource check.
			// if ( is_resource( $image ) ) {
			// imagealphablending( $image, false );
			// imagesavealpha( $image, true );
			// } else {
			// $this->throw_notice( 'Invalid resource (' . $img_src . ')', __LINE__ );
			// return false;
			// }

			// Add watermark image to image.
			$image = $this->wpcp_add_watermark_image( $image );

			// If it's ok - save.
			if ( false !== $image ) {
				$destination = $this->cache_folder_dir . '/' . $this->cache_filename( $img_src );
				switch ( $this->img_mime ) {
					case 'image/jpeg':
					case 'image/pjpeg':
						imagejpeg( $image, $destination, $this->quality );
						break;
					case 'image/png':
						imagepng( $image, $destination, (int) round( 9 - ( 9 * $this->quality / 100 ), 0 ) );
						break;
					case 'image/gif':
						imagegif( $image, $destination );
						break;
				}
				// Clear watermark memory.
				imagedestroy( $image );
				$image = null;
			}
		}

		/**
		 * Add watermark image to resource previously created.
		 *
		 * @param resource $image Image resource.
		 * @return resource Watermarked image
		 */
		private function wpcp_add_watermark_image( $image ) {
			switch ( $this->wm_mime ) {
				case 'image/jpeg':
					$watermark = imagecreatefromjpeg( $this->wm_img_path );
					break;

				case 'image/gif':
					$watermark = imagecreatefromgif( $this->wm_img_path );
					break;

				case 'image/png':
					$watermark = imagecreatefrompng( $this->wm_img_path );
					break;

				default:
					return false;
			}

			// Get image dimensions.
			$image_width  = imagesx( $image );
			$image_height = imagesy( $image );

			// Calculate watermark new dimensions.
			list($w, $h) = $this->wm_dimensions( $image_width, $image_height, imagesx( $watermark ), imagesy( $watermark ) );

			// Watermark image has to be resized?
			if ( $w != imagesx( $watermark ) || imagesy( $watermark ) ) {
				$watermark = $this->wpcp_resize( $watermark, $w, $h );
			}

			// Calculate image coordinates.
			list($dest_x, $dest_y) = $this->wm_coordinates( $image_width, $image_height, $w, $h );

			// Combine two images together.
			$this->wpcp_imagecopymerge_alpha( $image, $watermark, $dest_x, $dest_y, 0, 0, $w, $h );

			// Set image output to progressive.
			imageinterlace( $image, true );

			return $image;
		}

		/**
		 * Wpcp_imagecopymerge_alpha
		 *
		 * @param  mixed $image images.
		 * @param  mixed $watermark water mark.
		 * @param  mixed $dst_x dst_x.
		 * @param  mixed $dst_y dst_y.
		 * @param  mixed $src_x src_x.
		 * @param  mixed $src_y src_y.
		 * @param  mixed $wm_w  wm_w .
		 * @param  mixed $wm_h wm_h.
		 * @return void
		 */
		private function wpcp_imagecopymerge_alpha( $image, $watermark, $dst_x, $dst_y, $src_x, $src_y, $wm_w, $wm_h ) {
			// Create a cut resource.
			$cut = imagecreatetruecolor( $wm_w, $wm_h );

			// Copy relevant section from background to the cut resource.
			imagecopy( $cut, $image, 0, 0, $dst_x, $dst_y, $wm_w, $wm_h );

			// Copy relevant section from watermark to the cut resource.
			imagecopy( $cut, $watermark, 0, 0, $src_x, $src_y, $wm_w, $wm_h );

			// Insert cut resource to destination image.
			imagecopymerge( $image, $cut, $dst_x, $dst_y, 0, 0, $wm_w, $wm_h, $this->wm_opacity );
		}

		/**
		 * Resize image.
		 *
		 * @param resource $image Image resource.
		 * @param int      $width Image width.
		 * @param int      $height Image height.
		 * @return resource Resized image
		 */
		private function wpcp_resize( $image, $width, $height ) {
			$new_image = imagecreatetruecolor( $width, $height );
			// Check if this image is PNG/GIF, then set if transparent.
			if ( $this->wm_mime != 'image/jpeg' ) {
				imagealphablending( $new_image, false );
				imagesavealpha( $new_image, true );
				imagefilledrectangle( $new_image, 0, 0, $width, $height, imagecolorallocatealpha( $new_image, 255, 255, 255, 127 ) );
			}

			imagecopyresampled( $new_image, $image, 0, 0, 0, 0, $width, $height, imagesx( $image ), imagesy( $image ) );

			return $new_image;
		}

		/**
		 * Calculate watermark dimensions.
		 *
		 * @param int $image_width Image width.
		 * @param int $image_height Image height.
		 * @param int $watermark_width Watermark width.
		 * @param int $watermark_height Watermark height.
		 *
		 * @return array Watermark new dimensions
		 */
		private function wm_dimensions( $image_width, $image_height, $watermark_width, $watermark_height ) {

			// Calculate margins.
			if ( $this->wm_margin_type == 'px' ) {
				$vert_margin = $horiz_margin = (int) $this->wm_margin * 2;
			} else {
				$horiz_margin = floor( ( (float) $this->wm_margin / 100 ) * $image_width ) * 2;
				$vert_margin  = floor( ( (float) $this->wm_margin / 100 ) * $image_height ) * 2;
			}

			// Proportional size.
			if ( $this->proportional ) {
				$width = floor( $image_width * ( $this->prop_sizes[0] / 100 ) );
				if ( $width + $horiz_margin > $image_width ) {
					$width = $image_width - $horiz_margin;
				}

				$height = floor( ( $watermark_height * $width ) / $watermark_width );

				$max_h = floor( $image_height * ( $this->prop_sizes[1] / 100 ) );
				if ( $max_h + $vert_margin > $image_height ) {
					$max_h = $image_height - $vert_margin;
				}

				if ( $height + $vert_margin > $max_h ) {
					$height = $max_h;
					$width  = floor( ( $watermark_width * $height ) / $watermark_height );
				}
			} else { // use original WM size and if bigger than image > downscale.
				// If is bigger than image - scale down.
				if ( $watermark_width + $horiz_margin > $image_width ) {
					$width  = $image_width - $horiz_margin;
					$height = floor( ( $watermark_height * $width ) / $watermark_width );

					if ( $height + $vert_margin > $image_height ) {
						$height = $image_height - $vert_margin;
						$width  = floor( ( $watermark_width * $height ) / $watermark_height );
					}
				} elseif ( $watermark_height + $vert_margin > $image_height ) {
					$height = $image_height - $vert_margin;
					$width  = floor( ( $watermark_width * $height ) / $watermark_height );

					if ( $width + $horiz_margin > $image_width ) {
						$width  = $image_width - $horiz_margin;
						$height = floor( ( $watermark_height * $width ) / $watermark_width );
					}
				} else {
					$width  = $watermark_width;
					$height = $watermark_height;
				}
			}

			return array( $width, $height );
		}

		/**
		 * Calculate watermark coordinates
		 *
		 * @param  int $image_width Image width.
		 * @param  int $image_height Image height.
		 * @param  int $watermark_width Watermark width.
		 * @param  int $watermark_height Watermark height.
		 * @return array Image coordinates
		 */
		private function wm_coordinates( $image_width, $image_height, $watermark_width, $watermark_height ) {

			// Calculate margins.
			if ( $this->wm_margin_type == 'px' ) {
				$vert_margin = $horiz_margin = (int) $this->wm_margin;
			} else {
				$horiz_margin = floor( ( (float) $this->wm_margin / 100 ) * $image_width );
				$vert_margin  = floor( ( (float) $this->wm_margin / 100 ) * $image_height );
			}

			switch ( $this->wm_pos ) {
				case 'lt':
					$dest_x = $horiz_margin;
					$dest_y = $vert_margin;
					break;

				case 'mt':
					$dest_x = ( $image_width / 2 ) - ( $watermark_width / 2 );
					$dest_y = $vert_margin;
					break;

				case 'rt':
					$dest_x = $image_width - $watermark_width - $horiz_margin;
					$dest_y = $vert_margin;
					break;

				case 'lm':
					$dest_x = $horiz_margin;
					$dest_y = ( $image_height / 2 ) - ( $watermark_height / 2 );
					break;

				case 'rm':
					$dest_x = $image_width - $watermark_width - $horiz_margin;
					$dest_y = ( $image_height / 2 ) - ( $watermark_height / 2 );
					break;

				case 'lb':
					$dest_x = $horiz_margin;
					$dest_y = $image_height - $watermark_height - $vert_margin;
					break;

				case 'mb':
					$dest_x = ( $image_width / 2 ) - ( $watermark_width / 2 );
					$dest_y = $image_height - $watermark_height - $vert_margin;
					break;

				case 'rb':
					$dest_x = $image_width - $watermark_width - $horiz_margin;
					$dest_y = $image_height - $watermark_height - $vert_margin;
					break;

				case 'mm':
				default:
					$dest_x = ( $image_width / 2 ) - ( $watermark_width / 2 );
					$dest_y = ( $image_height / 2 ) - ( $watermark_height / 2 );
			}

			return array( $dest_x, $dest_y );
		}

		/**
		 * Display watermark text in different positions.
		 */
		private function wm_text_pos() {

			// Find out the position of text.
			switch ( $this->wm_pos ) {
				case 'lt':
					$text_pos = \Imagick::GRAVITY_NORTHWEST;
					break;

				case 'lm':
					$text_pos = \Imagick::GRAVITY_WEST;
					break;

				case 'lb':
					$text_pos = \Imagick::GRAVITY_SOUTHWEST;
					break;

				case 'rt':
					$text_pos = \Imagick::GRAVITY_NORTHEAST;
					break;

				case 'rm':
					$text_pos = \Imagick::GRAVITY_EAST;
					break;

				case 'rb':
					$text_pos = \Imagick::GRAVITY_SOUTHEAST;
					break;

				case 'mb':
					$text_pos = \Imagick::GRAVITY_SOUTH;
					break;

				case 'mt':
					$text_pos = \Imagick::GRAVITY_NORTH;
					break;

				case 'mm':
				default:
					$text_pos = \Imagick::GRAVITY_CENTER;

			}

			return $text_pos;
		}




		/**
		 * Retrieves external images and setups $img_mime and $rm_img_data.
		 *
		 * @param  mixed $url url.
		 * @return statement
		 */
		private function get_remote_contents( $url ) {
			$data         = wp_remote_get(
				$url,
				array(
					'timeout'     => 8,
					'redirection' => 3,
				)
			);
			$followaction = ( ! ini_get( 'open_basedir' ) && ! ini_get( 'safe_mode' ) ) ? true : false;
			// nothing got - use cURL.
			if ( is_wp_error( $data ) || 200 != wp_remote_retrieve_response_code( $data ) || empty( $data['body'] ) ) {
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
				curl_setopt( $ch, CURLOPT_HEADER, 0 );
				curl_setopt( $ch, CURLOPT_USERAGENT, true );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 2 );
				curl_setopt( $ch, CURLOPT_MAXREDIRS, 3 );
				curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 8 );
				curl_setopt( $ch, CURLOPT_URL, $url );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, $followaction );

				$mime     = curl_getinfo( $ch, CURLINFO_CONTENT_TYPE );
				$contents = curl_exec( $ch );

				curl_close( $ch );
			} else {
				$mime     = $data['headers']['content-type'];
				$contents = $data['body'];
			}

			// ok - setup.
			$this->img_mime    = $mime;
			$this->rm_img_data = $contents;

			if ( empty( $contents ) ) {
				$this->throw_notice( 'cURL call returned empty value (' . $url . ')', __LINE__ );
				return false;
			} else {
				return true;
			}
		}

		/**
		 * Create cached filename
		 *
		 * @param  mixed $img_src image src.
		 * @param  mixed $extensionless extension less.
		 * @return statement
		 */
		protected function cache_filename( $img_src, $extensionless = false ) {

			if ( $extensionless ) {
				$ext = '';
			} else {
				switch ( $this->img_mime ) {
					case 'image/jpeg':
						$ext = '.jpg';
						break;
					case 'image/png':
						$ext = '.png';
						break;
					case 'image/gif':
						$ext = '.gif';
						break;
				}
			}
			if ( 'text' == $this->wm_type ) {
				$parts = array( md5( $img_src ), md5( $this->wm_text ), $this->text_size, $this->quality, $this->wm_pos, $this->wm_opacity, $this->wm_margin );
			} else {
				$wpwm_src = substr( $this->wm_img_path, -15, -4 );
				$parts    = array( md5( $img_src ), md5( $wpwm_src ), $this->quality, $this->wm_pos, $this->wm_opacity, $this->wm_margin . str_replace( '%', 'perc', $this->wm_margin_type ), (int) $this->proportional );
			}
			if ( $this->proportional ) {
				$parts[] = implode( '-', $this->prop_sizes );
			}
			return implode( '_', $parts ) . $ext;
		}

		/**
		 * Prepare the array containing path and url
		 *
		 * @param  mixed $img_src src.
		 * @return statement
		 */
		private function wm_paths_arr( $img_src ) {
			return array(
				'path' => $this->cache_folder_dir . '/' . $this->cache_filename( $img_src ),
				'url'  => $this->cache_folder_url . '/' . $this->cache_filename( $img_src ),
			);
		}

		/**
		 * Know whether a string is an URL or not
		 *
		 * @param  mixed $string string.
		 * @return url
		 */
		private function is_url( $string ) {
			return ( strpos( str_replace( 'https://', 'http://', strtolower( $string ) ), 'http://' ) !== false || filter_var( $string, FILTER_VALIDATE_URL ) ) ? true : false;
		}

		/**
		 * Path to mime-type (only necessary ones - otherwise returns false)
		 *
		 * @param  mixed $path path.
		 * @return path
		 */
		private function path_to_mime( $path ) {

			$arr = explode( '.', $path );
			$ext = strtolower( end( $arr ) );

			switch ( $ext ) {
				case 'jpg':
				case 'jpeg':
					$mime = 'image/jpeg';
					break;
				case 'png':
					$mime = 'image/png';
					break;
				case 'gif':
					$mime = 'image/gif';
					break;

				default:
					$mime = false;
			}
			return $mime;
		}


		/**
		 * Notice
		 *
		 * @param  mixed $text text.
		 * @param  mixed $file_line file line.
		 * @return void
		 */
		private function throw_notice( $text, $file_line ) {
			if ( $this->debug_mode ) {
				trigger_error( $text . ' [line ' . $file_line . '] &nbsp; ' );
			}
		}
	}
}
