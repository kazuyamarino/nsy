<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Session;

class SessionTest extends TestCase
{
	protected function setUp(): void
	{
		if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
			@session_start();
		}
		$_SESSION = [];
	}

	protected function tearDown(): void
	{
		$_SESSION = [];
	}

	public function testSetGetHasRemove(): void
	{
		Session::set('nsy_key', 'value');

		$this->assertTrue(Session::has('nsy_key'));
		$this->assertSame('value', Session::get('nsy_key'));
		$this->assertSame('fallback', Session::get('missing', 'fallback'));

		Session::remove('nsy_key');

		$this->assertFalse(Session::has('nsy_key'));
	}

	public function testAll(): void
	{
		Session::set('a', 1);
		Session::set('b', 2);

		$this->assertSame(['a' => 1, 'b' => 2], Session::all());
	}

	public function testFlashIsConsumedOnce(): void
	{
		Session::flash('notice', 'saved');

		$this->assertSame('saved', Session::getFlash('notice'));
		$this->assertNull(Session::getFlash('notice'));
	}

	public function testStartIsSafeToCallRepeatedly(): void
	{
		$first  = Session::start();
		$second = Session::start();

		// In CLI, headers may already be sent, so start() safely returns false.
		$this->assertIsBool($first);
		$this->assertIsBool($second);
		$this->assertIsBool(Session::isActive());
	}
}
