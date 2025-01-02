<?php
/**
 * Pagination.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/pagination.php
 *
 * @package WP_Carousel_Pro
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$show_load_more_counter_message = isset( $shortcode_data['show_counter_message'] ) ? $shortcode_data['show_counter_message'] : false;
if ( 'content-carousel' === $carousel_type ) {
	$total_items = $content_sources;
	$text        = __( 'contents', 'wp-carousel-pro' );
} elseif ( 'image-carousel' === $carousel_type ) {
	$total_items = $attachments;
	$text        = __( 'images', 'wp-carousel-pro' );
} elseif ( 'video-carousel' === $carousel_type ) {
	$total_items = $video_sources;
	$text        = __( 'videos', 'wp-carousel-pro' );
} elseif ( 'audio-carousel' === $carousel_type ) {
	$total_items = $audio_sources;
	$text        = __( 'audio', 'wp-carousel-pro' );
} elseif ( 'mix-content' === $carousel_type ) {
	$total_items = $mix_sources;
	$text        = __( 'contents', 'wp-carousel-pro' );
} elseif ( 'external-carousel' === $carousel_type ) {
	$total_items = $all_rss_data_count;
	$text        = __( 'feeds', 'wp-carousel-pro' );
} else {
	// Load More counter items.
	// $posts_found = $wpcp_query->found_posts;
	$total_items = ( (int) $limit > 0 && $posts_found > (int) $limit ) ? (int) $limit : $posts_found;
	$text        = 'post-carousel' === $carousel_type ? __( 'posts', 'wp-carousel-pro' ) : __( 'products', 'wp-carousel-pro' );
}
$per_views                  = ( (int) $per_views > (int) $total_items ) ? (int) $total_items : (int) $per_views;
$load_more_post             = (int) $total_items - (int) $per_views;
$pagination_counter_message = '<div class="notice-load-more-post"><span class= "notice">' . __( 'You have viewed', 'wp-carousel-pro' ) . ' <span class="load-more-items-have">' . $per_views . '</span> ' . __( 'of', 'wp-carousel-pro' ) . ' ' . $total_items . ' ' . $text . '</span></div>';

if ( ( 'post-carousel' === $carousel_type || 'product-carousel' === $carousel_type ) && ! empty( $total_pages ) && ( $total_pages > 1 ) ) {
	?>
<div class="wpcpro-post-pagination <?php echo esc_attr( $post_pagination_type ); ?>" data-pagi="<?php echo esc_attr( $post_pagination_type ); ?>">
	<div class="wpcpro-post-pagination-number">
		<?php echo wp_kses_post( $page_links ); ?>
	</div>
	<?php
	if ( 'normal' !== $post_pagination_type && 'ajax_number' !== $post_pagination_type ) {
		?>
	<div class="wpcpro-post-load-more" data-pagi="<?php echo esc_attr( $pagination_type ); ?>" data-text="<?php echo esc_html( $end_content_text ); ?>" data-items-have=" <?php echo esc_html( $load_more_post ); ?>" data-per-page="<?php echo esc_html( $post_per_click ); ?>">
		<?php
		if ( 'load_more_btn' === $post_pagination_type && $show_load_more_counter_message ) {
			echo wp_kses_post( $pagination_counter_message );
		}
		?>
		<button wpcp-processing="0" data-id="<?php echo esc_attr( $post_id ); ?>"  data-total="<?php echo esc_attr( $total_pages ); ?>" data-page="1"><?php echo esc_html( $load_more_label ); ?> <span class="load-more-count"> (<?php echo esc_html( $load_more_post ); ?>)</span></button>
	</div>
	<?php } ?>
</div>
	<?php
} elseif ( ! empty( $total_page ) && $total_page > 1 ) {
	if ( 'ajax_number' === $pagination_type ) {
		?>
		<div class="wpcpro-post-pagination ajax_number content-ajax-pagination" data-id="<?php echo esc_attr( $post_id ); ?>" data-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-nonce="<?php echo esc_attr( $wpcp_nonce ); ?>" data-id="<?php echo esc_attr( $post_id ); ?>" data-total="<?php echo esc_attr( $total_page ); ?>">
			<div class="wpcpro-post-pagination-number">
			<a class="prev page-numbers current" href="#">
					<i class="fa fa-angle-left"></i>
				</a>
			<?php
			// Create number pagination tag.
			for ( $page_number = 1; $page_number < $total_page + 1; $page_number++ ) {
				$current = '';
				if ( $page_number == 1 ) {
					$current = ' current';
				}
				echo '<a class="page-numbers wcppage' . $current . '" href="#" data-page="' . $page_number . '">' . $page_number . '</a>';
			}
			?>
				<a class="next page-numbers" href="#">
					<i class="fa fa-angle-right"></i>
				</a>
			</div>
		</div>
			<?php
	} elseif ( 'load_more_btn' === $pagination_type || 'infinite_scroll' === $pagination_type ) {
		?>
		<div class="wpcpro-load-more" data-pagi="<?php echo esc_attr( $pagination_type ); ?>" data-text="<?php echo esc_html( $end_content_text ); ?>" data-items-have=" <?php echo esc_html( $load_more_post ); ?>" data-per-page="<?php echo esc_html( $item_per_click ); ?>">
			<?php
			if ( 'load_more_btn' === $pagination_type && $show_load_more_counter_message ) {
				echo wp_kses_post( $pagination_counter_message );
			}
			?>
			<button wpcp-processing="0" data-id="<?php echo esc_attr( $post_id ); ?>" data-page="1" data-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" data-total="<?php echo esc_attr( $total_page ); ?>" data-nonce="<?php echo esc_attr( $wpcp_nonce ); ?>"><?php echo esc_html( $load_more_label ); ?><span class="load-more-count"> (<?php echo esc_html( $load_more_post ); ?>)</span></button>
		</div>
			<?php
	}
}
