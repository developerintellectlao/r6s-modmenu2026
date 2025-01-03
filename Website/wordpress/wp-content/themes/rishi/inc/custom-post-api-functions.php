<?php
/**
 * Custom functions used for get post from external API and save into DB.
 *
 */
if (!function_exists('save_publications_from_api')) {
    function save_publications_from_api() {
        $api_url = 'https://api.mrcmekong.org/api/v1/document/doms-documents'; // Replace with your actual API URL
        $api_key = 'f51471f7b89441a48702ac31e370b3ed'; // Replace with your actual API key
        $response = wp_remote_get($api_url, [
            'headers' => [
                'X-API-Key' => $api_key,
            ],
        ]);

        if (is_wp_error($response)) {
            error_log('API Fetch Error: ' . $response->get_error_message());
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($data) || !is_array($data)) {
            error_log('API Response is empty or invalid');
            return;
        }
        //echo "<pre>".  count($data). "</pre></br>";
        $avl_t = 0; $not_avl_t = 0;
       
        //echo "avl_t > ". $avl_t . " not_avl_t ". $not_avl_t;
        $count = 0;
       
        foreach ($data as $publication) {
            // Skip if `topics` is not available
            if (empty($publication['topics'])) {
                continue;
            }

            // Check if post already exists by external ID
            $existing_post = get_posts([
                'post_type'   => 'publications',
                'meta_key'    => 'external_id',
                'meta_value'  => $publication['id'],
                'numberposts' => 1,
            ]);
            $post_title = str_replace(".pdf","",$publication['name']);
            $post_date = isset($publication['publicationDate']) ? date('Y-m-d H:i:s', strtotime($publication['publicationDate'])) : current_time('mysql');
            $created_date = isset($publication['createdDateTime']) ? $publication['createdDateTime'] : null;

            if (empty($existing_post)) {
                // Insert new post
                $post_id = wp_insert_post([
                    'post_title'   => $post_title,
                    'post_content' => !empty($publication['abstract']) ? $publication['abstract'] : "",
                    'post_type'    => 'publications',
                    'post_status'  => 'publish',
                    'post_date'  => $post_date,
                ]);

                if ($post_id) {
                    $count++;
                    // Save custom fields
                    update_post_meta($post_id, 'doi', $publication['doi']);
                    if(!empty($publication['keywords'])){
                        update_post_meta($post_id, 'keywords', implode(', ', $publication['keywords']));
                    }
                    if ($created_date) {
                        update_post_meta($post_id, 'createddatetime', $created_date);
                    }
                    update_post_meta($post_id, 'language', $publication['publicationLanguage']);
                    update_post_meta($post_id, 'external_id', $publication['id']);

                    // Ensure terms exist before assigning
                    wp_insert_term($publication['siteName'], 'publications_cat');
                    wp_insert_term($publication['publicationCategory'], 'publications_cat');

                    // Save taxonomy terms
                    wp_set_object_terms($post_id, $publication['siteName'], 'publications_cat');
                    wp_set_object_terms($post_id, $publication['publicationCategory'], 'publications_cat', true);

                    //------------------Process topics and attach terms to the post--------------
                    $term_ids = [];
                    foreach ($publication['topics'] as $topic) {
                        //echo "topic.........116 ". $topic ."</br>";
                        // Check if the term exists in the taxonomy
                        $term = get_term_by('name', trim($topic), 'publications_cat');
                        //echo "88 >> ". $term;
                        if (!$term) {
                            //echo "</br>term......... 119 </br><pre>". var_dump($term). "</br>";
                            // Create the term under the parent category "Topic"
                            $parent_term = get_term_by('name', 'Topic', 'publications_cat');
                            if (!$parent_term) {
                                //echo '126 Parent term not found for Topic in publications_cat </br>';
                                continue; // Skip to next topic if parent term is missing
                            }
                            //echo '128 Parent term found: <pre>' . print_r($parent_term, true) .'</br>';
                            $term = wp_insert_term(
                                trim($topic),
                                'publications_cat',
                                [
                                    'parent' => $parent_term->term_id ?? 0,
                                ]
                            );

                            // Check for errors during insertion
                            if (is_wp_error($term)) {
                                echo "Error inserting term for topic '{$topic}': " . $term->get_error_message() . "</br>";
                                continue; // Skip to the next topic if there's an error
                            }

                            echo "Successfully inserted term: <pre>" . print_r($term, true) . "</pre></br>";
                            $term_id = $term['term_id'];
                            $term = get_term($term_id);
                        }
        
                        // Get the term ID for assigning
                        //$term_ids[] = is_wp_error($term) ? $term->error_data['term_exists'] : $term['term_id'];
                        // If wp_insert_term succeeds, it returns an array, convert it to WP_Term
                         // Retrieve WP_Term object for consistency
                        $term_ids[] = $term->term_id;
                    }
                    // Assign terms to the post
                    if (!empty($term_ids)) {
                        //echo "Collected term IDs: <pre>" . print_r($term_ids, true) . "</pre>";
                        wp_set_post_terms($post_id, $term_ids, 'publications_cat');
                    }
                }
            } else {
                // Optionally update existing post
                $post_id = $existing_post[0]->ID;
                //echo "Exists id ".$post_id."</br>";
                wp_update_post([
                    'ID'           => $post_id,
                    'post_title'   => $post_title,
                    'post_content' => $publication['abstract'],
                ]);

                // Update custom fields
                update_post_meta($post_id, 'doi', $publication['doi']);
                if(!empty($publication['keywords'])){
                    update_post_meta($post_id, 'keywords', implode(', ', $publication['keywords']));
                }
                update_post_meta($post_id, 'language', $publication['publicationLanguage']);

                //Update taxonomy terms
                if (!empty($publication['topics'])) {
                    $term_ids = [];
                    foreach ($publication['topics'] as $topic) {
                        $term = get_term_by('name', trim($topic), 'publications_cat');
                        //echo "<pre>".var_dump($term)."</pre>";
                        if (!$term) {
                            $parent_term = get_term_by('name', 'Topic', 'publications_cat');
                            if (!$parent_term) {
                                //echo '126 Parent term not found for Topic in publications_cat </br>';
                                continue; // Skip to next topic if parent term is missing
                            }
                            $term = wp_insert_term(
                                trim($topic),
                                'publications_cat',
                                [
                                    'parent' => $parent_term->term_id ?? 0,
                                ]
                            );

                            // Check for errors during insertion
                            if (is_wp_error($term)) {
                                echo "Error inserting term for topic '{$topic}': " . $term->get_error_message() . "</br>";
                                continue; // Skip to the next topic if there's an error
                            }

                            //echo "Successfully inserted term: <pre>" . print_r($term, true) . "</pre></br>";
                            $term_id = $term['term_id'];
                            $term = get_term($term_id);
                        }
                        $term_ids[] = $term->term_id;
                    }
                    if (!empty($term_ids)) {
                        wp_set_post_terms($post_id, $term_ids, 'publications_cat');
                    }
                }
            }
        }
        echo "Insert record = ". $count;
    }
}

