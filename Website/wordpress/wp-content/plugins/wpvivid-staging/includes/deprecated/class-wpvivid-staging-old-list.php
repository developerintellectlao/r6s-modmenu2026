<?php
/*
    public function output_staging_sites_list_page()
    {
        ?>
        <div class="wpvivid-one-coloum" style="border:1px solid #f1f1f1;padding-top:0em;padding-bottom:0em;">
            <div class="wpvivid-two-col">
                <ul class="">
                    <li>
                        <input type="button" class="button button-primary" id="wpvivid_switch_create_staging_page" value="Create A Staging Site">
                        <p>Click to start creating a staging site.
                    </li>
                </ul>
            </div>

            <?php
            if(!is_multisite()){
                ?>
                <div class="wpvivid-two-col">
                    <ul class="">
                        <li>
                            <input type="button" class="button button-primary" id="wpvivid_switch_create_fresh_install_page" value="Create A Fresh WP Site">
                            <p>Click to start creating a fresh WP install.
                        </li>
                    </ul>
                </div>
                <?php
            }
            ?>

            <!--<div>
                <ul class="">
                    <li>
                        <input type="button" class="button button-primary" id="wpvivid_scan_exist_staging_page" value="Import A Exist Staging Site">
                        <p>Click to start importing a staging site.</p>
                    </li>
                </ul>
            </div>-->
            <script>
                jQuery('#wpvivid_scan_exist_staging_page').click(function()
                {
                    switch_staging_tab('import_staging');
                });

                jQuery('#wpvivid_switch_create_staging_page').click(function(){
                    switch_staging_tab('create_staging');
                    init_staging_create();

                    <?php
                    if(is_multisite())
                    {
                        ?>
                        init_staging_db_file_size('wpvivid_custom_mu_staging_list');
                        <?php
                    }
                    else
                    {
                        ?>
                        init_staging_db_file_size('wpvivid_custom_staging_list');
                        <?php
                    }
                    ?>
                });

                jQuery('#wpvivid_switch_create_fresh_install_page').click(function(){
                    switch_staging_tab('create_fresh_install');
                    init_fresh_install_themes_plugins();
                });
            </script>
            <div style="clear: both;"></div>
        </div>


        <div id="wpvivid_staging_list">
            <?php
            //$list = get_option('wpvivid_staging_task_list',array());
            $list = $this->check_site_list();
            if($list === false)
            {
                $list = array();
            }
            if(!empty($list))
            {
                foreach ($list as $id => $staging)
                {
                    if(isset($staging['path']) && !empty($staging['path']))
                    {
                        $staging_site_name = basename($staging['path']);
                    }
                    else{
                        $staging_site_name = 'N/A';
                    }

                    $home_url = home_url();
                    global $wpdb;
                    $home_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'home' ) );
                    foreach ( $home_url_sql as $home )
                    {
                        $home_url = $home->option_value;
                    }
                    $home_url = untrailingslashit($home_url);

                    if(is_multisite())
                    {
                        if(isset($staging['mu_single']))
                        {
                            $admin_url =admin_url();
                        }
                        else
                        {
                            $admin_url = network_admin_url();
                        }
                    }
                    else if(isset($staging['mu_single']))
                    {
                        $admin_url =admin_url();
                    }
                    else
                    {
                        $admin_url =admin_url();
                    }

                    if(!isset($staging['login_url']))
                    {
                        $admin_name = str_replace($home_url, '', $admin_url);
                        $admin_name = trim($admin_name, '/');
                        $admin_url_descript = 'Admin URL';
                    }
                    else
                    {
                        $login_url = $staging['login_url'];
                        $login_name = str_replace($home_url, '', $login_url);
                        $login_name = trim($login_name, '/');
                        if(isset($staging['fresh_install'])&& $staging['fresh_install'])
                        {
                            $fresh_install=true;
                        }
                        else
                        {
                            $fresh_install=false;
                        }

                        if($login_name !== 'wp-login.php' && !$fresh_install)
                        {
                            $admin_name = $login_name;
                            $admin_url_descript = 'Login URL';
                        }
                        else
                        {
                            $admin_name = str_replace($home_url, '', $admin_url);
                            $admin_name = trim($admin_name, '/');
                            $admin_url_descript = 'Admin URL';
                        }
                    }

                    if(isset($staging['home_url']) && !empty($staging['home_url']))
                    {
                        $site_url = esc_url($staging['home_url']);
                        $admin_url = esc_url($staging['home_url'].'/'.$admin_name.'/');
                    }
                    else{
                        $site_url = 'N/A';
                        $admin_url = 'N/A';
                    }

                    if(isset($staging['prefix']) && !empty($staging['prefix'])){
                        $prefix = $staging['prefix'];
                        if(isset($staging['db_connect']['dbname']) && !empty($staging['db_connect']['dbname'])){
                            $db_name = $staging['db_connect']['dbname'];
                        }
                        else{
                            $db_name = DB_NAME;
                        }
                    }
                    else{
                        $prefix = 'N/A';
                        $db_name = 'N/A';
                    }
                    if(isset($staging['path']) && !empty($staging['path'])){
                        $site_dir = $staging['path'];
                    }
                    else{
                        $site_dir = 'N/A';
                    }

                    if(isset($staging['fresh_install'])&& $staging['fresh_install'])
                    {
                        $copy_btn='Copy the Fresh Install to Live';
                        $update_btn='Update the Fresh Install';
                        $class_btn='fresh-install';
                    }
                    else
                    {
                        $copy_btn='Copy the Staging Site to Live';
                        $update_btn='Update the Staging Site';
                        $class_btn='staging-site';
                    }

                    if(isset($staging['mu_single']) && $staging['mu_single'] == true){
                        $mu_single_class = 'mu-single';
                    }
                    else{
                        $mu_single_class = '';
                    }

                    if(isset($staging['create_time']))
                    {
                        $staging_create_time = $staging['create_time'];
                        $offset=get_option('gmt_offset');
                        $utc_time = $staging_create_time + $offset * 60 * 60;
                        $staging_create_time = date('M-d-Y H:i', $utc_time);
                    }
                    else
                    {
                        $staging_create_time = 'N/A';
                    }

                    if(isset($staging['copy_time']))
                    {
                        $staging_copy_time = $staging['copy_time'];
                        $offset=get_option('gmt_offset');
                        $utc_time = $staging_copy_time + $offset * 60 * 60;
                        $staging_copy_time = date('M-d-Y H:i', $utc_time);
                    }
                    else
                    {
                        $staging_copy_time = 'N/A';
                    }

                    if(isset($staging['comment']) && !empty($staging['comment']))
                    {
                        $staging_comment = $staging['comment'];
                    }
                    else
                    {
                        $staging_comment = 'N/A';
                    }
                    ?>
                    <div class="wpvivid-one-coloum" style="border:1px solid #f1f1f1;padding-top:0em; margin-top:1em;" id="<?php echo esc_attr($id); ?>">
                        <div class="wpvivid-two-col">
                            <p><span class="dashicons dashicons-awards wpvivid-dashicons-blue"></span><span><strong>Site Name: </strong></span><span><?php echo $staging_site_name; ?></span></p>
                            <p><span class="dashicons dashicons-admin-home wpvivid-dashicons-blue"></span><span><strong>Home URL: </strong></span><span><a href="<?php echo esc_url($site_url); ?>"><?php echo $site_url; ?></a></span></p>
                            <p><span class="dashicons dashicons-rest-api wpvivid-dashicons-blue"></span><span><strong><?php echo $admin_url_descript; ?>: </strong></span><span><a href="<?php echo esc_url($admin_url); ?>"><?php echo $admin_url; ?></a></span></p>
                            <p><span class="dashicons dashicons-clock wpvivid-dashicons-blue"></span><span><strong>Create Time: </strong></span><span><?php echo $staging_create_time; ?></span></p>
                            <p><span class="dashicons dashicons-admin-tools wpvivid-dashicons-blue"></span><span><strong>Comment: </strong></span><span class="wpvivid-staging-comment-text"><?php echo $staging_comment; ?></span><span class="wpvivid-staging-comment-manage"><span class="dashicons dashicons-edit wpvivid-dashicons-blue wpvivid-staging-comment-edit" style="cursor: pointer;"></span></span></p>
                        </div>

                        <div class="wpvivid-two-col">
                            <p><span class="dashicons dashicons-admin-site-alt3 wpvivid-dashicons-blue"></span><span><strong>Database Name: </strong></span><span><?php echo $db_name; ?></span></p>
                            <p><span class="dashicons dashicons-list-view wpvivid-dashicons-blue"></span><span><strong>Table Prefix: </strong></span><span><?php echo $prefix; ?></span></p>
                            <p><span class="dashicons dashicons-portfolio wpvivid-dashicons-blue"></span><span><strong>Directory: </strong></span><span><?php echo $site_dir; ?></span></p>
                            <p><span class="dashicons dashicons-clock wpvivid-dashicons-blue"></span><span><strong>Update Time: </strong></span><span><?php echo $staging_copy_time; ?></span></p>
                        </div>

                        <div style="clear: both;"></div>

                        <div class="wpvivid-copy-staging-to-live-block <?php echo esc_attr($class_btn.' '.$mu_single_class); ?>" name="<?php echo esc_attr($id); ?>" style="padding:0.5em 1em 0 0;">
                            <span class="button wpvivid-staging-operate wpvivid-update-live-to-staging"><?php echo $update_btn; ?></span>
                            <span class="button wpvivid-staging-operate wpvivid-copy-staging-to-live"><?php echo $copy_btn; ?></span>
                            <span class="button wpvivid-staging-operate wpvivid-delete-staging-site">Delete</span>
                            <?php
                            do_action('wpvivid_staging_merging_block',$id);
                            ?>
                        </div>
                        <div class="wpvivid-jump-staging-text" style="padding:0.5em 1em 0 0; display: none;">
                            <span class="spinner is-active" style="float: left;"></span>
                            <span style="float: left; margin-top: 4px;">Preparing to copy the staging site to live...</span>
                            <div style="clear: both;"></div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <?php
        if(is_multisite())
        {
            ?>
            <div id="wpvividstg_select_mu_staging_site" style="width: 100%; display:none;">

            </div>
            <?php
        }
        ?>

        <div id="wpvivid_custom_staging_site" style="display:none;">
            <?php
            $custom_staging_list = new WPvivid_Staging_Custom_Select_List();
            $custom_staging_list ->set_parent_id('wpvivid_custom_staging_site');
            $custom_staging_list ->set_staging_home_path(true);
            $custom_staging_list ->display_rows();
            $custom_staging_list ->load_js();
            ?>
        </div>

        <script>
            <?php
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['basedir'];
            $upload_path = str_replace('\\','/',$upload_path);
            $upload_path = $upload_path.'/';
            $content_dir = WP_CONTENT_DIR;
            $content_path = str_replace('\\','/',$content_dir);
            $content_path = $content_path.'/';
            $home_path = str_replace('\\','/', get_home_path());
            $theme_path = str_replace('\\','/', get_theme_root());
            $theme_path = $theme_path.'/';
            $plugin_path = str_replace('\\','/', WP_PLUGIN_DIR);
            $plugin_path = $plugin_path.'/';
            ?>
            var path_arr = {};
            path_arr['core'] = '<?php echo $home_path; ?>';
            path_arr['content'] = '<?php echo $content_path; ?>';
            path_arr['uploads'] = '<?php echo $upload_path; ?>';
            path_arr['themes'] = '<?php echo $theme_path; ?>';
            path_arr['plugins'] = '<?php echo $plugin_path; ?>';

            var push_staging_site_id='';
            var wpvivid_ajax_lock=false;

            function wpvivid_create_standard_json(){
                var json = {};
                json['database_check_ex'] = '1';
                json['folder_check_ex'] = '1';
                json['exclude_custom'] = '0';
                json['core_list'] = Array();
                json['core_check'] = '0';
                json['database_list'] = Array();
                json['database_check'] = '1';
                json['themes_list'] = {};
                json['themes_check'] = '0';
                json['themes_extension']= Array();
                json['plugins_list'] = {};
                json['plugins_check'] = '0';
                json['plugins_extension']= Array();
                json['uploads_list'] = {};
                json['uploads_check'] = '1';
                json['upload_extension']= Array();
                json['content_list'] = {};
                json['content_check'] = '0';
                json['content_extension']= Array();
                json['additional_file_list'] = {};
                json['additional_file_check'] = '0';
                json['additional_file_extension']= Array();
                return json;
            }

            function wpvivid_create_all_json(){
                var json = {};
                json['database_check_ex'] = '1';
                json['folder_check_ex'] = '1';
                json['exclude_custom'] = '0';
                json['core_list'] = Array();
                json['core_check'] = '1';
                json['database_list'] = Array();
                json['database_check'] = '1';
                json['themes_list'] = {};
                json['themes_check'] = '1';
                json['themes_extension']= Array();
                json['plugins_list'] = {};
                json['plugins_check'] = '1';
                json['plugins_extension']= Array();
                json['uploads_list'] = {};
                json['uploads_check'] = '1';
                json['upload_extension']= Array();
                json['content_list'] = {};
                json['content_check'] = '1';
                json['content_extension']= Array();
                json['additional_file_list'] = {};
                json['additional_file_check'] = '0';
                json['additional_file_extension']= Array();
                return json;
            }

            function wpvivid_push_start_staging(mu_single){
                var push_type = 'push_standard';
                var push_mu_site=false;
                jQuery('#'+push_staging_site_id).find('input:radio').each(function()
                {
                    if(jQuery(this).prop('checked')){
                        push_type = jQuery(this).attr('value');
                    }
                });
                if(push_type === 'push_all') {
                    var custom_dir_json = wpvivid_create_all_json();
                    var custom_dir = JSON.stringify(custom_dir_json);
                }
                else if(push_type === 'push_standard') {
                    var custom_dir_json = wpvivid_create_standard_json();
                    var custom_dir = JSON.stringify(custom_dir_json);
                }
                else if(push_type === 'push_mu_site') {
                    var check_select = false;
                    jQuery('#wpvivid_mu_copy_staging_site_list').find('input:checkbox[name=copy_mu_site]').each(function(){
                        if(jQuery(this).prop('checked')){
                            check_select = true;
                        }
                    });

                    if(jQuery('input:checkbox[option=wpvividstg_copy_mu_sites][name=mu_all_site]').prop('checked')){
                        check_select = true;
                    }

                    if(jQuery('#wpvivid_staging_list').find('#wpvivid_mu_main_site_check').prop('checked')){
                        check_select = true;
                    }

                    if(mu_single){
                        check_select = true;
                    }

                    if(!check_select){
                        alert('Please select at least one item.');
                        return;
                    }

                    push_mu_site=true;
                    var json = {};
                    json['mu_site_list']=Array();
                    if(jQuery('input:checkbox[name=copy_mu_site_main]').prop('checked'))
                    {
                        var subjson = {};
                        subjson['check']=1;
                        subjson['id']=jQuery('input:checkbox[name=copy_mu_site_main]').val();

                        if(jQuery('input:checkbox[name=copy_mu_site_main_tables]').prop('checked'))
                        {
                            json['database_check']=1;
                            json['database_list'] = Array();
                        }
                        else
                        {
                            json['database_check']=0;
                            json['database_list'] = Array();
                        }

                        json['exclude_custom'] = '1';
                        if(!jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-part').prop('checked')){
                            json['exclude_custom'] = '0';
                        }


                        //uploads
                        if(jQuery('input:checkbox[name=copy_mu_site_main_folders]').prop('checked'))
                        {
                            json['uploads_check']=1;
                            json['uploads_list'] = {};
                            json['upload_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-uploads-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['uploads_list'][folder_name] = {};
                                    json['uploads_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['uploads_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['uploads_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['upload_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-uploads-extension').val();
                            }
                        }
                        else
                        {
                            json['uploads_check'] = '0';
                            json['uploads_list'] = {};
                            json['upload_extension'] = '';
                        }

                        //core
                        if(jQuery('input:checkbox[name=copy_mu_site_main_core]').prop('checked'))
                        {
                            json['core_check']=1;
                        }
                        else
                        {
                            json['core_check']=0;
                        }

                        //themes
                        if(jQuery('input:checkbox[name=copy_mu_site_main_themes]').prop('checked'))
                        {
                            json['themes_check'] = '1';
                            json['themes_list'] = {};
                            json['themes_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-themes-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['themes_list'][folder_name] = {};
                                    json['themes_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['themes_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['themes_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['themes_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-themes-extension').val();
                            }
                        }
                        else
                        {
                            json['themes_check'] = '0';
                            json['themes_list'] = {};
                            json['themes_extension'] = '';
                        }

                        //plugins
                        if(jQuery('input:checkbox[name=copy_mu_site_main_plugins]').prop('checked'))
                        {
                            json['plugins_check'] = '1';
                            json['plugins_list'] = {};
                            json['plugins_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-plugins-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['plugins_list'][folder_name] = {};
                                    json['plugins_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['plugins_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['plugins_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['plugins_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-plugins-extension').val();
                            }
                        }
                        else
                        {
                            json['plugins_check'] = '0';
                            json['plugins_list'] = {};
                            json['plugins_extension'] = '';
                        }

                        //content
                        if(jQuery('input:checkbox[name=copy_mu_site_main_content]').prop('checked'))
                        {
                            json['content_check'] = '1';
                            json['content_list'] = {};
                            json['content_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-content-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['content_list'][folder_name] = {};
                                    json['content_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['content_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['content_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['content_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-content-extension').val();
                            }
                        }
                        else
                        {
                            json['content_check'] = '0';
                            json['content_list'] = {};
                            json['content_extension'] = '';
                        }

                        //additional folder
                        if(jQuery('input:checkbox[name=copy_mu_site_main_additional_file]').prop('checked'))
                        {
                            json['additional_file_check'] = '1';
                            json['additional_file_list'] = {};
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-include-additional-folder-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['additional_file_list'][folder_name] = {};
                                    json['additional_file_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['additional_file_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['additional_file_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                            }
                        }
                        else
                        {
                            json['additional_file_check'] = '0';
                            json['additional_file_list'] = {};
                        }

                        json['mu_main_site']=subjson;
                    }
                    else
                    {
                        var subjson = {};
                        subjson['check']=0;
                        subjson['id']=jQuery('input:checkbox[name=copy_mu_site_main]').val();
                        json['mu_main_site']=subjson;
                    }

                    jQuery('input[name=copy_mu_site][type=checkbox]').each(function(index, value)
                    {
                        if(jQuery(value).prop('checked'))
                        {
                            var subjson = {};
                            subjson['id']=jQuery(value).val();
                            if(jQuery('input:checkbox[name=copy_mu_site_tables][value='+jQuery(value).val()+']').prop('checked'))
                            {
                                subjson['tables']=1;
                            }
                            else
                            {
                                subjson['tables']=0;
                            }
                            if(jQuery('input:checkbox[name=copy_mu_site_folders][value='+jQuery(value).val()+']').prop('checked'))
                            {
                                subjson['folders']=1;
                            }
                            else
                            {
                                subjson['folders']=0;
                            }
                            json['mu_site_list'].push(subjson);
                        }
                    });

                    if(jQuery('input:checkbox[option=wpvividstg_copy_mu_sites][name=mu_all_site]').prop('checked'))
                    {
                        json['all_site']=1;
                    }
                    else
                    {
                        json['all_site']=0;
                    }

                    var custom_dir = JSON.stringify(json);
                    jQuery('#wpvividstg_select_mu_staging_site').hide();
                }
                else if(push_type === 'push_custom') {
                    var custom_dir_json = wpvivid_create_custom_json(push_staging_site_id);
                    var custom_dir = JSON.stringify(custom_dir_json);
                    //var check_status = wpvivid_check_staging_additional_folder_valid(push_staging_site_id);
                    var check_status = wpvivid_check_backup_option_avail(push_staging_site_id, true);
                    if(!check_status) {
                        return;
                    }
                }
                else if(push_type === 'update_all') {
                    var custom_dir_json = wpvivid_create_all_json();
                    var custom_dir = JSON.stringify(custom_dir_json);
                }
                else if(push_type === 'update_standard') {
                    var custom_dir_json = wpvivid_create_standard_json();
                    var custom_dir = JSON.stringify(custom_dir_json);
                }
                else if(push_type === 'update_mu_site') {
                    var check_select = false;
                    jQuery('#wpvivid_mu_copy_staging_site_list').find('input:checkbox[name=copy_mu_site]').each(function(){
                        if(jQuery(this).prop('checked')){
                            check_select = true;
                        }
                    });

                    if(jQuery('input:checkbox[option=wpvividstg_copy_mu_sites][name=mu_all_site]').prop('checked')){
                        check_select = true;
                    }

                    if(jQuery('#wpvivid_staging_list').find('#wpvivid_mu_main_site_check').prop('checked')){
                        check_select = true;
                    }

                    if(mu_single){
                        check_select = true;
                    }

                    if(!check_select){
                        alert('Please select at least one item.');
                        return;
                    }

                    push_mu_site=true;
                    var json = {};
                    json['mu_site_list']=Array();
                    if(jQuery('input:checkbox[name=copy_mu_site_main]').prop('checked'))
                    {
                        var subjson = {};
                        subjson['check']=1;
                        subjson['id']=jQuery('input:checkbox[name=copy_mu_site_main]').val();


                        if(jQuery('input:checkbox[name=copy_mu_site_main_tables]').prop('checked'))
                        {
                            json['database_check']=1;
                            json['database_list'] = Array();
                        }
                        else
                        {
                            json['database_check']=0;
                            json['database_list'] = Array();
                        }


                        json['exclude_custom'] = '1';
                        if(!jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-part').prop('checked')){
                            json['exclude_custom'] = '0';
                        }

                        //uploads
                        if(jQuery('input:checkbox[name=copy_mu_site_main_folders]').prop('checked'))
                        {
                            json['uploads_check']=1;
                            json['uploads_list'] = {};
                            json['upload_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-uploads-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['uploads_list'][folder_name] = {};
                                    json['uploads_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['uploads_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['uploads_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['upload_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-uploads-extension').val();
                            }
                        }
                        else
                        {
                            json['uploads_check'] = '0';
                            json['uploads_list'] = {};
                            json['upload_extension'] = '';
                        }

                        //core
                        if(jQuery('input:checkbox[name=copy_mu_site_main_core]').prop('checked'))
                        {
                            json['core_check']=1;
                        }
                        else
                        {
                            json['core_check']=0;
                        }

                        //themes
                        if(jQuery('input:checkbox[name=copy_mu_site_main_themes]').prop('checked'))
                        {
                            json['themes_check'] = '1';
                            json['themes_list'] = {};
                            json['themes_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-themes-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['themes_list'][folder_name] = {};
                                    json['themes_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['themes_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['themes_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['themes_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-themes-extension').val();
                            }
                        }
                        else
                        {
                            json['themes_check'] = '0';
                            json['themes_list'] = {};
                            json['themes_extension'] = '';
                        }

                        //plugins
                        if(jQuery('input:checkbox[name=copy_mu_site_main_plugins]').prop('checked'))
                        {
                            json['plugins_check'] = '1';
                            json['plugins_list'] = {};
                            json['plugins_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-plugins-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['plugins_list'][folder_name] = {};
                                    json['plugins_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['plugins_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['plugins_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['plugins_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-plugins-extension').val();
                            }
                        }
                        else
                        {
                            json['plugins_check'] = '0';
                            json['plugins_list'] = {};
                            json['plugins_extension'] = '';
                        }

                        //content
                        if(jQuery('input:checkbox[name=copy_mu_site_main_content]').prop('checked'))
                        {
                            json['content_check'] = '1';
                            json['content_list'] = {};
                            json['content_extension'] = '';
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-exclude-content-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['content_list'][folder_name] = {};
                                    json['content_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['content_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['content_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                                json['content_extension'] = jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-content-extension').val();
                            }
                        }
                        else
                        {
                            json['content_check'] = '0';
                            json['content_list'] = {};
                            json['content_extension'] = '';
                        }

                        //additional folder
                        if(jQuery('input:checkbox[name=copy_mu_site_main_additional_file]').prop('checked'))
                        {
                            json['additional_file_check'] = '1';
                            json['additional_file_list'] = {};
                            if(json['exclude_custom'] == '1')
                            {
                                jQuery('#wpvividstg_select_mu_staging_site').find('.wpvivid-custom-include-additional-folder-list div').find('span:eq(2)').each(function ()
                                {
                                    var folder_name = this.innerHTML;
                                    json['additional_file_list'][folder_name] = {};
                                    json['additional_file_list'][folder_name]['name'] = folder_name;
                                    var type = jQuery(this).closest('div').attr('type');
                                    if(type === 'folder'){
                                        json['additional_file_list'][folder_name]['type'] = 'dashicons dashicons-category wpvivid-dashicons-orange wpvivid-icon-16px-nopointer';
                                    }
                                    else{
                                        json['additional_file_list'][folder_name]['type'] = 'dashicons dashicons-media-default wpvivid-dashicons-grey wpvivid-icon-16px-nopointer';
                                    }
                                });
                            }
                        }
                        else
                        {
                            json['additional_file_check'] = '0';
                            json['additional_file_list'] = {};
                        }

                        json['mu_main_site']=subjson;
                    }
                    else
                    {
                        var subjson = {};
                        subjson['check']=0;
                        subjson['id']=jQuery('input:checkbox[name=copy_mu_site_main]').val();
                        json['mu_main_site']=subjson;
                    }

                    jQuery('input[name=copy_mu_site][type=checkbox]').each(function(index, value)
                    {
                        if(jQuery(value).prop('checked'))
                        {
                            var subjson = {};
                            subjson['id']=jQuery(value).val();
                            if(jQuery('input:checkbox[name=copy_mu_site_tables][value='+jQuery(value).val()+']').prop('checked'))
                            {
                                subjson['tables']=1;
                            }
                            else
                            {
                                subjson['tables']=0;
                            }
                            if(jQuery('input:checkbox[name=copy_mu_site_folders][value='+jQuery(value).val()+']').prop('checked'))
                            {
                                subjson['folders']=1;
                            }
                            else
                            {
                                subjson['folders']=0;
                            }
                            json['mu_site_list'].push(subjson);
                        }
                    });

                    if(jQuery('input:checkbox[option=wpvividstg_copy_mu_sites][name=mu_all_site]').prop('checked'))
                    {
                        json['all_site']=1;
                    }
                    else
                    {
                        json['all_site']=0;
                    }

                    var custom_dir = JSON.stringify(json);
                    jQuery('#wpvividstg_select_mu_staging_site').hide();
                }
                else if(push_type === 'update_custom') {
                    var custom_dir_json = wpvivid_create_custom_json(push_staging_site_id);
                    var custom_dir = JSON.stringify(custom_dir_json);
                    //var check_status = wpvivid_check_staging_additional_folder_valid(push_staging_site_id);
                    var check_status = wpvivid_check_backup_option_avail(push_staging_site_id, true);
                    if(!check_status) {
                        return;
                    }
                }

                var action='wpvividstg_push_start_staging_ex';
                if(push_type === 'push_all'||push_type === 'push_standard'||push_type === 'push_custom'||push_type === 'push_mu_site')
                {
                    action='wpvividstg_push_start_staging_ex';
                }
                else if(push_type === 'update_all'||push_type === 'update_standard'||push_type === 'update_custom'||push_type === 'update_mu_site')
                {
                    action='wpvividstg_copy_start_staging_ex';
                }
                var ajax_data = {
                    'action':action,
                    'wpvivid_restore' : '1',
                    'id': push_staging_site_id,
                    'push_mu_site':push_mu_site,
                    'custom_dir': custom_dir
                };

                jQuery('#'+push_staging_site_id).find('.wpvivid-push-content').html('<div class="postbox wpvivid-staging-log" id="wpvivid_push_staging_log" style="margin-bottom: 0; word-break: break-all; word-wrap: break-word;"></div>');
                wpvivid_lock_unlock_push_ui('lock');

                wpvivid_post_request(ajax_data, function(data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        jQuery('#wpvivid_custom_staging_site').hide();
                        jQuery('#wpvividstg_select_mu_staging_site').hide();
                        setTimeout(function()
                        {
                            if(action=='wpvividstg_push_start_staging_ex')
                            {
                                wpvivid_get_push_staging_progress_ex();
                            }
                            else
                            {
                                wpvivid_get_copy_staging_progress_ex();
                            }
                        }, 1000);
                    }
                    else
                    {
                        wpvivid_lock_unlock_push_ui('unlock');
                        jQuery('#wpvivid_custom_staging_site').show();
                        jQuery('#wpvividstg_select_mu_staging_site').show();
                        alert(jsonarray.error);
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    jQuery('#wpvivid_custom_staging_site').show();
                    jQuery('#wpvividstg_select_mu_staging_site').show();
                    var error_message = wpvivid_output_ajaxerror('creating staging task', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvividstg_decode_response_ex(res)
            {
                if (res.indexOf("<wpvivid_section>") >= 0)
                {
                    var json = res.substring(
                        res.indexOf("<wpvivid_section>") + 17,
                        res.lastIndexOf("</wpvivid_section>")
                    );
                    return json;
                }
                else
                {
                    return res;
                }

            }

            function wpvivid_get_copy_staging_progress_ex()
            {
                var ajax_data = {
                    'action':'wpvividstg_get_staging_copy_progress_ex',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    try
                    {
                        var json = wpvividstg_decode_response_ex(data);
                        var jsonarray = jQuery.parseJSON(json);

                        if (jsonarray.result === 'success')
                        {
                            var log_data = jsonarray.log;
                            jQuery('#wpvivid_push_staging_log').html("");
                            while (log_data.indexOf('\n') >= 0)
                            {
                                var iLength = log_data.indexOf('\n');
                                var log = log_data.substring(0, iLength);
                                log_data = log_data.substring(iLength + 1);
                                var insert_log = "<div style=\"clear:both;\">" + log + "</div>";
                                jQuery('#wpvivid_push_staging_log').append(insert_log);
                                var div = jQuery('#wpvivid_push_staging_log');
                                div[0].scrollTop = div[0].scrollHeight;
                            }

                            if(jsonarray.status=='ready')
                            {
                                wpvivid_copy_restart_staging_ex();
                            }
                            else if(jsonarray.status=='completed')
                            {
                                wpvivid_copy_finish_staging();
                            }
                            else if(jsonarray.status=='running')
                            {
                                setTimeout(function(){
                                    wpvivid_get_copy_staging_progress_ex();
                                }, 1000);
                            }
                            else if(jsonarray.status=='no response')
                            {
                                setTimeout(function(){
                                    wpvivid_get_copy_staging_progress_ex();
                                }, 1000);
                            }
                        }
                        else
                        {
                            wpvivid_copy_staging_failed(jsonarray.error);
                        }
                    }
                    catch(err){
                        setTimeout(function()
                        {
                            wpvivid_get_copy_staging_progress_ex();
                        }, 3000);
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_copy_staging_progress_ex();
                    }, 3000);
                });
            }

            function wpvivid_copy_restart_staging_ex()
            {
                var ajax_data = {
                    'action':'wpvividstg_copy_restart_staging_ex',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_copy_staging_progress_ex();
                    }, 1000);
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_copy_staging_progress_ex();
                    }, 1000);
                });
            }

            function wpvivid_copy_finish_staging()
            {
                var ajax_data = {
                    'action':'wpvividstg_finish_copy_staging'
                };
                wpvivid_post_request(ajax_data, function(data)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert('Updating the staging site completed successfully.');
                    location.reload();

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert('Updating the staging site completed successfully.');
                    location.reload();
                });
            }

            function wpvivid_copy_staging_failed(error)
            {
                var ajax_data = {
                    'action':'wpvividstg_copy_staging_failed'
                };
                wpvivid_post_request(ajax_data, function(data)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert(error);

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert(error);
                });
            }

            function wpvivid_get_push_staging_progress_ex()
            {
                var ajax_data = {
                    'action':'wpvividstg_get_staging_push_progress_ex',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    try
                    {
                        var json = wpvividstg_decode_response_ex(data);
                        var jsonarray = jQuery.parseJSON(json);

                        if (jsonarray.result === 'success')
                        {
                            var log_data = jsonarray.log;
                            jQuery('#wpvivid_push_staging_log').html("");
                            while (log_data.indexOf('\n') >= 0)
                            {
                                var iLength = log_data.indexOf('\n');
                                var log = log_data.substring(0, iLength);
                                log_data = log_data.substring(iLength + 1);
                                var insert_log = "<div style=\"clear:both;\">" + log + "</div>";
                                jQuery('#wpvivid_push_staging_log').append(insert_log);
                                var div = jQuery('#wpvivid_push_staging_log');
                                div[0].scrollTop = div[0].scrollHeight;
                            }

                            if(jsonarray.status=='ready')
                            {
                                wpvivid_push_restart_staging_ex();
                            }
                            else if(jsonarray.status=='completed')
                            {
                                wpvivid_push_finish_staging();
                            }
                            else if(jsonarray.status=='running')
                            {
                                setTimeout(function(){
                                    wpvivid_get_push_staging_progress_ex();
                                }, 1000);
                            }
                            else if(jsonarray.status=='no response')
                            {
                                setTimeout(function(){
                                    wpvivid_get_push_staging_progress_ex();
                                }, 1000);
                            }
                        }
                        else
                        {
                            wpvivid_push_staging_failed(jsonarray.error);
                        }
                    }
                    catch(err){
                        setTimeout(function()
                        {
                            wpvivid_get_push_staging_progress_ex();
                        }, 3000);
                    }

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_push_staging_progress_ex();
                    }, 3000);
                });
            }

            function wpvivid_push_finish_staging()
            {
                var ajax_data = {
                    'action':'wpvividstg_finish_push_staging'
                };
                wpvivid_post_request(ajax_data, function(data)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert('Pushing the staging site to the live site completed successfully.');
                    location.reload();

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert('Pushing the staging site to the live site completed successfully.');
                    location.reload();
                });

            }

            function wpvivid_push_staging_failed(error)
            {
                var ajax_data = {
                    'action':'wpvividstg_push_staging_failed'
                };
                wpvivid_post_request(ajax_data, function(data)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert(error);

                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    alert(error);
                });
            }

            function wpvivid_push_restart_staging_ex()
            {
                var ajax_data = {
                    'action':'wpvividstg_push_restart_staging_ex',
                };

                wpvivid_post_request(ajax_data, function(data)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_push_staging_progress_ex();
                    }, 1000);
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    setTimeout(function()
                    {
                        wpvivid_get_push_staging_progress_ex();
                    }, 1000);
                });
            }

            function wpvivid_lock_unlock_push_ui(action){
                if(action === 'lock'){
                    jQuery('#wpvivid_staging_list').find('a').css({'pointer-events': 'none', 'opacity': '0.4'});
                    jQuery('#wpvivid_staging_list').find('input').attr('disabled', true);
                    jQuery('#wpvivid_staging_list').find('div.wpvivid-delete-staging-site').css({'pointer-events': 'none', 'opacity': '0.4'});
                    jQuery('#wpvivid_staging_list').find('div#wpvivid_custom_staging_site').css({'pointer-events': 'none', 'opacity': '0.4'});
                }
                else{
                    jQuery('#wpvivid_staging_list').find('a').css({'pointer-events': 'auto', 'opacity': '1'});
                    jQuery('#wpvivid_staging_list').find('input').attr('disabled', false);
                    jQuery('#wpvivid_staging_list').find('div.wpvivid-delete-staging-site').css({'pointer-events': 'auto', 'opacity': '1'});
                    jQuery('#wpvivid_staging_list').find('div#wpvivid_custom_staging_site').css({'pointer-events': 'auto', 'opacity': '1'});
                }
            }

            function wpvivid_delete_staging_site_lock_unlock(id, action){
                if(action === 'lock'){
                    jQuery('#wpvivid_staging_list').css({'pointer-events': 'none', 'opacity': '0.4'});
                }
                else{
                    jQuery('#wpvivid_staging_list').css({'pointer-events': 'auto', 'opacity': '1'});
                }
            }

            function wpvivid_staging_js_fix(parent_id, is_staging, themes_path, plugins_path, uploads_path, content_path, home_path, staging_site_id){
                var tree_path = themes_path;

                var path_arr = {};
                path_arr['core'] = home_path;
                path_arr['content'] = content_path;
                path_arr['uploads'] = uploads_path;
                path_arr['themes'] = themes_path;
                path_arr['plugins'] = plugins_path;

                jQuery('#'+parent_id).on('click', '.wpvivid-handle-additional-folder-detail', function(){
                    wpvivid_init_custom_include_tree(home_path, is_staging, parent_id);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-refresh-include-tree', function(){
                    wpvivid_init_custom_include_tree(home_path, is_staging, parent_id, 1);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-handle-tree-detail', function(){
                    var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                    if(value === 'themes'){
                        tree_path = themes_path;
                    }
                    else if(value === 'plugins'){
                        tree_path = plugins_path;
                    }
                    else if(value === 'content'){
                        tree_path = content_path;
                    }
                    else if(value === 'uploads'){
                        tree_path = uploads_path;
                    }
                    wpvivid_init_custom_exclude_tree(tree_path, is_staging, parent_id);
                });

                jQuery('#'+parent_id).on('change', '.wpvivid-custom-tree-selector', function(){
                    var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                    if(value === 'themes'){
                        tree_path = themes_path;
                    }
                    else if(value === 'plugins'){
                        tree_path = plugins_path;
                    }
                    else if(value === 'content'){
                        tree_path = content_path;
                    }
                    else if(value === 'uploads'){
                        tree_path = uploads_path;
                    }
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-tree-info').jstree("destroy").empty();
                    wpvivid_init_custom_exclude_tree(tree_path, is_staging, parent_id);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-refresh-exclude-tree', function(){
                    var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                    if(value === 'themes'){
                        tree_path = themes_path;
                    }
                    else if(value === 'plugins'){
                        tree_path = plugins_path;
                    }
                    else if(value === 'content'){
                        tree_path = content_path;
                    }
                    else if(value === 'uploads'){
                        tree_path = uploads_path;
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

            function wpvivid_copy_site(id) {
                var ajax_data = {
                    'action':'wpvividstg_copy_site',
                    'id': id
                };
                wpvivid_lock_unlock_push_ui('lock');
                wpvivid_post_request(ajax_data, function(data)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        push_staging_site_id=id;
                        wpvivid_staging_js_fix('wpvivid_custom_staging_site', false, jsonarray.themes_path, jsonarray.plugins_path, jsonarray.uploads_path, jsonarray.content_path, jsonarray.home_path, id);
                        jQuery('#'+id).find('.wpvivid-push-content').after(jQuery('#wpvivid_custom_staging_site'));
                        jQuery('#wpvivid_custom_staging_site').show();
                    }
                    else if (jsonarray.result === 'failed')
                    {
                        alert(jsonarray.error);
                    }

                    jQuery('#wpvivid_staging_list').find('.wpvivid-copy-staging-to-live-block').each(function() {
                        var tmp_id = jQuery(this).attr('name');
                        if(id !== tmp_id) {
                            if(jQuery(this).hasClass('staging-site')){
                                var class_btn = 'staging-site';
                                var copy_btn = 'Copy the Staging Site to Live';
                                var update_btn = 'Update the Staging Site';
                                var tip_text = 'Tips: Click the \'Copy the Staging Site to Live\' button above to migrate the staging site to your live site. Click the \'Update the Staging Site\' button to update the live site to the staging site.';
                            }
                            else{
                                var class_btn = 'fresh-install';
                                var copy_btn = 'Copy the Fresh Install to Live';
                                var update_btn = 'Update the Fresh Install';
                                var tip_text = 'Tips: Click the \'Copy the Fresh Install to Live\' button above to migrate the fresh install to your live site. Click the \'Update the Fresh Install\' button to update the live site to the fresh install.';
                            }

                            if(jQuery(this).hasClass('mu-single')){
                                var mu_single_class = 'mu-single';
                            }
                            else{
                                var mu_single_class = '';
                            }

                            var tmp_html = '<span class="button wpvivid-staging-operate wpvivid-update-live-to-staging">Update the Staging Site</span>' +
                                '<span class="button wpvivid-staging-operate wpvivid-copy-staging-to-live">Copy the Staging Site to Live</span>' +
                                '<span class="button wpvivid-staging-operate wpvivid-delete-staging-site">Delete</span>';
                            jQuery(this).html(tmp_html);
                        }
                        else{
                            if(jQuery(this).hasClass('mu-single')){
                                jQuery('#wpvivid_custom_staging_site').find('.core-desc').html('If the staging site and the live site have the same version of WordPress. Then it is not necessary to update the WordPress core files to the staging site.');
                                jQuery('#wpvivid_custom_staging_site').find('.database-desc').html('All the tables that belong to the subsite.');
                                jQuery('#wpvivid_custom_staging_site').find('.themes-plugins-desc').html('All the plugins and themes files used by the MU network. Plugins and themes activated on the subsite will be updated to the staging site by default.');
                                jQuery('#wpvivid_custom_staging_site').find('.uploads-desc').html('Files under the "uploads" folder that the staging site needs.');
                                jQuery('#wpvivid_custom_staging_site').find('.content-desc').html('<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to update to the staging site, except for the wp-content/uploads folder.');
                                jQuery('#wpvivid_custom_staging_site').find('.additional-file-desc').html('<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to update to the staging site.');
                            }
                        }
                    });
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    var error_message = wpvivid_output_ajaxerror('export the previously-exported settings', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_push_site(id)
            {
                var ajax_data = {
                    'action':'wpvividstg_push_site',
                    'id': id
                };
                wpvivid_lock_unlock_push_ui('lock');
                wpvivid_post_request(ajax_data, function(data)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        push_staging_site_id=id;
                        wpvivid_staging_js_fix('wpvivid_custom_staging_site', true, jsonarray.themes_path, jsonarray.plugins_path, jsonarray.uploads_path, jsonarray.content_path, jsonarray.home_path, id);
                        jQuery('#'+id).find('.wpvivid-push-content').after(jQuery('#wpvivid_custom_staging_site'));
                        jQuery('#wpvivid_custom_staging_site').show();

                        jQuery('#wpvivid_staging_list').find('.wpvivid-copy-staging-to-live-block').each(function() {
                            var tmp_id = jQuery(this).attr('name');
                            if(id !== tmp_id) {
                                if(jQuery(this).hasClass('staging-site')){
                                    var class_btn = 'staging-site';
                                    var copy_btn = 'Copy the Staging Site to Live';
                                    var update_btn = 'Update the Staging Site';
                                    var tip_text = 'Tips: Click the \'Copy the Staging Site to Live\' button above to migrate the staging site to your live site. Click the \'Update the Staging Site\' button to update the live site to the staging site.';
                                }
                                else{
                                    var class_btn = 'fresh-install';
                                    var copy_btn = 'Copy the Fresh Install to Live';
                                    var update_btn = 'Update the Fresh Install';
                                    var tip_text = 'Tips: Click the \'Copy the Fresh Install to Live\' button above to migrate the fresh install to your live site. Click the \'Update the Fresh Install\' button to update the live site to the fresh install.';
                                }

                                if(jQuery(this).hasClass('mu-single')){
                                    var mu_single_class = 'mu-single';
                                }
                                else{
                                    var mu_single_class = '';
                                }

                                var tmp_html = '<span class="button wpvivid-staging-operate wpvivid-update-live-to-staging">Update the Staging Site</span>' +
                                    '<span class="button wpvivid-staging-operate wpvivid-copy-staging-to-live">Copy the Staging Site to Live</span>' +
                                    '<span class="button wpvivid-staging-operate wpvivid-delete-staging-site">Delete</span>';
                                jQuery(this).html(tmp_html);
                            }
                        });
                    }
                    else if (jsonarray.result === 'failed')
                    {
                        alert(jsonarray.error);
                    }
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    var error_message = wpvivid_output_ajaxerror('export the previously-exported settings', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_load_mu_staging_js(parent_id){
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

                jQuery('#'+parent_id).on('click', '.wpvivid-handle-database-detail', function(){
                    var handle_obj = jQuery('#'+parent_id).find('.wpvivid-handle-database-detail');
                    var obj = jQuery('#'+parent_id).find('.wpvivid-database-detail');
                    wpvivid_handle_custom_open_close_ex(handle_obj, obj, parent_id);
                    init_staging_db_size(parent_id);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-handle-base-database-detail', function(){
                    var handle_obj = jQuery('#'+parent_id).find('.wpvivid-handle-base-database-detail');
                    var obj = jQuery('#'+parent_id).find('.wpvivid-base-database-detail');
                    wpvivid_handle_custom_open_close_ex(handle_obj, obj, parent_id);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-handle-file-detail', function(){
                    var handle_obj = jQuery('#'+parent_id).find('.wpvivid-handle-file-detail');
                    var obj = jQuery('#'+parent_id).find('.wpvivid-file-detail');
                    wpvivid_handle_custom_open_close_ex(handle_obj, obj, parent_id);
                    init_staging_file_size(parent_id);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-handle-additional-folder-detail', function(){
                    var handle_obj = jQuery('#'+parent_id).find('.wpvivid-handle-additional-folder-detail');
                    var obj = jQuery('#'+parent_id).find('.wpvivid-additional-folder-detail');
                    wpvivid_handle_custom_open_close_ex(handle_obj, obj, parent_id);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-handle-tree-detail', function(){
                    var handle_obj = jQuery('#'+parent_id).find('.wpvivid-handle-tree-detail');
                    var obj = jQuery('#'+parent_id).find('.wpvivid-tree-detail');
                    var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                    wpvivid_handle_custom_open_close_ex(handle_obj, obj, parent_id);
                });

                jQuery('#'+parent_id).on('change', '.wpvivid-custom-tree-selector', function(){
                    var value = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
                    jQuery('#'+parent_id).find('.wpvivid-custom-exclude-tree-info').jstree("destroy").empty();
                    wpvivid_change_custom_exclude_info(value, parent_id);
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-remove-custom-exlcude-tree', function(){
                    jQuery(this).parent().remove();
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-clear-custom-include-list', function(){
                    jQuery('#'+parent_id).find('.wpvivid-custom-include-additional-folder-list').html('');
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-clear-custom-exclude-list', function(){
                    var tree_type = jQuery('#'+parent_id).find('.wpvivid-custom-tree-selector').val();
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
                    jQuery('#'+parent_id).find('.'+list).html('');
                });

                jQuery('#'+parent_id).on('click', '.wpvivid-database-table-check', function(){
                    if(jQuery(this).prop('checked')){
                        if(jQuery(this).hasClass('wpvivid-database-base-table-check')){
                            jQuery('#'+parent_id).find('input:checkbox[option=base_db][name=Database]').prop('checked', true);
                        }
                        else if(jQuery(this).hasClass('wpvivid-database-other-table-check')){
                            jQuery('#'+parent_id).find('input:checkbox[option=other_db][name=Database]').prop('checked', true);
                        }
                        else if(jQuery(this).hasClass('wpvivid-database-woo-table-check')){
                            jQuery('#'+parent_id).find('input:checkbox[option=woo_db][name=Database]').prop('checked', true);
                        }
                    }
                    else{
                        var check_status = false;
                        if (jQuery(this).hasClass('wpvivid-database-base-table-check')) {
                            jQuery('#'+parent_id).find('input:checkbox[option=other_db][name=Database]').each(function(){
                                if(jQuery(this).prop('checked')){
                                    check_status = true;
                                }
                            });
                            jQuery('#'+parent_id).find('input:checkbox[option=woo_db][name=Database]').each(function(){
                                if(jQuery(this).prop('checked')){
                                    check_status = true;
                                }
                            });
                            if(check_status) {
                                jQuery('#'+parent_id).find('input:checkbox[option=base_db][name=Database]').prop('checked', false);
                            }
                            else{
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one table type under the Database option, or deselect the option.');
                            }
                        }
                        else if (jQuery(this).hasClass('wpvivid-database-other-table-check')) {
                            jQuery('#'+parent_id).find('input:checkbox[option=base_db][name=Database]').each(function(){
                                if(jQuery(this).prop('checked')){
                                    check_status = true;
                                }
                            });
                            jQuery('#'+parent_id).find('input:checkbox[option=woo_db][name=Database]').each(function(){
                                if(jQuery(this).prop('checked')){
                                    check_status = true;
                                }
                            });
                            if(check_status) {
                                jQuery('#'+parent_id).find('input:checkbox[option=other_db][name=Database]').prop('checked', false);
                            }
                            else{
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one table type under the Database option, or deselect the option.');
                            }
                        }
                        else if (jQuery(this).hasClass('wpvivid-database-woo-table-check')) {
                            jQuery('#'+parent_id).find('input:checkbox[option=base_db][name=Database]').each(function(){
                                if(jQuery(this).prop('checked')){
                                    check_status = true;
                                }
                            });
                            jQuery('#'+parent_id).find('input:checkbox[option=other_db][name=Database]').each(function(){
                                if(jQuery(this).prop('checked')){
                                    check_status = true;
                                }
                            });
                            if(check_status) {
                                jQuery('#'+parent_id).find('input:checkbox[option=woo_db][name=Database]').prop('checked', false);
                            }
                            else{
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one table type under the Database option, or deselect the option.');
                            }
                        }
                    }
                });

                jQuery('#'+parent_id).on("click", 'input:checkbox[option=base_db][name=Database]', function(){
                    if(jQuery(this).prop('checked')){
                        var all_check = true;
                        jQuery('#'+parent_id).find('input:checkbox[option=base_db][name=Database]').each(function(){
                            if(!jQuery(this).prop('checked')){
                                all_check = false;
                            }
                        });
                        if(all_check){
                            jQuery('#'+parent_id).find('.wpvivid-database-base-table-check').prop('checked', true);
                        }
                    }
                    else{
                        var check_status = false;
                        jQuery('#'+parent_id).find('input:checkbox[name=Database]').each(function(){
                            if(jQuery(this).prop('checked')){
                                check_status = true;
                            }
                        });
                        if(check_status){
                            jQuery('#'+parent_id).find('.wpvivid-database-base-table-check').prop('checked', false);
                        }
                        else{
                            jQuery(this).prop('checked', true);
                            alert('Please select at least one table type under the Database option, or deselect the option.');
                        }
                    }
                });

                jQuery('#'+parent_id).on("click", 'input:checkbox[option=other_db][name=Database]', function(){
                    if(jQuery(this).prop('checked')){
                        var all_check = true;
                        jQuery('#'+parent_id).find('input:checkbox[option=other_db][name=Database]').each(function(){
                            if(!jQuery(this).prop('checked')){
                                all_check = false;
                            }
                        });
                        if(all_check){
                            jQuery('#'+parent_id).find('.wpvivid-database-other-table-check').prop('checked', true);
                        }
                    }
                    else{
                        var check_status = false;
                        jQuery('#'+parent_id).find('input:checkbox[name=Database]').each(function(){
                            if(jQuery(this).prop('checked')){
                                check_status = true;
                            }
                        });
                        if(check_status){
                            jQuery('#'+parent_id).find('.wpvivid-database-other-table-check').prop('checked', false);
                        }
                        else{
                            jQuery(this).prop('checked', true);
                            alert('Please select at least one table type under the Database option, or deselect the option.');
                        }
                    }
                });

                jQuery('#'+parent_id).on("click", 'input:checkbox[option=woo_db][name=Database]', function(){
                    if(jQuery(this).prop('checked')){
                        var all_check = true;
                        jQuery('#'+parent_id).find('input:checkbox[option=woo_db][name=Database]').each(function(){
                            if(!jQuery(this).prop('checked')){
                                all_check = false;
                            }
                        });
                        if(all_check){
                            jQuery('#'+parent_id).find('.wpvivid-database-woo-table-check').prop('checked', true);
                        }
                    }
                    else{
                        var check_status = false;
                        jQuery('#'+parent_id).find('input:checkbox[name=Database]').each(function(){
                            if(jQuery(this).prop('checked')){
                                check_status = true;
                            }
                        });
                        if(check_status){
                            jQuery('#'+parent_id).find('.wpvivid-database-woo-table-check').prop('checked', false);
                        }
                        else{
                            jQuery(this).prop('checked', true);
                            alert('Please select at least one table type under the Database option, or deselect the option.');
                        }
                    }
                });

                jQuery('#'+parent_id).on("click", '.wpvivid-custom-database-part', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-database-check').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            jQuery('#'+parent_id).find('.wpvivid-custom-database-check').prop('checked', false);
                        }
                        else{
                            jQuery(this).prop('checked', true);
                            alert('Please select at least one item under Custom Backup option.');
                        }
                    }
                });

                jQuery('#'+parent_id).on("click", '.wpvivid-custom-database-check', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked', false);
                        }
                        else{
                            jQuery(this).prop('checked', true);
                            alert('Please select at least one item under Custom Backup option.');
                        }
                    }
                });

                jQuery('#'+parent_id).on("click", '.wpvivid-custom-file-part', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked', true);
                        jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked', true);
                        jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked', true);
                        jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked', true);
                        jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked', true);
                        jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked', false);
                            jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked', false);
                            jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked', false);
                            jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked', false);
                            jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked', false);
                            jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked', false);
                        }
                        else{
                            jQuery(this).prop('checked', true);
                            alert('Please select at least one item under Custom Backup option.');
                        }
                    }
                });

                //core
                jQuery('#'+parent_id).on("click", '.wpvivid-custom-core-check', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', false);
                            }
                        }
                        else{
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one item under Custom Backup option.');
                            }
                        }
                    }
                });

                //themes
                jQuery('#'+parent_id).on("click", '.wpvivid-custom-themes-check', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', false);
                            }
                        }
                        else{
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one item under Custom Backup option.');
                            }
                        }
                    }
                });

                //plugins
                jQuery('#'+parent_id).on("click", '.wpvivid-custom-plugins-check', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', false);
                            }
                        }
                        else{
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one item under Custom Backup option.');
                            }
                        }
                    }
                });

                //content
                jQuery('#'+parent_id).on("click", '.wpvivid-custom-content-check', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', false);
                            }
                        }
                        else{
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one item under Custom Backup option.');
                            }
                        }
                    }
                });

                //uploads
                jQuery('#'+parent_id).on("click", '.wpvivid-custom-uploads-check', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', false);
                            }
                        }
                        else{
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-additional-folder-check').prop('checked')){
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one item under Custom Backup option.');
                            }
                        }
                    }
                });

                //additional_folder
                jQuery('#'+parent_id).on("click", '.wpvivid-custom-additional-folder-check', function(){
                    if(jQuery(this).prop('checked')){
                        jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', true);
                    }
                    else{
                        var check_status = false;
                        if(jQuery('#'+parent_id).find('.wpvivid-custom-database-part').prop('checked')){
                            check_status = true;
                        }
                        if(check_status){
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked')){
                                jQuery('#'+parent_id).find('.wpvivid-custom-file-part').prop('checked', false);
                            }
                        }
                        else{
                            if(!jQuery('#'+parent_id).find('.wpvivid-custom-themes-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-plugins-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-content-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-uploads-check').prop('checked') &&
                                !jQuery('#'+parent_id).find('.wpvivid-custom-core-check').prop('checked')){
                                jQuery(this).prop('checked', true);
                                alert('Please select at least one item under Custom Backup option.');
                            }
                        }
                    }
                });
            }

            function wpvivid_get_mu_site_info(id,copy){
                var ajax_data = {
                    'action':'wpvividstg_get_mu_site_info',
                    'id': id,
                    'copy':copy
                };
                wpvivid_lock_unlock_push_ui('lock');
                wpvivid_post_request(ajax_data, function(data){
                    wpvivid_lock_unlock_push_ui('unlock');
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success') {
                        push_staging_site_id=id;
                        jQuery('#wpvividstg_select_mu_staging_site').html(jsonarray.html);
                        jQuery('#'+id).find('.wpvivid-push-content').after(jQuery('#wpvividstg_select_mu_staging_site'));
                        jQuery('#wpvividstg_select_mu_staging_site').show();
                        wpvivid_load_mu_staging_js('wpvivid_custom_mu_staging_site');
                        if(copy == 'true' || copy == true){
                            //wpvivid_load_staging_tree('wpvivid_custom_mu_staging_site', true);
                            wpvivid_staging_js_fix('wpvivid_custom_mu_staging_site', true, jsonarray.theme_path, jsonarray.plugin_path, jsonarray.uploads_path, jsonarray.content_path, jsonarray.home_path, id);
                        }
                        else{
                            //wpvivid_load_staging_tree('wpvivid_custom_mu_staging_site', false);
                            wpvivid_staging_js_fix('wpvivid_custom_mu_staging_site', false, jsonarray.theme_path, jsonarray.plugin_path, jsonarray.uploads_path, jsonarray.content_path, jsonarray.home_path, id);
                        }
                        jQuery('#wpvivid_mu_copy_staging_site_list').find('input:checkbox').each(function(){
                            jQuery(this).prop('checked', true);
                        });
                    }
                    else if (jsonarray.result === 'failed') {
                        alert(jsonarray.error);
                    }

                    jQuery('#wpvivid_staging_list').find('.wpvivid-copy-staging-to-live-block').each(function() {
                        var tmp_id = jQuery(this).attr('name');
                        if(id !== tmp_id) {
                            if(jQuery(this).hasClass('staging-site')){
                                var class_btn = 'staging-site';
                                var copy_btn = 'Copy the Staging Site to Live';
                                var update_btn = 'Update the Staging Site';
                                var tip_text = 'Tips: Click the \'Copy the Staging Site to Live\' button above to migrate the staging site to your live site. Click the \'Update the Staging Site\' button to update the live site to the staging site.';
                            }
                            else{
                                var class_btn = 'fresh-install';
                                var copy_btn = 'Copy the Fresh Install to Live';
                                var update_btn = 'Update the Fresh Install';
                                var tip_text = 'Tips: Click the \'Copy the Fresh Install to Live\' button above to migrate the fresh install to your live site. Click the \'Update the Fresh Install\' button to update the live site to the fresh install.';
                            }

                            if(jQuery(this).hasClass('mu-single')){
                                var mu_single_class = 'mu-single';
                            }
                            else{
                                var mu_single_class = '';
                            }

                            var tmp_html = '<span class="button wpvivid-staging-operate wpvivid-update-live-to-staging">Update the Staging Site</span>' +
                                '<span class="button wpvivid-staging-operate wpvivid-copy-staging-to-live">Copy the Staging Site to Live</span>' +
                                '<span class="button wpvivid-staging-operate wpvivid-delete-staging-site">Delete</span>';
                            jQuery(this).html(tmp_html);
                        }
                    });
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    wpvivid_lock_unlock_push_ui('unlock');
                    var error_message = wpvivid_output_ajaxerror('export the previously-exported settings', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            function wpvivid_get_copy_mu_list(page) {
                var copy=false;
                if(page==0)
                {
                    page =jQuery('#wpvivid_mu_copy_staging_site_list').find('.current-page').val();
                }
                var push_type = 'push_standard';
                jQuery('#'+push_staging_site_id).find('input:radio').each(function()
                {
                    if(jQuery(this).prop('checked')){
                        push_type = jQuery(this).attr('value');
                    }
                });
                if(push_type === 'update_standard'||push_type === 'update_custom'||push_type === 'update_mu_site')
                {
                    copy=true;
                }

                var search = jQuery('#wpvivid-mu-site-copy-search-input').val();
                var ajax_data = {
                    'action': 'wpvivid_get_mu_list',
                    'search':search,
                    'copy':copy,
                    'id':push_staging_site_id,
                    'page':page
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    jQuery('#wpvivid_mu_copy_staging_site_list').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_mu_copy_staging_site_list').html(jsonarray.html);
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
                    var error_message = wpvivid_output_ajaxerror('achieving backup', textStatus, errorThrown);
                    alert(error_message);
                });
            }

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-staging-comment-edit-ok', function(){
                var id = jQuery(this).closest('div').parent().attr('id');
                var staging_comment = jQuery('#'+id).find('.wpvivid-staging-comment-edit-text').val();
                var ajax_data = {
                    'action':'wpvividstg_edit_staging_comment',
                    'id': id,
                    'staging_comment': staging_comment
                };
                wpvivid_post_request(ajax_data, function(data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        if(staging_comment === '')
                        {
                            staging_comment = 'N/A';
                        }
                        jQuery('#'+id).find('.wpvivid-staging-comment-text').html(staging_comment);
                        jQuery('#'+id).find('.wpvivid-staging-comment-manage').html('<span class="dashicons dashicons-edit wpvivid-dashicons-blue wpvivid-staging-comment-edit" style="cursor: pointer;"></span>');
                    }
                    else if (jsonarray.result === 'failed')
                    {
                        alert(jsonarray.error);
                    }
                }, function(XMLHttpRequest, textStatus, errorThrown)
                {
                    var error_message = wpvivid_output_ajaxerror('export the previously-exported settings', textStatus, errorThrown);
                    alert(error_message);
                });
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-staging-comment-edit-cancel', function(){
                var id = jQuery(this).closest('div').parent().attr('id');
                jQuery('#'+id).find('.wpvivid-staging-comment-manage').html('<span class="dashicons dashicons-edit wpvivid-dashicons-blue wpvivid-staging-comment-edit" style="cursor: pointer;"></span>');
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-staging-comment-edit', function(){
                var id = jQuery(this).closest('div').parent().attr('id');
                var html = '<input type="text" class="wpvivid-staging-comment-edit-text" value="" style="margin-left: 5px; margin-right: 5px;" />' +
                    '<span class="button wpvivid-staging-comment-edit-ok" style="margin-top: 2px; margin-right: 5px;">Ok</span>' +
                    '<span class="button wpvivid-staging-comment-edit-cancel" style="margin-right: 5px;">Cancel</span>';
                jQuery('#'+id).find('.wpvivid-staging-comment-manage').html(html);
            });

            jQuery('#wpvivid_staging_list').on("click", 'input:radio', function(){
                var id = jQuery(this).attr('name');
                jQuery('#wpvivid_staging_list').find('input:radio').each(function()
                {
                    var tmp_id = jQuery(this).attr('name');
                    if(id !== tmp_id){
                        jQuery('#wpvivid_staging_list').find('input:radio[name='+tmp_id+'][value=push_standard]').prop('checked', true);
                    }
                });
                var value = jQuery(this).attr('value');
                if(value === 'push_all' || value === 'push_standard')
                {
                    jQuery('#wpvividstg_select_mu_staging_site').hide();
                    jQuery('#wpvivid_custom_staging_site').hide();
                }
                else if(value === 'push_custom')
                {
                    wpvivid_push_site(id);
                    jQuery('#wpvividstg_select_mu_staging_site').hide();
                    jQuery('#wpvivid_staging_list').find('.database-desc').html('It is recommended to copy all tables of the database to the live site.');
                    if(jQuery('#wpvivid_staging_list').find('tr#'+id).find('.wpvivid-copy-staging-to-live-block').hasClass('staging-site')){
                        var text = 'staging site';
                    }
                    else{
                        var text = 'fresh install';
                    }
                    <?php
                    if(is_multisite())
                    {
                    ?>
                    jQuery('#wpvivid_staging_list').find('.wpvivid-wordpress-core').html('WordPress MU Core');
                    jQuery('#wpvivid_staging_list').find('.core-desc').html('If the '+text+' and the live site have the same version of WordPress. Then it is not necessary to copy the WordPress MU core files to the live site.');
                    jQuery('#wpvivid_staging_list').find('.themes-plugins-desc').html('All the plugins and themes files used by the MU network. The activated plugins and themes will be copied to the live site by default. A child theme must be copied if it exists.');
                    jQuery('#wpvivid_staging_list').find('.uploads-desc').html('The folder where images and media files of the MU network are stored by default. All files will be copied to the live site by default. You can exclude folders you do not want to copy.');
                    jQuery('#wpvivid_staging_list').find('.content-desc').html('<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to copy to the live site, except for the wp-content/uploads folder.');
                    <?php
                    }
                    else{
                    ?>
                    jQuery('#wpvivid_staging_list').find('.core-desc').html('If the '+text+' and the live site have the same version of WordPress. Then it is not necessary to copy the WordPress core files to the live site. If they are not, it is recommended to copy the WordPress core files to the live site.');
                    jQuery('#wpvivid_staging_list').find('.themes-plugins-desc').html('The activated plugins and themes will be copied to the live site by default. The Child theme must be copied if it exists.');
                    jQuery('#wpvivid_staging_list').find('.uploads-desc').html('Images and media files are stored in the Uploads directory by default. All files are copied to the live site by default. You can exclude folders you do not want to copy.');
                    jQuery('#wpvivid_staging_list').find('.content-desc').html('<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to copy to the live site, except for the wp-content/uploads folder.');
                    <?php
                    }
                    ?>
                    jQuery('#wpvivid_staging_list').find('.additional-file-desc').html('<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to copy to the live site.');
                }
                else if(value === 'push_mu_site')
                {
                    wpvivid_get_mu_site_info(id,'true');
                    jQuery('#wpvivid_custom_staging_site').hide();
                }
                else if(value === 'update_all' || value === 'update_standard')
                {
                    jQuery('#wpvividstg_select_mu_staging_site').hide();
                    jQuery('#wpvivid_custom_staging_site').hide();
                }
                else if(value === 'update_custom')
                {
                    wpvivid_copy_site(id);
                    jQuery('#wpvividstg_select_mu_staging_site').hide();
                    if(jQuery('#wpvivid_staging_list').find('tr#'+id).find('.wpvivid-copy-staging-to-live-block').hasClass('staging-site')){
                        var text = 'staging site';
                    }
                    else{
                        var text = 'fresh install';
                    }
                    <?php
                    if(is_multisite())
                    {
                    ?>
                    jQuery('#wpvivid_staging_list').find('.wpvivid-wordpress-core').html('WordPress MU Core');
                    jQuery('#wpvivid_staging_list').find('.core-desc').html('If the '+text+' and the live site have the same version of WordPress. Then it is not necessary to update the WordPress MU core files to the '+text+'.');
                    jQuery('#wpvivid_staging_list').find('.database-desc').html('All the tables in the WordPress MU database. It is recommended to update all the tables to the '+text+'.');
                    jQuery('#wpvivid_staging_list').find('.themes-plugins-desc').html('All the plugins and themes files used by the MU network. The activated plugins and themes will be updated to the '+text+' by default. A child theme must be updated if it exists.');
                    jQuery('#wpvivid_staging_list').find('.uploads-desc').html('The folder where images and media files of the MU network are stored by default. All files will be updated to the '+text+' by default. You can exclude folders you do not want to update.');
                    jQuery('#wpvivid_staging_list').find('.content-desc').html('<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to update to the '+text+', except for the wp-content/uploads folder');
                    jQuery('#wpvivid_staging_list').find('.additional-file-desc').html('<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to update to the '+text+'.');
                    <?php
                    }
                    else{
                    ?>
                    jQuery('#wpvivid_staging_list').find('.core-desc').html('If the '+text+' and the live site have the same version of WordPress. Then it is not necessary to update the WordPress core files to the '+text+'.');
                    jQuery('#wpvivid_staging_list').find('.database-desc').html('It is recommended to update all tables of the database to the '+text+'.');
                    jQuery('#wpvivid_staging_list').find('.themes-plugins-desc').html('The activated plugins and themes will be updated to the '+text+' by default. The Child theme must be copied if it exists.');
                    jQuery('#wpvivid_staging_list').find('.uploads-desc').html('Images and media files are stored in the Uploads directory by default. All files are copied to the '+text+' by default. You can exclude folders you do not want to copy.');
                    jQuery('#wpvivid_staging_list').find('.content-desc').html('<strong style="text-decoration:underline;"><i>Exclude</i></strong> folders you do not want to update to the '+text+', except for the wp-content/uploads folder.');
                    jQuery('#wpvivid_staging_list').find('.additional-file-desc').html('<strong style="text-decoration:underline;"><i>Include</i></strong> additional files or folders you want to update to the '+text+'.');
                    <?php
                    }
                    ?>
                }
                else if(value === 'update_mu_site')
                {
                    wpvivid_get_mu_site_info(id,'false');
                    jQuery('#wpvivid_custom_staging_site').hide();
                }
            });

            jQuery('#wpvivid_staging_list').on("click", '.staging-list-push input', function(){
                var btn_name = jQuery(this).val();
                if(btn_name === 'Copy Now'){
                    var descript = 'Click OK to start pushing the staging site to live.';
                }
                else{
                    var descript = 'Click OK to start updating the staging site.';
                }

                var ret = confirm(descript);
                if(ret === true) {
                    var id = jQuery(this).parents("div").filter(".wpvivid-copy-staging-to-live-block").attr('name');
                    push_staging_site_id = id;
                    jQuery('#wpvivid_staging_notice').hide();
                    if (jQuery(this).closest('div').hasClass('mu-single')) {
                        var mu_single = true;
                    }
                    else {
                        var mu_single = false;
                    }
                    wpvivid_push_start_staging(mu_single);
                }
            });

            jQuery('#wpvivid_staging_list').on("click", '.staging-go-back', function(){
                //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-staging', 'wpvividstg-staging'); ?>';
                location.reload();
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-update-live-to-staging', function(){
                var id = jQuery(this).parent().attr('name');
                jQuery('#' + id).after(jQuery('#wpvivid_custom_staging_site'));
                jQuery('#wpvivid_custom_staging_site').hide();

                if (jQuery(this).parent().hasClass('staging-site')) {
                    var select_text = 'Choose what to update from the live site to the staging site';
                    var select_tip = 'Notice: Please do not refresh or close the page until the process completes. As it could cause some unexpected errors.';
                }
                else {
                    var select_text = 'Choose what to update from the live site to the fresh install';
                    var select_tip = 'Notice: Please do not refresh or close the page until the process completes. As it could cause some unexpected errors.';
                }

                if (jQuery(this).parent().hasClass('mu-single'))
                {
                    var mu_single_style = 'display: none;';
                    var class_single = 'mu-single';
                }
                else {
                    var mu_single_style = '';
                    var class_single = '';
                }

                if (jQuery(this).parent().hasClass('mu-single'))
                {
                    var html = '<div style="display:block;margin-bottom:10px;"><strong>' + select_text + '</strong></div>\n' +
                        '<div>' +
                        '<fieldset style="box-sizing: border-box;margin:10px 10px 0 10px;">' +
                        '<div style="margin:auto;">' +
                        '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; ' + mu_single_style + '">' +
                        '<label>' +
                        '<input type="radio" name="' + id + '" value="update_custom" checked>' +
                        '<span>Advanced</span>' +
                        '</label>' +
                        '</div>' +
                        '<div style="clear: both;"></div>' +
                        '</div>' +
                        '</fieldset>' +
                        '</div>' +
                        '<div class="wpvivid-push-content"></div>' +
                        '<div class="staging-list-push ' + class_single + '" style="margin-top:10px; float:left; margin-right: 10px;"><input class="button-primary" type="button" value="Update Now" /></div>' +
                        '<div class="staging-go-back" style="margin-top:10px; float:left;"><input class="button-primary" type="button" value="Go Back" /></div>' +
                        '<div style="clear:both"></div>' +
                        '<div style="border: 1px solid #f1f1f1; border-radius: 6px; margin-top: 10px;padding:5px;"><span>' + select_tip + '</span></div>';
                }
                else {
                    var html = '<div style="display:block;margin-bottom:10px;"><strong>' + select_text + '</strong></div>' +
                        '<div>' +
                        '<fieldset style="box-sizing: border-box;margin:10px 10px 0 10px;">' +
                        '<div style="margin:auto;">' +
                        <?php
                        if(is_multisite())
                        {
                        ?>
                        '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; ' + mu_single_style + '">' +
                        '<label>' +
                        '<input type="radio" name="' + id + '" value="update_mu_site" checked>' +
                        '<span>Easy Mode</span>' +
                        '</label>' +
                        '</div>' +
                        '<small>' +
                        '<div class="wpvivid_tooltip wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; margin-top: 4px; line-height: 100%; white-space: normal;">?' +
                        '<div class="wpvivid_tooltiptext">Quickly get started by choosing the entire MU database and custom files and/or specific subsites and updating them to the staging site.</div>' +
                        '</div>' +
                        '</small>' +
                        '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; ' + mu_single_style + '">' +
                        '<label>' +
                        '<input type="radio" name="' + id + '" value="update_custom">' +
                        '<span>Advanced Update</span>' +
                        '</label>' +
                        '</div>' +
                        '<small>' +
                        '<div class="wpvivid_tooltip wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; margin-top: 4px; line-height: 100%; white-space: normal;">?' +
                        '<div class="wpvivid_tooltiptext">Give you the freedom to choose custom files and database tables of the entire MU network and update them to the staging site.</div>' +
                        '</div>' +
                        '</small>' +
                        '<div style="clear: both;"></div>' +
                        '</div>' +
                        '</fieldset>' +
                        '</div>' +
                        '<div class="wpvivid-push-content"></div>' +
                        '<div class="staging-list-push ' + class_single + '" style="margin-top:10px; float:left; margin-right: 10px;"><input class="button-primary" type="button" value="Update Now" /></div>' +
                        '<div class="staging-go-back" style="margin-top:10px; float:left;"><input class="button-primary" type="button" value="Go Back" /></div>' +
                        '<div style="clear:both"></div>' +
                        '<div style="border: 1px solid #f1f1f1; border-radius: 6px; margin-top: 10px;padding:5px;"><span>' + select_tip + '</span></div>';
                    <?php
                    }
                    else{
                    ?>
                    '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">' +
                    '<label>' +
                    '<input type="radio" name="' + id + '" value="update_all" checked>' +
                    '<span>Files + DB</span>' +
                    '</label>' +
                    '</div>' +
                    '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">' +
                    '<label>' +
                    '<input type="radio" name="' + id + '" value="update_standard">' +
                    '<span>Only <code>Uploads</code> folder + DB</span>' +
                    '</label>' +
                    '</div>' +
                    '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">' +
                    '<label>' +
                    '<input type="radio" name="' + id + '" value="update_custom">' +
                    '<span>Custom Content</span>' +
                    '</label>' +
                    '</div>' +
                    '<div style="clear: both;"></div>' +
                    '</div>' +
                    '</fieldset>' +
                    '</div>' +
                    '<div class="wpvivid-push-content"></div>' +
                    '<div class="staging-list-push" style="margin-top:10px; float:left; margin-right: 10px;"><input class="button-primary" type="button" value="Update Now" /></div>' +
                    '<div class="staging-go-back" style="margin-top:10px; float:left;"><input class="button-primary" type="button" value="Go Back" /></div>' +
                    '<div style="clear:both"></div>' +
                    '<div style="border: 1px solid #f1f1f1; border-radius: 6px; margin-top: 10px;padding:5px;"><span>' + select_tip + '</span></div>';
                    <?php
                    }
                    ?>
                }

                <?php
                if(is_multisite()){
                ?>
                if(jQuery(this).parent().hasClass('mu-single'))
                {
                    var is_mu_single=true;
                }
                else
                {
                    var is_mu_single=false;
                }
                <?php
                }
                ?>

                //jQuery('.wpvivid-copy-staging-to-live-block').html(html);
                jQuery('#wpvivid_staging_list').find('div#'+id).find('.wpvivid-copy-staging-to-live-block').html(html);
                <?php
                if(is_multisite()){
                ?>
                if(is_mu_single)
                {
                    wpvivid_copy_site(id);
                }
                else
                {
                    wpvivid_get_mu_site_info(id, 'false');
                }
                <?php
                }
                ?>
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-copy-staging-to-live', function(){
                var ojb = jQuery(this);
                var id = ojb.parent().attr('name');
                jQuery('#'+id).find('.wpvivid-jump-staging-text').show();
                var ajax_data = {
                    'action':'wpvividstg_get_staging_site_url',
                    'id': id
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    var jsonarray = jQuery.parseJSON(data);
                    if (jsonarray.result === 'success')
                    {
                        //location.href=jsonarray.staging_site_url;
                        jQuery('#'+id).find('.wpvivid-jump-staging-text').hide();
                        jQuery('#'+id).after(jQuery('#wpvivid_custom_staging_site'));
                        jQuery('#wpvivid_custom_staging_site').hide();
                        if(ojb.parent().hasClass('staging-site'))
                        {
                            var select_text = 'Choose what to copy from the staging site to the live site';
                            var select_tip = 'Notice: Please do not refresh or close the page until the process completes. As it could cause some unexpected errors.';
                        }
                        else
                        {
                            var select_text = 'Choose what to copy from the fresh install to the live site';
                            var select_tip = 'Notice: Please do not refresh or close the page until the process completes. As it could cause some unexpected errors.';
                        }

                        if(ojb.parent().hasClass('mu-single')){
                            var mu_single_style = 'display: none;';
                            var class_single = 'mu-single';
                        }
                        else{
                            var mu_single_style = '';
                            var class_single = '';
                        }

                        <?php
                        if(!is_multisite()){
                        ?>
                        jQuery('#wpvivid_staging_list').find('.wpvivid-copy-staging-to-live-block').each(function() {
                            var tmp_id = ojb.attr('name');
                            if(id !== tmp_id) {
                                if(ojb.hasClass('staging-site')){
                                    var class_btn = 'staging-site';
                                    var copy_btn = 'Copy the Staging Site to Live';
                                    var update_btn = 'Update the Staging Site';
                                    var tip_text = 'Tips: Click the \'Copy the Staging Site to Live\' button above to migrate the staging site to your live site. Click the \'Update the Staging Site\' button to update the live site to the staging site.';
                                }
                                else{
                                    var class_btn = 'fresh-install';
                                    var copy_btn = 'Copy the Fresh Install to Live';
                                    var update_btn = 'Update the Fresh Install';
                                    var tip_text = 'Tips: Click the \'Copy the Fresh Install to Live\' button above to migrate the fresh install to your live site. Click the \'Update the Fresh Install\' button to update the live site to the fresh install.';
                                }

                                var tmp_html = '<span class="button wpvivid-staging-operate wpvivid-update-live-to-staging">Update the Staging Site</span>' +
                                    '<span class="button wpvivid-staging-operate wpvivid-copy-staging-to-live">Copy the Staging Site to Live</span>' +
                                    '<span class="button wpvivid-staging-operate wpvivid-delete-staging-site">Delete</span>';
                                ojb.html(tmp_html);
                            }
                        });
                        <?php
                        }
                        ?>

                        if(ojb.parent().hasClass('mu-single')) {
                            var html = '<div style="display:block;margin-bottom:10px;"><strong>'+select_text+'</strong></div>\n'+
                                '<div>'+
                                '<fieldset style="box-sizing: border-box;margin:10px 10px 0 10px;">'+
                                '<div style="margin:auto;">'+
                                '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; '+mu_single_style+'">'+
                                '<label>'+
                                '<input type="radio" name="'+id+'" value="push_custom" checked>'+
                                '<span>Advanced</span>'+
                                '</label>'+
                                '</div>'+
                                '<div style="clear: both;"></div>'+
                                '</div>'+
                                '</fieldset>'+
                                '</div>'+
                                '<div class="wpvivid-push-content"></div>'+
                                '<div class="staging-list-push '+class_single+'" style="margin-top:10px; float:left; margin-right: 10px;"><input class="button-primary" type="button" value="Copy Now" /></div>'+
                                '<div class="staging-go-back" style="margin-top:10px; float:left;"><input class="button-primary" type="button" value="Go Back" /></div>'+
                                '<div style="clear:both"></div>'+
                                '<div style="border: 1px solid #f1f1f1; border-radius: 6px; margin-top: 10px;padding:5px;"><span>'+select_tip+'</span></div>';
                        }
                        else {
                            var html = '<div style="display:block;margin-bottom:10px;"><strong>'+select_text+'</strong></div>\n'+
                                '<div>'+
                                '<fieldset style="box-sizing: border-box;margin:10px 10px 0 10px;">'+
                                '<div style="margin:auto;">'+
                                <?php
                                if(is_multisite())
                                {
                                ?>
                                '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; '+mu_single_style+'">'+
                                '<label>'+
                                '<input type="radio" name="'+id+'" value="push_mu_site" checked>'+
                                '<span>Easy Mode</span>'+
                                '</label>'+
                                '</div>'+
                                '<small>'+
                                '<div class="wpvivid_tooltip wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; margin-top: 4px; line-height: 100%; white-space: normal;">?'+
                                '<div class="wpvivid_tooltiptext">Quickly get started by choosing the entire MU database and custom files and/or specific subsites and pushing to the live site.</div>'+
                                '</div>'+
                                '</small>'+
                                '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; '+mu_single_style+'">'+
                                '<label>'+
                                '<input type="radio" name="'+id+'" value="push_custom">'+
                                '<span>Advanced Push</span>'+
                                '</label>'+
                                '</div>'+
                                '<small>'+
                                '<div class="wpvivid_tooltip wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left; margin-top: 4px; line-height: 100%; white-space: normal;">?'+
                                '<div class="wpvivid_tooltiptext">Give you the freedom to choose custom files and database tables of the entire MU network and push to the live site.</div>'+
                                '</div>'+
                                '</small>'+
                                '<div style="clear: both;"></div>'+
                                '</div>'+
                                '</fieldset>'+
                                '</div>'+
                                '<div class="wpvivid-push-content"></div>'+
                                '<div class="staging-list-push '+class_single+'" style="margin-top:10px; float:left; margin-right: 10px;"><input class="button-primary" type="button" value="Copy Now" /></div>'+
                                '<div class="staging-go-back" style="margin-top:10px; float:left;"><input class="button-primary" type="button" value="Go Back" /></div>'+
                                '<div style="clear:both"></div>'+
                                '<div style="border: 1px solid #f1f1f1; border-radius: 6px; margin-top: 10px;padding:5px;"><span>'+select_tip+'</span></div>';
                            <?php
                            }
                            else{
                            ?>
                            '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">' +
                            '<label>' +
                            '<input type="radio" name="'+id+'" value="push_all" checked>' +
                            '<span>Files + DB</span>' +
                            '</label>' +
                            '</div>' +
                            '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">'+
                            '<label>'+
                            '<input type="radio" name="'+id+'" value="push_standard">'+
                            '<span>Only <code>Uploads</code> folder + DB</span>'+
                            '</label>'+
                            '</div>'+
                            '<div class="wpvivid-element-space-bottom wpvivid-element-space-right" style="float: left;">'+
                            '<label>'+
                            '<input type="radio" name="'+id+'" value="push_custom">'+
                            '<span>Custom Content</span>'+
                            '</label>'+
                            '</div>'+
                            '<div style="clear: both;"></div>'+
                            '</div>'+
                            '</fieldset>'+
                            '</div>'+
                            '<div class="wpvivid-push-content"></div>'+
                            '<div class="staging-list-push" style="margin-top:10px; float:left; margin-right: 10px;"><input class="button-primary" type="button" value="Copy Now" /></div>'+
                            '<div class="staging-go-back" style="margin-top:10px; float:left;"><input class="button-primary" type="button" value="Go Back" /></div>'+
                            '<div style="clear:both"></div>'+
                            '<div style="border: 1px solid #f1f1f1; border-radius: 6px; margin-top: 10px;padding:5px;"><span>'+select_tip+'</span></div>';
                            <?php
                            }
                            ?>
                        }

                        <?php
                        if(is_multisite()){
                        ?>
                        if(ojb.parent().hasClass('mu-single'))
                        {
                            var is_mu_single=true;
                        }
                        else
                        {
                            var is_mu_single=false;
                        }
                        <?php
                        }
                        ?>

                        jQuery('#wpvivid_staging_list').find('div#'+id).find('.wpvivid-copy-staging-to-live-block').html(html);
                        <?php
                        if(is_multisite()){
                        ?>
                        if(is_mu_single)
                        {
                            wpvivid_push_site(id);
                        }
                        else
                        {
                            wpvivid_get_mu_site_info(id,'true');
                        }
                        <?php
                        }
                        ?>
                    }
                    else if (jsonarray.result === 'failed')
                    {
                        jQuery('#'+id).find('.wpvivid-jump-staging-text').hide();
                        alert(jsonarray.error);
                    }
                }, function (XMLHttpRequest, textStatus, errorThrown)
                {
                    jQuery('#'+id).find('.wpvivid-jump-staging-text').hide();
                    var error_message = wpvivid_output_ajaxerror('setting push restart staging id', textStatus, errorThrown);
                    alert(error_message);
                });
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-delete-staging-site', function(){
                var descript = 'Are you sure to delete this staging site?';
                var ret = confirm(descript);
                if (ret === true) {
                    var id = jQuery(this).parent().attr('name');
                    var ajax_data = {
                        'action': 'wpvividstg_delete_site_ex',
                        'id': id
                    };
                    wpvivid_delete_staging_site_lock_unlock(id, 'lock');
                    wpvivid_post_request(ajax_data, function (data) {
                        wpvivid_delete_staging_site_lock_unlock(id, 'unlock');
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success') {
                            //location.href='<?php echo apply_filters('wpvivid_white_label_page_redirect', 'admin.php?page=wpvividstg-staging', 'wpvividstg-staging'); ?>';
                            location.reload();
                        }
                        else if (jsonarray.result === 'failed') {
                            alert(jsonarray.error);
                        }
                    }, function (XMLHttpRequest, textStatus, errorThrown) {
                        wpvivid_delete_staging_site_lock_unlock(id, 'unlock');
                        var error_message = wpvivid_output_ajaxerror('export the previously-exported settings', textStatus, errorThrown);
                        alert(error_message);
                    });
                }
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-repush-staging-site', function(){
                var descript = 'Are you sure to restart this staging site?';
                var ret = confirm(descript);
                if (ret === true) {
                    var id = jQuery(this).parent().attr('name');
                    var ajax_data = {
                        'action':'wpvividstg_set_restart_staging_id',
                        'id': id
                    };
                    wpvivid_post_request(ajax_data, function (data) {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success') {
                            jQuery('#wpvivid_staging_notice').hide();
                            jQuery('#wpvivid_staging_list').find('div#'+id).find('.wpvivid-copy-staging-to-live-block').html('<div class="postbox wpvivid-staging-log" id="wpvivid_push_staging_log" style="margin-bottom: 0;"></div>');
                            wpvivid_lock_unlock_push_ui('lock');
                            wpvivid_push_restart_staging();
                        }
                        else if (jsonarray.result === 'failed') {
                            alert(jsonarray.error);
                        }
                    }, function (XMLHttpRequest, textStatus, errorThrown) {
                        var error_message = wpvivid_output_ajaxerror('setting push restart staging id', textStatus, errorThrown);
                        alert(error_message);
                    });
                }
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-recopy-staging-site', function(){
                var descript = 'Are you sure to restart this staging site?';
                var ret = confirm(descript);
                if (ret === true) {
                    var id = jQuery(this).parent().attr('name');
                    var ajax_data = {
                        'action':'wpvividstg_set_restart_staging_id',
                        'id': id
                    };
                    wpvivid_post_request(ajax_data, function (data) {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success') {
                            jQuery('#wpvivid_staging_notice').hide();
                            jQuery('#wpvivid_staging_list').find('div#'+id).find('.wpvivid-copy-staging-to-live-block').html('<div class="postbox wpvivid-staging-log" id="wpvivid_push_staging_log" style="margin-bottom: 0;"></div>');
                            wpvivid_lock_unlock_push_ui('lock');
                            wpvivid_copy_restart_staging();
                        }
                        else if (jsonarray.result === 'failed') {
                            alert(jsonarray.error);
                        }
                    }, function (XMLHttpRequest, textStatus, errorThrown) {
                        var error_message = wpvivid_output_ajaxerror('setting push restart staging id', textStatus, errorThrown);
                        alert(error_message);
                    });
                }
            });

            jQuery('#wpvivid_staging_list').on("click", '.wpvivid-restart-staging-site', function(){
                var descript = 'Are you sure to restart this staging site?';
                var ret = confirm(descript);
                if (ret === true) {
                    var id = jQuery(this).parent().attr('name');
                    var ajax_data = {
                        'action':'wpvividstg_set_restart_staging_id',
                        'id': id
                    };
                    wpvivid_post_request(ajax_data, function (data) {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success') {
                            jQuery('#wpvivid_choose_staging_content').hide();
                            jQuery('#wpvivid_create_btn').hide();
                            jQuery('#wpvivid_create_staging_step2').show();
                            switch_staging_tab('create_staging');
                            wpvivid_restart_staging();
                        }
                        else if (jsonarray.result === 'failed') {
                            alert(jsonarray.error);
                        }
                    }, function (XMLHttpRequest, textStatus, errorThrown) {
                        var error_message = wpvivid_output_ajaxerror('setting restart staging id', textStatus, errorThrown);
                        alert(error_message);
                    });
                }
            });

            jQuery('#wpvivid_staging_list').on("click",'.first-page',function() {
                wpvivid_get_copy_mu_list('first');
            });

            jQuery('#wpvivid_staging_list').on("click",'.prev-page',function() {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_copy_mu_list(page-1);
            });

            jQuery('#wpvivid_staging_list').on("click",'.next-page',function() {
                var page=parseInt(jQuery(this).attr('value'));
                wpvivid_get_copy_mu_list(page+1);
            });

            jQuery('#wpvivid_staging_list').on("click",'.last-page',function() {
                wpvivid_get_copy_mu_list('last');
            });

            jQuery('#wpvivid_staging_list').on("keypress", '.current-page', function(){
                if(event.keyCode === 13){
                    var page = jQuery(this).val();
                    wpvivid_get_copy_mu_list(page);
                }
            });

            jQuery('#wpvivid_staging_list').on("click",'#wpvivid-mu-copy-search-submit',function() {
                var copy=false;
                var push_type = 'push_standard';
                jQuery('#'+push_staging_site_id).find('input:radio').each(function()
                {
                    if(jQuery(this).prop('checked')){
                        push_type = jQuery(this).attr('value');
                    }
                });
                if(push_type === 'update_standard'||push_type === 'update_custom'||push_type === 'update_mu_site')
                {
                    copy=true;
                }
                var search = jQuery('#wpvivid-mu-site-copy-search-input').val();
                var ajax_data = {
                    'action': 'wpvivid_get_mu_list',
                    'copy':copy,
                    'id':push_staging_site_id,
                    'search':search
                };
                wpvivid_post_request(ajax_data, function (data)
                {
                    jQuery('#wpvivid_mu_copy_staging_site_list').html('');
                    try
                    {
                        var jsonarray = jQuery.parseJSON(data);
                        if (jsonarray.result === 'success')
                        {
                            jQuery('#wpvivid_mu_copy_staging_site_list').html(jsonarray.html);
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
                    var error_message = wpvivid_output_ajaxerror('achieving backup', textStatus, errorThrown);
                    alert(error_message);
                });
            });

            jQuery('#wpvivid_staging_list').on("click",'#wpvivid_mu_main_site_check',function() {
                if(jQuery(this).prop('checked'))
                {
                    jQuery('#wpvivid_mu_main_site_check_table').show();
                }
                else
                {
                    jQuery('#wpvivid_mu_main_site_check_table').hide();
                }
            });

            jQuery('#wpvivid_staging_list').on("click",'input:checkbox[option=wpvividstg_copy_mu_sites][name=mu_all_site]',function() {
                if(jQuery('input:checkbox[option=wpvividstg_copy_mu_sites][name=mu_all_site]').prop('checked'))
                {
                    jQuery('#wpvivid_mu_copy_staging_site_list').find('input:checkbox').each(function(){
                        jQuery(this).prop('checked', true);
                    });
                    jQuery('#wpvivid_mu_copy_staging_site_list').css({'pointer-events': 'none', 'opacity': '0.4'});
                }
                else{
                    jQuery('#wpvivid_mu_copy_staging_site_list').find('input:checkbox').each(function(){
                        jQuery(this).prop('checked', false);
                    });
                    jQuery('#wpvivid_mu_copy_staging_site_list').css({'pointer-events': 'auto', 'opacity': '1'});
                }
            });
        </script>
        <?php
        do_action('wpvivid_staging_merging_js');
    }
    */

