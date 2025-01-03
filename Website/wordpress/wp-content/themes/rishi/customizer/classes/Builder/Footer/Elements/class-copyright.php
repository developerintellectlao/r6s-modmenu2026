<?php
/**
 * Class Copyright.
 */

namespace Rishi\Customizer\Footer\Elements;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Copyright extends Abstracts\Builder_Element {
	public function get_id() {
		return 'copyright';
	}

	public function get_label() {
		return __('Copyright', 'rishi');
	}

	public function get_builder_type() {
		return 'footer';
	}

	public function config() {
		return array(
			'name' => $this->get_label(),
			'visibilityKey' => 'footer_hide_'.$this->get_id(),
		);
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return array get options
	 */
	public function get_options() {
		$options = array(
			\Rishi\Customizer\Helpers\Basic::uniqid() => array(
				'title' => __('General', 'rishi'),
				'control' => ControlTypes::TAB,
				'options' => array(
					'footer_hide_'.$this->get_id() => array(
						'label' => false,
						'control' => ControlTypes::HIDDEN,
						'value' => false,
						'disableRevertButton' => true,
						'help' => __('Hide', 'rishi'),
					),

					'copyright_text' => array(
						'label' => __('Copyright text', 'rishi'),
						'control' => ControlTypes::WYSIWYG_EDITOR,
						'value' => __('Copyright &copy; {current_year} {site_title} - Powered by {theme_author}', 'rishi'),
						'help' => __('You can insert some arbitrary HTML code tags: {current_year}, {site_title} and {theme_author}', 'rishi'),
						'disableRevertButton' => true,
						'quicktags' => false,
						'mediaButtons' => false,
						'tinymce' => array(
							'toolbar1' => 'bold,italic,link,undo,redo',
						),
					),

					'footerCopyrightAlignment' => array(
						'control' => ControlTypes::INPUT_RADIO,
						'label' => __('Horizontal Alignment', 'rishi'),
						'view' => 'text',
						'design' => 'block',
						'divider' => 'top',
						'disableRevertButton' => true,
						'responsive' => __return_false(),
						'attr' => array('data-type' => 'horizontal-alignment'),
						'choices' => array(
							'flex-start' => __('Left', 'rishi'),
							'center' => __('Center', 'rishi'),
							'flex-end' => __('Right', 'rishi'),
						),
						'value' => 'center',
					),

					'footerCopyrightVerticalAlignment' => array(
						'control' => ControlTypes::INPUT_RADIO,
						'label' => __('Vertical Alignment', 'rishi'),
						'view' => 'text',
						'design' => 'block',
						'divider' => 'top',
						'disableRevertButton' => true,
						'responsive' => false,
						'attr' => array('data-type' => 'vertical-alignment'),
						'choices' => array(
							'flex-start' => __('Top', 'rishi'),
							'center' => __('Center', 'rishi'),
							'flex-end' => __('Bottom', 'rishi'),
						),
						'value' => 'flex-start',
					),

					'footer_copyright_visibility' => array(
						'label' => __('Visibility', 'rishi'),
						'control' => ControlTypes::VISIBILITY,
						'design' => 'block',
						'divider' => 'top',
						'value' => array(
							'desktop' => 'desktop',
							'tablet' => 'tablet',
							'mobile' => 'mobile',
						),

						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
							array(
								'desktop' => __('Desktop', 'rishi'),
								'tablet' => __('Tablet', 'rishi'),
								'mobile' => __('Mobile', 'rishi'),
							)
						),
					),

				),
			),

			\Rishi\Customizer\Helpers\Basic::uniqid() => array(
				'title' => __('Design', 'rishi'),
				'control' => ControlTypes::TAB,
				'options' => array(

					'copyrightFont' => rishi_typography_control_option(array(
						'control' => ControlTypes::TYPOGRAPHY,
						'label' => __('Font', 'rishi'),
						'value' => \Rishi\Customizer\Helpers\Defaults::typography_value(
							array(
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
							)
						),
						'divider' => 'bottom',
					)),

					'copyrightColor' => array(
						'label' => __('Font Color', 'rishi'),
						'control' => ControlTypes::COLOR_PICKER,
						'controlType' => 'ColorPicker',
						'colorPalette'	  => true,
						'design' => 'inline',
						'divider' => 'bottom',
						'responsive' => false,
						'skipEditPalette' => true,
						'value' => array(
							'default' => array(
								'color' => 'rgba(255,255,255,0.6)',
							),
						),
						'pickers' => array(
							array(
								'title' => __('Initial', 'rishi'),
								'id' => 'default',
							),
						),
					),
					'copyrightLinkColor' => array(
						'label' => __('Link Color', 'rishi'),
						'control' => ControlTypes::COLOR_PICKER,
						'controlType' => 'ColorPicker',
						'colorPalette'	  => true,
						'design' => 'inline',
						'divider' => 'bottom',
						'responsive' => false,
						'skipEditPalette' => true,
						'value' => array(
							'default' => array(
								'color' => 'var(--paletteColor5)',
							),
							'hover' => array(
								'color' => 'var(--paletteColor3)',
							),
						),
						'pickers' => array(
							array(
								'title' => __('Initial', 'rishi'),
								'id' => 'default',
							),
							array(
								'title' => __('Hover', 'rishi'),
								'id' => 'hover',
								'inherit' => 'var(--linkHoverColor)',
							),
						),
					),
					'copyrightMargin' => array(
						'label' => __('Margin', 'rishi'),
						'control' => ControlTypes::INPUT_SPACING,
						'divider' => 'bottom',
						'value' => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px',
								)
							),
							'tablet' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px',
								)
							),
							'mobile' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => false,
									'top' => '0',
									'left' => '0',
									'right' => '0',
									'bottom' => '0',
									'unit' => 'px',
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => true,
					),

				),
			),

		);
		return $options;
	}

	/**
	 * Write logic for dynamic css change for the elements
	 *
	 * @return array dynamic styles
	 */
	public function dynamic_styles() {
		$copyright_default = \Rishi\Customizer\Helpers\Defaults::get_footer_defaults();
		$copyrightMargin = $this->get_mod_value('copyrightMargin', $copyright_default['copyrightMargin']);
		$copyrightColor = $this->get_mod_value('copyrightColor', $copyright_default['copyrightColor']);
		$copyrightLinkColor = $this->get_mod_value('copyrightLinkColor', $copyright_default['copyrightLinkColor']);
		$copyrightFont = $this->get_mod_value('copyrightFont', $copyright_default['copyrightFont']);

		return array(
			'copyrightMargin' => array(
				'selector' => '.rishi-footer-copyrights',
				'variableName' => 'margin',
				'value' => $copyrightMargin,
				'type' => 'spacing',
				'responsive' => true,
			),
			'copyrightColor' => array(
				'value' => $copyrightColor,
				'default' => array('color' => 'rgba(255,255,255,0.6)'),
				'variables' => array(
					'default' => array(
						'selector' => '.rishi-footer-copyrights',
						'variable' => 'color',
					),
				),
				'type' => 'color',
				'responsive' => false,
			),
			'copyrightLinkColor' => array(
				'value' => $copyrightLinkColor,
				'type' => 'color',
				'default' => $copyright_default['copyrightLinkColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.rishi-footer-copyrights',
					),
					'hover' => array(
						'variable' => 'linkHoverColor',
						'selector' => '.rishi-footer-copyrights',
					),
				),
			),
			'copyrightFont' => array(
				'value' => $copyrightFont,
				'selector' => '.rishi-footer-copyrights',
				'type' => 'typography'
			),
		);
	}

	/**
	 * Copyright Shortcode
	 *
	 * @param string $string
	 * @return string
	 */
	public function rishi_apply_theme_shortcode($string) {
		if(empty($string)) {
			return $string;
		}
		$search = array(
			'{current_year}',
			'{site_title}',
			'{theme_author}',
		);
		$replace = apply_filters(
			'rishi_copyright_string_replacements',
			array(
				date_i18n(esc_html__('Y', 'rishi')),
				'<a href="'.esc_url(home_url('/')).'">'.esc_html(get_bloginfo('name', 'display')).'</a>',
				'<a href="'.esc_url('https://rishitheme.com/', 'rishi').'" target="_blank" rel="nofollow noopener">'.__('Rishi Theme', 'rishi').'</a>',
			)
		);

		$string = str_replace($search, $replace, $string);
		return $string;
	}

	/**
	 * Add markup for the element
	 * @return void
	 */
	public function render( $device = 'desktop') {

        $copyright_text = $this->get_mod_value( 'copyright_text', __('Copyright &copy; {current_year} {site_title} - Powered by {theme_author}', 'rishi') );
        $horizontal_align = $this->get_mod_value( 'footerCopyrightAlignment', 'center');
        $vertical_alignment= $this->get_mod_value( 'footerCopyrightVerticalAlignment', 'start' );

		$visibility = $this->get_mod_value(
			'footer_copyright_visibility',
			array(
				'desktop' => 'desktop',
				'tablet' => 'tablet',
				'mobile' => 'mobile',
			)
		);
		$visibility_class = $this->get_visible_device_class($visibility);

		$class = $visibility_class.' horizontal-'.$horizontal_align.' vertical-'.$vertical_alignment;

		?>
		<div id="rishi-copyrights" class="<?php echo esc_attr($class); ?>">
			<div class="rishi-footer-copyrights">
				<?php
				if($copyright_text) {
					echo wp_kses_post($this->rishi_apply_theme_shortcode($copyright_text));
				}
				?>
			</div>
		</div>
		<?php
	}
}
