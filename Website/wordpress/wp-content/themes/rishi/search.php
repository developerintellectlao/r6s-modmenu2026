<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Rishi
 */
get_header();

	/**
	 * Before Posts hook
	*/
	do_action( 'rishi_before_primary_content' ); ?>

	<main id="primary" class="site-main">
		<?php 
			/**
			 * Before Posts hook
			*/
			do_action( 'rishi_before_posts_content' );
        ?>
		<div class="rishi-container-wrap">
			<?php 
				/**
				 * Rishi After Container Wrap
				*/
				do_action( 'rishi_before_container_wrap' );
			?>
			<div class="posts-wrap">
				<?php if ( have_posts() ) : ?>

					<?php
					/**
					 * Before Loop Hook
					*/
					do_action( 'rishi_before_loop' );

					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );
						

					endwhile;

					/**
					 * After Loop Hook
					*/
					do_action( 'rishi_after_loop' );

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>
			</div>
		</div>
		<?php
			/**
			 * After Posts hook
			 * 
			 * @hooked rishi_navigation - 10
			*/
			do_action( 'rishi_after_posts_content' );
        ?>		
	</main><!-- #main -->

<?php
get_sidebar();
get_footer();