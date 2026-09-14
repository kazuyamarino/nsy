# NSY Router Documentation

The NSY Router system provides a powerful, optimized routing solution with built-in security features, performance enhancements, and integrated SecurityMiddleware support. This documentation covers all available functions and their usage.

## Table of Contents

1. [Quick Start](#quick-start)
2. [Installation & Setup](#installation--setup)
3. [Basic Routing](#basic-routing)
4. [HTTP Methods](#http-methods)
5. [Route Parameters](#route-parameters)
6. [Route Groups](#route-groups)
7. [Security Integration](#security-integration)
8. [Security Features](#security-features)
9. [Performance Optimization](#performance-optimization)
10. [Cache Management](#cache-management)
11. [CSRF Protection](#csrf-protection)
12. [Error Handling](#error-handling)
13. [Advanced Features](#advanced-features)
14. [API Reference](#api-reference)

## Quick Start

```php
<?php

// Initialize the router
Route::initRouter();

// Define a simple route
Route::get('/hello', function() {
    echo 'Hello World!';
});

// Route with controller
Route::get('/users', [UserController::class, 'index']);

// Route with parameters
Route::get('/users/(:num)', [UserController::class, 'show']);
?>
```

## Installation & Setup

### Basic Setup

```php
<?php

// Initialize router with default optimizations
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
?>
```

### Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `cache_enabled` | boolean | `true` | Enable route caching for better performance |
| `security.validate_params` | boolean | `true` | Validate route parameters |
| `security.sanitize_input` | boolean | `true` | Sanitize input data |
| `security.csrf_protection` | boolean | `true` | Enable CSRF protection |
| `security.rate_limiting` | boolean | `true` | Enable rate limiting |
| `performance.controller_pooling` | boolean | `true` | Pool controller instances |
| `performance.route_compilation` | boolean | `true` | Compile routes for faster matching |
| `performance.cache_warm_up` | boolean | `true` | Pre-warm route cache |

## Basic Routing

### Simple Routes

```php
// GET route with closure
Route::get('/home', function() {
    return 'Welcome to NSY Framework!';
});

// POST route with controller
Route::post('/contact', [ContactController::class, 'submit']);

// Route with multiple HTTP methods
Route::map(['GET', 'POST'], '/form', [FormController::class, 'handle']);

// Route that accepts any HTTP method
Route::any('/webhook', [WebhookController::class, 'receive']);
```

### Route Parameters

```php
// Numeric parameter
Route::get('/user/(:num)', [UserController::class, 'show']);

// String parameter
Route::get('/post/(:slug)', [PostController::class, 'show']);

// Multiple parameters
Route::get('/user/(:num)/post/(:slug)', [PostController::class, 'userPost']);

// Optional parameters with regex
Route::get('/search/(:any?)', [SearchController::class, 'search']);
```

### Parameter Types

| Pattern | Description | Example |
|---------|-------------|---------|
| `(:all)` | Matches everything including slashes | `api/v1/users` |
| `(:any)` | Matches any character except slash | `john-doe` |
| `(:slug)` | Matches lowercase letters, numbers, hyphens | `my-blog-post` |
| `(:uslug)` | Matches letters, numbers, underscores, hyphens | `My_Blog-Post` |
| `(:num)` | Matches numbers only | `123` |
| `(:alpha)` | Matches letters only | `john` |
| `(:alnum)` | Matches letters and numbers | `user123` |
| `(:date)` | Matches date format YYYY-MM-DD | `2024-01-15` |

## HTTP Methods

The NSY Router supports all standard HTTP methods:

```php
// Standard HTTP methods
Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/(:num)', [UserController::class, 'update']);
Route::patch('/users/(:num)', [UserController::class, 'partialUpdate']);
Route::delete('/users/(:num)', [UserController::class, 'destroy']);

// Additional HTTP methods
Route::head('/users/(:num)', [UserController::class, 'checkExists']);
Route::options('/users', [UserController::class, 'getOptions']);

// Multiple methods for same route
Route::map(['GET', 'POST'], '/api/data', [ApiController::class, 'handleData']);

// Any method
Route::any('/catch-all', [CatchAllController::class, 'handle']);
```

## Route Groups

### Basic Route Groups

```php
// Simple route group with prefix
Route::group('/api/v1', function() {
    Route::get('/users', [ApiController::class, 'getUsers']);
    Route::post('/users', [ApiController::class, 'createUser']);
    Route::get('/users/(:num)', [ApiController::class, 'getUser']);
});
```

### Advanced Route Groups with Security

```php
// Route group with security validation
Route::group('/admin', function() {
    // Manual security checks within routes
    Route::get('/dashboard', function() {
        // Validate authentication and authorization here
        return AdminController::dashboard();
    });
    
    Route::get('/users', function() {
        // Apply security middleware checks
        $security = Route::createSecurityMiddleware('strict');
        return AdminController::manageUsers();
    });
    
    Route::post('/settings', function() {
        // CSRF and input validation
        if (!SecurityMiddleware::validateCSRFToken($_POST['csrf_token'] ?? null)) {
            http_response_code(403);
            return 'CSRF validation failed';
        }
        
        $cleanData = SecurityMiddleware::sanitizeForm($_POST);
        return AdminController::updateSettings($cleanData);
    });
});
```

### Advanced Route Group Creation

```php
// Create optimized route group with prefix and middleware
$apiGroup = Route::createRouteGroup('/api/v1', [
    Route::createSecurityMiddleware('standard')
], function($middleware) {
    Route::get('/users', [ApiController::class, 'getUsers']);
    Route::post('/users', [ApiController::class, 'createUser']);
    Route::put('/users/(:num)', [ApiController::class, 'updateUser']);
});

// Execute the route group
$apiGroup();
```

## Security Integration

### Creating Security Middleware

```php
// Create middleware with different security levels
$basicSecurity = Route::createSecurityMiddleware('basic');    // 200 req/min
$standardSecurity = Route::createSecurityMiddleware('standard'); // 100 req/min + CSRF
$strictSecurity = Route::createSecurityMiddleware('strict');  // 30 req/min + Enhanced protection
```

### Security Through SecurityMiddleware

```php
// Direct security validation in routes
Route::post('/secure-action', function() {
    // Validate CSRF token
    if (!SecurityMiddleware::validateCSRFToken($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        return 'CSRF validation failed';
    }
    
    // Sanitize input
    $cleanData = SecurityMiddleware::sanitizeForm($_POST);
    
    return SecureController::processData($cleanData);
});

// Using the enhanced route method with built-in security
Route::route('get', '/profile', [UserController::class, 'profile'], [
    'security_level' => 'standard',
    'name' => 'user.profile'
]);
```

## Security Features

### Security Levels

| Level | Rate Limit | CSRF Protection | Input Validation | Suspicious Pattern Blocking |
|-------|------------|-----------------|------------------|----------------------------|
| `basic` | 200 req/min | ❌ | ✅ | ❌ |
| `standard` | 100 req/min | ✅ | ✅ | ✅ |
| `strict` | 30 req/min | ✅ | ✅ | ✅ |

### Security Level Configurations

```php
// Basic security configuration
$basicConfig = [
    'rate_limit' => 200,
    'csrf_protection' => false,
    'validate_input' => true
];

// Standard security configuration  
$standardConfig = [
    'rate_limit' => 100,
    'csrf_protection' => true,
    'validate_input' => true,
    'block_suspicious_patterns' => true
];

// Strict security configuration
$strictConfig = [
    'rate_limit' => 30,
    'csrf_protection' => true,
    'validate_input' => true,
    'block_suspicious_patterns' => true
];
```

### Manual Security Configuration

```php
// Configure security settings manually
Route::configureSecurity([
    'validate_params' => true,
    'sanitize_input' => true,
    'csrf_protection' => true,
    'rate_limiting' => true
]);
```

## Performance Optimization

### Cache Management

```php
// Enable/disable caching
Route::enableCache(true);  // Enable
Route::enableCache(false); // Disable

// Get cache statistics
$stats = Route::getCacheStats();
echo "Cached routes: " . $stats['cached_routes'];
echo "Total routes: " . $stats['total_routes'];
echo "Cache enabled: " . ($stats['cache_enabled'] ? 'Yes' : 'No');

// Clear different types of cache
Route::clearCache();          // Clear route cache only
Route::clearControllerPool(); // Clear controller pool only
Route::clearCaches();         // Clear all caches
```

### Performance Monitoring

```php
// Monitor route performance
Route::get('/heavy-operation', function() {
    return Route::monitorRoute('/heavy-operation', function() {
        // Your heavy operation here
        return HeavyController::processLargeData();
    });
});

// Get performance statistics
$performanceStats = Route::getPerformanceStats();
$debugInfo = Route::debugInfo();
```

### Performance Statistics

```php
// Get comprehensive performance data
$stats = Route::getPerformanceStats();

// Example output structure:
[
    'router' => [
        'cached_routes' => 25,
        'total_routes' => 50,
        'cache_enabled' => true,
        'memory_usage' => 2048576
    ],
    'cache_manager' => [
        'hit_rate' => 0.85,
        'miss_rate' => 0.15,
        'total_requests' => 1000
    ],
    'performance' => [
        'average_response_time' => 45.2,
        'memory_peak' => 4194304
    ]
]
```

## CSRF Protection

### Basic CSRF Usage

```php
// Get CSRF token (with optional parameters)
$token = Route::csrf('csrf_token', 3600, true); // key, expiration, origin check

// Generate CSRF field for forms
$csrfField = Route::csrfField('form_token', 1800); // 30 minute expiration

// Generate CSRF meta tag for AJAX
$csrfMeta = Route::csrfMeta('ajax_token', null, true); // with origin validation

// Multiple tokens for complex forms
$tokens = Route::csrfTokens(['step1', 'step2', 'final']);
$fields = Route::csrfFields(['payment', 'shipping']);

// Manual CSRF validation
$isValid = Route::validateCsrf($token, 'csrf_token', 3600, true);
```

### CSRF in HTML Forms

```html
<!DOCTYPE html>
<html>
<head>
    <?= Route::csrfMeta() ?>
    <title>Example Form</title>
</head>
<body>
    <form method="POST" action="/submit-form">
        <?= Route::csrfField() ?>
        
        <input type="text" name="name" placeholder="Name">
        <input type="email" name="email" placeholder="Email">
        <button type="submit">Submit</button>
    </form>
    
    <script>
        // For AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/api/users', {
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
```

### Advanced CSRF Validation

```php
Route::post('/api/secure-action', function() {
    // Enhanced CSRF validation with expiration and origin check
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    if (!Route::validateCsrf($token, 'csrf_token', 3600, true)) {
        http_response_code(403);
        return json_encode(['error' => 'CSRF token validation failed']);
    }
    
    // Additional security measures
    $cleanData = SecurityMiddleware::sanitizeForm($_POST);
    $xssCleanData = SecurityMiddleware::cleanXSS($cleanData);
    
    return SecureController::performAction($xssCleanData);
});

// Using SecurityMiddleware directly
Route::post('/api/direct-security', function() {
    // Direct SecurityMiddleware usage
    $token = $_POST['csrf_token'] ?? null;
    if (!SecurityMiddleware::validateCSRFToken($token, 'csrf_token', 1800, true)) {
        return json_encode(['error' => 'Invalid CSRF token']);
    }
    
    return ApiController::secureEndpoint();
});
```

## Error Handling

### Custom Error Handlers

```php
// Set custom 404 handler
Route::error(function() {
    return ErrorController::notFound();
});

// Custom error handling in routes
Route::get('/posts/(:slug)', function($slug) {
    if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
        Route::error(function() {
            return ErrorController::badRequest();
        });
        return;
    }
    
    Route::goto([PostController::class, 'show'], [$slug]);
});
```

### Route Processing Control

```php
// Control route processing behavior
Route::haltOnMatch(true);  // Stop after first match (default)
Route::haltOnMatch(false); // Continue processing after matches
```

## Advanced Features

### Direct Controller Execution

```php
// Execute controller method directly
$result = Route::goto([UserController::class, 'show'], [123]);

// Execute controller with security validation
$result = Route::goto([UserController::class, 'profile'], ['user_id' => 123]);

// Execute controller from middleware chain
$result = Route::for([UserController::class, 'processData'], ['data' => $processedData]);

// Manual security checks before controller execution
if (SecurityMiddleware::validateCSRFToken($_POST['token'] ?? null)) {
    $result = Route::goto([UserController::class, 'secureAction'], [$cleanData]);
}
```

### Enhanced Route Definition

```php
// Enhanced route with security level
Route::route('post', '/api/users', [ApiController::class, 'createUser'], [
    'security_level' => 'strict',
    'name' => 'api.users.create'
]);

// Manual security validation within route
Route::post('/api/users', function() {
    // Validate CSRF and sanitize input
    if (!SecurityMiddleware::validateCSRFToken($_POST['csrf_token'] ?? null, 'user_create', 3600)) {
        return json_encode(['error' => 'Invalid CSRF token']);
    }
    
    $userData = SecurityMiddleware::validateAndSanitize($_POST, [
        'xss_clean' => true,
        'max_length' => 255
    ]);
    
    return ApiController::createUser($userData);
});
```

### Route Dispatching

```php
// Manual route dispatching (usually done automatically)
Route::dispatch();
```

## API Reference

### Core Functions

#### `initRouter(array $config = [])`
Initialize the router with optimization settings.

**Parameters:**
- `$config` (array): Configuration options

**Returns:** `array` - Merged configuration

---

#### `route(string $method, string $path, array $controller, array $options = [])`
Create an enhanced route with security and middleware support.

**Parameters:**
- `$method` (string): HTTP method
- `$path` (string): Route path pattern
- `$controller` (array): Controller class and method
- `$options` (array): Route options

**Returns:** Mixed route definition result

---

### HTTP Method Functions

#### `get(string $path, callable|array $callback)`
Handle GET requests.

#### `post(string $path, callable|array $callback)`
Handle POST requests.

#### `put(string $path, callable|array $callback)`
Handle PUT requests.

#### `patch(string $path, callable|array $callback)`
Handle PATCH requests.

#### `delete(string $path, callable|array $callback)`
Handle DELETE requests.

#### `head(string $path, callable|array $callback)`
Handle HEAD requests.

#### `options(string $path, callable|array $callback)`
Handle OPTIONS requests.

#### `any(string $path, callable|array $callback)`
Handle any HTTP method.

#### `map(array $methods, string $path, callable|array $callback)`
Handle multiple specific HTTP methods.

---

### Route Management Functions

#### `group(string $prefix, callable $callback)`
Create route group with URL prefix.

**Parameters:**
- `$prefix` (string): URL prefix for all routes in group
- `$callback` (callable): Function containing group routes

**Returns:** Mixed route group result

#### `createRouteGroup(string $prefix, array $middleware = [], callable $callback = null)`
Create optimized route group with prefix and middleware support.

**Parameters:**
- `$prefix` (string): URL prefix for the group
- `$middleware` (array): Array of middleware to apply to group  
- `$callback` (callable|null): Callback function for group routes

**Returns:** Callable route group function

#### `goto(array $controllerWithMethod, array $vars = [])`
Execute controller method directly.

#### `for(array $controllerWithMethod, array $vars = [])`
Execute controller from middleware chain.

**Parameters:**
- `$controllerWithMethod` (array): Controller class and method in [Class::class, 'method'] format
- `$vars` (array): Variables to pass to controller method

**Returns:** Mixed controller method result

**Usage:**
```php
// Execute controller through middleware chain
$result = Route::for([UserController::class, 'profile'], ['user_id' => 123]);
```

---

### Security Functions

#### `createSecurityMiddleware(string $level = 'standard')`
Create security middleware with predefined levels.

#### `csrf(string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)`
Get CSRF token for forms and AJAX requests with enhanced parameters.

#### `csrfField(string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)`
Get CSRF field HTML for forms with token expiration and origin validation.

#### `csrfMeta(string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)`
Get CSRF meta tag for AJAX requests with enhanced security options.

#### `validateCsrf(?string $token = null, string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)`
Validate CSRF token with expiration and origin checking.

#### `csrfTokens(array $keys, ?int $expiration = null, bool $originCheck = false)`
Generate multiple CSRF tokens for complex forms.

#### `csrfFields(array $keys, ?int $expiration = null, bool $originCheck = false)`
Generate CSRF fields for multiple tokens.

#### `configureSecurity(array $config = [])`
Configure security settings.

---

### Performance Functions

#### `enableCache(bool $enable = true)`
Enable or disable route caching.

#### `getCacheStats()`
Get cache statistics from router.

#### `getPerformanceStats()`
Get comprehensive performance statistics.

#### `clearCache()`
Clear route cache.

#### `clearControllerPool()`
Clear controller pool.

#### `clearCaches()`
Clear all router caches.

#### `monitorRoute(string $route, callable $callback)`
Monitor route execution time and memory usage.

**Parameters:**
- `$route` (string): Route path being monitored
- `$callback` (callable): Function to execute and monitor

**Returns:** Mixed result from callback execution

#### `createRouteGroup(string $prefix, array $middleware = [], callable $callback = null)`
Create optimized route group with prefix and middleware.

**Parameters:**
- `$prefix` (string): URL prefix for the group
- `$middleware` (array): Array of middleware to apply to group
- `$callback` (callable|null): Callback function for group routes

**Returns:** Callable route group function

#### `debugInfo()`
Get debug information about router state.

---

### Error Handling Functions

#### `error(callable $callback)`
Set error callback for 404 and other errors.

#### `haltOnMatch(bool $flag = true)`
Set halt on match behavior.

#### `dispatch()`
Dispatch routes and process current request.

---

## Best Practices

### 1. Security
- Always use appropriate security levels for your routes
- Enable CSRF protection for forms and state-changing operations
- Validate and sanitize user input
- Use rate limiting for API endpoints

### 2. Performance
- Enable route caching in production environments
- Use controller pooling for frequently accessed controllers
- Monitor route performance for optimization opportunities
- Clear caches when routes are updated

### 3. Organization
- Group related routes using route groups
- Use meaningful route names for easier maintenance
- Organize routes in separate files by functionality
- Document your routes and their purposes

### 4. Error Handling
- Implement custom error handlers for better user experience
- Log errors appropriately for debugging
- Provide meaningful error messages
- Handle edge cases gracefully

### 5. Security Integration
- Use SecurityMiddleware static methods for security validation
- Apply CSRF protection with appropriate expiration times
- Sanitize and validate input data consistently
- Use origin validation for sensitive operations

## Troubleshooting

### Common Issues

1. **Routes not matching**: Check route patterns and parameter types
2. **CSRF token mismatch**: Ensure CSRF tokens are properly included in forms
3. **Performance issues**: Enable caching and monitor route performance
4. **Security validation failing**: Check CSRF token expiration and origin validation settings
5. **Memory issues**: Clear controller pool and route cache regularly

### Debug Tools

```php
// Get debug information
$debug = Route::debugInfo();
print_r($debug);

// Monitor specific routes
Route::monitorRoute('/slow-route', function() {
    // Your route logic
});

// Check cache statistics
$stats = Route::getCacheStats();
print_r($stats);
```

## Examples

For complete examples and advanced usage patterns, see:
- `System/Routes/RouteExample.php` - Comprehensive examples
- `System/Routes/General.php` - Basic route definitions
- `System/Routes/Modules.php` - HMVC routing examples

## Support

For issues, questions, or contributions, please refer to the NSY Framework documentation or contact the development team.

---

*NSY Router*  
*Built with performance, security, and developer experience in mind.*
