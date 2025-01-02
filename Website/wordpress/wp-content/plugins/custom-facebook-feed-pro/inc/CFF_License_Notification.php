<?php

/**
 * Class CFF_License_Notification
 *
 * This class displays license related notices in front end
 *
 * @since 4.4
 */

namespace CustomFacebookFeed;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

use CustomFacebookFeed\Helpers\Util;
use CustomFacebookFeed\Builder\CFF_Db;
use CustomFacebookFeed\Builder\CFF_Feed_Builder;

class CFF_License_Notification
{
	protected $db;

	public function __construct()
	{
		$this->db = new CFF_Db();
		$this->register();
	}

	public function register()
	{
		add_action('wp_footer', [$this, 'cff_frontend_license_error'], 300);
		add_action('wp_ajax_cff_hide_frontend_license_error', [$this, 'hide_frontend_license_error'], 10);
	}

	/**
	 * Hide the frontend license error message for a day
	 *
	 * @since 2.0.3
	 */
	public function hide_frontend_license_error()
	{
		check_ajax_referer('cff_nonce', 'nonce');
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (!current_user_can($cap)) {
			return;
		}

		set_transient('cff_license_error_notice', true, DAY_IN_SECONDS);
		wp_die();
	}

	public function cff_frontend_license_error()
	{
		// Don't do anything for guests.
		if (! is_user_logged_in()) {
			return;
		}
		if (! current_user_can(Util::capablityCheck())) {
			return;
		}
		// Check that the license exists and the user hasn't already clicked to ignore the message
		if (empty(cff_main_pro()->cff_license_handler->get_license_key)) {
			$this->cff_frontend_license_error_content('inactive');
			return;
		}
		// If license not expired then return;
		if (!cff_main_pro()->cff_license_handler->is_license_expired) {
			return;
		}
		if (cff_main_pro()->cff_license_handler->is_license_grace_period_ended(true)) {
			$this->cff_frontend_license_error_content();
		}
		return;
	}

	/**
	 * Output frontend license error HTML content
	 *
	 * @since 6.2.0
	 */
	public function cff_frontend_license_error_content($license_state = 'expired')
	{
			$icons = CFF_Feed_Builder::builder_svg_icons();

			$feeds_count = $this->db->feeds_count();
		if ($feeds_count <= 0) {
			return;
		}
			$should_display_license_error_notice = get_transient('cff_license_error_notice');
		if ($should_display_license_error_notice) {
			return;
		}
		?>
			<div id="cff-fr-ce-license-error" class="cff-critical-error cff-frontend-license-notice cff-ce-license-<?php echo $license_state; ?>">
				<div class="cff-fln-header">
					<span class="sb-left">
						<?php echo $icons['eye2']; ?>
						<span class="sb-text">Only Visible to WordPress Admins</span>
					</span>
					<span id="cff-frce-hide-license-error" class="sb-close"><?php echo $icons['times2SVG']; ?></span>
				</div>
				<div class="cff-fln-body">
					<?php echo $icons['facebook']; ?>
					<div class="cff-fln-expired-text">
						<p>
							<?php
								printf(
									__('Your Facebook Feed Pro license key %s', 'custom-facebook-feed'),
									$license_state == 'expired' ? 'has ' . $license_state : 'is ' . $license_state
								);
							?>
							<a href="<?php echo $this->get_renew_url($license_state); ?>">Resolve Now <?php echo $icons['chevronRight']; ?></a>
						</p>
					</div>
				</div>
			</div>
		<?php
	}

	/**
	 * SBY Get Renew License URL
	 *
	 * @since 2.0
	 *
	 * @return string $url
	 */
	public function get_renew_url($license_state = 'expired')
	{
		global $cff_download_id;
		if ($license_state == 'inactive') {
			return admin_url('admin.php?page=cff-settings&focus=license');
		}
		$license_key = cff_main_pro()->cff_license_handler->get_license_key;

		$url = sprintf(
			'https://smashballoon.com/checkout/?edd_license_key=%s&download_id=%s&utm_campaign=instagram-pro&utm_source=expired-notice&utm_medium=renew-license',
			$license_key,
			$cff_download_id
		);

		return $url;
	}
}