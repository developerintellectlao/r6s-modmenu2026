<?php
/**
 * Class Cart.
 */
namespace Rishi\Customizer\Header\Elements;
use Rishi\Customizer\ControlTypes;
use Rishi\Customizer\Abstracts;

class Cart extends Abstracts\Builder_Element {

	public function get_id() {
		return 'cart';
	}

    public function get_builder_type() {
		return 'header';
	}

    public function get_label()
    {
        return __('Cart', 'rishi');
    }

    public function config()
    {
        return array(
            'name' => $this->get_label(),
            'visibilityKey' => 'header_hide_' . $this->get_id(),
        );
    }

    public static function is_enabled(){
		return rishi_is_woocommerce_activated();
	}

    /**
     * Add customizer settings for the element
     *
     * @return array get options
     */
    public function get_options(){
        $options = [
			\Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'General', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [
                    'header_hide_' . $this->get_id() => [
						'label'               => false,
						'control'             => ControlTypes::HIDDEN,
						'value'               => false,
						'disableRevertButton' => true,
						'help'                => __( 'Hide', 'rishi' ),
					],
                    'mini_cart_type' => [
                        'label'         => __( 'Mini Cart Type', 'rishi' ),
                        'control'       => ControlTypes::IMAGE_PICKER,
                        'value'         => 'type-1',
                        'responsive'    => false,
                        'attr' => [
                            'data-type'    => 'background',
                            'data-columns' => '3',
                            'data-usage'   => 'carishi_icon',
                        ],
                        'divider'       => 'bottom',
                        'choices'       => [
                            'type-1' => [
                                'src'   => self::get_header_cart_icons('type-1'),
                                'title' => __('Cart Basket', 'rishi'),
                            ],

                            'type-2' => [
                                'src'   => self::get_header_cart_icons('type-2'),
                                'title' => __('Cart Picker', 'rishi'),
                            ],

                            'type-3' => [
                                'src'   => self::get_header_cart_icons('type-3'),
                                'title' => __('Cart Bigbasket', 'rishi'),
                            ]
                        ],
                    ],
                    'cartIconSize'                        => [
						'label'      => __( 'Icon Size', 'rishi' ),
						'control'    => ControlTypes::INPUT_SLIDER,
						'value'      => '15px',
						'responsive' => false,
						'divider'    => 'bottom',
						'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
							[ 'unit' => 'px', 'min' => 0, 'max' => 50 ],
						] ),
					],
                    'has_cart_badge'                          => [
						'label'   => __( 'Icon Badge', 'rishi' ),
						'control' => ControlTypes::INPUT_SWITCH,
						'value'   => 'yes',
                        'divider' => 'bottom',

					],
                    'cart_subtotal_visibility' => [
                        'label' => __('Cart Total Visibility', 'rishi'),
                        'control' => ControlTypes::VISIBILITY,
                        'design' => 'block',
                        'divider' => 'bottom',
                        'value' => [
                            'desktop' => 'desktop',
                            'tablet'  => 'tablet',
                            'mobile'  => 'mobile',
                        ],
                        'choices' => \Rishi\Customizer\Helpers\Basic::ordered_keys([
                            'desktop' => __('Desktop', 'rishi'),
                            'tablet' => __('Tablet', 'rishi'),
                            'mobile' => __('Mobile', 'rishi'),
                        ]),
                    ],
                    'has_cart_dropdown' => [
                        'label'       => __( 'Cart Drawer', 'rishi' ),
                        'control'     => ControlTypes::INPUT_SWITCH,
                        'value'       => 'yes',
                        'wrapperAttr' => ['data-label' => 'heading-label'],

                    ],
                    \Rishi\Customizer\Helpers\Basic::uniqid() => [
                        'title'   => __( 'General', 'rishi' ),
                        'control' => ControlTypes::TAB,
                        'options' => [
                            'cartDropdownTopOffset'                        => [
                                'label'      => __( 'Dropdown Top Offset', 'rishi' ),
                                'control'    => ControlTypes::INPUT_SLIDER,
                                'value'      => '15px',
                                'responsive' => false,
                                'divider'    => 'bottom',
                                'units'      => \Rishi\Customizer\Helpers\Basic::get_units( [
                                    [ 'unit' => 'px', 'min' => 0, 'max' => 120 ],
                                ] ),
                            ],
                        ],
                    ],
                    \Rishi\Customizer\Helpers\Basic::uniqid() => [
                        'title'   => __( 'Design', 'rishi' ),
                        'control' => ControlTypes::TAB,
                        'options' => [
                            'cartFontColor' => [
                                'label' => __('Font Color', 'rishi'),
                                'control'  => ControlTypes::COLOR_PICKER,
                                'design' => 'inline',
                                'divider'    => 'bottom',
								'colorPalette'	  => true,
                                'value' => [
                                    'default' => [
                                        'color' => 'var(--paletteColor1)',
                                    ],

                                    'hover' => [
                                        'color' => 'var(--paletteColor3)',
                                    ],
                                ],
                                'pickers' => [
                                    [
                                        'title' => __('Initial', 'rishi'),
                                        'id' => 'default',
                                    ],
                                    [
                                        'title' => __('Hover', 'rishi'),
                                        'id' => 'hover',
                                        'inherit' => 'var(--linkHoverColor)'
                                    ],
                                ],
                            ],

                            'cartDropDownBackground' => [
                                'label' => __('Background Color', 'rishi'),
                                'control'  => ControlTypes::COLOR_PICKER,
                                'design' => 'inline',
								'colorPalette'	  => true,
                                'value' => [
                                    'default' => [
                                        'color' => 'var(--paletteColor5)',
                                    ],
                                ],
                                'pickers' => [
                                    [
                                        'title' => __('Initial', 'rishi'),
                                        'id' => 'default',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ],
            \Rishi\Customizer\Helpers\Basic::uniqid() => [
				'title'   => __( 'Design', 'rishi' ),
				'control'    => ControlTypes::TAB,
				'options' => [
                    'cartHeaderTextColor' => [
                        'label'      => __('Text Color', 'rishi'),
                        'control'    => ControlTypes::COLOR_PICKER,
                        'design'     => 'inline',
                        'responsive' => false,
						'divider'	 => 'bottom',
						'colorPalette'	  => true,
                        'value' => [
                            'default' => [
                                'color' =>'var(--paletteColor1)',
                            ],

                            'hover' => [
                                'color' => 'var(--paletteColor3)',
                            ],
                        ],

                        'pickers' => [
                            [
                                'title' => __('Initial', 'rishi'),
                                'id' => 'default',
                            ],

                            [
                                'title' => __('Hover', 'rishi'),
                                'id' => 'hover',
                                'inherit' => 'var(--linkHoverColor)'
                            ],
                        ],
                    ],
                    'cartHeaderIconColor' => [
                        'label' => __('Icon Color', 'rishi'),
                        'control'  => ControlTypes::COLOR_PICKER,
                        'design' => 'inline',
                        'responsive' => false,
                        'divider'	 => 'bottom',
						'colorPalette'	  => true,
                        'value' => [
                            'default' => [
                                'color' =>'var(--paletteColor1)',
                            ],
                            'hover' => [
                                'color' => 'var(--paletteColor3)',
                            ],
                        ],

                        'pickers' => [
                            [
                                'title' => __('Initial', 'rishi'),
                                'id' => 'default',
                            ],
                            [
                                'title' => __('Hover', 'rishi'),
                                'id' => 'hover',
                                'inherit' => 'var(--linkHoverColor)'
                            ],
                        ],
                    ],
                    'cartBadgeColor' => [
                        'label' => __('Badge Color', 'rishi'),
                        'control'  => ControlTypes::COLOR_PICKER,
                        'design' => 'inline',
                        'responsive' => false,
                        'divider'	 => 'bottom',
						'colorPalette'	  => true,
                        'value' => [
                            'background' => [
                                'color' => 'var(--paletteColor3)',
                            ],

                            'text' => [
                                'color' => 'var(--paletteColor5)',
                            ],
                        ],
                        'pickers' => [
                            [
                                'title' => __('Background', 'rishi'),
                                'id' => 'background',
                            ],

                            [
                                'title' => __('Text', 'rishi'),
                                'id' => 'text',
                            ],
                        ],
                    ],
                    'headerCartMargin' => [
                        'label'   => __('Margin', 'rishi'),
                        'control' => ControlTypes::INPUT_SPACING,
                        'divider'	 => 'bottom',
                        'value'      => \Rishi\Customizer\Helpers\Basic::spacing_value( [
							'linked' => true,
							'top'    => '0',
							'left'   => '0',
							'right'  => '0',
							'bottom' => '0',
							'unit'   => 'px'
						] ),
                        'responsive' => false
                    ],
                ],
            ]
        ];
        return $options;
    }

    /**
     * Write logic for dynamic css change for the elements
     *
     * @return array dynamic styles
     */
	public function dynamic_styles() {

        $cartIconSize          = $this->get_mod_value( 'cartIconSize', '15px' );
        $cartDropdownTopOffset = $this->get_mod_value( 'cartDropdownTopOffset', '15px' );
        $headerCartMargin      = $this->get_mod_value( 'headerCartMargin', \Rishi\Customizer\Helpers\Basic::spacing_value( [
            'linked' => true,
            'top'    => '0',
            'left'   => '0',
            'right'  => '0',
            'bottom' => '0',
            'unit'   => 'px'
        ]) );
        $cartHeaderTextColor   = $this->get_mod_value( 'cartHeaderTextColor', [
            'default' => [
                'color' =>'var(--paletteColor1)',
            ],
            'hover' => [
                'color' => 'var(--paletteColor3)',
            ],
        ] );
        $cartHeaderIconColor = $this->get_mod_value( 'cartHeaderIconColor', [
            'default' => [
                'color' =>'var(--paletteColor1)',
            ],

            'hover' => [
                'color' => 'var(--paletteColor3)',
            ],
        ] );
        $cartBadgeColor = $this->get_mod_value( 'cartBadgeColor', [
            'background' => [
                'color' => 'var(--paletteColor3)',
            ],
            'text' => [
                'color' => 'var(--paletteColor5)',
            ],
        ] );

        $cartFontColor = $this->get_mod_value( 'cartFontColor', [
            'default' => [
                'color' => 'var(--paletteColor1)',
            ],
            'hover' => [
                'color' => 'var(--paletteColor3)',
            ],
        ]);
        $cartDropDownBackground = $this->get_mod_value( 'cartDropDownBackground', [
            'default' => [
                'color' => 'var(--paletteColor5)',
            ],
        ]);

        $cartDropdownTopOffset = $this->get_mod_value( 'cartDropdownTopOffset', '15px' );
        $headerCartMargin      = $this->get_mod_value( 'headerCartMargin' );

        return array(
            'cartIconSize'   => array(
                'selector'     => '#rishi-cart',
                'variableName' => 'icon-size',
                'value'        => $cartIconSize,
                'responsive'   => false,
                'type'         => 'slider'
            ),
            'cartHeaderTextColor'      => array(
				'value'     => $cartHeaderTextColor,
				'type'      => 'color',
				'default'   => [
                    'default' => [
                        'color' =>'var(--paletteColor1)',
                    ],

                    'hover' => [
                        'color' => 'var(--paletteColor3)',
                    ],
                ],
				'variables' => array(
					'default' => array(
						'variable' => 'textInitialColor',
						'selector' => '#rishi-cart',
					),
					'hover' => array(
						'variable' => 'textHoverColor',
						'selector' => '#rishi-cart',
					)
				)
			),
            'cartHeaderIconColor'      => array(
				'value'     => $cartHeaderIconColor,
				'type'      => 'color',
				'default'   => [
                    'default' => [
                        'color' =>'var(--paletteColor1)',
                    ],

                    'hover' => [
                        'color' => 'var(--paletteColor3)',
                    ],
                ],
				'variables' => array(
					'default' => array(
						'variable' => 'icon-color',
						'selector' => '#rishi-cart',
					),
					'hover' => array(
						'variable' => 'icon-hover-color',
						'selector' => '#rishi-cart',
					)
				)
			),
            'cartBadgeColor'      => array(
				'value'     => $cartBadgeColor,
				'type'      => 'color',
				'default'   => [
                    'background' => [
                        'color' => 'var(--paletteColor3)',
                    ],
                    'text' => [
                        'color' => 'var(--paletteColor5)',
                    ],
                ],
				'variables' => array(
					'background' => array(
						'variable' => 'cartBadgeBackground',
						'selector' => '#rishi-cart',
					),
					'text' => array(
						'variable' => 'cartBadgeText',
						'selector' => '#rishi-cart',
					)
				)
			),
            'cartFontColor'      => array(
				'value'     => $cartFontColor,
				'type'      => 'color',
				'default'   => [
                    'default' => [
                        'color' => 'var(--paletteColor1)',
                    ],
                    'hover' => [
                        'color' => 'var(--paletteColor3)',
                    ],
                ],
				'variables' => array(
					'default' => array(
						'variable' => 'linkInitialColor',
						'selector' => '#rishi-cart .rishi-cart-content',
					),
					'hover' => array(
						'variable' => 'linkHoverColor',
						'selector' => '#rishi-cart .rishi-cart-content',
					)
				)
			),

            'cartDropDownBackground'      => array(
				'value'     => $cartDropDownBackground,
				'type'      => 'color',
				'default'   => [
                    'default' => [
                        'color' => 'var(--paletteColor5)',
                    ],
                ],
				'variables' => array(
					'default' => array(
						'variable' => 'backgroundColor',
						'selector' => '#rishi-cart .rishi-cart-content',
					)
				)
			),
            'cartDropdownTopOffset'     => array(
				'selector'     => '#rishi-cart .rishi-cart-content',
				'variableName' => 'dropdownTopOffset',
				'value'        => $cartDropdownTopOffset,
				'responsive'   => false,
				'type' 		   => 'slider'
			),
            'headerCartMargin' => array(
                'selector'     => '#rishi-cart',
                'variableName' => 'margin',
                'value'        => $headerCartMargin,
                'type'         => 'spacing',
                'unit'         => 'px',
                'property'     => 'margin'
			),
        );

	}

    /**
     * Header cart markup
     * @param string $desktop
     * @return void
     */
    public function render( $device = 'desktop'){
        if( !rishi_is_woocommerce_activated() ) return;
        $link = wc_get_cart_url();
        $subtotal = WC()->cart->get_cart_subtotal();
        $mini_cart_type     = $this->get_mod_value( 'mini_cart_type','type-1' );
        $has_cart_badge     = $this->get_mod_value( 'has_cart_badge','yes' );
        $has_cart_dropdown  = $this->get_mod_value( 'has_cart_dropdown','yes' );
        
        $class = "rishi-header-cart";
        if( $has_cart_badge === "yes" ){
            $class .= " counter-badge-on";
        }
        $visibility = $this->get_mod_value(
            'cart_subtotal_visibility',
            [
                'desktop' => 'desktop',
                'tablet'  => 'tablet',
                'mobile'  => 'mobile',
            ]
        );
        $class .= $this->get_visible_device_class( $visibility );

        $cartinput = '';
        $current_cart_count     = WC()->cart->get_cart_contents_count();

        if ( intval( $current_cart_count ) > 0 ) {
            $cartinput = 'style="--counter: \'' . esc_attr( $current_cart_count ) . '\'"';
        }
        do_action( 'woocommerce_before_mini_cart' );
        ?>
        <div class="<?php echo esc_attr( $class ); ?>" id="rishi-cart" <?php echo $cartinput; ?>>
            <a class="rishi-cart-item" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr__( 'Cart','rishi' ); ?>">
                <span class="rishi-label">
                    <?php echo $subtotal; ?>
                </span>
                <span class="rishi-icon-container">
                    <?php echo $this->get_header_cart_icons( $mini_cart_type ); ?>
                </span>
            </a>
            <?php if( $has_cart_dropdown === 'yes' ){ ?>
                <div class="rishi-cart-content">
                    <?php if ( ! WC()->cart->is_empty() ) : ?>
                        <ul class="woocommerce-mini-cart cart_list product_list_widget">
                            <?php do_action( 'woocommerce_before_mini_cart_contents' ); ?>
                            <?php
                                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                    $_product   = $cart_item['data'];
                                    $product_id = $cart_item['product_id'];
                                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
                                        $product_name      = $_product->get_name();
                                        $thumbnail         = $_product->get_image();
                                        $product_price     = WC()->cart->get_product_price( $_product );
                                        $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : ''; ?>
                                            <li class="woocommerce-mini-cart-item mini_cart_item">
                                                <a
                                                    class="remove remove_from_cart_button"
                                                    aria-label="<?php echo esc_attr( sprintf( __( 'Remove %s from cart', 'rishi' ), wp_strip_all_tags( $product_name ) ) ); ?>"
                                                    href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
                                                    data-product_id="<?php echo esc_attr( $product_id ); ?>"
                                                    data-cart_item_key="<?php echo esc_attr( $cart_item_key ); ?>"
                                                    data-product_sku="<?php echo esc_attr( $_product->get_sku() ); ?>"
                                                    >
                                                    &times;
                                                </a>
                                                <div class="rishi-mini-cart-thumb-wrapper">
                                                    <?php
                                                    if( empty( $product_permalink ) ){
                                                        echo $thumbnail;
                                                    }else{ ?>
                                                    <a href="<?php echo esc_url( $product_permalink ); ?>">
                                                        <?php echo $thumbnail; ?>
                                                    </a>
                                                    <?php } ?>
                                                </div>
                                                <div class="rishi-product-data">
                                                    <a class="product-title" href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
                                                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                                                    <span class="quantity">
                                                        <?php echo sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ); ?>
                                                    </span>
                                                </div>
                                            </li>
                                        <?php
                                    }
                                }
                                do_action( 'woocommerce_mini_cart_contents' );
                            ?>
                        </ul>

                        <p class="woocommerce-mini-cart__total total">
                            <?php do_action( 'woocommerce_widget_shopping_cart_total' ); ?>
                        </p>

                        <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

                        <p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

                        <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
                    <?php else : ?>
                        <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'rishi' ); ?></p>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>
        <?php
        do_action( 'woocommerce_after_mini_cart' );
    }

