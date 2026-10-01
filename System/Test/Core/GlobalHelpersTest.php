<?php

declare(strict_types=1);

namespace System\Test\Core;

use PHPUnit\Framework\TestCase;

/**
 * Regression tests for the URL/redirect hardening in NSY_Helpers_Global:
 * same-origin checks (open-redirect guard) and Host-header validation.
 */
final class GlobalHelpersTest extends TestCase
{
	public function testRelativeAndSameOriginUrlsAreAllowed(): void
	{
		$_SERVER['HTTP_HOST'] = 'localhost';

		$this->assertTrue(nsy_is_same_origin('/foo/bar'));
		$this->assertTrue(nsy_is_same_origin('foo?x=1'));
		$this->assertTrue(nsy_is_same_origin('http://localhost/app/x'));
	}

	public function testForeignAndSchemeAbusiveUrlsAreRejected(): void
	{
		$_SERVER['HTTP_HOST'] = 'localhost';

		$this->assertFalse(nsy_is_same_origin('http://evil.example/x'));
		$this->assertFalse(nsy_is_same_origin('//evil.example/x'));
		$this->assertFalse(nsy_is_same_origin('javascript:alert(1)'));
	}

	public function testSafeRedirectFallsBackToBaseUrlForForeignTarget(): void
	{
		$_SERVER['HTTP_HOST'] = 'localhost';

		$safe = nsy_safe_redirect_url('http://evil.example/x');

		$this->assertStringStartsWith('http://localhost/', $safe);
		$this->assertStringNotContainsString('evil', $safe);
	}

	public function testBaseUrlIgnoresForgedHostHeader(): void
	{
		// A Host header containing a path/illegal characters must not be used.
		$_SERVER['HTTP_HOST'] = 'evil.example/path';

		$this->assertStringStartsWith('http://localhost/', base_url('x'));
		$this->assertStringNotContainsString('evil', base_url('x'));
	}
}
