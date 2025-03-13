<?php

use CustomFacebookFeed\Admin\CFF_Support_Tool;

if (!defined('ABSPATH')) {
	return;
}
$role_id      = CFF_Support_Tool::$plugin . CFF_Support_Tool::$role;
$cap          = $role_id;
if (!current_user_can($cap)) {
	return;
}

$all_sources = CustomFacebookFeed\Builder\CFF_Feed_Builder::get_source_list();

if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'cff-api-check')) {
	$results = $this->validate_and_sanitize_support_settings($_POST);
}



?>
<div class="cff_support_tools_wrap">
	<form method="post" action="">
		<?php wp_nonce_field('cff-api-check'); ?>

		<div class="cff_support_tools_field_group">
			<label for="sb_facebook_support_source"><?php esc_html_e('Connected Sources', 'custom-facebook-feed'); ?></label>
			<select id="sb_facebook_support_source" name="sb_facebook_support_source">
				<option value="">Please Select</option>
				<?php foreach ($all_sources as $source) : ?>
					<option value="<?php echo esc_attr($source['id']); ?>">
						<?php echo esc_html($source['username']); ?>
						(<?php echo esc_html($source['account_type']); ?><?php echo (!empty($source['privilege']) ? (' + ' . esc_html($source['privilege'])) : '')?>)
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="cff_support_tools_field_group">
			<label for="sb_facebook_support_endpoint"><?php esc_html_e('Endpoint', 'custom-facebook-feed'); ?></label>
			<select id="sb_facebook_support_endpoint" name="sb_facebook_support_endpoint">
				<option value="">Please Select</option>
				<?php foreach ($this->available_endpoints() as $key => $endpoint) : ?>
					<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($endpoint); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="cff_support_tools_field_group cff_support_tools_field_group_hide_show" data-show="timeline,photos,videos,albums">
			<label for="sb_facebook_support_showby"><?php esc_html_e('Show Posts By', 'custom-facebook-feed'); ?></label>
			<select id="sb_facebook_support_showby" name="sb_facebook_support_showby">
				<?php foreach ($this->available_timeline_showby() as $key => $showby) : ?>
					<option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($showby); ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="cff_support_tools_field_group cff_support_tools_field_group_hide_show" data-show="videos">
			<label for="sb_facebook_support_videos_playlist"><?php esc_html_e('Videos Sources (Optional)', 'custom-facebook-feed'); ?></label>
			<input type="text"  id="sb_facebook_support_videos_playlist" name="sb_facebook_support_videos_playlist" placeholder="https://www.facebook.com/watch/XXXXX/YYYY"/>
			<span><?php esc_html_e('Leave empty if you want to get all the available videos', 'custom-facebook-feed'); ?></span>
		</div>

		<div class="cff_support_tools_field_group cff_support_tools_field_group_hide_show" data-show="singlealbum">
			<label for="sb_facebook_support_singlealbum"><?php esc_html_e('Single Album', 'custom-facebook-feed'); ?></label>
			<input type="text"  id="sb_facebook_support_singlealbum" name="sb_facebook_support_singlealbum" placeholder="https://www.facebook.com/media/set/?set=a.XXXXXX"/>
			<span><?php esc_html_e('Single album extension mandatory for this to work', 'custom-facebook-feed'); ?></span>
		</div>

		<div class="cff_support_tools_field_group cff_support_tools_field_group_hide_show" data-show="featuredpost">
			<label for="sb_facebook_support_featuredpost"><?php esc_html_e('Featured Post', 'custom-facebook-feed'); ?></label>
			<input type="text"  id="sb_facebook_support_featuredpost" name="sb_facebook_support_featuredpost" placeholder="https://facebook.com/{USER}/post/{ID}"/>
			<span><?php esc_html_e('Featured Post extension mandatory for this to work', 'custom-facebook-feed'); ?></span>
		</div>

		<div class="cff_support_tools_field_group cff_support_tools_field_group_hide_show" data-show="events">
			<label for="sb_facebook_support_events_ical"><?php esc_html_e('Events iCal URL', 'custom-facebook-feed'); ?></label>
			<input type="text"  id="sb_facebook_support_events_ical" name="sb_facebook_support_events_ical" placeholder="https://www.facebook.com/events/ical/upcoming/?uid=XXXX&key=XXX"/>
		</div>



		<div class="cff_support_tools_field_group">
			<label for="sb_facebook_support_limit"><?php esc_html_e('Limit', 'custom-facebook-feed'); ?></label>
			<input id="sb_facebook_support_limit" name="sb_facebook_support_limit" type="number">
		</div>

		<button class="button button-primary" type="submit">Submit</button>

	</form>
</div>


<style>
	.cff_support_tools_wrap {
		padding: 20px;
	}
	.cff_support_tools_field_group {
		margin-bottom: 20px;
		width: 50%;
	}
	.cff_support_tools_field_group label {
		display: block;
		font-weight: bold;
	}
	.cff_support_tools_field_group > *{
		width: 100%;
	}
	.cff_support_tools_field_group_hide_show{
		display: none;
	}
</style>

<script>
jQuery(function($) {
	$('#sb_facebook_support_endpoint').on('change', function(){
		let endpoint = $(this).val();
		$('.cff_support_tools_field_group_hide_show').hide();
		$('.cff_support_tools_field_group_hide_show[data-show*="'+endpoint+'"]').show();
	})

});

</script>