<?php
// database details ONLY NEEDED IF USING A DATABASE
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
	'default' => 'mysql',

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
	'connections' => [

        'mysql' => [
			'DB_DRIVER' => 'mysql',
		    'DB_HOST' => 'localhost',
		    'DB_PORT' => '3306',
		    'DB_NAME' => 'saleshub_development',
		    'DB_USER' => 'root',
		    'DB_PASS' => 'depelover90',
            'DB_CHARSET' => 'utf8mb4',
            'PREFIX' => ''
        ],

		'dblib' => [
			'DB_DRIVER' => 'dblib',
		    'DB_HOST' => 'localhost',
		    'DB_PORT' => '1433',
		    'DB_NAME' => '',
		    'DB_USER' => '',
		    'DB_PASS' => '',
            'DB_CHARSET' => 'utf8mb4',
            'PREFIX' => ''
        ],

		'sqlsrv' => [
			'DB_DRIVER' => 'sqlsrv',
		    'DB_HOST' => 'localhost',
		    'DB_PORT' => '1433',
		    'DB_NAME' => '',
		    'DB_USER' => '',
		    'DB_PASS' => '',
            'DB_CHARSET' => 'utf8mb4',
            'PREFIX' => ''
        ],

		'pgsql' => [
			'DB_DRIVER' => 'pgsql',
		    'DB_HOST' => 'localhost',
		    'DB_PORT' => '5432',
		    'DB_NAME' => '',
		    'DB_USER' => '',
		    'DB_PASS' => '',
            'DB_CHARSET' => 'utf8mb4',
            'PREFIX' => ''
        ]

	]

];
