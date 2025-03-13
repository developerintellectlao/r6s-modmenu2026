<?php
/**
 * The Elementor Widget of the plugin.
 *
 * @link https://shapedplugin.com
 * @since 3.0.0
 *
 * @package WordPress_Carousel_Pro
 * @subpackage WordPress_Carousel_Pro/admin
 */

namespace ShapedPlugin\WPCarouselPro\Admin\views\ElementAddon;

use ShapedPlugin\WPCarouselPro\Frontend\Helper;

/**
 * Elementor wp carousel shortcode Widget.
 *
 * @since 3.6.2
 */
class Elementor_Addon_Widget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 3.6.2
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sp_wp_carousel_shortcode';
	}

	/**
	 * Get widget title.
	 *
	 * @since 3.6.2
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WP Carousel Pro', 'wp-carousel-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 3.6.2
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wpcp-icon-block';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 3.6.2
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Get all post list.
	 *
	 * @since 3.6.2
	 * @return array
	 */
	public function sp_wp_carousel_pro_post_list() {
		$post_list            = array();
		$sp_wp_carousel_posts = new \WP_Query(
			array(
				'post_type'      => 'sp_wp_carousel',
				'post_status'    => 'publish',
				'posts_per_page' => 9999,
			)
		);
		$posts                = $sp_wp_carousel_posts->posts;
		foreach ( $posts as $post ) {
			$post_list[ $post->ID ] = $post->post_title;
		}
		krsort( $post_list );
		return $post_list;
	}

	/**
	 * Get first shortcode.
	 *
	 * @return array
	 */
	public function sp_wp_carousel_pro_get_first_shortcode() {
		$first_shortcode = array();
		foreach ( $this->sp_wp_carousel_pro_post_list() as $key => $value ) {
			$first_shortcode[] = $key;
		}
		rsort( $first_shortcode );
		return $first_shortcode[0];
	}

	/**
	 * Controls register.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'wp-carousel-pro' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'sp_wp_carousel_pro_shortcode',
			array(
				'label'       => __( 'WP Carousel Shortcode(s)', 'wp-carousel-pro' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => '',
				'options'     => $this->sp_wp_carousel_pro_post_list(),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Render wp carousel shortcode widget output on the frontend.
	 *
	 * @since 3.6.2
	 * @access protected
	 */
	protected function render() {

		$settings                 = $this->get_settings_for_display();
		$sp_wp_carousel_shortcode = $settings['sp_wp_carousel_pro_shortcode'];

		if ( '' === $sp_wp_carousel_shortcode ) {
			echo '<div style="text-align: center; margin-top: 0; padding: 10px" class="elementor-add-section-drag-title">Select a shortcode</div>';
			return;
		}

		$post_id = $sp_wp_carousel_shortcode;

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			// Preset Layouts.
			$upload_data        = get_post_meta( $post_id, 'sp_wpcp_upload_options', true );
			$shortcode_data     = get_post_meta( $post_id, 'sp_wpcp_shortcode_options', true );
			$main_section_title = get_the_title( $post_id );
			// Show the full html of wp carousel.
			echo Helper::sp_wpcp_html_show( $upload_data, $shortcode_data, $post_id, $main_section_title ); // phpcs:ignore
			?>
			<script>
				jQuery('#wpcp-preloader-' + <?php echo esc_attr( $post_id ); ?>).animate({ opacity: 0, zIndex: -99 }, 600);
			</script>
			<script src="<?php echo esc_url( WPCAROUSEL_URL . 'Frontend/js/bxslider-config.min.js' ); ?>" ></script>
			<script src="<?php echo esc_url( WPCAROUSEL_URL . 'Frontend/js/fancybox-config.min.js' ); ?>" ></script>
			<script src="<?php echo esc_url( WPCAROUSEL_URL . 'Frontend/js/wp-carousel-pro-public.min.js' ); ?>" ></script>
			<?php
		} else {
			echo do_shortcode( ' [sp_wpcarousel id="' . esc_attr( $post_id ) . '"]' );
		}

	}

}
