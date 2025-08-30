<?php

namespace System\Helpers;

use System\Core\NSY_RouterOptimized;
use System\Core\NSY_RouteCacheManager;
use System\Middlewares\SecurityMiddleware;

/**
 * Router Configuration Helper
 * Simplifies router setup and provides utility functions
 */
class RouterHelper
{
    /**
     * Initialize optimized router with recommended settings
     *
     * @param array $config Configuration array with cache, security, and performance settings
     * @return array Merged configuration array
     */
    public static function initRouter($config = [])
    {
        $defaultConfig = [
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
        ];

        $config = array_merge($defaultConfig, $config);

        // Enable caching
        NSY_RouterOptimized::enableCache($config['cache_enabled']);

        // Configure security
        NSY_RouterOptimized::configureSecurity($config['security']);

        // Initialize cache manager
        NSY_RouteCacheManager::init();

        // Skip cache warm-up to prevent recursion during initialization
        // Cache will be built naturally on first requests

        return $config;
    }

    /**
     * Create security middleware with predefined security levels
     *
     * @param string $level Security level: 'basic', 'standard', or 'strict'
     * @return SecurityMiddleware Configured security middleware instance
     */
    public static function createSecurityMiddleware($level = 'standard')
    {
        $configs = [
            'basic' => [
                'rate_limit' => 200,
                'csrf_protection' => false,
                'validate_input' => true
            ],
            'standard' => [
                'rate_limit' => 100,
                'csrf_protection' => true,
                'validate_input' => true,
                'block_suspicious_patterns' => true
            ],
            'strict' => [
                'rate_limit' => 30,
                'csrf_protection' => true,
                'validate_input' => true,
                'block_suspicious_patterns' => true
            ]
        ];

        $config = $configs[$level] ?? $configs['standard'];
        return new SecurityMiddleware($config);
    }

    /**
     * Get CSRF token for forms and AJAX requests
     *
     * @param string $key Token name/key (default: 'csrf_token')
     * @param int|null $expiration Token expiration in seconds (null = no expiration)
     * @param bool $originCheck Whether to include origin validation (IP + User Agent)
     * @return string CSRF token string
     */
    public static function csrf(string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)
    {
        return SecurityMiddleware::generateCSRFToken($key, $expiration, $originCheck);
    }

    /**
     * Get CSRF field HTML for forms
     *
     * @param string $key Token name/key (default: 'csrf_token')
     * @param int|null $expiration Token expiration in seconds (null = no expiration)
     * @param bool $originCheck Whether to include origin validation (IP + User Agent)
     * @return string HTML input field with CSRF token
     */
    public static function csrfField(string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)
    {
        return SecurityMiddleware::csrfField($key, $expiration, $originCheck);
    }

    /**
     * Get CSRF meta tag for AJAX requests
     *
     * @param string $key Token name/key (default: 'csrf_token')
     * @param int|null $expiration Token expiration in seconds (null = no expiration)
     * @param bool $originCheck Whether to include origin validation (IP + User Agent)
     * @return string HTML meta tag with CSRF token
     */
    public static function csrfMeta(string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)
    {
        return SecurityMiddleware::csrfMeta($key, $expiration, $originCheck);
    }

    /**
     * Validate CSRF token from request
     *
     * @param string|null $token Token to validate (if null, reads from $_POST)
     * @param string $key Token name/key (default: 'csrf_token')
     * @param int|null $expiration Token expiration in seconds (null = no expiration)
     * @param bool $originCheck Whether to validate origin (IP + User Agent)
     * @return bool True if valid, false otherwise
     */
    public static function validateCsrf(?string $token = null, string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false)
    {
        if ($token === null) {
            $token = $_POST[$key] ?? $_GET[$key] ?? null;
        }
        return SecurityMiddleware::validateCSRFToken($token, $key, $expiration, $originCheck);
    }

    /**
     * Generate multiple CSRF tokens for complex forms
     *
     * @param array $keys Array of token names/keys
     * @param int|null $expiration Token expiration in seconds (null = no expiration)
     * @param bool $originCheck Whether to include origin validation (IP + User Agent)
     * @return array Associative array of key => token pairs
     */
    public static function csrfTokens(array $keys, ?int $expiration = null, bool $originCheck = false)
    {
        $tokens = [];
        foreach ($keys as $key) {
            $tokens[$key] = SecurityMiddleware::generateCSRFToken($key, $expiration, $originCheck);
        }
        return $tokens;
    }

