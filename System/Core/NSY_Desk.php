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
	* Function error handler
	*
	* @param  string $var_msg
	* @return void
	*/
	public static function static_error_handler($var_msg = null)
	{
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				// Set as under development
				case 'development':
				try {
					throw new \Exception($var_msg);
				}
				catch (\Exception $e) {
					$err = $e->getTrace();
					echo "<pre>Message:\n" . $e->getMessage() ." in ".  $err[1]['file'] . "(". $err[1]['line'] .')' ."</pre>";
					echo "<pre>Stack trace:\n#0 ". $err[1]['file'] . "(" . $err[1]['line'] . "): " . $err[2]['class'] . "->" . $err[2]['function'] . "()" ."</pre>";
				}
				break;
				default:
			}
		}
	}

	/**
	* Function error switch
	*
	* @return void
	*/
	public static function static_error_switch()
	{
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				// Set as under development
				case 'development':
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 1);
				error_reporting(E_ALL);
				break;

				// Set as under production/go live
				case 'production':
				ini_set('display_errors', 0);
				error_reporting(0);

				if (version_compare(PHP_VERSION, '5.3', '>=')) {
					error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
				} else {
					error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
				}
				break;
				default:
				header('HTTP/1.1 503 Service Unavailable.', true, 503);
				exit('<pre>The application environment is not set correctly. Please check the <strong>APP_ENV</strong> inside env file in the root directory.</pre>');
				exit(1); // EXIT_ERROR
			}
		}
	}

	/**
	* Start migration
	*
	* @param  string $string
	* @return void
	*/
	public static function mig_up($string = null)
	{
		$classname = 'System\\Migrations\\'.$string;

		if (class_exists($classname) ) {
			$mig = new $classname;
			$mig->up();

			echo "<pre>The database has been successfully <strong>migrated up</strong></pre>";
			exit();
		} else {
			$var_msg = "Class name not found! \nSee the class name list in the <strong>System/Migrations</strong> directory";
			self::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	* Rollback migration
	*
	* @param  string $string
	* @return void
	*/
	public static function mig_down($string = null)
	{
		$classname = 'System\\Migrations\\'.$string;

		if (class_exists($classname) ) {
			$mig = new $classname;
			$mig->down();

			echo "<pre>The database has been successfully <strong>migrated down</strong></pre>";
			exit();
		} else {
			$var_msg = "Class name not found! \nSee the class name list in the <strong>System/Migrations</strong> directory";
			self::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	 * Require/Register NSY System
	 * @return void
	 */
	public static function register_system()
	{
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_DataConversion.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_IP.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_Language.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_LoadTime.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_Request.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_Security.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_String.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_Helpers_Validate.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Libraries/Assets.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Libraries/Aliases.php';
	}

	/**
	 * Require/Register NSY Route
	 * @return void
	 */
	public static function register_route()
	{
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Routes/Web.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Routes/Api.php';
		require_once __DIR__ . '/../../'. config_app('sys_dir') .'/Routes/Migration.php';
	}

	/**
	 * Require/Register NSY Config
	 * @return void
	 */
	public static function register_config()
	{
		// NSY System file check
		if (!is_readable( __DIR__ . '/../../'. config_app('sys_dir') .'/Core/NSY_System.php' ) ) {
			die('NSY_System.php not found,  please check in System/Core.');
		}

		// Env file check
		if (!is_readable( __DIR__ . '/../../' . config_app('env_file')) ) {
			die('env file not found, please check in root folder and Config/App.php => <strong>env_file</strong>.');
		}
	}

}
