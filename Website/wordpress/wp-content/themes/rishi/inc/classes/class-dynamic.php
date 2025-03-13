<?php
/**
 * Register Dynamic CSS for the backend
 */
namespace Rishi\Dynamic;

use Rishi\Customizer\Dynamic_Styles;

class Dynamic {

	protected static $instance = null;

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
		\add_action( 'rishi_customizer_dynamic_styles_collect_css',array( $this, 'get_dynamic_styles' ) );
	}

	public function get_dynamic_styles( Dynamic_Styles $dynamic_styles_object ){
		
		$defaults       = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
		$options        = array(
			'containerWidth'            => array(
				'selector'     => ':root',
				'variableName' => 'adminSagarContainerWidth',
				'unit'         => '',
				'value'        => get_theme_mod(
					'container_width',
					$defaults['container_width']
				),
				'responsive'   => true,
				'type'         => 'slider',
			)
		);
		foreach ( $options as $key => $option ) {
			$dynamic_styles_object->add( $key, $option );
		}
	}

}

	