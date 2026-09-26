<?php

declare(strict_types=1);

namespace System\Core;

/**
 * Optimized NSY Router with caching and security improvements
 * Facade: Route = System\Helpers\RouterHelper (see System/Libraries/Aliases.php)
 * Engine: this class
 *
 * @author NSY Framework Team - Optimized Version
 */
class NSY_RouterOptimized
{
	public static string $base = '';
	public static bool $halts = false;
	/** @var mixed */
	public static $response = null;
	/** @var string[] */
	public static array $routes = [];
	/** @var string[] */
	public static array $methods = [];
	/** @var mixed[] */
	public static array $callbacks = [];
	/** @var array[]|null[] */
	public static array $maps = [];
	public static array $patterns = [
		':all'   => '.*',
		':any'   => '[^/]+',
		':slug'  => '[a-z0-9-]+',
		':uslug' => '[\w-]+',
		':num'   => '[0-9]+',
		':alpha' => '[A-Za-z]+',
		':alnum' => '[0-9A-Za-z]+',
		':date'  => '[0-9]{4}-[0-9]{2}-[0-9]{2}',
	];
	/** @var callable|null */
	public static $error_callback = null;

	/** Request start time for access-log duration. */
	private static float $requestStart = 0.0;

	// Optimization features
	/** @var array<string,array{callback:mixed,params:string[]}> */
	private static array $routeCache = [];
	/** @var array<string,object> */
	private static array $controllerPool = [];
	/** @var array<int,array{original:string,pattern:string,method:string,callback:mixed,maps:?array,has_params:bool}>|null */
	private static ?array $compiledRoutes = null;
	private static bool $cacheEnabled = true;
	private static ?string $cachedAppDir = null;
	private static array $securityConfig = [
		'validate_params' => true,
		'sanitize_input' => true,
		'csrf_protection' => false,
		'rate_limiting' => false,
	];

	/**
	 * Enable/disable route caching
	 */
	public static function enableCache(bool $enable = true): void
	{
		self::$cacheEnabled = $enable;
	}

	/**
	 * Configure security settings — only scalar/null/bool values accepted
	 */
	public static function configureSecurity(array $config = []): void
	{
		if (empty($config)) {
			return;
		}

		if (count($config) > 1000) {
			error_log('Security config too large, ignoring...');
			return;
		}

		$safeConfig = [];
		foreach ($config as $key => $value) {
			if (is_scalar($value) || $value === null) {
				$safeConfig[$key] = $value;
			}
		}

		self::$securityConfig = array_merge(self::$securityConfig, $safeConfig);
	}

	private static function getAppDir(): string
	{
		if (self::$cachedAppDir === null) {
			$dir = config_app('app_dir');
			self::$cachedAppDir = is_string($dir) ? trim($dir, '/') : '';
		}
		return self::$cachedAppDir;
	}

	/**
	 * Defines a route w/ callback and method (optimized)
	 * Supports: get/post/put/delete/patch/head/options/any/map/group
	 */
	public static function __callStatic(string $method, array $params): void
	{
		$appDir = self::getAppDir();
		$prefix = $appDir !== '' ? '/' . $appDir : '';
		if (self::$base !== '') {
			$prefix .= self::$base;
		}

		if ($method === 'group') {
			// group() is handled via self::group(), not via __callStatic directly
			return;
		}

		if ($method === 'map') {
			$maps = is_array($params[0] ?? null) ? array_map('strtoupper', $params[0]) : [strtoupper((string) ($params[0] ?? 'GET'))];
			$uri = strpos((string) ($params[1] ?? ''), '/') === 0 ? (string) $params[1] : '/' . (string) ($params[1] ?? '');
			$uri = $prefix . $uri;
			$callback = $params[2] ?? null;
		} else {
			$maps = null;
			$raw = (string) ($params[0] ?? '');
			$uri = strpos($raw, '/') === 0 ? $raw : '/' . $raw;
			$uri = $prefix . $uri;
			$callback = $params[1] ?? null;
		}

		// Normalize multiple slashes once at registration (keep BC: dispatch no longer mutates global)
		$uri = preg_replace('#/+#', '/', $uri) ?? $uri;

		self::$maps[] = $maps;
		self::$routes[] = $uri;
		self::$methods[] = strtoupper($method);
		self::$callbacks[] = $callback;

		// Invalidate compiled routes cache when new route is added
		self::$compiledRoutes = null;
	}

