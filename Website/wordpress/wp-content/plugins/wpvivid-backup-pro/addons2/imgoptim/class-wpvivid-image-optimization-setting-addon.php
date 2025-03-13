<?php

/**
 * WPvivid addon: yes
 * Addon Name: wpvivid-imgoptim-pro
 * Description: Pro
 * Version: 2.2.27
 * Need_init: yes
 * Admin_load: yes
 * Interface Name: WPvivid_Image_Optimization_Setting_Addon
 */
if (!defined('WPVIVID_BACKUP_PRO_PLUGIN_DIR'))
{
    die;
}
class WPvivid_Image_Optimization_Setting_Addon
{
    public $main_tab;
    public function __construct()
    {
        //add_filter('wpvividdashboard_pro_setting_tab', array($this, 'setting_tab'), 14);
        add_filter('wpvivid_image_optimization_tab', array($this, 'setting_tab'), 14);
        add_action('wp_ajax_wpvivid_set_general_image_optimize_setting', array($this, 'set_image_optimize_general_setting'));
        //add_filter('wpvivid_set_general_setting', array($this, 'set_general_setting'), 12, 3);

        add_action('wpvivid_action_white_label_edit_path', array($this, 'edit_path'));

        //
        add_action('wp_ajax_wpvivid_delete_all_images_backup_ex', array($this, 'delete_all_images_backup_ex'));
        add_action('wp_ajax_wpvivid_ping_image_server_time', array($this, 'ping_image_server_time'));
    }

    public function delete_all_images_backup_ex()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        $options=get_option('wpvivid_optimization_options',array());
        $backup_path=isset($options['backup_path'])?$options['backup_path']:WPVIVID_IMGOPTIM_DEFAULT_SAVE_DIR;

        if(is_dir(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path))
        {
            $this->deldir(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$backup_path);
        }

