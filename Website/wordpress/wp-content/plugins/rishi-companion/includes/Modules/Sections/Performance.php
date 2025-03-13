<?php
/**
 * Performance Extension
 *
 * This class provides the functionality for the Performance extension.
 *
 * @package Rishi_Companion\Modules\Sections
 */
namespace Rishi_Companion\Modules\Sections;

use \Rishi\Customizer\Abstracts\Customize_Section;
use \Rishi\Customizer\ControlTypes;
class Performance extends Customize_Section {

	/**
     * The priority of the extension.
     *
     * @var int
     */
    protected $priority = 2;

    /**
     * The container of the extension.
     *
     * @var bool
     */
    protected $container = true;

    /**
     * The ID of the extension.
     *
     * @var string
     */
    protected $id = 'performance';

    /**
     * Get the type of the extension.
     *
     * @return string
     */
    public function get_type() {
        return self::OPTIONS;
    }

    /**
     * Get the order of the extension.
     *
     * @return int
     */
    public static function get_order() {
        return 52;
    }

    /**
     * Get the ID of the extension.
     *
     * @return string
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get the default values of the extension.
     *
     * @return array
     */
    protected function get_defaults() {
        return array();
    }

    /**
     * Get the title of the extension.
     *
     * @return string
     */
    public function get_title() {
        return __( 'Performance', 'rishi-companion' );
    }

	/**
     * Get the dynamic styles of the extension.
     *
     * @param array $styles
     * @return array
     */
    public function get_dynamic_styles($styles) {
        return array();
    }

    /**
     * Check if the extension is enabled.
     *
     * @return bool
     */
    public static function is_enabled() {
        $active_extensions = get_option('rc_active_extensions', array());

        if (in_array('performance', $active_extensions)) {
            return true;
        }

        return false;
    }

