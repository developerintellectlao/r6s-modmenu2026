<?php

/**
 * Class Widget Four.
 */

namespace Rishi\Customizer\Footer\Elements;
use Rishi\Customizer\ControlTypes;

use Rishi\Customizer\Abstracts;

/**
 * Class Widget_Four
 */

class Widget_Four extends Abstracts\Builder_Element {
	public function get_id() {
		return 'widget-area-4';
	}

    public function get_builder_type() {
		return 'footer';
	}

	public function get_label() {
		return __( 'Widget 4', 'rishi' );
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
	 * @return array get options
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
                'controlType' => 'WidgetArea',
                'control'     => ControlTypes::WIDGET_AREA,
                'sidebarId'   => 'footer-four'
            ],
        ];
        return $options;

	}

    /**
	 * Write logic for dynamic css change for the elements
	 *
	 * @return array 
	 */
	public function dynamic_styles() {
        return [];
    }
	/**
	 * Add markup for the element
	 * @return void
	 */
	public function render( $device = 'desktop') {
        if ( !isset( $sidebar ) ) {
            $sidebar = 'footer-four';
        } ?>
        <div class="rishi-footer-widgets-four" id="rishi-footer-widgets-four">
            <?php dynamic_sidebar($sidebar); ?>
        </div>
        <?php
	}
}
