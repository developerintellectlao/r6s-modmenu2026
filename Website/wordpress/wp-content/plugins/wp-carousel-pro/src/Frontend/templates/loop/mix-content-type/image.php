<?php
/**
 * Mix content carousel image
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/mix-content-type/image.php
 *
 * @package WP_Carousel_Pro
 */

$image_tag = '';
$image     = isset( $source['carousel_image_thumb'] ) ? $source['carousel_image_thumb'] : '';
if ( ! empty( $image['id'] ) ) {
	$image_data = get_post( $image['id'] );
	if ( ! $image_data ) {
		return;
	}
	$image_title         = $image_data->post_title;
	$image_caption       = $show_l_box_img_caption ? $image_data->post_excerpt : '';
	$image_description   = $image_data->post_content;
	$image_alt_titles    = $image_data->_wp_attachment_image_alt;
	$image_alt_title     = ! empty( $image_alt_titles ) ? $image_alt_titles : $image_title;
	$image_linking_meta  = wp_get_attachment_metadata( $image['id'] );
	$image_linking_urls  = isset( $image_linking_meta['image_meta'] ) ? $image_linking_meta['image_meta'] : '';
	$image_linking_url   = ! empty( $image_linking_urls['wpcplinking'] ) ? esc_url( $image_linking_urls['wpcplinking'] ) : '';
	$image_link_show     = isset( $source['carousel_image_link_action'] ) ? $source['carousel_image_link_action'] : '';
	$wcp_light_box_class = '';
	if ( 'img_lbox' === $image_link_show ) {
		$image_linking_url   = '#';
		$wcp_light_box_class = 'wcp-light-box-caption';
	}
	$the_image_title_attr = ' title="' . $image_title . '"';
	$image_title_attr     = 'true' == $show_image_title_attr ? $the_image_title_attr : '';
	$image_attr           = self::image_attr( $image['id'], $image_sizes, $is_variable_width, $carousel_mode, $image_width, $image_height, $image_crop );
	$image_tag            = self::image_tag( $lazy_load_image, $carousel_mode, $image_attr, $image_alt_title, $image_title_attr, $lazy_load_img, $wpcp_layout );
	$image_light_box_url  = wp_get_attachment_image_src( $image['id'], 'full' );
	$image_full_url       = $image_light_box_url[0];
}
?>
<div class="wpcp-single-item wpcp-mix-content">
	<?php
	if ( ! empty( $image['id'] ) ) {
		if ( 'img_lbox' === $image_link_show ) {
			wp_enqueue_script( 'wpcp-fancybox-popup' );
			wp_enqueue_script( 'wpcp-fancybox-config' );
			?>
			<a class="wcp-light-box" href="<?php echo esc_url( $image_light_box_url[0] ); ?>"  data-fancybox="wpcp_view" data-buttons='["<?php echo esc_attr( $l_box_zoom_button ); ?>","<?php echo esc_attr( $show_full_screen ); ?>","<?php echo esc_attr( $show_slideshow ); ?>","<?php echo esc_attr( $show_social_share ); ?>","<?php echo esc_attr( $show_download_button ); ?>","<?php echo esc_attr( $show_img_thumb ); ?>","<?php echo esc_attr( $l_box_close_button ); ?>"]' data-lightbox-gallery="group-<?php echo esc_attr( $post_id ); ?>" data-item-slug="<?php echo esc_attr( self::get_item_slug( $image_full_url ) ); ?>" data-caption="<?php echo esc_html( $image_caption ); ?>">
			<?php if ( 'none' !== $l_box_icon_style && 'with_overlay' !== $wpcp_post_detail && ! $is_mobile ) : ?>
			<div class="wpcp_icon_overlay l_box-icon-position-<?php echo esc_attr( $l_box_icon_position ); ?>">
				<?php echo $l_box_icon; ?>
			</div>
			<?php endif; ?>
		<?php } elseif ( 'img_link' === $image_link_show ) { ?>
			<a href="<?php echo esc_url( $image_linking_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" >
			<?php
		} echo wp_kses_post( $image_tag );
		if ( 'none' !== $image_link_show ) {
			?>
				</a>
			<?php
		}
		?>
	<?php } ?>
	<div class="wpcp-single-content">
		<?php echo do_shortcode( wpautop( $wp_embed->autoembed( $mix_content_description ) ) ); ?>
	</div>
</div>
