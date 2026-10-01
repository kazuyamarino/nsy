<?php

declare(strict_types=1);

namespace System\Factories;

/**
 * Factory for the users_table (database: nsy).
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
	 * @return array<string,mixed>
	 */
	public function definition(): array
	{
		return [
			'user_complete_name' => $this->faker->name(),
			'user_name' => $this->faker->unique()->userName(),
			'user_password' => password_hash('timnas', PASSWORD_DEFAULT),
			'user_session' => null,
			'user_code' => $this->faker->unique()->bothify('USR-####-????'),
			'user_status' => $this->faker->randomElement(['active', 'inactive']),
			'user_email' => $this->faker->unique()->safeEmail(),
			'reset_id' => null,
			'flag_reset' => 0,
			'flag_login' => 0,
			'login_date' => null,
			'logout_date' => null,
			'create_date' => date('Y-m-d H:i:s'),
			'update_date' => date('Y-m-d H:i:s'),
			'delete_date' => null,
		];
	}
}
