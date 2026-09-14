<?php

namespace System\Core;

/**
 * Optimized NSY System File Loader with Auto-Discovery
 * Automatically loads Core helpers, Libraries, and Config files with caching
 *
 * @author NSY Framework Team - Optimized Version
 */
class NSY_SystemLoader
{
    private static $loadedFiles = [];
    private static $fileCache = [];
    private static $performanceStats = [
        'core_files_loaded' => 0,
        'library_files_loaded' => 0,
        'config_files_loaded' => 0,
        'total_load_time' => 0,
        'cache_hits' => 0
    ];
    private static $initialized = false;

    // System file configuration
    private static $systemConfig = [
        'core_helpers' => [
            'NSY_Helpers_File.php',
            'NSY_Helpers_Language.php',
            'NSY_Helpers_LoadTime.php',
            'NSY_Helpers_Request.php',
            'NSY_Helpers_Validate.php'
        ],
        'libraries' => [
            'Aliases.php'
        ],
        'configs' => [
            'Assets.php'
        ],
        'cache_enabled' => true,
        'auto_discover' => false // Keep manual for system files for safety
    ];

    /**
     * Load all system files with optimization
     */
    public static function loadSystemFiles()
    {
        if (self::$initialized && self::$systemConfig['cache_enabled']) {
            self::$performanceStats['cache_hits']++;
            return true;
        }

        $startTime = microtime(true);

        try {
            // Load core helper files
            self::loadCoreHelpers();

            // Load library files
            self::loadLibraries();

            // Load configuration files
            self::loadConfigs();

            self::$initialized = true;
            self::$performanceStats['total_load_time'] = microtime(true) - $startTime;

            return true;

        } catch (Exception $e) {
            error_log("NSY_SystemLoader: Failed to load system files: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Load core helper files
     */
    private static function loadCoreHelpers()
    {
        $coreDir = self::getSystemDirectory() . '/Core';

        foreach (self::$systemConfig['core_helpers'] as $file) {
            $filePath = $coreDir . '/' . $file;
            if (self::loadFile($filePath, 'core')) {
                self::$performanceStats['core_files_loaded']++;
            }
        }
    }

    /**
     * Load library files
     */
    private static function loadLibraries()
    {
        $libDir = self::getSystemDirectory() . '/Libraries';

        foreach (self::$systemConfig['libraries'] as $file) {
            $filePath = $libDir . '/' . $file;
            if (self::loadFile($filePath, 'library')) {
                self::$performanceStats['library_files_loaded']++;
            }
        }
    }

    /**
     * Load configuration files
     */
    private static function loadConfigs()
    {
        $configDir = self::getSystemDirectory() . '/Config';

        foreach (self::$systemConfig['configs'] as $file) {
            $filePath = $configDir . '/' . $file;
            if (self::loadFile($filePath, 'config')) {
                self::$performanceStats['config_files_loaded']++;
            }
        }
    }

    /**
     * Load individual file with error handling and caching
     */
    private static function loadFile($filePath, $type = 'unknown')
    {
        // Check cache first
        if (in_array($filePath, self::$loadedFiles)) {
            return true;
        }

        // Validate file exists
        if (!file_exists($filePath)) {
            error_log("NSY_SystemLoader: {$type} file not found: $filePath");
            return false;
        }

        if (!is_readable($filePath)) {
            error_log("NSY_SystemLoader: {$type} file not readable: $filePath");
            return false;
        }

        try {
            require_once $filePath;
            self::$loadedFiles[] = $filePath;
            self::$fileCache[$type][] = $filePath;
            return true;

        } catch (Exception $e) {
            error_log("NSY_SystemLoader: Error loading {$type} file $filePath: " . $e->getMessage());
            return false;
        } catch (ParseError $e) {
            error_log("NSY_SystemLoader: Parse error in {$type} file $filePath: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get system directory path
     */
    private static function getSystemDirectory()
    {
        return __DIR__ . '/../../' . config_app('sys_dir');
    }

    /**
     * Add custom file to load list
     */
    public static function addFile($type, $filename)
    {
        if (isset(self::$systemConfig[$type])) {
            if (!in_array($filename, self::$systemConfig[$type])) {
                self::$systemConfig[$type][] = $filename;
            }
        }
    }

    /**
     * Remove file from load list
     */
    public static function removeFile($type, $filename)
    {
        if (isset(self::$systemConfig[$type])) {
            $key = array_search($filename, self::$systemConfig[$type]);
            if ($key !== false) {
                unset(self::$systemConfig[$type][$key]);
                self::$systemConfig[$type] = array_values(self::$systemConfig[$type]);
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
     * Get loaded files by type
     */
    public static function getLoadedFiles($type = null)
    {
        if ($type === null) {
            return self::$loadedFiles;
        }

        return self::$fileCache[$type] ?? [];
    }

    /**
     * Clear cache
     */
    public static function clearCache()
    {
        self::$loadedFiles = [];
        self::$fileCache = [];
        self::$initialized = false;
        self::$performanceStats = [
            'core_files_loaded' => 0,
            'library_files_loaded' => 0,
            'config_files_loaded' => 0,
            'total_load_time' => 0,
            'cache_hits' => 0
        ];
    }

    /**
     * Configure system loader
     */
    public static function configure($key, $value)
    {
        if (array_key_exists($key, self::$systemConfig)) {
            self::$systemConfig[$key] = $value;
        }
    }

    /**
     * Generate performance report
     */
    public static function generateReport()
    {
        $stats = self::$performanceStats;
        $totalFiles = $stats['core_files_loaded'] + $stats['library_files_loaded'] + $stats['config_files_loaded'];

        return [
            'summary' => [
                'total_files_loaded' => $totalFiles,
                'core_helpers' => $stats['core_files_loaded'],
                'libraries' => $stats['library_files_loaded'],
                'configs' => $stats['config_files_loaded'],
                'cache_hits' => $stats['cache_hits'],
                'total_load_time_ms' => round($stats['total_load_time'] * 1000, 2)
            ],
            'performance' => [
                'average_load_time' => $totalFiles > 0 ? round($stats['total_load_time'] / $totalFiles * 1000, 4) . 'ms per file' : '0ms',
                'memory_usage' => self::formatBytes(memory_get_usage(true)),
                'loaded_files_count' => count(self::$loadedFiles)
            ],
            'loaded_files' => [
                'core' => array_map('basename', self::getLoadedFiles('core')),
                'library' => array_map('basename', self::getLoadedFiles('library')),
                'config' => array_map('basename', self::getLoadedFiles('config'))
            ]
        ];
    }

    /**
     * Format bytes for human readable output
     */
    private static function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
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
            'configuration' => self::$systemConfig,
            'loaded_files_count' => count(self::$loadedFiles),
            'performance_stats' => self::$performanceStats,
            'file_cache' => array_map(function($files) {
                return array_map('basename', $files);
            }, self::$fileCache)
        ];
    }
}
