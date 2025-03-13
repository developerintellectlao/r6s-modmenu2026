<?php
/**
 * Rishi Woocommerce hooks and functions.
 *
 * @link https://docs.woothemes.com/document/third-party-custom-theme-compatibility/
 *
 * @package Rishi
 */

use Rishi\Customizer\Helpers\Basic;
/**
 * Woocommerce related hooks
*/
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0 );
remove_action( 'woocommerce_before_main_content','woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content','woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar','woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_shop_loop','woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop','woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop','woocommerce_pagination', 10 );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

if ( ! function_exists( 'rishi_woo_header_actions' ) ) :
	/**
	 * All the Woo Actions.
	 *
	 * @return void
	 */
	function rishi_woo_header_actions(){
		add_action( 'woocommerce_before_main_content','rishi_header_section_wrapper_start', 40 );
		add_action( 'woocommerce_before_shop_loop','rishi_header_section_wrap_start', 9 );
		add_action( 'woocommerce_before_shop_loop','woocommerce_output_all_notices', 30 );

		add_action( 'woocommerce_before_shop_loop','rishi_header_section_wrap_end', 50 );
		add_action( 'woocommerce_checkout_before_order_review_heading','rishi_woocommerce_checkout_before_order_review_heading_start' );
		add_action( 'woocommerce_checkout_after_order_review','rishi_woocommerce_checkout_after_order_review_end' );
		add_action( 'woocommerce_after_shop_loop','rishi_woocommerce_pagination',11 );
        add_action('woocommerce_shop_loop_item_title', 'rishi_product_title', 10);

        //Ajax header cart
        add_filter('woocommerce_add_to_cart_fragments', function(){
            $header_cart   = rishi_customizer()->header_builder->get_elements()->get_items()['cart'];
            $_cartinstance = new $header_cart();

            ob_start();
            $_cartinstance->render();
            $mini_cart = ob_get_clean();

            return array(
                'div.rishi-header-cart' => '<div class="rishi-header-cart">' . $mini_cart . '</div>'
            );
        });
	}
endif;
add_action( 'init','rishi_woo_header_actions',11 );

/**
 * Add Woo customizer sections under WooCommerce Settings
 *
 * @param object $wp_customize
 * @return void
 */
function rishi_woo_customize_register( $wp_customize ){
	$wp_customize->get_control( 'woocommerce_checkout_company_field' )->section  = "woo_checkout";
	$wp_customize->get_control( 'woocommerce_checkout_address_2_field' )->section  = "woo_checkout";
	$wp_customize->get_control( 'woocommerce_checkout_phone_field' )->section  = "woo_checkout";
	$wp_customize->get_control( 'woocommerce_checkout_highlight_required_fields' )->section  = "woo_checkout";
	$wp_customize->get_control( 'woocommerce_checkout_terms_and_conditions_checkbox_text' )->section  = "woo_checkout";
    
	//Shop page.
	$wp_customize->get_control( 'woocommerce_shop_page_display' )->section  = "woocommerce_shop";
	$wp_customize->get_control( 'woocommerce_category_archive_display' )->section  = "woocommerce_shop";
	$wp_customize->get_control( 'woocommerce_default_catalog_orderby' )->section  = "woocommerce_shop";
	$wp_customize->get_control( 'woocommerce_shop_page_display' )->priority  = 100;
	$wp_customize->get_control( 'woocommerce_category_archive_display' )->priority  = 100;
	$wp_customize->get_control( 'woocommerce_default_catalog_orderby' )->priority  = 100;
    
    //Store Notice
    $wp_customize->get_control( 'woocommerce_demo_store_notice' )->section  = "woocommerce_store";
    $wp_customize->get_control( 'woocommerce_demo_store_notice' )->priority  = 100;
	$wp_customize->get_control( 'woocommerce_demo_store' )->section  = "woocommerce_store";
	$wp_customize->get_control( 'woocommerce_demo_store' )->priority  = 100;

    //Add settings Privacy Policy page and Terms and Conditions Page if user can manage privacy options
    if ( current_user_can( 'manage_privacy_options' ) ) {
        $choose_pages = array(
            'wp_page_for_privacy_policy' => __( 'Privacy policy', 'rishi' ),
            'woocommerce_terms_page_id'  => __( 'Terms and conditions', 'rishi' ),
        );
    } else {
        $choose_pages = array(
            'woocommerce_terms_page_id' => __( 'Terms and conditions', 'rishi' ),
        );
    }
    $pages        = get_pages(
        array(
            'post_type'   => 'page',
            'post_status' => array('publish','private','draft'),
            'child_of'    => 0,
            'parent'      => -1,
            'exclude'     => array(
                wc_get_page_id( 'cart' ),
                wc_get_page_id( 'checkout' ),
                wc_get_page_id( 'myaccount' ),
            ),
            'sort_order'  => 'asc',
            'sort_column' => 'post_title',
        )
    );
    $page_choices = array( '' => __( 'No page set', 'rishi' ) ) + array_combine( array_map( 'strval', wp_list_pluck( $pages, 'ID' ) ), wp_list_pluck( $pages, 'post_title' ) );

    foreach ( $choose_pages as $id => $name ) {

        $wp_customize->add_setting(
            $id,
            array(
                'default'           => '',
                'type'              => 'option',
                'capability'        => 'manage_woocommerce',
                'sanitize_callback' => 'rishi_sanitize_select',
            )
        );
        $wp_customize->add_control(
            $id,
            array(
                /* Translators: %s: page name. */
                'label'    => sprintf( __( '%s page', 'rishi' ), $name ),
                'section'  => 'woo_checkout',
                'settings' => $id,
                'type'     => 'select',
                'choices'  => $page_choices,
            )
        );
    }

	$wp_customize->get_control( 'woocommerce_checkout_privacy_policy_text' )->section  = "woo_checkout";
}
add_action( 'customize_register', 'rishi_woo_customize_register', 99 );

