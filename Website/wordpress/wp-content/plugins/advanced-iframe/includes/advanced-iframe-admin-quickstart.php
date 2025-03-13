<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  Print the donation details depending on the version type
 */
 
 
function testContentCallback() {
	echo "content";
}
 
function printQuickstartGuide($closedArray) {	
aiPostboxOpen("id-options-quickstart", "Quick start guide", $closedArray);  

_e('<p>
      <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-video-tutorials" target="_blank" id="vid" class="button-primary">Show me the quickstart video</a>    
</p>

<p>To include a web page to your page please check the following things:</p>
<ul>
<li>- Check if your page you want to include is allowed to be included:<br />&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker">www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker</a>!</li>
<li>- Check if the iframe page and the parent page are one the same domain. www.example.com and text.example.com are different domains!</li>
<li>- Can you modify the page that should be included?</li>
</ul>
<p>Most likely you have one of the following setups:</p>
<ol>
<li>Iframe can be included and you are on the same domain: All features of the plugin can be used.</li>
<li>iframe can be included and you are on a different domain: See the <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart" target="_blank">feature comparison chart</a> and the <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-features-availability-overview" target="_blank">features availability overview</a>. To resize the content to the height/width or modify css you <strong>need to modify the remote iframe page</strong> by adding one line of Javascript to enable the "<a id="external-workaround-link" href="#xss">External workaround</a>".</li>
<li>iframe cannot be included: You cannot include the content because the owner does not allow this. </li>
</ol>', 'advanced-iframe');

_e('<p>To enter a simple iframe please go to the administration and follow the instructions on the basic settings tab. There you can either use a basic shortcode and set the settings in the administration or overwrite the settings directly in the shortcode. Please also read the <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-faq" target="_blank">FAQ</a> and look at the <a href="//www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0" target="_blank">free</a> and <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo" target="_blank">pro examples</a>.</p>', 'advanced-iframe');

_e('<p>Advanced users that have their own server might also setup a reverse proxy if the iframe page is on a different domain and cannot use the "<a id="external-workaround-link" href="#xss">External workaround</a>". See <a href="//www.tinywebgallery.com/blog/using-a-reverse-proxy-to-enable-all-features-of-advanced-iframe-pro" target="_blank">this blog</a> for details.<br />', 'advanced-iframe');
_e('If you mix http and https read <a href="//www.tinywebgallery.com/blog/iframe-do-not-mix-http-and-https" target="_blank">this blog</a>. Parent https and iframe http is not supported by any browser anymore!</p>', 'advanced-iframe');

	  aiPostboxClose();	
} 
 
function printDonation($devOptions, $evanto, $closedArray) {
global $isFreemius, $isFreemiusMigration, $ai_fs;
if ($evanto) {
      echo '<br/>
      <div>
      <div id="icon-options-general" class="icon_ai">
      <br>
      </div><h2 class="options-h2">';
      _e('Advanced iFrame Pro - Quickstart guide, plugin options, widget, vote for the plugin on code canyon', 'advanced-iframe');
    echo '</h2>';
	if (!$isFreemius) {
		// Registration    
		aiPostboxOpen("id-options-registration", "Registration", $closedArray);  
		echo '<table class="form-table hide-print">';
		
		$status = '';
		if (empty($devOptions['purchase_code'])) {    
			$status = __('<br><strong>Without a purchase code the 10.000 views/month of the free version is active.</strong> The iframes are still working but below all iframes a small "powered by" notice with a link to the pro version is shown. If you do not have access to the license anymore please login to codecanyon and contact me <a href="https://codecanyon.net/user/mdempfle" _target="_blank">here</a> with a proof of purchase so I can send you a custom license code.<br><strong>Current status</strong>: ', 'advanced-iframe');
			$viewsPercent = get_option('default_a_options') / 100;
			$status .= $viewsPercent . ' % of views for this month used.';
		   
			if ($viewsPercent > 100) {
			 $status .= __('<strong style="color: red"> The view li'.'mit is reached. A sm'.'all notice is shown be'.'low all iframes. Please reg'.'ister above.</strong>', 'advanced-iframe');
			}	
		} 
		
		$freeAdditional = $isFreemiusMigration ? __('<br><br>Advanced iframe pro has migrated to freemius. And this are the reasons why you should also move your existing license:<span>Automatic updates!</span><span>Different plans -> monthly, yearly, live time</span><span>Multi site support -> 1,5, unlimited sites plans available </span><span>You can switch plans or add additional site licenses how you need it.</span><span>Better support rules as for codecanyon. If you choose a subscription plan you get support as long as you plan runs.</span><br>If you have a codecanyon license already <a href="xxx">you can migrate it to freemius.</a><br><br>There is also a very good reason why I migrated: The fees are simply much lower than on  codecanyon (7% vs. 30%-70%!) and I never liked that codecanyon does not offer auto update and multi site licenses which many people complained about. This is also the reason why the live time license on freemius is cheaper then the one on codecanyon!</p><p>Just check by yourself: <a href="https://freemius.com/wordpress/features-comparison/" target="_blank">https://freemius.com/wordpress/features-comparison/</a>', 'advanced-iframe' ) : "";

		printPurchaseCodeInput($devOptions, __('Purchase code', 'advanced-iframe' ), 'purchase_code', __('Please enter your purchase code here to finish the registration and unlock all features of advanced iframe pro. Your license key (purchase code) is located inside your digital purchase receipt on your <a href="https://codecanyon.net/downloads" target="_blank">CodeCanyon Downloads page</a>. To retrieve your license key, visit CodeCanyon.net/downloads and click "Download > License Certificate". Please note that your purchase code will be validated and logged together with relevant data to detect fraud. Also see the privacy policy here: https://www.tinywebgallery.com/blog/privacy-policy for details. Please do not share your purchase code as it will be blocked when misuse will be detected.', 'advanced-iframe') . $status . $freeAdditional);
		echo '</table>';
		aiPostboxClose();
	}
	printQuickstartGuide($closedArray);

    _e('<h3 class="hide-print">Plugin options</h3>', 'advanced-iframe');
	
// Display
    aiPostboxOpen("id-options-display", "Display options", $closedArray);  
    echo '<table class="form-table hide-print">';
    printTrueFalse(false,$devOptions, __('Show this section as last tab', 'advanced-iframe'), 'donation_bottom', __('<strong class="move-bottom">You can show this tab as last tab after you have read it. Then the basic tab is shown first.</strong>', 'advanced-iframe'));
	printTrueFalse(false,$devOptions, __('Enable expert mode', 'advanced-iframe'), 'expert_mode', __('If you enable the expert mode the description is only shown if you click on the label of the setting. You see more settings at once but only one description at once. Also the padding between the table rows are reduced a lot. So you see a lot of more settings on one screen. Use this if you are common with the settings.', 'advanced-iframe'), 'false');
	printTextInput(false,$devOptions, __('Editor button', 'advanced-iframe'), 'editorbutton', __('With this setting you can add an "advanced iframe" button to the text editor of Wordpress. The button does add the shortcode with the current security code if set + the settings you define. You can use any setting from the administration. By default src,width,height is used. The securitykey is additionally rendered if you specify one. If you leave this setting empty the button is not shown.', 'advanced-iframe')); 
    echo '</table>';
    aiPostboxClose();

// Debug/validation	
    aiPostboxOpen("id-options-debug", "Validation of iframes/Debug console", $closedArray);  
    echo '<table class="form-table hide-print">';  
	printTrueFalse(false,$devOptions, __('Check shortcode', 'advanced-iframe'), 'check_shortcode', __('<strong class="move-bottom">If you enable this the plugin does check if the shortcode attributes are known. You will find typos, wrong quotes and missing spaces. It does not check the values! The only reason this is not enabled by default is to make sure that old shortcodes don\'t show a warning after an update! I strongly recommend to enable this setting! You can also add ?aiEnableCheckShortcode=true to the url to do a check dynamically. By default this check is done when you do a preview of your page.</strong>', 'advanced-iframe'), true); 
    printDebug($devOptions, __('Debug Javascript', 'advanced-iframe'), 'debug_js', __('You can enable that most messages from the Javascript console are shown in a debug div which shown at the bottom of the page. This is very useful if you have problems on Android or IOS because there it is quite hard to get on the infos displayed in the Javascript log. If you enable this you also get additional output from the ai.js and there you see which important methods are called. Important: Since 2021.2 also the external workaround with postMessage set to debug automatically and the ai_external.js also runs in debug mode! The current user agent, the headers and a basic iframe check of the first found iframe are printed into the debug log. Click on the debug area to enlarge and shrink it. You can also append ?aiEnableDebugConsole=true/false or ?aiEDC=true/false to the url to enable/disable the debug console. Shortcode attribute: debug_js="false/bottom"  ', 'advanced-iframe'));
	echo '</table>';
    aiPostboxClose();
 
// Check/Monitoring 
      aiPostboxOpen("id-options-check-monitoring", "Checking/Monitoring of iframes", $closedArray);  
      echo '<table class="form-table hide-print">';    
      printTrueFalse(false,$devOptions, __('Check Url on load', 'advanced-iframe'), 'check_iframe_url_when_load', __('By default the url on the basic tab is checked if it can be included into an iframe. Some servers do block this feature and the administration does fail then. If this is the case the plugin does disable this check and "Check iframes on save" automatically. You can enable this feature again if you allow curl calls on your server.', 'advanced-iframe'), true);
      printAllWarningFalse($devOptions, __('Check iframes on save', 'advanced-iframe'), 'check_iframes_when_save', __('You can define if you show errors only, errors and warnings or if iframes are not checked at all at save of a post/page. The Messages are only shown in the classic editor. The block editor does update the url automatically and you see a not loading page right away. Some servers do block this feature and you get a blank editor page on save once. If this is the case the plugin does disable this check automatically. You can enable this feature again if you allow curl calls on your server.', 'advanced-iframe') );
      printTrueFalse(true,$devOptions, __('Check all iframes once a day', 'advanced-iframe'), 'check_iframe_cronjob', __('You can automatically check all iframes of your site once a day. IF you upgrade from an older version please deactivate and active the plugin once to get the cronjob setup properly! The same function like  on the "Basic" tab -> Url -> "Check all iframes" is triggered here. Be aware that default Wordpress cronjobs are triggered by the user that hits the next execution time and therefore this user has to wait until this check is done! Please go to the administration if you get an e-mail for a full check! If the checks of your iframes takes longer than 5 sec. I recommend to switch to a native cronjob if possible. Google for "Wordpress cronjob replace" and also add your hoster! Because native cronjobs can not be created everywhere and also often depends on your package. To test cronjobs you can e.g. use the plugin "WP Control" where you can simply execute this cronjob.', 'advanced-iframe'));
      printNumberInput(true,$devOptions, __('i-20-Check batch size', 'advanced-iframe'), 'check_iframe_batch_size', __('This setting defines how many urls are checked in parallel. This is system dependent (number of available file descriptors) and can vary between 10 and over 1000. Some blog entries talk about a default of 256. During testing 20 was the value which worked fine on all systems tested. If you get many "This url cannot be checked..." messages you should reduce this value. A check is normally 3-4 sec. So if you have e.g. 30 iframe URLs to test and use the default of 20 the whole check takes ~ 7 sec.', 'advanced-iframe'));  
	  printTextInput(true,$devOptions, __('i-20-Check iframe cronjob email', 'advanced-iframe'), 'check_iframe_cronjob_email', __('You can define an alternative e-mail the iframe status check is sent to. If you leave this field empty the admin e-mail from "Settings -> Email Address" is used. You can define several e-mails separating them by ",".','advanced-iframe'));
      printTrueFalse(true,$devOptions, __('i-20-Send e-mail always', 'advanced-iframe'), 'check_iframe_cronjob_email_always', __('Get a report by e-mail always or only on case of a problem.', 'advanced-iframe'), 'true');
      echo '</table>';
      aiPostboxClose(); 
 
// Other iframe solutions 
      aiPostboxOpen("id-options-other-iframe", "Support for other iframe solutions", $closedArray);  
      echo '<table class="form-table hide-print">';  
      printTrueFalse(false,$devOptions, __('Replace iframe tags with advanced iframe shortcode', 'advanced-iframe'), 'replace_iframe_tags', __('When you set this to Yes all existing iframes on your site will be replaced by the advanced iframe shortcode dynamically. So you get all the power of the plugin without exchanging all your old iframes. The iframes are parsed and the main attributes (src, width, height, id, name, class, style) are used in the shortcode + all settings you make in the administration. Be aware that they all get the same additional settings! So only use this if this works for you. You can of course if you need only some iframes to be different replace them manually. If you have several iframes on the same page make sure that if they have an id, the id is unique.', 'advanced-iframe'), false);  
	  printTextInput(false,$devOptions, __('Alternative shortcode', 'advanced-iframe'), 'alternative_shortcode', __('You can define an alternative shortcode the plugin should evaluate. By default you can use: advanced_iframe and advanced-iframe. This is e.g. useful if you chance/upgrade from iframe to advanced iframe (pro). Simply insert e.g. "iframe" in the text field. Most if the parameters do already match! Make sure to deactivate the other plugin that used the shortcode. With using iframe also the BBCode [iframe]url[/iframe] is supported. IMPORTANT: If you use this, security codes are NOT checked anymore. So anyone who can e.g. write a post can also insert an iframe!', 'advanced-iframe'));
	  echo '</table>';
      aiPostboxClose();	   
	   
// Share part of your content  
      aiPostboxOpen("id-options-share", "Share part of your content", $closedArray);  
      echo '<table class="form-table hide-print">';  
	  printTrueFalse(true,$devOptions, __('Enable content filter', 'advanced-iframe'), 'enable_content_filter', __('This feature does not render an iframe. It gives you the option to filter the content of your page by an id. So you can offer parts of your page that then can be included into any iframe. You only need to specify the id of the element you want to show with the parameter ?ai-show-id-only=id. If you only specify this parameter the whole page is loaded and then with Javascript all other elements are hidden. If you add ai-server-side=1 to the url the content is filtered on the server side but this only works for elements which are in the content area because everything else depends on the template. By default overflow (scrolling) is hidden inside the iframe. If you like that scrollbars are shown if needed add &ai-show-overflow=1 to the url. Also check "Add ai_external.js local" as this actually the even more powerful solution but more complicated to setup. So try what fits best to your needs! Also the height of the content is sent to the parent by a post message. Please see the <a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-content-filter">demo</a> how you can use this and code you need to include to use this the optimal way.', 'advanced-iframe'), 'false', '//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-content-filter');
      printAiExternalLocal(false,$devOptions, __('Add ai_external.js local', 'advanced-iframe'), 'add_ai_external_local', __('The setting does add the ai_external.js to your own site. This enables you to provide parts of your site into an external iframe or use the "<a id="external-workaround-link" href="#xss">External workaround</a>" for the same domain. This is similar to "enable_content_filter" where you can filter parts of your page. The advantage of this solution is that you can use all css modifications and auto height of this solution. Also resize of element resize does work here. Also this works on included links if they still stay on the page. The disadvantage is that it is more complicated to setup then "enable_content_filter" and only one configuration is supported automatically. Also the height of the content is sent to the parent by a post message. If you like to include the script only to a single page use the shortcode [ai_advanced_js_local] to your page. Then the script will be included to your footer. You can even add custom settings like described on the "<a id="external-workaround-link" href="#xss">External workaround</a>" tab by adding a script to the content. You can enable this for the site, the admin pages or both. Please see the <a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-add-ai_external-js-local">demo</a> how you can use this and code you need to include to use this the optimal way.', 'advanced-iframe'), 'false','//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/share-content-from-your-domain-add-ai_external-js-local');
      echo '</table>';
      aiPostboxClose(); 
	  
// Technical   
      aiPostboxOpen("id-options-technical", "Technical options", $closedArray);  
      echo '<table class="form-table hide-print">';   
      printTrueFalse(false,$devOptions, __('Include ai.min.js in the footer', 'advanced-iframe'), 'include_scripts_in_footer', __('By default the Javascript file ai.min.js is included in the footer and then jQuery can also be loaded there. This has the advantage that the page is shown quicker to the user but if the iframe loads very fast the needed methods are not available yet. Please check the browser console (F12). If you see errors that advanced iframe methods are not found: Set this to false. The ai.min.js has also to be in the footer if it should only be loaded when the shortcode is on the page. This setting cannot be set as shortcode! There is an additional shortcode attribute called include_scripts_in_content="true". This is only needed in the special case if you use the page with content only (like using the plugin "Show Content Only" with "Content + Styles" mode). Then ai.js is directly rendered before the iframe. See demo <a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/same-domain-wrapped-auto-height">wrapped auto height</a>.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Load jQuery as dependency', 'advanced-iframe'), 'load_jquery', __('By default jQuery is loaded as dependency. If you have a theme or another plugin that does not stick to the Wordpress way to load the scripts you might have to disable the dependency. This avoids that jQuery is loaded again and other plugins do maybe not work anymore.', 'advanced-iframe'), true);   
      printTrueFalse(false,$devOptions, __('Fix WordPress 5.5 page parameter change', 'advanced-iframe'), 'remove_page_param_from_query', __('In Wordpress 5.5 the page parameter returns with a 301 redirect if not used the WordPress way. Setting this to true does allow to still use the parameter page in the url with non numerical characters. The recommended way is to not use page as parameter in the url at all.', 'advanced-iframe'), 'false');
	  printTrueFalse(false,$devOptions, __('Prevent early shortcode execution', 'advanced-iframe'), 'optimize_rendering', __('Some plugins like older version of all-in-one-seo do evaluate shortcodes for internal use. This slows down the page. Advanced iframe was detecting this and prevented this rendering. Till the theme Twenty Twenty-One this worked fine out of the box. Unfortunately in the theme Twenty Twenty-Two the iframe shortcode is evaluated before the HTML outputs starts. And then no iframe is rendered at all. Because of this and as all-in-one-seo also fixed this on their side, this optimization is disabled by default! The iframe is then rendered in all situations. Set this setting to "Yes" and <strong>test if the iframe is still shown</strong>. You can check if you need this setting:<span>Set this setting to Yes.</span><span>Enable on the options page "Debug Javascript".</span><span>Go to a page with an iframe and view the source code.</span><span>If you see &lt;!-- advanced iframe not rendered --&gt; in the HTML <strong>AND your iframe is still shown</strong> then you should use the optimization if possible. Otherwise you can use "No" here without any disadvantage. Enabling the debug will also show the rendering time of advanced iframe like this: &lt;!-- advanced iframe rendered in 0.000563 s --&gt;</span>', 'advanced-iframe'), 'false');
	  printTrueFalse(false,$devOptions, __('Enable temporary ai content pages', 'advanced-iframe'), 'enable_ai_content_pages', __('This is an advanced feature where the content of your page is not shown directly but a dynamic pages is created. See the demo <a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/show-content-in-iframe">Display content in iframe</a> how this us used and configured. Please note that you need to reload your page once after the save because Wordpress executes the needed hooks in the wrong order.', 'advanced-iframe'), 'false', '//www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/show-content-in-iframe');
  	 echo '</table>';
      aiPostboxClose();

// Security/delete
	  aiPostboxOpen("id-options-delete", "Security/Delete options", $closedArray);  
      echo '<table class="form-table hide-print">'; 
	  printTrueFalse(false,$devOptions, __('Allow shortcode attributes', 'advanced-iframe'), 'shortcode_attributes', __('Allow to set attributes in the shortcode. All of the attributes can be overwritten in the shortcode if you set \'Yes\'. Otherwise the settings you specify here are used.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Use shortcode attributes only', 'advanced-iframe'), 'use_shortcode_attributes_only', __('All iframes you use in your pages use the administration. With shortcode attributes you can overwrite these settings. When you use several iframes with different settings this can lead to unexpected behavior because you do not see the whole configuration in the shortcode. By setting this option to true only the parameters defined as attributes are used. You can set this for a single iframe as well with the shortcode attribute use_shortcode_attributes_only="true". Shortcode attribute: use_shortcode_attributes_only="true" or use_shortcode_attributes_only="false"', 'advanced-iframe'));
     
      $user = wp_get_current_user();
      if ( in_array( 'administrator', (array) $user->roles ) ) {
          printRoles($devOptions, __('Minimum user role', 'advanced-iframe'), 'roles', __('You can define the minimum user role a user needs to use the advanced iframe plugin. This limits the access to the administration, the editor button and if a user can edit a page with a advanced iframe shortcode. If a page with an advanced iframe is detected and the rights are not sufficient an error message is displayed and the user can not edit the page! A user with insufficient rights still can add the shortcode if he has the security code! This settings only works optimal for the 5 default user roles as it depends on the order of roles! So please check if this settings works for you if you have additional roles! Default restrictions means that the administration is only shown for administrators and the editor button for everyone who can edit a post/page. This setting can only be changed by an administrator!', 'advanced-iframe'), 'contributor');
      }
      printTrueFalse(false,$devOptions, __('Remove data when plugin is deleted', 'advanced-iframe'), 'delete_options_db', __('You can remove also all settings in the database when deleting the plugin. By default this is not enabled because updating the pro version is done by deleting the old version and installing the new version again. So please only set this setting to true if you completely want to remove the plugin. Also delete the "Advanced iFrame custom folder" plugin after you have deleted the main plugin.', 'advanced-iframe'), false);  	  
      echo '</table>';
      aiPostboxClose();
	  
  // echo '<p>';
  // echo '<p class="button-submit">
  //      <input class="button-primary" type="submit" name="update_iframe-loader" value="';
  //    _e('Update Settings', 'advanced-iframe');
  // echo '"/></p>';
  
   _e('<h3>Widget, vote</h3>', 'advanced-iframe');
  
    aiPostboxOpen("id-options-widget", "Advanced iFrame Pro Widget", $closedArray);  
    _e('The pro version also does offer a widget where you can include the iframe. The usage is really simple. Go to Appearance -> Widgets and insert the shortcode you would normally put into a page into the text field of the "Advanced iFrame Pro Widget".', 'advanced-iframe' );
    aiPostboxClose();	   
	
	aiPostboxOpen("id-options-vote", "Vote for the plugin", $closedArray);  
	_e('Thank you for getting Advanced iFrame Pro at Codecanyon.<br/>', 'advanced-iframe' );
	_e('Please feel free to leave an item rating from your items download page if you haven\'t already done so.</p>', 'advanced-iframe' );
	_e('<p>Please get in contact with me if you have problems because most of the issues are easy to solve. But at least tell me what you did not like so I can improve this. Also make sure that you took a look at the quick start guide to make sure the feature you like can be used!</p>', 'advanced-iframe' );
	aiPostboxClose();	   


} else {

echo '<br/>
<div>
    <div id="icon-options-general" class="icon_ai">
    <br>
  </div><h2 class="options-h2">';
  _e('Advanced iFrame - Upgrading to Advanced iFrame Pro, quick start guide, plugin options', 'advanced-iframe');
  echo '</h2>';
  aiPostboxOpen("id-options-pro", "Upgrading to Advanced iFrame Pro", $closedArray);  
  if ($isFreemiusMigration) {
      _e('<p>Advanced iframe is <strong>free for personal use</strong> and the Pro version a bargain for your business. The personal version does already contain many of the cool features of the Pro version. It has a limit of 10.000 views a month without a notice text which should normally not been hit by a personal website.</p>', 'advanced-iframe' );
  } else {
	   _e('<p>Advanced iframe is <strong>free for personal use</strong> and the Pro version a bargain for your business. The personal version does already contain many of the cool features of the Pro version. It has after opt-in unlimited views and after 10.000 views/month a small "powered by" notice which should normally not been hit by a personal website.</p>', 'advanced-iframe' );
  }

$isRegistered = $isFreemiusMigration && $ai_fs->is_registered() && $ai_fs->is_tracking_allowed();
$freemius_css = ($isFreemiusMigration) ? " signup_account_container_freemius" : "";
$freemius_inner_css = ($isFreemiusMigration) ? " signup_inner_freemius" : "";

echo '<div id="first" class="signup_account_container signup_account_container_active'.$freemius_css.'" style="cursor: default;" title="';
_e('Free - For personal and non-commercial sites', 'advanced-iframe');
echo '">
			<div class="signup_inner'.$freemius_inner_css.'">
				<div class="signup_inner_plan">';
        _e(' ', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_price">
					<strong>';
          _e('FREE', 'advanced-iframe');
          echo '</strong>
				</div>
				<div class="signup_inner_header">';
        _e('For personal and non-commercial sites', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_desc">';
        if ($isFreemiusMigration) {
		  _e('No OPT-IN: 5000 views/month<br>OPT-IN: Unlimited views*', 'advanced-iframe');
        } else {
		  _e('10.000 views/month without notice*', 'advanced-iframe');
		}
		echo '</div>';
		
		if ($isFreemiusMigration) {
			if ($isRegistered) {
				echo '<a href="#" id="opt-in-button" class="signup_inner_button opt_in_done" title="">';
                _e('Opt-In done<br><strong>unlimited</strong> views*', 'advanced-iframe');
			    echo '</a>';
			} else {
			    $optinUrl = $ai_fs->get_reconnect_url();	
			    echo '<a href="'.$optinUrl.'" id="opt-in-button" class="signup_inner_button" title="">';
                _e('Opt-In for <br><strong>Unlimited</strong> views', 'advanced-iframe');
			     echo '</a>';
			}
			echo '<br>';	
		}
		echo '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=paypal%40mdempfle%2ede&item_name=advanced%20iframe&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=EN&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank" id="plan_button_pro" class="signup_inner_button" title="">';
		_e('Donate with Paypal', 'advanced-iframe');
		
		echo '</a>';
		echo '</div>
    </div>
      ';
echo '
   <div  class="signup_account_container signup_account_container_active'.$freemius_css.'" style="cursor: default;" title="';
   _e('Pro - For commercial, business and professional sites', 'advanced-iframe');
   echo '">
			<div class="signup_inner'.$freemius_inner_css.'">
				<div class="signup_inner_plan">';
        _e(' ', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_price">
					<strong>PRO</strong>
				</div>
				<div class="signup_inner_header">';
        _e('For commercial, business and professional sites', 'advanced-iframe');
        echo '</div>
				<div class="signup_inner_desc">';
        _e('+ <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/advanced-iframe-comparison-chart" target="_blank">Many additional features!</a><br />&nbsp;', 'advanced-iframe');
        echo '</div>';
		if ($isFreemiusMigration) {
		    $trialUrl =  $ai_fs->get_trial_url();
			$pricingUrl = $ai_fs->get_upgrade_url();
			echo '<a href="' . $trialUrl . '&trial=true" id="plan_button_pro" class="signup_inner_button" title="">';
				_e('Start 30 days trial', 'advanced-iframe');
			echo '</a><br>';
			echo '<a href="' . $pricingUrl . '" target="_blank" id="plan_button_pro" class="signup_inner_button" title="">';
			_e('Upgrade to <br><strong>PRO</strong>', 'advanced-iframe');
		} else {
			echo '<a href="https://1.envato.market/OdoBZ" target="_blank" id="plan_button_pro" class="signup_inner_button">';
        _e('Get pro at Codecanyon', 'advanced-iframe');
		}
        echo '</a>
			</div>
		</div>
';
echo '
       <div id="last" class="signup_account_container signup_account_container_active'.$freemius_css.'" style="cursor: default;">
			<div class="signup_inner'.$freemius_inner_css.'">
				<div class="signup_inner_plan">';
        _e('Pro Version Benefits', 'advanced-iframe');
        echo '</div>

				<div class="signup_inner_desc">
           <ul class="pro"><li>';
           _e('<a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/show-only-a-part-of-the-iframe" target="_blank">Show/Hide specific areas of the iframe</a> if the iframe is on a different domain<br /><a target="_blank" href="//www.mdempfle.de/examples/configurator/advanced-iframe-area-selector.html">Show the graphical selector</a></li><li><a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/widgets" target="_blank">Widget support</a>, <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/change-links-targets" target="_blank">change link targets</a></li><li>External workaround supports <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/external-workaround-auto-height-and-css-modifications" target="_blank">iframe modifications</a> and <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/responsive-iframes" target="_blank">responsive iframes</a></li><li><a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/browser-detection" target="_blank">Browser dependant settings</a>, <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/lazy-loading" target="_blank">lazy load</a></li><li>No view limit, <a href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo/zoom-iframe-content" target="_blank">zoom</a>, <a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-standalone">standalone version!</a></li><li><a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo">See the pro demo</a><li><a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart">Compare versions for all features</a>', 'advanced-iframe');
           echo '</li></ul>';
		   if ($isFreemiusMigration) { 		  
			  echo '<ul class="pro"><li>';
			   _e('Multi site licenses', 'advanced-iframe');
			   echo '</li><li>';
			   _e('Monthly, yearly, live time licenses', 'advanced-iframe');
			   echo '</li></ul>';
		   }
		   echo '
        </div>
			</div>
		</div>

<div class="clear"></div><br />
';
if ($isFreemiusMigration) {
_e('<p>* After 10.000 views/month the iframe is still working and below all iframes a small "powered by" notice with a link to the pro version is shown. If you hit this limit and you qualify for the free license please contact <a href="//www.tinywebgallery.com/en/about.php" target="_blank">me</a> to get a version with a higher limit.</p>', 'advanced-iframe');	
} else {
_e('<p>* After 10.000 views/month the iframe is still working but below all iframes a small "powered by" notice with a link to the pro version is shown. If you hit this limit and you qualify for the free license please contact <a href="//www.tinywebgallery.com/en/about.php" target="_blank">me</a> to get a version with a higher limit.<br/>If you use the Advanced iFrame on a non personal website please first test the plugin carefully before buying. After that it is quick and painless to get Advanced iFrame Pro. Simply get <strong><a target="_blank" href="https://1.envato.market/OdoBZ">Advanced iFrame Pro on CodeCanyon</a></strong> and be pro in a few minutes!</p>', 'advanced-iframe');
}

_e('<p><strong>Current status</strong>: ', 'advanced-iframe');
if ($isFreemiusMigration) {
	
	$viewsPercent = get_option('default_a_options') / 100;
			$status .= $viewsPercent . ' % of views for this month used.';
		   
			if ($viewsPercent > 100) {
			 $status .= __('<strong style="color: red"> The view li'.'mit is reached. A sm'.'all notice is shown be'.'low all iframes. Please reg'.'ister above.</strong>', 'advanced-iframe');
			}	
	
    if ($isRegistered) {
		$viewsPercent = get_option('default_a_options') / 100;
	    echo $viewsPercent . ' % of views for this month used until a small notice is shown.';
		if ($viewsPercent > 100) {
		    _('<strong style="color: red"> 10.000 views are reached. A sm'.'all notice is shown be'.'low all iframes. Please reg'.'ister above.</strong>', 'advanced-iframe');
		}	
	} else {
		$viewsPercent = get_option('default_a_options') / 50;
		echo $viewsPercent . ' % of views for this month used. Please OPT-IN to get unlimited views*';
		if ($viewsPercent > 100) {
		    _('<strong style="color: red"> 5.000 views are reached. Your iframes are NOT shown anymore. Please OPT-IN to get unlimited views*</strong>', 'advanced-iframe');
		}	
	}
} else {
    echo get_option('default_a_options') / 100 . ' % of views for this month used.';
}
_e('</p>', 'advanced-iframe');
aiPostboxClose();	   


printQuickstartGuide($closedArray);

 _e('<h3 class="hide-print">Plugin options</h3>', 'advanced-iframe' );
// Display
      aiPostboxOpen("id-options-display", "Display options", $closedArray); 
      echo '<table class="form-table">';
      printTrueFalse(false,$devOptions, __('Show this section as last tab', 'advanced-iframe'), 'donation_bottom', __('<strong class="move-bottom">You can show this tab as last tab after you have read it. Then the basic tab is shown first.</strong>', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Show the administration of the pro version and test many features in the preview', 'advanced-iframe'), 'demo', __('<strong class="move-bottom">You can enable the administration of the pro version to see the available features there. Everything except the additional buttons and additional settings on the options tab are shown. All features which can be configured by a shortcode will work in the preview now! So you can really test most of the features before you buy! The additional features of the external workaround with the ai_extenal.js will NOT work as here because the code is not included and only available in the download from codecanyon! All pro features have a blue label or differences are described in the documentation!</strong>', 'advanced-iframe'));
      printTextInput(false,$devOptions, __('Editor button', 'advanced-iframe'), 'editorbutton', __('With this setting you can add an "advanced iframe" button to the text editor of Wordpress. The button does add the shortcode with the current security code if set + the settings you define. You can use any setting from the administration. By default src,width,height is used. The securitykey is additionally rendered if you specify one. If you leave this setting empty the button is not shown.', 'advanced-iframe')); 
      echo '</table>';
      aiPostboxClose();
 
 // Debug/validation	
      aiPostboxOpen("id-options-debug", "Validation of iframes/Debug console", $closedArray);  
      echo '<table class="form-table hide-print">';  
      printTrueFalse(false,$devOptions, __('Check shortcode', 'advanced-iframe'), 'check_shortcode', __('<strong class="move-bottom">If you enable this the plugin does check if the shortcode attributes are known. You will find typos, wrong quotes and missing spaces. It does not check the values! The only reason this is not enabled by default is to make sure that old shortcodes don\'t show a warning after an update! I strongly recommend to enable this setting! You can also add ?aiEnableCheckShortcode=true to the url to do a check dynamically. By default this check is done when you do a preview of your page.</strong>', 'advanced-iframe'), true);
      printDebug($devOptions, __('Debug Javascript', 'advanced-iframe'), 'debug_js', __('You can enable that most messages from the Javascript console are shown in a debug div which shown at the bottom of the page. This is very useful if you have problems on Android or IOS because there it is quite hard to get on the infos displayed in the Javascript log. If you enable this you also get additional output from the ai.js and there you see which important methods are called. Important: You need to use the external workaround with postMessage set to debug(!) (see the external workaround tab) if you also want to get the messages from the page in the iframe! Also the current user agent, the headers and a basic iframe check of the first found iframe are printed into the debug log. Click on the debug area to enlarge and shrink it. You can also append ?aiEnableDebugConsole=true/false or ?aiEDC=true/false to the url to enable/disable the debug console. Shortcode attribute: debug_js="false/bottom"  ', 'advanced-iframe'));         
     echo '</table>';
     aiPostboxClose();
 
 // Check/Monitoring 
      aiPostboxOpen("id-options-check-monitoring", "Checking/Monitoring of iframes", $closedArray);  
      echo '<table class="form-table hide-print">';    
      printTrueFalse(false,$devOptions, __('Check Url on load', 'advanced-iframe'), 'check_iframe_url_when_load', __('By default the url on the basic tab is checked if it can be included into an iframe. Some servers do block this feature and the administration does fail then. If this is the case the plugin does disable this check and "Check iframes on save" automatically. You can enable this feature again if you allow curl calls on your server.', 'advanced-iframe'), true);
      printAllWarningFalse($devOptions, __('Check iframes on save', 'advanced-iframe'), 'check_iframes_when_save', __('You can define if you show errors only, errors and warnings or if iframes are not checked at all at save. Some servers do block this feature and you get a blank editor page on save once. If this is the case the plugin does disable this check automatically. You can enable this feature again if you allow curl calls on your server.', 'advanced-iframe') );
      echo '</table>';
      aiPostboxClose(); 
 
 // Other iframe solutions 
      aiPostboxOpen("id-options-other-iframe", "Support for other iframe solutions", $closedArray);  
      echo '<table class="form-table hide-print">';  
      printTrueFalse(false,$devOptions, __('Replace iframe tags with advanced iframe shortcode', 'advanced-iframe'), 'replace_iframe_tags', __('When you set this to Yes all existing iframes on your site will be replaced by the advanced iframe shortcode dynamically. So you get all the power of the plugin without exchanging all your old iframes. The iframes are parsed and the main attributes (src, width, height, id, name, class, style) are used in the shortcode + all settings you make in the administration. Be aware that they all get the same additional settings! So only use this if this works for you. You can of course if you need only some iframes to be different replace them manually. If you have several iframes on the same page make sure that if they have an id, the id is unique.', 'advanced-iframe'), false); 
	  echo '</table>';
      aiPostboxClose();	
 
// Technical   
      aiPostboxOpen("id-options-technical", "Technical options", $closedArray);  
      echo '<table class="form-table hide-print">';   
      printTrueFalse(false,$devOptions, __('Include ai.js in the footer', 'advanced-iframe'), 'include_scripts_in_footer', __('If you include ai.js to the footer you can include jQuery also at the footer. This can cause errors that needed Javascript is not found!!! This can be the case if the iframe loads very fast and the needed callbacks are not available. Please check the browser console (F12). If you see errors that advanced iframe methods are not found: Set this to false. The ai.js has also to be in the footer if it should only be loaded when the shortcode is on the page. This setting cannot be set as shortcode! There is an additional shortcode attribute called include_scripts_in_content="true". This is only needed in the special case if you use the page with content only (like using the plugin "Show Content Only" with "Content + Styles" mode). Then ai.js is directly rendered before the iframe. See demo <a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/same-domain-wrapped-auto-height">wrapped auto height</a>.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Load jQuery as dependency', 'advanced-iframe'), 'load_jquery', __('By default jQuery is loaded as dependency. If you have a theme or another plugin that does not stick to the Wordpress way to load the scripts you might have to disable the dependency. This avoids that jQuery is loaded again and other plugins do maybe not work anymore.', 'advanced-iframe'), true);
	  printTrueFalse(false,$devOptions, __('Fix WordPress 5.5 page parameter change', 'advanced-iframe'), 'remove_page_param_from_query', __('In Wordpress 5.5 the page parameter returns with a 301 redirect if not used the WordPress way. Setting this to true does allow to still use the parameter page in the url with non numerical characters. The recommended way is to not use page as parameter in the url at all. ', 'advanced-iframe'), 'false');
	  printTrueFalse(false,$devOptions, __('Prevent early shortcode execution', 'advanced-iframe'), 'optimize_rendering', __('Some plugins like older version of all-in-one-seo do evaluate shortcodes for internal use. This slows down the page. Advanced iframe was detecting this and prevented this rendering. Till the theme Twenty Twenty-One this worked fine out of the box. Unfortunately in the theme Twenty Twenty-Two the iframe shortcode is evaluated before the HTML outputs starts. And then no iframe is rendered at all. Because of this and as all-in-one-seo also fixed this on their side, this optimization is disabled by default! The iframe is then rendered in all situations. Set this setting to "Yes" and <strong>test if the iframe is still shown</strong>. You can check if you need this setting:<span>Set this setting to Yes.</span><span>Enable on the options page "Debug Javascript".</span><span>Go to a page with an iframe and view the source code.</span><span>If you see &lt;!-- advanced iframe not rendered --&gt; in the HTML <strong>AND your iframe is still shown</strong> then you should use the optimization if possible. Otherwise you can use "No" here without any disadvantage. Enabling the debug will also show the rendering time of advanced iframe like this: &lt;!-- advanced iframe rendered in 0.000563 s --&gt;</span>', 'advanced-iframe'), 'false');
	  printTrueFalse(false,$devOptions, __('Enable temporary ai content pages', 'advanced-iframe'), 'enable_ai_content_pages', __('This is an advanced feature where the content of your page is not shown directly but a dynamic pages is created. See the demo <a target="_blank" href="//www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/show-content-in-iframe">Display content in iframe</a> how this us used and configured. Please note that you need to reload your page once after the save because Wordpress executes the needed hooks in the wrong order.', 'advanced-iframe'), 'false', '//www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0/show-content-in-iframe');
      echo '</table>';
      aiPostboxClose();
	   
 // Security/delete
	  aiPostboxOpen("id-options-delete", "Security/Delete options", $closedArray);  
      echo '<table class="form-table hide-print">'; 
	  printTrueFalse(false,$devOptions, __('Allow shortcode attributes', 'advanced-iframe'), 'shortcode_attributes', __('Allow to set attributes in the shortcode. All of the attributes can be overwritten in the shortcode if you set \'Yes\'. Otherwise the settings you specify here are used.', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Use shortcode attributes only', 'advanced-iframe'), 'use_shortcode_attributes_only', __('All iframes you use in your pages use the administration. With shortcode attributes you can overwrite these settings. When you use several iframes with different settings this can lead to strange unexpected because you do not see the whole configuration in the shortcode. By setting this option to true only the parameters defined as attributes are used. You can set this for a single iframe as well with the shortcode attribute use_shortcode_attributes_only="true". Shortcode attribute: use_shortcode_attributes_only="true" or use_shortcode_attributes_only="false"', 'advanced-iframe'));
      printTrueFalse(false,$devOptions, __('Remove data when plugin is deleted', 'advanced-iframe'), 'delete_options_db', __('You can remove also all settings in the database when deleting the plugin. By default this is not enabled because updating the pro version is done by deleting the old version and installing the new version again. So please only set this setting to true if you completely want to remove the plugin. Also delete the "Advanced iFrame custom folder" plugin after you have deleted the main plugin.', 'advanced-iframe'), false);  

	  echo '</table>';
      aiPostboxClose();
      
   //echo '<p class="button-submit">
   //   <input class="button-primary" type="submit" name="update_iframe-loader" value="';
   //   _e('Update Settings', 'advanced-iframe');
   //echo '"/>
   // </p>';
}
}
?>