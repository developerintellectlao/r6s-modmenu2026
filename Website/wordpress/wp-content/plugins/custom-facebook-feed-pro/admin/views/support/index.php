<div id="cff-support" class="cff-support">
	<?php
		CustomFacebookFeed\CFF_View::render('sections.header');
		CustomFacebookFeed\CFF_View::render('support.content');
		CustomFacebookFeed\CFF_View::render('sections.sticky_widget');
	?>

	<?php
		include_once CFF_BUILDER_DIR . 'templates/sections/popup/license-learn-more.php';
		include_once CFF_BUILDER_DIR . 'templates/sections/popup/why-renew-license-popup.php';
	?>
</div>