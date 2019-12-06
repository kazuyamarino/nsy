<?php
namespace Migrations;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Model;

class mig_tmp extends NSY_Model
{

	/**
	 * NSY Migration
	 */

	// Commit migration
	public function up()
 	{
 		$q = "CREATE TABLE my_table (
				create_date datetime NOT NULL,
				update_date datetime NOT NULL,
				additional_date datetime NOT NULL
 	  	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
 		$this->connect()->query($q)->exec();
 	}

	// Rollback migration
	public function down()
 	{
		$q = "DROP TABLE my_table";
		$this->connect()->query($q)->exec();
	}

}
