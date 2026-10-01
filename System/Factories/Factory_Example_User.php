<?php

declare(strict_types=1);

namespace System\Factories;

/**
 * Factory for the `example_users` table (created by Migration_Example_User).
 *
 * Generates fake user rows via Faker. Development/testing tool —
 * not meant for production reference data.
 *
 * Usage: factory('Factory_Example_User', 20)
 *
 * See docs/README_SEEDER.md.
 */
class Factory_Example_User extends Factory
{
	/**
	 * Default attribute set for one row.
	 *
	 * `id` and the create/update/delete timestamps are left to the database
	 * (auto-increment and CURRENT_TIMESTAMP defaults).
	 *
	 * @return array<string,mixed>
	 */
	public function definition(): array
	{
		return [
			'name'   => $this->faker->name(),
			'email'  => $this->faker->unique()->safeEmail(),
			'status' => $this->faker->randomElement(['active', 'inactive']),
			'age'    => $this->faker->numberBetween(18, 65),
		];
	}
}
