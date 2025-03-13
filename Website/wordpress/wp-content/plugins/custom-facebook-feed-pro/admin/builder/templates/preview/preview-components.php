<script type="text/x-template" id="cff-post-author-component">
	<div class="cff-post-item-info-ctn cff-fb-fs" v-if="customizerFeedData.settings.feedtype != 'events'">
		<div class="cff-post-item-avatar" v-if="customizerFeedData.settings.include.includes('author')">
			<a v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'from.picture.data.url')" href="" target="_blank">
				<img :src="singlePost.from.picture.data.url">
			</a>
		</div>
		<div class="cff-post-item-info" v-if="customizerFeedData.settings.include.includes('author')">
			<div class="cff-post-item-info-top">
				<a v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'from.name')" class="cff-post-item-author-name" href="" target="_blank" v-html="singlePost.from.name"></a>
				<span class="cff-post-item-story" v-html="$parent.$parent.printStory(singlePost)"></span>
				<span class="cff-rating" v-if="customizerFeedData.settings.feedtype == 'reviews' && singlePost.rating != undefined">
					<span class="cff-star" v-for="singleRating in singlePost.rating" :key="singleRating">★</span>
					<span class="cff-rating-num" v-html="singlePost.rating"></span>
				</span>

				<a class="cff-post-item-action-txt" v-if="$parent.$parent.valueIsEnabled(customizerFeedData.settings.showfacebooklink) && customizerFeedData.settings.feedtheme == 'social_wall'" :href="'https://www.facebook.com/'+singlePost.id" target="_blank">
					<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M10 0.540039C4.5 0.540039 0 5.03004 0 10.56C0 15.56 3.66 19.71 8.44 20.46V13.46H5.9V10.56H8.44V8.35004C8.44 5.84004 9.93 4.46004 12.22 4.46004C13.31 4.46004 14.45 4.65004 14.45 4.65004V7.12004H13.19C11.95 7.12004 11.56 7.89004 11.56 8.68004V10.56H14.34L13.89 13.46H11.56V20.46C13.9164 20.0879 16.0622 18.8856 17.6099 17.0701C19.1576 15.2546 20.0054 12.9457 20 10.56C20 5.03004 15.5 0.540039 10 0.540039Z" fill="#006BFA"/>
					</svg>
				</a>
			</div>
			<div class="cff-post-item-info-bottom">
				<span class="cff-post-item-date" v-if="customizerFeedData.settings.include.includes('date')">
					<span class="cff-post-item-date-before" v-if="$parent.$parent.valueIsEnabled(customizerFeedData.settings.beforedateenabled)">{{customizerFeedData.settings.beforedate}}</span>
					<span v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'created_time')" v-html="$parent.$parent.printDate(singlePost.created_time)"></span>
					<span class="cff-post-item-date-after" v-if="$parent.$parent.valueIsEnabled(customizerFeedData.settings.afterdateenabled)">{{customizerFeedData.settings.afterdate}}</span>
				</span>
			</div>
		</div>
	</div>
</script>

<script type="text/x-template" id="cff-iframe-media-component">
	<div class="cff-post-item-media-ctn cff-fb-fs" v-if="postmedia">
		<div class="cff-post-item-iframe-ctn" :data-source="postmedia.site" v-if="checkIframePostDisplay(postmedia)">
			<div class="cff-post-item-iframe">
				<iframe :src="postmedia.url" height="200" frameborder="0" type="text/html" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
			</div>
			<cff-post-overlay-component :parent="$parent.$parent" v-if="postmedia.site == 'video'" :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-overlay-component>
		</div>
		<div class="cff-post-item-video-ctn" v-if="postmedia.type == 'video' && customizerFeedData.settings.include.includes('media')">
			<iframe :src="'https://www.facebook.com/v2.3/plugins/video.php?href='+postmedia.args.unshimmedUrl" height="200" type="text/html"></iframe>
			<cff-post-overlay-component :parent="$parent.$parent" :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-overlay-component>
		</div>
		<div class="cff-post-item-link-ctn" v-if="postmedia.type == 'link' && customizerFeedData.settings.include.includes('sharedlinks')" :data-linkbox="customizerFeedData.settings.disablelinkbox">
			<a href="" v-if="customizerFeedData.settings.include.includes('media')">
				<img :src="postmedia.args.poster">
			</a>
			<div class="cff-post-item-link-info cff-fb-fs">
				<a class="cff-post-item-link-a" :href="postmedia.args.unshimmedUrl" target="_blank" v-html="postmedia.args.title"></a>
				<div class="cff-post-item-link-small" v-html="postmedia.args.domain"></div>
				<div class="cff-post-item-link-description" v-if="customizerFeedData.settings.include.includes('desc')" v-html="(postmedia.args.description != null) ? (postmedia.args.description.substring(0, customizerFeedData.settings.desclength) + (postmedia.args.description.length > customizerFeedData.settings.desclength ? '...' : '')) : ''"></div>
			</div>
		</div>
		<div class="cff-post-item-text" v-if="postmedia.type == 'link' && !customizerFeedData.settings.include.includes('sharedlinks')" v-html="postmedia.args.title">
		</div>
	</div>
</script>


<script type="text/x-template" id="cff-post-media-component">
	<div class="cff-post-item-media-wrap cff-fb-fs" v-if="$parent.$parent.checkProcessPostImage(singlePost)">
		<div class="cff-post-item-media-album">
			<cff-post-overlay-component :parent="$parent.$parent" :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-overlay-component>
			<a href="#">
				<div class="cff-post-item-album-poster">
					<img class="cff-post-item-full-img" :src="$parent.$parent.processPostImageSrc(singlePost)">
				</div>
				<div class="cff-post-item-album-thumbs" v-if="customizerFeedData.settings.oneimage !== 'on' && customizerFeedData.settings.feedtype != 'events' && singlePost.attachments.data[0].subattachments" :data-length="singlePost.attachments.data[0].subattachments.data.slice(1, 4).length">
					<span class="cff-post-item-album-thumb" v-for="(subAttachment, subAttachmentIndex) in singlePost.attachments.data[0].subattachments.data.slice(1, 4)" :style="'background-image:url('+subAttachment.media.image.src+');'">
						<span class="cff-post-item-album-thumb-overlay" v-if="singlePost.attachments.data[0].subattachments.data.length >= 4 && subAttachmentIndex == 2" v-html="$parent.$parent.printAlbumImageNumberOverlay(singlePost.attachments.data[0].subattachments.data)"></span>
					</span>
				</div>
			</a>
		</div>
	</div>
</script>


<script type="text/x-template" id="cff-post-text-component">

</script>

<script type="text/x-template" id="cff-post-overlay-component">
	<div class="cff-post-overlay" v-if="!parent.valueIsEnabled(customizerFeedData.settings.disablelightbox)" @click.prevent.default="parent.getPostElementOverlay(singlePost)">

	</div>
</script>

