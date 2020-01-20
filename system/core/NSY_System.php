<?php
namespace System\Core;

/**
* Use Session class
*/
use System\Libraries\Session;

/**
* This is the core of NSY System Settings
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*/
class NSY_System
{

	/**
	* Defined variable for NSY Core System
	*/
	public function __construct()
	{
		// turn on output buffering
		ob_start();

		if ( is_filled(config_app('public_dir')) ) {
			// set the default public/css/js path
			define('CSS_DIR', base_url() . config_app('public_dir') . '/' . config_app('css_dir') . '/'); // CSS directory path

			define('JS_DIR', base_url() . config_app('public_dir') . '/' . config_app('js_dir') . '/'); // JS directory path

			define('IMG_DIR', base_url() . config_app('public_dir') . '/' . config_app('img_dir') . '/'); // IMG directory
		} else {
			// set the default public/css/js path
			define('CSS_DIR', base_url() . config_app('css_dir') . '/'); // CSS directory path

			define('JS_DIR', base_url() . config_app('js_dir') . '/'); // JS directory path

			define('IMG_DIR', base_url() . config_app('img_dir') . '/'); // IMG directory
		}

		// Template directory path
		define('SYS_TMP_DIR', config_app('tmp_dir') . '/');

		// MVC directory path
		define('MVC_VIEW_DIR', config_app('mvc_dir') . '/');

		// HMVC directory path
		define('HMVC_VIEW_DIR', config_app('hmvc_dir') . '/');

		// Vendor directory path
		define('VENDOR_DIR', config_app('vendor_dir') . '/');

		// set a default language
		define('LANGUAGE_CODE', config_app('locale'));

		// set a default prefix OG
		define('OG_PREFIX', config_app('prefix_attr'));

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

		// optional set a version of the application
		define('VERSION', config_site('version'));

		// optional set a codename of the application
		define('CODENAME', config_site('codename'));

		// set timezone
		date_default_timezone_set(config_app('timezone'));

		// start session
		Session::init();
	}

}
