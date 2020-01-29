<?php
namespace System\Core;

/**
* Razr Template Engine
*/
use System\Razr\Engine;
use System\Razr\Loader\FilesystemLoader;

/**
* This is the core of NSY Controller
* Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
*/
class NSY_Controller
{

	private $module;

	/**
	* HMVC & MVC View Folder
	*
	* @param  string $module
	* @param  string $filename
	* @param  array  $vars
	* @return string
	*/
	protected function load_view($module = null, $filename = null, $vars = array())
	{
		// Instantiate Razr Template Engine
		$this->razr = new Engine(new FilesystemLoader(get_vendor_dir()));

		if (is_array($vars) || is_object($vars) || is_filled($filename) ) {
			if(not_filled($module) ) {
				echo $this->razr->render(get_mvc_view_dir() . $filename . '.php', $vars);
			} else {
				echo $this->razr->render(get_hmvc_view_dir() . $module . '/views/' . $filename . '.php', $vars);
			}
		} else
		{
			$var_msg = 'The variable in the <mark>load_view()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return $this;
	}

	/**
	* Template Directory
	*
	* @param  string $filename
	* @param  array  $vars
	* @return string
	*/
	protected function load_template($filename = null, $vars = array())
	{
		// Instantiate Razr Template Engine
		$this->razr = new Engine(new FilesystemLoader(get_vendor_dir()));

		if (is_array($vars) || is_object($vars) ) {
			echo $this->razr->render(get_system_dir() . $filename . '.php', $vars);
		} else
		{
			$var_msg = 'The variable in the <mark>load_template()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return $this;
	}

	/**
	 * Instantiate Model & Method caller
	 * Modified by Vikry Yuansah for NSY System
	 * @param  string $models
	 * @param  string $methods
	 * @return string
	 */
	protected function model($models = '', $methods = '')
	{
		if ( not_filled($methods) || not_filled($models) ) {
			$var_msg = 'The variable in the <mark>model(<strong>model_name</strong>, <strong>method_name</strong>)</mark> is improper or not filled';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		$params = explode('\\', $models);

		if (count($params) > 1) {
			$fullclass = 'System\Modules\\'.$params[0].'\Models\\'.$params[1];
		} else {
			$fullclass = 'System\Models\\'.$models;
		}

		$defClass = new $fullclass;
		return $defClass->{$methods}();
	}

}
