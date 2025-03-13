<?php
/**
 * The admin-specific of the plugin.
 *
 * @link https://shapedplugin.com
 * @since 3.0.0
 *
 * @package WordPress_Carousel_Pro
 * @subpackage WordPress_Carousel_Pro/admin
 */

namespace ShapedPlugin\WPCarouselPro\Admin;

use ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes\SP_WPCP_Framework;
use ShapedPlugin\WPCarouselPro\Admin\views\preview\Backend_Preview;
use ShapedPlugin\WPCarouselPro\Admin\views\Media_View\Media_View;

/**
 * The class for the admin-specific functionality of the plugin.
 */
class Admin {
	/**
	 * Script and style suffix
	 *
	 * @since 3.0.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * The ID of the plugin.
	 *
	 * @since 3.0.0
	 * @access protected
	 * @var string      $plugin_name The ID of this plugin
	 */
	protected $plugin_name;

	/**
	 * The version of the plugin
	 *
	 * @since 3.0.0
	 * @access protected
	 * @var string      $version The current version fo the plugin.
	 */
	protected $version;

	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since 3.0.0
	 */
	private static $instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since 3.0.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize the class sets its properties.
	 *
	 * @since 3.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->suffix      = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$framework         = new SP_WPCP_Framework();
		$framework::init();
		new Backend_Preview();
		new Elementor_Addon();
		new Cron_Schedules();
		new Media_View();
		add_filter( 'attachment_fields_to_edit', array( $this, 'add_attachment_linking_field' ), 80, 2 );
		add_filter( 'attachment_fields_to_save', array( $this, 'update_attachment_linking_field' ), 80, 2 );
		add_action( 'wp_ajax_wpcp_clean_wm_cache', array( $this, 'wpcp_clean_wm_cache' ) );
		add_action( 'before_woocommerce_init', array( $this, 'declare_compatibility_with_woo_hpos_feature' ) );
		add_action( 'wp_ajax_wpcp_image_get_attachment_links', array( $this, 'get_attachment_links' ) );
		add_action( 'wp_ajax_wpcp_image_save_meta', array( $this, 'save_meta' ) );
		// add_action( 'wp_ajax_create_image_meta', array( $this, 'create_image_meta' ) );
	}

	/**
	 * Returns the media link (direct image URL) for the given attachment ID
	 *
	 * @since
	 */
	// public function create_image_meta() {
	// Check nonce.
	// check_admin_referer( 'wpcp_image-save-meta', 'nonce' );

	// if ( ! current_user_can( 'edit_posts' ) ) {
	// wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to edit sliders.', 'wp-carousel-pro' ) ) );
	// }

	// Get required inputs.
	// $attachment_id = isset( $_POST['attach_id'] ) ? absint( wp_unslash( $_POST['attach_id'] ) ) : false;
	// if ( ! $attachment_id ) {
	// wp_send_json_error( array( 'message' => esc_html__( 'Invalid Attachment ID.', 'wp-carousel-pro' ) ) );
	// }
	// $image_meta = $this->getting_image_metadata( $attachment_id );
	// $json       = wp_json_encode( $image_meta );
	// Return the attachment's links.
	// wp_send_json_success(
	// array(
	// 'edit_text'  => __( 'Edit Image on Media Library', 'wp-carousel-pro' ),
	// 'image_meta' => $json,
	// )
	// );
	// }
	/**
	 * Getting image metadata.
	 *
	 * @param  int $image_id id.
	 * @return array
	 */
	private function getting_image_metadata( $image_id ) {
		$image_metadata_array                          = array();
		$image_linking_meta                            = wp_get_attachment_metadata( $image_id );
		$image_linking_urls                            = isset( $image_linking_meta['image_meta'] ) ? $image_linking_meta['image_meta'] : '';
		$image_linking_url                             = ! empty( $image_linking_urls['wpcplinking'] ) ? $image_linking_urls['wpcplinking'] : '';
		$image_metadata_array['status']                = 'active';
		$image_metadata_array['id']                    = $image_id;
		$image_metadata_array['src']                   = esc_url( wp_get_attachment_url( $image_id ) );
		$image_metadata_array['height']                = $image_linking_meta['height'] ?? '';
		$image_metadata_array['width']                 = $image_linking_meta['width'] ?? '';
		$image_metadata_array['alt']                   = trim( esc_html( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) ) ?? '';
		$image_metadata_array['caption']               = trim( esc_html( get_post_field( 'post_excerpt', $image_id ) ) ) ?? '';
		$image_metadata_array['title']                 = trim( esc_html( get_post_field( 'post_title', $image_id ) ) ) ?? '';
		$image_metadata_array['description']           = trim( get_post_field( 'post_content', $image_id ) ) ?? '';
		$image_metadata_array['filename']              = trim( esc_html( get_post_field( 'post_name', $image_id ) ) ) ?? '';
		$image_metadata_array['wpcplink']              = esc_url( $image_linking_url );
		$image_metadata_array['link_target']           = get_post_meta( $image_id, 'wpcplinktarget', true );
		$image_metadata_array['crop_position']         = trim( esc_html( get_post_meta( $image_id, 'crop_position', true ) ) ) ?? 'center_center';
		$image_metadata_array['editLink']              = get_edit_post_link( $image_id, 'display' );
		$image_metadata_array['type']                  = 'image';
		$image_metadata_array['mime']                  = $image_linking_meta['sizes']['thumbnail']['mime-type'] ?? '';
		$image_metadata_array['filesizeHumanReadable'] = isset( $image_linking_meta['filesize'] ) ? round( $image_linking_meta['filesize'] / 1024 ) : '';
		if ( array_key_exists( 'sizes', $image_linking_meta ) ) {
			unset( $image_linking_meta['sizes'] );
		}
		if ( array_key_exists( 'image_meta', $image_linking_meta ) ) {
			unset( $image_linking_meta['image_meta'] );
		}
		return array_merge( $image_linking_meta, $image_metadata_array );
	}

	/**
	 * Saves the metadata for an image in a slider.
	 *
	 * @since 1.0.0
	 */
	public function save_meta() {
		// Run a security check first.
		check_ajax_referer( 'wpcp_image-save-meta', 'nonce' );

		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : null;

		if ( null === $post_id ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid Post ID.', 'wp-carousel-pro' ) ) );
		}

		if ( ! current_user_can( 'edit_posts', $post_id ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to edit sliders.', 'wp-carousel-pro' ) ) );
		}

		// Prepare variables.
		$attach_id = isset( $_POST['attach_id'] ) ? intval( wp_unslash( $_POST['attach_id'] ) ) : false;
		$meta      = isset( $_POST['meta'] ) ? wp_unslash( $_POST['meta'] ) : array(); //@codingStandardsIgnoreLine

		// Update attachment post data.
		$update_attachment_data = array(
			'ID'           => $attach_id,
			'post_title'   => isset( $meta['title'] ) ? trim( esc_html( $meta['title'] ) ) : '',
			'post_content' => isset( $meta['description'] ) ? wp_kses_post( trim( $meta['description'] ) ) : '',
			'post_excerpt' => isset( $meta['caption'] ) ? wp_kses_post( trim( $meta['caption'] ) ) : '', // Caption is stored as post excerpt.
		);
		$image_linking_url      = ! empty( $meta['wpcplink'] ) ? esc_html( $meta['wpcplink'] ) : '';
		$wpcplinktarget         = ! empty( $meta['link_target'] ) ? esc_html( $meta['link_target'] ) : '';
		$crop_position          = ! empty( $meta['crop_position'] ) ? trim( esc_html( $meta['crop_position'] ) ) : '';

		// Update attachment meta.
		$new_alt_text                              = trim( esc_html( $meta['alt'] ) );
		$default_meta                              = wp_get_attachment_metadata( $attach_id );
		$default_meta['image_meta']['wpcplinking'] = $image_linking_url;
		wp_update_attachment_metadata( $attach_id, $default_meta );
		update_post_meta( $attach_id, 'wpcplinktarget', $wpcplinktarget );
		update_post_meta( $attach_id, 'crop_position', $crop_position );
		update_post_meta( $attach_id, '_wp_attachment_image_alt', $new_alt_text );
		// Update the post.
		wp_update_post( $update_attachment_data );
		wp_send_json_success();
	}

	/**
	 * Returns the media link (direct image URL) for the given attachment ID
	 *
	 * @since
	 */
	public function get_attachment_links() {
		// Check nonce.
		check_admin_referer( 'wpcp_image-save-meta', 'nonce' );
		$capability      = apply_filters( 'sp_wp_carousel_ui_permission', 'manage_options' );
		$user_capability = current_user_can( $capability ) ? true : false;
		if ( ! $user_capability ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You are not allowed to edit sliders.', 'wp-carousel-pro' ) ) );
		}

		// Get required inputs.
		$attachment_id     = isset( $_POST['attachment_id'] ) ? absint( wp_unslash( $_POST['attachment_id'] ) ) : false;
		$default_meta      = wp_get_attachment_metadata( $attachment_id );
		$image_linking_url = isset( $default_meta['image_meta']['wpcplinking'] ) ? $default_meta['image_meta']['wpcplinking'] : '';
		// Return the attachment's links.
		wp_send_json_success(
			array(
				'media_link'      => wp_get_attachment_url( $attachment_id ),
				'attachment_page' => get_attachment_link( $attachment_id ),
				'wpcplink'        => $image_linking_url,
				'crop_position'   => get_post_meta( $attachment_id, 'crop_position', true ),
				'link_target'     => get_post_meta( $attachment_id, 'wpcplinktarget', true ),
			)
		);
	}

	/**
	 * Get file extension from a filename
	 *
	 * @param mixed $string filename.
	 *
	 * @return return
	 */
	public static function wpcp_string_to_ext( $string ) {
		// Remove url parameters.
		if ( strpos( $string, '?' ) !== false ) {
			$arr    = explode( '?', $string );
			$string = $arr[0];
		}

		$pos = strrpos( $string, '.' );
		$ext = strtolower( substr( $string, $pos ) );
		return $ext;
	}

	/**
	 * Clean watermark cache.
	 *
	 * @return void
	 */
	public function wpcp_clean_wm_cache() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : null;
		if ( ! wp_verify_nonce( $nonce, 'sp_wpcp_options_nonce' ) ) {
			return;
		}
		$wp_dirs   = wp_upload_dir();
		$cache_dir = trailingslashit( $wp_dirs['basedir'] ) . 'wpcp_watermarked';

		// Folder exists?
		if ( ! @file_exists( $cache_dir ) ) {
			die( 'success' );
		}

		// Clean.
		foreach ( scandir( $cache_dir ) as $file ) {
			$ext      = self::wpcp_string_to_ext( $file );
			$accepted = array( '.jpg', '.jpeg', '.gif', '.png' );

			if ( in_array( $ext, $accepted ) && file_exists( $cache_dir . '/' . $file ) ) {
				unlink( $cache_dir . '/' . $file );
			}
		}
		die( 'success' );
	}

	/**
	 * Register the stylesheets for the admin area of the plugin.
	 *
	 * @since  3.0.0
	 * @return void
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style( $this->plugin_name . '-admin', WPCAROUSEL_URL . 'Admin/css/wp-carousel-pro-admin' . $this->suffix . '.css', array(), $this->version, 'all' );
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		if ( 'sp_wp_carousel' === $the_current_post_type ) {
			wp_enqueue_style( 'wpcp-swiper' );
			wp_enqueue_style( 'wpcp-navigation-and-tabbed-icons' );
			wp_enqueue_style( 'wpcp-bx-slider-css' );
			wp_enqueue_style( 'wp-carousel-pro-fontawesome' );
			wp_enqueue_style( 'wpcp-fancybox-popup' );
			wp_enqueue_style( 'wpcp-animate' );
			wp_enqueue_style( 'wp-carousel-pro' );

			wp_enqueue_script( 'wpcp-swiper' );
			wp_enqueue_script( 'wpcp-swiper-gl' );
			wp_enqueue_script( 'wpcp-bx-slider' );
			wp_enqueue_script( 'wpcp-fancybox-popup' );
			wp_enqueue_script( 'wpcp-image-custom-color' );
			wp_enqueue_script( 'wpcp-jGallery' );
			wp_enqueue_script( 'wpcp-password' );
			wp_enqueue_script( 'wpcp-preloader' );
			wp_enqueue_script( 'wpcp-carousel-lazy-load' );
			wp_enqueue_script( 'jquery-masonry' );
		}

		wp_enqueue_script( $this->plugin_name . 'admin', WPCAROUSEL_URL . 'Admin/js/wp-carousel-pro-admin' . $this->suffix . '.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Add custom field for Images.
	 *
	 * @param array   $fields An array of attachment form fields.
	 * @param WP_Post $post        The WP_Post attachment object.
	 */
	public function add_attachment_linking_field( $fields, $post ) {
		$capability      = apply_filters( 'sp_wp_carousel_ui_permission', 'manage_options' );
		$user_capability = current_user_can( $capability ) ? true : false;

		if ( 'image' === substr( $post->post_mime_type, 0, 5 ) ) {
			$meta = wp_get_attachment_metadata( $post->ID );
			// $wpcplinking     = get_post_meta( $post->ID, 'wpcplinking', true );
			$wpcplink_target = get_post_meta( $post->ID, 'wpcplinktarget', true );

			$fields['meta_wpcplinking']    = array(
				'label'      => __( 'Image Link', 'wp-carousel-pro' ),
				'input'      => 'text',
				'value'      => isset( $meta['image_meta']['wpcplinking'] ) ? $meta['image_meta']['wpcplinking'] : '',
				'error_text' => __( 'Error WP Carousel linking meta.', 'wp-carousel-pro' ),
			);
			$fields['meta_wpcplinktarget'] = array(
				'label' => __( 'Open New Tab', 'wp-carousel-pro' ),
				'input' => 'html',
				'html'  => '<input type="checkbox" id="attachments-' . esc_attr( $post->ID ) . '-meta_wpcplinktarget" name="attachments[' . esc_attr( $post->ID ) . '][wpcplinktarget]" value="1" ' . ( $wpcplink_target ? ' checked="checked"' : '' ) . ' />',
				'value' => $wpcplink_target,
				'helps' => __( 'Image Link and Open a New Tab. Only for WP Carousel Pro', 'wp-carousel-pro' ),
			);
		}
		if ( ! $user_capability ) {
			return;
		}
		return $fields;
	}

	/**
	 * Filters the attachment fields to be saved.
	 *
	 * @param array $post       An array of post data.
	 * @param array $attachment An array of attachment metadata.
	 */
	public function update_attachment_linking_field( $post, $attachment ) {
		if ( isset( $attachment['meta_wpcplinking'] ) ) {
			$linking_url = $attachment['meta_wpcplinking'];
			$meta        = wp_get_attachment_metadata( $post['ID'] );
			if ( ! isset( $meta['image_meta']['wpcplinking'] ) || $linking_url !== $meta['image_meta']['wpcplinking'] ) {
				$meta['image_meta']['wpcplinking'] = $linking_url;
				wp_update_attachment_metadata( $post['ID'], $meta );
			}
		}
		if ( isset( $_REQUEST['attachments'][ $post['ID'] ]['wpcplinktarget'] ) ) {
			$wpcplinktarget = sanitize_text_field( wp_unslash( $_REQUEST['attachments'][ $post['ID'] ]['wpcplinktarget'] ) );
			update_post_meta( $post['ID'], 'wpcplinktarget', $wpcplinktarget );
		} else {
			delete_post_meta( $post['ID'], 'wpcplinktarget' );
		}
		return $post;
	}

	/**
	 * Change Carousel updated messages.
	 *
	 * @param string $messages The Update messages.
	 * @return statement
	 */
	public function wpcp_carousel_updated_messages( $messages ) {
		global $post, $post_ID;
		$messages['sp_wp_carousel'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Shortcode updated.', 'wp-carousel-pro' ),
			2  => '',
			3  => '',
			4  => __( 'Shortcode updated.', 'wp-carousel-pro' ),
			/* translators: %s: revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Shortcode restored to revision from %s', 'wp-carousel-pro' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Shortcode published.', 'wp-carousel-pro' ),
			7  => __( 'Shortcode saved.', 'wp-carousel-pro' ),
			8  => __( 'Shortcode submitted.', 'wp-carousel-pro' ),
			/* translators: %1$s: time */
			9  => sprintf( __( 'Shortcode scheduled for: <strong>%1$s</strong>.', 'wp-carousel-pro' ), date_i18n( __( 'M j, Y @ G:i', 'wp-carousel-pro' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Shortcode draft updated.', 'wp-carousel-pro' ),
		);
		return $messages;
	}

	/**
	 * Add carousel admin columns.
	 *
	 * @return statement
	 */
	public function filter_carousel_admin_column() {
		$admin_columns['cb']            = '<input type="checkbox" />';
		$admin_columns['title']         = __( 'Title', 'wp-carousel-pro' );
		$admin_columns['shortcode']     = __( 'Shortcode', 'wp-carousel-pro' );
		$admin_columns['carousel_type'] = __( 'Source Type', 'wp-carousel-pro' );
		$admin_columns['wpcp_layout']   = __( 'Layout Type', 'wp-carousel-pro' );
		$admin_columns['date']          = __( 'Date', 'wp-carousel-pro' );

		return $admin_columns;
	}

	/**
	 * Display admin columns for the carousels.
	 *
	 * @param mix    $column The columns.
	 * @param string $post_id The post ID.
	 * @return void
	 */
	public function display_carousel_admin_fields( $column, $post_id ) {
		$upload_data     = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
		$shortcode_data  = get_post_meta( $post_id, 'sp_wpcp_shortcode_options', true );
		$carousels_types = isset( $upload_data['wpcp_carousel_type'] ) ? $upload_data['wpcp_carousel_type'] : '';
		$layout_types    = isset( $shortcode_data['wpcp_layout'] ) ? $shortcode_data['wpcp_layout'] : '';
		switch ( $column ) {
			case 'shortcode':
				echo '<input style="max-width:100%; width: 270px; padding: 6px;cursor:pointer;" type="text" onClick="this.select();" readonly="readonly" value="[sp_wpcarousel id=&quot;' . esc_attr( $post_id ) . '&quot;]"/><div class="spwpc-after-copy-text"><i class="fa fa-check-circle"></i> Shortcode Copied to Clipboard! </div>';
				break;
			case 'carousel_type':
				echo esc_html( ucwords( str_replace( '-carousel', ' ', $carousels_types ) ) );
				break;
			case 'wpcp_layout':
				echo esc_html( ucwords( str_replace( '-', ' ', $layout_types ) ) );
				break;

		} // end switch.
	}

	/**
	 * Duplicate the carousel
	 *
	 * @return void
	 */
	public function sp_wpcp_duplicate_carousel() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sp_wpcp_duplicate_carousel' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'wp-carousel-pro' ) );
		}

		/**
		 * Nonce verification
		 */
		$nonce = isset( $_GET['sp_wpcp_duplicate_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['sp_wpcp_duplicate_nonce'] ) ) : null;
		if ( ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) {
			return;
		}

		/**
		 * Get the original shortcode id
		 */
		$post_id    = ( isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		$capability = apply_filters( 'sp_wp_carousel_ui_permission', 'manage_options' );

		$show_ui = current_user_can( $capability ) ? true : false;
		if ( ! $show_ui && get_post_type( $post_id ) !== 'sp_wp_carousel' ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'wp-carousel-pro' ) );
		}

		/**
		 * And all the original shortcode data then
		 */
		$post = get_post( $post_id );

		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/**
		 * If shortcode data exists, create the shortcode duplicate.
		 */
		if ( isset( $post ) && null !== $post ) {

			// New shortcode data array.
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/**
			 * Insert the shortcode by wp_insert_post() function.
			 */
			$new_post_id = wp_insert_post( $args );

			/**
			 * Get all current post terms ad set them to the new post draft.
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");.
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/**
			 * Duplicate all post meta just in two SQL queries.
			 */
			$post_meta_infos = get_post_custom( $post_id );
			// Duplicate all post meta just.
			foreach ( $post_meta_infos as $key => $values ) {
				foreach ( $values as $value ) {
					$value = wp_slash( maybe_unserialize( $value ) ); // Unserialize data to avoid conflicts.
					add_post_meta( $new_post_id, $key, $value );
				}
			}

			/**
			 * Finally, redirect to the edit post screen for the new draft
			 */
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			exit;
		} else {
			wp_die( esc_html__( 'Shortcode duplication failed, could not find original shortcode: ', 'wp-carousel-pro' ) . esc_attr( $post_id ) );
		}
	}

	/**
	 * Add the duplicate link to action list for post_row_actions
	 *
	 * @param mix    $actions The actions of the link.
	 * @param object $post The post to provide its ID.
	 *
	 * @return statement
	 */
	public function sp_wpcp_duplicate_carousel_link( $actions, $post ) {
		$capability = apply_filters( 'sp_wp_carousel_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( $show_ui && 'sp_wp_carousel' === $post->post_type ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=sp_wpcp_duplicate_carousel&post=' . $post->ID, basename( __FILE__ ), 'sp_wpcp_duplicate_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'wp-carousel-pro' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Bottom review notice.
	 *
	 * @param string $text The review notice.
	 * @return string
	 */
	public function sp_wpcp_review_text( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wp_carousel' === get_post_type() || 'sp_wp_carousel_page_wpcp_settings' === $screen->id || 'sp_wp_carousel_page_wpcp_help' === $screen->id ) {
			$url  = 'https://wordpress.org/support/plugin/wp-carousel-free/reviews/?filter=5#new-post';
			$text = sprintf( __( 'If you like <strong>WP Carousel Pro</strong>, please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more.', 'wp-carousel-pro' ), $url );
		}

		return $text;
	}

	/**
	 * Declare that this plugin is compatible with WooCommerce High-Performance Order Storage (HPOS) feature.
	 *
	 * @since 3.10.5
	 *
	 * @return void
	 */
	public function declare_compatibility_with_woo_hpos_feature() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'wp-carousel-pro/wp-carousel-pro.php', true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'wp-carousel-free/wp-carousel-free.php', true );
		}
	}
}
