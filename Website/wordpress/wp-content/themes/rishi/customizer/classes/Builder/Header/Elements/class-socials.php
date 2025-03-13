<?php
/**
 * Class Socials.
 */

namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;
use Rishi\Customizer\Helpers\Defaults;
/**
 * Class Socials
 */
class Socials extends Abstracts\Builder_Element {

	public function get_id() {
		return 'socials';
	}

    public function get_builder_type() {
		return 'header';
	}

    public function get_label() {
		return __('Socials', 'rishi');
	}

	public function config() {
		return array(
			'name' => $this->get_label(),
			'visibilityKey' => 'header_hide_' . $this->get_id(),
		);
	}

    /**
     * Add customizer settings for the element
     *
     * @return void
     */
    public function get_options(){
        $options = [
            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                    'title' => __('General', 'rishi'),
                    'control' => ControlTypes::TAB,
                    'options' => [
                        'header_hide_' . $this->get_id() => [
                            'label'               => __('Choose Social Media', 'rishi'),
                            'control'             => ControlTypes::HIDDEN,
                            'value'               => false,
                            'disableRevertButton' => true,
                            'help'                => __('Hide', 'rishi'),
                        ],
                        'header_socials'        => [
                            'label'      => __( 'Choose Social Media', 'rishi' ),
                            'help'		 => __( 'Select the social media icons you want to display.', 'rishi' ),
                            'control'       => ControlTypes::LAYERS,
                            'manageable' => true,
                            'value'      => [
                                [
                                    'id'      => 'facebook',
                                    'enabled' => true,
                                    'title'   => __( 'Facebook', 'rishi' ),
                                    'url'     => ''
                                ],
                                [
                                    'id'      => 'twitter',
                                    'enabled' => true,
                                    'title'   => __( 'Twitter', 'rishi' ),
                                    'url'     => ''
                                ],
                                [
                                    'id'      => 'instagram',
                                    'enabled' => true,
                                    'title'   => __( 'Instagram', 'rishi' ),
                                    'url'     => ''
                                ]
                            ],

                            'settings'   => [
                                'facebook' => [
                                    'label'   => __( 'Facebook', 'rishi' ),
                                    'icon' => Defaults::lists_all_svgs('facebook')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'twitter' => [
                                    'label'   => __( 'Twitter', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('twitter')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'instagram' => [
                                    'label'   => __( 'Instagram', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('instagram')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'pinterest' => [
                                    'label'   => __( 'Pinterest', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('pinterest')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'dribble' => [
                                    'label'   => __( 'Dribbble', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('dribble')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'behance' => [
                                    'label'   => __( 'Behance', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('behance')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'unsplash' => [
                                    'label'   => __( 'Unsplash', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('unsplash')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'five-hundred-px' => [
                                    'label'   => __( 'Five Hundred PX', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('five-hundred-px')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'linkedin' => [
                                    'label'   => __( 'Linkedin', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('linkedin')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'WordPress' => [
                                    'label'   => __( 'WordPress', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('WordPress')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'parler' => [
                                    'label'   => __( 'Parler', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('parler')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'medium' => [
                                    'label'   => __( 'Medium', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('medium')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'slack' => [
                                    'label'   => __( 'Slack', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('slack')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'codepen' => [
                                    'label'   => __( 'Codepen', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('codepen')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'reddit' => [
                                    'label'   => __( 'Reddit', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('reddit')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'twitch' => [
                                    'label'   => __( 'Twitch', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('twitch')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'tiktok' => [
                                    'label'   => __( 'Tiktok', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('tiktok')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'snapchat' => [
                                    'label'   => __( 'Snapchat', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('snapchat')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'spotify' => [
                                    'label'   => __( 'Spotify', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('spotify')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'soundcloud' => [
                                    'label'   => __( 'Soundcloud', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('soundcloud')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'apple_podcast' => [
                                    'label'   => __( 'Apple Podcasts', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('apple_podcast')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'patreon' => [
                                    'label'   => __( 'Patreon', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('patreon')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'alignable' => [
                                    'label'   => __( 'Alignable', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('alignable')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'skype' => [
                                    'label'   => __( 'Skype', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('skype')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'github' => [
                                    'label'   => __( 'Github', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('github')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'gitlab' => [
                                    'label'   => __( 'Gitlab', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('gitlab')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'youtube' => [
                                    'label'   => __( 'YouTube', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('youtube')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'vimeo' => [
                                    'label'   => __( 'Vimeo', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('vimeo')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'dtube' => [
                                    'label'   => __( 'DTube', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('dtube')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'vk' => [
                                    'label'   => __( 'VK', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('vk')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'ok' => [
                                    'label'   => __( 'Odnoklassniki', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('ok')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'rss' => [
                                    'label'   => __( 'RSS', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('rss')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'discord' => [
                                    'label'   => __( 'Discord', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('discord')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'tripadvisor' => [
                                    'label'   => __( 'TripAdvisor', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('tripadvisor')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'foursquare' => [
                                    'label'   => __( 'Foursquare', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('foursquare')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'yelp' => [
                                    'label'   => __( 'Yelp', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('yelp')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'hacker_news' => [
                                    'label'   => __( 'Hacker News', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('hacker_news')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'xing' => [
                                    'label'   => __( 'Xing', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('xing')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'whatsapp' => [
                                    'label'   => __( 'Whatsapp', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('whatsapp')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'flipboard' => [
                                    'label'   => __( 'Flipboard', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('flipboard')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'viber' => [
                                    'label'   => __( 'Viber', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('viber')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                                'telegram' => [
                                    'label'   => __( 'Telegram', 'rishi' ),
                                    'icon'	  => Defaults::lists_all_svgs('telegram')['icon'],
                                    'options' => [
                                        'url'   => [
                                            'control' => ControlTypes::INPUT_TEXT,
                                            'label'   => __( 'Link', 'rishi' ),
                                            'type'    => 'link',
                                            'value'   => '',
                                            'design'  => 'block',
                                        ]
                                    ]
                                ],
                            ],
                        ],

                        'socialsIconSize' => [
                            'label'   => __('Icons Size', 'rishi'),
                            'control' => ControlTypes::INPUT_SLIDER,
                            'divider' => 'top',
                            'value'   => '15px',
                            'responsive' => false,
                            'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
                                [ 'unit' => 'px', 'min' => 5, 'max' => 50 ],
                            ] ),
                        ],

                        'socialsIconSpacing' => [
                            'label'      => __('Item Spacing', 'rishi'),
                            'control'    => ControlTypes::INPUT_SLIDER,
                            'value'      => '15px',
                            'responsive' => false,
                            'divider'    => 'top:bottom',
                            'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
                                [ 'unit' => 'px', 'min' => 0, 'max' => 50 ],
                            ] ),
                        ],

                    ],
                ],

            \Rishi\Customizer\Helpers\Basic::uniqid() => [
                    'title' => __('Design', 'rishi'),
                    'control' => ControlTypes::TAB,
                    'options' => [
                        'headerSocialsColor' => [
                            'label' => __('Icons Color', 'rishi'),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value' => 'custom',
                            'view' => 'text',
                            'design' => 'block',
                            'divider' => 'bottom',
                            'choices' => [
                                'custom' => __('Custom', 'rishi'),
                                'official' => __('Official', 'rishi'),
                            ],
                        ],

                        'socialsType' => [
                            'label' => __('Icons Shape Type', 'rishi'),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value' => 'simple',
                            'view' => 'text',
                            'design' => 'block',
                            'divider' => 'bottom',
                            'choices' => [
                                'simple' => __('None', 'rishi'),
                                'rounded' => __('Rounded', 'rishi'),
                                'square' => __('Square', 'rishi'),
                            ],
                        ],

                        'socialsFillType' => [
                            'label' => __('Shape Fill Type', 'rishi'),
                            'control' => ControlTypes::INPUT_RADIO,
                            'value' => 'solid',
                            'view' => 'text',
                            'design' => 'block',
                            'divider' => 'bottom',
                            'choices' => [
                                'solid' => __('Solid', 'rishi'),
                                'outline' => __('Outline', 'rishi'),
                            ],
                            'conditions' => ['socialsType' => 'rounded|square']
                        ],

                        'icon_color_group' => [
                            'label' => __('Icons Color', 'rishi'),
                            'control'    => ControlTypes::CONTROLS_GROUP,
                            'divider' => 'bottom',
                            'conditions' => [
                                'headerSocialsColor' => 'custom'
                            ],
                            'value'      => [
                                'headerSocialsIconColor' => [
                                    'default' => [
                                        'color' => 'var(--paletteColor1)',
                                    ],
                                    'hover'   => [
                                        'color' => 'var(--paletteColor3)',
                                    ],
                                ],
                            ],

                            'settings' => [
                                'headerSocialsIconColor'    => [
                                    'label'      => __( 'Default State', 'rishi' ),
                                    'control'    => ControlTypes::COLOR_PICKER,
                                    'design'     => 'inline',
                                    'responsive' => false,
									'colorPalette'	  => true,
                                    'value'      => [
                                        'default' => [
                                            'color' => 'var(--paletteColor1)',
                                        ],
                                        'hover'   => [
                                            'color' => 'var(--paletteColor3)',
                                        ],
                                    ],
                                    'pickers'    => [
                                        [
                                            'title'   => __( 'Initial', 'rishi' ),
                                            'id'      => 'default',
                                        ],

                                        [
                                            'title'   => __( 'Hover', 'rishi' ),
                                            'id'      => 'hover',
                                        ],
                                    ],
                                ],
                            ]
                        ],

                        'icon_color_background' => [
                            'label' => __('Icons Background Color', 'rishi'),
                            'control'  => ControlTypes::CONTROLS_GROUP,
                            'divider' => 'bottom',
                            'conditions' => [
                                'socialsType' => 'rounded|square',
                                'headerSocialsColor' => 'custom'
                            ],
                            'value' => [
                                'headerSocialsIconBackground' => [
                                    'default' => [
                                        'color' => 'var(--paletteColor7)',
                                    ],

                                    'hover' => [
                                        'color' => 'var(--paletteColor6)',
                                    ],
                                ],
                            ],

                            'settings' => [
                                'headerSocialsIconBackground'    => [
                                    'label'      => __( 'Default State', 'rishi' ),
                                    'control'    => ControlTypes::COLOR_PICKER,
                                    'design'     => 'inline',
                                    'responsive' => false,
									'colorPalette'	  => true,
                                    'value'      => [
                                        'default' => [
                                            'color' => 'var(--paletteColor7)',
                                        ],
                                        'hover' => [
                                            'color' => 'var(--paletteColor6)',
                                        ]
                                    ],
                                    'pickers'    => [
                                        [
                                            'title'   => __( 'Initial', 'rishi' ),
                                            'id'      => 'default',
                                        ],

                                        [
                                            'title'   => __( 'Hover', 'rishi' ),
                                            'id'      => 'hover',
                                        ],
                                    ],
                                ],
                            ]
                        ],

                        'headerSocialsMargin' => [
                            'label' => __('Margin', 'rishi'),
                            'control' => ControlTypes::INPUT_SPACING,
							'divider' => 'bottom',
                            'value'      => \Rishi\Customizer\Helpers\Basic::spacing_value( [
                                'linked' => false,
                                'top'    => '0',
                                'left'   => '0',
                                'right'  => '0',
                                'bottom' => '0',
                                'unit'   => 'px',
                            ] ),
							'units' => \Rishi\Customizer\Helpers\Basic::get_margin_units(),
                            'responsive' => false,
                        ],
                    ],
                ],
            ];
        return $options;
    }

    /**
     * Write logic for dynamic css change for the elements
     *
     * @return
     */
    public function dynamic_styles(){
		$social_default              = Defaults::get_header_defaults();
		$socialsIconSize             = $this->get_mod_value( 'socialsIconSize', $social_default['socialsIconSize'] );
		$socialsIconSpacing          = $this->get_mod_value( 'socialsIconSpacing', $social_default['socialsIconSpacing'] );
		$headerSocialsMargin         = $this->get_mod_value( 'headerSocialsMargin', $social_default['headerSocialsMargin'] );
		$icon_color_group = $this->get_mod_value(
			'icon_color_group',
			array(
				'headerSocialsIconColor'  => $social_default['headerSocialsIconColor'],
			)
		);
		$icon_color_background = $this->get_mod_value(
			'icon_color_background',
			array(
				'headerSocialsIconBackground'  => $social_default['headerSocialsIconBackground'],
			)
		);
        $headerSocialsIconColor      = $icon_color_group['headerSocialsIconColor'];
        $headerSocialsIconBackground      = $icon_color_background['headerSocialsIconBackground'];

        $options = array(
            'headerSocialsMargin' => array(
                'selector'     => '#rishi-socials',
                'variableName' => 'margin',
                'value'        => $headerSocialsMargin,
                'type'         => 'spacing',
                'unit'         => 'px',
                'property'     => 'margin'
			),
            'headerSocialsIconColor'    => array(
				'value'     => $headerSocialsIconColor,
				'default'   => $social_default['headerSocialsIconColor'],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '.rishi-color-type-custom',
                    ),
					'hover' => array(
						'variable' => 'icon-hover-color',
						'selector' => '.rishi-color-type-custom',
					)
				),
				'type'      => 'color'
			),
            'headerSocialsIconBackground'      => array(
				'value'     => $headerSocialsIconBackground,
				'type'      => 'color',
				'default'   => $social_default['headerSocialsIconBackground'],
				'variables' => array(
					'default' => array(
						'variable' => 'background-color',
						'selector' => '.rishi-color-type-custom',
                    ),
					'hover' => array(
						'variable' => 'background-hover-color',
						'selector' => '.rishi-color-type-custom',
					)
				),
			),
            'socialsIconSize'     => array(
				'selector'     => '#rishi-socials',
				'variableName' => 'icon-size',
				'value'        => $socialsIconSize,
				'responsive'   => false,
				'type' 		   => 'slider'
			),
            'socialsIconSpacing'     => array(
				'selector'     => '#rishi-socials',
				'variableName' => 'spacing',
				'value'        => $socialsIconSpacing,
				'responsive'   => false,
				'type' 		   => 'slider'
			)
        );
        return apply_filters(
			'dynamic_header_element_'.$this->get_id().'_options',
			$options,
			$this
		);
    }

    /**
     * Renders function
     *
     * @param string $desktop
     * @return void
     */
    public function render( $device = 'desktop'){

        $main_class = 'rishi_header_socials';
        $class      = 'rishi-icons-types';

        $socialsColor    = $this->get_mod_value('headerSocialsColor', 'custom');
        $socialsType     = $this->get_mod_value('socialsType', 'simple');
        $socialsFillType = $this->get_mod_value('socialsFillType', 'solid');

        if( $socialsColor ){
            $class .= ' rishi-color-type-' . $socialsColor;
        }

        if( $socialsType ){
            $class .= ' rishi-socials-type-' . $socialsType;
        }

        if( $socialsType !== "simple" && $socialsFillType ){
            $class .= ' rishi-socials-fill-type-' . $socialsFillType;
        }

        $social_default_lists = [
            [
                'id' => 'facebook',
                'enabled' => true,
            ],

            [
                'id' => 'twitter',
                'enabled' => true,
            ],

            [
                'id' => 'instagram',
                'enabled' => true,
            ],
        ];

        $socials = $this->get_mod_value(
            'header_socials',
            $social_default_lists
        );

        if( $socials ){
        ?>
        <div class="<?php echo esc_attr( $main_class ) ?>" id="rishi-socials">
            <div class="rishi_social_box <?php echo esc_attr( $class ); ?>">
                <?php foreach( $socials as $social ){

                    //if disabled from the backend then hide the social
                    if( !$social["enabled"] ){
                        continue;
                    }
                    $social_id = Defaults::lists_all_svgs( $social['id'] );
                    $social_label = !empty( $social_id ) ? $social_id['name'] : "";
                    $social_icon = !empty( $social_id ) ? $social_id['icon'] : "";
                    $social_url = !empty( $social['url'] ) ? $social['url'] : "#";

                    ?>
                    <a href="<?php echo esc_attr( $social_url ); ?>" target="_blank" class="rishi-<?php echo esc_attr( $social['id'] ); ?>" aria-label="<?php echo esc_attr( $social_label ); ?>">
                        <span class="rishi-icon-container">
                            <?php echo $social_icon; ?>
                        </span>
                    </a>
                <?php } ?>
            </div>
        </div>
        <?php
        }
    }
}
