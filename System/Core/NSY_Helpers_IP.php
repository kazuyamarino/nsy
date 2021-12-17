<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Ip; // Ip Class

/**
 * IP getter & checker Helpers
 */
if (! function_exists('get_ip')) {
	/**
	* Get user's IP.
	*
	* @return string|false → user IP
	*/
	function get_ip()
	{
		$get_ip = Ip::get();

		return $get_ip;
	}
}

if (! function_exists('validate_ip')) {
	/**
	* Validate IP.
	*
	* @since 1.1.2
	*
	* @param string $ip → IP address to be validated
	*
	* @return bool
	*/
	function validate_ip($ip)
	{
		$check_ip = Ip::validate($ip);

		return $check_ip;
	}
}
