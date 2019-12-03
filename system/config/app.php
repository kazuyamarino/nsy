<?php
defined('ROOT') OR exit('No direct script access allowed');

// Application config
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
    * NOTE: If you change these, also change the error_reporting() code below
    *
    */
	'app_env' => getenv('APP_ENV'),

	/*
    |--------------------------------------------------------------------------
    | Default Application Directory Name
    |--------------------------------------------------------------------------
    |
    | set the default application or project directory
    |
    */
	'app_dir' => getenv('APP_DIR'),

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
	'session_prefix' => getenv('SESSION_PREFIX'),

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
	'locale' => 'id',

	/*
    |--------------------------------------------------------------------------
    | Default Public Directory Name
    |--------------------------------------------------------------------------
    |
    | set the default public directory
    |
    */
	'public_dir' => 'public/',

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
    | defines the template directory path
    |
    */
	'tmp_dir' => __DIR__ . '/../templates',

	/*
    |--------------------------------------------------------------------------
    | Default MVC View Path
    |--------------------------------------------------------------------------
    |
    | defines the MVC View directory path
    |
    */
	'mvc_dir' => __DIR__ . '/../views',

	/*
    |--------------------------------------------------------------------------
    | Default HMVC View Path
    |--------------------------------------------------------------------------
    |
    | defines the HMVC View directory path
    |
    */
	'hmvc_dir' => __DIR__ . '/../modules',

	/*
    |--------------------------------------------------------------------------
    | Default Vendor Directory Path
    |--------------------------------------------------------------------------
    |
    | defines the Vendor directory path
    |
    */
	'vendor_dir' => __DIR__ . '/../vendor',

	/*
    |--------------------------------------------------------------------------
    | Default NSY_System Path
    |--------------------------------------------------------------------------
    |
    | defines the NSY_System file path
    |
    */
	'nsy_sys_dir' => __DIR__ . '/../core/NSY_System.php',

	/*
    |--------------------------------------------------------------------------
    | Default .env File Checking
    |--------------------------------------------------------------------------
    |
    | defines the .env file path
    |
    */
	'env_checking_dir' => __DIR__ . '/../../.env',

	/*
    |--------------------------------------------------------------------------
    | Default .env File Path Location
    |--------------------------------------------------------------------------
    |
    | defines the .env file path
    |
    */
	'env_dir' => __DIR__ . '/../..'

];
