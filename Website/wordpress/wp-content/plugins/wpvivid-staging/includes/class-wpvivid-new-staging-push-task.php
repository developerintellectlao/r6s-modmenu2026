<?php
if (!defined('WPVIVID_STAGING_PLUGIN_DIR'))
{
    die;
}

class WPvivid_New_Staging_Push_Task
{
    public $task;
    public $options;
    public $current_job;
    public $log;
    public $db_src_instance;
    public $replacing_table;

    public $from;
    public $to;

    public function __construct($options=array())
    {
        $this->db_src_instance=false;

        $this->options=new WPvivid_Staging_Option();

        if(!empty($options))
        {
            $this->task=$options;
            $this->log=new WPvivid_Staging_Log();
            $this->log->OpenLogFile($this->task['log_file_name']);
        }
    }

    public function create_new_task($options)
    {
        $task_id=uniqid('wpvivid-');

        $this->task['id']=$task_id;

        $this->task['status']['start_time']=time();
        $this->task['status']['run_time']=time();
        $this->task['status']['timeout']=time();
        $this->task['status']['str']='ready';
        $this->task['status']['resume_count']=0;
        $this->task['jobs']=array();
        $this->task['doing']=false;
        $this->task['current_doing']='';
        $this->task['timeout_limit']=900;
        $this->task['log_file_name']=$task_id.'_staging';

        $this->task['setting']=$options['setting'];

        $this->task['exclude_tables']=$options['exclude_tables'];
        $this->task['exclude_regex']=$options['exclude_regex'];
        $this->task['exclude_files']=$options['exclude_files'];
        $this->setup_task($options);

        $log=new WPvivid_Staging_Log();
        $log->CreateLogFile( $this->task['log_file_name'],'no_folder','staging');
        $log->CloseFile();

        $this->options->update_option('staging_task_cancel',false);
        $this->update_task();

        $ret['result']='success';
        $ret['test']=$this->task;
        return $ret;
    }

    public function setup_task($options)
    {
        global $wpvivid_staging;
        global $wpdb;
        $this->set_db_connect_option($options['site_data']['db_connect']);

        $this->task['src_path']=$options['site_data']['path'];
        $this->task['des_path']= untrailingslashit(ABSPATH);

        $this->task['old_prefix']=$options['site_data']['prefix'];
        $this->task['new_prefix']=$wpdb->base_prefix;
        $random_id = $this->create_random_id();
        if($random_id !== false)
        {
            $this->task['temp_prefix']='vi'.$random_id.'_';
        }
        else
        {
            $this->task['temp_prefix']='vividtmp_';
        }

        $this->task['new_site_url']=$options['new_site_url'];
        $this->task['new_home_url']=$options['new_home_url'];

        $this->task['old_site_url']=$options['site_data']['site_url'];
        $this->task['old_home_url']=$options['site_data']['home_url'];

        $this->task['permalink_structure'] = get_option( 'permalink_structure','');
        $this->task['login_url'] = $wpvivid_staging->get_login_url();

        $this->task['is_create_subdomain'] = $options['is_create_subdomain'];
        $this->task['is_mu_single']=isset($options['is_mu_single'])?$options['is_mu_single']:false;

        if(isset($options['need_retain_data']))
        {
            $this->task['need_retain_data'] = $options['need_retain_data'];
        }
        else
        {
            $this->task['need_retain_data']=array();
        }

        if(is_multisite())
        {
            $this->setup_mu($options);
        }

        $this->setup_pushing($options['staging_options']);
    }

    public function create_random_id()
    {
        global $wpdb;
        $retry_time = 0;

        do{
            $retry_time++;
            $id = mt_rand(100, 999);
            $verify_id = $wpdb->get_col($wpdb->prepare( 'SHOW TABLES LIKE %s', array('%'.$id.'%')));
        }while(!empty($verify_id) && $retry_time < 10);

        if($retry_time == 10)
        {
            return false;
        }

        return $id;
    }

    public function setup_mu($options)
    {
        $this->task['path_current_site']=$options['path_current_site'];
        $this->task['main_site_id']=$options['main_site_id'];
        $this->task['mu_site']=$options['mu_site'];

        if($options['is_mu'])
        {
            $staging_wpdb=$this->get_src_db_instance();
            $staging_wpdb->set_prefix($this->task['old_prefix']);
            $subsites=get_sites();
            $mu_exclude_table=array();
            $mu_upload_exclude=array();

            if($options['all_site'])
            {
                $this->task['database_check']='1';
                $this->task['uploads_check']='1';
            }
            else
            {
                $mu_site_list=$options['mu_site_list'];
                foreach ($subsites as $subsite)
                {
                    $subsite_id = get_object_vars($subsite)["blog_id"];
                    if($this->task['main_site_id']==$subsite_id)
                        continue;
                    if(array_key_exists($subsite_id,$options['mu_site_list']))
                    {
                        if($options['mu_site_list'][$subsite_id]['tables']==0)
                        {
                            $prefix=$staging_wpdb->get_blog_prefix($subsite_id);
                            $mu_exclude_table=$this->get_table_list($staging_wpdb,$prefix,$mu_exclude_table);
                        }

                        if($options['mu_site_list'][$subsite_id]['folders']==0)
                        {
                            $mu_upload_exclude[]=$this->get_upload_exclude_folder($subsite_id);
                        }
                    }
                    else
                    {
                        $prefix=$staging_wpdb->get_blog_prefix($subsite_id);
                        $mu_exclude_table=$this->get_table_list($staging_wpdb,$prefix,$mu_exclude_table);
                        $mu_upload_exclude[]=$this->get_upload_exclude_folder($subsite_id);
                    }
                }
            }

            if(isset($options['mu_main_site'])&&$options['mu_main_site']['check'])
            {
                /*
                if(!$options['mu_main_site']['tables'])
                {
                    $prefix=$staging_wpdb->get_blog_prefix($options['mu_main_site']);
                    $mu_exclude_table=$this->get_table_list($staging_wpdb,$prefix,$mu_exclude_table);
                }

                if($options['mu_main_site']['upload'])
                {

                }
                else
                {
                    $uploads_path = $this->task['src_path'] . '/wp-content/uploads';
                    $this->task['mu_upload_include'][] = '#^' . preg_quote($this->transfer_path($uploads_path . DIRECTORY_SEPARATOR . 'sites'), '/') . '#';
                }*/

            }
            else
            {
                $uploads_path = $this->task['src_path'] . '/wp-content/uploads';
                $this->task['mu_upload_include'][] = '#^' . preg_quote($this->transfer_path($uploads_path . DIRECTORY_SEPARATOR . 'sites'), '/') . '#';
                $prefix=$staging_wpdb->get_blog_prefix($options['main_site_id']);
                $mu_exclude_table=$this->get_table_list($staging_wpdb,$prefix,$mu_exclude_table);
            }

            foreach ($mu_exclude_table as $table)
            {
                $this->task['exclude_tables'][] = $table;
            }

            foreach ($mu_upload_exclude as $value)
            {
                $this->task['exclude_regex'][] = '#^'.preg_quote($this -> transfer_path($value), '/').'#';
            }
        }
        else if($options['is_mu_single'])
        {
            global $wpdb;
            $this->task['mu_single_site_id']=$options['mu_single_site_id'];

            $this->task['new_prefix']=$wpdb->get_blog_prefix($options['mu_single_site_id']);

            $this->task['new_site_url']=get_site_url($options['mu_single_site_id']);
            $this->task['new_home_url']=get_home_url($options['mu_single_site_id']);


            $this->task['exclude_tables'][] = apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']).'users';
            $this->task['exclude_tables'][] = apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']).'usermeta';
            $this->task['exclude_tables'][] = apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']).'site';
            $this->task['exclude_tables'][] = apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']).'sitemeta';
            $this->task['exclude_tables'][] = apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']).'blogs';
            $this->task['exclude_tables'][] = apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']).'blogmeta';
        }
    }

    public function get_table_list($db,$prefix,$mu_exclude_table,$exclude_user=true)
    {
        $sql=$db->prepare("SHOW TABLES LIKE %s;", $db->esc_like($prefix) . '%');
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
                    if($table_name==$db->base_prefix.'blogs'&&$exclude_user!==false)
                        continue;
                    if($exclude_user===false)
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

        return $mu_exclude_table;
    }

