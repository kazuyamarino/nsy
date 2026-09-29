<?php

declare(strict_types=1);

/**
 * PHPUnit bootstrap for the NSY test suite.
 *
 * Loads the Composer autoloader (which maps System\ -> System/) and the global
 * helpers, so tests can use System\Libraries\* and config_* helpers.
 */
require dirname(__DIR__) . '/Vendor/autoload.php';
require dirname(__DIR__) . '/Core/NSY_Helpers_Global.php';
