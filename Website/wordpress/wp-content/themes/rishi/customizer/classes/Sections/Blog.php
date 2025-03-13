<?php
/**
 *
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
use Rishi\Customizer\Helpers as Helpers;
class Blog extends Customize_Section {

	protected $priority = 1;

	protected $id = 'blog-section';

	protected $panel = 'main_blog_settings';

	protected $container = true;

	public function get_title() {
		return __( 'Blog Archive', 'rishi' );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_type() {
		return self::OPTIONS;
	}

	public static function get_order() {
		return 21;
	}

	protected function get_defaults() {
		return array();
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$blog_structure = get_theme_mod( 'archive_blog_post_meta', Helpers\Defaults::blogpost_structure_defaults() );
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
				'selector'     => '.blog .site-content .entry-content-main-wrap',
				'variableName' => 'blogHeadingFontSize',
				'value'        => $font_size,
				'unit'         => '',
				'responsive'   => true,
				'type'         => 'slider'
			),
			'featured_image_ratio' => array(
				'selector'     => '.blog .site-content .main-content-wrapper .rishi-featured-image',
				'variableName' => 'img-ratio',
				'value'        => $image_ratio,
				'type'         => 'alignment'
			),
			'featured_image_scale' => array(
				'selector'     => '.blog .site-content .main-content-wrapper .rishi-featured-image',
				'variableName' => 'img-scale',
				'value'        => $image_scale,
				'type'         => 'alignment'
			),
			'diivderMargin' => array(
				'selector'     => '.blog .site-content .entry-content-main-wrap',
				'variableName' => 'blogDividerMarginFirst',
				'property' 	   => 'margin',
				'type' 	   	   => 'spacing',
				'value'        => $divide_margin,
				'responsive'   => true,
			),
			'blogPageMargin' => array(
				'selector'     => '.blog .site-content .archive-title-wrapper',
				'variableName' => 'margin',
				'unit'         => '',
				'value'        => get_theme_mod(
					'blog_page_margin',
					array(
						'desktop' => '20px',
						'tablet'  => '20px',
						'mobile'  => '20px',
					)
				),
				'responsive' => true,
				'type'       => 'slider',
			),
			'blogFontTitleColor' => array(
				'value'     => get_theme_mod( 'blog_font_title_color' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor2)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'blogFontColor',
					),
				),
				'type'      => 'color',
			),
			'blogPageAlignment' => array(
				'selector'     => '.blog .site-content .archive-title-wrapper',
				'variableName' => 'alignment',
				'unit'         => '',
				'value'        => get_theme_mod(
					'blog_page_alignment',
					'left'
				),
				'responsive'   => true,
				'type'         => 'alignment'
			),
			'blog_page_header_content_background'      => array(
				'value'     => get_theme_mod( 'blog_page_header_content_background' ),
				'default'   => array(
					'color' => 'var(--paletteColor7)',
				),
				'variables' => array(
					'default' => array(
						'selector' => '.blog .site-content .archive-title-wrapper',
						'variable' => 'background-color',
					),
				),
				'type'      => 'color',
			),
			'blogBreadcrumbsPadding'   => array(
				'selector'     => '.blog .site-content .archive-title-wrapper',
				'variableName' => 'padding',
				'value'        => get_theme_mod(
					'breadcrumbsPadding',
					\Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => false,
							'top'    => '16',
							'left'   => '16',
							'right'  => '16',
							'bottom' => '16',
							'unit'   => 'px',
						)
					)
				),
				'unit'       => 'px',
				'type'       => 'spacing',
				'property'   => 'padding',
				'responsive' => true,
			),
			'avatar_size'     => array(
				'selector'     => '.blog .site-content .entry-content-main-wrap',
				'variableName' => 'blogAvatarSize',
				'value'        => $avatar_size,
				'responsive'   => false,
				'type'         => 'slider'
			),
		);

		foreach( $options as $key => $option ) {
			$dynamic_styles->add( $key, $option );
		}
	}

	public function get_setting() {
		return array( 'transport' => 'postMessage' );
	}

	public function get_customize_settings() {
		return $this->settings->get_settings();
	}

	protected function add_controls() {
		$this->wp_customize->add_section(
			'blog_container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'blog_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'blog_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'blog_section_options',
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
