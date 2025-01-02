<?php

class WPvivid_Staging_Create_UI_Display
{
    public $end_shutdown_function;
    public $task;

    public function __construct()
    {
        add_action('wp_ajax_wpvividstg_start_staging_ex', array($this, 'start_staging'));
        add_action('wp_ajax_wpvividstg_restart_staging', array($this, 'restart_staging'));
        add_action('wp_ajax_wpvividstg_get_staging_progress_ex', array($this, 'get_staging_progress_ex'));

        add_action('wp_ajax_wpvividstg_finish_staging', array($this, 'finish_staging'));
        add_action('wp_ajax_wpvivid_staging_failed', array($this, 'staging_failed'));

        add_action('wp_ajax_wpvividstg_recalc_backup_size_ex', array($this, 'recalc_backup_size_ex'));
        add_action('wp_ajax_wpvividstg_recalc_exclude_file_size', array($this, 'recalc_exclude_file_size'));
        add_action('wp_ajax_wpvividstg_get_need_recalc_website_size', array($this, 'get_need_recalc_website_size'));
        add_action('wp_ajax_wpvividstg_get_time_exceeded_size', array($this, 'get_time_exceeded_size'));
        add_action('wp_ajax_wpvividstg_get_time_exceeded_exclude_file_size', array($this, 'get_time_exceeded_exclude_file_size'));
        //
        //
        $this->end_shutdown_function=true;
        $this->task=false;
    }

