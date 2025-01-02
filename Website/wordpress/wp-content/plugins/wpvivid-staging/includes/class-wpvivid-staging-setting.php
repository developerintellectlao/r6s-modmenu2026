<?php

if (!defined('WPVIVID_STAGING_PLUGIN_DIR'))
{
    die;
}

class WPvivid_Staging_Setting
{
    public $main_tab;

    public function __construct()
    {
        add_action('wp_ajax_wpvividstg_save_setting_ex', array($this, 'save_setting'));

        add_filter('wpvivid_get_staging_screens', array($this, 'get_staging_screens'), 10);
        add_filter('wpvivid_get_staging_menu', array($this, 'get_staging_menu'), 10, 2);
    }

    public function get_staging_screens($screens)
    {
        $screen['menu_slug']='wpvividstg-stgsetting';
        $screen['screen_id']='wpvivid-staging_page_wpvividstg-stgsetting';
        $screen['is_top']=false;
        $screens[]=$screen;
        return $screens;
    }

    public function get_staging_menu($submenus, $parent_slug)
    {
        $submenu['parent_slug']=$parent_slug;
        $submenu['page_title']=__('WPvivid Staging');
        $submenu['menu_title']='Settings';
        $submenu['capability']='administrator';
        $submenu['menu_slug']=strtolower(sprintf('%s-stgsetting', apply_filters('wpvivid_white_label_slug', 'wpvividstg')));//'wpvividstg-setting';
        $submenu['index']=3;
        $submenu['function']=array($this, 'init_setting_page');
        $submenus[]=$submenu;
        return $submenus;
    }

    public function init_setting_page()
    {
        ?>
        <div class="wrap" style="max-width:1720px;">
            <h1><?php esc_attr_e( apply_filters('wpvivid_white_label_display', 'WPvivid').' Staging Pro', 'WpvividPlugins' ); ?></h1>
            <?php
            if(!class_exists('WPvivid_Tab_Page_Container_Ex'))
                include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-tab-page-container-ex.php';
            $this->main_tab=new WPvivid_Tab_Page_Container_Ex();

            $this->main_tab->add_tab('Settings','setting',array($this, 'output_setting'));

            $this->main_tab->display();
            ?>
        </div>
        <?php
    }

    public function output_setting()
    {
        ?>
        <div class="postbox quickbackup-addon">
            <div>
                <div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">
                    <img src="<?php echo esc_url(WPVIVID_STAGING_PLUGIN_URL.'includes/images/settings.png'); ?>" style="width:50px;height:50px;">
                </div>
                <div class="wpvivid-element-space-bottom">
                    <div class="wpvivid-text-space-bottom" style="margin-bottom: 0;"><?php echo sprintf(__('The settings page of %s Staging plugin.', 'wpvivid'), apply_filters('wpvivid_white_label_display', 'WPvivid')); ?></div>
                </div>
                <div style="clear: both;"></div>
            </div>
            <?php
            if(!class_exists('WPvivid_Tab_Page_Container_Ex'))
                include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-tab-page-container-ex.php';
            $this->main_tab=new WPvivid_Tab_Page_Container_Ex();

            $tabs=array();
            $tab['title']='Staging Settings';
            $tab['slug']='staging';
            $tab['callback']= array($this, 'output_staging_setting');
            $args['is_parent_tab']=0;
            $args['transparency']=1;
            $tab['args']=$args;
            $tabs[]=$tab;
            foreach ($tabs as $tab)
            {
                $this->main_tab->add_tab($tab['title'], $tab['slug'], $tab['callback'], $tab['args']);
            }

            $this->main_tab->display();
            ?>
            <!--<div><input class="button-primary" id="wpvivid_setting_general_save" type="submit" value="<?php esc_attr_e( 'Save Changes', 'wpvivid' ); ?>" /></div>-->
        </div>
        <script>
            function switch_setting_tab(id)
            {
                jQuery( document ).trigger( '<?php echo $this->main_tab->container_id ?>-show',id);
            }
            jQuery(document).ready(function($)
            {
                <?php
                if(isset($_REQUEST['tabs']))
                {
                ?>
                switch_setting_tab('<?php echo $_REQUEST['tabs'];?>');
                <?php
                }
                ?>
            });
        </script>
        <?php
    }

