<?php
/**
 * The admin preview.
 *
 * @link        https://shapedplugin.com/
 * @since      2.4.0
 *
 * @package    Wp_Carousel_Pro
 * @subpackage Wp_Carousel_Pro/admin
 */

namespace ShapedPlugin\WPCarouselPro\Admin\views\preview;

use ShapedPlugin\WPCarouselPro\Frontend\Helper;

/**
 * The admin preview.
 *
 * @package    Wp_Carousel_Pro
 * @subpackage Wp_Carousel_Pro/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */
class Backend_Preview {

	/**
	 * Script and style suffix
	 *
	 * @since 2.4.0
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.4.0
	 */
	public function __construct() {
		$this->wpcp_preview_action();
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function wpcp_preview_action() {
		// admin Preview.
		add_action( 'wp_ajax_sp__wpcp_preview_meta_box', array( $this, 'wpcp_backend_preview' ) );
	}

	/**
	 * Function Backed preview.
	 *
	 * @since 2.4.0
	 */
	public function wpcp_backend_preview() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';// phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'sp_wpcp_metabox_nonce' ) ) {
			return;
		}

		$setting_data = array();
		// XSS ok.
		// No worries, This "POST" requests is sanitizing in the below array map.
		$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] )  : ''; // phpcs:ignore
		parse_str( $data, $setting_data );
		// Preset Layouts.
		$post_id               = intval( $setting_data['post_ID'] );
		$setting_options       = get_option( 'sp_wpcp_settings' );
		$upload_data           = $setting_data['sp_wpcp_upload_options'];
		$shortcode_data        = $setting_data['sp_wpcp_shortcode_options'];
		$preview_section_title = $setting_data['post_title'];

		echo '<style>';
		$carousel_id = $post_id;
		$css_data    = $shortcode_data;
		$dynamic_css = '';
		include WPCAROUSEL_PATH . 'Frontend/css/dynamic/dynamic-style.php';
		include WPCAROUSEL_PATH . 'Frontend/css/dynamic/responsive.php';
		// Load all dynamic CSS styles.
		echo wp_strip_all_tags( $dynamic_css );
		echo '</style>';
		echo Helper::sp_wpcp_html_show( $upload_data, $css_data, $carousel_id, $preview_section_title, true ); // phpcs:ignore
		?>
		<script src="<?php echo esc_url( WPCAROUSEL_URL . 'Frontend/js/bxslider-config.min.js' ); ?>" ></script>
		<script src="<?php echo esc_url( WPCAROUSEL_URL . 'Frontend/js/fancybox-config.min.js' ); ?>" ></script>
		<?php
		die();
	}
}
