<?php
/**
 * Class Image.
 */
namespace Rishi\Customizer\Header\Elements;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Image extends Abstracts\Builder_Element {
	public function get_id() {
		return 'image';
	}

	public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'Image', 'rishi' );
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
	 * @return array get options
	 */
	public function get_options() {
		$options = array(
			'header_hide_' . $this->get_id() => array(
				'label'               => false,
				'control'             => ControlTypes::HIDDEN,
				'value'               => false,
				'disableRevertButton' => true,
				'help'                => __( 'Hide', 'rishi' ),
			),
			'rishi_header_image'   => array(
				'label'        => __( 'Upload Image', 'rishi' ),
				'control'      => ControlTypes::IMAGE_UPLOADER,
				'emptyLabel'   => __( 'Select Image', 'rishi' ),
				'filledLabel'  => __( 'Change Image', 'rishi' ),
				'divider'      => 'bottom',
				'value'        => '',
				'attr'         => array( 'data-type' => 'small' ),
			),
			'header_image_max_width'         => array(
				'label'      => __( 'Image Max-Width', 'rishi' ),
				'divider'    => 'bottom',
				'control'    => ControlTypes::INPUT_SLIDER,
				'value'      => array(
					'desktop' => '150px',
					'tablet'  => '150px',
					'mobile'  => '150px',
				),
				'units'  => \Rishi\Customizer\Helpers\Basic::get_units(
					array(
						array(
							'unit' => 'px',
							'min'  => 0,
							'max'  => 300,
						),
					)
				),
				'responsive' => true,
			),
			'header_image_link'  => array(
				'label'   => __( 'Image Link', 'rishi' ),
				'control' => ControlTypes::INPUT_TEXT,
				'divider' => 'bottom',
				'design'  => 'block',
				'value'   => '',
				'type'    => 'link',
			),
			'header_image_target'            => array(
				'label'   => __( 'Open New Tab', 'rishi' ),
				'control' => ControlTypes::INPUT_SWITCH,
				'divider' => 'bottom',
				'value'   => 'no',

			),
			'rel_attributes'  => array(
				'label'      => __( 'Rel Attributes', 'rishi' ),
				'divider'    => 'bottom',
				'help'       => __( 'Add the rel attribute for the link of your button.', 'rishi' ),
				'control'    => ControlTypes::INPUT_SELECT,
				'isMultiple' => true,
				'value'      => array( 'header_image_ed_nofollow', 'header_image_ed_sponsored' ),
				'view'       => 'text',
				'choices'    => \Rishi\Customizer\Helpers\Basic::ordered_keys(
					array(
						'header_image_ed_nofollow'   => __( 'Nofollow', 'rishi' ),
						'header_image_ed_sponsored'  => __( 'Sponsored', 'rishi' ),
						'header_image_ed_noopener'   => __( 'Noopener', 'rishi' ),
						'header_image_ed_noreferrer' => __( 'Noreferrer', 'rishi' ),
					)
				),
				'help' => __( 'Choose the Rel Attributes', 'rishi' ),
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
		$header_default = \Rishi\Customizer\Helpers\Defaults::get_header_defaults();

		$header_image_max_width = $this->get_mod_value( 'header_image_max_width', $header_default['header_image_max_width'] );

		return array(
			'header_image_max_width' => array(
				'selector'     => '.header-image-section',
				'variableName' => 'max-width',
				'value'        => $header_image_max_width,
				'responsive'   => true,
				'type'         => 'slider',
			),
		);
	}

	/**
	 * Renders function
	 * @param string $desktop
	 * @return void
	 */
	public function render( $device = 'desktop') {

		$header_image = $this->get_mod_value( 'rishi_header_image' );
		if ( ! empty( $header_image ) && is_array( $header_image ) ) {
			$attachment_id = $header_image['_value']['attachment_id'];
		}

		$header_image_link = $this->get_mod_value( 'header_image_link' );
		$image_url         = ! empty( $header_image_link ) ? esc_url( $header_image_link ) : '';

		$ed_target     = $this->get_mod_value( 'header_image_target', 'no' );
		$target_output = $ed_target === 'yes' ? 'target="_blank"' : '';
		$rel           = $ed_target === 'yes' ? 'noopener noreferrer' : '';

		$rel_attributes = $this->get_mod_value( 'rel_attributes', array( 'header_image_ed_nofollow', 'header_image_ed_sponsored' ) );

		// Ensure $rel_attributes is an array.
		if ( ! is_array( $rel_attributes ) ) {
			$rel_attributes = explode( ' ', $rel_attributes );
		}

		// Remove the prefix 'header_image_ed_' from each element in the array.
		$rel_attributes = array_map( function( $value ) {
			return str_replace( 'header_image_ed_', '', $value );
		}, $rel_attributes);

		// If $rel is not an array, convert it to an array.
		if ( ! is_array( $rel ) ) {
			$rel = explode( ' ', $rel );
		}

		// Remove any duplicate between $rel and $rel_attributes.
		$rel = array_unique( array_merge( $rel, $rel_attributes ) );

		// Convert $rel back to string.
		$rel = implode( ' ', $rel );

		?>
		<div id="rishi-header-image" class="header-image-section">
			<?php
			if ( $header_image && $attachment_id ) {
				?>
				<a
					href="<?php echo $image_url; ?>"
					class="image-wrapper"
					<?php echo $target_output; ?>
					rel ="<?php echo esc_attr( $rel ); ?>">
					<figure>
						<?php echo wp_get_attachment_image( $attachment_id, 'full' ); ?>
					</figure>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
}
