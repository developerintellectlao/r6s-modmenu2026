<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Rishi
 */
use Rishi\Customizer\Helpers\Basic as Basic;

$sidebar = rishi_sidebar();
$sidebar_meta = Basic::get_meta( get_the_ID(), 'rishi_advanced_sidebar_id', 'default' );

if ( ! $sidebar ) {
	return;
}

$display_sidebar = $sidebar_meta === 'default' || !is_active_sidebar($sidebar_meta) ? $sidebar : $sidebar_meta;
?>

<aside id="secondary" class="widget-area" <?php echo rishi_print_schema('sidebar'); ?>>
	<?php do_action( 'rishi_sidebar_before' ); ?>
	<div class="sidebar-wrap-main">
		<?php dynamic_sidebar( $display_sidebar ); ?>
	</div>
	<?php do_action( 'rishi_sidebar_after' ); ?>
</aside><!-- #secondary -->