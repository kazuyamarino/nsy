<?php
use Core\NSY_XSS_Filter;
use Core\NSY_CSRF;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_System {

	/**
	 * Defined variable for NSY Core System
	 */
	public function __construct() {
		// turn on output buffering
		ob_start();

		// set the default public/css/js path
		define('CSS_DIR', base_url() . config_app('public_dir') . config_app('css_dir') . '/'); // CSS PATH
		define('JS_DIR', base_url() . config_app('public_dir') . config_app('js_dir') . '/'); // JS PATH
		define('IMG_DIR', base_url() . config_app('public_dir') . config_app('img_dir') . '/'); // IMG PATH

		//set the default view path
		define('SYS_TMP_DIR', config_app('tmp_dir') . '/');
		define('MVC_VIEW_DIR', config_app('mvc_dir') . '/');
		define('HMVC_VIEW_DIR', config_app('hmvc_dir') . '/');

		// set a default language
		define('LANGUAGE_CODE', config_app('locale'));

		// set prefix for sessions
		define('SESSION_PREFIX', config_app('session_prefix'));

		// optional create a constant for the name of the site
		define('SITETITLE', config_site('sitetitle'));

		// optional set a site author
		define('SITEAUTHOR', config_site('siteauthor'));

		// optional set a site keywords
		define('SITEKEYWORDS', config_site('sitekeywords'));

		// optional set a site description
		define('SITEDESCRIPTION', config_site('sitedesc'));

		// optional set a site email address
		define('SITEEMAIL', config_site('siteemail'));

		// set timezone
		date_default_timezone_set(config_app('timezone'));

		// Aliasing NSY_AssetManager class name
		class_alias('Core\NSY_AssetManager', 'add');

		// Aliasing Assets class name
		class_alias('Assets', 'pull');

		// Aliasing NSY_Router class name
		class_alias('Core\NSY_Router', 'route');

		// start session
		session_start();
	}

}

//---------------------------------------------------------------------

/**
 * stringify_attributes function
 *
 * @return string
 */
function stringify_attributes($attributes, $js = FALSE)
{
	if (is_object($attributes) && count($attributes) > 0)
	{
		$attributes = (array) $attributes;
	}

	if (is_array($attributes))
	{
		$atts = '';
		if (count($attributes) === 0)
		{
			return $atts;
		}
		foreach ($attributes as $key => $val)
		{
			if ($js)
			{
				$atts .= $key.'='.$val.',';
			}
			else
			{
				$atts .= ' '.$key.'="'.$val.'"';
			}
		}
		return rtrim($atts, ',');
	}
	elseif (is_string($attributes) && strlen($attributes) > 0)
	{
		return ' '.$attributes;
	}

	return $attributes;
}

// ------------------------------------------------------------------------

/**
 * Returns the MIME types array from config/mimes.php
 *
 * @return	array
 */
function &get_mimes()
{
	static $_mimes;

	if (empty($_mimes))
	{
		$_mimes = file_exists(__DIR__ . '/../config/mimes.php')
			? include(__DIR__ . '/../config/mimes.php')
			: array();

		if (file_exists(__DIR__ . '/../config/mimes.php'))
		{
			$_mimes = array_merge($_mimes, include(__DIR__ . '/../config/mimes.php'));
		}
	}

	return $_mimes;
}

// ------------------------------------------------------------------------

/**
 * Get config value from system/config/database.php
 */
function config_db($d1 = null,$d2 = null) {
	$database = require(__DIR__ . '/../config/database.php');
	if ( not_filled($d2) ) {
		return $database[$d1];
	} else {
		return $database['connections'][$d1][$d2];
	}
}

/**
 * Get config value from system/config/database.php
 */
function config_db_sec($d1 = null,$d2 = null) {
	$database = require(__DIR__ . '/../config/database.php');
	if ( not_filled($d2) ) {
		return $database[$d1];
	} else {
		return $database['connections_sec'][$d1][$d2];
	}
}

// ------------------------------------------------------------------------

/**
 * Get config value from system/config/app.php
 */
function config_app($d1 = null) {
	$app = require(__DIR__ . '/../config/app.php');

	return $app[$d1];
}

// ------------------------------------------------------------------------

/**
 * Get config value from system/config/site.php
 */
function config_site($d1 = null) {
	$site = require(__DIR__ . '/../config/site.php');

	return $site[$d1];
}

// ------------------------------------------------------------------------

if ( ! function_exists('redirect'))
{
	/**
	 * Redirect URL
	 */
	function redirect($url = null) {
		header('location:'. base_url($url));
		exit();
	}
}

