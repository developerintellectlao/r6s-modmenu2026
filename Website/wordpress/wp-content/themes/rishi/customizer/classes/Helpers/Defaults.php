<?php
/**
 * Provide Default Values.
 */
namespace Rishi\Customizer\Helpers;

class Defaults {

	/**
	 * Get typography values in an array and return data in the appropriate format.
	 *
	 * @param array $values
	 * @param string $context
	 * @return array
	 */
	public static function typography_value( $values = array(), $context = null ) {
		$defaults = array_merge(
			array(
				'family'          => 'Default',
				'weight'          => '400',
				'size'            => array(
					'desktop' => '18px',
					'tablet'  => '18px',
					'mobile'  => '18px',
				),
				'style'           => 'default',
				'line-height'            => array(
					'desktop' => '1.75em',
					'tablet'  => '1.75em',
					'mobile'  => '1.75em',
				),
				'letter-spacing'            => array(
					'desktop' => '0px',
					'tablet'  => '0px',
					'mobile'  => '0px',
				),
				'text-transform'  => 'default',
				'text-decoration' => '',
			),
			$values
		);

		$defaults = apply_filters( 'rishi_defaults_typography', $defaults, $context );

		if ( $context ) {
			$defaults = apply_filters( "rishi_defaults_typography_{$context}", $defaults );
		}

		return $defaults;
	}

	/**
	 * Get default header placements value
	 *
	 * @return void
	 */
	public static function header_placements_value() {
		return new Header_Placements_Default();
	}

	/**
	 * Get default footer placements value
	 *
	 * @return void
	 */
	public static function footer_placements_value() {
		return new Footer_Placements_Default();
	}

	/**
	 * Gets default color value.
	 *
	 * @return array
	 */
	public static function color_value() {
		return array(
			'primary_color'                         => 'var(--paletteColor1)',
			'base_color'                            => 'var(--paletteColor7)',
			'site_background_color'                 => 'var(--paletteColor8)',
			'genLinkColor'                          => 'var(--paletteColor3)',
			'genLinkHoverColor'                     => 'var(--paletteColor4)',
			'linkHighlightColor'                    => 'var(--paletteColor3)',
			'linkHighlightHoverColor'               => 'var(--paletteColor1)',
			'linkHighlightBackgroundColor'          => 'var(--paletteColor6)',
			'linkHighlightBackgroundHoverColor'     => 'var(--paletteColor3)',
			'genheadingColor'                       => 'var(--paletteColor2)',
			'textSelectionColor'                    => 'var(--paletteColor3)',
			'genborderColor'                        => 'var(--paletteColor6)',
			'accentColorOne'                        => 'var(--paletteColor5)',
			'accentColorTwo'                        => 'var(--paletteColor5)',
			'accentColorThree'                      => 'var(--paletteColor5)',
			'site_background'                       => 'var(--paletteColor8)',
			'top_header_bg_color'                   => 'var(--paletteColor2)',
			'top_header_text_color'                 => 'var(--paletteColor1)',
			'top_header_link_color'                 => 'var(--paletteColor3)',
			'top_header_link_hover_color'           => 'var(--paletteColor2)',
			'primary_header_bg_color'               => 'var(--paletteColor5)',
			'primary_header_bottom_border_color'    => 'rgba(41, 41, 41, 0.05)',
			'primary_header_menu_item_color'        => 'var(--paletteColor1)',
			'primary_header_menu_item_hover_color'  => 'var(--paletteColor3)',
			'primary_header_menu_item_active_color' => 'var(--paletteColor3)',
			'header_btn_text_color'                 => 'var(--paletteColor5)',
			'header_btn_text_hover_color'           => 'var(--paletteColor3)',
			'header_btn_bg_color'                   => 'var(--paletteColor3)',
			'header_btn_bg_hover_color'             => 'var(--paletteColor5)',
			'header_btn_border_color'               => 'var(--paletteColor3)',
			'header_btn_border_hover_color'         => 'var(--paletteColor3)',
			'footer_bg_color'                       => 'var(--paletteColor2)',
			'footer_widget_title_color'             => 'rgba(255, 255, 255, 1)',
			'footer_text_color'                     => 'rgba(255, 255, 255, 0.9)',
			'footer_link_color'                     => 'rgba(255, 255, 255, 0.9)',
			'footer_link_hover_color'               => 'var(--paletteColor3)',
			'footer_border_top_color'               => 'rgba(255, 255, 255, 0.1)',
			'footer_bar_border_top_color'           => 'rgba(255, 255, 255, 0.1)',
			'footer_list_item_border_bottom_color'  => 'rgba(255, 255, 255, 0.1)',
			'footer_bar_bg_color'                   => 'var(--paletteColor2)',
			'footer_bar_text_color'                 => 'rgba(255,255,255,0.6)',
			'footer_bar_link_color'                 => 'rgba(255,255,255,0.6)',
			'footer_bar_link_hover_color'           => 'rgba(255,255,255,1)',
			'topButtonIconColorDefault'             => 'var(--paletteColor3)',
			'topButtonIconColorHover'               => 'var(--paletteColor5)',
			'topButtonShapeBackgroundDefault'       => 'var(--paletteColor5)',
			'topButtonShapeBackgroundHover'         => 'var(--paletteColor3)',
			'topButtonBorderDefaultColor'           => 'var(--paletteColor3)',
			'topButtonBorderHoverColor'             => 'var(--paletteColor3)',
			'breadcrumbsColor'                      => 'rgba(41,41,41,0.75)',
			'breadcrumbsCurrentColor'               => 'var(--paletteColor1)',
			'breadcrumbsSeparatorColor'             => 'rgba(41,41,41,0.75)',
			'btn_text_color'                        => 'var(--paletteColor5)',
			'btn_text_hover_color'                  => 'var(--paletteColor3)',
			'btn_bg_color'                          => 'var(--paletteColor3)',
			'btn_bg_hover_color'                    => 'var(--paletteColor5)',
			'btn_border_color'                      => 'var(--paletteColor3)',
			'btn_border_hover_color'                => 'var(--paletteColor3)',
			'woo_btn_text_color'                    => 'var(--paletteColor5)',
			'woo_btn_text_hover_color'              => 'var(--paletteColor5)',
			'woo_btn_bg_color'                      => 'var(--paletteColor3)',
			'woo_btn_bg_hover_color'                => 'var(--paletteColor4)',
			'woo_btn_border_color'                  => 'var(--paletteColor3)',
			'woo_btn_border_hover_color'            => 'var(--paletteColor4)',
		);
	}

	/**
	 * Gets default customizer typography value.
	 *
	 * @return array
	 */
	public static function typography_defaults() {
		$defaults = array(
			'body'          => \Rishi\Customizer\Helpers\Defaults::typography_value( array(
				'family'           => 'System Default',
				'weight'           => '400',
				'size'            => array(
					'desktop' => '18px',
					'tablet'  => '18px',
					'mobile'  => '18px',
				),
				'line_height'      => array(
					'desktop' => '1.75em',
					'tablet'  => '1.75em',
					'mobile'  => '1.75em',
				),
			)),
			'heading'       => array(
				'family'    => 'System Default',
				'weight'    => '600',
				'transform' => 'none',
			),
			'sitetitle'     => array(
				'family'    => 'System Default',
				'weight'    => '400',
				'transform' => 'none',
			),
			'sitetagline'   => array(
				'family'    => 'System Default',
				'weight'    => '300',
				'transform' => 'none',
			),
			'primarynav'    => array(
				'family'    => 'System Default',
				'weight'    => '400',
				'transform' => 'none',
			),
			'header_button' => array(
				'family'    => 'System Default',
				'weight'    => '400',
				'transform' => 'uppercase',
			),
			'breadcrumb'    => array(
				'family'    => 'System Default',
				'weight'    => '400',
				'transform' => 'none',
			),
			'button'        => array(
				'family'    => 'System Default',
				'weight'    => '400',
				'transform' => 'none',
			),
			'heading_one'   => \Rishi\Customizer\Helpers\Defaults::typography_value( array(
				'weight'           => '700',
				'size'            => array(
					'desktop' => '40px',
					'tablet'  => '40px',
					'mobile'  => '40px',
				),
				'line_height'      => array(
					'desktop' => '1.5em',
					'tablet'  => '1.5em',
					'mobile'  => '1.5em',
				),
			) ),
			'heading_two'   => \Rishi\Customizer\Helpers\Defaults::typography_value( array(
				'weight'           => '700',
				'size'            => array(
					'desktop' => '36px',
					'tablet'  => '36px',
					'mobile'  => '36px',
				),
				'line_height'      => array(
					'desktop' => '1.5em',
					'tablet'  => '1.5em',
					'mobile'  => '1.5em',
				),
			) ),
			'heading_three' => \Rishi\Customizer\Helpers\Defaults::typography_value( array(
				'weight'           => '700',
				'size'            => array(
					'desktop' => '30px',
					'tablet'  => '30px',
					'mobile'  => '30px',
				),
				'line_height'      => array(
					'desktop' => '1.5em',
					'tablet'  => '1.5em',
					'mobile'  => '1.5em',
				),
			) ),
			'heading_four'  => \Rishi\Customizer\Helpers\Defaults::typography_value( array(
				'weight'           => '700',
				'size'            => array(
					'desktop' => '26px',
					'tablet'  => '26px',
					'mobile'  => '26px',
				),
				'line_height'      => array(
					'desktop' => '1.5em',
					'tablet'  => '1.5em',
					'mobile'  => '1.5em',
				),
			) ),
			'heading_five'  => \Rishi\Customizer\Helpers\Defaults::typography_value( array(
				'weight'           => '700',
				'size'            => array(
					'desktop' => '22px',
					'tablet'  => '22px',
					'mobile'  => '22px',
				),
				'line_height'      => array(
					'desktop' => '1.5em',
					'tablet'  => '1.5em',
					'mobile'  => '1.5em',
				),
			) ),
			'heading_six'   => \Rishi\Customizer\Helpers\Defaults::typography_value( array(
				'weight'           => '700',
				'size'            => array(
					'desktop' => '18px',
					'tablet'  => '18px',
					'mobile'  => '18px',
				),
				'line_height'      => array(
					'desktop' => '1.5em',
					'tablet'  => '1.5em',
					'mobile'  => '1.5em',
				),
			) ),
			'widgets'       => array(
				'family'        => 'System Default',
				'weight'        => '600',
				'transform'     => 'none',
				'bottom_margin' => 50,
			),
			'footer'        => array(
				'family'    => 'System Default',
				'weight'    => '400',
				'transform' => 'none',
			),
		);

		return apply_filters( 'rishi_typography_options_defaults', $defaults );
	}

	/**
	 * Gets customizer typography value.
	 *
	 * @return array
	 */
	public function get_customizer_typography_value() {
		$defaults                         = self::typography_defaults();
		$settings                         = array();
		$settings['rootTypography']       = get_theme_mod( 'rootTypography', $defaults['body'] );
		$settings['h1Typography']         = get_theme_mod( 'h1Typography', $defaults['body'] );
		$settings['h2Typography']         = get_theme_mod( 'h2Typography', $defaults['body'] );
		$settings['h3Typography']         = get_theme_mod( 'h3Typography', $defaults['body'] );
		$settings['h4Typography']         = get_theme_mod( 'h4Typography', $defaults['body'] );
		$settings['h5Typography']         = get_theme_mod( 'h5Typography', $defaults['body'] );
		$settings['h6Typography']         = get_theme_mod( 'h6Typography', $defaults['body'] );
		$settings['copyrightFont']        = get_theme_mod( 'copyrightFont', $defaults['body'] );
		$settings['button_Typo']          = get_theme_mod( 'button_Typo', $defaults['body'] );
		$settings['breadcrumbsTypo']      = get_theme_mod( 'breadcrumbsTypo', $defaults['body'] );
		$settings['woo_shop_title_typo']  = get_theme_mod( 'woo_shop_title_typo', $defaults['body'] );
		$settings['woo_shop_button_typo'] = get_theme_mod( 'woo_shop_button_typo', $defaults['body'] );
		$settings['wooNoticeTypo']        = get_theme_mod( 'wooNoticeTypo', $defaults['body'] );
		$settings['trigger_typo']         = get_theme_mod( 'trigger_typo', $defaults['body'] );

		return apply_filters('rishi_customizer_typography_values',$settings);
	}

	/**
	 * Gets header customizer typography value.
	 *
	 * @return array
	 */
	public function get_header_customizer_typography_value() {
		$settings = array();
		// Header Fonts.
		$header_default                   = self::get_header_defaults();
		$header_elements_menu             = rishi_customizer()->header_builder->get_elements()->get_items()['menu'];
		$header_elements_menu2            = rishi_customizer()->header_builder->get_elements()->get_items()['menu-secondary'];
		$header_elements_date             = rishi_customizer()->header_builder->get_elements()->get_items()['date'];
		$header_elements_button           = rishi_customizer()->header_builder->get_elements()->get_items()['button'];
		$header_elements_randomize        = rishi_customizer()->header_builder->get_elements()->get_items()['randomize'];
		$header_elements_text             = rishi_customizer()->header_builder->get_elements()->get_items()['text'];
		$header_elements_logo             = rishi_customizer()->header_builder->get_elements()->get_items()['logo'];
		$header_elements_contacts         = rishi_customizer()->header_builder->get_elements()->get_items()['contacts'];
		$header_elements_trigger          = rishi_customizer()->header_builder->get_elements()->get_items()['trigger'];
		$_menu1instance                   = new $header_elements_menu();
		$_menu2instance                   = new $header_elements_menu2();
		$_dateinstance                    = new $header_elements_date();
		$_buttoninstance                  = new $header_elements_button();
		$_randomizeinstance               = new $header_elements_randomize();
		$_textinstance                    = new $header_elements_text();
		$_logoinstance                    = new $header_elements_logo();
		$_contactsinstance                = new $header_elements_contacts();
		$_triggerinstance                 = new $header_elements_trigger();
		$settings['headerMenuFont']       = $_menu1instance->get_mod_value( 'headerMenuFont', $header_default['headerMenuFont'] );
		$settings['headerMenu2Font']      = $_menu2instance->get_mod_value( 'headerMenuFont', $header_default['headerMenuFont'] );
		$settings['headerDateFont']       = $_dateinstance->get_mod_value( 'headerDateFont', $header_default['headerDateFont'] );
		$settings['headerButtonFont']     = $_buttoninstance->get_mod_value( 'headerButtonFont', $header_default['headerButtonFont'] );
		$settings['headerDropdownFont']   = $_menu1instance->get_mod_value( 'headerDropdownFont', $header_default['headerDropdownFont'] );
		$settings['mobileMenuFont']       = $_menu1instance->get_mod_value( 'mobileMenuFont', $header_default['mobileMenuFont'] );
		$settings['headerRandomizeFont']  = $_randomizeinstance->get_mod_value( 'headerRandomizeFont', $header_default['headerRandomizeFont'] );
		$settings['headerTextFont']       = $_textinstance->get_mod_value( 'headerTextFont', $header_default['headerTextFont'] );
		$settings['siteTitle']            = $_logoinstance->get_mod_value( 'siteTitle', $header_default['siteTitle'] );
		$settings['siteTagline']          = $_logoinstance->get_mod_value( 'siteTagline', $header_default['siteTagline'] );
		$settings['header_contacts_font'] = $_contactsinstance->get_mod_value( 'header_contacts_font', $header_default['header_contacts_font'] );
		$settings['trigger_typo']         = $_triggerinstance->get_mod_value( 'trigger_typo', $header_default['trigger_typo'] );
		return $settings;
	}

	/**
	 * Gets footer customizer typography value.
	 *
	 * @return array
	 */
	public static function get_footer_customizer_typography_value() {
		$settings = array();
		// Footer Fonts.
		$footer_default                                = self::get_footer_defaults();
		$rowTop_typo_default                           = self::get_footer_row_defaults()['top-row'];
		$rowMiddle_typo_default                        = self::get_footer_row_defaults()['middle-row'];
		$rowBottom_typo_default                        = self::get_footer_row_defaults()['bottom-row'];
		$footer_elements_top_row                       = rishi_customizer()->footer_builder->get_elements()->get_items()['top-row'];
		$footer_elements_middle_row                    = rishi_customizer()->footer_builder->get_elements()->get_items()['middle-row'];
		$footer_elements_bottom_row                    = rishi_customizer()->footer_builder->get_elements()->get_items()['bottom-row'];
		$footer_elements_menu                          = rishi_customizer()->footer_builder->get_elements()->get_items()['footer-menu'];
		$footer_elements_contacts                      = rishi_customizer()->footer_builder->get_elements()->get_items()['contacts'];
		$_menuinstance                                 = new $footer_elements_menu();
		$_topRowinstance                               = new $footer_elements_top_row();
		$_middleRowinstance                            = new $footer_elements_middle_row();
		$_bottomRowinstance                            = new $footer_elements_bottom_row();
		$_contactsinstance                             = new $footer_elements_contacts();
		$settings['footerMenuFont']                    = $_menuinstance->get_mod_value( 'footerMenuFont', $footer_default['footerMenuFont'] );
		$settings['top-row-footerWidgetsTitleFont']    = $_topRowinstance->get_mod_value( 'top-row-footerWidgetsTitleFont', $rowTop_typo_default['top-row-footerWidgetsTitleFont'] );
		$settings['middle-row-footerWidgetsTitleFont'] = $_middleRowinstance->get_mod_value( 'middle-row-footerWidgetsTitleFont', $rowMiddle_typo_default['middle-row-footerWidgetsTitleFont'] );
		$settings['bottom-row-footerWidgetsTitleFont'] = $_bottomRowinstance->get_mod_value( 'bottom-row-footerWidgetsTitleFont', $rowBottom_typo_default['bottom-row-footerWidgetsTitleFont'] );
		$settings['top-row-footerWidgetsFont']         = $_topRowinstance->get_mod_value( 'top-row-footerWidgetsFont', $rowTop_typo_default['top-row-footerWidgetsFont'] );
		$settings['middle-row-footerWidgetsFont']      = $_middleRowinstance->get_mod_value( 'middle-row-footerWidgetsFont', $rowMiddle_typo_default['middle-row-footerWidgetsFont'] );
		$settings['bottom-row-footerWidgetsFont']      = $_bottomRowinstance->get_mod_value( 'bottom-row-footerWidgetsFont', $rowBottom_typo_default['bottom-row-footerWidgetsFont'] );
		$settings['footer_contacts_font']              = $_contactsinstance->get_mod_value( 'footer_contacts_font', $footer_default['footer_contacts_font'] );
		return $settings;
	}

