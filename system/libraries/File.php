<?php
/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * PHP library for file management.
 *
 * @author    Josantonius <hello@josantonius.com>
 * @copyright 2017 - 2018 (c) Josantonius - PHP-File
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Josantonius/PHP-File
 * @since     1.0.0
 */
namespace System\Libraries;

defined('ROOT') OR exit('No direct script access allowed');

/**
 * File handler.
 */
class File
{
    /**
     * Check if a file exists in a path or url.
     *
     * @since 1.1.3
     *
     * @param string $file → path or file url
     *
     * @return bool
     */
    public static function exists($file)
    {
        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $stream = stream_context_create(['http' => ['method' => 'HEAD']]);
            if ($content = @fopen($file, 'r', null, $stream)) {
                $headers = stream_get_meta_data($content);
                fclose($content);
                $status = substr($headers['wrapper_data'][0], 9, 3);

                return $status >= 200 && $status < 400;
            }

            return false;
        }

        return file_exists($file) && is_file($file);
    }

    /**
     * Delete file.
     *
     * @since 1.1.3
     *
     * @param string $file → file path
     *
     * @return bool
     */
    public static function delete($file)
    {
        return self::exists($file) && @unlink($file);
    }

    /**
     * Create directory.
     *
     * @since 1.1.3
     *
     * @param string $path → path where to create directory
     *
     * @return bool
     */
    public static function create_dir($path)
    {
        return ! is_dir($path) && @mkdir($path, 0777, true);
    }

    /**
     * Copy directory recursively.
     *
     * @since 1.1.4
     *
     * @param string $fromPath → path from copy
     * @param string $toPath   → path to copy
     *
     * @return bool
     */
    public static function copy_dir_recursively($from, $to)
    {
        if (! $path = self::get_files_from_dir($from)) {
            return false;
        }

        self::create_dir($to = rtrim($to, '/') . '/');

        foreach ($path as $file) {
            if ($file->isFile()) {
                if (! copy($file->getRealPath(), $to . $file->getFilename())) {
                    return false;
                }
            } elseif (! $file->isDot() && $file->isDir()) {
                self::copy_dir_recursively($file->getRealPath(), $to . $path);
            }
        }

        return true;
    }

    /**
     * Delete empty directory.
     *
     * @since 1.1.3
     *
     * @param string $path → path to delete
     *
     * @return bool
     */
    public static function delete_empty_dir($path)
    {
        return is_dir($path) && @rmdir($path);
    }

    /**
     * Delete directory recursively.
     *
     * @since 1.1.3
     *
     * @param string $path → path to delete
     *
     * @return bool
     */
    public static function delete_dir_recursively($path)
    {
        if (! $paths = self::get_files_from_dir($path)) {
            return false;
        }

        foreach ($paths as $file) {
            if ($file->isFile()) {
                if (! self::delete($file->getRealPath())) {
                    return false;
                }
            } elseif (! $file->isDot() && $file->isDir()) {
                self::delete_dir_recursively($file->getRealPath());
                self::delete_empty_dir($file->getRealPath());
            }
        }

        return self::delete_empty_dir($path);
    }

    /**
     * Get files from directory.
     *
     * @since 1.1.3
     *
     * @param string $path → path where get files
     *
     * @return object|false →
     */
    public static function get_files_from_dir($path)
    {
        if (! is_dir($path)) {
            return false;
        }

        return new \DirectoryIterator(rtrim($path, '/') . '/');
    }
}
