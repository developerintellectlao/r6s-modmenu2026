<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Rishi
 */
use Rishi\Customizer\Helpers\Basic as Helpers;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

if ( ! function_exists( 'rishi_doctype' ) ) :
	/**
	 * Doctype Declaration
	 */
	function rishi_doctype(){ ?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
			<?php
	}
endif;
add_action( 'rishi_doctype', 'rishi_doctype' );

if ( ! function_exists( 'rishi_head' ) ) :
	/**
	 * Before wp_head
	 */
	function rishi_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php
	}
endif;
add_action( 'rishi_before_wp_head', 'rishi_head' );

if ( ! function_exists( 'rishi_header_builder' ) ) :
	/**
	 *  Header Builder Code goes here
	 */
	function rishi_header_builder() {

		if ( defined( 'THEME_CUSTOMIZER_BUILDER_DIR_' ) && ! ! THEME_CUSTOMIZER_BUILDER_DIR_ ) {
			rishi_customizer()->header_builder->render();
		}
	}
	endif;
add_action( 'rishi_headerbuilder', 'rishi_header_builder' );

if ( ! function_exists( 'rishi_footer_builder' ) ) :
	/**
	 * Footer Builder Code Goes here
	 */
	function rishi_footer_builder() {

		if ( defined( 'THEME_CUSTOMIZER_BUILDER_DIR_' ) && ! ! THEME_CUSTOMIZER_BUILDER_DIR_ ) {
			rishi_customizer()->footer_builder->render();
		}
	}
	endif;
add_action( 'rishi_footerbuilder', 'rishi_footer_builder' );


if ( ! function_exists( 'rishi_page_start' ) ) :
	/**
	 * Page Start
	 */
	function rishi_page_start() {
		?>
		<div id="main-container" class="site">
			<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'rishi' ); ?></a>
		<?php
	}
endif;
add_action( 'rishi_before_header', 'rishi_page_start', 20 );

if ( ! function_exists( 'rishi_content_start' ) ) :
	/**
	 * Content Start
	 */
	function rishi_content_start() {
		$defaults = Defaults::get_layout_defaults();

		$container_layout        = get_theme_mod( 'layout', $defaults['layout'] );
		$vertical_spacing_source = ( Helpers::get_meta( get_the_ID(), 'vertical_spacing_source', 'inherit' ) );

		$class = ' main-content-wrapper clear';

		$streched_ed = 'no';

		if ( is_page() ) {
			$page_layout      = get_theme_mod( 'page_layout', $defaults['page_layout'] );
			$container_layout = ( $page_layout === 'default' ) ? $container_layout : $page_layout;
			$streched_ed      = get_theme_mod( 'page_layout_streched_ed', 'no' );

			$streched_ed = ( Helpers::get_meta( get_the_ID(), 'blog_page_streched_ed', 'no' ) === 'yes' ) ? 'no' : $streched_ed;

			$vertical_spacing = get_theme_mod( 'page_content_area_spacing', 'both' );
			if ( $vertical_spacing_source === 'custom' ) {
				$vertical_spacing = Helpers::get_meta( get_the_ID(), 'content_area_spacing', 'both' );

			}
			if ( $vertical_spacing && ! Helpers::is_elementor_activated_post()) {
				$class .= ' rishi-spacing-' . $vertical_spacing;
			}
		}

		if ( is_single() ) {
			$blog_post_layout = get_theme_mod( 'blog_post_layout', $defaults['blog_post_layout'] );
			$container_layout = ( $blog_post_layout === 'default' ) ? $container_layout : $blog_post_layout;
			$streched_ed      = get_theme_mod( 'blog_post_streched_ed', $defaults['blog_post_streched_ed'] );

			$streched_ed = ( Helpers::get_meta( get_the_ID(), 'blog_post_streched_ed', 'no' ) === 'yes' ) ? 'no' : $streched_ed;

			$vertical_spacing = get_theme_mod( 'single_content_area_spacing', 'both' );
			if ( $vertical_spacing_source === 'custom' ) {
				$vertical_spacing = Helpers::get_meta( get_the_ID(), 'content_area_spacing', 'both' );

			}
			if ( $vertical_spacing  && ! Helpers::is_elementor_activated_post() ) {
				$class .= ' rishi-spacing-' . $vertical_spacing;
			}
		}

		if ( is_home() ) {
			$blog_container   = get_theme_mod( 'blog_container', $defaults['blog_container'] );
			$container_layout = ( $blog_container === 'default' ) ? $container_layout : $blog_container;
			$streched_ed      = get_theme_mod( 'blog_container_streched_ed', $defaults['blog_container_streched_ed'] );
		}

		if ( is_archive() ) {
			if ( is_author() ) {
				$archive_layout = get_theme_mod( 'author_layout', $defaults['author_layout'] );
				$streched_ed    = get_theme_mod( 'author_layout_streched_ed', $defaults['author_layout_streched_ed'] );
			} else {
				$archive_layout = get_theme_mod( 'archive_layout', $defaults['archive_layout'] );
				$streched_ed    = get_theme_mod( 'archive_layout_streched_ed', $defaults['archive_layout_streched_ed'] );
			}
			$container_layout = ( $archive_layout === 'default' ) ? $container_layout : $archive_layout;
		}

		if ( is_search() ) {
			$search_layout    = get_theme_mod( 'search_layout', $defaults['search_layout'] );
			$container_layout = ( $search_layout === 'default' ) ? $container_layout : $search_layout;
			$streched_ed      = get_theme_mod( 'search_layout_streched_ed', $defaults['search_layout_streched_ed'] );
		}

		if ( rishi_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) ) ) {
			$woocommerce_layout = get_theme_mod( 'woocommerce_layout', $defaults['woocommerce_layout'] );
			$streched_ed        = get_theme_mod( 'woo_layout_streched_ed', $defaults['woo_layout_streched_ed'] );
			$container_layout   = ( $woocommerce_layout === 'default' ) ? $container_layout : $woocommerce_layout;
		}
		$dataattr = ( $streched_ed == 'yes' ) ? 'data-strech=full' : 'data-strech=none';

		?>
		<?php
		do_action( 'rishi_content_before' );

		?>
		<div class="site-content">
			<?php do_action( 'rishi_content_top' ); ?>
			<?php
			$page_title = 'yes';
			if ( is_archive() ) {
				$page_title = get_theme_mod( 'ed_archive_header', 'yes' );

				if ( is_author() ) {
					$page_title = get_theme_mod( 'ed_author_header', 'yes' );
				}
			}
			if ( is_search() ) {
				$page_title = get_theme_mod( 'ed_search_header', 'yes' );
			}
			if ( is_home() ) {
				$page_title = get_theme_mod( 'ed_blog_header', 'no' );
			}

			/**
			 * @hooked rishi_archive_title_wrapper_start  - 10
			 * @hooked rishi_archive_heading              - 20
			 * @hooked rishi_archive_search_header_count  - 30
			 * @hooked rishi_archive_title_wrapper_end    - 40
			*/
			if ( $page_title === 'yes' ) {
				do_action( 'rishi_site_content_start' );
			}
			?>
			<div class="rishi-container" <?php echo esc_attr( $dataattr ); ?>>
				<div class="<?php echo esc_attr( $class ); ?>">
		<?php
	}
	endif;
