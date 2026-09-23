<?php

declare(strict_types=1);

/**
 * Site config — no duplication with System/Config/App.php (App handles app_env, session, timezone, locale, paths, aliases)
 * Each value prefers env() then falls back to default (12-factor, no file edit in production)
 *
 * @return array<string, string>
 */
return [

	/*
	|--------------------------------------------------------------------------
	| Default Site Title
	|--------------------------------------------------------------------------
	|
	| This value is for <title> tag.
	|
	*/
	'sitetitle' => config_env('SITE_TITLE') ?? 'NSY PHP Framework',

	/*
	|--------------------------------------------------------------------------
	| Default Site Author
	|--------------------------------------------------------------------------
	|
	| Define the author of website
	|
	*/
	'siteauthor' => config_env('SITE_AUTHOR') ?? 'Vikry Yuansah',

	/*
	|--------------------------------------------------------------------------
	| Default Site Keywords
	|--------------------------------------------------------------------------
	|
	| This value is for <meta> keyword tag.
	|
	*/
	'sitekeywords' => config_env('SITE_KEYWORDS') ?? 'MVC Framework, HMVC Framework, PHP Framework',

	/*
	|--------------------------------------------------------------------------
	| Default Site Description
	|--------------------------------------------------------------------------
	|
	| This value is for <meta> description tag.
	|
	*/
	'sitedesc' => config_env('SITE_DESC') ?? 'NSY is a simple PHP Framework that works well on MVC or HMVC mode.',

	/*
	|--------------------------------------------------------------------------
	| Default Site Email
	|--------------------------------------------------------------------------
	|
	| Define email contact for website.
	|
	*/
	'siteemail' => config_env('SITE_EMAIL') ?? '',

	/*
	|--------------------------------------------------------------------------
	| Default Version
	|--------------------------------------------------------------------------
	|
	| Define version of the application
	|
	*/
	'version' => config_env('APP_VERSION') ?? '',

	/*
	|--------------------------------------------------------------------------
	| Default Codename
	|--------------------------------------------------------------------------
	|
	| Define codename of the application
	|
	*/
	'codename' => config_env('APP_CODENAME') ?? ''

];
