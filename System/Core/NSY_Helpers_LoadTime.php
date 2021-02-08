<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\LoadTime; // LoadTime Class

/**
 * Script Load Time Helpers
 * @var mixed
 */
if (! function_exists('load_time')) {
	/**
	 * Calculate load time of pages or scripts
	 * Set initial time.
	 * @return boolean
	 */
	function load_time()
	{
		$timestart = LoadTime::start();

		return $timestart;
	}
}

if (! function_exists('end_time')) {
	/**
	 * Calculate end load time of pages or scripts
	 * Set end time.
	 * @return boolean
	 */
	function end_time()
	{
		$timestart = LoadTime::end();

		return $timestart;
	}
}

if (! function_exists('is_active_time')) {
	/**
	 * Check if the timer has been started
	 * @return boolean
	 */
	function is_active_time()
	{
		$timestart = LoadTime::is_active();

		return $timestart;
	}
}
