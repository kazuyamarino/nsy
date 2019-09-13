<?php
use Core\NSY_XSS_Filter;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_System {

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
		class_alias("Core\NSY_AssetManager", "add");

		// Aliasing Assets class name
		class_alias("Assets", "pull");

		// Aliasing NSY_Router class name
		class_alias("Core\NSY_Router", "route");

		// start session
		session_start();
	}

}

// Get config value from system/config/database.php
function config_db($d1 = null,$d2 = null) {
	$database = require(__DIR__ . '/../config/database.php');
	if ($d2 == "" || empty($d2) || !isset($d2) || $d2 == null) {
		return $database[$d1];
	} else {
		return $database['connections'][$d1][$d2];
	}
}

function config_db_sec($d1 = null,$d2 = null) {
	$database = require(__DIR__ . '/../config/database.php');
	if ($d2 == "" || empty($d2) || !isset($d2) || $d2 == null) {
		return $database[$d1];
	} else {
		return $database['connections_sec'][$d1][$d2];
	}
}

// Get config value from system/config/app.php
function config_app($d1 = null) {
	$app = require(__DIR__ . '/../config/app.php');
	return $app[$d1];
}

// Get config value from system/config/site.php
function config_site($d1 = null) {
	$site = require(__DIR__ . '/../config/site.php');
	return $site[$d1];
}

/*
Redirect URL
 */
function redirect($url = null) {
	header('location:'. base_url($url));
	exit();
}

/*
Redirect Back URL
 */
function redirect_back() {
    header('location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

/*
Fetching to json format
 */
function fetch_json($data = null) {
	$json_data = $data;
	$json_result = json_encode($json_data);

	return $json_result;
}

/*
Secure Input Element
 */
function secure_input($data = null) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

/*
Secure Form
 */
function secure_form($form = null) {
	foreach ($form as $key => $value) {
		$form[$key] = $this->secure_input($value);
	}
}

/*
CSRF Token
 */
function csrf_token() {
	if(config_app('csrf_token') === 'true') {
		$csrf_token = \NSY_CSRF::generate( 'csrf_token' );
		return $csrf_token;
	} elseif(config_app('csrf_token') === 'false') {
		return "CSRF Token Protection must be set 'true'";
	}
}

/*
CSRF Form Token
 */
function form_csrf_token() {
	if(config_app('csrf_token') === 'true') {
		$csrf_token = \NSY_CSRF::generate( 'csrf_token' );
		return '<input type="hidden" name="csrf_token" value=' . $csrf_token . '">';
	} elseif(config_app('csrf_token') === 'false') {
		return "CSRF Token Protection must be set 'true'";
	}
}

/*
XSS Filter
 */
function xss_filter($value = null) {
	$xss_filter = new NSY_XSS_Filter();
	$string = $xss_filter->filter_it($value);
	return $string;
}

/*
Allow http
 */
function allow_http() {
	$allow_http = new NSY_XSS_Filter();
	$func = $allow_http->allow_http();
	return $func;
}

/*
Disallow http
 */
function disallow_http() {
	$disallow_http = new NSY_XSS_Filter();
	$func = $disallow_http->disallow_http();
	return $func;
}

/*
Remove url get parameter
 */
function remove_get_parameters($url = null) {
	$remove_get_parameters = new NSY_XSS_Filter();
	$func = $remove_get_parameters->remove_get_parameters($url);
	return $func;
}

/*
Get URI Segment
 */
function get_uri_segment($key = null) {
	$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

	if (array_key_exists($key, $uriSegments)) {
		return $uriSegments[$key];
	} else {
		return "Segment doesn't exist";
	}
}

/*
Create Random Number
 */
function generate_num($prefix = 'NSY-', $id_length = 6, $num_length = 10) {
	$zeros = str_pad(null, $id_length, 0, STR_PAD_LEFT);
	$nines = str_pad(null, $id_length, 9, STR_PAD_LEFT);

	$ids = str_pad(mt_rand($zeros, $nines), $num_length, $prefix, STR_PAD_LEFT);
	return $ids;
}

/*
The PHP $_SESSION are used to create and show session.
 */
function add_session($index = null, $value = null) {
	$_SESSION[$index] = $value;
	return $_SESSION[$index];
}

function show_session($index = null) {
	if(isset($_SESSION[$index])) {
		return $_SESSION[$index];
	} else {
		return null;
	}
}

/*
PHP Shorthand If/Else Using Ternary Operators
 */
function ternary($condition = null, $result_one = null, $result_two = null) {
	$result = ($condition ? $result_one : $result_two);
	return $result;
}

/*
The PHP superglobals $_GET and $_POST are used to collect form-data.
 */
function post($param = null) {
	 $result = isset($_POST[$param]) ? $_POST[$param] : null;
	 return $result;
}

function get($param = null) {
	 $result = isset($_GET[$param]) ? $_GET[$param] : null;
	 return $result;
}

/*
Define base_url() method
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
