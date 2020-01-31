<?php
use System\Libraries\Validate; // Validate Class

/**
 * Data Validation Helpers
 * @var mixed
 */
if (! function_exists('validate_array')) {
	/**
	 * Parameter return as array
	 * @return array
	 */
	function validate_array($data, $default = null)
	{
		$result = Validate::as_array($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_object')) {
	/**
	 * Parameter return as object
	 * @return object
	 */
	function validate_object($data, $default = null)
	{
		$result = Validate::as_object($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_json')) {
	/**
	 * Parameter return as json
	 * @return string
	 */
	function validate_json($data, $default = null)
	{
		$result = Validate::as_json($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_string')) {
	/**
	 * Parameter return as string
	 * @return string
	 */
	function validate_string($data, $default = null)
	{
		$result = Validate::as_string($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_integer')) {
	/**
	 * Parameter return as integer
	 * @return integer
	 */
	function validate_integer($data, $default = null)
	{
		$result = Validate::as_integer($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_float')) {
	/**
	 * Parameter return as float
	 * @return float
	 */
	function validate_float($data, $default = null)
	{
		$result = Validate::as_float($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_boolean')) {
	/**
	 * Parameter return as boolean
	 * @return boolean
	 */
	function validate_boolean($data, $default = null)
	{
		$result = Validate::as_boolean($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_ip')) {
	/**
	 * Parameter return as ip
	 * @return string
	 */
	function validate_ip($data, $default = null)
	{
		$result = Validate::as_ip($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_url')) {
	/**
	 * Parameter return as url
	 * @return string
	 */
	function validate_url($data, $default = null)
	{
		$result = Validate::as_url($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_email')) {
	/**
	 * Parameter return as email
	 * @return string
	 */
	function validate_email($data, $default = null)
	{
		$result = Validate::as_email($data, $default = null);

		return $result;
	}
}
