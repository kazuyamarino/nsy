<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

/**
* Use Request library
*/
use System\Libraries\Request;

/**
* Helper for create a sequence of the named placeholders
*
* @return array
*/
function sequence($bind, $variables)
{
	$in = '';
	if (is_array($variables) || is_object($variables) || is_filled($bind) ) {
		foreach ($variables as $i => $item)
		{
			$key = $bind.$i;
			$in .= $key.',';
			$in_params[$key] = $item; // collecting values into key-value array
		}
	} else
	{
		$var_msg = 'The variable in the <mark>sequence(<strong>bind</strong>, <strong>variables</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}
	$in = rtrim($in, ','); // example = :id0,:id1,:id2

	return [$in, $in_params];
}

/**
 * Check if it's a PUT request
 * @return boolean
 */
function is_request_put()
{
	if ( Request::is_put() == true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if it's a DELETE request
 * @return boolean
 */
function is_request_delete()
{
	if ( Request::is_delete() == true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if it's a GET request
 * @return boolean
 */
function is_request_get()
{
	if ( Request::is_get() == true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if it's a POST request
 * @return boolean
 */
function is_request_post()
{
	if ( Request::is_post() == true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Parse array data from request params
 * @param  array  $filters
 * @param  string  $val
 * @return array
 */
function get_parsed_array($filters = array(), $val = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post()->as_array($filters, $val);
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get()->as_array($filters, $val);
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del()->as_array($filters, $val);
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put()->as_array($filters, $val);
	}

	return $arr;
}

/**
 * Parse object data from request params
 * @param  array  $filters
 * @param  string  $val
 * @return array
 */
function get_parsed_object($filters = array(), $val = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post()->as_object($filters, $val);
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get()->as_object($filters, $val);
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del()->as_object($filters, $val);
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put()->as_object($filters, $val);
	}

	return $arr;
}

/**
 * Get data from request params and parse to json
 * @param  mixed $params
 * @return string
 */
function get_parsed_json($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_json();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_json();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_json();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_json();
	}

	return $arr;
}

/**
 * Get data from request params and parse to string
 * @param  string $params
 * @return string
 */
function get_parsed_string($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_string();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_string();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_string();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_string();
	}

	return $arr;
}

/**
 * Get data from request params and parse to int
 * @param  int $params
 * @return int
 */
function get_parsed_integer($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_integer();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_integer();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_integer();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_integer();
	}

	return $arr;
}

/**
 * Get data from request params and parse to float
 * @param  integer $params
 * @return float
 */
function get_parsed_float($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_float();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_float();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_float();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_float();
	}

	return $arr;
}

/**
 * Get data from request params and parse to bool
 * @param  boolean $params
 * @return boolean
 */
function get_parsed_boolean($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_boolean();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_boolean();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_boolean();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_boolean();
	}

	return $arr;
}

/**
 * Get data from request params and parse to ip
 * @param  string $params
 * @return string
 */
function get_parsed_ip($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_ip();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_ip();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_ip();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_ip();
	}

	return $arr;
}

/**
 * Get data from request params and parse to url
 * @param  string $params
 * @return string
 */
function get_parsed_url($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_url();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_url();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_url();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_url();
	}

	return $arr;
}

/**
 * Get data from request params and parse to email
 * @param  string $params
 * @return string
 */
function get_parsed_email($params = null)
{
	if ( is_request_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->as_email();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->as_email();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->as_email();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->as_email();
	}

	return $arr;
}

/**
 * Get parsed content type
 * @return mixed
 */
function get_content_type()
	{
		$data = Request::get_content_type();

		return $data;
	}

// ------------------------------------------------------------------------

/**
* The PHP superglobals $_GET and $_POST are used to collect form-data.
*/
/**
* Post method
* @param  mixed|int $param
* @return mixed|int
*/
function post($param = null)
{
	if ( is_filled($param) ) {
		$result = isset($_POST[$param]) ? $_POST[$param] : null;
	} else {
		$var_msg = 'The variable in the <mark>post(<strong>variable</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}

	return $result;
}

/**
* Get method
* @param  mixed $param
* @return mixed
*/
function get($param = null)
{
	if ( is_filled($param) ) {
		$result = isset($_GET[$param]) ? $_GET[$param] : null;
	} else {
		$var_msg = 'The variable in the <mark>get(<strong>variable</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}

	return $result;
}

/**
* File method
* @param  mixed $param
* @return mixed
*/
function files($param = null)
{
	if ( is_filled($param) ) {
		$result = isset($_FILES[$param]) ? $_FILES[$param] : null;
	} else {
		$var_msg = 'The variable in the <mark>files(<strong>variable</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}

	return $result;
}
