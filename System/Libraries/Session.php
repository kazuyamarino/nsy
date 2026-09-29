<?php

declare(strict_types=1);

namespace System\Libraries;

/**
 * NSY session helper (native replacement for josantonius/session).
 *
 * Thin wrapper over PHP's session_* functions. `start()` accepts the same
 * options array as `session_start()` (NSY passes `session_config` from
 * System/Config/App.php). All methods are **static**.
 */
class Session
{
	/** @var array<string,mixed> */
	private static array $defaults = [
		'cookie_httponly' => true,
		'cookie_samesite' => 'Lax',
		'use_strict_mode' => true,
	];

	private const FLASH_KEY = '_flash';

	/**
	 * Start the session with the given PHP session options.
	 *
	 * Safe to call when a session is already active or headers were sent
	 * (returns without error).
	 */
	public static function start(array $options = []): bool
	{
		if (session_status() !== PHP_SESSION_NONE) {
			return true;
		}

		if (headers_sent()) {
			return false;
		}

		return @session_start(array_merge(self::$defaults, $options));
	}

	public static function isActive(): bool
	{
		return session_status() === PHP_SESSION_ACTIVE;
	}

	public static function get(string $key, mixed $default = null): mixed
	{
		return $_SESSION[$key] ?? $default;
	}

	public static function set(string $key, mixed $value): void
	{
		$_SESSION[$key] = $value;
	}

	public static function has(string $key): bool
	{
		return isset($_SESSION[$key]);
	}

	public static function remove(string ...$keys): void
	{
		foreach ($keys as $key) {
			unset($_SESSION[$key]);
		}
	}

	/** @return array<string,mixed> */
	public static function all(): array
	{
		return $_SESSION ?? [];
	}

	public static function id(): string
	{
		return session_id() ?: '';
	}

	/**
	 * Regenerate the session id (e.g. after login).
	 */
	public static function regenerate(bool $deleteOldSession = true): bool
	{
		if (!self::isActive() || headers_sent()) {
			return false;
		}

		return session_regenerate_id($deleteOldSession);
	}

	/**
	 * Destroy the session and clear $_SESSION.
	 */
	public static function destroy(): bool
	{
		$_SESSION = [];

		if (self::isActive() && !headers_sent()) {
			return session_destroy();
		}

		return false;
	}

	/**
	 * Store a one-request flash value.
	 */
	public static function flash(string $key, mixed $value): void
	{
		$_SESSION[self::FLASH_KEY][$key] = $value;
	}

	/**
	 * Read and consume a flash value.
	 */
	public static function getFlash(string $key, mixed $default = null): mixed
	{
		$value = $_SESSION[self::FLASH_KEY][$key] ?? $default;

		unset($_SESSION[self::FLASH_KEY][$key]);

		return $value;
	}
}
