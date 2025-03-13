<?php
/**
 * Social Sharing Extension.
 *
 * @package Rishi_Companion\Modules\Helpers
 */

namespace Rishi_Companion\Modules\Helpers;

/**
 * Class Social_Sharing
 *
 * Handles the display and functionality of the social sharing feature.
 */
class Social_Sharing{

   	/**
     * Social_Sharing constructor.
     *
     * Hooks into WordPress to display the social sharing feature and enqueue necessary assets.
     */
    public function __construct() {
        add_action( 'rishi_single_content_top', array( $this, 'rishi_companion_social_share' ), 11 );
        add_action( 'language_attributes', array( $this, 'opengraph_add_prefix' ) );
        add_action( 'wp', array( $this, 'opengraph_default_metadata' ) );
        add_action( 'wp_head', array( $this, 'opengraph_meta_tags' ) );
        $this->includes();
    }

    /**
     * Includes necessary files.
     */
    public function includes() {
        // Disables the OG Tags for Jetpack when disabled from the plugin
        if ( get_theme_mod( 'ed_og_tags', 'yes' ) === 'yes' ) {
            add_filter( 'jetpack_enable_opengraph', '__return_false' );
            add_filter( 'jetpack_enable_open_graph', '__return_false' );
        }
    }

	/**
     * Add Open Graph XML prefix to <html> element.
     *
     * @param string $output The output string.
     * @return string The modified output with the Open Graph prefix.
     */
    public function opengraph_add_prefix( $output ) {
        $prefixes = array(
            'og' => 'https://ogp.me/ns#',
        );
        $prefixes = apply_filters( 'rishi_companion_opengraph_prefixes', $prefixes );

        $prefix_str = '';
        foreach ( $prefixes as $k => $v ) {
            $prefix_str .= $k . ': ' . $v . ' ';
        }
        $prefix_str = trim( $prefix_str );

        if ( preg_match( '/(prefix\s*=\s*[\"|\'])/i', $output ) ) {
            $output = preg_replace( '/(prefix\s*=\s*[\"|\'])/i', '${1}' . $prefix_str, $output );
        } else {
            $output .= ' prefix="' . $prefix_str . '"';
        }

        return $output;
    }

	 /**
     * Register filters for default Open Graph metadata.
     */
    function opengraph_default_metadata() {
        if ( get_theme_mod( 'ed_og_tags', 'yes' ) === 'yes' ) {
            // additional prefixes
            add_filter( 'rishi_companion_opengraph_prefixes', array( $this, 'rishi_companion_opengraph_additional_prefixes' ) );

            // core metadata attributes
            add_filter( 'rishi_companion_opengraph_title', array( $this, 'rishi_companion_opengraph_default_title' ), 5 );
            add_filter( 'rishi_companion_opengraph_type', array( $this, 'rishi_companion_opengraph_default_type' ), 5 );
            add_filter( 'rishi_companion_opengraph_image', array( $this, 'rishi_companion_opengraph_default_image' ), 5 );
            add_filter( 'rishi_companion_opengraph_url', array( $this, 'rishi_companion_opengraph_default_url' ), 5 );

            add_filter( 'rishi_companion_opengraph_description', array( $this, 'rishi_companion_opengraph_default_description' ), 5 );
            add_filter( 'rishi_companion_opengraph_locale', array( $this, 'rishi_companion_opengraph_default_locale' ), 5 );
            add_filter( 'rishi_companion_opengraph_site_name', array( $this, 'rishi_companion_opengraph_default_sitename' ), 5 );

            // additional profile metadata
            add_filter( 'rishi_companion_opengraph_metadata', array( $this, 'rishi_companion_opengraph_profile_metadata' ) );

            // additional article metadata
            add_filter( 'rishi_companion_opengraph_metadata', array( $this, 'rishi_companion_opengraph_article_metadata' ) );

            // twitter card metadata
            add_filter( 'rishi_companion_twitter_card', array( $this, 'rishi_companion_twitter_default_card' ), 5 );
            add_filter( 'rishi_companion_twitter_creator', array( $this, 'rishi_companion_twitter_default_creator' ), 5 );
        }
    }

	/**
     * Add additional prefix namespaces that are supported by the opengraph plugin.
     *
     * @param array $prefixes The current prefixes.
     * @return array The modified prefixes.
     */
    public function rishi_companion_opengraph_additional_prefixes( $prefixes ) {
        if ( is_author() ) {
            $prefixes['profile'] = 'https://ogp.me/ns/profile#';
        }
        if ( is_singular() ) {
            $prefixes['article'] = 'https://ogp.me/ns/article#';
        }

        return $prefixes;
    }

