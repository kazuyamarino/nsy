<?php
declare(strict_types=1);

namespace System\Libraries\Log;

use Psr\Log\LoggerInterface;

/**
 * PSR-3 logger bound to a single channel.
 *
 * The full {@see LoggerInterface} is implemented (so third-party Composer
 * packages can receive it), while the stored levels stay at five:
 * debug, info, warning, error, critical.
 */
final class Logger implements LoggerInterface
{
	public function __construct(private string $channel = 'app')
	{
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function emergency($message, array $context = []): void
	{
		$this->log('emergency', $message, $context);
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function alert($message, array $context = []): void
	{
		$this->log('alert', $message, $context);
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function critical($message, array $context = []): void
	{
		$this->log('critical', $message, $context);
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function error($message, array $context = []): void
	{
		$this->log('error', $message, $context);
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function warning($message, array $context = []): void
	{
		$this->log('warning', $message, $context);
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function notice($message, array $context = []): void
	{
		$this->log('notice', $message, $context);
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function info($message, array $context = []): void
	{
		$this->log('info', $message, $context);
	}

	/**
	 * @param array<string,mixed> $context
	 */
	public function debug($message, array $context = []): void
	{
		$this->log('debug', $message, $context);
	}

	/**
	 * @param mixed                $level
	 * @param array<string,mixed>  $context
	 */
	public function log($level, $message, array $context = []): void
	{
		if ($message instanceof \Stringable) {
			$message = (string) $message;
		} elseif (!is_string($message)) {
			$message = (string) $message;
		}

		LogManager::write((string) $level, $this->channel, $message, $context);
	}
}
