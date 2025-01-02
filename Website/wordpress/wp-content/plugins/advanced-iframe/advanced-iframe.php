<?php
/*
Plugin Name: Advanced iFrame
Plugin URI: https://1.envato.market/VDRDJ
Version: 2024.5
Text Domain: advanced-iframe
Domain Path: /languages
Author: Michael Dempfle
Author URI: https://www.tinywebgallery.com
Description: This plugin includes any webpage as shortcode in an advanced iframe or embeds the content directly.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/
define('_VALID_AI', '42');
define('AIP_URL', plugin_dir_url( __FILE__ ));
define('AIP_IMGURL', AIP_URL.'img');

// enable this if nothing is working anymore to see as much output as possible on the page.
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$freemiusConfig = dirname(__FILE__) . "/config-freemius.php";
$isFreemius = file_exists($freemiusConfig);
$isFreemiusMigration = false;

if ($isFreemius) {
	include $freemiusConfig;
}

if (isset($aip_standalone)) {
define('AIP_URL_CUSTOM', plugins_url() . '/../advanced-iframe-custom/');
} else {
define('AIP_URL_CUSTOM', plugins_url() . '/advanced-iframe-custom/');
}

include dirname(__FILE__) . '/includes/advanced-iframe-main-helper.php';
include dirname(__FILE__) . '/includes/advanced-iframe-main-cookie.php';

$aiVersion = '2024.5';
// check $aiJsSize

if (!class_exists('advancediFrame')) {
    class advancediFrame
    {
        var $adminOptionsName = 'advancediFrameAdminOptions';
		var $page = 'advanced-iframe';
        var $scriptsNeeded = false;
		var $renderIframe = true;
	
        /**
         * wp activate
         */
        function activate()
        {
            $options = $this->getAiAdminOptions();
            if (! wp_next_scheduled ( 'ai_check_iframes_event' )) {
	              wp_schedule_event(time(), 'daily', 'ai_check_iframes_event');
            }
            if ($options['src'] === '//www.tinywebgallery.com') {
                $options['src'] =  '//www.tinywebgallery.com/blog/advanced-iframe';
            } 
			update_option($this->adminOptionsName, $options);
			$this->resetMetaBoxes();
			$this->saveExternalJsFile();
        }
		
		function aiUpdate($upgrader_object, $options) {
			global $aiVersion;
			$current_plugin_path_name = plugin_basename( __FILE__ );
		  
			if ($options['action'] === 'update' && $options['type'] === 'plugin' && is_array($options['plugins'])) {
			   foreach($options['plugins'] as $each_plugin) {
				  if ($each_plugin==$current_plugin_path_name) {
					 $this->saveExternalJsFile(false);
				  }
			   }
			} else if ($options['action'] === 'install' && $options['type'] === 'plugin' ) {
				 $this->saveExternalJsFile(false);
			}		
		}
		
		function resetMetaBoxes() {
			$options = get_option($this->adminOptionsName);
            $current_user = wp_get_current_user();
            delete_user_meta( $current_user->ID, 'closedpostboxes_toplevel_page_advanced-iframe');		  
		    $closed_widgets = get_user_meta( $current_user->ID, 'closedpostboxes_toplevel_page_advanced-iframe', true ) ?: array();
            $closed_widgets = array('id-advanced-features','id-advanced-resize','id-advanced-part','id-advanced-hide'
			,'id-advanced-modify-iframe','id-advanced-modify-parent','id-advanced-layer','id-advanced-zoom','id-advanced-lazy','id-external-ai-overview'
			,'id-external-ai-config-files','id-external-ai-config-files-url','id-files-overview','id-file-parent','id-file-iframe','id-files-include'
			,'id-file-parent','id-help-video','id-help-faq','id-help-forum','id-help-support','id-help-id','id-help-jquery','id-help-browser'
			,'id-help-communication','id-help-twg','id-advanced-parameters','id-options-display', 'id-options-debug', 'id-options-check-monitoring', 
			'id-options-other-iframe','id-options-share', 'id-options-technical', 'id-options-delete', 'id-options-widget', 'id-options-vote');
			
			if (!empty($options['purchase_code'])) {
			    $closed_widgets[] = 'id-options-registration';
		    }
            update_user_meta( $current_user->ID, 'closedpostboxes_toplevel_page_advanced-iframe', $closed_widgets );
		}

        /*
         * wp deactivate
         */
        function deactivate() {
	          wp_clear_scheduled_hook('ai_check_iframes_event');
			  $options = get_option($this->adminOptionsName);
			  delete_transient( 'aip_cache_check_' . $options['src']);
        }

        function aiCheckIframes() {
            include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-functions.php';
            $options = get_option($this->adminOptionsName);
            if ($options['check_iframe_cronjob'] === 'true') {
               $before = time();
			   if (empty ($options['check_iframe_cronjob_email'])) {
                   $to = get_bloginfo('admin_email');
               } else {
                   $to = $options['check_iframe_cronjob_email'];
               }
               $result = ai_check_all_iframes($options['src'], $options['check_iframe_batch_size'], true);
               $total_time = time()-$before;

               $body = '<html><head>';
               $body .= '</head><body>';
               $body .= __('Hello advanced iframe pro user,', 'advanced-iframe');
               $body .= '<p>';
               if ($result['overall_status'] === 'red') {
                  $subject = __('At least one of your iframes failed!', 'advanced-iframe');
                  $body .= __('At least one of the iframes you have configured has a src which does not work properly.<br>Please go to the administration of advanced iframe pro to get more details.</p>', 'advanced-iframe');
				  			  
				  $body_links = '<ul>';		
			      $downgrade = 0;
				  foreach ($result['links'] as $iframes) {
                    foreach ($iframes['links'] as $link => $resultLink) {
					  if ($resultLink['status'] === 'red') {
                        $body_links .= '<li>Page: ' .  esc_html($iframes['link'])  . ', link: ' .  esc_attr($link)  . '</li>';
						if ($resultLink['http_downgrade']) {
							 $downgrade++;
						}
					  }
					}
				  }				
				  $body_links .= '</ul>';
				  				  
				  if ($downgrade > 0) {
				    $body .= '<p>'.$downgrade. ' of your iframes failed because your page was accessed by https and your iframes are http! Make sure that your users get redirected to the http version of your site or use https also in the iframes!</p>';
				  }
				  
				  $body .= __('<p>The following iframe(s) failed:', 'advanced-iframe'); 
				  $body .= $body_links; 
				  
               } else if ($result['overall_status'] === 'orange') {
                  $subject = __('At least one of your iframes is not optimal configured!', 'advanced-iframe');
                  $body .= __('At least one of the iframes you have configured has most likely a src which is redirected.<br>Please go to the administration of advanced iframe pro to get more details.</p><p>The following iframe(s) have issues: ', 'advanced-iframe');
				  $body .= '<ul>';		
			      foreach ($result['links'] as $iframes) {
                    foreach ($iframes['links'] as $link => $resultLink) {
					  if ($resultLink['status'] === 'orange') {
                        $body .= '<li>Page: ' .  esc_html($iframes['link'])  . ', link: ' .  esc_attr($link)  . '</li>';
					  }
					}
				  }				
				  $body .= '</ul>'; 
               } else {
                  $subject = __('All your iframes look good.', 'advanced-iframe');
                  $body .= __('All your iframes look good.<br>Please go to the administration of advanced iframe pro to get more details.', 'advanced-iframe');
               }
               $body .= '</p><p>';
               $body .= __('The test took ', 'advanced-iframe') . $total_time . __(' sec.', 'advanced-iframe');
               $body .= '</p>';
               $body .= __('Best regards,<br>Your advanced iframe plugin', 'advanced-iframe');
               $body .= '</body></html>';
               $headers = array('Content-Type: text/html; charset=UTF-8');
               if ($options['check_iframe_cronjob_email_always'] === 'true' || $result['overall_status'] === 'red' || $result['overall_status'] === 'orange' ) {
               wp_mail( $to, $subject, $body, $headers );
			   }
            }
        }

        /**
         * Set the iframe default
         */
        function iframe_defaults() {
            $auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : 'AUTH_KEY_MISSING';
            $iframeAdminOptions = array(
                'securitykey' => '',
                'src' => '//www.tinywebgallery.com/blog/advanced-iframe', 
				'width' => '100%',
                'height' => '600', 
				'scrolling' => 'none', 
				'marginwidth' => '0', 
				'marginheight' => '0',
                'frameborder' => '0', 
				'transparency' => 'true', 
				'loading' => 'lazy',
				'content_id' => '', 
				'content_styles' => '',
                'hide_elements' => '', 
				'class' => '', 
				'shortcode_attributes' => 'true', 
				'url_forward_parameter' => '',
                'id' => 'advanced_iframe', 
				'name' => '',
                'onload' => '', 
				'onload_resize' => 'false', 
				'onload_scroll_top' => 'false',
                'additional_js' => '', 
				'additional_css' => '', 
				'store_height_in_cookie' => 'false',
                'additional_height' => '0', 
				'iframe_content_id' => '', 
				'iframe_content_styles' => '',
                'iframe_hide_elements' => '', 
				'version_counter' => '1', 
				'onload_show_element_only' => '',
                'include_url' => '', 
				'include_content' => '', 
				'include_height' => '', 
				'include_fade' => '',
                'include_hide_page_until_loaded' => 'false', 
				'donation_bottom' => 'false',
                'onload_resize_width' => 'false', 
				'resize_on_ajax' => '', 
				'resize_on_ajax_jquery' => 'true',
                'resize_on_click' => '', 
				'resize_on_click_elements' => 'a', 
				'hide_page_until_loaded' => 'false',
                'show_part_of_iframe' => 'false', 
				'show_part_of_iframe_x' => '100', 
				'show_part_of_iframe_y' => '100',
                'show_part_of_iframe_width' => '400', 
				'show_part_of_iframe_height' => '300',
                'show_part_of_iframe_new_window' => '', 
				'show_part_of_iframe_new_url' => '',
                'show_part_of_iframe_next_viewports_hide' => 'false', 
				'show_part_of_iframe_next_viewports' => '',
                'show_part_of_iframe_next_viewports_loop' => 'false', 
				'style' => '',
                'use_shortcode_attributes_only' => 'false',
				'enable_external_height_workaround' => 'external',
                'keep_overflow_hidden' => 'false',
				'hide_page_until_loaded_external' => 'false',
                'onload_resize_delay' => '',
				'expert_mode' => 'false',
                'show_part_of_iframe_allow_scrollbar_vertical' => 'false',
                'show_part_of_iframe_allow_scrollbar_horizontal' => 'false',
                'hide_part_of_iframe' => '',
				'change_parent_links_target' => '',
                'change_iframe_links' => '',
				'change_iframe_links_target' => '',
                'browser' => '',
				'show_part_of_iframe_style' => '',
                'map_parameter_to_url' => '',
				'iframe_zoom' => '',
                'accordeon_menu' => 'no',
                'show_iframe_loader' => 'false',
                'tab_visible' => '',
				'tab_hidden' => '',
                'enable_responsive_iframe' => 'false',
                'allowfullscreen' => 'false',
				'iframe_height_ratio' => '',
                'enable_lazy_load' => 'false',
				'enable_lazy_load_threshold' => '3000',
                'enable_lazy_load_fadetime' => '0',
				'enable_lazy_load_manual' => 'false',
                'pass_id_by_url' => '',
				'include_scripts_in_footer' => 'false',
                'write_css_directly' => 'false',
				'resize_on_element_resize' => '',
                'resize_on_element_resize_delay' => '250',
				'add_css_class_parent' => 'false',
                'auto_zoom' => 'false',
				'auto_zoom_by_ratio' => '',
				'enable_lazy_load_manual_element' => '',
                'alternative_shortcode' => '',
                'iframe_redirect_url' => '',
				'install_date' => 0,
                'show_part_of_iframe_last_viewport_remove' => 'false',
                'load_jquery' => 'true', 
				'show_iframe_as_layer' => 'false',
                'add_iframe_url_as_param' => 'false', 
				'add_iframe_url_as_param_direct' => 'false',
				'add_iframe_url_as_param_prefix' => '',
				'use_iframe_title_for_parent' => 'false',
                'reload_interval' => '', 
				'iframe_content_css' => '',
                'additional_js_file_iframe' => '', 
				'additional_css_file_iframe' => '',
                'add_css_class_iframe' => 'false', 
				'editorbutton' => 'src,width,height',
                'iframe_zoom_ie8' => 'false', 
				'enable_lazy_load_reserve_space' => 'true',
                'hide_content_until_iframe_color' => '', 
				'use_zoom_absolute_fix' => 'false',
                'include_html' => '', 
				'enable_ios_mobile_scolling' => 'false',
                'sandbox' => '',
				'show_iframe_as_layer_header_file' => '',
                'show_iframe_as_layer_header_height' => '100', 
				'show_iframe_as_layer_header_position' => 'top',
                'resize_min_height' => '1',
				'show_iframe_as_layer_full' => 'false',
                'demo' => 'false',
				'show_part_of_iframe_zoom' => 'false',
                'external_height_workaround_delay' => '0',
                'add_document_domain' => 'false',
				'document_domain' => '',
                'multi_domain_enabled' => 'true',
				'check_shortcode' => 'false',
                'use_post_message' => 'true', 
				'element_to_measure_offset' => '0',
                'data_post_message' => '',
				'element_to_measure' => 'default',
                'show_iframe_as_layer_keep_content' => 'true',
				'roles' => 'none',
                'parent_content_css' => '',
                'include_scripts_in_content' => 'false',
				'debug_js' => 'false',
                'check_iframe_cronjob' => 'false',
				'check_iframe_cronjob_email' => '',
                'enable_content_filter' => 'false',
				'add_ai_external_local' => 'false',
                'title' => '',
				'check_iframes_when_save' => 'false',
                'admin_was_loaded' => 'true',
				'check_iframe_url_when_load' => 'true',
                'modify_iframe_if_cookie' => 'false',
				'allow' => '',
                'safari_fix_url' => '',
				'external_scroll_top' => '',
                'change_iframe_links_href' => '',
				'delete_options_db' => 'false',
				'inline_config_file' => 'none',
				'replace_iframe_tags' => 'false',
				'check_iframe_batch_size' => 100,
				'check_iframe_cronjob_email_always' => 'true',
				'remove_elements_from_height' => '',
				'add_ai_to_all_pages' => 'false',
				'show_part_of_iframe_media_query' => '',
				'remove_page_param_from_query' => 'false',
				'src_hide' => '',
				'purchase_code' => '',
				'cookie_samesite_filter' => '',
				'show_iframe_as_layer_autoclick_delay' => '0',
				'show_iframe_as_layer_autoclick_hide_time' => '0',
				'fullscreen_button' => 'false',
				'fullscreen_button_hide_elements' => '',
				'fullscreen_button_full' => 'false',
				'fullscreen_button_style' => 'black',
                'optimize_rendering' => 'false',
				'referrerpolicy' => '',
				'add_surrounding_p' => 'false',
				'custom' => '',
				'enable_ai_content_pages' => 'false'
            );
            return $iframeAdminOptions;
        }

        /**
         * Get the admin options
         */
        function getAiAdminOptions()
        {
            $iframeAdminOptions = advancediFrame::iframe_defaults();
            $devOptions = get_option($this->adminOptionsName);
            if (!empty($devOptions)) {
                foreach ($devOptions as $key => $option)
                    $iframeAdminOptions[$key] = $option;
            } else {
                // new installations do now get postMessage as default
                if (file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php")) {
                    $iframeAdminOptions['use_post_message'] = 'true';
                    update_option($this->adminOptionsName, $iframeAdminOptions);		
				}	
            }
			// read the external config file if present.
			if (file_exists(dirname(__FILE__) . "/external_config.php")) {	 
				 include dirname(__FILE__) . '/external_config.php';
				 @unlink(dirname(__FILE__) . '/external_config.php');
			     update_option($this->adminOptionsName, $iframeAdminOptions);
			}
            return $iframeAdminOptions;
        }

        function aiAddHeader() {
            $devOptions = $this->getAiAdminOptions();
		
			// IE fix for cookies.
            header('P3P: CP="ALL DSP NID CURa ADMa DEVa HISa OTPa OUR NOR NAV DEM"');
			
			AdvancedIframeHelper::check_debug_get_parameter();
			AdvancedIframeCookie::addCookieSameSite($devOptions['cookie_samesite_filter']);
        }
		
		/**
		* Redirect to the real src. aiUrl has the hash where the real url is stored temp in the db. 
		*/
		function aiCheckRedirect() {
			if (isset($_GET['aiUrl']) ) {
				$url = urlencode($_GET['aiUrl']);
				$transient = "aip_" . $url; 
				if (false !== $redirectFull = get_transient($transient )) {
				  $redirectArray = explode('|', $redirectFull);
	              $redirect = $redirectArray[0];
				  if ($redirectArray[1] === '0') { // link can only be clicked once.
					  delete_transient( "aip_" . $url) ;
				  }
				  wp_redirect( $redirect , 307);
				  exit;
				}
		    }
		}

        /**
         * Initialize advance iframe 
         */
        function initAi() {
			
			$loaded = load_plugin_textdomain('advanced-iframe', false, dirname(plugin_basename(__FILE__)) . '/languages');
			
			$options = $this->getAiAdminOptions();
            if ($options['load_jquery'] === 'true') {
                wp_enqueue_script('jquery');
            }
            
            if (function_exists('register_block_type')) {
          	// Register our block editor script for Gutenberg.
            	wp_register_script(
            		'ai-block',
            		plugins_url( 'js/ai_block.js', __FILE__ ),
            		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
            	);
            
            	// Register our block, and explicitly define the attributes we accept.
            	register_block_type( 'ai/ai-block', array(
            		'attributes'      => array(
            			'src' => array(
            				'type' => 'string',
            			),
						'width' => array(
								'type' => 'string',
							),
						'height' => array(
								'type' => 'string',
							),
						'add_surrounding_p_boolean' => array(
								'type' => 'boolean',
							),
						'additional' => array(
								'type' => 'string',
							),
						'className' => array(
								'type' => 'string',
            			)
            		),
            		'editor_script'   => 'ai-block', // The script name we gave in the wp_register_script() call.
            		'render_callback' => 'render_ai_gutenberg',
            	) );
           }
           // end of Gutenberg   
		  
		   $add_iframe_url_as_param_prefix = $options['add_iframe_url_as_param_prefix'];
		   $map_parameter_to_url = $options['map_parameter_to_url'];
		   
		   if (empty($map_parameter_to_url)) {
		       $map_parameter_to_url = 'iframe';
		   }
		   
           if ($options['enable_ai_content_pages'] !== 'false') {	
		     register_post_type('ai_content_page', array(
				'capabilities' => array(
					'create_posts' => false, 
				),
				'labels'      => array(
					'name'          => __('AI content pages', 'advanced-iframe'),
					'singular_name' => __('AI content page', 'advanced-iframe'),
				),
				'public'      => true,
				'hierarchical' => true,
				'has_archive' => false,
				'show_in_admin_bar' => false, 
				'menu_icon'   => 'dashicons-admin-page',
				'map_meta_cap' => true,
				)
			 );
			 if ($options['enable_ai_content_pages'] === 'init') {
			    $options['enable_ai_content_pages'] = 'true';
				update_option($this->adminOptionsName, $options);
				flush_rewrite_rules(); 
			 }
           }
		   
		   
		   if (AdvancedIframeHelper::ai_startsWith($add_iframe_url_as_param_prefix,'hashrewrite')) {
				$map_parameter_to_url_array = explode(":", $map_parameter_to_url);
				$map_parameter_to_url = $map_parameter_to_url_array[0];
                if (count($map_parameter_to_url_array) === 1) {				
				    add_rewrite_rule(
					    '^(.*)/' . $map_parameter_to_url .  '/([^/]*)/?',
					    'index.php?pagename=$matches[1]&' . $map_parameter_to_url .  '=$matches[2]',
					    'top'
				    );
			   } else {
	                add_rewrite_rule(
					    '^(' . $map_parameter_to_url .  ')/([^/]*)/?',
					    'index.php?pagename=$matches[1]&' . $map_parameter_to_url .  '=$matches[2]',
					    'top'
				    );
               }
			   flush_rewrite_rules();
		    }
        }

        /* CSS and js for the admin area - only loaded when needed */
        function addAdminHeaderCode($hook) {
            $options = $this->getAiAdminOptions();
            // defaults
            extract(array('version_counter' => $options['version_counter'], 
                          'add_ai_external_local' => $options['add_ai_external_local']));
            if ($add_ai_external_local === 'admin' || $add_ai_external_local === 'all') {
                wp_enqueue_script('ai-external-js', plugins_url('js/ai_external.js', __FILE__), false, $version_counter);
            }  
            wp_enqueue_script('ai-js', plugins_url('js/ai.min.js', __FILE__), false, $version_counter);
            wp_localize_script( 'ai-js', 'MyAjax', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'aip-close-message-nonce' )
			));            
            if ($hook === 'settings_page_advanced-iframe' || $hook === 'toplevel_page_advanced-iframe') {
                wp_enqueue_style('ai-css', plugins_url('css/ai.css', __FILE__), false, $version_counter);
                // wp_enqueue_style('ai-css-print', plugins_url( 'css/ai-print.css' , __FILE__ ), false, $version_counter);
               
                wp_enqueue_script('ai-search', plugins_url('js/findAndReplaceDOMText.js', __FILE__), false, $version_counter);
                wp_enqueue_script( 'postbox' );
            }
           
            if ($hook === 'post.php' || $hook === 'post-new.php'  ) {
                if ($options['editorbutton'] != '' && $this->hasValidRole()) {
                    $additional_settings = '';
                        $elements = explode (',', $options['editorbutton']);
                        foreach ($elements as $setting) {
                            $setting = trim($setting);
                            if ($setting != 'securitykey') {
                                if (isset($options[$setting])) {
                                    $new_setting = $options[$setting];
                                    if (!empty($new_setting)) {
                                        $additional_settings .= ' ' . esc_html($setting) . '="' . esc_html(trim($new_setting)) . '"';
                                    }
                                }
                            }
                        }
                    $dep = ($options['load_jquery'] === 'true') ? array('jquery') : array();
                    wp_register_script('ai-editor-button', plugins_url('js/ai_editor_button.js', __FILE__), $dep, $options['version_counter'] , 'true');
                    $ai_button_settings_array = array('securitykey' => $options['securitykey'] , 'additionalSettings' => $additional_settings);
                    wp_localize_script( 'ai-editor-button', 'aiButtonSettings', $ai_button_settings_array );
                    wp_enqueue_script('ai-editor-button'); 
                }
            }             
        }  

        /* Add iframe button above the editor. */
        function addAiButton( $editor_id)
        {
            $options = get_option($this->adminOptionsName);
            if ($options['editorbutton'] != '' && $this->hasValidRole()) {
                echo '<a title="Insert Advanced iFrame" class="button insert-iframe-button" href="#" data-editor="' . esc_attr( $editor_id ) .'"><img style="padding-bottom:3px;" src="'. AIP_IMGURL . '/logo_16x16.png" />Add Advanced iFrame</a>';
            }
        }

        /* additional CSS for wp area */
        function addWpHeaderCode($atts)
        {
            $options = $this->getAiAdminOptions();	
            // defaults
            extract(array('additional_css' => $options['additional_css'],
                'additional_js' => $options['additional_js'],
                'version_counter' => $options['version_counter'],
                'enable_lazy_load' => $options['enable_lazy_load'],
                'include_scripts_in_footer' => $options['include_scripts_in_footer'],
                'add_css_class_parent' => $options['add_css_class_parent'],
                'add_ai_external_local' => $options['add_ai_external_local'],
                $atts));
            $to_footer = ($include_scripts_in_footer === 'true' && $add_css_class_parent === 'false');

            $older_version = version_compare(get_bloginfo('version'), '3.3') < 0; // wp < 3.3 - older version need to be included here
            $this->include_additional_files($additional_css, $additional_js, $version_counter, $older_version, $to_footer);

            $dep = ($options['load_jquery'] === 'true') ? array('jquery') : array();
            wp_enqueue_script('ai-js', plugins_url('js/ai.min.js', __FILE__), $dep, $version_counter, $to_footer);
			wp_localize_script( 'ai-js', 'MyAjax', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'security' => wp_create_nonce( 'aip-parameter-nonce' )
			));
			
            if ($add_ai_external_local === 'true' || $add_ai_external_local === 'all') {
                wp_enqueue_script('ai-external-js', plugins_url('js/ai_external.js', __FILE__), $dep, $version_counter, $to_footer);
            }
        }
		
		
        function addAiExternalLocal($atts) {
            $options = get_option($this->adminOptionsName);
            $dep = ($options['load_jquery'] === 'true') ? array('jquery') : array();
            // we add this independant of the main settings to make the feature more save.
            wp_register_script('ai-external-js', plugins_url('js/ai_external.js', __FILE__), $dep, $options['version_counter'] , 'true');
            $ai_external_settings_array = array('domainMultisite' => 'true', 'usePostMessage' => true);
            wp_localize_script( 'ai-external-js', 'aiExternalSettings', $ai_external_settings_array );
            wp_enqueue_script('ai-external-js');
         }

        function addCustomCss($parent_content_css) {
             if (!empty($parent_content_css)) {
                 return wp_kses($parent_content_css, array());
             }
             return '';
        }

        /**
         * Checks the parameter and returns the value. If only chars on the whitelist are in the request nothing is done
         * Otherwise it is returned encoded.
         */
        function param($param, $content = null)
        {
            // get and post parameters are checked. if both are set the get parameter is used.
            $value = isset($_GET[$param]) ? $_GET[$param] : (isset($_POST[$param]) ? $_POST[$param] : '');

            if (is_array($value)) {
				$value = $value[0];
			}
            $value_check = $value;
            // first we decode the param to be sure the it is not already encoded or doubleencoded as part of an attack
            while ($value_check != @urldecode($value_check)) {
                $value_check = @urldecode($value_check);
            }
            // If all chars are in the whitelist no additional encoding is done!
            if (@preg_match('/^[\.@~a-zA-Z0-9À-ÖØ-öø-ÿ\-\|\)\(]*$/', $value_check)) {
                return $value;
            } else {
                return @urlencode($value);
            }
        }

        function addPx($value)
        {
            if (strpos($value, '-') === false && strpos($value, '+') === false) {
                $value = trim($value);
                if (strpos($value, 'px') === false && strpos($value, 'px') === false &&
                    strpos($value, '%') === false && strpos($value, '%') === false &&
                    strpos($value, 'vw') === false && strpos(strtolower($value), 'vh') === false
                ) {
                    $value = $value . 'px';
                }
            }
            return $value;
        }
        
        /**
         * Formats the cacl string that it works even when entered slightly wrong
         */
        function formatCalcString($value) {
           $value = preg_replace('/\s+/', '', $value);
           $value = str_replace("-", " - ", $value);
           $value = str_replace("+", " + ", $value);
           $elements_px = array_map(array($this, 'addPx') ,explode(' ', $value));
           return implode(' ', $elements_px);
        }

        /**
         * renders the iframe script
         */
        function do_iframe_script($atts, $content = null) {
            global $isFreemiusMigration;			
			$start = microtime(true);
			// Avoids that iframes are called before the body!
			if ($this->renderIframe === false) {
				if (isset($_COOKIE['aiEnableDebugConsole'])) { 
				    echo "<!-- advanced iframe not rendered -->";
				}
				return "";
			}
			
            global $aip_standalone, $iframeStandaloneDefaultOptions, $iframeStandaloneOptions, $isFreemius;
			if (!is_array($atts)) {
				$atts = array(); 
			}

            $isValidBrowser = true;
            $html = ''; // the output
            $error_html = ''; // any error 
            include dirname(__FILE__) . '/includes/advanced-iframe-main-read-config.php';
           
            if ($error_html) {
                return $error_html;
            }
            if (!$isValidBrowser) {
                return;
            }

            $error_html = include dirname(__FILE__) . '/includes/advanced-iframe-main-css.php';
            if ($error_html) {
                return $error_html;
            }

            // check if the ai_external.js does exist
            $script_name = dirname(__FILE__) . '/js/ai_external.js';
            if (!isset($aip_standalone) && !file_exists($script_name)) {
                $retValue = $this->saveExternalJsFile(false);
                if (!empty($retValue)) {
                    return $error_css . '<div class="errordiv">' . $retValue . '</div>';
                }
            }

            if ($options['securitykey'] != '' && $options['securitykey'] != $securitykey && empty($alternative_shortcode)) {
                return $error_css . '<div class="errordiv">' . __('No valid security key found. Please use at least the following shortcode:<br>&#91;advanced_iframe securitykey="&lt;your security key - see settings&gt;"&#93;<br /> Please also check in the html mode that your shortcode does only contain normal spaces and not a &amp;nbsp; instead. It is also possible that you use wrong quotes like &#8220; or &#8221;. Only &#34; is valid!', 'advanced-iframe') . '</div>';
            } else if ($src === "not set" && empty($include_url) && empty($include_html)) {
                return $error_css . '<div class="errordiv">' . __('You have set "Use shortcode attributes only" (use_shortcode_attributes_only) to "true" which means that you have to specify all parameters as shortcode attributes. Please specify at least "securitykey" and "src". Examples are available in the administration.', 'advanced-iframe') . '</div>';
            } else {
                if (empty($include_url) && empty($include_html)) {
                    $error_html = include dirname(__FILE__) . '/includes/advanced-iframe-main-prepare.php';
					if ($error_html) {
                        return $error_html;
                    }
                    $error_html = include dirname(__FILE__) . '/includes/advanced-iframe-main-iframe.php';
                    if ($error_html) {
                        return $error_html;
                    }
					include dirname(__FILE__) . '/includes/advanced-iframe-main-after-iframe.php';
                } else {
                    include dirname(__FILE__) . '/includes/advanced-iframe-main-include-directly.php';
                }
				if ($debug_js === 'bottom') {  
				    $time_elapsed_secs = sprintf('%.6f', (microtime(true) - $start));
				    $html .= "<!-- advanced iframe rendered in " . $time_elapsed_secs . " s -->";
				}
                return $html;
            }
        }

        /**
         * Enqueue the additional js or css
         */
        function include_additional_files($additional_css, $additional_js, $version_counter, $version, $to_footer)
        {
            if ($additional_css != '' && $version) { // wp >= 3.3
                wp_enqueue_style('additional-advanced-iframe-css', $additional_css, false, $version_counter);
            }
            if ($additional_js != '' && $version) { // wp >= 3.3
                wp_enqueue_script('additional-advanced-iframe-js', $additional_js, false, $version_counter, $to_footer);
            }
        }

        function add_script_footer()
        {
		    $devOptions = $this->getAiAdminOptions();
			if ($devOptions['add_ai_to_all_pages'] === 'true') {
            	$shortcode = '[advanced_iframe';
				$securitykey = $devOptions['securitykey'];
				if ($securitykey !== '') {
					 $shortcode .= ' securitykey="' . $securitykey . '"'; 
				}
                echo do_shortcode($shortcode .']');		
			}	
			
            if (!$this->scriptsNeeded) {
                if ($devOptions['enable_content_filter'] === 'true' && isset($_GET['ai-show-id-only'])) {
                    $ai_show_id_only = $_GET['ai-show-id-only'];
                    
                    echo '<script type="text/javascript">var ai_show_id_only = "' . esc_js($ai_show_id_only) . '"</script>';
                    echo '<style>html, body { margin: 0px !important; padding: 0px !important; }</style>';
                } else { 
					wp_dequeue_script('ai-js');
                }
                $add_ai_external_local = $devOptions['add_ai_external_local'];
                // if ai_external.js is included locally we do not dequeue additional javascript!
                // because we can place the code for the config switcher there easily.
                if ($add_ai_external_local === 'false') {
                  wp_dequeue_script('additional-advanced-iframe-js');
                }
                wp_dequeue_script('ai-change-js');
                wp_dequeue_script('ai-lazy-js');
            } else {
                // for this single line no external script file is used! 
                echo '<script type="text/javascript">if(window.aiModifyParent) {aiModifyParent();}</script>';
            }
        }

        function printAdminPage()
        {
            $this->ai_createCustomFolder();
            require_once('advanced-iframe-admin-page.php');
        }

        function saveExternalJsFile($backend = true)
        {
            global $aiVersion;
			$devOptions = $this->getAiAdminOptions();
            $template_name = dirname(__FILE__) . '/js/ai_external.template.js';

            $jquery_path = site_url() . '/wp-includes/js/jquery/jquery.js';
            $jquery_path = preg_replace("(^https?:)", "", $jquery_path); 
			$plugins_url = preg_replace("(^https?:)", "", plugins_url());
			
			$content = file_get_contents($template_name);
            $newContent = str_replace('PLUGIN_URL', $plugins_url . '/advanced-iframe', $content);
            $newContent = str_replace('PARAM_ID', $devOptions['id'], $newContent);
            $newContent = str_replace('PARAM_IFRAME_HIDE_ELEMENTS', $devOptions['iframe_hide_elements'], $newContent);
            $newContent = str_replace('PARAM_ONLOAD_SHOW_ELEMENT_ONLY', $devOptions['onload_show_element_only'], $newContent);
            $newContent = str_replace('PARAM_IFRAME_CONTENT_ID', $devOptions['iframe_content_id'], $newContent);
            $newContent = str_replace('PARAM_IFRAME_CONTENT_STYLES', $devOptions['iframe_content_styles'], $newContent);
            $newContent = str_replace('PARAM_CHANGE_IFRAME_LINKS_TARGET', $devOptions['change_iframe_links_target'], $newContent);
            $newContent = str_replace('PARAM_CHANGE_IFRAME_LINKS_HREF', $devOptions['change_iframe_links_href'], $newContent);
            $newContent = str_replace('PARAM_CHANGE_IFRAME_LINKS', AdvancedIframeHelper::replace_brackets($devOptions['change_iframe_links']), $newContent);

            $delay = empty($devOptions['external_height_workaround_delay']) ? '0' : $devOptions['external_height_workaround_delay'];
            $newContent = str_replace('PARAM_ENABLE_EXTERNAL_HEIGHT_WORKAROUND_DELAY', $delay, $newContent);

            // external and true = true, false = false
            $isExternal = ($devOptions['enable_external_height_workaround'] === 'false') ? 'false': 'true';
            $newContent = str_replace('PARAM_ENABLE_EXTERNAL_HEIGHT_WORKAROUND', $isExternal, $newContent);
            $newContent = str_replace('PARAM_KEEP_OVERFLOW_HIDDEN', $devOptions['keep_overflow_hidden'], $newContent);
            $newContent = str_replace('PARAM_HIDE_PAGE_UNTIL_LOADED_EXTERNAL', $devOptions['hide_page_until_loaded_external'], $newContent);
            $newContent = str_replace('PARAM_IFRAME_REDIRECT_URL', $devOptions['iframe_redirect_url'], $newContent);
            $newContent = str_replace('PARAM_ENABLE_RESPONSIVE_IFRAME', $devOptions['enable_responsive_iframe'], $newContent);
            $newContent = str_replace('PARAM_WRITE_CSS_DIRECTLY', $devOptions['write_css_directly'], $newContent);
            $newContent = str_replace('PARAM_RESIZE_ON_ELEMENT_RESIZE_DELAY', $devOptions['resize_on_element_resize_delay'], $newContent);
            $newContent = str_replace('PARAM_RESIZE_ON_ELEMENT_RESIZE', $devOptions['resize_on_element_resize'], $newContent);
            $newContent = str_replace('PARAM_URL_ID', $devOptions['pass_id_by_url'], $newContent);

            $newContent = str_replace('PARAM_JQUERY_PATH', $jquery_path, $newContent);
            $newContent = str_replace('PARAM_ADD_IFRAME_URL_AS_PARAM', $devOptions['add_iframe_url_as_param'], $newContent);
			$newContent = str_replace('PARAM_USE_IFRAME_TITLE_FOR_PARENT', $devOptions['use_iframe_title_for_parent'], $newContent);		
            $newContent = str_replace('PARAM_ADDITIONAL_CSS_FILE_IFRAME', $devOptions['additional_css_file_iframe'], $newContent);
            $newContent = str_replace('PARAM_ADDITIONAL_JS_FILE_IFRAME', $devOptions['additional_js_file_iframe'], $newContent);
            $newContent = str_replace('PARAM_ADD_CSS_CLASS_IFRAME', $devOptions['add_css_class_iframe'], $newContent);
            $newContent = str_replace('PARAM_TIMESTAMP', date("Y-m-d H:i:s"), $newContent);

            $newContent = str_replace('MULTI_DOMAIN_ENABLED', $devOptions['multi_domain_enabled'], $newContent);
            $newContent = str_replace('USE_POST_MESSAGE', ($devOptions['use_post_message'] != 'false') ? 'true':'false' , $newContent);
            $newContent = str_replace('DEBUG_POST_MESSAGE', ($devOptions['use_post_message'] === 'debug') ? 'true':'false' , $newContent);
            $newContent = str_replace('DATA_POST_MESSAGE', $devOptions['data_post_message'], $newContent);

            $newContent = str_replace('PARAM_SEND_CONSOLE_LOG', ($devOptions['debug_js'] === 'bottom') ? 'true':'false' , $newContent);

            $asParts = parse_url(site_url()); // PHP function
            $home_url = (AdvancedIframeHelper::isSecure() ? 'https' : 'http') . '://' . $asParts['host'];
            $post_domain = ($devOptions['multi_domain_enabled'] === 'true') ? '*' : $home_url;
            $newContent = str_replace('POST_MESSAGE_DOMAIN', $post_domain, $newContent);

            $newContent = str_replace('PARAM_ELEMENT_TO_MEASURE_OFFSET', $devOptions['element_to_measure_offset'], $newContent);
            $newContent = str_replace('PARAM_MODIFY_IFRAME_IF_COOKIE', $devOptions['modify_iframe_if_cookie'], $newContent);
            $newContent = str_replace('PARAM_ELEMENT_TO_MEASURE', $devOptions['element_to_measure'], $newContent);
            $newContent = str_replace('PARAM_SCROLL_TO_TOP', $devOptions['external_scroll_top'], $newContent);
            $newContent = str_replace('PARAM_SRC_HIDE', $devOptions['src_hide'], $newContent);
			$newContent = str_replace('PARAM_VERSION', $aiVersion, $newContent);

			// include a inline JS
			$inlineConfigFile = $devOptions['inline_config_file'];
			$inlineConfigContent = '';
			if ($inlineConfigFile !== 'none') {
				// do it
				$fileName = dirname(__FILE__) . '/../advanced-iframe-custom/' . $inlineConfigFile;
				if (file_exists($fileName)) {
				    $inlineConfigContent = trim(file_get_contents($fileName));	
				}
			} 
			
			$newContent = str_replace('/* PARAM_INLINE_CONFIG_FILE */', $inlineConfigContent, $newContent);
			
			clearstatcache();
						
            $script_name = 'ai_external.js';
			$returnValue = $this->handleWorkaroundFile($script_name, $newContent, $backend);
			
			if ($returnValue !== '') {
			    return $returnValue;	 
			}
				
            $this->createMinimizedAiJs($backend);				
            return $this->createMinimizedAiExternalJs($newContent, $backend);
        }
		
		function createMinimizedAiExternalJs($newContent,$backend) {
			global $aiVersion;
			$url = 'https://www.toptal.com/developers/javascript-minifier/api/raw';     
			
		    $postdata = array('http' => array(
	            'method'  => 'POST',
	            'header'  => 'Content-type: application/x-www-form-urlencoded',
	            'content' => http_build_query( array('input' => $newContent) ),
                'timeout' => 10 ) );
				
		    $minifiedContent = @file_get_contents($url, false, stream_context_create($postdata));
	       
	        if ($minifiedContent === false) {
	        	$minifiedContent = $newContent;
	 		} else {
				// double check if it is somehow the expected content.
				if ($this->aiContains($minifiedContent , 'var extendedDebug') && $this->aiContains($minifiedContent , 'aiAddOnloadEvent(aiExecuteWorkaround')) {
				    $type = $this->aiContains($newContent, 'Advanced iframe pro') ? 'pro' : 'free';
				    $minifiedContent = '/** Advanced iframe '.$type.' external workaround file ' . $aiVersion . '. Created: '.date("Y-m-d H:i:s")." */\n" . $minifiedContent;	
				} else {
					$minifiedContent = $newContent;
				}
			}
	
			$script_name = 'ai_external.min.js';
			return $this->handleWorkaroundFile($script_name, $minifiedContent, $backend);	
		}
				
		function createMinimizedAiJs($backend) {
			global $aiVersion;
			$aiJsSize = 89611;
			$newContent = file_get_contents(dirname(__FILE__) . '/js/ai.js');
			$oldFileName = dirname(__FILE__) . '/js/ai.min.js';
			if ((strlen($newContent) == $aiJsSize) && file_exists($oldFileName)) {		   
			   return;
			}
			$url = 'https://www.toptal.com/developers/javascript-minifier/api/raw';     
			
		    $postdata = array('http' => array(
	            'method'  => 'POST',
	            'header'  => 'Content-type: application/x-www-form-urlencoded',
	            'content' => http_build_query( array('input' => $newContent) ),
                'timeout' => 10 ) );
		    $minifiedContent = @file_get_contents($url, false, stream_context_create($postdata));

			if ($minifiedContent === false) {
				$minifiedContent = $newContent;
			} else {
				// double check if it is somehow the expected content.
				if ($this->aiContains($minifiedContent , 'aiExtraSpace') && $this->aiContains($minifiedContent , 'aiOpenIframeOnClick')) {
				    $type = $this->aiContains($newContent, 'Advanced iframe pro') ? 'pro' : 'free';
					$minifiedContent = '/** Advanced iframe '.$type.' functions v' . $aiVersion . '. Created: '.date("Y-m-d H:i:s")." */\n" . $minifiedContent;	
				} else {
					$minifiedContent = $newContent;
				}				
			}
	
			$script_name = 'ai.min.js';
			return $this->handleWorkaroundFile($script_name, $minifiedContent, $backend);	
		}
			
		function handleWorkaroundFile($name, $newContent, $backend) {
			$script_name = dirname(__FILE__) . '/js/' . $name;
            
            if (@file_exists($script_name)) {
                if (!@unlink($script_name)) {
                    if ($backend) {
                        $errorText = __('The file "advanced-iframe/js/'.$name.'" can not be removed before saving. Please check the permissions of the js folder and the '.$name.' and save the settings again.', "advanced-iframe");
                        AdvancedIframeHelper::aiPrintError($errorText);
                    }
                    return '';
                }
            }
            $fh = @fopen($script_name, 'w');
            if ($fh) {
                @fwrite($fh, $newContent);
                @fclose($fh);
            } else {
                $errorText = __('The file "advanced-iframe/js/'.$name.'" can not be saved. Please check the permissions of the js folder and save the settings again.', "advanced-iframe");
                if ($backend) {
                    AdvancedIframeHelper::aiPrintError($errorText);
                } else {
                    return $errorText;
                }
            }
			return '';
		}

        function ai_startsWith($haystack, $needle) {
            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
        }

        function ai_endsWith($haystack, $needle) {         
		 return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
        }
		
	function aiContains($str, $substr) {
            return strpos($str, $substr) !== false;
        }
	function aiContainsParam($str, $substr) {
            $substr .= '=';
	    return strpos($str, '?' . $substr) !== false || strpos($str, '&amp;' . $substr) !== false;
        }
		
		/**
		* remove query string and trailing backslash
		*/
		function aiRemoveQueryString($str) {
		    if ($this->aiContains($str,'?')) {
                $value = strstr($str, '?', true);
				return rtrim($value, '/');
			} else {
			    return $str;	
			}		
		}
		
        function ai_createCustomFolder()
        {
            $filenamedir = dirname(__FILE__) . '/../advanced-iframe-custom';
            if (!@file_exists($filenamedir)) {
                if (!@mkdir($filenamedir)) {
                    echo 'The directory "advanced-iframe-custom" could not be created parallel to the advanced iframe plugin folder. Custom files are stored in this directory because Wordpress does delete the normal plugin folder during an update. Please create the folder manually.';
                    return false;
                }
            }
            $customPluginName = 'advanced-iframe-custom.php'; 
            if (!file_exists($filenamedir . '/' . $customPluginName)) { 
                $src = dirname(__FILE__) .'/custom/custom.txt'; 
                @copy($src, $filenamedir . '/' . $customPluginName);
            }
            if (!file_exists($filenamedir . '/readme.txt')) {
                $src = dirname(__FILE__) .'/custom/readme.txt'; 
                @copy($src, $filenamedir . '/readme.txt');
            }
			 if (!file_exists($filenamedir . '/hide_fullscreen.html')) {
                $src = dirname(__FILE__) .'/custom/hide_fullscreen.html'; 
                @copy($src, $filenamedir . '/hide_fullscreen.html');
            }
        }

        function checkIE8()
        {
            $filenamedir = dirname(__FILE__) . '/../advanced-iframe-custom/browser-check-failed.txt';
            if (file_exists($filenamedir)) {
                return false;
            } else {
                $fh = @fopen($filenamedir, 'w');
                if ($fh) {
                    @fwrite($fh, "Browser detection crashed. Please increase your php memory, delete this file and retry.");
                    @fclose($fh);
                }
                @unlink($filenamedir);
                return ai_is_ie(8);
            }
        }

        function ai_plugin_action_links($links, $file)
        {
            $plugin_file = basename(__FILE__);
            $file = basename($file);
            if ($file === $plugin_file) {
                $settings_link = '<a href="admin.php?page=advanced-iframe">' . __('Settings', 'advanced-iframe') . '</a>';
                array_unshift($links, $settings_link);
            }
            return $links;
        }
		
	function ai_preview_post_link($link) {	
		$sep = (strpos($link, '?') === false) ? '?': "&";
		return $link . $sep . 'aiEnableCheckShortcode=true';
	}

        function aiCheckContent($content) {
              // content contains [advanced AND role is not enough....
              if (!$this->hasValidRole() && strpos($content, '[advanced_iframe') !== false) {
                  set_transient("ai_no_rights_post_errors", __('This page/post contains an advanced iframe shortcode but you don\'t have the needed role to use this plugin. Please contact your system administrator to get the needed role.', 'advanced-iframe'), 20);
                  wp_redirect( admin_url());
              }
        return $content;
        }
		
		function aiTemplateRedirect() {
            $options = $this->getAiAdminOptions();
		if (isset($options['optimize_rendering']) && $options['optimize_rendering'] === "true") {
		        $this->renderIframe = false;	
            }
		}
		
		function aiWpHead() {
			$this->renderIframe = true;
		}	
		
		function aiHandleTempPages($content) {
			if (empty($content)) {
				return $content;
			} else {
				$devOptions = get_option($this->adminOptionsName);
	            return AdvancedIframeHelper::handleTempPages($content, $devOptions, $this->adminOptionsName);
			}
		}
		
		function aiDelecteAiContentPages() {
		    AdvancedIframeHelper::aiDelecteAiContentPages();
		}
		
		function aiReplaceIframes($content) {
		    // This prevents that this method is called multiple times.
			static $aiReplaceIframes_called = false;
			if ($aiReplaceIframes_called || empty($content)) {
				return $content;
			};
			$aiReplaceIframes_called = true;
			$devOptions = get_option($this->adminOptionsName);
		
		    $content = AdvancedIframeHelper::handleTempPages($content, $devOptions, $this->adminOptionsName);

			if ($devOptions['replace_iframe_tags'] !== 'true') {
			    return $content;
			}
			
			$dom = new DOMDocument;
            @$dom->loadHTML($content);
            $iframes = $dom->getElementsByTagName('iframe');
 
            //Iterate over the extracted iframes and create advanced iframe shortcodes
			foreach ($iframes as $iframe){
				$attributes = array('src','width','height','id','name','class','style');
				//Extract and show the "href" attribute. 
				$shortcode = '[advanced_iframe';
				$securitykey = $devOptions['securitykey'];
				if ($securitykey !== '') {
					 $shortcode .= ' securitykey="' . $securitykey . '"'; 
				}
							
				foreach ($attributes as $attribute){
				  $value = $iframe->getAttribute($attribute);
				  if ($value !== '') {
					  $shortcode .= ' ' . $attribute . '="' . $value . '"'; 
				  }
				}
				$shortcode .= ']';
				$content = preg_replace("/<iframe\s(.+?)>(.*?)<\/iframe>/is", $shortcode, $content);
			}
			return $content;
		}
		

        function aiShowValidationErrors() {
            global $aiVersion;
			global $isFreemius;
			if ( $error = get_transient( "ai_no_rights_post_errors" ) ) { ?>
                <div class="error">
                    <p><?php echo $error ?></p>
                </div><?php
                 delete_transient("ai_no_rights_post_errors");
            }
            if ( $error = get_transient( "ai_save_post_errors" ) ) { ?>
                <div class="error">
                    <p><?php echo $error; ?></p>
                </div><?php
                delete_transient("ai_save_post_errors");
            }
            if ( $error = get_transient( "ai_save_post_execution" ) ) { ?>
                <div class="error">
                    <p><?php echo $error; ?></p>
                </div><?php
                delete_transient("ai_save_post_execution");
            }
			 if ( $error = get_transient( "ai_save_post_unfiltered_html" ) ) { ?>
                <div class="error">
                    <p><?php echo $error; ?></p>
                </div><?php
                delete_transient("ai_save_post_unfiltered_html");
            }
			
			$this->ai_getlatestVersion(); 
            $devOptions = get_option($this->adminOptionsName);
			
			$hasDiscount = get_transient('aip_discount') === "true";
			$showDiscountMessage = $hasDiscount && !(isset($devOptions['closed_messages']) && isset($devOptions['closed_messages']['show-discount-message']));

			if ($showDiscountMessage) {  
			    echo '<script>jQuery(document).on( "click touchstart", "#show-discount-message.is-permanent-closable button", function() { closeInfoPermanent("show-discount-message"); });</script>';
				echo '<div id="show-discount-message" class="notice notice-success is-dismissible is-permanent-closable"><p><strong>';
				echo get_transient('aip_discount_message');
				echo '</strong></p></div>';
			} 

            $screen = get_current_screen();
			$viewsPercent = get_option('default_a_options') / 100;
			
		    if ((file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php")) && empty($devOptions['purchase_code']) && !strstr( $screen->id, $this->page ) && !$isFreemius ) {
				printf(
					'<div class="%s"><p>%s <a href="%s">%s</a>%s</p>',
					'notice notice-error',
					__( 'To unlock advanced iframe pro please add your personal purchase code in the ', 'advanced-iframe' ),
					admin_url( 'admin.php?page=advanced-iframe'),
					__( 'Advanced iFrame administration', 'advanced-iframe' ),
					__( ' on the options tab. Otherwise you will still have the view limit of the free version where a notice is shown below all iframes.', 'advanced-iframe' )
				);
				if ($viewsPercent > 100) {
					_e('<p><strong style="color: red"> The monthly view li'.'mit is reached. A small no'.'tice is shown bel'.'ow all iframes.</strong></p>', 'advanced-iframe');
				}	 
				echo '</div>';
		    } else if ($viewsPercent > 100 && !(file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php")) && !strstr( $screen->id, $this->page )) {
				printf(
					'<div class="%s">%s<p>%s <a href="%s">%s</a>.</p>',
					'notice notice-error',
					__('<p><strong style="color: red"> The monthly view li'.'mit of advanced iframe free is reached. A small no'.'tice is shown bel'.'ow all iframes.</strong></p>', 'advanced-iframe'),	 
					__( 'Please update to the pro version. For more details go to the options tab of the ', 'advanced-iframe' ),
					admin_url( 'admin.php?page=advanced-iframe'),
					__( 'Advanced iFrame administration', 'advanced-iframe')
				);	
				echo '</div>';
			}
        }
		
		function ai_getlatestVersion() {
			global $aiVersion;
			global $isFreemius;
			
			$aip_version = get_transient('aip_version');
		    if ($aip_version !== false) {
			    return $aip_version;
			} else { 
				$version_info = 0;
				$devOptions = get_option($this->adminOptionsName);
				$purchaseCode = (isset($devOptions['purchase_code']) && strlen($devOptions['purchase_code']) === 36) ? $devOptions['purchase_code'] : 'NOT_SET';  
				$pro = (file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php")) ? "1" : "2";
				$default_key = "put your unique phrase here";
			    $auth_key = defined( 'AUTH_KEY' ) ? ((AUTH_KEY == $default_key) ? get_site_url() : substr(AUTH_KEY, -2)) : get_site_url();
				$urls = 'https://www.tinywebgallery.com/updatecheck/getAipVersion.php';
				$rand = substr(md5(microtime()),rand(0,26),2);
				$data = http_build_query(array('url' => get_site_url(), 'codehash' => $rand . base64_encode($purchaseCode)   , 'sitehash' => hash('sha256', $auth_key), 'type' => $pro, 'version' =>  $aiVersion, 'isFreemius' => $isFreemius ? 'true' : 'false'), '', '&'); 
				// use key 'http' even if you send the request to https://...
				$options = array(
				   'ssl' => array(
						 "verify_peer"=>false,
						 "verify_peer_name"=>false
				   ),
				  'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n" .
					  "User-Agent: Mozilla/5.0 (Windows 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36\r\n",
					'method'  => 'POST',
					'content' => $data,
					'timeout' => 10
				  )
				);
				$context = stream_context_create($options);
				$result = @file_get_contents($urls, false, $context);
				if ($result === FALSE) { // Handle error 
				    $version_info = -1; 
				} 

				if ($version_info === 0) {
				  $version_info_array = json_decode(utf8_encode($result), true);		
				  $version_info = $version_info_array['version'];
				  if ($version_info_array['discount'] === "true") {
					  set_transient('aip_discount', 'true', 60 * 60 * 12);
					  set_transient('aip_discount_message', $version_info_array['discountMessage'], 60 * 60 * 12);					  
				  } else {		   
					   if (isset($devOptions['closed_messages'])) {
						  $closed_messages = $devOptions['closed_messages'];
						  unset($closed_messages['show-discount-message']);
						  $devOptions['closed_messages'] = $closed_messages;
		                  update_option($this->adminOptionsName, $devOptions);
					   } 
				  }
                  
                  if ($version_info_array['blocked'] === "true" && $devOptions['purchase_code'] !== '') {
                      $devOptions['purchase_code'] = "";
					  echo '<div class="error"><p><strong><p>';
					  _e('Your advanced iframe pro purchase code was removed because of license issues. Please use your individual code from code canyon.', 'advanced-iframe');  
					  echo '</p></strong></p></div>';      
                      update_option($this->adminOptionsName, $devOptions);
                  }
				  if (!is_numeric(substr($version_info, 0, 1))) {
					$version_info = -1;
				  }
			   }			
			}	
			// we check every 12 hours
			set_transient('aip_version', $version_info, 60 * 60 * 12);
			return $version_info;
		}

        function hasValidRole () {
              $options = get_option($this->adminOptionsName);
              $config_role = $options['roles'];
              if ($config_role === 'none' || $config_role === '') {
                  return true;
              }

              global $wp_roles;
              if ( ! isset( $wp_roles ) ) {
                  return true;
              }
              $roles_by_index = array_flip(array_keys($wp_roles->get_names()));

              // get the user role
              $current_user = wp_get_current_user();
              $user_roles = $current_user->roles;
              $user_role = array_shift( $user_roles );

              $user_index = $roles_by_index[$user_role];
              $config_index = $roles_by_index[$config_role];
              return $user_index <= $config_index;
        }

    function ai_show_id_only() {
		global $post;
        $options = $this->getAiAdminOptions();

		if ($options['enable_content_filter'] === 'true' && isset( $_GET['ai-show-id-only'] ) && isset( $_GET['ai-server-side'] ) && is_singular() ) {
			the_post();

			echo '<html><head><meta name="robots" value="noindex,nofollow" />';
				wp_print_scripts();
				wp_head();
				wp_print_styles();
				if ( function_exists( 'post_class' ) ) {
					echo '</head><body ';
					post_class();
					echo '>';
				} else {
					echo '</head><body>';
				}

			if ( isset( $_GET['plain'] ) ) {
				$html = get_the_content();
			} else {
				$html = $this->my_the_content();
			}

      $html = '<div>' . $html . '</div>';


      $dom = new DOMDocument();
      $dom->loadHTML($html);
      $xpath = new DOMXPath($dom);
      $xpath_resultset = $xpath->query('//div[@id="'.$_GET['ai-show-id-only'].'"]');

      if($xpath_resultset->length > 0) {
        echo $dom->saveHTML($xpath_resultset->item(0));
      } else {
       echo "No content found for this id. Please check if your id is correct and the part you want to show is in the content area.";
      }

      wp_footer();

      $overflow = isset( $_GET['ai-show-overflow'] ) ? '': ' overflow: hidden;';

			echo '<style>';
      echo 'html, body { margin: 0px !important; padding: 0px !important;'.$overflow.' }';
      echo '</style>';
      echo '</body></html>';
			die();
		}
	}


   /**
     * Filters the post content.
     *
     * @since 0.71
     *
     * @param string $content Content of the current post.
     */
  function my_the_content( $more_link_text = null, $strip_teaser = false) {
    $content = get_the_content( $more_link_text, $strip_teaser );
    $content = apply_filters( 'the_content', $content );
    $content = str_replace( ']]>', ']]&gt;', $content );
    return $content;
  }
  
    // The function that handles the AJAX request
	function aip_map_url_callback() {
	  check_ajax_referer( 'aip-parameter-nonce', 'security' );
	  $url = $_POST['url'];
	   
	  // we use a default wp table as the data is normally quite small.
	  $paramData = get_option("advancediFrameParameterData");
		if (!empty($paramData)) {
			$nextid = 1;
			foreach ($paramData as $key => $entry) {
			   if ($url === $entry->url) {
				   echo $entry->base; 
				   die();
			   } else {
				   $nextid ++;
			   }				   
			}
			// not found
			$entry = $this->createEntry($nextid, $url);
			$paramData[$nextid] = $entry;
			$result = $entry->base; 
		} else {
			  $paramData = array();
			  $entry = $this->createEntry(1, $url);
			  $paramData[1] = $entry;
			  $result = $entry->base;    
		}
      update_option("advancediFrameParameterData", $paramData);

	  echo $result;
	  die(); // this is required to return a proper result
	}
	
    function aip_close_message_permanent() {
		 check_ajax_referer( 'aip-close-message-nonce', 'security' );
	     $id = $_POST['id'];
		 $options = get_option($this->adminOptionsName);
		 if (!isset($options['closed_messages'])) {
	         $closed_messages = array();	 
		 } else {
		     $closed_messages = $options['closed_messages']; 
		 }
		 if ($id !== 'show-registration-message') {
		     $closed_messages[$id] = 'true';
			 $options['closed_messages'] =  $closed_messages;
		     update_option($this->adminOptionsName, $options);
		 }
		
		 echo "OK";
		 die(); // this is required to return a proper result
	}
	
	function createEntry ($id, $url) {
		$object = new stdClass();
        $object->id = $id;
		
		$options = get_option($this->adminOptionsName);
		$add_iframe_url_as_param_prefix = $options['add_iframe_url_as_param_prefix'];
		$hashIdArray = explode(":", $add_iframe_url_as_param_prefix);
		if (count($hashIdArray) === 2) {
		    $param = $hashIdArray[1];
		    if (AdvancedIframeHelper::ai_startsWith($param,"fullpath")) {
				$decodedUrl = urldecode($url);
			    $fullPath = trim(parse_url(decodedUrl,PHP_URL_PATH),"/");
				$fullQuery = parse_url(decodedUrl,PHP_URL_QUERY);
                $numToRemove = abs((int) filter_var($param, FILTER_SANITIZE_NUMBER_INT));		
				$pathArray = explode('/', $fullPath);
				$output = array_slice($pathArray, $numToRemove);		
			    $value = implode('/', $output);
				if (!empty($fullQuery)) {
					 $value = $value + "?" + $fullQuery;
				}
			} else {
			   $value = AdvancedIframeHelper::aiExtractParam(@urldecode($url), $param);
			}
			$object->base = isset($value) ? $value : "";
		} else {
			$object->base = base_convert ($id, 10, 32);
		}	
        $object->url = $url;  		
	    return $object;
	}
    
	/**
	* Removes the page parameter from the $query object if it is not numeric
	* WP 5.5 does now a 301 redirect if the page param is not valid as it is used 
	* internally for pagination. 
	*/
    function aiParseRequest( $query ) { 
        $options = $this->getAiAdminOptions();
	    $remove_page_param_from_query = $options['remove_page_param_from_query'];
		if ($remove_page_param_from_query === 'true') {
			if (isset($_GET['page']) && isset($query->query_vars['page'])) {			
				if (!is_numeric($query->query_vars['page'])) {
					unset( $query->query_vars['page']);	
				}
			}
		}
        return $query;
    }
	
	function ai_save_post( $content ) {
	  $options = get_option($this->adminOptionsName);
	  $check_save = $options['check_iframes_when_save'];
	  
	
	  $tags = array('advanced_iframe', 'advanced-iframe', 'iframe');
	  $pattern = get_shortcode_regex($tags);
	  if (preg_match_all('/' . $pattern . '/s', $content, $matches)) {
		  $oldContent = $content;  
		  foreach ($matches[0] as $hit) {
			  $attsArray = shortcode_parse_atts($hit);
			  // check if the user has the capability unfiltered_html and is therefore allowed to use the custom and onload shortcode attribute. 
			  if (!current_user_can( 'unfiltered_html' )) {
				   $content = $this->filterAttribute('onload', $attsArray, $content);
				   $content = $this->filterAttribute('custom', $attsArray, $content);
				   $content = $this->filterAttribute('include_html', $attsArray, $content);	
				   $content = $this->filterAttribute('additional_js', $attsArray, $content);	
				   $content = $this->filterAttribute('additional_js_file_iframe', $attsArray, $content);	
                   $content = $this->filterXSSAttributes($attsArray, $content);				   
			  }		  
		  }
		  if ($oldContent != $content) {					  
			  $error_unfiltered = __('You are not allowed add unfiltered HTML. If you like to use the attributes "onload", "include_html" and "custom" in advanced iframe you need the unfiltered_html permission. By default, the unfiltered_html permission is only given to Super Admins, Administrators and Editors. On WordPress multisite networks, only Super Admins have the unfiltered_html permission.<br>All attributes which are used in the shortcode where filtered to avoid XSS attacks. Please check your filtered shortcode.', 'advanced-iframe');  
			  set_transient("ai_save_post_unfiltered_html", $error_unfiltered);
		  }
	  }  

	  if ($check_save === 'false') {
		  return $content;
	  }

	  // disable the feature because if it fails is should not be executed again.
	  $options['check_iframes_when_save'] = 'false';
	  update_option($this->adminOptionsName, $options);
	  $error_execution = __('The integrated check of iframes on save failed. "Check iframes on save" is now disabled. You can enable this again on the "Options" tab. Check the description of this options what maybe caused this problem.', 'advanced-iframe');  
	  set_transient("ai_save_post_execution", $error_execution, 0);
	  
	  include_once dirname(__FILE__) . '/includes/advanced-iframe-admin-functions.php';

	  $result_array = array();
	  $result_array['links'] = array();
	  $result_array['overall_status'] = 'green';
	  $all_links = array();
	  $result_array = evaluatePageLinks($result_array, stripcslashes ($content), 'link', 'title', false, $all_links, 'none');
	  $error = '';
	   
	  foreach( $result_array['links'] as $iframes) {
		   foreach( $iframes['links'] as $link => $result) {
			  $resultArray = ai_checkUrlStatus(array($link));
              $resultStatus = $resultArray[$link]['status'];
			  $result['status'] = $resultStatus;
			  if (isset($result['status'])) {
			       if (($result['status'] === 'orange' && $check_save != 'error') || $result['status'] === 'red' ) {
				       $error .= '<p>' . __('Please check this iframe url: ', 'advanced-iframe') . $result['url_orig'] . '</p>';
			       }
			   }
		   }
	   }

	   if (!empty($error)) {
		   $error .= '<p>'. __('You can get more details about the problem on the basic tab where you can check all links. This check is not 100%. If the url is working, simply ignore it or disable the check on the options tab: "Check iframes on save".', 'advanced-iframe') . '</p>';
		   set_transient("ai_save_post_errors", $error, 20);
	   }
	   
	   // Enabled the feature for the next save because it was working and the error was removed.
	   $options['check_iframes_when_save'] = $check_save;
	   update_option($this->adminOptionsName, $options);
	   delete_transient("ai_save_post_execution");

	   return $content;
	}
	
	function filterAttribute($attribute, $attsArray, $content) {
		foreach($attsArray as $key => $element) {
			if (AdvancedIframeHelper::ai_startsWith($element, $attribute)) {
				$element_replace = addslashes(rtrim($element,']'));
				$content = str_replace(' ' . $element_replace, '', $content);
			}
			if ($key === $attribute) {
				$element_replace = $key . "=" . addslashes(rtrim($element,']'));
				$content = str_replace(' ' . $element_replace, '', $content);
				$content = str_replace(' ' . $key . '=', '', $content);
			}
		}
	    return $content;
	}
	
	/**
	* Detects possible XSS attacks in the shortcode attributes.
	*/
	function filterXSSAttributes($attsArray, $content) {
		$replaceArray = array("'",' ', '(',')',"\\x29", "\\x28");
		foreach($attsArray as $element) {	
			$checkedElement = str_replace($replaceArray, '', $element);
			if ($checkedElement != $element) {
				$content = str_replace(addslashes($element), addslashes($checkedElement), $content);
			}
		}
	    return $content;
	}
	
        /**
         * Intercepts the Ajax resize events in iframes.
         */
        function interceptAjaxResize($iframe_id, $resize_width, $timeout, $resize_on_ajax_jquery,
                                     $click_timeout, $resize_on_click_elements, $resize_min_height)
        {
            $debug = false;
            $val = '';
            if ($timeout != '' || $click_timeout != '') {
				$val .= 'function local_resize_' . $iframe_id . '(timeout) {
					if (timeout != 0) {
					   setTimeout(function() { aiResizeIframe(ifrm_' . $iframe_id . ', "' . $resize_width . '","' . $resize_min_height . '")},timeout);
					} else {
					   aiResizeIframe(ifrm_' . $iframe_id . ', "' . $resize_width . '","' . $resize_min_height . '");
					}
				}';

				if ($resize_on_ajax_jquery === 'true' || $click_timeout != '') {
					$val .= 'function ai_jquery_ajax_resize_' . $iframe_id . '() {
					jQuery("#' . $iframe_id . '").bind("load",function(){
					doc = this.contentWindow.document;';
					if ($timeout != '' && $resize_on_ajax_jquery === 'true') {
						$val .= 'var instance = this.contentWindow.jQuery;';
						$val .= 'instance(doc).ajaxComplete(function(){';
						if ($debug) {
							$val .= 'alert("AJAX request completed.");';
						}
						$val .= 'local_resize_' . $iframe_id . '(' . $timeout . ');';
						$val .= '});';
					}
					if ($click_timeout != '' && $resize_on_click_elements != '') {
						$val .= 'doc.addEventListener("click", function(evt) { ';
						$val .= ' if (aiCheckIfValidTarget(evt,"' . $resize_on_click_elements . '")) {';
						if ($debug) {
							$val .= 'alert("Click event intercepted.");';
						}
						$val .= ' local_resize_' . $iframe_id . '(' . $click_timeout . ');';
						$val .= ' }';
						$val .= '}, true);';
					}
					$val .= '});
				}';
					$val .= 'ai_jquery_ajax_resize_' . $iframe_id . '();';

				}
				if ($resize_on_ajax_jquery === 'false' && $timeout != '') {
					$val .= 'var send_' . $iframe_id . ' = ifrm_' . $iframe_id . '.contentWindow.XMLHttpRequest.prototype.send,
				  onReadyStateChange_' . $iframe_id . ';

				  function sendReplacement_' . $iframe_id . '(data) {
					  if(this.onreadystatechange) {
						  this._onreadystatechange_' . $iframe_id . ' = this.onreadystatechange;
					  }
					  this.onreadystatechange = onReadyStateChangeReplacement_' . $iframe_id . ';
					  return send_' . $iframe_id . '.apply(this, arguments);
				  }

				  function onReadyStateChangeReplacement_' . $iframe_id . '() {
					  if(this.readyState === 4 ) {
						  var retValue;
						  if (this._onreadystatechange_' . $iframe_id . ') {
							  retValue = this._onreadystatechange_' . $iframe_id . '.apply(this, arguments);
						  }';
						$val .= 'local_resize_' . $iframe_id . '(' . $timeout . ');';
						$val .= 'return retValue;
					  }
				  }';
				$val .= ' ifrm_' . $iframe_id . '.contentWindow.XMLHttpRequest.prototype.send = sendReplacement_' . $iframe_id . ';';
				}
            }
            return '<script type="text/javascript">' . $val . '</script>'; 
        }
    }
}

