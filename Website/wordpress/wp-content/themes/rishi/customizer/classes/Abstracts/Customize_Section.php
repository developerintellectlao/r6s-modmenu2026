<?php
/**
 * Abstarct class for Customizer Sections.
 *
 * @package Rishi
 * @subpackage Customizer
 */
namespace Rishi\Customizer\Abstracts;

use Rishi\Customizer\Section_Group_Title;

/**
 * Class Abstract_Customize_Section_Options.
 */
abstract class Customize_Section {
	protected $priority = 1;
	protected $id;
	protected $panel;
	protected $container = true;
	protected $wp_customize;

	const GROUP_TITLE = 'rishi-group-title';

	const OPTIONS = 'rishi-customizer-section';

	const PANEL = 'rishi-panel';

	const POSTMESSAGE = 'postMessage';

	const SECTION_LAYOUT = 'layout';

	const SECTION_CONTAINER = 'container';
	public $items           = array();

	public $settings;
	public $control;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->setup();
		\add_action( 'rishi_customizer_dynamic_styles_collect_css', array( $this, 'get_dynamic_styles' ) );
	}

	/**
	 * Get the title of the section.
	 *
	 * @return string The title of the section.
	 */
	public function get_title() {
		return __( 'Untitled Section', 'rishi' );
	}

	/**
	 * Get the type of the section.
	 *
	 * @return string The type of the section.
	 */
	public function get_type() {
		return self::GROUP_TITLE;
	}

	/**
	 * Set wp_customize.
	 *
	 * @param object $wp_customize WP_Customize_Manager instance.
	 * @return void
	 */
	public function set_wp_customize( $wp_customize ) {
		$this->wp_customize = $wp_customize;
	}

	/**
	 * Get the setting of the section.
	 *
	 * @return array The setting of the section.
	 */
	public function get_setting() {
		return array();
	}

	/**
	 * Setup the section.
	 */
	protected function setup() {
		$reflection    = new \ReflectionClass( $this );
		$className     = $reflection->getShortName();
		$setting_class = '\Rishi\Customizer\Settings\\' . $className . '_Setting';
		if ( class_exists( $setting_class ) ) {
			$this->settings = new $setting_class();
		}
	}

	/**
	 * Register the section.
	 */
	protected function register_section() {

		if ( self::GROUP_TITLE === $this->get_type() ) {
			$this->register_group_title();
			return;
		}

		$this->wp_customize->add_panel(
			'main_blog_settings',
			array(
				'title'       => __( 'Blog', 'rishi' ),
				'capability'  => 'edit_theme_options',
				'priority'    => 1
			)
		);

		$this->wp_customize->add_panel(
			'main_global_settings',
			array(
				'title'       => __( 'Global', 'rishi' ),
				'capability'  => 'edit_theme_options',
				'priority'    => 0
			)
		);

		$this->wp_customize->add_panel(
			'main_woo_settings',
			array(
				'title'       => __( 'WooCommerce', 'rishi' ),
				'capability'  => 'edit_theme_options',
				'priority'    => 3
			)
		);

		$this->wp_customize->add_section(
			$this->get_id(),
			array(
				'id'       => $this->get_id(),
				'title'    => $this->get_title(),
				'priority' => $this->priority,
				'panel'    => $this->panel,
			)
		);
	}

	/**
	 * Register the group title.
	 */
	protected function register_group_title() {
		$this->wp_customize->add_section(
			new Section_Group_Title(
				$this->wp_customize,
				$this->get_id(),
				array(
					'title'    => $this->get_title(),
					'priority' => $this->priority,
				)
			)
		);
	}

	/**
	 * Get default settings.
	 */
	protected function get_default_settings_control() {
		$setting_id = $this->get_id() . '_setting';
		$control    = new \WP_Customize_Control(
			$this->wp_customize,
			$setting_id . '_control',
			array(
				'label'              => $this->get_title(),
				'type'               => $this->get_type(),
				'customizer_section' => 'container',
				'settings'           => $setting_id,
				'section'            => $this->get_id(),
				'inner-options'      => $this->get_customize_settings(),
			)
		);

		$control->json['option'] = array(
			'type'              => $this->get_type(),
			'setting'           => $this->get_setting(),
			'customize_section' => 'container',
			'inner-options'     => $this->get_customize_settings(),
			'sanitize_callback' => function ( $input, $setting ) {
				return $input;
			},
		);

		return $control;
	}

	/**
	 * Register the section. settings, and controls.
	 */
	public function register() {
		$this->register_section();

		$settings = $this->get_customize_settings();
		$this->add_settings( $settings );

		$this->add_controls();
	}

	/**
	 * Get the customize settings.
	 *
	 * @return array The customize settings.
	 */
	protected function get_customize_settings() {
		return array();
	}

	/**
	 * Add controls to the section.
	 */
	protected function add_controls() {
	}

	/**
	 * Default sanitize callback.
	 *
	 * @param mixed $input The value to sanitize.
	 * @param mixed $settings The settings for the value.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_callback_default( $input, $settings ) {
		return $input;
	}

	/**
	 * Identify the type of control used and sanitize the data before sending to database.
	 *
	 * @param string $control
	 * @return mixed
	 */
	public function settings_sanitization( $control){

		if(!$control){
			return array( __CLASS__, 'sanitize_callback_default' );
		}

		$sanitize_function = [
			'ColorPickerControl'    => array( __CLASS__, 'sanitize_theme_color' ),
			'NumberControl'         => array( __CLASS__, 'sanitize_number_absint' ),
			'SpacingControl'        => array( __CLASS__, 'sanitize_margin_values' ),
			'RadioControl'          => array( __CLASS__, 'sanitize_select' ),
			'IconRadio'             => array( __CLASS__, 'sanitize_select' ),
			'SeparatorControl'      => array( __CLASS__, 'sanitize_select' ),
			'SelectControl'         => array( __CLASS__, 'sanitize_select' ),
			'CheckboxControl'       => array( __CLASS__, 'sanitize_checkbox' ),
			'TextareaControl'       => 'wp_kses_post',
			'TextControl'           => 'sanitize_text_field',
			'SwitchControl'         => array( __CLASS__, 'sanitize_switch' ),
			'ImagePickerControl'    => 'sanitize_text_field',
			'ImageUploaderControl'  => array( __CLASS__, 'sanitize_image' ),
			'SliderControl'         => array( __CLASS__, 'sanitize_slider_input_callback' ),
			'BoxShadowControl'      => array( __CLASS__, 'sanitize_box_shadow' ),
			'BorderControl'         => array( __CLASS__, 'sanitize_border_values' ),
			'VisibilityControl'     => array( __CLASS__, 'sanitize_visibility_settings' ),
			'DateTimePickerControl' => array( __CLASS__, 'sanitize_wp_datetime' ),
			'WPEditor'              => 'wp_kses_post'
		];

		if(!array_key_exists( $control, $sanitize_function)){
			return array( __CLASS__, 'sanitize_callback_default' );
		}

		return $sanitize_function[$control];
	}

	/**
	 * Add settings to the section.
	 *
	 * @param array $settings The settings to add to the section.
	 */
	protected function add_settings( $settings = array() ) {
		foreach ( $settings as $id => $setting ) {

			if ( isset( $setting['control'] ) || isset( $setting['type'] ) ) {
				$args = array(
					'default'           => '',
					'sanitize_callback' => array( __CLASS__, 'sanitize_callback_default' ),
					'postMessage'       => self::POSTMESSAGE,
				);

				if ( isset( $setting['setting']['transport'] ) ) {
					$args['transport'] = $setting['setting']['transport'];
				}

				if ( isset( $setting['value'] ) ) {
					$args['default'] = $setting['value'];
				}

				if ( isset( $setting['control'] ) ) {
					$args['sanitize_callback'] = $this->settings_sanitization($setting['control']);
				}

				$this->wp_customize->add_setting( $id, $args );

				if ( isset( $setting['innerControls'] ) ) {
					$this->add_settings( $setting['innerControls'] );
				}

				if ( isset( $setting['options'] ) ) {
					$this->add_settings( $setting['options'] );
				}
			} else {
				$this->add_settings( $setting );
			}
		}
	}

	/**
	 * Get the manager of the section.
	 *
	 * @return object The manager of the section.
	 */
	public function get_manager() {
		return $this->wp_customize;
	}

	/**
	 * Check if the section is enabled.
	 *
	 * @return bool True if the section is enabled, false otherwise.
	 */
	public static function is_enabled() {
		return true;
	}

	/**
	 * SwitchControl sanitize callback.
	 *
	 * @param mixed $checked The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_switch( $checked ){
		// Boolean check.
		return ( ( isset( $checked ) && 'yes' == $checked ) ? 'yes' : 'no' );
	}

	/**
	 * CheckboxControl sanitize callback.
	 *
	 * @param mixed $checked The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_checkbox( $checked ){
		// Boolean check.
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}

	/**
	 * SelectControl sanitize callback.
	 *
	 * @param mixed $value The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_select( $value ){
		if ( is_array( $value ) ) {
			foreach ( $value as $key => $subvalue ) {
				$value[ $key ] = sanitize_text_field( $subvalue );
			}
			return $value;
		}
		return sanitize_text_field( $value );
	}

	/**
	 * NumberControl sanitize callback.
	 *
	 * @param mixed $number The value to sanitize.
	 * @param mixed $setting The settings for the value.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_number_absint( $number, $setting ) {
		// Ensure $number is an absolute integer (whole number, zero or greater).
		$number = absint( $number );

		// If the input is an absolute integer, return it; otherwise, return the default
		return ( $number ? $number : $setting->default );
	}

	/**
	 * Numbers sanitize callback.
	 *
	 * @param mixed $input The value to sanitize.
	 * @param mixed $setting The settings for the value.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_empty_floatval( $input, $setting ) {
		if ( '' == $input ) {
			return '';
		}

		$number = floatval( $input );
		// If the input is an absolute integer, return it; otherwise, return the default
		return ( $number ? $number : $setting->default );
	}

	/**
	 * LayersControl sanitize callback.
	 *
	 * @param mixed $value The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_sortable( $value = array() ) {
		if ( is_string( $value ) || is_numeric( $value ) ) {
			return array(
				sanitize_text_field( $value ),
			);
		}
		$sanitized_value = array();
		foreach ( $value as $sub_value ) {
			$sanitized_value[] = sanitize_text_field( $sub_value );
		}
		return $sanitized_value;
	}

	/**
	 * Image sanitize callback.
	 *
	 * @param mixed $input The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_image( $input ){

		$sanitized_data = array();

		// Check if the input is an array
		if (is_array($input) && isset($input['_value'])) {
			$image_data = $input['_value'];

			// Check if the required keys are present in the image data
			if (isset($image_data['attachment_id']) && isset($image_data['url'])) {
				// Sanitize the attachment_id (ensure it is a positive integer)
				$sanitized_data['_value']['attachment_id'] = absint($image_data['attachment_id']);

				// Sanitize the URL (ensure it is a valid URL)
				$sanitized_data['_value']['url'] = esc_url_raw($image_data['url']);
			}
		}

		return $sanitized_data;

	}

	/**
	 * Margin sanitize callback.
	 *
	 * @param mixed $input The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_margin_values( $input ) {
		$sanitized_data = array();

		$allowed_units = array('px', 'em', 'rem','pt','%','vw','vh');

		if( array_key_exists('desktop', $input) ){
			foreach ($input as $device => $values) {
				// Sanitize device name
				$device = sanitize_key($device);

				// Validate and sanitize each margin value
				foreach (array('top', 'bottom', 'left', 'right') as $property) {
					$sanitized_data[$device][$property] = isset($values[$property]) ? absint($values[$property]) : 0;
				}

				// Validate and sanitize unit
				$unit = isset($values['unit']) ? sanitize_text_field($values['unit']) : 'px';
				$sanitized_data[$device]['unit'] = in_array($unit, $allowed_units) ? $unit : 'px';

				// Validate and sanitize linked
				$sanitized_data[$device]['linked'] = isset($values['linked']) && $values['linked'] ? true : false;
			}
		} else {
			// Validate and sanitize each margin value
			foreach (array('top', 'bottom', 'left', 'right') as $property) {
				$sanitized_data[$property] = isset($input[$property]) ? absint($input[$property]) : 0;
			}

			// Validate and sanitize unit
			$unit = isset($input['unit']) ? sanitize_text_field($input['unit']) : 'px';
			$sanitized_data['unit'] = in_array($unit, $allowed_units) ? $unit : 'px';

			// Validate and sanitize linked
			$sanitized_data['linked'] = isset($input['linked']) && $input['linked'] ? true : false;
		}


		return $sanitized_data;
	}

	/**
	 * Box Shadow sanitize callback.
	 *
	 * @param mixed $value The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_box_shadow( $value ) {
		// Define default values or fallbacks
		$defaults = array(
			'enable' => false,
			'inset' => false,
			'h_offset' => '0px',
			'v_offset' => '0px',
			'blur' => '0px',
			'spread' => '0px',
			'color' => 'rgba(0, 0, 0, 0.1)', // Default color if not provided
		);

		// Merge the input values with defaults
		$sanitized_value = wp_parse_args($value, $defaults);

		// Sanitize individual values
		$sanitized_value['enable'] = self::sanitize_checkbox( $sanitized_value['enable'] );
		$sanitized_value['inset'] = self::sanitize_checkbox( $sanitized_value['inset'] );
		$sanitized_value['h_offset'] = sanitize_text_field($sanitized_value['h_offset']);
		$sanitized_value['v_offset'] = sanitize_text_field($sanitized_value['v_offset']);
		$sanitized_value['blur'] = sanitize_text_field($sanitized_value['blur']);
		$sanitized_value['spread'] = sanitize_text_field($sanitized_value['spread']);
		$sanitized_value['color'] = self::color_sanitization($sanitized_value['color']);

		return $sanitized_value;
	}

	/**
	 * Border sanitize callback.
	 *
	 * @param mixed $value The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_border_values($input) {
		$sanitized_data = array();

		// Sanitize and validate border width (numeric value)
		$sanitized_data['width'] = isset($input['width']) ? absint($input['width']) : 0;

		// Sanitize and validate border style (string value)
		$sanitized_data['style'] = isset($input['style']) ? sanitize_text_field($input['style']) : '';

		// Sanitize and validate border color (array with 'color' and 'hover' keys)
		if (isset($input['color']) && is_array($input['color'])) {
			//Sanitize and validate normal state color
			$sanitized_data['color']['color'] = isset($input['color']['color']) ? self::color_sanitization($input['color']['color']) : '';

			//Sanitize and validate hover state color
			$sanitized_data['color']['hover'] = isset($input['color']['hover']) ? self::color_sanitization($input['color']['hover']) : '';
		}

		return $sanitized_data;
	}

	/**
	 * Visibility sanitize callback.
	 *
	 * @param mixed $input The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_visibility_settings( $input ) {
		$valid_devices = array('desktop', 'tablet', 'mobile');
		$sanitized_data = array();

		foreach ($input as $device => $value) {
			// Sanitize device name
			$sanitized_device = sanitize_key($device);

			// Check if the device is valid
			if (in_array($sanitized_device, $valid_devices)) {
				$sanitized_data[$sanitized_device] = $value;
			}
		}

		return $sanitized_data;
	}

	/**
	 * DateTime sanitize callback.
	 *
	 * @param mixed $datetime The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_wp_datetime( $datetime ) {
		// Check if $datetime is a DateTime object
		if (!($datetime instanceof DateTime)) {
			return false; // Return false if it's not a DateTime object
		}

		// Format the DateTime object to a standardized string format
		$formatted_datetime = $datetime->format('Y-m-d H:i:s');

		// Get the timezone offset in the format ±HH:MM
		$timezone_offset = $datetime->format('P');

		// Create a new DateTime object with the UTC timezone
		$utc_datetime = new DateTime($formatted_datetime, new DateTimeZone('UTC'));

		// Set the timezone offset to the original DateTime object
		$utc_datetime->setTimezone(new DateTimeZone($timezone_offset));

		// Return the sanitized DateTime object
		return $utc_datetime;
	}

	/**
	 * Slider sanitize callback.
	 *
	 * @param mixed $input The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_slider_input_callback( $input ) {
		$sanitized_data = array();

		// Define allowed units for the slider values
		$allowed_units = array('px', 'em', 'rem','pt','%','vw','vh');

		if( is_array($input) ){
			foreach ($input as $device => $value) {
				// Sanitize device name
				$device = sanitize_key($device);

				if (is_string($value)) {
					// Extract numeric value and unit from the input
					preg_match('/^([0-9.]+)([a-zA-Z%]+)$/', $value, $matches);
					$numeric_value = isset($matches[1]) ? $matches[1] : '';
					$unit = isset($matches[2]) ? $matches[2] : '';

					// Validate numeric value and unit
					if (is_numeric($numeric_value) && in_array($unit, $allowed_units)) {
						// Sanitize numeric value based on your requirements (e.g., rounding)
						$sanitized_numeric_value = round(floatval($numeric_value), 2);

						// Combine sanitized value and unit
						$sanitized_data[$device] = $sanitized_numeric_value . $unit;
					}
				}
			}
		} else {
			// Extract numeric value and unit from the input
			preg_match('/^([0-9.]+)([a-zA-Z%]+)$/', $input, $matches);
			$numeric_value = isset($matches[1]) ? $matches[1] : '';
			$unit = isset($matches[2]) ? $matches[2] : '';

			// Validate numeric value and unit
			if (is_numeric($numeric_value) && in_array($unit, $allowed_units)) {
				// Sanitize numeric value based on your requirements (e.g., rounding)
				$sanitized_numeric_value = round(floatval($numeric_value), 2);

				// Combine sanitized value and unit
				$sanitized_data = $sanitized_numeric_value . $unit;
			}
		}
		return $sanitized_data;
	}

	/**
     * Sanitize color - works for hex, rgb, rgba, hsl, & hsla values
     *
     * @param string $value
     * @return string
     */
    public static function color_sanitization( $value ) {
		$substring = 'var(--paletteColor';
		//Return variable if the color is set in the palleteColor format
		if (strpos($value, $substring) === 0) {
			return $value;
		}

        // This pattern will check and match 3/6/8-character hex, rgb, rgba, hsl, & hsla colors.
        $pattern = '/^(\#[\da-f]{3}|\#[\da-f]{6}|\#[\da-f]{8}|rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)(,\s*(0\.\d+|1))\)|hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\)|rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)|hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\))$/';
        \preg_match( $pattern, $value, $matches );
        // Return the 1st match found.
        if ( isset( $matches[0] ) ) {
            if ( is_string( $matches[0] ) ) {
                return $matches[0];
            }
            if ( is_array( $matches[0] ) && isset( $matches[0][0] ) ) {
                return $matches[0][0];
            }
        }
        // If no match was found, return an empty string.
        return '';
    }

	/**
     * Sanitize color - Sanitizes color for hex, rgb, rgba, hsl, & hsla values
     *
     * @param array $value
     * @return array
     */
    public static function sanitize_theme_color( $value ) {

		$sanitize_array = array();
		if(is_array($value)){
			foreach($value as $key => $color_data){
				$sanitize_array[$key]['color'] = self::color_sanitization($color_data['color']);
			}
		}

        return $sanitize_array;
    }

	/***
     * To ensure arrays are properly sanitized to WordPress Codex standards,
     * they encourage usage of sanitize_text_field(). That only works with a single
     * variable (string). This function allows for a full blown array to get sanitized
     * properly, while sanitizing each individual value in a key -> value pair.
     *
     * Note: Modified the original function to account for array with objects
     *
     * Source: https://wordpress.stackexchange.com/questions/24736/wordpress-sanitize-array
     * Author: Broshi, answered Feb 5 '17 at 9:14
     */
    public function sanitize_array( $array ) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = $this->sanitize_array( $value );
            } elseif ( is_object( $value ) ) {
                // If the value is an object, loop through its properties and sanitize them
                foreach ( $value as $prop => &$val ) {
                    if ( gettype($val) === 'integer' ){
                        $val = absint( $val );
                    } elseif( gettype($val) === 'string') {
                        $val = sanitize_text_field( $val );
                    } elseif(gettype($val) === 'boolean'){
                        $val = $this->sanitize_checkbox( $val );
                    }
                }
            } else {
                if ( gettype($value) === 'integer' ){
                    $value = absint( $value );
                } elseif( gettype($value) === 'string') {
                    $value = sanitize_text_field( $value );
                } elseif(gettype($value) === 'boolean'){
                    $value = $this->sanitize_checkbox( $value );
                } else {
                    $value = sanitize_text_field( $value );
                }
            }
        }
        return $array;
    }
}
