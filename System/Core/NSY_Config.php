<?php

declare(strict_types=1);

namespace System\Core;

/**
 * Config loader with per-request memoization.
 *
 * The config getters (config_app / config_site / config_env / config_db) run
 * many times per request, and each call used to re-include the config file.
 * This class includes every source at most once and caches the resulting array
 * for the rest of the request.
 *
 * There is no persistent cache file, so editing env.php / App.php / Site.php
 * takes effect on the very next request. Use clear() in tests when a test
 * rewrites a config file mid-run.
 */
class NSY_Config
{
	/** Relative path from this directory to each known config source. */
	private const SOURCES = [
		'app'  => '/../Config/App.php',
		'site' => '/../Config/Site.php',
		'env'  => '/../../env.php',
	];

	/** @var array<string,array<string,mixed>> */
	private static array $cache = [];

	/**
	 * Load and memoize one config source.
	 *
	 * @param  string $name app|site|env
	 * @return array<string,mixed>
	 */
	public static function get(string $name): array
	{
		if (array_key_exists($name, self::$cache)) {
			return self::$cache[$name];
		}

		$relative = self::SOURCES[$name] ?? null;
		if ($relative === null) {
			return self::$cache[$name] = [];
		}

		$path = __DIR__ . $relative;
		$data = is_file($path) ? include $path : [];

		return self::$cache[$name] = is_array($data) ? $data : [];
	}

	/**
	 * Names loaded so far in this request.
	 *
	 * @return string[]
	 */
	public static function loaded(): array
	{
		return array_keys(self::$cache);
	}

	/**
	 * Drop every memoized source (mainly for tests).
	 */
	public static function clear(): void
	{
		self::$cache = [];
	}
}
