<div class="cff-fb-section-wh cff-fb-sglelm-ctn" v-if="checkCreationFeedTypeChosen('events_page')">
	<div class="cff-fb-section-wh-insd cff-fb-fs">
		<div class="cff-fb-sec-heading cff-fb-fs cff-fb-sglelm-left">
			<h4>{{addEventiCalUrlScreen.mainHeading}}</h4>
			<span v-html="addEventiCalUrlScreen.description"></span>
			<div class="cff-fb-sglelm-inp-ctn cff-fb-fs">
				<span class="cff-fb-wh-label cff-fb-fs">{{addEventiCalUrlScreen.URLorID}}</span>
				<div class="cff-fb-fs">
					<input type="text" class="cff-fb-wh-inp cff-fb-fs" placeholder="https://www.facebook.com/events/ical/upcoming/?uid=XXXXXX&key=XXXXXX" v-model="eventFeedInfo.url">
					<div class="cff-fb-sglelm-error-icon cff-fb-fs" v-if="eventFeedInfo.isError">i</div>
					<div class="cff-fb-sglelm-errormsg" v-if="eventFeedInfo.isError" v-html="addEventiCalUrlScreen.unable"></div>
				</div>
			</div>
		</div>
	</div>
</div>