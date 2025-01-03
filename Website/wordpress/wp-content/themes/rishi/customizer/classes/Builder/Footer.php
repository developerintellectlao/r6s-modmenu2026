<?php
/**
 * Footer_Builder Class.
 *
 * This class is responsible for building the footer of the website.
 * It extends the Customize_Builder class and implements its own methods.
 *
 * @package Rishi\Customizer\Builder
 */

namespace Rishi\Customizer\Builder;

use Rishi\Customizer\Abstracts\Customize_Builder as Customize_Builder;
use Rishi\Customizer\Helpers\Basic as Basic;
use \Rishi\Customizer\Helpers\Defaults as Defaults;

class Footer extends Customize_Builder {
	private $default_value = null;

	/**
	 * Constructor for the Footer class.
	 *
	 * This method is used to set up the Footer object.
	 * It calls the parent constructor from the Customize_Builder class.
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * Get the section type.
	 *
	 * This method is used to get the section type of the Footer object.
	 * It returns a string 'footer'.
	 *
	 * @return string The section type.
	 */
	public function get_builder_type() {
		return 'footer';
	}

	/**
	 * Retrieve the default value.
	 *
	 * This method is responsible for fetching the default value of the Footer object.
	 * If the default value is already set, it simply returns it.
	 * If not, it sets the default value first and then returns it.
	 *
	 * @return array The default value.
	 */
	public function retrieve_default_value() {
		if ( isset( $this->default_value ) ) {
			return $this->default_value;
		}

		$this->default_value = array(
			'sections' => array(
				$this->construct_structure_for(
					array(
						'id'   => 'type-1',
						'rows' => array(
							'top-row'    => array(
								'columns' => array_fill( 0, 2, array() ),
							),
							'middle-row' => array(
								'columns' => array_fill( 0, 3, array() ),
							),
							'bottom-row' => array(
								'columns' => array(
									array( 'copyright' ),
								),
							),
						),
					)
				),
			),
		);

		return $this->default_value;
	}

