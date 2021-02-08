<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

/**
* Use NSY_XSS_Filter class
*/
use System\Core\NSY_XSS_Filter;

/**
* Use NSY_CSRF class
*/
use System\Core\NSY_CSRF;

/**
 * Security Helpers
 * @var mixed
 */
if (! function_exists('secure_input')) {
	/**
	* Secure Input Element
	* @param  string $data
	* @return string
	*/
	function secure_input($data = null)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}
}

if (! function_exists('secure_form')) {
	/**
	* Secure Form
	* @param  string $form
	* @return void
	*/
	function secure_form($form = null)
	{
		if (is_array($form) || is_object($form)) {
			foreach ($form as $key => $value) {
				$form[$key] = secure_input($value);
			}
		}
	}
}

if (! function_exists('csrf_token')) {
	/**
	* Return only CSRF Token
	* @return string
	*/
	function csrf_token()
	{
		if(config_app('csrf_token') === 'true') {
			$csrf_token = NSY_CSRF::generate('csrf_token');

			return $csrf_token;
		} elseif(config_app('csrf_token') === 'false') {
			$var_msg = "CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>System/Config/App.php</strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('form_csrf_token')) {
	/**
	* Return CSRF Input form with Token
	* @return string
	*/
	function form_csrf_token()
	{
		if(config_app('csrf_token') === 'true') {
			$csrf_token = NSY_CSRF::generate('csrf_token');

			return '<input type="hidden" name="csrf_token" value=' . $csrf_token . '">';
		} elseif(config_app('csrf_token') === 'false') {
			$var_msg = "CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>System/Config/App.php</strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('xss_filter')) {
	/**
	* XSS Filter
	* @param  string $value
	* @return string
	*/
	function xss_filter($value = null)
	{
		$xss_filter = new NSY_XSS_Filter();
		$string = $xss_filter->filter_it($value);

		return $string;
	}
}

if (! function_exists('allow_http')) {
	/**
	* Allow http
	* @return void
	*/
	function allow_http()
	{
		$allow_http = new NSY_XSS_Filter();
		$func = $allow_http->allow_http();
	}
}

if (! function_exists('disallow_http')) {
	/**
	* Disallow http
	* @return void
	*/
	function disallow_http()
	{
		$disallow_http = new NSY_XSS_Filter();
		$func = $disallow_http->disallow_http();
	}
}

if (! function_exists('remove_get_parameters')) {
	/**
	* Remove url get parameter
	* @param  string $url
	* @return string
	*/
	function remove_get_parameters($url = null)
	{
		$remove_get_parameters = new NSY_XSS_Filter();
		$func = $remove_get_parameters->remove_get_parameters($url);

		return $func;
	}
}
