<?php
/**
 * Social Sharing Section Extension Setting.
 *
 * This class provides the functionality for the Social Sharing Section extension setting.
 *
 * @package Rishi_Companion\Modules\Sections\Settings
 */

namespace Rishi_Companion\Modules\Sections\Settings;

use Rishi\Customizer\Settings\Single_Setting as Single_Setting;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Helpers as Helpers;

class Social_Sharing_Setting extends Single_Setting{

    /**
     * Add settings for the extension.
     */
    public function add_settings() {
		$this->add_social_sharing_settings();

        parent::add_settings();

	}

    /**
     * Add settings for the social sharing.
     */
    protected function add_social_sharing_settings() {
        $share_prefix = 'single_blog_post_';

        $this->add_setting( 'enable_social_share', array(
			'label'   => __( 'Social Share', 'rishi-companion' ),
			'control' => ControlTypes::PANEL,
			'divider' => 'top',
			'parent'  => 'single_post_container_panel',
            'innerControls' => array(
                Helpers\Basic::uniqid() => array(
                    'title'   => __( 'General', 'rishi-companion' ),
                    'control'    => ControlTypes::TAB,
                    'options' => array(
                        $share_prefix . 'has_share_box_title'       => array(
                            'label'   => __( 'Title', 'rishi-companion' ),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ),
                        $share_prefix . 'share_box_title'                  => array(
                            'control' => ControlTypes::INPUT_TEXT,
                            'label'   => __( 'Title', 'rishi-companion' ),
                            'value'   => __( 'SHARE THIS POST', 'rishi-companion' ),
                            'design'  => 'block',
                            'conditions' => [
                                $share_prefix . 'has_share_box_title' => 'yes'
                            ]
                        ),
                        'ed_og_tags' => [
                            'label'   => __('Open Graph Meta Tags', 'rishi-companion'),
                            'help'   => __('Disable this option if you’re using Jetpack, Yoast or other plugins to maintain Open Graph Meta Tags', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'yes',
                        ],
                        $share_prefix . 'box_sticky' => [
                            'label'   => __('Sticky Share', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'box_float'                     => [
                            'label'   => __( 'Float', 'rishi-companion' ),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value'   => 'left',
                            'divider' => 'top',
                            'design'  => 'block',
                            'choices' => [
                                'left' => __( 'Left', 'rishi-companion' ),
                                'right' => __( 'Right', 'rishi-companion' ),
                            ],
                            'conditions' => [
                                $share_prefix . 'box_sticky' => 'yes'
                            ]
                        ],

                        $share_prefix . 'sticky_top_offset'                      => [
                            'label'      => __( 'Top Offset', 'rishi-companion' ),
                            'control'    => ControlTypes::INPUT_SLIDER,
                            'value'      => '170px',
                            'divider'    => 'top',
                            'responsive' => false,
                            'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
                                [ 'unit' => 'px', 'min' => 5, 'max' => 500 ],
                            ] ),
                            'conditions' => [
                                $share_prefix . 'box_sticky' => 'yes'
                            ]
                        ],

                        $share_prefix . 'sticky_side_offset'                      => [
                            'label'      => __( 'Side Offset', 'rishi-companion' ),
                            'control'    => ControlTypes::INPUT_SLIDER,
                            'value'      => '0px',
                            'divider'    => 'top',
                            'responsive' => false,
                            'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
                                [ 'unit' => 'px', 'min' => 5, 'max' => 100 ],
                            ] ),
                            'conditions' => [
                                $share_prefix . 'box_sticky' => 'yes'
                            ]
                        ],

                        $share_prefix . 'share_alignment'                     => [
                            'label'   => __( 'Alignment', 'rishi-companion' ),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value'   => 'left',
                            'divider' => 'top',
                            'design'  => 'block',
                            'choices' => [
                                'left'   => __( 'Left','rishi-companion' ),
                                'center' => __( 'Center','rishi-companion' ),
                                'right'  => __( 'Right','rishi-companion' ),
                            ],
                            'conditions' => [
                                $share_prefix . 'box_sticky' => 'no'
                            ]
                        ],

                        $share_prefix . 'box_shape'                     => [
                            'label'   => __( 'Shape', 'rishi-companion' ),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value'   => 'square',
                            'divider' => 'top',
                            'design'  => 'block',
                            'choices' => [
                                'square' => __( 'Square', 'rishi-companion' ),
                                'circle' => __( 'Circle', 'rishi-companion' ),
                            ]
                        ],

                        \Rishi\Customizer\Helpers\Basic::uniqid() => [
                            'label'   => __( 'Social Networks', 'rishi-companion' ),
                            'control' => ControlTypes::TITLE,
                        ],
                        $share_prefix . 'share_facebook' => [
                            'label'   => __('Facebook', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'yes',
                        ],
                        $share_prefix . 'share_twitter' => [
                            'label'   => __('Twitter', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'yes',
                        ],
                        $share_prefix . 'share_pinterest' => [
                            'label'   => __('Pinterest', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'yes',
                        ],
                        $share_prefix . 'share_linkedin' => [
                            'label'   => __('Linkedin', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'yes',
                        ],
                        $share_prefix . 'share_email' => [
                            'label'   => __('Email', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_reddit' => [
                            'label'   => __('Reddit', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_telegram' => [
                            'label'   => __('Telegram', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_viber' => [
                            'label'   => __('Viber', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_whatsapp' => [
                            'label'   => __('Whatsapp', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_vk' => [
                            'label'   => __('VKontakte', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_tumblr' => [
                            'label'   => __('Tumblr', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_flipboard' => [
                            'label'   => __('Flipboard', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_weibo' => [
                            'label'   => __('Weibo', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_ok' => [
                            'label'   => __('Odnoklassniki', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_xing' => [
                            'label'   => __('Xing', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                        ],
                        $share_prefix . 'share_links_nofollow' => [
                            'label'   => __('Set links to nofollow', 'rishi-companion'),
                            'control' => ControlTypes::INPUT_SWITCH,
                            'value'   => 'no',
                            'divider' => 'top'
                        ],

                        $share_prefix . 'share_box_icon_size'                      => [
                            'label'      => __( 'Icon Size', 'rishi-companion' ),
                            'control'    => ControlTypes::INPUT_SLIDER,
                            'value'      => '15px',
                            'divider'    => 'top',
                            'responsive' => false,
                            'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
                                [ 'unit' => 'px', 'min' => 5, 'max' => 50 ],
                            ] ),
                        ],

                        $share_prefix . 'icons_spacing'                        => [
                            'label'      => __( 'Icons Spacing', 'rishi-companion' ),
                            'control'       => ControlTypes::INPUT_SPACING,
                            'divider'    => 'bottom',
                            'value'      => array(
                                'desktop' => \Rishi\Customizer\Helpers\Basic::spacing_value(
                                    array(
                                        'linked' => false,
                                        'top'    => '0',
                                        'left'   => '0',
                                        'right'  => '10',
                                        'bottom' => '10',
                                        'unit'	 => 'px'
                                    )
                                ),
                                'tablet'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
                                    array(
                                        'linked' => false,
                                        'top'    => '0',
                                        'left'   => '0',
                                        'right'  => '10',
                                        'bottom' => '10',
                                        'unit'	 => 'px'
                                    )
                                ),
                                'mobile'  => \Rishi\Customizer\Helpers\Basic::spacing_value(
                                    array(
                                        'linked' => false,
                                        'top'    => '0',
                                        'left'   => '0',
                                        'right'  => '10',
                                        'bottom' => '10',
                                        'unit'	 => 'px'
                                    )
                                ),
                            ),
                            'units' => \Rishi\Customizer\Helpers\Basic::get_basic_units(),
                            'responsive' => true,
                        ],
                        $share_prefix . 'visibility' => [
                            'label' => __('Visibility', 'rishi-companion'),
                            'control' => ControlTypes::VISIBILITY,
                            'design' => 'block',
                            'divider'=> 'top',
                            'value' => [
                                'desktop' => 'desktop',
                                'tablet' => 'tablet',
                            ],

                            'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys([
                                'desktop' => __('Desktop', 'rishi-companion'),
                                'tablet' => __('Tablet', 'rishi-companion'),
                                'mobile' => __('Mobile', 'rishi-companion'),
                            ]),
                        ],
                    ),
                ),
                Helpers\Basic::uniqid() => array(
                    'title'   => __( 'Design', 'rishi-companion' ),
                    'control'    => ControlTypes::TAB,
                    'options' => array(
                        'social_share_color_option'                     => [
                            'label'   => __( 'Icon Color', 'rishi-companion' ),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value'   => 'official',
                            'divider' => 'top',
                            'design'  => 'block',
                            'choices' => [
                                'official' => __( 'Brand', 'rishi-companion' ),
                                'custom'   => __( 'Custom', 'rishi-companion' ),
                            ]
                        ],

                        $share_prefix . 'share_items_icon_color' => [
                            'label' => __('Icons Color', 'rishi-companion'),
                            'control'  => ControlTypes::COLOR_PICKER,
                            'colorPalette'	  => true,
                            'design' => 'inline',
                            'divider'=> 'bottom',
                            'skipEditPalette' => true,
                            'value' => [
                                'default' => [
                                    'color' => 'var(--paletteColor5)',
                                ],
                                'hover'   => [
                                    'color' => 'var(--paletteColor5)',
                                ],
                            ],
                            'pickers' => [
                                [
                                    'title' => __('Initial', 'rishi-companion'),
                                    'id' 	=> 'default',
                                ],
                                [
                                    'title'   => __( 'Hover', 'rishi-companion' ),
                                    'id'      => 'hover',
                                ],
                            ],
                            'conditions' => ['social_share_color_option' => 'custom'],
                        ],

                        $share_prefix . 'share_items_background' => [
                            'label' => __('Background Color', 'rishi-companion'),
                            'control'  => ControlTypes::COLOR_PICKER,
                            'colorPalette'	  => true,
                            'design' => 'inline',
                            'divider'=> 'bottom',
                            'skipEditPalette' => true,
                            'value' => [
                                'default' => [
                                    'color' => 'var(--paletteColor3)',
                                ],
                                'hover'   => [
                                    'color' => 'var(--paletteColor4)',
                                ],
                            ],
                            'pickers' => [
                                [
                                    'title' => __('Initial', 'rishi-companion'),
                                    'id' 	=> 'default',
                                ],
                                [
                                    'title'   => __( 'Hover', 'rishi-companion' ),
                                    'id'      => 'hover',
                                ],
                            ],
                        'conditions' => ['social_share_color_option' => 'custom'],
                    ],
                    ),
                ),
            )
		) );

    }

}
