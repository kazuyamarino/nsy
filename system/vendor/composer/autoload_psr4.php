<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname(dirname($vendorDir));

return array(
    'Symfony\\Polyfill\\Mbstring\\' => array($vendorDir . '/symfony/polyfill-mbstring'),
    'Symfony\\Polyfill\\Ctype\\' => array($vendorDir . '/symfony/polyfill-ctype'),
    'Symfony\\Contracts\\Translation\\' => array($vendorDir . '/symfony/translation-contracts'),
    'Symfony\\Component\\Translation\\' => array($vendorDir . '/symfony/translation'),
    'Routes\\' => array($baseDir . '/system/routes'),
    'Modules\\Models\\' => array($baseDir . '/system/modules/homepage/models'),
    'Modules\\Controllers\\' => array($baseDir . '/system/modules/homepage/controllers'),
    'Models\\' => array($baseDir . '/system/models'),
    'Libraries\\' => array($baseDir . '/system/libraries'),
    'Dotenv\\' => array($vendorDir . '/vlucas/phpdotenv/src'),
    'Core\\' => array($baseDir . '/system/core'),
    'Controllers\\' => array($baseDir . '/system/controllers'),
    'Carbon\\' => array($vendorDir . '/nesbot/carbon/src/Carbon'),
);
