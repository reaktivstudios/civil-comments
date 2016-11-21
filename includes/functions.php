<?php
/**
 * Civil Comments functions
 *
 * @package Civil_Comments
 */

namespace Civil_Comments;

/**
 * Determines whether to show civil comments on a specific post.
 *
 * @param  WP_Post $post A post object.
 * @return boolean
 */
function can_replace( $post ) {
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
function is_enabled() {
	$settings = get_option( 'civil_comments', array() );
	$installed = ! empty( $settings['publication_slug'] )? true : false;
	$enabled = isset( $settings['enable'] ) && '1' === $settings['enable'] ? true : false;
	return apply_filters( 'civil_comments_enabled', $enabled && $installed );
}

/**
 * Generate a UUID.
 *
 * Used for the jti in JWT.
 *
 * @return string
 */
function generate_uuid() {
	return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		mt_rand( 0, 0xffff ),
		mt_rand( 0, 0x0fff ) | 0x4000,
		mt_rand( 0, 0x3fff ) | 0x8000,
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	);
}

/**
 * Get JWT token for SSO.
 *
 * @uses Civil_Comments\JWT
 *
 * @param  WP_User $user WP User object for the user to auth.
 * @param  string  $key  Secret key from Civil Comments.
 * @return string        Signed JWT token.
 */
function get_jwt_token( $user, $key ) {
	include_once CIVIL_PLUGIN_DIR . '/includes/vendor/JWT.php';
	$expires = 86400;

	$payload = array(
		'exp'        => time() + (int) $expires,
		'iat'        => time(),
		'jti'        => civil_generate_uuid(),
		'id'         => $user->ID,
		'name'       => $user->display_name,
		'email'      => $user->user_email,
		'avatar_url' => get_avatar_url( $user ),
	);

	try {
		$token = Civil_Comments\JWT::encode( $payload, $key, 'HS256' );
	} catch ( Exception $e ) {
		return null;
	}

	return $token;
}

add_filter( 'comments_template', __NAMESPACE__ . '\\comments_template' );
/**
 * Load the custom Civil Comments template.
 *
 * @param  string $template Path to a template file.
 * @return string
 */
function comments_template( $template ) {
	global $post;

	if ( empty( $post ) ) {
		return $template;
	}

	if ( ! is_enabled() ) {
		return $template;
	}

	if ( ! can_replace( $post ) ) {
		return $template;
	}

	return CIVIL_PLUGIN_DIR . '/templates/comments.php';
}
