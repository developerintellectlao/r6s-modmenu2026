<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_softlite_save_css', 'softlite_save_css');

function softlite_save_css() {
    $css = stripslashes($_POST['css']);
    $post_id = $_POST['post_id'];
    $file_path = SOFTLITE_CSS_DIR . '/' . $post_id . '.css';

    if (!file_exists($file_path)) {
        file_put_contents($file_path, $css);
        echo 'File created successfully with initial content.';
    } else {
        file_put_contents($file_path, $css);
        echo 'File overwritten with new CSS content.';
    }
    wp_die();
}