add_action( 'rishi_content', 'rishi_content_start', 20 );


if ( ! function_exists( 'rishi_navigation' ) ) :
	/**
	 * Navigation
	 */
	function rishi_navigation() {

		$defaults                = Defaults::get_layout_defaults();
		$ed_show_post_navigation = get_theme_mod( 'ed_show_post_navigation', 'yes' );

		if ( Helpers::get_meta( get_the_ID(), 'disable_posts_navigation', 'no' ) === 'yes' ) {
			return '';
		}
		if ( ( is_singular( 'post' ) && $ed_show_post_navigation === 'yes' ) ) {
			$next_post = get_next_post();
			$prev_post = get_previous_post();

			if ( $prev_post || $next_post ) {
				?>
				<nav class="navigation post-navigation" role="navigation">
					<h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'rishi' ); ?></h2>
					<div class="post-nav-links nav-links">
						<?php if ( $prev_post ) { ?>
							<div class="nav-holder nav-previous">
								<h3 class="entry-title"><a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></a></h3>
								<div class="meta-nav"><a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"><?php esc_html_e( 'Previous', 'rishi' ); ?></a></div>
							</div>
						<?php } if ( $next_post ) { ?>
							<div class="nav-holder nav-next">
								<h3 class="entry-title"><a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></a></h3>
								<div class="meta-nav"><a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"><?php esc_html_e( 'Next', 'rishi' ); ?></a></div>
							</div>
						<?php } ?>
					</div>
				</nav>
				<?php
			}
		} else {
			if ( is_archive() ) {
				if ( is_author() ) {
					$pagination = get_theme_mod( 'author_post_navigation', $defaults['author_post_navigation'] );
				} else {
					$pagination = get_theme_mod( 'archive_post_navigation', $defaults['archive_post_navigation'] );
				}
			} elseif ( is_search() ) {
				$pagination = get_theme_mod( 'search_post_navigation', $defaults['search_post_navigation'] );
			} else {
				$pagination = get_theme_mod( 'post_navigation', $defaults['post_navigation'] );
			}

			switch ( $pagination ) {

				case 'numbered': // Numbered Pagination
					the_posts_pagination(
						array(
							'prev_text'          => __( 'Previous', 'rishi' ),
							'next_text'          => __( 'Next', 'rishi' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'rishi' ) . ' </span>',
						)
					);
					break;

				/**
				 * Infinite Scroll
				 */
				case 'infinite_scroll';
					echo '<div class="infinite-pagination">
						<div class="pagination-loader">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div>
						<div class="pagination-info">No More Posts To Load</div>
					</div>';
				break;

				default:
					the_posts_pagination(
						array(
							'prev_text'          => __( 'Previous', 'rishi' ),
							'next_text'          => __( 'Next', 'rishi' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'rishi' ) . ' </span>',
						)
					);

					break;
			}
		}
	}
endif;
add_action( 'rishi_after_posts_content', 'rishi_navigation' );
add_action( 'rishi_after_post_loop', 'rishi_navigation', 10 );

