<?php

namespace System\Libraries;

/**
 * PHP simple library for managing of data types.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2018 (c) Josantonius - PHP-Validate
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-Validate
 * @since     1.0.0
 */

/**
 * Validation or conversion of data types.
 */
class Validate
{
	/**
	 * Parameter return as array.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asArray(mixed $data, mixed $default = null)
	{
		$json = is_string($data) ? $data : json_encode($data);

		$array = json_decode($json, true) ?? null;

		$array = $array ? filter_var_array($array) : false;

		return $array !== false ? $array : $default;
	}

	/**
	 * Parameter return as object.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asObject(mixed $data, mixed $default = null)
	{
		$json = is_string($data) ? $data : json_encode($data);

		$object = json_decode($json);

		return is_object($object) ? $object : $default;
	}

	/**
	 * Parameter return as JSON.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asJson(mixed $data, mixed $default = null)
	{
		$json = is_string($data) ? $data : json_encode($data);

		return $json !== false ? $json : $default;
	}

	/**
	 * Parameter return as string.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asString(mixed $data, mixed $default = null)
	{
		$string = filter_var(
			$data ?? [],
			FILTER_SANITIZE_STRING,
			FILTER_FLAG_NO_ENCODE_QUOTES
		);

		return $string !== false ? $string : $default;
	}

	/**
	 * Parameter return as integer.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asInteger(mixed $data, mixed $default = null)
	{
		$int = filter_var($data ?? '', FILTER_VALIDATE_INT);

		return $int !== false ? $int : $default;
	}

	/**
	 * Parameter return as float.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asFloat(mixed $data, mixed $default = null)
	{
		$float = filter_var($data ?? '', FILTER_VALIDATE_FLOAT);

		return $float !== false ? $float : $default;
	}

	/**
	 * Parameter return as boolean.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asBoolean(mixed $data, mixed $default = null)
	{
		$boolean = filter_var($data ?? [], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

		return !is_null($boolean) ? $boolean : $default;
	}

	/**
	 * Parameter return as IP.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asIp(mixed $data, mixed $default = null)
	{
		$isValid = filter_var($data ?? '', FILTER_VALIDATE_IP);

		return $isValid ? $data : $default;
	}

	/**
	 * Parameter return as URL.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asUrl(mixed $data, mixed $default = null)
	{
		$url = filter_var($data ?? '', FILTER_SANITIZE_URL);

		return $url ? $url : $default;
	}

	/**
	 * Parameter return as email.
	 *
	 * @param mixed $data    → data to convert
	 * @param mixed $default → default value in error case
	 *
	 * @return mixed → value, null or customized return value
	 */
	public static function asEmail(mixed $data, mixed $default = null)
	{
		$isValid = filter_var($data ?? '', FILTER_VALIDATE_EMAIL);

		return $isValid ? $data : $default;
	}
}
