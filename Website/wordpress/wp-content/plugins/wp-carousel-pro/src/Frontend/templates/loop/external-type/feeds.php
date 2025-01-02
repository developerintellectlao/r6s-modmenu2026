<?php
/**
 * The image carousel template.
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Get first image from feed content if the feature image is not found.
if ( ! $thumb_src ) {
	// Use regular expression to extract the first image URL.
	preg_match( '/<img.*?src=["\'](https:\/\/[^"\']+)["\'].*?>/i', $item->get_content(), $matches );

	// Check if a match is found.
	if ( isset( $matches[1] ) ) {
		$thumb_src = $matches[1];
	}
}

?>
<div class="<?php echo esc_attr( $grid_column ); ?>">
	<div class="wpcp-single-item wpcp-rss-feed">
		<?php if ( ! empty( $thumb_src ) ) { ?>
		<div class="wpcp-slide-image">
			<a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $link_target ); ?>" <?php echo $image_link_nofollow; ?>>
				<img src="<?php echo esc_url( $thumb_src ); ?>" alt="">
			</a>
		</div>
		<?php } ?>
		<div class="wpcp-all-captions">
			<?php if ( $show_feed_title ) { ?>
			<h3 class="wpcp-post-title">
				<a class="feed_title" href="<?php echo esc_url( $item->get_permalink() ); ?>"
					title="<?php printf( __( 'Posted %s', 'wp-carousel-pro' ), $pubDate ); ?>"
					target="<?php echo esc_attr( $link_target ); ?>"
				<?php echo $image_link_nofollow; ?>><?php echo $title; ?></a>
			</h3>
			<div class="wpcp-post-meta"><?php printf( __( 'Posted %s', 'wp-carousel-pro' ), $pubDate ); ?></div>
			<?php } ?>
			<?php
			if ( $wpcp_feed_content_show ) {
				?>

			<div class="wpcp-post-content"><p><?php echo wp_kses_post( $description ); ?></p></div>
			<?php } ?>
			<?php if ( $show_feed_read_more ) { ?>
			<div class="sp-wpcp-read-more">
				<a class="wpcp_readmore" href="<?php echo $link; ?>" target="<?php echo esc_attr( $link_target ); ?>" <?php echo $image_link_nofollow; ?>> <?php echo wp_kses_post( $wpcp_feed_readmore_text ); ?></a>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
