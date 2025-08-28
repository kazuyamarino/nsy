<?php

namespace System\Core;

use Optimus\Onion\Onion;

/**
 * Optimized NSY Router with caching and security improvements
 * @author NSY Framework Team - Optimized Version
 */
class NSY_RouterOptimized
{
	public static $base = '';
	public static $halts = false;
	public static $response = null;
	public static $routes = array();
	public static $methods = array();
	public static $callbacks = array();
	public static $maps = array();
	public static $patterns = array(
		':all'   => '.*',
		':any'   => '[^/]+',
		':slug'  => '[a-z0-9-]+',
		':uslug' => '[\w-]+',
		':num'   => '[0-9]+',
		':alpha' => '[A-Za-z]+',
		':alnum' => '[0-9A-Za-z]+',
		':date'  => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
	);
	public static $error_callback;

	// Optimization features
	private static $routeCache = [];
	private static $controllerPool = [];
	private static $compiledRoutes = null;
	private static $cacheEnabled = true;
	private static $securityConfig = [
		'validate_params' => true,
		'sanitize_input' => true,
		'csrf_protection' => false,
		'rate_limiting' => false
	];

	/**
	 * Enable/disable route caching
	 */
	public static function enableCache($enable = true)
	{
		self::$cacheEnabled = $enable;
	}

	/**
	 * Configure security settings
	 */
	public static function configureSecurity($config = [])
	{
		if (!is_array($config) || empty($config)) {
			return;
		}

		// Safety: limit depth & size
		if (count($config) > 1000) {
			error_log("Security config too large, ignoring...");
			return;
		}

		$safeConfig = [];
		foreach ($config as $key => $value) {
			if (is_scalar($value) || is_bool($value) || is_null($value)) {
				$safeConfig[$key] = $value;
			}
		}

		self::$securityConfig = array_merge(self::$securityConfig, $safeConfig);
	}

	/**
	 * Defines a route w/ callback and method (optimized)
	 */
	public static function __callstatic($method, $params)
	{
		if ($method == 'group') {
			$params[0] = null;
		} else {
			$params[0] = '/' . config_app('app_dir') . self::$base . $params[0];
		}

		if ($method == 'map') {
			$maps = is_array($params[0]) ? array_map('strtoupper', $params[0]) : [strtoupper($params[0])];
			$uri = strpos($params[1], '/') === 0 ? $params[1] : '/' . $params[1];
			$callback = $params[2];
		} else {
			$maps = null;
			$uri = strpos($params[0] ?? '', '/') === 0 ? $params[0] : '/' . $params[0];
			$callback = $params[1];
		}

		array_push(self::$maps, $maps);
		array_push(self::$routes, $uri);
		array_push(self::$methods, strtoupper($method));
		array_push(self::$callbacks, $callback);

		// Invalidate compiled routes cache when new route is added
		self::$compiledRoutes = null;
	}

	/**
	 * Compile routes for better performance
	 */
	private static function compileRoutes()
	{
		if (self::$compiledRoutes !== null) {
			return self::$compiledRoutes;
		}

		$compiled = [];
		$searches = array_keys(static::$patterns);
		$replaces = array_values(static::$patterns);

		for ($i = 0; $i < count(self::$routes); $i++) {
			$route = self::$routes[$i];
			$pattern = $route;

			if (strpos($route, ':') !== false) {
				$pattern = str_replace($searches, $replaces, $route);
			}

			$compiled[$i] = [
				'original' => $route,
				'pattern' => $pattern,
				'method' => self::$methods[$i],
				'callback' => self::$callbacks[$i],
				'maps' => self::$maps[$i],
				'has_params' => strpos($route, ':') !== false
			];
		}

		self::$compiledRoutes = $compiled;
		return $compiled;
	}

	/**
	 * Validate and sanitize route parameters
	 */
	private static function validateParameters($params, $route)
	{
		if (!self::$securityConfig['validate_params']) {
			return $params;
		}

		$cleaned = [];
		foreach ($params as $param) {
			// Basic sanitization
			if (self::$securityConfig['sanitize_input']) {
				$param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
				$param = trim($param);
			}

			// Additional validation can be added here based on route patterns
			$cleaned[] = $param;
		}

		return $cleaned;
	}

	/**
	 * Get controller instance from pool or create new one
	 */
	private static function getController($className)
	{
		if (!isset(self::$controllerPool[$className])) {
			self::$controllerPool[$className] = new $className;
		}
		return self::$controllerPool[$className];
	}

	/**
	 * Clear controller pool (useful for memory management)
	 */
	public static function clearControllerPool()
	{
		self::$controllerPool = [];
	}

	/**
	 * NSY Middleware System (optimized)
	 */
	public static function middleware($middleware)
	{
		if (is_filled($middleware)) {
			$object = true;
			$onion = new Onion;

			self::$response = $onion->layer($middleware)->peel($object, function ($object) {
				return $object;
			});
		} else {
			self::handleError('Invalid middleware configuration', 500);
			return null;
		}

		return new self;
	}

	/**
	 * Improved error handling
	 */
	private static function handleError($message, $code = 404)
	{
		if (self::$securityConfig['sanitize_input']) {
			// Don't expose sensitive information in production
			if (config_app('environment') === 'production') {
				$message = $code === 404 ? 'Page Not Found' : 'Server Error';
			}
		}

		header($_SERVER['SERVER_PROTOCOL'] . " $code " . self::getHttpStatusMessage($code));
		exit($message);
	}

