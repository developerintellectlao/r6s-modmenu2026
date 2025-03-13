<?php
/**
 * Dynamic Blocks rendering.
 *
 * @package Rishi_Companion\Blocks
 */

namespace Rishi_Companion\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Renders Block.
 *
 * @param array  $attributes Block attributes.
 * @param string $block      Block name.
 *
 * @return string Rendered block content.
 */
function render_block( $attributes, $block ) {
	ob_start();

	include plugin_dir_path( __FILE__ ) . 'templates/' . $block . '.php';

	return ob_get_clean();
}

/**
 * Register the blocks.
 *
 * @return void
 */
function register_blocks() {
	foreach ( array(
		'recent-posts',
		'posts-tab',
		'popular-posts',
		'categories',
	) as $block_name ) {
		// Register the block.
		\register_block_type(
			"rishi-blocks/{$block_name}",
			array(
				'render_callback' => function( $attributes ) use ( $block_name ) {
					return call_user_func( __NAMESPACE__ . "\\render_block", $attributes, $block_name );
				},
			)
		);
	}
}

add_action( 'init', __NAMESPACE__ . '\register_blocks' );

/**
 * Filter REST API query.
 *
 * @param array           $args    Query arguments.
 * @param WP_REST_Request $request Request object.
 *
 * @return array Filtered query arguments.
 */
function filter_rest_api_query( $args, $request ) {
	if ( 'yes' === $request->get_param( 'rishi_blocks' ) ) {
		$order_by = $request->get_param( 'rishi_orderby' );
		if ( in_array( $order_by, array( 'views', 'comments' ), true ) ) {
			switch ( $order_by ) {
				case 'views':
					$args['meta_key'] = '_rishi_post_view_count';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					break;

				case 'comments':
					$args['orderby'] = 'comment_count';
					$args['order']   = 'DESC';
					break;
			}
		}
	}

	return $args;
}

add_filter( 'rest_post_query', __NAMESPACE__ . '\filter_rest_api_query', 10, 2 );

/**
 * Enqueue editor assets.
 *
 * @return void
 */
function enqueue_editor_assets() {
	$dependencies_file_path = plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/blocks.asset.php';
	if ( file_exists( $dependencies_file_path ) ) {
		$blocks_deps     = include_once $dependencies_file_path;
		$js_dependencies = ( ! empty( $blocks_deps['dependencies'] ) ) ? $blocks_deps['dependencies'] : array();
		$version         = ( ! empty( $blocks_deps['version'] ) ) ? $blocks_deps['version'] : '';

		wp_enqueue_script(
			'rishi-companion-blocks',
			esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/blocks.js',
			$js_dependencies,
			$version,
			true
		);

		wp_localize_script(
			'rishi-companion-blocks',
			'rishiCompanionBlocksData',
			array(
				'pluginUrl' => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ),
			)
		);

		// Styles.
		wp_enqueue_style(
			'rishi-companion-blocks',
			esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/blocks.css',
			array(),
			$version
		);
	}
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_assets' );
