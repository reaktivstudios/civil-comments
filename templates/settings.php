<?php
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

if ( isset( $_GET['settings-updated'] ) ) {
	add_settings_error( 'civil_comments_messages', 'civil_comments_message', __( 'Settings Saved' ), 'updated' );
} ?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
</div>