// Schedule the event during activation
if (!wp_next_scheduled('sync_publications_from_api_cron')) {
    wp_schedule_event(time(), 'every_hour_schedular', 'sync_publications_from_api_cron');
}

// // Hook the sync function
add_action('sync_publications_from_api_cron', 'save_publications_from_api');

// // Clean up scheduled events during deactivation
// if (!function_exists('unschedule_publications_sync')) {
//     function unschedule_publications_sync() {
//         $timestamp = wp_next_scheduled('sync_publications_from_api');
//         if ($timestamp) {
//             wp_unschedule_event($timestamp, 'sync_publications_from_api');
//         }
//     }
//     register_deactivation_hook(__FILE__, 'unschedule_publications_sync'); // Adjust __FILE__ if used in a plugin
// }

// if (isset($_GET['sync_publications']) && $_GET['sync_publications'] === '1') {
    
//     save_publications_from_api();
//    echo 'Sync completed!';
//    //test_cron_();
//    exit;
// }

/** Cron job test */
// add_filter('cron_schedules', function($schedules) {
//     $schedules['every_minute_'] = [
//         'interval' => 60, // Interval in seconds
//         'display'  => __('Every Minute')
//     ];
//     return $schedules;
// });

add_action('sync_publications_from_api_cron', function() {
    // Your custom logic here
    save_publications_from_api();
    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'custom_logs', [
        'log' => 'Every hour cron executed at: ' . current_time('mysql'),
        'timestamp' => current_time('mysql')
    ]);
    error_log('Every hour cron executed at: ' . current_time('mysql'));
});
/** Cron job test */


