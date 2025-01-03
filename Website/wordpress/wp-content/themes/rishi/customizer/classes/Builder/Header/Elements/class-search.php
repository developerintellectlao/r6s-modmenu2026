<?php
/**
 * Class Search.
 */

namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

/**
 * Class Search
 */
class Search extends Abstracts\Builder_Element {

	public function get_id() {
		return 'search';
	}

	public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'Search', 'rishi' );
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

		$options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'General', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [
					'header_hide_' . $this->get_id() => [
						'label'               => false,
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],
					'search_placeholder'                      => [
						'label'   => __( 'Placeholder Text', 'rishi' ),
						'control' => ControlTypes::INPUT_TEXT,
						'divider' => 'bottom',
						'design'  => 'block',
						'value'   => __( 'Search', 'rishi' ),
					],
					'searchHeaderIconSize'  => [
						'label'      => __( 'Icon Size', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'divider'    => 'bottom',
						'value'      => '15px',
						'responsive' => false,
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 5, 'max' => 50 ],
						] ),
					]
				],
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [

					'search_icon_color' => [
						'label'   => __( 'Icon Color', 'rishi' ),
						'control'    => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value'      => [
							'searchHeaderIconColor' => [
								'default'      => [
									'color' => 'var(--paletteColor1)',
								],
								'hover' => [
									'color' => 'var(--paletteColor3)',
								],
							],
						],

						'settings' => [
							'searchHeaderIconColor'    => [
								'label'        => __( 'Default State', 'rishi' ),
								'control'      => ControlTypes::COLOR_PICKER,
								'colorPalette' => true,
								'design'       => 'inline',
								'responsive'   => false,
								'pickers'    => [
									[
										'title'   => __( 'Initial', 'rishi' ),
										'id'      => 'default',
										'inherit' => 'var(--color)'
									],
									[
										'title'   => __( 'Initial', 'rishi' ),
										'id'      => 'hover',
										'inherit' => 'var(--hover-color)'
									],
								],
								'value'      => [
									'default'      => [
										'color' => 'var(--paletteColor1)',
									],
									'hover' => [
										'color' => 'var(--paletteColor3)',
									],
								],
							],
						]
					],
					'search_close_button_color' => [
						'label'   => __( 'Close Icon Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'  => 'inline',
						'divider'   => 'bottom',
						'value'   => [
							'default' => [
								'color' => 'var(--paletteColor5)',
							],

							'hover'   => [
								'color' => 'var(--paletteColor4)',
							],
						],

						'pickers' => [
							[
								'title'   => __( 'Initial', 'rishi' ),
								'id'      => 'default',
								'inherit' => 'rgba(255, 255, 255, 0.7)',
							],

							[
								'title'   => __( 'Hover/Active', 'rishi' ),
								'id'      => 'hover',
								'inherit' => '#ffffff',
							],
						],
					],

					'searchHeaderFontColor' => array(
						'label'     => __( 'Modal Text Color', 'rishi' ),
						'control'   => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'    => 'inline',
						'divider'   => 'bottom',
						'value'     => array(
							'default' => array(
								'color' => 'var(--paletteColor1)',
							),
						),
						'pickers'   => array(
							array(
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							),
						),
					),

					'searchModalBackgroundColor' => array(
						'label'     => __( 'Modal Background Color', 'rishi' ),
						'control'   => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'    => 'inline',
						'divider'   => 'bottom',
						'value'     => array(
							'default' => array(
								'color' => 'rgba(18, 21, 25, 0.5)',
							),
						),
						'pickers'   => array(
							array(
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							),
						),
					),

					'headerSearchMargin' => [
                        'label'      => __('Icon Margin', 'rishi'),
                        'control'    => ControlTypes::INPUT_SPACING,
                        'divider'    => 'bottom',
                        'value'      => array(
							'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '0',
									'unit'	 => 'px'
								)
							),
							'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '0',
									'unit'	 => 'px'
								)
							),
							'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
								array(
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '0',
									'unit'	 => '0'
								)
							),
						),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => true,
                    ],
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
	public function dynamic_styles() {
		$header_default             = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$search_icon_color = $this->get_mod_value(
			'search_icon_color',
			array(
				'searchHeaderIconColor' => $header_default['searchHeaderIconColor'],
			)
		);
		$icon_size                  = $this->get_mod_value( 'searchHeaderIconSize', $header_default['searchHeaderIconSize'] );
		$searchHeaderIconColor  = $search_icon_color['searchHeaderIconColor'];
		$serachCloseIconColor       = $this->get_mod_value( 'search_close_button_color', $header_default['search_close_button_color'] );
		$headerSearchMargin         = $this->get_mod_value( 'headerSearchMargin', $header_default['headerSearchMargin'] );
		$searchHeaderFontColor      = $this->get_mod_value( 'searchHeaderFontColor', $header_default['searchHeaderFontColor'] );
		$searchModalBackgroundColor = $this->get_mod_value( 'searchModalBackgroundColor', $header_default['searchModalBackgroundColor'] );
		$options = array(
			'searchHeaderIconSize' => [
				'selector'     => '#search .rishi-icon',
				'variableName' => 'icon-size',
				'value'        => $icon_size,
				'type'		   => 'slider',
				'responsive'   => false
			],
			'searchHeaderIconColor'      => array(
				'value'     => $searchHeaderIconColor,
				'type'      => 'color',
				'default'   => $header_default['searchHeaderIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '#search',
					),
					'hover'   => array(
						'variable' => 'icon-hover-color',
						'selector' => '#search',
					),
				),
			),
			'search_close_button_color' => array(
				'value'     => $serachCloseIconColor,
				'type'      => 'color',
				'default'   => $header_default['search_close_button_color'],
				'variables' => array(
					'default' => array(
						'variable' => 'closeIconColor',
						'selector' => '.search-toggle-form .btn-form-close',
					),
					'hover'   => array(
						'variable' => 'closeIconHoverColor',
						'selector' => '.search-toggle-form .btn-form-close',
					),
				),
			),
			'searchHeaderFontColor' => array(
				'value'     => $searchHeaderFontColor,
				'type'      => 'color',
				'default'   => $header_default['searchHeaderFontColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'searchHeaderFontColor',
						'selector' => '.search-toggle-form .search-field',
					),
				),
			),
			'searchModalBackgroundColor' => array(
				'value'     => $searchModalBackgroundColor,
				'type'      => 'color',
				'default'   => $header_default['searchModalBackgroundColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.search-toggle-form',
					),
				),
			),
			'headerSearchMargin' => array(
				'selector'     => '#search',
				'variableName' => 'margin',
				'value'        => $headerSearchMargin,
				'type'         => 'spacing',
				'responsive'   => true,
				'property'     => 'margin',
				'unit'		   => 'px'
			),
		);
		return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
	}

	/**
	 * Add markup for the element
	 *
	 * @param string $device
	 * @return void
	 */
	public function render( $device = 'desktop') {

		$hidden       = $this->get_mod_value( 'header_hide_search', false );
		$placeholder  = $this->get_mod_value( 'search_placeholder', __('Search', 'rishi') );

		if ( $hidden ) {
			return '';
		}

		$class = 'rishi-header-search';

		$key = \rand( 0, 99999 );

		?>
		<div class="search-form-section">
			<button class="<?php echo esc_attr( $class ); ?> header-search-btn" data-modal-key="<?php echo esc_attr( $key ); ?>"
				id="search" aria-label="<?php esc_attr_e( 'Search Post and Pages', 'rishi' ); ?>">
				<?php 
				/**
                 * Note to code reviewers: It contains inline SVG, which is absolutely safe and this line doesn't need to be escaped.
                 */
				?>
				<svg class="rishi-icon" width="15" height="15" viewBox="0 0 15 15">
					<path
						d="M14.6 13L12 10.5c.7-.8 1.3-2.5 1.3-3.8 0-3.6-3-6.6-6.6-6.6C3 0 0 3.1 0 6.7c0 3.6 3 6.6 6.6 6.6 1.4 0 2.7-.6 3.8-1.2l2.5 2.3c.7.7 1.2.7 1.7.2.5-.5.5-1 0-1.6zm-8-1.4c-2.7 0-4.9-2.2-4.9-4.9s2.2-4.9 4.9-4.9 4.9 2.2 4.9 4.9c0 2.6-2.2 4.9-4.9 4.9z" />
				</svg>
			</button>
			<div class="search-toggle-form  cover-modal" data-modal-key="<?php echo esc_attr( $key ); ?>"
				data-modal-target-string=".search-modal">
				<div class="header-search-inner">
					<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<label for="header-search-field">
							<span class="screen-reader-text">
								<?php esc_html_e( 'Search for:','rishi' ); ?>
							</span>
							<input type="search" id="header-search-field" class="search-field" placeholder="<?php echo esc_attr($placeholder); ?>" value="<?php the_search_query(); ?>" name="s" title="<?php echo esc_attr__( 'Search Input','rishi' ); ?>">
						</label>
						<input type="submit" class="search-submit" value="<?php echo esc_attr__( 'Search','rishi' ); ?>">
					</form>
					<button id="btn-form-close" class="btn-form-close close"></button>
				</div>
			</div>
		</div>
		<?php
	}
}