if (!isset($aip_standalone)) {
    // setup new instance of plugin if not standalone
    if (class_exists("advancediFrame")) {
        $cons_advancediFrame = new advancediFrame();
    }
}
//Actions and Filters
if (isset($cons_advancediFrame)) {
    //Initialize the admin panel
    if (!function_exists('advancediFrame_ap')) {
        function advancediFrame_ap()
        {
            global $cons_advancediFrame;
            if (!isset($cons_advancediFrame)) {
                return;
            }
            $aiOptions = $cons_advancediFrame->getAiAdminOptions();

            $pro = (file_exists(dirname(__FILE__) . "/includes/class-cw-envato-api.php")) ? " Pro" : "";

            $cap = ai_map_role_to_capability($aiOptions['roles']);
            add_menu_page('Advanced iFrame' . $pro, 'Advanced iFrame' . $pro, $cap, 'advanced-iframe', 
			    array($cons_advancediFrame, 'printAdminPage'), AIP_IMGURL.'/logo_24x24.png', '80.001');
            if (!empty($aiOptions['alternative_shortcode'])) {
                // setup shortcode alternative style
                add_shortcode($aiOptions['alternative_shortcode'], array($cons_advancediFrame, 'do_iframe_script'), 1);
            }

            add_action('media_buttons', array($cons_advancediFrame, 'addAiButton'), 11);
        }
    }
    add_action('admin_menu', 'advancediFrame_ap',11); //admin page
    add_action('init', array($cons_advancediFrame, 'initAi'), 1); // add languages
    add_action('admin_enqueue_scripts', array($cons_advancediFrame, 'addAdminHeaderCode'), 99); // load css
    add_action('wp_enqueue_scripts', array($cons_advancediFrame, 'addWpHeaderCode'), 98); // load js
    add_action('wp_footer', array($cons_advancediFrame, 'add_script_footer'), 2);
    add_action('admin_notices', array($cons_advancediFrame, 'aiShowValidationErrors'), 3);
	
    add_action('ai_check_iframes_event', array($cons_advancediFrame, 'aiCheckIframes'));
    add_action('wp', array( $cons_advancediFrame, 'ai_show_id_only' ));
    add_action('send_headers', array( $cons_advancediFrame, 'aiAddHeader' ));
	add_action('plugins_loaded', array( $cons_advancediFrame, 'aiCheckRedirect' ),1);
    add_action('parse_request', array($cons_advancediFrame, 'aiParseRequest'), 1);
    add_action('template_redirect', array($cons_advancediFrame, 'aiTemplateRedirect'), 1);
	add_action('wp_head', array($cons_advancediFrame, 'aiWpHead'), 9999);
    add_action('switch_theme',  array($cons_advancediFrame, 'aiDelecteAiContentPages'), 1);
	

    add_shortcode('advanced_iframe', array($cons_advancediFrame, 'do_iframe_script'), 1); // setup shortcode
    add_shortcode('advanced-iframe', array($cons_advancediFrame, 'do_iframe_script'), 1); // setup shortcode alternative style
    add_shortcode('ai_advanced_js_local', array($cons_advancediFrame, 'addAiExternalLocal'), 1); // setup shortcode for adding ai_external only

    register_activation_hook(__FILE__, array($cons_advancediFrame, 'activate'));
    register_deactivation_hook(__FILE__, array($cons_advancediFrame, 'deactivate'));
    add_action( 'upgrader_process_complete', array($cons_advancediFrame, 'aiUpdate'), 99, 2);
		
    add_filter('content_edit_pre', array($cons_advancediFrame, 'aiCheckContent'), 1);
	add_filter('the_content', array($cons_advancediFrame, 'aiReplaceIframes'), 1);
	add_filter('ai_handle_temp_pages', array($cons_advancediFrame, 'aiHandleTempPages'), 1);
    add_filter('widget_text', 'shortcode_unautop');
    add_filter('widget_text', 'do_shortcode');
    add_filter('plugin_action_links', array($cons_advancediFrame, 'ai_plugin_action_links'), 10, 2);
	add_filter('preview_post_link',array($cons_advancediFrame, 'ai_preview_post_link'), 10, 2);
	
    // content_save_pre
    add_filter('content_save_pre', array($cons_advancediFrame, 'ai_save_post'), 11, 1 );
	
	if ( is_admin() ) {
      add_action( 'wp_ajax_aip_map_url_action', array($cons_advancediFrame,'aip_map_url_callback') );
      add_action( 'wp_ajax_nopriv_aip_map_url_action', array($cons_advancediFrame,'aip_map_url_callback') );
	  add_action( 'wp_ajax_aip_close_message_permanent', array($cons_advancediFrame,'aip_close_message_permanent') );
      add_action( 'wp_ajax_nopriv_aip_close_message_permanent', array($cons_advancediFrame,'aip_close_message_permanent') );
    }	
}

