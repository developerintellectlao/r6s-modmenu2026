<?php

namespace CustomFacebookFeed\Integrations\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use CustomFacebookFeed\Builder\CFF_Db;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class SBI_Elementor_Widget extends Widget_Base
{
	public function get_name()
	{
		return 'sbi-widget';
	}

	public function get_title()
	{
		return esc_html__('Instagram Feed', 'custom-facebook-feed');
	}

	public function get_icon()
	{
		return 'sb-elem-icon sb-elem-inactive sb-elem-instagram';
	}


	public function get_categories()
	{
		return array('smash-balloon');
	}

	public function get_script_depends()
	{
		return [
			'elementor-handler'
		];
	}

	protected function render()
	{
	}
}
