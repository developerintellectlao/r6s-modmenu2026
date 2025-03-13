<?php

/**
 * Custom facebook Plugin License Tier
 *
 * @since 1.0
 */

namespace CustomFacebookFeed;

use CustomFacebookFeed\License_Tier;

class CFF_License_Tier extends License_Tier
{
	/**
	 * This gets the license key
	 */
	public $license_key_option_name = 'cff_license_key';

	/**
	 * This gets the license status
	 */
	public $license_status_option_name = 'cff_license_status';

	/**
	 * This gets the license data
	 */
	public $license_data_option_name = 'cff_license_data';

	public $item_id_basic = 1722804; // put item id for the basic tier
	public $item_id_plus = 1722814; // put item id for the plus tier
	public $item_id_elite = 1722820; // put item id for the elite tier
	public $item_id_smash = 13384; // put item id for the elite tier
	public $item_id_all_access_elite = 1724078; // this is the all access item id, no need to change

	public $item_id_personal = 210; // put item id for the personal tier
	public $item_id_business = 299; // put item id for the business tier
	public $item_id_developer = 300; // put item id for the developer tier
	public $item_id_all_access = 789157; // this is the all access item id, no need to change


	public $license_tier_personal_name = 'personal'; // personal tier name
	public $license_tier_business_name = 'business'; // business tier name
	public $license_tier_developer_name = 'developer'; // developer tier name
	public $license_tier_smash_name = 'smash'; // smash tier name

	public $license_tier_basic_name = 'basic'; // basic tier name
	public $license_tier_plus_name = 'plus'; // plus tier name
	public $license_tier_elite_name = 'elite'; // elite tier name
	public $edd_item_name = WPW_SL_ITEM_NAME;

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * This defines the features list of the plugin
	 *
	 * @return void
	 */
	public function features_list()
	{
		$features_list = [
			'basic' => [
				// list of features for basic tier
				'unlimited_feeds',
				'pages_groups_feeds',
				'popup_lightbox',
				'meta_box',
				'post_layouts',
				'loadmore_button',
				'advanced_feed_layouts',
				'performance_optimization',
				'feed_customizer',
				'downtime_prevention_system'
			],
			'plus' => [
				// list of features for plus tier
				'photos_albums_feeds',
				'comments_replies',
				'video_feeds',
				'filter_posts',
				'feed_templates'
			],
			'elite' => [
				// list of features for elite tier
				'events_feeds',
				'feed_themes'
			],
			'smash' => [
				// list of features for smash tier
				'reviews_extension',
				'daterange_extension',
				'multifeed_extension',
				'albumsembed_extension',
				'featuredpost_extension',
			]
		];

		$this->plugin_features = $features_list;
	}

	/**
	 * This defines the features list of the plugin
	 *
	 * @return void
	 */
	public function legacy_features_list()
	{
		$legacy_features = [
			'personal' => [
				// list of features for personal tier
				'unlimited_feeds',
				'pages_groups_feeds',
				'popup_lightbox',
				'meta_box',
				'post_layouts',
				'loadmore_button',
				'advanced_feed_layouts',
				'performance_optimization',
				'feed_customizer',
				'downtime_prevention_system',
				'photos_albums_feeds',
				'comments_replies',
				'video_feeds',
				'filter_posts',
				'feed_templates',
				'events_feeds',
				'reviews_extension',
				'daterange_extension',
				'multifeed_extension',
				'albumsembed_extension',
				'featuredpost_extension',

				'feed_themes'// Should be removed

			],
			'business' => [
			],
			'developer' => [
				// 'feed_themes'
			],
			'smash' => [
			]
		];

		$this->legacy_features = $legacy_features;
	}
}
