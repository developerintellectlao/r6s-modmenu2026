<?php
/**
 * Rishi Custom Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Rishi
 */

//  error_reporting(E_ALL);
// ini_set('display_errors', '1');

$theme_data = wp_get_theme();
if( ! defined( 'RISHI_VERSION' ) ) define( 'RISHI_VERSION', $theme_data->get( 'Version' ) );
if( ! defined( 'RISHI_NAME' ) ) define( 'RISHI_NAME', $theme_data->get( 'Name' ) );
if( ! defined( 'RISHI_TEXTDOMAIN' ) ) define( 'RISHI_TEXTDOMAIN', $theme_data->get( 'TextDomain' ) );   


// Customizer Builder directory.
defined( 'THEME_CUSTOMIZER_BUILDER_DIR_' ) || define( 'THEME_CUSTOMIZER_BUILDER_DIR_', get_template_directory() . '/customizer' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Google Fonts.
 */
require get_template_directory() . '/inc/typography/google-fonts.php';

/**
 * Custom Functions for the theme
 */
require get_template_directory() . '/inc/custom-functions.php';

/**
 * SimpleXLSXGen library include
 */
require get_template_directory() . '/simplexlsxgen/src/SimpleXLSXGen.php';
use Shuchkin\SimpleXLSXGen;

/**
 * Extras Code
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
/**
 * Customizer Init Files
 */
require get_template_directory() . '/customizer/init.php';

/**
 * Dynamic Editor Styles
 */
require get_template_directory() . '/inc/editor.php';

/**
 * Elementor Compatibility for the theme
 */
if ( rishi_is_elementor_activated() ) require get_template_directory() . '/inc/elementor-compatibility.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) require get_template_directory() . '/inc/woocommerce.php';
/**
 * Load google fonts locally
 */
require get_template_directory() . '/inc/class-webfont-loader.php';

/** 
* Custom Dashboard Functions here
*/
require get_template_directory() . '/inc/classes/class-dashboard.php';

/**
 * Schema Markup here
 */
require get_template_directory() . '/inc/classes/class-microdata.php';

/**
 * Theme Updater
*/
require get_template_directory() . '/updater/theme-updater.php';
/**
 * Custom post get from external API
 */
require_once  get_template_directory() . '/inc/custom-post-api-functions.php';
/**
 * Static CSS 
 *
 * Requires all the path of static_css folder
 *
 * @since 1.0.0
 */
foreach ( glob( get_template_directory() . '/inc/assets/css/static_css/*.php' ) as $file ) {
    require $file;
}

/**
 * Notices
 */
require get_template_directory() . '/updater/notice.php';
/**
 * Meadia release custom
 * 
 */
function get_custom_template(){
    if ( in_category('media-releases') ) {
        get_template_part('single', 'media-releases');
        exit;
    }
    elseif ( in_category('photo-galleries') ) {
        get_template_part('single', 'photo-galleries');
        exit;
    }
}
/**
 * -------Theme Update Disabled function
 */
add_filter( 'auto_update_theme', '__return_false' );

function hide_theme_update_link() {
    echo '<style>
    .notice.notice-warning.notice-alt.notice-large,
    .update-message.notice.inline.notice-warning.notice-alt {
        display: none !important;
    }
</style>';
}
add_action( 'admin_head', 'hide_theme_update_link' );

/**
 * -------Theme Update Disabled function END
 */

/**
 * -------Get custom post title from slug
 */
/* function convert_slug_to_title($slug) {
    // Replace hyphens with spaces
    $string = str_replace('-', ' ', $slug);
    
    // Convert the first letter of each word to uppercase
    $title = ucwords($string);
    
    return $title;
}*/
/**
 * -------Get file size
 */
function get_pdf_file_size_from_url($file_url) {
    // Convert URL to a server path
    $file_path = str_replace(home_url('/'), ABSPATH, $file_url);

    // Check if the file exists
    if (file_exists($file_path)) {
        // Get the file size in bytes
        $file_size = filesize($file_path);
        // Return the file size in a human-readable format
        return size_format($file_size, 2);
    } else {
        return 'File not found.';
    }
}

/**
 * -------Get filename
 */

function get_fileName($post_id, $fieldName) {
    // Assuming you have the attachment ID
    $attachment_id = get_post_meta($post_id, $fieldName, true);
    $file_name = "";
    if ($attachment_id) {
        // Get the file URL
        $file_url = wp_get_attachment_url($attachment_id);

        // Get the file name from the URL
        $file_name = basename($file_url);
    }
    return $file_name;
}

/**
 *  Custom breadcrumbs
 */
/*function custom_breadcrumbs() {
    global $post;
    $separator = ' » ';
    $home_title = 'Home';

    echo '<div class="breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
            <div class="container">';
    
    if (is_front_page()) {
         echo '<a href="' . home_url() . '">' . $home_title . '</a>';
    } else {
        echo '<a href="' . home_url() . '"><svg aria-hidden="true" width="10px" class="e-font-icon-svg e-fas-long-arrow-alt-left" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg> &nbsp; </a>' . $separator;
        echo '<a href="' . home_url() . '">' . $home_title . '</a> ' . $separator;

        if (is_single()) {
            //echo "is Single page";
            $category = get_the_category();
            $category = get_the_category();
            if ($category) {
                $category_link = get_category_link($category[0]->term_id);
                echo '<a href="' . $category_link . '" class="breadcrumb-1">' . $category[0]->name . '</a> ' . $separator;
            }
            echo '<span>' . get_the_title() . '</span>';
        } elseif (is_page()) {
           // echo "is page </br>";
            if ($post->post_parent) {
                echo "is page parent";
                $ancestors = get_post_ancestors($post->ID);
                foreach ($ancestors as $ancestor) {
                    echo '<a href="' . get_permalink($ancestor) . '" class="breadcrumb-1">' . get_the_title($ancestor) . '</a> ' . $separator;
                }
                echo '<span>' . get_the_title() . '</span>';
            } else {
                echo "is page simple";
                echo '<span>' . get_the_title() . '</span>';
            }
        } elseif (is_category()) {
            echo '<span>' . single_cat_title('', false) . '</span>';
        } elseif (is_archive()) {
           // echo "is_archive";
            $taxonomy = get_queried_object();
            if ($taxonomy) {
                $term = get_term($taxonomy);
                $all_cat = get_category_hierarchy_by_term_id($term->term_id, $taxonomy->taxonomy);
                //echo $all_cat['parents'];
                echo str_replace('>/', '> '.$separator.' ', $all_cat['parents']);
            }
        } elseif (is_search()) {
            echo '<span>Search results for "' . get_search_query() . '"</span>';
        } elseif (is_404()) {
            echo '<span>404 Error</span>';
        }
    }
    echo '</div></div>';
}*/

/*function get_child_categories_by_parent($parent_term_id, $post_id, $taxonomy = 'category') {
    // Retrieve all child categories for the given parent term ID
    $child_terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'parent' => $parent_term_id,
        'hide_empty' => false, // Show terms even if they don't have posts
    ));
    
    $filtered_terms = array();
    
    // Loop through each child term and check if it has posts associated with the given post ID
    foreach ($child_terms as $term) {
        $term_posts = new WP_Query(array(
            'post_type' => get_post_type($post_id), // Get post type of the given post ID
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $term->term_id,
                ),
            ),
            'fields' => 'ids', // We only need post IDs
        ));
        
        // Check if there are any posts for this term
        if (in_array($post_id, $term_posts->posts)) {
            $filtered_terms[] = $term;
        }
    }
    
    return $filtered_terms;
}*/

function get_filtered_term_children_by_post($term_id, $taxonomy, $post_id) {
    // Get all child term IDs of the specified parent term
    $child_term_ids = get_term_children($term_id, $taxonomy);

    // Initialize an empty array to store terms associated with the post
    $filtered_terms = array();

    if (!empty($child_term_ids)) {
        foreach ($child_term_ids as $child_term_id) {
            // Query posts associated with this child term
            $term_posts = new WP_Query(array(
                'post_type' => get_post_type($post_id), // Ensure the post type matches
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $child_term_id,
                    ),
                ),
                'fields' => 'ids', // We only need post IDs
            ));

            // Check if the given post ID is among the posts associated with this term
            if (in_array($post_id, $term_posts->posts)) {
                $term = get_term($child_term_id, $taxonomy); // Get term object and add to result
                if (!is_wp_error($term) && $term) {
                    // Get the term link
                    $term->term_link = get_term_link($term);
                }
                $filtered_terms[] =  $term;
            }
        }
    }

    return $filtered_terms;
}