<script type="text/x-template" id="cff-post-actionlinks-component">
	<div class="cff-post-item-action-link" v-if="customizerFeedData.settings.include.includes('link')">
		<span class="cff-post-item-share-link" v-if="parent.valueIsEnabled(customizerFeedData.settings.showsharelink)">
			<div class="cff-post-item-share-tooltip" v-show="parent.showedSocialShareTooltip == singlePost.id">
				<span v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme !== 'default_theme'" class="cff-tooltip-label">{{translatedText.translations.cff_facebook_share_text}}</span>
				<a v-for="(socialLink, socialName) in parent.socialShareLink" :href="socialLink + 'https://www.facebook.com/'+singlePost.id" :class="'cff-bghv-'+socialName" v-html="parent.svgIcons[socialName +'Share']" target="_blank"></a>
			</div>
			<span class="cff-post-item-action-txt" @click.prevent.default="parent.toggleSocialShareTooltip(singlePost.id)">
				<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme !== 'default_theme' && customizerFeedData.settings.feedtheme !== 'social_wall'" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10.8409 4.08819L7.47282 0.632655C7.17819 0.330783 6.71394 0.576121 6.71394 1.04622V2.90051C3.52663 2.94126 1 3.65312 1 7.01614C1 8.37385 1.78567 9.71906 2.65347 10.4217C2.92444 10.6412 3.31013 10.3657 3.21013 10.0101C2.31019 6.80611 3.85697 6.101 6.71394 6.0785V7.93378C6.71394 8.40435 7.17909 8.64864 7.47326 8.3466L10.8414 4.89106C11.053 4.69578 11.053 4.30572 10.8409 4.08819Z" stroke="#141B38" stroke-linecap="round"/>
				</svg>
				<svg v-if="customizerFeedData.settings.feedtheme == 'social_wall'" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
					<rect y="0.5" width="20" height="20" rx="10" fill="#8C8F9A"/>
					<circle cx="5.5" cy="10.5" r="1.5" fill="white"/>
					<circle cx="10" cy="10.5" r="1.5" fill="white"/>
					<circle cx="14.5" cy="10.5" r="1.5" fill="white"/>
				</svg>
				<span class="cff-post-item-action-txt cff-post-item-dot" v-if="(!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme') && parent.valueIsEnabled(customizerFeedData.settings.showfacebooklink) && parent.valueIsEnabled(customizerFeedData.settings.showsharelink)">&middot;</span>
				<span v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'" class="cff-post-item-action-txt" v-html="parent.checkNotEmpty(customizerFeedData.settings.sharelinktext ) ? customizerFeedData.settings.sharelinktext : translatedText.translations.cff_facebook_share_text"></span>
			</span>
		</span>
		<a class="cff-post-item-action-txt" v-if="parent.valueIsEnabled(customizerFeedData.settings.showfacebooklink) && customizerFeedData.settings.feedtheme !== 'social_wall'" :href="'https://www.facebook.com/'+singlePost.id" target="_blank">
			<svg
			v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme !== 'default_theme'"
			width="14"
			height="15"
			viewBox="0 0 14 15"
			fill="none"
			xmlns="http://www.w3.org/2000/svg">
				<path d="M7.00016 0.860352C3.3335 0.860352 0.333496 3.85369 0.333496 7.54035C0.333496 10.8737 2.7735 13.6404 5.96016 14.1404V9.47369H4.26683V7.54035H5.96016V6.06702C5.96016 4.39369 6.9535 3.47369 8.48016 3.47369C9.20683 3.47369 9.96683 3.60035 9.96683 3.60035V5.24702H9.12683C8.30016 5.24702 8.04016 5.76035 8.04016 6.28702V7.54035H9.8935L9.5935 9.47369H8.04016V14.1404C9.61112 13.8922 11.0416 13.0907 12.0734 11.8804C13.1053 10.6701 13.6704 9.13078 13.6668 7.54035C13.6668 3.85369 10.6668 0.860352 7.00016 0.860352Z" fill="#434960"/>
			</svg>
			<span
			v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme' || customizerFeedData.settings.feedtheme == 'outline' || customizerFeedData.settings.feedtheme == 'overlap'"
			v-html="parent.checkNotEmpty(customizerFeedData.settings.facebooklinktext ) ? customizerFeedData.settings.facebooklinktext : translatedText.translations.cff_facebook_link_text">
			</span>
		</a>
	</div>
</script>



