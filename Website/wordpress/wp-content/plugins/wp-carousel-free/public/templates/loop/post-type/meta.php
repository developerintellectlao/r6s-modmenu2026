<?php
/**
 * Post meta
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-free/templates/loop/post-type/meta.php
 *
 * @since   2.3.4
 * @package WP_Carousel_Free
 * @subpackage WP_Carousel_Free/public/templates
 */
// Post comment number.
$wpcp_comments  = '';
$comment_number = get_comments_number();
if ( comments_open() && $show_post_comment ) {
	if ( '0' === $comment_number ) {
		$comments_num = __( '0 Comment', 'wp-carousel-free' );
	} elseif ( '1' === $comment_number ) {
		$comments_num = __( '1 Comment', 'wp-carousel-free' );
	} else {
		$comments_num = $comment_number . __( ' Comments', 'wp-carousel-free' );
	}
	// Prepare the comment count link for display.
	// This formats the comment count into a list item with a link to the comments section.
	$wpcp_comments = sprintf( '<li><a href="%1$s"> %2$s</a></li>', get_comments_link(), $comments_num );
}



if ( $show_post_date || $show_post_author || $show_post_comment ) {
	?>
	<ul class="wpcp-post-meta">
		<?php if ( $show_post_author ) { ?>
			<li><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php printf( __( 'By %s', 'wp-carousel-free' ), get_the_author() ); ?></a></li>
		<?php } ?>
		<?php if ( $show_post_date ) { ?>
			<li><time class="entry-date published updated" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"> <?php printf( __( 'On %s', 'wp-carousel-free' ), get_the_date() ); ?></time></li>
			<?php
		}
			echo wp_kses_post( $wpcp_comments );
		?>
	</ul>
	<?php
}