	/**
	 * Compile routes for better performance — cached until next registration
	 * @return array<int,array{original:string,pattern:string,method:string,callback:mixed,maps:?array,has_params:bool}>
	 */
	private static function compileRoutes(): array
	{
		if (self::$compiledRoutes !== null) {
			return self::$compiledRoutes;
		}

		$compiled = [];
		// Sort patterns by key length desc to avoid partial overlap (e.g. :slug vs :uslug)
		$patterns = self::$patterns;
		uksort($patterns, static fn($a, $b) => strlen($b) <=> strlen($a));
		$searches = array_keys($patterns);
		$replaces = array_values($patterns);

		foreach (self::$routes as $i => $route) {
			$hasParams = strpos($route, ':') !== false;
			$pattern = $hasParams ? str_replace($searches, $replaces, $route) : $route;

			$compiled[$i] = [
				'original' => $route,
				'pattern' => $pattern,
				'method' => self::$methods[$i] ?? 'GET',
				'callback' => self::$callbacks[$i] ?? null,
				'maps' => self::$maps[$i] ?? null,
				'has_params' => $hasParams,
			];
		}

		self::$compiledRoutes = $compiled;
		return $compiled;
	}

	/**
	 * Validate and sanitize route parameters
	 * @param string[] $params
	 * @return string[]
	 */
	private static function validateParameters(array $params, string $route): array
	{
		if (!self::$securityConfig['validate_params']) {
			return $params;
		}

		$sanitize = (bool) (self::$securityConfig['sanitize_input'] ?? true);
		$cleaned = [];
		foreach ($params as $param) {
			$param = (string) $param;
			if ($sanitize) {
				$param = htmlspecialchars($param, ENT_QUOTES, 'UTF-8');
				$param = trim($param);
			}
			$cleaned[] = $param;
		}

		return $cleaned;
	}

	/**
	 * Get controller instance from pool or create new one
	 */
	private static function getController(string $className): object
	{
		if (!isset(self::$controllerPool[$className])) {
			if (!class_exists($className)) {
				self::handleError("Controller {$className} not found", 500);
			}
			self::$controllerPool[$className] = new $className();
		}
		return self::$controllerPool[$className];
	}

	/**
	 * Centralized controller invocation — used by goto() and for()
	 * @param array{0:class-string,1:string} $target
	 * @param mixed $vars — string, array, or null (BC: TestRoute passes string $id)
	 */
	private static function invokeController(array $target, mixed $vars = []): mixed
	{
		$fullClass = $target[0] ?? '';
		$method = $target[1] ?? '';
		if ($fullClass === '' || $method === '') {
			self::handleError('Invalid controller target', 500);
			return null;
		}
		$controller = self::getController($fullClass);

		if (!method_exists($controller, $method)) {
			self::handleError("Method {$method} not found in controller {$fullClass}", 500);
			return null;
		}

		// Normalize $vars to array for validateParameters / invocation
		if ($vars === null || $vars === []) {
			$args = [];
		} elseif (is_array($vars)) {
			$args = $vars;
		} else {
			$args = [$vars];
		}
		$args = !empty($args) ? self::validateParameters($args, $fullClass) : [];

		return empty($args)
			? $controller->{$method}()
			: $controller->{$method}($args);
	}

	/**
	 * Clear controller pool (useful for memory management)
	 */
	public static function clearControllerPool(): void
	{
		self::$controllerPool = [];
	}

	/**
	 * Write one access-log entry. Never breaks routing.
	 */
	private static function logAccess(string $method, string $uri, ?string $route, ?int $status = null): void
	{
		try {
			\System\Libraries\Log\LogManager::access([
				'method' => $method,
				'uri' => $uri,
				'route' => $route,
				'status' => $status ?? (http_response_code() ?: 200),
				'duration_ms' => self::$requestStart > 0.0 ? round((microtime(true) - self::$requestStart) * 1000, 2) : null,
			]);
		} catch (\Throwable $e) {
			// never break routing
		}
	}

	/**
	 * Improved error handling
	 */
	private static function handleError(string $message, int $code = 404): void
	{
		if (self::$securityConfig['sanitize_input'] ?? true) {
			if (config_app('app_env') === 'production') {
				$message = $code === 404 ? 'Page Not Found' : 'Server Error';
			}
		}

		$protocol = $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
		self::logAccess(
			strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET'),
			parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
			null,
			$code
		);
		header($protocol . " {$code} " . self::getHttpStatusMessage($code));
		exit($message);
	}

	/**
	 * Get HTTP status message
	 */
	private static function getHttpStatusMessage(int $code): string
	{
		return [
			200 => 'OK',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			403 => 'Forbidden',
			405 => 'Method Not Allowed',
		][$code] ?? 'Unknown Status';
	}

	/**
	 * goto() — Execute controller directly (static). Preferred.
	 * @param array{0:class-string,1:string}|array $controllerWithMethod
	 * @param mixed $vars — string|array|null for BC (TestRoute passes string $id)
	 */
	public static function goto(array $controllerWithMethod = [], mixed $vars = []): mixed
	{
		if (empty($controllerWithMethod)) {
			return null;
		}
		return self::invokeController($controllerWithMethod, $vars);
	}

