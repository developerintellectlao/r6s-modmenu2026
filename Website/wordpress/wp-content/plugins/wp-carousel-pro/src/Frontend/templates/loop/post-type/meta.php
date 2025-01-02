<?php
/**
 * Post meta
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/post-type/meta.php
 *
 * @package WP_Carousel_Pro
 */

// Post Category over title.
if ( 'post' === $wpcp_post_type || ( 'post' === get_post_type( get_the_ID() ) ) ) {
	$categories         = get_the_category( get_the_ID() );
	$count_category     = count( $categories );
	$wpcp_post_cat_term = '';
	if ( ! empty( $categories ) ) {
		foreach ( $categories as $key => $category ) {
			$wpcp_post_cat_term .= '<li class="post-categories"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ) . '">' . esc_html( $category->name );
			if ( ( $count_category - 1 ) !== $key ) {
				$wpcp_post_cat_term .= apply_filters( 'wpcp_post_cat_term_separator', ', ' );
			}
			$wpcp_post_cat_term .= '</a></li>';
		}
	}
} else {
	if ( 'product' === get_post_type( get_the_ID() ) ) {
		$post_taxonomy = 'product_cat';
	}
	$post_taxonomy      = apply_filters( 'wpcp_custom_post_cat', $post_taxonomy, $post_id );
	$wpcp_post_cat_term = sprintf( '<p class="wpcp-post-cat">%1$s</p>', get_the_term_list( get_the_ID(), $post_taxonomy, '', ', ' ) );
}
$post_cat_term = $show_post_category ? $wpcp_post_cat_term : '';


// Author name.
$the_post_author_name = sprintf( '<li><a href="%1$s">%2$s%3$s</a></li>', get_author_posts_url( get_the_author_meta( 'ID' ) ), __( ' By ', 'wp-carousel-pro' ), ucfirst( get_the_author() ) );

$post_author_name = $show_post_author ? $the_post_author_name : '';
// Post updated date.
$post_update_date = sprintf( '<time class="updated wpcp-hidden" datetime="%1$s">%2$s</time>', get_the_modified_date( 'c' ), get_the_modified_date() );
$wpcp_post_date   = sprintf( '<li><time class="entry-date published updated" datetime="%1$s">%2$s%3$s</time></li>', get_the_date( 'c' ), __( ' On ', 'wp-carousel-pro' ), get_the_date() );
$post_date        = $show_post_date ? $wpcp_post_date : '';

// Post term.
if ( 'post' === $wpcp_post_type || ( 'post' === get_post_type( get_the_ID() ) ) ) {
	$wpcp_post_tags = sprintf( '<li>%1$s</li>', get_the_tag_list( '', ', ' ) );
} else {
	if ( 'product' === get_post_type( get_the_ID() ) ) {
		$post_taxonomy = 'product_tag';
	}
	$post_tag_taxonomy = apply_filters( 'wpcp_custom_post_tag', $post_taxonomy, $post_id );
	$wpcp_post_tags    = sprintf( '<li>%1$s</li>', get_the_term_list( get_the_ID(), $post_tag_taxonomy, '', ', ' ) );
}
$post_tags = $show_post_tags ? $wpcp_post_tags : '';

// Post comment number.
$wpcp_comments  = '';
$comment_number = get_comments_number();
if ( comments_open() && $show_post_comment ) {
	if ( '0' === $comment_number ) {
		$comments_num = __( '0 comment', 'wp-carousel-pro' );
	} elseif ( '1' === $comment_number ) {
		$comments_num = __( '1 comment', 'wp-carousel-pro' );
	} else {
		$comments_num = $comment_number . __( ' comments', 'wp-carousel-pro' );
	}
	$wpcp_comments = sprintf( '<li><a href="%1$s"> %2$s</a></li>', get_comments_link(), $comments_num );
}

$underline = '';
if ( $show_post_category && $show_post_author ) {
	$underline = ' - ';
}

// All post meta.
if ( $show_post_date || $show_post_author || $show_post_tags || $show_post_comment || $show_post_category ) {
	?>
	<ul class="wpcp-post-meta">
		<?php echo $post_cat_term . ' ' . $underline . $post_author_name . $post_date . $post_tags . $wpcp_comments; ?>
	</ul>
	<?php
}
