<?php

namespace Core;

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
	define('TEMPLATE_DIR', 'Template' . '/'); // TEMPLATE PATH
	define('CSS_DIR', BASE_URL . TEMPLATE_DIR . 'css' . '/'); // CSS PATH
	define('JS_DIR', BASE_URL . TEMPLATE_DIR . 'js' . '/'); // JS PATH
	define('IMG_DIR', BASE_URL . TEMPLATE_DIR . 'img' . '/'); // IMG PATH

	// set a default language
	define('LANGUAGE_CODE', 'en');

	// database details ONLY NEEDED IF USING A DATABASE
  define('DB_HOST', 'localhost'); // hostname
	define('DB_NAME', 'srd_development'); // database name
	define('DB_USER', 'root'); // username
	define('DB_PASS', 'depelover90'); // password

	// set prefix for sessions
	define('SESSION_PREFIX', '');

	// optional create a constant for the name of the site
	define('SITETITLE', 'NSY PHP Framework');

  // optional set a site author
	define('SITEAUTHOR', 'Vikry Yuansah');

  // optional set a site keywords
	define('SITEKEYWORDS', 'HTML, CSS, Datatables, JavaScript, PHP Framework');

  // optional set a site description
	define('SITEDESCRIPTION', 'NSY PHP Framework allows you to use the HTML5 Boilerplate and Foundation CSS Framework in one package at a time. As well as include some support for Font-Awesome and Sticky footer. NSY also provides several optimizations for plugin Datatables table.');

	// optional set a site email address
	define('SITEEMAIL', '');

	// set timezone
	date_default_timezone_set('Asia/Jakarta');

  // start session
	session_start();

  }

}
