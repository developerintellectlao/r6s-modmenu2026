<?php
/**
 *
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
use \Rishi\Customizer\Helpers;

class Author extends Customize_Section {

	protected $id = 'author-section';

	protected $panel = 'main_blog_settings';

	protected $container = true;

	public function get_title() {
		return __( 'Author Page', 'rishi' );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_type() {
		return self::OPTIONS;
	}

	protected function get_defaults() {
		return array();
	}

	public static function get_order() {
		return 24;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$blog_structure = get_theme_mod( 'author_post_structure', Helpers\Defaults::blogpost_structure_defaults() );
		$defaults       = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
		$image_data     = Helpers\Basic::get_post_structure_data($blog_structure, 'featured_image');
		$divide_data    = Helpers\Basic::get_post_structure_data($blog_structure, 'divider');
		$title_data     = Helpers\Basic::get_post_structure_data($blog_structure, 'custom_title');
		$post_meta     = Helpers\Basic::get_post_structure_data($blog_structure, 'custom_meta');

		$image_ratio   = isset($image_data['featured_image_ratio']) ? $image_data['featured_image_ratio']: 'auto';
		$image_scale   = isset($image_data['featured_image_scale']) ? $image_data['featured_image_scale']: 'contain';
		$divide_margin = isset($divide_data['divider_margin']) ? $divide_data['divider_margin']: '0px 0px 20px 0px';
		$font_size 	   = isset($title_data['font_size']) ? $title_data['font_size']
		: [
			'desktop' => '30px',
			'tablet'  => '24px',
			'mobile'  => '22px',
		];
		$avatar_size = isset($post_meta['avatar_size']) ? $post_meta['avatar_size']: '34px';

		$options = array(
			'font_size'     => array(
				'selector'     => '.archive.author .site-content .entry-content-main-wrap',
				'variableName' => 'authorHeadingFontSize',
				'value'        => $font_size,
				'unit'         => '',
				'responsive'   => true,
				'type'         => 'slider'
			),
			'featured_image_ratio' => array(
				'selector'     => '.archive.author .site-content .main-content-wrapper .rishi-featured-image',
				'variableName' => 'img-ratio',
				'value'        => $image_ratio,
				'type'         => 'alignment'
			),
			'featured_image_scale' => array(
				'selector'     => '.archive.author .site-content .main-content-wrapper .rishi-featured-image',
				'variableName' => 'img-scale',
				'value'        => $image_scale,
				'type'         => 'alignment'
			),
			'diivderMargin' => array(
				'selector'     => '.archive.author .site-content .entry-content-main-wrap',
				'variableName' => 'authorDividerMarginFirst',
				'property' 	   => 'margin',
				'value'        => $divide_margin,
				'responsive'   => true,
			),
			'authorpageAvatarSize' => array(
				'selector'     => '.archive.author .site-content .archive-title-wrapper',
				'variableName' => 'width',
				'unit'         => '',
				'value'        => get_theme_mod(
					'author_page_avatar_size',$defaults['author_page_avatar_size']
				),
				'responsive' => true,
				'type' => 'slider',
			),
			'authorPageMargin' => array(
				'selector'     => '.archive.author .site-content .archive-title-wrapper',
				'variableName' => 'margin',
				'unit'         => '',
				'value'        => get_theme_mod(
					'author_page_margin',
					$defaults['author_page_margin']
				),
				'responsive' => true,
				'type' => 'slider'
			),
			'author_page_color'   => array(
				'value'     => get_theme_mod( 'author_page_color' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor2)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'authorFontColor',
					),
				),
				'type' => 'color'
			),
			'authorPageAlignment' => array(
				'selector'     => '.archive.author .site-content .archive-title-wrapper',
				'variableName' => 'alignment',
				'value'        => get_theme_mod(
					'author_page_alignment',
					'left'
				),
				'responsive' => false,
				'type'       => 'alignment'
			),
			'authorPageAuthorMargin' => array(
				'selector'     => '.archive.author .site-content .archive-title-wrapper',
				'variableName' => 'authorMargin',
				'unit'         => '',
				'value'        => get_theme_mod(
					'author_page_author_margin',
					$defaults['author_page_author_margin']
				),
				'responsive' => false,
				'type'       => 'slider',
			),
			
			'author_page_header_content_background'      => array(
				'value'     => get_theme_mod( 'author_page_header_content_background' ),
				'variableName' => 'background-color',
				'default'   => array(
					'color' => 'var(--paletteColor7)',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.archive.author .site-content .archive-title-wrapper',
						'variable' => 'background-color',
					),
				),
				'type'      => 'color',
			),
			'avatar_size'     => array(
				'selector'     => '.archive.author .site-content .archive-title-wrapper',
				'variableName' => 'authorAvatarSize',
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
			'author_container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'author_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'author_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'author_section_options',
				'section'            => $this->get_id(),
				'innerControls'      => $this->get_customize_settings(),
			)
		);

		$control->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $this->get_setting(),
			'customize_section' => 'container',
			'innerControls'     => $this->get_customize_settings(),
			'sanitize_callback' => function ( $input, $setting ) {
				return $input;
			},
		);

		$this->wp_customize->add_control( $control );
	}

}
