<?php
/**
 * Customizer Color Section
 */
namespace Rishi\Customizer\Sections;

use Rishi\Customizer\Abstracts\Customize_Section;

class Colors extends Customize_Section {

	protected $priority = 1;

	protected $id = 'colors_panel';

	protected $panel = 'main_global_settings';


	protected $container = true;

	public function get_title() {
		return __( 'Colors', 'rishi' );
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
		return 7;
	}

	public function get_dynamic_styles( $dynamic_styles ) {
		$colordefaults = \Rishi\Customizer\Helpers\Defaults::color_value();
		$color_options = array(
			'color_palette'                        => array(
				'value'      => get_theme_mod( 'colorPalette' ),
				'default'    => array(
					'color1' => array( 'color' => 'rgba(41, 41, 41, 0.9)' ),
					'color2' => array( 'color' => '#292929' ),
					'color3' => array( 'color' => '#216BDB' ),
					'color4' => array( 'color' => '#5081F5' ),
					'color5' => array( 'color' => '#ffffff' ),
					'color6' => array( 'color' => '#EDF2FE' ),
					'color7' => array( 'color' => '#e9f1fa' ),
					'color8' => array( 'color' => '#F9FBFE' ),
				),
				'variables'  => array(
					'color1' => array( 'variable' => 'paletteColor1' ),
					'color2' => array( 'variable' => 'paletteColor2' ),
					'color3' => array( 'variable' => 'paletteColor3' ),
					'color4' => array( 'variable' => 'paletteColor4' ),
					'color5' => array( 'variable' => 'paletteColor5' ),
					'color6' => array( 'variable' => 'paletteColor6' ),
					'color7' => array( 'variable' => 'paletteColor7' ),
					'color8' => array( 'variable' => 'paletteColor8' ),
				),
				'type'       => 'color',
				'selector'   => ':root',
				'responsive' => false,
				'property'   => 'colorPalette',
			),
			'primary_color'                        => array(
				'value'     => get_theme_mod( 'primary_color' ),
				'default'   => array(
					'color' => $colordefaults['primary_color'],
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'primaryColor',
					),
				),
				'type'      => 'color',
			),
			'genheadingColor'                      => array(
				'value'     => get_theme_mod( 'genheadingColor' ),
				'default'   => array( 'color' => $colordefaults['genheadingColor'] ),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'genheadingColor',
					),
				),
				'type'      => 'color',
			),
			'genLinkColor'                         => array(
				'value'     => get_theme_mod( 'genLinkColor' ),
				'default'   => array(
					'default' => array( 'color' => $colordefaults['genLinkColor'] ),
					'hover'   => array( 'color' => $colordefaults['genLinkHoverColor'] ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'genLinkColor',
						'selector' => ':root',
					),
					'hover'   => array(
						'variable' => 'genLinkHoverColor',
						'selector' => ':root',
					),
				),
				'type'      => 'color',
			),
			'textSelectionColor'                   => array(
				'value'     => get_theme_mod( 'textSelectionColor' ),
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor5)' ),
					'hover'   => array( 'color' => $colordefaults['textSelectionColor'] ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'textSelectionColor',
						'selector' => ':root',
					),
					'hover'   => array(
						'variable' => 'textSelectionHoverColor',
						'selector' => ':root',
					),
				),
				'type'      => 'color',
			),
			'genborderColor'                       => array(
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['genborderColor'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'genborderColor',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'genborderColor' ),
			),
			'accentColors'                       => array(
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['accentColorOne'] ),
					'default_2'  => array( 'color' => $colordefaults['accentColorTwo'] ),
					'default_3'  => array( 'color' => $colordefaults['accentColorThree'] ),
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'accentColorOne',
					),
					'default_2' => array(
						'selector' => ':root',
						'variable' => 'accentColorTwo',
					),
					'default_3' => array(
						'selector' => ':root',
						'variable' => 'accentColorThree',
					),
				),
				'type'      => 'color',
				'value'     => get_theme_mod( 'accentColors' ),
			),
			'site_background_color'  => array(
				'value'      => get_theme_mod( 'site_background_color' ),
				'default'    => array(
					'default' => array(
						'color' => $colordefaults['site_background_color'],
					),
				),
				'variables'  => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => ':root',
					),
				),
				'type'       => 'color',
				'responsive' => false
			),
			'base_color'                           => array(
				'value'      => get_theme_mod( 'base_color' ),
				'default'    => array(
					'default' => array(
						'color' => $colordefaults['base_color'],
					),
				),
				'variables'  => array(
					'default' => array(
						'variable' => 'baseColor',
						'selector' => ':root',
					),
				),
				'type'       => 'color',
				'responsive' => false
			),
			'btn_text_color'                       => array(
				'value'     => get_theme_mod( 'btn_text_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['btn_text_color'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'btnTextColor',
					),
				),
				'type'      => 'color',
			),
			'btn_text_hover_color'                 => array(
				'value'     => get_theme_mod( 'btn_text_hover_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['btn_text_hover_color'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'btnTextHoverColor',
					),
				),
				'type'      => 'color',
			),
			'btn_bg_color'                         => array(
				'value'     => get_theme_mod( 'btn_bg_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['btn_bg_color'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'btnBgColor',
					),
				),
				'type'      => 'color',
			),
			'btn_bg_hover_color'                   => array(
				'value'     => get_theme_mod( 'btn_bg_hover_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['btn_bg_hover_color'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'btnBgHoverColor',
					),
				),
				'type'      => 'color',
			),
			'btn_border_color'                     => array(
				'value'     => get_theme_mod( 'btn_border_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['btn_border_color'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'btnBorderColor',
					),
				),
				'type'      => 'color',
			),
			'btn_border_hover_color'               => array(
				'value'     => get_theme_mod( 'btn_border_hover_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['btn_border_hover_color'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'btnBorderHoverColor',
					),
				),
				'type'      => 'color',
			),
			'fontColor'                            => array(
				'value'     => get_theme_mod( 'fontColor' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor2)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'color',
					),
				),
				'type'      => 'color',
			),
			'linkColor'                            => array(
				'value'     => get_theme_mod( 'fontColor' ),
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor1)' ),
					'hover'   => array( 'color' => 'var(--paletteColor2)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => ':root',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => ':root',
					),
				),
			),
			'selectionColor'                       => array(
				'value'     => get_theme_mod( 'selectionColor' ),
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor5)' ),
					'hover'   => array( 'color' => 'var(--paletteColor1)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'selectionTextColor',
						'selector' => ':root',
					),
					'hover'   => array(
						'variable' => 'selectionBackgroundColor',
						'selector' => ':root',
					),
				),
			),
			'border_color'                         => array(
				'value'     => get_theme_mod( 'border_color' ),
				'default'   => array(
					'default'  => array( 'color' => 'rgba(224, 229, 235, 0.9)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'border-color',
					),
				),
				'type'      => 'color',
			),
			'headingColor'                         => array(
				'value'     => get_theme_mod( 'headingColor' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor4)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'headingColor',
					),
				),
				'type'      => 'color',
			),
			'buttonTextColor'                      => array(
				'value'     => get_theme_mod( 'buttonTextColor' ),
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor5)' ),
					'hover'   => array( 'color' => 'var(--paletteColor5)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'buttonTextInitialColor',
						'selector' => ':root',
					),
					'hover'   => array(
						'variable' => 'buttonTextHoverColor',
						'selector' => ':root',
					),
				),
			),
			'buttonColor'                          => array(
				'value'     => get_theme_mod( 'buttonTextColor' ),
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor3)' ),
					'hover'   => array( 'color' => 'var(--paletteColor2)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'buttonInitialColor',
						'selector' => ':root',
					),
					'hover'   => array(
						'variable' => 'buttonHoverColor',
						'selector' => ':root',
					),
				),
			),
			'breadcrumbs_color'                    => array(
				'value'     => get_theme_mod( 'breadcrumbs_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['breadcrumbsColor'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'breadcrumbsColor',
					),
				),
				'type'      => 'color',
			),
			'breadcrumbs_current_color'            => array(
				'value'     => get_theme_mod( 'breadcrumbs_current_color' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['breadcrumbsCurrentColor'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'breadcrumbsCurrentColor',
					),
				),
				'type'      => 'color',
			),
			'breadcrumbsSeparatorColor'            => array(
				'value'     => get_theme_mod( 'breadcrumbsSeparatorColor' ),
				'default'   => array(
					'default'  => array( 'color' => $colordefaults['breadcrumbsSeparatorColor'] ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'breadcrumbsSeparatorColor',
					),
				),
				'type'      => 'color',
			),
			'single_blog_post_title_color'         => array(
				'value'     => get_theme_mod( 'single_blog_post_title_color' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor1)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'titleColor',
					),
				),
				'type'      => 'color',
			),
			'featured_image_caption_color'         => array(
				'value'     => get_theme_mod( 'featured_image_caption_color' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor8)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'captionColor',
					),
				),
				'type'      => 'color',
			),
			'featured_image_caption_overlay_color' => array(
				'value'     => get_theme_mod( 'featured_image_caption_overlay_color' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor1)' ),
					'selector' => ':root',
				),
				'variables' => array(
					'default' => array(
						'selector' => ':root',
						'variable' => 'captionOverlayColor',
					),
				),
				'type'      => 'color',
			),

			// Sidebar
			'sidebarWidgetsTitleColor'             => array(
				'value'     => get_theme_mod( 'sidebarWidgetsTitleColor' ),
				'default'   => array(
					'default'  => array( 'color' => 'var(--paletteColor2)' ),
					'selector' => '#secondary',
				),
				'variables' => array(
					'default' => array(
						'selector' => '#secondary',
						'variable' => 'sidebarWidgetsTitleColor',
					),
				),
				'type'      => 'color',
			),
			'widgets_link_color'                   => array(
				'value'     => get_theme_mod( 'widgets_link_color' ),
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor1)' ),
					'hover'   => array( 'color' => 'var(--paletteColor3)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'widgetsLinkColor',
						'selector' => '#secondary.widget-area',
					),
					'hover'   => array(
						'variable' => 'widgetsLinkHoverColor',
						'selector' => '#secondary.widget-area',
					),
				),
				'type'      => 'color',
			),
		);
		foreach ( $color_options as $key => $option ) {
			$dynamic_styles->add( $key, $option );
		}

	}

	public function get_customize_settings() {

		return $this->settings->get_settings();

	}

	public function get_control_setting_id() {
		return 'layouts_color_options';
	}

	protected function add_controls() {
		$this->wp_customize->add_section(
			'color_container_panel',
			array(
				'transport'         => self::POSTMESSAGE,
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'color_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'color_section_options',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'color_section_options',
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

	protected function get_default_palette_value() {
		$_palettes = apply_filters(
			'rishi_color_palettes',
			array(
				array( 'rgba(41, 41, 41, 0.9)', '#292929', '#216BDB', '#5081F5', '#ffffff', '#EDF2FE', '#e9f1fa', '#F9FBFE' ),
				array( 'rgba(0, 26, 26, 0.8)', 'rgba(0, 26, 26, 0.9)', '#03a6a6', '#001a1a', '#ffffff', '#E5E8E8', '#F4FCFC', '#FEFEFE' ),
				array( '#1e2436', '#242b40', '#ff8b3c', '#8E919A', '#ffffff', '#E9E9EC', '#FFF7F1', '#FFFBF9' ),
				array( '#8D8D8D', '#31332e', '#8cb369', '#A3C287', '#ffffff', '#E8F0E1', '#F3F7F0', '#ffffff' ),
				array( '#21201d', '#21201d', '#dea200', '#343330', '#ffffff', '#F8ECCC', '#FDF8ED', '#fdfcf7' ),
			)
		);

		$current_palette     = 'palette-1';
		$color_palette_value = array( 'current_palette' => $current_palette );
		foreach ( $_palettes[0] as $index => $color_code ) {
			$color_palette_value[ 'color' . ++$index ] = array( 'color' => $color_code );
		}

		unset( $color_code, $index );

		$palettes = array();

		foreach ( $_palettes as $index => $palette ) {
			$_palette['id'] = 'palette-' . ++$index;
			foreach ( $palette as $_index => $color_code ) {
				$_palette[ 'color' . ++$_index ] = array( 'color' => $color_code );
			}
			$palettes[] = $_palette;
		}

		$color_palette_value['palettes'] = $palettes;
		return $color_palette_value;
	}
}
