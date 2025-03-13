<?php
/**
 * Class Rishi Companion updater class.
 *
 * @package Rishi_Companion
 */
namespace Rishi_Companion;

class Rishi_Companion_Updater {


    /**
     * Download ID
     *
     * @var integer
     */
    private $download_id = 166692;

	protected $theme_slug = null;

    /**
	 * EDD API URL
	 *
	 * @var string
	 */
	private $edd_api_url = 'https://rishitheme.com/';


    /**
     * Class Constructor
     */
    public function __construct( $config = array(), $strings = array()) {
        $this->includes();
        $this->init_hooks();
		$config = wp_parse_args(
			$config,
			array(
				'remote_api_url' => 'https://easydigitaldownloads.com',
				'theme_slug'     => 'rishi-companion',
				'item_name'      => '',
				'license'        => '',
				'version'        => '',
				'author'         => '',
				'download_id'    => '',
				'renew_url'      => '',
				'beta'           => false,
				'item_id'        => '',
			)
		);
		$this->theme_slug     = sanitize_key( $config['theme_slug'] );
    }

    /**
     * Init hooks
     *
     * @return void
     */
    public function init_hooks() {

        add_action( 'admin_init', [ $this, 'initialize_updater' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'wp_ajax_control_activate_license', [ $this, 'activate_license' ] );
		add_action( 'wp_ajax_control_deactivate_license', [ $this, 'deactivate_license' ] );
		add_action( 'wp_ajax_get_license_status', [ $this, 'get_license_status' ] );
		add_action( 'admin_notices', [ $this, 'notices_handler' ]);
    }

	/**
	 * Register Settings
	 *
	 * @return void
	 */
	public function register_settings() {
		// creates our settings in the options table
		register_setting(
			$this->theme_slug . '_license_key',
			$this->theme_slug . '_license_key',
			array(
				'sanitize_callback' => [$this, 'sanitize_license' ],
			)
		);
	}

	/**
	 * Notices Handler
	 *
	 * @return void
	 */
	public function notices_handler() {
		$this->plugin_activation_notice();
	}

	/**
	 * Plugin activation notice
	 *
	 * @return void
	 */
	public function plugin_activation_notice() {
		global $current_user;
		$add_license        = get_option( $this->theme_slug . '_license_key' );
		$activate_license   = isset( $add_license['license_status'] ) && ! empty( $add_license['license_status'] ) ? $add_license['license_status']: '';
		$statuses           = array( 'invalid', 'inactive', 'expired', 'disabled', 'site_inactive', '' );

		if( empty( $add_license ) || in_array( $activate_license, $statuses ) ) { ?>
			<div class="notice notice-info is-dismissible">
				<p><?php printf(
					/* translators: %1$s is a placeholder for the plugin name (Rishi Companion) */
					esc_html__( 'Activate the license key for %1$s plugin to enjoy the full benefits of the advanced features.', 'rishi-companion' ),
					'<strong>Rishi Companion</strong>'
					);?></p>
				<p>
					<span style="color:red;"><?php esc_html_e( 'Please Activate Plugin License ', 'rishi-companion' ); ?></span><a href="<?php echo esc_url( admin_url( 'themes.php?page=rishi-dashboard' ) ) ?>"><?php esc_html_e( 'here.', 'rishi-companion' ); ?></a><?php esc_html_e( ' If you face any issue, please contact our support ', 'rishi-companion' ); ?><a href="https://rishitheme.com/support/" target="_blank"><?php esc_html_e('here.', 'rishi-companion'); ?></a><?php esc_html_e( ' Here’s a guide to ', 'rishi-companion' ); ?><a href="<?php echo esc_url( 'https://rishitheme.com/docs/activate-rishi-pro-and-rishi-companion-license/' ); ?>" target="_blank"><u><?php esc_html_e( 'activate your license key.', 'rishi-companion' ); ?></u></a>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Get version
	 *
	 * @return void
	 */
	public function get_version($addon) {
		$update_cache    = get_site_transient( 'update_plugins' );
		$update_cache    = is_object( $update_cache ) ? $update_cache : new stdClass();
		$license_options = get_option( $this->theme_slug . '_license_key', array() );
		$license_key     = isset( $license_options['license_key'] ) ? $license_options['license_key'] : '';

		if ( ! empty( $update_cache->response[ $addon->filepath ] ) ) {
			$response = $update_cache->response[ $addon->filepath ];
		} else {
			$verify_ssl = $this->verify_ssl();

			$addon_slug = $addon->slug;
			// Request to get current version.
			$response = wp_remote_post(
				$this->edd_api_url,
				array(
					'timeout'   => 15,
					'sslverify' => $verify_ssl,
					'body'      => array(
						'edd_action' => 'get_version',
						'license'    => $license_key,
						'item_id'    => $this->download_id,
						'version'    => RISHI_COMPANION_VERSION,
						'author'     => __( 'Rishi Companion', 'rishi-companion'),
						'url'        => home_url(),
						'beta'       => false,
					),
				)
			);
			if ( ! is_wp_error( $response ) ) {
				$response = json_decode( wp_remote_retrieve_body( $response ) );

				if ( version_compare( $addon->version, $response->new_version, '<' ) ) {
					$update_cache->response[ $addon->filepath ] = $response;
				}
				$update_cache->last_checked                = time();
				$update_cache->checked[ $addon->filepath ] = $addon->version;

				set_site_transient( 'update_plugins', $update_cache );
			}
		}

		return $response;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function sanitize_license($license) {
		$option = get_option( $this->theme_slug . '_license_key' );

		if ( isset( $_POST['edd_license_activate'] ) && $_POST['edd_license_activate'] == 'Activate License' ) {
			$license[ 'license_key' ]    = $option[ 'license_key' ];
			$license[ 'license_status' ] = isset( $license[ 'license_status' ] ) ? $license[ 'license_status' ] : 'valid';
		}

		if ( isset( $_POST['edd_license_deactivate'] ) && $_POST['edd_license_deactivate'] == 'Deactivate License' ) {

			$old = $option[ 'license_key' ];
			if ( $old && $old != $license[ 'license_key' ] ) {
				$arr[ 'license_status' ] = '';
				$dr_license_new_status   = array_merge_recursive( $option, $arr );

				update_option( $this->theme_slug . '_license_key', $dr_license_new_status );

				$license[ 'license_key' ]    = $option[ 'license_key' ];
				$license[ 'license_status' ] = '';
			}
		}

		if ( isset( $_POST['submit'] ) ) {
			$license[ 'license_key' ]    = isset( $_POST[$this->theme_slug . '_license_key'][ 'license_key' ] ) ? esc_attr( $_POST[$this->theme_slug . '_license_key'][ 'license_key' ] ) : false;
			$license[ 'license_status' ] = isset( $option[ 'license_status' ] ) ? esc_attr( $option[ 'license_status' ] ) : false;
		}
		return $license;
	}

    /**
     * Is license active checks.
     *
     * @return boolean
     */
    public function is_license_active() {
        return true;
    }

	/**
	 * Activate License
	 *
	 * @return void
	 */
	public function activate_license() {
		$license_input = filter_input( INPUT_GET, 'licenseKey' );

		if ( isset( $license_input ) ) {
			$license_details = array();
			$license_details['license_key'] = $license_input;
			update_option( $this->theme_slug . '_license_key', $license_details );
		}

		$license_data = get_option( $this->theme_slug . '_license_key', [] );
		// print_r($license_data);
		$license_key  = isset( $license_data['license_key'] ) ? $license_data['license_key'] : '';

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license_key,
			'item_id'    => $this->download_id, // The ID of the item in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			$this->edd_api_url,
			array(
				'timeout'   => 15,
				'sslverify' => $this->verify_ssl(),
				'body'      => $api_params,
			)
		);


		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			$message = ( is_wp_error( $response ) && ( $response->get_error_message() ) != '' ) ? $response->get_error_message() : __( 'An error occurred, please try again.', 'rishi-companion' );

		} else {

			$license_response_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! $license_response_data->success ) {
				$message = $this->response_messages( $license_response_data->error, $license_response_data );
			}
		}

		if ( isset( $license_response_data->license ) ) {
			$license_data['license_status'] = $license_response_data->license;
			update_option( $this->theme_slug . '_license_key', $license_data );
		}
		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			wp_send_json_error( [ 'message' => $message, 'status' => $this->check_license() ] );
		}

		wp_send_json_success( [ 'license' => $license_key, 'status' => $this->check_license() ] );
		exit();
	}

