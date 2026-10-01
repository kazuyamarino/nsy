<?php

declare(strict_types=1);

namespace System\Test\Core;

use PHPUnit\Framework\TestCase;
use System\Core\NSY_RouterOptimized;

/**
 * Locks the router's route-parameter handling: it must sanitise control
 * characters but NOT HTML-escape (escaping is a view concern; mutating params
 * corrupts legitimate values).
 */
final class RouterOptimizedTest extends TestCase
{
	public function testValidateParametersStripsControlCharsButKeepsHtml(): void
	{
		$method = new \ReflectionMethod(NSY_RouterOptimized::class, 'validateParameters');
		$method->setAccessible(true);

		$out = $method->invoke(null, [' a&b ', "x\x00y", '<b>z</b>']);

		$this->assertSame(['a&b', 'xy', '<b>z</b>'], $out);
	}
}