if ( ! function_exists( 'rishi_author' ) ) :
	/**
	 * Rishi Author
	 */
	function rishi_author() {
		$ed_show_post_author      = get_theme_mod( 'ed_show_post_author', 'yes' );
		$author_box_layout        = get_theme_mod( 'author_box_layout', 'layout-one' );
		$ed_show_portfolio_author = get_theme_mod( 'ed_show_portfolio_author', 'no' );

		if ( Helpers::get_meta( get_the_ID(), 'disable_author_box', 'no' ) === 'yes' ) {
			return '';
		}

		if ( get_the_author_meta( 'description' ) && ( ( $ed_show_post_author === 'yes' && is_singular( 'post' ) ) || ( $ed_show_portfolio_author === 'yes' ) ) ) {
			?>
			<div class="autor-section <?php echo esc_attr( $author_box_layout ); ?>">
				<div class="author-top-wrap post-author-wrap">
					<div class="img-holder">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 150 ); ?>
					</div>
					<div class="author-content-wrapper">
						<div class="author-meta">
							<?php
								echo '<h3 class="author-name"><span class="vcard">' . esc_html( get_the_author_meta( 'display_name' ) ) . '</span></h3>';
								echo '<div class="author-description">' . wp_kses_post( get_the_author_meta( 'description' ) ) . '</div>';
							?>
						</div>
						<div class="author-footer">
							<?php do_action( "rishi_authors_social" ); ?>
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="view-all-auth"><?php esc_html_e( 'View All Articles', 'rishi' ); ?></a>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
endif;
add_action( 'rishi_after_post_loop', 'rishi_author', 20 );

if ( ! function_exists( 'rishi_related_posts' ) ) :
	/**
	 * Related Posts
	 */
	function rishi_related_posts() {
		$defaults        = Defaults::get_layout_defaults();
		$ed_related_post = get_theme_mod( 'ed_related', $defaults['ed_related'] );

		if ( ( $ed_related_post == 'yes' ) && ( 'post' === get_post_type() ) && ( Helpers::get_meta( get_the_ID(), 'disable_related_posts', 'no' ) === 'no' ) ) {
			rishi_get_posts_list();
		}
	}
endif;

if ( ! function_exists( 'rishi_comment' ) ) :
	/**
	 * Comments Template
	 */
	function rishi_comment() {
		$defaults             = Defaults::get_layout_defaults();
		$ed_single_comment    = get_theme_mod( 'ed_comment', $defaults['ed_comment'] );
		$ed_portfolio_comment = get_theme_mod( 'ed_portfolio_comment', 'no' );
		$ed_page_comment      = get_theme_mod( 'single_page_ed_comment', $defaults['ed_page_comment'] );
		/**
		 * If comments are open or we have at least one comment, load up the comment template.
		 */
		if ( ( $ed_single_comment == 'yes' && is_singular( 'post' ) ) && ( comments_open() || get_comments_number() ) && ( ( Helpers::get_meta( get_the_ID(), 'disable_comments', 'no' ) === 'no' ) ) ) {
			comments_template();
		}

		if ( ( $ed_portfolio_comment == 'yes' ) && ( comments_open() || get_comments_number() ) ) {
			comments_template();
		}

		if ( ( $ed_page_comment == 'yes' && is_page() ) && ( comments_open() || get_comments_number() ) && ( ( Helpers::get_meta( get_the_ID(), 'disable_comments', 'no' ) === 'no' ) ) ) {
			comments_template();
		}
	}
	endif;
add_action( 'rishi_after_page_loop', 'rishi_comment' );

if ( ! function_exists( 'rishi_archive_title_wrapper_start' ) ) :
	/**
	 * Content End
	 */
	function rishi_archive_title_wrapper_start() {

		$defaults = Defaults::get_layout_defaults();

		$container_layout = get_theme_mod( 'layout', $defaults['layout'] );

		$streched_ed = 'no';

		if ( is_page() ) {
			$page_layout      = get_theme_mod( 'page_layout', $defaults['page_layout'] );
			$container_layout = ( $page_layout === 'default' ) ? $container_layout : $page_layout;
			$streched_ed      = get_theme_mod( 'page_layout_streched_ed', 'no' );
		}

		if ( is_single() ) {
			$blog_post_layout = get_theme_mod( 'blog_post_layout', $defaults['blog_post_layout'] );
			$container_layout = ( $blog_post_layout === 'default' ) ? $container_layout : $blog_post_layout;
			$streched_ed      = get_theme_mod( 'blog_post_streched_ed', $defaults['blog_post_streched_ed'] );
		}

		if ( is_home() ) {
			$blog_container   = get_theme_mod( 'blog_container', $defaults['blog_container'] );
			$container_layout = ( $blog_container === 'default' ) ? $container_layout : $blog_container;
			$streched_ed      = get_theme_mod( 'blog_container_streched_ed', $defaults['blog_container_streched_ed'] );
		}

		if ( is_archive() ) {
			if ( is_author() ) {
				$archive_layout = get_theme_mod( 'author_layout', $defaults['author_layout'] );
				$streched_ed    = get_theme_mod( 'author_layout_streched_ed', $defaults['author_layout_streched_ed'] );
			} else {
				$archive_layout = get_theme_mod( 'archive_layout', $defaults['archive_layout'] );
				$streched_ed    = get_theme_mod( 'archive_layout_streched_ed', $defaults['archive_layout_streched_ed'] );
			}
			$container_layout = ( $archive_layout === 'default' ) ? $container_layout : $archive_layout;
		}

		if ( is_search() ) {
			$search_layout    = get_theme_mod( 'search_layout', $defaults['search_layout'] );
			$container_layout = ( $search_layout === 'default' ) ? $container_layout : $search_layout;
			$streched_ed      = get_theme_mod( 'search_layout_streched_ed', $defaults['search_layout_streched_ed'] );
		}

		if ( rishi_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) ) ) {
			$woocommerce_layout = get_theme_mod( 'woocommerce_layout', $defaults['woocommerce_layout'] );
			$streched_ed        = get_theme_mod( 'woo_layout_streched_ed', $defaults['woo_layout_streched_ed'] );
			$container_layout   = ( $woocommerce_layout === 'default' ) ? $container_layout : $woocommerce_layout;
		}

		$single_content_area_spacing = get_theme_mod( 'single_content_area_spacing', 'both' );
		if ( $single_content_area_spacing === 'both' ) {
			$single_content_area_spacing = 'top:bottom';
		}

		$page_content_area_spacing = get_theme_mod( 'page_content_area_spacing', 'both' );
		if ( $page_content_area_spacing === 'both' ) {
			$page_content_area_spacing = 'top:bottom';
		}
		$search_page_alignment = get_theme_mod( 'search_page_alignment', 'left' );
		$dataattr              = ( $streched_ed == 'yes' ) ? 'data-strech=full' : 'data-strech=none';
		$dataalignment         = ( is_search() ) ? 'data-alignment=' . $search_page_alignment . '' : '';

		$breaddefaults          = Defaults::breadcrumbs_defaults();
		$breadcrumbs_ed_product = get_theme_mod( 'breadcrumbs_ed_single_product', $breaddefaults['breadcrumbs_ed_single_product'] );

		if ( ! is_singular() || ( rishi_is_woocommerce_activated() && is_singular( 'product' ) && $breadcrumbs_ed_product === 'yes' ) ) {
			?>
			<div class="archive-title-wrapper clear" <?php echo esc_attr( $dataalignment ); ?>>
				<div class="rishi-container" <?php echo esc_attr( $dataattr ); ?>>
			<?php
		}
	}
