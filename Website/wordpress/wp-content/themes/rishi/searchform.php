<?php
/**
 * Search form
 * @license   https://www.gnu.org/copyleft/gpl.html GNU General Public License
 */


$placeholder = esc_attr_x( 'Search', 'placeholder', 'rishi' );
$search_class = rishi_customizer()->header_builder->get_elements()->get_items()['search'];
$_instance      = new $search_class;

if ( class_exists( $search_class ) ) {
	$placeholder = $_instance->get_mod_value( 'search_placeholder', 'Search' );
}

$home_url = home_url( '' );

if ( function_exists( 'pll_home_url' ) ) {
	$home_url = pll_home_url();
}

?>
<form 
	autocomplete = "off"
	role         = "search"
	method       = "get"
	class        = "search-form"
	action       = "<?php echo esc_url( $home_url ); ?>">
	<label>
		<span class="screen-reader-text">
			<?php echo esc_html__( "Search for:", "rishi" ) ?>
		</span>
		<input type="search" class="search-field" placeholder="<?php echo $placeholder; ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo __( 'Search Input', 'rishi' ) ?>" />
	</label>
	<input type="submit" class="search-submit" value="<?php esc_attr_e( "Search", "rishi" ) ?>">
</form>
