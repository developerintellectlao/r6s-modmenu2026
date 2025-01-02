<?php

/**
 * WPvivid addon: yes
 * Addon Name: wpvivid-imgoptim-pro
 * Description: Pro
 * Version: 2.2.27
 */
if (!defined('WPVIVID_BACKUP_PRO_PLUGIN_DIR'))
{
    die;
}
class WPvivid_ImgOptim_Task_Ex
{
    public $task;
    public $log=false;
    public $mu=false;
    public function __construct()
    {
        if(is_multisite())
        {
            $this->mu=new WPvivid_MU_ImgOptim($this);
        }
        else
        {
            $this->mu=false;
        }
        $this->check_table();
    }

    public function cancel()
    {
        update_option('wpvivid_image_opt_task_cancel',true,'no');
    }

    public function init_task($options=array())
    {
        $this->task=array();
        $type = isset($options['optimize_type']) ? $options['optimize_type'] : 'media_library';

        if($type=='media_library')
        {
            if(is_multisite()&&$this->mu!==false)
            {
                $need_optimize_images=$this->mu->get_mu_need_optimize_images();
                $this->task['images']=array();
                if(!empty($need_optimize_images))
                {
                    $id=1;
                    foreach ($need_optimize_images as $image)
                    {
                        if($this->mu->is_mu_image_optimized($image))
                            continue;
                        $sub_task['id']=$id;
                        $sub_task['site_id']=$image['site_id'];
                        $sub_task['image_id']=$image['image_id'];
                        $sub_task['finished']=0;
                        $this->task['images'][$id]=$sub_task;
                        $id++;
                    }
                }
            }
            else
            {
                $ret_images=$this->get_need_optimize_images_ex();
                $need_optimize_images=$ret_images['images'];
                if($ret_images['continue']===1)
                {
                    $ret['result']='success';
                    $ret['continue']=1;
                    return $ret;
                }
                $this->task['images']=array();

                if(!empty($need_optimize_images))
                {
                    foreach ($need_optimize_images as $image)
                    {
                        if($this->is_image_optimized($image))
                            continue;
                        $sub_task['id']=$image;
                        $sub_task['finished']=0;
                        $this->task['images'][$image]=$sub_task;
                    }
                }
            }

            if(empty($this->task['images']))
            {
                $ret['result']='failed';
                $ret['error']='All image(s) optimized successfully.';
                update_option('wpvivid_image_opt_task',$this->task,'no');
                return $ret;
            }
        }
        else if($type=='custom_folders')
        {
            $folders = isset($options['custom_folders']) ? $options['custom_folders'] : '';
            $folders=$this->get_custom_folders($folders);
            $need_optimize_images=$this->get_folders_need_optimize_images($folders);

            $this->task['images']=array();
            if(!empty($need_optimize_images))
            {
                $id=1;
                foreach ($need_optimize_images as $path)
                {
                    if($this->is_folders_image_optimized($path))
                        continue;
                    $sub_task['id']=$id;
                    $sub_task['path']=$path;
                    $sub_task['finished']=0;
                    $this->task['images'][$id]=$sub_task;
                    $id++;
                }
            }
            if(empty($this->task['images']))
            {
                $ret['result']='failed';
                $ret['error']='All image(s) optimized successfully.';
                update_option('wpvivid_image_opt_task',$this->task,'no');
                return $ret;
            }
        }
        else
        {
            $this->task=apply_filters('wpvivid_init_custom_task',$options);
        }

        $options=apply_filters('wpvivid_optimization_custom_option',$options);

        $this->task['options']=$options;
        $this->task['optimize_type']=$type;
        $this->task['status']='running';
        $this->task['last_update_time']=time();
        $this->task['retry']=0;
        $this->task['log']=uniqid('wpvivid-');
        $this->log=new WPvivid_Image_Optimize_Log();
        $this->log->CreateLogFile();
        $this->task['error']='';
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $ret['result']='success';
        $ret['continue']=0;
        return $ret;
    }

    public function init_schedule_task($id,$options=array())
    {
        $this->task=array();

        if($this->is_image_optimized($id))
        {
            $ret['result']='failed';
            $ret['error']='All image(s) optimized successfully.';
            update_option('wpvivid_image_opt_task',$this->task,'no');
            return $ret;
        }
        $sub_task['id']=$id;
        $sub_task['finished']=0;
        $this->task['images'][$id]=$sub_task;
        $options=apply_filters('wpvivid_optimization_custom_option',$options);
        $this->task['options']=$options;
        $this->task['status']='running';
        $this->task['schedule']=1;
        $this->task['last_update_time']=time();
        $this->task['retry']=0;
        $this->task['log']=uniqid('wpvivid-');
        $this->log=new WPvivid_Image_Optimize_Log();
        $this->log->CreateLogFile();
        $this->task['error']='';
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $ret['result']='success';
        return $ret;
    }

    public function init_mu_schedule_task($site_id,$id,$options=array())
    {
        $this->task=array();

        $data['site_id']=$site_id;
        $data['image_id']=$id;
        if($this->mu->is_mu_image_optimized($data))
        {
            $ret['result']='failed';
            $ret['error']='All image(s) optimized successfully.';
            update_option('wpvivid_image_opt_task',$this->task,'no');
            return $ret;
        }
        $sub_task['id']=1;
        $sub_task['finished']=0;
        $sub_task['site_id']=$data['site_id'];
        $sub_task['image_id']=$data['image_id'];
        $this->task['images'][1]=$sub_task;
        $options=apply_filters('wpvivid_optimization_custom_option',$options);
        $this->task['options']=$options;
        $this->task['status']='running';
        $this->task['schedule']=1;
        $this->task['last_update_time']=time();
        $this->task['retry']=0;
        $this->task['log']=uniqid('wpvivid-');
        $this->log=new WPvivid_Image_Optimize_Log();
        $this->log->CreateLogFile();
        $this->task['error']='';
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $ret['result']='success';
        return $ret;
    }

    public function is_image_optimized($post_id)
    {
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

    public function is_image_progressing($post_id)
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        if(empty($this->task))
        {
            return false;
        }

        if(isset($this->task['images']))
        {
            if(!array_key_exists($post_id,$this->task['images']))
            {
                return false;
            }

            if(isset($this->task['status']))
            {
                if($this->task['status']=='error')
                {
                    return false;
                }
                else if($this->task['status']=='finished')
                {
                    return false;
                }
                else if($this->task['status']=='completed')
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
        else
        {
            return false;
        }
    }

    public function is_mu_image_progressing($site_id,$id)
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        if(empty($this->task))
        {
            return false;
        }

        if(isset($this->task['images']))
        {
            $bfind=false;
            foreach ($this->task['images'] as $image)
            {
                if($image['image_id']==$id&&$image['site_id']==$site_id)
                {
                    $bfind=true;
                }
            }
            if(!$bfind)
            {
                return false;
            }

            if(isset($this->task['status']))
            {
                if($this->task['status']=='error')
                {
                    return false;
                }
                else if($this->task['status']=='finished')
                {
                    return false;
                }
                else if($this->task['status']=='completed')
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
        else
        {
            return false;
        }
    }

    public function is_image_optimizing()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        if(empty($this->task))
        {
            return false;
        }

        if(isset($this->task['images']))
        {
            $finished=true;
            foreach ($this->task['images'] as $image)
            {
                if($image['finished']==0)
                {
                    $finished=false;
                    break;
                }
            }

            if($finished)
            {
                return false;
            }
            else
            {
                if(isset($this->task['status']))
                {
                    if($this->task['status']=='error')
                    {
                        return false;
                    }
                    else if($this->task['status']=='finished')
                    {
                        return false;
                    }
                    else
                    {
                        if(isset($this->task['last_update_time']))
                        {
                            if(time()-$this->task['last_update_time']>180)
                            {
                                $this->task['last_update_time']=time();
                                $this->task['retry']++;
                                $this->task['status']='timeout';
                                update_option('wpvivid_image_opt_task',$this->task,'no');
                                if($this->task['retry']<3)
                                {
                                   return true;
                                }
                                else
                                {
                                    return false;
                                }
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
                }
                else
                {
                    return false;
                }
            }
        }
        else
        {
            return false;
        }
    }

    public function WriteLog($log,$type)
    {
        if (is_a($this->log, 'WPvivid_Image_Optimize_Log'))
        {
            $this->log->WriteLog($log,$type);
        }
        else
        {
            $this->log=new WPvivid_Image_Optimize_Log();
            $this->log->OpenLogFile();
            $this->log->WriteLog($log,$type);
        }
        $this->task=get_option('wpvivid_image_opt_task',array());
        $this->task['last_log']=$log;
        update_option('wpvivid_image_opt_task',$this->task,'no');
    }

    public function get_last_log()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());
        if(empty($this->task)||!isset($this->task['last_log']))
        {
            return 'Optimizing images...';
        }
        else
        {
            return $this->task['last_log'];
        }
    }

    public function get_custom_folders($folders)
    {
        $folders=explode("\n", $folders);
        $custom_folders=array();
        foreach ($folders as $item)
        {
            if(empty($item))
            {
                continue;
            }
            if(file_exists($item))
            {
                $custom_folders[]=$item;
            }
            else
            {
                $path=$this->transfer_path(WP_CONTENT_DIR.$item);
                if(file_exists($path))
                {
                    $custom_folders[]=$path;
                }
            }
        }
        return $custom_folders;
    }

    public function get_need_optimize_images()
    {
        $args = array (
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'posts_per_page' => 0,
        ) ;
        $attatchments = new WP_Query ($args) ;
        $count = $attatchments->found_posts ;

        $offset=0;
        $page=500;

        $images = array();

        while ($offset<$count)
        {
            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'offset'=>$offset,
                'posts_per_page' => $page,
            );

            $query_images = new WP_Query( $query_images_args );
            $offset+=$page;

            $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );

            $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);

            foreach ( $query_images->posts as $image )
            {
                $type=get_post_mime_type($image->ID);

                if (in_array( $type, $mime_types, true ))
                {
                    $images[] = $image->ID ;
                }
            }
        }

        return $images;
    }

