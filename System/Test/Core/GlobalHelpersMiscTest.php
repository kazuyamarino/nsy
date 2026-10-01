<?php

declare(strict_types=1);

namespace System\Test\Core;

use PHPUnit\Framework\TestCase;

/**
 * Small, widely used global helpers that had no coverage: truthiness checks,
 * number abbreviation and array utilities.
 */
final class GlobalHelpersMiscTest extends TestCase
{
	public function testNotFilledMatchesEmptyAndWhitespace(): void
	{
		$this->assertTrue(not_filled(''));
		$this->assertTrue(not_filled('   '));
		$this->assertTrue(not_filled(null));
		$this->assertTrue(not_filled([]));
		$this->assertTrue(not_filled(0));
		$this->assertFalse(not_filled('0'));
		$this->assertFalse(not_filled('x'));
		$this->assertFalse(not_filled(['a']));
	}

	public function testIsFilledIsInverseOfNotFilled(): void
	{
		$this->assertTrue(is_filled('x'));
		$this->assertFalse(is_filled(''));
	}

	public function testTerner(): void
	{
		$this->assertSame('a', terner(true, 'a', 'b'));
		$this->assertSame('b', terner(false, 'a', 'b'));
	}

	public function testNumberFormatShort(): void
	{
		$this->assertSame('850', number_format_short(850, 0));
		$this->assertSame('1 Rb', number_format_short(999, 0)); // 900+ rounds into the thousands band
		$this->assertSame('1 Rb', number_format_short(1000));
		$this->assertSame('1.5 Rb', number_format_short(1500));
		$this->assertSame('0.9 Jt', number_format_short(900000));
		$this->assertSame('1 M', number_format_short(1000000000));
		$this->assertSame('0', number_format_short(-1));
	}

	public function testArrayFlatten(): void
	{
		$this->assertSame([1, 2, 3, 4, 5], array_flatten([1, [2, [3, 4]], 5]));
		$this->assertSame(['x'], array_flatten('x'));
	}

	public function testSequenceBuildsNamedPlaceholders(): void
	{
		$this->assertSame(
			['id0,id1', ['id0' => 'a', 'id1' => 'b']],
			sequence('id', ['a', 'b'])
		);
	}
}
