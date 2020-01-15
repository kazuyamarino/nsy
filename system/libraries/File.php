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
    public static function write_file($path, $data, $mode = 'wb')
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
     * @return array
     */
    public static function get_filenames($source_dir, $include_path = false, $_recursion = false)
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
                    self::get_filenames($source_dir.$file.DIRECTORY_SEPARATOR, $include_path, true);
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
     * @return array
     */
    public static function get_dir_file_info($source_dir, $top_level_only = true, $_recursion = false)
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
                    self::get_dir_file_info($source_dir.$file.DIRECTORY_SEPARATOR, $top_level_only, true);
                }
                elseif ($file[0] !== '.') {
                    $_filedata[$file] = self::get_file_info($source_dir.$file);
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
     * @return array
     */
    public static function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date'))
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
                $fileinfo['writable'] = is_really_writable($file);
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
    public static function get_mime_by_extension($filename)
    {
        static $mimes;

        if (! is_array($mimes)) {
            $mimes = self::get_mimes();

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
	public static function &get_mimes()
	{
	    static $_mimes;

	    if (empty($_mimes)) {
	        $_mimes = file_exists(__DIR__ . '/../config/Mimes.php')
	        ? include __DIR__ . '/../config/Mimes.php'
	        : array();

	        if (file_exists(__DIR__ . '/../config/Mimes.php')) {
	            $_mimes = array_merge($_mimes, include __DIR__ . '/../config/Mimes.php');
	        }
	    }

	    return $_mimes;
	}

	/**
     * Force Download
     *
     * Generates headers that force a download to happen
     *
     * @param  string    filename
     * @param  mixed    the data to be downloaded
     * @param  bool    whether to try and send the actual file MIME type
     * @return void
     */
    public static function force_download($filename = '', $data = '', $set_mime = false)
    {
        if ($filename === '' OR $data === '') {
            return;
        }
        elseif ($data === null) {
            if (! @is_file($filename) OR ($filesize = @filesize($filename)) === false) {
                return;
            }

            $filepath = $filename;
            $filename = explode('/', str_replace(DIRECTORY_SEPARATOR, '/', $filename));
            $filename = end($filename);
        }
        else
        {
            $filesize = strlen($data);
        }

        // Set the default MIME type to send
        $mime = 'application/octet-stream';

        $x = explode('.', $filename);
        $extension = end($x);

        if ($set_mime === true) {
            if (count($x) === 1 OR $extension === '') {
                /* If we're going to detect the MIME type,
                * we'll need a file extension.
                */
                return;
            }

            // Load the mime types
            $mimes =& self::get_mimes();

            // Only change the default MIME if we can find one
            if (isset($mimes[$extension])) {
                $mime = is_array($mimes[$extension]) ? $mimes[$extension][0] : $mimes[$extension];
            }
        }

        /* It was reported that browsers on Android 2.1 (and possibly older as well)
        * need to have the filename extension upper-cased in order to be able to
        * download it.
        *
        * Reference: http://digiblog.de/2011/04/19/android-and-the-download-file-headers/
        */
        if (count($x) !== 1 && isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/Android\s(1|2\.[01])/', $_SERVER['HTTP_USER_AGENT'])) {
            $x[count($x) - 1] = strtoupper($extension);
            $filename = implode('.', $x);
        }

        if ($data === null && ($fp = @fopen($filepath, 'rb')) === false) {
            return;
        }

        // Clean output buffer
        if (ob_get_level() !== 0 && @ob_end_clean() === false) {
            @ob_clean();
        }

        // Generate the server headers
        header('Content-Type: '.$mime);
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Expires: 0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$filesize);
        header('Cache-Control: private, no-transform, no-store, must-revalidate');

        // If we have raw data - just dump it
        if ($data !== null) {
            exit($data);
        }

        // Flush 1MB chunks of data
        while ( ! feof($fp) && ($data = fread($fp, 1048576)) !== false)
        {
            echo $data;
        }

        fclose($fp);
        exit;
    }

}
