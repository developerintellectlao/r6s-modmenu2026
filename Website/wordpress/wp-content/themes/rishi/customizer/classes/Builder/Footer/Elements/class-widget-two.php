<?php
/**
 * Class Widget Two.
 */
namespace Rishi\Customizer\Footer\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Widget_Two extends Abstracts\Builder_Element {
	public function get_id() {
		return 'widget-area-2';
	}

    public function get_builder_type() {
		return 'footer';
	}
	public function get_label() {
		return __( 'Widget 2', 'rishi' );
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
                'sidebarId' => 'footer-two'
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
            $sidebar = 'footer-two';
        } ?>
        <div class="rishi-footer-widgets-two" id="rishi-footer-widgets-two">
            <?php dynamic_sidebar( $sidebar ); ?>
        </div>
        <?php
	}
}
