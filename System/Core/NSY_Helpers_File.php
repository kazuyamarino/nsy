<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\File; // File Class

/**
 * File Helpers
 */
if (! function_exists('check_file')) {
	/**
     * Check if a file exists in a path or url.
     *
     * @since 1.1.3
     *
     * @param string $file → path or file url
     *
     * @return bool
     */
	function check_file($file)
	{
		$result = File::exists($file);

		return $result;
	}
}

if (! function_exists('create_file')) {
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
	function create_file($path, $data, $mode = 'wb')
	{
		$result = File::writeFile($path, $data, $mode = 'wb');

		return $result;
	}
}

if (! function_exists('delete_file')) {
	/**
     * Delete file.
     *
     * @since 1.1.3
     *
     * @param string $file → file path
     *
     * @return bool
     */
	function delete_file($file)
	{
		$result = File::delete($file);

		return $result;
	}
}

if (! function_exists('create_dir')) {
	/**
     * Create directory.
     *
     * @since 1.1.3
     *
     * @param string $path → path where to create directory
     *
     * @return bool
     */
	function create_dir($path)
	{
		$result = File::createDir($path);

		return $result;
	}
}

if (! function_exists('copy_dir_recur')) {
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
	function copy_dir_recur($from, $to)
	{
		$result = File::copyDirRecursively($from, $to);

		return $result;
	}
}

if (! function_exists('delete_dir')) {
	/**
     * Delete empty directory.
     *
     * @since 1.1.3
     *
     * @param string $path → path to delete
     *
     * @return bool
     */
	function delete_dir($path)
	{
		$result = File::deleteEmptyDir($path);

		return $result;
	}
}

if (! function_exists('delete_dir_recur')) {
	/**
     * Delete directory recursively.
     *
     * @since 1.1.3
     *
     * @param string $path → path to delete
     *
     * @return bool
     */
	function delete_dir_recur($path)
	{
		$result = File::deleteDirRecursively($path);

		return $result;
	}
}

if (! function_exists('get_files_from_dir')) {
	/**
     * Get files from directory.
     *
     * @since 1.1.3
     *
     * @param string $path → path where get files
     *
     * @return object|false →
     */
	function get_files_from_dir($path)
	{
		$result = File::getFilesFromDir($path);

		return $result;
	}
}

if (! function_exists('get_filenames')) {
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
	function get_filenames($source_dir, $include_path = false, $_recursion = false)
	{
		$result = File::getFilenames($source_dir, $include_path = false, $_recursion = false);

		return $result;
	}
}

if (! function_exists('get_dir_file_info')) {
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
	function get_dir_file_info($source_dir, $top_level_only = true, $_recursion = false)
	{
		$result = File::getDirFileInfo($source_dir, $top_level_only = true, $_recursion = false);

		return $result;
	}
}

if (! function_exists('get_file_info')) {
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
	function get_file_info($file, $returned_values = array('name', 'server_path', 'size', 'date'))
	{
		$result = File::getFileInfo($file, $returned_values = array('name', 'server_path', 'size', 'date'));

		return $result;
	}
}

if (! function_exists('get_mime_by_extension')) {
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
	function get_mime_by_extension($filename)
	{
		$result = File::getMimeByExtension($filename);

		return $result;
	}
}

if (! function_exists('get_mimes')) {
	/**
	 * Returns the MIME types array from config/Mimes.php
	 *
	 * @return array
	 */
	function get_mimes()
	{
		$result = File::getMimes();

		return $result;
	}
}
