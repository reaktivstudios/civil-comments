<?php
/**
 * Plugin Name:     Civil Comments
 * Plugin URI:      https://www.civilcomments.com/
 * Description:     Replace your comments with Civil Comments
 * Author:          Civil Comments, Josh Eaton, Reaktiv Studios
 * Author URI:      https://www.civilcomments.com/
 * Text Domain:     civil-comments
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Civil_Comments
 */

if ( ! defined( 'CIVIL_PLUGIN_DIR' ) ) {
	define( 'CIVIL_PLUGIN_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'CIVIL_PLUGIN_URL' ) ) {
	define( 'CIVIL_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}

if ( is_admin() ) {
	require_once CIVIL_PLUGIN_DIR . '/includes/admin.php';
}

// @TODO
function civil_can_replace() {
	return true;
}

function civil_is_enabled() {
	$settings = get_option( 'civil_comments', array() );
	$enabled = isset( $settings['enable'] ) && '1' === $settings['enable'] ? true : false;
	return apply_filters( 'civil_comments_enabled', $enabled );
}

// @TODO
function civil_is_installed() {
	return true;
}

add_filter( 'comments_template', 'civil_comments_template' );
function civil_comments_template( $template ) {
	global $post;

	if ( ! ( is_singular() && ( have_comments() || 'open' === $post->comment_status ) ) ) {
		return;
	}

	if ( ! civil_is_enabled() ) {
		return $template;
	}

	if ( ! civil_is_installed() ) {
		return $template;
	}

	if ( ! civil_can_replace() ) {
		return $template;
	}

	// TODO: If a civil-comments.php is found in the current template's
	// path, use that instead of the default bundled comments.php
	// return TEMPLATEPATH . '/civil-comments.php';
	return dirname( __FILE__ ) . '/templates/comments.php';
}
