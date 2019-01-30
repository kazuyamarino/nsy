<?php
/*
Hi Welcome to NSY Asset Manager.
The easiest & best asset manager in history
Made with love by Vikry Yuansah

How to use it? Simply follow this format.
Create <meta> tag :
$this->meta('name', 'content');

Create <link> tag :
$this->link('filename/url_filename', 'attribute_rel', 'attribute_type');

Create <script> tag :
$this->script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');

You can write any html tags with custom method :
$this->custom('anythings');
*/

namespace System\Libraries\AssetManager;

use System\Libraries\AssetManager\NSY_AssetManager;

defined('ROOT') OR exit('No direct script access allowed');

Class Assets extends NSY_AssetManager {

	function pull_header_assets()
	{
		// Site Title
		$this->custom('<title>' . SITETITLE . '</title>');

		// Meta Tag
		$this->meta('charset="utf-8"', '');
		$this->meta('http-equiv="x-ua-compatible"', 'ie=edge');
		$this->meta('name="description"', SITEDESCRIPTION);
		$this->meta('name="keywords"', SITEKEYWORDS);
		$this->meta('name="author"', SITEAUTHOR);
		$this->meta('name="viewport"', 'width=device-width, initial-scale=1, shrink-to-fit=no');

		// Favicon
		$this->link('favicon.png', 'shortcut icon', '');

		// Main Style
		$this->link('main.css', 'stylesheet', 'text/css');

		// Modernizr JS
		$this->script('vendor/modernizr.min.js', 'text/javascript', 'UTF-8', '');
	}

	function pull_footer_assets()
	{
		// JQuery JS
		$this->script('vendor/jquery.min.js', 'text/javascript', 'UTF-8', '');

		// Base JS
		$this->script('main.js', 'text/javascript', 'UTF-8', '');
	}

}
