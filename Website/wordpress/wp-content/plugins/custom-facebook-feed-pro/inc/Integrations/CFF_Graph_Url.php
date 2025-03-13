<?php

/**
 * The Settings Trait
 *
 * @since 4.0
 */

namespace CustomFacebookFeed\Integrations;

if (!defined('ABSPATH')) {
	exit;// Exit if accessed directly.
}


class CFF_Graph_Url
{
	/**
	 * Return URL String Depeding on the $feed_type and settings
	 *
	 * @param string $type Call Type (Header or Feed Type).
	 * @param array  $settings feed settings.
	 * @since 4.0
	 *
	 * @return mixed|string|boolean
	 */
	public static function get_url($type, $source_id, $settings = [], $misc_args = [])
	{
		if (is_array($source_id) || strpos($source_id, ',') !== false) {
			$ids = explode(',', $source_id);
			$source_id = $ids[0];
		}
		if (!isset($type) || empty($type)) {
			return false;
		}
		$page_type = $settings['pagetype'];
		$is_group = ($page_type === 'group')  ? true : false;

		$url_builder = [
			'query' 	=> 'posts',
			'version' 	=> '4.0',
			'source_id' => $source_id
		];
		switch ($type) {
			case 'timeline':
				// Logic to get the Graph query
				if ($settings['showpostsby'] === 'others' || $is_group) {
					$url_builder['query'] = 'feed';
				}
				if ($settings['showpostsby'] === 'onlyothers' && !$is_group) {
					$url_builder['query'] = 'visitor_posts';
				}
				break;
			case 'photos':
				$url_builder['query'] = 'photos';
				$misc_args['photos_type'] = true;
				break;
			case 'videos':
				if (isset($settings['playlist']) && !empty($settings['playlist'])) {
					$url_builder['source_id'] = $settings['playlist'];
				}
				$url_builder['query'] = 'videos';
				break;
			case 'albums':
				$url_builder['query'] = 'albums';
				break;
			case 'featuredpost':
				if ($settings['featuredpostactive'] && !empty($settings['featuredpost']) && function_exists('cff_featured_event_id')) {
					return isset($misc_args['is_event']) && $misc_args['is_event'] === true ?
							cff_featured_event_id(trim($settings['featuredpost']), $misc_args['token']) :
							cff_featured_post_id(trim($settings['featuredpost']), $misc_args['token']);
				}
				break;
			case 'singlealbum':
				if ($settings['albumactive'] && !empty($settings['album']) && function_exists('cff_album_id')) {
					return cff_album_id(trim($settings['album']), $misc_args['token'], $misc_args['limit'], CFF_Graph_Url::get_date_range($misc_args));
				}
				break;
			case 'reviews':
				if (function_exists('cff_reviews_url')) {
					return cff_reviews_url($source_id, $misc_args['token'], $misc_args['limit'], $misc_args['locale'], CFF_Graph_Url::get_date_range($misc_args));
				}
				break;
			case 'events':
				$url_builder['query'] = 'events';
				$url_builder['version'] = '3.2';
				if (isset($settings['pastevents']) && $settings['pastevents'] !== false && $settings['pastevents'] !== 'false') {
					$event_offset = isset($settings['eventoffset']) ? $settings['eventoffset'] : '6';
					$event_offset_time = '-' . $event_offset . ' hours';
					$curtimeplus = strtotime($event_offset_time, time());
					$misc_args['pastevents'] = $curtimeplus;
				} else {
					$event_offset = isset($settings['eventoffset']) ? $settings['eventoffset'] : '6';
					$event_offset_time = '-' . $event_offset . ' hours';
					$curtimeplus = strtotime($event_offset_time, time());
					// $misc_args['upcomingevents'] = $curtimeplus;
				}
				$misc_args['limit'] = isset($settings['eventspostlimit']) && intval($settings['eventspostlimit']) <= 50 ? $settings['eventspostlimit'] : 50;
				break;
		}
		$misc_args['source_id'] = $source_id;
		$url_builder['fields'] = CFF_Graph_Url::get_call_type_fields_args($type, $settings, $misc_args);
		return 'https://graph.facebook.com/v' . $url_builder['version'] . '/' . $url_builder['source_id'] . '/' . $url_builder['query'] . '?' . $url_builder['fields'];
	}


