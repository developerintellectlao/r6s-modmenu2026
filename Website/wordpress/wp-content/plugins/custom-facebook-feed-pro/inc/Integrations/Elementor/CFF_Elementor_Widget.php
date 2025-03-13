<?php

namespace CustomFacebookFeed\Integrations\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use CustomFacebookFeed\CFF_Utils;
use CustomFacebookFeed\Builder\CFF_Db;
use CustomFacebookFeed\Builder\CFF_Feed_Builder;
use CustomFacebookFeed\Integrations\CFF_Integration;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class CFF_Elementor_Widget extends Widget_Base
{
	public function get_name()
	{
		return 'cff-widget';
	}
	public function get_title()
	{
		return esc_html__('Facebook Feed', 'custom-facebook-feed');
	}
	public function get_icon()
	{
		return 'sb-elem-icon sb-elem-facebook';
	}
	public function get_categories()
	{
		return array('smash-balloon');
	}
	public function get_script_depends()
	{
		return [
			'cffscripts',
			'elementor-preview'
		];
	}



	protected function register_controls()
	{
		/********************************************
					CONTENT SECTION
		*/
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__('Facebook Feed Settings', 'custom-facebook-feed'),
			]
		);
		$this->add_control(
			'feed_id',
			[
				'label' => esc_html__('Select a Feed', 'custom-facebook-feed'),
				'type' => 'cff_feed_control',
				'label_block' => true,
				'dynamic' => ['active' => true],
				'options' =>  CFF_Db::elementor_feeds_query(),
			]
		);
		$this->end_controls_section();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		if (isset($settings['feed_id']) && !empty($settings['feed_id'])) {
			$output = do_shortcode(shortcode_unautop('[custom-facebook-feed feed=' . $settings['feed_id'] . ']'));
		} else {
			$output = is_admin() ? CFF_Integration::get_widget_cta() : '';
		}
		echo apply_filters('cff_output', $output, $settings);
	}
}
