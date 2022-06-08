<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Cookie; // Cookie Class

/**
* Cookie handler.
*/
if (! function_exists('cookie_set')) {
	/**
	* Set cookie.
	*
	* @param string $key   → name the data to save
	* @param string $value → the data to save
	* @param int $time  → expiration time in days
	*
	* @return boolean
	*/
	function cookie_set($key, $value, $time = 365)
	{
		$result = Cookie::set($key, $value, $time);

		return $result;
	}
}

if (! function_exists('cookie_get')) {
	/**
	* Get item from cookie.
	*
	* @param string $key → item to look for in cookie
	*
	* @return mixed|false → returns cookie value, cookies array or false
	*/
	function cookie_get($key = '')
	{
		$result = Cookie::get($key);

		return $result;
	}
}

if (! function_exists('cookie_pull')) {
	/**
	* Extract item from cookie then delete cookie and return the item.
	*
	* @param string $key → item to extract
	*
	* @return string|false → return item or false when key does not exists
	*/
	function cookie_pull($key)
	{
		$result = Cookie::pull($key);

		return $result;
	}
}

if (! function_exists('cookie_remove')) {
	/**
	* Empties and destroys the cookies.
	*
	* @param string $key → cookie name to destroy. Not set to delete all
	*
	* @return boolean
	*/
	function cookie_remove($key = '')
	{
		$result = Cookie::destroy($key);

		return $result;
	}
}

if (! function_exists('cookie_set_prefix')) {
	/**
	* Set cookie prefix.
	*
	* @since 1.1.6
	*
	* @param string $prefix → cookie prefix
	*
	* @return boolean
	*/
	function cookie_set_prefix($prefix)
	{
		$result = Cookie::setPrefix($prefix);

		return $result;
	}
}

if (! function_exists('cookie_get_prefix')) {
	/**
	* Get cookie prefix.
	*
	* @since 1.1.5
	*
	* @return string
	*/
	function cookie_get_prefix()
	{
		$result = Cookie::getPrefix();

		return $result;
	}
}
