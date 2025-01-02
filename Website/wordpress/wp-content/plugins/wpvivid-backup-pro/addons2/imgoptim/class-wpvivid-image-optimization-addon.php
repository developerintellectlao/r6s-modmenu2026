<?php

/**
 * WPvivid addon: yes
 * Addon Name: wpvivid-imgoptim-pro
 * Description: Pro
 * Version: 2.2.27
 * Need_init: yes
 * Interface Name: WPvivid_ImgOptim_Addon
 */
if (!defined('WPVIVID_BACKUP_PRO_PLUGIN_DIR'))
{
    die;
}
class WPvivid_ImgOptim_Addon
{
    public $auto_opt_ids;

    public function __construct()
    {
        $this->auto_opt_ids=array();
        add_filter('wpvivid_imgoptim_get_main_admin_menus',array($this,'get_main_admin_menus'),9999);
        add_filter('wpvivid_imgoptim_get_admin_menus',array($this,'get_admin_menus'),9999);
        //add_action('wpvivid_dashboard_menus_box',array($this, 'image_optimization_menu_box'),11);

        add_filter('wpvivid_image_optimize_columns',array($this,'image_optimize_columns'),9999);
        add_action( 'manage_media_custom_column', array($this, 'optimize_column_display'),10,2);
        add_filter('wpvivid_imgoptim_submitbox',array($this,'submitbox'),9999);
        //add_action( 'attachment_submitbox_misc_actions',  array( $this,'submitbox_ex'),30);
        add_filter('wpvivid_attachment_fields_to_edit',array($this,'attachment_fields_to_edit'),9999,2);

        add_filter('wpvivid_imgoptim_user_info',array($this,'image_optimization_user_info'),9999);
        add_filter('wpvivid_imgoptim_skip_file_ex', array($this, 'skip_file'), 20, 2);
        add_filter('wpvivid_imgoptim_skip_file_ex', array($this, 'skip_size'), 20, 3);
        add_filter('wpvivid_imgoptim_og_skip_file_ex', array($this, 'skip_file'), 20, 2);
        add_filter('cron_schedules',array( $this,'cron_schedules'),100);
        $this->check_auto_schedule();
        add_action('wpvivid_imgoptim_auto_event',array( $this,'imgoptim_auto_event'));

        add_filter( 'wpvivid_allowed_image_auto_optimization',   array( $this, 'allowed_image_auto_optimization' ), 11 );
        add_filter( 'wpvivid_allowed_image_auto_optimization_ex',   array( $this, 'allowed_image_auto_optimization_ex' ), 11 );
        add_action( 'add_attachment',                  array( $this, 'add_auto_opt_id' ), 1000 );
        add_filter( 'wp_generate_attachment_metadata', array( $this, 'update_auto_opt_id_status' ), 1000, 2 );
        add_filter( 'wp_update_attachment_metadata',   array( $this, 'auto_optimize' ), 2000, 2 );

        add_filter( 'wpvivid_upload_small_file_params', array($this, 'upload_small_file_params'),20,2);
        add_filter( 'wpvivid_compress_image_without_upload_params', array($this, 'compress_image_without_upload_params'),20,2);
        add_action('plugin_loaded',array($this,'fix_ajax'));

        //gif
        add_filter('wpvivid_imgoptim_support_mime_types',array($this, 'support_mime_types'));
        add_filter('wpvivid_imgoptim_get_file_type',array($this, 'get_file_type'),10,2);
        add_filter('wpvivid_imgoptim_support_extension',array($this, 'support_extension'));

        //role
        add_filter('wpvivid_get_role_cap_list',array($this, 'get_caps'));

        add_action('wpvivid_add_sidebar_image_optimization', array($this, 'add_sidebar'));

        add_action('admin_enqueue_scripts',array( $this,'enqueue_styles'));

        //ajax
        if(is_multisite())
        {
            add_action('wp_ajax_wpvivid_get_mu_opt_single_image_progress',array($this,'get_mu_single_image_progress'));
            add_action('wp_ajax_wpvivid_mu_opt_single_image',array($this,'mu_opt_single_image'));
            add_action('wp_ajax_wpvivid_mu_restore_single_image',array($this,'mu_restore_single_image'));
            //
        }

        $options=get_option('wpvivid_optimization_options',array());
        $empty_image_log=isset($options['empty_image_log'])?$options['empty_image_log']:true;
        if($empty_image_log)
        {
            add_action('wpvivid_clean_image_optimization_log_event',array( $this,'clean_image_optimization_log_event'));
            if(!defined( 'DOING_CRON' ))
            {
                if(wp_get_schedule('wpvivid_clean_image_optimization_log_event')===false)
                {
                    if(wp_schedule_event(time()+30, 'weekly', 'wpvivid_clean_image_optimization_log_event')===false)
                    {

                    }
                }
            }
        }
        else
        {
            if(wp_get_schedule('wpvivid_clean_image_optimization_log_event'))
            {
                wp_clear_scheduled_hook('wpvivid_clean_image_optimization_log_event');
                $timestamp = wp_next_scheduled('wpvivid_clean_image_optimization_log_event');
                wp_unschedule_event($timestamp,'wpvivid_clean_image_optimization_log_event');
            }
        }
    }

    public function clean_image_optimization_log_event()
    {
        try
        {
            $log=new WPvivid_Image_Optimize_Log();
            $dir=$log->GetSaveLogFolder();
            $regex='#^wpvivid.*_log.txt#';

            $dir.=DIRECTORY_SEPARATOR;
            if(file_exists($dir))
            {
                $handler=opendir($dir);
                if($handler!==false)
                {
                    while(($filename=readdir($handler))!==false)
                    {
                        if($filename != "." && $filename != "..")
                        {
                            if(is_dir($dir.$filename))
                            {
                                continue;
                            }
                            else {
                                if(preg_match($regex,$filename))
                                {
                                    @unlink($dir.$filename);
                                }
                            }
                        }
                    }
                    if($handler)
                        @closedir($handler);
                }
            }
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
        }
        die();
    }

