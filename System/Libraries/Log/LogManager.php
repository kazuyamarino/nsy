<?php
declare(strict_types=1);

namespace System\Libraries\Log;

use System\Libraries\Log\Formatter\JsonFormatter;
use System\Libraries\Log\Formatter\TextFormatter;
use System\Libraries\Log\Handler\FileHandler;

/**
 * Central logging configuration, channel registry and record writer.
 *
 * Views and framework code talk to a channel through {@see Logger}
 * (PSR-3). This class owns configuration, request correlation, redaction,
 * the slow-query helper and the single {@see FileHandler} instance.
 *
 * Design goals: zero external runtime dependency beyond psr/log, never throw
 * from a log call, and stay cheap when disabled (the default).
 */
final class LogManager
{
	/** Stored levels (PSR-3 extras are normalised onto these). */
	private const LEVELS = [
		'debug' => 0,
		'info' => 1,
		'warning' => 2,
		'error' => 3,
		'critical' => 4,
	];

	/** @var array<string,mixed>|null */
	private static ?array $config = null;

	private static bool $configLoaded = false;

	private static ?FileHandler $handler = null;

	/** @var array<string,Logger> */
	private static array $channels = [];

	private static ?string $requestId = null;

	private static bool $fatalLogged = false;

	private static bool $insideDocrootWarned = false;

	private function __construct()
	{
	}

	/**
	 * Get (or lazily create) the logger for a channel.
	 */
	public static function channel(string $channel = 'app'): Logger
	{
		return self::$channels[$channel] ??= new Logger($channel);
	}

	public static function logger(): Logger
	{
		return self::channel('app');
	}

	public static function enabled(): bool
	{
		return (bool) (self::config()['enabled'] ?? false);
	}

	public static function minLevel(): string
	{
		return (string) (self::config()['level'] ?? 'warning');
	}

	/**
	 * Write one record. Never throws.
	 *
	 * @param array<string,mixed> $context
	 */
	public static function write(string $level, string $channel, string $message, array $context = []): void
	{
		$config = self::config();
		if (empty($config['enabled'])) {
			return;
		}

		$level = self::normalizeLevel($level);
		if (self::severity($level) < self::severity((string) ($config['level'] ?? 'warning'))) {
			return;
		}

		try {
			self::handler()->handle($channel, self::buildRecord($level, $channel, $message, $context));
		} catch (\Throwable $e) {
			// A logger must never break the application.
		}
	}

	/**
	 * PSR-3 level normalisation: NSY keeps 5 stored levels.
	 * emergency/alert collapse onto critical; notice collapses onto info.
	 */
	public static function normalizeLevel(string $level): string
	{
		return match (strtolower($level)) {
			'emergency', 'alert' => 'critical',
			'notice' => 'info',
			'debug', 'info', 'warning', 'error', 'critical' => strtolower($level),
			default => 'info',
		};
	}

	/**
	 * Log a query that exceeded LOG_SLOW_QUERY_MS (SQL normalised, no literals).
	 */
	public static function query(string $sql, float $durationMs): void
	{
		$config = self::config();
		if (empty($config['enabled'])) {
			return;
		}
		if ($durationMs < (float) ($config['slow_query_ms'] ?? 500)) {
			return;
		}

		self::write('warning', 'db', 'Slow query', [
			'duration_ms' => round($durationMs, 2),
			'sql' => self::normalizeSql($sql),
		]);
	}

	/**
	 * Log one HTTP request (only when ACCESS_LOG_ENABLED is true).
	 *
	 * @param array<string,mixed> $fields
	 */
	public static function access(array $fields): void
	{
		$config = self::config();
		if (empty($config['enabled']) || empty($config['access'])) {
			return;
		}

		$fields['uri'] = self::sanitizeUri((string) ($fields['uri'] ?? ''));
		self::write('info', 'access', 'request', $fields);
	}

	/**
	 * Replace string and numeric literals with placeholders before logging.
	 */
	public static function normalizeSql(string $sql): string
	{
		$sql = preg_replace("/'[^']*'/", '?', $sql) ?? $sql;
		$sql = preg_replace('/"[^"]*"/', '?', $sql) ?? $sql;
		$sql = preg_replace('/\b\d+(\.\d+)?\b/', '?', $sql) ?? $sql;

		return trim((string) preg_replace('/\s+/', ' ', $sql));
	}

	/** Drop sensitive query-string values from a logged URI. */
	public static function sanitizeUri(string $uri): string
	{
		$parts = parse_url($uri);
		if ($parts === false || !isset($parts['query'])) {
			return $uri;
		}

		parse_str($parts['query'], $query);
		$sensitive = self::config()['redact'] ?? [];
		foreach (array_keys($query) as $key) {
			if (in_array(strtolower((string) $key), array_map('strtolower', (array) $sensitive), true)) {
				$query[$key] = '***';
			}
		}

		$base = ($parts['scheme'] ?? '') !== '' ? $parts['scheme'] . '://' : '';
		$base .= ($parts['host'] ?? '') . ($parts['path'] ?? '');
		$qs = http_build_query($query);

		return $qs !== '' ? $base . '?' . $qs : $base;
	}

	public static function requestId(): string
	{
		return self::$requestId ??= bin2hex(random_bytes(8));
	}

	public static function isFatalLogged(): bool
	{
		return self::$fatalLogged;
	}

	public static function markFatalLogged(): void
	{
		self::$fatalLogged = true;
	}

