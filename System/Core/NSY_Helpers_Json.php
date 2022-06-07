<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Json; // Json Class
use System\Libraries\JsonLastError; // JsonLastError Class

/**
 * Data Conversion Helpers
 */
if (! function_exists('json_fetch')) {
	/**
	* Fetch data to json format
	* @param  array $data
	* @param  int $status
	* @return string
	*/
	function json_fetch($data = array(), $status = 0)
	{
		$json_data = $data;
		$json_result = json_encode($json_data);

		http_response_code($status);
		return $json_result;
	}
}

if (! function_exists('json_array_to_file')) {
	/**
     * Creating JSON file from array.
     *
     * @param array  $array → array to be converted to JSON
     * @param string $file  → path to the file
     *
     * @return boolean → true if the file is created without errors
     */
	function json_array_to_file($array, $file)
	{
		$json = Json::arrayToFile($array, $file);

		return $json;
	}
}

if (! function_exists('json_file_to_array')) {
	/**
     * Save to array the JSON file content.
     *
     * @param string $file → path or external url to JSON file
     *
     * @return array|false
     */
	function json_file_to_array($file)
	{
		$json = Json::fileToArray($file);

		return $json;
	}
}

if (! function_exists('json_check_error')) {
	/**
	 * Check for errors
	 * @return array|null
	 */
	function json_check_error()
	{
		$lastError = JsonLastError::check();

		if (!is_null($lastError)) {
		    return $lastError;
		}
	}
}

if (! function_exists('json_collect_error')) {
	/**
	 * Get collection of JSON errors
	 * @return array
	 */
	function json_collect_error()
	{
		$jsonLastErrorCollection = JsonLastError::getCollection();

		return $jsonLastErrorCollection;
	}
}
