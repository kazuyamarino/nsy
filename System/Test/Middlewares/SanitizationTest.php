<?php

declare(strict_types=1);

namespace System\Test\Middlewares;

use PHPUnit\Framework\TestCase;
use System\Middlewares\SecurityMiddleware;

/**
 * Regression tests for the sanitisation/XSS hardening:
 *  - sanitizeInput() normalises (trim + strip control chars) without escaping
 *  - cleanXSS() removes script markup
 *  - allowed_tags keeps the tags but never their scriptable attributes
 */
final class SanitizationTest extends TestCase
{
	public function testSanitizeInputDoesNotEscapeOrStripSlashes(): void
	{
		$this->assertSame('a\\b', SecurityMiddleware::sanitizeInput('  a\\b  '));
		$this->assertSame('<b>x</b>', SecurityMiddleware::sanitizeInput('<b>x</b>'));
	}

	public function testSanitizeInputStripsControlChars(): void
	{
		$this->assertSame('ax', SecurityMiddleware::sanitizeInput("a\x00x"));
	}

	public function testSanitizeInputRejectsNonScalar(): void
	{
		$this->assertSame('', SecurityMiddleware::sanitizeInput(['a']));
	}

	public function testCleanXssRemovesScriptTags(): void
	{
		$out = (string) SecurityMiddleware::cleanXSS('<script>alert(1)</script>Hello');

		$this->assertStringNotContainsStringIgnoringCase('<script', $out);
	}

	public function testAllowedTagsDoNotPreserveJavascriptAttributes(): void
	{
		$out = (string) SecurityMiddleware::validateAndSanitize(
			'<a href="javascript:alert(1)">x</a><script>bad()</script>',
			['allowed_tags' => '<a>', 'html_escape' => true, 'strip_slashes' => false]
		);

		$this->assertStringNotContainsStringIgnoringCase('javascript:', $out);
		$this->assertStringNotContainsStringIgnoringCase('<script', $out);
	}
}
