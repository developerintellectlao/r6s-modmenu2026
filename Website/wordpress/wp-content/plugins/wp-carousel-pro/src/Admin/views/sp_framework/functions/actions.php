<?php
/**
 * Wp Carousel Pro admin action functions.
 *
 * @link       https://shapedplugin.com
 * @since      3.0.0
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

if ( ! function_exists( 'sp_wpcp_chosen_ajax' ) ) {
	/**
	 *
	 * Chosen Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function sp_wpcp_chosen_ajax() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : null;
		if ( ! wp_verify_nonce( $nonce, 'sp_wpcp_chosen_ajax_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wp-carousel-pro' ) ) );
		}
		$type  = ( ! empty( $_POST['type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';
		$term  = ( ! empty( $_POST['term'] ) ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';
		$query = ( ! empty( $_POST['query_args'] ) ) ? wp_kses_post_deep( $_POST['query_args'] ) : array();// phpcs:ignore

		if ( empty( $type ) || empty( $term ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid term ID.', 'wp-carousel-pro' ) ) );
		}

		$capability = apply_filters( 'sp_wp_carousel_ui_permission', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: You do not have permission to do that.', 'wp-carousel-pro' ) ) );
		}

		// Success.
		$options = SP_WPCP_Framework_Fields::field_data( $type, $term, $query );

		wp_send_json_success( $options );

	}
	add_action( 'wp_ajax_sp_wpcp-chosen', 'sp_wpcp_chosen_ajax' );
}

if ( ! function_exists( 'wpcp_get_option' ) ) {
	/**
	 * Get option
	 *
	 * @param  mixed $option_name option name.
	 * @param  mixed $default default value.
	 * @return statement
	 */
	function wpcp_get_option( $option_name = '', $default = '' ) {
		$options = apply_filters( 'wpcp_get_option', get_option( 'sp_wpcp_settings' ), $option_name, $default );

		if ( isset( $option_name ) && isset( $options[ $option_name ] ) ) {
			return $options[ $option_name ];
		} else {
			return ( isset( $default ) ) ? $default : null;
		}

	}
}

if ( ! function_exists( 'wpcp_get_all_option' ) ) {
	/**
	 *
	 * Get all option
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function wpcp_get_all_option() {
		return get_option( 'sp_wpcp_settings' );
	}
}

if ( ! function_exists( 'sp_wpcp_clean_transient' ) ) {
	/**
	 * Clean transient
	 *
	 * @return void
	 */
	function sp_wpcp_clean_transient() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';// phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'sp_wpcp_options_nonce' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'wp-carousel-pro' ) ) );
		}
		// Success.
		global $wpdb;
		$wp_sitemeta = $wpdb->prefix . 'sitemeta';
		$wp_options  = $wpdb->prefix . 'options';
		if ( is_multisite() ) {
			$wpdb->query( "DELETE FROM {$wp_sitemeta} WHERE `meta_key` LIKE ('%\_site_transient_sp_wpcp_%')" );
		} else {
			$wpdb->query( "DELETE FROM {$wp_options} WHERE `option_name` LIKE ('%\_transient_sp_wpcp_%')" );
		}
		wp_send_json_success();

	}
	add_action( 'wp_ajax_sp_wpcp_clean_transient', 'sp_wpcp_clean_transient' );
}


/**
 * Populate the taxonomy name list to he select option.
 *
 * @return void
 */
function wpcp_get_taxonomies() {
	check_ajax_referer( 'sp_wpcp_metabox_nonce', sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), false );
	$wpcp_post_type = ( ! empty( $_POST['wpcp_post_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wpcp_post_type'] ) ) : '';
	$taxonomy_names = get_object_taxonomies( array( 'post_type' => $wpcp_post_type ), 'names' );

	foreach ( $taxonomy_names as $key => $label ) {
		echo '<option value="' . esc_attr( $label ) . '">' . wp_kses_post( $label ) . '</option>';
	}
	die( 0 );
}
add_action( 'wp_ajax_wpcp_get_taxonomies', 'wpcp_get_taxonomies' );

/**
 * Populate the taxonomy terms list to the select option.
 *
 * @return void
 */
function wpcp_get_terms() {
	check_ajax_referer( 'sp_wpcp_metabox_nonce', sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), false );
	$wpcp_post_taxonomy = ( ! empty( $_POST['wpcp_post_taxonomy'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wpcp_post_taxonomy'] ) ) : '';
	$terms              = get_terms( $wpcp_post_taxonomy );
	foreach ( $terms as $key => $value ) {
		$count = $value->count;
		echo '<option value="' . esc_attr( $value->term_id ) . '">' . wp_kses_post( $value->name . '(' . $count . ')' ) . '</option>';
	}

	die( 0 );
}
add_action( 'wp_ajax_wpcp_get_terms', 'wpcp_get_terms' );

/**
 * Get specific post to the select box.
 *
 * @return void
 */
function wpcp_get_posts() {
	check_ajax_referer( 'sp_wpcp_metabox_nonce', sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ), false ); // phpcs:ignore
	$wpcp_post_type = ( ! empty( $_POST['wpcp_post_type'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wpcp_post_type'] ) ) : '';
	$all_posts      = get_posts(
		array(
			'post_type'      => $wpcp_post_type,
			'posts_per_page' => -1,
		)
	);
	if ( ! is_wp_error( $all_posts ) && ! empty( $all_posts ) ) {
		foreach ( $all_posts as $key => $post_obj ) {
			echo '<option value="' . esc_attr( $post_obj->ID ) . '">' . wp_kses_post( $post_obj->post_title ) . '</option>';
		}
	}
	wp_reset_postdata();
	die( 0 );
}
add_action( 'wp_ajax_wpcp_get_posts', 'wpcp_get_posts' );
