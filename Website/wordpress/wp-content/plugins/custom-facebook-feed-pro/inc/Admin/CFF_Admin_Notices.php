<?php

/**
 * CFF Admin Notices.
 *
 * @since 4.0
 */

namespace CustomFacebookFeed\Admin;

use CustomFacebookFeed\Builder\CFF_Feed_Builder;
use CustomFacebookFeed\Builder\CFF_Source;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

use CustomFacebookFeed\Helpers\Util;
use CustomFacebookFeed\CFF_Response;
use CustomFacebookFeed\CFF_HTTP_Request;

class CFF_Admin_Notices
{
	public function __construct()
	{
		$this->init();
	}

	/**
	 * Determining if the user is viewing the our page, if so, party on.
	 *
	 * @since 4.0
	 */
	public function init()
	{
		if (! is_admin()) {
			return;
		}
		add_action('in_admin_header', [ $this, 'remove_admin_notices' ]);
		add_action('cff_admin_notices', [ $this, 'cff_license_notices' ]);
		add_action('admin_notices', [ $this, 'cff_license_notices' ]);
		add_action('cff_admin_notices', [ $this, 'cff_custom_cssjs_notice' ]);
		add_action('cff_admin_notices', [ $this, 'cff_group_deprecation_dismiss_notice' ]);

		add_action('cff_admin_header_notices', array( $this, 'cff_license_header_notices' ));
		add_action('cff_admin_header_notices', array( $this, 'get_sources_events_ical_notice' ));
		add_action('admin_notices', [ $this, 'cff_custom_cssjs_notice' ]);
		add_action('admin_notices', [ $this, 'cff_group_deprecation_dismiss_notice' ]);

		add_action('wp_ajax_cff_check_license', [ $this, 'cff_check_license' ]);
		add_action('wp_ajax_cff_dismiss_license_notice', [ $this, 'cff_dismiss_license_notice' ]);
		add_action('wp_ajax_cff_dismiss_custom_cssjs_notice', [ $this, 'cff_dismiss_custom_cssjs_notice' ]);
	}

	/**
	 * Remove admin notices from inside our plugin screens so we can show our customized notices
	 *
	 * @since 4.0
	 */
	public function remove_admin_notices()
	{
		$current_screen = get_current_screen();
		$not_allowed_screens = array(
			'facebook-feed_page_cff-feed-builder',
			'facebook-feed_page_cff-settings',
			'facebook-feed_page_cff-oembeds-manager',
			'facebook-feed_page_cff-extensions-manager',
			'facebook-feed_page_cff-about-us',
			'facebook-feed_page_cff-support',
		);

		if (in_array($current_screen->base, $not_allowed_screens)  || strpos($current_screen->base, 'cff-') !== false) {
			remove_all_actions('admin_notices');
			remove_all_actions('all_admin_notices');
		}
	}

	/**
	 * CFF Get Renew License URL
	 *
	 * @since 4.0
	 *
	 * @return string $url
	 */
	public function get_renew_url()
	{
		global $cff_download_id;

		$license_key = cff_main_pro()->cff_license_handler->get_license_key;

		$url = sprintf(
			'https://smashballoon.com/checkout/?edd_license_key=%s&download_id=%s&utm_campaign=facebook-pro&utm_source=expired-notice&utm_medium=renew-license',
			$license_key,
			$cff_download_id
		);

		return $url;
	}

