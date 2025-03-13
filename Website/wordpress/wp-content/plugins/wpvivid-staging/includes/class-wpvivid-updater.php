<?php

if (!defined('WPVIVID_STAGING_PLUGIN_DIR'))
{
    die;
}

class WPvivid_Staging_Updater
{
    public $plugin_basename;

    public function __construct()
    {

        $this->plugin_basename        = plugin_basename( WPVIVID_STAGING_PLUGIN_DIR . 'wpvivid-staging.php' );
        add_action( 'load-plugins.php', array( $this, 'setup_staging_update_row' ),99);
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'set_site_transient_update_plugins' ) );

        if($this->need_update())
        {
            add_action( 'admin_notices', array($this,'show_update_notices'));
        }

        $this->check_update_schedule();

        add_action('wpvivid_staging_auto_update_schedule',array( $this,'check_dashboard_update_event'));
        add_action('wpvivid_staging_update_event',array( $this,'auto_update_event'));
    }

    public function get_staging_version($dashboard_info=array())
    {
        if(empty($dashboard_info))
        {
            $dashboard_info=get_option('wpvivid_dashboard_info',array());
        }

        if(empty($dashboard_info))
        {
            return false;
        }
        else
        {
            if(isset($dashboard_info['plugins']['staging_pro']))
            {
                return $dashboard_info['plugins']['staging_pro']['install']['version'];
            }
            else
            {
                return false;
            }
        }
    }

    public function get_staging_active($dashboard_info=array())
    {
        if(empty($dashboard_info))
        {
            $dashboard_info=get_option('wpvivid_dashboard_info',array());
        }

        if(empty($dashboard_info))
        {
            return false;
        }
        else
        {
            if(isset($dashboard_info['plugins']['staging_pro']))
            {
                return $dashboard_info['plugins']['staging_pro']['active'];
            }
            else
            {
                return false;
            }
        }
    }

    public function need_update()
    {
        $dashboard_info=get_option('wpvivid_dashboard_info',array());
        if(empty($dashboard_info))
        {
            return false;
        }
        
        $version=$this->get_staging_version();

        if(version_compare(WPVIVID_STAGING_VERSION,$version, '<'))
        {
            return true;
        }

        return false;
    }

    public function show_update_notices()
    {
        if (is_multisite())
        {
            if(!is_network_admin())
            {
                return ;
            }
        }

        $wpvivid_common_setting = get_option('wpvivid_common_setting', array());
        if(!empty($wpvivid_common_setting))
        {
            if(isset($wpvivid_common_setting['hide_admin_update_notice']) && $wpvivid_common_setting['hide_admin_update_notice'])
            {
                return;
            }
        }

        global $pagenow;

        if($pagenow=='update.php')
        {
            return ;
        }

        if($this->need_update())
        {
            $dashboard_info=get_option('wpvivid_dashboard_info',array());
            if($dashboard_info!==false)
            {
                $version=$this->get_staging_version();
                $show_time = get_option('wpvivid_need_update_pro_notice', false);

                if(time()>$show_time)
                {
                    $plugin_basename= plugin_basename( WPVIVID_STAGING_PLUGIN_DIR . 'wpvivid-staging.php' );
                    $url=wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $plugin_basename, 'upgrade-plugin_' . $plugin_basename);
                    $message = '<div class="notice notice-warning notice-need-update-pro is-dismissible" style="padding: 11px 15px;">';
                    $message .= sprintf(__('There is a new version of %s Staging available.', 'wpvivid'), apply_filters('wpvivid_white_label_display', 'WPvivid Plugins')).' <a href="'. $url. '">Update now</a> to Version ' . $version . ' </div>';
                    echo wp_kses_post($message);
                }
            }
            ?>
            <script>
                jQuery(document).on('click', '.notice-need-update-pro .notice-dismiss', function(){
                    var ajax_data = {
                        'action': 'wpvivid_hide_need_update_pro_notice'
                    };
                    var time_out = 30000;
                    jQuery.ajax({
                        type: "post",
                        url: '<?php echo admin_url('admin-ajax.php');?>',
                        data: ajax_data,
                        success: function (data) {
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                        },
                        timeout: time_out
                    });
                });
            </script>
            <?php
        }
        else
        {
            delete_option('wpvivid_need_update_pro_notice');
        }
    }

    public function check_update_schedule()
    {
        if(!defined( 'DOING_CRON' ))
        {
            if(wp_get_schedule('wpvivid_staging_auto_update_schedule')===false)
            {
                if(wp_schedule_event(time()+30, 'daily', 'wpvivid_staging_auto_update_schedule')===false)
                {
                    return false;
                }
            }
        }

        return true;
    }

    public function check_dashboard_update_event()
    {
        $info= get_option('wpvivid_pro_user',false);

        if($info===false)
        {
            die();
        }

        if(isset($info['token']))
        {
            $user_info=$info['token'];
        }
        else
        {
            $user_info=$info['password'];
        }
        $server=new WPvivid_Staging_Connect_server();
        $ret=$server->login($user_info,false);

        if($ret['result']=='success')
        {
            if($ret['status']['check_active'])
            {
                update_option('wpvivid_dashboard_info',$ret['status']);

                $this->update_site_transient_update_plugins();

                $auto_update=get_option('wpvivid_auto_update_staging','0');
                if($auto_update=='1')
                {
                    if($this->need_update())
                    {
                        wp_schedule_single_event( time() + 60, 'wpvivid_staging_update_event' );
                    }
                }
            }
            else
            {
                delete_option('wpvivid_pro_user');
                delete_option('wpvivid_dashboard_info');
            }
        }
        else
        {
            $this->handle_server_error($ret);
        }

        die();
    }

    public function handle_server_error($error)
    {
        if(isset($error['error_code']))
        {
            if($error['error_code']==109||$error['error_code']==108||$error['error_code']==107)
            {
                delete_option('wpvivid_pro_user');
                delete_option('wpvivid_dashboard_info');
            }
        }
    }

    public function auto_update_event()
    {
        $auto_update=get_option('wpvivid_auto_update_staging','0');

        if($auto_update=='0')
        {
            die();
        }

        $this->update_pro();

        die();
    }

    public function update_pro()
    {
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-plugin-installer-skin.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );
        if(!function_exists('request_filesystem_credentials'))
        {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        $skin = new Automatic_Upgrader_Skin;
        $upgrader = new Plugin_Upgrader( $skin );

        $upgrade_result = $upgrader->upgrade(
            $this->plugin_basename,
            array(
                'clear_update_cache'           => false,
                // Always use partial builds if possible for core updates.
                'pre_check_md5'                => false,
                // Only available for core updates.
                'attempt_rollback'             => true,
                // Allow relaxed file ownership in some scenarios
                'allow_relaxed_file_ownership' => false,
            )
        );
    }

    public function setup_staging_update_row()
    {
        remove_action( 'after_plugin_row_' . $this->plugin_basename, 'wp_plugin_update_row', 10);
        add_action( 'after_plugin_row_'.$this->plugin_basename, array( $this, 'pro_update_row' ), 10, 2 );
    }

    public function update_site_transient_update_plugins()
    {
        remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'set_site_transient_update_plugins' ) );

        $update_cache = get_site_transient( 'update_plugins' );

        $update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();

        $version_info=$this->get_version();

        if ( false === $version_info )
        {
            return;
        }

        if ( version_compare(WPVIVID_STAGING_VERSION, $version_info->new_version, '<' ) )
        {

            $update_cache->response[ $this->plugin_basename ] = $version_info;

        }

        $update_cache->last_checked = time();
        $update_cache->checked[ $this->plugin_basename ] = WPVIVID_STAGING_VERSION;

        set_site_transient( 'update_plugins', $update_cache );

        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'set_site_transient_update_plugins' ) );
    }

    public function set_site_transient_update_plugins($_transient_data)
    {
        global $pagenow;

        if ( ! is_object( $_transient_data ) )
        {
            $_transient_data = new stdClass;
        }

        $version_info=$this->get_version();

        if($version_info===false)
        {
            return $_transient_data;
        }

        if ( version_compare( WPVIVID_STAGING_VERSION, $version_info->new_version, '<' ) ) {

            $_transient_data->response[ $this->plugin_basename ] = $version_info;

        }

        $_transient_data->last_checked           = time();
        $_transient_data->checked[  $this->plugin_basename ] = WPVIVID_STAGING_VERSION;

        return $_transient_data;
    }

    public function get_version()
    {
        $dashboard_info = get_option('wpvivid_dashboard_info', array());

        if(empty($dashboard_info)||!isset($dashboard_info['staging_version_info']))
        {
            $info= get_option('wpvivid_pro_user',false);

            if($info!==false)
            {
                $user_info=$info['token'];
                $server=new WPvivid_Staging_Connect_server();
                $ret=$server->login($user_info,false);
                if($ret['result']=='success')
                {
                    update_option('wpvivid_dashboard_info',$ret['status']);
                    $dashboard_info=$ret['status'];
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        if(!isset($dashboard_info['staging_version_info']))
        {
            return false;
        }

        $version_info=new stdClass();

        foreach ($dashboard_info['staging_version_info'] as $key => $value)
        {
            $version_info->$key = $value;
        }

        $connect=new WPvivid_Staging_Connect_server();
        $version_info->download_link=$connect->get_download_link();
        $version_info->package=$connect->get_download_link();
        return $version_info;
    }

    public function pro_update_row($file, $plugin_data)
    {
        if( ! current_user_can( 'update_plugins' ) )
        {
            return;
        }

        if ( $this->plugin_basename != $file )
        {
            return;
        }

        remove_filter( 'pre_set_site_transient_update_plugins', array( $this, 'set_site_transient_update_plugins' ) );

        $update_cache = get_site_transient( 'update_plugins' );

        $update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();

        if ( empty( $update_cache->response ) || empty( $update_cache->response[ $this->plugin_basename ] ) )
        {
            $version_info=$this->get_version();

            if ( false === $version_info )
            {
                return;
            }

            if ( version_compare(WPVIVID_STAGING_VERSION, $version_info->new_version, '<' ) )
            {

                $update_cache->response[ $this->plugin_basename ] = $version_info;

            }

            $update_cache->last_checked = time();
            $update_cache->checked[ $this->plugin_basename ] = WPVIVID_STAGING_VERSION;

            set_site_transient( 'update_plugins', $update_cache );

        } else {
            $version_info = $update_cache->response[ $this->plugin_basename ];
        }

        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'set_site_transient_update_plugins' ) );

        if ( ! empty( $update_cache->response[ $this->plugin_basename ] ) && version_compare( WPVIVID_STAGING_VERSION, $version_info->new_version, '<' ) )
        {
            // build a plugin list row, with update notification
            $wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

            if ( is_network_admin() )
            {
                $active_class = is_plugin_active_for_network( $file ) ? ' active' : '';
            } else {
                $active_class = is_plugin_active( $file ) ? ' active' : '';
            }

            echo '<tr class="plugin-update-tr' . $active_class . ' " id="' . esc_attr( 'wpvivid-staging' ) . '-update" data-slug="' . esc_attr( 'wpvivid-staging' ) . '" data-plugin="' . esc_attr( $file ) . '">';
            echo '<td colspan="' . esc_attr( $wp_list_table->get_column_count() ) . '" class="plugin-update colspanchange">';
            echo '<div class="update-message notice inline notice-warning notice-alt"><p>';
            $admin_url = apply_filters('wpvivid_get_admin_url', ''). 'admin.php?page='.apply_filters('wpvivid_white_label_plugin_name', 'wpvividstg-pro');

            if ( empty( $version_info->download_link ) )
            {
                printf(
                    __( 'There is a new version of %1$s available.<a href="%2$s" %3$s> Update now</a>' ),
                    $plugin_data['Name'],
                    $admin_url,
                    sprintf(
                        'aria-label="%s"',
                        /* translators: %s: plugin name */
                        esc_attr( sprintf( __( 'Update %s now' ), $plugin_data['Name'] ) )
                    )
                );
            } else {
                printf(
                    __( 'There is a new version of %1$s available.<a href="%2$s" class="update-link" %3$s> Update now%4$s.', 'wpvivid' ),
                    $plugin_data['Name'],
                    esc_url( wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $this->plugin_basename, 'upgrade-plugin_' . $this->plugin_basename ) ),
                    sprintf(
                        'aria-label="%s"',
                        /* translators: %s: plugin name */
                        esc_attr( sprintf( __( 'Update %s now' ), $plugin_data['Name'] ) )
                    ),
                    '</a>'
                );
            }

            do_action( "in_plugin_update_message-{$file}", $plugin_data, $version_info );

            echo '</p></div></td></tr>';
        }
    }
}