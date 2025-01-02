<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link        http://shapedplugin.com/
 * @since      3.2.4
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPCarouselPro\Admin;

class Cron_Schedules {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.2.4
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_schedules' ) );
		add_action( 'wp', array( $this, 'schedule_events' ) );
	}

	/**
	 * Registers new cron schedules.
	 *
	 * @since 3.2.4
	 *
	 * @param array $schedules
	 * @return array
	 */
	public function add_schedules( $schedules = array() ) {
		// Adds once weekly to the existing schedules.
		$schedules['weekly'] = array(
			'interval' => WEEK_IN_SECONDS,
			'display'  => __( 'Once Weekly', 'wp-carousel-pro' ),
		);

		return $schedules;
	}

	/**
	 * Schedules our events
	 *
	 * @since 3.2.4
	 * @return void
	 */
	public function schedule_events() {
		$this->weekly_events();
	}

	/**
	 * Schedule weekly events
	 *
	 * @access private
	 * @since 3.2.4
	 * @return void
	 */
	private function weekly_events() {
		if ( ! wp_next_scheduled( 'wp_carousel_pro_weekly_scheduled_events' ) ) {
			wp_schedule_event( time(), 'weekly', 'wp_carousel_pro_weekly_scheduled_events' );
		}
	}

}
