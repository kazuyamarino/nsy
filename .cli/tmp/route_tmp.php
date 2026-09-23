<?php

/**
 * Routes are auto-discovered from System/Routes/*.php — no registration needed.
 *
 * Format 1: controller
 *   Route::get('/path', [System\Apps\General\Controllers\Your_Controller::class, 'method']);
 *
 * Format 2: closure
 *   Route::get('/path', function () { echo 'Hello'; });
 *
 * Methods: get | post | put | patch | delete | head | options | any | map
 * Patterns: (:any) (:num) (:alpha) (:alnum) (:slug) (:all)
 * Options : ['security_level' => 'basic|standard|strict', 'name' => 'route.name']
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

// Group example
Route::group('/admin', function () {
	Route::get('/dashboard', [
		System\Apps\General\Controllers\Controller_Welcome::class,
		'welcome'
	], ['security_level' => 'strict', 'name' => 'admin.dashboard']);
});

// Write your routes below
