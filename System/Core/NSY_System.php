<?php

namespace System\Core;

use Josantonius\Session\Facades\Session;

/**
 * NSY System Core - Optimized Configuration and Initialization
 * 
 * This is the core of NSY System Settings that handles:
 * - Asset directory path definitions
 * - System directory path definitions  
 * - Site configuration constants
 * - PDO constants and bindings
 * - Session initialization
 * 
 * @package System\Core
 * @version 2.0.0
 * @author NSY Framework Team
 */
class NSY_System
{
	/**
	 * Cached configuration values for performance optimization
	 */
	private array $configCache = [];

	/**
	 * Initialize NSY Core System with optimized configuration loading
	 */
	public function __construct()
	{
		$this->initializeAssetDirectories();
		$this->initializeSystemDirectories();
		$this->initializeApplicationSettings();
		$this->initializeSiteConstants();
		$this->initializePDOConstants();
		$this->initializeSession();
	}

	/**
	 * Initialize asset directory constants with optimized path building
	 */
	private function initializeAssetDirectories(): void
	{
		$baseUrl = base_url();
		$publicDir = $this->getCachedConfig('app', 'public_dir');
		
		// Determine asset base path
		$assetBasePath = is_filled($publicDir) 
			? "{$baseUrl}{$publicDir}/assets/"
			: "{$baseUrl}assets/";

		// Define asset directories with cached config values
		$assetDirs = [
			'CSS_DIR' => $this->getCachedConfig('app', 'css_dir'),
			'JS_DIR' => $this->getCachedConfig('app', 'js_dir'),
			'IMG_DIR' => $this->getCachedConfig('app', 'img_dir')
		];

		foreach ($assetDirs as $constant => $dir) {
			define($constant, $assetBasePath . $dir . '/');
		}
	}

	/**
	 * Initialize system directory constants
	 */
	private function initializeSystemDirectories(): void
	{
		$systemDirs = [
			'SYS_TMP_DIR' => $this->getCachedConfig('app', 'tmp_dir'),
			'MVC_VIEW_DIR' => $this->getCachedConfig('app', 'mvc_dir'),
			'HMVC_VIEW_DIR' => $this->getCachedConfig('app', 'hmvc_dir'),
			'VENDOR_DIR' => $this->getCachedConfig('app', 'vendor_dir')
		];

		foreach ($systemDirs as $constant => $dir) {
			define($constant, $dir . '/');
		}
	}

	/**
	 * Initialize application-level settings and constants
	 */
	private function initializeApplicationSettings(): void
	{
		// Application configuration constants
		$appSettings = [
			'LANGUAGE_CODE' => $this->getCachedConfig('app', 'locale'),
			'OG_PREFIX' => $this->getCachedConfig('app', 'prefix_attr'),
			'SESSION_PREFIX' => $this->getCachedConfig('app', 'session_prefix')
		];

		foreach ($appSettings as $constant => $value) {
			define($constant, $value);
		}

		// Set application timezone
		date_default_timezone_set($this->getCachedConfig('app', 'timezone'));
	}

	/**
	 * Initialize site-related constants
	 */
	private function initializeSiteConstants(): void
	{
		$siteSettings = [
			'SITETITLE' => $this->getCachedConfig('site', 'sitetitle'),
			'SITEAUTHOR' => $this->getCachedConfig('site', 'siteauthor'),
			'SITEKEYWORDS' => $this->getCachedConfig('site', 'sitekeywords'),
			'SITEDESCRIPTION' => $this->getCachedConfig('site', 'sitedesc'),
			'SITEEMAIL' => $this->getCachedConfig('site', 'siteemail'),
			'VERSION' => $this->getCachedConfig('site', 'version'),
			'CODENAME' => $this->getCachedConfig('site', 'codename')
		];

		foreach ($siteSettings as $constant => $value) {
			define($constant, $value);
		}
	}

	/**
	 * Initialize PDO-related constants with optimized definition checking
	 */
	private function initializePDOConstants(): void
	{
		// PDO parameter type constants
		$pdoParams = [
			'PAR_INT' => \PDO::PARAM_INT,
			'PAR_STR' => \PDO::PARAM_STR
		];

		// PDO binding type constants
		$pdoBindings = [
			'BINDVAL' => 'BINDVALUE',
			'BINDPAR' => 'BINDPARAM'
		];

		// PDO fetch mode constants
		$pdoFetchModes = [
			'FETCH_NUM' => \PDO::FETCH_NUM,
			'FETCH_COLUMN' => \PDO::FETCH_COLUMN,
			'FETCH_ASSOC' => \PDO::FETCH_ASSOC,
			'FETCH_BOTH' => \PDO::FETCH_BOTH,
			'FETCH_OBJ' => \PDO::FETCH_OBJ,
			'FETCH_LAZY' => \PDO::FETCH_LAZY,
			'FETCH_CLASS' => \PDO::FETCH_CLASS,
			'FETCH_KEY_PAIR' => \PDO::FETCH_KEY_PAIR,
			'FETCH_UNIQUE' => \PDO::FETCH_UNIQUE,
			'FETCH_GROUP' => \PDO::FETCH_GROUP,
			'FETCH_FUNC' => \PDO::FETCH_FUNC
		];

		// Define all PDO constants efficiently
		$allConstants = array_merge($pdoParams, $pdoBindings, $pdoFetchModes);
		
		foreach ($allConstants as $constant => $value) {
			defined($constant) or define($constant, $value);
		}
	}

	/**
	 * Initialize session with cached configuration
	 */
	private function initializeSession(): void
	{
		Session::start($this->getCachedConfig('app', 'session_config'));
	}

	/**
	 * Get cached configuration value to avoid repeated function calls
	 * 
	 * @param string $type Configuration type ('app' or 'site')
	 * @param string $key Configuration key
	 * @return mixed Configuration value
	 */
	private function getCachedConfig(string $type, string $key): mixed
	{
		$cacheKey = "{$type}.{$key}";
		
		if (!isset($this->configCache[$cacheKey])) {
			$this->configCache[$cacheKey] = $type === 'app' 
				? config_app($key) 
				: config_site($key);
		}
		
		return $this->configCache[$cacheKey];
	}
}
