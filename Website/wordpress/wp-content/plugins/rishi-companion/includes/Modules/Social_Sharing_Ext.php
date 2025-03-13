<?php
/**
 * Social Sharing Extension
 *
 * This class provides the functionality for the Social Sharing extension.
 *
 * @package Rishi_Companion\Modules
 */

namespace Rishi_Companion\Modules;

/**
 * Social Sharing Extension Class.
 */
class Social_Sharing_Ext {
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
	 * Initialize the properties of the extension and check if the extension is active.
	 */
	public function __construct() {
		$this->initialize_properties();
		$active_extensions = get_option( 'rc_active_extensions', array() );
		if ( in_array( 'socialshare', $active_extensions, true ) ) {
			new Helpers\Social_Sharing();
		}
	}

	/**
	 * Set the properties of the extension.
	 */
	private function initialize_properties() {
		$this->id               = 'socialshare';
		$this->name             = __( 'Social Sharing', 'rishi-companion' );
		$this->description      = __( 'Enable this extension to add social sharing option in single post.', 'rishi-companion' );
		$this->link             = 'customize.php?autofocus[section]=single-section';
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
