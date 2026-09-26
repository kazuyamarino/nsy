<?php
declare(strict_types=1);

namespace System\Libraries\Log\Formatter;

/**
 * One JSON object per line (JSONL) — default format.
 */
final class JsonFormatter
{
	/**
	 * @param array<string,mixed> $record
	 */
	public function format(array $record): string
	{
		$json = json_encode($record, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

		return $json === false ? '{"level":"error","msg":"log encoding failed"}' : $json;
	}
}
