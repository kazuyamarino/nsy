<?php

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router;

Class Routing {

	public function __construct()
	{
		// define routes, the params format is :
		// Format = ('url', 'namespace\class_controller@method')

		// MVC Route
		NSY_Router::any('nsy/', 'Controllers\Welcome@index');

		// HMVC Route
		NSY_Router::any('nsy/hmvc', 'Modules\Homepage\Controllers\Hello@index_hmvc');

		// execute matched routes
		NSY_Router::dispatch();
	}

}