	/**
	 * A List of Common Fields that can be used in different API calls
	 *
	 * @param string $type Call Type (Comment, Likes....).
	 * @param array  $settings Feed Settings.
	 * @since 4.0
	 *
	 * @return string
	 */
	public static function get_common_fields($type, $args = [])
	{
		$common_fields = [
			'comments' 			=>  'comments.summary(true)' . (isset($args['comments_limit']) ? '.limit(' . $args['comments_limit'] . ')' : '') . (isset($args['comments_childs']) ? '{created_time,from{name,id,picture{url},link},id,message,message_tags,attachment,like_count}' : '') . (isset($args['short_comments_childs']) ? '{message,created_time}' : ''),
			'likes' 			=> 'likes.summary(true)' . (isset($args['likes_limit']) ? '.limit(' . $args['likes_limit'] . ')' : ''),
			'reactions_mixed' 	=> 'reactions.type(LOVE).summary(total_count).limit(0).as(love),reactions.type(WOW).summary(total_count).limit(0).as(wow),reactions.type(HAHA).summary(total_count).limit(0).as(haha),reactions.type(SAD).summary(total_count).limit(0).as(sad),reactions.type(ANGRY).summary(total_count).limit(0).as(angry)',
			'reactions'			=> 'reactions.summary(true)' . (isset($args['reactions_limit']) ? '.limit(' . $args['reactions_limit'] . ')' : ''),
			'shares'			=> 'shares',
			'from' 				=> 'from{picture,id,name,link}',
			'attachments'	 	=> 'attachments{title' . (!isset($args['salesposts']) || $args['salesposts'] !== 'true' ? ',description' : '')  . ',media_type,unshimmed_url,target{id},multi_share_end_card,media{source,image},subattachments}'
		];
		return $common_fields[$type];
	}

