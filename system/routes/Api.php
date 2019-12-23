<?php
namespace Routes;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router as route;

Class Api
{

	public function __construct()
	{
		// define API routes, the params format is :
		// Format = route::type('url', 'namespace\class_controller@method')
		// Route type : any, get, post, put, delete, options, head

		// Api Route
		// route::any('data', 'Controllers\Welcome@data'); // Example
	}

}
