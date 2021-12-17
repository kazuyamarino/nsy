<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Str; // String Class

/**
 * String Helpers
 */
if (! function_exists('str_starts_with')) {
	/**
	* Check if the string starts with a certain value.
	*
	* @param string $search → the string to search
	* @param string $string → the string where search
	*
	* @return bool
	*/
	function str_starts_with($search = '', $string = '')
	{
		$str = Str::startsWith($search, $string);

		return $str;
	}
}

if (! function_exists('str_ends_with')) {
	/**
	* Check if the string ends with a certain value.
	*
	* @param string $search → the string to search
	* @param string $string → the string where search
	*
	* @return bool
	*/
	function str_ends_with($search = '', $string = '')
	{
		$str = Str::endsWith($search, $string);

		return $str;
	}
}