    /**
     * Generate CSRF fields for multiple tokens
     *
     * @param array $keys Array of token names/keys
     * @param int|null $expiration Token expiration in seconds (null = no expiration)
     * @param bool $originCheck Whether to include origin validation (IP + User Agent)
     * @return string HTML input fields for all tokens
     */
    public static function csrfFields(array $keys, ?int $expiration = null, bool $originCheck = false)
    {
        $fields = '';
        foreach ($keys as $key) {
            $fields .= SecurityMiddleware::csrfField($key, $expiration, $originCheck) . "\n";
        }
        return rtrim($fields);
    }

    /**
     * Clear all router caches including route cache and controller pool
     *
     * @return void
     */
    public static function clearCaches()
    {
        NSY_RouterOptimized::clearCache();
        NSY_RouterOptimized::clearControllerPool();
        NSY_RouteCacheManager::clearCache();
    }

    /**
     * Get comprehensive performance statistics from router and cache manager
     *
     * @return array Performance statistics including router, cache, and execution data
     */
    public static function getPerformanceStats()
    {
        return [
            'router' => NSY_RouterOptimized::getCacheStats(),
            'cache_manager' => NSY_RouteCacheManager::getCacheStats(),
            'performance' => NSY_RouteCacheManager::getPerformanceStats()
        ];
    }

    /**
     * Create optimized route group with prefix and middleware
     *
     * @param string $prefix URL prefix for the group
     * @param array $middleware Array of middleware to apply to group
     * @param callable|null $callback Callback function for group routes
     * @return callable Route group function
     */
    public static function createRouteGroup($prefix, $middleware = [], $callback = null)
    {
        return function() use ($prefix, $middleware, $callback) {
            NSY_RouterOptimized::group($prefix, function() use ($middleware, $callback) {
                if ($callback) {
                    return $callback($middleware);
                }
                return null;
            });
        };
    }

