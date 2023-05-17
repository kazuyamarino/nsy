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
	 * @param  mixed $module
	 * @param  mixed $filename
	 * @param  array  $vars
	 * @return void
	 */
	protected static function view(mixed $module = '', mixed $filename = '', array $vars = array())
	{
		// Instantiate Razr Template Engine
		self::$razr = new Engine(new FilesystemLoader(get_vendor_dir()));

		if (is_array($vars) || is_object($vars) || is_filled($filename)) {
			if (not_filled($module)) {
				echo self::$razr->render(get_mvc_view_dir() . $filename . '.php', $vars);
			} else {
				echo self::$razr->render(get_hmvc_view_dir() . $module . '/Views/' . $filename . '.php', $vars);
			}
		} else {
			$var_msg = 'The variable in the <mark>Load::view()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	 * Template Directory
	 *
	 * @param  mixed $filename
	 * @param  array  $vars
	 * @return void
	 */
	protected static function template(mixed $filename = '', array $vars = array())
	{
		// Instantiate Razr Template Engine
		self::$razr = new Engine(new FilesystemLoader(get_vendor_dir()));

		if (is_array($vars) || is_object($vars)) {
			echo self::$razr->render(get_system_dir() . $filename . '.php', $vars);
		} else {
			$var_msg = 'The variable in the <mark>Load::template()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	 * Instantiate Model & Method caller
	 * Modified by Vikry Yuansah for NSY System
	 * @param  mixed $models
	 * @return object
	 */
	protected static function model(mixed $fullclass = '')
	{
		if (not_filled($fullclass)) {
			$var_msg = 'The variable in the <mark>Load::model(<strong>model_name</strong>, <strong>method_name</strong>)</mark> is improper or not filled';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new $fullclass;
	}
}
