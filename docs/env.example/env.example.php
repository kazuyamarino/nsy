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
	'SITE_DESC' => 'Simple. Layered. Harmony in MVC and HMVC.',
	'SITE_EMAIL' => 'vikry.yuansah@gmail.com',
	'APP_VERSION' => '7.0.0',
	'APP_CODENAME' => 'Gamelan',
	'REPO_URL' => 'https://github.com/kazuyamarino/nsy',
	'SINCE_YEAR' => '2018',

	/*
	| Define Application Variables
	*/
	'CSRF_TOKEN' => 'false',
	'ENCRYPTION_KEY' => '',   // System\Libraries\Encryption (AES-256-GCM) — set a long random secret
	'DB_TRANSACTION' => 'off',
	'APP_MAINTENANCE' => 'false',   // 'true' takes the site offline with a 503 page (also: nsy down)
	'APP_MAINTENANCE_ALLOW' => '',  // comma-separated IPs allowed to bypass maintenance
	'APP_MIGRATION_HTTP' => 'false',        // opt-in: expose migration HTTP trigger (development only)
	'APP_MIGRATION_HTTP_TOKEN' => '',       // required secret for the HTTP trigger (?token=…)
	'APP_TIMEZONE' => 'Asia/Jakarta',
	'APP_LOCALE' => 'id-ID',
	'APP_URL' => '',   // optional canonical scheme://host[:port] (e.g. https://example.com) — blocks Host-header poisoning when set
	'OG_PREFIX' => 'og: http://ogp.me/ns#',
	'CSS_DIR' => 'css',
	'JS_DIR' => 'js',
	'IMG_DIR' => 'images',

	/*
	| CodeIgniter-ported global helpers (System/Helpers/CodeIgniterHelpers.php).
	| Loaded by NSY_SystemLoader by default; set 'false' to skip them entirely
	| (the NSY core never calls these functions).
	*/
	'NSY_CI_HELPERS' => 'true',

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
