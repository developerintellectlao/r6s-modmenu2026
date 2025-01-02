<?php
/**
 * Framework license fields file.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    easy-accordion-free
 * @subpackage easy-accordion-free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! class_exists( 'SP_WPCF_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 3.3.16
	 * @version 3.3.16
	 */
	class SP_WPCF_Field_license extends SP_WPCF_Fields {

		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {
			echo wp_kses_post( $this->field_before() );
			?>
				<div class="wpcf-field-license text-center">
					<h3><?php esc_html_e( 'You\'re using WP Carousel Lite - No License Needed. Enjoy', 'wp-carousel-free' ); ?>! 🙂</h3>
					<p><?php esc_html_e( 'Upgrade to WP Carousel Pro and unlock all the features.', 'wp-carousel-free' ); ?></p>
					<div class="wpcf-field-license-area">
						<div class="wpcf-field-license-key">
							<div class="wpcf-upgrade-button"><a href="https://wpcarousel.io/pricing/?ref=1" target="_blank"><?php esc_html_e( 'Upgrade To Pro Now', 'wp-carousel-free' ); ?></a></div>
						</div>
					</div>
				</div>
				<?php
				echo wp_kses_post( $this->field_after() );
		}
	}
}
