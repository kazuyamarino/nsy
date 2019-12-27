<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInityour_suffixed
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        'f3001b2d3b447163dc3f7cf0b0806362' => __DIR__ . '/../../..' . '/system/helpers/NSY_Helpers.php',
        '902c05a8f63df8bfe9d1ccfdea0f6e4e' => __DIR__ . '/../../..' . '/system/helpers/CI_Helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Contracts\\Translation\\' => 30,
            'Symfony\\Component\\Translation\\' => 30,
        ),
        'R' => 
        array (
            'Routes\\' => 7,
            'Razr\\Storage\\' => 13,
            'Razr\\Loader\\' => 12,
            'Razr\\Extension\\' => 15,
            'Razr\\Exception\\' => 15,
            'Razr\\Directive\\' => 15,
            'Razr\\' => 5,
        ),
        'P' => 
        array (
            'PhpOption\\' => 10,
        ),
        'M' => 
        array (
            'Modules\\Homepage\\Models\\' => 24,
            'Modules\\Homepage\\Controllers\\' => 29,
            'Models\\' => 7,
            'Migrations\\' => 11,
        ),
        'L' => 
        array (
            'Libraries\\' => 10,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
        'C' => 
        array (
            'Core\\' => 5,
            'Controllers\\' => 12,
            'Carbon\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Contracts\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation-contracts',
        ),
        'Symfony\\Component\\Translation\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/translation',
        ),
        'Routes\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/routes',
        ),
        'Razr\\Storage\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/core/Razr/Storage',
        ),
        'Razr\\Loader\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/core/Razr/Loader',
        ),
        'Razr\\Extension\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/core/Razr/Extension',
        ),
        'Razr\\Exception\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/core/Razr/Exception',
        ),
        'Razr\\Directive\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/core/Razr/Directive',
        ),
        'Razr\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/core/Razr',
        ),
        'PhpOption\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoption/phpoption/src/PhpOption',
        ),
        'Modules\\Homepage\\Models\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/modules/homepage/models',
        ),
        'Modules\\Homepage\\Controllers\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/modules/homepage/controllers',
        ),
        'Models\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/models',
        ),
        'Migrations\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/migrations',
        ),
        'Libraries\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/libraries',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
        'Core\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/core',
        ),
        'Controllers\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/system/controllers',
        ),
        'Carbon\\' => 
        array (
            0 => __DIR__ . '/..' . '/nesbot/carbon/src/Carbon',
        ),
    );

    public static $prefixesPsr0 = array (
        'F' => 
        array (
            'FtpClient' => 
            array (
                0 => __DIR__ . '/..' . '/nicolab/php-ftp-client/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInityour_suffixed::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInityour_suffixed::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInityour_suffixed::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
