<?php

declare(strict_types=1);

namespace System\Test\Config;

use PHPUnit\Framework\TestCase;
use System\Core\NSY_Config;

/**
 * Site.php must always expose a non-empty version/codename so the <title>,
 * footer and badges never render blank when APP_VERSION/APP_CODENAME is unset.
 */
final class SiteConfigTest extends TestCase
{
	protected function setUp(): void
	{
		NSY_Config::clear();
	}

	public function testVersionIsNeverEmpty(): void
	{
		$this->assertNotSame('', config_site('version'));
	}

	public function testCodenameIsNeverEmpty(): void
	{
		$this->assertNotSame('', config_site('codename'));
	}
}
