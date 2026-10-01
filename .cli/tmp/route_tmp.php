<?php

/**
 * Routes are auto-discovered from System/Routes/*.php — no registration needed.
 *
 * Simple form — Route::get($path, $callback) takes only 2 arguments:
 *   Route::get('/path', [System\Apps\General\Controllers\Your_Controller::class, 'method']);
 *   Route::get('/path', function () { echo 'Hello'; });
 *
 * With a route name (used by the route() URL helper) use Route::route($method, $path, $controller, $options):
 *   Route::route('get', '/path', [Your_Controller::class, 'method'], [
 *       'name' => 'route.name',
 *   ]);
 *
 * Methods : get | post | put | patch | delete | head | options | any | map
 * Patterns: (:any) (:num) (:alpha) (:alnum) (:slug) (:all)
 */

// MVC route example
Route::get('/example', [
	System\Apps\General\Controllers\Controller_Welcome::class,
	'welcome'
]);

// HMVC route example
Route::get('/example-hmvc', function () {
	Route::goto([
		System\Apps\Modules\HMVC\Controllers\Controller_Hello::class,
		'hello'
	]);
});

// Named route (options require Route::route())
Route::route('get', '/admin/dashboard', [
	System\Apps\General\Controllers\Controller_Welcome::class,
	'welcome'
], [
	'name' => 'admin.dashboard'
]);

// Group example
Route::group('/admin', function () {
	Route::get('/reports', [
		System\Apps\General\Controllers\Controller_Welcome::class,
		'welcome'
	]);
});

// Write your routes below
