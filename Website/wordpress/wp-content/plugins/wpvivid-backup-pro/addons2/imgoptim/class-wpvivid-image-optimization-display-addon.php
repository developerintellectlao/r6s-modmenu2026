<?php

/**
 * WPvivid addon: yes
 * Addon Name: wpvivid-imgoptim-pro
 * Description: Pro
 * Version: 2.2.27
 * Need_init: yes
 * Admin_load: yes
 * Interface Name: WPvivid_ImgOptim_Display_Addon
 */

define('WPVIVID_IMGOPTIM_PRO_VERSION','2.2.27');

if (!defined('WPVIVID_BACKUP_PRO_PLUGIN_DIR'))
{
    die;
}

if ( ! class_exists( 'WP_List_Table' ) )
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPvivid_Optimized_Image_List_Ex extends WP_List_Table
{
    public $list;
    public $type;
    public $page_num;
    public $parent;
    public $image_count;

    public function __construct( $args = array() )
    {
        parent::__construct(
            array(
                'plural' => 'upload_files',
                'screen' => 'upload_files',
            )
        );
    }

    public function set_list($list,$page_num=1,$count=0)
    {
        $this->list=$list;
        $this->page_num=$page_num;
        $this->image_count=$count;
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
        $html='<input type="checkbox" name="opt" value="'.$item['id'].'" />';
        echo $html;
    }

    public function column_title($item)
    {
        $thumb      = wp_get_attachment_image( $item['id'], array( 60, 60 ), true, array( 'alt' => '' ) );
        $title      = _draft_or_post_title($item['id']);

        $post=get_post($item['id']);

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
    }

    public function column_webp($item)
    {
        $meta=get_post_meta( $item['id'],'wpvivid_image_optimize_meta', true );
        $html='<div class="wpvivid-media-item" data-id="'.$item['id'].'">';
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
        $allowed_mime_types = array(
            'image/jpg',
            'image/jpeg',
            'image/png');

        $allowed_mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$allowed_mime_types);

        if ( ! wp_attachment_is_image( $item['id'] ) || ! in_array( get_post_mime_type( $item['id'] ),$allowed_mime_types ) )
        {
            return 'Not support';
        }

        $meta=get_post_meta( $item['id'],'wpvivid_image_optimize_meta', true );

        $html='<div class="wpvivid-media-item" data-id="'.$item['id'].'">';
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

        if($this->image_count!=0)
            $total_items=$this->image_count;
        else
            $total_items =sizeof($this->list);

        $args=array(
            'total_items' => $total_items,
            'per_page'    => 20,
        );

        $args = wp_parse_args(
            $args,
            array(
                'total_items' => 0,
                'total_pages' => 0,
                'per_page'    => 0,
            )
        );

        if ( ! $args['total_pages'] && $args['per_page'] > 0 ) {
            $args['total_pages'] = ceil( $args['total_items'] / $args['per_page'] );
        }
        $this->_pagination_args = $args;
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

        /*
        $count=0;
        while ( $count<$page )
        {
            $temp_page_list = array_splice( $page_list, 0, 20);
            $count++;
        }*/

        $temp_page_list = array_splice( $page_list, 0, 20);

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
                        <option value="wpvivid_restore_selected_image"><?php _e('Restore selected images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_restore_all_image"><?php _e('Restore all images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_delete_selected_webp"><?php _e('Delete selected WebP images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_delete_all_webp"><?php _e('Delete all WebP images','wpvivid-imgoptim')?></option>
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
                        <option value="wpvivid_restore_selected_image"><?php _e('Restore selected images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_restore_all_image"><?php _e('Restore all images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_delete_selected_webp"><?php _e('Delete selected WebP images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_delete_all_webp"><?php _e('Delete all WebP images','wpvivid-imgoptim')?></option>
                    </select>
                    <input type="submit" class="button action <?php echo esc_attr( $class ); ?>" value="<?php _e('Apply','wpvivid-imgoptim')?>">
                </div>

                <br class="clear" />
            </div>
            <?php
        }
    }
}

class WPvivid_ImgOptim_Display_Addon
{
    public $main_tab;
    public $screen_ids;
    public $submenus;
    public $display;
    public $custom_folders_display;

    public $end_shutdown_function;

    public function __construct()
    {
        add_filter('wpvivid_get_dashboard_menu', array($this, 'get_dashboard_menu'), 10, 2);
        add_filter('wpvivid_get_dashboard_screens', array($this, 'get_dashboard_screens'), 10);
        add_filter('wpvivid_get_toolbar_menus', array($this, 'get_toolbar_menus'),11);
        add_action('wp_ajax_wpvivid_get_server_status_ex',array($this, 'get_server_status'));
        add_action('wp_ajax_wpvivid_start_get_overview_ex', array($this, 'start_get_overview'));
        add_action('wp_ajax_wpvivid_get_overview_ex',array($this, 'get_overview'));

        add_action('wp_ajax_wpvivid_hide_imgoptm_not_exist_notice', array($this, 'hide_imgoptm_not_exist_notice'));

        add_action('wp_ajax_wpvivid_get_opt_list_ex',array($this, 'get_opt_list'));

        add_action('wp_ajax_wpvivid_delete_selected_webp_image',array($this, 'delete_selected_webp_image'));
        add_action('wp_ajax_wpvivid_delete_all_webp_image',array($this, 'delete_all_webp_image'));
        //
        //
        add_action('wp_ajax_wpvivid_init_opt_task_ex', array($this, 'init_opt_task'));
        add_action('wp_ajax_wpvivid_start_opt_task_ex',array($this, 'start_opt_task'));
        add_action('wp_ajax_wpvivid_cancel_opt_task_ex',array($this,'cancel_opt_task'));
        add_action('wp_ajax_wpvivid_opt_image_ex',array($this, 'opt_image'));
        add_action('wp_ajax_wpvivid_get_opt_progress_ex',array($this, 'get_opt_progress'));
        add_action('wp_ajax_wpvivid_get_need_increase_memory_retry', array($this, 'get_need_increase_memory_retry'));

        add_action('wp_ajax_wpvivid_restore_selected_opt_image_ex',array($this, 'restore_image'));
        add_action('wp_ajax_wpvivid_restore_all_opt_image_ex',array($this, 'restore_all_image'));

        add_action('wp_ajax_wpvivid_get_opt_folder_list_ex',array($this, 'folders_get_opt_list'));
        add_action('wp_ajax_wpvivid_delete_folders_selected_webp_image',array($this, 'folders_delete_selected_webp_image'));
        add_action('wp_ajax_wpvivid_delete_folders_all_webp_image',array($this, 'folders_delete_all_webp_image'));
        add_action('wp_ajax_wpvivid_restore_selected_opt_folders_image_ex',array($this, 'folders_restore_image'));
        add_action('wp_ajax_wpvivid_restore_all_opt_folders_image_ex',array($this, 'folders_restore_all_image'));

        add_action('wp_ajax_wpvivid_mu_restore_selected_opt_image_ex',array($this, 'restore_mu_image'));
        add_action('wp_ajax_wpvivid_mu_restore_all_opt_image_ex',array($this, 'restore_mu_all_image'));
        add_action('wp_ajax_wpvivid_mu_delete_selected_webp_image',array($this, 'delete_mu_selected_webp_image'));
        add_action('wp_ajax_wpvivid_mu_delete_all_webp_image',array($this, 'delete_mu_all_webp_image'));
    }



    public function get_dashboard_screens($screens)
    {
        $screen['menu_slug']='wpvivid-imgoptim';
        $screen['screen_id']='wpvivid-plugin_page_wpvivid-imgoptim';
        $screen['is_top']=false;
        $screens[]=$screen;
        return $screens;
    }

