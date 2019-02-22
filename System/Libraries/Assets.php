<?php
/*
Hi Welcome to NSY Asset Manager.
The easiest & best asset manager in history
Made with love by Vikry Yuansah

How to use it? Simply follow this format.
Create <meta> tag :
self::meta('name', 'content');

Create <link> tag :
self::link('filename/url_filename', 'attribute_rel', 'attribute_type');

Create <script> tag :
self::script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');

You can write any html tags with custom method :
self::custom('anythings');
*/

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_AssetManager;

Class Assets extends NSY_AssetManager {

	public static function pull_header_assets()
	{
		// Site Title
		self::custom('<title>' . SITETITLE . '</title>');

		// Meta Tag
		self::meta('charset="utf-8"', '');
		self::meta('http-equiv="x-ua-compatible"', 'ie=edge');
		self::meta('name="description"', SITEDESCRIPTION);
		self::meta('name="keywords"', SITEKEYWORDS);
		self::meta('name="author"', SITEAUTHOR);
		self::meta('name="viewport"', 'width=device-width, initial-scale=1, shrink-to-fit=no');

		// Favicon
		self::link('favicon.png', 'shortcut icon', '');

		// Main Style
		self::link('main.css', 'stylesheet', 'text/css');
	}

	public static function pull_footer_assets()
	{
		// Base JS
		self::script('main.js', 'text/javascript', 'UTF-8', '');
	}

}
