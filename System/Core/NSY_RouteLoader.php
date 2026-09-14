<?php

namespace System\Core;

/**
 * Optimized NSY Route Loader with Auto-Discovery
 * Automatically scans and loads route files with caching and error handling
 *
 * @author NSY Framework Team - Optimized Version
 */
class NSY_RouteLoader
{
    private static $routeCache = [];
    private static $loadedFiles = [];
    private static $performanceStats = [
        'files_scanned' => 0,
        'files_loaded' => 0,
        'files_failed' => 0,
        'cache_hits' => 0,
        'total_load_time' => 0
    ];
    private static $initialized = false;

    // Configuration for route loading
    private static $config = [
        'routes_directory' => null,
        'core_files' => ['General.php', 'Modules.php'], // Load first
        'excluded_files' => ['RouteExample.php', 'TestRoute.php'], // Skip by default
        'cache_enabled' => true,
        'auto_discover' => true,
        'load_order' => 'priority' // 'priority' or 'alphabetical'
    ];

    /**
     * Initialize route loader with configuration
     */
    public static function init($config = [])
    {
        self::$config = array_merge(self::$config, $config);

        // Set routes directory if not provided
        if (self::$config['routes_directory'] === null) {
            self::$config['routes_directory'] = __DIR__ . '/../../' . config_app('sys_dir') . '/Routes';
        }

        self::$initialized = true;
    }

    /**
     * Load all route files with optimization
     */
    public static function loadRoutes()
    {
        if (!self::$initialized) {
            self::init();
        }

        $startTime = microtime(true);
        $routesDir = self::$config['routes_directory'];

        // Check cache first
        if (self::$config['cache_enabled'] && !empty(self::$routeCache)) {
            self::$performanceStats['cache_hits']++;
            return self::loadCachedRoutes();
        }

        // Check if user has manually configured routes
        $userRoutes = config_app('routes');
        $hasManualRoutes = is_array($userRoutes) && !empty($userRoutes);

        // Auto-discover route files or use manual configuration
        if (self::$config['auto_discover'] && !$hasManualRoutes) {
            $routeFiles = self::discoverRouteFiles($routesDir);
        } else {
            // Use configured files only (including manual routes from config)
            $routeFiles = self::getConfiguredFiles($routesDir);
        }

        // Load core migration route first
        self::loadMigrationRoute();

        // Load files in priority order
        $loadedCount = 0;
        foreach ($routeFiles as $file) {
            if (self::loadRouteFile($file)) {
                $loadedCount++;
            }
        }

        // Cache the results
        if (self::$config['cache_enabled']) {
            self::$routeCache = $routeFiles;
        }

        self::$performanceStats['files_loaded'] = $loadedCount;
        self::$performanceStats['total_load_time'] = microtime(true) - $startTime;

        return true;
    }

    /**
     * Discover route files in directory
     */
    private static function discoverRouteFiles($routesDir)
    {
        if (!is_dir($routesDir)) {
            error_log("NSY_RouteLoader: Routes directory not found: $routesDir");
            return [];
        }

        $files = [];
        $coreFiles = [];
        $regularFiles = [];

        // Scan directory for PHP files
        $scannedFiles = scandir($routesDir);
        self::$performanceStats['files_scanned'] = count($scannedFiles);

        foreach ($scannedFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $fullPath = $routesDir . '/' . $file;

                // Skip excluded files
                if (in_array($file, self::$config['excluded_files'])) {
                    continue;
                }

                // Separate core files from regular files
                if (in_array($file, self::$config['core_files'])) {
                    $coreFiles[] = $fullPath;
                } else {
                    $regularFiles[] = $fullPath;
                }
            }
        }

        // Sort core files by configured priority
        $coreFiles = self::sortByPriority($coreFiles, self::$config['core_files']);

        // Sort regular files
        if (self::$config['load_order'] === 'alphabetical') {
            sort($regularFiles);
        }

