<?php

use System\Middlewares\BeforeLayer;
use System\Middlewares\AfterLayer;

// Initialize optimized router for modules with RouterHelper
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

// HMVC Route - refactored with new routing functions
Route::route('get', '/hmvc', [
    System\Apps\Modules\HMVC\Controllers\Controller_Hello::class,
    'hello'
], [
    'security_level' => 'standar',
    'middleware' => [new BeforeLayer(), new AfterLayer()],
    'name' => 'hmvc_hello'
]);
