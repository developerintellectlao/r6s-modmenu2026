<?php
/**
 * Custom functions used in the theme
 *
 * @package Rishi
 */

use \Rishi\Customizer\Helpers\Defaults as Defaults;
if ( ! function_exists( 'rishi_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function rishi_setup() {
		/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Rishi, use a find and replace
		* to change 'rishi' to the name of your theme in all the template files.
		*/
		load_theme_textdomain( 'rishi', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
		add_theme_support( 'title-tag' );

		/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/851
		*/
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary-menu'   => esc_html__( 'Primary', 'rishi' ),
				'secondary-menu' => esc_html__( 'Secondary', 'rishi' ),
				'footer-menu'    => esc_html__( 'Footer', 'rishi' ),
			)
		);

		/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'rishi_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		/**
		 * Custom Image sizes for Rishi theme.
		 *
		 * @link https://developer.wordpress.org/reference/functions/add_image_size/
		 */
		add_image_size( 'rishi-fullwidth', 1170, 650, ( apply_filters( 'rishi_image_dimension_1170_650', true ) ) ? true : false );
		add_image_size( 'rishi-withsidebar', 750, 520, ( apply_filters( 'rishi_image_dimension_750_520', true ) ) ? true : false );
		add_image_size( 'rishi-blog-grid', 360, 240, ( apply_filters( 'rishi_image_dimension_360_240', true ) ) ? true : false );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );

		/**
		 * Custom Block Gradient Presets.
		 *
		 * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#block-gradient-presets
		 */
		add_theme_support(
			'editor-gradient-presets',
			array(
				array(
					'name'     => esc_attr__( 'Vivid cyan blue to vivid purple', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgba(6,147,227,1) 0%,rgb(155,81,224) 100%)',
					'slug'     => 'vivid-cyan-blue-to-vivid-purple',
				),

				array(
					'name'     => esc_attr__( 'Light green cyan to vivid green cyan', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(122,220,180) 0%,rgb(0,208,130) 100%)',
					'slug'     => 'light-green-cyan-to-vivid-green-cyan',
				),

				array(
					'name'     => esc_attr__( 'Luminous vivid amber to luminous vivid orange', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgba(252,185,0,1) 0%,rgba(255,105,0,1) 100%)',
					'slug'     => 'luminous-vivid-amber-to-luminous-vivid-orange',
				),

				array(
					'name'     => esc_attr__( 'Luminous vivid orange to vivid red', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgba(255,105,0,1) 0%,rgb(207,46,46) 100%)',
					'slug'     => 'luminous-vivid-orange-to-vivid-red',
				),

				array(
					'name'     => esc_attr__( 'Cool to warm spectrum', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(74,234,220) 0%,rgb(151,120,209) 20%,rgb(207,42,186) 40%,rgb(238,44,130) 60%,rgb(251,105,98) 80%,rgb(254,248,76) 100%)',
					'slug'     => 'cool-to-warm-spectrum',
				),

				array(
					'name'     => esc_attr__( 'Blush light purple', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(255,206,236) 0%,rgb(152,150,240) 100%)',
					'slug'     => 'blush-light-purple',
				),

				array(
					'name'     => esc_attr__( 'Blush bordeaux', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(254,205,165) 0%,rgb(254,45,45) 50%,rgb(107,0,62) 100%)',
					'slug'     => 'blush-bordeaux',
				),

				array(
					'name'     => esc_attr__( 'Luminous dusk', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(255,203,112) 0%,rgb(199,81,192) 50%,rgb(65,88,208) 100%)',
					'slug'     => 'luminous-dusk',
				),

				array(
					'name'     => esc_attr__( 'Pale ocean', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(255,245,203) 0%,rgb(182,227,212) 50%,rgb(51,167,181) 100%)',
					'slug'     => 'pale-ocean',
				),

				array(
					'name'     => esc_attr__( 'Electric grass', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(202,248,128) 0%,rgb(113,206,126) 100%)',
					'slug'     => 'electric-grass',
				),

				array(
					'name'     => esc_attr__( 'Midnight', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(2,3,129) 0%,rgb(40,116,252) 100%)',
					'slug'     => 'midnight',
				),

				array(
					'name'     => esc_attr__( 'Juicy Peach', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #ffecd2 0%, #fcb69f 100%)',
					'slug'     => 'juicy-peach',
				),

				array(
					'name'     => esc_attr__( 'Young Passion', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #ff8177 0%, #ff867a 0%, #ff8c7f 21%, #f99185 52%, #cf556c 78%, #b12a5b 100%)',
					'slug'     => 'young-passion',
				),

				array(
					'name'     => esc_attr__( 'True Sunset', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #fa709a 0%, #fee140 100%)',
					'slug'     => 'true-sunset',
				),

				array(
					'name'     => esc_attr__( 'Morpheus Den', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #30cfd0 0%, #330867 100%)',
					'slug'     => 'morpheus-den',
				),

				array(
					'name'     => esc_attr__( 'Plum Plate', 'rishi' ),
					'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
					'slug'     => 'plum-plate',
				),

				array(
					'name'     => esc_attr__( 'Aqua Splash', 'rishi' ),
					'gradient' => 'linear-gradient(15deg, #13547a 0%, #80d0c7 100%)',
					'slug'     => 'aqua-splash',
				),

				array(
					'name'     => esc_attr__( 'Love Kiss', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #ff0844 0%, #ffb199 100%)',
					'slug'     => 'love-kiss',
				),

				array(
					'name'     => esc_attr__( 'New Retrowave', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #3b41c5 0%, #a981bb 49%, #ffc8a9 100%)',
					'slug'     => 'new-retrowave',
				),

				array(
					'name'     => esc_attr__( 'Plum Bath', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #cc208e 0%, #6713d2 100%)',
					'slug'     => 'plum-bath',
				),

				array(
					'name'     => esc_attr__( 'High Flight', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #0acffe 0%, #495aff 100%)',
					'slug'     => 'high-flight',
				),

				array(
					'name'     => esc_attr__( 'Teen Party', 'rishi' ),
					'gradient' => 'linear-gradient(-225deg, #FF057C 0%, #8D0B93 50%, #321575 100%)',
					'slug'     => 'teen-party',
				),

				array(
					'name'     => esc_attr__( 'Fabled Sunset', 'rishi' ),
					'gradient' => 'linear-gradient(-225deg, #231557 0%, #44107A 29%, #FF1361 67%, #FFF800 100%)',
					'slug'     => 'fabled-sunset',
				),

				array(
					'name'     => esc_attr__( 'Arielle Smile', 'rishi' ),
					'gradient' => 'radial-gradient(circle 248px at center, #16d9e3 0%, #30c7ec 47%, #46aef7 100%)',
					'slug'     => 'arielle-smile',
				),

				array(
					'name'     => esc_attr__( 'Itmeo Branding', 'rishi' ),
					'gradient' => 'linear-gradient(180deg, #2af598 0%, #009efd 100%)',
					'slug'     => 'itmeo-branding',
				),

				array(
					'name'     => esc_attr__( 'Deep Blue', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #6a11cb 0%, #2575fc 100%)',
					'slug'     => 'deep-blue',
				),

				array(
					'name'     => esc_attr__( 'Strong Bliss', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%)',
					'slug'     => 'strong-bliss',
				),

				array(
					'name'     => esc_attr__( 'Sweet Period', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #3f51b1 0%, #5a55ae 13%, #7b5fac 25%, #8f6aae 38%, #a86aa4 50%, #cc6b8e 62%, #f18271 75%, #f3a469 87%, #f7c978 100%)',
					'slug'     => 'sweet-period',
				),

				array(
					'name'     => esc_attr__( 'Purple Division', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #7028e4 0%, #e5b2ca 100%)',
					'slug'     => 'purple-division',
				),

				array(
					'name'     => esc_attr__( 'Cold Evening', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #0c3483 0%, #a2b6df 100%, #6b8cce 100%, #a2b6df 100%)',
					'slug'     => 'cold-evening',
				),

				array(
					'name'     => esc_attr__( 'Mountain Rock', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #868f96 0%, #596164 100%)',
					'slug'     => 'mountain-rock',
				),

				array(
					'name'     => esc_attr__( 'Desert Hump', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #c79081 0%, #dfa579 100%)',
					'slug'     => 'desert-hump',
				),

				array(
					'name'     => esc_attr__( 'Eternal Constance', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #09203f 0%, #537895 100%)',
					'slug'     => 'ethernal-constance',
				),

				array(
					'name'     => esc_attr__( 'Happy Memories', 'rishi' ),
					'gradient' => 'linear-gradient(-60deg, #ff5858 0%, #f09819 100%)',
					'slug'     => 'happy-memories',
				),

				array(
					'name'     => esc_attr__( 'Grown Early', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #0ba360 0%, #3cba92 100%)',
					'slug'     => 'grown-early',
				),

				array(
					'name'     => esc_attr__( 'Morning Salad', 'rishi' ),
					'gradient' => 'linear-gradient(-225deg, #B7F8DB 0%, #50A7C2 100%)',
					'slug'     => 'morning-salad',
				),

				array(
					'name'     => esc_attr__( 'Night Call', 'rishi' ),
					'gradient' => 'linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%)',
					'slug'     => 'night-call',
				),

				array(
					'name'     => esc_attr__( 'Mind Crawl', 'rishi' ),
					'gradient' => 'linear-gradient(-225deg, #473B7B 0%, #3584A7 51%, #30D2BE 100%)',
					'slug'     => 'mind-crawl',
				),

				array(
					'name'     => esc_attr__( 'Angel Care', 'rishi' ),
					'gradient' => 'linear-gradient(-225deg, #FFE29F 0%, #FFA99F 48%, #FF719A 100%)',
					'slug'     => 'angel-care',
				),

				array(
					'name'     => esc_attr__( 'Juicy Cake', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #e14fad 0%, #f9d423 100%)',
					'slug'     => 'juicy-cake',
				),

				array(
					'name'     => esc_attr__( 'Rich Metal', 'rishi' ),
					'gradient' => 'linear-gradient(to right, #d7d2cc 0%, #304352 100%)',
					'slug'     => 'rich-metal',
				),

				array(
					'name'     => esc_attr__( 'Mole Hall', 'rishi' ),
					'gradient' => 'linear-gradient(-20deg, #616161 0%, #9bc5c3 100%)',
					'slug'     => 'mole-hall',
				),

				array(
					'name'     => esc_attr__( 'Cloudy Knoxville', 'rishi' ),
					'gradient' => 'linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%)',
					'slug'     => 'cloudy-knoxville',
				),

				array(
					'name'     => esc_attr__( 'Very light gray to cyan bluish gray', 'rishi' ),
					'gradient' => 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)',
					'slug'     => 'very-light-gray-to-cyan-bluish-gray',
				),

				array(
					'name'     => esc_attr__( 'Soft Grass', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #c1dfc4 0%, #deecdd 100%)',
					'slug'     => 'soft-grass',
				),

				array(
					'name'     => esc_attr__( 'Saint Petersburg', 'rishi' ),
					'gradient' => 'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)',
					'slug'     => 'saint-petersburg',
				),

				array(
					'name'     => esc_attr__( 'Everlasting Sky', 'rishi' ),
					'gradient' => 'linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%)',
					'slug'     => 'everlasting-sky',
				),

				array(
					'name'     => esc_attr__( 'Kind Steel', 'rishi' ),
					'gradient' => 'linear-gradient(-20deg, #e9defa 0%, #fbfcdb 100%)',
					'slug'     => 'kind-steel',
				),

				array(
					'name'     => esc_attr__( 'Over Sun', 'rishi' ),
					'gradient' => 'linear-gradient(60deg, #abecd6 0%, #fbed96 100%)',
					'slug'     => 'over-sun',
				),

				array(
					'name'     => esc_attr__( 'Premium White', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #d5d4d0 0%, #d5d4d0 1%, #eeeeec 31%, #efeeec 75%, #e9e9e7 100%)',
					'slug'     => 'premium-white',
				),

				array(
					'name'     => esc_attr__( 'Clean Mirror', 'rishi' ),
					'gradient' => 'linear-gradient(45deg, #93a5cf 0%, #e4efe9 100%)',
					'slug'     => 'clean-mirror',
				),

				array(
					'name'     => esc_attr__( 'Wild Apple', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #d299c2 0%, #fef9d7 100%)',
					'slug'     => 'wild-apple',
				),

				array(
					'name'     => esc_attr__( 'Snow Again', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #e6e9f0 0%, #eef1f5 100%)',
					'slug'     => 'snow-again',
				),

				array(
					'name'     => esc_attr__( 'Confident Cloud', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #dad4ec 0%, #dad4ec 1%, #f3e7e9 100%)',
					'slug'     => 'confident-cloud',
				),

				array(
					'name'     => esc_attr__( 'Glass Water', 'rishi' ),
					'gradient' => 'linear-gradient(to top, #dfe9f3 0%, white 100%)',
					'slug'     => 'glass-water',
				),

				array(
					'name'     => esc_attr__( 'Perfect White', 'rishi' ),
					'gradient' => 'linear-gradient(-225deg, #E3FDF5 0%, #FFE6FA 100%)',
					'slug'     => 'perfect-white',
				),
			)
		);

		add_theme_support(
			'editor-color-palette',
			apply_filters(
				'rishi_editor_color_palette',
				array(
					array(
						'name'  => esc_attr__( 'Palette Color 1', 'rishi' ),
						'slug'  => 'palette-color-1',
						'color' => rishi_get_active_pallete_values( 'color1' ),
					),

					array(
						'name'  => esc_attr__( 'Palette Color 2', 'rishi' ),
						'slug'  => 'palette-color-2',
						'color' => rishi_get_active_pallete_values( 'color2' ),
					),

					array(
						'name'  => esc_attr__( 'Palette Color 3', 'rishi' ),
						'slug'  => 'palette-color-3',
						'color' => rishi_get_active_pallete_values( 'color3' ),
					),

					array(
						'name'  => esc_attr__( 'Palette Color 4', 'rishi' ),
						'slug'  => 'palette-color-4',
						'color' => rishi_get_active_pallete_values( 'color4' ),
					),

					array(
						'name'  => esc_attr__( 'Palette Color 5', 'rishi' ),
						'slug'  => 'palette-color-5',
						'color' => rishi_get_active_pallete_values( 'color5' ),
					),

					array(
						'name'  => esc_attr__( 'Palette Color 6', 'rishi' ),
						'slug'  => 'palette-color-6',
						'color' => rishi_get_active_pallete_values( 'color6' ),
					),

					array(
						'name'  => esc_attr__( 'Palette Color 7', 'rishi' ),
						'slug'  => 'palette-color-7',
						'color' => rishi_get_active_pallete_values( 'color7' ),
					),

					array(
						'name'  => esc_attr__( 'Palette Color 8', 'rishi' ),
						'slug'  => 'palette-color-8',
						'color' => rishi_get_active_pallete_values( 'color8' ),
					),
				)
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'rishi_setup' );

/**
 * Gets the color palette value
 *
 * @param [type] string
 * @return void
 */
function rishi_get_active_pallete_values( $colorId ) {
	if ( ! $colorId ) {
		return;
	}
	/**
	 * Custom Pallete Colors.
	 */
	$palleteColors = get_theme_mod(
		'colorPalette',
		array(
			'color1' => array( 'color' => 'rgba(41, 41, 41, 0.9)' ),
			'color2' => array( 'color' => '#292929' ),
			'color3' => array( 'color' => '#216BDB' ),
			'color4' => array( 'color' => '#5081F5' ),
			'color5' => array( 'color' => '#ffffff' ),
			'color6' => array( 'color' => '#EDF2FE' ),
			'color7' => array( 'color' => '#e9f1fa' ),
			'color8' => array( 'color' => '#F9FBFE' ),
		)
	);

	$currentPalette    = ( isset( $palleteColors['current_palette'] ) && ! empty( $palleteColors['current_palette'] ) ) ? $palleteColors['current_palette'] : 'palette-1';
	$palleteCollection = ( isset( $palleteColors['palettes'] ) && ! empty( $palleteColors['palettes'] ) ) ? $palleteColors['palettes'] : array();

	$color = null;

	if ( isset( $palleteCollection ) && ! empty( $palleteCollection ) ) {
		foreach ( $palleteCollection as $singlePallete ) {
			if ( isset( $singlePallete['id'] ) && $singlePallete['id'] === $currentPalette ) {
				$color = $singlePallete[ $colorId ];
			}
		}
	} else {
		$color = $palleteColors[ $colorId ];
	}
	return isset( $color['color'] ) ? $color['color'] : '';
}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function rishi_widgets_init() {
	$sidebars = array(
		'sidebar-1'    => array(
			'name'        => __( 'Sidebar', 'rishi' ),
			'description' => __( 'Default Sidebar', 'rishi' ),
		),
		'footer-one'   => array(
			'name'        => __( 'Footer One', 'rishi' ),
			'description' => __( 'Add footer one widgets here.', 'rishi' ),
		),
		'footer-two'   => array(
			'name'        => __( 'Footer Two', 'rishi' ),
			'description' => __( 'Add footer two widgets here.', 'rishi' ),
		),
		'footer-three' => array(
			'name'        => __( 'Footer Three', 'rishi' ),
			'description' => __( 'Add footer three widgets here.', 'rishi' ),
		),
		'footer-four'  => array(
			'name'        => __( 'Footer Four', 'rishi' ),
			'description' => __( 'Add footer four widgets here.', 'rishi' ),
		),
		'footer-five'  => array(
			'name'        => __( 'Footer Five', 'rishi' ),
			'description' => __( 'Add footer five widgets here.', 'rishi' ),
		),
		'footer-six'   => array(
			'name'        => __( 'Footer Six', 'rishi' ),
			'description' => __( 'Add footer six widgets here.', 'rishi' ),
		),
	);

	foreach ( $sidebars as $id => $sidebar ) {
		register_sidebar(
			array(
				'name'          => $sidebar['name'],
				'id'            => $id,
				'description'   => $sidebar['description'],
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => apply_filters( 'rishi_before_widget_title', '<h2 class="widget-title" itemprop="name">' ),
				'after_title'   => apply_filters( 'rishi_after_widget_title', '</h2>' ),
			)
		);
	}
}
add_action( 'widgets_init', 'rishi_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function rishi_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'rishi_content_width', 750 );
}
add_action( 'after_setup_theme', 'rishi_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function rishi_scripts() {
	$defaults = Defaults::get_layout_defaults();
	$suffix   = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Add parameters for the JS
	global $wp_query;
	$max                 = $wp_query->max_num_pages;
	$paged               = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
	$local_google_fonts  = get_theme_mod( 'local_google_fonts', 'no' );
	$preload_local_fonts = get_theme_mod( 'preload_local_fonts', 'no' );

	/** Ajax Pagination */
	if ( is_archive() ) {
		if ( is_author() ) {
			$pagination       = get_theme_mod( 'author_post_navigation', $defaults['author_post_navigation'] );
			$blog_page_layout = get_theme_mod( 'author_page_layout', $defaults['author_page_layout'] );
		} else {
			$pagination       = get_theme_mod( 'archive_post_navigation', $defaults['archive_post_navigation'] );
			$blog_page_layout = get_theme_mod( 'archive_page_layout', $defaults['archive_page_layout'] );
		}
	} elseif ( is_search() ) {
		$pagination       = get_theme_mod( 'search_post_navigation', $defaults['search_post_navigation'] );
		$blog_page_layout = get_theme_mod( 'search_page_layout', $defaults['search_page_layout'] );
	} else {
		$pagination       = get_theme_mod( 'post_navigation', $defaults['post_navigation'] );
		$blog_page_layout = get_theme_mod( 'blog_page_layout', $defaults['blog_page_layout'] );
	}

	if ( rishi_is_woocommerce_activated() ) {
		wp_enqueue_style( 'rishi-woocommerce', get_template_directory_uri() . '/compatibility/woocommerce.css', array(), RISHI_VERSION );
	}

	if ( rishi_is_elementor_activated() ) {
		wp_enqueue_style( 'rishi-elementor', get_template_directory_uri() . '/compatibility/elementor.css', array(), RISHI_VERSION );
	}

	if ( rishi_is_tutor_lms_activated() || rishi_is_learndash_activated() ) {
		wp_enqueue_style( 'rishi-lms', get_template_directory_uri() . '/compatibility/lms.css', array(), RISHI_VERSION );
	}

	wp_enqueue_style( 'rishi-style', get_template_directory_uri() . '/style' . $suffix . '.css', array(), RISHI_VERSION );

	/**
	 * Filter to process the dynamic theme css
	 */
	$theme_css_data = apply_filters( 'rishi_dynamic_theme_css', '' );
	wp_add_inline_style( 'rishi-style', $theme_css_data );

	/**
	 * Filter to process the dynamic theme css for customizer preview
	 */
	$customizer_css = apply_filters( 'rishi_dynamic_customizer_css', '' );
	wp_add_inline_style( 'rishi-style', $customizer_css );

	$dependencies_file_path = get_template_directory() . '/dist/custom/custom.asset.php';

	if ( file_exists( $dependencies_file_path ) ) {
		$meta_assets = require $dependencies_file_path;
		$meta_js_dep = ( ! empty( $meta_assets['dependencies'] ) ) ? $meta_assets['dependencies'] : array();
		$meta_ver    = ( ! empty( $meta_assets['version'] ) ) ? $meta_assets['version'] : RISHI_VERSION;

		wp_enqueue_script(
			'rishi-custom',
			get_template_directory_uri() . '/dist/custom/custom.js',
			$meta_js_dep,
			$meta_ver,
			true
		);

		wp_localize_script(
			'rishi-custom',
			'rishi_ajax',
			array(
				'url'        => admin_url( 'admin-ajax.php' ),
				'startPage'  => $paged,
				'maxPages'   => $max,
				'nextLink'   => next_posts( $max, false ),
				'autoLoad'   => $pagination,
				'bp_layout'  => $blog_page_layout,
				'rtl'        => is_rtl(),
				'plugin_url' => plugins_url(),
			)
		);

		if ( ! is_singular() && ! is_404() && ( function_exists( 'is_shop' ) && ! is_shop() ) && $blog_page_layout === 'masonry_grid' ) {
			wp_enqueue_script(
				'rishi-masonry',
				get_template_directory_uri() . '/js/masonryLayout.js',
				array( 'masonry' ),
				RISHI_VERSION,
				true
			);
		}
	}
	wp_style_add_data( 'rishi-style', 'rtl', 'replace' );

	if ( $suffix ) {
		wp_style_add_data( 'rishi-style', 'suffix', $suffix );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_style( 'rishi-google-fonts', rishi_load_google_fonts(), array(), null );

	$fonts_url = rishi_load_google_fonts();
	if ( $local_google_fonts == 'yes' && ! is_customize_preview() && ! is_admin() && $preload_local_fonts == 'yes' && $fonts_url !== '' ) {
		rishi_preload_local_fonts( rishi_load_google_fonts() );
	}

}
add_action( 'wp_enqueue_scripts', 'rishi_scripts', 9999 );

if ( ! function_exists( 'rishi_sidebar_scripts' ) ) :
	/**
	 * Enqueue admin scripts and styles.
	 */
	function rishi_sidebar_scripts( $hook ) {
		/**
		 * Post Meta CSS
		 */
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			wp_enqueue_style( 'rishi-post-meta-style', RISHI_CUSTOMIZER_BUILDER_DIR__URI . '/dist/postmeta.css' );

			$editor_assets = require get_template_directory() . '/customizer/dist/postmeta.asset.php';
			wp_enqueue_script(
				'rishi-main-editor-scripts',
				get_template_directory_uri() . '/customizer/dist/postmeta.js',
				$editor_assets['dependencies'],
				$editor_assets['version'],
				true
			);
		}
	}
endif;
add_action( 'admin_enqueue_scripts', 'rishi_sidebar_scripts' );

if ( ! function_exists( 'rishi_block_editor_styles' ) ) :
	/**
	 * Enqueue editor styles for Gutenberg
	 */
	function rishi_block_editor_styles() {

		/**
		 * Block styles.
		 */
		wp_enqueue_style( 'rishi-block-editor-style', get_template_directory_uri() . '/inc/assets/css/editor-block.css', array(), RISHI_VERSION );
		$css = rishi_customizer()->dynamic_style->print_block_editor_css();
		wp_add_inline_style( 'rishi-block-editor-style', trim($css) );
	
		// Enqueue Google Fonts.
		wp_register_style( 'rishi-admin-google-fonts', rishi_load_google_fonts(), array(), null );
		wp_enqueue_style( 'rishi-admin-google-fonts' );

	}
endif;
add_action( 'enqueue_block_editor_assets', 'rishi_block_editor_styles' );

add_action( 'init', 'rishi_register_block_assets_in_site_editor' );
function rishi_register_block_assets_in_site_editor() {
	global $pagenow;
	if ( 'site-editor.php' === $pagenow ) {
		rishi_block_editor_styles();
	}
}

if ( ! function_exists( 'rishi_register_meta_fields' ) ) :
	/**
	 * Register post meta field.
	 */
	function rishi_register_meta_fields() {
		$meta_fields = apply_filters( 'rishi_meta_key_data', array(
			'breadcrumbs_single_post'           => 'string',
			'page_title_panel'                  => 'string',
			'breadcrumbs_single_page'           => 'string',
			'single_page_alignment'             => 'string',
			'single_page_margin'                => 'string',
			'page_structure_type'               => 'string',
			'content_style_source'              => 'string',
			'content_style'                     => 'string',
			'blog_post_streched_ed'             => 'string',
			'blog_page_streched_ed'             => 'string',
			'has_transparent_header'            => 'string',
			'disable_transparent_header'        => 'string',
			'vertical_spacing_source'           => 'string',
			'content_area_spacing'              => 'string',
			'single_post_content_background'    => 'string',
			'single_page_content_background'    => 'string',
			'single_post_boxed_content_spacing' => 'string',
			'single_page_boxed_content_spacing' => 'string',
			'single_post_content_boxed_radius'  => 'string',
			'single_page_content_boxed_radius'  => 'string',
			'disable_featured_image'            => 'string',
			'disable_post_tags'                 => 'string',
			'disable_author_box'                => 'string',
			'disable_posts_navigation'          => 'string',
			'disable_comments'                  => 'string',
			'disable_related_posts'             => 'string',
			'disable_header'                    => 'string',
			'disable_footer'                    => 'string',
		));

		$post_types = array( 'post', 'page' );

		foreach ( $meta_fields as $meta_field => $type ) {
			foreach ( $post_types as $post_type ) {
				register_post_meta(
					$post_type,
					$meta_field,
					array(
						'show_in_rest' => true,
						'single'       => true,
						'type'         => $type,
						'schema'       => array(
							'type' => $type,
						),
					)
				);
			}
		}
	}
endif;
add_action( 'init', 'rishi_register_meta_fields' );

if ( ! function_exists( 'rishi_breadcrumb_init' ) ) :
	/**
	 * Hooked breadcrumb for different positions
	 *
	 * @return void
	 */
	function rishi_breadcrumb_init() {

		$defaults = Defaults::breadcrumbs_defaults();

		$breadcrumbs_position = get_theme_mod( 'breadcrumbs_position', $defaults['breadcrumbs_position'] );
		if ( $breadcrumbs_position == 'before' ) {
			add_action( 'rishi_after_container_wrap', 'rishi_breadcrumb_start', 10 );
		}
	}
endif;
add_action( 'wp', 'rishi_breadcrumb_init' );

if ( ! function_exists( 'rishi_excerpt_length' ) ) :
	/**
	 * Changes the default 55 character in excerpt
	 */
	function rishi_excerpt_length( $length ) {
		$excerpt_length = $length;

		if ( is_archive() ) {
			if ( is_author() ) {
				$key = 'author_post_structure';
			} else {
				$key = 'archive_post_meta';
			}
		} elseif ( is_search() ) {
			$key = 'search_post_structure';
		} else {
			$key = 'archive_blog_post_meta';
		}

		$blog_structure = get_theme_mod( $key, Defaults::blogpost_structure_defaults() );
		foreach ( $blog_structure as $structure ) {
			if ( $structure['enabled'] == true && $structure['id'] == 'excerpt' ) {
				$excerpt_length = $structure['excerpt_length'];
			}
		}
		return is_admin() ? $length : absint( $excerpt_length );
	}
endif;
add_filter( 'excerpt_length', 'rishi_excerpt_length', 999 );

if ( ! function_exists( 'rishi_comment_position' ) ) :
	/**
	 * Reorder Comment Section
	 */
	function rishi_comment_position() {
		$defaults                 = Defaults::get_layout_defaults();
		$ed_comment_below_content = get_theme_mod( 'ed_comment_below_content', $defaults['ed_comment_below_content'] );
		if ( $ed_comment_below_content == 'below' ) {
			add_action( 'rishi_after_post_loop', 'rishi_comment', 8 );
		} else {
			add_action( 'rishi_after_post_loop', 'rishi_comment', 40 );
		}
	}
endif;
add_action( 'wp', 'rishi_comment_position' );

if ( ! function_exists( 'rishi_visibility_for_devices' ) ) :
	/**
	 * Visibility Classes for devices
	 *
	 * @param array $key mod key
	 * @return string
	 */
	function rishi_visibility_for_devices( $key ) {

		$classes = array();

		if ( empty( $key['mobile'] ) && ! isset( $key['mobile'] ) ) {
			$classes[] = ' rishi-mobile-hide';
		}

		if ( empty( $key['tablet'] ) && ! isset( $key['tablet'] ) ) {
			$classes[] = ' rishi-tablet-hide';
		}

		if ( empty( $key['desktop'] ) && ! isset( $key['desktop'] ) ) {
			$classes[] = ' rishi-desktop-hide';
		}

		return implode( ' ', $classes );
	}
endif;

if ( ! function_exists( 'rishi_related_posts_position' ) ) :
	/**
	 * Reorder Comment Section
	 */
	function rishi_related_posts_position() {
		$defaults                 = Defaults::get_layout_defaults();
		$ed_related_after_comment = get_theme_mod( 'ed_related_after_comment', $defaults['ed_related_after_comment'] );
		$ed_comment_below_content = get_theme_mod( 'ed_comment_below_content', $defaults['ed_comment_below_content'] );
		if ( $ed_related_after_comment == 'yes' ) {
			if ( $ed_comment_below_content == 'below' ) {
				add_action( 'rishi_after_post_loop', 'rishi_related_posts', 9 );
			} else {
				add_action( 'rishi_after_post_loop', 'rishi_related_posts', 45 );
			}
		} else {
			if ( $ed_comment_below_content == 'below' ) {
				add_action( 'rishi_after_post_loop', 'rishi_related_posts', 7 );
			} else {
				add_action( 'rishi_after_post_loop', 'rishi_related_posts', 30 );
			}
		}
	}
endif;
add_action( 'wp', 'rishi_related_posts_position' );

if ( ! function_exists( 'rishi_print_schema' ) ) {
	/**
	 * Instance for Print schema.
	 */
	function rishi_print_schema( $params ) {

		$microdataObject = new \Rishi\Schema\Microdata();

		$enable_microdata = get_theme_mod( 'enable_microdata', 'yes' );
		if ( $enable_microdata && ( $enable_microdata === 'no' ) ) {
			return false;
		}

		return $microdataObject->print_schema( $params );
	}
}

if ( ! function_exists( 'rishi_handle_nav_menu_item' ) ) {
	/**
	 * Filter for menu.
	 */
	function rishi_handle_nav_menu_item( $item_output, $item ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', array_filter( $classes ) );

		$tag_name = ( wp_is_mobile() ) ? 'button' : 'span';
		$aria     = ( wp_is_mobile() ) ? 'aria-expanded=false' : '';

		if (
			strpos( $class_names, 'has-children' ) !== false
			||
			strpos( $class_names, 'has_children' ) !== false
		) {
			return $item_output . '<' . $tag_name . ' class="submenu-toggle" ' . esc_attr( $aria ) . '><svg xmlns="http://www.w3.org/2000/svg" width="10" height="5" viewBox="0 0 10 5"><path id="Polygon_5" data-name="Polygon 5" d="M5,0l5,5H0Z" transform="translate(10 5) rotate(180)"/></svg></' . $tag_name . '>';
		}

		return $item_output;
	}
}
add_filter( 'nav_menu_item_title', 'rishi_handle_nav_menu_item', 10, 4 );

if ( ! function_exists( 'rishi_bg_svg_css' ) ) {
	/**
	 * Add Dynamic SVG
	 *
	 * @return void
	 */
	function rishi_bg_svg_css() {
		echo "<style id='rishi-bg-svg-css' type='text/css' media='all'>"; ?>

		.comments-area ol.comment-list li .comment-body .text-holder .reply a:before {
			-webkit-mask-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%235081F5' viewBox='0 0 32 32'%3E%3Cpath d='M12.4,9.8c5.1,0.5,9.8,2.8,13.3,6.6c3.1,3.7,5.3,8.2,6.2,13c-4.3-6-10.8-9.1-19.6-9.1v7.3L0,15.1L12.4,2.7V9.8z'/%3E%3C/svg%3E");
			mask-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%235081F5' viewBox='0 0 32 32'%3E%3Cpath d='M12.4,9.8c5.1,0.5,9.8,2.8,13.3,6.6c3.1,3.7,5.3,8.2,6.2,13c-4.3-6-10.8-9.1-19.6-9.1v7.3L0,15.1L12.4,2.7V9.8z'/%3E%3C/svg%3E");
		}

		.comments-area ol.comment-list li.bypostauthor .comment-author:after {
			-webkit-mask-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%2300AB0B' viewBox='0 0 512 512'%3E%3Cpath d='M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z'/%3E%3C/svg%3E");
			mask-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%2300AB0B' viewBox='0 0 512 512'%3E%3Cpath d='M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z'/%3E%3C/svg%3E");
		}
		.wp-block-read-more:after {
			-webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(14.75 -1.999)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3290.464,377.064l4.366-4.367-4.366-4.367Z' transform='translate(-3291.713 -366.333)' fill='%235081f5'/%3E%3Cline id='Line_5' data-name='Line 5' x2='14.523' transform='translate(-14 6.499)' fill='none' stroke='%235081f5' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
			mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(14.75 -1.999)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3290.464,377.064l4.366-4.367-4.366-4.367Z' transform='translate(-3291.713 -366.333)' fill='%235081f5'/%3E%3Cline id='Line_5' data-name='Line 5' x2='14.523' transform='translate(-14 6.499)' fill='none' stroke='%235081f5' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
		}
		blockquote::before {
			-webkit-mask-image: url("data:image/svg+xml,%3Csvg width='112' height='112' viewBox='0 0 112 112' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30.3333 46.6666C29.2927 46.6666 28.294 46.8253 27.3 46.97C27.622 45.8873 27.9533 44.786 28.4853 43.7966C29.0173 42.3593 29.848 41.1133 30.674 39.858C31.3647 38.5 32.5827 37.5806 33.4787 36.4186C34.4167 35.2893 35.6953 34.538 36.708 33.6C37.702 32.62 39.004 32.13 40.04 31.4393C41.1227 30.8186 42.0653 30.1326 43.0733 29.806L45.5887 28.77L47.8007 27.8506L45.5373 18.8066L42.7513 19.4786C41.86 19.7026 40.7727 19.964 39.536 20.2766C38.2713 20.51 36.9227 21.1493 35.42 21.7326C33.936 22.3953 32.2187 22.8433 30.6227 23.9073C29.0173 24.9246 27.1647 25.774 25.5313 27.1366C23.9493 28.5413 22.0407 29.7593 20.6313 31.5466C19.0913 33.2173 17.57 34.972 16.3893 36.9693C15.022 38.8733 14.0933 40.964 13.1133 43.0313C12.2267 45.0986 11.5127 47.2126 10.9293 49.266C9.82334 53.382 9.32867 57.2926 9.13734 60.6386C8.97867 63.9893 9.07201 66.7753 9.26801 68.7913C9.33801 69.7433 9.46867 70.6673 9.56201 71.3066L9.67867 72.0906L9.80001 72.0626C10.63 75.9398 12.5408 79.5028 15.3112 82.3395C18.0816 85.1761 21.5985 87.1705 25.455 88.0918C29.3115 89.0132 33.3501 88.8239 37.1035 87.5459C40.857 86.2678 44.1719 83.9533 46.6648 80.87C49.1578 77.7866 50.7269 74.0605 51.1906 70.1227C51.6544 66.1848 50.9938 62.1962 49.2853 58.6181C47.5768 55.04 44.8902 52.0187 41.5364 49.9037C38.1825 47.7887 34.2984 46.6664 30.3333 46.6666V46.6666ZM81.6667 46.6666C80.626 46.6666 79.6273 46.8253 78.6333 46.97C78.9553 45.8873 79.2867 44.786 79.8187 43.7966C80.3507 42.3593 81.1813 41.1133 82.0073 39.858C82.698 38.5 83.916 37.5806 84.812 36.4186C85.75 35.2893 87.0287 34.538 88.0413 33.6C89.0353 32.62 90.3373 32.13 91.3733 31.4393C92.456 30.8186 93.3987 30.1326 94.4067 29.806L96.922 28.77L99.134 27.8506L96.8707 18.8066L94.0847 19.4786C93.1933 19.7026 92.106 19.964 90.8693 20.2766C89.6047 20.51 88.256 21.1493 86.7533 21.7326C85.274 22.4 83.552 22.8433 81.956 23.912C80.3507 24.9293 78.498 25.7786 76.8647 27.1413C75.2827 28.546 73.374 29.764 71.9647 31.5466C70.4247 33.2173 68.9033 34.972 67.7227 36.9693C66.3553 38.8733 65.4267 40.964 64.4467 43.0313C63.56 45.0986 62.846 47.2126 62.2627 49.266C61.1567 53.382 60.662 57.2926 60.4707 60.6386C60.312 63.9893 60.4053 66.7753 60.6013 68.7913C60.6713 69.7433 60.802 70.6673 60.8953 71.3066L61.012 72.0906L61.1333 72.0626C61.9634 75.9398 63.8741 79.5028 66.6445 82.3395C69.4149 85.1761 72.9318 87.1705 76.7883 88.0918C80.6448 89.0132 84.6834 88.8239 88.4369 87.5459C92.1903 86.2678 95.5052 83.9533 97.9982 80.87C100.491 77.7866 102.06 74.0605 102.524 70.1227C102.988 66.1848 102.327 62.1962 100.619 58.6181C98.9101 55.04 96.2236 52.0187 92.8697 49.9037C89.5158 47.7887 85.6317 46.6664 81.6667 46.6666V46.6666Z' fill='%232355D3' fill-opacity='1'/%3E%3C/svg%3E%0A");
			mask-image: url("data:image/svg+xml,%3Csvg width='112' height='112' viewBox='0 0 112 112' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30.3333 46.6666C29.2927 46.6666 28.294 46.8253 27.3 46.97C27.622 45.8873 27.9533 44.786 28.4853 43.7966C29.0173 42.3593 29.848 41.1133 30.674 39.858C31.3647 38.5 32.5827 37.5806 33.4787 36.4186C34.4167 35.2893 35.6953 34.538 36.708 33.6C37.702 32.62 39.004 32.13 40.04 31.4393C41.1227 30.8186 42.0653 30.1326 43.0733 29.806L45.5887 28.77L47.8007 27.8506L45.5373 18.8066L42.7513 19.4786C41.86 19.7026 40.7727 19.964 39.536 20.2766C38.2713 20.51 36.9227 21.1493 35.42 21.7326C33.936 22.3953 32.2187 22.8433 30.6227 23.9073C29.0173 24.9246 27.1647 25.774 25.5313 27.1366C23.9493 28.5413 22.0407 29.7593 20.6313 31.5466C19.0913 33.2173 17.57 34.972 16.3893 36.9693C15.022 38.8733 14.0933 40.964 13.1133 43.0313C12.2267 45.0986 11.5127 47.2126 10.9293 49.266C9.82334 53.382 9.32867 57.2926 9.13734 60.6386C8.97867 63.9893 9.07201 66.7753 9.26801 68.7913C9.33801 69.7433 9.46867 70.6673 9.56201 71.3066L9.67867 72.0906L9.80001 72.0626C10.63 75.9398 12.5408 79.5028 15.3112 82.3395C18.0816 85.1761 21.5985 87.1705 25.455 88.0918C29.3115 89.0132 33.3501 88.8239 37.1035 87.5459C40.857 86.2678 44.1719 83.9533 46.6648 80.87C49.1578 77.7866 50.7269 74.0605 51.1906 70.1227C51.6544 66.1848 50.9938 62.1962 49.2853 58.6181C47.5768 55.04 44.8902 52.0187 41.5364 49.9037C38.1825 47.7887 34.2984 46.6664 30.3333 46.6666V46.6666ZM81.6667 46.6666C80.626 46.6666 79.6273 46.8253 78.6333 46.97C78.9553 45.8873 79.2867 44.786 79.8187 43.7966C80.3507 42.3593 81.1813 41.1133 82.0073 39.858C82.698 38.5 83.916 37.5806 84.812 36.4186C85.75 35.2893 87.0287 34.538 88.0413 33.6C89.0353 32.62 90.3373 32.13 91.3733 31.4393C92.456 30.8186 93.3987 30.1326 94.4067 29.806L96.922 28.77L99.134 27.8506L96.8707 18.8066L94.0847 19.4786C93.1933 19.7026 92.106 19.964 90.8693 20.2766C89.6047 20.51 88.256 21.1493 86.7533 21.7326C85.274 22.4 83.552 22.8433 81.956 23.912C80.3507 24.9293 78.498 25.7786 76.8647 27.1413C75.2827 28.546 73.374 29.764 71.9647 31.5466C70.4247 33.2173 68.9033 34.972 67.7227 36.9693C66.3553 38.8733 65.4267 40.964 64.4467 43.0313C63.56 45.0986 62.846 47.2126 62.2627 49.266C61.1567 53.382 60.662 57.2926 60.4707 60.6386C60.312 63.9893 60.4053 66.7753 60.6013 68.7913C60.6713 69.7433 60.802 70.6673 60.8953 71.3066L61.012 72.0906L61.1333 72.0626C61.9634 75.9398 63.8741 79.5028 66.6445 82.3395C69.4149 85.1761 72.9318 87.1705 76.7883 88.0918C80.6448 89.0132 84.6834 88.8239 88.4369 87.5459C92.1903 86.2678 95.5052 83.9533 97.9982 80.87C100.491 77.7866 102.06 74.0605 102.524 70.1227C102.988 66.1848 102.327 62.1962 100.619 58.6181C98.9101 55.04 96.2236 52.0187 92.8697 49.9037C89.5158 47.7887 85.6317 46.6664 81.6667 46.6666V46.6666Z' fill='%232355D3' fill-opacity='1'/%3E%3C/svg%3E%0A");
		}
		.search-toggle-form .header-search-inner input[type="submit"] {
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='21.078' height='21.078' viewBox='0 0 21.078 21.078'%3E%3Cpath id='Path_24900' data-name='Path 24900' d='M12.867,21.756a8.82,8.82,0,0,0,5.476-1.9l5.224,5.224,1.512-1.512-5.224-5.224a8.864,8.864,0,1,0-6.988,3.414Zm0-15.648a6.77,6.77,0,1,1-6.759,6.759A6.771,6.771,0,0,1,12.867,6.108Z' transform='translate(-4 -4)' fill='%23fff'/%3E%3C/svg%3E%0A");
		}
		.post-author-wrap .view-all-auth:after {
			-webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(14.75 -1.999)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3290.464,377.064l4.366-4.367-4.366-4.367Z' transform='translate(-3291.713 -366.333)'/%3E%3Cline id='Line_5' data-name='Line 5' x2='14.523' transform='translate(-14 6.499)' fill='none' stroke='%23000' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
			mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(14.75 -1.999)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3290.464,377.064l4.366-4.367-4.366-4.367Z' transform='translate(-3291.713 -366.333)'/%3E%3Cline id='Line_5' data-name='Line 5' x2='14.523' transform='translate(-14 6.499)' fill='none' stroke='%23000' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
		}
		.post-nav-links .nav-holder.nav-previous .meta-nav a:before {
			-webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(3.117)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3294.831,377.064l-4.366-4.367,4.366-4.367Z' transform='translate(-3293.582 -368.331)' fill='%235081f5'/%3E%3Cline id='Line_5' data-name='Line 5' x1='14.523' transform='translate(-0.523 4.5)' fill='none' stroke='%235081f5' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
			mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(3.117)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3294.831,377.064l-4.366-4.367,4.366-4.367Z' transform='translate(-3293.582 -368.331)' fill='%235081f5'/%3E%3Cline id='Line_5' data-name='Line 5' x1='14.523' transform='translate(-0.523 4.5)' fill='none' stroke='%235081f5' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
		}
		.post-nav-links .nav-holder.nav-next .meta-nav a:after {
			-webkit-mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(14.75 -1.999)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3290.464,377.064l4.366-4.367-4.366-4.367Z' transform='translate(-3291.713 -366.333)' fill='%235081f5'/%3E%3Cline id='Line_5' data-name='Line 5' x2='14.523' transform='translate(-14 6.499)' fill='none' stroke='%235081f5' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
			mask-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.867' height='8.733' viewBox='0 0 17.867 8.733'%3E%3Cg id='Group_5838' data-name='Group 5838' transform='translate(14.75 -1.999)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3290.464,377.064l4.366-4.367-4.366-4.367Z' transform='translate(-3291.713 -366.333)' fill='%235081f5'/%3E%3Cline id='Line_5' data-name='Line 5' x2='14.523' transform='translate(-14 6.499)' fill='none' stroke='%235081f5' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A");
		}
		.error-search-again-wrapper .search-form input[type="submit"] {
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='25.86' height='26.434' viewBox='0 0 25.86 26.434'%3E%3Cpath id='icons8-search' d='M13.768,3a9.768,9.768,0,1,0,5.71,17.686l7.559,7.541,1.616-1.616-7.469-7.487A9.767,9.767,0,0,0,13.768,3Zm0,1.149a8.619,8.619,0,1,1-8.619,8.619A8.609,8.609,0,0,1,13.768,4.149Z' transform='translate(-3.5 -2.5)' fill='%23fff' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E");
		}
		.wp-block-search .wp-block-search__button {
			background-image: url('data:image/svg+xml; utf-8, <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="%23fff" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z"></path></svg>');
		}
		.wp-block-calendar .wp-calendar-nav .wp-calendar-nav-prev a::after,
		.wp-block-calendar .wp-calendar-nav .wp-calendar-nav-next a::after {
			background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16.333' height='13.244' viewBox='0 0 16.333 13.244'%3E%3Cg id='Group_763' data-name='Group 763' transform='translate(1.061 1.061)'%3E%3Cpath id='Path_5' data-name='Path 5' d='M3296.026,368.331l-5.561,5.561,5.561,5.561' transform='translate(-3290.464 -368.331)' fill='none' stroke='%231e1e1e' stroke-linecap='round' stroke-width='1.5'/%3E%3Cline id='Line_6' data-name='Line 6' x1='14.523' transform='translate(0 6)' fill='none' stroke='%231e1e1e' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A") no-repeat;
		}
		.wp-block-calendar .wp-calendar-nav .wp-calendar-nav-next a::after {
			background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='17.562' height='13.244' viewBox='0 0 17.562 13.244'%3E%3Cg id='Group_762' data-name='Group 762' transform='translate(0.75 1.061)'%3E%3Cpath id='Path_4' data-name='Path 4' d='M3290.465,368.331l5.561,5.561-5.561,5.561' transform='translate(-3280.275 -368.331)' fill='none' stroke='%231e1e1e' stroke-linecap='round' stroke-width='1.5'/%3E%3Cline id='Line_5' data-name='Line 5' x2='14.523' transform='translate(0 6)' fill='none' stroke='%231e1e1e' stroke-linecap='round' stroke-width='1.5'/%3E%3C/g%3E%3C/svg%3E%0A") no-repeat;
		}
		.search-result-wrapper .rishi-searchres-inner .search-form input[type="submit"] {
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='25.86' height='26.434' viewBox='0 0 25.86 26.434'%3E%3Cpath id='icons8-search' d='M13.768,3a9.768,9.768,0,1,0,5.71,17.686l7.559,7.541,1.616-1.616-7.469-7.487A9.767,9.767,0,0,0,13.768,3Zm0,1.149a8.619,8.619,0,1,1-8.619,8.619A8.609,8.609,0,0,1,13.768,4.149Z' transform='translate(-3.5 -2.5)' fill='%23fff' stroke='%23fff' stroke-width='1'/%3E%3C/svg%3E");
		}

		.pagination .nav-links .page-numbers:is(.next, .prev)::after {
			mask-image: url("data:image/svg+xml,%3Csvg width='11' height='18' viewBox='0 0 11 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.97769 0.209991L10.2277 8.45999L10.7422 8.99999L10.2269 9.53999L1.97694 17.79L0.896936 16.71L8.60994 8.99999L0.899938 1.28999L1.97769 0.209991Z' fill='%232B3034'/%3E%3C/svg%3E%0A");
			-webkit-mask-image: url("data:image/svg+xml,%3Csvg width='11' height='18' viewBox='0 0 11 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.97769 0.209991L10.2277 8.45999L10.7422 8.99999L10.2269 9.53999L1.97694 17.79L0.896936 16.71L8.60994 8.99999L0.899938 1.28999L1.97769 0.209991Z' fill='%232B3034'/%3E%3C/svg%3E%0A");
		}
		<?php
		echo '</style>';
	}
}
add_action( 'wp_head', 'rishi_bg_svg_css', 99 );

if ( ! function_exists( 'rishi_trim_css' ) ) {
	/**
	 * Parse CSS
	 *
	 * @todo looks like there is a duplicator function inside customizer that does the same thing
	 * @param array $css_output CSS.
	 *
	 * @return string $dynamic_css Generated CSS.
	 */
	function rishi_trim_css( $css_output = '' ) {

		if ( ! empty( $css_output ) ) {
			$css_output = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css_output );
			$css_output = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css_output );
			$css_output = str_replace( ', ', ',', $css_output );
		}
		return $css_output;
	}
}

if ( ! function_exists( 'rishi_parse_css' ) ) {
	/**
	 * Parse CSS
	 *
	 * @param array  $css_output Array of CSS.
	 * @param string $min_media Min Media breakpoint.
	 * @param string $max_media Max Media breakpoint.
	 *
	 * @return string $dynamic_css Generated CSS.
	 */
	function rishi_parse_css( $css_output = array(), $min_media = '', $max_media = '' ) {

		$parse_css = '';
		if ( is_array( $css_output ) && count( $css_output ) ) {
			foreach ( $css_output as $selector => $properties ) {
				if ( null === $properties ) {
					break;
				}

				if ( ! count( $properties ) ) {
					continue;
				}

				$temp_parse_css   = $selector . '{';
				$properties_added = 0;

				foreach ( $properties as $property => $value ) {
					if ( '' === $value && 0 !== $value ) {
						continue;
					}

					$properties_added++;
					$temp_parse_css .= $property . ':' . $value . ';';
				}

				$temp_parse_css .= '}';

				if ( $properties_added > 0 ) {
					$parse_css .= $temp_parse_css;
				}
			}

			if ( '' != $parse_css && ( '' !== $min_media || '' !== $max_media ) ) {

				$media_css       = '@media ';
				$min_media_css   = '';
				$max_media_css   = '';
				$media_separator = '';

				if ( '' !== $min_media ) {
					$min_media_css = '(min-width:' . $min_media . 'px)';
				}
				if ( '' !== $max_media ) {
					$max_media_css = '(max-width:' . $max_media . 'px)';
				}
				if ( '' !== $min_media && '' !== $max_media ) {
					$media_separator = ' and ';
				}

				$media_css .= $min_media_css . $media_separator . $max_media_css . '{' . $parse_css . '}';

				return $media_css;
			}
		}

		return $parse_css;
	}
}

if ( ! function_exists( 'rishi_flush_local_google_fonts' ) ) {
	/**
	 * Ajax Callback for flushing the local font
	 */
	function rishi_flush_local_google_fonts() {
		$WebFontLoader = new Rishi_WebFont_Loader();
		// deleting the fonts folder using ajax
		$WebFontLoader->delete_fonts_folder();
		die();
	}
}
add_action( 'wp_ajax_flush_local_google_fonts', 'rishi_flush_local_google_fonts' );
add_action( 'wp_ajax_nopriv_flush_local_google_fonts', 'rishi_flush_local_google_fonts' );
