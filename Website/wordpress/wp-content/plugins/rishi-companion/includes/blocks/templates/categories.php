<?php
/**
 * Dynamic Blocks rendering.
 *
 * @package Rishi_Companion
 */

// Define expected parameters.
$expected_params = array(
	'categoriesLabel'         => __( 'Categories', 'rishi-companion' ),
	'categoriesTitleSelector' => 'h2',
	'layoutStyle'             => 'layout-type-1',
	'showPostCount'           => true,
	'category_selected'       => array(),
	'backgroundColor'         => 'palette-color-1',
	'textColor'               => 'palette-color-5',
);

// Merge expected parameters with attributes.
$data = rishi_companion_list( array_keys( $expected_params ), array_merge( $expected_params, $attributes ) );

// Extract data.
list( $label, $title_selector, $style, $show_post_count, $category_selected, $background_color, $color ) = $data;

$args = '';

// Check if category selected is not empty.
if ( ! empty( $category_selected ) ) {
	$args            = array(
		'hide_empty' => false,
	);
	$args['include'] = array_map(
		function( $selected ) {
			return $selected['value'];
		},
		$category_selected
	);
}

// Get categories.
$_categories = get_categories( $args );

// Check if style is set.
$style = isset( $style['value'] ) ? $style['value'] : $style;

// Define category classes.
$category_classes = array(
	'category-post-count',
	"has-{$background_color}-background-color",
	"has-{$color}-color",
	'has-text-color',
	'has-background',
);
?>
<section id="rishi_categories" class="rishi_sidebar_widget_categories">
	<?php
	// Check if label is not empty and print it.
	! empty( $label ) && printf( '<' . esc_html( $title_selector ) . ' class="widget-title" itemProp="name"><span>%s</span></' . esc_html( $title_selector ) . '>', esc_html( $label ) );

	// Check if categories exist.
	if ( isset( $_categories[0] ) || count( $_categories ) > 0 ) {
		printf( '<ul class="%s">', esc_attr( $style ) );

		// Loop through categories.
		foreach ( $_categories as $_category ) {
			$image_id  = get_term_meta( $_category->term_id, 'category-image-id' );
			$image_cat = isset( $image_id[0] ) ? 'background-image: url(' . esc_url( wp_get_attachment_url( $image_id[0] ) ) . ');' : '';
			$class     = isset( $image_id[0] ) ? '' : 'class=fallback-img';

			printf(
				'<li><a href="%1$s"  ' . esc_html( $class ) . ' style="%2$s">',
				esc_url( get_category_link( $_category->term_id ) ),
				esc_attr( $image_cat )
			);

			// For Layout 1.
			'layout-type-1' === $style && printf(
				/* translators: 1: category name, 2: post count */
				'<span class="category-name">%1$s</span>%2$s',
				esc_html( $_category->name ),
				$show_post_count && $_category->count ? sprintf(
					/* translators: 1: post count */
					'<span class="category-post-count rishi_sidebar_widget_categories ul li %2$s" style="categoryStyle">%1$s</span>',
					sprintf(
						/* translators: %d is a placeholder for the number of posts within a category */
						esc_html( _n( '%d Post', '%d Posts', (int) $_category->count, 'rishi-companion' ) ),
						(int) $_category->count
					),
					esc_attr( implode( ' ', $category_classes ) )
				) : ''
			);

			// For Layout 2.
			'layout-type-2' === $style && printf(
				/* translators: 1: category name, 2: post count */
				'<div class="category-content"><span class="category-name">%1$s</span>%2$s</div>',
				esc_html( $_category->name ),
				$show_post_count && $_category->count ? sprintf(
					/* translators: 1: post count */
					'<span class="category-post-count rishi_sidebar_widget_categories ul li %2$s" style="categoryStyle">%1$s</span>',
					sprintf(
						/* translators: %d is a placeholder for the number of posts within a category */
						esc_html( _n( '%d Post', '%d Posts', (int) $_category->count, 'rishi-companion' ) ),
						(int) $_category->count
					),
					esc_attr( implode( ' ', $category_classes ) )
				) : ''
			);
			echo '</a></li>';
		}
		echo '</ul>';
	}
	?>
</section>
