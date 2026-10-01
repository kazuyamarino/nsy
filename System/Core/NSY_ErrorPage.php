<?php

declare(strict_types=1);

namespace System\Core;

/**
 * Renders optional HTML error pages from System/Apps/Templates/Errors.
 *
 * A template is a plain PHP file named after the status code (404.php,
 * 500.php, 503.php). It receives $code and $message (plus any extra data) and
 * may include the shared _layout.php. When no template exists, render()
 * returns false so the caller can fall back to its own output.
 */
class NSY_ErrorPage
{
	/**
	 * Directory that holds the error templates.
	 *
	 * @return string
	 */
	public static function dir(): string
	{
		return dirname(__DIR__) . '/Apps/Templates/Errors';
	}

	/**
	 * Render a status page when a template for the code exists.
	 *
	 * @param  int                 $code
	 * @param  string              $message
	 * @param  array<string,mixed> $data Extra variables exposed to the template
	 * @return bool  True when a template was rendered
	 */
	public static function render(int $code, string $message = '', array $data = []): bool
	{
		$file = self::dir() . '/' . $code . '.php';
		if (!is_file($file)) {
			return false;
		}

		extract($data, EXTR_SKIP);

		ob_start();
		include $file;

		echo (string) ob_get_clean();

		return true;
	}
}
