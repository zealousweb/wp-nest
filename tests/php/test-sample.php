<?php
use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase {

	public function test_simple_assertion() {
		$this->assertTrue( true );
		$this->assertEquals( 2, 1 + 1 );
	}

	public function test_mocked_wp_function() {
		// esc_html is mocked in bootstrap.php
		$escaped = esc_html( '<b>bold</b>' );
		$this->assertEquals( '&lt;b&gt;bold&lt;/b&gt;', $escaped );
	}
}
