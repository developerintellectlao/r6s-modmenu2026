	<div class="cff-fb-source-ctn cff-fb-icalurl-ctn sb-fs-boss cff-fb-center-boss" v-if="viewsActive.iCalUrlPopup">

		<div class="cff-fb-source-popup  cff-fb-popup-inside">
			<div class="cff-fb-popup-cls" @click.prevent.default="activateView('iCalUrlPopup')">
				<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="#141B38"/>
				</svg>
			</div>

			<div class="cff-fb-source-step3 cff-fb-fs">
				<div class="cff-fb-source-top cff-fb-fs">
					<div class="cff-fb-icalurl-top cff-fb-fs">
						<h4><?php echo __('Enter iCal URL', 'custom-facebook-feed') ?></h4>
						<span><?php echo __('Due to changes in Facebook API, all page event feeds require an Event iCal URL', 'custom-facebook-feed') ?></span>
					</div>
					<div class="cff-fb-source-inputs cff-fb-fs">
						<div class="cff-fb-fs"  v-show="addIcalUrl.reconnectPage !== false">
							<div class="cff-fb-source-inp-label cff-fb-fs">
								<?php echo __('Facebook Page ID', 'custom-facebook-feed') ?>
							</div>
							<input type="text" class="cff-fb-source-inp cff-fb-fs" v-model="addIcalUrl.source_id" placeholder="<?php echo __('Enter Facebook Page ID', 'custom-facebook-feed') ?>" disabled="disabled">

							<div class="cff-fb-source-inp-label cff-fb-fs">
								<?php echo __('Event Access Token', 'custom-facebook-feed') ?>
							</div>
							<input type="text" class="cff-fb-source-inp cff-fb-fs" v-model="addIcalUrl.pageToken" placeholder="<?php echo __('Enter Token', 'custom-facebook-feed') ?>">
						</div>

						<div class="cff-fb-source-inp-label cff-fb-fs">
							<?php echo __('Events iCal URL (Optional)', 'custom-facebook-feed') ?>
							<a href="https://smashballoon.com/doc/ical-url-for-the-facebook-events-feed/" target="_blank"><strong><?php echo __('Where do I get this?', 'custom-facebook-feed') ?></strong></a>
						</div>
						<input type="text" class="cff-fb-source-inp cff-fb-fs" v-model="addIcalUrl.url" placeholder="https://www.facebook.com/events/ical/upcoming/?uid=XXXX&key=XXX">




						<div class="cff-fb-icalvent-pp-error cff-fb-fs" v-if="addIcalUrl.isError === true" v-html="addIcalUrl.errorMessage">
						</div>
						<div class="cff-fb-icalvent-pp-success cff-fb-fs" v-if="addIcalUrl.success === true">
							<?php echo __("Event iCal added successfully to your page!", 'custom-facebook-feed') ?>
						</div>
					</div>
					<div class="cff-fb-icalvent-pp-btns cff-fb-fs">
						<button class="cff-fb-source-btn sb-btn-grey" @click.prevent.default="activateView('iCalUrlPopup')">
							<span>{{genericText.cancel}}</span>
						</button>
						<button class="cff-fb-source-btn sb-btn-blue sb-account-connection-button" @click.prevent.default="connectEventiCalUrl()" :data-active="addIcalUrl.loadingAjax == false ? 'true' : 'false'">
							<div v-if="addIcalUrl.loadingAjax === false" class="cff-fb-icon-success"></div>
							<span v-if="addIcalUrl.loadingAjax === false"><?php echo __('Save & Exit', 'custom-facebook-feed') ?></span>
							<span v-if="addIcalUrl.loadingAjax" v-html="loaderSVG"></span>
						</button>
					</div>

				</div>
			</div>
		</div>
	</div>
