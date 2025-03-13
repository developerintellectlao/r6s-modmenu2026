<?php
/**
 * The file is to generate of kinds of admin facing notices.
 *
 * @since        2.1.5
 * @version      2.1.5
 *
 * @package    WP_Carousel_Free
 * @subpackage WP_Carousel_Free/admin/views/notices
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

/**
 * This class is responsible for the admin-specific notices of the plugin.
 */
class WP_Carousel_Admin_Notices {

	/**
	 * Display review admin notice.
	 *
	 * @return void
	 */
	public function display_admin_notice() {
		// Show only to Admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Variable default value.
		$review = get_option( 'sp_wp_carousel_free_review_notice_dismiss' );
		$time   = time();
		$load   = false;

		if ( ! $review ) {
			$review = array(
				'time'      => $time,
				'dismissed' => false,
			);
			add_option( 'sp_wp_carousel_free_review_notice_dismiss', $review );
		} elseif ( ( isset( $review['dismissed'] ) && ! $review['dismissed'] ) && ( isset( $review['time'] ) && ( ( $review['time'] + ( DAY_IN_SECONDS * 3 ) ) <= $time ) ) ) {
			$load = true;
		}

		// If we cannot load, return early.
		if ( ! $load ) {
			return;
		}
		?>
		<div id="sp-wpcfree-review-notice" class="sp-wpcfree-review-notice">
			<div class="sp-wpcfree-plugin-icon">
				<img src="<?php echo esc_url( WPCAROUSELF_URL ) . 'admin/img/wp-carousel-pro.svg'; ?>" alt="WP Carousel">
			</div>
			<div class="sp-wpcfree-notice-text">
				<h3>Enjoying <strong>WP Carousel</strong>?</h3>
				<p>We hope you had a wonderful experience using <strong>WP Carousel</strong>. Please take a moment to leave a review on <a href="https://wordpress.org/support/plugin/wp-carousel-free/reviews/?filter=5#new-post" target="_blank"><strong>WordPress.org</strong></a>.
				Your positive review will help us improve. Thank you! 😊</p>

				<p class="sp-wpcfree-review-actions">
					<a href="https://wordpress.org/support/plugin/wp-carousel-free/reviews/?filter=5#new-post" target="_blank" class="button button-primary notice-dismissed rate-wp-carousel">Ok, you deserve ★★★★★</a>
					<a href="#" class="notice-dismissed remind-me-later"><span class="dashicons dashicons-clock"></span>Nope, maybe later
					</a>
					<a href="#" class="notice-dismissed never-show-again"><span class="dashicons dashicons-dismiss"></span>Never show again</a>
				</p>
			</div>
		</div>

		<script type='text/javascript'>

			jQuery(document).ready( function($) {
				$(document).on('click', '#sp-wpcfree-review-notice.sp-wpcfree-review-notice .notice-dismissed', function( event ) {
					if ( $(this).hasClass('rate-wp-carousel') ) {
						var notice_dismissed_value = "1";
					}
					if ( $(this).hasClass('remind-me-later') ) {
						var notice_dismissed_value =  "2";
						event.preventDefault();
					}
					if ( $(this).hasClass('never-show-again') ) {
						var notice_dismissed_value =  "3";
						event.preventDefault();
					}

					$.post( ajaxurl, {
						action: 'sp-wpcfree-never-show-review-notice',
						notice_dismissed_data : notice_dismissed_value,
						nonce: "<?php echo esc_html( wp_create_nonce( 'wpcfree-review-notice' ) ); ?>",
					});

					$('#sp-wpcfree-review-notice.sp-wpcfree-review-notice').hide();
				});
			});

		</script>
		<?php
	}