    public function output_staging_setting()
    {
        ?>
        <div style="margin-top: 10px;">
            <?php
            $this->wpvivid_setting_add_staging_cell_addon();
            ?>
        </div>
        <?php
    }

    public function wpvivid_setting_add_staging_cell_addon()
    {
        $options=get_option('wpvivid_staging_options',array());

        $staging_db_insert_count   = isset($options['staging_db_insert_count']) ? $options['staging_db_insert_count'] : WPVIVID_STAGING_DB_INSERT_COUNT_EX;
        $staging_db_replace_count  = isset($options['staging_db_replace_count']) ? $options['staging_db_replace_count'] : WPVIVID_STAGING_DB_REPLACE_COUNT_EX;
        $staging_file_copy_count   = isset($options['staging_file_copy_count']) ? $options['staging_file_copy_count'] : WPVIVID_STAGING_FILE_COPY_COUNT_EX;
        $staging_exclude_file_size = isset($options['staging_exclude_file_size']) ? $options['staging_exclude_file_size'] : WPVIVID_STAGING_MAX_FILE_SIZE_EX;
        $staging_memory_limit      = isset($options['staging_memory_limit']) ? $options['staging_memory_limit'] : WPVIVID_STAGING_MEMORY_LIMIT_EX;
        $staging_memory_limit      = str_replace('M', '', $staging_memory_limit);
        $staging_max_execution_time= isset($options['staging_max_execution_time']) ? $options['staging_max_execution_time'] : WPVIVID_STAGING_MAX_EXECUTION_TIME_EX;
        $staging_resume_count      = isset($options['staging_resume_count']) ? $options['staging_resume_count'] : WPVIVID_STAGING_RESUME_COUNT_EX;
        $staging_request_timeout      = isset($options['staging_request_timeout']) ? $options['staging_request_timeout'] : WPVIVID_STAGING_DELAY_BETWEEN_REQUESTS;

        $staging_keep_setting      = isset($options['staging_keep_setting']) ? $options['staging_keep_setting'] : true;


        $staging_not_need_login=isset($options['not_need_login']) ? $options['not_need_login'] : true;
        if($staging_not_need_login)
        {
            $staging_not_need_login_check='checked';
        }
        else
        {
            $staging_not_need_login_check='';
        }
        $staging_overwrite_permalink = isset($options['staging_overwrite_permalink']) ? $options['staging_overwrite_permalink'] : true;
        if($staging_overwrite_permalink){
            $staging_overwrite_permalink_check = 'checked';
        }
        else{
            $staging_overwrite_permalink_check = '';
        }

        if($staging_keep_setting)
        {
            $staging_keep_setting='checked';
        }
        else
        {
            $staging_keep_setting='';
        }

        /*$force_files_mode      = isset($options['force_files_mode']) ? $options['force_files_mode'] : false;
        if($force_files_mode)
        {
            $force_files_mode='checked';
        }
        else
        {
            $force_files_mode='';
        }*/

        $file_permission_755 = 'checked';
        $file_permission_644 = '';
        $file_permission_775 = '';
        if(isset($options['files_permission']))
        {
            if($options['files_permission'] === '0755')
            {
                $file_permission_755 = 'checked';
                $file_permission_644 = '';
                $file_permission_775 = '';
            }
            else if($options['files_permission'] === '0644')
            {
                $file_permission_755 = '';
                $file_permission_644 = 'checked';
                $file_permission_775 = '';
            }
            else if($options['files_permission'] === '0775')
            {
                $file_permission_755 = '';
                $file_permission_644 = '';
                $file_permission_775 = 'checked';
            }
            else
            {
                $file_permission_755 = 'checked';
                $file_permission_644 = '';
                $file_permission_775 = '';
            }
        }
        else if(isset($options['force_files_mode']))
        {
            $file_permission_755 = '';
            $file_permission_644 = 'checked';
            $file_permission_775 = '';
        }
        else
        {
            $file_permission_755 = 'checked';
            $file_permission_644 = '';
            $file_permission_775 = '';
        }

        ?>
        <div class="postbox schedule-tab-block wpvivid-setting-addon" style="margin-bottom: 10px; padding-bottom: 0;">
            <div class="wpvivid-element-space-bottom"><strong><?php _e('DB Copy Count', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <input type="text" class="all-options" option="setting" name="staging_db_insert_count" value="<?php esc_attr_e($staging_db_insert_count); ?>"
                       placeholder="10000" onkeyup="value=value.replace(/\D/g,'')" />
            </div>
            <div class="wpvivid-element-space-bottom">
                <?php _e( 'Number of DB rows, that are copied within one ajax query. The higher value makes the database copy process faster. 
                Please try a high value to find out the highest possible value. If you encounter timeout errors, try lower values until no 
                more errors occur.', 'wpvivid' ); ?>
            </div>

            <div class="wpvivid-element-space-bottom"><strong><?php _e('DB Replace Count', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <input type="text" class="all-options" option="setting" name="staging_db_replace_count" value="<?php esc_attr_e($staging_db_replace_count); ?>"
                       placeholder="5000" onkeyup="value=value.replace(/\D/g,'')" />
            </div>
            <div class="wpvivid-element-space-bottom">
                <?php _e( 'Number of DB rows, that are processed within one ajax query. The higher value makes the DB replacement process faster. 
                If timeout erros occur, decrease the value because this process consumes a lot of memory.', 'wpvivid' ); ?>
            </div>

            <div class="wpvivid-element-space-bottom"><strong><?php _e('File Copy Count', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <input type="text" class="all-options" option="setting" name="staging_file_copy_count" value="<?php esc_attr_e($staging_file_copy_count); ?>"
                       placeholder="500" onkeyup="value=value.replace(/\D/g,'')" />
            </div>
            <div class="wpvivid-element-space-bottom">
                <?php _e( 'Number of files to copy that will be copied within one ajax request. The higher value makes the file file copy process faster. 
                Please try a high value to find out the highest possible value. If you encounter timeout errors, try lower values until no more errors occur.', 'wpvivid' ); ?>
            </div>

            <div class="wpvivid-element-space-bottom"><strong><?php _e('Max File Size', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <input type="text" class="all-options" option="setting" name="staging_exclude_file_size" value="<?php esc_attr_e($staging_exclude_file_size); ?>"
                       placeholder="30" onkeyup="value=value.replace(/\D/g,'')" />MB
            </div>
            <div class="wpvivid-element-space-bottom">
                <?php _e( 'Maximum size of the files copied to a staging site. All files larger than this value will be ignored. If you set the value of 0 MB, all files will be copied to a staging site.', 'wpvivid' ); ?>
            </div>

            <div class="wpvivid-element-space-bottom"><strong><?php _e('Staging Memory Limit', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <input type="text" class="all-options" option="setting" name="staging_memory_limit" value="<?php esc_attr_e($staging_memory_limit); ?>"
                       placeholder="256" onkeyup="value=value.replace(/\D/g,'')" />MB
            </div>
            <div class="wpvivid-element-space-bottom">
                <?php _e('Adjust this value to apply for a temporary PHP memory limit for the plugin to create a staging site. 
                We set this value to 256M by default. Increase the value if you encounter a memory exhausted error. Note: some 
                web hosting providers may not support this.', 'wpvivid'); ?>
            </div>

            <div class="wpvivid-element-space-bottom"><strong><?php _e('PHP Script Execution Timeout', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <input type="text" class="all-options" option="setting" name="staging_max_execution_time" value="<?php esc_attr_e($staging_max_execution_time); ?>"
                       placeholder="900" onkeyup="value=value.replace(/\D/g,'')" />
            </div>
            <div class="wpvivid-element-space-bottom">
                <?php _e( 'The time-out is not your server PHP time-out. With the execution time exhausted, our plugin will shut down the progress of 
                creating a staging site. If the progress  encounters a time-out, that means you have a medium or large sized website. Please try to 
                scale the value bigger.', 'wpvivid' ); ?>
            </div>

            <div class="wpvivid-element-space-bottom"><strong><?php _e('Delay Between Requests', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <input type="text" class="all-options" option="setting" name="staging_request_timeout" value="<?php esc_attr_e($staging_request_timeout); ?>"
                       placeholder="1500" onkeyup="value=value.replace(/\D/g,'')" />ms
            </div>
            <div class="wpvivid-element-space-bottom">
                <?php _e( 'A lower value will help speed up the process of creating a staging site. However, if your server has a limit on the number of requests, a higher value is recommended.', 'wpvivid' ); ?>
            </div>

            <div class="wpvivid-element-space-bottom">
                <strong>Retrying </strong>
                <select option="setting" name="staging_resume_count">
                    <?php
                    for($resume_count=3; $resume_count<10; $resume_count++){
                        if($resume_count === $staging_resume_count){
                            _e('<option selected="selected" value="'.$resume_count.'">'.$resume_count.'</option>');
                        }
                        else{
                            _e('<option value="'.$resume_count.'">'.$resume_count.'</option>');
                        }
                    }
                    ?>
                </select><strong><?php _e(' times when encountering a time-out error', 'wpvivid'); ?></strong>
            </div>

            <div class="wpvivid-element-space-bottom">
                <label>
                    <input type="checkbox" option="setting" name="not_need_login" <?php esc_attr_e($staging_not_need_login_check); ?> />
                    <span><strong><?php _e('Anyone can visit the staging site', 'wpvivid'); ?></strong></span>
                </label>
            </div>

            <div class="wpvivid-element-space-bottom">
                <span>When the option is checked, anyone will be able to visit the staging site without the need to login. Uncheck it to request a login to visit the staging site.</span>
            </div>

            <div class="wpvivid-element-space-bottom">
                <label>
                    <input type="checkbox" option="setting" name="staging_overwrite_permalink" <?php esc_attr_e($staging_overwrite_permalink_check); ?> />
                    <span><strong><?php _e('Keep permalink when transferring website', 'wpvivid'); ?></strong></span>
                </label>
            </div>

            <div class="wpvivid-element-space-bottom">
                <span>When checked, this option allows you to keep the current permalink structure when you create a staging site or push a staging site to live.</span>
            </div>

            <div class="wpvivid-element-space-bottom">
                <label>
                    <input type="checkbox" option="setting" name="staging_keep_setting" <?php esc_attr_e($staging_keep_setting); ?> />
                    <span><strong><?php _e('Keep staging sites when deleting the plugin', 'wpvivid'); ?></strong></span>
                </label>
            </div>

            <div class="wpvivid-element-space-bottom">
                <span>With this option checked, all staging sites you have created will be retained when the plugin is deleted, just in case you still need them later. The sites will show up again after the plugin is reinstalled.</span>
            </div>

            <?php
            global $wpvivid_staging;
            $data=$wpvivid_staging->get_staging_site_data();
            if($data===false)
            {
                $staging_show_push      = isset($options['staging_show_push']) ? $options['staging_show_push'] : true;
                if($staging_show_push)
                {
                    $staging_show_push='checked ';
                }
                else
                {
                    $staging_show_push='';
                }
                ?>
                <div class="wpvivid-element-space-bottom">
                    <label>
                        <input type="checkbox" option="setting" name="staging_show_push" <?php esc_attr_e($staging_show_push); ?> />
                        <span><strong><?php _e('Show the option \'Push the staging site to live\' on the staging site', 'wpvivid'); ?></strong></span>
                    </label>
                </div>

                <div class="wpvivid-element-space-bottom">
                    <span>When this option is checked, the plugin will offer an option of 'Push the staging site to live' on the staging site, so you can directly push the staging site to live from the staging site. Uncheck it if you want to remove the option from the staging site.</span>
                </div>
                <?php
            }
            do_action('wpvivid_staging_additional_setting');
            ?>

            <div class="wpvivid-element-space-bottom"><strong><?php _e('File Permissions', 'wpvivid'); ?></strong></div>
            <div class="wpvivid-element-space-bottom">
                <label>
                    <input type="radio" option="setting" name="files_permission" value="0755" <?php esc_attr_e($file_permission_755); ?> >
                    <span>Set file permissions of the target site to 755 in staging creation, update, and push.</span>
                </label><br>
                <label>
                    <input type="radio" option="setting" name="files_permission" value="0644" <?php esc_attr_e($file_permission_644); ?> >
                    <span>Set file permissions of the target site to 644 in staging creation, update, and push.</span>
                </label><br>
                <label>
                    <input type="radio" option="setting" name="files_permission" value="0775" <?php esc_attr_e($file_permission_775); ?> >
                    <span>Set file permissions of the target site to 775 in staging creation and update, and to 755 in staging push.</span>
                </label>
            </div>
        </div>
        <div><input class="button-primary wpvividstg_save_setting" type="submit" value="<?php esc_attr_e( 'Save Changes', 'wpvivid' ); ?>" /></div>
        <script>
            jQuery('.wpvividstg_save_setting').click(function()
            {
                wpvividstg_save_setting();
            });

            function wpvividstg_save_setting()
            {
                var setting_data = wpvivid_ajax_data_transfer('setting');
                var ajax_data = {
                    'action': 'wpvividstg_save_setting_ex',
                    'setting': setting_data,
                };
                jQuery('.wpvividstg_save_setting').css({'pointer-events': 'none', 'opacity': '0.4'});
                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);

                        jQuery('.wpvividstg_save_setting').css({'pointer-events': 'auto', 'opacity': '1'});
                        if (jsonarray.result === 'success')
                        {
                            //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-stgsetting', 'wpvividstg-stgsetting'); ?>';
                            location.reload();
                        }
                        else {
                            alert(jsonarray.error);
                        }
                    }
                    catch (err)
                    {
                        alert(err);
                        jQuery('.wpvividstg_save_setting').css({'pointer-events': 'auto', 'opacity': '1'});
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown) {
                    jQuery('.wpvividstg_save_setting').css({'pointer-events': 'auto', 'opacity': '1'});
                    var error_message = wpvivid_output_ajaxerror('changing base settings', textStatus, errorThrown);
                    alert(error_message);
                });
            }
        </script>
        <?php
    }

    public function save_setting()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security('manage_options');
        $ret=array();
        try
        {
            if(isset($_POST['setting'])&&!empty($_POST['setting']))
            {
                $json_setting = $_POST['setting'];
                $json_setting = stripslashes($json_setting);
                $setting = json_decode($json_setting, true);
                if (is_null($setting))
                {
                    echo 'json decode failed';
                    die();
                }

                $options=get_option('wpvivid_staging_options',array());

                $options['staging_db_insert_count'] = intval($setting['staging_db_insert_count']);
                $options['staging_db_replace_count'] = intval($setting['staging_db_replace_count']);
                $options['staging_file_copy_count'] = intval($setting['staging_file_copy_count']);
                $options['staging_exclude_file_size'] = intval($setting['staging_exclude_file_size']);
                $options['staging_memory_limit'] = $setting['staging_memory_limit'].'M';
                $options['staging_max_execution_time'] = intval($setting['staging_max_execution_time']);
                $options['staging_resume_count'] = intval($setting['staging_resume_count']);
                $options['not_need_login']= intval($setting['not_need_login']);
                $options['staging_overwrite_permalink'] = intval($setting['staging_overwrite_permalink']);

                $options['staging_request_timeout']= intval($setting['staging_request_timeout']);
                $options['staging_keep_setting']= intval($setting['staging_keep_setting']);

                if(isset($setting['staging_show_push']))
                {
                    $options['staging_show_push']=intval($setting['staging_show_push']);
                }

                $options['files_permission'] = $setting['files_permission'];

                update_option('wpvivid_staging_options',$options);
                $this->update_to_all_staging('wpvivid_staging_options',$options);

                $ret['result']='success';
            }
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
            die();
        }
        echo json_encode($ret);
        die();
    }

    public function update_to_all_staging($option_name,$option_value)
    {
        $option=new WPvivid_Staging_Option();
        $list=$option->get_option('staging_site_data');
        if(!empty($list))
        {
            foreach ($list as $site_data)
            {
                $this->update_to_staging($site_data,$option_name,$option_value);
            }
        }
    }

    public function update_to_staging($site_data,$option_name,$option_value)
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

        $options_table=$prefix.'options';

        $option_value=maybe_serialize($option_value);

        $update_query = $db_des_instance->prepare("INSERT INTO $options_table (option_name,option_value) VALUES (%s, %s) ON DUPLICATE KEY UPDATE option_value=%s", $option_name, $option_value, $option_value);
        return $db_des_instance->get_results($update_query);
    }
}