/**
 * Declare Woocommerce Support
*/
function rishi_woocommerce_support() {
    global $woocommerce;

    add_theme_support( 'woocommerce' );

    if( version_compare( $woocommerce->version, '3.0', ">=" ) ) {
		if ( get_theme_mod('gallery_ed_zoom_effect', 'no') === 'yes' ) add_theme_support( 'wc-product-gallery-zoom' );
		if ( get_theme_mod('gallery_ed_lightbox', 'no') === 'yes' ) add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
    }
}
add_action( 'after_setup_theme', 'rishi_woocommerce_support');

if ( ! function_exists( 'rishi_before_single_product_summary' ) ) :
	/**
	 * Rishi Single Product Summary Before Hook.
	 *
	 * @return void
	 */
	function rishi_before_single_product_summary(){
		echo '<div class="product-entry-wrapper">';
	}
endif;
add_action( 'woocommerce_before_single_product_summary','rishi_before_single_product_summary',1 );

if ( ! function_exists( 'rishi_after_single_product_summary' ) ) :
	/**
	 * Rishi Single Product Summary After Hook.
	 *
	 * @return void
	 */
	function rishi_after_single_product_summary(){
		echo '</div><!-- #product-entry-wrapper -->';
	}
endif;
add_action( 'woocommerce_after_single_product_summary','rishi_after_single_product_summary',1 );

