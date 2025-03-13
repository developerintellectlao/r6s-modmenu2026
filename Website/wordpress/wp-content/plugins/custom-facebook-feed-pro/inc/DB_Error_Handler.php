<?php

namespace CustomFacebookFeed;

use CustomFacebookFeed\Builder\CFF_Db;

/*
 * Custom Facebook Feed DB Error Handler
 *
 * @since 4.2.6
 */
class DB_Error_Handler
{
	/**
	 * Register the hooks.
	 *
	 * @return void
	 */
	public function register_hooks()
	{
		add_action('wp_ajax_sbi_cff_retry_db_creation', [ $this, 'retry_db_creation' ]);
		add_action('cff_error_admin_notice', [ $this, 'display_admin_notice' ], 10, 1);
	}

	/**
	 * SBI Retry DB creating
	 *
	 * @since 4.2.6
	 *
	 * @return void
	 */
	public function retry_db_creation()
	{
		if (! isset($_POST['action']) || sanitize_text_field(wp_unslash($_POST['action'])) !== 'sbi_cff_retry_db_creation') {
			wp_send_json_error([
				'message' => __('Action mismatched!', 'custom-facebook-feed'),
			], 403);
		}

		if (! isset($_POST['cff_nonce']) || ! wp_verify_nonce(sanitize_key(wp_unslash($_POST['cff_nonce'])), 'cff_nonce')) {
			wp_send_json_error([
				'message' => __('Invalid nonce!', 'custom-facebook-feed'),
			], 403);
		}

		if (! current_user_can('manage_options')) {
			wp_send_json_error([
				'message' => __('You do not have the permission to do that!', 'custom-facebook-feed')
			], 403);
		}

		Custom_Facebook_Feed_Pro::instance()->cff_create_database_table(false);
		CFF_Db::create_tables(false);

		global $wpdb;
		$table_name = esc_sql($wpdb->prefix . CFF_POSTS_TABLE);

		if ($wpdb->get_var("show tables like '$table_name'") !== $table_name) {
			wp_send_json_error([ 'message' => '<div style="margin-top: 10px;">' . esc_html__('Unsuccessful. Try visiting our website.', 'custom-facebook-feed') . '</div>' ]);
		}

		wp_send_json_success([ 'message' => '<div style="margin-top: 10px;">' . esc_html__('Success! Try creating a feed and connecting a source.', 'custom-facebook-feed') . '</div>' ]);
	}

	/**
	 * Displays admin notice on DB error.
	 *
	 * @param array $errors
	 *
	 * @return void
	 */
	public function display_admin_notice($errors)
	{
		if (empty($errors['database_create'])) {
			return;
		}

		?>
		<div class="cff-admin-notices cff-critical-error-notice">
			<span class="sb-notice-icon sb-error-icon">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="#D72C2C"/>
				</svg>
			</span>
			<div class="cff-notice-body">
				<h3 class="sb-notice-title">
					<?php echo esc_html__('Facebook Feed was unable to create new database tables.', 'custom-facebook-feed') ; ?>
				</h3>

				<p><?php echo wp_kses_post($errors['database_create']); ?></p><br><br>

				<p><button class="cff-retry-db cff-btn sb-btn-blue"><?php esc_html_e('Retry Database Creation Process', 'custom-facebook-feed'); ?></button></p>
			</div>
		</div>
		<?php
	}
}