<script type="text/x-template" id="cff-post-meta-component">
	<div class="cff-post-item-meta-wrap cff-fb-fs" v-if="customizerFeedData.settings.feedtype != 'events'">
		<div class="cff-post-item-meta-top cff-fb-fs" v-if="customizerFeedData.settings.feedtype != 'reviews'">
			<div class="cff-post-item-meta cff-post-item-meta-bg" v-if="customizerFeedData.settings.include.includes('social')" @click.prevent.default="$parent.$parent.toggleCommentSection(singlePost.id)"  :data-icon-theme="customizerFeedData.settings.iconstyle">
				<a class="cff-post-item-view-comment">
					<span class="cff-post-meta-item">
						<span class="cff-post-meta-item-icon cff-post-meta-item-icon-like">
							<svg v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'" viewBox="0 0 512 512"><path d="M496.656 285.683C506.583 272.809 512 256 512 235.468c-.001-37.674-32.073-72.571-72.727-72.571h-70.15c8.72-17.368 20.695-38.911 20.695-69.817C389.819 34.672 366.518 0 306.91 0c-29.995 0-41.126 37.918-46.829 67.228-3.407 17.511-6.626 34.052-16.525 43.951C219.986 134.75 184 192 162.382 203.625c-2.189.922-4.986 1.648-8.032 2.223C148.577 197.484 138.931 192 128 192H32c-17.673 0-32 14.327-32 32v256c0 17.673 14.327 32 32 32h96c17.673 0 32-14.327 32-32v-8.74c32.495 0 100.687 40.747 177.455 40.726 5.505.003 37.65.03 41.013 0 59.282.014 92.255-35.887 90.335-89.793 15.127-17.727 22.539-43.337 18.225-67.105 12.456-19.526 15.126-47.07 9.628-69.405zM32 480V224h96v256H32zm424.017-203.648C472 288 472 336 450.41 347.017c13.522 22.76 1.352 53.216-15.015 61.996 8.293 52.54-18.961 70.606-57.212 70.974-3.312.03-37.247 0-40.727 0-72.929 0-134.742-40.727-177.455-40.727V235.625c37.708 0 72.305-67.939 106.183-101.818 30.545-30.545 20.363-81.454 40.727-101.817 50.909 0 50.909 35.517 50.909 61.091 0 42.189-30.545 61.09-30.545 101.817h111.999c22.73 0 40.627 20.364 40.727 40.727.099 20.363-8.001 36.375-23.984 40.727zM104 432c0 13.255-10.745 24-24 24s-24-10.745-24-24 10.745-24 24-24 24 10.745 24 24z"></path></svg>
							<svg v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'" viewBox="0 0 512 512" class="cff-svg-bg"><path d="M104 224H24c-13.255 0-24 10.745-24 24v240c0 13.255 10.745 24 24 24h80c13.255 0 24-10.745 24-24V248c0-13.255-10.745-24-24-24zM64 472c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zM384 81.452c0 42.416-25.97 66.208-33.277 94.548h101.723c33.397 0 59.397 27.746 59.553 58.098.084 17.938-7.546 37.249-19.439 49.197l-.11.11c9.836 23.337 8.237 56.037-9.308 79.469 8.681 25.895-.069 57.704-16.382 74.757 4.298 17.598 2.244 32.575-6.148 44.632C440.202 511.587 389.616 512 346.839 512l-2.845-.001c-48.287-.017-87.806-17.598-119.56-31.725-15.957-7.099-36.821-15.887-52.651-16.178-6.54-.12-11.783-5.457-11.783-11.998v-213.77c0-3.2 1.282-6.271 3.558-8.521 39.614-39.144 56.648-80.587 89.117-113.111 14.804-14.832 20.188-37.236 25.393-58.902C282.515 39.293 291.817 0 312 0c24 0 72 8 72 81.452z"></path></svg>
							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme != 'default_theme'" width="47" height="19" viewBox="0 0 47 19" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_1136_12613)"><circle cx="38" cy="9.5" r="8" fill="#FFD15E"/>
								<path d="M32.6665 10.1654C36.1051 8.87587 39.8945 8.87587 43.3332 10.1654C43.3332 11.8243 42.4786 13.3661 41.0718 14.2454C38.9903 15.5463 36.9606 15.5158 34.9278 14.2454C33.5211 13.3661 32.6665 11.8243 32.6665 10.1654Z" fill="#C4C4C4"/>
								<path d="M32.6665 10.1654C36.1051 8.87587 39.8945 8.87587 43.3332 10.1654C43.3332 11.8243 42.4786 13.3661 41.0718 14.2454C38.9903 15.5463 36.9606 15.5158 34.9278 14.2454C33.5211 13.3661 32.6665 11.8243 32.6665 10.1654Z" fill="url(#paint0_linear_1136_12613)"/>
								<mask id="mask0_1136_12613" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="32" y="10" width="12" height="6">
								<mask id="path-5-inside-1_1136_12613" fill="white"><path d="M32.6665 10.167C36.1051 10.8775 39.8945 10.8775 43.3332 10.167C43.3332 11.8259 42.4786 13.3678 41.0718 14.247C38.9903 15.5479 36.9606 15.5175 34.9278 14.247C33.5211 13.3678 32.6665 11.8259 32.6665 10.167Z"/></mask>
									<path d="M32.6665 10.167C36.1051 10.8775 39.8945 10.8775 43.3332 10.167C43.3332 11.8259 42.4786 13.3678 41.0718 14.247C38.9903 15.5479 36.9606 15.5175 34.9278 14.247C33.5211 13.3678 32.6665 11.8259 32.6665 10.167Z" fill="#C4C4C4"/>
									<path d="M32.6665 10.167C36.1051 10.8775 39.8945 10.8775 43.3332 10.167C43.3332 11.8259 42.4786 13.3678 41.0718 14.247C38.9903 15.5479 36.9606 15.5175 34.9278 14.247C33.5211 13.3678 32.6665 11.8259 32.6665 10.167Z" fill="url(#paint1_linear_1136_12613)"/>
									<path d="M32.6665 10.167L34.4877 1.35317L23.6665 -0.882748V10.167H32.6665ZM43.3332 10.167H52.3332V-0.882748L41.512 1.35317L43.3332 10.167ZM34.9278 14.247L30.1578 21.879L30.1578 21.879L34.9278 14.247ZM41.0718 14.247L36.3019 6.61501L36.3018 6.61501L41.0718 14.247ZM30.8453 18.9808C35.4854 19.9396 40.5143 19.9396 45.1543 18.9808L41.512 1.35317C39.2748 1.81544 36.7249 1.81544 34.4877 1.35317L30.8453 18.9808ZM23.6665 10.167C23.6665 14.929 26.1197 19.3551 30.1578 21.879L39.6978 6.61501C40.9225 7.38044 41.6665 8.72278 41.6665 10.167H23.6665ZM34.3332 10.167C34.3332 8.72278 35.0772 7.38044 36.3019 6.61501L45.8418 21.879C49.88 19.3551 52.3332 14.929 52.3332 10.167H34.3332ZM36.3018 6.61501C36.4325 6.53336 37.0474 6.20773 38.0151 6.21134C38.975 6.21491 39.5778 6.53999 39.6978 6.615L30.1578 21.879C32.3106 23.2245 34.9446 24.2 37.948 24.2112C40.9591 24.2224 43.6297 23.2616 45.8418 21.879L36.3018 6.61501Z" fill="black" mask="url(#path-5-inside-1_1136_12613)"/>
								</mask>
								<g mask="url(#mask0_1136_12613)"><ellipse cx="37.9997" cy="15.8337" rx="4.66667" ry="3.66667" fill="#FE544F"/></g>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M33.1364 6.74759C32.9545 6.77599 32.8301 6.94647 32.8585 7.12836C32.8869 7.31025 33.0574 7.43467 33.2393 7.40627C33.555 7.35695 33.9131 7.39118 34.2773 7.45903C33.8614 7.6859 33.4604 7.93614 33.1697 8.13138C33.0169 8.23404 32.9762 8.44114 33.0789 8.59395C33.1816 8.74677 33.3887 8.78743 33.5415 8.68478C34.0155 8.36636 34.7247 8.23256 35.3622 8.11231L35.4029 8.10462C35.6997 8.04857 36.0101 7.98926 36.2179 7.89724C36.3164 7.85361 36.4661 7.77219 36.545 7.61401C36.5901 7.52369 36.6009 7.43475 36.5911 7.35265C36.5936 7.30686 36.5906 7.26233 36.5842 7.22122C36.556 7.04118 36.4466 6.91481 36.3317 6.83397C36.2212 6.75627 36.0895 6.70782 35.9629 6.67525C35.7084 6.60978 35.3875 6.5872 35.0622 6.58599C34.4053 6.58354 33.6402 6.66891 33.1364 6.74759Z" fill="black"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M33.1364 6.74759C32.9545 6.77599 32.8301 6.94647 32.8585 7.12836C32.8869 7.31025 33.0574 7.43467 33.2393 7.40627C33.555 7.35695 33.9131 7.39118 34.2773 7.45903C33.8614 7.6859 33.4604 7.93614 33.1697 8.13138C33.0169 8.23404 32.9762 8.44114 33.0789 8.59395C33.1816 8.74677 33.3887 8.78743 33.5415 8.68478C34.0155 8.36636 34.7247 8.23256 35.3622 8.11231L35.4029 8.10462C35.6997 8.04857 36.0101 7.98926 36.2179 7.89724C36.3164 7.85361 36.4661 7.77219 36.545 7.61401C36.5901 7.52369 36.6009 7.43475 36.5911 7.35265C36.5936 7.30686 36.5906 7.26233 36.5842 7.22122C36.556 7.04118 36.4466 6.91481 36.3317 6.83397C36.2212 6.75627 36.0895 6.70782 35.9629 6.67525C35.7084 6.60978 35.3875 6.5872 35.0622 6.58599C34.4053 6.58354 33.6402 6.66891 33.1364 6.74759Z" fill="url(#paint2_linear_1136_12613)"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M42.6707 6.69091C42.9405 6.74989 43.1113 7.01639 43.0524 7.28616C42.9934 7.55593 42.7269 7.72681 42.4571 7.66784C42.3026 7.63407 42.1345 7.62161 41.9575 7.62406C42.2076 7.79359 42.4368 7.95991 42.6198 8.09959C42.8393 8.2671 42.8815 8.58087 42.714 8.8004C42.5465 9.01992 42.2327 9.06209 42.0132 8.89458C41.5868 8.56921 40.9145 8.39978 40.2691 8.2371L40.2691 8.2371L40.2505 8.23241L40.2458 8.23123C39.9614 8.15956 39.6354 8.07739 39.4137 7.96275C39.31 7.90915 39.1205 7.79609 39.0274 7.57759C38.9753 7.45526 38.9671 7.33829 38.984 7.23296C38.9854 7.17932 38.9923 7.1286 39.0023 7.08315C39.0535 6.8487 39.2071 6.69554 39.353 6.60556C39.4919 6.51986 39.6482 6.47439 39.7864 6.44759C40.0648 6.39362 40.4028 6.39134 40.732 6.40998C41.3999 6.44781 42.1678 6.58098 42.6707 6.69091Z" fill="black"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M42.6707 6.69091C42.9405 6.74989 43.1113 7.01639 43.0524 7.28616C42.9934 7.55593 42.7269 7.72681 42.4571 7.66784C42.3026 7.63407 42.1345 7.62161 41.9575 7.62406C42.2076 7.79359 42.4368 7.95991 42.6198 8.09959C42.8393 8.2671 42.8815 8.58087 42.714 8.8004C42.5465 9.01992 42.2327 9.06209 42.0132 8.89458C41.5868 8.56921 40.9145 8.39978 40.2691 8.2371L40.2691 8.2371L40.2505 8.23241L40.2458 8.23123C39.9614 8.15956 39.6354 8.07739 39.4137 7.96275C39.31 7.90915 39.1205 7.79609 39.0274 7.57759C38.9753 7.45526 38.9671 7.33829 38.984 7.23296C38.9854 7.17932 38.9923 7.1286 39.0023 7.08315C39.0535 6.8487 39.2071 6.69554 39.353 6.60556C39.4919 6.51986 39.6482 6.47439 39.7864 6.44759C40.0648 6.39362 40.4028 6.39134 40.732 6.40998C41.3999 6.44781 42.1678 6.58098 42.6707 6.69091Z" fill="url(#paint3_linear_1136_12613)"/>
									<g filter="url(#filter0_f_1136_12613)"><ellipse cx="38.6667" cy="20.5" rx="18.6667" ry="10" fill="url(#paint4_linear_1136_12613)"/></g>
								</g>
								<rect x="29.5" y="1" width="17" height="17" rx="8.5" stroke="white"/>
								<g clip-path="url(#clip1_1136_12613)"><circle cx="23" cy="9.5" r="8" fill="#FE544F"/>
									<g filter="url(#filter1_f_1136_12613)"><circle cx="23.3332" cy="29.8337" r="17.6667" fill="#C93030"/></g>
									<path d="M27.3694 7.01944C26.9791 6.21845 25.8548 5.56309 24.547 5.94465C23.9221 6.12516 23.3769 6.51227 23.0004 7.04274C22.6239 6.51227 22.0787 6.12516 21.4537 5.94465C20.143 5.56891 19.0216 6.21845 18.6313 7.01944C18.0838 8.14083 18.2812 9.42425 19.3071 10.7681C20.0591 11.7531 21.8474 13.3634 22.6273 14.0495C22.8427 14.2389 23.1616 14.239 23.3773 14.05C24.1756 13.3507 26.0235 11.6939 26.6966 10.7681C27.6898 9.40202 27.917 8.14083 27.3694 7.01944Z" fill="white"/>
								</g>
								<rect x="14.5" y="1" width="17" height="17" rx="8.5" stroke="white"/>
								<g clip-path="url(#clip2_1136_12613)"><circle cx="8" cy="9.5" r="8" fill="#1B90EF"/>
									<g filter="url(#filter2_f_1136_12613)"><circle cx="8.33317" cy="29.8337" r="17.6667" fill="#1066AC"/></g>
									<path d="M3.3335 8.80933C3.3335 8.54087 3.55112 8.32324 3.81958 8.32324H5.18061C5.44907 8.32324 5.6667 8.54087 5.6667 8.80933V12.5036C5.6667 12.772 5.44907 12.9896 5.18061 12.9896H3.81958C3.55112 12.9896 3.3335 12.772 3.3335 12.5036V8.80933Z" fill="white"/>
									<path d="M9.41161 7.93299V6.37753C9.41161 6.06813 9.27767 5.7714 9.03926 5.55262C8.92262 5.44559 8.78565 5.3615 8.63667 5.30352C8.34727 5.1909 8.05436 5.38845 7.919 5.66794L6.60734 8.37617C6.50071 8.59634 6.44531 8.8378 6.44531 9.08243V12.0161C6.44531 12.553 6.88057 12.9882 7.41748 12.9882H11.2253C11.4297 12.9903 11.628 12.9246 11.7838 12.8031C11.9395 12.6816 12.0422 12.5126 12.0728 12.3272L12.6576 8.82737C12.676 8.71591 12.6678 8.6021 12.6336 8.49383C12.5993 8.38556 12.5398 8.28541 12.4592 8.20034C12.3786 8.11526 12.2788 8.04729 12.1667 8.00112C12.0546 7.95496 11.9329 7.93171 11.8101 7.93299H9.41161Z" fill="white"/>
								</g>
								<rect x="-0.5" y="1" width="17" height="17" rx="8.5" stroke="white"/>
								<defs>
								<filter id="filter0_f_1136_12613" x="10" y="0.5" width="57.3335" height="40" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
								<feFlood flood-opacity="0" result="BackgroundImageFix"/>
								<feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
								<feGaussianBlur stdDeviation="5" result="effect1_foregroundBlur_1136_12613"/>
								</filter>
								<filter id="filter1_f_1136_12613" x="-14.8496" y="-8.34914" width="76.3658" height="76.3653" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
								<feFlood flood-opacity="0" result="BackgroundImageFix"/>
								<feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
								<feGaussianBlur stdDeviation="10.2581" result="effect1_foregroundBlur_1136_12613"/>
								</filter>
								<filter id="filter2_f_1136_12613" x="-29.8496" y="-8.34914" width="76.3658" height="76.3653" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
								<feFlood flood-opacity="0" result="BackgroundImageFix"/>
								<feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
								<feGaussianBlur stdDeviation="10.2581" result="effect1_foregroundBlur_1136_12613"/>
								</filter>
								<linearGradient id="paint0_linear_1136_12613" x1="37.9998" y1="8.16569" x2="37.9998" y2="15.4757" gradientUnits="userSpaceOnUse">
								<stop stop-color="#550D0D"/>
								<stop offset="1" stop-color="#952D2D"/>
								</linearGradient>
								<linearGradient id="paint1_linear_1136_12613" x1="37.9998" y1="8.16699" x2="37.9998" y2="15.477" gradientUnits="userSpaceOnUse">
								<stop stop-color="#550D0D"/>
								<stop offset="1" stop-color="#952D2D"/>
								</linearGradient>
								<linearGradient id="paint2_linear_1136_12613" x1="34.7242" y1="5.73149" x2="34.7242" y2="8.85452" gradientUnits="userSpaceOnUse">
								<stop stop-color="#550D0D"/>
								<stop offset="1" stop-color="#952D2D"/>
								</linearGradient>
								<linearGradient id="paint3_linear_1136_12613" x1="41.02" y1="5.36946" x2="41.02" y2="9.13395" gradientUnits="userSpaceOnUse">
								<stop stop-color="#550D0D"/>
								<stop offset="1" stop-color="#952D2D"/>
								</linearGradient>
								<linearGradient id="paint4_linear_1136_12613" x1="38.6667" y1="10.5" x2="38.6667" y2="30.5" gradientUnits="userSpaceOnUse">
								<stop stop-color="#FF0000" stop-opacity="0"/>
								<stop offset="1" stop-color="#D50000"/>
								</linearGradient>
								<clipPath id="clip0_1136_12613">
								<rect x="30" y="1.5" width="16" height="16" rx="8" fill="white"/>
								</clipPath>
								<clipPath id="clip1_1136_12613">
								<rect x="15" y="1.5" width="16" height="16" rx="8" fill="white"/>
								</clipPath>
								<clipPath id="clip2_1136_12613">
								<rect y="1.5" width="16" height="16" rx="8" fill="white"/>
								</clipPath>
								</defs>
							</svg>
						</span>
						<span class="cff-post-meta-item-text cff-post-meta-txt" v-html="$parent.$parent.hasOwnNestedProperty(singlePost, 'likes.summary.total_count') ? singlePost.likes.summary.total_count : '0'"></span>
					</span>
					<span class="cff-post-meta-item">
						<span class="cff-post-meta-item-icon cff-post-meta-item-icon-comment">
							<svg v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'" viewBox="0 0 512 512"><path d="M448 0H64C28.7 0 0 28.7 0 64v288c0 35.3 28.7 64 64 64h96v84c0 7.1 5.8 12 12 12 2.4 0 4.9-.7 7.1-2.4L304 416h144c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64zm32 352c0 17.6-14.4 32-32 32H293.3l-8.5 6.4L192 460v-76H64c-17.6 0-32-14.4-32-32V64c0-17.6 14.4-32 32-32h384c17.6 0 32 14.4 32 32v288z"></path></svg>
							<svg v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'" viewBox="0 0 512 512" class="cff-svg-bg"><path d="M448 0H64C28.7 0 0 28.7 0 64v288c0 35.3 28.7 64 64 64h96v84c0 9.8 11.2 15.5 19.1 9.7L304 416h144c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64z"></path></svg>

							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'modern'" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1.99985 8.50225L2.44707 8.72585C2.53183 8.55633 2.51345 8.35346 2.39961 8.20193L1.99985 8.50225ZM1 10.502L0.552786 10.2783C0.475289 10.4333 0.483571 10.6174 0.574675 10.7648C0.665778 10.9122 0.826711 11.002 1 11.002V10.502ZM10.5 5.50195C10.5 7.98723 8.48528 10.002 6 10.002V11.002C9.03757 11.002 11.5 8.53952 11.5 5.50195H10.5ZM6 1.00195C8.48528 1.00195 10.5 3.01667 10.5 5.50195H11.5C11.5 2.46439 9.03757 0.00195312 6 0.00195312V1.00195ZM1.5 5.50195C1.5 3.01667 3.51472 1.00195 6 1.00195V0.00195312C2.96243 0.00195312 0.5 2.46439 0.5 5.50195H1.5ZM2.39961 8.20193C1.83461 7.44984 1.5 6.51569 1.5 5.50195H0.5C0.5 6.73969 0.909453 7.88324 1.60009 8.80256L2.39961 8.20193ZM1.44721 10.7256L2.44707 8.72585L1.55264 8.27864L0.552786 10.2783L1.44721 10.7256ZM6 10.002H1V11.002H6V10.002Z" fill="#434960"/>
							</svg>

							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'social_wall'" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect y="0.5" width="20" height="20" rx="10" fill="#0096CC"></rect>
								<path d="M6.00332 16.0879C6.61281 16.0879 8.07659 15.4435 8.97583 14.809C9.07075 14.7391 9.15069 14.7141 9.23062 14.7191C9.29057 14.7191 9.35052 14.7241 9.40547 14.7241C13.0125 14.7241 15.5703 12.7457 15.5703 10.073C15.5703 7.49514 12.9975 5.42188 9.78516 5.42188C6.57285 5.42188 4 7.49514 4 10.073C4 11.6816 4.96919 13.1154 6.60282 13.9997C6.69774 14.0497 6.72272 14.1246 6.67276 14.2145C6.383 14.6941 5.89841 15.2387 5.69858 15.4934C5.47876 15.7732 5.60366 16.0879 6.00332 16.0879Z" fill="white"></path>
							</svg>

							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'outline'" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_1547_53979)">
								<path d="M18.5 2.5C18.5 2.22386 18.2761 2 18 2H7C6.72386 2 6.5 2.22386 6.5 2.5V9.75C6.5 10.0261 6.72386 10.25 7 10.25H14.642C14.7496 10.25 14.8543 10.2847 14.9406 10.349L17.7013 12.4052C18.0312 12.6508 18.5 12.4154 18.5 12.0042V10.25V2.5Z" stroke="#141B38" stroke-width="1.1"/>
								<path d="M2.5 6.5C2.5 6.22386 2.72386 6 3 6H15C15.2761 6 15.5 6.22386 15.5 6.5V14.5C15.5 14.7761 15.2761 15 15 15H6.66667C6.55848 15 6.45321 15.0351 6.36667 15.1L3.3 17.4C2.97038 17.6472 2.5 17.412 2.5 17V15V6.5Z" stroke="#141B38" stroke-width="1.1"/>
								<circle cx="5.75" cy="10.25" r="0.75" fill="#141B38"/>
								<circle cx="8.75" cy="10.25" r="0.75" fill="#141B38"/>
								<circle cx="11.75" cy="10.25" r="0.75" fill="#141B38"/>
								</g>
								<defs>
								<clipPath id="clip0_1547_53979">
								<rect x="0.5" width="20" height="20" rx="2" fill="white"/>
								</clipPath>
								</defs>
							</svg>

							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'overlap'" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M2.40002 8.70394C2.40002 5.59311 4.92185 3.07129 8.03268 3.07129H12.7674C15.8782 3.07129 18.4 5.59311 18.4 8.70394C18.4 11.8148 15.8782 14.3366 12.7674 14.3366H11.922C11.8615 14.3366 11.8027 14.3569 11.7552 14.3943L8.40361 17.0277C7.99495 17.3487 7.40458 17.0018 7.48624 16.4885L7.7889 14.5861C7.80973 14.4552 7.70853 14.3366 7.57591 14.3366C4.71735 14.3366 2.40002 12.0193 2.40002 9.16071V8.70394Z" fill="#1B95E0"/>
								<circle cx="6.97062" cy="8.78544" r="1.14286" fill="white"/>
								<circle cx="10.4003" cy="8.78544" r="1.14286" fill="white"/>
								<circle cx="13.828" cy="8.78544" r="1.14286" fill="white"/>
							</svg>

						</span>
						<span class="cff-post-meta-item-text cff-post-meta-txt" v-html="$parent.$parent.hasOwnNestedProperty(singlePost, 'comments.summary.total_count') ? singlePost.comments.summary.total_count : '0'"></span>
					</span>
					<span class="cff-post-meta-item">
						<span class="cff-post-meta-item-icon cff-post-meta-item-icon-share">
							<svg v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'" viewBox="0 0 576 512"><path d="M564.907 196.35L388.91 12.366C364.216-13.45 320 3.746 320 40.016v88.154C154.548 130.155 0 160.103 0 331.19c0 94.98 55.84 150.231 89.13 174.571 24.233 17.722 58.021-4.992 49.68-34.51C100.937 336.887 165.575 321.972 320 320.16V408c0 36.239 44.19 53.494 68.91 27.65l175.998-184c14.79-15.47 14.79-39.83-.001-55.3zm-23.127 33.18l-176 184c-4.933 5.16-13.78 1.73-13.78-5.53V288c-171.396 0-295.313 9.707-243.98 191.7C72 453.36 32 405.59 32 331.19 32 171.18 194.886 160 352 160V40c0-7.262 8.851-10.69 13.78-5.53l176 184a7.978 7.978 0 0 1 0 11.06z"></path></svg>
							<svg v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'" viewBox="0 0 512 512" class="cff-svg-bg"><path d="M503.691 189.836L327.687 37.851C312.281 24.546 288 35.347 288 56.015v80.053C127.371 137.907 0 170.1 0 322.326c0 61.441 39.581 122.309 83.333 154.132 13.653 9.931 33.111-2.533 28.077-18.631C66.066 312.814 132.917 274.316 288 272.085V360c0 20.7 24.3 31.453 39.687 18.164l176.004-152c11.071-9.562 11.086-26.753 0-36.328z"></path></svg>
							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'modern'" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10.8409 4.08819L7.47282 0.632655C7.17819 0.330783 6.71394 0.576121 6.71394 1.04622V2.90051C3.52663 2.94126 1 3.65312 1 7.01614C1 8.37385 1.78567 9.71906 2.65347 10.4217C2.92444 10.6412 3.31013 10.3657 3.21013 10.0101C2.31019 6.80611 3.85697 6.101 6.71394 6.0785V7.93378C6.71394 8.40435 7.17909 8.64864 7.47326 8.3466L10.8414 4.89106C11.053 4.69578 11.053 4.30572 10.8409 4.08819Z" stroke="#141B38" stroke-linecap="round"/>
							</svg>
							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'social_wall'" width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect y="0.5" width="20" height="20" rx="10" fill="#8C8F9A"></rect>
								<path d="M14.1975 9.48896L11.3919 6.6105C11.1464 6.35904 10.7597 6.56341 10.7597 6.955V8.49962C8.10468 8.53357 6 9.12655 6 11.9279C6 13.0589 6.65446 14.1795 7.37734 14.7647C7.60305 14.9476 7.92434 14.7181 7.84104 14.4219C7.09139 11.753 8.37985 11.1656 10.7597 11.1469V12.6923C10.7597 13.0843 11.1472 13.2878 11.3922 13.0362L14.1979 10.1578C14.3741 9.99509 14.3741 9.67017 14.1975 9.48896Z" fill="white"></path>
							</svg>

							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'outline'" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_1547_53989)">
								<path d="M7 4H5C4.44772 4 4 4.44772 4 5V15C4 15.5523 4.44772 16 5 16H15C15.5523 16 16 15.5523 16 15V13" stroke="black" stroke-width="1.1"/>
								<path d="M10 4H16V10" stroke="black" stroke-width="1.1"/>
								<path d="M8 12L15.5 4.5" stroke="black" stroke-width="1.1" stroke-linejoin="round"/>
								</g>
								<defs>
								<clipPath id="clip0_1547_53989">
								<rect width="20" height="20" rx="2" fill="white"/>
								</clipPath>
								</defs>
							</svg>

							<svg v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'overlap'" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M10.9861 3.45428C10.8798 3.3417 10.733 3.27601 10.5782 3.27171C10.4234 3.26741 10.2733 3.32484 10.1608 3.43135L7.08219 6.34802C6.90883 6.51226 6.85308 6.76557 6.94148 6.98742C7.02988 7.20926 7.24457 7.35482 7.48338 7.35482H9.23401V12.3132C9.23401 12.9575 9.75634 13.4798 10.4007 13.4798C11.045 13.4798 11.5673 12.9575 11.5673 12.3132V7.35482H13.3167C13.5496 7.35482 13.7602 7.21627 13.8524 7.00239C13.9446 6.7885 13.9007 6.54028 13.7408 6.37095L10.9861 3.45428ZM5.15002 12.8965C5.15002 12.4132 4.75827 12.0215 4.27502 12.0215C3.79178 12.0215 3.40002 12.4132 3.40002 12.8965V13.7715C3.40002 15.5434 4.83644 16.9798 6.60836 16.9798H14.1917C15.9636 16.9798 17.4 15.5434 17.4 13.7715V12.8965C17.4 12.4132 17.0083 12.0215 16.525 12.0215C16.0418 12.0215 15.65 12.4132 15.65 12.8965V13.7715C15.65 14.5769 14.9971 15.2298 14.1917 15.2298H6.60836C5.80294 15.2298 5.15002 14.5769 5.15002 13.7715V12.8965Z" fill="#0096CC"/>
								<path fill-rule="evenodd" clip-rule="evenodd" d="M10.9861 3.45428C10.8798 3.3417 10.733 3.27601 10.5782 3.27171C10.4234 3.26741 10.2733 3.32484 10.1608 3.43135L7.08219 6.34802C6.90883 6.51226 6.85308 6.76557 6.94148 6.98742C7.02988 7.20926 7.24457 7.35482 7.48338 7.35482H9.23401V12.3132C9.23401 12.9575 9.75634 13.4798 10.4007 13.4798C11.045 13.4798 11.5673 12.9575 11.5673 12.3132V7.35482H13.3167C13.5496 7.35482 13.7602 7.21627 13.8524 7.00239C13.9446 6.7885 13.9007 6.54028 13.7408 6.37095L10.9861 3.45428ZM5.15002 12.8965C5.15002 12.4132 4.75827 12.0215 4.27502 12.0215C3.79178 12.0215 3.40002 12.4132 3.40002 12.8965V13.7715C3.40002 15.5434 4.83644 16.9798 6.60836 16.9798H14.1917C15.9636 16.9798 17.4 15.5434 17.4 13.7715V12.8965C17.4 12.4132 17.0083 12.0215 16.525 12.0215C16.0418 12.0215 15.65 12.4132 15.65 12.8965V13.7715C15.65 14.5769 14.9971 15.2298 14.1917 15.2298H6.60836C5.80294 15.2298 5.15002 14.5769 5.15002 13.7715V12.8965Z" fill="#6F7A97"/>
							</svg>

						</span>
						<span class="cff-post-meta-item-text cff-post-meta-txt" v-html="$parent.$parent.hasOwnNestedProperty(singlePost, 'shares.count') ? singlePost.shares.count : '0'"></span>
					</span>
				</a>
			</div>
			<cff-post-actionlinks-component  :single-post="singlePost" :customizer-feed-data="customizerFeedData" :parent="$parent.$parent" :translated-text="translatedText"></cff-post-actionlinks-component>
		</div>
		<div class="cff-post-item-meta-comments cff-fb-fs" v-if="customizerFeedData.settings.feedtype != 'reviews' && $parent.$parent.hasFeature('comments_replies')" v-show="($parent.$parent.showedCommentSection.includes(singlePost.id) && !$parent.$parent.valueIsEnabled(customizerFeedData.settings.expandcomments)) || (!$parent.$parent.showedCommentSection.includes(singlePost.id) && $parent.$parent.valueIsEnabled(customizerFeedData.settings.expandcomments))">
			<div class="cff-post-item-comments-top cff-fb-fs cff-post-item-meta-bg">
				<div class="cff-post-item-comments-summary" v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme !== 'default_theme'">
					<span v-html="singlePost.comments.summary.total_count"></span> {{translatedText.comments_label}}
				</div>
				<div class="cff-post-item-comments-icon" v-if="!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme'">
					<svg viewBox="0 0 512 512"><path d="M448 0H64C28.7 0 0 28.7 0 64v288c0 35.3 28.7 64 64 64h96v84c0 7.1 5.8 12 12 12 2.4 0 4.9-.7 7.1-2.4L304 416h144c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64zm32 352c0 17.6-14.4 32-32 32H293.3l-8.5 6.4L192 460v-76H64c-17.6 0-32-14.4-32-32V64c0-17.6 14.4-32 32-32h384c17.6 0 32 14.4 32 32v288z"></path></svg>
				</div>
				<a class="cff-post-meta-link" :href="'https://www.facebook.com/'+singlePost.id" v-html="translatedText.commentonFacebookText" target="_blank"></a>
			</div>
			<div class="cff-post-item-comments-list cff-fb-fs  cff-post-item-meta-bg" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'comments.data') && singlePost.comments.data.length > 0">
				<div class="cff-post-comment-item cff-fb-fs" v-for="singleComment in singlePost.comments.data">
					<a class="cff-post-comment-item-avatar" v-if="$parent.$parent.hasOwnNestedProperty(singleComment, 'from.picture.data.url') && !$parent.$parent.valueIsEnabled(customizerFeedData.settings.hidecommentimages)" :href="$parent.$parent.hasOwnNestedProperty(singleComment, 'from.link') ? singleComment.from.link : '#'" target="_blank">
						<img :src="singleComment.from.picture.data.url" alt="">
					</a>
					<div class="cff-post-comment-item-content">
						<p>
							<a class="cff-post-comment-item-author cff-post-meta-link" :href="$parent.$parent.hasOwnNestedProperty(singleComment, 'from.link') ? singleComment.from.link : '#'" target="_blank" v-html="$parent.$parent.hasOwnNestedProperty(singleComment, 'from.name') ? singleComment.from.name : ''"></a>
							<span class="cff-post-comment-item-txt cff-post-meta-txt" v-html="singleComment.message"></span>
						</p>
						<span class="cff-post-comment-item-date cff-post-meta-txt" v-html="$parent.$parent.printDate(singleComment.created_time)"></span>
					</div>
				</div>
			</div>
		</div>
		<div class="cff-fb-fs" v-if="customizerFeedData.settings.feedtype == 'reviews'">
			<a class="cff-post-item-action-txt" :href="$parent.$parent.hasOwnNestedProperty(customizerFeedData, 'header.id') ? 'https://www.facebook.com/'+customizerFeedData.header.id+'/reviews' : ''" target="_blank">{{customizerFeedData.settings.reviewslinktext}}</a>
		</div>
	</div>
