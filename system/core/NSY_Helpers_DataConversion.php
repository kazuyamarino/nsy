<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

use System\Libraries\Json; // Json Class
use System\Libraries\JsonLastError; // JsonLastError Class

/**
 * Data Conversion Helpers
 * @var mixed
 */
 if (! function_exists('array_flatten')) {
 	/**
 	* PHP array_flatten() function. Convert a multi-dimensional array into a single-dimensional array
 	* https://gist.github.com/SeanCannon/6585889#gistcomment-2922278
 	* @param  array $items
 	* @return array
 	*/
 	function array_flatten($items)
 	{
 		if (! is_array($items)) {
 			return [$items];
 		}

 		return array_reduce(
 			$items, function ($carry, $item) {
 				return array_merge($carry, array_flatten($item));
 			}, []
 		);
 	}
 }

 if (! function_exists('fetch_json')) {
 	/**
 	* Fetch data to json format
 	* @param  array $data
 	* @param  int $status
 	* @return string
 	*/
 	function fetch_json($data = array(), $status = null)
 	{
 		$json_data = $data;
 		$json_result = json_encode($json_data);

 		http_response_code($status);
 		return $json_result;
 	}
 }

if (! function_exists('json_array_to_file')) {
	/**
	 * Creating JSON file from array
	 * @return boolean
	 */
	function json_array_to_file($array, $pathfile)
	{
		$json = Json::array_to_file($array, $pathfile);

		return $json;
	}
}

if (! function_exists('json_file_to_array')) {
	/**
	 * Create array from the JSON file content
	 * @return array|false
	 */
	function json_file_to_array($pathfile)
	{
		$json = Json::file_to_array($pathfile);

		return $json;
	}
}

if (! function_exists('json_last_error')) {
	/**
	 * Check for errors
	 * @return array|null
	 */
	function json_last_error()
	{
		$lastError = JsonLastError::check();

		if (!is_null($lastError)) {
		    return $lastError;
		}
	}
}

if (! function_exists('json_collection_error')) {
	/**
	 * Get collection of JSON errors
	 * @return array
	 */
	function json_collection_error()
	{
		$jsonLastErrorCollection = JsonLastError::get_collection();

		return $jsonLastErrorCollection;
	}
}
