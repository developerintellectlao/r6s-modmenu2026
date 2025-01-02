<?php
/**
 * Rishi_Companion\Module_Manager class
 *
 * @package Rishi_Companion
 */

namespace Rishi_Companion;

defined( 'ABSPATH' ) || exit;

/**
 * Main Module_Manager Class.
 *
 * @package Rishi_Companion
 */
class Module_Manager {

	/**
	 * Extensions.
	 *
	 * @var array
	 */
	public $extensions = array();

	/**
	 * Class Constructor.
	 *
	 * This function is hooked into 'plugins_loaded' action hook,
	 * ensuring that it runs after all plugins have been loaded.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'extensions_list' ), 20 );
	}

	/**
	 * Get the configuration.
	 *
	 * This function returns the array of extensions.
	 *
	 * @return array $this->extensions The array of extension.
	 */
	public function get_config() {
		return $this->extensions;
	}

	/**
	 * Get the list of extensions to be shown.
	 *
	 * This function creates an array of available extensions with their details such as name, description, link, and readme.
	 * It then scans the extensions directory and adds any found extensions to the list.
	 * If an extension is currently activated, its status is set to 'activated'.
	 *
	 * @return void
	 */
	public function extensions_list() {

		$sections_dir = apply_filters(
			'rishi_extensions_directory',
			array(
				'Rishi_Companion\\Modules\\' => __DIR__ . '/Modules/',
			)
		);

		$theme = apply_filters( 'rishi_customizer_get_wp_theme', wp_get_theme( get_template() ) );
		$active_extensions = $this->get_active_modules();
		$active_extensions = $theme->exists() && 'Rishi' !== $theme->get( 'Name' ) ? '' : $active_extensions;

		// Check if there are any active extensions.
		if ( is_array( $active_extensions ) && count( $active_extensions ) > 0 ) {
			foreach ( $sections_dir as $namespace => $sections_dir ) {
				$iterator = new \RecursiveDirectoryIterator( $sections_dir );
				foreach ( $iterator as $file ) {
					if ( $file->isFile() && 'php' === $file->getExtension() ) {
						$class_name = $namespace . pathinfo( $file->getFilename(), PATHINFO_FILENAME );
						if ( class_exists( $class_name ) ) {
							$instance = new $class_name();
							if ( method_exists( $instance, 'get_details' ) ) {
								$details                            = $instance->get_details();
								$this->extensions[ $details['id'] ] = array(
									'name'             => $details['name'],
									'id'               => $details['id'],
									'description'      => $details['description'],
									'link'             => $details['link'] ?? '',
									'status'           => $details['status'],
									'extension_status' => $details['extension_status'],
								);
							}
						}
					}
				}
			}
		}

		// Check if there are any activated extensions.
		if ( is_array( $this->get_active_modules() ) ) {
			foreach ( $this->get_active_modules() as $id ) {
				if ( isset( $this->extensions[ $id ] ) ) {
					$this->extensions[ $id ]['status'] = 'activated';
				}
			}
		}
	}

	/**
	 * Retrieve the list of extensions
	 *
	 * This function returns the array of extensions that are currently available.
	 *
	 * @return array $this->extensions The array of available extensions.
	 */
	public function retrieve_extensions() {
		$this->extensions_list();
		return $this->extensions;
	}

	/**
	 * Activates a specific extension.
	 *
	 * This function checks if the extension exists in the extensions array. If it does, it sets the status of the extension to 'activated'.
	 * It then retrieves the list of currently activated extensions. If no extensions are activated, it initializes an empty array.
	 * The function then adds the ID of the extension to the list of activated extensions, ensuring that the list contains unique values.
	 * It sanitizes the array of activated extensions and updates the 'rc_active_extensions' option in the WordPress database.
	 *
	 * @param string $extension_id The ID of the extension to activate.
	 * @return void
	 */
	public function enableModule( $extension_id ) {
		// Check if the extension exists.
        if ( ! isset( $this->extensions[ $extension_id ] ) ) {
            return;
        }
		// Set the status of the extension to 'activated'.
        $this->extensions[ $extension_id ]['status'] = 'activated';
		// Retrieve the list of currently activated extensions.
        $activated_extensions = $this->get_active_modules();
		// Add the ID of the extension to the list of activated extensions.
        if ( !in_array( $extension_id, $activated_extensions ) ) {
            $activated_extensions[] = $extension_id;
        }
		// Sanitize the array of activated extensions.
        $sanitized_activated_extensions = array_map( 'sanitize_text_field', $activated_extensions );

		// Update the 'rc_active_extensions' option in the WordPress database.
        update_option( 'rc_active_extensions', $sanitized_activated_extensions );
    }

	/**
	 * Handles the deactivation of an extension.
	 *
	 * This function checks if the extension exists in the list of extensions. If it does, it changes the status of the extension to 'deactivated'.
	 * It then gets the list of activated extensions and removes the current extension from the list.
	 * The list is sanitized and updated in the WordPress database.
	 *
	 * @param string $extension_id The ID of the extension to be deactivated.
	 * @return void
	 */
	public function disableModule( $extension_id ) {
		// Check if the extension exists in the list of extensions.
		if ( ! isset( $this->extensions[ $extension_id ] ) ) {
			return;
		}
		// Get the list of activated extensions.
		$activated_extensions = $this->get_active_modules();
		// Remove the current extension from the list of activated extensions.
		$activated_extensions_array = array_diff( $activated_extensions, array( $extension_id ) );
		// Sanitize the list of activated extensions.
		$sanitized_activated_extensions_array = array_map( 'sanitize_text_field', $activated_extensions_array );
		// Update the list of activated extensions in the WordPress database.
		update_option( 'rc_active_extensions', array_values( $sanitized_activated_extensions_array ) );
	}

	/**
	 * Retrieves the list of activated extensions from the database.
	 *
	 * This function checks if the 'rc_active_extensions' option in the WordPress database is empty.
	 * If it is, it updates the option with a default list of activated extensions.
	 * It then returns the list of currently activated extensions.
	 *
	 * @return array The list of activated extensions.
	 */
	public function get_active_modules() {
		// Check if the 'rc_active_extensions' option is empty.
		if ( '' === get_option( 'rc_active_extensions' ) || false === ( get_option( 'rc_active_extensions' ) ) ) {
			// Initially update the option with a default list of activated extensions.
			update_option( 'rc_active_extensions', array( 'transparent-header', 'sidebar-blocks', 'customizer-reset', 'extension_title' ) );
		}
		// Return the list of currently activated extensions.
		return get_option( 'rc_active_extensions' );
	}
}
