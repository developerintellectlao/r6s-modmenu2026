<?php

/**
 * Custom Facebook Feed Item : Author Template
 * Displays the item author
 *
 * @version 3.18 Custom Facebook Feed by Smash Balloon
 */

use CustomFacebookFeed\CFF_Shortcode_Display;

// Don't load directly
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (isset($cff_from_id)) :
	$cff_new_from_link 		= CFF_Shortcode_Display::get_author_new_from_link_($news);
	$cff_author_link_atts 	= CFF_Shortcode_Display::get_author_link_atts($cff_new_from_link, $news, $target, $cff_nofollow, $cff_author_styles);
	$cff_author_link_el 	= CFF_Shortcode_Display::get_author_link_el($cff_new_from_link, $news);
	$post_text_story 		= CFF_Shortcode_Display::get_author_post_text_story($post_text_story, $cff_author_name);
	$author_src_class 		= CFF_Shortcode_Display::get_author_pic_src_class($news, $feed_options);
	$cff_author_img_src 	= $author_src_class[ 'image' ];
	$cff_author_src 		= $author_src_class[ 'real_image' ];
	$cff_author_img_class 	= $author_src_class[ 'class' ];
	$link_text 				= CFF_Shortcode_Display::get_post_link_text_link($feed_options, $cff_post_type, $translations);
	$cff_link_styles 		= $this_class->get_style_attribute('post_link');
	?>
<div class="cff-author">
	<div class="cff-author-text">
		<?php if ($cff_show_date && $cff_date_position !== 'above' && $cff_date_position !== 'below') : ?>
			<div class="cff-page-name cff-author-date" <?php echo $cff_author_styles ?>>
				<<?php echo $cff_author_link_el . ' ' . $cff_author_link_atts ?>>
					<?php echo $cff_author_name ?>
				</<?php echo $cff_author_link_el ?>>
				<span class="cff-story"> <?php echo $post_text_story ?></span>
			</div>
			<?php echo $cff_date ?>
		<?php else : ?>
			<span class="cff-page-name">
				<<?php echo $cff_author_link_el . ' ' . $cff_author_link_atts;?>>
					<?php echo $cff_author_name ?>
				</<?php echo $cff_author_link_el ?>>
				<span class="cff-story"> <?php echo $post_text_story ?></span></span>
		<?php endif; ?>
	</div>
	<div class="cff-author-img <?php echo $cff_author_img_class ?>" data-avatar="<?php echo esc_url($cff_author_src)  ?>">
		<<?php echo $cff_author_link_el . '' . $cff_author_link_atts ?>>
			<img src="<?php echo esc_url($cff_author_img_src) ?>" class="cff-feed-image" alt="<?php echo esc_attr($cff_author_name) ?>" width=40 height=40 onerror="this.style.display='none'">
		</<?php echo $cff_author_link_el ?>>
	</div>

	<?php
	$feed_theme = in_array('feed_themes', $license_tier_features) ? $feed_options['feedtheme']  : 'default_theme';
	if ($feed_theme === 'social_wall') :
		$post_url = ( isset($news->from->link) ? $news->from->link . "/posts/" : 'https://www.facebook.com/' ) . $news->id;
		?>
		<a class="cff-viewpost-facebook" href="<?php echo esc_url($post_url) ?>" title="<?php echo esc_attr($link_text) ?>" <?php echo $target . '' . $cff_nofollow . ' ' . $cff_link_styles; ?>>
		<div class="cff-top-share">
			<svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M10 0.540039C4.5 0.540039 0 5.03004 0 10.56C0 15.56 3.66 19.71 8.44 20.46V13.46H5.9V10.56H8.44V8.35004C8.44 5.84004 9.93 4.46004 12.22 4.46004C13.31 4.46004 14.45 4.65004 14.45 4.65004V7.12004H13.19C11.95 7.12004 11.56 7.89004 11.56 8.68004V10.56H14.34L13.89 13.46H11.56V20.46C13.9164 20.0879 16.0622 18.8856 17.6099 17.0701C19.1576 15.2546 20.0054 12.9457 20 10.56C20 5.03004 15.5 0.540039 10 0.540039Z" fill="#006BFA"/>
			</svg>
		</div>
		</a>
		<?php
	endif;
	?>

</div>
	<?php
else :
	?>
<div class="cff-author cff-no-author-info">
	<div class="cff-author-text">
		<?php if ($cff_show_date && $cff_date_position !== 'above' && $cff_date_position !== 'below') : ?>
			<?php if (!empty($post_text_story)) : ?>
				<div class="cff-page-name cff-author-date"><span class="cff-story"> <?php echo $post_text_story ?></span></div>
				<?php echo $cff_date ?>
			<?php endif; ?>
		<?php else : ?>
			<?php if (!empty($post_text_story)) : ?>
				<span class="cff-page-name"><span class="cff-story"> <?php echo $post_text_story ?></span></span>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<div class="cff-author-img"></div>
</div>
	<?php
endif;
