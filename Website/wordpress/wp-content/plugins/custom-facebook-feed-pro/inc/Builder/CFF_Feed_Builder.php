<?php

/**
 * Custom Facebook Feed Builder
 *
 * @since 4.0
 */

namespace CustomFacebookFeed\Builder;

use CustomFacebookFeed\Builder\Tabs\CFF_Styling_Tab;
use CustomFacebookFeed\CFF_License_Tier;
use CustomFacebookFeed\CFF_Response;
use CustomFacebookFeed\Custom_Facebook_Feed_Pro;
use CustomFacebookFeed\Helpers\Util;
use CustomFacebookFeed\SB_Facebook_Data_Encryption;

use function DI\value;

class CFF_Feed_Builder
{
	/**
	 * Constructor.
	 *
	 * @since 4.0
	 */
	public function __construct()
	{
		$this->init();
	}


	/**
	 * Init the Builder.
	 *
	 * @since 4.0
	 */
	public function init()
	{

		if (is_admin()) {
			add_action('admin_menu', [$this, 'register_menu']);
			// add ajax listeners
			CFF_Feed_Saver_Manager::hooks();
			CFF_Source::hooks();
			CFF_Feed_Builder::hooks();
		}
	}

	/**
	 * Mostly AJAX related hooks
	 *
	 * @since 4.0
	 */
	public static function hooks()
	{
		add_action('wp_ajax_cff_dismiss_onboarding', array( 'CustomFacebookFeed\Builder\CFF_Feed_Builder', 'after_dismiss_onboarding' ));
		add_action('wp_ajax_sb_other_plugins_modal', array( 'CustomFacebookFeed\Builder\CFF_Feed_Builder', 'sb_other_plugins_modal' ));
	}

	/**
	 * Check users capabilities and maybe nonce before AJAX actions
	 *
	 * @param $check_nonce
	 * @param string      $action
	 *
	 * @since 4.0.6
	 */
	public static function check_privilege($check_nonce, $action = 'cff-admin')
	{
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);

		if (! current_user_can($cap)) {
			wp_die('You did not do this the right way!');
		}

