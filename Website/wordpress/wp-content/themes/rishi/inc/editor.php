<?php
/**
 * Rishi Dynamic Backend Editor Styles
 *
 * @package Rishi
 */

use Rishi\Customizer\Dynamic_Styles;
use Rishi\Customizer\Helpers\Basic as Helpers;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

add_action( 'rishi_customizer_dynamic_styles_collect_css', 'rishi_get_dynamic_styles',999999 );
function rishi_get_dynamic_styles( Dynamic_Styles $dynamic_styles_object ) {
	$defaults       = new Defaults();
	$colordefaults  = $defaults->color_value();
	$layoutdefaults = $defaults->get_layout_defaults();
	$buttondefaults = $defaults->button_defaults();

	$adminContainerWidth = get_theme_mod(
		'container_width',
		$layoutdefaults['container_width']
	);

	$adminContainerContentMaxWidth = get_theme_mod(
		'container_content_max_width',
		$layoutdefaults['container_content_max_width']
	);

	$options        = array(
		'color_palette'                  => array(
			'value'     => get_theme_mod( 'colorPalette' ),
			'default'   => array(
				'color1' => array( 'color' => 'rgba(41, 41, 41, 0.9)' ),
				'color2' => array( 'color' => '#292929' ),
				'color3' => array( 'color' => '#216BDB' ),
				'color4' => array( 'color' => '#5081F5' ),
				'color5' => array( 'color' => '#ffffff' ),
				'color6' => array( 'color' => '#EDF2FE' ),
				'color7' => array( 'color' => '#e9f1fa' ),
				'color8' => array( 'color' => '#F9FBFE' ),
			),
			'variables' => array(
				'color1' => array( 'variable' => 'paletteColor1' ),
				'color2' => array( 'variable' => 'paletteColor2' ),
				'color3' => array( 'variable' => 'paletteColor3' ),
				'color4' => array( 'variable' => 'paletteColor4' ),
				'color5' => array( 'variable' => 'paletteColor5' ),
				'color6' => array( 'variable' => 'paletteColor6' ),
				'color7' => array( 'variable' => 'paletteColor7' ),
				'color8' => array( 'variable' => 'paletteColor8' ),
			),
			'type'      => 'color',
			'selector'  => ':root',
			'property'  => 'colorPalette',
			'editor'    => true,
		),
		'admin_base_color'               => array(
			'value'     => get_theme_mod( 'base_color' ),
			'default'   => array(
				'default' => array( 'color' => $colordefaults['base_color'] ),
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminbaseColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_site_background_color'    => array(
			'value'     => get_theme_mod( 'site_background_color' ),
			'default'   => array(
				'default' => array( 'color' => $colordefaults['site_background_color'] ),
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminSiteBackgroundColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_genheadingColor'          => array(
			'value'     => get_theme_mod( 'genheadingColor' ),
			'default'   => array( 'color' => $colordefaults['genheadingColor'] ),
			'variables' => array(
				'default' => array(
					'selector' => ':root',
					'variable' => 'admingenheadingColor',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_primaryColor'             => array(
			'value'     => get_theme_mod( 'primary_color' ),
			'default'   => array(
				'default' => array( 'color' => $colordefaults['primary_color'] ),
			),
			'variables' => array(
				'default' => array(
					'selector' => ':root',
					'variable' => 'adminprimaryColor',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_genLinkColor'             => array(
			'value'     => get_theme_mod( 'genLinkColor' ),
			'default'   => array(
				'default' => array( 'color' => $colordefaults['genLinkColor'] ),
				'hover'   => array( 'color' => $colordefaults['genLinkHoverColor'] ),
			),
			'variables' => array(
				'default' => array(
					'variable' => 'admingenLinkColor',
					'selector' => ':root',
				),
				'hover'   => array(
					'variable' => 'admingenLinkHoverColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_textSelectionColor'       => array(
			'value'     => get_theme_mod( 'textSelectionColor' ),
			'default'   => array(
				'default' => array( 'color' => '#ffffff' ),
				'hover'   => array( 'color' => $colordefaults['textSelectionColor'] ),
			),
			'variables' => array(
				'default' => array(
					'variable' => 'admintextSelectionColor',
					'selector' => ':root',
				),
				'hover'   => array(
					'variable' => 'admintextSelectionHoverColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_genborderColor'           => array(
			'default'   => array(
				'default'  => array( 'color' => $colordefaults['genborderColor'] ),
				'selector' => ':root',
			),
			'variables' => array(
				'default' => array(
					'selector' => ':root',
					'variable' => 'admingenborderColor',
				),
			),
			'type'      => 'color',
			'value'     => get_theme_mod( 'genborderColor' ),
			'editor'    => true,
		),
		'admin_containerWidth'           => array(
			'selector'     => ':root',
			'variableName' => 'adminContainerWidth',
			'unit'         => '',
			'value'        => $adminContainerWidth['desktop'],
			'type'         => 'slider',
			'editor'       => true,
			'responsive'   => false,
		),
		'admin_containerContentMaxWidth' => array(
			'selector'     => ':root',
			'variableName' => 'adminContainerContentMaxWidth',
			'unit'         => '',
			'value'        => $adminContainerContentMaxWidth['desktop'],
			'type'         => 'slider',
			'editor'       => true,
			'responsive'   => false,
		),
		'admin_buttonRoundness'          => array(
			'selector'     => ':root',
			'variableName' => 'adminBottonRoundness',
			'unit'         => '',
			'value'        => get_theme_mod(
				'button_roundness',
				$buttondefaults['admin_buttonRoundness']
			),
			'type'         => 'slider',
			'editor'       => true,
		),
		'admin_buttonPadding'            => array(
			'selector'     => ':root',
			'variableName' => 'adminButtonPadding',
			'unit'         => '',
			'value'        => get_theme_mod(
				'button_padding',
				$buttondefaults['admin_buttonPadding']
			),
			'type'         => 'spacing',
			'editor'       => true,
		),
		'admin_btnTextColor'             => array(
			'value'     => get_theme_mod( 'btn_text_color' ),
			'default'   => array(
				'default' => array( 'color' => $colordefaults['btn_text_color'] ),
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminBtnTextColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_btnTextHoverColor'        => array(
			'value'     => get_theme_mod( 'btn_text_hover_color' ),
			'default'   => array(
				'default'  => array( 'color' => $colordefaults['btn_text_hover_color'] ),
				'selector' => ':root',
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminBtnTextHoverColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_btnBgColor'               => array(
			'value'     => get_theme_mod( 'btn_bg_color' ),
			'default'   => array(
				'default' => array( 'color' => $colordefaults['btn_bg_color'] ),
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminBtnBgColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_btnBgHoverColor'          => array(
			'value'     => get_theme_mod( 'btn_bg_hover_color' ),
			'default'   => array(
				'default'  => array( 'color' => $colordefaults['btn_bg_hover_color'] ),
				'selector' => ':root',
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminBtnBgHoverColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_btnBorderColor'           => array(
			'value'     => get_theme_mod( 'btn_border_color' ),
			'default'   => array(
				'default'  => array( 'color' => $colordefaults['btn_border_color'] ),
				'selector' => ':root',
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminBtnBorderColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'admin_btnBorderHoverColor'      => array(
			'value'     => get_theme_mod( 'btn_border_hover_color' ),
			'default'   => array(
				'default'  => array( 'color' => $colordefaults['btn_border_hover_color'] ),
				'selector' => ':root',
			),
			'variables' => array(
				'default' => array(
					'variable' => 'adminBtnBorderHoverColor',
					'selector' => ':root',
				),
			),
			'type'      => 'color',
			'editor'    => true,
		),
		'btnTypography'                  => array(
			'value'    => get_theme_mod(
				'button_Typo',
				$defaults->typography_value(
					array(
						'weight'      => '400',
						'size'            => array(
							'desktop' => '18px',
							'tablet'  => '18px',
							'mobile'  => '18px',
						),
						'line-height'            => array(
							'desktop' => '1.75',
							'tablet'  => '1.75',
							'mobile'  => '1.75',
						),
					)
				)
			),
			'selector' => ':root',
			'prefix'   => 'btn',
			'type'     => 'typography',
			'editor'   => true,
		),
		'rootTypography'                 => array(
			'value'    => get_theme_mod(
				'rootTypography',
				$defaults->typography_value(
					array(
						'family'          => 'System Default',
						'weight'          => '400',
						'size'            => array(
							'desktop' => '18px',
							'tablet'  => '18px',
							'mobile'  => '18px',
						),
						'line-height'            => array(
							'desktop' => '1.75',
							'tablet'  => '1.75',
							'mobile'  => '1.75',
						),
						'letter-spacing'            => array(
							'desktop' => '0em',
							'tablet'  => '0em',
							'mobile'  => '0em',
						),
						'style'           => 'Default',
						'text-transform'  => 'none',
						'text-decoration' => 'none',
					)
				)
			),
			'selector' => ':root',
			'type'     => 'typography',
			'editor'   => true,
		),
		'h1Typography'                   => array(
			'value'    => get_theme_mod(
				'h1Typography',
				$defaults->typography_value(
					array(
						'family'      => 'System Default',
						'weight'      => '700',
						'transform'   => 'none',
						'size'            => array(
							'desktop' => '40px',
							'tablet'  => '40px',
							'mobile'  => '40px',
						),
						'line-height'            => array(
							'desktop' => '1.5',
							'tablet'  => '1.5',
							'mobile'  => '1.5',
						),
					)
				)
			),
			'selector' => 'h1.block-editor-rich-text__editable, .wp-block-post-title ',
			'type'     => 'typography',
			'editor'   => true,
		),
		'h2Typography'                   => array(
			'value'    => get_theme_mod(
				'h2Typography',
				$defaults->typography_value(
					array(
						'family'      => 'System Default',
						'variants'    => '',
						'category'    => '',
						'weight'      => '700',
						'transform'   => 'none',
						'size'            => array(
							'desktop' => '36px',
							'tablet'  => '36px',
							'mobile'  => '36px',
						),
						'line-height'            => array(
							'desktop' => '1.5',
							'tablet'  => '1.5',
							'mobile'  => '1.5',
						),
					)
				)
			),
			'selector' => 'h2.block-editor-rich-text__editable',
			'type'     => 'typography',
			'editor'   => true,
		),
		'h3Typography'                   => array(
			'value'    => get_theme_mod(
				'h3Typography',
				$defaults->typography_value(
					array(
						'family'      => 'System Default',
						'variants'    => '',
						'category'    => '',
						'weight'      => '700',
						'transform'   => 'none',
						'size'            => array(
							'desktop' => '30px',
							'tablet'  => '30px',
							'mobile'  => '30px',
						),
						'line-height'            => array(
							'desktop' => '1.5',
							'tablet'  => '1.5',
							'mobile'  => '1.5',
						),
					)
				)
			),
			'selector' => 'h3.block-editor-rich-text__editable',
			'type'     => 'typography',
			'editor'   => true,
		),
		'h4Typography'                   => array(
			'value'    => get_theme_mod(
				'h4Typography',
				$defaults->typography_value(
					array(
						'family'      => 'System Default',
						'variants'    => '',
						'category'    => '',
						'weight'      => '700',
						'transform'   => 'none',
						'size'            => array(
							'desktop' => '26px',
							'tablet'  => '26px',
							'mobile'  => '26px',
						),
						'line-height'            => array(
							'desktop' => '1.5',
							'tablet'  => '1.5',
							'mobile'  => '1.5',
						),
					)
				)
			),
			'selector' => 'h4.block-editor-rich-text__editable',
			'type'     => 'typography',
			'editor'   => true,
		),
		'h5Typography'                   => array(
			'value'    => get_theme_mod(
				'h5Typography',
				$defaults->typography_value(
					array(
						'family'      => 'System Default',
						'variants'    => '',
						'category'    => '',
						'weight'      => '700',
						'transform'   => 'none',
						'size'            => array(
							'desktop' => '22px',
							'tablet'  => '22px',
							'mobile'  => '22px',
						),
						'line-height'            => array(
							'desktop' => '1.5',
							'tablet'  => '1.5',
							'mobile'  => '1.5',
						),
					)
				)
			),
			'selector' => 'h5.block-editor-rich-text__editable',
			'type'     => 'typography',
			'editor'   => true,
		),
		'h6Typography'                   => array(
			'value'    => get_theme_mod(
				'h6Typography',
				$defaults->typography_value(
					array(
						'family'      => 'System Default',
						'variants'    => '',
						'category'    => '',
						'weight'      => '700',
						'transform'   => 'none',
						'size'            => array(
							'desktop' => '18px',
							'tablet'  => '18px',
							'mobile'  => '18px',
						),
						'line-height'            => array(
							'desktop' => '1.5',
							'tablet'  => '1.5',
							'mobile'  => '1.5',
						),
					)
				)
			),
			'selector' => 'h6.block-editor-rich-text__editable',
			'type'     => 'typography',
			'editor'   => true,
		),
	);
	foreach ( $options as $key => $option ) {
		$dynamic_styles_object->add( $key, $option );
	}
}

/**
 * Adds the Backend Admin classes for the customizer values
 *
 * @param [type] $classes
 * @return void
 */
function rishi_admin_body_classes( $classes ){
	global $post;

	if ( ! isset( $post->ID ) ) {
		return $classes;
	}

	if ( ! get_current_screen()->is_block_editor() ) {
		return $classes;
	}

	$defaults = Defaults::get_layout_defaults();

	if ( get_current_screen()->base === "post") {

		$post_layout    = get_theme_mod('post_sidebar_layout', $defaults['post_sidebar_layout']);

		$sidebar_layout = Helpers::get_meta( $post->ID, 'page_structure_type', 'default-sidebar' );

		if ( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ) ) {
			$classes .= ' full-width'; //Fullwidth
		}elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'centered' ) ){
			$classes .= ' full-width centered';
		}elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
			$classes .= ' rightsidebar';
		}elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
			$classes .= ' leftsidebar';
		}else{
			$classes .= ' default-sidebar';
		}
	}

	$content_style_source = Helpers::get_meta( $post->ID, 'content_style_source', 'inherit' );

	if ( $content_style_source === 'custom' ) {
		$page_content_area = Helpers::get_meta( $post->ID, 'content_style', 'boxed' );
		if( $page_content_area == 'boxed' ){
			$classes .=' box-layout';
		}elseif( $page_content_area == 'content_boxed' ){
			$classes .=' content-box-layout';
		}else{
			$classes .=' default-layout';
		}
	}

	if ( get_post_type( $post ) === 'post' || get_post_type( $post ) === 'page' ) {
		$content_style = Helpers::get_meta( $post->ID, 'content_style', 'boxed' );
		$classes .= ' ' . $content_style;
	}
	return $classes;
}
add_filter( 'admin_body_class', 'rishi_admin_body_classes' );