</script>


<script type="text/x-template" id="cff-post-event-detail-component">
	<div class="cff-post-event-detail cff-fb-fs"  v-if="customizerFeedData.settings.feedtype == 'events' || (singlePost.status_type == 'created_event' && $parent.$parent.hasOwnNestedProperty(singlePost, 'owner'))">
		<p class="cff-post-event-date cff-fb-fs" v-if="customizerFeedData.settings.eventdatepos == 'above'">
			<span class="cff-post-event-start-date" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'start_time')" v-html="$parent.$parent.printDate(singlePost.start_time, true)"></span>
			<span class="cff-post-event-end-date" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'end_time')" v-html="'-' + $parent.$parent.printEventEndDate(singlePost.start_time, singlePost.end_time)"></span>
		</p>
		<p class="cff-post-event-title cff-fb-fs" v-if="customizerFeedData.settings.include.includes('eventtitle')">
			<a :href="'https://facebook.com/events/'+singlePost.id" target="_blank" v-html="singlePost.name"></a>
		</p>
		<p class="cff-post-event-date cff-fb-fs" v-if="customizerFeedData.settings.eventdatepos == 'below'">
			<span class="cff-post-event-start-date" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'start_time')" v-html="$parent.$parent.printDate(singlePost.start_time, true)"></span>
			<span class="cff-post-event-end-date" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'end_time')" v-html="'-' + $parent.$parent.printEventEndDate(singlePost.start_time, singlePost.end_time)"></span>
		</p>

		<p class="cff-post-event-location cff-fb-fs" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'place') && customizerFeedData.settings.include.includes('eventdetails')">
			<a class="cff-post-event-place" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'place.name') && $parent.$parent.hasOwnNestedProperty(singlePost, 'place.id')" :href="'https://facebook.com/'+singlePost.place.id" target="_blank"  v-html="$parent.$parent.htmlEntities(singlePost.place.name)"></a>
			<span class="cff-post-event-street" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'place.location.street')" v-html="$parent.$parent.htmlEntities(singlePost.place.location.street)"></span>
			<span class="cff-post-event-city" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'place.location.city')" v-html="$parent.$parent.htmlEntities(singlePost.place.location.city)"></span>
			<span class="cff-post-event-state" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'place.location.state')" v-html="$parent.$parent.htmlEntities(singlePost.place.location.state)"></span>
			<span class="cff-post-event-zip" v-if="$parent.$parent.hasOwnNestedProperty(singlePost, 'place.location.zip')" v-html="$parent.$parent.htmlEntities(singlePost.place.location.zip)"></span>
			<a class="cff-post-event-maplink" v-if="$parent.$parent.getEventMapLink(singlePost.place) !== false" :href="$parent.$parent.getEventMapLink(singlePost.place)">Map</a>
		</p>


	</div>
