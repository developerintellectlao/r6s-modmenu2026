<?php
/**
 * Mix content carousel audio
 *
 * This template can be overridden by copying it to yourtheme/wp-carousel-pro/templates/loop/mix-content-type/audio.php
 *
 * @package WP_Carousel_Pro
 */

	$audio_link = isset( $source['carousel_audio_source_upload'] ) && ! empty( $source['carousel_audio_source_upload'] ) ? $source['carousel_audio_source_upload'] : '';
?>

	<div class="wpcp-single-item">
		<?php if ( ! empty( $audio_link ) ) { ?>
			<audio src="<?php echo esc_url( $audio_link ); ?>" controls></audio>
			<?php
		}
		if ( ! empty( $mix_content_description ) ) {
			?>
		<div class="wpcp-single-content">
			<?php echo $mix_audio_embed; ?>
			<?php echo do_shortcode( wpautop( $wp_embed->autoembed( $mix_content_description ) ) ); ?>
		</div>
		<?php } ?>
	</div>
<?php
