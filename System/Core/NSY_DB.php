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
	* @param  string $conn_name
	* @return void
	*/
	public static function connect_mysql($conn_name)
	{
		$cdb = config_db();
		foreach( $cdb as $val ) {
			if ( $conn_name == $val['CONNECTION_NAME'] ) {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';
				$DB_CHARSET = config_db($conn_name, 'DB_CHARSET');

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
			} else {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';
				$DB_CHARSET = config_db($conn_name, 'DB_CHARSET');
				
				static $db_alter = null;
				if ($db_alter === null) {
					if (is_filled($DB_PORT)) {
						$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
					} else {
						$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
					}
					$db_alter = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
				}
				
				return $db_alter;
			}
		}
	}

	/**
	* Open connection function for dblib sql server PDO
	*
	* @param  string $conn_name
	* @return void
	*/
	public static function connect_dblib($conn_name)
	{
		$cdb = config_db();
		foreach( $cdb as $val ) {
			if ( $conn_name == $val['CONNECTION_NAME'] ) {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';
				$DB_CHARSET = config_db($conn_name, 'DB_CHARSET');

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
			} else {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';
				$DB_CHARSET = config_db($conn_name, 'DB_CHARSET');

				static $db_alter = null;
				if ($db_alter === null) {
					if (is_filled($DB_PORT)) {
						$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
					} else {
						$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';charset=' . $DB_CHARSET . '';
					}
					$db_alter = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
				}
				return $db_alter;
			}
		}
	}

	/**
	* Open connection function for postgresql PDO
	*
	* @param  string $conn_name
	* @return void
	*/
	public static function connect_pgsql($conn_name)
	{
		$cdb = config_db();
		foreach( $cdb as $val ) {
			if ( $conn_name == $val['CONNECTION_NAME'] ) {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';

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
			} else {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';

				static $db_alter = null;
				if ($db_alter === null) {
					if (is_filled($DB_PORT)) {
						$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';port=' . $DB_PORT . ';dbname=' . $DB_NAME . ';';
					} else {
						$dsn = '' . $DB_DRIVER . ':host=' . $DB_HOST . ';dbname=' . $DB_NAME . ';';
					}
					$db_alter = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
				}
				return $db_alter;
			}
		}
	}

	/**
	* Open connection function for sql server PDO
	*
	* @param  string $conn_name
	* @return void
	*/
	public static function connect_sqlsrv($conn_name)
	{
		$cdb = config_db();
		foreach( $cdb as $val ) {
			if ( $conn_name == $val['CONNECTION_NAME'] ) {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';

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
			} else {
				$OPTIONS = config_db($conn_name, 'DB_ATTR');
				$DB_DRIVER = config_db($conn_name, 'DB_CONNECTION');
				$DB_HOST = config_db($conn_name, 'DB_HOST');
				$DB_PORT = config_db($conn_name, 'DB_PORT');
				$DB_NAME = config_db($conn_name, 'DB_NAME');
				$DB_USER = config_db($conn_name, 'DB_USER') ?? '';
				$DB_PASS = config_db($conn_name, 'DB_PASS') ?? '';

				static $db_alter = null;
				if ($db_alter === null) {
					if (is_filled($DB_PORT)) {
						$dsn = '' . $DB_DRIVER . ':Server=' . $DB_HOST . ',' . $DB_PORT . ';Database=' . $DB_NAME . '';
					} else {
						$dsn = '' . $DB_DRIVER . ':Server=' . $DB_HOST . ';Database=' . $DB_NAME . '';
					}
					$db_alter = new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
				}
				return $db_alter;
			}
		}
	}

}
