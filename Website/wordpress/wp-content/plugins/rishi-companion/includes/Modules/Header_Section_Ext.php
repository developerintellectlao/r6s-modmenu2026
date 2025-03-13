<?php
/**
 * Advanced Header Extension
 *
 * This class provides the functionality for the Advanced Header extension.
 *
 * @package Rishi_Companion\Modules
 */

namespace Rishi_Companion\Modules;

/**
 * Header Section Extension Class.
 */
class Header_Section_Ext {
	/**
	 * The ID of the extension.
	 *
	 * @var string
	 */
	private $id;

	/**
	 * The name of the extension.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The description of the extension.
	 *
	 * @var string
	 */
	private $description;

	/**
	 * The link to the extension.
	 *
	 * @var string
	 */
	private $link;

	/**
	 * The status of the extension.
	 *
	 * @var string
	 */
	private $status;

	/**
	 * The status of the extension.
	 *
	 * @var string
	 */
	private $extension_status;

	/**
	 * Initialize the properties of the extension.
	 */
	public function __construct() {
		$this->initialize_properties();
		$active_extensions = get_option( 'rc_active_extensions', array() );
		if ( in_array( 'transparent-header', $active_extensions, true ) ) {
			new Helpers\Header_Section();
		}
	}

	/**
	 * Set the properties of the extension.
	 */
	public function initialize_properties() {
		$this->id               = 'transparent-header';
		$this->name             = __( 'Advanced Header', 'rishi-companion' );
		$this->description      = __( 'Enable this extension in order to enable transparent and sticky header feature.', 'rishi-companion' );
		$this->link             = 'customize.php?autofocus[section]=header';
		$this->status           = 'deactivated';
		$this->extension_status = 'free';
	}

	/**
	 * Get the ID of the extension.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the name of the extension.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get the description of the extension.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get the link to the extension.
	 *
	 * @return string
	 */
	public function get_link() {
		return $this->link;
	}

	/**
	 * Get the status of the extension.
	 *
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Get the status of the extension.
	 *
	 * @return string
	 */
	public function get_extension_status() {
		return $this->extension_status;
	}

	/**
	 * Get the details of the extension.
	 *
	 * @return array
	 */
	public function get_details() {
		return array(
			'id'               => $this->get_id(),
			'name'             => $this->get_name(),
			'description'      => $this->get_description(),
			'link'             => $this->get_link(),
			'status'           => $this->get_status(),
			'extension_status' => $this->get_extension_status(),
		);
	}
}
