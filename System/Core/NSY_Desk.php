<?php

declare(strict_types=1);

namespace System\Core;

use System\Libraries\Log\LogManager;

/**
 * This is the core of NSY Desk Settings
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 */
class NSY_Desk
{

	/**
	 * Optimized error handler with better formatting and logging
	 *
	 * @param  string $var_msg
	 * @param  int $error_code
	 * @return void
	 */
	public static function static_error_handler(string $var_msg = '', int $error_code = 500): never
	{
		$app_env = config_app('app_env');

		// Structured logging (never throws; inert when logging is disabled).
		try {
			$origin = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? [];
			LogManager::channel('error')->error(strip_tags($var_msg), [
				'code' => $error_code,
				'file' => $origin['file'] ?? '',
				'line' => $origin['line'] ?? 0,
			]);
		} catch (\Throwable $e) {
			// ignore — error_log() below always runs
		}

		// Log error in all environments (strip HTML for log)
		error_log('NSY Error: ' . strip_tags($var_msg));

		if ($app_env === 'development') {
			$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
			$caller = $trace[1] ?? ['file' => 'unknown', 'line' => 0];
			$function = isset($trace[2]) ? ($trace[2]['class'] ?? '') . ($trace[2]['type'] ?? '') . ($trace[2]['function'] ?? '') . '()' : 'unknown';

			// Escape message but keep allowed formatting tags (<mark><strong><i><em><b><code>)
			$escaped = htmlspecialchars($var_msg, ENT_QUOTES, 'UTF-8');
			$allowed = ['&lt;mark&gt;' => '<mark>', '&lt;/mark&gt;' => '</mark>', '&lt;strong&gt;' => '<strong>', '&lt;/strong&gt;' => '</strong>', '&lt;i&gt;' => '<i>', '&lt;/i&gt;' => '</i>', '&lt;em&gt;' => '<em>', '&lt;/em&gt;' => '</em>', '&lt;b&gt;' => '<b>', '&lt;/b&gt;' => '</b>', '&lt;code&gt;' => '<code>', '&lt;/code&gt;' => '</code>'];
			$safeMsg = strtr($escaped, $allowed);

			echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px; font-family: monospace;'>";
			echo "<h4>🚨 NSY Framework Error</h4>";
			echo "<strong>Message:</strong> " . $safeMsg . "<br>";
			echo "<strong>File:</strong> " . htmlspecialchars((string) ($caller['file'] ?? 'unknown'), ENT_QUOTES, 'UTF-8') . " (Line: " . (int) ($caller['line'] ?? 0) . ")<br>";
			echo "<strong>Function:</strong> " . htmlspecialchars($function, ENT_QUOTES, 'UTF-8') . "<br>";
			echo "<strong>Error Code:</strong> " . (int) $error_code;
			echo "</div>";
		} elseif ($app_env === 'production') {
			// Production: show generic error without leaking details
			if (!headers_sent()) {
				http_response_code($error_code);
			}
			echo "<div style='text-align: center; padding: 50px;'>";
			echo "<h2>Application Error</h2>";
			echo "<p>An error occurred while processing your request.</p>";
			echo "</div>";
		} else {
			// Unknown env — fail safe to production style
			if (!headers_sent()) {
				http_response_code($error_code);
			}
			echo "<div style='text-align: center; padding: 50px;'><h2>Application Error</h2></div>";
		}
		
		exit();
	}

	/**
	 * Function error switch
	 *
	 * @return void
	 */
	public static function static_error_switch(): void
	{
		$app_env = config_app('app_env');

		if ($app_env === 'development') {
			ini_set('display_errors', '1');
			ini_set('display_startup_errors', '1');
			error_reporting(E_ALL);
		} elseif ($app_env === 'production') {
			ini_set('display_errors', '0');
			ini_set('display_startup_errors', '0');
			error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
		} else {
			exit('<pre>The application environment is not set correctly. Please check the <strong>APP_ENV</strong> inside env file in the root directory.</pre>');
		}
	}

	/**
	 * Register PHP-level logging hooks: non-fatal diagnostics via
	 * set_error_handler, fatal/parse/uncaught via a shutdown handler.
	 * No-op when logging is disabled; never changes on-screen behaviour.
	 */
	public static function register_error_logging(): void
	{
		if (!LogManager::enabled()) {
			return;
		}

		set_error_handler([self::class, 'handle_php_error']);
		register_shutdown_function([self::class, 'handle_shutdown']);
	}

	/** Guard against re-entrant logging while handling an error. */
	private static bool $handlingPhpError = false;

