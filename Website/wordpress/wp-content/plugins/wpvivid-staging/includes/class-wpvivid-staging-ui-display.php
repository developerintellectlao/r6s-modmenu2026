<?php

if (!defined('WPVIVID_STAGING_PLUGIN_DIR'))
{
    die;
}

if ( ! class_exists( 'WP_List_Table' ) )
{
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class WPvivid_Staging_Custom_Select_List
{
    public $parent_id;
    public $is_staging_site   = false;
    public $staging_home_path = false;
    public $custom_core_path;
    public $custom_theme_path;
    public $custom_plugin_path;
    public $custom_uploads_path;
    public $custom_content_path;
    public $custom_additional_file_path;

    public function __construct(){

    }

    public function set_parent_id($parent_id){
        $this->parent_id = $parent_id;
    }

    public function set_staging_home_path($is_staging_site=false, $staging_home_path=false){
        $this->is_staging_site   = $is_staging_site;
        $this->staging_home_path = $staging_home_path;
    }

    public function display_rows(){
        $core_check = 'checked';
        $database_check = 'checked';
        $themes_check = 'checked';
        $plugins_check = 'checked';
        $uploads_check = 'checked';
        $content_check = 'checked';
        $additional_folder_check = '';

        $theme_exclude_extension = '';
        $plugin_exclude_extension = '';
        $upload_exclude_extension = '';
        $content_exclude_extension = '';
        $additional_folder_exclude_extension = '';

        $database_part_check = 'checked="checked"';
        $file_part_check = 'checked="checked"';
        $exclude_part_check = 'checked="checked"';

        if($this->is_staging_site){
            $border_css = 'border: 1px solid #f1f1f1;';
            $checkbox_disable = '';
            $core_descript = 'If the staging site and the live site have the same version of WordPress. Then it is not necessary to copy the WordPress core files to the live site.';
            $db_descript = 'It is recommended to copy all tables of the database to the live site.';
            $themes_plugins_descript = 'The activated plugins and themes will be copied to the live site by default. The Child theme must be copied if it exists';
            $uploads_descript = 'Images and media files are stored in the Uploads directory by default. All files are copied to the live site by default. You can exclude folders you do not want to copy.';
            $contents_descript = '<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to copy to the live site, except for the wp-content/uploads folder.';
            $additional_file_descript = '<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to copy to the live site.';
        }
        else{
            $border_css = 'border: none;';
            $checkbox_disable = ' disabled';
            $core_descript = 'These are the essential files for creating a staging site.';
            $db_descript = 'The tables created by WordPress are required for the staging site. Database tables created by themes or plugins are optional.';
            $themes_plugins_descript = 'The activated plugins and themes will be copied to a staging site by default. A Child theme must be copied if it exists.';
            $uploads_descript = 'Images and media files are stored in the Uploads directory by default. All files are copied to the staging site by default. You can exclude folders you do not want to copy.';
            $contents_descript = '<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to copy to the staging site, except for the wp-content/uploads folder.';
            $additional_file_descript = '<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to copy to the staging site.';
            $options = get_option('wpvivid_staging_history', array());
            if(isset($options['additional_file_check'])) {
                $additional_folder_check = $options['additional_file_check'] == '1' ? 'checked' : '';
            }
            if(isset($options['upload_extension']) && !empty($options['upload_extension'])){
                $upload_exclude_extension = implode(",", $options['upload_extension']);
            }
            if(isset($options['content_extension']) && !empty($options['content_extension'])){
                $content_exclude_extension = implode(",", $options['content_extension']);
            }
            if(isset($options['additional_file_extension']) && !empty($options['additional_file_extension'])){
                $additional_folder_exclude_extension = implode(",", $options['additional_file_extension']);
            }
        }

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
        }

        if($need_recalc)
        {
            $database_size = 'calculating';
            $core_size = 'calculating';
            $content_size = 'calculating';
            $themes_size = 'calculating';
            $plugins_size = 'calculating';
            $uploads_size = 'calculating';
            $total_file_size = 'calculating';
        }
        else
        {
            $database_size=isset($website_size['database_size'])?size_format($website_size['database_size'], 2):'calculating';
            $core_size=isset($website_size['core_size'])?size_format($website_size['core_size'], 2):'calculating';
            $content_size=isset($website_size['content_size'])?size_format($website_size['content_size'], 2):'calculating';
            $themes_size=isset($website_size['themes_size'])?size_format($website_size['themes_size'], 2):'calculating';
            $plugins_size=isset($website_size['plugins_size'])?size_format($website_size['plugins_size'], 2):'calculating';
            $uploads_size=isset($website_size['uploads_size'])?size_format($website_size['uploads_size'], 2):'calculating';
            $total_file_size = size_format($website_size['core_size']+$website_size['content_size']+$website_size['themes_size']+$website_size['plugins_size']+$website_size['uploads_size'], 2);
        }

        ?>
        <div>
            <span><input type="checkbox" class="wpvivid-custom-database-part" <?php esc_attr_e($database_part_check.$checkbox_disable); ?>></span>
            <span class="dashicons dashicons-admin-site-alt3 wpvivid-dashicons-blue"></span>
            <span class="wpvivid-handle-database-detail" style="cursor:pointer;"><strong>Database Will Be Copied</strong></span>
            <span class="wpvivid-handle-database-detail" style="cursor:pointer;"> (</span><span class="wpvivid-database-size"><?php esc_attr_e($database_size); ?></span><span>)</span>
            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                <div class="wpvivid-bottom">
                    <!-- The content you need -->
                    <p>Won't back up any tables or additional databases if you uncheck this.</p>
                    <i></i> <!-- do not delete this line -->
                </div>
            </span>
            <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-database-detail" style="cursor:pointer;"></span>
        </div>

        <div class="wpvivid-database-detail" style="display: none;">
            <!--  database begin  -->
            <div style="padding-left:2em;">
                <p><span><input type="checkbox" class="wpvivid-custom-database-check" <?php esc_attr_e($database_check.$checkbox_disable); ?>><span class="wpvivid-handle-base-database-detail" style="cursor:pointer;"><strong>Tables In The Wordpress Database</strong></span><span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-base-database-detail" style="cursor:pointer;"></span></span></p>
            </div>
            <div class="wpvivid-custom-database-info wpvivid-base-database-detail" style="display: none;">
                <div class="spinner is-active wpvivid-database-loading" style="margin: 0 5px 10px 0; float: left;"></div>
                <div style="float: left;">Archieving database tables</div>
                <div style="clear: both;"></div>
            </div>
            <div style="clear:both;"></div>
            <!--  database end  -->
        </div>

        <!--  files begin  -->
        <div style="margin-top:1em;">
            <span><input type="checkbox" class="wpvivid-custom-file-part" <?php esc_attr_e($file_part_check.$checkbox_disable); ?>></span>
            <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
            <span class="wpvivid-handle-file-detail" style="cursor:pointer;"><strong>Files & Folders Will Be Copied</strong></span>
            <span class="wpvivid-handle-file-detail" style="cursor:pointer;"> (</span><span class="wpvivid-total-file-size"><?php esc_attr_e($total_file_size); ?></span><span>)</span>
            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                <div class="wpvivid-bottom">
                    <!-- The content you need -->
                    <p>Won't back up any files or folders if you uncheck this.</p>
                    <i></i> <!-- do not delete this line -->
                </div>
            </span>
            <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-file-detail" style="cursor:pointer;"></span>
        </div>
        <div class="wpvivid-file-detail" style="padding-left:2em; display: none;">
            <p><span><input class="wpvivid-custom-file-check wpvivid-custom-core-check" type="checkbox" <?php esc_attr_e($core_check.$checkbox_disable); ?>><span><strong>Wordpress Core<span> (</span><span class="wpvivid-core-size"><?php esc_attr_e($core_size); ?></span><span>)</span>: </strong>includes <code>wp-admin</code> folder,<code>wp-includes</code> folder and all other essential files.</span></span></p>
            <p><span><input class="wpvivid-custom-file-check wpvivid-custom-themes-check" type="checkbox" <?php esc_attr_e($themes_check.$checkbox_disable); ?>><span><strong>Themes<span> (</span><span class="wpvivid-themes-size"><?php esc_attr_e($themes_size); ?></span><span>)</span>: </strong>includes all folders of themes.</span></p>
            <p><span><input class="wpvivid-custom-file-check wpvivid-custom-plugins-check" type="checkbox" <?php esc_attr_e($plugins_check.$checkbox_disable); ?>><span><strong>Plugins<span> (</span><span class="wpvivid-plugins-size"><?php esc_attr_e($plugins_size); ?></span><span>)</span>: </strong>includes all folders of plugins.</span></p>
            <p><span><input class="wpvivid-custom-file-check wpvivid-custom-content-check" type="checkbox" <?php esc_attr_e($content_check.$checkbox_disable); ?>><span><strong>Wp-content<span> (</span><span class="wpvivid-content-size"><?php esc_attr_e($content_size); ?></span><span>)</span>: </strong>everything in <code>wp-content</code> <strong>except for</strong> <code>themes</code>, <code>plugins</code> and <code>uploads</code> folders.</span></span></p>
            <p><span><input class="wpvivid-custom-file-check wpvivid-custom-uploads-check" type="checkbox" <?php esc_attr_e($uploads_check.$checkbox_disable); ?>><span><strong>Uploads<span> (</span><span class="wpvivid-uploads-size"><?php esc_attr_e($uploads_size); ?></span><span>)</span>: </strong>includes images, videos, and any other files such as PDF documents, MS Word docs, and GIFs.</span></span></p>
            <p>
                <span><input class="wpvivid-custom-file-check wpvivid-custom-additional-folder-check" type="checkbox" <?php esc_attr_e($additional_folder_check); ?>><span><strong>Additional Files/Folders: </strong>all folders/files in root directory of your website except for Wordpress core folders/files.</span></span>
                <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-additional-folder-detail" style="cursor:pointer;"></span>
            </p>

            <p></p>

            <div class="wpvivid-additional-folder-detail" style="display: none;">
                <div style="padding-left:2em;margin-top:1em;">
                    <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                        <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                    </div>
                </div>
                <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                            <span><strong>Tree View</strong></span>
                            <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-include-tree">Refresh<span>
                        </p>
                    </div>
                    <div class="wpvivid-custom-additional-folder-tree-info" style="margin-top:10px;height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer</div>
                    <div style="clear:both;"></div>
                    <div style="padding:1em 0 0 0;"><input class="button-primary wpvivid-include-additional-folder-btn" type="submit" value="Include Files/Folders"></div>
                </div>
                <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                            <span><strong>Additional Files/Folders Will Be Backed Up</strong></span>
                        </p>
                    </div>
                    <div class="wpvivid-custom-include-additional-folder-list" style="height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;">
                        <?php echo $this->wpvivid_load_custom_exclude_list('additional-folder'); ?>
                    </div>
                    <div style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-include-list" style="float:right;">Empty Included Files/Folders</span></div>
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <!--  files end  -->

        <div style="box-sizing:border-box; margin-top:1em;">
            <!--  exclude tree begin  -->
            <div style="margin-top:1em;">
                <span><input type="checkbox" class="wpvivid-custom-exclude-part" <?php esc_attr_e($exclude_part_check); ?>></span>
                <span class="dashicons dashicons-portfolio wpvivid-dashicons-grey"></span>
                <span class="wpvivid-handle-tree-detail" style="cursor:pointer;"><strong>Exclude Additional Files/Folders </strong></span>
                <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-tree-detail" style="cursor:pointer;"></span>
                <span style="display: none;"> (</span><span class="wpvivid-exclude-file-size" style="display: none;"></span><span style="display: none;">)</span>
                <span class="wpvivid-calc-exclude-file-size" style="padding:0.5em;">
                    <span class="wpvivid-grey" style="padding:0.2em 0.6em;cursor:pointer; border-radius:3px;">calc</span>
                </span>
            </div>
            <div class="wpvivid-tree-detail" style="display: none;">
                <div style="padding-left:2em;margin-top:1em;">
                    <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                        <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                    </div>
                </div>

                <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                            <span><strong>Folder Tree View</strong></span>
                            <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-exclude-tree">Refresh<span>
                        </p>
                    </div>
                    <div style="height:250px;">
                        <div>
                            <select name="action" class="wpvivid-custom-tree-selector" style="width:100%;border:1px solid #aaa;">
                                <option value="themes" selected>themes</option>
                                <option value="plugins">plugins</option>
                                <option value="content">wp-content</option>
                                <option value="uploads">uploads</option>
                            </select>
                        </div>
                        <div class="wpvivid-custom-exclude-tree-info" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div style="padding:1.5em 0 0 0;"><input class="button-primary wpvivid-custom-tree-exclude-btn" type="submit" value="Exclude Files/Folders"></div>
                </div>
                <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                            <span><strong>Excluded Files/Folders/File Types</strong></span>
                        </p>
                    </div>

                    <!-- themes -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module">
                        <input type="text" class="wpvivid-themes-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($theme_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module wpvivid-custom-exclude-themes-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;">
                        <?php echo $this->wpvivid_load_custom_exclude_list('themes'); ?>
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module" style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>

                    <!-- plugins -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="display: none;">
                        <input type="text" class="wpvivid-plugins-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($plugin_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module wpvivid-custom-exclude-plugins-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                        <?php echo $this->wpvivid_load_custom_exclude_list('plugins'); ?>
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>

                    <!-- content -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="display: none;">
                        <input type="text" class="wpvivid-content-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($content_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module wpvivid-custom-exclude-content-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                        <?php echo $this->wpvivid_load_custom_exclude_list('content'); ?>
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>

                    <!-- uploads -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="display: none;">
                        <input type="text" class="wpvivid-uploads-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($upload_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module wpvivid-custom-exclude-uploads-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;">
                        <?php echo $this->wpvivid_load_custom_exclude_list('uploads'); ?>
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                </div>

            </div>
            <div style="clear:both;"></div>
            <!--  exculde tree end  -->
        </div>
        <?php
    }

    public function wpvivid_load_custom_exclude_list($backup_type){
        $this->options=new WPvivid_Staging_Option();

        $list_type = 'themes_list';
        if($backup_type == 'themes'){
            $list_type = 'themes_list';
        }
        else if($backup_type == 'plugins'){
            $list_type = 'plugins_list';
        }
        else if($backup_type == 'content'){
            $list_type = 'content_list';
        }
        else if($backup_type == 'uploads'){
            $list_type = 'uploads_list';
        }
        else if($backup_type == 'additional-folder'){
            $list_type = 'additional_file_list';
        }

        $options = $this->options->get_option('wpvivid_staging_history_ex');
        $init=$this->options->get_option('wpvivid_init_staging_history_ex');
        if(empty($init))
        {
            if(isset($options['content_list'])&&!empty($options['content_list']))
            {
                $options['content_list']=$this->get_default_content($options['content_list']);
            }
            else
            {
                $options['content_list']=$this->get_default_content(array());
            }
            $this->options->update_option('wpvivid_staging_history_ex',$options);
            $this->options->update_option('wpvivid_init_staging_history_ex','1');
        }
        $ret = '';

        //fix old data
        /*$need_fix = false;
        if($backup_type == 'themes'){
            if(isset($options['themes_list']) && !empty($options['themes_list'])){
                foreach ($options['themes_list'] as $index => $value) {
                    if(!isset($value['type'])){
                        $need_fix = true;
                        $options['themes_list'][$value]['name'] = $value;
                        $options['themes_list'][$value]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                        unset($options['themes_list'][$index]);
                    }
                }
            }
        }
        else if($backup_type == 'plugins'){
            if(isset($options['plugins_list']) && !empty($options['plugins_list'])){
                foreach ($options['plugins_list'] as $index => $value) {
                    if(!isset($value['type'])){
                        $need_fix = true;
                        $options['plugins_list'][$value]['name'] = $value;
                        $options['plugins_list'][$value]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                        unset($options['plugins_list'][$index]);
                    }
                }
            }
        }

        if($need_fix){
            $this->options->update_option('wpvivid_staging_history', $options);
        }*/

        if(isset($options[$list_type]) && !empty($options[$list_type])) {
            foreach ($options[$list_type] as $index => $value) {
                if(isset($value['type'])){
                    if($value['type'] === 'wpvivid-custom-li-folder-icon'){
                        $value['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                    }
                    else if($value['type'] === 'wpvivid-custom-li-file-icon'){
                        $value['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                    }

                    $class_type = $value['type'];
                    $exclude_name = $value['name'];
                    if($value['type'] === 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer'){
                        $type = 'folder';
                    }
                    else{
                        $type = 'file';
                    }
                }
                else{
                    $class_type = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                    $exclude_name = $value;
                    $type = 'folder';
                }
                $ret .= '<div class="wpvivid-text-line" type="'.$type.'">
                            <span class="dashicons dashicons-trash wpvivid-icon-16px wpvivid-remove-custom-exlcude-tree"></span><span class="'.$class_type.'"></span><span class="wpvivid-text-line">'.$exclude_name.'</span>
                         </div>';
            }
        }
        return $ret;
    }

    public function load_js(){
        $core_dir = $this->is_staging_site === false ? str_replace('\\','/',get_home_path()) : str_replace('\\','/',$this->staging_home_path);
        $this->custom_core_path = $core_dir;

        $upload_dir = wp_upload_dir();
        $upload_path = $this->is_staging_site === false ?  $upload_dir['basedir'] : $this->staging_home_path.'/wp-content/uploads';
        $upload_path = str_replace('\\','/',$upload_path);
        $upload_path = $upload_path.'/';
        $this->custom_uploads_path = $upload_path;

        $content_dir = $this->is_staging_site === false ? WP_CONTENT_DIR : $this->staging_home_path.'/wp-content';
        $content_path = str_replace('\\','/',$content_dir);
        $content_path = $content_path.'/';
        $this->custom_content_path = $content_path;

        $additional_file_path = $this->is_staging_site === false ? str_replace('\\','/',get_home_path()) : str_replace('\\','/',$this->staging_home_path);
        $this->custom_additional_file_path = $additional_file_path;

        $theme_dir = $this->is_staging_site === false ? str_replace('\\','/', get_theme_root()) : str_replace('\\','/', $this->staging_home_path.'/wp-content/themes');
        $this->custom_theme_path = $theme_dir.'/';

        $plugin_dir = $this->is_staging_site === false ? str_replace('\\','/', WP_PLUGIN_DIR) : str_replace('\\','/', $this->staging_home_path.'/wp-content/plugins');
        $this->custom_plugin_path = $plugin_dir.'/';
        ?>
        <script>
            var path_arr = {};
            path_arr['core'] = '<?php echo $this->custom_core_path; ?>';
            path_arr['content'] = '<?php echo $this->custom_content_path; ?>';
            path_arr['uploads'] = '<?php echo $this->custom_uploads_path; ?>';
            path_arr['themes'] = '<?php echo $this->custom_theme_path; ?>';
            path_arr['plugins'] = '<?php echo $this->custom_plugin_path; ?>';

            function wpvivid_handle_custom_open_close_ex(handle_obj, obj, parent_id){
                if(obj.is(":hidden")) {
                    handle_obj.each(function(){
                        if(jQuery(this).hasClass('dashicons-arrow-down-alt2')){
                            jQuery(this).removeClass('dashicons-arrow-down-alt2');
                            jQuery(this).addClass('dashicons-arrow-up-alt2');
                        }
                    });
                    obj.show();
                }
                else{
                    handle_obj.each(function(){
                        if(jQuery(this).hasClass('dashicons-arrow-up-alt2')){
                            jQuery(this).removeClass('dashicons-arrow-up-alt2');
                            jQuery(this).addClass('dashicons-arrow-down-alt2');
                        }
                    });
                    obj.hide();
                }
            }

            function wpvivid_change_custom_exclude_info(type, parent_id){
                jQuery('#'+parent_id).find('.wpvivid-custom-exclude-module').hide();
                if(type === 'themes'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-themes-module').show();
                }
                else if(type === 'plugins'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-plugins-module').show();
                }
                else if(type === 'content'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-content-module').show();
                }
                else if(type === 'uploads'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-uploads-module').show();
                }
            }

            function wpvivid_check_tree_repeat(tree_type, value, parent_id) {
                if(tree_type === 'themes'){
                    var list = 'wpvivid-custom-exclude-themes-list';
                }
                else if(tree_type === 'plugins'){
                    var list = 'wpvivid-custom-exclude-plugins-list';
                }
                else if(tree_type === 'content'){
                    var list = 'wpvivid-custom-exclude-content-list';
                }
                else if(tree_type === 'uploads'){
                    var list = 'wpvivid-custom-exclude-uploads-list';
                }
                else if(tree_type === 'additional-folder'){
                    var list = 'wpvivid-custom-include-additional-folder-list';
                }

                var brepeat = false;
                jQuery('#'+parent_id).find('.'+list+' div').find('span:eq(2)').each(function (){
                    if (value === this.innerHTML) {
                        brepeat = true;
                    }
                });
                return brepeat;
            }

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-database-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-database-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
                init_staging_db_size('<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-base-database-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-base-database-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-base-database-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-file-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-file-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-file-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
                init_staging_file_size('<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-additional-folder-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-additional-folder-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-additional-folder-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-tree-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-tree-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-tree-detail');
                var value = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-tree-selector').val();
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('change', '.wpvivid-custom-tree-selector', function(){
                var value = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-tree-selector').val();
                jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-exclude-tree-info').jstree("destroy").empty();
                wpvivid_change_custom_exclude_info(value, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-refresh-include-tree', function(){

            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-refresh-exclude-tree', function(){

            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-include-additional-folder-btn', function(){
                var select_folders = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-additional-folder-tree-info').jstree(true).get_selected(true);
                var tree_path = '<?php echo $this->custom_additional_file_path; ?>';
                var list_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-include-additional-folder-list');
                var tree_type = 'additional-folder';

                jQuery.each(select_folders, function (index, select_item) {
                    if (select_item.id !== tree_path) {
                        var value = select_item.id;
                        value = value.replace(tree_path, '');
                        if (!wpvivid_check_tree_repeat(tree_type, value, '<?php echo $this->parent_id; ?>')) {
                            var class_name = select_item.icon;
                            if(class_name === 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer'){
                                var type = 'folder';
                            }
                            else{
                                var type = 'file';
                            }
                            var tr = "<div class='wpvivid-text-line' type='"+type+"'>" +
                                "<span class='dashicons dashicons-trash wpvivid-icon-16px wpvivid-remove-custom-exlcude-tree'></span>" +
                                "<span class='"+class_name+"'></span>" +
                                "<span class='wpvivid-text-line'>" + value + "</span>" +
                                "</div>";
                            list_obj.append(tr);
                        }
                    }
                });
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-remove-custom-exlcude-tree', function(){
                jQuery(this).parent().remove();
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-clear-custom-include-list', function(){
                jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-include-additional-folder-list').html('');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-clear-custom-exclude-list', function(){
                var tree_type = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-tree-selector').val();
                if(tree_type === 'themes'){
                    var list = 'wpvivid-custom-exclude-themes-list';
                }
                else if(tree_type === 'plugins'){
                    var list = 'wpvivid-custom-exclude-plugins-list';
                }
                else if(tree_type === 'content'){
                    var list = 'wpvivid-custom-exclude-content-list';
                }
                else if(tree_type === 'uploads'){
                    var list = 'wpvivid-custom-exclude-uploads-list';
                }
                jQuery('#<?php echo $this->parent_id; ?>').find('.'+list).html('');
            });

            //
            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-custom-database-part', function(){
                if(jQuery(this).prop('checked')){
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-table-check').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').prop('checked', true);
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', false);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-table-check').prop('checked', false);
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').prop('checked', false);
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-custom-database-check', function(){
                if(jQuery(this).prop('checked')){
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-table-check').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').prop('checked', true);
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', false);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-table-check').prop('checked', false);
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').prop('checked', false);
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-custom-file-part', function(){
                if(jQuery(this).prop('checked')){
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-file-check').prop('checked', true);
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-file-check').prop('checked', false);
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-custom-file-check', function(){
                if(jQuery(this).prop('checked')){
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-file-part').prop('checked', true);
                }
                else{
                    var all_uncheck=true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-file-check').each(function(){
                        if(jQuery(this).prop('checked')){
                            all_uncheck = false;
                        }
                    });
                    if(all_uncheck) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-file-part').prop('checked', false);
                    }
                }
            });
            //

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-database-table-check', function(){
                if(jQuery(this).prop('checked')){
                    if(jQuery(this).hasClass('wpvivid-database-base-table-check')){
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=base_db][name=Database]').prop('checked', true);
                    }
                    else if(jQuery(this).hasClass('wpvivid-database-other-table-check')){
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=other_db][name=Database]').prop('checked', true);
                    }
                    else if(jQuery(this).hasClass('wpvivid-database-woo-table-check')){
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=woo_db][name=Database]').prop('checked', true);
                    }
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', true);
                }
                else{
                    if (jQuery(this).hasClass('wpvivid-database-base-table-check')) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=base_db][name=Database]').prop('checked', false);
                    }
                    else if (jQuery(this).hasClass('wpvivid-database-other-table-check')) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=other_db][name=Database]').prop('checked', false);
                    }
                    else if (jQuery(this).hasClass('wpvivid-database-woo-table-check')) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=woo_db][name=Database]').prop('checked', false);
                    }
                    var all_uncheck=true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').each(function(){
                        if(jQuery(this).prop('checked')){
                            all_uncheck = false;
                        }
                    });
                    if(all_uncheck) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', false);
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', false);
                    }
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", 'input:checkbox[option=base_db][name=Database]', function(){
                if(jQuery(this).prop('checked')){
                    var all_check = true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=base_db][name=Database]').each(function(){
                        if(!jQuery(this).prop('checked')){
                            all_check = false;
                        }
                    });
                    if(all_check){
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-base-table-check').prop('checked', true);
                    }
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', true);
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-base-table-check').prop('checked', false);
                    var all_uncheck=true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').each(function(){
                        if(jQuery(this).prop('checked')){
                            all_uncheck = false;
                        }
                    });
                    if(all_uncheck) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', false);
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', false);
                    }
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", 'input:checkbox[option=other_db][name=Database]', function(){
                if(jQuery(this).prop('checked')){
                    var all_check = true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=other_db][name=Database]').each(function(){
                        if(!jQuery(this).prop('checked')){
                            all_check = false;
                        }
                    });
                    if(all_check){
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-other-table-check').prop('checked', true);
                    }
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', true);
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-other-table-check').prop('checked', false);
                    var all_uncheck=true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').each(function(){
                        if(jQuery(this).prop('checked')){
                            all_uncheck = false;
                        }
                    });
                    if(all_uncheck) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', false);
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', false);
                    }
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", 'input:checkbox[option=woo_db][name=Database]', function(){
                if(jQuery(this).prop('checked')){
                    var all_check = true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=woo_db][name=Database]').each(function(){
                        if(!jQuery(this).prop('checked')){
                            all_check = false;
                        }
                    });
                    if(all_check){
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-woo-table-check').prop('checked', true);
                    }
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', true);
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', true);
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-woo-table-check').prop('checked', false);
                    var all_uncheck=true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[name=Database]').each(function(){
                        if(jQuery(this).prop('checked')){
                            all_uncheck = false;
                        }
                    });
                    if(all_uncheck) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-part').prop('checked', false);
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-database-check').prop('checked', false);
                    }
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", '.wpvivid-calc-exclude-file-size', function(){
                var exclude_files = wpvivid_get_staging_exclude_json('<?php echo $this->parent_id; ?>');
                var custom_option = {
                    'exclude_files': exclude_files
                };
                custom_option = JSON.stringify(custom_option);

                var ajax_data = {
                    'action': 'wpvividstg_recalc_exclude_file_size',
                    'custom_option': custom_option
                };
                jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').html('. . .');
                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').show();
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').prev().show();
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').next().show();
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').html(jsonarray.total_file_size);
                            init_staging_file_size('<?php echo $this->parent_id; ?>');
                        }
                        else
                        {
                            wpvivid_staging_get_time_exceeded_exclude_file_size('<?php echo $this->parent_id; ?>');
                        }
                    }
                    catch (err)
                    {
                        wpvivid_staging_get_time_exceeded_exclude_file_size('<?php echo $this->parent_id; ?>');
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_staging_get_time_exceeded_exclude_file_size('<?php echo $this->parent_id; ?>');
                });
            });

            function wpvivid_get_database_size(){
                var ajax_data = {
                    'action': 'wpvividstg_get_custom_database_size'
                };
                wpvivid_post_request(ajax_data, function (data) {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result == 'success') {
                        jQuery('.wpvivid-database-size').html(jsonarray.database_size);
                    }
                    else {
                        alert(jsonarray.error);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown) {
                    var error_message = wpvivid_output_ajaxerror('retrieving the last backup log', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_get_files_size(){
                var ajax_data = {
                    'action': 'wpvividstg_get_custom_files_size'
                };
                wpvivid_post_request(ajax_data, function (data) {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result == 'success') {
                        jQuery('.wpvivid-core-size').html(jsonarray.core_size);
                        jQuery('.wpvivid-themes-size').html(jsonarray.themes_size);
                        jQuery('.wpvivid-plugins-size').html(jsonarray.plugins_size);
                        jQuery('.wpvivid-uploads-size').html(jsonarray.uploads_size);
                        jQuery('.wpvivid-content-size').html(jsonarray.content_size);
                        jQuery('.wpvivid-additional-folder-size').html(jsonarray.additional_size);
                        jQuery('.wpvivid-total-file-size').html(jsonarray.total_file_size);
                    }
                    else {
                        alert(jsonarray.error);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown) {
                    var error_message = wpvivid_output_ajaxerror('retrieving the last backup log', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            jQuery(document).ready(function () {
                //wpvivid_get_database_size();
                //wpvivid_get_files_size();
            });
        </script>
        <?php
    }

    public function get_default_content($content_list)
    {
        $exclude_default = array();
        $folders=array();
        //
        $exclude_default[3]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[3]['name'] = WPVIVID_STAGING_PATH;

        $local_setting=get_option('wpvivid_local_setting');
        if(empty($local_setting)||(!isset($local_setting['path'])))
        {
            $exclude_default[4]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
            $exclude_default[4]['name'] = 'wpvividbackups';
        }
        else
        {
            $exclude_default[4]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
            $exclude_default[4]['name'] = $local_setting['path'];
        }

        $exclude_default[5]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[5]['name'] = 'updraft';     // Updraft Plus backup directory
        $exclude_default[6]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[6]['name'] = 'ai1wm-backups';   // All-in-one WP migration backup directory
        $exclude_default[7]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[7]['name'] = 'backups';     // Xcloner backup directory
        $exclude_default[8]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[8]['name'] = 'upgrade';
        $exclude_default[10]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[10]['name'] = 'cache';
        $exclude_default[11]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[11]['name'] = 'wphb-cache';
        $exclude_default[12]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[12]['name'] = 'backup';
        $exclude_default[13]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[13]['name'] = 'Dropbox_Backup';
        $exclude_default[14]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
        $exclude_default[14]['name'] = 'mu-plugins';
        $exclude_default[15]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
        $exclude_default[15]['name'] = 'mysql.sql';  //mysql

        if(!empty($exclude_default))
        {
            foreach ($exclude_default as $index => $value)
            {
                if(array_search($value['name'],array_column($content_list, 'name'))===false)
                {
                    $content_list[]=$value;
                }
            }
        }

        return $content_list;
    }
}

class WPvivid_Staging_Custom_MU_Select_List{
    public $parent_id;
    public $is_staging_site   = false;
    public $is_sync_site      = false;
    public $staging_home_path = false;
    public $custom_core_path;
    public $custom_theme_path;
    public $custom_plugin_path;
    public $custom_uploads_path;
    public $custom_content_path;
    public $custom_additional_file_path;

    public function __construct(){

    }

    public function set_parent_id($parent_id){
        $this->parent_id = $parent_id;
    }

    public function set_staging_home_path($is_staging_site=false, $is_sync_site=false, $staging_home_path=false){
        $this->is_staging_site   = $is_staging_site;
        $this->is_sync_site      = $is_sync_site;
        $this->staging_home_path = $staging_home_path;
    }

    public function display_rows(){
        $core_check = 'checked';
        $database_check = 'checked';
        $themes_check = 'checked';
        $plugins_check = 'checked';
        $uploads_check = 'checked';
        $content_check = 'checked';
        $additional_folder_check = '';

        $theme_exclude_extension = '';
        $plugin_exclude_extension = '';
        $upload_exclude_extension = '';
        $content_exclude_extension = '';
        $additional_folder_exclude_extension = '';

        $database_part_check = 'checked="checked"';
        $file_part_check = 'checked="checked"';
        $exclude_part_check = 'checked="checked"';

        $db_descript = 'All the tables in the WordPress MU database except for subsites tables.';
        $uploads_descript = 'The folder where images and media files of the main site are stored by default. All files will be copied to the staging site by default. You can exclude folders you do not want to copy.';
        $core_descript = 'These are the essential files for creating a staging site.';
        $themes_plugins_descript = 'All the plugins and themes files used by the MU network. The activated plugins and themes will be copied to the staging site by default. A child theme must be copied if it exists.';
        $contents_descript = '<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to copy to the staging site, except for the wp-content/uploads folder.';
        $additional_file_descript = '<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to copy to the staging site.';

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
        }

        if($need_recalc)
        {
            $database_size = 'calculating';
            $core_size = 'calculating';
            $content_size = 'calculating';
            $themes_size = 'calculating';
            $plugins_size = 'calculating';
            $uploads_size = 'calculating';
            $total_file_size = 'calculating';
        }
        else
        {
            $database_size=isset($website_size['database_size'])?size_format($website_size['database_size'], 2):'calculating';
            $core_size=isset($website_size['core_size'])?size_format($website_size['core_size'], 2):'calculating';
            $content_size=isset($website_size['content_size'])?size_format($website_size['content_size'], 2):'calculating';
            $themes_size=isset($website_size['themes_size'])?size_format($website_size['themes_size'], 2):'calculating';
            $plugins_size=isset($website_size['plugins_size'])?size_format($website_size['plugins_size'], 2):'calculating';
            $uploads_size=isset($website_size['uploads_size'])?size_format($website_size['uploads_size'], 2):'calculating';
            $total_file_size = size_format($website_size['core_size']+$website_size['content_size']+$website_size['themes_size']+$website_size['plugins_size']+$website_size['uploads_size'], 2);
        }
        ?>
        <div>
            <span><input type="checkbox" class="wpvivid-custom-database-part" <?php esc_attr_e($database_part_check); ?> disabled></span>
            <span class="dashicons dashicons-admin-site-alt3 wpvivid-dashicons-blue"></span>
            <span class="wpvivid-handle-database-detail" style="cursor:pointer;"><strong>Database Will Be Copied</strong></span>
            <span class="wpvivid-handle-database-detail" style="cursor:pointer;"> (</span><span class="wpvivid-database-size"><?php esc_attr_e($database_size); ?></span><span>)</span>
            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                <div class="wpvivid-bottom">
                    <!-- The content you need -->
                    <p>Won't back up any tables or additional databases if you uncheck this.</p>
                    <i></i> <!-- do not delete this line -->
                </div>
            </span>
            <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-database-detail" style="cursor:pointer;"></span>
        </div>

        <div class="wpvivid-database-detail" style="display: none;">
            <!--  database begin  -->
            <div style="padding-left:2em;">
                <p><span><input type="checkbox" class="wpvivid-custom-database-check" <?php esc_attr_e($database_check); ?> disabled><span class="wpvivid-handle-base-database-detail" style="cursor:pointer;"><strong>Tables In The Wordpress Database</strong></span></span></p>
            </div>
            <div style="clear:both;"></div>
            <!--  database end  -->
        </div>

        <!--  files begin  -->
        <div style="margin-top:1em;">
            <span><input type="checkbox" class="wpvivid-custom-file-part" <?php esc_attr_e($file_part_check); ?> disabled></span>
            <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
            <span class="wpvivid-handle-file-detail" style="cursor:pointer;"><strong>Files & Folders Will Be Copied</strong></span>
            <span class="wpvivid-handle-file-detail" style="cursor:pointer;"> (</span><span class="wpvivid-total-file-size"><?php esc_attr_e($total_file_size); ?></span><span>)</span>
            <span class="dashicons dashicons-editor-help wpvivid-dashicons-editor-help wpvivid-tooltip">
                <div class="wpvivid-bottom">
                    <!-- The content you need -->
                    <p>Won't back up any files or folders if you uncheck this.</p>
                    <i></i> <!-- do not delete this line -->
                </div>
            </span>
            <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-file-detail" style="cursor:pointer;"></span>
        </div>
        <div class="wpvivid-file-detail" style="padding-left:2em; display: none;">
            <p><span><input class="wpvivid-custom-core-check" type="checkbox" <?php esc_attr_e($core_check); ?> disabled><span><strong>Wordpress Core<span> (</span><span class="wpvivid-core-size"><?php esc_attr_e($core_size); ?></span><span>)</span>: </strong>includes <code>wp-admin</code> folder,<code>wp-includes</code> folder and all other essential files.</span></span></p>
            <p><span><input class="wpvivid-custom-themes-check" type="checkbox" <?php esc_attr_e($themes_check); ?> disabled><span><strong>Themes<span> (</span><span class="wpvivid-themes-size"><?php esc_attr_e($themes_size); ?></span><span>)</span>: </strong>includes all folders of themes.</span></p>
            <p><span><input class="wpvivid-custom-plugins-check" type="checkbox" <?php esc_attr_e($plugins_check); ?> disabled><span><strong>Plugins<span> (</span><span class="wpvivid-plugins-size"><?php esc_attr_e($plugins_size); ?></span><span>)</span>: </strong>includes all folders of plugins.</span></p>
            <p><span><input class="wpvivid-custom-content-check" type="checkbox" <?php esc_attr_e($content_check); ?> disabled><span><strong>Wp-content<span> (</span><span class="wpvivid-content-size"><?php esc_attr_e($content_size); ?></span><span>)</span>: </strong>everything in <code>wp-content</code> <strong>except for</strong> <code>themes</code>, <code>plugins</code> and <code>uploads</code> folders.</span></span></p>
            <p><span><input class="wpvivid-custom-uploads-check" type="checkbox" <?php esc_attr_e($uploads_check); ?> disabled><span><strong>Uploads<span> (</span><span class="wpvivid-uploads-size"><?php esc_attr_e($uploads_size); ?></span><span>)</span>: </strong>includes images, videos, and any other files such as PDF documents, MS Word docs, and GIFs.</span></span></p>
            <p>
                <span><input class="wpvivid-custom-additional-folder-check" type="checkbox" <?php esc_attr_e($additional_folder_check); ?>><span><strong>Additional Files/Folders: </strong>all folders/files in root directory of your website except for Wordpress core folders/files.</span></span>
                <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-additional-folder-detail" style="cursor:pointer;"></span>
            </p>

            <p></p>

            <div class="wpvivid-additional-folder-detail" style="display: none;">
                <div style="padding-left:2em;margin-top:1em;">
                    <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                        <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                    </div>
                </div>
                <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                            <span><strong>Tree View</strong></span>
                            <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-include-tree">Refresh<span>
                        </p>
                    </div>
                    <div class="wpvivid-custom-additional-folder-tree-info" style="margin-top:10px;height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer</div>
                    <div style="clear:both;"></div>
                    <div style="padding:1em 0 0 0;"><input class="button-primary wpvivid-include-additional-folder-btn" type="submit" value="Include Files/Folders"></div>
                </div>
                <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                            <span><strong>Additional Files/Folders Will Be Backed Up</strong></span>
                        </p>
                    </div>
                    <div class="wpvivid-custom-include-additional-folder-list" style="height:250px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;"></div>
                    <div style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-include-list" style="float:right;">Empty Included Files/Folders</span></div>
                </div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <!--  files end  -->

        <div style="box-sizing:border-box; margin-top:1em;">
            <!--  exclude tree begin  -->
            <div style="margin-top:1em;">
                <span><input type="checkbox" class="wpvivid-custom-exclude-part" <?php esc_attr_e($exclude_part_check); ?>></span>
                <span class="dashicons dashicons-portfolio wpvivid-dashicons-grey"></span>
                <span class="wpvivid-handle-tree-detail" style="cursor:pointer;"><strong>Exclude Additional Files/Folders </strong></span>
                <span class="dashicons dashicons-arrow-down-alt2 wpvivid-dashicons-grey wpvivid-handle-tree-detail" style="cursor:pointer;"></span>
                <span style="display: none;"> (</span><span class="wpvivid-exclude-file-size" style="display: none;"></span><span style="display: none;">)</span>
                <span class="wpvivid-calc-exclude-file-size" style="padding:0.5em;">
                    <span class="wpvivid-grey" style="padding:0.2em 0.6em;cursor:pointer; border-radius:3px;">calc</span>
                </span>
            </div>
            <div class="wpvivid-tree-detail" style="display: none;">
                <div style="padding-left:2em;margin-top:1em;">
                    <div style="border-bottom:1px solid #eee;border-top:1px solid #eee;">
                        <p><span class="dashicons dashicons-lightbulb wpvivid-dashicons-orange"></span><span><code>CTRL</code> + <code>Left Click</code> to select multiple files or folders.</span></p>
                    </div>
                </div>

                <div style="width:30%;float:left;box-sizing:border-box;padding-right:0.5em;padding-left:2em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-networking wpvivid-dashicons-blue"></span>
                            <span><strong>Folder Tree View</strong></span>
                            <span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-refresh-exclude-tree">Refresh<span>
                        </p>
                    </div>
                    <div style="height:250px;">
                        <div>
                            <select name="action" class="wpvivid-custom-tree-selector" style="width:100%;border:1px solid #aaa;">
                                <option value="themes" selected>themes</option>
                                <option value="plugins">plugins</option>
                                <option value="content">wp-content</option>
                                <option value="uploads">uploads</option>
                            </select>
                        </div>
                        <div class="wpvivid-custom-exclude-tree-info" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow:auto;">Tree Viewer
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                    <div style="padding:1.5em 0 0 0;"><input class="button-primary wpvivid-custom-tree-exclude-btn" type="submit" value="Exclude Files/Folders"></div>
                </div>
                <div style="width:70%; float:left;box-sizing:border-box;padding-left:0.5em;">
                    <div>
                        <p>
                            <span class="dashicons dashicons-portfolio wpvivid-dashicons-orange"></span>
                            <span><strong>Excluded Files/Folders/File Types</strong></span>
                        </p>
                    </div>

                    <!-- themes -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module">
                        <input type="text" class="wpvivid-themes-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($theme_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module wpvivid-custom-exclude-themes-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;"></div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-themes-module" style="padding:1em 0 0 0;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>

                    <!-- plugins -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="display: none;">
                        <input type="text" class="wpvivid-plugins-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($plugin_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module wpvivid-custom-exclude-plugins-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;"></div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-plugins-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>

                    <!-- content -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="display: none;">
                        <input type="text" class="wpvivid-content-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($content_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module wpvivid-custom-exclude-content-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;"></div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-content-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>

                    <!-- uploads -->
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="display: none;">
                        <input type="text" class="wpvivid-uploads-extension" style="width:100%; border:1px solid #aaa;" value="<?php esc_attr_e($upload_exclude_extension); ?>" placeholder="Exclude file types, separate by comma - for example: gif, jpg, webp, pdf" />
                    </div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module wpvivid-custom-exclude-uploads-list" style="margin-top:10px;height:210px;border:1px solid #eee;padding:0.2em 0.5em;overflow-y:auto;display: none;"></div>
                    <div class="wpvivid-custom-exclude-module wpvivid-custom-exclude-uploads-module" style="padding:1em 0 0 0;display: none;"><span class="wpvivid-rectangle wpvivid-grey-light wpvivid-hover-blue wpvivid-clear-custom-exclude-list" style="float:right;">Empty Excluded Files/Folders</span></div>
                </div>

            </div>
            <div style="clear:both;"></div>
            <!--  exculde tree end  -->
        </div>
        <?php
    }

    public function load_js(){
        $core_dir = $this->is_staging_site === false ? str_replace('\\','/',get_home_path()) : str_replace('\\','/',$this->staging_home_path);
        $this->custom_core_path = $core_dir;

        $upload_dir = wp_upload_dir();
        $upload_path = $this->is_staging_site === false ?  $upload_dir['basedir'] : $this->staging_home_path.'/wp-content/uploads';
        $upload_path = str_replace('\\','/',$upload_path);
        $upload_path = $upload_path.'/';
        $this->custom_uploads_path = $upload_path;

        $content_dir = $this->is_staging_site === false ? WP_CONTENT_DIR : $this->staging_home_path.'/wp-content';
        $content_path = str_replace('\\','/',$content_dir);
        $content_path = $content_path.'/';
        $this->custom_content_path = $content_path;

        $additional_file_path = $this->is_staging_site === false ? str_replace('\\','/',get_home_path()) : str_replace('\\','/',$this->staging_home_path);
        $this->custom_additional_file_path = $additional_file_path;

        $theme_dir = $this->is_staging_site === false ? str_replace('\\','/', get_theme_root()) : str_replace('\\','/', $this->staging_home_path.'/wp-content/themes');
        $this->custom_theme_path = $theme_dir.'/';

        $plugin_dir = $this->is_staging_site === false ? str_replace('\\','/', WP_PLUGIN_DIR) : str_replace('\\','/', $this->staging_home_path.'/wp-content/plugins');
        $this->custom_plugin_path = $plugin_dir.'/';
        ?>
        <script>
            var path_arr = {};
            path_arr['core'] = '<?php echo $this->custom_core_path; ?>';
            path_arr['content'] = '<?php echo $this->custom_content_path; ?>';
            path_arr['uploads'] = '<?php echo $this->custom_uploads_path; ?>';
            path_arr['themes'] = '<?php echo $this->custom_theme_path; ?>';
            path_arr['plugins'] = '<?php echo $this->custom_plugin_path; ?>';

            function wpvivid_handle_custom_open_close_ex(handle_obj, obj, parent_id){
                if(obj.is(":hidden")) {
                    handle_obj.each(function(){
                        if(jQuery(this).hasClass('dashicons-arrow-down-alt2')){
                            jQuery(this).removeClass('dashicons-arrow-down-alt2');
                            jQuery(this).addClass('dashicons-arrow-up-alt2');
                        }
                    });
                    obj.show();
                }
                else{
                    handle_obj.each(function(){
                        if(jQuery(this).hasClass('dashicons-arrow-up-alt2')){
                            jQuery(this).removeClass('dashicons-arrow-up-alt2');
                            jQuery(this).addClass('dashicons-arrow-down-alt2');
                        }
                    });
                    obj.hide();
                }
            }

            function wpvivid_change_custom_exclude_info(type, parent_id){
                jQuery('#'+parent_id).find('.wpvivid-custom-exclude-module').hide();
                if(type === 'themes'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-themes-module').show();
                }
                else if(type === 'plugins'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-plugins-module').show();
                }
                else if(type === 'content'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-content-module').show();
                }
                else if(type === 'uploads'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-uploads-module').show();
                }
            }

            function wpvivid_check_tree_repeat(tree_type, value, parent_id) {
                if(tree_type === 'themes'){
                    var list = 'wpvivid-custom-exclude-themes-list';
                }
                else if(tree_type === 'plugins'){
                    var list = 'wpvivid-custom-exclude-plugins-list';
                }
                else if(tree_type === 'content'){
                    var list = 'wpvivid-custom-exclude-content-list';
                }
                else if(tree_type === 'uploads'){
                    var list = 'wpvivid-custom-exclude-uploads-list';
                }
                else if(tree_type === 'additional-folder'){
                    var list = 'wpvivid-custom-include-additional-folder-list';
                }

                var brepeat = false;
                jQuery('#'+parent_id).find('.'+list+' div').find('span:eq(2)').each(function (){
                    if (value === this.innerHTML) {
                        brepeat = true;
                    }
                });
                return brepeat;
            }

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-database-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-database-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
                init_staging_db_size('<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-base-database-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-base-database-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-base-database-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-file-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-file-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-file-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
                init_staging_file_size('<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-additional-folder-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-additional-folder-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-additional-folder-detail');
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-handle-tree-detail', function(){
                var handle_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-handle-tree-detail');
                var obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-tree-detail');
                var value = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-tree-selector').val();
                wpvivid_handle_custom_open_close_ex(handle_obj, obj, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('change', '.wpvivid-custom-tree-selector', function(){
                var value = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-tree-selector').val();
                jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-exclude-tree-info').jstree("destroy").empty();
                wpvivid_change_custom_exclude_info(value, '<?php echo $this->parent_id; ?>');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-refresh-include-tree', function(){

            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-refresh-exclude-tree', function(){

            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-include-additional-folder-btn', function(){
                var select_folders = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-additional-folder-tree-info').jstree(true).get_selected(true);
                var tree_path = '<?php echo $this->custom_additional_file_path; ?>';
                var list_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-include-additional-folder-list');
                var tree_type = 'additional-folder';

                jQuery.each(select_folders, function (index, select_item) {
                    if (select_item.id !== tree_path) {
                        var value = select_item.id;
                        value = value.replace(tree_path, '');
                        if (!wpvivid_check_tree_repeat(tree_type, value, '<?php echo $this->parent_id; ?>')) {
                            var class_name = select_item.icon;
                            if(class_name === 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer'){
                                var type = 'folder';
                            }
                            else{
                                var type = 'file';
                            }
                            var tr = "<div class='wpvivid-text-line' type='"+type+"'>" +
                                "<span class='dashicons dashicons-trash wpvivid-icon-16px wpvivid-remove-custom-exlcude-tree'></span>" +
                                "<span class='"+class_name+"'></span>" +
                                "<span class='wpvivid-text-line'>" + value + "</span>" +
                                "</div>";
                            list_obj.append(tr);
                        }
                    }
                });
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-custom-tree-exclude-btn', function(){
                var select_folders = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-exclude-tree-info').jstree(true).get_selected(true);
                var tree_type = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-tree-selector').val();
                var tree_path = path_arr[tree_type];
                if(tree_type === 'themes'){
                    var list_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-exclude-themes-list');
                }
                else if(tree_type === 'plugins'){
                    var list_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-exclude-plugins-list');
                }
                else if(tree_type === 'content'){
                    var list_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-exclude-content-list');
                }
                else if(tree_type === 'uploads'){
                    var list_obj = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-exclude-uploads-list');
                }

                jQuery.each(select_folders, function (index, select_item) {
                    if (select_item.id !== tree_path) {
                        var value = select_item.id;
                        value = value.replace(tree_path, '');
                        if (!wpvivid_check_tree_repeat(tree_type, value, '<?php echo $this->parent_id; ?>')) {
                            var class_name = select_item.icon;
                            if(class_name === 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer'){
                                var type = 'folder';
                            }
                            else{
                                var type = 'file';
                            }
                            var tr = "<div class='wpvivid-text-line' type='"+type+"'>" +
                                "<span class='dashicons dashicons-trash wpvivid-icon-16px wpvivid-remove-custom-exlcude-tree'></span>" +
                                "<span class='"+class_name+"'></span>" +
                                "<span class='wpvivid-text-line'>" + value + "</span>" +
                                "</div>";
                            list_obj.append(tr);
                        }
                    }
                });
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-remove-custom-exlcude-tree', function(){
                jQuery(this).parent().remove();
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-clear-custom-include-list', function(){
                jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-include-additional-folder-list').html('');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-clear-custom-exclude-list', function(){
                var tree_type = jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-custom-tree-selector').val();
                if(tree_type === 'themes'){
                    var list = 'wpvivid-custom-exclude-themes-list';
                }
                else if(tree_type === 'plugins'){
                    var list = 'wpvivid-custom-exclude-plugins-list';
                }
                else if(tree_type === 'content'){
                    var list = 'wpvivid-custom-exclude-content-list';
                }
                else if(tree_type === 'uploads'){
                    var list = 'wpvivid-custom-exclude-uploads-list';
                }
                jQuery('#<?php echo $this->parent_id; ?>').find('.'+list).html('');
            });

            jQuery('#<?php echo $this->parent_id; ?>').on('click', '.wpvivid-database-table-check', function(){
                if(jQuery(this).prop('checked')){
                    if(jQuery(this).hasClass('wpvivid-database-base-table-check')){
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=base_db][name=Database]').prop('checked', true);
                    }
                    else if(jQuery(this).hasClass('wpvivid-database-other-table-check')){
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=other_db][name=Database]').prop('checked', true);
                    }
                    else if(jQuery(this).hasClass('wpvivid-database-woo-table-check')){
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=woo_db][name=Database]').prop('checked', true);
                    }
                }
                else{
                    if (jQuery(this).hasClass('wpvivid-database-base-table-check')) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=base_db][name=Database]').prop('checked', false);
                    }
                    else if (jQuery(this).hasClass('wpvivid-database-other-table-check')) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=other_db][name=Database]').prop('checked', false);
                    }
                    else if (jQuery(this).hasClass('wpvivid-database-woo-table-check')) {
                        jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=woo_db][name=Database]').prop('checked', false);
                    }
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", 'input:checkbox[option=base_db][name=Database]', function(){
                if(jQuery(this).prop('checked')){
                    var all_check = true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=base_db][name=Database]').each(function(){
                        if(!jQuery(this).prop('checked')){
                            all_check = false;
                        }
                    });
                    if(all_check){
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-base-table-check').prop('checked', true);
                    }
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-base-table-check').prop('checked', false);
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", 'input:checkbox[option=other_db][name=Database]', function(){
                if(jQuery(this).prop('checked')){
                    var all_check = true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=other_db][name=Database]').each(function(){
                        if(!jQuery(this).prop('checked')){
                            all_check = false;
                        }
                    });
                    if(all_check){
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-other-table-check').prop('checked', true);
                    }
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-other-table-check').prop('checked', false);
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", 'input:checkbox[option=woo_db][name=Database]', function(){
                if(jQuery(this).prop('checked')){
                    var all_check = true;
                    jQuery('#<?php echo $this->parent_id; ?>').find('input:checkbox[option=woo_db][name=Database]').each(function(){
                        if(!jQuery(this).prop('checked')){
                            all_check = false;
                        }
                    });
                    if(all_check){
                        jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-woo-table-check').prop('checked', true);
                    }
                }
                else{
                    jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-database-woo-table-check').prop('checked', false);
                }
            });

            jQuery('#<?php echo $this->parent_id; ?>').on("click", '.wpvivid-calc-exclude-file-size', function(){
                var exclude_files = wpvivid_get_staging_exclude_json('<?php echo $this->parent_id; ?>');
                var custom_option = {
                    'exclude_files': exclude_files
                };
                custom_option = JSON.stringify(custom_option);

                var ajax_data = {
                    'action': 'wpvividstg_recalc_exclude_file_size',
                    'custom_option': custom_option
                };
                jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').html('. . .');
                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').show();
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').prev().show();
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').next().show();
                            jQuery('#<?php echo $this->parent_id; ?>').find('.wpvivid-exclude-file-size').html(jsonarray.total_file_size);
                            init_staging_file_size('<?php echo $this->parent_id; ?>');
                        }
                        else
                        {
                            wpvivid_staging_get_time_exceeded_exclude_file_size('<?php echo $this->parent_id; ?>');
                        }
                    }
                    catch (err)
                    {
                        wpvivid_staging_get_time_exceeded_exclude_file_size('<?php echo $this->parent_id; ?>');
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_staging_get_time_exceeded_exclude_file_size('<?php echo $this->parent_id; ?>');
                });
            });

            function wpvivid_get_database_size(){
                var ajax_data = {
                    'action': 'wpvividstg_get_custom_database_size'
                };
                wpvivid_post_request(ajax_data, function (data) {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result == 'success') {
                        jQuery('.wpvivid-database-size').html(jsonarray.database_size);
                    }
                    else {
                        alert(jsonarray.error);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown) {
                    var error_message = wpvivid_output_ajaxerror('retrieving the last backup log', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_get_files_size(){
                var ajax_data = {
                    'action': 'wpvividstg_get_custom_files_size'
                };
                wpvivid_post_request(ajax_data, function (data) {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result == 'success') {
                        jQuery('.wpvivid-core-size').html(jsonarray.core_size);
                        jQuery('.wpvivid-themes-size').html(jsonarray.themes_size);
                        jQuery('.wpvivid-plugins-size').html(jsonarray.plugins_size);
                        jQuery('.wpvivid-uploads-size').html(jsonarray.uploads_size);
                        jQuery('.wpvivid-content-size').html(jsonarray.content_size);
                        jQuery('.wpvivid-additional-folder-size').html(jsonarray.additional_size);
                        jQuery('.wpvivid-total-file-size').html(jsonarray.total_file_size);
                    }
                    else {
                        alert(jsonarray.error);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown) {
                    var error_message = wpvivid_output_ajaxerror('retrieving the last backup log', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            jQuery(document).ready(function () {
                //wpvivid_get_database_size();
                //wpvivid_get_files_size();
            });
        </script>
        <?php
    }
}

class WPvivid_Staging_UI_Display
{
    public $main_tab;

    public $staging_list_ui;
    public $staging_create_ui;
    public $fresh_install_ui;

    public function __construct()
    {
        add_filter('wpvivid_get_staging_menu', array($this, 'get_staging_menu'), 10, 2);
        add_action('wpvivid_staging_create_page_display', array($this, 'create_page_display'));

        include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-list-ui-display.php';
        include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-staging-create-ui-display.php';
        include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-fresh-install-create-ui-display.php';

        $this->staging_list_ui=new WPvivid_Staging_List_UI_Display();
        $this->staging_create_ui=new WPvivid_Staging_Create_UI_Display();
        $this->fresh_install_ui=new WPvivid_Fresh_Install_Create_UI_Display();
    }

    public function get_staging_menu($submenus, $parent_slug)
    {
        $submenu['parent_slug']=$parent_slug;
        $submenu['page_title']=__('WPvivid Staging');
        global $wpvivid_staging;
        $data=$wpvivid_staging->get_staging_site_data();
        if($data===false)
        {
            $submenu['menu_title']='Staging sites';
        }
        else
        {
            $submenu['menu_title']='Staging site';
        }

        $submenu['capability']='administrator';
        $submenu['menu_slug']=strtolower(sprintf('%s-staging', apply_filters('wpvivid_white_label_slug', 'wpvividstg')));//$menu['menu_slug'];
        $submenu['index']=1;
        $submenu['function']=array($this, 'create_page_display');
        $submenus[]=$submenu;

        return $submenus;
    }

    public static function wpvivid_check_site_url()
    {
        $site_url = site_url();
        $home_url = home_url();
        $site_url = untrailingslashit($site_url);
        $home_url = untrailingslashit($home_url);
        $db_site_url = '';
        $db_home_url = '';
        global $wpdb;
        $site_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'siteurl' ) );
        $home_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'home' ) );
        foreach ( $site_url_sql as $site ){
            $db_site_url = $site->option_value;
        }
        foreach ( $home_url_sql as $home ){
            $db_home_url = $home->option_value;
        }
        if($site_url !== $db_site_url || $home_url !== $db_home_url){
            _e('<div class="notice notice-warning inline" style="margin: 10px 10px 0 10px;"><p><strong>Warning:</strong> An inconsistency was detected between the site url, home url of the database and the actual website url. 
                                        This can cause inappropriate staging site url issues. Please change the site url and home url in the Options table of the database to the actual 
                                        url of your website. For example, if the site url and home url of the database is http://test.com, but the actual url of your website is https://test.com. 
                                        You’ll need to change the http to https.
                                                                  </p></div>');
        }
    }

    public static function wpvivid_check_login_url()
    {
        global $wpvivid_staging;
        $home_url = home_url();
        global $wpdb;
        $home_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'home' ) );
        foreach ( $home_url_sql as $home ){
            $home_url = $home->option_value;
        }
        $home_url = untrailingslashit($home_url);
        $login_url = $wpvivid_staging->get_login_url();
        $login_name = str_replace($home_url, '', $login_url);
        $login_name = trim($login_name, '/');
        if($login_name !== 'wp-login.php')
        {
            ?>
            <div class="notice notice-warning inline is-dismissible" style="margin: 10px 10px 0 10px;">
                <p>
                    <strong>Warning:</strong> We detected that the login url of your live site is not the default '/wp-login.php'. <a href="https://docs.wpvivid.com/wpvivid-staging-site-login-issue.html" target="_blank">Learn more</a>
                </p>
            </div>
            <?php
        }
    }

    public function create_page_display()
    {
        $options=get_option('wpvivid_staging_options',array());
        if(isset( $options['staging_request_timeout']))
        {
            $request_timeout=$options['staging_request_timeout'];
        }
        else
        {
            $request_timeout=WPVIVID_STAGING_DELAY_BETWEEN_REQUESTS;
        }
        ?>
        <div class="wrap" style="max-width:1720px;">
            <div class="wrap">
                <div id="icon-options-general" class="icon32"></div>
                <h1><?php esc_attr_e( apply_filters('wpvivid_white_label_display', 'WPvivid').' Plugins - Staging', 'WpvividPlugins' ); ?></h1>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <!-- main content -->
                        <div id="post-body-content">
                            <div class="meta-box-sortables ui-sortable">
                                <div class="wpvivid-backup">
                                    <?php
                                    global $wpvivid_staging;
                                    $data=$wpvivid_staging->get_staging_site_data();
                                    if($data===false){
                                        ?>
                                        <div class="wpvivid-welcome-bar wpvivid-clear-float">
                                            <div class="wpvivid-welcome-bar-left">
                                                <p><span class="dashicons dashicons-welcome-widgets-menus wpvivid-dashicons-large wpvivid-dashicons-green"></span><span class="wpvivid-page-title">Staging & Dev Environment</span></p>
                                                <span class="about-description">This page allows you to create a staging site and/or a fresh WordPress install, update a staging site, and push a staging site to live.</span>
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
                                                <span> Please temporarily deactivate cache plugins before creating a staging site to rule out possibilities of unknown failures.</span>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <script>
                                        function wpvivid_include_exclude_folder(type, parent_id, tree_path)
                                        {
                                            var select_folders = '';
                                            if (type === 'uploads')
                                            {
                                                select_folders = jQuery('#' + parent_id).find('.wpvivid-custom-uploads-tree-info').jstree(true).get_selected(true);
                                                var list_obj = jQuery('#' + parent_id).find('.wpvivid-custom-exclude-uploads-list');
                                            }
                                            if (type === 'content')
                                            {
                                                select_folders = jQuery('#' + parent_id).find('.wpvivid-custom-content-tree-info').jstree(true).get_selected(true);
                                                var list_obj = jQuery('#' + parent_id).find('.wpvivid-custom-exclude-content-list');
                                            }
                                            if (type === 'additional_file')
                                            {
                                                select_folders = jQuery('#' + parent_id).find('.wpvivid-custom-additional-file-tree-info').jstree(true).get_selected(true);
                                                var list_obj = jQuery('#' + parent_id).find('.wpvivid-custom-include-additional-file-list');
                                            }
                                            jQuery.each(select_folders, function (index, select_item)
                                            {
                                                if (select_item.id !== tree_path)
                                                {
                                                    var value = select_item.id;
                                                    value = value.replace(tree_path, '');
                                                    if (!wpvivid_check_custom_tree_repeat(type, value, parent_id))
                                                    {
                                                        var class_name = select_item.icon === 'jstree-folder' ? 'wpvivid-custom-li-folder-icon' : 'wpvivid-custom-li-file-icon';
                                                        var tr = "<ul style='margin: 0;'>" +
                                                            "<li>" +
                                                            "<div class='" + class_name + "'></div>" +
                                                            "<div class='wpvivid-custom-li-font'>" + value + "</div>" +
                                                            "<div class='wpvivid-custom-li-close' onclick='wpvivid_remove_custom_tree(this);' title='Remove' style='cursor: pointer;'>X</div>" +
                                                            "</li>" +
                                                            "</ul>";
                                                        list_obj.append(tr);
                                                    }
                                                }
                                            });
                                        }

                                        function wpvivid_check_custom_tree_repeat(type, value, parent_id)
                                        {
                                            var brepeat = false;
                                            var list_class = 'wpvivid-custom-exclude-uploads-list';
                                            if (type === 'uploads')
                                            {
                                                list_class = 'wpvivid-custom-exclude-uploads-list';
                                            }
                                            if (type === 'content')
                                            {
                                                list_class = 'wpvivid-custom-exclude-content-list';
                                            }
                                            if (type === 'additional_file')
                                            {
                                                list_class = 'wpvivid-custom-include-additional-file-list';
                                            }
                                            jQuery('#' + parent_id).find('.' + list_class + ' ul').find('li div:eq(1)').each(function ()
                                            {
                                                if (value === this.innerHTML)
                                                {
                                                    brepeat = true;
                                                }
                                            });
                                            return brepeat;
                                        }

                                        function wpvivid_remove_custom_tree(obj)
                                        {
                                            jQuery(obj).parent().parent().remove();
                                        }

                                        var staging_requet_timeout=<?php echo $request_timeout ?>;

                                        var archieve_info = {};
                                        archieve_info.src_db_retry    = 0;
                                        archieve_info.src_theme_retry = 0;
                                        archieve_info.des_db_retry    = 0;
                                        archieve_info.des_theme_retry = 0;

                                        function wpvivid_refresh_staging_database(parent_id, is_staging, staging_site_id) {
                                            if(is_staging == '1')
                                            {
                                                archieve_info.des_db_retry = 0;
                                            }
                                            else
                                            {
                                                archieve_info.src_db_retry = 0;
                                            }
                                            var custom_database_loading = '<div class="spinner" style="margin: 0 5px 10px 0; float: left;"></div>' +
                                                '<div style="float: left;">Archieving database tables</div>' +
                                                '<div style="clear: both;"></div>';
                                            jQuery('#' + parent_id).find('.wpvivid-custom-database-info').html('');
                                            jQuery('#' + parent_id).find('.wpvivid-custom-database-info').html(custom_database_loading);
                                            wpvivid_get_custom_database_tables_info(parent_id, is_staging, staging_site_id);
                                        }

                                        function wpvivid_get_custom_database_tables_info(parent_id, is_staging, staging_site_id) {
                                            var id = staging_site_id;

                                            var ajax_data = {
                                                'action': 'wpvividstg_get_custom_database_tables_info',
                                                'id': id,
                                                'is_staging': is_staging
                                            };
                                            wpvivid_post_request(ajax_data, function (data)
                                            {
                                                var jsonarray = jQuery.parseJSON(data);
                                                if (jsonarray.result === 'success')
                                                {
                                                    jQuery('#' + parent_id).find('.wpvivid-custom-database-info').html('');
                                                    jQuery('#' + parent_id).find('.wpvivid-custom-database-info').html(jsonarray.html);
                                                }
                                            }, function (XMLHttpRequest, textStatus, errorThrown)
                                            {
                                                var need_retry_custom_database = false;
                                                var retry_times=0;
                                                if(is_staging == '1')
                                                {
                                                    archieve_info.des_db_retry++;
                                                    retry_times = archieve_info.des_db_retry;
                                                }
                                                else{
                                                    archieve_info.src_db_retry++;
                                                    retry_times = archieve_info.src_db_retry;
                                                }
                                                if(retry_times < 10){
                                                    need_retry_custom_database = true;
                                                }
                                                if(need_retry_custom_database)
                                                {
                                                    setTimeout(function()
                                                    {
                                                        wpvivid_get_custom_database_tables_info(parent_id, is_staging, staging_site_id);
                                                    }, 3000);
                                                }
                                                else{
                                                    var refresh_btn = '<input type="submit" class="button-primary" value="Refresh" onclick="wpvivid_refresh_staging_database(\''+parent_id+'\', \''+is_staging+'\', \''+staging_site_id+'\');">';
                                                    jQuery('#' + parent_id).find('.wpvivid-custom-database-info').html('');
                                                    jQuery('#' + parent_id).find('.wpvivid-custom-database-info').html(refresh_btn);
                                                }
                                            });
                                        }

                                        function wpvivid_init_custom_include_tree(tree_path, is_staging, parent_id, refresh=0) {
                                            if (refresh) {
                                                jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-tree-info').jstree("refresh");
                                            }
                                            else {
                                                jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-tree-info').on('activate_node.jstree', function (e, data) {
                                                }).jstree({
                                                    "core": {
                                                        "check_callback": true,
                                                        "multiple": true,
                                                        "data": function (node_id, callback) {
                                                            var tree_node = {
                                                                'node': node_id,
                                                                'path': tree_path
                                                            };
                                                            var ajax_data = {
                                                                'action': 'wpvividstg_get_custom_include_path',
                                                                'tree_node': tree_node,
                                                                'is_staging': is_staging
                                                            };
                                                            ajax_data.nonce=wpvivid_ajax_object.ajax_nonce;
                                                            jQuery.ajax({
                                                                type: "post",
                                                                url: wpvivid_ajax_object.ajax_url,
                                                                data: ajax_data,
                                                                success: function (data) {
                                                                    var jsonarray = jQuery.parseJSON(data);
                                                                    callback.call(this, jsonarray.nodes);
                                                                    jQuery('#'+parent_id).find('.wpvivid-include-additional-folder-btn').attr('disabled', false);
                                                                },
                                                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                                    alert("error");
                                                                },
                                                                timeout: 30000
                                                            });
                                                        },
                                                        'themes': {
                                                            'stripes': true
                                                        }
                                                    }
                                                });
                                            }
                                        }

                                        function wpvivid_init_custom_exclude_tree(tree_path, is_staging, parent_id, refresh=0) {
                                            if (refresh) {
                                                jQuery('#'+parent_id).find('.wpvivid-custom-exclude-tree-info').jstree("refresh");
                                            }
                                            else {
                                                jQuery('#'+parent_id).find('.wpvivid-custom-exclude-tree-info').on('activate_node.jstree', function (event, data) {
                                                }).jstree({
                                                    "core": {
                                                        "check_callback": true,
                                                        "multiple": true,
                                                        "data": function (node_id, callback) {
                                                            var tree_node = {
                                                                'node': node_id,
                                                                'path': tree_path
                                                            };
                                                            var ajax_data = {
                                                                'action': 'wpvividstg_get_custom_exclude_path',
                                                                'tree_node': tree_node,
                                                                'is_staging': is_staging
                                                            };
                                                            ajax_data.nonce=wpvivid_ajax_object.ajax_nonce;
                                                            jQuery.ajax({
                                                                type: "post",
                                                                url: wpvivid_ajax_object.ajax_url,
                                                                data: ajax_data,
                                                                success: function (data) {
                                                                    var jsonarray = jQuery.parseJSON(data);
                                                                    callback.call(this, jsonarray.nodes);
                                                                    jQuery('#'+parent_id).find('.wpvivid-custom-tree-exclude-btn').attr('disabled', false);
                                                                },
                                                                error: function (XMLHttpRequest, textStatus, errorThrown) {
                                                                    alert("error");
                                                                },
                                                                timeout: 30000
                                                            });
                                                        },
                                                        'themes': {
                                                            'stripes': true
                                                        }
                                                    }
                                                });
                                            }
                                        }

                                        function load_js(parent_id, is_staging, theme_path, plugin_path, upload_path, content_path, home_path, staging_site_id = '')
                                        {
                                            var tree_path = theme_path;

                                            var path_arr = {};
                                            path_arr['core'] = home_path;
                                            path_arr['content'] = content_path;
                                            path_arr['uploads'] = upload_path;
                                            path_arr['themes'] = theme_path;
                                            path_arr['plugins'] = plugin_path;

                                            jQuery('#'+parent_id).on('click', '.wpvivid-handle-additional-folder-detail', function(){
                                                wpvivid_init_custom_include_tree(home_path, is_staging, parent_id);
                                            });

                                            jQuery('#'+parent_id).on('click', '.wpvivid-refresh-include-tree', function(){
                                                wpvivid_init_custom_include_tree(home_path, is_staging, parent_id, 1);
                                            });

                                            jQuery('#'+parent_id).on('click', '.wpvivid-handle-tree-detail', function(){
                                                var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                                                if(value === 'themes'){
                                                    tree_path = theme_path;
                                                }
                                                else if(value === 'plugins'){
                                                    tree_path = plugin_path;
                                                }
                                                else if(value === 'content'){
                                                    tree_path = content_path;
                                                }
                                                else if(value === 'uploads'){
                                                    tree_path = upload_path;
                                                }
                                                wpvivid_init_custom_exclude_tree(tree_path, is_staging, parent_id);
                                            });

                                            jQuery('#'+parent_id).on('change', '.wpvivid-custom-tree-selector', function(){
                                                var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                                                if(value === 'themes'){
                                                    tree_path = theme_path;
                                                }
                                                else if(value === 'plugins'){
                                                    tree_path = plugin_path;
                                                }
                                                else if(value === 'content'){
                                                    tree_path = content_path;
                                                }
                                                else if(value === 'uploads'){
                                                    tree_path = upload_path;
                                                }
                                                jQuery('#'+parent_id).find('.wpvivid-custom-exclude-tree-info').jstree("destroy").empty();
                                                wpvivid_init_custom_exclude_tree(tree_path, is_staging, parent_id);
                                            });

                                            jQuery('#'+parent_id).on('click', '.wpvivid-refresh-exclude-tree', function(){
                                                var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                                                if(value === 'themes'){
                                                    tree_path = theme_path;
                                                }
                                                else if(value === 'plugins'){
                                                    tree_path = plugin_path;
                                                }
                                                else if(value === 'content'){
                                                    tree_path = content_path;
                                                }
                                                else if(value === 'uploads'){
                                                    tree_path = upload_path;
                                                }
                                                wpvivid_init_custom_exclude_tree(tree_path, is_staging, parent_id, 1);
                                            });

                                            jQuery('#'+parent_id).on('click', '.wpvivid-custom-tree-exclude-btn', function(){
                                                var select_folders = jQuery('#'+parent_id).find('.wpvivid-custom-exclude-tree-info').jstree(true).get_selected(true);
                                                var tree_type = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                                                var tree_path = path_arr[tree_type];
                                                if(tree_type === 'themes'){
                                                    var list_obj = jQuery('#'+parent_id).find('.wpvivid-custom-exclude-themes-list');
                                                }
                                                else if(tree_type === 'plugins'){
                                                    var list_obj = jQuery('#'+parent_id).find('.wpvivid-custom-exclude-plugins-list');
                                                }
                                                else if(tree_type === 'content'){
                                                    var list_obj = jQuery('#'+parent_id).find('.wpvivid-custom-exclude-content-list');
                                                }
                                                else if(tree_type === 'uploads'){
                                                    var list_obj = jQuery('#'+parent_id).find('.wpvivid-custom-exclude-uploads-list');
                                                }

                                                jQuery.each(select_folders, function (index, select_item) {
                                                    if (select_item.id !== tree_path) {
                                                        var value = select_item.id;
                                                        value = value.replace(tree_path, '');
                                                        if (!wpvivid_check_tree_repeat(tree_type, value, parent_id)) {
                                                            var class_name = select_item.icon;
                                                            if(class_name === 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer'){
                                                                var type = 'folder';
                                                            }
                                                            else{
                                                                var type = 'file';
                                                            }
                                                            var tr = "<div class='wpvivid-text-line' type='"+type+"'>" +
                                                                "<span class='dashicons dashicons-trash wpvivid-icon-16px wpvivid-remove-custom-exlcude-tree'></span>" +
                                                                "<span class='"+class_name+"'></span>" +
                                                                "<span class='wpvivid-text-line'>" + value + "</span>" +
                                                                "</div>";
                                                            list_obj.append(tr);
                                                        }
                                                    }
                                                });
                                            });

                                            if(is_staging){
                                                is_staging = '1';
                                            }
                                            else{
                                                is_staging = '0';
                                            }
                                            wpvivid_get_custom_database_tables_info(parent_id, is_staging, staging_site_id);
                                        }
                                    </script>

                                    <?php self::wpvivid_check_site_url(); ?>

                                    <?php self::wpvivid_check_login_url(); ?>

                                    <div class="wpvivid-canvas wpvivid-clear-float">
                                        <?php
                                        if(!class_exists('WPvivid_Tab_Page_Container_Addon'))
                                            include_once WPVIVID_STAGING_PLUGIN_DIR . 'includes/class-wpvivid-tab-page-container-addon.php';
                                        $this->main_tab=new WPvivid_Tab_Page_Container_Addon();
                                        global $wpvivid_staging;
                                        $data=$wpvivid_staging->get_staging_site_data();

                                        if($data===false)
                                        {
                                            $args['span_class']='dashicons dashicons-admin-multisite wpvivid-dashicons-orange';
                                            $args['span_style']='color:orange; padding-right:0.5em;margin-top:0.1em;';
                                            $args['div_style']='display:block;';
                                            $args['is_parent_tab']=0;
                                            $tabs['staging_sites']['title']='Staging Sites';
                                            $tabs['staging_sites']['slug']='staging_sites';
                                            $tabs['staging_sites']['callback']=array($this->staging_list_ui, 'output_staging_sites_list_page_ex');
                                            $tabs['staging_sites']['args']=$args;

                                            $args['span_class']='dashicons dashicons-welcome-write-blog wpvivid-dashicons-blue';
                                            $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                            $args['div_style']='';
                                            $args['hide']=1;
                                            $args['can_delete']=1;
                                            $args['redirect']='staging_sites';
                                            $tabs['create_staging']['title']='Create A Staging Site';
                                            $tabs['create_staging']['slug']='create_staging';
                                            $tabs['create_staging']['callback']=array($this->staging_create_ui, 'output_create_staging_site_page');
                                            $tabs['create_staging']['args']=$args;


                                            if(!is_multisite())
                                            {
                                                $args['span_class']='dashicons dashicons-welcome-write-blog wpvivid-dashicons-blue';
                                                $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                                $args['div_style']='';
                                                $args['hide']=1;
                                                $args['can_delete']=1;
                                                $args['redirect']='staging_sites';
                                                $tabs['create_fresh_install']['title']='Create A Fresh WP Install';
                                                $tabs['create_fresh_install']['slug']='create_fresh_install';
                                                $tabs['create_fresh_install']['callback']=array($this->fresh_install_ui, 'output_create_wp_page');
                                                $tabs['create_fresh_install']['args']=$args;

                                                $args['span_class']='dashicons dashicons-migrate wpvivid-dashicons-blue';
                                                $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                                $args['div_style']='';
                                                $args['hide']=1;
                                                $args['can_delete']=1;
                                                $args['redirect']='staging_sites';
                                                $tabs['merging_setup']['title']='Setup Merging Dev Environment';
                                                $tabs['merging_setup']['slug']='merging_setup';
                                                $tabs['merging_setup']['callback']=array($this, 'output_merging_setup');
                                                $tabs['merging_setup']['args']=$args;
                                            }

                                            /*
                                            $args['span_class']='dashicons dashicons-welcome-write-blog wpvivid-dashicons-blue';
                                            $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                            $args['div_style']='';
                                            $args['hide']=1;
                                            $tabs['import_staging']['title']='Import A Exist Staging Site';
                                            $tabs['import_staging']['slug']='import_staging';
                                            $tabs['import_staging']['callback']=array($this->staging_create_ui, 'output_import_staging_site_page');
                                            $tabs['import_staging']['args']=$args;*/

                                        }
                                        else
                                        {
                                            $args['span_class']='dashicons dashicons-admin-multisite wpvivid-dashicons-orange';
                                            $args['span_style']='color:orange; padding-right:0.5em;margin-top:0.1em;';
                                            $args['div_style']='display:block;';
                                            $args['is_parent_tab']=0;
                                            $tabs['staging_sites']['title']='Staging Sites';
                                            $tabs['staging_sites']['slug']='staging_sites';
                                            $tabs['staging_sites']['callback']=array($this->staging_list_ui, 'output_staging');
                                            $tabs['staging_sites']['args']=$args;
                                        }

                                        global $wpvivid_staging;

                                        $args['div_style']='';
                                        $args['span_class']='dashicons dashicons-admin-generic wpvivid-dashicons-blue';
                                        $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                        unset($args['hide']);
                                        unset($args['can_delete']);
                                        unset($args['redirect']);
                                        $tabs['staging_setting']['title']='Staging Settings';
                                        $tabs['staging_setting']['slug']='staging_setting';
                                        $tabs['staging_setting']['callback']=array($wpvivid_staging->setting, 'wpvivid_setting_add_staging_cell_addon');
                                        $tabs['staging_setting']['args']=$args;

                                        $args['span_class']='dashicons dashicons-welcome-write-blog wpvivid-dashicons-grey';
                                        $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                        $tabs['staging_log']['title']='Staging Log';
                                        $tabs['staging_log']['slug']='staging_log';
                                        $tabs['staging_log']['callback']=array($wpvivid_staging->log_page, 'output_staging_log_list');
                                        $tabs['staging_log']['args']=$args;

                                        $args['span_class']='';
                                        $args['span_style']='';
                                        $args['can_delete']=1;
                                        $args['hide']=1;
                                        $tabs['open_log']['title']='Log';
                                        $tabs['open_log']['slug']='open_log';
                                        $tabs['open_log']['callback']=array($wpvivid_staging->log_page, 'output_log');
                                        $tabs['open_log']['args']=$args;

                                        foreach ($tabs as $key=>$tab)
                                        {
                                            $this->main_tab->add_tab($tab['title'],$tab['slug'],$tab['callback'], $tab['args']);
                                        }
                                        $this->main_tab->display();
                                        ?>
                                        <script>
                                            function switch_staging_tab(id)
                                            {
                                                jQuery( document ).trigger( '<?php echo $this->main_tab->container_id ?>-show',id);
                                            }
                                        </script>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- side bar -->
                        <?php
                        if(apply_filters('wpvivid_show_sidebar',true))
                        {
                            ?>
                            <div id="postbox-container-1" class="postbox-container">
                                <div class="meta-box-sortables ui-sortable">
                                    <div class="postbox  wpvivid-sidebar">
                                        <?php
                                        if(has_filter('wpvivid_add_staging_side_bar')){
                                            $side_bar = '1';
                                        }
                                        else{
                                            $side_bar = '0';
                                        }
                                        $side_bar = apply_filters('wpvivid_add_staging_side_bar', $side_bar, false);
                                        echo $side_bar;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>

        <script>
            function wpvivid_open_log(log,slug)
            {
                var ajax_data = {
                    'action':'wpvividstg_view_log_ex',
                    'log': log
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    jQuery('#wpvivid_read_log_content').html("");
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === "success")
                        {
                            jQuery( document ).trigger( '<?php echo $this->main_tab->container_id ?>-show',[ 'open_log', slug ]);
                            var log_data = jsonarray.data;
                            while (log_data.indexOf('\n') >= 0)
                            {
                                var iLength = log_data.indexOf('\n');
                                var log = log_data.substring(0, iLength);
                                log_data = log_data.substring(iLength + 1);
                                var insert_log = "<div style=\"clear:both;\">" + log + "</div>";
                                jQuery('#wpvivid_read_log_content').append(insert_log);
                            }
                        }
                        else
                        {
                            jQuery('#wpvivid_read_log_content').html(jsonarray.error);
                        }
                    }
                    catch(err)
                    {
                        alert(err);
                        var div = "Reading the log failed. Please try again.";
                        jQuery('#wpvivid_read_log_content').html(div);
                    }
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('export the previously-exported settings', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function init_staging_db_file_size(parent_id)
            {
                var ajax_data = {
                    'action': 'wpvividstg_get_need_recalc_website_size'
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            if(jsonarray.need_recalc)
                            {
                                init_staging_db_size(parent_id);
                                init_staging_file_size(parent_id);
                                init_exclude_file_size(parent_id);
                            }
                        }
                    }
                    catch (err)
                    {
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                });
            }

            function init_exclude_file_size(parent_id)
            {
                var exclude_files = wpvivid_get_staging_exclude_json(parent_id);
                var custom_option = {
                    'exclude_files': exclude_files
                };
                custom_option = JSON.stringify(custom_option);

                var ajax_data = {
                    'action': 'wpvividstg_recalc_exclude_file_size',
                    'custom_option': custom_option
                };
                jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').html('. . .');
                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').show();
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').prev().show();
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').next().show();
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').html(jsonarray.total_file_size);
                        }
                        else
                        {
                            wpvivid_staging_get_time_exceeded_exclude_file_size(parent_id);
                        }
                    }
                    catch (err)
                    {
                        wpvivid_staging_get_time_exceeded_exclude_file_size(parent_id);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_staging_get_time_exceeded_exclude_file_size(parent_id);
                });
            }

            function init_staging_db_size(parent_id)
            {
                var custom_dirs = wpvivid_get_custom_setting_json_ex(parent_id);
                var exclude_files = wpvivid_get_staging_exclude_json(parent_id);
                var custom_option = {
                    'custom_dirs': custom_dirs,
                    'exclude_files': exclude_files
                };
                custom_option = JSON.stringify(custom_option);

                var website_item_arr = new Array('database');
                var total_file_size = 0;

                wpvivid_staging_recalc_backup_size_ex(website_item_arr, custom_option, parent_id, 'database', total_file_size);
            }

            function init_staging_file_size(parent_id)
            {
                var custom_dirs = wpvivid_get_custom_setting_json_ex(parent_id);
                var exclude_files = wpvivid_get_staging_exclude_json(parent_id);
                var custom_option = {
                    'custom_dirs': custom_dirs,
                    'exclude_files': exclude_files
                };
                custom_option = JSON.stringify(custom_option);

                var website_item_arr = new Array('core', 'content', 'themes', 'plugins', 'uploads');
                var total_file_size = 0;

                wpvivid_staging_recalc_backup_size_ex(website_item_arr, custom_option, parent_id, 'file', total_file_size);
            }

            function wpvivid_staging_recalc_backup_size_ex(website_item_arr, custom_option, parent_id, what, total_file_size)
            {
                if(website_item_arr.length > 0)
                {
                    var website_item = website_item_arr.shift();
                    var ajax_data = {
                        'action': 'wpvividstg_recalc_backup_size_ex',
                        'website_item': website_item,
                        'custom_option': custom_option
                    };
                    jQuery('#'+parent_id).find('.wpvivid-total-file-size').html('. . .');
                    wpvivid_post_request(ajax_data, function (data)
                    {
                        try
                        {
                            var jsonarray = jQuery.parseJSON(data);
                            if (jsonarray.result === 'success')
                            {
                                if(website_item === 'database')
                                {
                                    jQuery('#'+parent_id).find('.wpvivid-database-size').html(jsonarray.database_size);
                                }
                                if(website_item === 'core')
                                {
                                    jQuery('#'+parent_id).find('.wpvivid-core-size').html(jsonarray.core_size);
                                }
                                if(website_item === 'content')
                                {
                                    jQuery('#'+parent_id).find('.wpvivid-content-size').html(jsonarray.content_size);
                                }
                                if(website_item === 'themes')
                                {
                                    jQuery('#'+parent_id).find('.wpvivid-themes-size').html(jsonarray.themes_size);
                                }
                                if(website_item === 'plugins')
                                {
                                    jQuery('#'+parent_id).find('.wpvivid-plugins-size').html(jsonarray.plugins_size);
                                }
                                if(website_item === 'uploads')
                                {
                                    jQuery('#'+parent_id).find('.wpvivid-uploads-size').html(jsonarray.uploads_size);
                                }
                                wpvivid_staging_recalc_backup_size_ex(website_item_arr, custom_option, parent_id, what, jsonarray.total_file_size);
                            }
                            else
                            {
                                wpvivid_staging_get_time_exceeded_size(website_item, parent_id);
                                wpvivid_staging_recalc_backup_size_ex(website_item_arr, custom_option, parent_id, what, total_file_size);
                            }
                        }
                        catch (err)
                        {
                            wpvivid_staging_get_time_exceeded_size(website_item, parent_id);
                            wpvivid_staging_recalc_backup_size_ex(website_item_arr, custom_option, parent_id, what, total_file_size);
                        }
                    }, function (XMLHttpRequest, textStatus, errorThrown)
                    {
                        wpvivid_staging_get_time_exceeded_size(website_item, parent_id);
                        wpvivid_staging_recalc_backup_size_ex(website_item_arr, custom_option, parent_id, what, total_file_size);
                    });
                }
                else
                {
                    if(what === 'database')
                    {
                    }
                    else if(what === 'file')
                    {
                        jQuery('#'+parent_id).find('.wpvivid-total-file-size').html(total_file_size);
                    }
                }
            }

            function wpvivid_staging_get_time_exceeded_size(website_item, parent_id)
            {
                var ajax_data = {
                    'action': 'wpvividstg_get_time_exceeded_size',
                    'website_item': website_item
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            if(website_item === 'database')
                            {
                                jQuery('#'+parent_id).find('.wpvivid-database-size').html('Large than '+jsonarray.curr_size);
                            }
                            if(website_item === 'core')
                            {
                                jQuery('#'+parent_id).find('.wpvivid-core-size').html('Large than '+jsonarray.curr_size);
                            }
                            if(website_item === 'content')
                            {
                                jQuery('#'+parent_id).find('.wpvivid-content-size').html('Large than '+jsonarray.curr_size);
                            }
                            if(website_item === 'themes')
                            {
                                jQuery('#'+parent_id).find('.wpvivid-themes-size').html('Large than '+jsonarray.curr_size);
                            }
                            if(website_item === 'plugins')
                            {
                                jQuery('#'+parent_id).find('.wpvivid-plugins-size').html('Large than '+jsonarray.curr_size);
                            }
                            if(website_item === 'uploads')
                            {
                                jQuery('#'+parent_id).find('.wpvivid-uploads-size').html('Large than '+jsonarray.curr_size);
                            }
                        }
                    }
                    catch (err)
                    {
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                });
            }

            function wpvivid_staging_get_time_exceeded_exclude_file_size(parent_id)
            {
                var ajax_data = {
                    'action': 'wpvividstg_get_time_exceeded_exclude_file_size'
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').show();
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').prev().show();
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').next().show();
                            jQuery('#'+parent_id).find('.wpvivid-exclude-file-size').html('Large than '+jsonarray.curr_size);
                        }
                    }
                    catch (err)
                    {
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                });
            }

            function wpvivid_get_staging_custom_database(parent_id)
            {
                var json = {};
                //database
                json['database_check'] = '0';
                json['exclude-tables'] = Array();
                json['include-tables'] = Array();
                if(jQuery('#'+parent_id).find('.wpvivid-custom-database-check').prop('checked')){
                    json['database_check'] = '1';
                }
                jQuery('#'+parent_id).find('input[option=base_db][type=checkbox]').each(function(index, value){
                    if(!jQuery(value).prop('checked')){
                        json['exclude-tables'].push(jQuery(value).val());
                    }
                });
                jQuery('#'+parent_id).find('input[option=other_db][type=checkbox]').each(function(index, value){
                    if(!jQuery(value).prop('checked')){
                        json['exclude-tables'].push(jQuery(value).val());
                    }
                });
                jQuery('#'+parent_id).find('input[option=woo_db][type=checkbox]').each(function(index, value){
                    if(jQuery(value).prop('checked')){
                        json['include-tables'].push(jQuery(value).val());
                    }
                });

                //additional database
                json['additional_database_check'] = '0';
                json['additional_database_list'] = {};
                if(jQuery('#'+parent_id).find('.wpvivid-custom-additional-database-check').prop('checked')){
                    json['additional_database_check'] = '1';
                }
                jQuery('#'+parent_id).find('.wpvivid-additional-database-list').find('div').each(function(index, value){
                    var database_name = jQuery(this).attr('database-name');
                    var database_host = jQuery(this).attr('database-host');
                    var database_user = jQuery(this).attr('database-user');
                    var database_pass = jQuery(this).attr('database-pass');
                    json['additional_database_list'][database_name] = {};
                    json['additional_database_list'][database_name]['db_host'] = database_host;
                    json['additional_database_list'][database_name]['db_user'] = database_user;
                    json['additional_database_list'][database_name]['db_pass'] = database_pass;
                });
                return json;
            }

            function wpvivid_get_custom_setting_json_ex(parent_id)
            {
                var json = {};
                //core
                json['core_check'] = '0';
                if(jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked')){
                    json['core_check'] = '1';
                }

                //themes
                json['themes_check'] = '0';
                if(jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked')){
                    json['themes_check'] = '1';
                }

                //plugins
                json['plugins_check'] = '0';
                if(jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked')){
                    json['plugins_check'] = '1';
                }

                //content
                json['content_check'] = '0';
                if(jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked')){
                    json['content_check'] = '1';
                }

                //uploads
                json['uploads_check'] = '0';
                if(jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked')){
                    json['uploads_check'] = '1';
                }

                //additional folders/files
                json['other_check'] = '0';
                json['other_list'] = [];
                if(jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                    json['other_check'] = '1';
                }
                if(json['other_check'] == '1'){
                    jQuery('#'+parent_id).find('.wpvivid-custom-include-additional-folder-list div').find('span:eq(2)').each(function (){
                        var folder_name = this.innerHTML;
                        json['other_list'].push(folder_name);
                    });
                }

                //database
                json['database_check'] = '0';
                json['exclude-tables'] = Array();
                json['include-tables'] = Array();
                if(jQuery('#'+parent_id).find('.wpvivid-custom-database-check').prop('checked')){
                    json['database_check'] = '1';
                }
                jQuery('#'+parent_id).find('input[option=base_db][type=checkbox]').each(function(index, value){
                    if(!jQuery(value).prop('checked')){
                        json['exclude-tables'].push(jQuery(value).val());
                    }
                });
                jQuery('#'+parent_id).find('input[option=other_db][type=checkbox]').each(function(index, value){
                    if(!jQuery(value).prop('checked')){
                        json['exclude-tables'].push(jQuery(value).val());
                    }
                });
                jQuery('#'+parent_id).find('input[option=woo_db][type=checkbox]').each(function(index, value){
                    if(jQuery(value).prop('checked')){
                        json['include-tables'].push(jQuery(value).val());
                    }
                });

                //additional database
                json['additional_database_check'] = '0';
                json['additional_database_list'] = {};
                if(jQuery('#'+parent_id).find('.wpvivid-custom-additional-database-check').prop('checked')){
                    json['additional_database_check'] = '1';
                }
                jQuery('#'+parent_id).find('.wpvivid-additional-database-list').find('div').each(function(index, value){
                    var database_name = jQuery(this).attr('database-name');
                    var database_host = jQuery(this).attr('database-host');
                    var database_user = jQuery(this).attr('database-user');
                    var database_pass = jQuery(this).attr('database-pass');
                    json['additional_database_list'][database_name] = {};
                    json['additional_database_list'][database_name]['db_host'] = database_host;
                    json['additional_database_list'][database_name]['db_user'] = database_user;
                    json['additional_database_list'][database_name]['db_pass'] = database_pass;
                });

                return json;
            }

            function wpvivid_get_staging_exclude_json(parent_id)
            {
                var json = {};
                json['themes']  = [];
                json['plugins'] = [];
                json['uploads'] = [];
                json['content'] = [];

                var type_array = new Array('content', 'themes', 'plugins', 'uploads');
                jQuery.each(type_array, function (index, value)
                {
                    if(value === 'themes')
                    {
                        var list_name = 'wpvivid-custom-exclude-themes-list';
                    }
                    else if(value === 'plugins')
                    {
                        var list_name = 'wpvivid-custom-exclude-plugins-list';
                    }
                    else if(value === 'uploads')
                    {
                        var list_name = 'wpvivid-custom-exclude-uploads-list';
                    }
                    else if(value === 'content')
                    {
                        var list_name = 'wpvivid-custom-exclude-content-list';
                    }

                    jQuery('#'+parent_id).find('.'+list_name+' div').find('span:eq(2)').each(function ()
                    {
                        var item={};
                        item['path']=this.innerHTML;
                        var type = jQuery(this).closest('div').attr('type');
                        item['type']=type;
                        json[value].push(item);
                    });
                });

                return json;
            }
        </script>
        <?php
    }

    public function output_merging_setup()
    {
        do_action('wpvivid_merging_setup_page');
    }
}