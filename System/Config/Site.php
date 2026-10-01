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
	'sitedesc' => config_env('SITE_DESC') ?? 'Simple. Layered. Harmony in MVC and HMVC.',

	/*
	|--------------------------------------------------------------------------
	| Default Site Email
	|--------------------------------------------------------------------------
	|
	| Define email contact for website. Uses ?: (not ??) so an env value of
	| '' still falls back to the default, otherwise the footer would render
	| a broken href="mailto:".
	|
	*/
	'siteemail' => config_env('SITE_EMAIL') ?: 'vikry.yuansah@gmail.com',

	/*
	|--------------------------------------------------------------------------
	| Default Version
	|--------------------------------------------------------------------------
	|
	| Define version of the application. Falls back to the framework release
	| when APP_VERSION is unset or empty, so <title>/footer never render blank.
	|
	*/
	'version' => config_env('APP_VERSION') ?: '7.0.0',

	/*
	|--------------------------------------------------------------------------
	| Default Codename
	|--------------------------------------------------------------------------
	|
	| Define codename of the application. Falls back to the framework codename
	| when APP_CODENAME is unset or empty.
	|
	*/
	'codename' => config_env('APP_CODENAME') ?: 'Gamelan',

	/*
	|--------------------------------------------------------------------------
	| Project Repository URL
	|--------------------------------------------------------------------------
	|
	| Canonical GitHub repository for this project. Referenced by the site
	| footer, the "View on GitHub" / "Releases" buttons, the ↗ links on
	| every documentation card and the codename deep link. Define it once
	| here instead of repeating the literal.
	|
	*/
	'repo_url' => config_env('REPO_URL') ?: 'https://github.com/kazuyamarino/nsy',

	/*
	|--------------------------------------------------------------------------
	| Project Start Year
	|--------------------------------------------------------------------------
	|
	| First release year, rendered as a copyright range in the footer.
	| String, to keep this config file homogeneous.
	|
	*/
	'since' => config_env('SINCE_YEAR') ?: '2018'

];
