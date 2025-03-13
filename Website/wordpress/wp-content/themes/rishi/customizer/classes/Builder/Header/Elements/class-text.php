<?php
/**
 * Class Text.
 */

namespace Rishi\Customizer\Header\Elements;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

/**
 * Class Text
 */
class Text extends Abstracts\Builder_Element {

	public function get_id() {
		return 'text';
	}

    public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'HTML', 'rishi' );
	}

	public function config() {
		return array(
			'name'          => $this->get_label(),
			'visibilityKey' => 'header_hide_' . $this->get_id(),
		);
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return void
	 */
	public function get_options() {

		$options  = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'General', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' =>  [
					'header_hide_' . $this->get_id() => [
						'label'               => false,
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],
					'header_text'         => [
						'label'               => __( 'HTML', 'rishi' ),
						'control'             => ControlTypes::WYSIWYG_EDITOR,
						'value'               => __( 'Edit something here..', 'rishi' ),
						'help'                => __( 'You can add here HTML code.', 'rishi' ),
						'divider'             => 'bottom',
					],
					'header_html_horizontal_alignment'        => [
						'control'             => ControlTypes::INPUT_RADIO,
						'label'               => __( 'Alignment', 'rishi' ),
						'view'                => 'text',
						'divider'             => 'bottom',
						'attr'                => ['data-type' => 'horizontal-alignment'],
						'value'               => 'left',
						'choices'             => [
							'left'   => __('Left', 'rishi'),
							'center' => __('Center', 'rishi'),
							'right'  => __('Right', 'rishi'),
						],
					],
				],
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [
					'headerTextFont'                          => rishi_typography_control_option([
						'control' => ControlTypes::TYPOGRAPHY,
						'label'   => __( 'Font', 'rishi' ),
						'divider' => 'bottom',
						'value'   => \Rishi\Customizer\Helpers\Defaults::typography_value( [
							'size'            => array(
								'desktop' => '15px',
								'tablet'  => '15px',
								'mobile'  => '15px',
							),
							'line-height'            => array(
								'desktop' => '1.5em',
								'tablet'  => '1.5em',
								'mobile'  => '1.5em',
							),
						] ),
					]),

					'text_color_group' => [
						'label'   => __( 'Text Color', 'rishi' ),
						'control' => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value'   => [
							'headerTextColor' => [
								'default'      => [
									'color' => 'var(--paletteColor1)',
								],
							],
						],
						'settings' => [
							'headerTextColor'    => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::COLOR_PICKER,
								'design'     => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'pickers'    => [
									[
										'title' => __( 'Initial', 'rishi' ),
										'id'    => 'default'
									],
								],
								'value'   => [
									'default'      => [
										'color' => 'var(--paletteColor1)',
									],
								],
							],
						]
					],
					'link_color_group'  => [
						'label'      => __( 'Link Color', 'rishi' ),
						'control'    => ControlTypes::CONTROLS_GROUP,
						'divider'    => 'bottom',
						'responsive' => false,
						'value'      => [
							'headerLinkColor' => [
								'default' => [
									'color' => 'var(--paletteColor3)',
								],

								'hover'   => [
									'color' => 'var(--paletteColor2)',
								],
							],
						],

						'settings' => [
							'headerLinkColor'    => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::COLOR_PICKER,
								'design'     => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'pickers'    => [
									[
										'title'   => __( 'Text Initial', 'rishi' ),
										'id'      => 'default'
									],
									[
										'title'   => __( 'Link Initial', 'rishi' ),
										'id'      => 'hover',
									],
								],
								'value'  => [
									'default' => [
										'color' => 'var(--paletteColor3)',
									],

									'hover'   => [
										'color' => 'var(--paletteColor2)',
									],
								],
							],
						]
					],

					'headerTextMargin'  => [
						'label'   => __( 'Margin', 'rishi' ),
						'control' => ControlTypes::INPUT_SPACING,
						'divider' => 'bottom',
						'value'      => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => 'auto',
									'left'   => '20',
									'right'  => '20',
									'bottom' => 'auto',
									'unit'	 => 'px'
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => 'auto',
									'left'   => '20',
									'right'  => '20',
									'bottom' => 'auto',
									'unit'	 => 'px'
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => 'auto',
									'left'   => '20',
									'right'  => '20',
									'bottom' => 'auto',
									'unit'	 => 'px'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => true,
					]
				],
			],
		];
		return $options;
	}

    /**
     * Write logic for dynamic css change for the elements
     *
     * @return
     */
    public function dynamic_styles(){
		$header_default   = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$text_color_group = $this->get_mod_value(
			'text_color_group',
			array(
				'headerTextColor' => $header_default['headerTextColor'],
			)
		);
		$link_color_group = $this->get_mod_value(
			'link_color_group',
			array(
				'headerLinkColor' => $header_default['headerLinkColor'],
			)
		);
		$headerTextColor  = $text_color_group['headerTextColor'];
		$headerLinkColor  = $link_color_group['headerLinkColor'];
		$headerTextMargin = $this->get_mod_value( 'headerTextMargin', $header_default['headerTextMargin'] );
		$dateFont         = $this->get_mod_value( 'headerTextFont', $header_default['headerTextFont'] );

		$options = array(
			'headerTextColor' => array(
                'value'     => $headerTextColor,
                'type'      => 'color',
				'default'   => $header_default['headerTextColor'],
                'variables' => array(
                    'default' => array(
                        'variable' => 'color',
                        'selector' => '#rishi-text',
                    ),
                ),
            ),
			//headermenu.
			'headerLinkColor'      => array(
				'value'     => $headerLinkColor,
				'type'      => 'color',
				'default'   => $header_default['headerLinkColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '#rishi-text a',
					),
					'hover' => array(
						'variable' => 'linkHoverColor',
						'selector' => '#rishi-text a',
					),
				),
			),
			'headerTextMargin' => [
				'selector'     => '#rishi-text',
				'important'    => true,
				'variableName' => 'margin',
				'value'        => $headerTextMargin,
				'type'         => 'spacing',
				'responsive'   => true,
			],
			'headerTextFont' => array(
                'value'      => $dateFont,
                'selector'   => '#rishi-text',
                'type'       => 'typography'
            ),
		);
		return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
	}

	/**
	 * Renders function
	 *
	 * @param string $desktop
	 * @return void
	 */
	public function render( $device = 'desktop') {

		$header_html_horizontal_alignment = $this->get_mod_value( 'header_html_horizontal_alignment', 'left' );
		$class  = 'rishi_header_text';

		if ( $header_html_horizontal_alignment ) {
			$class .= ' align-' . $header_html_horizontal_alignment;
		}

		$textforhtml = $this->get_mod_value(
			'header_text',
			__( 'Edit something here..', 'rishi' )
		);

		?>
		<div class="<?php echo esc_attr( $class ); ?>" id="rishi-text">
			<div class="html-content">
				<?php echo \do_shortcode( \wp_kses_post( $textforhtml ) ); ?>
			</div>
		</div>
		<?php
	}
}
