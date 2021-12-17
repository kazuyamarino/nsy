<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Session; // Session Class

/**
 * Session Helpers
 */
if (! function_exists('session_set_prefix')) {
	/**
	* Set prefix for sessions.
	*
	* @param mixed $prefix → prefix for sessions
	*
	* @return bool
	*/
	function session_set_prefix($prefix)
	{
		$result = Session::setPrefix($prefix);

		return $result;
	}
}

if (! function_exists('session_get_prefix')) {
	/**
	* Get prefix for sessions.
	*
	* @return string
	*/
	function session_get_prefix()
	{
		$result = Session::getPrefix();

		return $result;
	}
}

if (! function_exists('session_init')) {
	/**
	* If session has not started, start sessions.
	*
	* @param int $lifeTime → lifetime of session in seconds
	*
	* @return bool
	*/
	function session_init($lifeTime)
	{
		$result = Session::init($lifeTime);

		return $result;
	}
}

if (! function_exists('session_set')) {
	/**
	* Add value to a session.
	*
	* @param string $key   → name the data to save
	* @param mixed  $value → the data to save
	*
	* @return bool true
	*/
	function session_set($key, $value = false)
	{
		$result = Session::set($key, $value = false);

		return $result;
	}
}

if (! function_exists('session_pull')) {
	/**
	* Extract session item, delete session item and finally return the item.
	*
	* @param string $key → item to extract
	*
	* @return mixed|null → return item or null when key does not exists
	*/
	function session_pull($key)
	{
		$result = Session::pull($key);

		return $result;
	}
}

if (! function_exists('session_get')) {
	/**
	* Get item from session.
	*
	* @param string      $key       → item to look for in session
	* @param string|bool $secondkey → if used then use as a second key
	*
	* @return mixed|null → key value, or null if key doesn't exists
	*/
	function session_get($key = '', $secondkey = false)
	{
		$result = Session::get($key = '', $secondkey = false);

		return $result;
	}
}

if (! function_exists('session_regenerate')) {
	/**
	* Regenerate session_id.
	*
	* @return string → session_id
	*/
	function session_regenerate()
	{
		$result = Session::regenerate();

		return $result;
	}
}

if (! function_exists('session_remove')) {
	/**
	* Empties and destroys the session.
	*
	* @param string $key    → session name to destroy
	* @param bool   $prefix → if true clear all sessions for current prefix
	*
	* @return bool
	*/
	function session_remove($key = '', $prefix = false)
	{
		$result = Session::destroy($key = '', $prefix = false);

		return $result;
	}

}
