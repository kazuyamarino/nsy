<?php
/**
* Application config
*/
return [

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
	* Note: If you change these, also change the error_reporting() code below
	*
	*/
	'app_env' => config_env('APP_ENV'),

	/*
	|--------------------------------------------------------------------------
	| Default Application Directory Name
	|--------------------------------------------------------------------------
	|
	| set the default application or project directory
	|
	*/
	'app_dir' => config_env('APP_DIR'),

	/*
	|--------------------------------------------------------------------------
	| Default CSRF Token Protection
	|--------------------------------------------------------------------------
	|
	| set the default 'true' or 'false'
	|
	*/
	'csrf_token' => 'false',

	/*
	|--------------------------------------------------------------------------
	| Default Session Duration
	|--------------------------------------------------------------------------
	|
	| Start session by setting the session duration
	|
	*/
	'session_duration' => 0,

	/*
	|--------------------------------------------------------------------------
	| Default Database Transaction Mode Setting
	|--------------------------------------------------------------------------
	|
	| set the default 'on' or 'off'
	|
	*/
	'transaction' => 'off',

	/*
	|--------------------------------------------------------------------------
	| Default SESSION_PREFIX
	|--------------------------------------------------------------------------
	|
	| set the default prefix for session
	|
	*/
	'session_prefix' => config_env('SESSION_PREFIX'),

	/*
	|--------------------------------------------------------------------------
	| Default Set Timezone
	|--------------------------------------------------------------------------
	|
	| set the default timezone location
	|
	*/
	'timezone' => 'Asia/Jakarta',

	/*
	|--------------------------------------------------------------------------
	| Default Locale
	|--------------------------------------------------------------------------
	|
	| set the default locale
	|
	*/
	'locale' => 'id-ID',

	/*
	|--------------------------------------------------------------------------
	| Default Prefix Attribute
	|--------------------------------------------------------------------------
	|
	| set the default namespace prefix for Open Graph protocol
	|
	*/
	'prefix_attr' => 'og: http://ogp.me/ns#',

	/*
	|--------------------------------------------------------------------------
	| Default Public Directory Name
	|--------------------------------------------------------------------------
	|
	| set the default public directory
	|
	*/
	'public_dir' => config_env('PUBLIC_DIR'),

	/*
	|--------------------------------------------------------------------------
	| Default CSS Directory Name
	|--------------------------------------------------------------------------
	|
	| set the default css directory
	|
	*/
	'css_dir' => 'css',

	/*
	|--------------------------------------------------------------------------
	| Default Javascript Directory Name
	|--------------------------------------------------------------------------
	|
	| set the default javascript directory
	|
	*/
	'js_dir' => 'js',

	/*
	|--------------------------------------------------------------------------
	| Default Image Directory Name
	|--------------------------------------------------------------------------
	|
	| set the default image directory
	|
	*/
	'img_dir' => 'img',

	/*
	|--------------------------------------------------------------------------
	| Default Template Path
	|--------------------------------------------------------------------------
	|
	| define the template directory path
	|
	*/
	'tmp_dir' => __DIR__ . '/../Templates',

	/*
	|--------------------------------------------------------------------------
	| Default MVC View Path
	|--------------------------------------------------------------------------
	|
	| define the MVC View directory path
	|
	*/
	'mvc_dir' => __DIR__ . '/../Views',

	/*
	|--------------------------------------------------------------------------
	| Default HMVC View Path
	|--------------------------------------------------------------------------
	|
	| define the HMVC View directory path
	|
	*/
	'hmvc_dir' => __DIR__ . '/../Modules',

	/*
	|--------------------------------------------------------------------------
	| Default Vendor Directory Path
	|--------------------------------------------------------------------------
	|
	| define the Vendor directory path
	|
	*/
	'vendor_dir' => __DIR__ . '/../Vendor',

	/*
	|--------------------------------------------------------------------------
	| Default System Directory name
	|--------------------------------------------------------------------------
	|
	| set the default system directory name
	|
	*/
	'sys_dir' => 'System',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/
	'aliases' => [
        'Route' => System\Core\NSY_Router::class,
        'Add' => System\Core\NSY_AssetManager::class,
        'System\Migrations\Mig' => System\Core\NSY_Migration::class,
		'System\Vendor\Curl' => Curl\Curl::class,
		'System\Vendor\Faker' => Faker\Factory::class,
		'System\Vendor\Carbon' => Carbon\Carbon::class,
		'System\Vendor\Ftp' => FtpClient\FtpClient::class,
		'System\Vendor\Almana' => Lablnet\Encryption::class
	]

];
