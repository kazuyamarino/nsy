<?php


// Initialize NSY Router with full optimization
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

// ==========================================
// 1. Basic HTTP Methods
// ==========================================

// Home page with standard security
Route::get('/', [HomeController::class, 'index']);

// User management routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/(:num)', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/(:num)', [UserController::class, 'update']);
Route::delete('/users/(:num)', [UserController::class, 'destroy']);

// ==========================================
// 2. Enhanced Routes with Security Levels
// ==========================================

// Public routes with basic security (200 req/min)
Route::route('get', '/about', [PublicController::class, 'about'], [
    'security_level' => 'basic',
    'name' => 'about'
]);

Route::route('get', '/contact', [PublicController::class, 'contact'], [
    'security_level' => 'basic',
    'name' => 'contact'
]);

// Standard security routes (100 req/min + CSRF)
Route::route('post', '/contact', [PublicController::class, 'submitContact'], [
    'security_level' => 'standard',
    'name' => 'contact.submit'
]);

Route::route('get', '/profile', [UserController::class, 'profile'], [
    'security_level' => 'standard',
    'name' => 'user.profile'
]);

// ==========================================
// 3. API Routes Group with Strict Security
// ==========================================

Route::group('/api/v1', function() {
    // Strict security (30 req/min + Enhanced protection)
    Route::route('get', '/users', [ApiController::class, 'getAllUsers'], [
        'security_level' => 'strict',
        'name' => 'api.users.index'
    ]);

    Route::route('post', '/users', [ApiController::class, 'createUser'], [
        'security_level' => 'strict',
        'name' => 'api.users.store'
    ]);

    Route::route('get', '/users/(:num)', [ApiController::class, 'getUser'], [
        'security_level' => 'strict',
        'name' => 'api.users.show'
    ]);

    Route::route('put', '/users/(:num)', [ApiController::class, 'updateUser'], [
        'security_level' => 'strict',
        'name' => 'api.users.update'
    ]);

    Route::route('delete', '/users/(:num)', [ApiController::class, 'deleteUser'], [
        'security_level' => 'strict',
        'name' => 'api.users.destroy'
    ]);

    // API with custom middleware
    Route::post('/upload', function() {
        $customMiddleware = [
            Route::createSecurityMiddleware('strict'),
            new FileUploadMiddleware()
        ];

        return Route::middleware($customMiddleware)->for([ApiController::class, 'upload']);
    });
});

// ==========================================
// 4. Admin Panel with Maximum Security
// ==========================================

Route::group('/admin', function() {
    // Dashboard with comprehensive middleware
    Route::route('get', '/dashboard', [AdminController::class, 'dashboard'], [
        'security_level' => 'strict',
        'middleware' => [
            new AuthMiddleware(['role' => 'admin'])
        ],
        'name' => 'admin.dashboard'
    ]);

    // Settings management
    Route::route('get', '/settings', [AdminController::class, 'settings'], [
        'security_level' => 'strict',
        'middleware' => [new AuthMiddleware()],
        'name' => 'admin.settings'
    ]);

    Route::route('post', '/settings', [AdminController::class, 'updateSettings'], [
        'security_level' => 'strict',
        'middleware' => [new AuthMiddleware()],
        'name' => 'admin.settings.update'
    ]);

    // User management for admins
    Route::route('get', '/users', [AdminController::class, 'manageUsers'], [
        'security_level' => 'strict',
        'name' => 'admin.users.manage'
    ]);

    Route::route('post', '/users/(:num)/ban', [AdminController::class, 'banUser'], [
        'security_level' => 'strict',
        'name' => 'admin.users.ban'
    ]);
});

// ==========================================
// 5. Advanced Route Features
// ==========================================

// Multiple HTTP methods for same route
Route::map(['GET', 'POST'], '/webhook/github', [WebhookController::class, 'handle']);

// Catch-all route
Route::any('/catch-all/(:all)', [FallbackController::class, 'handle']);

// Manual middleware application
Route::get('/protected-resource', function() {
    $middleware = [
        Route::createSecurityMiddleware('strict'),
        new RateLimitMiddleware(['limit' => 10]),
        new LoggingMiddleware()
    ];

    return Route::middleware($middleware)->for([ProtectedController::class, 'resource']);
});

// Route with parameter validation
Route::get('/posts/(:slug)', function($slug) {
    // Custom parameter handling
    if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
        Route::error(function() {
            return ErrorController::badRequest();
        });
        return;
    }

    Route::goto([PostController::class, 'show'], [$slug]);
});

// ==========================================
// 6. Performance Monitoring Examples
// ==========================================

// Monitor heavy operations
Route::get('/heavy-operation', function() {
    return Route::monitorRoute('/heavy-operation', function() {
        return HeavyController::processLargeData();
    });
});

// Performance statistics route (for debugging)
Route::get('/debug/performance', function() {
    $stats = Route::getPerformanceStats();
    $debug = Route::debugInfo();

    return DebugController::showStats($stats, $debug);
});

// Cache management routes (admin only)
Route::post('/admin/cache/clear', function() {
    Route::clearCaches();
    return AdminController::cacheCleared();
});

