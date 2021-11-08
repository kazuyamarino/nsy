<?php
namespace System\Core;

/**
* This is the core of NSY Database Connection
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*
* For change variable of database connection, see System/Core/NSY_Config.php
*/
class NSY_DB
{

	/**
	* Open connection function for mysql/mariadb PDO
	*
	* @return void
	*/
	public static function connect_mysql()
	{
		$OPTIONS = config_db_attr('pdo', 'ATTR');
		$DB_DRIVER = config_db('mysql', 'DB_DRIVER');
		$DB_HOST = config_db('mysql', 'DB_HOST');
		$DB_PORT = config_db('mysql', 'DB_PORT');
		$DB_NAME = config_db('mysql', 'DB_NAME');
		$DB_USER = config_db('mysql', 'DB_USER');
		$DB_PASS = config_db('mysql', 'DB_PASS');
		$DB_CHARSET = config_db('mysql', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			} else {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

	/**
	* Open second connection function for mysql/mariadb PDO
	*
	* @return void
	*/
	public static function connect_mysql_sec()
	{
		$OPTIONS = config_db_attr_sec('pdo', 'ATTR');
		$DB_DRIVER = config_db_sec('mysql', 'DB_DRIVER');
		$DB_HOST = config_db_sec('mysql', 'DB_HOST');
		$DB_PORT = config_db_sec('mysql', 'DB_PORT');
		$DB_NAME = config_db_sec('mysql', 'DB_NAME');
		$DB_USER = config_db_sec('mysql', 'DB_USER');
		$DB_PASS = config_db_sec('mysql', 'DB_PASS');
		$DB_CHARSET = config_db_sec('mysql', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			} else {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

	/**
	* Open connection function for dblib sql server PDO
	*
	* @return void
	*/
	public static function connect_dblib()
	{
		$OPTIONS = config_db_attr('pdo', 'ATTR');
		$DB_DRIVER = config_db('dblib', 'DB_DRIVER');
		$DB_HOST = config_db('dblib', 'DB_HOST');
		$DB_PORT = config_db('dblib', 'DB_PORT');
		$DB_NAME = config_db('dblib', 'DB_NAME');
		$DB_USER = config_db('dblib', 'DB_USER');
		$DB_PASS = config_db('dblib', 'DB_PASS');
		$DB_CHARSET = config_db('dblib', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			} else {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

	/**
	* Open second connection function for dblib sql server PDO
	*
	* @return void
	*/
	public static function connect_dblib_sec()
	{
		$OPTIONS = config_db_attr_sec('pdo', 'ATTR');
		$DB_DRIVER = config_db_sec('dblib', 'DB_DRIVER');
		$DB_HOST = config_db_sec('dblib', 'DB_HOST');
		$DB_PORT = config_db_sec('dblib', 'DB_PORT');
		$DB_NAME = config_db_sec('dblib', 'DB_NAME');
		$DB_USER = config_db_sec('dblib', 'DB_USER');
		$DB_PASS = config_db_sec('dblib', 'DB_PASS');
		$DB_CHARSET = config_db_sec('dblib', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			} else {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

	/**
	* Open connection function for postgresql PDO
	*
	* @return void
	*/
	public static function connect_pgsql()
	{
		$OPTIONS = config_db_attr('pdo', 'ATTR');
		$DB_DRIVER = config_db('pgsql', 'DB_DRIVER');
		$DB_HOST = config_db('pgsql', 'DB_HOST');
		$DB_PORT = config_db('pgsql', 'DB_PORT');
		$DB_NAME = config_db('pgsql', 'DB_NAME');
		$DB_USER = config_db('pgsql', 'DB_USER');
		$DB_PASS = config_db('pgsql', 'DB_PASS');
		$DB_CHARSET = config_db('pgsql', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';';
			} else {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

	/**
	* Open second connection function for postgresql PDO
	*
	* @return void
	*/
	public static function connect_pgsql_sec()
	{
		$OPTIONS = config_db_attr_sec('pdo', 'ATTR');
		$DB_DRIVER = config_db_sec('pgsql', 'DB_DRIVER');
		$DB_HOST = config_db_sec('pgsql', 'DB_HOST');
		$DB_PORT = config_db_sec('pgsql', 'DB_PORT');
		$DB_NAME = config_db_sec('pgsql', 'DB_NAME');
		$DB_USER = config_db_sec('pgsql', 'DB_USER');
		$DB_PASS = config_db_sec('pgsql', 'DB_PASS');
		$DB_CHARSET = config_db_sec('pgsql', 'DB_CHARSET');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';';
			} else {
				$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

	/**
	* Open connection function for sql server PDO
	*
	* @return void
	*/
	public static function connect_sqlsrv()
	{
		$OPTIONS = config_db_attr('pdo', 'ATTR');
		$DB_DRIVER = config_db('sqlsrv', 'DB_DRIVER');
		$DB_HOST = config_db('sqlsrv', 'DB_HOST');
		$DB_PORT = config_db('sqlsrv', 'DB_PORT');
		$DB_NAME = config_db('sqlsrv', 'DB_NAME');
		$DB_USER = config_db('sqlsrv', 'DB_USER');
		$DB_PASS = config_db('sqlsrv', 'DB_PASS');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':Server=' . $DB_HOST . ',' . $DB_PORT . ';Database=' . $DB_NAME . '';
			} else {
				$dsn = '' . $DB_DRIVER . ':Server=' . $DB_HOST . ';Database=' . $DB_NAME . '';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

	/**
	* Open second connection function for sql server PDO
	*
	* @return void
	*/
	public static function connect_sqlsrv_sec()
	{
		$OPTIONS = config_db_attr_sec('pdo', 'ATTR');
		$DB_DRIVER = config_db_sec('sqlsrv', 'DB_DRIVER');
		$DB_HOST = config_db_sec('sqlsrv', 'DB_HOST');
		$DB_PORT = config_db_sec('sqlsrv', 'DB_PORT');
		$DB_NAME = config_db_sec('sqlsrv', 'DB_NAME');
		$DB_USER = config_db_sec('sqlsrv', 'DB_USER');
		$DB_PASS = config_db_sec('sqlsrv', 'DB_PASS');

		static $db = null;
		if ($db === null) {
			if (is_filled($DB_PORT)) {
				$dsn = '' . $DB_DRIVER . ':Server=' . $DB_HOST . ',' . $DB_PORT . ';Database=' . $DB_NAME . '';
			} else {
				$dsn = '' . $DB_DRIVER . ':Server=' . $DB_HOST . ';Database=' . $DB_NAME . '';
			}
			$db = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		}
		return $db;
	}

}
