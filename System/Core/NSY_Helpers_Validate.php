<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Validate; // Validate Class

/**
 * Data Validation Helpers
 */
if (! function_exists('validate_array')) {
	/**
	* Parameter return as array.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_array($data, $default = '')
	{
		$result = Validate::asArray($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_object')) {
	/**
	* Parameter return as object.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_object($data, $default = '')
	{
		$result = Validate::asObject($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_json')) {
	/**
	* Parameter return as JSON.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_json($data, $default = '')
	{
		$result = Validate::asJson($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_string')) {
	/**
	* Parameter return as string.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_string($data, $default = '')
	{
		$result = Validate::asString($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_integer')) {
	/**
	* Parameter return as integer.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_integer($data, $default = '')
	{
		$result = Validate::asInteger($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_float')) {
	/**
	* Parameter return as float.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_float($data, $default = '')
	{
		$result = Validate::asFloat($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_boolean')) {
	/**
	* Parameter return as boolean.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_boolean($data, $default = '')
	{
		$result = Validate::asBoolean($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_ip')) {
	/**
	* Parameter return as IP.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_ip($data, $default = '')
	{
		$result = Validate::asIp($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_url')) {
	/**
	* Parameter return as URL.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_url($data, $default = '')
	{
		$result = Validate::asUrl($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_email')) {
	/**
	* Parameter return as email.
	*
	* @param mixed $data    → data to convert
	* @param mixed $default → default value in error case
	*
	* @return mixed → value, null or customized return value
	*/
	function validate_email($data, $default = '')
	{
		$result = Validate::asEmail($data, $default = null);

		return $result;
	}
}
