<?php

namespace CustomFacebookFeed;

use CustomFacebookFeed\Builder\CFF_Feed_Saver;
use WpOrg\Requests\Requests;

class CFF_Events_Parser
{
	private $_feed_id;
	private $_feed;
	private $_settings;
	private $_ical_url;

	private $_transformed_settings;
	private $_event_source_name;
	private $_event_source_info;
	private $_events_list;
	private $_feed_sources;

	/**
	 * CFF_Events_Parser Construct
	 *
	 * @since 4.3.7
	 */
	public function __construct($feed_id)
	{
		$feed_id = str_replace('*', '', $feed_id);
		$this->_feed = new CFF_Feed_Saver($feed_id);
		$this->_transformed_settings =  $this->_feed->get_feed_settings();
		$this->_settings = $this->_feed->get_feed_options();

		if ($this->_settings['feedtype'] !== 'events') {
			return;
		}
		$this->_events_list = (object) [];
		$this->_feed_sources = $this->get_events_sources();
		$this->_ical_url = $this->check_feed_sources_ical_url_exists();
		if ($this->_ical_url !== false) {
			$this->get_events_data();
		}
	}

	/**
	 * Parse Events Id List from iCal Link
	 *
	 * @return array
	 *
	 * @since 4.3.7
	 */
	public function parse_events_ids()
	{
		if (!$this->_ical_url) {
			$this->update_need_ical_url(true);
			return;
		}
		$remote_ical_file = self::get_remote_file($this->_ical_url);
		if (!$remote_ical_file && !isset($remote_ical_file) || strpos($remote_ical_file, 'BEGIN:VCALENDAR') === false) {
			$this->update_need_ical_url(true);
			return;
		}


		$file_content =  str_replace(['\r', '\n', '\r\n'], '', $remote_ical_file);
		$events_ids = [];

		preg_match_all("'BEGIN:VEVENT(.*?)END:VEVENT'si", $file_content, $events_content_array);
		$this->_event_source_name = self::extract_page_name_name($file_content);
		foreach ($events_content_array[1] as $key => $single_event_content) {
			$pastdue_events = self::filter_event_date($single_event_content);
			$organizer_events = $this->filter_event_organizer($single_event_content);
			if ($pastdue_events && $organizer_events) {
				preg_match('`^URL:https://(.*)$`m', $single_event_content, $file_content_array);
				array_push(
					$events_ids,
					self::extract_event_id($file_content_array[1])
				);
			}
		}
		$events_ids = array_unique($events_ids);
		return $events_ids;
	}

	/**
	 * Parse Events Id List from iCal Link
	 *
	 * @return void
	 *
	 * @since 4.3.7
	 */
	public function update_need_ical_url($bool)
	{
		$this->_settings['need_ical_url'] = $bool;
		$this->_feed->set_data($this->_settings);
		$this->_feed->update_or_insert();
	}

	/**
	 * Extract Event ID form URL
	 *
	 * @return string
	 *
	 * @since 4.3.7
	 */
	public static function extract_event_id($event_url)
	{
		$event_url =  trim(str_replace(['\r', '\n', '\r\n'], '', $event_url));
		$event_url_parse = parse_url($event_url);

		$event_url = $event_url_parse['path'];
		$event_url = rtrim($event_url, "/");
		$event_id = explode('/', $event_url);
		$event_id = end($event_id);


		// Checking for recuring Event Time ID
		/*
		if (isset($event_url_parse['query']) && strpos($event_url_parse['query'], "event_time_id") !== false){
			$event_time = explode('=', $event_url_parse['query']);
			if (isset($event_time[1])){
				return [
					'event_id' => $event_id,
					'event_time_id' => $event_time[1]
				];
			}
		}
		*/

		return $event_id;
	}

	/**
	 * Extract Event Organizer Name
	 *
	 * @return string
	 *
	 * @since 4.3.7
	 */
	public static function extract_organizer_name($string)
	{
		$string =  trim(str_replace(['\r', '\n', '\r\n'], '', $string));
		$string = explode(':', $string);
		return isset($string[0]) ? stripslashes($string[0]) : false;
	}

	/**
	 * Extract Event Page Name
	 *
	 * @return string
	 *
	 * @since 4.3.7
	 */
	public static function extract_page_name_name($file_content)
	{
		preg_match('`^X-WR-CALNAME:(.*)$`m', $file_content, $file_content_array);
		if (!isset($file_content_array[1])) {
			return false;
		}
		return $file_content_array[1];
	}

