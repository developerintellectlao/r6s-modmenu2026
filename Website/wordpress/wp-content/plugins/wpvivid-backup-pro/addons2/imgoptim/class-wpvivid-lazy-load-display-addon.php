<?php

/**
 * WPvivid addon: yes
 * Addon Name: wpvivid-imgoptim-pro
 * Description: Pro
 * Version: 2.2.27
 * Need_init: yes
 * Admin_load: yes
 * Interface Name: WPvivid_Lazy_Load_Display_Addon
 */
if (!defined('WPVIVID_BACKUP_PRO_PLUGIN_DIR'))
{
    die;
}
class WPvivid_Lazy_Load_Display_Addon
{
    function __construct()
    {
        add_action('wp_ajax_wpvivid_lazyload_save_addon',array($this, 'lazyload_save'));

        add_filter('wpvivid_get_role_cap_list',array($this, 'get_caps'));
        add_filter('wpvivid_lazyload_skip_file',array($this, 'skip_file'),10,2);

        add_filter('wpvivid_image_optimization_page',array($this, 'add_page'),10, 2);

        $options=get_option('wpvivid_optimization_options',array());
        $enable=isset($options['lazyload']['enable'])?$options['lazyload']['enable']:false;
        if($enable)
        {
            add_filter( 'wp_lazy_loading_enabled', '__return_false' );
        }
    }

    public function add_page($tabs, $first_tab)
    {
        if( apply_filters('wpvivid_current_user_can',true,'wpvivid-can-use-lazy-load'))
        {
            if(!$first_tab)
            {
                $args['div_style']='padding-top:0;display:block;';
                $first_tab=true;
            }
            else
            {
                $args['div_style']='padding-top:0;';
            }
            $args['span_class']='dashicons dashicons-update wpvivid-dashicons-green';
            $args['span_style']='margin-top:0.1em;';
            $args['is_parent_tab']=0;
            $tabs['lazyload']['title']='Lazyload';
            $tabs['lazyload']['slug']='lazyload';
            $tabs['lazyload']['callback']=array($this, 'lazyload_page');
            $tabs['lazyload']['args']=$args;
            //lazyload_page
        }
        return $tabs;
    }

