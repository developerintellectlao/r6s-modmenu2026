<?php

class WPvivid_Staging_pro_page
{
    public $main_tab;
    public function __construct()
    {
        //ajax;
        add_action('wp_ajax_wpvivid_auto_update_staging_setting', array($this, 'auto_update_staging_setting'));

        add_filter('wpvivid_get_staging_screens', array($this, 'get_staging_screens'), 10);
        add_filter('wpvivid_get_staging_menu', array($this, 'get_staging_menu'), 10, 2);

        add_action('wp_ajax_wpvivid_staging_login',array( $this,'dashboard_login'));
        add_action('wp_ajax_wpvivid_staging_active',array( $this,'dashboard_active'));
        add_action('wp_ajax_wpvivid_staging_check_update',array( $this,'check_update_plugin'));
        add_action('wp_ajax_wpvivid_update_staging',array( $this,'update_staging'));
    }

    public function get_staging_screens($screens)
    {
        $screen['menu_slug']='wpvividstg-pro';
        $screen['screen_id']='wpvivid-staging_page_wpvividstg-pro';
        $screen['is_top']=false;
        $screens[]=$screen;
        return $screens;
    }

    public function get_staging_menu($submenus, $parent_slug)
    {
        if ( ! function_exists( 'is_plugin_active' ) )
        {
            include_once(ABSPATH.'wp-admin/includes/plugin.php');
        }
        if(is_plugin_active('wpvivid-backup-pro/wpvivid-backup-pro.php'))
        {
            return $submenus;
        }

        $submenu['parent_slug']=$parent_slug;
        $submenu['page_title']=__('WPvivid Staging');
        $submenu['menu_title']='License';
        $submenu['capability']='administrator';
        $submenu['menu_slug']=strtolower(sprintf('%s-pro', apply_filters('wpvivid_white_label_slug', 'wpvividstg')));//'wpvividstg-pro';
        $submenu['index']=5;
        $submenu['function']=array($this, 'display_plugin_pro_page');
        $submenus[]=$submenu;
        return $submenus;
    }

    public function display_plugin_pro_page()
    {
        $this->check_dashboard_info();
        $this->init_page();
    }

    public function check_dashboard_info()
    {
        $info= get_option('wpvivid_pro_user',false);
        if($info!==false)
        {
            $dashboard_info=get_option('wpvivid_dashboard_info',array());
            if(empty($dashboard_info))
            {
                $user_info=$info['token'];
                $server=new WPvivid_Staging_Connect_server();
                $ret=$server->login($user_info,false);
                if($ret['result']=='success')
                {
                    update_option('wpvivid_dashboard_info',$ret['status']);
                }
            }
        }
    }

    public function init_page()
    {
       ?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h1><?php esc_attr_e( apply_filters('wpvivid_white_label_display', 'WPvivid').' Staging Pro - License', 'wpvivid' ); ?></h1>
            <div id="wpvivid_pro_notice">
            </div>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <!-- main content -->
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="wpvivid-backup">
                                <?php $this->welcome_bar();?>
                                <div class="wpvivid-nav-bar wpvivid-clear-float">
                                    <span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span>
                                    <span> Tip: You can use either a father license or a child license to activate <?php echo apply_filters('wpvivid_white_label_display', 'WPvivid'); ?> plugins.</span>
                                </div>
                                <div class="wpvivid-canvas wpvivid-clear-float">
                                    <div class="wpvivid-one-coloum">
                                        <div class="wpvivid-one-coloum wpvivid-workflow wpvivid-clear-float">
                                            <?php $this->status_bar();?>
                                            <?php $this->user_bar();?>
                                        </div>
                                        <div style="clear: both;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- sidebar -->
                    <?php $this->sidebar(); ?>
                    <!-- #postbox-container-1 .postbox-container -->
                </div>
            </div>
        </div>
        <script>
            function wpvivid_display_pro_notice(notice_type, notice_message)
            {
                if(notice_type === 'Success')
                {
                    var div = "<div class='notice notice-success is-dismissible inline'><p>" + notice_message + "</p>" +
                        "<button type='button' class='notice-dismiss' onclick='click_dismiss_pro_notice(this);'>" +
                        "<span class='screen-reader-text'>Dismiss this notice.</span>" +
                        "</button>" +
                        "</div>";
                }
                else{
                    var div = "<div class=\"notice notice-error inline\"><p>Error: " + notice_message + "</p></div>";
                }
                jQuery('#wpvivid_pro_notice').show();
                jQuery('#wpvivid_pro_notice').html(div);
            }
            function wpvivid_dashboard_output_ajaxerror(action, textStatus, errorThrown)
            {
                action = 'trying to establish communication with your server';
                var error_msg = "wpvivid_request: "+ textStatus + "(" + errorThrown + "): an error occurred when " + action + ". " +
                    "This error may be request not reaching or server not responding. Please try again later.";
                //"This error could be caused by an unstable internet connection. Please try again later.";
                return error_msg;
            }
        </script>
        <?php
    }

