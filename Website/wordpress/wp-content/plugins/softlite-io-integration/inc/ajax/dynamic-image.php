<?php
add_action('wp_ajax_softlite_dynamic_image', 'softlite_dynamic_image');

function softlite_dynamic_image()
{
    $subject = $_POST['shortcode'] ?? '';
    $post_id = $_POST['post_id'] ?? 0;
    $post_type = $_POST['post_type'] ?? '';

    $dynamic = [
        'featured_image' => 'get_the_post_thumbnail_url',
        'acf_field' => 'get_field',
        'acf_image' => 'get_field',
        'pods_field' => 'pods_field',
        'pods_image' => 'pods_field',
        'metabox_field' => 'rwmb_get_value',
        'metabox_image' => 'rwmb_get_value',
        'jetengine_field' => 'get_post_meta',
        'toolset_field' => 'get_post_meta',
        'author_avatar' => 'get_post_meta',
    ];

    $callback = function ($matches) use ($dynamic, $post_id, $post_type) {
        $key = $matches[1] ?? '';
        $param = $matches[2] ?? '';
        if (function_exists($dynamic[$key])) {
            if (isset($dynamic[$key])) {
                switch ($key) {
                    case 'featured_image':
                        $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($post_id), $param);
                        return $image_url ?: '';

                    case 'acf_field':
                        $acf_meta = explode(':', $param);
                        $field_value = get_field($param, $post_id);
                        $acf_object = get_field_object($acf_meta[0]);
                        $acf_type = $acf_object['type'] ?? 'text';
                        if ($acf_type === 'image') { // For ACF image 
                            $acf_image_id = get_field($acf_meta[0], $post_id);
                            $image_id = $acf_image_id['id'] ?? false;
                            $acf_image_size = $acf_meta[1] ?? 'full';
                            $acf_image_size = $acf_image_size === 'size' ? 'full' : $acf_image_size;
                            $field_value = $image_id ? wp_get_attachment_image_url($image_id, $acf_image_size) : '';
                        } elseif ($acf_type === 'file') { // For ACF file
                            $acf_data = get_field($acf_meta[0], $post_id);
                            $field_value = $acf_data['url'] ?? '';
                        } else {
                            $field_value = get_field($acf_meta[0], $post_id);
                        }
                        return $field_value ?: '';
                    case 'acf_image':
                        $acf_meta = explode(':', $param);
                        $field_name = $acf_meta[0] ?? '';
                        $acf_image_id = get_field($field_name, $post_id);
                        $image_id = $acf_image_id['id'] ?? false;
                        $acf_image_size = $acf_meta[1] ?? 'full';
                        $acf_image_size = $acf_image_size === 'size' ? 'full' : $acf_image_size;
                        $field_value = $image_id ? wp_get_attachment_image_url($image_id, $acf_image_size) : '';
                        return $field_value ?: '';
                    case 'pods_field':
                    case 'pods_image':
                        $pod_meta = explode(':', $param);
                        $pod = pods_field($post_type, $post_id, $pod_meta[0], true);
                        if (is_array($pod) && $pod['post_type'] == 'attachment') { // For pods image
                            $pod_image_id = $pod['ID'];
                            $pod_image_size = $pod_meta[1] ?? 'full';
                            $pod_image_size = $pod_image_size === 'size' ? 'full' : $pod_image_size;
                            $field_value = $pod_image_id ? wp_get_attachment_image_url($pod_image_id, $pod_image_size) : '';
                        } else {
                            $field_value = $pod;
                        }
                        return $field_value ?: '';
                    case 'metabox_field':
                        $field_value = rwmb_get_value($param, [], $post_id);
                        return $field_value ?: '';
                    case 'metabox_image':
                        $metabox = explode(':', $param);
                        $metabox_key = $metabox[0] ?? '';
                        $metabox_size = $metabox[1] ?? 'full';
                        $metabox_image_id = get_post_meta($post_id, $metabox_key, true);
                        $field_value = wp_get_attachment_url($metabox_image_id, $metabox_size);
                        return $field_value ?: '';
                    case 'jetengine_field':
                    case 'toolset_field':
                        $param = ($key === 'toolset_field') ? 'wpcf-' + $param : $param;
                        $field_value = get_post_meta($post_id, $param, true);
                        return $field_value ?: '';
                    case 'jetengine_image':
                    case 'toolset_image':
                        $jetengine_meta = explode(':', $param);
                        $jetengine_key = $jetengine_meta[0] ?? '';
                        $jetengine_size = $jetengine_meta[1] ?? '';
                        $jetengine_key = ($key === 'toolset_field') ? 'wpcf-' + $param : $param;
                        $jetengine_image_id = get_post_meta($post_id, $jetengine_key, true);
                        $field_value = wp_get_attachment_image_url($jetengine_image_id, $jetengine_size);
                        return $field_value ?: '';
                    case 'author_avatar':
                        $author_id = get_post_field('post_author', $post_id);
                        $field_value = get_avatar_url($author_id);
                        return $field_value ?: '';
                }
            }
        }

        return $matches[0];
    };

    $result = preg_replace_callback(
        '/\{(\w+)(?:\s*:\s*(.+?))?\}/',
        $callback,
        $subject
    );

    echo $result;
    wp_die();
}

