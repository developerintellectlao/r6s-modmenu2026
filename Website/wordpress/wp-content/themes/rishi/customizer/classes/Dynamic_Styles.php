<?php
/**
 * Dynamic Styles.
 */

namespace Rishi\Customizer;

/**
 * Dynamic Styles Class.
 */
class Dynamic_Styles {

	/**
	 * CSS for background.
	 *
	 * @access protected
	 * @var array
	 */
	protected $background_css;

	/**
	 * css for border.
	 *
	 * @access protected
	 * @var array
	 */
	protected $border_css;

	/**
	 * CSS for box shadow
	 *
	 * @access protected
	 * @var array
	 */
	protected $box_shadow_css;

	/**
	 * CSS for color.
	 *
	 * @access protected
	 * @var array
	 */
	protected $colors_css;

	/**
	 * CSS for visibility.
	 *
	 * @access protected
	 * @var array
	 */
	protected $visibility_css;

	/**
	 * Collection of CSS.
	 *
	 * @access protected
	 * @var array
	 */
	protected $css_collection = array();

	/**
	 * Attributes.
	 *
	 * @access protected
	 * @var array
	 */
	protected $attr = array();

	/**
	 * Tablet attributes.
	 *
	 * @access protected
	 * @var array
	 */
	protected $tablet_attr = array();

	/**
	 * Mobile Attributes.
	 *
	 * @access protected
	 * @var array
	 */
	protected $mobile_attr = array();

