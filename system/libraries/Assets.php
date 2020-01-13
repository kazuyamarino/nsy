<?php
namespace System\Libraries;

use System\Core\NSY_AssetManager as Add;

/**
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*
* Hi Welcome to NSY Assets Manager.
* The easiest & best assets manager in history
* Made with love by Vikry Yuansah
*
* How to use it? Simply follow this format.
* Create <meta> tag :
* Add::meta('name', 'content');
*
* Create <link> tag :
* Add::link('filename/url_filename', 'attribute_rel', 'attribute_type');
*
* Create <script> tag :
* Add::script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');
*
* You can write any html tags with custom method :
* Add::custom('anythings');
*
* After that, to use it in View, you only need to call the static method name that you created like this :
* Pull::method_name();
*
* For example :
* Pull::header_assets();
* Pull::footer_assets();
*/
Class Assets extends \System\Core\NSY_AssetManager
{

	public static function header_assets()
	{
		// Site Title
		Add::custom('<title>' . get_title() . ' ' . get_version() . ' | ' . get_codename() . '</title>');

		// Meta Tag
		Add::meta('charset="utf-8"', null);
		Add::meta('http-equiv="x-ua-compatible"', 'ie=edge');
		Add::meta('name="description"', get_desc());
		Add::meta('name="keywords"', get_keywords());
		Add::meta('name="author"', get_author());
		Add::meta('name="viewport"', 'width=device-width, initial-scale=1, shrink-to-fit=no');

		// Favicon
		Add::link('favicon.png', 'shortcut icon', null);

		// Main Style
		Add::link('main.css', 'stylesheet', 'text/css');
	}

	public static function footer_assets()
	{
		// System JS
		Add::script('config/system.js', 'text/javascript', 'UTF-8', null);

		// Main JS
		Add::script('main.js', 'text/javascript', 'UTF-8', null);
	}

}
