<?php
/**
 * Class Header_Builder.
 *
 * This class is responsible for building the header of the website.
 * It extends the Customize_Builder class and implements its own methods.
 */
namespace Rishi\Customizer\Builder;

use Rishi\Customizer\Abstracts\Customize_Builder as Customize_Builder;
use Rishi\Customizer\Helpers\Defaults as Defaults;
use Rishi\Customizer\Helpers\Basic as Basic;

/**
 * Class Header_Builder
 */
class Header extends Customize_Builder {


	protected $header_placements = array();

	/**
	 * Constructor for the Header class.
	 *
	 * This method is used to set up the Header object.
	 * It calls the parent constructor from the Customize_Builder class.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get the section type.
	 *
	 * This method is used to get the section type of the Header object.
	 * It returns a string 'header'.
	 *
	 * @return string The section type.
	 */
	public function get_builder_type() {
		return 'header';
	}

	/**
	 * This function checks if the header is enabled on the current page.
	 *
	 * @return string Returns 'yes' if the header is enabled, 'no' otherwise.
	 */
	public function enabled_on_this_page() {
		$post_id = get_the_ID();
		if ( is_home() && ! is_front_page() ) {
			$post_id = get_option( 'page_for_posts' );
		}

		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$post_id = get_option( 'woocommerce_shop_page_id' );
		}
		$disable_header = Basic::get_meta( $post_id, 'disable_header', 'no' );
		return $disable_header;
	}

	/**
	 * This function renders the header.
	 *
	 * It checks if the header is enabled on the current page and if it is, it renders the header.
	 */
	public function render() {
		if ( $this->enabled_on_this_page() === 'yes' ) {
			return '';
		}
		?>
		<header id="header" class="site-header" <?php echo rishi_print_schema('header'); ?>>
			<?php

			// Transparent Header.
			$transparent_header = get_theme_mod( 'has_transparent_header', 'no' );
			$disable_on_mbl     = get_theme_mod( 'disable_transparent_header', 'no' );

			// Transparent Header PostMeta.
			$transparent_header = ( Basic::get_meta( get_the_ID(), 'has_transparent_header', 'no' ) === 'yes' ) ? 'yes' : $transparent_header;
			$disable_on_mbl     = ( Basic::get_meta( get_the_ID(), 'disable_transparent_header', 'no' ) === 'yes' ) ? 'yes' : $disable_on_mbl;

			$transparent_class = $transparent_header === 'yes' ? ' transparent-header' : '';

			// Sticky Header.
			$sticky_header     = get_theme_mod( 'has_sticky_header', 'no' );
			$sticky_visibility = get_theme_mod(
				'sticky_row_visibility',
				array(
					'desktop' => 'desktop',
					'mobile'  => 'mobile',
				)
			);

			$device_types = array( 'desktop', 'mobile' );
			foreach ( $device_types as $device ) {
				if ( $device === 'mobile' && $disable_on_mbl === 'yes' ) {
					$transparent_class = '';
				}

				$sticky_class = ( $sticky_header === 'yes' && in_array( $device, $sticky_visibility ) ) ? ' sticky-header' : '';

				echo '<div class="rishi-header-' . esc_attr( $device ) . esc_attr( $transparent_class ) . esc_attr( $sticky_class ) . '">';
				$this->output_elements_collection( 'top-row', $device );
				$this->output_elements_collection( 'middle-row', $device );
				$this->output_elements_collection( 'bottom-row', $device );
				echo '</div>';
			}
			?>
		</header>
		<?php
		$this->get_offcanvas_row_elements();
	}

	/**
	 * Get options.
	 *
	 * This method is used to get options for the Header object.
	 * It returns an empty array.
	 *
	 * @return array The options for the Header object.
	 */
	public function get_options() {
		return array();
	}

	/**
	 * Display offcanvas elements for the header.
	 *
	 * This method is used to display offcanvas elements for the Header object.
	 * It does not return any value.
	 *
	 * @return void
	 */
	public function get_offcanvas_row_elements() {
		$offcanvasClass = $this->get_elements()->get_items()['offcanvas'];
		$_instance      = new $offcanvasClass();
		$panel_location = $_instance->get_mod_value( 'side_panel_position', 'right' );
		?>

		<div id="rishi-offcanvas" class="rishi-offcanvas-drawer loc-<?php echo esc_attr( $panel_location ); ?>" role="dialog" aria-labelledby="offcanvasLabel">
			<div class="rishi-drawer-wrapper">
				<div class="rishi-drawer-header">
					<span id="offcanvasLabel" class="screen-reader-text">
						<?php echo esc_html__( 'Offcanvas menu', 'rishi' ); ?>
					</span>
					<button class="close-button" aria-label="close" aria-controls="rishi-offcanvas">
						<span class="rishi_menu_trigger closed">
							<span></span>
						</span>
					</button>
				</div>
				<?php $this->output_elements_collection( 'offcanvas', 'desktop' ); ?>
				<?php $this->output_elements_collection( 'offcanvas', 'mobile' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get Header Data on the basis of row placement
	 *
	 * This function retrieves the header data based on the row placement. It takes two parameters,
	 * the row and the device. The row parameter defaults to 'top-row' and the device parameter defaults to 'desktop'.
	 *
	 * @param string $row The row placement of the header data. Defaults to 'top-row'.
	 * @param string $device The device for which the header data is retrieved. Defaults to 'desktop'.
	 * @return array The header data for the specified row and device.
	 */
	public function get_elements_by_row( $row = 'top-row', $device = 'desktop' ) {
		$header_data = get_theme_mod( 'header_builder_key_placement', $this->retrieve_default_value() );

		$allDataArray = $header_data['sections'][0];
		$finalArr     = array();
		$finalArr     = $allDataArray[ $device ];
		$rowData      = array_filter(
			$finalArr,
			function ( $item ) use ( $row ) {
				return $item['id'] == $row;
			}
		);

		return $rowData;
	}

	/**
	 * Get Header Data on the basis of placements within the selected row
	 *
	 * @param string $row - Array of the current row
	 * @param string  $placement - Placement of the elements in the given row
	 * @return array $found_elements - Get a flattened array with required elements on the basis of placement
	 */
	public function get_elements_by_placements( $row = 'top-row', $placement = 'start', $device = 'desktop' ) {
		$header_data    = $this->get_elements_by_row( $row, $device );
		$placements_arr = ( current( (array) ( $header_data ) ) );
		$found_elements = array();
		foreach ( $placements_arr['placements'] as $element ) {
			if ( $element['id'] === $placement && count( $element['items'] ) > 0 ) {
				$found_elements[] = array_values( $element['items'] );
			}
		}

		if ( ! empty( current( $found_elements ) ) ) {
			$total_elements = current( $found_elements );
			// Remove static value saved in database
			$final_array = array_filter(
				$total_elements,
				function( $value ) {
					return $value !== '1o2';
				}
			);
			return $final_array;
		} else {
			return array();
		}
	}

	/**
	 * Get count for the elements in each placement
	 *
	 * @param string $row
	 * @param string $device
	 * @return array
	 */
	public function get_count_of_elements( $row, $device ) {

		$count_array = array();

		$start_content        = $this->get_elements_by_placements( $row, 'start', $device );
		$start_middle_content = $this->get_elements_by_placements( $row, 'start-middle', $device );
		$middle_content       = $this->get_elements_by_placements( $row, 'middle', $device );
		$end_middle_content   = $this->get_elements_by_placements( $row, 'end-middle', $device );
		$end_content          = $this->get_elements_by_placements( $row, 'end', $device );

		$count_array = array(
			'start'        => count( $start_content ),
			'start-middle' => count( $start_middle_content ),
			'middle'       => count( $middle_content ),
			'end-middle'   => count( $end_middle_content ),
			'end'          => count( $end_content ),
		);

		return $count_array;
	}

	/**
	 * Get content for each element in a row on the basis of their placement
	 *
	 * @param string $row
	 * @param string $placement
	 * @param string $device
	 * @return string
	 */
	public function get_single_row_elements( $row, $placement, $device ) {
		$content      = '';
		$get_elements = $this->get_elements_by_placements( $row, $placement, $device );

		if ( ! is_array( $get_elements ) || is_array( $get_elements ) && empty( $get_elements ) ) {
			return $content;
		}

		$elements = $this->get_elements()->get_items();

		foreach ( $get_elements as $element ) {
			if ( ! isset( $elements[ $element ] ) || ! class_exists( $elements[ $element ] ) ) {
				continue;
			}
			
			$_instance = new $elements[ $element ](); //Get Instance for Active Element
			$hidden = $_instance->get_mod_value('header_hide_' . $element, false); //Check if the element is hidden
			
			if( !$hidden ) $content .= $_instance->render($device); //Render if the element is visible
		}

		return $content;
	}

	/**
	 * Render collection of a row elements in the frontend
	 */
	public function output_elements_collection( $row, $device ) {

		$count_array = $this->get_count_of_elements( $row, $device );

		$count            = 0;
		$active_start_col = 1;
		$active_end_col   = 1;
		$start_col        = false;
		$mid_col          = false;
		$end_col          = false;

		if ( $count_array['start'] > 0 || $count_array['start-middle'] > 0 ) {
			$count    += 1;
			$start_col = true;
		}

		if ( $count_array['middle'] > 0 ) {
			$count += 1;
			if ( $start_col === true ) {
				$count += 1;
			}
			$mid_col = true;
		}

		if ( $count_array['end'] > 0 || $count_array['end-middle'] > 0 ) {
			if ( $count < 3 ) {
				$count += 1;
			}
			if ( $start_col === false && $mid_col === true && $count < 3 ) {
				$count += 1;
			}
			$end_col = true;
		}

		if ( $mid_col === true && $end_col === true ) {
			$start_col = true; // enable start row if middle and end exists
		}

		$args            = array(
			'row'              => $row,
			'start_col'        => $start_col,
			'mid_col'          => $mid_col,
			'end_col'          => $end_col,
			'count_array'      => $count_array,
			'active_start_col' => $active_start_col,
			'active_end_col'   => $active_end_col,
			'device'           => $device,
		);
		$path            = '/classes/Builder/templates/col-';
		$container_class = $row === 'offcanvas' ? 'rishi-drawer-inner' : 'row-wrapper';

		$row_default = \Rishi\Customizer\Helpers\Defaults::get_header_row_defaults()[ $row ];

		$rowClass  = $this->get_elements()->get_items()[ $row ];
		$_instance = new $rowClass();

		$width_type = $row !== 'offcanvas' ? $_instance->get_mod_value( 'headerRowWidth', $row_default['headerRowWidth'] ) : 'default';

		if ( $width_type !== 'default' ) {
			$container_class .= ' container-' . $width_type;
		}

		$row = $row === 'offcanvas' ? $row . '-' . $device : $row;

		$sticky_header     = get_theme_mod( 'has_sticky_header', 'no' );
		$sticky_class      = get_theme_mod( 'current_sticky_row', 'middle-row' );
		$sticky_visibility = get_theme_mod(
			'sticky_row_visibility',
			array(
				'desktop' => 'desktop',
				'mobile'  => 'mobile',
			)
		);
		$row .= $sticky_header === 'yes' && $sticky_class === $row && in_array( $device, $sticky_visibility ) ? ' sticky-row' : '';

		if ( $count > 0 && !$_instance->get_mod_value('header_hide_row', false) ) {
			?>
			<div class="rishi-header-col-<?php echo absint( $count ); ?> header-row <?php echo esc_attr( $row ); ?>">
				<div class="<?php echo esc_attr( $container_class ); ?>">
					<?php
					echo Basic::get_template_for( $path . 'start.php', $args );
					echo Basic::get_template_for( $path . 'middle.php', $args );
					echo Basic::get_template_for( $path . 'end.php', $args );
					?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Get default value.
	 *
	 * This method is used to get the default value for the Header object.
	 * It calls the header_placements_value method from the Defaults class, which returns a new Header_Placements_Default object.
	 * Then it calls the get_value method on this object to get the default value.
	 *
	 * @return array The default value for the Header object.
	 */
	public function retrieve_default_value() {
		return Defaults::header_placements_value()->get_value();
	}

}