	 /**
     * Get the customize settings of the extension.
     *
     * @return array
     */
    protected function get_customize_settings() {
        return array(
			'performance_images_listing_panel' => array(
                'label'   => __( 'Images', 'rishi-companion' ),
                'control' => ControlTypes::PANEL,
                'divider' => 'bottom',
                'innerControls' => [
                    'has_lazy_load' => [
                        'label'   => __('Lazy Load Images', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'divider' => 'bottom',
                        'value'   => 'yes',
                        'help'    => __('This option will be auto disabled if you have JetPack\'s lazy load option enabled.', 'rishi-companion'),
                    ],
                    'exclude_lazy_load_images' => [
                        'label'   => __('Exclude Lazy Load Images', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'divider' => 'bottom',
                        'value'   => 'no',
                    ],
                    'exclude_leading_images' => [
                        'label'      => __('Exclude Above-the-fold Images', 'rishi-companion'),
                        'help'       => __('Select the number of Above-the-fold images you want to exlude from lazy loading.', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_NUMBER,
                        'design'     => 'inline',
                        'value'      => 3,
                        'min'        => 0,
                        'max'        => 10,
                        'divider'    => 'bottom',
                        'conditions' => [
                            'exclude_lazy_load_images' => 'yes'
                        ]
                    ],
                    'excluded_images_list' => [
                        'label'      => __('Excluded images list', 'rishi-companion'),
                        'value'      => get_theme_mod('excluded_images_list',''),
                        'divider'    => 'bottom',
                        'help'       => __('Specify keywords (e.g. image filename, CSS class, domain) from the image to be excluded (one per line).', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_TEXTAREA,
                        'conditions' => [
                            'exclude_lazy_load_images' => 'yes'
                        ]
                    ],
                    'lazy_load_type' => [
                        'label'   => __('Images Loading Animation Type', 'rishi-companion'),
                        'type'    => ControlTypes::INPUT_RADIO,
                        'divider' => 'bottom',
                        'value'   => 'fade',
                        'view'    => 'text',
                        'choices' => [
                            'fade'   => __('Fade', 'rishi-companion'),
                            'circle' => __('Circles', 'rishi-companion'),
                            'none'   => __('None', 'rishi-companion'),
                        ],
                        'conditions' => [
                            'has_lazy_load' => 'yes'
                        ]
                    ],
                    'lazy_load_featured_img' => [
                        'label'      => __('Disable Lazy Load on Featured Image', 'rishi-companion'),
                        'help'       => __('This option will disable lazy loading for the featured image on single post.', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_SWITCH,
                        'value'      => 'no',
                        'divider'    => 'bottom',
                        'conditions' => [
                            'has_lazy_load' => 'yes'
                        ]
                    ],
                    'responsive_images' => [
                        'label'   => __('Disable Responsive Images', 'rishi-companion'),
                        'help'    => __('Enable this option to disable responsive images on your website.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'divider' => 'bottom',
                        'value'   => 'no',
                    ],
                    'missing_img_dimensions' => [
                        'label'   => __('Add Missing Image Dimensions', 'rishi-companion'),
                        'help'    => __('This features add width and height attributes to the images to improve the CLS.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                    ],
                    \Rishi\Customizer\Helpers\Basic::uniqid() => [
                        'label'   => __('Image Dimensions', 'rishi-companion'),
                        'desc'    => __('This option will enable the crop size for the featured images. You can disable the ones that you are not using to save your hosting space.', 'rishi-companion'),
                        'control' => ControlTypes::TITLE,
                        'divider' => 'bottom',
                    ],
                    'featured_image_360_240' => [
                        'label'   => __('360x240', 'rishi-companion'),
                        'help'    => __('This image size is used on blog and archive pages.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'divider' => 'bottom',
                        'value'   => 'yes',
                    ],
                    'featured_image_750_520' => [
                        'label'   => __('750x520', 'rishi-companion'),
                        'help'    => __('This image size is used on single post with a sidebar.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'divider' => 'bottom',
                        'value'   => 'yes',
                    ],
                    'featured_image_1170_650' => [
                        'label'   => __('1170x650', 'rishi-companion'),
                        'help'    => __('This image size is used on single post without a sidebar.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'yes',
                    ],
                ]
            ),
            'performance_wordpress_listing_panel' => array(
                'label' => __('WordPress', 'rishi-companion'),
                'control' => ControlTypes::PANEL,
                'divider' => 'bottom',
                'innerControls' => [
                    'ed_favicon' => [
                        'label'   => __('Prevent Automatic Favicon Request', 'rishi-companion'),
                        'help'    => __('Disable the automatic favicon request to speed up your site.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'yes',

                    ],
                    'ed_emoji' => [
                        'label'   => __('Remove Emojis from frontend', 'rishi-companion'),
                        'help'    => __('Removes WordPress Emojis JavaScript file (wp-emoji-release.min.js).', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    'ed_self_pingbacks' => [
                        'label'   => __('Disable Self Pingbacks', 'rishi-companion'),
                        'help'    => __('Disable Self Pingbacks (generated when linking to an article on your own blog).', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    'ed_rssfeed' => [
                        'label'   => __('Disable RSS Feeds', 'rishi-companion'),
                        'help'    => __('Disable WordPress generated RSS feeds and 301 redirect URL to parent.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    'ed_rssfeed_links' => [
                        'label'   => __('Remove RSS Feed Links', 'rishi-companion'),
                        'help'    => __('Disable WordPress generated RSS feed link tags.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    'ed_embeds' => [
                        'label'   => __('Disable WordPress Embeds', 'rishi-companion'),
                        'help'    => __('Removes WordPress Embeds Javascript file.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    'ed_local_gravatar' => [
                        'label'   => __('Local Gravatars', 'rishi-companion'),
                        'help'    => __('It will enable to load Gravatars from your servers (instead of making requests to the Gravatar site), to improve loading time.', 'rishi-companion'),
                        'control' => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top:bottom',
                    ]
                ]
            ),
            'performance_cssandjs_listing_panel' => array(
                'label' => __('CSS/JS Optimization', 'rishi-companion'),
                'control' => ControlTypes::PANEL,
                'divider' => 'bottom',
                'innerControls' => [
                    'ed_ver' => [
                        'label'   => __('Remove "ver" parameter from CSS and JS file calls.', 'rishi-companion'),
                        'help'    => __('Removes version query strings from your static resources to improve the caching of those resources.', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    'ed_gutenberg_style' => [
                        'label'   => __('Disable Gutenberg style on Page Builder', 'rishi-companion'),
                        'help'    => __('Disable the block style css on the page built with Elementor.', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    'ed_preload_css' => [
                        'label'   => __('Preload CSS', 'rishi-companion'),
                        'help'    => __('Preloading your CSS helps the pages load quicker.', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'top',
                    ],
                    \Rishi\Customizer\Helpers\Basic::uniqid() => [
                        'label'   => __('JavaScript Optimization', 'rishi-companion'),
                        'help'    => __('Manage Javascript files loading on your site.', 'rishi-companion'),
                        'control' => ControlTypes::TITLE,
                        'divider' => 'top',
                    ],
                    'ed_defer_js' => [
                        'label'   => __('Defer JavaScript Files', 'rishi-companion'),
                        'help'    => __('Defer loading of render-blocking JavaScript files for faster website loading.', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'bottom',
                    ],
                    'excluded_js_list' => [
                        'label'   => __('Deferred Excluded JavaScript Files', 'rishi-companion'),
                        'help'    => __('Specify JavaScript files to be excluded from defer (one per line).', 'rishi-companion'),
                        'control' 	  => ControlTypes::INPUT_TEXTAREA,
                        'value'   => 'jQuery.min.js',
                        'divider' => 'bottom',
                        'conditions' => ['ed_defer_js' => 'yes']
                    ],
                    'ed_delay_js' => [
                        'label'   => __('Delay JavaScript Files', 'rishi-companion'),
                        'help'    => __('It Improves the loading speed by delaying the loading of JS files until user interaction such as click, scroll, etc.', 'rishi-companion'),
                        'control'    => ControlTypes::INPUT_SWITCH,
                        'value'   => 'no',
                        'divider' => 'bottom',
                    ],
                    'delay_behaviour' => [
                        'label'	  => __('Delay Behaviour', 'rishi-companion'),
                        'control'	  => ControlTypes::INPUT_SELECT,
                        'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys([
                            'all_scripts' 		=> __('All Scripts', 'rishi-companion'),
                            'specific_scripts' 	=> __('Specific Scripts', 'rishi-companion'),
                        ]),
                        'value'	  => 'all_scripts',
                        'divider' => 'bottom',
                        'conditions' => ['ed_delay_js' => 'yes']
                    ],
                    'excluded_delay_list' => [
                        'label'   => __('Excluded from Delay', 'rishi-companion'),
                        'help'    => __('Specify JavaScript files to be excluded from delaying execution (one per line).', 'rishi-companion'),
                        'control' 	  => ControlTypes::INPUT_TEXTAREA,
                        'divider' => 'bottom',
                        'value'   => [],
                        'conditions' => ['ed_delay_js' => 'yes', 'delay_behaviour' => 'all_scripts'],
                    ],
                    'included_delay_list' => [
                        'label'   => __('Delayed Scripts', 'rishi-companion'),
                        'help'    => __('Specify JavaScript files to be included while delaying execution (one per line).', 'rishi-companion'),
                        'control' 	  => ControlTypes::INPUT_TEXTAREA,
                        'conditions' => ['ed_delay_js' => 'yes', 'delay_behaviour' => 'specific_scripts'],
                    ],
                    'delay_timeout' => [
                        'label'   => __('Delay Timeout', 'rishi-companion'),
                        'help'    => __('Set the time to load the delayed scripts if no user interaction is detected.', 'rishi-companion'),
                        'control' 	  => ControlTypes::INPUT_SELECT,
                        'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys([
                            'none'  => __('None', 'rishi-companion'),
                            '1' 	=> __('One second', 'rishi-companion'),
                            '2'		=> __('Two seconds', 'rishi-companion'),
                            '3' 	=> __('Three seconds', 'rishi-companion'),
                            '4' 	=> __('Four seconds', 'rishi-companion'),
                            '5' 	=> __('Five seconds', 'rishi-companion'),
                            '6' 	=> __('Six seconds', 'rishi-companion'),
                            '7' 	=> __('Seven seconds', 'rishi-companion'),
                            '8' 	=> __('Eight seconds', 'rishi-companion'),
                            '9' 	=> __('Nine seconds', 'rishi-companion'),
                            '10' 	=> __('Ten seconds', 'rishi-companion'),
                        ]),
                        'value'	=> 'none',
                        'conditions' => ['ed_delay_js' => 'yes'],
                    ],
                ]
                ),
                'performance_fonts_listing_panel' => array(
                    'label' => __('Fonts', 'rishi-companion'),
                    'control' => ControlTypes::PANEL,
                    'divider' => 'bottom',
                    'innerControls' => [
                        'ed_display_swap' => [
                            'label'   => __('Display Swap', 'rishi-companion'),
                            'help'    => __('It adds font display swap property to your Google fonts to improve its rendering.', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'yes',
                            'divider' => 'bottom',
                        ]
                    ],
                ),
                'performance_elementor_listing_panel' => class_exists( 'Elementor\\Plugin' ) ? array(
                    'label' => __('Elementor', 'rishi-companion'),
                    'control' => ControlTypes::PANEL,
                    'divider' => 'bottom',
                    'innerControls' => [
                        'ed_elementor_google_fonts' => [
							'label'   => __('Disable Google Fonts', 'rishi-companion'),
							'help'    => __('Enabling this option will prevent Elementor Google fonts from loading.', 'rishi-companion'),
							'control'    => ControlTypes::INPUT_SWITCH,
							'value'   => 'no',
                            'divider' => 'bottom',
						],
						'ed_elementor_icons' => [
							'label'   => __('Disable Icons', 'rishi-companion'),
							'help'    => __('Enabling this option will prevent Elementor icons from loading.', 'rishi-companion'),
							'control'    => ControlTypes::INPUT_SWITCH,
							'value'   => 'no',
                            'divider' => 'bottom',
						],
						'ed_elementor_font_awesome' => [
							'label'   => __('Disable Font Awesome', 'rishi-companion'),
							'help'    => __('Enabling this option will prevent Elementor Font Awesome icons from loading.', 'rishi-companion'),
							'control'    => ControlTypes::INPUT_SWITCH,
							'value'   => 'no',
                            'divider' => 'bottom',
						],
						'ed_elementor_frontend_script' => [
							'label'   => __('Disable Frontend Script', 'rishi-companion'),
							'help'    => __('Enabling this option will prevent Elementor frontend scripts (such as swiper, dialog, share link) from loading.', 'rishi-companion'),
							'control'    => ControlTypes::INPUT_SWITCH,
							'value'   => 'no',
                            'divider' => 'bottom',
						],
						'ed_elementor_elementor_pro_script' => [
							'label'   => __('Disable Elementor Pro Scripts', 'rishi-companion'),
							'help'    => __('Enabling this option will prevent some of the scripts of Elementor Pro from loading.', 'rishi-companion'),
							'control' => ControlTypes::INPUT_SWITCH,
							'value'   => 'no',
                            'divider' => 'bottom',
						],
                    ],
                ) : array(),
                'performance_woo_listing_panel' => class_exists( 'WooCommerce' ) ? array(
                    'label' => __('WooCommerce', 'rishi-companion'),
                    'control' => ControlTypes::PANEL,
                    'divider' => 'bottom',
                    'innerControls' => [
                        'ed_woo_scripts' => [
							'label'   => __('Disable Scripts and Styles', 'rishi-companion'),
							'help'    => __('Disables WooCommerce scripts and styles except on product, cart, and checkout pages.', 'rishi-companion'),
							'control' => ControlTypes::INPUT_SWITCH,
							'value'   => 'no',
                            'divider' => 'bottom',
						],
						'ed_woo_cart_fragramentation' => [
							'label'   => __('Disable Cart Fragmentation', 'rishi-companion'),
							'help'    => __('Completely disables WooCommerce cart fragmentation script.', 'rishi-companion'),
							'control' => ControlTypes::INPUT_SWITCH,
							'value'   => 'no',
							'divider' => 'bottom',
						],
                    ]
                ) : array()
		);
    }

     /**
     * Add controls for the extension.
     */
    protected function add_controls() {

		$this->wp_customize->add_section(
			'performance_panel',
			array(
				'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
				'default'           => '',
			)
		);
		$this->wp_customize->add_setting(
			'performance_section_options',
			array_merge(
				array( 'default' => '' ),
				$this->get_setting()
			)
		);

		$control = new \WP_Customize_Control(
			$this->wp_customize,
			'performance_section_options',
			array(
				'label'              => $this->get_title(),
				'description'        => '',
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => 'performance_section_options',
				'section'            => $this->get_id(),
				'innerControls'      => $this->get_customize_settings(),
			)
		);

		$control->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $this->get_setting(),
			'customize_section' => 'container',
			'innerControls'     => $this->get_customize_settings(),
			'sanitize_callback' => function ($input, $setting) {
				return $input;
			},
		);

		$this->wp_customize->add_control( $control );
	}

}
