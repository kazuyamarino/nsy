<?php
namespace System\Libraries;

/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * Calculate load time of pages or scripts.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - PHP-LoadTime
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-LoadTime
 * @since     1.0.0
 */

/**
* Load time handler.
*/
class LoadTime
{
	/**
	* Save initial status time.
	*
	* @var float
	*/
	public static $startTime = false;

	/**
	* Set initial time.
	*
	* @return float → microtime
	*/
	public static function start()
	{
		return self::$startTime = microtime(true);
	}

	/**
	* Set end time.
	*
	* @return float → seconds
	*/
	public static function end()
	{
		if (self::$startTime) {
			$time = round((microtime(true) - self::$startTime), 4);
			self::$startTime = false;
		}

		return (isset($time)) ? $time : false;
	}

	/**
	* Check if the timer has been started.
	*
	* @since 1.1.2
	*
	* @return boolean
	*/
	public static function isActive()
	{
		return (self::$startTime) ? true : false;
	}

}
