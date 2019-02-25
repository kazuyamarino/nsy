<?php
defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router;
use Core\NSY_Controller;

Class Web
{

	public function __construct()
	{
		// define routes, the params format is :
		// Format = NSY_Router::type('url', 'namespace\class_controller@method')
		// Route type : get, post, put, delete, options, head

		// MVC Route
		NSY_Router::any('nsy/', 'Controllers\Welcome@index');

		// HMVC Route
		NSY_Router::any('nsy/hmvc', 'Modules\Controllers\Hello@index_hmvc');
	}

}