	/**
	 * Check if the footer is enabled on the current page.
	 *
	 * @return string The footer status.
	 */
	public function enabled_on_this_page() {
		$post_id = get_the_ID();
		if ( is_home() && ! is_front_page() ) {
			$post_id = get_option( 'page_for_posts' );
		}

		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$post_id = get_option( 'woocommerce_shop_page_id' );
		}
		$disable_footer = Basic::get_meta( $post_id, 'disable_footer', 'no' );
		return $disable_footer;
	}

	/**
	 * Get the options for the footer.
	 *
	 * @return array An empty array.
	 */
	public function get_options() {
		return array();
	}

	/**
	 * Render the footer.
	 *
	 * @return string The HTML for the footer.
	 */
	public function render() {
		if ( $this->enabled_on_this_page() === 'yes' ) {
			return '';
		}
		?>
		<footer class="rishi-footer" id="rishi-footer" <?php echo rishi_print_schema( 'footer'); ?>>
			<?php
				$this->output_elements_collection( 'top-row' );
				$this->output_elements_collection( 'middle-row' );
				$this->output_elements_collection( 'bottom-row' );
			?>
		</footer>
		<?php
	}

	/**
	 * Render collection of a row elements in the frontend
	 *
	 * @param string $row The row to output elements for.
	 */
	public function output_elements_collection( $row ) {

		$row_default = Defaults::get_footer_row_defaults()[ $row ];

		$offcanvasClass = $this->get_elements()->get_items()[ $row ];
		$_instance      = new $offcanvasClass();

		$container = $_instance->get_mod_value( 'footerRowWidth', $row_default['footerRowWidth'] );

		$alignment    = $_instance->get_mod_value( 'footer_row_vertical_alignment', $row_default['footer_row_vertical_alignment'] );
		$border_width = $_instance->get_mod_value( 'footerRowTopBorderFullWidth', $row_default['footerRowTopBorderFullWidth'] );

		$class  = 'row-wrapper';
		$class .= ' vertical-' . $alignment;
		if ( $container !== 'default' ) {
			$class .= ' container-' . $container;
		}

		$border_class = 'footer-' . esc_attr( $row );

		$border_class .= $border_width === 'full-width' ? ' border-fullwidth' : '';
		$border_class .= $this->check_row_empty( $row ) ? ' hidden' : '';

		// Print the row and its elements only if the row is visible
		if ( ! $_instance->get_mod_value( 'hide_footer_row', false ) ) {
			?>
			<div class="<?php echo esc_attr( $border_class ); ?>">
				<div class="<?php echo esc_attr( $class ); ?>">
					<?php
						$this->get_column_markup( $row );
					?>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Get Footer Data on the basis of row placement.
	 *
	 * @param string $row The row to get data for.
	 * @return array $rowData The row data.
	 */
	public function get_elements_by_row( $row = 'top-row' ) {
		$footer_data  = get_theme_mod( 'footer_builder_key_placement', $this->retrieve_default_value() );
		$allDataArray = $footer_data['sections'][0]['rows'];
		$rowData      = array_filter(
			$allDataArray,
			function ( $item ) use ( $row ) {
				return $item['id'] == $row;
			}
		);

		if ( ! empty( $rowData ) ) {
			return current( $rowData )['columns'];
		}

		return array();

	}


	/**
	 * Add markup for elements on the basis of their assigned columns.
	 *
	 * @param string $row The row to get markup for.
	 */
	public function get_column_markup( $row = 'top-row' ) {
		$content     = '';
		$footer_data = $this->get_elements_by_row( $row );
		$elements    = $this->get_elements()->get_items();

		// Get row data from theme mod
		$row_default = Defaults::get_footer_row_defaults()[ $row ];
		$rowClass    = $this->get_elements()->get_items()[ $row ];
		$_instance   = new $rowClass();

		$col_dir = $_instance->get_mod_value( 'footer_row_column_direction', $row_default['footer_row_column_direction'] );

		$class  = 'col-wrapper';
		$class .= ' col-' . $col_dir;

		foreach ( $footer_data as $element ) {
			?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php
				if ( is_array( $element ) ) {
					foreach ( $element as $item ) {
						if ( ! isset( $elements[ $item ] ) || ! class_exists( $elements[ $item ] ) ) {
							continue;
						}

						$item_instance = new $elements[ $item ](); // Get Instance for active item in the array
						$hidden        = $item_instance->get_mod_value( 'footer_hide_' . $item, false ); // Check if the item is hidden

						if ( ! $hidden ) {
							$content .= $item_instance->render(); // Render if the item is visible
						}
					}
				} else {
					if ( ! isset( $elements[ $element ] ) || ! class_exists( $elements[ $element ] ) ) {
						continue;
					}

					$item_instance = new $elements[ $element ](); // Get Instance for Active Element
					$hidden        = $item_instance->get_mod_value( 'footer_hide_' . $element, false ); // Check if the element is hidden

					if ( ! $hidden ) {
						$content .= $item_instance->render(); // Render if the element is visible
					}
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Check whether the given row have any elements.
	 *
	 * @param string $row The row to check.
	 * @return boolean Whether the row is empty.
	 */
	public function check_row_empty( $row ) {
		$footer_data = $this->get_elements_by_row( $row );

		if ( count( $footer_data ) === 0 ) {
			return true;
		}

		foreach ( $footer_data as $col ) {
			if ( ! is_array( $col ) ) {
				continue;
			}

			if ( ! empty( $col ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Construct structure for the given parameters.
	 *
	 * This method is used to build a structure based on the provided arguments.
	 *
	 * @note This function could be restructured or optimized in the future
	 * @param array $args The arguments to construct structure for.
	 * @return array The constructed structure.
	 */
	public function construct_structure_for( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'   => null,
				'rows' => array(),
			)
		);

		$args['rows'] = wp_parse_args(
			$args['rows'],
			array(
				'top-row'    => array(),
				'middle-row' => array(),
				'bottom-row' => array(),
			)
		);

		$rows = [ 'top-row', 'middle-row', 'bottom-row' ];
		$result_rows = [];

		foreach( $rows as $row ) {
			$result_rows[] = $this->get_bar_structure(
				array_merge(
					array(
						'id' => $row,
					),
					$args['rows'][$row]
				)
				);
		}

		return array(
			'id'       => $args['id'],
			'rows'     => $result_rows,
			'items'    => array(),
			'settings' => array(),
		);
	}

	/**
	 * Construct bar structure for the given parameters.
	 *
	 * This method is used to build a bar structure based on the provided arguments.
	 *
	 * @note This function could be restructured or optimized in the future
	 * @param array $args The arguments to construct bar structure for.
	 * @return array The constructed bar structure.
	 */
	private function get_bar_structure( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'id'      => null,
				'columns' => array_fill( 0, 3, array() ),
			)
		);

		return array(
			'id'      => $args['id'],
			'columns' => $args['columns'],
		);
	}
}
