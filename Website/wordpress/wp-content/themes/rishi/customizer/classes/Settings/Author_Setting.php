<?php
/**
 * Author Customizer Setting
 */
namespace Rishi\Customizer\Settings;

use Rishi\Customizer\Abstracts\Customize_Settings;
use \Rishi\Customizer\ControlTypes;

class Author_Setting extends Customize_Settings {

	protected function add_settings() {

		$author_defaults = self::get_author_default_value();

		$this->add_setting('author_title_panel', array(
			'label'         => __( 'Page Title', 'rishi' ),
			'control'          => ControlTypes::PANEL,
			'innerControls' => array(
				\Rishi\Customizer\Helpers\Basic::uniqid() => array(
					'title'   => __( 'General', 'rishi' ),
					'control'    => ControlTypes::TAB,
					'options' => array(
						'ed_author_header'       => array(
							'label'   => __( 'Enable Page Header', 'rishi' ),
							'control' => ControlTypes::INPUT_SWITCH,
							'value'   => 'yes',

						),
						'breadcrumbs_ed_author' => array(
							'label' => __( 'Breadcrumb', 'rishi' ),
							'control'  => ControlTypes::INPUT_SWITCH,
							'value' => $author_defaults['breadcrumbs_ed_author'],
							'divider' => 'top',

							'conditions' => [
								'ed_author_header' => 'yes'
							]
						),
						'author_page_label'     => array(
							'label'   => __( 'Author Label', 'rishi' ),
							'control' => ControlTypes::INPUT_TEXT,
							'design'  => 'block',
							'divider' => 'top',
							'value'   => __( 'By', 'rishi' ),
							'conditions' => [
								'ed_author_header' => 'yes'
							]
						),
						'author_page_avatar_ed' => array(
							'label'   => __( 'Show Avatar', 'rishi' ),
							'control' => ControlTypes::INPUT_SWITCH,
							'value'   => 'yes',
							'divider' => 'top',
							'conditions' => [
								'ed_author_header' => 'yes'
							]
						),

						'author_page_avatar_size' => array(
							'label'      => __( 'Avatar Size', 'rishi' ),
							'control'    => ControlTypes::INPUT_SLIDER,
							'value'      => array(
								'desktop' => '142px',
								'tablet'  => '142px',
								'mobile'  => '142px',
							),
							'responsive' => true,
							'divider' => 'top',
							'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
								[ 'unit' => 'px', 'min' => 80, 'max' => 200 ],
							] ),
							'conditions' => [
								'ed_author_header' => 'yes',
								'author_page_avatar_ed' => 'yes'
							]
						),

						'author_page_avatar_types' => array(
							'label'   => __( 'Avatar Shape', 'rishi' ),
							'control'    => ControlTypes::INPUT_RADIO,
							'value'   => 'circle',
							'divider' => 'top',
							'attr'    => array( 'data-type' => 'author' ),
							'choices' => array(
								'circle' => __( 'Circle', 'rishi' ),
								'square' => __( 'Square', 'rishi' ),
							),
							'conditions' => [
								'ed_author_header' => 'yes',
								'author_page_avatar_ed' => 'yes'
							]
						),

						'author_page_alignment' => array(
							'control'    => ControlTypes::INPUT_RADIO,
							'label'      => __( 'Horizontal Alignment', 'rishi' ),
							'value'      => 'left',
							'divider'    => 'top',
							'attr'       => array( 'data-type' => 'alignment' ),
							'design'     => 'block',
							'choices'    => array(
								'left'   => __('Left', 'rishi'),
								'center' => __('Center', 'rishi'),
								'right'  => __('Right', 'rishi'),
							),
							'conditions' => [
								'ed_author_header' => 'yes'
							]
						),
						'author_page_search_ed' => array(
							'label' => __( 'Show Post Counts', 'rishi' ),
							'control'  => ControlTypes::INPUT_SWITCH,
							'divider' => 'top',
							'value' => 'yes',
							'conditions' => [
								'ed_author_header' => 'yes'
							]
						),

						'author_page_author_margin' => array(
							'label'   => __( 'Bottom Spacing', 'rishi' ),
							'control' => ControlTypes::INPUT_SLIDER,
							'value'   => '30px',
							'divider' => 'top:bottom',
							'units'   => \Rishi\Customizer\Helpers\Basic::get_units( [
								[ 'unit' => 'px', 'min' => 0, 'max' => 300 ],
							] ),
							'conditions' => [
								'ed_author_header'       => 'yes',
								'author_page_search_ed' => 'yes'
							]
						),

						'author_page_margin'    => array(
							'label'      => __( 'Vertical Spacing', 'rishi' ),
							'control'       => ControlTypes::INPUT_SLIDER,
							'value'      => array(
								'desktop' => '30px',
								'tablet'  => '20px',
								'mobile'  => '20px',
							),
							'responsive' => true,
							'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
								[ 'unit' => 'px', 'min' => 0, 'max' => 300 ],
							] ),
							'conditions' => [
								'ed_author_header' => 'yes'
							]
						),

					),
				),
				\Rishi\Customizer\Helpers\Basic::uniqid() => array(
					'title'   => __( 'Design', 'rishi' ),
					'control' => ControlTypes::TAB,
					'options' => array(
						'author_page_header_content_background' => array(
							'label'           => __( 'Content Area Background', 'rishi' ),
							'control'         => ControlTypes::COLOR_PICKER,
							'colorPalette'	  => true,
							'design'          => 'inline',
							'divider'         => 'bottom',
							'value'           =>  array(
								'default' => array(
									'color' => 'var(--paletteColor7)',
								),
							),
							'pickers' => array(
								array(
									'title' => __( 'Initial', 'rishi' ),
									'id'    => 'default',
								),
							),
						),
						'author_page_color' => array(
							'label'           => __( 'Font Color', 'rishi' ),
							'control'         => ControlTypes::COLOR_PICKER,
							'design'          => 'inline',
							'colorPalette'	  => true,
							'divider'         => 'bottom',
							'value'           => array(
								'default' => array(
									'color' => 'var(--paletteColor1)',
								),
							),
							'pickers'         => array(
								array(
									'title' => __( 'Initial', 'rishi' ),
									'id'    => 'default',
								),
							),
						),
					),
				),
			),
		));

		$this->add_setting('author_page_layout', array(
			'label'   => __( 'Author Page Layout', 'rishi' ),
			'help'    => __( 'Choose sidebar layout for Author page.', 'rishi' ),
			'control'    => ControlTypes::IMAGE_PICKER,
			'value'   => $author_defaults['author_page_layout'],
			'divider' => 'top',
			'choices' => $this->get_page_layout_choices(),
		));

		$this->add_setting(\Rishi\Customizer\Helpers\Basic::uniqid(), [
			'label'   => __( 'POST ELEMENT', 'rishi' ),
			'control' => ControlTypes::TITLE,
		]);

		$this->add_setting('author_post_structure', array(
			'control'     => ControlTypes::LAYERS,
			'attr'     => array( 'data-layers' => 'title-elements' ),
			'design'   => 'block',
			'value'    => \Rishi\Customizer\Helpers\Defaults::blogpost_structure_defaults(),

			'settings' => array(
				'featured_image' => array(
					'label'   => __( 'Featured Image', 'rishi' ),
					'options'  => array(
						'featured_image_ratio' => array(
							'label'   => __( 'Image Ratio', 'rishi' ),
							'control'    => ControlTypes::INPUT_SELECT,
							'design'  => 'inline',
							'value'   => 'auto',
							'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
								array(
									'auto' => __('Original', 'rishi'),
									'1/1'  => __( 'Square - 1:1', 'rishi'),
									'4/3'  => __('Standard - 4:3', 'rishi'),
									'3/4'  => __('Portrait - 3:4', 'rishi'),
									'3/2'  => __('Classic - 3:2', 'rishi'),
									'2/3'  => __( 'Classic Portrait - 2:3', 'rishi'),
									'16/9' => __('Wide - 16:9', 'rishi'),
									'9/16' => __('Tall - 9:16', 'rishi'),
								)
							),
						),
						'featured_image_scale' => array(
							'label'   => __( 'Image Scale', 'rishi' ),
							'control' => ControlTypes::INPUT_SELECT,
							'design'  => 'inline',
							'divider' => 'top',
							'value'   => 'contain',
							'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
								array(
									'contain' => __('Contain', 'rishi'),
									'cover'   => __('Cover', 'rishi'),
									'fill'    => __('Fill', 'rishi'),
								)
							),
						),
						'featured_image_size' => array(
							'label'   => __( 'Image Size', 'rishi' ),
							'control' => ControlTypes::INPUT_SELECT,
							'design'  => 'inline',
							'divider' => 'top',
							'value'   => 'full',
							'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys( \Rishi\Customizer\Helpers\Basic::get_all_image_sizes() ),
						),
						'featured_image_visibility' => [
							'label'   => __( 'Image Visibility', 'rishi' ),
							'control' => ControlTypes::VISIBILITY,
							'design'  => 'block',
							'divider' => 'top',
							'value' => [
								'desktop' => 'desktop',
								'tablet'  => 'tablet',
								'mobile'  => 'mobile',
							],
							'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys([
								'desktop' => __( 'Desktop', 'rishi' ),
								'tablet' => __( 'Tablet', 'rishi' ),
								'mobile' => __( 'Mobile', 'rishi' ),
							]),
						],
					),
				),
				'categories'    => array(
					'label'   => __( 'Categories', 'rishi' ),
					'options' => array(
						'separator' => array(
							'label'   => __( 'Separator', 'rishi' ),
							'control' => ControlTypes::INPUT_SEPARATOR,
							'value'   => 'dot',
							'design'  => 'block',
							'choices' => array(
								'dot'          => __('Dot', 'rishi'),
								'normal-slash' => __('Normal Slash', 'rishi'),
								'pipe'         => __('Pipe', 'rishi'),
								'back-slash'   => __('Back Slash', 'rishi'),
							),
						),
					),
				),
				'custom_title'   => array(
					'label'   => __( 'Title', 'rishi' ),
					'options' => array(
						'heading_tag' => array(
							'label'   => __( 'Heading tag', 'rishi' ),
							'control' => ControlTypes::INPUT_SELECT,
							'value'   => 'h2',
							'design'  => 'inline',
							'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
								array(
									'h1' => 'H1',
									'h2' => 'H2',
									'h3' => 'H3',
									'h4' => 'H4',
									'h5' => 'H5',
									'h6' => 'H6',
								)
							),
						),
						'font_size'   => array(
							'label'   => __( 'Font Size', 'rishi' ),
							'control' => ControlTypes::INPUT_SLIDER,
							'divider' => 'top',
							'units'   => \Rishi\Customizer\Helpers\Basic::get_units(
								array(
									array(
										'unit' => 'px',
										'min'  => 0,
										'max'  => 150,
									),
								)
							),
							'value' => [
								'desktop' => '30px',
								'tablet'  => '24px',
								'mobile'  => '22px',
							],
							'responsive' => true,
						),
					),
				),
				'custom_meta'    => array(
					'label'   => __( 'Post Meta', 'rishi' ),
					'options' => array(
						'archive_postmeta' => [
							'label'      => __( 'Post Meta', 'rishi' ),
							'control'    => ControlTypes::INPUT_SELECT,
							'isMultiple' => true,
							'value'      => array('author','published-date','comments'),
							'view'       => 'text',
							'choices'    => \Rishi\Customizer\Helpers\Basic::ordered_keys( [
								'author'         => __( 'Author', 'rishi' ),
								'comments'       => __( 'Comments', 'rishi' ),
								'reading-time'   => __( 'Reading Time', 'rishi' ),
								'published-date' => __( 'Published Date', 'rishi' ),
								'updated-date'   => __( 'Updated Date', 'rishi' )
							] ),
						],
						'divider_divider' => [
							'label'   => __( 'Category Separator', 'rishi' ),
							'control'    => ControlTypes::INPUT_SEPARATOR,
							'value'   => 'dot',
							'design'  => 'block',
							'choices' => array(
								'dot'          => __('Dot', 'rishi'),
								'normal-slash' => __('Normal Slash', 'rishi'),
								'pipe'         => __('Pipe', 'rishi'),
								'back-slash'   => __('Back Slash', 'rishi'),
							),
						],
						'has_author_avatar' => array(
							'label'   => __( 'Show Avatar', 'rishi' ),
							'control' => ControlTypes::INPUT_SWITCH,
							'divider' => 'top',
							'value'   => 'no'
						),
						'avatar_size'   => array(
							'label'   => __( 'Avatar Size', 'rishi' ),
							'control' => ControlTypes::INPUT_SLIDER,
							'units'   => \Rishi\Customizer\Helpers\Basic::get_units(
								array(
									array(
										'unit' => 'px',
										'min'  => 0,
										'max'  => 300,
									),
								)
							),
							'value'      => '34px',
							'responsive' => false,
							'divider'	 => 'top'
						),
						'label' => array(
							'label'   => __( 'Label', 'rishi' ),
							'control' => ControlTypes::INPUT_TEXT,
							'design'  => 'block',
							'divider' => 'top',
							'value'   => __( 'By', 'rishi' ),
						),
						'words_per_minute'   => array(
							'label'      => __( 'Words Per Minute', 'rishi' ),
							'control'    => ControlTypes::INPUT_NUMBER,
							'design'     => 'inline',
							'value'      => 200,
							'min'        => 100,
							'max'        => 400,
							'divider'    => 'top',
							'responsive' => false,
						),
						'show_updated_date_label' => array(
							'label'   => __( 'Show Updated Date Label', 'rishi' ),
							'control' => ControlTypes::INPUT_SWITCH,
							'divider' => 'top',

							'value'   => 'yes'
						),
						'updated_date_label' => array(
							'label'   => __( 'Label', 'rishi' ),
							'control' => ControlTypes::INPUT_TEXT,
							'design'  => 'block',
							'divider' => 'top',
							'value'   => __( 'Updated On', 'rishi' ),
						),
					)
				),
				'excerpt'        => array(
					'label'   => __( 'Excerpt', 'rishi' ),
					'options' => array(
						'post_content' => array(
							'label'   => __( 'Post Content', 'rishi' ),
							'control' => ControlTypes::INPUT_SELECT,
							'value'   => 'excerpt',
							'design'  => 'inline',
							'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
								array(
									'content' => __( 'Content', 'rishi' ),
									'excerpt' => __( 'Excerpt', 'rishi' ),
								)
							),
						),
						'excerpt_length' => array(
							'label'   => __( 'Length', 'rishi' ),
							'help'    => __( 'Choose the number of words to display in excerpt.', 'rishi' ),
							'control' => ControlTypes::INPUT_NUMBER,
							'design'  => 'inline',
							'divider' => 'top',
							'min'     => 10,
							'max'     => 100,
						),
					),
				),
				'divider'        => array(
					'label'   => __( 'Divider', 'rishi' ),
					'options' => array(
						'divider_margin' => array(
							'label'      => __( 'Margin', 'rishi' ),
							'control'    => ControlTypes::INPUT_SPACING,
							'responsive' => true,
							'value' => [
								'desktop' => [
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '20',
									'unit'   => 'px',
								],
								'tablet'  => [
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '20',
									'unit'   => 'px',
								],
								'mobile'  => [
									'linked' => true,
									'top'    => '0',
									'left'   => '0',
									'right'  => '0',
									'bottom' => '20',
									'unit'   => 'px',
								],
							],
							'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
						),
					),
				),
				'read_more'      => array(
					'label'   => __( 'Read More Button', 'rishi' ),
					'options' => array(
						'button_type'     => array(
							'label'   => false,
							'control'    => ControlTypes::INPUT_RADIO,
							'hasRevertButton' => false,
							'choices' => array(
								'simple' => __( 'Simple', 'rishi' ),
								'button' => __( 'Button', 'rishi' ),
							),
						),
						'read_more_text'  => array(
							'label'  => __( 'Text', 'rishi' ),
							'control'   => ControlTypes::INPUT_TEXT,
							'value'   => __( 'Read More', 'rishi' ),
							'design' => 'inline',
							'divider' => 'top',
						),
						'read_more_arrow' => array(
							'label'   => __( 'Show Arrow', 'rishi' ),
							'control' => ControlTypes::INPUT_SWITCH,
							'divider' => 'top',
							'value' => 'yes',
						),
					),
				)
			),
		));

		$this->add_setting('author_post_navigation', array(
			'label'   => __( 'Posts Navigation', 'rishi' ),
			'control' => ControlTypes::INPUT_SELECT,
			'value'   => $author_defaults['author_post_navigation'],
			'view'    => 'text',
			'design'  => 'block',
			'divider' => 'top',
			'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
				array(
					'numbered'        => __( 'Numbered', 'rishi' ),
					'infinite_scroll' => __( 'Infinite Scroll', 'rishi' ),
				)
			),
		));

		$this->add_setting('author_sidebar_layout', array(
			'label'   => __( 'Sidebar Layout', 'rishi' ),
			'control' => ControlTypes::IMAGE_PICKER,
			'value'   => $author_defaults['author_sidebar_layout'],
			'attr'    => array(
				'data-columns' => '2',
			),
			'help'    => __( 'Choose sidebar layout for Author page.', 'rishi' ),
			'divider' => 'top',
			'choices' => array(
				'default-sidebar' => array(
					'src'   => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="120" height="120" viewBox="0 0 219 119"><g id="Default" clip-path="url(#clip-Default)"><g id="Group_5761" data-name="Group 5761" transform="translate(8842 3234)"><g id="Group_5759" data-name="Group 5759" transform="translate(-8919.772 -3250)"><text id="Default-2" data-name="Default" transform="translate(187.772 93)" font-size="43" font-family="SegoeUI-Semibold, Segoe UI" font-weight="600" letter-spacing="-0.02em" fill="#8995A1"><tspan x="-68.828" y="0">Default</tspan></text></g></g></g></svg>',
					'title' => __( 'Default Sidebar', 'rishi' ),
				),
				'right-sidebar'   => array(
					'src'   => '<svg width="120" height="143" viewBox="0 0 120 143" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M118 0H2C0.895431 0 0 0.895427 0 2V141C0 142.105 0.895429 143 2 143H118C119.105 143 120 142.105 120 141V2C120 0.895431 119.105 0 118 0Z" fill="white"/>
					<path opacity="0.1" d="M82.8169 7.20001C82.8169 6.09544 83.7123 5.20001 84.8169 5.20001H108.765C109.87 5.20001 110.765 6.09544 110.765 7.20001V141C110.765 142.105 109.87 143 108.765 143H84.8169C83.7123 143 82.8169 142.105 82.8169 141V7.20001Z" fill="#42474B"/>
					<path opacity="0.25" d="M54.1142 53.7498H9.37325C9.09511 53.7498 8.86963 53.9753 8.86963 54.2534C8.86963 54.5316 9.09511 54.7571 9.37325 54.7571H54.1142C54.3923 54.7571 54.6178 54.5316 54.6178 54.2534C54.6178 53.9753 54.3923 53.7498 54.1142 53.7498Z" fill="#42474B"/>
					<path opacity="0.25" d="M58.1561 56.251H9.37325C9.09511 56.251 8.86963 56.4765 8.86963 56.7546C8.86963 57.0327 9.09511 57.2582 9.37325 57.2582H58.1561C58.4342 57.2582 58.6597 57.0327 58.6597 56.7546C58.6597 56.4765 58.4342 56.251 58.1561 56.251Z" fill="#42474B"/>
					<path opacity="0.25" d="M30.5963 58.7527H9.37325C9.09511 58.7527 8.86963 58.9782 8.86963 59.2563C8.86963 59.5344 9.09511 59.7599 9.37325 59.7599H30.5963C30.8744 59.7599 31.0999 59.5344 31.0999 59.2563C31.0999 58.9782 30.8744 58.7527 30.5963 58.7527Z" fill="#42474B"/>
					<path opacity="0.3" d="M60.4416 49.0646H10.1696C9.45166 49.0646 8.86963 49.6466 8.86963 50.3646C8.86963 51.0825 9.45166 51.6646 10.1696 51.6646H60.4416C61.1596 51.6646 61.7416 51.0825 61.7416 50.3646C61.7416 49.6466 61.1596 49.0646 60.4416 49.0646Z" fill="#42474B"/>
					<path opacity="0.25" d="M34.1769 44.2775H20.0847C19.6539 44.2775 19.3047 44.6267 19.3047 45.0575C19.3047 45.4882 19.6539 45.8375 20.0847 45.8375H34.1769C34.6076 45.8375 34.9569 45.4882 34.9569 45.0575C34.9569 44.6267 34.6076 44.2775 34.1769 44.2775Z" fill="#42474B"/>
					<path opacity="0.25" d="M17.4809 44.2775H9.64963C9.21885 44.2775 8.86963 44.6267 8.86963 45.0575C8.86963 45.4882 9.21885 45.8375 9.64963 45.8375H17.4809C17.9117 45.8375 18.2609 45.4882 18.2609 45.0575C18.2609 44.6267 17.9117 44.2775 17.4809 44.2775Z" fill="#42474B"/>
					<path opacity="0.2" d="M8.86963 7.20001C8.86963 6.09544 9.76506 5.20001 10.8696 5.20001H70.382C71.4865 5.20001 72.382 6.09544 72.382 7.20001V39.1575C72.382 40.2621 71.4865 41.1575 70.382 41.1575H10.8696C9.76506 41.1575 8.86963 40.2621 8.86963 39.1575V7.20001Z" fill="#42474B"/>
					<g opacity="0.4">
					<path d="M45.0288 18.3997C45.0288 18.8506 45.1629 19.2913 45.4143 19.6662C45.6656 20.0411 46.0228 20.3333 46.4408 20.5058C46.8587 20.6784 47.3186 20.7235 47.7623 20.6356C48.206 20.5476 48.6136 20.3305 48.9335 20.0117C49.2533 19.6928 49.4712 19.2866 49.5595 18.8444C49.6477 18.4022 49.6024 17.9438 49.4293 17.5273C49.2562 17.1107 48.963 16.7547 48.5869 16.5042C48.2107 16.2537 47.7685 16.12 47.3161 16.12C46.7095 16.12 46.1277 16.3602 45.6987 16.7877C45.2698 17.2152 45.0288 17.7951 45.0288 18.3997ZM47.5221 26.6867C48.4109 28.0156 47.4584 29.7986 45.8597 29.7986H34.1938C32.7965 29.7986 31.8299 28.4021 32.3219 27.0943L34.6436 20.9235C35.1892 19.4734 37.1053 19.1676 38.0752 20.3758L40.8392 23.819C41.4751 24.6112 42.6243 24.7571 43.438 24.1489C44.3031 23.5024 45.5334 23.7132 46.1338 24.6109L47.5221 26.6867Z" fill="white"/>
					</g>
					<path opacity="0.25" d="M54.1142 118.71H9.37325C9.09511 118.71 8.86963 118.935 8.86963 119.213C8.86963 119.492 9.09511 119.717 9.37325 119.717H54.1142C54.3923 119.717 54.6178 119.492 54.6178 119.213C54.6178 118.935 54.3923 118.71 54.1142 118.71Z" fill="#42474B"/>
					<path opacity="0.25" d="M58.1561 121.211H9.37325C9.09511 121.211 8.86963 121.436 8.86963 121.715C8.86963 121.993 9.09511 122.218 9.37325 122.218H58.1561C58.4342 122.218 58.6597 121.993 58.6597 121.715C58.6597 121.436 58.4342 121.211 58.1561 121.211Z" fill="#42474B"/>
					<path opacity="0.25" d="M30.5963 123.713H9.37325C9.09511 123.713 8.86963 123.938 8.86963 124.216C8.86963 124.495 9.09511 124.72 9.37325 124.72H30.5963C30.8744 124.72 31.0999 124.495 31.0999 124.216C31.0999 123.938 30.8744 123.713 30.5963 123.713Z" fill="#42474B"/>
					<path opacity="0.3" d="M60.4416 114.025H10.1696C9.45166 114.025 8.86963 114.607 8.86963 115.325C8.86963 116.043 9.45166 116.625 10.1696 116.625H60.4416C61.1596 116.625 61.7416 116.043 61.7416 115.325C61.7416 114.607 61.1596 114.025 60.4416 114.025Z" fill="#42474B"/>
					<path opacity="0.25" d="M34.1769 109.237H20.0847C19.6539 109.237 19.3047 109.587 19.3047 110.017C19.3047 110.448 19.6539 110.797 20.0847 110.797H34.1769C34.6076 110.797 34.9569 110.448 34.9569 110.017C34.9569 109.587 34.6076 109.237 34.1769 109.237Z" fill="#42474B"/>
					<path opacity="0.25" d="M17.4809 109.237H9.64963C9.21885 109.237 8.86963 109.587 8.86963 110.017C8.86963 110.448 9.21885 110.797 9.64963 110.797H17.4809C17.9117 110.797 18.2609 110.448 18.2609 110.017C18.2609 109.587 17.9117 109.237 17.4809 109.237Z" fill="#42474B"/>
					<path opacity="0.2" d="M8.86963 72.16C8.86963 71.0554 9.76506 70.16 10.8696 70.16H70.382C71.4865 70.16 72.382 71.0554 72.382 72.16V104.117C72.382 105.222 71.4865 106.117 70.382 106.117H10.8696C9.76506 106.117 8.86963 105.222 8.86963 104.117V72.16Z" fill="#42474B"/>
					<g opacity="0.4">
					<path d="M45.0288 83.3596C45.0288 83.8105 45.1629 84.2513 45.4143 84.6262C45.6656 85.001 46.0228 85.2932 46.4408 85.4658C46.8587 85.6383 47.3186 85.6835 47.7623 85.5955C48.206 85.5075 48.6136 85.2904 48.9335 84.9716C49.2533 84.6528 49.4712 84.2466 49.5595 83.8044C49.6477 83.3622 49.6024 82.9038 49.4293 82.4872C49.2562 82.0707 48.963 81.7146 48.5869 81.4642C48.2107 81.2137 47.7685 81.08 47.3161 81.08C46.7095 81.08 46.1277 81.3201 45.6987 81.7477C45.2698 82.1752 45.0288 82.755 45.0288 83.3596ZM47.5221 91.6467C48.4109 92.9756 47.4584 94.7586 45.8597 94.7586H34.1938C32.7965 94.7586 31.8299 93.3621 32.3219 92.0543L34.6436 85.8834C35.1892 84.4334 37.1053 84.1275 38.0752 85.3358L40.8392 88.779C41.4751 89.5711 42.6243 89.717 43.438 89.1089C44.3031 88.4623 45.5334 88.6732 46.1338 89.5709L47.5221 91.6467Z" fill="white"/>
					</g>
					<path opacity="0.2" d="M8.86963 137.12C8.86963 136.015 9.76506 135.12 10.8696 135.12H70.382C71.4865 135.12 72.382 136.015 72.382 137.12V141C72.382 142.105 71.4865 143 70.382 143H10.8696C9.76506 143 8.86963 142.105 8.86963 141V137.12Z" fill="#42474B"/>
					</svg>',
					'title' => __( 'Right Sidebar', 'rishi' ),
				),
				'left-sidebar'    => array(
					'src'   => '<svg width="120" height="143" viewBox="0 0 120 143" fill="none" xmlns="http://www.w3.org/2000/svg">
					<mask id="mask0_507_11701" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="120" height="143">
					<path d="M118 0H2C0.895431 0 0 0.895427 0 2V141C0 142.105 0.895429 143 2 143H118C119.105 143 120 142.105 120 141V2C120 0.89543 119.105 0 118 0Z" fill="white"/>
					</mask>
					<g mask="url(#mask0_507_11701)">
					<path d="M118.679 0H-0.408203C-1.51277 0 -2.4082 0.895427 -2.4082 2V141C-2.4082 142.105 -1.51277 143 -0.408199 143H118.679C119.784 143 120.679 142.105 120.679 141V2C120.679 0.89543 119.784 0 118.679 0Z" fill="white"/>
					<path opacity="0.25" d="M92.4819 53.7498H46.5639C46.2858 53.7498 46.0603 53.9753 46.0603 54.2534C46.0603 54.5316 46.2858 54.7571 46.5639 54.7571H92.4819C92.7601 54.7571 92.9855 54.5316 92.9855 54.2534C92.9855 53.9753 92.7601 53.7498 92.4819 53.7498Z" fill="#42474B"/>
					<path opacity="0.25" d="M96.6278 56.251H46.5639C46.2858 56.251 46.0603 56.4765 46.0603 56.7546C46.0603 57.0327 46.2858 57.2582 46.5639 57.2582H96.6278C96.906 57.2582 97.1314 57.0327 97.1314 56.7546C97.1314 56.4765 96.906 56.251 96.6278 56.251Z" fill="#42474B"/>
					<path opacity="0.25" d="M68.3589 58.7527H46.5639C46.2858 58.7527 46.0603 58.9782 46.0603 59.2564C46.0603 59.5345 46.2858 59.76 46.5639 59.76H68.3589C68.637 59.76 68.8625 59.5345 68.8625 59.2564C68.8625 58.9782 68.637 58.7527 68.3589 58.7527Z" fill="#42474B"/>
					<path opacity="0.3" d="M98.9926 49.0646H47.3603C46.6423 49.0646 46.0603 49.6466 46.0603 50.3646C46.0603 51.0825 46.6423 51.6646 47.3603 51.6646H98.9926C99.7106 51.6646 100.293 51.0825 100.293 50.3646C100.293 49.6466 99.7106 49.0646 98.9926 49.0646Z" fill="#42474B"/>
					<path opacity="0.25" d="M72.0386 44.2775H57.5437C57.1129 44.2775 56.7637 44.6267 56.7637 45.0575C56.7637 45.4882 57.1129 45.8375 57.5437 45.8375H72.0386C72.4693 45.8375 72.8186 45.4882 72.8186 45.0575C72.8186 44.6267 72.4693 44.2775 72.0386 44.2775Z" fill="#42474B"/>
					<path opacity="0.25" d="M54.9132 44.2775H46.8403C46.4095 44.2775 46.0603 44.6267 46.0603 45.0575C46.0603 45.4882 46.4095 45.8375 46.8403 45.8375H54.9132C55.344 45.8375 55.6932 45.4882 55.6932 45.0575C55.6932 44.6267 55.344 44.2775 54.9132 44.2775Z" fill="#42474B"/>
					<path opacity="0.2" d="M46.0603 7.20007C46.0603 6.0955 46.9557 5.20007 48.0603 5.20007H109.207C110.311 5.20007 111.207 6.0955 111.207 7.20007V39.1576C111.207 40.2621 110.311 41.1575 109.207 41.1575H48.0603C46.9557 41.1575 46.0603 40.2621 46.0603 39.1575V7.20007Z" fill="#42474B"/>
					<g opacity="0.4">
					<path d="M83.1497 18.3997C83.1497 18.8506 83.2873 19.2914 83.5451 19.6663C83.8029 20.0411 84.1693 20.3333 84.598 20.5059C85.0267 20.6784 85.4985 20.7236 85.9536 20.6356C86.4087 20.5476 86.8267 20.3305 87.1549 20.0117C87.483 19.6929 87.7064 19.2867 87.797 18.8445C87.8875 18.4023 87.841 17.9439 87.6634 17.5273C87.4859 17.1108 87.1852 16.7547 86.7993 16.5043C86.4135 16.2538 85.9599 16.1201 85.4959 16.1201C84.8736 16.1201 84.2769 16.3602 83.8369 16.7878C83.3969 17.2153 83.1497 17.7951 83.1497 18.3997ZM85.6938 26.6672C86.6043 27.9944 85.654 29.7987 84.0446 29.7987H71.9879C70.5834 29.7987 69.6163 28.3889 70.122 27.0786L72.5316 20.835C73.0827 19.4069 74.9684 19.102 75.9414 20.2838L78.8453 23.8105C79.5059 24.6129 80.6775 24.7615 81.5175 24.1493C82.4117 23.4978 83.6673 23.7132 84.2931 24.6255L85.6938 26.6672Z" fill="white"/>
					</g>
					<path opacity="0.25" d="M92.4819 118.71H46.5639C46.2858 118.71 46.0603 118.935 46.0603 119.213C46.0603 119.491 46.2858 119.717 46.5639 119.717H92.4819C92.7601 119.717 92.9855 119.491 92.9855 119.213C92.9855 118.935 92.7601 118.71 92.4819 118.71Z" fill="#42474B"/>
					<path opacity="0.25" d="M96.6278 121.211H46.5639C46.2858 121.211 46.0603 121.436 46.0603 121.715C46.0603 121.993 46.2858 122.218 46.5639 122.218H96.6278C96.906 122.218 97.1314 121.993 97.1314 121.715C97.1314 121.436 96.906 121.211 96.6278 121.211Z" fill="#42474B"/>
					<path opacity="0.25" d="M68.3589 123.713H46.5639C46.2858 123.713 46.0603 123.938 46.0603 124.216C46.0603 124.494 46.2858 124.72 46.5639 124.72H68.3589C68.637 124.72 68.8625 124.494 68.8625 124.216C68.8625 123.938 68.637 123.713 68.3589 123.713Z" fill="#42474B"/>
					<path opacity="0.3" d="M98.9926 114.025H47.3603C46.6423 114.025 46.0603 114.607 46.0603 115.325C46.0603 116.043 46.6423 116.625 47.3603 116.625H98.9926C99.7106 116.625 100.293 116.043 100.293 115.325C100.293 114.607 99.7106 114.025 98.9926 114.025Z" fill="#42474B"/>
					<path opacity="0.25" d="M72.0386 109.237H57.5437C57.1129 109.237 56.7637 109.587 56.7637 110.017C56.7637 110.448 57.1129 110.797 57.5437 110.797H72.0386C72.4693 110.797 72.8186 110.448 72.8186 110.017C72.8186 109.587 72.4693 109.237 72.0386 109.237Z" fill="#42474B"/>
					<path opacity="0.25" d="M54.9132 109.237H46.8403C46.4095 109.237 46.0603 109.587 46.0603 110.017C46.0603 110.448 46.4095 110.797 46.8403 110.797H54.9132C55.344 110.797 55.6932 110.448 55.6932 110.017C55.6932 109.587 55.344 109.237 54.9132 109.237Z" fill="#42474B"/>
					<path opacity="0.2" d="M46.0603 72.16C46.0603 71.0554 46.9557 70.16 48.0603 70.16H109.207C110.311 70.16 111.207 71.0554 111.207 72.16V104.117C111.207 105.222 110.311 106.117 109.207 106.117H48.0603C46.9557 106.117 46.0603 105.222 46.0603 104.117V72.16Z" fill="#42474B"/>
					<g opacity="0.4">
					<path d="M83.1497 83.3596C83.1497 83.8105 83.2873 84.2513 83.5451 84.6262C83.8029 85.001 84.1693 85.2932 84.598 85.4658C85.0267 85.6383 85.4985 85.6835 85.9536 85.5955C86.4087 85.5075 86.8267 85.2904 87.1549 84.9716C87.483 84.6528 87.7064 84.2466 87.797 83.8044C87.8875 83.3622 87.841 82.9038 87.6634 82.4872C87.4859 82.0707 87.1852 81.7146 86.7993 81.4642C86.4135 81.2137 85.9599 81.08 85.4959 81.08C84.8736 81.08 84.2769 81.3201 83.8369 81.7477C83.3969 82.1752 83.1497 82.755 83.1497 83.3596ZM85.6938 91.6271C86.6043 92.9543 85.654 94.7586 84.0446 94.7586H71.9879C70.5834 94.7586 69.6163 93.3488 70.122 92.0385L72.5316 85.7949C73.0827 84.3668 74.9684 84.0619 75.9414 85.2437L78.8453 88.7704C79.5059 89.5728 80.6775 89.7214 81.5175 89.1092C82.4117 88.4577 83.6673 88.6731 84.2931 89.5854L85.6938 91.6271Z" fill="white"/>
					</g>
					<path opacity="0.2" d="M46.0603 137.12C46.0603 136.015 46.9557 135.12 48.0603 135.12H109.207C110.311 135.12 111.207 136.015 111.207 137.12V141C111.207 142.105 110.311 143 109.207 143H48.0603C46.9557 143 46.0603 142.105 46.0603 141V137.12Z" fill="#42474B"/>
					<path opacity="0.1" d="M6.68945 7.20007C6.68945 6.0955 7.58488 5.20007 8.68945 5.20007H33.3571C34.4616 5.20007 35.3571 6.0955 35.3571 7.20007V141C35.3571 142.105 34.4616 143 33.3571 143H8.68945C7.58488 143 6.68945 142.105 6.68945 141V7.20007Z" fill="#42474B"/>
					</g>
					</svg>',
					'title' => __( 'Left Sidebar', 'rishi' ),
				),
				'no-sidebar'      => array(
					'src'   => '<svg width="120" height="143" viewBox="0 0 120 143" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M118 0H2C0.895431 0 0 0.895427 0 2V141C0 142.105 0.895429 143 2 143H118C119.105 143 120 142.105 120 141V2C120 0.89543 119.105 0 118 0Z" fill="white"/>
					<path opacity="0.25" d="M74.744 60.1354H20.7625C20.4418 60.1354 20.1819 60.3954 20.1819 60.716C20.1819 61.0367 20.4418 61.2966 20.7625 61.2966H74.744C75.0647 61.2966 75.3246 61.0367 75.3246 60.716C75.3246 60.3954 75.0647 60.1354 74.744 60.1354Z" fill="#42474B"/>
					<path opacity="0.25" d="M79.616 63.0195H20.7625C20.4418 63.0195 20.1819 63.2794 20.1819 63.6001C20.1819 63.9207 20.4418 64.1806 20.7625 64.1806H79.616C79.9367 64.1806 80.1966 63.9207 80.1966 63.6001C80.1966 63.2794 79.9367 63.0195 79.616 63.0195Z" fill="#42474B"/>
					<path opacity="0.25" d="M46.3968 65.9034H20.7625C20.4418 65.9034 20.1819 66.1633 20.1819 66.484C20.1819 66.8046 20.4418 67.0645 20.7625 67.0645H46.3968C46.7174 67.0645 46.9773 66.8046 46.9773 66.484C46.9773 66.1633 46.7174 65.9034 46.3968 65.9034Z" fill="#42474B"/>
					<path opacity="0.3" d="M82.4123 54.7336H21.6808C20.853 54.7336 20.1819 55.4047 20.1819 56.2325C20.1819 57.0604 20.853 57.7314 21.6808 57.7314H82.4123C83.2401 57.7314 83.9112 57.0604 83.9112 56.2325C83.9112 55.4047 83.2401 54.7336 82.4123 54.7336Z" fill="#42474B"/>
					<path opacity="0.25" d="M50.7264 50.2543H33.6589C33.1622 50.2543 32.7595 50.6569 32.7595 51.1536C32.7595 51.6503 33.1622 52.053 33.6589 52.053H50.7264C51.2231 52.053 51.6257 51.6503 51.6257 51.1536C51.6257 50.6569 51.2231 50.2543 50.7264 50.2543Z" fill="#42474B"/>
					<path opacity="0.25" d="M30.6024 50.2543H21.0812C20.5845 50.2543 20.1819 50.6569 20.1819 51.1536C20.1819 51.6503 20.5845 52.053 21.0812 52.053H30.6024C31.0991 52.053 31.5017 51.6503 31.5017 51.1536C31.5017 50.6569 31.0991 50.2543 30.6024 50.2543Z" fill="#42474B"/>
					<path opacity="0.2" d="M20.1819 7.20001C20.1819 6.09544 21.0773 5.20001 22.1819 5.20001H94.7364C95.841 5.20001 96.7364 6.09544 96.7364 7.20001V44.6575C96.7364 45.7621 95.841 46.6575 94.7364 46.6575H22.1819C21.0773 46.6575 20.1819 45.7621 20.1819 44.6575V7.20001Z" fill="#42474B"/>
					<g opacity="0.4">
					<path d="M63.7664 20.4188C63.7664 20.9387 63.9281 21.4469 64.2311 21.8792C64.5341 22.3115 64.9647 22.6484 65.4685 22.8473C65.9724 23.0463 66.5268 23.0983 67.0616 22.9969C67.5965 22.8955 68.0878 22.6451 68.4734 22.2775C68.859 21.9099 69.1216 21.4415 69.228 20.9316C69.3344 20.4217 69.2798 19.8932 69.0711 19.4129C68.8624 18.9326 68.509 18.5221 68.0556 18.2332C67.6021 17.9444 67.069 17.7902 66.5237 17.7902C66.1616 17.7902 65.8031 17.8582 65.4685 17.9903C65.134 18.1224 64.83 18.316 64.574 18.5601C64.318 18.8042 64.1149 19.094 63.9763 19.4129C63.8377 19.7318 63.7664 20.0736 63.7664 20.4188ZM67.0806 30.4152C68.0075 31.7409 67.0591 33.5613 65.4415 33.5613H50.1597C48.7497 33.5613 47.7824 32.1414 48.2985 30.8292L51.5067 22.6727C52.0619 21.2611 53.9249 20.9572 54.9 22.1192L58.9003 26.8863C59.5718 27.6865 60.7459 27.8352 61.5956 27.2276L62.1223 26.851C63.0275 26.2038 64.2869 26.4199 64.9246 27.3319L67.0806 30.4152Z" fill="white"/>
					</g>
					<path opacity="0.25" d="M74.744 131.91H20.7625C20.4418 131.91 20.1819 132.17 20.1819 132.491C20.1819 132.812 20.4418 133.071 20.7625 133.071H74.744C75.0647 133.071 75.3246 132.812 75.3246 132.491C75.3246 132.17 75.0647 131.91 74.744 131.91Z" fill="#42474B"/>
					<path opacity="0.25" d="M79.616 134.794H20.7625C20.4418 134.794 20.1819 135.054 20.1819 135.375C20.1819 135.696 20.4418 135.955 20.7625 135.955H79.616C79.9367 135.955 80.1966 135.696 80.1966 135.375C80.1966 135.054 79.9367 134.794 79.616 134.794Z" fill="#42474B"/>
					<path opacity="0.25" d="M46.3968 137.678H20.7625C20.4418 137.678 20.1819 137.938 20.1819 138.259C20.1819 138.579 20.4418 138.839 20.7625 138.839H46.3968C46.7174 138.839 46.9773 138.579 46.9773 138.259C46.9773 137.938 46.7174 137.678 46.3968 137.678Z" fill="#42474B"/>
					<path opacity="0.3" d="M82.4123 126.509H21.6808C20.853 126.509 20.1819 127.18 20.1819 128.007C20.1819 128.835 20.853 129.506 21.6808 129.506H82.4123C83.2401 129.506 83.9112 128.835 83.9112 128.007C83.9112 127.18 83.2401 126.509 82.4123 126.509Z" fill="#42474B"/>
					<path opacity="0.25" d="M50.7264 121.509H33.6589C33.1622 121.509 32.7595 121.912 32.7595 122.409C32.7595 122.905 33.1622 123.308 33.6589 123.308H50.7264C51.2231 123.308 51.6257 122.905 51.6257 122.409C51.6257 121.912 51.2231 121.509 50.7264 121.509Z" fill="#42474B"/>
					<path opacity="0.25" d="M30.6024 121.509H21.0812C20.5845 121.509 20.1819 121.912 20.1819 122.409C20.1819 122.905 20.5845 123.308 21.0812 123.308H30.6024C31.0991 123.308 31.5017 122.905 31.5017 122.409C31.5017 121.912 31.0991 121.509 30.6024 121.509Z" fill="#42474B"/>
					<path opacity="0.2" d="M20.1819 77.9349C20.1819 76.8304 21.0773 75.9349 22.1819 75.9349H94.7364C95.841 75.9349 96.7364 76.8304 96.7364 77.9349V115.392C96.7364 116.497 95.841 117.392 94.7364 117.392H22.1819C21.0773 117.392 20.1819 116.497 20.1819 115.392V77.9349Z" fill="#42474B"/>
					<g opacity="0.4">
					<path d="M63.7664 91.1537C63.7664 91.6736 63.9281 92.1818 64.2311 92.6141C64.5341 93.0464 64.9647 93.3833 65.4685 93.5823C65.9724 93.7812 66.5268 93.8333 67.0616 93.7318C67.5965 93.6304 68.0878 93.3801 68.4734 93.0124C68.859 92.6448 69.1216 92.1765 69.228 91.6666C69.3344 91.1567 69.2798 90.6281 69.0711 90.1478C68.8624 89.6675 68.509 89.257 68.0556 88.9681C67.6021 88.6793 67.069 88.5251 66.5237 88.5251C66.1616 88.5251 65.8031 88.5931 65.4685 88.7252C65.134 88.8573 64.83 89.051 64.574 89.295C64.318 89.5391 64.1149 89.8289 63.9763 90.1478C63.8377 90.4667 63.7664 90.8086 63.7664 91.1537ZM67.0806 101.15C68.0075 102.476 67.0591 104.296 65.4415 104.296H50.1597C48.7497 104.296 47.7824 102.876 48.2985 101.564L51.5067 93.4076C52.0619 91.9961 53.9249 91.6921 54.9 92.8541L58.9003 97.6212C59.5718 98.4214 60.7459 98.5701 61.5956 97.9625L62.1223 97.586C63.0275 96.9387 64.2869 97.1548 64.9246 98.0668L67.0806 101.15Z" fill="white"/>
					</g>
					</svg>',
					'title' => __( 'No Sidebar', 'rishi' ),
				),
			),
		));

		$this->add_setting('author_layout', array(
			'label'   => __( 'Author Container', 'rishi' ),
			'control' => ControlTypes::INPUT_SELECT,
			'value'   => $author_defaults['author_layout'],
			'view'    => 'text',
			'design'  => 'block',
			'divider' => 'top',
			'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys(
				array(
					'default'              => __( 'Default', 'rishi' ),
					'boxed'                => __( 'Boxed', 'rishi' ),
					'content_boxed'        => __( 'Content Boxed', 'rishi' ),
					'full_width_contained' => __( 'Unboxed', 'rishi' ),
				)
			),
			'help'    => __( 'Choose Author page container layout.', 'rishi' ),
		));

		$this->add_setting('author_layout_streched_ed', array(
			'label'   => __( 'Stretch Layout', 'rishi' ),
			'help'    => __( 'This setting stretches the container width.', 'rishi' ),
			'control' => ControlTypes::INPUT_SWITCH,
			'value'   => $author_defaults['author_layout_streched_ed'],
			'divider' => 'top',

		));
	}

	/**
	 * Layout choices for the Author page
	 *
	 * @return array layout_choices
	 */
	protected function get_page_layout_choices() {
		return array(
			'classic'      => array(
				'src'   => '<svg width="99" height="137" viewBox="0 0 99 137" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="mask0_507_11857" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="99" height="137"><path d="M96.9957 0.0022583H2.6618C1.56333 0.0022583 0.672852 0.892742 0.672852 1.99121V134.405C0.672852 135.503 1.56334 136.394 2.6618 136.394H96.9957C98.0942 136.394 98.9847 135.503 98.9847 134.405V1.99121C98.9847 0.892741 98.0942 0.0022583 96.9957 0.0022583Z" fill="white"/><path d="M96.9957 0.0022583H2.6618C1.56333 0.0022583 0.672852 0.892742 0.672852 1.99121V134.405C0.672852 135.503 1.56334 136.394 2.6618 136.394H96.9957C98.0942 136.394 98.9847 135.503 98.9847 134.405V1.99121C98.9847 0.892741 98.0942 0.0022583 96.9957 0.0022583Z" stroke="white"/></mask><g mask="url(#mask0_507_11857)"><path d="M2.96649 0.502258H96.4879C97.3102 0.502258 97.9769 1.16888 97.9769 1.99121V134.405C97.9769 135.227 97.3102 135.894 96.4879 135.894H2.96649C2.14417 135.894 1.47754 135.227 1.47754 134.405V1.99121C1.47754 1.16888 2.14416 0.502258 2.96649 0.502258Z" fill="white" stroke="#E0E3E7"/><g opacity="0.25"><path d="M65.3069 57.3587H17.1309C16.8251 57.3587 16.5771 57.6066 16.5771 57.9124C16.5771 58.2183 16.8251 58.4662 17.1309 58.4662H65.3069C65.6127 58.4662 65.8606 58.2183 65.8606 57.9124C65.8606 57.6066 65.6127 57.3587 65.3069 57.3587Z" fill="#566779"/><path d="M65.3069 57.3587H17.1309C16.8251 57.3587 16.5771 57.6066 16.5771 57.9124C16.5771 58.2183 16.8251 58.4662 17.1309 58.4662H65.3069C65.6127 58.4662 65.8606 58.2183 65.8606 57.9124C65.8606 57.6066 65.6127 57.3587 65.3069 57.3587Z" stroke="black"/></g><g opacity="0.25"><path d="M69.6612 60.1093H17.1309C16.8251 60.1093 16.5771 60.3573 16.5771 60.6631C16.5771 60.9689 16.8251 61.2168 17.1309 61.2168H69.6612C69.967 61.2168 70.2149 60.9689 70.2149 60.6631C70.2149 60.3573 69.967 60.1093 69.6612 60.1093Z" fill="#566779"/><path d="M69.6612 60.1093H17.1309C16.8251 60.1093 16.5771 60.3573 16.5771 60.6631C16.5771 60.9689 16.8251 61.2168 17.1309 61.2168H69.6612C69.967 61.2168 70.2149 60.9689 70.2149 60.6631C70.2149 60.3573 69.967 60.1093 69.6612 60.1093Z" stroke="black"/></g><g opacity="0.25"><path d="M39.9717 62.8599H17.1309C16.8251 62.8599 16.5771 63.1079 16.5771 63.4137C16.5771 63.7195 16.8251 63.9674 17.1309 63.9674H39.9717C40.2775 63.9674 40.5254 63.7195 40.5254 63.4137C40.5254 63.1079 40.2775 62.8599 39.9717 62.8599Z" fill="#566779"/><path d="M39.9717 62.8599H17.1309C16.8251 62.8599 16.5771 63.1079 16.5771 63.4137C16.5771 63.7195 16.8251 63.9674 17.1309 63.9674H39.9717C40.2775 63.9674 40.5254 63.7195 40.5254 63.4137C40.5254 63.1079 40.2775 62.8599 39.9717 62.8599Z" stroke="black"/></g><g opacity="0.3"><path d="M72.1051 52.2066H18.0068C17.2172 52.2066 16.5771 52.8467 16.5771 53.6362C16.5771 54.4258 17.2172 55.0659 18.0068 55.0659H72.1051C72.8947 55.0659 73.5348 54.4258 73.5348 53.6362C73.5348 52.8467 72.8947 52.2066 72.1051 52.2066Z" fill="#566779"/><path d="M72.1051 52.2066H18.0068C17.2172 52.2066 16.5771 52.8467 16.5771 53.6362C16.5771 54.4258 17.2172 55.0659 18.0068 55.0659H72.1051C72.8947 55.0659 73.5348 54.4258 73.5348 53.6362C73.5348 52.8467 72.8947 52.2066 72.1051 52.2066Z" stroke="black"/></g><g opacity="0.25"><path d="M43.8221 47.9343H28.6761C28.2024 47.9343 27.8184 48.3183 27.8184 48.7921C27.8184 49.2658 28.2024 49.6498 28.6761 49.6498H43.8221C44.2959 49.6498 44.6799 49.2658 44.6799 48.7921C44.6799 48.3183 44.2959 47.9343 43.8221 47.9343Z" fill="#566779"/><path d="M43.8221 47.9343H28.6761C28.2024 47.9343 27.8184 48.3183 27.8184 48.7921C27.8184 49.2658 28.2024 49.6498 28.6761 49.6498H43.8221C44.2959 49.6498 44.6799 49.2658 44.6799 48.7921C44.6799 48.3183 44.2959 47.9343 43.8221 47.9343Z" stroke="black"/></g><g opacity="0.25"><path d="M25.8364 47.9343H17.4349C16.9612 47.9343 16.5771 48.3183 16.5771 48.7921C16.5771 49.2658 16.9612 49.6498 17.4349 49.6498H25.8364C26.3101 49.6498 26.6942 49.2658 26.6942 48.7921C26.6942 48.3183 26.3101 47.9343 25.8364 47.9343Z" fill="#566779"/><path d="M25.8364 47.9343H17.4349C16.9612 47.9343 16.5771 48.3183 16.5771 48.7921C16.5771 49.2658 16.9612 49.6498 17.4349 49.6498H25.8364C26.3101 49.6498 26.6942 49.2658 26.6942 48.7921C26.6942 48.3183 26.3101 47.9343 25.8364 47.9343Z" stroke="black"/></g><g opacity="0.2"><path d="M16.5771 6.9509C16.5771 5.85243 17.4676 4.96194 18.5661 4.96194H83.0084C84.1068 4.96194 84.9973 5.85243 84.9973 6.95089V42.5147C84.9973 43.6131 84.1068 44.5036 83.0084 44.5036H18.5661C17.4676 44.5036 16.5771 43.6131 16.5771 42.5147V6.9509Z" fill="#566779"/><path d="M16.5771 6.9509C16.5771 5.85243 17.4676 4.96194 18.5661 4.96194H83.0084C84.1068 4.96194 84.9973 5.85243 84.9973 6.95089V42.5147C84.9973 43.6131 84.1068 44.5036 83.0084 44.5036H18.5661C17.4676 44.5036 16.5771 43.6131 16.5771 42.5147V6.9509Z" stroke="black"/></g><g opacity="0.4"><path d="M55.5309 19.4776C55.5309 19.9734 55.6754 20.4581 55.9462 20.8704C56.217 21.2827 56.6018 21.6041 57.0521 21.7938C57.5024 21.9836 57.9979 22.0332 58.4759 21.9365C58.954 21.8398 59.3931 21.601 59.7377 21.2504C60.0823 20.8997 60.317 20.453 60.4121 19.9667C60.5072 19.4803 60.4584 18.9762 60.2719 18.5181C60.0854 18.06 59.7695 17.6684 59.3643 17.393C58.959 17.1175 58.4826 16.9704 57.9952 16.9704C57.6716 16.9704 57.3511 17.0353 57.0521 17.1613C56.7532 17.2873 56.4815 17.4719 56.2527 17.7047C56.0238 17.9376 55.8423 18.2139 55.7185 18.5181C55.5946 18.8223 55.5309 19.1483 55.5309 19.4776ZM58.4416 28.9337C59.3083 30.2564 58.3593 32.0127 56.7779 32.0127H43.5989C42.215 32.0127 41.2541 30.6344 41.7327 29.3359L44.4437 21.9803C44.9817 20.5206 46.9118 20.2158 47.8735 21.4387L51.0335 25.4575C51.7055 26.3122 52.9528 26.4585 53.8171 25.799C54.735 25.0986 56.0693 25.3131 56.7021 26.2788L58.4416 28.9337Z" fill="white"/><path d="M55.5309 19.4776C55.5309 19.9734 55.6754 20.4581 55.9462 20.8704C56.217 21.2827 56.6018 21.6041 57.0521 21.7938C57.5024 21.9836 57.9979 22.0332 58.4759 21.9365C58.954 21.8398 59.3931 21.601 59.7377 21.2504C60.0823 20.8997 60.317 20.453 60.4121 19.9667C60.5072 19.4803 60.4584 18.9762 60.2719 18.5181C60.0854 18.06 59.7695 17.6684 59.3643 17.393C58.959 17.1175 58.4826 16.9704 57.9952 16.9704C57.6716 16.9704 57.3511 17.0353 57.0521 17.1613C56.7532 17.2873 56.4815 17.4719 56.2527 17.7047C56.0238 17.9376 55.8423 18.2139 55.7185 18.5181C55.5946 18.8223 55.5309 19.1483 55.5309 19.4776ZM58.4416 28.9337C59.3083 30.2564 58.3593 32.0127 56.7779 32.0127H43.5989C42.215 32.0127 41.2541 30.6344 41.7327 29.3359L44.4437 21.9803C44.9817 20.5206 46.9118 20.2158 47.8735 21.4387L51.0335 25.4575C51.7055 26.3122 52.9528 26.4585 53.8171 25.799C54.735 25.0986 56.0693 25.3131 56.7021 26.2788L58.4416 28.9337Z" stroke="black"/></g><g opacity="0.25"><path d="M65.3069 125.817H17.1309C16.8251 125.817 16.5771 126.065 16.5771 126.371C16.5771 126.676 16.8251 126.924 17.1309 126.924H65.3069C65.6127 126.924 65.8606 126.676 65.8606 126.371C65.8606 126.065 65.6127 125.817 65.3069 125.817Z" fill="#566779"/><path d="M65.3069 125.817H17.1309C16.8251 125.817 16.5771 126.065 16.5771 126.371C16.5771 126.676 16.8251 126.924 17.1309 126.924H65.3069C65.6127 126.924 65.8606 126.676 65.8606 126.371C65.8606 126.065 65.6127 125.817 65.3069 125.817Z" stroke="black"/></g><g opacity="0.25"><path d="M69.6612 128.568H17.1309C16.8251 128.568 16.5771 128.815 16.5771 129.121C16.5771 129.427 16.8251 129.675 17.1309 129.675H69.6612C69.967 129.675 70.2149 129.427 70.2149 129.121C70.2149 128.815 69.967 128.568 69.6612 128.568Z" fill="#566779"/><path d="M69.6612 128.568H17.1309C16.8251 128.568 16.5771 128.815 16.5771 129.121C16.5771 129.427 16.8251 129.675 17.1309 129.675H69.6612C69.967 129.675 70.2149 129.427 70.2149 129.121C70.2149 128.815 69.967 128.568 69.6612 128.568Z" stroke="black"/></g><g opacity="0.25"><path d="M39.9717 131.318H17.1309C16.8251 131.318 16.5771 131.566 16.5771 131.872C16.5771 132.178 16.8251 132.426 17.1309 132.426H39.9717C40.2775 132.426 40.5254 132.178 40.5254 131.872C40.5254 131.566 40.2775 131.318 39.9717 131.318Z" fill="#566779"/><path d="M39.9717 131.318H17.1309C16.8251 131.318 16.5771 131.566 16.5771 131.872C16.5771 132.178 16.8251 132.426 17.1309 132.426H39.9717C40.2775 132.426 40.5254 132.178 40.5254 131.872C40.5254 131.566 40.2775 131.318 39.9717 131.318Z" stroke="black"/></g><g opacity="0.3"><path d="M72.1051 120.665H18.0068C17.2172 120.665 16.5771 121.305 16.5771 122.094C16.5771 122.884 17.2172 123.524 18.0068 123.524H72.1051C72.8947 123.524 73.5348 122.884 73.5348 122.094C73.5348 121.305 72.8947 120.665 72.1051 120.665Z" fill="#566779"/><path d="M72.1051 120.665H18.0068C17.2172 120.665 16.5771 121.305 16.5771 122.094C16.5771 122.884 17.2172 123.524 18.0068 123.524H72.1051C72.8947 123.524 73.5348 122.884 73.5348 122.094C73.5348 121.305 72.8947 120.665 72.1051 120.665Z" stroke="black"/></g><g opacity="0.25"><path d="M43.8221 115.897H28.6761C28.2024 115.897 27.8184 116.281 27.8184 116.754C27.8184 117.228 28.2024 117.612 28.6761 117.612H43.8221C44.2959 117.612 44.6799 117.228 44.6799 116.754C44.6799 116.281 44.2959 115.897 43.8221 115.897Z" fill="#566779"/><path d="M43.8221 115.897H28.6761C28.2024 115.897 27.8184 116.281 27.8184 116.754C27.8184 117.228 28.2024 117.612 28.6761 117.612H43.8221C44.2959 117.612 44.6799 117.228 44.6799 116.754C44.6799 116.281 44.2959 115.897 43.8221 115.897Z" stroke="black"/></g><g opacity="0.25"><path d="M25.8364 115.896H17.4349C16.9612 115.896 16.5771 116.281 16.5771 116.754C16.5771 117.228 16.9612 117.612 17.4349 117.612H25.8364C26.3101 117.612 26.6942 117.228 26.6942 116.754C26.6942 116.281 26.3101 115.896 25.8364 115.896Z" fill="#566779"/><path d="M25.8364 115.896H17.4349C16.9612 115.896 16.5771 116.281 16.5771 116.754C16.5771 117.228 16.9612 117.612 17.4349 117.612H25.8364C26.3101 117.612 26.6942 117.228 26.6942 116.754C26.6942 116.281 26.3101 115.896 25.8364 115.896Z" stroke="black"/></g><g opacity="0.2"><path d="M16.5771 74.4172C16.5771 73.3187 17.4676 72.4283 18.5661 72.4283H83.0084C84.1068 72.4283 84.9973 73.3187 84.9973 74.4172V109.981C84.9973 111.079 84.1068 111.97 83.0084 111.97H18.5661C17.4676 111.97 16.5771 111.079 16.5771 109.981V74.4172Z" fill="#566779"/><path d="M16.5771 74.4172C16.5771 73.3187 17.4676 72.4283 18.5661 72.4283H83.0084C84.1068 72.4283 84.9973 73.3187 84.9973 74.4172V109.981C84.9973 111.079 84.1068 111.97 83.0084 111.97H18.5661C17.4676 111.97 16.5771 111.079 16.5771 109.981V74.4172Z" stroke="black"/></g><g opacity="0.4"><path d="M55.5309 86.9438C55.5309 87.4396 55.6754 87.9243 55.9462 88.3366C56.217 88.7489 56.6018 89.0703 57.0521 89.26C57.5024 89.4498 57.9979 89.4994 58.4759 89.4027C58.954 89.306 59.3931 89.0672 59.7377 88.7166C60.0823 88.3659 60.317 87.9192 60.4121 87.4329C60.5072 86.9465 60.4584 86.4424 60.2719 85.9843C60.0854 85.5262 59.7695 85.1346 59.3643 84.8592C58.959 84.5837 58.4826 84.4366 57.9952 84.4366C57.6716 84.4366 57.3511 84.5015 57.0521 84.6275C56.7532 84.7535 56.4815 84.9381 56.2527 85.171C56.0238 85.4038 55.8423 85.6801 55.7185 85.9843C55.5946 86.2885 55.5309 86.6145 55.5309 86.9438ZM58.4416 96.3999C59.3083 97.7226 58.3593 99.4789 56.7779 99.4789H43.5989C42.215 99.4789 41.2541 98.1006 41.7327 96.8021L44.4437 89.4465C44.9817 87.9868 46.9118 87.682 47.8735 88.9049L51.0335 92.9237C51.7055 93.7784 52.9528 93.9247 53.8171 93.2652C54.735 92.5648 56.0693 92.7793 56.7021 93.745L58.4416 96.3999Z" fill="white"/><path d="M55.5309 86.9438C55.5309 87.4396 55.6754 87.9243 55.9462 88.3366C56.217 88.7489 56.6018 89.0703 57.0521 89.26C57.5024 89.4498 57.9979 89.4994 58.4759 89.4027C58.954 89.306 59.3931 89.0672 59.7377 88.7166C60.0823 88.3659 60.317 87.9192 60.4121 87.4329C60.5072 86.9465 60.4584 86.4424 60.2719 85.9843C60.0854 85.5262 59.7695 85.1346 59.3643 84.8592C58.959 84.5837 58.4826 84.4366 57.9952 84.4366C57.6716 84.4366 57.3511 84.5015 57.0521 84.6275C56.7532 84.7535 56.4815 84.9381 56.2527 85.171C56.0238 85.4038 55.8423 85.6801 55.7185 85.9843C55.5946 86.2885 55.5309 86.6145 55.5309 86.9438ZM58.4416 96.3999C59.3083 97.7226 58.3593 99.4789 56.7779 99.4789H43.5989C42.215 99.4789 41.2541 98.1006 41.7327 96.8021L44.4437 89.4465C44.9817 87.9868 46.9118 87.682 47.8735 88.9049L51.0335 92.9237C51.7055 93.7784 52.9528 93.9247 53.8171 93.2652C54.735 92.5648 56.0693 92.7793 56.7021 93.745L58.4416 96.3999Z" stroke="black"/></g></g></svg>',
				'title' => __( 'Classic Layout', 'rishi' ),
			),
			'listing'      => array(
				'src'   => '<svg width="99" height="138" viewBox="0 0 99 138" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="mask0_507_11894" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="99" height="138"><path d="M2.38878 1.49484H96.1594C97.0939 1.49484 97.8514 2.25237 97.8514 3.18682V134.813C97.8514 135.748 97.0939 136.505 96.1594 136.505H2.38879C1.45433 136.505 0.696805 135.748 0.696805 134.813V3.18682C0.696805 2.25236 1.45433 1.49484 2.38878 1.49484Z" fill="white" stroke="white" stroke-width="1.12799"/></mask><g mask="url(#mask0_507_11894)"><path opacity="0.3" d="M90.8468 17.2719H50.0174C49.3342 17.2719 48.7803 17.8257 48.7803 18.509C48.7803 19.1922 49.3342 19.7461 50.0174 19.7461H90.8468C91.53 19.7461 92.0839 19.1922 92.0839 18.509C92.0839 17.8257 91.53 17.2719 90.8468 17.2719Z" fill="#42474B"/><path opacity="0.3" d="M76.2238 22.0545H50.0174C49.3342 22.0545 48.7803 22.6084 48.7803 23.2916C48.7803 23.9748 49.3342 24.5287 50.0174 24.5287H76.2238C76.907 24.5287 77.4609 23.9748 77.4609 23.2916C77.4609 22.6084 76.907 22.0545 76.2238 22.0545Z" fill="#42474B"/><path opacity="0.2" d="M59.8651 11.3268H50.1566C49.473 11.3268 48.9189 11.8809 48.9189 12.5644C48.9189 13.248 49.473 13.8021 50.1566 13.8021H59.8651C60.5486 13.8021 61.1027 13.248 61.1027 12.5644C61.1027 11.8809 60.5486 11.3268 59.8651 11.3268Z" fill="#42474B"/><path opacity="0.2" d="M79.3589 11.3268H63.8021C63.1186 11.3268 62.5645 11.8809 62.5645 12.5644C62.5645 13.248 63.1186 13.8021 63.8021 13.8021H79.3588C80.0424 13.8021 80.5965 13.248 80.5965 12.5644C80.5965 11.8809 80.0424 11.3268 79.3589 11.3268Z" fill="#42474B"/><path opacity="0.2" d="M8.23535 10.6124C8.23535 9.36651 9.24538 8.35648 10.4913 8.35648H42.5931C43.8391 8.35648 44.8491 9.36651 44.8491 10.6124V27.2272C44.8491 28.4731 43.8391 29.4832 42.5931 29.4832H10.4913C9.24538 29.4832 8.23535 28.4731 8.23535 27.2272V10.6124Z" fill="#42474B"/><g opacity="0.4"><path d="M30.2707 15.0134C30.2707 15.3952 30.3822 15.7685 30.5911 16.086C30.7999 16.4035 31.0968 16.651 31.4441 16.7971C31.7914 16.9432 32.1735 16.9815 32.5422 16.907C32.9109 16.8325 33.2496 16.6486 33.5154 16.3786C33.7812 16.1086 33.9622 15.7646 34.0356 15.39C34.1089 15.0155 34.0713 14.6273 33.9274 14.2745C33.7835 13.9218 33.5399 13.6202 33.2274 13.4081C32.9148 13.1959 32.5473 13.0827 32.1714 13.0827C31.6673 13.0827 31.1839 13.2861 30.8274 13.6482C30.471 14.0103 30.2707 14.5013 30.2707 15.0134ZM31.7802 21.1704C32.7644 22.6707 31.6882 24.6639 29.8939 24.6639H22.1081C20.5377 24.6639 19.4478 23.0995 19.9918 21.6264L21.2757 18.1495C21.8865 16.4955 24.0734 16.15 25.1643 17.5351L26.797 19.6081C27.3202 20.2725 28.2796 20.3943 28.9522 19.8819C29.6668 19.3375 30.693 19.5132 31.1857 20.2643L31.7802 21.1704Z" fill="white"/></g><path opacity="0.1" d="M92.2015 35.252H8.40416C8.31093 35.252 8.23535 35.3276 8.23535 35.4208C8.23535 35.514 8.31093 35.5896 8.40416 35.5896H92.2015C92.2947 35.5896 92.3703 35.514 92.3703 35.4208C92.3703 35.3276 92.2947 35.252 92.2015 35.252Z" fill="#42474B"/><path opacity="0.3" d="M90.8468 50.6762H50.0174C49.3342 50.6762 48.7803 51.2301 48.7803 51.9134C48.7803 52.5966 49.3342 53.1505 50.0174 53.1505H90.8468C91.53 53.1505 92.0839 52.5966 92.0839 51.9134C92.0839 51.2301 91.53 50.6762 90.8468 50.6762Z" fill="#42474B"/><path opacity="0.3" d="M76.2238 55.4589H50.0174C49.3342 55.4589 48.7803 56.0128 48.7803 56.696C48.7803 57.3793 49.3342 57.9331 50.0174 57.9331H76.2238C76.907 57.9331 77.4609 57.3793 77.4609 56.696C77.4609 56.0128 76.907 55.4589 76.2238 55.4589Z" fill="#42474B"/><path opacity="0.2" d="M59.8651 44.9905H50.1566C49.473 44.9905 48.9189 45.5446 48.9189 46.2282C48.9189 46.9117 49.473 47.4658 50.1566 47.4658H59.8651C60.5486 47.4658 61.1027 46.9117 61.1027 46.2282C61.1027 45.5446 60.5486 44.9905 59.8651 44.9905Z" fill="#42474B"/><path opacity="0.2" d="M79.3589 44.9905H63.8021C63.1186 44.9905 62.5645 45.5446 62.5645 46.2282C62.5645 46.9117 63.1186 47.4658 63.8021 47.4658H79.3588C80.0424 47.4658 80.5965 46.9117 80.5965 46.2282C80.5965 45.5446 80.0424 44.9905 79.3589 44.9905Z" fill="#42474B"/><path opacity="0.2" d="M8.23535 44.0169C8.23535 42.771 9.24538 41.7609 10.4913 41.7609H42.5931C43.8391 41.7609 44.8491 42.771 44.8491 44.0169V60.6317C44.8491 61.8776 43.8391 62.8876 42.5931 62.8876H10.4913C9.24538 62.8876 8.23535 61.8776 8.23535 60.6317V44.0169Z" fill="#42474B"/><g opacity="0.4"><path d="M30.2707 48.4178C30.2707 48.7997 30.3822 49.173 30.5911 49.4905C30.7999 49.808 31.0968 50.0554 31.4441 50.2015C31.7914 50.3477 32.1735 50.3859 32.5422 50.3114C32.9109 50.2369 33.2496 50.053 33.5154 49.783C33.7812 49.513 33.9622 49.169 34.0356 48.7945C34.1089 48.42 34.0713 48.0318 33.9274 47.679C33.7835 47.3262 33.5399 47.0247 33.2274 46.8125C32.9148 46.6004 32.5473 46.4871 32.1714 46.4871C31.6673 46.4871 31.1839 46.6905 30.8274 47.0526C30.471 47.4147 30.2707 47.9058 30.2707 48.4178ZM31.7802 54.5748C32.7644 56.0751 31.6882 58.0683 29.8939 58.0683H22.1081C20.5377 58.0683 19.4478 56.5039 19.9918 55.0308L21.2757 51.5539C21.8865 49.8999 24.0734 49.5544 25.1643 50.9396L26.797 53.0126C27.3202 53.6769 28.2796 53.7988 28.9522 53.2863C29.6668 52.7419 30.693 52.9177 31.1857 53.6687L31.7802 54.5748Z" fill="white"/></g><path opacity="0.1" d="M92.2015 68.6564H8.40416C8.31093 68.6564 8.23535 68.732 8.23535 68.8252C8.23535 68.9185 8.31093 68.9941 8.40416 68.9941H92.2015C92.2947 68.9941 92.3703 68.9185 92.3703 68.8252C92.3703 68.732 92.2947 68.6564 92.2015 68.6564Z" fill="#42474B"/><path opacity="0.3" d="M90.8468 84.0802H50.0174C49.3342 84.0802 48.7803 84.6341 48.7803 85.3173C48.7803 86.0006 49.3342 86.5544 50.0174 86.5544H90.8468C91.53 86.5544 92.0839 86.0006 92.0839 85.3173C92.0839 84.6341 91.53 84.0802 90.8468 84.0802Z" fill="#42474B"/><path opacity="0.3" d="M76.2238 88.8628H50.0174C49.3342 88.8628 48.7803 89.4167 48.7803 90.0999C48.7803 90.7832 49.3342 91.337 50.0174 91.337H76.2238C76.907 91.337 77.4609 90.7832 77.4609 90.0999C77.4609 89.4167 76.907 88.8628 76.2238 88.8628Z" fill="#42474B"/><path opacity="0.2" d="M59.8651 78.1584H50.1566C49.473 78.1584 48.9189 78.7125 48.9189 79.396C48.9189 80.0795 49.473 80.6336 50.1566 80.6336H59.8651C60.5486 80.6336 61.1027 80.0795 61.1027 79.396C61.1027 78.7125 60.5486 78.1584 59.8651 78.1584Z" fill="#42474B"/><path opacity="0.2" d="M79.3589 78.1584H63.8021C63.1186 78.1584 62.5645 78.7125 62.5645 79.396C62.5645 80.0795 63.1186 80.6336 63.8021 80.6336H79.3588C80.0424 80.6336 80.5965 80.0795 80.5965 79.396C80.5965 78.7125 80.0424 78.1584 79.3589 78.1584Z" fill="#42474B"/><path opacity="0.2" d="M8.23535 77.4208C8.23535 76.1749 9.24538 75.1648 10.4913 75.1648H42.5931C43.8391 75.1648 44.8491 76.1749 44.8491 77.4208V94.0355C44.8491 95.2815 43.8391 96.2915 42.5931 96.2915H10.4913C9.24538 96.2915 8.23535 95.2815 8.23535 94.0355V77.4208Z" fill="#42474B"/><g opacity="0.4"><path d="M30.2707 81.8217C30.2707 82.2036 30.3822 82.5768 30.5911 82.8943C30.7999 83.2118 31.0968 83.4593 31.4441 83.6054C31.7914 83.7516 32.1735 83.7898 32.5422 83.7153C32.9109 83.6408 33.2496 83.4569 33.5154 83.1869C33.7812 82.9169 33.9622 82.5729 34.0356 82.1984C34.1089 81.8239 34.0713 81.4357 33.9274 81.0829C33.7835 80.7301 33.5399 80.4285 33.2274 80.2164C32.9148 80.0043 32.5473 79.891 32.1714 79.891C31.6673 79.891 31.1839 80.0944 30.8274 80.4565C30.471 80.8186 30.2707 81.3097 30.2707 81.8217ZM31.7802 87.9787C32.7644 89.479 31.6882 91.4722 29.8939 91.4722H22.1081C20.5377 91.4722 19.4478 89.9078 19.9918 88.4347L21.2757 84.9578C21.8865 83.3038 24.0734 82.9583 25.1643 84.3435L26.797 86.4165C27.3202 87.0808 28.2796 87.2027 28.9522 86.6902C29.6668 86.1458 30.693 86.3215 31.1857 87.0726L31.7802 87.9787Z" fill="white"/></g><path opacity="0.1" d="M92.2015 102.06H8.40416C8.31093 102.06 8.23535 102.136 8.23535 102.229C8.23535 102.322 8.31093 102.398 8.40416 102.398H92.2015C92.2947 102.398 92.3703 102.322 92.3703 102.229C92.3703 102.136 92.2947 102.06 92.2015 102.06Z" fill="#42474B"/><path opacity="0.3" d="M90.8468 117.484H50.0174C49.3342 117.484 48.7803 118.038 48.7803 118.721C48.7803 119.404 49.3342 119.958 50.0174 119.958H90.8468C91.53 119.958 92.0839 119.404 92.0839 118.721C92.0839 118.038 91.53 117.484 90.8468 117.484Z" fill="#42474B"/><path opacity="0.3" d="M76.2238 122.267H50.0174C49.3342 122.267 48.7803 122.821 48.7803 123.504C48.7803 124.187 49.3342 124.741 50.0174 124.741H76.2238C76.907 124.741 77.4609 124.187 77.4609 123.504C77.4609 122.821 76.907 122.267 76.2238 122.267Z" fill="#42474B"/><path opacity="0.2" d="M59.8651 111.822H50.1566C49.473 111.822 48.9189 112.376 48.9189 113.059C48.9189 113.743 49.473 114.297 50.1566 114.297H59.8651C60.5486 114.297 61.1027 113.743 61.1027 113.059C61.1027 112.376 60.5486 111.822 59.8651 111.822Z" fill="#42474B"/><path opacity="0.2" d="M79.3589 111.822H63.8021C63.1186 111.822 62.5645 112.376 62.5645 113.059C62.5645 113.743 63.1186 114.297 63.8021 114.297H79.3588C80.0424 114.297 80.5965 113.743 80.5965 113.059C80.5965 112.376 80.0424 111.822 79.3589 111.822Z" fill="#42474B"/><path opacity="0.2" d="M8.23535 110.825C8.23535 109.579 9.24538 108.569 10.4913 108.569H42.5931C43.8391 108.569 44.8491 109.579 44.8491 110.825V127.439C44.8491 128.685 43.8391 129.695 42.5931 129.695H10.4913C9.24538 129.695 8.23535 128.685 8.23535 127.439V110.825Z" fill="#42474B"/><g opacity="0.4"><path d="M30.2707 115.226C30.2707 115.607 30.3822 115.981 30.5911 116.298C30.7999 116.616 31.0968 116.863 31.4441 117.009C31.7914 117.155 32.1735 117.194 32.5422 117.119C32.9109 117.045 33.2496 116.861 33.5154 116.591C33.7812 116.321 33.9622 115.977 34.0356 115.602C34.1089 115.228 34.0713 114.84 33.9274 114.487C33.7835 114.134 33.5399 113.832 33.2274 113.62C32.9148 113.408 32.5473 113.295 32.1714 113.295C31.6673 113.295 31.1839 113.498 30.8274 113.86C30.471 114.222 30.2707 114.714 30.2707 115.226ZM31.7802 121.383C32.7644 122.883 31.6882 124.876 29.8939 124.876H22.1081C20.5377 124.876 19.4478 123.312 19.9918 121.839L21.2757 118.362C21.8865 116.708 24.0734 116.362 25.1643 117.747L26.797 119.82C27.3202 120.485 28.2796 120.607 28.9522 120.094C29.6668 119.55 30.693 119.725 31.1857 120.476L31.7802 121.383Z" fill="white"/></g><path opacity="0.1" d="M92.2015 135.464H8.40416C8.31093 135.464 8.23535 135.54 8.23535 135.633C8.23535 135.726 8.31093 135.802 8.40416 135.802H92.2015C92.2947 135.802 92.3703 135.726 92.3703 135.633C92.3703 135.54 92.2947 135.464 92.2015 135.464Z" fill="#42474B"/></g></svg>',
				'title' => __( 'Listing Layout', 'rishi' ),
			),
			'grid'=> array(
				'src'   => '<svg width="109" height="136" viewBox="0 0 109 136" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.48242 0.5H106.383C107.212 0.5 107.883 1.17157 107.883 2V134C107.883 134.828 107.212 135.5 106.383 135.5H2.48242C1.654 135.5 0.982422 134.828 0.982422 134V2C0.982422 1.17157 1.654 0.5 2.48242 0.5Z" fill="white" stroke="#E0E3E7"/><path opacity="0.25" d="M97.588 33.6108H74.2148V34.5687H97.588V33.6108Z" fill="#42474B"/><path opacity="0.25" d="M98.9803 35.9896H74.2148V36.9475H98.9803V35.9896Z" fill="#42474B"/><path opacity="0.25" d="M93.9014 38.3688H74.2148V39.3268H93.9014V38.3688Z" fill="#42474B"/><path opacity="0.1" d="M99.2033 42.3252H74.2148V42.5725H99.2033V42.3252Z" fill="#42474B"/><path opacity="0.4" d="M79.8832 44.7979H74.2148V45.7559H79.8832V44.7979Z" fill="#42474B"/><path opacity="0.15" d="M99.2153 44.7979H93.5469V45.7559H99.2153V44.7979Z" fill="#42474B"/><path opacity="0.3" d="M98.9803 26.1877H74.2148V27.9186H98.9803V26.1877Z" fill="#42474B"/><path opacity="0.3" d="M91.7869 29.4072H74.2148V31.1381H91.7869V29.4072Z" fill="#42474B"/><path opacity="0.25" d="M82.3074 22.7491H74.2148V24.2327H82.3074V22.7491Z" fill="#42474B"/><path opacity="0.25" d="M95.3459 22.7491H83.207V24.2327H95.3459V22.7491Z" fill="#42474B"/><path opacity="0.2" d="M74.2148 4.94545H98.9803V20.4203H74.2148V4.94545Z" fill="#42474B"/><g opacity="0.4"><path d="M88.313 10.6263C88.313 10.8203 88.3653 11.0099 88.4632 11.1713C88.5612 11.3326 88.7004 11.4583 88.8633 11.5327C89.0262 11.607 89.2055 11.6265 89.3785 11.5887C89.5515 11.551 89.7104 11.4577 89.8352 11.3206C89.96 11.1836 90.0451 11.0089 90.0796 10.8186C90.1142 10.6284 90.0967 10.4312 90.0294 10.2519C89.9621 10.0725 89.848 9.9192 89.7015 9.81121C89.555 9.70322 89.3827 9.64542 89.2063 9.64513C89.0891 9.64493 88.9729 9.67017 88.8645 9.71939C88.7562 9.76861 88.6577 9.84085 88.5747 9.93198C88.4917 10.0231 88.4259 10.1313 88.381 10.2505C88.3361 10.3696 88.313 10.4973 88.313 10.6263ZM90.0969 15.5322H82.9629L84.7469 10.2994L87.1252 13.5698L88.3143 12.5887L90.0969 15.5322Z" fill="white"/></g><path opacity="0.25" d="M34.1964 33.6108H10.8232V34.5687H34.1964V33.6108Z" fill="#42474B"/><path opacity="0.25" d="M35.5887 35.9896H10.8232V36.9475H35.5887V35.9896Z" fill="#42474B"/><path opacity="0.25" d="M30.5097 38.3688H10.8232V39.3267H30.5097V38.3688Z" fill="#42474B"/><path opacity="0.1" d="M35.8117 42.3252H10.8232V42.5724H35.8117V42.3252Z" fill="#42474B"/><path opacity="0.4" d="M16.4916 44.7979H10.8232V45.7558H16.4916V44.7979Z" fill="#42474B"/><path opacity="0.15" d="M35.8237 44.7979H30.1553V45.7558H35.8237V44.7979Z" fill="#42474B"/><path opacity="0.3" d="M35.5887 26.1877H10.8232V27.9186H35.5887V26.1877Z" fill="#42474B"/><path opacity="0.3" d="M28.3953 29.4071H10.8232V31.1381H28.3953V29.4071Z" fill="#42474B"/><path opacity="0.25" d="M18.9158 22.7491H10.8232V24.2327H18.9158V22.7491Z" fill="#42474B"/><path opacity="0.25" d="M31.9543 22.7491H19.8154V24.2327H31.9543V22.7491Z" fill="#42474B"/><path opacity="0.2" d="M10.8232 4.94544H35.5887V20.4203H10.8232V4.94544Z" fill="#42474B"/><g opacity="0.4"><path d="M24.9214 10.6263C24.9214 10.8203 24.9737 11.0099 25.0716 11.1713C25.1696 11.3326 25.3088 11.4583 25.4717 11.5327C25.6346 11.607 25.8139 11.6265 25.9869 11.5887C26.1599 11.551 26.3188 11.4577 26.4436 11.3206C26.5684 11.1836 26.6535 11.0089 26.688 10.8186C26.7226 10.6284 26.7051 10.4312 26.6378 10.2519C26.5705 10.0725 26.4564 9.9192 26.3099 9.81121C26.1634 9.70322 25.9911 9.64542 25.8147 9.64513C25.6975 9.64493 25.5813 9.67017 25.4729 9.71939C25.3646 9.76861 25.2661 9.84085 25.1831 9.93198C25.1001 10.0231 25.0343 10.1313 24.9894 10.2505C24.9445 10.3696 24.9214 10.4973 24.9214 10.6263ZM26.7053 15.5322H19.5713L21.3553 10.2994L23.7336 13.5698L24.9227 12.5887L26.7053 15.5322Z" fill="white"/></g><path opacity="0.25" d="M66.1173 33.6108H42.7441V34.5687H66.1173V33.6108Z" fill="#42474B"/><path opacity="0.25" d="M67.5096 35.9896H42.7441V36.9475H67.5096V35.9896Z" fill="#42474B"/><path opacity="0.25" d="M62.4306 38.3688H42.7441V39.3268H62.4306V38.3688Z" fill="#42474B"/><path opacity="0.1" d="M67.7326 42.3252H42.7441V42.5725H67.7326V42.3252Z" fill="#42474B"/><path opacity="0.4" d="M48.4125 44.7979H42.7441V45.7559H48.4125V44.7979Z" fill="#42474B"/><path opacity="0.15" d="M67.7446 44.7979H62.0762V45.7559H67.7446V44.7979Z" fill="#42474B"/><path opacity="0.3" d="M67.5096 26.1877H42.7441V27.9186H67.5096V26.1877Z" fill="#42474B"/><path opacity="0.3" d="M60.3162 29.4072H42.7441V31.1381H60.3162V29.4072Z" fill="#42474B"/><path opacity="0.25" d="M50.8367 22.7491H42.7441V24.2327H50.8367V22.7491Z" fill="#42474B"/><path opacity="0.25" d="M63.8752 22.7491H51.7363V24.2327H63.8752V22.7491Z" fill="#42474B"/><path opacity="0.2" d="M42.7441 4.94545H67.5096V20.4203H42.7441V4.94545Z" fill="#42474B"/><g opacity="0.4"><path d="M56.8423 10.6263C56.8423 10.8203 56.8945 11.0099 56.9925 11.1713C57.0905 11.3326 57.2297 11.4583 57.3926 11.5327C57.5555 11.607 57.7348 11.6265 57.9078 11.5887C58.0808 11.551 58.2397 11.4577 58.3645 11.3206C58.4893 11.1836 58.5744 11.0089 58.6089 10.8186C58.6435 10.6284 58.626 10.4312 58.5587 10.2519C58.4914 10.0725 58.3773 9.9192 58.2308 9.81121C58.0843 9.70322 57.912 9.64542 57.7356 9.64513C57.6184 9.64493 57.5022 9.67017 57.3938 9.71939C57.2855 9.76861 57.187 9.84085 57.104 9.93198C57.021 10.0231 56.9552 10.1313 56.9103 10.2505C56.8654 10.3696 56.8423 10.4973 56.8423 10.6263ZM58.6262 15.5322H51.4922L53.2761 10.2994L55.6545 13.5698L56.8436 12.5887L58.6262 15.5322Z" fill="white"/></g><path opacity="0.25" d="M97.588 76.1417H74.2148V77.0996H97.588V76.1417Z" fill="#42474B"/><path opacity="0.25" d="M98.9803 78.5205H74.2148V79.4784H98.9803V78.5205Z" fill="#42474B"/><path opacity="0.25" d="M93.9014 80.8997H74.2148V81.8576H93.9014V80.8997Z" fill="#42474B"/><path opacity="0.1" d="M99.2033 84.8561H74.2148V85.1033H99.2033V84.8561Z" fill="#42474B"/><path opacity="0.4" d="M79.8832 87.3288H74.2148V88.2867H79.8832V87.3288Z" fill="#42474B"/><path opacity="0.15" d="M99.2153 87.3288H93.5469V88.2867H99.2153V87.3288Z" fill="#42474B"/><path opacity="0.3" d="M98.9803 72.1804H74.2148V73.9113H98.9803V72.1804Z" fill="#42474B"/><path opacity="0.25" d="M82.3074 68.7418H74.2148V70.2255H82.3074V68.7418Z" fill="#42474B"/><path opacity="0.25" d="M95.3459 68.7418H83.207V70.2255H95.3459V68.7418Z" fill="#42474B"/><path opacity="0.2" d="M74.2148 50.9382H98.9803V66.413H74.2148V50.9382Z" fill="#42474B"/><g opacity="0.4"><path d="M88.313 56.619C88.313 56.813 88.3653 57.0027 88.4632 57.164C88.5612 57.3253 88.7004 57.4511 88.8633 57.5254C89.0262 57.5997 89.2055 57.6192 89.3785 57.5815C89.5515 57.5437 89.7104 57.4504 89.8352 57.3133C89.96 57.1763 90.0451 57.0016 90.0796 56.8113C90.1142 56.6211 90.0967 56.4239 90.0294 56.2446C89.9621 56.0653 89.848 55.9119 89.7015 55.8039C89.555 55.6959 89.3827 55.6381 89.2063 55.6378C89.0891 55.6376 88.9729 55.6629 88.8645 55.7121C88.7562 55.7613 88.6577 55.8336 88.5747 55.9247C88.4917 56.0158 88.4259 56.1241 88.381 56.2432C88.3361 56.3623 88.313 56.49 88.313 56.619ZM90.0969 61.5249H82.9629L84.7469 56.2921L87.1252 59.5626L88.3143 58.5814L90.0969 61.5249Z" fill="white"/></g><path opacity="0.25" d="M34.1964 76.1417H10.8232V77.0996H34.1964V76.1417Z" fill="#42474B"/><path opacity="0.25" d="M35.5887 78.5205H10.8232V79.4784H35.5887V78.5205Z" fill="#42474B"/><path opacity="0.25" d="M30.5097 80.8997H10.8232V81.8577H30.5097V80.8997Z" fill="#42474B"/><path opacity="0.1" d="M35.8117 84.8561H10.8232V85.1033H35.8117V84.8561Z" fill="#42474B"/><path opacity="0.4" d="M16.4916 87.3288H10.8232V88.2867H16.4916V87.3288Z" fill="#42474B"/><path opacity="0.15" d="M35.8237 87.3288H30.1553V88.2867H35.8237V87.3288Z" fill="#42474B"/><path opacity="0.3" d="M35.5887 72.1804H10.8232V73.9113H35.5887V72.1804Z" fill="#42474B"/><path opacity="0.25" d="M18.9158 68.7418H10.8232V70.2255H18.9158V68.7418Z" fill="#42474B"/><path opacity="0.25" d="M31.9543 68.7418H19.8154V70.2255H31.9543V68.7418Z" fill="#42474B"/><path opacity="0.2" d="M10.8232 50.9382H35.5887V66.413H10.8232V50.9382Z" fill="#42474B"/><g opacity="0.4"><path d="M24.9214 56.619C24.9214 56.813 24.9737 57.0027 25.0716 57.164C25.1696 57.3253 25.3088 57.4511 25.4717 57.5254C25.6346 57.5997 25.8139 57.6192 25.9869 57.5815C26.1599 57.5437 26.3188 57.4504 26.4436 57.3133C26.5684 57.1763 26.6535 57.0016 26.688 56.8113C26.7226 56.6211 26.7051 56.4239 26.6378 56.2446C26.5705 56.0653 26.4564 55.9119 26.3099 55.8039C26.1634 55.6959 25.9911 55.6381 25.8147 55.6378C25.6975 55.6377 25.5813 55.6629 25.4729 55.7121C25.3646 55.7613 25.2661 55.8336 25.1831 55.9247C25.1001 56.0158 25.0343 56.1241 24.9894 56.2432C24.9445 56.3623 24.9214 56.49 24.9214 56.619ZM26.7053 61.5249H19.5713L21.3553 56.2921L23.7336 59.5626L24.9227 58.5814L26.7053 61.5249Z" fill="white"/></g><path opacity="0.25" d="M66.1173 76.1417H42.7441V77.0996H66.1173V76.1417Z" fill="#42474B"/><path opacity="0.25" d="M67.5096 78.5205H42.7441V79.4784H67.5096V78.5205Z" fill="#42474B"/><path opacity="0.25" d="M62.4306 80.8997H42.7441V81.8576H62.4306V80.8997Z" fill="#42474B"/><path opacity="0.1" d="M67.7326 84.8561H42.7441V85.1033H67.7326V84.8561Z" fill="#42474B"/><path opacity="0.4" d="M48.4125 87.3288H42.7441V88.2867H48.4125V87.3288Z" fill="#42474B"/><path opacity="0.15" d="M67.7446 87.3288H62.0762V88.2867H67.7446V87.3288Z" fill="#42474B"/><path opacity="0.3" d="M67.5096 72.1804H42.7441V73.9113H67.5096V72.1804Z" fill="#42474B"/><path opacity="0.25" d="M50.8367 68.7418H42.7441V70.2255H50.8367V68.7418Z" fill="#42474B"/><path opacity="0.25" d="M63.8752 68.7418H51.7363V70.2255H63.8752V68.7418Z" fill="#42474B"/><path opacity="0.2" d="M42.7441 50.9382H67.5096V66.413H42.7441V50.9382Z" fill="#42474B"/><g opacity="0.4"><path d="M56.8423 56.619C56.8423 56.813 56.8945 57.0027 56.9925 57.164C57.0905 57.3253 57.2297 57.4511 57.3926 57.5254C57.5555 57.5997 57.7348 57.6192 57.9078 57.5815C58.0808 57.5437 58.2397 57.4504 58.3645 57.3133C58.4893 57.1763 58.5744 57.0016 58.6089 56.8113C58.6435 56.6211 58.626 56.4239 58.5587 56.2446C58.4914 56.0653 58.3773 55.9119 58.2308 55.8039C58.0843 55.6959 57.912 55.6381 57.7356 55.6378C57.6184 55.6376 57.5022 55.6629 57.3938 55.7121C57.2855 55.7613 57.187 55.8336 57.104 55.9247C57.021 56.0158 56.9552 56.1241 56.9103 56.2432C56.8654 56.3623 56.8423 56.49 56.8423 56.619ZM58.6262 61.5249H51.4922L53.2761 56.2921L55.6545 59.5626L56.8436 58.5814L58.6262 61.5249Z" fill="white"/></g><path opacity="0.25" d="M97.588 122.134H74.2148V123.092H97.588V122.134Z" fill="#42474B"/><path opacity="0.25" d="M98.9803 124.513H74.2148V125.471H98.9803V124.513Z" fill="#42474B"/><path opacity="0.25" d="M93.9014 126.892H74.2148V127.85H93.9014V126.892Z" fill="#42474B"/><path opacity="0.1" d="M99.2033 130.849H74.2148V131.096H99.2033V130.849Z" fill="#42474B"/><path opacity="0.4" d="M79.8832 133.322H74.2148V134.279H79.8832V133.322Z" fill="#42474B"/><path opacity="0.15" d="M99.2153 133.322H93.5469V134.279H99.2153V133.322Z" fill="#42474B"/><path opacity="0.3" d="M98.9803 114.711H74.2148V116.442H98.9803V114.711Z" fill="#42474B"/><path opacity="0.3" d="M91.7869 117.931H74.2148V119.662H91.7869V117.931Z" fill="#42474B"/><path opacity="0.25" d="M82.3074 111.273H74.2148V112.756H82.3074V111.273Z" fill="#42474B"/><path opacity="0.25" d="M95.3459 111.273H83.207V112.756H95.3459V111.273Z" fill="#42474B"/><path opacity="0.2" d="M74.2148 93.4691H98.9803V108.944H74.2148V93.4691Z" fill="#42474B"/><g opacity="0.4"><path d="M88.313 99.1499C88.313 99.3439 88.3653 99.5336 88.4632 99.6949C88.5612 99.8562 88.7004 99.982 88.8633 100.056C89.0262 100.131 89.2055 100.15 89.3785 100.112C89.5515 100.075 89.7104 99.9813 89.8352 99.8443C89.96 99.7072 90.0451 99.5325 90.0796 99.3423C90.1142 99.152 90.0967 98.9548 90.0294 98.7755C89.9621 98.5962 89.848 98.4428 89.7015 98.3348C89.555 98.2268 89.3827 98.169 89.2063 98.1688C89.0891 98.1686 88.9729 98.1938 88.8645 98.243C88.7562 98.2922 88.6577 98.3645 88.5747 98.4556C88.4917 98.5467 88.4259 98.655 88.381 98.7741C88.3361 98.8932 88.313 99.021 88.313 99.1499ZM90.0969 104.056H82.9629L84.7469 98.823L87.1252 102.093L88.3143 101.112L90.0969 104.056Z" fill="white"/></g><path opacity="0.25" d="M34.1964 122.134H10.8232V123.092H34.1964V122.134Z" fill="#42474B"/><path opacity="0.25" d="M35.5887 124.513H10.8232V125.471H35.5887V124.513Z" fill="#42474B"/><path opacity="0.25" d="M30.5097 126.892H10.8232V127.85H30.5097V126.892Z" fill="#42474B"/><path opacity="0.1" d="M35.8117 130.849H10.8232V131.096H35.8117V130.849Z" fill="#42474B"/><path opacity="0.4" d="M16.4916 133.322H10.8232V134.279H16.4916V133.322Z" fill="#42474B"/><path opacity="0.15" d="M35.8237 133.322H30.1553V134.279H35.8237V133.322Z" fill="#42474B"/><path opacity="0.3" d="M35.5887 114.711H10.8232V116.442H35.5887V114.711Z" fill="#42474B"/><path opacity="0.3" d="M28.3953 117.931H10.8232V119.662H28.3953V117.931Z" fill="#42474B"/><path opacity="0.25" d="M18.9158 111.273H10.8232V112.756H18.9158V111.273Z" fill="#42474B"/><path opacity="0.25" d="M31.9543 111.273H19.8154V112.756H31.9543V111.273Z" fill="#42474B"/><path opacity="0.2" d="M10.8232 93.4691H35.5887V108.944H10.8232V93.4691Z" fill="#42474B"/><g opacity="0.4"><path d="M24.9214 99.1499C24.9214 99.3439 24.9737 99.5336 25.0716 99.6949C25.1696 99.8562 25.3088 99.982 25.4717 100.056C25.6346 100.131 25.8139 100.15 25.9869 100.112C26.1599 100.075 26.3188 99.9813 26.4436 99.8443C26.5684 99.7072 26.6535 99.5325 26.688 99.3423C26.7226 99.152 26.7051 98.9548 26.6378 98.7755C26.5705 98.5962 26.4564 98.4428 26.3099 98.3348C26.1634 98.2268 25.9911 98.1691 25.8147 98.1688C25.6975 98.1686 25.5813 98.1938 25.4729 98.243C25.3646 98.2922 25.2661 98.3645 25.1831 98.4556C25.1001 98.5467 25.0343 98.655 24.9894 98.7741C24.9445 98.8933 24.9214 99.021 24.9214 99.1499ZM26.7053 104.056H19.5713L21.3553 98.823L23.7336 102.093L24.9227 101.112L26.7053 104.056Z" fill="white"/></g><path opacity="0.25" d="M66.1173 122.134H42.7441V123.092H66.1173V122.134Z" fill="#42474B"/><path opacity="0.25" d="M67.5096 124.513H42.7441V125.471H67.5096V124.513Z" fill="#42474B"/><path opacity="0.25" d="M62.4306 126.892H42.7441V127.85H62.4306V126.892Z" fill="#42474B"/><path opacity="0.1" d="M67.7326 130.849H42.7441V131.096H67.7326V130.849Z" fill="#42474B"/><path opacity="0.4" d="M48.4125 133.322H42.7441V134.279H48.4125V133.322Z" fill="#42474B"/><path opacity="0.15" d="M67.7446 133.322H62.0762V134.279H67.7446V133.322Z" fill="#42474B"/><path opacity="0.3" d="M67.5096 114.711H42.7441V116.442H67.5096V114.711Z" fill="#42474B"/><path opacity="0.3" d="M60.3162 117.931H42.7441V119.662H60.3162V117.931Z" fill="#42474B"/><path opacity="0.25" d="M50.8367 111.273H42.7441V112.756H50.8367V111.273Z" fill="#42474B"/><path opacity="0.25" d="M63.8752 111.273H51.7363V112.756H63.8752V111.273Z" fill="#42474B"/><path opacity="0.2" d="M42.7441 93.4691H67.5096V108.944H42.7441V93.4691Z" fill="#42474B"/><g opacity="0.4"><path d="M56.8423 99.1499C56.8423 99.3439 56.8945 99.5336 56.9925 99.6949C57.0905 99.8562 57.2297 99.982 57.3926 100.056C57.5555 100.131 57.7348 100.15 57.9078 100.112C58.0808 100.075 58.2397 99.9813 58.3645 99.8443C58.4893 99.7072 58.5744 99.5325 58.6089 99.3423C58.6435 99.152 58.626 98.9548 58.5587 98.7755C58.4914 98.5962 58.3773 98.4428 58.2308 98.3348C58.0843 98.2268 57.912 98.169 57.7356 98.1688C57.6184 98.1686 57.5022 98.1938 57.3938 98.243C57.2855 98.2922 57.187 98.3645 57.104 98.4556C57.021 98.5467 56.9552 98.655 56.9103 98.7741C56.8654 98.8932 56.8423 99.021 56.8423 99.1499ZM58.6262 104.056H51.4922L53.2761 98.823L55.6545 102.093L56.8436 101.112L58.6262 104.056Z" fill="white"/></g></svg>',
				'title' => __( 'Grid Layout', 'rishi' ),
			),
			'masonry_grid' => array(
				'src'   => '<svg width="110" height="136" viewBox="0 0 110 136" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.48242 0.5H105.282C107.215 0.5 108.782 2.067 108.782 4V132C108.782 133.933 107.215 135.5 105.282 135.5H4.48242C2.54942 135.5 0.982422 133.933 0.982422 132V4C0.982422 2.06701 2.54943 0.5 4.48242 0.5Z" fill="white" stroke="#E0E3E7"/><path opacity="0.25" d="M34.7151 36.2223H10.9824V37.1839H34.7151V36.2223Z" fill="#566779"/><path opacity="0.25" d="M34.7151 130.165H10.9824V131.126H34.7151V130.165Z" fill="#566779"/><path opacity="0.25" d="M36.3553 38.6102H10.9824V39.5718H36.3553V38.6102Z" fill="#566779"/><path opacity="0.25" d="M36.3553 132.552H10.9824V133.514H36.3553V132.552Z" fill="#566779"/><path opacity="0.25" d="M30.9718 40.9986H10.9824V41.9602H30.9718V40.9986Z" fill="#566779"/><path opacity="0.1" d="M36.3553 44.9702H10.9824V45.2184H36.3553V44.9702Z" fill="#566779"/><path opacity="0.4" d="M16.738 47.4524H10.9824V48.4141H16.738V47.4524Z" fill="#566779"/><path opacity="0.15" d="M36.3679 47.4524H30.6123V48.4141H36.3679V47.4524Z" fill="#566779"/><path opacity="0.3" d="M36.1586 26.2884H10.9824V28.0259H36.1586V26.2884Z" fill="#566779"/><path opacity="0.3" d="M36.1586 119.734H10.9824V121.472H36.1586V119.734Z" fill="#566779"/><path opacity="0.3" d="M33.4196 29.2671H10.9824V31.0046H33.4196V29.2671Z" fill="#566779"/><path opacity="0.3" d="M33.4196 122.713H10.9824V124.45H33.4196V122.713Z" fill="#566779"/><path opacity="0.3" d="M22.4635 32.2458H10.9824V33.9833H22.4635V32.2458Z" fill="#566779"/><path opacity="0.3" d="M22.4635 125.692H10.9824V127.429H22.4635V125.692Z" fill="#566779"/><path opacity="0.25" d="M19.1995 22.8366H10.9824V24.3259H19.1995V22.8366Z" fill="#566779"/><path opacity="0.25" d="M19.1995 116.282H10.9824V117.772H19.1995V116.282Z" fill="#566779"/><path opacity="0.25" d="M32.4379 22.8366H20.1123V24.3259H32.4379V22.8366Z" fill="#566779"/><path opacity="0.25" d="M32.4379 116.282H20.1123V117.772H32.4379V116.282Z" fill="#566779"/><path opacity="0.2" d="M10.9824 4.96448H36.1289V20.4988H10.9824V4.96448Z" fill="#566779"/><path opacity="0.2" d="M10.9824 98.4103H36.1289V113.945H10.9824V98.4103Z" fill="#566779"/><g opacity="0.4"><path d="M25.2976 10.6672C25.2976 10.8619 25.3507 11.0523 25.4502 11.2142C25.5496 11.3762 25.691 11.5024 25.8564 11.577C26.0218 11.6516 26.2039 11.6712 26.3795 11.6333C26.5552 11.5954 26.7166 11.5017 26.8433 11.3641C26.97 11.2265 27.0564 11.0512 27.0915 10.8602C27.1266 10.6693 27.1088 10.4713 27.0405 10.2913C26.9721 10.1113 26.8563 9.95733 26.7075 9.84892C26.5587 9.74052 26.3838 9.6825 26.2047 9.68221C26.0856 9.68201 25.9677 9.70734 25.8577 9.75675C25.7476 9.80616 25.6476 9.87869 25.5634 9.97017C25.4791 10.0616 25.4123 10.1703 25.3667 10.2899C25.3211 10.4095 25.2976 10.5377 25.2976 10.6672ZM27.109 15.5919H19.8652L21.6766 10.339L24.0915 13.622L25.299 12.6371L27.109 15.5919Z" fill="white"/></g><g opacity="0.4"><path d="M25.2976 104.113C25.2976 104.308 25.3507 104.498 25.4502 104.66C25.5496 104.822 25.691 104.948 25.8564 105.023C26.0218 105.097 26.2039 105.117 26.3795 105.079C26.5552 105.041 26.7166 104.948 26.8433 104.81C26.97 104.672 27.0564 104.497 27.0915 104.306C27.1266 104.115 27.1088 103.917 27.0405 103.737C26.9721 103.557 26.8563 103.403 26.7075 103.295C26.5587 103.186 26.3838 103.128 26.2047 103.128C26.0856 103.128 25.9677 103.153 25.8577 103.203C25.7476 103.252 25.6476 103.325 25.5634 103.416C25.4791 103.507 25.4123 103.616 25.3667 103.736C25.3211 103.855 25.2976 103.984 25.2976 104.113ZM27.109 109.038H19.8652L21.6766 103.785L24.0915 107.068L25.299 106.083L27.109 109.038Z" fill="white"/></g><path opacity="0.25" d="M67.1272 30.2649H43.3945V31.2266H67.1272V30.2649Z" fill="#566779"/><path opacity="0.25" d="M68.7675 32.6528H43.3945V33.6145H68.7675V32.6528Z" fill="#566779"/><path opacity="0.25" d="M63.3839 35.0413H43.3945V36.0029H63.3839V35.0413Z" fill="#566779"/><path opacity="0.1" d="M68.7675 39.0128H43.3945V39.2611H68.7675V39.0128Z" fill="#566779"/><path opacity="0.4" d="M49.1501 41.4951H43.3945V42.4567H49.1501V41.4951Z" fill="#566779"/><path opacity="0.15" d="M68.78 41.4951H63.0244V42.4567H68.78V41.4951Z" fill="#566779"/><path opacity="0.3" d="M68.5707 26.2884H43.3945V28.0259H68.5707V26.2884Z" fill="#566779"/><path opacity="0.25" d="M51.6116 22.8366H43.3945V24.3259H51.6116V22.8366Z" fill="#566779"/><path opacity="0.25" d="M64.85 22.8366H52.5244V24.3259H64.85V22.8366Z" fill="#566779"/><path opacity="0.2" d="M43.3945 4.96448H68.541V20.4988H43.3945V4.96448Z" fill="#566779"/><g opacity="0.4"><path d="M57.7097 10.6672C57.7097 10.8619 57.7628 11.0523 57.8623 11.2142C57.9617 11.3762 58.1031 11.5024 58.2685 11.577C58.4339 11.6516 58.616 11.6712 58.7916 11.6333C58.9673 11.5954 59.1287 11.5017 59.2554 11.3641C59.3821 11.2265 59.4685 11.0512 59.5036 10.8602C59.5387 10.6693 59.5209 10.4713 59.4526 10.2913C59.3842 10.1113 59.2684 9.95733 59.1196 9.84892C58.9709 9.74052 58.7959 9.6825 58.6168 9.68221C58.4978 9.68201 58.3798 9.70734 58.2698 9.75675C58.1597 9.80616 58.0597 9.87869 57.9755 9.97017C57.8912 10.0616 57.8244 10.1703 57.7788 10.2899C57.7332 10.4095 57.7097 10.5377 57.7097 10.6672ZM59.5211 15.5919H52.2773L54.0888 10.339L56.5037 13.622L57.7111 12.6371L59.5211 15.5919Z" fill="white"/></g><path opacity="0.25" d="M67.1272 119.863H43.3945V120.825H67.1272V119.863Z" fill="#566779"/><path opacity="0.25" d="M68.7675 122.251H43.3945V123.213H68.7675V122.251Z" fill="#566779"/><path opacity="0.25" d="M63.3839 124.64H43.3945V125.601H63.3839V124.64Z" fill="#566779"/><path opacity="0.1" d="M68.7675 128.611H43.3945V128.859H68.7675V128.611Z" fill="#566779"/><path opacity="0.4" d="M49.1501 131.093H43.3945V132.055H49.1501V131.093Z" fill="#566779"/><path opacity="0.15" d="M68.78 131.093H63.0244V132.055H68.78V131.093Z" fill="#566779"/><path opacity="0.3" d="M68.5707 115.887H43.3945V117.624H68.5707V115.887Z" fill="#566779"/><path opacity="0.25" d="M51.6116 112.435H43.3945V113.924H51.6116V112.435Z" fill="#566779"/><path opacity="0.25" d="M64.85 112.435H52.5244V113.924H64.85V112.435Z" fill="#566779"/><path opacity="0.2" d="M43.3945 94.5628H68.541V110.097H43.3945V94.5628Z" fill="#566779"/><g opacity="0.4"><path d="M57.7097 100.266C57.7097 100.46 57.7628 100.651 57.8623 100.813C57.9617 100.975 58.1031 101.101 58.2685 101.175C58.4339 101.25 58.616 101.27 58.7916 101.232C58.9673 101.194 59.1287 101.1 59.2554 100.963C59.3821 100.825 59.4685 100.65 59.5036 100.459C59.5387 100.268 59.5209 100.07 59.4526 99.8896C59.3842 99.7096 59.2684 99.5557 59.1196 99.4473C58.9709 99.3389 58.7959 99.2809 58.6168 99.2806C58.4978 99.2804 58.3798 99.3057 58.2698 99.3551C58.1597 99.4045 58.0597 99.477 57.9755 99.5685C57.8912 99.66 57.8244 99.7687 57.7788 99.8883C57.7332 100.008 57.7097 100.136 57.7097 100.266ZM59.5211 105.19H52.2773L54.0888 99.9374L56.5037 103.22L57.7111 102.235L59.5211 105.19Z" fill="white"/></g><path opacity="0.25" d="M99.0325 33.2436H75.2998V34.2052H99.0325V33.2436Z" fill="#566779"/><path opacity="0.25" d="M100.446 35.6315H75.2998V36.5931H100.446V35.6315Z" fill="#566779"/><path opacity="0.25" d="M95.2892 38.0199H75.2998V38.9816H95.2892V38.0199Z" fill="#566779"/><path opacity="0.1" d="M100.673 41.9915H75.2998V42.2397H100.673V41.9915Z" fill="#566779"/><path opacity="0.4" d="M81.0554 44.4737H75.2998V45.4354H81.0554V44.4737Z" fill="#566779"/><path opacity="0.15" d="M100.685 44.4737H94.9297V45.4353H100.685V44.4737Z" fill="#566779"/><path opacity="0.3" d="M100.446 26.2884H75.2998V28.0259H100.446V26.2884Z" fill="#566779"/><path opacity="0.3" d="M93.1422 29.5202H75.2998V31.2578H93.1422V29.5202Z" fill="#566779"/><path opacity="0.25" d="M83.5169 22.8366H75.2998V24.3259H83.5169V22.8366Z" fill="#566779"/><path opacity="0.25" d="M96.7553 22.8366H84.4297V24.3259H96.7553V22.8366Z" fill="#566779"/><path opacity="0.2" d="M75.2998 4.96448H100.446V20.4988H75.2998V4.96448Z" fill="#566779"/><g opacity="0.4"><path d="M89.615 10.6672C89.615 10.8619 89.6681 11.0523 89.7676 11.2142C89.867 11.3762 90.0084 11.5024 90.1738 11.577C90.3392 11.6516 90.5213 11.6712 90.6969 11.6333C90.8726 11.5954 91.0339 11.5017 91.1607 11.3641C91.2874 11.2265 91.3737 11.0512 91.4088 10.8602C91.4439 10.6693 91.4262 10.4713 91.3579 10.2913C91.2895 10.1113 91.1737 9.95733 91.0249 9.84892C90.8761 9.74052 90.7012 9.6825 90.5221 9.68221C90.403 9.68201 90.2851 9.70734 90.1751 9.75675C90.065 9.80616 89.965 9.87869 89.8808 9.97017C89.7965 10.0616 89.7297 10.1703 89.6841 10.2899C89.6385 10.4095 89.615 10.5377 89.615 10.6672ZM91.4264 15.5919H84.1826L85.994 10.339L88.4089 13.622L89.6164 12.6371L91.4264 15.5919Z" fill="white"/></g><path opacity="0.25" d="M99.0325 122.688H75.2998V123.649H99.0325V122.688Z" fill="#566779"/><path opacity="0.25" d="M100.446 125.075H75.2998V126.037H100.446V125.075Z" fill="#566779"/><path opacity="0.25" d="M95.2892 127.464H75.2998V128.426H95.2892V127.464Z" fill="#566779"/><path opacity="0.1" d="M100.673 131.435H75.2998V131.684H100.673V131.435Z" fill="#566779"/><path opacity="0.4" d="M81.0554 133.918H75.2998V134.879H81.0554V133.918Z" fill="#566779"/><path opacity="0.15" d="M100.685 133.918H94.9297V134.879H100.685V133.918Z" fill="#566779"/><path opacity="0.3" d="M100.446 115.732H75.2998V117.47H100.446V115.732Z" fill="#566779"/><path opacity="0.3" d="M93.1422 118.964H75.2998V120.702H93.1422V118.964Z" fill="#566779"/><path opacity="0.25" d="M83.5169 112.281H75.2998V113.77H83.5169V112.281Z" fill="#566779"/><path opacity="0.25" d="M96.7553 112.281H84.4297V113.77H96.7553V112.281Z" fill="#566779"/><path opacity="0.2" d="M75.2998 94.4084H100.446V109.943H75.2998V94.4084Z" fill="#566779"/><g opacity="0.4"><path d="M89.615 100.111C89.615 100.306 89.6681 100.496 89.7676 100.658C89.867 100.82 90.0084 100.946 90.1738 101.021C90.3392 101.096 90.5213 101.115 90.6969 101.077C90.8726 101.039 91.0339 100.946 91.1607 100.808C91.2874 100.671 91.3737 100.495 91.4088 100.304C91.4439 100.113 91.4262 99.9152 91.3579 99.7352C91.2895 99.5552 91.1737 99.4013 91.0249 99.2929C90.8761 99.1845 90.7012 99.1265 90.5221 99.1262C90.403 99.126 90.2851 99.1513 90.1751 99.2007C90.065 99.2501 89.965 99.3227 89.8808 99.4141C89.7965 99.5056 89.7297 99.6143 89.6841 99.7339C89.6385 99.8535 89.615 99.9817 89.615 100.111ZM91.4264 105.036H84.1826L85.994 99.783L88.4089 103.066L89.6164 102.081L91.4264 105.036Z" fill="white"/></g><path opacity="0.25" d="M67.1272 76.7245H43.3945V77.6861H67.1272V76.7245Z" fill="#566779"/><path opacity="0.25" d="M68.541 79.1124H43.3945V80.074H68.541V79.1124Z" fill="#566779"/><path opacity="0.25" d="M63.3839 81.5008H43.3945V82.4624H63.3839V81.5008Z" fill="#566779"/><path opacity="0.1" d="M68.7675 85.4724H43.3945V85.7206H68.7675V85.4724Z" fill="#566779"/><path opacity="0.4" d="M49.1501 87.9546H43.3945V88.9162H49.1501V87.9546Z" fill="#566779"/><path opacity="0.15" d="M68.78 87.9546H63.0244V88.9162H68.78V87.9546Z" fill="#566779"/><path opacity="0.3" d="M68.541 69.2728H43.3945V71.0104H68.541V69.2728Z" fill="#566779"/><path opacity="0.3" d="M61.237 73.0011H43.3945V74.7387H61.237V73.0011Z" fill="#566779"/><path opacity="0.25" d="M51.6116 65.821H43.3945V67.3104H51.6116V65.821Z" fill="#566779"/><path opacity="0.25" d="M64.85 65.821H52.5244V67.3104H64.85V65.821Z" fill="#566779"/><path opacity="0.2" d="M43.3945 47.9489H68.541V63.4832H43.3945V47.9489Z" fill="#566779"/><g opacity="0.4"><path d="M57.7097 53.6516C57.7097 53.8463 57.7628 54.0367 57.8623 54.1986C57.9617 54.3606 58.1031 54.4868 58.2685 54.5614C58.4339 54.636 58.616 54.6556 58.7916 54.6177C58.9673 54.5798 59.1287 54.4862 59.2554 54.3486C59.3821 54.211 59.4685 54.0356 59.5036 53.8447C59.5387 53.6537 59.5209 53.4557 59.4526 53.2757C59.3842 53.0957 59.2684 52.9418 59.1196 52.8334C58.9709 52.725 58.7959 52.6669 58.6168 52.6666C58.4978 52.6664 58.3798 52.6918 58.2698 52.7412C58.1597 52.7906 58.0597 52.8631 57.9755 52.9546C57.8912 53.0461 57.8244 53.1547 57.7788 53.2743C57.7332 53.3939 57.7097 53.5221 57.7097 53.6516ZM59.5211 58.5764H52.2773L54.0888 53.3234L56.5037 56.6064L57.7111 55.6215L59.5211 58.5764Z" fill="white"/></g><path opacity="0.25" d="M34.7151 80.9597H10.9824V81.9213H34.7151V80.9597Z" fill="#566779"/><path opacity="0.25" d="M36.3553 83.3476H10.9824V84.3092H36.3553V83.3476Z" fill="#566779"/><path opacity="0.25" d="M30.9718 85.736H10.9824V86.6976H30.9718V85.736Z" fill="#566779"/><path opacity="0.1" d="M36.3553 89.7076H10.9824V89.9558H36.3553V89.7076Z" fill="#566779"/><path opacity="0.4" d="M16.738 92.1898H10.9824V93.1514H16.738V92.1898Z" fill="#566779"/><path opacity="0.15" d="M36.3679 92.1898H30.6123V93.1514H36.3679V92.1898Z" fill="#566779"/><path opacity="0.3" d="M36.1586 76.9831H10.9824V78.7207H36.1586V76.9831Z" fill="#566779"/><path opacity="0.25" d="M19.1995 73.5313H10.9824V75.0207H19.1995V73.5313Z" fill="#566779"/><path opacity="0.25" d="M32.4379 73.5313H20.1123V75.0207H32.4379V73.5313Z" fill="#566779"/><path opacity="0.2" d="M10.9824 55.6592H36.1289V71.1936H10.9824V55.6592Z" fill="#566779"/><g opacity="0.4"><path d="M25.2976 61.3619C25.2976 61.5567 25.3507 61.747 25.4502 61.909C25.5496 62.0709 25.691 62.1972 25.8564 62.2718C26.0218 62.3463 26.2039 62.3659 26.3795 62.3281C26.5552 62.2902 26.7166 62.1965 26.8433 62.0589C26.97 61.9213 27.0564 61.7459 27.0915 61.555C27.1266 61.364 27.1088 61.166 27.0405 60.986C26.9721 60.806 26.8563 60.6521 26.7075 60.5437C26.5587 60.4353 26.3838 60.3773 26.2047 60.377C26.0856 60.3768 25.9677 60.4021 25.8577 60.4515C25.7476 60.5009 25.6476 60.5734 25.5634 60.6649C25.4791 60.7564 25.4123 60.8651 25.3667 60.9846C25.3211 61.1042 25.2976 61.2324 25.2976 61.3619ZM27.109 66.2867H19.8652L21.6766 61.0338L24.0915 64.3168L25.299 63.3318L27.109 66.2867Z" fill="white"/></g><path opacity="0.25" d="M99.0325 76.7245H75.2998V77.6861H99.0325V76.7245Z" fill="#566779"/><path opacity="0.25" d="M100.673 79.1124H75.2998V80.074H100.673V79.1124Z" fill="#566779"/><path opacity="0.25" d="M95.2892 81.5008H75.2998V82.4624H95.2892V81.5008Z" fill="#566779"/><path opacity="0.1" d="M100.673 85.4724H75.2998V85.7206H100.673V85.4724Z" fill="#566779"/><path opacity="0.4" d="M81.0554 87.9546H75.2998V88.9162H81.0554V87.9546Z" fill="#566779"/><path opacity="0.15" d="M100.685 87.9546H94.9297V88.9162H100.685V87.9546Z" fill="#566779"/><path opacity="0.3" d="M100.476 72.7479H75.2998V74.4855H100.476V72.7479Z" fill="#566779"/><path opacity="0.25" d="M83.5169 69.2961H75.2998V70.7855H83.5169V69.2961Z" fill="#566779"/><path opacity="0.25" d="M96.7553 69.2961H84.4297V70.7855H96.7553V69.2961Z" fill="#566779"/><path opacity="0.2" d="M75.2998 51.424H100.446V66.9584H75.2998V51.424Z" fill="#566779"/><g opacity="0.4"><path d="M89.615 57.1267C89.615 57.3215 89.6681 57.5118 89.7676 57.6738C89.867 57.8357 90.0084 57.962 90.1738 58.0365C90.3392 58.1111 90.5213 58.1307 90.6969 58.0928C90.8726 58.055 91.0339 57.9613 91.1607 57.8237C91.2874 57.6861 91.3737 57.5107 91.4088 57.3198C91.4439 57.1288 91.4262 56.9308 91.3579 56.7508C91.2895 56.5708 91.1737 56.4169 91.0249 56.3085C90.8761 56.2001 90.7012 56.1421 90.5221 56.1418C90.403 56.1416 90.2851 56.1669 90.1751 56.2163C90.065 56.2657 89.965 56.3382 89.8808 56.4297C89.7965 56.5212 89.7297 56.6298 89.6841 56.7494C89.6385 56.869 89.615 56.9972 89.615 57.1267ZM91.4264 62.0515H84.1826L85.994 56.7986L88.4089 60.0816L89.6164 59.0966L91.4264 62.0515Z" fill="white"/></g></svg>',
				'title' => __( 'Masonry Layout', 'rishi' ),
			),
		);
	}

	/**
	 * Set default value for author page.
	 */
	protected static function get_author_default_value() {

		$author_defaults = array(
			'author_page_layout'        => 'classic',
			'author_post_navigation'    => 'numbered',
			'author_sidebar_layout'     => 'default-sidebar',
			'author_layout'             => 'default',
			'author_layout_streched_ed' => 'no',
			'breadcrumbs_ed_author'     => 'no',
		);

		return $author_defaults;
	}
}