/**
 * Woocommerce Sidebar
*/
function rishi_wc_widgets_init(){
    register_sidebar(
		array(
			'name'          => esc_html__( 'Shop Sidebar', 'rishi' ),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__( 'Sidebar displaying only in woocommerce pages.', 'rishi' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'rishi_wc_widgets_init' );

if ( ! function_exists( 'rishi_wc_wrapper' ) ) :
	/**
	 * Before Content
	 * Wraps all WooCommerce content in wrappers which match the theme markup
	 *
	 * @return void
	 */
	function rishi_wc_wrapper(){ ?>
		<main id="primary" class="site-main">
		<?php
	}
endif;
add_action( 'woocommerce_before_main_content', 'rishi_wc_wrapper' );

if ( ! function_exists( 'rishi_wc_wrapper_end' ) ) :
	/**
	 * After Content
 	 * Closes the wrapping divs
	 *
	 * @return void
	 */
	function rishi_wc_wrapper_end(){ ?>
		</main>
		<?php
		do_action( 'rishi_wo_sidebar' );
	}
endif;
add_action( 'woocommerce_after_main_content', 'rishi_wc_wrapper_end' );

if ( ! function_exists( 'rishi_wc_sidebar_cb' ) ) {
    /**
     * Callback function for Shop sidebar
    */
    function rishi_wc_sidebar_cb(){
        $sidebar = rishi_sidebar();

        if ( ! $sidebar ) {
            return;
        } ?>

        <aside id="secondary" class="widget-area" <?php echo rishi_print_schema('sidebar'); ?>>
            <?php dynamic_sidebar( $sidebar ); ?>
        </aside>
        <?php
    }
}
add_action( 'rishi_wo_sidebar', 'rishi_wc_sidebar_cb' );

if ( ! function_exists( 'rishi_header_section_wrap_start' ) ) {
	/**
     * Wrapper Start for the Main Content
    */
    function rishi_header_section_wrap_start(){ ?>
        <div class="woowrapper">
        <?php
    }
}
if ( ! function_exists( 'rishi_header_section_wrap_end' ) ) {
	/**
     * Wrapper End for the Main Content
    */
    function rishi_header_section_wrap_end(){ ?>
        </div><!-- .woowrapper -->
        <?php
    }
}

function rishi_woocommerce_pagination_args( $args ){
    $args['prev_text'] = esc_html__( 'Prev', 'rishi' );
    $args['next_text'] = esc_html__( 'Next', 'rishi' );
    return $args;
}
add_filter( 'woocommerce_pagination_args','rishi_woocommerce_pagination_args' );

if ( ! function_exists( 'rishi_header_section_wrapper_start' ) ){
	/**
     * Main wrapper starts
    */
    function rishi_header_section_wrapper_start(){
        $shop_cards_type = get_theme_mod( 'shop_cards_type', 'normal' );
        $badge_design = get_theme_mod( 'shop_cards_sales_badge_design', 'circle' );

		$class = "wholewrapper";

		if( $shop_cards_type ){
			$class .= " design-" .$shop_cards_type;
		}

		if( $badge_design ){
			$class .= " badge-" .$badge_design;
		}

        if( is_product() ){
            $class .= " product-tab-" .apply_filters( 'rishi_single_product_additional_class', '' );
        }

        ?>
        <div class="<?php echo esc_attr( $class ); ?>">
        <?php
    }
}
if ( ! function_exists( 'rishi_header_section_wrapper_end' ) ) {
	/**
     * Main wrapper end
    */
    function rishi_header_section_wrapper_end(){ ?>
        </div><!-- .wholewrapper -->
        <?php
    }
}
if ( ! function_exists( 'rishi_woocommerce_checkout_before_order_review_heading_start' ) ) {
	/**
     * Order Review Heading Start
    */
    function rishi_woocommerce_checkout_before_order_review_heading_start(){ ?>
        <div class="form-order-wrapper">
        <?php
    }
}
if ( ! function_exists( 'rishi_woocommerce_checkout_after_order_review_end' ) ) {
	/**
     * Order Review Heading end
    */
    function rishi_woocommerce_checkout_after_order_review_end(){ ?>
        </div><!-- .form-order-wrapper -->
        <?php
    }
}
/**
 * Removes the "shop" title on the main shop page
*/
add_filter( 'woocommerce_show_page_title' , '__return_false' );

if ( ! function_exists( 'rishi_archive_woocommerce_template_loop_restructure' ) ) {
    function rishi_archive_woocommerce_template_loop_restructure(){

        if ( get_theme_mod( 'has_star_rating', 'yes' ) !== 'yes'
        || ( is_single() && is_product() && get_theme_mod( 'rp_has_star_rating', 'yes' ) === 'no' ) ){
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
        }else{
            add_action( 'woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating', 5 );
        }
        if ( get_theme_mod( 'has_sale_badge', 'yes' ) !== 'yes'||
        ( is_single() && is_product() && get_theme_mod( 'rp_has_sale_badge', 'no' ) === 'no' ) ) {
            remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
        }else{
            add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
        }

        if ( get_theme_mod( 'has_shop_sort', 'yes' ) !== 'yes' ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		}else{
			add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
        }

		if ( get_theme_mod( 'has_shop_results_count', 'yes' ) !== 'yes') {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		}else{
            add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
        }

        if( get_theme_mod( 'woo_ed_upsell_tab', 'no' ) === 'yes' ){
            remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
            add_filter( 'woocommerce_product_tabs', 'rishi_woo_add_new_products_tab' );
        }
    }
}
add_action( 'wp','rishi_archive_woocommerce_template_loop_restructure',9999 );

if ( ! function_exists( 'rishi_woo_add_new_products_tab' ) ) {
    function rishi_woo_add_new_products_tab( $tabs ){
        $upsell_label = get_theme_mod( 'woo_upsell_tab_label', __( 'Upsell', 'rishi' ) );
        $tabs['rishi_upsell_products'] =
            array(
                'title'       => $upsell_label,
                'priority'    => 50,
                'callback'    => 'rishi_woo_new_product_tab_content'
            );
        return $tabs;
    }
}

if ( ! function_exists( 'rishi_woo_new_product_tab_content' ) ) {
    function rishi_woo_new_product_tab_content(){
        woocommerce_upsell_display();
    }
}

if (! function_exists( 'rishi_single_woocommerce_product_restructure' ) ) {
    function rishi_single_woocommerce_product_restructure(){

        if ( get_theme_mod('has_product_single_rating', 'yes') === 'no' ) {
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
        }else{
            add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
        }

        if (get_theme_mod('has_product_single_meta', 'yes') === 'no') {
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        }else{
            add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        }
    }
}
add_action( 'wp','rishi_single_woocommerce_product_restructure',99999 );

/**
 * Shop Page Row and Columns modification
 * @return integer
 */
add_filter( 'loop_shop_columns', function(){
    $cols = absint( get_theme_mod( 'woocommerce_cols', 4 ) );
    return ! empty( $cols ) ? absint( $cols ) : "4";
}, 999);

function rishi_set_rows_for_shop_page() {
	$rows = absint( get_theme_mod( 'woocommerce_rows', 4 ) );
    if ( get_option( 'woocommerce_catalog_rows', $rows ) ) {
        update_option( 'woocommerce_catalog_rows', $rows );
    }

}
add_action( 'init','rishi_set_rows_for_shop_page',99 );
/**
 * Related Product args
 *
 * @param array $args
 * @return void
 */
function rishi_output_related_product_args( $args ){
	$no_of_posts     = get_theme_mod( 'woo_single_no_of_posts', 3 );
    $no_of_posts_row = get_theme_mod( 'woo_single_no_of_posts_row',4 );

    if( $no_of_posts ){
        $args['posts_per_page'] = absint( $no_of_posts );
    }
    if( $no_of_posts_row ){
        $args['columns'] = absint( $no_of_posts_row );
    }
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'rishi_output_related_product_args' );

function rishi_upsell_display_args( $args ){
	$no_of_upsell     = get_theme_mod( 'woo_single_no_of_upsell', 24);
	$no_of_upsell_row = get_theme_mod( 'woo_single_no_of_upsell_row', 4 );
    
    if( $no_of_upsell ){
        $args['posts_per_page'] = absint( $no_of_upsell );
    }
    if( $no_of_upsell_row ){
        $args['columns'] = absint( $no_of_upsell_row );
    }
    return $args;
}
add_filter( 'woocommerce_upsell_display_args', 'rishi_upsell_display_args' );

/**
 * Overriding the default pagination
 *
 * @return void
 */
function rishi_woocommerce_pagination() {

    $woo_post_navigation = get_theme_mod( 'woo_post_navigation','numbered' );

    if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
        return;
    }

    if( $woo_post_navigation == 'numbered' ){
		$args = array(
		'total'   => wc_get_loop_prop( 'total_pages' ),
		'current' => wc_get_loop_prop( 'current_page' ),
		'base'    => esc_url_raw( add_query_arg( 'product-page', '%#%', false ) ),
		'format'  => '?product-page=%#%',
	);

	if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
		$args['format'] = '';
		$args['base']   = esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
	}

	wc_get_template( 'loop/pagination.php', $args );
	}
}


if( ! function_exists( 'rishi_get_category_woo_cat_list' ) ) :
	/**
	 * Get Category Woo Cat list
	 */
    function rishi_get_category_woo_cat_list( $sep='', $before='',$after='' ){
        global $product;
        $terms = get_the_terms( $product->get_id(), 'product_cat' );

        if ( is_wp_error( $terms ) ) {
            return $terms;
        }
        if ( empty( $terms ) ) {
            return false;
        }
        $links = array();
        foreach( $terms as $term ) {
            $link = get_term_link( $term, 'product_cat' );
            if ( is_wp_error( $link ) ) {
                return $link;
            }
            $links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
        }
        return $before . implode( $sep, $links ) . $after;
    }
endif;

if( ! function_exists( 'rishi_shop_loop_item_title' ) ) :
	/**
	 * Shop Loop Item title
	 */
	function rishi_shop_loop_item_title(){
		$get_categories = get_theme_mod( 'has_woo_category', 'yes' );
        global $product;

		echo '<div class="caption-content-wrapper">';
		if( $get_categories === 'yes' || 
        ( is_single() && is_product() && ( get_theme_mod( 'rp_has_single_category', 'no' ) === 'yes' ) ) ) echo rishi_get_category_woo_cat_list( '','<div class="cat-wrap">','</div>' );
        echo '<a href="'. esc_url( $product->get_permalink() ) .'" >';
    }
endif;
add_action( 'woocommerce_shop_loop_item_title','rishi_shop_loop_item_title',9 );

function rishi_product_title() {
    echo '<a href="' . esc_url( get_the_permalink() ) .'"><h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . get_the_title() . '</h2></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_action( 'woocommerce_shop_loop_item_title',function(){
},11 );

if( ! function_exists( 'rishi_after_shop_loop_item' ) ) :
	/**
	 * Shop Loop Item title
	 */
	function rishi_after_shop_loop_item(){
		echo '</div>';
	}
endif;
add_action( 'woocommerce_after_shop_loop_item','rishi_after_shop_loop_item',11 );

if ( ! function_exists( 'rishi_woo_single_post_class' ) ) :
    /**
     * Gallery Image position
     */
    function rishi_woo_single_post_class( $classes, $product ) {

        if ( ! is_product() ) {
            return $classes;
        }

        if(is_single() && is_product() ){
            $shop_cards_type = get_theme_mod( 'rp_cards_type', 'normal' );

            $classes[] = $shop_cards_type;
        }

        if ( count($product->get_gallery_image_ids()) > 0 ) {
            if ( get_theme_mod('gallery_thumbnail_position', 'horizontal') === 'vertical' ) {
                $classes[] = 'thumbs-left';
            } else {
                $classes[] = 'thumbs-bottom';
            }
        }

        return $classes;

    }
endif;
add_filter( 'woocommerce_post_class', 'rishi_woo_single_post_class', 999, 2 );

if ( ! function_exists( 'rishi_sale_badge_text' ) ) :
    /**
     * Adds Sales Badge Section.
     */
    function rishi_sale_badge_text() {
        $ed_salesbadge 		= get_theme_mod( 'has_sale_badge', 'yes' );
        $sales_badge_title 	= get_theme_mod( 'sales_badge_title', __( 'Sale!', 'rishi' ) );

        if( $ed_salesbadge == 'yes' ) {
            $value = '<span class="onsale">' . esc_html( $sales_badge_title ) . '</span>';
        }

        return $value;
    }
endif;
add_filter( 'woocommerce_sale_flash', 'rishi_sale_badge_text', 10, 3 );

add_action( 'woocommerce_before_cart',function(){
    echo '<div class="rishi-cart-wrapper">';
});
add_action( 'woocommerce_after_cart',function(){
        echo '<div class="products">';
            woocommerce_cross_sell_display(null, 4);
        echo '</div>';
    echo '</div>';
});

if( ! function_exists( 'rishi_get_related_products_info' ) ) :
	/**
	 * Related Products Title
	 */
	function rishi_get_related_products_info(){
		$defaults       = \Rishi\Customizer\Helpers\Defaults::get_layout_defaults();
		$product_title   = get_theme_mod('single_related_products', $defaults['single_related_products']);
		return $product_title;
	}
endif;
add_filter( 'woocommerce_product_related_products_heading', 'rishi_get_related_products_info' );

function rishi_woocommerce_demo_store( $notice ){
    $position = get_theme_mod('store_notice_position', 'bottom' );
    return Basic::add_html_content($notice, 'div', ['class' => 'woo-notice demo-' . esc_attr($position)]);
}
add_filter( 'woocommerce_demo_store', 'rishi_woocommerce_demo_store' );