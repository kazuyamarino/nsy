<?php

namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_DB {

	// for change variable of database connection, see System/Core\NSY_Config.php
	// open connection function for PDO mysqli
	public static function mysqlDB() {
		static $db = null;
		if ($db === null) {
			$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
			$db = new \PDO($dsn, DB_USER, DB_PASS);

			// Throw an Exception when an error occurs
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
		}
		return $db;
	}

}