//-----------------------------------Post----------
// function save_publications_from_api() {
//     $term = get_term_by('slug','agriculture-and-irrigation', 'publications_cat');
//     if($term){
//         echo "gdfgd";
//         print_r($term);
//     }
    
// }
// add_action('sync_publications_from_api', 'save_publications_from_api');

//----------------------Fetch post by ajax-------------
function enqueue_custom_ajax_scripts() {
    wp_enqueue_script('ajax-publications', get_template_directory_uri() . '/page-publication.php', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_ajax_scripts');

function register_publications_rest_endpoint() {
    register_rest_route('/v1', '/publications', [
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'fetch_publications_rest_handler',
        'permission_callback' => '__return_true', // Optional: Adjust permissions if needed
    ]);
}
add_action('rest_api_init', 'register_publications_rest_endpoint');

function fetch_publications_rest_handler(WP_REST_Request $request) {
    $paged = $request->get_param('page') ?: 1;
    $category = $request->get_param('category') ?: '';

    // Query arguments
    $args = [
        'post_type'      => 'publications',
        'posts_per_page' => 10,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
        //'s'              => '_Api',
        'meta_query'     => [
        [
            'key'     => 'external_id', // The meta key to check
            'compare' => 'EXISTS',     // Ensures the key exists
        ],
    ],
    ];

    // Add category filter if provided
    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'publications_cat',
                'field'    => 'slug',//'term_id',
                'terms'    => $category,
            ],
        ];
    }

    $query = new WP_Query($args);

    $posts = [];
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $c_date = new DateTime(get_field('createddatetime'));
            $formattedDate = $c_date->format('d M Y');
            $terms = get_the_terms(get_the_ID(), 'publications_cat');
            $comma_separated_terms = "";
            if (!empty($terms) && !is_wp_error($terms)) {
                // Extract term names and convert to a comma-separated string
                $term_names = wp_list_pluck($terms, 'name');
                $comma_separated_terms = implode(' | ', $term_names);
            }
            $posts[] = [
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'excerpt' => get_the_excerpt(),
                'post_id_' => get_field('external_id'),
                'create_date' => $formattedDate,
                'publish_date' => get_the_date(),
                'keywords' => get_post_meta(get_the_ID(), 'post_id', true),
                'language' => get_post_meta(get_the_ID(), 'language', true),
                'allterms' => $comma_separated_terms
            ];
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response([
        'total_post' =>  $query->found_posts,
        'posts'       => $posts,
        'total_pages' => $query->max_num_pages,
        'current_page' => (int) $paged,
    ], 200);
}

//---------------Download file----------------
function download_document_file() {
    if (!isset($_GET['download_document']) || empty($_GET['document_id']) || empty($_GET['name'])) {
        return;
    }

    $document_id = sanitize_text_field($_GET['document_id']);
    $api_url = "https://api.mrcmekong.org/api/v1/document/download/" . $document_id;
    $api_key = "f51471f7b89441a48702ac31e370b3ed";

    // Initialize cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/octet-stream',
        'X-API-Key: ' . $api_key
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        // Set headers for file download
        /**
         * For Download
         */
        // header('Content-Description: File Transfer');
        // header('Content-Type: application/octet-stream');
        // header('Content-Disposition: attachment; filename='.$_GET['name'].'.pdf');
        // header('Content-Transfer-Encoding: binary');
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        /**
         * For Preview
         */
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $_GET['name'] . '.pdf"');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: 0');
        echo $response;
    } else {
        echo 'Failed to download file.';
    }

    exit;
}
add_action('init', 'download_document_file');


