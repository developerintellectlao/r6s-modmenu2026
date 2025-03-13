<?php

/**
 * CFF License Util Class.
 *
 * @since 4.4
 */

namespace CustomFacebookFeed;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class CFF_License_Service
{
	/**
	 * Instance
	 *
	 * @since 4.4
	 * @access private
	 * @static
	 * @var CFF_License_Service
	 */
	private static $instance;

	/**
	 * Get license renew URL.
	 *
	 * @since 4.4
	 * @access public
	 */
	public $get_renew_url;

	/**
	 * Get the plugin license key.
	 *
	 * @since 4.4
	 * @access public
	 */
	public $get_license_key;

	/**
	 * Get the plugin license data
	 *
	 * @since 4.4
	 * @access public
	 */
	public $get_license_data;

	/**
	 * Check whether the license expired or not
	 *
	 * @since 4.4
	 * @access public
	 */
	public $is_license_expired;

	/**
	 * Check whether the grace period ended or not
	 *
	 * @since 4.4
	 * @access public
	 */
	public $is_license_grace_period_ended;

	/**
	 * Check whether the license expired and grace period ended
	 *
	 * @since 4.4
	 * @access public
	 */
	public $expiredLicenseWithGracePeriodEnded;

	/**
	 * Should disable Pro features
	 *
	 * @since 4.4
	 * @access public
	 */
	public $should_disable_pro_features;

	/**
	 * Instantiate the class
	 */
	public static function instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();

