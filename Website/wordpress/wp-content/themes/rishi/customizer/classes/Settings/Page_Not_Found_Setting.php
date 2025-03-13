<?php

namespace Rishi\Customizer\Settings;
use \Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts\Customize_Settings;

class Page_Not_Found_Setting extends Customize_Settings {

	protected function add_settings() {
		$this->add_setting( '404_image', array(
			'label'        => __( 'Upload 404 Image', 'rishi' ),
			'control'      => ControlTypes::IMAGE_UPLOADER,
			'value'        => [],
			'emptyLabel'   => __( 'Upload Image', 'rishi' ),
			'filledLabel'  => __( 'Change Image', 'rishi' ),
		) );
		$this->add_setting(
			'404_show_latest_post',
			array(
				'label'   => __( 'Show Latest Posts', 'rishi' ),
				'control' => ControlTypes::INPUT_SWITCH,
				'value'   => 'yes',
				'divider' => 'top',
			)
		);
		$this->add_setting(
			'404_show_search_form',
			array(
				'label'   => __( 'Show Search Form', 'rishi' ),
				'control' => ControlTypes::INPUT_SWITCH,
				'value'   => 'yes',
				'divider' => 'top',
			)
		);

		$this->add_setting(
			'404_no_of_posts',
			array(
				'label'      => __( 'Number of Posts', 'rishi' ),
				'control'    => ControlTypes::INPUT_NUMBER,
				'design'     => 'inline',
				'value'      => 3,
				'min'        => 1,
				'max'        => 12,
				'divider'    => 'top',
				'responsive' => false,
			)
		);

		$this->add_setting(
			'404_no_of_posts_row',
			array(
				'label'      => __( 'Number of Posts per Row', 'rishi' ),
				'control'    => ControlTypes::INPUT_NUMBER,
				'design'     => 'inline',
				'value'      => 3,
				'min'        => 1,
				'max'        => 4,
				'divider'    => 'top',
				'responsive' => false,
			)
		);

		$this->add_setting(
			'404_show_blog_page_button',
			array(
				'label'   => __( 'Show Blog Page Button', 'rishi' ),
				'control' => ControlTypes::INPUT_SWITCH,
				'value'   => 'yes',
				'divider' => 'top',
			)
		);

		$this->add_setting('404_show_blog_page_button_label', array(
			'label' => __('Label ', 'rishi'),
			'help' => __('Add label to your button in the 404 error page.', 'rishi'),
			'control' => ControlTypes::INPUT_TEXT,
			'design' => 'block',
			'divider' => 'top:bottom',
			'value' => __('Go To Blog', 'rishi'),
		));
	}

	protected static function get_layout_default_value() {

		$defaults = array(
			'container_width'             => array(
				'desktop' => '1200px',
				'tablet'  => '992px',
				'mobile'  => '420px',
			),
			'container_content_max_width' => array(
				'desktop' => '728px',
				'tablet'  => '500px',
				'mobile'  => '400px',
			),
			'containerVerticalMargin'     => array(
				'desktop' => '80px',
				'tablet'  => '40px',
				'mobile'  => '40px',
			),
			'sidebar_widget_spacing'      => array(
				'desktop' => '64px',
				'tablet'  => '50px',
				'mobile'  => '30px',
			),
			'widgets_font_size'           => array(
				'desktop' => '18px',
				'tablet'  => '16px',
				'mobile'  => '14px',
			),
			'layout'                      => 'boxed',
			'ed_scroll_to_top'            => 'no',
			'content_sidebar_width'       => '28%',
			'layout_style'                => 'no-sidebar',
		);

		return $defaults;
	}

}
