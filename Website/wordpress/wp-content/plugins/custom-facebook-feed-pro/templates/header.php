<?php

/**
 * Custom Facebook Feed Header Template
 * Adds account information and an avatar to the top of the feed
 *
 * @version 3.13 Custom Facebook Feed Pro by Smash Balloon
 */

use CustomFacebookFeed\CFF_Parse_Pro;
use CustomFacebookFeed\CFF_Utils;
use CustomFacebookFeed\CFF_Shortcode_Display;
use CustomFacebookFeed\CFF_Display_Elements_Pro;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
// Check Header Type
if ($cff_header_type == "text") : // Start Text Header
	$cff_icon_style 		= $this_class->get_style_attribute('header_icon');
	$cff_header_classes 	= CFF_Shortcode_Display::get_header_txt_classes($cff_header_outside);
	$palette_class 		= CFF_Display_Elements_Pro::palette_class($feed_options, $feed_id);
	?>
	<h3 class="cff-header <?php echo $cff_header_classes . $palette_class ?>" <?php echo $cff_header_styles ?>>
		<?php echo CFF_Display_Elements_Pro::get_icon($atts['headericon'], $cff_icon_style) ?>
		<span class="cff-header-text"><?php echo stripslashes($atts['headertext']) ?></span>
	</h3>
	<?php
