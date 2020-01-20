<?php
namespace System\Core;

/**
* Razr Template Engine
*/
use System\Razr\Engine;
use System\Razr\Loader\FilesystemLoader;

/**
* Use Request library
*/
use System\Libraries\Request;

/**
* This is the core of NSY Controller
* Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
*/
class NSY_Controller
{

	/**
	* HMVC & MVC View Folder
	*
	* @param  string $module
	* @param  string $filename
	* @param  array  $vars
	* @return string
	*/
	protected function load_view($module = null, $filename = null, $vars = array())
	{
		// Instantiate Razr Template Engine
		$this->razr = new Engine(new FilesystemLoader(VENDOR_DIR));

		if (is_array($vars) || is_object($vars) || is_filled($filename) ) {
			if(not_filled($module) ) {
				echo $this->razr->render(MVC_VIEW_DIR . $filename . '.php', $vars);
			} else {
				echo $this->razr->render(HMVC_VIEW_DIR . $module . '/views/' . $filename . '.php', $vars);
			}
		} else
		{
			$var_msg = 'The variable in the <mark>load_view()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return $this;
	}

	/**
	* Template Directory
	*
	* @param  string $filename
	* @param  array  $vars
	* @return string
	*/
	protected function load_template($filename = null, $vars = array())
	{
		// Instantiate Razr Template Engine
		$this->razr = new Engine(new FilesystemLoader(VENDOR_DIR));

		if (is_array($vars) || is_object($vars) ) {
			echo $this->razr->render(SYS_TMP_DIR . $filename . '.php', $vars);
		} else
		{
			$var_msg = 'The variable in the <mark>load_template()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return $this;
	}

	/**
	* Method for variables sequence (vars)
	*
	* @param  array $variables
	* @return array
	*/
	protected function vars($variables = array())
	{
		if (is_array($variables) || is_object($variables) ) {
			$this->variables = $variables;
		} else
		{
			$var_msg = 'The variable in the <mark>vars(<strong>variables</strong>)->sequence()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return $this;
	}

	/**
	* Method for variables sequence (bind)
	*
	* @param  string $bind
	* @return string
	*/
	protected function bind($bind = null)
	{
		if (is_filled($bind) ) {
			$this->bind = $bind;
		} else
		{
			$var_msg = 'The value that binds in the <mark>bind(<strong>value</strong>)->vars()->sequence()</mark> is empty or undefined';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return $this;
	}

	/**
	* Helper for NSY_Controller to create a sequence of the named placeholders
	*
	* @return array
	*/
	protected function sequence()
	{
		$in = '';
		if (is_array($this->variables) || is_object($this->variables) ) {
			foreach ($this->variables as $i => $item)
			{
				$key = $this->bind.$i;
				$in .= $key.',';
				$in_params[$key] = $item; // collecting values into key-value array
			}
		} else
		{
			$var_msg = 'The variable in the <mark>vars(<strong>variables</strong>)->sequence()</mark> is improper or not an array';
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
	protected function is_request_put()
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
	protected function is_request_delete()
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
	protected function is_request_get()
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
	protected function is_request_post()
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
	protected function get_parsed_array($filters = array(), $val = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post()->as_array($filters, $val);
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get()->as_array($filters, $val);
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del()->as_array($filters, $val);
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_object($filters = array(), $val = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post()->as_object($filters, $val);
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get()->as_object($filters, $val);
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del()->as_object($filters, $val);
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_json($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_json();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_json();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_json();
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_string($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_string();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_string();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_string();
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_integer($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_integer();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_integer();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_integer();
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_float($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_float();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_float();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_float();
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_boolean($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_boolean();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_boolean();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_boolean();
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_ip($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_ip();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_ip();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_ip();
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_url($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_url();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_url();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_url();
		} elseif ( $this->is_request_put() ) {
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
	protected function get_parsed_email($params = null)
	{
		if ( $this->is_request_post() ) {
			$req_post = Request::input('POST');
			$arr = $req_post($params)->as_email();
		} elseif ( $this->is_request_get() ) {
			$req_get = Request::input('GET');
			$arr = $req_get($params)->as_email();
		} elseif ( $this->is_request_delete() ) {
			$req_del = Request::input('DELETE');
			$arr = $req_del($params)->as_email();
		} elseif ( $this->is_request_put() ) {
			$req_put = Request::input('PUT');
			$arr = $req_put($params)->as_email();
		}

		return $arr;
	}

	/**
	 * Get parsed content type
	 * @return mixed
	 */
	protected function get_content_type()
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
	protected function post($param = null)
	{
		if ( is_filled($param) ) {
			$result = isset($_POST[$param]) ? $_POST[$param] : null;
		} else {
			$var_msg = 'The variable in the <mark>$this->post(<strong>variable</strong>)</mark> is improper or not an array';
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
	protected function get($param = null)
	{
		if ( is_filled($param) ) {
			$result = isset($_GET[$param]) ? $_GET[$param] : null;
		} else {
			$var_msg = 'The variable in the <mark>$this->get(<strong>variable</strong>)</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return $result;
	}

}
