<?php
use System\Middlewares\BeforeLayer;
use System\Middlewares\AfterLayer;

// define Web Routes.
// Format :
// Route::method('url', function() {
// 		Route::goto('namespace\class_controller@method');
// });
//
// Route::method('url/@id:num', function($id) {
// 		Route::goto('namespace\class_controller@method', $id);
// });
// Route method : any|get|post|put|patch|delete|head|options

// MVC Route
Route::get('/', function() {
	$middleware = [
		new BeforeLayer(),
		new AfterLayer()
	];

	Route::middleware($middleware)->for('Welcome@index');
});

// HMVC Route
Route::get('/hmvc', function() {
	Route::goto('Homepage\Hello@index_hmvc');
});

// This is just routing for testing, ignore or delete it.
Route::get('/test/universal', 'Controller_Test@test_url');
