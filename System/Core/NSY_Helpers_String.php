<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Str; // String Class

/**
 * String Helpers
 * @var mixed
 */
if (! function_exists('str_starts_with')) {
	/**
	 * Check if the string starts with a certain value
	 * @param  string $search
	 * @param  string $string
	 * @return boolean
	 */
	function str_starts_with($search = '', $string = '')
	{
		$str = Str::starts_with($search, $string);

		return $str;
	}
}

if (! function_exists('str_ends_with')) {
	/**
	 * Check if the string ends with a certain value
	 * @param  string $search
	 * @param  string $string
	 * @return boolean
	 */
	function str_ends_with($search = '', $string = '')
	{
		$str = Str::ends_with($search, $string);

		return $str;
	}
}
