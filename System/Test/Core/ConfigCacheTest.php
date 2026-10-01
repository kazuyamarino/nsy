<?php

declare(strict_types=1);

namespace System\Test\Core;

use PHPUnit\Framework\TestCase;
use System\Core\NSY_Config;

/**
 * NSY_Config is the per-request config memoizer behind config_app()/
 * config_env()/config_site(). It must load each source at most once, tolerate a
 * missing env.php (CI runs without one) and expose a reset for tests.
 */
final class ConfigCacheTest extends TestCase
{
	protected function tearDown(): void
	{
		NSY_Config::clear();
	}

	public function testKnownSourcesReturnArrays(): void
	{
		$this->assertIsArray(NSY_Config::get('app'));
		$this->assertIsArray(NSY_Config::get('site'));
		$this->assertIsArray(NSY_Config::get('env'));
	}

	public function testUnknownSourceIsEmptyArray(): void
	{
		$this->assertSame([], NSY_Config::get('nope'));
	}

	public function testAppSourceHasCanonicalKeys(): void
	{
		$app = NSY_Config::get('app');

		$this->assertArrayHasKey('app_env', $app);
		$this->assertArrayHasKey('transaction', $app);
		$this->assertArrayHasKey('log', $app);
	}

	public function testMemoizesSourceAndReportsLoaded(): void
	{
		$first = NSY_Config::get('app');
		$second = NSY_Config::get('app');

		$this->assertSame($first, $second);
		$this->assertContains('app', NSY_Config::loaded());
	}

	public function testClearDropsEverything(): void
	{
		NSY_Config::get('app');
		$this->assertNotSame([], NSY_Config::loaded());

		NSY_Config::clear();

		$this->assertSame([], NSY_Config::loaded());
	}

	public function testMissingEnvKeyReturnsNull(): void
	{
		$this->assertNull(config_env('NSY_DEFINITELY_MISSING_KEY'));
	}
}
