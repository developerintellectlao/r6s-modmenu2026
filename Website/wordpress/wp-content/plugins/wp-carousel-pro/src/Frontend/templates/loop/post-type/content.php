<?php

/**
 * Post Content
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/post-type/content.php
 *
 * @package WP_Carousel_Pro
 */

if ( $show_post_content ) {
	// Post Content.
	if ( 'excerpt' === $wpcp_content_type ) {
		// Excerpt.
		$wpcp_post_content = get_the_excerpt();
	} elseif ( 'content' === $wpcp_content_type ) {
		// Full content.
		$post_content      = apply_filters( 'the_content', get_the_content() );
		$wpcp_post_content = $post_content;
	} else {
		// Limited content.
		$post_content      = apply_filters( 'the_content', get_the_content() );
		$wpcp_post_content = wp_trim_words( $post_content, $wpcp_word_limit, '...' );
	}
	?>
<div class="wpcp-post-content"><?php echo wp_kses_post( $wpcp_post_content ); ?></div>
<?php } if ( $show_read_more && 'content' !== $wpcp_content_type ) { ?>
	<div class="sp-wpcp-read-more">
	<a class="wpcp_readmore" href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_kses_post( $post_read_more_text ); ?> </a>
	</div>
<?php } ?>
