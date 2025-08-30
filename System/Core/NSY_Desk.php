<?php

namespace System\Core;

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
	public static function static_error_handler($var_msg = '', $error_code = 500)
	{
		$app_env = config_app('app_env');

		// Log error in all environments
		error_log("NSY Error: $var_msg");

		if ($app_env === 'development') {
			try {
				throw new \Exception($var_msg);
			} catch (\Exception $e) {
				$trace = $e->getTrace();
				$caller = $trace[1] ?? ['file' => 'unknown', 'line' => 0];
				$function = isset($trace[2]) ? $trace[2]['class'] . '->' . $trace[2]['function'] . '()' : 'unknown';
				
				echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px; font-family: monospace;'>";
				echo "<h4>🚨 NSY Framework Error</h4>";
				echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
				echo "<strong>File:</strong> " . htmlspecialchars($caller['file']) . " (Line: {$caller['line']})<br>";
				echo "<strong>Function:</strong> " . htmlspecialchars($function) . "<br>";
				echo "<strong>Error Code:</strong> $error_code";
				echo "</div>";
			}
		} elseif ($app_env === 'production') {
			// Production: show generic error
			http_response_code($error_code);
			echo "<div style='text-align: center; padding: 50px;'>";
			echo "<h2>Application Error</h2>";
			echo "<p>An error occurred while processing your request.</p>";
			echo "</div>";
		}
		
		exit();
	}

	/**
	 * Function error switch
	 *
	 * @return void
	 */
	public static function static_error_switch()
	{
		$app_env = config_app('app_env');

		if ($app_env == 'development') {
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		} elseif ($app_env == 'production') {
			ini_set('display_errors', 0);
			error_reporting(0);

			if (version_compare(PHP_VERSION, '5.3', '>=')) {
				error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
			} else {
				error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
			}
		} else {
			exit('<pre>The application environment is not set correctly. Please check the <strong>APP_ENV</strong> inside env file in the root directory.</pre>');
		}
	}

	/**
	 * Execute migration with direction (up/down)
	 *
	 * @param  string $migration_name
	 * @param  string $direction
	 * @return void
	 */
	private static function executeMigration($migration_name = '', $direction = 'up')
	{
		if (empty($migration_name)) {
			self::static_error_handler('Migration name cannot be empty', 400);
			return;
		}

		$classname = 'System\\Migrations\\' . $migration_name;

		if (!class_exists($classname)) {
			$var_msg = "Migration class '$migration_name' not found!\nCheck class name in System/Migrations directory";
			self::static_error_handler($var_msg, 404);
			return;
		}

		try {
			$migration = new $classname;
			
			if (!method_exists($migration, $direction)) {
				self::static_error_handler("Method '$direction' not found in migration class", 500);
				return;
			}

			$migration->{$direction}();
			
			echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px;'>";
			echo "<h4>✅ Migration Success</h4>";
			echo "Database has been successfully <strong>migrated $direction</strong><br>";
			echo "<strong>Class:</strong> $classname<br>";
			echo "<strong>Timestamp:</strong> " . date('Y-m-d H:i:s');
			echo "</div>";

		} catch (Exception $e) {
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
	public static function mig_up($string = '')
	{
		self::executeMigration($string, 'up');
	}

	/**
	 * Rollback migration (optimized)
	 *
	 * @param  string $string
	 * @return void
	 */
	public static function mig_down($string = '')
	{
		self::executeMigration($string, 'down');
	}

	/**
	 * Register NSY System (optimized with SystemLoader)
	 * @return void
	 */
	public static function register_system()
	{
		// Use optimized system loader with caching and error handling
		NSY_SystemLoader::loadSystemFiles();
	}

	/**
	 * NSY Register route function (optimized with auto-discovery)
	 * @return void
	 */
	public static function register_route()
	{
		// Clear cache to ensure new configuration takes effect
		NSY_RouteLoader::clearCache();
		
		// Use optimized route loader with auto-discovery and caching
		NSY_RouteLoader::loadRoutes();
	}
}
