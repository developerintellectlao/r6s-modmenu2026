<?php
if (!defined('WPVIVID_STAGING_PLUGIN_DIR'))
{
    die;
}

if ( ! class_exists( 'WP_List_Table' ) )
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPvivid_Staging
{
    public $end_shutdown_function;
    public $screen_ids;
    public $plugin_name;
    public $version;

    public $log;
    public $log_page;
    public $pro_page;
    public $new_wp_page;
    public $ui_display;
    public $setting;
    public $option;

    public $updater;

    public $task;

    public function __construct()
    {
        $this->version = WPVIVID_STAGING_VERSION;
        $this->plugin_name = WPVIVID_STAGING_SLUG;

        include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-crypt.php';
        include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-connect-server.php';
        include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-updater.php';
        include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-option.php';

        $this->updater=new WPvivid_Staging_Updater();
        $this->option=new WPvivid_Staging_Option();

        $this->task=false;

        if(is_admin())
        {
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-new-staging-task.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-new-staging-push-task.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-new-staging-push-task-ex.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-new-staging-copy-task.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-log.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-log-page.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-pro-page.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-ui-display.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-setting.php';
            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-sites-list.php';

            $this->log=new WPvivid_Staging_Log();
            $this->log_page=new WPvivid_Staging_Log_Page();
            $this->pro_page=new WPvivid_Staging_pro_page();
            $this->ui_display=new WPvivid_Staging_UI_Display();
            $this->setting=new WPvivid_Staging_Setting();


            $this->screen_ids=array();
            if(is_multisite())
            {
                //
                $this->screen_ids[]='toplevel_page_'.$this->plugin_name.'-network';
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-log-network';
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-setting-network';
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-pro-network';
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-newwp-network';
            }
            else
            {
                $this->screen_ids[]='toplevel_page_'.$this->plugin_name;
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-log';
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-setting';
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-pro';
                $this->screen_ids[]='wpvivid-staging_page_wpvividstg-newwp';
            }

            add_filter('wpvivid_get_staging_screen_ids', array($this,'get_staging_screen_ids'), 9999);

            add_action('admin_enqueue_scripts',array( $this,'enqueue_styles'));
            add_action('admin_enqueue_scripts',array( $this,'enqueue_scripts'));

            add_filter('wpvivid_get_staging_admin_menus', array($this, 'get_staging_admin_menus'), 9999);
            add_filter('wpvivid_get_staging_menu_slug', array($this, 'get_staging_menu_slug'));

            if(is_multisite())
            {
                add_action('network_admin_menu',array( $this,'add_plugin_admin_menu'));
            }
            else
            {
                add_action('admin_menu',array( $this,'add_plugin_admin_menu'));
            }

            $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . 'wpvivid-staging.php' );
            add_filter('plugin_action_links_' . $plugin_basename, array( $this,'add_action_links'));
            add_filter( 'plugin_row_meta', array($this,'filter_plugin_row_meta'), 10, 4 );
            add_filter('wpvivid_export_setting_addon', array($this, 'export_setting_addon'), 11);

            add_action('wpvivid_before_setup_page',array($this,'show_staging_remote_notices'));
            add_action( 'admin_notices', array($this,'check_need_reset_schedules'));
            add_filter('wpvivid_add_staging_side_bar', array($this, 'add_staging_side_bar'), 11, 2);

            $this->load_ajax();
        }

        add_filter('wpvividstg_get_admin_url',array($this,'get_admin_url'),10);

        add_filter('wpvivid_get_login_domain',array($this,'get_login_domain'),10);

        add_action( "init",array($this,'staging_site'),9);

        add_action( "init",array($this,'update_options'));

        $this->check_need_lower_prefix();

        add_filter('wpvividstg_get_table_prefix', array($this, 'get_table_prefix'), 10);
    }

    public function load_ajax()
    {
        add_action('wp_ajax_wpvividstg_delete_site_ex', array($this, 'delete_site_ex'));

        add_action('wp_ajax_wpvivid_scan_exist_staging', array($this, 'scan_exist_staging'));
        add_action('wp_ajax_wpvividstg_delete_cancel_staging_site', array($this, 'delete_cancel_staging_site'));
        add_action('wp_ajax_wpvividstg_check_staging_dir', array($this, 'check_staging_dir'));
        add_action('wp_ajax_wpvividstg_check_filesystem_permissions', array($this, 'check_filesystem_permissions'));
        add_action('wp_ajax_wpvividstg_push_site', array($this, 'push_site'));
        add_action('wp_ajax_wpvividstg_copy_site', array($this, 'copy_site'));
        add_action('wp_ajax_wpvividstg_edit_staging_comment', array($this, 'edit_staging_comment'));
        //
        add_action('wp_ajax_wpvividstg_get_mu_site_info', array($this, 'get_mu_site_info'));
        add_action('wp_ajax_wpvividstg_get_mu_site_info_ex', array($this, 'get_mu_site_info_ex'));

        add_action('wp_ajax_wpvividstg_push_start_staging_ex', array($this, 'push_start_staging_ex'));
        add_action('wp_ajax_wpvividstg_get_staging_push_progress_ex', array($this, 'get_staging_progress_ex'));
        add_action('wp_ajax_wpvividstg_push_restart_staging_ex', array($this, 'push_restart_staging_ex'));
        add_action('wp_ajax_wpvividstg_finish_push_staging', array($this, 'finish_push_staging'));
        add_action('wp_ajax_wpvividstg_push_staging_failed', array($this, 'push_staging_failed'));

        add_action('wp_ajax_wpvividstg_copy_start_staging_ex', array($this, 'copy_start_staging_ex'));
        add_action('wp_ajax_wpvividstg_get_staging_copy_progress_ex', array($this, 'get_staging_copy_progress_ex'));
        add_action('wp_ajax_wpvividstg_copy_restart_staging_ex', array($this, 'copy_restart_staging_ex'));
        add_action('wp_ajax_wpvividstg_finish_copy_staging', array($this, 'finish_copy_staging'));
        add_action('wp_ajax_wpvividstg_copy_staging_failed', array($this, 'copy_staging_failed'));

        add_action('wp_ajax_wpvivid_staging_start_push', array($this, 'staging_start_push'));
        add_action('wp_ajax_wpvivid_get_staging_push_progress', array($this, 'get_staging_push_progress'));
        add_action('wp_ajax_wpvivid_staging_restart_push', array($this, 'staging_restart_push'));
        add_action('wp_ajax_wpvivid_staging_push_finish', array($this, 'staging_push_finish'));
        add_action('wp_ajax_wpvivid_staging_push_failed', array($this, 'staging_push_failed'));

        add_action('wp_ajax_wpvividstg_get_custom_database_tables_info',array($this, 'get_custom_database_tables_info'));

        add_action('wp_ajax_wpvividstg_cancel_staging', array($this, 'cancel_staging'));
        add_action('wp_ajax_wpvividstg_test_additional_database_connect', array($this, 'test_additional_database_connect'));
        add_action('wp_ajax_wpvividstg_update_staging_exclude_extension', array($this, 'update_staging_exclude_extension'));

        add_action('wp_ajax_wpvivid_get_mu_list', array($this, 'get_mu_list'));
        //

        add_action('wp_ajax_wpvividstg_get_custom_database_size', array($this, 'get_custom_database_size'));
        add_action('wp_ajax_wpvividstg_get_custom_files_size', array($this, 'get_custom_files_size'));
        add_action('wp_ajax_wpvividstg_get_custom_include_path', array($this, 'get_custom_include_path'));
        add_action('wp_ajax_wpvividstg_get_custom_exclude_path', array($this, 'get_custom_exclude_path'));
        add_action('wp_ajax_wpvividstg_get_custom_themes_plugins_info_ex', array($this, 'get_custom_themes_plugins_info_ex'));

        add_action('wp_ajax_wpvividstg_hide_staging_remote_notice', array($this, 'hide_staging_remote_notice'));

        add_action('wp_ajax_wpvividstg_get_staging_site_url', array($this, 'get_staging_site_url'));
    }

    public function check_need_lower_prefix()
    {
        global $wpdb;
        $result = $wpdb->get_row('SHOW VARIABLES LIKE \'lower_case_table_names\';', ARRAY_A);
        if ( ! empty( $result ) && array_key_exists( 'Value', $result ) )
        {
            if($result['Value'] == '1')
            {
                define('WPVIVID_STAGING_LOW_CASE_TABLE_NAME', true);
            }
        }
    }

    public function get_table_prefix($prefix)
    {
        if ( defined( 'WPVIVID_STAGING_LOW_CASE_TABLE_NAME' ) && preg_match('/[A-Z]/', $prefix) )
        {
            $prefix = strtolower($prefix);
        }
        return $prefix;
    }

    public function get_staging_site_url()
    {
        $this->ajax_check_security();
        try
        {
            if(isset($_POST['id'])&&!empty($_POST['id']))
            {
                $id = sanitize_key($_POST['id']);
                global $wpvivid_staging;
                $list = $wpvivid_staging->option->get_option('staging_site_data');
                if(isset($list[$id]))
                {
                    $site_data=$list[$id];
                    if($this->staging_version($site_data)===false)
                    {
                        $this->update_staging_version($site_data);
                    }
                    $this->reset_staging_data($site_data);
                    //
                    $this->check_staging_plugin_active($site_data);

                    if(!is_multisite())
                    {
                        $active_plugins = get_option('active_plugins');
                    }
                    else
                    {
                        $active_plugins = array();
                        //network active
                        $mu_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
                        if(!empty($mu_active_plugins)){
                            foreach ($mu_active_plugins as $plugin_name => $data){
                                $active_plugins[] = $plugin_name;
                            }
                        }
                    }
                    if(!function_exists('get_plugins'))
                        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
                    $plugins=get_plugins();
                    $pro_wpvivid_slug='wpvivid-backup-pro/wpvivid-backup-pro.php';
                    $is_active_pro=false;
                    if(!empty($plugins))
                    {
                        if(isset($plugins[$pro_wpvivid_slug]))
                        {
                            if(in_array($pro_wpvivid_slug, $active_plugins))
                            {
                                $is_active_pro=true;
                            }
                        }
                    }

                    if(is_multisite() && !$list[$id]['mu_single'])
                    {
                        if($is_active_pro)
                        {
                            $staging_site_url = $list[$id]['site_url'].'/wp-admin/network/admin.php?page='.strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                        }
                        else
                        {
                            $staging_site_url = $list[$id]['site_url'].'/wp-admin/network/admin.php?page='.strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvividstg')));
                        }
                    }
                    else
                    {
                        if($is_active_pro)
                        {
                            $staging_site_url = $list[$id]['site_url'].'/wp-admin/admin.php?page='.strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                        }
                        else
                        {
                            $staging_site_url = $list[$id]['site_url'].'/wp-admin/admin.php?page='.strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvividstg')));
                        }
                    }
                    $ret['staging_site_url'] = $staging_site_url;
                    $ret['result'] = 'success';
                }
                else
                {
                    $ret['result']='failed';
                    $ret['error']='site not found';
                }
                echo json_encode($ret);
            }
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function staging_version($site_data)
    {
        $plugins_path = $site_data['path'] . '/wp-content/plugins/';
        $plugin_slug="wpvivid-staging/wpvivid-staging.php";
        $plugin_data = get_plugin_data( $plugins_path.$plugin_slug, false, true);
        if(empty($plugin_data))
        {
            return false;
        }

        $version=$plugin_data['Version'];
        if(version_compare('2.0.14',$version,'>'))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function update_staging_version($site_data)
    {
        $des_plugins_path = $site_data['path'] . '/wp-content/plugins/wpvivid-staging/';
        $src_plugins_path = WPVIVID_STAGING_PLUGIN_DIR;

        if( ! function_exists('plugins_api') )
        {
            require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        }

        if(!class_exists('WP_Upgrader'))
            require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

        if(!class_exists('Plugin_Upgrader'))
            require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );

        $upgrader = new Plugin_Upgrader();
        WP_Filesystem();
        $upgrader->clear_destination($des_plugins_path);
        $this->recurseCopy($src_plugins_path,$des_plugins_path);
    }

    public function recurseCopy($sourceDirectory, $destinationDirectory, $childFolder = '')
    {
        $directory = opendir($sourceDirectory);

        if (is_dir($destinationDirectory) === false)
        {
            @mkdir($destinationDirectory,0755,true);
        }

        if ($childFolder !== '')
        {
            if (is_dir("$destinationDirectory/$childFolder") === false)
            {
                @mkdir("$destinationDirectory/$childFolder");
            }

            while (($file = readdir($directory)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                if (is_dir("$sourceDirectory/$file") === true)
                {
                    $this->recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                } else {
                    @copy("$sourceDirectory/$file", "$destinationDirectory/$childFolder/$file");
                }
            }

            @closedir($directory);

            return;
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                $this->recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
            else {
                @copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }

        closedir($directory);
    }

    public function check_staging_plugin_active($site_data)
    {
        global $wpdb;
        $prefix=$site_data['prefix'];
        if($site_data['db_connect']['use_additional_db']===false)
        {
            $db_des_instance=$wpdb;
        }
        else
        {
            $db_des_instance=new wpdb($site_data['db_connect']['dbuser'],
                $site_data['db_connect']['dbpassword'],
                $site_data['db_connect']['dbname'],
                $site_data['db_connect']['dbhost']);
        }

        if(is_multisite() && !$site_data['mu_single'])
        {
            $staging_is_mu = true;
            $wpvivid_options_table=$prefix.'sitemeta';
            $option_name='active_sitewide_plugins';
            $table_key = 'meta_key';
            $table_value = 'meta_value';
        }
        else
        {
            $staging_is_mu = false;
            $wpvivid_options_table=$prefix.'options';
            $option_name='active_plugins';
            $table_key = 'option_name';
            $table_value = 'option_value';
        }

        $active_plugin_res = $db_des_instance->get_results( $db_des_instance->prepare( 'SELECT `'.$table_value.'` FROM `'.$wpvivid_options_table.'` WHERE `'.$table_key.'` = %s', $option_name ) );
        if(!empty($active_plugin_res))
        {
            foreach ( $active_plugin_res as $value )
            {
                if($staging_is_mu)
                {
                    $active_plugin = $value->meta_value;
                    $active_plugin = maybe_unserialize( $active_plugin );
                    $staging_pro_slug='wpvivid-staging/wpvivid-staging.php';
                    if (!array_key_exists($staging_pro_slug, $active_plugin))
                    {
                        $active_plugin[$staging_pro_slug] = time();
                    }
                    $active_plugin = maybe_serialize( $active_plugin );
                    $update_query =$db_des_instance->prepare('UPDATE `'.$wpvivid_options_table.'` SET `'.$table_value.'` = %s WHERE `'.$table_key.'` = %s', $active_plugin, $option_name);
                    if ($db_des_instance->get_results($update_query)===false)
                    {
                    }
                }
                else
                {
                    $active_plugin = $value->option_value;
                    $active_plugin = maybe_unserialize( $active_plugin );
                    $staging_pro_slug='wpvivid-staging/wpvivid-staging.php';
                    if(!in_array($staging_pro_slug, $active_plugin))
                    {
                        $active_plugin[] = $staging_pro_slug;
                    }
                    $active_plugin = maybe_serialize( $active_plugin );
                    $update_query =$db_des_instance->prepare('UPDATE `'.$wpvivid_options_table.'` SET `'.$table_value.'` = %s WHERE `'.$table_key.'` = %s', $active_plugin, $option_name);
                    if ($db_des_instance->get_results($update_query)===false)
                    {
                    }

                }
            }
        }
    }

    public function check_plugin_active($plugin_slug)
    {
        $is_plugin_actived=false;
        if(!function_exists('get_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (is_multisite())
        {
            $active_plugins = array();
            //network active
            $mu_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
            if(!empty($mu_active_plugins)){
                foreach ($mu_active_plugins as $plugin_name => $data){
                    $active_plugins[] = $plugin_name;
                }
            }
            $plugins=get_mu_plugins();
            if(count($plugins) == 0 || !isset($plugins[$plugin_slug])){
                $plugins=get_plugins();
            }
        }
        else
        {
            $active_plugins = get_option('active_plugins');
            $plugins=get_plugins();
        }

        if(!empty($plugins))
        {
            if(isset($plugins[$plugin_slug]))
            {
                if(in_array($plugin_slug, $active_plugins))
                {
                    $is_plugin_actived=true;
                }
                else
                {
                    $is_plugin_actived=false;
                }
            }
            else
            {
                $is_plugin_actived=false;
            }
        }
        else
        {
            $is_plugin_actived=false;
        }

        return $is_plugin_actived;
    }

    public function switch_to_blog( $new_blog_id, $deprecated = null ) {
        global $wpdb;

        $prev_blog_id = get_current_blog_id();
        if ( empty( $new_blog_id ) ) {
            $new_blog_id = $prev_blog_id;
        }

        $GLOBALS['_wp_switched_stack'][] = $prev_blog_id;

        /*
         * If we're switching to the same blog id that we're on,
         * set the right vars, do the associated actions, but skip
         * the extra unnecessary work
         */
        if ( $new_blog_id == $prev_blog_id ) {

            $GLOBALS['switched'] = true;

            return true;
        }

        $wpdb->set_blog_id( $new_blog_id );
        $GLOBALS['table_prefix'] = $wpdb->get_blog_prefix();
        $GLOBALS['blog_id']      = $new_blog_id;

        if ( function_exists( 'wp_cache_switch_to_blog' ) ) {
            wp_cache_switch_to_blog( $new_blog_id );
        } else {
            global $wp_object_cache;

            if ( is_object( $wp_object_cache ) && isset( $wp_object_cache->global_groups ) ) {
                $global_groups = $wp_object_cache->global_groups;
            } else {
                $global_groups = false;
            }

            if( !function_exists('wp_cache_init') ) {
                require_once ABSPATH . WPINC . '/cache.php';
            }
            if( function_exists('wp_cache_init') ) {
                wp_cache_init();
            }

            if ( function_exists( 'wp_cache_add_global_groups' ) ) {
                if ( is_array( $global_groups ) ) {
                    wp_cache_add_global_groups( $global_groups );
                } else {
                    wp_cache_add_global_groups(
                        array(
                            'blog-details',
                            'blog-id-cache',
                            'blog-lookup',
                            'blog_meta',
                            'global-posts',
                            'networks',
                            'network-queries',
                            'sites',
                            'site-details',
                            'site-options',
                            'site-queries',
                            'site-transient',
                            'rss',
                            'users',
                            'user-queries',
                            'user_meta',
                            'useremail',
                            'userlogins',
                            'userslugs',
                        )
                    );
                }

                wp_cache_add_non_persistent_groups( array( 'counts', 'plugins', 'theme_json' ) );
            }
        }

        $GLOBALS['switched'] = true;

        return true;
    }

    public function restore_current_blog() {
        global $wpdb;

        if ( empty( $GLOBALS['_wp_switched_stack'] ) ) {
            return false;
        }

        $new_blog_id  = array_pop( $GLOBALS['_wp_switched_stack'] );
        $prev_blog_id = get_current_blog_id();

        if ( $new_blog_id == $prev_blog_id ) {

            // If we still have items in the switched stack, consider ourselves still 'switched'.
            $GLOBALS['switched'] = ! empty( $GLOBALS['_wp_switched_stack'] );

            return true;
        }

        $wpdb->set_blog_id( $new_blog_id );
        $GLOBALS['blog_id']      = $new_blog_id;
        $GLOBALS['table_prefix'] = $wpdb->get_blog_prefix();

        if ( function_exists( 'wp_cache_switch_to_blog' ) ) {
            wp_cache_switch_to_blog( $new_blog_id );
        } else {
            global $wp_object_cache;

            if ( is_object( $wp_object_cache ) && isset( $wp_object_cache->global_groups ) ) {
                $global_groups = $wp_object_cache->global_groups;
            } else {
                $global_groups = false;
            }

            if( !function_exists('wp_cache_init') ) {
                require_once ABSPATH . WPINC . '/cache.php';
            }
            if( function_exists('wp_cache_init') ) {
                wp_cache_init();
            }

            if ( function_exists( 'wp_cache_add_global_groups' ) ) {
                if ( is_array( $global_groups ) ) {
                    wp_cache_add_global_groups( $global_groups );
                } else {
                    wp_cache_add_global_groups(
                        array(
                            'blog-details',
                            'blog-id-cache',
                            'blog-lookup',
                            'blog_meta',
                            'global-posts',
                            'networks',
                            'network-queries',
                            'sites',
                            'site-details',
                            'site-options',
                            'site-queries',
                            'site-transient',
                            'rss',
                            'users',
                            'user-queries',
                            'user_meta',
                            'useremail',
                            'userlogins',
                            'userslugs',
                        )
                    );
                }

                wp_cache_add_non_persistent_groups( array( 'counts', 'plugins', 'theme_json' ) );
            }
        }

        // If we still have items in the switched stack, consider ourselves still 'switched'.
        $GLOBALS['switched'] = ! empty( $GLOBALS['_wp_switched_stack'] );

        return true;
    }

    public function update_options()
    {
        if(!empty($this->get_staging_site_data_ex()))
        {
            return;
        }

        if(is_multisite())
        {
            $polylang_slug='polylang/polylang.php';
            if($this->check_plugin_active($polylang_slug))
            {
                $this->switch_to_blog(get_main_network_id());
            }
            else
            {
                switch_to_blog(get_main_network_id());
            }
        }

        $option=new WPvivid_Staging_Option();

        $old_db_version=$option->get_option('staging_db_version');
        if(empty($old_db_version))
        {
            $old_db_version= '0.0';
        }

        if(version_compare($old_db_version, '1.0.1', '<'))
        {
            $task_list=$option->get_option('wpvivid_staging_task_list');
            if(empty($task_list))
            {
                $task_list= get_option('wpvivid_staging_task_list',array());
            }

            if(!empty($task_list))
            {
                $new_sites=array();

                foreach ($task_list as $site_id => $old_site)
                {
                    $new_site=array();
                    $new_site['id']=$old_site['id'];
                    $new_site['create_time']=$old_site['create_time'];
                    $new_site['comment']=isset($old_site['staging_comment'])?$old_site['staging_comment']:'';

                    $new_site['path']=$old_site['site']['path'];
                    $new_site['site_url']=$old_site['site']['site_url'];
                    $new_site['home_url']=$old_site['site']['home_url'];
                    $new_site['prefix']=$old_site['site']['prefix'];
                    $new_site['old_prefix']=$old_site['db_connect']['old_prefix'];
                    $new_site['db_connect']['use_additional_db']=isset($old_site['site']['db_connect']['use_additional_db'])?$old_site['site']['db_connect']['use_additional_db']:false;

                    if($new_site['db_connect']['use_additional_db'])
                    {
                        $new_site['db_connect']['dbuser']=$old_site['site']['db_connect']['dbuser'];
                        $new_site['db_connect']['dbpassword']=$old_site['site']['db_connect']['dbpassword'];
                        $new_site['db_connect']['dbname']=$old_site['site']['db_connect']['dbname'];
                        $new_site['db_connect']['dbhost']=$old_site['site']['db_connect']['dbhost'];
                    }

                    $new_site['fresh_install']=isset($old_site['site']['fresh_install'])?$old_site['site']['fresh_install']:false;
                    $new_site['log_file_name']=isset($old_site['log_file_name'])?$old_site['log_file_name']:'';

                    $new_site['permalink_structure']=isset($old_site['permalink_structure'])?$old_site['permalink_structure']:'';
                    $new_site['login_url']=isset($old_site['login_url'])?$old_site['login_url']:'';
                    $new_site['is_create_subdomain']=isset($old_site['is_create_subdomain'])?$old_site['is_create_subdomain']:'';

                    if(isset($old_site['site']['mu_single']))
                    {
                        $new_site['mu_single']=true;
                        $new_site['mu_single_site_id']=$old_site['site']['mu_single_site_id'];
                    }

                    if(isset($old_site['site']['path_current_site']))
                    {
                        $new_site['path_current_site']=$old_site['site']['path_current_site'];
                        $new_site['main_site_id']=$old_site['site']['main_site_id'];
                    }

                    $new_sites[$new_site['id']]=$new_site;
                }

                $option->update_option('staging_site_data', $new_sites);
            }

            $list=$option->get_option('staging_site_data');
            if(!empty($list))
            {
                foreach ($list as $site_data)
                {
                    $this->reset_staging_data($site_data);
                }
            }

            $option->update_option('staging_db_version','1.0.1');
        }


        if(is_multisite())
        {
            $polylang_slug='polylang/polylang.php';
            if($this->check_plugin_active($polylang_slug))
            {
                $this->restore_current_blog();
            }
            else
            {
                restore_current_blog();
            }
        }
    }

    public function reset_all_staging_data()
    {
        $list=$this->option->get_option('staging_site_data');
        if(!empty($list))
        {
            foreach ($list as $site_data)
            {
                $this->reset_staging_data($site_data);
            }
        }
    }

    public function show_staging_remote_notices()
    {
        $data=$this->get_staging_site_data();

        if($data!==false)
        {
            if ( ! function_exists( 'is_plugin_active' ) )
            {
                include_once(ABSPATH.'wp-admin/includes/plugin.php');
            }
            if(is_plugin_active('wpvivid-backup-pro/wpvivid-backup-pro.php'))
            {
                if(is_multisite())
                {
                    switch_to_blog(get_main_network_id());
                    $staging_remote_notice=get_option('wpvivid_staging_remote_notice',false);
                    restore_current_blog();
                }
                else
                {
                    $staging_remote_notice=get_option('wpvivid_staging_remote_notice',false);
                }

                if($staging_remote_notice == '1' || $staging_remote_notice === 1)
                {
                    ?>
                    <div class="notice notice-warning notice-staging-remote-warnning is-dismissible">
                        <p>
                            <strong>Warning:</strong> As the domain has changed, please change the remote backup folder accordingly. <a href="<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvivid-remote', 'wpvivid-remote'); ?>">Edit Now</a>
                        </p>
                    </div>
                    <?php
                }
            }
        }
        ?>
        <script>
            jQuery('.notice-staging-remote-warnning').on("click", '.notice-dismiss', function() {
                var ajax_data = {
                    'action':'wpvividstg_hide_staging_remote_notice',
                };
                wpvivid_post_request(ajax_data, function(res){
                }, function(XMLHttpRequest, textStatus, errorThrown) {
                });
            });
        </script>
        <?php
    }

    public static function get_start_time($time,$local_time=true)
    {
        if(!is_array( $time ) )
        {
            return false;
        }

        if(!isset($time['type']))
        {
            return false;
        }

        $week=$time['start_time']['week'];
        $day=$time['start_time']['day'];
        $current_day=$time['start_time']['current_day'];

        if(strtotime('now')>strtotime($current_day)){
            $daily_start_time = $current_day.' +1 day';
        }
        else{
            $daily_start_time = $current_day;
        }

        $weekly_tmp = $week.' '.$current_day;
        if(strtotime('now')>strtotime($weekly_tmp)) {
            $weekly_start_time = $week.' '.$weekly_tmp.' next week';
        }
        else{
            $weekly_start_time = $weekly_tmp;
        }

        $date_now = date("Y-m-",time());
        $monthly_tmp = $date_now.$day.' '.$current_day;
        if(strtotime('now')>strtotime($monthly_tmp)){
            $date_now = date("Y-m-",strtotime('first day of next month'));
            $monthly_start_time = $date_now.$day.' '.$current_day;
        }
        else{
            $monthly_start_time = $monthly_tmp;
        }

        $schedule_type_ex = array(
            'wpvivid_hourly'=>'Every hour',
            'wpvivid_2hours'=>'Every 2 hours',
            'wpvivid_4hours'=>'Every 4 hours',
            'wpvivid_6hours'=>'Every 6 hours',
            'wpvivid_8hours'=>'Every 8 hours',
            'wpvivid_12hours'=>'12Hours',
            'twicedaily'=>'12Hours',
            'wpvivid_daily'=>'Daily',
            'wpvivid_2days'=>'Every 2 days',
            'wpvivid_3days'=>'Every 3 days',
            'daily'=>'Daily',
            'onceday'=>'Daily',
            'wpvivid_weekly'=>'Weekly',
            'weekly'=>'Weekly',
            'wpvivid_fortnightly'=>'Fortnightly',
            'fortnightly'=>'Fortnightly',
            'wpvivid_monthly'=>'Monthly',
            'monthly'=>'Monthly',
            'montly'=>'Monthly'
        );

        $display_array = array(
            'Every hour'=>$daily_start_time,
            'Every 2 hours'=>$daily_start_time,
            'Every 4 hours'=>$daily_start_time,
            'Every 6 hours'=>$daily_start_time,
            'Every 8 hours'=>$daily_start_time,
            'Every 12 hours'=>$daily_start_time,
            'wpvivid_12hours'=>'12Hours',
            "12Hours"=>$daily_start_time,
            "Daily"=>$daily_start_time,
            "Every 2 days"=>$daily_start_time,
            'Every 3 days'=>$daily_start_time,
            "Weekly"=>$weekly_start_time,
            "Fortnightly"=>$weekly_start_time,
            "Monthly"=>$monthly_start_time
        );
        foreach ($schedule_type_ex as $key => $value)
        {
            if($key == $time['type'])
            {
                foreach ($display_array as $display_key => $display_value)
                {
                    if($value == $display_key)
                    {
                        if($local_time)
                        {
                            $offset=get_option('gmt_offset');
                            $offset=$offset * 60 * 60;
                            return strtotime($display_value)-$offset;
                        }
                        else
                        {
                            return strtotime($display_value);
                        }
                    }
                }
            }
        }
        return false;
    }

    public function reset_incremental_schedule_start_time($schedule)
    {
        //set file start time
        if(isset($schedule['incremental_recurrence'])){
            $time['type']=$schedule['incremental_recurrence'];
        }
        else{
            $time['type']='wpvivid_weekly';
        }
        if(isset($schedule['incremental_recurrence_week'])) {
            $time['start_time']['week']=$schedule['incremental_recurrence_week'];
        }
        else
            $time['start_time']['week']='mon';
        if(isset($schedule['incremental_recurrence_day'])) {
            $time['start_time']['day']=$schedule['incremental_recurrence_day'];
        }
        else
            $time['start_time']['day']='01';
        if(isset($schedule['files_current_day'])) {
            $time['start_time']['current_day']=$schedule['files_current_day'];
        }
        else
            $time['start_time']['current_day']="00:00";

        $timestamp=self::get_start_time($time);
        $schedule['files_start_time']=$timestamp;

        //set db start time
        if(isset($schedule['incremental_db_recurrence'])){
            $time['type']=$schedule['incremental_db_recurrence'];
        }
        else{
            $time['type']='wpvivid_weekly';
        }
        if(isset($schedule['incremental_db_recurrence_week'])) {
            $time['start_time']['week']=$schedule['incremental_db_recurrence_week'];
        }
        else
            $time['start_time']['week']='mon';
        if(isset($schedule['incremental_db_recurrence_day'])) {
            $time['start_time']['day']=$schedule['incremental_db_recurrence_day'];
        }
        else
            $time['start_time']['day']='01';
        if(isset($schedule['db_current_day'])) {
            $time['start_time']['current_day']=$schedule['db_current_day'];
        }
        else
            $time['start_time']['current_day']="00:00";
        $timestamp=self::get_start_time($time);
        $schedule['db_start_time']=$timestamp;

        return $schedule;
    }

    public function check_need_reset_schedules()
    {
        if ( ! function_exists( 'is_plugin_active' ) )
        {
            include_once(ABSPATH.'wp-admin/includes/plugin.php');
        }
        if(is_plugin_active('wpvivid-backup-pro/wpvivid-backup-pro.php'))
        {
            if(is_multisite())
            {
                switch_to_blog(get_main_network_id());
                $staging_need_reset_schedules=get_option('wpvivid_staging_need_reset_schedules',false);
                restore_current_blog();
            }
            else
            {
                $staging_need_reset_schedules=get_option('wpvivid_staging_need_reset_schedules',false);
            }

            if($staging_need_reset_schedules == '1' || $staging_need_reset_schedules === 1)
            {
                $enable_incremental_schedules=get_option('wpvivid_enable_incremental_schedules', false);
                if($enable_incremental_schedules)
                {
                    $need_remove_schedules=array();
                    $crons = _get_cron_array();

                    foreach ( $crons as $cronhooks )
                    {
                        foreach ($cronhooks as $hook_name=>$hook_schedules)
                        {
                            if(preg_match('#wpvivid_incremental_.*#',$hook_name))
                            {
                                foreach ($hook_schedules as $data)
                                {
                                    $need_remove_schedules[$hook_name]=$data['args'];
                                }
                            }
                            if (preg_match('#wpvivid_schedule_event.*#', $hook_name))
                            {
                                foreach ($hook_schedules as $data)
                                {
                                    $need_remove_schedules[$hook_name] = $data['args'];
                                }
                            }
                        }
                    }

                    foreach ($need_remove_schedules as $hook_name=>$args)
                    {
                        wp_clear_scheduled_hook($hook_name, $args);
                        $timestamp = wp_next_scheduled($hook_name, array($args));
                        wp_unschedule_event($timestamp,$hook_name,array($args));
                    }

                    $incremental_schedules=get_option('wpvivid_incremental_schedules');
                    $schedule_data=array_shift($incremental_schedules);

                    $schedule_data = $this->reset_incremental_schedule_start_time($schedule_data);

                    $is_mainwp=false;
                    if(wp_get_schedule($schedule_data['files_schedule_id'], array($schedule_data['id'])))
                    {
                        wp_clear_scheduled_hook($schedule_data['files_schedule_id'], array($schedule_data['id']));
                        $timestamp = wp_next_scheduled($schedule_data['files_schedule_id'], array($schedule_data['id']));
                        wp_unschedule_event($timestamp,$schedule_data['files_schedule_id'],array($schedule_data['id']));
                    }

                    if(wp_get_schedule($schedule_data['db_schedule_id'], array($schedule_data['id'])))
                    {
                        wp_clear_scheduled_hook($schedule_data['db_schedule_id'], array($schedule_data['id']));
                        $timestamp = wp_next_scheduled($schedule_data['db_schedule_id'], array($schedule_data['id']));
                        wp_unschedule_event($timestamp,$schedule_data['db_schedule_id'],array($schedule_data['id']));
                    }

                    wp_schedule_event($schedule_data['db_start_time'], $schedule_data['incremental_db_recurrence'], $schedule_data['db_schedule_id'],array($schedule_data['id']));

                    if(isset($schedule_data['incremental_files_start_backup'])&&$schedule_data['incremental_files_start_backup'])
                    {
                        wp_schedule_single_event(time() + 10, $schedule_data['files_schedule_id'],array($schedule_data['id']));

                        wp_schedule_single_event(time() + 10, $schedule_data['db_schedule_id'],array($schedule_data['id']));
                    }

                    wp_schedule_event($schedule_data['files_start_time'], $schedule_data['incremental_files_recurrence'], $schedule_data['files_schedule_id'],array($schedule_data['id']));

                    wp_schedule_single_event($schedule_data['files_start_time'] + 600, $schedule_data['db_schedule_id'],array($schedule_data['id']));
                }
                else
                {
                    $need_remove_schedules = array();
                    $crons = _get_cron_array();

                    foreach ($crons as $cronhooks) {
                        foreach ($cronhooks as $hook_name => $hook_schedules) {
                            if (preg_match('#wpvivid_incremental_.*#', $hook_name)) {
                                foreach ($hook_schedules as $data) {
                                    $need_remove_schedules[$hook_name] = $data['args'];
                                }
                            }
                            if (preg_match('#wpvivid_schedule_event.*#', $hook_name)) {
                                foreach ($hook_schedules as $data) {
                                    $need_remove_schedules[$hook_name] = $data['args'];
                                }
                            }
                        }
                    }

                    foreach ($need_remove_schedules as $hook_name => $args) {
                        wp_clear_scheduled_hook($hook_name, $args);
                        $timestamp = wp_next_scheduled($hook_name, $args);
                        wp_unschedule_event($timestamp, $hook_name, array($args));
                    }


                    $default=array();
                    $schedules = get_option('wpvivid_schedule_addon_setting', $default);

                    foreach ($schedules as $schedule)
                    {
                        if($schedule['status'] === 'Active')
                        {
                            $timestamp=wp_next_scheduled($schedule['id'], array($schedule['id']));
                            if($timestamp===false)
                            {
                                if(isset($schedule['week']))
                                {
                                    $time['start_time']['week']=$schedule['week'];
                                }
                                else
                                {
                                    $time['start_time']['week']='mon';
                                }

                                if(isset($schedule['day']))
                                {
                                    $time['start_time']['day']=$schedule['day'];
                                }
                                else
                                {
                                    $time['start_time']['day']='01';
                                }


                                if(isset($schedule['current_day']))
                                {
                                    $time['start_time']['current_day']=$schedule['current_day'];
                                }
                                else
                                    $time['start_time']['current_day']="00:00";

                                $timestamp=self::get_start_time($time);

                                wp_schedule_event($timestamp, $schedule['type'], $schedule['id'],array($schedule['id']));
                            }
                        }
                    }
                }

                delete_option('wpvivid_staging_need_reset_schedules');
            }
        }
    }

    public function get_admin_url($admin_url)
    {
        if(is_multisite())
        {
            $admin_url = network_admin_url();
        }
        else
        {
            $admin_url =admin_url();
        }

        return $admin_url;
    }

    public function get_login_domain($domain)
    {
        $data=$this->get_staging_site_data();

        if($data!==false)
        {
            if(isset($data['live_site_url']))
                $domain = $data['live_site_url'];
        }

        return $domain;
    }

    public function add_staging_side_bar($html, $show_schedule)
    {
        $html = '<h2 style="margin-top:0.5em;">
                     <span class="dashicons dashicons-sticky wpvivid-dashicons-orange"></span>
                     <span>Troubleshooting</span>
                 </h2>
                 <div class="inside" style="padding-top:0;">
                     <ul class="" >
                        <li style="border-top:1px solid #f1f1f1;"><span class="dashicons dashicons-editor-help wpvivid-dashicons-orange" ></span>
                            <a href="https://docs.wpvivid.com/troubleshooting-issues-wpvivid-staging-pro.html"><b>Troubleshooting</b></a>
                            <small><span style="float: right;"><a href="https://wpvivid.com/troubleshooting-issues-wpvivid-staging-pro" style="text-decoration: none;" target="_blank"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                        </li>
                     </ul>
                 </div>
                 
                 <h2>
                     <span class="dashicons dashicons-book-alt wpvivid-dashicons-orange" ></span>
                     <span>Documentation</span>
                 </h2>
                 <div class="inside" style="padding-top:0;">
                     <ul class="">
                        <li style="border-top:1px solid #f1f1f1;"><span class="dashicons dashicons-migrate wpvivid-dashicons-blue"></span>
                            <a href="https://docs.wpvivid.com/wpvivid-backup-pro-create-staging-site.html"><b>Create A Staging Site</b></a>
                            <small><span style="float: right;"><a href="https://wpvivid.com/wpvivid-backup-pro-create-staging-site" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                        </li>
                        <li><span class="dashicons dashicons-migrate wpvivid-dashicons-blue"></span>
                            <a href="https://docs.wpvivid.com/wpvivid-staging-pro-publish-staging-to-live.html"><b>Publish A Staging Site</b></a>
                            <small><span style="float: right;"><a href="https://wpvivid.com/wpvivid-backup-pro-publish-staging-to-live" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                        </li>
                        <li><span class="dashicons dashicons-migrate wpvivid-dashicons-blue"></span>
                            <a href="https://docs.wpvivid.com/wpvivid-staging-pro-create-staging-site-for-wordpress-multisite.html"><b>Create A MU Staging</b></a>
                            <small><span style="float: right;"><a href="https://wpvivid.com/wpvivid-staging-pro-create-staging-site-for-wordpress-multisite" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                        </li>
                     </ul>
                 </div>';
        return $html;
    }

    public function ajax_check_security($role='administrator')
    {
        check_ajax_referer( 'wpvivid_ajax', 'nonce' );
        $check=is_admin()&&current_user_can($role);
        $check=apply_filters('wpvivid_ajax_check_security',$check);
        if(!$check)
        {
            die();
        }
    }

    public function get_staging_screen_ids($screen_ids)
    {
        $screen_ids=array();
        $screen['menu_slug']='wpvividstg-staging';//apply_filters('wpvivid_get_dashboard_menu_slug','wpvivid-dashboard');
        $screen['screen_id']='toplevel_page_wpvividstg-staging';
        $screen['is_top']=true;
        $screens[]=$screen;
        $screens=apply_filters('wpvivid_get_staging_screens',$screens);

        foreach ($screens as $screen)
        {
            $screen_ids[]=$screen['screen_id'];
            if(is_multisite())
            {
                if(substr($screen['screen_id'],-8)=='-network')
                    continue;
                $screen_ids[]=$screen['screen_id'].'-network';
            }
            else
            {
                $screen_ids[]=$screen['screen_id'];
            }
        }
        return $screen_ids;
    }

    public function enqueue_styles()
    {
        $screen_ids=array();
        $this->screen_ids=apply_filters('wpvivid_get_staging_screen_ids',$screen_ids);
        if(in_array(get_current_screen()->id,$this->screen_ids))
        {
            wp_enqueue_style($this->plugin_name.'jstree', WPVIVID_STAGING_PLUGIN_URL . 'includes/js/jstree/dist/themes/default/style.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name, WPVIVID_STAGING_PLUGIN_URL . 'includes/css/wpvivid-staging-custom.css', array(), $this->version, 'all');
        }
    }

    public function enqueue_scripts()
    {
        $screen_ids=array();
        $this->screen_ids=apply_filters('wpvivid_get_staging_screen_ids',$screen_ids);
        if(in_array(get_current_screen()->id,$this->screen_ids))
        {
            wp_enqueue_script($this->plugin_name, WPVIVID_STAGING_PLUGIN_URL . 'includes/js/wpvivid-staging-admin.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name.'jstree', WPVIVID_STAGING_PLUGIN_URL . 'includes/js/jstree/dist/jstree.min.js', array('jquery'), $this->version, false);
            wp_localize_script($this->plugin_name, 'wpvivid_ajax_object', array('ajax_url' => admin_url('admin-ajax.php'),'ajax_nonce'=>wp_create_nonce('wpvivid_ajax')));

            wp_enqueue_script('plupload-all');
        }
    }

    public function get_staging_menu_slug($menu_slug)
    {
        $menu_slug=apply_filters('wpvivid_white_label_slug', 'wpvividstg').'-staging';
        return $menu_slug;
    }

    public function get_staging_admin_menus()
    {
        $parent_slug=apply_filters('wpvivid_get_staging_menu_slug','wpvividstg-staging');
        $submenus=apply_filters('wpvivid_get_staging_menu',array(),$parent_slug);
        return $submenus;
    }

    public function add_plugin_admin_menu()
    {
        $menu['page_title']=apply_filters('wpvivid_white_label_display', 'WPvivid').' Staging';//__('WPvivid Staging');
        $menu['menu_title']=apply_filters('wpvivid_white_label_display', 'WPvivid').' Staging';//__('WPvivid Staging');
        $menu['capability']='administrator';
        $menu['menu_slug']= strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvividstg')));//$this->plugin_name;
        $menu['function']=array($this->ui_display, 'create_page_display');
        $menu['icon_url']='dashicons-cloud';
        $menu['position']=100;

        $menu=apply_filters('wpvivid_staging_get_main_admin_menus', $menu);

        if($menu !== false)
        {
            add_menu_page( $menu['page_title'],$menu['menu_title'], $menu['capability'], $menu['menu_slug'], $menu['function'], $menu['icon_url'], $menu['position']);

            $submenus = apply_filters('wpvivid_get_staging_admin_menus', array());

            usort($submenus, function ($a, $b)
            {
                if ($a['index'] == $b['index'])
                    return 0;

                if ($a['index'] > $b['index'])
                    return 1;
                else
                    return -1;
            });

            foreach ($submenus as $submenu)
            {
                add_submenu_page
                (
                    $submenu['parent_slug'],
                    $submenu['page_title'],
                    $submenu['menu_title'],
                    $submenu['capability'],
                    $submenu['menu_slug'],
                    $submenu['function']
                );
            }
        }
    }

    public function add_action_links( $links )
    {
        if(!is_multisite())
        {
            $active_plugins = get_option('active_plugins');
        }
        else
        {
            $active_plugins = array();
            //network active
            $mu_active_plugins = get_site_option( 'active_sitewide_plugins', array() );
            if(!empty($mu_active_plugins)){
                foreach ($mu_active_plugins as $plugin_name => $data){
                    $active_plugins[] = $plugin_name;
                }
            }
        }
        if(!function_exists('get_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $plugins=get_plugins();
        $pro_wpvivid_slug='wpvivid-backup-pro/wpvivid-backup-pro.php';
        $is_active_pro=false;
        if(!empty($plugins))
        {
            if(isset($plugins[$pro_wpvivid_slug]))
            {
                if(in_array($pro_wpvivid_slug, $active_plugins))
                {
                    $is_active_pro=true;
                }
            }
        }

        if($is_active_pro)
        {
            if(!is_multisite())
            {
                $settings_link = array(
                    '<a href="' . admin_url( 'admin.php?page=' . strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvivid'))) ) . '">' . __('Settings', $this->plugin_name) . '</a>',
                );
            }
            else
            {
                $settings_link = array(
                    '<a href="' . network_admin_url( 'admin.php?page=' . strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvivid'))) ) . '">' . __('Settings', $this->plugin_name) . '</a>',
                );
            }
        }
        else
        {
            if(!is_multisite())
            {
                $settings_link = array(
                    '<a href="' . admin_url( 'admin.php?page=' . strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvividstg'))) ) . '">' . __('Settings', $this->plugin_name) . '</a>',
                );
            }
            else
            {
                $settings_link = array(
                    '<a href="' . network_admin_url( 'admin.php?page=' . strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvividstg'))) ) . '">' . __('Settings', $this->plugin_name) . '</a>',
                );
            }
        }


        return array_merge(  $settings_link, $links );
    }

    public function filter_plugin_row_meta( array $plugin_meta, $plugin_file )
    {
        if ( 'wpvivid-staging/wpvivid-staging.php' !== $plugin_file ) {
            return $plugin_meta;
        }

        $plugin_meta[] = sprintf(
            '<a href="https://wpvivid.com/wpvivid-staging-changelog">Revision</a>'
        );

        return $plugin_meta;
    }

    public function get_database_site_url()
    {
        $site_url = site_url();
        global $wpdb;
        $site_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'siteurl' ) );
        foreach ( $site_url_sql as $site ){
            $site_url = $site->option_value;
        }
        return untrailingslashit($site_url);
    }

    public function get_database_home_url()
    {
        $home_url = home_url();
        global $wpdb;
        $home_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'home' ) );
        foreach ( $home_url_sql as $home ){
            $home_url = $home->option_value;
        }
        return untrailingslashit($home_url);
    }

    public function get_mu_list()
    {
        $this->ajax_check_security('manage_options');
        try {
            $args = array();
            $list = array();

            if(isset($_POST['copy']))
            {
                $copy=$_POST['copy'];
            }
            else if(isset($_POST['create']))
            {
                $copy='true';
            }
            else
            {
                $copy=false;
            }

            if(isset($_POST['search']))
            {
                global $wpdb;
                $s=$_POST['search'];
                if ( empty( $s ) ) {
                    // Nothing to do.
                } elseif ( preg_match( '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $s ) ||
                    preg_match( '/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.?$/', $s ) ||
                    preg_match( '/^[0-9]{1,3}\.[0-9]{1,3}\.?$/', $s ) ||
                    preg_match( '/^[0-9]{1,3}\.$/', $s ) ) {
                    // IPv4 address
                    $sql          = $wpdb->prepare( "SELECT blog_id FROM {$wpdb->registration_log} WHERE {$wpdb->registration_log}.IP LIKE %s", $wpdb->esc_like( $s ) . ( ! empty( $wild ) ? '%' : '' ) );
                    $reg_blog_ids = $wpdb->get_col( $sql );

                    if ( $reg_blog_ids ) {
                        $args['site__in'] = $reg_blog_ids;
                    }
                } elseif ( is_numeric( $s ) && empty( $wild ) ) {
                    $args['ID'] = $s;
                } else {
                    $args['search'] = $s;

                    if ( ! is_subdomain_install() ) {
                        $args['search_columns'] = array( 'path' );
                    }
                }
            }

            if($copy==false||$copy=='false')
            {
                $task_id=$_POST['id'];
                $task = new WPvivid_Staging_Task_Ex($task_id);
                $subsites=$task->get_mu_sites($args);
            }
            else
            {
                $subsites=get_sites($args);
            }



            if(isset($_POST['single']))
            {
                $mu_site_list=new WPvivid_Staging_MU_Single_Site_List();

                foreach ($subsites as $subsite)
                {
                    $list[]=$subsite;
                }
            }
            else
            {
                $mu_site_list=new WPvivid_Staging_MU_Site_List();

                foreach ($subsites as $subsite)
                {
                    if(is_main_site(get_object_vars($subsite)["blog_id"]))
                    {
                        continue;
                    }
                    else
                    {
                        $list[]=$subsite;
                    }
                }
            }

            if(isset($_POST['page']))
            {
                $mu_site_list->set_list($list,'mu_site',$_POST['page']);
            }
            else
            {
                $mu_site_list->set_list($list,'mu_site');
            }

            $mu_site_list->prepare_items();
            ob_start();
            $mu_site_list->display();
            $html = ob_get_clean();

            $ret['result']='success';
            $ret['html']=$html;
            echo json_encode($ret);
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_custom_database_size(){
        $this->ajax_check_security();
        try
        {
            $ret['result']='success';

            global $wpdb;
            $tables = $wpdb->get_results('SHOW TABLE STATUS', ARRAY_A);
            if (is_null($tables)) {
                $ret['result'] = 'failed';
                $ret['error'] = 'Failed to retrieve the table information for the database. Please try again.';
                return $ret;
            }

            $db_size = 0;

            $base_table_size = 0;
            foreach ($tables as $row) {
                $base_table_size += ($row["Data_length"] + $row["Index_length"]);
            }

            $db_size = size_format($base_table_size, 2);

            $ret['database_size'] = $db_size;
            echo json_encode($ret);
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public static function get_custom_path_size($type, $path, $size=0){
        if(!function_exists('get_home_path'))
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        $home_path = str_replace('\\','/', get_home_path());
        $core_file_arr = array('.htaccess', 'index', 'license.txt', 'readme.html', 'wp-activate.php', 'wp-blog-header.php', 'wp-comments-post.php', 'wp-config.php', 'wp-config-sample.php',
            'wp-cron.php', 'wp-links-opml.php', 'wp-load.php', 'wp-login.php', 'wp-mail.php', 'wp-settings.php', 'wp-signup.php', 'wp-trackback.php', 'xmlrpc.php');
        if(is_dir($path))
        {
            $handler = opendir($path);
            if($handler!==false)
            {
                while (($filename = readdir($handler)) !== false)
                {
                    if ($filename != "." && $filename != "..") {
                        if (is_dir($path . DIRECTORY_SEPARATOR . $filename))
                        {
                            if($type === 'content'){
                                if($filename !== 'plugins' && $filename !== 'themes' && $filename !== 'uploads'){
                                    $size=self::get_custom_path_size($type, $path . DIRECTORY_SEPARATOR . $filename, $size);
                                }
                            }
                            else if($type === 'core' && $home_path === $path){
                                if($filename === 'wp-admin' || $filename === 'wp-includes'){
                                    $size=self::get_custom_path_size($type, $path . DIRECTORY_SEPARATOR . $filename, $size);
                                }
                            }
                            else if($type === 'additional'){
                                if($filename !== 'wp-admin' && $filename !== 'wp-content' && $filename !== 'wp-includes'){
                                    $size=self::get_custom_path_size($type, $path . DIRECTORY_SEPARATOR . $filename, $size);
                                }
                            }
                            else{
                                $size=self::get_custom_path_size($type, $path . DIRECTORY_SEPARATOR . $filename, $size);
                            }
                        } else {
                            if($type === 'core'){
                                if($home_path === $path){
                                    if(in_array($filename, $core_file_arr)){
                                        $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                                    }
                                }
                                else{
                                    $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                                }
                            }
                            else if($type === 'additional'){
                                if($home_path === $path){
                                    if(!in_array($filename, $core_file_arr)){
                                        $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                                    }
                                }
                                else{
                                    $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                                }
                            }
                            else{
                                $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                            }
                        }
                    }
                }
                if($handler)
                    @closedir($handler);
            }

        }
        return $size;
    }

    public function get_custom_files_size(){
        $this->ajax_check_security();
        try
        {
            $upload_dir = wp_upload_dir();
            $path = $upload_dir['basedir'];
            $path = str_replace('\\','/',$path);
            $uploads_path = $path.'/';

            $content_dir = WP_CONTENT_DIR;
            $path = str_replace('\\','/',$content_dir);
            $content_path = $path.'/';

            if(!function_exists('get_home_path'))
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            $home_path = str_replace('\\','/', get_home_path());

            $themes_path = str_replace('\\','/', get_theme_root());
            $themes_path = $themes_path.'/';

            $plugins_path = str_replace('\\','/', WP_PLUGIN_DIR);
            $plugins_path = $plugins_path.'/';

            $ret['result']='success';
            $core_size = self::get_custom_path_size('core', $home_path);
            $themes_size = self::get_custom_path_size('themes', $themes_path);
            $plugins_size = self::get_custom_path_size('plugins', $plugins_path);
            $uploads_size = self::get_custom_path_size('uploads', $uploads_path);
            $content_size = self::get_custom_path_size('content', $content_path);
            $additional_size = self::get_custom_path_size('additional', $home_path);
            $ret['core_size'] = size_format($core_size, 2);
            $ret['themes_size'] = size_format($themes_size, 2);
            $ret['plugins_size'] = size_format($plugins_size, 2);
            $ret['uploads_size'] = size_format($uploads_size, 2);
            $ret['content_size'] = size_format($content_size, 2);
            $ret['additional_size'] = size_format($additional_size, 2);
            $ret['total_file_size'] = size_format($core_size+$themes_size+$plugins_size+$uploads_size+$content_size+$additional_size, 2);
            echo json_encode($ret);
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }
    
    public function get_custom_include_path(){
        $this->ajax_check_security();
        try {
            if (isset($_POST['is_staging'])) {
                $is_staging = $_POST['is_staging'];

                $node_array = array();

                if ($_POST['tree_node']['node']['id'] == '#') {
                    $path = ABSPATH;

                    if (!empty($_POST['tree_node']['path'])) {
                        $path = $_POST['tree_node']['path'];
                    }

                    if (isset($_POST['select_prev_dir']) && $_POST['select_prev_dir'] === '1') {
                        $path = dirname($path);
                    }

                    $node_array[] = array(
                        'text' => basename($path),
                        'children' => true,
                        'id' => $path,
                        'icon' => 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer',
                        'state' => array(
                            'opened' => true
                        )
                    );
                } else {
                    $path = $_POST['tree_node']['node']['id'];
                }

                if (file_exists($path)) {
                    $path = trailingslashit(str_replace('\\', '/', realpath($path)));

                    if ($dh = opendir($path)) {
                        while (substr($path, -1) == '/') {
                            $path = rtrim($path, '/');
                        }

                        $skip_paths = array(".", "..");

                        $file_array = array();

                        while (($value = readdir($dh)) !== false) {
                            trailingslashit(str_replace('\\', '/', $value));

                            if (!in_array($value, $skip_paths)) {
                                if (is_dir($path . '/' . $value)) {
                                    $wp_admin_path = $is_staging == false ? ABSPATH . 'wp-admin' : $path . '/wp-admin';
                                    $wp_admin_path = str_replace('\\', '/', $wp_admin_path);

                                    $wp_include_path = $is_staging == false ? ABSPATH . 'wp-includes' : $path . '/wp-includes';
                                    $wp_include_path = str_replace('\\', '/', $wp_include_path);

                                    $content_dir = $is_staging == false ? WP_CONTENT_DIR : $path . '/wp-content';
                                    $content_dir = str_replace('\\', '/', $content_dir);
                                    $content_dir = rtrim($content_dir, '/');

                                    $exclude_dir = array($wp_admin_path, $wp_include_path, $content_dir);
                                    if (!in_array($path . '/' . $value, $exclude_dir)) {
                                        $node_array[] = array(
                                            'text' => $value,
                                            'children' => true,
                                            'id' => $path . '/' . $value,
                                            'icon' => 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer'
                                        );
                                    }

                                } else {
                                    $wp_admin_path = $is_staging == false ? ABSPATH : $path;
                                    $wp_admin_path = str_replace('\\', '/', $wp_admin_path);
                                    $wp_admin_path = rtrim($wp_admin_path, '/');
                                    $skip_path = rtrim($path, '/');

                                    if ($wp_admin_path == $skip_path) {
                                        continue;
                                    }
                                    $file_array[] = array(
                                        'text' => $value,
                                        'children' => false,
                                        'id' => $path . '/' . $value,
                                        'type' => 'file',
                                        'icon' => 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer'
                                    );
                                }
                            }
                        }
                        $node_array = array_merge($node_array, $file_array);
                    }
                } else {
                    $node_array = array();
                }

                $ret['nodes'] = $node_array;
                echo json_encode($ret);
                die();
            }
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_custom_exclude_path(){
        $this->ajax_check_security();
        try{
            if (isset($_POST['is_staging'])) {
                $is_staging = $_POST['is_staging'];
                $node_array = array();

                if ($_POST['tree_node']['node']['id'] == '#') {
                    $path = ABSPATH;

                    if (!empty($_POST['tree_node']['path'])) {
                        $path = $_POST['tree_node']['path'];
                    }

                    $node_array[] = array(
                        'text' => basename($path),
                        'children' => true,
                        'id' => $path,
                        'icon' => 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer',
                        'state' => array(
                            'opened' => true
                        )
                    );
                } else {
                    $path = $_POST['tree_node']['node']['id'];
                }

                if (file_exists($path)) {
                    $path = trailingslashit(str_replace('\\', '/', realpath($path)));

                    if ($dh = opendir($path)) {
                        while (substr($path, -1) == '/') {
                            $path = rtrim($path, '/');
                        }
                        $skip_paths = array(".", "..");

                        while (($value = readdir($dh)) !== false) {
                            trailingslashit(str_replace('\\', '/', $value));
                            if (!in_array($value, $skip_paths)) {
                                //
                                $custom_dir = $is_staging == false ? WP_CONTENT_DIR . '/' . WPVIVID_STAGING_DIR : $path . '/' . WPVIVID_STAGING_DIR;
                                $custom_dir = str_replace('\\', '/', $custom_dir);

                                $themes_dir = $is_staging == false ? get_theme_root() : $path . '/themes';
                                $themes_dir = trailingslashit(str_replace('\\', '/', $themes_dir));
                                $themes_dir = rtrim($themes_dir, '/');

                                $plugin_dir = $is_staging == false ? WP_PLUGIN_DIR : $path . '/plugins';
                                $plugin_dir = trailingslashit(str_replace('\\', '/', $plugin_dir));
                                $plugin_dir = rtrim($plugin_dir, '/');

                                $wpvivid_plugins = array($plugin_dir.'/wpvivid-staging', $plugin_dir.'/wpvivid-backup-pro', $plugin_dir.'/wpvivid-database-merging');

                                if ($is_staging == false) {
                                    $upload_path = wp_upload_dir();
                                    $upload_path['basedir'] = trailingslashit(str_replace('\\', '/', $upload_path['basedir']));
                                    $upload_dir = rtrim($upload_path['basedir'], '/');
                                    $subsite_dir = rtrim($upload_path['basedir'], '/') . '/' . 'sites';
                                } else {
                                    $upload_dir = $path . '/uploads';
                                    $subsite_dir = $path . '/sites';
                                }
                                $exclude_dir = array($themes_dir, $plugin_dir, $upload_dir, $custom_dir, $subsite_dir);
                                $exclude_dir = array_merge($exclude_dir, $wpvivid_plugins);
                                if (is_dir($path . '/' . $value)) {
                                    if (!in_array($path . '/' . $value, $exclude_dir)) {
                                        $node['text'] = $value;
                                        $node['children'] = true;
                                        $node['id'] = $path . '/' . $value;
                                        $node['icon'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                        $node_array[] = $node;
                                    }
                                }
                                else{
                                    $node['text'] = $value;
                                    $node['children'] = true;
                                    $node['id'] = $path . '/' . $value;
                                    $node['icon'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    $node_array[] = $node;
                                }
                            }
                        }
                    }
                }
                else {
                    $node_array = array();
                }

                $ret['nodes'] = $node_array;
                echo json_encode($ret);
            }
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_custom_themes_plugins_info_ex(){
        $this->ajax_check_security();
        try{

            $is_staging_site = false;

            $staging_option = array();

            //$themes_path = $is_staging_site == false ? get_theme_root() : $_POST['staging_path'] . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'themes';
            $themes_path = get_theme_root();

            $exclude_themes_list = '';

            $themes_info = array();

            //$themes = $is_staging_site == false ? wp_get_themes() : $ret['themes_list'];
            $themes = wp_get_themes();

            foreach ($themes as $theme)
            {
                $file = $theme->get_stylesheet();
                $themes_info[$file] = $this->get_theme_plugin_info($themes_path . DIRECTORY_SEPARATOR . $file);
                $parent=$theme->parent();
                $themes_info[$file]['parent']=$parent;
                $themes_info[$file]['parent_file']=$theme->get_template();
                $themes_info[$file]['child']=array();

                if(isset($_POST['subsite']))
                {
                    switch_to_blog($_POST['subsite']);
                    $ct = wp_get_theme();
                    if( $ct->get_stylesheet()==$file)
                    {
                        $themes_info[$file]['active'] = 1;
                    }
                    else
                    {
                        $themes_info[$file]['active'] = 0;
                    }
                    restore_current_blog();
                }
                else
                {
                    $themes_info[$file]['active'] = 1;
                }
            }

            foreach ($themes_info as $file => $info)
            {
                if($info['active']&&$info['parent']!=false)
                {
                    $themes_info[$info['parent_file']]['active']=1;
                    $themes_info[$info['parent_file']]['child'][]=$file;
                }
            }

            foreach ($themes_info as $file => $info) {
                if ($info['active'] == 1) {

                }
                else{
                    $exclude_themes_list .= '<div class="wpvivid-text-line" type="folder">
                                                <span class="dashicons dashicons-trash wpvivid-icon-16px wpvivid-remove-custom-exlcude-tree"></span><span class="dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer"></span><span class="wpvivid-text-line">'.$file.'</span>
                                              </div>';
                }
            }

            $exclude_plugin_list = '';
            //$path = $is_staging_site == false ? WP_PLUGIN_DIR : $_POST['staging_path'] . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins';
            $path = WP_PLUGIN_DIR;
            $plugin_info = array();

            if (!function_exists('get_plugins'))
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            //$plugins = $is_staging_site == false ? get_plugins() : $ret['plugins_list'];
            $plugins = get_plugins();
            if(isset($_POST['subsite']))
            {
                switch_to_blog($_POST['subsite']);
                $current   = get_option( 'active_plugins', array() );
                restore_current_blog();
            }
            else
            {
                $current   = get_option( 'active_plugins', array() );
            }


            foreach ($plugins as $key => $plugin)
            {
                $slug = dirname($key);
                if ($slug == '.')
                    continue;
                $plugin_info[$slug] = $this->get_theme_plugin_info($path . DIRECTORY_SEPARATOR . $slug);
                $plugin_info[$slug]['Name'] = $plugin['Name'];
                $plugin_info[$slug]['slug'] = $slug;

                if(isset($_POST['subsite']))
                {
                    if(in_array($key,$current))
                    {
                        $plugin_info[$slug]['active'] = 1;
                    }
                    else
                    {
                        $plugin_info[$slug]['active'] = 0;
                    }
                }
                else
                {
                    $plugin_info[$slug]['active'] = 1;
                }
            }

            foreach ($plugin_info as $slug => $info) {
                if ($info['active'] == 1) {

                }
                else{
                    $exclude_plugin_list .= '<div class="wpvivid-text-line" type="folder">
                                                <span class="dashicons dashicons-trash wpvivid-icon-16px wpvivid-remove-custom-exlcude-tree"></span><span class="dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer"></span><span class="wpvivid-text-line">'.$slug.'</span>
                                              </div>';
                }
            }
            $ret['result'] = 'success';
            $ret['theme_list'] = $exclude_themes_list;
            $ret['plugin_list'] .= $exclude_plugin_list;
            echo json_encode($ret);
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function hide_staging_remote_notice()
    {
        $this->ajax_check_security();
        try{
            delete_option('wpvivid_staging_remote_notice');
            $ret['result'] = 'success';
            echo json_encode($ret);
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_staging_site_data()
    {
        if(is_multisite())
        {
            switch_to_blog(get_main_network_id());
            $staging=$this->option->get_option('wpvivid_staging_data');
            restore_current_blog();
        }
        else
        {
            $staging=$this->option->get_option('wpvivid_staging_data');
        }

        return $staging;
    }

    public function get_staging_site_data_ex()
    {
        if(is_multisite())
        {
            $polylang_slug='polylang/polylang.php';
            if($this->check_plugin_active($polylang_slug))
            {
                $this->switch_to_blog(get_main_network_id());
            }
            else
            {
                switch_to_blog(get_main_network_id());
            }
            $staging=$this->option->get_option('wpvivid_staging_data');
            if($this->check_plugin_active($polylang_slug))
            {
                $this->restore_current_blog();
            }
            else
            {
                restore_current_blog();
            }
        }
        else
        {
            $staging=$this->option->get_option('wpvivid_staging_data');
        }

        return $staging;
    }

    public function reset_staging_data($site_data)
    {
        global $wpdb;
        $prefix=$site_data['prefix'];
        if($site_data['db_connect']['use_additional_db']===false)
        {
            $db_des_instance=$wpdb;
        }
        else
        {
            $db_des_instance=new wpdb($site_data['db_connect']['dbuser'],
                $site_data['db_connect']['dbpassword'],
                $site_data['db_connect']['dbname'],
                $site_data['db_connect']['dbhost']);
        }

        $data['id']=$site_data['id'];
        $data['name']=$site_data['path'];
        $data['prefix']= $prefix;
        $admin_url = apply_filters('wpvividstg_get_admin_url', '');
        $admin_url .= 'admin.php?page='.apply_filters('wpvivid_white_label_slug', 'WPvivid');
        $data['parent_admin_url']=$admin_url;
        $data['live_site_url']=home_url();
        $data['live_site_staging_url']=apply_filters('wpvividstg_get_admin_url', '').'admin.php?page='.apply_filters('wpvivid_white_label_plugin_name', 'WPvivid_Staging');


        $data['live_site_data']['db_connect']['use_additional_db']=$site_data['db_connect']['use_additional_db'];
        if($site_data['db_connect']['use_additional_db']!==false)
        {
            $data['live_site_data']['db_connect']['dbuser']=DB_USER;
            $data['live_site_data']['db_connect']['dbpassword']=DB_PASSWORD;
            $data['live_site_data']['db_connect']['dbname']=DB_NAME;
            $data['live_site_data']['db_connect']['dbhost']=DB_HOST;
        }

        $data['live_site_data']['path']=untrailingslashit(ABSPATH);
        $data['live_site_data']['prefix']=$wpdb->base_prefix;

        $data['live_site_data']['site_url']=site_url();
        $data['live_site_data']['home_url']=home_url();

        if($site_data['mu_single']==true)
        {
            $data['live_site_data']['mu_single']=true;
            $data['live_site_data']['mu_single_site_id']=$site_data['mu_single_site_id'];
            $data['live_site_data']['site_url']=get_site_url($this->task['mu_single_site_id']);
            $data['live_site_data']['home_url']=get_home_url($this->task['mu_single_site_id']);
        }
        else
        {
            $data['live_site_data']['mu_single']=false;
        }

        if(isset($site_data['path_current_site']))
        {
            $data['live_site_data']['path_current_site']=PATH_CURRENT_SITE;
            $data['live_site_data']['main_site_id']=$site_data['main_site_id'];
        }

        $wpvivid_options_table=$prefix.'wpvivid_options';
        if($db_des_instance->get_var("SHOW TABLES LIKE '$wpvivid_options_table'") != $wpvivid_options_table)
        {
            $sql = "CREATE TABLE IF NOT EXISTS $wpvivid_options_table (
                `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `option_name` varchar(191) NOT NULL DEFAULT '',
				`option_value` longtext NOT NULL,
				PRIMARY KEY (`option_id`),
				UNIQUE KEY `option_name` (`option_name`)
                );";
            $db_des_instance->query($sql);
        }

        $option_name='wpvivid_staging_data';
        $option_value=maybe_serialize($data);

        $update_query = $db_des_instance->prepare("INSERT INTO $wpvivid_options_table (option_name,option_value) VALUES (%s, %s) ON DUPLICATE KEY UPDATE option_value=%s", 'wpvivid_staging_data', $option_value, $option_value);
        return $db_des_instance->get_results($update_query);
    }

    public function get_custom_database_tables_info()
    {
        $this->ajax_check_security();
        try {
            global $wpdb;
            $db = array();
            $use_additional_db = false;
            if(isset($_POST['id']))
            {
                $staging_site_id = $_POST['id'];
            }

            if(empty($staging_site_id))
            {
                $get_site_mu_single=false;
                $base_prefix=$wpdb->base_prefix;
                $is_staging_site = false;
                if (is_multisite())
                {
                    $prefix = $wpdb->base_prefix;
                }
                else {
                    $prefix = $wpdb->get_blog_prefix(0);
                }
            }
            else
            {
                global $wpvivid_staging;
                $staging_site_data = $wpvivid_staging->option->get_option('staging_site_data');
                if (isset($_POST['is_staging']) && !empty($_POST['is_staging']) && is_string($_POST['is_staging'])&&$_POST['is_staging'] == '1')
                {
                    $is_staging_site = true;
                    if(isset($staging_site_data[$staging_site_id]['prefix']))
                    {
                        $base_prefix = $staging_site_data[$staging_site_id]['prefix'];
                        $prefix = $staging_site_data[$staging_site_id]['prefix'];
                    }
                    else
                    {
                        $base_prefix = false;
                        $prefix = false;
                    }
                    $site_id = false;
                    $get_site_mu_single = false;

                }
                else
                {
                    $is_staging_site = false;
                    if (is_multisite())
                    {
                        if(isset($staging_site_data[$staging_site_id]['mu_single']) && isset($staging_site_data[$staging_site_id]['mu_single_site_id']))
                        {
                            $site_id = $staging_site_data[$staging_site_id]['mu_single_site_id'];
                            $get_site_mu_single = $staging_site_data[$staging_site_id]['mu_single'];

                            if($get_site_mu_single)
                            {
                                $prefix = $wpdb->get_blog_prefix($site_id);
                            }
                            else
                            {
                                $prefix = $wpdb->base_prefix;
                            }
                        }
                        else
                        {
                            $site_id = false;
                            $get_site_mu_single = false;
                            $prefix = $wpdb->base_prefix;
                        }
                    }
                    else {
                        $site_id = false;
                        $get_site_mu_single = false;
                        $prefix = $wpdb->get_blog_prefix(0);
                    }

                    $base_prefix=$wpdb->base_prefix;
                }

                if(isset($staging_site_data[$staging_site_id]['db_connect']))
                {
                    $db = $staging_site_data[$staging_site_id]['db_connect'];
                    if ($db['use_additional_db'] !== false)
                    {
                        $use_additional_db = true;
                    }
                    else {
                        $use_additional_db = false;
                    }
                }
                else
                {
                    $use_additional_db = false;
                }

            }

            $base_prefix = apply_filters('wpvividstg_get_table_prefix', $base_prefix);
            $prefix = apply_filters('wpvividstg_get_table_prefix', $prefix);

            $ret['result'] = 'success';
            $ret['html'] = '';
            if (empty($prefix)) {
                echo json_encode($ret);
                die();
            }

            $base_table = '';
            $woo_table = '';
            $other_table = '';
            $default_table = array($prefix . 'commentmeta', $prefix . 'comments', $prefix . 'links', $prefix . 'options', $prefix . 'postmeta', $prefix . 'posts', $prefix . 'term_relationships',
                $prefix . 'term_taxonomy', $prefix . 'termmeta', $prefix . 'terms', $prefix . 'usermeta', $prefix . 'users');
            $woo_table_arr = array($prefix.'actionscheduler_actions', $prefix.'actionscheduler_claims', $prefix.'actionscheduler_groups', $prefix.'actionscheduler_logs', $prefix.'aelia_dismissed_messages',
                $prefix.'aelia_exchange_rates_history', $prefix.'automatewoo_abandoned_carts', $prefix.'automatewoo_customer_meta', $prefix.'automatewoo_customers', $prefix.'automatewoo_events',
                $prefix.'automatewoo_guest_meta', $prefix.'automatewoo_guests', $prefix.'automatewoo_log_meta', $prefix.'automatewoo_logs', $prefix.'automatewoo_queue', $prefix.'automatewoo_queue_meta',
                $prefix.'automatewoo_unsubscribes', $prefix.'wc_admin_note_actions', $prefix.'wc_admin_notes', $prefix.'wc_am_api_activation', $prefix.'wc_am_api_resource', $prefix.'wc_am_associated_api_key',
                $prefix.'wc_am_secure_hash', $prefix.'wc_category_lookup', $prefix.'wc_customer_lookup', $prefix.'wc_download_log', $prefix.'wc_order_coupon_lookup', $prefix.'wc_order_product_lookup',
                $prefix.'wc_order_stats', $prefix.'wc_order_tax_lookup', $prefix.'wc_product_meta_lookup', $prefix.'wc_reserved_stock', $prefix.'wc_tax_rate_classes', $prefix.'wc_webhooks',
                $prefix.'woocommerce_api_keys', $prefix.'woocommerce_attribute_taxonomies', $prefix.'woocommerce_downloadable_product_permissions', $prefix.'woocommerce_log', $prefix.'woocommerce_order_itemmeta',
                $prefix.'woocommerce_order_items', $prefix.'woocommerce_payment_tokenmeta', $prefix.'woocommerce_payment_tokens', $prefix.'woocommerce_sessions', $prefix.'woocommerce_shipping_zone_locations',
                $prefix.'woocommerce_shipping_zone_methods', $prefix.'woocommerce_shipping_zones', $prefix.'woocommerce_tax_rate_locations', $prefix.'woocommerce_tax_rates');

            if ($is_staging_site) {
                $staging_option = self::wpvivid_get_push_staging_history();
                if (empty($staging_option)) {
                    $staging_option = array();
                }
                if ($use_additional_db) {
                    $handle = new wpdb($db['dbuser'], $db['dbpassword'], $db['dbname'], $db['dbhost']);
                    $tables = $handle->get_results('SHOW TABLE STATUS', ARRAY_A);
                } else {
                    $tables = $wpdb->get_results('SHOW TABLE STATUS', ARRAY_A);
                }
            } else {
                //$staging_option = self::wpvivid_get_staging_history();
                $staging_options=new WPvivid_Staging_Option();
                $staging_option=$staging_options->get_option('wpvivid_staging_history_ex');
                if (empty($staging_option)) {
                    $staging_option = array();
                }
                $tables = $wpdb->get_results('SHOW TABLE STATUS', ARRAY_A);
            }

            if (is_null($tables)) {
                $ret['result'] = 'failed';
                $ret['error'] = 'Failed to retrieve the table information for the database. Please try again.';
                echo json_encode($ret);
                die();
            }

            $tables_info = array();
            $has_base_table = false;
            $has_woo_table = false;
            $has_other_table = false;
            $base_count = 0;
            $woo_count = 0;
            $other_count = 0;
            $base_table_all_check = true;
            $woo_table_all_check = true;
            $other_table_all_check = true;
            foreach ($tables as $row)
            {
                if (preg_match('/^(?!' . $base_prefix . ')/', $row["Name"]) == 1)
                {
                    continue;
                }

                if($get_site_mu_single)
                {
                    if(!is_main_site($site_id))
                    {
                        if ( 1 == preg_match('/^' . $prefix . '/', $row["Name"]) )
                        {
                        }
                        else if ( 1 == preg_match('/^' . $base_prefix . '\d+_/', $row["Name"]) )
                        {
                            continue;
                        }
                        else
                        {
                            if($row["Name"]==$base_prefix.'users'||$row["Name"]==$base_prefix.'usermeta')
                            {

                            }
                            else
                            {
                                continue;
                            }
                        }
                    }
                    else
                    {
                        if ( 1 == preg_match('/^' . $base_prefix . '\d+_/', $row["Name"]) )
                        {
                            continue;
                        }
                        else
                        {
                            if($row["Name"]==$base_prefix.'blogs')
                                continue;
                            if($row["Name"]==$base_prefix.'blogmeta')
                                continue;
                            if($row["Name"]==$base_prefix.'sitemeta')
                                continue;
                            if($row["Name"]==$base_prefix.'site')
                                continue;
                        }
                    }
                }


                $tables_info[$row["Name"]]["Rows"] = $row["Rows"];
                $tables_info[$row["Name"]]["Data_length"] = size_format($row["Data_length"] + $row["Index_length"], 2);

                $checked = 'checked';
                if (!empty($staging_option['database_list'])) {
                    if ($is_staging_site) {
                        $tmp_row = $row["Name"];

                        $tmp_row = str_replace($base_prefix, $wpdb->base_prefix, $tmp_row);
                        if (in_array($tmp_row, $staging_option['database_list'])) {
                            $checked = '';
                        }
                    }
                    else if (in_array($row["Name"], $staging_option['database_list'])) {
                        $checked = '';
                    }
                }

                if (in_array($row["Name"], $default_table)) {
                    if ($checked == '') {
                        $base_table_all_check = false;
                    }
                    $has_base_table = true;

                    $base_table .= '<div class="wpvivid-text-line">
                                        <input type="checkbox" option="base_db" name="Database" value="'.esc_html($row["Name"]).'" '.esc_html($checked).' />
                                        <span class="wpvivid-text-line">'.esc_html($row["Name"]).'|Rows:'.$row["Rows"].'|Size:'.$tables_info[$row["Name"]]["Data_length"].'</span>
                                    </div>';
                    $base_count++;
                } else if(in_array($row['Name'], $woo_table_arr)){
                    if ($checked == '') {
                        $woo_table_all_check = false;
                    }
                    $has_woo_table = true;

                    $woo_table .= '<div class="wpvivid-text-line">
                                        <input type="checkbox" option="woo_db" name="Database" value="'.esc_html($row["Name"]).'" '.esc_html($checked).' />
                                        <span class="wpvivid-text-line">'.esc_html($row["Name"]).'|Rows:'.$row["Rows"].'|Size:'.$tables_info[$row["Name"]]["Data_length"].'</span>
                                   </div>';
                    $woo_count++;
                }
                else {
                    if ($checked == '') {
                        $other_table_all_check = false;
                    }
                    $has_other_table = true;

                    $other_table .= '<div class="wpvivid-text-line">
                                        <input type="checkbox" option="other_db" name="Database" value="'.esc_html($row["Name"]).'" '.esc_html($checked).' />
                                        <span class="wpvivid-text-line">'.esc_html($row["Name"]).'|Rows:'.$row["Rows"].'|Size:'.$tables_info[$row["Name"]]["Data_length"].'</span>
                                     </div>';
                    $other_count++;
                }
            }

            $ret['html'] = '<div style="padding-left:4em;margin-top:1em;">
                                        <div style="border-bottom:1px solid #eee;"></div>
                                     </div>';

            $base_table_html = '';
            $woo_table_html = '';
            $other_table_html = '';
            if ($has_base_table) {
                $base_all_check = '';
                if ($base_table_all_check) {
                    $base_all_check = 'checked';
                }
                $base_table_html .= '<div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:4em;">
                                        <div>
                                            <p>
                                                <span class="dashicons dashicons-list-view wpvivid-dashicons-blue"></span>
                                                <span><input type="checkbox" class="wpvivid-database-table-check wpvivid-database-base-table-check" '.esc_attr($base_all_check).'></span>
                                                <span><strong>Wordpress Default Tables</strong></span>
                                            </p>
                                        </div>
                                        <div style="height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">
                                            '.$base_table.'
                                        </div>
                                        <div style="clear:both;"></div>
                                    </div>';
            }

            if ($has_other_table) {
                $other_all_check = '';
                if ($other_table_all_check) {
                    $other_all_check = 'checked';
                }

                if($has_woo_table){
                    $other_table_width = '40%';
                }
                else{
                    $other_table_width = '70%';
                }

                $other_table_html .= '<div style="width:'.$other_table_width.'; float:left;box-sizing:border-box;padding-left:0.5em;">
                                        <div>
                                            <p>
                                                <span class="dashicons dashicons-list-view wpvivid-dashicons-green"></span>
                                                <span><input type="checkbox" class="wpvivid-database-table-check wpvivid-database-other-table-check" '.esc_attr($other_all_check).'></span>
                                                <span><strong>Other Tables</strong></span>
                                            </p>
                                        </div>
                                        <div style="height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;">
                                            '.$other_table.'
                                        </div>
                                     </div>';
            }

            if($has_woo_table) {
                $woo_all_check = '';
                if ($woo_table_all_check) {
                    $woo_all_check = 'checked';
                }
                $woo_table_html .= '<div style="width:30%; float:left;box-sizing:border-box;padding-left:0.5em;">
                                        <div>
										    <p><span class="dashicons dashicons-list-view wpvivid-dashicons-orange"></span>
												<span><input type="checkbox" class="wpvivid-database-table-check wpvivid-database-woo-table-check" '.esc_attr($woo_all_check).'></span>
												<span><strong>WooCommerce Tables</strong></span>
											</p>
										</div>
										<div style="height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">
											'.$woo_table.'
                                        </div>
                                    </div>';
            }

            $ret['html'] .= $base_table_html . $other_table_html . $woo_table_html;
            $ret['tables_info'] = $tables_info;
            echo json_encode($ret);
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function wpvivid_replace_directory( $path ) {
        return preg_replace( '/[\\\\]+/', '/', $path );
    }

    public function getPath( $path, $wpcontentDir, $directory ) {
        $realPath = $this->wpvivid_replace_directory($directory->getRealPath());
        if( false === strpos( $realPath, $path ) ) {
            return false;
        }

        $path = str_replace( $wpcontentDir . '/', null, $this->wpvivid_replace_directory($directory->getRealPath()) );
        // Using strpos() for symbolic links as they could create nasty stuff in nix stuff for directory structures
        if( !$directory->isDir() ||
            strlen( $path ) < 1 ||
            (strpos( $this->wpvivid_replace_directory($directory->getRealPath()), $wpcontentDir . '/' . 'plugins' ) !== 0 &&
                strpos( $this->wpvivid_replace_directory($directory->getRealPath()), $wpcontentDir . '/' . 'themes' ) !== 0 &&
                strpos( $this->wpvivid_replace_directory($directory->getRealPath()), $wpcontentDir . '/' . 'uploads' ) !== 0 )
        ) {
            return false;
        }

        return $path;
    }

    public function wpvivid_search_staging_theme_directories($wpvivid_staging_themes_dir){
        if ( empty( $wpvivid_staging_themes_dir ) ) {
            return false;
        }
        $found_themes = array();
        $wpvivid_staging_themes_dir = (array) $wpvivid_staging_themes_dir;
        foreach ( $wpvivid_staging_themes_dir as $theme_root ) {
            $dirs = @ scandir( $theme_root );
            if ( ! $dirs ) {
                continue;
            }
            foreach ( $dirs as $dir ) {
                if ( ! is_dir( $theme_root . '/' . $dir ) || $dir[0] == '.' || $dir == 'CVS' ) {
                    continue;
                }
                if ( file_exists( $theme_root . '/' . $dir . '/style.css' ) ) {
                    $found_themes[ $dir ] = array(
                        'theme_file' => $dir . '/style.css',
                        'theme_root' => $theme_root,
                    );
                }
                else {
                    $found_theme = false;
                    $sub_dirs = @ scandir( $theme_root . '/' . $dir );
                    if ( ! $sub_dirs ) {
                        continue;
                    }
                    foreach ( $sub_dirs as $sub_dir ) {
                        if ( ! is_dir( $theme_root . '/' . $dir . '/' . $sub_dir ) || $dir[0] == '.' || $dir == 'CVS' ) {
                            continue;
                        }
                        if ( ! file_exists( $theme_root . '/' . $dir . '/' . $sub_dir . '/style.css' ) ) {
                            continue;
                        }
                        $found_themes[ $dir . '/' . $sub_dir ] = array(
                            'theme_file' => $dir . '/' . $sub_dir . '/style.css',
                            'theme_root' => $theme_root,
                        );
                        $found_theme = true;
                    }
                    if ( ! $found_theme ) {
                        $found_themes[ $dir ] = array(
                            'theme_file' => $dir . '/style.css',
                            'theme_root' => $theme_root,
                        );
                    }
                }
            }
        }
        asort( $found_themes );
        return $found_themes;
    }

    public function get_staging_themes_info($wpvivid_staging_themes_dir){
        $themes = array();
        $theme_directories = $this->wpvivid_search_staging_theme_directories($wpvivid_staging_themes_dir);
        if ( !empty( $theme_directories ) ) {
            foreach ( $theme_directories as $theme => $theme_root ) {
                $themes[ $theme ] = $theme_root['theme_root'] . '/' . $theme;
                $themes[ $theme ] = new WP_Theme( $theme, $theme_root['theme_root'] );
            }
        }
        return $themes;
    }

    public function get_staging_plugins_info($wpvivid_stating_plugins_dir){
        $wp_plugins  = array();
        $plugin_root = $wpvivid_stating_plugins_dir;
        $plugins_dir  = @ opendir( $plugin_root );
        $plugin_files = array();
        if ( $plugins_dir ) {
            while ( ( $file = readdir( $plugins_dir ) ) !== false ) {
                if ( substr( $file, 0, 1 ) == '.' ) {
                    continue;
                }
                if ( is_dir( $plugin_root . '/' . $file ) ) {
                    $plugins_subdir = @ opendir( $plugin_root . '/' . $file );
                    if ( $plugins_subdir ) {
                        while ( ( $subfile = readdir( $plugins_subdir ) ) !== false ) {
                            if ( substr( $subfile, 0, 1 ) == '.' ) {
                                continue;
                            }
                            if ( substr( $subfile, -4 ) == '.php' ) {
                                $plugin_files[] = "$file/$subfile";
                            }
                        }
                        closedir( $plugins_subdir );
                    }
                } else {
                    if ( substr( $file, -4 ) == '.php' ) {
                        $plugin_files[] = $file;
                    }
                }
            }
            closedir( $plugins_dir );
        }
        if ( !empty( $plugin_files ) ) {
            foreach ( $plugin_files as $plugin_file ) {
                if ( ! is_readable( "$plugin_root/$plugin_file" ) ) {
                    continue;
                }

                $plugin_data = get_plugin_data( "$plugin_root/$plugin_file", false, false );

                if ( empty( $plugin_data['Name'] ) ) {
                    continue;
                }

                $wp_plugins[ plugin_basename( $plugin_file ) ] = $plugin_data;
            }
        }
        return $wp_plugins;
    }

    public function get_staging_directory_info($path){
        $wpcontentDir = $path.DIRECTORY_SEPARATOR.'wp-content';
        $wpcontentDir = str_replace('\\', '/', $wpcontentDir);
        $tmp_path = str_replace('\\', '/', $path);
        if(!file_exists($wpcontentDir)){
            //return error
        }
        else {
            $directories = new \DirectoryIterator($wpcontentDir);
        }
        $wpvivid_staging_themes_dir  = '';
        $wpvivid_stating_plugins_dir = '';
        foreach ( $directories as $directory ) {
            if( false === ($path = $this->getPath( $tmp_path, $wpcontentDir, $directory )) ) {
                continue;
            }
            if($directory == 'themes'){
                $wpvivid_staging_themes_dir  = $wpcontentDir . '/' . 'themes';
            }
            if($directory == 'plugins'){
                $wpvivid_stating_plugins_dir = $wpcontentDir . '/' . 'plugins';
            }
        }
        $ret['themes_list']  = $this->get_staging_themes_info($wpvivid_staging_themes_dir);
        $ret['plugins_list'] = $this->get_staging_plugins_info($wpvivid_stating_plugins_dir);
        return $ret;
    }

    public function get_theme_plugin_info($root)
    {
        $theme_info['size']=$this->get_folder_size($root,0);
        return $theme_info;
    }

    public function get_folder_size($root,$size)
    {
        $count = 0;
        if(is_dir($root))
        {
            $handler = opendir($root);
            if($handler!==false)
            {
                while (($filename = readdir($handler)) !== false)
                {
                    if ($filename != "." && $filename != "..") {
                        $count++;

                        if (is_dir($root . DIRECTORY_SEPARATOR . $filename))
                        {
                            $size=$this->get_folder_size($root . DIRECTORY_SEPARATOR . $filename,$size);
                        } else {
                            $size+=filesize($root . DIRECTORY_SEPARATOR . $filename);
                        }
                    }
                }
                if($handler)
                    @closedir($handler);
            }

        }
        return $size;
    }

    public function staging_site()
    {
        $redirect=false;
        if(is_multisite())
        {
            $polylang_slug='polylang/polylang.php';
            if($this->check_plugin_active($polylang_slug))
            {
                $this->switch_to_blog(get_main_network_id());
            }
            else
            {
                switch_to_blog(get_main_network_id());
            }
            $staging_init=get_option('wpvivid_staging_init', false);
            $staging_finish=get_option('wpvivid_staging_finish', false);
            if($this->check_plugin_active($polylang_slug))
            {
                $this->restore_current_blog();
            }
            else
            {
                restore_current_blog();
            }
        }
        else
        {
            $staging_init=get_option('wpvivid_staging_init', false);
            $staging_finish=get_option('wpvivid_staging_finish', false);
        }

        if($staging_finish)
        {
            $this->flush_elementor_cache();
            $this->regenerate_css_files();
            $this->flush_divi_cache();

            delete_transient( 'wp_core_block_css_files' );

            $this->repair_htaccess();

            if ( function_exists( 'save_mod_rewrite_rules' ) ) {
                save_mod_rewrite_rules();
            }
            else{
                if(file_exists(ABSPATH . 'wp-admin/includes/misc.php')) {
                    require_once ABSPATH . 'wp-admin/includes/misc.php';
                }
                if ( function_exists( 'save_mod_rewrite_rules' ) ) {
                    save_mod_rewrite_rules();
                }
            }
            flush_rewrite_rules(true);

            delete_option('wpvivid_staging_finish');
            if(!$this->check_theme_exist())
            {
                $redirect=true;
            }
        }

        if($staging_init)
        {
            global $wp_rewrite;

            if($staging_init == 1){
                //create staging site
                $wp_rewrite->set_permalink_structure( null );
            }
            else{
                //push to live site
                $wp_rewrite->set_permalink_structure( $staging_init );
            }

            delete_option('wpvivid_staging_init');
        }

        $data=$this->get_staging_site_data_ex();
        if($data!==false)
        {
            $user = wp_get_current_user();
            if($user->exists())
            {
                wp_enqueue_style( "wpvivid-admin-bar", WPVIVID_STAGING_PLUGIN_URL . "includes/css/wpvivid-admin-bar.css", array(), $this->version );
            }

            if(!$this->is_login_page())
            {
                if(is_multisite())
                {
                    $polylang_slug='polylang/polylang.php';
                    if($this->check_plugin_active($polylang_slug))
                    {
                        $this->switch_to_blog(get_main_network_id());
                    }
                    else
                    {
                        switch_to_blog(get_main_network_id());
                    }
                    $options=get_option('wpvivid_staging_options', false);
                    if($this->check_plugin_active($polylang_slug))
                    {
                        $this->restore_current_blog();
                    }
                    else
                    {
                        restore_current_blog();
                    }
                }
                else
                {
                    $options=get_option('wpvivid_staging_options',array());
                }

                $staging_not_need_login=isset($options['not_need_login']) ? $options['not_need_login'] : true;

                if(!$staging_not_need_login)
                {
                    if(!current_user_can('manage_options'))
                    {
                        $this->output_login_page();
                    }
                }
            }
        }
        else
        {
            //fix create freshinstall, live site .htaccess rewrite rule empty
            $is_create_freshinstall=get_option('wpvivid_create_freshinstall', false);
            if($is_create_freshinstall)
            {
                if ( function_exists( 'save_mod_rewrite_rules' ) ) {
                    save_mod_rewrite_rules();
                }
                else{
                    if(file_exists(ABSPATH . 'wp-admin/includes/misc.php')) {
                        require_once ABSPATH . 'wp-admin/includes/misc.php';
                    }
                    if ( function_exists( 'save_mod_rewrite_rules' ) ) {
                        save_mod_rewrite_rules();
                    }
                }
                flush_rewrite_rules(true);

                delete_option('wpvivid_create_freshinstall');
            }
        }

        if($redirect)
        {
            ?>
            <script>
                location.reload();
            </script>
            <?php
        }
    }

    public function repair_htaccess()
    {
        $path=ABSPATH.'.htaccess';
        if(file_exists($path))
        {
            if(!is_multisite())
            {
                $data=file_get_contents($path);
                preg_match( "/RewriteBase\s*(.*)/", $data, $matches );
                if( !empty( $matches[1] ) )
                {
                    $home_root = parse_url( home_url() );
                    if ( isset( $home_root['path'] ) ) {
                        $home_root = trailingslashit( $home_root['path'] );
                    } else {
                        $home_root = '/';
                    }

                    $pattern = "/RewriteBase\s*(.*)/";
                    $replace = "RewriteBase $home_root";
                    $data = preg_replace( array($pattern), $replace, $data );
                }
                file_put_contents($path,$data);
            }
        }
    }

    public function flush_elementor_cache()
    {
        $wp_upload_dir=wp_upload_dir( null, false );
        $path =  $wp_upload_dir['basedir'] . '/elementor/css/' . '*';

        foreach ( glob( $path ) as $file_path ) {
            unlink( $file_path );
        }
        delete_post_meta_by_key( '_elementor_css' );
        delete_option( '_elementor_global_css' );
        delete_option( 'elementor-custom-breakpoints-files' );

        delete_option( '_elementor_assets_data' );
        delete_post_meta_by_key( '_elementor_inline_svg' );
    }

    public function regenerate_css_files()
    {
        delete_option( 'generateblocks_dynamic_css_posts' );
    }

    public function flush_divi_cache()
    {
        $current_active_theme = get_stylesheet();
        if($current_active_theme === 'Divi' || $current_active_theme === 'Divi-child')
        {
            if(class_exists('ET_Core_PageResource'))
            {
                if ( ! ET_Core_PageResource::can_write_to_filesystem() )
                {
                    return;
                }

                if ( ! ET_Core_PageResource::$data_utils )
                {
                    ET_Core_PageResource::startup();
                }

                $post_id='all';
                $owner='all';
                if ( method_exists( 'ET_Core_PageResource', 'do_remove_static_resources' ) )
                {
                    ET_Core_PageResource::do_remove_static_resources( $post_id, $owner );
                }
                else if( method_exists( 'ET_Core_PageResource', 'remove_static_resources' ) )
                {
                    ET_Core_PageResource::remove_static_resources( $post_id, $owner );
                }
            }
        }
    }

    public function check_theme_exist()
    {
        global $wp_theme_directories;
        $stylesheet = get_stylesheet();
        $theme_root = get_raw_theme_root( $stylesheet );
        if ( false === $theme_root ) {
            $theme_root = WP_CONTENT_DIR . '/themes';
        }
        elseif ( ! in_array( $theme_root, (array) $wp_theme_directories ) )
        {
            $theme_root = WP_CONTENT_DIR . $theme_root;
        }

        $theme_dir = $stylesheet;

        // Correct a situation where the theme is 'some-directory/some-theme' but 'some-directory' was passed in as part of the theme root instead.
        if ( ! in_array( $theme_root, (array) $wp_theme_directories ) && in_array( dirname( $theme_root ), (array) $wp_theme_directories ) ) {
            $stylesheet = basename( $theme_root ) . '/' .$theme_dir;
            $theme_root = dirname( $theme_root );
        }

        $theme_file       = $stylesheet . '/style.css';

        if( ! file_exists( $theme_root . '/' . $theme_file ) )
        {
            $themes=wp_get_themes();
            foreach ($themes as $theme)
            {
                switch_theme($theme->get_stylesheet());
                return false;
            }
        }
        return true;
    }

    public function is_login_page()
    {
        return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
    }

    public function wpvivid_logout_redirect()
    {
        $redirectTo = get_site_url();
        wp_logout();
        ?>
        <script>
            location.href='<?php echo $redirectTo; ?>';
        </script>
        <?php
    }

    public function output_login_page()
    {
        if(is_user_logged_in())
        {
            if(current_user_can( 'manage_options' ))
            {
                return false;
            }
            else
            {
                $this->wpvivid_logout_redirect();
            }
        }
        if( !isset( $_POST['log'] ) || !isset( $_POST['pwd'] ) )
        {
            wp_safe_redirect( wp_login_url() );
        }
        else
        {
            $user_data = get_user_by( 'login', $_POST['log'] );

            if( !$user_data ) {
                $user_data = get_user_by( 'email', $_POST['log'] );
            }

            if( $user_data )
            {
                if( wp_check_password( $_POST['pwd'], $user_data->user_pass, $user_data->ID ) )
                {

                    $rememberme = isset( $_POST['rememberme'] ) ? true : false;

                    wp_set_auth_cookie( $user_data->ID, $rememberme );
                    wp_set_current_user( $user_data->ID, $_POST['log'] );
                    do_action( 'wp_login', $_POST['log'], get_userdata( $user_data->ID ) );

                    $redirect_to = get_site_url() . '/wp-admin/';

                    if( !empty( $_POST['redirect_to'] ) ) {
                        $redirectTo = $_POST['redirect_to'];
                    }

                    header( 'Location:' . $redirectTo );
                }
            }
        }

        require_once( ABSPATH . 'wp-login.php' );

        ?>
        <script>
            jQuery(document).ready(function ()
            {
                jQuery('#loginform').prop('action', '');
            });
        </script>
        <?php

        die();
    }

    public function delete_site_ex()
    {
        $this->ajax_check_security();
        try {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
            } else {
                die();
            }

            $ret = $this->_delete_site_ex($id);

            $html = '';
            global $wpvivid_staging;
            $list = $wpvivid_staging->option->get_option('staging_site_data');
            if($list === false)
            {
                $list = array();
            }
            if (!empty($list))
            {
                $display_list = new WPvivid_Staging_List_Ex();
                $display_list->set_parent('staging_site_data');
                $display_list->set_list($list);
                $display_list->prepare_items();
                ob_start();
                $display_list->display();
                $html = ob_get_clean();
            }
            $ret['html'] = $html;
            echo json_encode($ret);
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function wpvivid_get_staging_database_object($use_additional_db, $db_user, $db_pass, $db_name, $db_host){
        if($use_additional_db){
            return new wpdb($db_user, $db_pass, $db_name, $db_host);
        }
        else{
            global $wpdb;
            return $wpdb;
        }
    }

    public function delete_cancel_staging_site(){
        $this->ajax_check_security();
        try {
            if (isset($_POST['staging_site_info'])) {
                $json = $_POST['staging_site_info'];
                $json = stripslashes($json);
                $staging_site_info = json_decode($json, true);
                $site_path = $staging_site_info['staging_path'];
                $use_additional_db = $staging_site_info['staging_additional_db'];
                $db_user = $staging_site_info['staging_additional_db_user'];
                $db_pass = $staging_site_info['staging_additional_db_pass'];
                $db_name = $staging_site_info['staging_additional_db_name'];
                $db_host = $staging_site_info['staging_additional_db_host'];
                if (!empty($site_path)) {
                    $home_path = untrailingslashit(ABSPATH);
                    if ($home_path != $site_path) {
                        if (file_exists($site_path)) {
                            if (!class_exists('WP_Filesystem_Base')) include_once(ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php');
                            if (!class_exists('WP_Filesystem_Direct')) include_once(ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php');

                            $fs = new WP_Filesystem_Direct(false);
                            $fs->rmdir($site_path, true);
                        }
                    }
                }

                $prefix = $staging_site_info['staging_table_prefix'];
                if (!empty($prefix)) {
                    $db = $this->wpvivid_get_staging_database_object($use_additional_db, $db_user, $db_pass, $db_name, $db_host);
                    $sql = $db->prepare("SHOW TABLES LIKE %s;", $db->esc_like($prefix) . '%');
                    $result = $db->get_results($sql, OBJECT_K);

                    if (!empty($result)) {
                        foreach ($result as $table_name => $value) {
                            $table['name'] = $table_name;
                            $db->query("DROP TABLE IF EXISTS {$table_name}");
                        }
                    }
                }

                $ret['result'] = 'success';
                echo json_encode($ret);
            }
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function _delete_site_ex($site_id)
    {
        try
        {
            set_time_limit(900);

            global $wpvivid_staging;
            $list = $wpvivid_staging->option->get_option('staging_site_data');

            if(!array_key_exists($site_id,$list))
            {
                $ret['result']='failed';
                $ret['error']='Site data not found';
                return $ret;
            }

            $site_data=$list[$site_id];
            $site_path=$site_data['path'];

            unset($list[$site_id]);
            $wpvivid_staging->option->update_option('staging_site_data',$list);
            $this->flush();

            set_time_limit(120);

            $home_path=untrailingslashit(ABSPATH);

            $prefix=$site_data['prefix'];
            if($site_data['db_connect']['use_additional_db'])
            {
                $db_instance=new wpdb($site_data['db_connect']['dbuser'],
                    $site_data['db_connect']['dbpassword'],
                    $site_data['db_connect']['dbname'],
                    $site_data['db_connect']['dbhost']);
            }
            else
            {
                global $wpdb;
                $db_instance=$wpdb;
            }

            if($home_path!=$site_path)
            {
                if (file_exists($site_path))
                {
                    if (!class_exists('WP_Filesystem_Base')) include_once(ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php');
                    if (!class_exists('WP_Filesystem_Direct')) include_once(ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php');

                    $fs = new WP_Filesystem_Direct(false);
                    $fs->rmdir($site_path, true);
                }
            }

            $sql=$db_instance->prepare("SHOW TABLES LIKE %s;", $db_instance->esc_like($prefix) . '%');
            $result = $db_instance->get_results($sql, OBJECT_K);
            if(!empty($result))
            {
                $db_instance->query( "SET foreign_key_checks = 0" );
                foreach ($result as $table_name=>$value)
                {
                    $table['name']=$table_name;
                    $db_instance->query( "DROP TABLE IF EXISTS {$table_name}" );
                }
            }


            $ret['result']='success';
        }
        catch (Exception $error)
        {
            $ret['result']='failed';
            $ret['error']=$error->getMessage();
        }

        return $ret;
    }

    public function flush()
    {
        $ret['result']='success';
        $text=json_encode($ret);
        if(!headers_sent())
        {
            header('Content-Length: '.( ( ! empty( $text ) ) ? strlen( $text ) : '0' ));
            header('Connection: close');
            header('Content-Encoding: none');
        }
        if (session_id())
            session_write_close();

        echo $text;

        if(function_exists('fastcgi_finish_request'))
        {
            fastcgi_finish_request();
        }
        else
        {
            if(ob_get_level()>0)
                ob_flush();
            flush();
        }
    }

    public function check_dir_is_empty($dir)
    {
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle)))
        {
            if ($entry != "." && $entry != "..")
            {
                if (is_dir($dir.DIRECTORY_SEPARATOR.$entry) && $entry === '.well-known')
                {

                }
                else
                {
                    closedir($handle);
                    return false;
                }
            }
        }
        closedir($handle);
        return true;
    }

    public function check_staging_dir()
    {
       $this->ajax_check_security();
        try
        {
            $ret['result'] = 'success';
            if(!isset($_POST['path']) || empty($_POST['path']) || !is_string($_POST['path']))
            {
                $ret['result']='failed';
                $ret['error']='A site path is required.';
                echo json_encode($ret);
                die();
            }

            $path = sanitize_text_field($_POST['path']);

            if(!isset($_POST['table_prefix']) || empty($_POST['table_prefix']) || !is_string($_POST['table_prefix']))
            {
                $ret['result']='failed';
                $ret['error']='A table prefix is required.';
                echo json_encode($ret);
                die();
            }

            $table_prefix = sanitize_text_field($_POST['table_prefix']);

            $is_directory = false;
            if (isset($_POST['root_dir']) && $_POST['root_dir'] == 0)
            {
                $path = untrailingslashit(ABSPATH) . DIRECTORY_SEPARATOR. $path;
                $is_directory = true;
            }
            else if(isset($_POST['root_dir']) && $_POST['root_dir'] == 1)
            {
                $path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path;
                $is_directory = true;
            }

            if($is_directory)
            {
                if (file_exists($path))
                {
                    $ret['result'] = 'failed';
                    $ret['error'] = 'A folder with the same name already exists in website\'s root directory.';
                }
                else
                {
                    if (mkdir($path, 0755, true))
                    {
                        rmdir($path);
                    } else {
                        $ret['result'] = 'failed';
                        $ret['error'] = 'Create directory is not allowed in ' . $path . '.Please check the directory permissions and try again';
                    }
                }
            }

            if(isset($_POST['root_dir']) && $_POST['root_dir'] == 2)
            {
                if(!file_exists($path))
                {
                    $ret['result']='failed';
                    $ret['error']='The directory does not exist, please check and make sure to enter a valid directory.';
                    echo json_encode($ret);
                    die();
                }

                $test_file_path = untrailingslashit($path).DIRECTORY_SEPARATOR.'index.html';
                @unlink($test_file_path);

                if(!$this->check_dir_is_empty($path))
                {
                    $ret['result']='failed';
                    $ret['error']='The directory you entered is not empty, please delete all files in the directory then try again.';
                    echo json_encode($ret);
                    die();
                }

                $mk_res = fopen($test_file_path, 'wb');
                if (!$mk_res)
                {
                    $ret['result']='failed';
                    $ret['error']='The directory is not writable, please make sure set it\'s permissions to 755.';
                    echo json_encode($ret);
                    die();
                }
                else
                {
                    fwrite($mk_res, 'vivid connect test');
                    @fclose($mk_res);
                }

                if(isset($_POST['subdomain']))
                {
                    $url = $_POST['subdomain'];
                    $options=array();
                    $options['timeout']=30;
                    $options['sslverify']=false;
                    if(empty($options['body']))
                    {
                        $options['body']=array();
                    }

                    $retry=0;
                    $max_retry=3;

                    while($retry<$max_retry)
                    {
                        $request=wp_remote_request($url,$options);

                        if(!is_wp_error($request) && ($request['response']['code'] == 200))
                        {
                            $body= wp_remote_retrieve_body($request);
                            if($body !== 'vivid connect test')
                            {
                                $ret['result']='failed';
                                $ret['error']='The subdomain is not pointed to the directory you entered. Please check your DNS configuration.';
                            }
                            else
                            {
                                @unlink($test_file_path);
                                break;
                            }
                        }
                        else
                        {
                            $ret['result']='failed';
                            if ( is_wp_error( $request ) )
                            {
                                $error_message = $request->get_error_message();
                                $ret['error']="Sorry, something went wrong: $error_message. Please try again later or contact us.";
                            }
                            else if($request['response']['code'] != 200)
                            {
                                $ret['error']=$request['response']['message'];
                            }
                            else {
                                $ret['error']=$request;
                            }
                        }

                        $retry++;
                    }

                    @unlink($test_file_path);
                    if($ret['result'] === 'failed')
                    {
                        echo json_encode($ret);
                        die();
                    }
                }
                else
                {
                    @unlink($test_file_path);
                    $ret['result']='failed';
                    $ret['error']='Subdomain is required.';
                    echo json_encode($ret);
                    die();
                }
            }

            if(isset($_POST['additional_db']))
            {
                $additional_db_json = $_POST['additional_db'];
                $additional_db_json = stripslashes($additional_db_json);
                $additional_db_options = json_decode($additional_db_json, true);
                if($additional_db_options['additional_database_check'] === '1')
                {
                    $db_user = sanitize_text_field($additional_db_options['additional_database_info']['db_user']);
                    $db_pass = sanitize_text_field($additional_db_options['additional_database_info']['db_pass']);
                    $db_host = sanitize_text_field($additional_db_options['additional_database_info']['db_host']);
                    $db_name = sanitize_text_field($additional_db_options['additional_database_info']['db_name']);
                    $db = new wpdb($db_user, $db_pass, $db_name, $db_host);
                    $sql = $db->prepare("SHOW TABLES LIKE %s;", $db->esc_like($table_prefix) . '%');
                    $result = $db->get_results($sql, OBJECT_K);
                    if (!empty($result))
                    {
                        $ret['result'] = 'failed';
                        $ret['error'] = 'The table prefix already exists.';
                    }
                }
                else
                {
                    global $wpdb;
                    $sql = $wpdb->prepare("SHOW TABLES LIKE %s;", $wpdb->esc_like($table_prefix) . '%');
                    $result = $wpdb->get_results($sql, OBJECT_K);
                    if (!empty($result))
                    {
                        $ret['result'] = 'failed';
                        $ret['error'] = 'The table prefix already exists.';
                    }
                }
            }
            else
            {
                global $wpdb;
                $sql = $wpdb->prepare("SHOW TABLES LIKE %s;", $wpdb->esc_like($table_prefix) . '%');
                $result = $wpdb->get_results($sql, OBJECT_K);
                if (!empty($result))
                {
                    $ret['result'] = 'failed';
                    $ret['error'] = 'The table prefix already exists.';
                }
            }
            echo json_encode($ret);
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function check_filesystem_permissions()
    {
        $this->ajax_check_security();
        try{
            if(!isset($_POST['path']) || empty($_POST['path']) || !is_string($_POST['path']))
            {
                $ret['result']='failed';
                $ret['error']='A site path is required.';
                echo json_encode($ret);
                die();
            }

            $path = sanitize_text_field($_POST['path']);
            $src_path = untrailingslashit(ABSPATH);

            if(isset($_POST['root_dir'])&&$_POST['root_dir']==0)
            {
                $des_path = untrailingslashit(ABSPATH) . '/' . $path;
            }
            else if (isset($_POST['root_dir'])&&$_POST['root_dir']==1)
            {
                $des_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path;
            }
            else
            {
                $test_dir = 'wpvividstg_testfolder';
                $des_path = untrailingslashit($path) . '/' . $test_dir;
            }

            $mk_res = mkdir($des_path,0755,true);
            if (!$mk_res)
            {
                $ret['result']='failed';
                $ret['error']='The directory where the staging site will be installed is not writable. Please set the permissions of the directory to 755 then try it again.';
                echo json_encode($ret);
                die();
            }

            $test_file_name = 'wpvividstg_test_file.txt';
            $test_file_path = $des_path.DIRECTORY_SEPARATOR.$test_file_name;
            $mk_res = fopen($test_file_path, 'wb');
            if (!$mk_res)
            {
                if(file_exists($des_path))
                    @rmdir($des_path);
                $ret['result']='failed';
                $ret['error']='The directory where the staging site will be installed is not writable. Please set the permissions of the directory to 755 then try it again.';
                echo json_encode($ret);
                die();
            }

            fclose($mk_res);
            @unlink($test_file_path);
            if(file_exists($des_path))
                @rmdir($des_path);

            $ret['result'] = 'success';
            echo json_encode($ret);
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function output_staging()
    {
        $data=$this->get_staging_site_data();
        $data['live_site_staging_url'] = str_replace('wpvivid-staging', 'WPvivid_Staging', $data['live_site_staging_url']);
        $parent_url    = $data['parent_admin_url'];
        $live_site_url = $data['live_site_url'];
        $push_site_url = $data['live_site_staging_url'];
        ?>

        <div class="postbox quickbackup-addon">
            <table class="wp-list-table widefat plugins" style="width: 100%;">
                <tbody>
                <tr>
                    <td class="column-primary" style="border-bottom:1px solid #f1f1f1;background-color:#e2b300; color:#fff;" colspan="3">
                        <span><strong>Note: This is a staging site: </strong></span><span><?php echo _e(basename(get_home_path())); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="column-primary" style="margin: 10px;">
                        <div>
                            <div style="margin:auto; width:100px; height:100px; right:50%;">
                                <img src="<?php echo esc_url(WPVIVID_STAGING_PLUGIN_URL.'includes/images/staging-site.png'); ?>">
                            </div>
                        </div>
                    </td>
                    <td class="column-description desc">
                        <div>
                            <div style="height:20px;display:block;">The details of the staging enviroment</div>
                            <div style="height:20px;display:block;"><span>Database: </span><span><?php echo _e(DB_NAME); ?></span></div>
                            <div style="height:20px;display:block;"><span>Table Prefix: </span><span><?php echo _e($data['prefix']); ?></span></div>
                            <div style="height:20px;display:block;"><span>Site Directory: </span><span><?php echo _e(get_home_path()); ?></span></div>
                            <div style="height:20px;display:block;"><span>Live Site URL: </span><span><a href="<?php echo esc_url($live_site_url); ?>"><?php echo esc_url($live_site_url); ?></a></span></div>
                            <div style="height:20px;display:block;"><span>Live Site Staging: </span><span><a href="<?php echo esc_url($push_site_url); ?>"><?php echo esc_url($push_site_url); ?></a></span></div>
                        </div>
                    </td>
                    <td class="column-description desc">
                        <div>
                            <div style="height:20px;display:block;margin-bottom:10px;text-align:center;">
                                <input class="button-primary" type="submit" name="post" value="Click here to migrate the staging site to live site" onclick="wpvivid_jump_live_staging();">
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <script>
            function wpvivid_jump_live_staging(){
                location.href='<?php echo $push_site_url; ?>';
            }
        </script>
        <!--<div id="staging_site">
            <?php $this->get_recent_post()?>
        </div>-->
        <?php
    }

    public function get_recent_post()
    {
        //set_prefix
        $post_type='post';
        $args = array(
            'orderby' => 'modified',
            'ignore_sticky_posts' => '1',
            'page_id' => 0,
            'posts_per_page' => 1,
            'post_type' => $post_type
        );

        $loop = new WP_Query( $args );
        $string = '<ul>';
        while( $loop->have_posts())
        {
            $loop->the_post();
            $string .= '<li><a href="' . get_permalink( $loop->post->ID ) . '"> ' .get_the_title( $loop->post->ID ) . '</a> ( '. get_the_modified_date() .') </li>';
        }
        $string .= '</ul>';
        $string.='<input id="wpvivid_update_post" type="button" class="button button-primary" value="Update">';
        echo $string;

    }

    public function update_recent_post()
    {
        $post_type='post';
        $args = array(
            'orderby' => 'modified',
            'ignore_sticky_posts' => '1',
            'page_id' => 0,
            'posts_per_page' => 1,
            'post_type' => $post_type
        );

        $loop = new WP_Query( $args );
        global $wpdb;

        $ret['result']='success';
        $posts=array();

        while( $loop->have_posts())
        {
            $post=$loop->next_post();
            $posts[$post->ID]['post']=$post;
            $posts[$post->ID]['postmeta']= $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID ) );
        }


        $dbuser     = defined( 'DB_USER' ) ? DB_USER : '';
        $dbpassword = defined( 'DB_PASSWORD' ) ? DB_PASSWORD : '';
        $dbname     = 'test_post_import';
        $dbhost     = defined( 'DB_HOST' ) ? DB_HOST : '';

        $wpdb = new wpdb( $dbuser, $dbpassword, $dbname, $dbhost );
        $wpdb->set_prefix('wp_');

        foreach ($posts as $id=>$post)
        {
            $post_exists = post_exists( $post['post']->post_title, '', $post['post']->post_type );
            if ( $post_exists && get_post_type( $post_exists ) == $post['post']->post_type )
            {
                wp_update_post($post['post']);
                foreach ($post['postmeta'] as $meta)
                {
                    if(get_post_meta($id,$meta->meta_key,true))
                    {
                        update_post_meta( $id, $meta->meta_key ,  $meta->meta_value  );
                    }
                    else
                    {
                        add_post_meta( $id, $meta->meta_key ,  $meta->meta_value  );
                    }
                }
            }
            else
            {
                $postdata = array(
                    'import_id' => $post['post']->id, 'post_author' => $post['post']->post_author, 'post_date' => $post['post']->post_date,
                    'post_date_gmt' => $post['post']->post_date_gmt, 'post_content' => $post['post']->post_content,
                    'post_excerpt' => $post['post']->post_excerpt, 'post_title' => $post['post']->post_title,
                    'post_status' => $post['post']->post_status, 'post_name' => $post['post']->post_name,
                    'comment_status' =>  $post['post']->comment_status, 'ping_status' => $post['post']->ping_status,
                    'guid' => $post['post']->guid, 'post_parent' => $post['post']->post_parent, 'menu_order' => $post['post']->menu_order,
                    'post_type' => $post['post']->post_type, 'post_password' => $post['post']->post_password
                );
                $post_id = wp_insert_post( $postdata, true );
                foreach ($post['postmeta'] as $meta)
                {
                    if(get_post_meta($post_id,$meta->meta_key,true))
                    {
                        update_post_meta( $post_id, $meta->meta_key ,  $meta->meta_value  );
                    }
                    else
                    {
                        add_post_meta( $post_id, $meta->meta_key ,  $meta->meta_value  );
                    }
                }
            }

        }

        echo json_encode($ret);
        die();
    }

    public function cancel_staging()
    {
        $this->ajax_check_security();

        $this->option->update_option('staging_task_cancel',true);

        $ret['result']='success';
        echo json_encode($ret);

        die();
    }

    public function test_additional_database_connect(){
        $this->ajax_check_security();
        try {
            if (isset($_POST['database_info']) && !empty($_POST['database_info']) && is_string($_POST['database_info'])) {
                $data = $_POST['database_info'];
                $data = stripslashes($data);
                $json = json_decode($data, true);
                $db_user = sanitize_text_field($json['db_user']);
                $db_pass = sanitize_text_field($json['db_pass']);
                $db_host = sanitize_text_field($json['db_host']);
                $db_name = sanitize_text_field($json['db_name']);

                $db = new wpdb($db_user, $db_pass, $db_name, $db_host);
                // Can not connect to mysql
                if (!empty($db->error->errors['db_connect_fail']['0'])) {
                    $ret['result'] = 'failed';
                    $ret['error'] = 'Failed to connect to MySQL server. Please try again later.';
                    echo json_encode($ret);
                    die();
                }

                // Can not connect to database
                $db->select($db_name);
                if (!$db->ready) {
                    $ret['result'] = 'failed';
                    $ret['error'] = 'Unable to connect to MySQL database. Please try again later.';
                    echo json_encode($ret);
                    die();
                }
                $ret['result'] = 'success';

                echo json_encode($ret);
            }
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function update_staging_exclude_extension(){
        $this->ajax_check_security();
        try {
            if (isset($_POST['type']) && !empty($_POST['type']) && is_string($_POST['type']) &&
                isset($_POST['exclude_content']) && !empty($_POST['exclude_content']) && is_string($_POST['exclude_content'])) {
                $type = sanitize_text_field($_POST['type']);
                $value = sanitize_text_field($_POST['exclude_content']);

                $staging_option = self::wpvivid_get_staging_history();
                if (empty($staging_option)) {
                    $staging_option = array();
                }

                if ($type === 'upload') {
                    $staging_option['upload_extension'] = array();
                    $str_tmp = explode(',', $value);
                    for ($index = 0; $index < count($str_tmp); $index++) {
                        if (!empty($str_tmp[$index])) {
                            $staging_option['upload_extension'][] = $str_tmp[$index];
                        }
                    }
                } else if ($type === 'content') {
                    $staging_option['content_extension'] = array();
                    $str_tmp = explode(',', $value);
                    for ($index = 0; $index < count($str_tmp); $index++) {
                        if (!empty($str_tmp[$index])) {
                            $staging_option['content_extension'][] = $str_tmp[$index];
                        }
                    }
                } else if ($type === 'additional_file') {
                    $staging_option['additional_file_extension'] = array();
                    $str_tmp = explode(',', $value);
                    for ($index = 0; $index < count($str_tmp); $index++) {
                        if (!empty($str_tmp[$index])) {
                            $staging_option['additional_file_extension'][] = $str_tmp[$index];
                        }
                    }
                }

                self::wpvivid_set_staging_history($staging_option);

                $ret['result'] = 'success';
                echo json_encode($ret);
            }
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_table_list($prefix,&$mu_exclude_table,$task=false,$exculude_user=true)
    {

        global $wpdb;

        if($task===false)
        {
            $db=$wpdb;
        }
        else
        {
            $db=$task->get_site_db_instance();
        }

        $sql=$db->prepare("SHOW TABLES LIKE %s;", $wpdb->esc_like($prefix) . '%');
        $result = $db->get_results($sql, OBJECT_K);
        foreach ($result as $table_name=>$value)
        {
            if($prefix==$db->base_prefix)
            {
                if ( 1 == preg_match('/^' . $db->base_prefix . '\d+_/', $table_name) )
                {

                }
                else
                {
                    if($table_name==$db->base_prefix.'blogs'&&$exculude_user!==false)
                        continue;
                    if($exculude_user===false)
                    {
                        if($table_name==$db->base_prefix.'users'||$table_name==$db->base_prefix.'usermeta')
                            continue;
                    }
                    $mu_exclude_table[]=$table_name;
                }
            }
            else
            {
                $mu_exclude_table[]=$table_name;
            }
        }
    }

    public function get_upload_exclude_folder($site_id,$des=false,$task=false)
    {
        if($des)
        {
            $upload_dir = wp_upload_dir();
            $dir = str_replace( ABSPATH, '', $upload_dir['basedir'] );
            $src_path=$task->get_site_path();
            $upload_basedir=$src_path.DIRECTORY_SEPARATOR.$dir;
            if ( defined( 'MULTISITE' ) )
            {
                $upload_basedir = $upload_basedir.'/sites/' . $site_id;
            } else {
                $upload_basedir = $upload_basedir.'/' . $site_id;
            }
            return $upload_basedir;
        }
        else
        {
            $upload= $this->get_site_upload_dir($site_id);
            return $upload['basedir'];
        }
    }

    public function get_site_upload_dir($site_id, $time = null, $create_dir = true, $refresh_cache = false)
    {
        static $cache = array(), $tested_paths = array();

        $key = sprintf( '%d-%s',$site_id, (string) $time );

        if ( $refresh_cache || empty( $cache[ $key ] ) ) {
            $cache[ $key ] = $this->_wp_upload_dir( $site_id,$time );
        }

        /**
         * Filters the uploads directory data.
         *
         * @since 2.0.0
         *
         * @param array $uploads Array of upload directory data with keys of 'path',
         *                       'url', 'subdir, 'basedir', and 'error'.
         */
        $uploads = apply_filters( 'upload_dir', $cache[ $key ] );

        if ( $create_dir ) {
            $path = $uploads['path'];

            if ( array_key_exists( $path, $tested_paths ) ) {
                $uploads['error'] = $tested_paths[ $path ];
            } else {
                if ( ! wp_mkdir_p( $path ) ) {
                    if ( 0 === strpos( $uploads['basedir'], ABSPATH ) ) {
                        $error_path = str_replace( ABSPATH, '', $uploads['basedir'] ) . $uploads['subdir'];
                    } else {
                        $error_path = basename( $uploads['basedir'] ) . $uploads['subdir'];
                    }

                    $uploads['error'] = sprintf(
                    /* translators: %s: directory path */
                        __( 'Unable to create directory %s. Is its parent directory writable by the server?' ),
                        esc_html( $error_path )
                    );
                }

                $tested_paths[ $path ] = $uploads['error'];
            }
        }

        return $uploads;
    }

    public function _wp_upload_dir($site_id, $time = null ) {
        $siteurl     = get_option( 'siteurl' );
        $upload_path = trim( get_option( 'upload_path' ) );

        if ( empty( $upload_path ) || 'wp-content/uploads' == $upload_path ) {
            $dir = WP_CONTENT_DIR . '/uploads';
        } elseif ( 0 !== strpos( $upload_path, ABSPATH ) ) {
            // $dir is absolute, $upload_path is (maybe) relative to ABSPATH
            $dir = path_join( ABSPATH, $upload_path );
        } else {
            $dir = $upload_path;
        }

        if ( ! $url = get_option( 'upload_url_path' ) ) {
            if ( empty( $upload_path ) || ( 'wp-content/uploads' == $upload_path ) || ( $upload_path == $dir ) ) {
                $url = WP_CONTENT_URL . '/uploads';
            } else {
                $url = trailingslashit( $siteurl ) . $upload_path;
            }
        }

        /*
         * Honor the value of UPLOADS. This happens as long as ms-files rewriting is disabled.
         * We also sometimes obey UPLOADS when rewriting is enabled -- see the next block.
         */
        if ( defined( 'UPLOADS' ) && ! ( is_multisite() && get_site_option( 'ms_files_rewriting' ) ) ) {
            $dir = ABSPATH . UPLOADS;
            $url = trailingslashit( $siteurl ) . UPLOADS;
        }

        // If multisite (and if not the main site in a post-MU network)
        if ( is_multisite() && ! ( is_main_network() && is_main_site($site_id) && defined( 'MULTISITE' ) ) ) {
            if ( ! get_site_option( 'ms_files_rewriting' ) ) {
                /*
                 * If ms-files rewriting is disabled (networks created post-3.5), it is fairly
                 * straightforward: Append sites/%d if we're not on the main site (for post-MU
                 * networks). (The extra directory prevents a four-digit ID from conflicting with
                 * a year-based directory for the main site. But if a MU-era network has disabled
                 * ms-files rewriting manually, they don't need the extra directory, as they never
                 * had wp-content/uploads for the main site.)
                 */

                if ( defined( 'MULTISITE' ) ) {
                    $ms_dir = '/sites/' . $site_id;
                } else {
                    $ms_dir = '/' . $site_id;
                }

                $dir .= $ms_dir;
                $url .= $ms_dir;
            } elseif ( defined( 'UPLOADS' ) && ! ms_is_switched() ) {
                /*
                 * Handle the old-form ms-files.php rewriting if the network still has that enabled.
                 * When ms-files rewriting is enabled, then we only listen to UPLOADS when:
                 * 1) We are not on the main site in a post-MU network, as wp-content/uploads is used
                 *    there, and
                 * 2) We are not switched, as ms_upload_constants() hardcodes these constants to reflect
                 *    the original blog ID.
                 *
                 * Rather than UPLOADS, we actually use BLOGUPLOADDIR if it is set, as it is absolute.
                 * (And it will be set, see ms_upload_constants().) Otherwise, UPLOADS can be used, as
                 * as it is relative to ABSPATH. For the final piece: when UPLOADS is used with ms-files
                 * rewriting in multisite, the resulting URL is /files. (#WP22702 for background.)
                 */

                if ( defined( 'BLOGUPLOADDIR' ) ) {
                    $dir = untrailingslashit( BLOGUPLOADDIR );
                } else {
                    $dir = ABSPATH . UPLOADS;
                }
                $url = trailingslashit( $siteurl ) . 'files';
            }
        }

        $basedir = $dir;
        $baseurl = $url;

        $subdir = '';
        if ( get_option( 'uploads_use_yearmonth_folders' ) ) {
            // Generate the yearly and monthly dirs
            if ( ! $time ) {
                $time = current_time( 'mysql' );
            }
            $y      = substr( $time, 0, 4 );
            $m      = substr( $time, 5, 2 );
            $subdir = "/$y/$m";
        }

        $dir .= $subdir;
        $url .= $subdir;

        return array(
            'path'    => $dir,
            'url'     => $url,
            'subdir'  => $subdir,
            'basedir' => $basedir,
            'baseurl' => $baseurl,
            'error'   => false,
        );
    }

    public static function wpvivid_set_staging_history($option){
        update_option('wpvivid_staging_history', $option);
    }

    public static function wpvivid_get_staging_history(){
        $options = get_option('wpvivid_staging_history', array());
        return $options;
    }

    public static function wpvivid_set_push_staging_history($option){
        update_option('wpvivid_push_staging_history', $option);
    }

    public static function wpvivid_get_push_staging_history(){
        $options = get_option('wpvivid_push_staging_history', array());
        return $options;
    }

    private function transfer_path($path)
    {
        $path = str_replace('\\','/',$path);
        $values = explode('/',$path);
        return implode(DIRECTORY_SEPARATOR,$values);
    }

    public function set_staging_option()
    {
        $options=get_option('wpvivid_staging_options');

        if(isset($options['staging_db_insert_count']))
            $option['staging_db_insert_count']=$options['staging_db_insert_count'];
        else
            $option['staging_db_insert_count']=WPVIVID_STAGING_DB_INSERT_COUNT_EX;

        if(isset($options['staging_db_replace_count']))
            $option['staging_db_replace_count']=$options['staging_db_replace_count'];
        else
            $option['staging_db_replace_count']=WPVIVID_STAGING_DB_REPLACE_COUNT_EX;

        if(isset($options['staging_memory_limit']))
            $option['staging_memory_limit']=$options['staging_memory_limit'];
        else
            $option['staging_memory_limit']=WPVIVID_STAGING_MEMORY_LIMIT_EX;

        if(isset($options['staging_file_copy_count']))
            $option['staging_file_copy_count']=$options['staging_file_copy_count'];
        else
            $option['staging_file_copy_count']=WPVIVID_STAGING_FILE_COPY_COUNT_EX;

        if(isset($options['staging_exclude_file_size'])) {
            $option['staging_exclude_file_size'] = $options['staging_exclude_file_size'];
        }
        else {
            $option['staging_exclude_file_size'] = WPVIVID_STAGING_MAX_FILE_SIZE_EX;
        }

        if(isset($options['staging_max_execution_time']))
            $option['staging_max_execution_time']=$options['staging_max_execution_time'];
        else
            $option['staging_max_execution_time']=WPVIVID_STAGING_MAX_EXECUTION_TIME_EX;

        if(isset($options['staging_resume_count']))
            $option['staging_resume_count']=$options['staging_resume_count'];
        else
            $option['staging_resume_count']=WPVIVID_STAGING_RESUME_COUNT_EX;

        if(isset($options['staging_overwrite_permalink']))
            $option['staging_overwrite_permalink']=$options['staging_overwrite_permalink'];
        else
            $option['staging_overwrite_permalink']=1;

        /*if(isset($options['force_files_mode']))
            $option['force_files_mode']=$options['force_files_mode'];
        else
            $option['force_files_mode']=0;*/

        if(isset($options['files_permission']))
            $option['files_permission']=$options['files_permission'];
        else if(isset($options['force_files_mode']))
            $option['files_permission']='0644';
        else
            $option['files_permission']='0755';

        return $option;
    }

    public function push_site()
    {
        $this->ajax_check_security();
        try
        {
            $task_id = $_POST['id'];
            if (!empty($task_id))
            {
                global $wpvivid_staging;
                $staging_site_data = $wpvivid_staging->option->get_option('staging_site_data');

                if(isset($staging_site_data[$task_id]['path']))
                {
                    $home_path = $staging_site_data[$task_id]['path'];
                    $home_path = untrailingslashit($home_path);
                    $home_path = str_replace('\\', '/', $home_path);
                    $ret['result'] = 'success';
                    $ret['home_path'] = $home_path . '/';
                    $ret['uploads_path'] = $home_path . '/wp-content/uploads/';
                    $ret['content_path'] = $home_path . '/wp-content/';
                    $ret['themes_path'] = $home_path . '/wp-content/themes/';
                    $ret['plugins_path'] = $home_path. '/wp-content/plugins/';
                }
                else
                {
                    $ret['result'] = 'failed';
                    $ret['error'] = 'not found staging path';
                }
            } else {
                $ret['result'] = 'failed';
                $ret['error'] = 'not found site';
            }
            echo json_encode($ret);
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function copy_site()
    {
        $this->ajax_check_security();
        try
        {
            $task_id = $_POST['id'];
            if (!empty($task_id))
            {

                $upload_dir = wp_upload_dir();
                $upload_path = $upload_dir['basedir'];
                $upload_path = str_replace('\\','/',$upload_path);
                $upload_path = $upload_path.'/';
                $content_dir = WP_CONTENT_DIR;
                $content_path = str_replace('\\','/',$content_dir);
                $content_path = $content_path.'/';
                $home_path = str_replace('\\','/', get_home_path());

                $theme_path = str_replace('\\','/', get_theme_root());
                $theme_path = $theme_path.'/';

                $plugin_path = str_replace('\\','/', WP_PLUGIN_DIR);
                $plugin_path = $plugin_path.'/';

                $ret['result'] = 'success';
                $ret['home_path'] = $home_path;
                $ret['uploads_path'] = $upload_path ;
                $ret['content_path'] = $content_path;
                $ret['themes_path'] = $theme_path;
                $ret['plugins_path'] = $plugin_path;
            } else {
                $ret['result'] = 'failed';
                $ret['error'] = 'not found site';
            }
            echo json_encode($ret);
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function edit_staging_comment()
    {
        $this->ajax_check_security();
        try
        {
            $task_id = $_POST['id'];
            global $wpvivid_staging;
            $list = $wpvivid_staging->option->get_option('staging_site_data');
            if(!empty($list))
            {
                foreach ($list as $id => $staging)
                {
                    if($id === $task_id)
                    {
                        $staging['comment'] = $_POST['staging_comment'];
                        $list[$id]['comment'] = $staging['comment'];
                        $wpvivid_staging->option->update_option('staging_site_data',$list);
                        break;
                    }
                }
            }
            $ret['result'] = 'success';
            echo json_encode($ret);
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_mu_site_info()
    {
        $this->ajax_check_security();
        try
        {
            $task_id = $_POST['id'];
            if (!empty($task_id))
            {
                global $wpdb;
                $old_db=$wpdb;
                global $wpvivid_staging;
                $staging_site_data = $wpvivid_staging->option->get_option('staging_site_data');
                if(isset($staging_site_data[$task_id]))
                {
                    $html='';

                    if(is_multisite())
                    {
                        if($_POST['copy']=='true')
                        {
                            $subsites=$this->get_mu_sites($staging_site_data[$task_id]);
                            $is_restore=true;
                            $home_path = $staging_site_data[$task_id]['path'];
                            $home_path = untrailingslashit($home_path);
                            $home_path = str_replace('\\', '/', $home_path);
                            $ret['home_path'] = $home_path . '/';
                            $ret['uploads_path'] = $home_path . '/wp-content/uploads/';
                            $ret['content_path'] = $home_path . '/wp-content/';
                            $ret['theme_path'] = $home_path . '/wp-content/themes/';
                            $ret['plugin_path'] = $home_path . '/wp-content/plugins/';
                        }
                        else
                        {
                            $subsites=get_sites();
                            $is_restore=false;
                            $upload_dir = wp_upload_dir();
                            $upload_path = $upload_dir['basedir'];
                            $upload_path = str_replace('\\','/',$upload_path);
                            $upload_path = $upload_path.'/';
                            $content_dir = WP_CONTENT_DIR;
                            $content_path = str_replace('\\','/',$content_dir);
                            $content_path = $content_path.'/';
                            $home_path = str_replace('\\','/', get_home_path());
                            $theme_path = str_replace('\\','/', get_theme_root());
                            $theme_path = $theme_path.'/';
                            $plugin_path = str_replace('\\','/', WP_PLUGIN_DIR);
                            $plugin_path = $plugin_path.'/';
                            $ret['home_path'] = $home_path;
                            $ret['uploads_path'] = $upload_path ;
                            $ret['content_path'] = $content_path;
                            $ret['theme_path'] = $theme_path;
                            $ret['plugin_path'] = $plugin_path;
                        }
                        $list=array();
                        $main_site_id='0';
                        $main_site_name='';
                        $main_site_title=get_option( 'blogname' );
                        $main_site_description=get_option( 'blogdescription' );

                        $staging_data = $staging_site_data[$task_id];

                        foreach ($subsites as $subsite)
                        {
                            if($_POST['copy']=='true')
                            {
                                if($staging_data['main_site_id']==get_object_vars($subsite)["blog_id"])
                                {
                                    $main_site_id=get_object_vars($subsite)["blog_id"];
                                    $main_site_name = get_object_vars($subsite)["domain"].get_object_vars($subsite)["path"];
                                }
                                else
                                {
                                    $list[]=$subsite;
                                }
                            }
                            else
                            {
                                if(is_main_site(get_object_vars($subsite)["blog_id"]))
                                {
                                    $main_site_id=get_object_vars($subsite)["blog_id"];
                                    $main_site_name = get_object_vars($subsite)["domain"].get_object_vars($subsite)["path"];
                                }
                                else
                                {
                                    $list[]=$subsite;
                                }
                            }
                        }


                        $html.='';
                        if($staging_site_data['mu_single'])
                        {

                        }
                        else
                        {
                            $html.='<div style="padding:10px; background: #fff; border: 1px solid #ccd0d4; border-radius: 6px; margin-top: 10px;">';
                        }

                        if($is_restore)
                        {
                            $core_descript = 'If the staging site and the live site have the same version of WordPress. Then it is not necessary to copy the WordPress MU core files to the live site';
                            $db_descript = 'All the tables in the WordPress MU database except for subsites tables.';
                            $themes_plugins_descript = 'All the plugins and themes files used by the MU network. The activated plugins and themes will be copied to the live site by default. A child theme must be copied if it exists.';
                            $uploads_descript = 'The folder where images and media files of the main site are stored by default. All files will be copied to the live site by default. You can exclude folders you do not want to copy.';
                            $contents_descript = '<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to copy to the live site, except for the wp-content/uploads folder.';
                            $additional_file_descript = '<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to copy to the live site';
                            $select_subsite = 'Select the subsites you wish to copy to the live site';
                        }
                        else {
                            $core_descript = 'If the staging site and the live site have the same version of WordPress. Then it is not necessary to update the WordPress MU core files to the staging site.';
                            $db_descript = 'All the tables in the WordPress MU database except for subsites tables.';
                            $themes_plugins_descript = 'All the plugins and themes files used by the MU network. The activated plugins and themes will be updated to the staging site by default. A child theme must be updated if it exists.';
                            $uploads_descript = 'The folder where images and media files of the main site are stored by default. All files will be updated to the staging site by default. You can exclude folders you do not want to update.';
                            $contents_descript = '<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to update to the staging site, except for the wp-content/uploads folder.';
                            $additional_file_descript = '<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to updated to the staging site.';
                            $select_subsite = 'Select the subsites you wish to update to the staging site';
                        }

                        if($staging_site_data['mu_single'])
                        {

                        }
                        else
                        {
                            $html.= ' <label class="wpvivid-element-space-bottom" style="width:100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap; padding-top: 3px;">
                        <input id="wpvivid_mu_main_site_check" type="checkbox" option="wpvividstg_mu_sites" name="copy_mu_site_main" value="'.$main_site_id.'" checked/>
                        MU Files and Database
                        </label>
                        <p></p>';
                        }

                        $html .= '<div id="wpvivid_custom_mu_staging_site" class="wpvivid-element-space-bottom">
                                  <div id="wpvivid_mu_main_site_check_table">
                                      <div>
                                          <span><input type="checkbox" class="wpvivid-custom-database-part" checked></span>
                                          <span class="dashicons dashicons-admin-site-alt3 wpvivid-dashicons-blue"></span>
                                          <span class="wpvivid-handle-database-detail" style="cursor:pointer;"><strong>Database Will Be Copied</strong></span>
                                          <span class="wpvivid-handle-database-detail" style="cursor:pointer;"></span>
                                          <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                              <div class="wpvivid-bottom">
                                                  <!-- The content you need -->
                                                  <p>Won\'t back up any tables or additional databases if uncheck this.</p>
                                                  <i></i> <!-- do not delete this line -->
                                              </div>
                                          </span>
                                          <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-database-detail" style="cursor:pointer;"></span>
                                      </div>
                                      <div class="wpvivid-database-detail" style="display: none;">
                                          <!--  database begin  -->
                                          <div style="padding-left:2em;">
                                              <p><span><input type="checkbox" class="wpvivid-custom-database-check" name="copy_mu_site_main_tables" checked><span class="wpvivid-handle-base-database-detail" style="cursor:pointer;"><strong>'.$db_descript.'</strong></span></span></p>
                                          </div> 
                                          <div style="clear:both;"></div>
                                          <!--  database end  -->
                                      </div>
                                      
                                      <!--  files begin  -->
                                      <div style="margin-top:1em;">
                                          <span><input type="checkbox" class="wpvivid-custom-file-part" checked></span>
                                          <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                                          <span class="wpvivid-handle-file-detail" style="cursor:pointer;"><strong>Files & Folders Will Be Copied</strong></span>
                                          <span class="wpvivid-handle-file-detail" style="cursor:pointer;"></span>
                                          <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                              <div class="wpvivid-bottom">
                                                  <!-- The content you need -->
                                                  <p>Won\'t back up any files or folders if uncheck this.</p>
                                                  <i></i> <!-- do not delete this line -->
                                              </div>
                                          </span>
                                          <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-file-detail" style="cursor:pointer;"></span>
                                      </div>
                                      <div class="wpvivid-file-detail" style="padding-left:2em; display: none;">
                                          <p><span><input class="wpvivid-custom-core-check" name="copy_mu_site_main_core" type="checkbox" checked><span><strong>WordPress MU Core<span></span>: </strong>'.$core_descript.'</span></span></p>
                                          <p><span><input class="wpvivid-custom-themes-check" name="copy_mu_site_main_themes" type="checkbox" checked><span><strong>Themes<span></span>: </strong>includes all folders of themes.</span></p>
                                          <p><span><input class="wpvivid-custom-plugins-check" name="copy_mu_site_main_plugins" type="checkbox" checked><span><strong>Plugins<span></span>: </strong>includes all folders of plugins.</span></p>
                                          <p><span><input class="wpvivid-custom-content-check" name="copy_mu_site_main_content" type="checkbox" checked><span><strong>Wp-content<span></span>: </strong>'.$contents_descript.'</span></span></p>
                                          <p><span><input class="wpvivid-custom-uploads-check" name="copy_mu_site_main_folders" type="checkbox" checked><span><strong>Uploads<span></span>: </strong>'.$uploads_descript.'</span></span></p>
                                          <p>
                                              <span><input class="wpvivid-custom-additional-folder-check" name="copy_mu_site_main_additional_file" type="checkbox" ><span><strong>Additional Files/Folders<span></span>: </strong>'.$additional_file_descript.'</span></span>
                                              <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-additional-folder-detail" style="cursor:pointer;"></span>
                                          </p>
                            
                                          <p></p>
                            
                                          <div class="wpvivid-additional-folder-detail" style="display: none;">
                                              <div style="padding-left:2em;margin-top:1em;">
                                                  <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                                                      <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                                                  </div>
                                              </div>
                                              <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                                                          <span><strong>Tree View</strong></span>
                                                          <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-include-tree">Refresh<span>
                                                      </p>
                                                  </div>
                                                  <div class="wpvivid-custom-additional-folder-tree-info" style="margin-top:10px;height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer</div>
                                                  <div style="clear:both;"></div>
                                                  <div style="padding:1em 0 0 0;"><input class="button-primary wpvivid-include-additional-folder-btn" type="submit" value="Include Files/Folders"></div>
                                              </div>
                                              <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                                                          <span><strong>Additional Files/Folders Will Be Backed Up</strong></span>
                                                      </p>
                                                  </div>
                                                  <div class="wpvivid-custom-include-additional-folder-list" style="height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;"></div>
                                                  <div style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-include-list" style="float:right;">Empty Included Files/Folders</span></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div style="clear:both;"></div>
                                      <!--  files end  -->
                                      
                                      <div style="box-sizing:border-box; margin-top:1em;">
                                          <!--  exclude tree begin  -->
                                          <div style="margin-top:1em;">
                                              <span><input type="checkbox" class="wpvivid-custom-exclude-part" checked></span>
                                              <span class="dashicons dashicons-portfolio wpvivid-dashicons-grey"></span>
                                              <span class="wpvivid-handle-tree-detail" style="cursor:pointer;"><strong>Exclude Additional Files/Folders </strong></span>
                                              <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-tree-detail" style="cursor:pointer;"></span>
                                          </div>
                                          <div class="wpvivid-tree-detail" style="display: none;">
                                              <div style="padding-left:2em;margin-top:1em;">
                                                  <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                                                      <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                                                  </div>
                                              </div>
                            
                                              <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                                                          <span><strong>Folder Tree View</strong></span>
                                                          <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-exclude-tree">Refresh<span>
                                                      </p>
                                                  </div>
                                                  <div style="height:250px;">
                                                      <div>
                                                          <select name="action" class="wpvivid-custom-tree-selector" style="width:100%;border:1px solid #aaa;">
                                                              <option value="themes" selected>themes</option>
                                                              <option value="plugins">plugins</option>
                                                              <option value="content">wp-content</option>
                                                              <option value="uploads">uploads</option>
                                                          </select>
                                                      </div>
                                                      <div class="wpvivid-custom-exclude-tree-info" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer
                                                      </div>
                                                  </div>
                                                  <div style="clear:both;"></div>
                                                  <div style="padding:1.5em 0 0 0;"><input class="button-primary wpvivid-custom-tree-exclude-btn" type="submit" value="Exclude Files/Folders"></div>
                                              </div>
                                              <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                                                          <span><strong>Excluded Files/Folders/File Types</strong></span>
                                                      </p>
                                                  </div>
                            
                                                  <!-- themes -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module">
                                                      <input type="text" class="wpvivid-themes-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module wpvivid-custom-exclude-themes-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module" style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                            
                                                  <!-- plugins -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="display: none;">
                                                      <input type="text" class="wpvivid-plugins-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module wpvivid-custom-exclude-plugins-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                            
                                                  <!-- content -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="display: none;">
                                                      <input type="text" class="wpvivid-content-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module wpvivid-custom-exclude-content-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                            
                                                  <!-- uploads -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="display: none;">
                                                      <input type="text" class="wpvivid-uploads-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module wpvivid-custom-exclude-uploads-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                                              </div>
                             
                                          </div>
                                          <div style="clear:both;"></div>
                                          <!--  exculde tree end  -->
                                      </div>
                                  </div>
                              </div>';

                        if($staging_site_data['mu_single'])
                        {

                        }
                        else
                        {
                            $html.= ' <p>'.$select_subsite.'</p>';
                            $html.='<p>
                                            <label>
                                                <input type="checkbox" option="wpvividstg_copy_mu_sites" name="mu_all_site" checked />
                                                Select all subsites with their database tables and folders
                                            </label>
                                            <span style="float: right;margin-bottom: 6px">
                                                <label class="screen-reader-text" for="site-search-input">Search A Subsite:</label>
                                                <input type="search" id="wpvivid-mu-site-copy-search-input" name="s" value="">
                                                <input type="submit" id="wpvivid-mu-copy-search-submit" class="button" value="Search A Subsite">
                                            </span>
                                        </p>';
                            $html.='<div id="wpvivid_mu_copy_staging_site_list" style="pointer-events: none; opacity: 0.4;">';
                            $mu_site_list=new WPvivid_Staging_MU_Site_List();
                            if(isset($_POST['page']))
                            {
                                $mu_site_list->set_list($list,'copy_mu_site',$_POST['page']);
                            }
                            else
                            {
                                $mu_site_list->set_list($list,'copy_mu_site');
                            }

                            $mu_site_list->prepare_items();
                            ob_start();
                            $mu_site_list->display();
                            $html .= ob_get_clean();
                        }

                        if($staging_site_data['mu_single'])
                        {

                        }
                        else
                        {
                            $html.='</div>';
                        }

                        $html .= '</div><div style="clear: both;">';
                        $html.='</div>';

                    }

                    $wpdb=$old_db;

                    $ret['result'] = 'success';
                    $ret['html']=$html;
                }
                else
                {
                    $ret['result'] = 'failed';
                    $ret['error'] = 'not found staging path';
                }

            } else {
                $ret['result'] = 'failed';
                $ret['error'] = 'not found site';
            }
            echo json_encode($ret);
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_mu_site_info_ex()
    {
        $this->ajax_check_security();
        try
        {
            $html='';

            if(is_multisite())
            {
                $subsites=get_sites();

                $upload_dir = wp_upload_dir();
                $upload_path = $upload_dir['basedir'];
                $upload_path = str_replace('\\','/',$upload_path);
                $upload_path = $upload_path.'/';
                $content_dir = WP_CONTENT_DIR;
                $content_path = str_replace('\\','/',$content_dir);
                $content_path = $content_path.'/';
                $home_path = str_replace('\\','/', get_home_path());
                $theme_path = str_replace('\\','/', get_theme_root());
                $theme_path = $theme_path.'/';
                $plugin_path = str_replace('\\','/', WP_PLUGIN_DIR);
                $plugin_path = $plugin_path.'/';
                $ret['home_path'] = $home_path;
                $ret['uploads_path'] = $upload_path ;
                $ret['content_path'] = $content_path;
                $ret['theme_path'] = $theme_path;
                $ret['plugin_path'] = $plugin_path;

                $list=array();
                $main_site_id='0';
                $main_site_name='';
                $main_site_title=get_option( 'blogname' );
                $main_site_description=get_option( 'blogdescription' );

                foreach ($subsites as $subsite)
                {
                    if(is_main_site(get_object_vars($subsite)["blog_id"]))
                    {
                        $main_site_id=get_object_vars($subsite)["blog_id"];
                        $main_site_name = get_object_vars($subsite)["domain"].get_object_vars($subsite)["path"];
                    }
                    else
                    {
                        $list[]=$subsite;
                    }
                }


                $html.='';
                $html.='<div style="padding:10px; background: #fff; border: 1px solid #ccd0d4; border-radius: 6px; margin-top: 10px;">';

                $core_descript = 'If the staging site and the live site have the same version of WordPress. Then it is not necessary to copy the WordPress MU core files to the live site';
                $db_descript = 'All the tables in the WordPress MU database except for subsites tables.';
                $themes_plugins_descript = 'All the plugins and themes files used by the MU network. The activated plugins and themes will be copied to the live site by default. A child theme must be copied if it exists.';
                $uploads_descript = 'The folder where images and media files of the main site are stored by default. All files will be copied to the live site by default. You can exclude folders you do not want to copy.';
                $contents_descript = '<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to copy to the live site, except for the wp-content/uploads folder.';
                $additional_file_descript = '<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to copy to the live site';
                $select_subsite = 'Select the subsites you wish to copy to the live site';

                $html.= ' <label class="wpvivid-element-space-bottom" style="width:100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap; padding-top: 3px;">
                        <input id="wpvivid_mu_main_site_check" type="checkbox" option="wpvividstg_mu_sites" name="copy_mu_site_main" value="'.$main_site_id.'" checked/>
                        MU Files and Database
                        </label>
                        <p></p>';

                $html .= '<div id="wpvivid_custom_mu_staging_site" class="wpvivid-element-space-bottom">
                                  <div id="wpvivid_mu_main_site_check_table">
                                      <div>
                                          <span><input type="checkbox" class="wpvivid-custom-database-part" checked></span>
                                          <span class="dashicons dashicons-admin-site-alt3 wpvivid-dashicons-blue"></span>
                                          <span class="wpvivid-handle-database-detail" style="cursor:pointer;"><strong>Database Will Be Copied</strong></span>
                                          <span class="wpvivid-handle-database-detail" style="cursor:pointer;"></span>
                                          <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                              <div class="wpvivid-bottom">
                                                  <!-- The content you need -->
                                                  <p>Won\'t back up any tables or additional databases if uncheck this.</p>
                                                  <i></i> <!-- do not delete this line -->
                                              </div>
                                          </span>
                                          <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-database-detail" style="cursor:pointer;"></span>
                                      </div>
                                      <div class="wpvivid-database-detail" style="display: none;">
                                          <!--  database begin  -->
                                          <div style="padding-left:2em;">
                                              <p><span><input type="checkbox" class="wpvivid-custom-database-check" name="copy_mu_site_main_tables" checked><span class="wpvivid-handle-base-database-detail" style="cursor:pointer;"><strong>'.$db_descript.'</strong></span></span></p>
                                          </div> 
                                          <div style="clear:both;"></div>
                                          <!--  database end  -->
                                      </div>
                                      
                                      <!--  files begin  -->
                                      <div style="margin-top:1em;">
                                          <span><input type="checkbox" class="wpvivid-custom-file-part" checked></span>
                                          <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                                          <span class="wpvivid-handle-file-detail" style="cursor:pointer;"><strong>Files & Folders Will Be Copied</strong></span>
                                          <span class="wpvivid-handle-file-detail" style="cursor:pointer;"></span>
                                          <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                              <div class="wpvivid-bottom">
                                                  <!-- The content you need -->
                                                  <p>Won\'t back up any files or folders if uncheck this.</p>
                                                  <i></i> <!-- do not delete this line -->
                                              </div>
                                          </span>
                                          <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-file-detail" style="cursor:pointer;"></span>
                                      </div>
                                      <div class="wpvivid-file-detail" style="padding-left:2em; display: none;">
                                          <p><span><input class="wpvivid-custom-core-check" name="copy_mu_site_main_core" type="checkbox" checked><span><strong>WordPress MU Core<span></span>: </strong>'.$core_descript.'</span></span></p>
                                          <p><span><input class="wpvivid-custom-themes-check" name="copy_mu_site_main_themes" type="checkbox" checked><span><strong>Themes<span></span>: </strong>includes all folders of themes.</span></p>
                                          <p><span><input class="wpvivid-custom-plugins-check" name="copy_mu_site_main_plugins" type="checkbox" checked><span><strong>Plugins<span></span>: </strong>includes all folders of plugins.</span></p>
                                          <p><span><input class="wpvivid-custom-content-check" name="copy_mu_site_main_content" type="checkbox" checked><span><strong>Wp-content<span></span>: </strong>'.$contents_descript.'</span></span></p>
                                          <p><span><input class="wpvivid-custom-uploads-check" name="copy_mu_site_main_folders" type="checkbox" checked><span><strong>Uploads<span></span>: </strong>'.$uploads_descript.'</span></span></p>
                                          <p>
                                              <span><input class="wpvivid-custom-additional-folder-check" name="copy_mu_site_main_additional_file" type="checkbox" ><span><strong>Additional Files/Folders<span></span>: </strong>'.$additional_file_descript.'</span></span>
                                              <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-additional-folder-detail" style="cursor:pointer;"></span>
                                          </p>
                            
                                          <p></p>
                            
                                          <div class="wpvivid-additional-folder-detail" style="display: none;">
                                              <div style="padding-left:2em;margin-top:1em;">
                                                  <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                                                      <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                                                  </div>
                                              </div>
                                              <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                                                          <span><strong>Tree View</strong></span>
                                                          <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-include-tree">Refresh<span>
                                                      </p>
                                                  </div>
                                                  <div class="wpvivid-custom-additional-folder-tree-info" style="margin-top:10px;height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer</div>
                                                  <div style="clear:both;"></div>
                                                  <div style="padding:1em 0 0 0;"><input class="button-primary wpvivid-include-additional-folder-btn" type="submit" value="Include Files/Folders"></div>
                                              </div>
                                              <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                                                          <span><strong>Additional Files/Folders Will Be Backed Up</strong></span>
                                                      </p>
                                                  </div>
                                                  <div class="wpvivid-custom-include-additional-folder-list" style="height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;"></div>
                                                  <div style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-include-list" style="float:right;">Empty Included Files/Folders</span></div>
                                              </div>
                                          </div>
                                      </div>
                                      <div style="clear:both;"></div>
                                      <!--  files end  -->
                                      
                                      <div style="box-sizing:border-box; margin-top:1em;">
                                          <!--  exclude tree begin  -->
                                          <div style="margin-top:1em;">
                                              <span><input type="checkbox" class="wpvivid-custom-exclude-part" checked></span>
                                              <span class="dashicons dashicons-portfolio wpvivid-dashicons-grey"></span>
                                              <span class="wpvivid-handle-tree-detail" style="cursor:pointer;"><strong>Exclude Additional Files/Folders </strong></span>
                                              <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-tree-detail" style="cursor:pointer;"></span>
                                          </div>
                                          <div class="wpvivid-tree-detail" style="display: none;">
                                              <div style="padding-left:2em;margin-top:1em;">
                                                  <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                                                      <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                                                  </div>
                                              </div>
                            
                                              <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                                                          <span><strong>Folder Tree View</strong></span>
                                                          <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-exclude-tree">Refresh<span>
                                                      </p>
                                                  </div>
                                                  <div style="height:250px;">
                                                      <div>
                                                          <select name="action" class="wpvivid-custom-tree-selector" style="width:100%;border:1px solid #aaa;">
                                                              <option value="themes" selected>themes</option>
                                                              <option value="plugins">plugins</option>
                                                              <option value="content">wp-content</option>
                                                              <option value="uploads">uploads</option>
                                                          </select>
                                                      </div>
                                                      <div class="wpvivid-custom-exclude-tree-info" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer
                                                      </div>
                                                  </div>
                                                  <div style="clear:both;"></div>
                                                  <div style="padding:1.5em 0 0 0;"><input class="button-primary wpvivid-custom-tree-exclude-btn" type="submit" value="Exclude Files/Folders"></div>
                                              </div>
                                              <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                                                  <div>
                                                      <p>
                                                          <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                                                          <span><strong>Excluded Files/Folders/File Types</strong></span>
                                                      </p>
                                                  </div>
                            
                                                  <!-- themes -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module">
                                                      <input type="text" class="wpvivid-themes-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module wpvivid-custom-exclude-themes-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module" style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                            
                                                  <!-- plugins -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="display: none;">
                                                      <input type="text" class="wpvivid-plugins-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module wpvivid-custom-exclude-plugins-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                            
                                                  <!-- content -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="display: none;">
                                                      <input type="text" class="wpvivid-content-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module wpvivid-custom-exclude-content-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                            
                                                  <!-- uploads -->
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="display: none;">
                                                      <input type="text" class="wpvivid-uploads-extension" style="width:100%; border:1px solid #aaa;" value="" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module wpvivid-custom-exclude-uploads-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                                                      
                                                  </div>
                                                  <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                                              </div>
                             
                                          </div>
                                          <div style="clear:both;"></div>
                                          <!--  exculde tree end  -->
                                      </div>
                                  </div>
                              </div>';

                $html.= ' <p>'.$select_subsite.'</p>';
                $html.='<p>
                                            <label>
                                                <input type="checkbox" option="wpvividstg_copy_mu_sites" name="mu_all_site" checked />
                                                Select all subsites with their database tables and folders
                                            </label>
                                            <span style="float: right;margin-bottom: 6px">
                                                <label class="screen-reader-text" for="site-search-input">Search A Subsite:</label>
                                                <input type="search" id="wpvivid-mu-site-copy-search-input" name="s" value="">
                                                <input type="submit" id="wpvivid-mu-copy-search-submit" class="button" value="Search A Subsite">
                                            </span>
                                        </p>';
                $html.='<div id="wpvivid_mu_copy_staging_site_list" style="pointer-events: none; opacity: 0.4;">';
                $mu_site_list=new WPvivid_Staging_MU_Site_List();
                if(isset($_POST['page']))
                {
                    $mu_site_list->set_list($list,'copy_mu_site',$_POST['page']);
                }
                else
                {
                    $mu_site_list->set_list($list,'copy_mu_site');
                }

                $mu_site_list->prepare_items();
                ob_start();
                $mu_site_list->display();
                $html .= ob_get_clean();

                $html.='</div>';
                $html .= '</div><div style="clear: both;">';
                $html.='</div>';
            }

            $ret['result'] = 'success';
            $ret['html']=$html;

            echo json_encode($ret);
        }
        catch (Exception $error) {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function finish_push_staging()
    {
        $this->ajax_check_security();

        $this->wpvivid_check_clear_litespeed_rule();

        $task_option=$this->option->get_option('wpvivid_staging_push_task_ex');
        $this->task=new WPvivid_New_Staging_Push_Task($task_option);
        $this->task->finish_push_staging();
        $ret['result']='success';
        echo json_encode($ret);
        die();
    }

    public function scan_exist_staging()
    {
        $this->ajax_check_security();

        if(isset($_POST['path'])&&!empty($_POST['path']))
        {
           $path=sanitize_text_field($_POST['path']);
           $path=realpath($path);
           if(!file_exists($path))
           {
               $ret['result']='failed';
               $ret['error']='path not found';
               echo json_encode($ret);
               die();
           }
           else
           {
               $option=new WPvivid_Staging_Option();
               $staging_sites=$option->get_option('staging_site_data');

               $task_id=uniqid('wpvivid-');
               $new_site['id']=$task_id;
               $new_site['create_time']=time();
               $new_site['comment']='';

               $data=$this->get_site_data($path);

               if(empty($data))
               {
                   $ret['result']='failed';
                   $ret['error']='wp-config not found';
                   echo json_encode($ret);
                   die();
               }

               global $wpdb;
               $new_site['path']=untrailingslashit($path);;
               $new_site['site_url']=$data['new_site_url'];
               $new_site['home_url']=$data['new_home_url'];
               $new_site['prefix']=$data['new_prefix'];
               $new_site['old_prefix']=$wpdb->base_prefix;
               $new_site['db_connect']['use_additional_db']=$data['db_connect']['use_additional_db'];

               if($new_site['db_connect']['use_additional_db'])
               {
                   $new_site['db_connect']['dbuser']=$data['db_connect']['dbuser'];
                   $new_site['db_connect']['dbpassword']=$data['db_connect']['dbpassword'];
                   $new_site['db_connect']['dbname']=$data['db_connect']['dbname'];
                   $new_site['db_connect']['dbhost']=$data['db_connect']['dbhost'];
               }

               $new_site['fresh_install']=false;
               $new_site['log_file_name']='';

               $new_site['permalink_structure']='';
               $new_site['login_url']=$this->get_login_url();
               $new_site['is_create_subdomain']=false;
               $new_site['mu_single']=false;

               foreach ($staging_sites as $site)
               {
                   if($site['site_url']==$new_site['site_url'])
                   {
                       $ret['result']='failed';
                       $ret['error']='site already added';
                       echo json_encode($ret);
                       die();
                   }
               }

               $staging_sites[$new_site['id']]=$new_site;
               $option->update_option('staging_site_data', $staging_sites);

               $ret['result']='success';
               echo json_encode($ret);
               die();

           }
        }


        die();
    }

    public function get_site_data($path)
    {
        $path=untrailingslashit($path);
        if(file_exists($path.'/wp-config.php'))
        {
            $data=array();
            $config_data=file_get_contents($path.'/wp-config.php');
            $pattern     = '/\$table_prefix\s*=\s*(.*)/';
            preg_match( $pattern, $config_data, $matches );
            if( !empty( $matches[1] ) )
            {
                $data['new_prefix']=$matches[1];
                $data['new_prefix']=str_replace('"', "", $data['new_prefix']);
                $data['new_prefix']=str_replace("'", "", $data['new_prefix']);
                $data['new_prefix']=str_replace(";", "", $data['new_prefix']);
            }

            $pattern     = "/define\s*\(\s*'DB_NAME'\s*,\s*(.*)\s*\);.*/";
            preg_match( $pattern, $config_data, $matches );
            if( !empty( $matches[1] ) )
            {
                $dbname=ltrim(rtrim($matches[1],"'"));

                $dbname=str_replace('"', "", $dbname);
                $dbname=str_replace("'", "", $dbname);

                $data['db_connect']['dbname']=$dbname;
            }

            if($data['db_connect']['dbname']==DB_NAME)
            {
                $data['db_connect']['use_additional_db']=false;
            }
            else
            {
                $data['db_connect']['use_additional_db']=true;
            }

            $pattern     = "/define\s*\(\s*'DB_USER'\s*,\s*(.*)\s*\);.*/";
            preg_match( $pattern, $config_data, $matches );
            if( !empty( $matches[1] ) )
            {
                $data['db_connect']['dbuser']=$matches[1];

                $data['db_connect']['dbuser']=str_replace('"', "", $data['db_connect']['dbuser']);
                $data['db_connect']['dbuser']=str_replace("'", "", $data['db_connect']['dbuser']);

            }

            $pattern     = "/define\s*\(\s*'DB_PASSWORD'\s*,\s*(.*)\s*\);.*/";
            preg_match( $pattern, $config_data, $matches );
            if( !empty( $matches[1] ) )
            {
                $data['db_connect']['dbpassword']=$matches[1];

                $data['db_connect']['dbpassword']=str_replace('"', "", $data['db_connect']['dbpassword']);
                $data['db_connect']['dbpassword']=str_replace("'", "", $data['db_connect']['dbpassword']);
            }

            $pattern     = "/define\s*\(\s*'DB_HOST'\s*,\s*(.*)\s*\);.*/";
            preg_match( $pattern, $config_data, $matches );
            if( !empty( $matches[1] ) )
            {
                $data['db_connect']['dbhost']=$matches[1];

                $data['db_connect']['dbhost']=str_replace('"', "", $data['db_connect']['dbhost']);
                $data['db_connect']['dbhost']=str_replace("'", "", $data['db_connect']['dbhost']);
            }

            if($data['db_connect']['use_additional_db'])
            {
                $db_instance=new wpdb($data['db_connect']['dbuser'],
                    $data['db_connect']['dbpassword'],
                    $data['db_connect']['dbname'],
                    $data['db_connect']['dbhost']);
            }
            else
            {
                global $wpdb;
                $db_instance=$wpdb;
            }

            $options_name=$data['new_prefix'].'options';
            $site_url_sql = $db_instance->get_results( $db_instance->prepare( "SELECT * FROM $options_name WHERE option_name = %s", 'siteurl' ) );
            foreach ( $site_url_sql as $site )
            {
                $data['new_site_url'] = $site->option_value;
            }

            $site_url_sql = $db_instance->get_results( $db_instance->prepare( "SELECT * FROM $options_name WHERE option_name = %s", 'home' ) );
            foreach ( $site_url_sql as $site )
            {
                $data['new_home_url'] = $site->option_value;
            }

            preg_match( "/define\s*\(\s*['\"]WP_HOME['\"]\s*,\s*(.*)\s*\);/", $config_data, $matches );
            if( !empty( $matches[1] ) )
            {
                $data['new_home_url']=$matches[1];
                $data['new_home_url']=str_replace('"', "", $data['new_home_url']);
                $data['new_home_url']=str_replace("'", "", $data['new_home_url']);
            }

            preg_match( "/define\s*\(\s*['\"]WP_SITEURL['\"]\s*,\s*(.*)\s*\);/", $config_data, $matches );
            if( !empty( $matches[1] ) )
            {
                $data['new_site_url']=$matches[1];
                $data['new_site_url']=str_replace('"', "", $data['new_site_url']);
                $data['new_site_url']=str_replace("'", "", $data['new_site_url']);
            }
            return $data;
        }
        else
        {
            return false;
        }
    }

    public function push_staging_failed()
    {
        $this->ajax_check_security();
        $this->wpvivid_check_clear_litespeed_rule();
        $options=new WPvivid_Staging_Option();
        $task_option=$options->get_option('wpvivid_staging_push_task_ex');
        $this->task=new WPvivid_New_Staging_Push_Task($task_option);
        $this->flush();
        $this->task->clean_tmp();

        $ret['result']='success';
        echo json_encode($ret);
        die();
    }

    public function finish_copy_staging()
    {
        $this->ajax_check_security();
        $this->wpvivid_check_clear_litespeed_rule();
        $ret['result']='success';
        echo json_encode($ret);
        die();
    }

    public function copy_staging_failed()
    {
        $this->ajax_check_security();

        $options=new WPvivid_Staging_Option();
        $task_option=$options->get_option('wpvivid_staging_copy_task_ex');
        $this->task=new WPvivid_New_Staging_Copy_Task($task_option);
        $this->flush();
        $this->task->clean_tmp();
        $this->wpvivid_check_clear_litespeed_rule();
        $ret['result']='success';
        echo json_encode($ret);
        die();
    }

    public function staging_start_push()
    {
        $this->ajax_check_security();

        $site_data=$this->get_live_site_data();

        if(empty($site_data))
        {
            $ret['result']='failed';
            $ret['error']='Site data not found.';
            echo json_encode($ret);
            die();
        }

        if(isset( $_POST['custom_dir']))
        {
            $json = $_POST['custom_dir'];
            $json = stripslashes($json);
            $staging_options = json_decode($json, true);
        }
        else
        {
            $staging_options=array();
        }

        global $wpdb;
        if($site_data['db_connect']['use_additional_db']===false)
        {
            global $wpdb;
            $db_des_instance=$wpdb;
        }
        else
        {
            $additional_db['des_dbuser'] =$site_data['db_connect']['dbuser'];
            $additional_db['des_dbpassword'] = $site_data['db_connect']['dbpassword'];
            $additional_db['des_dbname'] = $site_data['db_connect']['dbname'];
            $additional_db['des_dbhost'] = $site_data['db_connect']['dbhost'];
            $db_des_instance=new wpdb($additional_db['des_dbuser'],$additional_db['des_dbpassword'],$additional_db['des_dbname'],$additional_db['des_dbhost']);
        }

        $need_retain_data = array();
        $need_retain_option_array = array('elementor_pro_license_key', '_elementor_pro_license_data', '_elementor_pro_license_data_fallback', 'icl_sitepress_settings', 'WPML(setup)', 'wpvivid_compress_setting', 'wpvivid_local_setting',
            'wpvivid_upload_setting', 'wpvivid_common_setting', 'wpvivid_email_setting', 'wpvivid_saved_api_token', 'wpvivid_auto_backup_before_update',
            'wpvivid_email_setting_addon', 'wpvivid_schedule_addon_setting', 'white_label_setting', 'wpvivid_enable_incremental_schedules', 'wpvivid_incremental_schedules',
            'wpvivid_user_history', 'wpvivid_backup_list', 'wpvivid_new_remote_list');

        $options_table_name=$site_data['prefix'].'options';
        foreach ($need_retain_option_array as $need_retain_option)
        {
            $sql_res = $db_des_instance->get_results( $wpdb->prepare( "SELECT option_value FROM $options_table_name WHERE option_name = %s", $need_retain_option ) );
            if(!empty($sql_res))
            {
                foreach ( $sql_res as $value )
                {
                    $need_retain_data[$need_retain_option] = $value->option_value;
                }
            }
        }

        $options['need_retain_data'] = $need_retain_data;

        $is_mu=isset($_POST['push_mu_site'])?$_POST['push_mu_site']:false;
        if(is_multisite())
        {
            $options['path_current_site']=$site_data['path_current_site'];
            $options['main_site_id']=$site_data['main_site_id'];

            $subsites = get_sites();
            foreach ($subsites as $subsite)
            {
                $subsite_id = get_object_vars($subsite)["blog_id"];

                $str=get_object_vars($subsite)["path"];
                $options['mu_site'][$subsite_id]['path_site']=$options['path_current_site'].substr($str, strlen(PATH_CURRENT_SITE));
            }
        }

        if($is_mu=='true')
        {
            $options['is_mu']=true;
            $options['is_mu_single']=false;

            $mu_site_list_json=$staging_options['mu_site_list'];
            $options['mu_main_site']=$staging_options['mu_main_site'];
            $options['all_site']=$staging_options['all_site'];
            $options['mu_site_list']=array();
            if(!empty($mu_site_list_json))
            {
                foreach ($mu_site_list_json as $site)
                {
                    $options['mu_site_list'][$site['id']]['tables']=$site['tables'];
                    $options['mu_site_list'][$site['id']]['folders']=$site['folders'];
                }
            }
        }
        else if($site_data['mu_single'])
        {
            $options['is_mu']=false;
            $options['is_mu_single']=true;
            $options['mu_single_site_id']=$site_data['mu_single_site_id'];

            $options['path_current_site']=$site_data['path_current_site'];
            $options['main_site_id']=$site_data['main_site_id'];
        }
        else
        {
            $options['is_mu_single']=false;
            $options['is_mu']=false;
        }


        $options['exclude_regex'] = array();
        $options['exclude_files'] = array();

        $themes_path  = get_theme_root();
        $plugins_path = WP_PLUGIN_DIR;
        $upload_dir   = wp_upload_dir();
        $uploads_path = $upload_dir['basedir'];
        $content_path = WP_CONTENT_DIR;

        if($staging_options['themes_check'] == '1')
        {
            foreach ($staging_options['themes_list'] as $name=>$theme)
            {
                $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($themes_path.DIRECTORY_SEPARATOR.$name), '/').'#';
            }
        }

        if($staging_options['plugins_check'] == '1')
        {
            foreach ($staging_options['plugins_list'] as $name=>$plugin)
            {
                $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($plugins_path.DIRECTORY_SEPARATOR.$name), '/').'#';
            }
        }

        if($staging_options['uploads_check'] == '1')
        {
            $upload_dir = wp_upload_dir();
            foreach ($staging_options['uploads_list'] as $key => $value)
            {
                $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($uploads_path.DIRECTORY_SEPARATOR.$key), '/').'#';
            }

            if(isset($staging_options['upload_extension']) && !empty($staging_options['upload_extension']))
            {
                $str_tmp = explode(',', $staging_options['upload_extension']);
                for($index=0; $index<count($str_tmp); $index++)
                {
                    if(!empty($str_tmp[$index]))
                    {
                        $options['exclude_files'][] = '#' . '.*\.' . $str_tmp[$index] . '$' . '#';
                    }
                }
            }
        }

        if($staging_options['content_check'] == '1')
        {
            foreach ($staging_options['content_list'] as $key => $value)
            {
                $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($content_path.DIRECTORY_SEPARATOR.$key), '/').'#';
            }

            if(isset($staging_options['content_extension']) && !empty($staging_options['content_extension']))
            {
                $str_tmp = explode(',', $staging_options['content_extension']);
                for($index=0; $index<count($str_tmp); $index++)
                {
                    if(!empty($str_tmp[$index]))
                    {
                        $options['exclude_files'][] = '#' . '.*\.' . $str_tmp[$index] . '$' . '#';
                    }
                }
            }
        }

        $options['is_create_subdomain'] = false;

        $options['old_site_url']=untrailingslashit($this->get_database_site_url());
        $options['old_home_url']=untrailingslashit($this->get_database_home_url());

        $options['setting'] = $this->set_staging_option();
        $options['staging_options']=$staging_options;
        $options['site_data']=$site_data;

        $options['exclude_tables'] = array();
        $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'hw_blocks';
        $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_options';

        $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_increment_big_ids';
        $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_log';
        $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_record_task';

        if($staging_options['database_check'] == '1')
        {
            foreach ($staging_options['database_list'] as $table)
            {
                $options['exclude_tables'][] = $table;
            }
        }

        $task = new WPvivid_New_Staging_Push_Task_Ex();
        $ret=$task->create_new_task($options);
        $this->wpvivid_check_add_litespeed_server();
        echo json_encode($ret);
        die();
    }

    public function get_live_site_data()
    {
        $staging_site_data=$this->get_staging_site_data();
        if($staging_site_data===false)
        {
            return false;
        }

        if(!isset($staging_site_data['live_site_data']))
        {
            return false;
        }
        else
        {
            return $staging_site_data['live_site_data'];
        }
    }

    public function get_staging_push_progress()
    {
        $this->ajax_check_security();

        $task_option=$this->option->get_option('wpvivid_staging_push_task_ex');
        $this->task=new WPvivid_New_Staging_Push_Task_Ex($task_option);

        $ret['test']= $this->task->task;

        $status=$this->task->get_status();
        if($status==false)
        {
            $ret['result']='failed';
            $ret['error']='Failed to get status of the task.';
            echo json_encode($ret);
            die();
        }
        $ret['test_time']=time()-$status['run_time'];
        if($status['str']=='error')
        {
            $ret['result']='failed';
            $ret['error']=$status['error'];
        }
        else if($status['str']=='ready')
        {
            $ret['result']='success';
            $ret['status']='ready';
        }
        else if($status['str']=='running')
        {
            $limit=$this->task->get_time_limit();

            $time_spend=time()-$status['timeout'];
            $last_active_time=time()-$status['run_time'];
            if($time_spend>$limit&&$last_active_time>180)
            {
                //time out
                $max_resume_count=$this->task->get_max_resume_count();
                $status['resume_count']++;
                if($status['resume_count']>$max_resume_count)
                {
                    $message=__('Too many resumption attempts.', 'wpvivid-backuprestore');
                    $log=new WPvivid_Staging_Log();
                    $log->OpenLogFile($this->task->task['log_file_name']);
                    $log->WriteLog($message,'error');
                    $this->task->update_task_status(false,'error',false,$status['resume_count'],$message);
                    $ret['result']='failed';
                    $ret['error']=$message;
                }
                else
                {
                    $ret['result']='success';
                    $ret['status']='ready';

                    $log=new WPvivid_Staging_Log();
                    $log->OpenLogFile($this->task->task['log_file_name']);
                    $log->WriteLog('Task timed out.','error');
                    $this->task->update_task_status(false,'ready',false,$status['resume_count']);
                }
            }
            else
            {
                $time_spend=time()-$status['run_time'];
                if($time_spend>180)
                {
                    $ret['result']='success';
                    $ret['status']='no_responds';
                }
                else
                {
                    $ret['result']='success';
                    $ret['status']='running';
                }
            }
        }
        else if($status['str']=='completed')
        {
            $ret['result']='success';
            $ret['status']='completed';
        }
        else
        {
            $ret['result']='failed';
            $ret['error']='Failed to get status of the task.';
        }

        if($ret['result']=='success')
        {
            $progress=$this->task->get_progress();

            $log=new WPvivid_Staging_Log();
            $log->OpenLogFile($this->task->task['log_file_name']);
            $file_name=$log->GetSaveLogFolder(). $this->task->task['log_file_name'].'_log.txt';
            $file =fopen($file_name,'r');
            $buffer='';
            if(!$file)
            {
                $buffer='open log file failed';
            }
            else
            {
                if(filesize($file_name)<=5*1024)
                {
                    while(!feof($file))
                    {
                        $buffer .= fread($file,1024);
                    }
                }
                else
                {
                    $pos=-2;
                    $eof='';
                    $n=50;
                    $buffer_array = array();
                    while($n>0)
                    {
                        while($eof!=="\n")
                        {
                            if(!fseek($file, $pos, SEEK_END))
                            {
                                $eof=fgetc($file);
                                $pos--;
                            }
                            else
                            {
                                break;
                            }
                        }
                        $buffer_array[].=fgets($file);
                        $eof='';
                        $n--;
                    }

                    if(!empty($buffer_array))
                    {
                        $buffer_array = array_reverse($buffer_array);
                        foreach($buffer_array as $value)
                        {
                            $buffer.=$value;
                        }
                    }
                }

                fclose($file);
            }

            $ret['log']=$buffer;
            $ret['percent']=$progress['main_percent'];
        }

        echo json_encode($ret);
        //echo WPvivid_Merge_Common_Function::prepare_response($ret);
        die();
    }

    public function staging_restart_push()
    {
        $this->ajax_check_security();

        $this->end_shutdown_function=false;

        $task_option=$this->option->get_option('wpvivid_staging_push_task_ex');
        $this->task=new WPvivid_New_Staging_Push_Task_Ex($task_option);

        $this->task->update_task_status(true,'running');
        if($this->task->is_task_finished())
        {
            $ret['result']='success';
            $this->task->update_task_status(false,'completed');
            echo json_encode($ret);
        }
        else
        {
            $this->flush();
            $job= $this->task->get_next_job();

            if($job===false)
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'completed');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }

            $this->task->set_time_limit();
            $ret= $this->task->do_staging_job($job);
            if($ret['result']!='success')
            {
                $this->task->update_task_status(false,'error',false,false,$ret['error']);
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }

            if( $this->task->is_task_finished())
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'completed');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }
            else
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'ready');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }
        }

        $this->end_shutdown_function=true;

        die();
    }

    public function staging_push_finish()
    {
        $this->ajax_check_security();
        $this->wpvivid_check_clear_litespeed_rule();
        $ret['result']='success';
        echo json_encode($ret);
        die();
    }

    public function staging_push_failed()
    {
        $this->ajax_check_security();

        $options=new WPvivid_Staging_Option();
        $task_option=$options->get_option('wpvivid_staging_push_task_ex');
        $this->task=new WPvivid_New_Staging_Push_Task_Ex($task_option);
        $this->flush();
        $this->task->clean_tmp();
        $this->wpvivid_check_clear_litespeed_rule();
        $ret['result']='success';
        echo json_encode($ret);
        die();
    }

    public function push_start_staging_ex()
    {
        $this->ajax_check_security();

        if(isset($_POST['id']) && isset($_POST['custom_dir']))
        {
            global $wpdb;
            $site_id = sanitize_text_field($_POST['id']);
            $list = $this->option->get_option('staging_site_data');
            if(!array_key_exists($site_id,$list))
            {
                $ret['result']='failed';
                $ret['error']='Site data not found.';
                echo json_encode($ret);
                die();
            }

            $site_data=$list[$site_id];
            $json = $_POST['custom_dir'];
            $json = stripslashes($json);
            $staging_options = json_decode($json, true);


            global $wpdb;
            $need_retain_data = array();
            $need_retain_option_array = array('elementor_pro_license_key', '_elementor_pro_license_data', '_elementor_pro_license_data_fallback', 'icl_sitepress_settings', 'WPML(setup)', 'wpvivid_compress_setting', 'wpvivid_local_setting',
                'wpvivid_upload_setting', 'wpvivid_common_setting', 'wpvivid_email_setting', 'wpvivid_saved_api_token', 'wpvivid_auto_backup_before_update',
                'wpvivid_email_setting_addon', 'wpvivid_schedule_addon_setting', 'white_label_setting', 'wpvivid_enable_incremental_schedules', 'wpvivid_incremental_schedules',
                'wpvivid_user_history', 'wpvivid_backup_list', 'wpvivid_new_remote_list');

            foreach ($need_retain_option_array as $need_retain_option)
            {
                $sql_res = $wpdb->get_results( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s", $need_retain_option ) );
                if(!empty($sql_res))
                {
                    foreach ( $sql_res as $value )
                    {
                        $need_retain_data[$need_retain_option] = $value->option_value;
                    }
                }
            }

            $options['need_retain_data'] = $need_retain_data;

            $options['exclude_regex'] = array();
            $options['exclude_files'] = array();

            $is_mu=$_POST['push_mu_site'];
            if(is_multisite())
            {
                $options['path_current_site']=$site_data['path_current_site'];
                $options['main_site_id']=$site_data['main_site_id'];
                $subsites = $this->get_mu_sites($site_data);
                foreach ($subsites as $subsite)
                {
                    $subsite_id = get_object_vars($subsite)["blog_id"];

                    $str=get_object_vars($subsite)["path"];
                    $options['mu_site'][$subsite_id]['path_site']=PATH_CURRENT_SITE.substr($str, strlen($options['path_current_site']));
                }
            }

            if($is_mu=='true')
            {
                $options['is_mu']=true;
                $options['is_mu_single']=false;

                $mu_site_list_json=$staging_options['mu_site_list'];
                $options['mu_main_site']=$staging_options['mu_main_site'];
                $options['all_site']=$staging_options['all_site'];
                $options['mu_site_list']=array();
                foreach ($mu_site_list_json as $site)
                {
                    $options['mu_site_list'][$site['id']]['tables']=$site['tables'];
                    $options['mu_site_list'][$site['id']]['folders']=$site['folders'];
                }
            }
            else if($site_data['mu_single'])
            {
                $options['is_mu']=false;
                $options['is_mu_single']=true;
                $options['mu_single_site_id']=$site_data['mu_single_site_id'];
            }
            else
            {
                $options['is_mu_single']=false;
                $options['is_mu']=false;
            }
            $site_path = $site_data['path'];
            $themes_path  = $site_path . '/wp-content/themes';
            $plugins_path = $site_path . '/wp-content/plugins';
            $uploads_path = $site_path . '/wp-content/uploads';
            $content_path = $site_path . '/wp-content';

            if($staging_options['themes_check'] == '1')
            {
                foreach ($staging_options['themes_list'] as $name=>$theme)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($themes_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
            }

            if($staging_options['plugins_check'] == '1')
            {
                foreach ($staging_options['plugins_list'] as $name=>$plugin)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($plugins_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
            }

            if($staging_options['uploads_check'] == '1')
            {
                $upload_dir = wp_upload_dir();
                foreach ($staging_options['uploads_list'] as $key => $value)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($uploads_path.DIRECTORY_SEPARATOR.$key), '/').'#';
                }

                if(isset($staging_options['upload_extension']) && !empty($staging_options['upload_extension']))
                {
                    $str_tmp = explode(',', $staging_options['upload_extension']);
                    for($index=0; $index<count($str_tmp); $index++)
                    {
                        if(!empty($str_tmp[$index]))
                        {
                            $options['exclude_files'][] = '#' . '.*\.' . $str_tmp[$index] . '$' . '#';
                        }
                    }
                }
            }

            if($staging_options['content_check'] == '1')
            {
                foreach ($staging_options['content_list'] as $key => $value)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($content_path.DIRECTORY_SEPARATOR.$key), '/').'#';
                }

                if(isset($staging_options['content_extension']) && !empty($staging_options['content_extension']))
                {
                    $str_tmp = explode(',', $staging_options['content_extension']);
                    for($index=0; $index<count($str_tmp); $index++)
                    {
                        if(!empty($str_tmp[$index]))
                        {
                            $options['exclude_files'][] = '#' . '.*\.' . $str_tmp[$index] . '$' . '#';
                        }
                    }
                }
            }

            $options['is_create_subdomain'] = false;

            $options['new_site_url']=untrailingslashit($this->get_database_site_url());
            $options['new_home_url']=untrailingslashit($this->get_database_home_url());

            $options['setting'] = $this->set_staging_option();
            $options['staging_options']=$staging_options;
            $options['site_data']=$site_data;

            $options['exclude_tables'] = array();
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $options['site_data']['prefix']).'hw_blocks';
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $options['site_data']['prefix']).'wpvivid_options';

            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $options['site_data']['prefix']).'wpvivid_increment_big_ids';
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $options['site_data']['prefix']).'wpvivid_log';
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $options['site_data']['prefix']).'wpvivid_record_task';

            if($staging_options['database_check'] == '1')
            {
                foreach ($staging_options['database_list'] as $table)
                {
                    $options['exclude_tables'][] = $table;
                }
            }
            $task = new WPvivid_New_Staging_Push_Task();
            $ret=$task->create_new_task($options);
            $this->wpvivid_check_add_litespeed_server();
            echo json_encode($ret);
            die();
        }
        else
        {
            die();
        }

    }

    public function get_mu_sites($site_data,$args=array())
    {
        global $wpdb;
        $db_connect=$site_data['db_connect'];
        $prefix=$site_data['prefix'];
        if($db_connect['use_additional_db']===false)
        {
            $old_prefix=$wpdb->base_prefix;
            $wpdb->set_prefix($prefix);
            $subsites=get_sites($args);
            $wpdb->set_prefix($old_prefix);

        }
        else
        {
            $old_wpdb=$wpdb;
            $wpdb=new wpdb($db_connect['dbuser'],$db_connect['dbpassword'],$db_connect['dbname'],$db_connect['dbhost']);
            $wpdb->set_prefix($prefix);
            $subsites=get_sites($args);
            $wpdb=$old_wpdb;
        }

        /*
        if($db['use_additional_db']===false)
        {
            $db_instance=$wpdb;
        }
        else
        {
            $db_instance=new wpdb($db['dbuser'],$db['dbpassword'],$db['dbname'],$db['dbhost']);
        }
        $sql='SELECT * FROM '.$this->get_site_prefix().'blogs';
        $subsites=$db_instance->get_results($sql,OBJECT_K);
        */

        return $subsites;
    }

    public function get_staging_progress_ex()
    {
        $this->ajax_check_security();

        $task_option=$this->option->get_option('wpvivid_staging_push_task_ex');
        $this->task=new WPvivid_New_Staging_Push_Task($task_option);

        $ret['test']= $this->task->task;

        $status=$this->task->get_status();
        if($status==false)
        {
            $ret['result']='failed';
            $ret['error']='Failed to get status of the task.';
            echo json_encode($ret);
            die();
        }
        $ret['test_time']=time()-$status['run_time'];
        if($status['str']=='error')
        {
            $ret['result']='failed';
            $ret['error']=$status['error'];
        }
        else if($status['str']=='ready')
        {
            $ret['result']='success';
            $ret['status']='ready';
        }
        else if($status['str']=='running')
        {
            $limit=$this->task->get_time_limit();

            $time_spend=time()-$status['timeout'];
            $last_active_time=time()-$status['run_time'];
            if($time_spend>$limit&&$last_active_time>180)
            {
                //time out
                $max_resume_count=$this->task->get_max_resume_count();
                $status['resume_count']++;
                if($status['resume_count']>$max_resume_count)
                {
                    $message=__('Too many resumption attempts.', 'wpvivid-backuprestore');
                    $log=new WPvivid_Staging_Log();
                    $log->OpenLogFile($this->task->task['log_file_name']);
                    $log->WriteLog($message,'error');
                    $this->task->update_task_status(false,'error',false,$status['resume_count'],$message);
                    $ret['result']='failed';
                    $ret['error']=$message;
                }
                else
                {
                    $ret['result']='success';
                    $ret['status']='ready';

                    $log=new WPvivid_Staging_Log();
                    $log->OpenLogFile($this->task->task['log_file_name']);
                    $log->WriteLog('Task timed out.','error');
                    $this->task->update_task_status(false,'ready',false,$status['resume_count']);
                }
            }
            else
            {
                $time_spend=time()-$status['run_time'];
                if($time_spend>180)
                {
                    $ret['result']='success';
                    $ret['status']='no_responds';
                }
                else
                {
                    $ret['result']='success';
                    $ret['status']='running';
                }
            }
        }
        else if($status['str']=='completed')
        {
            $ret['result']='success';
            $ret['status']='completed';
        }
        else
        {
            $ret['result']='failed';
            $ret['error']='Failed to get status of the task.';
        }

        if($ret['result']=='success')
        {
            $progress=$this->task->get_progress();

            $log=new WPvivid_Staging_Log();
            $log->OpenLogFile($this->task->task['log_file_name']);
            $file_name=$log->GetSaveLogFolder(). $this->task->task['log_file_name'].'_log.txt';
            $file =fopen($file_name,'r');
            $buffer='';
            if(!$file)
            {
                $buffer='open log file failed';
            }
            else
            {
                if(filesize($file_name)<=5*1024)
                {
                    while(!feof($file))
                    {
                        $buffer .= fread($file,1024);
                    }
                }
                else
                {
                    $pos=-2;
                    $eof='';
                    $n=50;
                    $buffer_array = array();
                    while($n>0)
                    {
                        while($eof!=="\n")
                        {
                            if(!fseek($file, $pos, SEEK_END))
                            {
                                $eof=fgetc($file);
                                $pos--;
                            }
                            else
                            {
                                break;
                            }
                        }
                        $buffer_array[].=fgets($file);
                        $eof='';
                        $n--;
                    }

                    if(!empty($buffer_array))
                    {
                        $buffer_array = array_reverse($buffer_array);
                        foreach($buffer_array as $value)
                        {
                            $buffer.=$value;
                        }
                    }
                }

                fclose($file);
            }

            $ret['log']=$buffer;
            $ret['percent']=$progress['main_percent'];
        }

        echo json_encode($ret);
        //echo WPvivid_Merge_Common_Function::prepare_response($ret);
        die();
    }

    public function push_restart_staging_ex()
    {
        $this->ajax_check_security();

        $this->end_shutdown_function=false;

        $task_option=$this->option->get_option('wpvivid_staging_push_task_ex');
        $this->task=new WPvivid_New_Staging_Push_Task($task_option);

        $this->task->update_task_status(true,'running');
        if($this->task->is_task_finished())
        {
            $ret['result']='success';
            $this->task->update_task_status(false,'completed');
            echo json_encode($ret);
        }
        else
        {
            $this->flush();
            $job= $this->task->get_next_job();

            if($job===false)
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'completed');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }

            $this->task->set_time_limit();
            $ret= $this->task->do_staging_job($job);
            if($ret['result']!='success')
            {
                $this->task->update_task_status(false,'error',false,false,$ret['error']);
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }

            if( $this->task->is_task_finished())
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'completed');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }
            else
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'ready');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }
        }

        $this->end_shutdown_function=true;

        die();
    }

    public function copy_start_staging_ex()
    {
        $this->ajax_check_security();

        if(isset($_POST['id']) && isset($_POST['custom_dir']))
        {
            global $wpdb;
            $site_id = sanitize_text_field($_POST['id']);
            $list = $this->option->get_option('staging_site_data');
            if(!array_key_exists($site_id,$list))
            {
                $ret['result']='failed';
                $ret['error']='Site data not found.';
                echo json_encode($ret);
                die();
            }

            $site_data=$list[$site_id];
            $json = $_POST['custom_dir'];
            $json = stripslashes($json);
            $staging_options = json_decode($json, true);

            global $wpdb;
            if($site_data['db_connect']['use_additional_db']===false)
            {
                global $wpdb;
                $db_des_instance=$wpdb;
            }
            else
            {
                $additional_db['des_dbuser'] =$site_data['db_connect']['dbuser'];
                $additional_db['des_dbpassword'] = $site_data['db_connect']['dbpassword'];
                $additional_db['des_dbname'] = $site_data['db_connect']['dbname'];
                $additional_db['des_dbhost'] = $site_data['db_connect']['dbhost'];
                $db_des_instance=new wpdb($additional_db['des_dbuser'],$additional_db['des_dbpassword'],$additional_db['des_dbname'],$additional_db['des_dbhost']);
            }

            $need_retain_data = array();
            $need_retain_option_array = array('elementor_pro_license_key', '_elementor_pro_license_data', '_elementor_pro_license_data_fallback', 'icl_sitepress_settings', 'WPML(setup)', 'wpvivid_compress_setting', 'wpvivid_local_setting',
                'wpvivid_upload_setting', 'wpvivid_common_setting', 'wpvivid_email_setting', 'wpvivid_saved_api_token', 'wpvivid_auto_backup_before_update',
                'wpvivid_email_setting_addon', 'wpvivid_schedule_addon_setting', 'white_label_setting', 'wpvivid_enable_incremental_schedules', 'wpvivid_incremental_schedules',
                'wpvivid_user_history', 'wpvivid_backup_list', 'wpvivid_new_remote_list');

            $options_table_name=$site_data['prefix'].'options';
            foreach ($need_retain_option_array as $need_retain_option)
            {
                $sql_res = $db_des_instance->get_results( $wpdb->prepare( "SELECT option_value FROM $options_table_name WHERE option_name = %s", $need_retain_option ) );
                if(!empty($sql_res))
                {
                    foreach ( $sql_res as $value )
                    {
                        $need_retain_data[$need_retain_option] = $value->option_value;
                    }
                }
            }

            $options['need_retain_data'] = $need_retain_data;

            $is_mu=$_POST['push_mu_site'];
            if(is_multisite())
            {
                $options['path_current_site']=$site_data['path_current_site'];
                $subsites = get_sites();
                foreach ($subsites as $subsite)
                {
                    $subsite_id = get_object_vars($subsite)["blog_id"];

                    $str=get_object_vars($subsite)["path"];
                    $options['mu_site'][$subsite_id]['path_site']=$options['path_current_site'].substr($str, strlen(PATH_CURRENT_SITE));

                    //$option['data']['mu']['site'][$subsite_id]['path_site'] = str_replace(PATH_CURRENT_SITE,$option['data']['mu']['path_current_site'],get_object_vars($subsite)["path"]);
                    if(is_main_site($subsite_id))
                    {
                        $options['main_site_id']=$subsite_id;
                    }
                }
            }

            if($is_mu=='true')
            {
                $options['is_mu']=true;
                $options['is_mu_single']=false;

                $mu_site_list_json=$staging_options['mu_site_list'];
                $options['mu_main_site']=$staging_options['mu_main_site'];
                $options['all_site']=$staging_options['all_site'];
                $options['mu_site_list']=array();
                if(!empty($mu_site_list_json))
                {
                    foreach ($mu_site_list_json as $site)
                    {
                        $options['mu_site_list'][$site['id']]['tables']=$site['tables'];
                        $options['mu_site_list'][$site['id']]['folders']=$site['folders'];
                    }
                }
            }
            else if($site_data['mu_single'])
            {
                $options['is_mu']=false;
                $options['is_mu_single']=true;
                $options['mu_single_site_id']=$site_data['mu_single_site_id'];
            }
            else
            {
                $options['is_mu_single']=false;
                $options['is_mu']=false;
            }

            $options['exclude_regex'] = array();
            $options['exclude_files'] = array();

            $themes_path  = get_theme_root();
            $plugins_path = WP_PLUGIN_DIR;
            $upload_dir   = wp_upload_dir();
            $uploads_path = $upload_dir['basedir'];
            $content_path = WP_CONTENT_DIR;

            if($staging_options['themes_check'] == '1')
            {
                foreach ($staging_options['themes_list'] as $name=>$theme)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($themes_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
            }

            if($staging_options['plugins_check'] == '1')
            {
                foreach ($staging_options['plugins_list'] as $name=>$plugin)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($plugins_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
            }

            if($staging_options['uploads_check'] == '1')
            {
                $upload_dir = wp_upload_dir();
                foreach ($staging_options['uploads_list'] as $key => $value)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($uploads_path.DIRECTORY_SEPARATOR.$key), '/').'#';
                }

                if(isset($staging_options['upload_extension']) && !empty($staging_options['upload_extension']))
                {
                    $str_tmp = explode(',', $staging_options['upload_extension']);
                    for($index=0; $index<count($str_tmp); $index++)
                    {
                        if(!empty($str_tmp[$index]))
                        {
                            $options['exclude_files'][] = '#' . '.*\.' . $str_tmp[$index] . '$' . '#';
                        }
                    }
                }
            }

            if($staging_options['content_check'] == '1')
            {
                foreach ($staging_options['content_list'] as $key => $value)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($content_path.DIRECTORY_SEPARATOR.$key), '/').'#';
                }

                if(isset($staging_options['content_extension']) && !empty($staging_options['content_extension']))
                {
                    $str_tmp = explode(',', $staging_options['content_extension']);
                    for($index=0; $index<count($str_tmp); $index++)
                    {
                        if(!empty($str_tmp[$index]))
                        {
                            $options['exclude_files'][] = '#' . '.*\.' . $str_tmp[$index] . '$' . '#';
                        }
                    }
                }
            }

            $options['is_create_subdomain'] = false;

            $options['old_site_url']=untrailingslashit($this->get_database_site_url());
            $options['old_home_url']=untrailingslashit($this->get_database_home_url());

            $options['setting'] = $this->set_staging_option();
            $options['staging_options']=$staging_options;
            $options['site_data']=$site_data;

            $options['exclude_tables'] = array();
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'hw_blocks';
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_options';

            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_increment_big_ids';
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_log';
            $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_record_task';

            if($staging_options['database_check'] == '1')
            {
                foreach ($staging_options['database_list'] as $table)
                {
                    $options['exclude_tables'][] = $table;
                }
            }
            $task = new WPvivid_New_Staging_Copy_Task();
            $ret=$task->create_new_task($options);
            $this->wpvivid_check_add_litespeed_server();

            $list[$site_id]['copy_time']=time();
            $this->option->update_option('staging_site_data', $list);

            echo json_encode($ret);
            die();
        }
        else
        {
            die();
        }
    }

    public function get_staging_copy_progress_ex()
    {
        $this->ajax_check_security();

        $task_option=$this->option->get_option('wpvivid_staging_copy_task_ex');
        $this->task=new WPvivid_New_Staging_Copy_Task($task_option);

        $ret['test']= $this->task->task;

        $status=$this->task->get_status();
        if($status==false)
        {
            $ret['result']='failed';
            $ret['error']='Failed to get status of the task.';
            echo json_encode($ret);
            die();
        }
        $ret['test_time']=time()-$status['run_time'];
        if($status['str']=='error')
        {
            $ret['result']='failed';
            $ret['error']=$status['error'];
        }
        else if($status['str']=='ready')
        {
            $ret['result']='success';
            $ret['status']='ready';
        }
        else if($status['str']=='running')
        {
            $limit=$this->task->get_time_limit();

            $time_spend=time()-$status['timeout'];
            $last_active_time=time()-$status['run_time'];
            if($time_spend>$limit&&$last_active_time>180)
            {
                //time out
                $max_resume_count=$this->task->get_max_resume_count();
                $status['resume_count']++;
                if($status['resume_count']>$max_resume_count)
                {
                    $message=__('Too many resumption attempts.', 'wpvivid-backuprestore');
                    $log=new WPvivid_Staging_Log();
                    $log->OpenLogFile($this->task->task['log_file_name']);
                    $log->WriteLog($message,'error');
                    $this->task->update_task_status(false,'error',false,$status['resume_count'],$message);
                    $ret['result']='failed';
                    $ret['error']=$message;
                }
                else
                {
                    $ret['result']='success';
                    $ret['status']='ready';

                    $log=new WPvivid_Staging_Log();
                    $log->OpenLogFile($this->task->task['log_file_name']);
                    $log->WriteLog('Task timed out.','error');
                    $this->task->update_task_status(false,'ready',false,$status['resume_count']);
                }
            }
            else
            {
                $time_spend=time()-$status['run_time'];
                if($time_spend>180)
                {
                    $ret['result']='success';
                    $ret['status']='no_responds';
                }
                else
                {
                    $ret['result']='success';
                    $ret['status']='running';
                }
            }
        }
        else if($status['str']=='completed')
        {
            $ret['result']='success';
            $ret['status']='completed';
        }
        else
        {
            $ret['result']='failed';
            $ret['error']='Failed to get status of the task.';
        }

        if($ret['result']=='success')
        {
            $progress=$this->task->get_progress();

            $log=new WPvivid_Staging_Log();
            $log->OpenLogFile($this->task->task['log_file_name']);
            $file_name=$log->GetSaveLogFolder(). $this->task->task['log_file_name'].'_log.txt';
            $file =fopen($file_name,'r');
            $buffer='';
            if(!$file)
            {
                $buffer='open log file failed';
            }
            else
            {
                if(filesize($file_name)<=5*1024)
                {
                    while(!feof($file))
                    {
                        $buffer .= fread($file,1024);
                    }
                }
                else
                {
                    $pos=-2;
                    $eof='';
                    $n=50;
                    $buffer_array = array();
                    while($n>0)
                    {
                        while($eof!=="\n")
                        {
                            if(!fseek($file, $pos, SEEK_END))
                            {
                                $eof=fgetc($file);
                                $pos--;
                            }
                            else
                            {
                                break;
                            }
                        }
                        $buffer_array[].=fgets($file);
                        $eof='';
                        $n--;
                    }

                    if(!empty($buffer_array))
                    {
                        $buffer_array = array_reverse($buffer_array);
                        foreach($buffer_array as $value)
                        {
                            $buffer.=$value;
                        }
                    }
                }

                fclose($file);
            }

            $ret['log']=$buffer;
            $ret['percent']=$progress['main_percent'];
        }

        echo json_encode($ret);
        //echo WPvivid_Merge_Common_Function::prepare_response($ret);
        die();
    }

    public function copy_restart_staging_ex()
    {
        $this->ajax_check_security();

        $this->end_shutdown_function=false;

        $task_option=$this->option->get_option('wpvivid_staging_copy_task_ex');
        $this->task=new WPvivid_New_Staging_Copy_Task($task_option);

        $this->task->update_task_status(true,'running');
        if($this->task->is_task_finished())
        {
            $ret['result']='success';
            $this->task->update_task_status(false,'completed');
            echo json_encode($ret);
        }
        else
        {
            $this->flush();
            $job= $this->task->get_next_job();

            if($job===false)
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'completed');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }

            $this->task->set_time_limit();
            $ret= $this->task->do_staging_job($job);
            if($ret['result']!='success')
            {
                $this->task->update_task_status(false,'error',false,false,$ret['error']);
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }

            if( $this->task->is_task_finished())
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'completed');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }
            else
            {
                $ret['result']='success';
                $this->task->update_task_status(false,'ready');
                echo json_encode($ret);
                $this->end_shutdown_function=true;
                die();
            }
        }

        $this->end_shutdown_function=true;

        die();
    }

    public function create_new_prefix($use_additional_db)
    {
        if($use_additional_db)
        {
            global $wpdb;
            $prefix=$wpdb->base_prefix;
        }
        else
        {
            global $wpdb;
            $prefix='';
            $site_id=0;
            while(1)
            {
                $prefix='wpvividstg'.$site_id.'_';
                $sql=$wpdb->prepare("SHOW TABLES LIKE %s;", $wpdb->esc_like($prefix) . '%');
                $result = $wpdb->get_results($sql, OBJECT_K);
                if(empty($result))
                {
                    break;
                }
                $site_id++;
            }
        }

        return $prefix;
    }

    public function export_setting_addon($json)
    {
        $default = array();
        $wpvivid_staging_history = get_option('wpvivid_staging_history', $default);
        $wpvivid_push_staging_history = get_option('wpvivid_push_staging_history', $default);
        $json['data']['wpvivid_staging_history'] = $wpvivid_staging_history;
        $json['data']['wpvivid_push_staging_history'] = $wpvivid_push_staging_history;
        return $json;
    }

    public function wpvivid_check_add_litespeed_server()
    {
        $litespeed=false;
        if ( isset( $_SERVER['HTTP_X_LSCACHE'] ) && $_SERVER['HTTP_X_LSCACHE'] )
        {
            $litespeed=true;
        }
        elseif ( isset( $_SERVER['LSWS_EDITION'] ) && strpos( $_SERVER['LSWS_EDITION'], 'Openlitespeed' ) === 0 ) {
            $litespeed=true;
        }
        elseif ( isset( $_SERVER['SERVER_SOFTWARE'] ) && $_SERVER['SERVER_SOFTWARE'] == 'LiteSpeed' ) {
            $litespeed=true;
        }

        if($litespeed)
        {
            if ( ! function_exists( 'got_mod_rewrite' ) )
            {
                require_once ABSPATH . 'wp-admin/includes/misc.php';
            }

            if(function_exists('insert_with_markers'))
            {
                if(!function_exists('get_home_path'))
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                $home_path     = get_home_path();
                $htaccess_file = $home_path . '.htaccess';

                if ( ( ! file_exists( $htaccess_file ) && is_writable( $home_path ) ) || is_writable( $htaccess_file ) )
                {
                    if ( got_mod_rewrite() )
                    {
                        $line[]='<IfModule Litespeed>';
                        $line[]='RewriteEngine On';
                        $line[]='RewriteRule .* - [E=noabort:1, E=noconntimeout:1]';
                        $line[]='</IfModule>';
                        insert_with_markers($htaccess_file,'WPvivid Rewrite Rule for LiteSpeed',$line);
                    }
                }
            }
        }
    }

    public function wpvivid_check_clear_litespeed_rule()
    {
        $litespeed=false;
        if ( isset( $_SERVER['HTTP_X_LSCACHE'] ) && $_SERVER['HTTP_X_LSCACHE'] )
        {
            $litespeed=true;
        }
        elseif ( isset( $_SERVER['LSWS_EDITION'] ) && strpos( $_SERVER['LSWS_EDITION'], 'Openlitespeed' ) === 0 ) {
            $litespeed=true;
        }
        elseif ( isset( $_SERVER['SERVER_SOFTWARE'] ) && $_SERVER['SERVER_SOFTWARE'] == 'LiteSpeed' ) {
            $litespeed=true;
        }

        if($litespeed)
        {
            if ( ! function_exists( 'got_mod_rewrite' ) )
            {
                require_once ABSPATH . 'wp-admin/includes/misc.php';
            }

            if(function_exists('insert_with_markers'))
            {
                if(!function_exists('get_home_path'))
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                $home_path     = get_home_path();
                $htaccess_file = $home_path . '.htaccess';

                if ( ( ! file_exists( $htaccess_file ) && is_writable( $home_path ) ) || is_writable( $htaccess_file ) )
                {
                    if ( got_mod_rewrite() )
                    {
                        insert_with_markers($htaccess_file,'WPvivid Rewrite Rule for LiteSpeed','');
                    }
                }
            }
        }
    }

    public function get_login_url()
    {
        $active_plugins = get_option( 'active_plugins', array() );
        $plugins=get_plugins();
        $sg_security_slug='sg-security/sg-security.php';
        if(!empty($plugins))
        {
            if(isset($plugins[$sg_security_slug]))
            {
                if(in_array($sg_security_slug, $active_plugins))
                {
                    $sg_security_login_type = get_option('sg_security_login_type', false);
                    $sg_security_login_url  = get_option('sg_security_login_url', false);
                    if($sg_security_login_type !== false && $sg_security_login_type === 'custom' && isset($sg_security_login_url) && !empty($sg_security_login_url))
                    {
                        $home_url = home_url();
                        global $wpdb;
                        $home_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'home' ) );
                        foreach ( $home_url_sql as $home ){
                            $home_url = $home->option_value;
                        }
                        $home_url = untrailingslashit($home_url);
                        return $home_url.'/'.$sg_security_login_url;
                    }
                }
            }
        }
        return wp_login_url();
    }
}