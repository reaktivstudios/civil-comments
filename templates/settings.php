<?php
/**
 * Settings page template
 *
 * @package Civil_Comments
 */

if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

if ( isset( $_GET['settings-updated'] ) ) { // Input var okay.
	add_settings_error( 'civil_comments_messages', 'civil_comments_message', __( 'Settings Saved' ), 'updated' );
}

settings_errors( 'civil_comments_messages' );
?>
<div class="wrap">
	<a href="https://app.civilcomments.com/" style="float:right; margin-right: 0.6em; margin-top:0.2em;">
		<img src="<?php echo esc_url( CIVIL_PLUGIN_URL . '/assets/img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Civil Comments Logo' ); ?>" height="40" width="75">
	</a>
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		settings_fields( 'civil_comments' );
		$settings = get_option( 'civil_comments', array() );
		// @codingStandardsIgnoreStart
		var_dump( $settings );
		// @codingStandardsIgnoreEnd

		$enable = isset( $settings['enable'] ) ? $settings['enable'] : false;
		$publication_slug = isset( $settings['publication_slug'] ) ? $settings['publication_slug'] : '';
		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<h2><?php esc_html_e( 'General' ); ?></h2>
				</th>
			</tr>
			<tr>
				<th scope="row">
					<label for="civil_comments[enable]"><?php esc_html_e( 'Enable Civil Comments' ); ?></label>
				</th>
				<td>
					<input type="checkbox" name="civil_comments[enable]" id="civil_comments[enable]" value="1" <?php checked( '1', $enable ); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="civil_comments[publication_slug]">
						<?php esc_html_e( 'Publication Slug' ); ?>
					</label>
				</th>
				<td>
					<input type="text" id="civil_comments[publication_slug]" name="civil_comments[publication_slug]" class="regular-text" value="<?php echo esc_attr( $publication_slug ); ?>">
					<p class="description"><?php esc_html_e( 'The unique ID for your site from Civil Comments.' ); ?></p>
				</td>
			</tr>
		</table>

		<?php submit_button( __( 'Save Settings' ) ); ?>
	</form>
</div>
