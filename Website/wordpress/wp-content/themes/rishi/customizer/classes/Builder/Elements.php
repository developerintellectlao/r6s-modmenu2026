<?php
/**
 * Builder Elements.
 *
 * @package Rishi\Customizer\Builder
 */
namespace Rishi\Customizer\Builder;

use \Rishi\Customizer\Dynamic_Styles;

/**
 * Class Builder_Elements
 */
class Elements {

	/**
	 * Items array.
	 *
	 * @var array
	 */
	public $items = array();

	/**
	 * Builder object.
	 *
	 * @var null|object
	 */
	public $builder = null;

	/**
	 * Elements constructor.
	 *
	 * @param object $builder Builder object.
	 */
	public function __construct( $builder ) {
		$this->builder = $builder;

		$this->set_items( __DIR__ . '/' . ucfirst( $this->builder->get_builder_type() ) . '/Elements' );

		\add_action( 'rishi_customizer_dynamic_styles_collect_css', array( $this, 'get_dynamic_styles' ) );
	}

	/**
	 * Get dynamic styles.
	 *
	 * @param Dynamic_Styles $dynamic_styles_object An instance of the Dynamic_Styles calss.
	 * This object is used to add dynamic styles to the elements.
	 */
	public function get_dynamic_styles( Dynamic_Styles $dynamic_styles_object ) {
		$elements = $this->items;
		foreach ( $elements as $element ) {
			$element_object = new $element();
			$dynamic_styles = $element_object->dynamic_styles();
			if ( ! is_array( $dynamic_styles ) ) {
				continue;
			}
			foreach ( $dynamic_styles as $selector => $dynamic_style ) {
				$dynamic_styles_object->add( $selector, $dynamic_style );
			}
		}
	}

	/**
	 * Get element class name.
	 *
	 * @param string $file_name File name.
	 * @return string Class name.
	 */
	private function get_element_class_name( $file_name ) {
		$file_name = pathinfo( $file_name, PATHINFO_FILENAME );
		return '\\Rishi\\Customizer\\' . ucfirst( $this->builder->get_builder_type() ) . '\\Elements\\' . str_replace( array( 'class-', '-' ), array( '', '_' ), $file_name );
	}

	/**
	 * Set items.
	 *
	 * @param string $dir Directory path.
	 */
	private function set_items( $dir ) {
		$iterator = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $dir ) );
		foreach ( $iterator as $file ) {
			if ( $file->isDir() ) {
				continue;
			}
			$path = $file->getPathname();
			if ( pathinfo( $path, PATHINFO_EXTENSION ) === 'php' ) {
				require_once $path;
				$file_name  = pathinfo( $path, PATHINFO_FILENAME );
				$class_name = $this->get_element_class_name( $file_name );
				if ( class_exists( $class_name ) ) {
					if ( ! $class_name::is_enabled() ) {
						continue;
					}
					$this->items[ str_replace( 'class-', '', $file_name ) ] = $class_name;
				}
			}
		}
	}

	/**
	 * Get item.
	 *
	 * @param string $item Item name.
	 * @return object New item object.
	 */
	public function get_item( $item ) {
		return new $this->items[ $item ]();
	}

	/**
	 * Get items.
	 *
	 * @return array Items array.
	 */
	public function get_items() {
		return $this->items;
	}

}
