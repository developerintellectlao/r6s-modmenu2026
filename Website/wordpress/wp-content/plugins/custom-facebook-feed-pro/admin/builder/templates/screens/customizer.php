<div class="sb-customizer-ctn cff-fb-fs" v-if="iscustomizerScreen">
	<?php include_once CFF_BUILDER_DIR . 'templates/sections/customizer/sidebar.php'; ?>

	<div class="cff-customizer-theme-preview">
		<div class="cff-theme-preview-item" :class="previewTheme ? 'cff-theme-preview-show' : ''">
			<img v-if="previewTheme" :src="plugin_path + 'admin/assets/img/customizer-theme/preview-images/' + previewTheme + '.jpg'" alt="previewTheme">
		</div>
	</div>

	<?php include_once CFF_BUILDER_DIR . 'templates/sections/customizer/preview.php'; ?>
</div>
<div v-html="feedStyleOutput != false ? feedStyleOutput : ''"></div>
<script type="text/x-template" id="cff-colorpicker-component">
	<input type="text" v-bind:value="color" placeholder="Select">
</script>