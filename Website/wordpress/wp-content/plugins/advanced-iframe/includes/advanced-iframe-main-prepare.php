<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  Prepares Javascript and values for the iframe
 */
 
if (!class_exists('AdvancediFramePrepareJs')) {
    class AdvancediFramePrepareJs { 
	static function aiPrepareGlobalJsVariables($id, $include_scripts_in_content, $aiPath, $add_document_domain, $document_domain) { 
		$html_js = '';

		$html_js .= 'var ai_iframe_width_'.$id.' = 0;';
		$html_js .= 'var ai_iframe_height_'.$id.' = 0;';

		if ($add_document_domain === 'true' && !empty($document_domain)) {
		   $html_js .= 'document.domain="'.esc_html($document_domain).'";'; 
		}
		return $html_js;
	}

	static function aiPreparePostMessageJs($html_js, $id, $use_post_message, $src, $multi_domain_enabled) {
		if ($use_post_message != 'false') {

			$iframe_origin_full = $src;
			
			$protocol = (AdvancedIframeHelper::isSecure()) ? 'https:' : 'http:';
			if (AdvancedIframeHelper::ai_startsWith($src, '//')) {
			  $iframe_origin_full = $protocol . $iframe_origin_full; 
			} else if (!AdvancedIframeHelper::ai_startsWith($src, 'http')) {
			   $iframe_origin_full = $protocol . '//'.$_SERVER['HTTP_HOST'] . '/';
               $multi_domain_enabled = 'true';			   
			}
			$iframe_origin_parts = parse_url($iframe_origin_full);
			if (isset($iframe_origin_parts['host'])) {
				$iframe_origin = $iframe_origin_parts['scheme'] . '://' . $iframe_origin_parts['host'];
			} else {
				$multi_domain_enabled = 'true';
			}
			
			$html_js .= 'function aiReceiveMessage'.$id.'(event) {';
			
			if ($use_post_message === 'debug' || isset($_COOKIE['aiEnableDebugConsole'])) {
			  $html_js .= '    if (console && console.log) {';
			  $html_js .= '      try {';
			  $html_js .= '        var jsObject = JSON.parse(event.data);';
			  // the debug messages itself are not printed as the content is.
			  $html_js .= '        if  (jsObject.aitype !== "debug") {';
			  $html_js .= '          console.log("Advanced iframe: postMessage received: " + event.data + " - origin: " + event.origin);';
			  // in case of an error we ignore this here as it is handled in the aiProcessMessage 
			  $html_js .= '        }'; 
			  $html_js .= '      } catch(e) {}';
			  $html_js .= '    }';
			}
			
			if ($multi_domain_enabled === 'false') {
			  $html_js .= "if (event.origin !== '". $iframe_origin ."') {return;}"; 
			}
			
			// this is a special file that can be included to convert postMessage events 
			// from non ai pages. 
			$filenamedir  = dirname(__FILE__) . '/../../advanced-iframe-custom';
			$post_js_filename = $filenamedir . '/ai_post_message_converter_'.$id.'.js';
			$post_js_filename_old = $filenamedir . '/ai_post_message_converter.js';
			if (file_exists($post_js_filename)) {
			  $html_js .=  trim(file_get_contents($post_js_filename));
			  $html_js .= 'event = aiConvertPostMessage(event);';
			} else  if (file_exists($post_js_filename_old)) {
			  $html_js .=  trim(file_get_contents($post_js_filename_old));
			  $html_js .= 'event = aiConvertPostMessage(event);';
			}
			$html_js .= '  aiProcessMessage(event,"'.$id.'", "'.$use_post_message.'");';
			$html_js .= '}';
			$html_js .= 'if (window.addEventListener) {';
			$html_js .= '  window.addEventListener("message", aiReceiveMessage'.$id.');'; 
			$html_js .= '} else if (el.attachEvent)  {';
			$html_js .= '  el.attachEvent("message", aiReceiveMessage'.$id.');';
			$html_js .= '}';      
		}
		return 	$html_js;
	}

	static function aiPrepareAiJsVariables($html_js, $iframe_zoom, $show_part_of_iframe_zoom, $enable_ie_8_support, 
		$store_height_in_cookie, $id, $onload_scroll_top, $additional_height, $debug_js, $fullscreen_button_full) {
		if (version_compare(PHP_VERSION, '5.3.0') >= 0 && (!empty($iframe_zoom) || !empty($show_part_of_iframe_zoom) )) { 
		  $html_js .= ($enable_ie_8_support) ? 'var aiIsIe8=true;' : 'var aiIsIe8=false;';
		} else {
		  $html_js .= 'var aiIsIe8=false;';
		}
		if ($store_height_in_cookie === 'true') {
			$html_js .=  'var aiEnableCookie=true; aiId="' . $id . '";';
		}
		if ($fullscreen_button_full === 'true') {
			$html_js .=  'var aiRealFullscreen=true;';
		}
		if ($onload_scroll_top !== 'false') {
			$html_js .=  'var aiOnloadScrollTop="' . $onload_scroll_top . '";';   
		} else {
			$html_js .=  'var aiOnloadScrollTop="true";';  
		}

		if ($additional_height != 0) {
			$html_js .=  'var aiExtraSpace=' . esc_html($additional_height) . ';';
		}

		if ($debug_js === 'bottom') {
			$html_js .= 'var aiShowDebug=true;';
		} else {
			$html_js .= 'var aiShowDebug=false;';
		}

		if (!empty($iframe_zoom)) {
			$html_js .= ' var zoom_' . $id.' = ' .esc_html($iframe_zoom). ';'; 
		}

		$html_js .= '
		if (typeof aiReadyCallbacks === \'undefined\') {
			var aiReadyCallbacks = [];  
		} else if (!(aiReadyCallbacks instanceof Array)) {
			var aiReadyCallbacks = [];
		}';
		return $html_js;
	}	

	static function aiPrepareAiShowIframeIdJs($html_js, $hide_part_of_iframe) { 
		$html_js .= '    function aiShowIframeId(id_iframe) { jQuery("#"+id_iframe).css("visibility", "visible");';
		if (!empty($hide_part_of_iframe)) {
			$html_js .= '        jQuery("#wrapper-div-"+id_iframe).css("visibility", "visible");';
		}
		$html_js .= '    }';
		return $html_js; 
	}
 
	static function aiPrepareAiResizeJs($html_js, $id, $auto_zoom, $enable_responsive_iframe, $iframe_zoom, $show_part_of_iframe, $hide_page_until_loaded) {
	  $html_js .= '    function aiResizeIframeHeight(height) { aiResizeIframeHeight(height,'.$id.'); }'; 
	  // the external height is rendered always for easier configuration
	  $html_js .= '    function aiResizeIframeHeightId(height,width,id) {'; 
	  if ($auto_zoom === 'remote') { 
		  $html_js .= '   aiAutoZoomExternal(id, width,"' . $enable_responsive_iframe . '");';
		  $html_js .= '   ai_iframe_width_'.$id.' = width;';
		  $html_js .= '   ai_iframe_height_'.$id.' = height;';
	  }
	  if (!empty($iframe_zoom)) { 
		$html_js .= ' var zoom_height = parseInt(height * parseFloat(window["zoom_" + id]),10)+1;';
		$html_js .= ' jQuery(\'#ai-zoom-div-\' + id).css("height",zoom_height);';
	  }            
	  if ($show_part_of_iframe === 'true') {
		$html_js .= ' aiResetShowPartOfAnIframe(id);';
	  }
	  $html_js .= 'aiResizeIframeHeightById(id,height);';
	  $html_js .= '}';
	  // end aiResizeIframeHeightId
	  
	  // this does hide the window after an initial page load when the iframe url changes.
	  // the onbeforeunload event is added on onload!
	  if ($hide_page_until_loaded  === 'true') {
		 $html_js .= 'var hide_iframe_loading_'.$id.' = function() {
			jQuery("#'.$id.'").css("visibility","hidden");
		 };';
		 $html_js .= 'function ai_hide_iframe_loading_'.$id.'(element) {
			try {
			  element.contentWindow.onunload = hide_iframe_loading_'.$id.'; 
			} catch (e) {}
		 }';
	  }
	  return $html_js;
	}

    static function aiPrepareAiSrcUrl(&$src, $map_parameter_to_url, $add_iframe_url_as_param_prefix, $url_forward_parameter, $pass_id_by_url, $id, $src_orig, $error_css) {
		// keep ? and & encoded!
		$src = str_replace('&#38;', '__AMPERSAND__' ,$src);
		$src = str_replace('&#34;', '__QUOTATION_MARK__' ,$src);
					  
		// if a hash exists we remove to to add parameters first
		$src_array = explode('#', $src);
		$src = $src_array[0];
		$iframeParam = '';

		if (!empty($map_parameter_to_url)) {
			$parameters = explode(",", $map_parameter_to_url); 
			foreach ($parameters as $parameter) {
				// check for mapping parameter|value|url
				$parameter_url_mapping = explode("|", $parameter);
				 if (count($parameter_url_mapping) === 3) {
					$read_param_url = AdvancedIframeHelper::param($parameter_url_mapping[0]);
					if ($read_param_url === $parameter_url_mapping[1]) {
						$src = $parameter_url_mapping[2]; 
					}  
				 } else if (count($parameter_url_mapping) === 1) {
					$prefix = trim(urldecode($add_iframe_url_as_param_prefix),'/');   
					$iframeParam = $parameter_url_mapping[0];
					$src_url = AdvancedIframeHelper::param($iframeParam);
					
					if (empty($src_url) && AdvancedIframeHelper::ai_startsWith($prefix,'hashrewrite')) {
						$iframeParam = explode(":", $iframeParam); 
						$iframeParam = $iframeParam[0]; 
						$fullurl = trim($_SERVER['REQUEST_URI'], '/');
						if (AdvancedIframeHelper::aiContains ($fullurl, '/' . $iframeParam . '/' )) { 
							// Get everything after the iframeParam as key! 
							$src_url = end(explode('/' . $iframeParam . '/', AdvancedIframeHelper::aiRemoveQueryString($fullurl)));	
						}
					}

					if (!empty($src_url)) { 
						$src = urldecode($src_url);   
						$src = trim($src, '/');
								 
						if (AdvancedIframeHelper::ai_startsWith($prefix,'hash')) {
							$paramData = get_option("advancediFrameParameterData");
							if (!empty($paramData)) {
								foreach ($paramData as $key => $entry) {
									if ($src_url === $entry->base) {
									   $src = urldecode($entry->url);
									   break;
									}
								}
							}
						} else if (!AdvancedIframeHelper::ai_startsWith($src,"http")) {              
						   if (!empty($prefix)) {
							   $prefix .= '/';
						   }
						   if (AdvancedIframeHelper::ai_startsWith($src,"s|")) { 
							 $src = "https://" . $prefix . substr($src,2);
						   } else if (AdvancedIframeHelper::ai_startsWith($src_orig,"https")) {
							 $src = "https://" . $prefix . $src;
						   } else if (AdvancedIframeHelper::ai_startsWith($src_orig,"//")) {
							 $src = "//" . $prefix . $src;
						   } else {
							 $src = "https://" . $prefix . $src;
						   } 
						}				
					}
				 } else {
					return $error_css . '<div class="errordiv">' . __('ERROR: map_parameter_to_url does not have the required 1 or 3 parameters', 'advanced-iframe') . '</div>';
				 }
			}        
		}

		// add parameters
		if ($url_forward_parameter != '') {
			$sep = (strpos($src, '?') === false)? '?': "&amp;";
			if ($url_forward_parameter === 'ALL') {
				$parameters = array();
				foreach ($_GET as $key => $value) {
					
					if ($key !== $iframeParam) {		
					$parameters[$key] = $key;
					}
				}
				foreach ($_POST as $key => $value) {
					$parameters[$key] = $key;
				}  
			} else {
				$url_forward_parameter = AdvancedIframeHelper::replace_brackets($url_forward_parameter);
				$parameters = explode(",", $url_forward_parameter);
			}
			foreach ($parameters as $parameter) {
				// check for mapping urlname|iframe name
				$parameter_mapping = explode("|", $parameter);
				if (count($parameter_mapping) === 1) {
					$parameter_mapping[1] = $parameter_mapping[0];
				}
				$read_param_url = AdvancedIframeHelper::param($parameter_mapping[0]);
				$alreadyAttached = AdvancedIframeHelper::aiContainsParam($src, $parameter_mapping[1]);
				if ($read_param_url != '' && !$alreadyAttached) {
					$src .= $sep . $parameter_mapping[1] . "=" . ($read_param_url);
					$sep = "&amp;";
				}
			}
		}

		if (!empty($pass_id_by_url)) {
			$sep = (strpos($src, '?') === false)? '?': "&amp;";
			$src .= $sep . $pass_id_by_url . "=" . $id;
		}  

		// add the hash again.
		if (count($src_array) > 1) {
			array_shift($src_array);
			$src .= '#' . implode('#', $src_array);
		}

		$src = str_replace('__AMPERSAND__', '&#38;' ,$src);
		$src = str_replace('__QUOTATION_MARK__' , '&#34;' ,$src);
		  
        return '';		
	}
	
	static function aiPrepareSafariFixJs($src, $safari_fix_url) {	
		// This is the safari cookie fix from https://vitr.github.io/safari-cookie-in-iframe/ 
		if (!empty($safari_fix_url)) {
			$html_safari = '';
			$safari_fix_url_message='';
			$all_browsers = (strpos($safari_fix_url, 'all') !== false) ? true : false;
			$sep = (strpos($src, '?') === false)? '?': "&";
			$type = (strpos($safari_fix_url, 'message') !== false) ? 'message':'true'; 
			$is_iframe = 'false';
			if (strpos($safari_fix_url, 'src') !== false) {   
				$safari_fix_url = $src . $sep . 'safari_cookie_fix=true';
				 // if aifixed is set and  aichecked not set yet then we use the safari_cookie_fix=message 
				if ($type === 'message') {
					if (isset($_COOKIE['aifixed']) && !isset($_COOKIE['aichecked'])) {
						$src = $src . $sep . 'safari_cookie_fix=message';
					}
				}
			} else {
				// external settings
				 $is_iframe = 'true';
				 // remove all: message:   
				 $safari_fix_url = str_replace('all:', "", $safari_fix_url);
				 $safari_fix_url = str_replace('message:', "", $safari_fix_url);
				 $safari_fix_url_message = $safari_fix_url . '/_safari_fix_message.html';
				 $safari_fix_url .= '/_safari_fix.html';      
			}
			
			// optimize this by checking the browser and cookies already on the server side to avoid rendering only the needed js

			$html_safari .= '<script type="text/javascript">';
			$html_safari .= 'var safari_cookie_fix_type="' . $type . '";'; 
			$html_safari .= 'var safari_cookie_fix_iframe=' . $is_iframe . ';'; 
			if ($all_browsers) {
			  $html_safari .= 'var is_safari = true;';
			} else {
			  $html_safari .= 'var is_safari = navigator.userAgent.toLowerCase().indexOf("safari") > -1;
				var is_chrome = navigator.userAgent.toLowerCase().indexOf("chrome") > -1;
				if ((is_chrome) && (is_safari)) {is_safari = false;} 
				';     
			}
			$html_safari .= 'if (is_safari) {
				if (!document.cookie.match(/^(.*;)?\s*aifixed\s*=\s*[^;]+(.*)?$/)) {
					document.cookie = "aifixed=fixed; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/; Secure; SameSite=None; Partitioned;";
					window.location.replace("'.$safari_fix_url.'");
				} else if (safari_cookie_fix_type === "message") {
					// we call the message check url once
					if (!document.cookie.match(/^(.*;)?\s*aichecked\s*=\s*[^;]+(.*)?$/)) {
						document.cookie = "aichecked=checked; expires=Tue, 19 Jan 2038 03:14:07 UTC; path=/; Secure; SameSite=None; Partitioned;";
						if (safari_cookie_fix_iframe) {
						   // create a iframe in external mode
						   document.write(\'<iframe src="' . $safari_fix_url_message. '" width="0" height="0" border="0" style="visibility:hidden"></iframe>\'); 
						}
					}
				}           
			}  
			</script>';
			echo $html_safari; 		
		} 
		return $src;
	}
	
	static function aiPreparePdfJs($src) {
		if (AdvancedIframeHelper::ai_endsWith($src, '.pdf')) {
			if (AdvancedIframeHelper::ai_startsWith($src, 'NATIVE:')) {
			   $src = substr($src, 7);
			} else {
			   $src = '//docs.google.com/gview?url=' . $src . '&embedded=true';
			}     
		}
		return $src;
	}
	
	static function aiPrepareAddCssClassIframe($add_css_class_iframe, $id) {
		 if ($add_css_class_iframe === 'true') {
		   // get the url from the iframe - create a hash and add this as class to the body. 
		   // this enables us to distinguish between sites with the same structure but where 
		   // different thing e.g. should be hidden.
		   $hideiframehtml = "var iframeHref".$id." = jQuery('#".$id."').contents().get(0).location.href; 
		   if (iframeHref".$id.".substr(-1) === '/') {
			   iframeHref".$id." = iframeHref".$id.".substr(0, iframeHref".$id.".length - 1);
		   }
		   var lastIndex".$id." = iframeHref".$id.".lastIndexOf('/');
		   var result".$id." = iframeHref".$id.".substring(lastIndex".$id." + 1);
		   var newClass".$id." = result".$id.".replace(/[^A-Za-z0-9]/g, '-');
		   var iframeBody".$id." = jQuery('#".$id."').contents().find('body');
		   iframeBody".$id.".addClass('ai-' + newClass".$id.");
		   iframeBody".$id.".children().each(function (i) {
				 jQuery(this).addClass('ai-' + newClass".$id." + '-child-' + (i+1)); 
			});
		   "; 
		} else {
		    $hideiframehtml = '';
		}
		return $hideiframehtml;	
	}
	
	static function aiPrepareChangeCss(&$hideiframehtml, $id, $iframe_content_id, $iframe_content_styles, $error_css) {
		if ($iframe_content_id !== '' || $iframe_content_styles !== '') {
			$elements = esc_html($iframe_content_id); // this field should not have a problem if they are encoded.
			$values = esc_html($iframe_content_styles); // this field style should not have a problem if they are encoded.
			$elementArray = explode("|", $elements);
			$valuesArray = explode("|", $values);
			if (empty($values) || empty($elements) || count($elementArray) != count($valuesArray)) {
				return $error_css . '<div class="errordiv">' . __('Configuration error: The attributes iframe_content_id and iframe_content_styles have to have the amount of value sets separated by |.', 'advanced-iframe') . '</div>';
			} else {
				for ($x = 0; $x < count($elementArray); ++$x) {
					$valuesArrayPairs = explode(";", trim($valuesArray[$x], " ;:"));
					for ($y = 0; $y < count($valuesArrayPairs); ++$y) {
						$elements = explode(":", $valuesArrayPairs[$y]);
						$hideiframehtml .= "jQuery('#".$id."').contents().find('" . trim($elementArray[$x])
						  . "').css('" . trim(strtolower($elements[0])) . "', '" . trim(strtolower($elements[1])) . "');";
					}
				}
			}
		}
	    return '';
	}
	
	static function aiPrepareChangeIframesLinks(&$hideiframehtml, $change_iframe_links, $change_iframe_links_target, $change_iframe_links_href, $id, $error_css) {
		if (!empty($change_iframe_links)) {
			$links = esc_html(AdvancedIframeHelper::replace_brackets($change_iframe_links)); // this field should not have a problem if they are encoded.
			$links = str_replace('&#039;',"'", $links);
			$targets = esc_html($change_iframe_links_target); // this field style should not have a problem if they are encoded.
			$hrefs = esc_html($change_iframe_links_href); // this field style should not have a problem if they are encoded.
			
			$linksArray = explode("|", $links);
			$targetArray = empty($targets) ? array() : explode("|", $targets);
			$hrefArray = empty($hrefs) ? array() : explode("|", $hrefs);
			if (count($linksArray) < count($targetArray) && count($linksArray) < count($hrefArray) ) {
				return $error_css . '<div class="errordiv">' . __('Configuration error: The attribute change_iframe_links has to have at least the same amount of values separated by | as change_iframe_links_target and change_iframe_links_href. Please read the documentation for details', 'advanced-iframe') . '</div>';
			} else {
				for ($x = 0; $x < count($targetArray); ++$x) {
					$hideiframehtml .= "jQuery('#".$id."').contents().find('body').on('mouseover keydown', \"".trim($linksArray[$x])."\", function(e) {
							jQuery(this).attr('target', '".trim($targetArray[$x])."');
							if ('".trim($targetArray[$x])."' === '_blank') {
								var originalRel = (this.rel === undefined) ? '' : this.rel.toLowerCase();
								var newRel = originalRel.split(' ');
								if (originalRel.indexOf('noopener') === -1){
									newRel.push('noopener');
								}
								jQuery(this).attr('rel', newRel.join(' ').trim() );
							} 
						 });";
				}          
				for ($y = 0; $y < count($hrefArray); ++$y) {
					$hrefArrayelements = explode("~", $hrefArray[$y]);
					if (count($hrefArrayelements) === 2) {
						$hideiframehtml .= "jQuery('#".$id."').contents().find('body').on('mouseover keydown',\"" . trim($linksArray[$y]) . "\", function(e){
								if ((typeof this.href !== 'undefined') &&  !this.href.includes('".$hrefArrayelements[1]."')) { ";
						if ($hrefArrayelements[0] === 'append') {
							$hideiframehtml .= "this.href = this.href.concat('".$hrefArrayelements[1]."'); ";
						} else {
							$hideiframehtml .= "this.href = this.href.replace('".$hrefArrayelements[0]."','".$hrefArrayelements[1]."'); ";
						}   
						$hideiframehtml .= "}
								});";
					} else {
						  return $error_css . '<div class="errordiv">' . __('Configuration error: The attribute change_iframe_links_href has not the required ~ seperator set. Please read the documentation for details', 'advanced-iframe') . '</div>';
					}
				}
			}
		} else if (!empty($change_iframe_links_target) || !empty($change_iframe_links_href)) {
		    	return $error_css . '<div class="errordiv">' . __('Configuration error: The attribute change_iframe_links has to have at least the same amount of value separated by | as  change_iframe_links_target and change_iframe_links_href. Please read the documentation for details', 'advanced-iframe') . '</div>';	
		}
	    return '';
	}
	
	
	/**
	* Configures the detection of url changes with the history api. 
	* aiChangeUrlParam is called each time the url was modified with javascript
	* which sends the url back to the parent.
	*/
	static function aiConfigureHistroyChange($method, $id) {  
	  $js = '<script>';
  	  $js .= 'var iframeHc'.$id.' = document.getElementById("'.$id.'");';
	  $js .= 'iframeHc'.$id.'.contentWindow.addEventListener("popstate", function(event) {
		        '.$method.'
			});		
			(function(){
			  var rs = iframeHc'.$id.'.contentWindow.history.replaceState; 
			  iframeHc'.$id.'.contentWindow.history.replaceState = function(){
				rs.apply(history, arguments); // preserve normal functionality
		        '.$method.'
			  };
			}());
			(function(){
			  var ps = iframeHc'.$id.'.contentWindow.history.pushState; 
			  iframeHc'.$id.'.contentWindow.history.pushState = function(){
				ps.apply(history, arguments); // preserve normal functionality
		       '.$method.'
			  };
			}());';

	  $js .= '</script>';
	  return ''; // $js;
	}
	
	static function aiPrepareModifyIframe($html_js, &$hideiframehtml, $id, $iframe_hide_elements, $iframe_content_css, $additional_css_file_iframe, $additional_js_file_iframe) {
		if (!empty($iframe_hide_elements)) {
			$hideiframehtml .= "jQuery('#".$id."').contents().find('" .
			esc_html($iframe_hide_elements) . "').css('display', 'none').css('width', '0').css('height','0');";
		}
		if (!empty($iframe_content_css)) {
			$hideiframehtml .= 'aiAddCss("#'.$id.'","'.urlencode(wp_kses($iframe_content_css, array())).'");';
		}
		if (!empty($additional_css_file_iframe)) {
			$hideiframehtml .= 'aiAddCssFile("#'.$id.'","'.$additional_css_file_iframe.'");';
		}
		if (!empty($additional_js_file_iframe)) {
			$hideiframehtml .= 'aiAddJsFile("#'.$id.'","'.$additional_js_file_iframe.'");';
		}
		if ($hideiframehtml != '') {
			$html_js .= 'function aiModifyIframe_' . $id . '() { ';
			$html_js .= 'try {';
			$html_js .=  $hideiframehtml;
			$html_js .=  '} catch(e) {';
			$html_js .=  '  if (console) {';
			$html_js .=  '    if (console.error) {';
			$html_js .=  '      console.error("Advanced iframe configuration error: You have enabled the modification of the iframe for pages on the same domain. But you use an iframe page on a different domain. You need to use the pro version of external workaround like described in the settings. Also check the next log. There the browser message for this error is displayed."); ';
			$html_js .=  '      console.log(e);';
			$html_js .=  '    }';
			$html_js .=  '  }';
			$html_js .=  '}';
			$html_js .= '}';
		}
		return $html_js;
	}	
  }
}

