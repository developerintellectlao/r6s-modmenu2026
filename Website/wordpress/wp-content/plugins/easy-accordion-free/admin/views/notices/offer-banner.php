<?php
/**
 * The class for admin offer banner.
 *
 * @since        2.0.4
 * @version      2.0.4
 *
 * @package    Easy_Accordion_Free
 * @subpackage Easy_Accordion_Free/admin/views/notices
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

/**
 * Admin Offer Banner.
 */
class Easy_Accordion_Free_Offer_Banner {
	/**
	 * Retrieve and cache offers data from a remote API.
	 *
	 * @param string $api_url The URL of the API endpoint.
	 * @param int    $cache_duration Duration (in seconds) to cache the offers data.
	 *
	 * @return array The offers data, or an empty array if the data could not be retrieved or is invalid.
	 */
	public static function get_cached_offers_data( $api_url, $cache_duration = DAY_IN_SECONDS ) {
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
	public static function display_admin_offer_banner() {
		// Show only to Admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Retrieve offer banner data.
		$api_url = 'https://shapedplugin.com/offer/wp-json/shapedplugin/v1/easy-accordion';
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
		$banner_dismiss_status = get_option( 'sp_eafree_offer_banner_dismiss_status_' . $banner_unique_id ); // Banner closing or dismissing status.

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
		$action_title     = isset( $offer['action_title'] ) ? $offer['action_title'] : 'Grab the Deals!'; // Action button title.
		// Banner starting date & ending date according to EST timezone.
		$start_date   = strtotime( $start_date . ' 00:00:00 EST' ); // Convert start date to timestamp.
		$end_date     = strtotime( $end_date . ' 23:59:59 EST' ); // Convert end date to timestamp.
		$current_date = time(); // Get the current timestamp.

		// Only display the banner if the current date is within the specified range.
		if ( $current_date >= $start_date && $current_date <= $end_date ) {
			// Start Banner HTML markup.
			?>
			<div class="sp_eafree-admin-offer-banner-section">	
				<?php if ( ! empty( $plugin_logo ) ) { ?>
					<div class="sp_eafree-offer-banner-image">
						<img src="<?php echo esc_url( $plugin_logo ); ?>" alt="Plugin Logo" class="sp_eafree-plugin-logo">
					</div>
				<?php } if ( ! empty( $offer_name ) ) { ?>
					<div class="sp_eafree-offer-banner-image">
						<img src="<?php echo esc_url( $offer_name ); ?>" alt="Offer Name" class="sp_eafree-offer-name">
					</div>
				<?php } if ( ! empty( $offer_percentage ) ) { ?>
					<div class="sp_eafree-offer-banner-image">
						<img src="<?php echo esc_url( $offer_percentage ); ?>" alt="Offer Percentage" class="sp_eafree-offer-percentage">
					</div>
				<?php } ?>
				<div class="sp_eafree-offer-additional-text">
					<span class="sp_eafree-clock-icon">⏱</span><p><?php esc_html_e( 'Limited Time Offer, Upgrade Now!', 'wp-carousel-free' ); ?></p>
				</div>
				<?php if ( ! empty( $action_url ) ) { ?>
					<div class="sp_eafree-banner-action-button">
						<a href="<?php echo esc_url( $action_url ); ?>" class="sp_eafree-get-offer-button" target="_blank">
							<?php echo esc_html( $action_title ); ?>
						</a>
					</div>
				<?php } ?>
				<div class="sp_eafree-close-offer-banner" data-unique_id="<?php echo esc_attr( $banner_unique_id ); ?>"></div>
			</div>
			<script type='text/javascript'>
			jQuery(document).ready( function($) {
				$('.sp_eafree-close-offer-banner').on('click', function(event) {
					var unique_id = $(this).data('unique_id');
					event.preventDefault();
					$.post(ajaxurl, {
						action: 'sp_eafree-hide-offer-banner',
						sp_offer_banner: 'hide',
						unique_id,
						nonce: '<?php echo esc_attr( wp_create_nonce( 'splw_banner_notice_nonce' ) ); ?>'
					})
					$(this).parents('.sp_eafree-admin-offer-banner-section').fadeOut('slow');
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
	public function dismiss_offer_banner() {
		$post_data = wp_unslash( $_POST );
		if ( ! isset( $post_data['nonce'] ) || ! wp_verify_nonce( sanitize_key( $post_data['nonce'] ), 'splw_banner_notice_nonce' ) ) {
			return;
		}
		// Banner unique ID generated by offer key and offer starting date.
		$unique_id = isset( $post_data['unique_id'] ) ? sanitize_text_field( $post_data['unique_id'] ) : '';
		/**
		 * Update banner dismissal status to 'hide' if offer banner is closed of hidden by admin.
		 */
		if ( 'hide' === $post_data['sp_offer_banner'] && isset( $post_data['sp_offer_banner'] ) ) {
			$offer = 'hide';
			update_option( 'sp_eafree_offer_banner_dismiss_status_' . $unique_id, $offer );
		}
		die;
	}
}
