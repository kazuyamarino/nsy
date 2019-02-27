<?php
/*
Hi Welcome to NSY Asset Manager.
The easiest & best asset manager in history
Made with love by Vikry Yuansah

How to use it? Simply follow this format.
Create <meta> tag :
add::meta('name', 'content');

Create <link> tag :
add::link('filename/url_filename', 'attribute_rel', 'attribute_type');

Create <script> tag :
add::script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');

You can write any html tags with custom method :
add::custom('anythings');

After that, to use it in View, you only need to call the static method name that you created like this :
pull::method_name();

For example :
pull::header_assets();
pull::footer_assets();
*/

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_AssetManager;

Class Assets extends NSY_AssetManager
{

	public static function header_assets()
	{
		// Site Title
		add::custom('<title>' . SITETITLE . '</title>');

		// Meta Tag
		add::meta('charset="utf-8"', '');
		add::meta('http-equiv="x-ua-compatible"', 'ie=edge');
		add::meta('name="description"', SITEDESCRIPTION);
		add::meta('name="keywords"', SITEKEYWORDS);
		add::meta('name="author"', SITEAUTHOR);
		add::meta('name="viewport"', 'width=device-width, initial-scale=1, shrink-to-fit=no');

		// Favicon
		add::link('favicon.png', 'shortcut icon', '');

		// Main Style
		add::link('main.css', 'stylesheet', 'text/css');
	}

	public static function footer_assets()
	{
		// Base JS
		add::script('main.js', 'text/javascript', 'UTF-8', '');
	}

}
