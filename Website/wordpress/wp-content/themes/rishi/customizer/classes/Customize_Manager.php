<?php
/**
 * Class for customize register.
 *
 */
namespace Rishi\Customizer;

use Rishi\Customizer\Section_Group_Title;

class Customize_Manager {

	/**
	 * WP_Customize_Manager instance.
	 */
	public $wp_customize;

	public $sections = null;

	public $header_builder;

	public $footer_builder;
	public $header_elements;

	/**
	 * Constructor function.
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->includes();
		$this->set_sections();
		$this->init_hooks();
	}

	/**
	 * Includes necessary files.
	 */
	private function includes() {
	}


	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'admin_init', array( $this, 'register_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );
	}

	/**
	 * Remove sections.
	 */
	private function _remove_sections() {
		$this->wp_customize->remove_section( 'colors' );
		$this->wp_customize->remove_section( 'background_image' );
		$this->wp_customize->remove_section( 'header_image' );
	}

	/**
	 * Remove controls.
	 */
	private function _remove_controls() {
		$this->wp_customize->remove_control( 'header_image' );
		$this->wp_customize->remove_control( 'custom_logo' );
		$this->wp_customize->remove_control( 'blogname' );
		$this->wp_customize->remove_control( 'blogdescription' );

		if ( rishi_is_woocommerce_activated() ) {
			$this->wp_customize->remove_control( 'woocommerce_single_image_width' );
			$this->wp_customize->remove_control( 'woocommerce_thumbnail_image_width' );
			$this->wp_customize->remove_control( 'woocommerce_thumbnail_cropping' );
		}
	}

	/**
	 * Register sections and controls in the customizer.
	 */
	private function _register() {
		$this->_remove_sections();
		$this->_remove_controls();

		$this->wp_customize->add_section(
			new Section_Group_Title(
				$this->wp_customize,
				'core',
				array(
					'title' => '',
					'priority' => 15,
				)
			)
		);

		$this->register_sections();
	}

	public function customize_controls_enqueue_scripts() {
		$template_name = \wp_get_theme()->template;
		wp_enqueue_script( $template_name, '_modules' );
		wp_enqueue_script( $template_name );
		wp_enqueue_style( $template_name );

		/**
		 * Ajax functionality to flush local fonts folder
		 */
		if ( get_theme_mod( 'local_google_fonts', 'no' ) === 'yes' ) {
			wp_enqueue_script( 'rishi-flush', get_template_directory_uri() . '/js/flush.js', array( $template_name ), wp_rand(), true );
			wp_localize_script(
				'rishi-flush',
				'rishi_cdata',
				array(
					'nonce' => wp_create_nonce( 'rishi-local-fonts-flush' ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'flushit' => __( 'Successfully Flushed!', 'rishi' ),
				)
			);
		}
	}

	/**
	 * Enqueue scripts and styles for the customizer.
	 */
	public function register_scripts() {
		$template_name = wp_get_theme()->template;

		$rishi_fonts = get_transient( 'rishi_google_fonts' );
		if ( false === $rishi_fonts ) {
			$fonts = rishi_get_all_google_fonts_from_json();
			set_transient( 'rishi_google_fonts', $fonts, 7 * DAY_IN_SECONDS );
			$rishi_fonts = $fonts;
		}
		$rishi_fonts = apply_filters( 'rishi-fonts', $rishi_fonts );

		$modules_assets = require_once get_template_directory() . '/customizer/dist/modules.asset.php';
		wp_register_script( $template_name . '_modules', get_template_directory_uri() . '/customizer/dist/modules.js', $modules_assets['dependencies'], $modules_assets['version'], true );
		$customizer_assets = require_once get_template_directory() . '/customizer/dist/customizer.asset.php';
		wp_register_script( $template_name, get_template_directory_uri() . '/customizer/dist/customizer.js', array_merge( $customizer_assets['dependencies'], [ $template_name . '_modules' ] ), $modules_assets['version'], true );
		wp_register_style( $template_name, get_template_directory_uri() . '/customizer/dist/customizer.css', array(), wp_rand() );

		$theme_data = array(
			'customizer_reset_none' => wp_create_nonce( 'rara-customizer-reset' ),
			'builder_data' => array(
				'header' => $this->header_builder->get_items(),
				'footer' => $this->footer_builder->get_items(),
				'header_data' => array( 'header_options' => $this->header_builder->get_options() ),
				'footer_data' => array( 'footer_options' => $this->footer_builder->get_options() ),
				'secondary_items' => array(
					'header' => $this->header_builder->get_items(),
					'footer' => $this->footer_builder->get_items(),
				),
			),
			'all_mods' => get_theme_mods(),
			'gradients' => get_theme_support( 'editor-gradient-presets' )[0],
			'use_new_widgets' => ! ! get_theme_support( 'widgets-block-editor' ),
			'has_child_theme' => false,
			'is_parent_theme' => ! wp_get_theme()->parent(),
			'fonts' => $rishi_fonts,
		);
		$inline_script = ';(function(){
			document.body.dataset.theme = "%s";
			window[\'%1$s\'] = window[\'%1$s\'] || {};
			window[\'%1$s\'][\'themeData\'] = %2$s})();';
		wp_add_inline_script( $template_name, sprintf( $inline_script, $template_name, wp_json_encode( $theme_data ) ), 'before' );
	}

	public function load_controls( $wp_customize ) {
	}

	/**
	 * Customize register.
	 */
	public function customize_register( $wp_customize ) {
		$this->load_controls( $wp_customize );
		$this->wp_customize = $wp_customize;
		$this->_register();
	}

	/**
	 * Register sections in the customizer.
	 */
	protected function register() {
		ksort( $this->sections );
		foreach ( $this->sections as $section ) {
			$section->set_wp_customize( $this->wp_customize );
			$section->register();
		}
	}

	/**
	 * Set sections for the customizer.
	 */
	private function set_sections() {
		if ( ! is_null( $this->sections ) ) {
			return;
		}

		$this->sections = array();

		$sections_dirs = apply_filters( 'rishi_customizer_sections_directory', array(
			'Rishi\\Customizer\\Sections\\' => __DIR__ . '/Sections/' )
		);

		foreach ( $sections_dirs as $namespace => $sections_dir ) {

			$iterator = new \RecursiveDirectoryIterator( $sections_dir );

			foreach ( $iterator as $file ) {
				if ( $file->isFile() ) {
					if ( 'php' === $file->getExtension() ) {
						$class_name = $namespace . pathinfo( $file->getFilename(), PATHINFO_FILENAME );

						if ( ! $class_name::is_enabled() ) {
							continue;
						}

						$this->sections[ $class_name::get_order()] = new $class_name();
					}
				}
			}
		}

		do_action( 'rishi_customizer_' . __FUNCTION__, $this );
	}

	/**
	 * Get sections for the customizer.
	 *
	 * @return array Sections for the customizer.
	 */
	public function get_sections() {
		return $this->sections;
	}

	/**
	 * Register sections in the customizer.
	 */
	private function register_sections() {

		do_action( __NAMESPACE__ . '_before_' . __FUNCTION__, $this->sections );

		$this->register();

		do_action( __NAMESPACE__ . '_after_' . __FUNCTION__, $this->sections );
	}

	/**
	 * Set property value.
	 *
	 * @param string $property Property name.
	 * @param mixed  $value    Property value.
	 */
	public function set( $property, $value ) {
		$this->{$property} = $value;
	}

}
