<?php

declare(strict_types=1);

namespace System\Seeders;

/**
 * Seeds users_table with 20 fake rows via Factory_Example_User.
 *
 * Run: nsy run:seed Seeder_Example_User
 *
 * See docs/README_SEEDER.md.
 */
class Seeder_Example_User extends Seeder
{
	/**
	 * Insert the seed data.
	 *
	 * Refuses to run in production: this seeder writes dummy data,
	 * which must never land in a live database (e.g. via run:seed all).
	 *
	 * @return void
	 */
	public function run(): void
	{
		if (config_env('APP_ENV') === 'production') {
			echo "Refusing to seed dummy users in production.\n";
			return;
		}

		$this->table('users_table')->insertBatch(factory('Factory_Example_User', 20));
	}
}