if ($include_scripts_in_content === 'true') {
	$html .= '<script type="text/javascript" src="' . plugins_url() . $aiPath . '/js/ai.min.js" ></script>';
} 
$html_js = AdvancediFramePrepareJs::aiPrepareGlobalJsVariables($id, $include_scripts_in_content, $aiPath, $add_document_domain, $document_domain); 
$html_js = AdvancediFramePrepareJs::aiPreparePostMessageJs($html_js, $id, $use_post_message, $src, $multi_domain_enabled);
$html_js = AdvancediFramePrepareJs::aiPrepareAiJsVariables($html_js, $iframe_zoom, $show_part_of_iframe_zoom, $enable_ie_8_support, 
               $store_height_in_cookie, $id, $onload_scroll_top, $additional_height, $debug_js, $fullscreen_button_full);
$html_js = AdvancediFramePrepareJs::aiPrepareAiShowIframeIdJs($html_js, $hide_part_of_iframe);
$html_js = AdvancediFramePrepareJs::aiPrepareAiResizeJs($html_js, $id, $auto_zoom, $enable_responsive_iframe, $iframe_zoom, $show_part_of_iframe, $hide_page_until_loaded);

$src_orig = $src;

$error_html = AdvancediFramePrepareJs::aiPrepareAiSrcUrl($src, $map_parameter_to_url, $add_iframe_url_as_param_prefix, $url_forward_parameter, $pass_id_by_url, $id, $src_orig, $error_css);
if ($error_html) {
	return $error_html;
}
 
