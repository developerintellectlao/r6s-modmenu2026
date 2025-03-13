<?php
/**
 * Heading for Extensions Section
 *
 * This class provides the functionality for the Extensions.
 *
 * @package Rishi_Companion\Modules\Sections
 */

namespace Rishi_Companion\Modules\Sections;

use \Rishi\Customizer\Abstracts\Customize_Section;

class Extension_Title extends Customize_Section {
    /**
     * The priority of the extension.
     *
     * @var int
     */
    protected $priority = 2;

    /**
     * The ID of the extension.
     *
     * @var string
     */
    protected $id = 'extension-panel';

    /**
     * Get the type of the extension.
     *
     * @return string
     */
    public function get_type() {
        return self::GROUP_TITLE;
    }

    /**
     * Get the order of the extension.
     *
     * @return int
     */
    public static function get_order() {
        return 50;
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
     * Check if the extension is enabled.
     *
     * @return bool
     */
    public static function is_enabled() {
        $active_extensions = get_option('rc_active_extensions', array());
        $extensions_to_check = array('customizer-reset', 'performance', 'progress-bar', 'advanced-blog', 'code-snippets', 'portfolio', 'notification-bar');
        // Check if any of the values exist in the $active_extensions array
        if ( array_intersect( $extensions_to_check, $active_extensions ) ) {
            return true;
        }
        return false;
    }

    /**
     * Get the default values of the extension.
     *
     * @return array
     */
    protected function get_defaults() {
        return array();
    }

    /**
     * Get the title of the extension.
     *
     * @return string
     */
    public function get_title() {
        return '';
    }

    /**
     * Get the dynamic styles of the extension.
     *
     * @param array $styles
     * @return array
     */
    public function get_dynamic_styles($styles) {
        return array();
    }
}
