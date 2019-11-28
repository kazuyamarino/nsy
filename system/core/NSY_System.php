<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

/*
 * This is the core of NSY System Settings
 * 2018 - Vikry Yuansah
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
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

		define('VENDOR_DIR', config_app('vendor_dir') . '/');

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

		// Aliasing Assets class name
		class_alias('Libraries\Assets', 'pull');

		// start session
		session_start();
	}

}
