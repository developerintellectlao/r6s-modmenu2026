<?php
/**
 * Product image
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/product-type/image.php
 *
 * @package WP_Carousel_Pro
 */

// Product image.
if ( $show_product_image ) {
	if ( has_post_thumbnail() && $show_product_image ) {
		$product_thumb_id       = get_post_thumbnail_id();
		$show_product_badge     = apply_filters( 'wpcp_show_product_bage', true );
		$product_thumb_alt_text = get_post_meta( $product_thumb_id, '_wp_attachment_image_alt', true );
		$image_light_box_url    = wp_get_attachment_image_src( $product_thumb_id, 'full' );
		$image_attr             = self::image_attr( $product_thumb_id, $image_sizes, $is_variable_width, $carousel_mode, $image_width, $image_height, $image_crop, $show_2x_image );
		$the_image_title_attr   = ' title="' . get_the_title() . '"';
		$image_title_attr       = 'true' === $show_image_title_attr ? $the_image_title_attr : '';

		if ( $image_attr['src'] ) {
			$wpcp_product_thumb = self::image_tag( $lazy_load_image, $carousel_mode, $image_attr, $product_thumb_alt_text, $image_title_attr, $lazy_load_img, $wpcp_layout );
			if ( $thumbnail_slider ) {
				?>
		<div class="<?php echo esc_attr( $grid_column ); ?>">
			<div class="wpcp-slide-image">
				<?php if ( $product->is_on_sale() && $show_product_badge ) { ?>
					<span class="wpcp-on-sale"> <?php echo esc_html__( 'On Sale', 'wp-carousel-pro' ); ?> </span>
				<?php } ?>
				<?php echo $wpcp_product_thumb; ?>
			</div>
		</div>
				<?php
			} else {
				if ( $light_box ) {
					?>
		<div class="wpcp-slide-image">
					<?php if ( $product->is_on_sale() && $show_product_badge ) { ?>
				<span class="wpcp-on-sale"> <?php echo esc_html__( 'On Sale', 'wp-carousel-pro' ); ?> </span>
			<?php } ?>
			<a href="<?php echo esc_url( $image_light_box_url[0] ); ?>" class="wcp-light-box" data-fancybox="wpcp_view" data-buttons='["zoom","slideShow","fullScreen","share","download","close"]' data-lightbox-gallery="group-<?php echo esc_attr( $post_id ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $image_light_box_url[0] ) ); ?>" title="<?php echo esc_html( get_the_title() ); ?>">
					<?php echo $wpcp_product_thumb; ?>
			</a>
		</div>
				<?php } else { ?>
		<div class="wpcp-slide-image">
					<?php if ( $product->is_on_sale() && $show_product_badge ) { ?>
				<span class="wpcp-on-sale"> <?php echo esc_html__( 'On Sale', 'wp-carousel-pro' ); ?> </span>
			<?php } ?>
			<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo $wpcp_product_thumb; ?></a>
		</div>
					<?php
				}
			}
		}
	} else {
		if ( $thumbnail_slider || 'thumbnails-slider' === $wpcp_layout ) {
			?>
			<div class="<?php echo esc_attr( $grid_column ); ?>">
				<div class="wpcp-slide-image">
					<img src="<?php echo esc_attr( WPCAROUSEL_URL ); ?>Frontend/img/placeholder.png" width="600" height="450" alt="placeholder">
				</div>
			</div>
					<?php
		}
	}
}