</script>


<script type="text/x-template" id="cff-post-full-layout-component">
	<article class="cff-post-item-ctn" v-if="$parent.checkShowPost(singlePost)" :data-post-layout="customizerFeedData.settings.layout" :data-post-type="$parent.getPostTypeTimeline(singlePost)">
		<div class="cff-post-item-content cff-fb-fs">
			<cff-post-author-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-author-component>
			<div class="cff-post-item-text cff-fb-fs" v-if="customizerFeedData.settings.mediaposition == 'below' || $parent.getPostTypeTimeline(singlePost) == 'links'">
				<cff-post-event-detail-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-event-detail-component>
				<span v-html="$parent.expandedPostText.includes(singlePost.id) ? $parent.printPostText( singlePost, true ) : $parent.printPostText( singlePost )" v-if="customizerFeedData.settings.include.includes('text')"></span>
				<span class="cff-post-item-text-expand" v-if="singlePost.message && singlePost.message.length > customizerFeedData.settings.textlength && customizerFeedData.settings.include.includes('text')">
					...<a @click.prevent.default="$parent.expandPostText( singlePost.id )" v-html="$parent.printExpandText(singlePost.id)"></a>
				</span>
			</div>
			<cff-post-media-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-media-component>
			<cff-iframe-media-component v-if="$parent.processIframeAndLinkAndVideo( singlePost ) !== false" :single-post="singlePost" :postmedia="$parent.processIframeAndLinkAndVideo( singlePost )" :customizer-feed-data="customizerFeedData"></cff-iframe-media-component>
			<div class="cff-post-item-text cff-fb-fs" v-if="customizerFeedData.settings.mediaposition == 'above' && $parent.getPostTypeTimeline(singlePost) != 'links'">
				<cff-post-event-detail-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-event-detail-component>
				<span v-html="$parent.expandedPostText.includes(singlePost.id) ? $parent.printPostText( singlePost, true ) : $parent.printPostText( singlePost )" v-if="customizerFeedData.settings.include.includes('text')"></span>
				<span class="cff-post-item-text-expand" v-if="singlePost.message && singlePost.message.length > customizerFeedData.settings.textlength && customizerFeedData.settings.include.includes('text')">
					...<a @click.prevent.default="$parent.expandPostText( singlePost.id )" v-html="$parent.printExpandText(singlePost.id)"></a>
				</span>
			</div>
			<span class="cff-post-item-date" v-if="!customizerFeedData.settings.include.includes('author') && customizerFeedData.settings.include.includes('date')">
				<span class="cff-post-item-date-before" v-if="$parent.valueIsEnabled(customizerFeedData.settings.beforedateenabled)">{{customizerFeedData.settings.beforedate}}</span>
				<span v-if="$parent.hasOwnNestedProperty(singlePost, 'created_time')" v-html="$parent.printDate(singlePost.created_time)"></span>
				<span class="cff-post-item-date-after" v-if="$parent.valueIsEnabled(customizerFeedData.settings.afterdateenabled)">{{customizerFeedData.settings.afterdate}}</span>
			</span>
			<cff-post-meta-component :single-post="singlePost" :customizer-feed-data="customizerFeedData" :translated-text="translatedText"></cff-post-meta-component>
			<cff-post-actionlinks-component v-if="customizerFeedData.settings.feedtype == 'events'" :single-post="singlePost" :customizer-feed-data="customizerFeedData" :parent="$parent" :translated-text="translatedText"></cff-post-actionlinks-component>
		</div>
	</article>
