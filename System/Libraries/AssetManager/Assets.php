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

		// Fonts
		$this->link('https://fonts.googleapis.com/css?family=Lato', 'stylesheet', 'text/css');

		// Main Style
		$this->link('main.css', 'stylesheet', 'text/css');

		// Foundation CSS
		$this->link('vendor/foundation.min.css', 'stylesheet', 'text/css');
		$this->link('vendor/responsive-tables.min.css', 'stylesheet', 'text/css');

		// Datatables CSS
		$this->link('vendor/dataTables.foundation.min.css', 'stylesheet', 'text/css');

		// Font Awesome CSS
		$this->custom('<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">');

		// Modernizr JS
		$this->script('vendor/modernizr.min.js', 'text/javascript', 'UTF-8', '');
	}

	function pull_footer_assets()
	{
		// JQuery JS
		$this->script('vendor/jquery.min.js', 'text/javascript', 'UTF-8', '');

		// Foundation JS
		$this->script('vendor/foundation.min.js', 'text/javascript', 'UTF-8', '');
		$this->script('vendor/what-input.min.js', 'text/javascript', 'UTF-8', '');
		$this->script('vendor/responsive-tables.min.js', 'text/javascript', 'UTF-8', '');

		// Datatables JS
		$this->script('vendor/jquery.dataTables.min.js', 'text/javascript', 'UTF-8', '');
		$this->script('vendor/dataTables.foundation.min.js', 'text/javascript', 'UTF-8', '');

		// Google Analytics: change UA-XXXXX-Y to be your site's ID.
		$this->custom("<script>window.ga=function(){ga.q.push(arguments)}; ga.q=[]; ga.l=+new Date;ga('create','UA-XXXXX-Y','auto'); ga('send','pageview')</script>");
		$this->script('https://www.google-analytics.com/analytics.js', 'text/javascript', '', 'async defer');

		// Base JS
		$this->script('main.js', 'text/javascript', 'UTF-8', '');
	}

	function datatables_init() {
		$this->script('datatables/init.js', 'text/javascript', 'UTF-8', '');
	}

}