endif;
add_action( 'rishi_site_content_start', 'rishi_archive_title_wrapper_start', 10 );

if ( ! function_exists( 'rishi_archive_heading' ) ) :
	/**
	 * Content End
	 */
	function rishi_archive_heading() {
		$ed_prefix        = get_theme_mod( 'archive_page_prefix_ed', 'no' );
		$ed_archive_title = get_theme_mod( 'archive_page_title_ed', 'yes' );
		$ed_archive_desc  = get_theme_mod( 'archive_page_desc_ed', 'yes' );
		$ed_blog_title    = get_theme_mod( 'ed_blog_title', 'yes' );
		$ed_blog_desc     = get_theme_mod( 'ed_blog_desc', 'no' );

		if ( ! is_singular() ) {
			/**
			 * Rishi After Container Wrap
			*/
			do_action( 'rishi_after_container_wrap' );
		}
		if ( rishi_is_woocommerce_activated() && is_shop() ) {
			$woo_title_panel = get_theme_mod( 'shop_page_title', 'yes' );
			$shoptitle       = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
			if ( ! $shoptitle ) {
				$product_post_type = get_post_type_object( 'product' );
				$shoptitle         = $product_post_type->labels->singular_name;
			}
			if ( $woo_title_panel == 'yes' ) {
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
						<h1 class="category-title"><?php echo esc_html( $shoptitle ); ?></h1>
					</div>
				</section>
				<?php
			}
		}

		// works only for single-product
		if ( is_singular( 'product' ) ) {
			/**
			 * Rishi After Container Wrap
			*/
			do_action( 'rishi_after_container_wrap' );
		}

		if ( is_archive() ) {
			if ( is_category() ) {
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
						<?php if ( $ed_prefix === 'yes' ) : ?>
							<span class="tagged-in"><?php echo esc_html__( 'Browsing Category:', 'rishi' ); ?></span>
						<?php endif; ?>
						<?php
						if ( $ed_archive_title === 'yes' ) {
							echo '<h1 class="category-title">' . esc_html( single_cat_title( '', false ) ) . '</h1>';
						}
						if ( $ed_archive_desc === 'yes' ) {
							the_archive_description( '<div class="archive-description">', '</div>' );
						}
						?>
					</div>
				</section>
				<?php
			} elseif ( is_tag() ) {
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
						<?php if ( $ed_prefix === 'yes' ) : ?>
							<span class="tagged-in"><?php echo esc_html__( 'Browsing Tag:', 'rishi' ); ?></span>
						<?php endif; ?>
						<?php
						if ( $ed_archive_title === 'yes' ) {
							echo '<h1 class="category-title">' . esc_html( single_tag_title( '', false ) ) . '</h1>';
						}
						if ( $ed_archive_desc === 'yes' ) {
							the_archive_description( '<div class="archive-description">', '</div>' );
						}
						?>
					</div>
				</section>
				<?php
			} elseif ( is_year() ) {
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
						<?php if ( $ed_prefix === 'yes' ) : ?>
							<span class="tagged-in"><?php echo esc_html__( 'Browsing Year:', 'rishi' ); ?></span>
						<?php endif; ?>
						<?php
						if ( $ed_archive_title === 'yes' ) {
							echo '<h1 class="category-title">' . esc_html( get_the_date( _x( 'Y', 'yearly archives date format', 'rishi' ) ) ) . '</h1>';
						}
						?>
					</div>
				</section>
				<?php
			} elseif ( is_month() ) {
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
						<?php if ( $ed_prefix === 'yes' ) : ?>
							<span class="tagged-in"><?php echo esc_html__( 'Browsing Month:', 'rishi' ); ?></span>
						<?php endif; ?>
						<?php
						if ( $ed_archive_title === 'yes' ) {
							echo '<h1 class="category-title">' . esc_html( get_the_date( _x( 'F Y', 'monthly archives date format', 'rishi' ) ) ) . '</h1>';
						}
						?>
					</div>
				</section>
				<?php
			} elseif ( is_day() ) {
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
						<?php if ( $ed_prefix === 'yes' ) : ?>
							<span class="tagged-in"><?php echo esc_html__( 'Browsing Day:', 'rishi' ); ?></span>
						<?php endif; ?>
						<?php
						if ( $ed_archive_title === 'yes' ) {
							echo '<h1 class="category-title">' . esc_html( get_the_date( _x( 'F j, Y', 'daily archives date format', 'rishi' ) ) ) . '</h1>';
						}
						?>
					</div>
				</section>
				<?php
			} elseif ( is_tax() ) {
				$tax = get_taxonomy( get_queried_object()->taxonomy );
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
					<?php if ( $ed_prefix === 'yes' ) : ?>
							<span class="tagged-in"><?php echo esc_html__( 'Browsing ', 'rishi' ) . esc_html( $tax->labels->singular_name ); ?></span>
						<?php endif; ?>
						<?php
						if ( $ed_archive_title === 'yes' ) {
							echo '<h1 class="category-title">' . esc_html( single_term_title( '', false ) ) . '</h1>';
						}
						?>
					</div>
				</section>
				<?php
			} elseif ( is_author() ) {
				$author_page_avatar_ed    = get_theme_mod( 'author_page_avatar_ed', 'yes' );
				$author_page_avatar_types = get_theme_mod( 'author_page_avatar_types', 'circle' );
				$author_page_label        = get_theme_mod( 'author_page_label', __( 'By', 'rishi' ) );
				?>
				<section class="rishi-author-box">
					<div class="autor-section">
						<div class="author-top-wrap">
							<?php
							if ( $author_page_avatar_ed === 'yes' ) {
								?>
								<div class="img-holder" data-avatar='<?php echo esc_attr( $author_page_avatar_types ); ?>' >
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 150 ); ?>
								</div>
								<?php
							}
							?>
							<div class="author-meta">
								<h1 class="author-name">
									<span class="vcard">
										<?php printf( esc_html__( '%1$s %2$s', 'rishi' ), esc_html( $author_page_label ), esc_html( get_the_author_meta( 'display_name' ) ) ); ?>
									</span>
								</h1>
								<?php
								if ( get_the_author_meta( 'description' ) ) {
									echo '<div class="author-description">' . wp_kses_post( get_the_author_meta( 'description' ) ) . '</div>';
								}
								do_action( "rishi_authors_social" );
								?>
							</div>
						</div>
					</div>
				</section>
				<?php
			}
		} elseif ( is_search() ) {
			?>
			<section class="search-result-wrapper">
				<div class="rishi-searchres-inner">
					<?php rishi_search_page_label(); ?>
					<?php get_search_form(); ?>
				</div>
			</section>
			<?php
		} elseif ( ! is_front_page() && is_home() ) {
			?>
			<section class="tagged-in-wrapper">
				<div class="rishi-tagged-inner">
					<?php
					if ( $ed_blog_title === 'yes' ) {
						echo '<h1 class="blog-page-title">' . esc_html( single_post_title( '', false ) ) . '</h1>';
					}
					if ( $ed_blog_desc === 'yes' ) {
						echo '<div class="blog-page-description">' . wp_kses_post( get_the_content( '', '', get_option( 'page_for_posts' ) ) ) . '</div>';
					}
					?>
				</div>
			</section>
			<?php
		} elseif( is_page_template( 'portfolio.php' ) && rishi_is_pro_activated() ){
			$portfolio_page_title_ed  = get_theme_mod('portfolio_page_title_ed', 'no');
			$portfolio_breadcrumbs_ed = get_theme_mod( 'breadcrumbs_portfolio_page_title', 'no' );
			if( $portfolio_page_title_ed === 'yes' ){
				/**
				 * Rishi After Container Wrap
				 */
				if( $portfolio_breadcrumbs_ed === 'yes' ) do_action('rishi_after_container_wrap'); 
				?>
				<section class="tagged-in-wrapper">
					<div class="rishi-tagged-inner">
						<?php echo '<h1 class="blog-page-title">'. esc_html( single_post_title( '', false ) ) .'</h1>'; ?>
					</div>
				</section>
			<?php }
		}
	}
	endif;