</script>


<script type="text/x-template" id="cff-post-half-layout-component">
	<article class="cff-post-item-ctn" v-if="$parent.checkShowPost(singlePost)" :data-post-layout="customizerFeedData.settings.layout" :data-post-type="$parent.getPostTypeTimeline(singlePost)" :data-media-side="customizerFeedData.settings.mediaside">
		<div class="cff-post-item-content cff-fb-fs">
			<div class="cff-post-item-sides cff-fb-fs">
				<div class="cff-post-item-side cff-post-item-left">
					<cff-post-author-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-author-component>
					<div class="cff-post-item-text cff-fb-fs">
						<cff-post-event-detail-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-event-detail-component>
						<span v-html="$parent.expandedPostText.includes(singlePost.id) ? $parent.printPostText( singlePost, true ) : $parent.printPostText( singlePost )" v-if="customizerFeedData.settings.include.includes('text')"></span>
						<span class="cff-post-item-text-expand" v-if="singlePost.message && singlePost.message.length > customizerFeedData.settings.textlength && customizerFeedData.settings.include.includes('text')">
							...<a @click.prevent.default="$parent.expandPostText( singlePost.id )" v-html="$parent.printExpandText(singlePost.id)"></a>
						</span>
					</div>
					<span class="cff-post-item-date" v-if="!customizerFeedData.settings.include.includes('author') && customizerFeedData.settings.include.includes('date')">
						<span class="cff-post-item-date-before" v-if="$parent.valueIsEnabled(customizerFeedData.settings.beforedateenabled)">{{customizerFeedData.settings.beforedate}}</span>
						<span v-if="$parent.hasOwnNestedProperty(singlePost, 'created_time')" v-html="$parent.printDate(singlePost.created_time)"></span>
						<span class="cff-post-item-date-after" v-if="$parent.valueIsEnabled(customizerFeedData.settings.afterdateenabled)">{{customizerFeedData.settings.afterdate}}</span>
					</span>
				</div>

				<div class="cff-post-item-side cff-post-item-right">
					<cff-post-media-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-media-component>
					<cff-iframe-media-component v-if="$parent.processIframeAndLinkAndVideo( singlePost ) !== false" :single-post="singlePost" :postmedia="$parent.processIframeAndLinkAndVideo( singlePost )" :customizer-feed-data="customizerFeedData"></cff-iframe-media-component>
				</div>


			</div>

			<cff-post-meta-component :single-post="singlePost" :customizer-feed-data="customizerFeedData" :translated-text="translatedText"></cff-post-meta-component>
			<cff-post-actionlinks-component v-if="customizerFeedData.settings.feedtype == 'events'" :single-post="singlePost" :customizer-feed-data="customizerFeedData" :parent="$parent" :translated-text="translatedText"></cff-post-actionlinks-component>
		</div>
	</article>
