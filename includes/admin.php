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

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );
function enqueue_scripts( $hook ) {
	if ( 'comments_page_civil-comments' !== $hook ) {
		return;
	}

	wp_enqueue_style( 'jquery-ui-base', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/base/jquery-ui.css', false, '1.8.24', false );
	wp_enqueue_script( 'cc-timepicker', CIVIL_PLUGIN_URL . '/assets/js/vendor/jquery.timepicker.js', array( 'jquery', 'jquery-ui-slider', 'jquery-ui-datepicker' ), '0.9.7', true );
	wp_enqueue_script( 'civil-comments', CIVIL_PLUGIN_URL . '/assets/js/civil-comments.js', array( 'cc-timepicker' ), CIVIL_VERSION, true );
}
