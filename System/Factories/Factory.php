<?php

declare(strict_types=1);

namespace System\Factories;

use System\Core\NSY_Desk;

/**
 * Base class for model factories.
 *
 * A factory builds arrays of fake attributes for a table. It uses Faker, which
 * is a dev dependency — factories are a development and testing tool.
 *
 *   class UserFactory extends Factory
 *   {
 *       public function definition(): array
 *       {
 *           return [
 *               'name'  => $this->faker->name(),
 *               'email' => $this->faker->unique()->safeEmail(),
 *           ];
 *       }
 *   }
 *
 *   factory('UserFactory');       // one row
 *   factory('UserFactory', 50);   // 50 rows
 *
 * See docs/README_SEEDER.md.
 */
abstract class Factory
{
	/**
	 * Faker generator, available as $this->faker in definition().
	 *
	 * @var \Faker\Generator
	 */
	protected \Faker\Generator $faker;

	public function __construct()
	{
		if (!class_exists(\Faker\Factory::class)) {
			NSY_Desk::staticErrorHandler('Faker is required for factories. Run composer install (dev dependency).', 500);
		}

		$this->faker = \Faker\Factory::create();
	}

	/**
	 * Default attribute set for one row.
	 *
	 * @return array<string,mixed>
	 */
	abstract public function definition(): array;

	/**
	 * Build one row: definition merged with the given overrides.
	 *
	 * @param  array<string,mixed> $overrides
	 * @return array<string,mixed>
	 */
	public function make(array $overrides = []): array
	{
		return array_merge($this->definition(), $overrides);
	}

	/**
	 * Build many rows.
	 *
	 * @param  int                 $count
	 * @param  array<string,mixed> $overrides
	 * @return array<int,array<string,mixed>>
	 */
	public function makeMany(int $count, array $overrides = []): array
	{
		$count = max(0, $count);
		$rows = [];

		for ($i = 0; $i < $count; $i++) {
			$rows[] = $this->make($overrides);
		}

		return $rows;
	}
}