	/**
	 * Get Events JSON Data
	 *
	 * @return string
	 *
	 * @since 4.3.7
	 */
	public function get_events_data()
	{
		$events_array = $this->parse_events_ids();
		$this->_event_source_info = $this->get_events_source_info();
		if (empty($this->_event_source_info['access_token'])) {
			return;
		}
		$access_token = $this->_event_source_info['access_token'];
		$post_index = 0;
		foreach ($events_array as $event) {
			$eventID = is_array($event) && isset($event['event_id']) ? $event['event_id'] : $event;
			$event_details = CFF_Shortcode::get_single_event_data($eventID, $access_token);
			if (isset($event_details->event_times) && isset($event['event_time_id'])) {
				$key = array_search($event['event_time_id'], array_column($event_details->event_times, 'id'));
				if (isset($event_details->event_times[$key])) {
					if (isset($event_details->start_time, $event_details->event_times[$key]->start_time)) {
						$event_details->start_time = $event_details->event_times[$key]->start_time;
					}
					if (isset($event_details->end_time, $event_details->event_times[$key]->end_time)) {
						$event_details->end_time = $event_details->event_times[$key]->end_time;
					}
				}
			}
			$this->_events_list->data[$post_index] = $event_details;
			$post_index++;
		}
		if (!isset($this->_events_list->data) || is_null($this->_events_list->data) || empty($this->_events_list->data)) {
			return [];
		}
		usort($this->_events_list->data, function ($event_1, $event_2) {
			return strcmp(strtotime($event_1->start_time), strtotime($event_2->start_time));
		});
	}

	/**
	 * Build Data
	 *
	 * @return string
	 *
	 * @since 4.3.7
	 */
	public function get_events_full_json_data()
	{
		return json_encode($this->_events_list, true);
	}

	/**
	 * Get Event Page Token
	 *
	 * @return string
	 *
	 * @since 4.3.7
	 */
	public function get_events_source_info()
	{
		if (!isset($this->_transformed_settings['sources'])) {
			return false;
		}
		foreach ($this->_transformed_settings['sources'] as $source) {
			if (strpos($this->_event_source_name, $source['username']) !== false && $source['privilege'] === 'events') {
				return $source;
			}
		}
	}

	/**
	 * Get Event Page Token
	 *
	 * @return string
	 *
	 * @since 4.3.7
	 */
	public function get_events_sources()
	{
		if (!isset($this->_transformed_settings['sources'])) {
			return false;
		}
		$sources = [];
		foreach ($this->_transformed_settings['sources'] as $source) {
			if ($source['privilege'] === 'events') {
				array_push($sources, $source);
			}
		}

		return $sources;
	}

