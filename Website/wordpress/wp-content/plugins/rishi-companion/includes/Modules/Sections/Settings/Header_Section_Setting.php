<?php
/**
 * Header Section Extension Setting.
 *
 * This class provides the functionality for the Header Section extension setting.
 *
 * @package Rishi_Companion\Modules\Sections\Settings
 */

namespace Rishi_Companion\Modules\Sections\Settings;

use Rishi\Customizer\Settings\Header_Section_Setting as Default_Header_Section_Setting;
use Rishi\Customizer\ControlTypes;

class Header_Section_Setting extends Default_Header_Section_Setting {

	 /**
     * Add settings for the extension.
     */
    public function add_settings() {
        $this->add_setting( 'sticky_header', array(
            'label' => __( 'Sticky Header', 'rishi-companion' ),
            'control' => ControlTypes::PANEL,
            'divider' => 'bottom',
        ) );

        $this->add_sticky_header_settings();

        $this->add_setting( 'transparent_header', array(
            'label' => __( 'Transparent Header', 'rishi-companion' ),
            'control' => ControlTypes::PANEL,
        ) );

        $this->add_transparent_header_settings();

        parent::add_settings();
    }

	 /**
     * Add settings for the sticky header.
     */
	protected function add_sticky_header_settings() {

		$this->add_setting( 'has_sticky_header', array(
			'label' => __( 'Enable Sticky Header', 'rishi-companion' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value' => 'no',
			'divider' => 'bottom',
			'parent' => 'sticky_header'
		) );

		$this->add_setting('sticky_logo', array(
			'label'        => __( 'Sticky Logo', 'rishi-companion' ),
			'control'      => ControlTypes::IMAGE_UPLOADER,
			'value'        => [],
			'emptyLabel'   => __( 'Select Logo', 'rishi-companion' ),
			'filledLabel'  => __( 'Change Logo', 'rishi-companion' ),
			'conditions' => array(
				'has_sticky_header' => 'yes'
			),
			'parent' => 'sticky_header'
		) );

		$this->add_setting( 'current_sticky_row', array(
			'label' => __( 'Choose Sticky Header', 'rishi-companion' ),
			'control' => ControlTypes::INPUT_SELECT,
			'value' => 'middle-row',
			'view' => 'text',
			'design' => 'block',
			'divider' => 'bottom',
			'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
				array(
					'top-row' => __( 'Top Row', 'rishi-companion' ),
					'middle-row' => __( 'Main Row', 'rishi-companion' ),
					'bottom-row' => __( 'Bottom Row', 'rishi-companion' ),
				)
			),
			'conditions' => array(
				'has_sticky_header' => 'yes'
			),
			'parent' => 'sticky_header'
		) );

		$this->add_setting( 'sticky_row_box_shadow', array(
			'label' => __( 'Box Shadow', 'rishi-companion' ),
			'control' => ControlTypes::BOX_SHADOW,
			'design' => 'inline',
			'divider' => 'bottom',
			'value' => \Rishi\Customizer\Helpers\Box_Shadow_CSS::box_shadow_value( array(
				'enable' => false,
				'inset' => false,
				'h_offset' => '0px',
				'v_offset' => '10px',
				'blur' => '20px',
				'spread' => '0px',
				'color' => 'rgba(41, 51, 61, 0.1)',
			) ),
			'conditions' => array(
				'has_sticky_header' => 'yes'
			),
			'parent' => 'sticky_header'

		) );

		$this->add_setting( 'sticky_row_visibility', array(
			'label' => __( 'Enable On', 'rishi-companion' ),
			'control' => ControlTypes::VISIBILITY,
			'design' => 'block',
			'divider' => 'bottom',
			'value' => array(
				'desktop' => 'desktop',
				'mobile' => 'mobile',
			),
			'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys( array(
				'desktop' => __( 'Desktop', 'rishi-companion' ),
				'mobile' => __( 'Mobile', 'rishi-companion' ),
			) ),
			'conditions' => array(
				'has_sticky_header' => 'yes'
			),
			'parent' => 'sticky_header'

		) );

	}

	/**
     * Add settings for the transparent header.
     */
	protected function add_transparent_header_settings() {
		$this->add_setting( 'has_transparent_header', array(
			'label' => __( 'Enable Transparent Header', 'rishi-companion' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value' => 'no',
			'divider' => 'bottom',
			'parent' => 'transparent_header'
		) );

		$this->add_setting('transparent_logo', array(
			'label'             => __( 'Transparent Logo', 'rishi-companion' ),
			'control'           => ControlTypes::IMAGE_UPLOADER,
			'value'             => [],
			'emptyLabel'        => __( 'Select Logo', 'rishi-companion' ),
			'filledLabel'       => __( 'Change Logo', 'rishi-companion' ),
			'conditions' => array(
				'has_transparent_header' => 'yes'
			),
			'parent' => 'transparent_header'
		) );

		$this->add_setting( 'transparent_header_locations', array(
			'label' => __( 'Transparent Header Locations', 'rishi-companion' ),
			'control' => ControlTypes::INPUT_SELECT,
			'divider' => 'top',
			'isMultiple' => true,
			'value' => [],
			'view' => 'text',
			'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys( \Rishi\Customizer\Helpers\Basic::rishi_get_pages() ),
			'conditions' => array(
				'has_transparent_header' => 'yes'
			),
			'parent' => 'transparent_header'
		) );

		$this->add_setting( 'disable_transparent_header', array(
			'label' => __( 'Disable on Mobile', 'rishi-companion' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value' => 'no',
			'divider' => 'bottom',
			'conditions' => array(
				'has_transparent_header' => 'yes'
			),
			'parent' => 'transparent_header'
		) );
	}
}
