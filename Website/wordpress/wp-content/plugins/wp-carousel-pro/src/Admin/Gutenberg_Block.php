<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPCarouselPro\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use ShapedPlugin\WPCarouselPro\Admin\views\GutenbergBlock\Gutenberg_Block_Init;

if ( ! class_exists( 'Gutenberg_Block ' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class Gutenberg_Block {
		/**
		 * Block Initializer.
		 */
		public function __construct() {
			new Gutenberg_Block_Init();
		}

	}
}