    public function welcome_bar()
    {
        ?>
        <div class="wpvivid-welcome-bar wpvivid-clear-float">
            <div class="wpvivid-welcome-bar-left">
                <p><span class="dashicons dashicons-admin-network wpvivid-dashicons-large wpvivid-dashicons-green"></span><span class="wpvivid-page-title">License</span></p>
                <span class="about-description">Enter your license to activate <?php echo apply_filters('wpvivid_white_label_display', 'WPvivid'); ?> plugin and get plugin updates and support.</span>
            </div>
            <div class="wpvivid-welcome-bar-right">
                <p></p>
                <div style="float:right;">
                    <span>Local Time:</span>
                    <span>
                        <a href="<?php esc_attr_e(apply_filters('wpvivid_get_admin_url', '').'options-general.php'); ?>">
                            <?php
                            $offset=get_option('gmt_offset');
                            echo date("l, F-d-Y H:i",time()+$offset*60*60);
                            ?>
                        </a>
                    </span>
                    <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                        <div class="wpvivid-left">
                            <!-- The content you need -->
                            <p>Clicking the date and time will redirect you to the WordPress General Settings page where you can change your timezone settings.</p>
                            <i></i> <!-- do not delete this line -->
                        </div>
                    </span>
                </div>
            </div>
        </div>
        <?php
    }

