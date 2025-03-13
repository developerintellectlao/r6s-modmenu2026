<?php
/**
 * Advanced Header Extension
 *
 * This class provides the functionality for the Advanced Header extension.
 *
 * @package Rishi_Companion\Modules\Sections
 */

namespace Rishi_Companion\Modules\Sections;

use Rishi\Customizer\Sections\Header_Section as Default_Header_Section;

class Header_Section extends Default_Header_Section {
    /**
     * Setup the section.
     */
    protected function setup() {
        $this->settings = new \Rishi_Companion\Modules\Sections\Settings\Header_Section_Setting();
    }

    /**
     * Check if the extension is enabled.
     *
     * @return bool
     */
    public static function is_enabled() {
        $active_extensions = get_option('rc_active_extensions', array());

        if (in_array('transparent-header', $active_extensions)) {
            return true;
        }

        return false;
    }
}
