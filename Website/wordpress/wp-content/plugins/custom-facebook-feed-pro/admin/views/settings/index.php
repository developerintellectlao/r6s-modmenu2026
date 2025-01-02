<div id="cff-settings" class="cff-settings" :data-app-loaded="appLoaded ? 'true' : 'false'">
	<?php
		CustomFacebookFeed\CFF_View::render('sections.header');
		CustomFacebookFeed\CFF_View::render('settings.content');
		CustomFacebookFeed\CFF_View::render('sections.sticky_widget');
	?>
	<div class="sb-control-elem-tltp-content" v-show="tooltip.hover" @mouseover.prevent.default="hoverTooltip(true)" @mouseleave.prevent.default="hoverTooltip(false)">
		<div class="sb-control-elem-tltp-txt" v-html="tooltip.text"></div>
	</div>

	<?php
		include_once CFF_BUILDER_DIR . 'templates/sections/popup/license-learn-more.php';
		include_once CFF_BUILDER_DIR . 'templates/sections/popup/why-renew-license-popup.php';
	?>

	<div class="sb-notification-ctn" :data-active="notificationElement.shown" :data-type="notificationElement.type">
		<div class="sb-notification-icon" v-html="svgIcons[notificationElement.type+'Notification']"></div>
		<span class="sb-notification-text" v-html="notificationElement.text"></span>
	</div>
</div>