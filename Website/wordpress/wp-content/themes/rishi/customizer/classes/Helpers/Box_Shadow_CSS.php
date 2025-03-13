<?php
/**
 * Box Shadow CSS Helper Class.
 */
namespace Rishi\Customizer\Helpers;

class Box_Shadow_CSS {
	public static function box_shadow_value( $args = [] ) {
		return \wp_parse_args(
			$args,
			[
				'enable'   => true,
				'inset'    => false,
				'h_offset' => '0px',
				'v_offset' => '5px',
				'blur'     => '20px',
				'spread'   => '0px',
				'color' => 'rgba(44,62,80,0.2)',
			]
		);
	}
}
