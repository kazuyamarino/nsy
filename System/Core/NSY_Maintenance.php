<?php

declare(strict_types=1);

namespace System\Core;

/**
 * Application maintenance mode.
 *
 * Active when config `maintenance` is truthy OR the flag file
 * System/Storage/maintenance.flag exists (its first line becomes the message).
 * Toggle it from the CLI with `nsy down` / `nsy up`. While active, every
 * request receives a 503 unless the client IP is listed in config
 * `maintenance_allow`.
 */
class NSY_Maintenance
{
	/**
	 * Absolute path of the maintenance flag file.
	 *
	 * @return string
	 */
	public static function flagFile(): string
	{
		return dirname(__DIR__) . '/Storage/maintenance.flag';
	}

	/**
	 * Is maintenance mode currently on?
	 *
	 * @return bool
	 */
	public static function active(): bool
	{
		if (is_file(self::flagFile())) {
			return true;
		}

		return filter_var(config_app('maintenance'), FILTER_VALIDATE_BOOLEAN) === true;
	}

	/**
	 * Message shown to visitors (first line of the flag file, or a default).
	 *
	 * @return string
	 */
	public static function message(): string
	{
		if (is_file(self::flagFile())) {
			$line = trim((string) @file_get_contents(self::flagFile()));
			if ($line !== '') {
				return $line;
			}
		}

		return 'We are performing scheduled maintenance. Please check back soon.';
	}

	/**
	 * Is the current client IP allow-listed (bypasses maintenance)?
	 *
	 * @return bool
	 */
	public static function allowed(): bool
	{
		$list = trim((string) (config_app('maintenance_allow') ?? ''));
		if ($list === '') {
			return false;
		}

		$ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
		foreach (explode(',', $list) as $allow) {
			$allow = trim($allow);
			if ($allow !== '' && $allow === $ip) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Send the 503 maintenance response and stop.
	 * No-op when maintenance is off or the client is allow-listed.
	 *
	 * @return void
	 */
	public static function check(): void
	{
		if (!self::active() || self::allowed()) {
			return;
		}

		$message = self::message();

		if (!headers_sent()) {
			http_response_code(503);
			header('Retry-After: 3600');
		}

		if (wants_json()) {
			echo json(['error' => ['code' => 503, 'message' => $message]], 503);
			exit();
		}

		if (NSY_ErrorPage::render(503, $message, ['title' => 'Under Maintenance'])) {
			exit();
		}

		echo self::defaultHtml($message);
		exit();
	}

	/**
	 * Turn maintenance mode on by writing the flag file.
	 *
	 * @param  string $message Optional custom message
	 * @return bool
	 */
	public static function down(string $message = ''): bool
	{
		$file = self::flagFile();
		$dir = dirname($file);

		if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
			return false;
		}

		return @file_put_contents($file, $message) !== false;
	}

	/**
	 * Turn maintenance mode off by removing the flag file.
	 *
	 * @return bool
	 */
	public static function up(): bool
	{
		$file = self::flagFile();

		return !is_file($file) || @unlink($file);
	}

	/**
	 * Minimal built-in page, used only when the 503 template is absent.
	 */
	private static function defaultHtml(string $message): string
	{
		$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

		return '<!doctype html><html lang="en"><head><meta charset="utf-8">'
			. '<meta name="viewport" content="width=device-width, initial-scale=1">'
			. '<meta name="robots" content="noindex"><title>Under Maintenance</title></head>'
			. '<body style="margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;'
			. 'font-family:system-ui,sans-serif;background:#0F4468;color:#E4F1FB">'
			. '<main style="max-width:520px;margin:24px;padding:40px 36px;text-align:center;background:#133A56;'
			. 'border:1px solid #235a7e;border-radius:16px"><h1 style="margin:0 0 12px">Under Maintenance</h1>'
			. '<p style="margin:0;color:#BFD0DE;line-height:1.6">' . $message . '</p></main></body></html>';
	}
}
