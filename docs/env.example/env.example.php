<?php

/**
 * Environment Variables
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
	| Define Public directory name
	*/
	'PUBLIC_DIR' => 'public',

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

		// Secondary connection
		'secondary' => [
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
		]

	]

];