	/**
	 * Log non-fatal PHP diagnostics, then defer to PHP's normal handling.
	 */
	public static function handle_php_error(int $errno, string $errstr, string $errfile = '', int $errline = 0): bool
	{
		if (self::$handlingPhpError) {
			return false;
		}
		self::$handlingPhpError = true;

		try {
			if ((error_reporting() & $errno) !== 0) {
				$fatals = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR];
				$level = in_array($errno, $fatals, true)
					? 'error'
					: (in_array($errno, [E_WARNING, E_USER_WARNING], true) ? 'warning' : 'debug');
				LogManager::channel('error')->log($level, $errstr, ['errno' => $errno, 'file' => $errfile, 'line' => $errline]);
			}
		} catch (\Throwable $e) {
			// never interfere with PHP's handling
		} finally {
			self::$handlingPhpError = false;
		}

		return false; // let PHP's internal handler continue (dev still displays)
	}

	/**
	 * Capture fatal errors and uncaught exceptions at shutdown.
	 */
	public static function handle_shutdown(): void
	{
		$error = error_get_last();
		if (!is_array($error)) {
			return;
		}

		$fatals = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR];
		if (!in_array($error['type'] ?? 0, $fatals, true) || LogManager::isFatalLogged()) {
			return;
		}

		try {
			LogManager::channel('error')->error((string) ($error['message'] ?? 'Fatal error'), [
				'errno' => $error['type'] ?? 0,
				'file' => $error['file'] ?? '',
				'line' => $error['line'] ?? 0,
			]);
			LogManager::markFatalLogged();
		} catch (\Throwable $e) {
			// ignore
		}
	}

	/**
	 * Execute migration with direction (up/down)
	 *
	 * @param  string $migration_name
	 * @param  string $direction
	 * @return void
	 */
	private static function executeMigration(string $migration_name = '', string $direction = 'up'): never
	{
		if ($migration_name === '' || trim($migration_name) === '') {
			self::static_error_handler('Migration name cannot be empty', 400);
		}

		$classname = 'System\\Migrations\\' . $migration_name;

		if (!class_exists($classname)) {
			$var_msg = "Migration class '$migration_name' not found!\nCheck class name in System/Migrations directory";
			self::static_error_handler($var_msg, 404);
		}

		try {
			$migration = new $classname;
			
			if (!method_exists($migration, $direction)) {
				self::static_error_handler("Method '$direction' not found in migration class", 500);
			}

			$migration->{$direction}();

			try {
				LogManager::channel('migration')->info('Migration executed', [
					'class' => $classname,
					'direction' => $direction,
				]);
			} catch (\Throwable $e) {
				// ignore
			}

			echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px;'>";
			echo "<h4>✅ Migration Success</h4>";
			echo "Database has been successfully <strong>migrated " . htmlspecialchars($direction, ENT_QUOTES, 'UTF-8') . "</strong><br>";
			echo "<strong>Class:</strong> " . htmlspecialchars($classname, ENT_QUOTES, 'UTF-8') . "<br>";
			echo "<strong>Timestamp:</strong> " . date('Y-m-d H:i:s');
			echo "</div>";

		} catch (\Throwable $e) {
			self::static_error_handler("Migration failed: " . $e->getMessage(), 500);
		}
		
		exit();
	}

	/**
	 * Start migration (optimized)
	 *
	 * @param  string $string
	 * @return void
	 */
	public static function mig_up(string $string = ''): never
	{
		self::guardMigrationEnvironment();
		self::executeMigration($string, 'up');
	}

	/**
	 * Rollback migration (optimized)
	 *
	 * @param  string $string
	 * @return void
	 */
	public static function mig_down(string $string = ''): never
	{
		self::guardMigrationEnvironment();
		self::executeMigration($string, 'down');
	}

	/**
	 * Block web-triggered migrations in production; CLI (`nsy run:migrate`) stays allowed.
	 */
	private static function guardMigrationEnvironment(): void
	{
		if (PHP_SAPI !== 'cli' && config_app('app_env') === 'production') {
			try {
				LogManager::channel('migration')->warning('Web-triggered migration blocked', ['sapi' => PHP_SAPI]);
			} catch (\Throwable $e) {
				// ignore
			}
			self::static_error_handler('Migrations are disabled in production. Use CLI: <code>nsy run:migrate</code>', 403);
		}
	}

	/**
	 * Register NSY System (optimized with SystemLoader)
	 * @return void
	 */
	public static function register_system(): void
	{
		// PHP-level logging hooks first (fatal / parse / uncaught capture)
		self::register_error_logging();

		// Use optimized system loader with caching and error handling
		NSY_SystemLoader::loadSystemFiles();
	}

	/**
	 * NSY Register route function (optimized with auto-discovery)
	 * @return void
	 */
	public static function register_route(): void
	{
		// Keep route cache effective — clear only in development to reflect changes
		if (config_app('app_env') === 'development') {
			NSY_RouteLoader::clearCache();
		}
		
		// Use optimized route loader with auto-discovery and caching
		NSY_RouteLoader::loadRoutes();
	}
}
