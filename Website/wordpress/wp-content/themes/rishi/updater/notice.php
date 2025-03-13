<?php 
/**
 * Rishi Admin Notices
 *
 * @package Rishi
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( ! class_exists( 'Rishi_Admin_Notices' ) ) :

    /**
    * Rishi Admin Notices
    */
    class Rishi_Admin_Notices {
        /**
		 * Setup class.
		 *
		 * @since 1.0.0
		 */
        public function __construct() {

			add_action( 'admin_notices', array( $this, 'output_theme_activation_notice'), 5 );
			add_action( 'admin_notices', array( $this, 'rishi_companion_activation_notice'), 6 );
			add_action( 'admin_init', array( $this, 'rishi_update_admin_notice_for_companion') );
			
			add_action('admin_enqueue_scripts', function(){
				wp_enqueue_script(
					'rishi-activation-script',
					get_template_directory_uri() . '/updater/notice.js',
					array(),
					'2.0.7',
					true
				);
			});
        }

		/**
		 * Outputs the admin notice for the theme activator.
		 *
		 * @return void
		 */
		function output_theme_activation_notice() {
		
			if (! current_user_can('manage_options') ) return;

			$license_status = get_option( 'rishi_new_license_key_status', 'site_inactive' );

			$admin_redirect_url = admin_url('themes.php?page=rishi-dashboard');

			if ( $license_status === 'valid' ) return;
			$activationnonce = wp_create_nonce( 'rishi-theme-activate-license' );
			$meta = get_option( 'rishi_admin_activation_notice' );
			if( !$meta ){
				?>
				<div class="notice notice-info notice-rishi-theme-activation">
					<div class="notice-rishi-theme-activation-root" style="padding:10px;" data-nonce="<?php echo esc_attr( $activationnonce ); ?>" data-link="<?php esc_url( $admin_redirect_url ); ?>">
						<strong>
							<p>
								<?php echo esc_html__('To receive the automatic notifications of the new version in your dashboard and to enjoy latest features of Rishi Theme, activate this feature.', 'rishi'); ?>
								<a class="rishi-activation-link" href="?rishi_admin_activation_notice=1&_wpnonce=<?php echo esc_attr( $activationnonce ); ?>""><?php esc_html_e( 'Activate', 'rishi' ); ?></a>
							</p>
						</strong>
					</div>
				</div>
				<?php
			}
		}

		/**
		 * Outputs the admin notice for the theme activator.
		 *
		 * @return void
		 */
		function rishi_companion_activation_notice() {

			if (! current_user_can('manage_options') ) return;
			$dismissnonce = wp_create_nonce( 'rishi_companion_activation_admin_notice' );
			$meta         = get_option( 'rishi_companion_activation_admin_notice' );

			if( !class_exists( 'Rishi_Companion\Plugin' ) && !$meta ){
				?>
				<div class="notice notice-info notice-rishi-theme-activation" style="padding:10px;display: flex;justify-content: space-between;">
					<p>
						<?php 
							$plugin_name = 'Rishi Companion';
							printf(
								esc_html__( 'We strongly recommend you to activate %1$s plugin to get access to features like extensions, demo starter templates and many other essential features.', 'rishi' ),
								'<strong>' . esc_html($plugin_name) . '</strong>'
							);
						?>
						<strong><a class="rishi-companion-activation-link" target="_blank" href="<?php echo esc_url('https://rishitheme.com/rishi-companion/'); ?>""><?php esc_html_e( 'Download Rishi Companion', 'rishi' ); ?></a></strong>
					</p>
					<p class="dismiss-link"><strong><a href="?rishi_companion_activation_admin_notice=1&_wpnonce=<?php echo esc_attr( $dismissnonce ); ?>""><?php esc_html_e( 'Dismiss', 'rishi' ); ?></a></strong></p>
				</div>
				<?php
			}
		}

		/**
		 * Updating admin notice on dismiss
		*/
		function rishi_update_admin_notice_for_companion(){

			if ( !current_user_can('manage_options')) {
				return;
			}

			// Bail if the nonce doesn't check out
			if ( ( isset( $_GET['rishi_companion_activation_admin_notice'] ) && $_GET['rishi_companion_activation_admin_notice'] = '1') && wp_verify_nonce( $_GET['_wpnonce'], 'rishi_companion_activation_admin_notice' ) ) {
				update_option( 'rishi_companion_activation_admin_notice', true );
			}
		}
    }

endif;

return new Rishi_Admin_Notices();