    public function recalc_backup_size_ex()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try{
            if(isset($_POST['custom_option'])&&!empty($_POST['custom_option'])&&isset($_POST['website_item'])&&!empty($_POST['website_item']))
            {
                $json = $_POST['custom_option'];
                $json = stripslashes($json);
                $json = json_decode($json, true);
                $website_item = sanitize_key($_POST['website_item']);

                $ret['result']='success';

                if($website_item === 'database')
                {
                    $is_select_db = true;
                    $database_exclude_list = isset($json['custom_dirs']['exclude-tables']) ? $json['custom_dirs']['exclude-tables'] : array();
                    $ret = $this->_get_custom_database_size($is_select_db, $database_exclude_list, true);
                    $options=new WPvivid_Staging_Option();
                    $website_size=$options->get_option('wpvivid_staging_custom_select_website_size_ex');
                    if(empty($website_size))
                        $website_size = array();
                    $website_size['database_size'] = $ret['database_size'];
                    $website_size['calc_time'] = time();
                    $options->update_option('wpvivid_staging_custom_select_website_size_ex', $website_size);
                    $ret['database_size'] = size_format($ret['database_size'], 2);
                }

                if($website_item === 'core')
                {
                    if(!function_exists('get_home_path'))
                        require_once(ABSPATH . 'wp-admin/includes/file.php');
                    $home_path = str_replace('\\','/', get_home_path());

                    $is_select_core = true;

                    $tmp_core_path = str_replace('\\','/', untrailingslashit($home_path).'/');
                    $core_folder_exclude_list = array($tmp_core_path.'wp-admin', $tmp_core_path.'wp-includes');
                    $core_file_exclude_list = array($tmp_core_path.'.htaccess', $tmp_core_path.'index.php', $tmp_core_path.'license.txt', $tmp_core_path.'readme.html', $tmp_core_path.'wp-activate.php',
                        $tmp_core_path.'wp-blog-header.php', $tmp_core_path.'wp-comments-post.php', $tmp_core_path.'wp-config.php', $tmp_core_path.'wp-config-sample.php',
                        $tmp_core_path.'wp-cron.php', $tmp_core_path.'wp-links-opml.php', $tmp_core_path.'wp-load.php', $tmp_core_path.'wp-login.php', $tmp_core_path.'wp-mail.php',
                        $tmp_core_path.'wp-settings.php', $tmp_core_path.'wp-signup.php', $tmp_core_path.'wp-trackback.php', $tmp_core_path.'xmlrpc.php');
                    $core_exclude_list = array();
                    if($is_select_core)
                    {
                        $core_size = self::get_custom_path_size('core', $home_path, $core_folder_exclude_list, $core_file_exclude_list);
                    }
                    else
                    {
                        $core_size = 0;
                    }
                    $options=new WPvivid_Staging_Option();
                    $website_size=$options->get_option('wpvivid_staging_custom_select_website_size_ex');
                    if(empty($website_size))
                        $website_size = array();
                    $website_size['core_size'] = $core_size;
                    $website_size['calc_time'] = time();
                    $options->update_option('wpvivid_staging_custom_select_website_size_ex', $website_size);
                    $ret['core_size'] = size_format($core_size, 2);

                    $core_size=isset($website_size['core_size'])?$website_size['core_size']:0;
                    $content_size=isset($website_size['content_size'])?$website_size['content_size']:0;
                    $themes_size=isset($website_size['themes_size'])?$website_size['themes_size']:0;
                    $plugins_size=isset($website_size['plugins_size'])?$website_size['plugins_size']:0;
                    $uploads_size=isset($website_size['uploads_size'])?$website_size['uploads_size']:0;
                    $ret['total_file_size'] = size_format($core_size+$themes_size+$plugins_size+$uploads_size+$content_size, 2);
                }

                if($website_item === 'content')
                {
                    $content_dir = WP_CONTENT_DIR;
                    $path = str_replace('\\','/',$content_dir);
                    $content_path = $path.'/';

                    $is_select_content = true;

                    $local_setting = get_option('wpvivid_local_setting', array());
                    if(!empty($local_setting))
                    {
                        $content_folder_exclude_list = array($content_path.'plugins', $content_path.'themes', $content_path.'uploads', $content_path.'wpvividbackups', $content_path.$local_setting['path']);
                    }
                    else {
                        $content_folder_exclude_list = array($content_path.'plugins', $content_path.'themes', $content_path.'uploads', $content_path.'wpvividbackups');
                    }
                    $content_file_exclude_list = array();

                    $this->get_exclude_list($json, $website_item, $content_folder_exclude_list, $content_file_exclude_list);

                    if($is_select_content)
                    {
                        $content_size = self::get_custom_path_size('content', $content_path, $content_folder_exclude_list, $content_file_exclude_list);
                    }
                    else
                    {
                        $content_size = 0;
                    }
                    $options=new WPvivid_Staging_Option();
                    $website_size=$options->get_option('wpvivid_staging_custom_select_website_size_ex');
                    if(empty($website_size))
                        $website_size = array();
                    $website_size['content_size'] = $content_size;
                    $website_size['calc_time'] = time();
                    $options->update_option('wpvivid_staging_custom_select_website_size_ex', $website_size);
                    $ret['content_size'] = size_format($content_size, 2);

                    $core_size=isset($website_size['core_size'])?$website_size['core_size']:0;
                    $content_size=isset($website_size['content_size'])?$website_size['content_size']:0;
                    $themes_size=isset($website_size['themes_size'])?$website_size['themes_size']:0;
                    $plugins_size=isset($website_size['plugins_size'])?$website_size['plugins_size']:0;
                    $uploads_size=isset($website_size['uploads_size'])?$website_size['uploads_size']:0;
                    $ret['total_file_size'] = size_format($core_size+$themes_size+$plugins_size+$uploads_size+$content_size, 2);
                }

                if($website_item === 'themes')
                {
                    $themes_path = str_replace('\\','/', get_theme_root());
                    $themes_path = $themes_path.'/';

                    $is_select_themes = true;

                    $themes_folder_exclude_list = array();
                    $themes_file_exclude_list = array();
                    $this->get_exclude_list($json, $website_item, $themes_folder_exclude_list, $themes_file_exclude_list);

                    if($is_select_themes)
                    {
                        $themes_size = self::get_custom_path_size('themes', $themes_path, $themes_folder_exclude_list, $themes_file_exclude_list);
                    }
                    else
                    {
                        $themes_size = 0;
                    }
                    $options=new WPvivid_Staging_Option();
                    $website_size=$options->get_option('wpvivid_staging_custom_select_website_size_ex');
                    if(empty($website_size))
                        $website_size = array();
                    $website_size['themes_size'] = $themes_size;
                    $website_size['calc_time'] = time();
                    $options->update_option('wpvivid_staging_custom_select_website_size_ex', $website_size);
                    $ret['themes_size'] = size_format($themes_size, 2);

                    $core_size=isset($website_size['core_size'])?$website_size['core_size']:0;
                    $content_size=isset($website_size['content_size'])?$website_size['content_size']:0;
                    $themes_size=isset($website_size['themes_size'])?$website_size['themes_size']:0;
                    $plugins_size=isset($website_size['plugins_size'])?$website_size['plugins_size']:0;
                    $uploads_size=isset($website_size['uploads_size'])?$website_size['uploads_size']:0;
                    $ret['total_file_size'] = size_format($core_size+$themes_size+$plugins_size+$uploads_size+$content_size, 2);
                }

                if($website_item === 'plugins')
                {
                    $plugins_path = str_replace('\\','/', WP_PLUGIN_DIR);
                    $plugins_path = $plugins_path.'/';

                    $is_select_plugins = true;

                    $plugins_folder_exclude_list = array();
                    $plugins_file_exclude_list = array();
                    $this->get_exclude_list($json, $website_item, $plugins_folder_exclude_list, $plugins_file_exclude_list);

                    if($is_select_plugins)
                    {
                        $plugins_size = self::get_custom_path_size('plugins', $plugins_path, $plugins_folder_exclude_list, $plugins_file_exclude_list);
                    }
                    else
                    {
                        $plugins_size = 0;
                    }
                    $options=new WPvivid_Staging_Option();
                    $website_size=$options->get_option('wpvivid_staging_custom_select_website_size_ex');
                    if(empty($website_size))
                        $website_size = array();
                    $website_size['plugins_size'] = $plugins_size;
                    $website_size['calc_time'] = time();
                    $options->update_option('wpvivid_staging_custom_select_website_size_ex', $website_size);
                    $ret['plugins_size'] = size_format($plugins_size, 2);

                    $core_size=isset($website_size['core_size'])?$website_size['core_size']:0;
                    $content_size=isset($website_size['content_size'])?$website_size['content_size']:0;
                    $themes_size=isset($website_size['themes_size'])?$website_size['themes_size']:0;
                    $plugins_size=isset($website_size['plugins_size'])?$website_size['plugins_size']:0;
                    $uploads_size=isset($website_size['uploads_size'])?$website_size['uploads_size']:0;
                    $ret['total_file_size'] = size_format($core_size+$themes_size+$plugins_size+$uploads_size+$content_size, 2);
                }

                if($website_item === 'uploads')
                {
                    $upload_dir = wp_upload_dir();
                    $path = $upload_dir['basedir'];
                    $path = str_replace('\\','/',$path);
                    $uploads_path = $path.'/';

                    $is_select_uploads = true;

                    $uploads_folder_exclude_list = array();
                    $uploads_file_exclude_list = array();
                    $this->get_exclude_list($json, $website_item, $uploads_folder_exclude_list, $uploads_file_exclude_list);

                    if($is_select_uploads)
                    {
                        $uploads_size = self::get_custom_path_size('uploads', $uploads_path, $uploads_folder_exclude_list, $uploads_file_exclude_list);
                    }
                    else
                    {
                        $uploads_size = 0;
                    }
                    $options=new WPvivid_Staging_Option();
                    $website_size=$options->get_option('wpvivid_staging_custom_select_website_size_ex');
                    if(empty($website_size))
                        $website_size = array();
                    $website_size['uploads_size'] = $uploads_size;
                    $website_size['calc_time'] = time();
                    $options->update_option('wpvivid_staging_custom_select_website_size_ex', $website_size);
                    $ret['uploads_size'] = size_format($uploads_size, 2);

                    $core_size=isset($website_size['core_size'])?$website_size['core_size']:0;
                    $content_size=isset($website_size['content_size'])?$website_size['content_size']:0;
                    $themes_size=isset($website_size['themes_size'])?$website_size['themes_size']:0;
                    $plugins_size=isset($website_size['plugins_size'])?$website_size['plugins_size']:0;
                    $uploads_size=isset($website_size['uploads_size'])?$website_size['uploads_size']:0;
                    $ret['total_file_size'] = size_format($core_size+$themes_size+$plugins_size+$uploads_size+$content_size, 2);
                }

                echo json_encode($ret);
            }
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function recalc_exclude_file_size()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try{
            if(isset($_POST['custom_option'])&&!empty($_POST['custom_option']))
            {
                $json = $_POST['custom_option'];
                $json = stripslashes($json);
                $json = json_decode($json, true);

                //wp-content
                $content_folder_exclude_list = array();
                $content_file_exclude_list = array();
                $this->get_exclude_list($json, 'content', $content_folder_exclude_list, $content_file_exclude_list);

                //themes
                $themes_folder_exclude_list = array();
                $themes_file_exclude_list = array();
                $this->get_exclude_list($json, 'themes', $themes_folder_exclude_list, $themes_file_exclude_list);

                //plugins
                $plugins_folder_exclude_list = array();
                $plugins_file_exclude_list = array();
                $this->get_exclude_list($json, 'plugins', $plugins_folder_exclude_list, $plugins_file_exclude_list);

                //uploads
                $uploads_folder_exclude_list = array();
                $uploads_file_exclude_list = array();
                $this->get_exclude_list($json, 'uploads', $uploads_folder_exclude_list, $uploads_file_exclude_list);

                $ret['total_file_size'] = 0;
                if(!empty($content_folder_exclude_list))
                {
                    foreach ($content_folder_exclude_list as $content_folder)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($content_folder);
                    }
                }
                if(!empty($content_file_exclude_list))
                {
                    foreach ($content_file_exclude_list as $content_file)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($content_file);
                    }
                }

                if(!empty($themes_folder_exclude_list))
                {
                    foreach ($themes_folder_exclude_list as $themes_folder)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($themes_folder);
                    }
                }
                if(!empty($themes_file_exclude_list))
                {
                    foreach ($themes_file_exclude_list as $themes_file)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($themes_file);
                    }
                }

                if(!empty($plugins_folder_exclude_list))
                {
                    foreach ($plugins_folder_exclude_list as $plugins_folder)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($plugins_folder);
                    }
                }
                if(!empty($plugins_file_exclude_list))
                {
                    foreach ($plugins_file_exclude_list as $plugins_file)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($plugins_file);
                    }
                }

                if(!empty($uploads_folder_exclude_list))
                {
                    foreach ($uploads_folder_exclude_list as $uploads_folder)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($uploads_folder);
                    }
                }
                if(!empty($uploads_file_exclude_list))
                {
                    foreach ($uploads_file_exclude_list as $uploads_file)
                    {
                        $ret['total_file_size'] += $this->get_custom_exclude_file_size($uploads_file);
                    }
                }

                $ret['result']='success';
                $ret['total_file_size'] = size_format($ret['total_file_size'], 2);
                echo json_encode($ret);
            }
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public static function get_custom_exclude_file_size($path, $size=0, &$file_count = 0){
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
                            $size=self::get_custom_exclude_file_size(untrailingslashit($path) . DIRECTORY_SEPARATOR . $filename, $size, $file_count);
                        }
                        else {
                            $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                            $file_count++;
                            if($file_count >= 10000)
                                {
                                $options=new WPvivid_Staging_Option();
                                $exclude_size=$options->get_option('wpvivid_staging_temp_calc_exclude_file_size');
                                if(empty($exclude_size))
                                    $exclude_size = 0;
                                $exclude_size = $size;
                                $options->update_option('wpvivid_staging_temp_calc_exclude_file_size', $exclude_size);
                                $file_count = 0;
                            }
                        }
                    }
                }
                if($handler)
                    @closedir($handler);
            }
        }
        else
        {
            if(file_exists($path))
            {
                $size+=filesize($path);
            }
        }
        return $size;
    }

    public function _get_custom_database_size($is_select_db, $exclude_table_list, $recalc)
    {
        global $wpdb;
        $tables = $wpdb->get_results('SHOW TABLE STATUS', ARRAY_A);
        if (is_null($tables)) {
            $ret['result'] = 'failed';
            $ret['database_size'] = 0;
            $ret['error'] = 'Failed to retrieve the table information for the database. Please try again.';
            return $ret;
        }

        $db_size = 0;

        if($is_select_db)
        {
            $base_table_size = 0;

            foreach ($tables as $row) {
                if($recalc)
                {
                    if(!in_array($row['Name'], $exclude_table_list))
                    {
                        $base_table_size += ($row["Data_length"] + $row["Index_length"]);
                    }
                }
                else
                {
                    global $wpdb;
                    if (is_multisite() && !defined('MULTISITE')) {
                        $prefix = $wpdb->base_prefix;
                    } else {
                        $prefix = $wpdb->get_blog_prefix(0);
                    }
                    if (preg_match('/^(?!' . $prefix . ')/', $row["Name"]) == 1) {
                        if (!empty($exclude_table_list))
                        {
                            if(!in_array($row['Name'], $exclude_table_list))
                            {
                                $base_table_size += ($row["Data_length"] + $row["Index_length"]);
                            }
                        }
                        else
                        {
                            $base_table_size += ($row["Data_length"] + $row["Index_length"]);
                        }
                    }
                    else
                    {
                        if(!in_array($row['Name'], $exclude_table_list))
                        {
                            $base_table_size += ($row["Data_length"] + $row["Index_length"]);
                        }
                    }
                }
            }
        }
        else
        {
            $base_table_size = 0;
        }


        $db_size = $base_table_size;

        $ret['database_size'] = $db_size;
        $ret['result']='success';

        return $ret;
    }

    public function get_exclude_list($data_json, $website_item, &$folder_list, &$file_list)
    {
        if(!empty($data_json))
        {
            if($website_item === 'additional_folder')
            {
                if(isset($data_json['custom_dirs']['other_list']) && !empty($data_json['custom_dirs']['other_list']))
                {
                    $folder_list = $data_json['custom_dirs']['other_list'];
                }
            }
            else
            {
                if(isset($data_json['exclude_files']) && !empty($data_json['exclude_files']))
                {
                    foreach ($data_json['exclude_files'] as $index => $value)
                    {
                        if($index !== $website_item)
                        {
                            continue;
                        }

                        $content_dir = WP_CONTENT_DIR;
                        $path = str_replace('\\','/',$content_dir);
                        $content_path = $path.'/';

                        $upload_dir = wp_upload_dir();
                        $path = $upload_dir['basedir'];
                        $path = str_replace('\\','/',$path);
                        $uploads_path = $path.'/';

                        $themes_path = str_replace('\\','/', get_theme_root());
                        $themes_path = $themes_path.'/';

                        $plugins_path = str_replace('\\','/', WP_PLUGIN_DIR);
                        $plugins_path = $plugins_path.'/';

                        if($website_item === 'content')
                        {
                            foreach ($value as $item)
                            {
                                $exclude_path = $content_path.$this->transfer_path($item['path']);

                                if(preg_match('#'.$content_path.'#', $exclude_path) && !preg_match('#'.$uploads_path.'#', $exclude_path) && !preg_match('#'.$themes_path.'#', $exclude_path) && !preg_match('#'.$plugins_path.'#', $exclude_path))
                                {
                                    if($item['type'] === 'folder')
                                    {
                                        $folder_list[] = $exclude_path;
                                    }
                                    else if($item['type'] === 'file')
                                    {
                                        $file_list[] = $exclude_path;
                                    }
                                }
                            }
                        }
                        else if($website_item === 'uploads')
                        {
                            foreach ($value as $item)
                            {
                                $exclude_path = $uploads_path.$this->transfer_path($item['path']);

                                if(preg_match('#'.$uploads_path.'#', $exclude_path))
                                {
                                    if($item['type'] === 'folder')
                                    {
                                        $folder_list[] = $exclude_path;
                                    }
                                    else if($item['type'] === 'file')
                                    {
                                        $file_list[] = $exclude_path;
                                    }
                                }
                            }
                        }
                        else if($website_item === 'themes')
                        {
                            foreach ($value as $item)
                            {
                                $exclude_path = $themes_path.$this->transfer_path($item['path']);

                                if(preg_match('#'.$themes_path.'#', $exclude_path))
                                {
                                    if($item['type'] === 'folder')
                                    {
                                        $folder_list[] = $exclude_path;
                                    }
                                    else if($item['type'] === 'file')
                                    {
                                        $file_list[] = $exclude_path;
                                    }
                                }
                            }
                        }
                        else if($website_item === 'plugins')
                        {
                            foreach ($value as $item)
                            {
                                $exclude_path = $plugins_path.$this->transfer_path($item['path']);

                                if(preg_match('#'.$plugins_path.'#', $exclude_path))
                                {
                                    if($item['type'] === 'folder')
                                    {
                                        $folder_list[] = $exclude_path;
                                    }
                                    else if($item['type'] === 'file')
                                    {
                                        $file_list[] = $exclude_path;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function get_custom_path_size($type, $path, $folder_exclude_list, $file_exclude_list, $size=0, $file_count = 0){
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
                            if($type === 'core' && $home_path === $path || $type === 'additional')
                            {
                                if(in_array(str_replace('\\','/', untrailingslashit($path) . DIRECTORY_SEPARATOR . $filename), $folder_exclude_list))
                                {
                                    $size=self::get_custom_path_size($type, untrailingslashit($path) . DIRECTORY_SEPARATOR . $filename, $folder_exclude_list, $file_exclude_list, $size, $file_count);
                                }
                            }
                            else
                            {
                                if(!in_array(str_replace('\\','/', untrailingslashit($path) . DIRECTORY_SEPARATOR . $filename), $folder_exclude_list))
                                {
                                    $size=self::get_custom_path_size($type, untrailingslashit($path) . DIRECTORY_SEPARATOR . $filename, $folder_exclude_list, $file_exclude_list, $size, $file_count);
                                }
                            }
                        }
                        else {
                            if($type === 'core' || $type === 'additional')
                            {
                                if($home_path === $path){
                                    if(in_array(str_replace('\\','/', untrailingslashit($path) . DIRECTORY_SEPARATOR . $filename), $file_exclude_list)){
                                        $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                                    }
                                }
                                else{
                                    $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                                }
                            }
                            else
                            {
                                if(!in_array(str_replace('\\','/', untrailingslashit($path) . DIRECTORY_SEPARATOR . $filename), $file_exclude_list)){
                                    $size+=filesize($path . DIRECTORY_SEPARATOR . $filename);
                                }
                            }
                            $file_count++;
                            if($file_count >= 10000)
                            {
                                $options=new WPvivid_Staging_Option();
                                $website_size=$options->get_option('wpvivid_staging_temp_calc_size');
                                if(empty($website_size))
                                    $website_size = array();
                                $website_size[$type] = $size;
                                $options->update_option('wpvivid_staging_temp_calc_size', $website_size);
                                $file_count = 0;
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

    public function get_need_recalc_website_size()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try{
            $ret['result']='success';
            /*
            $need_recalc = true;
            $options=new WPvivid_Staging_Option();
            $website_size=$options->get_option('wpvivid_staging_custom_select_website_size_ex');
            if(empty($website_size))
                $website_size = array();
            if(isset($website_size['calc_time']) && !empty($website_size['calc_time']))
            {
                $calc_time = $website_size['calc_time'];
                $curr_time = time();
                if(($curr_time - $calc_time) > 24 * 60 * 60)
                {
                    $need_recalc = true;
                }
                else{
                    $need_recalc = false;
                }
            }*/
            $ret['need_recalc']=true;
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

    public function get_time_exceeded_size()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try{
            if(isset($_POST['website_item'])&&!empty($_POST['website_item']))
            {
                $ret['result']='success';

                $website_item = sanitize_key($_POST['website_item']);

                $options=new WPvivid_Staging_Option();
                $website_size=$options->get_option('wpvivid_staging_temp_calc_size');
                if(empty($website_size))
                    $website_size = array();
                if(isset($website_size[$website_item]))
                {
                    $ret['curr_size'] = size_format($website_size[$website_item], 2);
                }
                else
                {
                    $ret['curr_size'] = 0;
                }
                echo json_encode($ret);
            }
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
        }
        die();
    }

    public function get_time_exceeded_exclude_file_size()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        try{
            $ret['result']='success';

            $options=new WPvivid_Staging_Option();
            $exclude_size=$options->get_option('wpvivid_staging_temp_calc_exclude_file_size');
            if(empty($exclude_size))
            {
                $ret['curr_size'] = 0;
            }
            else
            {
                $ret['curr_size'] = size_format($exclude_size, 2);
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

    public function finish_staging()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();
        $ret['result']='success';
        echo json_encode($ret);
        $wpvivid_staging->wpvivid_check_clear_litespeed_rule();
        die();
    }

    public function staging_failed()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();

        $options=new WPvivid_Staging_Option();
        $task_option=$options->get_option('wpvivid_staging_task_ex');
        $this->task=new WPvivid_New_Staging_Task($task_option);
        $this->flush();
        $this->task->clean_tmp();
        $wpvivid_staging->wpvivid_check_clear_litespeed_rule();
        die();
    }

    public function deal_shutdown_error()
    {
        if($this->task===false)
            return;

        if($this->end_shutdown_function===false)
        {
            $error = error_get_last();

            if (!is_null($error))
            {

            }
            else
            {
                if(preg_match('/Allowed memory size of.*$/', $error['message']))
                {
                    $ret['result']='failed';
                    $ret['error']=$error['message'];

                    $options=new WPvivid_Staging_Option();
                    $task_option=$options->get_option('wpvivid_staging_task_ex');
                    $task=new WPvivid_New_Staging_Task($task_option);
                    $task->update_task_status(false,'error',false,false,$ret['error']);
                    echo json_encode($ret);
                }
                else if (in_array($error['type'], array(E_ERROR,E_RECOVERABLE_ERROR,E_CORE_ERROR,E_COMPILE_ERROR), true))
                {
                    $ret['result']='failed';
                    $ret['error']=$error['message'];

                    $options=new WPvivid_Staging_Option();
                    $task_option=$options->get_option('wpvivid_staging_task_ex');
                    $task=new WPvivid_New_Staging_Task($task_option);
                    $task->update_task_status(false,'error',false,false,$ret['error']);
                    echo json_encode($ret);
                }
            }

            die();
        }
    }

    public function start_staging()
    {
        global $wpvivid_staging,$wpdb;
        $wpvivid_staging->ajax_check_security();

        if(!isset($_POST['path']) || empty($_POST['path']) || !is_string($_POST['path']))
        {
            $ret['result']='failed';
            $ret['error']='A site path is required.';
            echo json_encode($ret);
            die();
        }

        if(!isset($_POST['table_prefix']) || empty($_POST['table_prefix']) || !is_string($_POST['table_prefix']))
        {
            $ret['result']='failed';
            $ret['error']='A table prefix is required.';
            echo json_encode($ret);
            die();
        }

        $additional_db_json=isset($_POST['additional_db'])?$_POST['additional_db']:'';
        if(empty($additional_db_json))
        {
            $additional_db_options=array();
        }
        else
        {
            $additional_db_json = $_POST['additional_db'];
            $additional_db_json = stripslashes($additional_db_json);
            $additional_db_options = json_decode($additional_db_json, true);
        }

        $json = isset($_POST['custom_dir'])?$_POST['custom_dir']:'';
        $json = stripslashes($json);
        if(empty($json))
        {
            $staging_options = array();
        }
        else
        {
            $staging_options = json_decode($json, true);
        }

        if($additional_db_options['additional_database_check'] === '1')
        {
            update_option('wpvivid_staging_additional_database_history', $additional_db_options['additional_database_info']);
        }

        $src_path = untrailingslashit(ABSPATH);
        $path = sanitize_text_field($_POST['path']);
        $options['table_prefix'] = sanitize_text_field($_POST['table_prefix']);
        $options['is_create_subdomain'] = false;
        $options['root_dir']=isset($_POST['root_dir'])?$_POST['root_dir']:2;
        $options['additional_db_options']=$additional_db_options;

        $options['create_new_wp']=isset($_POST['create_new_wp'])?$_POST['create_new_wp']:false;

        $options['old_site_url']=untrailingslashit($wpvivid_staging->get_database_site_url());
        $options['old_home_url']=untrailingslashit($wpvivid_staging->get_database_home_url());

        if($options['root_dir']==0)
        {
            $url_path=$path;

            $options['new_site_url'] = untrailingslashit($this->get_database_site_url()). '/' . $url_path;
            $options['new_home_url'] = untrailingslashit($this->get_database_home_url()). '/' . $url_path;
            $des_path = untrailingslashit(ABSPATH) . '/' . $path;
        }
        else if ($options['root_dir']==1)
        {
            $url_path=str_replace(ABSPATH,'',WP_CONTENT_DIR).'/' . $path;

            $options['new_site_url'] = untrailingslashit($this->get_database_site_url()). '/' . $url_path;
            $options['new_home_url'] = untrailingslashit($this->get_database_home_url()). '/' . $url_path;

            $des_path = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path;
        }
        else
        {
            $options['new_site_url'] = untrailingslashit($_POST['subdomain']);
            $options['new_home_url'] = untrailingslashit($_POST['subdomain']);
            $des_path = untrailingslashit($path);
            $options['is_create_subdomain'] = true;
        }

        $options['src_path'] = $src_path;
        $options['des_path'] = $des_path;

        if(is_multisite())
        {
            if ($options['root_dir']==2)
            {
                $options['path_current_site']=PATH_CURRENT_SITE;
            }
            else if($options['root_dir']==1)
            {
                $options['path_current_site']=PATH_CURRENT_SITE.'wp-content'.'/'.$path.'/';
            }
            else
            {
                $options['path_current_site']=PATH_CURRENT_SITE.$path.'/';
            }

            $subsites = get_sites();
            foreach ($subsites as $subsite)
            {
                $subsite_id = get_object_vars($subsite)["blog_id"];
                $str=get_object_vars($subsite)["path"];
                if (isset($_POST['root_dir'])&&$_POST['root_dir']==2)
                {
                    $options['mu_site'][$subsite_id]['path_site']=$str;
                }
                else
                {
                    $options['mu_site'][$subsite_id]['path_site']=$options['path_current_site'].substr($str, strlen(PATH_CURRENT_SITE));
                }
                //$option['data']['mu']['site'][$subsite_id]['path_site'] = str_replace(PATH_CURRENT_SITE,PATH_CURRENT_SITE.$path.'/',get_object_vars($subsite)["path"]);
                if(is_main_site($subsite_id))
                {
                    $options['main_site_id']=$subsite_id;
                }
            }
        }

        if(isset($_POST['staging_comment']) && !empty($_POST['staging_comment']))
        {
            $options['staging_comment'] = $_POST['staging_comment'];
        }
        else
        {
            $options['staging_comment']='';
        }

        $options['exclude_regex'] = array();
        $options['exclude_files'] = array();

        if(is_multisite())
        {
            if(isset($_POST['mu_quick_select']))
            {
                if($_POST['mu_quick_select']=='true')
                {
                    $options['is_mu']=true;
                }
                else
                {
                    $options['is_mu']=false;
                }
            }
            else
            {
                $options['is_mu']=false;
            }

            if(isset($_POST['mu_single_select']))
            {
                if($_POST['mu_single_select']=='true')
                {
                    $options['is_mu_single']=true;
                }
                else
                {
                    $options['is_mu_single']=false;
                }
            }
            else
            {
                $options['is_mu_single']=false;
            }

            $options['mu_site_list']=array();
            if( $options['is_mu'])
            {
                $json = $_POST['mu_site_list'];
                $json = stripslashes($json);
                $mu_site_list_json = json_decode($json, true);
                foreach ($mu_site_list_json['mu_site_list'] as $site)
                {
                    $options['mu_site_list'][$site['id']]['tables']=$site['tables'];
                    $options['mu_site_list'][$site['id']]['folders']=$site['folders'];
                }

                $options['all_site']=$mu_site_list_json['all_site'];
            }
            else if($options['is_mu_single'])
            {
                $json = $_POST['mu_single_site'];
                $json = stripslashes($json);
                $mu_single_site = json_decode($json, true);
                $options['mu_single_site_id']=$mu_single_site['id'];
            }
            else
            {
                $options['mu_site_list']=array();
            }
        }

        $options['exclude_tables'] = array();
        $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'hw_blocks';
        $options['exclude_tables'][] =apply_filters('wpvividstg_get_table_prefix', $wpdb->base_prefix).'wpvivid_options';

        if($staging_options['database_check'] == '1')
        {
            foreach ($staging_options['database_list'] as $table)
            {
                $options['exclude_tables'][] = $table;
            }
        }

        $themes_path  = get_theme_root();
        $plugins_path = WP_PLUGIN_DIR;
        $upload_dir   = wp_upload_dir();
        $uploads_path = $upload_dir['basedir'];
        $content_path = WP_CONTENT_DIR;

        if($options['create_new_wp'])
        {
            if(isset($staging_options['themes_list']) && !empty($staging_options['themes_list']))
            {
                foreach ($staging_options['themes_list'] as $name=>$theme)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($themes_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
            }
        }
        else
        {
            if($staging_options['themes_check'] == '1')
            {
                foreach ($staging_options['themes_list'] as $name=>$theme)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($themes_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
            }
        }

        if($options['create_new_wp'])
        {
            if(isset($staging_options['plugins_list']) && !empty($staging_options['plugins_list']))
            {
                foreach ($staging_options['plugins_list'] as $name=>$plugin)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($plugins_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
            }
        }
        else
        {
            if($staging_options['plugins_check'] == '1')
            {
                foreach ($staging_options['plugins_list'] as $name=>$plugin)
                {
                    $options['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($plugins_path.DIRECTORY_SEPARATOR.$name), '/').'#';
                }
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

        $options['setting'] = $this->get_staging_setting();
        $options['staging_options']=$staging_options;

        $json = isset($_POST['additional_option'])?$_POST['additional_option']:'';
        $json = stripslashes($json);
        if(empty($json))
        {
            $additional_options = array();
        }
        else
        {
            $additional_options = json_decode($json, true);
        }
        $options['additional_options']=$additional_options;

        $ret['result']='success';

        $ret=apply_filters('wpvivid_allow_create_new_staging',$ret,$options);
        if($ret['result']!='success')
        {
            echo json_encode($ret);
            die();
        }
        $task = new WPvivid_New_Staging_Task();
        $ret=$task->create_new_task($options);
        $wpvivid_staging->wpvivid_check_add_litespeed_server();
        echo json_encode($ret);

        die();
    }

    private function transfer_path($path)
    {
        $path = str_replace('\\','/',$path);
        $values = explode('/',$path);
        return implode(DIRECTORY_SEPARATOR,$values);
    }

    public function restart_staging()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();
        $this->end_shutdown_function=false;
        register_shutdown_function(array($this,'deal_shutdown_error'));

        $options=new WPvivid_Staging_Option();
        $task_option=$options->get_option('wpvivid_staging_task_ex');
        $this->task=new WPvivid_New_Staging_Task($task_option);

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

    public function get_staging_progress_ex()
    {
        global $wpvivid_staging;
        $wpvivid_staging->ajax_check_security();
        $options=new WPvivid_Staging_Option();
        $task_option=$options->get_option('wpvivid_staging_task_ex');
        $this->task=new WPvivid_New_Staging_Task($task_option);

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

    public function get_staging_setting()
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

    public function wpvivid_generate_path( $length = 8 ) {
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }

    public function output_import_staging_site_page()
    {
        $default=ABSPATH;
        ?>
         <div>
             <div class="wpvivid-one-coloum" style="border:1px solid #f1f1f1;padding-bottom:0em; margin-top:0em;margin-bottom:1em;">
                 <h2 style="padding-left:1em;padding-top:0.6em;background:#f1f1f1;">
                     <span class="dashicons dashicons-cloud wpvivid-dashicons-blue"></span>
                     <span>Enter the absolute path of the website you want to import</span>
                 </h2>
                 <p>
                     <input id="wpvivid_staging_scan_path" type="text" style="width: 400px" placeholder="<?php echo $default?>" value="<?php echo $default?>">
                     <input class="button-primary" id="wpvivid_scan_staging" type="submit" value="Scan this folder">
                 </p>
             </div>
         </div>
        <script>
            jQuery('#wpvivid_scan_staging').click(function()
            {
                wpvivid_scan_exist_staging();
            });
            function wpvivid_scan_exist_staging()
            {
                var path=jQuery("#wpvivid_staging_scan_path").val();
                var ajax_data = {
                    'action':'wpvivid_scan_exist_staging',
                    'path':path
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    try
                    {
                        var json = wpvividstg_decode_response_ex(data);
                        var jsonarray = jQuery.parseJSON(json);

                        if (jsonarray.result === 'success')
                        {
                            location.reload();
                        }
                        else
                        {
                            alert(jsonarray.error);
                        }
                    }
                    catch(err)
                    {
                        alert("error");
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert("timeout");
                });
            }

        </script>
        <?php
    }

    public function output_create_staging_site_page()
    {
        update_option('wpvivid_current_running_staging_task','');
        update_option('wpvivid_staging_task_cancel', false);

        global $wpvivid_staging;
        $wpvivid_staging->option->update_option('wpvivid_staging_push_running', '0');

        $home_url   = $this->get_database_home_url();
        $admin_url  = admin_url();
        $admin_name = basename($admin_url);
        $admin_name = trim($admin_name, '/');

        $home_path = get_home_path();
        $staging_path = $this->wpvivid_generate_path();
        $default_staging_site = $staging_path;
        while(1){
            $staging_dir = $home_path.$default_staging_site;
            if(!file_exists($staging_dir)){
                break;
            }
            $staging_path = $this->wpvivid_generate_path();
            $default_staging_site = $staging_path;
        }

        $content_dir = WP_CONTENT_DIR;
        $content_dir = str_replace('\\','/',$content_dir);
        $content_path = $content_dir.'/';

        $default_content_staging_site = $staging_path;
        while(1){
            $staging_dir = $content_path.$default_content_staging_site;
            if(!file_exists($staging_dir)){
                break;
            }
            $staging_path = $this->wpvivid_generate_path();
            $default_content_staging_site = $staging_path;
        }

        global $wpdb;
        $prefix = $this->wpvivid_generate_path().'_';
        $base_prefix=$wpdb->base_prefix;
        while(1)
        {
            $sql=$wpdb->prepare("SHOW TABLES LIKE %s;", $wpdb->esc_like($prefix) . '%');
            $result = $wpdb->get_results($sql, OBJECT_K);
            if(empty($result))
            {
                break;
            }
            $prefix = $this->wpvivid_generate_path().'_';
        }
        ?>

        <div class="wpvivid-one-coloum" id="wpvivid_create_staging_step1" style="border:1px solid #f1f1f1;padding-bottom:0em; margin-top:0em;margin-bottom:1em;">
            <div class="wpvivid-one-coloum" style="background:#f5f5f5;padding-top:0em;padding-bottom:0em;display: none;">
                <div class="wpvivid-two-col">
                    <p><span class="dashicons dashicons-awards wpvivid-dashicons-blue"></span><span><strong>Site Name: </strong></span><span class="wpvivid-staging-site-name"><?php echo $default_staging_site; ?></span></p>
                    <p><span class="dashicons dashicons-admin-site-alt3 wpvivid-dashicons-blue"></span><span><strong>Database Name: </strong></span><span class="wpvivid-staging-additional-database-name-display"><?php echo DB_NAME; ?></span></p>
                    <p><span class="dashicons dashicons-list-view wpvivid-dashicons-blue"></span><span><strong>Table Prefix: </strong></span><span class="wpvivid-staging-table-prefix-display"><?php echo $prefix; ?></span></p>
                </div>
                <div class="wpvivid-two-col">
                    <!--<p><span class="dashicons dashicons-admin-site-alt3 wpvivid-dashicons-blue"></span><span><strong>Database Name:</strong></span><span>admin06</span></p>-->
                    <p><span class="dashicons dashicons-admin-home wpvivid-dashicons-blue"></span><span><strong>Home URL: </strong></span><span class="wpvivid-staging-home-url"><?php echo $home_url; ?>/</span><span class="wpvivid-staging-site-name"><?php echo $default_staging_site; ?></span></p>
                    <p><span class="dashicons  dashicons-rest-api wpvivid-dashicons-blue"></span><span><strong>Admin URL: </strong></span><span class="wpvivid-staging-home-url"><?php echo $home_url; ?>/</span><span class="wpvivid-staging-site-name"><?php echo $default_staging_site; ?></span><span>/<?php echo $admin_name; ?></span></p>
                </div>
            </div>

            <div>
                <div>
                    <h2 style="padding-left:1em;padding-top:0.6em; background:#f1f1f1;">
                        <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                        <span>Directory to Install the Staging Site</span>
                    </h2>
                    <?php
                    $server_type = $_SERVER['SERVER_SOFTWARE'];
                    if(preg_match('/nginx/i', $server_type))
                    {
                        ?>
                        <div style="border:1px solid #ccc; padding:0 1em;margin-top:1em; border-radius:0.5em;">
                            <p>
                                <span>We detected that your web server is Nginx, please add specific rewrite rules to the Nginx config file for the staging site working properly. <a href="https://docs.wpvivid.com/add-rewrite-rules-to-nginx.html">How to</a></span>
                            <p>
                            <div style="clear:both;"></div>
                        </div>
                        <?php
                    }
                    ?>
                    <p>
                        <label>
                            <input type="radio" name="choose_staging_dir" value="0" checked="checked">
                            <span>website root</span>
                        </label>
                        <label>
                            <input type="radio" name="choose_staging_dir" value="2">
                            <span>subdomain</span>
                        </label>
                        <label>
                            <input type="radio" name="choose_staging_dir" value="1">
                            <span>/wp-content/</span>
                        </label>
                    </p>

                    <div id="wpvivid_staging_path_part" style="border-left: 4px solid #007cba;padding-left:1em;">
                        <p>
                            <input type="text" id="wpvivid_staging_path" placeholder="<?php esc_attr_e($default_staging_site); ?>" value="<?php esc_attr_e($default_staging_site); ?>" onkeyup="value=value.replace(/[^a-zA-Z0-9-]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z0-9-]/g,'')"><span> Custom directory</span>
                        </p>
                        <p>
                            <span class="dashicons dashicons-admin-home wpvivid-dashicons-blue"></span><span>Home Url: </span><span class="wpvivid-staging-home-url"><?php echo $home_url; ?>/</span><span class="wpvivid-staging-site-name"><?php echo $default_staging_site; ?></span>
                            <span style="margin-left:1em;" class="dashicons dashicons-portfolio wpvivid-dashicons-blue"></span><span><strong>Directory:</strong></span>
                            <span><?php echo untrailingslashit(ABSPATH); ?>/</span><span class="wpvivid-staging-site-name"><?php echo $default_staging_site; ?></span>
                        </p>
                    </div>

                    <div id="wpvivid_staging_subdomain_part" style="border-left: 4px solid #007cba;padding-left:1em; display: none;">
                        <p></p>
                        <div>
                            <input style="width:300px;" type="text" id="wpvivid_staging_subdomain" placeholder="e.g. http(s)://dev.yourdomain.com" />
                            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip" style="margin-top:0.25em;">
                                <div class="wpvivid-bottom">
                                    <!-- The content you need -->
                                    <p>Enter an existing subdomain of your live site that you want to install the staging site to. You can usually create one on your hosting panel. E.g., http(s)://dev.yourdomain.com</p>
                                    <i></i> <!-- do not delete this line -->
                                </div>
                            </span>
                        </div>
                        <p></p>
                        <div>
                            <input style="width:300px;" type="text" id="wpvivid_staging_subdomain_path" placeholder="Absolute path, e.g. /var/www/html/dev.example.com/" />
                            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip" style="margin-top:0.25em;">
                                <div class="wpvivid-bottom">
                                    <!-- The content you need -->
                                    <p>An existing absolute path that you have mapped the subdomain to. E.g., /var/www/html/dev.example.com/.</p>
                                    <i></i> <!-- do not delete this line -->
                                </div>
                            </span>
                        </div>

                        <p>
                            <span class="dashicons dashicons-admin-home wpvivid-dashicons-blue"></span><span>Home Url: </span><span class="wpvivid-staging-site-name">*</span>
                            <span style="margin-left:1em;" class="dashicons dashicons-portfolio wpvivid-dashicons-blue"></span><span><strong>Directory:</strong></span><span class="wpvivid-staging-subdomain-path">*</span>
                        </p>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <h2 style="padding-left:1em;padding-top:0.6em;background:#f1f1f1;">
                    <span class="dashicons dashicons-cloud wpvivid-dashicons-blue"></span>
                    <span>Choose Database to Install the Staging Site</span>
                </h2>
                <p>
                    <input type="text" id="wpvivid_staging_table_prefix" placeholder="<?php esc_attr_e($prefix); ?>" value="<?php esc_attr_e($prefix); ?>" onkeyup="value=value.replace(/[^a-zA-Z0-9_]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z0-9_]/g,'')" title="Table Prefix"> Custom Table Prefix
                </p>

                <p>
                    <label>
                        <input type="radio" name="choose_staging_db" value="0" checked="">
                        <span>Install the staging site to the live site's database (Easy setup)</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="radio" name="choose_staging_db" value="1">
                        <span>Install the staging site to a separate database (Expert setup)</span>
                    </label>
                </p>
                <p></p>
            <div class="" id="wpvivid_additional_database_account" style="display: none;">
                <?php
                $additional_database_info = get_option('wpvivid_staging_additional_database_history', array());
                $additional_database_name = isset($additional_database_info['db_name']) ? $additional_database_info['db_name'] : '';
                $additional_database_user = isset($additional_database_info['db_user']) ? $additional_database_info['db_user'] : '';
                $additional_database_pass = isset($additional_database_info['db_pass']) ? $additional_database_info['db_pass'] : '';
                $additional_database_host = isset($additional_database_info['db_host']) ? $additional_database_info['db_host'] : '';
                ?>
                <form>
                    <p><label><input type="text" class="wpvivid-additional-database-name" autocomplete="off" placeholder="DB Name" value="<?php esc_attr_e($additional_database_name); ?>" title="DB Name" readonly></label>
                        <label><input type="text" class="wpvivid-additional-database-user" autocomplete="off" placeholder="DB Username" value="<?php esc_attr_e($additional_database_user); ?>" title="DB Username" readonly></label></p>
                    <p><label><input type="password" class="wpvivid-additional-database-pass" autocomplete="off" placeholder="Password" value="<?php esc_attr_e($additional_database_pass); ?>" title="The Password of the Database Username" readonly></label>
                        <label><input type="text" class="wpvivid-additional-database-host" autocomplete="off" placeholder="localhost" value="<?php esc_attr_e($additional_database_host); ?>" title="Database Host" readonly></label></p>
                    <p><label><input class="button-primary wpvivid_setting_general_save" type="button" id="wpvivid_connect_additional_database" onclick="wpvivid_additional_database_connect_test();" value="Test Connection" readonly></label></p>
                </form>
            </div>
            <div style="clear: both;"></div>
        </div>
        <!--<div class="wpvivid-two-col">
                <h2 style="padding-left:0em;padding-top:1em;"><span class="dashicons dashicons-cloud wpvivid-dashicons-blue"></span><span>Choose Database to Install the Staging Site</span></h2>
                <p><input type="text" id="wpvivid_staging_table_prefix" placeholder="<?php esc_attr_e($prefix); ?>" value="<?php esc_attr_e($prefix); ?>" onkeyup="value=value.replace(/[^a-zA-Z0-9_]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z0-9_]/g,'')" title="Table Prefix"> Custom Table Prefix, By default: <?php echo $prefix; ?></p>

                <p><label><input type="radio" name="choose_staging_db" value="0" checked><span>Share the same database with your live site (recommended)</span></label></p>
                <p><label><input type="radio" name="choose_staging_db" value="1"><span>Install the staging site to another database</span></label></p>
                <p></p>
                <div class="" id="wpvivid_additional_database_account">
                    <form>
                        <p><label><input type="text" class="wpvivid-additional-database-name" autocomplete="off" placeholder="DB Name" title="DB Name" readonly></label>
                            <label><input type="text" class="wpvivid-additional-database-user" autocomplete="off" placeholder="DB Username" title="DB Username" readonly></label></p>
                        <p><label><input type="password" class="wpvivid-additional-database-pass" autocomplete="off" placeholder="Password" title="The Password of the Database Username" readonly></label>
                            <label><input type="text" class="wpvivid-additional-database-host" autocomplete="off" placeholder="localhost" title="Database Host" readonly></label></p>
                        <p><label><input class="button-primary wpvivid_setting_general_save" type="button" id="wpvivid_connect_additional_database" onclick="wpvivid_additional_database_connect_test();" value="Test Connection" readonly></label></p>
                    </form>
                </div>
                <div style="clear: both;"></div>
            </div>

            <div class="wpvivid-two-col">
                <h2 style="padding-left:0em;padding-top:1em;"><span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span><span>Choose Directory to Install the Staging Site</span></h2>
                <div id="wpvivid_staging_subdomain_part" style="display: none;">
                    <p>
                        <input type="text" id="wpvivid_staging_subdomain" placeholder="<?php echo $home_url;?>" /><span> Subdomain name</span>
                    </p>
                    <p>
                        Enter an existing subdomain of your live site that you want to install the staging site to. You can usually create one on your hosting panel.
                        E.g., <strong>http://dev.yourdomain.com</strong>
                    </p>
                    <p>
                        <input type="text" id="wpvivid_staging_subdomain_path" placeholder="/var/www/html/dev/" /><span> Destination path</span>
                    </p>
                    <p>
                        An existing absolute path that you have mapped the subdomain to.
                        E.g., <strong>/var/www/html/dev/staging01</strong>
                    </p>
                </div>
                <div id="wpvivid_staging_path_part">
                    <p>
                        <input type="text" id="wpvivid_staging_path" placeholder="<?php esc_attr_e($default_staging_site); ?>" value="<?php esc_attr_e($default_staging_site); ?>" onkeyup="value=value.replace(/[^a-zA-Z0-9]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z0-9]/g,'')"><span> Custom directory</span>
                    </p>
                </div>

                <p>
                    <label>
                        <input type="radio" name="choose_staging_dir" value="0" checked><span>Install the staging site to <code>root</code> directory of the live site</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="radio" name="choose_staging_dir" value="1"><span>Install the staging site to <code>wp-content</code> directory of the live site</span>
                    </label>
                </p>
                <p>
                    <label>
                        <input type="radio" name="choose_staging_dir" value="2"><span>Install the staging site to a <code>subdomain</code>.</span>
                    </label>
                </p>
                <div style="clear: both;"></div>
            </div>-->
        </div>

        <div id="wpvivid_choose_staging_content" class="wpvivid-one-coloum" style="border:1px solid #f1f1f1;padding-bottom:1em; margin-top:1em;margin-bottom:1em;">
            <h2 style="padding-left:1em;padding-top:0.6em;background:#f1f1f1;">
                <span class="dashicons dashicons-admin-page wpvivid-dashicons-orange"></span>
                <span>Choose What to Copy to The Staging Site</span>
            </h2>
            <p></p>
            <div>
                <?php
                if(is_multisite()) {
                    ?>
                    <div id="wpvividstg_select_backup_content">
                        <fieldset>
                            <div style="margin: auto;">
                                <div class="wpvivid-element-space-right" style="float: left;">
                                    <label>
                                        <input type="radio" name="choose_backup_content" value="2" checked/>
                                        <span>Create a staging site for a single MU subsite <strong>(both subdomain and subdirectory Multisite supported)</strong></span>
                                    </label>
                                </div>
                                <div style="clear: both;"></div>
                                <div class="wpvivid-element-space-right" style="float: left;">
                                    <label>
                                        <input type="radio" name="choose_backup_content" value="0" />
                                        <span>Create a staging site for the entire MU network <strong>(only subdirectory Multisite supported)</strong></span>
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <?php
                    $subsites = get_sites();
                    $list=array();
                    $listex=array();
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
                        $listex[]=$subsite;
                    }
                    $core_descript = 'These are the essential files for creating a staging site.';
                    $db_descript = 'The tables created by WordPress are required for the staging site.';
                    $themes_plugins_descript = 'All the plugins and themes files used by the MU network.';
                    $uploads_descript = 'The folder where images and media files of main site are stored by default.';
                    $contents_descript = 'All the folders under wp-content you want to copy to the staging site, except for the Uploads folder.';
                    ?>
                    <div id="wpvividstg_single_site_backup_content">
                        <div id="wpvivid_mu_single_staging_site_step1">
                            <p>
                                Choose the subsite for which you want to create a staging site.
                                <span style="float: right;margin-bottom: 6px">
                                    <label class="screen-reader-text" for="site-search-input">Search A Subsite:</label>
                                    <input type="search" id="wpvivid-mu-single-site-search-input" name="s" value="">
                                    <input type="submit" id="wpvivid-mu-single-search-submit" class="button" value="Search A Subsite">
                                </span>
                            </p>
                            <div id="wpvivid_mu_single_staging_site_list">
                                <?php
                                $mu_site_list = new WPvivid_Staging_MU_Single_Site_List();
                                $mu_site_list ->set_parent('wpvivid_mu_single_staging_site_list');
                                $mu_site_list->set_list($listex,'mu_single_site');
                                $mu_site_list->prepare_items();
                                $mu_site_list ->display();
                                ?>
                                <br>
                                <div class="wpvivid-element-space-bottom">
                                    <input type="button" id="wpvivid_next_single_site_staging" class="button button-primary" value="Next Step" />
                                </div>
                            </div>
                        </div>

                        <p></p>
                        <div id="wpvivid_mu_single_staging_site_step2">
                            <div id="wpvivid_custom_mu_single_staging_list">
                                <?php
                                $custom_mu_staging_list = new WPvivid_Staging_Custom_MU_Select_List();
                                $custom_mu_staging_list ->set_parent_id('wpvivid_custom_mu_single_staging_list');
                                $custom_mu_staging_list ->set_staging_home_path();
                                $custom_mu_staging_list ->display_rows();
                                $custom_mu_staging_list ->load_js();
                                ?>
                                <br>
                                <div class="wpvivid-element-space-bottom">
                                    <input type="button" id="wpvivid_back_single_site_staging" class="button button-primary" value="Previous Step" />
                                    <input type="button" id="wpvivid_mu_single_create_staging" class="button button-primary" value="Create Now" />
                                </div>
                                <div>Note: Please don't refresh the page while creating a staging site.</div>
                            </div>
                        </div>
                    </div>

                    <div id="wpvividstg_quick_select_backup_content">
                        <p></p>
                        <label class="wpvivid-element-space-bottom" style="width:100%;overflow: hidden;text-overflow: ellipsis;white-space: nowrap; padding-top: 3px;">
                            <input type="checkbox" option="wpvividstg_mu_sites" name="mu_site" value="<?php echo $main_site_id?>" checked disabled/>
                            MU Files and Database
                        </label>
                        <p></p>
                        <div class="wpvivid-element-space-bottom">
                            <div id="wpvivid_custom_mu_staging_list">
                                <?php
                                $custom_mu_staging_list = new WPvivid_Staging_Custom_MU_Select_List();
                                $custom_mu_staging_list ->set_parent_id('wpvivid_custom_mu_staging_list');
                                $custom_mu_staging_list ->set_staging_home_path();
                                $custom_mu_staging_list ->display_rows();
                                $custom_mu_staging_list ->load_js();
                                ?>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                        <p>Select the subsites you wish to copy to the staging site</p>
                        <div style="clear: both;"></div>
                        <p>
                            <label>
                                <input type="checkbox" option="wpvividstg_mu_sites" name="mu_all_site" checked />
                                Select all subsites with their database tables and folders
                            </label>
                            <span style="float: right;margin-bottom: 6px">
                                <label class="screen-reader-text" for="site-search-input">Search A Subsite:</label>
                                <input type="search" id="wpvivid-mu-site-search-input" name="s" value="">
                                <input type="submit" id="wpvivid-mu-search-submit" class="button" value="Search A Subsite">
                            </span>
                        </p>
                        <div id="wpvivid_mu_staging_site_list" style="pointer-events: none; opacity: 0.4;">
                            <?php
                            $mu_site_list = new WPvivid_Staging_MU_Site_List();
                            $mu_site_list ->set_parent('wpvivid_mu_staging_site_list');
                            $mu_site_list->set_list($list,'mu_site');
                            $mu_site_list->prepare_items();
                            $mu_site_list ->display();
                            ?>
                        </div>
                    </div>
                    <?php
                }
                else{
                    ?>
                    <div id="wpvividstg_custom_backup_content">
                        <div id="wpvivid_custom_staging_list">
                            <?php
                            $custom_staging_list = new WPvivid_Staging_Custom_Select_List();
                            $custom_staging_list ->set_parent_id('wpvivid_custom_staging_list');
                            $custom_staging_list ->set_staging_home_path();
                            $custom_staging_list ->display_rows();
                            $custom_staging_list ->load_js();
                            ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div style="clear: both;"></div>
            </div>
        </div>
        <?php
        if(class_exists("WPvivid_Merging_Ex"))
        {
            do_action('wpvivid_staging_additional_options');
        }
        else
        {
            ?>
            <div class="wpvivid-one-coloum" style="border:1px solid #f1f1f1;padding-bottom:0em; margin-top:0em;margin-bottom:1em;">
                <h2 style="padding-left:1em;padding-top:0.6em; background:#f1f1f1;">
                    <span class="dashicons dashicons-migrate wpvivid-dashicons-orange"></span>
                    <span>Database Merging <?php if(apply_filters('wpvivid_show_sidebar',true)){ ?>(Need <a href="https://wpvivid.com/db-merging">Database Merging plugin</a>)</span><?php }  ?>
                </h2>
                <p>
                    <label>
                        <input id="wpvivid_enable_merging" type="checkbox" option="additional_option" name="enable_merging">
                        <span><strong>Set the staging site as a development site for database merging </strong>(optional)</span>
                    </label>
                </p>
            </div>
            <script>
                jQuery("#wpvivid_enable_merging").attr("disabled", true);
            </script>
            <?php
        }
        ?>
        <div>
            <p>
                <span class="dashicons dashicons-welcome-write-blog wpvivid-dashicons-green" style="margin-top:0.2em;"></span>
                <span><strong>Comment the staging</strong>(optional): </span><input type="text" id="wpvivid_set_staging_prefix" placeholder="Mystaging">
            </p>
        </div>
        <div id="wpvivid_create_btn" style="padding:1em 1em 0 0;">
            <?php
            if(is_multisite())
            {
                ?>
                <div id="wpvivid_mu_create_staging_content">
                    <input class="button-primary wpvivid_setting_general_save" id="wpvivid_mu_create_staging" type="submit" value="Create Now" /><span> Note: Please don't refresh the page while creating a staging site.</span>
                </div>
                <?php
            }
            else
            {
                ?>
                <div id="wpvivid_create_staging_content">
                    <input class="button-primary wpvivid_setting_general_save" id="wpvivid_create_staging" type="submit" value="Create Now" /><span> Note: Please don't refresh the page while creating a staging site.</span>
                </div>
                <?php
            }
            ?>
            <div style="padding:1em 1em 0 0;">
                <span>Tips: Please temporarily deactivate all cache, firewall and redirect plugins before creating a staging site to rule out possibilities of unknown failures.</span>
            </div>
        </div>

        <div id="wpvivid_create_staging_step2" style="display: none;">
            <div class="wpvivid-element-space-bottom">
                <input class="button button-primary" type="button" id="wpvivid_staging_cancel" value="Cancel" />
            </div>
            <div class="postbox wpvivid-staging-log wpvivid-element-space-bottom" id="wpvivid_staging_log" style="margin-bottom: 0; word-break: break-all;word-wrap: break-word;"></div>
            <div class="action-progress-bar" style="margin: 10px 0 0 0; !important;">
                <div class="action-progress-bar-percent" id="wpvivid_staging_progress_bar" style="height:24px;line-height:24px;width:0;">
                    <div style="float: left; margin-left: 4px;">0</div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>

        <script>
            <?php
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
            if(is_multisite()){
            ?>
            jQuery('#wpvivid_custom_mu_single_staging_list').find('.wpvivid-wordpress-core').html('WordPress Core');
            jQuery('#wpvivid_custom_mu_single_staging_list').find('.database-desc').html('All the tables that belong to the subsite');
            jQuery('#wpvivid_custom_mu_single_staging_list').find('.themes-plugins-desc').html('All the plugins and themes files used by the MU network. Plugins and themes activated on the subsite will be copied to the staging site by default.');
            jQuery('#wpvivid_custom_mu_single_staging_list').find('.uploads-desc').html('Files under the "uploads" folder that the staging site needs.');

            jQuery('#wpvivid_custom_mu_staging_list').find('.wpvivid-wordpress-core').html('WordPress MU Core');
            jQuery('#wpvivid_custom_staging_list').find('.wpvivid-wordpress-core').html('WordPress MU Core');
            jQuery('#wpvivid_custom_staging_list').find('.database-desc').html('All the tables in the WordPress MU database. The tables created by WordPress MU are required for the staging site. The tables created by themes or plugins are optional.');
            jQuery('#wpvivid_custom_staging_list').find('.themes-plugins-desc').html('All the plugins and themes files used by the MU network. The activated plugins and themes will be copied to the staging site by default. A child theme must be copied if it exists.');
            jQuery('#wpvivid_custom_staging_list').find('.uploads-desc').html('The folder where images and media files of the MU network are stored by default. All files will be copied to the staging site by default. You can exclude folders you do not want to copy.');
            jQuery('#wpvivid_mu_staging_site_list').find('input:checkbox').each(function(){
                jQuery(this).prop('checked', true);
            });
            jQuery('#wpvividstg_quick_select_backup_content').on("click",'input:checkbox[option=wpvividstg_mu_sites][name=mu_all_site]',function()
            {
                if(jQuery('input:checkbox[option=wpvividstg_mu_sites][name=mu_all_site]').prop('checked'))
                {
                    jQuery('#wpvivid_mu_staging_site_list').find('input:checkbox').each(function(){
                        jQuery(this).prop('checked', true);
                    });
                    jQuery('#wpvivid_mu_staging_site_list').css({'pointer-events': 'none', 'opacity': '0.4'});
                }
                else{
                    jQuery('#wpvivid_mu_staging_site_list').find('input:checkbox').each(function(){
                        jQuery(this).prop('checked', false);
                    });
                    jQuery('#wpvivid_mu_staging_site_list').css({'pointer-events': 'auto', 'opacity': '1'});
                }
            });
            <?php
            }
            ?>
            var is_init_staging_create=false;
            function init_staging_create()
            {
                if(is_init_staging_create==false)
                {
                    load_js('wpvivid_custom_staging_list', false, '<?php echo $theme_path; ?>', '<?php echo $plugin_path; ?>',  '<?php echo $upload_path; ?>', '<?php echo $content_path; ?>', '<?php echo $home_path; ?>');
                    load_js('wpvivid_custom_mu_staging_list', false, '<?php echo $theme_path; ?>', '<?php echo $plugin_path; ?>', '<?php echo $upload_path; ?>', '<?php echo $content_path; ?>', '<?php echo $home_path; ?>');
                    load_js('wpvivid_custom_mu_single_staging_list', false, '<?php echo $theme_path; ?>', '<?php echo $plugin_path; ?>', '<?php echo $upload_path; ?>', '<?php echo $content_path; ?>', '<?php echo $home_path; ?>');
                    is_init_staging_create=true;
                }
            }

            jQuery('#wpvivid_create_staging_step1').on("keyup", '#wpvivid_staging_path', function() {
                var value = jQuery('#wpvivid_create_staging_step1').find('input:radio[name=choose_staging_dir]:checked').val();
                if(value === '0')
                {
                    var staging_path = jQuery('#wpvivid_staging_path').val();
                    if(staging_path !== ''){
                        jQuery('.wpvivid-staging-site-name').html(staging_path);
                    }
                    else{
                        jQuery('.wpvivid-staging-site-name').html('*');
                    }
                }
                else if(value === '1')
                {
                    var staging_path = jQuery('#wpvivid_staging_path').val();
                    if(staging_path !== '')
                    {
                        jQuery('.wpvivid-staging-site-name').html('wp-content/'+staging_path);
                    }
                    else{
                        jQuery('.wpvivid-staging-site-name').html('wp-content/*');
                    }
                }
            });

            jQuery('#wpvivid_create_staging_step1').on("keyup", '#wpvivid_staging_subdomain_path', function(){
                var staging_subdomain_path = jQuery('#wpvivid_staging_subdomain_path').val();
                if(staging_subdomain_path !== ''){
                    jQuery('.wpvivid-staging-subdomain-path').html(staging_subdomain_path);
                }
                else{
                    jQuery('.wpvivid-staging-subdomain-path').html('*');
                }
            });

            jQuery('#wpvivid_create_staging_step1').on("click", 'input:radio[name=choose_staging_db]', function(){
                if(jQuery(this).prop('checked')){
                    var value = jQuery(this).val();
                    if(value === '0'){
                        jQuery('#wpvivid_additional_database_account').hide();
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-name').attr('readonly', true);
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-user').attr('readonly', true);
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-pass').attr('readonly', true);
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-host').attr('readonly', true);
                        jQuery('.wpvivid-staging-additional-database-name-display').html('<?php echo DB_NAME; ?>');
                    }
                    else{
                        jQuery('#wpvivid_additional_database_account').show();
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-name').attr('readonly', false);
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-user').attr('readonly', false);
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-pass').attr('readonly', false);
                        jQuery('#wpvivid_additional_database_account').find('.wpvivid-additional-database-host').attr('readonly', false);
                        var additional_db_name = jQuery('.wpvivid-additional-database-name').val();
                        if(additional_db_name !== ''){
                            jQuery('.wpvivid-staging-additional-database-name-display').html(additional_db_name);
                        }
                        else{
                            jQuery('.wpvivid-staging-additional-database-name-display').html('*');
                        }
                        wpvivid_additional_database_table_prefix();
                    }
                }
            });

            var default_staging_site = '<?php echo $default_staging_site; ?>';
            var default_content_staging_site = '<?php echo $default_content_staging_site; ?>';
            var is_mu='<?php echo is_multisite(); ?>';
            jQuery('#wpvivid_create_staging_step1').on("click", 'input:radio[name=choose_staging_dir]', function() {
                if(jQuery(this).prop('checked'))
                {
                    var value = jQuery(this).val();

                    if(value === '0')
                    {
                        jQuery('#wpvivid_staging_subdomain_part').hide();
                        jQuery('.wpvivid-staging-home-url').show();
                        jQuery('#wpvivid_staging_path_part').show();
                        jQuery('#wpvivid_staging_path').val(default_staging_site);
                        var staging_path = jQuery('#wpvivid_staging_path').val();
                        if(staging_path !== '')
                        {
                            jQuery('.wpvivid-staging-site-name').html(staging_path);
                        }
                        else{
                            jQuery('.wpvivid-staging-site-name').html('*');
                        }
                    }
                    else if(value === '1')
                    {
                        jQuery('#wpvivid_staging_subdomain_part').hide();
                        jQuery('.wpvivid-staging-home-url').show();
                        jQuery('#wpvivid_staging_path_part').show();
                        jQuery('#wpvivid_staging_path').val(default_content_staging_site);
                        var staging_path = jQuery('#wpvivid_staging_path').val();
                        if(staging_path !== '')
                        {
                            jQuery('.wpvivid-staging-site-name').html('wp-content/'+staging_path);
                        }
                        else{
                            jQuery('.wpvivid-staging-site-name').html('wp-content/*');
                        }
                    }
                    else{
                        jQuery('#wpvivid_staging_subdomain_part').show();
                        jQuery('.wpvivid-staging-home-url').hide();
                        jQuery('#wpvivid_staging_path_part').hide();
                        jQuery('#wpvivid_staging_path').val('');
                        var staging_path = jQuery('#wpvivid_staging_subdomain').val();
                        if(staging_path !== '')
                        {
                            jQuery('.wpvivid-staging-site-name').html(staging_path);
                        }
                        else{
                            jQuery('.wpvivid-staging-site-name').html('*');
                        }
                    }
                }
            });

            jQuery('#wpvivid_create_staging_step1').on("keyup", '#wpvivid_staging_subdomain', function(){
                var subdomain = jQuery(this).val();
                if(subdomain !== '')
                {
                    jQuery('.wpvivid-staging-site-name').html(subdomain);
                }
                else{
                    jQuery('.wpvivid-staging-site-name').html('*');
                }
            });

            jQuery('#wpvivid_create_staging_step1').on("keyup", '.wpvivid-additional-database-name', function(){
                var additional_db_name = jQuery(this).val();
                if(additional_db_name !== ''){
                    jQuery('.wpvivid-staging-additional-database-name-display').html(additional_db_name);
                }
                else{
                    jQuery('.wpvivid-staging-additional-database-name-display').html('*');
                }
            });

            jQuery('#wpvivid_create_staging_step1').on("keyup", '#wpvivid_staging_table_prefix', function(){
                wpvivid_additional_database_table_prefix();
            });

            function wpvivid_additional_database_table_prefix(){
                var additional_db_prefix = jQuery('#wpvivid_create_staging_step1').find('#wpvivid_staging_table_prefix').val();
                if(additional_db_prefix !== ''){
                    jQuery('#wpvivid_create_staging_step1').find('.wpvivid-staging-table-prefix-display').html(additional_db_prefix);
                }
                else{
                    jQuery('#wpvivid_create_staging_step1').find('.wpvivid-staging-table-prefix-display').html('*');
                }
            }

            jQuery('#wpvivid_create_staging_step2').on("click", '#wpvivid_staging_cancel', function(){
                wpvivid_staging_cancel();
            });

            function wpvivid_staging_cancel(){
                var descript = 'Are you sure you want to cancel the task?';
                var ret = confirm(descript);
                if(ret === true)
                {
                    var ajax_data = {
                        'action': 'wpvividstg_cancel_staging'
                    };
                    jQuery('#wpvivid_staging_cancel').css({'pointer-events': 'none', 'opacity': '0.4'});
                    wpvivid_post_request(ajax_data, function(data)
                    {
                        jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                    }, function(XMLHttpRequest, textStatus, errorThrown)
                    {
                        jQuery('#wpvivid_staging_cancel').css({'pointer-events': 'auto', 'opacity': '1'});
                        var error_message = wpvivid_output_ajaxerror('cancelling the staging', textStatus, errorThrown);
                        alert(error_message);
                    });
                }
            }

            jQuery('#wpvivid_create_staging').click(function() {
                var descript = 'Click OK to start creating the staging site.';
                var ret = confirm(descript);
                if(ret === true){
                    jQuery('#wpvivid_staging_notice').hide();
                    wpvivid_ready_start_staging();
                }
            });

            jQuery('#wpvivid_mu_create_staging').click(function() {
                var descript = 'Click OK to start creating the staging site.';
                var ret = confirm(descript);
                if(ret === true){
                    jQuery('#wpvivid_staging_notice').hide();
                    wpvivid_ready_start_staging();
                }
            });

            jQuery('#wpvivid_mu_single_create_staging').click(function() {
                var descript = 'Click OK to start creating the staging site.';
                var ret = confirm(descript);
                if(ret === true){
                    jQuery('#wpvivid_staging_notice').hide();
                    wpvivid_ready_start_staging();
                }
            });

            function wpvivid_recreate_staging(){
                jQuery('#wpvivid_choose_staging_content').show();
                jQuery('#wpvivid_create_btn').show();
                jQuery('#wpvivid_create_staging_step2').hide();
            }

            function wpvivid_create_custom_json(parent_id){
                var json = {};
                //exclude
                json['exclude_custom'] = '1';
                if(!jQuery('#'+parent_id).find('.wpvivid-custom-exclude-part').prop('checked')){
                    json['exclude_custom'] = '0';
                }

                if(jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked')){
                    json['folder_check_ex'] = '1';
                }
                else{
                    json['folder_check_ex'] = '0';
                }

                if(json['folder_check_ex'] === '0')
                {
                    //core
                    json['core_check'] = '0';
                    json['core_list'] = Array();
                    //themes
                    json['themes_check'] = '0';
                    json['themes_list'] = {};
                    json['themes_extension'] = '';
                    //plugins
                    json['plugins_check'] = '0';
                    json['plugins_list'] = {};
                    json['plugins_extension'] = '';
                    //content
                    json['content_check'] = '0';
                    json['content_list'] = {};
                    json['content_extension'] = '';
                    //uploads
                    json['uploads_check'] = '0';
                    json['uploads_list'] = {};
                    json['upload_extension'] = '';
                    //additional folders/files
                    json['additional_file_check'] = '0';
                    json['additional_file_list'] = {};
                }
                else
                {
                    //core
                    json['core_check'] = '0';
                    json['core_list'] = Array();
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked')){
                        json['core_check'] = '1';
                    }

                    //themes
                    json['themes_check'] = '0';
                    json['themes_list'] = {};
                    json['themes_extension'] = '';
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked')){
                        json['themes_check'] = '1';
                    }
                    if(json['exclude_custom'] == '1'){
                        jQuery('#'+parent_id).find('.wpvivid-custom-exclude-themes-list div').find('span:eq(2)').each(function (){
                            var folder_name = this.innerHTML;
                            json['themes_list'][folder_name] = {};
                            json['themes_list'][folder_name]['name'] = folder_name;
                            var type = jQuery(this).closest('div').attr('type');
                            if(type === 'folder'){
                                json['themes_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                            }
                            else{
                                json['themes_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                            }
                        });
                        json['themes_extension'] = jQuery('#'+parent_id).find('.wpvivid-themes-extension').val();
                    }

                    //plugins
                    json['plugins_check'] = '0';
                    json['plugins_list'] = {};
                    json['plugins_extension'] = '';
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked')){
                        json['plugins_check'] = '1';
                    }
                    if(json['exclude_custom'] == '1'){
                        jQuery('#'+parent_id).find('.wpvivid-custom-exclude-plugins-list div').find('span:eq(2)').each(function (){
                            var folder_name = this.innerHTML;
                            json['plugins_list'][folder_name] = {};
                            json['plugins_list'][folder_name]['name'] = folder_name;
                            var type = jQuery(this).closest('div').attr('type');
                            if(type === 'folder'){
                                json['plugins_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                            }
                            else{
                                json['plugins_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                            }
                        });
                        json['plugins_extension'] = jQuery('#'+parent_id).find('.wpvivid-plugins-extension').val();
                    }

                    //content
                    json['content_check'] = '0';
                    json['content_list'] = {};
                    json['content_extension'] = '';
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked')){
                        json['content_check'] = '1';
                    }
                    if(json['exclude_custom'] == '1'){
                        jQuery('#'+parent_id).find('.wpvivid-custom-exclude-content-list div').find('span:eq(2)').each(function (){
                            var folder_name = this.innerHTML;
                            json['content_list'][folder_name] = {};
                            json['content_list'][folder_name]['name'] = folder_name;
                            var type = jQuery(this).closest('div').attr('type');
                            if(type === 'folder'){
                                json['content_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                            }
                            else{
                                json['content_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                            }
                        });
                        json['content_extension'] = jQuery('#'+parent_id).find('.wpvivid-content-extension').val();
                    }

                    //uploads
                    json['uploads_check'] = '0';
                    json['uploads_list'] = {};
                    json['upload_extension'] = '';
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked')){
                        json['uploads_check'] = '1';
                    }
                    if(json['exclude_custom'] == '1'){
                        jQuery('#'+parent_id).find('.wpvivid-custom-exclude-uploads-list div').find('span:eq(2)').each(function (){
                            var folder_name = this.innerHTML;
                            json['uploads_list'][folder_name] = {};
                            json['uploads_list'][folder_name]['name'] = folder_name;
                            var type = jQuery(this).closest('div').attr('type');
                            if(type === 'folder'){
                                json['uploads_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                            }
                            else{
                                json['uploads_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                            }
                        });
                        json['upload_extension'] = jQuery('#'+parent_id).find('.wpvivid-uploads-extension').val();
                    }

                    //additional folders/files
                    json['additional_file_check'] = '0';
                    json['additional_file_list'] = {};
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                        json['additional_file_check'] = '1';
                    }
                    if(json['exclude_custom'] == '1'){
                        jQuery('#'+parent_id).find('.wpvivid-custom-include-additional-folder-list div').find('span:eq(2)').each(function (){
                            var folder_name = this.innerHTML;
                            json['additional_file_list'][folder_name] = {};
                            json['additional_file_list'][folder_name]['name'] = folder_name;
                            var type = jQuery(this).closest('div').attr('type');
                            if(type === 'folder'){
                                json['additional_file_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                            }
                            else{
                                json['additional_file_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                            }
                        });
                    }
                }

                if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                    json['database_check_ex'] = '1';
                }
                else{
                    json['database_check_ex'] = '0';
                }

                if(json['database_check_ex'] === '0')
                {
                    //database
                    json['database_check'] = '0';
                    json['database_list'] = Array();
                }
                else
                {
                    //database
                    json['database_check'] = '0';
                    json['database_list'] = Array();
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-database-check').prop('checked')){
                        json['database_check'] = '1';
                    }
                    jQuery('#'+parent_id).find('input:checkbox[name=Database]').each(function(index, value){
                        if(!jQuery(value).prop('checked')){
                            json['database_list'].push(jQuery(value).val());
                        }
                    });
                }

                return json;
            }

            function wpvivid_create_staging_lock_unlock(action){
                if(action === 'lock'){
                    jQuery('#wpvivid_create_staging_step1').find('input').attr('disabled', true);
                    jQuery('#wpvivid_staging_list').find('div.wpvivid-delete-staging-site').css({'pointer-events': 'none', 'opacity': '0.4'});
                }
                else{
                    jQuery('#wpvivid_create_staging_step1').find('input').attr('disabled', false);
                    jQuery('#wpvivid_staging_list').find('div.wpvivid-delete-staging-site').css({'pointer-events': 'auto', 'opacity': '1'});
                }
            }

            function wpvivid_check_staging_additional_folder_valid(parent_id){
                var check_status = false;
                if(jQuery('#'+parent_id).find('.wpvivid-custom-additional-file-check').prop('checked')){
                    jQuery('#'+parent_id).find('.wpvivid-custom-include-additional-file-list ul').find('li div:eq(1)').each(function () {
                        check_status = true;
                    });
                    if(check_status === false){
                        alert('Please select at least one item under the additional files/folder option, or deselect the option.');
                    }
                }
                else{
                    check_status = true;
                }
                return check_status;
            }

            function wpvivid_check_backup_option_avail(parent_id, check_database_item)
            {
                var check_status = true;

                //check is backup db or files
                var has_select_db_file = false;
                if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                    has_select_db_file = true;
                    var has_db_item = false;
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-database-check').prop('checked')){
                        has_db_item = true;
                        var has_local_table_item = false;
                        if(!check_database_item){
                            has_local_table_item = true;
                        }
                        jQuery('#'+parent_id).find('input:checkbox[name=Database]').each(function(index, value){
                            if(jQuery(this).prop('checked')){
                                has_local_table_item = true;
                            }
                        });
                        if(!has_local_table_item){
                            check_status = false;
                            alert('Please select at least one database table to copy. Or, deselect the option \'Tables In The Wordpress Database\' under the option \'Database Will Be Copied\'.');
                            return check_status;
                        }
                    }
                    if(!has_db_item){
                        check_status = false;
                        alert('Please tick \'Tables In The Wordpress Database\' under the option \'Database Will Be Copied\'. Or, deselect the option \'Database Will Be Copied\'.');
                        return check_status;
                    }
                }
                if(jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked')){
                    has_select_db_file = true;
                    var has_file_item = false;
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked')){
                        has_file_item = true;
                    }
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked')){
                        has_file_item = true;
                    }
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked')){
                        has_file_item = true;
                    }
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked')){
                        has_file_item = true;
                    }
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked')){
                        has_file_item = true;
                    }
                    if(jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                        has_file_item = true;
                        var has_additional_folder = false;
                        jQuery('#'+parent_id).find('.wpvivid-custom-include-additional-folder-list div').find('span:eq(2)').each(function(){
                            has_additional_folder = true;
                        });
                        if(!has_additional_folder){
                            check_status = false;
                            alert('Please select at least one additional file or folder under the option \'Files/Folders Will Be Copied\', Or, deselect the option \'Additional Files/Folders\'.');
                            return check_status;
                        }
                    }
                    if(!has_file_item){
                        check_status = false;
                        alert('Please select at least one file/folder to copy. Or, deselect the option \'Files/Folders Will Be Copied\'.');
                        return check_status;
                    }
                }
                if(!has_select_db_file){
                    check_status = false;
                    alert('Please select at least one file/folder or database table to copy.');
                    return check_status;
                }

                return check_status;
            }

            function wpvivid_additional_option()
            {
                var json = {};
                var data_type='additional_option';
                jQuery('input:checkbox[option='+data_type+']').each(function() {
                    var value = '0';
                    var key = jQuery(this).prop('name');
                    if(jQuery(this).prop('checked')) {
                        value = '1';
                    }
                    else {
                        value = '0';
                    }
                    json[key]=value;
                });
                jQuery('input:radio[option='+data_type+']').each(function() {
                    if(jQuery(this).prop('checked'))
                    {
                        var key = jQuery(this).prop('name');
                        var value = jQuery(this).prop('value');
                        json[key]=value;
                    }
                });
                jQuery('input:text[option='+data_type+']').each(function(){
                    var obj = {};
                    var key = jQuery(this).prop('name');
                    var value = jQuery(this).val();
                    json[key]=value;
                });
                jQuery('textarea[option='+data_type+']').each(function(){
                    var obj = {};
                    var key = jQuery(this).prop('name');
                    var value = jQuery(this).val();
                    json[key]=value;
                });
                jQuery('input:password[option='+data_type+']').each(function(){
                    var obj = {};
                    var key = jQuery(this).prop('name');
                    var value = jQuery(this).val();
                    json[key]=value;
                });
                jQuery('select[option='+data_type+']').each(function(){
                    var obj = {};
                    var key = jQuery(this).prop('name');
                    var value = jQuery(this).val();
                    json[key]=value;
                });
                return JSON.stringify(json);
            }

            function wpvivid_start_staging(path,subdomain,table_prefix,additional_database_info,staging_root_dir)
            {
                jQuery('#wpvivid_staging_log').html("");
                jQuery('#wpvivid_staging_progress_bar').css('width', '0%');
                jQuery('#wpvivid_staging_progress_bar').find('div').eq(0).html('0%');
                var staging_comment=jQuery('#wpvivid_set_staging_prefix').val();
                var custom_dir_json = wpvivid_create_custom_json('wpvivid_custom_staging_list');
                var custom_dir = JSON.stringify(custom_dir_json);
                var mu_quick_select=false;
                var mu_single_select=false;
                var mu_site_list='';
                var mu_single_site='';
                var check_select = true;
                var additional_option= wpvivid_additional_option();
                if(is_mu)
                {
                    if(jQuery('#wpvividstg_select_backup_content').find('input:radio[name=choose_backup_content][value="0"]').prop('checked'))
                    {
                        custom_dir_json = wpvivid_create_custom_json('wpvivid_custom_mu_staging_list');
                        custom_dir = JSON.stringify(custom_dir_json);
                        mu_quick_select=true;
                        var json = {};
                        //wpvividstg_mu_sites
                        if(jQuery('#wpvividstg_mu_sites').prop('checked'))
                        {

                        }
                        json['mu_site_list']=Array();
                        jQuery('input[name=mu_site][type=checkbox]').each(function(index, value)
                        {
                            if(jQuery(value).prop('checked'))
                            {
                                var subjson = {};
                                subjson['id']=jQuery(value).val();
                                if(jQuery('input:checkbox[name=mu_site_tables][value='+jQuery(value).val()+']').prop('checked'))
                                {
                                    subjson['tables']=1;
                                }
                                else
                                {
                                    subjson['tables']=0;
                                }
                                if(jQuery('input:checkbox[name=mu_site_folders][value='+jQuery(value).val()+']').prop('checked'))
                                {
                                    subjson['folders']=1;
                                }
                                else
                                {
                                    subjson['folders']=0;
                                }
                                json['mu_site_list'].push(subjson);
                            }
                        });

                        if(jQuery('input:checkbox[option=wpvividstg_mu_sites][name=mu_all_site]').prop('checked'))
                        {
                            json['all_site']=1;
                        }
                        else
                        {
                            json['all_site']=0;
                        }
                        mu_site_list= JSON.stringify(json);
                    }
                    else if(jQuery('#wpvividstg_select_backup_content').find('input:radio[name=choose_backup_content][value="2"]').prop('checked'))
                    {
                        custom_dir_json = wpvivid_create_custom_json('wpvivid_custom_mu_single_staging_list');
                        custom_dir = JSON.stringify(custom_dir_json);
                        var json = {};
                        jQuery('input[name=mu_single_site][type=checkbox]').each(function(index, value)
                        {
                            if(jQuery(value).prop('checked'))
                            {
                                json['id']=jQuery(value).val();
                                mu_single_select=true;
                            }
                        });
                        mu_single_site= JSON.stringify(json);
                        if(mu_single_select!=true)
                        {
                            alert('You must select a site before creating staging.');
                            return;
                        }
                    }
                }
                wpvivid_create_staging_lock_unlock('lock');

                var ajax_data = {
                    'action': 'wpvividstg_start_staging_ex',
                    'path': path,
                    'subdomain' : subdomain,
                    'table_prefix': table_prefix,
                    'custom_dir': custom_dir,
                    'mu_quick_select':mu_quick_select,
                    'mu_site_list':mu_site_list,
                    'mu_single_select':mu_single_select,
                    'mu_single_site':mu_single_site,
                    'additional_db': additional_database_info,
                    'root_dir':staging_root_dir,
                    'staging_comment': staging_comment,
                    'additional_option':additional_option
                };

                wpvivid_post_request(ajax_data, function (data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        jQuery('#wpvivid_choose_staging_content').hide();
                        jQuery('#wpvivid_create_btn').hide();
                        jQuery('#wpvivid_create_staging_step2').show();
                        wpvivid_restart_staging_ex();
                    }
                    else
                    {
                        wpvivid_create_staging_lock_unlock('unlock');
                        jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                        jQuery('#wpvivid_choose_staging_content').show();
                        jQuery('#wpvivid_create_btn').show();
                        jQuery('#wpvivid_create_staging_step2').hide();
                        alert(jsonarray.error);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_create_staging_lock_unlock('unlock');
                    jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                    jQuery('#wpvivid_choose_staging_content').show();
                    jQuery('#wpvivid_create_btn').show();
                    jQuery('#wpvivid_create_staging_step2').hide();

                    var error_message = wpvivid_output_ajaxerror('creating staging site', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvividstg_decode_response(res)
            {
                if (res.indexOf("<wpvivid_section>") >= 0)
                {
                    var json = res.substring(
                        res.indexOf("<wpvivid_section>") + 17,
                        res.lastIndexOf("</wpvivid_section>")
                    );
                    return json;
                }
                else
                {
                    return res;
                }

            }

            function wpvivid_get_staging_progress_ex()
            {
                console.log(staging_requet_timeout);
                var ajax_data = {
                    'action':'wpvividstg_get_staging_progress_ex',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    try
                    {
                        var json = wpvividstg_decode_response(data);
                        var jsonarray = jQuery.parseJSON(json);

                        if (jsonarray.result === 'success')
                        {
                            var log_data = jsonarray.log;
                            jQuery('#wpvivid_staging_log').html("");
                            while (log_data.indexOf('\n') >= 0)
                            {
                                var iLength = log_data.indexOf('\n');
                                var log = log_data.substring(0, iLength);
                                log_data = log_data.substring(iLength + 1);
                                var insert_log = "<div style=\"clear:both;\">" + log + "</div>";
                                jQuery('#wpvivid_staging_log').append(insert_log);
                                var div = jQuery('#wpvivid_staging_log');
                                div[0].scrollTop = div[0].scrollHeight;
                            }
                            jQuery('#wpvivid_staging_progress_bar').css('width', jsonarray.percent + '%');
                            jQuery('#wpvivid_staging_progress_bar').find('div').eq(0).html(jsonarray.percent + '%');

                            if(jsonarray.status=='ready')
                            {
                                wpvivid_restart_staging_ex();
                            }
                            else if(jsonarray.status=='completed')
                            {
                                wpvivid_finish_staging();
                            }
                            else if(jsonarray.status=='running')
                            {
                                setTimeout(function(){
                                    wpvivid_get_staging_progress_ex();
                                }, 1000);
                            }
                            else if(jsonarray.status=='no_responds')
                            {
                                setTimeout(function(){
                                    wpvivid_get_staging_progress_ex();
                                }, 1000);
                            }
                        }
                        else
                        {
                            wpvivid_staging_failed(jsonarray.error);
                        }
                    }
                    catch(err){
                        setTimeout(function()
                        {
                            wpvivid_get_staging_progress_ex();
                        }, 3000);
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_staging_progress_ex();
                    }, 3000);
                });
            }

            function wpvivid_finish_staging()
            {
                var ajax_data = {
                    'action':'wpvividstg_finish_staging'
                };
                wpvivid_post_request(ajax_data, function(data)
                {
                    jQuery('#wpvivid_staging_cancel').css({'pointer-events': 'auto', 'opacity': '1'});
                    wpvivid_create_staging_lock_unlock('unlock');
                    jQuery('#wpvivid_create_staging_step2').hide();
                    alert("Creating staging site completed successfully.");
                    location.reload();

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    jQuery('#wpvivid_staging_cancel').css({'pointer-events': 'auto', 'opacity': '1'});
                    wpvivid_create_staging_lock_unlock('unlock');
                    jQuery('#wpvivid_create_staging_step2').hide();
                    alert("Creating staging site completed successfully.");
                    location.reload();
                });
            }

            function wpvivid_staging_failed(error)
            {
                var ajax_data = {
                    'action':'wpvivid_staging_failed'
                };
                wpvivid_post_request(ajax_data, function(data)
                {
                    jQuery('#wpvivid_staging_cancel').css({'pointer-events': 'auto', 'opacity': '1'});
                    jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                    wpvivid_create_staging_lock_unlock('unlock');
                    jQuery('#wpvivid_choose_staging_content').show();
                    jQuery('#wpvivid_create_btn').show();
                    jQuery('#wpvivid_create_staging_step2').hide();
                    alert(error);

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    jQuery('#wpvivid_staging_cancel').css({'pointer-events': 'auto', 'opacity': '1'});
                    jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                    wpvivid_create_staging_lock_unlock('unlock');
                    jQuery('#wpvivid_choose_staging_content').show();
                    jQuery('#wpvivid_create_btn').show();
                    jQuery('#wpvivid_create_staging_step2').hide();
                    alert(error);
                });
            }

            function wpvivid_restart_staging_ex()
            {
                var ajax_data = {
                    'action':'wpvividstg_restart_staging',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_staging_progress_ex();
                    }, 1000);
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_staging_progress_ex();
                    }, 1000);
                });
            }

            function wpvivid_check_staging_dir(path,staging_root_dir,table_prefix,subdomain,additional_database_info)
            {
                jQuery('#wpvivid_create_staging').css({'pointer-events': 'none', 'opacity': '0.4'});
                var ajax_data =
                    {
                        'action': 'wpvividstg_check_staging_dir',
                        'root_dir':staging_root_dir,
                        'path': path,
                        'table_prefix': table_prefix,
                        'subdomain': subdomain,
                        'additional_db': additional_database_info
                    };
                wpvivid_post_request(ajax_data, function (data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'failed')
                    {
                        jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                        alert(jsonarray.error);
                    }
                    else
                    {
                        var ajax_data =
                            {
                                'action': 'wpvividstg_check_filesystem_permissions',
                                'root_dir':staging_root_dir,
                                'path': path
                            };
                        wpvivid_post_request(ajax_data, function (data)
                        {
                            var jsonarray = jQuery.parseJSON(data);
                            if (jsonarray.result === 'failed')
                            {
                                jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                                alert(jsonarray.error);
                            }
                            else
                            {
                                wpvivid_start_staging(path,subdomain,table_prefix,additional_database_info,staging_root_dir);
                            }
                        }, function (XMLHttpRequest, textStatus, errorThrown) {
                            jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                            var error_message = wpvivid_output_ajaxerror('creating staging site', textStatus, errorThrown);
                            alert(error_message);
                        });
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    jQuery('#wpvivid_create_staging').css({'pointer-events': 'auto', 'opacity': '1'});
                    var error_message = wpvivid_output_ajaxerror('creating staging site', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_ready_start_staging()
            {
                var staging_root_dir='0';
                jQuery('#wpvivid_create_staging_step1').find('input:radio[name=choose_staging_dir]').each(function ()
                {
                    if (jQuery(this).prop('checked'))
                    {
                        staging_root_dir = jQuery(this).val();
                    }
                });

                var path='';
                var subdomain='';

                if(staging_root_dir==='0'||staging_root_dir==='1')
                {
                    path=jQuery('#wpvivid_staging_path').val();

                    if(path === '')
                    {
                        alert('A site name is required.');
                        return;
                    }
                }
                else
                {
                    path=jQuery('#wpvivid_staging_subdomain_path').val();
                    subdomain=jQuery('#wpvivid_staging_subdomain').val();

                    if(subdomain === '')
                    {
                        alert('Subdomain is required.');
                        return;
                    }

                    if(path === '')
                    {
                        alert('Please fill in the absolute path that you have pointed the subdomain to.');
                        return;
                    }
                }

                var table_prefix=jQuery('#wpvivid_staging_table_prefix').val();

                if(table_prefix === '')
                {
                    alert('Table Prefix is required.');
                    return;
                }

                var staging_comment=jQuery('#wpvivid_set_staging_prefix').val();

                var additional_database_json = {};

                var additional_database_option = '0';
                jQuery('#wpvivid_create_staging_step1').find('input:radio[name=choose_staging_db]').each(function ()
                {
                    if (jQuery(this).prop('checked')) {
                        additional_database_option = jQuery(this).val();
                    }
                });

                if (additional_database_option === '1')
                {
                    additional_database_json['additional_database_check'] = '1';
                    additional_database_json['additional_database_info'] = {};
                    additional_database_json['additional_database_info']['db_user'] = jQuery('.wpvivid-additional-database-user').val();
                    additional_database_json['additional_database_info']['db_pass'] = jQuery('.wpvivid-additional-database-pass').val();
                    additional_database_json['additional_database_info']['db_host'] = jQuery('.wpvivid-additional-database-host').val();
                    additional_database_json['additional_database_info']['db_name'] = jQuery('.wpvivid-additional-database-name').val();
                    if (additional_database_json['additional_database_info']['db_name'] === '') {
                        alert('Database Name is required.');
                        return;
                    }
                    if (additional_database_json['additional_database_info']['db_user'] === '') {
                        alert('Database User is required.');
                        return;
                    }
                    if (additional_database_json['additional_database_info']['db_host'] === '') {
                        alert('Database Host is required.');
                        return;
                    }
                }
                else {
                    additional_database_json['additional_database_check'] = '0';
                }
                var additional_database_info = JSON.stringify(additional_database_json);

                if(!is_mu)
                {
                    var check_status = wpvivid_check_backup_option_avail('wpvivid_custom_staging_list', true);
                }
                else{
                    var check_status = wpvivid_check_backup_option_avail('wpvivid_custom_mu_staging_list', false);
                }

                if (check_status)
                {
                    wpvivid_check_staging_dir(path,staging_root_dir,table_prefix,subdomain,additional_database_info);
                }
            }

            function wpvivid_additional_database_connect_test(){
                var db_user = jQuery('.wpvivid-additional-database-user').val();
                var db_pass = jQuery('.wpvivid-additional-database-pass').val();
                var db_host = jQuery('.wpvivid-additional-database-host').val();
                var db_name = jQuery('.wpvivid-additional-database-name').val();
                if(db_name !== ''){
                    if(db_user !== ''){
                        if(db_host !== ''){
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
                            jQuery('#wpvivid_connect_additional_database').css({
                                'pointer-events': 'none',
                                'opacity': '0.4'
                            });
                            wpvivid_post_request(ajax_data, function (data) {
                                jQuery('#wpvivid_connect_additional_database').css({
                                    'pointer-events': 'auto',
                                    'opacity': '1'
                                });
                                try {
                                    var jsonarray = jQuery.parseJSON(data);
                                    if (jsonarray !== null) {
                                        if (jsonarray.result === 'success') {
                                            alert('Connection success.')
                                        }
                                        else {
                                            alert(jsonarray.error);
                                        }
                                    }
                                    else {
                                        alert('Connection Failed. Please check the credentials you entered and try again.');
                                    }
                                }
                                catch (e) {
                                    alert('Connection Failed. Please check the credentials you entered and try again.');
                                }
                            }, function (XMLHttpRequest, textStatus, errorThrown) {
                                jQuery('#wpvivid_connect_additional_database').css({
                                    'pointer-events': 'auto',
                                    'opacity': '1'
                                });
                                jQuery(obj).css({'pointer-events': 'auto', 'opacity': '1'});
                                var error_message = wpvivid_output_ajaxerror('connecting database', textStatus, errorThrown);
                                alert(error_message);
                            });
                        }
                        else{
                            alert('Database Host is required.');
                        }
                    }
                    else{
                        alert('Database User is required.');
                    }
                }
                else{
                    alert('Database Name is required.');
                }
            }

            jQuery('#wpvivid_mu_staging_site_list').on("click",'.first-page',function() {
                wpvivid_get_mu_list('first');
            });

            jQuery('#wpvivid_mu_staging_site_list').on("click",'.prev-page',function() {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_mu_list(page-1);
            });

            jQuery('#wpvivid_mu_staging_site_list').on("click",'.next-page',function() {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_mu_list(page+1);
            });

            jQuery('#wpvivid_mu_staging_site_list').on("click",'.last-page',function() {
                wpvivid_get_mu_list('last');
            });

            jQuery('#wpvivid_mu_staging_site_list').on("keypress", '.current-page', function(){
                if(event.keyCode === 13){
                    var page = jQuery(this).val();
                    wpvivid_get_mu_list(page);
                }
            });

            jQuery('#wpvivid-mu-search-submit').click(function() {
                var search = jQuery('#wpvivid-mu-site-search-input').val();
                var ajax_data = {
                    'action': 'wpvivid_get_mu_list',
                    'search':search,
                    'create':true
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    jQuery('#wpvivid_mu_staging_site_list').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_mu_staging_site_list').html(jsonarray.html);
                        }
                        else
                        {
                            alert(jsonarray.error);
                        }
                    }
                    catch (err)
                    {
                        alert(err);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('achieving backup', textStatus, errorThrown);
                    alert(error_message);
                });
            });

            function wpvivid_get_mu_list(page) {
                if(page==0)
                {
                    page =jQuery('#wpvivid_mu_staging_site_list').find('.current-page').val();
                }
                var search = jQuery('#wpvivid-mu-site-search-input').val();
                var ajax_data = {
                    'action': 'wpvivid_get_mu_list',
                    'search':search,
                    'page':page,
                    'copy':true
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    jQuery('#wpvivid_mu_staging_site_list').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_mu_staging_site_list').html(jsonarray.html);
                        }
                        else
                        {
                            alert(jsonarray.error);
                        }
                    }
                    catch (err)
                    {
                        alert(err);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('achieving backup', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            jQuery('#wpvivid_mu_single_staging_site_list').on("click",'.first-page',function() {
                wpvivid_get_mu_single_list('first');
            });

            jQuery('#wpvivid_mu_single_staging_site_list').on("click",'.prev-page',function() {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_mu_single_list(page-1);
            });

            jQuery('#wpvivid_mu_single_staging_site_list').on("click",'.next-page',function() {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_mu_single_list(page+1);
            });

            jQuery('#wpvivid_mu_single_staging_site_list').on("click",'.last-page',function() {
                wpvivid_get_mu_single_list('last');
            });

            jQuery('#wpvivid_mu_single_staging_site_list').on("keypress", '.current-page', function(){
                if(event.keyCode === 13){
                    var page = jQuery(this).val();
                    wpvivid_get_mu_single_list(page);
                }
            });

            jQuery('#wpvivid-mu-single-search-submit').click(function() {
                var search = jQuery('#wpvivid-mu-single-site-search-input').val();
                var ajax_data = {
                    'action': 'wpvivid_get_mu_list',
                    'search':search,
                    'create':true
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    jQuery('#wpvivid_mu_single_staging_site_list').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_mu_staging_site_list').html(jsonarray.html);
                        }
                        else
                        {
                            alert(jsonarray.error);
                        }
                    }
                    catch (err)
                    {
                        alert(err);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('achieving backup', textStatus, errorThrown);
                    alert(error_message);
                });
            });

            function wpvivid_get_mu_single_list(page) {
                if(page==0)
                {
                    page =jQuery('#wpvivid_mu_single_staging_site_list').find('.current-page').val();
                }
                var search = jQuery('#wpvivid-mu-single-site-search-input').val();
                var ajax_data = {
                    'action': 'wpvivid_get_mu_list',
                    'search':search,
                    'page':page,
                    'copy':true,
                    'single':true
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    jQuery('#wpvivid_mu_single_staging_site_list').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_mu_single_staging_site_list').html(jsonarray.html);
                        }
                        else
                        {
                            alert(jsonarray.error);
                        }
                    }
                    catch (err)
                    {
                        alert(err);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('achieving backup', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            jQuery('#wpvivid_mu_single_staging_site_list').on("click",'[name=mu_single_site]',function() {
                jQuery('#wpvivid_mu_single_staging_site_list').find('input:checkbox[name=mu_single_site]').prop('checked', false);
                jQuery('#wpvivid_mu_single_staging_site_list').find('input:checkbox[name=mu_single_site_tables]').prop('checked', false);
                jQuery('#wpvivid_mu_single_staging_site_list').find('input:checkbox[name=mu_single_site_folders]').prop('checked', false);
                jQuery(this).prop('checked', true);
                jQuery(this).closest('tr').find('input:checkbox[name=mu_single_site_tables]').prop('checked', true);
                jQuery(this).closest('tr').find('input:checkbox[name=mu_single_site_folders]').prop('checked', true);
            });

            <?php
            if(is_multisite())
            {
            ?>
            jQuery(document).ready(function () {
                jQuery('#wpvividstg_quick_select_backup_content').hide();
                wpvivid_single_site_step1();
            });

            jQuery('#wpvivid_next_single_site_staging').click(function() {
                var checked=false;
                var site_id='';
                jQuery('input[name=mu_single_site][type=checkbox]').each(function(index, value)
                {
                    if(jQuery(value).prop('checked'))
                    {
                        checked=true;
                        site_id=jQuery(value).val();
                    }
                });

                if(checked!=true)
                {
                    alert('You must choose a subsite to create the staging site.');
                    return;
                }
                //wpvivid_get_mu_custom_themes_plugins_info(site_id);
                wpvivid_single_site_step2();
            });

            function wpvivid_get_mu_custom_themes_plugins_info(site_id) {
                var ajax_data = {
                    'action': 'wpvividstg_get_custom_themes_plugins_info_ex',
                    'id':'',
                    'subsite':site_id,
                    'is_staging': '0'
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        jQuery('#wpvivid_custom_mu_single_staging_list').find('.wpvivid-custom-exclude-themes-list').html('');
                        jQuery('#wpvivid_custom_mu_single_staging_list').find('.wpvivid-custom-exclude-themes-list').html(jsonarray.theme_list);
                        jQuery('#wpvivid_custom_mu_single_staging_list').find('.wpvivid-custom-exclude-plugins-list').html('');
                        jQuery('#wpvivid_custom_mu_single_staging_list').find('.wpvivid-custom-exclude-plugins-list').html(jsonarray.plugin_list);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    var need_retry_custom_themes = false;
                    archieve_info.src_theme_retry++;
                    var retry_times = archieve_info.src_theme_retry;
                    if(retry_times < 10){
                        need_retry_custom_themes = true;
                    }
                    if(need_retry_custom_themes)
                    {
                        setTimeout(function()
                        {
                            wpvivid_get_mu_custom_themes_plugins_info(site_id);
                        }, 3000);
                    }
                    else
                    {
                        var refresh_btn = '<input type="submit" class="button-primary" value="Refresh" onclick="wpvivid_get_mu_custom_themes_plugins_info(\''+site_id+'\');">';
                        jQuery('#wpvivid_custom_mu_single_staging_list').find('.wpvivid-custom-themes-plugins-info').html('');
                        jQuery('#wpvivid_custom_mu_single_staging_list').find('.wpvivid-custom-themes-plugins-info').html(refresh_btn);
                    }
                });
            }

            jQuery('#wpvivid_back_single_site_staging').click(function() {
                wpvivid_single_site_step1();
            });

            function wpvivid_single_site_step1() {
                jQuery('#wpvividstg_single_site_backup_content').show();
                jQuery('#wpvivid_mu_single_staging_site_step1').show();
                jQuery('#wpvivid_mu_single_staging_site_step2').hide();
                jQuery('#wpvivid_mu_create_staging_content').hide();
            }

            function wpvivid_single_site_step2() {
                jQuery('#wpvividstg_single_site_backup_content').show();
                jQuery('#wpvivid_mu_single_staging_site_step1').hide();
                jQuery('#wpvivid_mu_single_staging_site_step2').show();
                jQuery('#wpvivid_mu_create_staging_content').hide();
            }

            jQuery('#wpvividstg_select_backup_content').on("click", 'input:radio[name=choose_backup_content]', function() {
                if(jQuery(this).prop('checked'))
                {
                    var value = jQuery(this).val();
                    if(value === '0')
                    {
                        jQuery('#wpvividstg_quick_select_backup_content').show();
                        jQuery('#wpvividstg_custom_backup_content').hide();
                        jQuery('#wpvividstg_single_site_backup_content').hide();
                        jQuery('#wpvivid_mu_create_staging_content').show();
                    }
                    else if(value === '1')
                    {
                        jQuery('#wpvividstg_quick_select_backup_content').hide();
                        jQuery('#wpvividstg_custom_backup_content').show();
                        jQuery('#wpvividstg_single_site_backup_content').hide();
                        jQuery('#wpvivid_mu_create_staging_content').hide();
                    }
                    else if(value === '2')
                    {
                        jQuery('#wpvividstg_quick_select_backup_content').hide();
                        jQuery('#wpvividstg_custom_backup_content').hide();
                        wpvivid_single_site_step1();
                    }
                }
            });
            <?php
            }
            ?>
        </script>
        <?php
    }
}