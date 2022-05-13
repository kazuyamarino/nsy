<?php
/**
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*
* Parses and verifies the doc comments for files.
*
* @category  PHP
* @package   NSY_PHP_Framework
* @author    Vikry Yuansah <admin@nsyframework.com>
* @copyright 2018-2020 Polimerz
* @license   https://github.com/kazuyamarino/nsy/blob/master/LICENSE.txt MIT License
* @link      https://github.com/kazuyamarino/nsy
*/

/**
* The PSR-4 Autoloader
* The default autoload.php file path.
* You can set the file path itself according to your settings.
*/
require __DIR__ . '/../System/Vendor/autoload.php';

/**
* NSY GLobal Helpers
*/
require __DIR__ . '/../System/Core/NSY_Helpers_Global.php';

/*
*---------------------------------------------------------------
* Don't change anythings about this instantiate
*---------------------------------------------------------------
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
* Instantiate System
*/
new NSY_System();

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
* Check System File
*/
NSY_Desk::register_system();

/**
* Check Config File
*/
NSY_Desk::register_config();

/**
* Routing System
*/
NSY_Desk::register_route();

/**
* Execute matched routes
*/
NSY_Router::dispatch();
