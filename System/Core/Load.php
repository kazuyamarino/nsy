<?php
namespace System\Core;

/**
* Razr Template Engine
*/
use System\Core\Razr\Engine;
use System\Core\Razr\Loader\FilesystemLoader;

/**
* This is the core of NSY Controller
* Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
*/
class Load
{

	static $razr;
	static $module;

	/**
	* HMVC & MVC View Folder
	*
	* @param  string $module
	* @param  string $filename
	* @param  array  $vars
	* @return string
	*/
	protected static function view($module = '', $filename = '', $vars = array())
	{
		// Instantiate Razr Template Engine
		self::$razr = new Engine(new FilesystemLoader(get_vendor_dir()));

		if (is_array($vars) || is_object($vars) || is_filled($filename) ) {
			if(not_filled($module) ) {
				echo self::$razr->render(get_mvc_view_dir() . $filename . '.php', $vars);
			} else {
				echo self::$razr->render(get_hmvc_view_dir() . $module . '/Views/' . $filename . '.php', $vars);
			}
		} else
		{
			$var_msg = 'The variable in the <mark>Load::view()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	* Template Directory
	*
	* @param  string $filename
	* @param  array  $vars
	* @return string
	*/
	protected static function template($filename = '', $vars = array())
	{
		// Instantiate Razr Template Engine
		self::$razr = new Engine(new FilesystemLoader(get_vendor_dir()));

		if (is_array($vars) || is_object($vars) ) {
			echo self::$razr->render(get_system_dir() . $filename . '.php', $vars);
		} else
		{
			$var_msg = 'The variable in the <mark>Load::template()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	 * Instantiate Model & Method caller
	 * Modified by Vikry Yuansah for NSY System
	 * @param  object $models
	 * @return object
	 */
	protected static function model($models = '')
	{
		if ( not_filled($models) ) {
			$var_msg = 'The variable in the <mark>Load::model(<strong>model_name</strong>, <strong>method_name</strong>)</mark> is improper or not filled';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		$params = explode('\\', $models);

		if (count($params) > 1) {
			$fullclass = 'System\Modules\\'.$params[0].'\Models\\'.$params[1];
		} else {
			$fullclass = 'System\Models\\'.$models;
		}

		return $defClass = new $fullclass;
	}

}
