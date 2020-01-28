<?php
/**
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*
* Parses and verifies the doc comments for files.
*
* PHP version 7
*
* @category  PHP
* @package   NSY_PHP_Framework
* @author    Vikry Yuansah <admin@kazuyamarino.com>
* @copyright 2018-2020 Polimerz
* @license   https://github.com/kazuyamarino/nsy/blob/master/LICENSE.txt MIT License
* @link      https://github.com/kazuyamarino/nsy
*/

/**
* Use NSY_System class
*/
use System\Core\NSY_System;

/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

/**
* Use NSY_Router class
*/
use System\Core\NSY_Router;

/**
* Phpdotenv class
*/
use Dotenv\Dotenv;

/*
*---------------------------------------------------------------
* ROOT path
*---------------------------------------------------------------
*/
define('ROOT', str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));

/**
* The PSR-4 Autoloader
* The default autoload.php file path.
* You can set the file path itself according to your settings.
*/
require __DIR__ . '/../system/vendor/autoload.php';

/**
* NSY Helpers Class
*/
require_once __DIR__ . '/../system/core/NSY_Helpers.php';

/**
* Class Aliases
*/
require_once __DIR__ . '/../system/libraries/Aliases.php';

/*
*---------------------------------------------------------------
* Check Config File
*---------------------------------------------------------------
*/
// NSY System file check
if (!is_readable( __DIR__ . '/../system/core/NSY_System.php' ) ) {
	die('NSY_System.php not found,  please check in system/core.');
}

// Env file check
if (!is_readable( __DIR__ . '/../' . config_app('env_file')) ) {
	die('env file not found, please check in root folder and config/App.php => <strong>env_file</strong>.');
}

/*
*---------------------------------------------------------------
* Don't change anythings about this instantiate
*---------------------------------------------------------------
*/
/**
* Load Environment Variables from .env file
*/
$dotenv = Dotenv::create( __DIR__ . '/..', config_app('env_file'));
$dotenv->load();

/**
* Instantiate System
*/
new NSY_System();

/**
* Routing System
*/
require_once __DIR__ . '/../system/routes/Web.php';
require_once __DIR__ . '/../system/routes/Api.php';
require_once __DIR__ . '/../system/routes/Migration.php';

/*
|--------------------------------------------------------------------------
| Application Environment
|--------------------------------------------------------------------------
|
| you can set this value on 'System/config/app.php'.
|
*/
define('ENVIRONMENT', config_app('app_env'));

/*
*---------------------------------------------------------------
* ERROR REPORTING
*---------------------------------------------------------------
*
* Different environments will require different levels of error reporting.
* By default development will show errors but production will hide them.
*/
/**
* Get Application Environment
*/
NSY_Desk::static_error_switch();

/**
* Execute matched routes
*/
NSY_Router::dispatch();
