<?php

declare(strict_types=1);

namespace System\Core;

/**
 * Optimized NSY Route Loader with Auto-Discovery
 * Responsibility: discover & require_once route files. NOT dispatch or compile.
 * Dispatch/compilation stays in NSY_RouterOptimized; file-persisted cache in NSY_RouteCacheManager.
 */
class NSY_RouteLoader
{
	/** @var string[] */
	private static array $routeCache = [];
	/** @var string[] */
	private static array $loadedFiles = [];
	private static array $performanceStats = [
		'files_scanned' => 0,
		'files_loaded' => 0,
		'files_failed' => 0,
		'cache_hits' => 0,
		'total_load_time' => 0,
	];
	private static bool $initialized = false;

	private static array $config = [
		'routes_directory' => null,
		'core_files' => ['General.php', 'Modules.php'],
		'excluded_files' => ['Route_Example.php', 'TestRoute.php'],
		'cache_enabled' => true,
		'auto_discover' => true,
		'load_order' => 'priority',
	];

	public static function init(array $config = []): void
	{
		self::$config = array_merge(self::$config, $config);

		if (self::$config['routes_directory'] === null) {
			$sysDir = config_app('sys_dir') ?: 'System';
			self::$config['routes_directory'] = __DIR__ . '/../../' . $sysDir . '/Routes';
		}

		self::$initialized = true;
	}

	public static function loadRoutes(): bool
	{
		if (!self::$initialized) {
			self::init();
		}

		$startTime = microtime(true);
		$routesDir = (string) self::$config['routes_directory'];

		if (self::$config['cache_enabled'] && !empty(self::$routeCache)) {
			self::$performanceStats['cache_hits']++;
			return self::loadCachedRoutes();
		}

		$userRoutes = config_app('routes');
		$hasManualRoutes = is_array($userRoutes) && !empty($userRoutes);

		$routeFiles = (self::$config['auto_discover'] && !$hasManualRoutes)
			? self::discoverRouteFiles($routesDir)
			: self::getConfiguredFiles($routesDir);

		self::loadMigrationRoute();

		$loadedCount = 0;
		foreach ($routeFiles as $file) {
			if (self::loadRouteFile($file)) {
				$loadedCount++;
			}
		}

		if (self::$config['cache_enabled']) {
			self::$routeCache = $routeFiles;
		}

		self::$performanceStats['files_loaded'] = $loadedCount;
		self::$performanceStats['total_load_time'] = microtime(true) - $startTime;

		return true;
	}

	/**
	 * @return string[]
	 */
	private static function discoverRouteFiles(string $routesDir): array
	{
		if (!is_dir($routesDir)) {
			error_log("NSY_RouteLoader: Routes directory not found: $routesDir");
			return [];
		}

		$scannedFiles = scandir($routesDir);
		if ($scannedFiles === false) {
			return [];
		}
		self::$performanceStats['files_scanned'] = count($scannedFiles);

		$coreFiles = [];
		$regularFiles = [];
		$excluded = (array) self::$config['excluded_files'];
		$coreNames = (array) self::$config['core_files'];

		foreach ($scannedFiles as $file) {
			if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
				continue;
			}
			if (in_array($file, $excluded, true)) {
				continue;
			}
			$fullPath = $routesDir . '/' . $file;
			if (in_array($file, $coreNames, true)) {
				$coreFiles[] = $fullPath;
			} else {
				$regularFiles[] = $fullPath;
			}
		}

		$coreFiles = self::sortByPriority($coreFiles, $coreNames);

		if (self::$config['load_order'] === 'alphabetical') {
			sort($regularFiles);
		}

