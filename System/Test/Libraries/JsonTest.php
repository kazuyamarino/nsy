<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Json;

class JsonTest extends TestCase
{
	private string $dir;

	protected function setUp(): void
	{
		$this->dir = sys_get_temp_dir() . '/nsy_json_' . bin2hex(random_bytes(4));
		mkdir($this->dir, 0775, true);
	}

	protected function tearDown(): void
	{
		foreach (glob($this->dir . '/*') ?: [] as $file) {
			@unlink($file);
		}
		@rmdir($this->dir);
	}

	public function testWriteReadRoundTrip(): void
	{
		$path = $this->dir . '/data.json';

		$this->assertTrue(Json::write($path, ['a' => 1, 'b' => [2, 3]]));
		$this->assertTrue(Json::has($path));
		$this->assertSame(['a' => 1, 'b' => [2, 3]], Json::read($path));
	}

	public function testAtomicWriteLeavesNoTempFile(): void
	{
		$path = $this->dir . '/data.json';
		Json::write($path, ['x' => 1]);

		$this->assertSame([], glob($this->dir . '/*.tmp') ?: []);
	}

	public function testInvalidJsonThrows(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		Json::decode('{not valid');
	}

	public function testEncodeCompact(): void
	{
		$this->assertSame('{"a":1}', Json::encode(['a' => 1], 0));
	}
}