/*
    public function create_page_display_ex()
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
        <div class="wrap wpvivid-canvas">

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

                                    <div class="wpvivid-canvas wpvivid-clear-float" style="table-layout: fixed;">
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
                                            $tabs['staging_sites']['callback']=array($this->staging_list_ui, 'output_staging_sites_list_page');
                                            $tabs['staging_sites']['args']=$args;

                                            $args['span_class']='dashicons dashicons-welcome-write-blog wpvivid-dashicons-blue';
                                            $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                            $args['div_style']='';
                                            $args['hide']=1;
                                            $tabs['create_staging']['title']='Create A Staging Site';
                                            $tabs['create_staging']['slug']='create_staging';
                                            $tabs['create_staging']['callback']=array($this->staging_create_ui, 'output_create_staging_site_page');
                                            $tabs['create_staging']['args']=$args;

                                            if(!is_multisite()){
                                                $args['span_class']='dashicons dashicons-welcome-write-blog wpvivid-dashicons-blue';
                                                $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                                $args['div_style']='';
                                                $args['hide']=1;
                                                $tabs['create_fresh_install']['title']='Create A Fresh WP Install';
                                                $tabs['create_fresh_install']['slug']='create_fresh_install';
                                                $tabs['create_fresh_install']['callback']=array($this->fresh_install_ui, 'output_create_wp_page');
                                                $tabs['create_fresh_install']['args']=$args;
                                            }

                                            global $wpvivid_staging;
                                            $args['span_class']='dashicons dashicons-admin-generic wpvivid-dashicons-blue';
                                            $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                            unset($args['hide']);
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

                                            global $wpvivid_staging;
                                            $args['span_class']='dashicons dashicons-admin-generic wpvivid-dashicons-blue';
                                            $args['span_style']='padding-right:0.5em;margin-top:0.1em;';
                                            $args['div_style']='';
                                            $tabs['staging_setting']['title']='Staging Settings';
                                            $tabs['staging_setting']['slug']='staging_setting';
                                            $tabs['staging_setting']['callback']=array($wpvivid_staging->setting, 'wpvivid_setting_add_staging_cell_addon');
                                            $tabs['staging_setting']['args']=$args;
                                        }

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
        </script>
        <?php
    }
    */