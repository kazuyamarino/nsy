<?php

declare(strict_types=1);

namespace System\Test\Core;

use PHPUnit\Framework\TestCase;
use System\Core\NSY_Migration;
use System\Core\NSY_QueryBuilder;

/**
 * Regression tests for the SQL hardening: operator/direction allow-lists in the
 * Query Builder and quoted/allow-listed index identifiers in the migration
 * builder. These helpers are pure, so no database is required.
 */
final class SqlHardeningTest extends TestCase
{
	private static function call(string $class, string $method, mixed ...$args): mixed
	{
		$ref = new \ReflectionMethod($class, $method);
		$ref->setAccessible(true);

		return $ref->invoke(null, ...$args);
	}

	public function testOperatorAllowListAcceptsKnownOperators(): void
	{
		$this->assertSame('=', self::call(NSY_QueryBuilder::class, 'normalizeOperator', '='));
		$this->assertSame('>=', self::call(NSY_QueryBuilder::class, 'normalizeOperator', '>='));
		$this->assertSame('NOT LIKE', self::call(NSY_QueryBuilder::class, 'normalizeOperator', ' not   like '));
	}

	public function testOperatorAllowListRejectsInjection(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		self::call(NSY_QueryBuilder::class, 'normalizeOperator', '= 1; DROP TABLE users');
	}

	public function testDirectionClampsToAscDesc(): void
	{
		$this->assertSame('DESC', self::call(NSY_QueryBuilder::class, 'normalizeDirection', 'desc'));
		$this->assertSame('ASC', self::call(NSY_QueryBuilder::class, 'normalizeDirection', 'ASC; DROP TABLE x'));
		$this->assertSame('ASC', self::call(NSY_QueryBuilder::class, 'normalizeDirection', 'weird'));
	}

	public function testIndexTypeIsAllowListed(): void
	{
		$this->assertSame('BTREE', self::call(NSY_Migration::class, 'normalizeIndexType', 'btree'));
		$this->assertSame('GIN', self::call(NSY_Migration::class, 'normalizeIndexType', 'GIN'));
		$this->assertSame('BTREE', self::call(NSY_Migration::class, 'normalizeIndexType', 'BTREE; DROP TABLE x'));
	}

	public function testIndexColumnsAreQuoted(): void
	{
		$this->assertSame('`email`', self::call(NSY_Migration::class, 'quoteIndexColumns', 'email'));
		$this->assertSame('`a`, `b`', self::call(NSY_Migration::class, 'quoteIndexColumns', ['a', 'b']));
		$this->assertSame('`a`, `b`', self::call(NSY_Migration::class, 'quoteIndexColumns', 'a, b'));
	}
}
