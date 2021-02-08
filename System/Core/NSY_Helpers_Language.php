<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\LanguageCode; // LanguageCode Class

/**
 * Language Helpers
 * @var mixed
 */
if (! function_exists('parse_language')) {
	/**
	 * Get all language codes as array
	 * @return array
	 */
	function parse_language()
	{
		$arr = LanguageCode::get();

		return $arr;
	}
}

if (! function_exists('get_language_name')) {
	/**
	 * Get language name from language code
	 * @return string
	 */
	function get_language_name($langcode)
	{
		$langname = LanguageCode::get_language_from_code($langcode);

		return $langname;
	}
}

if (! function_exists('get_language_code')) {
	/**
	 * Get language code from language name
	 * @return string
	 */
	function get_language_code($langname)
	{
		$langcode = LanguageCode::get_code_from_language($langname);

		return $langcode;
	}
}
