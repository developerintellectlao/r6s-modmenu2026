<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');

class AdvancedIframeHelper {
      static function replace_brackets($str_input) {
               $str_output = str_replace('{{', "[", $str_input);
               return str_replace('}}', "]", $str_output);
      }
	  
	  static function isSecure() {
	      return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		    || $_SERVER['SERVER_PORT'] == 443;
	  }
	  
      static function scale_value($value, $iframe_zoom) {
		if (strpos($value, '%') === false) {
			return (intval($value) * floatval($iframe_zoom)) . 'px';
		} else {
			$value = substr($value, 0, -1);
			return (intval($value) * floatval($iframe_zoom)) . '%';
		}
	  } 

        /**
         * Replace placeholders in the url and fill them with proper values.
         */
        static function ai_replace_placeholders($str_input, $enable_replace, $aip_standalone) {
          // wordpress does encode ' by default which does kill urls that contain this char
          $str_input = str_replace('&#8242;', '%27', $str_input);
          $str_input = str_replace('&#8217;', '%27', $str_input);

          if ($enable_replace) {
              $str_input = str_replace('{host}', $_SERVER['HTTP_HOST'], $str_input);
              $str_input = str_replace('{port}', $_SERVER['SERVER_PORT'], $str_input);
              
              // the random number can be used to avoid caching
              $str_input = str_replace('{timestamp}', time() , $str_input);
              $str_input = str_replace('{session_id}', session_id(), $str_input);
              
			  $str_input = AdvancedIframeHelper::replace_url_path_data($str_input);
              $str_input = AdvancedIframeHelper::replace_full_url_data($str_input);
              $str_input = AdvancedIframeHelper::replace_query_data($str_input);
			  $str_input = AdvancedIframeHelper::replace_locale_data($str_input);
			    
              if (!isset($aip_standalone)) {
                  $str_input = str_replace('{site}', site_url(), $str_input);
                  $str_input = AdvancedIframeHelper::replace_user_data($str_input);

                  $admin_email = get_option( 'admin_email' );
                  $str_input = str_replace('{adminemail}', rawurlencode($admin_email), $str_input);

                // evaluate shortcodes for the parameter 
                $str_input = str_replace('{{', "[", $str_input);
                $str_input = str_replace('}}', "]", $str_input);
				// if " cannot be replaced as alternative _|_ can be used. This combination is normally not in a shortcode
				$str_input = str_replace('_|_', '"', $str_input);
                $str_input = do_shortcode($str_input);
              }

		              
              // we replace all leftover placeholder 
             $regex = '/{(.*?)}/';
             $result = preg_match_all( $regex, $str_input, $match);  
             if ($result) {
               foreach ($match[1] as $key) {
                 $str_input = str_replace('{'.$key.'}', '' , $str_input); 
               } 
             }      
          }
	
	      $str_input = AdvancedIframeHelper::removeEmptyParameters($str_input); 
          return $str_input;
      }

      /**
       *  replaces one on the main user keys and also checks if a default 
       *  is set. The default is used when the result is empty.
       *  
       *  
       */
    static function replace_key_with_default($key, $value, $str_input, $current_user) {
        if (strpos($str_input,'{' . $key) !== false) {
            $regex = '/{('.$key.'.*?)}/';
            preg_match_all( $regex, $str_input, $match);
            foreach ($match[1] as $result_key) {
                // we check if we have a default value
                $userinfo_elements = explode(",", $result_key);
                $result_value =  ($value === '') ? '' :  $current_user->$value;
                if (count($userinfo_elements) === 2 && empty($result_value)) {
                    $result_value = trim($userinfo_elements[1]);
                }
                $str_input = str_replace('{'.$result_key.'}', urlencode($result_value), $str_input);
            }
        }
        return  $str_input;
    }


