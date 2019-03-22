<?php
use Core\NSY_CSRF;
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
function config_db($d1 = "",$d2 = "") {
	$database = require(__DIR__ . '/../config/database.php');
	if ($d2 == "" || empty($d2) || !isset($d2) || $d2 == null) {
		return $database[$d1];
	} else {
		return $database['connections'][$d1][$d2];
	}
}

// Get config value from system/config/app.php
function config_app($d1 = "") {
	$app = require(__DIR__ . '/../config/app.php');
	return $app[$d1];
}

// Get config value from system/config/site.php
function config_site($d1 = "") {
	$site = require(__DIR__ . '/../config/site.php');
	return $site[$d1];
}

/*
Redirect URL
 */
function redirect($url = "") {
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
function fetch_json($data = "") {
	$json_data = $data;
	$json_result = json_encode($json_data);

	return $json_result;
}

/*
Secure Input Element
 */
function secure_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

/*
Secure Form
 */
function secure_form($form) {
	foreach ($form as $key => $value) {
		$form[$key] = $this->secure_input($value);
	}
}

/*
CSRF Token
 */
function csrf_token() {
	if(config_app('csrf_token') === 'true') {
		$csrf_token = NSY_CSRF::generate( 'csrf_token' );
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
		$csrf_token = NSY_CSRF::generate( 'csrf_token' );
		return '<input type="hidden" name="csrf_token" value=' . $csrf_token . '">';
	} elseif(config_app('csrf_token') === 'false') {
		return "CSRF Token Protection must be set 'true'";
	}
}

/*
XSS Filter
 */
function xss_filter($value) {
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
function remove_get_parameters($url) {
	$remove_get_parameters = new NSY_XSS_Filter();
	$func = $remove_get_parameters->remove_get_parameters($url);
	return $func;
}

// Define base_url() method
function base_url($url = "") {
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
