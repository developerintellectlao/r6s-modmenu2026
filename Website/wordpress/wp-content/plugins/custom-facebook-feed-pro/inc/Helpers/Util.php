<?php

namespace CustomFacebookFeed\Helpers;

class Util
{
	public static function isFBPage()
	{
		return get_current_screen() !== null && ! empty($_GET['page']) && strpos($_GET['page'], 'cff-') !== false;
	}

	public static function currentPageIs($page)
	{
		$current_screen = get_current_screen();
		return $current_screen !== null && ! empty($current_screen) && strpos($current_screen->id, $page) !== false;
	}

	public static function capablityCheck()
	{
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);
		return  $cap;
	}

	public static function locationDataSanitize($location_data)
	{
		$sanitized_location_data = array(
			'feed_id' => '',
			'atts' => array(),
			'location' => array(
				'post_id' => 0,
				'html' => 'unknown'
			)
		);

		if (is_array($location_data['atts'])) {
			foreach ($location_data['atts'] as $key => $value) {
				$sanitized_location_data['atts'][ sanitize_key($key) ] = sanitize_text_field(wp_unslash($value));
			}
		}
		$sanitized_location_data['feed_id'] = sanitize_text_field($location_data['feed_id']);
		$sanitized_location_data['location'] = array(
			'post_id' => sanitize_text_field($location_data['location']['post_id']),
			'html' => in_array($location_data['location']['html'], array( 'header', 'footer', 'sidebar', 'content' )) ? $location_data['location']['html'] : 'unknown',
		);

		return $sanitized_location_data;
	}

	/**
	 * Get other active plugins of Smash Balloon
	 *
	 * @since 4.4.0
	 */
	public static function get_sb_active_plugins_info()
	{
		// get the WordPress's core list of installed plugins
		if (! function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$installed_plugins = get_plugins();

		$is_facebook_installed = false;
		$facebook_plugin = 'custom-facebook-feed/custom-facebook-feed.php';
		if (
			isset($installed_plugins['custom-facebook-feed-pro/custom-facebook-feed.php'])
			|| isset($installed_plugins['custom-facebook-feed/custom-facebook-feed.php'])
		) {
			$is_facebook_installed = true;
			$facebook_plugin = is_plugin_active('custom-facebook-feed-pro/custom-facebook-feed.php')
				? 'custom-facebook-feed-pro/custom-facebook-feed.php'
				: $facebook_plugin;
		}

		$is_instagram_installed = false;
		$instagram_plugin = 'instagram-feed/instagram-feed.php';
		if (
			isset($installed_plugins['instagram-feed-pro/instagram-feed.php'])
			|| isset($installed_plugins['instagram-feed/instagram-feed.php'])
		) {
			$is_instagram_installed = true;
			$instagram_plugin = is_plugin_active('instagram-feed-pro/instagram-feed.php')
				? 'instagram-feed-pro/instagram-feed.php'
				: $instagram_plugin;
		}

		$is_twitter_installed = false;
		$twitter_plugin = 'custom-twitter-feeds/custom-twitter-feed.php';

		if (
			isset($installed_plugins['custom-twitter-feeds-pro/custom-twitter-feed.php'])
			|| isset($installed_plugins['custom-twitter-feeds/custom-twitter-feed.php'])
		) {
			$is_twitter_installed = true;
			$twitter_plugin = is_plugin_active('custom-twitter-feeds-pro/custom-twitter-feed.php')
				? 'custom-twitter-feeds-pro/custom-twitter-feed.php'
				: $twitter_plugin;
		}

		$is_youtube_installed = false;
		$youtube_plugin       = 'feeds-for-youtube/youtube-feed.php';
		if (
			isset($installed_plugins['youtube-feed-pro/youtube-feed-pro.php'])
			|| isset($installed_plugins['feeds-for-youtube/youtube-feed.php'])
		) {
			$is_youtube_installed = true;
			$youtube_plugin = is_plugin_active('youtube-feed-pro/youtube-feed-pro.php')
				? 'youtube-feed-pro/youtube-feed-pro.php'
				: $youtube_plugin;
		}

		$is_reviews_installed = false;
		$reviews_plugin       = 'reviews-feed/sb-reviews.php';
		if (
			isset($installed_plugins['reviews-feed-pro/sb-reviews-pro.php'])
			|| isset($installed_plugins['reviews-feed/sb-reviews.php'])
		) {
			$is_reviews_installed = true;
			$reviews_plugin = is_plugin_active('reviews-feed-pro/sb-reviews-pro.php')
				? 'reviews-feed-pro/sb-reviews-pro.php'
				: $reviews_plugin;
		}

		$is_social_wall_installed = isset($installed_plugins['social-wall/social-wall.php']) ? true : false;
		$social_wall_plugin = 'social-wall/social-wall.php';


		return array(
			'is_facebook_installed' => $is_facebook_installed,
			'is_instagram_installed' => $is_instagram_installed,
			'is_twitter_installed' => $is_twitter_installed,
			'is_youtube_installed' => $is_youtube_installed,
			'is_reviews_installed' => $is_reviews_installed,
			'is_social_wall_installed' => $is_social_wall_installed,
			'facebook_plugin' => $facebook_plugin,
			'instagram_plugin' => $instagram_plugin,
			'twitter_plugin' => $twitter_plugin,
			'youtube_plugin' => $youtube_plugin,
			'reviews_plugin' => $reviews_plugin,
			'social_wall_plugin' => $social_wall_plugin,
			'installed_plugins' => $installed_plugins
		);
	}

	/**
	 * Get the info of the other active plugins of Smash Balloon
	 *
	 * @since 4.3.7
	 */
	public static function get_smash_plugins_status_info()
	{
		$plugins = self::get_sb_active_plugins_info();

		$plugins_status = array(
			'instagram' => array(
				'installed' => $plugins['is_instagram_installed'],
				'active' => is_plugin_active($plugins['instagram_plugin']),
				'plugin_file' => $plugins['instagram_plugin']
			),
			'facebook' => array(
				'installed' => $plugins['is_facebook_installed'],
				'active' => is_plugin_active($plugins['facebook_plugin']),
				'plugin_file' => $plugins['facebook_plugin']
			),
			'twitter' => array(
				'installed' => $plugins['is_twitter_installed'],
				'active' => is_plugin_active($plugins['twitter_plugin']),
				'plugin_file' => $plugins['twitter_plugin']
			),
			'youtube' => array(
				'installed' => $plugins['is_youtube_installed'],
				'active' => is_plugin_active($plugins['youtube_plugin']),
				'plugin_file' => $plugins['youtube_plugin']
			),
			'social_wall' => array(
				'installed' => $plugins['is_social_wall_installed'],
				'active' => is_plugin_active($plugins['social_wall_plugin']),
				'plugin_file' => $plugins['social_wall_plugin']
			),
			'reviews' => array(
				'installed' => $plugins['is_reviews_installed'],
				'active' => is_plugin_active($plugins['reviews_plugin']),
				'plugin_file' => $plugins['reviews_plugin'],
			),
		);

		return $plugins_status;
	}
}