    public function status_bar()
    {
        if(isset($_REQUEST['auto_update']))
        {
            if($_REQUEST['auto_update']==1)
            {
                update_option('wpvivid_auto_update_staging','1');
            }
            else if($_REQUEST['auto_update']==0)
            {
                update_option('wpvivid_auto_update_staging','0');
            }
        }

        $current_version=WPVIVID_STAGING_VERSION;

        $auto_update =get_option('wpvivid_auto_update_staging', false);

        if($auto_update === false||$auto_update=='0')
        {
            $auto_update_class = 'wpvivid-green';
            $auto_update_text = 'Turn On';
            $auto_update_status = 'Disabled';
        }
        else{
            $auto_update_class = 'wpvivid-grey';
            $auto_update_text = 'Turn Off';
            $auto_update_status = 'Enabled';
        }

        if($auto_update=='1')
        {
            $auto_update_switch_url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-pro', 'wpvividstg-pro').'&auto_update=0';
        }
        else
        {
            $auto_update_switch_url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-pro', 'wpvividstg-pro').'&auto_update=1';
        }

        $dashboard_info=get_option('wpvivid_dashboard_info',array());

        if(empty($dashboard_info))
        {
            $active_status='N/A';
            $version_compare='';
        }
        else
        {
            global $wpvivid_staging;
            $active=$wpvivid_staging->updater->get_staging_active();
            if($active)
            {
                $active_status='Active';
            }
            else
            {
                $active_status='Inactive';
            }

            $version_compare = ' (Latest Version)';

            $version=$wpvivid_staging->updater->get_staging_version();

            if(version_compare(WPVIVID_STAGING_VERSION, $version, '<'))
            {
                $version_compare = '(Latest Version Available: '.$version.')';
            }
        }

        ?>
        <div class="wpvivid-two-col">
            <p>
                <span class="dashicons dashicons-awards wpvivid-dashicons-blue"></span>
                <span>Current Version: </span><span><?php echo $current_version; ?></span>
                <span><?php echo $version_compare; ?></span>
            </p>
            <p>
                <span class="dashicons dashicons-update-alt wpvivid-dashicons-blue"></span>
                <span>Automatic Updates: </span>
                <span id="auto_update_status"><?php _e($auto_update_status); ?></span>
                <span class="wpvivid-rectangle <?php esc_attr_e($auto_update_class); ?>" id="wpvivid_auto_update_switch" title="Click here to disable automatic updates of WPvivid Plugin" style="cursor:pointer;">
                                                        <?php _e($auto_update_text); ?>
                                                    </span>
            </p>
            <p>
                <span class="dashicons dashicons-yes-alt wpvivid-dashicons-blue"></span>
                <span>Status: </span>
                <span><?php echo $active_status; ?></span>
            </p>
        </div>
        <script>
            jQuery('#wpvivid_auto_update_switch').click(function()
            {
                location.href='<?php echo $auto_update_switch_url;?>';
            });
        </script>
        <?php
    }

    public function user_bar()
    {
        $user_info= get_option('wpvivid_pro_user',false);
        ?>
        <div class="wpvivid-two-col" style="padding-right:1em;">
            <?php $this->sign_out_bar();?>
            <?php
            if($user_info===false)
            {
                $this->login_form();
            }
            else
            {
                $this->logged();
            }
            ?>
        </div>
        <?php
    }

    public function sign_out_bar()
    {
        if(isset($_REQUEST['sign_out']))
        {
            delete_option('wpvivid_pro_user');
            delete_option('wpvivid_dashboard_info');
            $url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-pro', 'wpvividstg-pro');
            ?>
            <script>
                location.href='<?php echo $url;?>';
            </script>
            <?php
        }
        $white_label_setting=get_option('white_label_setting',array());
        if(empty($white_label_setting))
        {
            $white_label_website_protocol='https';
            $white_label_website='wpvivid.com/my-account';
        }
        else
        {
            $white_label_website_protocol = empty($white_label_setting['white_label_website_protocol']) ? 'https' : $white_label_setting['white_label_website_protocol'];
            $white_label_website = empty($white_label_setting['white_label_website']) ? 'wpvivid.com/my-account' : $white_label_setting['white_label_website'];
        }
        $signout_url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-pro', 'wpvividstg-pro').'&sign_out=1';
        ?>
        <span class="dashicons dashicons-businessman wpvivid-dashicons-green"></span>
        <span><a href="<?php echo esc_html($white_label_website_protocol); ?>://<?php echo esc_html($white_label_website); ?>" target="_blank">My Account</a></span>
        <span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span>
        <span><a href="#" id="wpvivid_dashboard_signout">Sign Out</a></span>
        <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
            <div class="wpvivid-bottom">
                <!-- The content you need -->
                <p>Sign out or switch to another account. Once signed out, you will need to re-enter the credentials to get WPvivid Pro authorization.</p>
                <i></i> <!-- do not delete this line -->
            </div>
        </span>
        <script>
            jQuery('#wpvivid_dashboard_signout').click(function()
            {
                var descript = 'Are you sure you want to sign out?';
                var ret = confirm(descript);
                if(ret === true)
                {
                    location.href='<?php echo $signout_url;?>';
                }
            });
        </script>
        <?php
    }

