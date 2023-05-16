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
Route::get('/', function () {
	$middleware = [
		new BeforeLayer(),
		new AfterLayer()
	];

	Route::middleware($middleware)->for([System\Controllers\Welcome::class, 'index']);
});

// HMVC Route
Route::get('/hmvc', function () {
	Route::goto([System\Modules\HMVC\Controllers\Hello::class, 'index_hmvc']);
});