	public function get_license_status() {
		$license_settings =get_option( $this->theme_slug . '_license_key', array());
		$license_status   = $this->check_license();
		$license_key  = isset( $license_settings['license_key'] ) ? $license_settings['license_key'] : '';
		return wp_send_json_success( [ 'license' => $license_key, 'status' => $license_status ] );
	}


	public function deactivate_license() {

			$license_data = get_option( $this->theme_slug . '_license_key', [] );
			$license_key  = isset( $license_data['license_key'] ) ? $license_data['license_key'] : '';

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license_key,
				'item_id'    => $this->download_id, // The ID of the item in EDD
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$this->edd_api_url,
				array(
					'timeout'   => 15,
					'sslverify' => $this->verify_ssl(),
					'body'      => $api_params,
				)
			);

			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				$message = ( is_wp_error( $response ) && ( $response->get_error_message() ) != '' ) ? $response->get_error_message() : __( 'An error occurred, please try again.', 'rishi-companion' );
			} else {
				$response = json_decode( wp_remote_retrieve_body( $response ) );

			}

			wp_send_json_success( [ 'license' => $license_key, 'status' => $this->check_license() ] );
			exit();

	}

	/**
	 * Get Response messages
	 *
	 * @return void
	 */
	function response_messages( $code, $response= null ) {
		switch ( $code ) {
			case 'expired':
				$message = sprintf(
					/* translators: %1$s is a placeholder for the extension name, %2$s is a placeholder for the expiration date */
					__( 'Your license for %1$s extension expired on %2$s. To ensure you get features and security updates, having an active license is strongly recommended, and in some cases required.', 'rishi-companion' ),
					$response->item_name,
					wp_date( get_option( 'date_format' ), strtotime( $response->expires, current_time( 'timestamp' ) ) )
				);
				break;

			case 'disabled':
			case 'revoked':
				$message = __( 'Your license key has been disabled.', 'rishi-companion' );
				break;

			case 'missing':
				$message = __( 'Invalid license key supplied. Please check if you have entered correct license key.', 'rishi-companion' );
				break;

			case 'invalid':
			case 'site_inactive':
				$message = __( 'Your license is not active for this URL.', 'rishi-companion' );
				break;

			case 'item_name_mismatch':
				$message = sprintf(
					/* translators: %s is a placeholder for the product name */
					__( 'This appears to be an invalid license key for %s.', 'rishi-companion' ),
					EDD_SAMPLE_ITEM_NAME
				);
				break;

			case 'no_activations_left':
				$message = __( 'Your license key has reached its activation limit.', 'rishi-companion' );
				break;

			default:
				$message = __( 'An error occurred, please try again.', 'rishi-companion' );
				break;
		}

		return $message;
	}

    /**
     * Includes
     *
     * @return void
     */
    public function includes() {
        if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			include plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'includes/updater/EDD_SL_Plugin_Updater.php';
		}
    }

    /**
     * Initialize EDD.
     *
     * @return void
     */
    public function initialize_updater() {

        if ( ! current_user_can( 'manage_options' ) ) return;

		$rishi_companion_license = get_option( $this->theme_slug . '_license_key', [] );

		if ( ! isset( $rishi_companion_license['license_key'] ) || empty( $rishi_companion_license['license_key'] ) ) return;

        // setup the updater
		$edd_updater = new \EDD_SL_Plugin_Updater(
			$this->edd_api_url,
			RISHI_COMPANION_PLUGIN_FILE,
			array(
				'version' => RISHI_COMPANION_VERSION, // current version number
				'license' => $rishi_companion_license['license_key'], // license key (used get_option above to retrieve from DB)
				'item_id' => $this->download_id, // ID of the product
				'author'  => __( 'Rishi Companion', 'rishi-companion' ), // author of this plugin
				'beta'    => false,
			)
		);
    }

	/**
	 * Check License Status.
	 *
	 * @return void
	 */
	public function check_license() {
		$verify_ssl      = $this->verify_ssl();
		$license_options = get_option( $this->theme_slug . '_license_key' );

		if ( empty( $license_options ) ) {
			$license_details = array(
				'message' => '',
				'status'  => ''
			);
			return $license_details;
		}

		// Strings
		$strings = array(
			'theme-license'             => __( 'Getting Started', 'rishi-companion' ),
			'enter-key'                 => __( 'Enter your theme license key.', 'rishi-companion' ),
			'license-key'               => __( 'License Key', 'rishi-companion' ),
			'license-action'            => __( 'License Action', 'rishi-companion' ),
			'deactivate-license'        => __( 'Deactivate License', 'rishi-companion' ),
			'activate-license'          => __( 'Activate License', 'rishi-companion' ),
			'status-unknown'            => __( 'License status is unknown.', 'rishi-companion' ),
			'renew'                     => __( 'Renew?', 'rishi-companion' ),
			'unlimited'                 => __( 'unlimited', 'rishi-companion' ),
			'license-key-is-active'     => __( 'License key is active.', 'rishi-companion' ),
			'expires%s'                 => __( 'Expires %s.', 'rishi-companion' ), // Translators: Placeholder %s is for the expiration date.
			'expires-never'             => __( 'Lifetime License.', 'rishi-companion' ), // Translators: Indicates a license that doesn't expire.
			'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'rishi-companion' ), // Translators: Placeholder %1$s is for the number of activated sites, %2$s is for the total number of allowed sites.
			'license-key-expired-%s'    => __( 'License key expired on %s.', 'rishi-companion' ), // Translators: Placeholder %s is for the expiration date.
			'license-key-expired'       => __( 'License key has expired.', 'rishi-companion' ),
			'license-keys-do-not-match' => __( 'License keys do not match.', 'rishi-companion' ),
			'license-is-inactive'       => __( 'License is inactive.', 'rishi-companion' ),
			'license-key-is-disabled'   => __( 'License key is disabled.', 'rishi-companion' ),
			'site-is-inactive'          => __( 'Site is inactive.', 'rishi-companion' ),
			'license-status-unknown'    => __( 'License status is unknown.', 'rishi-companion' ),
			'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'rishi-companion' ),
			'update-available' => __(
				/* Translators: %1$s is a placeholder for the plugin/theme name, %2$s is a placeholder for the version number.
				%3$s is a placeholder for the URL to check what's new, %4$s is a placeholder for the title attribute of the 'Check out what's new' link.
				%5$s is a placeholder for the URL to update, %6$s is a placeholder for additional attributes for the 'update now' link. */
				'<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4$s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.',
				'rishi-companion'
			),
		);

		$license_status = [
			'status' => '',
			'message' => ''
		];

		$response = wp_remote_post(
			$this->edd_api_url,
			array(
				'timeout'   => 15,
				'sslverify' => $verify_ssl,
				'body'      => array(
					'edd_action' => 'check_license',
					'license'    => isset( $license_options['license_key'] ) ? $license_options['license_key'] : '',
					'item_id'    => $this->download_id,
					'version'    => RISHI_COMPANION_VERSION,
					'slug'       => 'rishi-companion',
					'author'  => __( 'Rishi Companion', 'rishi-companion' ), // author of this plugin
					'url'        => home_url(),
					'beta'       => false,
				),
			)
		);
		// make sure the response came back okay
		if ( ! is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// If response doesn't include license data, return
			if ( !isset( $license_data->license ) ) {
				$message = $strings['license-status-unknown'];
				return $message;
			}

			// Get expire date
			$expires = false;
			if ( isset( $license_data->expires ) && 'lifetime' != $license_data->expires ) {
				$expires = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
				$renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
			} elseif ( isset( $license_data->expires ) && 'lifetime' == $license_data->expires ) {
				$expires = 'lifetime';
			}

			// Get site counts
			$site_count = isset( $license_data->site_count ) ? $license_data->site_count : '';
			$license_limit = isset( $license_data->license_limit ) ? $license_data->license_limit : '';

			// If unlimited
			if ( 0 == $license_limit ) {
				$license_limit = $strings['unlimited'];
			}

			if ( $license_data->license == 'valid' ) {
				$message = $strings['license-key-is-active'] . ' ';
				if ( isset( $expires ) && 'lifetime' != $expires ) {
					$message .= sprintf( $strings['expires%s'], $expires ) . ' ';
				}
				if ( isset( $expires ) && 'lifetime' == $expires ) {
					$message .= $strings['expires-never'];
				}
				if ( $site_count && $license_limit ) {
					$message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
				}
			} else if ( $license_data->license == 'expired' ) {
				if ( $expires ) {
					$message = sprintf( $strings['license-key-expired-%s'], $expires );
				} else {
					$message = $strings['license-key-expired'];
				}
				if ( $renew_link ) {
					$message .= ' ' . $renew_link;
				}
			} else if ( $license_data->license == 'invalid' ) {
				$message = $strings['license-keys-do-not-match'];
			} else if ( $license_data->license == 'inactive' ) {
				$message = $strings['license-is-inactive'];
			} else if ( $license_data->license == 'disabled' ) {
				$message = $strings['license-key-is-disabled'];
			} else if ( $license_data->license == 'site_inactive' ) {
				// Site is inactive
				$message = $strings['site-is-inactive'];
			} else {
				$message = $strings['license-status-unknown'];
			}

			$license_status['status']  = $license_data->license;
			$license_status['message'] = $message;
		}

		return $license_status;
	}

	/**
	 * Constructs a renewal link
	 *
	 * @since 1.0.0
	 */
	function get_renewal_link() {

		$license_options = get_option( $this->theme_slug . '_license_key', array() );

		// If download_id was passed in the config, a renewal link can be constructed
		$license_key = isset( $license_options['license_key'] ) ? $license_options['license_key'] : '';
		if ( '' != $this->download_id && $license_key ) {
			$url = esc_url( $this->edd_api_url );
			$url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;
			return $url;
		}

		// Otherwise return the edd_api_url
		return $this->edd_api_url;

	}

	/**
	 * Should verify ssl or not.
	 *
	 * @return bool
	 * @since 5.0.0
	 */
	public function verify_ssl() {
		return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true );
	}

}
