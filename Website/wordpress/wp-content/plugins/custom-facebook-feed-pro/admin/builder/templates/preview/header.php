<section id="cff-header-section" class="cff-preview-header-ctn cff-fb-fs cff-preview-section" :data-dimmed="!isSectionHighLighted('header')" v-if="valueIsEnabled(customizerFeedData.settings.showheader) && customizerFeedData.header && sourcesList.length">
	<!--Visual header-->
	<div class="cff-preview-header-visual cff-fb-fs" v-if="customizerFeedData.settings.headertype == 'visual'">
		<div class="cff-preview-header-cover cff-fb-fs" v-if="valueIsEnabled(customizerFeedData.settings.headercover)">
			<img v-if="hasOwnNestedProperty(customizerFeedData,  'header.cover.source')" :src="customizerFeedData.header.cover.source" alt="Header Cover">
			<div
			class="cff-preview-header-likebox"
			v-if="(customizerFeedData.settings.feedtheme == 'default_theme' || customizerFeedData.settings.feedtheme == 'social_wall') && valueIsEnabled(customizerFeedData.settings.headerbio)">
				<div v-if="(!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme')" v-html="svgIcons['facebook']"></div>
				<div v-if="(customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'social_wall')">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_669_113477)">
						<circle cx="10" cy="10" r="10" fill="#FE544F"/>
						<path d="M15.3028 7.41117C14.8142 6.45845 13.4067 5.67895 11.7695 6.13279C10.9871 6.3475 10.3046 6.80793 9.83325 7.43888C9.36195 6.80793 8.67937 6.3475 7.89703 6.13279C6.25616 5.68588 4.85231 6.45845 4.3637 7.41117C3.67818 8.74497 3.9626 10.2451 5.20966 11.8699C6.18688 13.1413 7.58344 14.4301 9.61082 15.9267C9.67489 15.9742 9.75382 16 9.83507 16C9.91633 16 9.99526 15.9742 10.0593 15.9267C12.0831 14.4336 13.4833 13.1552 14.4605 11.8699C15.7039 10.2451 15.9883 8.74497 15.3028 7.41117Z" fill="white"/>
						</g>
						<defs>
						<clipPath id="clip0_669_113477">
						<rect width="20" height="20" fill="white"/>
						</clipPath>
						</defs>
					</svg>
				</div>
				<span>{{customizerFeedData.header.fan_count}}</span>
			</div>
		</div>
		<div class="cff-preview-header-info-ctn cff-fb-fs">
			<div class="cff-preview-header-avatar" v-if="valueIsEnabled(customizerFeedData.settings.headername)" >
				<img v-if="hasOwnNestedProperty(customizerFeedData,  'header.picture.data.url')" :src="customizerFeedData.header.picture.data.url" alt="Header Avatar">
			</div>
			<div class="cff-preview-header-info">
				<h3
				class="cff-preview-header-name"
				v-if="((!customizerFeedData.settings.feedtheme || customizerFeedData.settings.feedtheme == 'default_theme') || customizerFeedData.settings.feedtheme == 'social_wall' || customizerFeedData.settings.feedtheme == 'outline') && valueIsEnabled(customizerFeedData.settings.headername)"
				>
					{{customizerFeedData.header.name}}
				</h3>
				<h3
				class="cff-preview-header-name"
				v-if="(customizerFeedData.settings.feedtheme && (customizerFeedData.settings.feedtheme == 'modern' || customizerFeedData.settings.feedtheme == 'overlap')) && valueIsEnabled(customizerFeedData.settings.headername)"
				>
					<span>{{customizerFeedData.header.name}}</span>
					<span v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'modern'">
						{{customizerFeedData.header.fan_count}} <?php _e('Likes', 'custom-facebook-feed'); ?>
					</span>
				</h3>
				<div class="cff-preview-header-like-overlap" v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'overlap'">
					<span>
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<g clip-path="url(#clip0_669_113477)">
								<circle cx="10" cy="10" r="10" fill="#FE544F"/>
								<path d="M15.3028 7.41117C14.8142 6.45845 13.4067 5.67895 11.7695 6.13279C10.9871 6.3475 10.3046 6.80793 9.83325 7.43888C9.36195 6.80793 8.67937 6.3475 7.89703 6.13279C6.25616 5.68588 4.85231 6.45845 4.3637 7.41117C3.67818 8.74497 3.9626 10.2451 5.20966 11.8699C6.18688 13.1413 7.58344 14.4301 9.61082 15.9267C9.67489 15.9742 9.75382 16 9.83507 16C9.91633 16 9.99526 15.9742 10.0593 15.9267C12.0831 14.4336 13.4833 13.1552 14.4605 11.8699C15.7039 10.2451 15.9883 8.74497 15.3028 7.41117Z" fill="white"/>
							</g>
							<defs>
								<clipPath id="clip0_669_113477">
									<rect width="20" height="20" fill="white"/>
								</clipPath>
							</defs>
						</svg>
					</span>
					<span>{{customizerFeedData.header.fan_count}}</span>
				</div>
				<div class="cff-preview-header-bio" v-if="valueIsEnabled(customizerFeedData.settings.headerbio)">{{customizerFeedData.header.about}}</div>

				<div class="cff-preview-header-like-count" v-if="customizerFeedData.settings.feedtheme && customizerFeedData.settings.feedtheme == 'outline'">
					<span>
						<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M22.33 12.88C22.44 12.63 22.5 12.36 22.5 12.08V11C22.5 9.9 21.6 9 20.5 9H15L15.92 4.35C15.97 4.13 15.94 3.89 15.84 3.69C15.6126 3.23961 15.3156 2.82789 14.96 2.47V2.47C14.7208 2.22565 14.3186 2.25842 14.1221 2.53825L10 8.41V8.41C9.05471 9.52544 7.5 10.1853 7.5 11.6474V17.67C7.50264 18.2889 7.75035 18.8815 8.1889 19.3182C8.62744 19.7548 9.22112 20 9.84 20H17.95C18.65 20 19.31 19.63 19.67 19.03L22.33 12.88Z" stroke="#141B38" stroke-width="1.25"/>
							<rect x="3.5" y="9" width="4" height="11" rx="1" stroke="#141B38" stroke-width="1.25"/>
						</svg>
					</span>
					<span>{{customizerFeedData.header.fan_count}}</span>
				</div>
			</div>

		</div>
	</div>
	<!--Text header-->
	<div class="cff-preview-header-text cff-fb-fs" v-if="customizerFeedData.settings.headertype == 'text'">
		<h3 class="cff-preview-header-text-h cff-fb-fs">
			<div class="cff-preview-header-text-icon" v-if="valueIsEnabled(customizerFeedData.settings.headericonenabled)">
				<span class="cff-header-text-icon fa fab " :class="'fa-'+customizerFeedData.settings.headericon"></span>
			</div>
			<span class="cff-header-text" v-html="customizerFeedData.settings.headertext"></span>
		</h3>
	</div>

</section>

<svg width="24px" height="24px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="cff-screenreader" role="img" aria-labelledby="metaSVGid metaSVGdesc"><title id="metaSVGid">Comments Box SVG icons</title><desc id="metaSVGdesc">Used for the like, share, comment, and reaction icons</desc><defs><linearGradient id="angryGrad" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#f9ae9e"></stop><stop offset="70%" stop-color="#ffe7a4"></stop></linearGradient><linearGradient id="likeGrad"><stop offset="25%" stop-color="rgba(0,0,0,0.05)"></stop><stop offset="26%" stop-color="rgba(255,255,255,0.7)"></stop></linearGradient><linearGradient id="likeGradHover"><stop offset="25%" stop-color="#a3caff"></stop><stop offset="26%" stop-color="#fff"></stop></linearGradient><linearGradient id="likeGradDark"><stop offset="25%" stop-color="rgba(255,255,255,0.5)"></stop><stop offset="26%" stop-color="rgba(255,255,255,0.7)"></stop></linearGradient></defs></svg>