    /**
     * Monitor route execution time and memory usage
     *
     * @param string $route Route path being monitored
     * @param callable $callback Function to execute and monitor
     * @return mixed Result from callback execution
     */
    public static function monitorRoute($route, $callback)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);

        $result = $callback();

        $executionTime = microtime(true) - $startTime;
        $memoryUsage = memory_get_usage(true) - $startMemory;

        NSY_RouteCacheManager::logRoutePerformance($route, $executionTime, $memoryUsage);

        return $result;
    }

    /**
     * Generate optimized route definition with security and middleware support
     *
     * @param string $method HTTP method (get, post, put, delete, etc.)
     * @param string $path Route path pattern
     * @param array $controller Controller class and method [Class::class, 'method']
     * @param array $options Route options including middleware, name, security_level
     * @return mixed Route definition result
     */
    public static function route($method, $path, $controller, $options = [])
    {
        $defaultOptions = [
            'middleware' => [],
            'name' => null,
            'security_level' => 'standard'
        ];

        $options = array_merge($defaultOptions, $options);

        // Add security middleware if not already present
        if (!empty($options['security_level'])) {
            $securityMiddleware = self::createSecurityMiddleware($options['security_level']);
            array_unshift($options['middleware'], $securityMiddleware);
        }

        $method = strtolower($method);

        return NSY_RouterOptimized::$method($path, function() use ($controller, $options) {
            // Security middleware is now applied through SecurityMiddleware static methods
            // No longer using deprecated middleware chain system
            return NSY_RouterOptimized::goto($controller);
        });
    }

    /**
     * Get debug information about router state and performance
     *
     * @return array Debug information including routes count, cache stats, memory usage
     */
    public static function debugInfo()
    {
        return [
            'routes_count' => count(NSY_RouterOptimized::$routes),
            'cache_stats' => self::getPerformanceStats(),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ];
    }

    // ===============================================
    // HTTP Methods - Direct NSY_RouterOptimized proxy
    // ===============================================

    /**
     * Handle GET requests
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function get($path, $callback)
    {
        return NSY_RouterOptimized::get($path, $callback);
    }

    /**
     * Handle POST requests
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function post($path, $callback)
    {
        return NSY_RouterOptimized::post($path, $callback);
    }

    /**
     * Handle PUT requests
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function put($path, $callback)
    {
        return NSY_RouterOptimized::put($path, $callback);
    }

    /**
     * Handle DELETE requests
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function delete($path, $callback)
    {
        return NSY_RouterOptimized::delete($path, $callback);
    }

    /**
     * Handle PATCH requests
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function patch($path, $callback)
    {
        return NSY_RouterOptimized::patch($path, $callback);
    }

    /**
     * Handle OPTIONS requests
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function options($path, $callback)
    {
        return NSY_RouterOptimized::options($path, $callback);
    }

    /**
     * Handle HEAD requests
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function head($path, $callback)
    {
        return NSY_RouterOptimized::head($path, $callback);
    }

    /**
     * Handle ANY requests (all HTTP methods)
     *
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function any($path, $callback)
    {
        return NSY_RouterOptimized::any($path, $callback);
    }

    /**
     * Handle MAP requests (multiple specific HTTP methods)
     *
     * @param array $methods Array of HTTP methods ['GET', 'POST', etc.]
     * @param string $path Route path pattern
     * @param callable|array $callback Controller callback or [Class::class, 'method']
     * @return mixed Route definition result
     */
    public static function map($methods, $path, $callback)
    {
        return NSY_RouterOptimized::map($methods, $path, $callback);
    }

    // ===============================================
    // Route Management Methods
    // ===============================================

    /**
     * Create route group with URL prefix
     *
     * @param string $prefix URL prefix for all routes in group
     * @param callable $callback Function containing group routes
     * @return mixed Route group result
     */
    public static function group($prefix, $callback)
    {
        return NSY_RouterOptimized::group($prefix, $callback);
    }


    /**
     * Go to controller method directly
     *
     * @param array $controllerWithMethod [Class::class, 'method'] format
     * @param array $vars Variables to pass to controller method
     * @return mixed Controller method result
     */
    public static function goto($controllerWithMethod, $vars = [])
    {
        return NSY_RouterOptimized::goto($controllerWithMethod, $vars);
    }

    /**
     * Execute controller from middleware chain
     *
     * @param array $controllerWithMethod [Class::class, 'method'] format
     * @param array $vars Variables to pass to controller method
     * @return mixed Controller method result
     */
    public static function for($controllerWithMethod, $vars = [])
    {
        // This needs to be called on RouterOptimized instance
        // We'll create a wrapper
        return call_user_func([new NSY_RouterOptimized(), 'for'], $controllerWithMethod, $vars);
    }

    /**
     * Set error callback for 404 and other errors
     *
     * @param callable $callback Error handler function
     * @return mixed Error handler result
     */
    public static function error($callback)
    {
        return NSY_RouterOptimized::error($callback);
    }

    /**
     * Set halt on match behavior (stop processing after first match)
     *
     * @param bool $flag True to halt on first match, false to continue
     * @return mixed Router configuration result
     */
    public static function haltOnMatch($flag = true)
    {
        return NSY_RouterOptimized::haltOnMatch($flag);
    }

    /**
     * Dispatch routes and process current request
     *
     * @return mixed Route dispatch result
     */
    public static function dispatch()
    {
        return NSY_RouterOptimized::dispatch();
    }

    /**
     * Enable or disable route caching
     *
     * @param bool $enable True to enable cache, false to disable
     * @return mixed Cache configuration result
     */
    public static function enableCache($enable = true)
    {
        return NSY_RouterOptimized::enableCache($enable);
    }

    /**
     * Configure security settings for router
     *
     * @param array $config Security configuration array
     * @return mixed Security configuration result
     */
    public static function configureSecurity($config = [])
    {
        return NSY_RouterOptimized::configureSecurity($config);
    }

    /**
     * Clear controller pool (useful for memory management)
     *
     * @return mixed Controller pool clear result
     */
    public static function clearControllerPool()
    {
        return NSY_RouterOptimized::clearControllerPool();
    }

    /**
     * Get cache statistics from router
     *
     * @return array Cache statistics including cached routes, total routes, cache status, and memory usage
     */
    public static function getCacheStats()
    {
        return NSY_RouterOptimized::getCacheStats();
    }

    /**
     * Clear route cache
     *
     * @return mixed Cache clear result
     */
    public static function clearCache()
    {
        return NSY_RouterOptimized::clearCache();
    }
}