</script>


<script type="text/x-template" id="cff-post-half-theme-layout-component">
	<article class="cff-post-item-ctn" v-if="$parent.checkShowPost(singlePost)" :data-post-layout="customizerFeedData.settings.layout" :data-post-type="$parent.getPostTypeTimeline(singlePost)" :data-media-side="customizerFeedData.settings.mediaside">
		<div class="cff-post-item-content cff-fb-fs">
			<div class="cff-post-item-sides cff-fb-fs" :class="$parent.processIframeAndLinkAndVideo( singlePost ) !== false ? 'link-post' : ''">
				<div class="cff-post-item-side cff-post-item-left">
					<div class="cff-post-item-left-inner">
						<cff-post-author-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-author-component>
						<div class="cff-post-item-text cff-fb-fs">
							<cff-post-event-detail-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-event-detail-component>
							<span v-html="$parent.expandedPostText.includes(singlePost.id) ? $parent.printPostText( singlePost, true ) : $parent.printPostText( singlePost )" v-if="customizerFeedData.settings.include.includes('text')"></span>
							<span class="cff-post-item-text-expand" v-if="singlePost.message && singlePost.message.length > customizerFeedData.settings.textlength && customizerFeedData.settings.include.includes('text')">
								...<a @click.prevent.default="$parent.expandPostText( singlePost.id )" v-html="$parent.printExpandText(singlePost.id)"></a>
							</span>
						</div>
						<span class="cff-post-item-date" v-if="!customizerFeedData.settings.include.includes('author') && customizerFeedData.settings.include.includes('date')">
							<span class="cff-post-item-date-before" v-if="$parent.valueIsEnabled(customizerFeedData.settings.beforedateenabled)">{{customizerFeedData.settings.beforedate}}</span>
							<span v-if="$parent.hasOwnNestedProperty(singlePost, 'created_time')" v-html="$parent.printDate(singlePost.created_time)"></span>
							<span class="cff-post-item-date-after" v-if="$parent.valueIsEnabled(customizerFeedData.settings.afterdateenabled)">{{customizerFeedData.settings.afterdate}}</span>
						</span>
					</div>
					<cff-post-meta-component v-if="singlePost?.attachments?.data?.[0].media_type !== 'link'" :single-post="singlePost" :customizer-feed-data="customizerFeedData" :translated-text="translatedText"></cff-post-meta-component>
				</div>
				<div class="cff-post-item-side cff-post-item-right">
					<cff-post-media-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-media-component>
					<cff-iframe-media-component v-if="$parent.processIframeAndLinkAndVideo( singlePost ) !== false" :single-post="singlePost" :postmedia="$parent.processIframeAndLinkAndVideo( singlePost )" :customizer-feed-data="customizerFeedData"></cff-iframe-media-component>
					<cff-post-meta-component v-if="singlePost?.attachments?.data?.[0].media_type == 'link'" :single-post="singlePost" :customizer-feed-data="customizerFeedData" :translated-text="translatedText"></cff-post-meta-component>
				</div>
			</div>

		</div>
	</article>
