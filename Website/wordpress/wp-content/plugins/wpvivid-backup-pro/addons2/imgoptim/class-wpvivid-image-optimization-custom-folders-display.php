<?php

/**
 * WPvivid addon: yes
 * Addon Name: wpvivid-imgoptim-pro
 * Description: Pro
 * Admin_load: yes
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

class WPvivid_Folders_Optimized_Image_List_Ex extends WP_List_Table
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
            'title'    =>__( 'Images (Custom Folder)' ),
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
        $html='<input type="checkbox" name="folder_opt" value="'.$item['id'].'" />';
        echo $html;
    }

    public function column_title($item)
    {
        ?>
        <p class="filename">
            <span class="screen-reader-text"><?php _e( 'File name:' ); ?> </span>
            <?php
            echo esc_html( wp_basename( $item['path'] ) );
            ?>
        </p>
        <?php
    }

    public function column_webp($item)
    {
        $meta=$item['meta'];

        if(isset($meta['webp_status'])&&$meta['webp_status']==1)
        {
            $status=true;
        }
        else
        {
            $status=false;
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
        $meta=$item['meta'];

        if($meta['opt_status']==1)
        {
            $status=true;
        }
        else
        {
            $status=false;
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
        $meta=$item['meta'];

        $html='<div class="wpvivid-media-item" data-id="'.$item['id'].'">';
        $task=new WPvivid_ImgOptim_Task_Ex();
        $options=get_option('wpvivid_optimization_options',array());

        if($meta['opt_status']==1)
        {
            $status=true;
        }
        else
        {
            $status=false;
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
            $id='wpvivid_image_opt_folders_bulk_top_action';
            $class='top-action';
        }
        else if( 'bottom' === $which )
        {
            $css_type = 'margin: 10px 0 0 0';
            $id='wpvivid_image_opt_folders_bottom_action';
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
                        <option value="wpvivid_folders_restore_selected_image"><?php _e('Restore selected images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_folders_restore_all_image"><?php _e('Restore all images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_folders_delete_selected_webp"><?php _e('Delete selected WebP images','wpvivid-imgoptim')?></option>
                        <option value="wpvivid_folders_delete_all_webp"><?php _e('Delete all WebP images','wpvivid-imgoptim')?></option>
                    </select>
                    <input type="submit" class="button action <?php echo esc_attr( $class ); ?>" value="<?php _e('Apply','wpvivid-imgoptim')?>">
                </div>

                <br class="clear" />
            </div>
            <?php
        }
    }
}

class WPvivid_ImgOptim_Custom_Folders_Display_Addon
{
    public function __construct()
    {
    }

    public function get_optimize_data()
    {
        $optimize_data = array(
            'optimized_images' => 0
        );

        global $wpdb;

        $query="SELECT * FROM {$wpdb->prefix}wpvivid_files_opt_meta";

        $results = $wpdb->get_results($query,ARRAY_A);
        if (empty($results))
        {
            return $optimize_data;
        }
        else
        {
            foreach ($results as $meta)
            {
                $optimize_data['optimized_images']++;
            }
        }

        return $optimize_data;
    }

    public function get_folders_dropdown_results()
    {
        global $wpdb;

        $query="SELECT * FROM {$wpdb->prefix}wpvivid_files_opt_meta";
        $custom_folders=array();
        $results = $wpdb->get_results($query,ARRAY_A);
        if (empty($results))
        {
            return $custom_folders;
        }
        else
        {
            foreach ($results as $meta)
            {
                $path=dirname($meta['path']);
                $path=$this->transfer_path($path);
                $root=$this->transfer_path(ABSPATH);
                $sub_dir=str_replace($root,'',$path);
                $custom_folders[$sub_dir]=$sub_dir;
            }
        }
        return $custom_folders;
    }

    private function transfer_path($path)
    {
        $path = str_replace('\\','/',$path);
        $values = explode('/',$path);
        return implode(DIRECTORY_SEPARATOR,$values);
    }

    public function get_folders_optimized_list($folder='')
    {
        global $wpdb;

        $list=array();

        $query="SELECT * FROM {$wpdb->prefix}wpvivid_files_opt_meta";

        $results = $wpdb->get_results($query,ARRAY_A);
        if (empty($results))
        {
            return $list;
        }
        else
        {
            foreach ($results as $item)
            {
                $image['id']=$item['id'];
                $image['path']=$item['path'];

                if(empty($folder))
                {
                    $image['meta']=unserialize($item['meta']);
                    $list[$item['id']]=$image;
                }
                else
                {
                    $folder=$this->transfer_path($folder);
                    $path=dirname($item['path']);
                    $path=$this->transfer_path($path);
                    $root=$this->transfer_path(ABSPATH);
                    $sub_dir=str_replace($root,'',$path);
                    if($folder==$sub_dir)
                    {
                        $image['meta']=unserialize($item['meta']);
                        $list[$item['id']]=$image;
                    }
                }


            }
            return $list;
        }
    }

    public function optimized_images()
    {
        $folders=$this->get_folders_dropdown_results();

        ?>
        <div style="" id="wpvivid_optimized_folders_image_list">
            <div class="wp-filter" style="margin-bottom:0.5em;margin-top:0;">
                <div class="filter-items view-switch">
                    <div class="actions">
                        <label for="wpvivid-optimized-filter-by-folders" class="screen-reader-text">Filter</label>
                        <select id="wpvivid-optimized-filter-by-folders">
                            <option value="0"><?php _e( 'All Folders' ); ?></option>
                            <?php
                            foreach ( $folders as $folder )
                            {
                                ?>
                                <option value='<?php echo $folder?>'><?php echo $folder?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <input type="submit" name="filter_action" id="wpvivid_image_folders_filter" class="button" value="Filter">
                    </div>
                </div>
            </div>
            <div id="wpvivid_optimized_folders_image_list_body">
                <?php

                $result=$this->get_folders_optimized_list();

                $list = new WPvivid_Folders_Optimized_Image_List_Ex();
                $list->set_list($result);
                $list->prepare_items();
                $list ->display();
                ?>
            </div>
        </div>
        <script>
            jQuery('#wpvivid_image_folders_filter').click(function()
            {
                var folder=jQuery('#wpvivid-optimized-filter-by-folders').val();
                var ajax_data = {
                    'action': 'wpvivid_get_opt_folder_list_ex',
                    'folder':folder
                };

                wpvivid_post_request_addon(ajax_data, function (data)
                {
                    jQuery('#wpvivid_optimized_folders_image_list_body').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_optimized_folders_image_list_body').html(jsonarray.html);
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

            jQuery('#wpvivid_optimized_folders_image_list').on("click",'.top-action',function()
            {
                var selected=jQuery('#wpvivid_image_opt_folders_bulk_top_action').val();
                if(selected=='wpvivid_folders_restore_selected_image' || selected=='wpvivid_restore_selected_image')
                {
                    wpvivid_folders_restore_selected_image();
                }
                else if(selected=='wpvivid_folders_restore_all_image' || selected=='wpvivid_restore_all_image')
                {
                    wpvivid_folders_restore_all_image();
                }
                else if(selected=='wpvivid_folders_delete_selected_webp' || selected=='wpvivid_delete_selected_webp')
                {
                    wpvivid_folders_delete_selected_image();
                }
                else if(selected=='wpvivid_folders_delete_all_webp' || selected=='wpvivid_delete_all_webp')
                {
                    wpvivid_folders_delete_all_image();
                }
            });

            jQuery('#wpvivid_optimized_folders_image_list').on("click",'.bottom-action',function()
            {
                var selected=jQuery('#wpvivid_image_opt_folders_bottom_action').val();
                if(selected=='wpvivid_folders_restore_selected_image' || selected=='wpvivid_restore_selected_image')
                {
                    wpvivid_folders_restore_selected_image();
                }
                else if(selected=='wpvivid_folders_restore_all_image' || selected=='wpvivid_restore_all_image')
                {
                    wpvivid_folders_restore_all_image();
                }
                else if(selected=='wpvivid_folders_delete_selected_webp' || selected=='wpvivid_delete_selected_webp')
                {
                    wpvivid_folders_delete_selected_image();
                }
                else if(selected=='wpvivid_folders_delete_all_webp' || selected=='wpvivid_delete_all_webp')
                {
                    wpvivid_folders_delete_all_image();
                }
            });

            function wpvivid_folders_restore_selected_image()
            {
                var json = {};
                json['selected']=Array();
                jQuery('input[name=folder_opt][type=checkbox]').each(function(index, value)
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

                jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_restore_selected_opt_folders_image_ex',
                    'selected':selected
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
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
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_folders_restore_all_image()
            {
                jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_restore_all_opt_folders_image_ex'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
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
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_folders_delete_selected_image()
            {
                var json = {};
                json['selected']=Array();
                jQuery('input[name=folder_opt][type=checkbox]').each(function(index, value)
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

                jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_delete_folders_selected_webp_image',
                    'selected':selected
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
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
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_folders_delete_all_image()
            {
                jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', true);
                var ajax_data = {
                    'action': 'wpvivid_delete_folders_all_webp_image'
                };
                wpvivid_post_request_addon(ajax_data, function(data)
                {
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
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
                    jQuery('#wpvivid_optimized_folders_image_list').find('.action').prop('disabled', false);
                    var error_message = wpvivid_output_ajaxerror('restore image', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_get_opt_folders_list(page)
            {
                var folder=jQuery('#wpvivid-optimized-filter-by-folders').val();
                var ajax_data = {
                    'action': 'wpvivid_get_opt_folder_list_ex',
                    'folder':folder,
                    'page':page,
                };

                wpvivid_post_request_addon(ajax_data, function (data)
                {
                    jQuery('#wpvivid_optimized_folders_image_list_body').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_optimized_folders_image_list_body').html(jsonarray.html);
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

            jQuery('#wpvivid_optimized_folders_image_list').on("click",'.first-page',function()
            {
                wpvivid_get_opt_folders_list('first');
            });

            jQuery('#wpvivid_optimized_folders_image_list').on("click",'.prev-page',function()
            {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_opt_folders_list(page-1);
            });

            jQuery('#wpvivid_optimized_folders_image_list').on("click",'.next-page',function()
            {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_opt_folders_list(page+1);
            });

            jQuery('#wpvivid_optimized_folders_image_list').on("click",'.last-page',function()
            {
                wpvivid_get_opt_folders_list('last');
            });

            jQuery('#wpvivid_optimized_folders_image_list').on("keypress", '.current-page', function()
            {
                if(event.keyCode === 13){
                    var page = jQuery(this).val();
                    wpvivid_get_opt_folders_list(page);
                }
            });
        </script>
        <?php
    }
}