	/** Absolute log directory, resolved from LOG_DIR (relative → project root). */
	public static function logDir(): string
	{
		$dir = (string) (self::config()['dir'] ?? '');
		if ($dir === '') {
			$dir = dirname(__DIR__, 3) . '/Storage/logs';
		} elseif (!self::isAbsolute($dir)) {
			$dir = dirname(__DIR__, 3) . '/' . ltrim($dir, '/');
		}

		return rtrim(str_replace('\\', '/', $dir), '/');
	}

	// ---------------------------------------------------------------------

	/** @return array<string,mixed> */
	private static function config(): array
	{
		if (self::$configLoaded) {
			return self::$config ?? [];
		}
		self::$configLoaded = true;

		$defaults = [
			'enabled' => false,
			'dir' => '',
			'level' => 'warning',
			'format' => 'json',
			'split_channels' => false,
			'max_size_mb' => 50,
			'retention_days' => 14,
			'slow_query_ms' => 500,
			'access' => true,
			'redact' => ['password', 'passwd', 'secret', 'token', 'authorization', 'cookie', 'csrf'],
			'context' => ['ip' => false, 'user_agent' => false, 'user_id' => false],
		];

		try {
			$configured = function_exists('config_app') ? config_app('log') : null;
			self::$config = array_replace_recursive($defaults, is_array($configured) ? $configured : []);
		} catch (\Throwable $e) {
			self::$config = $defaults; // enabled=false → fully inert
		}

		return self::$config;
	}

	private static function handler(): FileHandler
	{
		if (self::$handler instanceof FileHandler) {
			return self::$handler;
		}

		$config = self::config();
		$formatter = strtolower((string) ($config['format'] ?? 'json')) === 'text'
			? new TextFormatter()
			: new JsonFormatter();

		$dir = self::logDir();
		self::assertOutsideDocroot($dir);

		self::$handler = new FileHandler(
			$dir,
			$formatter,
			max(1, (int) ($config['max_size_mb'] ?? 50)) * 1024 * 1024,
			max(1, (int) ($config['retention_days'] ?? 14)),
			(bool) ($config['split_channels'] ?? false)
		);

		return self::$handler;
	}

	/**
	 * @param array<string,mixed> $context
	 * @return array<string,mixed>
	 */
	private static function buildRecord(string $level, string $channel, string $message, array $context): array
	{
		$record = [
			'ts' => date('c'),
			'env' => self::appEnv(),
			'level' => $level,
			'channel' => $channel,
			'msg' => $message,
			'request_id' => self::requestId(),
		];

		// Promote well-known correlation fields to the top level.
		foreach (['file', 'line', 'route', 'method', 'uri', 'status', 'duration_ms', 'conn', 'driver', 'host', 'port', 'dbname'] as $key) {
			if (array_key_exists($key, $context)) {
				$record[$key] = $context[$key];
				unset($context[$key]);
			}
		}

		$record['context'] = self::redact($context) + self::requestContext();

		foreach ($record as $key => $value) {
			if ($value === null || ($value === [] && $key === 'context')) {
				unset($record[$key]);
			}
		}

		return $record;
	}

	/**
	 * @param array<string,mixed> $context
	 * @return array<string,mixed>
	 */
	private static function redact(array $context): array
	{
		$redact = array_map('strtolower', (array) (self::config()['redact'] ?? []));

		foreach ($context as $key => $value) {
			if (in_array(strtolower((string) $key), $redact, true)) {
				$context[$key] = '***';
				continue;
			}
			if (is_array($value)) {
				$context[$key] = self::redact($value);
			}
		}

		return $context;
	}

	/** @return array<string,mixed> */
	private static function requestContext(): array
	{
		$ctx = self::config()['context'] ?? [];
		$context = [];

		if (!empty($ctx['ip']) && isset($_SERVER['REMOTE_ADDR'])) {
			$context['ip'] = (string) $_SERVER['REMOTE_ADDR'];
		}
		if (!empty($ctx['user_agent']) && isset($_SERVER['HTTP_USER_AGENT'])) {
			$context['user_agent'] = (string) $_SERVER['HTTP_USER_AGENT'];
		}
		if (!empty($ctx['user_id']) && isset($_SESSION['user_id'])) {
			$context['user_id'] = $_SESSION['user_id'];
		}

		return $context;
	}

	private static function severity(string $level): int
	{
		return self::LEVELS[self::normalizeLevel($level)] ?? 1;
	}

	private static function appEnv(): string
	{
		try {
			return function_exists('config_app') ? (string) (config_app('app_env') ?? 'unknown') : 'unknown';
		} catch (\Throwable $e) {
			return 'unknown';
		}
	}

	private static function assertOutsideDocroot(string $dir): void
	{
		if (self::$insideDocrootWarned) {
			return;
		}

		$docroot = $_SERVER['DOCUMENT_ROOT'] ?? '';
		if ($docroot === '') {
			return;
		}

		$realDir = realpath($dir) ?: $dir;
		$realDoc = realpath($docroot) ?: $docroot;

		if (str_starts_with(str_replace('\\', '/', $realDir) . '/', rtrim(str_replace('\\', '/', $realDoc), '/') . '/')) {
			self::$insideDocrootWarned = true;
			self::write('warning', 'app', 'Log directory is inside DOCUMENT_ROOT; ensure web-server deny rules are active', ['dir' => $dir]);
		}
	}

	private static function isAbsolute(string $path): bool
	{
		return str_starts_with($path, '/') || (bool) preg_match('#^[A-Za-z]:[\\\\/]#', $path);
	}
}
