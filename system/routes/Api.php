<?php
defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router;
use Core\NSY_Controller;

Class Api
{

	public function __construct()
	{
		// define API routes, the params format is :
		// Format = route::type('url', 'namespace\class_controller@method')
		// Route type : any, get, post, put, delete, options, head

		// Api Route
		// route::any('nsy/data', 'Controllers\Welcome@data'); // Example
	}

}