add_action( 'rishi_site_content_start', 'rishi_archive_heading', 20 );

if ( ! function_exists( 'rishi_search_page_label' ) ) :
	/**
	 * Rishi Search label
	 */
	function rishi_search_page_label() {
		$search_page_label = get_theme_mod( 'search_page_label', __( 'Search Result for:', 'rishi' ) );
		?>
			<?php if ( $search_page_label ) { ?>
				<h1 class="search-res">
					<?php echo sprintf( '%s  %s', esc_html( $search_page_label ),  esc_html( get_search_query() ) ); ?>
				</h1>
				<?php
			}
	}
endif;

if ( ! function_exists( 'rishi_archive_search_header_count' ) ) :
	/**
	 * Content End
	 */
	function rishi_archive_search_header_count() {
		if ( is_archive() || is_search() || is_author() ) {
			rishi_search_post_count();
		}
	}
	endif;
add_action( 'rishi_site_content_start', 'rishi_archive_search_header_count', 30 );

if ( ! function_exists( 'rishi_archive_title_wrapper_end' ) ) :
	/**
	 * Wrapper End
	 */
	function rishi_archive_title_wrapper_end() {
		$breaddefaults          = Defaults::breadcrumbs_defaults();
		$ed_breadcrumbs_product = get_theme_mod( 'breadcrumbs_ed_single_product', $breaddefaults['breadcrumbs_ed_single_product'] );
		if ( ! is_singular() ||
		( rishi_is_woocommerce_activated() && is_singular( 'product' ) && $ed_breadcrumbs_product === 'yes' )
		) {
			?>
			</div>
		</div>
			<?php
		}
	}
	endif;
