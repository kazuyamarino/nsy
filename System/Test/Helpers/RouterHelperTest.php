<?php

declare(strict_types=1);

namespace System\Test\Helpers;

use PHPUnit\Framework\TestCase;
use System\Helpers\RouterHelper;

/**
 * Locks the method validation in RouterHelper::route().
 *
 * Only the single-path HTTP verbs are accepted; an unknown verb used to become
 * a fatal dynamic static call (NSY_RouterOptimized::$method(...)).
 */
final class RouterHelperTest extends TestCase
{
	public function testRouteRejectsUnsupportedMethod(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		RouterHelper::route('foo', '/x', [\stdClass::class, 'method']);
	}

	public function testRouteRejectsMapMethod(): void
	{
		// map() takes $methods first — it must not be routed through route().
		$this->expectException(\InvalidArgumentException::class);
		RouterHelper::route('map', '/x', [\stdClass::class, 'method']);
	}
}