	/**
	 * CSS for editor.
	 *
	 * @access protected
	 * @var array
	 */
	protected $editor_css = array();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		\add_action( 'wp_head', array( $this, 'print_inline_css' ) );
		$this->box_shadow_css = new Helpers\Box_Shadow_CSS();
	}

	/**
	 * Print inline CSS.
	 */
	public function print_inline_css() {
		$this->collect_css();
		$final_css = $this->collect_all_styles_data();
		echo '<style id="rishi-main-styles-inline-css">';
		echo apply_filters( 'rishi-dynamic-custom-fonts', $final_css['desktop']);
		echo "</style>\n";
		if ( isset( $this->tablet_attr ) && is_array( $this->tablet_attr ) ) {
			echo '<style id="rishi-main-styles-inline-tablet-css" media="(max-width: 999.98px)">';
			echo $final_css['tablet'];
			echo "</style>\n";
		}
		if ( isset( $this->mobile_attr ) && is_array( $this->mobile_attr ) ) {
			echo '<style id="rishi-main-styles-inline-mobile-css" media="(max-width: 689.98px)">';
			echo $final_css['mobile'];
			echo "</style>\n";
		}
	}

	/**
	 * Print block editor CSS
	 * for Gutenberg editor.
	 *
	 * @return string the editor CSS string.
	 */
	public function print_block_editor_css() {
		$this->collect_css();
		$final_css = $this->collect_all_styles_data();
		return $final_css['editor'];
	}

	/**
	 * Collect all temporary structure data
	 * and transform them into actual CSS.
	 *
	 * @return string the CSS String.
	 */
	public function collect_all_styles_data() {
		$styles = $this->styles_to_css_string();
		return array(
			'desktop' => $this->minify_css_string( $styles['desktop'] ),
			'tablet'  => $this->minify_css_string( $styles['tablet'] ),
			'mobile'  => $this->minify_css_string( $styles['mobile'] ),
			'editor'  => $this->minify_css_string( $styles['editor'] ),
		);
	}

	/**
	 * Minify CSS String.
	 *
	 * @param string $minify CSS String.
	 * @return string minified CSS string.
	 */
	public function minify_css_string( $minify ) {
		// Remove comments.
		$minify = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify );

		// Remove tabs, spaces, newlines, etc.
		$minify = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $minify );

		// Remove space after colons.
		$minify = str_replace( ': ', ':', $minify );

		return $minify;
	}

	/**
	 * Merge CSS Properties
	 *
	 * @param array $properties CSS Properties.
	 * @return array $rules the merged CSS properties.
	 */
	protected function merged_css_properties( $properties ) {
		$rules = array();
		if ( is_array( $properties ) ) {
			$rules = array_merge(
				$rules,
				array_map(
					function ( $rule ) {
						return explode( ';', $rule );
					},
					$properties
				)
			);
		} elseif ( is_string( $properties ) ) {
			$rules = explode( ';', $properties );
		}

		return $rules;
	}

	/**
	 * Merge class with CSS properties.
	 */
	public function merge_class_properties() {
		$new_names = array();
		$used      = array();

		foreach ( $this->attr as $key => $values ) {
			if ( isset( $used[ $key ] ) ) {
				continue;
			}
			$new_names[]  = $key;
			$used[ $key ] = true;
		}
		$this->attr = array_intersect_key( $this->attr, array_flip( $new_names ) );
	}

	/**
	 * Checks if style is empty or not.
	 *
	 * @param string $style CSS style.
	 * @return bool True if the style is empty, false otherwise.
	 * */
	protected function empty_style( $style ) {
		$additional_symbols = array( '-', '%', 'px', 's' );
		foreach ( $additional_symbols as $symbol ) {
			if ( strpos( $style, $symbol ) !== false ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Convert styles to CSS String.
	 *
	 * @access protected
	 *
	 * @return array {
	 *  An array of CSS strings for different devices and editor.
	 *  @type string $desktop CSS styles for desktop.
	 *  @type string $tablet  CSS styles for tablet.
	 *  @type string $mobile  CSS styles for mobile.
	 *  @type string $editor  CSS styles for editor.
	 * }
	 */
	protected function styles_to_css_string() {
		$css        = '';
		$tablet_css = '';
		$mobile_css = '';
		$editor_css = '';
		$this->merge_class_properties();
		foreach ( array( $this->attr, $this->tablet_attr, $this->mobile_attr, $this->editor_css ) as $attr ) {
			foreach ( $attr as $selector => $properties ) {
				$rules          = $selector . '{';
				$values         = '';
				$is_media_query = strpos( $selector, '@media' ) !== false;

				foreach ( $properties as $style ) {
					if ( is_array( $style ) ) {
						$style = implode( ';', array_map( 'trim', $style ) );
					} else {
						$style = trim( $style );
					}
					if ( ! $this->empty_style( $style ) ) {
						$style   = rtrim( $style, ';' );
						$values .= " {$style}";

						if ( ! $is_media_query ) {
							$values .= ';';
						} else {
							$values .= '';
						}
					}
				}

				// CSS is not empty.
				if ( $values ) {
					$rules .= $values . '}';

					if ( $attr === $this->attr ) {
						$css .= $rules;
					} elseif ( $attr === $this->tablet_attr ) {
						$tablet_css .= $rules;
					} elseif ( $attr === $this->mobile_attr ) {
						$mobile_css .= $rules;
					} elseif ( $attr === $this->editor_css ) {
						$editor_css .= $rules;
					}
				}
			}
		}

		// Erase structure.
		$this->attr        = array();
		$this->tablet_attr = array();
		$this->mobile_attr = array();
		$this->editor_css  = array();

		return array(
			'desktop' => $css,
			'tablet'  => $tablet_css,
			'mobile'  => $mobile_css,
			'editor'  => $editor_css,
		);
	}

	/**
	 * Pushes CSS properties to respective device's attribute.
	 *
	 * @param string $selector - The CSS selector.
	 * @param mixed  $properties - The CSS properties.
	 * @param string $responsive - The device type.
	 */
	public function push( $selector, $properties, $responsive ) {
		if ( 'desktop' === $responsive ) {
			$this->attr[ $selector ][] = $this->merged_css_properties( $properties );
		}
		if ( 'tablet' === $responsive ) {
			$this->tablet_attr[ $selector ][] = $this->merged_css_properties( $properties );
		}
		if ( 'mobile' === $responsive ) {
			$this->mobile_attr[ $selector ][] = $this->merged_css_properties( $properties );
		}
		if ( 'editor' === $responsive ) {
			$this->editor_css[ $selector ][] = $this->merged_css_properties( $properties );
		}
	}

	/**
	 * Retrieves the responsive value for a given value.
	 *
	 * @param array $value - The value to retrieve.
	 * @param mixed $is_responsive - Boolean indicating if the value is responsive.
	 * @return mixed - Rerturns an array if the value is responsive, otherwise return the value itself.
	 */
	public function retrieve_responsive_value( $value, $is_responsive ) {
		if ( $is_responsive && is_array( $value ) && isset( $value['desktop'] ) ) {
			return array(
				'desktop' => $value['desktop'],
				'tablet'  => $value['tablet'],
				'mobile'  => $value['mobile'],
			);
		}
		if ( is_array( $value ) && isset( $value['desktop'] ) ) {
			return ( ! $is_responsive ) ? $value['desktop'] : $value;
		}

		return ( ! $is_responsive ) ? $value : array(
			'desktop' => $value,
			'tablet'  => $value,
			'mobile'  => $value,
		);
	}

	/**
	 * Adds CSS properties to the respective device's attribute.
	 *
	 * @param mixed $selector - The CSS selector.
	 * @param mixed $raw_value - The raw CSS value.
	 */
	public function add( $selector, $raw_value ) {
		$value  = \wp_parse_args(
			$raw_value,
			array(
				'responsive' => false,
				'value'      => '',
				'unit'       => '',
			)
		);
		$newval = $this->retrieve_responsive_value( $value['value'], $value['responsive'] );

		// Check if 'editor' is set and not empty in $value, else set $device to 'desktop'
		$device = ( isset( $value['editor'] ) && ! empty( $value['editor'] ) ) ? 'editor' : 'desktop';

		// If 'type' is set to 'typography' and 'prefix' is set to 'btn', process button typography
		if ( isset( $value['type'] ) && $value['type'] == 'typography' && isset( $value['prefix'] ) && $value['prefix'] == 'btn' ) {
			$this->processBtnTypography( $value, 'desktop' );
			$this->processBtnTypography( $value, 'tablet' );
			$this->processBtnTypography( $value, 'mobile' );
			$this->processBtnTypography( $value, 'editor' );
		}

		// If 'type' is set to 'typography' and 'prefix' is not set, process typography
		if ( isset( $value['type'] ) && $value['type'] == 'typography' && ! isset( $value['prefix'] ) ) {
			$this->processTypography( $value, 'desktop' );
			$this->processTypography( $value, 'tablet' );
			$this->processTypography( $value, 'mobile' );
			$this->processTypography( $value, 'editor' );
		}

		// If 'type' is set to 'color', process color
		if ( isset( $value['type'] ) && $value['type'] == 'color' ) {
			$this->processColor( $value, $device );
		}

		// If 'type' is set to 'border', process border
		if ( isset( $value['type'] ) && $value['type'] == 'border' ) {
			$this->processBorder( $value );
		}

		// If 'type' is set to 'boxshadow', process box shadow
		if ( isset( $value['type'] ) && $value['type'] == 'boxshadow' ) {
			$this->processBoxShadow( $value );
		}

		// If 'type' is set to 'divider', process divider
		if ( isset( $value['type'] ) && $value['type'] == 'divider' ) {
			$this->processDivider( $value );
		}

		// If 'variableName' is set, process variable
		if ( isset( $value['variableName'] ) ) {
			if ( isset( $value['editor'] ) && ! empty( $value['editor'] ) ) {
				$this->processVariable( $value, $newval, 'editor' );
			} elseif ( isset( $value['responsive'] ) && ! $value['responsive'] ) {
				$this->processVariable( $value, $newval, 'desktop' );
			} else {
				$this->processVariable( $value, $newval, 'desktop' );
				$this->processVariable( $value, $newval, 'tablet' );
				$this->processVariable( $value, $newval, 'mobile' );
			}
		} else {
			$value['property'] = isset( $value['property'] ) ? $value['property'] : '';
			if ( 'margin' === $value['property'] ) {
				if ( isset( $value['editor'] ) && ! empty( $value['editor'] ) ) {
					$this->processVariable( $value, $newval, 'editor' );
				} elseif ( isset( $value['responsive'] ) && ! $value['responsive'] ) {
					$this->processMargin( $value, $newval, 'desktop' );
				} else {
					$this->processMargin( $value, $newval, 'desktop' );
					$this->processMargin( $value, $newval, 'tablet' );
					$this->processMargin( $value, $newval, 'mobile' );
				}
			}
			if ( 'padding' === $value['property'] ) {
				if ( isset( $value['editor'] ) && ! empty( $value['editor'] ) ) {
					$this->processVariable( $value, $newval, 'editor' );
				} elseif ( isset( $value['responsive'] ) && ! $value['responsive'] ) {
					$this->processPadding( $value, $newval, 'desktop' );
				} else {
					$this->processPadding( $value, $newval, 'desktop' );
					$this->processPadding( $value, $newval, 'tablet' );
					$this->processPadding( $value, $newval, 'mobile' );
				}
			}
		}
	}

	/**
	 * Process color styles
	 * to CSS String.
	 *
	 * @param array  $value CSS Styles
	 * @param string $device Device
	 */
	public function processColor( $value, $device ) {
		
		// Get the current palette from your array
		if ( is_array( $value['value'] ) && isset( $value['value']['palettes'] ) && is_array( $value['value']['palettes'] ) ) {
			$currentPaletteId = $value['value']['current_palette'];
			$inputArray       = '';
			// Initialize an empty result array
			$resultArray = array();
			 // Loop through the palettes to find the matching one
			foreach ( $value['value']['palettes'] as $palette ) {
				if ( $palette['id'] === $currentPaletteId ) {
					$index = 1;
					// Extract the color values from the matched palette
					foreach ( $palette as $key => $val ) {
						if ( isset( $value['variables'] ) ) {
							if ( strpos( $key, 'color' ) === 0 ) {
								$variableName        = 'paletteColor' . $index;
								$resultArray[ $key ] = array(
									'color'    => $val['color'],
									'variable' => $value['variables'][ $key ]['variable'],
								);
								$variableName++;
							}
						}
					}
					break; // Exit the loop once the matching palette is found
				}
			}
			foreach ( $resultArray as $results => $colorvariable ) {
				$this->push(
					':root',
					'--' . $colorvariable['variable'] . ': ' . $colorvariable['color'],
					$device
				);
			}
		} else if ( isset( $value ) && empty( $value[''] ) ) { //Send default colorPallete values when not saved in the database
			if ( isset( $value['property'] ) && $value['property'] == 'colorPalette' ) {
				$resultArray = array();
				foreach ( $value['variables'] as $key => $new_value ) {
					$resultArray[ $new_value['variable'] ] = $value['default'][ $key ]['color'];
				}
				foreach ( $resultArray as $results => $colorvariable ) {
					$this->push(
						':root',
						'--' . $results . ': ' . $colorvariable,
						$device
					);
				}
			}
		}

		$args = isset( $value['default'] ) ? $this->retrieve_colors_value( $value['default'], $value['value'] ) : array();
		if ( is_array( $args ) && isset( $args['desktop'] ) ) {
			foreach ( $value['variables'] as $key => $descriptor ) {
				if ( isset( $args ) && is_array( $args ) ) {
					foreach ( $args as $a => $b ) {
						if ( isset( $a ) && isset( $b ) && isset( $b[ $key ] ) ) {
							$this->push(
								$descriptor['selector'],
								'--' . $descriptor['variable'] . ': ' . $b[ $key ],
								$a
							);
						}
					}
				}
			}
		} else {
			if ( isset( $value['variables'] ) ) {
				foreach ( $value['variables'] as $key => $descriptor ) {
					if ( isset( $args ) && is_array( $args ) && isset( $descriptor['selector'] ) && isset( $args[ $key ] ) && !is_array( $args[ $key ] )) {
						$this->push(
							$descriptor['selector'],
							'--' . $descriptor['variable'] . ': ' . $args[ $key ],
							$device
						);
					}
				}
			}
		}
	}

	/**
	 * Process border styles
	 * to CSS String.
	 *
	 * @param array $value CSS Styles.
	 */
	public function processBorder( $value ) {
		if ( isset( $value ) && isset( $value['value'] ) && is_array( $value['value'] ) ) {
			$width = $value['value']['width'];
			$this->push(
				$value['selector'],
				'--' . $value['variableName'] . ': ' . $width . $value['unit'],
				'desktop'
			);
		}
	}

	/**
	 * Process BooxShadow styles
	 * to CSS String.
	 *
	 * @param array $value CSS Styles
	 */
	public function processBoxShadow( $value ) {
		$args = isset( $value['default'] ) ? $this->retrieve_box_shadow_value( $value['default'], $value['value'] ) : array();
		if ( is_array( $args ) && isset( $args['enable'] ) && $args['enable'] ) {
			foreach ( $value['variables'] as $key => $descriptor ) {
				if ( isset( $args['inset'] ) && ! empty( $args['inset'] ) ) {
					$args['inset'] = 'inset';
				}
				if ( isset( $args['color'] ) && is_array( $args['color'] ) && isset( $args['color']['color'] ) ) {
					$args['color'] = $args['color']['color'];
				}

				if ( isset( $args['blur'] ) && isset( $args['h_offset'] ) && isset( $args['v_offset'] ) && isset( $args['spread'] ) ) {
					$this->push(
						$descriptor['selector'],
						'--' . $descriptor['variable'] . ': ' . $args['inset'] . ' ' . $args['h_offset'] . ' ' . $args['v_offset'] . ' ' . $args['blur'] . ' ' . $args['spread'] . ' ' . $args['color'],
						'desktop'
					);
				}
			}
		}
	}

	/**
	 * Process Divider value
	 * to CSS String.
	 *
	 * @param array $value CSS Styles.
	 */
	public function processDivider( $value ) {
		$args = isset( $value['default'] ) ? $this->retrieve_footer_divider_value( $value['default'], $value['value'] ) : array();
		if ( is_array( $args ) ) {
			foreach ( $value['variables'] as $key => $descriptor ) {
				if ( isset( $args['width'] ) && isset( $args['style'] ) && isset( $args['color'] ) ) {
					if (is_array ( $args['color'] ) && isset( $args['color']['color'] ) ) {
						if ( isset( $args['color']['hover'] ) ) {
							$this->push(
								$descriptor['selector'],
								'--' . $descriptor['variable'] . '_hover' . ': ' . $args['width'] . 'px ' . $args['style'] . ' ' . $args['color']['hover'],
								'desktop'
							);
						}
						$this->push(
							$descriptor['selector'],
							'--' . $descriptor['variable'] . ': ' . $args['width'] . 'px ' . $args['style'] . ' ' . $args['color']['color'],
							'desktop'
						);
					} else {
						$this->push(
							$descriptor['selector'],
							'--' . $descriptor['variable'] . ': ' . $args['width'] . 'px ' . $args['style'] . ' ' . $args['color'],
							'desktop'
						);
					}
				}
			}
		}
	}

	/**
	 * Retrieve color value.
	 *
	 * @param mixed $defaults default value.
	 * @param mixed $value value.
	 */
	public function retrieve_colors_value( $defaults, $value ) {
		$result  = array();
		$devices = array( 'desktop', 'tablet', 'mobile' );
		if ( is_array( $value ) && isset( $value['desktop'] ) ) {
			foreach ( $devices as $device ) {
				foreach ( array( 'default', 'hover' ) as $state ) {
					if ( isset( $value[ $device ][ $state ] ) ) {
						$data                        = is_array( $value ) && isset( $value[ $device ] ) && is_array( $value[ $device ][ $state ] ) ? $value[ $device ][ $state ] : $defaults[ $device ][ $state ];
						$result[ $device ][ $state ] = $data['color'];
					}
				}
			}
		} else {
			if ( is_array( $value ) ) {
				foreach ( $value as $id => $data ) {
					$result[ $id ] = isset( $data ) && isset( $data['color'] ) ? $data['color'] : $data;
				}
			} elseif ( empty( $value ) && is_array( $defaults ) && isset( $defaults['default'] ) ) {
				foreach ( $defaults as $id => $data ) {
					$result[ $id ] = isset( $data ) && isset( $data['color'] ) ? $data['color'] : $data;
				}
			} else {
				$result['default'] = isset( $defaults['color'] ) ? $defaults['color'] : '';
			}
		}
		return $result;
	}

	/**
	 * Retrieve box shadow value.
	 *
	 * @param mixed $defaults default value.
	 * @param mixed $value value.
	 * @return array
	 */
	public function retrieve_box_shadow_value( $defaults, $value ) {
		$result = array();

		if ( is_array( $value ) ) {
			foreach ( $value as $id => $data ) {
				if ( is_array( $data ) && isset( $data['value'] ) && isset( $data['unit'] ) ) {
					$result[ $id ] = $data['value'] . $data['unit'];
				} else {
					$result[ $id ] = $data;
				}
			}
		} elseif ( empty( $value ) && is_array( $defaults ) && isset( $defaults ) ) {
			foreach ( $defaults as $id => $data ) {
				$result[ $id ] = $data;
			}
		}
		return $result;
	}

	/**
	 * Retrieve footer divider value.
	 *
	 * @param mixed $defaults default value.
	 * @param mixed $value value.
	 * @return array
	 */
	public function retrieve_footer_divider_value( $defaults, $value ) {
		$result = array();

		if ( is_array( $value ) ) {
			foreach ( $value as $id => $data ) {
				$result[ $id ] = $data;
			}
		} elseif ( empty( $value ) && is_array( $defaults ) && isset( $defaults ) ) {
			foreach ( $defaults as $id => $data ) {
				$result[ $id ] = $data;
			}
		}
		return $result;
	}

	/**
	 * Processes the variable for a given element and device.
	 *
	 * @param mixed  $value - The variable settings.
	 * @param mixed  $newval - The new variable values.
	 * @param string $device - The device type.
	 */
	protected function processVariable( $value, $newval, $device ) {
		if ( is_array( $value['value'] ) && isset( $device ) && isset( $value['value'][ $device ] ) ) {
			$value['value'] = $value['value'][ $device ];
		}
		if ( is_array( $newval ) && isset( $value['variableName'] ) && isset( $value['unit'] ) && isset( $value['selector'] ) ) {
			if ( ! $value['responsive'] && isset( $newval['top'], $newval['right'], $newval['bottom'], $newval['left'] ) ) {
				$unit     = isset( $newval['unit'] ) ? $newval['unit'] : '';
				$sides    = array( 'top', 'right', 'bottom', 'left' );
				$variable = '';

				foreach ( $sides as $side ) {
					$variable .= $newval[ $side ] . ( $newval[ $side ] !== 'auto' ? $unit : '' ) . ' ';
				}

				$variable = trim( $variable );

				if ( isset( $value['important'] ) && $value['important'] ) {
					$variable .= ' !important';
				}

				$this->push(
					$value['selector'],
					'--' . $value['variableName'] . ': ' . $variable,
					$device
				);
			}
			if ( isset( $newval[ $device ] ) && ! is_array( $newval[ $device ] ) ) {
				$variable = $newval[ $device ] . $value['unit'];
				if ( isset( $value['important'] ) && $value['important'] ) {
					$variable = $variable . ' !important';
				}
				$this->push(
					$value['selector'],
					'--' . $value['variableName'] . ': ' . $variable,
					$device
				);
			}
			if ( isset( $newval[ $device ] ) && is_array( $newval[ $device ] ) && $value['responsive'] && isset( $newval[ $device ]['top'] ) && isset( $newval[ $device ]['right'] ) && isset( $newval[ $device ]['bottom'] ) && isset( $newval[ $device ]['left'] ) && isset( $value['unit'] ) ) {
				if ( isset( $newval[ $device ]['unit'] ) ) {
					$variable = $newval[ $device ]['top'] . ( $newval[ $device ]['top'] !== 'auto' ? $newval[ $device ]['unit'] : '' ) . ' ' . $newval[ $device ]['right'] . ( $newval[ $device ]['right'] !== 'auto' ? $newval[ $device ]['unit'] : '' ) . ' ' . $newval[ $device ]['bottom'] . ( $newval[ $device ]['bottom'] !== 'auto' ? $newval[ $device ]['unit'] : '' ) . ' ' . $newval[ $device ]['left'] . ( $newval[ $device ]['left'] !== 'auto' ? $newval[ $device ]['unit'] : '' );
				} else {
					$variable = $newval[ $device ]['top'] . ( $newval[ $device ]['top'] !== 'auto' ? $value['unit'] : '' ) . ' ' . $newval[ $device ]['right'] . ( $newval[ $device ]['right'] !== 'auto' ? $value['unit'] : '' ) . ' ' . $newval[ $device ]['bottom'] . ( $newval[ $device ]['bottom'] !== 'auto' ? $value['unit'] : '' ) . ' ' . $newval[ $device ]['left'] . ( $newval[ $device ]['left'] !== 'auto' ? $value['unit'] : '' );
				}
				if ( isset( $value['important'] ) && $value['important'] ) {
					$variable = $variable . ' !important';
				}
				$this->push(
					$value['selector'],
					'--' . $value['variableName'] . ': ' . $variable,
					$device
				);
			}
		}
		if ( isset( $value['type'] ) && $value['type'] == 'slider' && ! is_array( $newval ) && ! $value['responsive'] ) {
			$variable = $value['value'] . $value['unit'];
			if ( isset( $value['important'] ) && $value['important'] ) {
				$variable = $variable . ' !important';
			}
			$this->push(
				$value['selector'],
				'--' . $value['variableName'] . ': ' . $variable,
				$device
			);
		}
		if ( isset( $value['type'] ) && $value['type'] == 'alignment' && ! is_array( $newval ) && ! $value['responsive'] ) {
			$variable = $value['value'];
			if ( isset( $value['important'] ) && $value['important'] ) {
				$variable = $variable . ' !important';
			}
			$this->push(
				$value['selector'],
				'--' . $value['variableName'] . ': ' . $variable,
				$device
			);
		}
		if ( isset( $value['type'] ) && $value['type'] == 'alignment' && ! is_array( $newval ) && $value['responsive'] ) {
			$variable = $value['value'];
			$this->push(
				$value['selector'],
				'--' . $value['variableName'] . ': ' . $variable,
				$device
			);
		}
	}

	/**
	 * Processes typography settings for a given element and device.
	 *
	 * @param mixed  $value - The typography settings.
	 * @param string $device - The device type.
	 */
	protected function processTypography( $value, $device ) {
		if ( isset( $value['value']['family'] ) && ( $value['value']['family'] === 'System Default' || $value['value']['family'] === 'Default' ) ) {
			$value['value']['family'] = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'";
		} else {
			if ( isset( $value['value']['family'] ) && $value['value']['family'] !== 'Default' ) {
				$value['value']['family'] .= ', ' . get_theme_mod(
					'font_family_fallback',
					'Sans-Serif'
				);
			}
		}
		foreach ( $value as $val ) {
			if ( is_array( $val ) ) {
				$fontMappings      = array(
					'family'          => '--fontFamily',
					'text-transform'  => '--textTransform',
					'text-decoration' => '--textDecoration',
					'size'            => '--fontSize',
					'line-height'     => '--lineHeight',
					'letter-spacing'  => '--letterSpacing',
					'weight'          => '--fontWeight',
					'style'           => '--fontStyle',
				);
				$fontWeightMapping = array(
					'thin'        => 100,
					'extra_light' => 200,
					'light'       => 300,
					'regular'     => 400,
					'medium'      => 500,
					'semibold'    => 600,
					'bold'        => 700,
					'extra_bold'  => 800,
					'ultra_bold'  => 900,
				);
				foreach ( $val as $font => $result ) {
					if ( is_array( $result ) && isset( $result[ $device ] ) ) {
						$result = $result[ $device ];
					} elseif ( isset( $result['desktop'] ) ) {
						$result = $result['desktop'];
					}
					if ( isset( $fontMappings[ $font ] ) ) {
						if ( $font === 'weight' && isset( $fontWeightMapping[ $result ] ) ) {
							$result = $fontWeightMapping[ $result ];
						}
						$variableName = $fontMappings[ $font ];
						$this->push(
							$value['selector'],
							'' . $variableName . ': ' . $result,
							$device
						);
					}
				}
			}
		}
	}

	/**
	 * Processes the typography for buttons.
	 *
	 * @param mixed  $value - The typography value.
	 * @param string $device - The device type.
	 */
	protected function processBtnTypography( $value, $device ) {

		if ( isset( $value['value']['family'] ) && $value['value']['family'] === 'System Default' ) {
			$value['value']['family'] = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'";
		}

		foreach ( $value as $val ) {
			if ( is_array( $val ) ) {
				if ( isset( $value['prefix'] ) && $value['prefix'] === 'btn' ) {
					$fontMappings = array(
						'family'          => 'FontFamily',
						'text-transform'  => 'TextTransform',
						'text-decoration' => 'TextDecoration',
						'size'            => 'FontSize',
						'line-height'     => 'LineHeight',
						'letter-spacing'  => 'LetterSpacing',
						'weight'          => 'FontWeight',
						'style'           => 'FontStyle',
					);
				}

				foreach ( $val as $font => $result ) {
					if ( is_array( $result ) && isset( $result[ $device ] ) ) {
						$result = $result[ $device ];
					} elseif ( isset( $result['desktop'] ) ) {
						$result = $result['desktop'];
					}
					if ( isset( $fontMappings[ $font ] ) ) {
						$variableName = '--' . $value['prefix'] . $fontMappings[ $font ];
						$this->push(
							$value['selector'],
							'' . $variableName . ': ' . $result,
							$device
						);
					}
				}
			}
		}
	}

	/**
	 * Applies margin to a CSS selector for a specific device if margin values are provided.
	 *
	 * @param mixed  $value - The CSS selector.
	 * @param mixed  $newval - Margin values.
	 * @param string $device - Device type.
	 */
	protected function processMargin( $value, $newval, $device ) {
		if ( is_array( $newval ) && isset( $newval[ $device ] ) && '' !== $newval[ $device ] ) {
			extract( $newval[ $device ] );
			$this->push(
				$value['selector'],
				"margin: $top $right $bottom $left",
				$device
			);
		}
	}

	/**
	 * Applies padding to a CSS selector for a specific device if padding values are provided.
	 *
	 * @param mixed  $value - CSS selector.
	 * @param mixed  $newval - Padding values.
	 * @param string $device - Device type.
	 */
	protected function processPadding( $value, $newval, $device ) {
		if ( is_array( $newval ) && isset( $newval[ $device ] ) && '' !== $newval[ $device ] ) {
			extract( $newval[ $device ] );
			$this->push(
				$value['selector'],
				"padding: $top $right $bottom $left",
				$device
			);
		}
	}

	/**
	 * Retrieve either dynamic or default value based on condition set.
	 *
	 * @param mixed $dynamic dynamic value.
	 * @param mixed $defaults default value.
	 * @return mixed
	 **/
	public function retrieve_value_for_key( $dynamic, $defaults ) {
		if ( empty( $dynamic ) ) {
			return $defaults;
		}
		return $dynamic;
	}

	/**
	 * Triggers a WordPress action with a dynamic name, passing the current instance.
	 */
	protected function collect_css() {
		do_action( 'rishi_customizer_dynamic_styles_' . __FUNCTION__, $this );
	}
}
