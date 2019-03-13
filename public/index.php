<?php
/*
Use NSY_Router namespace
 */
use Core\NSY_Router;

/*
Use Dotenv namespace
 */
use Dotenv\Dotenv;

/*
NSY Framework
MIT License
Copyright (c) 2018 Vikry Yuansah

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

/*
*---------------------------------------------------------------
* ROOT path
*---------------------------------------------------------------
*/
define('ROOT', str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));

/*
The PSR-4 Autoloader, generate via composer (composer dump-autoload) *see composer.json
The default autoload.php file path.
You can set the file path itself according to your settings.
 */
require(__DIR__ . '/../system/vendor/autoload.php');


/*
*---------------------------------------------------------------
* Check Config File
*---------------------------------------------------------------
*/
if (!is_readable(config_app('nsy_sys_dir'))) {
	die('No NSY_System.php found, configure and rename System file to NSY_System.php in system/core.');
}

/*
*---------------------------------------------------------------
* Don't change anythings about this instantiate
*---------------------------------------------------------------
*/
/*
Load Environment Variables from .env file
 */
$dotenv = Dotenv::create(config_app('env_dir'));
$dotenv->load();

/*
Instantiate System
 */
new NSY_System();

/*
Instantiate Api route
 */
new Api();

/*
Instantiate Web route
 */
new Web();

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
if (defined('ENVIRONMENT')) {
	switch (ENVIRONMENT) {
		// Set as under development
		case 'development':
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		break;

		// Set as under production/go live
		case 'production':
			ini_set('display_errors', 0);
			error_reporting(0);

			if (version_compare(PHP_VERSION, '5.3', '>=')) {
				error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
			} else {
				error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
			}
		break;
		default:

		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		exit('The application environment is not set correctly.');
		exit(1); // EXIT_ERROR
	}
}

/*
Execute matched routes
 */
route::dispatch();