if ( ! function_exists('redirect_back'))
{
 	/**
 	 * Redirect Back URL
 	 */
	function redirect_back() {
	    header('location: ' . $_SERVER['HTTP_REFERER']);
	    exit();
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('fetch_json'))
{
	/**
	 * Fetching to json format
	 *
	 * @return string
	 */
	function fetch_json($data = null) {
		$json_data = $data;
		$json_result = json_encode($json_data);

		return $json_result;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('secure_input'))
{
	/**
	 * Secure Input Element
	 *
	 * @return string
	 */
	function secure_input($data = null) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}
}

if ( ! function_exists('secure_form'))
{
	/**
	 * Secure Form
	 *
	 * @return string
	 */
	function secure_form($form = null) {
		foreach ($form as $key => $value) {
			$form[$key] = $this->secure_input($value);
		}
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('csrf_token'))
{
	/**
	 * CSRF Token
	 *
	 * @return string
	 */
	function csrf_token() {
		if(config_app('csrf_token') === 'true') {
			$csrf_token = NSY_CSRF::generate( 'csrf_token' );

			return $csrf_token;
		} elseif(config_app('csrf_token') === 'false') {
			return '<p>CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>system/config/app.php</strong></p>';
			exit();
		}
	}
}

if ( ! function_exists('form_csrf_token'))
{
	/**
	 * CSRF Form Token
	 *
	 * @return string
	 */
	function form_csrf_token() {
		if(config_app('csrf_token') === 'true') {
			$csrf_token = NSY_CSRF::generate( 'csrf_token' );

			return '<input type="hidden" name="csrf_token" value=' . $csrf_token . '">';
		} elseif(config_app('csrf_token') === 'false') {
			return '<p>CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>system/config/app.php</strong></p>';
			exit();
		}
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('xss_filter'))
{
	/**
	 * XSS Filter
	 *
	 * @return string
	 */
	function xss_filter($value = null) {
		$xss_filter = new NSY_XSS_Filter();
		$string = $xss_filter->filter_it($value);

		return $string;
	}
}

if ( ! function_exists('allow_http'))
{
	/**
	 * Allow http
	 *
	 * @return string
	 */
	function allow_http() {
		$allow_http = new NSY_XSS_Filter();
		$func = $allow_http->allow_http();

		return $func;
	}
}

if ( ! function_exists('disallow_http'))
{
	/**
	 * Disallow http
	 *
	 * @return string
	 */
	function disallow_http() {
		$disallow_http = new NSY_XSS_Filter();
		$func = $disallow_http->disallow_http();

		return $func;
	}
}

if ( ! function_exists('remove_get_parameters'))
{
	/**
	 * Remove url get parameter
	 *
	 * @return string
	 */
	function remove_get_parameters($url = null) {
		$remove_get_parameters = new NSY_XSS_Filter();
		$func = $remove_get_parameters->remove_get_parameters($url);

		return $func;
	}
}

// ------------------------------------------------------------------------

/**
 * The PHP $_SESSION are used to create, show, unset session.
 */
if ( ! function_exists('add_session'))
{
	function add_session($index = null, $value = null) {
		$_SESSION[$index] = $value;

		return $_SESSION[$index];
	}
}

if ( ! function_exists('show_session'))
{
	function show_session($index = null) {
		if(isset($_SESSION[$index])) {
			return $_SESSION[$index];
		} else {
			return null;
			exit();
		}
	}
}

if ( ! function_exists('unset_session'))
{
	function unset_session($index = null) {
		unset($_SESSION[$index]);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('ternary'))
{
	/**
	 * PHP Shorthand If/Else Using Ternary Operators
	 */
	function ternary($condition = null, $result_one = null, $result_two = null) {
		$result = ($condition ? $result_one : $result_two);

		return $result;
	}
}

// ------------------------------------------------------------------------

/**
 * The PHP superglobals $_GET and $_POST are used to collect form-data.
 */
if ( ! function_exists('post'))
{
	function post($param = null) {
		 $result = isset($_POST[$param]) ? $_POST[$param] : null;

		 return $result;
	}
}

if ( ! function_exists('get'))
{
	function get($param = null) {
		 $result = isset($_GET[$param]) ? $_GET[$param] : null;

		 return $result;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('not_filled'))
{
	/**
	 * Function for basic field validation (present and neither empty nor only white space
	 */
	function not_filled($str = null) {
		if (!empty($str)) {
			return false;
			exit();
		} else {
			if (is_array($str)) {
				return (!isset($str) || empty($str));
			} else {
				return (!isset($str) || $str == '' || empty($str));
			}
		}
	}
}

if ( ! function_exists('is_filled'))
{
	/**
	 * Function for basic field validation (present and neither filled nor not empty)
	 */
	function is_filled($str = null) {
		if (!isset($str)) {
			return false;
			exit();
		} else {
			if (is_array($str)) {
				return (isset($key) || !empty($str));
			} else {
				return (isset($key) || !empty($str));
			}
		}
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('aurora'))
{
	/**
	 * Export File
	 */
	function aurora($ext = null, $name = null, $sep = null, $h = null, $d = null, $s = null) {
		if ( not_filled($ext) ) {
			echo '<p>File extension not yet filled</p>';
			echo '<p>aurora(<strong><i>file_extension</i></strong>, filename, separator, header, data, string_delimiter);</p>';
			exit();
		} elseif ( not_filled($name) ) {
			echo '<p>Filename not yet filled</p>';
			echo '<p>aurora(file_extension, <strong><i>filename</i></strong>, separator, header, data, string_delimiter);</p>';
			exit();
		} elseif ( not_filled($sep) ) {
			echo '<p>Separator not yet filled</p>';
			echo '<p>aurora(file_extension, filename, <strong><i>separator</i></strong>, header, data, string_delimiter);</p>';
			exit();
		} elseif ( not_filled($h) ) {
			echo '<p>Header of the table undefined</p>';
			echo '<p>aurora(file_extension, filename, separator, <strong><i>header</i></strong>, data, string_delimiter);</p>';
			exit();
		} elseif ( not_filled($d) ) {
			echo '<p>Record of data empty or unreadable</p>';
			echo '<p>aurora(file_extension, filename, separator, header, <strong><i>data</i></strong>, string_delimiter);</p>';
			exit();
		} else {
			// export filename
			$filename  = $name;

			// separator
			if ( $sep == 'tab' ) {
				$separator = "\011";
			} elseif ( $sep == 'comma' ) {
				$separator = "\054";
			} elseif ( $sep == 'semicolon' ) {
				$separator = "\073";
			} elseif ( $sep == 'space' ) {
				$separator = "\040";
			} elseif ( $sep == 'dot' ) {
				$separator = "\056";
			} elseif ( $sep == 'pipe' ) {
				$separator = "\174";
			} else {
				echo '<p>There is no such separator name (<strong>example:</strong> tab, comma, semicolon, space, pipe, &amp; dot)</p>';
				echo '<p>aurora(file_extension, filename, <strong><i>separator</i></strong>, header, data, string_delimiter);</p>';
				exit();
			}

			// string delimiter (double = "" & single = '')
			if ( $s == 'double' ) {
				$s = "\042";
			} elseif ( $s == 'single' ) {
				$s = "\047";
			} elseif ( $s == null ) {
				$s = null;
			} else {
				echo '<p>There is no such string delimiter name (<strong>example:</strong> "double" for double quote, &amp; \'single\' for singlequote)</p>';
				echo '<p>aurora(file_extension, filename, separator, header, data, <strong><i>string_delimiter</i></strong>);</p>';
				exit();
			}

			// header file text (.txt)
			if ( $ext == 'txt' || $ext == 'csv' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'ods' ) {
				// display header
				$i = 0;
				$len_h = count($h);
				foreach ( $h as $key_h => $val_h ) {
					if ($i == $len_h - 1) {
						$roles[] = $s.$val_h.$s;
					} else {
						$roles[] = $s.$val_h.$s.$separator;
					}
					$i++;
				}

				// newline
				$roles[] = "\r\n";

				// display records
				foreach ( $d as $key_d => $val_d ) {
					for ($x = 0; $x <= $len_h - 1; $x++) {
						if ($x == $len_h - 1) {
							$roles[] = $s.$val_d[$x].$s;
						} else {
							$roles[] = $s.$val_d[$x].$s.$separator;
						}
					}

					// newline
					$roles[] = "\r\n";
				}

				// return $roles;
				$file = $filename.'.'.$ext;
				$data = $roles;
				file_put_contents($file, $data);
			} else {
				echo '<p>There is no such file extension name (<strong>example:</strong> txt, csv, xls, xlsx, &amp; ods)</p>';
				echo '<p>aurora(<strong><i>file_extension</i></strong>, filename, separator, header, data, string_delimiter);</p>';
				exit();
			}
		}
	}
}

// ------------------------------------------------------------------------

/**
 * Define base_url() method
 */
function base_url($url = null) {
	// set the default application or project directory
	$APP_DIR = config_app('app_dir');

	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443) {
		// if default application or project directory undefined
		if(empty($APP_DIR) || is_null($APP_DIR)) {
			// then get this result
			// site address (https) without application directory
			return 'https://' . $_SERVER['HTTP_HOST'] . '/' . $url;
		} else {
			// else if default application or project directory defined then get this result
			// site address (https) with application directory
			return 'https://' . $_SERVER['HTTP_HOST'] . '/' . $APP_DIR . '/' . $url;
		}
	} else {
		// if default application or project directory undefined
		if(empty($APP_DIR) || is_null($APP_DIR)) {
			// then get this result
			// site address (http) without application directory
			return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url;
		} else {
			// else if default application or project directory defined then get this result
			// site address (http) with application directory
			return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $APP_DIR . '/' . $url;
		}
	}
}