	/**
	 * for() — Instance alias of goto() for middleware chain BC.
	 * Kept for backward compatibility; delegates to goto().
	 * @param array{0:class-string,1:string}|array $controllerWithMethod
	 * @param mixed $vars
	 */
	public function for(array $controllerWithMethod = [], mixed $vars = []): mixed
	{
		return self::goto($controllerWithMethod, $vars);
	}

	/**
	 * group() — Prefix all routes defined inside callback (exception-safe, supports nesting)
	 */
	public static function group(string $base, callable $callback): void
	{
		$prev = self::$base;
		self::$base = $prev . $base;
		try {
			$callback();
		} finally {
			self::$base = $prev;
		}
	}

	/**
	 * Defines callback if route is not found
	 */
	public static function error(callable|string $callback): void
	{
		self::$error_callback = $callback;
	}

	public static function haltOnMatch(bool $flag = true): void
	{
		self::$halts = $flag;
	}

	/**
	 * Optimized route dispatcher with caching (no global mutation)
	 */
	public static function dispatch(): void
	{
		$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
		$uri = preg_replace('#/+#', '/', $uri) ?? $uri;
		$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

		self::$requestStart = microtime(true);

		$cacheKey = md5($uri . $method);

		if (self::$cacheEnabled && isset(self::$routeCache[$cacheKey])) {
			$cached = self::$routeCache[$cacheKey];
			self::executeRoute($cached['callback'], $cached['params']);
			self::logAccess($method, $uri, null);
			return;
		}

		$compiledRoutes = self::compileRoutes();
		$found = false;

		foreach ($compiledRoutes as $route) {
			$matched = [];
			$isMatch = false;

			if (!$route['has_params']) {
				$isMatch = ($uri === $route['original']);
			} else {
				$isMatch = (bool) preg_match('#^' . $route['pattern'] . '$#', $uri, $matched);
			}

			if (!$isMatch) {
				continue;
			}

			$routeMethod = $route['method'];
			$maps = $route['maps'];
			if ($routeMethod !== $method && $routeMethod !== 'ANY' && !(is_array($maps) && in_array($method, $maps, true))) {
				continue;
			}

			$found = true;

			if (isset($matched[0])) {
				array_shift($matched);
			}

			$matched = self::validateParameters($matched, $route['original']);

			if (self::$cacheEnabled) {
				self::$routeCache[$cacheKey] = [
					'callback' => $route['callback'],
					'params' => $matched,
				];
			}

			self::executeRoute($route['callback'], $matched);
			self::logAccess($method, $uri, $route['original']);
			return;
		}

		if (!$found) {
			if (self::$error_callback === null) {
				self::handleError('404 Not Found', 404);
			} else {
				$cb = self::$error_callback;
				// String error handler: if callable (function name), call it; otherwise treat as message
				if (is_string($cb)) {
					if (is_callable($cb)) {
						call_user_func($cb);
					} else {
						self::handleError($cb, 404);
					}
					return;
				}
				call_user_func($cb);
			}
		}
	}

	/**
	 * Execute route callback
	 */
	private static function executeRoute(mixed $callback, array $params = []): void
	{
		if (!is_object($callback)) {
			// Array callback [Controller::class, 'method']
			if (is_array($callback) && isset($callback[0], $callback[1])) {
				$fullClass = $callback[0];
				$method = $callback[1];
				$controller = self::getController($fullClass);

				if (!method_exists($controller, $method)) {
					self::handleError("Method {$method} not found in controller", 500);
					return;
				}

				if (!empty($params)) {
					call_user_func_array([$controller, $method], $params);
				} else {
					$controller->{$method}();
				}
				if (self::$halts) {
					return;
				}
				return;
			}
			// String callback edge case
			if (is_string($callback) && $callback !== '') {
				if (!empty($params)) {
					call_user_func_array($callback, $params);
				} else {
					call_user_func($callback);
				}
			}
		} else {
			// Closure
			if (!empty($params)) {
				call_user_func_array($callback, $params);
			} else {
				call_user_func($callback);
			}
		}

		if (self::$halts) {
			return;
		}
	}

	/**
	 * Clear route cache
	 */
	public static function clearCache(): void
	{
		self::$routeCache = [];
		self::$compiledRoutes = null;
	}

	/**
	 * Get cache statistics
	 */
	public static function getCacheStats(): array
	{
		return [
			'cached_routes' => count(self::$routeCache),
			'total_routes' => count(self::$routes),
			'cache_enabled' => self::$cacheEnabled,
			'memory_usage' => memory_get_usage(true),
		];
	}
}
