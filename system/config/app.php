<?php
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
	'public_dir' => 'public',

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
	'tmp_dir' => __DIR__ . '/../../public',

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
	'hmvc_dir' => __DIR__ . '/../modules/*/views',

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
    | Default NSY_System Path
    |--------------------------------------------------------------------------
    |
    | defines the NSY_System file path
    |
    */
	'env_dir' => __DIR__ . '/../..'

];