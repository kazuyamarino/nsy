<?php

namespace System\Migrations;

/**
 * Example User Testing Migration for NSY Framework
 * - Demonstrates powerful, minimal-lines Migration (DRY: quoteIdent/execDDL)
 * - Uses unified NSY_DB::connect() (mysql/pgsql/sqlsrv/dblib)
 * - Run via CLI: nsy run:migrate all  OR  nsy run:migrate list
 * - Run via URL (development only): /migup=Migration_Example_User  /migdown=Migration_Example_User
 *
 * Copy this file as template: cp System/Migrations/Migration_Example_User.php System/Migrations/My_New_Migration.php
 * Then edit class name to match filename.
 */
class Migration_Example_User
{
	/**
	 * Run the migrations — create example table
	 *
	 * @return void
	 */
	public function up()
	{
		// 1. Create table with minimal lines — chainable, quoted identifiers, DRY execDDL
		Mig::connect('primary')->createTable('example_users', [
			Mig::bigint('id', 20)->autoIncrement(),
			Mig::varchar('name', 100)->notNull(),
			Mig::varchar('email', 150)->notNull(),
			Mig::varchar('status', 20)->default("'active'"),
			Mig::int('age', 3)->null(),
			Mig::primary('id'),
			Mig::unique('email')
		])->index('BTREE', 'email');

		// 2. Demo data lives in seeders, not migrations — see
		// System/Seeders/Seeder_Example_User.php + System/Factories/Factory_Example_User.php.
		// Run: nsy run:seed Seeder_Example_User

		// 3. Example: Add column later (uncomment to test addCols)
		// Mig::connect('primary')->addCols('example_users', [
		//     Mig::text('bio')->null()
		// ]);

		// 4. Example: Query Builder minimal lines — filtering, pagination, pluck
		// $active = qb('example_users')->where('status', 'active')->whereNull('deleted_at')->get();
		// $names = qb('example_users')->whereIn('id', [1,2,3])->pluck('name');
		// $page = qb('example_users')->whereLike('name', '%a%')->paginate(10);
	}

	/**
	 * Reverse the migrations — clean up for testing
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop table if exists (safe for re-run)
		Mig::connect('primary')->dropExistTable(['example_users']);

		// Alternative: drop without IF EXISTS (will error if not exists)
		// Mig::connect('primary')->dropTable(['example_users']);
	}
}
