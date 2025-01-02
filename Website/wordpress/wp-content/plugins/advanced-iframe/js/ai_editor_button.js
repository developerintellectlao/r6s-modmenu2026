/**
 *  This file is used to initialize the editor button above the editor. 
 */
var securitykey = aiButtonSettings.securitykey;  
var additionalSettings = aiButtonSettings.additionalSettings;

jQuery(document).ready(function(){
	 jQuery(document.body).on('click', '.insert-iframe-button', function(e) {
		 e.preventDefault();
		 if (securitykey !== '') {
		   var shortcode_tag = "[advanced_iframe securitykey=\"" + securitykey +  "\""  + additionalSettings + "]";
		 } else {
		   var shortcode_tag = "[advanced_iframe" + additionalSettings + "]";
		 }
		 var activeEditor = jQuery(this).data('editor');
		 var editor = (typeof tinyMCE !== 'undefined') ? tinyMCE.get(activeEditor) : false;
			if (editor) {
				editor.execCommand('mceInsertContent', false, shortcode_tag);
			} else {
				wp.media.editor.insert(shortcode_tag);
			}
		 return false;
	 });
});
