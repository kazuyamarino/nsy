<?php

declare(strict_types=1);

namespace System\Middlewares;

/**
 * Security Middleware for NSY Framework — Implementation Engine
 * CSRF is single-sourced via NSY Router facade: use Route::csrf(), Route::csrfField(), Route::validateCsrf()
 * (see docs/README_NSY_ROUTER.md#security). Direct SecurityMiddleware CSRF calls remain for BC/internal (DB.php).
 * Sanitization/XSS remains here as the direct API.
 */
class SecurityMiddleware
{
	/** @var array<string,mixed> */
	private array $config;

	public function __construct(array $config = [])
	{
		$this->config = array_merge([
			'csrf_protection' => true,
			'rate_limit' => 60,
			'rate_window' => 60,
			'validate_input' => true,
			'block_suspicious_patterns' => true,
		], $config);
	}

	private static function ensureSession(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}

	// -----------------------------------------------------------------
	// CSRF — Core
	// -----------------------------------------------------------------

	/**
	 * Generate CSRF token
	 * @param string $key Session key (default _csrf_token)
	 * @param int|null $expiration Ignored at generate time, kept for BC (expiration is checked on validate)
	 * @param bool $enableOriginCheck Embed IP+UA hash
	 */
	public static function generateCSRFToken(string $key = '_csrf_token', ?int $expiration = null, bool $enableOriginCheck = false): string
	{
		self::ensureSession();

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

	public static function generateCSRFTokenForKey(string $key, bool $enableOriginCheck = false): string
	{
		return self::generateCSRFToken('csrf_' . $key, null, $enableOriginCheck);
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

		if (!$multiple) {
			$_SESSION[$key] = '';
		}

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

		return true;
	}

	// -----------------------------------------------------------------
	// CSRF — Public API (used by RouterHelper)
	// -----------------------------------------------------------------

	public static function csrfField(string $key = '_csrf_token', ?int $expiration = null, bool $enableOriginCheck = false): string
	{
		$token = self::generateCSRFToken($key, $expiration, $enableOriginCheck);
		$fieldName = ($key === '_csrf_token') ? '_token' : str_replace('csrf_', '', $key);
		return '<input type="hidden" name="' . htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
	}

	public static function csrfMeta(string $key = '_csrf_token', ?int $expiration = null, bool $enableOriginCheck = false): string
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

		$sessionKey = str_starts_with($key, 'csrf_') ? $key : 'csrf_' . $key;

		$valid = self::checkCSRFToken($sessionKey, $token, false, $expiration, false);
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

		$token = $origin[$key] ?? '';
		if (empty($token) || !is_string($token)) {
			if ($throwException) {
				throw new \Exception('Missing CSRF form token');
			}
			return false;
		}

		$sessionKey = str_starts_with($key, 'csrf_') ? $key : 'csrf_' . $key;

		if (!self::checkCSRFToken($sessionKey, $token, $throwException, $timeSpan, $multiple)) {
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

	public static function sanitizeInput(mixed $data = ''): string
	{
		if (!is_string($data)) {
			$data = (string) $data;
		}
		return self::validateAndSanitize($data, [
			'trim' => true,
			'strip_slashes' => true,
			'html_escape' => true,
			'xss_clean' => false,
		]);
	}

	public static function sanitizeForm(mixed $form = ''): mixed
	{
		if (is_array($form) || is_object($form)) {
			foreach ($form as $key => $value) {
				if (is_string($value) || is_array($value) || is_object($value)) {
					$form[$key] = self::sanitizeForm($value);
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
		if (!class_exists('voku\helper\AntiXSS')) {
			if (is_string($data)) {
				return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
			}
			if (is_array($data)) {
				return array_map([self::class, 'cleanXSS'], $data);
			}
			return $data;
		}

		$antiXSS = new \voku\helper\AntiXSS();

		if (is_string($data)) {
			return $antiXSS->xss_clean($data);
		}
		if (is_array($data)) {
			return array_map(static fn($item) => is_string($item) ? $antiXSS->xss_clean($item) : $item, $data);
		}

		return $data;
	}

	public static function validateAndSanitize(mixed $data, array $options = []): mixed
	{
		$defaults = [
			'trim' => true,
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
					$data = strip_tags($data, $options['allowed_tags']);
				} else {
					$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
				}
			}
			return $data;
		}
		if (is_array($data) || is_object($data)) {
			foreach ($data as $key => $value) {
				$data[$key] = self::validateAndSanitize($value, $options);
			}
		}
		return $data;
	}
}