function custom_breadcrumbs() {
    global $post;
    // $separator = ' » ';
    $separator = ' <i class="fa fa-angle-double-right ms-2 me-2" aria-hidden="true" style="font-size: 10px;"></i> ';
    $home_title = 'Home';

    echo '<div class="breadcrumb gray-bg _theme-bg-50 d-none d-md-block" style="margin-bottom: 0;">
            <div class="container">';
    
    if (is_front_page()) {
        echo '<a href="' . home_url() . '">' . $home_title . '</a>';
    } else {
        echo '<a href="' . home_url() . '"><svg aria-hidden="true" width="10px" class="e-font-icon-svg e-fas-long-arrow-alt-left" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z"></path></svg> &nbsp; </a>' . $separator;
        echo '<a href="' . home_url() . '">' . $home_title . '</a> ' . $separator;
        if (is_single()) {
            // Get custom post type and taxonomy
            $post_type = get_post_type();
            $taxonomy = ''; // Default empty

            if ($post_type !== 'post') {
                $taxonomy = get_queried_object();
                $taxonomies = get_object_taxonomies($post_type, 'objects');
                if ($taxonomies) {
                    $taxonomy = key($taxonomies); // Get the first taxonomy
                }
            } else {
                $taxonomy = 'category';
            }

            // Get the terms (categories) for the post
            //echo "postid... ". $post->ID . " taxonomy ".$taxonomy;
            $terms = get_the_terms($post->ID, $taxonomy);
            if ($terms && !is_wp_error($terms)) {
                $term = array_shift($terms); // Get the first term
                $all_cat = get_category_hierarchy_by_term_id($term->term_id, $term->taxonomy);
                // if (str_contains($all_cat['parents'], 'Publications')) { 
                //     $all_cat['parents'] = str_replace('<a href="'.get_site_url().'/publications_cat/publications/">Publications</a>','<a href="'.get_site_url().'/publication/">Publications</a>',$all_cat['parents']);
                // }
                if (!empty($all_cat['children'])) {
                    $filtered_child_terms = get_filtered_term_children_by_post($term->term_id,$term->taxonomy, $post->ID);
                    if (!empty($filtered_child_terms)) {
                        // echo str_replace('>/', '> ' . $separator . ' ', $all_cat['parents'] . ' <a href="' . $filtered_child_terms[0]->term_link . '">' . $filtered_child_terms[0]->name. '</a> ' . $separator);
                        if($filtered_child_terms[0]->term_id === 123 && $filtered_child_terms[0]->slug === 'former-ceos'){
                            echo str_replace('>/', '> ' . $separator . ' ', 'About ' .$separator . ' MRC Governance '.$separator. ' <a href="/mrc-former-ceos/">' . $filtered_child_terms[0]->name. '</a> ' . $separator);
                            // echo str_replace('>/', '> ' . $separator . ' ', 'About ' .$separator . ' MRC Secretariat '.$separator. ' <a href="' . $filtered_child_terms[0]->term_link . '">' . $filtered_child_terms[0]->name. '</a> ' . $separator);
                         }else{
                             echo str_replace('>/', '> ' . $separator . ' ', $all_cat['parents'] . ' <a href="' . $filtered_child_terms[0]->term_link . '">' . $filtered_child_terms[0]->name. '</a> ' . $separator);
                         }
                    }else{
                        if (str_contains($all_cat['parents'], 'Media Releases')) { 
                            echo '<a href="/news_and_events_cat/news/">News</a>'.$separator. str_replace('>/', '> ' . $separator . ' ', $all_cat['parents']);
                        }else{
                            echo str_replace('>/', '> ' . $separator . ' ', $all_cat['parents']);
                        }
                    }
                }else{
                    echo str_replace('>/', '> ' . $separator . ' ', $all_cat['parents']);
                }
            }
            echo '<span>' . get_the_title() . '</span>';
        } elseif (is_page()) {
            if ($post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                foreach ($ancestors as $ancestor) {
                    echo '<a href="' . get_permalink($ancestor) . '" class="breadcrumb-1">' . get_the_title($ancestor) . '</a> ' . $separator;
                }
                echo '<span>' . get_the_title() . '</span>';
            } else {
                echo '<span>' . get_the_title() . '</span>';
            }
        } elseif (is_category()) {
            if(single_cat_title('', false) === 'Media Releases'){
                echo '<a href="/news_and_events_cat/news/">News</a> '.$separator.' <span>' . single_cat_title('', false) . '</span>';
            }else{
                echo '<span>' . single_cat_title('', false) . '</span>';
            }
        } elseif (is_archive()) {
            $taxonomy = get_queried_object();
            if ($taxonomy) {
                $term = get_term($taxonomy);
                $all_cat = get_category_hierarchy_by_term_id($term->term_id, $taxonomy->taxonomy);
                // if (str_contains($all_cat['parents'], 'Publications')) { 
                //     $all_cat['parents'] = str_replace('<a href="'.get_site_url().'/publications_cat/publications/">Publications</a>','<a href="'.get_site_url().'/publication/">Publications</a>',$all_cat['parents']);
                // }
                $output = str_replace('>/', '> ' . $separator . ' ', $all_cat['parents']);
               // Remove the last separator
                if (($pos = strrpos($output, $separator)) !== false) {
                    $output = substr($output, 0, $pos);
                }
                echo $output;
            }
        } elseif (is_search()) {
            echo '<span>Search results for "' . get_search_query() . '"</span>';
        } elseif (is_404()) {
            echo '<span>404 Error</span>';
        }
    }
    echo '</div></div>';
}


