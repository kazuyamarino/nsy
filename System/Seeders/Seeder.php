<?php

declare(strict_types=1);

namespace System\Seeders;

use System\Core\NSY_QueryBuilder;

/**
 * Base class for NSY seeders.
 *
 * A seeder is a plain class with a single public run() method. Extending this
 * base is optional — any class in System\Seeders with run() works — but it
 * provides a couple of small helpers so seeders stay short.
 *
 * See docs/README_SEEDER.md.
 */
abstract class Seeder
{
	/**
	 * Insert the seed data.
	 *
	 * @return void
	 */
	abstract public function run(): void;

	/**
	 * Start a fluent query on a table (same builder as the qb() helper).
	 *
	 * @param  string      $table
	 * @param  string|null $alias
	 * @return \System\Core\NSY_QueryBuilder
	 */
	protected function table(string $table, ?string $alias = null): NSY_QueryBuilder
	{
		return (new NSY_QueryBuilder())->table($table, $alias);
	}

	/**
	 * Current timestamp in SQL format (Y-m-d H:i:s).
	 *
	 * @return string
	 */
	protected function now(): string
	{
		return date('Y-m-d H:i:s');
	}
}
