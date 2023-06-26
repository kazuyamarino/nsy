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
	 * @return \PDO
	 */
	public static function connect_mysql($conn_name)
	{
		return self::createConnection($conn_name, 'DB_ATTR', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET');
	}

	/**
	 * Open connection function for dblib sql server PDO
	 *
	 * @param  string $conn_name
	 * @return \PDO
	 */
	public static function connect_dblib($conn_name)
	{
		return self::createConnection($conn_name, 'DB_ATTR', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET');
	}

	/**
	 * Open connection function for postgresql PDO
	 *
	 * @param  string $conn_name
	 * @return \PDO
	 */
	public static function connect_pgsql($conn_name)
	{
		return self::createConnection($conn_name, 'DB_ATTR', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET');
	}

	/**
	 * Open connection function for sql server PDO
	 *
	 * @param  string $conn_name
	 * @return \PDO
	 */
	public static function connect_sqlsrv($conn_name)
	{
		return self::createSqlrvConnection($conn_name, 'DB_ATTR', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS');
	}

	/**
	 * Create PDO connection
	 *
	 * @param string $conn_name
	 * @param string $attr_key
	 * @param string $driver_key
	 * @param string $host_key
	 * @param string $port_key
	 * @param string $dbname_key
	 * @param string $user_key
	 * @param string $pass_key
	 * @param string $charset_key
	 * @return \PDO
	 */
	private static function createConnection($conn_name, $attr_key, $driver_key, $host_key, $port_key, $dbname_key, $user_key, $pass_key, $charset_key = '')
	{
		$OPTIONS = config_db($conn_name, $attr_key);
		$DB_DRIVER = config_db($conn_name, $driver_key);
		$DB_HOST = config_db($conn_name, $host_key);
		$DB_PORT = config_db($conn_name, $port_key);
		$DB_NAME = config_db($conn_name, $dbname_key);
		$DB_USER = config_db($conn_name, $user_key) ?? '';
		$DB_PASS = config_db($conn_name, $pass_key) ?? '';
		$DB_CHARSET = config_db($conn_name, $charset_key);

		try {
			// Create a new PDO instance
			if (is_filled($DB_PORT)) {
				if (is_filled($DB_CHARSET)) {
					$dsn = "{$DB_DRIVER}:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset={$DB_CHARSET}";
				} else {
					$dsn = "{$DB_DRIVER}:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME}";
				}
			} else {
				if (is_filled($DB_CHARSET)) {
					$dsn = "{$DB_DRIVER}:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";
				} else {
					$dsn = "{$DB_DRIVER}:host={$DB_HOST};dbname={$DB_NAME}";
				}
			}

			// Perform database operations using the $pdo object
			return new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		} catch (PDOException $e) {
			// Handle any errors that occur during the connection
			echo "Connection failed: " . $e->getMessage();
		}
	}

	/**
	 * Create SQLSRV PDO connection
	 *
	 * @param string $conn_name
	 * @param string $attr_key
	 * @param string $driver_key
	 * @param string $host_key
	 * @param string $port_key
	 * @param string $dbname_key
	 * @param string $user_key
	 * @param string $pass_key
	 * @param string $charset_key
	 * @return \PDO
	 */
	private static function createSqlrvConnection($conn_name, $attr_key, $driver_key, $host_key, $port_key, $dbname_key, $user_key, $pass_key)
	{
		$OPTIONS = config_db($conn_name, $attr_key);
		$DB_DRIVER = config_db($conn_name, $driver_key);
		$DB_HOST = config_db($conn_name, $host_key);
		$DB_PORT = config_db($conn_name, $port_key);
		$DB_NAME = config_db($conn_name, $dbname_key);
		$DB_USER = config_db($conn_name, $user_key) ?? '';
		$DB_PASS = config_db($conn_name, $pass_key) ?? '';

		try {
			// Create a new PDO instance
			if (is_filled($DB_PORT)) {
				$dsn = "{$DB_DRIVER}:Server={$DB_HOST},{$DB_PORT};Database={$DB_NAME}";
			} else {
				$dsn = "{$DB_DRIVER}:Server={$DB_HOST};Database={$DB_NAME}";
			}

			// Perform database operations using the $pdo object
			return new \PDO($dsn, $DB_USER, $DB_PASS, $OPTIONS);
		} catch (PDOException $e) {
			// Handle any errors that occur during the connection
			echo "Connection failed: " . $e->getMessage();
		}
	}
}
