var support_data = {
    genericText: cff_support.genericText,
    articles: cff_support.articles,
    system_info: cff_support.system_info,
    system_info_n: cff_support.system_info_n,
    exportFeed: 'none',
    stickyWidget: false,
    feeds: cff_support.feeds,
    supportUrl: cff_support.supportUrl,
    socialWallActivated: cff_support.socialWallActivated,
    socialWallLinks: cff_support.socialWallLinks,
    siteSearchUrl: cff_support.siteSearchUrl,
    siteSearchUrlWithArgs: null,
    searchKeywords: null,
    buttons: cff_support.buttons,
    links: cff_support.links,
    supportPageUrl: cff_support.supportPageUrl,
    systemInfoBtnStatus: 'collapsed',
    copyBtnStatus: null,
    ajax_handler: cff_support.ajax_handler,
    nonce: cff_support.nonce,
    icons: cff_support.icons,
    images: cff_support.images,
    recheckLicenseStatus: null,
    notificationElement : {
        type : 'success', // success, error, warning, message
        text : '',
        shown : null
    },
    licenseKey: cff_support.licenseKey,
    cffLicenseNoticeActive: (cff_support.cffLicenseNoticeActive === '1'),
    cffLicenseInactiveState: (cff_support.cffLicenseInactiveState === '1'),
    svgIcons: cff_support.svgIcons,
    licenseBtnClicked: false,
    viewsActive : {
        whyRenewLicense : false,
        licenseLearnMore : false,
        tempLoginAboutPopup : false
    },
    //Tenmp User Account
    tempUser : cff_support.tempUser,
    createStatus : null,
    deleteStatus : null
}

var cffsupport = new Vue({
    el: "#cff-support",
    http: {
        emulateJSON: true,
        emulateHTTP: true
    },
    data: support_data,
    methods: {

        activateLicense: function() {
            var self = this;
            self.licenseBtnClicked = true;

            let data = new FormData();
            data.append( 'action', 'cff_activate_license' );
            data.append( 'license_key', self.licenseKey );
            data.append( 'nonce', self.nonce );
            fetch(cff_support.ajax_handler, {
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

        copySystemInfo: function() {
            let self = this;
            const el = document.createElement('textarea');
			el.className = 'cff-fb-cp-clpboard';
			el.value = self.system_info_n;
			document.body.appendChild(el);
			el.select();
			document.execCommand('copy');
			document.body.removeChild(el);
            this.notificationElement =  {
                type : 'success',
                text : this.genericText.copiedToClipboard,
                shown : "shown"
            };

            setTimeout(function() {
                this.notificationElement.shown =  "hidden";
            }.bind(self), 3000);
        },
        expandSystemInfo: function() {
            this.systemInfoBtnStatus = ( this.systemInfoBtnStatus == 'collapsed' ) ? 'expanded' : 'collapsed';
        },
        expandBtnArrow: function() {
            if ( this.systemInfoBtnStatus == 'collapsed' ) {
                return this.icons.downAngle;
            } else if ( this.systemInfoBtnStatus == 'expanded' ) {
                return this.icons.upAngle;
            }
        },
        expandBtnText: function() {
            if ( this.systemInfoBtnStatus == 'collapsed' ) {
                return this.buttons.expand;
            } else if ( this.systemInfoBtnStatus == 'expanded' ) {
                return this.buttons.collapse;
            }
        },
        exportFeedSettings: function() {
            // return if no feed is selected
            if ( this.exportFeed === 'none' ) {
                return;
            }

            let url = this.ajax_handler + '?action=cff_export_settings_json&feed_id=' + this.exportFeed + '&nonce=' + this.nonce;
            window.location = url;
        },
        searchDoc: function() {
            let self = this;
            let searchInput = document.getElementById('cff-search-doc-input');
            searchInput.addEventListener('keyup', function ( event ) {
                let url = new URL( self.siteSearchUrl );
                let search_params = url.searchParams;
                if ( self.searchKeywords ) {
                    search_params.set('search', self.searchKeywords);
                }
                search_params.set('plugin', 'facebook');
                url.search = search_params.toString();
                self.siteSearchUrlWithArgs = url.toString();

                if ( event.key === 'Enter' ) {
                    window.open( self.siteSearchUrlWithArgs, '_blank');
                }
            })
        },
        searchDocStrings: function() {
            let self = this;
            let url = new URL( this.siteSearchUrl );
            let search_params = url.searchParams;
            setTimeout(function() {
                search_params.set('search', self.searchKeywords);
                search_params.set('plugin', 'facebook');
                url.search = search_params.toString();
                self.siteSearchUrlWithArgs = url.toString();
            }, 10);
        },
        goToSearchDocumentation: function() {
            if ( this.searchKeywords !== null && this.siteSearchUrlWithArgs !== null ) {
                window.open( this.siteSearchUrlWithArgs, '_blank');
            }
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
         * Toggle Sticky Widget view
         *
         * @since 4.0
         */
         toggleStickyWidget: function() {
            this.stickyWidget = !this.stickyWidget;
        },
        /**
		 * Copy text to clipboard
		 *
		 * @since 4.0
		 */
         copyToClipBoard : function(value){
			var self = this;
			const el = document.createElement('textarea');
			el.className = 'cff-fb-cp-clpboard';
			el.value = value;
			document.body.appendChild(el);
			el.select();
			document.execCommand('copy');
			document.body.removeChild(el);
			self.notificationElement =  {
				type : 'success',
				text : this.genericText.copiedToClipboard,
				shown : "shown"
			};
			setTimeout(function(){
				self.notificationElement.shown =  "hidden";
			}, 3000);
		},
          /**
         * Create New Temp User
         *
         * @since 4.0
         */
        createTempUser: function() {
            const self = this;
            self.createStatus = 'loading';
            let data = new FormData();
            data.append( 'action', 'cff_create_temp_user' );
            data.append( 'nonce', cff_support.nonce );
            fetch(cff_support.ajax_handler, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                self.createStatus = null;
                if( data.success ){
                    self.tempUser = data.user;
                }
                self.notificationElement =  {
                    type : data.success === true ? 'success' : 'error',
                    text : data.message,
                    shown : "shown"
                };
                setTimeout(function(){
                    self.notificationElement.shown =  "hidden";
                }, 5000);
            });

        },

        /**
         * Delete Temp User
         *
         * @since 4.0
         */
        deleteTempUser: function() {
            const self = this;
            self.deleteStatus = 'loading';
            let data = new FormData();
            data.append( 'action', 'cff_delete_temp_user' );
            data.append( 'nonce', cff_support.nonce );
            data.append( 'userId', self.tempUser.id );
            fetch(cff_support.ajax_handler, {
                method: "POST",
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(data => {
                self.deleteStatus = null;
                if( data.success ){
                    self.tempUser = null;
                }
                self.notificationElement =  {
                    type : data.success === true ? 'success' : 'error',
                    text : data.message,
                    shown : "shown"
                };
                setTimeout(function(){
                    self.notificationElement.shown =  "hidden";
                }, 5000);
            });
        }

    },
})