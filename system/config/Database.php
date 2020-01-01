<?php
defined('ROOT') OR exit('No direct script access allowed');

/**
 * Database details ONLY NEEDED IF USING A DATABASE
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

       // Primary/Default Connection DB
    'default' => getenv('DB_CONNECTION'),

    // Secondary Connection DB
    'secondary' => getenv('DB_CONNECTION_SEC'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by NSY is shown below to make development simple.
    |
    |
    | All database work in NSY is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

       // Default/Primary Connection
    'connections' => [

        'mysql' => [
            'DB_DRIVER' => 'mysql',
            'DB_HOST' => getenv('DB_HOST'),
            'DB_PORT' => getenv('DB_PORT'),
            'DB_NAME' => getenv('DB_NAME'),
            'DB_USER' => getenv('DB_USER'),
            'DB_PASS' => getenv('DB_PASS'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ],

        'dblib' => [
        'DB_DRIVER' => 'dblib',
        'DB_HOST' => getenv('DB_HOST'),
        'DB_PORT' => getenv('DB_PORT'),
        'DB_NAME' => getenv('DB_NAME'),
        'DB_USER' => getenv('DB_USER'),
        'DB_PASS' => getenv('DB_PASS'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ],

        'sqlsrv' => [
        'DB_DRIVER' => 'sqlsrv',
        'DB_HOST' => getenv('DB_HOST'),
        'DB_PORT' => getenv('DB_PORT'),
        'DB_NAME' => getenv('DB_NAME'),
        'DB_USER' => getenv('DB_USER'),
        'DB_PASS' => getenv('DB_PASS'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ],

        'pgsql' => [
        'DB_DRIVER' => 'pgsql',
        'DB_HOST' => getenv('DB_HOST'),
        'DB_PORT' => getenv('DB_PORT'),
        'DB_NAME' => getenv('DB_NAME'),
        'DB_USER' => getenv('DB_USER'),
        'DB_PASS' => getenv('DB_PASS'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ]

    ],

    // Second Connection
    'connections_sec' => [

        'mysql' => [
            'DB_DRIVER' => 'mysql',
            'DB_HOST' => getenv('DB_HOST_SEC'),
            'DB_PORT' => getenv('DB_PORT_SEC'),
            'DB_NAME' => getenv('DB_NAME_SEC'),
            'DB_USER' => getenv('DB_USER_SEC'),
            'DB_PASS' => getenv('DB_PASS_SEC'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ],

        'dblib' => [
        'DB_DRIVER' => 'dblib',
        'DB_HOST' => getenv('DB_HOST_SEC'),
        'DB_PORT' => getenv('DB_PORT_SEC'),
        'DB_NAME' => getenv('DB_NAME_SEC'),
        'DB_USER' => getenv('DB_USER_SEC'),
        'DB_PASS' => getenv('DB_PASS_SEC'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ],

        'sqlsrv' => [
        'DB_DRIVER' => 'sqlsrv',
        'DB_HOST' => getenv('DB_HOST_SEC'),
        'DB_PORT' => getenv('DB_PORT_SEC'),
        'DB_NAME' => getenv('DB_NAME_SEC'),
        'DB_USER' => getenv('DB_USER_SEC'),
        'DB_PASS' => getenv('DB_PASS_SEC'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ],

        'pgsql' => [
        'DB_DRIVER' => 'pgsql',
        'DB_HOST' => getenv('DB_HOST_SEC'),
        'DB_PORT' => getenv('DB_PORT_SEC'),
        'DB_NAME' => getenv('DB_NAME_SEC'),
        'DB_USER' => getenv('DB_USER_SEC'),
        'DB_PASS' => getenv('DB_PASS_SEC'),
            'DB_CHARSET' => 'utf8',
            'PREFIX' => ''
        ]

    ]

];
