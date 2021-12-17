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
 * Check if it's a PUT request
 * @return boolean
 */
function is_request_put()
{
	if ( Request::isPut() == true ) {
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
	if ( Request::isDelete() == true ) {
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
	if ( Request::isGet() == true ) {
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
	if ( Request::isPost() == true ) {
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
		$arr = $req_post()->asArray($filters, $val);
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get()->asArray($filters, $val);
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del()->asArray($filters, $val);
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put()->asArray($filters, $val);
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
		$arr = $req_post()->asObject($filters, $val);
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get()->asObject($filters, $val);
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del()->asObject($filters, $val);
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put()->asObject($filters, $val);
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
		$arr = $req_post($params)->asJson();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asJson();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asJson();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asJson();
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
		$arr = $req_post($params)->asString();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asString();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asString();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asString();
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
		$arr = $req_post($params)->asInteger();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asInteger();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asInteger();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asInteger();
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
		$arr = $req_post($params)->asFloat();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asFloat();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asFloat();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asFloat();
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
		$arr = $req_post($params)->asBoolean();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asBoolean();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asBoolean();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asBoolean();
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
		$arr = $req_post($params)->asIp();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asIp();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asIp();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asIp();
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
		$arr = $req_post($params)->asUrl();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asUrl();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asUrl();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asUrl();
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
		$arr = $req_post($params)->asEmail();
	} elseif ( is_request_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asEmail();
	} elseif ( is_request_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asEmail();
	} elseif ( is_request_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asEmail();
	}

	return $arr;
}

/**
 * Get parsed content type
 * @return mixed
 */
function get_content_type()
	{
		$data = Request::getContentType();

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
function deposer($param = null)
{
	if ( is_filled($param) ) {
		$result = isset($_FILE[$param]) ? $_FILE[$param] : null;
	} else {
		$var_msg = 'The variable in the <mark>get(<strong>variable</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}

	return $result;
}