	/**
     * Default title property, using the page title.
     *
     * @param string $title The current title.
     * @return string The modified title.
     */
    function rishi_companion_opengraph_default_title( $title ) {
        if ( $title ) {
            return $title;
        }

        if ( is_singular() ) {
            $title = get_the_title( get_queried_object_id() );
        } elseif ( is_author() ) {
            $author = get_queried_object();
            $title  = $author->display_name;
        } elseif ( is_category() && single_cat_title( '', false ) ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag() && single_tag_title( '', false ) ) {
            $title = single_tag_title( '', false );
        } elseif ( is_archive() && get_post_format() ) {
            $title = get_post_format_string( get_post_format() );
        } elseif ( is_archive() && function_exists( 'get_the_archive_title' ) && get_the_archive_title() ) { // new in version 4.1 to get all other archive titles
            $title = get_the_archive_title();
        }

        return $title;
    }

	/**
     * Default type property.
     *
     * @param string $type The current type.
     * @return string The modified type.
     */
    public function rishi_companion_opengraph_default_type( $type ) {
        if ( empty( $type ) ) {
            if ( is_singular( array( 'post', 'page' ) ) ) {
                $type = 'article';
            } elseif ( is_author() ) {
                $type = 'profile';
            } else {
                $type = 'website';
            }
        }

        return $type;
    }

	/**
     * Default image property, using the post-thumbnail and any attached images.
     *
     * @param string $image The current image.
     * @return string The modified image.
     */
    public function rishi_companion_opengraph_default_image( $image ) {
        if ( $image ) {
            return $image;
        }

        // As of July 2014, Facebook seems to only let you select from the first 3 images
        $max_images = apply_filters( 'rishi_companion_opengraph_max_images', 3 );

        if ( is_singular() ) {
            $id        = get_queried_object_id();
            $image_ids = array();

            // list post thumbnail first if this post has one
            if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $id ) ) {
                $image_ids[] = get_post_thumbnail_id( $id );
            }

            // then list any image attachments
            $attachments = get_children(
                array(
                    'post_parent'    => $id,
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => 'ASC',
                    'orderby'        => 'menu_order ID',
                )
            );

            foreach ( $attachments as $attachment ) {
                if ( ! in_array( $attachment->ID, $image_ids ) ) {
                    $image_ids[] = $attachment->ID;
                    if ( sizeof( $image_ids ) >= $max_images ) {
                        break;
                    }
                }
            }

