<?php

/**
 * WPvivid addon: yes
 * Addon Name: wpvivid-imgoptim-pro
 * Description: Pro
 * Version: 2.2.27
 * Need_init: yes
 * Interface Name: WPvivid_Webp_addon
 */

if (!defined('WPVIVID_BACKUP_PRO_PLUGIN_DIR'))
{
    die;
}

class WPvivid_Webp_addon
{
    public $ngg=false;

    public function __construct()
    {
        if (class_exists( 'C_NextGEN_Bootstrap' ))
        {
            //$this->ngg=new WPvivid_Ngg_Image_Optimize();
        }

        add_filter('wpvivid_is_image_optimized',array($this,'is_image_optimized'),20,2);

        add_filter('wpvivid_imgoptim_generate_meta',array($this,'generate_meta'),20,3);
        add_filter('wpvivid_imgoptim_opt_skip_file_ex', array($this, 'opt_skip_file'), 20, 4);

        //add_filter('wpvivid_do_optimized', array($this, 'do_webp_optimized'), 10,4);
        //add_filter('wpvivid_do_file_optimized',array($this,'do_folders_webp_optimized'),10,2);
        //add_filter('wpvivid_init_image_opt_meta',array($this,'init_image_opt_meta'),20,2);
        //add_action('wpvivid_do_after_optimized', array($this, 'do_after_optimized'), 10,1);
        //add_action('wpvivid_do_after_ngg_optimized', array($this, 'do_after_ngg_optimized'), 10,1);
        add_action('wpvivid_delete_image', array($this, 'do_after_delete_image'), 10,1);
        add_action('wpvivid_restore_image',array($this,'restore_image'),10,1);

        $is_main_site = is_main_site();
        if(!$is_main_site)
        {
            switch_to_blog(get_main_site_id());
        }
        $options =$this->get_main_option('wpvivid_optimization_options');
        if(!$is_main_site)
        {
            restore_current_blog();
        }
        if(!isset($options['webp'])||$options['webp']['display_enable']==false)
        {
            return;
        }

        if ( is_admin() || is_customize_preview() )
        {
            return;
        }

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
        {
            return;
        }

        if ( defined( 'DOING_CRON' ) && DOING_CRON )
        {
            return;
        }

        if($options['webp']['display']=='pic')
        {
            //add_filter( 'the_content', array($this, 'replace_picture_tag'), 1000 );
            //add_filter( 'the_excerpt', array($this, 'replace_picture_tag'), 1000 );
            //add_filter( 'post_thumbnail_html', array($this,'replace_picture_tag') );

            //template_redirect
            add_action( 'template_redirect', [ $this, 'start_content_process' ], 1000 );
            add_action( 'wp_enqueue_scripts', array( $this, 'overrides_main_style' ), 9999 );
        }
        //

    }

