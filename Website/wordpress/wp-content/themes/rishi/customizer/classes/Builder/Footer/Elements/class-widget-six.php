<?php
/**
 * Class Widget Six.
 */
namespace Rishi\Customizer\Footer\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Widget_Six extends Abstracts\Builder_Element {
	public function get_id() {
		return 'widget-area-6';
	}

	public function get_label() {
		return __( 'Widget 6', 'rishi' );
	}

    public function get_builder_type() {
		return 'footer';
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
                'control' => ControlTypes::WIDGET_AREA,
                'sidebarId' => 'footer-six'
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
        if ( !isset( $sidebar ) ) {
            $sidebar = 'footer-six';
        } ?>
        <div class="rishi-footer-widgets-six" id="rishi-footer-widgets-six">
            <?php dynamic_sidebar( $sidebar ); ?>
        </div>
        <?php
	}
}
