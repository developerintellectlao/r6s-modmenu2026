<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

add_action('wp_ajax_softlite_media_upload', 'softlite_media_upload');

function softlite_media_upload() {
    check_ajax_referer('softlite_media_upload_nonce', 'nonce');

    if (!empty($_POST['media_urls'])) {
        $media_urls = json_decode(stripslashes($_POST['media_urls']));
        $uploaded_file_ids = array();
        $uploaded_file_urls = array();

        foreach ($media_urls as $url) {
            $url = esc_url_raw($url);
            $temp_file = download_url($url);

            if (is_wp_error($temp_file)) {
                $uploaded_file_ids[] = 0;
                $uploaded_file_urls[] = $url;
                continue;
            }

            $file_array = array(
                'name' => basename($url),
                'tmp_name' => $temp_file
            );

            $file = media_handle_sideload($file_array, 0);

            if (!is_wp_error($file)) {
                $uploaded_file_ids[] = $file;
                $uploaded_file_urls[] = wp_get_attachment_url($file);
            } else {
                // var_dump($file);
                $uploaded_file_ids[] = 0;
                $uploaded_file_urls[] = $url;
            }
        }

        $response = array(
            'success' => true,
            'message' => 'Media uploaded successfully.',
            'ids' => $uploaded_file_ids,
            'urls' => $uploaded_file_urls
        );
    } else {
        $response = array(
            'success' => false,
            'message' => 'No media URLs provided.'
        );
    }

    wp_send_json($response);
    wp_die();
}