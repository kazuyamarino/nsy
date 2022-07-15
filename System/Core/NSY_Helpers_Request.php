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
function request_is_put()
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
function request_is_delete()
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
function request_is_get()
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
function request_is_post()
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
function request_as_array($filters = array(), $val = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post()->asArray($filters, $val);
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get()->asArray($filters, $val);
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del()->asArray($filters, $val);
	} elseif ( request_is_put() ) {
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
function request_as_object($filters = array(), $val = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post()->asObject($filters, $val);
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get()->asObject($filters, $val);
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del()->asObject($filters, $val);
	} elseif ( request_is_put() ) {
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
function request_as_json($params = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asJson();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asJson();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asJson();
	} elseif ( request_is_put() ) {
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
function request_as_string($params = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asString();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asString();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asString();
	} elseif ( request_is_put() ) {
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
function request_as_integer($params = 0)
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asInteger();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asInteger();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asInteger();
	} elseif ( request_is_put() ) {
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
function request_as_float($params = 0)
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asFloat();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asFloat();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asFloat();
	} elseif ( request_is_put() ) {
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
function request_as_boolean($params = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asBoolean();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asBoolean();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asBoolean();
	} elseif ( request_is_put() ) {
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
function request_as_ip($params = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asIp();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asIp();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asIp();
	} elseif ( request_is_put() ) {
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
function request_as_url($params = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asUrl();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asUrl();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asUrl();
	} elseif ( request_is_put() ) {
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
function request_as_email($params = '')
{
	if ( request_is_post() ) {
		$req_post = Request::input('POST');
		$arr = $req_post($params)->asEmail();
	} elseif ( request_is_get() ) {
		$req_get = Request::input('GET');
		$arr = $req_get($params)->asEmail();
	} elseif ( request_is_delete() ) {
		$req_del = Request::input('DELETE');
		$arr = $req_del($params)->asEmail();
	} elseif ( request_is_put() ) {
		$req_put = Request::input('PUT');
		$arr = $req_put($params)->asEmail();
	}

	return $arr;
}

/**
 * Get parsed content type
 * @return mixed
 */
function request_content_type()
{
	$data = Request::getContentType();

	return $data;
}
