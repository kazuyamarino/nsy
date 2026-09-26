<?php


// Initialize optimized router with RouterHelper
Route::initRouter([
    'cache_enabled' => true,
    'security' => [
        'validate_params' => true,
        'sanitize_input' => true,
        'csrf_protection' => true,
        'rate_limiting' => true
    ],
    'performance' => [
        'controller_pooling' => true,
        'route_compilation' => true,
        'cache_warm_up' => true
    ]
]);

// Home route
Route::route('get', '/', [
    System\Apps\General\Controllers\Controller_Welcome::class,
    'welcome'
], [
    'security_level' => 'standard',
    'name' => 'home'
]);

// In-app documentation viewer — reads /docs/*.md and renders them as HTML.
// Note: Route::get() is used (not Route::route()) so the (:slug) parameter is
// forwarded to the controller.
Route::get('/docs/(:slug)', [
    System\Apps\General\Controllers\Controller_Docs::class,
    'show'
]);
