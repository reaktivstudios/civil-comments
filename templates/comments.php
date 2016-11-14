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
	</script>
</div><!-- .comments-area -->
