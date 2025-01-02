<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<div class="softlite-integration-dashboard">
    <br>
    <h1>Softlite.io Integration Settings</h1>
    <br>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'softlite_settings_group' );
            do_settings_sections( 'softlite_settings_group' );

            if ( isset( $_POST['softlite_io_integration_clonewebx_popup_nonce'] ) && wp_verify_nonce( $_POST['softlite_io_integration_clonewebx_popup_nonce'], 'softlite_io_integration_clonewebx_popup' ) ) {
                update_option( 'softlite_io_integration_clonewebx_popup', $_POST['softlite_io_integration_clonewebx_popup'] );
            }
        ?>
        <input type="checkbox" id="softlite_io_integration_clonewebx_popup" name="softlite_io_integration_clonewebx_popup" value="1" <?php checked( get_option( 'softlite_io_integration_clonewebx_popup' ), 1 ); ?> />
        <label for="softlite_io_integration_clonewebx_popup">Enable bottom ClonewebX popup in page builder editor</label>
        <?php wp_nonce_field( 'softlite_io_integration_clonewebx_popup', 'softlite_io_integration_clonewebx_popup_nonce' ); ?>
        <?php
            submit_button();
        ?>
    </form>
</div>
