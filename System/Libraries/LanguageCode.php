<?php
declare(strict_types=1);
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
	public static function get(): array
	{
		return LanguageCodeCollection::all();
	}

	/**
	 * Get language name from language code.
	 *
	 * @param string $languageCode → language code, e.g. 'es'
	 *
	 * @return string|false → language name
	 */
	public static function getLanguageFromCode(string $languageCode): string|false
	{
		return LanguageCodeCollection::get(strtolower(trim($languageCode))) ?: false;
	}

	/**
	 * Get language code from language name.
	 *
	 * @param string $languageName → language name, e.g. 'Spanish'
	 *
	 * @return string|false → language code
	 */
	public static function getCodeFromLanguage(string $languageName): string|false
	{
		$all = LanguageCodeCollection::all();

		// Fast path: exact (case-sensitive) match
		$code = array_search($languageName, $all, true);

		if ($code !== false) {
			return $code;
		}

		// Slow path: case-insensitive match
		foreach ($all as $key => $name) {
			if (strcasecmp($name, $languageName) === 0) {
				return $key;
			}
		}

		return false;
	}
}
