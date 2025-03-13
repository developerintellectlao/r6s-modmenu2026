<div class="cff-fb-types-ctn cff-fb-fs sb-box-shadow" v-if="viewsActive.selectedFeedSection == 'feedsType'">
	<div class="cff-fb-types cff-fb-fs">
		<h4>{{selectFeedTypeScreen.feedTypeHeading}}</h4>
		<div class="cff-fb-types-list">
			<div class="cff-fb-type-el" v-for="(feedTypeEl, feedTypeIn) in feedTypes" :data-active="selectedFeed === feedTypeEl.type" @click.prevent.default="checkFeedTypeTier(feedTypeEl.type) ? chooseFeedType('normal', feedTypeEl) : viewsActive.extensionsPopupElement = feedTypeEl.type" :data-type="feedTypeEl.type">
				<div class="cff-fb-type-el-img cff-fb-fs" v-if="!shouldDisableProFeatures" v-html="svgIcons[feedTypeEl.icon]"></div>
				<div class="cff-fb-type-el-img cff-fb-fs" v-if="shouldDisableProFeatures" v-html="svgIcons[feedTypeEl.iconFree]"></div>
				<div class="cff-fb-type-el-info cff-fb-fs">
					<p class="sb-small-p sb-bold sb-dark-text">
						{{feedTypeEl.title}}
						<span v-html="svgIcons.rocketPremiumBlue" v-if="feedTypeEl.type !== 'timeline'"></span>
					</p>
					<span class="sb-caption sb-lightest">{{feedTypeEl.description}}</span>
				</div>
			</div>

		</div>
	</div>
	<div class="cff-fb-adv-types cff-fb-fs">
		<h4 class="cff-adv-types-heading">{{selectFeedTypeScreen.advancedHeading}}</h4>
		<div class="cff-fb-types-list cff-fb-fs">

			<div class="cff-fb-type-el" v-for="(advFeedTypeEl, advFeedTypeIn) in advancedFeedTypes" :data-active="selectedFeed === advFeedTypeEl.type" @click.prevent.default="chooseFeedType('advanced', advFeedTypeEl)">
				<div class="cff-fb-type-el-img cff-fb-fs" v-if="!shouldDisableProFeatures" v-html="svgIcons[advFeedTypeEl.icon]"></div>
				<div class="cff-fb-type-el-img cff-fb-fs" v-if="shouldDisableProFeatures" v-html="svgIcons[advFeedTypeEl.iconFree]"></div>
				<div class="cff-fb-type-el-info cff-fb-fs">
                    <p class="sb-small-p sb-bold sb-dark-text">
						{{advFeedTypeEl.title}}
						<span v-html="svgIcons.rocketPremiumBlue"></span>
                    </p>
					<span class="sb-caption sb-lightest">{{advFeedTypeEl.description}}</span>
				</div>
			</div>

		</div>
	</div>
</div>