	/**
	 * A list of all API URL args depending on the FeedType
	 *
	 * @param string $type Call Type (Comment, Likes....).
	 * @param array  $settings Feed Settings.
	 * @since 4.0
	 *
	 * @return string
	 */
	public static function get_call_type_fields_args($type, $settings = [], $graph_args = [])
	{
		$feed_type_fields = [
			'timeline' => [
				'fields' => 'id,updated_time,message,message_tags,story,picture,full_picture,status_type,created_time,backdated_time,call_to_action,privacy' . (!isset($settings['storytags']) || $settings['storytags'] !== 'true' ? ',story_tags' : ''),
				'common_fields' => [
					'from',
					'attachments',
					'shares',
					'comments' => [
						'comments_limit' => 3
					]
				],
				'common_args' => [
					'token', 'limit', 'locale', 'ssl', 'date_range'
				]
			],
			'photos' => [
				'fields' => 'id,updated_time,created_time,link,picture,images{width,source},name' . (isset($settings['include_extras']) ? ',from{picture,id,name,link},likes.summary(true).limit(0),comments.summary(true).limit(0)' : ''),
				'common_args' => ['token', 'limit', 'locale', 'ssl', 'date_range', 'photos_type']
			],
			'videos' => [
				'fields' => 'published,source,updated_time,created_time,title,description,embed_html,format{picture},status' . (isset($settings['include_extras']) ? ',from{picture,id,name,link},length,likes.summary(true).limit(0),comments.summary(true).limit(0)' : ''),
				'common_args' => ['token', 'limit', 'locale', 'ssl', 'date_range']
			],
			'albums' => [
				'fields' =>  CFF_Graph_Url::get_albums_fields($settings) . (isset($settings['include_extras']) ? ',from{picture,id,name,link}' : ''),
				'common_args' => ['token', 'limit', 'locale', 'ssl', 'date_range']
			],
			'events' => [
				'fields' => 'id,name,attending_count,cover,start_time,end_time,event_times,timezone,place,description,ticket_uri,interested_count,updated_time,created_time'  . (isset($settings['include_extras']) ? ',owner' : ''),
				'common_args' => ['token', 'limit', 'locale', 'ssl', 'date_range', 'pastevents', 'upcomingevents']
			]
		];

		if ($settings['pagetype'] === 'group') {
			$feed_type_fields['timeline']['common_fields'] = array_merge(
				$feed_type_fields['timeline']['common_fields'],
				[
					'reactions_mixed',
					'reactions' => [
						'reactions_limit' => 0
					]
				]
			);
		} else {
			$feed_type_fields['timeline']['common_fields'] = array_merge(
				$feed_type_fields['timeline']['common_fields'],
				[
					'reactions_mixed',
					'likes' => [
						'likes_limit' => 0
					]
				]
			);
			$feed_type_fields['timeline']['common_fields'] = array_merge(
				$feed_type_fields['timeline']['common_fields'],
				[
					'reactions' => [
						'reactions_limit' => 0
					]
				]
			);
		}

		if (!isset($feed_type_fields[$type])) {
			return false;
		}
		$fields_string_arr = [];
		$fields_args_arr = [];
		foreach ($feed_type_fields[$type] as $key => $element) {
			if ($key === 'fields') {
				array_push($fields_string_arr, $element);
			}
			if ($key === 'common_fields') {
				foreach ($element as $ckey => $value) {
					$c_type = is_array($value) ? $ckey : $value;
					$c_value = is_array($value) ? $value : [];
					array_push($fields_string_arr, CFF_Graph_Url::get_common_fields($c_type, $c_value));
				}
			}
			if ($key === 'common_args') {
				foreach ($element as $argvalue) {
					$single_arg =  CFF_Graph_Url::get_common_args($argvalue, $graph_args);
					if (!empty($single_arg)) {
						array_push($fields_args_arr, $single_arg);
					}
				}
			}
		}
		$url_string = '';
		if (sizeof($fields_string_arr) > 0) {
			$url_string .= 'fields=' . implode(',', $fields_string_arr);
		}
		if (sizeof($fields_args_arr) > 0) {
			$url_string .= '&' . implode('&', $fields_args_arr);
		}
		return $url_string;
	}

	/**
	 * A List of Common Args
	 *
	 * @param string $type Call Type (Comment, Likes....).
	 * @param array  $settings Feed Settings.
	 * @since 4.0
	 *
	 * @return array
	 */
	public static function get_common_args($type, $args = [])
	{
		$token = is_array($args['token']) ? (isset($args['token'][$args['source_id']]) ? $args['token'][$args['source_id']] : '') : $args['token'];
		$api_call_args = [
			'token' 				=> 'access_token=' . $token,
			'limit' 				=> 'limit=' . $args['limit'],
			'locale' 				=> 'locale=' . $args['locale'],
			'photos_type' 			=> 'type=uploaded',
			'ssl' 					=> (is_ssl()) ? 'return_ssl_resources=true' : '',
			'date_range' 			=> CFF_Graph_Url::get_date_range($args),
			'pastevents'			=> isset($args['pastevents']) ? 'until=' . $args['pastevents'] : '',
			'upcomingevents'			=> isset($args['upcomingevents']) ? 'since=' . $args['upcomingevents'] : ''
		];
		return isset($args[$type]) && !empty($args[$type]) ? $api_call_args[$type] : '';
	}

	/**
	 * Album Fields
	 *
	 * @param array $settings Feed Settings.
	 * @since 4.0
	 *
	 * @return string
	 */
	public static function get_albums_fields($settings)
	{
		return $settings['pagetype'] === 'group' ? 'created_time,updated_time,name,count,cover_photo,link,modified,id' :
			'id,name,description,link,cover_photo{source,id},count,created_time,updated_time';
	}

	/**
	 * Get Date Range
	 *
	 * @param array $args
	 * @since 4.0
	 *
	 * @return string
	 */
	public static function get_date_range($args)
	{
		return  function_exists('cff_ext_date') && isset($args['date_from'], $args['date_until']) ?  cff_ext_date(strtotime($args['date_from']), strtotime($args['date_until'])) : '';
	}
}
