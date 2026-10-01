<?php

declare(strict_types=1);

namespace System\Middlewares;

/**
 * Security Middleware for NSY Framework — Implementation Engine
 * CSRF is single-sourced via NSY Router facade: use Route::csrf(), Route::csrfField(), Route::validateCsrf()
 * (see docs/README_NSY_ROUTER.md#security). Direct SecurityMiddleware CSRF calls remain for BC/internal (DB.php).
 * Sanitization/XSS remains here as the direct API. Rate limiting is a file-backed
 * fixed-window limiter (rateLimit() / hit()).
 */
class SecurityMiddleware
{
	/** @var array<string,mixed> */
	private array $config;

	public function __construct(array $config = [])
	{
		// Only the rate-limit keys are enforced by this class; CSRF and input
		// sanitisation are exposed as static methods below.
		$this->config = array_merge([
			'rate_limit' => 60,
			'rate_window' => 60,
		], $config);
	}

	private static function ensureSession(): void
	{
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
			session_start();
		}
	}

	// -----------------------------------------------------------------
	// CSRF — Core
	// -----------------------------------------------------------------

	/**
	 * Derive the HTML field name from a token key so csrfField()/csrfMeta() and
	 * the validators agree (csrf_token → token, _csrf_token → _token).
	 */
	public static function csrfFieldName(string $key): string
	{
		if ($key === '_csrf_token') {
			return '_token';
		}

		return str_starts_with($key, 'csrf_') ? substr($key, 5) : $key;
	}

	/**
	 * Generate CSRF token
	 * @param string $key Session key (default csrf_token)
	 * @param int|null $expiration Ignored at generate time, kept for BC (expiration is checked on validate)
	 * @param bool $enableOriginCheck Embed IP+UA hash
	 */
	public static function generateCSRFToken(string $key = 'csrf_token', ?int $expiration = null, bool $enableOriginCheck = false): string
	{
		self::ensureSession();

		// Reuse a still-valid token so several csrfField()/csrfMeta() calls on
		// one page (or several forms sharing a key) yield the SAME token instead
		// of overwriting each other. A fresh token is issued when none exists,
		// when it has expired, or when origin binding is requested but the stored
		// token is not (or no longer) bound to this client.
		$existing = $_SESSION[$key] ?? null;
		if (is_string($existing) && $existing !== '') {
			$expired = $expiration !== null && self::isTokenExpired($existing, $expiration);
			$originMismatch = $enableOriginCheck
				&& (!self::tokenHasOrigin($existing) || !self::validateOrigin($existing));

			if (!$expired && !$originMismatch) {
				return $existing;
			}
		}

		$extra = '';
		if ($enableOriginCheck) {
			$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
			$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
			$extra = hash('sha256', $ip . $ua);
		}

		$timestamp = time();
		$randomData = bin2hex(random_bytes(32));
		$tokenData = $timestamp . $extra . $randomData;
		$token = base64_encode($tokenData);

		$_SESSION[$key] = $token;

		return $token;
	}

	/**
	 * Does the token embed an origin (IP+UA) hash? Non-origin tokens are
	 * timestamp (10) + random hex (64) = 74 chars; origin tokens add 64 more.
	 */
	private static function tokenHasOrigin(string $token): bool
	{
		$decoded = base64_decode($token, true);

		return $decoded !== false && strlen($decoded) > 74;
	}

	public static function generateCSRFTokenForKey(string $key, bool $enableOriginCheck = false): string
	{
		// Store under the same key the validators use (no implicit prefix).
		return self::generateCSRFToken($key, null, $enableOriginCheck);
	}

	private static function isTokenExpired(string $token, int $timeSpan): bool
	{
		$decoded = base64_decode($token, true);
		if ($decoded === false || strlen($decoded) < 10) {
			return true;
		}
		$timestamp = substr($decoded, 0, 10);
		if (!ctype_digit($timestamp)) {
			return true;
		}
		return (intval($timestamp) + $timeSpan) < time();
	}

	private static function validateOrigin(string $token): bool
	{
		$decoded = base64_decode($token, true);
		if ($decoded === false || strlen($decoded) < 74) {
			return false;
		}
		$storedHash = substr($decoded, 10, 64);
		$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
		$ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
		$currentHash = hash('sha256', $ip . $ua);

		return hash_equals($storedHash, $currentHash);
	}

	/**
	 * Core CSRF check
	 */
	public static function checkCSRFToken(string $key, string $token, bool $throwException = false, ?int $timeSpan = null, bool $multiple = false): bool
	{
		self::ensureSession();

		if (!isset($_SESSION[$key]) || $_SESSION[$key] === '') {
			if ($throwException) {
				throw new \Exception('Missing CSRF session token');
			}
			return false;
		}

		$sessionToken = $_SESSION[$key];

		if (!hash_equals($sessionToken, $token)) {
			if ($throwException) {
				throw new \Exception('Invalid CSRF token');
			}
			return false;
		}

		if ($timeSpan !== null && self::isTokenExpired($sessionToken, $timeSpan)) {
			if ($throwException) {
				throw new \Exception('CSRF token has expired');
			}
			return false;
		}

		// Consume the token only after a successful match, so a failed attempt
		// cannot invalidate a legitimate token.
		if (!$multiple) {
			$_SESSION[$key] = '';
		}

		return true;
	}

	// -----------------------------------------------------------------
	// CSRF — Public API (used by RouterHelper)
	// -----------------------------------------------------------------

	public static function csrfField(string $key = 'csrf_token', ?int $expiration = null, bool $enableOriginCheck = false): string
	{
		$token = self::generateCSRFToken($key, $expiration, $enableOriginCheck);
		$fieldName = self::csrfFieldName($key);
		return '<input type="hidden" name="' . htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
	}

	public static function csrfMeta(string $key = 'csrf_token', ?int $expiration = null, bool $enableOriginCheck = false): string
	{
		$token = self::generateCSRFToken($key, $expiration, $enableOriginCheck);
		return '<meta name="csrf-token" content="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
	}

	/**
	 * Unified validation — called by RouterHelper::validateCsrf
	 */
	public static function validateCSRFToken(?string $token, string $key = 'csrf_token', ?int $expiration = null, bool $originCheck = false): bool
	{
		self::ensureSession();

		if (empty($token)) {
			return false;
		}

		$valid = self::checkCSRFToken($key, $token, false, $expiration, false);
		if (!$valid) {
			return false;
		}

		if ($originCheck && !self::validateOrigin($token)) {
			return false;
		}

		return true;
	}

	/**
	 * Advanced validation — used by System/Core/DB.php
	 */
	public static function validateAdvancedCSRF(string $key, array $origin, bool $throwException = false, ?int $timeSpan = null, bool $multiple = false, bool $enableOriginCheck = false): bool
	{
		self::ensureSession();

		$fieldName = self::csrfFieldName($key);
		$token = $origin[$fieldName] ?? $origin[$key] ?? '';
		if (empty($token) || !is_string($token)) {
			if ($throwException) {
				throw new \Exception('Missing CSRF form token');
			}
			return false;
		}

		if (!self::checkCSRFToken($key, $token, $throwException, $timeSpan, $multiple)) {
			return false;
		}

		if ($enableOriginCheck && !self::validateOrigin($token)) {
			if ($throwException) {
				throw new \Exception('Form origin does not match token origin');
			}
			return false;
		}

		return true;
	}

	/**
	 * Factory for BC — not used by RouterHelper currently, kept for compatibility
	 */
	public static function createCSRFProtection(bool $enableOriginCheck = false): object
	{
		return new class($enableOriginCheck) {
			private bool $enableOriginCheck;
			public function __construct(bool $enableOriginCheck = false) { $this->enableOriginCheck = $enableOriginCheck; }
			public function enableOriginCheck(): void { $this->enableOriginCheck = true; }
			public function generate(string $key): string { return SecurityMiddleware::generateCSRFTokenForKey($key, $this->enableOriginCheck); }
			public function check(string $key, array $origin, bool $throwException = false, ?int $timeSpan = null, bool $multiple = false): bool {
				return SecurityMiddleware::validateAdvancedCSRF($key, $origin, $throwException, $timeSpan, $multiple, $this->enableOriginCheck);
			}
		};
	}

	// -----------------------------------------------------------------
	// Input sanitization — consolidated around validateAndSanitize
	// -----------------------------------------------------------------

	/**
	 * Normalise a single scalar input value: trim + strip control characters.
	 *
	 * It deliberately does NOT HTML-escape or strip slashes — escaping is an
	 * output concern (do it when you print), and escaping on input both
	 * double-encodes values and corrupts legitimate backslashes. Pass explicit
	 * options to validateAndSanitize() if you really need those behaviours.
	 */
	public static function sanitizeInput(mixed $data = ''): string
	{
		// Only scalars can be safely cast to string (arrays/objects would warn or throw)
		if (!is_scalar($data)) {
			return '';
		}
		$data = (string) $data;

		return self::validateAndSanitize($data, [
			'trim' => true,
			'strip_control_chars' => true,
			'strip_slashes' => false,
			'html_escape' => false,
			'xss_clean' => false,
		]);
	}

	public static function sanitizeForm(mixed $form = ''): mixed
	{
		if (is_array($form)) {
			foreach ($form as $key => $value) {
				if (is_string($value) || is_array($value) || is_object($value)) {
					$form[$key] = self::sanitizeForm($value);
				}
			}
			return $form;
		}
		if (is_object($form)) {
			foreach (get_object_vars($form) as $key => $value) {
				if (is_string($value) || is_array($value) || is_object($value)) {
					$form->$key = self::sanitizeForm($value);
				}
			}
			return $form;
		}
		if (is_string($form)) {
			return self::sanitizeInput($form);
		}
		return $form;
	}

	public static function cleanXSS(mixed $data): mixed
	{
		static $antiXSS = null;
		if ($antiXSS === null && class_exists('voku\helper\AntiXSS')) {
			$antiXSS = new \voku\helper\AntiXSS();
		}

		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = self::cleanXSS($value);
			}
			return $data;
		}

		if (!is_string($data)) {
			return $data;
		}

		if ($antiXSS !== null) {
			return $antiXSS->xss_clean($data);
		}

		// Fallback (no voku/anti-xss): drop tags only. Do NOT htmlspecialchars
		// here — escaping is validateAndSanitize()'s html_escape step, and doing
		// both would double-encode the text.
		return strip_tags($data);
	}

	public static function validateAndSanitize(mixed $data, array $options = []): mixed
	{
		$defaults = [
			'trim' => true,
			'strip_control_chars' => false,
			'strip_slashes' => true,
			'html_escape' => true,
			'xss_clean' => false,
			'max_length' => null,
			'allowed_tags' => null,
		];
		$options = array_merge($defaults, $options);

		if (is_string($data)) {
			if ($options['trim']) {
				$data = trim($data);
			}
			if ($options['strip_control_chars']) {
				$data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data) ?? $data;
			}
			if ($options['strip_slashes']) {
				$data = stripslashes($data);
			}
			if ($options['max_length'] !== null && is_int($options['max_length']) && strlen($data) > $options['max_length']) {
				$data = substr($data, 0, $options['max_length']);
			}
			if ($options['xss_clean']) {
				$data = self::cleanXSS($data);
			}
			if ($options['html_escape']) {
				if (!empty($options['allowed_tags']) && is_string($options['allowed_tags'])) {
					// strip_tags() alone keeps allowed tags verbatim, including
					// scriptable attributes (e.g. <a href="javascript:…">). Run the
					// allowed-tag output through AntiXSS; if it is unavailable, escape
					// everything so nothing dangerous survives.
					$data = strip_tags($data, $options['allowed_tags']);
					$data = class_exists('voku\helper\AntiXSS')
						? self::cleanXSS($data)
						: htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
				} else {
					$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
				}
			}
			return $data;
		}
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = self::validateAndSanitize($value, $options);
			}
		} elseif (is_object($data)) {
			foreach (get_object_vars($data) as $key => $value) {
				$data->$key = self::validateAndSanitize($value, $options);
			}
		}
		return $data;
	}

	// -----------------------------------------------------------------
	// Rate limiting — fixed window, file-backed per client IP + bucket
	// -----------------------------------------------------------------

	/**
	 * Enforce this instance's rate limit for the given bucket.
	 * Config keys: rate_limit (max hits) and rate_window (seconds).
	 *
	 * @param  string   $bucket        Logical bucket, e.g. 'login'
	 * @param  int|null $maxAttempts   Override config('rate_limit')
	 * @param  int|null $windowSeconds Override config('rate_window')
	 * @return bool  true = allowed, false = throttled
	 */
	public function rateLimit(string $bucket = 'default', ?int $maxAttempts = null, ?int $windowSeconds = null): bool
	{
		$max = $maxAttempts ?? (int) ($this->config['rate_limit'] ?? 60);
		$window = $windowSeconds ?? (int) ($this->config['rate_window'] ?? 60);

		return self::hit(self::clientKey() . ':' . $bucket, $max, $window);
	}

	/**
	 * Count one hit for $key in the current fixed window.
	 * Fails OPEN (returns true) when the counter cannot be stored, so a storage
	 * problem never locks users out.
	 *
	 * @return bool  true = allowed, false = limit exceeded
	 */
	public static function hit(string $key, int $maxAttempts, int $windowSeconds): bool
	{
		$max = max(1, $maxAttempts);
		$window = max(1, $windowSeconds);

		$file = self::storageDir() . '/' . hash('sha256', $key) . '.json';
		$fh = @fopen($file, 'c+');
		if ($fh === false) {
			return true;
		}

		try {
			if (!flock($fh, LOCK_EX)) {
				return true;
			}

			$data = json_decode((string) stream_get_contents($fh), true);
			$now = time();

			if (!is_array($data) || ($now - (int) ($data['start'] ?? 0)) >= $window) {
				$data = ['start' => $now, 'count' => 0];
			}

			$data['count'] = (int) ($data['count'] ?? 0) + 1;

			ftruncate($fh, 0);
			rewind($fh);
			fwrite($fh, json_encode($data));
			fflush($fh);
			flock($fh, LOCK_UN);

			return $data['count'] <= $max;
		} finally {
			fclose($fh);
		}
	}

	private static function storageDir(): string
	{
		$dir = dirname(__DIR__) . '/Storage/ratelimit';

		if (!is_dir($dir)) {
			@mkdir($dir, 0775, true);
		}

		return $dir;
	}

	private static function clientKey(): string
	{
		return (string) ($_SERVER['REMOTE_ADDR'] ?? 'cli');
	}
}
