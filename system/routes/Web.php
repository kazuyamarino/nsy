<?php
namespace Routes;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router as route;

Class Web
{

	public function __construct()
	{
		// define Web routes, the params format is :
		// Format = route::type('url', 'namespace\class_controller@method')
		// Route type : any, get, post, put, delete, options, & head

		// MVC Route
		route::any('', 'Controllers\Welcome@index');

		// HMVC Route
		route::any('hmvc', 'Modules\Homepage\Controllers\Hello@index_hmvc');
	}

}
