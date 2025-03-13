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

if ( ! class_exists( 'WP_List_Table' ) )
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPvivid_Optimized_Image_MU_List_Ex extends WP_List_Table
{
    public $list;
    public $type;
    public $page_num;
    public $parent;

    public function __construct( $args = array() )
    {
        parent::__construct(
            array(
                'plural' => 'upload_files',
                'screen' => 'upload_files',
            )
        );
    }

    public function set_list($list,$page_num=1)
    {
        $this->list=$list;
        $this->page_num=$page_num;
    }

    protected function get_table_classes()
    {
        return array( 'widefat  media striped' );
    }

    public function print_column_headers( $with_id = true )
    {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

        if (!empty($columns['cb']))
        {
            static $cb_counter = 1;
            $columns['cb'] = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' . __('Select All') . '</label>'
                . '<input id="cb-select-all-' . $cb_counter . '" type="checkbox"/>';
            $cb_counter++;
        }

        foreach ( $columns as $column_key => $column_display_name )
        {

            $class = array( 'manage-column', "column-$column_key" );

            if ( in_array( $column_key, $hidden ) )
            {
                $class[] = 'hidden';
            }


            if ( $column_key === $primary )
            {
                $class[] = 'column-primary';
            }

            if ( $column_key === 'cb' )
            {
                $class[] = 'check-column';
            }
            $tag='th';
            $tag   = ( 'cb' === $column_key ) ? 'td' : 'th';
            $scope = ( 'th' === $tag ) ? 'scope="col"' : '';
            $id    = $with_id ? "id='$column_key'" : '';

            if ( ! empty( $class ) )
            {
                $class = "class='" . join( ' ', $class ) . "'";
            }

            echo "<$tag $scope $id $class>$column_display_name</$tag>";
        }
    }

    public function get_columns()
    {
        $sites_columns = array(
            'cb'          => __( ' ' ),
            'site'=>__( 'Site' ),
            'title'    =>__( 'Images (Media Library)' ),
            'webp'=>__( 'Webp' ),
            'status'=>__('Status'),
            'optimization' =>__('Result (including thumbnails)')
        );

        /*
            'og_size'    => __( 'Original image size' ),
            'opt_size' => __( 'Optimized image size' ),
            'saved'=>'Saved'
         */
        return $sites_columns;
    }

    public function get_pagenum()
    {
        if($this->page_num=='first')
        {
            $this->page_num=1;
        }
        else if($this->page_num=='last')
        {
            $this->page_num=$this->_pagination_args['total_pages'];
        }
        $pagenum = $this->page_num ? $this->page_num : 0;

        if ( isset( $this->_pagination_args['total_pages'] ) && $pagenum > $this->_pagination_args['total_pages'] )
        {
            $pagenum = $this->_pagination_args['total_pages'];
        }

        return max( 1, $pagenum );
    }

    public function column_cb( $item )
    {
        $html='<input type="checkbox" name="opt" data-id="'.$item['image_id'].'" data-site="'.$item['site_id'].'" />';
        echo $html;
    }

    public function column_site( $item )
    {
        $current_blog_details = get_blog_details( array( 'blog_id' => $item['site_id'] ) );
        echo $current_blog_details->blogname;
    }
    public function column_title($item)
    {
        switch_to_blog( $item['site_id']  );
        $thumb      = wp_get_attachment_image( $item['image_id'], array( 60, 60 ), true, array( 'alt' => '' ) );
        $title      = _draft_or_post_title($item['image_id']);

        $post=get_post($item['image_id']);

        list( $mime ) = explode( '/', $post->post_mime_type );

        $link_start = $link_end = '';

        if ( current_user_can( 'edit_post', $post->ID ) )
        {
            $link_start = sprintf(
                '<a href="%s" aria-label="%s">',
                get_edit_post_link( $post->ID ),
                /* translators: %s: attachment title */
                esc_attr( sprintf( __( '&#8220;%s&#8221; (Edit)' ), $title ) )
            );
            $link_end = '</a>';
        }

        $class = $thumb ? ' class="has-media-icon"' : '';
        ?>
        <strong<?php echo $class; ?>>
            <?php
            echo $link_start;
            if ( $thumb ) :
                ?>
                <span class="media-icon <?php echo sanitize_html_class( $mime . '-icon' ); ?>"><?php echo $thumb; ?></span>
            <?php
            endif;
            echo $title . $link_end;
            _media_states( $post );
            ?>
        </strong>
        <p class="filename">
            <span class="screen-reader-text"><?php _e( 'File name:' ); ?> </span>
            <?php
            $file = get_attached_file( $post->ID );
            echo esc_html( wp_basename( $file ) );
            ?>
        </p>
        <?php
        $this->restore_current_blog();
    }

    public function column_webp($item)
    {
        switch_to_blog( $item['site_id']  );
        $meta=get_post_meta( $item['image_id'],'wpvivid_image_optimize_meta', true );
        $html='<div class="wpvivid-media-item" data-id="'.$item['image_id'].'">';
        $status=true;

        foreach ($item['size'] as $size_key=>$size)
        {

            if(isset($size['webp_status'])&&$size['webp_status']==1)
            {
                $status=true;
            }
            else
            {
                $status=false;
            }
        }

        if($status)
        {
            $status=__('Converted');
        }
        else
        {
            $status=__('Unconverted');
        }

        echo $status;
        $this->restore_current_blog();
    }

    public function column_status($item)
    {
        $status=true;

        $options=get_option('wpvivid_optimization_options',array());

        foreach ($item['size'] as $size_key=>$size)
        {
            if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
            {
                if($options['skip_size'][$size_key])
                    continue;
            }

            if($size['opt_status']==1)
            {
                $status=true;
            }
            else
            {
                $status=false;
            }
        }

        if($status)
        {
            $status=__('Optimized');
        }
        else
        {
            $status=__('Un-optimized');
        }

        echo $status;
    }

    public function column_optimization($item)
    {
        switch_to_blog( $item['site_id']  );

        $allowed_mime_types = array(
            'image/jpg',
            'image/jpeg',
            'image/png');

        $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

        if ( ! wp_attachment_is_image( $item['image_id'] ) || ! in_array( get_post_mime_type( $item['image_id'] ),$allowed_mime_types ) )
        {
            return 'Not support';
        }

        $meta=get_post_meta( $item['image_id'],'wpvivid_image_optimize_meta', true );

        $html='<div class="wpvivid-media-item" data-site="'.$item['site_id'].'" data-id="'.$item['image_id'].'">';
        $task=new WPvivid_ImgOptim_Task_Ex();
        $options=get_option('wpvivid_optimization_options',array());

        $status=true;
        $converted=0;
        $unconverted=0;
        foreach ($item['size'] as $size_key=>$size)
        {
            if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
            {
                if($options['skip_size'][$size_key])
                    continue;
            }
            if($size['opt_status']==1)
            {
                $status=true;
            }
            else
            {
                $status=false;
            }

            if(isset($size['webp_status'])&&$size['webp_status']==1)
            {
                $converted++;
            }
            else
            {
                $unconverted++;
            }
        }

        if($status)
        {
            if($meta['sum']['og_size']==0)
            {
                $percent=0;
            }
            else
            {
                $percent=round(100-($meta['sum']['opt_size']/$meta['sum']['og_size'])*100,2);
            }
            $html.='<ul>';
            $html.= '<li><span>'.__('Optimized size','wpvivid-imgoptim').' : </span><strong>'.size_format($meta['sum']['opt_size'],2).'</strong></li>';
            $html.= '<li><span>'.__('Saved','wpvivid-imgoptim').' : </span><strong>'.$percent.'%</strong></li>';
            $html.= '<li><span>'.__('Original size','wpvivid-imgoptim').' : </span><strong>'.size_format($meta['sum']['og_size'],2).'</strong></li>';
            $html.= '<li><span>'.__('WebP','wpvivid-imgoptim').' : </span><strong>'.$converted.' converted</strong></li>';
            $html.='</ul>';
        }

        $html.='</div>';
        $this->restore_current_blog();
        return $html;
    }

    public function has_items()
    {
        return !empty($this->list);
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $total_items =sizeof($this->list);

        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page'    => 20,
            )
        );
    }

    public function display_rows()
    {
        $this->_display_rows( $this->list );
    }

    private function _display_rows( $list )
    {
        $page=$this->get_pagenum();

        $page_list=$list;
        $temp_page_list=array();

        $count=0;
        while ( $count<$page )
        {
            $temp_page_list = array_splice( $page_list, 0, 20);
            $count++;
        }

        foreach ( $temp_page_list as $key=>$item)
        {
            $this->single_row($item);
        }
    }

    public function single_row($item)
    {
        ?>
        <tr>
            <?php $this->single_row_columns( $item ); ?>
        </tr>
        <?php
    }

    protected function pagination( $which )
    {
        if ( empty( $this->_pagination_args ) )
        {
            return;
        }

        $total_items     = $this->_pagination_args['total_items'];
        $total_pages     = $this->_pagination_args['total_pages'];
        $infinite_scroll = false;
        if ( isset( $this->_pagination_args['infinite_scroll'] ) )
        {
            $infinite_scroll = $this->_pagination_args['infinite_scroll'];
        }

        if ( 'top' === $which && $total_pages > 1 )
        {
            $this->screen->render_screen_reader_content( 'heading_pagination' );
        }

        $output = '<span class="displaying-num">' . sprintf( _n( '%s item', '%s items', $total_items ), number_format_i18n( $total_items ) ) . '</span>';

        $current              = $this->get_pagenum();

        $page_links = array();

        $total_pages_before = '<span class="paging-input">';
        $total_pages_after  = '</span></span>';

        $disable_first = $disable_last = $disable_prev = $disable_next = false;

        if ( $current == 1 ) {
            $disable_first = true;
            $disable_prev  = true;
        }
        if ( $current == 2 ) {
            $disable_first = true;
        }
        if ( $current == $total_pages ) {
            $disable_last = true;
            $disable_next = true;
        }
        if ( $current == $total_pages - 1 ) {
            $disable_last = true;
        }

        if ( $disable_first ) {
            $page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
        } else {
            $page_links[] = sprintf(
                "<div class='first-page button'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></div>",
                __( 'First page' ),
                '&laquo;'
            );
        }

        if ( $disable_prev ) {
            $page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
        } else {
            $page_links[] = sprintf(
                "<div class='prev-page button' value='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></div>",
                $current,
                __( 'Previous page' ),
                '&lsaquo;'
            );
        }

        if ( 'bottom' === $which ) {
            $html_current_page  = $current;
            $total_pages_before = '<span class="screen-reader-text">' . __( 'Current Page' ) . '</span><span id="table-paging" class="paging-input"><span class="tablenav-paging-text">';
        } else {
            $html_current_page = sprintf(
                "%s<input class='current-page'  type='text' name='paged' value='%s' size='%d' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
                '<label  class="screen-reader-text">' . __( 'Current Page' ) . '</label>',
                $current,
                strlen( $total_pages )
            );
        }
        $html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
        $page_links[]     = $total_pages_before . sprintf( _x( '%1$s of %2$s', 'paging' ), $html_current_page, $html_total_pages ) . $total_pages_after;

        if ( $disable_next ) {
            $page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
        } else {
            $page_links[] = sprintf(
                "<div class='next-page button' value='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></div>",
                $current,
                __( 'Next page' ),
                '&rsaquo;'
            );
        }

        if ( $disable_last ) {
            $page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
        } else {
            $page_links[] = sprintf(
                "<div class='last-page button'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></div>",
                __( 'Last page' ),
                '&raquo;'
            );
        }

        $pagination_links_class = 'pagination-links';
        if ( ! empty( $infinite_scroll ) ) {
            $pagination_links_class .= ' hide-if-js';
        }
        $output .= "\n<span class='$pagination_links_class'>" . join( "\n", $page_links ) . '</span>';

        if ( $total_pages ) {
            $page_class = $total_pages < 2 ? ' one-page' : '';
        } else {
            $page_class = ' no-pages';
        }
        $this->_pagination = "<div class='tablenav-pages{$page_class}'>$output</div>";

        echo $this->_pagination;
    }

    protected function display_tablenav( $which ) {
        $css_type = '';
        if ( 'top' === $which ) {
            wp_nonce_field( 'bulk-' . $this->_args['plural'] );
            $css_type = 'margin: 0 0 10px 0';
            $id='wpvivid_image_opt_bulk_top_action';
            $class='top-action';
        }
        else if( 'bottom' === $which )
        {
            $css_type = 'margin: 10px 0 0 0';
            $id='wpvivid_image_opt_bulk_bottom_action';
            $class='bottom-action';
        }
        else
        {
            $id='';
            $class='';
        }

        $total_pages     = $this->_pagination_args['total_pages'];
        if ( $total_pages >1)
        {
            ?>
            <div class="tablenav <?php echo esc_attr( $which ); ?>" style="<?php esc_attr_e($css_type); ?>">
                <div class="alignleft actions bulkactions">
                    <label for="wpvivid_uc_bulk_action" class="screen-reader-text"><?php _e('Select bulk action','wpvivid-imgoptim')?></label>
                    <select name="action" id="<?php echo esc_attr( $id ); ?>">
                        <option value="-1"><?php _e('Bulk Actions','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_restore_selected_image"><?php _e('Restore selected images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_restore_all_image"><?php _e('Restore all images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_delete_selected_webp"><?php _e('Delete selected WebP images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_delete_all_webp"><?php _e('Delete all WebP images','wpvivid-imgoptim')?></option>
                    </select>
                    <input type="submit" class="button action <?php echo esc_attr( $class ); ?>" value="<?php _e('Apply','wpvivid-imgoptim')?>">
                </div>
                <?php
                $this->extra_tablenav( $which );
                $this->pagination( $which );
                ?>
                <br class="clear" />
            </div>
            <?php
        }
        else
        {
            ?>
            <div class="tablenav <?php echo esc_attr( $which ); ?>" style="<?php esc_attr_e($css_type); ?>">
                <div class="alignleft actions bulkactions">
                    <label for="wpvivid_uc_bulk_action" class="screen-reader-text"><?php _e('Select bulk action','wpvivid-imgoptim')?></label>
                    <select name="action" id="<?php echo esc_attr( $id ); ?>">
                        <option value="-1"><?php _e('Bulk Actions','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_restore_selected_image"><?php _e('Restore selected images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_restore_all_image"><?php _e('Restore all images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_delete_selected_webp"><?php _e('Delete selected WebP images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_mu_delete_all_webp"><?php _e('Delete all WebP images','wpvivid-imgoptim')?></option>
                    </select>
                    <input type="submit" class="button action <?php echo esc_attr( $class ); ?>" value="<?php _e('Apply','wpvivid-imgoptim')?>">
                </div>

                <br class="clear" />
            </div>
            <?php
        }
    }

    public function restore_current_blog()
    {
        if(is_main_site())
        {
            restore_current_blog();
        }
        else
        {
            $site_id=get_main_site_id();
            switch_to_blog($site_id);
        }
    }
}

class WPvivid_MU_ImgOptim
{
    public $imgoptim_task;
    public function __construct($task)
    {
        $this->imgoptim_task=$task;
    }

    public function get_mu_need_optimize_images()
    {
        $images = array();
        $blogs_ids=get_sites();
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

            $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );

            $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);

            foreach ( $query_images->posts as $image )
            {
                $type=get_post_mime_type($image->ID);

                if (in_array( $type, $mime_types, true ))
                {
                    $data['site_id']=$site_id;
                    $data['image_id']=$image->ID;
                    $images[] = $data ;
                }
            }
            $this->restore_current_blog();
        }

        return $images;
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
                            $this->restore_current_blog();
                            return false;
                        }
                    }
                    else
                    {
                        $this->restore_current_blog();
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
                        $this->restore_current_blog();
                        return false;
                    }
                }
            }

            $optimized= apply_filters('wpvivid_is_image_optimized',true,$post_id);
            $this->restore_current_blog();
            return $optimized;
        }
        else
        {
            $this->restore_current_blog();
            return false;
        }
    }

    public function do_mu_unoptimized_image()
    {
        $image_id=false;
        $site_id=false;
        $id=false;
        foreach ($this->imgoptim_task->task['images'] as $image)
        {
            if($image['finished']==0)
            {
                $id=$image['id'];
                $image_id=$image['image_id'];
                $site_id=$image['site_id'];
                break;
            }
        }

        if($image_id===false)
        {
            $ret['result']='success';
            $ret['unoptimized_image']=false;
            return $ret;
        }

        $this->imgoptim_task->task['status']='running';
        $this->imgoptim_task->task['last_update_time']=time();
        update_option('wpvivid_image_opt_task',$this->imgoptim_task->task,'no');

        $this->imgoptim_task->WriteLog('Start optimizing site id:'.$site_id.' image id:'.$image_id,'notice');

        $ret=$this->optimize_image($site_id,$image_id);

        if($ret['result']=='success')
        {
            $this->imgoptim_task->WriteLog('Optimizing site id:'.$site_id.' image id:'.$image_id.' succeeded.','notice');

            $this->imgoptim_task->task['images'][$id]['finished']=1;
            $this->imgoptim_task->task['status']='completed';
            $this->imgoptim_task->task['last_update_time']=time();
            $this->imgoptim_task->task['retry']=0;
            update_option('wpvivid_image_opt_task',$this->imgoptim_task->task,'no');
            $ret['result']='success';
            $ret['unoptimized_image']=true;
            return $ret;
        }
        else
        {
            $this->imgoptim_task->WriteLog('Optimizing image failed. Error:'.$ret['error'],'error');
            $this->imgoptim_task->task['status']='error';
            $this->imgoptim_task->task['error']=$ret['error'];
            $this->imgoptim_task->task['last_update_time']=time();
            update_option('wpvivid_image_opt_task',$this->imgoptim_task->task,'no');
            $ret['unoptimized_image']=true;
            return $ret;
        }
    }

    public function optimize_image($site_id,$image_id)
    {
        $files=array();
        switch_to_blog( $site_id );
        $file_path = get_attached_file( $image_id );
        $meta = wp_get_attachment_metadata( $image_id, true );
        $image_opt_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
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
        $this->restore_current_blog();

        if(empty($image_opt_meta))
        {
            $image_opt_meta=$this->imgoptim_task->init_image_opt_meta($image_id);
        }

        if(empty($meta['sizes']))
        {
            if(apply_filters('wpvivid_imgoptim_og_skip_file_ex',false,$file_path))
            {
                $this->imgoptim_task->WriteLog('Skip file '.$file_path,'notice');
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
                    $this->imgoptim_task->WriteLog('Skip file '.$filename,'notice');
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
                    $this->imgoptim_task->WriteLog('Skip file '.$file_path,'notice');
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
        $this->update_site_post_meta($site_id,$image_id,'wpvivid_image_optimize_meta',$image_opt_meta);

        if($image_opt_meta['sum']['options']['backup'])
        {
            $this->imgoptim_task->WriteLog('Start backing up image(s).','notice');
            $this->backup($files,$site_id,$image_id);
        }

        if($this->is_resize($site_id,$image_id))
        {
            $this->imgoptim_task->WriteLog('Start resizing site id:'.$site_id.' image id:'.$image_id,'notice');
            $this->resize($site_id,$image_id);
        }
        $only_resize=isset($this->imgoptim_task->task['options']['only_resize'])?$this->imgoptim_task->task['options']['only_resize']:false;

        if($only_resize)
        {
            $ret['result']='success';
            return $ret;
        }

        $retry=0;

        if(empty($files))
        {
            $ret['result']='success';
            return $ret;
        }
        $ret['result']='success';

        $webp_convert=isset($this->imgoptim_task->task['options']['webp']['convert'])?$this->imgoptim_task->task['options']['webp']['convert']:false;

        foreach ($files as $size_key=>$file)
        {
            if(!file_exists($file))
            {
                $this->imgoptim_task->WriteLog('Skip not exist file: '.$file,'notice');
                $files_not_exist = get_option('wpvivid_imgoptm_file_not_exist', array());
                if(!in_array($file, $files_not_exist))
                {
                    $files_not_exist[] = $file;
                    update_option('wpvivid_imgoptm_file_not_exist', $files_not_exist,'no');
                    update_option('wpvivid_hide_imgoptm_not_exist_notice', '0','no');
                }
                continue;
            }

            if(filesize($file) == 0)
            {
                $this->imgoptim_task->WriteLog('Skip file: '.$file.', file size: 0, image id: '.$image_id,'notice');
                continue;
            }

            if(apply_filters('wpvivid_imgoptim_opt_skip_file_ex',false,$file,$image_opt_meta,$size_key))
            {
                $this->imgoptim_task->WriteLog('Skip optimized size '.$size_key,'notice');
            }

            if(!isset($image_opt_meta['size'][$size_key]['webp_status']))
            {
                $image_opt_meta['size'][$size_key]['webp_status']=0;
            }

            if($type=='gif')
            {
                $gif_webp_convert=isset($this->imgoptim_task->task['options']['webp']['gif_convert'])?$this->imgoptim_task->task['options']['webp']['gif_convert']:false;
            }
            else
            {
                $gif_webp_convert=false;
            }

            $ret['result']='success';
            if(!empty($type))
            {
                if($image_opt_meta['size'][$size_key]['opt_status']==0)
                {
                    while ($retry<3)
                    {
                        $this->imgoptim_task->WriteLog('Start compressing image '.basename($file).', file size: '.filesize($file),'notice');
                        $ret=$this->imgoptim_task->compress_image($type,$file,$file,$this->imgoptim_task->task['options']);
                        if($ret['result']=='failed')
                        {
                            $this->imgoptim_task->WriteLog('Compressing image '.basename($file).' failed. Error:'.$ret['error'],'notice');
                            if(isset($ret['remain'])&&$ret['remain']==false)
                            {
                                return $ret;
                            }

                            $retry++;
                            $this->imgoptim_task->WriteLog('Start retrying optimization. Count:'.$retry,'notice');
                        }
                        else
                        {
                            $this->imgoptim_task->WriteLog('Compressing image '.basename($file).' succeeded.','notice');
                            $retry=0;
                            clearstatcache();
                            $image_opt_meta['size'][$size_key]['opt_size']=filesize($file);
                            $image_opt_meta['sum']['opt_size']+=$image_opt_meta['size'][$size_key]['opt_size'];
                            $image_opt_meta['size'][$size_key]['opt_status']=1;
                            $image_opt_meta['last_update_time']=time();

                            if($type=='gif'&&$gif_webp_convert)
                            {
                                if(isset($ret['webp_result'])&&$ret['webp_result']=='success')
                                {
                                    $this->imgoptim_task->WriteLog('Converting image '.basename($file).' succeeded.','notice');
                                    $image_opt_meta['size'][$size_key]['webp_status']=1;
                                }
                                else
                                {
                                    $this->imgoptim_task->WriteLog('Converting image '.basename($file).' failed.','notice');
                                }
                            }
                            else if($type=='gif'&&!$gif_webp_convert)
                            {

                            }
                            else if($webp_convert)
                            {
                                if(isset($ret['webp_result'])&&$ret['webp_result']=='success')
                                {
                                    $this->imgoptim_task->WriteLog('Converting image '.basename($file).' succeeded.','notice');
                                    $image_opt_meta['size'][$size_key]['webp_status']=1;
                                }
                                else
                                {
                                    $this->imgoptim_task->WriteLog('Converting image '.basename($file).' failed.','notice');
                                }
                            }

                            $this->update_site_post_meta($site_id,$image_id,'wpvivid_image_optimize_meta',$image_opt_meta);
                            break;
                        }
                    }

                    if($ret['result']=='failed')
                    {
                        $this->imgoptim_task->WriteLog('Compressing image '.basename($file).' failed. Error:'.$ret['error'],'error');
                        return $ret;
                    }
                }
                else if($type=='gif'&&$gif_webp_convert&&$image_opt_meta['size'][$size_key]['webp_status']==0)
                {
                    while ($retry<3)
                    {
                        $this->imgoptim_task->WriteLog('Start Converting image '.basename($file),'notice');
                        $webp_file =$file.'.webp';
                        $ret=$this->imgoptim_task->convert_webp($type,$file,$webp_file,$this->imgoptim_task->task['options']);
                        if($ret['result']=='failed')
                        {
                            $this->imgoptim_task->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
                            if(isset($ret['remain'])&&$ret['remain']==false)
                            {
                                return $ret;
                            }

                            $retry++;
                            $this->imgoptim_task->WriteLog('Start retrying converting. Count:'.$retry,'notice');
                        }
                        else
                        {
                            $this->imgoptim_task->WriteLog('Converting image '.basename($file).' succeeded.','notice');
                            $retry=0;
                            clearstatcache();

                            $image_opt_meta['size'][$size_key]['webp_status']=1;
                            $this->update_site_post_meta($site_id,$image_id,'wpvivid_image_optimize_meta',$image_opt_meta);
                            break;
                        }
                    }

                    if($ret['result']=='failed')
                    {
                        $this->imgoptim_task->WriteLog('Converting image '.basename($file).' failed. Error:'.$ret['error'],'error');
                        return $ret;
                    }
                }
                else if($webp_convert&&$image_opt_meta['size'][$size_key]['webp_status']==0)
                {
                    while ($retry<3)
                    {
                        $this->imgoptim_task->WriteLog('Start Converting image '.basename($file),'notice');
                        $webp_file =$file.'.webp';
                        $ret=$this->imgoptim_task->convert_webp($type,$file,$webp_file,$this->imgoptim_task->task['options']);
                        if($ret['result']=='failed')
                        {
                            $this->imgoptim_task->WriteLog('Converting images ' . basename($file) . ' to webp failed.', 'notice');
                            if(isset($ret['remain'])&&$ret['remain']==false)
                            {
                                return $ret;
                            }

                            $retry++;
                            $this->imgoptim_task->WriteLog('Start retrying converting. Count:'.$retry,'notice');
                        }
                        else
                        {
                            $this->imgoptim_task->WriteLog('Converting image '.basename($file).' succeeded.','notice');
                            $retry=0;
                            clearstatcache();

                            $image_opt_meta['size'][$size_key]['webp_status']=1;
                            $this->update_site_post_meta($site_id,$image_id,'wpvivid_image_optimize_meta',$image_opt_meta);
                            break;
                        }
                    }

                    if($ret['result']=='failed')
                    {
                        $this->imgoptim_task->WriteLog('Converting image '.basename($file).' failed. Error:'.$ret['error'],'error');
                        return $ret;
                    }
                }
            }
        }
        $image_opt_meta['last_update_time']=time();
        $this->update_site_post_meta($site_id,$image_id,'wpvivid_image_optimize_meta',$image_opt_meta);
        if($ret['result']=='success')
        {
            do_action('wpvivid_do_after_optimized',$image_id);
        }
        else
        {
            $this->imgoptim_task->WriteLog('Optimize images '.$image_id.' failed.Error:'.$ret['error'],'notice');
        }
        $ret['result']='success';
        return $ret;
    }

    public function update_site_post_meta($site_id,$post_id,$meta_key,$meta_value)
    {
        switch_to_blog( $site_id );
        update_post_meta($post_id,$meta_key,$meta_value);
        $this->restore_current_blog();
    }

    public function backup($files,$site_id,$image_id)
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
                $backup_dir=$this->get_backup_folder($file,$site_id);

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
            $this->update_site_post_meta($site_id,$image_id,'wpvivid_backup_image_meta',$backup_meta);
        }
    }

    public function get_backup_folder( $attachment_path ,$site_id)
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

        switch_to_blog( $site_id );
        $upload_dir = wp_get_upload_dir();
        $this->restore_current_blog();

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

    public function is_resize($site_id,$id)
    {
        $this->imgoptim_task->task=get_option('wpvivid_image_opt_task',array());

        $resize=isset($this->imgoptim_task->task['options']['resize'])?$this->imgoptim_task->task['options']['resize']:false;

        if($resize!==false&&$resize['enable'])
        {
            switch_to_blog( $site_id );
            $meta =wp_get_attachment_metadata( $id );
            $this->restore_current_blog();

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

    public function resize($site_id,$image_id)
    {
        switch_to_blog( $site_id );
        $file_path = get_attached_file( $image_id );
        $resize=isset($this->imgoptim_task->task['options']['resize'])?$this->imgoptim_task->task['options']['resize']:false;

        if($resize===false)
        {
            $this->restore_current_blog();
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
            $this->restore_current_blog();
            return false;
        }

        $resize_path = path_join( dirname( $file_path ),$data['file']);
        if (!file_exists($resize_path))
        {
            $this->restore_current_blog();
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
        $this->restore_current_blog();
        return true;
    }

    public function restore_image($site_id,$image_id)
    {
        switch_to_blog( $site_id );
        $backup_image_meta = get_post_meta( $image_id, 'wpvivid_backup_image_meta', true );

        if(empty($backup_image_meta))
        {
            delete_post_meta( $image_id, 'wpvivid_image_optimize_meta');
            restore_current_blog();
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
                        wp_update_attachment_metadata( $image_id, $meta );
                    }
                }
            }

            do_action('wpvivid_restore_image',$image_id);
            delete_post_meta( $image_id, 'wpvivid_image_optimize_meta');
            delete_post_meta( $image_id, 'wpvivid_backup_image_meta');
            delete_post_meta( $image_id, 'wpvivid_image_og_pixel_meta');
            restore_current_blog();
            return true;
        }
        else
        {
            delete_post_meta( $image_id, 'wpvivid_image_optimize_meta');
            restore_current_blog();
            return true;
        }
    }

    public function restore_current_blog()
    {
        if(is_main_site())
        {
            restore_current_blog();
        }
        else
        {
            $site_id=get_main_site_id();
            switch_to_blog($site_id);
        }
    }
}