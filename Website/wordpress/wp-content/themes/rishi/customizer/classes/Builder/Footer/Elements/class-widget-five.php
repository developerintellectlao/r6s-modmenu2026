<?php

/**
 * Class Widget Five.
 */

namespace Rishi\Customizer\Footer\Elements;
use Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts;

/**
 * Class Widget_Five
 */

class Widget_Five extends Abstracts\Builder_Element {
	public function get_id() {
		return 'widget-area-5';
	}

    public function get_builder_type() {
		return 'footer';
	}

	public function get_label() {
		return __( 'Widget 5', 'rishi' );
	}

	public function config() {
		return array(
			'name'          => $this->get_label(),
			'visibilityKey' => 'footer_hide_' . $this->get_id(),
		);
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return void
	 */
	public function get_options() {

        $options = [
            'footer_hide_' . $this->get_id() => [
                'label'               => false,
                'control'             => ControlTypes::HIDDEN,
                'value'               => false,
                'disableRevertButton' => true,
                'help'                => __('Hide', 'rishi'),
            ],
            'widget' => [
                'control' => ControlTypes::WIDGET_AREA,
                'sidebarId' => 'footer-five'
            ],
        ];
        return $options;
	}

    /**
	 * Write logic for dynamic css change for the elements
	 *
	 * @return array dynamic styles
	 */
	public function dynamic_styles() {
        return [];
    }
	/**
	 * Add markup for the element
	 * @return void
	 */
	public function render( $device = 'desktop') {
        if (!isset($sidebar)) {
            $sidebar = 'footer-five';
        } ?>
        <div class="rishi-footer-widgets-five" id="rishi-footer-widgets-five">
            <?php dynamic_sidebar($sidebar); ?>
        </div>
        <?php
	}
}
