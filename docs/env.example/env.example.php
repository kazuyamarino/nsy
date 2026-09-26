<?php

/**
 * Environment Variables — Example
 * Copy to env.php and fill values: cp docs/env.example/env.example.php env.php
 */
return [

	/*
	| Define Environment - Select 'development' or 'production' mode
	*/
	'APP_ENV' => 'development',

	/*
	| Define Application Directory
	*/
	'APP_DIR' => 'nsy',

	/*
	| Define Session Prefix
	*/
	'SESSION_PREFIX' => '',

	/*
	| Define Public directory name
	*/
	'PUBLIC_DIR' => 'public',

	/*
	| Define Site Variables
	*/
	'SITE_TITLE' => 'NSY PHP Framework',
	'SITE_AUTHOR' => 'Vikry Yuansah',
	'SITE_KEYWORDS' => 'MVC Framework, HMVC Framework, PHP Framework',
	'SITE_DESC' => 'NSY is a simple PHP Framework that works well on MVC or HMVC mode.',
	'SITE_EMAIL' => '',
	'APP_VERSION' => '7.0.0',
	'APP_CODENAME' => 'Gamelan',

	/*
	| Define Application Variables
	*/
	'CSRF_TOKEN' => 'false',
	'DB_TRANSACTION' => 'off',
	'APP_TIMEZONE' => 'Asia/Jakarta',
	'APP_LOCALE' => 'id-ID',
	'OG_PREFIX' => 'og: http://ogp.me/ns#',
	'CSS_DIR' => 'css',
	'JS_DIR' => 'js',
	'IMG_DIR' => 'images',

	/*
	| Define Logging (see docs/README_LOGGING.md)
	| LOG_ENABLED toggles the whole subsystem. Context (IP/UA/User-ID) is OFF
	| by default; LOG_DIR may be relative to the project root or absolute.
	*/
	'LOG_ENABLED' => 'false',
	'LOG_DIR' => 'System/Storage/logs',
	'LOG_LEVEL' => '',
	'LOG_FORMAT' => 'json',
	'LOG_SPLIT_CHANNELS' => 'false',
	'LOG_MAX_SIZE_MB' => '50',
	'LOG_RETENTION_DAYS' => '14',
	'LOG_SLOW_QUERY_MS' => '500',
	'ACCESS_LOG_ENABLED' => 'true',
	'LOG_IP' => 'false',
	'LOG_USER_AGENT' => 'false',
	'LOG_USER_ID' => 'false',
	'LOG_REDACT' => 'password,passwd,secret,token,authorization,cookie,csrf',

	/*
	| Define FTP Variables
	*/
	'FTP_HOST' => '',
	'FTP_USERNAME' => '',
	'FTP_PASSWORD' => '',

	/*
	| Database Connection
	| You can create your own database connection as you need.
	 */
	'connections' => [

		// Primary connection
		'primary' => [
			'DB_CONNECTION' => '',
			'DB_HOST' => '',
			'DB_PORT' => '',
			'DB_NAME' => '',
			'DB_USER' => '',
			'DB_PASS' => '',
			'DB_CHARSET' => '',
			'DB_PREFIX' => '',
			'DB_ATTR' => [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_EMULATE_PREPARES => false
			]
		],

		// mysql connection
		'mysql' => [
			'DB_CONNECTION' => 'mysql',
			'DB_HOST' => '',
			'DB_PORT' => '3306',
			'DB_NAME' => '',
			'DB_USER' => '',
			'DB_PASS' => '',
			'DB_CHARSET' => '',
			'DB_PREFIX' => '',
			'DB_ATTR' => [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_EMULATE_PREPARES => false,
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
			]
		],

		// Pgsql connection
		'pgsql' => [
			'DB_CONNECTION' => 'pgsql',
			'DB_HOST' => '',
			'DB_PORT' => '5432',
			'DB_NAME' => '',
			'DB_USER' => '',
			'DB_PASS' => '',
			'DB_CHARSET' => '',
			'DB_PREFIX' => '',
			'DB_ATTR' => [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_EMULATE_PREPARES => false
			]
		],

		// Sqlsrv connection
		'sqlsrv' => [
			'DB_CONNECTION' => 'sqlsrv',
			'DB_HOST' => '',
			'DB_PORT' => '1433',
			'DB_NAME' => '',
			'DB_USER' => '',
			'DB_PASS' => '',
			'DB_CHARSET' => '',
			'DB_PREFIX' => '',
			'DB_ATTR' => [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_EMULATE_PREPARES => false
			]
		]

	]

];
