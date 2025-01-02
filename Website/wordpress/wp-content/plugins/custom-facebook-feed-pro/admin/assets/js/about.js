var extensions_data = {
    genericText: cff_about.genericText,
    links: cff_about.links,
    extentions_bundle: cff_about.extentions_bundle,
    supportPageUrl: cff_about.supportPageUrl,
    plugins: cff_about.pluginsInfo,
    stickyWidget: false,
    socialWallActivated: cff_about.socialWallActivated,
    socialWallLinks: cff_about.socialWallLinks,
    recommendedPlugins: cff_about.recommendedPlugins,
    social_wall: cff_about.social_wall,
    aboutBox: cff_about.aboutBox,
    ajax_handler: cff_about.ajax_handler,
    nonce: cff_about.nonce,
    buttons: cff_about.buttons,
    icons: cff_about.icons,
    btnClicked: null,
    btnStatus: null,
    btnName: null,
    recheckLicenseStatus: null,
    licenseKey: cff_about.licenseKey,
    cffLicenseNoticeActive: (cff_about.cffLicenseNoticeActive === '1'),
    cffLicenseInactiveState: (cff_about.cffLicenseInactiveState === '1'),
    svgIcons: cff_about.svgIcons,
    licenseBtnClicked: false,
    viewsActive : {
        whyRenewLicense : false,
        licenseLearnMore : false,
    },
    notificationElement : {
        type : 'success', // success, error, warning, message
        text : '',
        shown : null
    },
}

var cffAbout = new Vue({
    el: "#cff-about",
    http: {
        emulateJSON: true,
        emulateHTTP: true
    },
    data: extensions_data,
    methods: {
        activatePlugin: function( plugin, name, index, type ) {
            this.btnClicked = index + 1;
            this.btnStatus = 'loading';
            this.btnName = name;

            let data = new FormData();
            data.append( 'action', 'cff_activate_addon' );
            data.append( 'nonce', this.nonce );
            data.append( 'plugin', plugin );
            data.append( 'type', 'plugin' );
            if ( this.extentions_bundle && type == 'extension' ) {
                data.append( 'extensions_bundle', this.extentions_bundle );
            }
            fetch(this.ajax_handler, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if( data.success == true ) {
                    if ( name === 'social_wall' ) {
                        this.social_wall.activated = true;
                    } else if ( type === 'recommended_plugin' ) {
                        this.recommendedPlugins[name].activated = true;
                    } else {
                        this.plugins[name].activated = true;
                    }
                    this.btnClicked = null;
                    this.btnStatus = null;
                    this.btnName = null;
                }
            });
        },
        deactivatePlugin: function( plugin, name, index, type  ) {
            this.btnClicked = index + 1;
            this.btnStatus = 'loading';
            this.btnName = name;
            
            let data = new FormData();
            data.append( 'action', 'cff_deactivate_addon' );
            data.append( 'nonce', this.nonce );
            data.append( 'plugin', plugin );
            data.append( 'type', 'plugin' );
            if ( this.extentions_bundle && type == 'extension' ) {
                data.append( 'extensions_bundle', this.extentions_bundle );
            }
            fetch(this.ajax_handler, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if( data.success == true ) {
                    if ( name === 'social_wall' ) {
                        this.social_wall.activated = false;
                    } else if ( type === 'recommended_plugin' ) {
                        this.recommendedPlugins[name].activated = false;
                    } else {
                        this.plugins[name].activated = false;
                    }
                    this.btnClicked = null;
                    this.btnName = null;
                    this.btnStatus = null;
                }
                return;
            });
        },
        installPlugin: function( plugin, name, index, type ) {
            this.btnClicked = index + 1;
            this.btnStatus = 'loading';
            this.btnName = name;

            let data = new FormData();
            data.append( 'action', 'cff_install_addon' );
            data.append( 'nonce', this.nonce );
            data.append( 'plugin', plugin );
            data.append( 'type', 'plugin' );
            fetch(this.ajax_handler, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if( data.success == true ) {
                    if ( type === 'recommended_plugin' ) {
                        this.recommendedPlugins[name].installed = true;
                        this.recommendedPlugins[name].activated = true;
                    } else {
                        this.plugins[name].installed = true;
                        this.plugins[name].activated = true;
                    }
                    this.btnClicked = null;
                    this.btnName = null;
                    this.btnStatus = null;
                }
                return;
            });
        },
        buttonIcon: function() {
            if ( this.btnStatus == 'loading' ) {
                return this.icons.loaderSVG
            }
        },

        activateLicense: function() {
            var self = this;
            self.licenseBtnClicked = true;

            let data = new FormData();
            data.append( 'action', 'cff_activate_license' );
            data.append( 'license_key', self.licenseKey );
            data.append( 'nonce', self.nonce );
            fetch(self.ajax_handler, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(data => {
				self.licenseBtnClicked = false;

				if(data && data.success == false) {
					self.processNotification("licenseError");
					return;
				}
				if( data !== false ){
					self.processNotification("licenseActivated");

                    // Remove the license notices
                    jQuery('#sby-license-inactive-agp').remove();
                    self.activateView('licenseLearnMore');

					jQuery('.cff_get_pro_highlight, .cff_get_sbr, .cff_get_sbi, .cff_get_yt, .cff_get_ctf').closest('li').remove();
				}
                return;
            });
        },
        /**
         * Activate View
         *
         * @since 4.0
        */
         activateView : function(viewName){
             var self = this;
            self.viewsActive[viewName] = (self.viewsActive[viewName] == false ) ? true : false;
        },

        recheckLicense: function( optionName = null ) {
            this.recheckLicenseStatus = 'loading';
			let licenseNoticeWrapper = document.querySelector('.sb-license-notice');

            let data = new FormData();
            data.append( 'action', 'cff_recheck_connection' );
            data.append( 'license_key', this.licenseKey );
            data.append( 'nonce', this.nonce );
            fetch(this.ajax_handler, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if ( data.success == true ) {
                    if ( data.data.license == 'valid' ) {
                        this.recheckLicenseStatus = 'success';
                    }
                    if ( data.data.license != 'valid' ) {
                        this.recheckLicenseStatus = 'error';
                    }

                    setTimeout(function() {
                        self.recheckLicenseStatus = null;
                        if ( data.data.license == 'valid' ) {
                            licenseNoticeWrapper.remove();
                        }
                    }.bind(this), 3000);
                }
                return;
            });
        },
        recheckBtnText: function( btnName ) {
            if ( this.recheckLicenseStatus == null ) {
                return this.genericText.recheckLicense;
            } else if ( this.recheckLicenseStatus == 'loading' ) {
                return this.svgIcons.loaderSVG;
            } else if ( this.recheckLicenseStatus == 'success' ) {
                return this.svgIcons.checkmark + ' ' + this.genericText.licenseValid;
            } else if ( this.recheckLicenseStatus == 'error' ) {
                return this.svgIcons.times2SVG + ' ' + this.genericText.licenseExpired;
            }
        },

		/**
		 * Loading Bar & Notification
		 *
		 * @since 4.0
		 */
         processNotification : function( notificationType ){
			var self = this,
				notification = self.genericText.notification[ notificationType ];
			self.loadingBar = false;
			self.notificationElement =  {
				type : notification.type,
				text : notification.text,
				shown : "shown"
			};
			setTimeout(function(){
				self.notificationElement.shown =  "hidden";
			}, 5000);
		},

        /**
         * Toggle Sticky Widget view
         * 
         * @since 4.0
         */
         toggleStickyWidget: function() {
            this.stickyWidget = !this.stickyWidget;
        },
    }
})