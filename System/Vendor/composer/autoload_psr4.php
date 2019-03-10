<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname(dirname($vendorDir));

return array(
    'Symfony\\Polyfill\\Mbstring\\' => array($vendorDir . '/symfony/polyfill-mbstring'),
    'Symfony\\Polyfill\\Ctype\\' => array($vendorDir . '/symfony/polyfill-ctype'),
    'Symfony\\Contracts\\' => array($vendorDir . '/symfony/contracts'),
    'Symfony\\Component\\Translation\\' => array($vendorDir . '/symfony/translation'),
    'PHPMailer\\PHPMailer\\' => array($vendorDir . '/phpmailer/phpmailer/src'),
    'Modules\\Models\\' => array($baseDir . '/System/Modules/Homepage/Models'),
    'Modules\\Controllers\\' => array($baseDir . '/System/Modules/Homepage/Controllers'),
    'Models\\' => array($baseDir . '/System/Models'),
    'Dotenv\\' => array($vendorDir . '/vlucas/phpdotenv/src'),
    'Core\\' => array($baseDir . '/System/Core'),
    'Controllers\\' => array($baseDir . '/System/Controllers'),
    'Carbon\\' => array($vendorDir . '/nesbot/carbon/src/Carbon'),
);
