<?php
/**
 * Public area settings and hooks.
 *
 * Handles the public-facing functionality of the plugin.
 *
 * @package Rishi_Companion
 */

namespace Rishi_Companion;

defined( 'ABSPATH' ) || exit;

/**
 * Global Settings for Public Area.
 *
 * This class defines all the settings and hooks
 * used in the public-facing side of the plugin.
 */
class Plugin_Public {

	/**
	 * Constructor.
	 *
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialization.
	 *
	 * Initialize hooks and other settings for the public area.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init() {
		// Initialize hooks.
		$this->init_hooks();

		// Allow 3rd party to remove hooks.
		do_action( 'rishi_companion_public_unhook', $this );

		// Set views if not in customize preview.
		if ( ! is_customize_preview() ) {
			add_action( 'wp', array( $this, 'rishi_companion_set_views' ) );
		}
	}

	/**
	 * Initialize hooks.
	 *
	 * Set up various hooks for the public area.
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_head', array( $this, 'rishi_single_post_schema' ) );
	}

	/**
	 * Enqueue necessary assets.
	 *
	 * Enqueue styles, sripts, and other assets for the public area.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		$dependencies_file_path = plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/public.asset.php';
		$version                = ( ! empty( $dependencies_file_path['version'] ) ) ? $dependencies_file_path['version'] : '';
		wp_enqueue_style( 'rishi-companion-frontend', esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/public.css', array(), $version );

		wp_style_add_data( 'rishi-companion-frontend', 'rtl', 'replace' );

		$localize_data = array(
			'public_url' => esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . '/build',
		);

		wp_localize_script( 'rishi-companion-frontend', 'rishi_companion_data', $localize_data );
	}

	/**
	 * Generate Schema for Single Post.
	 *
	 * Generate the schmea markup for a single post.
	 *
	 * @return void
	 */
	public function rishi_single_post_schema() {
		$enable_schema_org_markup = get_theme_mod( 'enable_schema_org_markup', 'yes' );
		if ( 'yes' === $enable_schema_org_markup && is_singular( 'post' ) ) {
			global $post;
			$custom_logo = get_theme_mod( 'custom_logo' );
			$attachment_id = 0;
			if(!empty($custom_logo) && is_array($custom_logo) && isset($custom_logo['_value']['attachment_id'])) {
				$attachment_id = $custom_logo['_value']['attachment_id'];
			}
			$site_logo   = wp_get_attachment_image_src( $attachment_id, array( 600, 60 ) );

			$images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			$excerpt     = $this->rishi_escape_text_tags( $post->post_excerpt );
			$content     = '' === $excerpt ? mb_substr( $this->rishi_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
			$schema_type = ! empty( $attachment_id ) && has_post_thumbnail( $post->ID ) ? 'BlogPosting' : 'Blog';

			$args = array(
				'@context'         => 'https://schema.org',
				'@type'            => $schema_type,
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id'   => esc_url( get_permalink( $post->ID ) ),
				),
				'headline'         => esc_html( get_the_title( $post->ID ) ),
				'datePublished'    => esc_html( get_the_time( DATE_ISO8601, $post->ID ) ),
				'dateModified'     => esc_html( get_post_modified_time( DATE_ISO8601, __return_false(), $post->ID ) ),
				'author'           => array(
					'@type' => 'Person',
					'name'  => $this->rishi_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) ),
					'url'   => esc_url( get_the_author_meta( 'user_url', $post->post_author ) ),
				),
				'description'      => ( defined( 'WPSEO_VERSION' ) && class_exists( 'WPSEO_Meta' ) ? \WPSEO_Meta::get_value( 'metadesc' ) : $content ),
			);

			$args = apply_filters( 'rishi_companion_single_post_schema', $args );

			if ( has_post_thumbnail( $post->ID ) && is_array( $images ) ) :
				$args['image'] = array(
					'@type'  => 'ImageObject',
					'url'    => $images[0],
					'width'  => $images[1],
					'height' => $images[2],
				);
			endif;

			if ( ! empty( $attachment_id ) && is_array( $site_logo ) ) :
				$args['publisher'] = array(
					'@type'       => 'Organization',
					'name'        => esc_html( get_bloginfo( 'name' ) ),
					'description' => wp_kses_post( get_bloginfo( 'description' ) ),
					'logo'        => array(
						'@type'  => 'ImageObject',
						'url'    => $site_logo[0],
						'width'  => $site_logo[1],
						'height' => $site_logo[2],
					),
				);
			endif;

			echo '<script type="application/ld+json">';
			if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
				echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
			} else {
				echo wp_json_encode( $args );
			}
			echo '</script>';
		}
	}

	/**
	 * Remove new line tags from string.
	 *
	 * @param string $text The text to be processed.
	 * @return string The string after escaping and stripping all tags.
	 */
	public function rishi_escape_text_tags( $text ) {
		return (string) str_replace( array( "\r", "\n" ), '', wp_strip_all_tags( $text ) );
	}

	/**
	 * Function to add the post view count.
	 *
	 * This function adds the post view count and updates the view count meta.
	 *
	 * @param int $post_id Post ID.
	 */
	public function rishi_companion_set_views( $post_id ) {
		if ( in_the_loop() ) {
			$post_id = get_the_ID();
		} else {
			global $wp_query;
			$post_id = $wp_query->get_queried_object_id();
		}
		if ( is_singular( 'post' ) ) {
			$count_key = '_rishi_post_view_count';
			$count     = get_post_meta( $post_id, $count_key, true );
			$count++;
			update_post_meta( $post_id, $count_key, $count );
		}
	}
}
