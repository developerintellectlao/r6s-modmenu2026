<?php
/**
 * Class Logo.
 */
namespace Rishi\Customizer\Header\Elements;

use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Bookmark extends Abstracts\Builder_Element {

	public function get_id() {
		return 'bookmark';
	}

	public function get_builder_type() {
		return 'header';
	}

	public function get_label() {
		return __( 'Bookmark', 'rishi' );
	}

	public function config() {
		return array(
			'name' => $this->get_label(),
			'visibilityKey' => 'header_hide_' . $this->get_id(),
		);
	}

	public static function is_enabled(){
		return rishi_is_pro_activated();
	}

	/**
	 * Add customizer settings for the element
	 *
	 * @return array get options
	 */
	public function get_options() {

		$options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title' => __( 'General', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [
					'header_hide_' . $this->get_id() => [
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],
					'header_bookmark_type' => [
						'control' => ControlTypes::IMAGE_PICKER,
						'label'   => __( 'BookMark Type', 'rishi' ),
						'value'   => 'bookmark-one',
						'divider' => 'top',
						'attr'    => [
							'data-type'    => 'background',
							'data-usage'   => 'bookmark',
							'data-columns' => '3',
						],
						'choices' => [
							'bookmark-one' => [
								'src' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 3C3 2.20435 3.31607 1.44129 3.87868 0.87868C4.44129 0.316071 5.20435 0 6 0L18 0C18.7956 0 19.5587 0.316071 20.1213 0.87868C20.6839 1.44129 21 2.20435 21 3V23.25C20.9999 23.3857 20.9631 23.5188 20.8933 23.6351C20.8236 23.7515 20.7236 23.8468 20.604 23.9108C20.4844 23.9748 20.3497 24.0052 20.2142 23.9988C20.0787 23.9923 19.9474 23.9492 19.8345 23.874L12 19.6515L4.1655 23.874C4.05256 23.9492 3.92135 23.9923 3.78584 23.9988C3.65033 24.0052 3.5156 23.9748 3.396 23.9108C3.2764 23.8468 3.17641 23.7515 3.10667 23.6351C3.03694 23.5188 3.00007 23.3857 3 23.25V3ZM6 1.5C5.60218 1.5 5.22064 1.65804 4.93934 1.93934C4.65804 2.22064 4.5 2.60218 4.5 3V21.849L11.5845 18.126C11.7076 18.0441 11.8521 18.0004 12 18.0004C12.1479 18.0004 12.2924 18.0441 12.4155 18.126L19.5 21.849V3C19.5 2.60218 19.342 2.22064 19.0607 1.93934C18.7794 1.65804 18.3978 1.5 18 1.5H6Z" fill="black"/>
                                </svg>',
								'title' => __( 'BookMark One', 'rishi' ),
							],

							'bookmark-two' => [
								'src' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 2.01001H6C4.794 2.01001 3 2.80901 3 5.01001V19.01C3 21.211 4.794 22.01 6 22.01H21V20.01H6.012C5.55 19.998 5 19.815 5 19.01C5 18.909 5.009 18.819 5.024 18.737C5.136 18.162 5.607 18.02 6.011 18.01H20C20.018 18.01 20.031 18.001 20.049 18H21V4.01001C21 2.90701 20.103 2.01001 19 2.01001ZM19 16.01H5V5.01001C5 4.20401 5.55 4.02201 6 4.01001H13V11.01L15 10.01L17 11.01V4.01001H19V16.01Z" fill="black"/>
                                </svg>',
								'title' => __( 'BookMark Two', 'rishi' ),
							],

							'bookmark-three' => [
								'src' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.8 6.59995C22.8 8.03212 22.2311 9.40563 21.2184 10.4183C20.2057 11.431 18.8322 11.9999 17.4 11.9999C15.9679 11.9999 14.5944 11.431 13.5817 10.4183C12.569 9.40563 12 8.03212 12 6.59995C12 5.16778 12.569 3.79427 13.5817 2.78157C14.5944 1.76888 15.9679 1.19995 17.4 1.19995C18.8322 1.19995 20.2057 1.76888 21.2184 2.78157C22.2311 3.79427 22.8 5.16778 22.8 6.59995V6.59995ZM18 4.19995C18 4.04082 17.9368 3.88821 17.8243 3.77569C17.7118 3.66317 17.5592 3.59995 17.4 3.59995C17.2409 3.59995 17.0883 3.66317 16.9758 3.77569C16.8633 3.88821 16.8 4.04082 16.8 4.19995V5.99995H15C14.8409 5.99995 14.6883 6.06316 14.5758 6.17569C14.4633 6.28821 14.4 6.44082 14.4 6.59995C14.4 6.75908 14.4633 6.91169 14.5758 7.02421C14.6883 7.13674 14.8409 7.19995 15 7.19995H16.8V8.99995C16.8 9.15908 16.8633 9.31169 16.9758 9.42421C17.0883 9.53674 17.2409 9.59995 17.4 9.59995C17.5592 9.59995 17.7118 9.53674 17.8243 9.42421C17.9368 9.31169 18 9.15908 18 8.99995V7.19995H19.8C19.9592 7.19995 20.1118 7.13674 20.2243 7.02421C20.3368 6.91169 20.4 6.75908 20.4 6.59995C20.4 6.44082 20.3368 6.28821 20.2243 6.17569C20.1118 6.06316 19.9592 5.99995 19.8 5.99995H18V4.19995ZM18 19.8215V13.1735C18.4061 13.1368 18.8078 13.0625 19.2 12.9515V20.9999C19.2 21.1106 19.1693 21.219 19.1114 21.3133C19.0535 21.4076 18.9707 21.484 18.8721 21.5342C18.7735 21.5844 18.663 21.6063 18.5527 21.5976C18.4424 21.5888 18.3367 21.5498 18.2472 21.4847L12 16.9415L5.75285 21.4847C5.66337 21.5498 5.55765 21.5888 5.44737 21.5976C5.33709 21.6063 5.22655 21.5844 5.12796 21.5342C5.02936 21.484 4.94656 21.4076 4.88869 21.3133C4.83082 21.219 4.80014 21.1106 4.80005 20.9999V5.39995C4.80005 4.6043 5.11612 3.84124 5.67873 3.27863C6.24134 2.71602 7.0044 2.39995 7.80005 2.39995H12.3084C12.0028 2.77011 11.7385 3.17249 11.52 3.59995H7.80005C7.32266 3.59995 6.86482 3.78959 6.52726 4.12716C6.18969 4.46472 6.00005 4.92256 6.00005 5.39995V19.8215L11.6472 15.7151C11.7498 15.6406 11.8733 15.6005 12 15.6005C12.1268 15.6005 12.2503 15.6406 12.3528 15.7151L18 19.8215Z" fill="black"/>
                                </svg>',
								'title' => __( 'BookMark Three', 'rishi' ),
							],
						],
					],
					'header_bookmark_text' => [
						'control'             => ControlTypes::INPUT_TEXT,
						'design'              => 'block',
						'disableRevertButton' => true,
						'value'               => __('Bookmark', 'rishi'),
					],
                    'header_bookmark_size'                        => [
						'label'      => __( 'Size', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '20px',
						'responsive' => false,
						'divider'    => 'top',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 0, 'max' => 100 ],
						] ),
					],
                    'bookmark_visibility' => [
						'label' => __( 'Bookmark Visibility', 'rishi' ),
						'control' => ControlTypes::VISIBILITY,
						'divider' => 'top',
						'design' => 'block',
						'value' => [
							'desktop' => 'desktop',
							'tablet' => 'tablet',
							'mobile' => 'mobile',
						],
						'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys( [
							'desktop' => __( 'Desktop', 'rishi' ),
							'tablet' => __( 'Tablet', 'rishi' ),
							'mobile' => __( 'Mobile', 'rishi' ),
						] )
					],
				],
			],
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title' => __( 'Design', 'rishi' ),
				'control' => ControlTypes::TAB,
				'options' => [
                    'headerBookmarkColor' => [
						'label'   => __( 'Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'  => 'inline',
						'divider'   => 'bottom',
						'value'   => [
							'default' => [
								'color' => 'var(--paletteColor3)',
							],

							'hover'   => [
								'color' => 'var(--paletteColor4)',
							],
						],

						'pickers' => [
							[
								'title'   => __( 'Initial', 'rishi' ),
								'id'      => 'default',
								'inherit' => 'var(--bookmarkInitialColor)',
							],

							[
								'title'   => __( 'Hover', 'rishi' ),
								'id'      => 'hover',
								'inherit' => 'var(--bookmarkHoverColor)',
							],
						],
					],

                    'headerBookmarkCountColor' => [
						'label'   => __( 'Count Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'  => 'inline',
						'divider'   => 'bottom',
						'value'   => [
							'default' => [
								'color' => 'var(--paletteColor5)',
							],

							'hover'   => [
								'color' => 'var(--paletteColor5)',
							],
						],

						'pickers' => [
							[
								'title'   => __( 'Initial', 'rishi' ),
								'id'      => 'default',
								'inherit' => 'var(--bookmarkCountInitialColor)',
							],

							[
								'title'   => __( 'Hover', 'rishi' ),
								'id'      => 'hover',
								'inherit' => 'var(--bookmarkCountHoverColor)',
							],
						],
					],

                    'headerBookmarkCountBGColor' => [
						'label'   => __( 'Count Background Color', 'rishi' ),
						'control'    => ControlTypes::COLOR_PICKER,
						'colorPalette' => true,
						'design'  => 'inline',
						'divider'   => 'bottom',
						'value'   => [
							'default' => [
								'color' => 'var(--paletteColor2)',
							],

							'hover'   => [
								'color' => 'var(--paletteColor2)',
							],
						],

						'pickers' => [
							[
								'title'   => __( 'Initial', 'rishi' ),
								'id'      => 'default',
								'inherit' => 'var(--bookmarkCountBgInitialColor)',
							],

							[
								'title'   => __( 'Hover', 'rishi' ),
								'id'      => 'hover',
								'inherit' => 'var(--bookmarkCountBgHoverColor)',
							],
						],
					],
				]
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

        $header_bookmark_size = $this->get_mod_value( 'header_bookmark_size', '20px' );
        $headerBookmarkColor  = $this->get_mod_value( 'headerBookmarkColor',[
            'default' => [
                'color' => 'var(--paletteColor3)',
            ],

            'hover'   => [
                'color' => 'var(--paletteColor4)',
            ],
        ]);
        $headerBookmarkCountColor   = $this->get_mod_value( 'headerBookmarkCountColor',[
            'default' => [
                'color' => 'var(--paletteColor5)',
            ],

            'hover'   => [
                'color' => 'var(--paletteColor5)',
            ]
        ]);
        $headerBookmarkCountBGColor = $this->get_mod_value( 'headerBookmarkCountBGColor', [
			'default' => [
				'color' => 'var(--paletteColor2)',
			],

			'hover'   => [
				'color' => 'var(--paletteColor2)',
			]
		]);

		$options = array(
			'header_bookmark_size' => array(
				'selector'     => '#rishi-bookmark',
				'variableName' => 'bookmark_icon_size',
				'value'        => $header_bookmark_size,
				'responsive'   => true,
				'type'         => 'slider'
			),
            'headerBookmarkColor' => array(
				'value'     => $headerBookmarkColor,
				'type'      => 'color',
				'default'   => [
                    'default' => [
                        'color' => 'var(--paletteColor3)',
                    ],

                    'hover'   => [
                        'color' => 'var(--paletteColor4)',
                    ],
                ],
				'variables' => array(
					'default' => array(
						'variable' => 'BookmarkColor',
						'selector' => '#rishi-bookmark',
					),
					'hover'   => array(
						'variable' => 'BookmarkHoverColor',
						'selector' => '#rishi-bookmark',
					),
				),
			),
            'headerBookmarkCountColor' => array(
				'value'     => $headerBookmarkCountColor,
				'type'      => 'color',
				'default'   => [
                    'default' => [
                        'color' => 'var(--paletteColor5)',
                    ],

                    'hover'   => [
                        'color' => 'var(--paletteColor5)',
                    ],
                ],
				'variables' => array(
					'default' => array(
						'variable' => 'BookmarkCountColor',
						'selector' => '#rishi-bookmark',
					),
					'hover'   => array(
						'variable' => 'BookmarkCountHoverColor',
						'selector' => '#rishi-bookmark',
					),
				),
			),
            'headerBookmarkCountBGColor' => array(
				'value'     => $headerBookmarkCountBGColor,
				'type'      => 'color',
				'default'   => [
                    'default' => [
                        'color' => 'var(--paletteColor2)',
                    ],

                    'hover'   => [
                        'color' => 'var(--paletteColor2)',
                    ],
                ],
				'variables' => array(
					'default' => array(
						'variable' => 'BookmarkCountBgColor',
						'selector' => '#rishi-bookmark',
					),
					'hover'   => array(
						'variable' => 'BookmarkCountBgHoverColor',
						'selector' => '#rishi-bookmark',
					),
				),
			),

		);

		return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
	}

	/**
	 * Add markup for the element
	 *
	 * @param string $desktop
	 * @return void
	 */
	public function render( $device = 'desktop') {

        $class = '';

        $visibility = $this->get_mod_value(
            'bookmark_visibility',
            [
                'desktop' => 'desktop',
                'tablet'  => 'tablet',
                'mobile'  => 'mobile',
            ]
        );
        $class .= $this->get_visible_device_class( $visibility );

        $header_bookmark_type = $this->get_mod_value( 'header_bookmark_type','bookmark-one' );

        $icon_class = 'read-it-later added ' . $header_bookmark_type;

        $bookmarktext = $this->get_mod_value( 'header_bookmark_text', __('Bookmark', 'rishi') );

        $bookMarkCount = ( isset( $_COOKIE['BookmarkID'] ) ? $_COOKIE['BookmarkID'] : '' );

        $bookMarkArr = explode( ',', $bookMarkCount );

        $bookMarkValue = array_filter( $bookMarkArr );

        $args = [
            'post_type'  => 'page',
            'fields'     => 'ids',
            'nopaging'   => true,
            'meta_key'   => '_wp_page_template',
            'meta_value' => 'page-bookmark.php'
        ];
        $pages = get_posts( $args );
        if( $pages ){
            $permalink = esc_url( get_permalink( $pages[0] ) );
        }else{
            $permalink = '';
        }
        ?>
        <div class="rishi-bookmark" id="rishi-bookmark">
            <a href="<?php echo $permalink; ?>" class="<?php echo esc_attr( $class ); ?>">
                <span class="read-it-later-count"><?php echo absint( count( $bookMarkValue ) ); ?></span>
                <span class="read-it-later-hover-text"><?php echo esc_html( $bookmarktext ); ?></span>
                <span class="<?php echo esc_attr( $icon_class ); ?>"></span>
            </a>
        </div>
        <?php 
	}
}
