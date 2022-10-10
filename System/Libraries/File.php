<?php
namespace System\Libraries;

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
            if ($content = @fopen($file, 'r', true, $stream)) {
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
    public static function createDir($path)
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
    public static function copyDirRecursively($from, $to)
    {
        if (! $path = self::getFilesFromDir($from)) {
            return false;
        }

        self::createDir($to = rtrim($to, '/') . '/');

        foreach ($path as $file) {
            if ($file->isFile()) {
                if (! copy($file->getRealPath(), $to . $file->getFilename())) {
                    return false;
                }
            } elseif (! $file->isDot() && $file->isDir()) {
                self::copyDirRecursively($file->getRealPath(), $to . $path);
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
    public static function deleteEmptyDir($path)
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
    public static function deleteDirRecursively($path)
    {
        if (! $paths = self::getFilesFromDir($path)) {
            return false;
        }

        foreach ($paths as $file) {
            if ($file->isFile()) {
                if (! self::delete($file->getRealPath())) {
                    return false;
                }
            } elseif (! $file->isDot() && $file->isDir()) {
                self::deleteDirRecursively($file->getRealPath());
                self::deleteEmptyDir($file->getRealPath());
            }
        }

        return self::deleteEmptyDir($path);
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
    public static function getFilesFromDir($path)
    {
        if (! is_dir($path)) {
            return false;
        }

        return new \DirectoryIterator(rtrim($path, '/') . '/');
    }

	/**
     * Write File
     *
     * Writes data to the file specified in the path.
     * Creates a new file if non-existent.
     *
     * @param  string $path File path
     * @param  string $data Data to write
     * @param  string $mode fopen() mode (default: 'wb')
     * @return bool
     */
    public static function writeFile($path, $data, $mode = 'wb')
    {
        if (! $fp = @fopen($path, $mode)) {
            return false;
        }

        flock($fp, LOCK_EX);

        for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result)
        {
            if (($result = fwrite($fp, substr($data, $written))) === false) {
                break;
            }
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return is_int($result);
    }

	/**
     * Get Filenames
     *
     * Reads the specified directory and builds an array containing the filenames.
     * Any sub-folders contained within the specified path are read as well.
     *
     * @param  string    path to source
     * @param  bool    whether to include the path as part of the filename
     * @param  bool    internal variable to determine recursion status - do not use in calls
     * @return mixed
     */
    public static function getFilenames($source_dir, $include_path = false, $_recursion = false)
    {
        static $_filedata = array();

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false) {
                $_filedata = array();
                $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            }

            while (false !== ($file = readdir($fp)))
            {
                if (is_dir($source_dir.$file) && $file[0] !== '.') {
                    self::getFilenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, true);
                }
                elseif ($file[0] !== '.') {
                    $_filedata[] = ($include_path === true) ? $source_dir.$file : $file;
                }
            }

            closedir($fp);
            return $_filedata;
        }

        return false;
    }

	/**
     * Get Directory File Information
     *
     * Reads the specified directory and builds an array containing the filenames,
     * filesize, dates, and permissions
     *
     * Any sub-folders contained within the specified path are read as well.
     *
     * @param  string    path to source
     * @param  bool    Look only at the top level directory specified?
     * @param  bool    internal variable to determine recursion status - do not use in calls
     * @return mixed
     */
    public static function getDirFileInfo($source_dir, $top_level_only = true, $_recursion = false)
    {
        static $_filedata = array();
        $relative_path = $source_dir;

        if ($fp = @opendir($source_dir)) {
            // reset the array and make sure $source_dir has a trailing slash on the initial call
            if ($_recursion === false) {
                $_filedata = array();
                $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            }

            // Used to be foreach (scandir($source_dir, 1) as $file), but scandir() is simply not as fast
            while (false !== ($file = readdir($fp)))
            {
                if (is_dir($source_dir.$file) && $file[0] !== '.' && $top_level_only === false) {
                    self::getDirFileInfo($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, true);
                }
                elseif ($file[0] !== '.') {
                    $_filedata[$file] = self::getFileInfo($source_dir.$file);
                    $_filedata[$file]['relative_path'] = $relative_path;
                }
            }

            closedir($fp);
            return $_filedata;
        }

        return false;
    }

	/**
     * Get File Info
     *
     * Given a file and path, returns the name, path, size, date modified
     * Second parameter allows you to explicitly declare what information you want returned
     * Options are: name, server_path, size, date, readable, writable, executable, fileperms
     * Returns FALSE if the file cannot be found.
     *
     * @param  string    path to file
     * @param  mixed    array or comma separated string of information returned
     * @return mixed
     */
    public static function getFileInfo($file, $returned_values = array('name', 'server_path', 'size', 'date'))
    {
        if (! file_exists($file)) {
            return false;
        }

        if (is_string($returned_values)) {
            $returned_values = explode(',', $returned_values);
        }

        foreach ($returned_values as $key)
        {
            switch ($key)
            {
            case 'name':
                $fileinfo['name'] = basename($file);
                break;
            case 'server_path':
                $fileinfo['server_path'] = $file;
                break;
            case 'size':
                $fileinfo['size'] = filesize($file);
                break;
            case 'date':
                $fileinfo['date'] = filemtime($file);
                break;
            case 'readable':
                $fileinfo['readable'] = is_readable($file);
                break;
            case 'writable':
                $fileinfo['writable'] = is_writable($file);
                break;
            case 'executable':
                $fileinfo['executable'] = is_executable($file);
                break;
            case 'fileperms':
                $fileinfo['fileperms'] = fileperms($file);
                break;
            }
        }

        return $fileinfo;
    }

	/**
     * Get Mime by Extension
     *
     * Translates a file extension into a mime type based on config/Mimes.php.
     * Returns FALSE if it can't determine the type, or open the mime config file
     *
     * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
     * It should NOT be trusted, and should certainly NOT be used for security
     *
     * @param  string $filename File name
     * @return string
     */
    public static function getMimeByExtension($filename)
    {
        static $mimes;

        if (! is_array($mimes)) {
            $mimes = self::getMimes();

            if (empty($mimes)) {
                return false;
            }
        }

        $extension = strtolower(substr(strrchr($filename, '.'), 1));

        if (isset($mimes[$extension])) {
            return is_array($mimes[$extension])
            ? current($mimes[$extension]) // Multiple mime types, just give the first one
            : $mimes[$extension];
        }

        return false;
    }

	/**
	 * Returns the MIME types array from config/Mimes.php
	 *
	 * @return array
	 */
	public static function &getMimes()
	{
        static $_mimes;

        if (empty($_mimes)) {
            $_mimes = file_exists(__DIR__ . '/../Config/Mimes.php')
            ? include __DIR__ . '/../Config/Mimes.php'
            : array();

            if (file_exists(__DIR__ . '/../Config/Mimes.php')) {
                $_mimes = array_merge($_mimes, include __DIR__ . '/../Config/Mimes.php');
            }
        }

        return $_mimes;
	}

}
