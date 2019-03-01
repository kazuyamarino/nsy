<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_Config {

	public function __construct() {
		// turn on output buffering
		ob_start();

		// set the default application or project directory
		$APP_DIR = 'nsy'; // defined
		// $APP_DIR = ''; // undefined

		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443) {
			// if default application or project directory undefined
			if(empty($APP_DIR) || is_null($APP_DIR)) {
				// then get this result
				// site address (https) without application directory
				define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
			} else {
				// else if default application or project directory defined then get this result
				// site address (https) with application directory
				define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/' . $APP_DIR . '/');
			}
		} else {
			// if default application or project directory undefined
			if(empty($APP_DIR) || is_null($APP_DIR)) {
				// then get this result
				// site address (http) without application directory
				define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
			} else {
				// else if default application or project directory defined then get this result
				// site address (http) with application directory
				define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . $APP_DIR . '/');
			}
		}

		// set the default template/css/js path
		define('TEMPLATE_DIR', 'Public/Template' . '/'); // TEMPLATE PATH
		define('CSS_DIR', BASE_URL . TEMPLATE_DIR . 'css' . '/'); // CSS PATH
		define('JS_DIR', BASE_URL . TEMPLATE_DIR . 'js' . '/'); // JS PATH
		define('IMG_DIR', BASE_URL . TEMPLATE_DIR . 'img' . '/'); // IMG PATH

		//set the default view path
		define('SYS_TMP_DIR', '../Public/Template' . '/');
		define('MVC_VIEW_DIR', '../System/Views' . '/');
		define('HMVC_VIEW_DIR', '../System/Modules/*/Views' . '/');

		// set a default language
		define('LANGUAGE_CODE', 'en');

		// database details ONLY NEEDED IF USING A DATABASE
		define('DB_HOST', ''); // hostname
		define('DB_NAME', ''); // database name
		define('DB_USER', ''); // username
		define('DB_PASS', ''); // password

		// set prefix for sessions
		define('SESSION_PREFIX', '');

		// optional create a constant for the name of the site
		define('SITETITLE', 'NSY PHP Framework');

		// optional set a site author
		define('SITEAUTHOR', 'Vikry Yuansah');

		// optional set a site keywords
		define('SITEKEYWORDS', 'MVC Framework, HMVC Framework, PHP Framework');

		// optional set a site description
		define('SITEDESCRIPTION', 'NSY is a simple PHP Framework that works well on MVC or HMVC mode.');

		// optional set a site email address
		define('SITEEMAIL', '');

		// set timezone
		date_default_timezone_set('Asia/Jakarta');

		// start session
		session_start();
	}

}