add_action( 'rishi_site_content_start', 'rishi_archive_title_wrapper_end', 40 );

if ( ! function_exists( 'rishi_content_end' ) ) :
	/**
	 * Content End
	 */
	function rishi_content_end() {
		?>
			</div><!-- .main-content-wrapper -->
		</div><!-- .rishi-container-->
		<?php do_action( 'rishi_site_content_end' ); ?>
		<?php do_action( 'rishi_content_bottom' ); ?>
		</div><!-- .site-content -->
		<?php do_action( 'rishi_content_after' ); ?>
		<?php
	}
	endif;
add_action( 'rishi_before_footer', 'rishi_content_end', 20 );

if ( ! function_exists( 'rishi_footer_end' ) ) :
	/**
	 * Footer End
	 */
	function rishi_footer_end() {
		?>
		</footer><!-- #colophon -->
		<?php
	}
	endif;
add_action( 'rishi_footer', 'rishi_footer_end', 50 );

if ( ! function_exists( 'rishi_scrolltotop' ) ) :
	/**
	 * Scroll To Top
	 */
	function rishi_scrolltotop() {
		$defaults             = Defaults::get_layout_defaults();
		$scrolltotop          = get_theme_mod( 'ed_scroll_to_top', $defaults['ed_scroll_to_top'] );
		$top_button_type      = get_theme_mod( 'top_button_type', 'type-1' );
		$top_button_shape     = get_theme_mod( 'top_button_shape', 'square' );
		$top_button_alignment = get_theme_mod( 'top_button_alignment', 'right' );

		$top_button_scroll_style = get_theme_mod( 'top_button_scroll_style', 'filled' );

		switch ( $top_button_type ) {

			case 'type-1':
				$svg_image = Helpers::get_svg_by_name( 'top-1' );
				break;

			case 'type-2':
				$svg_image = Helpers::get_svg_by_name( 'top-2' );
				break;

			case 'type-3':
				$svg_image = Helpers::get_svg_by_name( 'top-3' );
				break;

			case 'type-4':
				$svg_image = Helpers::get_svg_by_name( 'top-4' );
				break;
			default:
				$svg_image = '';
				break;
		}

		$class           = ' to_top';
		$devices_classes = get_theme_mod(
			'back_top_visibility',
			array(
				'desktop' => 'desktop',
				'tablet'  => 'tablet',
				'mobile'  => 'mobile',
			)
		);

		if ( $top_button_type ) {
			$class .= ' top-' . $top_button_type;
		}

		if ( $top_button_shape ) {
			$class .= ' top-shape-' . $top_button_shape;
		}

		if ( $top_button_alignment ) {
			$class .= ' top-align-' . $top_button_alignment;
		}

		if ( $top_button_scroll_style ) {
			$class .= ' top-scroll-' . $top_button_scroll_style;
		}

		$class .= rishi_visibility_for_devices( $devices_classes );

		if ( $scrolltotop == 'yes' ) {
			?>
			<button
				class="<?php echo esc_attr( $class ); ?>"
				aria-label= "<?php echo esc_attr__( 'Back to top', 'rishi' ); ?>"
			>
				<?php
				if ( $svg_image ) {
					echo $svg_image;
				}
				?>
			</button>
			<?php
		}
	}
endif;
add_action( 'rishi_after_footer', 'rishi_scrolltotop', 30 );

/**
 * Header Code Ends Here
 * Footer Code Starts Here
 */

if ( ! function_exists( 'rishi_page_end' ) ) :
	/**
	 * Page End
	 */
	function rishi_page_end() {
		?>
		</div><!-- #page -->
		<?php
	}
endif;
add_action( 'rishi_after_footer', 'rishi_page_end', 20 );

/**
 * 404 Template functions goes here
 */
