<?php
/**
 * Class SampleTest
 *
 * @package Civil_Comments
 */

/**
 * Sample test case.
 */
class CivilCommentsTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
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

	/**
	 * A single example test.
	 */
	public function test_civil_comments_template_used_when_enabled() {
		$p = $this->factory()->post->create( array(
			'post-title' => 'post-with-comments',
			'post_status' => 'publish',
			'comment_status' => 'open',
		) );

		update_option( 'civil_comments', array( 'enable' => '1', 'publication_slug' => 'test' ) );

		$this->go_to( get_permalink( $p ) );
		$this->assertTrue( civil_is_enabled() );
		$this->assertQueryTrue( 'is_single', 'is_singular' );
		$this->assertTrue( civil_can_replace( get_post( $p ) ) );
		$output = get_echo( 'comments_template' );

		// Life in the fast lane.
		$found = preg_match( '/id="civil-comments"/', $output, $matches );

		$this->assertEquals( 1, $found );
	}
}
