<?php

/**
 * Custom Facebook Load More Button Template
 * Display the Facebook load more button
 *
 * @version 3.18 Custom Facebook Feed by Smash Balloon
 */

use CustomFacebookFeed\CFF_Utils;
use CustomFacebookFeed\CFF_Shortcode_Display;

// Don't load directly
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$load_more_attributes 	= CFF_Shortcode_Display::get_load_more_button_attr($atts);
$cff_load_more_styles 	= $this_class->get_style_attribute('load_more');
$cff_load_more_text 	= CFF_Utils::return_value(stripslashes($atts['buttontext']), esc_html__('Load more', 'custom-facebook-feed')) ;
?>
<input type="hidden" class="cff-pag-url" data-cff-pag-url="<?php echo $next_urls_arr_safe ?>" data-cff-prev-url="<?php echo $prev_urls_arr_safe ?>" data-transient-name="<?php echo $facebook_settings->get_transient_name(); ?>" data-post-id="<?php echo get_the_ID() ?>" data-feed-id="<?php echo $atts['id'] ?>"  value="">
<?php if ($next_urls_arr_safe == '{}') : ?>
	<p class="cff-no-more-posts"><?php echo $atts['nomoretext'] ?></p>
<?php else : ?>
	<a href="javascript:void(0);" id="cff-load-more" class="cff-load-more" <?php echo $cff_load_more_styles ?> <?php echo $load_more_attributes ?>>
		<?php
		if (! empty($atts['feedtheme']) && $atts['feedtheme'] != 'default_theme') :
			?>
			<span class="cff-load-icon">
				<svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="5.75" cy="9.75" r="1.25" fill="#141B38"/>
					<circle cx="10.5" cy="9.75" r="1.25" fill="#141B38"/>
					<circle cx="15.25" cy="9.75" r="1.25" fill="#141B38"/>
				</svg>
			</span>
			<?php
		endif;
		?>
		<span><?php echo $cff_load_more_text ?></span>
	</a>
<?php endif; ?>
