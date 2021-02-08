<?php
namespace System\Libraries;

/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * PHP simple library for managing Json files.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2016 - 2018 (c) Josantonius - PHP-Json
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-Json
 * @since     1.0.0
 */

use System\Libraries\Exception\JsonException;

/**
 * Json handler.
 */
class Json
{
    /**
     * Creating JSON file from array.
     *
     * @param array  $array → array to be converted to JSON
     * @param string $file  → path to the file
     *
     * @return boolean → true if the file is created without errors
     */
    public static function array_to_file($array, $file)
    {
        self::create_directory($file);

        $lastError = JsonLastError::check();

        $json = json_encode($lastError ? $lastError : $array, JSON_PRETTY_PRINT);

        self::save_file($file, $json);

        return is_null($lastError);
    }

    /**
     * Save to array the JSON file content.
     *
     * @param string $file → path or external url to JSON file
     *
     * @return array|false
     */
    public static function file_to_array($file)
    {
        if (! is_file($file) && ! filter_var($file, FILTER_VALIDATE_URL)) {
            self::array_to_file([], $file);
        }

        $json = @file_get_contents($file);
        $array = json_decode($json, true);
        $lastError = JsonLastError::check();

        return $array === null || !is_null($lastError) ? false : $array;
    }

    /**
     * Create directory recursively if it doesn't exist.
     *
     * @since 1.1.3
     *
     * @param string $file → path to the directory
     *
     * @throws JsonException → couldn't create directory
     */
    private static function create_directory($file)
    {
        $basename = is_string($file) ? basename($file) : '';
        $path = str_replace($basename, '', $file);

        if (! empty($path) && ! is_dir($path)) {
            if (! mkdir($path, 0755, true)) {
                $message = 'Could not create directory in';
                throw new JsonException($message . ' ' . $path);
            }
        }
    }

    /**
     * Save file.
     *
     * @since 1.1.3
     *
     * @param string $file → path to the file
     * @param string $json → json string
     *
     * @throws JsonException → couldn't create file
     */
    private static function save_file($file, $json)
    {
        if (@file_put_contents($file, $json) === false) {
            $message = 'Could not create file in';
            throw new JsonException($message . ' ' . $file);
        }
    }
}
