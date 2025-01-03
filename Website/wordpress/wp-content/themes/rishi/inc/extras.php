<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Rishi
 */
use Rishi\Customizer\Helpers\Basic as Helpers;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

if ( ! function_exists( 'rishi_comment_link' ) ) :
	/**
	 * Comments Links
	 * @return HTML
	 */
	function rishi_comment_link() {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comment-link-wrap meta-common">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Write a Comment<span class="screen-reader-text"> on %s</span>', 'rishi' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'rishi_post_meta' ) ) :
	/**
	 * Post metas Collection
	 *
	 * @param [type]  $metas determines the individual meta for the posts
	 * @param boolean $position determines where it lands
	 * @return void
	 */
	function rishi_post_meta( $metas, $position = false ) {
		$data_position = $position ? ' data-position="' . esc_attr( $position ) . '"' : '';
		if ( $metas && isset( $metas['enabled'] ) && isset( $metas['archive_postmeta'] ) ) { ?>
			<div class="post-meta-wrapper">
				<div class="post-meta-inner <?php echo esc_attr( $metas['divider_divider'] ); ?>"<?php echo $data_position; ?>>
					<?php
					if ( $metas['enabled'] == true && in_array( 'author', $metas['archive_postmeta'] ) ) {
						rishi_posted_by( $metas );
					}
					if ( $metas['enabled'] == true && in_array( 'published-date', $metas['archive_postmeta'] ) ) {
						rishi_posted_on();
					}
					if ( $metas['enabled'] == true && in_array( 'updated-date', $metas['archive_postmeta'] ) ) {
						rishi_updated_on( $metas );
					}
					if ( $metas['enabled'] == true && in_array( 'comments', $metas['archive_postmeta'] ) ) {
						rishi_comment_link();
					}
					if ( $metas['enabled'] == true && in_array( 'reading-time', $metas['archive_postmeta'] ) ) {
						rishi_estimated_reading_time( $metas, get_post( get_the_ID() )->post_content );
					}
					if ( class_exists( 'Rishi_Pro\Rishi_Pro' ) && method_exists( 'Rishi_Pro\Modules\Helpers\Advanced_Blogging\Multiple_Authors', 'rishi_multiple_authors' ) && $metas['enabled'] === true && in_array( 'multiple_authors', $metas['archive_postmeta'] ) ) {
						Rishi_Pro\Modules\Helpers\Advanced_Blogging\Multiple_Authors::rishi_multiple_authors( $metas, get_the_ID() );
					}
					?>
				</div>
			</div>
			<?php
		}
	}
endif;

if ( ! function_exists( 'rishi_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 * @return HTML
	 */
	function rishi_entry_footer( $meta ) {
		?>
		<footer class="entry-footer rishi-flex">
			<?php
				$arrow = ( $meta['read_more_arrow'] == 'yes' ) ? 'yes' : 'no';
				$class = ( $meta['button_type'] == 'button' ) ? ' button-style' : '';
			if ( $meta['read_more_text'] ) {
				echo '<div class="readmore-btn-wrap"><a href="' . esc_url( get_the_permalink() ) . '" class="btn-readmore' . esc_attr( $class ) . '" data-arrow="' . esc_attr( $arrow ) . '">' . esc_html( $meta['read_more_text'] ) . '</a></div>';
			}
			?>
		</footer><!-- .entry-footer -->
		<?php
	}
endif;

if ( ! function_exists( 'rishi_comment_callback' ) ) :
	/**
	 * @return HTML
	 * Callback function for Comment List *
	 *
	 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments
	 */
	function rishi_comment_callback( $comment, $args, $depth ) {
		if ( 'div' == $args['style'] ) {
			$tag       = 'div';
			$add_below = 'comment';
		} else {
			$tag       = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo $tag; ?>
			<?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-
			<?php comment_ID(); ?>">

			<?php if ( 'div' != $args['style'] ) : ?>
				<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
				<?php endif; ?>

				<footer class="comment-meta">
					<div class="comment-author vcard">
						<?php
						if ( $args['avatar_size'] != 0 ) {
							echo get_avatar( $comment, $args['avatar_size'] );}
						?>
					</div><!-- .comment-author vcard -->
				</footer>
				<div class="text-holder">
					<div class="top">
						<div class="left">
							<?php if ( $comment->comment_approved == '0' ) : ?>
								<em class="comment-awaiting-moderation">
									<?php _e( 'Your comment is awaiting moderation.', 'rishi' ); ?>
								</em>
								<br />
							<?php endif; ?>
							<b class="fn">
								<?php echo get_comment_author_link(); ?>
							</b><span class="says">
								<?php esc_html_e( 'says:', 'rishi' ); ?>
							</span>
							<div class="comment-metadata commentmetadata">
								<a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
									<time itemprop="commentTime" datetime="<?php echo esc_attr( get_gmt_from_date( get_comment_date() . get_comment_time(), 'Y-m-d H:i:s' ) ); ?>">
										<?php printf( esc_html__( '%1$s at %2$s', 'rishi' ), get_comment_date(), get_comment_time() ); ?>
									</time>
								</a>
							</div>
						</div>
					</div>
					<div class="comment-content" itemprop="commentText">
						<?php comment_text(); ?>
					</div>
					<div class="reply">
						<?php
							comment_reply_link(
								array_merge(
									$args,
									array(
										'add_below' => $add_below,
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
									)
								)
							);
						?>
					</div>
				</div><!-- .text-holder -->
				<?php if ( 'div' != $args['style'] ) : ?>
				</article><!-- .comment-body -->
					<?php
			endif;
	}
endif;

if ( ! function_exists( 'rishi_breadcrumb' ) ) :
	/**
	 * Breadcrumbs
	 * @return HTML
	 */
	function rishi_breadcrumb() {
		global $post;
		$defaults              = Defaults::breadcrumbs_defaults();
		$post_page             = get_option( 'page_for_posts' ); // The ID of the page that displays posts.
		$show_front            = get_option( 'show_on_front' ); // What to show on the front page
		$breadcrumbs_separator = get_theme_mod( 'breadcrumbs_separator', $defaults['breadcrumbs_separator'] );
		$position              = get_theme_mod( 'breadcrumbs_position', $defaults['breadcrumbs_position'] );
		$alignment             = get_theme_mod( 'breadcrumbs_alignment', $defaults['breadcrumbs_alignment'] );
		$separators            = array(
			'type-1' => Helpers::get_svg_by_name( 'breadcrumb-sep-1' ),
			'type-2' => Helpers::get_svg_by_name( 'breadcrumb-sep-2' ),
			'type-3' => Helpers::get_svg_by_name( 'breadcrumb-sep-3' ),
		);
		if ( $breadcrumbs_separator == 'type-1' ) {
			$seperator_svg = $separators['type-1'];
		} elseif ( $breadcrumbs_separator == 'type-2' ) {
			$seperator_svg = $separators['type-2'];
		} elseif ( $breadcrumbs_separator == 'type-3' ) {
			$seperator_svg = $separators['type-3'];
		} else {
			$seperator_svg = '';
		}
		$delimiter = '<span class="separator">' . $seperator_svg . '</span>';
		$before    = '<span class="current">'; // tag before the current crumb
		$after     = '</span>'; // tag after the current crumb

		// settings from the theme
		if ( get_theme_mod( 'breadcrumbs_position', $defaults['breadcrumbs_position'] ) !== 'none' ) {
			$depth = 1;
			?>
				<div id="crumbs" class="rishi-breadcrumb-main-wrap" <?php echo rishi_print_schema( 'breadcrumb_list' ); ?>>
					<?php
					if ( $position !== 'before' ) {
						echo '<div class="rishi-container">';}
					?>
					<div class="rishi-breadcrumbs align-<?php echo esc_attr( $alignment ); ?>">

						<span <?php echo rishi_print_schema( 'breadcrumb_item' ); ?>>
							<?php
							echo '<a href="' . esc_url( home_url() ) . '" itemprop="item"><span itemprop="name">' . esc_html__( 'Home', 'rishi' ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
							if ( is_home() ) {
								$depth = 2;
								echo $before . '<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">' . esc_html( single_post_title( '', false ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_category() ) {
								$depth   = 2;
								$thisCat = get_category( get_query_var( 'cat' ), false );
								if ( $show_front === 'page' && $post_page ) { // If static blog post page is set
									$p = get_post( $post_page );
									echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
									$depth++;
								}
								if ( $thisCat->parent != 0 ) {
									$parent_categories = get_category_parents( $thisCat->parent, false, ',' );
									$parent_categories = explode( ',', $parent_categories );
									foreach ( $parent_categories as $parent_term ) {
										$parent_obj = get_term_by( 'name', $parent_term, 'category' );
										if ( is_object( $parent_obj ) ) {
											$term_url  = get_term_link( $parent_obj->term_id );
											$term_name = $parent_obj->name;
											echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
											$depth++;
										}
									}
								}
								echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $thisCat->term_id ) ) . '"><span itemprop="name">' . esc_html( single_cat_title( '', false ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( rishi_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ) { // For Woocommerce archive page
								$depth        = 2;
								$current_term = $GLOBALS['wp_query']->get_queried_object();
								if ( wc_get_page_id( 'shop' ) ) { // Displaying Shop link in woocommerce archive page
									$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
									if ( ! $_name ) {
										$product_post_type = get_post_type_object( 'product' );
										$_name             = $product_post_type->labels->singular_name;
									}
									echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
									$depth++;
								}
								if ( is_product_category() ) {
									$ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
									$ancestors = array_reverse( $ancestors );
									foreach ( $ancestors as $ancestor ) {
										$ancestor = get_term( $ancestor, 'product_cat' );
										if ( ! is_wp_error( $ancestor ) && $ancestor ) {
											echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
											$depth++;
										}
									}
								}
								echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $current_term->term_id ) ) . '"><span itemprop="name">' . esc_html( $current_term->name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( rishi_is_woocommerce_activated() && is_shop() ) { // Shop Archive page

								$depth = 2;
								if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
									return;
								}
								$_name    = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
								$shop_url = ( wc_get_page_id( 'shop' ) && wc_get_page_id( 'shop' ) > 0 ) ? get_the_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop' );
								if ( ! $_name ) {
									$product_post_type = get_post_type_object( 'product' );
									$_name             = $product_post_type->labels->singular_name;
								}
								echo $before . '<a itemprop="item" href="' . esc_url( $shop_url ) . '"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_tag() ) {
								$depth          = 2;
								$queried_object = get_queried_object();
								echo $before . '<a itemprop="item" href="' . esc_url( get_term_link( $queried_object->term_id ) ) . '"><span itemprop="name">' . esc_html( single_tag_title( '', false ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_author() ) {
								global $author;
								$depth    = 2;
								$userdata = get_userdata( $author );
								echo $before . '<a itemprop="item" href="' . esc_url( get_author_posts_url( $author ) ) . '"><span itemprop="name">' . esc_html( $userdata->display_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_search() ) {
								$depth       = 2;
								$request_uri = $_SERVER['REQUEST_URI'];
								echo $before . '<a itemprop="item" href="' . esc_url( $request_uri ) . '"><span itemprop="name">' . sprintf( __( 'Search Results for "%s"', 'rishi' ), esc_html( get_search_query() ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_day() ) {
								$depth = 2;
								echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'rishi' ) ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'rishi' ) ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
								$depth++;
								echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'rishi' ) ), get_the_time( __( 'm', 'rishi' ) ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'rishi' ) ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
								$depth++;
								echo $before . '<a itemprop="item" href="' . esc_url( get_day_link( get_the_time( __( 'Y', 'rishi' ) ), get_the_time( __( 'm', 'rishi' ) ), get_the_time( __( 'd', 'rishi' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'd', 'rishi' ) ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_month() ) {
								$depth = 2;
								echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'rishi' ) ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'rishi' ) ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
								$depth++;
								echo $before . '<a itemprop="item" href="' . esc_url( get_month_link( get_the_time( __( 'Y', 'rishi' ) ), get_the_time( __( 'm', 'rishi' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'F', 'rishi' ) ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_year() ) {
								$depth = 2;
								echo $before . '<a itemprop="item" href="' . esc_url( get_year_link( get_the_time( __( 'Y', 'rishi' ) ) ) ) . '"><span itemprop="name">' . esc_html( get_the_time( __( 'Y', 'rishi' ) ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_single() && ! is_attachment() ) {
								$depth = 2;
								if ( rishi_is_woocommerce_activated() && 'product' === get_post_type() ) { // For Woocommerce single product
									if ( wc_get_page_id( 'shop' ) ) { // Displaying Shop link in woocommerce archive page
										$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
										if ( ! $_name ) {
											$product_post_type = get_post_type_object( 'product' );
											$_name             = $product_post_type->labels->singular_name;
										}
										echo '<span><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
										$depth++;
									}
									if ( $terms = wc_get_product_terms(
										$post->ID,
										'product_cat',
										array(
											'orderby' => 'parent',
											'order'   => 'DESC',
										)
									) ) {
										$main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
										$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
										$ancestors = array_reverse( $ancestors );
										foreach ( $ancestors as $ancestor ) {
											$ancestor = get_term( $ancestor, 'product_cat' );
											if ( ! is_wp_error( $ancestor ) && $ancestor ) {
												echo '<span><a href="' . esc_url( get_term_link( $ancestor ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $ancestor->name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
												$depth++;
											}
										}
										echo '<span><a href="' . esc_url( get_term_link( $main_term ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $main_term->name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
										$depth++;
									}
									echo $before . '<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
								} elseif ( get_post_type() != 'post' ) {
									$post_type = get_post_type_object( get_post_type() );
									if ( $post_type->has_archive == true ) { // For CPT Archive Link
										// Add support for a non-standard label of 'archive_title' (special use case).
										$label = ! empty( $post_type->labels->archive_title ) ? $post_type->labels->archive_title : $post_type->labels->name;
										echo '<span><a href="' . esc_url( get_post_type_archive_link( get_post_type() ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $label ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
										$depth++;
									}
									echo $before . '<a href="' . esc_url( get_the_permalink() ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
								} else { // For Post
									$cat_object       = get_the_category();
									$potential_parent = 0;

									if ( $show_front === 'page' && $post_page ) { // If static blog post page is set
										$p = get_post( $post_page );
										echo '<span><a href="' . esc_url( get_permalink( $post_page ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $p->post_title ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
										$depth++;
									}

									if ( $cat_object ) { // Getting category hierarchy if any
										// Now try to find the deepest term of those that we know of
										$use_term = key( $cat_object );
										foreach ( $cat_object as $key => $object ) {
											// Can't use the next($cat_object) trick since order is unknown
											if ( $object->parent > 0 && ( $potential_parent === 0 || $object->parent === $potential_parent ) ) {
												$use_term         = $key;
												$potential_parent = $object->term_id;
											}
										}
										$cat  = $cat_object[ $use_term ];
										$cats = get_category_parents( $cat, false, ',' );
										$cats = explode( ',', $cats );
										foreach ( $cats as $cat ) {
											$cat_obj = get_term_by( 'name', $cat, 'category' );
											if ( is_object( $cat_obj ) ) {
												$term_url  = get_term_link( $cat_obj->term_id );
												$term_name = $cat_obj->name;
												echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a itemprop="item" href="' . esc_url( $term_url ) . '"><span itemprop="name">' . esc_html( $term_name ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
												$depth++;
											}
										}
									}
									echo $before . '<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
								}
							} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) { // For Custom Post Archive
								$depth     = 2;
								$post_type = get_post_type_object( get_post_type() );
								if ( get_query_var( 'paged' ) ) {
									echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '/</span>';
									echo $before . sprintf( __( 'Page %s', 'rishi' ), get_query_var( 'paged' ) ) . $after;
								} else {
									echo $before . '<a itemprop="item" href="' . esc_url( get_post_type_archive_link( $post_type->name ) ) . '"><span itemprop="name">' . esc_html( $post_type->label ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
								}
							} elseif ( is_attachment() ) {
								$depth = 2;
								echo $before . '<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_page() && ! $post->post_parent ) {
								$depth = 2;
								echo $before . '<a itemprop="item" href="' . esc_url( get_the_permalink() ) . '"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							} elseif ( is_page() && $post->post_parent ) {
								$depth       = 2;
								$parent_id   = $post->post_parent;
								$breadcrumbs = array();
								while ( $parent_id ) {
									$current_page  = get_post( $parent_id );
									$breadcrumbs[] = $current_page->ID;
									$parent_id     = $current_page->post_parent;
								}
								$breadcrumbs = array_reverse( $breadcrumbs );
								for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
									echo '<span ' . rishi_print_schema( 'breadcrumb_item' ) . '><a href="' . esc_url( get_permalink( $breadcrumbs[ $i ] ) ) . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title( $breadcrumbs[ $i ] ) ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $delimiter . '</span>';
									$depth++;
								}
								echo $before . '<a href="' . get_permalink() . '" itemprop="item"><span itemprop="name">' . esc_html( get_the_title() ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" /></span>' . $after;
							} elseif ( is_404() ) {
								$depth = 2;
								echo $before . '<a itemprop="item" href="' . esc_url( home_url() ) . '"><span itemprop="name">' . esc_html__( '404 Error - Page Not Found', 'rishi' ) . '</span></a><meta itemprop="position" content="' . absint( $depth ) . '" />' . $after;
							}

							if ( get_query_var( 'paged' ) ) {
								printf( __( ' (Page %s)', 'rishi' ), get_query_var( 'paged' ) );
							}
							?>
					</div>
					<?php
					if ( $position !== 'before' ) {
						echo '</div>';}
					?>
				</div><!-- .crumbs -->
				<?php
		}
	}
endif;

if ( ! function_exists( 'rishi_breadcrumb_start' ) ) :
	/**
	 * Content Start
	 * @return HTML
	 */
	function rishi_breadcrumb_start() {
		$defaults                       = Defaults::breadcrumbs_defaults();
		$breadcrumbs_position           = get_theme_mod( 'breadcrumbs_position', $defaults['breadcrumbs_position'] );
		$disable_search                 = get_theme_mod( 'breadcrumbs_ed_search', $defaults['breadcrumbs_ed_search'] );
		$disable_author                 = get_theme_mod( 'breadcrumbs_ed_author', $defaults['breadcrumbs_ed_author'] );
		$disable_archive                = get_theme_mod( 'breadcrumbs_ed_archive', $defaults['breadcrumbs_ed_archive'] );
		$disable_blog                   = get_theme_mod( 'blog_ed_breadcrumbs', $defaults['blog_ed_breadcrumbs'] );
		$breadcrumbs_ed_single_page     = get_theme_mod( 'breadcrumbs_ed_single_page', $defaults['breadcrumbs_ed_single_page'] );
		$breadcrumbs_ed_single_post     = get_theme_mod( 'breadcrumbs_ed_single_post', $defaults['breadcrumbs_ed_single_post'] );
		$disable_single_product         = get_theme_mod( 'breadcrumbs_ed_single_product', $defaults['breadcrumbs_ed_single_product'] );
		$breadcrumbs_ed_archive_product = get_theme_mod( 'breadcrumbs_ed_archive_product', $defaults['breadcrumbs_ed_archive_product'] );
		$disable_404                    = get_theme_mod( 'breadcrumbs_ed_404', $defaults['breadcrumbs_ed_404'] );
		$breadcrumbs_ed_single_post     = Helpers::get_meta( get_the_ID(), 'breadcrumbs_single_post', 'no' ) === 'no' ? $breadcrumbs_ed_single_post : 'no';
		$page_title_panel               = Helpers::get_meta( get_the_ID(), 'page_title_panel', 'inherit' );
		if ( 'custom' === $page_title_panel ) {
			$breadcrumbs_ed_single_page = Helpers::get_meta( get_the_ID(), 'breadcrumbs_single_page', 'no' ) === 'no' ? 'yes' : 'no';
		} else if( 'disabled' === $page_title_panel ){
			$breadcrumbs_ed_single_page = 'no';
		}

		if ( $breadcrumbs_position == 'none' ) {
			return;
		} elseif ( is_404() && $disable_404 !== 'yes' ) {
			return;
		} elseif ( is_singular() ) {
			if ( ( get_post_type() == 'product' ) && $disable_single_product !== 'yes' ) {
				return;
			} elseif ( is_single() && ( $breadcrumbs_ed_single_post !== 'yes' ) ) {
				return;
			} elseif ( is_page() && ( $breadcrumbs_ed_single_page !== 'yes' ) ) {
				return;
			} else {
				rishi_breadcrumb();
			}
		} elseif ( is_archive() ) {
			if ( is_author() && $disable_author !== 'yes' ) {
				return;
			} elseif ( ! is_author() && ! ( rishi_is_woocommerce_activated() && is_shop() ) && $disable_archive !== 'yes' ) {
				return;
			} elseif ( rishi_is_woocommerce_activated() && is_shop() && $breadcrumbs_ed_archive_product !== 'yes' ) {
				return;
			} else {
				rishi_breadcrumb();
			}
		} elseif ( is_search() && $disable_search !== 'yes' ) {
			return;
		} elseif ( ! is_front_page() && is_home() && $disable_blog !== 'yes' ) {
			return;
		} else {
			if ( ! is_front_page() ) {
				rishi_breadcrumb();
			}
		}
	}
endif;

if ( ! function_exists( 'rishi_get_posts_list' ) ) :
	/**
	* @return HTML
	 * Returns Latest, Related Posts
	 */
	function rishi_get_posts_list() {
		global $post;

		$defaults       = Defaults::get_layout_defaults();
		$posts_per_page = get_theme_mod( 'no_of_related_post', $defaults['no_of_related_post'] );
		$posts_per_row  = get_theme_mod( 'related_post_per_row', $defaults['related_post_per_row'] );
		$related_tax    = get_theme_mod( 'related_taxonomy', $defaults['related_taxonomy'] );
		$single_title   = get_theme_mod( 'single_related_title', $defaults['single_related_title'] );

		$args = array(
			'posts_status'        => 'publish',
			'posts_per_page'      => $posts_per_page,
			'post__not_in'        => array( $post->ID ),
			'orderby'             => 'rand',
			'ignore_sticky_posts' => true,
			'post_type'           => 'post',
		);

		if ( $related_tax == 'cat' ) {
			$cats = get_the_category( $post->ID );
			if ( $cats ) {
				$c = array();
				foreach ( $cats as $cat ) {
					$c[] = $cat->term_id;
				}
				$args['category__in'] = $c;
			}
		} elseif ( $related_tax == 'tag' ) {
			$tags = get_the_tags( $post->ID );
			if ( $tags ) {
				$t = array();
				foreach ( $tags as $tag ) {
					$t[] = $tag->term_id;
				}
				$args['tag__in'] = $t;
			}
		}

		$qry = new WP_Query( $args );

		if ( $qry->have_posts() ) {
			?>
				<div class="recommended-articles related-posts related-posts-per-row-<?php echo esc_attr( $posts_per_row ); ?>">
					<?php if ( $single_title ) { ?>
						<h2 class="blog-single-wid-title">
							<span><?php echo esc_html( $single_title ); ?></span>
						</h2>
					<?php } ?>
					<div class="recomm-artcles-wrap">
						<?php
						while ( $qry->have_posts() ) {
							$qry->the_post();
							?>
							<div class="recomm-article-singl">
								<article class="post rishi-article-post">
									<div class="blog-post-lay">
										<div class="post-content">
											<div class="entry-content-main-wrap">
												<div class="post-thumb">
													<div class="post-thumb-inner-wrap">
														<a href="<?php the_permalink(); ?>" rel="prev">
															<?php
															if ( has_post_thumbnail() ) {
																the_post_thumbnail( 'rishi-blog-grid', array( 'itemprop' => 'image' ) );
															} else {
																rishi_get_fallback_svg( 'rishi-blog-grid' );
															}
															?>
														</a>
													</div>
												</div>
												<header class="entry-header">
													<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
												</header>
												<?php
													rishi_posted_on();
												?>
											</div>
										</div>
									</div>
								</article>
							</div><!-- .recomm-article-singl -->
						<?php } ?>
					</div><!-- .recomm-artcles-wrap -->
				</div><!-- .related-articles/latest-articles -->
			<?php
			wp_reset_postdata();
		}
	}
endif;

if ( ! function_exists( 'rishi_search_post_count' ) ) :
	/**
	 * Search Result Page Count
	 * @return HTML
	 */
	function rishi_search_post_count() {
		if ( rishi_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) ) ) {
			return;
		}
		$ed = 'yes';
		if ( is_archive() ) {
			$ed = get_theme_mod( 'archive_page_search_ed', 'yes' );

			if ( is_author() ) {
				$ed = get_theme_mod( 'author_page_search_ed', 'yes' );
			}
		}

		if ( is_search() ) {
			$ed = get_theme_mod( 'search_page_search_ed', 'yes' );
		}

		global $wp_query;
		$found_posts  = $wp_query->found_posts;
		$visible_post = get_option( 'posts_per_page' );

		if ( $found_posts > 0 ) {
			?>
			<section class="rishi-search-count" data-count="<?php echo esc_attr( $ed ); ?>">
				<span class="srch-results-cnt">
					<?php
					if ( $found_posts > $visible_post ) {
						printf( esc_html__( 'Showing %1$s of %2$s Results', 'rishi' ), number_format_i18n( $visible_post ), number_format_i18n( $found_posts ) );
					} else {
						printf( _nx( '%s Result', '%s Results', $found_posts, 'found posts', 'rishi' ), number_format_i18n( $found_posts ) );
					}
					?>
				</span>
			</section>
			<?php
		}
	}
endif;

if ( ! function_exists( 'rishi_get_image_sizes' ) ) :
	/**
	 * Get information about available image sizes
	 * @return HTML
	 */
	function rishi_get_image_sizes( $size = '' ) {

		global $_wp_additional_image_sizes;

		$sizes                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}
		// Get only 1 size if found
		if ( $size ) {
			if ( isset( $sizes[ $size ] ) ) {
				return $sizes[ $size ];
			} else {
				return false;
			}
		}
		return $sizes;
	}
endif;

if ( ! function_exists( 'rishi_get_fallback_svg' ) ) :
	/**
	 * Get Fallback SVG
	 */
	function rishi_get_fallback_svg( $post_thumbnail ) {

		if ( ! $post_thumbnail ) {
			return;
		}
		$image_size = rishi_get_image_sizes( $post_thumbnail );

		if ( $image_size ) {
			?>
			<div class="svg-holder">
				<svg class="fallback-svg" viewBox="0 0 <?php echo esc_attr( $image_size['width'] ); ?> <?php echo esc_attr( $image_size['height'] ); ?>" preserveAspectRatio="none">
					<rect width="<?php echo esc_attr( $image_size['width'] ); ?>" height="<?php echo esc_attr( $image_size['height'] ); ?>" style="fill:#f6f9ff;"></rect>
				</svg>
			</div>
			<?php
		}
	}
endif;

if ( ! function_exists( 'rishi_sidebar' ) ) :
	/**
	 * Return sidebar layouts for pages/posts
	 * @return HTML
	 */
	function rishi_sidebar( $class = false ) {
		global $post;
		$defaults = Defaults::get_layout_defaults();
		$return   = $class ? 'full-width' : false; // Fullwidth
		$layout   = get_theme_mod( 'layout_style', $defaults['layout_style'] );

		if ( is_page_template( 'page-bookmark.php' ) || is_page_template( 'portfolio.php' ) || is_singular( 'rishi-portfolio' ) ) {
			return;
		}

		if ( is_home() ) {

			$home_multiple_sidebar = 'sidebar-1';

			$blog_sidebar = get_theme_mod( 'blog_sidebar_layout', $defaults['blog_sidebar_layout'] );
			if ( $blog_sidebar == 'no-sidebar' || ( $blog_sidebar == 'default-sidebar' && $layout == 'no-sidebar' ) ) {
				$return = $class ? 'full-width' : false; // Fullwidth
			} elseif ( is_active_sidebar( $home_multiple_sidebar ) ) {
				if ( $blog_sidebar == 'right-sidebar' || ( $blog_sidebar == 'default-sidebar' && $layout == 'right-sidebar' ) ) {
					$return = $class ? 'rightsidebar' : $home_multiple_sidebar;
				}
				if ( $blog_sidebar == 'left-sidebar' || ( $blog_sidebar == 'default-sidebar' && $layout == 'left-sidebar' ) ) {
					$return = $class ? 'leftsidebar' : $home_multiple_sidebar;
				}
			} else {
				$return = $class ? 'full-width' : false; // Fullwidth
			}
		}

		if ( is_archive() ) {

			$archive_multiple_sidebar = 'sidebar-1';

			if ( is_author() ) {
				$archive_sidebar = get_theme_mod( 'author_sidebar_layout', $defaults['author_sidebar_layout'] );
			} else {
				$archive_sidebar = get_theme_mod( 'archive_sidebar_layout', $defaults['archive_sidebar_layout'] );
			}

			if ( $archive_sidebar == 'no-sidebar' || ( $archive_sidebar == 'default-sidebar' && $layout == 'no-sidebar' ) ) {
				$return = $class ? 'full-width' : false; // Fullwidth
			} elseif ( is_active_sidebar( $archive_multiple_sidebar ) ) {
				if ( $archive_sidebar == 'right-sidebar' || ( $archive_sidebar == 'default-sidebar' && $layout == 'right-sidebar' ) ) {
					$return = $class ? 'rightsidebar' : $archive_multiple_sidebar;
				}
				if ( $archive_sidebar == 'left-sidebar' || ( $archive_sidebar == 'default-sidebar' && $layout == 'left-sidebar' ) ) {
					$return = $class ? 'leftsidebar' : $archive_multiple_sidebar;
				}
			} else {
				$return = $class ? 'full-width' : false; // Fullwidth
			}
		}

		if ( is_search() ) {

			$search_multiple_sidebar = 'sidebar-1';

			$search_sidebar = get_theme_mod( 'search_sidebar_layout', $defaults['search_sidebar_layout'] );
			if ( $search_sidebar == 'no-sidebar' || ( $search_sidebar == 'default-sidebar' && $layout == 'no-sidebar' ) ) {
				$return = $class ? 'full-width' : false; // Fullwidth
			} elseif ( is_active_sidebar( $search_multiple_sidebar ) ) {
				if ( $search_sidebar == 'right-sidebar' || ( $search_sidebar == 'default-sidebar' && $layout == 'right-sidebar' ) ) {
					$return = $class ? 'rightsidebar' : $search_multiple_sidebar;
				}
				if ( $search_sidebar == 'left-sidebar' || ( $search_sidebar == 'default-sidebar' && $layout == 'left-sidebar' ) ) {
					$return = $class ? 'leftsidebar' : $search_multiple_sidebar;
				}
			} else {
				$return = $class ? 'full-width' : false; // Fullwidth
			}
		}

		if ( rishi_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) ) ) {

			$woo_multiple_sidebar = 'shop-sidebar';

			$woo_sidebar = get_theme_mod( 'woocommerce_sidebar_layout', $defaults['woocommerce_sidebar_layout'] );
			if ( $woo_sidebar == 'no-sidebar' || ( $woo_sidebar == 'default-sidebar' && $layout == 'no-sidebar' ) ) {
				$return = $class ? 'full-width' : false; // Fullwidth
			} elseif ( is_active_sidebar( $woo_multiple_sidebar ) ) {
				if ( $woo_sidebar == 'right-sidebar' || ( $woo_sidebar == 'default-sidebar' && $layout == 'right-sidebar' ) ) {
					$return = $class ? 'rightsidebar' : $woo_multiple_sidebar;
				}
				if ( $woo_sidebar == 'left-sidebar' || ( $woo_sidebar == 'default-sidebar' && $layout == 'left-sidebar' ) ) {
					$return = $class ? 'leftsidebar' : $woo_multiple_sidebar;
				}
			} else {
				$return = $class ? 'full-width' : false; // Fullwidth
			}
		}

		if ( is_singular() ) {
			$page_layout = get_theme_mod( 'page_sidebar_layout', $defaults['page_sidebar_layout'] ); // Global Layout/Position for Pages
			$post_layout = get_theme_mod( 'post_sidebar_layout', $defaults['post_sidebar_layout'] ); // Global Layout/Position for Posts
			/**
			 * Individual post/page layout
			 */
			if ( get_post_meta( $post->ID, '_rishi_sidebar_layout', true ) ) {
				$sidebar_layout = get_post_meta( $post->ID, '_rishi_sidebar_layout', true );
			} else {
				$sidebar_layout = 'default-sidebar';
			}

			$sidebar_layout = Helpers::get_meta( $post->ID, 'page_structure_type', 'default-sidebar' );

			/**
			 * Individual post/page sidebar
			 */

			$single_sidebar = 'sidebar-1';

			if ( is_page() ) {
				if ( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'no-sidebar' ) ) {
					$return = $class ? 'full-width' : false; // Fullwidth
				} elseif ( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'centered' ) ) {
					$return = $class ? 'full-width centered' : false;
				} elseif ( is_active_sidebar( $single_sidebar ) ) {
					if ( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ) {
						$return = $class ? 'rightsidebar' : $single_sidebar;
					}
					if ( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ) {
						$return = $class ? 'leftsidebar' : $single_sidebar;
					}
				} else {
					$return = $class ? 'full-width' : false; // Fullwidth
				}
			}

			if ( is_single() ) {
				if ( 'product' === get_post_type() ) { // For Product Post Type
					$woo_single_sidebar = 'shop-sidebar';
					$woo_sidebar        = get_theme_mod( 'woocommerce_sidebar_layout', $defaults['woocommerce_sidebar_layout'] );
					if ( $woo_sidebar == 'no-sidebar' || ( $woo_sidebar == 'default-sidebar' && $layout == 'no-sidebar' ) ) {
						$return = $class ? 'full-width' : false; // Fullwidth
					} elseif ( is_active_sidebar( $woo_single_sidebar ) ) {
						if ( $woo_sidebar == 'right-sidebar' || ( $woo_sidebar == 'default-sidebar' && $layout == 'right-sidebar' ) ) {
							$return = $class ? 'rightsidebar' : $woo_single_sidebar;
						}
						if ( $woo_sidebar == 'left-sidebar' || ( $woo_sidebar == 'default-sidebar' && $layout == 'left-sidebar' ) ) {
							$return = $class ? 'leftsidebar' : $woo_single_sidebar;
						}
					} else {
						$return = $class ? 'full-width' : false; // Fullwidth
					}
				} elseif ( 'post' === get_post_type() ) { // For default post type
					if ( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ) ) {
						$return = $class ? 'full-width' : false; // Fullwidth
					} elseif ( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'centered' ) ) {
						$return = $class ? 'full-width centered' : false;
					} elseif ( is_active_sidebar( $single_sidebar ) ) {
						if ( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ) {
							$return = $class ? 'rightsidebar' : $single_sidebar;
						}
						if ( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ) {
							$return = $class ? 'leftsidebar' : $single_sidebar;
						}
					} else {
						$return = $class ? 'full-width' : false; // Fullwidth
					}
				} else { // Custom Post Type
					if ( $post_layout == 'no-sidebar' ) {
						$return = $class ? 'full-width' : false; // Fullwidth
					} elseif ( $post_layout == 'centered' ) {
						$return = $class ? 'full-width centered' : false;
					} elseif ( is_active_sidebar( 'sidebar-1' ) ) {
						if ( $post_layout == 'right-sidebar' ) {
							$return = $class ? 'rightsidebar' : 'sidebar-1';
						}
						if ( $post_layout == 'left-sidebar' ) {
							$return = $class ? 'leftsidebar' : 'sidebar-1';
						}
					} else {
						$return = $class ? 'full-width' : false; // Fullwidth
					}
				}
			}
		}
		return $return;
	}
endif;

function rishi_sanitize_select( $value ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $key => $subvalue ) {
			$value[ $key ] = sanitize_text_field( $subvalue );
		}
		return $value;
	}
	return sanitize_text_field( $value );
}

/**
 * Query WooCommerce activation
 */
function rishi_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

/**
 * Query if Elementor Page Builder plugin is activated
 */
function rishi_is_elementor_activated() {
	return class_exists( 'Elementor\\Plugin' ) ? true : false;
}
/**
 * Check whether the current edited post is created in Elementor Page Builder
 *
 * @return boolean
 */
function rishi_is_elementor_activated_post() {
	if ( rishi_is_elementor_activated() && is_singular() ) {
		global $post;
		$post_id = $post->ID;
		return \Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor() ? true : false;
	} else {
		return false;
	}
}
/**
 * Query check if the Tutor LMS plugins acivated or not
 */
function rishi_is_tutor_lms_activated() {
	return class_exists( 'Tutor' ) ? true : false;
}
/**
 * Query check if the Learndash plugin acivated or not
 */
function rishi_is_learndash_activated() {
	return defined( 'LEARNDASH_VERSION' ) ? true : false;
}
/**
 * Checks if classic editor is active or not
 */
function rishi_is_classic_editor_activated() {
	return class_exists( 'Classic_Editor' ) ? true : false;
}

/**
 * Checks if primary-menu is set or not
 */
function rishi_is_primary_menu_activated() {
	return has_nav_menu( 'primary-menu' ) ? true : false;
}

/**
 * Query if Rishi pro is activated or not
 *
 * @return BOOLEAN
 */
function rishi_is_pro_activated() {
	return class_exists( 'Rishi_Pro\Rishi_Pro' ) ? true : false;
}

if ( ! function_exists( 'rishi_bookmark_settings' ) ) :
	function rishi_bookmark_settings() {
		if ( method_exists( 'Rishi_Pro\Modules\Helpers\Advanced_Blogging\Read_It_Later', 'rishi_pro_bookmark' ) ) {
			Rishi_Pro\Modules\Helpers\Advanced_Blogging\Read_It_Later::rishi_pro_bookmark();
		}
	}
endif;

if ( ! function_exists( 'rishi_is_bookmark_enabled' ) ) :
	function rishi_is_bookmark_enabled() {
		if ( method_exists( 'Rishi_Pro\Modules\Helpers\Advanced_Blogging\Read_It_Later', 'rishi_pro_bookmark' ) ) :
			$ed_bookmark = get_theme_mod( 'ed_read_it_later', 'no' );

			if ( $ed_bookmark === 'yes' ) {
				return true;
			} else {
				return false;
			}
		endif;
	}
endif;

if ( ! function_exists( 'rishi_customizer_get_wp_theme' ) ) {
	function rishi_customizer_get_wp_theme() {
		return apply_filters( 'rishi_customizer_get_wp_theme', wp_get_theme() );
	}
}

/**
 * Returns true to validate Rishi theme is activated
 */
if(  ! function_exists( 'rishi_check_if_theme_is_activated' ) ){
	function rishi_check_if_theme_is_activated() {
		return true;
	}
}