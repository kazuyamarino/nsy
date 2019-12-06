<?php
/**
 * Use NSY_System class
 */
use Core\NSY_System;

/**
 * Use NSY_Desk class
 */
use Core\NSY_Desk;

/**
 * Use NSY_Router class
 */
use Core\NSY_Router;

/**
 * Use Web class
 */
use Routes\Web;

/**
 * Use Api class
 */
use Routes\Api;

/**
 * Use Migration class
 */
use Routes\Migration;

/**
 * phpdotenv class
 */
use Dotenv\Dotenv;

/**
 * NSY Framework
 * MIT License
 * Copyright (c) 2019 Vikry Yuansah
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
*/

/*
*---------------------------------------------------------------
* ROOT path
*---------------------------------------------------------------
*/
define('ROOT', str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));

/**
 * The PSR-4 Autoloader, generate via composer (composer dump-autoload) *see composer.json
 * The default autoload.php file path.
 * You can set the file path itself according to your settings.
 */
require(__DIR__ . '/../system/vendor/autoload.php');

/*
*---------------------------------------------------------------
* Check Config File
*---------------------------------------------------------------
*/
if ( !is_readable(config_app('nsy_sys_dir')) ) {
	die('No NSY_System.php found, configure and rename System file to NSY_System.php in system/core.');
} elseif ( !is_readable(config_app('env_checking_dir')) ) {
	die('No .env file found, please check in the root folder.');
}

/*
*---------------------------------------------------------------
* Don't change anythings about this instantiate
*---------------------------------------------------------------
*/
/**
 * Load Environment Variables from .env file
 */
$dotenv = Dotenv::create(config_app('env_dir'));
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
 * Instantiate Desk
 */
$desk = new NSY_Desk();
$desk->error_switch();

/**
 * Execute matched routes
 */
NSY_Router::dispatch();
