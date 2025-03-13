<?php
/**
 * Framework setup.class file.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

namespace ShapedPlugin\WPCarouselPro\Admin\views\sp_framework\classes;

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WPCP_Framework' ) ) {
	/**
	 *
	 * Setup Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WPCP_Framework {

		// Default constants.
		/**
		 * Premium.
		 *
		 * @var boolean
		 */
		public static $premium = true;
		/**
		 * Version.
		 *
		 * @var string
		 */
		public static $version = '2.2.4';
		/**
		 * Dir.
		 *
		 * @var string
		 */
		public static $dir = '';
		/**
		 * Url.
		 *
		 * @var string
		 */
		public static $url = '';
		/**
		 * CSS.
		 *
		 * @var string
		 */
		public static $css = '';
		/**
		 * File.
		 *
		 * @var string
		 */
		public static $file = '';
		/**
		 * Enqueue.
		 *
		 * @var boolean
		 */
		public static $enqueue = false;
		/**
		 * Web fonts.
		 *
		 * @var array
		 */
		public static $webfonts = array();
		/**
		 * Subsets.
		 *
		 * @var array
		 */
		public static $subsets = array();
		/**
		 * Inited.
		 *
		 * @var array
		 */
		public static $inited = array();
		/**
		 * Fields.
		 *
		 * @var array
		 */
		public static $fields = array();
		/**
		 * Args.
		 *
		 * @var array
		 */
		public static $args = array(
			'admin_options'   => array(),
			'metabox_options' => array(),
		);

			/**
			 * Shortcode instances.
			 *
			 * @var array
			 */
		public static $shortcode_instances = array();
		/**
		 * Instance.
		 *
		 * @var string
		 */
		private static $instance = null;
		/**
		 * Init function of the setup class.
		 *
		 * @param mixed $file files.
		 * @return statement
		 */
		public static function init( $file = __FILE__ ) {

			// Set file constant.
			self::$file = $file;

			// Set constants.
			self::constants();

			// Include files.
			self::includes();

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Initialize.
		 *
		 * @return void
		 */
		public function __construct() {

			// Init action.
			do_action( 'sp_wpcp_init' );

			add_action( 'after_setup_theme', array( $this, 'setup' ) );
			add_action( 'init', array( $this, 'setup' ) );
			add_action( 'switch_theme', array( $this, 'setup' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_enqueue_scripts' ) );
		}

		/**
		 * Setup frameworks
		 *
		 * @return void
		 */
		public static function setup() {
			self::include_plugin_file( 'configs/metabox-config.php' );
			self::include_plugin_file( 'configs/option-config.php' );
			self::include_plugin_file( 'configs/tools-config.php' );
			// Setup admin option framework.
			$params = array();
			if ( class_exists( 'SP_WPCP_Framework_Options' ) && ! empty( self::$args['admin_options'] ) ) {
				foreach ( self::$args['admin_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

						$params['args']       = $value;
						$params['sections']   = self::$args['sections'][ $key ];
						self::$inited[ $key ] = true;

						\SP_WPCP_Framework_Options::instance( $key, $params );
					}
				}
			}
			// Setup metabox option framework.
			$params = array();
			if ( class_exists( 'SP_WPCP_Framework_Metabox' ) && ! empty( self::$args['metabox_options'] ) ) {
				foreach ( self::$args['metabox_options'] as $key => $value ) {
					if ( ! empty( self::$args['sections'][ $key ] ) && ! isset( self::$inited[ $key ] ) ) {

							$params['args']       = $value;
							$params['sections']   = self::$args['sections'][ $key ];
							self::$inited[ $key ] = true;

							\SP_WPCP_Framework_Metabox::instance( $key, $params );

					}
				}
			}

			do_action( 'sp_wpcp_loaded' );
		}

		/**
		 * Create options
		 *
		 * @param  mixed $id id.
		 * @param  mixed $args array.
		 * @return void
		 */
		public static function createOptions( $id, $args = array() ) {
			self::$args['admin_options'][ $id ] = $args;
		}

		/**
		 * Create metabox options
		 *
		 * @param  mixed $id id.
		 * @param  mixed $args array.
		 * @return void
		 */
		public static function createMetabox( $id, $args = array() ) {
			self::$args['metabox_options'][ $id ] = $args;
		}
		/**
		 * Create section.
		 *
		 * @param  mixed $id id.
		 * @param  mixed $sections Section.
		 * @return void
		 */
		public static function createSection( $id, $sections ) {
			self::$args['sections'][ $id ][] = $sections;
			self::set_used_fields( $sections );
		}

		/**
		 * Set directory constants
		 *
		 * @return void
		 */
		public static function constants() {

			// We need this path-finder code for set URL of framework.
			$dirname        = str_replace( '//', '/', wp_normalize_path( dirname( dirname( self::$file ) ) ) );
			$theme_dir      = str_replace( '//', '/', wp_normalize_path( get_parent_theme_file_path() ) );
			$plugin_dir     = str_replace( '//', '/', wp_normalize_path( WP_PLUGIN_DIR ) );
			$plugin_dir     = str_replace( '/opt/bitnami', '/bitnami', $plugin_dir );
			$located_plugin = ( preg_match( '#' . self::sanitize_dirname( $plugin_dir ) . '#', self::sanitize_dirname( $dirname ) ) ) ? true : false;
			$directory      = ( $located_plugin ) ? $plugin_dir : $theme_dir;
			$directory_uri  = ( $located_plugin ) ? WP_PLUGIN_URL : get_parent_theme_file_uri();
			$foldername     = str_replace( $directory, '', $dirname );
			$protocol_uri   = ( is_ssl() ) ? 'https' : 'http';
			$directory_uri  = set_url_scheme( $directory_uri, $protocol_uri );

			self::$dir = $dirname;
			self::$url = $directory_uri . $foldername;
		}

		/**
		 * Include file helper.
		 *
		 * @param  mixed $file file.
		 * @param  mixed $load load.
		 * @return Statement.
		 */
		public static function include_plugin_file( $file, $load = true ) {
			$path     = '';
			$file     = ltrim( $file, '/' );
			$override = apply_filters( 'sp_wpcp_override', 'sp_wpcp-override' );

			if ( file_exists( get_parent_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_parent_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( get_theme_file_path( $override . '/' . $file ) ) ) {
				$path = get_theme_file_path( $override . '/' . $file );
			} elseif ( file_exists( self::$dir . '/' . $override . '/' . $file ) ) {
				$path = self::$dir . '/' . $override . '/' . $file;
			} elseif ( file_exists( self::$dir . '/' . $file ) ) {
				$path = self::$dir . '/' . $file;
			}

			if ( ! empty( $path ) && ! empty( $file ) && $load ) {

				global $wp_query;

				if ( is_object( $wp_query ) && function_exists( 'load_template' ) ) {

					load_template( $path, true );

				} else {

					require_once $path;

				}
			} else {
				return self::$dir . '/' . $file;
			}
		}

		/**
		 * Is active plugin helper.
		 *
		 * @param  mixed $file file path.
		 * @return array.
		 */
		public static function is_active_plugin( $file = '' ) {
			return in_array( $file, (array) get_option( 'active_plugins', array() ) );
		}

		/**
		 * Sanitize dirname
		 *
		 * @param  mixed $dirname dir name.
		 * @return string
		 */
		public static function sanitize_dirname( $dirname ) {
			return preg_replace( '/[^A-Za-z]/', '', $dirname );
		}

		/**
		 * Set url constant.
		 *
		 * @param  mixed $file file url.
		 * @return url
		 */
		public static function include_plugin_url( $file ) {
			return WPCAROUSEL_URL . 'Admin/views/sp_framework/' . ltrim( $file, '/' );
		}

		/**
		 * Include files
		 *
		 * @return void
		 */
		public static function includes() {

			// Helpers.
			self::include_plugin_file( 'functions/actions.php' );
			self::include_plugin_file( 'functions/helpers.php' );
			self::include_plugin_file( 'functions/sanitize.php' );
			self::include_plugin_file( 'functions/validate.php' );

			// Includes free version classes.
			self::include_plugin_file( 'classes/abstract.class.php' );
			self::include_plugin_file( 'classes/fields.class.php' );
			self::include_plugin_file( 'classes/admin-options.class.php' );
			self::include_plugin_file( 'classes/metabox-options.class.php' );

			// Include all framework fields.
			$fields = apply_filters(
				'sp_wpcp_fields',
				array(
					'background',
					'border',
					'access_button',
					'button_clean',
					'button_set',
					'carousel_type',
					'checkbox',
					'code_editor',
					'color',
					'color_group',
					'custom_import',
					'column',
					'datetime',
					'dimensions_advanced',
					'fieldset',
					'gallery',
					'group',
					'insta_group',
					'heading',
					'image_color',
					'image_select',
					'image_sizes',
					'license',
					'media',
					'preview',
					'radio',
					'select',
					'shortcode',
					'spacing',
					'spinner',
					'subheading',
					'slider',
					'switcher',
					'tabbed',
					'text',
					'textarea',
					'typography',
					'upload',
					'wp_editor',
					'box_shadow',
				)
			);

			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( ! class_exists( 'SP_WPCP_Framework_Field_' . $field ) && class_exists( 'SP_WPCP_Framework_Fields' ) ) {
						self::include_plugin_file( 'fields/' . $field . '/' . $field . '.php' );
					}
				}
			}
		}

		/**
		 * Set all of used fields
		 *
		 * @param  mixed $sections section.
		 * @return void
		 */
		public static function set_used_fields( $sections ) {

			if ( ! empty( $sections['fields'] ) ) {

				foreach ( $sections['fields'] as $field ) {

					if ( ! empty( $field['fields'] ) ) {
						self::set_used_fields( $field );
					}

					if ( ! empty( $field['tabs'] ) ) {
						self::set_used_fields( array( 'fields' => $field['tabs'] ) );
					}

					if ( ! empty( $field['accordions'] ) ) {
						self::set_used_fields( array( 'fields' => $field['accordions'] ) );
					}

					if ( ! empty( $field['type'] ) ) {
						self::$fields[ $field['type'] ] = $field;
					}
				}
			}
		}

		/**
		 * Enqueue admin and fields styles and scripts
		 *
		 * @return void
		 */
		public static function add_admin_enqueue_scripts() {

			// Loads scripts and styles only when needed.
			$wpscreen = get_current_screen();
			if ( 'sp_wp_carousel' === $wpscreen->post_type ) {
				global $post_id;

				if ( ! empty( self::$args['admin_options'] ) ) {
					foreach ( self::$args['admin_options'] as $argument ) {
						if ( substr( $wpscreen->id, -strlen( $argument['menu_slug'] ) ) === $argument['menu_slug'] ) {
							self::$enqueue = true;
						}
					}
				}
				if ( ! empty( self::$args['metabox_options'] ) ) {
					foreach ( self::$args['metabox_options'] as $argument ) {
						if ( in_array( $wpscreen->post_type, (array) $argument['post_type'] ) ) {
							self::$enqueue = true;
						}
					}
				}

				if ( ! apply_filters( 'sp_wpcp_enqueue_assets', self::$enqueue ) ) {
					return;
				}

				// Check for developer mode.
				$min = ( self::$premium && SCRIPT_DEBUG ) ? '' : '.min';

				// Admin utilities.
				wp_enqueue_media();

				// Wp color picker.
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker' );

				// Font awesome 4 and 5 loader.
				if ( apply_filters( 'sp_wpcp_fa4', true ) ) {
					wp_enqueue_style( 'sp_wpcp-fa', 'https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome' . $min . '.css', array(), '4.7.0', 'all' );
				} else {
					wp_enqueue_style( 'sp_wpcp-fa5', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all' . $min . '.css', array(), '5.15.5', 'all' );
					wp_enqueue_style( 'sp_wpcp-fa5-v4-shims', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/v4-shims' . $min . '.css', array(), '5.15.5', 'all' );
				}
				// Main style.
				wp_enqueue_style( 'sp_wpcp', self::include_plugin_url( 'assets/css/style' . $min . '.css' ), array(), WPCAROUSEL_VERSION, 'all' );
				// Main RTL styles.
				if ( is_rtl() ) {
					wp_enqueue_style( 'sp_wpcp-rtl', self::include_plugin_url( 'assets/css/style-rtl' . $min . '.css' ), array(), WPCAROUSEL_VERSION, 'all' );
				}

				// Main scripts.
				wp_enqueue_script( 'sp_wpcp-plugins', self::include_plugin_url( 'assets/js/plugins' . $min . '.js' ), array(), WPCAROUSEL_VERSION, true );
				wp_enqueue_script( 'sp_wpcp', self::include_plugin_url( 'assets/js/main' . $min . '.js' ), array( 'sp_wpcp-plugins' ), WPCAROUSEL_VERSION, true );

				wp_localize_script(
					'sp_wpcp',
					'wpcp_admin',
					array(
						'previewJS'         => WPCAROUSEL_URL . 'Frontend/js/wp-carousel-pro-public.min.js',
						're_serialize_json' => apply_filters( 'sp_wpcp_settings_re_serialize_json', false ),
					)
				);
				wp_localize_script(
					'sp_wpcp',
					'sp_wpcp_metabox_local',
					array(
						'id'           => $post_id,
						'slide_width'  => 960,
						'slide_height' => 300,
						'save_nonce'   => wp_create_nonce( 'wpcp_image-save-meta' ),
						'saving'       => esc_attr__( 'Saving...', 'wp-carousel-pro' ),
						'videoframe'   => esc_attr__( 'Choose a Video Placeholder Image', 'wp-carousel-pro' ),
						'videouse'     => esc_attr__( 'Select Placeholder Image', 'wp-carousel-pro' ),
					)
				);

				// Main variables.
				wp_localize_script(
					'sp_wpcp',
					'sp_wpcp_vars',
					array(
						'color_palette'  => apply_filters( 'sp_wpcp_color_palette', array() ),
						'wpcp_swiper_js' => true,
						'i18n'           => array(
							'confirm'         => esc_html__( 'Are you sure?', 'wp-carousel-pro' ),
							'typing_text'     => esc_html__( 'Please enter %s or more characters', 'wp-carousel-pro' ),
							'searching_text'  => esc_html__( 'Searching...', 'wp-carousel-pro' ),
							'no_results_text' => esc_html__( 'No results found.', 'wp-carousel-pro' ),
						),
					)
				);

				// Enqueue fields scripts and styles.
				$enqueued = array();

				if ( ! empty( self::$fields ) ) {
					foreach ( self::$fields as $field ) {
						if ( ! empty( $field['type'] ) ) {
							$classname = 'SP_WPCP_Framework_Field_' . $field['type'];
							if ( class_exists( $classname ) && method_exists( $classname, 'enqueue' ) ) {
								$instance = new $classname( $field );
								if ( method_exists( $classname, 'enqueue' ) ) {
									$instance->enqueue();
								}
								unset( $instance );
							}
						}
					}
				}
			}
			do_action( 'sp_wpcp_enqueue' );
		}

		/**
		 * Add a new framework field.
		 *
		 * @param  array  $field field.
		 * @param  string $value string.
		 * @param  mixed  $unique unique id.
		 * @param  mixed  $where where to add.
		 * @param  mixed  $parent parent.
		 * @return void
		 */
		public static function field( $field = array(), $value = '', $unique = '', $where = '', $parent = '' ) {

			// Check for disallow fields.
			if ( ! empty( $field['_notice'] ) ) {

				$field_type = $field['type'];

				$field            = array();
				$field['content'] = esc_html__( 'Oops! Not allowed.', 'wp-carousel-pro' ) . ' <strong>(' . $field_type . ')</strong>';
				$field['type']    = 'notice';
				$field['style']   = 'danger';

			}

			$depend     = '';
			$visible    = '';
			$unique     = ( ! empty( $unique ) ) ? $unique : '';
			$class      = ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
			$is_pseudo  = ( ! empty( $field['pseudo'] ) ) ? ' sp_wpcp-pseudo-field' : '';
			$field_type = ( ! empty( $field['type'] ) ) ? esc_attr( $field['type'] ) : '';

			if ( ! empty( $field['dependency'] ) ) {

				$dependency      = $field['dependency'];
				$depend_visible  = '';
				$data_controller = '';
				$data_condition  = '';
				$data_value      = '';
				$data_global     = '';

				if ( is_array( $dependency[0] ) ) {
					$data_controller = implode( '|', array_column( $dependency, 0 ) );
					$data_condition  = implode( '|', array_column( $dependency, 1 ) );
					$data_value      = implode( '|', array_column( $dependency, 2 ) );
					$data_global     = implode( '|', array_column( $dependency, 3 ) );
					$depend_visible  = implode( '|', array_column( $dependency, 4 ) );
				} else {
					$data_controller = ( ! empty( $dependency[0] ) ) ? $dependency[0] : '';
					$data_condition  = ( ! empty( $dependency[1] ) ) ? $dependency[1] : '';
					$data_value      = ( ! empty( $dependency[2] ) ) ? $dependency[2] : '';
					$data_global     = ( ! empty( $dependency[3] ) ) ? $dependency[3] : '';
					$depend_visible  = ( ! empty( $dependency[4] ) ) ? $dependency[4] : '';
				}

				$depend .= ' data-controller="' . esc_attr( $data_controller ) . '"';
				$depend .= ' data-condition="' . esc_attr( $data_condition ) . '"';
				$depend .= ' data-value="' . esc_attr( $data_value ) . '"';
				$depend .= ( ! empty( $data_global ) ) ? ' data-depend-global="true"' : '';

				$visible = ( ! empty( $depend_visible ) ) ? ' sp_wpcp-depend-visible' : ' sp_wpcp-depend-hidden';

			}

				// These attributes has been sanitized above.
				echo wp_kses_post( '<div class="sp_wpcp-field sp_wpcp-field-' . $field_type . $is_pseudo . $class . $visible . '"' . $depend . '>' );
			if ( ! empty( $field_type ) ) {
				if ( ! empty( $field['fancy_title'] ) ) {
					echo '<div class="sp_wpcp-fancy-title">' . wp_kses_post( $field['fancy_title'] ) . '</div>';
				}

				if ( ! empty( $field['title'] ) ) {
					echo '<div class="sp_wpcp-title">';
					$help_title = ( ! empty( $field['title_help'] ) ) ? '
					<div class="sp_wpcp-help sp_wpcp-title-help">
					<span class="sp_wpcp-help-text">' . $field['title_help'] . '</span>
					<img src="' . self::include_plugin_url( 'assets/images/info.svg' ) . '">
					</div>' : '';
					echo '<h4>' . wp_kses_post( $field['title'] . $help_title ) . '</h4>';
					echo ( ! empty( $field['subtitle'] ) ) ? '<div class="sp_wpcp-subtitle-text">' . wp_kses_post( $field['subtitle'] ) . '</div>' : '';
					echo '</div>';
				}

				echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '<div class="sp_wpcp-fieldset">' : '';

				$value = ( ! isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
				$value = ( isset( $field['value'] ) ) ? $field['value'] : $value;

				$classname = 'SP_WPCP_Framework_Field_' . $field_type;

				if ( class_exists( $classname ) ) {
					$instance = new $classname( $field, $value, $unique, $where, $parent );
					$instance->render();
				} else {
					echo '<p>' . esc_html__( 'Field not found!', 'wp-carousel-pro' ) . '</p>';
				}
			} else {
				echo '<p>' . esc_html__( 'Field not found!', 'wp-carousel-pro' ) . '</p>';
			}

			echo ( ! empty( $field['title'] ) || ! empty( $field['fancy_title'] ) ) ? '</div>' : '';
			echo isset( $field['type'] ) && 'heading' == $field['type'] ? '' : '<div class="clear"></div>';
			echo '</div>';
		}
	}

}

// SP_WPCP_Framework::init( __FILE__ );
