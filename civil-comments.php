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

if ( ! defined( 'CIVIL_VERSION' ) ) {
	define( 'CIVIL_VERSION', '0.1.0' );
}

if ( is_admin() ) {
	require_once CIVIL_PLUGIN_DIR . '/includes/admin.php';
}

/**
 * Determines whether to show civil comments on a specific post.
 *
 * @param  WP_Post $post A post object.
 * @return boolean
 */
function civil_can_replace( $post ) {
	$replace = true;

	if ( ! ( is_singular() && 'open' === $post->comment_status ) ) {
		$replace = false;
	}

	// Only show on publish or private posts.
	if ( ! in_array( $post->post_status, array( 'publish', 'private' ), true ) ) {
		$replace = false;
	}

	$settings = get_option( 'civil_comments', array() );
	$start_date = ! empty( $settings['start_date'] ) ? $settings['start_date'] : '';

	// Only show on posts past the start date.
	if ( ! empty( $start_date )
		&& mysql2date( 'U', $post->post_date ) < strtotime( $start_date ) ) {
		$replace = false;
	}

	return apply_filters( 'civil_can_replace', $replace, $post );
}

/**
 * Determines whether Civil Comments is enabled and has a publication slug.
 *
 * @return boolean
 */
function civil_is_enabled() {
	$settings = get_option( 'civil_comments', array() );
	$installed = ! empty( $settings['publication_slug'] )? true : false;
	$enabled = isset( $settings['enable'] ) && '1' === $settings['enable'] ? true : false;
	return apply_filters( 'civil_comments_enabled', $enabled && $installed );
}

add_filter( 'comments_template', 'civil_comments_template' );
/**
 * Load the custom Civil Comments template.
 *
 * @param  string $template Path to a template file.
 * @return string
 */
function civil_comments_template( $template ) {
	global $post;

	if ( empty( $post ) ) {
		return $template;
	}

	if ( ! civil_is_enabled() ) {
		return $template;
	}

	if ( ! civil_can_replace( $post ) ) {
		return $template;
	}

	return dirname( __FILE__ ) . '/templates/comments.php';
}