// End Text Header
elseif ($cff_header_type == "visual" && $cff_show_header) : // Start Visual Header
	$header_details = CFF_Utils::fetch_header_data($page_id, $cff_is_group, $access_token, $cff_cache_time, $cff_multifeed_active, $data_att_html);
	if (isset($header_details->error)) {
		return '';
	}
	$header_parts 				= CFF_Shortcode_Display::get_header_parts($atts);
	$cff_header_cover 			= $header_parts['cover'];
	$cff_header_name 			= $header_parts['name'];
	$cff_header_bio 			= $header_parts['bio'];
	$header_style_attribute 	= $this_class->get_style_attribute('header_visual');
	$header_data 				= $header_details;
	if (! isset($feed_options['feedtheme'])) {
		$feed_options['feedtheme'] = 'default_theme';
	}

	$link 				= CFF_Shortcode_Display::get_header_link($header_data, $page_id);
	$avatar 			= CFF_Parse_Pro::get_avatar($header_data);
	$avatar_img_src 	= CFF_Display_Elements_Pro::avatar_src($header_data, $feed_options);
	$name 				= CFF_Parse_Pro::get_name($header_data);
	$cover_url 			= CFF_Parse_Pro::get_cover_source($header_data);
	$cover_img_src 		= CFF_Display_Elements_Pro::cover_image_src($header_data, $feed_options);
	$likes_count 		= CFF_Parse_Pro::get_likes($header_data);
	$bio  				= CFF_Parse_Pro::get_bio($header_data);
	$should_show_bio 	= $bio !== '' ? $cff_header_bio : false;
	$bio_class 			= $cff_header_bio ? ' cff-has-about' : '';
	$bio_style          = CFF_Display_Elements_Pro::bio_style($this_class);
	$avatar_class 		= $cff_header_name ? ' cff-has-name' : '';
	$cover_class 		= $cff_header_cover ? ' cff-has-cover' : '';
	$palette_class 		= CFF_Display_Elements_Pro::palette_class($feed_options, $feed_id);
	$title_style        = CFF_Display_Elements_Pro::title_style($this_class);

	$header_hero_style  = CFF_Shortcode_Display::get_header_height_style($atts);

	if (empty($cover_url)) {
		$cff_header_cover = false;
		$cover_class = '';
	}
	if (empty($likes_count)) {
		$cff_header_bio = false;
	}
	$square_logo = '<svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-square" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-facebook-square fa-w-14"><path fill="currentColor" d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z" class=""></path></svg>';
	?>
	<?php CFF_Shortcode_Display::print_gdpr_notice('Visual Header '); ?>
	<div id="cff-visual-header-<?php echo esc_attr(preg_replace("/[^A-Za-z0-9]/", '', $page_id)); ?>" class="cff-visual-header<?php echo $avatar_class . $bio_class . $cover_class . $palette_class ?>">
		<?php if ($cff_header_cover) : ?>
		<div class="cff-header-hero"<?php echo $header_hero_style; ?>>
			<img src="<?php echo esc_url($cover_img_src); ?>" class="cff-feed-image" alt="<?php echo esc_attr(sprintf(__('Cover for %s', 'custom-facebook-feed'), $name)); ?>" data-cover-url="<?php echo esc_url($cover_url); ?>">
			<?php
			if (
				$cff_header_bio &&
				( ! isset($feed_options['feedtheme']) || 'social_wall' === $feed_options['feedtheme'] || 'default_theme' === $feed_options['feedtheme'] )
			) :
				?>
				<div class="cff-likes-box">
					<?php
					if (isset($feed_options['feedtheme']) && 'social_wall' === $feed_options['feedtheme']) :
						?>
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_669_113477)"><circle cx="10" cy="10" r="10" fill="#FE544F"></circle> <path d="M15.3028 7.41117C14.8142 6.45845 13.4067 5.67895 11.7695 6.13279C10.9871 6.3475 10.3046 6.80793 9.83325 7.43888C9.36195 6.80793 8.67937 6.3475 7.89703 6.13279C6.25616 5.68588 4.85231 6.45845 4.3637 7.41117C3.67818 8.74497 3.9626 10.2451 5.20966 11.8699C6.18688 13.1413 7.58344 14.4301 9.61082 15.9267C9.67489 15.9742 9.75382 16 9.83507 16C9.91633 16 9.99526 15.9742 10.0593 15.9267C12.0831 14.4336 13.4833 13.1552 14.4605 11.8699C15.7039 10.2451 15.9883 8.74497 15.3028 7.41117Z" fill="white"></path></g> <defs><clipPath id="clip0_669_113477"><rect width="20" height="20" fill="white"></rect></clipPath></defs></svg>
						<?php
					elseif (! isset($feed_options['feedtheme']) || 'default_theme' === $feed_options['feedtheme']) :
						?>
						<div class="cff-square-logo"><?php echo $square_logo; ?></div>
						<?php
					endif;
					?>
					<div class="cff-likes-count">
						<?php echo $likes_count; ?>
					</div>
				</div>
				<?php
			endif;
			?>
		</div>
			<?php
		endif;

		if ($cff_header_cover) :
			$inner_header_style = '';
			if ($feed_options['poststyle'] == 'regular' && ( ($feed_options[ 'sepcolor' ] !== '#' && $feed_options[ 'sepcolor' ] !== '') || ( empty($feed_options[ 'sepsize' ]) || $feed_options[ 'sepsize' ] == '' ) ) && $feed_options['feedtheme'] === 'outline') {
				$inner_header_style = 'style="border-bottom: ' . $feed_options[ 'sepsize' ] . 'px solid #' . str_replace('#', '', $feed_options[ 'sepcolor' ]) . ';"';
			}

			?>
		<div class="cff-header-wrap" <?php echo $inner_header_style; ?>>
		<?php endif; ?>
			<div class="cff-header-inner-wrap">
				<?php if ($cff_header_name && $avatar !== '') : ?>
					<div class="cff-header-img">
						<a href="<?php echo esc_url($link); ?>" target="_blank" rel="nofollow noopener" title="<?php echo esc_attr($name); ?>"><img src="<?php echo esc_url($avatar_img_src); ?>" class="cff-feed-image" alt="<?php echo esc_attr($name); ?>" data-avatar="<?php echo esc_url($avatar); ?>"></a>
					</div>
				<?php endif; ?>
				<div class="cff-header-text">

				<?php if ($cff_header_name) : ?>
					<a href="<?php echo esc_url($link); ?>" target="_blank" rel="nofollow noopener" title="<?php echo esc_attr($name); ?>" class="cff-header-name" <?php echo $header_style_attribute; ?>><h3 <?php echo $title_style; ?>><?php echo esc_html($name); ?></h3></a>
				<?php endif; ?>
				<?php if ($cff_header_bio && !$cff_header_cover && ( ! isset($feed_options['feedtheme']) || 'default_theme' == $feed_options['feedtheme'] )) : ?>
					<div class="cff-bio-info">
						<span class="cff-posts-count"><?php echo $square_logo . number_format(intval($likes_count), 0); ?></span>
					</div>
				<?php endif; ?>
				<?php
				if (
					$cff_header_bio
					&& ! $cff_header_cover
					&& ( isset($feed_options['feedtheme']) && ('modern' == $feed_options['feedtheme'] || 'social_wall' == $feed_options['feedtheme']) )
				) :
					?>
					<div class="cff-bio-info">
						<span class="cff-posts-count cff-without-cover-img">
							<?php echo $likes_count . ' '; ?>
							<?php _e('Likes', 'custom-facebook-feed'); ?>
						</span>
					</div>
					<?php
				endif;

				if ($cff_header_bio && $cff_header_cover && ( isset($feed_options['feedtheme']) && 'modern' == $feed_options['feedtheme'] )) : ?>
					<div class="cff-bio-info">
						<span class="cff-posts-count">
							<?php echo $likes_count . ' '; ?>
							<?php _e('Likes', 'custom-facebook-feed'); ?>
						</span>
					</div>
					<?php
				endif;

				if (
					$cff_header_bio &&
					( isset($feed_options['feedtheme']) && 'overlap' == $feed_options['feedtheme'] )
				) :
					?>
					<div class="cff-bio-info">
						<span class="cff-posts-count">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_669_113477)"><circle cx="10" cy="10" r="10" fill="#FE544F"></circle> <path d="M15.3028 7.41117C14.8142 6.45845 13.4067 5.67895 11.7695 6.13279C10.9871 6.3475 10.3046 6.80793 9.83325 7.43888C9.36195 6.80793 8.67937 6.3475 7.89703 6.13279C6.25616 5.68588 4.85231 6.45845 4.3637 7.41117C3.67818 8.74497 3.9626 10.2451 5.20966 11.8699C6.18688 13.1413 7.58344 14.4301 9.61082 15.9267C9.67489 15.9742 9.75382 16 9.83507 16C9.91633 16 9.99526 15.9742 10.0593 15.9267C12.0831 14.4336 13.4833 13.1552 14.4605 11.8699C15.7039 10.2451 15.9883 8.74497 15.3028 7.41117Z" fill="white"></path></g> <defs><clipPath id="clip0_669_113477"><rect width="20" height="20" fill="white"></rect></clipPath></defs></svg>
							<?php echo ' ' . $likes_count; ?>
						</span>
					</div>
				<?php endif; ?>

				<?php if ($should_show_bio) : ?>
					<p class="cff-bio"<?php echo $bio_style; ?>><?php echo str_replace('&lt;br /&gt;', '<br>', esc_html(nl2br($bio))); ?></p>
				<?php endif; ?>
				</div>

				<?php
				if (isset($feed_options['feedtheme']) && 'outline' === $feed_options['feedtheme']) :
					?>
					<div class="cff-header-likes-count">
						<span>
							<svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22.33 12.88C22.44 12.63 22.5 12.36 22.5 12.08V11C22.5 9.9 21.6 9 20.5 9H15L15.92 4.35C15.97 4.13 15.94 3.89 15.84 3.69C15.6126 3.23961 15.3156 2.82789 14.96 2.47V2.47C14.7208 2.22565 14.3186 2.25842 14.1221 2.53825L10 8.41V8.41C9.05471 9.52544 7.5 10.1853 7.5 11.6474V17.67C7.50264 18.2889 7.75035 18.8815 8.1889 19.3182C8.62744 19.7548 9.22112 20 9.84 20H17.95C18.65 20 19.31 19.63 19.67 19.03L22.33 12.88Z" stroke="#141B38" stroke-width="1.25"></path> <rect x="3.5" y="9" width="4" height="11" rx="1" stroke="#141B38" stroke-width="1.25"></rect></svg>
						</span>
						<span><?php echo $likes_count; ?></span>
					</div>
					<?php
				endif;
				?>
			</div>
			<?php
			if ($cff_header_cover) :
				?>
			</div>
			<?php endif; ?>
	</div>
<?php endif; // End Visual Header ?>
