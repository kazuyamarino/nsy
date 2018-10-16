<?php

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
		$this->meta('name="viewport"', 'width=device-width, initial-scale=1');

		// Favicon
		$this->link_icon('favicon.png', 'shortcut icon');

		// Fonts
		$this->link_url('https://fonts.googleapis.com/css?family=Lato', 'stylesheet', 'text/css');

		// Main Style
		$this->link_css('main', 'stylesheet', 'text/css');

		// Foundation CSS
		$this->link_css('vendor/foundation.min', 'stylesheet', 'text/css');
		$this->link_css('vendor/responsive-tables.min', 'stylesheet', 'text/css');

		// Datatables CSS
		$this->link_css('vendor/dataTables.foundation.min', 'stylesheet', 'text/css');

		// Font Awesome CSS
		$this->custom('<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">');
	}

	function pull_footer_assets()
	{
		// Modernizr JS
		$this->script('vendor/modernizr.min', 'text/javascript', '', '');

		// JQuery JS
		$this->script('vendor/jquery.min', 'text/javascript', '', '');

		// Foundation JS
		$this->script('vendor/foundation.min', 'text/javascript', '', '');
		$this->script('vendor/what-input.min', 'text/javascript', '', '');
		$this->script('vendor/responsive-tables.min', 'text/javascript', '', '');

		// Datatables JS
		$this->script('vendor/jquery.dataTables.min', 'text/javascript', '', '');
		$this->script('vendor/dataTables.foundation.min', 'text/javascript', '', '');

		// Google Analytics: change UA-XXXXX-Y to be your site's ID.
		$this->custom("<script>window.ga=function(){ga.q.push(arguments)}; ga.q=[]; ga.l=+new Date;ga('create','UA-XXXXX-Y','auto'); ga('send','pageview')</script>"
		);
		$this->script_url('https://www.google-analytics.com/analytics', 'text/javascript', '', 'async defer');

		// Base JS
		$this->script('main', 'text/javascript', '', '');
	}

	function datatables_init() {
		$this->script('datatables/init', 'text/javascript', '', '');
	}

}
