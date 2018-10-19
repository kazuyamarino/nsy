<?php

namespace System\Core;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_DB {

	// variable for database connection, see Core\NSY_Config.php
	private $dbHost = DB_HOST;
	private $dbUser = DB_USER;
	private $dbPass = DB_PASS;
	private $dbName = DB_NAME;

	// open connection function for PDO mysqli
	public static function mysqlDB() {
		static $db = null;
		if ($db === null) {
			$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
			$db = new \PDO($dsn, DB_USER, DB_PASS);

			// Throw an Exception when an error occurs
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		return $db;
	}

}
