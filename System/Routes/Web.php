<?php

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Router;
use System\Core\NSY_Controller;

Class Routing extends NSY_Controller {

	public function __construct()
	{
		// define routes, the params format is :
		// Format = ('url', 'namespace\class_controller@method')

		// MVC Route
		NSY_Router::any('nsy/', 'System\Controllers\Welcome@index');

		// HMVC Route
		NSY_Router::any('nsy/hmvc', 'System\Modules\Homepage\Controllers\Hello@index_hmvc');

		// execute matched routes
		NSY_Router::dispatch();
	}

}
