<div class="cff-fb-types-ctn cff-fb-templates-ctn cff-fb-fs sb-box-shadow" v-if="viewsActive.selectedFeedSection == 'selectTheme'">
	<div class="cff-fb-types cff-fb-fs">
		<h4>{{selectFeedThemeScreen.feedThemeHeading}}</h4>
		<p class="sb-caption sb-lighter">{{selectFeedThemeScreen.feedThemeDescription}}</p>
		<div class="cff-fb-templates-list cff-feed-theme-list">
			<div class="cff-fb-type-el" v-for="(feedTemplateEl, feedTemplateIn) in feedThemes" :data-active="selectedFeedTheme === feedTemplateEl.type" @click.prevent.default="hasFeature('feed_themes') ? chooseFeedTheme(feedTemplateEl) : alert('Poup for Feed Themes')">
				<div :class="['cff-fb-type-el-img cff-fb-fs', 'cff-feedtemplate-' + feedTemplateEl.type]">
					<img
					:src="plugin_path + 'admin/assets/img/customizer-theme/' + feedTemplateEl.type + '.jpg'"
					alt="feedTemplateEl.type">
				</div>
				<div class="cff-fb-type-el-info cff-fb-fs">
					<p class="sb-small-p sb-bold sb-dark-text" v-html="getFeedTemplateElTitle(feedTemplateEl)"></p>
				</div>
			</div>
		</div>
	</div>
</div>