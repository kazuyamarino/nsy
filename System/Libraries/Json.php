<?php

declare(strict_types=1);

namespace System\Libraries;

/**
 * NSY JSON file helper (native replacement for josantonius/json).
 *
 * Static utility for reading/writing JSON files and encode/decode values.
 * Writes are atomic: data goes to a sibling temp file then is renamed into
 * place, so a crash never leaves a half-written file.
 */
class Json
{
	public const ENCODE_FLAGS = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

	/**
	 * Decode a JSON string to an array/object.
	 *
	 * @param  string $json
	 * @param  bool   $assoc decode objects as associative arrays
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException on malformed JSON
	 */
	public static function decode(string $json, bool $assoc = true): mixed
	{
		$decoded = json_decode($json, $assoc);

		if (json_last_error() !== JSON_ERROR_NONE) {
			throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
		}

		return $decoded;
	}

	/**
	 * Encode a value to a JSON string.
	 *
	 * @param  mixed $data
	 * @param  int   $flags
	 * @return string
	 *
	 * @throws \InvalidArgumentException when the value cannot be encoded
	 */
	public static function encode(mixed $data, int $flags = self::ENCODE_FLAGS): string
	{
		$json = json_encode($data, $flags);

		if ($json === false) {
			throw new \InvalidArgumentException('Unable to encode JSON: ' . json_last_error_msg());
		}

		return $json;
	}

	/**
	 * Read and decode a JSON file.
	 *
	 * @param  string $path
	 * @param  bool   $assoc
	 * @return mixed
	 *
	 * @throws \InvalidArgumentException when the file is missing/unreadable
	 * @throws \RuntimeException         when the read fails
	 */
	public static function read(string $path, bool $assoc = true): mixed
	{
		if (!is_file($path) || !is_readable($path)) {
			throw new \InvalidArgumentException("JSON file not readable: {$path}");
		}

		$contents = file_get_contents($path);
		if ($contents === false) {
			throw new \RuntimeException("Unable to read JSON file: {$path}");
		}

		return self::decode($contents, $assoc);
	}

	/**
	 * Atomically encode and write a value to a JSON file.
	 *
	 * @param  string $path
	 * @param  mixed  $data
	 * @param  int    $flags
	 * @return bool
	 */
	public static function write(string $path, mixed $data, int $flags = self::ENCODE_FLAGS): bool
	{
		$json = self::encode($data, $flags);

		$dir = dirname($path);
		if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
			return false;
		}

		$tmp = $path . '.' . bin2hex(random_bytes(6)) . '.tmp';

		if (@file_put_contents($tmp, $json, LOCK_EX) === false) {
			@unlink($tmp);
			return false;
		}

		if (!@rename($tmp, $path)) {
			@unlink($tmp);
			return false;
		}

		return true;
	}

	/**
	 * Does a readable JSON file exist at the given path?
	 */
	public static function has(string $path): bool
	{
		return is_file($path);
	}
}