    public function get_need_optimize_images_ex()
    {
        $args = array (
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'posts_per_page' => 0,
        ) ;
        $attatchments = new WP_Query ($args) ;
        $count = $attatchments->found_posts ;


        $need_optimize_array=get_option('wpvivid_get_need_optimize_images_ex', array());
        $offset=isset($need_optimize_array['offset'])?$need_optimize_array['offset']:0;
        $page=2000;

        $images=isset($need_optimize_array['images'])?$need_optimize_array['images']:array();

        $start_time=time();
        while ($offset<$count)
        {
            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'offset'=>$offset,
                'posts_per_page' => $page,
            );

            $query_images = new WP_Query( $query_images_args );
            $offset+=$page;

            $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );

            $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);

            foreach ( $query_images->posts as $image )
            {
                $type=get_post_mime_type($image->ID);

                if (in_array( $type, $mime_types, true ))
                {
                    $images[] = $image->ID ;
                }
            }

            $current_time=time();
            if($current_time - $start_time >= 21)
            {
                $need_optimize_array=array();
                $need_optimize_array['images']=isset($images)?$images:array();
                $need_optimize_array['offset']=$offset;
                update_option('wpvivid_get_need_optimize_images_ex', $need_optimize_array,'no');
                $ret['images']=$images;
                $ret['continue']=1;
                return $ret;
            }
        }

        $ret['images']=$images;
        $ret['continue']=0;
        return $ret;
    }

    public function get_folders_need_optimize_images($folders)
    {
        $exclude_regex_file=array();
        $exclude_regex_folder=array();
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
                    $exclude_regex_file[]='#'.preg_quote($this -> transfer_path($item), '/').'#';
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
                    $exclude_regex_folder[]='#'.preg_quote($this -> transfer_path($item), '/').'#';
                }
            }
        }

        $files=array();
        foreach ($folders as $folder)
        {
            $this->getFileLoop($files,$folder,$exclude_regex_file,$exclude_regex_folder);
        }
        return $files;
    }

    public function is_folders_image_optimized($path)
    {
        $meta=$this->get_image_opt_meta($path);
        if($meta===false)
            return false;

        if(isset($meta['opt_status'])&&$meta['opt_status']==1)
            return true;
        else
            return false;
    }

    public function check_image($file)
    {
        if ( ! file_exists( $file ))
        {
            return false;
        }
        $extension = array('jpg', 'jpeg', 'png' );

        $extension=apply_filters('wpvivid_imgoptim_support_extension',$extension);

        $ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );

        if ( !in_array( $ext, $extension, true ) )
        {
            return false;
        }
        $upload_dir  = wp_upload_dir();
        $upload_path = $upload_dir['basedir'];

        // Get the base path of file.
        $base_dir = dirname( $file );
        if ( $base_dir === $upload_path )
        {
            return false;
        }
        return true;
    }

    public function getFileLoop(&$files,$path,$exclude_regex_file,$exclude_regex_folder)
    {
        if(is_dir($path))
        {
            $handler = opendir($path);
            if($handler!==false)
            {
                while (($filename = readdir($handler)) !== false)
                {
                    if ($filename != "." && $filename != "..")
                    {
                        if (is_dir($path . DIRECTORY_SEPARATOR . $filename))
                        {
                            if ($this->regex_match($exclude_regex_folder, $path . DIRECTORY_SEPARATOR . $filename, 0))
                            {
                                $this->getFileLoop($files, $path . DIRECTORY_SEPARATOR . $filename,$exclude_regex_file,$exclude_regex_folder);
                            }
                        }
                        else
                        {
                            if($this->regex_match($exclude_regex_file, $filename, 0))
                            {
                                if($this->check_image($path . DIRECTORY_SEPARATOR . $filename))
                                {
                                    $files[] = $path . DIRECTORY_SEPARATOR . $filename;
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

    public function do_optimize_image()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());
        $this->task['status']='running';
        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $cancel=get_option('wpvivid_image_opt_task_cancel',false);
        if($cancel)
        {
            $this->WriteLog('Cancel bulk optimization.','notice');

            $this->task['status']='finished';
            $this->task['last_update_time']=time();
            update_option('wpvivid_image_opt_task',$this->task,'no');
            update_option('wpvivid_image_opt_task_cancel',false,'no');

            $server=new WPvivid_Image_Optimize_Connect_server_Ex();
            $info=get_option('wpvivid_pro_user',false);
            if($info===false)
            {
                $ret['result']='failed';
                $ret['error']='Need login';
                return $ret;
            }
            $user_info=$info['token'];
            $server->delete_cache($user_info);

            $ret['result']='success';
            return $ret;
        }

        if(empty($this->task)||!isset($this->task['images']))
        {
            $this->WriteLog('Cannot find optimization task, task quit.','notice');
            $ret['result']='success';
            return $ret;
        }

        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');
        $this->task['optimize_type'] = isset($this->task['optimize_type']) ? $this->task['optimize_type'] : 'media_library';
        if($this->task['optimize_type']=='media_library')
        {
            if(is_multisite()&&$this->mu!==false)
            {
                $ret=$this->mu->do_mu_unoptimized_image();
            }
            else
            {
                $ret=$this->do_unoptimized_image();
            }


            if($ret['result']=='success')
            {
                if($ret['unoptimized_image'])
                {
                    $ret['result']='success';
                    return $ret;
                }

                if(!$ret['unoptimized_image'])
                {
                    $this->finish_optimize_image();
                }
                $ret['result']='success';
                return $ret;
            }
            else
            {
                $server=new WPvivid_Image_Optimize_Connect_server_Ex();

                $info=get_option('wpvivid_pro_user',false);
                if($info===false)
                {
                    $ret['result']='failed';
                    $ret['error']='Need login';
                    return $ret;
                }

                $user_info=$info['token'];

                $server->delete_cache($user_info);
                return $ret;
            }

        }
        else
        {
            $ret=$this->do_folders_unoptimized_image();

            if($ret['result']=='success')
            {
                if($ret['unoptimized_image'])
                {
                    $ret['result']='success';
                    return $ret;
                }
                else
                {
                    $this->finish_folders_optimize_image();
                }
                $ret['result']='success';
                return $ret;
            }
            else
            {
                $server=new WPvivid_Image_Optimize_Connect_server_Ex();

                $info=get_option('wpvivid_pro_user',false);
                if($info===false)
                {
                    $ret['result']='failed';
                    $ret['error']='Need login';
                    return $ret;
                }

                $user_info=$info['token'];

                $server->delete_cache($user_info);
                return $ret;
            }
        }

    }

    public function do_folders_unoptimized_image()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());
        $sub_task_id=false;
        $path='';
        foreach ($this->task['images'] as $image)
        {
            if($image['finished']==0)
            {
                $sub_task_id=$image['id'];
                $path=$image['path'];
                break;
            }
        }

        if($sub_task_id===false)
        {
            $ret['result']='success';
            $ret['unoptimized_image']=false;
            return $ret;
        }

        $this->task['status']='running';
        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $this->WriteLog('Start optimizing file:'.basename($path),'notice');

        $ret=$this->optimize_folders_image($path);

        if($ret['result']=='success')
        {
            $this->WriteLog('Optimizing file:'.basename($path).' succeeded.','notice');

            $this->task['images'][$sub_task_id]['finished']=1;
            $this->task['status']='completed';
            $this->task['last_update_time']=time();
            $this->task['retry']=0;
            update_option('wpvivid_image_opt_task',$this->task,'no');
            $ret['result']='success';
            $ret['unoptimized_image']=true;
            return $ret;
        }
        else
        {
            $this->WriteLog('Optimizing image failed. Error:'.$ret['error'],'error');
            $this->task['status']='error';
            $this->task['error']=$ret['error'];
            $this->task['last_update_time']=time();
            update_option('wpvivid_image_opt_task',$this->task,'no');
            return $ret;
        }
    }

    public function finish_folders_optimize_image()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());
        $this->task['status']='finished';
        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $server=new WPvivid_Image_Optimize_Connect_server_Ex();
        $info=get_option('wpvivid_pro_user',false);
        if($info===false)
        {
            $ret['result']='failed';
            $ret['error']='Need login';
            return $ret;
        }

        $user_info=$info['token'];
        $server->delete_cache($user_info);


        $ret['result']='success';
        return $ret;
    }

    public function optimize_folders_image($path)
    {
        $image_opt_meta=$this->get_image_opt_meta($path);
        if(empty($image_opt_meta))
        {
            $image_opt_meta=$this->init_folder_image_opt_meta($path);
        }

        $image_opt_meta['last_update_time']=time();
        $this->update_image_opt_meta($path,$image_opt_meta);

        $retry=0;

        $ret['result']='success';

        if($image_opt_meta['sum']['options']['backup'])
        {
            $this->WriteLog('Start backing up image(s).','notice');
            $this->backup_folders_image($path);
            $image_opt_meta=$this->get_image_opt_meta($path);
        }

        $webp_convert=isset($this->task['options']['webp']['convert'])?$this->task['options']['webp']['convert']:false;

        if($image_opt_meta['opt_status']==0)
        {
            if(!file_exists($path))
            {
                $this->WriteLog('Skip not exist file: '.$path,'notice');
                $ret['result']='success';
                return $ret;
            }

            if(filesize($path) == 0)
            {
                $this->WriteLog('Skip file: '.$path.', file size: 0.','notice');
                $ret['result']='success';
                return $ret;
            }

            while ($retry<3)
            {
                $this->WriteLog('Start compressing image '.basename($path).', file size: '.filesize($path),'notice');
                $size=filesize($path);
                $type=$this->get_file_type($path);
                $ret=$this->compress_image($type,$path,$path,$this->task['options']);
                if($ret['result']=='failed')
                {
                    $this->WriteLog('Compressing image '.basename($path).' failed. Error:'.$ret['error'],'notice');
                    if(isset($ret['remain'])&&$ret['remain']==false)
                    {
                        return $ret;
                    }

                    $retry++;
                    $this->WriteLog('Start retrying optimization. Count:'.$retry,'notice');
                }
                else
                {
                    $this->WriteLog('Compressing image '.basename($path).' succeeded.','notice');
                    clearstatcache();
                    $image_opt_meta['sum']['opt_size']=filesize($path);
                    $image_opt_meta['opt_status']=1;
                    $image_opt_meta['last_update_time']=time();

                    if($webp_convert)
                    {
                        if(isset($ret['webp_result'])&&$ret['webp_result']=='success')
                        {
                            $this->WriteLog('Converting image '.basename($path).' succeeded.','notice');
                            $image_opt_meta['webp_status']=1;
                        }
                        else
                        {
                            $this->WriteLog('Converting image '.basename($path).' failed.','notice');
                        }
                    }

                    $this->update_image_opt_meta($path,$image_opt_meta);
                    break;
                }
            }

            if($ret['result']=='failed')
            {
                $this->WriteLog('Compressing image '.basename($path).' failed. Error:'.$ret['error'],'error');
                return $ret;
            }
        }
        else if($webp_convert&&$image_opt_meta['webp_status']==0)
        {
            while ($retry<3)
            {
                $this->WriteLog('Start Converting image '.basename($path),'notice');
                $type=$this->get_file_type($path);
                $webp_file =$path.'.webp';
                $ret=$this->convert_webp($type,$path,$webp_file,$this->task['options']);
                if($ret['result']=='failed')
                {
                    $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                    if(isset($ret['remain'])&&$ret['remain']==false)
                    {
                        return $ret;
                    }

                    $retry++;
                    $this->WriteLog('Start retrying converting. Count:'.$retry,'notice');
                }
                else
                {
                    $this->WriteLog('Converting image '.basename($path).' succeeded.','notice');
                    $retry=0;
                    clearstatcache();

                    $image_opt_meta['webp_status']=1;

                    $this->update_image_opt_meta($path,$image_opt_meta);
                    break;
                }
            }

            if($ret['result']=='failed')
            {
                $this->WriteLog('Converting image '.basename($path).' failed. Error:'.$ret['error'],'error');
                return $ret;
            }
        }

        $image_opt_meta=apply_filters('wpvivid_do_file_optimized',$image_opt_meta,$path);
        $image_opt_meta['last_update_time']=time();
        $this->update_image_opt_meta($path,$image_opt_meta);

        if($ret['result']=='success')
        {
            do_action('wpvivid_do_after_file_optimized',$path);
        }
        else
        {
            $this->WriteLog('Optimize images '.basename($path).' failed.Error:'.$ret['error'],'notice');
        }

        $ret['result']='success';
        return $ret;
    }

    public function optimize_image($image_id)
    {
        $files=array();

        $file_path = get_attached_file( $image_id );
        $meta = wp_get_attachment_metadata( $image_id, true );
        $image_opt_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
        if(empty($image_opt_meta))
        {
            $image_opt_meta=$this->init_image_opt_meta($image_id);
        }

        if(empty($meta['sizes']))
        {
            if(apply_filters('wpvivid_imgoptim_og_skip_file_ex',false,$file_path))
            {
                $this->WriteLog('Skip file '.$file_path,'notice');
            }
            else
            {
                $files['og']=$file_path;
                if(!isset($image_opt_meta['size']['og']))
                {
                    $image_opt_meta['size']['og']['og_size']=filesize($file_path);
                    $image_opt_meta['sum']['og_size']+=$image_opt_meta['size']['og']['og_size'];
                    $image_opt_meta['size']['og']['opt_size']=0;
                    $image_opt_meta['size']['og']['opt_status']=0;
                    $image_opt_meta=apply_filters('wpvivid_imgoptim_generate_meta',$image_opt_meta,$image_id,'og');
                }
            }
        }
        else
        {
            foreach ( $meta['sizes'] as $size_key => $size_data )
            {
                $filename= path_join( dirname( $file_path ), $size_data['file'] );

                if(apply_filters('wpvivid_imgoptim_skip_file_ex',false,$filename,$size_key))
                {
                    $this->WriteLog('Skip file test '.$filename,'notice');
                    continue;
                }

                $files[$size_key] =$filename;
                if(!isset($image_opt_meta['size'][$size_key]))
                {
                    $image_opt_meta['size'][$size_key]['og_size']=filesize($filename);
                    $image_opt_meta['sum']['og_size']+=$image_opt_meta['size'][$size_key]['og_size'];
                    $image_opt_meta['size'][$size_key]['opt_size']=0;
                    $image_opt_meta['size'][$size_key]['opt_status']=0;
                    $image_opt_meta=apply_filters('wpvivid_imgoptim_generate_meta',$image_opt_meta,$image_id,$size_key);
                }
            }

            if(!in_array($file_path,$files))
            {
                if(apply_filters('wpvivid_imgoptim_og_skip_file_ex',false,$file_path))
                {
                    $this->WriteLog('Skip file '.$file_path,'notice');
                }
                else
                {
                    $files['og']=$file_path;
                    if(!isset($image_opt_meta['size']['og']))
                    {
                        $image_opt_meta['size']['og']['og_size']=filesize($file_path);
                        $image_opt_meta['sum']['og_size']+=$image_opt_meta['size']['og']['og_size'];
                        $image_opt_meta['size']['og']['opt_size']=0;
                        $image_opt_meta['size']['og']['opt_status']=0;
                        $image_opt_meta=apply_filters('wpvivid_imgoptim_generate_meta',$image_opt_meta,$image_id,'og');
                    }
                }
            }
        }

        $image_opt_meta['last_update_time']=time();
        update_post_meta($image_id,'wpvivid_image_optimize_meta',$image_opt_meta);

        if($image_opt_meta['sum']['options']['backup'])
        {
            $this->WriteLog('Start backing up image(s).','notice');
            $this->backup($files,$image_id);
        }

        if($this->is_resize($image_id))
        {
            $this->WriteLog('Start resizing image id:'.$image_id,'notice');
            $this->resize($image_id);
        }

        $only_resize=isset($this->task['options']['only_resize'])?$this->task['options']['only_resize']:false;

        if($only_resize)
        {
            $ret['result']='success';
            $this->task['images'][$image_id]['finished']=1;
            $this->task['status']='completed';
            $this->task['last_update_time']=time();
            $this->task['retry']=0;
            update_option('wpvivid_image_opt_task',$this->task,'no');
            return $ret;
        }

        $retry=0;

        if(empty($files))
        {
            $ret['result']='success';
            $this->task['images'][$image_id]['finished']=1;
            $this->task['status']='completed';
            $this->task['last_update_time']=time();
            $this->task['retry']=0;
            update_option('wpvivid_image_opt_task',$this->task,'no');
            return $ret;
        }
        $ret['result']='success';

        $option_webp_convert=isset($this->task['options']['webp']['convert'])?$this->task['options']['webp']['convert']:false;

        $mime_type=get_post_mime_type($image_id);
        if($mime_type=='image/jpeg')
        {
            $type='jpg';
        }
        else if($mime_type=='image/jpg')
        {
            $type='jpg';
        }
        else if($mime_type=='image/png')
        {
            $type='png';
        }
        else
        {
            $type=apply_filters('wpvivid_imgoptim_get_file_type','',$mime_type);
        }

        $max_allowed_optimize_count=isset($this->task['options']['max_allowed_optimize_count'])?$this->task['options']['max_allowed_optimize_count']:15;

        if(isset($this->task['schedule'])&&$this->task['schedule'])
        {
            $max_allowed_optimize_count=999;
        }

        $optimize_count=0;

        foreach ($files as $size_key=>$file)
        {
            if(!file_exists($file))
            {
                $this->WriteLog('Skip not exist file: '.$file,'notice');
                $files_not_exist = get_option('wpvivid_imgoptm_file_not_exist', array());
                if(!in_array($file, $files_not_exist))
                {
                    $files_not_exist[] = $file;
                    update_option('wpvivid_imgoptm_file_not_exist', $files_not_exist,'no');
                    update_option('wpvivid_hide_imgoptm_not_exist_notice', '0', 'no');
                }
                continue;
            }

            if(filesize($file) == 0)
            {
                $this->WriteLog('Skip file: '.$file.', file size: 0, image id: '.$image_id,'notice');
                continue;
            }

            set_time_limit(180);
            if(apply_filters('wpvivid_imgoptim_opt_skip_file_ex',false,$file,$image_opt_meta,$size_key))
            {
                $this->WriteLog('Skip optimized size '.$size_key,'notice');
                continue;
            }

            if(!isset($image_opt_meta['size'][$size_key]['webp_status']))
            {
                $image_opt_meta['size'][$size_key]['webp_status']=0;
            }

            if($type=='gif')
            {
                $webp_convert=isset($this->task['options']['webp']['gif_convert'])?$this->task['options']['webp']['gif_convert']:false;
            }
            else
            {
                $webp_convert=$option_webp_convert;
            }

            $ret['result']='success';
            if(!empty($type))
            {
                if($image_opt_meta['size'][$size_key]['opt_status']==0)
                {
                    while ($retry<3)
                    {
                        $this->WriteLog('Start compressing image '.basename($file).', file size: '.filesize($file),'notice');
                        $ret=$this->compress_image($type,$file,$file,$this->task['options']);

                        $this->task['status']='running';
                        $this->task['last_update_time']=time();
                        update_option('wpvivid_image_opt_task',$this->task,'no');

                        if($ret['result']=='failed')
                        {
                            $this->WriteLog('Compressing image '.basename($file).' failed. Error:'.$ret['error'],'notice');
                            if(isset($ret['remain'])&&$ret['remain']==false)
                            {
                                return $ret;
                            }

                            $retry++;
                            $this->WriteLog('Start retrying optimization. Count:'.$retry,'notice');
                        }
                        else
                        {
                            $this->WriteLog('Compressing image '.basename($file).' succeeded.','notice');
                            $retry=0;
                            clearstatcache();
                            $image_opt_meta['size'][$size_key]['opt_size']=filesize($file);
                            $image_opt_meta['sum']['opt_size']+=$image_opt_meta['size'][$size_key]['opt_size'];
                            $image_opt_meta['size'][$size_key]['opt_status']=1;
                            $image_opt_meta['last_update_time']=time();

                            if($webp_convert)
                            {
                                if(isset($ret['webp_result'])&&$ret['webp_result']=='success')
                                {
                                    $this->WriteLog('Converting image '.basename($file).' succeeded.','notice');
                                    $image_opt_meta['size'][$size_key]['webp_status']=1;
                                }
                                else
                                {
                                    $this->WriteLog('Converting image '.basename($file).' failed.','notice');
                                }
                            }

                            update_post_meta($image_id,'wpvivid_image_optimize_meta',$image_opt_meta);
                            break;
                        }
                    }

                    if($ret['result']=='failed')
                    {
                        $this->WriteLog('Compressing image '.basename($file).' failed. Error:'.$ret['error'],'error');
                        return $ret;
                    }

                    $optimize_count++;
                }
                else if($webp_convert&&$image_opt_meta['size'][$size_key]['webp_status']==0)
                {
                    while ($retry<3)
                    {
                        $this->WriteLog('Start Converting image '.basename($file),'notice');
                        $webp_file =$file.'.webp';
                        $ret=$this->convert_webp($type,$file,$webp_file,$this->task['options']);

                        $this->task['status']='running';
                        $this->task['last_update_time']=time();
                        update_option('wpvivid_image_opt_task',$this->task,'no');

                        if($ret['result']=='failed')
                        {
                            $this->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
                            if(isset($ret['remain'])&&$ret['remain']==false)
                            {
                                return $ret;
                            }

                            $retry++;
                            $this->WriteLog('Start retrying converting. Count:'.$retry,'notice');
                        }
                        else
                        {
                            $this->WriteLog('Converting image '.basename($file).' succeeded.','notice');
                            $retry=0;
                            clearstatcache();

                            $image_opt_meta['size'][$size_key]['webp_status']=1;

                            update_post_meta($image_id,'wpvivid_image_optimize_meta',$image_opt_meta);
                            break;
                        }
                    }

                    if($ret['result']=='failed')
                    {
                        $this->WriteLog('Converting image '.basename($file).' failed. Error:'.$ret['error'],'error');
                        return $ret;
                    }
                    $optimize_count++;
                }

                if($optimize_count>$max_allowed_optimize_count)
                {
                    $this->WriteLog('opt count:'.$optimize_count.' max:'.$max_allowed_optimize_count,'notice');
                    $this->task['status']='completed';
                    $this->task['last_update_time']=time();
                    $this->task['retry']=0;
                    update_option('wpvivid_image_opt_task',$this->task,'no');
                    $ret['result']='success';
                    return $ret;
                }
            }
        }
        $image_opt_meta['last_update_time']=time();
        update_post_meta($image_id,'wpvivid_image_optimize_meta',$image_opt_meta);

        if(function_exists( 'getimagesize' ))
        {
            $og_file_path = get_attached_file( $image_id );
            if(!empty($og_file_path))
            {
                $image_filesize = filesize($og_file_path);
                if($image_filesize)
                {
                    $meta = wp_get_attachment_metadata($image_id);
                    $meta['filesize']=$image_filesize;
                    wp_update_attachment_metadata( $image_id, $meta );
                }
            }
        }

        if($ret['result']=='success')
        {
            do_action('wpvivid_do_after_optimized',$image_id);
        }
        else
        {
            $this->WriteLog('Optimize images '.$image_id.' failed.Error:'.$ret['error'],'notice');
        }

        $this->task['images'][$image_id]['finished']=1;
        $this->task['status']='completed';
        $this->task['last_update_time']=time();
        $this->task['retry']=0;
        update_option('wpvivid_image_opt_task',$this->task,'no');
        $ret['result']='success';
        return $ret;
    }

    public function init_image_opt_meta($path)
    {
        $image_opt_meta['sum']['og_size']=0;
        $image_opt_meta['sum']['opt_size']=0;
        $image_opt_meta['sum']['options']['mode']='lossless';
        $image_opt_meta['sum']['options']['backup']=$this->is_backup();
        $image_opt_meta['size']=array();

        $image_opt_meta=apply_filters('wpvivid_init_image_file_opt_meta',$image_opt_meta,$path);

        return $image_opt_meta;
    }

    public function init_folder_image_opt_meta($path)
    {
        $image_opt_meta['sum']['og_size']=filesize($path);
        $image_opt_meta['sum']['opt_size']=0;
        $image_opt_meta['sum']['options']['mode']='lossless';
        $image_opt_meta['sum']['options']['backup']=$this->is_backup();
        $image_opt_meta['opt_status']=0;
        $image_opt_meta['size']=array();

        $image_opt_meta=apply_filters('wpvivid_init_image_file_opt_meta',$image_opt_meta,$path);

        return $image_opt_meta;
    }

    public function get_image_opt_meta($path)
    {
        global $wpdb;

        $query=$wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpvivid_files_opt_meta WHERE path ='%s'",$path);

        $results = $wpdb->get_results($query,ARRAY_A);
        if (empty($results))
        {
            return false;
        }
        else
        {
            foreach ($results as $meta)
            {
                return unserialize($meta['meta']);
            }
            return false;
        }
    }

    public function update_image_opt_meta($path,$meta)
    {
        global $wpdb;

        $query=$wpdb->prepare("SELECT * FROM {$wpdb->prefix}wpvivid_files_opt_meta WHERE path ='%s'",$path);

        $results = $wpdb->get_results($query,ARRAY_A);
        if (empty($results))
        {
            $data['path']=$path;
            $data['meta']=serialize($meta);
            $wpdb->insert($wpdb->prefix.'wpvivid_files_opt_meta',$data);
        }
        else
        {
            $where['path']=$path;
            $data['meta']=serialize($meta);
            $wpdb->update($wpdb->prefix.'wpvivid_files_opt_meta',$data,$where);
        }
    }

    public function get_file_type($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function check_table()
    {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $table_name = $wpdb->prefix . "wpvivid_files_opt_meta";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name)
        {
            $sql = "CREATE TABLE $table_name (
                id int NOT NULL AUTO_INCREMENT,
                path text NOT NULL,
                meta text NOT NULL,
                PRIMARY KEY (id)
                );";
            //reference to upgrade.php file
            dbDelta( $sql );
        }
    }

    public function do_optimize_schedule_image()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());
        $cancel=get_option('wpvivid_image_opt_task_cancel',false);
        if($cancel)
        {
            $this->WriteLog('Cancel bulk optimization.','notice');

            $this->task['status']='finished';
            $this->task['last_update_time']=time();
            update_option('wpvivid_image_opt_task',$this->task,'no');
            update_option('wpvivid_image_opt_task_cancel',false,'no');

            $server=new WPvivid_Image_Optimize_Connect_server_Ex();
            $info=get_option('wpvivid_pro_user',false);
            if($info===false)
            {
                $ret['result']='failed';
                $ret['error']='Need login';
                return $ret;
            }
            $user_info=$info['token'];
            $server->delete_cache($user_info);

            $ret['result']='success';
            return $ret;
        }

        if(empty($this->task)||!isset($this->task['images']))
        {
            $this->WriteLog('Cannot find optimization task, task quit.','notice');
            $ret['result']='success';
            return $ret;
        }

        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');
        $this->task['optimize_type'] = isset($this->task['optimize_type']) ? $this->task['optimize_type'] : 'media_library';
        $this->do_unoptimized_schedule_image();
        $this->finish_optimize_image();

        $ret['result']='success';
        return $ret;
    }

    public function do_unoptimized_schedule_image()
    {
        $image_id=false;

        foreach ($this->task['images'] as $image)
        {
            if($image['finished']==0)
            {
                $image_id=$image['id'];
                break;
            }
        }

        if($image_id===false)
        {
            return false;
        }

        $this->task['status']='running';
        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $this->WriteLog('Start optimizing image id:'.$image_id,'notice');

        $ret=$this->optimize_image($image_id);

        if($ret['result']=='success')
        {
            $this->WriteLog('Optimizing image id:'.$image_id.' succeeded.','notice');
            update_option('wpvivid_image_opt_task',$this->task,'no');
        }
        else
        {
            $this->WriteLog('Optimizing image failed. Error:'.$ret['error'],'error');
            $this->task['status']='error';
            $this->task['error']=$ret['error'];
            $this->task['last_update_time']=time();
            update_option('wpvivid_image_opt_task',$this->task,'no');
        }

        return true;
    }

    public function do_unoptimized_image()
    {
        $image_id=false;

        foreach ($this->task['images'] as $image)
        {
            if($image['finished']==0)
            {
                $image_id=$image['id'];
                break;
            }
        }

        if($image_id===false)
        {
            $ret['result']='success';
            $ret['unoptimized_image']=false;
            return $ret;
        }

        $this->task['status']='running';
        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $this->WriteLog('Start optimizing image id:'.$image_id,'notice');

        $ret=$this->optimize_image($image_id);

        if($ret['result']=='success')
        {
            $this->WriteLog('Optimizing image id:'.$image_id.' succeeded.','notice');
            $ret['result']='success';
            $ret['unoptimized_image']=true;
            return $ret;
        }
        else
        {
            $this->WriteLog('Optimizing image failed. Error:'.$ret['error'],'error');
            $this->task['status']='error';
            $this->task['error']=$ret['error'];
            $this->task['last_update_time']=time();
            update_option('wpvivid_image_opt_task',$this->task,'no');
            $ret['unoptimized_image']=true;
            return $ret;
        }
    }

    public function finish_optimize_image()
    {
        $this->task['status']='finished';
        $this->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->task,'no');

        $server=new WPvivid_Image_Optimize_Connect_server_Ex();

        $info=get_option('wpvivid_pro_user',false);
        if($info===false)
        {
            $ret['result']='failed';
            $ret['error']='Need login';
            return $ret;
        }

        $user_info=$info['token'];

        $server->delete_cache($user_info);
        $ret['result']='success';
        return $ret;
    }

    public function is_backup()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        return isset($this->task['options']['backup'])?$this->task['options']['backup']:true;
    }

    public function is_resize($id)
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        $resize=isset($this->task['options']['resize'])?$this->task['options']['resize']:false;

        if($resize!==false&&$resize['enable'])
        {
            $meta =wp_get_attachment_metadata( $id );

            if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) )
            {
                $old_width  = $meta['width'];
                $old_height = $meta['height'];
                $max_width=isset($resize['width'])?$resize['width']:1280;
                $max_height=isset($resize['height'])?$resize['height']:1280;

                if ( ( $old_width > $max_width && $max_width > 0 ) || ( $old_height > $max_height && $max_height > 0 ) )
                {
                    return true;
                }
            }

            return false;
        }
        else
        {
            return false;
        }
    }

    public function resize($image_id)
    {
        $file_path = get_attached_file( $image_id );

        $resize=isset($this->task['options']['resize'])?$this->task['options']['resize']:false;

        if($resize===false)
        {
            return true;
        }

        $max_width=isset($resize['width'])?$resize['width']:1280;
        $max_height=isset($resize['height'])?$resize['height']:1280;
        $resize_method=isset($resize['method'])?$resize['method']:'auto';
        if($resize_method==='width')
        {
            $max_height='0';
        }
        else if($resize_method==='height')
        {
            $max_width='0';
        }

        $data=image_make_intermediate_size($file_path,$max_width,$max_height);
        if(!$data)
        {
            return false;
        }

        $resize_path = path_join( dirname( $file_path ),$data['file']);
        if (!file_exists($resize_path))
        {
            return false;
        }

        @copy($resize_path,$file_path);
        $meta = wp_get_attachment_metadata($image_id);

        if(!empty($meta['sizes']))
        {
            $path_parts = pathinfo($resize_path );
            $filename   = ! empty( $path_parts['basename'] ) ? $path_parts['basename'] : $path_parts['filename'];
            $unlink=true;
            foreach ( $meta['sizes'] as $image_size )
            {
                if ( false === strpos( $image_size['file'], $filename ) )
                {
                    continue;
                }
                $unlink = false;
            }

            if($unlink)
            {
                @unlink($resize_path );
            }
        }
        else
        {
            @unlink( $resize_path );
        }

        $meta['width']=$data['width'];
        $meta['height']=$data['height'];
        wp_update_attachment_metadata( $image_id, $meta );
        return true;
    }

    public function backup($files,$image_id)
    {
        $og_file_path = get_attached_file( $image_id );
        $backup_meta=array();
        foreach ($files as $file)
        {
            if(file_exists($file))
            {
                if($og_file_path === $file)
                {
                    $meta = wp_get_attachment_metadata( $image_id );

                    if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) )
                    {
                        $image_opt_meta=array();
                        $image_opt_meta['og_image_pixel']['width']=$meta['width'];
                        $image_opt_meta['og_image_pixel']['height']=$meta['height'];
                        update_post_meta($image_id,'wpvivid_image_og_pixel_meta',$image_opt_meta);
                    }
                }
                $backup_dir=$this->get_backup_folder($file);

                if(!file_exists($backup_dir))
                {
                    @copy($file,$backup_dir);
                }
                $file_data['og_path']=wp_slash($file);
                $file_data['backup_path']=wp_slash($backup_dir);
                $backup_meta[]=$file_data;
            }
        }
        if(!empty($backup_meta))
        {
            update_post_meta($image_id,'wpvivid_backup_image_meta',$backup_meta);
        }
    }

    public function backup_folders_image($path)
    {
        $meta=$this->get_image_opt_meta($path);

        if(file_exists($path))
        {
            $backup_dir=$this->get_backup_folder_ex($path);

            if(!file_exists($backup_dir))
            {
                @copy($path,$backup_dir);
            }
            $meta['backup']['og_path']=wp_slash($path);
            $meta['backup']['backup_path']=wp_slash($backup_dir);
        }

        $this->update_image_opt_meta($path,$meta);
    }

    public function get_backup_folder_ex($path)
    {
        $options=get_option('wpvivid_optimization_options',array());
        $backup_path=isset($options['backup_path'])?$options['backup_path']:WPVIVID_IMGOPTIM_DEFAULT_SAVE_DIR;
        if(!is_dir(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path))
        {
            @mkdir(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path,0777,true);
            @fopen(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path.DIRECTORY_SEPARATOR.'index.html', 'x');
            $tempfile=@fopen(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path.DIRECTORY_SEPARATOR.'.htaccess', 'x');
            if($tempfile)
            {
                $text="deny from all";
                fwrite($tempfile,$text );
                fclose($tempfile);
            }
        }

        $attachment_dir=dirname($path);
        $attachment_dir=$this->transfer_path($attachment_dir);
        $root=$this->transfer_path(ABSPATH);
        $sub_dir=str_replace($root,'',$attachment_dir);
        $sub_dir=untrailingslashit($sub_dir);
        $path=WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path.DIRECTORY_SEPARATOR.'backup_image'.DIRECTORY_SEPARATOR.$sub_dir;

        if(!file_exists($path))
        {
            @mkdir($path,0777,true);
        }

        return $path.DIRECTORY_SEPARATOR.basename($path);
    }

    public function get_backup_folder( $attachment_path )
    {
        $options=get_option('wpvivid_optimization_options',array());
        $backup_path=isset($options['backup_path'])?$options['backup_path']:WPVIVID_IMGOPTIM_DEFAULT_SAVE_DIR;
        if(!is_dir(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path))
        {
            @mkdir(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path,0777,true);
            @fopen(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path.DIRECTORY_SEPARATOR.'index.html', 'x');
            $tempfile=@fopen(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path.DIRECTORY_SEPARATOR.'.htaccess', 'x');
            if($tempfile)
            {
                $text="deny from all";
                fwrite($tempfile,$text );
                fclose($tempfile);
            }
        }

        $upload_dir = wp_get_upload_dir();
        $upload_root=$this->transfer_path($upload_dir['basedir']);
        $attachment_dir=dirname($attachment_path);
        $attachment_dir=$this->transfer_path($attachment_dir);
        $sub_dir=str_replace($upload_root,'',$attachment_dir);
        $sub_dir=untrailingslashit($sub_dir);
        $path=WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path.DIRECTORY_SEPARATOR.'backup_image'.DIRECTORY_SEPARATOR.$sub_dir;

        if(!file_exists($path))
        {
            @mkdir($path,0777,true);
        }

        return $path.DIRECTORY_SEPARATOR.basename($attachment_path);
    }

    private function transfer_path($path)
    {
        $path = str_replace('\\','/',$path);
        $values = explode('/',$path);
        return implode(DIRECTORY_SEPARATOR,$values);
    }

    public function compress_image($type,$in,$out,$options)
    {
        $size=filesize($in);

        if($size< 1024 *1024*2)
        {
            $server=new WPvivid_Image_Optimize_Connect_server_Ex();

            $info=get_option('wpvivid_pro_user',false);
            $info=apply_filters('wpvivid_imgoptim_user_info',$info);
            if($info===false)
            {
                $ret['result']='failed';
                $ret['error']='Need login';
                return $ret;
            }

            $user_info=$info['token'];
            $ret=$server->upload_small_file($user_info,$type,$in,$out,$options);
        }
        else
        {
            $ret=$this->upload_file($type,$in,$out,$options);
        }

        return $ret;
    }

    public function convert_webp($type,$in,$out,$options)
    {
        $size=filesize($in);

        if($size< 1024 *1024 *2)
        {
            $server=new WPvivid_Image_Optimize_Connect_server_Ex();

            $info=get_option('wpvivid_pro_user',false);
            $info=apply_filters('wpvivid_imgoptim_user_info',$info);
            if($info===false)
            {
                $ret['result']='failed';
                $ret['error']='Need login';
                return $ret;
            }

            $user_info=$info['token'];
            $ret=$server->convert_small_file($user_info,$type,$in,$out,$options);
        }
        else
        {
            $ret=$this->convert_file($type,$in,$out,$options);
        }

        return $ret;
    }

    public function convert_file($type,$file,$out,$options)
    {
        $server=new WPvivid_Image_Optimize_Connect_server_Ex();

        $info=get_option('wpvivid_pro_user',false);
        if($info===false)
        {
            $ret['result']='failed';
            $ret['error']='Need login';
            return $ret;
        }

        $user_info=$info['token'];
        $ret=$server->delete_exist_file($user_info,$file);

        if($ret['result']!='success')
        {
            return $ret;
        }

        $file_size=filesize($file);

        $offset=0;

        $handle=fopen($file,'rb');
        $upload_size=1024*1024*2;

        while(!feof($handle))
        {
            $data = fread($handle,$upload_size);

            $ret=$server->upload_loop($user_info,$file,$offset,$upload_size,$data);

            if($ret['result']=='success')
            {
                $offset+=$upload_size;
            }
            else
            {
                return $ret;
            }
        }

        fclose($handle);
        $ret=$server->convert_image_without_upload($user_info,$type,$file,$options);

        if($ret['result']!='success')
        {
            return $ret;
        }

        $ret=$server->download_webp_file($user_info,$ret['output'],$out);

        return $ret;
    }

    public function upload_file($type,$file,$out,$options)
    {
        $server=new WPvivid_Image_Optimize_Connect_server_Ex();

        $info=get_option('wpvivid_pro_user',false);
        if($info===false)
        {
            $ret['result']='failed';
            $ret['error']='Need login';
            return $ret;
        }

        $user_info=$info['token'];
        $ret=$server->delete_exist_file($user_info,$file);

        if($ret['result']!='success')
        {
            return $ret;
        }

        $file_size=filesize($file);

        $offset=0;

        $handle=fopen($file,'rb');
        $upload_size=1024*1024;
        $this->WriteLog('Start upload file:'.basename($file),'notice');
        while(!feof($handle))
        {
            $data = fread($handle,$upload_size);
            $this->WriteLog('Upload chunk:'.$offset,'notice');
            $ret=$server->upload_loop($user_info,$file,$offset,$upload_size,$data);

            if($ret['result']=='success')
            {
                $offset+=$upload_size;
            }
            else
            {
                return $ret;
            }
        }
        $this->WriteLog('Upload file:'.basename($file).' success.','notice');
        fclose($handle);

        $this->WriteLog('Start compress file:'.basename($file),'notice');
        $compress_ret=$server->compress_image_without_upload($user_info,$type,$file,$options);

        if($compress_ret['result']!='success')
        {
            return $compress_ret;
        }
        $this->WriteLog('Compress file:'.basename($file).' success.','notice');
        $output_file=$compress_ret['output'];

        $this->WriteLog('Start download file:'.$output_file,'notice');
        $ret=$server->download_file($user_info,$output_file,$out);
        if($ret['result']!='success')
        {
            return $ret;
        }

        $this->WriteLog('download file:'.basename($file).' success.','notice');

        $webp_convert=isset($options['webp']['convert'])?$options['webp']['convert']:false;

        if($type=='gif')
        {
            $gif_webp_convert=isset($this->task['options']['webp']['gif_convert'])?$this->task['options']['webp']['gif_convert']:false;
        }
        else
        {
            $gif_webp_convert=false;
        }

        if($type=='gif'&&$gif_webp_convert)
        {
            if($compress_ret['webp_result']=='success')
            {
                $webp_file=$compress_ret['webp_file'];
                $out_webp=$out.'.webp';
                $webp_ret=$server->download_webp_file($user_info,$webp_file,$out_webp);
                $ret['webp_result']=$webp_ret['result'];
                if($ret['webp_result']=='failed')
                {
                    $ret['webp_error']=$webp_ret['error'];
                }
            }
            else
            {
                $ret['webp_result']='failed';
                $ret['webp_error']=$compress_ret['webp_error'];
            }
        }
        else if($type=='gif'&&!$gif_webp_convert)
        {

        }
        else if($webp_convert)
        {
            if($compress_ret['webp_result']=='success')
            {
                $webp_file=$compress_ret['webp_file'];
                $out_webp=$out.'.webp';
                $webp_ret=$server->download_webp_file($user_info,$webp_file,$out_webp);
                $ret['webp_result']=$webp_ret['result'];
                if($ret['webp_result']=='failed')
                {
                    $ret['webp_error']=$webp_ret['error'];
                }
            }
            else
            {
                $ret['webp_result']='failed';
                $ret['webp_error']=$compress_ret['webp_error'];
            }
        }

        return $ret;
    }

    public function get_task_status()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        if(empty($this->task))
        {
            $ret['result']='failed';
            $ret['error']='All image(s) optimized successfully.';
            return $ret;
        }

        if($this->task['status']=='error')
        {
            $ret['result']='failed';
            $ret['error']=$this->task['error'];
        }
        else if($this->task['status']=='completed')
        {
            $ret['result']='success';
            $ret['status']='completed';
        }
        else if($this->task['status']=='finished')
        {
            $ret['result']='success';
            $ret['status']='finished';
        }
        else if($this->task['status']=='timeout')
        {
            $ret['result']='success';
            $ret['status']='completed';
        }
        else
        {
            $ret['result']='success';
            $ret['status']='running';
        }
        return $ret;
    }

    public function get_task_progress()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        if(empty($this->task))
        {
            $ret['result']='failed';
            $ret['error']='All image(s) optimized successfully.';
            $ret['timeout']=0;
            $ret['percent']=0;
            $ret['total_images']=0;
            $ret['optimized_images']=0;
            $ret['log']='All image(s) optimized successfully.';
            return $ret;
        }

        if(isset($this->task['images']))
        {
            $ret['total_images']=sizeof($this->task['images']);
        }
        else
        {
            $ret['total_images']=0;
        }

        $ret['optimized_images']=0;
        if(isset($this->task['images']))
        {
            foreach ($this->task['images'] as $image)
            {
                if($image['finished'])
                {
                    $ret['optimized_images']++;
                }
            }
        }

        if(isset($this->task['schedule'])&&$this->task['schedule']==1)
        {
            $ret['is_schedule']=1;
        }
        else
        {
            $ret['is_schedule']=0;
        }

        if(isset($this->task['status']))
        {
            if($this->task['status']=='error')
            {
                $ret['result']='failed';
                $ret['error']=$this->task['error'];
                $ret['timeout']=0;
                $ret['percent']= intval(($ret['optimized_images']/$ret['total_images'])*100);
                $ret['log']=$this->task['error'];
            }
            else if($this->task['status']=='finished')
            {
                $ret['result']='success';
                $ret['continue']=0;
                $ret['finished']=1;
                $ret['timeout']=0;
                $ret['percent']= 100;
                $ret['log']='Finish Optimizing images.';
            }
            else if($this->task['status']=='completed')
            {
                $ret['result']='success';
                $ret['continue']=0;
                $ret['finished']=0;
                $ret['timeout']=0;
                $ret['percent']= intval(($ret['optimized_images']/$ret['total_images'])*100);
                $ret['log']=$this->get_last_log();
                //$ret['log']='Optimizing images...';
            }
            else
            {
                if(isset($this->task['last_update_time']))
                {
                    if(time()-$this->task['last_update_time']>180)
                    {
                        $this->task['last_update_time']=time();
                        $this->task['retry']++;
                        $this->task['status']='timeout';
                        update_option('wpvivid_image_opt_task',$this->task,'no');
                        if($this->task['retry']<3)
                        {
                            $ret['timeout']=1;
                        }
                        else
                        {
                            $ret['timeout']=0;
                            if($this->log)
                            {
                                $this->log=new WPvivid_Image_Optimize_Log();
                                $this->log->OpenLogFile();
                                $this->log->WriteLog('To many retry attempts. Task timed out.','error');
                                $this->log->Copy_To_Error();
                            }
                            update_option('wpvivid_image_opt_task',array(),'no');
                        }

                        $ret['result']='failed';
                        $ret['error']='Task timed out';
                        $ret['percent']=0;
                        $ret['retry']=$this->task['retry'];
                        $ret['total_images']=0;
                        $ret['optimized_images']=0;
                        $ret['log']='task time out';
                    }
                    else
                    {
                        $ret['continue']=1;
                        $ret['finished']=0;
                        $ret['timeout']=0;
                        $ret['running_time']=time()-$this->task['last_update_time'];
                        $ret['result']='success';
                        $ret['percent']= intval(($ret['optimized_images']/$ret['total_images'])*100);

                        $ret['log']=$this->get_last_log();
                        //$ret['log']='Optimizing images...';
                    }
                }
                else
                {
                    $ret['result']='failed';
                    $ret['error']='not start task';
                    $ret['timeout']=0;
                    $ret['percent']=0;
                    $ret['total_images']=0;
                    $ret['optimized_images']=0;
                    $ret['log']='not start task';
                }
            }
        }
        else
        {
            $ret['result']='failed';
            $ret['error']='not start task';
            $ret['timeout']=0;
            $ret['percent']=0;
            $ret['total_images']=0;
            $ret['optimized_images']=0;
            $ret['log']='not start task';
        }


        return $ret;
    }

    public function get_manual_task_progress()
    {
        $this->task=get_option('wpvivid_image_opt_task',array());

        if(empty($this->task))
        {
            $ret['result']='failed';
            $ret['error']='All image(s) optimized successfully.';
            $ret['timeout']=0;
            if(isset($this->task['error']))
                $ret['log']=($this->task['error']);
            else
                $ret['log']='All image(s) optimized successfully.';
            return $ret;
        }

        if(isset($this->task['status']))
        {
            if($this->task['status']=='error')
            {
                $ret['result']='failed';
                $ret['error']=$this->task['error'];
                $ret['timeout']=0;
                $ret['log']=$this->task['error'];
            }
            else if($this->task['status']=='finished')
            {
                $ret['result']='success';
                $ret['continue']=0;
                $ret['finished']=1;
                $ret['timeout']=0;
                $ret['log']='Finish Optimizing images.';
            }
            else if($this->task['status']=='completed')
            {
                $ret['result']='success';
                $ret['continue']=0;
                $ret['finished']=0;
                $ret['timeout']=0;
                $ret['log']='Optimizing images...';
            }
            else
            {
                if(isset($this->task['last_update_time']))
                {
                    if(time()-$this->task['last_update_time']>180)
                    {
                        $this->task['last_update_time']=time();
                        $this->task['retry']++;
                        $this->task['status']='timeout';
                        update_option('wpvivid_image_opt_task',$this->task,'no');
                        if($this->task['retry']<3)
                        {
                            $ret['timeout']=1;
                        }
                        else
                        {
                            $ret['timeout']=0;
                            if($this->log)
                            {
                                $this->log=new WPvivid_Image_Optimize_Log();
                                $this->log->OpenLogFile();
                                $this->log->WriteLog('To many retry attempts. Task timed out.','error');
                                $this->log->Copy_To_Error();
                            }
                            update_option('wpvivid_image_opt_task',array(),'no');
                        }

                        $ret['result']='failed';
                        $ret['error']='Task timed out';
                        $ret['retry']=$this->task['retry'];
                        $ret['log']='task time out';
                    }
                    else
                    {
                        $ret['continue']=1;
                        $ret['finished']=0;
                        $ret['timeout']=0;
                        $ret['running_time']=time()-$this->task['last_update_time'];
                        $ret['result']='success';
                        $ret['log']='Optimizing images...';
                    }
                }
                else
                {
                    $ret['result']='failed';
                    $ret['error']='not start task';
                    $ret['timeout']=0;
                    $ret['log']='not start task';
                }
            }
        }
        else
        {
            $ret['result']='failed';
            $ret['error']='not start task';
            $ret['timeout']=0;
            $ret['log']='not start task';
        }


        return $ret;
    }

    public function restore_image($image_id)
    {
        $backup_image_meta = get_post_meta( $image_id, 'wpvivid_backup_image_meta', true );

        if(empty($backup_image_meta))
        {
            delete_post_meta( $image_id, 'wpvivid_image_optimize_meta');
            return true;
        }


        if($backup_image_meta)
        {
            foreach ($backup_image_meta as $meta)
            {
                if(file_exists($meta['backup_path']))
                    @rename($meta['backup_path'],$meta['og_path']);
            }


            $image_opt_meta=get_post_meta($image_id,'wpvivid_image_og_pixel_meta',true);
            if(isset($image_opt_meta['og_image_pixel']['width']) && isset($image_opt_meta['og_image_pixel']['height']))
            {
                $og_file_width_old=$image_opt_meta['og_image_pixel']['width'];
                $og_file_height_old=$image_opt_meta['og_image_pixel']['height'];
                $meta = wp_get_attachment_metadata($image_id);

                if(function_exists( 'getimagesize' ))
                {
                    $og_file_path = get_attached_file( $image_id );
                    $size = @getimagesize( $og_file_path );

                    if ( ! $size || ! isset( $size[0], $size[1] ) ) {
                        $meta['width']=$og_file_width_old;
                        $meta['height']=$og_file_height_old;
                    }
                    else {
                        $og_file_width_new = $size[0];
                        $og_file_height_new = $size[1];

                        if($og_file_width_old == $og_file_width_new && $og_file_height_old == $og_file_height_new)
                        {
                            $meta['width']=$og_file_width_old;
                            $meta['height']=$og_file_height_old;
                        }
                        else
                        {
                            $meta['width']=$og_file_width_new;
                            $meta['height']=$og_file_height_new;
                        }
                    }

                    $image_filesize = filesize($og_file_path);
                    if($image_filesize)
                    {
                        $meta['filesize']=$image_filesize;
                    }
                }
                else
                {
                    $meta['width']=$og_file_width_old;
                    $meta['height']=$og_file_height_old;
                }
                wp_update_attachment_metadata( $image_id, $meta );
            }
            else
            {
                if(function_exists( 'getimagesize' ))
                {
                    $og_file_path = get_attached_file( $image_id );
                    $size = @getimagesize( $og_file_path );

                    if ( ! $size || ! isset( $size[0], $size[1] ) ) {
                    }
                    else {
                        $og_file_width = $size[0];
                        $og_file_height = $size[1];

                        $meta = wp_get_attachment_metadata($image_id);
                        $meta['width']=$og_file_width;
                        $meta['height']=$og_file_height;

                        $image_filesize = filesize($og_file_path);
                        if($image_filesize)
                        {
                            $meta['filesize']=$image_filesize;
                        }

                        wp_update_attachment_metadata( $image_id, $meta );
                    }
                }
            }


            do_action('wpvivid_restore_image',$image_id);
            delete_post_meta( $image_id, 'wpvivid_image_optimize_meta');
            delete_post_meta( $image_id, 'wpvivid_backup_image_meta');
            delete_post_meta( $image_id, 'wpvivid_image_og_pixel_meta');
            return true;
        }
        else
        {
            delete_post_meta( $image_id, 'wpvivid_image_optimize_meta');
            return true;
        }
    }

    public function restore_folders_image($image_id)
    {
        global $wpdb;

        $query="SELECT * FROM {$wpdb->prefix}wpvivid_files_opt_meta WHERE id=$image_id";
        $results = $wpdb->get_results($query,ARRAY_A);
        if (empty($results))
        {
            return true;
        }
        else
        {
            foreach ($results as $image_meta)
            {
                $meta=unserialize($image_meta['meta']);
                if(isset($meta['backup']))
                {
                    $backup_image_meta=$meta['backup'];
                    if(file_exists($backup_image_meta['backup_path']))
                        @rename($backup_image_meta['backup_path'],$backup_image_meta['og_path']);
                }
                $where['id']=$image_id;
                $wpdb->delete("{$wpdb->prefix}wpvivid_files_opt_meta",$where);
            }
        }

        return true;
    }
}