<?php
declare(strict_types=1);

namespace System\Libraries\Log\Formatter;

/**
 * Single-line human-readable format, for `tail -f` / `cat`.
 * Enable with LOG_FORMAT=text.
 */
final class TextFormatter
{
	private const HEAD_KEYS = ['ts', 'level', 'channel', 'msg'];

	/**
	 * @param array<string,mixed> $record
	 */
	public function format(array $record): string
	{
		$head = sprintf(
			'%s %s [%s] %s',
			$record['ts'] ?? date('c'),
			strtoupper((string) ($record['level'] ?? 'info')),
			$record['channel'] ?? 'app',
			$record['msg'] ?? ''
		);

		$pairs = [];
		foreach ($record as $key => $value) {
			if (in_array($key, self::HEAD_KEYS, true) || $value === null || $value === '' || $value === []) {
				continue;
			}
			if (is_array($value)) {
				$value = (string) json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			}
			if (is_bool($value)) {
				$value = $value ? 'true' : 'false';
			}
			$pairs[] = $key . '=' . $value;
		}

		return $pairs === [] ? $head : $head . '  ' . implode(' ', $pairs);
	}
}
