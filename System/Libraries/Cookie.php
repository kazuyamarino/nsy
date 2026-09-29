<?php

declare(strict_types=1);

namespace System\Libraries;

/**
 * NSY cookie helper (native replacement for josantonius/cookie).
 *
 * Thin, secure-by-default wrapper over PHP's setcookie()/$_COOKIE. It never
 * mutates $_COOKIE on write (only the browser does that), so reads stay
 * predictable within the same request; delete() removes the superglobal entry
 * for convenience and expires the cookie.
 */
class Cookie
{
	/** @var array<string,mixed> */
	private static array $defaults = [
		'expires'  => 0,
		'path'     => '/',
		'domain'   => '',
		'secure'   => false,
		'httponly' => true,
		'samesite' => 'Lax',
	];

	/**
	 * Merge global defaults used by every set()/delete() call.
	 *
	 * @param array<string,mixed> $options
	 */
	public static function configure(array $options): void
	{
		self::$defaults = array_merge(self::$defaults, $options);
	}

	/** @return array<string,mixed> */
	public static function defaults(): array
	{
		return self::$defaults;
	}

	/**
	 * Send a cookie.
	 *
	 * Accepts either set($name,$value,$expires,$options) or
	 * set($name,$value,$options).
	 *
	 * @param int|array<string,mixed> $expires unix timestamp, or the options array
	 * @param array<string,mixed>     $options path/domain/secure/httponly/samesite
	 */
	public static function set(string $name, string $value, int|array $expires = 0, array $options = []): bool
	{
		if (headers_sent()) {
			return false;
		}

		if (is_array($expires)) {
			$options = $expires;
			$expires = (int) (self::$defaults['expires'] ?? 0);
		}

		$opts = array_merge(self::$defaults, $options);
		$opts['expires'] = (int) ($expires > 0 ? $expires : ($opts['expires'] ?? 0));

		return @setcookie($name, $value, $opts);
	}

	public static function get(string $name, mixed $default = null): mixed
	{
		return array_key_exists($name, $_COOKIE) ? $_COOKIE[$name] : $default;
	}

	public static function has(string $name): bool
	{
		return array_key_exists($name, $_COOKIE);
	}

	/** @return array<string,mixed> */
	public static function all(): array
	{
		return $_COOKIE;
	}

	/**
	 * Expire a cookie and drop it from $_COOKIE.
	 *
	 * @param array<string,mixed> $options
	 */
	public static function delete(string $name, array $options = []): bool
	{
		$ok = true;

		if (!headers_sent()) {
			$opts = array_merge(self::$defaults, $options, ['expires' => time() - 3600]);
			$ok   = @setcookie($name, '', $opts);
		}

		unset($_COOKIE[$name]);

		return $ok;
	}
}
