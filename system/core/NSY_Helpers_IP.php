<?php
use System\Libraries\Ip; // Ip Class

/**
 * IP getter & checker Helpers
 * @var mixed
 */
if (! function_exists('get_ip')) {
	/**
	 * Get user's IP
	 * @return string|false
	 */
	function get_ip()
	{
		$get_ip = Ip::get();

		return $get_ip;
	}
}

if (! function_exists('check_ip')) {
	/**
	 * Validate IP
	 * @return string|false
	 */
	function check_ip($ip)
	{
		$check_ip = Ip::validate($ip);

		return $check_ip;
	}
}
