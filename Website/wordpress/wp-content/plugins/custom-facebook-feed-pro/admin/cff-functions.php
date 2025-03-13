<?php

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

function cff_should_disable_pro()
{
	return cff_main_pro()->cff_license_handler->should_disable_pro_features;
}

function cff_license_inactive_state()
{
	return empty(cff_main_pro()->cff_license_handler->get_license_key);
}

function cff_license_notice_active()
{
	return empty(cff_main_pro()->cff_license_handler->get_license_key) || cff_main_pro()->cff_license_handler->expiredLicenseWithGracePeriodEnded;
}

/**
 * Check should add free plugin submenu for the free version
 *
 * @since 4.4
 */
function cff_should_add_free_plugin_submenu($plugin)
{
	if (!cff_main_pro()->cff_license_handler->should_disable_pro_features) {
		return;
	}

	if ($plugin === 'instagram' && !is_plugin_active('instagram-feed/instagram-feed.php') && !is_plugin_active('instagram-feed-pro/instagram-feed.php')) {
		return true;
	}

	if ($plugin === 'youtube' && !is_plugin_active('youtube-feed-pro/youtube-feed-pro.php') && !is_plugin_active('feeds-for-youtube/youtube-feed.php')) {
		return true;
	}

	if ($plugin === 'twitter' && !is_plugin_active('custom-twitter-feeds/custom-twitter-feed.php') && !is_plugin_active('custom-twitter-feeds-pro/custom-twitter-feed.php')) {
		return true;
	}

	if ($plugin === 'reviews' && !is_plugin_active('reviews-feed/sb-reviews.php') && !is_plugin_active('reviews-feed-pro/sb-reviews-pro.php')) {
		return true;
	}

	return;
}


function flatten_array($array)
{
	$result = array();
	foreach ($array as $value) {
		if (is_array($value)) {
			$result = array_merge($result, flatten_array($value));
		} else {
			$result[] = $value;
		}
	}
	return $result;
}

function cff_encrypt_decrypt($action, $string)
{
	$output = false;

	$encrypt_method = "AES-256-CBC";
	$secret_key = 'SMA$H.BA[[OON#23121';
	$secret_iv = '1231394873342102221';

	// hash
	$key = hash('sha256', $secret_key);

	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	if ($action === 'encrypt') {
		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		$output = base64_encode($output);
	} elseif ($action === 'decrypt') {
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	}

	return $output;
}


function update_connected_accounts($connected_accounts)
{
	update_option('cff_connected_accounts', $connected_accounts);
	return $connected_accounts;
}


/**
 * Return a combination of legacy and new sources with new sources
 * overriding legacy sources.
 *
 * @return StdClass
 *
 * @since 4.0
 */
function get_connected_accounts_list()
{
	$connected_accounts = CustomFacebookFeed\CFF_Utils::cff_get_connected_accounts_object();

	if (empty($connected_accounts)) {
		$connected_accounts = [];
	}

	$new_sources = \CustomFacebookFeed\Builder\CFF_Feed_Builder::get_source_list();

	$encryption = new \CustomFacebookFeed\SB_Facebook_Data_Encryption();

	foreach ($new_sources as $new_source) {
		if (!empty($new_source['account_id'])) {
			$account_id = $new_source['account_id'];
			array_push(
				$connected_accounts,
				[
					'id'          => $account_id,
					'accesstoken' => $encryption->decrypt($new_source['access_token']) ? $encryption->decrypt($new_source['access_token']) : $new_source['access_token'],
					'pagetype'    => $new_source['account_type'],
					'name'        => $new_source['username'],
					'avatar'      => $new_source['avatar_url']
				]
			);
		}
	}

	return $connected_accounts;
}


function cff_get_oembed_connection_url()
{
	$admin_url_state = admin_url('admin.php?page=cff-oembeds-manager');
	$nonce           = wp_create_nonce('cff_con');
	// If the admin_url isn't returned correctly then use a fallback
	if ($admin_url_state == '/wp-admin/admin.php?page=cff-oembeds-manager') {
		$admin_url_state = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	}

	return array(
		'connect' => CFF_OEMBED_CONNECT_URL,
		'cff_con' => $nonce,
		'stateURL' => $admin_url_state
	);
}
