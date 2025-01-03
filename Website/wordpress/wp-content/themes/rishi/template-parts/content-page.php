<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rishi
 */

use Rishi\Customizer\Helpers\Basic as Basic;

$prefix = 'single_page_';

$itemprop                  = ' itemprop="text"';
$page_title_panel          = get_theme_mod( 'page_title_panel', 'yes' );
$image_size                = get_theme_mod( 'single_page_featured_image_size', 'full' );
$image_scale               = get_theme_mod( 'single_page_featured_image_scale', 'contain' );
$featured_image_visibility = get_theme_mod(
	'single_page_featured_image_visibility',
	array(
		'desktop' => 'desktop',
		'tablet'  => 'tablet',
		'mobile'  => 'mobile',
	)
);
$featured_image_visibility = ( Basic::get_meta( get_the_ID(), 'disable_featured_image', 'no' ) === 'no' ? $featured_image_visibility : 'no' );
?>
<article id="post-<?php the_ID(); ?>" 
	<?php
		post_class();
		echo rishi_print_schema( 'article' );
	?>
>
	<?php do_action( 'rishi_title_section_before' ); ?>
	<?php
	if ( ( $page_title_panel === 'yes' ) && ( Basic::get_meta( get_the_ID(), 'page_title_panel', 'inherit' ) !== 'disabled' ) ) :
	?>
		<header class="entry-header">
			<?php
				do_action( 'rishi_title_before' );
					echo '<h1 class="entry-title">';
						the_title();
					echo '</h1>';
				do_action( 'rishi_title_after' );
			?>
		</header><!-- .entry-header -->
	<?php endif; ?>
	<?php do_action( 'rishi_title_section_after' ); ?>

	<?php
		echo rishi_single_featured_image( null, $image_scale, $image_size, $featured_image_visibility );
	?>

	<div class="entry-content"<?php echo $itemprop; ?>>
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'rishi' ),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Edit <span class="screen-reader-text">%s</span>', 'rishi' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