		return array_merge($coreFiles, $regularFiles);
	}

	/**
	 * @return string[]
	 */
	private static function getConfiguredFiles(string $routesDir): array
	{
		$files = [];

		foreach ((array) self::$config['core_files'] as $file) {
			$fullPath = $routesDir . '/' . $file;
			if (file_exists($fullPath)) {
				$files[] = $fullPath;
			}
		}

		$userRoutes = config_app('routes');
		if (is_array($userRoutes)) {
			foreach ($userRoutes as $filename) {
				$fullPath = $routesDir . '/' . $filename . '.php';
				if (file_exists($fullPath) && !in_array($fullPath, $files, true)) {
					$files[] = $fullPath;
				}
			}
		}

		return $files;
	}

	private static function loadMigrationRoute(): void
	{
		$migrationRoute = __DIR__ . '/NSY_Migration_Route.php';
		if (file_exists($migrationRoute)) {
			self::loadRouteFile($migrationRoute);
		}
	}

	private static function loadRouteFile(string $filePath): bool
	{
		if (in_array($filePath, self::$loadedFiles, true)) {
			return true;
		}

		if (!file_exists($filePath)) {
			error_log("NSY_RouteLoader: Route file not found: $filePath");
			self::$performanceStats['files_failed']++;
			return false;
		}

		if (!is_readable($filePath)) {
			error_log("NSY_RouteLoader: Route file not readable: $filePath");
			self::$performanceStats['files_failed']++;
			return false;
		}

		try {
			require_once $filePath;
			self::$loadedFiles[] = $filePath;
			return true;
		} catch (\Throwable $e) {
			error_log("NSY_RouteLoader: Error loading route file $filePath: " . $e->getMessage());
			self::$performanceStats['files_failed']++;
			return false;
		}
	}

	private static function loadCachedRoutes(): bool
	{
		foreach (self::$routeCache as $file) {
			self::loadRouteFile($file);
		}
		return true;
	}

	/**
	 * @param string[] $files
	 * @param string[] $priorityOrder
	 * @return string[]
	 */
	private static function sortByPriority(array $files, array $priorityOrder): array
	{
		$sorted = [];

		foreach ($priorityOrder as $priority) {
			foreach ($files as $file) {
				if (basename($file) === $priority) {
					$sorted[] = $file;
					break;
				}
			}
		}

		foreach ($files as $file) {
			if (!in_array($file, $sorted, true)) {
				$sorted[] = $file;
			}
		}

		return $sorted;
	}

	public static function configure(string $key, mixed $value): void
	{
		if (array_key_exists($key, self::$config)) {
			self::$config[$key] = $value;

			if (self::$config['cache_enabled']) {
				self::clearCache();
			}
		}
	}

	public static function getPerformanceStats(): array
	{
		return self::$performanceStats;
	}

	/** @return string[] */
	public static function getLoadedFiles(): array
	{
		return self::$loadedFiles;
	}

	public static function clearCache(): void
	{
		self::$routeCache = [];
		self::$loadedFiles = [];
		self::$performanceStats = [
			'files_scanned' => 0,
			'files_loaded' => 0,
			'files_failed' => 0,
			'cache_hits' => 0,
			'total_load_time' => 0,
		];
	}

	public static function setAutoDiscovery(bool $enabled): void
	{
		self::configure('auto_discover', $enabled);
	}

	public static function excludeFile(string $filename): void
	{
		if (!in_array($filename, self::$config['excluded_files'], true)) {
			self::$config['excluded_files'][] = $filename;
		}
	}

	public static function includeFile(string $filename): void
	{
		$key = array_search($filename, self::$config['excluded_files'], true);
		if ($key !== false) {
			unset(self::$config['excluded_files'][$key]);
			self::$config['excluded_files'] = array_values(self::$config['excluded_files']);
		}
	}

	public static function generateReport(): array
	{
		$stats = self::$performanceStats;
		$successRate = $stats['files_scanned'] > 0
			? round(($stats['files_loaded'] / $stats['files_scanned']) * 100, 2)
			: 0;

		return [
			'summary' => [
				'files_scanned' => $stats['files_scanned'],
				'files_loaded' => $stats['files_loaded'],
				'files_failed' => $stats['files_failed'],
				'success_rate' => $successRate . '%',
				'cache_hits' => $stats['cache_hits'],
				'total_load_time_ms' => round($stats['total_load_time'] * 1000, 2),
			],
			'configuration' => [
				'auto_discovery' => self::$config['auto_discover'],
				'cache_enabled' => self::$config['cache_enabled'],
				'excluded_files' => self::$config['excluded_files'],
				'core_files' => self::$config['core_files'],
			],
			'loaded_files' => array_map('basename', self::$loadedFiles),
		];
	}

	public static function debugInfo(): ?array
	{
		if (config_app('app_env') === 'production') {
			return null;
		}

		return [
			'initialized' => self::$initialized,
			'config' => self::$config,
			'loaded_files_count' => count(self::$loadedFiles),
			'cached_files_count' => count(self::$routeCache),
			'performance_stats' => self::$performanceStats,
		];
	}
}
