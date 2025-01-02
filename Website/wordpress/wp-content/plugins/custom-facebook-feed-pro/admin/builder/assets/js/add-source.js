var cffStorage = window.localStorage;
/**
 * Add Source Popup
 *
 * @since 4.0
 */
 Vue.component('sb-add-source-component', {
    name: 'sb-add-source-component',
    template: '#sb-add-source-component',
    props: [
    'genericText',
    'links',
    'svgIcons',
    'viewsActive',
    'selectSourceScreen',
    'selectedFeed',
    'parent'
    ],
    data: function() {
        return{
            sourcesList : cff_source.sources,
            //Add New Source
            newSourceData        : cff_source.newSourceData ? cff_source.newSourceData : null,
            sourceConnectionURLs : cff_source.sourceConnectionURLs,
            returnedApiSourcesList : [],
            manualSourcePopupInit : cff_source.manualSourcePopupInit,
            addNewSource : {
                typeSelected        : 'page',
                manualSourceID      : null,
                manualSourceToken   : null,
			    eventsManualSourceICal 	: null
            },
            selectedSourcesToConnect : [],
            loadingAjax : false
        }
    },
    computed : {

    },
    mounted : function(){
        var self = this;
        if(self.newSourceData != null){
            self.initAddSourceData();
        }
        if( self.manualSourcePopupInit != undefined && self.manualSourcePopupInit == true){
            self.viewsActive.sourcePopupScreen = 'step_3';
            self.viewsActive.sourcePopup = true;
        }
        self.processFBConnectSuccess();
    },
    methods : {
        /**
         * Return Page/Group Avatar
         *
         * @since 4.0
         *
         * @return string
         */
         returnGroupPageAvatar : function(source){
            var info = this.$parent.jsonParse(source.info);
            return (source.account_type == 'page') ? 'https://graph.facebook.com/'+source.account_id+'/picture' : (info.picture.data.url ? info.picture.data.url : '');
        },


        /**
         * Add Feed Source Manually
         *
         * @since 4.0
         */
        addSourceManually: function(isEventSource = false){
            var self = this,
            manualSourceData = {
                'action' : 'cff_source_builder_update',
                'type' : self.addNewSource.typeSelected,
                'id' : self.addNewSource.manualSourceID,
                'access_token' : self.addNewSource.manualSourceToken
            };
            if(isEventSource){
                manualSourceData.privilege = 'events';
                manualSourceData.iCalUrl = self.addNewSource.eventsManualSourceICal;
            }
            var alerts = document.querySelectorAll(".sb-alerts-wrap");
            if (alerts.length) {
                alerts[0].parentNode.removeChild(alerts[0]);
            }

            if(self.$parent.checkNotEmpty(self.addNewSource.manualSourceID) && self.$parent.checkNotEmpty(self.addNewSource.manualSourceToken) ){
                self.loadingAjax = true;
                self.$parent.ajaxPost(manualSourceData, function(_ref){
                    var data = _ref.data;
                    if (typeof data.success !== 'undefined' && data.success === false) {
                        //cff-fb-source-inputs cff-fb-fs
                        var inputs = document.querySelectorAll(".cff-fb-source-inputs")[0];

                        var div = document.createElement('div');
                        div.innerHTML = data.message;
                        while (div.children.length > 0) {
                            inputs.appendChild(div.children[0]);
                        }

                    } else {
                        self.addNewSource = {typeSelected : 'page', manualSourceID : null,manualSourceToken : null};
                        self.sourcesList = data?.sourcesList || [];
                        self.$parent.sourcesList = data?.sourcesList || [];
                        self.$parent.viewsActive.sourcePopup = false;
                        if(self.$parent.customizerFeedData){
                            self.$parent.activateView('sourcePopup', 'customizer');
                        }
                    }
                    self.loadingAjax = false;

                });
            }else{
                alert("Token or ID Empty")
            }
        },

        /**
         * Make sure something entered for manual connections
         *
         * @since 4.0
         */
        checkManualEmpty : function() {
            var self = this;
            return self.$parent.checkNotEmpty(self.addNewSource.manualSourceID) && self.$parent.checkNotEmpty(self.addNewSource.manualSourceToken);
        },

        /**
         * Init Add Source Action
         * Triggered when the connect button is returned
         *
         * @since 4.0
         */
         initAddSourceData : function(){
            var self = this;
            self.$parent.viewsActive.sourcePopup = true;
            self.$parent.viewsActive.sourcePopupScreen = 'step_2';
            if(self.newSourceData && !self.newSourceData.error){
                if(self.newSourceData.admin || self.newSourceData.member){
                    self.addNewSource.typeSelected = 'group';
                    self.newSourceData.admin.forEach(function(singleSource){
                        singleSource.admin = true;
                        self.returnedApiSourcesList.push(self.createSourceObject('group',singleSource));
                    });
                    self.newSourceData.member.forEach(function(singleSource){
                        //if(!self.checkObjectArrayElement(self.returnedApiSourcesList, self.newSourceData.admin, 'id')){
                            singleSource.admin = false;
                            self.returnedApiSourcesList.push(self.createSourceObject('group',singleSource));
                        //}
                    });
                }else{
                    self.newSourceData.pages.forEach(function(singleSource){
                        if(self.checkSourceAllLocation(self.newSourceData.pages, singleSource) && singleSource.location === undefined){
                            singleSource.location = 'All Locations';
                        }
                        self.returnedApiSourcesList.push(self.createSourceObject('page',singleSource));
                    });
                }
            }
        },

        checkSourceAllLocation : function( pages, source ){
            const sourcesList = pages.filter( singleSources => singleSources.name === source.name  );
            return sourcesList.length > 1;

        },
        /**
         * Create Single Source Object
         *
         * @since 4.0
         *
         * @return Object
         */
         createSourceObject : function(type,object){
            return {
                account_id : object.id,
                access_token : object.access_token,
                account_type : type,
                info : (type == 'group' ? JSON.stringify(object) : '{}'),
                admin : (type == 'group' ? object.admin  : ''),
                location : object?.location,
                username : object.name
            }
        },

        /**
         * Select Page/Group to Connect
         *
         * @since 4.0
         */
         selectSourcesToConnect : function(source){
            var self = this;

            if (typeof window.cffSelected === 'undefined') {
                window.cffSelected = [];
            }
            if(self.selectedSourcesToConnect.includes(source.account_id)){
                self.selectedSourcesToConnect.splice(self.selectedSourcesToConnect.indexOf(source.account_id), 1);
                window.cffSelected.splice(self.selectedSourcesToConnect.indexOf(source.admin), 1);
            }else{
                self.selectedSourcesToConnect.push(source.account_id);
                window.cffSelected.push(source.admin);
            }
        },

        /**
         * Select Page/Group to Connect
         *
         * @since 4.0
         */
         addSourcesOnConnect : function(){
            var self = this;
            if(self.selectedSourcesToConnect.length > 0){
                var sourcesListToAdd = [];
                self.selectedSourcesToConnect.forEach(function(accountID, index){
                    self.returnedApiSourcesList.forEach(function(source){
                        if(source.account_id === accountID)
                            sourcesListToAdd.push(source);
                    });
                });
                var connectSourceData = {
                    'action' : 'cff_source_builder_update_multiple',
                    'type' : self.addNewSource.typeSelected,
                    'sourcesList' : sourcesListToAdd
                };
                self.$parent.ajaxPost(connectSourceData, function(_ref){
                    var data = _ref.data;
                    self.sourcesList = data;
                    self.$parent.sourcesList = data;
                    self.$parent.viewsActive.sourcePopup = false;
                    if(self.$parent.customizerFeedData){
                        self.$parent.activateView('sourcePopup', 'customizer');
                    }
                });
            }
        },

        /**
         * Process Connect FB Button
         *
         * @since 4.0
         */
        processFBConnect : function(){
            var self = this,
            accountType = self.addNewSource.typeSelected,
            params = accountType === 'page' ? self.sourceConnectionURLs.page : self.sourceConnectionURLs.group,
            ifConnectURL = params.connect,
            screenType = (self.$parent.customizerFeedData != undefined) ? 'customizer'  : 'creationProcess',
            appendURL = ( screenType == 'customizer' ) ? self.sourceConnectionURLs.stateURL + ',feed_id='+ self.$parent.customizerFeedData.feed_info.id : self.sourceConnectionURLs.stateURL;
            if(screenType != 'customizer'){
                self.createLocalStorage(screenType);
            }

            if( self.$parent.isSetupPage === 'true'){
                appendURL = appendURL+ ',is_setup_page=yes';
            }

            const urlParams = {
                'wordpress_user' : params.wordpress_user,
                'v' : params.v,
                'vn' : params.vn,
                'cff_con' : params.cff_con,
                'has_group' : params.has_group,
                'state' : "{'{url=" + appendURL + "}'}"
            };

            if(params.sw_feed) {
                urlParams['sw-feed'] = 'true';
            }

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = ifConnectURL + (params.has_group === true ? '?has_group=true' : '');

            for (const param in urlParams) {
                if (urlParams.hasOwnProperty(param)) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = param;
                    input.value = urlParams[param];
                    form.appendChild(input);
                }
            }

            document.body.appendChild(form);
            form.submit();
        },

        submitConnectPostForm : function ( postData, connectURL  ) {
            const formDOM = document.createElement("form");

            for (const [key, value] of Object.entries(postData)) {
                var elemDOM = document.createElement("input");
                elemDOM.name = key;
                elemDOM.value = value
                formDOM.appendChild(elemDOM)
            }

            formDOM.method = "POST";
            formDOM.action = connectURL;
            document.body.appendChild(formDOM);
            console.log(connectURL)
            formDOM.submit()
        },

        /**
         * Browser Local Storage for FB Connect
         *
         * @since 4.0
         */
         createLocalStorage : function(screenType){
            var self = this;
            switch (screenType) {
                case 'creationProcess':
                    cffStorage.setItem('selectedFeed', self.$parent.selectedFeed);
                break;
                case 'customizer':
                    cffStorage.setItem( 'feed_id', self.$parent.customizerFeedData.feed_info.id );
                break;
            }
            cffStorage.setItem( 'FBConnect', 'true' );
            cffStorage.setItem( 'screenType', screenType );
        },


        /**
         * Process FB Connect Success
         *
         * @since 4.0
         */
         processFBConnectSuccess : function(){
            var self = this;
            if( cffStorage.FBConnect === 'true' && cffStorage.screenType ){
                if( cffStorage.screenType == 'creationProcess' && cffStorage.selectedFeed ){
                    self.$parent.selectedFeed = cffStorage.selectedFeed;
                    self.$parent.viewsActive.pageScreen = 'selectFeed';
                    self.$parent.viewsActive.selectedFeedSection = 'selectSource';
                }
                if( cffStorage.screenType == 'customizer' && cffStorage.feed_id){
                    var urlParams = new URLSearchParams(window.location.search);
                    urlParams.set('feed_id', cffStorage.feed_id);
                    window.location.search = urlParams;
                }
            }
            localStorage.removeItem("FBConnect");
            localStorage.removeItem("screenType");
            localStorage.removeItem("selectedFeed");
            localStorage.removeItem("feed_id");
        },

        groupNext : function() {
             console.log('next');
        }
    }
});
