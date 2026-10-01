<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\File;

/**
 * File helpers must stay local: exists()/delete() never perform an outbound
 * request (SSRF guard), and the remote check is opt-in via existsRemote(),
 * which rejects non-http(s) schemes.
 */
final class FileTest extends TestCase
{
	public function testExistsForLocalFile(): void
	{
		$tmp = tempnam(sys_get_temp_dir(), 'nsy_file_');
		file_put_contents($tmp, 'x');

		$this->assertTrue(File::exists($tmp));

		@unlink($tmp);
		$this->assertFalse(File::exists($tmp));
	}

	public function testExistsDoesNotFetchRemoteUrl(): void
	{
		// A URL must not trigger an outbound request.
		$this->assertFalse(File::exists('http://127.0.0.1:1/never'));
	}

	public function testExistsRemoteRejectsNonHttpSchemes(): void
	{
		$this->assertFalse(File::existsRemote('file:///etc/passwd'));
		$this->assertFalse(File::existsRemote('ftp://example.com/x'));
		$this->assertFalse(File::existsRemote('not a url'));
	}
}
