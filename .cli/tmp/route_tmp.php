<?php

use System\Middlewares\BeforeLayer;
use System\Middlewares\AfterLayer;

// User defined routes.
// Format :
// Route::method('url', function() {
// 		Route::goto('namespace\class_controller@method');
// });
//
// Route::method('url/@id:num', function($id) {
// 		Route::goto('namespace\class_controller@method', $id);
// });
// Route method : get|post|put|patch|delete|head|options

// MVC Route Example
Route::get('/mvc', function () {
	$middleware = [
		new BeforeLayer(),
		new AfterLayer()
	];

	// Sets up a route with middleware that directs requests to the welcome method within the Controller_Welcome class.
	// A middleware indicates that you're defining a route with middleware. Middleware is a piece of code that filters HTTP requests entering your application. It often performs tasks like authentication, authorization, and session handling.
	Route::middleware($middleware)->for([System\Apps\General\Controllers\Your_Controller::class, 'your_method']);
});

// HMVC Route Example
Route::get('/hmvc', function () {
	Route::goto([System\Apps\Modules\YourModule\Controllers\Your_Controller::class, 'your_method']);
});

// Write here