     static function replace_user_data($str_input) {
            $current_user = wp_get_current_user();
            
            $str_input = AdvancedIframeHelper::replace_key_with_default('userid', 'ID', $str_input,$current_user);
            if (empty($current_user->ID)) {
                $str_input = AdvancedIframeHelper::replace_key_with_default('username', '', $str_input,$current_user);
                $str_input = AdvancedIframeHelper::replace_key_with_default('useremail', '', $str_input,$current_user);
            } else {
                $str_input = AdvancedIframeHelper::replace_key_with_default('username', 'user_login', $str_input,$current_user);
                $str_input = AdvancedIframeHelper::replace_key_with_default('useremail', 'user_email', $str_input,$current_user);
                
                // dynamic $propertyName = 'id'; print($phpObject->{$propertyName});
                if (strpos($str_input,'{userinfo') !== false) {
                    $regex = '/{(userinfo.*?)}/';
                    $result = preg_match_all( $regex, $str_input, $match);
                    if ($result) {
                        foreach ($match[1] as $hits) {
                            $key = substr($hits, 9);
                            // we check if we have a default value
                            $userinfo_elements = explode(",", $key);
                            if (count($userinfo_elements) === 2) {
                                $value = $current_user->trim($userinfo_elements[0]);
                                if (empty($value)) {
                                    $value = trim($userinfo_elements[1]);
                                }
                            } else {
                                $value = $current_user->trim($key);
                            }
							$str_input = str_replace('{'.$hits.'}', urlencode($value), $str_input);
                        }
                    }
                }
                // postmeta! https://codex.wordpress.org/Custom_Fields
                if (strpos($str_input,'{usermeta') !== false) {
                    $regex = '/{(usermeta.*?)}/';
                    $result = preg_match_all( $regex, $str_input, $match);
                    if ($result) {
                        foreach ($match[1] as $hits) {
                            $key = substr($hits, 9);    
                             // we check if we have a default value
                            $usermeta_elements = explode(",", $key);
                            if (count($usermeta_elements) === 2) {
                                $value = get_user_meta( $current_user->ID, trim($usermeta_elements[0]), true );
                                if (empty($value)) {         
                                    $value = trim($usermeta_elements[1]);
                                }
                            } else {
                                $value = get_user_meta( $current_user->ID, trim($key), true );
                            }
                            $str_input = str_replace('{'.$hits.'}', urlencode($value), $str_input);
                        }
                    }
                }
            }
            return $str_input;
        }

