<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Validate;

/**
 * Type coercion / sanitation library used by Request and form handling.
 * Every invalid input must fall back to the caller-provided default instead of
 * throwing or leaking the raw value.
 */
final class ValidateTest extends TestCase
{
	public function testAsIntegerAcceptsIntegersAndRejectsJunk(): void
	{
		$this->assertSame(12, Validate::asInteger('12'));
		$this->assertSame(-3, Validate::asInteger(-3));
		$this->assertSame(0, Validate::asInteger('0'));
		$this->assertNull(Validate::asInteger('abc'));
		$this->assertSame(5, Validate::asInteger('abc', 5));
		$this->assertSame(5, Validate::asInteger('5.9', 5));
	}

	public function testAsFloat(): void
	{
		$this->assertSame(1.5, Validate::asFloat('1.5'));
		$this->assertNull(Validate::asFloat('abc'));
		$this->assertSame(1.0, Validate::asFloat('abc', 1.0));
	}

	public function testAsBoolean(): void
	{
		$this->assertTrue(Validate::asBoolean('yes'));
		$this->assertTrue(Validate::asBoolean('1'));
		$this->assertFalse(Validate::asBoolean('0'));
		$this->assertFalse(Validate::asBoolean('false'));
		$this->assertSame('D', Validate::asBoolean('nope', 'D'));
	}

	public function testAsStringStripsTagsButKeepsQuotes(): void
	{
		$this->assertSame('hi', Validate::asString('<b>hi</b>'));
		$this->assertSame("a'b", Validate::asString("a'b"));
		$this->assertSame('D', Validate::asString(['x'], 'D'));
	}

	public function testAsArray(): void
	{
		$this->assertSame(['a' => 'x'], Validate::asArray('{"a":"x"}'));
		$this->assertNull(Validate::asArray('5'));
		$this->assertSame('D', Validate::asArray('[]', 'D'));
	}

	public function testAsObject(): void
	{
		$object = Validate::asObject('{"a":1}');

		$this->assertIsObject($object);
		$this->assertSame(1, $object->a);
		$this->assertNull(Validate::asObject('5'));
	}

	public function testAsJson(): void
	{
		$this->assertSame('{"a":1}', Validate::asJson(['a' => 1]));
	}

	public function testAsIp(): void
	{
		$this->assertSame('1.2.3.4', Validate::asIp('1.2.3.4'));
		$this->assertSame('D', Validate::asIp('999.1.1.1', 'D'));
	}

	public function testAsUrlSanitizes(): void
	{
		$this->assertSame('http://a/bc', Validate::asUrl('http://a/b c'));
		$this->assertSame('D', Validate::asUrl('', 'D'));
	}

	public function testAsEmail(): void
	{
		$this->assertSame('a@b.com', Validate::asEmail('a@b.com'));
		$this->assertSame('D', Validate::asEmail('nope', 'D'));
	}
}