    public function get_upload_exclude_folder($site_id)
    {
        $upload_dir = wp_upload_dir();
        $dir = str_replace( ABSPATH, '', $upload_dir['basedir'] );
        $upload_basedir=$this->task['src_path'].DIRECTORY_SEPARATOR.$dir;
        if ( defined( 'MULTISITE' ) )
        {
            $upload_basedir = $upload_basedir.'/sites/' . $site_id;
        } else {
            $upload_basedir = $upload_basedir.'/' . $site_id;
        }
        return $upload_basedir;
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

    public function setup_pushing($staging_options)
    {
        $index=0;
        $this->task['jobs']=array();
        
        if($staging_options['core_check']=='1')
        {
            $this->task['jobs'][$index]['type']='copy_core';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;
        }

        if($staging_options['content_check']=='1')
        {
            $this->task['jobs'][$index]['type']='copy_wp_content';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;
        }

        if($staging_options['plugins_check']=='1')
        {
            $this->task['jobs'][$index]['type']='copy_plugins';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;
        }

        if($staging_options['themes_check']=='1')
        {
            $this->task['jobs'][$index]['type']='copy_themes';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;
        }

        if($staging_options['uploads_check']=='1'||(isset($this->task['uploads_check'])&&$this->task['uploads_check']=='1'))
        {
            $this->task['jobs'][$index]['type']='copy_upload';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;
        }

        if($staging_options['additional_file_check'] == '1')
        {
            foreach ($staging_options['additional_file_list'] as $key => $value)
            {
                $this->task['custom'][] = $key;
            }
            $this->task['jobs'][$index]['type']='copy_custom';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;
        }

        if($staging_options['database_check']=='1'||(isset($this->task['database_check'])&&$this->task['database_check']=='1'))
        {
            $this->task['need_rename_tables']=true;

            $this->task['jobs'][$index]['type']='copy_db';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;

            $this->task['jobs'][$index]['type']='replace_link';
            $this->task['jobs'][$index]['finished']=0;
            $this->task['jobs'][$index]['progress']=0;
            $index++;
        }
        else
        {
            $this->task['need_rename_tables']=false;
        }

        $this->task['jobs'][$index]['type']='finish_staging';
        $this->task['jobs'][$index]['finished']=0;
        $this->task['jobs'][$index]['progress']=0;
    }

    public function is_task_finished()
    {
        $finished=true;

        foreach ($this->task['jobs'] as $job)
        {
            if($job['finished']==0)
            {
                $finished=false;
                break;
            }
        }
        return $finished;
    }

    public function get_next_job()
    {
        $job_key=false;
        foreach ($this->task['jobs'] as $key=>$job)
        {
            if($job['finished']==0)
            {
                $job_key=$key;
                break;
            }
        }
        return $job_key;
    }

    public function do_staging_job($key)
    {
        if(!isset($this->task['jobs'][$key]))
        {
            $ret['result']='failed';
            $ret['error']='not found job';
            return $ret;
        }

        $this->current_job=$key;
        $job=$this->task['jobs'][$key];
        $this->task['current_job']=$key;
        if(!isset($this->task['jobs'][$key]['start_time']))
        {
            $this->task['jobs'][$key]['start_time']=time();
        }
        $this->update_task();

        if($job['type']=='copy_core')
        {
            $ret= $this->do_copy_core();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='copy_wp_content')
        {
            $ret= $this->do_copy_wp_content();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='copy_plugins')
        {
            $ret= $this->do_copy_plugins();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='copy_themes')
        {
            $ret= $this->do_copy_themes();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='copy_upload')
        {
            $ret= $this->do_copy_upload();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='copy_custom')
        {
            $ret= $this->do_copy_custom();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='copy_db')
        {
            $ret= $this->do_copy_db();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='replace_link')
        {
            $ret= $this->do_replace_link();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }
        else if($job['type']=='finish_staging')
        {
            $ret= $this->do_finish_staging();
            if($ret['result']=='failed')
            {
                return $ret;
            }
        }

        $this->update_task();
        $ret['result']='success';
        return $ret;
    }

    public function do_finish_staging()
    {
        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->log->WriteLog('Pushing the staging site to the live site completed successfully.','notice');
        $this->task['status']['resume_count']=0;
        $this->update_task();

        return $ret;
    }

    public function finish_push_staging()
    {
        if( $this->task['need_rename_tables'])
        {
            global $wpdb;
            $tmp_prefix=$this->task['temp_prefix'];

            $tables = $wpdb->get_results('SHOW TABLE STATUS');
            $new_tables = array();
            if (is_array($tables))
            {
                foreach ($tables as $table)
                {
                    if (0 !== stripos($table->Name, $tmp_prefix))
                    {
                        continue;
                    }
                    if (empty($table->Engine))
                    {
                        continue;
                    }
                    $new_tables[] = $table->Name;
                }
            }
            else
            {
                $ret['result']='failed';
                $ret['error']='Failed to get temp tables.';
                return $ret;
            }

            foreach ($new_tables as $table)
            {
                $new_table=$this->str_replace_first($tmp_prefix,$this->task['new_prefix'],$table);
                $this->log->WriteLog("RENAME TABLE {$table} TO {$new_table}", 'Warning');
                if($wpdb->query('DROP TABLE IF EXISTS ' . $new_table) === false)
                {
                    $error='Failed to drop table. Error:'.$wpdb->last_error;
                    $this->log->WriteLog($error,'error');
                }
                if($wpdb->query("RENAME TABLE {$table} TO {$new_table}") === false)
                {
                    $error='Failed to rename table. Error:'.$wpdb->last_error;
                    $this->log->WriteLog($error,'error');
                }
            }

            $this->reset_staging_data();
            wp_cache_flush();
        }

        $ret['result']='success';

        return $ret;
    }

    public function reset_staging_data()
    {
        global $wpdb;

        $prefix=$wpdb->base_prefix;

        if($this->task['is_mu_single'])
        {

        }
        else
        {
            $query=$wpdb->prepare("UPDATE {$prefix}options SET option_value = %s WHERE option_name = 'siteurl' or option_name='home'",$this->task['new_site_url']);
            $this->log->WriteLog($query, 'Warning');
            if ($wpdb->get_results($query)===false)
            {
                $error=$wpdb->last_error;
                $this->log->WriteLog($error, 'Warning');
            }
        }


        delete_option('wpvivid_staging_finish');
        $update_query=$wpdb->prepare("INSERT INTO {$prefix}options (option_name,option_value) VALUES ('wpvivid_staging_finish',%d)", 1);
        //$this->log->WriteLog($update_query, 'notice');
        if ($wpdb->get_results($update_query)===false)
        {
            $error=$wpdb->last_error;
            $this->log->WriteLog($error, 'Warning');
        }

        $is_overwrite_permalink_structure = $this->task['setting']['staging_overwrite_permalink'];
        if($is_overwrite_permalink_structure == 0)
        {
            delete_option('wpvivid_staging_init');
            $update_query = $wpdb->prepare("INSERT INTO {$prefix}options (option_name,option_value) VALUES ('wpvivid_staging_init',%d)", 1);

            //$this->log->WriteLog($update_query, 'notice');
            if ($wpdb->get_results($update_query) === false)
            {
                $error = $wpdb->last_error;
                $this->log->WriteLog($error, 'Warning');
            }
        }

        global $wpvivid_staging;
        $wpvivid_staging->option->update_option('wpvivid_staging_data',false);
        delete_option('wpvivid_staging_data');
        update_option('blog_public','1');

        //$push_staging_history = $this->get_push_staging_history();
        //update_option('wpvivid_push_staging_history', $push_staging_history);

        if($this->task['is_mu_single'])
        {
            switch_to_blog( $this->task['mu_single_site_id']);
            global $wpvivid_staging;
            $wpvivid_staging->option->update_option('wpvivid_staging_data',false);
            delete_option('wpvivid_staging_data');
            delete_option('wpvivid_staging_finish');
            delete_option('wpvivid_staging_init');
            restore_current_blog();
        }

        $need_retain_options = $this->get_need_retain_options();

        foreach($need_retain_options as $key => $option)
        {
            $update_query = $wpdb->prepare("INSERT INTO {$prefix}options VALUES ('', %s, %s, '') ON DUPLICATE KEY UPDATE option_value=%s", $key, $option, $option);
            //$this->log->WriteLog($update_query, 'notice');
            if ($wpdb->get_results($update_query)===false)
            {
                $error=$wpdb->last_error;
                $this->log->WriteLog($error, 'Warning');
            }
        }

        $insert_query =$wpdb->prepare("INSERT INTO {$prefix}options (option_name,option_value) VALUES ('wpvivid_staging_need_reset_schedules',%d)", 1);
        //$this->log->WriteLog($insert_query, 'notice');
        if ($wpdb->get_results($insert_query)===false)
        {
            $error=$wpdb->last_error;
            $this->log->WriteLog($error, 'Warning');
        }

        $update_query =$wpdb->prepare("UPDATE {$prefix}options SET option_value = %s WHERE option_name = 'upload_path'", "");
        //$this->log->WriteLog($update_query, 'notice');
        if ($wpdb->get_results($update_query)===false)
        {
            $error=$wpdb->last_error;
            $this->log->WriteLog($error, 'Warning');
        }
    }

    public function get_need_retain_options()
    {
        return $this->task['need_retain_data'];
    }

    public function do_copy_core()
    {
        $src_path=$des_path='';
        $this->log->WriteLog('Retrieve the files required to copy.','notice');

        if(!isset($this->task['jobs'][$this->current_job]['offset']))
        {
            $list=$this->get_copy_dir_list('core',$src_path,$des_path);
            $this->task['jobs'][$this->current_job]['files_list']=$list;
            $this->log->WriteLog('Create a cache file.','notice');
            $this->task['jobs'][$this->current_job]['create_cache_file']=$this->create_cache_file($list);

            $this->task['jobs'][$this->current_job]['offset']=0;
            $this->task['jobs'][$this->current_job]['src_path']=$src_path;
            $this->task['jobs'][$this->current_job]['des_path']=$des_path;
        }

        $src_path=$this->task['jobs'][$this->current_job]['src_path'];
        $des_path=$this->task['jobs'][$this->current_job]['des_path'];
        $start=$this->task['jobs'][$this->current_job]['offset'];
        $this->log->WriteLog('Copying files starts from: '.$start,'notice');
        $this->log->WriteLog('Copying files from '.$src_path.' to '.$des_path,'notice');

        while($this->copy_files($this->task['jobs'][$this->current_job]['create_cache_file'],$start,$this->get_files_copy_count(),$src_path,$des_path))
        {
            $this->log->WriteLog('The count of copied files: '.$this->get_files_copy_count(),'notice');
            $this->log->WriteLog('The next copying files starts from:'.$start,'notice');
            $this->task['jobs'][$this->current_job]['offset']=$start;
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }

        $this->log->WriteLog('Copying core files is completed.','notice');

        $this->clean_up();

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function do_copy_wp_content()
    {
        $src_path=$des_path='';
        $this->log->WriteLog('Retrieve the files required to copy.','notice');

        if(!isset($this->task['jobs'][$this->current_job]['offset']))
        {
            $list=$this->get_copy_dir_list('wp-content',$src_path,$des_path);
            $this->task['jobs'][$this->current_job]['files_list']=$list;
            $this->log->WriteLog('Create a cache file.','notice');
            $this->task['jobs'][$this->current_job]['create_cache_file']=$this->create_cache_file($list);

            $this->task['jobs'][$this->current_job]['offset']=0;
            $this->task['jobs'][$this->current_job]['src_path']=$src_path;
            $this->task['jobs'][$this->current_job]['des_path']=$des_path;
        }

        $src_path=$this->task['jobs'][$this->current_job]['src_path'];
        $des_path=$this->task['jobs'][$this->current_job]['des_path'];

        $start=$this->task['jobs'][$this->current_job]['offset'];
        $this->log->WriteLog('Copying files starts from: '.$start,'notice');
        $this->log->WriteLog('Copying files from '.$src_path.' to '.$des_path,'notice');

        while($this->copy_files($this->task['jobs'][$this->current_job]['create_cache_file'],$start,$this->get_files_copy_count(),$src_path,$des_path))
        {
            $this->log->WriteLog('The count of copied files: '.$this->get_files_copy_count(),'notice');
            $this->log->WriteLog('The next copying files starts from:'.$start,'notice');
            $this->task['jobs'][$this->current_job]['offset']=$start;
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }
        $this->log->WriteLog('Copying wp-content files is completed.','notice');
        $this->clean_up();

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function do_copy_plugins()
    {
        $src_path=$des_path='';
        $this->log->WriteLog('Retrieve the files required to copy.','notice');

        if(!isset($this->task['jobs'][$this->current_job]['offset']))
        {
            $list=$this->get_copy_dir_list('plugins',$src_path,$des_path);
            $this->task['jobs'][$this->current_job]['files_list']=$list;
            $this->log->WriteLog('Create a cache file.','notice');
            $this->task['jobs'][$this->current_job]['create_cache_file']=$this->create_cache_file($list);

            $this->task['jobs'][$this->current_job]['offset']=0;
            $this->task['jobs'][$this->current_job]['src_path']=$src_path;
            $this->task['jobs'][$this->current_job]['des_path']=$des_path;
        }

        $src_path=$this->task['jobs'][$this->current_job]['src_path'];
        $des_path=$this->task['jobs'][$this->current_job]['des_path'];

        $start=$this->task['jobs'][$this->current_job]['offset'];
        $this->log->WriteLog('Copying files starts from: '.$start,'notice');
        $this->log->WriteLog('Copying files from '.$src_path.' to '.$des_path,'notice');

        while($this->copy_files($this->task['jobs'][$this->current_job]['create_cache_file'],$start,$this->get_files_copy_count(),$src_path,$des_path))
        {
            $this->log->WriteLog('The count of copied files: '.$this->get_files_copy_count(),'notice');
            $this->log->WriteLog('The next copying files starts from:'.$start,'notice');
            $this->task['jobs'][$this->current_job]['offset']=$start;
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }
        $this->log->WriteLog('Copying plugins files is completed.','notice');
        $this->clean_up();

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function do_copy_themes()
    {
        $src_path=$des_path='';
        $this->log->WriteLog('Retrieve the files required to copy.','notice');

        if(!isset($this->task['jobs'][$this->current_job]['offset']))
        {
            $list=$this->get_copy_dir_list('themes',$src_path,$des_path);
            $this->task['jobs'][$this->current_job]['files_list']=$list;
            $this->log->WriteLog('Create a cache file.','notice');
            $this->task['jobs'][$this->current_job]['create_cache_file']=$this->create_cache_file($list);

            $this->task['jobs'][$this->current_job]['offset']=0;
            $this->task['jobs'][$this->current_job]['src_path']=$src_path;
            $this->task['jobs'][$this->current_job]['des_path']=$des_path;
        }

        $src_path=$this->task['jobs'][$this->current_job]['src_path'];
        $des_path=$this->task['jobs'][$this->current_job]['des_path'];

        $start=$this->task['jobs'][$this->current_job]['offset'];
        $this->log->WriteLog('Copying files starts from: '.$start,'notice');
        $this->log->WriteLog('Copying files from '.$src_path.' to '.$des_path,'notice');

        while($this->copy_files($this->task['jobs'][$this->current_job]['create_cache_file'],$start,$this->get_files_copy_count(),$src_path,$des_path))
        {
            $this->log->WriteLog('The count of copied files: '.$this->get_files_copy_count(),'notice');
            $this->log->WriteLog('The next copying files starts from:'.$start,'notice');
            $this->task['jobs'][$this->current_job]['offset']=$start;
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }
        $this->log->WriteLog('Copying themes files is completed.','notice');
        $this->clean_up();

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function do_copy_upload()
    {
        $src_path=$des_path='';
        $this->log->WriteLog('Retrieve the files required to copy.','notice');

        if(!isset($this->task['jobs'][$this->current_job]['offset']))
        {
            $list=$this->get_copy_dir_list('upload',$src_path,$des_path);
            $this->task['jobs'][$this->current_job]['files_list']=$list;
            $this->log->WriteLog('Create a cache file.','notice');
            $this->task['jobs'][$this->current_job]['create_cache_file']=$this->create_cache_file($list);

            $this->task['jobs'][$this->current_job]['offset']=0;
            $this->task['jobs'][$this->current_job]['src_path']=$src_path;
            $this->task['jobs'][$this->current_job]['des_path']=$des_path;
        }

        $src_path=$this->task['jobs'][$this->current_job]['src_path'];
        $des_path=$this->task['jobs'][$this->current_job]['des_path'];

        $start=$this->task['jobs'][$this->current_job]['offset'];
        $this->log->WriteLog('Copying files starts from: '.$start,'notice');
        $this->log->WriteLog('Copying files from '.$src_path.' to '.$des_path,'notice');

        while($this->copy_files($this->task['jobs'][$this->current_job]['create_cache_file'],$start,$this->get_files_copy_count(),$src_path,$des_path))
        {
            $this->log->WriteLog('The count of copied files: '.$this->get_files_copy_count(),'notice');
            $this->log->WriteLog('The next copying files starts from:'.$start,'notice');
            $this->task['jobs'][$this->current_job]['offset']=$start;
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }
        $this->log->WriteLog('Copying upload files is completed.','notice');
        $this->clean_up();

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function do_copy_custom()
    {
        $this->log->WriteLog('Retrieve the files required to copy.','notice');

        if(!isset($this->task['jobs'][$this->current_job]['offset']))
        {
            $src_path=$des_path='';
            $list=$this->get_copy_dir_list('custom',$src_path,$des_path);
            $this->task['jobs'][$this->current_job]['files_list']=$list;
            $this->log->WriteLog('Create a cache file.','notice');
            $this->task['jobs'][$this->current_job]['create_cache_file']=$this->create_cache_file($list);

            $this->task['jobs'][$this->current_job]['offset']=0;
            $this->task['jobs'][$this->current_job]['src_path']=$src_path;
            $this->task['jobs'][$this->current_job]['des_path']=$des_path;
        }

        $src_path=$this->task['jobs'][$this->current_job]['src_path'];
        $des_path=$this->task['jobs'][$this->current_job]['des_path'];

        $start=$this->task['jobs'][$this->current_job]['offset'];
        $this->log->WriteLog('Copying files starts from: '.$start,'notice');
        $this->log->WriteLog('Copying files from '.$src_path.' to '.$des_path,'notice');

        while($this->copy_files($this->task['jobs'][$this->current_job]['create_cache_file'],$start,$this->get_files_copy_count(),$src_path,$des_path))
        {
            $this->log->WriteLog('The count of copied files: '.$this->get_files_copy_count(),'notice');
            $this->log->WriteLog('The next copying files starts from:'.$start,'notice');
            $this->task['jobs'][$this->current_job]['offset']=$start;
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }
        $this->clean_up();

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->log->WriteLog('Copying custom files is completed.','notice');
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function do_copy_db()
    {
        global $wpdb;
        if(!isset($this->task['jobs'][$this->current_job]['tables']))
        {
            $ret=$this->init_tables_data();
            if($ret['result']=='failed')
            {
                return $ret;
            }
            $this->task['jobs'][$this->current_job]['tables']=$ret['tables'];
        }

        $scr=$this->get_src_db_instance();
        $scr->query('SET FOREIGN_KEY_CHECKS=0;');
        $wpdb->query('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($this->task['jobs'][$this->current_job]['tables'] as $table_name=>$table)
        {
            if($table['finished']==1)
            {
                continue;
            }

            $ret=$this->copy_table($table);
            if($ret['result']=='failed')
            {
                return $ret;
            }

            $this->task['jobs'][$this->current_job]['tables'][$table_name]=$ret['table'];
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->log->WriteLog('Copying db is completed.','notice');
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function copy_table($table)
    {
        $ret['result']='success';
        $ret['table']=$table;

        if($table['create']==0)
        {
            $ret=$this->create_table($table['name']);
            if($ret['result']=='success')
            {
                $table['create']=1;
            }
            else
            {
                return $ret;
            }
        }

        while(!$table['finished'])
        {
            $ret=$this->copy_table_data($table);

            if($ret['result']=='failed')
            {
                return $ret;
            }

            $table=$ret['table'];

            if($table['finished'])
            {
                $this->log->WriteLog('Copying '.$table['name'].' is completed.','notice');
            }
            else if($this->is_time_limit_exceeded())
            {
                $this->log->WriteLog('Copying '.$table['name'].' offset:'.$table['start'],'notice');
                return $ret;
            }

        }

        return $ret;
    }

    public function copy_table_data($table)
    {
        global $wpdb;
        $src_db_instance=$this->get_src_db_instance();

        if($this->task['is_mu_single']&&($table['name']==apply_filters('wpvividstg_get_table_prefix', $src_db_instance->base_prefix).'users'||$table['name']==apply_filters('wpvividstg_get_table_prefix', $src_db_instance->base_prefix).'usermeta'))
        {
            $new_table_name=$this->str_replace_first(apply_filters('wpvividstg_get_table_prefix', $wpdb->prefix),$this->task['temp_prefix'],$table['name']);
        }
        else
        {
            $new_table_name=$this->str_replace_first(apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']),$this->task['temp_prefix'],$table['name']);
        }

        $old_table_name=$table['name'];

        $sum =$src_db_instance->get_var("SELECT COUNT(1) FROM `{$old_table_name}`");
        if($sum==0)
        {
            $table['finished']=1;
            $ret['result']='success';
            $ret['table']=$table;
            return $ret;
        }

        $count=$this->get_db_insert_count();

        $limit = " LIMIT {$count} OFFSET {$table['start']}";

        if($this->is_same_database())
        {
            $select =  "SELECT * FROM `{$old_table_name}` {$limit}";
            if($wpdb->query( "INSERT INTO `{$new_table_name}` ".$select )===false)
            {
                $error='Failed to insert '.$new_table_name.', error: '.$wpdb->last_error;
                $this->log->WriteLog($error,'warning');

                $start =$wpdb->get_var("SELECT COUNT(1) FROM `{$new_table_name}`");
                if($start===false)
                {
                    $ret['result']='failed';
                    $ret['error']=$error;
                    return $ret;
                }

                $this->log->WriteLog('new start offset '.$start,'warning');
                $limit = " LIMIT {$count} OFFSET {$start}";
                $select =  "SELECT * FROM `{$old_table_name}` {$limit}";
                $this->log->WriteLog("INSERT IGNORE INTO `{$new_table_name}` ".$select,'warning');
                if($wpdb->query( "INSERT IGNORE INTO `{$new_table_name}` ".$select )===false)
                {
                    $error='Failed to insert '.$new_table_name.', error: '.$wpdb->last_error;
                    $this->log->WriteLog($error,'error');
                    $ret['result']='failed';
                    $ret['error']=$error;
                    return $ret;
                }
                else
                {
                    $table['start']=$start;
                }
            }
        }
        else
        {
            $src_db=$this->get_src_db_instance();

            $index=$table['start'];
            $rows = $src_db->get_results( "SELECT * FROM `{$old_table_name}` {$limit}", ARRAY_A );

            foreach ( $rows as $row )
            {
                $wpdb->insert($new_table_name,$row);
                $index++;
            }

            if($this->is_time_limit_exceeded())
            {
                $table['start']=$index;
                $ret['result']='success';
                $ret['table']=$table;
                return $ret;
            }
        }

        $table['start'] += $count;

        if( $table['start'] > $sum )
        {
            $table['finished']=1;
        }

        $ret['result']='success';
        $ret['table']=$table;
        return $ret;
    }

    public function str_replace_first($from, $to, $content)
    {
        $from = '/'.preg_quote($from, '/').'/';

        return preg_replace($from, $to, $content, 1);
    }

    public function create_table($table_name)
    {
        global $wpdb;
        $src_db_instance=$this->get_src_db_instance();
        $ret['result']='success';
        if($this->task['is_mu_single']&&($table_name==apply_filters('wpvividstg_get_table_prefix', $src_db_instance->base_prefix).'users'||$table_name==apply_filters('wpvividstg_get_table_prefix', $src_db_instance->base_prefix).'usermeta'))
        {
            $new_table_name=$this->str_replace_first(apply_filters('wpvividstg_get_table_prefix', $src_db_instance->prefix),$this->task['temp_prefix'],$table_name);
        }
        else
        {
            $new_table_name=$this->str_replace_first(apply_filters('wpvividstg_get_table_prefix', $this->task['old_prefix']),$this->task['temp_prefix'],$table_name);
        }

        if($this->is_same_database())
        {
            $wpdb->query("DROP TABLE IF EXISTS {$new_table_name}");
            if (false === $wpdb->query("CREATE TABLE `{$new_table_name}` LIKE `{$table_name}`"))
            {
                $error='Failed to create a table. Error:'.$wpdb->last_error;
                $this->log->WriteLog($error,'error');
                $ret['result']='failed';
                $ret['error']=$error;
                return $ret;
            }
        }
        else
        {
            $wpdb->query("DROP TABLE IF EXISTS {$new_table_name}");

            $result = $src_db_instance->get_results( "SHOW CREATE TABLE `{$table_name}`", ARRAY_A );
            if( isset($result[0]['Create Table']))
            {
                $query=$result[0]['Create Table'];

                $query = str_replace( "CREATE TABLE `{$table_name}`", "CREATE TABLE `{$new_table_name}`", $query );
                $query = str_replace( "CREATE TABLE \"{$table_name}\"", "CREATE TABLE \"{$new_table_name}\"", $query );

                $query = preg_replace_callback( "/CONSTRAINT\s`(\w+)`/", function()
                {
                    return "CONSTRAINT `" . uniqid() . "`";
                }, $query );

                $query = preg_replace_callback( "/REFERENCES\s`(\w+)`/", function($matches)
                {
                    return str_replace($this->task['old_prefix'],$this->task['temp_prefix'],$matches[0]);
                }, $query );

                $query = preg_replace_callback( "/CONSTRAINT\s\"(\w+)\"/", function()
                {
                    return "CONSTRAINT `" . uniqid() . "`";
                }, $query );

                $query = preg_replace_callback( "/REFERENCES\s\"(\w+)\"/", function($matches)
                {
                    return str_replace($this->task['old_prefix'],$this->task['temp_prefix'],$matches[0]);
                }, $query );

                if(!preg_match( '/PRIMARY KEY\s/', $query ))
                {
                    $wpdb->query('SET SQL_REQUIRE_PRIMARY_KEY=0;');
                }

                if( false === $wpdb->query( $query ) )
                {
                    $error='Failed to create a table. Error:'.$wpdb->last_error.', query:'.$query;
                    $this->log->WriteLog($error,'error');
                    $ret['result']='failed';
                    $ret['error']=$error;
                }
            }
            else
            {
                $error='Failed to retrieve the table structure. Table name: '.$table_name;
                $this->log->WriteLog($error,'error');
                $ret['result']='failed';
                $ret['error']=$error;
            }
        }


        return $ret;
    }

    public function do_replace_link()
    {
        if(!isset($this->task['jobs'][$this->current_job]['tables']))
        {
            $ret=$this->init_replace_tables_data();
            if($ret['result']=='failed')
            {
                return $ret;
            }
            $this->task['jobs'][$this->current_job]['tables']=$ret['tables'];
        }

        global $wpdb;
        $wpdb->query('SET FOREIGN_KEY_CHECKS=0;');

        $src=$this->get_src_db_instance();
        $src->query('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($this->task['jobs'][$this->current_job]['tables'] as $table_name=>$table)
        {
            if($table['finished']==1)
            {
                continue;
            }

            $ret=$this->replace_table($table);
            if($ret['result']=='failed')
            {
                return $ret;
            }

            $this->task['jobs'][$this->current_job]['tables'][$table_name]=$ret['table'];
            $this->update_task();
            if($this->is_time_limit_exceeded())
            {
                $ret['result']='success';
                return $ret;
            }
        }

        $ret['result']='success';
        $this->task['jobs'][$this->current_job]['finished']=1;
        $this->log->WriteLog('Replacing db is completed.','notice');
        $this->task['status']['resume_count']=0;
        $this->update_task();
        return $ret;
    }

    public function replace_table($table)
    {
        $ret['result']='success';
        $ret['table']=$table;

        while(!$table['finished'])
        {
            $ret=$this->replace_table_data($table);

            if($ret['result']=='failed')
            {
                return $ret;
            }

            $table=$ret['table'];

            if($table['finished'])
            {
                $this->log->WriteLog('Replacing '.$table['name'].' is completed.','notice');
            }
            else if($this->is_time_limit_exceeded())
            {
                $this->log->WriteLog($this->get_db_replace_count().' queries of '.$table['name'].' is replaced.','notice');
                return $ret;
            }
        }

        return $ret;
    }

    public function init_replace_data($new_site_url,$old_site_url)
    {
        $this->from=array();
        $this->to=array();

        $new_url_use_https=false;
        if (0 === stripos($new_site_url, 'https://')|| stripos($new_site_url, 'https:\/\/'))
        {
            $new_url_use_https=true;
        }
        else if (0 === stripos($new_site_url, 'http://')|| stripos($new_site_url, 'http:\/\/'))
        {
            $new_url_use_https=false;
        }

        if($old_site_url!=$new_site_url)
        {
            $remove_http_link=$this->get_remove_http_link($old_site_url);
            if($remove_http_link!==false)
            {
                $new_remove_http_link=$this->get_remove_http_link($new_site_url);
                $this->from[]=$remove_http_link;
                $this->to[]=$new_remove_http_link;

                if($new_url_use_https)
                {
                    $this->from[]='http:'.$new_remove_http_link;
                    $this->to[]='https:'.$new_remove_http_link;
                }
                else
                {
                    $this->from[]='https:'.$new_remove_http_link;
                    $this->to[]='http:'.$new_remove_http_link;
                }

                $quote_old_site_url=$this->get_http_link_at_quote($remove_http_link);
                $quote_new_site_url=$this->get_http_link_at_quote($new_remove_http_link);
                $this->from[]=$quote_old_site_url;
                $this->to[]=$quote_new_site_url;
                if($new_url_use_https)
                {
                    $this->from[]='http:'.$quote_new_site_url;
                    $this->to[]='https:'.$quote_new_site_url;
                }
                else
                {
                    $this->from[]='https:'.$quote_new_site_url;
                    $this->to[]='http:'.$quote_new_site_url;
                }
            }
            else
            {
                $remove_http_link=$this->get_remove_http_link_ex($old_site_url);
                if($remove_http_link!==false)
                {
                    $new_remove_http_link=$this->get_remove_http_link_ex($new_site_url);
                    $this->from[]=$remove_http_link;
                    $this->to[]=$new_remove_http_link;

                    if($new_url_use_https)
                    {
                        $this->from[]='http:'.$new_remove_http_link;
                        $this->to[]='https:'.$new_remove_http_link;
                    }
                    else
                    {
                        $this->from[]='https:'.$new_remove_http_link;
                        $this->to[]='http:'.$new_remove_http_link;
                    }
                }
            }

            $tmp_old_site_url = str_replace(':', '%3A', $old_site_url);
            $tmp_old_site_url = str_replace('/', '%2F', $tmp_old_site_url);

            $tmp_new_site_url = str_replace(':', '%3A', $new_site_url);
            $tmp_new_site_url = str_replace('/', '%2F', $tmp_new_site_url);

            $this->from[]=$tmp_old_site_url;
            $this->to[]=$tmp_new_site_url;
        }
    }

    private function get_remove_http_link($url)
    {
        if (0 === stripos($url, 'https://'))
        {
            $mix_link = '//'.substr($url, 8);
        } elseif (0 === stripos($url, 'http://')) {
            $mix_link = '//'.substr($url, 7);
        }
        else
        {
            $mix_link=false;
        }
        return $mix_link;
    }

    private function get_remove_http_link_ex($url)
    {
        if (0 === stripos($url, 'https://'))
        {
            $mix_link = '\/\/'.substr($url, 8);
        } elseif (0 === stripos($url, 'http://')) {
            $mix_link = '\/\/'.substr($url, 7);
        }
        else
        {
            $mix_link=false;
        }
        return $mix_link;
    }

    private function get_http_link_at_quote($url)
    {
        return str_replace('/','\/',$url);
    }

    public function replace_table_data($table)
    {
        global $wpdb;
        $this->init_replace_data($this->task['new_site_url'],$this->task['old_site_url']);

        $replace_count=$this->get_db_replace_count();

        $this->replacing_table=$table['name'];
        $src_db=$this->get_src_db_instance();
        if(substr($table['name'], strlen($this->task['temp_prefix']))=='usermeta')
        {
            $update_query ='UPDATE '.$table['name'].' SET meta_key=REPLACE(meta_key,\''.$this->task['old_prefix'].'\',\''.$this->task['new_prefix'].'\') WHERE meta_key LIKE \''.str_replace('_','\_',$this->task['old_prefix']).'%\';';
            $this->log->WriteLog($update_query, 'notice');
            $this->log->WriteLog('The length of UPDATE statement: '.strlen($update_query), 'notice');
            if ($wpdb->get_results($update_query)===false)
            {
                $error=$wpdb->last_error;
                $this->log->WriteLog($error, 'Warning');
            }

            if($this->task['is_mu_single'])
            {
                $update_query ='UPDATE '.$table['name'].' SET meta_key=REPLACE(meta_key,\''.$src_db->base_prefix.'\',\''.$this->task['new_prefix'].'\') WHERE meta_key LIKE \''.str_replace('_','\_',$src_db->base_prefix).'%\';';
                $this->log->WriteLog($update_query, 'notice');
                $this->log->WriteLog('The length of UPDATE statement: '.strlen($update_query), 'notice');
                if ($wpdb->get_results($update_query)===false)
                {
                    $error=$wpdb->last_error;
                    $this->log->WriteLog($error, 'Warning');
                }
            }

            $table['finished']=1;
            $ret['result']='success';
            $ret['table']=$table;
            return $ret;
        }

        if(is_multisite())
        {
            if(substr($table['name'], strlen($this->task['temp_prefix']))=='blogs')
            {
                $this->log->WriteLog('update mu blogs', 'notice');

                if((preg_match('#^https?://([^/]+)#i', $this->task['new_home_url'], $matches) || preg_match('#^https?://([^/]+)#i', $this->task['new_site_url'], $matches)) && (preg_match('#^https?://([^/]+)#i', $this->task['old_home_url'], $old_matches) || preg_match('#^https?://([^/]+)#i', $this->task['old_site_urll'], $old_matches)))
                {
                    $new_string = strtolower($matches[1]);
                    $old_string = strtolower($old_matches[1]);

                    $query = 'SELECT * FROM `'.$table['name'].'`';
                    $result=$wpdb->get_results($query,ARRAY_A);
                    if($result && sizeof($result)>0)
                    {
                        $rows = $result;
                        foreach ($rows as $row)
                        {
                            $update=array();
                            $where=array();

                            $old_domain_data = $row['domain'];
                            $new_domain_data=str_replace($old_string,$new_string,$old_domain_data);

                            $temp_where='`blog_id` = "' . $row['blog_id'] . '"';
                            if (is_callable(array($wpdb, 'remove_placeholder_escape')))
                                $temp_where = $wpdb->remove_placeholder_escape($temp_where);
                            $where[] = $temp_where;
                            $update[] = '`domain` = "' . $new_domain_data . '"';

                            $new_path_data=$this->task['mu_site'][$row['blog_id']]['path_site'];
                            $update[] = '`path` = "' . $new_path_data . '"';

                            if(!empty($update)&&!empty($where))
                            {
                                $update_query = 'UPDATE `'.$table['name'].'` SET '.implode(', ', $update).' WHERE '.implode(' AND ', array_filter($where)).';';
                                $this->log->WriteLog($update_query, 'notice');
                                $wpdb->get_results($update_query);
                            }
                        }
                    }
                }
            }

            if(substr($table['name'], strlen($this->task['temp_prefix']))=='site')
            {
                if($this->task['is_create_subdomain'])
                {
                    $this->log->WriteLog('Replace table subdomain staging: true', 'Warning');
                    $this->log->WriteLog('update mu site', 'notice');

                    if((preg_match('#^https?://([^/]+)#i', $this->task['new_home_url'], $matches) || preg_match('#^https?://([^/]+)#i', $this->task['new_site_url'], $matches)) && (preg_match('#^https?://([^/]+)#i', $this->task['old_home_url'], $old_matches) || preg_match('#^https?://([^/]+)#i', $this->task['old_site_url'], $old_matches)))
                    {
                        $new_string = strtolower($matches[1]);
                        $old_string = strtolower($old_matches[1]);

                        $query = 'SELECT * FROM `'.$table['name'].'`';
                        $result=$wpdb->get_results($query,ARRAY_A);
                        if($result && sizeof($result)>0)
                        {
                            $rows = $result;
                            $mu_option=$this->get_mu_option();
                            $this->log->WriteLog(json_encode($mu_option), 'notice');
                            foreach ($rows as $row)
                            {
                                $update=array();
                                $where=array();

                                $old_domain_data = $row['domain'];
                                $new_domain_data=str_replace($old_string,$new_string,$old_domain_data);

                                $temp_where='`id` = "' . $row['id'] . '"';
                                if (is_callable(array($wpdb, 'remove_placeholder_escape')))
                                    $temp_where = $wpdb->remove_placeholder_escape($temp_where);
                                $where[] = $temp_where;
                                $update[] = '`domain` = "' . $new_domain_data . '"';

                                if(!empty($update)&&!empty($where))
                                {
                                    $update_query = 'UPDATE `'.$table['name'].'` SET '.implode(', ', $update).' WHERE '.implode(' AND ', array_filter($where)).';';
                                    $this->log->WriteLog($update_query, 'notice');
                                    $wpdb->get_results($update_query);
                                }
                            }
                        }
                    }
                }
                else
                {
                    $this->log->WriteLog('Replace table subdomain staging: false', 'Warning');
                }
            }

        }

        $skip_table=false;
        if(apply_filters('wpvivid_restore_db_skip_replace_tables',$skip_table,$table['name']))
        {
            $this->log->WriteLog('Ignore table '.$table['name'], 'Warning');
            $table['finished']=1;
            $ret['result']='success';
            $ret['table']=$table;
            return $ret;
        }

        if(preg_match('/aiowps_audit_log/i', $table['name']) || preg_match('/aiowps_debug_log/i', $table['name']) || preg_match('/itsec_logs/i', $table['name']) ||
            preg_match('/eum_logs/i', $table['name']))
        {
            $this->log->WriteLog('Skip replace table '.$table['name'], 'Warning');
            $table['finished']=1;
            $ret['result']='success';
            $ret['table']=$table;
            return $ret;
        }

        $count =$wpdb->get_var("SELECT COUNT(1) FROM `{$table['name']}`");

        if($count>0)
        {
            $query='DESCRIBE `'.$table['name'].'`';
            $result=$wpdb->get_results($query,ARRAY_A);
            if($result===false)
            {
                $error=$wpdb->last_error;
                $this->log->WriteLog($error, 'Warning');
                $table['finished']=1;
                $ret['result']='success';
                $ret['table']=$table;
                return $ret;
            }
            $columns=array();
            foreach ($result as $data)
            {
                $column['Field']=$data['Field'];
                if($data['Key']=='PRI')
                    $column['PRI']=1;
                else
                    $column['PRI']=0;

                if($data['Type']=='mediumblob')
                {
                    $column['skip']=1;
                }
                $columns[]=$column;
            }

            $update_query='';
            $replace_row=0;
            $start_row=$table['start'];

            $page=$replace_count;

            for ($current_row = $start_row; $current_row <= $count; $current_row += $page)
            {
                $this->log->WriteLog('Start replacing the table prefix from '.$current_row. ' row.', 'notice');
                $query = 'SELECT * FROM `'.$table['name'].'` LIMIT '.$current_row.', '.$page;

                $replace_row+=$page;
                $result=$wpdb->get_results($query,ARRAY_A);

                if($result && sizeof($result)>0)
                {
                    $rows = $result;
                    $row_offset=$current_row;

                    foreach ($rows as $row)
                    {
                        if( isset( $row['option_value'] ) && strlen( $row['option_value'] ) >= 5000000 )
                        {
                            continue;
                        }

                        $update=array();
                        $where=array();
                        foreach ($columns as $column)
                        {
                            if(isset($column['skip']))
                            {
                                //$this->log->WriteLog('Skip MEDIUMBLOB data.', 'notice');
                                continue;
                            }
                            if($column['Field']=='option_name'&&$row[$column['Field']]=='mainwp_child_subpages')
                            {
                                break;
                            }
                            $old_data = $row[$column['Field']];
                            if (!is_null($old_data))
                            {
                                $size = strlen( $old_data );
                            }
                            else
                            {
                                $size = 0;
                            }
                            if( $size >= 5000000 )
                            {
                                continue;
                            }
                            if($column['PRI']==1)
                            {
                                $wpdb->escape_by_ref($old_data);
                                $temp_where='`'.$column['Field'].'` = "' . $old_data . '"';
                                if (is_callable(array($wpdb, 'remove_placeholder_escape')))
                                    $temp_where = $wpdb->remove_placeholder_escape($temp_where);
                                $where[] = $temp_where;
                            }
                            $skip_row=false;
                            if(apply_filters('wpvivid_restore_db_skip_replace_rows',$skip_row,$table['name'],$column['Field']))
                            {
                                continue;
                            }
                            $new_data=$this->replace_row_data($old_data);
                            if($new_data==$old_data)
                                continue;
                            $wpdb->escape_by_ref($new_data);
                            if (is_callable(array($wpdb, 'remove_placeholder_escape')))
                                $new_data = $wpdb->remove_placeholder_escape($new_data);
                            $update[] = '`'.$column['Field'].'` = "' . $new_data . '"';
                        }
                        if(!empty($update)&&!empty($where))
                        {
                            $temp_query = 'UPDATE `'.$table['name'].'` SET '.implode(', ', $update).' WHERE '.implode(' AND ', array_filter($where)).';';
                            $update_query=$temp_query;

                            if ($wpdb->get_results($update_query)===false)
                            {
                                $error=$wpdb->last_error;
                                $this->log->WriteLog($error, 'Warning');
                            }
                            $update_query='';
                        }

                        $row_offset++;
                        if($this->is_time_limit_exceeded())
                        {
                            $table['start']=$row_offset;

                            $ret['result']='success';
                            $ret['table']=$table;
                            return $ret;
                        }

                        if($this->is_time_limit_exceeded())
                        {
                            $current_row+= $page;
                            break;
                        }
                    }
                }
                if(!empty($update_query))
                {
                    $this->log->WriteLog($update_query, 'notice');
                    if ($wpdb->get_results($update_query)===false)
                    {
                        $error=$wpdb->last_error;
                        $this->log->WriteLog($error, 'Warning');
                    }
                }
            }

            if(!empty($update_query))
            {
                $wpdb->get_results($update_query);
            }

            if($current_row >= $count)
            {
                $replace_current_table_finish=1;
            }
            else
            {
                $replace_current_table_finish=0;
            }
        }
        else
        {
            $table['finished']=1;
            $ret['table']=$table;
            $ret['result']='success';
            return $ret;
        }

        $ret['result']='success';
        if($replace_current_table_finish==1)
        {
            $table['finished']=1;
            $this->log->WriteLog('Replacing database tables is completed.', 'notice');
        }
        else
        {
            $table['start'] = $current_row;
        }

        if($table['finished'])
        {
            if(substr($table['name'], strlen($this->task['temp_prefix']))=='options')
            {
                $update_query ='UPDATE '.$table['name'].' SET option_name=\''.$this->task['new_prefix'].'user_roles\' WHERE option_name=\''.$this->task['old_prefix'].'user_roles\';';
                $this->log->WriteLog($update_query, 'notice');
                $this->log->WriteLog('The length of UPDATE statement: '.strlen($update_query), 'notice');
                if ($wpdb->get_results($update_query)===false)
                {
                    $error=$wpdb->last_error;
                    $this->log->WriteLog($error, 'Warning');
                }
            }
        }

        $ret['table']=$table;
        $ret['result']='success';
        return $ret;
    }

    public function replace_row_data($old_data)
    {
        try{
            $unserialize_data = @unserialize($old_data);
        }
        catch (LogicException $error)
        {
            return $old_data;
        }
        try{
            if($unserialize_data===false)
            {
                $old_data=$this->replace_string_v2($old_data);
            }
            else
            {
                $old_data=$this->replace_serialize_data($unserialize_data);
                $old_data=serialize($old_data);
            }
        }
        catch (Error $error)
        {
            $old_data=$this->replace_string_v2($old_data);
        }

        return $old_data;
    }

    private function replace_serialize_data($data)
    {
        if(is_string($data))
        {
            $serialize_data =@unserialize($data);
            if($serialize_data===false)
            {
                $data=$this->replace_string_v2($data);
            }
            else
            {
                $data=serialize($this->replace_serialize_data($serialize_data));
            }
        }
        else if(is_array($data))
        {
            foreach ($data as $key => $value)
            {
                if(is_string($value))
                {
                    $data[$key]=$this->replace_string_v2($value);
                }
                else if(is_array($value))
                {
                    $data[$key]=$this->replace_serialize_data($value);
                }
                else if(is_object($value))
                {
                    if (is_a($value, '__PHP_Incomplete_Class'))
                    {
                        //
                    }
                    else
                    {
                        $data[$key]=$this->replace_serialize_data($value);
                    }
                }
            }
        }
        else if(is_object($data))
        {
            $temp = $data; // new $data_class();
            if (is_a($data, '__PHP_Incomplete_Class'))
            {

            }
            else
            {
                $props = get_object_vars($data);
                foreach ($props as $key => $value)
                {
                    if (strpos($key, "\0")===0)
                        continue;
                    if(is_string($value))
                    {
                        $temp->$key =$this->replace_string_v2($value);
                    }
                    else if(is_array($value))
                    {
                        $temp->$key=$this->replace_serialize_data($value);
                    }
                    else if(is_object($value))
                    {
                        $temp->$key=$this->replace_serialize_data($value);
                    }
                }
            }
            $data = $temp;
            unset($temp);
        }

        return $data;
    }

    public function replace_string_v2($old_string)
    {
        if(!is_string($old_string))
        {
            return $old_string;
        }


        if(!empty($this->from)&&!empty($this->to))
        {
            $old_string=str_replace($this->from,$this->to,$old_string);
        }

        return $old_string;
    }

    public function is_same_database()
    {
        if($this->task['db_connect']['use_additional_db']===false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_src_db_instance()
    {
        if( $this->db_src_instance===false)
        {
            if($this->task['db_connect']['use_additional_db']===false)
            {
                global $wpdb;
                $this->db_src_instance=$wpdb;
                return $this->db_src_instance;
            }
            else
            {
                $this->db_src_instance=new wpdb($this->task['db_connect']['dbuser'],
                    $this->task['db_connect']['dbpassword'],
                    $this->task['db_connect']['dbname'],
                    $this->task['db_connect']['dbhost']);
                return $this->db_src_instance;
            }
        }
        else
        {
            return $this->db_src_instance;
        }
    }
    
    public function init_tables_data()
    {
        $this->log->WriteLog('Retrieve the tables required to copy.','notice');

        $tables=array();
        $src_db=$this->get_src_db_instance();
        $sql=$src_db->prepare("SHOW TABLES LIKE %s;", $src_db->esc_like($this->task['old_prefix']) . '%');
        $result = $src_db->get_results($sql, OBJECT_K);

        if($result===false)
        {
            $error='Failed to retrieve database tables, error:'.$src_db->last_error;
            $this->log->WriteLog($error,'error');
            $ret['result']='failed';
            $ret['error']=$error;
            return $ret;
        }

        if(empty($result))
        {
            $error='Tables not found in database.';
            $this->log->WriteLog($error,'error');
            $ret['result']='failed';
            $ret['error']=$error;
            return $ret;
        }

        foreach ($result as $table_name=>$value)
        {
            if($this->is_tables_exclude($table_name))
            {
                continue;
            }

            $table['name']=$table_name;
            $table['create']=0;
            $table['start']=0;
            $table['finished']=0;
            $tables[$table_name]=$table;
        }

        $all_tables = (array) $src_db->get_results( "SHOW FULL TABLES", ARRAY_N );
        if(!empty($all_tables) && !empty($tables))
        {
            foreach ($tables as $table_name => $table)
            {
                foreach ($all_tables as $table_arr)
                {
                    if($table_name === $table_arr[0] && $table_arr[1] === 'VIEW')
                    {
                        unset($tables[$table_name]);
                    }
                }
            }
        }

        $ret['result']='success';
        $ret['tables']=$tables;
        return $ret;
    }

    public function init_replace_tables_data()
    {
        global $wpdb;
        $this->log->WriteLog('Retrieve the tables required to replace.','notice');

        $tables=array();

        $db=$wpdb;

        $sql=$db->prepare("SHOW TABLES LIKE %s;", $db->esc_like($this->task['temp_prefix']) . '%');
        $result = $db->get_results($sql, OBJECT_K);

        if($result===false)
        {
            $error='Failed to retrieve database tables, error:'.$db->last_error;
            $this->log->WriteLog($error,'error');
            $ret['result']='failed';
            $ret['error']=$error;
            return $ret;
        }

        if(empty($result))
        {
            $error='Tables not found in database.';
            $this->log->WriteLog($error,'error');
            $ret['result']='failed';
            $ret['error']=$error;
            return $ret;
        }

        foreach ($result as $table_name=>$value)
        {
            $table['name']=$table_name;
            $table['start']=0;
            $table['finished']=0;
            $tables[$table_name]=$table;
        }

        $ret['result']='success';
        $ret['tables']=$tables;
        return $ret;
    }

    public function is_tables_exclude($table)
    {
        $arr=$this->task['exclude_tables'];

        if(empty($arr))
            return false;

        return in_array($table, $arr);
    }
    
    public function clean_up()
    {
        @unlink($this->task['jobs'][$this->current_job]['create_cache_file']);
    }

    public function change_wp_temp_config()
    {
        $src_path=$this->task['src_path'];

        $path=$src_path.DIRECTORY_SEPARATOR.'wp-config.php.vividtemp';
        $data=file_get_contents($path);
        if( $data === false )
        {
            $this->log->WriteLog('wp-config.php.vividtemp not found in '.$path,'notice');
            return false;
        }

        $pattern     = '/\$table_prefix\s*=\s*(.*)/';
        $replacement = '$table_prefix = \'' . $this->task['new_prefix'] . '\';';
        $data     = preg_replace( $pattern, $replacement, $data );


        if( $data===null )
        {
            $this->log->WriteLog('table_prefix not found in wp-config.php.vividtemp','notice');
            return false;
        }

        preg_match( "/define\s*\(\s*['\"]WP_HOME['\"]\s*,\s*(.*)\s*\);/", $data, $matches );
        if( !empty( $matches[1] ) )
        {
            $this->log->WriteLog('WP_HOME found in wp-config.php.vividtemp','notice');
            $pattern = "/define\s*\(\s*['\"]WP_HOME['\"]\s*,\s*(.*)\s*\);.*/";
            $replace = "define('WP_HOME','" . $this->task['new_home_url'] . "'); //";
            $data = preg_replace( array($pattern), $replace, $data );
            if( null === ($data) )
            {
                $this->log->WriteLog('WP_HOME not replace in wp-config.php.vividtemp','notice');
                return false;
            }
        }

        preg_match( "/define\s*\(\s*['\"]WP_SITEURL['\"]\s*,\s*(.*)\s*\);/", $data, $matches );
        if( !empty( $matches[1] ) )
        {
            $this->log->WriteLog('WP_SITEURL found in wp-config.php.vividtemp','notice');
            $pattern = "/define\s*\(\s*['\"]WP_SITEURL['\"]\s*,\s*(.*)\s*\);.*/";
            $replace = "define('WP_SITEURL','" .  $this->task['new_site_url'] . "'); //";
            $data = preg_replace( array($pattern), $replace, $data );
            if( null === ($data) )
            {
                $this->log->WriteLog('WP_SITEURL not replace in wp-config.php.vividtemp','notice');
                return false;
            }
        }

        $pattern     = "/define\s*\(\s*'DB_NAME'\s*,\s*(.*)\s*\);.*/";
        $replacement = "define( 'DB_NAME', '".DB_NAME."');";
        //$data     = preg_replace( $pattern, $replacement, $data );
        if (preg_match($pattern, $data, $matches)) {
            $db_name = $matches[0];
            $data = str_replace($db_name, $replacement, $data);
        }

        $pattern     = "/define\s*\(\s*'DB_USER'\s*,\s*(.*)\s*\);.*/";
        $replacement = "define( 'DB_USER', '".DB_USER."');";
        //$data     = preg_replace( $pattern, $replacement, $data );
        if (preg_match($pattern, $data, $matches)) {
            $db_user = $matches[0];
            $data = str_replace($db_user, $replacement, $data);
        }

        $pattern     = "/define\s*\(\s*'DB_PASSWORD'\s*,\s*(.*)\s*\);.*/";
        $replacement = "define( 'DB_PASSWORD', '".DB_PASSWORD."');";
        //$data     = preg_replace( $pattern, $replacement, $data );
        if (preg_match($pattern, $data, $matches)) {
            $db_pass = $matches[0];
            $data = str_replace($db_pass, $replacement, $data);
        }

        $pattern     = "/define\s*\(\s*'DB_HOST'\s*,\s*(.*)\s*\);.*/";
        $replacement = "define( 'DB_HOST', '".DB_HOST."');";
        //$data     = preg_replace( $pattern, $replacement, $data );
        if (preg_match($pattern, $data, $matches)) {
            $db_host = $matches[0];
            $data = str_replace($db_host, $replacement, $data);
        }

        preg_match( "/define\s*\(\s*['\"]WP_ENVIRONMENT_TYPE['\"]\s*,\s*(.*)\s*\);/", $data, $matches );
        if( !empty( $matches[1] ) )
        {
            $this->log->WriteLog('WP_ENVIRONMENT_TYPE found in wp-config.php.vividtemp', 'notice');
            $pattern = "/define\s*\(\s*['\"]WP_ENVIRONMENT_TYPE['\"]\s*,\s*(.*)\s*\);.*/";
            $replace = "";
            $data = preg_replace( array($pattern), $replace, $data );
            if( null === ($data) )
            {
                $this->log->WriteLog('WP_ENVIRONMENT_TYPE not replace in wp-config.php.vividtemp','notice');
                return false;
            }
            else
            {
                $this->log->WriteLog('WP_ENVIRONMENT_TYPE replace in wp-config.php.vividtemp','notice');
            }
        }

        file_put_contents($path,$data);

        $this->log->WriteLog('Replacing table_prefix in wp-config.php.vividtemp is completed.','notice');
        return true;
    }

    public function get_files_copy_count()
    {
        if(isset($this->task['setting']['staging_file_copy_count']))
            $files_copy_count=$this->task['setting']['staging_file_copy_count'];
        else
            $files_copy_count=WPVIVID_STAGING_FILE_COPY_COUNT_EX;
        return $files_copy_count;
    }

    public function copy_files($cache_file,&$start,$count,$src_path,$des_path)
    {
        $file = new SplFileObject($cache_file);

        if($start==0)
            $file->seek($start);
        else
            $file->seek($start-1);

        $file->setFlags( \SplFileObject::SKIP_EMPTY | \SplFileObject::READ_AHEAD );

        for ( $i = 0; $i < $count; $i++ )
        {
            if( $file->eof() )
            {
                return false;
            }
            $src = $file->fgets();

            $src=trim($src,PHP_EOL);

            if(empty($src))
                continue;

            $start++;

            if(!file_exists($src))
            {
                continue;
            }
            $src=$this -> transfer_path($src);
            $des=str_replace($src_path,$des_path,$src);

            if(is_dir($src))
            {
                @mkdir($des,0755,true);
            }
            else
            {
                if(strpos($src,'wp-config.php')===false)
                {
                    if(copy($src,$des))
                    {
                        if(isset($this->task['setting']['files_permission']))
                        {
                            if($this->task['setting']['files_permission'] === '0755')
                            {
                                @chmod($des,0755);
                            }
                            else if($this->task['setting']['files_permission'] === '0644')
                            {
                                @chmod($des,0644);
                            }
                            else if($this->task['setting']['files_permission'] === '0775')
                            {
                                @chmod($des,0755);
                            }
                            else
                            {
                                @chmod($des,0755);
                            }
                        }
                        else
                        {
                            @chmod($des,0755);
                        }
                    }
                    else
                    {
                        $this->log->WriteLog('Failed to copy files from '.$src.' to '.$des.'.','warning');
                    }
                }
            }
        }

        $file = null;
        return true;
    }

    public function create_cache_file($list)
    {
        $cache_file_name=WP_CONTENT_DIR.DIRECTORY_SEPARATOR.WPVIVID_STAGING_PATH.DIRECTORY_SEPARATOR.$this->task['id'].'_staging_cache.txt';

        if(file_exists($cache_file_name))
            @unlink($cache_file_name);
        $cache_file=fopen($cache_file_name,'a');
        foreach ($list as $item)
        {
            $exclude_regex=array();
            $exclude_files_regex=array();
            if(isset($item['exclude_regex'])&&$item['exclude_regex']!=false)
            {
                $exclude_regex=$item['exclude_regex'];
            }
            if(isset($item['exclude_files_regex'])&&$item['exclude_files_regex']!=false)
            {
                $exclude_files_regex=$item['exclude_files_regex'];
            }
            //
            if(isset($item['include_regex'])&&$item['include_regex']!=false)
            {
                $include_regex=$item['include_regex'];
            }
            else
            {
                $include_regex=array();
            }

            $this->create_cache_from_folder($cache_file,$item['root'],$item['recursive'],$exclude_regex,$exclude_files_regex,$include_regex);
        }
        fclose($cache_file);
        return $cache_file_name;
    }

    public function create_cache_from_folder($cache_file,$folder,$recursive=false,$exclude_regex=array(),$exclude_files_regex=array(),$include_regex=array())
    {
        $this->getFolder($cache_file,$folder,$recursive,$exclude_regex,$exclude_files_regex,$include_regex);
    }

    public function getFolder($cache_file,$path,$recursive,$exclude_regex,$exclude_files_regex,$include_regex)
    {
        if(is_dir($path))
        {
            $line = $path.PHP_EOL;
            fwrite($cache_file, $line);

            $handler = opendir($path);
            if($handler!==false)
            {
                while (($filename = readdir($handler)) !== false)
                {
                    if ($filename != "." && $filename != "..")
                    {
                        if (is_dir($path . DIRECTORY_SEPARATOR . $filename))
                        {
                            if($recursive&&$this->regex_match($exclude_regex, $path . DIRECTORY_SEPARATOR . $filename, 0))
                            {
                                if(!empty($include_regex))
                                {
                                    if($recursive&&$this->regex_match($include_regex, $path . DIRECTORY_SEPARATOR . $filename, 1))
                                    {
                                        $this->getFolder($cache_file,$path . DIRECTORY_SEPARATOR . $filename,$recursive,$exclude_regex,$exclude_files_regex,$include_regex);
                                    }
                                }
                                else
                                {
                                    $this->getFolder($cache_file,$path . DIRECTORY_SEPARATOR . $filename,$recursive,$exclude_regex,$exclude_files_regex,$include_regex);
                                }
                            }
                        } else {

                            if($this->regex_match($exclude_files_regex, $filename, 0))
                            {
                                if ($this->regex_match($exclude_regex, $path . DIRECTORY_SEPARATOR . $filename, 0))
                                {
                                    if(is_readable($path . DIRECTORY_SEPARATOR . $filename))
                                    {
                                        if (filesize($path . DIRECTORY_SEPARATOR . $filename) < $this->get_exclude_file_size() * 1024 * 1024 || $this->get_exclude_file_size() === 0)
                                        {
                                            $line = $path . DIRECTORY_SEPARATOR . $filename.PHP_EOL;
                                            fwrite($cache_file, $line);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if($handler)
                    @closedir($handler);
            }
        }
    }

    public function get_exclude_file_size()
    {
        if(isset($this->task['setting']['staging_exclude_file_size']))
        {
            $exclude_file_size = $this->task['setting']['staging_exclude_file_size'];
        }
        else {
            $exclude_file_size = WPVIVID_STAGING_MAX_FILE_SIZE_EX;
        }
        return $exclude_file_size;
    }

    private function regex_match($regex_array,$string,$mode)
    {
        if(empty($regex_array))
        {
            return true;
        }

        if($mode==0)
        {
            foreach ($regex_array as $regex)
            {
                if(preg_match($regex,$string))
                {
                    return false;
                }
            }

            return true;
        }

        if($mode==1)
        {
            foreach ($regex_array as $regex)
            {
                if(preg_match($regex,$string))
                {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    public function get_copy_dir_list($key,&$src_path,&$des_path)
    {
        $list=array();
        $exclude_regex=$this->task['exclude_regex'];
        $exclude_files_regex=$this->task['exclude_files'];

        if($key=='core')
        {
            $src_path=$this->task['src_path'];
            $des_path=$this->task['des_path'];
            $dir_info['root']=$this -> transfer_path($src_path);
            $dir_info['recursive']=false;
            $exclude_files_regex[]='#.htaccess#';
            $exclude_files_regex[]='#wp-config.php#';
            $dir_info['exclude_files_regex']=$exclude_files_regex;

            $list[]=$dir_info;
            $dir_info['root']=$src_path.DIRECTORY_SEPARATOR.'wp-admin';
            $dir_info['recursive']=true;
            $list[]=$dir_info;
            $dir_info['root']=$src_path.DIRECTORY_SEPARATOR.'wp-includes';
            $list[]=$dir_info;
        }
        else if($key=='wp-content')
        {
            $des_path=$this->get_content_dir($this->task['des_path']);
            $src_path=untrailingslashit($this->get_content_dir($this->task['src_path']));
            $dir_info['root']=$this -> transfer_path($src_path);
            $dir_info['recursive']=true;

            /*
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'updraft', '/').'#';   // Updraft Plus backup directory
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'ai1wm-backups', '/').'#'; // All-in-one WP migration backup directory
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'backups', '/').'#'; // Xcloner backup directory
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'upgrade', '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'wpvivid', '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.WPVIVID_STAGING_PATH, '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'cache', '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'w3tc-config', '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'Dropbox_Backup', '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'WPvivid_Image_Optimization', '/').'#';
            */

            $local_setting=get_option('wpvivid_local_setting');
            if(isset($local_setting['path']))
                $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR. $local_setting['path'], '/').'#';
            if(defined('WPVIVID_UPLOADS_ISO_DIR'))
            {
                $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).'/'.WPVIVID_UPLOADS_ISO_DIR, '/').'#';
            }
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_content_dir($this->task['src_path'])).DIRECTORY_SEPARATOR.'aiowps_backups', '/').'#';

            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_upload_dir($this->task['src_path'])), '/').'#';
            $exclude_regex[]='#^'.preg_quote($this->transfer_path($this->get_theme_dir($this->task['src_path'])), '/').'#';
            $exclude_regex[]='#^'.preg_quote($this->transfer_path($this->get_plugin_dir($this->task['src_path'])), '/').'#';

            global $wpvivid_staging;
            $staging_list = $wpvivid_staging->option->get_option('staging_site_data');

            if(!empty($staging_list))
            {
                foreach ($staging_list as $key => $value)
                {
                    $exclude_regex[]='#^'.preg_quote($this -> transfer_path($value['path']), '/').'$#';
                }
            }

            $dir_info['exclude_regex']=$exclude_regex;
            $dir_info['exclude_files_regex']=$exclude_files_regex;
            $list[]=$dir_info;
        }
        else if($key=='plugins')
        {
            $des_path=$this->get_plugin_dir($this->task['des_path']);
            $src_path=untrailingslashit($this->get_plugin_dir($this->task['src_path']));

            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_plugin_dir($this->task['src_path']).DIRECTORY_SEPARATOR.'wpvivid-backuprestore'), '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_plugin_dir($this->task['src_path']).DIRECTORY_SEPARATOR.'wpvivid-staging'), '/').'#';
            $exclude_regex[]='#^'.preg_quote($this -> transfer_path($this->get_plugin_dir($this->task['src_path']).DIRECTORY_SEPARATOR.'wpvivid-backup-pro'), '/').'#';

            $dir_info['root']=$this -> transfer_path($src_path);
            $dir_info['exclude_regex']=$exclude_regex;
            $dir_info['exclude_files_regex']=$exclude_files_regex;
            $dir_info['recursive']=true;
            $list[]=$dir_info;
        }
        else if($key=='themes')
        {
            $des_path=$this->get_theme_dir($this->task['des_path']);
            $src_path=$this->get_theme_dir($this->task['src_path']);
            $dir_info['root']=$this -> transfer_path($src_path);
            $dir_info['exclude_regex']=$exclude_regex;
            $dir_info['exclude_files_regex']=$exclude_files_regex;
            $dir_info['recursive']=true;
            $list[]=$dir_info;
        }
        else if($key=='upload')
        {
            $des_path=$this->get_upload_dir($this->task['des_path']);
            $src_path=$this->get_upload_dir($this->task['src_path']);
            $dir_info['root']=$this -> transfer_path($src_path);
            $dir_info['exclude_regex']=$exclude_regex;
            $dir_info['exclude_files_regex']=$exclude_files_regex;
            //$this->task['mu_upload_include']
            if(isset($this->task['mu_upload_include'])&&!empty($this->task['mu_upload_include']))
            {
                $dir_info['include_regex']=$this->task['mu_upload_include'];
            }
            $dir_info['recursive']=true;
            $list[]=$dir_info;
        }
        else
        {
            $src_path=$this->task['src_path'];
            $des_path=$this->task['des_path'];
            foreach ($this->task['custom'] as $path)
            {
                $dir_info['root']=$this -> transfer_path($src_path.DIRECTORY_SEPARATOR.$path);
                $dir_info['exclude_regex']=$exclude_regex;
                $dir_info['exclude_files_regex']=$exclude_files_regex;
                $dir_info['recursive']=true;
                $list[]=$dir_info;
            }
        }

        $src_path=$this -> transfer_path($src_path);
        $des_path=$this -> transfer_path($des_path);

        return $list;
    }

    public function get_content_dir($root)
    {
        $dir = str_replace( ABSPATH, '', WP_CONTENT_DIR );
        return $root.DIRECTORY_SEPARATOR.$dir;
    }

    public function get_upload_dir($root)
    {
        $upload_dir = wp_upload_dir();
        $dir = str_replace( ABSPATH, '', $upload_dir['basedir'] );
        return $root.DIRECTORY_SEPARATOR.$dir;
    }

    public function get_theme_dir($root)
    {
        $dir = str_replace( ABSPATH, '',get_theme_root() );
        return $root.DIRECTORY_SEPARATOR.$dir;
    }

    public function get_plugin_dir($root)
    {
        $dir = str_replace( ABSPATH, '',WP_PLUGIN_DIR );
        return $root.DIRECTORY_SEPARATOR.$dir;
    }

    private function transfer_path($path)
    {
        $path = str_replace('\\','/',$path);
        $values = explode('/',$path);
        return implode(DIRECTORY_SEPARATOR,$values);
    }

    public function set_db_connect_option($db_connect)
    {
        if($db_connect['use_additional_db'])
        {
            $this->task['db_connect']['use_additional_db'] = true;
            $this->task['db_connect']['dbuser'] = $db_connect['dbuser'];
            $this->task['db_connect']['dbpassword'] = $db_connect['dbpassword'];
            $this->task['db_connect']['dbname'] = $db_connect['dbname'];
            $this->task['db_connect']['dbhost'] = $db_connect['dbhost'];
        }
        else
        {
            $this->task['db_connect']['use_additional_db'] = false;
        }
    }

    public function set_time_limit()
    {
        //max_execution_time
        @ini_set('memory_limit', $this->task['setting']['staging_memory_limit']);
        @set_time_limit( $this->task['setting']['staging_max_execution_time']);
        $this->task['status']['timeout']=time();
        $this->update_task();
    }

    public function get_db_insert_count()
    {
        if(isset($this->task['setting']['staging_db_insert_count']))
            $db_insert_count=$this->task['setting']['staging_db_insert_count'];
        else
            $db_insert_count=WPVIVID_STAGING_DB_INSERT_COUNT_EX;
        return $db_insert_count;
    }

    public function get_db_replace_count()
    {
        if(isset($this->task['setting']['staging_db_replace_count']))
            $db_replace_count=$this->task['setting']['staging_db_replace_count'];
        else
            $db_replace_count=WPVIVID_STAGING_DB_REPLACE_COUNT_EX;
        return $db_replace_count;
    }

    public function get_time_limit()
    {
        return $this->task['setting']['staging_max_execution_time'];
    }

    public function get_max_resume_count()
    {
        return $this->task['setting']['staging_resume_count'];
    }

    public function get_status()
    {
        return $this->task['status'];
    }

    public function update_task_status($reset_start_time=false,$status='',$reset_timeout=false,$resume_count=false,$error='')
    {
        $this->task['status']['run_time']=time();
        if($reset_start_time)
            $this->task['status']['start_time']=time();
        if(!empty($status))
        {
            $this->task['status']['str']=$status;
        }
        if($reset_timeout)
            $this->task['status']['timeout']=time();
        if($resume_count!==false)
        {
            $this->task['status']['resume_count']=$resume_count;
        }

        if(!empty($error))
        {
            $this->task['status']['error']=$error;
        }

        $this->update_task();
    }

    public function update_task()
    {
        wp_cache_flush();
        $this->task['status']['run_time']=time();
        $this->options->update_option('wpvivid_staging_push_task_ex',$this->task);
    }

    public function get_progress()
    {
        $progress['main_percent']='0';
        $progress['doing']='';

        $finished_job=0;
        $total_jobs=count($this->task['jobs']);
        foreach ($this->task['jobs'] as $job)
        {
            if($job['finished']==1)
            {
                $finished_job++;
            }
        }

        if($total_jobs==0)
        {
            return $progress;
        }

        $i_progress=intval(($finished_job/$total_jobs)*100);
        $progress['main_percent']=$i_progress;

        $progress['doing']= $this->task['current_doing'];

        return $progress;
    }

    public function is_time_limit_exceeded()
    {
        $time_limit =isset($this->task['setting']['request_timeout'])?$this->task['setting']['request_timeout']:60;
        $time_taken = microtime(true) - $this->task['status']['start_time'];
        if($time_taken >= $time_limit)
        {
            return true;
        }

        return false;
    }

    public function clean_tmp()
    {
        set_time_limit(120);
        $prefix=$this->task['temp_prefix'];

        global $wpdb;
        $sql=$wpdb->prepare("SHOW TABLES LIKE %s;", $wpdb->esc_like($prefix) . '%');
        $result = $wpdb->get_results($sql, OBJECT_K);
        if(!empty($result))
        {
            $wpdb->query( "SET foreign_key_checks = 0" );
            foreach ($result as $table_name=>$value)
            {
                $table['name']=$table_name;
                $wpdb->query( "DROP TABLE IF EXISTS {$table_name}" );
            }
        }
    }
}
