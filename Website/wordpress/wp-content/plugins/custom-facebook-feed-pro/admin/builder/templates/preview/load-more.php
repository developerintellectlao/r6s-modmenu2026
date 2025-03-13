<div v-if="sourcesList.length">
    <div id="cff-load-more-section" class="cff-preview-loadmore-ctn cff-fb-fs cff-preview-section" :data-dimmed="!isSectionHighLighted('loadMore')" v-if="valueIsEnabled(customizerFeedData.settings.loadmore) && customizerFeedData.settings.feedlayout != 'carousel' && customizerFeedData.settings.feedtype != 'singlealbum' && customizerFeedData.settings.feedtype != 'featuredpost'">
        <div class="cff-preview-loadmore-btn cff-fb-fs">
            <span class="cff-load-icon" v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme != 'default_theme'">
                <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="5.75" cy="9.75" r="1.25" fill="#141B38"/>
                    <circle cx="10.5" cy="9.75" r="1.25" fill="#141B38"/>
                    <circle cx="15.25" cy="9.75" r="1.25" fill="#141B38"/>
                </svg>
            </span>
            <span>{{customizerFeedData.settings.buttontext}}</span>
        </div>
    </div>
</div>