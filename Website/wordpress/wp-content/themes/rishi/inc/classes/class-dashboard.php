<?php 
/**
 * All Dashboard related php classes here
 *
 * @package Rishi
 */
namespace Rishi\Dashboard;
class Rishi_Dashboard{

    /**
	 * Instance of this class.
	 *
	 * @var object|null
	 */
	protected static $instance = null;

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Hooks and Filters.
	 */
	public function init() {
        add_action( 'admin_menu', array( $this, 'rishi_add_menu_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'rishi_admin_scripts' ) );
        // AJAX for Starter PLugin install
        add_action( 'wp_ajax_rishi_get_install_starter', array( $this, 'rishi_install_starter_templates' ) );
	}

    /**
     * Add Menu Page
     */
    public function rishi_add_menu_page() {

        if ( !current_user_can( 'activate_plugins' ) ) {
            return;
        }    
    
        $menu_name = apply_filters(
            'rishi_add_menu_page',
            __( 'Rishi Theme', 'rishi' )
        );

        add_theme_page(
            $menu_name,
            $menu_name,
            'activate_plugins',
            'rishi-dashboard',
            array( $this, 'rishi_getting_started' ),
            5
        );
    }

    /**
     * Getting Started Callback
     */
    public function rishi_getting_started() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html( __( 'You do not have sufficient permissions to access this page.', 'rishi' ) ) );
        }
    
        echo '<div id="rishi-dashboard"></div>';
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param [type] $hook
     * @return void
     */
    public function rishi_admin_scripts( $hook ){
    
        if ( $hook === 'appearance_page_rishi-dashboard' ) {
            $installed_plugins = get_plugins();
            $data_action       = '';
            $button_label      = esc_html__( 'Browse Rishi Starter Templates', 'rishi' );
        
            if ( ! defined( 'DEMO_IMPORTER_PLUS_VER' ) ) {
                if ( ! isset( $installed_plugins['demo-importer-plus/demo-importer-plus.php'] ) ) {
                    $button_label = esc_html__( 'Install Rishi Starter Templates', 'rishi' );
                    $data_action  = 'install';
                } elseif ( ! $this->rishi_active_plugin_check( 'demo-importer-plus/demo-importer-plus.php' ) ) {
                    $button_label = esc_html__( 'Activate Rishi Starter Templates', 'rishi' );
                    $data_action  = 'activate';
                }
            }

            $dash_links = [
                'support'            => apply_filters( 'rishi_dashboard_support_link', 'https://rishitheme.com/support/' ),
                'docs'               => apply_filters( 'rishi_dashboard_docs_link', 'https://rishitheme.com/docs/' ),
                'tutorial'           => apply_filters( 'rishi_dashboard_tutorial_link', 'https://www.youtube.com/channel/UCmrylkZogxYi1s8Yq8ZQNsg' ),
                'agency'             => apply_filters( 'rishi_agency_link', 'https://rishitheme.com/' ),
                'hide_video_link'    => apply_filters( 'rishi_video_link', false ),
                'hide_support_link'  => apply_filters( 'rishi_support_link', false ),
                'hide_doc_link'      => apply_filters( 'rishi_doc_link', false ),
                'hide_starter_sites' => apply_filters( 'rishi_starter_sites', false ),
                'hide_plugins_tab'   => apply_filters( 'rishi_plugins_tab', false ),
                'hide_white_label'   => apply_filters( 'rishi_white_label_extensions', false ),
                'hide_dash_header_info' => apply_filters( 'rishi_dash_header_info', false ),
            ];

            $localize = array(
                'ajaxUrl'              => admin_url( 'admin-ajax.php' ),
                'proActivated'         => class_exists( 'Rishi\Rishi_Pro' ) ? true : false,
                'ActivatingText'       => __( 'Activating', 'rishi' ),
                'DeactivatingText'     => __( 'Deactivating', 'rishi' ),
                'PluginActivateText'   => __( 'Activate', 'rishi' ),
                'PluginDeactivateText' => __( 'Deactivate', 'rishi' ),
                'SettingsText'         => __( 'Settings', 'rishi' ),
                'BrowseTemplates'      => __( 'Browse Starter Templates', 'rishi' ),
                'ThemeVersion'         => RISHI_VERSION,
                'ThemeName'            => RISHI_NAME,
                'customizeURL'         => admin_url('/customize.php?autofocus'),
                'adminURL'             => admin_url(),
                'ajax_nonce' 	   	   => wp_create_nonce( 'rishi-ajax-verification' ),
                'status'           	   => $data_action,
                'starterLabel' 	       => $button_label,
                'starterURL' 	   	   => esc_url( admin_url( 'themes.php?page=demo-importer-plus' ) ),
                'starterTemplates' 	   => defined( 'DEMO_IMPORTER_PLUS_VER' ),
                'dash_header_data' 	   => apply_filters( 'rishi_dashboard_heading_data', [] ),
                'dash_links'           => $dash_links
            );
        
            wp_enqueue_style(
                'rishi-dashboard',
                get_template_directory_uri() . '/dist/dashboard.css',
                [],
                RISHI_VERSION
            );
        
            $dependencies_file_path = get_template_directory() . '/dist/dashboard/dashboard.asset.php';
            if ( file_exists( $dependencies_file_path ) ) {
                $dashboard_assets = require $dependencies_file_path;
                $js_dependencies = ( ! empty( $dashboard_assets['dependencies'] ) ) ? $dashboard_assets['dependencies'] : [];
                $version         = ( ! empty( $dashboard_assets['version'] ) ) ? $dashboard_assets['version'] : RISHI_VERSION;

                wp_enqueue_script(
                    'rishi-dashboard',
                    get_template_directory_uri() . '/dist/dashboard/dashboard.js',
                    $js_dependencies,
                    $version,
                    true
                );
        
                // Add Translation support for Dashboard 
                wp_set_script_translations( 'rishi-dashboard', 'rishi' );
        
                wp_localize_script( 'rishi-dashboard', 'rishi_dashboard', $localize);
            }
        
            $components_dependencies    = get_template_directory() . '/dist/options/options.asset.php';
            if ( file_exists( $components_dependencies ) ) {
                $components_assets = require $components_dependencies;
                $components_js_dependencies = ( ! empty( $components_assets['dependencies'] ) ) ? $components_assets['dependencies'] : [];
                $com_version                = ( ! empty( $components_assets['version'] ) ) ? $components_assets['version'] : RISHI_VERSION;
                wp_enqueue_script(
                    'rishi-components',
                    get_template_directory_uri() . '/dist/options/options.js',
                    $components_js_dependencies,
                    $com_version,
                    true
                );
            }

        }
    }

