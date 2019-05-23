<?php
defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router;

Class Web
{

	public function __construct()
	{
		// define Web routes, the params format is :
		// Format = route::type('url', 'namespace\class_controller@method')
		// Route type : any, get, post, put, delete, options, & head

		// MVC Route
		route::any('nsy/', 'Controllers\Welcome@index');

		// HMVC Route
		route::any('nsy/hmvc', 'Modules\Controllers\Hello@index_hmvc');
	}

}