    public function sidebar()
    {
        if(apply_filters('wpvivid_show_sidebar',true))
        {
            ?>
            <div id="postbox-container-1" class="postbox-container">

                <div class="meta-box-sortables ui-sortable">

                    <div class="postbox  wpvivid-sidebar">

                        <h2 style="margin-top:0.5em;"><span class="dashicons dashicons-sticky wpvivid-dashicons-orange"></span>
                            <span><?php esc_attr_e(
                                    'Troubleshooting', 'WpAdminStyle'
                                ); ?></span></h2>
                        <div class="inside" style="padding-top:0;">
                            <ul class="" >
                                <li style="border-top:1px solid #f1f1f1;"><span class="dashicons dashicons-editor-help wpvivid-dashicons-orange" ></span>
                                    <a href="https://docs.wpvivid.com/troubleshooting-issues-on-wpvivid-backup-pro.html"><b>Troubleshooting</b></a>
                                    <small><span style="float: right;"><a href="#" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                                </li>
                                <li style="border-top:1px solid #f1f1f1;"><span class="dashicons dashicons-admin-generic wpvivid-dashicons-orange" ></span>
                                    <a href="https://docs.wpvivid.com/wpvivid-backup-pro-advanced-settings.html"><b>Adjust Advanced Settings </b></a>
                                    <small><span style="float: right;"><a href="#" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                                </li>
                            </ul>
                        </div>
                        <h2><span class="dashicons dashicons-businesswoman wpvivid-dashicons-green"></span>
                            <span><?php esc_attr_e(
                                    'Support', 'WpAdminStyle'
                                ); ?></span></h2>
                        <div class="inside">

                            <ul class="">
                                <li><span class="dashicons dashicons-admin-comments wpvivid-dashicons-green"></span>
                                    <a href="https://wpvivid.com/submit-ticket"><b>Submit A Ticket</b></a>
                                    <br>
                                    The ticket system is for <?php echo apply_filters('wpvivid_white_label_display', 'WPvivid'); ?> Pro users only. If you need any help with our plugin, submit a ticket and we will respond shortly.
                                </li>
                            </ul>

                        </div>

                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables -->

            </div>
            <?php
        }
    }

    public function login_form()
    {
        ?>
        <form action="">
            <div style="margin-top: 10px; margin-bottom: 15px;">
                <input type="password" class="regular-text" id="wpvivid_account_license" placeholder="License" autocomplete="new-password" required="">
            </div>
            <div style="margin-bottom: 10px; float: left; margin-left: 0; margin-right: 10px;">
                <input class="button-primary" id="wpvivid_active_btn" type="button" value="Activate">
            </div>
            <div style="clear:both;"></div>
            <div id="wpvivid_login_box_progress" style="display: none">
                <p>
                    <span class="dashicons dashicons-admin-network wpvivid-dashicons-green"></span>
                    <span id="wpvivid_log_progress_text"></span>
                </p>
            </div>
            <div id="wpvivid_login_error_msg_box" style="display: none">
                <p>
                    <span class="dashicons dashicons-info wpvivid-dashicons-grey"></span>
                    <span id="wpvivid_login_error_msg"></span>
                </p>
            </div>
            <div style="clear: both;"></div>
        </form>
        <script>
            jQuery('#wpvivid_active_btn').click(function()
            {
                wpvivid_dashboard_login();
            });

            function wpvivid_dashboard_login()
            {
                var license = jQuery('#wpvivid_account_license').val();
                var ajax_data={
                    'action':'wpvivid_staging_login',
                    'license':license,
                };

                var login_msg = '<?php echo sprintf(__('Logging in to your %s account', 'wpvivid'), apply_filters('wpvivid_white_label_display', 'WPvivid')); ?>';
                wpvivid_lock_login(true);
                wpvivid_login_progress(login_msg);
                jQuery('#wpvivid_pro_notice').hide();
                wpvivid_post_request(ajax_data, function(data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        //need_active
                        if(jsonarray.need_active)
                        {
                            wpvivid_login_progress('You have successfully logged in');
                            wpvivid_active_site();
                        }
                        else
                        {
                            wpvivid_login_progress('You have successfully logged in');
                            location.reload();
                        }
                    }
                    else
                    {
                        wpvivid_lock_login(false,jsonarray.error);
                    }
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_dashboard_output_ajaxerror('check update', textStatus, errorThrown);
                    wpvivid_lock_login(false,error_message);
                });
            }