// ==============================================
//	Setup for widget + remove update functionality
// ==============================================
function ai_remove_update($value)
{
    if (isset($value) && is_object($value) && isset($value->response[plugin_basename(__FILE__)])) {
        unset($value->response[plugin_basename(__FILE__)]);
    }
    return $value;
}

function ai_remove_auto_update( $update, $item ) {
	$plugins = array ( 'advanced-iframe');
	if (isset($item->slug) && in_array( $item->slug, $plugins ) ) {
		return false; 
	}
	return $update;
}

function advanced_iframe_widget_init()
{
    register_widget('AdvancedIframe_Widget');
}

if (!isset($aip_standalone) && file_exists(dirname(__FILE__) . "/includes/advanced-iframe-widget.php")) {
    require_once('includes/advanced-iframe-widget.php');
    add_action('widgets_init', 'advanced_iframe_widget_init');
    if (!$isFreemiusMigration) {
    add_filter('site_transient_update_plugins', 'ai_remove_update');
 	add_filter('auto_update_plugin', 'ai_remove_auto_update', 10,2);
	}
}

// ==============================================
//	Get Plugin Version
// ==============================================
function advanced_iframe_plugin_version()
{
    if (!function_exists('get_plugins'))
        require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    $plugin_folder = get_plugins('/' . plugin_basename(dirname(__FILE__)));
    $plugin_file = basename((__FILE__));
    return $plugin_folder[$plugin_file]['Version'];
}

