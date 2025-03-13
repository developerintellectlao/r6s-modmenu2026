<?php
/**
 *
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;
use Rishi\Customizer\Helpers\Basic as Basic;

class Pages extends Customize_Section {

	protected $id = 'pages-section';

	protected $priority = 2;

	protected $container = true;

	public function get_title() {
		return __( 'Page', 'rishi' );
	}

	public function get_id() {
		return $this->id;
	}

	public function get_type() {
		return self::OPTIONS;
	}

	public static function get_order() {
		return 26;
	}

	protected function get_defaults() {

	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$image_ratio = get_theme_mod( 'single_page_featured_image_ratio', 'auto' );
		$image_scale = get_theme_mod( 'single_page_featured_image_scale', 'contain' );

		$prefix                = 'single_page_';
		$pages_default         = \Rishi\Customizer\Helpers\Defaults::get_pages_defaults();
		$alignment             = get_theme_mod( $prefix . 'alignment', 'left' );
		$margin                = get_theme_mod( $prefix . 'margin', '50px' );
		$content_background    = get_theme_mod( $prefix . 'content_background', $pages_default['content_background'] );
		$boxed_content_spacing = get_theme_mod( $prefix . 'boxed_content_spacing', $pages_default['boxed_content_spacing'] );
		$border_radius         = get_theme_mod( $prefix . 'content_boxed_radius', $pages_default['content_boxed_radius'] );

		// Get postmeta value.
		$page_title_panel = Basic::get_meta( get_the_ID(), 'page_title_panel', 'inherit' );
		if ( 'custom' === $page_title_panel ) {
			$alignment = Basic::get_meta( get_the_ID(), 'single_page_alignment', 'left' );
			$margin    = Basic::get_meta( get_the_ID(), 'single_page_margin', '50px' );
		}
		if ( ( Basic::get_meta( get_the_ID(), 'content_style_source', 'inherit' ) === 'custom' ) ) {
			$postmeta_content_background            = Basic::get_meta( get_the_ID(), 'single_page_content_background', '#f4f4f4' );
			if ( ! isset( $postmeta_content_background['default'] ) ) {
				$content_background['default']['color'] = $postmeta_content_background;
			} else {
				$content_background = $postmeta_content_background;
			}
			$boxed_content_spacing                  = Basic::get_meta( get_the_ID(), 'single_page_boxed_content_spacing', $pages_default['boxed_content_spacing'] );
			$border_radius                          = Basic::get_meta( get_the_ID(), 'single_page_content_boxed_radius', $pages_default['content_boxed_radius'] );
			if ( is_string( $boxed_content_spacing ) ) {
				$boxed_content_spacing = json_decode( $boxed_content_spacing, true );
			}
			if ( is_string( $border_radius ) ) {
				$border_radius = json_decode( $border_radius, true );
			}
		}
		$options = array(
			'featured_image_ratio' => array(
				'selector'     => '.page .rishi-featured-image',
				'variableName' => 'img-ratio',
				'value'        => $image_ratio,
				'type'         => 'alignment',
			),
			'featured_image_scale' => array(
				'selector'     => '.page .rishi-featured-image',
				'variableName' => 'img-scale',
				'value'        => $image_scale,
				'type'         => 'alignment',
			),
			'single_page_alignment' => array(
				'selector'     => '.page .entry-header',
				'variableName' => 'alignment',
				'value'        => $alignment,
				'responsive'   => false,
				'type'         => 'alignment',
			),
			'single_page_margin' => array(
				'selector'     => '.page .entry-header',
				'variableName' => 'margin-bottom',
				'value'        => $margin,
				'responsive'   => false,
				'type'         => 'slider',
			),
			'singlePageHeaderContentBg' => array(
				'value'     => $content_background,
				'default'   => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'variables' => array(
					'default' => array(
						'selector' => '.page .main-content-wrapper',
						'variable' => 'background-color',
					),
				),
				'type'      => 'color',
			),
			'singlePageHeaderContentBoxShadow' => array(
				'value'     => get_theme_mod(
					$prefix . 'content_boxed_shadow',
					array(
						'enable'   => false,
						'h_offset' => '0px',
						'v_offset' => '12px',
						'blur'     => '18px',
						'spread'   => '-6px',
						'inset'    => false,
						'color'    => 'rgba(34, 56, 101, 0.04)',
					)
				),
				'default' => array(
					'enable'   => false,
					'h_offset' => '0px',
					'v_offset' => '12px',
					'blur'     => '18px',
					'spread'   => '-6px',
					'inset'    => false,
					'color'    => 'rgba(34, 56, 101, 0.04)',
				),
				'variables' => array(
					'default' => array(
						'variable' => 'box-shadow',
						'selector' => '.page .main-content-wrapper',
					),
				),
				'type'      => 'boxshadow',
			),
			'singlePageBoxedContentSpacing' => array(
				'selector'     => '.page .main-content-wrapper',
				'variableName' => 'padding',
				'unit'         => '',
				'value'        => $boxed_content_spacing,
				'responsive'   => true,
				'type'         => 'spacing',
			),
			'singlePageContentBoxRadius' => array(
				'selector'     => '.page .rishi-container-wrap',
				'variableName' => 'box-radius',
				'unit'         => '',
				'value'        => $border_radius,
				'responsive'   => true,
				'type'         => 'spacing',
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
			'pages_container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'pages_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'pages_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'pages_section_options',
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