        // Merge core files first, then regular files
        return array_merge($coreFiles, $regularFiles);
    }

    /**
     * Get configured files (non-auto-discovery mode)
     */
    private static function getConfiguredFiles($routesDir)
    {
        $files = [];

        // Load core files
        foreach (self::$config['core_files'] as $file) {
            $fullPath = $routesDir . '/' . $file;
            if (file_exists($fullPath)) {
                $files[] = $fullPath;
            }
        }

        // Load user-defined routes from config
        $userRoutes = config_app('routes');
        if (is_array($userRoutes)) {
            foreach ($userRoutes as $filename) {
                $fullPath = $routesDir . '/' . $filename . '.php';
                if (file_exists($fullPath) && !in_array($fullPath, $files)) {
                    $files[] = $fullPath;
                }
            }
        }

        return $files;
    }

    /**
     * Load migration route (always loaded first)
     */
    private static function loadMigrationRoute()
    {
        $migrationRoute = __DIR__ . '/NSY_Migration_Route.php';
        if (file_exists($migrationRoute)) {
            self::loadRouteFile($migrationRoute);
        }
    }

    /**
     * Load individual route file with error handling
     */
    private static function loadRouteFile($filePath)
    {
        // Skip if already loaded
        if (in_array($filePath, self::$loadedFiles)) {
            return true;
        }

        // Validate file exists and is readable
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
            // Load the route file
            require_once $filePath;
            self::$loadedFiles[] = $filePath;
            return true;

        } catch (Exception $e) {
            error_log("NSY_RouteLoader: Error loading route file $filePath: " . $e->getMessage());
            self::$performanceStats['files_failed']++;
            return false;
        } catch (ParseError $e) {
            error_log("NSY_RouteLoader: Parse error in route file $filePath: " . $e->getMessage());
            self::$performanceStats['files_failed']++;
            return false;
        }
    }

    /**
     * Load cached routes
     */
    private static function loadCachedRoutes()
    {
        foreach (self::$routeCache as $file) {
            self::loadRouteFile($file);
        }
        return true;
    }

    /**
     * Sort files by priority order
     */
    private static function sortByPriority($files, $priorityOrder)
    {
        $sorted = [];

        // First, add files in priority order
        foreach ($priorityOrder as $priority) {
            foreach ($files as $file) {
                if (basename($file) === $priority) {
                    $sorted[] = $file;
                    break;
                }
            }
        }

        // Then add any remaining files
        foreach ($files as $file) {
            if (!in_array($file, $sorted)) {
                $sorted[] = $file;
            }
        }

        return $sorted;
    }

    /**
     * Configure route loader
     */
    public static function configure($key, $value)
    {
        if (array_key_exists($key, self::$config)) {
            self::$config[$key] = $value;

            // Clear cache if configuration changes
            if (self::$config['cache_enabled']) {
                self::clearCache();
            }
        }
    }

    /**
     * Get performance statistics
     */
    public static function getPerformanceStats()
    {
        return self::$performanceStats;
    }

    /**
     * Get loaded files list
     */
    public static function getLoadedFiles()
    {
        return self::$loadedFiles;
    }

    /**
     * Clear route cache
     */
    public static function clearCache()
    {
        self::$routeCache = [];
        self::$loadedFiles = [];
        self::$performanceStats = [
            'files_scanned' => 0,
            'files_loaded' => 0,
            'files_failed' => 0,
            'cache_hits' => 0,
            'total_load_time' => 0
        ];
    }

    /**
     * Enable/disable auto-discovery
     */
    public static function setAutoDiscovery($enabled)
    {
        self::configure('auto_discover', $enabled);
    }

    /**
     * Add file to exclusion list
     */
    public static function excludeFile($filename)
    {
        if (!in_array($filename, self::$config['excluded_files'])) {
            self::$config['excluded_files'][] = $filename;
        }
    }

    /**
     * Remove file from exclusion list
     */
    public static function includeFile($filename)
    {
        $key = array_search($filename, self::$config['excluded_files']);
        if ($key !== false) {
            unset(self::$config['excluded_files'][$key]);
            self::$config['excluded_files'] = array_values(self::$config['excluded_files']);
        }
    }

    /**
     * Generate performance report
     */
    public static function generateReport()
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
                'total_load_time_ms' => round($stats['total_load_time'] * 1000, 2)
            ],
            'configuration' => [
                'auto_discovery' => self::$config['auto_discover'],
                'cache_enabled' => self::$config['cache_enabled'],
                'excluded_files' => self::$config['excluded_files'],
                'core_files' => self::$config['core_files']
            ],
            'loaded_files' => array_map('basename', self::$loadedFiles)
        ];
    }

    /**
     * Debug information
     */
    public static function debugInfo()
    {
        if (config_app('app_env') === 'production') {
            return null;
        }

        return [
            'initialized' => self::$initialized,
            'config' => self::$config,
            'loaded_files_count' => count(self::$loadedFiles),
            'cached_files_count' => count(self::$routeCache),
            'performance_stats' => self::$performanceStats
        ];
    }
}
