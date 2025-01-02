<?php

/**
 * Custom Facebook Feed Item : Post Link Template
 * Displays the item post link
 *
 * @version 3.18 Custom Facebook Feed by Smash Balloon
 */

use CustomFacebookFeed\CFF_Utils;
use CustomFacebookFeed\CFF_Shortcode_Display;
use CustomFacebookFeed\CFF_Display_Elements_Pro;

// Don't load directly
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}


$cff_link_styles 			= $this_class->get_style_attribute('post_link');
$cff_show_facebook_link 	= CFF_Utils::check_if_on($feed_options['showfacebooklink']);
$cff_show_facebook_share 	= CFF_Utils::check_if_on($feed_options['showsharelink']);
$cff_post_text_to_share 	= CFF_Shortcode_Display::get_post_link_text_to_share($cff_post_text);
$link_text 					= CFF_Shortcode_Display::get_post_link_text_link($feed_options, $cff_post_type, $translations);
$social_share_links 		= CFF_Shortcode_Display::get_post_link_social_links($link, $cff_post_text_to_share);
$cff_facebook_share_text 	= CFF_Shortcode_Display::get_post_link_fb_share_text($feed_options, $translations);

if ($cff_show_facebook_link || $cff_show_facebook_share) :
	// print_r($feed_options['feedtheme']);
	?>
<div class="cff-post-links">
	<?php
	if ($cff_show_facebook_link) :
		?>
		<a class="cff-viewpost-facebook" href="<?php echo esc_url($link) ?>" title="<?php echo esc_attr($link_text) ?>" <?php echo $target . '' . $cff_nofollow . ' ' . $cff_link_styles; ?>>
			<?php
			if ($feed_theme && $feed_theme !== 'default_theme') :
				if ($feed_theme !== 'social_wall') :
					?>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M8.00016 1.36035C4.3335 1.36035 1.3335 4.35369 1.3335 8.04035C1.3335 11.3737 3.7735 14.1404 6.96016 14.6404V9.97369H5.26683V8.04035H6.96016V6.56702C6.96016 4.89369 7.9535 3.97369 9.48016 3.97369C10.2068 3.97369 10.9668 4.10035 10.9668 4.10035V5.74702H10.1268C9.30016 5.74702 9.04016 6.26035 9.04016 6.78702V8.04035H10.8935L10.5935 9.97369H9.04016V14.6404C10.6111 14.3922 12.0416 13.5907 13.0734 12.3804C14.1053 11.1701 14.6704 9.63078 14.6668 8.04035C14.6668 4.35369 11.6668 1.36035 8.00016 1.36035V1.36035Z" fill="#434960"/>
					</svg>
					<?php
					if ($feed_theme == 'outline' || $feed_theme == 'overlap') :
						?>
						<span><?php _e('View on Facebook', 'custom-facebook-feed'); ?></span>
						<?php
					endif;
				endif;
			else :
				echo esc_html($link_text);
			endif;
			?>
		</a>
		<?php
	endif;

	if ($cff_show_facebook_share) : ?>
		<div class="cff-share-container">
			<?php
			if ($cff_show_facebook_share) :
				if ($cff_show_facebook_link && $cff_show_facebook_share && $feed_theme == 'default_theme') :?>
				<span class="cff-dot" <?php echo $cff_link_styles ?>>&middot;</span>
				<?php endif; ?>
				<a
				class="cff-share-link"
				href="<?php echo esc_url($social_share_links['facebook']['share_link']); ?>"
				title="<?php echo esc_attr($cff_facebook_share_text) ?>"
				<?php echo $cff_link_styles ?>>
					<?php
					if ($feed_theme && $feed_theme !== 'default_theme') {
						if ($feed_theme === 'social_wall') :
							?>
							<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect y="0.5" width="20" height="20" rx="10" fill="#8C8F9A"/>
								<circle cx="5.5" cy="10.5" r="1.5" fill="white"/>
								<circle cx="10" cy="10.5" r="1.5" fill="white"/>
								<circle cx="14.5" cy="10.5" r="1.5" fill="white"/>
							</svg>
							<?php
						else :
							?>
							<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_1969_47911)">
									<path d="M12.8409 6.58819L9.47282 3.13265C9.17819 2.83078 8.71394 3.07612 8.71394 3.54622V5.40051C5.52663 5.44126 3 6.15312 3 9.51614C3 10.8738 3.78567 12.2191 4.65347 12.9217C4.92444 13.1412 5.31013 12.8657 5.21013 12.5101C4.31019 9.30611 5.85697 8.601 8.71394 8.5785V10.4338C8.71394 10.9044 9.17909 11.1486 9.47326 10.8466L12.8414 7.39106C13.053 7.19578 13.053 6.80572 12.8409 6.58819Z" stroke="#141B38" stroke-linecap="round"/>
								</g>
								<defs>
									<clipPath id="clip0_1969_47911">
										<rect width="16" height="16" rx="2" fill="white"/>
									</clipPath>
								</defs>
							</svg>
							<?php
						endif;
					} else {
						echo esc_html($cff_facebook_share_text);
					} ?>
				</a>
				<div class="cff-share-tooltip">
					<?php
					if ($feed_theme && $feed_theme !== 'default_theme') :
						?>
						<div class="cff-share-title"><?php echo 'Share'; ?></div>
						<?php
					endif;
					?>
					<?php foreach ($social_share_links as $social_key => $social) : ?>
						<a href="<?php echo esc_url($social['share_link']) ?>" target="_blank" rel="nofollow noopener" class="cff-<?php echo $social_key ?>-icon">
							<?php echo CFF_Display_Elements_Pro::get_icon($social['icon']); ?>
							<span class="cff-screenreader"><?php echo $social['text'] ?></span>
						</a>
					<?php endforeach; ?>
				<?php echo CFF_Display_Elements_Pro::get_icon('play') ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
	<?php
endif;