// ==============================================
//	Add Links in Plugins Table
// ==============================================
function advanced_iframe_plugin_meta_free($links, $file)
{
	global $isFreemiusMigration; 
    if (strpos($file, '/advanced-iframe') !== false) {
        $iconstyle = 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';
        $reviewlink = 'https://wordpress.org/support/view/plugin-reviews/advanced-iframe?rate=5#postform';
        if ($isFreemiusMigration) {
		     $links = array_merge($links, array('<a href="' . $reviewlink . '"><span class="dashicons dashicons-star-filled"' . 
			    $iconstyle . 'title="Give a 5 Star Review"></span></a>'));
		} else {
        $links = array_merge($links, array('<a target="_blank" href="https://1.envato.market/rNeNd">Advanced iFrame Pro</a>',
            '<a href="' . $reviewlink . '"><span class="dashicons dashicons-star-filled"' . $iconstyle . 'title="Give a 5 Star Review"></span></a>'
        ));
		}
    }
    return $links;
}

function advanced_iframe_plugin_meta_pro($links, $file)
{
	global $isFreemiusMigration; 
    if (strpos($file, '/advanced-iframe') !== false) {
        $iconstyle = 'style="-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;"';
        $links = array();
		
		if ($isFreemiusMigration) {
			$aiLinks = array('Version ' . advanced_iframe_plugin_version(),
                'By <a href="https://www.tinywebgallery.com">Michael Dempfle</a>',
                '<a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo">Demos</a>'
            ); 
		} else {
			$aiLinks  = array('Version ' . advanced_iframe_plugin_version(),
                'By <a href="https://www.tinywebgallery.com">Michael Dempfle</a>',
                '<a target="_blank" href="https://1.envato.market/k2Q2x">Code canyon - Advanced iFrame Pro</a>',
                '<a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo">Demos</a>'
            );
		}
        $links = array_merge($links, $aiLinks);
    }
    return $links;
}

  function render_ai_gutenberg( $attributes ) {
      global $cons_advancediFrame;
	  global $isAiGutenberg;
      // remove the empty ones and extract the ones from the additional field.
      foreach($attributes as $key => $value) {         
        if(empty($value) || $key === 'className') { 
            unset($attributes[$key]); 
        }
      }  
      // the u0022 is replaced by " - because of a unknown reason the gutenberg block editor replaces " with u0022
	  $add_array = (isset($attributes['additional'])) ?  shortcode_parse_atts(str_replace("u0022",'"',$attributes['additional'])) : array();
	  
	  if (is_array($add_array)) {
	      $new_attributes = array_merge ($add_array, $attributes);
	  } else {
		  $new_attributes = $attributes;
	  }
	  
	  $new_attributes['add_surrounding_p'] = (isset($attributes['add_surrounding_p_boolean'])) ? 'true' : 'false';
	  
      unset($new_attributes['additional']); 
	  unset($new_attributes['add_surrounding_p_boolean']); 
      $html = '';
	  if (current_action() == "parse_request") {     
          $html = '<style>';
    	  $html .= '.ai-iframe-select { border: 1px dotted transparent;} .ai-iframe-select:hover { border: 1px dotted #eeeeee; }';
    	  $html .= '.ai-iframe-select-text { padding-left: 5px; font-size: 14px; color: transparent; cursor:text; } .ai-iframe-select-text:hover { color: #000; background-color: #f8f8f8; }';
    	  $html .= '.errordiv { font-size: 15px; }'; 
    	  $html .= '</style>';
    	  $html .= '<div class="ai-iframe-select">';
    	  $html .= '<div class="ai-iframe-select-text">';
          $html .= 'Click here to edit the advanced iframe block.'; 
          $html .= '</div>';	  
      }
	  $isAiGutenberg = true;
      
	  $new_attributes['enable_lazy_load'] = 'false';
	  $new_attributes['hide_content_until_iframe_color'] = '';	  
	   
	  $html .= $cons_advancediFrame->do_iframe_script($new_attributes, null);
	  if (current_action() == "parse_request") {   
            $html .= '</div>'; 	  
	  }
	  return $html;
  }

/**
 *
 *
 */
function ai_map_role_to_capability($role) {
         $role_map = array('administrator' => 'manage_options', 'editor' => 'delete_others_pages',
         'author' => 'delete_published_posts', 'contributor' => 'delete_posts','subscriber' => 'read');
         return (isset ($role_map[$role])) ? $role_map[$role] : 'manage_options';
}

if (!isset($aip_standalone)) {
    if (file_exists(dirname(__FILE__) . "/includes/advanced-iframe-widget.php")) {
        add_filter('plugin_row_meta', 'advanced_iframe_plugin_meta_pro', 10, 2);
    } else {
        add_filter('plugin_row_meta', 'advanced_iframe_plugin_meta_free', 10, 2);
    }
}

?>