function get_category_hierarchy_by_term_id($term_id, $taxonomy = 'category') {
    // Get the term object for the provided term ID
    $term = get_term($term_id, $taxonomy);
    if (is_wp_error($term)) {
        return [];
    }

    // Get parent terms
    $parents = get_term_parents_list($term_id, $taxonomy, ['format' => 'array']);
    if (is_wp_error($parents)) {
        $parents = [];
    }
    // Get child terms

    $children = get_term_children($term_id, $taxonomy);
    if (is_wp_error($children)) {
        $children = [];
    } else {
        $children = get_terms([
            'taxonomy' => $taxonomy,
            'include' => $children,
            'hide_empty' => true,
        ]);
    }
    return [
        'term' => $term,
        'parents' => $parents,
        'children' => $children,
    ];
}


function get_post_years($post_type = 'post', $term_id, $taxonomy = 'category') {
    global $wpdb;
    
    $years = $wpdb->get_col($wpdb->prepare("
        SELECT DISTINCT YEAR(p.post_date) 
        FROM $wpdb->posts p
        INNER JOIN $wpdb->term_relationships tr ON (p.ID = tr.object_id)
        INNER JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
        WHERE p.post_status = 'publish' 
        AND p.post_type = '%s' 
        AND tt.taxonomy = %s 
        AND tt.term_id = %d
        ORDER BY p.post_date DESC
    ", $post_type, $taxonomy, $term_id));
    return $years; 
}

/**
 *  Custom post year dropdown
 */

function display_year_dropdown($post_type, $taxonomy) {
    $term_id = "";
    $category = get_queried_object();
    if($category->term_id){
        $term_id = $category->term_id;
    }else{
        $term_id = 24;
    }
    $current_url = get_category_link($term_id);
    // if (str_contains($current_url, 'category/')) {
    //     $current_url = str_replace('category/','',$current_url);
    // }
    $years = get_post_years($post_type, $term_id, $taxonomy);
    if (!empty($years)) {
          echo '<form action="'.$current_url.'" method="get">';
          echo '<select class="custom-select" name="post_year" id="post_year" onchange="this.form.submit();">';
          echo '<option value="">Select Year</option>';

          foreach ($years as $year) {
             // Check if the current year is selected
             $selected = (isset($_GET['post_year']) && $_GET['post_year'] == $year) ? 'selected' : '';
             echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
          }

          echo '</select>';
          echo '</form>';
    }
}

function custom_year_dropdown($post_type, $taxonomy) {
    $term_id = "";
    $category = get_queried_object();
    if($category->term_id){
        $term_id = $category->term_id;
    }else{
        $term_id = 124;
    }
    $current_url = get_category_link($term_id);
    if (str_contains($current_url, 'category/')) {
        $current_url = str_replace('category/','',$current_url);
    }
    $years = get_post_years($post_type, $term_id, $taxonomy);
    if (!empty($years)) {
          echo '<select class="custom-select form-select" name="post_year" id="post_year">';
          echo '<option value="">All</option>';

          foreach ($years as $year) {
             // Check if the current year is selected
             $selected = (isset($_GET['post_year']) && $_GET['post_year'] == $year) ? 'selected' : '';
             echo '<option value="' . $year . '" ' . $selected . '>' . $year . '</option>';
          }

          echo '</select>';
    }
}
/**
 * Custom pagination
 * 
 */

 function custom_pagination($total_pages, $current_page) {
    $show_pages = 7; // Number of pages to show around the current page
    // Display pagination only if there are more than 1 page
    if ($total_pages <= 1) {
        return; // No pagination needed
    }
    // Calculate the start and end page numbers
    $start = max(1, $current_page - floor($show_pages / 2));
    $end = min($total_pages, $current_page + floor($show_pages / 2));

    // Adjust the start and end pages if they are too close to the edges
    if ($end - $start + 1 < $show_pages) {
        if ($current_page < $show_pages) {
            $end = min($total_pages, $show_pages);
        } else {
            $start = max(1, $total_pages - $show_pages + 1);
        }
    }

    echo '<ul class="pagination">';

    // Previous page link
    if ($current_page > 1) {
        echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($current_page - 1)) . '">«</a></li>';
    }

    // Page numbers
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $current_page) {
            echo '<li class="page-item active"><a class="page-link" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a></li>';
        }
    }

    // Next page link
    if ($current_page < $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="' . esc_url(get_pagenum_link($current_page + 1)) . '">»</a></li>';
    }

    echo '</ul>';
}

