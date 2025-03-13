<div v-if="selected === 'app-1'">
	<div class="sb-tab-box sb-license-box clearfix">
		<div class="tab-label">
			<h3>{{generalTab.licenseBox.title}}</h3>
			<p>{{generalTab.licenseBox.description}}</p>
		</div>

		<div class="cff-tab-form-field d-flex">
			<div v-if="licenseType === 'free'" class="cff-tab-field-inner-wrap cff-license" :class="['license-' + licenseStatus, 'license-type-' + licenseType, {'form-error': hasError}]">
				<div class="upgrade-info">
					<span v-html="generalTab.licenseBox.upgradeText1"></span>
					<span v-html="generalTab.licenseBox.upgradeText2"></span>
				</div>
				<span class="license-status" v-html="generalTab.licenseBox.freeText"></span>
				<div class="field-left-content">
					<div class="sb-form-field">
						<input type="password" name="license-key" id="license-key" class="cff-form-field" :placeholder="generalTab.licenseBox.inactiveFieldPlaceholder">
					</div>
					<div class="form-info d-flex justify-between">

						<span class="manage-license">
							<a :href="links.manageLicense">{{generalTab.licenseBox.manageLicense}}</a>
						</span>
						<span>
							<span class="test-connection">
								{{generalTab.licenseBox.test}}
							</span>
							<span class="upgrade">
								<a :href="upgradeUrl" target="_blank">{{generalTab.licenseBox.upgrade}}</a>
							</span>
						</span>
					</div>
				</div>
				<div class="field-right-content">
					<button type="button" class="cff-btn sb-btn-blue">{{generalTab.licenseBox.activate}}</button>
				</div>
			</div>

			<div v-if="licenseType === 'pro' && (
				licenseStatus === 'valid' ||
				licenseStatus === 'active' ||
				licenseStatus === 'activated')"
				class="cff-tab-field-inner-wrap cff-license cff-license" :class="['license-' + licenseStatus, 'license-type-' + licenseType, {'form-error': hasError}]">
				<span class="license-status" v-html="generalTab.licenseBox.activeText"></span>
				<div class="d-flex">
					<div class="field-left-content">
						<div class="sb-form-field">
							<input type="password" name="license-key" id="license-key" class="cff-form-field" value="******************************" v-model="licenseKey">
							<span class="field-icon fa fa-check-circle"></span>
						</div>
						<div class="form-info d-flex justify-between">
							<span class="manage-license">
								<a :href="links.manageLicense" target="_blank">{{generalTab.licenseBox.manageLicense}}</a>
							</span>
							<span>
								<span class="test-connection" @click="testConnection()" v-if="testConnectionStatus === null">
									{{generalTab.licenseBox.test}}
									<span v-html="testConnectionIcon()" :class="testConnectionStatus">
									</span>
								</span>
								<span v-html="testConnectionIcon()" class="test-connection"  :class="testConnectionStatus" v-if="testConnectionStatus !== null"></span>
								<span class="recheck-license-status" @click="recheckLicense(licenseKey, pluginItemName, 'cff')" v-html="recheckBtnText('cff')" :class="recheckLicenseStatus"></span>

								<span class="upgrade">
									<a :href="upgradeUrl" target="_blank">{{generalTab.licenseBox.upgrade}}</a>
								</span>
							</span>
						</div>
					</div>
					<div class="field-right-content">
						<button type="button" class="cff-btn" v-on:click="deactivateLicense" :class="{loading: loading}">
							<span v-if="loading && pressedBtnName === 'cff'" v-html="loaderSVG"></span>
							{{generalTab.licenseBox.deactivate}}
						</button>
					</div>
				</div>
			</div>

			<div v-else
				class="cff-tab-field-inner-wrap cff-license"
				:class="['license-' + licenseStatus, 'license-type-' + licenseType, {'form-error': hasError}]"
				>
				<span class="license-status" v-html="generalTab.licenseBox.inactiveText"></span>
				<div class="d-flex">
					<div class="field-left-content">
						<div class="sb-form-field">
							<input type="password" name="license-key" id="license-key" class="cff-form-field" :placeholder="generalTab.licenseBox.inactiveFieldPlaceholder" v-model="licenseKey">
							<span class="field-icon field-icon-error fa fa-times-circle" v-if="licenseErrorMsg !== null"></span>
						</div>
						<div class="mb-6" v-if="licenseErrorMsg !== null">
							<span v-html="licenseErrorMsg" class="cff-error-text"></span>
						</div>
						<div class="form-info d-flex justify-between">
							<span></span>
							<span>
								<span class="test-connection" @click="testConnection()" v-if="testConnectionStatus === null">
									{{generalTab.licenseBox.test}}
									<span v-html="testConnectionIcon()" :class="testConnectionStatus"></span>
								</span>
								<span v-html="testConnectionIcon()" class="test-connection"  :class="testConnectionStatus" v-if="testConnectionStatus !== null"></span>
								<span class="recheck-license-status" @click="recheckLicense(licenseKey, pluginItemName, 'cff')" v-html="recheckBtnText('cff')" :class="recheckLicenseStatus"></span>

								<span class="upgrade">
									<a :href="upgradeUrl" target="_blank">{{generalTab.licenseBox.upgrade}}</a>
								</span>
							</span>
						</div>
					</div>
					<div class="field-right-content">
						<button type="button" class="cff-btn sb-btn-blue" v-on:click="activateLicense">
							<span v-if="loading && pressedBtnName === 'cff'" v-html="loaderSVG"></span>
							{{generalTab.licenseBox.activate}}
						</button>
					</div>
				</div>
			</div>

			<!-- Extension license -->
			<div class="cff-tab-field-inner-wrap" v-for="extension in extensionsLicense" :class="[extension.name + '-license', 'license-' + extension.licenseStatus]">
				<span class="license-status" v-html="extension.statusText"></span>

				<!-- If extensions license is valid -->
				<div class="d-flex" v-if="extension.licenseStatus !== false && extension.licenseStatus == 'valid'">
					<div class="field-left-content">
						<div class="sb-form-field">
							<input type="password" class="cff-form-field" value="show pass" v-model="extension.licenseKey">
							<span v-if="extension.licenseStatus == 'valid'" class="field-icon fa fa-check-circle"></span>
						</div>
						<div class="form-info d-flex justify-between">
							<span class="manage-license">
								<a :href="links.manageLicense" target="_blank">{{generalTab.licenseBox.manageLicense}}</a>
							</span>
							<span>
								<span class="recheck-license-status" @click="recheckLicense(extension.licenseKey, extension.itemName, extension.name)" v-html="recheckBtnText(extension.name)" :class="recheckLicenseStatus"></span>

								<span class="upgrade">
									<a :href="extension.upgradeUrl" target="_blank">{{generalTab.licenseBox.upgrade}}</a>
								</span>
							</span>
						</div>
					</div>
					<div class="field-right-content">
						<button type="button" class="cff-btn" v-on:click="deactivateExtensionLicense(extension)" :class="{loading: loading}">
							<span v-if="loading && pressedBtnName === extension.name" v-html="loaderSVG"></span>
							{{generalTab.licenseBox.deactivate}}
						</button>
					</div>
				</div>

				<!-- If extensions license is not valid -->
				<div class="d-flex" v-else>
					<div class="field-left-content">
						<div class="sb-form-field" :class="{'sb-field-error': extensionFieldHasError && pressedBtnName === extension.name}">
							<input type="password" class="cff-form-field" :placeholder="generalTab.licenseBox.inactiveFieldPlaceholder" v-model="extensionsLicenseKey[extension.name]">
						</div>
						<div class="form-info d-flex justify-between">
							<span class="manage-license">
								<a :href="links.manageLicense" target="_blank">{{generalTab.licenseBox.manageLicense}}</a>
							</span>
							<span>
								<span class="recheck-license-status" v-if="extension.licenseKey" @click="recheckLicense(extension.licenseKey, extension.itemName, extension.name)" v-html="recheckBtnText(extension.name)" :class="recheckLicenseStatus"></span>

								<span class="upgrade">
									<a :href="extension.upgradeUrl" target="_blank">{{generalTab.licenseBox.upgrade}}</a>
								</span>
							</span>
						</div>
					</div>
					<div class="field-right-content">
						<button @click="activateExtensionLicense(extension)" type="button" class="cff-btn cff-btn-blue" :class="{loading: loading}">
							<span v-if="loading && pressedBtnName === extension.name" v-html="loaderSVG"></span>
							{{generalTab.licenseBox.activate}}
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	 <div class="sb-propro-box clearfix"  v-if="isLicenseUpgraded">
		<div class="sb-protopro-upgrade-ctn">
			<div class="sb-protopro-upgrade-text">
				<strong>
					<?php echo __('Congratulations! You have upgraded to Custom Facebook Feed', 'custom-facebook-feed') ?>
					<span class="sb-protopro-upgrade-type" v-if="licenseUpgradedInfoTierName !== null" v-html="licenseUpgradedInfoTierName"></span>
				</strong>
				<span><?php echo __('Please update the plugin by clicking the button on the right for changes to take effect', 'custom-facebook-feed') ?></span>
			</div>
			<button type="button" class="cff-btn sb-btn-blue" v-on:click="upgradeProProLicense" v-if="isLicenseUpgraded && upgradeNewVersion === false">
				<svg v-if="!loading || pressedBtnName !== 'cff-upgrade'" width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6666 5.83332L9.99996 8.49999H12C12 9.56086 11.5785 10.5783 10.8284 11.3284C10.0782 12.0786 9.06082 12.5 7.99996 12.5C7.33329 12.5 6.68663 12.3333 6.13329 12.0333L5.15996 13.0067C6.00869 13.5468 6.99392 13.8336 7.99996 13.8333C9.41445 13.8333 10.771 13.2714 11.7712 12.2712C12.7714 11.271 13.3333 9.91448 13.3333 8.49999H15.3333L12.6666 5.83332ZM3.99996 8.49999C3.99996 7.43912 4.42139 6.42171 5.17153 5.67156C5.92168 4.92142 6.93909 4.49999 7.99996 4.49999C8.66663 4.49999 9.31329 4.66666 9.86663 4.96666L10.84 3.99332C9.99123 3.45316 9.006 3.16638 7.99996 3.16666C6.58547 3.16666 5.22892 3.72856 4.22872 4.72875C3.22853 5.72895 2.66663 7.0855 2.66663 8.49999H0.666626L3.33329 11.1667L5.99996 8.49999" fill="white"/></svg>
				<span v-if="loading && pressedBtnName === 'cff-upgrade'" v-html="loaderSVG"></span>
				<?php echo __('Update Plugin', 'custom-facebook-feed') ?>
			</button>

		</div>
	</div>
	<div id="sb-manage-sources-box" class="sb-tab-box sb-manage-sources-box clearfix">
		<div class="tab-label">
			<h3>{{generalTab.manageSource.title}}</h3>
		</div>
		<div class="cff-tab-form-field">
			<div class="sb-form-field">
				<span class="help-text">
					{{generalTab.manageSource.description}}
				</span>
				<div class="sb-sources-list">
					<div class="sb-srcs-item sb-srcs-new" @click.prevent.default="activateView('sourcePopup','creationRedirect')">
						<span class="add-new-icon">
							<svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M14.25 8H8.25V14H6.25V8H0.25V6H6.25V0H8.25V6H14.25V8Z" fill="#0068A0"/>
							</svg>
						</span>
						<span>{{genericText.addSource}}</span>
					</div>
					<div class="sb-srcs-item" v-for="(source, sourceIndex) in sourcesList" :class="{expanded: expandedFeedID == sourceIndex + 1, 'sb-account-has-error' : source.error !== ''}" :data-has-event-issue="checkSourceiCalUrl(source) === false">
						<div class="cff-fb-srcs-item-ins">
							<div class="sb-srcs-item-avatar">
								<img :src="typeof source.avatar_url !== 'undefined' && source.account_type === 'group' ? source.avatar_url : 'https://graph.facebook.com/'+source.account_id+'/picture'" alt="<?php __('Sourece avatar', 'custom-facebook-feed') ?>">
							</div>
							<div class="sb-srcs-item-inf">
								<div class="sb-srcs-item-name">
									{{source.username}}
									<span class="sb-small-p sb-small-text" v-if="typeof source?.location !== 'undefined' && source?.location !== 'undefined'"  v-html="'(' +source?.location+ ')'"></span>
									<div class="sb-group-small-notice" v-if="source?.account_type === 'group'">
										<svg width="16" height="16" viewBox="0 0 13 13" fill="none">
											<path d="M6.50008 0.666664C3.28008 0.666664 0.666748 3.28 0.666748 6.5C0.666748 9.72 3.28008 12.3333 6.50008 12.3333C9.72008 12.3333 12.3334 9.72 12.3334 6.5C12.3334 3.28 9.72008 0.666664 6.50008 0.666664ZM7.08342 9.41667H5.91675V8.25H7.08342V9.41667ZM7.08342 7.08333H5.91675V3.58333H7.08342V7.08333Z" fill="#D72C2C"/>
										</svg>
										<div class="cff-fb-onbrd-tltp-elem cff-fb-onbrd-tltp-elem-2 sb-tr-2" data-active="true">
											<div class="sb-pointer">
												<svg width="21" height="11" viewBox="0 0 21 11" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M8.59642 0.811655C9.39546 -0.135356 10.8545 -0.135357 11.6536 0.811654L20.25 11H0L8.59642 0.811655Z" fill="white"/>
												</svg>
											</div>
											<div class="cff-fb-onbrd-tltp-txt sb-small-p sb-dark-text ">
												<span v-html="genericText.deperecatedGroupText"></span>
											</div>
										</div>

									</div>
								</div>
								<div class="sb-srcs-item-used">
									<span v-html="printUsedInText(source.used_in)"></span>
									<div v-if="source.used_in > 0" class="sb-control-elem-tltp"><div class="sb-control-elem-tltp-icon" v-html="svgIcons['info']" @click.prevent.default="viewSourceInstances(source)"></div></div>
									<div v-if="source.error !== '' || source.error_encryption" class="sb-source-error-wrap">
										<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M6.50008 0.666664C3.28008 0.666664 0.666748 3.28 0.666748 6.5C0.666748 9.72 3.28008 12.3333 6.50008 12.3333C9.72008 12.3333 12.3334 9.72 12.3334 6.5C12.3334 3.28 9.72008 0.666664 6.50008 0.666664ZM7.08342 9.41667H5.91675V8.25H7.08342V9.41667ZM7.08342 7.08333H5.91675V3.58333H7.08342V7.08333Z" fill="#D72C2C"/>
										</svg>

										<span v-html="source.error !== '' ? genericText.errorSource : genericText.errorEncryption"></span><a href="#" @click.prevent.default="activateView('sourcePopup', 'creationRedirect')" v-html="genericText.reconnect"></a>
									</div>
									<div v-if="source.error === '' && typeof( source.needs_update ) !== 'undefined' && source.needs_update" class="sb-source-error-wrap">
										<svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M6.50008 0.666664C3.28008 0.666664 0.666748 3.28 0.666748 6.5C0.666748 9.72 3.28008 12.3333 6.50008 12.3333C9.72008 12.3333 12.3334 9.72 12.3334 6.5C12.3334 3.28 9.72008 0.666664 6.50008 0.666664ZM7.08342 9.41667H5.91675V8.25H7.08342V9.41667ZM7.08342 7.08333H5.91675V3.58333H7.08342V7.08333Z" fill="#D72C2C"/>
										</svg>

										<span v-html="genericText.updateRequired"></span><a href="#" @click.prevent.default="activateView('sourcePopup','creationRedirect')" v-html="genericText.reconnect"></a>
									</div>
								</div>
							</div>
							<div class="sb-srcs-item-actions">
								<div class="sb-srcs-item-actions-btn sb-srcs-item-delete" @click.prevent.default="openDialogBox('deleteSource', source)" v-html="svgIcons['delete']"></div>
								<div class="sb-srcs-item-actions-btn sb-srcs-item-cog" v-if="expandedFeedID != sourceIndex + 1" v-html="svgIcons['cog']" @click="displayFeedSettings(source, sourceIndex)"></div>
								<div class="sb-srcs-item-actions-btn sb-srcs-item-angle-up" v-if="expandedFeedID == sourceIndex + 1" v-html="svgIcons['angleUp']" @click="hideFeedSettings()"></div>
								<div class="sb-srcs-item-actions-btn sb-srcs-item-cog" v-if="source.privilege === 'events'" v-html="svgIcons['edit']" @click.prevent.default="chooseAccountId(source.account_id, true)"></div>

							</div>
						</div>
						<div class="cff-fd-lst-issue-bottom cff-fb-fs" v-if="checkSourceiCalUrl(source) === false" >
							<strong class="sb-bold" @click.prevent.default="chooseAccountId(source.account_id, true)">
								<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.99902 10.4683C7.22041 10.4683 7.40628 10.3934 7.55664 10.2436C7.707 10.0939 7.78219 9.90833 7.78219 9.68695C7.78219 9.46557 7.70731 9.27904 7.55755 9.12735C7.4078 8.97566 7.22223 8.89982 7.00085 8.89982C6.77947 8.89982 6.59359 8.97566 6.44324 9.12735C6.29287 9.27904 6.21769 9.46557 6.21769 9.68695C6.21769 9.90833 6.29257 10.0939 6.44232 10.2436C6.59208 10.3934 6.77764 10.4683 6.99902 10.4683ZM6.99994 7.67408C7.21274 7.67408 7.39233 7.60089 7.5387 7.45452C7.68508 7.30814 7.75827 7.12855 7.75827 6.91575V4.34075C7.75827 4.12795 7.68508 3.94836 7.5387 3.80198C7.39233 3.6556 7.21274 3.58242 6.99994 3.58242C6.78714 3.58242 6.60755 3.6556 6.46117 3.80198C6.31479 3.94836 6.2416 4.12795 6.2416 4.34075V6.91575C6.2416 7.12855 6.31479 7.30814 6.46117 7.45452C6.60755 7.60089 6.78714 7.67408 6.99994 7.67408ZM6.99994 13.8016C6.05646 13.8016 5.17121 13.6232 4.34419 13.2663C3.51716 12.9095 2.79777 12.4251 2.186 11.8134C1.57424 11.2016 1.08992 10.4822 0.733054 9.6552C0.376187 8.82818 0.197754 7.94293 0.197754 6.99945C0.197754 6.05597 0.376187 5.17072 0.733054 4.3437C1.08992 3.51668 1.57424 2.79728 2.186 2.18552C2.79777 1.57375 3.51716 1.08943 4.34419 0.732565C5.17121 0.375699 6.05646 0.197266 6.99994 0.197266C7.94342 0.197266 8.82866 0.375699 9.65569 0.732565C10.4827 1.08943 11.2021 1.57375 11.8139 2.18552C12.4256 2.79728 12.91 3.51668 13.2668 4.3437C13.6237 5.17072 13.8021 6.05597 13.8021 6.99945C13.8021 7.94293 13.6237 8.82818 13.2668 9.6552C12.91 10.4822 12.4256 11.2016 11.8139 11.8134C11.2021 12.4251 10.4827 12.9095 9.65569 13.2663C8.82866 13.6232 7.94342 13.8016 6.99994 13.8016ZM6.99994 12.2849C8.47819 12.2849 9.72868 11.7736 10.7514 10.7509C11.7741 9.72819 12.2854 8.47771 12.2854 6.99945C12.2854 5.52119 11.7741 4.27071 10.7514 3.248C9.72868 2.2253 8.47819 1.71395 6.99994 1.71395C5.52168 1.71395 4.2712 2.2253 3.24849 3.248C2.22579 4.27071 1.71444 5.52119 1.71444 6.99945C1.71444 8.47771 2.22579 9.72819 3.24849 10.7509C4.2712 11.7736 5.52168 12.2849 6.99994 12.2849Z" fill="#841919"/></svg>
								<span>1 {{genericText.issueFound}}</span>
								<a>{{genericText.fix}}</a>
							</strong>
						</div>
						<div class="cff-fb-srcs-info cff-fb-fs" v-if="expandedFeedID == sourceIndex + 1">
							<div class="cff-fb-srcs-info-item">
								<strong>{{genericText.id}}</strong>
								<span>{{source.account_id}}</span>
								<div class="cff-fb-srcs-info-icon" v-html="svgIcons['copy2']" @click.prevent.default="copyToClipBoard(source.account_id)"></div>
							</div>
							<div class="cff-fb-srcs-info-item" v-if="source.privilege === 'events'">
								<strong>{{genericText.icalUrlS}}</strong>
								<span>{{print_ical_url(source)}}</span>
								<div class="cff-fb-srcs-info-icon" v-html="svgIcons['edit']" @click.prevent.default="chooseAccountId(source.account_id)"></div>
							</div>
							<!--
							<div class="cff-fb-srcs-info-item">
								<strong>{{genericText.token}}</strong>
								<span>{{source.access_token}}</span>
								<div class="cff-fb-srcs-info-icon" v-html="svgIcons['copy2']" @click.prevent.default="copyToClipBoard(source.access_token)"></div>
							</div>
						-->
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>


	<div class="sb-tab-box sb-preserve-settings-box clearfix">
		<div class="tab-label">
			<h3>{{generalTab.preserveBox.title}}</h3>
		</div>

		<div class="cff-tab-form-field">
			<div class="sb-form-field">
				<label for="preserve-settings" class="cff-checkbox">
					<input type="checkbox" name="preserve-settings" id="preserve-settings" v-model="model.general.preserveSettings">
					<span class="toggle-track">
						<div class="toggle-indicator"></div>
					</span>
				</label>
				<span class="help-text">
					{{generalTab.preserveBox.description}}
				</span>
			</div>
		</div>
	</div>

	<div class="sb-tab-box sb-import-box sb-reset-box-style clearfix">
		<div class="tab-label">
			<h3>{{generalTab.importBox.title}}</h3>
		</div>
		<div class="cff-tab-form-field">
			<div class="sb-form-field">
				<div class="d-flex mb-15">
					<button type="button" class="cff-btn sb-btn-lg import-btn" id="import-btn" @click="importFile" :disabled="uploadStatus !== null">
						<span class="icon" v-html="importBtnIcon()" :class="uploadStatus"></span>
						{{generalTab.importBox.button}}
					</button>
					<div class="input-hidden">
						<input id="import_file" type="file" value="import_file" ref="file" v-on:change="uploadFile">
					</div>
				</div>
				<span class="help-text">
					{{generalTab.importBox.description}}
				</span>
			</div>
		</div>
	</div>

	<div class="sb-tab-box sb-export-box clearfix">
		<div class="tab-label">
			<h3>{{generalTab.exportBox.title}}</h3>
		</div>
		<div class="cff-tab-form-field">
			<div class="sb-form-field">
				<div class="d-flex mb-15">
					<select name="" id="cff-feeds-list" class="cff-select" v-model="exportFeed" ref="export_feed">
						<option value="none" selected disabled>Select Feed</option>
						<option v-for="feed in feeds" :value="feed.id">{{ feed.name }}</option>
					</select>
					<button type="button" class="cff-btn sb-btn-lg export-btn" @click="exportFeedSettings" :disabled="exportFeed === 'none'">
						<span class="icon" v-html="exportSVG"></span>
						{{generalTab.exportBox.button}}
					</button>
				</div>
				<span class="help-text">
					{{generalTab.exportBox.description}}
				</span>
			</div>
		</div>
	</div>
</div>
<div class="sb-fs-boss sb-pro-newversion-popup-ctn" v-if="upgradeNewVersion === true && upgradeNewVersionUrl !== false">
	<div class="sb-pro-newversion-popup">
		<div class="sb-pro-newversion-popup-txt">
			<strong><?php echo __('The plugin will be updated to a newer version', 'custom-facebook-feed'); ?> <span style="color:blue">{{upgradeRemoteVersion}}</span></strong>
			<span><?php echo __('You might want to backup your website before updating the plugin', 'custom-facebook-feed'); ?></span>
		</div>
		<div class="sb-pro-newversion-popup-btns">
			<button type="button" class="cff-btn sb-btn-gery" @click.prevent.default="cancelUpgrade()">
				<?php echo __('Cancel', 'custom-facebook-feed'); ?>
			</button>
			<button type="button" class="cff-btn sb-btn-blue" @click.prevent.default="window.location.href = upgradeNewVersionUrl">
				<?php echo __('Update Anyway', 'custom-facebook-feed'); ?>
			</button>
		</div>
	</div>
</div>