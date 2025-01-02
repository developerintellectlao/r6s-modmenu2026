=== Advanced iFrame ===
Contributors: mdempfle
Donate link: http://www.tinywebgallery.com
Tags: iframe, embed, resize, shortcode, modify css
Requires at least: 3.3
Tested up to: 6.5.4
Stable tag: 2024.5
Requires PHP: 5.4
License: Envato market standard or extended license
License URI: https://themeforest.net/licenses/standard

Include content the way YOU like in an iframe that can hide and modify elements, does auto height, forward parameters and does many, many more...

== Description ==
> **[Advanced iFrame Pro](https://1.envato.market/b1vbg)** 
> **[Demo](http://www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0)**
> **[p3d](https://p3d.in/b/24) - 3d models pre-configured with advenced iframe: Get 50% discount with the coupon AIFRAME**

Include content the way YOU like in an iframe that can hide and modify elements, does auto height, forward parameters and does many, many more...

= Main features of advanced iframe =
By entering the shortcode '[advanced_iframe]' you can include any webpage to any page or article. 

Advanced iFrame now has out of the box support for embedded 3D models using the p3d 3D viewer. Go to https://p3d.in/b/24 and download a pre-configured plugin where the model does scale already nicely on all devices. Get started for free! If you need more storage or access to the Premium features of p3d.in, you can get a 50% discount on your first payment with the coupon AIFRAME on checkout.

The following cool features compared to a normal iframe are implemented:

- Hide areas of the layout to give the iframe more space (see screenshot) 
- Show only specific areas of the iframe when the iframe is on a same domain (The Pro version supports this on different domains) or include parts directly by jQuery
- Modify css styles in the parent and the iframe to e.g. change the width of the content area (see screen-shot)
- Forward parameters to the iframe 
- Resize the iframe to the content height or width on loading, AJAX or click 
- Responsive videos (moved from the pro to the the free version in v2022)
- Scroll the parent to the top when the iframe is loaded
- Hide the content until it is fully loaded 
- Add a css and js file to the parent page
- Security code: You can only insert the shortcode with a valid security code from the administration.
- Many additional cool features are available the pro version - see http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart 

In the free version you can enable the pro administration AND test many of the pro features in the preview!

Please note: Modification inside the iframe are only possible if you are on the same domain or use a workaround like described in the settings.

So please check first if the iframe page and the parent page are one the same domain. www.example.com and text.example.com are different domains! Please check in the documentation if you can use the feature you like

A free iframe checker is available at 
http://www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker.
This tool does check if a page is allowed to be included! 

All settings can be set with shortcode attributes as well. If you only use one iframe please use the settings in the administration because there each parameter is explained in detail and also the defaults are set there.

**[Quick overview of all advanced iframe attributes](http://wordpress.org/extend/plugins/advanced-iframe/other_notes/)**

= Limitations of the free version = 
The free version has no functional restrictions and is for personal and non-commercial sites. After 10.000 views/month the iframe is still working but below the iframe a small "powered by" notice with a link to the pro version is shown. If you hit this limit and you qualify for the free license please contact <a href="http://www.tinywebgallery.com/en/about.php" target="_blank">me</a> to get a version with a higher limit.

= Upgrading to Advanced IFrame Pro =
It's quick and painless to get Advanced iFrame Pro. Simply Get Advanced iFrame Pro on CodeCanyon.net (https://1.envato.market/mvGme) and install your new plugin! You can than use the plugin on commercial, business, and professional sites and blogs. You furthermore get:

* Show only specific areas of the iframe even when the iframe is on different domain
* Graphical content selector: http://examples.tinywebgallery.com/configurator/advanced-iframe-area-selector.html
* External workaround supports iframe modifications
* Widget support
* No view limit
* Hide areas of an iframe
* Browser detection 
* Change link targets
* Url forward parameter mapping.
* Zoom iframe content
* Accordion menu
* jQuery help
* Advanced lazy load 
* Standalone version - can be used in ANY php page!
* And much more...

You can find the comparison chart here: http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart
See the pro demo here: 
http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo

= Administration =  
* Go to Settings -> Advanced iFrame

=	Quick start guide =
The quickstart guide is also available as video: http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-video-tutorials 

To include a webpage to your page please check the following things first:

*	Check if your page page is allowed to be included http://www.tinywebgallery.com/blog/advanced-iframe/free-iframe-checker!
* Check if the iframe page and the parent page are one the same domain. www.example.com and text.example.com are different domains!
* Can you modify the page that should be included?

Most likely you have one of the following setups:

1.	iframe cannot be included:  You cannot include the content because the owner does not allow this. 
1.	iframe can be included and you are on a different domain: See the feature comparison chart: http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-comparison-chart and the feature overview http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-features-availability-overview. To resize the content to the height/width or modify css you need to modify the remote iframe page by adding one line of Javascript to enable the provided workaround.
1.  iframe can be included and you are on the same domain: All features of the plugin can be used. 

If you mix http and https read http://www.tinywebgallery.com/blog/iframe-do-not-mix-http-and-https. Parent https and iframe http does not work on all mayor browsers!

== Installation ==
There are 2 ways to install the Advanced iFrame

*Using the Wordpress Admin screen*

1. Click Plugins, Add New
1. Search for advanced iframe
1. Install and Activate it
1. Place '[advanced_iframe]' in the editor directly or click on the "Add advanced iframe" button above the editor
1. Configure your iframe at your dashboard side menu -> "Advanced iFrame pro". For adding several iframes please see the examples and the FAQ.

*Using FTP*

1. Upload the 'advanced-iframe' folder of the download zip to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place '[advanced_iframe]' in the editor directly or click on the "Add advanced iframe" button above the editor
1. Configure your iframe at your dashboard side menu -> "Advanced iFrame pro". For adding several iframes please see the examples and the FAQ.

== Other Notes ==
= Advanced iframe attributes =

Below you find all possible shortcode attributes. If you only use one iframe please use the settings in the administration because there each parameter is explained in detail and also the defaults are set there.

Setting an attribute does overwrite the setting in the administration. 

[advanced_iframe securitykey=""   src="" 
  id=""   name="" 
  width=""   height="" 
  marginwidth=""   marginheight=""
  scrolling=""   frameborder="" 
  class=""   style="" 
  content_id=""   content_styles="" 
  hide_elements=""   url_forward_parameter="" 
  onload=""   onload_resize="" 
  onload_scroll_top=""   onload_show_element_only="" 
  store_height_in_cookie=""   additional_height="" 
  additional_js=""   additional_css="" 
  iframe_content_id=""   iframe_content_styles="" 
  iframe_hide_elements=""  hide_page_until_loaded=""
  include_hide_page_until_loaded="" 
  include_url="" include_content=""  
  include_height=""  include_fade="" 
  onload_resize_width=""   resize_on_ajax=""  
  resize_on_ajax_jquery=""   resize_on_click="" 
  resize_on_click_elements=""   use_shortcode_attributes_only=""
  onload_resize_delay=""
  ]


== Screenshots ==
1. Comparison between normal iframe and advanced iframe wrapper. The red areas are modified by the advanced iframe to display the content better.
2. This image shows the difference with an url forward parameter. In the advanced iframe a sub album is shown while the normal iframe still shows the entry screen.
3. The basic admin screen to enable standard settings
4. The advanced admin screen to enable advanced settings like HTML and css changes
5. The advanced admin screen to enable Javascript scroll to top and autoresize resize
 
== Frequently Asked Questions ==
Find the latest FAQ here:
http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-faq/

= Demo =
See the pro demo here:
http://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-pro-demo

See the free demo here:
http://www.tinywebgallery.com/blog/advanced-iframe/demo-advanced-iframe-2-0

== Upgrade Notice ==
Use the Wordpress installer to update or simply overwrite all files from your previous installation.
If you have some radio elements empty after the update simply select the one you like and save again.

== Changelog ==
= 2024.5 = 
- New: Tested with Wordpress 6.5.4
- New: Description of ai_external.min.js was optimized.
- Fix: filterBasicXSS could cause a fatal php error with some configurations. 

= 2024.4 = 
- New: Tested with WordPress 6.5.3
- Fix: When using arrays in the parameters was causing an error. If this is now the case no optimization of placeholders are done anymore.
- Security fix: Added additional filters to some Javascript parameters to increase security.

= 2024.3 =
- Security fix: The filter attribute method now filters shortcode attributes which are parsed wrong by WordPress if the user does not have the unfiltered_html permission.
- Security fix: #x28 and #x29 are  filtered if the user does not have the unfiltered_html permission.

= 2024.2 =
- Security fix: The additional_js and additional_js_file_iframe attribute are now only allowed to be used if you have the permission "unfiltered_html", that you need in WordPress to use iframes. If you do not have this permission, during save the attributes are removed and an error message is shown.
- Fix: Show iframe as layer was not working properly in Firefox. The link was opening in a new tab. Using a different way to hide the iframe solves the problem. 
- Fix: Filtering all short code attributes failed because "The Plus Blocks for Block Editor" was adding their settings to the ai attributes. I will contact them, why they do such stupid things!
- Fix: Switching a theme could cause an error message when no content pages where existing. Now this is only executed if content pages do exist. 

= 2024.1 =
- Fix: add_iframe_url_as_param thrown an error because of the additional security filter. Now it works fine again. 

= 2024.0 =
- Security fix: The include_html attribute is now only allowed to be used if you have the permission "unfiltered_html", that you need in WordPress to use iframes. If you do not have this permission, during save the attribute is removed and an error message is shown.
- Security fix: All shortcode attributes have now input sanitation to avoid Stored Cross-Site Scripting at save if you do not have the permission "unfiltered_html"! This happens in the normal editor and also in the Gutenberg block! Please get the unfiltered_html permission if you get an error message while you want to use '();= or a space in attributes. This sanitation is very general and does not allow all possible things you can do with advanced iframe. As 99.9% of the users who add an iframe are editors or above this should affect almost no one directly and it makes the plugin more secure. 
- Security fix: " inside advanced iframe shortcode attributes is not allowed anymore to avoid XSS attacks.
- Security fix: Additional output filtering of short code attributes directly used in HTML or Javascript to avoid XSS attacks.  This is done for ALL roles!
- New: The scroll to top in the external workaround is now also supporting the "touched" event next to the "click" event. 
- New: The documentation was improved for scroll to top as the external workaround is also supporting "iframe" if "Scrolls the parent window/iframe to the top" is set to iframe.

For older changes please see: https://www.tinywebgallery.com/blog/advanced-iframe/advanced-iframe-history