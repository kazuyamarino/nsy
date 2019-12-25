<?php
defined('ROOT') OR exit('No direct script access allowed');

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
	'public_dir' => getenv('PUBLIC_DIR'),

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
	'tmp_dir' => __DIR__ . '/../templates',

	/*
    |--------------------------------------------------------------------------
    | Default MVC View Path
    |--------------------------------------------------------------------------
    |
    | define the MVC View directory path
    |
    */
	'mvc_dir' => __DIR__ . '/../views',

	/*
    |--------------------------------------------------------------------------
    | Default HMVC View Path
    |--------------------------------------------------------------------------
    |
    | define the HMVC View directory path
    |
    */
	'hmvc_dir' => __DIR__ . '/../modules',

	/*
    |--------------------------------------------------------------------------
    | Default Vendor Directory Path
    |--------------------------------------------------------------------------
    |
    | define the Vendor directory path
    |
    */
	'vendor_dir' => __DIR__ . '/../vendor',

	/*
    |--------------------------------------------------------------------------
    | Default NSY_System Path
    |--------------------------------------------------------------------------
    |
    | define the NSY_System file path
    |
    */
	'nsy_sys_dir' => __DIR__ . '/../core/NSY_System.php',

	/*
    |--------------------------------------------------------------------------
    | Default Environment Variable File name
    |--------------------------------------------------------------------------
    |
    | set the default env file name
    |
    */
	'env_file' => 'env',

	/*
    |--------------------------------------------------------------------------
    | Default .env File Checking
    |--------------------------------------------------------------------------
    |
    | define the .env file path
    |
    */
	'env_checking_dir' => __DIR__ . '/../../env',

	/*
    |--------------------------------------------------------------------------
    | Default .env File Path Location
    |--------------------------------------------------------------------------
    |
    | define the .env file path
    |
    */
	'env_dir' => __DIR__ . '/../..'

];
