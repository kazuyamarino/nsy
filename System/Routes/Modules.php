<?php
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

// HMVC Route
Route::get('/hmvc', function () {
	Route::goto([System\Apps\Modules\HMVC\Controllers\Controller_Hello::class, 'hello']);
});
