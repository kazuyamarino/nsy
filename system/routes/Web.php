<?php
namespace System\Routes;

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Router as Route;

Class Web
{

	public function __construct()
	{
		// define Web Routes, the params format is :
		// Format = Route::type('url', 'namespace\class_controller@method')
		// Route type : any, get, post, put, delete, options, & head

		// MVC Route
		Route::any('', 'System\Controllers\Welcome@index');

		// HMVC Route
		Route::any('hmvc', 'System\Modules\Homepage\Controllers\Hello@index_hmvc');
	}

}
