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

if ( ! defined( 'CC_PLUGIN_DIR' ) ) {
	define( 'CC_PLUGIN_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'CC_PLUGIN_URL' ) ) {
	define( 'CC_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}

if ( is_admin() ) {
	require_once CC_PLUGIN_DIR . '/includes/admin.php';
}

require_once CC_PLUGIN_DIR . '/includes/admin.php';