    public function mu_restore_single_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['id'])||!is_string($_POST['id']))
        {
            die();
        }

        if(!isset($_POST['site_id'])||!is_string($_POST['site_id']))
        {
            die();
        }

        if(isset($_POST['page'])&&is_string($_POST['page']))
        {
            $page=sanitize_text_field($_POST['page']);
        }
        else
        {
            $page='media';
        }

        try
        {
            $id=sanitize_key($_POST['id']);
            $site_id=sanitize_key($_POST['site_id']);
            $image['image_id']=$id;
            $image['site_id']=$site_id;
            if(!is_main_site())
            {
                switch_to_blog(get_main_site_id());
            }

            $task=new WPvivid_ImgOptim_Task_Ex();
            $task->mu->restore_image($site_id,$id);

            if($page=='edit')
            {
                $html='<h4>'.__('WPvivid Imgoptim', 'wpvivid-imgoptim').'</h4>';
            }
            else
            {
                $html='';
            }

            if(!$task->mu->is_mu_image_optimized($image))
            {
                if($task->is_mu_image_progressing($site_id,$id))
                {
                    $html.= "<a  class='wpvivid-media-progressing button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Optimizing...', 'wpvivid-imgoptim')."</a>";
                }
                else
                {
                    $html.= "<a  class='wpvivid-mu-media button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Optimize','wpvivid-imgoptim')."</a>";

                }
            }
            else
            {
                restore_current_blog();
                $meta=get_post_meta( $id,'wpvivid_image_optimize_meta', true );
                $percent=round(100-($meta['sum']['opt_size']/$meta['sum']['og_size'])*100,2);
                $html.='<ul>';
                $html.= '<li><span>'.__('Optimized size','wpvivid-imgoptim').' : </span><strong>'.size_format($meta['sum']['opt_size'],2).'</strong></li>';
                $html.= '<li><span>'.__('Saved','wpvivid-imgoptim').' : </span><strong>'.$percent.'%</strong></li>';
                $html.= '<li><span>'.__('Original size','wpvivid-imgoptim').' : </span><strong>'.size_format($meta['sum']['og_size'],2).'</strong></li>';
                $html.='<li><p style="border-bottom:1px solid #D2D3D6;"></p></li>';
                $html.="<li><a  class='wpvivid-mu-media-restore button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Restore','wpvivid-imgoptim')."</a></li>";
                $html.='</ul>';
            }
            $ret[$id]['html']=$html;
            $ret['result']='success';

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

    public function enqueue_styles()
    {
        if(get_current_screen()->id=='upload'||get_current_screen()->id=='attachment')
        {
            if(is_multisite())
            {
                wp_enqueue_script(WPVIVID_PRO_PLUGIN_SLUG.'_Optimize_MU', WPVIVID_BACKUP_PRO_PLUGIN_URL . '/includes/display/js/optimize.js', array('jquery'), WPVIVID_BACKUP_PRO_VERSION, true);
            }
        }
    }

    public function add_sidebar()
    {
        if(apply_filters('wpvivid_show_sidebar',true))
        {
            ?>
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox  wpvivid-sidebar">
                        <h2 style="margin-top:0.5em;">
                            <span class="dashicons dashicons-book-alt wpvivid-dashicons-orange" ></span>
                            <span><?php esc_attr_e(
                                    'Documentation', 'WpAdminStyle'
                                ); ?></span></h2>
                        <div class="inside" style="padding-top:0;">
                            <ul class="" >
                                <li>
                                    <span class="dashicons dashicons-format-gallery  wpvivid-dashicons-grey"></span>
                                    <a href="https://docs.wpvivid.com/wpvivid-image-optimization-pro-overview.html"><b><?php _e('Image Bulk Optimization', 'wpvivid-imgoptim'); ?></b></a>
                                    <small><span style="float: right;"><a href="#" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                                </li>
                                <li><span class="dashicons dashicons-update  wpvivid-dashicons-grey"></span>
                                    <a href="https://docs.wpvivid.com/wpvivid-image-optimization-free-lazyload-images.html"><b><?php _e('Lazy Loading', 'wpvivid-imgoptim'); ?></b></a>
                                    <small><span style="float: right;"><a href="#" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                                </li>
                                <li><span class="dashicons dashicons-admin-site  wpvivid-dashicons-grey"></span>
                                    <a href="https://docs.wpvivid.com/wpvivid-image-optimization-pro-integrate-cdn.html"><b><?php _e('CDN Integration', 'wpvivid-imgoptim'); ?></b></a>
                                    <small><span style="float: right;"><a href="#" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                                </li>
                                <li>
                                    <span class="dashicons dashicons-format-image  wpvivid-dashicons-grey"></span>
                                    <a href="https://docs.wpvivid.com/wpvivid-image-optimization-pro-convert-to-webp.html"><b><?php _e('Convert Images to WebP', 'wpvivid-imgoptim'); ?></b></a>
                                    <small><span style="float: right;"><a href="#" style="text-decoration: none;"><span class="dashicons dashicons-migrate wpvivid-dashicons-grey"></span></a></span></small><br>
                                </li>
                            </ul>
                        </div>

                        <?php
                        if(apply_filters('wpvivid_show_submit_ticket',true))
                        {
                            ?>
                            <h2>
                                <span class="dashicons dashicons-businesswoman wpvivid-dashicons-green"></span>
                                <span><?php esc_attr_e(
                                        'Support', 'WpAdminStyle'
                                    ); ?></span>
                            </h2>
                            <div class="inside">
                                <ul class="">
                                    <li><span class="dashicons dashicons-admin-comments wpvivid-dashicons-green"></span>
                                        <a href="https://wpvivid.com/submit-ticket"><b><?php _e('Submit A Ticket', 'wpvivid-imgoptim'); ?></b></a>
                                        <br>
                                        <?php echo sprintf(__('The ticket system is for %s Pro users only. If you need any help with our plugin, submit a ticket and we will respond shortly.', 'wpvivid-imgoptim'), apply_filters('wpvivid_white_label_display', 'WPvivid')); ?>
                                    </li>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
            <?php
        }
    }

    public function get_caps($cap_list)
    {
        $cap['slug']='wpvivid-can-use-image-optimization';
        $cap['display']='Image Optimization';
        $cap['menu_slug']=strtolower(sprintf('%s-imgoptim', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
        $cap['index']=19;
        $cap['icon']='<span class="dashicons dashicons-format-gallery wpvivid-dashicons-grey"></span>';
        $cap_list[$cap['slug']]=$cap;

        return $cap_list;
    }

    public function support_extension($extension)
    {
        $options= $this->get_main_option('wpvivid_optimization_options');

        $gif=isset($options['opt_gif'])?$options['opt_gif']:true;
        if($gif)
        {
            $extension[]='gif';
        }


        return $extension;
    }

    public function support_mime_types($mime_types)
    {
        $options= $this->get_main_option('wpvivid_optimization_options');
        $gif=isset($options['opt_gif'])?$options['opt_gif']:true;
        if($gif)
        {
            $mime_types[] = 'image/gif';
        }

        return $mime_types;
    }

    public function get_file_type($type,$mime_type)
    {
        $options= $this->get_main_option('wpvivid_optimization_options');
        $gif=isset($options['opt_gif'])?$options['opt_gif']:true;

        if($mime_type=='image/gif')
        {
            if($gif)
            {
                return 'gif';
            }
            else
            {
                return '';
            }
        }
        else
        {
            return '';
        }
    }

    public function fix_ajax()
    {
        global $wpvivid_imgoptim;
        remove_action('wp_ajax_wpvivid_get_opt_single_image_progress',array($wpvivid_imgoptim,'get_single_image_progress'));
        remove_action('wp_ajax_wpvivid_opt_single_image',array($wpvivid_imgoptim,'opt_single_image'));
        add_action('wp_ajax_wpvivid_get_opt_single_image_progress',array($this,'get_single_image_progress'));
        add_action('wp_ajax_wpvivid_opt_single_image',array($this,'opt_single_image'));
    }

    public function mu_opt_single_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['id']))
        {
            die();
        }

        set_time_limit(180);

        $id=sanitize_key($_POST['id']);
        $site_id=sanitize_key($_POST['site_id']);

        if(!is_main_site())
        {
            switch_to_blog(get_main_site_id());
        }

        $task=new WPvivid_ImgOptim_Task_Ex();
        $options=get_option('wpvivid_optimization_options',array());

        $ret=$task->init_mu_schedule_task($site_id,$id,$options);

        $this->flush($ret);

        if($ret['result']=='success')
        {
            $task->do_optimize_image();
        }

        die();
    }

    public function opt_single_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['id']))
        {
            die();
        }

        set_time_limit(180);

        $task=new WPvivid_ImgOptim_Task_Ex();

        $id=sanitize_key($_POST['id']);

        $options=get_option('wpvivid_optimization_options',array());

        $ret=$task->init_schedule_task($id,$options);

        $this->flush($ret);

        if($ret['result']=='success')
        {
            $task->do_optimize_image();
        }

        die();
    }

    public function flush($ret)
    {
        $text=json_encode($ret);
        if(!headers_sent()){
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

    public function get_single_image_progress()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        $task=new WPvivid_ImgOptim_Task_Ex();
        $ret=$task->get_manual_task_progress();

        if(!isset($_POST['ids'])||!is_string($_POST['ids']))
        {
            die();
        }

        $ids=sanitize_text_field($_POST['ids']);
        $ids=json_decode($ids,true);

        $running=false;

        if(isset($_POST['page']))
        {
            $page=sanitize_text_field($_POST['page']);
        }
        else
        {
            $page='media';
        }

        foreach ($ids as $id)
        {
            if(!$task->is_image_optimized($id))
            {
                if($task->is_image_progressing($id))
                {
                    $running=true;
                }
            }
        }

        foreach ($ids as $id)
        {
            if($page=='edit')
            {
                $html='<h4>'.__('WPvivid Imgoptim', 'wpvivid-imgoptim').'</h4>';
            }
            else
            {
                $html='';
            }
            $meta=get_post_meta( $id,'wpvivid_image_optimize_meta', true );
            $html.=$this->get_optimize_columns($id,$meta);

            $ret[$id]['html']=$html;
        }

        echo json_encode($ret);

        die();
    }

    public function get_mu_single_image_progress()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        $site_id=get_current_blog_id();

        if(!is_main_site())
        {
            switch_to_blog(get_main_site_id());
        }

        $task=new WPvivid_ImgOptim_Task_Ex();
        $ret=$task->get_manual_task_progress();

        if(!isset($_POST['ids'])||!is_string($_POST['ids']))
        {
            die();
        }

        $ids=sanitize_text_field($_POST['ids']);
        $ids=json_decode($ids,true);

        $running=false;

        if(isset($_POST['page']))
        {
            $page=sanitize_text_field($_POST['page']);
        }
        else
        {
            $page='media';
        }

        foreach ($ids as $id)
        {
            $image['image_id']=$id;
            $image['site_id']=$site_id;
            if(!$task->mu->is_mu_image_optimized($image))
            {
                if($task->is_mu_image_progressing($site_id,$id))
                {
                    $running=true;
                }
            }
        }

        restore_current_blog();

        foreach ($ids as $id)
        {
            if($page=='edit')
            {
                $html='<h4>'.__('WPvivid Imgoptim', 'wpvivid-imgoptim').'</h4>';
            }
            else
            {
                $html='';
            }
            $meta=get_post_meta( $id,'wpvivid_image_optimize_meta', true );
            $html.=$this->get_mu_optimize_columns($site_id,$id,$meta);

            $ret[$id]['html']=$html;
        }

        echo json_encode($ret);

        die();
    }

    public function get_main_admin_menus($menu)
    {
        return false;
    }

    public function get_admin_menus($submenus)
    {
        return array();
    }

    public function get_optimize_data()
    {
        $optimize_data=get_option('wpvivid_imgoptim_overview',array());
        if(empty($optimize_data))
        {
            $optimize_data = array(
                'original_size'  => 0,
                'optimized_size' => 0,
                'optimized_percent'=> 0,
                'total_images'     => 0,
                'optimized_images' => 0,
                'webp' => 0,
            );
        }

        return $optimize_data;
    }

    public function image_optimization_menu_box()
    {
        $show=false;

        if(class_exists('WPvivid_ImgOptim_Display_Addon'))
        {
            $show=true;
            $image_optimization=true;
        }
        else
        {
            $image_optimization=false;
        }


        if(class_exists('WPvivid_CDN_Display_Addon'))
        {
            $show=true;
            $cdn=true;
        }
        else
        {
            $cdn=false;
        }

        if(class_exists('WPvivid_Lazy_Load_Display_Addon'))
        {
            $show=true;
            $lazyload=true;
        }
        else
        {
            $lazyload=false;
        }

        if($show)
        {
            $overview=$this->get_optimize_data();

            if(get_option('wpvivid_pro_user',false)===false)
            {
                $server_cache['server_name']='N/A';
                $server_cache['total']=0;
                $server_cache['remain']=0;

                $admin_url=apply_filters('wpvivid_get_admin_url', '');
                $admin_url.='admin.php?page=wpvivid-imgoptim-setting';
            }
            else
            {
                $server_cache=get_option('wpvivid_server_cache',array());
                if(empty($server_cache)||(time()-$server_cache['time']>60*60*24))
                {
                    $server_cache['total']=0;
                    $server_cache['remain']=0;
                }

                $options=get_option('wpvivid_optimization_options',array());
                $region=isset($options['region'])?$options['region']:'us2';
                if($region=='us1')
                {
                    $server_cache['server_name']='North American - Pro';
                    $options['region']='us2';
                    update_option('wpvivid_optimization_options',$options,'no');
                }
                else if($region=='us2')
                {
                    $server_cache['server_name']='North American - Pro';
                }
                else if($region=='eu1')
                {
                    $server_cache['server_name']='Europe - Pro';
                }
                else
                {
                    $server_cache['server_name']='North American - Pro';
                }

                $admin_url='admin.php?page='.strtolower(sprintf('%s-setting', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
            }
            $unoptimized=max($overview['total_images']-$overview['optimized_images'],0);
            $webp=isset($overview['webp'])?$overview['webp']:0;

            ?>
            <div class="wpvivid-dashboard" style="margin-bottom:1em;">
                <div class="wpvivid-one-coloum" style="border-bottom:1px solid #eee;">
                    <div style="padding-left:1em;">
                        <div style="width:40%;float:left;">
                            <div style="padding:0.5em 0.5em 0.5em 0em;">
                                <span class="dashicons dashicons-admin-site wpvivid-dashicons-blue"></span>
                                <span>Cloud Server:</span>
                                <span><?php echo $server_cache['server_name']?></span>
                                <span> </span>
                                <span><a href="<?php echo $admin_url;?>"><?php _e('Settings', 'wpvivid-imgoptim');?></a></span>
                            </div>
                            <div style="padding:0.5em 0 0.5em 0;">
                                <span class="dashicons dashicons-admin-media wpvivid-dashicons-blue"></span>
                                <span><?php echo $overview['total_images'];?></span>
                                <span class="wpvivid-dashicons"> images &amp; thumbnails in media library.</span>
                                <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                    <div class="wpvivid-bottom">
                                        <!-- The content you need -->
                                        <p>All original images and thumbnails(associated images) in your WordPress media library.</p>
                                        <i></i> <!-- do not delete this line -->
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div style="width:60%;float:left;border:1px solid #f1f1f1;box-sizing:border-box;padding-left:1em;">
                            <div style="width:50%;float:left;">
                                <p></p>
                                <div>
                                    <span class="dashicons dashicons-format-gallery wpvivid-dashicons-blue"></span>
                                    <span class="wpvivid-dashicons">Convert to WebP: </span><span id="wpvivid_overview_webp"><?php echo $webp?></span>
                                    <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                        <div class="wpvivid-bottom">
                                            <!-- The content you need -->
                                            <p>Enable <code>Convert to Webp</code> option in settings if you want to convert png/jpg images to WebP format.</p>
                                            <i></i> <!-- do not delete this line -->
                                        </div>
                                    </span>
                                </div>
                                <p></p>
                                <p>
                                    <span class="dashicons dashicons-format-gallery wpvivid-dashicons-grey"></span>
                                    <span class="wpvivid-dashicons">Un-optimized: </span><span id="wpvivid_overview_unoptimized"><?php echo $unoptimized;?></span>
                                </p>
                                <p>
                                    <span class="dashicons dashicons-format-gallery wpvivid-dashicons-green"></span>
                                    <span class="wpvivid-dashicons">Optimized: </span><span id="wpvivid_overview_optimized_images"><?php echo $overview['optimized_images'];?></span>
                                </p>
                            </div>
                            <div style="width:50%;float:left;">
                                <p>
                                    <span class="dashicons dashicons-thumbs-down wpvivid-dashicons wpvivid-dashicons-red"></span>
                                    <span>Original Size:</span>
                                    <span id="wpvivid_overview_original_size"><?php echo size_format($overview['original_size'],2)?></span>
                                </p>
                                <p>
                                    <span class="dashicons dashicons-thumbs-up wpvivid-dashicons wpvivid-dashicons-green"></span>
                                    <span>Optimized Size</span>
                                    <span id="wpvivid_overview_optimized_size"><?php echo size_format($overview['optimized_size'],2)?></span>
                                </p>
                                <p>
                                    <span class="dashicons dashicons-chart-line wpvivid-dashicons wpvivid-dashicons-blue"></span>
                                    <span>Total Saved:</span>
                                    <span id="wpvivid_overview_optimized_percent"><?php echo $overview['optimized_percent']?>%</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <script>
                        jQuery(document).ready(function ()
                        {
                            wpvivid_get_overview();
                        });
                        function wpvivid_get_overview()
                        {
                            var ajax_data = {
                                'action': 'wpvivid_get_overview_ex'
                            };
                            wpvivid_post_request_addon(ajax_data, function(data)
                            {
                                try
                                {
                                    var jsonarray = jQuery.parseJSON(data);
                                    if (jsonarray.result === 'success')
                                    {
                                        jQuery('#wpvivid_overview_webp').html(jsonarray.status.webp);
                                        jQuery('#wpvivid_overview_unoptimized').html(jsonarray.status.unoptimized);
                                        jQuery('#wpvivid_overview_optimized_images').html(jsonarray.status.optimized_images);
                                        jQuery('#wpvivid_overview_original_size').html(jsonarray.status.original_size_format);
                                        jQuery('#wpvivid_overview_optimized_size').html(jsonarray.status.optimized_size_format);
                                        jQuery('#wpvivid_overview_optimized_percent').html(jsonarray.status.optimized_percent_format);
                                    }
                                    else if (jsonarray.result === 'failed')
                                    {
                                        alert(jsonarray.error);
                                    }
                                }
                                catch(err)
                                {
                                    alert(err);
                                }

                            }, function(XMLHttpRequest, textStatus, errorThrown)
                            {
                                var error_message = wpvivid_output_ajaxerror('get server', textStatus, errorThrown);
                                alert(error_message);
                            });
                        }
                    </script>
                </div>
                <div class="wpvivid-clear-float">
                    <div class="wpvivid-two-col">
                        <ul>
                            <?php
                            if($image_optimization)
                            {
                                $help_url="https://docs.wpvivid.com/wpvivid-image-optimization-pro-overview.html";

                                $url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvivid-imgoptim', 'wpvivid-imgoptim');
                                echo '<li><span class="dashicons dashicons-format-gallery wpvivid-dashicons-large wpvivid-dashicons-red"></span>
                                            <a href="'.$url.'"><b>Image Bulk Optimization</b></a>
                                            <small><span style="float: right;"><a href="'.$help_url.'">Learn more...</a></span></small><br>
                                            Optimize images on your website in bulk. You can set up a schedule to perform the optimization automatically.
                                      </li>';
                            }

                            if($cdn)
                            {
                                $help_url="https://docs.wpvivid.com/wpvivid-image-optimization-pro-integrate-cdn.html";

                                $url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvivid-cdn', 'wpvivid-cdn');
                                echo '<li><span class="dashicons dashicons-admin-site wpvivid-dashicons-large wpvivid-dashicons-orange"></span>
                                            <a href="'.$url.'"><b>CDN Integration</b></a>
                                            <small><span style="float: right;"><a href="'.$help_url.'">Learn more...</a></span></small><br>
                                           CDN integrate allows to integrate a CDN service to your website to serve website content to visitors faster.
                                      </li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="wpvivid-two-col">
                        <ul>
                            <?php
                            if($lazyload)
                            {
                                $help_url="https://docs.wpvivid.com/wpvivid-image-optimization-free-lazyload-images.html";
                                $url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvivid-lazyload', 'wpvivid-lazyload');
                                echo '<li><span class="dashicons dashicons-update wpvivid-dashicons-large wpvivid-dashicons-green"></span>
                                         <a href="'.$url.'"><b>Lazyload</b></a>
                                         <small><span style="float: right;"><a href="'.$help_url.'">Learn more...</a></span></small><br>
                                         Lazy load your website images with the options to choose where and how the lazy loading works.
                                       </li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    public function image_optimize_columns($defaults)
    {
        unset($defaults['wpvivid_imgoptim']);
        $defaults['wpvivid_imgoptim_ex'] = __('WPvivid Imgoptim Pro','wpvivid-imgoptim');
        return $defaults;
    }

    public function optimize_column_display($column_name, $id)
    {
        if ( 'wpvivid_imgoptim_ex' === $column_name )
        {
            echo wp_kses_post( $this->optimize_action_columns( $id ) );
        }
    }

    public function is_image_optimized_ex($id)
    {
        $meta=get_post_meta($id,'wpvivid_image_optimize_meta',true);
        if(!empty($meta)&&isset($meta['size'])&&!empty($meta['size']))
        {
            foreach ($meta['size'] as $size_key => $size_data)
            {
                if(!isset($size_data['opt_status'])||$size_data['opt_status']==0)
                {
                    return false;
                }
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    public function is_mu_image_optimized_ex($meta)
    {
        if(!empty($meta)&&isset($meta['size'])&&!empty($meta['size']))
        {
            foreach ($meta['size'] as $size_key => $size_data)
            {
                if(!isset($size_data['opt_status'])||$size_data['opt_status']==0)
                {
                    return false;
                }
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    public function is_image_converted_webp($id)
    {
        $meta=get_post_meta($id,'wpvivid_image_optimize_meta',true);
        if(!empty($meta)&&isset($meta['size'])&&!empty($meta['size']))
        {
            foreach ($meta['size'] as $size_key => $size_data)
            {
                if(!isset($size_data['webp_status'])||$size_data['webp_status']==0)
                {
                    return false;
                }
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    public function is_mu_image_converted_webp($meta)
    {
        if(!empty($meta)&&isset($meta['size'])&&!empty($meta['size']))
        {
            foreach ($meta['size'] as $size_key => $size_data)
            {
                if(!isset($size_data['webp_status'])||$size_data['webp_status']==0)
                {
                    return false;
                }
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_image_optimized_percent($meta)
    {
        return round(100-($meta['sum']['opt_size']/$meta['sum']['og_size'])*100,2);
    }

    public function get_optimize_columns($id,$meta)
    {
        $task=new WPvivid_ImgOptim_Task_Ex();
        $html='';

        if($task->is_image_progressing($id))
        {
            $html.= "<a  class='wpvivid-media-progressing button-primary' data-id='{$id}'>".__('Optimizing...','wpvivid')."</a>";
        }
        else
        {
            if($this->is_image_optimized_ex($id))
            {
                $percent=$this->get_image_optimized_percent($meta);

                $html.='<ul>';
                $html.= '<li><span>'.__('Optimized size','wpvivid').' : </span><strong>'.size_format($meta['sum']['opt_size'],2).'</strong></li>';
                $html.= '<li><span>'.__('Saved','wpvivid').' : </span><strong>'.$percent.'%</strong></li>';
                $html.= '<li><span>'.__('Original size','wpvivid').' : </span><strong>'.size_format($meta['sum']['og_size'],2).'</strong></li>';
                if($this->is_image_converted_webp($id))
                {
                    $converted=0;
                    foreach ($meta['size'] as $size_key=>$size)
                    {
                        if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
                        {
                            if($options['skip_size'][$size_key])
                                continue;
                        }

                        if(isset($size['webp_status'])&&$size['webp_status']==1)
                        {
                            $converted++;
                        }
                    }
                    $html.= '<li><span>'.__('WebP','wpvivid-imgoptim').' : </span><strong>'.$converted.' converted </strong></li>';
                    $html.='<li><p style="border-bottom:1px solid #D2D3D6;"></p></li>';
                    $html.="<li><a  class='wpvivid-media-restore button-primary' data-id='{$id}'>".__('Restore','wpvivid')."</a></li>";
                    $html.='</ul>';
                }
                else
                {
                    $options=get_option('wpvivid_optimization_options',array());

                    if(isset($options['webp'])&&$options['webp']['convert'])
                    {
                        $html.='<li><p style="border-bottom:1px solid #D2D3D6;"></p></li>';
                        $html.="<li><a  class='wpvivid-media button-primary' data-id='{$id}'>".__('Convert to webp','wpvivid')."</a></li>";
                        $html.='</ul>';
                    }
                    else
                    {
                        $html.='<li><p style="border-bottom:1px solid #D2D3D6;"></p></li>';
                        $html.="<li><a  class='wpvivid-media-restore button-primary' data-id='{$id}'>".__('Restore','wpvivid')."</a></li>";
                        $html.='</ul>';
                    }
                }
            }
            else
            {
                if(is_multisite())
                {
                    if(is_main_site())
                    {
                        $options=get_option('wpvivid_optimization_options',array());
                    }
                    else
                    {
                        $site_id=get_main_site_id();
                        $options = get_blog_option($site_id,'wpvivid_optimization_options', array());
                    }
                }
                else
                {
                    $options=get_option('wpvivid_optimization_options',array());
                }
                $auto_optimize_type=isset($options['auto_optimize_type'])?$options['auto_optimize_type']:'upload';


                if($auto_optimize_type=='schedule')
                {
                    $html.="<p>The image will be optimized in schedule</p>";
                    $html.= "<a  class='wpvivid-media button-primary' data-id='{$id}'>".__('Optimize Now','wpvivid')."</a>";
                }
                else
                {
                    $html.= "<a  class='wpvivid-media button-primary' data-id='{$id}'>".__('Optimize Now','wpvivid')."</a>";
                }
            }
        }
        return $html;
    }

    public function get_mu_optimize_columns($site_id,$id,$meta)
    {
        $is_main_site = is_main_site();
        if(!$is_main_site)
        {
            switch_to_blog(get_main_site_id());
        }
        $task=new WPvivid_ImgOptim_Task_Ex();
        $html='';
        $options=$this->get_main_option('wpvivid_optimization_options');
        if($task->is_mu_image_progressing($site_id,$id))
        {
            $html.= "<a  class='wpvivid-mu-media-progressing button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Optimizing...','wpvivid')."</a>";
        }
        else
        {
            if($this->is_mu_image_optimized_ex($meta))
            {
                $percent=$this->get_image_optimized_percent($meta);

                $html.='<ul>';
                $html.= '<li><span>'.__('Optimized size','wpvivid').' : </span><strong>'.size_format($meta['sum']['opt_size'],2).'</strong></li>';
                $html.= '<li><span>'.__('Saved','wpvivid').' : </span><strong>'.$percent.'%</strong></li>';
                $html.= '<li><span>'.__('Original size','wpvivid').' : </span><strong>'.size_format($meta['sum']['og_size'],2).'</strong></li>';
                if($this->is_mu_image_converted_webp($meta))
                {
                    $converted=0;
                    foreach ($meta['size'] as $size_key=>$size)
                    {
                        if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
                        {
                            if($options['skip_size'][$size_key])
                                continue;
                        }

                        if(isset($size['webp_status'])&&$size['webp_status']==1)
                        {
                            $converted++;
                        }
                    }
                    $html.= '<li><span>'.__('WebP','wpvivid-imgoptim').' : </span><strong>'.$converted.' converted </strong></li>';
                    $html.='<li><p style="border-bottom:1px solid #D2D3D6;"></p></li>';
                    $html.="<li><a  class='wpvivid-mu-media-restore button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Restore','wpvivid')."</a></li>";
                    $html.='</ul>';
                }
                else
                {
                    if(isset($options['webp'])&&$options['webp']['convert'])
                    {
                        $html.='<li><p style="border-bottom:1px solid #D2D3D6;"></p></li>';
                        $html.="<li><a  class='wpvivid-mu-media button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Convert to webp','wpvivid')."</a></li>";
                        $html.='</ul>';
                    }
                    else
                    {
                        $html.='<li><p style="border-bottom:1px solid #D2D3D6;"></p></li>';
                        $html.="<li><a  class='wpvivid-mu-media-restore button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Restore','wpvivid')."</a></li>";
                        $html.='</ul>';
                    }
                }
            }
            else
            {
                $auto_optimize_type=isset($options['auto_optimize_type'])?$options['auto_optimize_type']:'upload';

                if($auto_optimize_type=='schedule')
                {
                    $html.="<p>The image will be optimized in schedule</p>";
                    $html.= "<a  class='wpvivid-mu-media button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Optimize Now','wpvivid')."</a>";
                }
                else
                {
                    $html.= "<a  class='wpvivid-mu-media button-primary' data-site='{$site_id}' data-id='{$id}'>".__('Optimize Now','wpvivid')."</a>";
                }
            }
        }

        if(!$is_main_site)
        {
            restore_current_blog();
        }
        return $html;
    }

    public function optimize_action_columns($id)
    {
        if(is_multisite())
        {
            $info =$this->get_main_option('wpvivid_pro_user');

            if($info===false)
            {
                $url='admin.php?page='.strtolower(sprintf('%s-license', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                $html='<div><p>'.__('Not set License','wpvivid').'</p>';
                $html.='<a href="'.$url.'">'.__('Check your Settings','wpvivid').'</a>';
                $html.='</div>';
            }
            else
            {
                $allowed_mime_types = array(
                    'image/jpg',
                    'image/jpeg',
                    'image/png');

                $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

                if ( ! wp_attachment_is_image( $id ) || ! in_array( get_post_mime_type( $id ),$allowed_mime_types ) )
                {
                    return __('Not support','wpvivid');
                }

                $meta=get_post_meta( $id,'wpvivid_image_optimize_meta', true );

                $site_id=get_current_blog_id();

                $html='<div class="wpvivid-mu-media-item" data-id="'.$id.'" data-site="'.$site_id.'">';

                $html.=$this->get_mu_optimize_columns($site_id,$id,$meta);

                $html.='</div>';
            }
        }
        else
        {
            if(get_option('wpvivid_pro_user',false)===false)
            {
                $url='admin.php?page='.strtolower(sprintf('%s-license', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                $html='<div><p>'.__('Not set License','wpvivid').'</p>';
                $html.='<a href="'.$url.'">'.__('Check your Settings','wpvivid').'</a>';
                $html.='</div>';
            }
            else
            {
                $allowed_mime_types = array(
                    'image/jpg',
                    'image/jpeg',
                    'image/png');

                $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

                if ( ! wp_attachment_is_image( $id ) || ! in_array( get_post_mime_type( $id ),$allowed_mime_types ) )
                {
                    return __('Not support','wpvivid');
                }

                $meta=get_post_meta( $id,'wpvivid_image_optimize_meta', true );

                $html='<div class="wpvivid-media-item" data-id="'.$id.'">';

                $html.=$this->get_optimize_columns($id,$meta);

                $html.='</div>';
            }
        }

        return $html;
    }

    public function submitbox_ex()
    {
        $html='';
        $html=apply_filters('wpvivid_imgoptim_submitbox',$html);
        echo $html;
    }

    public function submitbox($html)
    {
        global $post;

        if(is_multisite())
        {
            $info =$this->get_main_option('wpvivid_pro_user');

            if($info===false)
            {
                $url='admin.php?page='.strtolower(sprintf('%s-license', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                $html='<div class="misc-pub-section misc-pub-wpvivid"><h4>'.__('WPvivid Imgoptim Pro','wpvivid').'</h4>';
                $html.='<p>'.__('Not set License','wpvivid').'</p>';
                $html.='<a href="'.$url.'">'.__('Check your Settings','wpvivid').'</a>';
                $html.='</div>';
            }
            else
            {
                $allowed_mime_types = array(
                    'image/jpg',
                    'image/jpeg',
                    'image/png');

                $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

                if ( ! wp_attachment_is_image( $post->ID ) || ! in_array( get_post_mime_type( $post->ID ),$allowed_mime_types ) )
                {
                    $html= __('Not support','wpvivid');
                }
                else
                {
                    $meta=get_post_meta( $post->ID,'wpvivid_image_optimize_meta', true );
                    $site_id=get_current_blog_id();
                    $html='<div class="misc-pub-section misc-pub-wpvivid" data-site="'.$site_id.'" data-id="'.$post->ID.'"><h4>'.__('WPvivid Imgoptim Pro','wpvivid').'</h4>';
                    $html.=$this->get_mu_optimize_columns($site_id,$post->ID,$meta);

                    $html.='</div>';
                }
            }
        }
        else
        {
            if(get_option('wpvivid_pro_user',false)===false)
            {
                $url='admin.php?page='.strtolower(sprintf('%s-license', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                $html='<div class="misc-pub-section misc-pub-wpvivid"><h4>'.__('WPvivid Imgoptim Pro','wpvivid').'</h4>';
                $html.='<p>'.__('Not set License','wpvivid').'</p>';
                $html.='<a href="'.$url.'">'.__('Check your Settings','wpvivid').'</a>';
                $html.='</div>';
            }
            else
            {
                $allowed_mime_types = array(
                    'image/jpg',
                    'image/jpeg',
                    'image/png');

                $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

                if ( ! wp_attachment_is_image( $post->ID ) || ! in_array( get_post_mime_type( $post->ID ),$allowed_mime_types ) )
                {
                    $html= __('Not support','wpvivid');
                }
                else
                {
                    $meta=get_post_meta( $post->ID,'wpvivid_image_optimize_meta', true );
                    $html='<div class="misc-pub-section misc-pub-wpvivid" data-id="'.$post->ID.'"><h4>'.__('WPvivid Imgoptim Pro','wpvivid').'</h4>';
                    $html.=$this->get_optimize_columns($post->ID,$meta);

                    $html.='</div>';
                }
            }
        }

        return $html;
    }

    public function attachment_fields_to_edit($form_fields,$post)
    {
        unset($form_fields['wpvivid_imgoptim']);

        if(is_multisite())
        {
            $info =$this->get_main_option('wpvivid_pro_user');

            if($info===false)
            {
                $url='admin.php?page='.strtolower(sprintf('%s-license', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                $html='<div>';
                $html.='<p>'.__('Not set License','wpvivid').'</p>';
                $html.='<a href="'.$url.'">'.__('Check your Settings','wpvivid').'</a>';
                $html.='</div>';
            }
            else
            {
                $allowed_mime_types = array(
                    'image/jpg',
                    'image/jpeg',
                    'image/png');

                $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

                if ( ! wp_attachment_is_image( $post->ID ) || ! in_array( get_post_mime_type( $post->ID ),$allowed_mime_types ) )
                {
                    $html= 'Not support';
                }
                else
                {
                    $meta=get_post_meta( $post->ID,'wpvivid_image_optimize_meta', true );
                    $site_id=get_current_blog_id();
                    $html='<div class="wpvivid-media-attachment" data-id="'.$post->ID.'" data-site="'.$site_id.'">';
                    $html.=$this->get_mu_optimize_columns($site_id,$post->ID,$meta);
                    $html.='</div>';
                }
            }
        }
        else
        {
            if(get_option('wpvivid_pro_user',false)===false)
            {
                $url='admin.php?page='.strtolower(sprintf('%s-license', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
                $html='<div>';
                $html.='<p>'.__('Not set License','wpvivid').'</p>';
                $html.='<a href="'.$url.'">'.__('Check your Settings','wpvivid').'</a>';
                $html.='</div>';
            }
            else
            {
                $allowed_mime_types = array(
                    'image/jpg',
                    'image/jpeg',
                    'image/png');

                $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

                if ( ! wp_attachment_is_image( $post->ID ) || ! in_array( get_post_mime_type( $post->ID ),$allowed_mime_types ) )
                {
                    $html= 'Not support';
                }
                else
                {
                    $meta=get_post_meta( $post->ID,'wpvivid_image_optimize_meta', true );
                    $html='<div class="wpvivid-media-attachment" data-id="'.$post->ID.'">';
                    $html.=$this->get_optimize_columns($post->ID,$meta);
                    $html.='</div>';
                }
            }
        }

        $form_fields['wpvivid'] = array(
            'label'         => 'WPvivid Imgoptim Pro',
            'input'         => 'html',
            'html'          => $html,
            'show_in_edit'  => true,
            'show_in_modal' => true,
        );

        return $form_fields;
    }

    public function upload_small_file_params($params,$options)
    {
        $option['quality']=isset($options['quality'])?$options['quality']:'lossless';
        if($option['quality']=='custom')
        {
            $custom_quality=isset($options['custom_quality'])?$options['custom_quality']:80;
            $custom_quality=min(99,$custom_quality);
            $custom_quality=max(1,$custom_quality);

            $params['option']['custom_quality']=$custom_quality;
        }

        if(isset($params['type'])&&$params['type']=='gif')
        {
            $optimize_gif_color=isset($options['optimize_gif_color'])?$options['optimize_gif_color']:false;
            $gif_colors=isset($options['gif_colors'])?$options['gif_colors']:64;
            if($optimize_gif_color)
            {
                $params['option']['colors']=$gif_colors;
            }
            else
            {
                $params['option']['colors']=false;
            }
        }
        return $params;
    }

    public function compress_image_without_upload_params($params,$options)
    {
        $option['quality']=isset($options['quality'])?$options['quality']:'lossless';
        if($option['quality']=='custom')
        {
            $custom_quality=isset($options['custom_quality'])?$options['custom_quality']:80;
            $custom_quality=min(99,$custom_quality);
            $custom_quality=max(1,$custom_quality);

            $params['option']['custom_quality']=$custom_quality;
        }

        if(isset($params['type'])&&$params['type']=='gif')
        {
            $optimize_gif_color=isset($options['optimize_gif_color'])?$options['optimize_gif_color']:false;
            $gif_colors=isset($options['gif_colors'])?$options['gif_colors']:64;
            if($optimize_gif_color)
            {
                $params['option']['colors']=$gif_colors;
            }
            else
            {
                $params['option']['colors']=false;
            }
        }
        return $params;
    }

    public function image_optimization_user_info($info)
    {
        $info=get_option('wpvivid_pro_user',false);
        return $info;
    }

    public function skip_file($skip,$filename)
    {
        if($skip)
        {
            return true;
        }

        $exclude_regex=array();
        $options=get_option('wpvivid_optimization_options',array());
        $enable_exclude_file=isset($options['enable_exclude_file'])?$options['enable_exclude_file']:true;
        if($enable_exclude_file)
        {
            $exclude_file=isset($options['exclude_file'])?$options['exclude_file']:'';
            if(!empty($exclude_file))
            {
                $exclude_file=explode("\n", $exclude_file);
                foreach ($exclude_file as $item)
                {
                    $exclude_regex[]='#'.preg_quote($this -> transfer_path($item), '/').'#';
                }
            }
        }

        $enable_exclude_path=isset($options['enable_exclude_path'])?$options['enable_exclude_path']:true;
        if($enable_exclude_path)
        {
            $exclude_path=isset($options['exclude_path'])?$options['exclude_path']:'';
            if(!empty($exclude_path))
            {
                $exclude_path=explode("\n", $exclude_path);
                foreach ($exclude_path as $item)
                {
                    $exclude_regex[]='#'.preg_quote($this -> transfer_path($item), '/').'#';
                }
            }
        }

        if(!empty($exclude_regex))
        {
            if($this->regex_match($exclude_regex,$this -> transfer_path($filename),0))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    private function transfer_path($path)
    {
        $path = str_replace('\\','/',$path);
        $values = explode('/',$path);
        return implode(DIRECTORY_SEPARATOR,$values);
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

    public function allowed_image_auto_optimization($allowed)
    {
        return false;
    }

    public function allowed_image_auto_optimization_ex($allowed)
    {
        if(is_multisite())
        {
            $options =$this->get_main_option('wpvivid_optimization_options');
        }
        else
        {
            $options=get_option('wpvivid_optimization_options',array());
        }


        $auto_optimize_type=isset($options['auto_optimize_type'])?$options['auto_optimize_type']:'upload';
        if($auto_optimize_type=='upload')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function add_auto_opt_id($attachment_id)
    {
        $is_auto=apply_filters('wpvivid_allowed_image_auto_optimization_ex',false);

        if($is_auto)
        {
            $this->auto_opt_ids[$attachment_id]=0;
        }
    }

    public function update_auto_opt_id_status($metadata, $attachment_id)
    {
        if(isset( $this->auto_opt_ids[$attachment_id]))
        {
            if ( ! wp_attachment_is_image( $attachment_id ) )
            {
                unset($this->auto_opt_ids[$attachment_id]);
            }
            $this->auto_opt_ids[$attachment_id]=1;
        }

        return $metadata;
    }

    public function auto_optimize($metadata, $attachment_id)
    {
        set_time_limit(300);

        $is_auto=apply_filters('wpvivid_allowed_image_auto_optimization_ex',false);
        if($is_auto)
        {
            if(isset($this->auto_opt_ids[$attachment_id])&&$this->auto_opt_ids[$attachment_id])
            {
                $mime_type=get_post_mime_type($attachment_id);
                if(is_multisite())
                {
                    $options =$this->get_main_option('wpvivid_optimization_options');
                }
                else
                {
                    $options=get_option('wpvivid_optimization_options',array());
                }
                if($mime_type=='image/jpeg'||$mime_type=='image/jpg'||$mime_type=='image/png'||$mime_type=='image/gif')
                {
                    if($mime_type=='image/gif')
                    {
                        $gif=isset($options['opt_gif'])?$options['opt_gif']:true;
                        if(!$gif)
                        {
                            return $metadata;
                        }
                    }

                    if(is_multisite())
                    {
                        $current_blog_id=get_current_blog_id();
                        if(!is_main_site())
                        {
                            $site_id=get_main_site_id();
                            switch_to_blog($site_id);
                        }

                        $task=new WPvivid_ImgOptim_Task_Ex();
                        $ret=$task->init_mu_schedule_task($current_blog_id,$attachment_id,$options);

                        if($ret['result']=='success')
                        {
                            $task->do_optimize_image();
                        }

                        if(!is_main_site())
                        {
                            restore_current_blog();
                        }
                    }
                    else
                    {
                        $task=new WPvivid_ImgOptim_Task_Ex();
                        $ret=$task->init_schedule_task($attachment_id,$options);

                        if($ret['result']=='success')
                        {
                            $task->do_optimize_image();
                        }
                    }

                    if(function_exists( 'getimagesize' ))
                    {
                        $og_file_path = get_attached_file( $attachment_id );
                        if(!empty($og_file_path))
                        {
                            $size = @getimagesize( $og_file_path );
                            if ( ! $size || ! isset( $size[0], $size[1] ) )
                            {
                            }
                            else
                            {
                                $metadata['width']  = $size[0];
                                $metadata['height'] = $size[1];
                            }

                            $image_filesize = filesize($og_file_path);
                            if($image_filesize)
                            {
                                $metadata['filesize']=$image_filesize;
                            }
                        }
                    }
                }
            }
        }

        return $metadata;
    }

    public function check_auto_schedule()
    {
        if(is_multisite())
        {
            $options =$this->get_main_option('wpvivid_optimization_options');
        }
        else
        {
            $options=get_option('wpvivid_optimization_options',array());
        }

        $auto_optimize_type=isset($options['auto_optimize_type'])?$options['auto_optimize_type']:'upload';
        if($auto_optimize_type=='schedule')
        {
            $auto_schedule_cycles=isset($options['auto_schedule_cycles'])?$options['auto_schedule_cycles']:'wpvivid_5minutes';
            if(!defined( 'DOING_CRON' ))
            {
                if(wp_get_schedule('wpvivid_imgoptim_auto_event')===false)
                {
                    if(wp_schedule_event(time()+30, $auto_schedule_cycles, 'wpvivid_imgoptim_auto_event')===false)
                    {
                        return false;
                    }
                }
            }
        }
        else
        {
            if(!defined( 'DOING_CRON' ))
            {
                if(wp_get_schedule('wpvivid_imgoptim_auto_event')!==false)
                {
                    wp_clear_scheduled_hook('wpvivid_imgoptim_auto_event');
                    $timestamp = wp_next_scheduled('wpvivid_imgoptim_auto_event');
                    wp_unschedule_event($timestamp,'wpvivid_imgoptim_auto_event');
                }
            }
        }


        return true;
    }

    public function imgoptim_auto_event()
    {
        set_time_limit(300);
        if(is_multisite())
        {
            $options =$this->get_main_option('wpvivid_optimization_options');
            $image=$this->get_mu_need_optimize_images();
            if(empty($image))
            {
                $attachment_id=false;
                $current_blog_id=get_current_blog_id();
            }
            else
            {
                $attachment_id=$image['image_id'];
                $current_blog_id=$image['site_id'];
            }

            if($attachment_id!==false)
            {
                if(!is_main_site())
                {
                    $site_id=get_main_site_id();
                    switch_to_blog($site_id);
                }

                $task=new WPvivid_ImgOptim_Task_Ex();

                if($task->is_image_optimizing())
                {
                    return;
                }

                $ret=$task->init_mu_schedule_task($current_blog_id,$attachment_id,$options);

                if($ret['result']=='success')
                {
                    $task->do_optimize_image();
                }

                if(!is_main_site())
                {
                    restore_current_blog();
                }
            }
        }
        else
        {
            $options=get_option('wpvivid_optimization_options',array());
            $attachment_id=$this->get_need_optimize_images();

            if($attachment_id!==false)
            {
                $task=new WPvivid_ImgOptim_Task_Ex();
                if($task->is_image_optimizing())
                {
                    return;
                }

                $ret=$task->init_schedule_task($attachment_id,$options);

                if($ret['result']=='success')
                {
                    $task->do_optimize_image();
                }
            }
        }
    }

    public function get_mu_need_optimize_images()
    {
        $need_optimize_images = array();
        $blogs_ids=get_sites();
        $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );
        $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);
        foreach( $blogs_ids as $site )
        {
            $site_id = get_object_vars($site)["blog_id"];

            switch_to_blog( $site_id );

            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => - 1,
            );

            $query_images = new WP_Query( $query_images_args );

            foreach ( $query_images->posts as $image )
            {
                $type=get_post_mime_type($image->ID);

                if (in_array( $type, $mime_types, true ))
                {
                    $need_optimize_image['site_id']=$site_id;
                    $need_optimize_image['image_id']=$image->ID;
                    $need_optimize_images[]=$need_optimize_image;
                }
            }
        }

        $need_optimize_image=array();
        if(!empty($need_optimize_images))
        {
            foreach ($need_optimize_images as $image)
            {
                if($this->is_mu_image_optimized($image))
                    continue;
                $need_optimize_image['site_id']=$image['site_id'];
                $need_optimize_image['image_id']=$image['image_id'];
                break;
            }
        }

        restore_current_blog();
        return $need_optimize_image;
    }

    public function is_mu_image_optimized($image)
    {
        $post_id=$image['image_id'];
        $site_id=$image['site_id'];

        switch_to_blog( $site_id );

        $image_opt_meta=get_post_meta($post_id,'wpvivid_image_optimize_meta',true);
        $meta = wp_get_attachment_metadata( $post_id, true );

        if(!empty($image_opt_meta)&&isset($image_opt_meta['size'])&&!empty($image_opt_meta['size']))
        {
            if(!empty($meta['sizes']))
            {
                foreach ($meta['sizes'] as $size_key => $size_data)
                {
                    if(isset($image_opt_meta['size'][$size_key]))
                    {
                        if(!isset($image_opt_meta['size'][$size_key]['opt_status'])||$image_opt_meta['size'][$size_key]['opt_status']==0)
                        {
                            return false;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
            }
            else
            {
                foreach ($image_opt_meta['size'] as $size_key => $size_data)
                {
                    if(!isset($size_data['opt_status'])||$size_data['opt_status']==0)
                    {
                        return false;
                    }
                }
            }

            return apply_filters('wpvivid_is_image_optimized',true,$post_id);
        }
        else
        {
            return false;
        }
    }

    public function get_need_optimize_images()
    {
        $task=new WPvivid_ImgOptim_Task_Ex();

        $need_optimize_images=$task->get_need_optimize_images();

        if(!empty($need_optimize_images))
        {
            foreach ($need_optimize_images as $image)
            {
                if($task->is_image_optimized($image))
                    continue;
               return $image;
            }
        }

        return false;
    }

    public function cron_schedules($schedules)
    {
        if(!isset($schedules["wpvivid_10minutes"]))
        {
            $schedules["wpvivid_10minutes"] = array(
                'interval' => 600,
                'display' => __('Every 10 minutes'));
        }

        if(!isset($schedules["wpvivid_9minutes"]))
        {
            $schedules["wpvivid_9minutes"] = array(
                'interval' => 540,
                'display' => __('Every 9 minutes'));
        }

        if(!isset($schedules["wpvivid_8minutes"]))
        {
            $schedules["wpvivid_8minutes"] = array(
                'interval' => 480,
                'display' => __('Every 8 minutes'));
        }

        if(!isset($schedules["wpvivid_7minutes"]))
        {
            $schedules["wpvivid_7minutes"] = array(
                'interval' => 420,
                'display' => __('Every 7 minutes'));
        }

        if(!isset($schedules["wpvivid_6minutes"]))
        {
            $schedules["wpvivid_6minutes"] = array(
                'interval' => 360,
                'display' => __('Every 6 minutes'));
        }

        if(!isset($schedules["wpvivid_5minutes"]))
        {
            $schedules["wpvivid_5minutes"] = array(
                'interval' => 300,
                'display' => __('Every 5 minutes'));
        }

        if(!isset($schedules["wpvivid_4minutes"]))
        {
            $schedules["wpvivid_4minutes"] = array(
                'interval' => 240,
                'display' => __('Every 4 minutes'));
        }

        if(!isset($schedules["wpvivid_3minutes"]))
        {
            $schedules["wpvivid_3minutes"] = array(
                'interval' => 180,
                'display' => __('Every 3 minutes'));
        }

        if(!isset($schedules["wpvivid_2minutes"]))
        {
            $schedules["wpvivid_2minutes"] = array(
                'interval' => 120,
                'display' => __('Every 2 minutes'));
        }

        return $schedules;
    }

    public function skip_size($skip,$filename,$size_key)
    {
        if($skip)
        {
            return true;
        }

        $options =$this->get_main_option('wpvivid_optimization_options');

        if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
        {
            return $options['skip_size'][$size_key];
        }

        return false;
    }

    public function get_main_option($option_name)
    {
        if(is_multisite())
        {
            if(is_main_site())
            {
                $options=get_option($option_name,array());
            }
            else
            {
                $site_id=get_main_site_id();
                $options = get_blog_option($site_id,$option_name, array());
            }
        }
        else
        {
            $options=get_option($option_name,array());
        }
        return $options;
    }

    public function update_main_option($option_name,$option_value)
    {
        if(is_multisite())
        {
            if(is_main_site())
            {
                return update_option($option_name,$option_value,'no');
            }
            else
            {
                $site_id=get_main_site_id();
                return update_blog_option($site_id,$option_name, $option_value);
            }
        }
        else
        {
            return update_option($option_name,$option_value,'no');
        }
    }
}