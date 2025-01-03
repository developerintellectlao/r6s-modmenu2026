<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Rishi
 */

if ( ! function_exists( 'rishi_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function rishi_posted_on(){
		$time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';
		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date(DATE_W3C) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date(DATE_W3C) ),
			esc_html( get_the_modified_date() )
		);
		echo '<span class="posted-on meta-common">' . $time_string . '</span>';
	}
endif;

if ( !function_exists( 'rishi_updated_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function rishi_updated_on( $meta ){	
		$updated_on = $meta['updated_date_label'] && $meta['show_updated_date_label'] === 'yes' ? 
			'<span class="poson">' . esc_html( $meta['updated_date_label'] ) . '</span>' 
			: '';

		$time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';		

		if( get_the_time('U') !== get_the_modified_time('U') ){
			$time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
		}
		
		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf( '%1$s %2$s', $updated_on, $time_string );

		echo '<span class="posted-on meta-common">' . $posted_on . '</span>';
	}
endif;

if ( !function_exists( 'rishi_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function rishi_posted_by( $meta ){
		
		$enable_schema_org_markup = get_theme_mod( 'enable_schema_org_markup','yes' );
		if( $enable_schema_org_markup === 'yes' ){
			$class = " url fn n";
		}else{
			$class= "url-fn-n";
		}
		$avatar = ( $meta['has_author_avatar'] == 'yes' ) ? get_avatar( get_the_author_meta('ID'), $meta['avatar_size'] ) : '';		
		$byline = esc_html( $meta['label'] ) . '<span class="author vcard"><a class='. esc_attr( $class ) .' href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" itemprop="url"><span itemprop="name">' . esc_html( get_the_author() ) . '</span></a></span>'; ?>
		<span class="posted-by author vcard meta-common" <?php echo rishi_print_schema('comment-author'); ?>>
			<?php echo $avatar . $byline; ?>
		</span>
		<?php
	}
endif;

if (!function_exists('rishi_categories')) :
	/**
	 * Post Category
	 */
	function rishi_categories(){
		if ('post' === get_post_type()) {
			$categories_list = get_the_category_list(' ');
			if ($categories_list) {
				echo '<div class="entry-meta-pri"><span class="cat-links meta-common">' . wp_kses_post($categories_list) . '</span></div>';
			}
		}
	}
endif;

if ( !function_exists( 'rishi_category' ) ) :
	/**
	 * Rishi Category
	 *
	 * @param string $meta
	 * @param string $taxonomy_slug
	 * @return HTML
	 */
	function rishi_category( $meta,  $taxonomy_slug = "category" ){
		global $post;
		$enable_microdata = get_theme_mod( 'enable_microdata','yes' );
		
		$categories_lists = get_the_terms($post->ID, $taxonomy_slug);

		if( $categories_lists ){
			$cat_list = false;
			?>
			<div class="post-meta-inner">
				<span class="cat-links meta-common <?php echo esc_attr( $meta ); ?>">
					<?php foreach( $categories_lists as $term ){ 
						$term_link = isset( $term->term_taxonomy_id ) ? get_term_link( $term->term_taxonomy_id ) : "";
						if(  is_wp_error($term_link) ){ //Skip the loop when get_term_link returns WP_Error in some instances
							if (!$cat_list) echo wp_kses_post(get_the_category_list(' ')); //Check if category is already displayed for the post
							$cat_list = true;
							continue;
						}
						$term_name = isset( $term->name ) ? $term->name : ""; ?>
						<a 
						href="<?php echo esc_url( $term_link ); ?>" 
						rel="category<?php if( $enable_microdata == 'yes' ) echo esc_attr( ' tag' ); ?>">
							<?php echo esc_html( $term_name ); ?>
						</a>
					<?php } ?>
				</span>
			</div>
			<?php 
		}
	}
endif;


if ( !function_exists( 'rishi_tags' ) ) :
	/**
	 * Post Tags
	 */
	function rishi_tags(){
		if ( 'post' === get_post_type() ) {
			$tags_list = get_the_tag_list('', ' ');
			if ( $tags_list ) {
				echo '<span class="tags-links">' . '<span class="tagtext">' . esc_html__('Tagged In', 'rishi') . '</span>' . $tags_list . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

if( ! function_exists( 'rishi_estimated_reading_time' ) ) :
	/** 
	 * Reading Time Calculate Function 
	*/
	function rishi_estimated_reading_time( $meta,$content ) {
		$wpm           = isset( $meta['words_per_minute'] ) ? $meta['words_per_minute'] : 200;
		$clean_content = strip_shortcodes( strip_tags( $content ) );
		$word_count    = str_word_count( $clean_content );
		$time          = ceil( $word_count / $wpm );
		echo '<span class="post-read-time meta-common">' . absint( $time ) . esc_html__( ' min read', 'rishi' ) . '</span>';
	}
endif;

if ( ! function_exists( 'rishi_single_featured_image' ) ) :

	/**
	 * Featured image helper render.
	 *
	 * @return void
	 */
	function rishi_single_featured_image( $featured_image_ratio = NULL,$featured_image_scale = NULL, $featured_image_size = NULL, $featured_image_visibility = NULL ) {
	
		if ( ! $featured_image_visibility ) {
			$featured_image_visibility = [
				'desktop' => 'desktop',
				'tablet'  => 'tablet',
				'mobile'  => 'mobile',
			];
		}

		if ( ! $featured_image_ratio ) {
			$featured_image_ratio = 'auto';
		}

		if ( ! $featured_image_scale ) {
			$featured_image_scale = 'contain';
		}

		if ( ! $featured_image_size ) {
			$featured_image_size = 'full';
		}

		$class = 'rishi-featured-image';

		if( $featured_image_ratio == 'auto' && $featured_image_scale ){
			$class .= ' image-' . $featured_image_scale;
		}

		$class .= ' ' . rishi_visibility_for_devices(
			$featured_image_visibility
		);
		
		if (! has_post_thumbnail()) {
			return '';
		}
		?>
		<figure class="<?php echo esc_attr( $class ); ?>">
			<?php if( is_home() || is_archive() || is_search() ) echo '<a class="post-thumbnail" href="'. esc_url( get_the_permalink( get_the_ID() ) ) .'" title=' . esc_attr(get_the_title(get_post_thumbnail_id())) . '>'; 
					echo wp_get_attachment_image( 
						get_post_thumbnail_id(), 
						$featured_image_size,
						false,
						[
							'loading'       => Rishi\Customizer\Helpers\Basic::post_image_lazyload(),
							'lazyload-type' => get_theme_mod('lazy_load_type', 'fade'),
							'itemprop'      => "image"
						] 
					);
				if( is_home() || is_archive() || is_search() ) echo '</a>';
			?>
		</figure>
		<?php 
		
	}
endif;

if( ! function_exists( 'rishi_404_show_blog_page_button_label' ) ) :
	function rishi_404_show_blog_page_button_label(){
		$show_button                 = get_theme_mod( '404_show_blog_page_button','yes' );
		$show_blog_page_button_label = get_theme_mod( '404_show_blog_page_button_label',__('Go To Blog', 'rishi') );
		$blog                        = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : get_home_url();
		if( $show_button === "yes" ){
			echo '<div class="go-to-blog-wrap">';
				if( $show_blog_page_button_label ){ ?>				
					<a href="<?php echo esc_url( $blog ); ?>" class="go-to-blog"><?php echo esc_html( $show_blog_page_button_label ); ?></a>
				<?php }
			echo '</div>';
		}
		
	} 
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;
