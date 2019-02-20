<?php

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Router;
use System\Core\NSY_Controller;

Class Web extends NSY_Controller {

	public function __construct()
	{
		// define routes, the params format is :
		// Format = NSY_Router::type('url', 'namespace\class_controller@method')
		// Route type : get, post, put, delete, options, head

		// MVC Route
		NSY_Router::any('nsy/', 'System\Controllers\Welcome@index');

		// HMVC Route
		NSY_Router::any('nsy/hmvc', 'System\Modules\Homepage\Controllers\Hello@index_hmvc');

		// execute matched routes
		NSY_Router::dispatch();
	}

}