if ( ! function_exists( 'rishi_404_topsection' ) ) :
	/**
	 * 404 Top Section
	 */
	function rishi_404_topsection() {
		$image404 = get_theme_mod( '404_image' );
		if ( ! empty( $image404 ) && is_array( $image404 ) && isset( $image404['_value']['attachment_id'] ) ) {
			$attachment_id = $image404['_value']['attachment_id'];
		}
		?>
		<section class="fourofour-main-wrap">
			<div class="four-o-four-inner">
				<div class="four-error-wrap">
				<?php
				if ( isset( $attachment_id ) && is_numeric( $attachment_id ) ) {
					?>
						<figure>
							<?php echo wp_get_attachment_image( $attachment_id, 'full' ); ?>
						</figure>
					<?php } else { ?>
						<figure>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/inc/assets/images/404-error.png' ); ?>" alt="<?php esc_attr_e( '404 Not Found', 'rishi' ); ?>">
						</figure>
					<?php } ?>
					<div class="four-error-content">
						<h1 class="error-title"><?php esc_html_e( '404 Error!', 'rishi' ); ?></h1>
						<h4 class="error-sub-title"><?php esc_html_e( 'OOPS! That page can&#39;t be found.', 'rishi' ); ?></h4>
						<p class="error-desc"><?php esc_html_e( 'The page you are looking for may have been moved, deleted, or possibly never existed.', 'rishi' ); ?></p>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
endif;
add_action( 'rishi_404_page_content', 'rishi_404_topsection', 10 );

/**
 * 404 Template functions goes here
 */
if ( ! function_exists( 'rishi_404_search' ) ) :
	/**
	 * 404 Search Section
	 */
	function rishi_404_search() {
		$show_search = get_theme_mod( '404_show_search_form', 'yes' );
		if ( $show_search === 'yes' ) {
			?>
			<section class="error-search-again-wrapper">
				<div class="error-search-inner">
					<?php get_search_form(); ?>
				</div>
			</section>
			<?php
		}
	}
endif;
add_action( 'rishi_404_page_content', 'rishi_404_search', 20 );

/**
 * 404 Template functions goes here
 */
if ( ! function_exists( 'rishi_404_latestposts' ) ) :
	/**
	 * Scroll To Top
	 */
	function rishi_404_latestposts() {
		$show_latest_post = get_theme_mod( '404_show_latest_post', 'yes' );
		$no_of_posts      = get_theme_mod( '404_no_of_posts', 3 );
		$no_of_posts_row  = get_theme_mod( '404_no_of_posts_row', 3 );
		?>
		<main id="primary" class="site-main">
			<?php
				$args = array(
					'post_type'           => 'post',
					'posts_status'        => 'publish',
					'ignore_sticky_posts' => true,
					'posts_per_page'      => $no_of_posts,
				);
				$qry  = new WP_Query( $args );

				if ( $qry->have_posts() && $show_latest_post === 'yes' ) {
					?>
					<div class="rishi-container-wrap col-per-<?php echo absint( $no_of_posts_row ); ?>">
						<?php
							/**
							 * Rishi After Container Wrap
							 */
							do_action( 'rishi_before_container_wrap' );
						?>
						<h2 class="recommended-title"><?php esc_html_e( 'Recommended Articles', 'rishi' ); ?></h2>
						<div class="posts-wrap">
						<?php
						while ( $qry->have_posts() ) {
							$qry->the_post();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="blog-post-lay">
									<div class="post-content">
										<div class="entry-content-main-wrap">
											<?php
											if ( has_post_thumbnail() ) {
												echo '<div class="post-thumb"><div class="post-thumb-inner-wrap">';
												?>
												<a class="post-thumbnail" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'rishi-withsidebar' ); ?></a>
												<?php
												echo '</div><!-- .post-thumb-inner-wrap --></div><!-- .post-thumb -->';
											}
											?>
											<div class="entry-meta-pri-wrap">
												<div class="entry-meta-sec">
													<?php rishi_categories(); ?>
												</div>
											</div>
											<header class="entry-header">
												<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
											</header>

										</div>
									</div>
								</div>
							</article>
						<?php } ?>
						</div>
						<?php rishi_404_show_blog_page_button_label(); ?>
					</div>
					<?php
					wp_reset_postdata();
				}
				?>
		</main>
		<?php
	}
endif;
add_action( 'rishi_404_page_content', 'rishi_404_latestposts', 30 );


