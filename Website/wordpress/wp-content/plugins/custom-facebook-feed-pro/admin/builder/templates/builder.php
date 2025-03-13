<div id="cff-builder-app" class="cff-fb-fs cff-builder-app" :data-app-loaded="appLoaded ? 'true' : 'false'" :data-disable-pro="shouldDisableProFeatures ? 'true' : 'false'"
:class="{'cff-builder-app-lite-dismiss' : shouldDisableProFeatures == true && iscustomizerScreen, 'sw-feed-link-bar-present' : sw_feed, 'cff-customizer-screen': iscustomizerScreen}"
>
	<?php
		$icons = function ($icon) {
			return CustomFacebookFeed\Builder\CFF_Feed_Builder::builder_svg_icons($icon);
		};

		include_once CFF_BUILDER_DIR . 'templates/sections/header.php';
		include_once CFF_BUILDER_DIR . 'templates/screens/select-feed.php';
		include_once CFF_BUILDER_DIR . 'templates/screens/welcome.php';
		include_once CFF_BUILDER_DIR . 'templates/screens/customizer.php';
		include_once CFF_BUILDER_DIR . 'templates/sections/footer.php';
		?>
	<div class="sb-control-elem-tltp-content" v-show="tooltip.hover" @mouseover.prevent.default="hoverTooltip(true, 'inside')" @mouseleave.prevent.default="hoverTooltip(false, 'outside')">
		<div class="sb-control-elem-tltp-txt" v-html="tooltip.text" :data-align="tooltip.align"></div>
	</div>
</div>
<?php
	include_once CFF_BUILDER_DIR . 'templates/preview/theme-styles.php';
?>