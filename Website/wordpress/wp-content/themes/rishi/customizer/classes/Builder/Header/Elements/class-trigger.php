<?php
/**
 * Class Trigger.
 */

namespace Rishi\Customizer\Header\Elements;

use \Rishi\Customizer\Helpers\Defaults as Defaults;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

/**
 * Class Trigger
 */
class Trigger extends Abstracts\Builder_Element {

	public function get_id() {
		return 'trigger';
	}

	public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'Trigger', 'rishi' );
	}

	public function config() {
		return array(
			'name'          => $this->get_label(),
			'visibilityKey' => 'header_hide_' . $this->get_id(),
			'devices'       => ['mobile']
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
				'control' => ControlTypes::TAB,
				'options' => [
					'header_hide_' . $this->get_id()  => [
						'label'               => false,
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],
					'mobile_menu_trigger_type'  => [
						'label'   => __( 'Type', 'rishi' ),
						'control' => ControlTypes::IMAGE_PICKER,
						'value'   => 'type-1',
						'attr'    => [
							'data-columns' => '4',
							'data-ratio'   => '2:1',
						],
						'choices' => [

							'type-1' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( 'trigger-1' ),
								'title' => __( 'Type 1', 'rishi' ),
							],

							'type-2' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( 'trigger-2' ),
								'title' => __( 'Type 2', 'rishi' ),
							],

							'type-3' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( 'trigger-3' ),
								'title' => __( 'Type 3', 'rishi' ),
							],
							'type-4' => [
								'src'   => \Rishi\Customizer\Helpers\Basic::get_svg_by_name( 'trigger-4' ),
								'title' => __( 'Type 4', 'rishi' ),
							],
						],
					],

					'triggerIconSize'                         => [
						'label'      => __( 'Icon Size', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '20px',
						'divider'    => 'top',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 0, 'max' => 50 ],
						] ),
						'responsive' => false,
					],

					'has_trigger_label'                       => [
						'label'   => __( 'Trigger Label', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => 'no',
						'divider' => 'top:bottom',
					],

					'trigger_label'           => [
						'label'   => false,
						'control' => ControlTypes::INPUT_TEXT,
						'design'  => 'block',
						'value'   => __( 'Menu', 'rishi' ),
						'conditions' => [ 'has_trigger_label' => 'yes' ]
					],

					'trigger_label_alignment' => [
						'control' => ControlTypes::INPUT_RADIO,
						'label'   => __( 'Label Alignment', 'rishi' ),
						'value'   => 'right',
						'divider' => 'top',
						'design'  => 'block',
						'conditions' => [ 'has_trigger_label' => 'yes' ],
						'choices' => [
							'left'  => __( 'Left', 'rishi' ),
							'right' => __( 'Right', 'rishi' ),
						],
					],
				],
			],

			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [

					'triggerIconColor'    => [
						'label'      => __( 'Default State', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette'	  => true,
						'design'     => 'inline',
						'value'      => [
							'default' => [
								'color' => 'var(--paletteColor3)',
							],
							'hover' => [
								'color' => 'var(--paletteColor6)',
							],
						],
						'pickers' => [
							[
								'title' => __( 'Initial', 'rishi' ),
								'id'    => 'default',
							],
							[
								'title'   => __( 'Hover', 'rishi' ),
								'id'      => 'hover',
							],
						],
					],


					'trigger_typo'     => rishi_typography_control_option([
						'control' => ControlTypes::TYPOGRAPHY,
						'label'   => __( 'Font', 'rishi' ),
						'divider' => 'bottom',
						'value'   => Defaults::typography_value( [
							'size'            => array(
								'desktop' => '17x',
								'tablet'  => '17px',
								'mobile'  => '17px',
							),
							'line-height'            => array(
								'desktop' => '1.3em',
								'tablet'  => '1.3em',
								'mobile'  => '1.3em',
							),
							'weight'         => '500',
							'text-transform' => 'normal',
						] ),
					]),

					'triggerMargin'    => [
						'label'      => __( 'Margin', 'rishi' ),
						'control'    => ControlTypes::INPUT_SPACING,
						'divider'    => 'bottom',
						'value'      => \Rishi\Customizer\Helpers\Basic::spacing_value( [
							'linked' => true,
							'top'    => '0',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '0',
							'unit'   => 'px'
						] ),
						'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						'responsive' => false,
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
		$trigger_default  = Defaults::get_header_defaults();
		$triggerIconColor = $this->get_mod_value( 'triggerIconColor', $trigger_default['triggerIconColor'] );
		$trigger_typo     = $this->get_mod_value( 'trigger_typo', $trigger_default['trigger_typo'] );
		$triggerMargin    = $this->get_mod_value( 'triggerMargin', $trigger_default['triggerMargin'] );
		$triggerIconSize  = $this->get_mod_value( 'triggerIconSize', $trigger_default['triggerIconSize'] );

		return array(
			'triggerIconSize'  => array(
				'selector'     => '.rishi-header-trigger',
				'variableName' => 'trigger-width',
				'value'        => $triggerIconSize,
				'responsive'   => false,
				'type'         => 'slider'
			),
			'triggerIconColor' => array(
				'value'     => $triggerIconColor,
				'type'      => 'color',
				'default'   => $trigger_default['triggerIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '.rishi-header-trigger',
					),
					'hover'   => array(
						'variable' => 'linkHoverColor',
						'selector' => '.rishi-header-trigger',
					)
				)
			),
			'trigger_typo'     => array(
				'value'    => $trigger_typo,
				'selector' => '.rishi-header-trigger',
				'type'     => 'typography'
			),
			'triggerMargin'    => [
				'selector'     => '.rishi-header-trigger',
				'variableName' => 'margin',
				'value'        => $triggerMargin,
				'responsive'   => false,
				'type'         => 'spacing',
				'property'     => 'margin',
				'unit'         => 'px'
			],
		);
	}

	/**
	 * Renders function
	 *
	 * @param string $desktop
	 * @return void
	 */
	public function render( $device = 'desktop') {

		/**
		 * Triggers the span for the click
		 */
		$trigger_type  = $this->get_mod_value( 'mobile_menu_trigger_type', 'type-1' );
		$trigger_class = 'rishi_menu_trigger';
		if ( $trigger_type ) {
			$trigger_class .= ' rishi-trigger-' . $trigger_type;
		}

		$class     = "rishi_header_trigger toggle-btn";
		$has_label = $this->get_mod_value( 'has_trigger_label', 'no' ) === 'yes';

		if ( $has_label ) {
			$trigger_label_alignment = $this->get_mod_value( 'trigger_label_alignment', 'right' );
			$class .= ' trigger-' . $trigger_label_alignment;
		}

		$has_label_output = '';

		if ( ! $has_label ) {
			$has_label_output = 'hidden';
		}

		$trigger_label = $this->get_mod_value( 'trigger_label', __( 'Menu', 'rishi' ) );

		?>
		<div id="rishi-header-trigger" class="rishi-header-trigger">
			<a
				href="#rishi-offcanvas"
				class="<?php echo esc_attr( $class ); ?>"
				aria-label="<?php echo esc_attr( $trigger_label ); ?>">
				<span class="<?php echo esc_attr( $trigger_class ); ?>">
					<?php if ( $trigger_type == 'type-4' ) {
						/**
						 * Note to code reviewers: It contains inline SVG, which is absolutely safe and this line doesn't need to be escaped.
						 */
						?>
						<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="1.67314" cy="2.17412" r="1.67412" fill="currentColor" />
							<circle cx="7.51738" cy="2.17412" r="1.67412" fill="currentColor" />
							<circle cx="13.3616" cy="2.17412" r="1.67412" fill="currentColor" />
							<circle cx="1.67314" cy="8.01836" r="1.67412" fill="currentColor" />
							<circle cx="7.51738" cy="8.01836" r="1.67412" fill="currentColor" />
							<circle cx="13.3616" cy="8.01836" r="1.67412" fill="currentColor" />
							<circle cx="1.67314" cy="13.8626" r="1.67412" fill="currentColor" />
							<circle cx="7.51738" cy="13.8626" r="1.67412" fill="currentColor" />
							<circle cx="13.3616" cy="13.8626" r="1.67412" fill="currentColor" />
						</svg>
						<?php
					} else {
						echo '<span></span>';
					}
					?>
				</span>

				<span class="rishi-label" <?php echo esc_attr( $has_label_output ); ?>>
					<?php echo esc_html( $trigger_label ); ?>
				</span>
			</a>
		</div>
		<?php
	}
}
