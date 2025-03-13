<?php
/**
 * Users Meta
 *
 * @package Rishi
 */

namespace Rishi_Companion;

/**
 * Users Meta Class.
 */
class Users_Meta {

	/**
	 * Constructor.
	 */
	public function __construct() {

		/** Hooks to add extra field in profile */
		add_action( 'show_user_profile', array( $this, 'rishi_companion_user_fields' ) ); // editing your own profile.
		add_action( 'edit_user_profile', array( $this, 'rishi_companion_user_fields' ) ); // editing another user.
		add_action( 'user_new_form', array( $this, 'rishi_companion_user_fields' ) ); // creating a new user.

		/** Hook to Save Extra User Fields */
		add_action( 'personal_options_update', array( $this, 'rishi_companion_save_user_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'rishi_companion_save_user_fields' ) );
		add_action( 'user_register', array( $this, 'rishi_companion_save_user_fields' ) );
		add_action( 'rishi_authors_social', array( $this, 'rishi_companion_author_social' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'rishi_companion_admin_scripts' ) );
	}

	/**
	 * User Profile Extra Fields
	 *
	 * @param WP_User $user User object.
	 */
	public function rishi_companion_user_fields( $user ) {

		wp_nonce_field( basename( __FILE__ ), 'rishi_companion_user_fields_nonce' );

		if ( is_string( $user ) === true ) {
			$user = new \stdClass(); // create a new user object.
			$id   = -9999;
			unset( $user );
		} else {
			$id = $user->ID;
		}

		$defaults     = apply_filters(
			'rishi_companion_user_social_icons',
			array(
				'facebook'  => '',
				'twitter'   => '',
				'instagram' => '',
				'snapchat'  => '',
				'pinterest' => '',
				'linkedin'  => '',
				'tiktok'    => '',
				'medium'    => '',
				'youtube'   => '',
			)
		);
		$social_icons = get_user_meta( $id, '_rishi_companion_user_social_icons', true );

		$social_icons = $social_icons ? $social_icons : $defaults;

		echo '<h3>';
		esc_html_e( 'User Social Link', 'rishi-companion' );
		echo '</h3>';

		echo '<ul class="user-social-sortable-icons">';
		foreach ( $social_icons as $k => $v ) {
			echo '<li>';
			echo '<label for="' . esc_attr( $k ) . '">';
			/* translators: %s: label name */
			printf( esc_html__( '%s :', 'rishi-companion' ), esc_html( ucfirst( $k ) ) );
			echo '</label>';
			echo '<input type="text" name="rishi_companion_user_social_icons[' . esc_attr( $k ) . ']" id="' . esc_attr( $k ) . '" value="' . ( isset( $v ) ? esc_attr( $v ) : '' ) . '" class="regular-text" /><br />';
			echo '<span class="description">';
			printf(
				// Translators: %s represents a placeholder for a specific URL field (e.g., Facebook, Twitter).
				esc_html__( 'Please enter your %s Url.', 'rishi-companion' ),
				esc_html( ucfirst( $k ) )
			);
			echo '</span>';
			echo '</li>';
		}
		echo '</ul>';
	}


	/**
	 * Saving Extra User Profile Information
	 *
	 * @param int $user_id User ID.
	 */
	public function rishi_companion_save_user_fields( $user_id ) {
		$socials = array();

		// Check if our nonce is set.
		if ( ! isset( $_POST['rishi_companion_user_fields_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		$nonce = isset( $_POST['rishi_companion_user_fields_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['rishi_companion_user_fields_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( wp_unslash( $nonce ), basename( __FILE__ ) ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		
		if ( isset( $_POST['rishi_companion_user_social_icons'] ) ) {
			foreach ( $_POST['rishi_companion_user_social_icons'] as $key => $links ) {
				$socials[ $key ] = esc_url_raw( $links );
			}
			update_user_meta( $user_id, '_rishi_companion_user_social_icons', $socials );
		}
	}


	/**
	 * Enqueue scripts for admin
	 *
	 * @param string $hook Admin page hook.
	 */
	public function rishi_companion_admin_scripts( $hook ) {

		if ( 'profile.php' === $hook || 'user-edit.php' === $hook || 'user-new.php' === $hook ) {
			$dependencies_file_path = plugin_dir_path( RISHI_COMPANION_PLUGIN_FILE ) . 'build/adminUserMeta.asset.php';
			$js_dependencies        = ( ! empty( $dependencies_file_path['dependencies'] ) ) ? $dependencies_file_path['dependencies'] : array();
			$version                = ( ! empty( $dependencies_file_path['version'] ) ) ? $dependencies_file_path['version'] : '';

			wp_enqueue_style(
				'rishi-companion-users-admin',
				esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/adminUserMeta.css',
				array(),
				$version
			);

			// Enqueue wp default jquery-ui-sortable js.
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script(
				'rishi-companion-users-admin',
				esc_url( plugin_dir_url( RISHI_COMPANION_PLUGIN_FILE ) ) . 'build/adminUserMeta.js',
				$js_dependencies,
				$version,
				true
			);
		}
	}


	/**
	 * Get key from the users meta
	 *
	 * @param string $key Key to get from the user meta.
	 * @return string SVG icon.
	 */
	public function get_users_meta_from_key( $key ) {
		if ( ! $key ) {
			return;
		}

		$icons = '';

		$html_array = \Rishi\Customizer\Helpers\Defaults::lists_all_svgs( ( $key ) );
		if ( isset( $html_array['icon'] ) && ! empty( $html_array['icon'] ) ) {
			$icons = $html_array['icon'];
		}

		return $icons;
	}

	/**
	 * Display author social links
	 */
	public function rishi_companion_author_social() {
		$id      = get_the_author_meta( 'ID' );
		$socials = get_user_meta( $id, '_rishi_companion_user_social_icons', true );

		if ( $socials ) {
			echo '<div class="rishi-social-box rishi-color-type-official">';
			foreach ( $socials as $key => $social ) {

				$link = isset( $social ) ? esc_url( $social ) : '';
				if( $link ) { ?>
                    <a class="rishi-<?php echo esc_attr($key); ?>" href="<?php echo $link ?>" target="_blank" rel="nofollow" aria-label="<?php echo esc_html( $key ); ?>">
                        <span class="rishi-icon-container">
                            <?php echo  $this->get_users_meta_from_key( $key ); ?>
                            </span>
                        <span class="hidden"><?php echo esc_html( $social ); ?></span>
                    </a>
                <?php 
				}
			}
			echo '</div>';
		}
	}

}
new Users_Meta();