	/**
	 * Get HTTP status message
	 */
	private static function getHttpStatusMessage($code)
	{
		$messages = [
			200 => 'OK',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			403 => 'Forbidden',
			405 => 'Method Not Allowed'
		];

		return $messages[$code] ?? 'Unknown Status';
	}

	/**
	 * goto() method for Instantiate controller (optimized)
	 */
	public static function goto($controllerWithMethod = '', $vars = array())
	{
		$params = $controllerWithMethod;
		$fullclass = $params[0];

		// Use controller pool for better performance
		$controller = self::getController($fullclass);

		// Validate parameters
		if (is_filled($vars)) {
			$vars = self::validateParameters($vars, $fullclass);
			return $controller->{$params[1]}($vars);
		} else {
			return $controller->{$params[1]}();
		}
	}

	/**
	 * for() method for Instantiate controller from middleware (optimized)
	 */
	public function for($controllerWithMethod = '', $vars = array())
	{
		$params = $controllerWithMethod;
		$fullclass = $params[0];

		// Use controller pool for better performance
		$controller = self::getController($fullclass);

		// Validate parameters
		if (is_filled($vars)) {
			$vars = self::validateParameters($vars, $fullclass);
			return $controller->{$params[1]}($vars);
		} else {
			return $controller->{$params[1]}();
		}
	}

	/**
	 * group() method for grouping route (optimized)
	 */
	public static function group($base, $callback)
	{
		$meths = 'group';
		self::$base = $base;
		self::__callstatic($meths, [$base, $callback()]);
		self::$base = null;
	}

	/**
	 * Defines callback if route is not found
	 */
	public static function error($callback)
	{
		self::$error_callback = $callback;
	}

	public static function haltOnMatch($flag = true)
	{
		self::$halts = $flag;
	}

	/**
	 * Optimized route dispatcher with caching
	 */
	public static function dispatch()
	{
		$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$method = $_SERVER['REQUEST_METHOD'];

		// Create cache key
		$cacheKey = md5($uri . $method);

		// Check cache first
		if (self::$cacheEnabled && isset(self::$routeCache[$cacheKey])) {
			$cachedRoute = self::$routeCache[$cacheKey];
			return self::executeRoute($cachedRoute['callback'], $cachedRoute['params']);
		}

		// Compile routes for better performance
		$compiledRoutes = self::compileRoutes();
		$found_route = false;

		// Normalize routes
		self::$routes = preg_replace('/\/+/', '/', self::$routes);

		// Check compiled routes
		foreach ($compiledRoutes as $pos => $route) {
			$matched = [];
			$isMatch = false;

			if (!$route['has_params']) {
				// Exact match for routes without parameters
				$isMatch = ($uri === $route['original']);
			} else {
				// Regex match for parameterized routes
				$isMatch = preg_match('#^' . $route['pattern'] . '$#', $uri, $matched);
			}

			if ($isMatch) {
				// Check method compatibility
				if (
					$route['method'] == $method ||
					$route['method'] == 'ANY' ||
					(!empty($route['maps']) && in_array($method, $route['maps']))
				) {

					$found_route = true;

					// Remove full match from parameters
					if (isset($matched[0])) {
						array_shift($matched);
					}

					// Validate parameters
					$matched = self::validateParameters($matched, $route['original']);

					// Cache the route if caching is enabled
					if (self::$cacheEnabled) {
						self::$routeCache[$cacheKey] = [
							'callback' => $route['callback'],
							'params' => $matched
						];
					}

					return self::executeRoute($route['callback'], $matched);
				}
			}
		}

		// Handle 404
		if (!$found_route) {
			if (!self::$error_callback) {
				self::handleError('404 Not Found', 404);
			} else {
				if (is_string(self::$error_callback)) {
					self::get($_SERVER['REQUEST_URI'], self::$error_callback);
					self::$error_callback = null;
					self::dispatch();
					return;
				}
				call_user_func(self::$error_callback);
			}
		}
	}

	/**
	 * Execute route callback
	 */
	private static function executeRoute($callback, $params = [])
	{
		if (!is_object($callback)) {
			// Array callback [Controller::class, 'method']
			$fullclass = $callback[0];

			// Use controller pool
			$controller = self::getController($fullclass);

			// Check if method exists
			if (!method_exists($controller, $callback[1])) {
				self::handleError("Method {$callback[1]} not found in controller", 500);
				return;
			}

			if (!empty($params)) {
				call_user_func_array(array($controller, $callback[1]), $params);
			} else {
				$controller->{$callback[1]}();
			}
		} else {
			// Closure callback
			if (!empty($params)) {
				call_user_func_array($callback, $params);
			} else {
				call_user_func($callback);
			}
		}

		if (self::$halts) return;
	}

	/**
	 * Clear route cache
	 */
	public static function clearCache()
	{
		self::$routeCache = [];
		self::$compiledRoutes = null;
	}

	/**
	 * Get cache statistics
	 */
	public static function getCacheStats()
	{
		return [
			'cached_routes' => count(self::$routeCache),
			'total_routes' => count(self::$routes),
			'cache_enabled' => self::$cacheEnabled,
			'memory_usage' => memory_get_usage(true)
		];
	}
}