/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function rishi_body_classes( $classes ) {
	$defaults            = Defaults::get_layout_defaults();
	$container_layout    = get_theme_mod( 'layout', $defaults['layout'] );
	$blog_page_layout    = get_theme_mod( 'blog_page_layout', $defaults['blog_page_layout'] );
	$editor_options      = get_option( 'classic-editor-replace' );
	$allow_users_options = get_option( 'classic-editor-allow-users' );
	$underlinestyle      = get_theme_mod( 'underlinestyle', $defaults['underlinestyle'] );
	$transparent_loc     = get_theme_mod( 'transparent_header_locations', array() );
 
	if ( is_archive() ) {
		if ( is_author() ) {
			$blog_page_layout = get_theme_mod( 'author_page_layout', $defaults['author_page_layout'] );
		} else {
			$blog_page_layout = get_theme_mod( 'archive_page_layout', $defaults['archive_page_layout'] );
		}
	}

	if ( is_search() ) {
		$blog_page_layout = get_theme_mod( 'search_page_layout', $defaults['search_page_layout'] );
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_page() ) {

		$page_content_area         = 'boxed';
		$page_content_style_source = 'inherit';

		$page_content_style_source = Helpers::get_meta( get_the_ID(), 'content_style_source', 'inherit' );

		if ( $page_content_style_source === 'custom' ) {
			$page_content_area = Helpers::get_meta( get_the_ID(), 'content_style', 'boxed' );
		}

		$page_layout      = get_theme_mod( 'page_layout', $defaults['page_layout'] );
		$container_layout = ( $page_layout === 'default' ) ? $container_layout : $page_layout;
		$container_layout = ( $page_content_style_source === 'custom' ) ? $page_content_area : $container_layout;
	}

	if ( is_single() ) {
		$post_content_area         = 'boxed';
		$post_content_style_source = 'inherit';

		$post_content_style_source = Helpers::get_meta( get_the_ID(), 'content_style_source', 'inherit' );

		if ( $post_content_style_source === 'custom' ) {
			$post_content_area = Helpers::get_meta( get_the_ID(), 'content_style', 'boxed' );

		}

		$blog_post_layout = get_theme_mod( 'blog_post_layout', $defaults['blog_post_layout'] );
		$container_layout = ( $blog_post_layout === 'default' ) ? $container_layout : $blog_post_layout;
		$container_layout = ( $post_content_style_source === 'custom' ) ? $post_content_area : $container_layout;

		if ( ( get_theme_mod( 'ed_link_highlight', 'yes' ) === 'yes' ) && $underlinestyle ) {
			$classes[] = 'link-highlight-' . esc_attr( $underlinestyle ) . '';
		}
	}

	if ( is_home() ) {
		$blog_container   = get_theme_mod( 'blog_container', $defaults['blog_container'] );
		$container_layout = ( $blog_container === 'default' ) ? $container_layout : $blog_container;
	}

	if ( is_archive() ) {
		if ( is_author() ) {
			$archive_layout = get_theme_mod( 'author_layout', $defaults['author_layout'] );
		} else {
			$archive_layout = get_theme_mod( 'archive_layout', $defaults['archive_layout'] );
		}
		$container_layout = ( $archive_layout === 'default' ) ? $container_layout : $archive_layout;
	}

	if ( is_search() ) {
		$search_layout    = get_theme_mod( 'search_layout', $defaults['search_layout'] );
		$container_layout = ( $search_layout === 'default' ) ? $container_layout : $search_layout;
	}

	if ( rishi_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || is_singular( 'product' ) || is_cart() ) ) {
		$woocommerce_layout = get_theme_mod( 'woocommerce_layout', $defaults['woocommerce_layout'] );
		$container_layout   = ( $woocommerce_layout === 'default' ) ? $container_layout : $woocommerce_layout;
	}

	if ( rishi_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) ) {
		$classes[] = 'woocommerce-archive';
	}

	if ( rishi_is_woocommerce_activated() && is_cart() ) {
		$classes[] = 'woocommerce';
	}

	switch ( $container_layout ) {
		case 'boxed':
			$classes[] = 'box-layout';
			break;
		case 'content_boxed':
			$classes[] = 'content-box-layout';
			break;
		case 'full_width_contained':
			$classes[] = 'default-layout';
			break;
		case 'full_width_stretched':
			$classes[] = 'fluid-layout';
			break;
	}

	if ( is_home() || is_archive() || is_search() ) {
		switch ( $blog_page_layout ) {
			case 'classic':
				$classes[] = 'blog-classic';
				break;
			case 'listing':
				$classes[] = 'blog-list';
				break;
			case 'grid':
				$classes[] = 'blog-grid';
				break;
			case 'masonry_grid':
				$classes[] = 'blog-grid-masonry';
				break;
		}
	}

	if (
		! rishi_is_classic_editor_activated() ||
		( rishi_is_classic_editor_activated() && $editor_options == 'block' ) ||
		( rishi_is_classic_editor_activated() && $allow_users_options == 'allow' &&
		has_blocks() )
	) {
		$classes[] = 'rishi-has-blocks';
	}

	$classes[] = rishi_sidebar( true );

	$transparent_header = get_theme_mod( 'has_transparent_header', 'no' );
	if ( isset( $transparent_header ) && 'yes' === $transparent_header ) {
		if ( in_array( 'homepage', $transparent_loc ) ) {
			if ( is_front_page() ) {
				$classes[] = 'transparent-active';
			}
		}
	
		if ( in_array( 'all_pages', $transparent_loc ) ) {
			if ( is_page() ) {
				$classes[] = 'transparent-active';
			}
		}
	
		if ( in_array( get_the_id(), $transparent_loc ) ) {
			$classes[] = 'transparent-active';
		}
	}

	$transparent_header = Helpers::get_meta( get_the_ID(), 'has_transparent_header', 'no' );
	if ( 'yes' === $transparent_header ) {
		$classes[] = 'transparent-active';
	}

	return $classes;
}
add_filter( 'body_class', 'rishi_body_classes' );

if ( ! function_exists( 'rishi_post_classes' ) ) :
	/**
	 * Add custom classes to the array of post classes.
	 */
	function rishi_post_classes( $classes, $class, $post_id ) {

		$classes[] = 'rishi-post';
		$ed_bookmark  = rishi_is_bookmark_enabled();

		if ( ! has_post_thumbnail( $post_id ) ) {
			$classes[] = 'no-post-thumbnail';
		}

		if ( is_single() ) {
			$classes[] = 'rishi-single post-autoload';
		}
		if( $ed_bookmark ){
			$classes[] = 'bookmark';
		}
		return $classes;
	}
endif;
add_filter( 'post_class', 'rishi_post_classes', 10, 3 );

/**
 * Demo Importer Plus compatibility settings
 */
add_filter(
	'demo_importer_plus_api_url',
	function( $api_url ) {
		return 'https://rishidemos.com/';
	}
);

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function rishi_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'rishi_pingback_header' );

