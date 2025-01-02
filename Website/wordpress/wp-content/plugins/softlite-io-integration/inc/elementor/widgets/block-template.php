<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Softlite_Block_Template extends \Elementor\Widget_Base {

	public function get_name() {
		return 'softlite_block_template';
	}

	public function get_title() {
		return __( 'Block by Softlite.io', 'softlite' );
	}

	public function get_icon() {
		return 'eicon-document-file';
	}

	public function get_categories() {
		return [ 'softlite' ];
	}

	public function get_keywords() {
		return [ 'block', 'template', 'softlite', 'clonewebx' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'block_template_section',
			[
				'label' => __( 'Block Template', 'softlite' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$block_template_id_query = get_posts(
			array(
				'post_type' => 'softlite_block',
				'posts_per_page' => -1,
				'fields' => 'ids',
			)
		);

		$block_templates = [];

		foreach ($block_template_id_query as $block_template_id) {
			$block_templates[ $block_template_id ] = get_the_title($block_template_id) ? get_the_title($block_template_id) : '#' . $block_template_id;
		}

		$this->add_control(
			'block_template_id',
			[
				'type' => \Elementor\Controls_Manager::SELECT,
				'label' => __( 'Select a block template', 'softlite' ),
                'label_block' => true,
				'options' => $block_templates,
				'default' => '',
				'description' => '<div style="display: flex; flex-direction: column; align-items: flex-start;"><a href="' . admin_url() . 'post.php?" target="_blank" data-softlite-edit-block-template-link class="softlite-edit-block-template-link">Edit this block template</a><a href="' . admin_url() . 'edit.php?post_type=softlite_block" target="_blank" class="softlite-add-block-template-link">Create a new block template</a></div>',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$block_template_id = !empty( $this->get_settings_for_display('block_template_id')) ? $this->get_settings_for_display('block_template_id') : '';
		$shortcode = '[softlite_block id=' . $block_template_id . ']';
        $shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
        $block_template_id = $this->get_settings( 'block_template_id' );
        $this->add_render_attribute( 'wrapper', 'class', 'softlite-bl' );

		if ( ! empty( $block_template_id ) ) {
            $css_url = SOFTLITE_CSS_DIR . '/' . $block_template_id . '.css';
            $css_url = str_replace(WP_CONTENT_DIR, content_url(), $css_url);
			?>
			<div <?php echo $this->get_render_attribute_string( 'wrapper' ) ;?>>
                <?php echo $shortcode; ?>
                <?php 
                    if ( $editor ) {
                        echo '<link rel="stylesheet" href="' . $css_url . '">';
                    }
                ?>
			</div>
			<?php
		}
	}

    	/**
	 * Render shortcode widget as plain content.
	 *
	 * Override the default behavior by printing the shortcode instead of rendering it.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_plain_content() {
		// In plain mode, render without shortcode
		$this->print_unescaped_setting( 'block_template_id' );
	}

	/**
	 * Render shortcode widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {}
}