	/**
	 * All fonts key.
	 *
	 * @return array
	 */
	public function get_rishi_fonts_key() {
		$font_settings = array(
			'rootTypography',
			'h1Typography',
			'h2Typography',
			'h3Typography',
			'h4Typography',
			'h5Typography',
			'h6Typography',
			'footer_contacts_font',
			'header_contacts_font',
			'copyrightFont',
			'siteTitle',
			'siteTagline',
			'button_Typo',
			'breadcrumbsTypo',
			'woo_shop_title_typo',
			'woo_shop_button_typo',
			'wooNoticeTypo',
			'footerMenuFont',
			'headerDateFont',
			'headerRandomizeFont',
			'headerTextFont',
			'mobileMenuFont',
			'headerMenuFont',
			'headerMenu2Font',
			'headerDropdownFont',
			'headerButtonFont',
			'trigger_typo',
			'top-row-footerWidgetsTitleFont',
			'middle-row-footerWidgetsTitleFont',
			'bottom-row-footerWidgetsTitleFont',
			'top-row-footerWidgetsFont',
			'middle-row-footerWidgetsFont',
			'bottom-row-footerWidgetsFont',
		);
		return apply_filters('rishi_typography_font_keys',$font_settings);
	}

	/**
	 * Get Default Fonts.
	 *
	 * @return array
	 */
	public function rishi_typography_default_fonts() {
		$fonts = array(
			'System Default',
			'Default',
			'Arial',
			'Verdana',
			'Trebuchet',
			'Georgia',
			'Times New Roman',
			'Palatino',
			'Helvetica',
			'Myriad Pro',
			'Lucida',
			'Gill Sans',
			'Impact',
			'Serif',
			'monospace',
		);

		return apply_filters( 'rishi_typography_default_fonts', $fonts );
	}

	/**
	 * Set default customizer setting value for breadcrumb
	 *
	 * @return array
	 */
	public static function breadcrumbs_defaults() {

		$defaults = array(
			'breadcrumbs_position'           => 'before',
			'breadcrumbs_separator'          => 'type-1',
			'breadcrumbs_ed_search'          => 'no',
			'breadcrumbs_ed_author'          => 'no',
			'breadcrumbs_ed_archive'         => 'no',
			'breadcrumbs_ed_single_post'     => 'yes',
			'breadcrumbs_ed_single_page'     => 'yes',
			'breadcrumbs_ed_single_product'  => 'yes',
			'breadcrumbs_ed_archive_product' => 'yes',
			'blog_ed_breadcrumbs'            => 'yes',
			'breadcrumbs_ed_404'             => 'no',
			'breadcrumbs_alignment'          => 'left',
		);

		return apply_filters( 'rishi_breadcrumbs_options_defaults', $defaults );
	}

