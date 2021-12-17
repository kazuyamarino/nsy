<?php
namespace System\Libraries;

/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * List of 217 language codes: ISO 639-1.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - PHP-LanguageCode
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-LanguageCode
 * @since     1.0.0
 */

/**
* Language code handler.
*/
class LanguageCode
{
	/**
	* Get all language codes as array.
	*
	* @return array → language codes and language names
	*/
	public static function get()
	{
		return LanguageCodeCollection::all();
	}

	/**
	* Get language name from language code.
	*
	* @param string $languageCode → language code, e.g. 'es'
	*
	* @return string|false → country name
	*/
	public static function getLanguageFromCode($languageCode)
	{
		return LanguageCodeCollection::get($languageCode) ?: false;
	}

	/**
	* Get language code from language name.
	*
	* @param string $languageName → language name, e.g. 'Spanish'
	*
	* @return string|false → language code
	*/
	public static function getCodeFromLanguage($languageName)
	{
		return array_search($languageName, LanguageCodeCollection::all(), true);
	}

}
