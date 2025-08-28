<?php

use System\Middlewares\BeforeLayer;
use System\Middlewares\AfterLayer;

// Initialize optimized router with RouterHelper
Route::initOptimizedRouter([
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
    'security_level' => 'standar',
    'middleware' => [new BeforeLayer(), new AfterLayer()],
    'name' => 'home'
]);
