<?php

declare(strict_types=1);

namespace System\Test\Config;

use PHPUnit\Framework\TestCase;
use System\Core\NSY_Config;

/**
 * Locks the stringly-typed boolean contract in System/Config/App.php.
 *
 * App.php normalises env values with filter_var() into canonical strings that
 * DB.php / NSY_Migration compare with === ('true'/'false', 'on'/'off'). A real
 * boolean in env.php must never reach those comparisons raw.
 */
final class AppConfigTest extends TestCase
{
	protected function setUp(): void
	{
		// Re-read config from disk so a mutation in another test cannot leak in.
		NSY_Config::clear();
	}

	public function testCsrfTokenIsCanonicalString(): void
	{
		$this->assertContains(config_app('csrf_token'), ['true', 'false'], 'csrf_token must be a canonical string');
	}

	public function testTransactionIsCanonicalString(): void
	{
		$this->assertContains(config_app('transaction'), ['on', 'off'], 'transaction must be a canonical string');
	}

	public function testMaintenanceIsCanonicalString(): void
	{
		$this->assertContains(config_app('maintenance'), ['true', 'false'], 'maintenance must be a canonical string');
	}
}
