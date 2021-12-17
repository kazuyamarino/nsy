<?php
namespace System\Libraries;

/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * PHP library for string handler.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - PHP-Str
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-Str
 * @since     1.0.0
 */

/**
* String handler.
*/
class Str
{
	/**
	* Check if the string starts with a certain value.
	*
	* @param string $search → the string to search
	* @param string $string → the string where search
	*
	* @return bool
	*/
	public static function startsWith($search, $string)
	{
		return substr($string, 0, strlen($search)) === $search;
	}

	/**
	* Check if the string ends with a certain value.
	*
	* @param string $search → the string to search
	* @param string $string → the string where search
	*
	* @return bool
	*/
	public static function endsWith($search, $string)
	{
		return substr($string, -strlen($search)) == $search;
	}

}
