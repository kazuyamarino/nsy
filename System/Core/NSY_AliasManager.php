<?php

namespace System\Core;

/**
 * Optimized NSY Class Alias Manager
 * Provides caching, validation, and performance monitoring for class aliases
 * 
 * @author NSY Framework Team - Optimized Version
 */
class NSY_AliasManager
{
    private static $aliasCache = [];
    private static $performanceStats = [
        'total_aliases' => 0,
        'successful_aliases' => 0,
        'failed_aliases' => 0,
        'cache_hits' => 0,
        'load_time' => 0
    ];
    private static $configCache = null;
    private static $initialized = false;

    /**
     * Initialize and load all aliases with optimization
     */
    public static function loadAliases()
    {
        if (self::$initialized) {
            self::$performanceStats['cache_hits']++;
            return true;
        }

        $startTime = microtime(true);

        try {
            // Cache configuration to avoid repeated config_app() calls
            if (self::$configCache === null) {
                self::$configCache = config_app('aliases');
            }

            $aliases = self::$configCache;

            if (!is_array($aliases)) {
                self::handleError('The aliases configuration must be an array');
                return false;
            }

            self::$performanceStats['total_aliases'] = count($aliases);

            // Process aliases with validation
            foreach ($aliases as $alias => $targetClass) {
                if (self::createAlias($alias, $targetClass)) {
                    self::$performanceStats['successful_aliases']++;
                } else {
                    self::$performanceStats['failed_aliases']++;
                }
            }

            self::$initialized = true;
            self::$performanceStats['load_time'] = microtime(true) - $startTime;

            return true;

        } catch (Exception $e) {
            self::handleError('Failed to load aliases: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create individual alias with validation
     */
    private static function createAlias($alias, $targetClass)
    {
        // Check if alias already exists in cache
        if (isset(self::$aliasCache[$alias])) {
            return true;
        }

        // Validate inputs
        if (!self::validateAlias($alias, $targetClass)) {
            return false;
        }

        try {
            // Create the alias
            if (!class_exists($alias, false) && !interface_exists($alias, false)) {
                class_alias($targetClass, $alias);
                self::$aliasCache[$alias] = $targetClass;
                return true;
            } else {
                error_log("NSY_AliasManager: Alias '$alias' already exists, skipping...");
                return false;
            }
        } catch (Exception $e) {
            error_log("NSY_AliasManager: Failed to create alias '$alias': " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate alias and target class
     */
    private static function validateAlias($alias, $targetClass)
    {
        // Check if alias name is valid
        if (empty($alias) || !is_string($alias)) {
            error_log("NSY_AliasManager: Invalid alias name provided");
            return false;
        }

        // Check if target class is valid
        if (empty($targetClass) || !is_string($targetClass)) {
            error_log("NSY_AliasManager: Invalid target class for alias '$alias'");
            return false;
        }

        // Check if target class exists (with autoloading)
        if (!class_exists($targetClass) && !interface_exists($targetClass)) {
            error_log("NSY_AliasManager: Target class '$targetClass' does not exist for alias '$alias'");
            return false;
        }

        return true;
    }

    /**
     * Get performance statistics
     */
    public static function getPerformanceStats()
    {
        return self::$performanceStats;
    }

    /**
     * Get cached aliases
     */
    public static function getCachedAliases()
    {
        return self::$aliasCache;
    }

    /**
     * Clear alias cache (useful for testing)
     */
    public static function clearCache()
    {
        self::$aliasCache = [];
        self::$configCache = null;
        self::$initialized = false;
        self::$performanceStats = [
            'total_aliases' => 0,
            'successful_aliases' => 0,
            'failed_aliases' => 0,
            'cache_hits' => 0,
            'load_time' => 0
        ];
    }

    /**
     * Check if alias exists
     */
    public static function hasAlias($alias)
    {
        return isset(self::$aliasCache[$alias]);
    }

    /**
     * Get target class for alias
     */
    public static function getTargetClass($alias)
    {
        return self::$aliasCache[$alias] ?? null;
    }

    /**
     * Create single alias on demand (lazy loading)
     */
    public static function createSingleAlias($alias, $targetClass = null)
    {
        // If no target class provided, try to get from config
        if ($targetClass === null) {
            if (self::$configCache === null) {
                self::$configCache = config_app('aliases');
            }
            $targetClass = self::$configCache[$alias] ?? null;
        }

        if ($targetClass === null) {
            return false;
        }

        return self::createAlias($alias, $targetClass);
    }

    /**
     * Handle errors with improved messaging
     */
    private static function handleError($message)
    {
        $errorMsg = "NSY Class Alias Manager Error: $message";
        
        if (class_exists('System\Core\NSY_Desk')) {
            NSY_Desk::static_error_handler($errorMsg);
        } else {
            error_log($errorMsg);
            if (config_app('app_env') !== 'production') {
                echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px;'>";
                echo "<strong>Alias Manager Error:</strong> " . htmlspecialchars($message);
                echo "</div>";
            }
        }
    }

    /**
     * Debug information (development mode only)
     */
    public static function debugInfo()
    {
        if (config_app('app_env') === 'production') {
            return null;
        }

        return [
            'initialized' => self::$initialized,
            'cached_aliases_count' => count(self::$aliasCache),
            'performance_stats' => self::$performanceStats,
            'memory_usage' => memory_get_usage(true),
            'cached_aliases' => array_keys(self::$aliasCache)
        ];
    }

    /**
     * Generate performance report
     */
    public static function generateReport()
    {
        $stats = self::$performanceStats;
        $successRate = $stats['total_aliases'] > 0 
            ? round(($stats['successful_aliases'] / $stats['total_aliases']) * 100, 2) 
            : 0;

        return [
            'summary' => [
                'total_aliases' => $stats['total_aliases'],
                'successful' => $stats['successful_aliases'],
                'failed' => $stats['failed_aliases'],
                'success_rate' => $successRate . '%',
                'cache_hits' => $stats['cache_hits'],
                'load_time_ms' => round($stats['load_time'] * 1000, 2)
            ],
            'performance' => [
                'average_load_time' => $stats['load_time'] > 0 ? round($stats['load_time'] / $stats['total_aliases'] * 1000, 4) . 'ms per alias' : '0ms',
                'memory_usage' => self::formatBytes(memory_get_usage(true)),
                'cached_count' => count(self::$aliasCache)
            ]
        ];
    }

    /**
     * Format bytes for human readable output
     */
    private static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
