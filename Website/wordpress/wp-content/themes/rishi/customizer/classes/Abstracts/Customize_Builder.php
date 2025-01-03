<?php
/**
 * Abstract Customize Builder Class.
 *
 * @package Rishi
 * @subpackage Customizer
 */
namespace Rishi\Customizer\Abstracts;

use Rishi\Customizer\Builder\Elements as Builder_Elements;

abstract class Customize_Builder {

	protected $elements = null;

	/**
	 * Get the section type.
	 *
	 * @return string The section type.
	 */
	abstract public function get_builder_type();

	/**
	 * Render the builder.
	 */
	abstract public function render();

	/**
	 * Get the options for the builder.
	 *
	 * @return array The options for the builder.
	 */
	abstract public function get_options();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->set_elements();
	}

	/**
	 * Get the elements for the builder.
	 *
	 * @return object The elements for the builder.
	 */
	public function get_elements() {
		if ( is_null( $this->elements ) ) {
			$this->elements = $this->set_elements();
		}
		return $this->elements;
	}

	/**
	 * Set the elements for the builder.
	 *
	 * @return array The elements for the builder.
	 */
	protected function set_elements() {
		if ( is_null( $this->elements ) ) {
			$this->elements = new Builder_Elements( $this );
		}
		return $this->elements;
	}

	/**
	 * Get the items for the builder.
	 *
	 * @return array The items for the builder.
	 */
	public function get_items() {

		$_items = \wp_cache_get( $this->get_builder_type() . '_items', 'rishi' );

		if ( $_items ) {
			return $_items;
		}

		$items = $this->get_elements()->get_items();

		$_items = array();
		if ( is_array( $items ) ) {
			foreach ( $items as $item_id => $item ) {
				$element = new $item();

				$item_args = array(
					'id' => $item_id,
					'config' => $element->get_config(),
					'path' => '',
					'options' => $element->get_settings(),
					'is_primary' => $element->is_row_element(),
				);

				$item_args = \apply_filters( 'rishi_customizer_builder_element_args', $item_args, $item_id, $element );

				$item_args = \apply_filters( 'rishi_customizer_builder_element_args_' . $item_id, $item_args, $item_id, $element );

				$_items[] = $item_args;
			}
		}

		wp_cache_add( $this->get_builder_type() . '_items', $_items, 'rishi' );

		return \apply_filters( 'rishi_customize_builder' . __FUNCTION__, $_items );
	}

}