	/**
	 * Set default customizer setting value for button
	 *
	 * @return array
	 */
	public static function button_defaults() {

		$defaults = array(
			'button_padding' => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '15',
						'left'   => '34',
						'right'  => '34',
						'bottom' => '15',
						'unit'   => 'px',
				)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '15',
						'left'   => '34',
						'right'  => '34',
						'bottom' => '15',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '15',
						'left'   => '34',
						'right'  => '34',
						'bottom' => '15',
						'unit'   => 'px',
					)
				)
			),
			'botton_roundness'  => array(
				'desktop' => '3px',
				'tablet'  => '3px',
				'mobile'  => '3px',
			),
			'top_button_padding'    => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => true,
					'top'    => '10',
					'left'   => '10',
					'right'  => '10',
					'bottom' => '10',
					'unit'   => 'px',
				)
			),
			'admin_buttonRoundness' => '3px',
			'admin_buttonPadding'   => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => false,
					'top'    => '15',
					'left'   => '34',
					'right'  => '34',
					'bottom' => '15',
					'unit'   => 'px',
				)
			),
		);

		return apply_filters( 'rishi_button_options_defaults', $defaults );
	}

	/**
	 * Set default customizer setting value for blogpost meta elements.
	 *
	 * @return array
	 */
	public static function blogpost_structure_defaults() {

		$default_post_structure = array();

		$default_post_structure[] = array(
			'id'                        => 'featured_image',
			'enabled'                   => true,
			'featured_image_ratio'      => 'auto',
			'featured_image_scale'      => 'contain',
			'featured_image_size'       => 'full',
			'featured_image_visibility' => array(
				'desktop' => 'desktop',
				'tablet'  => 'tablet',
				'mobile'  => 'mobile',
			),
		);

		$default_post_structure[] = array(
			'id'        => 'categories',
			'enabled'   => true,
			'separator' => 'dot',
		);

		$default_post_structure[] = array(
			'id'          => 'custom_title',
			'enabled'     => true,
			'heading_tag' => 'h2',
			'font_size'   => array(
				'desktop' => '40px',
				'tablet'  => '36px',
				'mobile'  => '24px',
			),
		);

		$default_post_structure[] = array(
			'id'                      => 'custom_meta',
			'enabled'                 => true,
			'archive_postmeta'        => array(
				0 => 'author',
				1 => 'published-date',
				2 => 'comments',
			),
			'divider_divider'         => 'dot',
			'label'                   => __( 'By', 'rishi' ),
			'has_author_avatar'       => 'no',
			'avatar_size'             => '34px',
			'words_per_minute'        => 200,
			'show_updated_date_label' => 'yes',
			'updated_date_label'      => __( 'Updated On', 'rishi' ),
		);

		$default_post_structure[] = array(
			'id'             => 'excerpt',
			'enabled'        => true,
			'post_content'   => 'excerpt',
			'excerpt_length' => 30,
		);

		$default_post_structure[] = array(
			'id'             => 'divider',
			'enabled'        => true,
			'divider_margin' => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '20',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '20',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '20',
						'unit'   => 'px',
					)
				),
			),
		);

		$default_post_structure[] = array(
			'id'              => 'read_more',
			'enabled'         => true,
			'button_type'     => 'simple',
			'read_more_text'  => __( 'Read More', 'rishi' ),
			'read_more_arrow' => 'yes',
		);

		return apply_filters( 'rishi_blogpost_structure_defaults', $default_post_structure );

	}

	/**
	 *  Set default customizer setting value for singlepost meta elements.
	 *
	 * @return array
	 */
	public static function singlepost_structure_defaults() {

		$default_post_structure = array();

		$default_post_structure[] = array(
			'id'                        => 'featured_image',
			'enabled'                   => true,
			'featured_image_ratio'      => 'auto',
			'featured_image_scale'      => 'contain',
			'featured_image_size'       => 'full',
			'featured_image_visibility' => array(
				'desktop' => 'desktop',
				'tablet'  => 'tablet',
				'mobile'  => 'mobile',
			),
		);

		$default_post_structure[] = array(
			'id'        => 'categories',
			'enabled'   => true,
			'separator' => 'dot',
		);

		$default_post_structure[] = array(
			'id'          => 'custom_title',
			'enabled'     => true,
			'heading_tag' => 'h1',
			'font_size'   => array(
				'desktop' => '36px',
				'tablet'  => '28px',
				'mobile'  => '24px',
			),
		);

		$default_post_structure[] = array(
			'id'                      => 'custom_meta',
			'enabled'                 => true,
			'archive_postmeta'        => array(
				0 => 'author',
				1 => 'published-date',
				2 => 'comments',
			),
			'divider_divider'         => 'dot',
			'label'                   => __( 'By', 'rishi' ),
			'has_author_avatar'       => 'no',
			'avatar_size'             => '34px',
			'words_per_minute'        => 200,
			'show_updated_date_label' => 'yes',
			'updated_date_label'      => __( 'Updated On', 'rishi' ),
		);

		return apply_filters( 'rishi_singlepost_structure_defaults', $default_post_structure );

	}

	/**
	 * Set default value for customizer layout and miscellaneous settings.
	 * @param string $default
	 * @param string $index
	 * @return array
	 */
	public static function get_layout_defaults( $default = '', $index = null ) {

		$defaults = array(
			'blog_page_layout'               => 'classic',
			'post_navigation'                => 'numbered',
			'blog_sidebar_layout'            => 'default-sidebar',
			'blog_container'                 => 'default',
			'blog_container_streched_ed'     => 'no',
			'post_sidebar_layout'            => 'right-sidebar',
			'blog_post_layout'               => 'default',
			'blog_post_streched_ed'          => 'no',
			'archive_page_layout'            => 'classic',
			'archive_post_navigation'        => 'numbered',
			'archive_sidebar_layout'         => 'default-sidebar',
			'archive_layout'                 => 'default',
			'archive_layout_streched_ed'     => 'no',
			'woo_layout_streched_ed'         => 'no',
			'author_page_layout'             => 'classic',
			'author_post_navigation'         => 'numbered',
			'author_sidebar_layout'          => 'default-sidebar',
			'author_layout'                  => 'default',
			'author_layout_streched_ed'      => 'no',
			'author_page_avatar_size'        => array(
				'desktop' => '142px',
				'tablet'  => '142px',
				'mobile'  => '142px',
			),
			'author_page_author_margin'      => '30px',
			'author_page_margin'             => array(
				'desktop' => '30px',
				'tablet'  => '20px',
				'mobile'  => '20px',
			),
			'search_page_layout'             => 'classic',
			'search_post_navigation'         => 'numbered',
			'search_sidebar_layout'          => 'default-sidebar',
			'search_layout'                  => 'default',
			'search_layout_streched_ed'      => 'no',
			'page_layout_streched_ed'        => 'no',
			'container_width'                => array(
				'desktop' => '1200px',
				'tablet'  => '992px',
				'mobile'  => '420px',
			),
			'container_content_max_width'    => array(
				'desktop' => '728px',
				'tablet'  => '500px',
				'mobile'  => '400px',
			),
			'containerVerticalMargin'        => array(
				'desktop' => '80px',
				'tablet'  => '40px',
				'mobile'  => '40px',
			),
			'sidebar_widget_spacing'         => array(
				'desktop' => '32px',
				'tablet'  => '30px',
				'mobile'  => '24px',
			),
			'widgets_font_size'              => array(
				'desktop' => '18px',
				'tablet'  => '16px',
				'mobile'  => '14px',
			),
			'widgets_content_area_spacing'   => array(
				'top'    => '0px',
				'right'  => '0px',
				'bottom' => '20px',
				'left'   => '20px',
			),
			'layout'                         => 'boxed',
			'page_layout'                    => 'boxed',
			'woocommerce_layout'             => 'default',
			'layout_style'                   => 'no-sidebar',
			'page_sidebar_layout'            => 'right-sidebar',
			'woocommerce_sidebar_layout'     => 'no-sidebar',
			'single_product_sidebar_layout'  => 'default-sidebar',
			'content_sidebar_width'          => '28%',
			'ed_footer_widget_title'         => 'yes',
			'ed_show_post_navigation'        => 'yes',
			'ed_show_post_author'            => 'yes',
			'author_box_layout'              => 'layout-one',
			'ed_scroll_to_top'               => 'no',
			'topButtonShadow'                => array(
				'enable'   => true,
				'inset'    => false,
				'h_offset' => '0px',
				'v_offset' => '5px',
				'blur'     => '20px',
				'spread'   => '0px',
				'color'    => 'rgba(210, 213, 218, 0.2)',
			),
			'sticky_row_box_shadow'          => array(
				'enable'   => false,
				'inset'    => false,
				'h_offset' => '0px',
				'v_offset' => '10px',
				'blur'     => '20px',
				'spread'   => '0px',
				'color'    => 'rgba(41, 51, 61, 0.1)',
			),
			'top_btn_border' => array(
				'width' => 1,
				'style' => 'solid',
				'color' => array(
					'color' => 'var(--paletteColor3)',
					'hover' => 'var(--paletteColor3)',
				),
			),
			'btn_border' => array(
				'width' => 1,
				'style' => 'solid',
				'color' => array(
					'color' => 'var(--paletteColor3)',
					'hover' => 'var(--paletteColor3)',
				),
			),
			'ed_post_tags'                   => 'yes',
			'ed_link_highlight'              => 'yes',
			'ed_related'                     => 'yes',
			'underlinestyle'                 => 'style1',
			'single_related_title'           => __( 'Related Posts', 'rishi' ),
			'related_taxonomy'               => 'cat',
			'ed_related_after_comment'       => 'no',
			'ed_comment'                     => 'yes',
			'ed_page_comment'                => 'no',
			'no_of_related_post'             => 3,
			'related_post_per_row'           => 3,
			'ed_comment_form_above_clist'    => 'yes',
			'ed_comment_below_content'       => 'default',
			'single_related_products'        => __( 'Related Products', 'rishi' ),
			'admin_containerWidth'           => '1234px',
			'admin_containerContentMaxWidth' => '728px',
		);

		if ( $index ) {
			if ( isset( $defaults[ $index ] ) ) {
				return $defaults[ $index ];
			} else {
				return $default;
			}
		}

		return apply_filters( 'rishi_button_layouts_defaults', $defaults );
	}

	/**
	 * Set default value for footer row settings.
	 *
	 * @return array
	 */
	public static function get_footer_row_defaults() {

		$defaults = array(
			'top-row'    => array(
				'rowColumnSpacing'               => array(
					'desktop' => '60px',
					'tablet'  => '40px',
					'mobile'  => '40px',
				),
				'rowTopSpacing'                  => array(
					'mobile'  => '30px',
					'tablet'  => '30px',
					'desktop' => '30px',
				),
				'rowBottomSpacing'               => array(
					'mobile'  => '30px',
					'tablet'  => '30px',
					'desktop' => '30px',
				),
				'rowItemSpacing'                 => array(
					'desktop' => '15px',
					'tablet'  => '15px',
					'mobile'  => '15px',
				),
				'footer_row_vertical_alignment'  => 'flex-start',
				'footer_row_column_direction'    => 'vertical',
				'footerRowWidth'                 => 'default',
				'items_per_row'                  => '2',
				'2_columns_layout'               => 'repeat(2, 1fr)',
				'3_columns_layout'               => 'repeat(3, 1fr)',
				'4_columns_layout'               => 'repeat(4, 1fr)',
				'5_columns_layout'               => 'repeat(5, 1fr)',
				'custom_footer_row_width'        => '1200px',
				'footerRowTopBorderFullWidth'    => 'default',
				'footerRowBackground'            => array(
					'default' => array(
						'color' => 'var(--paletteColor2)',
					),
				),
				'footerWidgetsTitleColor'        => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'top-row-footerWidgetsTitleFont' => array(
					'size'            => array(
						'desktop' => '16px',
						'tablet'  => '16px',
						'mobile'  => '16px',
					),
					'line-height'            => array(
						'desktop' => '1.75',
						'tablet'  => '1.75',
						'mobile'  => '1.75',
					),
					'letter-spacing'            => array(
						'desktop' => '0.4px',
						'tablet'  => '0.4px',
						'mobile'  => '0.4px',
					),
					'text-transform' => 'uppercase',
				),
				'top-row-footerWidgetsFont'      => array(
					'size'            => array(
						'desktop' => '16px',
						'tablet'  => '16px',
						'mobile'  => '16px',
					),
					'line-height'            => array(
						'desktop' => '1.75',
						'tablet'  => '1.75',
						'mobile'  => '1.75',
					),
					'letter-spacing'            => array(
						'desktop' => '0.4px',
						'tablet'  => '0.4px',
						'mobile'  => '0.4px',
					),
					'text-transform' => 'none',
				),
				'rowFontColor'                   => array(
					'default'      => array(
						'color' => 'var(--paletteColor5)',
					),
					'link_initial' => array(
						'color' => 'var(--paletteColor5)',
					),
					'link_hover'   => array(
						'color' => 'var(--paletteColor3)',
					),
				),
				'footerRowTopDivider'            => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
				'footerRowBottomDivider'         => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
				'footerColumnsDivider'           => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
			),
			'bottom-row' => array(
				'rowColumnSpacing'                  => array(
					'desktop' => '60px',
					'tablet'  => '40px',
					'mobile'  => '40px',
				),
				'rowTopSpacing'                     => array(
					'mobile'  => '15px',
					'tablet'  => '25px',
					'desktop' => '25px',
				),
				'rowBottomSpacing'                  => array(
					'mobile'  => '15px',
					'tablet'  => '25px',
					'desktop' => '25px',
				),
				'rowItemSpacing'                    => array(
					'desktop' => '15px',
					'tablet'  => '15px',
					'mobile'  => '15px',
				),
				'footer_row_vertical_alignment'     => 'flex-start',
				'footer_row_column_direction'       => 'vertical',
				'footerRowWidth'                    => 'default',
				'items_per_row'                     => '1',
				'2_columns_layout'                  => 'repeat(2, 1fr)',
				'3_columns_layout'                  => 'repeat(3, 1fr)',
				'4_columns_layout'                  => 'repeat(4, 1fr)',
				'5_columns_layout'                  => 'repeat(5, 1fr)',
				'custom_footer_row_width'           => '1200px',
				'footerRowTopBorderFullWidth'       => 'default',
				'footerRowBackground'               => array(
					'default' => array(
						'color' => 'var(--paletteColor2)',
					),
				),
				'footerWidgetsTitleColor'           => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'bottom-row-footerWidgetsTitleFont' => array(
					'size'            => array(
						'desktop' => '16px',
						'tablet'  => '16px',
						'mobile'  => '16px',
					),
					'line-height'            => array(
						'desktop' => '1.75',
						'tablet'  => '1.75',
						'mobile'  => '1.75',
					),
					'letter-spacing'            => array(
						'desktop' => '0.4px',
						'tablet'  => '0.4px',
						'mobile'  => '0.4px',
					),
					'text-transform' => 'uppercase',
				),
				'bottom-row-footerWidgetsFont'      => array(
					'size'            => array(
						'desktop' => '16px',
						'tablet'  => '16px',
						'mobile'  => '16px',
					),
					'line-height'            => array(
						'desktop' => '1.75',
						'tablet'  => '1.75',
						'mobile'  => '1.75',
					),
					'letter-spacing'            => array(
						'desktop' => '0.4px',
						'tablet'  => '0.4px',
						'mobile'  => '0.4px',
					),
					'text-transform' => 'none',
				),
				'rowFontColor'                      => array(
					'default'      => array(
						'color' => 'var(--paletteColor5)',
					),
					'link_initial' => array(
						'color' => 'var(--paletteColor5)',
					),
					'link_hover'   => array(
						'color' => 'var(--paletteColor3)',
					),
				),
				'footerRowTopDivider'               => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
				'footerRowBottomDivider'            => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
				'footerColumnsDivider'              => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
			),
			'middle-row' => array(
				'rowColumnSpacing'                  => array(
					'desktop' => '60px',
					'tablet'  => '40px',
					'mobile'  => '40px',
				),
				'rowTopSpacing'                     => array(
					'desktop' => '70px',
					'tablet'  => '50px',
					'mobile'  => '40px',
				),
				'rowBottomSpacing'                  => array(
					'desktop' => '70px',
					'tablet'  => '50px',
					'mobile'  => '40px',
				),
				'rowItemSpacing'                    => array(
					'desktop' => '15px',
					'tablet'  => '15px',
					'mobile'  => '15px',
				),
				'footer_row_vertical_alignment'     => 'start',
				'footer_row_column_direction'       => 'vertical',
				'footerRowWidth'                    => 'default',
				'items_per_row'                     => '3',
				'2_columns_layout'                  => 'repeat(2, 1fr)',
				'3_columns_layout'                  => 'repeat(3, 1fr)',
				'4_columns_layout'                  => 'repeat(4, 1fr)',
				'5_columns_layout'                  => 'repeat(5, 1fr)',
				'custom_footer_row_width'           => '1200px',
				'footerRowTopBorderFullWidth'       => 'default',
				'footerRowBackground'               => array(
					'default' => array(
						'color' => 'var(--paletteColor2)',
					),
				),
				'footerWidgetsTitleColor'           => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'middle-row-footerWidgetsTitleFont' => array(
					'size'            => array(
						'desktop' => '16px',
						'tablet'  => '16px',
						'mobile'  => '16px',
					),
					'line-height'            => array(
						'desktop' => '1.75',
						'tablet'  => '1.75',
						'mobile'  => '1.75',
					),
					'letter-spacing'            => array(
						'desktop' => '0.4px',
						'tablet'  => '0.4px',
						'mobile'  => '0.4px',
					),
					'text-transform' => 'uppercase',
				),
				'middle-row-footerWidgetsFont'      => array(
					'size'            => array(
						'desktop' => '16px',
						'tablet'  => '16px',
						'mobile'  => '16px',
					),
					'line-height'            => array(
						'desktop' => '1.75',
						'tablet'  => '1.75',
						'mobile'  => '1.75',
					),
					'letter-spacing'            => array(
						'desktop' => '0.4px',
						'tablet'  => '0.4px',
						'mobile'  => '0.4px',
					),
					'text-transform' => 'none',
				),
				'rowFontColor'                      => array(
					'default'      => array(
						'color' => 'var(--paletteColor5)',
					),
					'link_initial' => array(
						'color' => 'var(--paletteColor5)',
					),
					'link_hover'   => array(
						'color' => 'var(--paletteColor3)',
					),
				),
				'footerRowTopDivider'               => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
				'footerRowBottomDivider'            => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
				'footerColumnsDivider'              => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => '#dddddd',
					),
				),
			),
		);

		return apply_filters( 'rishi_footer_row_data_defaults', $defaults );
	}

	/**
	 * Set default value for header row settings.
	 *
	 * @return array
	 */
	public static function get_header_row_defaults() {

		$defaults = array(
			'top-row'    => array(
				'headerRowWidth'          => 'default',
				'custom_header_row_width' => '1200px',
				'headerRowBackground'  => array(
					'default' => array(
						'color' => '#f9f9f9',
					),
				),
				'headerRowTopBorder'      => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => 'rgba(44,62,80,0.2)',
					),
				),
				'headerRowBottomBorder'   => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => 'rgba(44,62,80,0.2)',
					),
				),
				'headerRowShadow'         => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value(
					array(
						'enable'   => false,
						'h_offset' => '0px',
						'v_offset' => '10px',
						'blur'     => '20px',
						'spread'   => '0px',
						'inset'    => false,
						'color'    => 'rgba(44,62,80,0.05)',
					)
				),
				'headerRowPadding'        => array(
					'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
					'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
					'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
				),
				'headerRowItemSpacing'    => array(
					'desktop' => '15px',
					'tablet'  => '15px',
					'mobile'  => '15px',
				),
			),
			'bottom-row' => array(
				'headerRowWidth'          => 'default',
				'custom_header_row_width' => '1200px',
				'headerRowBackground'  => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'headerRowTopBorder'      => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => 'rgba(44,62,80,0.2)',
					),
				),
				'headerRowBottomBorder'   => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => 'rgba(44,62,80,0.2)',
					),
				),
				'headerRowShadow'         => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value(
					array(
						'enable'   => false,
						'h_offset' => '0px',
						'v_offset' => '10px',
						'blur'     => '20px',
						'spread'   => '0px',
						'inset'    => false,
						'color'    => 'rgba(44,62,80,0.05)',
					)
				),
				'headerRowPadding'        => array(
					'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
					'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
					'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
				),
				'headerRowItemSpacing'    => array(
					'desktop' => '15px',
					'tablet'  => '15px',
					'mobile'  => '15px',
				),
			),
			'middle-row' => array(
				'headerRowWidth'          => 'default',
				'custom_header_row_width' => '1200px',
				'headerRowBackground'  => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'headerRowTopBorder'      => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => 'rgba(44,62,80,0.2)',
					),
				),
				'headerRowBottomBorder'   => array(
					'width' => 1,
					'style' => 'none',
					'color' => array(
						'color' => 'rgba(44,62,80,0.2)',
					),
				),
				'headerRowShadow'         => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value(
					array(
						'enable'   => false,
						'h_offset' => '0px',
						'v_offset' => '10px',
						'blur'     => '20px',
						'spread'   => '0px',
						'inset'    => false,
						'color'    => 'rgba(44,62,80,0.05)',
					)
				),
				'headerRowPadding'        => array(
					'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
					'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
					'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
						array(
							'linked' => true,
							'top'    => '16',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '16',
							'unit'   => 'px',
						)
					),
				),
				'headerRowItemSpacing'    => array(
					'desktop' => '15px',
					'tablet'  => '15px',
					'mobile'  => '15px',
				),
			),
			'offcanvas'  => array(
				'close_btn_size'            => '30px',
				'offcanvasContentAlignment' => 'left',
				'offcanvasBackground'       => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'bottomBorderColor'         => array(
					'default' => array(
						'color' => 'var(--paletteColor5)',
					),
				),
				'menu_close_button_color'   => array(
					'default' => array(
						'color' => 'var(--paletteColor3)',
					),

					'hover'   => array(
						'color' => 'var(--paletteColor2)',
					),
				),
				'offcanvasItemSpacing'      => '30px',
			),
		);

		return apply_filters( 'rishi_header_row_data_defaults', $defaults );
	}

	/**
	 * Set default value for footer elements settings.
	 *
	 * @return array
	 */
	public static function get_footer_defaults() {

		$defaults = array(
			'copyrightMargin'             => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
			),
			'copyrightColor'              => array(
				'default' => array(
					'color' => 'rgba(255,255,255,0.6)',
				),
			),
			'contacts_margin'             => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
			),
			'copyrightLinkColor'          => array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'copyrightFont'              => array(
				'size'            => array(
					'desktop' => '14px',
					'tablet'  => '14px',
					'mobile'  => '14px',
				),
				'line-height'            => array(
					'desktop' => '1.75em',
					'tablet'  => '1.75em',
					'mobile'  => '1.75em',
				),
				'letter-spacing'            => array(
					'desktop' => '0.6px',
					'tablet'  => '0.6px',
					'mobile'  => '0.6px',
				),
			),
			'contacts_icon_size'          => '15px',
			'contacts_spacing'            => '15px',
			'footer_contacts_font'        => array(
				'size'            => array(
					'desktop' => '14px',
					'tablet'  => '14px',
					'mobile'  => '14px',
				),
				'line-height'            => array(
					'desktop' => '1.3',
					'tablet'  => '1.3',
					'mobile'  => '1.3',
				),
				'weight'         => '400',
				'text-transform' => 'normal',
			),
			'contacts_font_color'         => array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'contacts_icon_color'         => array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'contacts_icon_background'    => array(
				'default' => array(
					'color' => 'var(--paletteColor6)',
				),
				'hover'   => array(
					'color' => 'rgba(218, 222, 228, 0.7)',
				),
			),
			// Footer Socials
			'footerSocialsIconColor'      => array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),

				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'footerSocialsMargin'         => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => true,
					'top'    => '0',
					'left'   => '0',
					'bottom' => '0',
					'right'  => '0',
					'unit'   => 'px',
				)
			),
			'footerSocialsIconBackground' => array(
				'default' => array(
					'color' => 'var(--paletteColor7)',
				),

				'hover'   => array(
					'color' => 'var(--paletteColor6)',
				),
			),
			'footersocialsIconSize'       => '15px',
			'footersocialsIconSpacing'    => '15px',
			// Footer Menu
			'footerMenuFontColor'         => array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'footerMenuMargin'            => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
			),
			'footerMenuItemsSpacing'      => '15px',
			'footerMenuFont'              => array(
				'size'            => array(
					'desktop' => '16px',
					'tablet'  => '16px',
					'mobile'  => '16px',
				),
				'line-height'            => array(
					'desktop' => '2.25',
					'tablet'  => '2.25',
					'mobile'  => '2.25',
				),
				'weight'         => '400',
				'text-transform' => 'normal',
			),
		);

		return apply_filters( 'rishi_footer_data_defaults', $defaults );
	}

	/**
	 * Set default value for header elements settings.
	 *
	 * @return array
	 */
	public static function get_header_defaults() {

		$defaults = array(
			'headerDateColor'             => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
			),
			'headerDateIconColor'         => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
			),
			'headerDateFont'              => array(
				'size'            => array(
					'desktop' => '16px',
					'tablet'  => '16px',
					'mobile'  => '16px',
				),
				'line-height'            => array(
					'desktop' => '2.25',
					'tablet'  => '2.25',
					'mobile'  => '2.25',
				),
				'weight'         => '400',
				'text-transform' => 'normal',
			),
			'header_date_icon_size'       => '18px',
			'headerTextFont'              => array(
				'size'            => array(
					'desktop' => '15px',
					'tablet'  => '15px',
					'mobile'  => '15px',
				),
				'line-height'            => array(
					'desktop' => '1.5',
					'tablet'  => '1.5',
					'mobile'  => '1.5',
				),
				'weight'         => '400',
				'text-transform' => 'normal',
			),
			'headerTextColor'             => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
			),
			'headerLinkColor'             => array(
				'default' => array(
					'color' => 'var(--paletteColor3)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor2)',
				),
			),
			'headerTextMargin'            => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'top'    => 'auto',
					'bottom' => 'auto',
					'left'   => '20',
					'right'  => '20',
					'linked' => true,
					'unit'   => 'px',
				)
			),
			'header_image_max_width'      => array(
				'desktop' => '150px',
				'tablet'  => '150px',
				'mobile'  => '150px',
			),
			'menuFontColor'               => array(
				'default'      => array(
					'color' => 'var(--paletteColor1)',
				),
				'hover'        => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'headerMenuFont'              => array(
				'size'            => array(
					'desktop' => '16px',
					'tablet'  => '16px',
					'mobile'  => '16px',
				),
				'line-height'            => array(
					'desktop' => '2.25',
					'tablet'  => '2.25',
					'mobile'  => '2.25',
				),
				'weight'         => '400',
				'text-transform' => 'normal',
			),
			'headerMenuMargin'            => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => 'auto',
						'left'   => '20',
						'right'  => '20',
						'bottom' => 'auto',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => 'auto',
						'left'   => '20',
						'right'  => '20',
						'bottom' => 'auto',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => 'auto',
						'left'   => '20',
						'right'  => '20',
						'bottom' => 'auto',
						'unit'   => 'px',
					)
				),
			),
			'menuIndicatorColor'          => array(
				'default' => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'currentMenuLinkBg'           => array(
				'default' => array(
					'color' => 'var(--paletteColor7)',
				),
			),
			'headerDropdownBackground'    => array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor7)',
				),
			),
			'dropdownItemsSpacing'        => '15px',
			'dropdownMenuWidth'           => '200px',
			'headerMenuItemsSpacing'      => '25px',
			'headerMenuItemsHeight'       => '60px',
			'dropdownTopOffset'           => '0px',
			'headerDropdownFontColor'     => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'headerDropdownFont'          => array(
				'size'            => array(
					'desktop' => '16px',
					'tablet'  => '16px',
					'mobile'  => '16px',
				),
				'weight' => '400',
			),
			'headerDropdownRadius'        => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '2',
						'right'  => '0',
						'bottom' => '2',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '2',
						'right'  => '0',
						'bottom' => '2',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '2',
						'right'  => '0',
						'bottom' => '2',
						'unit'   => 'px',
					)
				),
			),
			'headerDropdownShadow'        => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value(
				array(
					'enable'   => true,
					'inset'    => false,
					'h_offset' => '0px',
					'v_offset' => '10px',
					'blur'     => '20px',
					'spread'   => '0px',
					'color'    => 'rgba(41, 51, 61, 0.1)',
				)
			),
			'headerDropdownDivider'       => array(
				'width' => 1,
				'style' => 'none',
				'color' => array(
					'color' => '#dddddd',
				),
			),
			// Search
			'searchHeaderIconSize'        => '15px',
			'searchHeaderIconColor'       => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'search_close_button_color'   => array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor4)',
				),
			),
			'searchHeaderFontColor'       => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
			),
			'searchModalBackgroundColor'  => array(
				'default' => array(
					'color' => 'rgba(18, 21, 25, 0.5)',
				),
			),
			'headerSearchMargin'          => array(
				'linked' => false,
				'top'    => '0',
				'left'   => '0',
				'right'  => '0',
				'bottom' => '0',
				'unit'   => 'px',
			),
			// button
			'headerButtonFont'            => array(
				'size'            => array(
					'desktop' => '16px',
					'tablet'  => '16px',
					'mobile'  => '16px',
				),
				'line-height'            => array(
					'desktop' => '1.7',
					'tablet'  => '1.7',
					'mobile'  => '1.7',
				),
				'weight'         => '400',
				'text-transform' => 'normal',
			),
			'header_button_minwidth'      => array(
				'desktop' => '50px',
				'tablet'  => '50px',
				'mobile'  => '50px',
			),
			'headerButtonFontColor'       => array(
				'default'   => array(
					'color' => 'var(--paletteColor5)',
				),
				'hover'     => array(
					'color' => 'var(--paletteColor5)',
				)
			),
			'headerButtonFontColorOutline'       => array(
				'default' => array(
					'color' => 'var(--paletteColor3)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor5)',
				),
			),
			'headerButtonForeground'      => array(
				'default' => array(
					'color' => 'var(--paletteColor3)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor2)',
				),
			),
			'headerButtonBorder'     => array(
				'width' => 1,
				'style' => 'solid',
				'color' => array(
					'color' => 'var(--paletteColor3)',
					'hover' => 'var(--paletteColor2)',
				),
			),
			'headerButtonBorderRadius'    => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '3',
						'left'   => '3',
						'right'  => '3',
						'bottom' => '3',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '3',
						'left'   => '3',
						'right'  => '3',
						'bottom' => '3',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '3',
						'left'   => '3',
						'right'  => '3',
						'bottom' => '3',
						'unit'   => 'px',
					)
				),
			),
			'headerButtonMargin'          => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
			),
			'headerButtonPadding'         => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '10',
						'left'   => '20',
						'right'  => '20',
						'bottom' => '10',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '10',
						'left'   => '20',
						'right'  => '20',
						'bottom' => '10',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '10',
						'left'   => '20',
						'right'  => '20',
						'bottom' => '10',
						'unit'   => 'px',
					)
				),
			),
			'headerButtonBorderWidth'     => '1px',
			// Header Contact
			'icon_size'                   => '15px',
			'icon_spacing'                => '15px',
			'header_contacts_font'        => array(
				'size'            => array(
					'desktop' => '14px',
					'tablet'  => '14px',
					'mobile'  => '14px',
				),
				'line-height'            => array(
					'desktop' => '1.3',
					'tablet'  => '1.3',
					'mobile'  => '1.3',
				),
				'weight'         => '400',
				'text-transform' => 'normal',
			),
			'contacts_font_color'         => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'contacts_icon_color'         => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'contacts_icon_background'    => array(
				'default' => array(
					'color' => 'var(--paletteColor6)',
				),
				'hover'   => array(
					'color' => 'rgba(218, 222, 228, 0.7)',
				),
			),
			'contacts_margin'             => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
			),
			// randomize
			'headerRandomizeColor'    => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
			),
			'headerRandomizeIconColor'    => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'headerRandomizeIconSize'     => '20px',
			'headerRandomizeFont'         => array(
				'size'            => array(
					'desktop' => '16px',
					'tablet'  => '16px',
					'mobile'  => '16px',
				),
				'weight' => '400',
			),
			// mobilemenu.
			'mobileMenuFont'              => array(
				'size'            => array(
					'desktop' => '16px',
					'tablet'  => '16px',
					'mobile'  => '16px',
				),
				'weight' => '400',
			),
			'mobileMenuColor'             => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor4)',
				),
			),
			'mobileMenuChildSize'         => '14px',
			'mobileMenuMargin'            => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => 'auto',
					'right'  => 'auto',
					'linked' => true,
					'unit'   => 'px',
				)
			),
			'mobileMenuPadding'           => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => true,
					'top'    => '5',
					'left'   => '0',
					'bottom' => '5',
					'right'  => '0',
					'unit'   => 'px',
				)
			),
			'mobile_menu_divider'         => array(
				'width' => 1,
				'style' => 'solid',
				'color' => array(
					'color' => 'var(--paletteColor6)',
				),
			),
			// trigger
			'triggerIconColor'            => array(
				'default' => array(
					'color' => 'var(--paletteColor3)',
				),

				'hover'   => array(
					'color' => 'var(--paletteColor4)',
				),
			),
			'trigger_typo'                => array(
				'size'            => array(
					'desktop' => '17px',
					'tablet'  => '17px',
					'mobile'  => '17px',
				),
				'line-height'            => array(
					'desktop' => '1.3',
					'tablet'  => '1.3',
					'mobile'  => '1.3',
				),
				'weight'         => '500',
				'text-transform' => 'normal',
			),
			'triggerMargin'               => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => false,
					'top'    => '0',
					'left'   => '0',
					'right'  => '0',
					'bottom' => '0',
					'unit'   => 'px',
				)
			),
			'triggerIconSize'             => '20px',

			// Logo
			'headerLogoMargin'            => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => false,
						'top'    => '0',
						'left'   => '0',
						'right'  => '0',
						'bottom' => '0',
						'unit'   => 'px',
					)
				),
			),
			'siteTitleColor'              => array(
				'default' => array(
					'color' => 'var(--paletteColor2)',
				),
				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'siteTaglineColor'            => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),
			),
			'logoMaxWidth'                => array(
				'desktop' => '150px',
				'tablet'  => '150px',
				'mobile'  => '150px',
			),
			'siteTitle'                   => array(
				'size'            => array(
					'desktop' => '27px',
					'tablet'  => '27px',
					'mobile'  => '27px',
				),
				'line-height'            => array(
					'desktop' => '2.25',
					'tablet'  => '2.25',
					'mobile'  => '2.25',
				),
				'letter-spacing'            => array(
					'desktop' => '0em',
					'tablet'  => '0em',
					'mobile'  => '0em',
				),
				'weight'       => '400',
				'text-transform'  => 'none',
				'text-decoration' => 'none',
			),
			'siteTagline'                 => array(
				'size'            => array(
					'desktop' => '18px',
					'tablet'  => '18px',
					'mobile'  => '18px',
				),
				'weight'       => '500',
				'text-transform'  => 'none',
				'text-decoration' => 'none',
			),
			// Header Socials
			'headerSocialsMargin'         => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => true,
					'top'    => '0',
					'left'   => '0',
					'bottom' => '0',
					'right'  => '0',
					'unit'   => 'px',
				)
			),
			'headerSocialsIconBackground' => array(
				'default' => array(
					'color' => 'var(--paletteColor7)',
				),

				'hover'   => array(
					'color' => 'var(--paletteColor6)',
				),
			),
			'headerSocialsIconColor'      => array(
				'default' => array(
					'color' => 'var(--paletteColor1)',
				),

				'hover'   => array(
					'color' => 'var(--paletteColor3)',
				),
			),
			'socialsIconSize'             => '15px',
			'socialsIconSpacing'          => '15px',

		);

		return apply_filters( 'rishi_header_data_defaults', $defaults );
	}
	
	/**
	 * Set default value for single page settings.
	 *
	 * @return array
	 */
	public static function get_pages_defaults() {

		$defaults = array(
			'content_background' =>  array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
			),
			'boxed_content_spacing' => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '40',
						'left'   => '40',
						'right'  => '40',
						'bottom' => '40',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '15',
						'left'   => '15',
						'right'  => '15',
						'bottom' => '15',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '15',
						'left'   => '15',
						'right'  => '15',
						'bottom' => '15',
						'unit'   => 'px',
					)
				),
			),
			'content_boxed_radius'  => array(
				'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '3',
						'left'   => '3',
						'right'  => '3',
						'bottom' => '3',
						'unit'   => 'px',
					)
				),
				'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '3',
						'left'   => '3',
						'right'  => '3',
						'bottom' => '3',
						'unit'   => 'px',
					)
				),
				'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
					array(
						'linked' => true,
						'top'    => '3',
						'left'   => '3',
						'right'  => '3',
						'bottom' => '3',
						'unit'   => 'px',
					)
				),
			),

		);

		return apply_filters( 'rishi_pages_data_defaults', $defaults );
	}

	/**
	 * Set default value for single post.
	 *
	 * @return array
	 */
	public static function get_posts_default_value() {

		$posts_defaults = array(
			'ed_link_highlight'           => 'yes',
			'underlinestyle'              => 'style1',
			'post_sidebar_layout'         => 'right-sidebar',
			'blog_post_layout'            => 'default',
			'blog_post_streched_ed'       => 'no',
			'ed_post_tags'                => 'yes',
			'ed_show_post_navigation'     => 'yes',
			'ed_show_post_author'         => 'yes',
			'author_box_layout'           => 'layout-one',
			'ed_related'                  => 'yes',
			'single_related_title'        => __( 'Related Posts', 'rishi' ),
			'related_taxonomy'            => 'cat',
			'no_of_related_post'          => 3,
			'related_post_per_row'        => 3,
			'ed_related_after_comment'    => 'no',
			'ed_comment'                  => 'yes',
			'ed_comment_form_above_clist' => 'yes',
			'ed_comment_below_content'    => 'default',
			'breadcrumbs_ed_single_post'  => 'yes',
			'content_background' =>  array(
				'default' => array(
					'color' => 'var(--paletteColor5)',
				),
			),
			'boxed_content_spacing' => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => true,
					'top'    => '40',
					'left'   => '40',
					'right'  => '40',
					'bottom' => '40',
					'unit'   => 'px',
				)
			),
			'content_boxed_radius' => \Rishi\Customizer\Helpers\Basic::spacing_value(
				array(
					'linked' => true,
					'top'    => '3',
					'left'   => '3',
					'right'  => '3',
					'bottom' => '3',
					'unit'   => 'px',
				)
			)
		);

		return apply_filters( 'rishi_posts_defaults', $posts_defaults );
	}

	/**
	 * Retrieve name and icon for provided $svg
	 *
	 * @param string $svg
	 * @return array
	 */
	public static function lists_all_svgs( $svg ) {
		if ( ! $svg ) {
			return;
		}

		switch ( $svg ) {

			case 'address';
				return array(
					'name' => __( 'Phone', 'rishi' ),
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg"
                class="rishi-icon"
                width="13.788" height="20.937"
                viewBox="0 0 13.788 20.937">
                <path id="Path_26497" data-name="Path 26497" d="M29.894,961.362A6.894,6.894,0,0,0,23,968.256a10.93,10.93,0,0,0,1.277,4.6l5.617,9.447,5.617-9.447a10.929,10.929,0,0,0,1.277-4.6A6.894,6.894,0,0,0,29.894,961.362Zm0,3.83a3.064,3.064,0,1,1-3.064,3.064A3.064,3.064,0,0,1,29.894,965.192Z" transform="translate(-23 -961.362)"/></svg>',
				);
			break;

			case 'phone';
				return array(
					'name' => __( 'Phone', 'rishi' ),
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg"
                class="rishi-icon"
                width="18.823" height="19.788"
                viewBox="0 0 18.823 19.788">
                <path id="Phone" d="M15.925,19.741a8.537,8.537,0,0,1-3.747-1.51,20.942,20.942,0,0,1-3.524-3.094,51.918,51.918,0,0,1-3.759-4.28A13.13,13.13,0,0,1,2.75,6.867a6.3,6.3,0,0,1-.233-2.914,5.144,5.144,0,0,1,1.66-2.906A7.085,7.085,0,0,1,5.306.221,1.454,1.454,0,0,1,6.9.246a5.738,5.738,0,0,1,2.443,2.93,1.06,1.06,0,0,1-.117,1.072c-.283.382-.578.754-.863,1.136-.251.338-.512.671-.736,1.027a.946.946,0,0,0,.01,1.108c.564.791,1.11,1.607,1.723,2.36a30.024,30.024,0,0,0,3.672,3.8c.3.255.615.481.932.712a.892.892,0,0,0,.96.087,10.79,10.79,0,0,0,.989-.554c.443-.283.878-.574,1.314-.853a1.155,1.155,0,0,1,1.207-.024,5.876,5.876,0,0,1,2.612,2.572,1.583,1.583,0,0,1-.142,1.795,5.431,5.431,0,0,1-4.353,2.362A6.181,6.181,0,0,1,15.925,19.741Z" transform="translate(-2.441 0.006)"/></svg>',
				);
			break;

			case 'mobile';
				return array(
					'name' => __( 'Mobile', 'rishi' ),
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg"
                class="rishi-icon"
                width="12.542" height="21"
                viewBox="0 0 12.542 21">
                <path id="mobile" d="M159.292,76H150.25a1.748,1.748,0,0,0-1.75,1.75v17.5A1.748,1.748,0,0,0,150.25,97h9.042a1.748,1.748,0,0,0,1.75-1.75V77.75A1.748,1.748,0,0,0,159.292,76Zm.525,16.158h-10.15V79.967h10.15Z" transform="translate(-148.5 -76)"/></svg>',
				);
			break;

			case 'hours';
				return array(
					'name' => __( 'Hours', 'rishi' ),
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg"
                class="rishi-icon"
                width="20" height="20"
                viewBox="0 0 20 20">
                <path id="clock" d="M35,977.362a10,10,0,1,0,10,10A10,10,0,0,0,35,977.362Zm0,3.6a.8.8,0,0,1,.8.8V986.9l3.763,2.175a.8.8,0,0,1-.8,1.375l-4.075-2.35a.813.813,0,0,1-.087-.05.792.792,0,0,1-.4-.687v-5.6A.8.8,0,0,1,35,980.962Z" transform="translate(-25 -977.362)"/></svg>',
				);
			break;

			case 'fax';
				return array(
					'name' => __( 'Fax', 'rishi' ),
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg"
                class="rishi-icon"
                width="19" height="17.417"
                viewBox="0 0 19 17.417">
                <g id="Group_5861" data-name="Group 5861"><path id="Path_26501" data-name="Path 26501" d="M18.208,16H.792A.794.794,0,0,0,0,16.792v5.526a.794.794,0,0,0,.792.792H3.167V20.746H15.833v2.363h2.375A.794.794,0,0,0,19,22.317V16.792A.794.794,0,0,0,18.208,16Zm-5.542,2.771a.792.792,0,1,1,.792-.792A.792.792,0,0,1,12.667,18.771Zm2.375,0a.792.792,0,1,1,.792-.792.792.792,0,0,1-.792.792Z" transform="translate(0 -9.667)" /><path id="Path_26502" data-name="Path 26502" d="M11,32.166v3.182a.794.794,0,0,0,.792.792H20.5a.794.794,0,0,0,.792-.792V30.99H11Z" transform="translate(-6.646 -18.723)" /><path id="Path_26503" data-name="Path 26503" d="M21.292,2.771H19.708a1.191,1.191,0,0,1-1.187-1.188V0H11.792A.794.794,0,0,0,11,.792v4.75H21.292Z" transform="translate(-6.646)" /><path id="Path_26504" data-name="Path 26504" d="M32.4,1.979h1.583L32,0V1.583A.4.4,0,0,0,32.4,1.979Z" transform="translate(-19.333)" /></g></svg>',
				);
			break;

			case 'email';
				return array(
					'name' => __( 'Email', 'rishi' ),
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg"
                class="rishi-icon"
                width="20" height="12.683"
                viewBox="0 0 20 12.683">
                <path id="Path_26505" data-name="Path 26505" d="M10.463,976.362a1.465,1.465,0,0,0-.541.107l8.491,7.226a.825.825,0,0,0,1.159,0l8.5-7.233a1.469,1.469,0,0,0-.534-.1H10.463Zm-1.448,1.25a1.511,1.511,0,0,0-.015.213v9.756a1.46,1.46,0,0,0,1.463,1.463H27.537A1.46,1.46,0,0,0,29,987.581v-9.756a1.51,1.51,0,0,0-.015-.213l-8.46,7.2a2.376,2.376,0,0,1-3.064,0Z" transform="translate(-9 -976.362)"/></svg>',
				);
			break;

			case 'website';
				return array(
					'name' => __( 'Website', 'rishi' ),
					'icon' => '<svg xmlns="http://www.w3.org/2000/svg"
                class="rishi-icon"
                width="20" height="20"
                viewBox="0 0 20 20">
                <path id="Path_26506" data-name="Path 26506" d="M12,50A10,10,0,1,1,2,60,10,10,0,0,1,12,50Zm2.537,14H9.463A9.263,9.263,0,0,0,10.7,66.943c.393.576.776,1.057,1.3,1.057s.907-.481,1.3-1.057A9.263,9.263,0,0,0,14.537,64Zm-7.12,0H5.072a8.038,8.038,0,0,0,3.466,3.213A13.037,13.037,0,0,1,7.417,64Zm11.511,0H16.583a13.037,13.037,0,0,1-1.121,3.213A8.038,8.038,0,0,0,18.928,64ZM7.1,58H4.252a8.062,8.062,0,0,0,0,4H7.1a20.05,20.05,0,0,1,0-4Zm7.791,0H9.109a18.4,18.4,0,0,0,0,4h5.782A17.985,17.985,0,0,0,15,60,17.984,17.984,0,0,0,14.891,58Zm4.857,0H16.9a20,20,0,0,1,.1,2,20,20,0,0,1-.1,2h2.848a8.063,8.063,0,0,0,0-4ZM8.538,52.787A8.038,8.038,0,0,0,5.072,56H7.417A13.037,13.037,0,0,1,8.538,52.787ZM12,52c-.524,0-.907.481-1.3,1.057A9.263,9.263,0,0,0,9.463,56h5.074A9.263,9.263,0,0,0,13.3,53.057C12.907,52.481,12.524,52,12,52Zm3.462.787A13.037,13.037,0,0,1,16.583,56h2.345A8.038,8.038,0,0,0,15.462,52.787Z" transform="translate(-2 -50)" fill-rule="evenodd"/></svg>',
				);
			break;

			case 'facebook';
				return array(
					'name' => __( 'Facebook', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24">
					<path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/>
				</svg>',
				);
			break;

			case 'twitter';
				return array(
					'name' => __( 'Twitter', 'rishi' ),
					'icon' => '<svg
						width="20px"
						height="20px"
						class="rishi-icon"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_232_4313)">
                    	<path d="M0.13108 0.139647C3.99795 5.64756 7.72555 10.9678 7.73374 10.994C7.73784 11.0114 7.58218 11.2122 7.38556 11.4392C7.18484 11.6661 6.12391 12.8925 5.01792 14.1626C2.65028 16.8903 2.3021 17.2919 0.991296 18.7976C0.446493 19.4261 0 19.9498 0 19.9629C0 19.976 0.385049 19.9891 0.856119 19.9891H1.71633L2.74859 18.8019C5.53815 15.5941 8.27035 12.4473 8.39734 12.2946L8.54071 12.1244L11.3016 16.0567L14.0584 19.9891H17.0241C18.6544 19.9891 19.9898 19.976 19.9898 19.9629C19.9898 19.9367 19.2033 18.8107 14.554 12.1855C13.0835 10.0905 11.8792 8.35787 11.8792 8.33605C11.8792 8.30986 13.4931 6.43316 15.4634 4.16802C17.4337 1.89851 19.0476 0.0349009 19.0476 0.0218076C19.0476 0.00871429 18.6544 -1.45696e-05 18.1792 0.00434986L17.3067 0.00871429L14.3574 3.40424C12.7353 5.27222 11.3303 6.89578 11.236 7.01362L11.0599 7.21875L10.9903 7.11401C10.9534 7.05727 10.0154 5.72175 8.90937 4.14619C7.80338 2.575 6.69739 0.99944 6.45161 0.641557L6.00512 -1.45696e-05H3.01895H0.0327701L0.13108 0.139647ZM8.88889 6.42443C16.3973 17.1173 17.3477 18.4746 17.3804 18.5314C17.4091 18.5837 17.2002 18.5925 16.0492 18.5925L14.6851 18.5881L13.9027 17.4664C13.0425 16.2357 9.3149 10.9242 5.23502 5.1151C3.79724 3.06818 2.62161 1.38351 2.62161 1.37042C2.62161 1.36169 3.23605 1.35732 3.98157 1.36169L5.34153 1.37478L8.88889 6.42443Z" />
						</g>
						<defs>
						<clipPath id="clip0_232_4313">
						<rect width="20" height="20" fill="white"/>
						</clipPath>
                    </defs>
            	</svg>',
				);
			break;

			case 'instagram';
				return array(
					'name' => __( 'Instagram', 'rishi' ),
					'icon' => '<svg
                        class="rishi-icon"
                        width="20"
                        height="20"
                        viewBox="0 0 511 511.9">
                        <path d="m510.949219 150.5c-1.199219-27.199219-5.597657-45.898438-11.898438-62.101562-6.5-17.199219-16.5-32.597657-29.601562-45.398438-12.800781-13-28.300781-23.101562-45.300781-29.5-16.296876-6.300781-34.898438-10.699219-62.097657-11.898438-27.402343-1.300781-36.101562-1.601562-105.601562-1.601562s-78.199219.300781-105.5 1.5c-27.199219 1.199219-45.898438 5.601562-62.097657 11.898438-17.203124 6.5-32.601562 16.5-45.402343 29.601562-13 12.800781-23.097657 28.300781-29.5 45.300781-6.300781 16.300781-10.699219 34.898438-11.898438 62.097657-1.300781 27.402343-1.601562 36.101562-1.601562 105.601562s.300781 78.199219 1.5 105.5c1.199219 27.199219 5.601562 45.898438 11.902343 62.101562 6.5 17.199219 16.597657 32.597657 29.597657 45.398438 12.800781 13 28.300781 23.101562 45.300781 29.5 16.300781 6.300781 34.898438 10.699219 62.101562 11.898438 27.296876 1.203124 36 1.5 105.5 1.5s78.199219-.296876 105.5-1.5c27.199219-1.199219 45.898438-5.597657 62.097657-11.898438 34.402343-13.300781 61.601562-40.5 74.902343-74.898438 6.296876-16.300781 10.699219-34.902343 11.898438-62.101562 1.199219-27.300781 1.5-36 1.5-105.5s-.101562-78.199219-1.300781-105.5zm-46.097657 209c-1.101562 25-5.300781 38.5-8.800781 47.5-8.601562 22.300781-26.300781 40-48.601562 48.601562-9 3.5-22.597657 7.699219-47.5 8.796876-27 1.203124-35.097657 1.5-103.398438 1.5s-76.5-.296876-103.402343-1.5c-25-1.097657-38.5-5.296876-47.5-8.796876-11.097657-4.101562-21.199219-10.601562-29.398438-19.101562-8.5-8.300781-15-18.300781-19.101562-29.398438-3.5-9-7.699219-22.601562-8.796876-47.5-1.203124-27-1.5-35.101562-1.5-103.402343s.296876-76.5 1.5-103.398438c1.097657-25 5.296876-38.5 8.796876-47.5 4.101562-11.101562 10.601562-21.199219 19.203124-29.402343 8.296876-8.5 18.296876-15 29.398438-19.097657 9-3.5 22.601562-7.699219 47.5-8.800781 27-1.199219 35.101562-1.5 103.398438-1.5 68.402343 0 76.5.300781 103.402343 1.5 25 1.101562 38.5 5.300781 47.5 8.800781 11.097657 4.097657 21.199219 10.597657 29.398438 19.097657 8.5 8.300781 15 18.300781 19.101562 29.402343 3.5 9 7.699219 22.597657 8.800781 47.5 1.199219 27 1.5 35.097657 1.5 103.398438s-.300781 76.300781-1.5 103.300781zm0 0"/><path d="m256.449219 124.5c-72.597657 0-131.5 58.898438-131.5 131.5s58.902343 131.5 131.5 131.5c72.601562 0 131.5-58.898438 131.5-131.5s-58.898438-131.5-131.5-131.5zm0 216.800781c-47.097657 0-85.300781-38.199219-85.300781-85.300781s38.203124-85.300781 85.300781-85.300781c47.101562 0 85.300781 38.199219 85.300781 85.300781s-38.199219 85.300781-85.300781 85.300781zm0 0"/><path d="m423.851562 119.300781c0 16.953125-13.746093 30.699219-30.703124 30.699219-16.953126 0-30.699219-13.746094-30.699219-30.699219 0-16.957031 13.746093-30.699219 30.699219-30.699219 16.957031 0 30.703124 13.742188 30.703124 30.699219zm0 0"/>
				</svg>',
				);
			break;

			case 'pinterest';
				return array(
					'name' => __( 'Pinterest', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M10,0C4.5,0,0,4.5,0,10c0,4.1,2.5,7.6,6,9.2c0-0.7,0-1.5,0.2-2.3c0.2-0.8,1.3-5.4,1.3-5.4s-0.3-0.6-0.3-1.6c0-1.5,0.9-2.6,1.9-2.6c0.9,0,1.3,0.7,1.3,1.5c0,0.9-0.6,2.3-0.9,3.5c-0.3,1.1,0.5,1.9,1.6,1.9c1.9,0,3.2-2.4,3.2-5.3c0-2.2-1.5-3.8-4.2-3.8c-3,0-4.9,2.3-4.9,4.8c0,0.9,0.3,1.5,0.7,2C6,12,6.1,12.1,6,12.4c0,0.2-0.2,0.6-0.2,0.8c-0.1,0.3-0.3,0.3-0.5,0.3c-1.4-0.6-2-2.1-2-3.8c0-2.8,2.4-6.2,7.1-6.2c3.8,0,6.3,2.8,6.3,5.7c0,3.9-2.2,6.9-5.4,6.9c-1.1,0-2.1-0.6-2.4-1.2c0,0-0.6,2.3-0.7,2.7c-0.2,0.8-0.6,1.5-1,2.1C8.1,19.9,9,20,10,20c5.5,0,10-4.5,10-10C20,4.5,15.5,0,10,0z"/>
				</svg>',
				);
			break;

			case 'dribble';
				return array(
					'name' => __( 'Dribbble', 'rishi' ),
					'icon' => '<svg
				class="rishi-icon"
				width="20"
				height="20"
				viewBox="0 0 20 20">
					<path d="M10,0C4.5,0,0,4.5,0,10c0,5.5,4.5,10,10,10c5.5,0,10-4.5,10-10C20,4.5,15.5,0,10,0 M16.1,5.2c1,1.2,1.6,2.8,1.7,4.4c-1.1-0.2-2.2-0.4-3.2-0.4v0h0c-0.8,0-1.6,0.1-2.3,0.2c-0.2-0.4-0.3-0.8-0.5-1.2C13.4,7.6,14.9,6.6,16.1,5.2 M10,2.2c1.8,0,3.5,0.6,4.9,1.7c-1,1.2-2.4,2.1-3.8,2.7c-1-2-2-3.4-2.7-4.3C8.9,2.3,9.4,2.2,10,2.2 M6.6,3c0.5,0.6,1.6,2,2.8,4.2C7,8,4.6,8.1,3.2,8.1c0,0-0.1,0-0.1,0h0c-0.2,0-0.4,0-0.6,0C3,5.9,4.5,4,6.6,3 M2.2,10c0,0,0-0.1,0-0.1c0.2,0,0.5,0,0.9,0h0c1.6,0,4.3-0.1,7.1-1c0.2,0.3,0.3,0.7,0.4,1c-1.9,0.6-3.3,1.6-4.4,2.6c-1,0.9-1.7,1.9-2.2,2.5C2.9,13.7,2.2,11.9,2.2,10 M10,17.8c-1.7,0-3.3-0.6-4.6-1.5c0.3-0.5,0.9-1.3,1.8-2.2c1-0.9,2.3-1.9,4.1-2.5c0.6,1.7,1.1,3.6,1.5,5.7C11.9,17.6,11,17.8,10,17.8M14.4,16.4c-0.4-1.9-0.9-3.7-1.4-5.2c0.5-0.1,1-0.1,1.6-0.1h0h0h0c0.9,0,2,0.1,3.1,0.4C17.3,13.5,16.1,15.3,14.4,16.4"/>
				</svg>',
				);
			break;

			case 'behance';
				return array(
					'name' => __( 'Behance', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 511.958 511.958">
					<path d="M210.624,240.619c10.624-5.344,18.656-11.296,24.16-17.728c9.792-11.584,14.624-26.944,14.624-45.984
					c0-18.528-4.832-34.368-14.496-47.648c-16.128-21.632-43.424-32.704-82.016-33.28H0v312.096h142.56
					c16.064,0,30.944-1.376,44.704-4.192c13.76-2.848,25.664-8.064,35.744-15.68c8.96-6.624,16.448-14.848,22.4-24.544
					c9.408-14.656,14.112-31.264,14.112-49.76c0-17.92-4.128-33.184-12.32-45.728C238.912,255.627,226.752,246.443,210.624,240.619z
					M63.072,150.187h68.864c15.136,0,27.616,1.632,37.408,4.864c11.328,4.704,16.992,14.272,16.992,28.864
					c0,13.088-4.32,22.24-12.864,27.392c-8.608,5.152-19.776,7.744-33.472,7.744H63.072V150.187z M171.968,348.427
					c-7.616,3.68-18.336,5.504-32.064,5.504H63.072v-83.232h77.888c13.568,0.096,24.128,1.888,31.68,5.248
					c13.44,6.08,20.128,17.216,20.128,33.504C192.768,328.651,185.856,341.579,171.968,348.427z"/>
					<rect x="327.168" y="110.539" width="135.584" height="38.848"/>
					<path d="M509.856,263.851c-2.816-18.08-9.024-33.984-18.688-47.712c-10.592-15.552-24.032-26.944-40.384-34.144
					c-16.288-7.232-34.624-10.848-55.04-10.816c-34.272,0-62.112,10.72-83.648,32c-21.472,21.344-32.224,52.032-32.224,92.032
					c0,42.656,11.872,73.472,35.744,92.384c23.776,18.944,51.232,28.384,82.4,28.384c37.728,0,67.072-11.232,88.032-33.632
					c13.408-14.144,20.992-28.064,22.656-41.728H446.24c-3.616,6.752-7.808,12.032-12.608,15.872
					c-8.704,7.04-20.032,10.56-33.92,10.56c-13.216,0-24.416-2.912-33.76-8.704c-15.424-9.28-23.488-25.536-24.512-48.672h170.464
					C512.16,289.739,511.52,274.411,509.856,263.851z M342.976,269.835c2.24-15.008,7.68-26.912,16.32-35.712
					c8.64-8.768,20.864-13.184,36.512-13.216c14.432,0,26.496,4.128,36.32,12.416c9.696,8.352,15.168,20.48,16.288,36.512H342.976z"/>
				</svg>',
				);
			break;

			case 'unsplash';
				return array(
					'name' => __( 'Unsplash', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M6.2 5.6V0h7.5v5.6H6.2zm7.6 3.2H20V20H0V8.8h6.2v5.6h7.5V8.8z"/>
				</svg>',
				);
			break;

			case 'five-hundred-px';
				return array(
					'name' => __( 'Five Hundred PX', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M17.7 17.3c-.9.9-1.9 1.6-3 2-1.1.5-2.3.7-3.5.7-1.2 0-2.4-.2-3.5-.7-1.1-.5-2.1-1.1-2.9-2-.8-.8-1.5-1.8-2-2.9-.3-.8-.5-1.5-.6-2.1 0-.2.1-.3.5-.4.4-.1.6 0 .6.2.1.7.3 1.3.5 1.8.4.9.9 1.8 1.7 2.5.7.7 1.6 1.3 2.5 1.7 1 .4 2 .6 3.1.6s2.1-.2 3.1-.6c1-.4 1.8-1 2.5-1.7l.1-.1c.1-.1.2-.1.3-.1.1 0 .2.1.4.2.3.5.3.7.2.9zm-5.3-6.9l-.7.7.7.7c.2.2.1.3-.1.5-.1.1-.2.2-.4.2-.1 0-.1 0-.2-.1l-.7-.7-.7.7s-.1.1-.2.1-.2-.1-.3-.2c-.1-.1-.2-.2-.2-.3 0-.1 0-.1.1-.2l.7-.7-.7-.7c-.1-.1-.1-.3.2-.5.1-.1.2-.2.3-.2 0 0 .1 0 .1.1l.7.7.7-.7c.1-.1.3-.1.5.1.3.2.4.4.2.5zm5.3.6c0 .9-.2 1.7-.5 2.5s-.8 1.5-1.4 2.1c-.6.6-1.3 1.1-2.1 1.4-.8.3-1.6.5-2.5.5-.9 0-1.7-.2-2.5-.5s-1.5-.8-2.1-1.4c-.6-.6-1.1-1.3-1.4-2.1l-.2-.4c-.1-.2.1-.4.5-.5.4-.1.6-.1.7.1.3.7.6 1.4 1.1 1.9v-3.8c0-1 .4-1.9 1.1-2.6.8-.8 1.7-1.1 2.8-1.1 1.1 0 2 .4 2.8 1.1.8.8 1.2 1.7 1.2 2.8 0 1.1-.4 2-1.2 2.8-.8.8-1.7 1.2-2.8 1.2-.4 0-.8-.1-1.2-.2-.2-.1-.3-.3-.1-.7.1-.4.3-.5.5-.5h.2c.1 0 .2 0 .4.1s.3 0 .3 0c.8 0 1.4-.3 2-.8.5-.5.8-1.2.8-1.9 0-.8-.3-1.4-.8-1.9s-1.2-.8-2-.8-1.5.3-2 .9c-.7.6-.9 1.2-.9 1.8v4.6c.8.5 1.7.7 2.7.7.7 0 1.4-.1 2.1-.4.7-.3 1.2-.7 1.7-1.2s.9-1.1 1.2-1.7c.3-.7.4-1.3.4-2 0-1.5-.5-2.7-1.6-3.8-1-1-2.3-1.6-3.8-1.6s-2.8.5-3.8 1.6c-.4.4-.7.8-.8 1l-.2.2s-.1.1-.2.1h-.4c-.2 0-.3-.1-.4-.2S5 8.1 5 8V.4c0-.1 0-.2.1-.3s.2-.1.4-.1h9.8c.2 0 .3.2.3.6s-.1.6-.3.6H6.2v5.4c.3-.3.7-.6 1.2-.9.4-.3.8-.6 1.2-.7.8-.3 1.7-.5 2.6-.5.9 0 1.7.2 2.5.5s1.5.8 2.1 1.4c.6.6 1.1 1.3 1.4 2.1.3.8.5 1.7.5 2.5zm-.4-6.4c.1.1.1.1.1.2s0 .1-.1.2l-.2.2c-.2.2-.3.3-.4.3-.1 0-.1 0-.2-.1-.8-.7-1.6-1.2-2.3-1.5-1-.4-2-.6-3.1-.6-1 0-2 .2-2.9.5-.1.1-.3 0-.4-.4-.1-.2-.1-.3-.1-.4 0-.1.1-.2.2-.2 1-.4 2.1-.6 3.3-.6 1.2 0 2.4.2 3.5.7 1 .4 1.9 1 2.6 1.7z"/>
				</svg>',
				);
			break;

			case 'linkedin';
				return array(
					'name' => __( 'LinkedIn', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24">
					<path d="m23.994 24v-.001h.006v-8.802c0-4.306-.927-7.623-5.961-7.623-2.42 0-4.044 1.328-4.707 2.587h-.07v-2.185h-4.773v16.023h4.97v-7.934c0-2.089.396-4.109 2.983-4.109 2.549 0 2.587 2.384 2.587 4.243v7.801z"/><path d="m.396 7.977h4.976v16.023h-4.976z"/><path d="m2.882 0c-1.591 0-2.882 1.291-2.882 2.882s1.291 2.909 2.882 2.909 2.882-1.318 2.882-2.909c-.001-1.591-1.292-2.882-2.882-2.882z"/>
				</svg>',
				);
			break;

			case 'WordPress';
				return array(
					'name' => __( 'WordPress', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 96.682 96.682">
					<path d="M48.343,0C21.686,0,0.002,21.683,0.002,48.339c0,26.657,21.684,48.343,48.341,48.343c26.653,0,48.337-21.686,48.337-48.343
					C96.68,21.686,74.996,0,48.343,0z M5.55,48.339c0-6.203,1.332-12.094,3.706-17.413l20.412,55.925
					C15.394,79.916,5.55,65.279,5.55,48.339z M48.343,91.135c-4.201,0-8.255-0.62-12.09-1.744l12.838-37.307l13.157,36.033
					c0.084,0.211,0.189,0.406,0.304,0.59C58.105,90.273,53.324,91.135,48.343,91.135z M54.238,28.277
					c2.576-0.136,4.896-0.409,4.896-0.409c2.307-0.272,2.037-3.659-0.271-3.523c0,0-6.932,0.543-11.405,0.543
					c-4.203,0-11.272-0.543-11.272-0.543c-2.305-0.136-2.573,3.39-0.27,3.523c0,0,2.183,0.272,4.486,0.409l6.667,18.266L37.706,74.63
					L22.125,28.279c2.579-0.136,4.898-0.408,4.898-0.408c2.303-0.272,2.034-3.661-0.275-3.523c0,0-6.929,0.542-11.405,0.542
					c-0.806,0-1.749-0.021-2.753-0.052C20.238,13.219,33.392,5.549,48.343,5.549c11.142,0,21.283,4.26,28.896,11.232
					c-0.187-0.009-0.364-0.033-0.556-0.033c-4.202,0-7.187,3.661-7.187,7.595c0,3.525,2.031,6.51,4.203,10.034
					c1.629,2.853,3.527,6.514,3.527,11.803c0,3.663-1.406,7.914-3.256,13.833l-4.268,14.263L54.238,28.277z M69.854,85.326l13.07-37.79
					c2.445-6.104,3.254-10.986,3.254-15.328c0-1.573-0.104-3.038-0.288-4.4c3.345,6.095,5.245,13.091,5.243,20.532
					C91.133,64.126,82.574,77.908,69.854,85.326z"/>
				</svg>',
				);
			break;

			case 'parler';
				return array(
					'name' => __( 'Parler', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M11.7,16.7h-5V15c0-0.9,0.7-1.6,1.6-1.6h3.4c2.8,0,5-2.2,5-5s-2.2-5-5-5h0l-1.1,0H0C0,1.5,1.5,0,3.3,0h7.3l1.1,0C16.3,0,20,3.8,20,8.4S16.3,16.7,11.7,16.7z M3.3,20C1.5,20,0,18.5,0,16.7V9.9c0-1.8,1.4-3.2,3.2-3.2h8.4c0.9,0,1.7,0.7,1.7,1.7c0,0.9-0.7,1.7-1.7,1.7H5c-0.9,0-1.6,0.7-1.6,1.6V20z"/>
				</svg>',
				);
			break;

			case 'medium';
				return array(
					'name' => __( 'Medium', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M2.4,5.3c0-0.2-0.1-0.5-0.3-0.7L0.3,2.4V2.1H6l4.5,9.8l3.9-9.8H20v0.3l-1.6,1.5c-0.1,0.1-0.2,0.3-0.2,0.4v11.2c0,0.2,0,0.3,0.2,0.4l1.6,1.5v0.3h-7.8v-0.3l1.6-1.6c0.2-0.2,0.2-0.2,0.2-0.4V6.5L9.4,17.9H8.8L3.6,6.5v7.6c0,0.3,0.1,0.6,0.3,0.9L6,17.6v0.3H0v-0.3L2.1,15c0.2-0.2,0.3-0.6,0.3-0.9V5.3z"/>
				</svg>',
				);
			break;

			case 'slack';
				return array(
					'name' => __( 'Slack', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M7.4,0C6.2,0,5.2,1,5.2,2.2s1,2.2,2.2,2.2h2.2V2.2C9.6,1,8.6,0,7.4,0zM12.6,0c-1.2,0-2.2,1-2.2,2.2v5.2c0,1.2,1,2.2,2.2,2.2s2.2-1,2.2-2.2V2.2C14.8,1,13.8,0,12.6,0z M2.2,5.2C1,5.2,0,6.2,0,7.4s1,2.2,2.2,2.2h5.2c1.2,0,2.2-1,2.2-2.2s-1-2.2-2.2-2.2H2.2zM17.8,5.2c-1.2,0-2.2,1-2.2,2.2v2.2h2.2c1.2,0,2.2-1,2.2-2.2S19,5.2,17.8,5.2z M2.2,10.4c-1.2,0-2.2,1-2.2,2.2s1,2.2,2.2,2.2s2.2-1,2.2-2.2v-2.2H2.2zM7.4,10.4c-1.2,0-2.2,1-2.2,2.2v5.2c0,1.2,1,2.2,2.2,2.2s2.2-1,2.2-2.2v-5.2C9.6,11.4,8.6,10.4,7.4,10.4z M12.6,10.4c-1.2,0-2.2,1-2.2,2.2s1,2.2,2.2,2.2h5.2c1.2,0,2.2-1,2.2-2.2s-1-2.2-2.2-2.2H12.6zM10.4,15.7v2.2c0,1.2,1,2.2,2.2,2.2s2.2-1,2.2-2.2c0-1.2-1-2.2-2.2-2.2H10.4z"/>
				</svg>',
				);
			break;

			case 'codepen';
				return array(
					'name' => __( 'CodePen', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M10,0L0,6.4v7.3L10,20l10-6.4V6.4L10,0z M10,12l-2.8-2L10,8.1l2.8,1.9L10,12z M11,6.5V2.8l6.4,4.1l-2.9,2L11,6.5z M9,6.5L5.5,8.9l-2.9-2L9,2.8V6.5z M3.9,10l-1.9,1.3V8.7L3.9,10z M5.5,11.2L9,13.6v3.5l-6.4-4.1L5.5,11.2z M11,13.6l3.5-2.5l2.8,1.9L11,17.2V13.6z M16.1,10l1.9-1.4v2.7L16.1,10z"/>
				</svg>',
				);
			break;

			case 'reddit';
				return array(
					'name' => __( 'Reddit', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M11.7,0.9c-0.9,0-2,0.7-2.1,3.9c0.1,0,0.3,0,0.4,0c0.2,0,0.3,0,0.5,0c0.1-1.9,0.6-3.1,1.3-3.1c0.3,0,0.5,0.2,0.8,0.5c0.4,0.4,0.9,0.9,1.8,1.1c0-0.1,0-0.2,0-0.4c0-0.2,0-0.4,0.1-0.5c-0.6-0.2-0.9-0.5-1.2-0.8C12.8,1.3,12.4,0.9,11.7,0.9z M16.9,1.3c-1,0-1.7,0.8-1.7,1.7s0.8,1.7,1.7,1.7s1.7-0.8,1.7-1.7S17.9,1.3,16.9,1.3z M10,5.7c-5.3,0-9.5,2.7-9.5,6.5s4.3,6.9,9.5,6.9s9.5-3.1,9.5-6.9S15.3,5.7,10,5.7z M2.4,6.1c-0.6,0-1.2,0.3-1.7,0.7C0,7.5-0.2,8.6,0.2,9.5C0.9,8.2,2,7.1,3.5,6.3C3.1,6.2,2.8,6.1,2.4,6.1z M17.6,6.1c-0.4,0-0.7,0.1-1.1,0.3c1.5,0.8,2.6,1.9,3.2,3.2c0.4-0.9,0.3-2-0.5-2.7C18.8,6.3,18.2,6.1,17.6,6.1z M6.5,9.6c0.7,0,1.3,0.6,1.3,1.3s-0.6,1.3-1.3,1.3s-1.3-0.6-1.3-1.3S5.8,9.6,6.5,9.6z M13.5,9.6c0.7,0,1.3,0.6,1.3,1.3s-0.6,1.3-1.3,1.3s-1.3-0.6-1.3-1.3S12.8,9.6,13.5,9.6z M6.1,14.3c0.1,0,0.2,0.1,0.3,0.2c0,0.1,1.1,1.4,3.6,1.4c2.6,0,3.6-1.4,3.6-1.4c0.1-0.2,0.4-0.2,0.6-0.1c0.2,0.1,0.2,0.4,0.1,0.6c-0.1,0.1-1.3,1.8-4.3,1.8c-3,0-4.2-1.7-4.3-1.8c-0.1-0.2-0.1-0.5,0.1-0.6C5.9,14.4,6,14.3,6.1,14.3z"/>
				</svg>',
				);
			break;

			case 'twitch';
				return array(
					'name' => __( 'Twitch', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20px"
                    viewBox="0 0 20 20">
					<path d="M1.5,0L0,4.1v12.8h4.6V20h2.1l3.8-3.1h4.1l5.4-5.8V0H1.5zM3.1,1.5h15.4v8.8l-3.3,3.5H9.5l-3.4,2.9v-2.9H3.1V1.5z M7.7,4.6v6.2h1.5V4.6H7.7z M12.3,4.6v6.2h1.5V4.6H12.3z"/>
				</svg>',
				);
			break;

			case 'tiktok';
				return array(
					'name' => __( 'TikTok', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M18.2 4.5c-2.3-.2-4.1-1.9-4.4-4.2V0h-3.4v13.8c0 1.4-1.2 2.6-2.8 2.6-1.4 0-2.6-1.1-2.6-2.6s1.1-2.6 2.6-2.6h.2l.5.1V7.5h-.7c-3.4 0-6.2 2.8-6.2 6.2S4.2 20 7.7 20s6.2-2.8 6.2-6.2v-7c1.1 1.1 2.4 1.6 3.9 1.6h.8V4.6l-.4-.1z"/>
				</svg>',
				);
			break;

			case 'snapchat';
				return array(
					'name' => __( 'Snapchat', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M10,0.5c-6,0-6,6-6,6v1c0,0,0,0-0.1,0C3.6,7.5,2,7.6,2,8.9c0,1.5,1.7,1.6,2,1.6c0,0,0,0,0,0c0,1-1.7,2.2-2.7,2.4C0.3,13.3,0,14,0,14.5c0,0.3,0.1,0.5,0.1,0.6c0.4,0.9,1.5,1.3,2.6,1.3c0,1.4,1.1,2,1.8,2c0.8,0,1.6-0.4,1.6-0.4c0,0,1.3,1.4,3.9,1.4s3.9-1.4,3.9-1.4c0,0,0.8,0.4,1.6,0.4c0.7,0,1.7-0.6,1.8-2c1.1,0,2.2-0.5,2.6-1.3c0-0.1,0.1-0.3,0.1-0.6c0-0.5-0.3-1.2-1.3-1.6c-1.1-0.3-2.7-1.4-2.7-2.4c0,0,0,0,0,0c0.3,0,2-0.1,2-1.6c0-1.3-1.6-1.4-1.9-1.4c0,0-0.1,0-0.1,0v-1C16,6.5,16,0.5,10,0.5L10,0.5z"/>
				</svg>',
				);
			break;

			case 'spotify';
				return array(
					'name' => __( 'Spotify', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20px"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M10,0C4.5,0,0,4.5,0,10s4.5,10,10,10s10-4.5,10-10S15.5,0,10,0z M14.2,14.5c-0.1,0.2-0.3,0.3-0.5,0.3c-0.1,0-0.2,0-0.4-0.1c-1.1-0.7-2.9-1.2-4.4-1.2c-1.6,0-2.8,0.4-2.8,0.4c-0.3,0.1-0.7-0.1-0.8-0.4c-0.1-0.3,0.1-0.7,0.4-0.8c0.1,0,1.4-0.5,3.2-0.5c1.5,0,3.6,0.4,5.1,1.4C14.4,13.8,14.4,14.2,14.2,14.5z M15.5,11.8c-0.1,0.2-0.4,0.4-0.6,0.4c-0.1,0-0.3,0-0.4-0.1c-1.9-1.2-4-1.5-5.7-1.5c-1.9,0-3.5,0.4-3.5,0.4c-0.4,0.1-0.8-0.1-0.9-0.5c-0.1-0.4,0.1-0.8,0.5-0.9c0.1,0,1.7-0.4,3.8-0.4c1.9,0,4.4,0.3,6.6,1.7C15.6,11,15.8,11.5,15.5,11.8z M16.8,8.7c-0.2,0.3-0.5,0.4-0.8,0.4c-0.1,0-0.3,0-0.4-0.1c-2.3-1.3-5-1.6-6.9-1.6c0,0,0,0,0,0c-2.3,0-4.1,0.4-4.1,0.4c-0.5,0.1-0.9-0.2-1-0.6c-0.1-0.5,0.2-0.9,0.6-1c0.1,0,2-0.5,4.5-0.5c0,0,0,0,0,0c2.1,0,5.2,0.3,7.8,1.9C16.9,7.8,17.1,8.3,16.8,8.7z"/>
				</svg>',
				);
			break;

			case 'soundcloud';
				return array(
					'name' => __( 'SoundCloud', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20px"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M20 12.7c0 1.5-1.2 2.7-2.7 2.7h-6c-.4 0-.7-.3-.7-.7V5.3c0-.4.3-.7.7-.7h.7c3.3 0 6 2.7 4.7 5.3h.7c1.4.1 2.6 1.3 2.6 2.8zM.7 9.9c-.4 0-.7.3-.7.7v4.1c0 .4.3.7.7.7.4 0 .7-.3.7-.7v-4.1c-.1-.4-.4-.7-.7-.7zM6 5.3c-.4 0-.7.3-.7.7v8.7c0 .4.3.7.7.7s.7-.3.7-.7V6c0-.4-.3-.7-.7-.7zm2.7 2c-.4 0-.7.3-.7.7v6.7c0 .4.3.7.7.7.4 0 .7-.3.7-.7V8c-.1-.4-.4-.7-.7-.7zM3.3 8c-.3 0-.6.3-.6.7v6c0 .4.3.7.7.7.3-.1.6-.4.6-.7v-6c0-.4-.3-.7-.7-.7z"/>
				</svg>',
				);
			break;

			case 'apple_podcast';
				return array(
					'name' => __( 'Apple Podcasts', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20px"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M10 0C5.1 0 1.1 4 1.1 8.9c0 2.9 1.4 5.5 3.6 7.1.3.2.5.4.8.5.3.2.8.1 1-.2.2-.3.1-.8-.2-1-.2-.1-.5-.3-.7-.5-1.8-1.4-3-3.6-3-6 0-4.2 3.4-7.5 7.5-7.5s7.5 3.4 7.5 7.5c0 2.5-1.2 4.7-3 6-.2.2-.5.3-.7.5-.3.2-.5.6-.3 1 .2.3.6.5 1 .3.3-.2.6-.4.8-.6 2.2-1.6 3.6-4.2 3.6-7.2C18.9 4 14.9 0 10 0zm0 2.8c-3.4 0-6.1 2.7-6.1 6.1 0 1.7.7 3.2 1.8 4.3.3.3.7.3 1 0s.3-.7 0-1c-.9-.9-1.4-2-1.4-3.3 0-2.6 2.1-4.7 4.7-4.7s4.7 2.1 4.7 4.7c0 1.3-.5 2.5-1.4 3.3-.3.3-.3.7 0 1 .3.3.7.3 1 0 1.1-1.1 1.8-2.6 1.8-4.3 0-3.3-2.7-6.1-6.1-6.1zm0 3.8C8.7 6.6 7.6 7.7 7.6 9s1.1 2.4 2.4 2.4 2.4-1.1 2.4-2.4-1.1-2.4-2.4-2.4zm0 5.6c-1.3 0-2.4 1.1-2.4 2.4v.5l.9 3.7c.2.7.8 1.2 1.5 1.2s1.3-.5 1.4-1.1l.9-3.7v-.1-.4c.1-1.4-1-2.5-2.3-2.5z"/>
				</svg>',
				);
			break;

			case 'patreon';
				return array(
					'name' => __( 'Patreon', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M20,7.6c0,4-3.2,7.2-7.2,7.2c-4,0-7.2-3.2-7.2-7.2c0-4,3.2-7.2,7.2-7.2C16.8,0.4,20,3.6,20,7.6z M0,19.6h3.5V0.4H0V19.6z"/>
				</svg>',
				);
			break;

			case 'alignable';
				return array(
					'name' => __( 'Alignable', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M19.5 6.7C18.1 2.8 14.3 0 9.9 0c-.7 0-1.4.1-2.1.3L6.6.6c.1.1.1.3.2.4.2.8.5 1.6.7 2.4.2.4.4.9.5 1.4.5 1.5 1.1 2.8 1.7 3.8.2.4.5.8.8 1.1.4.4.8.7 1.3.7.7 0 1.3-.6 1.9-1.4.5 1 1.1 2.3 1.5 3.5-.9.8-2 1.3-3.3 1.3-1 0-1.8-.3-2.6-.8-.3-.2-.7-.5-1-.8-1-.9-1.7-2.2-2.4-3.6-.3-.5-.5-1-.7-1.6C4.5 5.5 4 3.9 3.6 2.3c-.4.2-.7.6-1 .9C1 5 0 7.4 0 10c0 2.3.7 4.4 2 6.1.2.4.6.8.9 1.1.3-1.1.7-2.1 1-3.1.4-1.3.8-2.6 1.3-3.9.7 1.3 1.5 2.5 2.5 3.3-.2.6-.4 1.2-.6 1.7-.5 1.3-.9 2.7-1.4 4 .4.1.8.3 1.2.4 1 .3 2 .4 3 .4 2.7 0 5.2-1.1 7-2.8.4-.4.7-.7 1-1.1-.1-.3-.2-.7-.3-1-.3-.7-.5-1.5-.8-2.3-.2-.5-.3-.9-.5-1.4-.5-1.5-1.1-2.8-1.7-3.8-.2-.4-.5-.8-.8-1.1l-.3-.3c-.3-.3-.7-.4-1-.4-.7 0-1.3.6-1.9 1.4-.6-1-1.2-2.3-1.6-3.5.1-.1.2-.2.4-.3.9-.6 1.9-1 3-1 1 0 1.8.3 2.6.8.3.2.7.5 1 .8.9.9 1.7 2.2 2.3 3.5.3.5.5 1.1.7 1.6.3.7.6 1.4.8 2.1.2-.4.2-.8.2-1.2 0-1.1-.2-2.2-.5-3.3z"/>
				</svg>',
				);
			break;

			case 'skype';
				return array(
					'name' => __( 'Skype', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M5.7 0C2.6 0 0 2.5 0 5.6c0 1 .2 1.9.7 2.7-.1.6-.2 1.2-.2 1.8 0 5.2 4.3 9.4 9.6 9.4.5 0 1.1 0 1.6-.1.8.4 1.7.6 2.6.6 3.1 0 5.7-2.5 5.7-5.6 0-.8-.2-1.6-.5-2.4.1-.6.2-1.2.2-1.9 0-5.2-4.3-9.4-9.6-9.4-.5 0-1 0-1.5.1C7.7.3 6.7 0 5.7 0zM10 3.8c.8 0 1.5.1 2.1.3.6.2 1.1.4 1.5.7.4.3.7.6.9 1 .2.3.3.7.3 1 0 .3-.1.6-.4.9s-.5.3-.8.3c-.3 0-.6-.1-.8-.2-.2-.2-.4-.4-.6-.7-.2-.4-.5-.8-.8-1-.3-.2-.8-.3-1.5-.3s-1.2.1-1.6.4c-.4.2-.6.5-.6.8 0 .2.1.4.2.5.1.2.3.3.5.4.3.1.5.2.8.3.3.1.7.2 1.3.3.7.2 1.4.3 2 .5.6.2 1.1.4 1.6.7.4.3.8.6 1 1.1s.4 1 .4 1.6c0 .7-.2 1.4-.6 2-.4.6-1.1 1.1-1.9 1.4-.8.3-1.8.5-2.9.5-1.3 0-2.4-.2-3.3-.7-.6-.3-1.1-.8-1.5-1.3-.4-.6-.6-1.1-.6-1.6 0-.3.1-.6.4-.9.3-.2.6-.3.9-.3.3 0 .6.1.8.3.2.2.4.4.5.8.2.4.3.7.5.9.2.2.4.4.8.6.3.2.8.2 1.3.2.8 0 1.4-.2 1.8-.5.5-.3.7-.7.7-1.1 0-.4-.1-.6-.4-.9-.2-.2-.6-.4-1-.5-.4-.1-1-.3-1.7-.4-.9-.2-1.8-.4-2.4-.7-.4-.3-1-.7-1.3-1.2-.4-.5-.7-1.1-.7-1.8s.2-1.3.6-1.8c.4-.5 1-.9 1.8-1.2.8-.3 1.7-.4 2.7-.4z"/>
				</svg>',
				);
			break;

			case 'github';
				return array(
					'name' => __( 'GitHub', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M8.9,0.4C4.3,0.9,0.6,4.6,0.1,9.1c-0.5,4.7,2.2,8.9,6.3,10.5C6.7,19.7,7,19.5,7,19.1v-1.6c0,0-0.4,0.1-0.9,0.1c-1.4,0-2-1.2-2.1-1.9c-0.1-0.4-0.3-0.7-0.6-1C3.1,14.6,3,14.6,3,14.5c0-0.2,0.3-0.2,0.4-0.2c0.6,0,1.1,0.7,1.3,1c0.5,0.8,1.1,1,1.4,1c0.4,0,0.7-0.1,0.9-0.2c0.1-0.7,0.4-1.4,1-1.8c-2.3-0.5-4-1.8-4-4c0-1.1,0.5-2.2,1.2-3C5.1,7.1,5,6.6,5,5.9c0-0.4,0-1,0.3-1.6c0,0,1.4,0,2.8,1.3C8.6,5.4,9.3,5.3,10,5.3s1.4,0.1,2,0.3c1.3-1.3,2.8-1.3,2.8-1.3C15,4.9,15,5.5,15,5.9c0,0.8-0.1,1.2-0.2,1.4c0.7,0.8,1.2,1.8,1.2,3c0,2.2-1.7,3.5-4,4c0.6,0.5,1,1.4,1,2.3v2.6c0,0.3,0.3,0.6,0.7,0.5c3.7-1.5,6.3-5.1,6.3-9.3C20,4.4,14.9-0.3,8.9,0.4z"/>
				</svg>',
				);
			break;

			case 'gitlab';
				return array(
					'name' => __( 'GitLab', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M15.7.9c-.2 0-.4.1-.4.3l-2.2 6.7H6.9L4.8 1.2C4.7 1 4.5.9 4.4.9c-.2 0-.4.1-.5.3l-2.6 7L0 11.6c0 .2 0 .4.2.5l9.6 7h.1l9.6-7c.5-.1.5-.3.5-.5l-1.3-3.5-2.6-7c-.1-.1-.3-.2-.4-.2zM2.6 8.7h3.7l2.5 7.8-6.2-7.8zm11.1 0h3.7l-6.2 7.8 2.5-7.8zm-11.8.4l5.8 7.3L1 11.6l.9-2.5zm16.2 0l.9 2.4-6.7 4.9 5.8-7.3z"/>
				</svg>',
				);
			break;

			case 'youtube';
				return array(
					'name' => __( 'YouTube', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewbox="-21 -117 682.66672 682">
					<path d="m626.8125 64.035156c-7.375-27.417968-28.992188-49.03125-56.40625-56.414062-50.082031-13.703125-250.414062-13.703125-250.414062-13.703125s-200.324219 0-250.40625 13.183593c-26.886719 7.375-49.03125 29.519532-56.40625 56.933594-13.179688 50.078125-13.179688 153.933594-13.179688 153.933594s0 104.378906 13.179688 153.933594c7.382812 27.414062 28.992187 49.027344 56.410156 56.410156 50.605468 13.707031 250.410156 13.707031 250.410156 13.707031s200.324219 0 250.40625-13.183593c27.417969-7.378907 49.03125-28.992188 56.414062-56.40625 13.175782-50.082032 13.175782-153.933594 13.175782-153.933594s.527344-104.382813-13.183594-154.460938zm-370.601562 249.878906v-191.890624l166.585937 95.945312zm0 0"/>
				</svg>',
				);
			break;

			case 'vimeo';
				return array(
					'name' => __( 'Vimeo', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M20,5.3c-0.1,1.9-1.4,4.6-4.1,8c-2.7,3.5-5,5.3-6.9,5.3c-1.2,0-2.2-1.1-3-3.2C4.5,9.7,3.8,6.3,2.5,6.3c-0.2,0-0.7,0.3-1.6,0.9L0,6c2.3-2,4.5-4.3,5.9-4.4c1.6-0.2,2.5,0.9,2.9,3.2c1.3,8.1,1.8,9.3,4.2,5.7c0.8-1.3,1.3-2.3,1.3-3c0.2-2-1.6-1.9-2.8-1.4c1-3.2,2.9-4.8,5.6-4.7C19.1,1.4,20.1,2.7,20,5.3L20,5.3z"/>
				</svg>',
				);
			break;

			case 'dtube';
				return array(
					'name' => __( 'DTube', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M18.2,6c-0.4-1.2-1.1-2.3-1.9-3.2c-0.8-0.9-1.8-1.6-2.9-2C12.3,0.2,11,0,9.6,0H1.1v20h8.2c1.3,0,2.4-0.2,3.4-0.5c1-0.3,1.9-0.8,2.7-1.4c1.1-0.9,2-2,2.6-3.3c0.6-1.4,0.9-2.9,0.9-4.7C18.9,8.6,18.7,7.2,18.2,6z M6.1,14.5v-9l7.8,4.5L6.1,14.5z"/>
				</svg>',
				);
			break;

			case 'vk';
				return array(
					'name' => __( 'VK', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20px"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M19.2,4.8H16c-0.3,0-0.5,0.1-0.6,0.4c0,0-1.3,2.4-1.7,3.2c-1.1,2.2-1.8,1.5-1.8,0.5V5.4c0-0.6-0.5-1.1-1.1-1.1H8.2C7.6,4.3,6.9,4.6,6.5,5.1c0,0,1.2-0.2,1.2,1.5c0,0.4,0,1.6,0,2.6c0,0.4-0.3,0.7-0.7,0.7c-0.2,0-0.4-0.1-0.6-0.2c-1-1.4-1.8-2.9-2.5-4.5C4,5,3.7,4.8,3.5,4.8c-0.7,0-2.1,0-2.9,0C0.2,4.8,0,5,0,5.3c0,0.1,0,0.1,0,0.2C0.9,8,4.8,15.7,9.2,15.7H11c0.4,0,0.7-0.3,0.7-0.7v-1.1c0-0.4,0.3-0.7,0.7-0.7c0.2,0,0.4,0.1,0.5,0.2l2.2,2.1c0.2,0.2,0.5,0.3,0.7,0.3h2.9c1.4,0,1.4-1,0.6-1.7c-0.5-0.5-2.5-2.6-2.5-2.6c-0.3-0.4-0.4-0.9-0.1-1.3c0.6-0.8,1.7-2.2,2.1-2.8C19.6,6.5,20.7,4.8,19.2,4.8z"/>
				</svg>',
				);
			break;

			case 'ok';
				return array(
					'name' => __( 'Odnoklassniki', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24">
					<path d="m4.721 12.881c-.613 1.205.083 1.781 1.671 2.765 1.35.834 3.215 1.139 4.413 1.261-.491.472 1.759-1.692-4.721 4.541-1.374 1.317.838 3.43 2.211 2.141l3.717-3.585c1.423 1.369 2.787 2.681 3.717 3.59 1.374 1.294 3.585-.801 2.226-2.141-.102-.097-5.037-4.831-4.736-4.541 1.213-.122 3.05-.445 4.384-1.261l-.001-.001c1.588-.989 2.284-1.564 1.68-2.769-.365-.684-1.349-1.256-2.659-.267 0 0-1.769 1.355-4.622 1.355-2.854 0-4.622-1.355-4.622-1.355-1.309-.994-2.297-.417-2.658.267z"/><path d="m11.999 12.142c3.478 0 6.318-2.718 6.318-6.064 0-3.36-2.84-6.078-6.318-6.078-3.479 0-6.319 2.718-6.319 6.078 0 3.346 2.84 6.064 6.319 6.064zm0-9.063c1.709 0 3.103 1.341 3.103 2.999 0 1.644-1.394 2.985-3.103 2.985s-3.103-1.341-3.103-2.985c-.001-1.659 1.393-2.999 3.103-2.999z"/>
				</svg>',
				);
			break;

			case 'rss';
				return array(
					'name' => __( 'RSS', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
				    height="20"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 448 448" style="enable-background:new 0 0 448 448;" xml:space="preserve"><circle cx="64" cy="384" r="64"/>
					<path d="M0,149.344v85.344c117.632,0,213.344,95.68,213.344,213.312h85.312C298.656,283.328,164.672,149.344,0,149.344z"/><path d="M0,0v85.344C200,85.344,362.688,248,362.688,448H448C448,200.96,247.04,0,0,0z"/>
				</svg>',
				);
			break;

			case 'discord';
				return array(
					'name' => __( 'Discord', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M17.2,4.2c-1.7-1.4-4.5-1.6-4.6-1.6c-0.2,0-0.4,0.1-0.4,0.3c0,0-0.1,0.1-0.1,0.4c1.1,0.2,2.6,0.6,3.8,1.4C16.1,4.7,16.2,5,16,5.2c-0.1,0.1-0.2,0.2-0.4,0.2c-0.1,0-0.2,0-0.2-0.1C13.3,4,10.5,3.9,10,3.9S6.7,4,4.6,5.3C4.4,5.5,4.1,5.4,4,5.2C3.8,5,3.9,4.7,4.1,4.6c1.3-0.8,2.7-1.2,3.8-1.4C7.9,3,7.8,2.9,7.8,2.9C7.7,2.7,7.5,2.6,7.4,2.6c-0.1,0-2.9,0.2-4.6,1.7C1.8,5.1,0,10.1,0,14.3c0,0.1,0,0.2,0.1,0.2c1.3,2.2,4.7,2.8,5.5,2.8c0,0,0,0,0,0c0.1,0,0.3-0.1,0.4-0.2l0.8-1.1c-2.1-0.6-3.2-1.5-3.3-1.6c-0.2-0.2-0.2-0.4,0-0.6c0.2-0.2,0.4-0.2,0.6,0c0,0,2,1.7,6,1.7c4,0,6-1.7,6-1.7c0.2-0.2,0.5-0.1,0.6,0c0.2,0.2,0.1,0.5,0,0.6c-0.1,0.1-1.2,1-3.3,1.6l0.8,1.1c0.1,0.1,0.2,0.2,0.4,0.2c0,0,0,0,0,0c0.8,0,4.2-0.6,5.5-2.8c0-0.1,0.1-0.1,0.1-0.2C20,10.1,18.2,5.1,17.2,4.2z M7.2,12.6c-0.8,0-1.5-0.8-1.5-1.7s0.7-1.7,1.5-1.7c0.8,0,1.5,0.8,1.5,1.7S8,12.6,7.2,12.6z M12.8,12.6c-0.8,0-1.5-0.8-1.5-1.7s0.7-1.7,1.5-1.7c0.8,0,1.5,0.8,1.5,1.7S13.7,12.6,12.8,12.6z"/>
				</svg>',
				);
			break;

			case 'tripadvisor';
				return array(
					'name' => __( 'TripAdvisor', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M5.9 10.7c0 .4-.4.8-.8.8s-.8-.4-.8-.8.4-.8.8-.8.8.3.8.8zm1.7 0c0 1.3-1.1 2.4-2.4 2.4S2.7 12 2.7 10.7c0-1.3 1.1-2.4 2.4-2.4s2.5 1 2.5 2.4zm-.9 0c0-.9-.7-1.6-1.6-1.6-.9 0-1.6.7-1.6 1.6 0 .9.7 1.6 1.6 1.6.9 0 1.6-.7 1.6-1.6zm8.2-.8c-.4 0-.8.4-.8.8s.4.8.8.8.8-.4.8-.8c0-.5-.4-.8-.8-.8zm2.4.8c0 1.3-1.1 2.4-2.4 2.4s-2.4-1.1-2.4-2.4c0-1.3 1.1-2.4 2.4-2.4s2.4 1 2.4 2.4zm-.8 0c0-.9-.7-1.6-1.6-1.6-.9 0-1.6.7-1.6 1.6 0 .9.7 1.6 1.6 1.6.9 0 1.6-.7 1.6-1.6zm1.6 4.1c-2.1 1.7-5.2 1.3-6.9-.8l-.9 1.5c0 .1-.1.1-.1.1-.2.1-.4.1-.6-.1L8.7 14c-1.7 2.1-4.7 2.5-6.9.8-2-1.7-2.4-4.8-.8-6.9-.1-.5-.4-1-.7-1.4 0-.1-.1-.2-.1-.3 0-.2.2-.4.4-.4h3.1c3.9-2.2 8.7-2.2 12.6 0h3.1c.1 0 .2 0 .3.1.2.1.2.4 0 .6-.3.4-.6.9-.8 1.4 1.7 2.1 1.3 5.2-.8 6.9zm-8.9-4.1c0-2.2-1.8-4.1-4.1-4.1h-1C2.3 7.1 1 8.8 1 10.7c0 2.2 1.9 4 4.1 4 2.3.1 4.1-1.8 4.1-4zm6.6-4h-.2c-.2 0-.5-.1-.7-.1-2.2 0-4 1.7-4.1 3.9 0 .7.2 1.4.5 2.1.1.1.1.2.2.3.8 1.1 2 1.8 3.4 1.8 1.9 0 3.5-1.3 3.9-3.1.5-2.1-.8-4.3-3-4.9z"/>
				</svg>',
				);
			break;

			case 'foursquare';
				return array(
					'name' => __( 'Foursquare', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M14.8 2.9l-.4 2.3c-.1.3-.4.5-.7.5H9.5c-.5 0-.8.4-.8.8V7c0 .5.3.8.8.8H13c.3 0 .7.4.6.7l-.4 2.3c0 .2-.3.5-.7.5H9.6c-.5 0-.7.1-1 .5-.3.4-3.5 4.2-3.5 4.2H5V2.8c0-.3.3-.6.6-.6h8.6c.4 0 .7.3.6.7zm.3 9.1c.1-.5 1.5-7.3 1.9-9.5M15.4 0H4.7C3.3 0 2.8 1.1 2.8 1.8v16.9c0 .8.4 1.1.7 1.2.2.1.9.2 1.3-.3 0 0 5-5.8 5.1-5.9.1-.1.1-.1.3-.1h3.3c1.4 0 1.6-1 1.7-1.5.1-.5 1.5-7.3 1.9-9.5C17.4.9 17 0 15.4 0z"/>
				</svg>',
				);
			break;

			case 'yelp';
				return array(
					'name' => __( 'Yelp', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M18.8 14.4c0 .4-.3.8-.3.9l-2.1 2.9-.1.1c-.1 0-.5.3-1 .3s-1-.6-1.1-.7l-2.7-4.2c-.3-.3-.3-1 .1-1.5.3-.3.5-.3.9-.3h.3l5 1.5c.3.1 1 .3 1 1zm-6.1-3.3l5-1.4c.2-.1.9-.3 1-.9.2-.5-.1-1-.2-1 0 0 0-.1-.1-.1L16 5.2c0-.1-.3-.5-1-.5s-1 .6-1 .7l-2.8 4.2c-.2.3-.3.8 0 1.2.3.2.6.3 1.1.3h.4zM9.9.2C9.3 0 8.9 0 8.6.1L4.4 1.4c-.1 0-.5.2-.9.6-.4.8.4 1.6.4 1.6l4.4 5.5c.1.1.4.4 1 .4h.3c.7-.2 1-.9 1-1.3V1.6c-.1-.2-.2-1.1-.7-1.4zM8 12.6c.3-.1.7-.3.7-1.1s-.8-1.1-.9-1.2L3.4 8.2c-.1 0-1-.3-1.3-.1-.2.1-.7.5-.7.9l-.3 3.3c0 .2 0 .7.2 1 .1.2.3.4.8.4.3 0 .6-.1.6-.1l5.1-1c.2.1.2 0 .2 0zm1.8.3c-.2-.1-.3-.1-.4-.1-.5 0-1 .3-1 .4l-3.5 3.6c-.1.2-.5.8-.3 1.3.2.4.3.7.8.9l3.5 1h.4c.2 0 .3 0 .4-.1.5-.2.7-.8.7-1.2l.1-4.9c0-.2-.2-.7-.7-.9z"/>
				</svg>',
				);
			break;

			case 'hacker_news';
				return array(
					'name' => __( 'Hacker News', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M0,0v20h20V0H0z M11.2,11.8v4.7H8.8v-4.7L4.7,4.1h1.9l3.4,6l3.4-6h1.9L11.2,11.8z"/>
				</svg>',
				);
			break;

			case 'xing';
				return array(
					'name' => __( 'Xing', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 512 512">
					<polygon points="496,0 376.384,0 198.688,311.264 313.184,512 432.8,512 318.304,311.264"/>
					<polygon points="149.216,96 36.448,96 101.696,210.912 16,352 128.768,352 214.464,210.912"/>
				</svg>',
				);
			break;

			case 'whatsapp';
				return array(
					'name' => __( 'WhatsApp', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="-23 -21 682 682.66669">
					<path d="m544.386719 93.007812c-59.875-59.945312-139.503907-92.9726558-224.335938-93.007812-174.804687 0-317.070312 142.261719-317.140625 317.113281-.023437 55.894531 14.578125 110.457031 42.332032 158.550781l-44.992188 164.335938 168.121094-44.101562c46.324218 25.269531 98.476562 38.585937 151.550781 38.601562h.132813c174.785156 0 317.066406-142.273438 317.132812-317.132812.035156-84.742188-32.921875-164.417969-92.800781-224.359376zm-224.335938 487.933594h-.109375c-47.296875-.019531-93.683594-12.730468-134.160156-36.742187l-9.621094-5.714844-99.765625 26.171875 26.628907-97.269531-6.269532-9.972657c-26.386718-41.96875-40.320312-90.476562-40.296875-140.28125.054688-145.332031 118.304688-263.570312 263.699219-263.570312 70.40625.023438 136.589844 27.476562 186.355469 77.300781s77.15625 116.050781 77.132812 186.484375c-.0625 145.34375-118.304687 263.59375-263.59375 263.59375zm144.585938-197.417968c-7.921875-3.96875-46.882813-23.132813-54.148438-25.78125-7.257812-2.644532-12.546875-3.960938-17.824219 3.96875-5.285156 7.929687-20.46875 25.78125-25.09375 31.066406-4.625 5.289062-9.242187 5.953125-17.167968 1.984375-7.925782-3.964844-33.457032-12.335938-63.726563-39.332031-23.554687-21.011719-39.457031-46.960938-44.082031-54.890626-4.617188-7.9375-.039062-11.8125 3.476562-16.171874 8.578126-10.652344 17.167969-21.820313 19.808594-27.105469 2.644532-5.289063 1.320313-9.917969-.664062-13.882813-1.976563-3.964844-17.824219-42.96875-24.425782-58.839844-6.4375-15.445312-12.964843-13.359374-17.832031-13.601562-4.617187-.230469-9.902343-.277344-15.1875-.277344-5.28125 0-13.867187 1.980469-21.132812 9.917969-7.261719 7.933594-27.730469 27.101563-27.730469 66.105469s28.394531 76.683594 32.355469 81.972656c3.960937 5.289062 55.878906 85.328125 135.367187 119.648438 18.90625 8.171874 33.664063 13.042968 45.175782 16.695312 18.984374 6.03125 36.253906 5.179688 49.910156 3.140625 15.226562-2.277344 46.878906-19.171875 53.488281-37.679687 6.601563-18.511719 6.601563-34.375 4.617187-37.683594-1.976562-3.304688-7.261718-5.285156-15.183593-9.253906zm0 0" fill-rule="evenodd"/>
				</svg>',
				);
			break;

			case 'flipboard';
				return array(
					'name' => __( 'Flipboard', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M0 0v20h20V0H0zm16 8h-4v4H8v4H4V4h12v4z"/>
				</svg>',
				);
			break;

			case 'viber';
				return array(
					'name' => __( 'Viber', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24">
					<path d="m23.155 13.893c.716-6.027-.344-9.832-2.256-11.553l.001-.001c-3.086-2.939-13.508-3.374-17.2.132-1.658 1.715-2.242 4.232-2.306 7.348-.064 3.117-.14 8.956 5.301 10.54h.005l-.005 2.419s-.037.98.589 1.177c.716.232 1.04-.223 3.267-2.883 3.724.323 6.584-.417 6.909-.525.752-.252 5.007-.815 5.695-6.654zm-12.237 5.477s-2.357 2.939-3.09 3.702c-.24.248-.503.225-.499-.267 0-.323.018-4.016.018-4.016-4.613-1.322-4.341-6.294-4.291-8.895.05-2.602.526-4.733 1.93-6.168 3.239-3.037 12.376-2.358 14.704-.17 2.846 2.523 1.833 9.651 1.839 9.894-.585 4.874-4.033 5.183-4.667 5.394-.271.09-2.786.737-5.944.526z"/><path d="m12.222 4.297c-.385 0-.385.6 0 .605 2.987.023 5.447 2.105 5.474 5.924 0 .403.59.398.585-.005h-.001c-.032-4.115-2.718-6.501-6.058-6.524z"/><path d="m16.151 10.193c-.009.398.58.417.585.014.049-2.269-1.35-4.138-3.979-4.335-.385-.028-.425.577-.041.605 2.28.173 3.481 1.729 3.435 3.716z"/><path d="m15.521 12.774c-.494-.286-.997-.108-1.205.173l-.435.563c-.221.286-.634.248-.634.248-3.014-.797-3.82-3.951-3.82-3.951s-.037-.427.239-.656l.544-.45c.272-.216.444-.736.167-1.247-.74-1.337-1.237-1.798-1.49-2.152-.266-.333-.666-.408-1.082-.183h-.009c-.865.506-1.812 1.453-1.509 2.428.517 1.028 1.467 4.305 4.495 6.781 1.423 1.171 3.675 2.371 4.631 2.648l.009.014c.942.314 1.858-.67 2.347-1.561v-.007c.217-.431.145-.839-.172-1.106-.562-.548-1.41-1.153-2.076-1.542z"/><path d="m13.169 8.104c.961.056 1.427.558 1.477 1.589.018.403.603.375.585-.028-.064-1.346-.766-2.096-2.03-2.166-.385-.023-.421.582-.032.605z"/>
				</svg>',
				);
			break;

			case 'telegram';
				return array(
					'name' => __( 'Telegram', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M19.9,3.1l-3,14.2c-0.2,1-0.8,1.3-1.7,0.8l-4.6-3.4l-2.2,2.1c-0.2,0.2-0.5,0.5-0.9,0.5l0.3-4.7L16.4,5c0.4-0.3-0.1-0.5-0.6-0.2L5.3,11.4L0.7,10c-1-0.3-1-1,0.2-1.5l17.7-6.8C19.5,1.4,20.2,1.9,19.9,3.1z"/>
				</svg>',
				);
			break;

			case 'weibo';
				return array(
					'name' => __( 'Weibo', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M15.9,7.6c0.3-0.9-0.5-1.8-1.5-1.6c-0.9,0.2-1.1-1.1-0.3-1.3c2-0.4,3.6,1.4,3,3.3C16.9,8.8,15.6,8.4,15.9,7.6z M8.4,18.1c-4.2,0-8.4-2-8.4-5.3C0,11,1.1,9,3,7.2c3.9-3.9,7.9-3.9,6.8-0.2c-0.2,0.5,0.5,0.2,0.5,0.2c3.1-1.3,5.5-0.7,4.5,2c-0.1,0.4,0,0.4,0.3,0.5C20.3,11.3,16.4,18.1,8.4,18.1L8.4,18.1zM14,12.4c-0.2-2.2-3.1-3.7-6.4-3.3C4.3,9.4,1.8,11.4,2,13.6s3.1,3.7,6.4,3.3C11.7,16.6,14.2,14.6,14,12.4zM13.6,2c-1,0.2-0.7,1.7,0.3,1.5c2.8-0.6,5.3,2.1,4.4,4.8c-0.3,0.9,1.1,1.4,1.5,0.5C21,4.9,17.6,1.2,13.6,2L13.6,2z M10.5,14.2c-0.7,1.5-2.6,2.3-4.3,1.8c-1.6-0.5-2.3-2.1-1.6-3.5c0.7-1.4,2.5-2.2,4-1.8C10.4,11.1,11.2,12.7,10.5,14.2zM7.2,13c-0.5-0.2-1.2,0-1.5,0.5C5.3,14,5.5,14.6,6,14.8c0.5,0.2,1.2,0,1.5-0.5C7.8,13.8,7.7,13.2,7.2,13zM8.4,12.5c-0.2-0.1-0.4,0-0.6,0.2c-0.1,0.2-0.1,0.4,0.1,0.5c0.2,0.1,0.5,0,0.6-0.2C8.7,12.8,8.6,12.6,8.4,12.5z"/>
				</svg>',
				);
			break;

			case 'tumblr';
				return array(
					'name' => __( 'Tumblr', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24">
					<path d="m19 22.594-1.175-3.425c-.458.214-1.327.399-1.968.419h-.091c-1.863 0-2.228-1.37-2.244-2.371v-7.47h4.901v-3.633h-4.883v-6.114h-3.575c-.059 0-.162.051-.176.179-.202 1.873-1.098 5.156-4.789 6.469v3.099h2.456v7.842c0 2.655 1.97 6.411 7.148 6.411l-.011-.002h.181c1.786-.03 3.783-.768 4.226-1.404z"/>
				</svg>',
				);
			break;

			case 'getpocket';
				return array(
					'name' => __( 'Getpocket', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24">
				<path d="M0,10.7c0,6.702,5.341,12.05,12.01,12.05C18.654,22.75,24,17.402,24,10.7V3.432c0-1.204-0.949-2.182-2.165-2.182H2.175C0.991,1.25,0,2.244,0,3.432V10.7z M6.374,7.41c0.911,0,0.863,0.204,5.635,4.8c4.854-4.67,4.746-4.8,5.651-4.8c0.905,0,1.645,0.742,1.645,1.65c0,0.956-0.156,0.844-6.15,6.622h0.001c-0.664,0.634-1.682,0.596-2.272,0c-6.091-5.854-6.155-5.65-6.155-6.622C4.729,8.152,5.468,7.41,6.374,7.41z"/></svg>
					<path d="m19 22.594-1.175-3.425c-.458.214-1.327.399-1.968.419h-.091c-1.863 0-2.228-1.37-2.244-2.371v-7.47h4.901v-3.633h-4.883v-6.114h-3.575c-.059 0-.162.051-.176.179-.202 1.873-1.098 5.156-4.789 6.469v3.099h2.456v7.842c0 2.655 1.97 6.411 7.148 6.411l-.011-.002h.181c1.786-.03 3.783-.768 4.226-1.404z"/>
				</svg>',
				);
			break;

			case 'line';
				return array(
					'name' => __( 'Line', 'rishi' ),
					'icon' => '<svg
                    width="20"
                    height="20"
                    viewBox="0 0 296.528 296.528"
				    x="0px" y="0px"><g><path d="M295.838,115.347l0.003-0.001l-0.092-0.76c-0.001-0.013-0.002-0.023-0.004-0.036c-0.001-0.011-0.002-0.021-0.004-0.032 l-0.344-2.858c-0.069-0.574-0.148-1.228-0.238-1.974l-0.072-0.594l-0.147,0.018c-3.617-20.571-13.553-40.093-28.942-56.762 c-15.317-16.589-35.217-29.687-57.548-37.878c-19.133-7.018-39.434-10.577-60.337-10.577c-28.22,0-55.627,6.637-79.257,19.193 C23.289,47.297-3.585,91.799,0.387,136.461c2.056,23.111,11.11,45.11,26.184,63.621c14.188,17.423,33.381,31.483,55.503,40.66 c13.602,5.642,27.051,8.301,41.291,11.116l1.667,0.33c3.921,0.776,4.975,1.842,5.247,2.264c0.503,0.784,0.24,2.329,0.038,3.18 c-0.186,0.785-0.378,1.568-0.57,2.352c-1.529,6.235-3.11,12.683-1.868,19.792c1.428,8.172,6.531,12.859,14.001,12.86 c0.001,0,0.001,0,0.002,0c8.035,0,17.18-5.39,23.231-8.956l0.808-0.475c14.436-8.478,28.036-18.041,38.271-25.425 c22.397-16.159,47.783-34.475,66.815-58.17C290.172,175.745,299.2,145.078,295.838,115.347z M92.343,160.561H66.761 c-3.866,0-7-3.134-7-7V99.865c0-3.866,3.134-7,7-7c3.866,0,7,3.134,7,7v46.696h18.581c3.866,0,7,3.134,7,7 C99.343,157.427,96.209,160.561,92.343,160.561z M119.03,153.371c0,3.866-3.134,7-7,7c-3.866,0-7-3.134-7-7V99.675 c0-3.866,3.134-7,7-7c3.866,0,7,3.134,7,7V153.371z M182.304,153.371c0,3.033-1.953,5.721-4.838,6.658 c-0.712,0.231-1.441,0.343-2.161,0.343c-2.199,0-4.323-1.039-5.666-2.888l-25.207-34.717v30.605c0,3.866-3.134,7-7,7 c-3.866,0-7-3.134-7-7v-52.16c0-3.033,1.953-5.721,4.838-6.658c2.886-0.936,6.045,0.09,7.827,2.545l25.207,34.717V99.675 c0-3.866,3.134-7,7-7c3.866,0,7,3.134,7,7V153.371z M233.311,159.269h-34.645c-3.866,0-7-3.134-7-7v-26.847V98.573 c0-3.866,3.134-7,7-7h33.57c3.866,0,7,3.134,7,7s-3.134,7-7,7h-26.57v12.849h21.562c3.866,0,7,3.134,7,7c0,3.866-3.134,7-7,7 h-21.562v12.847h27.645c3.866,0,7,3.134,7,7S237.177,159.269,233.311,159.269z"/></g></svg>',
				);
			break;

			case 'evernote';
				return array(
					'name' => __( 'Evernote', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 40 48">
				 	<path d="M834.793 1065.704a9.585 9.585 0 0 0-1.847.111c.163-1.321.707-2.944 2.633-2.877 2.132.075 2.43 2.097 2.438 3.468-.9-.403-2.012-.658-3.224-.702m9.117-14.353c-.357-1.916-1.491-2.86-2.518-3.23-1.105-.402-3.35-.817-6.169-1.152-2.268-.267-4.933-.246-6.544-.196-.192-1.33-1.12-2.543-2.16-2.963-2.767-1.119-7.045-.849-8.141-.54-.873.245-1.84.747-2.377 1.52-.36.516-.593 1.179-.595 2.103 0 .523.015 1.755.027 2.85.013 1.098.028 2.08.028 2.088 0 .976-.79 1.773-1.769 1.774h-4.486c-.958 0-1.69.162-2.247.416-.56.256-.956.6-1.258 1.006-.6.809-.704 1.805-.7 2.822 0 0 .008.832.208 2.44.166 1.245 1.51 9.944 2.788 12.588.495 1.03.825 1.458 1.797 1.911 2.168.933 7.121 1.97 9.441 2.267 2.318.297 3.771.922 4.638-.902.003-.004.173-.454.408-1.113.753-2.289.857-4.32.857-5.788 0-.15.219-.156.219 0 0 1.036-.198 4.708 2.565 5.694 1.09.388 3.352.733 5.65 1.005 2.079.24 3.588 1.06 3.588 6.414 0 3.256-.682 3.703-4.244 3.703-2.888 0-3.989.075-3.989-2.227 0-1.862 1.835-1.667 3.193-1.667.608 0 .167-.453.167-1.602 0-1.143.712-1.803.04-1.82-4.701-.13-7.466-.005-7.466 5.896 0 5.358 2.042 6.352 8.711 6.352 5.231 0 7.075-.172 9.234-6.898.427-1.33 1.46-5.38 2.085-12.186.396-4.302-.372-17.288-.981-20.565m-34.915.946h4.49a.467.467 0 0 0 .464-.466c0-.002-.053-3.863-.053-4.937v-.013c0-.882.183-1.65.505-2.295l.154-.288a.088.088 0 0 0-.053.027l-8.718 8.68a.1.1 0 0 0-.032.051c.18-.09.427-.211.461-.226.76-.345 1.68-.533 2.782-.533" transform="translate(-805 -1043)"/>
				</svg>',
				);
			break;

			case 'threema';
				return array(
					'name' => __( 'Threema', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    x="0px"
                    y="0px"
                    viewBox="0 0 278.7 323.3"
				version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="Threema-Logo"><g id="Threema:-Wortmarke-_x2B_-Bildmarke-_x2B_-Claim-_x28_Landscape_x29_" transform="translate(-200.000000, -342.000000)"><path d="M425,618c13.1,0,23.7,10.6,23.7,23.7c0,13.1-10.6,23.7-23.7,23.7c-13.1,0-23.6-10.6-23.6-23.7 C401.4,628.6,411.9,618,425,618z M254.4,618c13.1,0,23.7,10.6,23.7,23.7c0,13.1-10.6,23.7-23.7,23.7c-13.1,0-23.6-10.6-23.6-23.7 C230.7,628.6,241.3,618,254.4,618z M339.7,618c13.1,0,23.7,10.6,23.7,23.7c0,13.1-10.6,23.7-23.7,23.7 c-13.1,0-23.6-10.6-23.6-23.7C316,628.6,326.6,618,339.7,618z M339.7,342.1c76.8,0,139,54.8,139,122.3s-62.2,122.3-139,122.3 c-21.8,0-42.5-4.4-60.9-12.3l-69.6,17.4l14.9-59.5c-14.7-19.4-23.3-42.8-23.3-67.9C200.7,396.9,262.9,342.1,339.7,342.1z M339.7,399.2c-21.8,0-39.4,17.6-39.4,39.4v15.7h-1.5c-3.5,0-6.3,2.8-6.3,6.3v55.6c0,3.5,2.8,6.3,6.3,6.3h82 c3.5,0,6.3-2.8,6.3-6.3v-55.6c0-3.5-2.8-6.3-6.3-6.3h-1.5v-15.7C379.1,416.8,361.5,399.2,339.7,399.2z M339.7,414.9 c13.1,0,23.7,10.6,23.7,23.6v15.7H316v-15.7C316,425.5,326.6,414.9,339.7,414.9z"/></g></g></svg>',
				);
			break;

			case 'qq';
				return array(
					'name' => __( 'QQ', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M18.2,16.4c-0.5,0.1-1.8-2.1-1.8-2.1c0,1.2-0.6,2.8-2,4c0.7,0.2,2.1,0.7,1.8,1.3C16,20.2,11.3,20,10,19.8c-1.3,0.2-5.9,0.3-6.2-0.2c-0.4-0.6,1.1-1.1,1.8-1.3c-1.4-1.2-2-2.8-2-4c0,0-1.3,2.1-1.8,2.1c-0.2,0-0.5-1.2,0.4-3.9c0.4-1.3,0.9-2.4,1.6-4.1C3.6,3.8,5.5,0,10,0c4.4,0,6.4,3.8,6.3,8.4c0.7,1.8,1.2,2.8,1.6,4.1C18.7,15.3,18.4,16.4,18.2,16.4L18.2,16.4z"/>
				</svg>',
				);
			break;

			case 'wechat';
				return array(
					'name' => __( 'WeChat', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M13.5,6.8c0.2,0,0.5,0,0.7,0c-0.6-2.9-3.7-5-7.1-5C3.2,1.9,0,4.5,0,7.9c0,1.9,1.1,3.5,2.8,4.8l-0.7,2.1l2.5-1.2c0.9,0.2,1.6,0.4,2.5,0.4c0.2,0,0.4,0,0.7,0c-0.1-0.5-0.2-1-0.2-1.5C7.5,9.3,10.2,6.8,13.5,6.8L13.5,6.8zM9.7,4.9c0.5,0,0.9,0.4,0.9,0.9c0,0.5-0.4,0.9-0.9,0.9c-0.5,0-1.1-0.4-1.1-0.9C8.7,5.2,9.2,4.9,9.7,4.9zM4.8,6.6c-0.5,0-1.1-0.4-1.1-0.9c0-0.5,0.5-0.9,1.1-0.9c0.5,0,0.9,0.4,0.9,0.9C5.7,6.3,5.3,6.6,4.8,6.6z M20,12.3c0-2.8-2.8-5.1-6-5.1c-3.4,0-6,2.3-6,5.1s2.6,5.1,6,5.1c0.7,0,1.4-0.2,2.1-0.4l1.9,1.1l-0.5-1.8C18.9,15.3,20,13.9,20,12.3zM12,11.4c-0.4,0-0.7-0.4-0.7-0.7c0-0.4,0.4-0.7,0.7-0.7c0.5,0,0.9,0.4,0.9,0.7C12.9,11.1,12.6,11.4,12,11.4zM15.9,11.4c-0.4,0-0.7-0.4-0.7-0.7c0-0.4,0.4-0.7,0.7-0.7c0.5,0,0.9,0.4,0.9,0.7C16.8,11.1,16.5,11.4,15.9,11.4z"/>
				</svg>',
				);
			break;

			case 'strava';
				return array(
					'name' => __( 'Strava', 'rishi' ),
					'icon' => '<svg
				    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M12.3,13.9l-1.4-2.7h2.8L12.3,13.9z M20,3v14c0,1.7-1.3,3-3,3H3c-1.7,0-3-1.3-3-3V3c0-1.7,1.3-3,3-3h14C18.7,0,20,1.3,20,3zM15.8,11.1h-2.1L9,2l-4.7,9.1H7L9,7.5l1.9,3.6H8.8l3.5,6.9L15.8,11.1z"/>
				</svg>',
				);
			break;

			case 'flickr';
				return array(
					'name' => __( 'Flickr', 'rishi' ),
					'icon' => '<svg
                    class="rishi-icon"
                    width="20"
                    height="20"
                    viewBox="0 0 20 20">
					<path d="M4.7 14.7C2.1 14.8 0 12.6 0 10c0-2.5 2.1-4.7 4.8-4.7 2.6 0 4.7 2.1 4.7 4.8 0 2.6-2.2 4.7-4.8 4.6z"/>
					<path d="M15.3 5.3C18 5.3 20 7.5 20 10c0 2.6-2.1 4.7-4.7 4.7-2.5 0-4.7-2-4.7-4.7-.1-2.6 2-4.7 4.7-4.7z"/>
				</svg>',
				);
			break;

			default:
				// code...
				break;
		}
	}
}