       static function replace_full_url_data($str_input) {
            if (strpos($str_input,'{href}') !== false) {
                $location = (@$_SERVER["HTTPS"] === "on") ? "https://" : "http://";
                if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443" ) {
                    $location .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
                } else {
                    $location .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                }
                $str_input = str_replace('{href}', urlencode($location), $str_input);
            }
            return $str_input;
        }

        static function replace_query_data($str_input) {
            if (strpos($str_input,'{query') !== false) {
                $regex = '/{(query.*?)}/';
                $result = preg_match_all( $regex, $str_input, $match);
                if ($result) {
                    foreach ($match[1] as $hits) {
                       $key = substr($hits, 6);
                       $query_elements = explode(",", $key);
                          if (count($query_elements) === 2) {
                               $value = AdvancedIframeHelper::param(trim($query_elements[0]));
                              if (empty($value)) {
                                  $value = trim($query_elements[1]);
                              }
                          } else {
                              $value = AdvancedIframeHelper::param(trim($key));
                          }
                      $str_input = str_replace('{'.$hits.'}', $value , $str_input);
                    }
                }
            }
            return $str_input;
        }
		
		/**
		 * Handles {language:de,en,es}
		 */
		static function replace_locale_data($str_input) {
			if (strpos($str_input,'{language') !== false) {
				$regex = '/{(language.*?)}/';
                $result = preg_match_all($regex, $str_input, $match);
                if ($result) {
				    $language = strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
				    foreach ($match[1] as $hits) {
				        $lang_elements_string = strtolower(substr($hits, 9));
				        $lang_elements = explode(",", $lang_elements_string);
				        if (in_array($language, $lang_elements)) {
						    $str_input = str_replace('{'.$hits.'}', $language , $str_input);
				        } else {
						    $str_input = str_replace('{'.$hits.'}', $lang_elements[0] , $str_input);
						}
				    }
			    }  
			}
			return $str_input;
		}
			
    static function removeEmptyParameters($str_input) {
		$urlSplit = explode("?", $str_input);
		if (!isset($urlSplit[1])) {
			return $str_input;
		}
		$querySplit = explode("#", $urlSplit[1]);
		$hash = isset($querySplit[1]) ? '#' . $querySplit[1] : '';
		parse_str($querySplit[0], $queryArray);
		$cleanedParams = array_filter($queryArray, function($var) {
            return isset($var) && (is_array($var) || trim($var) !== '');
        });
		$count = count($cleanedParams);
		$i = 0;
		$newURL = $urlSplit[0] ;
		foreach ($cleanedParams as $key => $cleanParam){
			$newURL .= ($i === 0) ? '?' : '';
			// No cleaning is done when arrays are in the params. This could change the logic on the remote side 
			// if this is removed and not an array anymore...
			if (is_array($cleanParam)) {
				return $str_input;
			} else {
			    $newURL .= $key . "=" . urlencode($cleanParam) . (($i++ < $count - 1) ? '&' : '');
			}
		}
		return $newURL . $hash;	
	}

    static function replace_url_path_data($str_input) {     
        if (strpos($str_input,'{requesturi}') !== false) {
			$str_input .= trim($_SERVER['REQUEST_URI']);
		}
		if (strpos($str_input,'{urlpath') !== false) {
            // part of the url are extracted {urlpath1} = first path element
            $path_elements = explode("/", trim($_SERVER['REQUEST_URI'], "/"));
            $count = 1;
            foreach($path_elements as $path_element){
                $str_input = str_replace('{urlpath'.$count.'}', rawurlencode($path_element), $str_input);
                $count++;
            }
            // part of the url counting from the end {urlpath-1} = last path element
            reset($path_elements);
            $rpath_elements = array_reverse($path_elements);
            $count = 1;
            foreach($rpath_elements as $path_element){
                $str_input = str_replace('{urlpath-'.$count.'}', rawurlencode($path_element), $str_input);
                $count++;
            }
        }
        return $str_input;
    }
	
	static function check_shortcode_enabled($check_shortcode) {    
		return (isset($_GET['aiEnableCheckShortcode'])) ? 'true' : $check_shortcode;
	}
	
	static function check_debug_enabled($debug_js) {    
		return (isset($_COOKIE['aiEnableDebugConsole'])) ? 'bottom' : $debug_js;
	}
	
	static function check_debug_get_parameter() {
		 if (isset($_GET['aiEDC'])) {
	         $_GET['aiEnableDebugConsole'] = $_GET['aiEDC'];
		 }
		 if (isset($_GET['aiEnableDebugConsole'])) {
			if ($_GET['aiEnableDebugConsole'] === 'true') {
			  setcookie('aiEnableDebugConsole', 'bottom', 0, '/');
			  $_COOKIE['aiEnableDebugConsole'] = 'bottom';
			} else {
			  unset($_COOKIE['aiEnableDebugConsole']);	
			  setcookie('aiEnableDebugConsole', 'bottom', 1, '/');		  
			}
		}
	}

    static function aiUpdateOption($name, $value, $time) {
		$fileDir = dirname(__FILE__) . "/../../advanced-iframe-custom/";
		$fileName = $fileDir . "iframe-data.csv";
		$htaccessName = $fileDir . ".htaccess";
		if(!file_exists($htaccessName)) {
		    file_put_contents($htaccessName, "ErrorDocument 403 /\n<Files *.csv>\nDeny from all\n</Files>");
		}

		if ($time !== 0) {
			$time = time() + $time; 
		} else {
			$time = -(time() + 3600); 
		}
		if(file_exists($fileName)) {
			$csv = file_get_contents($fileName) . $name . "," . $value . "," . $time . "\n";
			file_put_contents($fileName, $csv, LOCK_EX);	
       } else {
		    $csv = $name . "," . $value . "," . $time . "\n";
		    file_put_contents($fileName, $csv, LOCK_EX);
	   }
	}

    static function allowIframeOpen($selector) {   
		return !isset($_COOKIE['ai_disable_autoclick_iframe_' . $selector]);
	}
	
	static function ai_startsWith($haystack, $needle) {
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}

    static function ai_endsWith($haystack, $needle) {         
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }
	
	/**
	 * Checks the parameter and returns the value. If only chars on the whitelist are in the request nothing is done
	 * Otherwise it is returned encoded.
	 */
	static function param($param, $content = null)
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
	
	static function aiContains($str, $substr) {
		return strpos($str, $substr) !== false;
	}
	
	/**
	* remove query string and trailing backslash
	*/
	static function aiRemoveQueryString($str) {
		if (AdvancedIframeHelper::aiContains($str,'?')) {
			$value = strstr($str, '?', true);
			return rtrim($value, '/');
		} else {
			return $str;	
		}
	}
	
	static function aiContainsParam($str, $substr) {
        $substr .= '=';
	    return strpos($str, '?' . $substr) !== false || strpos($str, '&amp;' . $substr) !== false;
    }
	
	static function aiPrintError($message) {
            echo '
           <div class="error">
              <p><strong>' . $message . '
                 </strong>
              </p>
           </div>';
        }
		
	static function aiExtractParam($url, $name) {
		$url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        return (isset($params[$name])) ? $params[$name] : ""; 	
	}
	
	/**
	*  Replaces the content with a temp iframe page.
	*/
	static function handleTempPages($content, $devOptions, $adminOptionsName) {
		$aiShortcode = get_shortcode_regex( array('advanced_iframe','advanced-iframe','iframe') );
		$result = preg_match( '/' . $aiShortcode . '/s', $content, $matches );
		if (isset($matches[0]) && AdvancedIframeHelper::aiContains($matches[0], 'src="page"')) {
			$atts = shortcode_parse_atts($matches[3]);
			if (isset($devOptions['enable_ai_content_pages']) && $devOptions['enable_ai_content_pages'] === 'false') {
			    $devOptions['enable_ai_content_pages'] = 'init';
				update_option($adminOptionsName, $devOptions);
			}
			if (isset($atts["id"])) {
			   $newContent = str_replace($matches[0], '', $content);
			   if (isset($atts['iframe_content_css'])) {
				 $css_filtered = wp_kses($atts['iframe_content_css'], array());
			     $newContent = '<style>' . $css_filtered . '</style>' . $newContent;
			   }
			   if (isset($atts['iframe_hide_elements'])) {
				 $hide_filtered = wp_kses($atts['iframe_hide_elements'], array());
				 $hide_array = explode(',', $hide_filtered);
				 $css_filtered = '';
				 foreach ($hide_array as $hide_element) {
				    $css_filtered .= $hide_element . "{ display: none !important; } ";	 
				 }
			     $newContent = '<style>' . $css_filtered . '</style>' . $newContent;
			   }
			   $newUrl = AdvancedIframeHelper::aiCreateDummyPage($atts["id"], $newContent);		
			   
			   $content = str_replace('src="page"', 'src="'.$newUrl.'"', $matches[0]);
			} else {
			   echo "<p>Please add an unique id to the iframe</p>";
			} 
		}
		return $content;
	}
	
	static function aiCreateDummyPage($id, $newContent) {
		
		$existingPage = AdvancedIframeHelper::aiGetPageByTitle('iframe-' . $id, 'ai_content_page');
		// Check if the page already exists
		if(empty($existingPage)) {
			$page_id = wp_insert_post(
				array(
				'comment_status' => 'close',
				'ping_status'    => 'close',
				'post_author'    => 1,
				'post_title'     => ucwords('iframe-' . $id),
				'post_name'      => sanitize_title('iframe-' . $id),
				'post_status'    => 'publish',
				'post_content'   => $newContent,
				'post_type'      => 'ai_content_page',
				)
			);
		} else if ($existingPage->post_content != $newContent || $existingPage->post_status != 'publish') {
		   $my_post = array(
			  'ID'           => $existingPage->ID,
			  'post_content' => $newContent,
			  'post_status'    => 'publish',
		   );
           $result = wp_update_post( $my_post, true );  
		}
		// generate the url
		return site_url() . '/ai_content_page/iframe-' . $id . '?' . rand(1000, 9999) ;
    }
	
	static function aiGetPageByTitle( $title, $post_type) {

		$query = new WP_Query(
	    	array(
		        'post_type'              => $post_type,
		        'title'                  => $title,
		        'post_status'            => 'all',
		        'posts_per_page'         => 1,
		        'no_found_rows'          => true,
		        'ignore_sticky_posts'    => true,
		        'update_post_term_cache' => false,
		        'update_post_meta_cache' => false,
		        'orderby'                => 'post_date ID',
		        'order'                  => 'ASC',
		    )
		);
		 
		if (!empty( $query->post)) {
		    $page_got_by_title = $query->post;
		} else {
		    $page_got_by_title = null;
		}

		return $page_got_by_title;
	}
	
	static function aiDelecteAiContentPages() {
	    $pages = get_pages( array( 'post_type' => 'ai_content_page', 'post_status' => get_post_stati()));	
        if ($pages != false) {
		    foreach( $pages as $page ) {	
	            wp_delete_post( $page->ID, true);
	        }
		}
	}
	
	static $replaceArrayXSS = array('"',"'",' ', '(',')',';','=','}');
	
	static function filterXSS($value) {
		return empty($value) ? '' : str_replace(static::$replaceArrayXSS, '', $value);
	}
	
	static function filterXSSTrueFalse($value) {
		return ($value === 'true' || $value === 'false') ? $value : 'false';	
	}
	
	static $replaceBasicXSS = array('"',"'",' ', '(',')',';','}');
	
	static function filterBasicXSS($value) {
		return empty($value) ? '' : str_replace(static::$replaceBasicXSS, '', $value);
	}
}
?>