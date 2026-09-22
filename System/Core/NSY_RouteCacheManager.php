<?php

declare(strict_types=1);

namespace System\Core;

/**
 * Route Cache Manager for NSY Framework
 * File-backed cache + performance log. In-memory dispatch cache lives in NSY_RouterOptimized.
 * This class does NOT duplicate dispatch matching — it only persists/loads data.
 */
class NSY_RouteCacheManager
{
	private static ?string $cacheDir = null;
	private static string $cacheFile = 'routes.cache.php';
	private static bool $enabled = true;
	private static bool $warmingUp = false;

	/**
	 * Initialize cache manager — creates temp dir if needed
	 */
	public static function init(?string $cacheDir = null): void
	{
		if ($cacheDir === null) {
			self::$cacheDir = sys_get_temp_dir() . '/nsy_routes';
		} else {
			self::$cacheDir = rtrim($cacheDir, '/');
		}

		if (!is_dir(self::$cacheDir)) {
			mkdir(self::$cacheDir, 0755, true);
		}
	}

	public static function setEnabled(bool $enabled): void
	{
		self::$enabled = $enabled;
	}

	private static function getCacheFilePath(): string
	{
		if (self::$cacheDir === null) {
			self::init();
		}
		return self::$cacheDir . '/' . self::$cacheFile;
	}

	/**
	 * Cache compiled routes to file (24h TTL on load)
	 * Skips caching when routes contain Closures (not var_export-able)
	 */
	public static function cacheRoutes(array $routes): bool
	{
		if (!self::$enabled) {
			return false;
		}

		foreach ($routes as $r) {
			if ($r instanceof \Closure) {
				return false;
			}
			if (is_array($r) && isset($r['callback']) && $r['callback'] instanceof \Closure) {
				return false;
			}
		}

		$cacheFile = self::getCacheFilePath();
		$cacheData = [
			'timestamp' => time(),
			'routes' => $routes,
			'hash' => md5(serialize($routes)),
		];

		$content = '<?php' . PHP_EOL . 'return ' . var_export($cacheData, true) . ';';
		return file_put_contents($cacheFile, $content, LOCK_EX) !== false;
	}

	public static function loadCachedRoutes(): ?array
	{
		if (!self::$enabled) {
			return null;
		}

		$cacheFile = self::getCacheFilePath();

		if (!file_exists($cacheFile)) {
			return null;
		}

		if (time() - filemtime($cacheFile) > 86400) {
			self::clearCache();
			return null;
		}

		$cacheData = include $cacheFile;

		if (!is_array($cacheData) || !isset($cacheData['routes'])) {
			return null;
		}

		return $cacheData['routes'];
	}

	public static function clearCache(): bool
	{
		$cacheFile = self::getCacheFilePath();

		if (file_exists($cacheFile)) {
			return unlink($cacheFile);
		}

		return true;
	}

	public static function getCacheStats(): array
	{
		$cacheFile = self::getCacheFilePath();
		$stats = [
			'enabled' => self::$enabled,
			'cache_dir' => self::$cacheDir,
			'cache_exists' => file_exists($cacheFile),
			'cache_size' => 0,
			'cache_age' => 0,
			'writable' => is_writable(dirname($cacheFile)),
		];

		if ($stats['cache_exists']) {
			$stats['cache_size'] = filesize($cacheFile);
			$stats['cache_age'] = time() - filemtime($cacheFile);
		}

		return $stats;
	}

	/**
	 * Warm up cache — no-op by design to avoid recursion during bootstrap.
	 * Kept for BC; callers should rely on natural first-request compilation.
	 * Dynamic path instead of hardcoded /var/www/html/nsy
	 */
	public static function warmUp(array $routeFiles = []): bool
	{
		if (self::$warmingUp) {
			return false;
		}

		self::$warmingUp = true;

		try {
			if (empty($routeFiles)) {
				$sysDir = config_app('sys_dir') ?: 'System';
				$base = __DIR__ . '/../../' . $sysDir . '/Routes';
				$routeFiles = [$base . '/General.php', $base . '/Modules.php'];
			}
			// Intentionally no file I/O — cache builds naturally on first dispatch
			return true;
		} finally {
			self::$warmingUp = false;
		}
	}

	/**
	 * Optimize route patterns — thin wrapper around RouterOptimized patterns.
	 * Deduplicated: uses the same pattern map as NSY_RouterOptimized::compileRoutes()
	 * @param string[] $routes
	 * @return array<int,array{original:string,compiled:string,has_params:bool}>
	 */
	public static function optimizePatterns(array $routes): array
	{
		$patterns = NSY_RouterOptimized::$patterns;
		$searches = array_keys($patterns);
		$replaces = array_values($patterns);

		$optimized = [];
		foreach ($routes as $route) {
			$hasParams = strpos($route, ':') !== false;
			$compiled = $hasParams ? str_replace($searches, $replaces, $route) : $route;
			$optimized[] = [
				'original' => $route,
				'compiled' => $compiled,
				'has_params' => $hasParams,
			];
		}

		return $optimized;
	}

	public static function logRoutePerformance(string $route, float $executionTime, int $memoryUsage): void
	{
		if (self::$cacheDir === null) {
			self::init();
		}
		$logFile = self::$cacheDir . '/performance.log';

		$logData = [
			'timestamp' => date('Y-m-d H:i:s'),
			'route' => $route,
			'execution_time' => $executionTime,
			'memory_usage' => $memoryUsage,
		];

		file_put_contents($logFile, json_encode($logData) . PHP_EOL, FILE_APPEND | LOCK_EX);
	}

	public static function getPerformanceStats(): array
	{
		if (self::$cacheDir === null) {
			self::init();
		}
		$logFile = self::$cacheDir . '/performance.log';

		if (!file_exists($logFile)) {
			return ['total_requests' => 0, 'avg_execution_time' => 0, 'avg_memory_usage' => 0];
		}

		$lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if ($lines === false) {
			return ['total_requests' => 0, 'avg_execution_time' => 0, 'avg_memory_usage' => 0];
		}
		$totalTime = 0.0;
		$totalMemory = 0;
		$count = 0;

		foreach (array_slice($lines, -1000) as $line) {
			$data = json_decode($line, true);
			if (is_array($data)) {
				$totalTime += (float) ($data['execution_time'] ?? 0);
				$totalMemory += (int) ($data['memory_usage'] ?? 0);
				$count++;
			}
		}

		return [
			'total_requests' => $count,
			'avg_execution_time' => $count > 0 ? $totalTime / $count : 0,
			'avg_memory_usage' => $count > 0 ? $totalMemory / $count : 0,
		];
	}
}
