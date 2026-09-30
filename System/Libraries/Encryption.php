<?php

declare(strict_types=1);

namespace System\Libraries;

/**
 * NSY encryption helper.
 *
 * New payloads use AES-256-GCM (authenticated) with a random 12-byte IV per
 * call and a versioned envelope:
 *
 *     v1:<base64( iv[12] | tag[16] | ciphertext )>
 *
 * The key comes from the ENCRYPTION_KEY env value (see env.php) unless an
 * explicit key is passed. decrypt() also understands the legacy AES-256-CBC
 * payload produced by the old string_encrypt() helper, so existing data keeps
 * decrypting.
 */
class Encryption
{
	private const CIPHER = 'aes-256-gcm';
	private const PREFIX = 'v1:';
	private const IV_LEN = 12;
	private const TAG_LEN = 16;

	/** Legacy string_encrypt() material — kept only so old payloads decrypt. */
	private const LEGACY_KEY = 'Kazu#Key!';
	private const LEGACY_IV  = '!VI@_$3';
	private const LEGACY_CIPHER = 'aes-256-cbc';

	/**
	 * Derive a 32-byte raw key from ENCRYPTION_KEY (or an explicit value).
	 *
	 * @throws \RuntimeException when no key is configured
	 */
	public static function key(?string $key = null): string
	{
		$key ??= (string) (config_env('ENCRYPTION_KEY') ?? '');

		if ($key === '') {
			throw new \RuntimeException('ENCRYPTION_KEY is not configured (env.php).');
		}

		return hash('sha256', $key, true);
	}

	/**
	 * Encrypt a plaintext string into a versioned, authenticated envelope.
	 *
	 * @throws \RuntimeException on configuration or cipher failure
	 */
	public static function encrypt(string $plain, ?string $key = null): string
	{
		$keyBytes = self::key($key);
		$iv       = random_bytes(self::IV_LEN);
		$tag      = '';

		$cipher = openssl_encrypt(
			$plain,
			self::CIPHER,
			$keyBytes,
			OPENSSL_RAW_DATA,
			$iv,
			$tag,
			'',
			self::TAG_LEN
		);

		if ($cipher === false) {
			throw new \RuntimeException('Encryption failed: ' . (openssl_error_string() ?: 'unknown error'));
		}

		return self::PREFIX . base64_encode($iv . $tag . $cipher);
	}

	/**
	 * Decrypt a payload. Handles both v1 (GCM) and legacy CBC envelopes.
	 *
	 * @return string|null plaintext, or null when the payload is invalid/tampered
	 */
	public static function decrypt(string $payload, ?string $key = null): ?string
	{
		if (!str_starts_with($payload, self::PREFIX)) {
			return self::decryptLegacy($payload);
		}

		$keyBytes = self::key($key);
		$raw      = base64_decode(substr($payload, strlen(self::PREFIX)), true);

		if ($raw === false || strlen($raw) < self::IV_LEN + self::TAG_LEN) {
			return null;
		}

		$iv     = substr($raw, 0, self::IV_LEN);
		$tag    = substr($raw, self::IV_LEN, self::TAG_LEN);
		$cipher = substr($raw, self::IV_LEN + self::TAG_LEN);

		$plain = openssl_decrypt($cipher, self::CIPHER, $keyBytes, OPENSSL_RAW_DATA, $iv, $tag);

		return $plain === false ? null : $plain;
	}

	/**
	 * Is the payload in the new versioned (v1) format?
	 */
	public static function isEncrypted(string $payload): bool
	{
		return str_starts_with($payload, self::PREFIX);
	}

	/**
	 * Decrypt a payload produced by the legacy string_encrypt() helper.
	 * Uses the original hard-coded key/IV, so no ENCRYPTION_KEY is required.
	 */
	private static function decryptLegacy(string $payload): ?string
	{
		$decoded = base64_decode($payload, true);
		if ($decoded === false) {
			return null;
		}

		$key = hash('sha256', self::LEGACY_KEY);
		$iv  = substr(hash('sha256', self::LEGACY_IV), 0, 16);

		$plain = openssl_decrypt($decoded, self::LEGACY_CIPHER, $key, 0, $iv);

		return $plain === false ? null : $plain;
	}
}
