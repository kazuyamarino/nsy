<?php

declare(strict_types=1);

namespace System\Migrations;

/**
 * The migration class
 */
class mig_tmp_class
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Mig::connect()->createTable('your_table', [
			Mig::bigint('id', 20)->autoIncrement(),
			Mig::varchar('name')->notNull(),
			Mig::text('address')->null(),
			Mig::primary('id')
		])->index('BTREE', 'id');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Mig::connect()->dropExistTable(['your_table']);
	}
}
