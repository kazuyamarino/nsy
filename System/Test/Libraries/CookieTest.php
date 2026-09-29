<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Cookie;

class CookieTest extends TestCase
{
	protected function setUp(): void
	{
		$_COOKIE = [];
	}

	protected function tearDown(): void
	{
		$_COOKIE = [];
	}

	public function testGetHasAll(): void
	{
		$_COOKIE['nsy_a'] = '1';
		$_COOKIE['nsy_b'] = 'x';

		$this->assertTrue(Cookie::has('nsy_a'));
		$this->assertSame('1', Cookie::get('nsy_a'));
		$this->assertSame('def', Cookie::get('missing', 'def'));
		$this->assertArrayHasKey('nsy_b', Cookie::all());
	}

	public function testConfigureUpdatesDefaults(): void
	{
		Cookie::configure(['samesite' => 'Strict']);

		$this->assertSame('Strict', Cookie::defaults()['samesite']);

		Cookie::configure(['samesite' => 'Lax']);
	}

	public function testDeleteRemovesFromSuperglobal(): void
	{
		$_COOKIE['nsy_del'] = '1';

		Cookie::delete('nsy_del');

		$this->assertFalse(Cookie::has('nsy_del'));
	}
}