	/**
	 * CFF Check License
	 *
	 * @since 4.0
	 *
	 * @return CFF_Response
	 */
	public function cff_check_license()
	{
		$cff_license = trim(get_option('cff_license_key'));
		check_ajax_referer('cff_nonce', 'cff_nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}
		$user_id = get_current_user_id();
		// Check the API
		$cff_api_params = array(
			'edd_action' => 'check_license',
			'license'   => $cff_license,
			'item_name' => urlencode(WPW_SL_ITEM_NAME) // the name of our product in EDD
		);
		$cff_response = wp_remote_get(add_query_arg($cff_api_params, WPW_SL_STORE_URL), array( 'timeout' => 60, 'sslverify' => false ));
		$cff_license_data = (array) json_decode(wp_remote_retrieve_body($cff_response));
		// Update the updated license data
		update_option('cff_license_data', $cff_license_data);

		$cff_todays_date = date('Y-m-d');
		// Check whether it's active
		if ($cff_license_data['license'] !== 'expired' && ( strtotime($cff_license_data['expires']) > strtotime($cff_todays_date) )) {
			// if the license is active then lets remove the ignore check for dashboard so next time it will show the expired notice in dashboard screen
			update_user_meta($user_id, 'cff_ignore_dashboard_license_notice', false);
			$response = new CFF_Response(true, array(
				'msg' => 'License Active',
				'content' => $this->get_renewed_license_notice_content()
			));
			$response->send();
		} else {
			$content = $this->get_expired_license_notice_content();
			$content = str_replace('Your Custom Facebook Feed Pro license key has expired', 'We rechecked but your license key is still expired', $content);
			$response = new CFF_Response(false, array(
				'msg' => 'License Not Renewed',
				'content' => $content
			));
			$response->send();
		}
	}

	/**
	 * CFF Dismiss Notice
	 *
	 * @since 4.0
	 */
	public function cff_dismiss_license_notice()
	{
		check_ajax_referer('cff_nonce', 'cff_nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		global $current_user;
		$user_id = $current_user->ID;
		update_user_meta($user_id, 'cff_ignore_dashboard_license_notice', true);
	}

	/**
	 * Dismiss Custom JS and CSS deprecation notice (AJAX)
	 *
	 * @since 4.0.2/4.0.7
	 */
	public function cff_dismiss_custom_cssjs_notice()
	{
		check_ajax_referer('cff_nonce', 'cff_nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		if (! current_user_can($cap)) {
			wp_send_json_error(); // This auto-dies.
		}

		// Only display notice to admins
		if (!current_user_can($cap)) {
			return;
		}

		$cff_statuses_option = get_option('cff_statuses', array());
		$cff_statuses_option['custom_js_css_dismissed'] = true;
		update_option('cff_statuses', $cff_statuses_option, false);
	}

	/**
	 * Display license expire related notices in the plugin's pages
	 *
	 * @since 4.4
	 */
	public function cff_license_notices()
	{
		$capability_check = Util::capablityCheck();
		$current_screen  = cff_main_pro()->cff_license_handler->is_current_screen_allowed();

		// Only display notice to admins
		if (! current_user_can($capability_check)) {
			return;
		}
		// We will display the license notice only on those allowed screens
		if (isset($current_screen['is_allowed']) && $current_screen['is_allowed'] === false) {
			return;
		}
		// get the license key
		$cff_license = cff_main_pro()->cff_license_handler->get_license_key;

		/* Check that the license exists and the user hasn't already clicked to ignore the message */
		if (empty($cff_license) || !isset($cff_license)) {
			if ($current_screen['base'] !== 'facebook-feed_page_cff-feed-builder') {
				echo $this->get_inactive_license_notice_content($current_screen['base']);
			}
			return;
		}

		$cff_license_expired = cff_main_pro()->cff_license_handler->is_license_expired;

		// If license not expired then return;
		if (!$cff_license_expired) {
			return;
		}
		// Grace period ended?
		if (cff_main_pro()->cff_license_handler->is_license_grace_period_ended) {
			return;
		}

		// So, license has expired and grace period active
		// Lets display the error notice
		if ($current_screen['base'] == 'facebook-feed_page_cff-feed-builder') {
			echo $this->get_expired_license_notice_content();
		}
	}


	/**
	 * Display post 2 weeks license expired notice at the top of header
	 *
	 * @since 4.4
	 */
	public function cff_license_header_notices()
	{
		$capability_check = Util::capablityCheck();
		$current_screen  = cff_main_pro()->cff_license_handler->is_current_screen_allowed();

		// Only display notice to admins
		if (! current_user_can($capability_check)) {
			return;
		}
		// We will display the license notice only on those allowed screens
		if (isset($current_screen['is_allowed']) && $current_screen['is_allowed'] === false) {
			return;
		}
		// get the license key
		$sbi_license_key = cff_main_pro()->cff_license_handler->get_license_key;
		/* Check that the license exists and */
		if (empty($sbi_license_key) || ! isset($sbi_license_key)) {
			if ($current_screen['base'] == 'facebook-feed_page_cff-feed-builder') {
				echo $this->get_post_grace_period_header_notice('cff-license-inactive-state');
			}
			return;
		}
		// Number of days until license expires
		$cff_license_expired = cff_main_pro()->cff_license_handler->is_license_expired;
		if (! $cff_license_expired) {
			return;
		}
		// Grace period ended?
		if (cff_main_pro()->cff_license_handler->is_license_grace_period_ended(true)) {
			if (get_option('cff_check_license_api_post_grace_period') !== 'false') {
				$cff_license_expired = cff_main_pro()->cff_license_handler->cff_check_license(cff_main_pro()->cff_license_handler->get_license_key, true, true);
			}
			if ($cff_license_expired) {
				echo $this->get_post_grace_period_header_notice();
			}
		}
	}

	/**
	 * Custom JS and CSS deprecation notice
	 *
	 * @since 4.0.2/4.0.7
	 */
	public function cff_custom_cssjs_notice()
	{
		$cff_statuses_option = get_option('cff_statuses', array());
		if (! empty($cff_statuses_option['custom_js_css_dismissed'])) {
			return;
		}

		if (! empty($_GET['cff_dismiss_notice']) && $_GET['cff_dismiss_notice'] === 'customjscss') {
			$cff_statuses_option['custom_js_css_dismissed'] = true;
			update_option('cff_statuses', $cff_statuses_option, false);
			return;
		}
		$cff_style_settings 					= get_option('cff_style_settings');

		$custom_js_not_empty = ! empty($cff_style_settings['cff_custom_js']) && trim($cff_style_settings['cff_custom_js']) !== '';
		$custom_css_not_empty = ! empty($cff_style_settings['cff_custom_css_read_only']) && trim($cff_style_settings['cff_custom_css_read_only']) !== '';

		if (! $custom_js_not_empty && ! $custom_css_not_empty) {
			return;
		}
		$close_href = add_query_arg(array( 'cff_dismiss_notice' => 'customjscss' ));

		?>
		<div class="notice notice-warning is-dismissible cff-dismissible">
			<p><?php if ($custom_js_not_empty) : ?>
				<?php echo sprintf(__('You are currently using Custom CSS or JavaScript in the Custom Facebook Feed plugin, however, these settings have now been deprecated. To continue using any custom code, please go to the Custom CSS and JS settings %shere%s and follow the directions.', 'custom-facebook-feed'), '<a href="' . admin_url('admin.php?page=cff-settings&view=feeds') . '">', '</a>'); ?>
			   <?php else : ?>
				   <?php echo sprintf(__('You are currently using Custom CSS in the Custom Facebook Feed plugin, however, this setting has now been deprecated. Your CSS has been moved to the "Additional CSS" field in the WordPress Customizer %shere%s instead.', 'custom-facebook-feed'), '<a href="' . esc_url(wp_customize_url()) . '">', '</a>'); ?>
			   <?php endif; ?>
			&nbsp;<a href="<?php echo esc_attr($close_href); ?>"><?php echo __('Dismiss', 'custom-facebook-feed'); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Get content for expired license notice
	 *
	 * @since 4.0
	 *
	 * @return string $output
	 */
	public function get_expired_license_notice_content()
	{
		$output = '<div class="sb-license-notice">
				<h4>Your license key has expired</h4>
				<p>You are no longer receiving updates that protect you against upcoming Facebook changes. There’s a <strong>14 day</strong> grace period before access to some Pro features in the plugin will be limited.</p>
				<div class="sb-notice-buttons">
					<a href="' . $this->get_renew_url() . '" class="sb-btn sb-btn-blue" target="_blank">Renew License</a>
					<a href="#" class="sb-btn" @click.prevent.default="activateView(\'whyRenewLicense\')">Why Renew?</a>
					<a class="recheck-license-status sb-btn" @click="recheckLicense(\'cff\')" v-html="recheckBtnText()" :class="recheckLicenseStatus"></a>
				</div>
				<svg class="sb-notice-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="#D72C2C"/></svg>
			</div>';

		return $output;
	}


	/**
	 * Get post grace period header notice content
	 *
	 * @since 4.0
	 */
	public function get_post_grace_period_header_notice($license_status = 'expired')
	{
		$notice_text = 'Your Facebook Feed Pro License has expired. Renew to keep using PRO features.';
		if ($license_status == 'cff-license-inactive-state') {
			$notice_text = 'Your license key is inactive. Please add license key to enable PRO features.';
		}
		return '<div id="cff-license-expired-agp" class="cff-license-expired-agp sbi-le-flow-1 ' . $license_status . '">
			<span class="cff-license-expired-agp-message">' . $notice_text . ' <span @click.prevent.default="activateView(\'licenseLearnMore\')">Learn More</span></span>
			<button type="button" id="sbi-dismiss-header-notice" title="Dismiss this message" data-page="overview" class="sbi-dismiss">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M15.8327 5.34175L14.6577 4.16675L9.99935 8.82508L5.34102 4.16675L4.16602 5.34175L8.82435 10.0001L4.16602 14.6584L5.34102 15.8334L9.99935 11.1751L14.6577 15.8334L15.8327 14.6584L11.1744 10.0001L15.8327 5.34175Z" fill="white"></path>
				</svg>
			</button>
		</div>';
	}

	public function get_inactive_license_notice_content($screen)
	{
		$output = '<div id="sby-license-inactive-agp" class="sby-license-inactive-agp sby-le-flow-1">
				<div class="sb-left">
					<div class="sb-left-content">
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="#D72C2C"></path>
						</svg>
						<h4>Your license key is inactive</h4>
						<p>No license key detected. Please activate your license key to enable Pro features.</p>
					</div>
				</div>
				<div class="sb-right">
					<div class="sby-buttons">';
		if ($screen == 'facebook-feed_page_cff-settings') {
			$output .= '<a class="sb-btn sb-btn-blue"  id="sbFocusLicenseSection">Activate License Key</a>';
		} else {
			$output .= '<a href="' . admin_url('admin.php?page=cff-settings&focus=license') . '" class="sby-buttons"><span class="sb-btn sb-btn-blue">Activate License Key</span></a>';
		}
					$output .= '<a class="sb-btn sb-btn-grey" @click.prevent.default="activateView(\'licenseLearnMore\')">Learn More</a>
					</div></div>
			</div>';
		return $output;
	}

	/**
	 * Get content for successfully renewed license notice
	 *
	 * @since 4.0
	 *
	 * @return string $output
	 */
	public function get_renewed_license_notice_content()
	{
		$output = '<span class="sb-notice-icon sb-error-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z" fill="#59AB46"/>
                </svg>
            </span>
            <div class="sb-notice-body">
                <h3 class="sb-notice-title">Thanks! Your license key is valid.</h3>
                <p>You can safely dismiss this modal.</p>
                <div class="license-action-btns">
                    <a target="_blank" class="cff-license-btn cff-btn-blue cff-notice-btn" id="cff-hide-notice">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.66683 1.27325L8.72683 0.333252L5.00016 4.05992L1.2735 0.333252L0.333496 1.27325L4.06016 4.99992L0.333496 8.72659L1.2735 9.66659L5.00016 5.93992L8.72683 9.66659L9.66683 8.72659L5.94016 4.99992L9.66683 1.27325Z" fill="white"/>
                        </svg>
                        Dismiss
                    </a>
                </div>
            </div>';

		return $output;
	}

	/**
	 * Get Events iCal URL Notice
	 *
	 * @since 4.X
	 */
	public function get_sources_events_ical_notice()
	{
		$capability_check = Util::capablityCheck();
		$current_screen  = cff_main_pro()->cff_license_handler->is_current_screen_allowed();

		// Only display notice to admins
		if (! current_user_can($capability_check)) {
			return;
		}
		// We will display the license notice only on those allowed screens
		if (isset($current_screen['is_allowed']) && $current_screen['is_allowed'] === false) {
			return;
		}

		$ical_urls = get_option('cff_ical_urls', []);
		$sources_list = CFF_Feed_Builder::get_source_list();
		if (empty($ical_urls) || empty($sources_list)) {
			return;
		}
		$should_shown = false;
		foreach ($sources_list as $source) {
			if ($source['privilege'] === 'events' && $source['account_type'] === 'page' && !isset($ical_urls[$source['account_id']])) {
				$should_shown = true;
				break;
			}
		}

		if (!$should_shown) {
			return;
		}

		$notice_text = __('Due to Facebook API changes, you need to update your sources to keep on displaying events', 'custom-facebook-feed');
		echo '<div id="cff-license-expired-agp" class="cff-license-expired-agp sbi-le-flow-1">
			<span class="cff-license-expired-agp-message">' . $notice_text . ' <a class="cff-fix-btn" href="' . esc_url('admin.php?page=cff-settings') . '">Fix</a></span>
		</div>';
		   return;
	}


	/**
	 * Group Deprecation Notice
	 *
	 * @since 4.0.2/4.0.7
	 */
	public function cff_group_deprecation_dismiss_notice()
	{
		$cff_statuses_option = get_option('cff_statuses', array());
		if (
			!empty($cff_statuses_option['cff_group_deprecation_dismiss']) &&
			$cff_statuses_option['cff_group_deprecation_dismiss']  !== true
		) {
			return;
		}

		if (!empty($_GET['cff_dismiss_notice']) && $_GET['cff_dismiss_notice'] === 'group_deprecation') {
			\cff_main_pro()->cff_error_reporter->dismiss_group_deprecation_error();
			$cff_statuses_option['cff_group_deprecation_dismiss'] = true;
			update_option('cff_statuses', $cff_statuses_option, false);
			return;
		}

		if (!CFF_Source::should_show_group_deprecation()) {
			return;
		}
		$close_href = add_query_arg(array('cff_dismiss_notice' => 'group_deprecation'));
		$group_doc_url = 'https://smashballoon.com/doc/facebook-api-changes-affecting-groups-april-2024';
		?>
		<div class="notice notice-error is-dismissible cff-dismissible">
			<p>
			<?php
				echo
				sprintf(
					__('Due to changes with the Facebook API, which we use to create feeds, group feeds will no longer update after April of 2024 %sLearn More %s', 'custom-facebook-feed'),
					'<a href="' . esc_url($group_doc_url) . '">',
					'</a>'
				);
			?>
			&nbsp;<a href="<?php echo esc_attr($close_href); ?>">
					<?php echo __('Dismiss', 'custom-facebook-feed'); ?>
				</a>
			</p>
		</div>
		<?php
	}
}
