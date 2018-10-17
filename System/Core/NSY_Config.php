<?php

namespace System\Core;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_Config {

	public function __construct() {
		// turn on output buffering
		ob_start();

		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443) {
			// site address (https)
			define('BASE_URL', 'https://' . $_SERVER['HTTP_HOST'] . '/' . 'nsy' . '/');
		} else {
			// site address (http)
			define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . 'nsy' . '/');
		}

		// set the default template
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
		define('DB_HOST', 'localhost'); // hostname
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
		define('SITEKEYWORDS', 'MVC Framework, HMVC Framework, PHP Framework, Datatables, HTML5 Boilerplate, Foundation Zurb, Font Awesome, JQuery');

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