/**
 * Get Current URL
 */
function get_current_pageURL(){
    return $current_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 *  Create a Custom Endpoint for Download
 */
add_action('init', 'register_procurement_notice_download_route');
function register_procurement_notice_download_route() {
    add_rewrite_rule('^download-procurement-notices/?', 'index.php?download_procurement_notices=1', 'top');
}

add_filter('query_vars', function($vars) {
    $vars[] = 'download_procurement_notices';
    return $vars;
});

add_action('template_redirect', 'procurement_notice_download_handler');
function procurement_notice_download_handler() {
    if (get_query_var('download_procurement_notices')) {
        procurement_notice_generate_excel();
        exit;
    }
}
/**
 * Create the Excel Generation Function
 */
//echo "Sumit ". get_template_directory();
function procurement_notice_generate_excel() {
    // Fetch procurement notices
    $args = array(
        'post_type' => 'procurement_notice',
        'posts_per_page' => -1,
    );
    $posts = get_posts($args);
   
    // Prepare data for Excel
    $data = [
        ['Tender Number', 'Description', 'Procurement Methods', 'Published Date', 'Close Date', 'Notice Type']
    ];

    foreach ($posts as $post) {
        $tender_number = get_field('tender_number', $post->ID); // Replace with ACF or meta key
        $description = $post->post_title; //$post->post_content , post_excerpt;
        $procurement_method = get_field('procurement_method', $post->ID);
        $published_date = get_the_date('d M Y', $post);
        $close_date = get_field('close_date', $post->ID); // Replace with ACF or meta key
        $notice_type = get_field('notice_type', $post->ID);

        $data[] = [
            $tender_number,
            $description,
            $procurement_method,
            $published_date,
            $close_date,
            $notice_type
        ];
    }
    // Generate and download the Excel file
    if (!empty($data)) {
        $xlsx = Shuchkin\SimpleXLSXGen::fromArray( $data );
        $xlsx->downloadAs('procurement_notices_'.time().'.xlsx');
    }
}