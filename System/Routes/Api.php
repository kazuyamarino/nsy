<?php

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Router;
use System\Core\NSY_Controller;

Class Api extends NSY_Controller {

	public function __construct()
	{
		// define API routes, the params format is :
		// Format = NSY_Router::type('url', 'namespace\class_controller@method')
		// Route type : get, post, put, delete, options, head

		// Api Route
		// NSY_Router::post('nsy/data', 'Controller@showData'); // Example

		// execute matched routes
		NSY_Router::dispatch();
	}

}
