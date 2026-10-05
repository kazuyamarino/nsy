<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Log\LogManager;

/**
 * Covers the level policy of the logging core.
 *
 * The `access` channel is gated by ACCESS_LOG_ENABLED on its own and must not be
 * filtered by LOG_LEVEL: in production the default minimum level is `warning`
 * while every access line is `info`, so a threshold check there silently
 * disabled access logging entirely.
 */
class LogManagerTest extends TestCase
{
	private string $dir;

	/** @var array<string,mixed> */
	private array $originals = [];

	protected function setUp(): void
	{
		$this->dir = sys_get_temp_dir() . '/nsy_log_' . bin2hex(random_bytes(4));
		mkdir($this->dir, 0775, true);

		$ref = new \ReflectionClass(LogManager::class);
		foreach (['config', 'configLoaded', 'handler'] as $name) {
			$this->originals[$name] = $this->staticProperty($ref, $name)->getValue();
		}
	}

	protected function tearDown(): void
	{
		$ref = new \ReflectionClass(LogManager::class);
		foreach ($this->originals as $name => $value) {
			$this->staticProperty($ref, $name)->setValue(null, $value);
		}

		foreach (glob($this->dir . '/*') ?: [] as $file) {
			@unlink($file);
		}
		@rmdir($this->dir);
	}

	public function testAccessChannelIsTheReservedName(): void
	{
		$this->assertSame('access', LogManager::ACCESS_CHANNEL);
	}

	public function testAccessLineIsWrittenWhenMinimumLevelIsWarning(): void
	{
		$this->applyConfig(['level' => 'warning', 'access' => true]);

		LogManager::access(['uri' => '/docs/router', 'method' => 'GET', 'status' => 200]);

		$log = $this->logContents();
		$this->assertStringContainsString('"channel":"access"', $log);
		$this->assertStringContainsString('"level":"info"', $log);
		$this->assertStringContainsString('"uri":"/docs/router"', $log);
	}

	public function testAccessLineSurvivesEvenAtCriticalMinimumLevel(): void
	{
		$this->applyConfig(['level' => 'critical', 'access' => true]);

		LogManager::access(['uri' => '/']);

		$this->assertStringContainsString('"channel":"access"', $this->logContents());
	}

	public function testAccessLineIsSuppressedWhenAccessLoggingIsDisabled(): void
	{
		$this->applyConfig(['level' => 'warning', 'access' => false]);

		LogManager::access(['uri' => '/']);

		$this->assertSame('', $this->logContents());
	}

	public function testApplicationChannelStillHonoursMinimumLevel(): void
	{
		$this->applyConfig(['level' => 'warning']);

		LogManager::channel('app')->info('below threshold');
		LogManager::channel('app')->warning('at threshold');

		$log = $this->logContents();
		$this->assertStringNotContainsString('below threshold', $log);
		$this->assertStringContainsString('at threshold', $log);
	}

	public function testNothingIsWrittenWhenLoggingIsDisabled(): void
	{
		$this->applyConfig(['enabled' => false, 'access' => true]);

		$this->assertFalse(LogManager::enabled());

		LogManager::access(['uri' => '/']);
		LogManager::channel('app')->critical('must not be written');

		$this->assertSame('', $this->logContents());
	}

	/**
	 * Replace the cached configuration and drop the handler so the next write
	 * builds a fresh one against the temp directory.
	 *
	 * @param array<string,mixed> $overrides
	 */
	private function applyConfig(array $overrides): void
	{
		$config = array_replace([
			'enabled' => true,
			'dir' => $this->dir,
			'level' => 'warning',
			'format' => 'json',
			'split_channels' => false,
			'max_size_mb' => 50,
			'retention_days' => 14,
			'slow_query_ms' => 500,
			'access' => true,
			'redact' => ['password', 'passwd', 'secret', 'token', 'authorization', 'cookie', 'csrf'],
			'context' => ['ip' => false, 'user_agent' => false, 'user_id' => false],
		], $overrides);

		$ref = new \ReflectionClass(LogManager::class);
		$this->staticProperty($ref, 'config')->setValue(null, $config);
		$this->staticProperty($ref, 'configLoaded')->setValue(null, true);
		$this->staticProperty($ref, 'handler')->setValue(null, null);
	}

	private function staticProperty(\ReflectionClass $ref, string $name): \ReflectionProperty
	{
		$property = $ref->getProperty($name);
		$property->setAccessible(true);

		return $property;
	}

	private function logContents(): string
	{
		$path = $this->dir . '/nsy-' . date('Y-m-d') . '.log';

		return is_file($path) ? (string) file_get_contents($path) : '';
	}
}
