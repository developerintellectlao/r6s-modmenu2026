<div class="cff-ev-timezone-ctn cff-fb-fs" :data-type="getShowEventsTimezoneNoticeType()" v-if="showEventsTimezoneNotice">
	<div class="cff-ev-timezone-content">
		<span v-html="getShowEventsTimezoneNoticeType() === 'fix' ? svgIcons['info'] : svgIcons['checkmark']"></span>
		<div class="cff-ev-timezone-text" v-if="getShowEventsTimezoneNoticeType() === 'fix'">
			<strong><?php echo __('Event start and end times may be inaccurate', 'custom-facebook-feed') ?></strong>
			<span><?php echo __('Due to a limitation on Facebook API, events might display time incorrectly for some users.', 'custom-facebook-feed') ?></span>
		</div>
		<div class="cff-ev-timezone-text" v-if="getShowEventsTimezoneNoticeType() !== 'fix'">
			<strong><?php echo __('Event times fixed successfully', 'custom-facebook-feed') ?></strong>
			<span><?php echo __('We have successfully added a timezone offset to fix incorrect times.<br/>You can always revert this from Advanced settings.', 'custom-facebook-feed') ?></span>
		</div>
	</div>
	<div class="cff-ev-timezone-btns"  v-if="getShowEventsTimezoneNoticeType() === 'fix'">
		<button class="sb-btn cff-btn-grey" @click.prevent.default="openFixEventTimezoneIssue()">
			<span><?php echo __('Fix Issue', 'custom-facebook-feed') ?></span>
		</button>
		<button class="sb-btn cff-btn-grey" @click.prevent.default="dimissEventsTimezoneNotice()">
			<span><?php echo __('Hide', 'custom-facebook-feed') ?></span>
		</button>
	</div>
	<div class="cff-ev-timezone-btns"  v-if="getShowEventsTimezoneNoticeType() === 'dismiss'">
		<button class="sb-btn cff-btn-grey" @click.prevent.default="dimissEventsTimezoneNotice()">
			<span><?php echo __('Dismiss', 'custom-facebook-feed') ?></span>
		</button>
	</div>
</div>