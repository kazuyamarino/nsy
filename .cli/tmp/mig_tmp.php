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
		Mig::connect()->create_table('mig_tmp', function () {
			return Mig::cols([
				Mig::bigint('id', 20)->auto_increment(),
				# add columns here...
				Mig::primary('id')
			]);
		});
		Mig::connect()->index('mig_tmp', 'BTREE', 'id');
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
