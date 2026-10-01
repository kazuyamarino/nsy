<?php

declare(strict_types=1);

namespace System\Test\Core;

use PHPUnit\Framework\TestCase;
use System\Core\NSY_QueryBuilder;

/**
 * Query Builder SQL generation and injection guards.
 *
 * The builder is instantiated without its constructor on purpose: the
 * constructor opens a PDO connection, and these tests must run without a
 * database (CI has no env.php). Only the pure SQL-building paths are exercised.
 */
final class QueryBuilderSqlTest extends TestCase
{
	private static function qb(): NSY_QueryBuilder
	{
		return (new \ReflectionClass(NSY_QueryBuilder::class))->newInstanceWithoutConstructor();
	}

	public function testWhereBindsValueAndQuotesIdentifier(): void
	{
		$qb = self::qb()->table('users')->where('id', 7);

		$this->assertSame('SELECT * FROM `users` WHERE `id` = ?', $qb->toSql());
		$this->assertSame([7], $qb->getBindings());
	}

	public function testWhereExplicitOperatorAndShorthandChain(): void
	{
		$qb = self::qb()->table('users')->where('age', '>=', 18)->where('status', 'active');

		$this->assertSame('SELECT * FROM `users` WHERE `age` >= ? AND `status` = ?', $qb->toSql());
		$this->assertSame([18, 'active'], $qb->getBindings());
	}

	public function testOrWhereUsesOrPrefix(): void
	{
		$qb = self::qb()->table('users')->where('a', 1)->orWhere('b', 2);

		$this->assertSame('SELECT * FROM `users` WHERE `a` = ? OR `b` = ?', $qb->toSql());
		$this->assertSame([1, 2], $qb->getBindings());
	}

	public function testWhereValueIsBoundNotInterpolated(): void
	{
		$qb = self::qb()->table('users')->where('name', "x' OR '1'='1");

		$this->assertStringNotContainsString("OR '1'='1", $qb->toSql());
		$this->assertSame(["x' OR '1'='1"], $qb->getBindings());
	}

	public function testWhereInBindsEveryValue(): void
	{
		$qb = self::qb()->table('users')->whereIn('id', [1, 2, 3]);

		$this->assertSame('SELECT * FROM `users` WHERE `id` IN (?, ?, ?)', $qb->toSql());
		$this->assertSame([1, 2, 3], $qb->getBindings());
	}

	public function testWhereInWithEmptyArrayIsAlwaysFalse(): void
	{
		$qb = self::qb()->table('users')->whereIn('id', []);

		$this->assertSame('SELECT * FROM `users` WHERE 1=0', $qb->toSql());
		$this->assertSame([], $qb->getBindings());
	}

	public function testEmptyWhereNotInIsIgnored(): void
	{
		$qb = self::qb()->table('users')->whereNotIn('id', []);

		$this->assertSame('SELECT * FROM `users`', $qb->toSql());
		$this->assertSame([], $qb->getBindings());
	}

	public function testMalformedBetweenIsIgnored(): void
	{
		$qb = self::qb()->table('users')->whereBetween('age', [18]);

		$this->assertSame('SELECT * FROM `users`', $qb->toSql());
		$this->assertSame([], $qb->getBindings());
	}

	public function testNullChecks(): void
	{
		$qb = self::qb()->table('users')->whereNull('deleted_at')->whereNotNull('email');

		$this->assertSame('SELECT * FROM `users` WHERE `deleted_at` IS NULL AND `email` IS NOT NULL', $qb->toSql());
		$this->assertSame([], $qb->getBindings());
	}

	public function testJoinQuotesTablesAliasesAndColumns(): void
	{
		$qb = self::qb()->table('users', 'u')->leftJoin('posts', 'u.id', '=', 'posts.user_id', 'p');

		$this->assertSame(
			'SELECT * FROM `users` AS `u` LEFT JOIN `posts` AS `p` ON `u`.`id` = `posts`.`user_id`',
			$qb->toSql()
		);
	}

	public function testSelectArrayQuotesColumnsAndAliases(): void
	{
		$qb = self::qb()->table('users')->select(['id', 'name as full_name'])->distinct();

		$this->assertSame('SELECT DISTINCT `id`, `name` AS `full_name` FROM `users`', $qb->toSql());
	}

	public function testGroupHavingOrder(): void
	{
		$qb = self::qb()->table('orders')
			->groupBy('status')
			->having('cnt', '>', 5)
			->orderBy('status', 'desc')
			->orderBy('id', 'weird');

		$this->assertSame(
			'SELECT * FROM `orders` GROUP BY `status` HAVING `cnt` > ? ORDER BY `status` DESC, `id` ASC',
			$qb->toSql()
		);
		$this->assertSame([5], $qb->getBindings());
	}

	public function testLimitAndOffsetAreCastToInt(): void
	{
		$qb = self::qb()->table('users')->limit('5; DROP TABLE users', '2; --');

		$this->assertSame('SELECT * FROM `users` LIMIT 5 OFFSET 2', $qb->toSql());
		$this->assertStringNotContainsString('DROP', $qb->toSql());
	}

	public function testUpdateWithoutWhereThrows(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		self::qb()->table('users')->update(['status' => 'x']);
	}

	public function testDeleteWithoutWhereThrows(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		self::qb()->table('users')->delete();
	}

	public function testIncrementWithoutWhereThrows(): void
	{
		$this->expectException(\InvalidArgumentException::class);

		self::qb()->table('users')->increment('views');
	}
}