// Evaluate shortcodes and replace placeholders for the src - they are not encoded! 
// This has to be done by the shortcode that is used
$src = AdvancedIframeHelper::ai_replace_placeholders($src , $enable_replace, $aip_standalone);

// if the src contains a # but does not end with it we disable scroll to top
if ($this->ai_endsWith($src, '#')) {
  $src = substr($src, 0, -1);	
} else if ($this-> aiContains($src, '#')) {
  $onload_scroll_top = "false";
}


$src = AdvancediFramePrepareJs::aiPrepareSafariFixJs($src, $safari_fix_url);
$src = AdvancediFramePrepareJs::aiPreparePdfJs($src);
 

   if ((!empty($content_id) && !empty($content_styles)) ||
       !empty($hide_elements) || !empty($change_parent_links_target)
       || $enable_lazy_load === 'true' || $add_css_class_parent === 'true'
       || $show_iframe_as_layer === 'external' || $show_part_of_iframe_zoom !== 'false' 
	   || !empty($remove_elements_from_height) || !empty($fullscreen_button_hide_elements)) {

    // hide elements is called directly in the page to hide elements as fast as quickly
    $hidehtml = '';
     // Add class to all parent elements for easier styling
    if ($add_css_class_parent === 'true') {
        $hidehtml .= " if (window.aiAddCssClassAllParents) { aiAddCssClassAllParents('#".$id."'); }";
    }
                        
    if (!empty($hide_elements)) {
		    $hidehtml .= "jQuery('" . esc_html($hide_elements) . "').css('display', 'none');";
    }
	
	if (!empty($fullscreen_button_hide_elements)) {
        $hidehtml .= "jQuery('" . esc_html($fullscreen_button_hide_elements) . "').addClass('ai-fullscreen-hide');";
    }
	
    if (!empty($content_id)) {
        $elements = esc_html($content_id); // this field should not have a problem if they are encoded.
        $values = esc_html($content_styles); // this field style should not have a problem if they are encoded.
        $elementArray = explode("|", $elements);
        $valuesArray = explode("|", $values);
		if (empty($values) || count($elementArray) != count($valuesArray)) {
			return $error_css . '<div class="errordiv">' . __('Configuration error: The attributes content_id and content_styles have to have the amount of value sets separated by |.', 'advanced-iframe') . '</div>';
        } else {
            for ($x = 0; $x < count($elementArray); ++$x) {
                $valuesArrayPairs = explode(";", trim($valuesArray[$x], " ;:"));
                for ($y = 0; $y < count($valuesArrayPairs); ++$y) {
                    $valueElements = explode(":", $valuesArrayPairs[$y]);
                    $sel = trim($elementArray[$x]);
                    $sel = str_replace('##', '>', $sel );               
				    $hidehtml .= "jQuery('" . $sel . "').css('" . trim(strtolower($valueElements[0])) . "', '" . trim(strtolower($valueElements[1])) . "');";		
			    }
            }
        }
    }

    $html_js .= 'function loadElem_'.$id.'(elem)
     {'; 
     if ($enable_lazy_load_fadetime != '0') {
     $html_js .= ' 
        elem.fadeOut(0, function() {
          elem.fadeIn('.$enable_lazy_load_fadetime.');
        });';
     }
     $html_js .= '}';

    $html_js .= 'function aiModifyParent_' . $id . '() { ';
    $html_js .=  $hidehtml;
    $html_js .= '}';
    
    $aiReady = '';
	
    //  Change parent links target
    if (!empty($change_parent_links_target) && $show_iframe_as_layer !== 'external') {
      $fulljQueryStringArray = array();
	  $autoElement = '';
	  $allElementsArray = explode(",", $change_parent_links_target);
	  foreach ($allElementsArray as $singleElement) {
		 
	      $singleElementArray = explode("|auto", $singleElement); 
          $fulljQueryStringArray[] = $singleElementArray[0];	
          if (isset($singleElementArray[1]))	{
		      $autoElement = $singleElementArray[0];
		  }		  
	  
	  }
	  $fulljQueryString = implode (',', $fulljQueryStringArray);
	  
		// scan the fulljQueryString for |bold and add the bold solution to them.
		$fulljQueryStringExplode = explode("|bold", $fulljQueryString); 
		$fulljQueryString = $fulljQueryStringExplode[0];	
		if (isset($fulljQueryStringExplode[1]))	{
			$cssClassArray = explode(":", $fulljQueryStringExplode[1]); 
			$cssBold = (isset($cssClassArray[1])) ? esc_html($cssClassArray[1]) : 'ai-bold';
			$aiReady .= 'jQuery(function(){
				var headerAnchors = jQuery("'.$fulljQueryString.'").click(function(){
					headerAnchors.removeClass("'.$cssBold.'");
					jQuery(this).addClass("'.$cssBold.'");
				});
			});';
		}

      $el = AdvancedIframeHelper::replace_brackets($fulljQueryString);
      $aiReady .= 'jQuery("'. trim($el) .'").attr("target", "'.$id.'");';
      
      if ($show_iframe_as_layer === 'true') {
		$show_iframe_as_layer_autoclick_delay = preg_replace('/[^0-9]/', '', $show_iframe_as_layer_autoclick_delay);
		$show_iframe_as_layer_autoclick_hide_time = preg_replace('/[^0-9]/', '', $show_iframe_as_layer_autoclick_hide_time);
			
		$aiLayerMethod = 'var reload=aiCheckReload(this, "' . $id . '"); aiShowLayerIframe(event,"' . $id . '","'.plugins_url() . $aiPath.'/img/","'.$show_iframe_loader_layer.'", '.$show_iframe_as_layer_keep_content.', reload);'; 
	    $aiReady .= 'jQuery("'. trim($el) .'").off( "click"); jQuery("'. trim($el) .'").on( "click", function(event) { '.$aiLayerMethod.' });';
        if (!empty($autoElement)) {  // auto click - click on the link!
            // get latest selector	
			$trimmedSelector = preg_replace('/[^A-Za-z0-9\-]/', '', $autoElement);	
			if (AdvancedIframeHelper::allowIframeOpen($trimmedSelector)) {
				  $aiReady .= 'aiTriggerAutoOpen("' .$id . '","' . $autoElement . '", '.$show_iframe_as_layer_autoclick_delay. ', '. $show_iframe_as_layer_autoclick_hide_time . ');';
			}			
		}
        // we check now the hash if we need to open something!!! 
        $aiReady .= 'aiCheckAutoOpenHash("' .$id . '", '.$show_iframe_as_layer_autoclick_delay. ', '. $show_iframe_as_layer_autoclick_hide_time . ');';		
      }      
    }
    if ($show_iframe_as_layer === 'external') {   
         $aiReady .=  'jQuery("a").each(function () {
          if (this.host !== location.host) {
            jQuery(this).attr("target", "'.$id.'");
            jQuery(this).on("click", function(event) { var reload=aiCheckReload(this, "' . $id . '"); aiShowLayerIframe(event,"' . $id . '","'.plugins_url() . $aiPath.'/img/","'.$show_iframe_loader_layer.'", '.$show_iframe_as_layer_keep_content.', reload); });
          }
      });';
    }

    $aiReady .= 'aiModifyParent_' . $id . '();';
    
    if ($enable_lazy_load === 'true') { 
       // the 50 ms timeout is used because tabs need a little bit to initialize and hide the content.
       $initLazyIframe = 'setTimeout(function() { jQuery("#ai-lazy-load-'.$id.'").lazyload({threshold: '.$enable_lazy_load_threshold.', load: loadElem_'.$id.'}); },50);';   
       if ($enable_lazy_load_manual != 'auto') {
           $initLazyIframe .= "jQuery.lazyload.setInterval(0);"; 
       }
       if ($enable_lazy_load_manual === 'true') {
           $html_js .= 'function aiLoadIframe_' . $id . '() { ';
           $html_js .=  $initLazyIframe;
           $html_js .= 'return false;};'; 
           
            if (!empty($enable_lazy_load_manual_element)) {
               $html_js .= ' function trigger_manual_' . $id . '() { '; 
               $html_js .= 'jQuery( "' . esc_html($enable_lazy_load_manual_element) . '" ).click(function() { ';
               $html_js .= 'window.setTimeout(function(){'; 
               $html_js .= '  aiLoadIframe_' . $id . '(); ';  
               $html_js .= '}, 10);';
               $html_js .= 'return false;';
               $html_js .= '});'; 
               $html_js .= '}';  
               $aiReady .= 'trigger_manual_' . $id . '();';
            }    
       } else {
           $aiReady .= $initLazyIframe; 
       } 
    }
    
    if ($show_part_of_iframe_zoom !== 'false' ) { 
       $auto_zoom_div = empty($hide_part_of_iframe) ? '#ai-div-': '#wrapper-div-';
       $aiReady .= 'aiAutoZoomViewport("'.$auto_zoom_div.$id.'","' . $show_part_of_iframe_zoom . '");';
    }
	if (!empty($remove_elements_from_height)) {
		$aiReady .= 'aiRemoveElementsFromHeight("' . $id . '","'.$height.'","'.$remove_elements_from_height.'");';
	}
   
    $html_js .= 'var aiReadyAiFunct_' . $id . ' = function aiReadyAi_' . $id . '() { ';
	
    if (!empty($onload_resize_delay)) {
        $html_js .= 'window.setTimeout(function(){'; 
	}
	$html_js .=  $aiReady;
	if (!empty($onload_resize_delay)) {
	    $html_js .= '},'.$onload_resize_delay.');';
	}
	
    $html_js .= '};';
    $html_js .= 'aiReadyCallbacks.push(aiReadyAiFunct_' . $id . ');';
    
    // Modify parent is called right away to do the modifications even when the dom is not ready yet.
    // It is called again on dom ready - errors happening here are simply catched.
    $html_js .= 'if (window.jQuery) { try { aiModifyParent_' . $id . '(); } catch (error) {} }';
}
   
// modify the iframe JS  
$hideiframehtml = AdvancediFramePrepareJs::aiPrepareAddCssClassIframe($add_css_class_iframe, $id);
$error_html = AdvancediFramePrepareJs::aiPrepareChangeCss($hideiframehtml, $id, $iframe_content_id, $iframe_content_styles, $error_css);
if ($error_html) {
	return $error_html;
}

$error_html = AdvancediFramePrepareJs::aiPrepareChangeIframesLinks($hideiframehtml, $change_iframe_links, $change_iframe_links_target, $change_iframe_links_href, $id, $error_css);
if ($error_html) {
	return $error_html;
}
  
$html_js = AdvancediFramePrepareJs::aiPrepareModifyIframe($html_js, $hideiframehtml, $id, $iframe_hide_elements, $iframe_content_css, $additional_css_file_iframe, $additional_js_file_iframe); 

$html .= '<script type="text/javascript">' . $html_js . '</script>';
return "";
?>