(function ($) {
	'use strict';

    $(document).ready(function(){

        function wpcf_load_script(){
			var WPCP_AjaxInterval = setInterval(function () {
				if ( typeof sp_wpcp_ajax !== "undefined" && $('.wpcp-carousel-section:not(.wpcp-loaded)').length > 0 ) {
					if ($('.wpcp-carousel-section').hasClass('wpcp-ticker')) {
						$.getScript(sp_wpcp_ajax.script_path + '/bxslider-config.js');
					}
					if ($('.wpcp-carousel-section').find('a.wcp-light-box').length >= 1) {
						$.getScript(sp_wpcp_ajax.script_path + '/fancybox-config.js');
					}
					$.getScript(sp_wpcp_ajax.script_path + '/preloader.js');
					$.getScript(sp_wpcp_ajax.script_path + '/wp-carousel-pro-public.js');
					clearInterval(WPCP_AjaxInterval);
				};
			}, 1000);
			setTimeout(function () {
				clearInterval(WPCP_AjaxInterval);
			}, 12000);
        }
        
        // Load script for ajax loaded theme.
        $(window).on('popstate', function(event) {
            wpcf_load_script();
        });
        $(document).on('click','a',function(){
            wpcf_load_script();
        });

    });
})(jQuery);