    public function overrides_main_style()
    {
        $current_active_theme = get_stylesheet();
        if($current_active_theme === 'Divi')
        {
            $style = '';
            $logo_height = esc_attr( et_get_option( 'logo_height', '54' ) );
            $style      .= "
				picture#logo {
					display: inherit;
				}
				picture#logo source, picture#logo img {
					width: auto;
					max-height: {$logo_height}%;
					vertical-align: middle;
				}
				@media (min-width: 981px) {
					.et_vertical_nav #main-header picture#logo source,
					.et_vertical_nav #main-header picture#logo img {
						margin-bottom: 28px;
					}
				}
			";

            if ( ! empty( $style ) ) {
                wp_add_inline_style( 'divi-style', $style );
            }
        }
    }

    public function start_content_process()
    {
        ob_start( [ $this, 'replace_picture_tag' ] );
    }

    public function replace_picture_tag($content)
    {
        $images = $this->get_images( $content );

        if ( empty( $images ) )
        {
            return $content;
        }
        foreach ( $images as $image )
        {
            $tag     = $this->build_picture_tag( $image );
            $content = str_replace( $image['tag'], $tag, $content );
        }
        return $content;
    }

    protected function get_images( $content )
    {
        if ( preg_match( '/(?=<body).*<\/body>/is', $content, $body ) )
        {
            $content = $body[0];
        }

        $content = preg_replace( '/<!--(.*)-->/Uis', '', $content );

        $content = preg_replace('#<noscript(.*?)>(.*?)</noscript>#is', '', $content);

        if ( ! preg_match_all( '/<img\s.*>/isU', $content, $matches ) )
        {
            return array();
        }

        $images = array_map(array($this, 'process_image'), $matches[0] );
        $images = array_filter( $images );

        if ( ! $images || ! is_array( $images ) )
        {
            return array();
        }

        return $images;
    }

    public function process_image($image)
    {
        $attributes=$this->get_attribute($image);

        $src=$this->get_src($attributes);

        $srcset=$this->get_srcset($attributes);

        $style=$this->get_style($attributes);

        if ( empty($src)&&empty($srcset) )
        {
            return false;
        }

        $data=array(
            'tag'              => $image,
            'attributes'       => $attributes,
            'src'              =>  $src,
            'srcset'           => $srcset,
            'style'            => $style,
        );

        return $data;
    }

    protected function build_picture_tag( $image )
    {
        $to_remove = array(
            'alt'              => '',
            'height'           => '',
            'width'            => '',
            'data-lazy-src'    => '',
            'data-src'         => '',
            'src'              => '',
            'data-lazy-srcset' => '',
            'data-srcset'      => '',
            'srcset'           => '',
            'data-lazy-sizes'  => '',
            'data-sizes'       => '',
            'sizes'            => '',
            'style'            => '',
        );

        $attributes = array_diff_key( $image['attributes'], $to_remove );

        $output = '<picture' . $this->picture_build_attributes( $attributes ) . ">\n";
        $output .= $this->build_source_tag( $image );

        $output .= $this->build_img_tag( $image );
        $output .= "</picture>\n";

        return $output;
    }

    protected function picture_build_attributes( $attributes )
    {
        if ( ! $attributes || ! is_array( $attributes ) )
        {
            return '';
        }

        $out = '';

        foreach ( $attributes as $attribute => $value )
        {
            $out .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
        }

        return $out;
    }

    protected function build_attributes( $attributes )
    {
        if ( ! $attributes || ! is_array( $attributes ) )
        {
            return '';
        }

        $out = '';

        foreach ( $attributes as $attribute => $value )
        {
            $out .= ' ' . $attribute . '="' . esc_attr( $value ) . '"';
        }

        return $out;
    }

    protected function build_source_tag( $image )
    {
        $srcset_source = ! empty( $image['srcset']['srcset_attr'] ) ? $image['srcset']['srcset_attr'] : $image['src']['src_attr'] . 'set';
        $attributes    = array(
            'type'         => 'image/webp',
            $srcset_source => array(),
        );

        if ( ! empty( $image['srcset']['srcs'] ) )
        {
            foreach ( $image['srcset']['srcs'] as $srcset )
            {
                if ( empty( $srcset['webp_url'] ) )
                {
                    continue;
                }
                $attributes[ $srcset_source ][] = $srcset['webp_url'] . ' ' . $srcset['descriptor'];
            }
        }

        if ( empty( $attributes[ $srcset_source ] ) )
        {
            $attributes[ $srcset_source ][] = $image['src']['webp_url'];
        }

        $attributes[ $srcset_source ] = implode( ', ', $attributes[ $srcset_source ] );

        $data_srcset=array( 'data-lazy-srcset', 'data-srcset', 'srcset');

        foreach ($data_srcset as $srcset_attr )
        {
            if ( ! empty( $image['attributes'][ $srcset_attr ] ) && $srcset_attr !== $srcset_source )
            {
                $attributes[ $srcset_attr ] = $image['attributes'][ $srcset_attr ];
            }
        }

        if ( 'srcset' !== $srcset_source && empty( $attributes['srcset'] ) && ! empty( $image['attributes']['src'] ) )
        {
            // Lazyload: the "src" attr should contain a placeholder (a data image or a blank.gif ).
            $attributes['srcset'] = $image['attributes']['src'];
        }

        $data_sizes=array( 'data-lazy-sizes', 'data-sizes', 'sizes');

        foreach ( $data_sizes as $sizes_attr )
        {
            if ( ! empty( $image['attributes'][ $sizes_attr ] ) )
            {
                $attributes[ $sizes_attr ] = $image['attributes'][ $sizes_attr ];
            }
        }

        return '<source' . $this->build_attributes( $attributes ) . "/>\n";
    }

    protected function build_img_tag( $image )
    {
        /*$to_remove = array(
            'class'  => '',
            'id'     => '',
            'style'  => '',
            'title'  => '',
        );*/
        $to_remove = array(
            'id'     => '',
            'title'  => '',
        );

        $attributes = array_diff_key( $image['attributes'], $to_remove );

        return '<img' . $this->build_attributes( $attributes ) . "/>\n";
    }

    public function get_attribute($image)
    {
        if (function_exists("mb_convert_encoding"))
        {
            $image = mb_encode_numericentity($image, [0x80, 0x10FFFF, 0, ~0], 'UTF-8');
            //$image = mb_convert_encoding($image, 'HTML-ENTITIES', 'UTF-8');
        }

        if (class_exists('DOMDocument'))
        {
            $dom = new \DOMDocument();
            @$dom->loadHTML($image);
            $image = $dom->getElementsByTagName('img')->item(0);
            $attributes = array();

            /* This can happen with mismatches, or extremely malformed HTML.
            In customer case, a javascript that did  for (i<imgDefer) --- </script> */
            if (! is_object($image))
                return false;

            foreach ($image->attributes as $attr)
            {
                $attributes[$attr->nodeName] = $attr->nodeValue;
            }
            return $attributes;
        }
        else
        {
            $atts_pattern = '/(?<name>[^\s"\']+)\s*=\s*(["\'])\s*(?<value>.*?)\s*\2/s';

            if ( ! preg_match_all( $atts_pattern, $image, $tmp_attributes, PREG_SET_ORDER ) )
            {
                return false;
            }

            $attributes = array();

            foreach ( $tmp_attributes as $attribute )
            {
                $attributes[ $attribute['name'] ] = $attribute['value'];
            }

            return $attributes;
        }

    }

    public function get_src($attributes)
    {
        $src_source = false;
        $data_tag=array( 'data-lazy-src','data-src', 'src');

        foreach ( $data_tag as $src_attr )
        {
            if ( ! empty( $attributes[ $src_attr ] ) )
            {
                $src_source = $src_attr;
                break;
            }
        }

        if ( ! $src_source )
        {
            // No src attribute.
            return false;
        }

        $extensions = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png'          => 'image/png',
            //'gif'          => 'image/gif',
        );
        $extensions = array_keys( $extensions );
        $extensions = implode( '|', $extensions );

        if ( ! preg_match( '@^(?<src>(?:(?:https?:)?//|/).+\.(?<extension>' . $extensions . '))(?<query>\?.*)?$@i', $attributes[ $src_source ], $src ) )
        {
            // Not a supported image format.
            return false;
        }

        $upload=wp_upload_dir();
        $webp_url  =  $src['src'].'.webp';

        if ( stripos( $webp_url, $upload['baseurl'] ) === 0 )
        {
            $webp_path = str_replace($upload['baseurl'],$upload['basedir'],$webp_url);
        }
        else if ( stripos( $webp_url, site_url() ) === 0 )
        {
            if(!function_exists('get_home_path'))
                require_once(ABSPATH . 'wp-admin/includes/file.php');

            $webp_path= str_ireplace( site_url(), get_home_path(), $webp_url );
        }
        else
        {
            $webp_path='';
        }

        if(file_exists($webp_path))
        {
            $path_exist=true;
        }
        else
        {
            //$path_exist=false;
            return false;
        }

        $webp_url .= ! empty( $src['query'] ) ? $src['query'] : '';

        $ret['src']=$attributes[ $src_source ];
        $ret['src_attr']=$src_source;
        $ret['webp_exist']=$path_exist;
        if($path_exist)
        {
            $ret['webp_path']=$webp_path;
            $ret['webp_url']=$webp_url;
        }

        return $ret;
    }

    public function get_srcset($attributes)
    {
        $srcset_source = false;
        $upload=wp_upload_dir();
        $data_tag=array('data-lazy-srcset', 'data-srcset', 'srcset');

        $extensions = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png'          => 'image/png',
            //'gif'          => 'image/gif',
        );
        $extensions = array_keys( $extensions );
        $extensions = implode( '|', $extensions );

        foreach ( $data_tag as $srcset_attr )
        {
            if ( ! empty( $attributes[ $srcset_attr ] ) )
            {
                $srcset_source = $srcset_attr;
                break;
            }
        }

        $ret['srcset_attr']=$srcset_source;
        $ret['srcs']=array();
        if ( $srcset_source )
        {
            $srcset = explode( ',', $attributes[ $srcset_source ] );

            foreach ( $srcset as $srcs )
            {
                $srcs = preg_split( '/\s+/', trim( $srcs ) );

                if ( count( $srcs ) > 2 )
                {
                    $descriptor = array_pop( $srcs );
                    $srcs       = array(implode( ' ', $srcs ), $descriptor);
                }

                if ( empty( $srcs[1] ) )
                {
                    $srcs[1] = '1x';
                }

                if ( ! preg_match( '@^(?<src>(?:https?:)?//.+\.(?<extension>' . $extensions . '))(?<query>\?.*)?$@i', $srcs[0], $src ) )
                {
                    continue;
                }

                $webp_url  = $src['src'].'.webp';
                if ( stripos( $webp_url, $upload['baseurl'] ) === 0 )
                {
                    $webp_path = str_replace($upload['baseurl'],$upload['basedir'],$webp_url);
                }
                else if ( stripos( $webp_url, site_url() ) === 0 )
                {
                    if(!function_exists('get_home_path'))
                        require_once(ABSPATH . 'wp-admin/includes/file.php');

                    $webp_path= str_ireplace( site_url(), get_home_path(), $webp_url );
                }
                else
                {
                    $webp_path='';
                }

                if(file_exists($webp_path))
                {
                    $path_exist=true;
                }
                else
                {
                    //$path_exist=false;
                    continue;
                }

                $webp_url .= ! empty( $src['query'] ) ? $src['query'] : '';

                $tmp_srcset=array(
                    'url'         => $srcs[0],
                    'descriptor'  => $srcs[1],
                    'webp_exists'=>$path_exist
                );

                if($path_exist)
                {
                    $tmp_srcset['webp_path']=$webp_path;
                    $tmp_srcset['webp_url']=$webp_url;
                }

                $ret['srcs'][]=$tmp_srcset;
            }
        }
        else
        {
            return false;
        }

        if(empty($ret['srcs']))
            return false;

        return $ret;
    }

    public function get_style($attributes)
    {
        $style_attr='style';
        if ( empty( $attributes[ $style_attr ] ) )
        {
            // No style.
            return false;
        }

        return $attributes[ $style_attr ];
    }

    public function do_folders_webp_optimized($image_opt_meta,$path)
    {
        try {
            if($this->is_convert_webp())
            {
                if(!file_exists($path)||filesize($path)==0)
                {
                    $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                    return $image_opt_meta;
                }

                $webp_file =$path.'.webp';

                if(isset($image_opt_meta['webp_status'])&&$image_opt_meta['webp_status']=1)
                {
                    return $image_opt_meta;
                }
                else
                {
                    $options =$this->get_main_option('wpvivid_optimization_options');
                    $option['quality']=isset($options['quality'])?$options['quality']:'lossless';
                    if($option['quality']='lossless')
                    {
                        $quality=80;
                    }
                    else if($option['quality']='lossy')
                    {
                        $quality=80;
                    }
                    else if($option['quality']='super')
                    {
                        $quality=50;
                    }
                    else if($option['quality']=='custom')
                    {
                        $custom_quality=isset($options['custom_quality'])?$options['custom_quality']:80;
                        $custom_quality=min(99,$custom_quality);
                        $custom_quality=max(1,$custom_quality);
                        $quality=min($custom_quality,80);
                    }
                    else
                    {
                        $quality=80;
                    }

                    $type=$this->get_file_type($path);

                    if (file_exists($webp_file))
                    {
                        @unlink($webp_file);
                    }

                    if ($type == 'jpeg' ||$type == 'jpg')
                    {
                        $img = imagecreatefromjpeg($path);
                        if(!$img)
                        {
                            $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                        }
                        else
                        {
                            $result = imagewebp($img, $webp_file, $quality);
                            imagedestroy($img);
                            if (!$result) {
                                $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                            } else {
                                $image_opt_meta['webp_status']=1;
                                $this->WriteLog('Converting images ' . basename($path) . ' to webp success.', 'notice');
                            }
                        }
                    }
                    else if ($type== 'png')
                    {
                        $img = imagecreatefrompng($path);
                        if(!$img)
                        {
                            $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                        }
                        else
                        {
                            if(imagepalettetotruecolor($img))
                            {
                                if(imagealphablending($img, true))
                                {
                                    if(imagesavealpha($img, true))
                                    {
                                        $result = imagewebp($img, $webp_file, $quality);
                                        imagedestroy($img);

                                        if (!$result) {
                                            $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                                        } else {
                                            $image_opt_meta['webp_status']=1;
                                            $this->WriteLog('Converting images ' . basename($path) . ' to webp success.', 'notice');
                                        }
                                    }
                                    else
                                    {
                                        $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                                    }
                                }
                                else
                                {
                                    $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                                }
                            }
                            else
                            {
                                $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
                            }
                        }
                    }
                }


            }
        }
        catch (Exception $error)
        {
            $this->WriteLog('Converting images ' . basename($path) . ' to webp failed.', 'notice');
        }

        return $image_opt_meta;
    }

    public function get_file_type($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function do_webp_optimized($image_opt_meta,$image_id,$file,$size_key)
    {
        try {
            if($this->is_convert_webp())
            {
                if(!file_exists($file)||filesize($file)==0)
                {
                    $this->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
                    return $image_opt_meta;
                }
                $webp_file =$file.'.webp';
                $options =$this->get_main_option('wpvivid_optimization_options');
                $option['quality']=isset($options['quality'])?$options['quality']:'lossless';
                if($option['quality']='lossless')
                {
                    $quality=80;
                }
                else if($option['quality']='lossy')
                {
                    $quality=80;
                }
                else if($option['quality']='super')
                {
                    $quality=50;
                }
                else if($option['quality']=='custom')
                {
                    $custom_quality=isset($options['custom_quality'])?$options['custom_quality']:80;
                    $custom_quality=min(99,$custom_quality);
                    $custom_quality=max(1,$custom_quality);
                    $quality=min($custom_quality,80);
                }
                else
                {
                    $quality=80;
                }

                if(!isset($image_opt_meta['size'][$size_key]['webp_status'])||$image_opt_meta['size'][$size_key]['webp_status']==0||!file_exists($webp_file))
                {
                    if(file_exists($webp_file))
                    {
                        @unlink($webp_file);
                    }
                    if(get_post_mime_type($image_id)=='image/jpeg'||get_post_mime_type($image_id)=='image/jpg')
                    {
                        $img=  imagecreatefromjpeg($file);
                        if(!$img)
                        {
                            $this->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
                        }
                        else
                        {
                            $result=imagewebp($img,$webp_file,$quality);
                            imagedestroy($img);
                            if(!$result)
                            {
                                $this->WriteLog('convert webp images '.basename($file).' failed.','notice');
                            }
                            else
                            {
                                $this->WriteLog('Converting images ' . basename($file) . ' to webp success.','notice');
                                $image_opt_meta['size'][$size_key]['webp_status']=1;
                                $image_opt_meta['last_update_time']=time();
                            }
                        }

                    }
                    else if(get_post_mime_type($image_id)=='image/png')
                    {
                        $img = imagecreatefrompng($file);
                        if(!$img)
                        {
                            $this->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
                        }
                        else
                        {
                            if(imagepalettetotruecolor($img))
                            {
                                if(imagealphablending($img, true))
                                {
                                    if( imagesavealpha($img, true))
                                    {
                                        $result=imagewebp($img, $webp_file, $quality);
                                        imagedestroy($img);

                                        if(!$result)
                                        {
                                            $this->WriteLog('convert webp images '.basename($file).' failed.','notice');
                                        }
                                        else
                                        {
                                            $this->WriteLog('Converting images ' . basename($file) . ' to webp success.','notice');
                                            $image_opt_meta['size'][$size_key]['webp_status']=1;
                                            $image_opt_meta['last_update_time']=time();
                                        }
                                    }
                                    else
                                    {
                                        $this->WriteLog('convert webp images '.basename($file).' failed.','notice');
                                    }
                                }
                                else
                                {
                                    $this->WriteLog('convert webp images '.basename($file).' failed.','notice');
                                }
                            }
                            else
                            {
                                $this->WriteLog('convert webp images '.basename($file).' failed.','notice');
                            }
                        }
                    }
                    else if(get_post_mime_type($image_id)=='image/gif')
                    {
                        $img = imagecreatefromgif($file);
                        if(!$img)
                        {
                            $this->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
                        }
                        else
                        {
                            if(imagepalettetotruecolor($img))
                            {
                                $result=imagewebp($img, $webp_file, $quality);
                                imagedestroy($img);

                                if(!$result)
                                {
                                    $this->WriteLog('convert webp images '.basename($file).' failed.','notice');
                                }
                                else
                                {
                                    $this->WriteLog('Converting images ' . basename($file) . ' to webp success.','notice');
                                    $image_opt_meta['size'][$size_key]['webp_status']=1;
                                    $image_opt_meta['last_update_time']=time();
                                }
                            }
                            else
                            {
                                $this->WriteLog('convert webp images '.basename($file).' failed.','notice');
                            }
                        }
                    }
                }
            }
        }
        catch (Exception $error)
        {
            $this->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
        }

        return $image_opt_meta;
    }

    public function init_image_opt_meta($image_opt_meta,$image_id)
    {
        return $image_opt_meta;
    }

    public function generate_meta($image_opt_meta,$image_id,$key)
    {
        $image_opt_meta['size'][$key]['webp_status']=0;
        return $image_opt_meta;
    }

    public function opt_skip_file($skip,$filename,$image_opt_meta,$key)
    {
        return $skip;
    }

    public function is_image_optimized($optimized,$post_id)
    {
        if($optimized===false)
        {
            return $optimized;
        }

        if($this->is_convert_webp())
        {
            $meta=get_post_meta($post_id,'wpvivid_image_optimize_meta',true);
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
        }

        return $optimized;
    }

    public function is_convert_webp()
    {
        $options =$this->get_main_option('wpvivid_optimization_options');

        if(isset($options['webp']))
        {
            return $options['webp']['convert'];
        }
        else
        {
            return false;
        }
    }

    public function do_after_delete_image($image_id)
    {
        $this->delete_webp($image_id);
    }

    public function delete_webp($image_id)
    {
        $files=array();
        $file_path = get_attached_file( $image_id );
        $meta = wp_get_attachment_metadata( $image_id, true );

        if ( ! empty( $meta['sizes'] ) )
        {
            foreach ( $meta['sizes'] as $size_key => $size_data )
            {
                $filename= path_join( dirname( $file_path ), $size_data['file'] );
                $files[$size_key] =$filename;
            }

            if(!in_array($file_path,$files))
            {
                $files['og']=$file_path;
            }
        }
        else
        {
            $files['og']=$file_path;
        }

        foreach ($files as $size_key=>$file)
        {
            $webp_file =$file.'.webp';
            if(file_exists($webp_file))
                @unlink($webp_file);
        }
    }

    public function restore_image($image_id)
    {
        $this->delete_webp($image_id);
    }

    public function WriteLog($text,$type)
    {
        $log=new WPvivid_Image_Optimize_Log();
        $log->OpenLogFile();
        $log->WriteLog($text,$type);
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