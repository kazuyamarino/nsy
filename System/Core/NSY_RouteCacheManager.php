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
			@mkdir(self::$cacheDir, 0775, true);
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

	// NOTE: the file-backed route cache (formerly cacheRoutes()/loadCachedRoutes())
	// was removed. Routing uses the in-memory cache in NSY_RouterOptimized, and
	// persisting then include()ing compiled PHP from a shared temp directory was
	// a latent RCE risk. clearCache()/getCacheStats() remain for the perf log.

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

		// Never let an unwritable temp dir break a request — log once instead of warning
		if (@file_put_contents($logFile, json_encode($logData) . PHP_EOL, FILE_APPEND | LOCK_EX) === false) {
			error_log('NSY_RouteCacheManager: unable to write route performance log at ' . $logFile);
		}
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