    /**
     * AJAX callback to install a plugin.
     */
    public function rishi_install_starter_templates() {

        if ( ! check_ajax_referer( 'rishi-ajax-verification', 'security', false ) ) {
            wp_send_json_error( __( 'Security Error, Please reload the page.', 'rishi' ) );
        }
        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error( __( 'Security Error, Need higher Permissions to install plugin.', 'rishi' ) );
        }
        // Get selected file index or set it to 0.
        $status = empty( $_POST['status'] ) ? 'install' : sanitize_text_field( $_POST['status'] );
        if ( ! function_exists( 'plugins_api' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
        if ( ! class_exists( 'WP_Upgrader' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }
        $install = true;
        if ( 'install' === $status ) {
            $api = plugins_api(
                'plugin_information',
                array(
                    'slug' => 'demo-importer-plus',
                    'fields' => array(
                        'short_description' => false,
                        'sections' => false,
                        'requires' => false,
                        'rating' => false,
                        'ratings' => false,
                        'downloaded' => false,
                        'last_updated' => false,
                        'added' => false,
                        'tags' => false,
                        'compatibility' => false,
                        'homepage' => false,
                        'donate_link' => false,
                    ),
                )
            );
            if ( ! is_wp_error( $api ) ) {

                /**
                 * Use AJAX upgrader skin instead of plugin installer skin.
                 * 
                 * ref: function wp_ajax_install_plugin().
                 */
                $upgrader = new \Plugin_Upgrader( new \WP_Ajax_Upgrader_Skin() );

                $installed = $upgrader->install( $api->download_link );
                if ( $installed ) {
                    $activate = activate_plugin( 'demo-importer-plus/demo-importer-plus.php', '', false, true );
                    if ( is_wp_error( $activate ) ) {
                        $install = false;
                    }
                } else {
                    $install = false;
                }
            } else {
                $install = false;
            }
        } elseif ( 'activate' === $status ) {
            $activate = activate_plugin( 'demo-importer-plus/demo-importer-plus.php', '', false, true );
            if ( is_wp_error( $activate ) ) {
                $install = false;
            }
        }

        if ( false === $install ) {
            wp_send_json_error( __( 'Error, plugin could not be installed, please install manually.', 'rishi' ) );
        } else {
            wp_send_json_success();
        }
    }


    /**
     * Active Plugin Check
     *
     * @param string $plugin_base_name is plugin folder/filename.php.
     */
    public function rishi_active_plugin_check( $plugin_base_name ) {

        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        return in_array( $plugin_base_name, $active_plugins, true ) || array_key_exists( $plugin_base_name, $active_plugins );
    }

}

$instance = \Rishi\Dashboard\Rishi_Dashboard::instance();