    public function skip_file($skip,$src)
    {
        $exclude_regex_file=array();
        $options =$this->get_main_option('wpvivid_optimization_options');
        $enable_exclude_file=isset($options['lazyload']['enable_exclude_file'])?$options['lazyload']['enable_exclude_file']:true;
        if($enable_exclude_file)
        {
            $exclude_file=isset($options['lazyload']['exclude_file'])?$options['lazyload']['exclude_file']:'';
            if(!empty($exclude_file))
            {
                $exclude_file=explode("\n", $exclude_file);
                foreach ($exclude_file as $item)
                {
                    if(!empty($item))
                    {
                        $exclude_regex_file[]='#'.preg_quote($this -> transfer_path($item), '/').'#';
                    }
                }
            }
        }
        else
        {
            return $skip;
        }
        if(empty($exclude_regex_file))
        {
            return $skip;
        }

        if(!$this->regex_match($exclude_regex_file, $src, 0))
        {
            return true;
        }
        else
        {
            return false;
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

    private function transfer_path($path)
    {
        $path = str_replace('\\','/',$path);
        $values = explode('/',$path);
        return implode(DIRECTORY_SEPARATOR,$values);
    }

    public function get_caps($cap_list)
    {
        $cap['slug']='wpvivid-can-use-lazy-load';
        $cap['display']='Lazyload';
        $cap['menu_slug']=strtolower(sprintf('%s-lazyload', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
        $cap['index']=21;
        $cap['icon']='<span class="dashicons dashicons-update wpvivid-dashicons-grey"></span>';
        $cap_list[$cap['slug']]=$cap;

        return $cap_list;
    }

    public function display()
    {
        ?>
        <div class="wrap wpvivid-canvas">
            <div id="icon-options-general" class="icon32"></div>
            <h1><?php esc_attr_e( apply_filters('wpvivid_white_label_display', 'WPvivid').' Plugins - Lazyload', 'wpvivid' ); ?></h1>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="wpvivid-backup">
                                <?php
                                $this->welcome_bar();
                                ?>
                                <div class="wpvivid-canvas wpvivid-clear-float">
                                    <div class="wpvivid-one-coloum">
                                        <?php
                                        $this->setting();
                                        ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php $this->sidebar();?>
                </div>
            </div>
        </div>
        <?php
    }

    public function lazyload_page()
    {
        ?>
        <div class="wpvivid-one-coloum">
            <?php
            $this->setting();
            ?>
        </div>
        <?php
    }

    public function welcome_bar()
    {
        ?>
        <div class="wpvivid-welcome-bar wpvivid-clear-float">
            <div class="wpvivid-welcome-bar-left">
                <p></p>
                <div>
                    <span class="dashicons dashicons-update wpvivid-dashicons-large wpvivid-dashicons-green"></span>
                    <span class="wpvivid-page-title"><?php _e('Lazyload', 'wpvivid-imgoptim'); ?>
            </span><p></p>
                </div>
                <span class="about-description"><?php _e('This page allows you to enable lazy loading on your website to delay loading images or medias on your website pages until they are needed.', 'wpvivid-imgoptim'); ?></span>
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
            <div class="wpvivid-nav-bar wpvivid-clear-float">
                <span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span>
                <span> <?php _e('Enabling lazy loading can speed up your website pages loading time and improve your Google PageSpeed Insights score, which is recommended.', 'wpvivid-imgoptim'); ?></span>
            </div>
        </div>
        <?php
    }

    public function sidebar()
    {
        do_action('wpvivid_add_sidebar_image_optimization');
    }

    public function setting()
    {
        $options =$this->get_main_option('wpvivid_optimization_options');

        $options['lazyload']=isset($options['lazyload'])?$options['lazyload']:array();
        $enable=isset($options['lazyload']['enable'])?$options['lazyload']['enable']:false;
        if($enable)
        {
            $enable='checked';
        }
        else
        {
            $enable='';
        }


        if(isset($options['lazyload']['extensions']))
        {
            $jpg=array_key_exists('jpg|jpeg|jpe',$options['lazyload']['extensions'])?$options['lazyload']['extensions']['jpg|jpeg|jpe']:true;
            $png=array_key_exists('png',$options['lazyload']['extensions'])?$options['lazyload']['extensions']['png']:true;
            $gif=array_key_exists('gif',$options['lazyload']['extensions'])?$options['lazyload']['extensions']['gif']:true;
            $svg=array_key_exists('svg',$options['lazyload']['extensions'])?$options['lazyload']['extensions']['svg']:true;
            if($jpg)
                $jpg='checked';
            if($png)
                $png='checked';
            if($gif)
                $gif='checked';
            if($svg)
                $svg='checked';
        }
        else
        {
            $jpg='checked';
            $png='checked';
            $gif='checked';
            $svg='checked';
        }

        $content=isset($options['lazyload']['content'])?$options['lazyload']['content']:true;
        $thumbnails=isset($options['lazyload']['thumbnails'])?$options['lazyload']['thumbnails']:true;

        if($content)
            $content='checked';
        if($thumbnails)
            $thumbnails='checked';

        $js=isset($options['lazyload']['js'])?$options['lazyload']['js']:'footer';

        if($js=='footer')
        {
            $footer='checked';
            $header='';
        }
        else
        {
            $footer='';
            $header='checked';
        }

        $noscript=isset($options['lazyload']['noscript'])?$options['lazyload']['noscript']:true;

        if($noscript)
            $noscript='checked';

        $animation=isset($options['lazyload']['animation'])?$options['lazyload']['animation']:'fadein';
        if($animation=='fadein')
        {
            $fade_in='checked';
            $spinner='';
            $placeholder='';
            $none='';
        }
        else
        {
            $fade_in='';
            $spinner='';
            $placeholder='';
            $none='checked';
        }

        $enable_exclude_file=isset($options['lazyload']['enable_exclude_file'])?$options['lazyload']['enable_exclude_file']:true;
        if($enable_exclude_file)
        {
            $enable_exclude_file='checked';
        }
        else
        {
            $enable_exclude_file='';
        }
        $exclude_file=isset($options['lazyload']['exclude_file'])?$options['lazyload']['exclude_file']:'';
        /*
        else if($animation=='spinner')
        {
            $fade_in='';
            $spinner='checked';
            $placeholder='';
        }
        else if($animation=='placeholder')
        {
            $fade_in='';
            $spinner='';
            $placeholder='checked';
        }*/

        ?>
        <table class="widefat" style="border-left:none;border-top:none;border-right:none;">
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <label for="tablecell"><?php _e('Enable/Disable lazyload', 'wpvivid-imgoptim'); ?></label>
                </td>
                <td>
                    <span>
                        <label class="wpvivid-switch">
                            <input type="checkbox" option="lazyload" name="enable" <?php esc_attr_e($enable); ?> >
                            <span class="wpvivid-slider wpvivid-round"></span>
                        </label>
                        <span>
                            <strong><?php _e('Enable lazyload', 'wpvivid-imgoptim'); ?></strong>
                        </span>
                        <?php _e('Once enabled, the plugin will delay loading images on your website site pages until visitors scroll down to them, hence speeding up your website pages loading time and improving your Google PageSpeed Insights score.', 'wpvivid-imgoptim'); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <label for="tablecell"><?php _e('Media type to lazyload', 'wpvivid-imgoptim'); ?></label>
                </td>
                <td>
                    <label class="wpvivid-checkbox">
                        <span>.jpg | .jpeg</span>
                        <input type="checkbox" option="lazyload" name="jpg" <?php esc_attr_e($jpg); ?> />
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                    <p></p>
                    <label class="wpvivid-checkbox">
                        <span>.png</span>
                        <input type="checkbox" option="lazyload" name="png" <?php esc_attr_e($png); ?> />
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                    <p></p>
                    <label class="wpvivid-checkbox">
                        <span>.gif</span>
                        <input type="checkbox" option="lazyload" name="gif" <?php esc_attr_e($gif); ?> />
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                    <p></p>
                    <label class="wpvivid-checkbox">
                        <span>.svg</span>
                        <input type="checkbox" option="lazyload" name="svg" <?php esc_attr_e($svg); ?> />
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell"><?php _e('Lazyload works on locations', 'wpvivid'); ?></label></td>
                <td>
                    <label class="wpvivid-checkbox">
                        <span>Content</span>
                        <input type="checkbox" option="lazyload" name="content" <?php esc_attr_e($content); ?>>
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                    <p></p>
                    <label class="wpvivid-checkbox">
                        <span>Thumbnails</span>
                        <input type="checkbox" option="lazyload" name="thumbnails" <?php esc_attr_e($thumbnails); ?>>
                        <span class="wpvivid-checkbox-checkmark"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;"><label for="tablecell"><?php _e('Browsers compatibility', 'wpvivid'); ?></label></td>
                <td>
                    <div>
                        <label class="wpvivid-checkbox">
                            <span>Use <code>noscript</code> tag</span>
                            <input type="checkbox" option="lazyload" name="noscript" <?php esc_attr_e($noscript); ?> />
                            <span class="wpvivid-checkbox-checkmark"></span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <label for="tablecell"><?php _e('Location where scripts insert', 'wpvivid-imgoptim'); ?></label>
                </td>
                <td>
                    <p><?php _e('The', 'wpvivid-imgoptim'); ?> <code>wp_header()</code> <?php _e('and', 'wpvivid-imgoptim'); ?> <code>wp_footer()</code> <?php _e('function are required for your theme', 'wpvivid-imgoptim'); ?></p>
                    <fieldset>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="lazyload" name="js" value="footer" <?php esc_attr_e($footer); ?> />footer
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">header
                            <input type="radio" option="lazyload" name="js" value="header" <?php esc_attr_e($header); ?> />
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                    </fieldset>
                    <p><?php _e('The plugin will load it’s scripts in the footer by default to speed up page loading times. Switch to the header option if you have problems', 'wpvivid-imgoptim'); ?>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <label for="tablecell"><?php _e('Animation', 'wpvivid'); ?></label>
                </td>
                <td>
                    <fieldset>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio"option="lazyload" name="lazyload_display" value="fadein" <?php esc_attr_e($fade_in); ?> /><?php _e('Fade in', 'wpvivid'); ?>
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="lazyload" name="lazyload_display" value="none" <?php esc_attr_e($none); ?> /><?php _e('None', 'wpvivid'); ?>
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <!--label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="lazyload" name="lazyload_display" value="spinner" <?php esc_attr_e($spinner); ?> /><?php _e('Spinner', 'wpvivid'); ?>
                            <span class="wpvivid-radio-checkmark"></span>
                        </label>
                        <label class="wpvivid-radio" style="float:left; padding-right:1em;">
                            <input type="radio" option="lazyload" name="lazyload_display" value="placeholder" <?php esc_attr_e($placeholder); ?> /><?php _e('Placeholder', 'wpvivid'); ?>
                            <span class="wpvivid-radio-checkmark"></span>
                        </label-->
                    </fieldset>
                </td>
            </tr>
            <tr>
                <td class="row-title" style="min-width:200px;">
                    <span>Exclude images from lazy loading</span>
                </td>
                <td>
                    <p><label class="wpvivid-checkbox">
                            <span><?php _e('Exclude by file path','wpvivid-imgoptim')?></span>
                            <input type="checkbox" option="lazyload" name="enable_exclude_file" <?php esc_attr_e($enable_exclude_file); ?> />
                            <span class="wpvivid-checkbox-checkmark"></span>
                        </label>
                    </p>
                    <textarea placeholder="Example:&#10;test1.png&#10;test2.jpg" option="lazyload" name="exclude_file" style="width:100%; height:200px; overflow-x:auto;"><?php echo $exclude_file?></textarea>
                </td>
            </tr>
        </table>
        <div style="padding:1em 1em 0 0;">
            <input id="wpvivid_lazyload_save" class="button-primary" type="submit" value="Save Changes">
        </div>
        <script>
            jQuery('#wpvivid_lazyload_save').click(function()
            {
                wpvivid_lazyload_save();
            });

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
            function wpvivid_lazyload_save()
            {
                var lazyload = wpvivid_ajax_data_transfer_ex('lazyload');
                var ajax_data = {
                    'action': 'wpvivid_lazyload_save_addon',
                    'lazyload':lazyload
                };

                jQuery('#wpvivid_lazyload_save').css({'pointer-events': 'none', 'opacity': '0.4'});
                wpvivid_post_request_addon(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);

                        jQuery('#wpvivid_lazyload_save').css({'pointer-events': 'auto', 'opacity': '1'});
                        if (jsonarray.result === 'success')
                        {
                            location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvivid-imgoptim', 'wpvivid-imgoptim').'&tab=lazyload'; ?>';
                        }
                        else {
                            alert(jsonarray.error);
                        }
                    }
                    catch (err)
                    {
                        alert(err);
                        jQuery('#wpvivid_lazyload_save').css({'pointer-events': 'auto', 'opacity': '1'});
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown) {
                    jQuery('#wpvivid_lazyload_save').css({'pointer-events': 'auto', 'opacity': '1'});
                    var error_message = wpvivid_output_ajaxerror('Update lazyload setting', textStatus, errorThrown);
                    alert(error_message);
                });
            }
        </script>
        <?php
    }

    public function lazyload_save()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-lazy-load');

        if(isset($_POST['lazyload'])&&!empty($_POST['lazyload']))
        {
            $json_setting = $_POST['lazyload'];
            $json_setting = stripslashes($json_setting);
            $setting = json_decode($json_setting, true);
            if (is_null($setting))
            {
                die();
            }

            $options =$this->get_main_option('wpvivid_optimization_options');

            $options['lazyload']['enable']=$setting['enable'];

            $options['lazyload']['extensions']['jpg|jpeg|jpe']=$setting['jpg'];
            $options['lazyload']['extensions']['png']=$setting['png'];
            $options['lazyload']['extensions']['gif']=$setting['gif'];
            $options['lazyload']['extensions']['svg']=$setting['svg'];

            $options['lazyload']['js']=$setting['js'];

            $options['lazyload']['animation']=$setting['lazyload_display'];

            $options['lazyload']['content']=$setting['content'];
            $options['lazyload']['thumbnails']=$setting['thumbnails'];

            $options['lazyload']['noscript']=$setting['noscript'];

            $options['lazyload']['enable_exclude_file']=$setting['enable_exclude_file'];
            $options['lazyload']['exclude_file']=$setting['exclude_file'];

            $this->update_main_option('wpvivid_optimization_options', $options);

            $ret['result']='success';
            echo json_encode($ret);
        }

        die();
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