<?php

declare(strict_types=1);

namespace System\Test\Middlewares;

use PHPUnit\Framework\TestCase;
use System\Helpers\RouterHelper;
use System\Middlewares\SecurityMiddleware;

/**
 * Regression tests for the CSRF key/field handling:
 *  - generate and validate must agree on the session key (any key, not only csrf_*)
 *  - the field name produced by csrfField() is the one the validators auto-read
 *  - a failed attempt must not consume a legitimate token (single-use on success)
 */
final class SecurityMiddlewareTest extends TestCase
{
	/** @var string[] Rate-limit keys created by a test (cleaned in tearDown). */
	private array $rateKeys = [];

	protected function setUp(): void
	{
		// PHPUnit has already produced output, so headers_sent() is true and
		// SecurityMiddleware::ensureSession() is a no-op — $_SESSION behaves as a
		// plain superglobal array here, which is exactly what these tests need.
		$_SESSION = [];
		$_POST = [];
		$_GET = [];
	}

	public function testArbitraryKeyRoundTrips(): void
	{
		$token = SecurityMiddleware::generateCSRFToken('login');

		$this->assertTrue(SecurityMiddleware::validateCSRFToken($token, 'login'));
	}

	public function testDefaultKeyRoundTrips(): void
	{
		$token = SecurityMiddleware::generateCSRFToken();

		$this->assertTrue(SecurityMiddleware::validateCSRFToken($token));
	}

	public function testFieldNameDerivation(): void
	{
		$this->assertSame('token', SecurityMiddleware::csrfFieldName('csrf_token'));
		$this->assertSame('_token', SecurityMiddleware::csrfFieldName('_csrf_token'));
		$this->assertSame('login', SecurityMiddleware::csrfFieldName('login'));
		$this->assertStringContainsString('name="token"', SecurityMiddleware::csrfField('csrf_token'));
	}

	public function testAutoReadUsesDerivedFieldName(): void
	{
		$_POST['token'] = SecurityMiddleware::generateCSRFToken('csrf_token');

		$this->assertTrue(RouterHelper::validateCsrf(null, 'csrf_token'));
	}

	public function testFailedAttemptDoesNotConsumeToken(): void
	{
		$token = SecurityMiddleware::generateCSRFToken('csrf_token');

		$this->assertFalse(SecurityMiddleware::validateCSRFToken('bogus', 'csrf_token'));
		$this->assertTrue(SecurityMiddleware::validateCSRFToken($token, 'csrf_token'));
	}

	public function testTokenIsSingleUseOnSuccess(): void
	{
		$token = SecurityMiddleware::generateCSRFToken('csrf_token');

		$this->assertTrue(SecurityMiddleware::validateCSRFToken($token, 'csrf_token'));
		$this->assertFalse(SecurityMiddleware::validateCSRFToken($token, 'csrf_token'));
	}

	public function testAdvancedValidationReadsDerivedField(): void
	{
		$token = SecurityMiddleware::generateCSRFToken('csrf_token');

		$this->assertTrue(SecurityMiddleware::validateAdvancedCSRF('csrf_token', ['token' => $token]));
	}

	public function testGeneratedTokenIsReused(): void
	{
		$first = SecurityMiddleware::generateCSRFToken('csrf_token');
		$second = SecurityMiddleware::generateCSRFToken('csrf_token');

		$this->assertSame($first, $second);
	}

	public function testCsrfFieldAndMetaShareToken(): void
	{
		$field = SecurityMiddleware::csrfField('csrf_token');
		$meta = SecurityMiddleware::csrfMeta('csrf_token');

		preg_match('/value="([^"]+)"/', $field, $f);
		preg_match('/content="([^"]+)"/', $meta, $m);

		$this->assertNotEmpty($f[1] ?? '');
		$this->assertSame($f[1] ?? null, $m[1] ?? null);
	}

	public function testRateLimitBlocksAfterMaxAttempts(): void
	{
		$key = 'test-rate-' . bin2hex(random_bytes(4));
		$this->rateKeys[] = $key;

		$this->assertTrue(SecurityMiddleware::hit($key, 2, 60));
		$this->assertTrue(SecurityMiddleware::hit($key, 2, 60));
		$this->assertFalse(SecurityMiddleware::hit($key, 2, 60));
	}

	public function testInstanceRateLimitUsesConfig(): void
	{
		$middleware = new SecurityMiddleware(['rate_limit' => 1, 'rate_window' => 60]);
		$bucket = 'test-bucket-' . bin2hex(random_bytes(4));
		$this->rateKeys[] = ($_SERVER['REMOTE_ADDR'] ?? 'cli') . ':' . $bucket;

		$this->assertTrue($middleware->rateLimit($bucket));
		$this->assertFalse($middleware->rateLimit($bucket));
	}

	protected function tearDown(): void
	{
		$dir = dirname(__DIR__, 2) . '/Storage/ratelimit';

		foreach ($this->rateKeys as $key) {
			@unlink($dir . '/' . hash('sha256', $key) . '.json');
		}

		$this->rateKeys = [];
	}
}
