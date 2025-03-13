<?php
/**
 * The plugin license page.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.4
 *
 * @package    WP_Carousel_Pro
 * @subpackage WP_Carousel_Pro/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPCarouselPro\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\ShapedPlugin\WPCarouselPro\Includes\License' ) ) {
	/**
	 * License class to handle license.
	 *
	 * @since 3.2.4
	 */
	class License {

		/**
		 * File variable.
		 *
		 * @var string $file File.
		 */
		protected $file;
		/**
		 * The plugin version.
		 *
		 * @var string
		 */
		protected $version;
		/**
		 * The plugin author.
		 *
		 * @var string
		 */
		protected $author;
		/**
		 * The API URL.
		 *
		 * @var string
		 */
		private $api_url = 'https://shapedplugin.com/';
		/**
		 * The Plugin ID.
		 *
		 * @var integer
		 */
		private $item_id;
		/**
		 * Item_name
		 *
		 * @var mixed
		 */
		private $item_name;

		/**
		 * Construct of the class.
		 *
		 * @param  mixed $_file files.
		 * @param  mixed $_version plugin version.
		 * @param  mixed $_author author of the plugin.
		 * @param  mixed $_api_url api url.
		 * @param  mixed $_item_id item ids.
		 * @param  mixed $_item_name item name.
		 * @return void
		 */
		public function __construct( $_file, $_version, $_author, $_api_url = null, $_item_id = '', $_item_name = null ) {
			$this->file      = $_file;
			$this->item_name = $_item_name;
			$this->version   = $_version;
			$this->author    = $_author;
			$this->api_url   = is_null( $_api_url ) ? $this->api_url : $_api_url;

			if ( is_numeric( $_item_id ) ) {
				$this->item_id = absint( $_item_id );
			}
			//$this->includes();
		}


		/**
		 * Include the updater class
		 *
		 * @access  private
		 * @return mixed
		 */
		// private function includes() {
		// 	if ( ! class_exists( 'Auto_Update' ) ) {
		// 		require_once __DIR__ . '/class-wp-carousel-pro-auto-update.php';
		// 	}
		// }

		/**
		 * Check if the current server is localhost.
		 *
		 * @return boolean
		 */
		private function is_local_server() {
			$addr = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
			$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';

			$is_local = ( in_array( $addr, array( '127.0.0.1', '::1' ) ) || substr( $host, -4 ) == '.dev' );

			return apply_filters( 'wp_carousel_pro_is_local_server', $is_local );
		}

		/**
		 * License activate URL.
		 *
		 * @return string
		 */
		private function get_license_activate_url() {
			if ( is_multisite() ) {
				$home_url = network_home_url();
			} else {
				$home_url = apply_filters( 'sp_wp_carousel_home_url', home_url() );
			}
			return $home_url;
		}

		/**
		 * The option id for the license key
		 *
		 * @return string option_key
		 */
		public function get_license_option_key() {
			return 'sp_wpcp_settings';
		}

		/**
		 * Get the plugin/item/addon license key.
		 *
		 * @return string
		 */
		public function get_license_key() {
			$plugin_settings = get_option( $this->get_license_option_key(), false );
			if ( stripos( serialize( $plugin_settings ), 'license_key' ) ) {
				return $plugin_settings['license_key'];
			}
			return;
		}

		/**
		 * The option id for the license key status.
		 *
		 * @return string
		 */
		public function get_license_status_option_key() {
			return 'wp_carousel_pro_license_key_status';
		}

		/**
		 * Get license status.
		 *
		 * @return array
		 */
		public function get_license_status() {
			return get_option( $this->get_license_status_option_key(), array() );
		}

		/**
		 * Init Auto updater.
		 *
		 * @access  private
		 * @return  void
		 */
		public function init_updater() {

			$args = array(
				'version' => $this->version,
				'license' => $this->get_license_key(),
				'item_id' => $this->item_id,
				'author'  => $this->author,
				'url'     => $this->get_license_activate_url(),
			);

			// Setup the updater.
			$edd_updater = new Auto_Update(
				$this->api_url,
				$this->file,
				$args
			);
		}

		/**
		 * The API request.
		 *
		 * @param mixed $action of the license.
		 * @access  public
		 * @return  $license_data
		 */
		public function api_request( $action = 'check_license' ) {
			// data to send in our API request.
			$api_params = array(
				'edd_action' => $action,
				'license'    => $this->get_license_key(),
				'item_id'    => $this->item_id,
				'item_name'  => urlencode( $this->item_name ),
				'url'        => $this->get_license_activate_url(),
			);

			// Call the API.
			$response = wp_remote_post(
				$this->api_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			return $license_data;

		}

		/**
		 * Check license status weekly.
		 *
		 * @access  public
		 * @return  void
		 */
		public function check_license_status() {
			$license_key = $this->get_license_key();
			if ( empty( $license_key ) ) {
				return;
			}
			$license_data = $this->api_request();
			if ( $license_data ) {
				update_option( $this->get_license_status_option_key(), $license_data );
			}
		}

		/**
		 * Activate License.
		 *
		 * @return void
		 */
		public function wp_carousel_pro_activate_license() {

			// listen to our activate button to be clicked.
			if ( ! isset( $_POST['sp_wp_carousel_pro_license_activate'] ) ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			// run a quick security check.
			if ( ! check_admin_referer( 'sp_wp_carousel_pro_nonce', 'sp_wp_carousel_pro_nonce' ) ) {
				return;
			} // get out if we didn't click the Activate button.

			// retrieve the license from the database.
			$license = $this->get_license_key();

			// Data to send in our API request.
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_id'    => $this->item_id,
				'url'        => $this->get_license_activate_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$this->api_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay.
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'wp-carousel-pro' );
				}
			} else {

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				if ( false === $license_data->success ) {

					switch ( $license_data->error ) {

						case 'expired':
							$message = sprintf(
								__( 'Your license key expired on %s.', 'wp-carousel-pro' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;
						case 'revoked':
							$message = __( 'Your license key has been disabled.', 'wp-carousel-pro' );
							break;
						case 'missing':
							$message = __( 'Invalid license.', 'wp-carousel-pro' );
							break;
						case 'invalid':
						case 'site_inactive':
							$message = __( 'Your license is not active for this URL.', 'wp-carousel-pro' );
							break;

						case 'item_name_mismatch':
							$message = sprintf(
								__( 'This appears to be an invalid license key for %s.', 'wp-carousel-pro' ),
								WPCAROUSEL_ITEM_NAME
							);
							break;
						case 'no_activations_left':
							$message = __( 'Your license key has reached its activation limit.', 'wp-carousel-pro' );
							break;
						default:
							$message = __( 'An error occurred, please try again.', 'wp-carousel-pro' );
							break;
					}
				}
			}

			// Check if anything passed on a message constituting a failure.
			$base_url = admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=1' );
			if ( ! empty( $message ) ) {
				$redirect = add_query_arg(
					array(
						'wp_carousel_pro_license_activation' => 'false',
						'message' => urlencode( $message ),
					),
					$base_url
				);

				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"
			update_option( $this->get_license_status_option_key(), $license_data );
			wp_safe_redirect( $base_url );
			exit();
		}

		/**
		 * Deactivate the license.
		 *
		 * @return void
		 */
		public function wp_carousel_pro_deactivate_license() {

			// listen for our activate button to be clicked.
			if ( ! isset( $_POST['sp_wp_carousel_pro_license_deactivate'] ) ) {
				return;
			}
			// Check if the user an admin.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			// run a quick security check.
			if ( ! check_admin_referer( 'sp_wp_carousel_pro_nonce', 'sp_wp_carousel_pro_nonce' ) ) {
				return;
			} // get out if we didn't click the Activate button.

			// retrieve the license from the database.
			$license = $this->get_license_key();

			// data to send in our API request.
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_id'    => $this->item_id,
				'url'        => $this->get_license_activate_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$this->api_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      =>
						$api_params,
				)
			);

			// make sure the response came back okay.
			$base_url = admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=1' );
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = __( 'An error occurred, please try again.', 'wp-carousel-pro' );
				}

				$redirect = add_query_arg(
					array(
						'wp_carousel_pro_license_activation' => 'false',
						'message' => urlencode( $message ),
					),
					$base_url
				);

				wp_safe_redirect( $redirect );
				exit();
			}

			// decode the license data.
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed".
			// if ( 'deactivated' == $license_data->license ) {
				delete_option( $this->get_license_status_option_key() );
			// }

			wp_safe_redirect( $base_url );
			exit();
		}

		/**
		 * Notice
		 *
		 * @access  private
		 * @return  void
		 */
		public function license_active_notices() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$license_key_status = $this->get_license_status();
			$license_status     = ( is_object( $license_key_status ) ? $license_key_status->license : '' );
			$license_key        = $this->get_license_key();

			if ( 'valid' === $license_status ) {
				// Valid license.
			} elseif ( 'expired' === $license_status ) {
				$renew_url =
				$this->show_error(
					sprintf(
						__( 'Your <strong>WP Carousel Pro</strong> license key has been expired. Please <a href="%1$s" target="_blank">renew license key</a> at discount.', 'wp-carousel-pro' ),
						esc_url( WPCAROUSEL_STORE_URL . '/checkout/?edd_license_key=' . $license_key . '&download_id=' . WPCAROUSEL_ITEM_ID . '&utm_campaign=wp_carousel_pro&utm_source=licenses&utm_medium=expired' )
					)
				);
			} else {
				echo sprintf(
					__( '<div class="wp-carousel-pro-license-notice notice"><div class="wp-carousel-pro-notice-icon"><p>You must activate the license key to make the <strong><a href="%1$s" target="_blank">WP Carousel Pro</a></strong> plugin work, get automatic updates & support.</p><a href="%2$s" class="wp-carousel-pro-activate-btn">Activate</a></div></div>', 'wp-carousel-pro' ),
					esc_url( WPCAROUSEL_PRODUCT_URL ),
					esc_url( admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=1' ) )
				);
				echo '<style>
				/* License notice */
				.wp-carousel-pro-license-notice {
					background-color: #167e98;
					border: 0px;
					padding: 0;
					margin-left: 0;
				}
				.wp-carousel-pro-license-notice .wp-carousel-pro-notice-icon {
					background-image: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDI0LjMuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAyMzQ4LjMyIDkzNS4zNyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjM0OC4zMiA5MzUuMzc7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4KCS5zdDB7ZmlsbC1ydWxlOmV2ZW5vZGQ7Y2xpcC1ydWxlOmV2ZW5vZGQ7ZmlsbDojRjc2MjAxO30KCS5zdDF7ZmlsbDojRkZGRkZGO30KCS5zdDJ7b3BhY2l0eTowLjYzO2ZpbGw6I0ZGRkZGRjt9Cgkuc3Qze2ZpbGw6I0VGQTg4Rjt9Cgkuc3Q0e2ZpbGw6IzAyNjdBNDt9Cjwvc3R5bGU+CjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0yMTI4LjM2LDU0MS4wNWMtMzcuNzYsMTkuMi02Ni4wMyw4LjQzLTg4Ljc3LTkuODljLTMxLjcyLTI1LjU1LTI2LjgzLTQ1Ljk1LTMwLjM5LTgwLjYzCgljLTMuOTgtMzguODItNDYuMTctMjYuMTUtNDYuMTctODguNTJjMC01NS43NCw0OC45MS0zNC45LDM2LjIxLTgzLjY4Yy0xNC45NS01Ny40Myw1Mi4zNy04NC42MSw3MS42My02OS43OAoJYzIwLjksMTYuMDksNTIuMDQsNS4wMyw2Ny40OS0xMC44NGMyOC4yMS0yOC45Nyw2MC4wOS0zMS44NSw4Ny45Ni00Ljk4czE1LjkzLDMxLjE2LDQzLjgsMzMuMTVjMjcuODcsMS45OSw1NC41MSw1LjA5LDY1LjcsNDcuNDgKCWMxMi4wOCw0NS43OS00LjU3LDUxLjM3LTUuNDQsNzAuMzRjLTEuOTksNDMuMTMsMjMuMjMsMzkuMTUsMTYuMzIsNzYuOThjLTUuMTksMjguNC0yNS40NiwyNC42NC0zNy41NSwzOS4xNQoJYy0xNC45MywxNy45MiwwLjA0LDQ1LjE4LTE3LjEyLDYxLjM4Yy0yNy44NywyNi4zMi02MS45NS0xLjE3LTc1LjY1LDE1Ljk0QzIxODguNSw1NzEuOTcsMjE2NS41NSw1MjIuMTQsMjEyOC4zNiw1NDEuMDV6Ii8+CjxnPgoJPHBhdGggY2xhc3M9InN0MSIgZD0iTTIyMDguNTcsNDc2LjIyYy0yMC4yNy0wLjgtNjQuOTcsMS40NC04OS44NSwwLjI0Yy0xOS45OS0wLjk3LDEwLjgzLTg2LjQ3LDE4LjcyLTk3Ljk0CgkJYzAuNDItMC42MSwwLjQyLTEuMjcsMC4wOS0yLjAyYy0yLjc4LTYuMzctMjkuNDQtMTguODgtMjkuNDQtNTMuOTNjMC04LjI1LDEuMDgtMTUuNTYsMy4wNC0yMS45NWMwLjA2LTAuMTksMC4xMi0wLjM4LDAuMTgtMC41NwoJCWM0LjA0LTEyLjcxLDExLjY0LTIxLjc2LDIxLjI2LTI3LjM5YzAuMDEsMCwwLjAxLTAuMDEsMC4wMi0wLjAxYzguMjItNC44LDE3LjkyLTcuMTIsMjguMTUtNy4xMmMxMi40NywwLDIyLjExLDIuNjYsMjkuNTIsNi45OQoJCWM1LjM2LDMuMTMsOS41Niw3LjE0LDEyLjgxLDExLjY1YzMuNjcsNS4wOCw2LjE0LDEwLjgxLDcuNzIsMTYuNjRjMC4wMSwwLjAyLDAuMDEsMC4wNSwwLjAyLDAuMDdjMS44OSw2Ljk2LDIuNTIsMTQuMDcsMi40NSwyMC40MQoJCWMtMC4zNiwzMi41OC0yNi4wNiw0OS4wNi0yNy4xOSw1NS4xNGMtMC4wOSwwLjUtMC4wMiwwLjkzLDAuMjUsMS4yOUMyMTg5LjgzLDM4Mi41MSwyMjI2LjI2LDQ3Ni45MiwyMjA4LjU3LDQ3Ni4yMnoiLz4KCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0yMTYwLjc0LDI3My45OGM0My44MiwwLDQ4LjU3LDI2LjM5LDQ4LjA0LDQ5LjUzYy0wLjc2LDMzLjMyLTI5LjE0LDQ4LjA3LTI0LjksNTUuMDEKCQljMy4xMSw1LjA5LDM3LjIxLDk3LjQ4LDI0LjY4LDk3LjdjLTM0LjcxLDAuNi01Ny40NywyLjk2LTg0LjgsMC42Yy0xMy4wMS0xLjEyLDE0LjI2LTg3LjE0LDIyLjE0LTk4LjYxCgkJYzMuOTYtNS43Ny0yOS40Ni0xNy40LTI5LTU0LjdDMjExNy4zMSwyOTEuMTEsMjEzMi4zMywyNzMuOTgsMjE2MC43NCwyNzMuOTh6Ii8+CjwvZz4KPGc+Cgk8cGF0aCBjbGFzcz0ic3QxIiBkPSJNMTQwMS44OCwzNjkuODhjMC45LDEuNzIsMS4xNSwzLjcxLDAuNzMsNS42bC0xLjg1LDguMjVjLTEuMTYsNS4xNiwyLjc4LDEwLjA2LDguMDcsMTAuMDNsMTEuOTktMC4wNwoJCWwxNS45MS0wLjFsMTE0LjkzLTAuNjlsMzguNzItMC4yM2M0Ljk1LTAuMDMsOC44Miw0LjI4LDguMjMsOS4yYy0xLjU3LDEzLjE3LTMuNTMsMzUuNzQsMS41LDM1LjcxCgkJYzUuMDMtMC4wMywyNy44Ni0wLjE3LDQwLjc2LTAuMjVjNC44MS0wLjAyLDguNTctNC4xNiw4LjE0LTguOTZsLTIuNDMtMjcuMjFjLTAuNDMtNC43NCwzLjI2LTguODYsOC4wMy04Ljk1CgkJYzEwLjg0LTAuMjIsMzEuMTMtMC43OCw1Ny42OC0yLjJjNC43OS0wLjI1LDguNzksMy42NCw4LjY2LDguNDRsLTIuMDEsNzQuODljLTAuMTMsNC42NSwzLjYyLDguNDgsOC4yNyw4LjQ1bDE1LjQyLTAuMDkKCQljNC41Ny0wLjAzLDguMjUtMy43OSw4LjE3LTguMzZsLTEuMjItNzcuNGMtMC4wNy00LjU0LDMuNTUtOC4yOSw4LjA5LTguMzZsMTEuNjQtMC4xOGM0LjU1LTAuMDcsOC4yOSwzLjU1LDguMzYsOC4wOWwxLjIzLDc3Ljg1CgkJYzAuMDcsNC41MSwzLjc3LDguMTIsOC4yOCw4LjA5bDExLjM2LTAuMDdjNC4zNy0wLjAyLDcuOTYtMy40Niw4LjE3LTcuODNjMC41OC0xMi4wMSwxLjUxLTM4LjAxLDEuNC03OC42OQoJCWMtMC4wMS00LjUzLDMuNjQtOC4yMyw4LjE3LTguMjVjOS4zNy0wLjA2LDIzLjU1LTAuMTUsNDQuNTUtMC4yN2M4NC4zMS0wLjUxLDE0LjQzLTQxLjUyLDEuNjMtNDguNjUKCQljLTEuMjQtMC42OS0yLjYzLTEuMDUtNC4wNS0xLjA0bC00MjIuNzgsMi41M2wtMzEuMSwwLjE4Yy0zLjc1LDAuMDMtNy4wMSwyLjU4LTcuOTMsNi4yMWwtMS42NSw2LjUKCQljLTAuNDksMS45NS0wLjI1LDQuMDIsMC42OCw1LjgxTDE0MDEuODgsMzY5Ljg4eiIvPgoJPHBhdGggY2xhc3M9InN0MSIgZD0iTTEyMTYuODQsMzY5LjIzYzAuMjEsMzQuOTMsMTYuNDgsNjYuMDEsNDEuNzcsODYuMjdjMTkuMjMsMTUuNDIsNDMuNjgsMjQuNTgsNzAuMjUsMjQuNDIKCQljNTIuNjctMC4zMSw5Ni41OC0zNy4xMywxMDcuODctODYuMzNjMC42Ni0yLjg1LDEuMjEtNS43NSwxLjY0LTguNjhjMC44MS01LjU1LDEuMjItMTEuMjMsMS4xOC0xNy4wMQoJCWMtMC4wNi05Ljk0LTEuNDItMTkuNTgtMy45Mi0yOC43NGMtNC45My0xOC4wOC0xNC4zMS0zNC4zMi0yNi44OC00Ny40OGMtMjAuNDItMjEuNC00OS4yOS0zNC42Ni04MS4yMi0zNC40NwoJCWMtNy4wNCwwLjA0LTEzLjkyLDAuNzQtMjAuNTgsMi4wM0MxMjU1LjM0LDI2OS4yLDEyMTYuNTEsMzE0Ljc2LDEyMTYuODQsMzY5LjIzeiBNMTM4NS41NiwzNjguMjIKCQljMC4xOSwzMS42OC0yNS4zNCw1Ny41Mi01Ny4wMiw1Ny43MWMtMzEuNjgsMC4xOS01Ny41Mi0yNS4zNC01Ny43MS01Ny4wMmMtMC4xOS0zMS42OCwyNS4zNC01Ny41Miw1Ny4wMi01Ny43MQoJCUMxMzU5LjUzLDMxMS4wMSwxMzg1LjM3LDMzNi41NCwxMzg1LjU2LDM2OC4yMnoiLz4KPC9nPgo8cGF0aCBjbGFzcz0ic3QyIiBkPSJNMTI1OC42MSw0NTUuNWMxOS4yMywxNS40Miw0My42OCwyNC41OCw3MC4yNSwyNC40MmM1Mi42Ny0wLjMxLDk2LjU4LTM3LjEzLDEwNy44Ny04Ni4zM2wxMTQuOTMtMC42OQoJYy0zNi4xNS04LjE3LTg1Ljc4LTE0LjE3LTExMy4yOS03Ljk5Yy02LjI3LDEuNC0xMS4zOSwzLjQ0LTE0Ljk0LDYuMjNjLTAuOTIsMC43My0xLjc5LDEuNTgtMi42MSwyLjU1CgljLTEwLjMyLDEyLjA0LTE1LjAzLDQxLjc0LTUxLjgsNTguOTRDMTMzOS45OCw0NjYuMjIsMTI5Ni4yLDQ3My4yMSwxMjU4LjYxLDQ1NS41eiIvPgo8Zz4KCTxwYXRoIGNsYXNzPSJzdDMiIGQ9Ik0tMC40Miw0MDYuMzh2NDQ4LjE2YzExNS43OC04Ni4zLDI0Mi41OC0xODIuMDQsMzQ0LjMtMjU2LjAyYzIyLjY1LTE2LjQ4LDQ0LjA2LTMxLjg4LDYzLjgyLTQ1Ljg1CgkJYzcuNjQtNS40LDE1LjAzLTEwLjU5LDIyLjE2LTE1LjU1YzE5LjI3LTEyLjcxLDEwNC40NS03MC4wMSwyMTcuODktODUuMDJjOC40NS0xLjAzLDE3LjQzLTEuNjQsMjYuODQtMS44OWgwLjA3CgkJYzExLjEyLTAuMywyMi44My0wLjEsMzQuOTQsMC40OWMxMDEuNTQsNC45MiwyMzEuMywzNy4yOSwyNzguNjMsMzUuNDFjNzQuNTYtOS4wNSw0MDEuOTUsNDMuNDYsMjI4LjczLTc1LjU1CgkJYy0zNi0yOC45NC0xMTUuNjQtNDUuNjYtMTk0LjM3LTU3LjUzYy03OS4xOC0xMC43NC0xMjUuNTktNTguMzgtNTguMTQtMTE5LjQyYzY3LjQ3LTYxLjA0LDIzMi43My0zNS41NSw0NDYuMDgsNTguMjcKCQljMTQ4LjIxLDIwLjk1LDcxLjUtNzkuNTEtODAuMzQtMTQ1Ljc1QzExNzcuMjgsNzkuNDMsMTA0OS4wNS0yMS41OSw5OTMuMDEsNC4wOUM5MzYuOTYsMjkuNzYsODQzLjQsMzAuOTQsNjg1LjQsODAuNzMKCQlTNDc0LjkxLDIyMS4yLDM0NC45MiwyNTMuMjljLTMzLjcsMTIuMzctNjksMjYuNDUtMTA1LjY2LDQxLjk3Yy0zMi4wNiwxMy41OC02NS4xNywyOC4yNi05OS4xMyw0My44NwoJCUM5NC42OSwzNjAuMDIsNDcuNywzODIuNTctMC40Miw0MDYuMzh6Ii8+Cgk8cGF0aCBjbGFzcz0ic3QxIiBkPSJNLTAuNDIsMzY2LjZ2NTE5Ljc4QzE1MS41Niw3NjYuNTksMjgxLDY2NS45NSwzNTAuOTcsNjExLjMzYzMyLjU4LTI1LjQ0LDUyLjI3LTQwLjksNTUuMzUtNDMuNjgKCQljMi43OC0yLjUxLDMuMDgtNy43LDEuMzgtMTQuOThjLTMuNjgtMTUuODItMTYuODItNDEuNS0zNC41OS03MC45OWMtMzkuNTEtNjUuNjItMTAxLjktMTUwLjA1LTEzMy44NS0xODYuNDIKCQljLTUuNy02LjQ5LTEwLjQzLTExLjQ0LTEzLjg5LTE0LjQ5Yy02LjQ1LTUuNjgtNDUuOTgsNy4yNC0xMDkuNjUsMzQuMDZDODIuOTgsMzI4LjYyLDQzLjg2LDM0Ni4wOS0wLjQyLDM2Ni42eiIvPgoJPHBhdGggY2xhc3M9InN0NCIgZD0iTS0wLjQyLDM1OC41MWwwLDU3Ni44NmMxOTkuODktMTYyLjQ5LDM1MC42Ny0yODUuOTQsMzU4LjQ0LTI5NC4xOGMzLjc3LTQsMC43Ny0xNC42OC03LjA1LTI5Ljg2CgkJYy0yLjA0LTMuOTctNC40Mi04LjI1LTcuMDktMTIuODFjLTEzLjMtMjIuNjYtMzMuOTEtNTIuMDItNTcuNDktODMuMjhjLTQ4Ljg2LTY0Ljc2LTExMC40OC0xMzcuNjgtMTQ2LjI2LTE3Ni4xMQoJCWMtMTAuOS0xMS42OS0xOS4zOS0yMC4yLTI0LjQxLTI0LjNjLTEuMjUtMS4wMy0yLjI5LTEuNzgtMy4wOS0yLjI0QzEwNy44MSwzMDkuODMsNjYuODIsMzI2Ljg3LTAuNDIsMzU4LjUxeiIvPgo8L2c+Cjwvc3ZnPgo=");
					background-repeat: no-repeat;
					background-size: 150px;
					background-position: 0 18px;
					padding: 24px 26px;
					color: #fff;
					position: relative;
				}
				.wp-carousel-pro-license-notice p {
					padding: 0;
					margin: 0;
					font-size: 16px;
					padding-left: 135px;
					line-height: 34px;
					padding-right: 120px;
				}
				.wp-carousel-pro-license-notice p a {
					color: #fff;
				}
				.wp-carousel-pro-license-notice .wp-carousel-pro-activate-btn {
					color: #167e98;
					background: #fff;
					border-radius: 3px;
					text-decoration: none;
					padding: 8px 16px;
					text-transform: uppercase;
					font-size: 14px;
					font-weight: 600;
					position: absolute;
					right: 26px;
					top: 24px;
				}
				.wp-carousel-pro-license-notice .wp-carousel-pro-activate-btn:hover {
					color: #167e98;
					background: #fafafa;
				}
				.wp-carousel-pro-license-notice .wp-carousel-pro-activate-btn:focus{
					outline: none;
					box-shadow: 0 0 0;
				}</style>';
			}
		}

		/**
		 * License Notices.
		 *
		 * @return license notice.
		 */
		public function license_notices() {
			settings_errors();
			if ( isset( $_GET['wp_carousel_pro_license_activation'] ) && ! empty( $_GET['message'] ) ) {

				switch ( $_GET['wp_carousel_pro_license_activation'] ) {

					case 'false':
						$message = urldecode( $_GET['message'] );
						return ( $message );
						break;
					case 'true':
					default:
						// Developers can put a custom success message here for when activation is successful if they way.
						break;

				}
			}
		}

		/**
		 * Show a error message
		 *
		 * @param string $message error message.
		 *
		 * @return void
		 */
		public function show_error( $message ) {
			echo '<div class="error">';
			echo '<p>' . wp_kses_post( $message ) . '</p>';
			echo '</div>';
		}

		/**
		 * Displays message inline on plugin row that the license key is missing.
		 *
		 * @param  mixed $plugin_data plugin data.
		 * @param  mixed $version_info version info.
		 * @return void
		 */
		public function plugin_row_license_missing( $plugin_data, $version_info ) {
			static $showed_missing_key_message;

			$license = $this->get_license_status();

			if ( ( ! is_object( $license ) || 'valid' !== $license->license ) && empty( $showed_missing_key_message[ $this->get_license_option_key() ] ) ) {

				echo '&nbsp;<strong><a href="' . esc_url( admin_url( 'edit.php?post_type=sp_wp_carousel&page=wpcp_settings#tab=1' ) ) . '">' . esc_html__( 'Enter valid license key for automatic updates.', 'wp-carousel-pro' ) . '</a></strong>';
				$showed_missing_key_message[ $this->get_license_option_key() ] = true;
			}
		}

	}
}
