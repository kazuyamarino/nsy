<?php
namespace System\Routes;

use System\Core\NSY_Router as Route;

Class Api
{

	public function __construct()
	{
		// define API Routes, the params format is :
		// Format = Route::type('url', 'namespace\class_controller@method')
		// Route type : any, get, post, put, and delete

		// Api Route
		// Route::any('data', 'Controllers\Welcome@data'); // Example
	}

}