        $ret['result']='success';
        echo json_encode($ret);
        die();
    }

    public function ping_image_server_time()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(isset($_POST['region'])&&!empty($_POST['region']))
        {
            if(function_exists("curl_init"))
            {
                $region = sanitize_key($_POST['region']);

                if($region == 'us2')
                {
                    $url = 'http://us2.wpvivid.com';
                }
                else
                {
                    $url = 'http://eu1.wpvivid.com';
                }

                $start_time = microtime(true);
                $ping_count = 0;
                $is_success = false;

                while($ping_count < 5)
                {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_exec($ch);
                    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if (200==$retcode) {
                        $is_success = true;
                    } else {
                    }
                    $ping_count++;
                }

                if($is_success)
                {
                    $ret['result']='success';
                    $end_time = microtime(true);
                    $use_time = intval((($end_time - $start_time)*1000) / 5).'ms';
                    $ret['time']=$use_time;
                }
                else
                {
                    $ret['result'] = 'failed';
                    $ret['error']  = 'The ping has failed, please check your network connection and try again.';
                }

            }
            else
            {
                $ret['result'] = 'failed';
                $ret['error']  = 'The PHP curl extension is not detected. Please install the extension first.';
            }

            echo json_encode($ret);
        }

        die();
    }

    public function deldir($path,$flag = false)
    {
        if(!is_dir($path))
        {
            return ;
        }
        $handler=opendir($path);
        if(empty($handler))
            return ;
        while(($filename=readdir($handler))!==false)
        {
            if($filename != "." && $filename != "..")
            {
                if(is_dir($path.DIRECTORY_SEPARATOR.$filename))
                {
                    self::deldir( $path.DIRECTORY_SEPARATOR.$filename, $flag);
                    @rmdir( $path.DIRECTORY_SEPARATOR.$filename );
                }else {
                    @unlink($path.DIRECTORY_SEPARATOR.$filename);
                }
            }
        }
        if($handler)
            @closedir($handler);
        if($flag)
            @rmdir($path);
    }

    public function setting_tab($tabs)
    {
        if( apply_filters('wpvivid_current_user_can',true,'wpvivid-can-use-image-optimization'))
        {
            $args['span_class']='dashicons dashicons-admin-generic wpvivid-dashicons-blue';
            $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
            $args['is_parent_tab']=0;
            $tabs['setting']['title']='Setting';
            $tabs['setting']['slug']='setting';
            $tabs['setting']['callback']=array($this, 'output_setting_ex');
            $tabs['setting']['args']=$args;
        }
        return $tabs;
    }

    public function set_image_optimize_general_setting()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

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
                $ret = $this->check_setting_option($setting);
                echo json_encode($ret);
                die();
            }
            else
            {
                die();
            }
        }
        catch (Exception $error)
        {
            $message = 'An exception has occurred. class: '.get_class($error).';msg: '.$error->getMessage().';code: '.$error->getCode().';line: '.$error->getLine().';in_file: '.$error->getFile().';';
            error_log($message);
            echo json_encode(array('result'=>'failed','error'=>$message));
            die();
        }
    }

    public function set_general_setting($setting_data, $setting, $options)
    {
        //wpvivid_optimization_options
        if(isset($setting['auto_optimize']))
            $setting_data['wpvivid_optimization_options']['auto_optimize']=$setting['auto_optimize'];

        if(isset($setting['keep_exif']))
            $setting_data['wpvivid_optimization_options']['keep_exif']=$setting['keep_exif'];

        if(isset($setting['quality']))
            $setting_data['wpvivid_optimization_options']['quality']=$setting['quality'];
        if(isset($setting['custom_quality']))
            $setting_data['wpvivid_optimization_options']['custom_quality']=$setting['custom_quality'];

        if(isset($setting['resize']))
            $setting_data['wpvivid_optimization_options']['resize']['enable']=$setting['resize'];
        if(isset($setting['resize_width']))
            $setting_data['wpvivid_optimization_options']['resize']['width']=$setting['resize_width'];
        if(isset($setting['resize_height']))
            $setting_data['wpvivid_optimization_options']['resize']['height']=$setting['resize_height'];
        if(isset($setting['resize_method']))
            $setting_data['wpvivid_optimization_options']['resize']['method']=$setting['resize_method'];

        if(isset($setting['convert']))
            $setting_data['wpvivid_optimization_options']['webp']['convert']=intval($setting['convert']);
        if(isset($setting['display_enable']))
            $setting_data['wpvivid_optimization_options']['webp']['display_enable']=$setting['display_enable'];
        if(isset($setting['webp_display']))
            $setting_data['wpvivid_optimization_options']['webp']['display']=$setting['webp_display'];

        if(isset($setting['display_enable']) && $setting['display_enable'] == '1' &&
            isset($setting['webp_display']) && $setting['webp_display'] === 'rewrite')
        {
            $insert_rewrite_rule = true;
        }
        else
        {
            $insert_rewrite_rule = false;
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
                    if($insert_rewrite_rule)
                    {
                        $extensions = $this->get_extensions_pattern();
                        $home_root  = wp_parse_url( home_url( '/' ) );
                        $home_root  = $home_root['path'];

                        $line[]='<IfModule mod_setenvif.c>';
                        $line[]='# Vary: Accept for all the requests to jpeg, png, and gif.';
                        $line[]='SetEnvIf Request_URI "\.(' . $extensions . ')$" REQUEST_image';
                        $line[]='</IfModule>';

                        $line[]='<IfModule mod_rewrite.c>';
                        $line[]='RewriteEngine On';
                        $line[]='RewriteBase ' . $home_root . '';
                        $line[]='# Check if browser supports WebP images.';
                        $line[]='RewriteCond %{HTTP_ACCEPT} image/webp';
                        $line[]='# Check if WebP replacement image exists.';
                        $line[]='RewriteCond %{REQUEST_FILENAME}.webp -f';
                        $line[]='# Serve WebP image instead.';
                        $line[]='RewriteRule (.+)\.(' . $extensions . ')$ $1.$2.webp [T=image/webp,NC]';
                        $line[]='</IfModule>';

                        $line[]='<IfModule mod_headers.c>';
                        $line[]='Header append Vary Accept env=REQUEST_image';
                        $line[]='</IfModule>';

                        $line[]='<IfModule mod_mime.c>';
                        $line[]='AddType image/webp .webp';
                        $line[]='</IfModule>';
                        insert_with_markers($htaccess_file,'WPvivid Rewrite Rule for Webp',$line);
                    }
                    else
                    {
                        insert_with_markers($htaccess_file,'WPvivid Rewrite Rule for Webp','');
                    }

                }
            }
        }

        if(isset($setting['image_backup_path']))
            $setting_data['wpvivid_optimization_options']['backup_path']=$setting['image_backup_path'];
        //image_backup_path

        $intermediate_image_sizes = get_intermediate_image_sizes();

        if(isset($setting['og']))
        {
            $setting_data['wpvivid_optimization_options']['skip_size']['og']=!$setting['og'];
        }
        else
        {
            $setting_data['wpvivid_optimization_options']['skip_size']['og']=false;
        }
        foreach ($intermediate_image_sizes as $size_key)
        {
            if(isset($setting[$size_key]))
            {
                $setting_data['wpvivid_optimization_options']['skip_size'][$size_key]=!$setting[$size_key];
            }
            else
            {
                $setting_data['wpvivid_optimization_options']['skip_size'][$size_key]=false;
            }
        }

        if(isset($setting['image_backup']))
            $setting_data['wpvivid_optimization_options']['backup']=$setting['image_backup'];

        if(isset($setting['enable_exclude_file']))
            $setting_data['wpvivid_optimization_options']['enable_exclude_file']=$setting['enable_exclude_file'];
        if(isset($setting['exclude_file']))
            $setting_data['wpvivid_optimization_options']['exclude_file']=$setting['exclude_file'];
        if(isset($setting['enable_exclude_path']))
            $setting_data['wpvivid_optimization_options']['enable_exclude_path']=$setting['enable_exclude_path'];
        if(isset($setting['exclude_path']))
            $setting_data['wpvivid_optimization_options']['exclude_path']=$setting['exclude_path'];

        if(isset($setting['region']))
        {
            delete_option('wpvivid_get_optimization_url_ex');
            $setting_data['wpvivid_optimization_options']['region']=$setting['region'];
        }

        if(isset($setting['image_optimization_memory_limit']))
        {
            $setting_data['wpvivid_optimization_options']['image_optimization_memory_limit']=$setting['image_optimization_memory_limit'];
        }

        return $setting_data;
    }

    public function output_setting_ex()
    {
        ?>
        <div>
            <?php
            $this->output_setting();
            ?>
            <div>
                <input class="button-primary" id="wpvivid_image_optimization_setting_general_save" type="submit" value="<?php esc_attr_e( 'Save Changes', 'wpvivid-imgoptim' ); ?>" />
            </div>
        </div>
        <script>
            jQuery('#wpvivid_image_optimization_setting_general_save').click(function()
            {
                wpvivid_set_image_optimization_general_settings();
            });

            function wpvivid_set_image_optimization_general_settings()
            {
                var json = {};

                var setting_data = wpvivid_ajax_data_transfer_ex('setting');
                var json1 = JSON.parse(setting_data);

                jQuery.extend(json1, json);
                setting_data=JSON.stringify(json1);

                var ajax_data = {
                    'action': 'wpvivid_set_general_image_optimize_setting',
                    'setting': setting_data,
                };
                jQuery('#wpvivid_image_optimization_setting_general_save').css({'pointer-events': 'none', 'opacity': '0.4'});
                wpvivid_post_request_addon(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);

                        jQuery('#wpvivid_image_optimization_setting_general_save').css({'pointer-events': 'auto', 'opacity': '1'});
                        if (jsonarray.result === 'success')
                        {
                            location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvivid-imgoptim', 'wpvivid-imgoptim').'&sub_tab=setting'; ?>';
                        }
                        else {
                            alert(jsonarray.error);
                        }
                    }
                    catch (err)
                    {
                        alert(err);
                        jQuery('#wpvivid_image_optimization_setting_general_save').css({'pointer-events': 'auto', 'opacity': '1'});
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    jQuery('#wpvivid_image_optimization_setting_general_save').css({'pointer-events': 'auto', 'opacity': '1'});
                    var error_message = wpvivid_output_ajaxerror('changing base settings', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_ajax_data_transfer_ex(data_type){
                var json = {};
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
        </script>
        <?php
    }

    public function output_setting()
    {
        $options=get_option('wpvivid_optimization_options',array());
        if(isset($options['webp'])&&is_array($options['webp']))
        {
            $convert=$options['webp']['convert'];
            $display_enable=$options['webp']['display_enable'];
            $display=$options['webp']['display'];
            $gif_webp_convert=isset($options['webp']['gif_convert'])?$options['webp']['gif_convert']:false;
        }
        else
        {
            $convert='';
            $display_enable='';
            $display='pic';
            $gif_webp_convert='';
        }

        if($convert)
        {
            $convert='checked';
        }

        if($gif_webp_convert)
        {
            $gif_webp_convert='checked';
        }

        /*
        $extensions = get_loaded_extensions();

        if (array_search('gd', $extensions))
        {
            $support_webp='';
        }
        else
        {
            $support_webp="<p><span class=\"dashicons  dashicons-warning wpvivid-dashicons-red\"></span>
                       <span> PHP GD extension not found, the extension is required to convert images to WebP.</span></p>";
        }*/

        $keep_exif=isset($options['keep_exif'])?$options['keep_exif']:true;

        if($keep_exif)
        {
            $keep_exif='checked';
        }

        $quality=isset($options['quality'])?$options['quality']:'lossless';
        $custom_quality=isset($options['custom_quality'])?$options['custom_quality']:80;
        $custom_quality=min(99,$custom_quality);
        $custom_quality=max(1,$custom_quality);
        if($quality=='lossless')
        {
            $lossless='checked';
            $lossy='';
            $super='';
            $custom='';
            $custom_css='style="display:none"';
        }
        else if($quality=='lossy')
        {
            $lossy='checked';
            $lossless='';
            $super='';
            $custom='';
            $custom_css='style="display:none"';
        }
        else if($quality=='super')
        {
            $lossy='';
            $lossless='';
            $super='checked';
            $custom='';
            $custom_css='style="display:none"';
        }
        else
        {
            $lossy='';
            $lossless='';
            $super='';
            $custom='checked';
            $custom_css='';
        }

        $optimize_gif_color=isset($options['optimize_gif_color'])?$options['optimize_gif_color']:false;
        $gif_colors=isset($options['gif_colors'])?$options['gif_colors']:64;

        if($optimize_gif_color)
        {
            $optimize_gif_color='checked';
        }

        if($display_enable)
        {
            $display_enable='checked';

            if($display=='pic')
            {
                $display_pic='checked';
                $display_rewrite='';
            }
            else
            {
                $display_pic='';
                $display_rewrite='checked';
            }
        }
        else
        {
            $display_pic='checked';
            $display_rewrite='';
        }

        if(isset($options['resize']))
        {
            $resize=$options['resize']['enable'];
            $resize_width=$options['resize']['width'];
            $resize_height=$options['resize']['height'];

            if(isset($options['resize']['method']))
            {
                $resize_method=$options['resize']['method'];
                if($resize_method === 'auto')
                {
                    $resize_method_auto='checked';
                    $resize_method_width='';
                    $resize_method_height='';
                }
                else if($resize_method === 'width')
                {
                    $resize_method_auto='';
                    $resize_method_width='checked';
                    $resize_method_height='';
                }
                else if($resize_method === 'height')
                {
                    $resize_method_auto='';
                    $resize_method_width='';
                    $resize_method_height='checked';
                }
                else
                {
                    $resize_method_auto='checked';
                    $resize_method_width='';
                    $resize_method_height='';
                }
            }
            else
            {
                $resize_method_auto='checked';
                $resize_method_width='';
                $resize_method_height='';
            }
        }
        else
        {
            $resize=true;
            $resize_width=2560;
            $resize_height=2560;
            $resize_method_auto='checked';
            $resize_method_width='';
            $resize_method_height='';
        }

        if($resize)
        {
            $resize='checked';
        }

        if(!isset($options['skip_size']))
        {
            $options['skip_size']=array();
        }

        global $_wp_additional_image_sizes;
        $intermediate_image_sizes = get_intermediate_image_sizes();
        $image_sizes=array();
        $image_sizes[ 'og' ]['skip']=isset($options['skip_size']['og'])?$options['skip_size']['og']:false;

        foreach ( $intermediate_image_sizes as $size_key )
        {
            if ( in_array( $size_key, array( 'thumbnail', 'medium', 'large' ), true ) )
            {
                $image_sizes[ $size_key ]['width']  = get_option( $size_key . '_size_w' );
                $image_sizes[ $size_key ]['height'] = get_option( $size_key . '_size_h' );
                $image_sizes[ $size_key ]['crop']   = (bool) get_option( $size_key . '_crop' );
                if(isset($options['skip_size'][$size_key])&&$options['skip_size'][$size_key])
                {
                    $image_sizes[ $size_key ]['skip']=true;
                }
                else
                {
                    $image_sizes[ $size_key ]['skip']=false;
                }
            }
            else if ( isset( $_wp_additional_image_sizes[ $size_key ] ) )
            {
                $image_sizes[ $size_key ] = array(
                    'width'  => $_wp_additional_image_sizes[ $size_key ]['width'],
                    'height' => $_wp_additional_image_sizes[ $size_key ]['height'],
                    'crop'   => $_wp_additional_image_sizes[ $size_key ]['crop'],
                );
                if(isset($options['skip_size'][$size_key])&&$options['skip_size'][$size_key])
                {
                    $image_sizes[ $size_key ]['skip']=true;
                }
                else
                {
                    $image_sizes[ $size_key ]['skip']=false;
                }
            }
        }

        if ( ! isset( $sizes['medium_large'] ) || empty( $sizes['medium_large'] ) )
        {
            $width  = intval( get_option( 'medium_large_size_w' ) );
            $height = intval( get_option( 'medium_large_size_h' ) );

            $image_sizes['medium_large'] = array(
                'width'  => $width,
                'height' => $height,
            );

            if(isset($options['skip_size']['medium_large'])&&$options['skip_size']['medium_large'])
            {
                $image_sizes[ 'medium_large' ]['skip']=true;
            }
            else
            {
                $image_sizes[ 'medium_large' ]['skip']=false;
            }
        }


        $auto_optimize_type=isset($options['auto_optimize_type'])?$options['auto_optimize_type']:'upload';
        if($auto_optimize_type=='upload')
        {
            $is_auto='checked';
            $is_auto_schedule='';
            $is_no_auto='';
            $auto_schedule_text='The schedule is disabled.';
        }
        else if($auto_optimize_type=='nooptimize')
        {
            $is_auto='';
            $is_auto_schedule='';
            $is_no_auto='checked';
        }
        else{
            $is_auto='';
            $is_auto_schedule='checked';
            $is_no_auto='';
            $auto_schedule_text='The schedule is enabled.';
        }

        $auto_schedule_cycles=isset($options['auto_schedule_cycles'])?$options['auto_schedule_cycles']:'wpvivid_5minutes';

        $backup=isset($options['backup'])?$options['backup']:true;

        if($backup)
        {
            $backup='checked';
        }
        else
        {
            $backup='';
        }

        $backup_path=isset($options['backup_path'])?$options['backup_path']:'wpvivid_image_optimization';

        $backup_path_placeholder='.../wp-content/'.$backup_path;
        $backup_path_prefix='.../wp-content/';

        $enable_exclude_file=isset($options['enable_exclude_file'])?$options['enable_exclude_file']:true;
        if($enable_exclude_file)
        {
            $enable_exclude_file='checked';
        }
        else
        {
            $enable_exclude_file='';
        }
        $exclude_file=isset($options['exclude_file'])?$options['exclude_file']:'';
        $enable_exclude_path=isset($options['enable_exclude_path'])?$options['enable_exclude_path']:true;
        if($enable_exclude_path)
        {
            $enable_exclude_path='checked';
        }
        else
        {
            $enable_exclude_path='';
        }
        $exclude_path=isset($options['exclude_path'])?$options['exclude_path']:'';

        $region=isset($options['region'])?$options['region']:'us2';
        if($region=='us1')
        {
            $selected='us2';
        }
        else if($region=='us2')
        {
            $selected='us2';
        }
        else if($region=='eu1')
        {
            $selected='eu1';
        }
        else
        {
            $selected='us2';
        }

        $optimize_type = isset($options['optimize_type']) ? $options['optimize_type'] : 'media_library';
        $folders = isset($options['custom_folders']) ? $options['custom_folders'] : '';
        if($optimize_type=='media_library')
        {
            $media_library='checked';
            $custom_folders='';
        }
        else
        {
            $media_library='';
            $custom_folders='checked';
        }

        $memory_limit=isset($options['image_optimization_memory_limit'])?$options['image_optimization_memory_limit']:256;

        $memory_limit=max(256,intval($memory_limit));


        $gif=isset($options['opt_gif'])?$options['opt_gif']:true;

        if($gif)
        {
            $gif='checked';
        }
        else
        {
            $gif='';
        }

        $max_allowed_optimize_count=isset($options['max_allowed_optimize_count'])?$options['max_allowed_optimize_count']:15;

        $empty_image_log=isset($options['empty_image_log'])?$options['empty_image_log']:true;
        if($empty_image_log)
        {
            $empty_image_log='checked';
        }
        else
        {
            $empty_image_log='';
        }

        ?>

        <table class="wp-list-table widefat plugins" style="border-left:none;border-top:none;border-right:none;">
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell">Cloud Servers</label></td>
                <td>
                    <div>
                        <span>
                            <select option="setting" name="region">
                                <option value="us2">North American - Pro</option>
                                <option value="eu1">Europe - Pro</option>
                            </select>
                        </span>
                        <span>
                            <input id="wpvivid_ping_image_server_btn" type="button" class="button-primary" value="Ping Test">
                            <span id="wpvivid_ping_imaga_server_time"></span>
                        </span>
                        <p>Choosing the server closest to your website can speed up optimization process.</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell">Optimize images after uploading</label></td>
                <td>
                    <div>
                        <div>
                            <input type="radio" option="setting" name="auto_optimize_type" value="nooptimize" <?php esc_attr_e($is_no_auto); ?> />
                            <span><?php _e('Do not optimize', 'wpvivid-imgoptim'); ?></span>
                        </div>
                        <p></p>
                        <div>
                            <input type="radio" option="setting" name="auto_optimize_type" value="upload" <?php esc_attr_e($is_auto); ?> />
                            <span><?php _e('Optimize immediately','wpvivid-imgoptim')?></span>
                            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                <div class="wpvivid-bottom">
                                    <!-- The content you need -->
                                    <p>With the option checked，our plugin will optimize images immediately upon upload, but it won't optimize existing images. You have to click 'Optimize Now' button on the plugin's Image Bulk Optimization page to optimize the existing images.</p>
                                    <i></i>
                                    <!-- do not delete this line -->
                                </div>
                            </span>
                        </div>
                        <div>
                            <div style="margin-bottom:1em;">
                                <div>
                                    <p></p>
                                    <div>
                                        <input type="radio" option="setting" name="auto_optimize_type"  value="schedule" <?php esc_attr_e($is_auto_schedule); ?>>
                                        <span>Optimize in background (schedule)</span>
                                        <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                            <div class="wpvivid-bottom">
                                                <!-- The content you need -->
                                                <p>Set up a schedule to check and auto-optimize unoptimized images after uploading. This option is designed and recommended for servers with limited resources. For servers with sufficient resources, it is recommended to use the 'SIBO' option to optimize all images in 1-click.</p>
                                                <i></i> <!-- do not delete this line -->
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div style="padding-left: 1.5em;">
                                <p>
                                    <span class="dashicons dashicons-clock wpvivid-dashicons-green" style="padding-top:0.2em;"></span>
                                    <span>Schedule Cycles: </span>
                                    <span>Process unoptimized images every </span>
                                    <span>
                                        <select option="setting" name="auto_schedule_cycles">
                                            <option value="wpvivid_2minutes">2</option>
                                            <option value="wpvivid_3minutes">3</option>
                                            <option value="wpvivid_4minutes">4</option>
                                            <option value="wpvivid_5minutes">5</option>
                                            <option value="wpvivid_6minutes">6</option>
                                            <option value="wpvivid_7minutes">7</option>
                                            <option value="wpvivid_8minutes">8</option>
                                            <option value="wpvivid_9minutes">9</option>
                                            <option value="wpvivid_10minutes">10</option>
                                        </select>
                                    </span>
                                    <span> min.</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <!--tr>
                <td class="row-title" style="min-width:200px;">
                    <label for="tablecell">Auto-optimize (schedule)</label>
                </td>
                <td>
                    <div style="margin-bottom:1em;">
                        <span>
                            <label class="wpvivid-switch">
                                <input type="checkbox" option="setting" name="auto_optimize_schedule" <?php //esc_attr_e($is_auto_schedule); ?>>
                                <span class="wpvivid-slider wpvivid-round"></span>
                            </label>
                        </span>
                        <span><?php //echo $auto_schedule_text;?></span>
                        <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                            <div class="wpvivid-bottom">

                                <p>Enabling the schedule means our smart image bulk optimization(SIBO) engine will automatically optimize your images in background. So you don’t need to keep the current page open.</p>
                                <i></i>
                            </div>
                        </span>
                    </div>
                    <div>
                        <p>
                            <span class="dashicons dashicons-clock wpvivid-dashicons-green" style="padding-top:0.2em;"></span>
                            <span>Cycles: </span>
                            <span>auto-optimize </span>
                            <span><strong>one image</strong></span>
                            <span>  per </span>
                            <span>
                                <select option="setting" name="auto_schedule_cycles">
                                    <option value="wpvivid_2minutes">2</option>
                                    <option value="wpvivid_3minutes">3</option>
                                    <option value="wpvivid_4minutes">4</option>
                                    <option value="wpvivid_5minutes">5</option>
                                    <option value="wpvivid_6minutes">6</option>
                                    <option value="wpvivid_7minutes">7</option>
                                    <option value="wpvivid_8minutes">8</option>
                                    <option value="wpvivid_9minutes">9</option>
                                    <option value="wpvivid_10minutes">10</option>
                                </select>
                            </span>
                            <span> min in the background.</span>
                        </p>
                    </div>
                    <div style="padding-left:2em;padding-right:1em;">
                        <p>
                            <span><u>One image</u> means an image set in your media library, which includes the original image and all it's associated images(thumbnails).</span>
                        </p>
                    </div>

                </td>
            </tr>-->
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell">Customize what will be optimized</label></td>
                <td>
                    <div>
                        <div>
                            <p>
                                <input type="radio" option="setting" name="optimize_type" value="media_library" <?php esc_attr_e($media_library); ?> />
                                <span class="dashicons dashicons-format-gallery wpvivid-dashicons-blue" style="padding-top:0.2em;"></span>
                                <span><strong>Media Library</strong></span>
                            </p>
                        </div>
                        <div>
                            <div style="margin-top:1em;">
                                <div style="border-top:1px solid #eee;">
                                    <p></p>
                                    <div>
                                        <input type="radio" option="setting" name="optimize_type" value="custom_folders" <?php esc_attr_e($custom_folders); ?> />
                                        <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                                        <span><strong>Custom Folders</strong></span>
                                        <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                            <div class="wpvivid-bottom">
                                                    <!-- The content you need -->
                                                <p>Optimize the images under the custom folders entered.</p>
                                                <p>One folder path per line.</p>
                                                <i></i> <!-- do not delete this line -->
                                            </div>
                                        </span>
                                    </div>
                                    <p></p>
                                </div>
                            </div>
                            <div>
                                <textarea option="setting" name="custom_folders" style="width:100%; height:100px; text-align:left;" placeholder="Examples:
/uploads/2022/05
/var/www/html/yourdomain.com/wordpress/wp-content/uploads/2022/05"><?php echo $folders?></textarea>
                                <p><span>Tip: the setting will effect both real-time and automatic optimization.</span></p>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell">Compression mode</label></td>
                <td>
                    <fieldset>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="setting" name="quality" value="lossless" <?php esc_attr_e($lossless); ?> /><?php _e('Lossless','wpvivid-imgoptim')?>
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;"><?php _e('Lossy','wpvivid-imgoptim')?>
                            <input type="radio" option="setting" name="quality" value="lossy" <?php esc_attr_e($lossy); ?> />
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;"><?php _e('Super','wpvivid-imgoptim')?>
                            <input type="radio" option="setting" name="quality" value="super" <?php esc_attr_e($super); ?> />
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;"><?php _e('Custom','wpvivid-imgoptim')?>
                            <input type="radio" option="setting" name="quality" value="custom" <?php esc_attr_e($custom); ?> />
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                    </fieldset>
                    <p></p>
                    <div id="wpvivid_imgoptim_custom_compress" <?php echo $custom_css; ?> >
                        <input id="wpvivid_imgoptim_custom_compress_slider" type="range" value="<?php esc_attr_e($custom_quality) ?>" min="1" max="99"/>
                        <output id="wpvivid_imgoptim_custom_compress_output" ><?php esc_attr_e($custom_quality) ?></output>
                        <input style="display: none" type="text" readonly option="setting" name="custom_quality" value="<?php esc_attr_e($custom_quality) ?>">
                    </div>
                    <div style="border:1px solid #eee; padding:0 1em 0 1em;margin:1em 0 1em 0;">
                        <p><span>Lossless: </span><span>Compress the image by up to 10%</span></p>
                        <p><span>Lossy: </span><span>Compress the image by up to 20%(conservatively)</span></p>
                        <p><span>Super: </span><span>Compress the image by up to 30-40%(optimistically)</span></p>
                        <p><span>Custom: </span><span>A lower value means a higher compression rate, but a reduction in image quality. The recommended value is 80</span></p>
                    </div>
                    <div>
                        <label class="wpvivid-checkbox">
                            <span><?php _e('Compress GIF Images','wpvivid-imgoptim')?></span>
                            <input type="checkbox" option="setting" name="opt_gif" <?php esc_attr_e($gif); ?>>
                            <span class="wpvivid-checkbox-checkmark"></span>
                        </label>
                    </div>
                    <p></p>
                    <div>
                        <label class="wpvivid-checkbox">
                            <span><?php _e('Leave EXIF data','wpvivid-imgoptim')?></span>
                            <input type="checkbox" option="setting" name="keep_exif" <?php esc_attr_e($keep_exif); ?>>
                            <span class="wpvivid-checkbox-checkmark"></span>
                        </label>
                    </div>
                    <p></p>
                    <div>
                        <label class="wpvivid-checkbox">
                            <span><?php _e('With the option checked, you can choose the number of colors in each GIF when it is being optimized, from 2-256. The lower the number, the smaller the GIF size, but it may result in image quality loss. Choose the one with the best size/quality ratio for your needs.The recommended value is 64.','wpvivid-imgoptim')?></span>
                            <input type="checkbox" option="setting" name="optimize_gif_color" <?php esc_attr_e($optimize_gif_color); ?>>
                            <span class="wpvivid-checkbox-checkmark"></span>
                        </label>
                        <p></p>
                        <select id="wpvivid_imgoptim_optimize_gif_colors" option="setting" name="gif_colors" style="margin-bottom: 3px;">
                            <option value="2">2 colors</option>
                            <option value="4">4 colors</option>
                            <option value="8">8 colors</option>
                            <option value="16">16 colors</option>
                            <option value="32">32 colors</option>
                            <option value="64">64 colors</option>
                            <option value="128">128 colors</option>
                            <option value="256">256 colors</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell"><?php _e('Resizing large images','wpvivid-imgoptim')?></label></td>
                <td>
                    <div>
                        <label class="wpvivid-checkbox">
                            <span><?php _e('Enable auto-resizing large images','wpvivid-imgoptim')?></span>
                            <input type="checkbox"  option="setting" name="resize" <?php esc_attr_e($resize); ?> />
                            <span class="wpvivid-checkbox-checkmark"></span>
                            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                            <div class="wpvivid-bottom">
                                <!-- The content you need -->
                                <p>This option allows you to enter a width and height, so large images will be proportionately resized upon upload. For example, if you set 1280 px for the width, all large images will be resized in proportion to 1280 px in width upon upload.</p>
                                <i></i> <!-- do not delete this line -->
                            </div>
                        </span>
                        </label>
                    </div>
                    <p></p>

                    <fieldset>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="setting" name="resize_method" value="auto" <?php esc_attr_e($resize_method_auto); ?> />Auto Resize
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="setting" name="resize_method" value="width" <?php esc_attr_e($resize_method_width); ?> />Fixed Width
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="setting" name="resize_method" value="height" <?php esc_attr_e($resize_method_height); ?> />Fixed Height
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                            <div class="wpvivid-bottom">
                                <!-- The content you need -->
                                <p>Auto Resize: Resize large images intelegently by using WordPress API. Sometimes images won't be resized to the exact size you set, but to a very close size.</p>
                                <p>Fixed Width: Resize large images to the width you set. Height will be automatically resized with the same ratio as the width.</p>
                                <p>Fixed Height: Resize large images to the height you set. Width will be automatically resized with the same ratio as the height.</p>
                                <i></i> <!-- do not delete this line -->
                            </div>
                        </span>
                    </fieldset>

                    <p></p>
                    <label style="display: inline-block;min-width: 60px" for="wpvivid_resize_width">Width</label><input id="wpvivid_resize_width" placeholder="2560" type="text" option="setting" name="resize_width" value="<?php esc_attr_e($resize_width); ?>" onkeyup="value=value.replace(/\D/g,'')" /> px
                    <p></p>
                    <label style="display: inline-block;min-width: 60px" for="wpvivid_resize_height">Height</label><input id="wpvivid_resize_height" placeholder="2560" type="text" option="setting" name="resize_height" value="<?php esc_attr_e($resize_height); ?>" onkeyup="value=value.replace(/\D/g,'')" /> px
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell"><?php _e('Optimize different sizes of images','wpvivid-imgoptim')?></label></td>
                <td>
                    <?php
                    $first=true;
                    foreach ($image_sizes as $size_key=>$size)
                    {
                        if($size['skip'])
                        {
                            $checked='';
                        }
                        else
                        {
                            $checked='checked';
                        }

                        if($first)
                        {
                            $first=false;
                        }
                        else
                        {
                            echo '<p></p>';
                        }

                        if($size_key=='og')
                        {
                            $text='Original image';
                            echo '<label class="wpvivid-checkbox">
                                    <span>'.$text.'</span>
                                    <input type="checkbox" option="setting" name="'.$size_key.'" '.$checked.'/>
                                    <span class="wpvivid-checkbox-checkmark"></span>
                               </label>';
                        }
                        else
                        {
                            $text=$size_key.' ('.$size['width'].'x'.$size['height'].')';
                            echo '<label class="wpvivid-checkbox">
                                    <span>'.$text.'</span>
                                    <input type="checkbox" option="setting" name="'.$size_key.'" '.$checked.'/>
                                    <span class="wpvivid-checkbox-checkmark"></span>
                               </label>';
                        }
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell"><?php _e('Convert images','wpvivid-imgoptim')?></label></td>
                <td>
                    <fieldset>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;"><?php _e('Convert JPG and PNG to Webp','wpvivid-imgoptim')?>
                            <input type="checkbox" option="setting" name="convert" <?php esc_attr_e($convert); ?>/>
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                    </fieldset>
                    <p></p>
                    <fieldset>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;"><?php _e('Convert GIF to Webp','wpvivid-imgoptim')?>
                            <input type="checkbox" option="setting" name="gif_convert" <?php esc_attr_e($gif_webp_convert); ?>/>
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                    </fieldset>
                    <p></p>
                    <div class="wpvivid-one-coloum" style="border:1px solid #f1f1f1;">
                        <label class="wpvivid-checkbox">
                            <span><?php _e('Enable Webp format on your site, ','wpvivid-imgoptim')?></span>
                            <a href="https://docs.wpvivid.com/wpvivid-image-optimization-pro-convert-to-webp-notes.html" target="_blank">Learn more</a>
                            <input type="checkbox" option="setting" name="display_enable" <?php esc_attr_e($display_enable); ?>>
                            <span class="wpvivid-checkbox-checkmark"></span>
                        </label>
                        <p></p>
                        <fieldset>
                            <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                                <input type="radio" option="setting" name="webp_display" value="pic" <?php esc_attr_e($display_pic); ?> />Use <code>picture</code> tag (Does not support images in CSS)
                                <span class="wpvivid-radio-checkmark"></span>
                            </label>
                            <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                                <input type="radio" option="setting" name="webp_display" value="rewrite" <?php esc_attr_e($display_rewrite); ?> />Use <code>rewrite</code> rule (Only supports Apache servers)
                                <span class="wpvivid-radio-checkmark"></span>
                            </label>
                        </fieldset>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <span>Exclude images by folder/file path</span>
                </td>
                <td>
                    <label class="wpvivid-checkbox">
                        <span><?php _e('Exclude by directory path','wpvivid-imgoptim')?></span>
                        <input type="checkbox" option="setting" name="enable_exclude_path" <?php esc_attr_e($enable_exclude_path); ?> />
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                    <p></p>
                    <textarea placeholder="Example:&#10;/wp-content/upload/19/03/&#10;/wp-content/upload/19/04/" option="setting" name="exclude_path" style="width:100%; height:200px; overflow-x:auto;"><?php echo $exclude_path?></textarea>
                    <p><label class="wpvivid-checkbox">
                            <span><?php _e('Exclude by file path','wpvivid-imgoptim')?></span>
                            <input type="checkbox" option="setting" name="enable_exclude_file" <?php esc_attr_e($enable_exclude_file); ?> />
                            <span class="wpvivid-checkbox-checkmark"></span>
                        </label>
                    </p>
                    <textarea placeholder="Example:&#10;/wp-content/upload/19/03/test1.png&#10;/wp-content/upload/19/03/test2.jpg" option="setting" name="exclude_file" style="width:100%; height:200px; overflow-x:auto;"><?php echo $exclude_file?></textarea>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell"><?php _e('Image backup','wpvivid-imgoptim')?></label></td>
                <td>
                    <label class="wpvivid-checkbox">
                        <span><?php _e('Enable image backup before optimization','wpvivid-imgoptim')?></span>
                        <input type="checkbox" option="setting" name="image_backup" <?php esc_attr_e($backup); ?> />
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                    <p></p>
                    <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                    <span><?php _e('Image backup folder','wpvivid-imgoptim')?>:</span>
                    <div id="wpvivid_image_custom_backup_path_placeholder">
                        <span><code><?php echo $backup_path_placeholder;?></code></span>
                        <input id="wpvivid_image_custom_backup_path_placeholder_btn" type="button" class="button-primary" value="Change">
                    </div>
                    <div id="wpvivid_image_custom_backup_path" style="display: none">
                        <span><code><?php echo $backup_path_prefix;?></code></span>
                        <input type="text" option="setting" name="image_backup_path" class="all-options" value="<?php esc_attr_e($backup_path, 'wpvivid-imgoptim'); ?>" onkeyup="value=value.replace(/[^\a-\z\A-\Z0-9]/g,'')" onpaste="value=value.replace(/[^\a-\z\A-\Z0-9]/g,'')" />
                    </div>
                    <p></p>
                    <span><?php _e("Click 'Empty' button to delete images backup",'wpvivid')?></span>
                    <input id="wpvivid_empty_image_backup_btn" type="button" class="button-primary" value="Empty">
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell"><?php _e('Clean logs','wpvivid-imgoptim')?></label></td>
                <td>
                    <label class="wpvivid-checkbox">
                        <span><?php _e('Delete logs of image optimization automatically every week.','wpvivid-imgoptim')?></span>
                        <input type="checkbox" option="setting" name="empty_image_log" <?php esc_attr_e($empty_image_log); ?> />
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <label for="tablecell">
                        <?php _e('Max memory limit','wpvivid-imgoptim')?>
                    </label>
                </td>
                <td>
                    <input type="text" placeholder="256" option="setting" name="image_optimization_memory_limit" value="<?php esc_attr_e($memory_limit); ?>" onkeyup="value=value.replace(/\D/g,'')" /> M
                    <span style="margin-top: 4px" class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                        <div class="wpvivid-bottom">
                            <!-- The content you need -->
                            <p>The maximum PHP memory for image optimization. Try to increase the value if you encounter a memory exhausted error.</p>
                            <i></i> <!-- do not delete this line -->
                        </div>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <label for="tablecell">
                        <?php _e('Max optimized count','wpvivid')?>
                    </label>
                </td>
                <td>
                    <input type="text" placeholder="15" option="setting" name="max_allowed_optimize_count" value="<?php esc_attr_e($max_allowed_optimize_count); ?>" onkeyup="value=value.replace(/\D/g,'')" /> Image(s)
                    <span style="margin-top: 4px" class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                        <div class="wpvivid-bottom">
                            <!-- The content you need -->
                            <p>The number of images that will be optimized per request. The higher the value, the faster the optimization, but the greater the chance of a timeout error. If you get an optimization timeout error, try lowering this value. The default and recommended value is 15.</p>
                            <i></i> <!-- do not delete this line -->
                        </div>
                    </span>
                </td>
            </tr>
        </table>

        <br>
        <script>
            jQuery('#wpvivid_empty_image_backup_btn').click(function()
            {
                var descript = 'Are you sure you want to delete images backup?';
                var ret = confirm(descript);
                if(ret === true)
                {
                    var ajax_data = {
                        'action': 'wpvivid_delete_all_images_backup_ex'
                    };

                    wpvivid_post_request_addon(ajax_data, function (data)
                    {
                        try
                        {
                            var jsonarray = jQuery.parseJSON(data);
                            if (jsonarray.result === 'success')
                            {
                                alert('Delete images backup successfully');
                            }
                            else if(jsonarray.result === 'failed')
                            {
                                alert(jsonarray.error);
                            }
                        }
                        catch(err){
                            alert(err);
                        }
                    }, function (XMLHttpRequest, textStatus, errorThrown)
                    {
                        var error_message = wpvivid_output_ajaxerror('deleting images backup', textStatus, errorThrown);
                        alert(error_message);
                    });
                }
            });
            jQuery('#wpvivid_image_custom_backup_path_placeholder_btn').click(function()
            {
                jQuery('#wpvivid_image_custom_backup_path_placeholder').hide();
                jQuery('#wpvivid_image_custom_backup_path').show();
            });
            jQuery('#wpvivid_image_custom_backup_path_placeholder_btn').click(function()
            {
                jQuery('#wpvivid_image_custom_backup_path_placeholder').hide();
                jQuery('#wpvivid_image_custom_backup_path').show();
            });
            jQuery('input:radio[option=setting][name=quality]').click(function()
            {
                var quality=jQuery(this).val();
                if(quality=='custom')
                {
                    jQuery('#wpvivid_imgoptim_custom_compress').show();
                }
                else
                {
                    jQuery('#wpvivid_imgoptim_custom_compress').hide();
                }
            });

            jQuery('#wpvivid_imgoptim_custom_compress_slider').change(function()
            {
                var rang=jQuery('#wpvivid_imgoptim_custom_compress_slider').val();
                jQuery('input:text[option=setting][name=custom_quality]').val(rang);
                jQuery('#wpvivid_imgoptim_custom_compress_output').html(rang);
            });

            jQuery('#wpvivid_imgoptim_optimize_gif_colors').change(function()
            {
                var rang=jQuery('#wpvivid_imgoptim_optimize_gif_colors').val();
                var a = Math.pow(2,rang);
                jQuery('input:text[option=setting][name=gif_colors]').val(a);
                jQuery('#wpvivid_imgoptim_optimize_gif_colors_output').html(a);
            });
            //

            jQuery('#wpvivid_ping_image_server_btn').click(function(){
                var region = jQuery('select[option=setting][name=region]').val();

                var ajax_data = {
                    'action': 'wpvivid_ping_image_server_time',
                    'region': region
                };

                jQuery('#wpvivid_ping_image_server_btn').css({'pointer-events': 'none', 'opacity': '0.4'});
                jQuery('#wpvivid_ping_imaga_server_time').val();
                jQuery('#wpvivid_ping_imaga_server_time').hide();
                wpvivid_post_request_addon(ajax_data, function (data)
                {
                    jQuery('#wpvivid_ping_image_server_btn').css({'pointer-events': 'auto', 'opacity': '1'});
                    jQuery('#wpvivid_ping_imaga_server_time').show();
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_ping_imaga_server_time').html(jsonarray.time);
                        }
                        else if(jsonarray.result === 'failed')
                        {
                            jQuery('#wpvivid_ping_imaga_server_time').html(jsonarray.error);
                        }
                    }
                    catch(err){
                        jQuery('#wpvivid_ping_imaga_server_time').html(err);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    jQuery('#wpvivid_ping_image_server_btn').css({'pointer-events': 'auto', 'opacity': '1'});
                    jQuery('#wpvivid_ping_imaga_server_time').show();
                    var error_message = wpvivid_output_ajaxerror('ping images server', textStatus, errorThrown);
                    jQuery('#wpvivid_ping_imaga_server_time').html(error_message);
                });
            });

            jQuery(document).ready(function($)
            {
                $('select[option=setting][name=region]').val('<?php echo $selected;?>');
                $('select[option=setting][name=auto_schedule_cycles]').val('<?php echo $auto_schedule_cycles;?>');
                $('#wpvivid_imgoptim_optimize_gif_colors').val('<?php echo $gif_colors;?>');
            });
        </script>
        <?php
    }

    public function check_setting_option($setting)
    {
        $options=get_option('wpvivid_optimization_options',array());

        if(isset($setting['auto_optimize']))
            $options['auto_optimize']=$setting['auto_optimize'];

        if(isset($setting['keep_exif']))
            $options['keep_exif']=$setting['keep_exif'];

        if(isset($setting['quality']))
            $options['quality']=$setting['quality'];
        if(isset($setting['custom_quality']))
            $options['custom_quality']=$setting['custom_quality'];

        if(isset($setting['resize']))
            $options['resize']['enable']=$setting['resize'];
        if(isset($setting['resize_width']))
            $options['resize']['width']=$setting['resize_width'];
        if(isset($setting['resize_height']))
            $options['resize']['height']=$setting['resize_height'];
        if(isset($setting['resize_method']))
            $options['resize']['method']=$setting['resize_method'];

        if(isset($setting['convert']))
            $options['webp']['convert']=intval($setting['convert']);
        if(isset($setting['gif_convert']))
            $options['webp']['gif_convert']=intval($setting['gif_convert']);
        if(isset($setting['display_enable']))
            $options['webp']['display_enable']=$setting['display_enable'];
        if(isset($setting['webp_display']))
            $options['webp']['display']=$setting['webp_display'];

        if(isset($setting['display_enable']) && $setting['display_enable'] == '1' &&
            isset($setting['webp_display']) && $setting['webp_display'] === 'rewrite')
        {
            $insert_rewrite_rule = true;
        }
        else
        {
            $insert_rewrite_rule = false;
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
                    if($insert_rewrite_rule)
                    {
                        $extensions = $this->get_extensions_pattern();
                        $home_root  = wp_parse_url( home_url( '/' ) );
                        $home_root  = $home_root['path'];

                        $line[]='<IfModule mod_setenvif.c>';
                        $line[]='# Vary: Accept for all the requests to jpeg, png, and gif.';
                        $line[]='SetEnvIf Request_URI "\.(' . $extensions . ')$" REQUEST_image';
                        $line[]='</IfModule>';

                        $line[]='<IfModule mod_rewrite.c>';
                        $line[]='RewriteEngine On';
                        $line[]='RewriteBase ' . $home_root . '';
                        $line[]='# Check if browser supports WebP images.';
                        $line[]='RewriteCond %{HTTP_ACCEPT} image/webp';
                        $line[]='# Check if WebP replacement image exists.';
                        $line[]='RewriteCond %{REQUEST_FILENAME}.webp -f';
                        $line[]='# Serve WebP image instead.';
                        $line[]='RewriteRule (.+)\.(' . $extensions . ')$ $1.$2.webp [T=image/webp,NC]';
                        $line[]='</IfModule>';

                        $line[]='<IfModule mod_headers.c>';
                        $line[]='Header append Vary Accept env=REQUEST_image';
                        $line[]='</IfModule>';

                        $line[]='<IfModule mod_mime.c>';
                        $line[]='AddType image/webp .webp';
                        $line[]='</IfModule>';
                        insert_with_markers($htaccess_file,'WPvivid Rewrite Rule for Webp',$line);
                    }
                    else
                    {
                        insert_with_markers($htaccess_file,'WPvivid Rewrite Rule for Webp','');
                    }

                }
            }
        }

        if(isset($setting['image_backup_path']))
            $options['backup_path']=$setting['image_backup_path'];
        //image_backup_path
        if(isset($setting['og']))
        {
            $options['skip_size']['og']=!$setting['og'];
        }
        else
        {
            $options['skip_size']['og']=false;
        }

        $intermediate_image_sizes = get_intermediate_image_sizes();

        foreach ($intermediate_image_sizes as $size_key)
        {
            if(isset($setting[$size_key]))
            {
                $options['skip_size'][$size_key]=!$setting[$size_key];
            }
            else
            {
                $options['skip_size'][$size_key]=false;
            }
        }

        if(isset($setting['image_backup']))
            $options['backup']=$setting['image_backup'];

        if(isset($setting['enable_exclude_file']))
            $options['enable_exclude_file']=$setting['enable_exclude_file'];
        if(isset($setting['exclude_file']))
            $options['exclude_file']=$setting['exclude_file'];
        if(isset($setting['enable_exclude_path']))
            $options['enable_exclude_path']=$setting['enable_exclude_path'];
        if(isset($setting['exclude_path']))
            $options['exclude_path']=$setting['exclude_path'];

        if(isset($setting['region']))
        {
            delete_option('wpvivid_get_optimization_url_ex');
            $options['region']=$setting['region'];
        }

        if(isset($setting['auto_optimize_type']))
            $options['auto_optimize_type']=$setting['auto_optimize_type'];
        if(isset($setting['auto_schedule_cycles']))
            $options['auto_schedule_cycles']=$setting['auto_schedule_cycles'];

        $auto_optimize_type=isset($options['auto_optimize_type'])?$options['auto_optimize_type']:'upload';
        $auto_schedule_cycles=isset($options['auto_schedule_cycles'])?$options['auto_schedule_cycles']:'wpvivid_10minutes';

        if($auto_optimize_type=='schedule')
        {
            if(wp_get_schedule('wpvivid_imgoptim_auto_event')!==false)
            {
                wp_clear_scheduled_hook('wpvivid_imgoptim_auto_event');
                $timestamp = wp_next_scheduled('wpvivid_imgoptim_auto_event');
                wp_unschedule_event($timestamp,'wpvivid_imgoptim_auto_event');
            }

            wp_schedule_event(time()+30, $auto_schedule_cycles, 'wpvivid_imgoptim_auto_event');
        }
        else
        {
            if(wp_get_schedule('wpvivid_imgoptim_auto_event')!==false)
            {
                wp_clear_scheduled_hook('wpvivid_imgoptim_auto_event');
                $timestamp = wp_next_scheduled('wpvivid_imgoptim_auto_event');
                wp_unschedule_event($timestamp,'wpvivid_imgoptim_auto_event');
            }
        }

        if(isset($setting['optimize_type']))
            $options['optimize_type']=$setting['optimize_type'];
        if(isset($setting['custom_folders']))
            $options['custom_folders']=$setting['custom_folders'];

        if(isset($setting['optimize_gif_color']))
            $options['optimize_gif_color']=$setting['optimize_gif_color'];
        if(isset($setting['gif_colors']))
            $options['gif_colors']=$setting['gif_colors'];

        if(isset($setting['image_optimization_memory_limit']))
        {
            $options['image_optimization_memory_limit']=max(256,intval($setting['image_optimization_memory_limit']));
        }

        if(isset($setting['max_allowed_optimize_count']))
        {
            $options['max_allowed_optimize_count']=max(1,intval($setting['max_allowed_optimize_count']));
        }
        //

        if(isset($setting['opt_gif']))
        {
            $options['opt_gif']=$setting['opt_gif'];
        }

        //empty log
        if(isset($setting['empty_image_log']))
        {
            $options['empty_image_log']=$setting['empty_image_log'];
        }

        update_option('wpvivid_optimization_options',$options,'no');

        $ret['result']='success';
        return $ret;
    }

    public function get_extensions_pattern()
    {
        $extensions = $this->imagify_get_mime_types( 'image' );
        $extensions = array_keys( $extensions );

        return implode( '|', $extensions );
    }

    public function imagify_get_mime_types( $type = null ) {
        $mimes = array();

        if ( 'not-image' !== $type ) {
            $mimes = array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png'          => 'image/png',
                'gif'          => 'image/gif',
            );
        }

        if ( 'image' !== $type ) {
            $mimes['pdf'] = 'application/pdf';
        }

        return $mimes;
    }

    public function edit_path($white_label_slug)
    {
        $optimization_options=get_option('wpvivid_optimization_options',array());
        $search = 'wpvivid';
        $label_slug = strtolower($white_label_slug);
        $optimization_options['backup_path'] = str_replace($search, $label_slug, $optimization_options['backup_path']);
        update_option('wpvivid_optimization_options', $optimization_options,'no');
    }
}