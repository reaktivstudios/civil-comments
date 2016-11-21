<?php
/**
 * Civil Comments Template
 *
 * @package Civil_Comments
 */

global $post;
$settings = get_option( 'civil_comments', array() );
$publication_slug = isset( $settings['publication_slug'] ) ? $settings['publication_slug'] : '';
$lang = isset( $settings['lang'] ) ? $settings['lang'] : 'en_US';
$enable_sso = isset( $settings['enable_sso'] ) ? (bool) $settings['enable_sso'] : false;
$sso_secret = isset( $settings['sso_secret'] ) ? $settings['sso_secret'] : false;
$current_user = null;

// Attempt SSO if enabled, configured and we're logged in.
if ( $enable_sso && ! empty( $sso_secret ) && is_user_logged_in() ) {
	$token = Civil_Comments\get_jwt_token( wp_get_current_user(), $sso_secret );
	$current_user = array(
		'token' => $token,
	);
}
?>
<div id="comments" class="comments-area">
	<div id="civil-comments"></div>
	<script>
	(function(c, o, mm, e, n, t, s){
		c[n] = c[n] || function() {
			var args = [].slice.call(arguments);
			(c[n].q = c[n].q || []).push(args);
			if (c[n].r) return; t = o.createElement(mm); s = o.getElementsByTagName(mm)[0];
			t.async = 1; t.src = [e].concat(args.map(encodeURIComponent)).join("/");
			s.parentNode.insertBefore(t, s); c[n].r = 1;};
		c["CivilCommentsObject"] = c[n];
	})(window, document, "script", "https://ssr.civilcomments.com/v1", "Civil");

	Civil(<?php echo wp_json_encode( $post->ID ); ?>, <?php echo wp_json_encode( $publication_slug ); ?>, <?php echo wp_json_encode( $lang ); ?>);

	function civilWpGetCurrentUser() {
		return <?php echo wp_json_encode( $current_user ); ?>
	}

	Civil({
		provider: 'jwt',
		getUser: civilWpGetCurrentUser,
		login: function() {
			window.location = <?php echo wp_json_encode( wp_login_url( get_permalink() ) ); ?>;
		},
		logout: function() {
			<?php // @see: https://core.trac.wordpress.org/ticket/34352 ?>
			window.location = <?php echo wp_json_encode( html_entity_decode( wp_logout_url( get_permalink() ) ) ); ?>;
		}
	});
	</script>
</div><!-- .comments-area -->