</script>

<script type="text/x-template" id="cff-post-thumb-layout-component">
	<article class="cff-post-item-ctn" v-if="$parent.checkShowPost(singlePost)" :data-post-layout="customizerFeedData.settings.layout" :data-post-type="$parent.getPostTypeTimeline(singlePost)" :data-media-side="customizerFeedData.settings.mediaside">
		<div class="cff-post-item-content cff-fb-fs">
			<div class="cff-post-item-sides  cff-fb-fs">
				<div class="cff-post-item-side cff-post-item-left">
					<cff-post-media-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-media-component>
					<cff-iframe-media-component v-if="$parent.processIframeAndLinkAndVideo( singlePost ) !== false" :single-post="singlePost" :postmedia="$parent.processIframeAndLinkAndVideo( singlePost )" :customizer-feed-data="customizerFeedData"></cff-iframe-media-component>
				</div>

				<div class="cff-post-item-side cff-post-item-right">
					<cff-post-author-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-author-component>
					<div class="cff-post-item-text cff-fb-fs">
						<cff-post-event-detail-component :single-post="singlePost" :customizer-feed-data="customizerFeedData"></cff-post-event-detail-component>
						<span v-html="$parent.expandedPostText.includes(singlePost.id) ? $parent.printPostText( singlePost, true ) : $parent.printPostText( singlePost )" v-if="customizerFeedData.settings.include.includes('text')"></span>
						<span class="cff-post-item-text-expand" v-if="singlePost.message && singlePost.message.length > customizerFeedData.settings.textlength && customizerFeedData.settings.include.includes('text')">
							...<a @click.prevent.default="$parent.expandPostText( singlePost.id )" v-html="$parent.printExpandText(singlePost.id)"></a>
						</span>
					</div>
					<span class="cff-post-item-date" v-if="!customizerFeedData.settings.include.includes('author') && customizerFeedData.settings.include.includes('date')">
						<span class="cff-post-item-date-before" v-if="$parent.valueIsEnabled(customizerFeedData.settings.beforedateenabled)">{{customizerFeedData.settings.beforedate}}</span>
						<span v-if="$parent.hasOwnNestedProperty(singlePost, 'created_time')" v-html="$parent.printDate(singlePost.created_time)"></span>
						<span class="cff-post-item-date-after" v-if="$parent.valueIsEnabled(customizerFeedData.settings.afterdateenabled)">{{customizerFeedData.settings.afterdate}}</span>
					</span>
				</div>
			</div>

			<cff-post-meta-component :single-post="singlePost" :customizer-feed-data="customizerFeedData" :translated-text="translatedText" ></cff-post-meta-component>
			<cff-post-actionlinks-component v-if="customizerFeedData.settings.feedtype == 'events'" :single-post="singlePost" :customizer-feed-data="customizerFeedData" :parent="$parent" :translated-text="translatedText"></cff-post-actionlinks-component>
		</div>
	</article>

</script>