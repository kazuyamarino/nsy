<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_DB {

	// for change variable of database connection, see System/Core/NSY_Config.php
	// open connection function for mysql/mariadb PDO
	public static function connect_mysql() {
		$DB_DRIVER = config_db('mysql', 'DB_DRIVER');
		$DB_HOST = config_db('mysql', 'DB_HOST');
		$DB_PORT = config_db('mysql', 'DB_PORT');
		$DB_NAME = config_db('mysql', 'DB_NAME');
		$DB_USER = config_db('mysql', 'DB_USER');
		$DB_PASS = config_db('mysql', 'DB_PASS');
		$DB_CHARSET = config_db('mysql', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			$db = new \PDO($dsn, $DB_USER, $DB_PASS);

			// Throw an Exception when an error occurs
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
		}
		return $db;
	}

	// open connection function for dblib sql server PDO
	public static function connect_dblib() {
		$DB_DRIVER = config_db('dblib', 'DB_DRIVER');
		$DB_HOST = config_db('dblib', 'DB_HOST');
		$DB_PORT = config_db('dblib', 'DB_PORT');
		$DB_NAME = config_db('dblib', 'DB_NAME');
		$DB_USER = config_db('dblib', 'DB_USER');
		$DB_PASS = config_db('dblib', 'DB_PASS');
		$DB_CHARSET = config_db('dblib', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			$db = new \PDO($dsn, $DB_USER, $DB_PASS);

			// Throw an Exception when an error occurs
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
		}
		return $db;
	}

	// open connection function for postgresql PDO
	public static function connect_pgsql() {
		$DB_DRIVER = config_db('pgsql', 'DB_DRIVER');
		$DB_HOST = config_db('pgsql', 'DB_HOST');
		$DB_PORT = config_db('pgsql', 'DB_PORT');
		$DB_NAME = config_db('pgsql', 'DB_NAME');
		$DB_USER = config_db('pgsql', 'DB_USER');
		$DB_PASS = config_db('pgsql', 'DB_PASS');
		$DB_CHARSET = config_db('pgsql', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			$db = new \PDO($dsn, $DB_USER, $DB_PASS);

			// Throw an Exception when an error occurs
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
		}
		return $db;
	}

	// open connection function for sql server PDO
	public static function connect_sqlsrv() {
		$DB_DRIVER = config_db('sqlsrv', 'DB_DRIVER');
		$DB_HOST = config_db('sqlsrv', 'DB_HOST');
		$DB_PORT = config_db('sqlsrv', 'DB_PORT');
		$DB_NAME = config_db('sqlsrv', 'DB_NAME');
		$DB_USER = config_db('sqlsrv', 'DB_USER');
		$DB_PASS = config_db('sqlsrv', 'DB_PASS');
		$DB_CHARSET = config_db('sqlsrv', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			$dsn = '' . $DB_DRIVER . ':Server=' . $DB_HOST . ',' . $DB_PORT . ';Database=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			$db = new \PDO($dsn, $DB_USER, $DB_PASS);

			// Throw an Exception when an error occurs
			$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
		}
		return $db;
	}

}