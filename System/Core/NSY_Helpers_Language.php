<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\LanguageCode; // LanguageCode Class

/**
 * Language Helpers
 */
if (! function_exists('show_all_lang')) {
	/**
	* Get all language codes as array.
	*
	* @return array → language codes and language names
	*/
	function show_all_lang()
	{
		$arr = LanguageCode::get();

		return $arr;
	}
}

if (! function_exists('show_lang_name')) {
	/**
	* Get language name from language code.
	*
	* @param string $languageCode → language code, e.g. 'es'
	*
	* @return string|false → country name
	*/
	function show_lang_name($languageCode)
	{
		$langname = LanguageCode::getLanguageFromCode($languageCode);

		return $langname;
	}
}

if (! function_exists('show_lang_code')) {
	/**
	* Get language code from language name.
	*
	* @param string $languageName → language name, e.g. 'Spanish'
	*
	* @return string|false → language code
	*/
	function show_lang_code($languageName)
	{
		$langcode = LanguageCode::getCodeFromLanguage($languageName);

		return $langcode;
	}
}