            function wpvivid_active_site()
            {
                var license = jQuery('#wpvivid_account_license').val();
                var ajax_data={
                    'action':'wpvivid_staging_active',
                    'license':license,
                };

                wpvivid_lock_login(true);
                wpvivid_login_progress('Activating your license on the current site');
                jQuery('#wpvivid_pro_notice').hide();
                wpvivid_post_request(ajax_data, function(data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        wpvivid_login_progress('Your license has been activated successfully');
                        location.reload();
                    }
                    else
                    {
                        wpvivid_lock_login(false,jsonarray.error);
                    }
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_dashboard_output_ajaxerror('check update', textStatus, errorThrown);
                    wpvivid_lock_login(false,error_message);
                });
            }

            function wpvivid_lock_login(lock,error='')
            {
                if(lock)
                {
                    jQuery('#wpvivid_active_btn').css({'pointer-events': 'none', 'opacity': '0.4'});
                    jQuery('#wpvivid_login_box_progress').show();
                    jQuery('#wpvivid_login_error_msg_box').hide();
                }
                else
                {
                    jQuery('#wpvivid_log_progress_text').html('');
                    jQuery('#wpvivid_login_box_progress').hide();
                    jQuery('#wpvivid_active_btn').css({'pointer-events': 'auto', 'opacity': '1'});

                    if(error!=='')
                    {
                        //wpvivid_display_pro_notice('Error', error);
                        jQuery('#wpvivid_login_error_msg_box').show();
                        jQuery('#wpvivid_login_error_msg').html(error);
                    }
                }
            }

            function wpvivid_login_progress(log)
            {
                jQuery('#wpvivid_log_progress_text').html(log);
            }
        </script>
        <?php
    }

    public function logged()
    {
        $dashboard_info = get_option('wpvivid_dashboard_info', array());

        if(empty($dashboard_info))
        {
            ?>
            <p><input id="wpvivid_check_update_plugin" type="button" class="button-primary ud_connectsubmit" value="Check Update"></p>
            <div id="wpvivid_user_info_box_progress" style="display: none">
                <p>
                    <span class="dashicons dashicons-admin-network wpvivid-dashicons-green"></span>
                    <span id="wpvivid_user_info_log_progress_text"></span>
                </p>
            </div>
            <div id="wpvivid_user_info_error_msg_box" style="display: none">
                <p>
                    <span class="dashicons dashicons-info wpvivid-dashicons-grey"></span>
                    <span id="wpvivid_user_info_error_msg"></span>
                </p>
            </div>
            <div style="clear: both;"></div>
            <script>
                jQuery('#wpvivid_check_update_plugin').click(function()
                {
                    wpvivid_check_update_plugin();
                });

                function wpvivid_check_update_plugin()
                {
                    var ajax_data={
                        'action':'wpvivid_staging_check_update',
                    };
                    jQuery('#wpvivid_check_update_plugin').css({'pointer-events': 'none', 'opacity': '0.4'});
                    jQuery('#wpvivid_user_info_log_progress_text').html('Checking Update...');
                    jQuery('#wpvivid_user_info_box_progress').show();
                    jQuery('#wpvivid_user_info_error_msg_box').hide();
                    wpvivid_post_request(ajax_data, function(data)
                    {
                        jQuery('#wpvivid_check_update_plugin').css({'pointer-events': 'auto', 'opacity': '1'});
                        jQuery('#wpvivid_user_info_box_progress').hide();


                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            location.reload();
                        }
                        else
                        {
                            jQuery('#wpvivid_user_info_error_msg_box').show();
                            jQuery('#wpvivid_user_info_error_msg').html(jsonarray.error);
                        }
                    }, function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        jQuery('#wpvivid_check_update_plugin').css({'pointer-events': 'auto', 'opacity': '1'});
                        jQuery('#wpvivid_user_info_box_progress').hide();
                        jQuery('#wpvivid_user_info_log_progress_text').html('');

                        jQuery('#wpvivid_change_btn').css({'pointer-events': 'auto', 'opacity': '1'});
                        var error_message = wpvivid_dashboard_output_ajaxerror('check update', textStatus, errorThrown);
                        alert(error_message);
                    });
                }

                jQuery(document).ready(function ()
                {
                    wpvivid_check_update_plugin();
                });
            </script>
            <?php
        }
        else
        {
            $need_update=false;
            global $wpvivid_staging;
            $version=$wpvivid_staging->updater->get_staging_version();
            if(version_compare($version,WPVIVID_STAGING_VERSION, '>'))
            {
                $need_update=true;
            }

            if($need_update)
            {
                $plugin_basename= plugin_basename( WPVIVID_STAGING_PLUGIN_DIR . 'wpvivid-staging.php' );
                $url=wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $plugin_basename, 'upgrade-plugin_' . $plugin_basename);
                ?>
                <p>
                    <a href="<?php echo $url; ?>">
                        <input type="button" class="button-primary ud_connectsubmit" value="update">
                    </a>
                </p>
                <div id="wpvivid_user_info_box_progress" style="display: none">
                    <p>
                        <span class="dashicons dashicons-admin-network wpvivid-dashicons-green"></span>
                        <span id="wpvivid_user_info_log_progress_text"></span>
                    </p>
                </div>
                <div id="wpvivid_user_info_error_msg_box" style="display: none">
                    <p>
                        <span class="dashicons dashicons-info wpvivid-dashicons-grey"></span>
                        <span id="wpvivid_user_info_error_msg"></span>
                    </p>
                </div>
                <div style="clear: both;"></div>
                <script>
                    jQuery('#wpvivid_dashboard_update').click(function()
                    {
                        wpvivid_dashboard_update();
                    });

                    function wpvivid_dashboard_update()
                    {
                        var ajax_data={
                            'action':'wpvivid_update_staging',
                        };
                        jQuery('#wpvivid_dashboard_update').css({'pointer-events': 'none', 'opacity': '0.4'});
                        jQuery('#wpvivid_user_info_log_progress_text').html('Checking Update...');
                        jQuery('#wpvivid_user_info_box_progress').show();
                        jQuery('#wpvivid_user_info_error_msg_box').hide();
                        wpvivid_post_request(ajax_data, function(data)
                        {
                            jQuery('#wpvivid_dashboard_update').css({'pointer-events': 'auto', 'opacity': '1'});
                            jQuery('#wpvivid_user_info_box_progress').hide();
                            jQuery('#wpvivid_user_info_log_progress_text').html('');

                            var jsonarray = jQuery.parseJSON(data);
                            if (jsonarray.result === 'success')
                            {
                                location.href=jsonarray.url;
                            }
                            else
                            {
                                jQuery('#wpvivid_user_info_error_msg_box').show();
                                jQuery('#wpvivid_user_info_error_msg').html(jsonarray.error);
                            }
                        }, function(XMLHttpRequest, textStatus, errorThrown)
                        {
                            jQuery('#wpvivid_dashboard_update').css({'pointer-events': 'auto', 'opacity': '1'});
                            jQuery('#wpvivid_user_info_box_progress').hide();
                            jQuery('#wpvivid_user_info_log_progress_text').html('');

                            jQuery('#wpvivid_change_btn').css({'pointer-events': 'auto', 'opacity': '1'});
                            var error_message = wpvivid_dashboard_output_ajaxerror('check update', textStatus, errorThrown);
                            alert(error_message);
                        });
                    }
                </script>
                <?php
            }
            else
            {
                ?>
                <p><input id="wpvivid_check_update_plugin" type="button" class="button-primary ud_connectsubmit" value="Check Update"></p>
                <div id="wpvivid_user_info_box_progress" style="display: none">
                    <p>
                        <span class="dashicons dashicons-admin-network wpvivid-dashicons-green"></span>
                        <span id="wpvivid_user_info_log_progress_text"></span>
                    </p>
                </div>
                <div id="wpvivid_user_info_error_msg_box" style="display: none">
                    <p>
                        <span class="dashicons dashicons-info wpvivid-dashicons-grey"></span>
                        <span id="wpvivid_user_info_error_msg"></span>
                    </p>
                </div>
                <div style="clear: both;"></div>
                <script>
                    jQuery('#wpvivid_check_update_plugin').click(function()
                    {
                        wpvivid_check_update_plugin();
                    });

                    function wpvivid_check_update_plugin($slug)
                    {
                        var ajax_data={
                            'action':'wpvivid_staging_check_update',
                        };
                        jQuery('#wpvivid_check_update_plugin').css({'pointer-events': 'none', 'opacity': '0.4'});
                        jQuery('#wpvivid_user_info_log_progress_text').html('Checking Update...');
                        jQuery('#wpvivid_user_info_box_progress').show();
                        jQuery('#wpvivid_user_info_error_msg_box').hide();
                        wpvivid_post_request(ajax_data, function(data)
                        {
                            jQuery('#wpvivid_check_update_plugin').css({'pointer-events': 'auto', 'opacity': '1'});
                            jQuery('#wpvivid_user_info_box_progress').hide();
                            jQuery('#wpvivid_user_info_log_progress_text').html('');

                            var jsonarray = jQuery.parseJSON(data);
                            if (jsonarray.result === 'success')
                            {
                                location.reload();
                            }
                            else
                            {
                                jQuery('#wpvivid_user_info_error_msg_box').show();
                                jQuery('#wpvivid_user_info_error_msg').html(jsonarray.error);
                            }
                        }, function(XMLHttpRequest, textStatus, errorThrown)
                        {
                            jQuery('#wpvivid_check_update_plugin').css({'pointer-events': 'auto', 'opacity': '1'});
                            jQuery('#wpvivid_user_info_box_progress').hide();
                            jQuery('#wpvivid_user_info_log_progress_text').html('');

                            jQuery('#wpvivid_change_btn').css({'pointer-events': 'auto', 'opacity': '1'});
                            var error_message = wpvivid_dashboard_output_ajaxerror('check update', textStatus, errorThrown);
                            alert(error_message);
                        });
                    }
                </script>
                <?php
            }
        }


    }

    public function handle_server_error($error)
    {
        if(isset($error['error_code']))
        {
            if($error['error_code']==109||$error['error_code']==108||$error['error_code']==107)
            {
                delete_option('wpvivid_pro_user');
                delete_option('wpvivid_pro_addons_cache');
            }
        }

        update_option('wpvivid_connect_server_last_error',$error['error']);
    }

    public function dashboard_login()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try
        {
            if(isset($_POST['license']))
            {
                if(empty($_POST['license']))
                {
                    $ret['result']='failed';
                    $ret['error']='A license is required.';
                    echo json_encode($ret);
                    die();
                }

                $license=$_POST['license'];
            }
            else
            {
                $ret['result']='failed';
                $ret['error']='Retrieving user information failed. Please try again later.';
                echo json_encode($ret);
                die();
            }


            $server=new WPvivid_Staging_Connect_server();

            $ret=$server->login($license,true);
            if($ret['result']=='success')
            {
                global $wpvivid_staging;
                if($wpvivid_staging->updater->get_staging_active($ret['status']))
                {
                    if($ret['status']['check_active'])
                    {
                        $info['token']=$ret['user_info'];
                        update_option('wpvivid_pro_user',$info);
                        update_option('wpvivid_dashboard_info',$ret['status']);
                        update_option('wpvivid_last_update_time',time());
                        update_option('wpvivid_last_login_time',time());
                        $result['result']='success';
                        $result['need_active']=false;
                    }
                    else
                    {
                        $result['result']='success';
                        $result['need_active']=true;
                    }
                }
                else
                {
                    $result['result']='failed';
                    $result['error']='No permission to use staging.';
                }
            }
            else
            {
                $result=$ret;
            }

            echo json_encode($result);
        }
        catch (Exception $e)
        {
            $ret['result']='failed';
            $ret['error']= $e->getMessage();
            echo json_encode($ret);
        }

        die();
    }

    public function dashboard_active()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try
        {
            if(isset($_POST['license']))
            {
                if(empty($_POST['license']))
                {
                    $ret['result']='failed';
                    $ret['error']='A license is required.';
                    echo json_encode($ret);
                    die();
                }

                $license=$_POST['license'];
            }
            else
            {
                $ret['result']='failed';
                $ret['error']='Retrieving user information failed. Please try again later.';
                echo json_encode($ret);
                die();
            }


            $server=new WPvivid_Staging_Connect_server();

            $ret=$server->active_site($license,true);
            if($ret['result']=='success')
            {
                $info['token']=$ret['user_info'];
                update_option('wpvivid_pro_user',$info);
                update_option('wpvivid_dashboard_info',$ret['status']);
                update_option('wpvivid_last_update_time',time());
                update_option('wpvivid_last_login_time',time());
                $result['result']='success';
                $result['need_active']=false;
            }
            else
            {
                $result=$ret;
            }

            echo json_encode($result);
        }
        catch (Exception $e)
        {
            $ret['result']='failed';
            $ret['error']= $e->getMessage();
            echo json_encode($ret);
        }

        die();
    }

    public function check_update_plugin()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try
        {
            $info= get_option('wpvivid_pro_user',false);

            if($info===false)
            {
                $ret['result']='failed';
                $ret['error']='need login.';
                echo json_encode($ret);
                die();
            }

            $user_info=$info['token'];

            $server=new WPvivid_Staging_Connect_server();
            $ret=$server->login($user_info,false);

            if($ret['result']=='success')
            {
                global $wpvivid_staging;
                if($wpvivid_staging->updater->get_staging_active($ret['status']))
                {
                    if($ret['status']['check_active'])
                    {
                        update_option('wpvivid_dashboard_info',$ret['status']);
                        $wpvivid_staging->updater->update_site_transient_update_plugins();
                    }
                    else
                    {
                        delete_option('wpvivid_pro_user');
                        delete_option('wpvivid_dashboard_info');
                    }
                }
                else
                {
                    $result['result']='failed';
                    $result['error']='No permission to use staging.';
                }
            }
            else
            {
                $this->handle_server_error($ret);
            }

            echo json_encode($ret);
        }
        catch (Exception $e)
        {
            $ret['result']='failed';
            $ret['error']= $e->getMessage();
            echo json_encode($ret);
        }

        die();
    }

    public function update_staging()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try
        {
            $info= get_option('wpvivid_pro_user',false);

            if($info===false)
            {
                $ret['result']='failed';
                $ret['error']='need login.';
                echo json_encode($ret);
                die();
            }

            $ret['result']='success';

            $plugin_basename= plugin_basename( WPVIVID_STAGING_PLUGIN_DIR . 'wpvivid-staging.php' );
            $url=wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $plugin_basename, 'upgrade-plugin_' . $plugin_basename);

            $ret['url']=$url;

            echo json_encode($ret);
        }
        catch (Exception $e)
        {
            $ret['result']='failed';
            $ret['error']= $e->getMessage();
            echo json_encode($ret);
        }

        die();
    }

}