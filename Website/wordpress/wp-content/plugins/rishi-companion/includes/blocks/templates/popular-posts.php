<?php
/**
 * Dynamic Blocks rendering.
 *
 * @package Rishi_Companion
 */

// Enqueue the public styles for the blocks.
wp_enqueue_style( 'rishi-companion-blocks-public' );

// Define the expected parameters for the popular posts.
$expected_params = array(
	'popularPostLabel'         => __( 'Popular Posts', 'rishi-companion' ),
	'popularTitleSelector'     => 'h2',
	'popularPostCount'         => 3,
	'layoutStyle'              => 'layout-type-1',
	'openInNewTab'             => false,
	'popularPostsType'         => 'views',
	'popularPostShowThumbnail' => true,
	'popularImageSize'         => 'default',
	'popularPostShowDate'      => true,
	'popularPostViewCount'     => false,
	'popularPostCommentCount'  => false,
);

// Merge the expected parameters with the attributes.
$data = rishi_companion_list( array_keys( $expected_params ), array_merge( $expected_params, $attributes ) );

// Extract the data into variables.
list( $label, $title_selector, $post_count, $style, $new_tab, $popular_post_type, $show_thumbnail, $popular_image_size, $show_post_date, $show_view_count, $show_comment_count ) = $data;

// Initialize the query arguments.
$query_args = array();

// Set the query arguments based on the popular post type.
if ( 'comments' === $popular_post_type ) {
	$query_args['orderby'] = 'comment_count';
	$query_args['order']   = 'DESC';
} else {
	$query_args['meta_key'] = '_rishi_post_view_count';
	$query_args['orderby']  = 'meta_value_num';
	$query_args['order']    = 'DESC';
}

// Set the remaining query arguments.
$query_args['post_type']      = 'post';
$query_args['post_status']    = 'publish';
$query_args['posts_per_page'] = $post_count;

// Execute the query.
$posts_query = get_posts( $query_args );

// Determine the image size.
if ( 'full_size' === $popular_image_size ) {
	$image_size = 'full';
} else {
	$image_size = ( 'layout-type-1' === $style ) ? 'thumbnail' : 'large';
}
?>
<section id="rishi_popular_posts" class="rishi_sidebar_widget_popular_post">
	<?php if ( $label ) { ?>
		<<?php echo esc_html( $title_selector ); ?> class="widget-title" itemProp="name"><span><?php echo esc_html( $label ); ?></span></<?php echo esc_html( $title_selector ); ?>>
	<?php } ?>
	<?php if ( isset( $posts_query[0] ) ) { ?>
		<ul id="rishi-popularpost-wrapper" class="<?php echo esc_attr( $style ); ?>">
		<?php
		foreach ( $posts_query as $_post ) :
			$thumbnail_url = get_post_thumbnail_id( $_post->ID );
			$post_title    = get_the_title( $_post );
			$post_content  = get_the_content( null, false, $_post );
			$author        = get_the_author_meta( 'display_name', $_post->post_author );
			$post_link     = get_permalink( $_post );
			$post_date     = get_the_date( '', $_post );
			$post_views    = (int) get_post_meta( $_post->ID, '_rishi_post_view_count', true );
			$comment_count = (int) $_post->comment_count;
			$categories    = get_the_category( $_post->ID );
			?>
			<li>
				<?php
				$show_thumbnail && printf(
					'<a target="%1$s" rel="noopener" href="%2$s" class="post-thumbnail %3$s">%4$s</a>',
					esc_attr( $new_tab ) ? '_blank' : '_self',
					esc_url( $post_link ),
					$thumbnail_url ? '' : 'fallback-img',
					$thumbnail_url ? wp_get_attachment_image( $thumbnail_url, esc_attr( $image_size ) ) : ''
				);
				?>
				<div class="widget-entry-header">
					<?php
					isset( $categories[0] ) && printf(
						'<span class="cat-links">%s</span>',
						array_reduce(
							$categories,
							function( $carry, $_category ) use ( $new_tab ) {
								return $carry .= sprintf(
									'<a target="%1$s" rel="noopener" href="%2$s">%3$s</a>',
									esc_attr( $new_tab ) ? '_blank' : '_self',
									esc_url( get_category_link( $_category->term_id ) ),
									esc_html( $_category->name )
								);
							},
							''
						)
					);

					printf(
						'<h3 class="entry-title"><a target="%1$s" rel="noopener" href="%2$s">%3$s</a></h3>',
						esc_attr( $new_tab ) ? '_blank' : '_self',
						esc_url( $post_link ),
						esc_html( $post_title )
					);
					?>
					<div class="entry-meta">
						<?php
						// Datetime.
						$show_post_date && printf(
							'<span class="posted-on">
								<a target="%1$s" href="%2$s" rel="noopener">
									<time dateTime="%3$s">%4$s</time>
								</a>
							</span>',
							esc_attr( $new_tab ) ? '_blank' : '_self',
							esc_url( esc_url( $post_link ) ),
							esc_html( get_the_date( $_post->post_date ) ),
							esc_html( get_the_date( 'F j, Y', $_post ) )
						);
						// View Count.
						'views' === $popular_post_type && $show_view_count && $post_views && printf(
							/* translators: 1: view count */
							'<span class="view-count">%1$s</span>',
							sprintf(
								/* translators: %s is a placeholder for the number of views a post has */
								esc_html( _n( '%s View', '%s Views', (int) $post_views, 'rishi-companion' ) ),
								(int) $post_views
							)
						);
						// Comment Counts.
						'comments' === $popular_post_type && $show_comment_count && $comment_count && printf(
							/* translators: 1: comment count */
							'<span class="comment-count">%1$s</span>',
							sprintf(
								/* translators: %s is a placeholder for the number of comments */
								esc_html( _n( '%s Comment', '%s Comments', (int) $comment_count, 'rishi-companion' ) ),
								(int) $comment_count
							)
						);
						?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php } ?>
</section>
