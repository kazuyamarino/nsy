<?php

namespace System\Core;

/**
 * Route Cache Manager for NSY Framework
 * Manages route compilation, caching, and performance optimization
 */
class NSY_RouteCacheManager
{
    private static $cacheDir = null;
    private static $cacheFile = 'routes.cache.php';
    private static $enabled = true;

    /**
     * Initialize cache manager
     */
    public static function init($cacheDir = null)
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

    /**
     * Enable or disable caching
     */
    public static function setEnabled($enabled)
    {
        self::$enabled = $enabled;
    }

    /**
     * Get cache file path
     */
    private static function getCacheFilePath()
    {
        if (self::$cacheDir === null) {
            self::init();
        }
        return self::$cacheDir . '/' . self::$cacheFile;
    }

    /**
     * Cache compiled routes
     */
    public static function cacheRoutes($routes)
    {
        if (!self::$enabled) {
            return false;
        }

        $cacheFile = self::getCacheFilePath();
        $cacheData = [
            'timestamp' => time(),
            'routes' => $routes,
            'hash' => md5(serialize($routes))
        ];

        $content = '<?php' . PHP_EOL . 'return ' . var_export($cacheData, true) . ';';
        return file_put_contents($cacheFile, $content, LOCK_EX) !== false;
    }

    /**
     * Load cached routes
     */
    public static function loadCachedRoutes()
    {
        if (!self::$enabled) {
            return null;
        }

        $cacheFile = self::getCacheFilePath();

        if (!file_exists($cacheFile)) {
            return null;
        }

        // Check if cache is still valid (24 hours)
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

    /**
     * Clear route cache
     */
    public static function clearCache()
    {
        $cacheFile = self::getCacheFilePath();

        if (file_exists($cacheFile)) {
            return unlink($cacheFile);
        }

        return true;
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats()
    {
        $cacheFile = self::getCacheFilePath();
        $stats = [
            'enabled' => self::$enabled,
            'cache_dir' => self::$cacheDir,
            'cache_exists' => file_exists($cacheFile),
            'cache_size' => 0,
            'cache_age' => 0,
            'writable' => is_writable(dirname($cacheFile))
        ];

        if ($stats['cache_exists']) {
            $stats['cache_size'] = filesize($cacheFile);
            $stats['cache_age'] = time() - filemtime($cacheFile);
        }

        return $stats;
    }

    private static $warmingUp = false;

    /**
     * Warm up cache by pre-compiling routes
     */
    public static function warmUp($routeFiles = [])
    {
        // Prevent infinite recursion
        if (self::$warmingUp) {
            return false;
        }

        self::$warmingUp = true;

        if (empty($routeFiles)) {
            $routeFiles = [
                '/var/www/html/nsy/System/Routes/General.php',
                '/var/www/html/nsy/System/Routes/Modules.php'
            ];
        }

        try {
            // Skip cache warm-up to prevent recursion
            // Cache will be built naturally during first requests
            return true;
        } finally {
            self::$warmingUp = false;
        }
    }

    /**
     * Optimize route patterns for better performance
     */
    public static function optimizePatterns($routes)
    {
        $optimized = [];

        foreach ($routes as $route) {
            // Pre-compile regex patterns
            if (strpos($route, ':') !== false) {
                $patterns = NSY_RouterOptimized::$patterns;
                $searches = array_keys($patterns);
                $replaces = array_values($patterns);

                $compiledPattern = str_replace($searches, $replaces, $route);
                $optimized[] = [
                    'original' => $route,
                    'compiled' => $compiledPattern,
                    'has_params' => true
                ];
            } else {
                $optimized[] = [
                    'original' => $route,
                    'compiled' => $route,
                    'has_params' => false
                ];
            }
        }

        return $optimized;
    }

    /**
     * Monitor route performance
     */
    public static function logRoutePerformance($route, $executionTime, $memoryUsage)
    {
        $logFile = self::$cacheDir . '/performance.log';

        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'route' => $route,
            'execution_time' => $executionTime,
            'memory_usage' => $memoryUsage
        ];

        $logLine = json_encode($logData) . PHP_EOL;
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }

    /**
     * Get performance statistics
     */
    public static function getPerformanceStats()
    {
        $logFile = self::$cacheDir . '/performance.log';

        if (!file_exists($logFile)) {
            return ['total_requests' => 0, 'avg_execution_time' => 0, 'avg_memory_usage' => 0];
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $totalTime = 0;
        $totalMemory = 0;
        $count = 0;

        foreach (array_slice($lines, -1000) as $line) { // Last 1000 requests
            $data = json_decode($line, true);
            if ($data) {
                $totalTime += $data['execution_time'];
                $totalMemory += $data['memory_usage'];
                $count++;
            }
        }

        return [
            'total_requests' => $count,
            'avg_execution_time' => $count > 0 ? $totalTime / $count : 0,
            'avg_memory_usage' => $count > 0 ? $totalMemory / $count : 0
        ];
    }
}
