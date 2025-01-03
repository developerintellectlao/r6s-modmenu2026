<?php

/**
 * Recommended Articles - Dynamic CSS
 *
 * @package Rishi
 *
 * @since 1.0.0
 */

use Rishi\Customizer\Helpers\Basic as Helpers;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

add_filter('rishi_dynamic_theme_css', 'rishi_recommended_articles_static_css', 11);

/**
 * Recommended Articles - CSS
 *
 * @param  string $output_css.
 * @return String CSS for Recommended Articles.
 *
 * @since 1.0.0
 */
function rishi_recommended_articles_static_css($output_css)
{

    $defaults        = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
    $ed_related_post = get_theme_mod('ed_related', $defaults['ed_related']);

    if ($ed_related_post == 'no' && is_single() && (Helpers::get_meta(get_the_ID(), 'disable_related_posts', 'no') === 'no')) {
        return $output_css;
    }

    $output_css .= '.recommended-articles {
        display: block;
        padding-top: 40px;
    }
    
    .recommended-articles .blog-single-wid-title {
        font-size: 1.4444em;
        margin-bottom: 25px;
    }

    .recommended-articles .post-content .posted-on {
        font-size: 0.833em;
    }
    
    .recommended-articles .post-content .entry-meta-pri{
        font-size: 0.833em;
    }
    
    .recommended-articles .recomm-artcles-wrap {
        display: grid;
        gap:30px;
    }
    
    @media (min-width: 767px) {
        .recommended-articles.related-posts-per-row-2 .recomm-artcles-wrap {
            grid-template-columns: repeat(2, 1fr);
        }
    
        .recommended-articles.related-posts-per-row-3 .recomm-artcles-wrap {
            grid-template-columns: repeat(3, 1fr);
        }
    
        .recommended-articles.related-posts-per-row-4 .recomm-artcles-wrap {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .post-content {
        background: none;
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .entry-header {
        margin-bottom: 0;
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .entry-title {
        font-size: 1.11em;
        line-height: 1.5;
        margin: 15px 0;
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .post-meta-wrapper {
        padding: 0;
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .post-meta-wrapper .post-meta-inner {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        font-size: 0.83333em;
        font-weight: 400;
        letter-spacing: 0.3px;
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .post-meta-wrapper .post-meta-inner .meta-common.author {
        display: inline-flex;
        align-items: center;
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .post-meta-wrapper .post-meta-inner .meta-common.author .avatar {
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .recommended-articles .recomm-artcles-wrap .recomm-article-singl .post-meta-wrapper .post-meta-inner .meta-common.author .author {
        margin-left: 5px;
    }
    
    .recommended-articles .post-meta-inner .cat-links[data-cat-style="normal"] a {
        color: var(--relatedPostCategoryDividerInitialColor);
    }
    
    .recommended-articles .post-meta-inner .cat-links[data-cat-style="normal"] a:hover {
        color: var(--relatedPostCategoryDividerHoverColor);
    }
    
    .recommended-articles .post-meta-inner .cat-links[data-cat-style=filled] a {
        background: var(--relatedPostCategoryDividerInitialColor);
    }
    
    .recommended-articles .post-meta-inner .cat-links[data-cat-style=filled] a:hover {
        background: var(--relatedPostCategoryDividerHoverColor);
    }';

    return rishi_trim_css($output_css);
}


/********** Related Post **********/
