<?php
/**
 * Class Randomize.
 */

namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

/**
 * Class Randomize
 */
class Randomize extends Abstracts\Builder_Element {

	public function get_id() {
		return 'randomize';
	}

	public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'Randomize', 'rishi' );
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
		$cpt_choices = [
			'post'    => __( 'Posts', 'rishi' ),
			'page'    => __( 'Pages', 'rishi' )
		];

		if (class_exists( 'WooCommerce' )) $cpt_choices['product'] =  __( 'Products', 'rishi' );

		$cpt_options = [
			'post'    => true,
			'page'    => true,
			'product' => true
		];

		$options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'General', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [
					'header_hide_' . $this->get_id()            => [
						'label'               => false,
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],
					'header_randomize_icon_type'       => [
						'size'	  => '20px',
						'label'   => __( 'Icon', 'rishi' ),
						'control'    => ControlTypes::IMAGE_PICKER,
						'value'   => 'type-1',
						'attr'    => [
							'data-columns' => '3',
						],
						'divider' => 'bottom',
						'choices' => [
							'type-1' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'shuffle-1.svg' ),
								'title' => __( 'Icon 1', 'rishi' ),
							],
							'type-2' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'shuffel-2.svg' ),
								'title' => __( 'Icon 2', 'rishi' ),
							],

							'type-3' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_image_url( 'shuffle-3.svg' ),
								'title' => __( 'Icon 3', 'rishi' ),
							],

						],
					],
					'headerRandomizeIconSize'       => [
						'label'   => __( 'Icon Size', 'rishi' ),
						'control' => ControlTypes::INPUT_SLIDER,
						'value'   => '20px',
						'divider' => 'bottom',
						'units'   => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 0, 'max' => 50 ],
						] ),
						'responsive' => false,
					],
					'header_randomize_ed_title'        => [
						'label'   => __( 'Show Label', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => 'no',
						'divider' => 'bottom',
					],

					'header_randomize_label' => [
						'label'  => __( 'Label', 'rishi' ),
						'control'   => ControlTypes::INPUT_TEXT,
						'design' => 'inline',
						'value'  => __( 'Surprise me', 'rishi' ),
						'divider' => 'bottom',
						'conditions' => [ 'header_randomize_ed_title' => 'yes' ]
					],

					'header_randomize_pages'           => [
						'label'               => __( 'Randomize', 'rishi' ),
						'help'                => __( 'Choose what you want your visitors to see after clicking this icon.', 'rishi' ),
						'control'             => ControlTypes::INPUT_CHECKBOX,
						'attr'                => [ 'data-columns' => '1' ],
						'disableRevertButton' => true,
						'divider'             => 'bottom',
						'choices'             => \Rishi\Customizer\Helpers\Basic::ordered_keys( $cpt_choices ),
						'value'               => $cpt_options
					],
				]
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [
					'headerRandomizeFont'                     => rishi_typography_control_option([
						'control'    => ControlTypes::TYPOGRAPHY,
						'label'      => __( 'Text Font', 'rishi' ),
						'conditions' => ['header_randomize_ed_title' => 'yes'],
						'divider'    => 'bottom',
						'value'      => \Rishi\Customizer\Helpers\Defaults::typography_value( [
							'size'            => array(
								'desktop' => '18px',
								'tablet'  => '18px',
								'mobile'  => '18px',
							),
						] ),
					]),
					'text_color_group'  => [
						'label'      => __( 'Text Color', 'rishi' ),
						'control'    => ControlTypes::CONTROLS_GROUP,
						'conditions' => ['header_randomize_ed_title' => 'yes'],
						'divider'    => 'bottom',
						'value'      => [
							'headerRandomizeColor' => [
								'default'      => [
									'color' => 'var(--paletteColor1)',
								]
							],
						],
						'settings' => [
							'headerRandomizeColor'    => [
								'label'      => __( 'Default State', 'rishi' ),
								'design'     => 'inline',
								'colorPalette'	  => true,
								'control'    => ControlTypes::COLOR_PICKER,
								'pickers'    => [
									[
										'title'   => __( 'Initial', 'rishi' ),
										'id'      => 'default',
									],
								],
								'value' =>  [
									'default'      => [
										'color' => 'var(--paletteColor1)',
									]
								],
							],
						]
					],
					'icon_color_group' => [
						'label' => __('Icon Color', 'rishi'),
						'control' => ControlTypes::CONTROLS_GROUP,
						'divider' => 'bottom',
						'value'   => [
							'headerRandomizeIconColor' => [
								'default' => [
									'color' => 'var(--paletteColor1)',
								],

								'hover'   => [
									'color' => 'var(--paletteColor3)',
								],
							],

						],
						'settings' => [
							'headerRandomizeIconColor'            => [
								'label'      => __( 'Default State', 'rishi' ),
								'control'    => ControlTypes::COLOR_PICKER,
								'design'     => 'inline',
								'responsive' => false,
								'colorPalette'	  => true,
								'value' => [
									'default' => [
										'color' => 'var(--paletteColor1)',
									],

									'hover'   => [
										'color' => 'var(--paletteColor3)',
									],
								],
								'pickers'    => [
									[
										'title'   => __( 'Initial', 'rishi' ),
										'id'      => 'default',
										'inherit' => 'var(--buttonInitialColor)',
									],

									[
										'title'   => __( 'Hover', 'rishi' ),
										'id'      => 'hover',
										'inherit' => 'var(--buttonHoverColor)',
									],
								],
							],
						]
					],
				]
			]
		];
		return $options;
	}

	/**
	 * Write logic for dynamic css change for the elements
	 *
	 * @return
	 */
	public function dynamic_styles() {
		$header_default = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();
		$text_color 	= $this->get_mod_value( 'text_color_group',
			[
				'headerRandomizeColor'            => $header_default['headerRandomizeColor'],
			]
		);
		$headerRandomizeColor     = $text_color['headerRandomizeColor'];
		$icon_color 	= $this->get_mod_value( 'icon_color_group',
			[
				'headerRandomizeIconColor'            => $header_default['headerRandomizeIconColor'],
			]
		);
		$headerRandomizeIconColor = $icon_color['headerRandomizeIconColor'];
		$headerRandomizeIconSize  = $this->get_mod_value( 'headerRandomizeIconSize', $header_default['headerRandomizeIconSize'] );
		$headerRandomizeFont      = $this->get_mod_value( 'headerRandomizeFont', $header_default['headerRandomizeFont'] );
		$options = array(
			'headerRandomizeColor' => array(
				'value'     => $headerRandomizeColor,
				'type'      => 'color',
				'default'   => array(
					'default' => [
						'color' => 'var(--paletteColor1)',
					],
				),
				'variables' => array(
					'default' => array(
						'variable' => 'headerRandomizeInitialColor',
						'selector' => '.header-randomize-section',
					),
				),
			),
			'headerRandomizeIconColor'      => array(
				'value'     => $headerRandomizeIconColor,
				'type'      => 'color',
				'default'   => array(
					'default' => array( 'color' => 'var(--paletteColor1)' ),
					'hover'   => array( 'color' => 'var(--paletteColor4)' ),
				),
				'variables' => array(
					'default' => array(
						'variable' => 'headerRandomizeInitialIconColor',
						'selector' => '.header-randomize-section',
					),
					'hover'   => array(
						'variable' => 'headerRandomizeInitialIconHoverColor',
						'selector' => '.header-randomize-section',
					),
				),
			),
			'headerRandomizeIconSize' => array(
				'selector'     => '.header-randomize-section',
				'variableName' => 'icon-size',
				'value'        => $headerRandomizeIconSize,
				'responsive'   => false,
				'type'         => 'slider',
			),
			'headerRandomizeFont' => array(
				'value'      => $headerRandomizeFont,
				'selector'   => '.header-randomize-section',
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

		$icon_type       = $this->get_mod_value( 'header_randomize_icon_type', 'type-1' );
		$ed_title        = $this->get_mod_value( 'header_randomize_ed_title', 'no' );
		$randomize_label = $this->get_mod_value( 'header_randomize_label', 'Surprise me' );
		$random_posts    = $this->get_mod_value(
			'header_randomize_pages',
			array(
				'post'    => true,
				'page'    => true,
				'product' => true,
			)
		);

		/**
		 * Note to code reviewers: It contains inline SVG, which is absolutely safe and the value assigned to $icon_type doesn't need to be escaped.
		 */
		if ( $icon_type == 'type-1' ) {
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" strokeLinecap="round" strokeLinejoin="round"  class="no-fill"><polyline points="16 3 21 3 21 8"></polyline><line x1="4" y1="20" x2="21" y2="3"></line><polyline points="21 16 21 21 16 21"></polyline><line x1="15" y1="15" x2="21" y2="21"></line><line x1="4" y1="4" x2="9" y2="9"></line></svg>';
		} elseif ( $icon_type == 'type-2' ) {
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" height="48" width="48"><path d="M5 46Q3.8 46 2.9 45.1Q2 44.2 2 43V4.95Q2 3.8 2.9 2.875Q3.8 1.95 5 1.95H43.05Q44.2 1.95 45.125 2.875Q46.05 3.8 46.05 4.95V43Q46.05 44.2 45.125 45.1Q44.2 46 43.05 46ZM19.8 21.8 21.95 19.65 10.75 8.5Q10.3 8.05 9.7 8.05Q9.1 8.05 8.65 8.5Q8.2 8.95 8.225 9.575Q8.25 10.2 8.65 10.65ZM30.6 40.5H39.05Q39.7 40.5 40.125 40.075Q40.55 39.65 40.55 39V30.55Q40.55 29.9 40.125 29.475Q39.7 29.05 39.05 29.05Q38.4 29.05 37.975 29.475Q37.55 29.9 37.55 30.55V35.4L28.25 26.2L26.15 28.35L35.35 37.5H30.6Q29.95 37.5 29.525 37.925Q29.1 38.35 29.1 39Q29.1 39.65 29.525 40.075Q29.95 40.5 30.6 40.5ZM8.6 39.4Q9.05 39.8 9.65 39.85Q10.25 39.9 10.7 39.45L37.55 12.6V17.4Q37.55 18.05 37.975 18.475Q38.4 18.9 39.05 18.9Q39.7 18.9 40.125 18.475Q40.55 18.05 40.55 17.4V8.95Q40.55 8.3 40.125 7.875Q39.7 7.45 39.05 7.45H30.6Q29.95 7.45 29.525 7.875Q29.1 8.3 29.1 8.95Q29.1 9.6 29.525 10.025Q29.95 10.45 30.6 10.45H35.45L8.6 37.3Q8.15 37.75 8.15 38.35Q8.15 38.95 8.6 39.4Z"/></svg>';
		} else {
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18 17.883V16l5 3-5 3v-2.09a9 9 0 0 1-6.997-5.365L11 14.54l-.003.006A9 9 0 0 1 2.725 20H2v-2h.725a7 7 0 0 0 6.434-4.243L9.912 12l-.753-1.757A7 7 0 0 0 2.725 6H2V4h.725a9 9 0 0 1 8.272 5.455L11 9.46l.003-.006A9 9 0 0 1 18 4.09V2l5 3-5 3V6.117a7 7 0 0 0-5.159 4.126L12.088 12l.753 1.757A7 7 0 0 0 18 17.883z"/></svg>';
		}

		$components = array();

		if ( $random_posts['post'] ) {
			$components[] = 'post';
		}
		if ( $random_posts['page'] ) {
			$components[] = 'page';
		}

		if ( $random_posts['product'] && class_exists( 'WooCommerce' )) {
			$components[] = 'product';
		}

		$args = array(
			'post_type'      => $components,
			'post_status'    => 'publish',
			'orderby'        => 'rand',
			'posts_per_page' => '1',
		);

		$random_post = get_posts( $args );

		foreach ( $random_post as $p ) {
			$post_id = $p->ID;
		}

		$post_url = get_post_permalink( $post_id );
		if ( $post_url ) {
			?>
			<div id="rishi-randomize">
				<div class="header-randomize-section">
					<?php
						echo '<a href="' . esc_url( $post_url ) . '">';
						if ( $ed_title == 'yes' ) echo '<span class="randomize-label">'	. esc_html( $randomize_label ) . '</span>';
						echo $svg . '</a>';
					?>
				</div>
			</div>
			<?php
		}
	}
}
