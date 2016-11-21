<?php
/**
 * Civil Comments Tests
 *
 * @package Civil_Comments
 */

/**
 * Civil Comments Test Class.
 */
class CivilCommentsTest extends WP_UnitTestCase {

	public function test_civil_comments_not_returned_when_disabled() {
		$p = $this->factory->post->create( array(
			'post_title' => 'no-comments-post'
		));

		$this->go_to( get_permalink( $p ) );
		$output = get_echo( 'comments_template' );

		// Life in the fast lane.
		$found = preg_match( '/id="civil-comments"/', $output, $matches );

		$this->assertEquals( 0, $found );
	}

	public function test_civil_comments_template_used_when_enabled() {
		$p = $this->factory->post->create( array(
			'post-title' => 'post-with-comments',
			'post_status' => 'publish',
			'comment_status' => 'open',
		) );

		update_option( 'civil_comments', array( 'enable' => '1', 'publication_slug' => 'test' ) );

		$this->go_to( get_permalink( $p ) );
		$output = get_echo( 'comments_template' );

		// Life in the fast lane.
		$found = preg_match( '/id="civil-comments"/', $output, $matches );

		$this->assertEquals( 1, $found );
	}

	public function test_no_comments_on_password_protected_post() {
		$post = $this->factory->post->create_and_get( array(
			'post_password'  => 'password',
			'post-title'     => 'password-post',
			'post_status'    => 'publish',
			'comment_status' => 'open',
		) );

		$this->go_to( get_permalink( $post ) );

		$this->assertFalse( Civil_Comments\can_replace( $post ) );
	}

	public function test_comments_on_password_protected_post_with_cookie() {
		$password = 'password';
		$hasher   = new PasswordHash( 8, true );

		$_COOKIE['wp-postpass_' . COOKIEHASH] = $hasher->HashPassword( $password );

		$post = $this->factory->post->create_and_get( array(
			'post_password'  => 'password',
			'post-title'     => 'password-post',
			'post_status'    => 'publish',
			'comment_status' => 'open',
		) );

		$this->go_to( get_permalink( $post ) );

		$this->assertTrue( Civil_Comments\can_replace( $post ) );

		unset( $_COOKIE['wp-postpass_' . COOKIEHASH] );
	}
}
