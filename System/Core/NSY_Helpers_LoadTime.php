<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\LoadTime; // LoadTime Class

/**
 * Script Load Time Helpers
 */
if (! function_exists('loadtime_start')) {
	/**
	* Set initial time.
	*
	* @return float → microtime
	*/
	function loadtime_start()
	{
		$timestart = LoadTime::start();

		return $timestart;
	}
}

if (! function_exists('loadtime_end')) {
	/**
	* Set end time.
	*
	* @return float → seconds
	*/
	function loadtime_end()
	{
		$timestart = LoadTime::end();

		return $timestart;
	}
}

if (! function_exists('loadtime_active')) {
	/**
	 * Check if the timer has been started
	 * @return boolean
	 */
	function loadtime_active()
	{
		$timestart = LoadTime::is_active();

		return $timestart;
	}
}
