<?php

namespace CustomFacebookFeed\Integrations\Elementor;

use CustomFacebookFeed\Cff_Utils;
use CustomFacebookFeed\Builder\CFF_Feed_Builder;
use CustomFacebookFeed\Integrations\Elementor\CFF_Elementor_Widget;

class CFF_Elementor_Base
{
	const VERSION = CFFVER;
	const MINIMUM_ELEMENTOR_VERSION = '3.6.0';
	const MINIMUM_PHP_VERSION = '5.6';
	const NAME_SPACE = 'CustomFacebookFeed.Integrations.Elementor.';
	private static $instance;


	public static function instance()
	{
		if (!isset(self::$instance) && !self::$instance instanceof CFF_Elementor_Base) {
			self::$instance = new CFF_Elementor_Base();
			self::$instance->apply_hooks();
		}
		return self::$instance;
	}

	private function apply_hooks()
	{
		add_action('elementor/frontend/after_register_scripts', [$this, 'register_frontend_scripts']);
		add_action('elementor/frontend/after_register_styles', [$this, 'register_frontend_styles'], 10);
		add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_frontend_styles'], 10);
		add_action('elementor/controls/register', [$this, 'register_controls']);
		add_action('elementor/widgets/register', [$this,'register_widgets']);
		add_action('elementor/elements/categories_registered', [$this, 'add_smashballon_categories']);
	}

	public function register_controls($controls_manager)
	{
		$controls_manager->register(new CFF_Elementor_Control());
	}


	public function register_widgets($widgets_manager)
	{
		$widgets_manager->register(new CFF_Elementor_Widget());

		$installed_plugins = CFF_Feed_Builder::get_smashballoon_plugins_info();
		unset($installed_plugins['facebook']);

		foreach ($installed_plugins as $plugin) {
			if (!$plugin['installed']) {
				$plugin_class = str_replace('.', '\\', self::NAME_SPACE) . $plugin['class'];
				$widgets_manager->register(new $plugin_class());
			}
		}
	}


	public function register_frontend_scripts()
	{
		$data = array(
			'placeholder' => CFF_PLUGIN_URL . 'assets/img/placeholder.png',
			'resized_url' => Cff_Utils::cff_get_resized_uploads_url(),
		);
		$cff_min = isset($_GET['sb_debug']) ? '' : '.min';


		wp_register_script(
			'cffscripts',
			CFF_PLUGIN_URL . 'assets/js/cff-scripts' . $cff_min . '.js',
			array('jquery'),
			CFFVER,
			true
		);
		wp_localize_script('cffscripts', 'cffOptions', $data);


		$data_handler = array(
			'smashPlugins'  => CFF_Feed_Builder::get_smashballoon_plugins_info(),
			'nonce'         => wp_create_nonce('cff-admin'),
			'ajax_handler'      =>  admin_url('admin-ajax.php'),

		);

		wp_register_script(
			'elementor-handler',
			CFF_PLUGIN_URL . 'admin/assets/js/elementor-handler.js',
			array('jquery'),
			CFFVER,
			true
		);

		wp_localize_script('elementor-handler', 'sbHandler', $data_handler);

		wp_register_script(
			'elementor-preview',
			CFF_PLUGIN_URL . 'admin/assets/js/elementor-preview.js',
			array('jquery'),
			CFFVER,
			true
		);
	}

	public function register_frontend_styles()
	{
		$cff_min = isset($_GET['sb_debug']) ? '' : '.min';
		wp_register_style(
			'cffstyles',
			CFF_PLUGIN_URL . 'assets/css/cff-style' . $cff_min . '.css',
			array(),
			CFFVER
		);
	}

	public function enqueue_frontend_styles()
	{
		wp_enqueue_style('cffstyles');
	}

	public function add_smashballon_categories($elements_manager)
	{
		$elements_manager->add_category(
			'smash-balloon',
			[
				'title' => esc_html__('Smash Balloon', 'custom-facebook-feed'),
				'icon' => 'fa fa-plug',
			]
		);
	}
}