    public function get_dashboard_menu($submenus,$parent_slug)
    {
        $display = apply_filters('wpvivid_get_menu_capability_addon', 'menu_image_optimization');
        if($display)
        {
            $submenu['parent_slug'] = $parent_slug;
            $submenu['page_title'] = apply_filters('wpvivid_white_label_display', 'Image Optimization');
            $submenu['menu_title'] = 'Image Optimization';

            $submenu['capability'] = apply_filters("wpvivid_menu_capability","administrator","wpvivid-imgoptim");
            $submenu['menu_slug'] = strtolower(sprintf('%s-imgoptim', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
            $submenu['index'] = 8;//10;
            $submenu['function'] = array($this, 'display');
            $submenus[$submenu['menu_slug']] = $submenu;
        }

        return $submenus;
    }

    public function get_toolbar_menus($toolbar_menus)
    {
        $admin_url = apply_filters('wpvivid_get_admin_url', '');
        $display = apply_filters('wpvivid_get_menu_capability_addon', 'menu_image_optimization');
        if($display) {
            $menu['id'] = 'wpvivid_admin_menu_image_optimization';
            $menu['parent'] = 'wpvivid_admin_menu';
            $menu['title'] = 'Image Optimization';
            $menu['tab'] = 'admin.php?page=' . apply_filters('wpvivid_white_label_plugin_name', 'wpvivid-imgoptim');
            $menu['href'] = $admin_url . 'admin.php?page=' . apply_filters('wpvivid_white_label_plugin_name', 'wpvivid').'-imgoptim';

            $menu['capability'] = apply_filters("wpvivid_menu_capability","administrator","wpvivid-imgoptim");

            $menu['index'] = 8;
            $toolbar_menus[$menu['parent']]['child'][$menu['id']] = $menu;
        }
        return $toolbar_menus;
    }

    public function display()
    {
        $options=get_option('wpvivid_optimization_options',array());
        $memory_limit=isset($options['image_optimization_memory_limit'])?$options['image_optimization_memory_limit']:256;

        $memory_limit=max(256,intval($memory_limit));
        ini_set('memory_limit',$memory_limit.'M');

        global $wpvivid_imgoptim;
        $this->display=$wpvivid_imgoptim->display;
        $this->custom_folders_display=new WPvivid_ImgOptim_Custom_Folders_Display_Addon();
        do_action('wpvivid_show_imgoptim_notice');
        ?>
        <div class="wrap wpvivid-canvas">
            <div id="icon-options-general" class="icon32"></div>
            <h1><?php esc_attr_e( apply_filters('wpvivid_white_label_display', 'WPvivid').' Plugins - Image Optimization', 'wpvivid-imgoptim' ); ?></h1>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="wpvivid-backup">
                                <?php
                                $this->welcome_bar();
                                ?>
                                <div class="wpvivid-canvas wpvivid-clear-float">
                                    <?php
                                    if(!class_exists('WPvivid_Tab_Page_Container_Ex'))
                                        include_once WPVIVID_BACKUP_PRO_PLUGIN_DIR . 'includes/class-wpvivid-tab-page-container-ex.php';
                                    $this->main_tab=new WPvivid_Tab_Page_Container_Ex();

                                    $tabs = array();
                                    $first_tab=false;
                                    if( apply_filters('wpvivid_current_user_can',true,'wpvivid-can-use-image-optimization'))
                                    {
                                        $first_tab=true;
                                        $args['span_class']='dashicons dashicons-format-gallery wpvivid-dashicons-red';
                                        $args['span_style']='margin-top:0.1em;';
                                        $args['div_style']='padding-top:0;display:block;';
                                        $args['is_parent_tab']=0;
                                        $tabs['image_optimization']['title']='Image Optimization';
                                        $tabs['image_optimization']['slug']='image_optimization';
                                        $tabs['image_optimization']['callback']=array($this, 'image_optimization_page');
                                        $tabs['image_optimization']['args']=$args;
                                    }

                                    $tabs=apply_filters('wpvivid_image_optimization_page',$tabs,$first_tab);

                                    foreach ($tabs as $key=>$tab)
                                    {
                                        $this->main_tab->add_tab($tab['title'],$tab['slug'],$tab['callback'], $tab['args']);
                                    }

                                    $this->main_tab->display();
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->sidebar();?>
                </div>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($)
            {
                <?php
                if (isset($_GET['tab']))
                {
                $tab=$_GET['tab'];
                ?>
                jQuery( document ).trigger( '<?php echo $this->main_tab->container_id ?>-show','<?php echo $tab?>');
                <?php
                }
                ?>

                <?php
                if (isset($_GET['sub_tab']))
                {
                    $tab=$_GET['sub_tab'];
                ?>
                jQuery( document ).trigger( '<?php echo $this->display->main_tab->container_id ?>-show','<?php echo $tab?>');
                <?php
                }
                ?>

                <?php
                if(isset($_REQUEST['image_setting']))
                {
                ?>
                jQuery( document ).trigger( '<?php echo $this->display->main_tab->container_id; ?>-show',[ 'setting', 'setting' ]);
                <?php
                }
                ?>
            });
        </script>
        <?php
    }

    public function image_optimization_page()
    {
        ?>
        <div class="wpvivid-one-coloum" style="padding-top:0;">
            <?php
                $hide_notice = get_option('wpvivid_hide_imgoptm_not_exist_notice', '1');
                if($hide_notice === '0')
                {
                    $files_not_exist = get_option('wpvivid_imgoptm_file_not_exist', array());
                    if(!empty($files_not_exist))
                    {
                        if(sizeof($files_not_exist) === 1)
                        {
                            $file=array_shift($files_not_exist);
                            ?>
                            <div class="notice notice-error notice-imgoptm-not-exist is-dismissible"><p>The image <?php echo $file; ?> not found in the image optimization process.</p></div>
                            <?php
                        }
                        else
                        {
                            ?>
                            <div class="notice notice-error notice-imgoptm-not-exist is-dismissible"><p>Several images not found in the image optimization process.</p></div>
                            <?php
                        }
                    }
                }
            ?>

            <div class="wpvivid-one-coloum wpvivid-workflow wpvivid-clear-float" style="">
                <?php
                $this->progress();
                $this->overview();
                ?>
            </div>

            <div>
                <?php
                if(!class_exists('WPvivid_Tab_Page_Container_Ex'))
                {
                    include_once WPVIVID_BACKUP_PRO_PLUGIN_DIR . 'includes/class-wpvivid-tab-page-container-ex.php';
                }

                $args['is_parent_tab']=0;
                $args['transparency']=1;
                $this->display->main_tab=new WPvivid_Tab_Page_Container_Ex();
                $args['span_class']='dashicons dashicons-backup';
                $args['span_style']='color:#007cba; padding-right:0.5em;margin-top:0.1em;';
                $args['div_style']='padding:0;display:block;';
                $args['is_parent_tab']=0;
                $overview=$this->display->get_optimize_data();

                $tabs['optimize']['title']=__('Optimized Images('.$overview['optimized_images'].')','wpvivid-imgoptim');
                $tabs['optimize']['slug']='optimize';
                $tabs['optimize']['callback']=array($this, 'optimized_images');
                $tabs['optimize']['args']=$args;

                $overview=$this->custom_folders_display->get_optimize_data();

                $args['span_class']='dashicons dashicons-portfolio wpvivid-dashicons-orange';
                $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                $args['div_style']='padding:0;';
                $args['is_parent_tab']=0;
                $tabs['custom']['title']=__('Custom Folders('.$overview['optimized_images'].')','wpvivid-imgoptim');
                $tabs['custom']['slug']='custom_folders';
                $tabs['custom']['callback']=array($this->custom_folders_display, 'optimized_images');
                $tabs['custom']['args']=$args;

                $tabs=apply_filters('wpvivid_image_optimization_tab',$tabs);

                $loglist=$this->display->get_log_list();
                $args['span_class']='dashicons dashicons-welcome-write-blog';
                $args['span_style']='color:grey;padding-right:0.5em;margin-top:0.1em;';
                $args['div_style']='padding:0;';
                $args['is_parent_tab']=0;
                $tabs['log']['title']=__('Logs','wpvivid-imgoptim');
                $tabs['log']['slug']='log';
                $tabs['log']['callback']=array($this->display, 'error_log');
                $tabs['log']['args']=$args;

                foreach ($tabs as $key=>$tab)
                {
                    $this->display->main_tab->add_tab($tab['title'],$tab['slug'],$tab['callback'], $tab['args']);
                }
                $this->display->main_tab->display();
                ?>
            </div>
        </div>
        <script>
            jQuery(document).on('click', '.notice-imgoptm-not-exist .notice-dismiss', function(){
                var ajax_data = {
                    'action': 'wpvivid_hide_imgoptm_not_exist_notice'
                };
                wpvivid_post_request(ajax_data, function(res){
                }, function(XMLHttpRequest, textStatus, errorThrown) {
                });
            });
        </script>
        <?php
    }
    public function welcome_bar()
    {
        if ( ! function_exists( 'is_plugin_active' ) )
        {
            include_once(ABSPATH.'wp-admin/includes/plugin.php');
        }

        if(is_plugin_active('wpvivid-backuprestore/wpvivid-backuprestore.php'))
        {
            $url=apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvivid-backup', 'wpvivid-backup');
        }
        else
        {
            $url='plugin-install.php?s=wpvivid&tab=search&type=term';
        }

        ?>
        <div class="wpvivid-welcome-bar wpvivid-clear-float">
            <div class="wpvivid-welcome-bar-left">
                <p></p>
                <div>
                    <span class="dashicons dashicons-format-gallery wpvivid-dashicons-large wpvivid-dashicons-red"></span>
                    <span class="wpvivid-page-title"><?php _e('Image Bulk Optimization', 'wpvivid-imgoptim');?>
                        <span class="wpvivid-rectangle-small wpvivid-grey"><?php echo WPVIVID_IMGOPTIM_PRO_VERSION;?> pro</span>
                    </span>
                </div>
                <p>
                    <span class="about-description"><?php _e('The page allows you to optimize images on your website in bulk.', 'wpvivid-imgoptim');?></span>
                </p>
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
        </div>
        <div class="wpvivid-nav-bar wpvivid-clear-float">
            <span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span>
            <span><?php _e('It is recommended to back up the entire website with', 'wpvivid-imgoptim');?> <a href="<?php echo $url?>"> <?php echo apply_filters('wpvivid_white_label_display', 'WPvivid'); ?> Backup & Migration plugin (It's free)</a> before optimizing images.</span>
        </div>
        <?php
    }

    public function sidebar()
    {
        do_action('wpvivid_add_sidebar_image_optimization');
    }

    public function progress()
    {
        $task=new WPvivid_ImgOptim_Task_Ex();
        $result=$task->get_task_progress();

        if(get_option('wpvivid_pro_user',false)===false)
        {
            ?>
            <div id="wpvivid_image_optimize_progress" style="display: none">
                <p>
                    <span>
                        <strong><?php _e('Bulk Optimization Progress', 'wpvivid-imgoptim');?></strong>
                    </span>
                    <span style="float:right;">
                    <span class="wpvivid-rectangle wpvivid-green"><?php _e('Optimized', 'wpvivid-imgoptim');?></span>
                    <span class="wpvivid-rectangle wpvivid-grey"><?php _e('Un-optimized', 'wpvivid-imgoptim');?></span>
                    </span>
                </p>
                <p>
                    <span class="wpvivid-span-progress">
                    <span class="wpvivid-span-processed-progress" style="width:0%;">0% <?php _e('completed', 'wpvivid-imgoptim');?></span>
                    </span>
                </p>
            </div>
            <?php
            return;
        }

        if($result['result']=='success'&&$result['finished']==0)
        {
            if($result['is_schedule'])
            {
                $Processing='Processing (schedule):';
            }
            else
            {
                $Processing='Processing (real-time):';
            }
            $result['progress_html']='
                <div id="wpvivid_image_optimize_progress">
                    <p>
                        <span>
                            <strong>'.__('Bulk Optimization Progress','wpvivid-imgoptim').'</strong>
                        </span>
                        <span style="float:right;">
                            <span class="wpvivid-rectangle wpvivid-green">'.__('Optimized','wpvivid-imgoptim').'</span>
                            <span class="wpvivid-rectangle wpvivid-grey">'.__('Un-optimized','wpvivid-imgoptim').'</span>
                        </span>
                    </p>            
                    <p>
                        <span class="wpvivid-span-progress">
                            <span class="wpvivid-span-processed-progress" style="width:'.$result['percent'].'%;">'.$result['percent'].'% '.__('completed','wpvivid-imgoptim').'</span>
                        </span>
                    </p>
                    <p><span class="dashicons dashicons-flag wpvivid-dashicons-green"></span><span><strong>'.$Processing.' </strong></span>
						<span style="color:#999;">'.$result['log'].'</span>
						<span title="View logs"><a id="wpvivid_image_open_log" href="#">logs</a></span>
				    </p>
                </div>';
            echo  $result['progress_html'];
        }
        else
        {
            ?>
            <div id="wpvivid_image_optimize_progress" style="display: none">
                <p>
                    <span>
                        <strong><?php _e('Bulk Optimization Progress', 'wpvivid-imgoptim');?></strong>
                    </span>
                    <span style="float:right;">
                    <span class="wpvivid-rectangle wpvivid-green"><?php _e('Optimized', 'wpvivid-imgoptim');?></span>
                    <span class="wpvivid-rectangle wpvivid-grey"><?php _e('Un-optimized', 'wpvivid-imgoptim');?></span>
                    </span>
                </p>
                <p>
                    <span class="wpvivid-span-progress">
                    <span class="wpvivid-span-processed-progress" style="width:0%;">0% <?php _e('completed', 'wpvivid-imgoptim');?></span>
                    </span>
                </p>
            </div>
            <?php
        }
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

    public function overview()
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

            $admin_url='admin.php?page='.strtolower(sprintf('%s-imgoptim&image_setting', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
        }
        $unoptimized=max($overview['total_images']-$overview['optimized_images'],0);
        $webp=isset($overview['webp'])?$overview['webp']:0;
        ?>
        <div style="border-sizing:border-box;">
            <div style="width:66%;float:left;">
                <div style="padding:0.5em 0.5em 0.5em 0em;">
                    <span class="dashicons dashicons-admin-site wpvivid-dashicons-blue"></span>
                    <span><?php _e('Cloud Server', 'wpvivid-imgoptim');?>:</span>
                    <span id="wpvivid_server_name"><?php echo $server_cache['server_name']?></span>
                    <span> </span>
                    <span><a href="<?php echo $admin_url;?>"><?php _e('Settings', 'wpvivid-imgoptim');?></a></span>
                </div>
            </div>
            <div style="width:34%;float:left;">
                <div style="background:orange;color:#fff;padding:0.5em 0.5em 0.5em 1em;border-radius:15px 0 0 15px;">
                    <span class="dashicons dashicons-smartphone wpvivid-dashicons-white"></span>
                    <span class="wpvivid-dashicons">Credits:</span>
                    <span id="wpvivid_credits_remain"><?php echo $server_cache['remain']?></span>
                    <span></span>/<span id="wpvivid_credits_total"><?php echo $server_cache['total']?></span>
                    <span class="dashicons dashicons-editor-help wpvivid-dashicons-white-editor-help  wpvivid-tooltip">
                        <div class="wpvivid-bottom">
                            <!-- The content you need -->
                            <p>Credits stands for the total number of the images that you can optimize. Credits are consumed based on the actual number of images optimized.</p>
                            <p>For Example:</p>
                            <p>1. You have 10 images optimized, and they have 60 associated images, then 70 credits are used.<p>
                            <p>2. 900/1000 means you have 900 out of 1000 credits remaining in your account.</p>
                            <i></i> <!-- do not delete this line -->
                        </div>
                    </span>
                </div>
            </div>
            <div style="clear:both;"></div>
            <div style="width:66%;border-sizing:border-box;float:left;">
                <div style="padding:0.5em 0 0.5em 0;">
                    <span class="dashicons dashicons-admin-media wpvivid-dashicons-blue"></span>
                    <span><?php echo $overview['total_images'];?></span>
                    <span class="wpvivid-dashicons"> images & thumbnails in media library.</span>
                    <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                        <div class="wpvivid-bottom">
                            <!-- The content you need -->
                            <p>All original images and thumbnails(associated images) in your WordPress media library.</p>
                            <i></i> <!-- do not delete this line -->
                        </div>
                    </span>
                </div>
                <div style="width:50%; float:left; ">
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
                    <p>
                        <span class="dashicons dashicons-format-gallery wpvivid-dashicons-grey"></span>
                        <span class="wpvivid-dashicons">Un-optimized: </span><span id="wpvivid_overview_unoptimized"><?php echo $unoptimized;?></span>
                    </p>
                    <p>
                        <span class="dashicons dashicons-format-gallery wpvivid-dashicons-green"></span>
                        <span class="wpvivid-dashicons">Optimized: </span><span id="wpvivid_overview_optimized_images"><?php echo $overview['optimized_images'];?></span>
                    </p>
                </div>
                <div style="width:50%; float:left;">
                    <p>
                        <span class="dashicons dashicons-thumbs-down wpvivid-dashicons wpvivid-dashicons-red"></span>
                        <span><?php _e('Original Size', 'wpvivid-imgoptim');?>:</span>
                        <span id="wpvivid_overview_original_size"><?php echo size_format($overview['original_size'],2)?></span>
                    </p>
                    <p>
                        <span class="dashicons dashicons-thumbs-up wpvivid-dashicons wpvivid-dashicons-green"></span>
                        <span><?php _e('Optimized Size', 'wpvivid-imgoptim');?></span>
                        <span id="wpvivid_overview_optimized_size"><?php echo size_format($overview['optimized_size'],2)?></span>
                    </p>
                    <p>
                        <span class="dashicons dashicons-chart-line wpvivid-dashicons wpvivid-dashicons-blue"></span>
                        <span><?php _e('Total Saved', 'wpvivid-imgoptim');?>:</span>
                        <span id="wpvivid_overview_optimized_percent"><?php echo $overview['optimized_percent']?>%</span>
                    </p>
                </div>
                <?php
                if(apply_filters('wpvivid_show_sidebar',true))
                {
                    ?>
                    <div>
                        <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span>
                            <span><a href="https://docs.wpvivid.com/wpvivid-image-optimization-pro-convert-to-webp.html">How to check if WebP images have been effected?</a></span>
                        </p>
                    </div>
                    <?php
                }
                ?>
            </div>

            <?php
            $this->optimize();
            ?>
        </div>
        <script>
            jQuery(document).ready(function ()
            {
                <?php
                if(get_option('wpvivid_pro_user',false)===false)
                {

                }
                else
                {
                ?>
                wpvivid_get_server_status();
                <?php
                }
                ?>
                wpvivid_get_overview();
            });
            function wpvivid_get_server_status()
            {
                var ajax_data = {
                    'action': 'wpvivid_get_server_status_ex'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_credits_remain').html(jsonarray.status.remain);
                            jQuery('#wpvivid_credits_total').html(jsonarray.status.total);
                            jQuery('#wpvivid_server_name').html(jsonarray.status.server_name);
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
            function wpvivid_get_overview()
            {
                var ajax_data = {
                    'action': 'wpvivid_start_get_overview_ex'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            if(jsonarray.continue)
                            {
                                wpvivid_get_overview_progress();
                            }
                            else
                            {
                                jQuery('#wpvivid_overview_webp').html(jsonarray.status.webp);
                                jQuery('#wpvivid_overview_unoptimized').html(jsonarray.status.unoptimized);
                                jQuery('#wpvivid_overview_optimized_images').html(jsonarray.status.optimized_images);
                                jQuery('#wpvivid_overview_original_size').html(jsonarray.status.original_size_format);
                                jQuery('#wpvivid_overview_optimized_size').html(jsonarray.status.optimized_size_format);
                                jQuery('#wpvivid_overview_optimized_percent').html(jsonarray.status.optimized_percent_format);
                                jQuery('#wpvivid_tab_optimize').children().last().html("Optimized Images("+jsonarray.status.optimized_images+")");
                            }
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

            function wpvivid_get_overview_progress()
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
                            if(jsonarray.continue)
                            {
                                wpvivid_get_overview_progress();
                            }
                            else
                            {
                                jQuery('#wpvivid_overview_webp').html(jsonarray.status.webp);
                                jQuery('#wpvivid_overview_unoptimized').html(jsonarray.status.unoptimized);
                                jQuery('#wpvivid_overview_optimized_images').html(jsonarray.status.optimized_images);
                                jQuery('#wpvivid_overview_original_size').html(jsonarray.status.original_size_format);
                                jQuery('#wpvivid_overview_optimized_size').html(jsonarray.status.optimized_size_format);
                                jQuery('#wpvivid_overview_optimized_percent').html(jsonarray.status.optimized_percent_format);
                                jQuery('#wpvivid_tab_optimize').children().last().html("Optimized Images("+jsonarray.status.optimized_images+")");
                            }
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
        <?php
    }

    public function optimize()
    {
        $task=new WPvivid_ImgOptim_Task_Ex();
        $result=$task->get_task_progress();

        if ( ! function_exists( 'is_plugin_active' ) )
        {
            include_once(ABSPATH.'wp-admin/includes/plugin.php');
        }

        if(is_plugin_active('wpvivid-backuprestore/wpvivid-backuprestore.php'))
        {
            $url='admin.php?page=wpvivid-backup';
        }
        else
        {
            $url='plugin-install.php?s=wpvivid&tab=search&type=term';
        }
        //$admin_url='admin.php?page='.strtolower(sprintf('%s-setting', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
        $admin_url='admin.php?page='.strtolower(sprintf('%s-imgoptim&image_setting', apply_filters('wpvivid_white_label_slug', 'wpvivid')));
        ?>
        <div style="width:34%; float:left;">
            <div style="padding:1em 0 1em 0;">
                <fieldset>
                    <label class="wpvivid-radio">Resizing images only
                        <input type="radio" name="imgopt_resize" value="1">
                        <span class="wpvivid-radio-checkmark"></span>
                    </label>
                    <label class="wpvivid-radio">Smart image bulk optimization(SIBO)
                        <input type="radio" name="imgopt_resize" value="0" checked>
                        <span class="wpvivid-radio-checkmark"></span>
                        <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                                <div class="wpvivid-bottom">
                                    <!-- The content you need -->
                                    <p>
                                        With the SIBO option ticked, our plugin will perform compression and resizing on the fly according to the image status, and run WebP conversion according to your settings. All you have to do is clicking the ‘Optimize Now’ button to start the process.
                                    </p>
                                    <i></i> <!-- do not delete this line -->
                                </div>
                            </span>
                    </label>
                </fieldset>
            </div>
            <div style="">
                <span>
                    <input class="button-primary" style="width: 200px; height: 50px; font-size: 20px; margin-bottom: 10px; pointer-events: auto; opacity: 1;" id="wpvivid_start_opt" type="submit" value="Optimize Now">
                    <input class="button-primary" style="display:none;width: 200px; height: 50px; font-size: 20px; margin-bottom: 10px; pointer-events: auto; opacity: 1;" id="wpvivid_cancel_opt" type="submit" value="Cancel">
                </span>
                <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip" style="padding-top:0.8em;">
                        <div class="wpvivid-bottom">
                            <!-- The content you need -->
                            <p>Click the "Optimize Now" button to perform a real-time bulk optimization, you can refresh but <u>do not close</u> the page when performing the real-time bulk optimization.</p>
                            <i></i> <!-- do not delete this line -->
                        </div>
                    </span>
            </div>
            <div>
                <p>or enable auto-optimize (schedule) in <a href="<?php echo $admin_url;?>">settings</a></p>
            </div>
        </div>
        <script>
            jQuery('#wpvivid_start_opt').click(function()
            {
                wpvivid_start_opt();
            });

            jQuery('#wpvivid_cancel_opt').click(function()
            {
                if (confirm('Are you sure you want to cancel optimization?'))
                {
                    wpvivid_cancel_opt();
                }
            });

            function wpvivid_cancel_opt()
            {
                jQuery('#wpvivid_cancel_opt').prop('disabled', true);

                var ajax_data = {
                    'action': 'wpvivid_cancel_opt_task_ex',
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('cancel task', textStatus, errorThrown);
                    alert(error_message);

                    jQuery('#wpvivid_cancel_opt').prop('disabled', false);
                });
            }

            function wpvivid_start_opt()
            {
                var html='<p><span><strong>Bulk Optimization Progress</strong></span>' +
                    '<span style="float:right;"><span class="wpvivid-rectangle wpvivid-green">Optimized</span>' +
                    '<span class="wpvivid-rectangle wpvivid-grey">Un-optimized</span></span></p>' +
                    '<p><span class="wpvivid-span-progress"><span class="wpvivid-span-processed-progress" style="width:0%;">0% completed</span></span></p>'
                    +'<p><span class="dashicons dashicons-flag wpvivid-dashicons-green"></span><span><strong>Processing</strong></span></p>';
                jQuery('#wpvivid_image_optimize_progress').show();
                jQuery('#wpvivid_image_optimize_progress').html(html);
                jQuery('#wpvivid_start_opt').prop('disabled', true);
                jQuery('#wpvivid_start_opt').hide();
                jQuery('#wpvivid_cancel_opt').show();
                var resize='0';
                jQuery('input:radio[name=imgopt_resize]').each(function()
                {
                    if(jQuery(this).prop('checked'))
                    {
                        resize = jQuery(this).prop('value');
                    }
                });

                var ajax_data = {
                    'action': 'wpvivid_init_opt_task_ex',
                    'resize': resize
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            if(typeof jsonarray.continue !== 'undefined' && jsonarray.continue)
                            {
                                wpvivid_start_opt_ex();
                            }
                            else
                            {
                                wpvivid_get_opt_progress();
                            }
                        }
                        else if (jsonarray.result === 'failed')
                        {
                            alert(jsonarray.error);
                            jQuery('#wpvivid_image_optimize_progress').hide();
                            jQuery('#wpvivid_start_opt').prop('disabled', false);
                            jQuery('#wpvivid_start_opt').show();
                            jQuery('#wpvivid_cancel_opt').hide();
                        }
                    }
                    catch(err)
                    {
                        alert(err);
                        jQuery('#wpvivid_image_optimize_progress').hide();
                        jQuery('#wpvivid_start_opt').prop('disabled', false);
                        jQuery('#wpvivid_start_opt').show();
                        jQuery('#wpvivid_cancel_opt').hide();
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('start task', textStatus, errorThrown);
                    wpvivid_get_need_increase_memory_retry(error_message);
                });
            }

            function wpvivid_start_opt_ex()
            {
                var html='<p><span><strong>Bulk Optimization Progress</strong></span>' +
                    '<span style="float:right;"><span class="wpvivid-rectangle wpvivid-green">Optimized</span>' +
                    '<span class="wpvivid-rectangle wpvivid-grey">Un-optimized</span></span></p>' +
                    '<p><span class="wpvivid-span-progress"><span class="wpvivid-span-processed-progress" style="width:0%;">0% completed</span></span></p>'
                    +'<p><span class="dashicons dashicons-flag wpvivid-dashicons-green"></span><span><strong>Processing</strong></span></p>';
                jQuery('#wpvivid_image_optimize_progress').show();
                jQuery('#wpvivid_image_optimize_progress').html(html);
                jQuery('#wpvivid_start_opt').prop('disabled', true);
                jQuery('#wpvivid_start_opt').hide();
                jQuery('#wpvivid_cancel_opt').show();
                var resize='0';
                jQuery('input:radio[name=imgopt_resize]').each(function()
                {
                    if(jQuery(this).prop('checked'))
                    {
                        resize = jQuery(this).prop('value');
                    }
                });

                var ajax_data = {
                    'action': 'wpvivid_start_opt_task_ex',
                    'resize': resize
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            if(typeof jsonarray.continue !== 'undefined' && jsonarray.continue)
                            {
                                wpvivid_start_opt_ex();
                            }
                            else
                            {
                                wpvivid_get_opt_progress();
                            }
                        }
                        else if (jsonarray.result === 'failed')
                        {
                            alert(jsonarray.error);
                            jQuery('#wpvivid_image_optimize_progress').hide();
                            jQuery('#wpvivid_start_opt').prop('disabled', false);
                            jQuery('#wpvivid_start_opt').show();
                            jQuery('#wpvivid_cancel_opt').hide();
                        }
                    }
                    catch(err)
                    {
                        alert(err);
                        jQuery('#wpvivid_image_optimize_progress').hide();
                        jQuery('#wpvivid_start_opt').prop('disabled', false);
                        jQuery('#wpvivid_start_opt').show();
                        jQuery('#wpvivid_cancel_opt').hide();
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('start task', textStatus, errorThrown);
                    wpvivid_get_need_increase_memory_retry(error_message);
                });
            }

            function wpvivid_get_need_increase_memory_retry(error_message)
            {
                var ajax_data = {
                    'action': 'wpvivid_get_need_increase_memory_retry'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            if(jsonarray.retry)
                            {
                                wpvivid_start_opt();
                            }
                            else
                            {
                                alert(error_message);
                                jQuery('#wpvivid_image_optimize_progress').hide();
                                jQuery('#wpvivid_start_opt').prop('disabled', false);
                                jQuery('#wpvivid_start_opt').show();
                                jQuery('#wpvivid_cancel_opt').hide();
                            }
                        }
                        else if (jsonarray.result === 'failed')
                        {
                            alert(error_message);
                            jQuery('#wpvivid_image_optimize_progress').hide();
                            jQuery('#wpvivid_start_opt').prop('disabled', false);
                            jQuery('#wpvivid_start_opt').show();
                            jQuery('#wpvivid_cancel_opt').hide();
                        }
                    }
                    catch(err)
                    {
                        alert(error_message);
                        jQuery('#wpvivid_image_optimize_progress').hide();
                        jQuery('#wpvivid_start_opt').prop('disabled', false);
                        jQuery('#wpvivid_start_opt').show();
                        jQuery('#wpvivid_cancel_opt').hide();
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    alert(error_message);
                    jQuery('#wpvivid_image_optimize_progress').hide();
                    jQuery('#wpvivid_start_opt').prop('disabled', false);
                    jQuery('#wpvivid_start_opt').show();
                    jQuery('#wpvivid_cancel_opt').hide();
                });
            }

            function wpvivid_get_opt_progress()
            {
                var ajax_data = {
                    'action': 'wpvivid_get_opt_progress_ex'
                };

                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_image_optimize_progress').html(jsonarray.progress_html);
                            if(jsonarray.continue)
                            {
                                setTimeout(function ()
                                {
                                    wpvivid_get_opt_progress();
                                }, 1000);
                            }
                            else if(jsonarray.finished)
                            {
                                alert("The optimization completed successfully.");
                                location.reload();
                            }
                            else
                            {
                                wpvivid_opt_image();
                            }
                        }
                        else if (jsonarray.result === 'failed')
                        {
                            if(jsonarray.timeout)
                            {
                                wpvivid_opt_image();
                            }
                            else
                            {
                                alert(jsonarray.error);
                                jQuery('#wpvivid_image_optimize_progress').hide();
                                jQuery('#wpvivid_start_opt').prop('disabled', false);
                                jQuery('#wpvivid_start_opt').show();
                                jQuery('#wpvivid_cancel_opt').hide();
                            }
                        }
                    }
                    catch(err)
                    {
                        alert(err);
                        jQuery('#wpvivid_image_optimize_progress').hide();
                        jQuery('#wpvivid_start_opt').prop('disabled', false);
                        jQuery('#wpvivid_start_opt').show();
                        jQuery('#wpvivid_cancel_opt').hide();
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function ()
                    {
                        wpvivid_get_opt_progress();
                    }, 1000);
                });
            }

            function wpvivid_opt_image()
            {
                var ajax_data = {
                    'action': 'wpvivid_opt_image_ex'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            setTimeout(function ()
                            {
                                wpvivid_get_opt_progress();
                            }, 1000);
                        }
                        else if (jsonarray.result === 'failed')
                        {
                            alert(jsonarray.error);
                            jQuery('#wpvivid_image_optimize_progress').hide();
                            jQuery('#wpvivid_start_opt').prop('disabled', false);
                            jQuery('#wpvivid_start_opt').show();
                            jQuery('#wpvivid_cancel_opt').hide();
                        }
                    }
                    catch(err)
                    {
                        alert(err);
                        jQuery('#wpvivid_image_optimize_progress').hide();
                        jQuery('#wpvivid_start_opt').prop('disabled', false);
                        jQuery('#wpvivid_start_opt').show();
                        jQuery('#wpvivid_cancel_opt').hide();
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_get_opt_progress();
                });
            }

            jQuery(document).ready(function ()
            {
                <?php
                $info= get_option('wpvivid_pro_user',false);
                if($info===false)
                {
                ?>
                jQuery('#wpvivid_start_opt').prop('disabled', true);
                return;
                <?php
                }

                if($result['result']=='success'&&$result['finished']==0)
                {
                $cancel=get_option('wpvivid_image_opt_task_cancel',false);
                if($cancel)
                {
                ?>
                jQuery('#wpvivid_cancel_opt').prop('disabled', true);
                jQuery('#wpvivid_start_opt').hide();
                jQuery('#wpvivid_cancel_opt').show();
                <?php
                }
                else
                {
                ?>
                jQuery('#wpvivid_start_opt').prop('disabled', false);
                jQuery('#wpvivid_start_opt').hide();
                jQuery('#wpvivid_cancel_opt').show();
                <?php
                }
                }
                else
                {
                ?>
                jQuery('#wpvivid_start_opt').prop('disabled', false);
                jQuery('#wpvivid_start_opt').show();
                jQuery('#wpvivid_cancel_opt').hide();
                <?php
                }

                ?>

                var ajax_data = {
                    'action': 'wpvivid_get_opt_progress_ex'
                };

                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_image_optimize_progress').html(jsonarray.progress_html);
                            if(jsonarray.continue)
                            {
                                setTimeout(function ()
                                {
                                    wpvivid_get_opt_progress();
                                }, 1000);
                            }
                            else if(jsonarray.finished)
                            {

                            }
                            else
                            {
                                wpvivid_opt_image();
                            }
                        }
                        else if (jsonarray.result === 'failed')
                        {
                            if(jsonarray.timeout)
                            {
                                wpvivid_opt_image();
                            }
                            else
                            {
                                jQuery('#wpvivid_image_optimize_progress').hide();
                                jQuery('#wpvivid_start_opt').prop('disabled', false);
                                jQuery('#wpvivid_start_opt').show();
                                jQuery('#wpvivid_cancel_opt').hide();
                            }
                        }
                    }
                    catch(err)
                    {
                        alert(err);
                        jQuery('#wpvivid_image_optimize_progress').hide();
                        jQuery('#wpvivid_start_opt').prop('disabled', false);
                        jQuery('#wpvivid_start_opt').show();
                        jQuery('#wpvivid_cancel_opt').hide();
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('get progress', textStatus, errorThrown);
                    alert(error_message);

                    jQuery('#wpvivid_image_optimize_progress').hide();
                    jQuery('#wpvivid_start_opt').prop('disabled', false);
                    jQuery('#wpvivid_start_opt').show();
                    jQuery('#wpvivid_cancel_opt').hide();
                });
            });
        </script>
        <?php
    }

    public function get_server_status()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        $options=get_option('wpvivid_optimization_options',array());
        $region=isset($options['region'])?$options['region']:'us2';
        if($region=='us1')
        {
            $server_name='North American - Pro';
            $options['region']='us2';
            update_option('wpvivid_optimization_options',$options,'no');
        }
        else if($region=='us2')
        {
            $server_name='North American - Pro';
        }
        else if($region=='eu1')
        {
            $server_name='Europe - Pro';
        }
        else
        {
            $server_name='North American - Pro';
        }

        $info= get_option('wpvivid_pro_user',false);
        if($info===false)
        {
            $ret['result']='success';
            $ret['server_name']=$server_name;
            $ret['total']=0;
            $ret['remain']=0;
            echo json_encode($ret);
            die();
        }

        $user_info=$info['token'];

        $task=new WPvivid_Image_Optimize_Connect_server_Ex();
        $ret=$task->get_image_optimization_status($user_info);

        if($ret['result']=='success')
        {
            $options=$ret['status'];
            $options['time']=time();
            update_option('wpvivid_server_cache',$options,'no');
        }
        else {
            delete_option('wpvivid_server_cache');
        }

        echo json_encode($ret);
        die();
    }

    public function start_get_overview()
    {
        $options=get_option('wpvivid_optimization_options',array());
        $memory_limit=isset($options['image_optimization_memory_limit'])?$options['image_optimization_memory_limit']:256;

        $memory_limit=max(512,intval($memory_limit));
        ini_set('memory_limit',$memory_limit.'M');

        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');
        $optimize_data = array(
            'original_size'  => 0,
            'optimized_size' => 0,
            'optimized_percent'=> 0,
            'total_images'     => 0,
            'optimized_images' => 0,
            'test_optimized_images'=>array(),
            'webp'=>0
        );

        $overview_array=array();
        $overview_array['webp']=0;
        $overview_array['unoptimized']=0;
        $overview_array['optimized_images']=0;
        $overview_array['original_size']=0;
        $overview_array['optimized_size']=0;
        $overview_array['optimized_percent']=0;
        $overview_array['total_images']=0;
        $overview_array['offset']=0;
        update_option('wpvivid_get_get_overview_ex', $overview_array,'no');

        if(is_multisite())
        {
            $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );
            $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);
            $options=get_option('wpvivid_optimization_options',array());
            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => - 1,
            );
            $total_images=array();
            $blogs_ids=get_sites();
            foreach( $blogs_ids as $site )
            {
                $site_id = get_object_vars($site)["blog_id"];

                switch_to_blog( $site_id );

                $query_images = new WP_Query( $query_images_args );
                foreach ( $query_images->posts as $image )
                {
                    $type=get_post_mime_type($image->ID);
                    if (in_array( $type, $mime_types, true ))
                    {
                        $total_images[$site_id][] = $image->ID;
                    }
                }
                restore_current_blog();
            }

            if(empty($total_images))
            {
                $optimize_data['total_images']=0;
            }
            else
            {
                foreach ($total_images as $site_id=>$images)
                {
                    switch_to_blog( $site_id );

                    $not_found=array();

                    foreach ($images as $image_id)
                    {
                        $image_opt_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
                        $meta = wp_get_attachment_metadata( $image_id, true );
                        $file_path = get_attached_file( $image_id );

                        if(isset($options['skip_size'])&&isset($options['skip_size']['og']))
                        {
                            if($options['skip_size']['og']!=true)
                            {
                                if (!file_exists($file_path))
                                {
                                    $not_found[$image_id]['og']=$file_path;
                                }
                                else
                                {
                                    $optimize_data['total_images']++;
                                    if(isset($image_opt_meta['size']['og']))
                                    {
                                        if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                        {
                                            $optimize_data['optimized_images']++;
                                        }

                                        if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                        {
                                            $optimize_data['webp']++;
                                        }
                                        else
                                        {
                                            $webp_file =$file_path.'.webp';
                                            if (file_exists($webp_file))
                                            {
                                                $optimize_data['webp']++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (!file_exists($file_path))
                            {
                                $not_found[$image_id]['og']=$file_path;
                            }
                            else
                            {
                                $optimize_data['total_images']++;
                                if(isset($image_opt_meta['size']['og']))
                                {
                                    if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                    {
                                        $optimize_data['optimized_images']++;
                                    }

                                    if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                    {
                                        $optimize_data['webp']++;
                                    }
                                    else
                                    {
                                        $webp_file =$file_path.'.webp';
                                        if (file_exists($webp_file))
                                        {
                                            $optimize_data['webp']++;
                                        }
                                    }
                                }
                            }
                        }

                        if(!empty($image_opt_meta['sum']))
                        {
                            $optimize_data['original_size']  += ! empty( $image_opt_meta['sum']['og_size'] ) ? (int) $image_opt_meta['sum']['og_size'] : 0;
                            $optimize_data['optimized_size']   += ! empty( $image_opt_meta['sum']['opt_size'] ) ? (int) $image_opt_meta['sum']['opt_size'] : 0;
                        }

                        if(!empty($meta['sizes']))
                        {
                            foreach ( $meta['sizes'] as $size_key => $size_data )
                            {
                                $filename = path_join(dirname($file_path), $size_data['file']);

                                if (!file_exists($filename))
                                {
                                    $not_found[$image_id][$size_key]=$filename;
                                    continue;
                                }

                                if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
                                {
                                    if($options['skip_size'][$size_key])
                                        continue;
                                }

                                $optimize_data['total_images']++;

                                if(isset($image_opt_meta['size'][$size_key]))
                                {
                                    if(isset($image_opt_meta['size'][$size_key]['opt_status'])&&$image_opt_meta['size'][$size_key]['opt_status']==1)
                                    {
                                        $optimize_data['optimized_images']++;
                                    }

                                    if(isset($image_opt_meta['size'][$size_key]['webp_status'])&&$image_opt_meta['size'][$size_key]['webp_status']==1)
                                    {
                                        $optimize_data['webp']++;
                                    }
                                    else
                                    {
                                        $webp_file =$filename.'.webp';
                                        if (file_exists($webp_file))
                                        {
                                            $optimize_data['webp']++;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    restore_current_blog();
                }
            }
        }
        else
        {
            $args = array (
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => 0,
            ) ;
            $attatchments = new WP_Query ($args) ;
            $count = $attatchments->found_posts ;

            $offset=$overview_array['offset'];
            $page=2000;

            $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );
            $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);
            $options=get_option('wpvivid_optimization_options',array());
            $not_found=array();
            $optimize_data['total_images']=$overview_array['total_images'];

            $start_time=time();
            while($offset<$count)
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

                $total_images=array();
                foreach ( $query_images->posts as $image )
                {
                    $type=get_post_mime_type($image->ID);
                    if (in_array( $type, $mime_types, true ))
                    {
                        $total_images[] = $image->ID ;
                    }
                }

                $query_images=null;
                if(empty($total_images))
                {
                    continue;
                }

                foreach ($total_images as $image_id)
                {
                    $image_opt_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
                    $meta = wp_get_attachment_metadata( $image_id, true );
                    $file_path = get_attached_file( $image_id );

                    if(isset($options['skip_size'])&&isset($options['skip_size']['og']))
                    {
                        if($options['skip_size']['og']!=true)
                        {
                            if (!file_exists($file_path))
                            {
                                $not_found[$image_id]['og']=$file_path;
                            }
                            else
                            {
                                $optimize_data['total_images']++;
                                if(isset($image_opt_meta['size']['og']))
                                {
                                    if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                    {
                                        $optimize_data['optimized_images']++;
                                    }

                                    if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                    {
                                        $optimize_data['webp']++;
                                    }
                                    else
                                    {
                                        $webp_file =$file_path.'.webp';
                                        if (file_exists($webp_file))
                                        {
                                            $optimize_data['webp']++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        if (!file_exists($file_path))
                        {
                            $not_found[$image_id]['og']=$file_path;
                        }
                        else
                        {
                            $optimize_data['total_images']++;
                            if(isset($image_opt_meta['size']['og']))
                            {
                                if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                {
                                    $optimize_data['optimized_images']++;
                                }

                                if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                {
                                    $optimize_data['webp']++;
                                }
                                else
                                {
                                    $webp_file =$file_path.'.webp';
                                    if (file_exists($webp_file))
                                    {
                                        $optimize_data['webp']++;
                                    }
                                }
                            }
                        }
                    }

                    if(!empty($image_opt_meta['sum']))
                    {
                        $optimize_data['original_size']  += ! empty( $image_opt_meta['sum']['og_size'] ) ? (int) $image_opt_meta['sum']['og_size'] : 0;
                        $optimize_data['optimized_size']   += ! empty( $image_opt_meta['sum']['opt_size'] ) ? (int) $image_opt_meta['sum']['opt_size'] : 0;
                    }

                    if(!empty($meta['sizes']))
                    {
                        foreach ( $meta['sizes'] as $size_key => $size_data )
                        {
                            $filename = path_join(dirname($file_path), $size_data['file']);

                            if (!file_exists($filename))
                            {
                                $not_found[$image_id][$size_key]=$filename;
                                continue;
                            }

                            if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
                            {
                                if($options['skip_size'][$size_key])
                                    continue;
                            }

                            $optimize_data['total_images']++;

                            if(isset($image_opt_meta['size'][$size_key]))
                            {
                                if(isset($image_opt_meta['size'][$size_key]['opt_status'])&&$image_opt_meta['size'][$size_key]['opt_status']==1)
                                {
                                    $optimize_data['optimized_images']++;
                                }

                                if(isset($image_opt_meta['size'][$size_key]['webp_status'])&&$image_opt_meta['size'][$size_key]['webp_status']==1)
                                {
                                    $optimize_data['webp']++;
                                }
                                else
                                {
                                    $webp_file =$filename.'.webp';
                                    if (file_exists($webp_file))
                                    {
                                        $optimize_data['webp']++;
                                    }
                                }
                            }
                        }
                    }
                }
                $total_images=null;
                $current_time=time();
                if($current_time - $start_time >= 21)
                {
                    $overview_array=array();
                    $overview_array['webp']=isset($optimize_data['webp'])?$optimize_data['webp']:0;
                    $overview_array['unoptimized']=isset($optimize_data['unoptimized'])?$optimize_data['unoptimized']:0;
                    $overview_array['optimized_images']=isset($optimize_data['optimized_images'])?$optimize_data['optimized_images']:0;
                    $overview_array['original_size']=isset($optimize_data['original_size'])?$optimize_data['original_size']:0;
                    $overview_array['optimized_size']=isset($optimize_data['optimized_size'])?$optimize_data['optimized_size']:0;
                    $overview_array['optimized_percent']=isset($optimize_data['optimized_percent'])?$optimize_data['optimized_percent']:0;
                    $overview_array['total_images']=isset($optimize_data['total_images'])?$optimize_data['total_images']:0;
                    $overview_array['offset']=$offset;
                    update_option('wpvivid_get_get_overview_ex', $overview_array,'no');
                    $ret['result']='success';
                    $ret['continue']=1;
                    echo json_encode($ret);
                    die();
                }
            }
        }

        if($optimize_data['optimized_size']>0&&$optimize_data['original_size']>0)
        {
            $optimize_data['optimized_percent']=intval(100-($optimize_data['optimized_size']/$optimize_data['original_size'])*100);
        }

        $optimize_data['unoptimized']=max($optimize_data['total_images']-$optimize_data['optimized_images'],0);

        $optimize_data['original_size_format']=size_format($optimize_data['original_size'],2);
        $optimize_data['optimized_size_format']=size_format($optimize_data['optimized_size'],2);
        $optimize_data['optimized_percent_format']= $optimize_data['optimized_percent'].'%';
        $ret['status']=$optimize_data;
        $ret['result']='success';
        $ret['continue']=0;
        update_option('wpvivid_imgoptim_overview',$optimize_data,'no');
        echo json_encode($ret);
        die();
    }

    public function get_overview()
    {
        //$options=get_option('wpvivid_optimization_options',array());
        //$memory_limit=isset($options['image_optimization_memory_limit'])?$options['image_optimization_memory_limit']:256;

        //$memory_limit=max(256,intval($memory_limit));
        //ini_set('memory_limit',$memory_limit.'M');

        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');
        $optimize_data = array(
            'original_size'  => 0,
            'optimized_size' => 0,
            'optimized_percent'=> 0,
            'total_images'     => 0,
            'optimized_images' => 0,
            'test_optimized_images'=>array(),
            'webp'=>0
        );

        $overview_array=get_option('wpvivid_get_get_overview_ex', array());
        $optimize_data['webp']=isset($overview_array['webp'])?$overview_array['webp']:0;
        $optimize_data['unoptimized']=isset($overview_array['unoptimized'])?$overview_array['unoptimized']:0;
        $optimize_data['optimized_images']=isset($overview_array['optimized_images'])?$overview_array['optimized_images']:0;
        $optimize_data['original_size']=isset($overview_array['original_size'])?$overview_array['original_size']:0;
        $optimize_data['optimized_size']=isset($overview_array['optimized_size'])?$overview_array['optimized_size']:0;
        $optimize_data['optimized_percent']=isset($overview_array['optimized_percent'])?$overview_array['optimized_percent']:0;
        $optimize_data['total_images']=isset($overview_array['total_images'])?$overview_array['total_images']:0;

        if(is_multisite())
        {
            $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );
            $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);
            $options=get_option('wpvivid_optimization_options',array());
            $query_images_args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => - 1,
            );
            $total_images=array();
            $blogs_ids=get_sites();
            foreach( $blogs_ids as $site )
            {
                $site_id = get_object_vars($site)["blog_id"];

                switch_to_blog( $site_id );

                $query_images = new WP_Query( $query_images_args );
                foreach ( $query_images->posts as $image )
                {
                    $type=get_post_mime_type($image->ID);
                    if (in_array( $type, $mime_types, true ))
                    {
                        $total_images[$site_id][] = $image->ID;
                    }
                }
                restore_current_blog();
            }

            if(empty($total_images))
            {
                $optimize_data['total_images']=0;
            }
            else
            {
                foreach ($total_images as $site_id=>$images)
                {
                    switch_to_blog( $site_id );

                    $not_found=array();

                    foreach ($images as $image_id)
                    {
                        $image_opt_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
                        $meta = wp_get_attachment_metadata( $image_id, true );
                        $file_path = get_attached_file( $image_id );

                        if(isset($options['skip_size'])&&isset($options['skip_size']['og']))
                        {
                            if($options['skip_size']['og']!=true)
                            {
                                if (!file_exists($file_path))
                                {
                                    $not_found[$image_id]['og']=$file_path;
                                }
                                else
                                {
                                    $optimize_data['total_images']++;
                                    if(isset($image_opt_meta['size']['og']))
                                    {
                                        if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                        {
                                            $optimize_data['optimized_images']++;
                                        }

                                        if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                        {
                                            $optimize_data['webp']++;
                                        }
                                        else
                                        {
                                            $webp_file =$file_path.'.webp';
                                            if (file_exists($webp_file))
                                            {
                                                $optimize_data['webp']++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            if (!file_exists($file_path))
                            {
                                $not_found[$image_id]['og']=$file_path;
                            }
                            else
                            {
                                $optimize_data['total_images']++;
                                if(isset($image_opt_meta['size']['og']))
                                {
                                    if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                    {
                                        $optimize_data['optimized_images']++;
                                    }

                                    if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                    {
                                        $optimize_data['webp']++;
                                    }
                                    else
                                    {
                                        $webp_file =$file_path.'.webp';
                                        if (file_exists($webp_file))
                                        {
                                            $optimize_data['webp']++;
                                        }
                                    }
                                }
                            }
                        }

                        if(!empty($image_opt_meta['sum']))
                        {
                            $optimize_data['original_size']  += ! empty( $image_opt_meta['sum']['og_size'] ) ? (int) $image_opt_meta['sum']['og_size'] : 0;
                            $optimize_data['optimized_size']   += ! empty( $image_opt_meta['sum']['opt_size'] ) ? (int) $image_opt_meta['sum']['opt_size'] : 0;
                        }

                        if(!empty($meta['sizes']))
                        {
                            foreach ( $meta['sizes'] as $size_key => $size_data )
                            {
                                $filename = path_join(dirname($file_path), $size_data['file']);

                                if (!file_exists($filename))
                                {
                                    $not_found[$image_id][$size_key]=$filename;
                                    continue;
                                }

                                if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
                                {
                                    if($options['skip_size'][$size_key])
                                        continue;
                                }

                                $optimize_data['total_images']++;

                                if(isset($image_opt_meta['size'][$size_key]))
                                {
                                    if(isset($image_opt_meta['size'][$size_key]['opt_status'])&&$image_opt_meta['size'][$size_key]['opt_status']==1)
                                    {
                                        $optimize_data['optimized_images']++;
                                    }

                                    if(isset($image_opt_meta['size'][$size_key]['webp_status'])&&$image_opt_meta['size'][$size_key]['webp_status']==1)
                                    {
                                        $optimize_data['webp']++;
                                    }
                                    else
                                    {
                                        $webp_file =$filename.'.webp';
                                        if (file_exists($webp_file))
                                        {
                                            $optimize_data['webp']++;
                                        }
                                    }
                                }
                            }
                        }
                    }

                    restore_current_blog();
                }
            }
        }
        else
        {
            $args = array (
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => 0,
            ) ;
            $attatchments = new WP_Query ($args) ;
            $count = $attatchments->found_posts ;

            $offset=isset($overview_array['offset'])?$overview_array['offset']:0;
            $page=2000;

            $mime_types = array('image/jpeg', 'image/jpg', 'image/png' );
            $mime_types=apply_filters('wpvivid_imgoptim_support_mime_types',$mime_types);
            $options=get_option('wpvivid_optimization_options',array());
            $not_found=array();
            //$optimize_data['total_images']=0;

            $start_time=time();
            while($offset<$count)
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

                $total_images=array();
                foreach ( $query_images->posts as $image )
                {
                    $type=get_post_mime_type($image->ID);
                    if (in_array( $type, $mime_types, true ))
                    {
                        $total_images[] = $image->ID ;
                    }
                }

                $query_images=null;
                if(empty($total_images))
                {
                    continue;
                }

                foreach ($total_images as $image_id)
                {
                    $image_opt_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
                    $meta = wp_get_attachment_metadata( $image_id, true );
                    $file_path = get_attached_file( $image_id );

                    if(isset($options['skip_size'])&&isset($options['skip_size']['og']))
                    {
                        if($options['skip_size']['og']!=true)
                        {
                            if (!file_exists($file_path))
                            {
                                $not_found[$image_id]['og']=$file_path;
                            }
                            else
                            {
                                $optimize_data['total_images']++;
                                if(isset($image_opt_meta['size']['og']))
                                {
                                    if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                    {
                                        $optimize_data['optimized_images']++;
                                    }

                                    if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                    {
                                        $optimize_data['webp']++;
                                    }
                                    else
                                    {
                                        $webp_file =$file_path.'.webp';
                                        if (file_exists($webp_file))
                                        {
                                            $optimize_data['webp']++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        if (!file_exists($file_path))
                        {
                            $not_found[$image_id]['og']=$file_path;
                        }
                        else
                        {
                            $optimize_data['total_images']++;
                            if(isset($image_opt_meta['size']['og']))
                            {
                                if(isset($image_opt_meta['size']['og']['opt_status'])&&$image_opt_meta['size']['og']['opt_status']==1)
                                {
                                    $optimize_data['optimized_images']++;
                                }

                                if(isset($image_opt_meta['size']['og']['webp_status'])&&$image_opt_meta['size']['og']['webp_status']==1)
                                {
                                    $optimize_data['webp']++;
                                }
                                else
                                {
                                    $webp_file =$file_path.'.webp';
                                    if (file_exists($webp_file))
                                    {
                                        $optimize_data['webp']++;
                                    }
                                }
                            }
                        }
                    }

                    if(!empty($image_opt_meta['sum']))
                    {
                        $optimize_data['original_size']  += ! empty( $image_opt_meta['sum']['og_size'] ) ? (int) $image_opt_meta['sum']['og_size'] : 0;
                        $optimize_data['optimized_size']   += ! empty( $image_opt_meta['sum']['opt_size'] ) ? (int) $image_opt_meta['sum']['opt_size'] : 0;
                    }

                    if(!empty($meta['sizes']))
                    {
                        foreach ( $meta['sizes'] as $size_key => $size_data )
                        {
                            $filename = path_join(dirname($file_path), $size_data['file']);

                            if (!file_exists($filename))
                            {
                                $not_found[$image_id][$size_key]=$filename;
                                continue;
                            }

                            if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
                            {
                                if($options['skip_size'][$size_key])
                                    continue;
                            }

                            $optimize_data['total_images']++;

                            if(isset($image_opt_meta['size'][$size_key]))
                            {
                                if(isset($image_opt_meta['size'][$size_key]['opt_status'])&&$image_opt_meta['size'][$size_key]['opt_status']==1)
                                {
                                    $optimize_data['optimized_images']++;
                                }

                                if(isset($image_opt_meta['size'][$size_key]['webp_status'])&&$image_opt_meta['size'][$size_key]['webp_status']==1)
                                {
                                    $optimize_data['webp']++;
                                }
                                else
                                {
                                    $webp_file =$filename.'.webp';
                                    if (file_exists($webp_file))
                                    {
                                        $optimize_data['webp']++;
                                    }
                                }
                            }
                        }
                    }
                }
                $total_images=null;
                $current_time=time();
                if($current_time - $start_time >= 21)
                {
                    $overview_array['webp']=isset($optimize_data['webp'])?$optimize_data['webp']:0;
                    $overview_array['unoptimized']=isset($optimize_data['unoptimized'])?$optimize_data['unoptimized']:0;
                    $overview_array['optimized_images']=isset($optimize_data['optimized_images'])?$optimize_data['optimized_images']:0;
                    $overview_array['original_size']=isset($optimize_data['original_size'])?$optimize_data['original_size']:0;
                    $overview_array['optimized_size']=isset($optimize_data['optimized_size'])?$optimize_data['optimized_size']:0;
                    $overview_array['optimized_percent']=isset($optimize_data['optimized_percent'])?$optimize_data['optimized_percent']:0;
                    $overview_array['total_images']=isset($optimize_data['total_images'])?$optimize_data['total_images']:0;
                    $overview_array['offset']=$offset;
                    update_option('wpvivid_get_get_overview_ex', $overview_array,'no');
                    $ret['result']='success';
                    $ret['continue']=1;
                    echo json_encode($ret);
                    die();
                }
            }
        }

        if($optimize_data['optimized_size']>0&&$optimize_data['original_size']>0)
        {
            $optimize_data['optimized_percent']=intval(100-($optimize_data['optimized_size']/$optimize_data['original_size'])*100);
        }

        $optimize_data['unoptimized']=max($optimize_data['total_images']-$optimize_data['optimized_images'],0);

        $optimize_data['original_size_format']=size_format($optimize_data['original_size'],2);
        $optimize_data['optimized_size_format']=size_format($optimize_data['optimized_size'],2);
        $optimize_data['optimized_percent_format']= $optimize_data['optimized_percent'].'%';
        $ret['status']=$optimize_data;
        $ret['result']='success';
        $ret['continue']=0;
        update_option('wpvivid_imgoptim_overview',$optimize_data,'no');
        echo json_encode($ret);
        die();
    }

    public function hide_imgoptm_not_exist_notice()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');
        update_option('wpvivid_hide_imgoptm_not_exist_notice', '1','no');
        update_option('wpvivid_imgoptm_file_not_exist', array(),'no');
        $ret['result']=WPVIVID_PRO_SUCCESS;
        echo json_encode($ret);
        die();
    }

    public function get_months_dropdown_results()
    {
        global $wpdb;
        $months = $wpdb->get_results(
            $wpdb->prepare(
                "
			SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
			FROM $wpdb->posts
			WHERE post_type = %s
			ORDER BY post_date DESC
		",
                'attachment'
            )
        );
        return $months;
    }

    public function optimized_images()
    {
        global $wp_locale;
        $months=$this->get_months_dropdown_results();
        $m =0;
        ?>
        <div style="" id="wpvivid_optimized_imgae_list">
            <div class="wp-filter" style="margin-bottom:0.5em;margin-top:0;">
                <div class="filter-items view-switch">
                    <div class="actions">
                        <label for="wpvivid-optimized-filter-by-date" class="screen-reader-text">Filter by date</label>
                        <select name="m" id="wpvivid-optimized-filter-by-date">
                            <option<?php selected( $m, 0 ); ?> value="0"><?php _e( 'All dates' ); ?></option>
                            <?php
                            foreach ( $months as $arc_row )
                            {
                                if ( 0 == $arc_row->year )
                                {
                                    continue;
                                }

                                $month = zeroise( $arc_row->month, 2 );
                                $year  = $arc_row->year;

                                printf(
                                    "<option %s value='%s'>%s</option>\n",
                                    selected( $m, $year . $month, false ),
                                    esc_attr( $arc_row->year . $month ),
                                    /* translators: 1: month name, 2: 4-digit year */
                                    sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
                                );
                            }
                            ?>
                        </select>
                        <input type="submit" name="filter_action" id="wpvivid_image_date_filter" class="button" value="Filter">
                    </div>
                </div>

                <div class="search-form">
                    <label for="media-search-input" class="screen-reader-text">Search Media</label>
                    <input type="search" placeholder="Search media items..." id="wpvivid_image_search" class="search" name="s" value="">
                </div>
            </div>
            <div id="wpvivid_optimized_imgae_list_body">
                <?php
                if(is_multisite())
                {
                    $result=$this->get_mu_optimized_list();
                    $list = new WPvivid_Optimized_Image_MU_List_Ex();
                    $list->set_list($result);
                    $list->prepare_items();
                    $list ->display();
                }
                else
                {
                    $result=$this->get_optimized_list();
                    $count=$this->get_optimized_list_count();
                    $list = new WPvivid_Optimized_Image_List_Ex();
                    $list->set_list($result,1,$count);
                    $list->prepare_items();
                    $list ->display();
                }

                ?>
            </div>
        </div>
        <script>
            jQuery('#wpvivid_image_date_filter').click(function()
            {
                var date=jQuery('#wpvivid-optimized-filter-by-date').val();
                var search=jQuery('#wpvivid_image_search').val();
                var ajax_data = {
                    'action': 'wpvivid_get_opt_list_ex',
                    'date':date,
                    'search':search
                };

                wpvivid_post_request_addon(ajax_data, function (data)
                {
                    jQuery('#wpvivid_optimized_imgae_list_body').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_optimized_imgae_list_body').html(jsonarray.html);
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
                    var error_message = wpvivid_output_ajaxerror('get list', textStatus, errorThrown);
                    alert(error_message);
                });
            });

            jQuery('#wpvivid_optimized_imgae_list').on("click",'.top-action',function()
            {
                var selected=jQuery('#wpvivid_image_opt_bulk_top_action').val();
                if(selected=='wpvivid_restore_selected_image')
                {
                    wpvivid_restore_selected_image();
                }
                else if(selected=='wpvivid_restore_all_image')
                {
                    wpvivid_restore_all_image();
                }
                else if(selected=='wpvivid_delete_selected_webp')
                {
                    wpvivid_delete_selected_image();
                }
                else if(selected=='wpvivid_delete_all_webp')
                {
                    wpvivid_delete_all_image();
                }
                else if(selected=='wpvivid_mu_restore_selected_image')
                {
                    wpvivid_mu_restore_selected_image();
                }
                else if(selected=='wpvivid_mu_restore_all_image')
                {
                    wpvivid_mu_restore_all_image();
                }
                else if(selected=='wpvivid_mu_delete_selected_webp')
                {
                    wpvivid_mu_delete_selected_image();
                }
                else if(selected=='wpvivid_mu_delete_all_webp')
                {
                    wpvivid_mu_delete_all_image();
                }
            });

            jQuery('#wpvivid_optimized_imgae_list').on("click",'.bottom-action',function()
            {
                var selected=jQuery('#wpvivid_image_opt_bulk_bottom_action').val();
                if(selected=='wpvivid_restore_selected_image')
                {
                    wpvivid_restore_selected_image();
                }
                else if(selected=='wpvivid_restore_all_image')
                {
                    wpvivid_restore_all_image();
                }
                else if(selected=='wpvivid_delete_selected_webp')
                {
                    wpvivid_delete_selected_image();
                }
                else if(selected=='wpvivid_delete_all_webp')
                {
                    wpvivid_delete_all_image();
                }
                else if(selected=='wpvivid_mu_restore_selected_image')
                {
                    wpvivid_mu_restore_selected_image();
                }
                else if(selected=='wpvivid_mu_restore_all_image')
                {
                    wpvivid_mu_restore_all_image();
                }
                else if(selected=='wpvivid_mu_delete_selected_webp')
                {
                    wpvivid_mu_delete_selected_image();
                }
                else if(selected=='wpvivid_mu_delete_all_webp')
                {
                    wpvivid_mu_delete_all_image();
                }
            });

            function wpvivid_restore_selected_image()
            {
                var json = {};
                json['selected']=Array();
                jQuery('input[name=opt][type=checkbox]').each(function(index, value)
                {
                    if(jQuery(value).prop('checked'))
                    {
                        json['selected'].push(jQuery(value).val())
                    }
                });

                if(json['selected'].length>0)
                {
                    var selected= JSON.stringify(json);
                }
                else
                {
                    alert('Please select at least one item to perform this action on.');
                    return;
                }

                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_restore_selected_opt_image_ex',
                    'selected':selected
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            alert('Image(s) restored successfully.');
                            location.reload();
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_mu_restore_selected_image()
            {
                var json = {};
                json['selected']=Array();
                jQuery('input[name=opt][type=checkbox]').each(function(index, value)
                {
                    if(jQuery(value).prop('checked'))
                    {
                        var image={};
                        image['image_id']=jQuery(value).data("id");
                        image['site_id']=jQuery(value).data("id");
                        json['selected'].push(image);
                    }
                });

                if(json['selected'].length>0)
                {
                    var selected= JSON.stringify(json);
                }
                else
                {
                    alert('Please select at least one item to perform this action on.');
                    return;
                }

                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_mu_restore_selected_opt_image_ex',
                    'selected':selected
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            alert('Image(s) restored successfully.');
                            //location.reload();
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_restore_all_image()
            {
                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_restore_all_opt_image_ex'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            if(jsonarray.continue)
                            {
                                wpvivid_restore_all_image();
                            }
                            else
                            {
                                alert('Image(s) restored successfully.');
                                location.reload();
                            }
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_mu_restore_all_image()
            {
                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_mu_restore_all_opt_image_ex'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            alert('Image(s) restored successfully.');
                            location.reload();
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_delete_selected_image()
            {
                var json = {};
                json['selected']=Array();
                jQuery('input[name=opt][type=checkbox]').each(function(index, value)
                {
                    if(jQuery(value).prop('checked'))
                    {
                        json['selected'].push(jQuery(value).val())
                    }
                });

                if(json['selected'].length>0)
                {
                    var selected= JSON.stringify(json);
                }
                else
                {
                    alert('Please select at least one item to perform this action on.');
                    return;
                }

                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_delete_selected_webp_image',
                    'selected':selected
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            alert('Delete webp images success');
                            location.reload();
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_mu_delete_selected_image()
            {
                var json = {};
                json['selected']=Array();
                jQuery('input[name=opt][type=checkbox]').each(function(index, value)
                {
                    if(jQuery(value).prop('checked'))
                    {
                        var image={};
                        image['image_id']=jQuery(value).data("id");
                        image['site_id']=jQuery(value).data("id");
                        json['selected'].push(image);
                    }
                });

                if(json['selected'].length>0)
                {
                    var selected= JSON.stringify(json);
                }
                else
                {
                    alert('Please select at least one item to perform this action on.');
                    return;
                }

                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_mu_delete_selected_webp_image',
                    'selected':selected
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            alert('Delete webp images success');
                            location.reload();
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_delete_all_image()
            {
                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_delete_all_webp_image'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            alert('Delete webp images success');
                            location.reload();
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_mu_delete_all_image()
            {
                jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_mu_delete_all_webp_image'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            alert('Delete webp images success');
                            location.reload();
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
                    jQuery('#wpvivid_optimized_imgae_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_get_opt_list(page)
            {
                var date=jQuery('#wpvivid-optimized-filter-by-date').val();
                var search=jQuery('#wpvivid_image_search').val();
                var ajax_data = {
                    'action': 'wpvivid_get_opt_list_ex',
                    'date':date,
                    'search':search,
                    'page':page,
                };

                wpvivid_post_request_addon(ajax_data, function (data)
                {
                    jQuery('#wpvivid_optimized_imgae_list_body').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_optimized_imgae_list_body').html(jsonarray.html);
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
                    var error_message = wpvivid_output_ajaxerror('get list', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            jQuery('#wpvivid_optimized_imgae_list').on("click",'.first-page',function()
            {
                wpvivid_get_opt_list('first');
            });

            jQuery('#wpvivid_optimized_imgae_list').on("click",'.prev-page',function()
            {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_opt_list(page-1);
            });

            jQuery('#wpvivid_optimized_imgae_list').on("click",'.next-page',function()
            {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_opt_list(page+1);
            });

            jQuery('#wpvivid_optimized_imgae_list').on("click",'.last-page',function()
            {
                wpvivid_get_opt_list('last');
            });

            jQuery('#wpvivid_optimized_imgae_list').on("keypress", '.current-page', function()
            {
                if(event.keyCode === 13){
                    var page = jQuery(this).val();
                    wpvivid_get_opt_list(page);
                }
            });
        </script>
        <?php
    }

    public function get_opt_list()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            if(isset($_POST['date']))
            {
                $date=$_POST['date'];
            }
            else
            {
                $date='';
            }

            if(isset($_POST['search']))
            {
                $search=$_POST['search'];
            }
            else
            {
                $search='';
            }
            if(is_multisite())
            {
                $result=$this->get_mu_optimized_list($date,$search);

                $list = new WPvivid_Optimized_Image_MU_List_Ex();
                if(isset($_POST['page']))
                {
                    $page=sanitize_key($_POST['page']);
                    $list->set_list($result,$page);
                }
                else
                {
                    $list->set_list($result);
                }
                $list->prepare_items();
                ob_start();
                $list->display();
                $html = ob_get_clean();
            }
            else
            {

                $list = new WPvivid_Optimized_Image_List_Ex();
                if(isset($_POST['page']))
                {
                    $page=sanitize_key($_POST['page']);
                    $result=$this->get_optimized_list($date,$search,20,$page);
                    $count=$this->get_optimized_list_count($date,$search);
                    $list->set_list($result,$page,$count);
                }
                else
                {
                    $result=$this->get_optimized_list($date,$search,20);
                    $count=$this->get_optimized_list_count($date,$search);
                    $list->set_list($result,1,$count);
                }
                $list->prepare_items();
                ob_start();
                $list->display();
                $html = ob_get_clean();
            }


            $ret['result']='success';
            $ret['html']=$html;
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

    public function get_mu_optimized_list($date='',$search='')
    {
        global $wpdb;
        $blogs_ids=get_sites();
        $list=array();
        $index=0;
        foreach( $blogs_ids as $site )
        {
            $site_id = get_object_vars($site)["blog_id"];

            switch_to_blog( $site_id );

            $query="SELECT `ID` FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id=$wpdb->posts.ID AND $wpdb->postmeta.meta_key='wpvivid_image_optimize_meta' ";

            if(empty($date)&&empty($search))
            {
                $where='';
            }
            else
            {
                $where='WHERE ';
            }

            if ( !empty($date) )
            {
                $where .= " YEAR({$wpdb->posts}.post_date)=" . substr($date, 0, 4 );
                if ( strlen( $date ) > 5 ) {
                    $where .= " AND MONTH({$wpdb->posts}.post_date)=" . substr( $date, 4, 2 );
                }
                if ( strlen( $date ) > 7 ) {
                    $where .= " AND DAYOFMONTH({$wpdb->posts}.post_date)=" . substr( $date, 6, 2 );
                }
                if ( strlen( $date ) > 9 ) {
                    $where .= " AND HOUR({$wpdb->posts}.post_date)=" . substr( $date, 8, 2 );
                }
                if ( strlen( $date ) > 11 ) {
                    $where .= " AND MINUTE({$wpdb->posts}.post_date)=" . substr( $date, 10, 2 );
                }
                if ( strlen( $date ) > 13 ) {
                    $where .= " AND SECOND({$wpdb->posts}.post_date)=" . substr( $date, 12, 2 );
                }
            }

            if(!empty($search))
            {
                if(!empty($data))
                {
                    $where .= " AND ";
                }
                $where.=" post_title LIKE '%".$search."%' ";
            }

            $query=$query.$where.' GROUP BY ID';

            $ids = $wpdb->get_col( $query );

            if(count($ids)>0)
            {
                foreach ( $ids as $id )
                {
                    $meta=get_post_meta($id,'wpvivid_image_optimize_meta',true);

                    if($this->has_optimized_image($meta))
                    {
                        $meta['image_id']=$id;
                        $meta['site_id']=$site_id;
                        $index++;
                        $list[$index]=$meta;

                    }

                }
            }

            restore_current_blog();
        }

        if(empty($list))
        {
            return $list;
        }

        usort($list, function ($a, $b)
        {
            if(isset($a['last_update_time'])&&isset($b['last_update_time']))
            {
                if($a['last_update_time']>$b['last_update_time'])
                    return -1;
                else if($a['last_update_time']<$b['last_update_time'])
                    return 1;
                else
                    return 0;
            }
            else if(isset($a['last_update_time'])&&!isset($b['last_update_time']))
            {
                return -1;
            }
            else if(!isset($a['last_update_time'])&&isset($b['last_update_time']))
            {
                return 1;
            }
            else
            {
                return 0;
            }
        });

        return $list;
    }

    public function get_optimized_list($date='',$search='',$pre_page=20,$page=0)
    {
        global $wpdb;
        $limit_count=$pre_page;

        if($page==0)
        {
            $limit = " LIMIT {$limit_count}";
        }
        else
        {
            $start=$pre_page*($page-1);
            $limit = " LIMIT {$limit_count} OFFSET {$start}";
        }


        $query="SELECT `ID` FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id=$wpdb->posts.ID AND $wpdb->postmeta.meta_key='wpvivid_image_optimize_meta' ";

        if(empty($date)&&empty($search))
        {
            $where='';
        }
        else
        {
            $where='WHERE ';
        }

        if ( !empty($date) )
        {
            $where .= " YEAR({$wpdb->posts}.post_date)=" . substr($date, 0, 4 );
            if ( strlen( $date ) > 5 ) {
                $where .= " AND MONTH({$wpdb->posts}.post_date)=" . substr( $date, 4, 2 );
            }
            if ( strlen( $date ) > 7 ) {
                $where .= " AND DAYOFMONTH({$wpdb->posts}.post_date)=" . substr( $date, 6, 2 );
            }
            if ( strlen( $date ) > 9 ) {
                $where .= " AND HOUR({$wpdb->posts}.post_date)=" . substr( $date, 8, 2 );
            }
            if ( strlen( $date ) > 11 ) {
                $where .= " AND MINUTE({$wpdb->posts}.post_date)=" . substr( $date, 10, 2 );
            }
            if ( strlen( $date ) > 13 ) {
                $where .= " AND SECOND({$wpdb->posts}.post_date)=" . substr( $date, 12, 2 );
            }
        }

        if(!empty($search))
        {
            if(!empty($data))
            {
                $where .= " AND ";
            }
            $where.=" post_title LIKE '%".$search."%' ";
        }

        $query=$query.$where.' GROUP BY ID '.$limit;

        $ids = $wpdb->get_col( $query );
        if(count($ids)>0)
        {
            $list=array();
            foreach ( $ids as $id )
            {
                $meta=get_post_meta($id,'wpvivid_image_optimize_meta',true);

                if($this->has_optimized_image($meta))
                {
                    $meta['id']=$id;
                    $list[$id]=$meta;
                }

            }

            /*
            usort($list, function ($a, $b)
            {
                if(isset($a['last_update_time'])&&isset($b['last_update_time']))
                {
                    if($a['last_update_time']>$b['last_update_time'])
                        return -1;
                    else if($a['last_update_time']<$b['last_update_time'])
                        return 1;
                    else
                        return 0;
                }
                else if(isset($a['last_update_time'])&&!isset($b['last_update_time']))
                {
                    return -1;
                }
                else if(!isset($a['last_update_time'])&&isset($b['last_update_time']))
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            });
            */
            return $list;
        }
        else
        {
            return array();
        }
    }

    public function get_optimized_list_ex($date='',$search='')
    {
        global $wpdb;

        $query="SELECT `ID` FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id=$wpdb->posts.ID AND $wpdb->postmeta.meta_key='wpvivid_image_optimize_meta' ";

        if(empty($date)&&empty($search))
        {
            $where='';
        }
        else
        {
            $where='WHERE ';
        }

        if ( !empty($date) )
        {
            $where .= " YEAR({$wpdb->posts}.post_date)=" . substr($date, 0, 4 );
            if ( strlen( $date ) > 5 ) {
                $where .= " AND MONTH({$wpdb->posts}.post_date)=" . substr( $date, 4, 2 );
            }
            if ( strlen( $date ) > 7 ) {
                $where .= " AND DAYOFMONTH({$wpdb->posts}.post_date)=" . substr( $date, 6, 2 );
            }
            if ( strlen( $date ) > 9 ) {
                $where .= " AND HOUR({$wpdb->posts}.post_date)=" . substr( $date, 8, 2 );
            }
            if ( strlen( $date ) > 11 ) {
                $where .= " AND MINUTE({$wpdb->posts}.post_date)=" . substr( $date, 10, 2 );
            }
            if ( strlen( $date ) > 13 ) {
                $where .= " AND SECOND({$wpdb->posts}.post_date)=" . substr( $date, 12, 2 );
            }
        }

        if(!empty($search))
        {
            if(!empty($data))
            {
                $where .= " AND ";
            }
            $where.=" post_title LIKE '%".$search."%' ";
        }

        $query=$query.$where.' GROUP BY ID ';

        $ids = $wpdb->get_col( $query );

        if(count($ids)>0)
        {
            $list=array();
            foreach ( $ids as $id )
            {
                $meta=get_post_meta($id,'wpvivid_image_optimize_meta',true);
                if($this->has_optimized_image($meta))
                {
                    $meta['id']=$id;
                    $list[$id]=$meta;
                }

            }

            return $list;
        }
        else
        {
            return array();
        }
    }

    public function get_optimized_list_count($date='',$search='')
    {
        global $wpdb;

        $query="SELECT `ID` FROM $wpdb->posts INNER JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id=$wpdb->posts.ID AND $wpdb->postmeta.meta_key='wpvivid_image_optimize_meta' ";

        if(empty($date)&&empty($search))
        {
            $where='';
        }
        else
        {
            $where='WHERE ';
        }

        if ( !empty($date) )
        {
            $where .= " YEAR({$wpdb->posts}.post_date)=" . substr($date, 0, 4 );
            if ( strlen( $date ) > 5 ) {
                $where .= " AND MONTH({$wpdb->posts}.post_date)=" . substr( $date, 4, 2 );
            }
            if ( strlen( $date ) > 7 ) {
                $where .= " AND DAYOFMONTH({$wpdb->posts}.post_date)=" . substr( $date, 6, 2 );
            }
            if ( strlen( $date ) > 9 ) {
                $where .= " AND HOUR({$wpdb->posts}.post_date)=" . substr( $date, 8, 2 );
            }
            if ( strlen( $date ) > 11 ) {
                $where .= " AND MINUTE({$wpdb->posts}.post_date)=" . substr( $date, 10, 2 );
            }
            if ( strlen( $date ) > 13 ) {
                $where .= " AND SECOND({$wpdb->posts}.post_date)=" . substr( $date, 12, 2 );
            }
        }

        if(!empty($search))
        {
            if(!empty($data))
            {
                $where .= " AND ";
            }
            $where.=" post_title LIKE '%".$search."%' ";
        }

        $query=$query.$where.' GROUP BY ID ';

        $ids = $wpdb->get_col( $query );
        return count($ids);
    }

    public function has_optimized_image($meta)
    {
        if(empty($meta))
        {
            return false;
        }

        $options=get_option('wpvivid_optimization_options',array());

        $status=false;
        foreach ($meta['size'] as $size_key=>$size)
        {
            if(isset($options['skip_size'])&&isset($options['skip_size'][$size_key]))
            {
                if($options['skip_size'][$size_key])
                    continue;
            }
            if($size['opt_status']==1)
            {
                $status=true;
                break;
            }
        }

        if($status)
        {
            if($meta['sum']['og_size']==0)
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

    public function delete_selected_webp_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['selected'])||!is_string($_POST['selected']))
        {
            die();
        }

        try
        {
            $json = sanitize_text_field($_POST['selected']);
            $json = stripslashes($json);
            $json = json_decode($json, true);

            $ids=$json['selected'];

            foreach ($ids as $id)
            {
                $this->delete_webp_image($id);
            }

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

    public function delete_all_webp_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            ini_set('memory_limit','512M');
            $list=$this->get_optimized_list();
            if(!empty($list))
            {
                foreach ($list as $meta)
                {
                    $this->delete_webp_image($meta['id']);
                }
            }

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

    public function delete_webp_image($image_id)
    {
        $image_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
        $meta = wp_get_attachment_metadata( $image_id, true );
        $file_path = get_attached_file( $image_id );

        if(empty($image_meta))
            return false;

        foreach ( $image_meta['size'] as $size_key => $size_data )
        {
            if ($size_key == 'og') {
                $filename = $file_path;
            } else {
                if (!empty($meta['sizes']) && isset($meta['sizes'][$size_key])) {
                    $filename = path_join(dirname($file_path), $meta['sizes'][$size_key]['file']);
                } else {
                    continue;
                }
            }

            $webp_file = $filename . '.webp';
            if (file_exists($filename))
            {
                @unlink($webp_file);
                $image_meta['size'][$size_key]['webp_status'] = 0;
            }
        }
        update_post_meta($image_id,'wpvivid_image_optimize_meta',$image_meta);
        return true;
    }

    public function delete_mu_webp_image($site_id,$image_id)
    {
        switch_to_blog( $site_id );
        $image_meta = get_post_meta( $image_id, 'wpvivid_image_optimize_meta', true );
        $meta = wp_get_attachment_metadata( $image_id, true );
        $file_path = get_attached_file( $image_id );

        if(empty($image_meta))
            return false;

        foreach ( $image_meta['size'] as $size_key => $size_data )
        {
            if ($size_key == 'og') {
                $filename = $file_path;
            } else {
                if (!empty($meta['sizes']) && isset($meta['sizes'][$size_key])) {
                    $filename = path_join(dirname($file_path), $meta['sizes'][$size_key]['file']);
                } else {
                    continue;
                }
            }

            $webp_file = $filename . '.webp';
            if (file_exists($filename))
            {
                @unlink($webp_file);
                $image_meta['size'][$size_key]['webp_status'] = 0;
            }
        }
        update_post_meta($image_id,'wpvivid_image_optimize_meta',$image_meta);
        restore_current_blog();
        return true;
    }

    public function init_opt_task()
    {
        delete_option('wpvivid_need_increase_memory');
        $this->end_shutdown_function = false;
        register_shutdown_function(array($this,'deal_shutdown_error'));
        try {
            global $wpvivid_backup_pro;
            $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

            $options=get_option('wpvivid_optimization_options',array());
            $memory_limit=isset($options['image_optimization_memory_limit'])?$options['image_optimization_memory_limit']:256;

            $memory_limit=max(512,intval($memory_limit));
            ini_set('memory_limit',$memory_limit.'M');

            $options=get_option('wpvivid_optimization_options',array());

            set_time_limit(180);
            $task=new WPvivid_ImgOptim_Task_Ex();

            if(isset($_POST['resize']))
            {
                if($_POST['resize']=='1')
                    $options['only_resize']=1;
                else
                    $options['only_resize']=0;
            }

            $need_optimize_array=array();
            $need_optimize_array['images']=array();
            $need_optimize_array['offset']=0;
            update_option('wpvivid_get_need_optimize_images_ex', $need_optimize_array,'no');

            $ret=$task->init_task($options);
            if(isset($ret['continue']) && $ret['continue'] === 1)
            {
                $ret['result']='success';
                $ret['continue']=1;
                echo json_encode($ret);
            }
            else
            {
                $this->flush($ret);

                if($ret['result']=='success')
                {
                    $task->do_optimize_image();
                }
            }
            $this->end_shutdown_function=true;
            //$ret['result']='success';
            //echo json_encode($ret);
        }
        catch (Exception $e)
        {
            $ret['result']='error';
            $ret['error']=$e->getMessage();
            echo json_encode($ret);
        }


        die();
    }

    public function start_opt_task()
    {
        delete_option('wpvivid_need_increase_memory');
        $this->end_shutdown_function = false;
        register_shutdown_function(array($this,'deal_shutdown_error'));
        try {
            global $wpvivid_backup_pro;
            $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

            $options=get_option('wpvivid_optimization_options',array());

            set_time_limit(180);
            $task=new WPvivid_ImgOptim_Task_Ex();

            if(isset($_POST['resize']))
            {
                if($_POST['resize']=='1')
                    $options['only_resize']=1;
                else
                    $options['only_resize']=0;
            }

            $ret=$task->init_task($options);
            if(isset($ret['continue']) && $ret['continue'] === 1)
            {
                $ret['result']='success';
                $ret['continue']=1;
                echo json_encode($ret);
            }
            else
            {
                $this->flush($ret);

                if($ret['result']=='success')
                {
                    $task->do_optimize_image();
                }
            }

            $this->end_shutdown_function=true;
            //$ret['result']='success';
            //echo json_encode($ret);
        }
        catch (Exception $e)
        {
            $ret['result']='error';
            $ret['error']=$e->getMessage();
            echo json_encode($ret);
        }


        die();
    }

    public function opt_image()
    {
        $this->end_shutdown_function = false;
        register_shutdown_function(array($this,'deal_shutdown_error'));
        try {
            global $wpvivid_backup_pro;
            $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

            $options=get_option('wpvivid_optimization_options',array());
            $memory_limit=isset($options['image_optimization_memory_limit'])?$options['image_optimization_memory_limit']:256;

            $memory_limit=max(256,intval($memory_limit));
            ini_set('memory_limit',$memory_limit.'M');

            set_time_limit(180);

            if(is_multisite())
            {
                if(!is_main_site())
                {
                    switch_to_blog(get_main_site_id());
                }
            }

            $task=new WPvivid_ImgOptim_Task_Ex();

            $ret=$task->get_task_status();

            //$this->flush($ret);
            if($ret['result']=='success'&&$ret['status']=='completed')
            {
                $task->do_optimize_image();
                echo json_encode($ret);
            }
            else
            {
                echo json_encode($ret);
            }

            $this->end_shutdown_function=true;

            //$ret['result']='success';
            //echo json_encode($ret);
        }
        catch (Exception $e)
        {
            $ret['result']='error';
            $ret['error']=$e->getMessage();
            echo json_encode($ret);
        }


        die();
    }

    public function deal_shutdown_error(){
        if($this->end_shutdown_function===false)
        {
            $last_error = error_get_last();

            if (!is_null($last_error))
            {
                if(preg_match('/Allowed memory size of.*$/', $last_error['message']))
                {
                    $options=get_option('wpvivid_optimization_options',array());
                    $memory_limit=isset($options['image_optimization_memory_limit'])?$options['image_optimization_memory_limit']:256;
                    $memory_limit = $memory_limit * 2;
                    $options['image_optimization_memory_limit']=$memory_limit;
                    update_option('wpvivid_optimization_options', $options,'no');

                    update_option('wpvivid_need_increase_memory', true, 'no');

                    $error = $last_error;
                    $message= 'type: '. $error['type'] . ', ' . $error['message'] . ' file:' . $error['file'] . ' line:' . $error['line'];

                    $ret['result']='error';
                    $ret['error']=$message;
                    $ret['retry']=true;
                }
                else
                {
                    $error = $last_error;
                    $message= 'type: '. $error['type'] . ', ' . $error['message'] . ' file:' . $error['file'] . ' line:' . $error['line'];

                    $ret['result']='error';
                    $ret['error']=$message;
                    $ret['retry']=false;
                }
            }
            else {
                $error = false;
                $message= 'type: '. $error['type'] . ', ' . $error['message'] . ' file:' . $error['file'] . ' line:' . $error['line'];

                $ret['result']='error';
                $ret['error']=$message;
                $ret['retry']=false;
            }


            echo json_encode($ret);
            die();
        }
    }

    public function get_opt_progress()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        $task=new WPvivid_ImgOptim_Task_Ex();

        $result=$task->get_task_progress();

        if($result['is_schedule'])
        {
            $Processing='Processing (schedule):';
        }
        else
        {
            $Processing='Processing (real-time):';
        }
        $result['progress_html']='
                <p>
                    <span>
                        <strong>'.__('Bulk Optimization Progress','wpvivid-imgoptim').'</strong>
                    </span>
                    <span style="float:right;">
                        <span class="wpvivid-rectangle wpvivid-green">'.__('Optimized','wpvivid-imgoptim').'</span>
                        <span class="wpvivid-rectangle wpvivid-grey">'.__('Un-optimized','wpvivid-imgoptim').'</span>
                    </span>
                </p>            
                <p>
                    <span class="wpvivid-span-progress">
                        <span class="wpvivid-span-processed-progress" style="width:'.$result['percent'].'%;">'.$result['percent'].'% '.__('completed','wpvivid-imgoptim').'</span>
                    </span>
                </p>
                <p>
                    <span class="dashicons dashicons-flag wpvivid-dashicons-green"></span><span><strong>'.$Processing.' </strong></span>
					<span style="color:#999;">'.$result['log'].'</span>
					<span title="View logs"><a id="wpvivid_image_open_log" href="#">logs</a></span>
				</p>
                ';

        echo json_encode($result);

        die();
    }

    public function get_need_increase_memory_retry()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        $ret['result']='success';

        $need_retry = get_option('wpvivid_need_increase_memory', false);
        if($need_retry)
        {
            $ret['retry']=true;
        }
        else
        {
            $ret['retry']=false;
        }

        echo json_encode($ret);
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

    public function cancel_opt_task()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        set_time_limit(180);

        $task=new WPvivid_ImgOptim_Task_Ex();

        $task->cancel();

        die();
    }

    public function restore_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['selected'])||!is_string($_POST['selected']))
        {
            die();
        }

        try
        {
            $json = sanitize_text_field($_POST['selected']);
            $json = stripslashes($json);
            $json = json_decode($json, true);

            $ids=$json['selected'];

            $task=new WPvivid_ImgOptim_Task_Ex();

            foreach ($ids as $id)
            {
                $task->restore_image($id);
            }

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

    public function restore_all_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            ini_set('memory_limit','512M');

            $task=new WPvivid_ImgOptim_Task_Ex();

            $list=$this->get_optimized_list_ex();
            if(!empty($list))
            {
                $ret['continue']=1;
                foreach ($list as $meta)
                {
                    $task->restore_image($meta['id']);
                }
            }
            else
            {
                $ret['continue']=0;
            }

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

    public function restore_mu_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['selected'])||!is_string($_POST['selected']))
        {
            die();
        }

        try
        {
            $json = sanitize_text_field($_POST['selected']);
            $json = stripslashes($json);
            $json = json_decode($json, true);

            $image_ids=$json['selected'];

            $mu=new WPvivid_MU_ImgOptim(false);

            foreach ($image_ids as $image)
            {
                $image_id=$image['image_id'];
                $site_id=$image['site_id'];

                $mu->restore_image($site_id,$image_id);
            }

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

    public function restore_mu_all_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            $mu=new WPvivid_MU_ImgOptim(false);

            $list=$this->get_mu_optimized_list();
            if(!empty($list))
            {
                foreach ($list as $meta)
                {
                    $mu->restore_image($meta['site_id'],$meta['image_id']);
                }
            }

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

    public function delete_mu_selected_webp_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['selected'])||!is_string($_POST['selected']))
        {
            die();
        }

        try
        {
            $json = sanitize_text_field($_POST['selected']);
            $json = stripslashes($json);
            $json = json_decode($json, true);

            $image_ids=$json['selected'];

            foreach ($image_ids as $image)
            {
                $image_id=$image['image_id'];
                $site_id=$image['site_id'];
                $this->delete_mu_webp_image($site_id,$image_id);
            }

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

    public function delete_mu_all_webp_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            $list=$this->get_mu_optimized_list();
            if(!empty($list))
            {
                foreach ($list as $meta)
                {
                    $this->delete_mu_webp_image($meta['site_id'],$meta['image_id']);
                }
            }

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

    public function folders_delete_selected_webp_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['selected'])||!is_string($_POST['selected']))
        {
            die();
        }

        try
        {
            $json = sanitize_text_field($_POST['selected']);
            $json = stripslashes($json);
            $json = json_decode($json, true);

            $ids=$json['selected'];

            foreach ($ids as $id)
            {
                $this->folders_delete_webp_image($id);
            }

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

    public function folders_delete_all_webp_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            $this->custom_folders_display=new WPvivid_ImgOptim_Custom_Folders_Display_Addon();
            $list=$this->custom_folders_display->get_folders_optimized_list();
            if(!empty($list))
            {
                foreach ($list as $meta)
                {
                    $this->delete_webp_image($meta['id']);
                }
            }

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

    public function folders_delete_webp_image($image_id)
    {
        global $wpdb;

        $query="SELECT * FROM {$wpdb->prefix}wpvivid_files_opt_meta WHERE id=$image_id";
        $task=new WPvivid_ImgOptim_Task_Ex();
        $results = $wpdb->get_results($query,ARRAY_A);
        if (empty($results))
        {
            return false;
        }
        else
        {
            foreach ($results as $image_meta)
            {
                $path=$image_meta['path'];
                $meta=unserialize($image_meta['meta']);
                $webp_file = $path . '.webp';
                if (file_exists($webp_file))
                {
                    @unlink($webp_file);
                    $meta['webp_status'] = 0;
                }
                $task->update_image_opt_meta($path,$meta);
            }
        }

        return true;
    }

    public function folders_restore_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        if(!isset($_POST['selected'])||!is_string($_POST['selected']))
        {
            die();
        }

        try
        {
            $json = sanitize_text_field($_POST['selected']);
            $json = stripslashes($json);
            $json = json_decode($json, true);

            $ids=$json['selected'];

            $task=new WPvivid_ImgOptim_Task_Ex();

            foreach ($ids as $id)
            {
                $this->folders_delete_webp_image($id);
                $task->restore_folders_image($id);
            }

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

    public function folders_restore_all_image()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            $task=new WPvivid_ImgOptim_Task_Ex();
            $this->custom_folders_display=new WPvivid_ImgOptim_Custom_Folders_Display_Addon();
            $list= $this->custom_folders_display->get_folders_optimized_list();
            if(!empty($list))
            {
                foreach ($list as $meta)
                {
                    $this->folders_delete_webp_image($meta['id']);
                    $task->restore_folders_image($meta['id']);
                }
            }

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

    public function folders_get_opt_list()
    {
        global $wpvivid_backup_pro;
        $wpvivid_backup_pro->ajax_check_security('wpvivid-can-use-image-optimization');

        try
        {
            if(isset($_POST['folder'])&&!empty($_POST['folder'])&&$_POST['folder']!=0)
            {
                $folder=stripslashes($_POST['folder']);
            }
            else
            {
                $folder='';
            }
            $this->custom_folders_display=new WPvivid_ImgOptim_Custom_Folders_Display_Addon();
            $result=$this->custom_folders_display->get_folders_optimized_list($folder);

            $list = new WPvivid_Folders_Optimized_Image_List_Ex();
            if(isset($_POST['page']))
            {
                $page=sanitize_key($_POST['page']);
                $list->set_list($result,$page);
            }
            else
            {
                $list->set_list($result);
            }
            $list->prepare_items();
            ob_start();
            $list->display();
            $html = ob_get_clean();

            $ret['result']='success';
            $ret['html']=$html;
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
}