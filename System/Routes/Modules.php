<?php


// Initialize optimized router for modules with RouterHelper
Route::initRouter([
	'cache_enabled' => true,
	'security' => [
		'validate_params' => true,
		'sanitize_input' => true,
		'csrf_protection' => true,
		'rate_limiting' => true
	]
]);

// HMVC Route - refactored with new routing functions
Route::route('get', '/hmvc', [
	System\Apps\Modules\HMVC\Controllers\Controller_Hello::class,
	'hello'
], [
	'name' => 'hmvc_hello'
]);