	/**
	 * Dismiss review notice
	 *
	 * @since  2.1.5
	 *
	 * @return void
	 **/
	public function dismiss_review_notice() {
		$review = array();
		$nonce  = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( current_user_can( 'manage_options' ) && wp_verify_nonce( $nonce, 'wpcfree-review-notice' ) ) {
			$notice_dismissed = ( ! empty( $_POST['notice_dismissed_data'] ) ) ? sanitize_text_field( wp_unslash( $_POST['notice_dismissed_data'] ) ) : '';

			switch ( $notice_dismissed ) {
				case '1':
					$review['time']      = time();
					$review['dismissed'] = true;
					break;
				case '2':
					$review['time']      = time();
					$review['dismissed'] = false;
					break;
				case '3':
					$review['time']      = time();
					$review['dismissed'] = true;
					break;
			}
			update_option( 'sp_wp_carousel_free_review_notice_dismiss', $review );
			die;
		}
	}

	/**
	 * Retrieve and cache offers data from a remote API.
	 *
	 * @param string $api_url The URL of the API endpoint.
	 * @param int    $cache_duration Duration (in seconds) to cache the offers data. Default is 1 hour.
	 *
	 * @return array The offers data, or an empty array if the data could not be retrieved or is invalid.
	 */
	public static function get_cached_offers_data( $api_url, $cache_duration = HOUR_IN_SECONDS ) {
		$cache_key   = 'sp_offers_data_' . md5( $api_url ); // Unique cache key based on the API URL.
		$offers_data = get_transient( $cache_key );

		if ( false === $offers_data ) {
			// Data not in cache; fetch from API.
			$offers_data = self::sp_fetch_offers_data( $api_url );
			set_transient( $cache_key, $offers_data, $cache_duration ); // Cache the data.
		}

		return $offers_data;
	}

	/**
	 * Fetch offers data directly from a remote API.
	 *
	 * @param string $api_url The URL of the API endpoint to fetch offers data from.
	 * @return array The offers data, or an empty array if the API request fails or returns invalid data.
	 */
	public static function sp_fetch_offers_data( $api_url ) {
		// Fetch API data.
		$response = wp_remote_get(
			$api_url,
			array(
				'timeout' => 15, // Timeout in seconds.
			)
		);

		// Check for errors.
		if ( is_wp_error( $response ) ) {
			return array();
		}

		// Decode JSON response.
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		// Validate and return data from the offer 0 index.
		return isset( $data['offers'][0] ) && is_array( $data['offers'][0] ) ? $data['offers'][0] : array();
	}