    /**
     * Return SVG for cart icon
     * Note to code reviewers: It contains inline SVG, which is absolutely safe and the returned value doesn't need to be escaped.
     * 
     * @param string $carticon
     * @return string
     */
    public function get_header_cart_icons( $carticon ){

        switch ( $carticon ) {
            case 'type-1':
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.41665 6.16665C6.41665 3.17513 8.8418 0.75 11.8333 0.75C14.8249 0.75 17.25 3.17513 17.25 6.16665V7.75H20C20.39 7.75 20.715 8.049 20.7474 8.4377L21.9141 22.4377C21.9315 22.6468 21.8606 22.8535 21.7186 23.0079C21.5765 23.1622 21.3764 23.25 21.1666 23.25H2.5C2.29026 23.25 2.0901 23.1622 1.94809 23.0079C1.80607 22.8535 1.73517 22.6468 1.75259 22.4377L2.91926 8.4377C2.95165 8.049 3.2766 7.75 3.66667 7.75H6.41665V6.16665ZM15.75 7.75H7.91665V6.16665C7.91665 4.00355 9.6702 2.25 11.8333 2.25C13.9964 2.25 15.75 4.00355 15.75 6.16665V7.75Z" fill="currentColor"></path></svg>';
            break;

            case 'type-2':
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18 18C16.9038 18 16 18.8846 16 20C16 21.0962 16.8846 22 18 22C19.0962 22 20 21.1154 20 20C19.9808 18.9038 19.0962 18 18 18Z" fill="currentColor"></path><path d="M21.2662 5.36093C21.2212 5.36093 21.1539 5.33775 21.0865 5.33775H6.94004L6.71549 3.78477C6.58076 2.7649 5.72748 2 4.71702 2H3C2.5 2 2 2.5 2 3C2 3.5 2.5 4 3 4C3 4 4.60475 4 4.71702 4C4.8293 4 4.91911 4.09272 4.94157 4.20861C4.96402 4.3245 6.33376 13.8444 6.33376 13.8444C6.5134 15.0728 7.54632 16 8.75887 16H18.1C19.2677 16 20.2781 15.1424 20.5252 13.9603L21.9847 6.42715C22.0745 5.9404 21.7602 5.45364 21.2662 5.36093Z" fill="currentColor"></path><path d="M8.96972 18C7.82803 18.058 6.96192 18.9855 7.00129 20.087C7.04066 21.1498 7.92645 22 9.00909 22H9.04846C10.1705 21.942 11.0563 21.0145 10.9972 19.913C10.9578 18.8502 10.0524 18 8.96972 18Z" fill="currentColor"></path></svg>';
            break;

            case 'type-3':
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.46398 9.50757C1.16228 9.50757 0.9409 9.79112 1.01408 10.0838L3.94062 21.79C4.04385 22.2029 4.41483 22.4926 4.84044 22.4926H19.1595C19.5851 22.4926 19.9561 22.2029 20.0593 21.79L22.9859 10.0838C23.0591 9.79112 22.8377 9.50757 22.536 9.50757H1.46398ZM12 13.6813C10.7194 13.6813 9.68125 14.7195 9.68125 16.0001C9.68125 17.2807 10.7194 18.3188 12 18.3188C13.2806 18.3188 14.3187 17.2807 14.3187 16.0001C14.3187 14.7195 13.2806 13.6813 12 13.6813Z" fill="currentColor"></path><path d="M18.5 10.5L13.5887 4.07758C12.7882 3.03078 11.2118 3.03078 10.4113 4.07758L5.5 10.5" stroke="currentColor" stroke-width="3" strokelinecap="round" strokelinejoin="round"></path></svg>';
            break;

            default:
                return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.41665 6.16665C6.41665 3.17513 8.8418 0.75 11.8333 0.75C14.8249 0.75 17.25 3.17513 17.25 6.16665V7.75H20C20.39 7.75 20.715 8.049 20.7474 8.4377L21.9141 22.4377C21.9315 22.6468 21.8606 22.8535 21.7186 23.0079C21.5765 23.1622 21.3764 23.25 21.1666 23.25H2.5C2.29026 23.25 2.0901 23.1622 1.94809 23.0079C1.80607 22.8535 1.73517 22.6468 1.75259 22.4377L2.91926 8.4377C2.95165 8.049 3.2766 7.75 3.66667 7.75H6.41665V6.16665ZM15.75 7.75H7.91665V6.16665C7.91665 4.00355 9.6702 2.25 11.8333 2.25C13.9964 2.25 15.75 4.00355 15.75 6.16665V7.75Z" fill="currentColor"></path></svg>';
            break;
        }
    }
}
