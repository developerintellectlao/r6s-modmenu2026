<?php
/**
 * Rishi Theme Customizer
 *
 * @package Rishi
 */
namespace Rishi\Customizer;

final class Customize {

	/**
	 * Instance of this class.
	 *
	 * @var object|null
	 */
	protected static $instance = null;

	/**
	 * Instance of Customize_Register class.
	 *
	 * @var object
	 */
	public $customize_manager;


	public $header_builder;

	public $footer_builder;

	public $css_printer;

	public $dynamic_style;

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Hooks and Filters.
	 */
	private function init() {

		$this->customize_manager = new Customize_Manager();
		$this->header_builder    = new Builder\Header();
		$this->footer_builder    = new Builder\Footer();
		$this->dynamic_style     = new Dynamic_Styles();

		$this->customize_manager->set( 'header_builder', $this->header_builder );
		$this->customize_manager->set( 'footer_builder', $this->footer_builder );

		\do_action( 'rishi_customize' . __FUNCTION__, $this );
	}

}
