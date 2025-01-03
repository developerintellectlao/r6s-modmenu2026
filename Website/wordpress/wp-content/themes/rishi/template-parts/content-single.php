<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Rishi
*/

use Rishi\Customizer\Helpers\Basic as Helpers;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

$posts_defaults  = Defaults::get_posts_default_value();
$defaults        = Defaults::get_layout_defaults();
$post_strucuture = get_theme_mod( 'single_blog_post_meta', Defaults::singlepost_structure_defaults() );
$ed_post_tags    = get_theme_mod( 'ed_post_tags', $defaults['ed_post_tags'] );
$itemprop        = ' itemprop="text"';
$position        = 'First';
$class           = "";
if( get_theme_mod( 'single_blog_post_box_sticky','no') === 'yes' ){
    $box_float  = get_theme_mod( 'single_blog_post_box_float','left');
    $class     .= 'float-' . $box_float;
}
if( class_exists('Rishi_Pro\Rishi_Pro') && wp_is_mobile() && get_theme_mod( 'ed_mobile_truncate', 'no' ) === 'yes' ){
    $class .= ' rishi-mobile-truncate';
}
do_action('rishi_single_before_article'); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); echo rishi_print_schema('article'); ?> >
    <?php do_action('rishi_single_top'); ?>
    <?php do_action('rishi_title_section_before'); ?>
    <header class="entry-header">
        <div class="rishi-entry-header-inner">
            <?php
                foreach( $post_strucuture as $structure ){
					$img_scale = array_key_exists('featured_image_scale', $structure) ? $structure['featured_image_scale'] : '';
                    if( $structure['enabled'] == true && $structure['id'] == 'featured_image' && ( Helpers::get_meta( get_the_ID(), 'disable_featured_image', 'no' ) === 'no' ) ){
                        echo rishi_single_featured_image( $structure['featured_image_ratio'],$img_scale, $structure['featured_image_size'], $structure['featured_image_visibility'] );
                    }

                    if( $structure['enabled'] == true && $structure['id'] == 'categories' ){
                        rishi_category( $structure['separator'] );
                    }

                    if( $structure['enabled'] == true && $structure['id'] == 'custom_meta' ){
                        rishi_post_meta( $structure, $position );
                        $position = 'Second';
                    }
                    if( $structure['enabled'] == true && $structure['id'] == 'custom_title' ){
                        do_action('rishi_title_before');
                            echo '<' . $structure["heading_tag"] . ' class="entry-title">';
                                the_title();
                                rishi_bookmark_settings();
                            echo '</' . $structure["heading_tag"] . '>';
                        do_action('rishi_title_after');
                    }
                    if ( class_exists( 'Rishi_Pro\Rishi_Pro' ) && method_exists( 'Rishi_Pro\Modules\Helpers\Advanced_Blogging\Quick_Summary', 'rishi_quick_summary' ) && $structure['enabled'] === true && $structure['id'] === 'quick_summary' ) {
                        Rishi_Pro\Modules\Helpers\Advanced_Blogging\Quick_Summary::rishi_quick_summary();
                    }
                }
            ?>
        </div>
    </header>
    <?php do_action('rishi_title_section_after'); ?>
    <div class="post-inner-wrap <?php echo esc_attr( $class ); ?>">
        <?php do_action('rishi_single_content_top'); ?>
        <?php if( get_theme_mod( 'single_blog_post_box_sticky','no' ) === 'no' ) do_action( 'rishi_social_share','top' ); ?>
        <?php if( get_theme_mod( 'single_blog_post_box_sticky','no' ) === 'yes' ) do_action( 'rishi_social_share','sticky' ); ?>
        <?php if( get_theme_mod( 'disclaimer_location','bottom' ) === 'top' ) do_action( 'rishi_disclaimer_section', 'rishi_pro_disclaimer' ); ?>
        <div class="entry-content"<?php echo $itemprop; ?>>
            <?php
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'rishi' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'rishi' ),
                        'after'  => '</div>',
                    )
                );
            ?>
        </div>
        <?php if( get_theme_mod( 'disclaimer_location','bottom' ) === 'bottom' ) do_action( 'rishi_disclaimer_section', 'rishi_pro_disclaimer' ); ?>
        <?php do_action('rishi_single_content_bottom'); ?>
    </div>
    <?php
        if( $ed_post_tags == 'yes' && ( Helpers::get_meta( get_the_ID(), 'disable_post_tags', 'no' ) === 'no' ) ){ ?>
            <div class="post-footer-meta-wrap">
                <span class="post-tags meta-wrapper">
                    <?php rishi_tags(); ?>
                </span>
            </div>
            <?php
        } ?>
    <?php if( get_theme_mod( 'single_blog_post_box_sticky','no' ) === 'no' ) do_action( 'rishi_social_share','bottom' ); ?>
    <?php do_action('rishi_single_bottom'); ?>
</article><!-- #post-## -->
