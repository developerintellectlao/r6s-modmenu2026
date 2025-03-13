<?php
/**
 * Archive Customizer Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
use \Rishi\Customizer\Helpers;

class Archive extends Customize_Section {

	protected $id = 'archive-section';

	protected $panel = 'main_blog_settings';

	protected $container = true;

	public function get_title() {
		return __( 'Archive Page', 'rishi' );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_type() {
		return self::OPTIONS;
	}

	public static function get_order() {
		return 23;
	}

	protected function get_defaults() {
		return array();
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$blog_structure = get_theme_mod( 'archive_post_meta', Helpers\Defaults::blogpost_structure_defaults() );
		$image_data     = Helpers\Basic::get_post_structure_data($blog_structure, 'featured_image');
		$divide_data    = Helpers\Basic::get_post_structure_data($blog_structure, 'divider');
		$title_data     = Helpers\Basic::get_post_structure_data($blog_structure, 'custom_title');
		$post_meta      = Helpers\Basic::get_post_structure_data($blog_structure, 'custom_meta');

		$image_ratio   = isset($image_data['featured_image_ratio']) ? $image_data['featured_image_ratio']: 'auto';
		$image_scale   = isset($image_data['featured_image_scale']) ? $image_data['featured_image_scale']: 'contain';
		$divide_margin = isset($divide_data['divider_margin']) ? $divide_data['divider_margin']: '0px 0px 20px 0px';
		$font_size 	   = isset($title_data['font_size']) ? $title_data['font_size']
		: [
			'desktop' => '40px',
			'tablet'  => '36px',
			'mobile'  => '24px',
		];
		$avatar_size = isset($post_meta['avatar_size']) ? $post_meta['avatar_size']: '34px';

		$options = array(
			'font_size'     => array(
				'selector'     => '.archive .site-content .entry-content-main-wrap',
				'variableName' => 'archiveHeadingFontSize',
				'value'        => $font_size,
				'responsive'   => true,
				'type'         => 'slider'
			),
			'featured_image_ratio' => array(
				'selector'     => '.archive .site-content .main-content-wrapper .rishi-featured-image',
				'variableName' => 'img-ratio',
				'value'        => $image_ratio,
				'type'         => 'alignment'
			),
			'featured_image_scale' => array(
				'selector'     => '.archive .site-content .main-content-wrapper .rishi-featured-image',
				'variableName' => 'img-scale',
				'value'        => $image_scale,
				'type'         => 'alignment'
			),
			'dividerMargin' => array(
				'selector'     => '.archive .site-content .entry-content-main-wrap',
				'variableName' => 'archiveDividerMarginFirst',
				'property' 	   => 'margin',
				'value'        => $divide_margin,
				'responsive'   => true,
				'type'         => 'spacing'
			),
			'archivePageMargin' => array(
				'selector'     => '.archive .site-content .archive-title-wrapper',
				'variableName' => 'margin',
				'property' 	   => 'margin',
				'unit'         => '',
				'value'        => get_theme_mod(
					'archive_page_margin',
					array(
						'desktop' => '30px',
						'tablet'  => '20px',
						'mobile'  => '20px',
					)
				),
				'responsive'   => true,
				'type'         => 'slider'
			),
			'archivePageAlignment' => array(
				'selector'     => '.archive .site-content .archive-title-wrapper',
				'variableName' => 'alignment',
				'unit'         => '',
				'value'        => get_theme_mod(
					'archive_page_alignment',
					'left'
				),
				'type'		   => 'alignment',
				'responsive'   => false,
			),
			'archivePageArchiveMargin' => array(
				'type' 	   => 'slider',
				'selector'     => '.archive .site-content .archive-title-wrapper',
				'variableName' => 'archiveMargin',
				'unit'         => '',
				'value'        => get_theme_mod(
					'archive_page_archive_margin',
					'30px'
				),
				'responsive'   => false,
			),
			'archive_font_title_color'             => array(
				'value'     => get_theme_mod( 'archive_font_title_color' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor2)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'archiveFontColor',
					),
				),
				'type'      => 'color',
			),
			'archivePageHeaderContentBg'      => array(
				'value'     => get_theme_mod(
					'archive_page_header_content_background',
					array(
						'default' => array(
							'color' => 'var(--paletteColor7)',
						),
					)
				),
				'type'      => 'color',
				'default'   => array(
					'default' => array(
						'color' => 'var(--paletteColor7)',
					),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.archive .site-content .archive-title-wrapper',
					)
				)
			),
			'avatar_size'     => array(
				'selector'     => '.archive .site-content .entry-content-main-wrap',
				'variableName' => 'archiveAvatarSize',
				'value'        => $avatar_size,
				'responsive'   => false,
				'type'         => 'slider'
			),
		);

		foreach ( $options as $key => $option ) {
			$dynamic_styles->add( $key, $option );
		}

	}

	protected function get_customize_settings() {
		return $this->settings->get_settings();
	}

	protected function add_controls() {
		$this->wp_customize->add_section(
			'archive_container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'archive_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'archive_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'archive_section_options',
				'section'            => $this->get_id(),
				'innerControls'      => $this->get_customize_settings(),
			)
		);

		$control->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $this->get_setting(),
			'customize_section' => 'container',
			'innerControls'     => $this->get_customize_settings(),
			'sanitize_callback' => function ($input, $setting) {
				return $input;
			},
		);

		$this->wp_customize->add_control( $control );
	}
	
}
