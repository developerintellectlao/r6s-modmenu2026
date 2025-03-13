<?php
/**
 * Plugin Name: SoftLite.io Integration
 * Description: This plugin is used to integrate Softlite.io tools into your website
 * Plugin URI:  https://softlite.io/clonewebx/
 * Version:     1.0.28
 * Author:      SoftLite.io
 * Author URI:  https://softlite.io/
 * Text Domain: softlite
 * Domain Path: /languages
 * Elementor tested up to: 3.13.4
 * Elementor Pro tested up to: 3.13.2
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SOFTLITE_VERSION', '1.0.28' );

final class Softlite {
    
    private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        
        require_once(__DIR__ . '/inc/ajax/upload-files.php');
        require_once(__DIR__ . '/inc/ajax/save-css.php');
        require_once(__DIR__ . '/inc/gutenberg/dynamic/dynamic.php');
        require_once(__DIR__ . '/inc/ajax/dynamic-image.php');

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'wp_footer', [ $this, 'footer_scripts' ] );

        add_filter( 'upload_mimes', [ $this, 'add_mime_types' ] );

        if ( defined('ELEMENTOR_VERSION') && version_compare( ELEMENTOR_VERSION, '3.10.0', '>=' ) ) {
            add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
            add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles_widget' ] );
            add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
            add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'add_custom_css_for_editor' ] );
            add_action( 'elementor/editor/before_enqueue_styles', [ $this, 'enqueue_elementor_editor_control_script' ] );
            add_action( 'wp_print_scripts', [ $this, 'dequeue_scripts' ] );
            add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );
        }

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
        add_action( 'admin_init', [ $this, 'admin_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );

        // Check gutenberg enable
        add_action( 'enqueue_block_editor_assets', [$this, 'gutenberg_block_script_register'] );
        add_action( 'enqueue_block_assets', [$this, 'gutenberg_block_script_register'] );

        if ( version_compare( $GLOBALS['wp_version'], '5.8', '<' ) ) {
			add_filter( 'block_categories', [ $this, 'gutenberg_block_categories' ], 10, 2 );
		} else {
			add_filter( 'block_categories_all', [ $this, 'gutenberg_block_categories' ], 10, 2 );
		}

        $upload     = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/softlite-io-integration';
		if ( ! is_dir( $upload_dir ) ) {
			mkdir( $upload_dir, 0775 );
		} else {
			@chmod( $upload_dir, 0775 );
		}
		if ( ! is_dir( $upload_dir . '/css' ) ) {
			mkdir( $upload_dir . '/css', 0775 );
		} else {
			@chmod( $upload_dir . '/css', 0775 );
		}
        define('SOFTLITE_CSS_DIR', $upload_dir . '/css');
        $this->setup_updater();
        add_action( 'wp_head', [$this, 'gutenberg_render_style'] );

        add_action( 'init', [ $this, 'register_post_type' ] );
        add_shortcode( 'softlite_block', [ $this, 'softlite_block_shortcode' ] );

        add_filter( 'manage_softlite_block_posts_columns', [ $this, 'set_custom_edit_columns' ] );
		add_action( 'manage_softlite_block_posts_custom_column', [ $this, 'custom_column' ], 10, 2 );

        add_action( 'admin_init', [ $this, 'do_flush' ] );

        add_filter( 'the_content', [$this, 'override_dynamic_block'] );
    }

    public function override_dynamic_block($content){
        if(!is_admin() && strpos($content, 'id="softlite-block-') !== false){
            $content = softlite_get_dynamic_tag($content);
        }
        return $content;
    }

    public function do_flush() {
		if ( !get_option( 'softlite_io_integration_do_flush' ) ) {
			update_option( 'softlite_io_integration_do_flush', true );
			flush_rewrite_rules();
		}
	}

    public function register_post_type() {
		register_post_type(
			'softlite_block',
			[
				'labels'       => [
					'name'          => __( 'Block Templates', 'softlite' ),
					'singular_name' => __( 'Block Template', 'softlite' ),
				],
				'public'       => true,
				'has_archive'  => true,
				'show_in_menu' => false,
				'show_in_rest' => true,
				'supports' => ['title', 'editor', 'revisions'],
			]
		);
    }

    public function set_custom_edit_columns( $columns ) {
		$columns['softlite-block-shortcode'] = __( 'Shortcode', 'softlite' );
		return $columns;
	}

	public function custom_column( $column, $post_id ) {
		switch ( $column ) {
			case 'softlite-block-shortcode':
				echo '<input class="softlite-block-shortcode-input" type="text" readonly="" onfocus="this.select()" value="[softlite_block id=' . $post_id  . ']">';
				break;
		}
	}

    public function enqueue_scripts() {
        wp_register_script(
            'softlite-editor-popup-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/minify/editor-popup.min.js',
            [],
            SOFTLITE_VERSION,
            true
        );

        wp_register_style(
            'softlite-editor-popup-style',
            plugin_dir_url( __FILE__ ) . 'assets/css/minify/editor-popup.min.css',
            [],
            SOFTLITE_VERSION
        );

        if ( !empty( $_GET['bricks'] ) && $_GET['bricks'] == 'run' || !empty( $_GET['breakdance_iframe'] ) ) {
            if ( get_option( 'softlite_io_integration_clonewebx_popup' ) ) {
                wp_enqueue_script('softlite-editor-popup-script');
                wp_enqueue_style('softlite-editor-popup-style');
            }
		}
    }

    public function admin_enqueue() {
        wp_enqueue_style( 'softlite-io-integration-admin-style', plugin_dir_url( __FILE__ ) . 'assets/css/minify/admin.min.css', [], SOFTLITE_VERSION );
    }

    public function gutenberg_render_style($in_footer = false){
        $current_post_id = get_the_ID();
        $current_post = get_post($current_post_id);

        if(is_null($current_post)){
            return;
        }
        $css_dir = SOFTLITE_CSS_DIR . '/' . $current_post_id . '.css';
        if(file_exists($css_dir) && filesize($css_dir)){
            $css_url = str_replace(WP_CONTENT_DIR, content_url(), $css_dir);
            wp_enqueue_style( 'softlite-gutenberg-' . $current_post_id, $css_url, [],  time());
        }
    }

    public function footer_scripts() {
        if ( !empty( $_GET['bricks'] ) && $_GET['bricks'] == 'run' || !empty( $_GET['breakdance_iframe'] ) ) {
            ?>
            <script>
                var softliteData = {
                    ajaxurl: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    postID: '<?php echo get_the_ID(); ?>',
                    media_upload_nonce: '<?php echo wp_create_nonce( 'softlite_media_upload_nonce' ); ?>',
                    site_domain: '<?php echo preg_replace('/^https?:\/\//', '', get_site_url()); ?>',
                    site_url: '<?php echo get_site_url(); ?>',
                };
            </script>
            <?php
        }
    }

    public function dequeue_scripts() {
        wp_dequeue_script( 'editor-css-script' );
    }

    public function gutenberg_block_script_register() {
        wp_enqueue_script( 'softlite-gutenberg-js-block', plugin_dir_url( __FILE__ ) . 'assets/js/minify/block.min.js', [ 'wp-components', 'wp-element', 'wp-data', 'wp-blocks', 'wp-i18n', 'wp-editor', 'wp-dom', 'wp-dom-ready', 'wp-edit-post'], true, false );
        wp_localize_script(
            'softlite-gutenberg-js-block',
            'softliteData',
            array(
                'postID' => get_the_ID(),
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'media_upload_nonce' => wp_create_nonce( 'softlite_media_upload_nonce' ),
                'site_domain' => preg_replace('/^https?:\/\//', '', get_site_url()),
                'site_url' => get_site_url(),
                'post_type' => get_post_type()
            )
        );
        wp_register_script(
            'softlite-editor-popup-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/minify/editor-popup.min.js',
            [],
            SOFTLITE_VERSION,
            true
        );
        wp_register_style(
            'softlite-editor-popup-style',
            plugin_dir_url( __FILE__ ) . 'assets/css/minify/editor-popup.min.css',
            [],
            SOFTLITE_VERSION
        );
        wp_localize_script(
            'softlite-elementor-editor-script',
            'softliteData',
            array(
                'postID' => get_the_ID(),
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'media_upload_nonce' => wp_create_nonce( 'softlite_media_upload_nonce' ),
                'site_domain' => preg_replace('/^https?:\/\//', '', get_site_url()),
                'site_url' => get_site_url(),
            )
        );

        global $pagenow;

        if ( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && get_option( 'softlite_io_integration_clonewebx_popup' ) ) {
            wp_enqueue_script('softlite-editor-popup-script');
            wp_enqueue_style('softlite-editor-popup-style');
        }
    }

    public function gutenberg_block_categories( $categories ) {
		return array_merge(
			$categories,
			[
				[
					'slug'  => 'softlite-io-integration',
					'title' => __( 'Softlite.io', 'softlite' ),
				],
			]
		);
	}

    public function add_mime_types($mimes) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['woff'] = 'application/font-woff';
        $mimes['woff2'] = 'application/font-woff2';
        $mimes['otf'] = 'application/font-opentype';
        $mimes['ttf'] = 'application/x-font-ttf';
        $mimes['eot'] = 'application/vnd.ms-fontobject';
        return $mimes;
    }

    public function init_widgets($widgets_manager) {
        require_once( __DIR__ . '/inc/elementor/widgets/block-template.php' );
        $widgets_manager->register( new \Softlite_Block_Template() );
        require_once( __DIR__ . '/inc/elementor/widgets/image.php' );
        $widgets_manager->register( new \Softlite_Image() );
    }

    public function init_controls() {
        require_once( __DIR__ . '/inc/elementor/extensions/advanced.php' );
		new Softlite_Advanced();

        require_once( __DIR__ . '/inc/elementor/extensions/background.php' );
		new Softlite_Background_Image();
    }

    public function enqueue_styles_widget() {
		wp_register_style( 'softlite-integration-widget-style', plugin_dir_url( __FILE__ ) . 'assets/css/minify/widget.min.css', [], SOFTLITE_VERSION );
	}

    public function enqueue_elementor_editor_control_script() {
        wp_enqueue_script(
            'softlite-elementor-editor-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/minify/elementor-editor-control.min.js',
            ['jquery'],
            SOFTLITE_VERSION,
            true
        );
    }

    public function add_custom_css_for_editor() {
        wp_dequeue_script( 'editor-css-script' );
        
        wp_enqueue_script(
            'purify',
            plugin_dir_url( __FILE__ ) . 'assets/js/minify/purify.min.js',
            [],
            SOFTLITE_VERSION,
            true
        );

        wp_enqueue_script(
            'softlite-elementor-editor-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/minify/elementor-editor.min.js',
            ['elementor-frontend', 'purify', 'jquery'],
            SOFTLITE_VERSION,
            true
        );

        if ( !empty( $_GET['elementor-preview'] ) ) {
            wp_localize_script(
                'softlite-elementor-editor-script',
                'softliteData',
                array(
                    'postID' => get_the_ID(),
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'media_upload_nonce' => wp_create_nonce( 'softlite_media_upload_nonce' ),
                    'site_domain' => preg_replace('/^https?:\/\//', '', get_site_url()),
                    'site_url' => get_site_url(),
                )
            );
            if ( get_option( 'softlite_io_integration_clonewebx_popup' ) ) {
                wp_enqueue_script('softlite-editor-popup-script');
                wp_enqueue_style('softlite-editor-popup-style');
            }
        }
    }

    public function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'softlite',
			[
				'title' => __( 'Softlite.io Integration', 'softlite' ),
				'icon' => 'fa fa-plug',
			]
		);

	}

    public function admin_menu() {
        add_menu_page(
            'Settings',
			'Softlite.io',
            'edit_others_posts',
            'softlite',
			[$this, 'admin_page'],
            'dashicons-softlite'
        );

        add_submenu_page( 'softlite', 'Settings', 'Settings', 'edit_others_posts', 'softlite', [ $this, 'admin_page' ] );

		add_submenu_page( 'softlite', 'Block Templates', 'Block Templates', 'edit_others_posts', 'edit.php?post_type=softlite_block' );
	}

    public function softlite_block_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => '',
        ), $atts, 'softlite_block');
    
        if (empty($atts['id'])) {
            return '';
        }
    
        $post_id = intval($atts['id']);
        $post = get_post($post_id);
    
        if ($post && $post->post_type == 'softlite_block') {
            $css_dir = SOFTLITE_CSS_DIR . '/' . $post_id . '.css';

            if(file_exists($css_dir) && filesize($css_dir)){
                $css_url = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $css_dir);
                wp_enqueue_style( 'softlite-gutenberg-' . $post_id, $css_url, [],  time());
            }

            return do_blocks($post->post_content);
        }
    
        return '';
    }

    public function admin_settings() {
        register_setting( 'softlite_settings_group', 'softlite_io_integration_clonewebx_popup' );
    }

    public function admin_page() {
		require_once( __DIR__ . '/inc/admin-page.php' );
	}

    private function setup_updater() {
		require_once( 'updater.php' );
		$plugin_slug = plugin_basename( __FILE__ );
		new Softlite_Updater( $plugin_slug, SOFTLITE_VERSION );
	}

}

// Instantiate Softlite.
Softlite::instance();