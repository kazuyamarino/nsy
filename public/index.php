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
 * Use Web class
 */
use System\Routes\Web;

/**
 * Use Api class
 */
use System\Routes\Api;

/**
 * Use Migration class
 */
use System\Routes\Migration;

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

/*
*---------------------------------------------------------------
* Check Config File
*---------------------------------------------------------------
*/
// NSY System file check
if (!is_readable(config_app('nsy_sys_dir')) ) {
    die('NSY_System.php not found,  please check in system/core.');
}

// Env file check
if (!is_readable(config_app('env_checking_dir')) ) {
    die('env file not found, please check in root folder.');
}

/*
*---------------------------------------------------------------
* Don't change anythings about this instantiate
*---------------------------------------------------------------
*/
/**
 * Load Environment Variables from .env file
 */
$dotenv = Dotenv::create(config_app('env_dir'), config_app('env_file'));
$dotenv->load();

/**
 * Instantiate System
 */
new NSY_System();

/**
 * Instantiate Web route
 */
new Web();

/**
 * Instantiate Api route
 */
new Api();

/**
 * Instantiate Migration route
 */
new Migration();

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
