<?php
function softlite_get_dynamic_tag($subject, $postID = false)
{
    $post_id = $postID ?: get_the_ID() ?: '';
    // $dynamic_json = file_get_contents(__DIR__ . "/dynamic-tag.json");
    $dynamic_json = '{
        "POST": [
            {
                "name": "Post title",
                "shortcode": "{post_title}",
                "callable": "get_the_title"
            },
            {
                "name": "Post ID",
                "shortcode": "{post_id}",
                "callable": "get_the_ID"
            },
            {
                "name": "Post link",
                "shortcode": "{post_link}",
                "callable": "get_permalink"
            },
            {
                "name": "Post date",
                "shortcode": "{post_date}",
                "callable": "get_the_date",
                "format": "date_format"
            },
            {
                "name": "Post modified date",
                "shortcode": "{post_modified_date}",
                "callable": "get_the_modified_date",
                "format": "date_format"
            },
            {
                "name": "Post time",
                "shortcode": "{post_time}",
                "callable": "get_post_time",
                "format": "time_format"
            },
            {
                "name": "Post comments count",
                "shortcode": "{post_comments_count}",
                "callable": "get_comment_count",
                "status": "total_comments"
            },
            {
                "name": "Post content",
                "shortcode": "{post_content}",
                "callable": "get_the_content"
            },
            {
                "name": "Post excerpt",
                "shortcode": "{post_excerpt}",
                "callable": "get_the_excerpt"
            },
            {
                "name": "Featured image",
                "shortcode": "{featured_image:size}",
                "callable": "get_the_post_thumbnail_url",
                "image_url": true
            }
        ],
        "AUTHOR": [
            {
                "name": "Author name",
                "shortcode": "{author_name}",
                "callable": "get_the_author_meta",
                "parameter": "display_name"
            },
            {
                "name": "Author bio",
                "shortcode": "{author_bio}",
                "callable": "get_the_author_meta",
                "parameter": "description"
            },
            {
                "name": "Author email",
                "shortcode": "{author_email}",
                "callable": "get_the_author_meta",
                "parameter": "user_email"
            },
            {
                "name": "Author website",
                "shortcode": "{author_website}",
                "callable": "get_the_author_meta",
                "parameter": "user_url"
            },
            {
                "name": "Author avatar",
                "shortcode": "{author_avatar}",
                "callable": "get_the_author_meta",
                "parameter": "user_url",
                "image_url": true
            },
            {
                "name": "Author meta - add key after:",
                "shortcode": "{author_meta:description}",
                "callable": "get_the_author_meta",
                "key": true
            }
        ],
        "SITE": [
            {
                "name": "Site title",
                "shortcode": "{site_title}",
                "callable": "get_bloginfo",
                "parameter": "name"
            },
            {
                "name": "Site tagline",
                "shortcode": "{site_tagline}",
                "callable": "get_bloginfo",
                "parameter": "description"
            },
            {
                "name": "Site URL",
                "shortcode": "{site_url}",
                "callable": "get_bloginfo",
                "parameter": "url"
            },
            {
                "name": "Logout URL",
                "shortcode": "{logout_url}",
                "callable": "wp_logout_url"
            },
            {
                "name": "URL parameter - add key after:",
                "shortcode": "{url_parameter:name}",
                "callable": "request_parameter"
            }
        ],
        "ARCHIVE": [
            {
                "name": "Archive title",
                "shortcode": "{archive_title}",
                "callable": "get_the_archive_title"
            },
            {
                "name": "Archive description",
                "shortcode": "{archive_description}",
                "callable": "get_the_archive_description"
            }
        ],
        "CUSTOM FIELD": [
            {
                "name": "Post custom field",
                "shortcode": "{post_custom_field:field_name}",
                "callable": "get_post_meta",
                "key": true,
                "image_url": true
            },
            {
                "name": "ACF field",
                "shortcode": "{acf_field:field_name}",
                "callable": "get_field",
                "key": true,
                "image_url": true
            },
            {
                "name": "ACF image",
                "shortcode": "{acf_image:field_name:size}",
                "callable": "get_field",
                "key": true,
                "image_url": true
            },
            {
                "name": "Metabox field",
                "shortcode": "{metabox_field:field_name}",
                "callable": "rwmb_get_value",
                "key": true,
                "image_url": true
            },
            {
                "name": "Pods field",
                "shortcode": "{pods_field:field_name}",
                "callable": "pods_field",
                "key": true,
                "image_url": true
            },
            {
                "name": "Pods image",
                "shortcode": "{pods_image:field_name:size}",
                "callable": "pods_field",
                "key": true,
                "image_url": true
            },
            {
                "name": "Toolset field",
                "shortcode": "{toolset_field:field_name}",
                "callable": "get_post_meta",
                "key": true,
                "image_url": true
            },
            {
                "name": "JetEngine field",
                "shortcode": "{jetengine_field:field_name}",
                "callable": "get_post_meta",
                "key": true,
                "image_url": true
            }
        ],
        "TERM":[
            {
                "name": "Term ID",
                "shortcode": "{term_id}",
                "callable": "term",
                "key": true
            },
            {
                "name": "Term name",
                "shortcode": "{term_name}",
                "callable": "term",
                "key": true
            },
            {
                "name": "Term description",
                "shortcode": "{term_description}",
                "callable": "term",
                "key": true
            },
            {
                "name": "Term url",
                "shortcode": "{term_url}",
                "callable": "term",
                "key": true
            }, 
            {
                "name": "Term count",
                "shortcode": "{term_count}",
                "callable": "term",
                "key": true
            },
            {
                "name": "Term meta",
                "shortcode": "{term_meta:meta_name}",
                "callable": "get_term_meta",
                "key": true
            }
        ]
    }';
    $dynamic = json_decode($dynamic_json, true);
    foreach ($dynamic as $tag => $items) {
        foreach ($items as $item) {
            $dynamic_value = '';
            if ($tag === 'TERM' || function_exists($item["callable"])) {
                switch (true) {
                    case $tag === 'TERM':
                        global $term;
                        if (!empty($term)) {
                            if ($item['callable'] == 'get_term_meta') {
                                if (preg_match($pattern, $subject, $matches)) {
                                    $meta_key = $matches[1];
                                    $meta_value = $matches[2];
                                    $short_code = $matches[0];
                                    $dynamic_value = get_term_meta($term->term_id, $meta_value, true);
                                }
                            } else {
                                preg_match('/{(.*?)}/', $item["shortcode"], $matches);
                                $term_param = $matches[1];
                                $dynamic_value = !empty($term) ? !empty($term->$term_param) ? $term->$term_param : "" : "";
                            }
                        }
                        break;
                    case $item['callable'] === 'get_avatar_url':
                        $author_id = get_post_field('post_author', $post_id);
                        $dynamic_value = get_avatar_url($author_id);
                        break;
                    case array_key_exists('format', $item):
                        $format = get_option($item['format']);
                        $dynamic_value = call_user_func($item["callable"], $format);
                        break;
                    case array_key_exists('status', $item):
                        $dynamic_value = call_user_func($item["callable"], $format)[$item['status']] ?? '';
                        break;
                    case array_key_exists('parameter', $item):
                        $dynamic_value = call_user_func($item["callable"], $item['parameter']);
                        break;
                    case array_key_exists('key', $item):
                        $allowed_keys = ['author_meta', 'url_parameter', 'acf_field', 'post_custom_field', 'metabox_field', 'pods_field', 'pods_image', 'acf_image'];
                        $pattern = '/\{(\w+)\s*:\s*(.+?)\}/';
                        if (preg_match($pattern, $subject, $matches)) {
                            $meta_key = $matches[1] ?? '';
                            $meta_value = $matches[2] ?? '';
                            $short_code = $matches[0] ?? '';
                            if (in_array($meta_key, $allowed_keys) && strpos($item['shortcode'], $meta_key) !== false) {
                                switch ($item['callable']) {
                                    case 'get_post_meta':
                                        $meta_value = ($meta_key === 'toolset_field') ? "wpcf-" . $meta_value : $meta_value;
                                        $dynamic_value = get_post_meta($post_id, $meta_value, true);
                                        break;
                                    case 'get_field':
                                        $acf_meta = explode(':', $meta_value);
                                        $acf_object = get_field_object($acf_meta[0]);
                                        $acf_type = $acf_object['type'] ?? 'text';
                                        if ($acf_type === 'image') { // For ACF image 
                                            $acf_image_id = get_field($acf_meta[0]);
                                            $image_id = $acf_image_id['id'] ?? false;
                                            $acf_image_size = $acf_meta[1] ?? 'full';
                                            $acf_image_size = $acf_image_size === 'size' ? 'full' : $acf_image_size;
                                            $dynamic_value = $image_id ? wp_get_attachment_image_url($image_id, $acf_image_size) : '';
                                        } elseif ($acf_type === 'file') { // For ACF file
                                            $acf_data = get_field($acf_meta[0]);
                                            $dynamic_value = $acf_data['url'] ?? '';
                                        } else {
                                            $dynamic_value = call_user_func($item['callable'], $acf_meta[0]);
                                        }
                                        break;
                                    case 'get_the_post_thumbnail_url':
                                        $image_size = $meta_value ?? 'full';
                                        $dynamic_value = get_the_post_thumbnail_url($post_id, $image_size) ?: '';
                                        break;
                                    case 'pods_field':
                                        $post_type = get_post_type();
                                        $pod_meta = explode(':', $meta_value);
                                        $pod = pods_field($post_type, $post_id, $pod_meta[0], true);
                                        if (is_array($pod) && $pod['post_type'] == 'attachment') { // For pods image
                                            $pod_image_id = $pod['ID'];
                                            $pod_image_size = $pod_meta[1] ?? 'full';
                                            $pod_image_size = $pod_image_size === 'size' ? 'full' : $pod_image_size;
                                            $dynamic_value = $pod_image_id ? wp_get_attachment_image_url($pod_image_id, $pod_image_size) : '';
                                        } else {
                                            $dynamic_value = $pod;
                                        }
                                        break;
                                    default:
                                        // URL parameter hoặc các callable khác 
                                        $dynamic_value = ($meta_key === 'url_parameter') ? ($_GET[$meta_value] ?? '') : call_user_func($item['callable'], $meta_value);
                                        break;
                                }
                                $item["shortcode"] = $short_code;
                            }
                        }
                        break;
                    default:
                        $dynamic_value = call_user_func($item["callable"]);
                        break;
                }
            }
            $dynamic_value = $dynamic_value === false ? "" : $dynamic_value;
            $replace = $dynamic_value ?? "";
            $subject = is_string($dynamic_value) ? str_replace($item["shortcode"], $replace, $subject) : $subject;
        }
    }
    return $subject;
}