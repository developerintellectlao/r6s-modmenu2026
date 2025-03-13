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

if ( ! class_exists( 'SP_EAP_Field_license' ) ) {
	/**
	 *
	 * Field: license
	 *
	 * @since 3.3.16
	 * @version 3.3.16
	 */
	class SP_EAP_Field_license extends SP_EAP_Fields {

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
				<div class="sp-easy-accordion-license text-center">
					<h3><?php esc_html_e( 'You\'re using Easy Accordion Lite - No License Needed. Enjoy', 'easy-accordion-free' ); ?>! 🙂</h3>
					<p><?php esc_html_e( 'Upgrade to Easy Accordion Pro and unlock all the features.', 'easy-accordion-free' ); ?></p>
					<div class="sp-easy-accordion-license-area">
						<div class="sp-easy-accordion-license-key">
							<div class="eap-upgrade-button"><a href="https://easyaccordion.io/pricing/?ref=1" target="_blank"><?php esc_html_e( 'Upgrade To Pro Now', 'easy-accordion-free' ); ?></a></div>
						</div>
					</div>
				</div>
				<?php
				echo wp_kses_post( $this->field_after() );
		}
	}
}
