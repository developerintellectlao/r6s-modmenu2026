<?php

/**
 * Custom Facebook Feed Item : meta wrapper Template
 *
 * @version 3.18 Custom Facebook Feed by Smash Balloon
 */

use CustomFacebookFeed\CFF_Shortcode_Display;
use CustomFacebookFeed\CFF_Utils;

// Don't load directly
if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>

<div class="cff-meta-wrap">
	<?php
	if ($cff_show_link && $feed_options['feedtheme'] === 'default_theme') {
		echo $cff_link;
	}
	if ($cff_show_meta || $cff_lightbox_comments) {
		echo $cff_meta;
	}
	if ($cff_show_link && $feed_options['feedtheme'] !== 'default_theme') {
		echo $cff_link;
	}
	?>
</div>