// ==========================================
// 7. Advanced Route Group Creation
// ==========================================

// Using createRouteGroup helper for complex route groups with middleware
$adminRouteGroup = Route::createRouteGroup('/admin/api', [
    new AuthMiddleware(['role' => 'admin']),
    Route::createSecurityMiddleware('strict'),
    new LoggingMiddleware()
], function($middleware) {
    // All routes in this group automatically inherit the middleware
    Route::get('/stats', [AdminApiController::class, 'getStats']);
    Route::post('/backup', [AdminApiController::class, 'createBackup']);
    Route::delete('/cache', [AdminApiController::class, 'clearAllCache']);
});

// Execute the route group
$adminRouteGroup();

// ==========================================
// 8. Cache and Performance Management
// ==========================================

// Manual cache control examples
Route::get('/admin/cache/enable', function() {
    Route::enableCache(true);
    return AdminController::cacheEnabled('Cache has been enabled');
});

Route::get('/admin/cache/disable', function() {
    Route::enableCache(false);
    return AdminController::cacheDisabled('Cache has been disabled');
});

// Get individual cache statistics
Route::get('/admin/cache/stats', function() {
    $routerStats = Route::getCacheStats();
    $performanceStats = Route::getPerformanceStats();

    return AdminController::showCacheStats([
        'router_cache' => $routerStats,
        'performance' => $performanceStats
    ]);
});

// Clear specific cache types
Route::post('/admin/cache/routes/clear', function() {
    Route::clearCache(); // Clear route cache only
    return AdminController::routeCacheCleared();
});

Route::post('/admin/cache/controllers/clear', function() {
    Route::clearControllerPool(); // Clear controller pool only
    return AdminController::controllerPoolCleared();
});

// Security configuration examples
Route::post('/admin/security/configure', function() {
    Route::configureSecurity([
        'validate_params' => true,
        'sanitize_input' => true,
        'csrf_protection' => true,
        'rate_limiting' => true
    ]);

    return AdminController::securityConfigured('Security settings updated');
});

// ==========================================
// 9. CSRF Token Management Examples
// ==========================================

// Get CSRF token programmatically
Route::get('/api/csrf-token', function() {
    return json_encode([
        'csrf_token' => Route::csrf(),
        'expires_in' => 3600 // 1 hour
    ]);
});

// Validate CSRF token manually in route
Route::post('/api/secure-action', function() {
    $providedToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    $validToken = Route::csrf();

    if (!hash_equals($validToken, $providedToken)) {
        http_response_code(403);
        return json_encode(['error' => 'CSRF token mismatch']);
    }

    return SecureController::performAction();
});

// ==========================================
// 10. Route Dispatcher Configuration
// ==========================================

// Advanced dispatcher setup (typically in bootstrap file)
Route::get('/bootstrap/example', function() {
    // Set up router configuration
    Route::haltOnMatch(false); // Continue processing after matches
    Route::enableCache(true);   // Enable route caching

    // Configure comprehensive security
    Route::configureSecurity([
        'validate_params' => true,
        'sanitize_input' => true,
        'csrf_protection' => false, // Disable for API endpoints
        'rate_limiting' => true
    ]);

    // Finally dispatch all routes
    Route::dispatch();

    return BootstrapController::routerConfigured();
});

// ==========================================
// 11. Error Handling
// ==========================================

// Custom 404 handler
Route::error(function() {
    return ErrorController::notFound();
});

// Set halt behavior
Route::haltOnMatch(true);

// ==========================================
// 12. HTTP Method Examples (Complete Coverage)
// ==========================================

// HEAD method for resource checking without content
Route::head('/api/users/(:num)', [ApiController::class, 'checkUserExists']);

// OPTIONS method for CORS preflight requests
Route::options('/api/users', function() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN');
    return '';
});

// PATCH method for partial updates
Route::patch('/api/users/(:num)', [ApiController::class, 'partialUpdate']);

// ==========================================
// 13. Direct Controller Execution Examples
// ==========================================

// Direct controller method execution without route matching
Route::get('/direct-execution-example', function() {
    // Execute controller method directly
    $result = Route::goto([DirectController::class, 'processData'], ['param1', 'param2']);

    // Execute with middleware chain
    $middlewareResult = Route::middleware([
        new AuthMiddleware(),
        new LoggingMiddleware()
    ])->for([DirectController::class, 'secureMethod'], ['secure_param']);

    return DirectController::combineResults($result, $middlewareResult);
});

// ==========================================
// 14. CSRF Protection Example
// ==========================================

Route::get('/form-example', [ExampleController::class, 'showForm']);
Route::post('/form-example', [ExampleController::class, 'processForm']);

/*
In your view file (form-example.php):

<!DOCTYPE html>
<html>
<head>
    <?= Route::csrfMeta() ?>
    <title>Example Form</title>
</head>
<body>
    <form method="POST" action="/form-example">
        <?= Route::csrfField() ?>

        <input type="text" name="name" placeholder="Name">
        <input type="email" name="email" placeholder="Email">
        <button type="submit">Submit</button>
    </form>

    <script>
        // For AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/api/v1/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({name: 'John', email: 'john@example.com'})
        });
    </script>
</body>
</html>
*/
