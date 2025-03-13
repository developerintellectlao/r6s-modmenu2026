<?php
/**
 * Image caption.
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel/templates/loop/image-type/caption.php
 *
 * @package WP_Carousel
 */

// Image caption.
if ( ! empty( $image_title ) && $show_img_caption ) {
	?>
<div class="wpcp-all-captions">
	<?php
	echo wp_kses_post( $image_title );
	?>
</div>
<?php } ?>
