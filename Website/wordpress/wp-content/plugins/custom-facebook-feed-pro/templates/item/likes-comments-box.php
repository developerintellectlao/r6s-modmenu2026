<?php

/**
 * Custom Facebook Feed Item : likes-comments-box Template
 * Displays the item meta Likes & Comments
 *
 * @version 3.18 Custom Facebook Feed by Smash Balloon
 */

use CustomFacebookFeed\CFF_Shortcode_Display;

// Don't load directly
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$btn_class 	= CFF_Shortcode_Display::get_like_comment_btn_classes($cff_lightbox_comments, $cff_show_meta);
$other_meta = ['comments', 'shares'];




?>
<div class="cff-view-comments-wrap">
	<a href="javaScript:void(0);" <?php echo $btn_class . '' . $cff_meta_styles ?> id="<?php echo $orig_post_id ?>">
		<span class="cff-screenreader"><?php echo esc_html__('View Comments', 'custom-facebook-feed') ?></span>
		<ul class="cff-meta <?php echo $cff_icon_style ?>">
			<li class="cff-likes">
				<?php
				if (is_array($l_c_s_info) && !empty($l_c_s_info)) {
					foreach ($l_c_s_info as $r_key => $r_element) {
						if (!in_array($r_key, $other_meta) && !empty($r_element['icon'])) {
							?>
								<span class="cff-<?php echo $r_key; ?> cff-reaction-one cff-icon">
									<span class="cff-screenreader"><?php echo $r_key; ?></span>
								<?php echo $r_element['icon']; ?>
								</span>
							<?php
						}
					}
				}
				?>
				<span class="cff-count"><?php echo isset($l_c_s_info['reactions']['count']) ? $l_c_s_info['reactions']['count'] : 0; ?></span>
			</li>

			<?php
			if (!empty($feed_theme) && $feed_theme !== 'default_theme') {
				CFF_Shortcode_Display::print_metabox_comment_icon($l_c_s_info);
			}

				CFF_Shortcode_Display::print_metabox_share_icon($l_c_s_info);

			if (!$feed_theme || $feed_theme === 'default_theme') {
				CFF_Shortcode_Display::print_metabox_comment_icon($l_c_s_info);
			}
			?>
		</ul>
	</a>
</div>


