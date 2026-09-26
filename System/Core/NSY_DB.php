<?php

declare(strict_types=1);

namespace System\Core;

use System\Libraries\Log\LogManager;

/**
 * This is the core of NSY Database Connection
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * For change variable of database connection, see env.php via config_db()
 */
class NSY_DB
{
	/**
	 * Unified connection factory — single source for all drivers (powerful)
	 *
	 * @param string $conn_name
	 * @return \PDO|null
	 */
	public static function connect(string $conn_name = 'primary'): ?\PDO
	{
		$driver = config_db($conn_name, 'DB_CONNECTION');
		return match ($driver) {
			'mysql' => self::createConnection($conn_name),
			'dblib' => self::createConnection($conn_name),
			'pgsql' => self::createConnection($conn_name),
			'sqlsrv' => self::createSqlsrvConnection($conn_name),
			default => self::handleUnknownDriver($driver),
		};
	}

	/**
	 * Open connection function for mysql/mariadb PDO (BC proxy)
	 *
	 * @param string $conn_name
	 * @return \PDO|null
	 */
	public static function connect_mysql(string $conn_name = 'primary'): ?\PDO
	{
		return self::createConnection($conn_name);
	}

	/**
	 * Open connection function for dblib sql server PDO (BC proxy)
	 *
	 * @param string $conn_name
	 * @return \PDO|null
	 */
	public static function connect_dblib(string $conn_name = 'primary'): ?\PDO
	{
		return self::createConnection($conn_name);
	}

	/**
	 * Open connection function for postgresql PDO (BC proxy)
	 *
	 * @param string $conn_name
	 * @return \PDO|null
	 */
	public static function connect_pgsql(string $conn_name = 'primary'): ?\PDO
	{
		return self::createConnection($conn_name);
	}

	/**
	 * Open connection function for sql server PDO (BC proxy)
	 *
	 * @param string $conn_name
	 * @return \PDO|null
	 */
	public static function connect_sqlsrv(string $conn_name = 'primary'): ?\PDO
	{
		return self::createSqlsrvConnection($conn_name);
	}

	/**
	 * Create PDO connection for mysql/dblib/pgsql
	 *
	 * @param string $conn_name
	 * @return \PDO|null
	 */
	private static function createConnection(string $conn_name): ?\PDO
	{
		$cfg = self::fetchConfig($conn_name);
		self::validateConfig($cfg);

		$dsn = self::buildDsn($cfg);
		$options = self::normalizeOptions($cfg['options']);
		$started = microtime(true);

		try {
			$pdo = new \PDO($dsn, $cfg['user'], $cfg['pass'], $options);
			self::logConnection($conn_name, $cfg, $started, true);

			return $pdo;
		} catch (\PDOException $e) {
			self::logConnection($conn_name, $cfg, $started, false, $e->getMessage());
			NSY_Desk::static_error_handler("Connection failed: " . $e->getMessage(), 500);
			return null;
		}
	}

	/**
	 * Create SQLSRV PDO connection
	 *
	 * @param string $conn_name
	 * @return \PDO|null
	 */
	private static function createSqlsrvConnection(string $conn_name): ?\PDO
	{
		$cfg = self::fetchConfig($conn_name);
		self::validateConfig($cfg);

		$dsn = self::buildSqlsrvDsn($cfg);
		$options = self::normalizeOptions($cfg['options']);
		$started = microtime(true);

		try {
			$pdo = new \PDO($dsn, $cfg['user'], $cfg['pass'], $options);
			self::logConnection($conn_name, $cfg, $started, true);

			return $pdo;
		} catch (\PDOException $e) {
			self::logConnection($conn_name, $cfg, $started, false, $e->getMessage());
			NSY_Desk::static_error_handler("Connection failed: " . $e->getMessage(), 500);
			return null;
		}
	}

	/**
	 * Log a connection attempt — driver/host/port/dbname only, never credentials.
	 *
	 * @param array<string,mixed> $cfg
	 */
	private static function logConnection(string $conn_name, array $cfg, float $started, bool $ok, string $error = ''): void
	{
		try {
			$context = [
				'conn' => $conn_name,
				'driver' => $cfg['driver'] ?? '',
				'host' => $cfg['host'] ?? '',
				'port' => $cfg['port'] ?? '',
				'dbname' => $cfg['name'] ?? '',
				'duration_ms' => round((microtime(true) - $started) * 1000, 2),
			];

			if ($ok) {
				LogManager::channel('db')->debug('Database connected', $context);
				return;
			}

			$context['error'] = $error;
			LogManager::channel('db')->error('Database connection failed', $context);
		} catch (\Throwable $e) {
			// never interfere with connection handling
		}
	}

	/**
	 * Fetch and normalize config for a connection
	 */
	private static function fetchConfig(string $conn_name): array
	{
		return [
			'driver'  => config_db($conn_name, 'DB_CONNECTION'),
			'host'    => config_db($conn_name, 'DB_HOST'),
			'port'    => config_db($conn_name, 'DB_PORT'),
			'name'    => config_db($conn_name, 'DB_NAME'),
			'user'    => config_db($conn_name, 'DB_USER') ?? '',
			'pass'    => config_db($conn_name, 'DB_PASS') ?? '',
			'charset' => config_db($conn_name, 'DB_CHARSET'),
			'options' => config_db($conn_name, 'DB_ATTR'),
		];
	}

	/**
	 * Validate required configuration
	 */
	private static function validateConfig(array $cfg): void
	{
		if (!is_filled($cfg['driver']) || !is_filled($cfg['host']) || !is_filled($cfg['name'])) {
			NSY_Desk::static_error_handler('Database configuration missing required values: DB_DRIVER, DB_HOST, or DB_NAME', 500);
		}
	}

	/**
	 * Build DSN for mysql/dblib/pgsql
	 */
	private static function buildDsn(array $cfg): string
	{
		$dsn = "{$cfg['driver']}:host={$cfg['host']}";
		if (is_filled($cfg['port'])) {
			$dsn .= ";port={$cfg['port']}";
		}
		$dsn .= ";dbname={$cfg['name']}";
		if (is_filled($cfg['charset'])) {
			$dsn .= ";charset={$cfg['charset']}";
		}
		return $dsn;
	}

	/**
	 * Build DSN for sqlsrv
	 */
	private static function buildSqlsrvDsn(array $cfg): string
	{
		$dsn = "{$cfg['driver']}:Server={$cfg['host']}";
		if (is_filled($cfg['port'])) {
			$dsn .= ",{$cfg['port']}";
		}
		$dsn .= ";Database={$cfg['name']}";
		return $dsn;
	}

	/**
	 * Normalize PDO options with secure defaults
	 */
	private static function normalizeOptions(mixed $options): ?array
	{
		$defaults = [
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
			\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
			\PDO::ATTR_EMULATE_PREPARES => false,
		];
		if (!is_array($options) || empty($options)) {
			return $defaults;
		}
		// User options override defaults
		return $options + $defaults;
	}

	/**
	 * Handle unknown driver
	 */
	private static function handleUnknownDriver(mixed $driver): ?\PDO
	{
		$var_msg = "Default database connection not found or undefined, please configure it in <strong>.env</strong> file <strong><i>DB_CONNECTION</i></strong> (got: " . htmlspecialchars((string)$driver, ENT_QUOTES, 'UTF-8') . ")";
		NSY_Desk::static_error_handler($var_msg);
		return null;
	}
}
