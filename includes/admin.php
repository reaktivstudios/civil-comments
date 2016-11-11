<?php
/**
 * Admin Settings page
 *
 * @package Civil_Comments
 */

namespace Civil_Comments;

add_action( 'admin_init', __NAMESPACE__ . '\\register_settings' );
function register_settings() {
	register_setting( 'civil_comments', 'civil_comments' );
}

add_action( 'admin_menu', __NAMESPACE__ . '\\add_settings_page' );
function add_settings_page() {
	add_submenu_page(
		'edit-comments.php',
		__( 'Civil Comments' ),
		__( 'Civil Comments' ),
		'moderate_comments', // @TODO: Add cap filter.
		'civil-comments',
		__NAMESPACE__ . '\\render_settings_page'
	);
}

function render_settings_page() {
	require_once CIVIL_PLUGIN_DIR . '/templates/settings.php';
}
