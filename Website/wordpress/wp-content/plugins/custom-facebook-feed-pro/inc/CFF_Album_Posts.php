<?php

namespace CustomFacebookFeed;

use CustomFacebookFeed\SB_Facebook_Data_Encryption;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Class CFF_Album_Posts
 *
 * @since 4.X
 */
class CFF_Album_Posts
{
	/**
	 * @var string
	 */
	private $cache_name;

	/**
	 * @var string
	 */
	private $api_call_url;

	/**
	 * @var array
	 */
	private $feed_options;

	/**
	 * @var class
	 */
	private $encryption;

	/**
	 * @var array
	 */
	private $albums_cache_data;
	private $albums_data;
	private $access_token;
	private $is_group;
	private $date_range;
	private $page_id;

	/**
	 * Construct.
	 *
	 * Construct Caching System
	 *
	 * @since 4.X
	 * @access public
	 */
	public function __construct($page_id, $access_token, $cff_date_range, $cff_is_group, $from_field, $feed_options, $is_cron = false)
	{
		$this->encryption = new SB_Facebook_Data_Encryption();
		$this->cache_name = '!cff_album_posts_' . $page_id;
		$this->albums_cache_data = get_option($this->cache_name);
		$this->access_token = $access_token;
		$this->is_group = $cff_is_group;
		$this->date_range = $cff_date_range;
		$this->page_id = $page_id;
		if (!$this->albums_cache_data || $is_cron === true) {
			$this->get_api_url();
			$this->init_albums();
			$this->save_albums();
		} else {
			$this->albums_data = json_decode($this->encryption->maybe_decrypt($this->albums_cache_data), true);
		}
	}


	public function get_albums($limit = false, $page = false)
	{
		// Means Return all the Data
		if ($limit === false && $page === false) {
			return $this->albums_data;
		} else {
			// Limit with pagination
			$data = array_slice($this->albums_data['data'], $page, $limit);
			return [
				'api_url' => $this->api_call_url,
				'page_id' => $this->page_id,
				'access_token' => $this->access_token,
				'is_group' => $this->is_group,
				'data' => $data
			];
		}
	}


	public function get_api_url_args($new_args = [])
	{
		return array_merge([
			'access_token'      => $this->access_token,
			'locale'            => !empty($this->feed_options['locale']) ? $this->feed_options['locale'] : get_option('cff_locale', 'en_US'),
			'date_range'        => $this->date_range
		], $new_args);
	}

	public function get_api_url($args = [])
	{
		$args = $this->get_api_url_args($args);
		if ($this->is_group) {
			$this->api_call_url = 'https://graph.facebook.com/' . $this->page_id . '/albums?fields=created_time,updated_time,name,count,cover_photo,link,modified,id&limit=10&access_token=' . $args['access_token'] .  '&locale=' . $args['locale'] . $args['date_range'];
		} else {
			$this->api_call_url =  'https://graph.facebook.com/' . $this->page_id . '/albums?fields=id,name,description,link,cover_photo{source,id},count,created_time,updated_time,from{picture,id,name,link}&limit=100&access_token=' . $args['access_token'] . '&locale=' . $args['locale'] . $args['date_range'];
		}
		return $this->api_call_url;
	}


	public static function sort_updated_date($a, $b)
	{
		return intval(strtotime($b->updated_time)) - intval(strtotime($a->updated_time));
	}

	/**
	 *
	 * @since 4.X
	 * Returns Needed Information for the Albums Posts
	 *
	 * @access public
	 */
	public function init_albums()
	{
		$albums = self::get_add_albums($this->api_call_url, []);
		usort($albums, [ 'CustomFacebookFeed\CFF_Album_Posts', 'sort_updated_date' ]);

		$this->albums_data = [
			'api_url' => $this->api_call_url,
			'page_id' => $this->page_id,
			'access_token' => $this->access_token,
			'is_group' => $this->is_group,
			'last_updated' => time(),
			'data' => $albums
		];
	}

	/**
	 *
	 * @since 4.X
	 * Save Albums
	 *
	 * @access public
	 */
	public function save_albums()
	{
		if (sizeof($this->albums_data) > 0) {
			update_option($this->cache_name, $this->encryption->maybe_encrypt(json_encode($this->albums_data)), false);
		}
	}

	public static function get_add_albums($api_url, $data = [])
	{
		$albums_data = json_decode(CFF_Utils::cff_fetchUrl($api_url));
		$temp_albums = $data;
		if (isset($albums_data->data)) {
			$temp_albums = array_merge($albums_data->data, $data);
			if (isset($albums_data->paging->next)) {
				$temp_albums = self::get_add_albums($albums_data->paging->next, $temp_albums);
			}
		}
		return $temp_albums;
	}

	/**
	 *
	 * @since
	 * Cron to Update the Album Posts
	 *
	 * @access public
	 */
	public static function cron_update_album_posts()
	{
		global $wpdb;
		$encryption = new SB_Facebook_Data_Encryption();
		$table_name = $wpdb->prefix . "options";
		$feed_albums = $wpdb->get_results("
	        SELECT `option_name` AS `name`, `option_value` AS `value`
	        FROM  $table_name
	        WHERE `option_name` LIKE ('%!cff\_album\_posts\_%')
	      ");
		foreach ($feed_albums as $single_feed) {
			$album_feed = json_decode($encryption->maybe_decrypt($single_feed->value), true);
			if (isset($album_feed['page_id'], $album_feed['access_token'], $album_feed['is_group'])) {
				$albums_posts = new CFF_Album_Posts($album_feed['page_id'], $album_feed['access_token'], '', $album_feed['is_group'], '', '', true);
			}
		}
	}
}
