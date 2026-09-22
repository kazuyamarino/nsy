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
	 * Run the migrations — create example table + demo data via Query Builder
	 *
	 * @return void
	 */
	public function up()
	{
		// 1. Create table with minimal lines — chainable, quoted identifiers, DRY execDDL
		Mig::connect('primary')->create_table('example_users', [
			Mig::bigint('id', 20)->auto_increment(),
			Mig::varchar('name', 100)->not_null(),
			Mig::varchar('email', 150)->not_null(),
			Mig::varchar('status', 20)->default("'active'"),
			Mig::int('age', 3)->null(),
			Mig::primary('id'),
			Mig::unique('email')
		])->index('BTREE', 'email');

		// 2. Optional: Insert demo data via new powerful Query Builder (1 line per query)
		// Uncomment to test Query Builder together with Migration:
		// qb('example_users')->insert(['name' => 'Ana', 'email' => 'ana@example.com', 'age' => 22]);
		// qb('example_users')->insertBatch([
		//     ['name' => 'Budi', 'email' => 'budi@example.com', 'age' => 25],
		//     ['name' => 'Citra', 'email' => 'citra@example.com', 'age' => 30],
		// ]);

		// 3. Example: Add column later (uncomment to test add_cols)
		// Mig::connect('primary')->add_cols('example_users', [
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
		Mig::connect('primary')->drop_exist_table(['example_users']);

		// Alternative: drop without IF EXISTS (will error if not exists)
		// Mig::connect('primary')->drop_table(['example_users']);
	}
}