	/**
	 * Show offer banner.
	 *
	 * @since  3.0.4
	 *
	 * @return void
	 **/
	public function show_admin_offer_banner() {
			// Show only to Admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Retrieve offer banner data.
		$api_url = 'https://shapedplugin.com/offer/wp-json/shapedplugin/v1/wp-carousel';
		$offer   = self::get_cached_offers_data( $api_url );
		// Ensure the array is not empty and includes 'org' as a valid value.
		$enable_for_org = ( ! empty( $offer['offer_enable'][0] ) && in_array( 'org', $offer['offer_enable'], true ) );

		// Return an empty string if the offer is empty, not an array, or not enabled for the org.
		if ( empty( $offer ) || ! is_array( $offer ) || ! $enable_for_org ) {
			return '';
		}

		$offer_key             = isset( $offer['key'] ) ? esc_attr( $offer['key'] ) : ''; // Uniq identifier of the offer banner.
		$start_date            = isset( $offer['start_date'] ) ? esc_html( $offer['start_date'] ) : ''; // Offer starting date.
		$banner_unique_id      = $offer_key . strtotime( $offer['start_date'] ); // Generate banner unique ID by the offer key and starting date.
		$banner_dismiss_status = get_option( 'sp_wcf_offer_banner_dismiss_status_' . $banner_unique_id ); // Banner closing or dismissing status.
		// Only display the banner if the dismissal status of the banner is not hide.
		if ( isset( $banner_dismiss_status ) && 'hide' === $banner_dismiss_status ) {
			return;
		}

		// Declare admin banner related variables.
		$end_date         = isset( $offer['end_date'] ) ? esc_html( $offer['end_date'] ) : ''; // Offer ending date.
		$plugin_logo      = isset( $offer['plugin_logo'] ) ? $offer['plugin_logo'] : ''; // Plugin logo URL.
		$offer_name       = isset( $offer['offer_name'] ) ? $offer['offer_name'] : ''; // Offer name.
		$offer_percentage = isset( $offer['offer_percentage'] ) ? $offer['offer_percentage'] : ''; // Offer discount percentage.
		$action_url       = isset( $offer['action_url'] ) ? $offer['action_url'] : ''; // Action button URL.
		$action_title     = isset( $offer['action_title'] ) ? $offer['action_title'] : ''; // Action button title.
		// Banner starting date & ending date according to UTC timezone.
		$start_date   = strtotime( $start_date . ' 00:00:00 EST' ); // Convert start date to timestamp.
		$end_date     = strtotime( $end_date . ' 23:59:59 EST' ); // Convert end date to timestamp.
		$current_date = time(); // Get the current timestamp.

		// Only display the banner if the current date is within the specified range.
		if ( $current_date >= $start_date && $current_date <= $end_date ) {
			// Start Banner HTML markup.
			?>
			<div class="sp_wp_carousel-notice-info sp_wp_carousel-custom-admin-notice">
				<div class="sp_wp_carousel-notice-image">
					<img src="<?php echo esc_url( $plugin_logo ); ?>" alt="Plugin Logo" class="sp_wp_carousel-plugin-logo">
				</div>
				<div class="sp_wp_carousel-notice-image">
					<img src="<?php echo esc_url( $offer_name ); ?>" alt="Offer Name" class="sp_wp_carousel-offer-name">
				</div>
				<div class="sp_wp_carousel-notice-image">
					<img src="<?php echo esc_url( $offer_percentage ); ?>" alt="Offer Percentage" class="sp_wp_carousel-offer-percentage">
				</div>
				<div class="sp_wp_carousel-offer-text">
				<span class="sp_wp_carousel-clock-icon">⏱</span><p><?php esc_html_e( 'Limited Time Offer, Upgrade Now!', 'wp-carousel-free' ); ?> </p>
				</div>
				<div class="sp_wp_carousel-notice-content">
					<a href="<?php echo esc_url( $action_url ); ?>" class="sp_wp_carousel-get-offer-button" target="_blank">
					<?php echo esc_html( $action_title ); ?>
					</a>
				</div>
				<div class="sp_wp_carousel-close-offer-banner" data-unique_id="<?php echo esc_attr( $banner_unique_id ); ?>">
					<i class="sp_wp_carousel-icon-banner-close"></i>
				</div>
			</div>
			<script type='text/javascript'>
			jQuery(document).ready( function($) {
				$('.sp_wp_carousel-close-offer-banner').on('click', function(event) {
					var unique_id = $(this).data('unique_id');
					event.preventDefault();
						$.post(ajaxurl, {
							action: 'sp-carousel-hide-offer-banner',
							sp_offer_banner: 'hide',
							unique_id,
							nonce: '<?php echo esc_attr( wp_create_nonce( 'sp_wp_carousel_banner_notice_nonce' ) ); ?>'
						})
						$(this).parents('.sp_wp_carousel-custom-admin-notice').fadeOut('slow');
					});
				});
			</script>
			<?php
		}
	}

	/**
	 * Dismiss review notice
	 *
	 * @since  3.0.4
	 *
	 * @return void
	 **/
	public function dismiss_friday_offer_banner() {
		$post_data = wp_unslash( $_POST );
		if ( ! isset( $post_data['nonce'] ) || ! wp_verify_nonce( sanitize_key( $post_data['nonce'] ), 'sp_wp_carousel_banner_notice_nonce' ) ) {
			return;
		}
		// Banner unique ID generated by offer key and offer starting date.
		$unique_id = isset( $post_data['unique_id'] ) ? sanitize_text_field( $post_data['unique_id'] ) : '';
		/**
		 * Update banner dismissal status to 'hide' if offer banner is closed of hidden by admin.
		 */
		if ( 'hide' === $post_data['sp_offer_banner'] && isset( $post_data['sp_offer_banner'] ) ) {
			$offer = 'hide';
			update_option( 'sp_wcf_offer_banner_dismiss_status_' . $unique_id, $offer );
		}
		die;
	}
}
