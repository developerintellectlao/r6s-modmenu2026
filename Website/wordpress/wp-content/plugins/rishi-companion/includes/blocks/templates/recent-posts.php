<?php
/**
 * Dynamic Blocks rendering.
 *
 * @package Rishi_Companion
 */

// Enqueue the public styles for the blocks.
wp_enqueue_style( 'rishi-companion-blocks-public' );

// Define the expected parameters for the recent posts block.
$expected_params = array(
	'recentPostLabel'         => __( 'Recent Posts', 'rishi-companion' ),
	'recentTitleSelector'     => 'h2',
	'recentPostCount'         => 3,
	'layoutStyle'             => 'layout-type-1',
	'openInNewTab'            => false,
	'recentPostShowThumbnail' => true,
	'recentImageSize'         => 'default',
	'recentPostShowDate'      => true,
);

// Merge the expected parameters with the provided attributes.
$data = rishi_companion_list( array_keys( $expected_params ), array_merge( $expected_params, $attributes ) );

// Extract the data into variables.
list( $label, $title_selector, $post_count, $style, $new_tab, $show_thumbnail, $recent_image_size, $show_post_date ) = $data;

// Define the query arguments for fetching the posts.
$query_args = array(
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => $post_count,
);

// Fetch the posts.
$posts_query = get_posts( $query_args );

// Determine the image size based on the provided parameters.
if ( 'full_size' === $recent_image_size ) {
	$image_size = 'full';
} else {
	$image_size = ( 'layout-type-1' === $style ) ? 'thumbnail' : 'large';
}

// Start the output of the block.
?>
<section class="rishi_sidebar_widget_recent_post">
	<?php if ( $label ) { ?>
		<<?php echo esc_html( $title_selector ); ?> class="widget-title" itemProp="name"><span><?php echo esc_html( $label ); ?></span></<?php echo esc_html( $title_selector ); ?>>
	<?php } ?>
	<?php if ( isset( $posts_query[0] ) ) { ?>
		<ul class="<?php echo esc_attr( $style ); ?>">
		<?php
		// Loop through the posts and output each one.
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
				// Output the post thumbnail if it's enabled.
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
					// Output the post categories.
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

					// Output the post title.
					printf(
						'<h3 class="entry-title"><a target="%1$s" rel="noopener" href="%2$s">%3$s</a></h3>',
						esc_attr( $new_tab ) ? '_blank' : '_self',
						esc_url( $post_link ),
						esc_html( $post_title )
					);
					?>
					<div class="entry-meta">
						<?php
						// Output the post date if it's enabled.
						$show_post_date && printf(
							'<span class="posted-on">
								<a target="%1$s" href="%2$s" rel="noopener">
									<time dateTime="%3$s">%4$s</time>
								</a>
							</span>',
							esc_attr( $new_tab ) ? '_blank' : '_self',
							esc_url( esc_url( $post_link ) ),
							esc_html( get_the_date( $_post->post_date ) ),
							esc_html( get_the_date( 'F j, Y', $_post ) ),
						);
						?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php } ?>
</section>