            // get URLs for each image
            $image = array();
            foreach ( $image_ids as $id ) {
                $thumbnail = wp_get_attachment_image_src( $id, 'full' );
                if ( $thumbnail ) {
                    $image[] = $thumbnail[0];
                }
            }
        } elseif ( is_attachment() && wp_attachment_is_image() ) {
            $id    = get_queried_object_id();
            $image = array( wp_get_attachment_url( $id ) );
        }

        if ( empty( $image ) ) {
            $image = array();

            // add site icon
            if ( function_exists( 'get_site_icon_url' ) && has_site_icon() ) {
                $image[] = get_site_icon_url( 512 );
            }

            // add header images
            if ( function_exists( 'get_uploaded_header_images' ) ) {
                if ( is_random_header_image() ) {
                    foreach ( get_uploaded_header_images() as $header_image ) {
                        $image[] = $header_image['url'];

                        if ( sizeof( $image ) >= $max_images ) {
                            break;
                        }
                    }
                } elseif ( get_header_image() ) {
                    $image[] = get_header_image();
                }
            }
        }

        return $image;
    }

	/**
     * Default url property, using the permalink for the page.
     *
     * @param string $url The current url.
     * @return string The modified url.
     */
    public function rishi_companion_opengraph_default_url( $url ) {
        if ( empty( $url ) ) {
            if ( is_singular() ) {
                $url = get_permalink();
            } elseif ( is_author() ) {
                $url = get_author_posts_url( get_queried_object_id() );
            }
        }

        return $url;
    }

	/**
     * Default description property, using the excerpt or content for posts, or the
     * bloginfo description.
     *
     * @param string $description The current description.
     * @return string The modified description.
     */
    public function rishi_companion_opengraph_default_description( $description ) {
        if ( $description ) {
            return $description;
        }

        if ( is_singular() ) {
            $post = get_queried_object();
            if ( ! empty( $post->post_excerpt ) ) {
                $description = $post->post_excerpt;
            } else {
                $description = $post->post_content;
            }
        } elseif ( is_author() ) {
            $id          = get_queried_object_id();
            $description = get_user_meta( $id, 'description', true );
        } elseif ( is_category() && category_description() ) {
            $description = category_description();
        } elseif ( is_tag() && tag_description() ) {
            $description = tag_description();
        } elseif ( is_archive() && function_exists( 'get_the_archive_description' ) && get_the_archive_description() ) { // new in version 4.1 to get all other archive descriptions
            $description = get_the_archive_description();
        } else {
            $description = get_bloginfo( 'description' );
        }

        // strip description to first 55 words.
        $description = strip_tags( strip_shortcodes( $description ) );
        $description = $this->rishi_companion_opengraph_trim_text( $description );

        return $description;
    }

	/**
	 * This method sets the default locale for the Open Graph protocol.
	 * If the locale is not set, it uses the locale of the WordPress installation.
	 *
	 * @param string $locale The current locale.
	 * @return string The locale to be used.
	 */
	public function rishi_companion_opengraph_default_locale( $locale ) {
		if ( empty( $locale ) ) {
			$locale = get_locale();
		}

		return $locale;
	}

	/**
	 * This method sets the default site name for the Open Graph protocol.
	 * If the site name is not set, it uses the name of the WordPress site.
	 *
	 * @param string $name The current site name.
	 * @return string The site name to be used.
	 */
	public function rishi_companion_opengraph_default_sitename( $name ) {
		if ( empty( $name ) ) {
			$name = get_bloginfo( 'name' );
		}

		return $name;
	}

	/**
	 * This method includes profile metadata for author pages in the Open Graph protocol.
	 * It sets the first name, last name, and username of the author.
	 *
	 * @param array $metadata The current metadata.
	 * @return array The metadata to be used.
	 */
	public function rishi_companion_opengraph_profile_metadata( $metadata ) {
		if ( is_author() ) {
			$id                             = get_queried_object_id();
			$metadata['profile:first_name'] = get_the_author_meta( 'first_name', $id );
			$metadata['profile:last_name']  = get_the_author_meta( 'last_name', $id );
			$metadata['profile:username']   = get_the_author_meta( 'nicename', $id );
		}

		return $metadata;
	}

	/**
	 * This method includes article metadata for posts and pages in the Open Graph protocol.
	 * It sets the tags, categories, published time, modified time, and author of the article.
	 *
	 * @param array $metadata The current metadata.
	 * @return array The metadata to be used.
	 */
	public function rishi_companion_opengraph_article_metadata( $metadata ) {
		if ( ! is_singular() ) {
			return $metadata;
		}

		$post   = get_queried_object();
		$author = $post->post_author;

		// check if page/post has tags
		$tags = wp_get_object_terms( $post->ID, 'post_tag' );
		if ( $tags && is_array( $tags ) ) {
			foreach ( $tags as $tag ) {
				$metadata['article:tag'][] = $tag->name;
			}
		}

		// check if page/post has categories
		$categories = wp_get_object_terms( $post->ID, 'category' );
		if ( $categories && is_array( $categories ) ) {
			$metadata['article:section'][] = current( $categories )->name;
		}

		$metadata['article:published_time'] = get_the_time( 'c', $post->ID );
		$metadata['article:modified_time']  = get_the_modified_time( 'c', $post->ID );
		$metadata['article:author'][]       = get_author_posts_url( $author );

		$facebook = get_the_author_meta( 'facebook', $author );

		if ( ! empty( $facebook ) ) {
			$metadata['article:author'][] = $facebook;
		}

		return $metadata;
	}

	/**
	 * This method sets the default twitter-card type for the Open Graph protocol.
	 * If the card type is not set, it uses 'summary' as the default type.
	 *
	 * @param string $card The current card type.
	 * @return string The card type to be used.
	 */
	public function rishi_companion_twitter_default_card( $card ) {
		if ( $card ) {
			return $card;
		}

		$card   = 'summary';
		$images = apply_filters( 'rishi_companion_opengraph_image', null );

		if ( is_singular() && count( $images ) >= 1 ) {
			$card = 'summary_large_image';
		}

		return $card;
	}

	/**
	 * This method sets the default twitter-card creator for the Open Graph protocol.
	 * If the creator is not set, it uses the Twitter handle of the author of the post.
	 *
	 * @param string $creator The current creator.
	 * @return string The creator to be used.
	 */
	public function rishi_companion_twitter_default_creator( $creator ) {
		if ( $creator || ! is_singular() ) {
			return $creator;
		}

		$post    = get_queried_object();
		$author  = $post->post_author;
		$twitter = get_the_author_meta( 'twitter', $author );

		if ( ! $twitter ) {
			return $creator;
		}

		// check if twitter-account matches "https://twitter.com/username"
		if ( preg_match( '/^http:\/\/twitter\.com\/(#!\/)?(\w+)/i', $twitter, $matches ) ) {
			$creator = '@' . $matches[2];
		} elseif ( preg_match( '/^@?(\w+)$/i', $twitter, $matches ) ) { // check if twitter-account matches "(@)username"
			$creator = '@' . $matches[1];
		}

		return $creator;
	}

	/**
	 * This method gets the Open Graph metadata for the current page.
	 * It applies filters for each property name and returns the metadata array.
	 * @uses apply_filters() Calls 'rishi_companion_opengraph_{$name}' for each property name
	 * @uses apply_filters() Calls 'rishi_companion_twitter_{$name}' for each property name
	 * @uses apply_filters() Calls 'rishi_companion_opengraph_metadata' before returning metadata array
	 */
	public function rishi_companion_opengraph_metadata() {
		$metadata = array();

		// defualt properties defined at https://ogp.me/
		$properties = array(
			// required
			'title',
			'type',
			'image',
			'url',

			// optional
			'audio',
			'description',
			'determiner',
			'locale',
			'site_name',
			'video',
		);

		foreach ( $properties as $property ) {
			$filter                     = 'rishi_companion_opengraph_' . $property;
			$metadata[ "og:$property" ] = apply_filters( $filter, '' );
		}

		$twitter_properties = array( 'card', 'creator' );

		foreach ( $twitter_properties as $property ) {
			$filter                          = 'rishi_companion_twitter_' . $property;
			$metadata[ "twitter:$property" ] = apply_filters( $filter, '' );
		}

		return apply_filters( 'rishi_companion_opengraph_metadata', $metadata );
	}

	/**
	 * This method adds a 512x512 icon size for the site icon.
	 *
	 * @param  array $sizes The current sizes available for the site icon.
	 * @return array The updated list of icon sizes.
	 */
	public function rishi_companion_opengraph_site_icon_image_sizes( $sizes ) {
		$sizes[] = 512;

		return array_unique( $sizes );
	}

	/**
	 * This helper function trims text using the same default values for length and
	 * 'more' text as wp_trim_excerpt.
	 *
	 * @param string $text The text to be trimmed.
	 * @return string The trimmed text.
	 */
	public function rishi_companion_opengraph_trim_text( $text ) {
		$excerpt_length = apply_filters( 'excerpt_length', 55 );
		$excerpt_more   = apply_filters( 'excerpt_more', ' [...]' );

		return wp_trim_words( $text, $excerpt_length, $excerpt_more );
	}

	/**
	 * This method outputs Open Graph <meta> tags in the page header.
	*/
	public function opengraph_meta_tags() {
		if ( get_theme_mod( 'ed_og_tags', 'yes' ) === 'yes' ) {
			$metadata = $this->rishi_companion_opengraph_metadata();
			foreach ( $metadata as $key => $value ) {
				if ( empty( $key ) || empty( $value ) ) {
					continue;
				}
				$value = (array) $value;

				foreach ( $value as $v ) {
					// use "name" attribute for Twitter Cards
					if ( stripos( $key, 'twitter:' ) === 0 ) {
						printf(
							'<meta name="%1$s" content="%2$s" />' . PHP_EOL,
							esc_attr( $key ),
							esc_attr( $v )
						);
					} else { // use "property" attribute for Open Graph
						printf(
							'<meta property="%1$s" content="%2$s" />' . PHP_EOL,
							esc_attr( $key ),
							esc_attr( $v )
						);
					}
				}
			}
		}
	}

	/**
	 * This method gets the social share HTML for a given key.
	 * It checks if the key is not empty, then retrieves the SVG icons for the key.
	 * If the 'icon' index is set and not empty in the HTML array, it assigns the value to the icons variable.
	 *
	 * @param string $key The key for which the social share HTML is to be retrieved.
	 * @return string The HTML for the social share icons.
	 */
	public function get_social_share_html_from_key( $key ) {
		if ( ! $key ) {
			return;
		}

		$icons = '';

		$html_array = \Rishi\Customizer\Helpers\Defaults::lists_all_svgs( ( $key ) );
		if ( isset( $html_array['icon'] ) && ! empty( $html_array['icon'] ) ) {
			$icons = $html_array['icon'];
		}

		return $icons;
	}

	/**
	 * This method gets the list of social sharing icons.
	 * It checks the type of social share and generates the appropriate HTML for each type.
	 *
	 * @param string $share The type of social share.
	 */
	public function get_social_share( $share ) {
		global $post;

		$share_prefix = 'single_blog_post_';

		$links_nofollow = get_theme_mod( $share_prefix . 'share_links_nofollow', 'no' );

		if ( $links_nofollow === 'yes' ) {
			$rel = 'rel="noopener noreferrer nofollow"';
		} else {
			$rel = 'rel="noopener"';
		}

		switch ( $share ) {
			case 'facebook':
				echo '<li><a class="rishi-facebook" href="' . esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink( $post->ID ) ) . '" ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'facebook' ) ) . '</a></li>';
				break;

			case 'twitter':
				echo '<li><a class="rishi-twitter" href="' . esc_url( 'https://twitter.com/intent/tweet?text=' . get_the_title( $post->ID ) ) . '&nbsp;' . get_the_permalink( $post->ID ) . '" ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'twitter' ) ) . '</a></li>';

				break;

			case 'linkedin':
				echo '<li><a class="rishi-linkedin" href="' . esc_url( 'https://www.linkedin.com/shareArticle?mini=true&url=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . '" ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'linkedin' ) ) . '</i></a></li>';

				break;

			case 'pinterest':
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				if ( $image ) {
					echo '<li><a class="rishi-pinterest" href="' . esc_url( 'https://pinterest.com/pin/create/button/?url=' . get_the_permalink( $post->ID ) . ' &media=' . $image[0] . '&description=' . get_the_title( $post->ID ) ) . '" ' . $rel . ' target="_blank" data-pin-do="none" data-pin-custom="true">' . $this->get_social_share_html_from_key( ( 'pinterest' ) ) . '</a></li>';
				}

				break;

			case 'email':
				echo '<li><a href="' . esc_url( 'mailto:?Subject=' . get_the_title( $post->ID ) . '&Body=' . get_the_permalink( $post->ID ) ) . '" ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'email' ) ) . '</i></a></li>';

				break;

			case 'reddit':
				echo '<li><a class="rishi-reddit" href="' . esc_url( 'https://www.reddit.com/submit?url=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . '" ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'reddit' ) ) . '</a></li>';

				break;

			case 'tumblr':
				echo '<li> <a class="rishi-tumblr" href=" ' . esc_url( 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'tumblr' ) ) . '</a></li>';

				break;

			case 'digg':
				echo '<li> <a class="rishi-digg" href=" ' . esc_url( 'https://digg.com/submit?url=' . get_the_permalink( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'digg' ) ) . '</a></li>';

				break;

			case 'weibo':
				echo '<li> <a class="rishi-weibo" href=" ' . esc_url( 'https://service.weibo.com/share/share.php?url=' . get_the_permalink( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'weibo' ) ) . '</a></li>';

				break;

			case 'xing':
				echo '<li> <a class="rishi-xing" href=" ' . esc_url( 'https://www.xing.com/app/user?op=share&url=' . get_the_permalink( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'xing' ) ) . '</a></li>';

				break;

			case 'vk':
				echo '<li> <a class="rishi-vk" href=" ' . esc_url( 'https://vk.com/share.php?url=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'vk' ) ) . '</a></li>';

				break;

			case 'pocket':
				echo '<li> <a class="rishi-pocket" href=" ' . esc_url( 'https://getpocket.com/edit?url=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'pocket' ) ) . '</a></li>';

				break;

			case 'whatsapp':
				echo '<li> <a class="rishi-whatsapp" href=" ' . esc_url( 'https://wa.me/?text=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . ' " ' . $rel . ' target="_blank" data-action="share/whatsapp/share" >' . $this->get_social_share_html_from_key( ( 'whatsapp' ) ) . '</a></li>';

				break;

			case 'telegram':
				echo '<li> <a class="rishi-telegram" href=" ' . esc_url( 'https://telegram.me/share/url?url=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'telegram' ) ) . '</a></li>';
			break;

			case 'viber':
				echo '<li> <a class="rishi-viber" href=" ' . esc_url( 'viber://forward?text=' . get_the_permalink( $post->ID ) . '&title=' . get_the_title( $post->ID ) ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'viber' ) ) . '</a></li>';
			break;

			case 'flipboard':
				echo '<li> <a class="rishi-flipboard" href=" ' . esc_url( 'https://share.flipboard.com/bookmarklet/popout?v=2&title=' . get_the_permalink( $post->ID ) . '&url=' . get_the_permalink( $post->ID ) . ' ' ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'flipboard' ) ) . '</a></li>';
			break;

			case 'ok':
				echo '<li> <a class="rishi-ok" href=" ' . esc_url( 'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=' . get_the_permalink( $post->ID ) . ' ' ) . ' " ' . $rel . ' target="_blank">' . $this->get_social_share_html_from_key( ( 'ok' ) ) . '</a></li>';
			break;
		}
	}

	/**
	 * This method returns an array of active social lists.
	 * It checks the default socials and adds them to the active social array if they are set to 'yes' in the theme mod.
	 *
	 * @return array The active social lists.
	 */
	public function get_active_social_lists() {

		$default_socials = apply_filters(
			'rishi_social_share_lists',
			array(
				'facebook',
				'twitter',
				'pinterest',
				'linkedin',
				'email',
				'reddit',
				'telegram',
				'viber',
				'whatsapp',
				'vk',
				'tumblr',
				'flipboard',
				'weibo',
				'ok',
				'xing'
			)
		);

		$share_prefix = 'single_blog_post_share_';

		$active_social_array = array();

		foreach ( $default_socials as $social ) {

			// this is done to carry out the default values for the social links
			if ( $social == 'facebook' || $social == 'twitter' || $social == 'linkedin' || $social == 'pinterest' ) {
				$defaultvalue = 'yes';
			} else {
				$defaultvalue = 'no';
			}
			if ( get_theme_mod( $share_prefix . $social, $defaultvalue ) === 'yes' ) {
				$active_social_array[] = $social;
			}
		}

		return $active_social_array;

	}

	/**
	 * This method generates the social share section.
	 * It retrieves the active social lists and generates the appropriate HTML for each list.
	 * It also applies various theme modifications to the social share section.
	 */
	public function rishi_companion_social_share() {

		$share_prefix = 'single_blog_post_';

		$active_social_lists = $this->get_active_social_lists();

		$class = 'rishi-share-box';

		$ed_title        = get_theme_mod( $share_prefix . 'has_share_box_title', 'no' );
		$share_box_title = get_theme_mod( $share_prefix . 'share_box_title', __( 'SHARE THIS POST', 'rishi-companion' ) );

		$box_sticky = get_theme_mod( $share_prefix . 'box_sticky', 'no' );

		if ( $box_sticky == 'yes' ) {
			$class .= ' data-location-sticky';
		} else {
			$class .= ' data-location-top';
		}

		$box_float = get_theme_mod( $share_prefix . 'box_float', 'left' );

		if ( $box_sticky == 'yes' && $box_float ) {
			$class .= ' data-float-' . $box_float;
		}

		$share_alignment = get_theme_mod( $share_prefix . 'share_alignment', 'left' );
		if ( $share_alignment ) {
			$class .= ' data-shape-' . $share_alignment;
		}

		$color_option = get_theme_mod( 'social_share_color_option', 'official' );

		if ( $color_option ) {
			$class .= ' rishi-color-type-' . $color_option;
		}

		$box_shape = get_theme_mod( $share_prefix . 'box_shape', 'square' );
		if ( $box_shape ) {
			$class .= ' data-shape-' . $box_shape;
		}

		$visibility = get_theme_mod(
			$share_prefix . 'visibility',
			array(
				'desktop' => 'desktop',
				'tablet'  => 'tablet',
			)
		);

		$class .= \rishi_visibility_for_devices( $visibility );
		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<div class="rishi-social-wrapper">
				<?php if ( $ed_title == 'yes' && ! empty( $share_box_title ) ) { ?>
					<span class="rishi-share-title"><?php echo esc_html( $share_box_title ); ?></span>
				<?php } ?>
				<ul class="rishi-social-icons">
					<?php foreach ( $active_social_lists as $social_list ) { ?>
						<?php
							$this->get_social_share( $social_list );
						?>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php
	}
}
