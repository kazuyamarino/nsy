<?php

use Core\NSY_Config;

/**
 * NSY
 *
 * HTML5 Boilerplate & Foundation CSS framework in one package
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2018, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package NSY
 * @author  Polimerz Dev Team
 * @copyright   Copyright (c) 2016 - 2018, PolimerzStudio.
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link
 * @since   Version 4.0.0
 * @filesource
 */

/*
 *---------------------------------------------------------------
 * ROOT path
 *---------------------------------------------------------------
 */
define('ROOT', str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));

/*
 *---------------------------------------------------------------
 * Check Config File
 *---------------------------------------------------------------
 */
if (!is_readable('Core/NSY_Config.php'))
{
  die('No Config.php found, configure and rename Config file to Config.php in app/core.');
}


/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
define('ENVIRONMENT', 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but production will hide them.
 */

if (defined('ENVIRONMENT'))
{
  switch (ENVIRONMENT)
  {
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
        if (version_compare(PHP_VERSION, '5.3', '>='))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        }
        else
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        break;
    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        exit('The application environment is not set correctly.');
        exit(1); // EXIT_ERROR
  }
}

// The PSR-4 Autoloader, generate via composer (composer dump-autoload) *see composer.json
require (ROOT . 'NSYAutoload/autoload.php');

new NSY_Config();
new Routing();