		if ($check_nonce) {
			$nonce = ! empty($_POST[ $check_nonce ]) ? sanitize_text_field(wp_unslash($_POST[ $check_nonce ])) : false;

			if (! wp_verify_nonce($nonce, $action)) {
				wp_die('You did not do this the right way!');
			}
		}
	}

	/**
	 * Used to dismiss onboarding using AJAX
	 *
	 * @since 4.0
	 */
	public static function after_dismiss_onboarding()
	{

		check_ajax_referer('cff-admin', 'nonce');

		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);

		if (current_user_can($cap)) {
			$type = 'newuser';
			if (isset($_POST['was_active'])) {
				$type = sanitize_text_field(wp_unslash($_POST['was_active']));
			}
			CFF_Feed_Builder::update_onboarding_meta('dismissed', $type);
		}
		wp_die();
	}


	/**
	 * Display modal to install other plugins
	 *
	 * @since 4.4
	 */
	public static function sb_other_plugins_modal()
	{
		check_ajax_referer('cff_nonce', 'cff_nonce');

		if (! current_user_can('activate_plugins') || ! current_user_can('install_plugins')) {
			wp_send_json_error();
		}

		$plugin = isset($_POST['plugin']) ? sanitize_text_field($_POST['plugin']) : '';
		$sb_other_plugins = self::install_plugins_popup();
		$plugin = $sb_other_plugins[ $plugin ];

		// Build the content for modals
		$output = '<div class="cff-fb-source-popup cff-fb-popup-inside cff-install-plugin-modal">
		<div class="cff-fb-popup-cls">' . self::builder_svg_icons('close') . '</div>
		<div class="cff-install-plugin-body cff-fb-fs">
		<div class="cff-install-plugin-header">
		<div class="sb-plugin-image">' . $plugin['svgIcon'] . '</div>
		<div class="sb-plugin-name">
		<h3>' . $plugin['name'] . '<span>Free</span></h3>
		<p><span class="sb-author-logo">
		' . self::builder_svg_icons('smash-logo') . '
		</span>
		<span class="sb-author-name">' . $plugin['author'] . '</span>
		</p></div></div>
		<div class="cff-install-plugin-content">
		<p>' . $plugin['description'] . '</p>';

		$plugin_install_data = array(
			'step' => 'install',
			'action' => 'cff_install_addon',
			'nonce' => wp_create_nonce('cff-admin'),
			'plugin' => $plugin['plugin'],
			'download_plugin' => $plugin['download_plugin'],
		);

		if (! $plugin['installed']) {
			$output .= sprintf(
				"<button class='cff-install-plugin-btn cff-btn-orange' id='cff_install_op_btn' data-plugin-atts='%s'>%s</button></div></div></div>",
				json_encode($plugin_install_data),
				__('Install', 'custom-facebook-feed')
			);
		}
		if ($plugin['installed'] && ! $plugin['activated']) {
			$plugin_install_data['step'] = 'activate';
			$plugin_install_data['action'] = 'cff_activate_addon';
			$output .= sprintf(
				"<button class='cff-install-plugin-btn cff-btn-orange' id='cff_install_op_btn' data-plugin-atts='%s'>%s</button></div></div></div>",
				json_encode($plugin_install_data),
				__('Activate', 'custom-facebook-feed')
			);
		}
		if ($plugin['installed'] && $plugin['activated']) {
			$output .= sprintf(
				"<button class='cff-install-plugin-btn cff-btn-orange' id='cff_install_op_btn' disabled='disabled'>%s</button></div></div></div>",
				__('Plugin installed & activated', 'custom-facebook-feed')
			);
		}

		$response = new CFF_Response(true, array(
			'output' => $output
		));
		$response->send();
	}

	/**
	 * Register Menu.
	 *
	 * @since 4.0
	 */
	public function register_menu()
	{
		$cap = current_user_can('manage_custom_facebook_feed_options') ? 'manage_custom_facebook_feed_options' : 'manage_options';
		$cap = apply_filters('cff_settings_pages_capability', $cap);

		$feed_builder = add_submenu_page(
			'cff-top',
			__('All Feeds', 'custom-facebook-feed'),
			__('All Feeds', 'custom-facebook-feed'),
			$cap,
			'cff-feed-builder',
			[$this, 'feed_builder'],
			0
		);
		add_action('load-' . $feed_builder, [$this,'builder_enqueue_admin_scripts']);
	}

	/**
	 * Enqueue Builder CSS & Script.
	 *
	 * Loads only for builder pages
	 *
	 * @since 4.0
	 */
	public function builder_enqueue_admin_scripts()
	{
		if (! Util::currentPageIs('cff-feed-builder')) {
			return;
		}

		$license_key = null;
		if (get_option('cff_license_key')) {
			$license_key = get_option('cff_license_key');
		}

		$license_tier = new CFF_License_Tier();
		$license_tier_features = $license_tier->tier_features();

		$newly_retrieved_source_connection_data = CFF_Source::maybe_source_connection_data();
		$active_extensions = $this->get_active_extensions();
		$installed_plugins = get_plugins();
		$cff_builder = array(
			'ajax_handler'		=> 	admin_url('admin-ajax.php'),
			'pluginType' 		=> $this->get_plugin_type(),
			'builderUrl'		=> admin_url('admin.php?page=cff-feed-builder'),
			'supportPageUrl'    => admin_url('admin.php?page=cff-support'),
			'nonce'				=> wp_create_nonce('cff-admin'),
			'adminPostURL'		=> 	admin_url('post.php'),
			'widgetsPageURL'	=> 	admin_url('widgets.php'),
			'groupSourcesNumber' => CFF_Db::check_group_source(),
			'iCalURLs'			=> 	get_option('cff_ical_urls', []),
			'genericText'       => self::get_generic_text(),
			'shouldDisableProFeatures' => cff_should_disable_pro(),
			'license_tier_features' => $license_tier_features,
			'cffLicenseNoticeActive' => cff_license_notice_active() ? true : false,
			'cffLicenseInactiveState' => cff_license_inactive_state() ? true : false,
			'welcomeScreen' => array(
				'mainHeading' => __('All Feeds', 'custom-facebook-feed'),
				'createFeed' => __('Create your Feed', 'custom-facebook-feed'),
				'createFeedDescription' => __('Select your Facebook page or group and choose a feed type', 'custom-facebook-feed'),
				'customizeFeed' => __('Customize your feed type', 'custom-facebook-feed'),
				'customizeFeedDescription' => __('Choose layouts, color schemes, filters and more', 'custom-facebook-feed'),
				'embedFeed' => __('Embed your feed', 'custom-facebook-feed'),
				'embedFeedDescription' => __('Easily add the feed anywhere on your website', 'custom-facebook-feed'),
				'customizeImgPath' => CFF_BUILDER_URL . 'assets/img/welcome-1.png',
				'embedImgPath' => CFF_BUILDER_URL . 'assets/img/welcome-2.png',
			),
			'selectFeedTypeScreen' => array(
				'mainHeading' => __('Create a Facebook Feed', 'custom-facebook-feed'),
				'feedTypeHeading' => __('Select Feed Type', 'custom-facebook-feed'),
				'advancedHeading' => __('Advanced Feed Types', 'custom-facebook-feed'),
				'updateHeading' => __('Update Feed Type', 'custom-facebook-feed'),
			),
			'selectFeedTemplateScreen' => array(
				'feedTemplateHeading' => __('Start with a template', 'custom-facebook-feed'),
				'feedTemplateDescription' => __('Select a starting point for your feed. You can customize this later.', 'custom-facebook-feed'),
				'updateHeading' => __('Select another template', 'custom-facebook-feed'),
				'updateHeadingWarning' => __('Changing a template will override your layout, header and button settings', 'custom-facebook-feed')
			),
			'selectFeedThemeScreen' => array(
				'feedThemeHeading' => __('Start with a Theme', 'custom-facebook-feed'),
				'feedThemeDescription' => __('Select a starting point for your feed. You can customize this later.', 'custom-facebook-feed'),
				'updateHeading' => __('Select another Theme', 'custom-facebook-feed'),
				'updateHeadingWarning' => __('Changing a theme will override your layout, header and button settings', 'custom-facebook-feed')
			),
			'selectSourceScreen' => self::select_source_screen_text(),
			'extensionsPopup' => self::get_extensions_popup(),
			'allFeedsScreen' => array(
				'mainHeading' => __('All Feeds', 'custom-facebook-feed'),
				'columns' => array(
					'nameText' => __('Name', 'custom-facebook-feed'),
					'shortcodeText' => __('Shortcode', 'custom-facebook-feed'),
					'instancesText' => __('Instances', 'custom-facebook-feed'),
					'actionsText' => __('Actions', 'custom-facebook-feed'),
				),
				'bulkActions' => __('Bulk Actions', 'custom-facebook-feed'),
				'legacyFeeds' => array(
					'heading' => __('Legacy Feeds', 'custom-facebook-feed'),
					'toolTip' => __('What are Legacy Feeds?', 'custom-facebook-feed'),
					'toolTipExpanded' => array(
						__('Legacy feeds are older feeds from before the version 4 update. You can edit settings for these feeds by using the "Settings" button to the right. These settings will apply to all legacy feeds, just like the settings before version 4, and work in the same way that they used to.', 'custom-facebook-feed'),
						__('You can also create a new feed, which will now have it\'s own individual settings. Modifying settings for new feeds will not affect other feeds.', 'custom-facebook-feed'),
					),
					'toolTipExpandedAction' => array(
						__('Legacy feeds represent shortcodes of old feeds found on your website before <br/>the version 4 update.', 'custom-facebook-feed'),
						__('To edit Legacy feed settings, you will need to use the "Settings" button above <br/>or edit their shortcode settings directly. To delete them, simply remove the <br/>shortcode wherever it is being used on your site.', 'custom-facebook-feed'),
					),
					'show' => __('Show Legacy Feeds', 'custom-facebook-feed'),
					'hide' => __('Hide Legacy Feeds', 'custom-facebook-feed'),
				),
				'socialWallLinks' => CFF_Feed_Builder::get_social_wall_links(),
				'onboarding' => $this->get_onboarding_text()
			),
			'addFeaturedPostScreen' => array(
				'mainHeading' => __('Add Featured Post', 'custom-facebook-feed'),
				'description' => __('Add the URL or ID of the post you want to feature', 'custom-facebook-feed'),
				'couldNotFetch' => __('Could not fetch post preview', 'custom-facebook-feed'),
				'URLorID' => __('Post URL or ID', 'custom-facebook-feed'),
				'unable' => sprintf(__('Unable to retrieve post. Please make sure the link is correct. See %shere%s for more help.', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/how-to-use-the-featured-post-extension-to-display-a-specific-facebook-post/?facebook" target="_blank" rel="noopener">', '</a>'),
				'unablePreview' => __('Unable to retrieve post. Please make sure the link<br/>entered is correct.', 'custom-facebook-feed'),
				'preview' => __('Post Preview', 'custom-facebook-feed'),
				'previewDescription' => __('Once you enter a post URL or ID, click next and the preview will show up here', 'custom-facebook-feed'),
				'previewHeading' => __('Add a Featured Post', 'custom-facebook-feed'),
				'previewText' => __('To add a featured post, add it\'s URL or ID in the<br/>"Featured Post URL or ID" field on the left sidebar.', 'custom-facebook-feed'),
			),
			'addVideosPostScreen' => array(
				'mainHeading' => __('Customize Video Feed', 'custom-facebook-feed'),
				'description' => __('Add the URL or ID of the post you want to feature', 'custom-facebook-feed'),
				'sections' => array(
					array(
						'id' => 'all',
						'heading' => __('Show all Videos', 'custom-facebook-feed'),
						'description' => __('I want to show all the videos from my Facebook page or group "Videos" page', 'custom-facebook-feed'),
					),
					array(
						'id' => 'playlist',
						'heading' => __('Show from a specific Playlist', 'custom-facebook-feed'),
						'description' => __('I want to show videos from a specific playlist', 'custom-facebook-feed'),
					)
				),
				'inputLabel' => __('Add Playlist URL', 'custom-facebook-feed'),
				'inputDescription' => __('Add your Facebook playlist URL here. It should look something like: https://www.facebook.com/watch/100066924416370/260988882604892', 'custom-facebook-feed'),
				'errorMessage' => __("Couldn't fetch the playlist, please make sure it's a valid URL", 'custom-facebook-feed'),
			),
			'addFeaturedAlbumScreen' => array(
				'mainHeading' => __('Choose Album to Embed', 'custom-facebook-feed'),
				'description' => __('Add the URL or ID of the album you want to feature', 'custom-facebook-feed'),
				'couldNotFetch' => __('Could not fetch album preview', 'custom-facebook-feed'),
				'URLorID' => __('Album URL or ID', 'custom-facebook-feed'),
				'unable' => sprintf(__('Unable to retrieve album. Please make sure the link is correct. See %shere%s for more help.', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/how-to-use-the-album-extension-to-display-photos-from-a-specific-facebook-album/?facebook" target="_blank" rel="noopener">', '</a>'),
				'unablePreview' => __('Unable to retrieve album. Please make sure the link<br/>entered is correct.', 'custom-facebook-feed'),
				'preview' => __('Album Preview', 'custom-facebook-feed'),
				'previewDescription' => __('Once you enter an album URL or ID, click next and the preview will show up here', 'custom-facebook-feed'),
				'previewHeading' => __('Add an Album', 'custom-facebook-feed'),
				'previewText' => __('To add a single album, add it\'s URL or ID in the "Album<br/>URL or ID" field on the left sidebar.', 'custom-facebook-feed'),
			),
			'addEventiCalUrlScreen' => array(
				'mainHeading' => __('Add Events iCal Url', 'custom-facebook-feed'),
				'description' => sprintf(__('Add the iCal URL of the page events you want to add. %sGet your Link here%s.', 'custom-facebook-feed'), '<a href="https://www.facebook.com/events/calendar" target="_blank" rel="noopener">', '</a>'),
				__('Add the iCal URL of the page events you want to add', 'custom-facebook-feed'),

				'couldNotFetch' => __('Could not fetch album preview', 'custom-facebook-feed'),
				'URLorID' => __('iCal URL', 'custom-facebook-feed'),
				'unable' => sprintf(__('Unable to retrieve feeds from the iCal URL provided. Please make sure the link is correct. See %shere%s for more help.', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/how-to-use-the-album-extension-to-display-photos-from-a-specific-facebook-album/?facebook" target="_blank" rel="noopener">', '</a>'),
			),

			'mainFooterScreen' => array(
				'heading' => sprintf(__('Upgrade to the %sAll Access Bundle%s to get all of our Pro Plugins', 'custom-facebook-feed'), '<strong>', '</strong>'),
				'description' => __('Includes all Smash Balloon plugins for one low price: Instagram, Facebook, Twitter, YouTube, and Social Wall', 'custom-facebook-feed'),
				'promo' => sprintf(__('%sBonus%s Lite users get %s50&#37; Off%s automatically applied at checkout', 'custom-facebook-feed'), '<span class="cff-bld-ft-bns">', '</span>', '<strong>', '</strong>'),
			),
			'embedPopupScreen' => array(
				'heading' => __('Embed Feed', 'custom-facebook-feed'),
				'description' => __('Add the unique shortcode to any page, post, or widget:', 'custom-facebook-feed'),
				'description_2' => __('Or use the built in WordPress block or widget', 'custom-facebook-feed'),
				'addPage' => __('Add to a Page', 'custom-facebook-feed'),
				'addWidget' => __('Add to a Widget', 'custom-facebook-feed'),
				'selectPage' => __('Select Page', 'custom-facebook-feed'),
			),
			'dialogBoxPopupScreen'  => array(
				'deleteSourceCustomizer' => array(
					'heading' =>  __('Delete "#"?', 'custom-facebook-feed'),
					'description' => __('You are going to delete this source. To retrieve it, you will need to add it again. Are you sure you want to continue?', 'custom-facebook-feed'),
				),
				'deleteSingleFeed' => array(
					'heading' =>  __('Delete "#"?', 'custom-facebook-feed'),
					'description' => __('You are going to delete this feed. You will lose all the settings. Are you sure you want to continue?', 'custom-facebook-feed'),
				),
				'deleteMultipleFeeds' => array(
					'heading' =>  __('Delete Feeds?', 'custom-facebook-feed'),
					'description' => __('You are going to delete these feeds. You will lose all the settings. Are you sure you want to continue?', 'custom-facebook-feed'),
				),
				'backAllToFeed' => array(
					'heading' =>  __('Are you Sure?', 'custom-facebook-feed'),
					'description' => __('Are you sure you want to leave this page, all unsaved settings will be lost, please make sure to save before leaving.', 'custom-facebook-feed'),
				)
			),
			'translatedText' => $this->get_translated_text(),
			'socialShareLink' => $this->get_social_share_link(),
			'dummyLightBoxData' => $this->get_dummy_lightbox_data(),
			'feedTypes' => $this->get_feed_types(),
			'feedTemplates' => $this->get_feed_templates(),
			'feedThemes' => $this->get_feed_themes(),
			'advancedFeedTypes' => $this->get_advanced_feed_types($active_extensions),
			'feeds' => CFF_Feed_Builder::get_feed_list(),
			'itemsPerPage' => CFF_Db::RESULTS_PER_PAGE,
			'feedsCount' => CFF_Db::feeds_count(),
			'sources' => self::get_source_list(),
			'links' => self::get_links_with_utm(),
			'legacyFeeds' => $this->get_legacy_feed_list(),
			'activeExtensions' => $active_extensions,
			'pluginsInfo' => [
				'social_wall' => [
					'installed' => isset($installed_plugins['social-wall/social-wall.php']) ? true : false,
					'activated' => is_plugin_active('social-wall/social-wall.php'),
					'settingsPage' => admin_url('admin.php?page=sbsw'),
				]
			],
			'demoUrl' 		=> sprintf('https://smashballoon.com/extensions/reviews/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=reviews-modal&utm_content=learn-more'),
			'buyUrl' 		=> sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=reviews&utm_content=upgrade', $license_key),
			'featuredpost' => array(
				'heading' 		=> __('Upgrade your License to display Single Featured Posts', 'custom-facebook-feed'),
				'description' 	=> __('Easily highlight any single post or event from your Facebook page.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display feed of your Facebook reviews', 'custom-facebook-feed'),
						__('Create engaging carousel feeds', 'custom-facebook-feed'),
						__('Combine feeds from multiple accounts', 'custom-facebook-feed'),
						__('Filter your feeds using a date range', 'custom-facebook-feed'),
						__('Embed single Facebook posts', 'custom-facebook-feed'),
						__('Embed photos from a single album', 'custom-facebook-feed'),
					]
				],
				'img' 			=> self::builder_svg_icons('plugins-info.featuredpost'),
				'demoUrl' 		=> 'https://smashballoon.com/extensions/featured-post?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=featured-post&utm_content=learn-more',
				'buyUrl' 		=> sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=featured-post&utm_content=upgrade', $license_key)
			),
			'singlealbum' => array(
				'heading' 		=> __('Upgrade your License to embed Single Album Feeds', 'custom-facebook-feed'),
				'description' 	=> __('Embed photos from inside any single album from your Facebook Page, and display them in several attractive layouts.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display feed of your Facebook reviews', 'custom-facebook-feed'),
						__('Create engaging carousel feeds', 'custom-facebook-feed'),
						__('Combine feeds from multiple accounts', 'custom-facebook-feed'),
						__('Filter your feeds using a date range', 'custom-facebook-feed'),
						__('Embed single Facebook posts', 'custom-facebook-feed'),
						__('Embed photos from a single album', 'custom-facebook-feed'),
					]
				],
				'img' 			=> self::builder_svg_icons('plugins-info.singlealbum'),
				'demoUrl' 		=> 'https://smashballoon.com/extensions/album/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=album&utm_content=learn-more',
				'buyUrl' 		=> sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=album&utm_content=upgrade', $license_key)
			),
			'carousel' => array(
				'heading' 		=> __('Upgrade your License to get Carousel layout', 'custom-facebook-feed'),
				'description' 	=> __('The Carousel layout is perfect for when you either want to display a lot of content in a small space or want to catch your visitors attention.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('All advanced feed types', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'img' 			=> self::builder_svg_icons('plugins-info.carousel'),
				'demoUrl' 		=> 'https://smashballoon.com/extensions/carousel/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=layout&utm_content=carousel',
				'buyUrl' 		=> sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=customizer&utm_medium=layout&utm_content=carousel', $license_key),
				'socialwall' => array(
					// Combine all your social media channels into one Social Wall
					'heading' 		=> __('<span class="sb-social-wall">Combine all your social media channels into one', 'custom-facebook-feed') . ' <span>' . __('Social Wall', 'custom-facebook-feed') . '</span></span>',
					'description' 	=> __('<span class="sb-social-wall">A dash of Instagram, a sprinkle of Facebook, a spoonful of Twitter, and a dollop of YouTube, all in the same feed.</span>', 'custom-facebook-feed'),
					'popupContentBtn' 	=> '<div class="cff-fb-extpp-lite-btn">' . self::builder_svg_icons('tag') . __('Facebook Pro users get 50% OFF', 'custom-facebook-feed') . '</div>',
					'img' 			=> self::builder_svg_icons('extensions-popup.socialwall'),
					'demoUrl' 		=> 'https://smashballoon.com/social-wall/demo/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=social-wall&utm_content=learn-more',
					'buyUrl' 		=> sprintf('https://smashballoon.com/social-wall/pricing/?license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=social-wall&utm_content=upgrade', $license_key),
					'bullets'       => [
						'heading' => __('Upgrade to the All Access Bundle and get:', 'custom-facebook-feed'),
						'content' => [
							__('Custom Facebook Feed Pro', 'custom-facebook-feed'),
							__('All Pro Facebook Extensions', 'custom-facebook-feed'),
							__('Custom Twitter Feeds Pro', 'custom-facebook-feed'),
							__('Instagram Feed Pro', 'custom-facebook-feed'),
							__('YouTube Feeds Pro', 'custom-facebook-feed'),
							__('Social Wall Pro', 'custom-facebook-feed'),
						]
					],
				),
				'date_range' => array(
					'heading' 		=> __('Upgrade your License to filter by Date Range', 'custom-facebook-feed'),
					'description' 	=> __('Filter posts based on a start and end date. Use relative dates (such as "1 month ago" or "now") or absolute dates (such as 01/01/21) to curate your feeds to specific date ranges.', 'custom-facebook-feed'),
					'bullets'       => [
						'heading' => __('And much more!', 'custom-facebook-feed'),
						'content' => [
							__('Display images & videos in posts', 'custom-facebook-feed'),
							__('Filter Posts', 'custom-facebook-feed'),
							__('Multiple post layout options', 'custom-facebook-feed'),
							__('Show likes, reactions, comments', 'custom-facebook-feed'),
							__('Popup photo/video lightbox', 'custom-facebook-feed'),
							__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
							__('All advanced feed types', 'custom-facebook-feed'),
							__('Ability to load more posts', 'custom-facebook-feed'),
							__('30 day money back guarantee', 'custom-facebook-feed'),
						]
					],
					'img' 			=> self::builder_svg_icons('extensions-popup.date_range'),
					'demoUrl' 		=> 'https://smashballoon.com/extensions/date-range/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=filters&utm_content=date-range',
					'buyUrl' 		=> sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=customizer&utm_medium=filters&utm_content=date-range', $license_key)
				)
			),
			'allFeedsScreen' => array(
				'mainHeading' => __('All Feeds', 'custom-facebook-feed'),
				'columns' => array(
					'nameText' => __('Name', 'custom-facebook-feed'),
					'shortcodeText' => __('Shortcode', 'custom-facebook-feed'),
					'instancesText' => __('Instances', 'custom-facebook-feed'),
					'actionsText' => __('Actions', 'custom-facebook-feed'),
				),
				'bulkActions' => __('Bulk Actions', 'custom-facebook-feed'),
				'legacyFeeds' => array(
					'heading' => __('Legacy Feeds', 'custom-facebook-feed'),
					'toolTip' => __('What are Legacy Feeds?', 'custom-facebook-feed'),
					'toolTipExpanded' => array(
						__('Legacy feeds are older feeds from before the version 4 update. You can edit settings for these feeds by using the "Settings" button to the right. These settings will apply to all legacy feeds, just like the settings before version 4, and work in the same way that they used to.', 'custom-facebook-feed'),
						__('You can also create a new feed, which will now have it\'s own individual settings. Modifying settings for new feeds will not affect other feeds.', 'custom-facebook-feed'),
					),
					'toolTipExpandedAction' => array(
						__('Legacy feeds represent shortcodes of old feeds found on your website before <br/>the version 4 update.', 'custom-facebook-feed'),
						__('To edit Legacy feed settings, you will need to use the "Settings" button above <br/>or edit their shortcode settings directly. To delete them, simply remove the <br/>shortcode wherever it is being used on your site.', 'custom-facebook-feed'),
					),
					'show' => __('Show Legacy Feeds', 'custom-facebook-feed'),
					'hide' => __('Hide Legacy Feeds', 'custom-facebook-feed'),
				),
				'socialWallLinks' => CFF_Feed_Builder::get_social_wall_links(),
				'onboarding' => $this->get_onboarding_text()
			),
			'addFeaturedPostScreen' => array(
				'mainHeading' => __('Add Featured Post', 'custom-facebook-feed'),
				'description' => __('Add the URL or ID of the post you want to feature', 'custom-facebook-feed'),
				'couldNotFetch' => __('Could not fetch post preview', 'custom-facebook-feed'),
				'URLorID' => __('Post URL or ID', 'custom-facebook-feed'),
				'unable' => sprintf(__('Unable to retrieve post. Please make sure the link is correct. See %shere%s for more help.', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/how-to-use-the-featured-post-extension-to-display-a-specific-facebook-post/?facebook" target="_blank" rel="noopener">', '</a>'),
				'unablePreview' => __('Unable to retrieve post. Please make sure the link<br/>entered is correct.', 'custom-facebook-feed'),
				'preview' => __('Post Preview', 'custom-facebook-feed'),
				'previewDescription' => __('Once you enter a post URL or ID, click next and the preview will show up here', 'custom-facebook-feed'),
				'previewHeading' => __('Add a Featured Post', 'custom-facebook-feed'),
				'previewText' => __('To add a featured post, add it\'s URL or ID in the<br/>"Featured Post URL or ID" field on the left sidebar.', 'custom-facebook-feed'),
			),
			'addVideosPostScreen' => array(
				'mainHeading' => __('Customize Video Feed', 'custom-facebook-feed'),
				'description' => __('Add the URL or ID of the post you want to feature', 'custom-facebook-feed'),
				'sections' => array(
					array(
						'id' => 'all',
						'heading' => __('Show all Videos', 'custom-facebook-feed'),
						'description' => __('I want to show all the videos from my Facebook page or group "Videos" page', 'custom-facebook-feed'),
					),
					array(
						'id' => 'playlist',
						'heading' => __('Show from a specific Playlist', 'custom-facebook-feed'),
						'description' => __('I want to show videos from a specific playlist', 'custom-facebook-feed'),
					)
				),
				'inputLabel' => __('Add Playlist URL', 'custom-facebook-feed'),
				'inputDescription' => __('Add your Facebook playlist URL here. It should look something like: https://www.facebook.com/watch/100066924416370/260988882604892', 'custom-facebook-feed'),
				'errorMessage' => __("Couldn't fetch the playlist, please make sure it's a valid URL", 'custom-facebook-feed'),
			),
			'addFeaturedAlbumScreen' => array(
				'mainHeading' => __('Choose Album to Embed', 'custom-facebook-feed'),
				'description' => __('Add the URL or ID of the album you want to feature', 'custom-facebook-feed'),
				'couldNotFetch' => __('Could not fetch album preview', 'custom-facebook-feed'),
				'URLorID' => __('Album URL or ID', 'custom-facebook-feed'),
				'unable' => sprintf(__('Unable to retrieve album. Please make sure the link is correct. See %shere%s for more help.', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/how-to-use-the-album-extension-to-display-photos-from-a-specific-facebook-album/?facebook" target="_blank" rel="noopener">', '</a>'),
				'unablePreview' => __('Unable to retrieve album. Please make sure the link<br/>entered is correct.', 'custom-facebook-feed'),
				'preview' => __('Album Preview', 'custom-facebook-feed'),
				'previewDescription' => __('Once you enter an album URL or ID, click next and the preview will show up here', 'custom-facebook-feed'),
				'previewHeading' => __('Add an Album', 'custom-facebook-feed'),
				'previewText' => __('To add a single album, add it\'s URL or ID in the "Album<br/>URL or ID" field on the left sidebar.', 'custom-facebook-feed'),
			),
			'mainFooterScreen' => array(
				'heading' => sprintf(__('Upgrade to the %sAll Access Bundle%s to get all of our Pro Plugins', 'custom-facebook-feed'), '<strong>', '</strong>'),
				'description' => __('Includes all Smash Balloon plugins for one low price: Instagram, Facebook, Twitter, YouTube, and Social Wall', 'custom-facebook-feed'),
				'promo' => sprintf(__('%sBonus%s Lite users get %s50&#37; Off%s automatically applied at checkout', 'custom-facebook-feed'), '<span class="cff-bld-ft-bns">', '</span>', '<strong>', '</strong>'),
			),
			'embedPopupScreen' => array(
				'heading' => __('Embed Feed', 'custom-facebook-feed'),
				'description' => __('Add the unique shortcode to any page, post, or widget:', 'custom-facebook-feed'),
				'description_2' => __('Or use the built in WordPress block or widget', 'custom-facebook-feed'),
				'addPage' => __('Add to a Page', 'custom-facebook-feed'),
				'addWidget' => __('Add to a Widget', 'custom-facebook-feed'),
				'selectPage' => __('Select Page', 'custom-facebook-feed'),
			),
			'dialogBoxPopupScreen'  => array(
				'deleteSourceCustomizer' => array(
					'heading' =>  __('Delete "#"?', 'custom-facebook-feed'),
					'description' => __('You are going to delete this source. To retrieve it, you will need to add it again. Are you sure you want to continue?', 'custom-facebook-feed'),
				),
				'deleteSingleFeed' => array(
					'heading' =>  __('Delete "#"?', 'custom-facebook-feed'),
					'description' => __('You are going to delete this feed. You will lose all the settings. Are you sure you want to continue?', 'custom-facebook-feed'),
				),
				'deleteMultipleFeeds' => array(
					'heading' =>  __('Delete Feeds?', 'custom-facebook-feed'),
					'description' => __('You are going to delete these feeds. You will lose all the settings. Are you sure you want to continue?', 'custom-facebook-feed'),
				),
				'backAllToFeed' => array(
					'heading' =>  __('Are you Sure?', 'custom-facebook-feed'),
					'description' => __('Are you sure you want to leave this page, all unsaved settings will be lost, please make sure to save before leaving.', 'custom-facebook-feed'),
				)
			),
			'socialInfo' => $this->get_smashballoon_info(),
			'sourceConnectionURLs' => CFF_Source::get_connection_urls(),
			'installPluginsPopup' => $this->install_plugins_popup()
		);

		if ($newly_retrieved_source_connection_data) {
			$cff_builder['newSourceData'] = $newly_retrieved_source_connection_data;
		}



		$maybe_feed_customizer_data = CFF_Feed_Saver_Manager::maybe_feed_customizer_data();
		if ($maybe_feed_customizer_data) {
			// Masonry + Isotope + ImagesLoaded Scripts
			wp_enqueue_script(
				"cff-isotope",
				'https://unpkg.com/isotope-layout@3.0.6/dist/isotope.pkgd.min.js',
				null,
				'3.0.6',
				true
			);
			wp_enqueue_script(
				"cff-images-loaded",
				'https://unpkg.com/imagesloaded@4.1.4/imagesloaded.pkgd.min.js',
				null,
				'4.1.4',
				true
			);

			// Check if carousel Plugin is Active
			if ($active_extensions['carousel'] == true) {
				wp_enqueue_script(
					"cff-carousel-js",
					'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js',
					null,
					'2.3.4',
					true
				);
				wp_enqueue_script(
					"cff-autoheight",
					CFF_PLUGIN_URL . 'admin/builder/assets/js/owl.autoheight.js',
					null,
					CFFVER,
					true
				);
				wp_enqueue_style(
					'cff-carousel-css',
					'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css',
					false,
					CFFVER
				);
				wp_enqueue_style(
					'cff-carousel-theme-css',
					'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css',
					false,
					CFFVER
				);
			}

			wp_enqueue_style(
				'feed-builder-preview-style',
				CFF_PLUGIN_URL . 'admin/builder/assets/css/preview.css',
				false,
				CFFVER . time()
			);
			$cff_builder['customizerFeedData'] 			=  $maybe_feed_customizer_data;
			$cff_builder['customizerSidebarBuilder'] 	=  \CustomFacebookFeed\Builder\Tabs\CFF_Builder_Customizer_Tab::get_customizer_tabs();
			$cff_builder['wordpressPageLists']	= $this->get_wp_pages();

			// Date
			global $wp_locale;
			wp_enqueue_script(
				"cff-date_i18n",
				CFF_PLUGIN_URL . 'admin/builder/assets/js/date_i18n.js',
				null,
				CFFVER,
				true
			);

			$monthNames = array_map(
				array(&$wp_locale, 'get_month'),
				range(1, 12)
			);
			$monthNamesShort = array_map(
				array(&$wp_locale, 'get_month_abbrev'),
				$monthNames
			);
			$dayNames = array_map(
				array(&$wp_locale, 'get_weekday'),
				range(0, 6)
			);
			$dayNamesShort = array_map(
				array(&$wp_locale, 'get_weekday_abbrev'),
				$dayNames
			);
			wp_localize_script(
				"cff-date_i18n",
				"DATE_I18N",
				array(
					"month_names" => $monthNames,
					"month_names_short" => $monthNamesShort,
					"day_names" => $dayNames,
					"day_names_short" => $dayNamesShort
				)
			);
		}

		wp_enqueue_style(
			'feed-builder-style',
			CFF_PLUGIN_URL . 'admin/builder/assets/css/builder.css',
			false,
			CFFVER . time()
		);

		self::global_enqueue_ressources_scripts();

		wp_register_script('feed-builder-svgs', CFF_PLUGIN_URL . 'assets/svgs/svgs.js');

		wp_enqueue_script(
			'feed-builder-app',
			CFF_PLUGIN_URL . 'admin/builder/assets/js/builder.js',
			['feed-builder-svgs'],
			CFFVER . time(),
			true
		);


		// Customize screens
		$cff_builder['customizeScreens'] = $this->get_customize_screens_text();
		$cff_builder['cff_plugin_path'] = CFF_PLUGIN_URL;
		wp_localize_script(
			'feed-builder-app',
			'cff_builder',
			$cff_builder
		);
	}


	/**
	 * Global JS + CSS Files
	 *
	 * Shared JS + CSS ressources for the admin panel
	 *
	 * @since 4.0
	 */
	public static function global_enqueue_ressources_scripts($is_settings = false)
	{
		wp_enqueue_style(
			'feed-global-style',
			CFF_PLUGIN_URL . 'admin/builder/assets/css/global.css',
			false,
			CFFVER
		);

		wp_enqueue_script(
			'sb-vue',
			CFF_PLUGIN_URL . 'admin/assets/js/vue.min.js',
			null,
			'2.6.12',
			true
		);
		wp_enqueue_script(
			'feed-colorpicker-vue',
			CFF_PLUGIN_URL . 'admin/builder/assets/js/vue-color.min.js',
			null,
			CFFVER,
			true
		);

		wp_enqueue_script(
			'feed-builder-ressources',
			CFF_PLUGIN_URL . 'admin/builder/assets/js/ressources.js',
			null,
			CFFVER,
			true
		);

		wp_enqueue_script(
			'sb-dialog-box',
			CFF_PLUGIN_URL . 'admin/builder/assets/js/confirm-dialog.js',
			null,
			CFFVER,
			true
		);

		wp_enqueue_script(
			'sb-add-source',
			CFF_PLUGIN_URL . 'admin/builder/assets/js/add-source.js',
			null,
			CFFVER,
			true
		);

		wp_enqueue_script(
			'install-plugin-popup',
			CFF_PLUGIN_URL . 'admin/builder/assets/js/install-plugin-popup.js',
			null,
			CFFVER,
			true
		);

		$newly_retrieved_source_connection_data = CFF_Source::maybe_source_connection_data();
		$cff_source = array(
			'sources' => self::get_source_list(),
			'sourceConnectionURLs' => CFF_Source::get_connection_urls($is_settings)
		);
		if ($newly_retrieved_source_connection_data) {
			$cff_source['newSourceData'] = $newly_retrieved_source_connection_data;
		}
		if (isset($_GET['manualsource']) && $_GET['manualsource'] == true) {
			$cff_source['manualSourcePopupInit'] = true;
		}

		wp_localize_script(
			'sb-add-source',
			'cff_source',
			$cff_source
		);
	}


	/**
	 * Whether this is the free or Pro version
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function get_plugin_type()
	{
		if (function_exists('cff_main_pro')) {
			return 'pro';
		}
		return 'free';
	}

	/**
	 * Get WP Pages List
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public function get_wp_pages()
	{
		$pagesList = get_pages();
		$pagesResult = [];
		if (is_array($pagesList)) {
			foreach ($pagesList as $page) {
				array_push($pagesResult, ['id' => $page->ID, 'title' => $page->post_title]);
			}
		}
		return $pagesResult;
	}


	/**
	 * Get Generic text
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function get_generic_text()
	{
		return array(
			'done' => __('Done', 'custom-facebook-feed'),
			'title' => __('Settings', 'custom-facebook-feed'),
			'dashboard' => __('Dashboard', 'custom-facebook-feed'),
			'addNew' => __('Add New', 'custom-facebook-feed'),
			'addSource' => __('Add Source', 'custom-facebook-feed'),
			'previous' => __('Previous', 'custom-facebook-feed'),
			'next' => __('Next', 'custom-facebook-feed'),
			'finish' => __('Finish', 'custom-facebook-feed'),
			'new' => __('New', 'custom-facebook-feed'),
			'update' => __('Update', 'custom-facebook-feed'),
			'upgrade' => __('Upgrade', 'custom-facebook-feed'),
			'settings' => __('Settings', 'custom-facebook-feed'),
			'back' => __('Back', 'custom-facebook-feed'),
			'backAllFeeds' => __('Back to all feeds', 'custom-facebook-feed'),
			'createFeed' => __('Create Feed', 'custom-facebook-feed'),
			'add' => __('Add', 'custom-facebook-feed'),
			'change' => __('Change', 'custom-facebook-feed'),
			'getExtention' => __('Get Extension', 'custom-facebook-feed'),
			'viewDemo' => __('View Demo', 'custom-facebook-feed'),
			'includes' => __('Includes', 'custom-facebook-feed'),
			'photos' => __('Photos', 'custom-facebook-feed'),
			'photo' => __('Photo', 'custom-facebook-feed'),
			'apply' => __('Apply', 'custom-facebook-feed'),
			'copy' => __('Copy', 'custom-facebook-feed'),
			'edit' => __('Edit', 'custom-facebook-feed'),
			'duplicate' => __('Duplicate', 'custom-facebook-feed'),
			'delete' => __('Delete', 'custom-facebook-feed'),
			'shortcode' => __('Shortcode', 'custom-facebook-feed'),
			'clickViewInstances' => __('Click to view Instances', 'custom-facebook-feed'),
			'usedIn' => __('Used in', 'custom-facebook-feed'),
			'place' => __('place', 'custom-facebook-feed'),
			'places' => __('places', 'custom-facebook-feed'),
			'item' => __('Item', 'custom-facebook-feed'),
			'items' => __('Items', 'custom-facebook-feed'),
			'learnMore' => __('Learn More', 'custom-facebook-feed'),
			'location' => __('Location', 'custom-facebook-feed'),
			'page' => __('Page', 'custom-facebook-feed'),
			'copiedClipboard' => __('Copied to Clipboard', 'custom-facebook-feed'),
			'feedImported' => __('Feed imported successfully', 'custom-facebook-feed'),
			'failedToImportFeed' => __('Failed to import feed', 'custom-facebook-feed'),
			'timeline' => __('Timeline', 'custom-facebook-feed'),
			'help' => __('Help', 'custom-facebook-feed'),
			'admin' => __('Admin', 'custom-facebook-feed'),
			'member' => __('Member', 'custom-facebook-feed'),
			'reset' => __('Reset', 'custom-facebook-feed'),
			'preview' => __('Preview', 'custom-facebook-feed'),
			'name' => __('Name', 'custom-facebook-feed'),
			'id' => __('ID', 'custom-facebook-feed'),
			'token' => __('Token', 'custom-facebook-feed'),
			'confirm' => __('Confirm', 'custom-facebook-feed'),
			'cancel' => __('Cancel', 'custom-facebook-feed'),
			'clearFeedCache' => __('Clear Feed Cache', 'custom-facebook-feed'),
			'saveSettings' => __('Save Changes', 'custom-facebook-feed'),
			'feedName' => __('Feed Name', 'custom-facebook-feed'),
			'shortcodeText' => __('Shortcode', 'custom-facebook-feed'),
			'general' => __('General', 'custom-facebook-feed'),
			'feeds' => __('Feeds', 'custom-facebook-feed'),
			'translation' => __('Translation', 'custom-facebook-feed'),
			'advanced' => __('Advanced', 'custom-facebook-feed'),
			'error' => __('Error:', 'custom-facebook-feed'),
			'errorNotice' => __('There was an error when trying to connect to Facebook.', 'custom-facebook-feed'),
			'errorDirections' => '<a href="https://smashballoon.com/custom-facebook-feed/docs/errors/" target="_blank" rel="noopener">' . __('Directions on How to Resolve This Issue', 'custom-facebook-feed')  . '</a>',
			'errorSource' => __('Source Invalid', 'custom-facebook-feed'),
			'errorEncryption' => __('Encryption Error', 'custom-facebook-feed'),
			'updateRequired' => __('Update Required', 'custom-facebook-feed'),
			'invalid' => __('Invalid', 'custom-facebook-feed'),
			'reconnect' => __('Reconnect', 'custom-facebook-feed'),
			'feed' => __('feed', 'custom-facebook-feed'),
			'sourceNotUsedYet' => __('Source is not used yet', 'custom-facebook-feed'),
			'largeGrid'	=> __('Large Grid', 'custom-facebook-feed'),
			'singlePhoto'	=> __('Single Photo', 'custom-facebook-feed'),
			'latestAlbum'	=> __('Latest Album', 'custom-facebook-feed'),
			'latestVideo'	=> __('Latest Video', 'custom-facebook-feed'),
			'icalUrl'	=> __('Events iCal URL', 'custom-facebook-feed'),
			'icalUrlS'	=> __('iCal URL', 'custom-facebook-feed'),
			'issue' => __('Issue', 'custom-facebook-feed'),
			'issueFound' => __('Issue Found', 'custom-facebook-feed'),
			'fix' => __('Fix', 'custom-facebook-feed'),
			'deperecatedGroupText' =>
				sprintf(
					__('Due to changes with the Facebook API, which we use to create feeds, group feeds will no longer update after April of 2024 %sLearn More%s', 'custom-facebook-feed'),
					'<a href="https://smashballoon.com/doc/facebook-api-changes-affecting-groups-april-2024" target="_blank">',
					'</a>'
				),

			'notification' => array(
				'feedSaved' => array(
					'type' => 'success',
					'text' => __('Feed saved successfully', 'custom-facebook-feed')
				),
				'feedSavedError' => array(
					'type' => 'error',
					'text' => __('Error saving Feed', 'custom-facebook-feed')
				),
				'previewUpdated' => array(
					'type' => 'success',
					'text' => __('Preview updated successfully', 'custom-facebook-feed')
				),
				'carouselLayoutUpdated' => array(
					'type' => 'success',
					'text' => __('Carousel updated successfully', 'custom-facebook-feed')
				),
				'unkownError' => array(
					'type' => 'error',
					'text' => __('Unknown error occurred', 'custom-facebook-feed')
				),
				'cacheCleared' => array(
					'type' => 'success',
					'text' => __('Feed cache cleared', 'custom-facebook-feed')
				),
				'selectSourceError' => array(
					'type' => 'error',
					'text' => __('Please select a source for your feed', 'custom-facebook-feed')
				),
				'licenseActivated'   => array(
					'type' => 'success',
					'text' => __('License Successfully Activated', 'custom-facebook-feed'),
				),
				'licenseError'   => array(
					'type' => 'error',
					'text' => __('Couldn\'t Activate License', 'custom-facebook-feed'),
				),
			),
			'install' => __('Install', 'custom-facebook-feed'),
			'installed' => __('Installed', 'custom-facebook-feed'),
			'activate' => __('Activate', 'custom-facebook-feed'),
			'installedAndActivated' => __('Installed & Activated', 'custom-facebook-feed'),
			'free' => __('Free', 'custom-facebook-feed'),
			'invalidLicenseKey' => __('Invalid license key', 'custom-facebook-feed'),
			'licenseActivated' => __('License activated', 'custom-facebook-feed'),
			'licenseDeactivated' => __('License Deactivated', 'custom-facebook-feed'),
			'carouselLayoutUpdated' => array(
				'type' => 'success',
				'text' => __('Carousel Layout updated', 'custom-facebook-feed')
			),
			'clickingHere' => __('clicking here', 'custom-facebook-feed'),
			'redirectLoading' => array(
				'heading' =>  __('Redirecting to connect.smashballoon.com', 'custom-facebook-feed'),
				'description' =>  __('You will be redirected to our app so you can connect your account in 5 seconds', 'custom-facebook-feed'),
			),
			'active' => __('Active', 'custom-facebook-feed'),
			'licenseExpired'					=> __('License Expired', 'custom-facebook-feed'),
			'licenseInactive'					=> __('Inactive', 'custom-facebook-feed'),
			'renew'								=> __('Renew', 'custom-facebook-feed'),
			'activateLicense'					=> __('Activate License', 'custom-facebook-feed'),
			'recheckLicense'					=> __('Recheck License', 'custom-facebook-feed'),
			'licenseValid'						=> __('License Valid', 'custom-facebook-feed'),
			'licenseExpired'					=> __('License Expired', 'custom-facebook-feed'),
			'cffFeedCreated' => __('Facebook feed successfully created!', 'custom-facebook-feed'),
			'onceDoneSWFeed' => __('Once you are done creating the Facebook feed, you can go back to Social plugin', 'custom-facebook-feed'),
			'goToSocialWall' => __('Go to Social Wall', 'custom-facebook-feed'),
			'installNewVersion'					=> __('Install New Version', 'custom-facebook-feed'),
		);
	}

	public static function get_extensions_popup()
	{
		$license_key = cff_main_pro()->cff_license_handler->get_license_key;
		$plus_text = __('Plus', 'custom-facebook-feed');
		$elite_text = __('Elite', 'custom-facebook-feed');

		return array(
			'photos' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get Photo Feeds', $plus_text),
				'description' 	=> __('Save time by displaying beautiful photo feeds which pull right from your Facebook Photos page. List, grid, masonry, and carousels, with different layouts for both desktop and mobile, and a full size photo lightbox.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url('https://smashballoondemo.com/photos/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=photos&utm_content=see-demo'),
				'demoUrl'		=> WPW_SL_STORE_URL . 'account?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=photo&utm_content=learn-more'
			),
			'videos' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get Video Feeds', $plus_text),
				'description' 	=> __('Automatically feed videos from your Facebook Videos page right to your website. List, grid, masonry, and carousel layouts, played in stunning HD with a full size video lightbox.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url('https://smashballoondemo.com/videos/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=videos&utm_content=see-demo'),
				'demoUrl'		=> WPW_SL_STORE_URL . 'account?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=video&utm_content=learn-more'
			),
			'albums' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get Album Feeds', $plus_text),
				'description' 	=> __('Display a feed of the albums from your Facebook Photos page. Show photos within each album inside a beautiful album lightbox to increase discovery of your content to your website visitors.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url('https://smashballoondemo.com/albums/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=albums&utm_content=see-demo'),
				'demoUrl'		=> WPW_SL_STORE_URL . 'account?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=album&utm_content=learn-more'
			),
			'events' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get Event Feeds', $elite_text),
				'description' 	=> __('Promote your Facebook events to your website visitors to help boost attendance. Display both upcoming and past events in a list, masonry layout, or carousel.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url('https://smashballoondemo.com/events/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=events&utm_content=see-demo'),
				'demoUrl'		=> WPW_SL_STORE_URL . 'account?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=event&utm_content=learn-more'
			),
			'reviews' => array(
				'heading' 		=> __('Upgrade to get Facebook Reviews', 'custom-facebook-feed'),
				'description' 	=> __('Add compelling social proof to your site by displaying reviews and recommendations from your Facebook Pages. Easily filter by rating to only show your best reviews.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display feed of your Facebook reviews', 'custom-facebook-feed'),
						__('Create engaging carousel feeds', 'custom-facebook-feed'),
						__('Combine feeds from multiple accounts', 'custom-facebook-feed'),
						__('Filter your feeds using a date range', 'custom-facebook-feed'),
						__('Embed single Facebook posts', 'custom-facebook-feed'),
						__('Embed photos from a single album', 'custom-facebook-feed'),
					]
				],
				'demoUrl' 		=> sprintf('https://smashballoon.com/extensions/reviews/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=reviews-modal&utm_content=learn-more'),
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url(sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=reviews&utm_content=upgrade', $license_key))
			),
			'featuredpost' => array(
				'heading' 		=> __('Upgrade to display Single Featured Posts', 'custom-facebook-feed'),
				'description' 	=> __('Easily highlight any single post or event from your Facebook page.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display feed of your Facebook reviews', 'custom-facebook-feed'),
						__('Create engaging carousel feeds', 'custom-facebook-feed'),
						__('Combine feeds from multiple accounts', 'custom-facebook-feed'),
						__('Filter your feeds using a date range', 'custom-facebook-feed'),
						__('Embed single Facebook posts', 'custom-facebook-feed'),
						__('Embed photos from a single album', 'custom-facebook-feed'),
					]
				],
				'demoUrl' 		=> 'https://smashballoon.com/extensions/featured-post?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=featured-post&utm_content=learn-more',
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url(sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=featured-post&utm_content=upgrade', $license_key))
			),
			'singlealbum' => array(
				'heading' 		=> __('Upgrade to embed Single Album Feeds', 'custom-facebook-feed'),
				'description' 	=> __('Embed photos from inside any single album from your Facebook Page, and display them in several attractive layouts.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display feed of your Facebook reviews', 'custom-facebook-feed'),
						__('Create engaging carousel feeds', 'custom-facebook-feed'),
						__('Combine feeds from multiple accounts', 'custom-facebook-feed'),
						__('Filter your feeds using a date range', 'custom-facebook-feed'),
						__('Embed single Facebook posts', 'custom-facebook-feed'),
						__('Embed photos from a single album', 'custom-facebook-feed'),
					]
				],
				'demoUrl' 		=> 'https://smashballoon.com/extensions/album/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=album&utm_content=learn-more',
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url(sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=album&utm_content=upgrade', $license_key))
			),
			'carousel' => array(
				'heading' 		=> __('Upgrade to get Carousel layout', 'custom-facebook-feed'),
				'description' 	=> __('The Carousel layout is perfect for when you either want to display a lot of content in a small space or want to catch your visitors attention.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('All advanced feed types', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'demoUrl' 		=> 'https://smashballoon.com/extensions/carousel/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=layout&utm_content=carousel',
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url(sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=customizer&utm_medium=layout&utm_content=carousel', $license_key))
			),
			'socialwall' => array(
				'heading' 		=> __('<span class="sb-social-wall">Combine all your social media channels into one', 'custom-facebook-feed') . ' <span>' . __('Social Wall', 'custom-facebook-feed') . '</span></span>',
				'description' 	=> __('<span class="sb-social-wall">A dash of Instagram, a sprinkle of Facebook, a spoonful of Twitter, and a dollop of YouTube, all in the same feed.</span>', 'custom-facebook-feed'),
				'popupContentBtn' 	=> '<div class="cff-fb-extpp-lite-btn">' . self::builder_svg_icons('tag') . __('Facebook Pro users get 50% OFF', 'custom-facebook-feed') . '</div>',
				'demoUrl' 		=> 'https://smashballoon.com/social-wall/demo/?utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=social-wall&utm_content=learn-more',
				'buyUrl' 		=> sprintf('https://smashballoon.com/social-wall/pricing/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=feed-type&utm_medium=social-wall&utm_content=upgrade', $license_key),
				'bullets'       => [
					'heading' => __('Upgrade to the All Access Bundle and get:', 'custom-facebook-feed'),
					'content' => [
						__('Custom Facebook Feed Pro', 'custom-facebook-feed'),
						__('All Pro Facebook Extensions', 'custom-facebook-feed'),
						__('Custom Twitter Feeds Pro', 'custom-facebook-feed'),
						__('Instagram Feed Pro', 'custom-facebook-feed'),
						__('YouTube Feeds Pro', 'custom-facebook-feed'),
						__('Social Wall Pro', 'custom-facebook-feed'),
					]
				],
			),
			'date_range' => array(
				'heading' 		=> __('Upgrade your License to filter by Date Range', 'custom-facebook-feed'),
				'description' 	=> __('Filter posts based on a start and end date. Use relative dates (such as "1 month ago" or "now") or absolute dates (such as 01/01/21) to curate your feeds to specific date ranges.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('All advanced feed types', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'demoUrl' 		=> 'https://smashballoon.com/extensions/date-range/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=filters&utm_content=date-range',
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url(sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=customizer&utm_medium=filters&utm_content=date-range', $license_key))
			),
			'feedTemplate' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get one-click templates!', $plus_text),
				'description' 	=> __('Quickly create and preview new feeds with pre-configured options based on popular feed types.', 'feeds-for-youtube'),
				'popupContentBtn' 	=> '<br/><div class="cff-fb-extpp-lite-btn">' . self::builder_svg_icons('tag') . __('Lite Feed Users get a 50% OFF', 'feeds-for-youtube') . '</div>',
				'img' 			=> self::builder_svg_icons('extensions-popup.feedTemplate'),
				'demoUrl' 		=> 'https://smashballoon.com/youtube-feed/demo/?utm_campaign=youtube-free&utm_source=feed-type&utm_medium=youtube-feed&utm_content=view-demo',
				'buyUrl' 		=> self::get_extension_popup_dynamic_buy_url(sprintf('https://smashballoon.com/custom-facebook-feed/pricing/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=customizer&utm_medium=filters&utm_content=date-range', $license_key)),
				'bullets'       => [
					'heading' => __('And get much more!', 'feeds-for-youtube'),
					'content' => [
						__('Covert videos to WP Posts', 'feeds-for-youtube'),
						__('Show subscribers', 'feeds-for-youtube'),
						__('Show video details', 'feeds-for-youtube'),
						__('Fast and Effective Support', 'feeds-for-youtube'),
						__('Always up to date', 'feeds-for-youtube'),
						__('30 Day Money Back Guarantee', 'feeds-for-youtube')
					]
				],
			),

			// Fake Extensions
			'lightbox' => array(
				'heading' 		=> __('Upgrade to enable the popup Lightbox', 'custom-facebook-feed'),
				'description' 	=> __('Display photos and videos in your feed and allow visitors to view them in a beautiful full size lightbox, keeping them on your site for longer to discover more of your content.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> 'https://smashballoondemo.com/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=lightbox',
				'demoUrl'		=> WPW_SL_STORE_URL . 'account',
			),
			'advancedFilter' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get Advanced Filters', $plus_text),
				'description' 	=> __('Filter your posts using specific words, hashtags, or phrases to ensure only the content you want is displayed. Choose to hide or show specific types of posts in your timeline feeds.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> 'https://smashballoondemo.com/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=filters&utm_content=advanced-filters',
				'demoUrl'		=> WPW_SL_STORE_URL . 'account',
			),
			'feedThemes' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get Feed Themes', $elite_text),
				'description' 	=> __('We already have desiged some preset layouts for your themes.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> 'https://smashballoondemo.com/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=filters&utm_content=advanced-filters',
				'demoUrl'		=> WPW_SL_STORE_URL . 'account',
			),
			'postSettings' => array(
				'heading' 		=> self::get_extension_popup_dynamic_heading('get Post Layouts', $plus_text),
				'description' 	=> __('Display your timeline posts in 3 easy layout options with photos and videos included to make your posts pop, keeping your visitors engaged on your site for longer.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> 'https://smashballoondemo.com/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=posts&utm_content=post-layouts'
			),
			'mediaComment' => array(
				'heading' 		=> __('Upgrade to add Media, Likes, & Comments', 'custom-facebook-feed'),
				'description' 	=> __('Display any likes, shares, comments, and reactions in a customizable drop-down box below each timeline post, including comment replies and image attachments.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> 'https://smashballoondemo.com/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=posts&utm_content=advanced-elements'
			),
			'loadMore' => array(
				'heading' 		=> __('Upgrade to add Load More functionality', 'custom-facebook-feed'),
				'description' 	=> __('Add a Load More button at the bottom of each feed to infinitely load more content. Customize the button text, colors, and font to look exactly as you\'d like.', 'custom-facebook-feed'),
				'bullets'       => [
					'heading' => __('And much more!', 'custom-facebook-feed'),
					'content' => [
						__('Display images & videos in posts', 'custom-facebook-feed'),
						__('Show likes, reactions, comments', 'custom-facebook-feed'),
						__('Advanced feed types', 'custom-facebook-feed'),
						__('Filter Posts', 'custom-facebook-feed'),
						__('Popup photo/video lightbox', 'custom-facebook-feed'),
						__('Ability to load more posts', 'custom-facebook-feed'),
						__('Multiple post layout options', 'custom-facebook-feed'),
						__('Video player (HD, 360, Live)', 'custom-facebook-feed'),
						__('30 day money back guarantee', 'custom-facebook-feed'),
					]
				],
				'buyUrl' 		=> 'https://smashballoondemo.com/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=load-more'
			),
		);
	}

	/**
	 * Get dynamic heading for the extension popup depending on license state
	 *
	 * @since 4.4.0
	 */
	public static function get_extension_popup_dynamic_heading($extension_title, $license_tier = '')
	{
		if (empty(cff_main_pro()->cff_license_handler->get_license_key)) {
			return sprintf(__('Activate your License to %s', 'custom-facebook-feed'), $extension_title);
		} else {
			if (cff_main_pro()->cff_license_handler->expiredLicenseWithGracePeriodEnded) {
				return sprintf(__('Renew license to %s', 'custom-facebook-feed'), $extension_title);
			} else {
				return sprintf(__('Upgrade to %1$s to %2$s', 'custom-facebook-feed'), $license_tier, $extension_title);
			}
		}
	}

	/**
	 * Get dynamic upgrade/activate/renew URL depending on license state
	 *
	 * @since 4.4.0
	 */
	public static function get_extension_popup_dynamic_buy_url($default_upgrade_url)
	{
		$license_key = cff_main_pro()->cff_license_handler->get_license_key;
		$license_notice_active = empty(cff_main_pro()->cff_license_handler->get_license_key) || cff_main_pro()->cff_license_handler->expiredLicenseWithGracePeriodEnded ? true : false;
		// if the license is inactive
		if (empty(cff_main_pro()->cff_license_handler->get_license_key)) {
			return admin_url('admin.php?page=cff-settings');
		}
		// if the license is active but expired and grace period ended
		if ($license_notice_active) {
			return cff_main_pro()->cff_license_handler->get_renew_url;
		}
		return $default_upgrade_url;
	}

	/**
	 * Select Source Screen Text
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function select_source_screen_text()
	{
		return array(
			'mainHeading' => __('Select a Source', 'custom-facebook-feed'),
			'description' => __('Sources are Facebook pages or groups your feed will fetch posts, photos, or videos from', 'custom-facebook-feed'),
			'eventsToolTip' => array(
				__('To display events from a Facebook Page<br/>you need to create your own Facebook app.', 'custom-facebook-feed'),
				__('Click "+ Add New" to get started.', 'custom-facebook-feed')
			),
			'groupsToolTip' => array(
				__('Due to Facebook limitations, it\'s not possible to display photo feeds from a Group, only a Page.', 'custom-facebook-feed')
			),
			'updateHeading' => __('Update Source', 'custom-facebook-feed'),
			'updateDescription' => __('Select a source from your connected Facebook Pages and Groups. Or, use "Add New" to connect a new one.', 'custom-facebook-feed'),
			'updateFooter' => __('Add multiple Facebook Pages or Groups to a feed with our Multifeed extension', 'custom-facebook-feed'),
			'noSources' => __('Please add a source in order to display a feed. Go to the "Settings" tab -> "Sources" section -> Click "Add New" to connect a source.', 'custom-facebook-feed'),

			'modal' => array(
				'addNew' => __('Add a New Source', 'custom-facebook-feed'),
				'selectSourceType' => __('Select Source Type', 'custom-facebook-feed'),
				'connectAccount' => __('Connect a Facebook Account', 'custom-facebook-feed'),
				'connectAccountDescription' => __('This does not give us permission to manage your Facebook Pages or Groups, it simply allows the plugin to see a list of them and retrieve their public content from the API.', 'custom-facebook-feed'),
				'connect' => __('Connect', 'custom-facebook-feed'),
				'enterEventToken' => __('Enter Events Access Token', 'custom-facebook-feed'),
				'enterEventTokenDescription' => sprintf(__('Due to restrictions by Facebook, you need to create a Facebook app and then paste that app Access Token here. We have a guide to help you with just that, which you can read %shere%s', 'custom-facebook-feed'), '<a href="https://smashballoon.com/custom-facebook-feed/page-token/" target="_blank" rel="noopener">', '</a>'),
				'enterEventiCalUrlDescription' => sprintf(__('Events iCal URL %sWhere do I get this?%s', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/ical-url-for-the-facebook-events-feed/" target="_blank" rel="noopener">', '</a>'),
				'enterIcalUrl' => __('Enter iCal URL', 'custom-facebook-feed'),

				'alreadyHave' => __('Already have a Facebook Access Token for your Page or Group?', 'custom-facebook-feed'),
				'addManuallyLink' => __('Add Account Manually', 'custom-facebook-feed'),
				'selectPage' => __('Select a Facebook Page', 'custom-facebook-feed'),
				'selectGroup' => __('Select a Facebook Group', 'custom-facebook-feed'),
				'showing' => __('Showing', 'custom-facebook-feed'),
				'facebook' => __('Facebook', 'custom-facebook-feed'),
				'pages' => __('Pages', 'custom-facebook-feed'),
				'groups' => __('Groups', 'custom-facebook-feed'),
				'connectedTo' => __('connected to', 'custom-facebook-feed'),
				'addManually' => __('Add a Source Manually', 'custom-facebook-feed'),
				'addSource' => __('Add Source', 'custom-facebook-feed'),
				'sourceType' => __('Source Type', 'custom-facebook-feed'),
				'pageOrGroupID' => __('Facebook Page or Group ID', 'custom-facebook-feed'),
				'fbPageID' => __('Facebook Page ID', 'custom-facebook-feed'),
				'eventAccessToken' => __('Event Access Token', 'custom-facebook-feed'),
				'enterID' => __('Enter ID', 'custom-facebook-feed'),
				'accessToken' => __('Facebook Access Token', 'custom-facebook-feed'),
				'enterToken' => __('Enter Token', 'custom-facebook-feed'),
				'addApp' => __('Add Facebook App to your group', 'custom-facebook-feed'),
				'addAppDetails' => __('To get posts from your group, Facebook requires the "Smash Balloon WordPress" app to be added in your group settings. Just follow the directions here:', 'custom-facebook-feed'),
				'addAppSteps' => [
					__('Go to your group settings page by ', 'custom-facebook-feed'),
					sprintf(__('Search for "Smash Balloon WordPress" and select our app %s(see screenshot)%s', 'custom-facebook-feed'), '<a href="JavaScript:void(0);" id="cff-group-app-tooltip">', '<img class="cff-group-app-screenshot sb-tr-1" src="' . trailingslashit(CFF_PLUGIN_URL) . 'admin/assets/img/group-app.png" alt="Thumbnail Layout"></a>'),
					__('Click "Add" and you are done.', 'custom-facebook-feed')
				],
				'reconnectingAppDir' => __('If you are reconnecting an existing Group then make sure to follow the directions above to add this new app to your Group settings. The previous app will no longer work. This is required in order for new posts to be retrieved.', 'custom-facebook-feed'),
				'appMemberInstructions' => sprintf(__('To display a feed from this group, Facebook requires the admin to add the Smash Balloon app in the group settings. Please ask an admin to follow the %sdirections here%s to add the app.', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/display-facebook-group-feed/" target="_blank" rel="noopener noreferrer">', '</a>') . '<br><br>' . __('Once this is done, you will be able to display a feed from this group.', 'custom-facebook-feed'),
				'notAdmin' => __('For groups you are not an administrator of', 'custom-facebook-feed'),
				'disclaimer' => sprintf(__('Please note: There are Facebook limitations to displaying group content which may prevent older posts from being displayed. Please %ssee here%s for more information.', 'custom-facebook-feed'), '<a href="https://smashballoon.com/doc/facebook-api-change-limits-groups-to-90-days/" target="_blank" rel="noopener noreferrer">', '</a>'),
				'noGroupTooltip' => __('Due to Facebook limitations, it\'s not possible to display photo feeds from a Group, only a Page.', 'custom-facebook-feed')
			),
			'footer' => array(
				'heading' => __('Add feeds for popular social platforms with our other plugins', 'custom-facebook-feed'),
			),
			'page' => __('Page', 'custom-facebook-feed'),
			'group' => __('Group', 'custom-facebook-feed'),
		);
	}
	/**
	 * For types listed on the top of the select feed type screen
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_feed_types()
	{
		$feed_types = array(
			array(
				'type' => 'timeline',
				'title' => __('Timeline', 'custom-facebook-feed'),
				'description' => __('Fetch posts from your Facebook timeline', 'custom-facebook-feed'),
				'icon'	=>  'timelineIcon',
				'iconFree'	=>  'timelineIcon',
			),
			array(
				'type' => 'photos',
				'title' => __('Photos', 'custom-facebook-feed'),
				'description' => __('Display photos from your Facebook Photos page', 'custom-facebook-feed'),
				'icon'	=>  'photosIcon',
				'iconFree'	=>  'photosIconFree'
			),
			array(
				'type' => 'videos',
				'title' => __('Videos', 'custom-facebook-feed'),
				'description' => __('Display videos from your Facebook Videos page', 'custom-facebook-feed'),
				'icon'	=>  'videosIcon',
				'iconFree'	=>  'videosIconFree'
			),
			array(
				'type' => 'albums',
				'title' => __('Albums', 'custom-facebook-feed'),
				'description' => __('Display all albums from your Facebook Photos page', 'custom-facebook-feed'),
				'icon'	=>  'albumsIcon',
				'iconFree'	=>  'albumsIconFree',
			),
			array(
				'type' => 'events',
				'title' => __('Events', 'custom-facebook-feed'),
				'description' => __('Display events from your Facebook Events page', 'custom-facebook-feed'),
				'icon'	=>  'eventsIcon',
				'iconFree'	=>  'eventsIconFree',
			)
		);

		return $feed_types;
	}

	/**
	 * For types listed on the bottom of the select feed type screen
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_advanced_feed_types($active_extensions)
	{
		$feed_types = array(
			array(
				'type' => 'reviews',
				'title' => __('Reviews', 'custom-facebook-feed'),
				'description' => __('Show reviews or recommendations from your Facebook page', 'custom-facebook-feed'),
				'extensionActive' => $active_extensions['reviews'],
				'icon'	=>  'reviewsIcon',
				'iconFree'	=>  'reviewsIconFree',
			),
			array(
				'type' => 'featuredpost',
				'title' => __('Single Featured Post', 'custom-facebook-feed'),
				'description' => __('Display a single post from your Facebook page', 'custom-facebook-feed'),
				'extensionActive' => $active_extensions['featured_post'],
				'icon'	=>  'featuredpostIcon',
				'iconFree'	=>  'featuredpostIconFree',
			),
			array(
				'type' => 'singlealbum',
				'title' => __('Single Album', 'custom-facebook-feed'),
				'description' => __('Display the contents of a single Album from your Facebook page', 'custom-facebook-feed'),
				'extensionActive' => $active_extensions['album'],
				'icon'	=>  'singlealbumIcon',
				'iconFree'	=>  'singlealbumIconFree'
			),
			array(
				'type' => 'socialwall',
				'title' => __('Social Wall', 'custom-facebook-feed'),
				'description' => __('Create a feed which combines sources from multiple social platforms', 'custom-facebook-feed'),
				'extensionActive' => defined('SWVER'),
				'icon'	=>  'socialwallIcon',
				'iconFree'	=>  'socialwallIconFree'
			)
		);

		return $feed_types;
	}

	/**
	 * For types listed on the top of the select feed type screen
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_feed_templates()
	{
		$feed_types = array(
			array(
				'type' => 'default',
				'title' => __('Default', 'custom-facebook-feed'),
				'icon'	=>  'defaultFTIcon'
			),
			array(
				'type' => 'simple_masonry',
				'title' => __('Simple Masonry', 'custom-facebook-feed'),
				'icon'	=>  'singleMasonryFTIcon'
			),
			array(
				'type' => 'widget',
				'title' => __('Widget', 'custom-facebook-feed'),
				'icon'	=>  'widgetFTIcon'
			),
			array(
				'type' => 'simple_cards',
				'title' => __('Simple Cards', 'custom-facebook-feed'),
				'icon'	=>  'simpleCardsFTIcon'
			),
			array(
				'type' => 'latest_post',
				'title' => __('Latest Post', 'custom-facebook-feed'),
				'icon'	=>  'latestPostFTIcon'
			),
			array(
				'type' => 'showcase_carousel',
				'title' => __('Showcase Carousel', 'custom-facebook-feed'),
				'icon'	=>  'showcaseCarouselFTIcon'
			),
			array(
				'type' => 'simple_carousel',
				'title' => __('Simple Carousel', 'custom-facebook-feed'),
				'icon'	=>  'simpleCarouselFTIcon'
			)
		);

		return $feed_types;
	}

	/**
	 * Feed theme list
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_feed_themes()
	{
		$feed_thmes = array(
			array(
				'type' => 'default_theme',
				'title' => __('Default', 'custom-facebook-feed'),
				'icon'	=>  'singleMasonryFTIcon'
			),
			array(
				'type' => 'modern',
				'title' => __('Modern', 'custom-facebook-feed'),
				'icon'	=>  'singleMasonryFTIcon'
			),
			array(
				'type' => 'social_wall',
				'title' => __('Social Wall', 'custom-facebook-feed'),
				'icon'	=>  'widgetFTIcon'
			),
			array(
				'type' => 'outline',
				'title' => __('Outline', 'custom-facebook-feed'),
				'icon'	=>  'simpleCardsFTIcon'
			),
			array(
				'type' => 'overlap',
				'title' => __('Overlap', 'custom-facebook-feed'),
				'icon'	=>  'latestPostFTIcon'
			)
		);

		return $feed_thmes;
	}

	/**
	 * Returns an associate array of all existing feeds along with their data
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function get_feed_list($feeds_args = array())
	{
		$feeds_data = CFF_Db::feeds_query($feeds_args);

		$i = 0;
		foreach ($feeds_data as $single_feed) {
			$args = array(
				'feed_id' => '*' . $single_feed['id'],
				'html_location' => array( 'content' ),
			);
			$count = \CustomFacebookFeed\CFF_Feed_Locator::count($args);

			$content_locations = \CustomFacebookFeed\CFF_Feed_Locator::facebook_feed_locator_query($args);

			// if this is the last page, add in the header footer and sidebar locations
			if (count($content_locations) < CFF_Db::RESULTS_PER_PAGE) {
				$args = array(
					'feed_id' => '*' . $single_feed['id'],
					'html_location' => array( 'header', 'footer', 'sidebar' ),
					'group_by' => 'html_location'
				);
				$other_locations = \CustomFacebookFeed\CFF_Feed_Locator::facebook_feed_locator_query($args);

				$locations = array();

				$combined_locations = array_merge($other_locations, $content_locations);
			} else {
				$combined_locations = $content_locations;
			}

			foreach ($combined_locations as $location) {
				$page_text = get_the_title($location['post_id']);
				if ($location['html_location'] === 'header') {
					$html_location = __('Header', 'custom-facebook-feed');
				} elseif ($location['html_location'] === 'footer') {
					$html_location = __('Footer', 'custom-facebook-feed');
				} elseif ($location['html_location'] === 'sidebar') {
					$html_location = __('Sidebar', 'custom-facebook-feed');
				} else {
					$html_location = __('Content', 'custom-facebook-feed');
				}
				$shortcode_atts = json_decode($location['shortcode_atts'], true);
				$shortcode_atts = is_array($shortcode_atts) ? $shortcode_atts : array();

				$full_shortcode_string = '[custom-facebook-feed';
				foreach ($shortcode_atts as $key => $value) {
					if (! empty($value)) {
						$full_shortcode_string .= ' ' . esc_html($key) . '="' . esc_html($value) . '"';
					}
				}
				$full_shortcode_string .= ']';

				$locations[] = [
					'link' => esc_url(get_the_permalink($location['post_id'])),
					'page_text' => $page_text,
					'html_location' => $html_location,
					'shortcode' => $full_shortcode_string
				];
			}
			$feeds_data[ $i ]['instance_count'] = $count;
			$feeds_data[ $i ]['location_summary'] = $locations;
			$feeds_data[ $i ]['settings'] = json_decode($feeds_data[ $i ]['settings']);

			$i++;
		}
		return $feeds_data;
	}

	/**
	 * Return legacy feed source name
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function get_legacy_feed_name($sources_list, $source_id)
	{
		foreach ($sources_list as $source) {
			if ($source['account_id'] == $source_id) {
				return $source['username'];
			}
		}
		return $source_id;
	}

	/**
	 * Returns an associate array of all existing sources along with their data
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_legacy_feed_list()
	{
		$cff_statuses = get_option('cff_statuses', array());
		$sources_list = CFF_Feed_Builder::get_source_list();

		if (empty($cff_statuses['support_legacy_shortcode'])) {
			return [];
		}

		$args = array(
			'html_location' => array( 'header', 'footer', 'sidebar', 'content' ),
			'group_by' => 'shortcode_atts',
			'page' => 1
		);
		$feeds_data = \CustomFacebookFeed\CFF_Feed_Locator::legacy_facebook_feed_locator_query($args);

		if (empty($feeds_data)) {
			$args = array(
				'html_location' => array( 'header', 'footer', 'sidebar', 'content', 'unknown' ),
				'group_by' => 'shortcode_atts',
				'page' => 1
			);
			$feeds_data = \CustomFacebookFeed\CFF_Feed_Locator::legacy_facebook_feed_locator_query($args);
		}

		$feed_saver = new CFF_Feed_Saver('legacy');
		$settings = $feed_saver->get_feed_settings();

		$default_type = 'timeline';

		if (isset($settings['feedtype'])) {
			$default_type = $settings['feedtype'];
		} elseif (isset($settings['type'])) {
			if (strpos($settings['type'], ',') === false) {
				$default_type = $settings['type'];
			}
		}
		$i = 0;
		$reindex = false;
		foreach ($feeds_data as $single_feed) {
			$args = array(
				'shortcode_atts' => $single_feed['shortcode_atts'],
				'html_location' => array( 'content' ),
			);
			$content_locations = \CustomFacebookFeed\CFF_Feed_Locator::facebook_feed_locator_query($args);

			$count = \CustomFacebookFeed\CFF_Feed_Locator::count($args);
			if (count($content_locations) < CFF_Db::RESULTS_PER_PAGE) {
				$args = array(
					'feed_id' => $single_feed['feed_id'],
					'html_location' => array( 'header', 'footer', 'sidebar' ),
					'group_by' => 'html_location'
				);
				$other_locations = \CustomFacebookFeed\CFF_Feed_Locator::facebook_feed_locator_query($args);

				$combined_locations = array_merge($other_locations, $content_locations);
			} else {
				$combined_locations = $content_locations;
			}

			$locations = array();
			foreach ($combined_locations as $location) {
				$page_text = get_the_title($location['post_id']);
				if ($location['html_location'] === 'header') {
					$html_location = __('Header', 'custom-facebook-feed');
				} elseif ($location['html_location'] === 'footer') {
					$html_location = __('Footer', 'custom-facebook-feed');
				} elseif ($location['html_location'] === 'sidebar') {
					$html_location = __('Sidebar', 'custom-facebook-feed');
				} else {
					$html_location = __('Content', 'custom-facebook-feed');
				}
				$shortcode_atts = json_decode($location['shortcode_atts'], true);
				$shortcode_atts = is_array($shortcode_atts) ? $shortcode_atts : array();

				$full_shortcode_string = '[custom-facebook-feed';
				foreach ($shortcode_atts as $key => $value) {
					if (! empty($value)) {
						$full_shortcode_string .= ' ' . esc_html($key) . '="' . esc_html($value) . '"';
					}
				}
				$full_shortcode_string .= ']';

				$locations[] = [
					'link' => esc_url(get_the_permalink($location['post_id'])),
					'page_text' => $page_text,
					'html_location' => $html_location,
					'shortcode' => $full_shortcode_string
				];
			}
			$shortcode_atts = json_decode($feeds_data[ $i ]['shortcode_atts'], true);
			$shortcode_atts = is_array($shortcode_atts) ? $shortcode_atts : array();

			$full_shortcode_string = '[custom-facebook-feed';
			foreach ($shortcode_atts as $key => $value) {
				if (! empty($value)) {
					$full_shortcode_string .= ' ' . esc_html($key) . '="' . esc_html($value) . '"';
				}
			}
			$full_shortcode_string .= ']';

			$feeds_data[ $i ]['shortcode'] = $full_shortcode_string;
			$feeds_data[ $i ]['instance_count'] = $count;
			$feeds_data[ $i ]['location_summary'] = $locations;
			$feeds_data[ $i ]['feed_name'] = self::get_legacy_feed_name($sources_list, $feeds_data[ $i ]['feed_id']);
			$feeds_data[ $i ]['feed_type'] = $default_type;

			if (isset($shortcode_atts['feedtype'])) {
				$feeds_data[ $i ]['feed_type'] = $shortcode_atts['feedtype'];
			} elseif (isset($shortcode_atts['type'])) {
				if (strpos($shortcode_atts['type'], ',') === false) {
					$feeds_data[ $i ]['feed_type'] = $shortcode_atts['type'];
				}
			}

			if (isset($feeds_data[ $i ]['id'])) {
				unset($feeds_data[ $i ]['id']);
			}

			if (isset($feeds_data[ $i ]['html_location'])) {
				unset($feeds_data[ $i ]['html_location']);
			}

			if (isset($feeds_data[ $i ]['last_update'])) {
				unset($feeds_data[ $i ]['last_update']);
			}

			if (isset($feeds_data[ $i ]['post_id'])) {
				unset($feeds_data[ $i ]['post_id']);
			}

			if (! empty($shortcode_atts['feed'])) {
				$reindex = true;
				unset($feeds_data[ $i ]);
			}

			if (isset($feeds_data[ $i ]['shortcode_atts'])) {
				unset($feeds_data[ $i ]['shortcode_atts']);
			}

			$i++;
		}

		if ($reindex) {
			$feeds_data = array_values($feeds_data);
		}

		// if there were no feeds found in the locator table we still want the legacy settings to be available
		// if it appears as though they had used version 3.x or under at some point.
		if (
			empty($feeds_data)
			 && ! is_array($cff_statuses['support_legacy_shortcode'])
		) {
			$feeds_data = array(
				array(
					'feed_id' => __('Legacy Feed', 'custom-facebook-feed') . ' ' . __('(unknown location)', 'custom-facebook-feed'),
					'feed_name' => __('Legacy Feed', 'custom-facebook-feed') . ' ' . __('(unknown location)', 'custom-facebook-feed'),
					'shortcode' => '[custom-facebook-feed]',
					'feed_type' => '',
					'instance_count' => false,
					'location_summary' => array()
				)
			);
		}

		return $feeds_data;
	}

	/**
	 * Returns an associate array of all existing sources along with their data
	 *
	 * @param int $page
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function get_source_list($page = 1)
	{
		$args['page'] = $page;
		$source_data = CFF_Db::source_query($args);
		$encryption = new SB_Facebook_Data_Encryption();

		$legacy_data = \CustomFacebookFeed\CFF_FB_Settings::get_legacy_settings(array());

		$legacy_id = ! empty($legacy_data['id']) ? $legacy_data['id'] : '';

		$return = array();
		foreach ($source_data as $source) {
			$info = ! empty($source['info']) ? json_decode($encryption->decrypt($source['info'])) : array();
			$avatar = \CustomFacebookFeed\CFF_Parse_Pro::get_avatar($info);

			$source['avatar_url'] = $avatar;

			$source['needs_update'] = CFF_Source::needs_update($source, $info);

			if ($source['account_id'] === $legacy_id) {
				$source['used_in'] = $source['used_in'] + 1;
				if (! isset($source['instances'])) {
					$source['instances'] = array();
				}
				$source['instances'][] = [
					'id' => 'legacy',
					'feed_name' => __('Legacy Feeds', 'custom-facebook-feed'),
					'settings' => $legacy_data,
					'author' => 1,
					'status' => 'publish',
					'last_modified' => '2021-07-07 19:46:09'
				];
			}

			$source['error_encryption'] = false;
			if (isset($source['access_token']) && strpos($source['access_token'], 'IG') === false && strpos($source['access_token'], 'EA') === false && ! $encryption->decrypt($source['access_token'])) {
				$source['error_encryption'] = true;
			}

			if (isset($info->location)) {
				$source['location'] = $info->location;
			}

			$return[] = $source;
		}

		return $return;
	}

	/**
	 * Get Links with UTM
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function get_links_with_utm()
	{
		$license_key = null;
		if (get_option('cff_license_key')) {
			$license_key = get_option('cff_license_key');
		}
		$all_access_bundle = sprintf('https://smashballoon.com/all-access/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=all-feeds&utm_medium=footer-banner&utm_content=learn-more', $license_key);
		$all_access_bundle_popup = sprintf('https://smashballoon.com/all-access/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=balloon&utm_medium=all-access', $license_key);
		$sourceCombineCTA = sprintf('https://smashballoon.com/social-wall/?edd_license_key=%s&upgrade=true&utm_campaign=facebook-pro&utm_source=customizer&utm_medium=sources&utm_content=social-wall', $license_key);

		return array(
			'allAccessBundle' => $all_access_bundle,
			'popup' => array(
				'allAccessBundle' => $all_access_bundle_popup,
				'fbProfile' => 'https://www.facebook.com/SmashBalloon/',
				'twitterProfile' => 'https://twitter.com/smashballoon',
			),
			'sourceCombineCTA' => $sourceCombineCTA,
			'multifeedCTA' => 'https://smashballoon.com/extensions/multifeed/?utm_campaign=facebook-pro&utm_source=customizer&utm_medium=sources&utm_content=multifeed',
			'doc' => 'https://smashballoon.com/docs/facebook/?utm_campaign=facebook-pro&utm_source=support&utm_medium=view-documentation-button&utm_content=view-documentation',
			'blog' => 'https://smashballoon.com/blog/?utm_campaign=facebook-pro&utm_source=support&utm_medium=view-blog-button&utm_content=view-blog',
			'gettingStarted' => 'https://smashballoon.com/docs/getting-started/?utm_campaign=facebook-pro&utm_source=support&utm_medium=getting-started-button&utm_content=getting-started',
		);
	}

	/**
	 * Gets a list of info
	 * Used in multiple places in the feed creator
	 * Other Platforms + Social Links
	 * Upgrade links
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function get_smashballoon_info()
	{
		$smash_info = [
			'colorSchemes' => [
				'facebook' => '#006BFA',
				'twitter' => '#1B90EF',
				'instagram' => '#BA03A7',
				'youtube' => '#EB2121',
				'linkedin' => '#007bb6',
				'mail' => '#666',
				'smash' => '#EB2121'
			],
			'upgrade' => [
				'name' => __('Upgrade to Pro', 'custom-facebook-feed'),
				'icon' => 'facebook',
				'link' => ''
			],
			'platforms' => [
				[
					'name' => __('Twitter Feed Pro', 'custom-facebook-feed'),
					'icon' => 'twitter',
					'link' => 'https://smashballoon.com/custom-twitter-feeds/?utm_campaign=facebook-pro&utm_source=balloon&utm_medium=twitter'
				],
				[
					'name' => __('Instagram Feed Pro', 'custom-facebook-feed'),
					'icon' => 'instagram',
					'link' => 'https://smashballoon.com/instagram-feed/?utm_campaign=facebook-pro&utm_source=balloon&utm_medium=instagram'
				],
				[
					'name' => __('YouTube Feed Pro', 'custom-facebook-feed'),
					'icon' => 'youtube',
					'link' => 'https://smashballoon.com/youtube-feed/?utm_campaign=facebook-pro&utm_source=balloon&utm_medium=youtube'
				],
				[
					'name' => __('Social Wall Plugin', 'custom-facebook-feed'),
					'icon' => 'smash',
					'link' => 'https://smashballoon.com/social-wall/?utm_campaign=facebook-pro&utm_source=balloon&utm_medium=social-wall ',
				]
			],
			'socialProfiles' => [
				'facebook' => 'https://www.facebook.com/SmashBalloon/',
				'twitter' => 'https://twitter.com/smashballoon',
			],
			'morePlatforms' => ['instagram','youtube','twitter']
		];

		return $smash_info;
	}

	/**
	 * Text specific to onboarding. Will return an associative array 'active' => false
	 * if onboarding has been dismissed for the user or there aren't any legacy feeds.
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_onboarding_text()
	{
		// TODO: return if no legacy feeds
		$cff_statuses_option = get_option('cff_statuses', array());

		if (! isset($cff_statuses_option['legacy_onboarding'])) {
			return array( 'active' => false );
		}

		if (
			$cff_statuses_option['legacy_onboarding']['active'] === false
			 || CFF_Feed_Builder::onboarding_status() === 'dismissed'
		) {
			return array( 'active' => false );
		}

		$type = $cff_statuses_option['legacy_onboarding']['type'];

		$text = array(
			'active' => true,
			'type' => $type,
			'legacyFeeds' => array(
				'heading' => __('Legacy Feed Settings', 'custom-facebook-feed'),
				'description' => sprintf(__('These settings will impact %s legacy feeds on your site. You can learn more about what legacy feeds are and how they differ from new feeds %shere%s.', 'custom-facebook-feed'), '<span class="cff-fb-count-placeholder"></span>', '<a href="https://smashballoon.com/doc/facebook-legacy-feeds/" target="_blank" rel="noopener">', '</a>'),
			),
			'getStarted' => __('You can now create and customize feeds individually. Tap "Add New" to get started.', 'custom-facebook-feed'),
		);

		if ($type === 'single') {
			$text['tooltips'] = array(
				array(
					'step' => 1,
					'heading' => __('How you create a feed has changed', 'custom-facebook-feed'),
					'p' => __('You can now create and customize feeds individually without using shortcode options.', 'custom-facebook-feed') . ' ' . __('Click "Add New" to get started.', 'custom-facebook-feed'),
					'pointer' => 'top'
				),
				array(
					'step' => 2,
					'heading' => __('Your existing feed is here', 'custom-facebook-feed'),
					'p' => __('You can edit your existing feed from here, and all changes will only apply to this feed.', 'custom-facebook-feed'),
					'pointer' => 'top'
				)
			);
		} else {
			$text['tooltips'] = array(
				array(
					'step' => 1,
					'heading' => __('How you create a feed has changed', 'custom-facebook-feed'),
					'p' => __('You can now create and customize feeds individually without using shortcode options.', 'custom-facebook-feed') . ' ' . __('Click "Add New" to get started.', 'custom-facebook-feed'),
					'pointer' => 'top'
				),
				array(
					'step' => 2,
					'heading' => __('Your existing feeds are under "Legacy" feeds', 'custom-facebook-feed'),
					'p' => __('You can edit the settings for any existing "legacy" feed (i.e. any feed created prior to this update) here.', 'custom-facebook-feed') . ' ' . __('This works just like the old settings page and affects all legacy feeds on your site.', 'custom-facebook-feed')
				),
				array(
					'step' => 3,
					'heading' => __('Existing feeds work as normal', 'custom-facebook-feed'),
					'p' => __('You don\'t need to update or change any of your existing feeds. They will continue to work as usual.', 'custom-facebook-feed') . ' ' . __('This update only affects how new feeds are created and customized.', 'custom-facebook-feed')
				)
			);
		}

		return $text;
	}

	public function get_customizer_onboarding_text()
	{

		if (CFF_Feed_Builder::onboarding_status('customizer') === 'dismissed') {
			return array( 'active' => false );
		}

		$text = array(
			'active' => true,
			'type' => 'customizer',
			'tooltips' => array(
				array(
					'step' => 1,
					'heading' => __('Embedding a Feed', 'custom-facebook-feed'),
					'p' => __('After you are done customizing the feed, click here to add it to a page or a widget.', 'custom-facebook-feed') . ' ' . __('Click "Add New" to get started.', 'custom-facebook-feed'),
					'pointer' => 'top'
				),
				array(
					'step' => 2,
					'heading' => __('Customize', 'custom-facebook-feed'),
					'p' => __('Change your feed layout, color scheme, or customize individual feed sections here.', 'custom-facebook-feed'),
					'pointer' => 'top'
				),
				array(
					'step' => 3,
					'heading' => __('Settings', 'custom-facebook-feed'),
					'p' => __('Update your feed source, change the feed type, or filter your posts here.', 'custom-facebook-feed'),
					'pointer' => 'top'
				)
			)
		);

		return $text;
	}

	/**
	 * Text related to the feed customizer
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_customize_screens_text()
	{
		$text =  [
			'common' => [
				'preview' => __('Preview', 'custom-facebook-feed'),
				'help' => __('Help', 'custom-facebook-feed'),
				'embed' => __('Embed', 'custom-facebook-feed'),
				'save' => __('Save', 'custom-facebook-feed'),
				'sections' => __('Sections', 'custom-facebook-feed'),
				'enable' => __('Enable', 'custom-facebook-feed'),
				'background' => __('Background', 'custom-facebook-feed'),
				'text' => __('Text', 'custom-facebook-feed'),
				'inherit' => __('Inherit from Theme', 'custom-facebook-feed'),
				'size' => __('Size', 'custom-facebook-feed'),
				'color' => __('Color', 'custom-facebook-feed'),
				'height' => __('Height', 'custom-facebook-feed'),
				'placeholder' => __('Placeholder', 'custom-facebook-feed'),
				'select' => __('Select', 'custom-facebook-feed'),
				'enterText' => __('Enter Text', 'custom-facebook-feed'),
				'hoverState' => __('Hover State', 'custom-facebook-feed'),
				'sourceCombine'	=>  __('Combine sources from multiple platforms using our Social Wall plugin', 'custom-facebook-feed'),
			],

			'tabs' => [
				'customize' => __('Customize', 'custom-facebook-feed'),
				'settings' => __('Settings', 'custom-facebook-feed'),
			],
			'overview' => [
				'feedLayout' => __('Feed Layout', 'custom-facebook-feed'),
				'colorScheme' => __('Color Scheme', 'custom-facebook-feed'),
				'header' => __('Header', 'custom-facebook-feed'),
				'posts' => __('Posts', 'custom-facebook-feed'),
				'likeBox' => __('Like Box', 'custom-facebook-feed'),
				'loadMore' => __('Load More Button', 'custom-facebook-feed'),
			],
			'feedLayoutScreen' => [
				'layout' => __('Layout', 'custom-facebook-feed'),
				'list' => __('List', 'custom-facebook-feed'),
				'grid' => __('Grid', 'custom-facebook-feed'),
				'masonry' => __('Masonry', 'custom-facebook-feed'),
				'carousel' => __('Carousel', 'custom-facebook-feed'),
				'feedHeight' => __('Feed Height', 'custom-facebook-feed'),
				'number' => __('Number of Posts', 'custom-facebook-feed'),
				'columns' => __('Columns', 'custom-facebook-feed'),
				'desktop' => __('Desktop', 'custom-facebook-feed'),
				'tablet' => __('Tablet', 'custom-facebook-feed'),
				'mobile' => __('Mobile', 'custom-facebook-feed'),
				'bottomArea' => [
					'heading' => __('Tweak Post Styles', 'custom-facebook-feed'),
					'description' => __('Change post background, border radius, shadow etc.', 'custom-facebook-feed'),
				]
			],
			'colorSchemeScreen' => [
				'scheme' => __('Scheme', 'custom-facebook-feed'),
				'light' => __('Light', 'custom-facebook-feed'),
				'dark' => __('Dark', 'custom-facebook-feed'),
				'custom' => __('Custom', 'custom-facebook-feed'),
				'customPalette' => __('Custom Palette', 'custom-facebook-feed'),
				'background2' => __('Background 2', 'custom-facebook-feed'),
				'text2' => __('Text 2', 'custom-facebook-feed'),
				'link' => __('Link', 'custom-facebook-feed'),
				'bottomArea' => [
					'heading' => __('Overrides', 'custom-facebook-feed'),
					'description' => __('Colors that have been overridden from individual post element settings will not change. To change them, you will have to reset overrides.', 'custom-facebook-feed'),
					'ctaButton' => __('Reset Overrides.', 'custom-facebook-feed'),
				]
			],
			'headerScreen' => [
				'headerType' => __('Header Type', 'custom-facebook-feed'),
				'visual' => __('Visual', 'custom-facebook-feed'),
				'coverPhoto' => __('Cover Photo', 'custom-facebook-feed'),
				'nameAndAvatar' => __('Name and avatar', 'custom-facebook-feed'),
				'about' => __('About (bio and Likes)', 'custom-facebook-feed'),
				'displayOutside' => __('Display outside scrollable area', 'custom-facebook-feed'),
				'icon' => __('Icon', 'custom-facebook-feed'),
				'iconImage' => __('Icon Image', 'custom-facebook-feed'),
				'iconColor' => __('Icon Color', 'custom-facebook-feed'),
			],
			// all Lightbox in common
			// all Load More in common
			'likeBoxScreen' => [
				'small' => __('Small', 'custom-facebook-feed'),
				'large' => __('Large', 'custom-facebook-feed'),
				'coverPhoto' => __('Cover Photo', 'custom-facebook-feed'),
				'customWidth' => __('Custom Width', 'custom-facebook-feed'),
				'defaultSetTo' => __('By default, it is set to auto', 'custom-facebook-feed'),
				'width' => __('Width', 'custom-facebook-feed'),
				'customCTA' => __('Custom CTA', 'custom-facebook-feed'),
				'customCTADescription' => __('This toggles the custom CTA like "Show now" and "Contact"', 'custom-facebook-feed'),
				'showFans' => __('Show Fans', 'custom-facebook-feed'),
				'showFansDescription' => __('Show visitors which of their friends follow your page', 'custom-facebook-feed'),
				'displayOutside' => __('Display outside scrollable area', 'custom-facebook-feed'),
				'displayOutsideDescription' => __('Make the like box fixed by moving it outside the scrollable area', 'custom-facebook-feed'),
			],
			'postsScreen' => [
				'thumbnail' => __('Thumbnail', 'custom-facebook-feed'),
				'half' => __('Half width', 'custom-facebook-feed'),
				'full' => __('Full width', 'custom-facebook-feed'),
				'useFull' => __('Use full width layout when post width is less than 500px', 'custom-facebook-feed'),
				'postStyle' => __('Post Style', 'custom-facebook-feed'),
				'editIndividual' => __('Edit Individual Elements', 'custom-facebook-feed'),
				'individual' => [
					'description' => __('Hide or show individual elements of a post or edit their options', 'custom-facebook-feed'),
					'name' => __('Name', 'custom-facebook-feed'),
					'edit' => __('Edit', 'custom-facebook-feed'),
					'postAuthor' => __('Post Author', 'custom-facebook-feed'),
					'postText' => __('Post Text', 'custom-facebook-feed'),
					'date' => __('Date', 'custom-facebook-feed'),
					'photosVideos' => __('Photos/Videos', 'custom-facebook-feed'),
					'likesShares' => __('Likes, Shares and Comments', 'custom-facebook-feed'),
					'eventTitle' => __('Event Title', 'custom-facebook-feed'),
					'eventDetails' => __('Event Details', 'custom-facebook-feed'),
					'postAction' => __('Post Action Links', 'custom-facebook-feed'),
					'sharedPostText' => __('Shared Post Text', 'custom-facebook-feed'),
					'sharedLinkBox' => __('Shared Link Box', 'custom-facebook-feed'),
					'postTextDescription' => __('The main text of the Facebook post', 'custom-facebook-feed'),
					'maxTextLength' => __('Maximum Text Length', 'custom-facebook-feed'),
					'characters' => __('Characters', 'custom-facebook-feed'),
					'linkText' => __('Link text to Facebook post', 'custom-facebook-feed'),
					'postDateDescription' => __('The date of the post', 'custom-facebook-feed'),
					'format' => __('Format', 'custom-facebook-feed'),
					'custom' => __('Custom', 'custom-facebook-feed'),
					'learnMoreFormats' => '<a href="https://smashballoon.com/doc/date-formatting-reference/" target="_blank" rel="noopener">' . __('Learn more about custom formats', 'custom-facebook-feed') . '</a>',
					'addTextBefore' => __('Add text before date', 'custom-facebook-feed'),
					'addTextBeforeEG' => __('E.g. Posted', 'custom-facebook-feed'),
					'addTextAfter' => __('Add text after date', 'custom-facebook-feed'),
					'addTextAfterEG' => __('E.g. - posted date', 'custom-facebook-feed'),
					'timezone' => __('Timezone', 'custom-facebook-feed'),
					'tzDescription' => __('Timezone settings are global across all feeds. To update it use the global settings.', 'custom-facebook-feed'),
					'tzCTAText' => __('Go to Global Settings', 'custom-facebook-feed'),
					'photosVideosDescription' => __('Any photos or videos in your posts', 'custom-facebook-feed'),
					'useOnlyOne' => __('Use only one image per post', 'custom-facebook-feed'),
					'postActionLinksDescription' => __('The "View on Facebook" and "Share" links at the bottom of each post', 'custom-facebook-feed'),
					'viewOnFBLink' => __('View on Facebook link', 'custom-facebook-feed'),
					'viewOnFBLinkDescription' => __('Toggle "View on Facebook" link below each post', 'custom-facebook-feed'),
					'customizeText' => __('Customize Text', 'custom-facebook-feed'),
					'shareLink' => __('Share Link', 'custom-facebook-feed'),
					'shareLinkDescription' => __('Toggle "Share" link below each post', 'custom-facebook-feed'),
					'likesSharesDescription' => __('The comments box displayed at the bottom of each timeline post', 'custom-facebook-feed'),
					'iconTheme' => __('Icon Theme', 'custom-facebook-feed'),
					'auto' => __('Auto', 'custom-facebook-feed'),
					'light' => __('Light', 'custom-facebook-feed'),
					'dark' => __('Dark', 'custom-facebook-feed'),
					'expandComments' => __('Expand comments box by default', 'custom-facebook-feed'),
					'hideComment' => __('Hide comment avatars', 'custom-facebook-feed'),
					'showLightbox' => __('Show comments in lightbox', 'custom-facebook-feed'),
					'eventTitleDescription' => __('The title of an event', 'custom-facebook-feed'),
					'eventDetailsDescription' => __('The information associated with an event', 'custom-facebook-feed'),
					'textSize' => __('Text Size', 'custom-facebook-feed'),
					'textColor' => __('Text Color', 'custom-facebook-feed'),
					'sharedLinkBoxDescription' => __("The link info box that's created when a link is shared in a Facebook post", 'custom-facebook-feed'),
					'boxStyle' => __('Box Style', 'custom-facebook-feed'),
					'removeBackground' => __('Remove background/border', 'custom-facebook-feed'),
					'linkTitle' => __('Link Title', 'custom-facebook-feed'),
					'linkURL' => __('Link URL', 'custom-facebook-feed'),
					'linkDescription' => __('Link Description', 'custom-facebook-feed'),
					'chars' => __('chars', 'custom-facebook-feed'),
					'sharedPostDescription' => __('The description text associated with shared photos, videos, or links', 'custom-facebook-feed'),
				],
				'postType' => __('Post Type', 'custom-facebook-feed'),
				'boxed' => __('boxed', 'custom-facebook-feed'),
				'regular' => __('Regular', 'custom-facebook-feed'),
				'indvidualProperties' => __('Indvidual Properties', 'custom-facebook-feed'),
				'backgroundColor' => __('Background Color', 'custom-facebook-feed'),
				'borderRadius' => __('Border Radius', 'custom-facebook-feed'),
				'boxShadow' => __('Box Shadow', 'custom-facebook-feed'),
			],
		];

		$text['onboarding'] = $this->get_customizer_onboarding_text();

		return $text;
	}


	/**
	 * Get Social Share Links
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	function get_social_share_link()
	{
		return [
			'facebook'	=> 'https://www.facebook.com/sharer/sharer.php?u=',
			'twitter' 	=> 'https://twitter.com/intent/tweet?text=',
			'linkedin'	=> 'https://www.linkedin.com/shareArticle?mini=true&amp;url=',
			'mail' 	=> 'mailto:?subject=Facebook&amp;body=',
		];
	}

	/**
	 * Creating a Dummy Lightbox Data
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	function get_dummy_lightbox_data()
	{
		return [
			'visibility' => 'hidden',
			'image' => CFF_BUILDER_URL . 'assets/img/dummy-lightbox.jpeg',
			'post'	=> [
				'id' => '410484879066269_4042924559155598',
				'updated_time' => '2021-06-07T22:45:17+0000',
				'from' => [
					'picture' => [
						'data' => [
							'height' => 50,
							'is_silhouette' => false,
							'url' => CFF_BUILDER_URL . 'assets/img/dummy-author.png',
							'width' => 50
						]
					],
					'id' => '410484879066269',
					'name' => 'Smash Balloon',
					'link' => 'https://www.facebook.com/410484879066269'
				],
				'message' => 'This is example text to show how it is displayed inside the lightbox. This is an example <a>Link</a> inside the lightbox text.',
				'message_tags' => [],
				'status_type' => 'added_photos',
				'created_time' => '2021-05-31T14:00:30+0000',
				"shares"	=> [
					"count" => 29
				],

				// HERE COMMENTs
				'comments' => [
					'data'	=> [
						[
							'created_time' => '2021-06-02T01:25:27+0000',
							'from' => [
								'name' => 'John Doe',
								'id' => '3933732853410659',
								'picture' => [
									'data' => [
										'url' => CFF_BUILDER_URL . 'assets/img/dummy-author.png',
									]
								],
							],
							'id' => "4042924559155598_4048646911916696",
							'message' => 'It is a long established fact that a reader will be distracted by the readable content.',
							'like_count' => 0
						],
						[
							'created_time' => '2021-06-02T01:25:27+0000',
							'from' => [
								'name' => 'Jane Parker',
								'id' => '3933732853410659',
								'picture' => [
									'data' => [
										'url' => CFF_BUILDER_URL . 'assets/img/dummy-author.png',
									]
								],
							],
							'id' => "4042924559155598_4048646911916696",
							'message' => 'It is a long established fact that a reader will be distracted by the readable content.',
							'like_count' => 0
						]
					],
					'summary' => [
						'total_count' => 14,
						'can_comment' => false,
						'order' => "ranked"
					]
				],
				'likes' => [
					'data' => [],
					'summary' => [
						'total_count' => 14,
						'can_like' => false,
						'has_liked' => false
					]
				],
				'privacy' => [
					'allow' => '',
					'deny' => '',
					'description' => 'Public',
					'friends' => '',
					'value' => 'EVERYONE'
				]
			]
		];
	}


	/**
	 * Get Translated text Set in the Settings Page
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	function get_translated_text()
	{
		$translations = get_option('cff_style_settings', array());
		$translations['cff_facebook_link_text'] = isset($translations['cff_facebook_link_text']) ? stripslashes(esc_attr($translations['cff_facebook_link_text'])) : __('View on Facebook', 'custom-facebook-feed');
		$translations['cff_facebook_share_text'] = isset($translations['cff_facebook_share_text']) ? stripslashes(esc_attr($translations['cff_facebook_share_text'])) : __('Share', 'custom-facebook-feed');
		$translations['cff_load_more_text'] = isset($translations[ 'cff_load_more_text' ]) ? stripslashes(esc_attr($translations[ 'cff_load_more_text' ])) : __('Load more', 'custom-facebook-feed');
		$translations['cff_reviews_link_text'] = isset($translations[ 'cff_reviews_link_text' ]) ? stripslashes(esc_attr($translations[ 'cff_reviews_link_text' ])) : __('View all Reviews', 'custom-facebook-feed');


		$text =  [
			'translations' => $translations,
			'seeMoreText' => __('See More', 'custom-facebook-feed'),
			'seeLessText' => __('See Less', 'custom-facebook-feed'),
			'secondText' => __('second', 'custom-facebook-feed'),
			'secondsText' => __('seconds', 'custom-facebook-feed'),
			'minuteText' => __('minute', 'custom-facebook-feed'),
			'minutesText' => __('minutes', 'custom-facebook-feed'),
			'hourText' => __('hour', 'custom-facebook-feed'),
			'hoursText' => __('hours', 'custom-facebook-feed'),
			'dayText' => __('day', 'custom-facebook-feed'),
			'daysText' => __('days', 'custom-facebook-feed'),
			'weekText' => __('week', 'custom-facebook-feed'),
			'weeksText' => __('weeks', 'custom-facebook-feed'),
			'monthText' => __('month', 'custom-facebook-feed'),
			'monthsText' => __('months', 'custom-facebook-feed'),
			'yearText' => __('year', 'custom-facebook-feed'),
			'yearsText' => __('years', 'custom-facebook-feed'),
			'agoText' => __('ago', 'custom-facebook-feed'),
			'commentonFacebookText' => __('Comment on Facebook', 'custom-facebook-feed'),
			'comments_label' => __('Comments', 'custom-facebook-feed'),
		];

		return $text;
	}

	/**
	 * Status of the onboarding sequence for specific user
	 *
	 * @return string|boolean
	 *
	 * @since 4.0
	 */
	public static function onboarding_status($type = 'newuser')
	{
		$onboarding_statuses = get_user_meta(get_current_user_id(), 'cff_onboarding', true);
		$status = false;
		if (! empty($onboarding_statuses)) {
			$statuses = maybe_unserialize($onboarding_statuses);
			$status = isset($statuses[ $type ]) ? $statuses[ $type ] : false;
		}

		return $status;
	}

	/**
	 * Update status of onboarding sequence for specific user
	 *
	 * @return string|boolean
	 *
	 * @since 4.0
	 */
	public static function update_onboarding_meta($value, $type = 'newuser')
	{
		$onboarding_statuses = get_user_meta(get_current_user_id(), 'cff_onboarding', true);
		if (! empty($onboarding_statuses)) {
			$statuses = maybe_unserialize($onboarding_statuses);
			$statuses[ $type ] = $value;
		} else {
			$statuses = array(
				$type => $value
			);
		}

		$statuses = maybe_serialize($statuses);

		update_user_meta(get_current_user_id(), 'cff_onboarding', $statuses);
	}

	/**
	 * Checks & Returns the list of Active Extensions
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public function get_active_extensions()
	{
		$active_extensions = array(
			'multifeed' => \CustomFacebookFeed\CFF_FB_Settings::check_active_extension('multifeed'),
			'date_range' => cff_should_disable_pro() ? false : \CustomFacebookFeed\CFF_FB_Settings::check_active_extension('date_range'),
			'featured_post' => \CustomFacebookFeed\CFF_FB_Settings::check_active_extension('featured_post'),
			'album' => \CustomFacebookFeed\CFF_FB_Settings::check_active_extension('album'),
			'carousel' => cff_should_disable_pro() ? false : \CustomFacebookFeed\CFF_FB_Settings::check_active_extension('carousel'),
			'reviews' => \CustomFacebookFeed\CFF_FB_Settings::check_active_extension('reviews'),
			// Fake
			'lightbox' => false,
			'advancedFilter'  => false,
			'postSettings'  => false,
			'mediaComment'	=> false,
			'loadMore'	=> false,
		);

		return $active_extensions;
	}

	/**
	 * Plugins information for plugin install modal in all feeds page on select source flow
	 *
	 * @since 4.0
	 *
	 * @return array
	 */
	public static function install_plugins_popup()
	{
		// get the WordPress's core list of installed plugins
		if (! function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$installed_plugins = get_plugins();

		$is_reviews_installed = false;
		$reviews_plugin       = 'reviews-feed/sb-reviews.php';
		if (isset($installed_plugins['reviews-feed-pro/sb-reviews-pro.php'])) {
			$is_reviews_installed = true;
			$reviews_plugin       = 'reviews-feed-pro/sb-reviews-pro.php';
		} elseif (isset($installed_plugins['reviews-feed/sb-reviews.php'])) {
			$is_reviews_installed = true;
		}

		$is_instagram_installed = false;
		$instagram_plugin = 'instagram-feed/instagram-feed.php';
		if (isset($installed_plugins['instagram-feed-pro/instagram-feed.php'])) {
			$is_instagram_installed = true;
			$instagram_plugin = 'instagram-feed-pro/instagram-feed.php';
		} elseif (isset($installed_plugins['instagram-feed/instagram-feed.php'])) {
			$is_instagram_installed = true;
		}

		$is_twitter_installed = false;
		$twitter_plugin = 'custom-twitter-feeds/custom-twitter-feed.php';
		if (isset($installed_plugins['custom-twitter-feeds-pro/custom-twitter-feed.php'])) {
			$is_twitter_installed = true;
			$twitter_plugin = 'custom-twitter-feeds-pro/custom-twitter-feed.php';
		} elseif (isset($installed_plugins['custom-twitter-feeds/custom-twitter-feed.php'])) {
			$is_twitter_installed = true;
		}

		$is_youtube_installed = false;
		$youtube_plugin = 'feeds-for-youtube/youtube-feed.php';
		if (isset($installed_plugins['youtube-feed-pro/youtube-feed.php'])) {
			$is_youtube_installed = true;
			$youtube_plugin = 'youtube-feed-pro/youtube-feed.php';
		} elseif (isset($installed_plugins['feeds-for-youtube/youtube-feed.php'])) {
			$is_youtube_installed = true;
		}

		return array(
			'reviews' => array(
				'displayName'         => __('Reviews', 'instagram-feed'),
				'name'                => __('Reviews Feed', 'instagram-feed'),
				'author'              => __('By Smash Balloon', 'instagram-feed'),
				'description'         => __('To display a Reviews feed, our Reviews plugin is required. </br> Increase conversions and build positive brand trust through Google and Yelp reviews from your customers. Provide social proof needed to turn visitors into customers.', 'instagram-feed'),
				'dashboard_permalink' => admin_url('admin.php?page=sbr'),
				'svgIcon'             => self::builder_svg_icons('install-plugins-popup.reviews'),
				'installed'           => $is_reviews_installed,
				'activated'           => is_plugin_active($reviews_plugin),
				'plugin'              => $reviews_plugin,
				'download_plugin'     => 'https://downloads.wordpress.org/plugin/reviews-feed.zip',
			),
			'instagram' => array(
				'displayName' => __('Instagram', 'custom-facebook-feed'),
				'name' => __('Instagram Feed', 'custom-facebook-feed'),
				'author' => __('By Smash Balloon', 'custom-facebook-feed'),
				'description' => __('To display an Instagram feed, our Instagram plugin is required. </br> It provides a clean and beautiful way to add your Instagram posts to your website. Grab your visitors attention and keep them engaged with your site longer.', 'custom-facebook-feed'),
				'dashboard_permalink' => admin_url('admin.php?page=sbi-feed-builder'),
				'svgIcon' => self::builder_svg_icons('install-plugins-popup.instagram'),
				'installed' => $is_instagram_installed,
				'activated' => is_plugin_active($instagram_plugin),
				'plugin' => $instagram_plugin,
				'download_plugin' => 'https://downloads.wordpress.org/plugin/instagram-feed.zip',
			),
			'twitter' => array(
				'displayName' => __('Twitter', 'custom-facebook-feed'),
				'name' => __('Twitter Feed', 'custom-facebook-feed'),
				'author' => __('By Smash Balloon', 'custom-facebook-feed'),
				'description' => __('Custom Twitter Feeds is a highly customizable way to display tweets from your Twitter account. Promote your latest content and update your site content automatically.', 'custom-facebook-feed'),
				'dashboard_permalink' => admin_url('admin.php?page=custom-twitter-feeds'),
				'svgIcon' => self::builder_svg_icons('install-plugins-popup.twitter'),
				'installed' => $is_twitter_installed,
				'activated' => is_plugin_active($twitter_plugin),
				'plugin' => $twitter_plugin,
				'download_plugin' => 'https://downloads.wordpress.org/plugin/custom-twitter-feeds.zip',
			),
			'youtube' => array(
				'displayName' => __('YouTube', 'custom-facebook-feed'),
				'name' => __('Feeds for YouTube', 'custom-facebook-feed'),
				'author' => __('By Smash Balloon', 'custom-facebook-feed'),
				'description' => __('To display a YouTube feed, our YouTube plugin is required. It provides a simple yet powerful way to display videos from YouTube on your website, Increasing engagement with your channel while keeping visitors on your website.', 'custom-facebook-feed'),
				'dashboard_permalink' => admin_url('admin.php?page=youtube-feed'),
				'svgIcon' => self::builder_svg_icons('install-plugins-popup.youtube'),
				'installed' => $is_youtube_installed,
				'activated' => is_plugin_active($youtube_plugin),
				'plugin' => $youtube_plugin,
				'download_plugin' => 'https://downloads.wordpress.org/plugin/feeds-for-youtube.zip',
			),
		);
	}

	/**
	 * For Other Platforms listed on the footer widget
	 *
	 * @return string
	 *
	 * @since 4.0
	 */
	public static function builder_svg_icons($icon = null)
	{

		// If the icon is set, load the SVG file and return it.
		if (! empty($icon)) {
			$icon_folder = explode('.', $icon);
			if (count($icon_folder) > 1) {
				$folder   = $icon_folder[0];
				$icon     = $icon_folder[1];
				$svg_path = CFF_PLUGIN_DIR . 'assets/svgs/' . $folder . '/' . $icon . '.svg';
			} else {
				$svg_path = CFF_PLUGIN_DIR . 'assets/svgs/' . $icon . '.svg';
			}
			if (is_file($svg_path)) {
				return file_get_contents($svg_path);
			}
		}

		return '';
	}

	/**
	 * Color Overrides Manager
	 *
	 * @return array
	 *
	 * @since 4.0
	 */
	public static function get_color_overrides()
	{
		return [
			// Post Author
			[
				'heading' 		=> __('Post Author', 'custom-facebook-feed'),
				'elements'		=> ['authorcolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						0,
						'section'
					],
					'id' => 'post_styling_author'
				],
				'controls' => [
					[
						'heading' => __('Text', 'custom-facebook-feed'),
						'id'	  => 'authorcolor'
					]
				]
			],
			// Post Text
			[
				'heading' => __('Post', 'custom-facebook-feed'),
				'elements'	=> ['textcolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						1,
						'section'
					],
					'id' => 'post_styling_text'
				],
				'controls' => [
					[
						'heading' => __('Text', 'custom-facebook-feed'),
						'id'	  => 'textcolor'
					]
				]
			],
			// Post Date
			[
				'heading' => __('Post Date', 'custom-facebook-feed'),
				'elements'	=> ['datecolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						2,
						'section'
					],
					'id' => 'post_styling_date'
				],
				'controls' => [
					[
						'heading' => __('Text', 'custom-facebook-feed'),
						'id'	  => 'datecolor'
					]
				]
			],
			// Likes Shares & Comments
			[
				'heading' => __('Likes, Shares and Comments', 'custom-facebook-feed'),
				'elements'	=> ['socialtextcolor','sociallinkcolor','socialbgcolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						4,
						'section'
					],
					'id' => 'post_styling_social'
				],
				'controls' => [
					[
						'heading' => __('Text', 'custom-facebook-feed'),
						'id'	  => 'socialtextcolor'
					],
					[
						'heading' => __('Link', 'custom-facebook-feed'),
						'id'	  => 'sociallinkcolor'
					],
					[
						'heading' => __('Background', 'custom-facebook-feed'),
						'id'	  => 'socialbgcolor'
					]
				]
			],
			// Event Title
			[
				'heading' => __('Event Title', 'custom-facebook-feed'),
				'elements'	=> ['eventtitlecolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						5,
						'section'
					],
					'id' => 'post_styling_eventtitle'
				],
				'controls' => [
					[
						'heading' => __('Text', 'custom-facebook-feed'),
						'id'	  => 'eventtitlecolor'
					]
				]
			],
			// Event Details
			[
				'heading' => __('Event Details', 'custom-facebook-feed'),
				'elements'	=> ['eventdetailscolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						6,
						'section'
					],
					'id' => 'post_styling_eventdetails'
				],
				'controls' => [
					[
						'heading' => __('Text', 'custom-facebook-feed'),
						'id'	  => 'eventdetailscolor'
					]
				]
			],
			// Link
			[
				'heading' => __('Post Action Links', 'custom-facebook-feed'),
				'elements'	=> ['linkcolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						7,
						'section'
					],
					'id' => 'post_styling_link'
				],
				'controls' => [
					[
						'heading' => __('Text', 'custom-facebook-feed'),
						'id'	  => 'linkcolor'
					]
				]
			],
			// Description
			/*
			[
				'heading' => __( 'Shared Post', 'custom-facebook-feed' ),
				'elements'	=> ['desccolor'],
				'controls' => [
					[
						'heading' => __( 'Text', 'custom-facebook-feed' ),
						'id'	  => 'desccolor'
					]
				]
			],*/
			[
				'heading' => __('Shared Link Box', 'custom-facebook-feed'),
				'elements'	=> ['linkbgcolor','linktitlecolor','linkdesccolor','linkurlcolor'],
				'enableViewAction' 	=> [
					'sections' => [
						'customize',
						'sections',
						'customize_posts',
						'nested_sections',
						'individual_elements',
						'controls',
						8,
						'section'
					],
					'id' => 'post_styling_sharedlinks'
				],
				'controls' => [
					[
						'heading' => __('Background', 'custom-facebook-feed'),
						'id'	  => 'linkbgcolor'
					],
					[
						'heading' => __('Title', 'custom-facebook-feed'),
						'id'	  => 'linktitlecolor'
					],
					[
						'heading' => __('Description', 'custom-facebook-feed'),
						'id'	  => 'linkdesccolor'
					],
					[
						'heading' => __('Url', 'custom-facebook-feed'),
						'id'	  => 'linkurlcolor'
					]
				]
			]

		];
	}

	public static function get_social_wall_links()
	{
		return array(
			'<a href="' . esc_url(admin_url('admin.php?page=cff-feed-builder')) . '">' . __('All Feeds', 'custom-facebook-feed') . '</a>',
			'<a href="' . esc_url(admin_url('admin.php?page=cff-settings')) . '">' . __('Settings', 'custom-facebook-feed') . '</a>',
			'<a href="' . esc_url(admin_url('admin.php?page=cff-oembeds-manager')) . '">' . __('oEmbeds', 'custom-facebook-feed') . '</a>',
			'<a href="' . esc_url(admin_url('admin.php?page=cff-extensions-manager')) . '">' . __('Extensions', 'custom-facebook-feed') . '</a>',
			'<a href="' . esc_url(admin_url('admin.php?page=cff-about-us')) . '">' . __('About Us', 'custom-facebook-feed') . '</a>',
			'<a href="' . esc_url(admin_url('admin.php?page=cff-support')) . '">' . __('Support', 'custom-facebook-feed') . '</a>',
		);
	}

	/**
	 * Feed Builder Wrapper.
	 *
	 * @since 4.0
	 */
	public function feed_builder()
	{
		include_once CFF_BUILDER_DIR . 'templates/builder.php';
	}


	/**
	 * Get Smahballoon Plugins Info
	 *
	 * @since 4.3
	 */
	public static function get_smashballoon_plugins_info()
	{

		$installed_plugins = get_plugins();
		// check whether the pro or free plugins are installed
		$is_facebook_installed = false;
		$facebook_plugin = 'custom-facebook-feed/custom-facebook-feed.php';
		if (isset($installed_plugins['custom-facebook-feed-pro/custom-facebook-feed.php'])) {
			$is_facebook_installed = true;
			$facebook_plugin = 'custom-facebook-feed-pro/custom-facebook-feed.php';
		} elseif (isset($installed_plugins['custom-facebook-feed/custom-facebook-feed.php'])) {
			$is_facebook_installed = true;
		}

		$is_instagram_installed = false;
		$instagram_plugin = 'instagram-feed/instagram-feed.php';
		if (isset($installed_plugins['instagram-feed-pro/instagram-feed.php'])) {
			$is_instagram_installed = true;
			$instagram_plugin = 'instagram-feed-pro/instagram-feed.php';
		} elseif (isset($installed_plugins['instagram-feed/instagram-feed.php'])) {
			$is_instagram_installed = true;
		}

		$is_twitter_installed = false;
		$twitter_plugin = 'custom-twitter-feeds/custom-twitter-feed.php';
		if (isset($installed_plugins['custom-twitter-feeds-pro/custom-twitter-feed.php'])) {
			$is_twitter_installed = true;
			$twitter_plugin = 'custom-twitter-feeds-pro/custom-twitter-feed.php';
		} elseif (isset($installed_plugins['custom-twitter-feeds/custom-twitter-feed.php'])) {
			$is_twitter_installed = true;
		}

		$is_youtube_installed = false;
		$youtube_plugin = 'feeds-for-youtube/youtube-feed.php';
		if (isset($installed_plugins['youtube-feed-pro/youtube-feed.php'])) {
			$is_youtube_installed = true;
			$youtube_plugin = 'youtube-feed-pro/youtube-feed.php';
		} elseif (isset($installed_plugins['feeds-for-youtube/youtube-feed.php'])) {
			$is_youtube_installed = true;
		}



		return [
			'facebook' => [
				'installed' => $is_facebook_installed,
				'class' => 'CFF_Elementor_Widget',
				'link' => 'https://smashballoon.com/custom-facebook-feed/',
				'icon' => self::builder_svg_icons('install-plugins-popup.facebook'),
				'description' => __('Custom Facebook Feeds is a highly customizable way to display tweets from your Facebook account. Promote your latest content and update your site content automatically.', 'custom-facebook-feed'),
				'download_plugin' => 'https://downloads.wordpress.org/plugin/custom-facebook-feed.zip',
			],
			'instagram' => [
				'installed' => $is_instagram_installed,
				'class' => 'SBI_Elementor_Widget',
				'link' => 'https://smashballoon.com/instagram-feed/',
				'icon' => self::builder_svg_icons('install-plugins-popup.instagram'),
				'description' => __('Instagram Feeds is a highly customizable way to display tweets from your Instagram account. Promote your latest content and update your site content automatically.', 'custom-facebook-feed'),
				'download_plugin' => 'https://downloads.wordpress.org/plugin/instagram-feed.zip',
			],
			'twitter' => [
				'installed' => $is_twitter_installed,
				'class' => 'CTF_Elementor_Widget',
				'link' => 'https://smashballoon.com/custom-twitter-feeds/',
				'icon' => self::builder_svg_icons('install-plugins-popup.twitter'),
				'description' => __('Custom Twitter Feeds is a highly customizable way to display tweets from your Twitter account. Promote your latest content and update your site content automatically.', 'custom-facebook-feed'),
				'download_plugin' => 'https://downloads.wordpress.org/plugin/custom-twitter-feeds.zip',
			],
			'youtube' => [
				'installed' => $is_youtube_installed,
				'class' => 'SBY_Elementor_Widget',
				'link' => 'https://smashballoon.com/youtube-feed/',
				'icon' => self::builder_svg_icons('install-plugins-popup.youtube'),
				'description' => __('YouTube Feeds is a highly customizable way to display tweets from your YouTube account. Promote your latest content and update your site content automatically.', 'custom-facebook-feed'),
				'download_plugin' => 'https://downloads.wordpress.org/plugin/feeds-for-youtube.zip',
			]
		];
	}
}
