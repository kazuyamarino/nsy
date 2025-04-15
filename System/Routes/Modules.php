<?php
// define Web Routes.
//
// Format 1 :
// Route::get('/url', ['namespace\class_controller::class', 'method/function']);
//
// Format 2 :
// Route::method('url/(:num)', function($id) {
//      Route::goto(['namespace\class_controller::class', 'method/function']);
// });
//
// Route method : any|get|post|put|patch|delete|head|options

// HMVC Route
Route::get('/hmvc', function () {
	Route::goto([System\Apps\Modules\HMVC\Controllers\Controller_Hello::class, 'hello']);
});