			self::$instance->get_renew_url = self::get_renew_url();
			self::$instance->get_license_key = self::get_license_key();
			self::$instance->get_license_data = self::get_license_data();
			self::$instance->is_license_expired = self::is_license_expired();
			self::$instance->is_license_grace_period_ended = self::is_license_grace_period_ended();
			self::$instance->expiredLicenseWithGracePeriodEnded = self::expiredLicenseWithGracePeriodEnded();
			self::$instance->should_disable_pro_features = self::should_disable_pro_features();
		}

		return self::$instance;
	}

	public static function is_current_screen_allowed()
	{
		$current_screen = get_current_screen();
		$allowed_screens = array(
			'facebook-feed_page_cff-feed-builder',
			'facebook-feed_page_cff-settings',
			'facebook-feed_page_cff-oembeds-manager',
			'facebook-feed_page_cff-extensions-manager',
			'facebook-feed_page_cff-about-us',
			'facebook-feed_page_cff-support',
		);
		$allowed_screens = apply_filters('cff_admin_pages_allowed_screens', $allowed_screens);
		$is_allowed = in_array($current_screen->id, $allowed_screens);
		return array(
			'is_allowed' => $is_allowed,
			'base' => $current_screen->base,
		);
	}

	public static function get_license_key()
	{
		$license_key = get_option('cff_license_key');
		$license_key = apply_filters('cff_license_key', $license_key);
		return trim($license_key);
	}

	public static function get_license_data()
	{
		if (get_option('cff_license_data')) {
			// Get license data from the db and convert the object to an array
			return (array) get_option('cff_license_data');
		}

		$cff_license_data = self::cff_check_license(self::$instance->get_license_key);

		return $cff_license_data;
	}

	public static function is_license_expired()
	{
		$cff_license_data = (array) self::$instance->get_license_data;
		if (isset($cff_license_data['license']) && $cff_license_data['license'] == 'invalid') {
			return true;
		}

		// If expires param isn't set yet then set it to be a date to avoid PHP notice
		$cff_license_expires_date = isset($cff_license_data['expires']) ? $cff_license_data['expires'] : '2036-12-31 23:59:59';
		if ($cff_license_expires_date === 'lifetime') {
			$cff_license_expires_date = '2036-12-31 23:59:59';
		}
		$cff_todays_date = gmdate('Y-m-d');
		$cff_interval = round(abs(strtotime($cff_todays_date) - strtotime($cff_license_expires_date)) / 86400);
		// Is license expired?
		if ($cff_interval === 0 || strtotime($cff_license_expires_date) < strtotime($cff_todays_date)) {
			// If we haven't checked the API again one last time before displaying the expired notice then check it to make sure the license hasn't been renewed
			if (get_option('cff_check_license_api_when_expires') !== 'false') {
				$cff_license_expired = self::$instance->cff_check_license(self::$instance->get_license_key, true);
			} else {
				$cff_license_expired = true;
			}
		} else {
			$cff_license_expired = false;
			// License is not expired so change the check_api setting to be true so the next time it expires it checks again
			update_option('cff_check_license_api_when_expires', 'true');
			update_option('cff_check_license_api_post_grace_period', 'true');
		}

		$cff_license_expires_date_arr = str_split($cff_license_expires_date);
		// If expired date is returned as 1970 (or any other 20th century year) then it means that the correct expired date was not returned and so don't show the renewal notice
		if ($cff_license_expires_date_arr[0] === '1') {
			$cff_license_expired = false;
		}

		// If there's no expired date then don't show the expired notification
		if (empty($cff_license_expires_date) || ! isset($cff_license_expires_date)) {
			$cff_license_expired = false;
		}

		// Is license missing - ie. on very first check
		if (isset($cff_license_data['error'])) {
			if ($cff_license_data['error'] === 'missing') {
				$cff_license_expired = false;
			}
		}

		return $cff_license_expired;
	}

	public static function is_license_grace_period_ended($post_grace_period = false)
	{
		// Get license data
		$cff_license_data = (array) self::$instance->get_license_data;
		// If expires param isn't set yet then set it to be a date to avoid PHP notice
		$cff_license_expires_date = isset($cff_license_data['expires']) ? $cff_license_data['expires'] : '2036-12-31 23:59:59';
		if ($cff_license_expires_date == 'lifetime') {
			$cff_license_expires_date = '2036-12-31 23:59:59';
		}

		$cff_todays_date = date('Y-m-d');
		$cff_grace_period_date = strtotime($cff_license_expires_date . '+14 days');
		$cff_grace_period_interval = round(abs(strtotime($cff_todays_date) - $cff_grace_period_date) / 86400);

		if ($post_grace_period && strtotime($cff_todays_date) > $cff_grace_period_date) {
			return true;
		}

		if ($cff_grace_period_interval == 0 || $cff_grace_period_date < strtotime($cff_todays_date)) {
			return true;
		}

		return;
	}

	/**
	 * Remote check for license status
	 *
	 * @since 4.4
	 */
	public static function cff_check_license($cff_license, $check_license_status = false, $license_api_second_check = false)
	{
		$cff_license = empty($cff_license) ? $cff_license : self::$instance->get_license_key;
		if (empty($cff_license)) {
			return;
		}
		if ($license_api_second_check) {
			update_option('cff_check_license_api_post_grace_period', 'false');
		} else {
			update_option('cff_check_license_api_when_expires', 'false');
		}
		// data to send to our API request
		$cff_api_params = array(
			'edd_action' => 'check_license',
			'license'    => $cff_license,
			'item_name'  => urlencode(WPW_SL_ITEM_NAME), // the name of our product in EDD
		);
		$api_url        = add_query_arg($cff_api_params, WPW_SL_STORE_URL);
		$args           = array(
			'timeout'   => 60,
			'sslverify' => false
		);
		// Call the remore license request.
		$request = CFF_HTTP_Request::request('GET', $api_url, $args);
		if (CFF_HTTP_Request::is_error($request)) {
			return;
		}
		// decode the license data
		$cff_license_data = (array) CFF_HTTP_Request::data($request);
		// Store license data in db
		if ($cff_license_data && is_array($cff_license_data) && isset($cff_license_data['license'])) {
			update_option('cff_license_data', $cff_license_data);
			update_option('cff_license_status', $cff_license_data['license']);
		}
		$cff_todays_date = gmdate('Y-m-d');
		if ($check_license_status) {
			// Check whether it's active
			if ($cff_license_data['license'] !== 'expired' && ( strtotime($cff_license_data['expires']) > strtotime($cff_todays_date) )) {
				$cff_license_status = false;
			} else {
				$cff_license_status = true;
			}

			return $cff_license_status;
		}

		return $cff_license_data;
	}

	/**
	 * Check if licese expired/inactive notices needs to show
	 *
	 * @since 2.0.2
	 */
	public static function expiredLicenseWithGracePeriodEnded()
	{
		return !empty(self::$instance->get_license_key) &&
				self::$instance->is_license_expired &&
				self::is_license_grace_period_ended(true);
	}

	/**
	 * Check if need to disable the pro features
	 *
	 * @since 4.4
	 */
	public static function should_disable_pro_features()
	{
		return empty(self::$instance->get_license_key) ||
				( self::$instance->is_license_expired &&
				self::$instance->is_license_grace_period_ended );
	}

	/**
	 * CFF Get Renew License URL
	 *
	 * @since 4.0
	 *
	 * @return string $url
	 */
	public static function get_renew_url($license_state = 'expired')
	{
		global $cff_download_id;

		if ($license_state == 'inactive') {
			return admin_url('admin.php?page=cff-settings&focus=license');
		}
		$license_key = self::$instance->get_license_key;

		$url = sprintf(
			'https://smashballoon.com/checkout/?edd_license_key=%s&download_id=%s&utm_campaign=facebook-pro&utm_source=expired-notice&utm_medium=renew-license',
			$license_key,
			$cff_download_id
		);

		return $url;
	}
}
