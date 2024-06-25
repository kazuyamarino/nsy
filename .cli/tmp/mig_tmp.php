<?php

namespace System\Migrations;

/**
 * The migration class
 */
class mig_tmp_class
{

	/**
	 * NSY Migration
	 */

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Mig::connect()->create_table('mig_tmp', [
			Mig::bigint('id', 20)->auto_increment(),
			Mig::varchar('name')->not_null(),
			Mig::text('address')->null(),
			Mig::primary('id')
		])->index('mig_tmp', 'BTREE', 'id');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Mig::connect()->drop_exist_table('mig_tmp');
	}
}
