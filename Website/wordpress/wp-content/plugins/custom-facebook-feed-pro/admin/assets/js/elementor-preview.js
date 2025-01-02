'use strict';


var CustomFacebookFeedElementor = window.CustomFacebookFeedElementor || ( function( document, window, $ ) {

	var vars = {};

	var app = {

		init: function() {
			app.events();
		},

		events: function() {

			$( window ).on('elementor/frontend/init', function ( $scope ) {

				elementorFrontend.hooks.addAction('frontend/element_ready/cff-widget.default', app.frontendWidgetInit);
				if( 'undefined' !== typeof elementor ){
					elementor.hooks.addAction( 'panel/open_editor/widget/cff-widget', app.widgetPanelOpen );
				}

			});

		},

		CffInitWidget: function() {
			if( window?.parent?.window[0]?.cff_init ){
				window.parent.window[0].cff_init($(window.parent.window[0]).find('.cff'));
				if( jQuery('#cff.cff-lb').length && jQuery('#cff-lightbox-wrapper').length == 0) cffLightbox();
			}
		},

		registerWidgetEvents: function( $scope ) {
			$scope
				.on( 'change', '.sb-elementor-cta-feedselector', app.selectFeedInPreview );

		},

		frontendWidgetInit : function( $scope ){
			app.CffInitWidget();
			app.registerWidgetEvents( $scope );
		},

		findFeedSelector: function( event ) {

			vars.$select = event && event.$el ?
				event.$el.closest( '#elementor-controls' ).find( 'select[data-setting="feed_id"]' ) :
				window.parent.jQuery( '#elementor-controls select[data-setting="feed_id"]' );
		},


		selectFeedInPreview : function( event ){

			vars.feedId = $( this ).val();

			app.findFeedSelector();

			vars.$select.val( vars.feedId ).trigger( 'change' );

		},


		widgetPanelOpen: function( panel, model ) {
			panel.$el.find( '.elementor-control.elementor-control-feed_id' ).find( 'select' ).on( 'change', function(){
				setTimeout(function(){
					app.CffInitWidget();
				}, 4000)
			});
		},



	};

	return app;



}( document, window, jQuery ) );


CustomFacebookFeedElementor.init();