	/**
	 * Filter Event Date
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public static function filter_event_date($file_content)
	{
		preg_match('`^DTSTART:(.*)$`m', $file_content, $file_content_array);
		if (!isset($file_content_array[1])) {
			return false;
		}
		$date = str_replace(['\r', '\n', '\r\n'], '', $file_content_array[1]) ;
		$time = new \DateTime($date);
		$event_timestamp = $time->getTimestamp();
		return $event_timestamp > time();
	}

	/**
	 * Filter Event Organizer
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public function filter_event_organizer($file_content)
	{
		preg_match('`^ORGANIZER;CN=(.*)$`m', $file_content, $file_content_array);
		$organizer =  self::extract_organizer_name($file_content_array[1]);
		if (!isset($file_content_array[1]) || is_null($organizer)) {
			return false;
		}
		$i = 0;
		foreach ($this->_transformed_settings['sources'] as $source) {
			if (strpos(trim($organizer), trim($source['username'])) !== false) {
				$i++;
			}
		}
		return $i > 0;
	}

	/**
	 * Check All Source iCal YRLS
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public function check_feed_sources_ical_url_exists()
	{
		$resp = false;
		foreach ($this->_feed_sources as $source) {
			$resp = self::get_ical_url($source['account_id']);
		}

		return $resp;
	}

	public static function get_remote_file($url)
	{
		$event_url_parse = parse_url($url);
		$event_url = 'https://www.facebook.com/events/ical/upcoming/?' . $event_url_parse['query'];
		$response = Requests::get($event_url);
		if ($response->status_code !== 200) {
			return false;
		}
		return $response->body;
	}


	/**
	 * Check iCal URL is VAlid
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public static function check_ical_url($ical_url)
	{
		// $remote_ical_file = wp_remote_get($ical_url);
		$remote_ical_file = self::get_remote_file($ical_url);
		if (!$remote_ical_file && !isset($remote_ical_file) || strpos($remote_ical_file, 'BEGIN:VCALENDAR') === false) {
			return false;
		}
		return true;
	}

	/**
	 * Update iCal URL
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public static function update_ical_url($page_id, $ical_url)
	{
		$cff_ical_urls = get_option('cff_ical_urls', []);
		$cff_ical_urls[$page_id] = $ical_url;
		update_option('cff_ical_urls', $cff_ical_urls);
		return $cff_ical_urls;
	}

	/**
	 * Delete iCal URL
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public static function delete_ical_url($page_id)
	{
		$cff_ical_urls = get_option('cff_ical_urls', []);
		unset($cff_ical_urls[$page_id]);
		return $cff_ical_urls;
	}

	/**
	 * Delete iCal URL
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public static function check_ical_url_exists($page_id)
	{
		$cff_ical_urls = get_option('cff_ical_urls', []);
		return isset($cff_ical_urls[$page_id]) && !empty($cff_ical_urls[$page_id]);
	}



	/**
	 * Update iCal URL
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public static function get_ical_url($page_id)
	{
		$cff_ical_urls = get_option('cff_ical_urls', []);
		return isset($cff_ical_urls[$page_id]) && !empty($cff_ical_urls[$page_id]) ? $cff_ical_urls[$page_id] : false;
	}



	/**
	 * Filter Event Organizer
	 *
	 * @return boolean
	 *
	 * @since 4.3.7
	 */
	public static function filter_event_organizer_support_tool($file_content, $sources)
	{
		preg_match('`^ORGANIZER;CN=(.*)$`m', $file_content, $file_content_array);
		$organizer =  self::extract_organizer_name($file_content_array[1]);
		if (!isset($file_content_array[1]) || is_null($organizer)) {
			return false;
		}
		$i = 0;
		foreach ($sources as $source) {
			if (strpos(trim($organizer), trim($source['username'])) !== false) {
				$i++;
			}
		}
		return $i > 0;
	}
	/**
	 * Get Events for Support Tool
	 *
	 * @return array
	 *
	 * @since 4.3.7
	 */
	public static function events_list_support_tool($ical_url, $source)
	{
		$remote_ical_file = self::get_remote_file($ical_url);
		if (!$remote_ical_file && !isset($remote_ical_file) || strpos($remote_ical_file, 'BEGIN:VCALENDAR') === false) {
			return false;
		}

		$file_content =  str_replace(['\r', '\n', '\r\n'], '', $remote_ical_file);
		$events_ids = [];
		$_events_list = (object) [];

		preg_match_all("'BEGIN:VEVENT(.*?)END:VEVENT'si", $file_content, $events_content_array);
		$event_source_name = self::extract_page_name_name($file_content);
		foreach ($events_content_array[1] as $key => $single_event_content) {
			$pastdue_events = self::filter_event_date($single_event_content);
			$organizer_events = self::filter_event_organizer_support_tool($single_event_content, [$source]);
			if ($pastdue_events && $organizer_events) {
				preg_match('`^URL:https://(.*)$`m', $single_event_content, $file_content_array);
				array_push(
					$events_ids,
					self::extract_event_id($file_content_array[1])
				);
			}
		}

		$events_ids = array_unique($events_ids);

		$access_token = $source['token'];
		$post_index = 0;
		foreach ($events_ids as $event) {
			$eventID = is_array($event) && isset($event['event_id']) ? $event['event_id'] : $event;
			$event_details = CFF_Shortcode::get_single_event_data($eventID, $access_token);
			if (isset($event_details->event_times) && isset($event['event_time_id'])) {
				$key = array_search($event['event_time_id'], array_column($event_details->event_times, 'id'));
				if (isset($event_details->event_times[$key])) {
					if (isset($event_details->start_time, $event_details->event_times[$key]->start_time)) {
						$event_details->start_time = $event_details->event_times[$key]->start_time;
					}
					if (isset($event_details->end_time, $event_details->event_times[$key]->end_time)) {
						$event_details->end_time = $event_details->event_times[$key]->end_time;
					}
				}
			}
			$_events_list->data[$post_index] = $event_details;
			$post_index++;
		}
		if (!isset($_events_list->data) || is_null($_events_list->data) || empty($_events_list->data)) {
			return [];
		}
		usort($_events_list->data, function ($event_1, $event_2) {
			return strcmp(strtotime($event_1->start_time), strtotime($event_2->start_time));
		});

		return wp_json_encode($_events_list);
	}
}
