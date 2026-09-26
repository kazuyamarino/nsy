<?php
declare(strict_types=1);

namespace System\Libraries\Log\Handler;

/**
 * Writes records to daily files with size-based rotation and retention prune.
 *
 * Layout (default):  <dir>/nsy-YYYY-MM-DD.log
 * Split channels:    <dir>/<channel>/nsy-YYYY-MM-DD.log
 * Rotated:           <dir>/nsy-YYYY-MM-DD.<n>.log
 */
final class FileHandler
{
	public function __construct(
		private string $dir,
		private object $formatter,
		private int $maxBytes = 52428800,
		private int $retentionDays = 14,
		private bool $splitChannels = false
	) {
	}

	/**
	 * @param array<string,mixed> $record
	 */
	public function handle(string $channel, array $record): void
	{
		$directory = $this->directoryFor($channel);
		$this->ensureDir($directory);

		$path = $directory . '/nsy-' . date('Y-m-d') . '.log';
		$line = $this->formatter->format($record) . "\n";

		$this->rotateIfNeeded($path, strlen($line));
		$this->append($path, $line);
		$this->prune($directory);
	}

	private function directoryFor(string $channel): string
	{
		if (!$this->splitChannels) {
			return $this->dir;
		}

		$safe = (string) preg_replace('/[^A-Za-z0-9_-]/', '', $channel);

		return $this->dir . '/' . ($safe !== '' ? $safe : 'app');
	}

	private function ensureDir(string $directory): void
	{
		if (!is_dir($directory)) {
			@mkdir($directory, 0775, true);
		}
	}

	private function rotateIfNeeded(string $path, int $incoming): void
	{
		if (!is_file($path)) {
			return;
		}

		$size = @filesize($path);
		if ($size === false || $size + $incoming <= $this->maxBytes) {
			return;
		}

		$i = 1;
		do {
			$target = (string) preg_replace('/\.log$/', '.' . $i . '.log', $path);
			$i++;
		} while (file_exists($target) && $i < 10000);

		@rename($path, $target);
	}

	private function append(string $path, string $line): void
	{
		@file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
	}

	private function prune(string $directory): void
	{
		if ($this->retentionDays <= 0 || !is_dir($directory)) {
			return;
		}

		$cutoff = time() - ($this->retentionDays * 86400);
		$entries = @scandir($directory);
		if ($entries === false) {
			return;
		}

		foreach ($entries as $entry) {
			if ($entry === '.' || $entry === '..' || !str_starts_with($entry, 'nsy-') || !str_contains($entry, '.log')) {
				continue;
			}

			$file = $directory . '/' . $entry;
			$mtime = @filemtime($file);
			if ($mtime !== false && $mtime < $cutoff) {
				@unlink($file);
			}
		}
	}
}
