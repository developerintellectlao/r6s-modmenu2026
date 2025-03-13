<?php
if (!defined('WPVIVID_STAGING_PLUGIN_DIR'))
{
    die;
}

if ( ! class_exists( 'WP_List_Table' ) )
{
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class WPvivid_Staging_Create_New_WP
{
    public $main_tab;

    public function __construct()
    {

    }

    public function output_page()
    {
        ?>
        <div class="wrap" style="max-width:1720px;">
            <h1>
                <?php
                $plugin_display_name = 'WPvivid Staging';
                _e($plugin_display_name);
                ?>
            </h1>
            <?php
            if(!class_exists('WPvivid_Tab_Page_Container_Ex'))
                include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-tab-page-container-ex.php';
            $this->main_tab=new WPvivid_Tab_Page_Container_Ex();

            $this->main_tab->add_tab('Create New WP','newwp',array($this, 'output_create_wp_page'));
            $this->main_tab->display();
            ?>
        </div>
        <?php
    }

    public function output_create_wp_page()
    {
        $options=get_option('wpvivid_staging_options',array());
        if(isset( $options['staging_request_timeout']))
        {
            $request_timeout=$options['staging_request_timeout'];
        }
        else
        {
            $request_timeout=100;
        }

        update_option('wpvivid_current_running_staging_task','');
        update_option('wpvivid_staging_task_cancel', false);

        global $wpvivid_staging;
        $wpvivid_staging->option->update_option('wpvivid_staging_push_running', '0');

        $home_url   = home_url();
        $admin_url  = admin_url();
        $admin_name = basename($admin_url);
        $admin_name = trim($admin_name, '/');

        $home_path = get_home_path();
        $staging_num = 1;
        $staging_dir = 'myfreshinstall01';
        $staging_content_dir = 'myfreshinstall01';
        $default_staging_site = 'myfreshinstall01';
        while(1){
            $default_staging_site = 'myfreshinstall'.sprintf("%02d", $staging_num);
            $staging_dir = $home_path.$default_staging_site;
            if(!file_exists($staging_dir)){
                break;
            }
            $staging_num++;
        }

        $content_dir = WP_CONTENT_DIR;
        $content_dir = str_replace('\\','/',$content_dir);
        $content_path = $content_dir.'/';
        $staging_num = 1;
        $default_content_staging_site='myfreshinstall01';
        while(1){
            $default_content_staging_site = 'myfreshinstall'.sprintf("%02d", $staging_num);
            $staging_dir = $content_path.$default_content_staging_site;
            if(!file_exists($staging_dir)){
                break;
            }
            $staging_num++;
        }

        global $wpdb;
        $prefix='';
        $site_id=1;
        $base_prefix=$wpdb->base_prefix;
        while(1)
        {
            if($site_id<10)
            {
                $prefix='wpvividfresh0'.$site_id.'_';
            }
            else
            {
                $prefix='wpvividfresh'.$site_id.'_';
            }

            $sql=$wpdb->prepare("SHOW TABLES LIKE %s;", $wpdb->esc_like($prefix) . '%');
            $result = $wpdb->get_results($sql, OBJECT_K);
            if(empty($result))
            {
                break;
            }
            $site_id++;
        }
        $themes_plugins_descript = 'The activated plugins and themes will be copied to a fresh site by default. A Child theme must be copied if it exists.';
        ?>
        <div class="postbox quickbackup-addon">
            <div>
                <div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">
                    <img src="<?php echo esc_url(WPVIVID_STAGING_PLUGIN_URL.'includes/images/Fresh-tab.png'); ?>" style="width:50px;height:50px;">
                </div>
                <div class="wpvivid-element-space-bottom">
                    <div class="wpvivid-text-space-bottom">
                        <?php _e( 'This tab allows you to create a fresh WordPress install in a subfolder of the current site. You can choose which plugins and themes to install.', 'wpvivid' ); ?>
                    </div>
                    <div class="wpvivid-text-space-bottom">
                        <?php _e( '* This feature is in Beta, please <a href="https://wpvivid.com/submit-ticket">contact us</a> if you have any issues using it.', 'wpvivid' ); ?>
                    </div>
                </div>
            </div><div style="clear: both;"></div>

            <div id="wpvivid_create_new_wp_content">
                <div class="wpvivid-element-space-bottom">
                    <table class="wp-list-table widefat plugins" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td class="column-primary" style="border-bottom:1px solid #f1f1f1;background-color:#f1f1f1;" colspan="2">
                                    <span><strong>Choose which directory to install the fresh site</strong></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="column-description desc" colspan="2" style="padding: 0 10px;">
                                    <div style="padding: 10px 0;">
                                        <fieldset>
                                            <div class="wpvivid-element-space-bottom">
                                                <label>
                                                    <span id="wpvivid_create_new_wp_path"><?php echo $home_url.'/'; ?></span>
                                                    <input type="text" option="create_wp" name="path" placeholder="<?php esc_attr_e($default_staging_site); ?>" value="<?php esc_attr_e($default_staging_site); ?>" onkeyup="value=value.replace(/[^a-zA-Z0-9]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z0-9]/g,'')" />
                                                </label>
                                            </div>
                                            <div style="margin: auto;">
                                                <div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">
                                                    <label>
                                                        <input type="radio" option="create_wp" name="choose_create_staging_dir" value="0" checked />
                                                        <span>Install the fresh site to the root directory of the current site</span>
                                                    </label>
                                                </div>
                                                <div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">
                                                    <label>
                                                        <input type="radio" option="create_wp" name="choose_create_staging_dir" value="1" />
                                                        <span>Install the fresh site to the wp-content directory of the current site</span>
                                                    </label>
                                                </div>
                                                <div style="clear: both;"></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="column-primary" style="border-bottom:1px solid #f1f1f1;background-color:#f1f1f1;" colspan="2">
                                    <span><strong>Choose a database for the fresh site</strong></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="column-description desc" colspan="2" style="padding: 0 10px;">
                                    <div style="padding: 10px 0;">
                                        <fieldset>
                                            <div class="wpvivid-element-space-bottom">
                                                <label>
                                                    Table Prefix
                                                    <input type="text" option="create_wp" name="prefix" placeholder="<?php esc_attr_e($prefix); ?>" value="<?php esc_attr_e($prefix); ?>" onkeyup="value=value.replace(/[^a-zA-Z0-9-_]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z0-9-_]/g,'')" title="Table Prefix">
                                                </label>
                                            </div>
                                            <div class="wpvivid-element-space-bottom" id="wpvivid_additional_database_account_new" style="padding:10px;border:1px solid #f1f1f1; border-radius:10px; display: none;">
                                                <form>
                                                    <label><input type="text" option="create_wp" name="database-name" autocomplete="off" placeholder="Database" title="Database Name"></label>
                                                    <label><input type="text" option="create_wp" name="database-user" autocomplete="off" placeholder="Username" title="Database Username"></label>
                                                    <label><input type="password" option="create_wp" name="database-pass" autocomplete="off" placeholder="Password" title="The Password of the Database Username"></label>
                                                    <label><input type="text" option="create_wp" name="database-host" autocomplete="off" placeholder="localhost" title="Database Host"></label>
                                                    <label><input type="button" onclick="wpvivid_additional_database_connect_test_ex();" value="Test Connection"></label>
                                                </form>
                                            </div>
                                            <div style="margin: auto;">
                                                <div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">
                                                    <label>
                                                        <input type="radio" option="create_wp" name="choose_create_staging_db" value="0" checked />
                                                        <span>Share the same database with your live site (recommended)</span>
                                                    </label>
                                                </div>
                                                <div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">
                                                    <label>
                                                        <input type="radio" option="create_wp" name="choose_create_staging_db" value="1" />
                                                        <span>Install the fresh site to another database</span>
                                                    </label>
                                                </div>
                                                <div style="clear: both;"></div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="column-primary" style="border-bottom:1px solid #f1f1f1;background-color:#f1f1f1;" colspan="2">
                                    <span><strong>Choose What to Copy to The Fresh Site</strong></span>
                                </td>
                            </tr>
                            <tr style="cursor:pointer">
                                <td class="plugin-title column-primary wpvivid-backup-to-font wpvivid-handle-themes-plugins-detail">Themes and Plugins</td>
                                <td class="column-description desc wpvivid-handle-themes-plugins-detail themes-plugins-desc">
                                    <?php _e($themes_plugins_descript); ?>
                                </td>
                            </tr>
                            <tr class="wpvivid-custom-detail wpvivid-themes-plugins-detail">
                                <td colspan="3" class="plugin-title column-primary wpvivid-custom-themes-plugins-info">
                                    <?php
                                    $html=$this->output_themes_plugins_info();
                                    echo $html;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="column-description desc" colspan="2" style="padding: 0 10px;">
                                    <div class="wpvivid-element-space-bottom">
                                        <input type="button" id="wpvivid_create_new_wp" class="button button-primary" value="Create Now" />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="wpvivid_create_new_wp_progress" style="display: none;">
                <div class="wpvivid-element-space-bottom">
                    <input class="button button-primary" type="button" id="wpvivid_staging_cancel" value="Cancel" />
                </div>
                <div class="postbox wpvivid-staging-log wpvivid-element-space-bottom" id="wpvivid_fresh_install_staging_log" style="margin-bottom: 0; word-break: break-all; word-wrap: break-word;"></div>
                <div class="action-progress-bar" style="margin: 10px 0 0 0; !important;">
                    <div class="action-progress-bar-percent" id="wpvivid_fresh_install_staging_progress_bar" style="height:24px;line-height:24px;width:0;">
                        <div style="float: left; margin-left: 4px;">0</div>
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        </div>
        <script>

            var home_url="<?php echo $home_url.'/'; ?>";
            var content_url="<?php echo $home_url.'/wp-content/'; ?>";
            var staging_requet_timeout=<?php echo $request_timeout ?>;

            jQuery('#wpvivid_create_new_wp_content').on("click", '.wpvivid-themes-plugins-table-check', function(){
                if(jQuery(this).prop('checked')){
                    if(jQuery(this).hasClass('wpvivid-themes-table-check'))
                    {
                        jQuery('#wpvivid_create_new_wp_content').find('input:checkbox[option=create_wp][name=Themes]').prop('checked', true);
                    }
                    else if(jQuery(this).hasClass('wpvivid-plugins-table-check'))
                    {
                        jQuery('#wpvivid_create_new_wp_content').find('input:checkbox[option=create_wp][name=Plugins]').prop('checked', true);
                    }
                }
                else
                {
                    if (jQuery(this).hasClass('wpvivid-themes-table-check'))
                    {
                        jQuery('#wpvivid_create_new_wp_content').find('input:checkbox[option=create_wp][name=Themes]').each(function()
                        {
                            if(jQuery(this).is(":disabled"))
                            {

                            }
                            else
                            {
                                jQuery(this).prop('checked', false);
                            }

                        });
                    }
                    else if (jQuery(this).hasClass('wpvivid-plugins-table-check'))
                    {
                        jQuery('#wpvivid_create_new_wp_content').find('input:checkbox[option=create_wp][name=Plugins]').each(function()
                        {
                            if(jQuery(this).is(":disabled"))
                            {

                            }
                            else
                            {
                                jQuery(this).prop('checked', false);
                            }
                        });
                    }
                }
            });

            jQuery('#wpvivid_create_new_wp_content').on("click", 'input:radio[name=choose_create_staging_dir]', function()
            {
                var value = jQuery(this).val();
                if(value === '0')
                {
                    jQuery('#wpvivid_create_new_wp_path').html(home_url);
                }
                else
                {
                    jQuery('#wpvivid_create_new_wp_path').html(content_url);
                }
            });

            jQuery('#wpvivid_create_new_wp_content').on("click", 'input:radio[name=choose_create_staging_db]', function()
            {
                var value = jQuery(this).val();
                if(value === '0')
                {
                    jQuery('#wpvivid_additional_database_account_new').hide();
                }
                else
                {
                    jQuery('#wpvivid_additional_database_account_new').show();
                }
            });

            function wpvivid_additional_database_connect_test_ex()
            {
                var db_user =jQuery('input[option=create_wp][name=database-user]').val();
                var db_pass =jQuery('input[option=create_wp][name=database-pass]').val();
                var db_host =jQuery('input[option=create_wp][name=database-host]').val();
                var db_name =jQuery('input[option=create_wp][name=database-name]').val();
                if(db_name == '')
                {
                    alert('Database Name is required.');
                    return;
                }

                if(db_user == '')
                {
                    alert('Database User is required.');
                    return;
                }

                if(db_pass == '')
                {
                    alert('Database Password is required.');
                    return;
                }

                if(db_host == '')
                {
                    alert('Database Host is required.');
                    return ;
                }

                var db_json = {};
                db_json['db_user'] = db_user;
                db_json['db_pass'] = db_pass;
                db_json['db_host'] = db_host;
                db_json['db_name'] = db_name;
                var db_connect_info = JSON.stringify(db_json);
                var ajax_data = {
                    'action': 'wpvividstg_test_additional_database_connect',
                    'database_info': db_connect_info
                };

                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray !== null)
                        {
                            if (jsonarray.result === 'success')
                            {
                                alert('Connection success.')
                            }
                            else
                            {
                                alert(jsonarray.error);
                            }
                        }
                        else
                        {
                            alert('Connection Failed. Please check the credentials you entered and try again.');
                        }
                    }
                    catch (e)
                    {
                        alert('Connection Failed. Please check the credentials you entered and try again.');
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('connecting database', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            jQuery('#wpvivid_create_new_wp').click(function()
            {
                var descript = 'Click OK to start creating fresh WordPress install.';
                var ret = confirm(descript);
                if(ret === true)
                {
                    wpvivid_create_new_wp();
                }
            });

            function wpvivid_get_custom_create_new_wp_option()
            {
                var json = {};
                json['themes_list'] = Array();
                json['plugins_list'] = Array();
                json['themes_check'] = '0';
                json['plugins_check'] = '0';
                jQuery('input:checkbox[option=create_wp][name=Themes]').each(function()
                {
                    if(jQuery(this).prop('checked'))
                    {
                        json['themes_check'] = '1';
                    }
                    else{
                        json['themes_list'].push(jQuery(this).val());
                    }
                });
                jQuery('input:checkbox[option=create_wp][name=Plugins]').each(function()
                {
                    if(jQuery(this).prop('checked'))
                    {
                        json['plugins_check'] = '1';
                    }
                    else{
                        json['plugins_list'].push(jQuery(this).val());
                    }
                });
                return json;
            }

            function wpvivid_create_new_wp()
            {
                var path=jQuery('input[option=create_wp][name=path]').val();
                if(path=='')
                {
                    alert('A site name is required.');
                    return ;
                }
                var staging_root_dir='0';
                jQuery('input[option=create_wp][name=choose_create_staging_dir]').each(function ()
                {
                    if (jQuery(this).prop('checked'))
                    {
                        staging_root_dir = jQuery(this).val();
                    }
                });

                var table_prefix=jQuery('input[option=create_wp][name=prefix]').val();

                if(table_prefix=='')
                {
                    alert('Table Prefix is required.');
                    return ;
                }

                var additional_database_json = {};

                var additional_database_option = '0';
                jQuery('input[option=create_wp][name=choose_create_staging_db]').each(function ()
                {
                    if (jQuery(this).prop('checked'))
                    {
                        additional_database_option = jQuery(this).val();
                    }
                });

                if (additional_database_option === '1')
                {
                    additional_database_json['additional_database_check'] = '1';
                    additional_database_json['additional_database_info'] = {};
                    additional_database_json['additional_database_info']['db_user'] = jQuery('input[option=create_wp][name=database-user]').val();
                    additional_database_json['additional_database_info']['db_pass'] = jQuery('input[option=create_wp][name=database-pass]').val();
                    additional_database_json['additional_database_info']['db_host'] = jQuery('input[option=create_wp][name=database-host]').val();
                    additional_database_json['additional_database_info']['db_name'] = jQuery('input[option=create_wp][name=database-name]').val();
                    if (additional_database_json['additional_database_info']['db_name'] === '')
                    {
                        alert('Database Name is required.');
                        return;
                    }
                    if (additional_database_json['additional_database_info']['db_user'] === '')
                    {
                        alert('Database User is required.');
                        return;
                    }
                    if (additional_database_json['additional_database_info']['db_host'] === '')
                    {
                        alert('Database Host is required.');
                        return;
                    }
                }
                else {
                    additional_database_json['additional_database_check'] = '0';
                }
                var additional_database_info=JSON.stringify(additional_database_json);
                var custom_dir_json = wpvivid_get_custom_create_new_wp_option();
                var custom_dir = JSON.stringify(custom_dir_json);

                var ajax_data = {
                    'action': 'wpvividstg_start_staging',
                    'create_new_wp':true,
                    'path': path,
                    'table_prefix': table_prefix,
                    'custom_dir': custom_dir,
                    'additional_db': additional_database_info,
                    'root_dir':staging_root_dir
                };


                jQuery('#wpvivid_create_new_wp_content').hide();
                jQuery('#wpvivid_create_new_wp_progress').show();

                wpvivid_post_request(ajax_data, function (data)
                {
                    setTimeout(function ()
                    {
                        wpvivid_get_create_new_wp_progress();
                    }, staging_requet_timeout);
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    jQuery('#wpvivid_create_new_wp_content').hide();
                    jQuery('#wpvivid_create_new_wp_progress').show();
                    setTimeout(function () {
                        wpvivid_get_create_new_wp_progress();
                    }, staging_requet_timeout);
                });
            }

            function wpvivid_restart_create_new_wp()
            {
                var ajax_data = {
                    'action':'wpvividstg_start_staging',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_create_new_wp_progress();
                    }, staging_requet_timeout);
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_create_new_wp_progress();
                    }, staging_requet_timeout);
                });
            }

            function wpvivid_get_create_new_wp_progress()
            {
                var ajax_data = {
                    'action':'wpvividstg_get_staging_progress',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            var log_data = jsonarray.log;
                            jQuery('#wpvivid_fresh_install_staging_log').html("");
                            while (log_data.indexOf('\n') >= 0)
                            {
                                var iLength = log_data.indexOf('\n');
                                var log = log_data.substring(0, iLength);
                                log_data = log_data.substring(iLength + 1);
                                var insert_log = "<div style=\"clear:both;\">" + log + "</div>";
                                jQuery('#wpvivid_fresh_install_staging_log').append(insert_log);
                                var div = jQuery('#wpvivid_fresh_install_staging_log');
                                div[0].scrollTop = div[0].scrollHeight;
                            }
                            jQuery('#wpvivid_fresh_install_staging_progress_bar').css('width', jsonarray.percent + '%');
                            jQuery('#wpvivid_fresh_install_staging_progress_bar').find('div').eq(0).html(jsonarray.percent + '%');
                            if(jsonarray.continue)
                            {
                                if(jsonarray.need_restart)
                                {
                                    wpvivid_restart_create_new_wp();
                                }
                                else
                                {
                                    setTimeout(function()
                                    {
                                        wpvivid_get_create_new_wp_progress();
                                    }, staging_requet_timeout);
                                }
                            }
                            else
                            {
                                if(typeof jsonarray.completed !== 'undefined' && jsonarray.completed)
                                {
                                    jQuery('#wpvivid_staging_cancel').css({'pointer-events': 'auto', 'opacity': '1'});
                                    var percent = 100;
                                    jQuery('#wpvivid_fresh_install_staging_progress_bar').css('width', percent + '%');
                                    jQuery('#wpvivid_fresh_install_staging_progress_bar').find('div').eq(0).html(percent + '%');
                                    setTimeout(function()
                                    {
                                        alert('Creating a fresh WordPress install completed successfully.');
                                        //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-staging', 'wpvividstg-staging'); ?>';
                                        location.reload();
                                    }, 1000);
                                }
                                else if(typeof jsonarray.error !== 'undefined' && jsonarray.error)
                                {
                                    alert(jsonarray.error);
                                    //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-staging', 'wpvividstg-staging'); ?>';
                                    location.reload();
                                }
                                else if(typeof jsonarray.is_cancel !== 'undefined' && jsonarray.is_cancel)
                                {
                                    var staging_site_info = {};
                                    staging_site_info['staging_path'] = jsonarray.staging_path;
                                    staging_site_info['staging_additional_db'] = jsonarray.staging_additional_db;
                                    staging_site_info['staging_additional_db_user'] = jsonarray.staging_additional_db_user;
                                    staging_site_info['staging_additional_db_pass'] = jsonarray.staging_additional_db_pass;
                                    staging_site_info['staging_additional_db_host'] = jsonarray.staging_additional_db_host;
                                    staging_site_info['staging_additional_db_name'] = jsonarray.staging_additional_db_name;
                                    staging_site_info['staging_table_prefix'] = jsonarray.staging_table_prefix;
                                    staging_site_info = JSON.stringify(staging_site_info);
                                    ajax_data = {
                                        'action': 'wpvividstg_delete_cancel_staging_site',
                                        'staging_site_info': staging_site_info
                                    };
                                    wpvivid_post_request(ajax_data, function (data)
                                    {
                                        //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-staging', 'wpvividstg-staging'); ?>';
                                        location.reload();
                                    }, function (XMLHttpRequest, textStatus, errorThrown)
                                    {
                                        var error_message = wpvivid_output_ajaxerror('deleting fresh site', textStatus, errorThrown);
                                        alert(error_message);
                                        //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-staging', 'wpvividstg-staging'); ?>';
                                        location.reload();
                                    });
                                }
                                else{
                                    //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-staging', 'wpvividstg-staging'); ?>';
                                    location.reload();
                                }
                            }
                        }
                        else if (jsonarray.result === 'failed')
                        {
                            jQuery('#wpvivid_create_new_wp_content').show();
                            jQuery('#wpvivid_create_new_wp_progress').hide();
                            alert(jsonarray.error);
                        }
                    }
                    catch(err)
                    {
                        setTimeout(function()
                        {
                            wpvivid_get_create_new_wp_progress();
                        }, 3000);
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_create_new_wp_progress();
                    }, 3000);
                });
            }
        </script>
        <?php
    }

    public function output_themes_plugins_info()
    {
        $themes_path = get_theme_root();
        $has_themes = false;
        $themes_table = '';
        $themes_table_html = '';
        $themes_info = array();

        $themes = wp_get_themes();

        if (!empty($themes))
        {
            $has_themes = true;
        }
        foreach ($themes as $theme)
        {
            $file = $theme->get_stylesheet();
            $parent=$theme->parent();

            $themes_info[$file] = $this->get_theme_plugin_info($themes_path . DIRECTORY_SEPARATOR . $file);
            $themes_info[$file]['parent']=$parent;
            $themes_info[$file]['parent_file']=$theme->get_template();
            $themes_info[$file]['child']=array();
            $current_theme=wp_get_theme();
            if($current_theme->get_stylesheet()==$file)
            {
                $themes_info[$file]['active'] = 1;
            }
            else
            {
                $themes_info[$file]['active'] = 0;
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

        $themes_all_check = 'checked';
        foreach ($themes_info as $file => $info)
        {
            $checked = '';

            if ($info['active'] == 1)
            {
                $checked = 'checked';
            }
            if (empty($checked)) {
                $themes_all_check = '';
            }

            $themes_table .= '<div class="wpvivid-custom-database-table-column">
                                        <label style="width:100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap; padding-top: 3px;"
                                        title="' . esc_html($file) . '|Size:' . size_format($info["size"], 2) . '">
                                        <input type="checkbox" option="create_wp" name="Themes" value="' . esc_attr($file) . '" '. esc_html($checked) .'/>
                                        ' . esc_html($file) . '|Size:' . size_format($info["size"], 2) . '</label></div>';
        }
        $themes_table .= '<div style="clear:both;"></div>';
        if ($has_themes)
        {
            $themes_table_html .= '<div class="wpvivid-custom-database-wp-table-header" style="border:1px solid #e5e5e5;">
                                        <label><input type="checkbox" class="wpvivid-themes-plugins-table-check wpvivid-themes-table-check" '. esc_attr($themes_all_check).'/>Themes</label>
                                     </div>
                                     <div class="wpvivid-database-table-addon" style="border:1px solid #e5e5e5; border-top: none; padding: 0 4px 4px 4px; max-height: 300px; overflow-y: auto; overflow-x: hidden;">
                                        ' . $themes_table . '
                                     </div>';
        }
        $html = $themes_table_html;
        $html .= '<div style="clear:both;"></div>';
        $html .= '<div style="margin-bottom: 10px;"></div>';

        $has_plugins = false;
        $plugins_table = '';
        $plugins_table_html = '';
        $path = WP_PLUGIN_DIR;
        $plugin_info = array();

        if (!function_exists('get_plugins'))
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        $plugins = get_plugins();

        if (!empty($plugins))
        {
            $has_plugins = true;
        }
        foreach ($plugins as $key => $plugin)
        {
            $slug = dirname($key);
            if ($slug == '.')
                continue;
            $plugin_info[$slug] = $this->get_theme_plugin_info($path . DIRECTORY_SEPARATOR . $slug);
            $plugin_info[$slug]['Name'] = $plugin['Name'];
            $plugin_info[$slug]['slug'] = $slug;
            if($slug=='wpvivid-staging')
            {
                $plugin_info[$slug]['active'] = 1;
                $plugin_info[$slug]['disable'] = 1;
            }
            else
            {
                $plugin_info[$slug]['active'] = 0;
                $plugin_info[$slug]['disable'] = 0;
            }

        }

        $plugins_all_check='checked';

        foreach ($plugin_info as $slug => $info)
        {
            $disable_check = '';
            if ($info['disable']==1)
            {
                $disable_check = 'disabled';
            }
            $checked = '';

            if ($info['active'] == 1)
            {
                $checked = 'checked';
            }

            if (empty($checked)) {
                $plugins_all_check = '';
            }

            $plugins_table .= '<div class="wpvivid-custom-database-table-column">
                                        <label style="width:100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap; padding-top: 3px;" 
                                        title="' . esc_html($info['Name']) . '|Size:' . size_format($info["size"], 2) . '">
                                        <input type="checkbox" option="create_wp" name="Plugins" value="' . esc_attr($info['slug']) . '" '.esc_attr($checked.' '.$disable_check) .'/>
                                        ' . esc_html($info['Name']) . '|Size:' . size_format($info["size"], 2) . '</label>
                                    </div>';
        }

        $plugins_table .= '<div style="clear:both;"></div>';
        if ($has_plugins)
        {
            $plugins_table_html .= '<div class="wpvivid-custom-database-other-table-header" style="border:1px solid #e5e5e5;">
                                        <label><input type="checkbox" class="wpvivid-themes-plugins-table-check wpvivid-plugins-table-check" '. esc_attr($plugins_all_check).'/>Plugins</label>
                                     </div>
                                     <div class="wpvivid-database-table-addon" style="border:1px solid #e5e5e5; border-top: none; padding: 0 4px 4px 4px; max-height: 300px; overflow-y: auto; overflow-x: hidden;">
                                        ' . $plugins_table . '
                                     </div>';
        }
        $html .= $plugins_table_html;
        return $html;
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
}