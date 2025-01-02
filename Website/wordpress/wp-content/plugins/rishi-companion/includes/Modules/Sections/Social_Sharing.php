<?php
/**
 * Social Sharing Extension
 *
 * This class provides the functionality for the Social Sharing extension.
 *
 * @package Rishi_Companion\Modules\Sections
 */

namespace Rishi_Companion\Modules\Sections;

use Rishi\Customizer\Dynamic_Styles;
use Rishi\Customizer\Sections\Single as Default_Single;

class Social_Sharing extends Default_Single {

    /**
	 * Setup the section.
	 */
	protected function setup() {
		$this->settings = new \Rishi_Companion\Modules\Sections\Settings\Social_Sharing_Setting();
	}

    /**
     * Check if the extension is enabled.
     *
     * @return bool
     */
    public static function is_enabled() {
        $active_extensions = get_option('rc_active_extensions', array());

        if (in_array('socialshare', $active_extensions)) {
            return true;
        }

        return false;
    }


    /**
     * Dynamic Styles for the social share
     *
     * @param Dynamic_Styles $dynamic_styles_object
     * @return array dynamic styles
     */
    public function get_dynamic_styles($dynamic_styles_object)	{
		$share_prefix = 'single_blog_post_';
        $top_offset             = get_theme_mod( $share_prefix . 'sticky_top_offset','170px' );
        $side_offset            = get_theme_mod( $share_prefix . 'sticky_side_offset','0px' );
        $icon_size              = get_theme_mod( $share_prefix . 'share_box_icon_size','15px' );
        $share_items_icon_color = get_theme_mod( $share_prefix . 'share_items_icon_color',[
            'default' => [
                'color' => 'var(--paletteColor5)',
            ],
            'hover'   => [
                'color' => 'var(--paletteColor5)',
            ],
        ]);
        $share_items_background = get_theme_mod( $share_prefix . 'share_items_background',[
            'default' => [
                'color' => 'var(--paletteColor3)',
            ],
            'hover'   => [
                'color' => 'var(--paletteColor4)',
            ],
        ]);
        $icons_spacing      = get_theme_mod( $share_prefix . 'icons_spacing',array(
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
        ) );

        $options        = array(
            'sticky_top_offset'   => array(
				'selector'     => '.rishi-share-box',
				'variableName' => 'topoffset',
				'value'        => $top_offset,
				'responsive'   => false,
				'type'         => 'slider'
			),
            'sticky_side_offset'   => array(
				'selector'     => '.rishi-share-box',
				'variableName' => 'sideOffset',
				'value'        => $side_offset,
				'responsive'   => false,
				'type'         => 'slider'
			),
            'icons_spacing' => [
				'selector'     => '.rishi-share-box',
				'variableName' => 'iconspacing',
				'value'        => $icons_spacing,
				'responsive'   => true,
				'type'         => 'spacing',
				'property'     => 'margin',
				'unit'         => 'px'
			],
            'share_box_icon_size'   => array(
				'selector'     => '.rishi-share-box',
				'variableName' => 'icon-size',
				'value'        => $icon_size,
				'responsive'   => false,
				'type'         => 'slider'
			),
            'single_blog_post_share_items_icon_color'      => array(
				'value'     => $share_items_icon_color,
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
						'variable' => 'color',
						'selector' => '.rishi-share-box',
					),
					'hover' => array(
						'variable' => 'hover-color',
						'selector' => '.rishi-share-box',
					)
				)
			),
            'single_blog_post_share_items_background'      => array(
				'value'     => $share_items_background,
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
						'variable' => 'bgcolor',
						'selector' => '.rishi-share-box',
					),
					'hover' => array(
						'variable' => 'bghovercolor',
						'selector' => '.rishi-share-box',
					)
				)
			),
        );
        foreach ( $options as $key => $option ) {
            $dynamic_styles_object->add( $key, $option );
        }
	}
}
